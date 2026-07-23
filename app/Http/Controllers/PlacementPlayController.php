<?php

namespace App\Http\Controllers;

use App\Models\IeltsPlacementAttempt;
use App\Models\IeltsPlacementAttemptAnswer;
use App\Models\PlacementTest;
use App\Models\PlacementQuestion;
use App\Services\PlacementAdaptiveEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PlacementPlayController extends Controller
{
    private PlacementAdaptiveEngine $engine;

    public function __construct(PlacementAdaptiveEngine $engine)
    {
        $this->middleware('auth');
        $this->engine = $engine;
    }

    public function entry(Request $request)
    {
        $user = $request->user();

        if ($user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = $this->findInProgressAttempt($user->id);

        // Luôn hiện Intro + Countdown, kể cả khi học viên đang có phiên làm dở
        // (thoát ra rồi quay lại) — đây là nghi thức chuẩn bị trước khi vào bài,
        // không nên bị bỏ qua. $isResuming chỉ để đổi chữ trên nút bấm.
        return view('web.placement.intro', [
            'isResuming' => (bool) $attempt,
        ]);
    }

    /**
     * Trang kiểm tra mic + loa — hoàn toàn client-side (ghi âm bằng MediaRecorder,
     * phát lại bằng thẻ <audio>), không tạo attempt, không đụng gì tới đồng hồ 10
     * phút. Chỉ là bước xác nhận thiết bị trước khi vào bài thi thật.
     */
    public function micCheck(Request $request)
    {
        $user = $request->user();

        if ($user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = $this->findInProgressAttempt($user->id);

        return view('web.placement.mic_check', [
            'isResuming' => (bool) $attempt,
        ]);
    }

    /**
     * Được gọi khi học viên bấm "Bắt đầu/Tiếp tục bài test" trên trang Intro.
     * Tạo attempt nếu chưa có (đồng hồ 10 phút bắt đầu tính từ đây), rồi RENDER
     * LUÔN nội dung trang làm bài trong CÙNG 1 response — không redirect sang
     * take() nữa. Countdown 5->1 sẽ chạy như 1 overlay CSS/JS đè lên nội dung
     * đã có sẵn trong trang, nên không còn phụ thuộc mạng ở khoảnh khắc đếm
     * về 0 (đây là lý do gộp 2 bước thành 1 request duy nhất).
     */
    public function start(Request $request)
    {
        $user = $request->user();

        if ($user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = $this->findInProgressAttempt($user->id);

        if (!$attempt) {
            $firstTest = $this->pickTestForLevel(PlacementAdaptiveEngine::FIRST_LEVEL, []);

            if (!$firstTest) {
                return redirect('/')->with('toast', [
                    'status' => 'error',
                    'title'  => 'Chưa sẵn sàng',
                    'msg'    => 'Hệ thống chưa có đề Placement Test nào được xuất bản. Vui lòng thử lại sau.',
                ]);
            }

            $attempt = IeltsPlacementAttempt::create([
                'user_id'         => $user->id,
                'status'          => 'in_progress',
                'current_step'    => 1,
                'current_test_id' => $firstTest->id,
                'test_ids_taken'  => [],
                'levels_taken'    => [],
                'scores'          => [],
                'current_level'   => PlacementAdaptiveEngine::FIRST_LEVEL,
                'started_at'      => now(),
            ]);
        }

        return $this->renderTakePage($attempt, showCountdown: true);
    }

    public function take(Request $request)
    {
        $user = $request->user();

        if ($user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = $this->findInProgressAttempt($user->id);

        if (!$attempt || !$attempt->current_test_id) {
            return redirect()->route('placement.entry');
        }

        return $this->renderTakePage($attempt, showCountdown: false);
    }

    /**
     * Dùng chung cho cả start() (sau Intro, có countdown overlay) và take()
     * (vd học viên F5 lại trang giữa bài, không cần countdown lại).
     */
    private function renderTakePage(IeltsPlacementAttempt $attempt, bool $showCountdown)
    {
        // Hết giờ mà vẫn còn đang ở trang làm bài (vd load lại trang) -> tự nộp với
        // đáp án rỗng cho đề hiện tại, rồi để logic finalize xử lý tiếp.
        if ($attempt->isTimeUp()) {
            return $this->finalizeOnTimeout($attempt);
        }

        $test = PlacementTest::with('questions')->find($attempt->current_test_id);

        if (!$test) {
            return redirect()->route('placement.entry');
        }

        $questions = $test->questions->map(fn (PlacementQuestion $q) => $this->buildPublicQuestion($q));

        return view('web.placement.take', [
            'attempt'          => $attempt,
            'test'             => $test,
            'questions'        => $questions,
            'remainingSeconds' => $attempt->remainingSeconds(),
            'showCountdown'    => $showCountdown,
        ]);
    }

    public function submitTest(Request $request)
    {
        $user = $request->user();
        $attempt = $this->findInProgressAttempt($user->id);

        if (!$attempt || !$attempt->current_test_id) {
            return redirect()->route('placement.entry');
        }

        $submittedTestId = (int) $request->input('test_id');

        if ($submittedTestId !== (int) $attempt->current_test_id) {
            // Đề bị lệch (vd mở 2 tab, hoặc submit lại đề cũ) -> quay lại trang làm bài hiện tại cho an toàn.
            return redirect()->route('placement.take');
        }

        $test = PlacementTest::with('questions')->find($attempt->current_test_id);

        if (!$test) {
            return redirect()->route('placement.entry');
        }

        $rawAnswers = $request->input('answers', []);
        $score = $this->gradeAndPersistAnswers($attempt, $test, $rawAnswers);

        return $this->advanceAttempt($attempt, $test->level, $score);
    }

    public function result(Request $request)
    {
        $user = $request->user();
        $attempt = $user->latestPlacementResult;

        $isDemoData = false;
        if (!$attempt) {
            $isDemoData = true;
            $attempt = new IeltsPlacementAttempt([
                'status'         => 'completed',
                'current_step'   => 3,
                'test_ids_taken' => [1, 2, 3],
                'scores'         => [7, 6, 8],
                'final_level'    => 'B1',
                'started_at'     => now()->subMinutes(12),
                'completed_at'   => now(),
            ]);
        }

        return view('web.placement.result', [
            'attempt'    => $attempt,
            'isDemoData' => $isDemoData,
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function findInProgressAttempt(int $userId): ?IeltsPlacementAttempt
    {
        return IeltsPlacementAttempt::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->latest('started_at')
            ->first();
    }

    /**
     * Chọn 1 đề published của đúng level, ưu tiên đề CHƯA làm trong attempt này.
     * Nếu pool cạn (không còn đề chưa dùng), fallback cho phép dùng lại đề cũ
     * để tránh việc học viên bị kẹt không làm được tiếp (tốt hơn là crash).
     */
    private function pickTestForLevel(string $level, array $excludeIds): ?PlacementTest
    {
        $test = PlacementTest::where('level', $level)
            ->where('status', 'published')
            ->whereNotIn('id', $excludeIds)
            ->inRandomOrder()
            ->first();

        if (!$test) {
            $test = PlacementTest::where('level', $level)
                ->where('status', 'published')
                ->inRandomOrder()
                ->first();
        }

        return $test;
    }

    /**
     * Chuyển câu hỏi trong DB thành dữ liệu AN TOÀN gửi cho học viên —
     * KHÔNG bao giờ để lọt trường correct_answer ra view.
     */
    private function buildPublicQuestion(PlacementQuestion $q): array
    {
        $blankCount = $q->type === 'sentence_completion'
            ? preg_match_all('/_{2,}/', $q->question_text)
            : 0;

        return [
            'id'                => $q->id,
            'type'              => $q->type,
            'question_text'     => $q->question_text,
            'blank_count'       => $blankCount,
            'options'           => $q->type === 'multiple_choice' ? ($q->options ?? []) : [],
            'image_options'     => $q->type === 'listening_image_choice'
                ? collect($q->options ?? [])->map(fn ($path, $i) => [
                    'label' => chr(65 + $i),
                    'url'   => $path ? Storage::url($path) : null,
                ])->values()->all()
                : [],
            'has_audio'         => (bool) $q->has_audio,
            'audio_url'         => $q->audio_path ? Storage::url($q->audio_path) : null,
            'word_bank'         => $q->word_bank ?? [],
            'blank_hints'       => $q->blank_hints ?? [],
            'linked_to_passage' => (bool) $q->linked_to_passage,
        ];
    }

    /**
     * Chấm điểm từng câu, lưu lại đáp án vào ielts_placement_attempt_answers,
     * trả về tổng số câu đúng (0-10).
     */
    private function gradeAndPersistAnswers(IeltsPlacementAttempt $attempt, PlacementTest $test, array $rawAnswers): int
    {
        $correctCount = 0;

        DB::transaction(function () use ($test, $attempt, $rawAnswers, &$correctCount) {
            foreach ($test->questions as $question) {
                $given = $rawAnswers[$question->id] ?? null;
                $isCorrect = $this->isAnswerCorrect($question, $given);

                if ($isCorrect) {
                    $correctCount++;
                }

                IeltsPlacementAttemptAnswer::create([
                    'attempt_id'             => $attempt->id,
                    'placement_test_id'      => $test->id,
                    'placement_question_id'  => $question->id,
                    'answer_given'           => $given,
                    'is_correct'             => $isCorrect,
                ]);
            }
        });

        return $correctCount;
    }

    private function isAnswerCorrect(PlacementQuestion $question, $given): bool
    {
        $correct = $question->correct_answer;

        if (empty($correct)) {
            return false;
        }

        switch ($question->type) {
            case 'multiple_choice':
            case 'listening_image_choice':
                $correctValue = is_array($correct) ? ($correct[0] ?? null) : $correct;
                return is_string($given) && trim($given) !== '' && trim($given) === trim((string) $correctValue);

            case 'error_correction':
                $correctValue = is_array($correct) ? ($correct[0] ?? null) : $correct;
                return is_string($given) && trim($given) !== ''
                    && strcasecmp(trim($given), trim((string) $correctValue)) === 0;

            case 'sentence_completion':
                if (!is_array($correct) || !is_array($given)) {
                    return false;
                }

                if (count($correct) === 0 || count($correct) !== count($given)) {
                    return false;
                }

                foreach ($correct as $blankIndex => $acceptedVariants) {
                    $blankGiven = trim((string) ($given[$blankIndex] ?? ''));

                    if ($blankGiven === '') {
                        return false;
                    }

                    $acceptedVariants = is_array($acceptedVariants) ? $acceptedVariants : [$acceptedVariants];
                    $matched = false;

                    foreach ($acceptedVariants as $variant) {
                        if (strcasecmp($blankGiven, trim((string) $variant)) === 0) {
                            $matched = true;
                            break;
                        }
                    }

                    if (!$matched) {
                        return false;
                    }
                }

                return true;
        }

        return false;
    }

    /**
     * Sau khi chấm xong 1 đề: cập nhật attempt, gọi engine để biết dừng hay làm tiếp.
     */
    private function advanceAttempt(IeltsPlacementAttempt $attempt, string $testLevel, int $score)
    {
        $levelsTaken = $attempt->levels_taken ?? [];
        $testIdsTaken = $attempt->test_ids_taken ?? [];
        $scores = $attempt->scores ?? [];

        $levelsTaken[] = $testLevel;
        $testIdsTaken[] = $attempt->current_test_id;
        $scores[] = $score;

        $decision = $this->engine->decideNext($levelsTaken, $scores);
        $timeUp = $attempt->isTimeUp();

        // Hết giờ ngay sau khi vừa nộp đề này -> dừng lại, dùng ngay kết quả bước
        // này (dù engine nói "continue" hay "stop") làm level cuối cùng — không có
        // đủ thời gian để làm đề tiếp theo nữa. Đây là quy tắc bổ sung cho trường
        // hợp Logic Chart gốc chưa mô tả (hết giờ giữa chừng) — CẦN XÁC NHẬN LẠI
        // với đội ngũ nếu muốn xử lý khác.
        if ($timeUp || $decision['action'] === 'stop' || count($levelsTaken) >= PlacementAdaptiveEngine::MAX_STEPS) {
            $finalLevel = $decision['action'] === 'stop'
                ? $decision['final_level']
                : ($decision['next_level'] ?? $testLevel);

            $attempt->update([
                'status'          => 'completed',
                'current_test_id' => null,
                'test_ids_taken'  => $testIdsTaken,
                'levels_taken'    => $levelsTaken,
                'scores'          => $scores,
                'final_level'     => $finalLevel,
                'current_level'   => $finalLevel,
                'completed_at'    => now(),
            ]);

            return redirect()->route('placement.result');
        }

        // Còn làm tiếp — chọn đề mới đúng level engine chỉ định.
        $nextTest = $this->pickTestForLevel($decision['next_level'], $testIdsTaken);

        if (!$nextTest) {
            // Không còn đề nào ở level cần thiết -> đành dừng sớm, lấy level hiện
            // tại làm kết quả tạm (tốt hơn là chặn học viên lại giữa chừng).
            $attempt->update([
                'status'          => 'completed',
                'current_test_id' => null,
                'test_ids_taken'  => $testIdsTaken,
                'levels_taken'    => $levelsTaken,
                'scores'          => $scores,
                'final_level'     => $testLevel,
                'current_level'   => $testLevel,
                'completed_at'    => now(),
            ]);

            return redirect()->route('placement.result');
        }

        $attempt->update([
            'current_step'    => count($levelsTaken) + 1,
            'current_test_id' => $nextTest->id,
            'test_ids_taken'  => $testIdsTaken,
            'levels_taken'    => $levelsTaken,
            'scores'          => $scores,
            'current_level'   => $decision['next_level'],
        ]);

        return redirect()->route('placement.take');
    }

    private function finalizeOnTimeout(IeltsPlacementAttempt $attempt)
    {
        // Hết giờ trong lúc học viên đang xem trang (chưa kịp bấm nộp) -> chấm đề
        // hiện tại với đáp án hiện có là rỗng (coi như bỏ trống hết), rồi finalize.
        $test = PlacementTest::with('questions')->find($attempt->current_test_id);

        if (!$test) {
            $attempt->update(['status' => 'abandoned', 'current_test_id' => null]);
            return redirect()->route('placement.entry');
        }

        $score = $this->gradeAndPersistAnswers($attempt, $test, []);

        return $this->advanceAttempt($attempt, $test->level, $score);
    }
}