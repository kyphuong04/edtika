<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlacementTest;
use App\Models\PlacementQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\PlacementSpeakingQuestion; 

class PlacementTestController extends Controller
{
    public function __construct()
    {
        // Chỉ Manager / CEO được truy cập toàn bộ module Placement Test.
        // Đổi lại thành middleware permission chuẩn của bạn (vd. 'can:admin_placement_tests')
        // nếu bạn đã đăng ký permission này trong bảng quyền.
        $this->middleware(function (Request $request, $next) {
            $user = auth()->user();

            if (!$user || !(method_exists($user, 'isManager') && ($user->isManager() || $user->isCeo()))) {
                abort(403, 'Bạn không có quyền truy cập mục này.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        [$tests, $poolProgress] = $this->getTestsListWithProgress();

        return view('admin.placement_tests.index', compact('tests', 'poolProgress'));
    }

    /**
     * Lấy danh sách toàn bộ đề + tiến độ pool (dùng chung cho trang index
     * và cho tab "Danh sách đề đã tạo" hiển thị ngay trong trang tạo/sửa đề).
     */
    private function getTestsListWithProgress(): array
    {
        $tests = PlacementTest::withCount('questions')
            ->orderByRaw("FIELD(level, 'A1','A2','B1','B2','B2+')")
            ->orderByDesc('created_at')
            ->get();

        $poolProgress = collect(PlacementTest::REQUIRED_POOL)->map(function ($required, $level) use ($tests) {
            return [
                'level'     => $level,
                'required'  => $required,
                'current'   => $tests->where('level', $level)->count(),
                'published' => $tests->where('level', $level)->where('status', 'published')->count(),
            ];
        })->values();

        return [$tests, $poolProgress];
    }

    public function create()
    {
        $pageTitle = 'Tạo đề Placement Test';
        $formAction = route('admin.placement_tests.store');
        $placementTest = null;
        $questionsData = [];
        $passagesData = [];
        [$existingTests, $poolProgress] = $this->getTestsListWithProgress();
        $speakingQuestions = PlacementSpeakingQuestion::orderByDesc('created_at')->get();

        return view('admin.placement_tests.form', compact(
            'pageTitle', 'formAction', 'placementTest', 'questionsData', 'existingTests', 'poolProgress', 'speakingQuestions'
        ));
    }

    public function edit(PlacementTest $placementTest)
    {
        $placementTest->load('questions');

        $pageTitle = 'Sửa đề: ' . $placementTest->title;
        $formAction = route('admin.placement_tests.update', $placementTest);

        $questionsData = $placementTest->questions->map(function (PlacementQuestion $q) {
            $rawCorrect = $q->correct_answer;
            $correctAnswerFlat = null;
            $correctAnswerText = '';
            $correctAnswerNested = null;

            if ($q->type === 'sentence_completion') {
                $correctAnswerNested = $rawCorrect;
            } elseif ($q->type === 'error_correction') {
                $correctAnswerText = is_array($rawCorrect) ? ($rawCorrect[0] ?? '') : (string) $rawCorrect;
            } else {
                // multiple_choice, listening_image_choice
                $correctAnswerFlat = is_array($rawCorrect) ? ($rawCorrect[0] ?? null) : $rawCorrect;
            }

            return [
                'id'                      => $q->id,
                'type'                    => $q->type,
                'has_audio'               => $q->has_audio,
                'linked_passage_id'       => $q->linked_passage_id,
                'answer_help'             => $q->answer_help,
                'has_audio'               => (bool) $q->audio_clip_id,
                'audio_clip_id'           => $q->audio_clip_id,
                'question_text'           => $q->question_text,
                'options'                 => $q->type === 'multiple_choice' ? ($q->options ?? []) : [],
                // listening_image_choice: options là mảng đường dẫn ảnh -> kèm URL để preview
                // + giữ nguyên đường dẫn gốc để JS gửi lại khi không đổi ảnh mới.
                'option_image_urls'       => $q->type === 'listening_image_choice'
                    ? array_map(fn ($path) => $path ? Storage::url($path) : null, array_pad($q->options ?? [], 3, null))
                    : [null, null, null],
                'existing_option_images'  => $q->type === 'listening_image_choice'
                    ? array_pad($q->options ?? [], 3, null)
                    : [null, null, null],
                'option_image_input_names' => [null, null, null],
                'word_bank'               => $q->word_bank ?? [],
                'blank_hints'             => $q->blank_hints ?? [],
                'correct_answer'          => $q->type === 'sentence_completion' ? $correctAnswerNested : $correctAnswerFlat,
                'correct_answer_text'     => $correctAnswerText,
                'points'                  => $q->points,
            ];
        })->values();
        $passagesData = $placementTest->reading_passages ?? [];

        // dd($passagesData);

        [$existingTests, $poolProgress] = $this->getTestsListWithProgress();
        $speakingQuestions = PlacementSpeakingQuestion::orderByDesc('created_at')->get();

        $audioClipsData = collect($placementTest->audio_clips ?? [])->map(fn ($clip) => [
            'id'         => $clip['id'],
            'label'      => $clip['label'] ?? '',
            'path'       => $clip['path'] ?? null,
            'input_name' => null,
            'url'        => !empty($clip['path']) ? Storage::url($clip['path']) : null,
        ])->values()->all();
        return view('admin.placement_tests.form', compact(
            'pageTitle', 'formAction', 'placementTest', 'questionsData', 'passagesData', 'existingTests', 'poolProgress', 'speakingQuestions', 'audioClipsData'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTest($request);

        DB::beginTransaction();
        try {
            $test = PlacementTest::create([
                'level'            => $validated['level'],
                'title'            => $validated['title'],
                'description'      => $validated['description'] ?? null,
                'reading_passages'  => json_decode($validated['reading_passages'] ?? '[]', true) ?: [],
                'status'           => $request->input('submit_action') === 'publish' ? 'published' : 'draft',
                'created_by'       => auth()->id(),
            ]);

            $clipMap = $this->syncAudioClips($test, $request);
            $this->syncQuestions($test, $request, $clipMap);

            DB::commit();

            return redirect()
                ->route('admin.placement_tests.edit', $test)
                ->with('toast', ['status' => 'success', 'title' => 'Thành công', 'msg' => 'Đã tạo đề Placement Test.']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('toast', [
                'status' => 'error',
                'title'  => 'Lỗi',
                'msg'    => 'Không thể tạo đề: ' . $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, PlacementTest $placementTest)
    {
        $validated = $this->validateTest($request);

        DB::beginTransaction();
        try {
            $placementTest->update([
                'level'           => $validated['level'],
                'title'           => $validated['title'],
                'description'     => $validated['description'] ?? null,
                'reading_passages'  => json_decode($validated['reading_passages'] ?? '[]', true) ?: [],
                'status'          => $request->input('submit_action') === 'publish' ? 'published' : $placementTest->status,
            ]);

            $clipMap = $this->syncAudioClips($placementTest, $request);

            // Ảnh của listening_image_choice vẫn gắn 1-1 với câu hỏi nên vẫn cần
            // diff thủ công quanh chu trình xoá-tạo-lại câu hỏi. Audio đã do
            // syncAudioClips() lo, không cần gom ở đây nữa.
            $oldImagePaths = [];
            foreach ($placementTest->questions as $oldQuestion) {
                if ($oldQuestion->type === 'listening_image_choice') {
                    foreach (($oldQuestion->options ?? []) as $imgPath) {
                        if ($imgPath) {
                            $oldImagePaths[] = $imgPath;
                        }
                    }
                }
            }

            $placementTest->questions()->delete();

            $this->syncQuestions($placementTest, $request, $clipMap);

            $newImagePaths = [];
            foreach ($placementTest->fresh('questions')->questions as $newQuestion) {
                if ($newQuestion->type === 'listening_image_choice') {
                    foreach (($newQuestion->options ?? []) as $imgPath) {
                        if ($imgPath) {
                            $newImagePaths[] = $imgPath;
                        }
                    }
                }
            }

            foreach (array_diff($oldImagePaths, $newImagePaths) as $path) {
                Storage::disk('public')->delete($path);
            }

            DB::commit();

            return redirect()
                ->route('admin.placement_tests.edit', $placementTest)
                ->with('toast', ['status' => 'success', 'title' => 'Thành công', 'msg' => 'Đã cập nhật đề.']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('toast', [
                'status' => 'error',
                'title'  => 'Lỗi',
                'msg'    => 'Không thể cập nhật đề: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(PlacementTest $placementTest)
    {
        foreach (($placementTest->audio_clips ?? []) as $clip) {
            if (!empty($clip['path'])) {
                Storage::disk('public')->delete($clip['path']);
            }
        }

        foreach ($placementTest->questions as $question) {
            if ($question->audio_path) {   // dữ liệu cũ trước migration
                Storage::disk('public')->delete($question->audio_path);
            }
            if ($question->type === 'listening_image_choice') {
                foreach (($question->options ?? []) as $imgPath) {
                    if ($imgPath) {
                        Storage::disk('public')->delete($imgPath);
                    }
                }
            }
        }

        $placementTest->delete();

        return back()->with('toast', ['status' => 'success', 'title' => 'Đã xoá', 'msg' => 'Đã xoá đề Placement Test.']);
    }

    public function toggleStatus(PlacementTest $placementTest)
    {
        if ($placementTest->status === 'draft') {
            if (!$placementTest->isReadyToPublish()) {
                return back()->with('toast', [
                    'status' => 'error',
                    'title'  => 'Chưa đủ điều kiện',
                    'msg'    => 'Đề cần đủ ' . PlacementTest::MAX_QUESTIONS . ' câu hỏi trước khi xuất bản.',
                ]);
            }
            $placementTest->update(['status' => 'published']);
        } else {
            $placementTest->update(['status' => 'draft']);
        }

        return back()->with('toast', ['status' => 'success', 'title' => 'Thành công', 'msg' => 'Đã cập nhật trạng thái đề.']);
    }

    private function validateTest(Request $request): array
    {
        return $request->validate([
            'level'           => 'required|in:A1,A2,B1,B2,B2+',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'reading_passages'  => 'nullable|json',
            'audio_clips_data' => 'nullable|json',
            'questions_data'  => 'required|string',
        ]);
    }

    /**
     * Ghi mảng audio_clips vào đề.
     *
     * Với mỗi clip: nếu người dùng chọn file mới (input_name có file kèm request)
     * thì upload; nếu không thì giữ nguyên path cũ mà JS gửi lại. Sau khi ghi
     * xong, xoá file vật lý của những clip đã bị gỡ khỏi đề.
     *
     * So với cách cũ (audio gắn 1-1 câu hỏi), cleanup file ở đây gọn và an toàn
     * hơn hẳn: không còn phụ thuộc vào chu trình questions()->delete() rồi tạo lại
     * trong update().
     *
     * @return array map [clip_id => path], dùng để validate audio_clip_id của câu hỏi
     */
    private function syncAudioClips(PlacementTest $test, Request $request): array
    {
        $raw = $request->input('audio_clips_data');
        $oldClips = $test->audio_clips ?? [];

        // Form không gửi trường này -> GIỮ NGUYÊN, không coi là "đã xoá hết".
        if ($raw === null) {
            return array_column($oldClips, 'path', 'id');
        }

        $incoming = json_decode($raw, true) ?: [];

        // Đề đang có audio mà payload rỗng -> gần như chắc chắn form chưa nạp được
        // dữ liệu, không phải chủ ý người dùng. Dừng lại thay vì xoá file.
        if (empty($incoming) && !empty($oldClips)
            && $test->questions()->whereNotNull('audio_clip_id')->exists()) {
            throw new \RuntimeException(
                'Form không gửi lên danh sách file audio trong khi đề đang có audio. '
                . 'Đã huỷ thao tác để tránh mất file. Hãy tải lại trang rồi thử lại.'
            );
        }
        $incoming = json_decode($request->input('audio_clips_data', '[]'), true) ?: [];
        $oldPaths = collect($test->audio_clips ?? [])->pluck('path')->filter()->values()->all();

        $clips = [];
        $seenIds = [];

        foreach ($incoming as $i => $clip) {
            $id = trim((string) ($clip['id'] ?? ''));

            if ($id === '') {
                throw new \RuntimeException('File audio #' . ($i + 1) . ' thiếu mã định danh.');
            }

            if (in_array($id, $seenIds, true)) {
                throw new \RuntimeException('Mã file audio bị trùng: ' . $id);
            }
            $seenIds[] = $id;

            $inputName = $clip['input_name'] ?? null;

            if ($inputName && $request->hasFile($inputName)) {
                $path = $request->file($inputName)->store('placement-tests/audio', 'public');
            } elseif (!empty($clip['path'])) {
                $path = $clip['path'];
            } else {
                throw new \RuntimeException(
                    'File audio #' . ($i + 1) . ' (' . ($clip['label'] ?: $id) . ') chưa được chọn.'
                );
            }

            $clips[] = [
                'id'    => $id,
                'label' => trim((string) ($clip['label'] ?? '')) ?: basename($path),
                'path'  => $path,
            ];
        }

        $test->update(['audio_clips' => $clips]);

        // Chỉ xoá file KHÔNG còn được clip nào dùng nữa.
        $newPaths = array_column($clips, 'path');
        foreach (array_diff($oldPaths, $newPaths) as $stalePath) {
            Storage::disk('public')->delete($stalePath);
        }

        return array_column($clips, 'path', 'id');
    }

    /**
     * Đọc JSON câu hỏi được build ở client (hidden input `questions_data`),
     * ghép với file audio/ảnh thực tế gửi kèm, rồi ghi vào bảng placement_questions.
     *
     * Chuẩn hoá correct_answer luôn là mảng để logic chấm về sau nhất quán:
     *  - multiple_choice:        ["đáp án đúng"]
     *  - sentence_completion:    [["ans1","alt1"], ["ans2"]]  (theo từng chỗ trống)
     *  - error_correction:       ["câu đúng hoàn chỉnh"]      (so khớp không phân biệt hoa/thường)
     *  - listening_image_choice: ["A"] / ["B"] / ["C"]
     */
    private function syncQuestions(PlacementTest $test, Request $request, array $clipMap = []): void
    {
        $questions = json_decode($request->input('questions_data'), true) ?: [];
        $passages = collect($test->reading_passages ?? []);

        if (count($questions) === 0) {
            throw new \RuntimeException('Đề phải có ít nhất 1 câu hỏi.');
        }

        if (count($questions) > PlacementTest::MAX_QUESTIONS) {
            throw new \RuntimeException('Mỗi đề tối đa ' . PlacementTest::MAX_QUESTIONS . ' câu hỏi.');
        }

        foreach ($questions as $index => $q) {
            $type = $q['type'] ?? 'multiple_choice';

            if (!array_key_exists($type, PlacementTest::QUESTION_TYPES)) {
                throw new \RuntimeException('Câu ' . ($index + 1) . ' có dạng câu hỏi không hợp lệ.');
            }

            $clipId = trim((string) ($q['audio_clip_id'] ?? ''));
            $hasAudio = $clipId !== '';

            if ($hasAudio && !isset($clipMap[$clipId])) {
                throw new \RuntimeException(
                    'Câu ' . ($index + 1) . ' gắn với file audio "' . $clipId . '" không tồn tại. '
                    . 'Các file audio hiện có: [' . implode(', ', array_keys($clipMap)) . ']'
                );
            }

            if ($type === 'listening_image_choice' && !$hasAudio) {
                throw new \RuntimeException('Câu ' . ($index + 1) . ' (Listening - Image) cần chọn file audio.');
            }

            // ── Dữ liệu riêng theo từng dạng ──
            $options = null;
            $wordBank = null;
            $blankHints = null;
            $linkedPassageId = null;
            $correctAnswer = null;

            if ($type === 'multiple_choice') {
                $options = $q['options'] ?? [];
                if (count($options) < 2) {
                    throw new \RuntimeException('Câu ' . ($index + 1) . ' cần tối thiểu 2 lựa chọn.');
                }
                if (empty($q['correct_answer'])) {
                    throw new \RuntimeException('Câu ' . ($index + 1) . ' chưa chọn đáp án đúng.');
                }
                $correctAnswer = [$q['correct_answer']];

                $linkedPassageId = $q['linked_passage_id'] ?? null;
                if ($linkedPassageId && !$passages->firstWhere('id', $linkedPassageId)) {
                    throw new \RuntimeException(
                        'Câu ' . ($index + 1) . ' gắn với đoạn văn "' . $linkedPassageId . '" không tồn tại. '
                        . 'Các đoạn văn hiện có trong $test->reading_passages: [' . $passages->pluck('id')->implode(', ') . ']'
                    );
                }
                
            } elseif ($type === 'sentence_completion') {
                $wordBank = !empty($q['word_bank']) ? array_values(array_filter($q['word_bank'])) : null;
                $blankHints = $q['blank_hints'] ?? null;
                if (!is_array($q['correct_answer'] ?? null) || count($q['correct_answer']) === 0) {
                    throw new \RuntimeException('Câu ' . ($index + 1) . ' (Sentence Completion) chưa nhập đáp án cho các chỗ trống.');
                }

                // Số đáp án phải khớp số chỗ trống ___, nếu không MỌI học viên đều sai
                // câu này mà không có cảnh báo nào (isAnswerCorrect yêu cầu count khớp).
                $blankCount = preg_match_all('/_{2,}/', $q['question_text'] ?? '');
                if ($blankCount !== count($q['correct_answer'])) {
                    throw new \RuntimeException(
                        'Câu ' . ($index + 1) . ' có ' . $blankCount . ' chỗ trống nhưng nhập '
                        . count($q['correct_answer']) . ' đáp án.'
                    );
                }
                $correctAnswer = $q['correct_answer'];

                $linkedPassageId = $q['linked_passage_id'] ?? null;
                if ($linkedPassageId && !$passages->firstWhere('id', $linkedPassageId)) {
                    throw new \RuntimeException('Câu ' . ($index + 1) . ' gắn với đoạn văn không tồn tại.');
                }
            } elseif ($type === 'error_correction') {
                $answerText = trim((string) ($q['correct_answer_text'] ?? ''));
                if ($answerText === '') {
                    throw new \RuntimeException('Câu ' . ($index + 1) . ' (Error Correction) chưa nhập câu đúng.');
                }
                $correctAnswer = [$answerText];
            } elseif ($type === 'listening_image_choice') {
                $optionImagePaths = [];
                $inputNames = $q['option_image_input_names'] ?? [null, null, null];
                $existingPaths = $q['existing_option_images'] ?? [null, null, null];

                for ($i = 0; $i < 3; $i++) {
                    $inputName = $inputNames[$i] ?? null;
                    if ($inputName && $request->hasFile($inputName)) {
                        $optionImagePaths[$i] = $request->file($inputName)->store('placement-tests/images', 'public');
                    } elseif (!empty($existingPaths[$i])) {
                        $optionImagePaths[$i] = $existingPaths[$i];
                    } else {
                        throw new \RuntimeException('Câu ' . ($index + 1) . ' (Listening - Image) thiếu ảnh cho lựa chọn ' . chr(65 + $i) . '.');
                    }
                }
                $options = $optionImagePaths;

                if (empty($q['correct_answer']) || !in_array($q['correct_answer'], ['A', 'B', 'C'], true)) {
                    throw new \RuntimeException('Câu ' . ($index + 1) . ' chưa chọn ảnh đáp án đúng (A/B/C).');
                }
                $correctAnswer = [$q['correct_answer']];
            }

            PlacementQuestion::create([
                'placement_test_id' => $test->id,
                'order_index'       => $index,
                'type'              => $type,
                'has_audio'         => $hasAudio,
                'linked_passage_id'   => $linkedPassageId,
                'audio_clip_id'     => $hasAudio ? $clipId : null,
                'audio_path'        => null,
                'question_text'     => $q['question_text'] ?? '',
                'options'           => $options,
                'word_bank'         => $wordBank,
                'blank_hints'       => $blankHints,
                'correct_answer'    => $correctAnswer,
                'answer_help'       => trim((string) ($q['answer_help'] ?? '')) ?: null,
                'points'            => $q['points'] ?? 1,
            ]);
        }
    }
}