<?php

namespace App\Http\Controllers;

use App\Models\IeltsPlacementAttempt;
use App\Models\IeltsPlacementAttemptAnswer;
use App\Models\PlacementSpeakingQuestion;
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
        // Chỉ trang XEM KẾT QUẢ mới bắt buộc đăng nhập. Toàn bộ luồng làm bài
        // (entry, mic-check, start, take, submit, speaking, finished) cho phép
        // KHÁCH (guest) truy cập. Laravel's `auth` middleware tự lưu URL hiện
        // tại (route('placement.result')) và tự redirect ngược lại đó sau khi
        // đăng nhập thành công — không cần code thêm cho bước quay lại này.
        $this->middleware('auth')->only(['result']);
        $this->engine = $engine;
    }

    public function entry(Request $request)
    {
        $user = $request->user();

        if ($user && $user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = $this->findActiveAttempt($request);

        if ($attempt && $attempt->status === 'speaking') {
            return redirect()->route('placement.speaking');
        }

        if ($attempt && $attempt->status === 'completed' && !$user) {
            return redirect()->route('placement.finished');
        }

        return view('web.placement.intro', [
            'isResuming' => (bool) $attempt,
        ]);
    }

    public function micCheck(Request $request)
    {
        $user = $request->user();

        if ($user && $user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = $this->findActiveAttempt($request);

        return view('web.placement.mic_check', [
            'isResuming' => (bool) $attempt,
        ]);
    }

    public function start(Request $request)
    {
        $user = $request->user();

        if ($user && $user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = $this->findActiveAttempt($request);

        if ($attempt && $attempt->status === 'speaking') {
            return redirect()->route('placement.speaking');
        }

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
                'user_id'                  => $user?->id,
                'status'                   => 'in_progress',
                'current_step'             => 1,
                'current_test_id'          => $firstTest->id,
                'test_ids_taken'           => [],
                'levels_taken'             => [],
                'scores'                   => [],
                'current_level'            => PlacementAdaptiveEngine::FIRST_LEVEL,
                'started_at'               => now(),
                'current_step_started_at'  => now(),
            ]);

            // Ghi nhớ attempt này trong session để nhận lại được xuyên suốt các
            // bước sau, kể cả khi người dùng chưa đăng nhập (không có user_id
            // để tra cứu).
            $request->session()->put('placement_attempt_id', $attempt->id);
        }

        return $this->renderTakePage($attempt, showCountdown: true);
    }

    public function take(Request $request)
    {
        $user = $request->user();

        if ($user && $user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = $this->findActiveAttempt($request);

        if ($attempt && $attempt->status === 'speaking') {
            return redirect()->route('placement.speaking');
        }

        if (!$attempt || !$attempt->current_test_id) {
            return redirect()->route('placement.entry');
        }

        return $this->renderTakePage($attempt, showCountdown: false);
    }

    private function renderTakePage(IeltsPlacementAttempt $attempt, bool $showCountdown)
    {
        if ($attempt->isTimeUp()) {
            return $this->finalizeOnTimeout($attempt);
        }

        $test = PlacementTest::with('questions')->find($attempt->current_test_id);

        if (!$test) {
            return redirect()->route('placement.entry');
        }

        return view('web.placement.take', [
            'attempt'          => $attempt,
            'test'             => $test,
            'questions'        => $test->questionsWithAudioGroups(),
            'remainingSeconds' => $attempt->remainingSeconds(),
            'showCountdown'    => $showCountdown,
        ]);
    }

    public function submitTest(Request $request)
    {
        $attempt = $this->findActiveAttempt($request);

        if (!$attempt || !$attempt->current_test_id) {
            return redirect()->route('placement.entry');
        }

        $submittedTestId = (int) $request->input('test_id');

        if ($submittedTestId !== (int) $attempt->current_test_id) {
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

    /**
     * Bước Speaking — hiển thị 1 câu hỏi RANDOM (trong các câu đang is_active),
     * xuất hiện SAU KHI đã làm xong hết các đề trắc nghiệm (status = 'speaking').
     * Không chấm điểm, chỉ lưu lại file ghi âm cho giáo viên nghe sau. Cho phép
     * cả khách chưa đăng nhập.
     */
    public function speaking(Request $request)
    {
        $attempt = $this->findAttemptByStatus($request, 'speaking');

        if (!$attempt) {
            return redirect()->route('placement.entry');
        }

        if ($attempt->isTimeUp()) {
            return $this->finalizeSpeakingOnTimeout($attempt, $request);
        }

        $question = $attempt->speaking_question_id
            ? PlacementSpeakingQuestion::find($attempt->speaking_question_id)
            : null;

        return view('web.placement.speaking', [
            'attempt'          => $attempt,
            'question'         => $question,
            'remainingSeconds' => $attempt->remainingSeconds(),
        ]);
    }

    /**
     * Hết 7 phút mà học viên chưa nộp Speaking -> tự hoàn tất attempt, không có
     * ghi âm (giống hành vi "Bỏ qua"), không chấm điểm phần này (vốn dĩ không
     * chấm điểm) nên không cần grade gì thêm.
     */
    private function finalizeSpeakingOnTimeout(IeltsPlacementAttempt $attempt, Request $request)
    {
        $attempt->status = 'completed';
        $attempt->completed_at = now();
        $attempt->save();

        if (!$request->user()) {
            return redirect()->route('placement.finished');
        }

        return redirect()->route('placement.result');
    }

    public function submitSpeaking(Request $request)
    {
        $attempt = $this->findAttemptByStatus($request, 'speaking');

        if (!$attempt) {
            return redirect()->route('placement.entry');
        }

        // Không bắt buộc ghi âm — phần này không chấm điểm, chặn cứng học viên
        // chỉ vì thiếu mic sẽ gây trải nghiệm xấu không cần thiết.
        if ($request->hasFile('recording')) {
            $path = $request->file('recording')->store('placement-tests/speaking', 'public');
            $attempt->speaking_recording_path = $path;
        }

        $attempt->status = 'completed';
        $attempt->completed_at = now();
        $attempt->save();

        // Khách chưa đăng nhập -> hiện màn "Đã hoàn thành, đăng nhập để xem kết
        // quả" thay vì đi thẳng vào /result (route đó sẽ tự bounce sang /login
        // do middleware, nhưng ta muốn 1 bước thông báo thân thiện trước đã).
        if (!$request->user()) {
            return redirect()->route('placement.finished');
        }

        return redirect()->route('placement.result');
    }

    /**
     * Màn hình trung gian dành cho KHÁCH đã làm xong toàn bộ bài test (kể cả
     * Speaking) nhưng chưa đăng nhập. Không tự lộ kết quả ở đây — chỉ thông
     * báo đã xong và mời đăng nhập/đăng ký để xem + lưu kết quả.
     */
    public function finished(Request $request)
    {
        $user = $request->user();

        // Nếu vừa đăng nhập xong thì kết quả đã có thể xem được luôn, không
        // cần dừng ở màn thông báo này nữa.
        if ($user) {
            return redirect()->route('placement.result');
        }

        $attempt = $this->findAttemptByStatus($request, 'completed');

        if (!$attempt) {
            return redirect()->route('placement.entry');
        }

        $request->session()->put('url.intended', route('placement.result'));

        return view('web.placement.finished');
    }

    // public function result(Request $request)
    // {
    //     $user = $request->user();

    //     if (!$user) {
    //         return redirect()->route('placement.request_login');
    //     }

    //     $sessionAttemptId = $request->session()->get('placement_attempt_id');

    //     if ($sessionAttemptId) {
    //         $pendingAttempt = IeltsPlacementAttempt::where('id', $sessionAttemptId)
    //             ->whereNull('user_id')
    //             ->where('status', 'completed')
    //             ->first();

    //         if ($pendingAttempt) {
    //             $pendingAttempt->update(['user_id' => $user->id]);
    //         }

    //         $request->session()->forget('placement_attempt_id');
    //     }

    //     $attempt = $user->latestPlacementResult;

    //     $isDemoData = false;
    //     if (!$attempt) {
    //         $isDemoData = true;
    //         $attempt = new IeltsPlacementAttempt([
    //             'status'         => 'completed',
    //             'current_step'   => 3,
    //             'test_ids_taken' => [1, 2, 3],
    //             'scores'         => [7, 6, 8],
    //             'final_level'    => 'B1',
    //             'started_at'     => now()->subMinutes(12),
    //             'completed_at'   => now(),
    //         ]);
    //     }

    //     return view('web.placement.result', [
    //         'attempt'    => $attempt,
    //         'isDemoData' => $isDemoData,
    //     ]);
    // }

    public function result(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('placement.request_login');
        }

        $sessionAttemptId = $request->session()->get('placement_attempt_id');

        if ($sessionAttemptId) {
            $pendingAttempt = IeltsPlacementAttempt::where('id', $sessionAttemptId)
                ->whereNull('user_id')
                ->where('status', 'completed')
                ->first();

            if ($pendingAttempt) {
                $pendingAttempt->update(['user_id' => $user->id]);
            }

            $request->session()->forget('placement_attempt_id');
        }

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

        $testBlocks = $isDemoData ? collect() : $this->buildTestBlocksForAttempt($attempt);

        return view('web.placement.result', [
            'attempt'    => $attempt,
            'isDemoData' => $isDemoData,
            'testBlocks' => $testBlocks,
        ]);
    }

    /**
     * Build chi tiết từng đề (câu hỏi + đáp án đúng) cho attempt đã hoàn thành,
     * dùng để hiển thị modal "Xem đáp án" ở trang kết quả phía học viên.
     * Logic giống PlacementResultController::detail() bên admin.
     */
    private function buildTestBlocksForAttempt(IeltsPlacementAttempt $attempt)
    {
        $testIds = $attempt->test_ids_taken ?? [];
        $tests = PlacementTest::with('questions')->whereIn('id', $testIds)->get()->keyBy('id');

        $answersByQuestion = IeltsPlacementAttemptAnswer::where('attempt_id', $attempt->id)
            ->get()
            ->keyBy('placement_question_id');

        return collect($testIds)->values()->map(function ($testId, $i) use ($tests, $answersByQuestion, $attempt) {
            $test = $tests->get($testId);

            if (!$test) {
                return null;
            }

            // keyBy trước để tránh N+1: questionsWithAudioGroups() trả về array thuần,
            // nhưng vẫn cần model gốc để gọi correctAnswerDisplay().
            $models = $test->questions->keyBy('id');

            $questions = $test->questionsWithAudioGroups()->map(function (array $public) use ($answersByQuestion, $models) {
                $record = $answersByQuestion->get($public['id']);
                $model = $models->get($public['id']);

                return [
                    'question'        => $public,
                    'given'           => $record->answer_given ?? null,
                    'is_correct'      => $record->is_correct ?? false,
                    'correct_display' => $model ? $model->correctAnswerDisplay() : '—',
                ];
            });

            return [
                'test'      => $test,
                'index'     => $i,
                'is_scored' => $attempt->isStepScored($i),
                'questions' => $questions,
            ];
        })->filter()->values();
    }
    
    private function findActiveAttempt(Request $request): ?IeltsPlacementAttempt
    {
        $user = $request->user();

        if ($user) {
            $attempt = IeltsPlacementAttempt::where('user_id', $user->id)
                ->whereIn('status', ['in_progress', 'speaking'])
                ->latest('started_at')
                ->first();

            if ($attempt) {
                return $attempt;
            }
        }

        $sessionAttemptId = $request->session()->get('placement_attempt_id');

        if ($sessionAttemptId) {
            $attempt = IeltsPlacementAttempt::where('id', $sessionAttemptId)
                ->whereIn('status', ['in_progress', 'speaking', 'completed'])
                ->first();

            if ($attempt) {
                if ($user && !$attempt->user_id) {
                    $attempt->update(['user_id' => $user->id]);
                }

                return $attempt;
            }
        }

        return null;
    }

    /**
     * Giống findActiveAttempt() nhưng lọc đúng 1 status cụ thể — dùng cho các
     * bước speaking()/submitSpeaking()/finished() để tránh nhầm sang attempt
     * đang ở trạng thái khác.
     */
    private function findAttemptByStatus(Request $request, string $status): ?IeltsPlacementAttempt
    {
        $user = $request->user();

        if ($user) {
            $attempt = IeltsPlacementAttempt::where('user_id', $user->id)
                ->where('status', $status)
                ->latest('started_at')
                ->first();

            if ($attempt) {
                return $attempt;
            }
        }

        $sessionAttemptId = $request->session()->get('placement_attempt_id');

        if ($sessionAttemptId) {
            $attempt = IeltsPlacementAttempt::where('id', $sessionAttemptId)
                ->where('status', $status)
                ->first();

            if ($attempt) {
                if ($user && !$attempt->user_id) {
                    $attempt->update(['user_id' => $user->id]);
                }

                return $attempt;
            }
        }

        return null;
    }

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

    private function pickRandomSpeakingQuestion(): ?PlacementSpeakingQuestion
    {
        return PlacementSpeakingQuestion::active()->inRandomOrder()->first();
    }

    // private function buildPublicQuestion(PlacementQuestion $q): array
    // {
    //     $blankCount = $q->type === 'sentence_completion'
    //         ? preg_match_all('/_{2,}/', $q->question_text)
    //         : 0;

    //     return [
    //         'id'                => $q->id,
    //         'type'              => $q->type,
    //         'question_text'     => $q->question_text,
    //         'blank_count'       => $blankCount,
    //         'options'           => $q->type === 'multiple_choice' ? ($q->options ?? []) : [],
    //         'image_options'     => $q->type === 'listening_image_choice'
    //             ? collect($q->options ?? [])->map(fn ($path, $i) => [
    //                 'label' => chr(65 + $i),
    //                 'url'   => $path ? Storage::url($path) : null,
    //             ])->values()->all()
    //             : [],
    //         'has_audio'         => (bool) $q->has_audio,
    //         'audio_url'         => $q->audio_path ? Storage::url($q->audio_path) : null,
    //         'word_bank'         => $q->word_bank ?? [],
    //         'blank_hints'       => $q->blank_hints ?? [],
    //     ];
    // }

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
     * Sau khi chấm xong 1 đề: cập nhật attempt, gọi engine để biết dừng hay
     * làm tiếp. Khi engine quyết định DỪNG, chuyển sang bước Speaking (nếu có
     * câu hỏi active) trước khi hoàn tất thật sự.
     */
    private function advanceAttempt(IeltsPlacementAttempt $attempt, string $testLevel, int $score)
    {
        $levelsTaken = $attempt->levels_taken ?? [];
        $testIdsTaken = $attempt->test_ids_taken ?? [];
        $scores = $attempt->scores ?? [];

        $levelsTaken[] = $testLevel;
        $testIdsTaken[] = $attempt->current_test_id;
        $scores[] = $score;

        // Nếu final_level đã bị KHOÁ từ vòng trước (rơi vào 1 trong 4 trường hợp
        // đặc biệt), đề vừa nộp chính là đề tham khảo (đề 3) -> không gọi lại
        // engine nữa, đi thẳng sang Speaking/hoàn tất với final_level đã chốt.
        if ($attempt->final_level) {
            return $this->moveToSpeakingOrFinish($attempt, $attempt->final_level, $testIdsTaken, $levelsTaken, $scores);
        }

        $decision = $this->engine->decideNext($levelsTaken, $scores);
        $timeUp = $attempt->isTimeUp();

        if ($decision['action'] === 'continue_locked') {
            $nextTest = $this->pickTestForLevel($decision['next_level'], $testIdsTaken);

            if (!$nextTest) {
                // Không có đề tham khảo để giao -> coi như hoàn tất luôn, final_level vẫn chốt đúng.
                return $this->moveToSpeakingOrFinish($attempt, $decision['final_level'], $testIdsTaken, $levelsTaken, $scores);
            }

            $attempt->update([
                'current_step'            => count($levelsTaken) + 1,
                'current_test_id'         => $nextTest->id,
                'test_ids_taken'          => $testIdsTaken,
                'levels_taken'            => $levelsTaken,
                'scores'                  => $scores,
                'current_level'           => $decision['next_level'],
                'final_level'             => $decision['final_level'],
                'scored_steps'            => count($levelsTaken), // chỉ 2 đề đầu được tính
                'current_step_started_at' => now(),
            ]);

            return redirect()->route('placement.take');
        }

        if ($timeUp || $decision['action'] === 'stop' || count($levelsTaken) >= PlacementAdaptiveEngine::MAX_STEPS) {
            $finalLevel = $decision['action'] === 'stop'
                ? $decision['final_level']
                : ($decision['next_level'] ?? $testLevel);

            return $this->moveToSpeakingOrFinish($attempt, $finalLevel, $testIdsTaken, $levelsTaken, $scores);
        }

        $nextTest = $this->pickTestForLevel($decision['next_level'], $testIdsTaken);

        if (!$nextTest) {
            return $this->moveToSpeakingOrFinish($attempt, $testLevel, $testIdsTaken, $levelsTaken, $scores);
        }

        $attempt->update([
            'current_step'            => count($levelsTaken) + 1,
            'current_test_id'         => $nextTest->id,
            'test_ids_taken'          => $testIdsTaken,
            'levels_taken'            => $levelsTaken,
            'scores'                  => $scores,
            'current_level'           => $decision['next_level'],
            'current_step_started_at' => now(),
        ]);

        return redirect()->route('placement.take');
    }

    private function moveToSpeakingOrFinish(IeltsPlacementAttempt $attempt, string $finalLevel, array $testIdsTaken, array $levelsTaken, array $scores)
    {
        $speakingQuestion = $this->pickRandomSpeakingQuestion();

        if ($speakingQuestion) {
            $attempt->update([
                'status'                   => 'speaking',
                'current_test_id'          => null,
                'test_ids_taken'           => $testIdsTaken,
                'levels_taken'             => $levelsTaken,
                'scores'                   => $scores,
                'final_level'              => $finalLevel,
                'current_level'            => $finalLevel,
                'speaking_question_id'     => $speakingQuestion->id,
                'current_step_started_at'  => now(),
            ]);

            return redirect()->route('placement.speaking');
        }

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

        if (!$attempt->user_id) {
            return redirect()->route('placement.finished');
        }

        return redirect()->route('placement.result');
    }

    private function finalizeOnTimeout(IeltsPlacementAttempt $attempt)
    {
        $test = PlacementTest::with('questions')->find($attempt->current_test_id);

        if (!$test) {
            $attempt->update(['status' => 'abandoned', 'current_test_id' => null]);
            return redirect()->route('placement.entry');
        }

        $score = $this->gradeAndPersistAnswers($attempt, $test, []);

        return $this->advanceAttempt($attempt, $test->level, $score);
    }
    public function requestLogin(Request $request)
    {
        $request->session()->put('url.intended', route('placement.result'));
        $request->session()->flash('open_auth_modal', true);
        $request->session()->flash('open_auth_tab', $request->query('tab', 'login'));

        return redirect('/');
    }
}