<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthModalSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Manager / CEO thay ảnh + chữ của slider bên phải modal Đăng nhập / Đăng ký.
 * Kiểm quyền ngay trong controller (cùng cách IeltsResultBannerController).
 */
class AuthModalSlideController extends Controller
{
    private const UPLOAD_DIR = 'auth/modal_slides';

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $user = auth()->user();

            if (!$user || !method_exists($user, 'canManageAuthModalSlides') || !$user->canManageAuthModalSlides()) {
                abort(403, 'Bạn không có quyền truy cập mục này.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        return view('admin.auth_modal_slides.index', [
            'pageTitle' => 'Ảnh modal đăng nhập',
            'slides' => AuthModalSlide::overview(),
        ]);
    }

    public function update(Request $request, $slot)
    {
        abort_unless(AuthModalSlide::isValidSlot($slot), 404);
        $slot = (int)$slot;

        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=400',
            'title' => 'nullable|string|max:160',
            'subtitle' => 'nullable|string|max:255',
        ], [
            'image.image' => 'File tải lên phải là ảnh.',
            'image.mimes' => 'Chỉ nhận ảnh JPG, PNG hoặc WebP.',
            'image.max' => 'Ảnh tối đa 5MB.',
            'image.dimensions' => 'Ảnh cần rộng tối thiểu 400px để hiển thị nét.',
            'title.max' => 'Tiêu đề tối đa 160 ký tự.',
            'subtitle.max' => 'Mô tả tối đa 255 ký tự.',
        ]);

        $slide = AuthModalSlide::firstOrNew(['slot' => $slot]);
        $oldPath = $slide->image_path;
        $newPath = $oldPath;

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store(self::UPLOAD_DIR, 'public');
        }

        $slide->forceFill([
            'image_path' => $newPath,
            'title' => trim((string)$request->input('title')) ?: null,
            'subtitle' => trim((string)$request->input('subtitle')) ?: null,
            'updated_by' => auth()->id(),
            'updated_at' => time(),
        ])->save();

        $this->deleteFile($oldPath, $newPath);
        AuthModalSlide::flushCache();

        return redirect(getAdminPanelUrl('/auth-modal-slides') . '#slide-' . $slot)->with('toast', [
            'status' => 'success',
            'title' => 'Đã cập nhật',
            'msg' => "Slide {$slot} của modal đăng nhập đã được lưu.",
        ]);
    }

    public function reset($slot)
    {
        abort_unless(AuthModalSlide::isValidSlot($slot), 404);
        $slot = (int)$slot;

        $slide = AuthModalSlide::where('slot', $slot)->first();

        if ($slide) {
            $this->deleteFile($slide->image_path);
            $slide->delete();
            AuthModalSlide::flushCache();
        }

        return redirect(getAdminPanelUrl('/auth-modal-slides') . '#slide-' . $slot)->with('toast', [
            'status' => 'success',
            'title' => 'Đã khôi phục',
            'msg' => "Slide {$slot} trở về nội dung mặc định.",
        ]);
    }

    /** Chỉ xoá file nằm trong thư mục của tính năng này — không bao giờ đụng file khác. */
    private function deleteFile(?string $path, ?string $keep = null): void
    {
        if (!$path || $path === $keep || strpos($path, self::UPLOAD_DIR . '/') !== 0) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
