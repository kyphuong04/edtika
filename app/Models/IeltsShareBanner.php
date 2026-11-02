<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Ảnh 1:1 khi chia sẻ bài làm IELTS lên mạng xã hội.
 *
 * Mức ảnh dùng CÙNG ngưỡng với banner trang kết quả
 * (IeltsResultBanner::tierForRatio): < 50% tiếc nuối, 50–80% động viên,
 * > 80% khen ngợi. Mỗi mức có nhiều ảnh, chọn ngẫu nhiên 1 ảnh đang bật;
 * mức chưa có ảnh nào bật -> ảnh mặc định trong public/.
 */
class IeltsShareBanner extends Model
{
    public const UPLOAD_DIR = 'ielts/share_banners';

    /** Kích thước ảnh mặc định (px) — khai báo og:image:width/height. */
    public const DEFAULT_SIZE = 1080;

    public const TIERS = [
        IeltsResultBanner::TIER_LOW => [
            'label' => 'Tiếc nuối',
            'rule' => 'Tỷ lệ đúng dưới 50%',
            'alt' => 'Babe em cần học thêm gòi',
            'default' => 'assets/images/ielts/share-banners/low.png',
        ],
        IeltsResultBanner::TIER_MID => [
            'label' => 'Động viên',
            'rule' => 'Tỷ lệ đúng từ 50% đến 80%',
            'alt' => 'Chuẩn bài rồi đó nha',
            'default' => 'assets/images/ielts/share-banners/mid.png',
        ],
        IeltsResultBanner::TIER_HIGH => [
            'label' => 'Khen ngợi',
            'rule' => 'Tỷ lệ đúng trên 80%',
            'alt' => 'Mãi keo như này nha',
            'default' => 'assets/images/ielts/share-banners/high.png',
        ],
    ];

    protected $table = 'ielts_share_banners';

    protected $guarded = ['id'];

    protected $dateFormat = 'U';

    protected $casts = [
        'is_active' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }

    public static function isValidTier(?string $tier): bool
    {
        return $tier !== null && array_key_exists($tier, self::TIERS);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** URL tuyệt đối (Facebook / LinkedIn cần URL đầy đủ cho og:image). */
    public function getUrlAttribute(): string
    {
        return self::absoluteUrl(Storage::disk('public')->url($this->image_path));
    }

    /**
     * Chọn ngẫu nhiên 1 ảnh đang bật của mức $tier. $exceptId: ảnh đang dùng —
     * bỏ qua nếu mức còn ảnh khác (nút "Đổi ảnh khác").
     */
    public static function pickFor(string $tier, ?int $exceptId = null): ?self
    {
        $query = self::active()->where('tier', $tier);

        if ($exceptId && (clone $query)->where('id', '!=', $exceptId)->exists()) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->inRandomOrder()->first();
    }

    public static function activeCount(string $tier): int
    {
        return self::active()->where('tier', $tier)->count();
    }

    /**
     * ['id', 'url', 'alt', 'width', 'height'] để hiển thị / làm og:image.
     * $banner null (mức chưa có ảnh) -> ảnh mặc định.
     */
    public static function present(?self $banner, string $tier): array
    {
        $tier = self::isValidTier($tier) ? $tier : IeltsResultBanner::TIER_MID;

        if ($banner) {
            return [
                'id' => $banner->id,
                'url' => $banner->url,
                'alt' => $banner->title ?: self::TIERS[$tier]['alt'],
                'width' => $banner->width ?: null,
                'height' => $banner->height ?: null,
            ];
        }

        return [
            'id' => null,
            'url' => self::absoluteUrl(asset(self::TIERS[$tier]['default'])),
            'alt' => self::TIERS[$tier]['alt'],
            'width' => self::DEFAULT_SIZE,
            'height' => self::DEFAULT_SIZE,
        ];
    }

    /** Dữ liệu trang quản lý: tier => meta + danh sách ảnh. */
    public static function overview(): array
    {
        $rows = self::with('creator')->orderByDesc('id')->get()->groupBy('tier');
        $out = [];

        foreach (self::TIERS as $tier => $meta) {
            $items = ($rows->get($tier) ?? collect())->map(fn (self $b) => [
                'id' => $b->id,
                'url' => $b->url,
                'title' => $b->title,
                'is_active' => $b->is_active,
                'width' => $b->width,
                'height' => $b->height,
                'created_at' => $b->created_at ? (int) $b->getRawOriginal('created_at') : null,
                'creator_name' => $b->creator ? ($b->creator->full_name ?? $b->creator->name ?? null) : null,
            ])->values()->all();

            $out[$tier] = $meta + [
                'tier' => $tier,
                'items' => $items,
                'active_count' => count(array_filter($items, fn ($i) => $i['is_active'])),
                'default_url' => asset($meta['default']),
            ];
        }

        return $out;
    }

    public static function absoluteUrl(string $url): string
    {
        return Str::startsWith($url, ['http://', 'https://', '//']) ? $url : url($url);
    }
}
