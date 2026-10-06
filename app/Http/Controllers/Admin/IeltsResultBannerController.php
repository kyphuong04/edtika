<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IeltsResultBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Manager / CEO thay 3 banner của trang kết quả IELTS.
 * Kiểm quyền ngay trong controller (cùng cách PlacementResultManagerController)
 * nên kể cả khi menu bị lộ, người không đủ quyền vẫn nhận 403.
 */
class IeltsResultBannerController extends Controller
{
    private const UPLOAD_DIR = 'ielts/result_banners';

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
        return view('admin.ielts_tests.result_banners', [
            'pageTitle' => 'Banner trang kết quả',
            'banners' => IeltsResultBanner::overview(),
        ]);
    }

    public function upload(Request $request, string $tier)
    {
        abort_unless(IeltsResultBanner::isValidTier($tier), 404);

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=800',
        ], [
            'image.required' => 'Hãy chọn ảnh banner.',
            'image.image' => 'File tải lên phải là ảnh.',
            'image.mimes' => 'Chỉ nhận ảnh JPG, PNG hoặc WebP.',
            'image.max' => 'Ảnh tối đa 5MB.',
            'image.dimensions' => 'Ảnh cần rộng tối thiểu 800px để hiển thị nét.',
        ]);

        $path = $request->file('image')->store(self::UPLOAD_DIR, 'public');

        $banner = IeltsResultBanner::firstOrNew(['tier' => $tier]);
        $oldPath = $banner->image_path;

        $banner->forceFill([
            'image_path' => $path,
            'updated_by' => auth()->id(),
            'updated_at' => time(),
        ])->save();

        $this->deleteFile($oldPath, $path);
        IeltsResultBanner::flushCache();

        return back()->with('toast', [
            'status' => 'success',
            'title' => 'Đã cập nhật banner',
            'msg' => 'Banner "' . IeltsResultBanner::TIERS[$tier]['label'] . '" đã được thay.',
        ]);
    }

    public function reset(string $tier)
    {
        abort_unless(IeltsResultBanner::isValidTier($tier), 404);

        $banner = IeltsResultBanner::where('tier', $tier)->first();

        if ($banner) {
            $this->deleteFile($banner->image_path);
            $banner->delete();
            IeltsResultBanner::flushCache();
        }

        return back()->with('toast', [
            'status' => 'success',
            'title' => 'Đã khôi phục',
            'msg' => 'Banner "' . IeltsResultBanner::TIERS[$tier]['label'] . '" trở về ảnh mặc định.',
        ]);
    }

    /** Chỉ xoá file nằm trong thư mục banner — không bao giờ đụng file khác. */
    private function deleteFile(?string $path, ?string $keep = null): void
    {
        if (!$path || $path === $keep || strpos($path, self::UPLOAD_DIR . '/') !== 0) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}