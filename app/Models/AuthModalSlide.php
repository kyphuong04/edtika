<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Slider bên phải modal Đăng nhập / Đăng ký (tối đa 5 slide, tự chuyển).
 *
 * Manager/CEO đổi ảnh + chữ tại Admin > Giao diện > Ảnh modal đăng nhập.
 * Vị trí nào chưa tuỳ chỉnh thì dùng slide trong cài đặt theme
 * (Admin > Themes > Authentication pages), cuối cùng là ảnh mặc định.
 */
class AuthModalSlide extends Model
{
    public $timestamps = false;

    protected $table = 'auth_modal_slides';

    protected $guarded = ['id'];

    public const SLOTS = [1, 2, 3, 4, 5];

    public const DEFAULT_IMAGE = 'store/icons/—Pngtree—abstract purple line wave background_5542852 1.png';

    private const CACHE_KEY = 'auth_modal_slides.rows';

    public function updater()
    {
        return $this->belongsTo(\App\User::class, 'updated_by');
    }

    public static function isValidSlot($slot): bool
    {
        return in_array((int)$slot, self::SLOTS, true);
    }

    /**
     * Slide cho modal: [['image' => url, 'title' => ..., 'subtitle' => ..., 'fill' => bool], ...]
     * Dùng chung cho modal ở layout và modal ở trang chủ.
     *
     * - Vị trí đã tuỳ chỉnh -> dùng nội dung của manager.
     * - Vị trí chưa tuỳ chỉnh -> dùng slide theme cùng vị trí (nếu theme có);
     *   theme không có slide ở vị trí đó thì bỏ qua, không lặp lại slide cũ.
     * - 'fill' = true khi ảnh là banner manager tải lên -> ảnh phủ kín khung slider.
     */
    public static function forModal(): array
    {
        $themeSlides = self::themeSlides(false);
        $custom = self::customRows();
        $slides = [];

        foreach (self::SLOTS as $index => $slot) {
            if (!empty($custom[$slot])) {
                $row = $custom[$slot];
                $hasCustomImage = !empty($row['image_path']);

                $slides[] = [
                    'image' => $hasCustomImage
                        ? Storage::disk('public')->url($row['image_path'])
                        : ($themeSlides[$index]['image'] ?? asset(self::DEFAULT_IMAGE)),
                    'title' => (string)($row['title'] ?? ''),
                    'subtitle' => (string)($row['subtitle'] ?? ''),
                    'fill' => $hasCustomImage,
                ];

                continue;
            }

            if (!empty($themeSlides[$index])) {
                $slides[] = $themeSlides[$index] + ['fill' => false];
            }
        }

        if (empty($slides)) {
            $slides[] = [
                'image' => asset(self::DEFAULT_IMAGE),
                'title' => '',
                'subtitle' => '',
                'fill' => false,
            ];
        }

        return $slides;
    }

    /** Dữ liệu cho trang quản lý của manager. */
    public static function overview(): array
    {
        $themeSlides = self::themeSlides(false);
        $rows = self::with('updater')->get()->keyBy('slot');
        $out = [];

        foreach (self::SLOTS as $index => $slot) {
            $row = $rows->get($slot);
            $isCustom = !empty($row);
            $hasCustomImage = $isCustom && !empty($row->image_path);

            $hasThemeSlide = !empty($themeSlides[$index]);

            $out[$slot] = [
                'slot' => $slot,
                'is_custom' => $isCustom,
                'has_custom_image' => $hasCustomImage,
                // Chưa tuỳ chỉnh và theme không có slide ở vị trí này -> modal không hiện slide này.
                'is_shown' => $isCustom || $hasThemeSlide,
                'image' => $hasCustomImage
                    ? Storage::disk('public')->url($row->image_path)
                    : ($themeSlides[$index]['image'] ?? asset(self::DEFAULT_IMAGE)),
                'title' => $isCustom ? (string)$row->title : (string)($themeSlides[$index]['title'] ?? ''),
                'subtitle' => $isCustom ? (string)$row->subtitle : (string)($themeSlides[$index]['subtitle'] ?? ''),
                'updated_at' => $isCustom && $row->updated_at ? (int)$row->updated_at : null,
                'updated_by_name' => $isCustom && $row->updater
                    ? ($row->updater->full_name ?? $row->updater->name ?? null)
                    : null,
            ];
        }

        return $out;
    }

    /**
     * Slide lấy từ cài đặt theme, tối đa count(SLOTS) phần tử.
     * $pad = true: lặp slide cuối cho đủ số vị trí (giống logic cũ);
     * $pad = false: chỉ trả đúng số slide theme thật sự có (ít nhất 1 nếu theme trống).
     */
    public static function themeSlides(bool $pad = true): array
    {
        $settings = function_exists('getThemeAuthenticationPagesSettings') ? getThemeAuthenticationPagesSettings() : [];

        $slides = (!empty($settings) && !empty($settings['slider_contents']) && is_array($settings['slider_contents']))
            ? array_values($settings['slider_contents'])
            : [];

        if (empty($slides)) {
            $slides = [[
                'image' => asset(self::DEFAULT_IMAGE),
                'title' => '',
                'subtitle' => '',
            ]];
        }

        $slides = array_slice($slides, 0, count(self::SLOTS));

        while ($pad && count($slides) < count(self::SLOTS)) {
            $slides[] = $slides[count($slides) - 1];
        }

        return array_map(function ($slide) {
            return [
                'image' => $slide['image'] ?? asset(self::DEFAULT_IMAGE),
                'title' => $slide['title'] ?? '',
                'subtitle' => $slide['subtitle'] ?? '',
            ];
        }, $slides);
    }

    /** slot => [image_path, title, subtitle] của các vị trí đã tuỳ chỉnh. Cache tới khi manager đổi. */
    public static function customRows(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                return self::query()
                    ->get(['slot', 'image_path', 'title', 'subtitle'])
                    ->keyBy('slot')
                    ->map(function ($row) {
                        return [
                            'image_path' => $row->image_path,
                            'title' => $row->title,
                            'subtitle' => $row->subtitle,
                        ];
                    })
                    ->all();
            });
        } catch (\Throwable $exception) {
            // Chưa chạy migration -> modal vẫn hiển thị slide theme như cũ.
            return [];
        }
    }

    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
