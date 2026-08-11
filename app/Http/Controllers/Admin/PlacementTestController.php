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
                'audio_url'               => $q->audio_path ? Storage::url($q->audio_path) : null,
                'existing_audio_path'     => $q->audio_path,
                'audio_input_name'        => null,
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

        // return view('admin.placement_tests.form', compact(
        //     'pageTitle', 'formAction', 'placementTest', 'questionsData', 'existingTests', 'poolProgress', 'speakingQuestions'
        // ));
        return view('admin.placement_tests.form', compact(
            'pageTitle', 'formAction', 'placementTest', 'questionsData', 'passagesData', 'existingTests', 'poolProgress', 'speakingQuestions'
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

            $this->syncQuestions($test, $request);

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

            // Thu thập toàn bộ path file (audio + ảnh) đang được các câu hỏi CŨ
            // sử dụng — nhưng CHƯA xoá file vật lý vội. Nếu xoá ngay ở đây, những
            // câu hỏi mà người dùng không đổi file mới (JS gửi lại qua
            // existing_audio_path / existing_option_images) sẽ bị mất file dù
            // path trong DB vẫn còn trỏ tới đó -> 404 khi phát audio/hiển thị ảnh.
            $oldFilePaths = [];
            foreach ($placementTest->questions as $oldQuestion) {
                if ($oldQuestion->audio_path) {
                    $oldFilePaths[] = $oldQuestion->audio_path;
                }
                if ($oldQuestion->type === 'listening_image_choice') {
                    foreach (($oldQuestion->options ?? []) as $imgPath) {
                        if ($imgPath) {
                            $oldFilePaths[] = $imgPath;
                        }
                    }
                }
            }

            $placementTest->questions()->delete();

            $this->syncQuestions($placementTest, $request);

            // Sau khi câu hỏi mới đã được ghi xong, lấy danh sách path ĐANG được
            // dùng bởi câu hỏi mới. Chỉ xoá những file cũ KHÔNG còn xuất hiện
            // trong danh sách này nữa (tức thực sự đã bị thay thế / không dùng nữa).
            $newFilePaths = [];
            foreach ($placementTest->fresh('questions')->questions as $newQuestion) {
                if ($newQuestion->audio_path) {
                    $newFilePaths[] = $newQuestion->audio_path;
                }
                if ($newQuestion->type === 'listening_image_choice') {
                    foreach (($newQuestion->options ?? []) as $imgPath) {
                        if ($imgPath) {
                            $newFilePaths[] = $imgPath;
                        }
                    }
                }
            }

            $filesToDelete = array_diff($oldFilePaths, $newFilePaths);
            foreach ($filesToDelete as $path) {
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
        foreach ($placementTest->questions as $question) {
            if ($question->audio_path) {
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
            'questions_data'  => 'required|string',
        ]);
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
    private function syncQuestions(PlacementTest $test, Request $request): void
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

            // ── Audio (áp dụng cho mọi dạng, kể cả listening_image_choice) ──
            $audioPath = null;
            $hasAudio = !empty($q['has_audio']) || $type === 'listening_image_choice';

            if ($hasAudio) {
                $inputName = $q['audio_input_name'] ?? null;

                if ($inputName && $request->hasFile($inputName)) {
                    $audioPath = $request->file($inputName)->store('placement-tests/audio', 'public');
                } elseif (!empty($q['existing_audio_path'])) {
                    $audioPath = $q['existing_audio_path'];
                } else {
                    throw new \RuntimeException('Câu ' . ($index + 1) . ' cần file audio nhưng chưa có.');
                }
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
                    throw new \RuntimeException(
                        'Câu ' . ($index + 1) . ' gắn với đoạn văn "' . $linkedPassageId . '" không tồn tại. '
                        . 'Các đoạn văn hiện có trong $test->reading_passages: [' . $passages->pluck('id')->implode(', ') . ']'
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
                'audio_path'        => $audioPath,
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