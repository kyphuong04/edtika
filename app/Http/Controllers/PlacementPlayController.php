<?php

namespace App\Http\Controllers;

use App\Models\IeltsPlacementAttempt;
use Illuminate\Http\Request;

class PlacementPlayController extends Controller
{
    public function __construct()
    {
        // Trang này chỉ dành cho học viên đã đăng nhập — khớp với logic ở navbar
        // (`data-open-auth-modal` chỉ áp dụng phía client cho khách chưa đăng nhập,
        // nhưng vẫn cần chặn ở server để tránh truy cập trực tiếp bằng URL khi chưa login).
        $this->middleware('auth');
    }

    /**
     * Điểm vào duy nhất từ navbar: /panel/ielts-tests/diagnostic
     * Quyết định điều hướng:
     *  - Đã có kết quả hoàn thành (hasPlacementResult) -> trang kết quả
     *  - Có phiên đang làm dở (in_progress)            -> tiếp tục làm bài
     *  - Chưa từng làm                                  -> tạo phiên mới, vào làm bài
     */
    public function entry(Request $request)
    {
        $user = $request->user();

        if ($user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = IeltsPlacementAttempt::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->latest('started_at')
            ->first();

        if (!$attempt) {
            $attempt = IeltsPlacementAttempt::create([
                'user_id'       => $user->id,
                'status'        => 'in_progress',
                'current_step'  => 1,
                'test_ids_taken' => [],
                'scores'        => [],
                'started_at'    => now(),
            ]);
        }

        return redirect()->route('placement.take');
    }

    /**
     * Trang làm bài. HIỆN TẠI ĐANG HARDCODE nội dung câu hỏi để xem giao diện trước —
     * chưa lấy đề thật từ bảng placement_tests/placement_questions (sẽ nối ở bước sau).
     */
    public function take(Request $request)
    {
        $user = $request->user();

        // Nếu đã có kết quả hoàn thành rồi mà cố vào lại trang làm bài -> đẩy sang trang kết quả.
        if ($user->hasPlacementResult()) {
            return redirect()->route('placement.result');
        }

        $attempt = IeltsPlacementAttempt::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->latest('started_at')
            ->first();

        // Không có phiên nào đang chạy (vd truy cập thẳng URL /take mà chưa qua /diagnostic) -> quay lại entry để tạo phiên.
        if (!$attempt) {
            return redirect()->route('placement.entry');
        }

        return view('web.placement.take', [
            'attempt' => $attempt,
        ]);
    }

    /**
     * Trang kết quả. HIỆN TẠI ĐANG HARDCODE — nếu chưa có attempt completed thật sự
     * trong DB thì hiển thị dữ liệu demo kèm ghi chú, để bạn xem giao diện trước.
     */
    public function result(Request $request)
    {
        $user = $request->user();

        $attempt = $user->latestPlacementResult;

        $isDemoData = false;
        if (!$attempt) {
            $isDemoData = true;
            // Dữ liệu demo — KHÔNG lưu DB, chỉ để hiển thị giao diện.
            $attempt = new IeltsPlacementAttempt([
                'status'          => 'completed',
                'current_step'    => 3,
                'test_ids_taken'  => [1, 2, 3],
                'scores'          => [7, 6, 8],
                'final_level'     => 'B1',
                'started_at'      => now()->subMinutes(12),
                'completed_at'    => now(),
            ]);
        }

        return view('web.placement.result', [
            'attempt'    => $attempt,
            'isDemoData' => $isDemoData,
        ]);
    }

    /**
     * DEMO ONLY — nút "Hoàn thành đề (demo)" ở trang take bấm vào đây để giả lập hoàn tất
     * bộ 3 đề, tạo kết quả completed, rồi chuyển sang trang result. Sẽ bị thay thế hoàn
     * toàn bởi engine chấm điểm thật + logic phân luồng ở bước sau.
     */
    public function demoComplete(Request $request)
    {
        $user = $request->user();

        $attempt = IeltsPlacementAttempt::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->latest('started_at')
            ->first();

        if ($attempt) {
            $attempt->update([
                'status'         => 'completed',
                'final_level'    => 'B1',
                'completed_at'   => now(),
                'test_ids_taken' => [1, 2, 3],
                'scores'         => [7, 6, 8],
            ]);
        }

        return redirect()->route('placement.result');
    }
}