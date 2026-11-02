<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IeltsShareBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Manager / CEO quản lý ảnh 1:1 dùng khi học viên chia sẻ bài làm IELTS.
 * Mỗi mức tải được nhiều ảnh, bật / tắt / xoá từng ảnh; hệ thống chọn
 * ngẫu nhiên 1 ảnh đang bật. Cùng quyền với Banner trang kết quả.
 */
class IeltsShareBannerController extends Controller
{
    /** Ảnh lệch 1:1 tối đa 3% (VD 1080×1100 vẫn nhận). */
    private const RATIO_TOLERANCE = 0.03;

    private const MAX_FILES = 10;

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $user = auth()->user();

            if (!$user || !method_exists($user, 'canManageIeltsResultBanners') || !$user->canManageIeltsResultBanners()) {
                abort(403, 'Bạn không có quyền truy cập mục này.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        return view('admin.ielts_tests.share_banners', [
            'pageTitle' => 'Banner chia sẻ bài làm',
            'tiers' => IeltsShareBanner::overview(),
        ]);
    }

    public function upload(Request $request, string $tier)
    {
        abort_unless(IeltsShareBanner::isValidTier($tier), 404);

        $request->validate([
            'images' => 'required|array|min:1|max:' . self::MAX_FILES,
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=600,min_height=600',
            'title' => 'nullable|string|max:120',
        ], [
            'images.required' => 'Hãy chọn ít nhất 1 ảnh.',
            'images.max' => 'Mỗi lần tải tối đa ' . self::MAX_FILES . ' ảnh.',
            'images.*.image' => 'File tải lên phải là ảnh.',
            'images.*.mimes' => 'Chỉ nhận ảnh JPG, PNG hoặc WebP.',
            'images.*.max' => 'Mỗi ảnh tối đa 5MB.',
            'images.*.dimensions' => 'Ảnh cần tối thiểu 600×600px để hiển thị nét.',
            'title.max' => 'Ghi chú tối đa 120 ký tự.',
        ]);

        // Kiểm tra tỷ lệ 1:1 cho TẤT CẢ ảnh trước khi lưu ảnh nào.
        $sizes = [];
        foreach ($request->file('images') as $index => $file) {
            $size = @getimagesize($file->getRealPath());
            if (!$size || $size[1] <= 0) {
                return back()->withErrors(['images' => 'Không đọc được kích thước ảnh "' . $file->getClientOriginalName() . '".']);
            }

            if (abs($size[0] / $size[1] - 1) > self::RATIO_TOLERANCE) {
                return back()->withErrors([
                    'images' => 'Ảnh "' . $file->getClientOriginalName() . '" có kích thước ' . $size[0] . '×' . $size[1]
                        . 'px — cần ảnh vuông 1:1 (VD 1080×1080px).',
                ]);
            }

            $sizes[$index] = $size;
        }

        $title = trim((string) $request->input('title', ''));
        $userId = auth()->id();

        foreach ($request->file('images') as $index => $file) {
            IeltsShareBanner::create([
                'tier' => $tier,
                'image_path' => $file->store(IeltsShareBanner::UPLOAD_DIR, 'public'),
                'title' => $title !== '' ? $title : null,
                'width' => min(65535, (int) $sizes[$index][0]),
                'height' => min(65535, (int) $sizes[$index][1]),
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }

        return redirect()->route('admin.ielts_share_banners.index')->with(['toast' => [
            'title' => 'Đã lưu',
            'msg' => 'Đã thêm ' . count($sizes) . ' ảnh cho mức "' . IeltsShareBanner::TIERS[$tier]['label'] . '".',
            'status' => 'success',
        ]]);
    }

    public function toggle($id)
    {
        $banner = IeltsShareBanner::findOrFail($id);

        $banner->forceFill([
            'is_active' => !$banner->is_active,
            'updated_by' => auth()->id(),
        ])->save();

        return redirect()->route('admin.ielts_share_banners.index')->with(['toast' => [
            'title' => 'Đã lưu',
            'msg' => $banner->is_active ? 'Đã bật ảnh.' : 'Đã tắt ảnh — ảnh này không còn được chọn khi chia sẻ.',
            'status' => 'success',
        ]]);
    }

    public function destroy($id)
    {
        $banner = IeltsShareBanner::findOrFail($id);
        $path = $banner->image_path;

        $banner->delete();
        $this->deleteFile($path);

        return redirect()->route('admin.ielts_share_banners.index')->with(['toast' => [
            'title' => 'Đã lưu',
            'msg' => 'Đã xoá ảnh.',
            'status' => 'success',
        ]]);
    }

    /** Chỉ xoá file nằm trong thư mục upload của tính năng này. */
    private function deleteFile(?string $path): void
    {
        if ($path && strpos($path, IeltsShareBanner::UPLOAD_DIR . '/') === 0) {
            Storage::disk('public')->delete($path);
        }
    }
}
