<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsAnswerReport;
use Illuminate\Http\Request;

/**
 * "Thông báo đề lỗi" — giáo viên (role teacher) xem các báo cáo lỗi đáp án mà
 * học viên gửi cho những đề do mình tạo. Menu: Bảng thông báo > Thông báo đề lỗi.
 * Mỗi giáo viên chỉ thấy báo cáo có teacher_id là mình.
 */
class IeltsAnswerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $user = auth()->user();

            if (!$user || !$user->isTeacher()) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $teacherId = (int) auth()->id();

        $query = IeltsAnswerReport::query()
            ->where('teacher_id', $teacherId)
            ->with(['user', 'test']);

        $status = $request->input('status');
        if (in_array($status, [IeltsAnswerReport::STATUS_NEW, IeltsAnswerReport::STATUS_RESOLVED], true)) {
            $query->where('status', $status);
        }

        if ($request->filled('test_id')) {
            $query->where('test_id', (int) $request->input('test_id'));
        }

        if ($request->filled('skill') && array_key_exists($request->input('skill'), IeltsAnswerReport::SKILL_LABELS)) {
            $query->where('skill', $request->input('skill'));
        }

        if ($request->filled('search')) {
            $term = '%' . trim($request->input('search')) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('message', 'like', $term)
                    ->orWhereHas('user', fn ($u) => $u->where('full_name', 'like', $term)->orWhere('email', 'like', $term));
            });
        }

        // Chưa xử lý lên đầu, trong cùng trạng thái thì mới nhất trước.
        $reports = $query
            ->orderByRaw("CASE WHEN status = 'new' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(20)
            ->appends($request->query());

        $base = IeltsAnswerReport::where('teacher_id', $teacherId);

        $stats = [
            'total' => (clone $base)->count(),
            'new' => (clone $base)->where('status', IeltsAnswerReport::STATUS_NEW)->count(),
            'resolved' => (clone $base)->where('status', IeltsAnswerReport::STATUS_RESOLVED)->count(),
        ];

        // Danh sách đề có báo cáo (cho bộ lọc).
        $tests = \App\Models\IeltsTest::query()
            ->whereIn('id', (clone $base)->select('test_id')->distinct())
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('design_1.panel.ielts_tests_manage.answer_reports', [
            'pageTitle' => 'Thông báo đề lỗi',
            'reports' => $reports,
            'stats' => $stats,
            'tests' => $tests,
            'skillLabels' => IeltsAnswerReport::SKILL_LABELS,
        ]);
    }

    /** Đánh dấu đã xử lý <-> mở lại. */
    public function toggle($reportId)
    {
        $report = IeltsAnswerReport::where('teacher_id', auth()->id())->findOrFail($reportId);

        if ($report->isResolved()) {
            $report->forceFill([
                'status' => IeltsAnswerReport::STATUS_NEW,
                'resolved_by' => null,
                'resolved_at' => null,
            ])->save();

            $toast = ['status' => 'success', 'title' => 'Đã mở lại', 'msg' => 'Báo cáo chuyển về "Chưa xử lý".'];
        } else {
            $report->forceFill([
                'status' => IeltsAnswerReport::STATUS_RESOLVED,
                'resolved_by' => auth()->id(),
                'resolved_at' => time(),
            ])->save();

            $toast = ['status' => 'success', 'title' => 'Đã xử lý', 'msg' => 'Báo cáo được đánh dấu đã xử lý.'];
        }

        return back()->with('toast', $toast);
    }
}
