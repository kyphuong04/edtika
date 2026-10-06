<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Banner trang kết quả IELTS theo tỷ lệ làm đúng:
 *   low  : < 50%          — "Bạn sẽ làm tốt hơn mà!"
 *   mid  : 50% – 80%      — "Bạn đang làm rất tốt rồi!"
 *   high : > 80%          — "Bạn đã làm thật xuất sắc!"
 *
 * Manager/CEO đổi ảnh tại Admin > IELTS Tests > Banner trang kết quả.
 * Mức nào chưa upload thì dùng ảnh mặc định trong public/.
 */
class IeltsResultBanner extends Model
{
    public $timestamps = false;

    protected $table = 'ielts_result_banners';

    protected $guarded = ['id'];

    public const TIER_LOW = 'low';
    public const TIER_MID = 'mid';
    public const TIER_HIGH = 'high';

    private const CACHE_KEY = 'ielts_result_banners.paths';

    public const TIERS = [
        self::TIER_LOW => [
            'label' => 'Cần cố gắng',
            'rule' => 'Tỷ lệ đúng dưới 50%',
            'alt' => 'Bạn sẽ làm tốt hơn mà!',
            'default' => 'assets/images/ielts/result-banners/low.webp',
        ],
        self::TIER_MID => [
            'label' => 'Động viên',
            'rule' => 'Tỷ lệ đúng từ 50% đến 80%',
            'alt' => 'Bạn đang làm rất tốt rồi!',
            'default' => 'assets/images/ielts/result-banners/mid.webp',
        ],
        self::TIER_HIGH => [
            'label' => 'Khen ngợi',
            'rule' => 'Tỷ lệ đúng trên 80%',
            'alt' => 'Bạn đã làm thật xuất sắc!',
            'default' => 'assets/images/ielts/result-banners/high.webp',
        ],
    ];

    public function updater()
    {
        return $this->belongsTo(\App\User::class, 'updated_by');
    }

    public static function isValidTier(string $tier): bool
    {
        return array_key_exists($tier, self::TIERS);
    }

    /**
     * Tỷ lệ 0..1 -> mức banner. Không tính được tỷ lệ (VD đề chỉ có
     * Writing/Speaking đang chờ chấm) -> banner động viên.
     */
    public static function tierForRatio(?float $ratio): string
    {
        if ($ratio === null) {
            return self::TIER_MID;
        }

        if ($ratio < 0.5) {
            return self::TIER_LOW;
        }

        return $ratio <= 0.8 ? self::TIER_MID : self::TIER_HIGH;
    }

    /** ['url' => ..., 'alt' => ...] cho trang kết quả. */
    public static function forTier(string $tier): array
    {
        $tier = self::isValidTier($tier) ? $tier : self::TIER_MID;
        $path = self::customPaths()[$tier] ?? null;

        return [
            'tier' => $tier,
            'url' => $path ? Storage::disk('public')->url($path) : asset(self::TIERS[$tier]['default']),
            'alt' => self::TIERS[$tier]['alt'],
        ];
    }

    /** Dữ liệu cho trang quản lý của manager. */
    public static function overview(): array
    {
        $rows = self::with('updater')->get()->keyBy('tier');
        $out = [];

        foreach (self::TIERS as $tier => $meta) {
            $row = $rows->get($tier);
            $isCustom = $row && !empty($row->image_path);

            $out[$tier] = $meta + [
                'tier' => $tier,
                'is_custom' => $isCustom,
                'url' => $isCustom ? Storage::disk('public')->url($row->image_path) : asset($meta['default']),
                'updated_at' => $isCustom && $row->updated_at ? (int) $row->updated_at : null,
                'updated_by_name' => $isCustom && $row->updater
                    ? ($row->updater->full_name ?? $row->updater->name ?? null)
                    : null,
            ];
        }

        return $out;
    }

    /** tier => image_path của các mức đã upload. Cache tới khi manager đổi ảnh. */
    public static function customPaths(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return self::query()
                ->whereNotNull('image_path')
                ->pluck('image_path', 'tier')
                ->all();
        });
    }

    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}