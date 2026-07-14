<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlacementTest;
use App\Models\PlacementQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        [$existingTests, $poolProgress] = $this->getTestsListWithProgress();

        return view('admin.placement_tests.form', compact(
            'pageTitle', 'formAction', 'placementTest', 'questionsData', 'existingTests', 'poolProgress'
        ));
    }

    public function edit(PlacementTest $placementTest)
    {
        $placementTest->load('questions');

        $pageTitle = 'Sửa đề: ' . $placementTest->title;
        $formAction = route('admin.placement_tests.update', $placementTest);

        $questionsData = $placementTest->questions->map(function (PlacementQuestion $q) {
            return [
                'id'             => $q->id,
                'type'           => $q->type,
                'has_audio'      => $q->has_audio,
                'audio_url'      => $q->audio_path ? Storage::url($q->audio_path) : null,
                'question_text'  => $q->question_text,
                'options'        => $q->options ?? [],
                'correct_answer' => $q->correct_answer,
                'points'         => $q->points,
            ];
        })->values();

        [$existingTests, $poolProgress] = $this->getTestsListWithProgress();

        return view('admin.placement_tests.form', compact(
            'pageTitle', 'formAction', 'placementTest', 'questionsData', 'existingTests', 'poolProgress'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTest($request);

        DB::beginTransaction();
        try {
            $test = PlacementTest::create([
                'level'       => $validated['level'],
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status'      => $request->input('submit_action') === 'publish' ? 'published' : 'draft',
                'created_by'  => auth()->id(),
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
                'level'       => $validated['level'],
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status'      => $request->input('submit_action') === 'publish' ? 'published' : $placementTest->status,
            ]);

            // Xoá câu hỏi cũ + file audio cũ, rồi tạo lại theo dữ liệu mới gửi lên.
            // (Đơn giản và an toàn hơn so với diff từng câu ở bước đầu tiên này.)
            foreach ($placementTest->questions as $oldQuestion) {
                if ($oldQuestion->audio_path) {
                    Storage::disk('public')->delete($oldQuestion->audio_path);
                }
            }
            $placementTest->questions()->delete();

            $this->syncQuestions($placementTest, $request);

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
            'level'       => 'required|in:A1,A2,B1,B2,B2+',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions_data' => 'required|string',
        ]);
    }

    /**
     * Đọc JSON câu hỏi được build ở client (hidden input `questions_data`),
     * ghép với file audio thực tế gửi kèm (input file có name = audio_input_name),
     * rồi ghi vào bảng placement_questions.
     */
    private function syncQuestions(PlacementTest $test, Request $request): void
    {
        $questions = json_decode($request->input('questions_data'), true) ?: [];

        if (count($questions) === 0) {
            throw new \RuntimeException('Đề phải có ít nhất 1 câu hỏi.');
        }

        if (count($questions) > PlacementTest::MAX_QUESTIONS) {
            throw new \RuntimeException('Mỗi đề tối đa ' . PlacementTest::MAX_QUESTIONS . ' câu hỏi.');
        }

        foreach ($questions as $index => $q) {
            $audioPath = null;
            $hasAudio = !empty($q['has_audio']);

            if ($hasAudio) {
                $inputName = $q['audio_input_name'] ?? null;

                if ($inputName && $request->hasFile($inputName)) {
                    $audioPath = $request->file($inputName)->store('placement-tests/audio', 'public');
                } elseif (!empty($q['existing_audio_path'])) {
                    // Giữ nguyên file audio cũ khi sửa đề mà không chọn file mới.
                    $audioPath = $q['existing_audio_path'];
                } else {
                    throw new \RuntimeException('Câu ' . ($index + 1) . ' được đánh dấu có audio nhưng chưa có file.');
                }
            }

            PlacementQuestion::create([
                'placement_test_id' => $test->id,
                'order_index'       => $index,
                'type'              => $q['type'],
                'has_audio'         => $hasAudio,
                'audio_path'        => $audioPath,
                'question_text'     => $q['question_text'],
                'options'           => $q['type'] === 'multiple_choice' ? ($q['options'] ?? []) : null,
                'correct_answer'    => $q['correct_answer'] ?? null,
                'points'            => $q['points'] ?? 1,
            ]);
        }
    }
}