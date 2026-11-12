<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AiExaminer\MockWritingData;
use App\Services\AiExaminer\MockWritingGrader;
use App\Services\AiExaminer\WritingGraderInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * EDTIKA AI Examiner — BẢN MẪU GIAO DIỆN.
 *
 * Luồng: Giới thiệu → Làm bài (Task 1/2) → [chưa đăng nhập: popup đăng nhập]
 *        → Đang chấm (polling status) → Overall → Breakdown.
 *
 * Bài nộp đang lưu tạm trong SESSION để chạy thử, chưa tạo bảng DB.
 * Khi chốt tính năng: chuyển sang bảng ai_writing_submissions (xem ghi chú trong docs).
 */
class AiExaminerController extends Controller
{
    private const SESSION_KEY = 'ai_examiner.writing';

    public function index()
    {
        return view('design_1.web.ai_examiner.index', [
            'pageTitle' => 'AI Examiner',
            'introVideoUrl' => config('ai_examiner.intro_video_url'),
        ]);
    }

    public function speaking()
    {
        return view('design_1.web.ai_examiner.speaking', [
            'pageTitle' => 'AI Examiner | Speaking',
        ]);
    }

    public function writing(Request $request)
    {
        $task = (int)$request->get('task', 1) === 2 ? 2 : 1;

        // Khách chưa đăng nhập: sau khi đăng nhập xong quay lại đúng màn này và tự nộp bài nháp
        if (auth()->guest()) {
            session()->put('url.intended', route('ai_examiner.writing', ['task' => $task, 'resume' => 1]));
        }

        return view('design_1.web.ai_examiner.writing.setup', [
            'pageTitle' => 'AI Examiner | Writing',
            'activeTask' => $task,
            'tasks' => config('ai_examiner.writing.tasks'),
            // Đề + bài demo (nút "Điền đề + bài demo" của bản mẫu)
            'demo' => [
                1 => MockWritingData::topics(1)[0] + ['essay' => MockWritingData::demoEssay(1)],
                2 => MockWritingData::topics(2)[0] + ['essay' => MockWritingData::demoEssay(2)],
            ],
            'resume' => (bool)$request->get('resume'),
        ]);
    }

    public function randomTopic(Request $request)
    {
        $task = (int)$request->get('task', 1) === 2 ? 2 : 1;
        $topics = MockWritingData::topics($task);
        $exclude = (string)$request->get('current', '');
        $candidates = array_values(array_filter($topics, fn ($t) => $t['prompt'] !== $exclude)) ?: $topics;

        return response()->json($candidates[array_rand($candidates)]);
    }

    /** "Tải bài viết tay" — bản mẫu trả văn bản giả (sau này gọi OCR của team AI). */
    public function ocr(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
            'task' => 'required|in:1,2',
        ]);

        return response()->json([
            'status' => 'ok',
            'text' => MockWritingData::ocrText((int)$request->get('task')),
            'is_mock' => true,
        ]);
    }

    public function submit(Request $request)
    {
        if (auth()->guest()) {
            return response()->json(['status' => 'login_required'], 401);
        }

        $data = $request->validate([
            'task' => 'required|in:1,2',
            'prompt' => 'required|string|max:3000',
            'essay' => 'required|string|max:20000',
            'chart' => 'nullable|image|max:10240',
            'chart_url' => 'nullable|string|max:500',
        ]);

        $task = (int)$data['task'];
        $chartUrl = $data['chart_url'] ?? null;

        if ($task === 1 && $request->hasFile('chart')) {
            $path = $request->file('chart')->store('ai-examiner/charts', 'public');
            $chartUrl = Storage::disk('public')->url($path);
        }

        if ($task === 1 && empty($chartUrl)) {
            return response()->json([
                'status' => 'error',
                'errors' => ['chart' => ['Vui lòng tải lên biểu đồ / bảng / bản đồ / hình ảnh của đề bài.']],
            ], 422);
        }

        $submission = $this->grader()->submit([
            'id' => 'aiw_' . Str::lower(Str::random(12)),
            'user_id' => auth()->id(),
            'task' => $task,
            'prompt' => trim($data['prompt']),
            'chart_url' => $task === 1 ? $chartUrl : null,
            'essay' => trim($data['essay']),
            'word_count' => MockWritingGrader::wordCount($data['essay']),
            'created_at' => microtime(true),
        ]);

        session()->put(self::SESSION_KEY . '.items.' . $submission['id'], $submission);
        session()->put(self::SESSION_KEY . '.latest.' . $task, $submission['id']);

        return response()->json([
            'status' => 'ok',
            'submission_id' => $submission['id'],
            'redirect' => route('ai_examiner.writing.grading', $submission['id']),
        ]);
    }

    public function grading(string $id)
    {
        $submission = $this->findSubmission($id);

        return view('design_1.web.ai_examiner.writing.grading', [
            'pageTitle' => 'Đang chấm bài | AI Examiner',
            'submission' => $submission,
            'criteria' => config('ai_examiner.writing.criteria.' . $submission['task']),
            'statusUrl' => route('ai_examiner.writing.status', $id),
            'resultUrl' => route('ai_examiner.writing.result', $id),
        ]);
    }

    public function status(string $id)
    {
        $submission = $this->findSubmission($id);
        $status = $this->grader()->status($submission);

        if ($status['status'] === 'done') {
            $status['redirect'] = route('ai_examiner.writing.result', $id);
        }

        return response()->json($status);
    }

    public function result(string $id)
    {
        return $this->renderResult($id, 'overall');
    }

    public function breakdown(string $id)
    {
        return $this->renderResult($id, 'breakdown');
    }

    private function renderResult(string $id, string $mode)
    {
        $submission = $this->findSubmission($id);

        if ($this->grader()->status($submission)['status'] !== 'done') {
            return redirect()->route('ai_examiner.writing.grading', $id);
        }

        $result = $this->grader()->result($submission);
        $task = (int)$submission['task'];

        // Tab Task 1 / Task 2: trỏ tới bài gần nhất của task còn lại (nếu có)
        $taskLinks = [];
        foreach ([1, 2] as $n) {
            $latestId = $n === $task ? $id : session()->get(self::SESSION_KEY . '.latest.' . $n);
            $taskLinks[$n] = $latestId
                ? route($mode === 'overall' ? 'ai_examiner.writing.result' : 'ai_examiner.writing.breakdown', $latestId)
                : null;
        }

        return view('design_1.web.ai_examiner.writing.' . ($mode === 'overall' ? 'overall' : 'breakdown'), [
            'pageTitle' => 'Kết quả chấm chữa | AI Examiner',
            'submission' => $submission,
            'result' => $result,
            'task' => $task,
            'taskLinks' => $taskLinks,
            'taskConfig' => config('ai_examiner.writing.tasks'),
            'resources' => MockWritingData::resources($task),
            'courses' => MockWritingData::courses(),
            'sampleBands' => config('ai_examiner.writing.sample_bands'),
        ]);
    }

    private function findSubmission(string $id): array
    {
        $submission = session()->get(self::SESSION_KEY . '.items.' . $id);

        abort_if(empty($submission) || (int)$submission['user_id'] !== (int)auth()->id(), 404);

        return $submission;
    }

    private function grader(): WritingGraderInterface
    {
        // Sau này: 'http' => app(HttpWritingGrader::class)
        return app(MockWritingGrader::class);
    }
}
