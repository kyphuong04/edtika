<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\WebinarAssignment;
use App\Models\WebinarAssignmentHistory;
use App\Models\WebinarChapter;
use App\Models\Webinar;
use App\Services\Bundle\AssignmentGradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * GĐ4 - Màn chấm bài (Post Lesson / Bài tập) theo khóa học cho teacher.
 */
class BundleGradingController extends Controller
{
    const STATUS_FILTERS = ['pending', 'passed', 'not_passed', 'all'];

    /**
     * Danh sách bài nộp của mọi module trong bundle.
     */
    public function index(Request $request, $id)
    {
        $user = auth()->user();
        $bundle = $this->findBundleForGrader($id, $user);
        $webinarIds = $this->bundleWebinarIds($bundle);

        $status = in_array($request->get('status'), self::STATUS_FILTERS, true) ? $request->get('status') : 'pending';
        $moduleId = (int)$request->get('module');
        $chapterId = (int)$request->get('lesson');
        $search = trim((string)$request->get('search'));

        $query = $this->historyQuery($webinarIds)
            ->when($moduleId > 0, function ($query) use ($moduleId) {
                $query->where('webinar_assignments.webinar_id', $moduleId);
            })
            ->when($chapterId > 0, function ($query) use ($chapterId) {
                $query->where('webinar_assignments.chapter_id', $chapterId);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('student', function ($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        // Số bài theo trạng thái (cho các tab), tính trước khi lọc trạng thái
        $statusCounts = (clone $query)
            ->select('webinar_assignment_history.status', DB::raw('count(*) as total'))
            ->groupBy('webinar_assignment_history.status')
            ->pluck('total', 'status');

        if ($status !== 'all') {
            $query->where('webinar_assignment_history.status', $status);
        }

        $histories = $query
            ->select('webinar_assignment_history.*')
            ->with([
                'student:id,full_name,email,avatar',
                'assignment' => function ($query) {
                    $query->with(['webinar', 'chapter']);
                },
            ])
            ->withMax('messages', 'created_at')
            ->orderByDesc('messages_max_created_at')
            ->paginate(20)
            ->withQueryString();

        $modules = Webinar::query()->whereIn('id', $webinarIds)->get();
        $lessons = WebinarChapter::query()
            ->whereIn('webinar_id', $moduleId > 0 ? [$moduleId] : $webinarIds)
            ->orderBy('order')
            ->get();

        return view('design_1.panel.bundles.grading.index', [
            'pageTitle' => trans('bundle_learning.grading_title') . ' - ' . $bundle->title,
            'bundle' => $bundle,
            'histories' => $histories,
            'statusCounts' => $statusCounts,
            'status' => $status,
            'modules' => $modules,
            'lessons' => $lessons,
            'moduleId' => $moduleId,
            'chapterId' => $chapterId,
            'search' => $search,
        ]);
    }

    /**
     * Chi tiết một bài nộp + form chấm.
     */
    public function show(Request $request, $id, $historyId)
    {
        $user = auth()->user();
        $bundle = $this->findBundleForGrader($id, $user);
        $history = $this->findHistory($bundle, (int)$historyId);

        $history->load([
            'student',
            'assignment' => function ($query) {
                $query->with(['webinar', 'chapter', 'attachments']);
            },
            'messages' => function ($query) {
                $query->orderBy('created_at')->with('sender');
            },
        ]);

        // Bài chờ chấm tiếp theo để chấm liên tục
        $nextPending = $this->historyQuery($this->bundleWebinarIds($bundle))
            ->where('webinar_assignment_history.status', WebinarAssignmentHistory::$pending)
            ->where('webinar_assignment_history.id', '!=', $history->id)
            ->select('webinar_assignment_history.id')
            ->orderBy('webinar_assignment_history.id')
            ->first();

        return view('design_1.panel.bundles.grading.show', [
            'pageTitle' => trans('bundle_learning.grading_title'),
            'bundle' => $bundle,
            'history' => $history,
            'assignment' => $history->assignment,
            'nextPending' => $nextPending,
        ]);
    }

    /**
     * Lưu điểm + nhận xét.
     */
    public function grade(Request $request, $id, $historyId)
    {
        $user = auth()->user();
        $bundle = $this->findBundleForGrader($id, $user);
        $history = $this->findHistory($bundle, (int)$historyId);
        $assignment = $history->assignment;

        if (!$assignment->canBeGradedBy($user)) {
            abort(403);
        }

        $maxGrade = max((int)$assignment->grade, (int)$assignment->pass_grade, 0);

        $validator = Validator::make($request->all(), [
            'grade' => 'required|integer|min:0' . ($maxGrade > 0 ? '|max:' . $maxGrade : ''),
            'comment' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($history->status === WebinarAssignmentHistory::$notSubmitted) {
            return back()->withErrors(['grade' => trans('bundle_learning.grading_not_submitted')]);
        }

        app(AssignmentGradingService::class)->grade(
            $history,
            (int)$request->get('grade'),
            $user,
            $request->get('comment')
        );

        $toast = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.save_assignment_grade_success'),
            'status' => 'success',
        ];

        // "Lưu và chấm bài tiếp theo"
        if ($request->get('next') == 1) {
            $next = $this->historyQuery($this->bundleWebinarIds($bundle))
                ->where('webinar_assignment_history.status', WebinarAssignmentHistory::$pending)
                ->select('webinar_assignment_history.id')
                ->orderBy('webinar_assignment_history.id')
                ->first();

            if (!empty($next)) {
                return redirect("/panel/bundles/{$bundle->id}/grading/{$next->id}")->with(['toast' => $toast]);
            }
        }

        return redirect("/panel/bundles/{$bundle->id}/grading")->with(['toast' => $toast]);
    }

    /* ---------------- Helpers ---------------- */

    /**
     * Teacher/người tạo bundle hoặc admin.
     */
    private function findBundleForGrader($id, $user): Bundle
    {
        if (empty($user) or $user->isUser()) {
            abort(403);
        }

        $bundle = Bundle::query()->findOrFail($id);

        if (!$bundle->isOwner($user->id) and !$user->isAdmin()) {
            abort(403);
        }

        return $bundle;
    }

    private function bundleWebinarIds(Bundle $bundle): array
    {
        return $bundle->bundleWebinars()->pluck('webinar_id')->all();
    }

    /**
     * Bài nộp thực sự (bỏ các bản ghi "chưa nộp") thuộc các module của bundle.
     */
    private function historyQuery(array $webinarIds)
    {
        return WebinarAssignmentHistory::query()
            ->join('webinar_assignments', 'webinar_assignments.id', '=', 'webinar_assignment_history.assignment_id')
            ->whereIn('webinar_assignments.webinar_id', !empty($webinarIds) ? $webinarIds : [0])
            ->where('webinar_assignment_history.status', '!=', WebinarAssignmentHistory::$notSubmitted);
    }

    private function findHistory(Bundle $bundle, int $historyId): WebinarAssignmentHistory
    {
        $history = WebinarAssignmentHistory::query()->findOrFail($historyId);

        $belongsToBundle = WebinarAssignment::query()
            ->where('id', $history->assignment_id)
            ->whereIn('webinar_id', $this->bundleWebinarIds($bundle))
            ->exists();

        if (!$belongsToBundle) {
            abort(404);
        }

        return $history;
    }
}
