<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\VocabularyWordReport;
use Illuminate\Http\Request;

/**
 * "Thông báo từ vựng lỗi" — giáo viên (role teacher) xem các báo cáo lỗi từ vựng
 * mà học viên gửi cho những bộ từ do mình tạo. Menu: Bảng thông báo > Thông báo
 * từ vựng lỗi (ngay dưới "Thông báo đề lỗi").
 * Mỗi giáo viên chỉ thấy báo cáo có teacher_id là mình.
 */
class VocabularyWordReportController extends Controller
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

        $query = VocabularyWordReport::query()
            ->where('teacher_id', $teacherId)
            ->with('user');

        $status = $request->input('status');
        if (in_array($status, [VocabularyWordReport::STATUS_NEW, VocabularyWordReport::STATUS_RESOLVED], true)) {
            $query->where('status', $status);
        }

        // Bộ lọc bộ từ: giá trị dạng "bundle:12" / "academic:3"
        $setFilter = (string) $request->input('set', '');
        if (preg_match('/^(bundle|academic):(\d+)$/', $setFilter, $m)) {
            $query->where('source', $m[1])->where('set_id', (int) $m[2]);
        }

        if ($request->filled('search')) {
            $term = '%' . trim($request->input('search')) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('message', 'like', $term)
                    ->orWhere('word', 'like', $term)
                    ->orWhereHas('user', fn ($u) => $u->where('full_name', 'like', $term)->orWhere('email', 'like', $term));
            });
        }

        // Chưa xử lý lên đầu, trong cùng trạng thái thì mới nhất trước.
        $reports = $query
            ->orderByRaw("CASE WHEN status = 'new' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(20)
            ->appends($request->query());

        $base = VocabularyWordReport::where('teacher_id', $teacherId);

        $stats = [
            'total' => (clone $base)->count(),
            'new' => (clone $base)->where('status', VocabularyWordReport::STATUS_NEW)->count(),
            'resolved' => (clone $base)->where('status', VocabularyWordReport::STATUS_RESOLVED)->count(),
        ];

        // Danh sách bộ từ có báo cáo (cho bộ lọc) — lấy tên mới nhất đã lưu.
        $sets = (clone $base)
            ->whereNotNull('set_id')
            ->orderByDesc('id')
            ->get(['source', 'set_id', 'set_name'])
            ->unique(fn ($r) => $r->source . ':' . $r->set_id)
            ->map(fn ($r) => [
                'value' => $r->source . ':' . $r->set_id,
                'label' => ($r->set_name ?: ('Bộ từ #' . $r->set_id)) . ($r->source === VocabularyWordReport::SOURCE_ACADEMIC ? ' (Academic)' : ''),
            ])
            ->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        return view('design_1.panel.dictionary.word_reports', [
            'pageTitle' => 'Thông báo từ vựng lỗi',
            'latestId' => (int) (clone $base)->max('id'),
            'reports' => $reports,
            'stats' => $stats,
            'sets' => $sets,
        ]);
    }

    /**
     * Trình duyệt của giáo viên gọi định kỳ (answer_report_poller) để cập nhật
     * số trên sidebar và tải lại danh sách khi có báo cáo mới.
     */
    public function summary()
    {
        $teacherId = (int) auth()->id();

        return response()->json([
            'new_count' => VocabularyWordReport::newCountFor($teacherId),
            'latest_id' => (int) VocabularyWordReport::where('teacher_id', $teacherId)->max('id'),
        ]);
    }

    /** Đánh dấu đã xử lý <-> mở lại. */
    public function toggle($reportId)
    {
        $report = VocabularyWordReport::where('teacher_id', auth()->id())->findOrFail($reportId);

        if ($report->isResolved()) {
            $report->forceFill([
                'status' => VocabularyWordReport::STATUS_NEW,
                'resolved_by' => null,
                'resolved_at' => null,
            ])->save();

            $toast = ['status' => 'success', 'title' => 'Đã mở lại', 'msg' => 'Báo cáo chuyển về "Chưa xử lý".'];
        } else {
            $report->forceFill([
                'status' => VocabularyWordReport::STATUS_RESOLVED,
                'resolved_by' => auth()->id(),
                'resolved_at' => time(),
            ])->save();

            $toast = ['status' => 'success', 'title' => 'Đã xử lý', 'msg' => 'Báo cáo được đánh dấu đã xử lý.'];
        }

        return back()->with('toast', $toast);
    }
}
