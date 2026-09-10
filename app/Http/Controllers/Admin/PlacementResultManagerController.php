<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IeltsPlacementAttempt;
use App\Models\IeltsPlacementAttemptAnswer;
use App\Models\PlacementTest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Quản lý bài làm Placement Test của học viên.
 *
 * Thay cho việc phải chạy tay trong tinker:
 *     \App\Models\IeltsPlacementAttemptAnswer::truncate();
 *     \App\Models\IeltsPlacementAttempt::truncate();
 *
 * Hai lệnh đó xoá sạch dữ liệu của TOÀN BỘ học viên và không hoàn tác được,
 * nên ở đây thao tác mặc định là RESET TỪNG HỌC VIÊN dưới dạng lưu trữ
 * (status -> 'archived'), giữ nguyên dữ liệu để đối chiếu về sau.
 */
class PlacementResultManagerController extends Controller
{
    public function __construct()
    {
        // Cùng cách kiểm quyền với PlacementTestController để nhất quán.
        $this->middleware(function (Request $request, $next) {
            $user = auth()->user();

            if (!$user || !(method_exists($user, 'isManager') && ($user->isManager() || $user->isCeo()))) {
                abort(403, 'Bạn không có quyền truy cập mục này.');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = IeltsPlacementAttempt::query()->with('user');

        // ── Lọc theo trạng thái ──
        $status = $request->input('status');
        if ($status === 'archived') {
            $query->where('status', IeltsPlacementAttempt::STATUS_ARCHIVED);
        } elseif (!empty($status)) {
            $query->where('status', $status);
        } else {
            // Mặc định ẩn bản đã lưu trữ để danh sách gọn.
            $query->where('status', '!=', IeltsPlacementAttempt::STATUS_ARCHIVED);
        }

        if ($level = $request->input('level')) {
            $query->where('final_level', $level);
        }

        // ── Tìm theo tên / email học viên ──
        if ($search = trim((string) $request->input('search'))) {
            $userIds = User::query()
                ->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%")
                ->pluck('id');

            $query->whereIn('user_id', $userIds);
        }

        if ($request->input('guest') === '1') {
            $query->whereNull('user_id');
        }

        $attempts = $query->orderByDesc('started_at')
            ->paginate(25)
            ->appends($request->query());

        $stats = [
            'total'       => IeltsPlacementAttempt::count(),
            'completed'   => IeltsPlacementAttempt::where('status', 'completed')->count(),
            'in_progress' => IeltsPlacementAttempt::whereIn('status', ['in_progress', 'speaking'])->count(),
            'archived'    => IeltsPlacementAttempt::where('status', IeltsPlacementAttempt::STATUS_ARCHIVED)->count(),
            'guest'       => IeltsPlacementAttempt::whereNull('user_id')->count(),
        ];

        $levels = PlacementTest::query()->distinct()->orderByRaw("FIELD(level,'A1','A2','B1','B2','B2+')")->pluck('level');

        return view('admin.placement_results.index', compact('attempts', 'stats', 'levels'));
    }

    /**
     * Reset 1 bài thi: chuyển sang 'archived' để học viên thi lại được ngay
     * (hasPlacementResult() chỉ đếm status='completed'), dữ liệu vẫn còn.
     */
    public function reset(Request $request, IeltsPlacementAttempt $attempt)
    {
        if (!$attempt->user_id) {
            return back()->with('toast', [
                'status' => 'error',
                'title'  => 'Không áp dụng được',
                'msg'    => 'Bài của khách chưa đăng nhập không reset được — hãy dùng nút Xoá để dọn dữ liệu.',
            ]);
        }

        if ($attempt->status === IeltsPlacementAttempt::STATUS_ARCHIVED) {
            return back()->with('toast', [
                'status' => 'warning',
                'title'  => 'Đã lưu trữ trước đó',
                'msg'    => 'Bài này đang ở trạng thái lưu trữ, học viên đã có thể thi lại.',
            ]);
        }

        $attempt->update([
            'status'         => IeltsPlacementAttempt::STATUS_ARCHIVED,
            'archived_at'    => now(),
            'archived_by'    => auth()->id(),
            'archive_reason' => trim((string) $request->input('reason')) ?: null,
        ]);

        return back()->with('toast', [
            'status' => 'success',
            'title'  => 'Đã reset',
            'msg'    => 'Học viên ' . $this->userLabel($attempt) . ' có thể làm lại Placement Test.',
        ]);
    }

    /**
     * Reset nhiều bài cùng lúc (chọn bằng checkbox ở danh sách).
     */
    public function bulkReset(Request $request)
    {
        $ids = array_filter((array) $request->input('attempt_ids', []));

        if (empty($ids)) {
            return back()->with('toast', [
                'status' => 'error',
                'title'  => 'Chưa chọn bài nào',
                'msg'    => 'Hãy tick vào các dòng cần reset trước.',
            ]);
        }

        $affected = IeltsPlacementAttempt::whereIn('id', $ids)
            ->whereNotNull('user_id')
            ->where('status', '!=', IeltsPlacementAttempt::STATUS_ARCHIVED)
            ->update([
                'status'      => IeltsPlacementAttempt::STATUS_ARCHIVED,
                'archived_at' => now(),
                'archived_by' => auth()->id(),
            ]);

        return back()->with('toast', [
            'status' => 'success',
            'title'  => 'Đã reset',
            'msg'    => "Đã reset {$affected} bài thi. Các học viên này có thể làm lại ngay.",
        ]);
    }

    /**
     * Khôi phục bài đã lưu trữ về lại trạng thái completed.
     * Dùng khi bấm reset nhầm.
     */
    public function restore(IeltsPlacementAttempt $attempt)
    {
        if ($attempt->status !== IeltsPlacementAttempt::STATUS_ARCHIVED) {
            return back()->with('toast', [
                'status' => 'error',
                'title'  => 'Không hợp lệ',
                'msg'    => 'Chỉ khôi phục được bài đang ở trạng thái lưu trữ.',
            ]);
        }

        // Bài chưa từng hoàn tất thì trả về 'abandoned' chứ không phải 'completed'.
        $attempt->update([
            'status'         => $attempt->completed_at ? 'completed' : 'abandoned',
            'archived_at'    => null,
            'archived_by'    => null,
            'archive_reason' => null,
        ]);

        return back()->with('toast', [
            'status' => 'success',
            'title'  => 'Đã khôi phục',
            'msg'    => 'Bài thi đã trở lại danh sách kết quả.',
        ]);
    }

    /**
     * Xoá vĩnh viễn: attempt + toàn bộ câu trả lời + file ghi âm Speaking.
     * Không hoàn tác được — chỉ dùng để dọn dữ liệu rác (bài của khách, bài test thử).
     */
    public function destroy(IeltsPlacementAttempt $attempt)
    {
        $label = $this->userLabel($attempt);
        $recordingPath = $attempt->speaking_recording_path;

        DB::transaction(function () use ($attempt) {
            IeltsPlacementAttemptAnswer::where('attempt_id', $attempt->id)->delete();
            $attempt->delete();
        });

        // Xoá file sau khi transaction thành công — file đã xoá thì rollback
        // cũng không lấy lại được, nên không đặt trong transaction.
        if ($recordingPath) {
            Storage::disk('public')->delete($recordingPath);
        }

        return back()->with('toast', [
            'status' => 'success',
            'title'  => 'Đã xoá',
            'msg'    => 'Đã xoá vĩnh viễn bài thi của ' . $label . '.',
        ]);
    }

    private function userLabel(IeltsPlacementAttempt $attempt): string
    {
        return $attempt->user ? $attempt->user->full_name : 'Khách #' . $attempt->id;
    }
}