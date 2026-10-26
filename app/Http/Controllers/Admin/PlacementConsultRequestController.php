<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlacementConsultRequest;
use App\Support\Placement\PlacementCourseCatalog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Trang admin "Yêu cầu tư vấn" — danh sách form "Bạn muốn được tư vấn thêm
 * về lộ trình học?" mà học viên gửi từ trang kết quả Placement Test.
 *
 * Quyền: Admin, Manager, CEO (User::canManagePlacementConsults()).
 *
 * Manager/CEO dùng trang này trong /admin. Role Admin không vào được /admin
 * (AdminAuthenticate chỉ cho isSuperAdmin) nên dùng bản /panel —
 * Panel\PlacementConsultRequestController kế thừa lớp này, chỉ đổi view + route.
 *
 * Mọi thao tác trên trang chạy bằng AJAX (không tải lại trang):
 *   - index   : gọi kèm header X-Requested-With -> JSON { html, stats, total }
 *   - update  : Accept: application/json        -> JSON { row_html, stats, message }
 *   - destroy : Accept: application/json        -> JSON { stats, message }
 * Gọi kiểu form thường thì vẫn redirect + toast như cũ.
 */
class PlacementConsultRequestController extends Controller
{
    /** View trang danh sách */
    protected string $view = 'admin.placement_consults.index';

    /** Tiền tố tên route: {prefix}.index / .update / .destroy */
    protected string $routeName = 'admin.placement_consults';

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $user = auth()->user();

            if (!$user || !method_exists($user, 'canManagePlacementConsults') || !$user->canManagePlacementConsults()) {
                abort(403, 'Bạn không có quyền truy cập mục này.');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $requests = $this->filteredQuery($request)
            ->orderByRaw("FIELD(status, 'new', 'contacted', 'closed')")
            ->orderByDesc('created_at')
            ->paginate(20)
            ->appends($request->except('page'));

        $viewData = $this->sharedViewData() + [
            'requests' => $requests,
            'stats'    => $this->stats(),
        ];

        // Lọc / phân trang bằng AJAX: chỉ trả về phần bảng + số liệu.
        if ($request->ajax()) {
            return response()->json([
                'html'  => view('admin.placement_consults.partials.results', $viewData)->render(),
                'stats' => $viewData['stats'],
                'total' => $requests->total(),
            ]);
        }

        return view($this->view, $viewData + [
            'pageTitle'     => 'Yêu cầu tư vấn',
            'routeName'     => $this->routeName,
            'statuses'      => PlacementConsultRequest::STATUSES,
            'courseOptions' => PlacementCourseCatalog::options(),
            'levels'        => ['A1', 'A2', 'B1', 'B2', 'B2+'],
        ]);
    }

    /**
     * Cập nhật trạng thái + ghi chú nội bộ.
     */
    public function update(Request $request, $consult)
    {
        $consult = $this->resolveConsult($consult);

        $data = $request->validate([
            'status'     => ['required', Rule::in(array_keys(PlacementConsultRequest::STATUSES))],
            'admin_note' => 'nullable|string|max:2000',
        ], [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in'       => 'Trạng thái không hợp lệ.',
            'admin_note.max'  => 'Ghi chú tối đa 2000 ký tự.',
        ]);

        $consult->update([
            'status'     => $data['status'],
            'admin_note' => trim((string) ($data['admin_note'] ?? '')) ?: null,
            'handled_by' => auth()->id(),
            'handled_at' => now(),
        ]);

        $message = 'Yêu cầu của ' . $consult->full_name . ' chuyển sang "' . $consult->statusLabel() . '".';

        if ($request->expectsJson()) {
            $consult->load(['user', 'attempt', 'handler']);

            return response()->json([
                'ok'       => true,
                'message'  => $message,
                'row_html' => view('admin.placement_consults.partials.row', $this->sharedViewData() + ['item' => $consult])->render(),
                'stats'    => $this->stats(),
            ]);
        }

        return back()->with('toast', ['status' => 'success', 'title' => 'Đã cập nhật', 'msg' => $message]);
    }

    public function destroy(Request $request, $consult)
    {
        $consult = $this->resolveConsult($consult);
        $message = 'Đã xoá yêu cầu tư vấn của ' . $consult->full_name . '.';
        $consult->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'ok'      => true,
                'message' => $message,
                'stats'   => $this->stats(),
            ]);
        }

        return back()->with('toast', ['status' => 'success', 'title' => 'Đã xoá', 'msg' => $message]);
    }

    /**
     * Áp dụng bộ lọc từ query string.
     */
    protected function filteredQuery(Request $request)
    {
        $query = PlacementConsultRequest::query()->with(['user', 'attempt', 'handler']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($courseKey = $request->input('course')) {
            $query->where('course_key', $courseKey);
        }

        if ($level = $request->input('level')) {
            $query->where('final_level', $level);
        }

        // Tìm theo thông tin học viên đã điền vào form (không phải tên tài khoản)
        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    /**
     * Số lượng theo trạng thái — cho 4 thẻ thống kê và badge sidebar.
     */
    protected function stats(): array
    {
        $counts = PlacementConsultRequest::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'total'     => (int) $counts->sum(),
            'new'       => (int) ($counts[PlacementConsultRequest::STATUS_NEW] ?? 0),
            'contacted' => (int) ($counts[PlacementConsultRequest::STATUS_CONTACTED] ?? 0),
            'closed'    => (int) ($counts[PlacementConsultRequest::STATUS_CLOSED] ?? 0),
        ];
    }

    /**
     * Biến mà partial bảng / dòng cần (dùng cho cả trang đầy đủ lẫn JSON).
     */
    protected function sharedViewData(): array
    {
        $authUser = auth()->user();

        return [
            'pcRoute'        => $this->routeName,
            // Trang chi tiết bài làm (PlacementResultController) hiện chỉ mở cho Manager/CEO.
            'canViewAttempt' => $authUser->isManager() || $authUser->isCeo(),
        ];
    }

    /**
     * Nhận model (khi route có bind) hoặc id thuần — để chạy được ở cả /admin
     * lẫn /panel mà không phụ thuộc middleware binding của từng nhóm route.
     */
    protected function resolveConsult($consult): PlacementConsultRequest
    {
        return $consult instanceof PlacementConsultRequest
            ? $consult
            : PlacementConsultRequest::findOrFail((int) $consult);
    }
}
