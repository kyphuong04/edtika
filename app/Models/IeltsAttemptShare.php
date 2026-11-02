<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Link chia sẻ công khai của 1 bài làm IELTS (/ielts-share/{token}).
 * Mỗi bài làm có tối đa 1 link; tắt rồi bật lại vẫn giữ nguyên link.
 */
class IeltsAttemptShare extends Model
{
    protected $table = 'ielts_attempt_shares';

    protected $guarded = ['id'];

    protected $dateFormat = 'U';

    protected $casts = [
        'is_active' => 'boolean',
        'views' => 'integer',
    ];

    public function attempt()
    {
        return $this->belongsTo(IeltsTestAttempt::class, 'attempt_id')->withoutGlobalScope('not_preview');
    }

    public function banner()
    {
        return $this->belongsTo(IeltsShareBanner::class, 'banner_id');
    }

    public static function newToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    public function url(): string
    {
        return route('ielts_share.show', $this->token);
    }

    /**
     * Đồng bộ ảnh với mức điểm hiện tại: đổi mức (VD Writing vừa chấm xong)
     * hoặc ảnh đang dùng bị manager tắt / xoá -> chọn lại. Không lưu DB.
     *
     * @return bool true nếu tier / banner_id thay đổi
     */
    public function syncBanner(string $tier): bool
    {
        $current = $this->banner_id ? IeltsShareBanner::find($this->banner_id) : null;
        $valid = $current && $current->is_active && $current->tier === $tier;

        // Đang dùng ảnh mặc định nhưng manager vừa thêm ảnh cho mức này -> chọn lại.
        if (!$this->banner_id && $this->tier === $tier && IeltsShareBanner::activeCount($tier) === 0) {
            $valid = true;
        }

        if ($this->tier === $tier && $valid) {
            return false;
        }

        $picked = IeltsShareBanner::pickFor($tier);
        $this->tier = $tier;
        $this->banner_id = $picked ? $picked->id : null;

        return true;
    }

    /** ['id', 'url', 'alt', 'width', 'height'] của ảnh 1:1 đang dùng. */
    public function bannerData(): array
    {
        $tier = IeltsShareBanner::isValidTier($this->tier) ? $this->tier : IeltsResultBanner::TIER_MID;
        $banner = $this->banner_id ? IeltsShareBanner::find($this->banner_id) : null;

        if (!$banner || !$banner->is_active) {
            $banner = null;
        }

        return IeltsShareBanner::present($banner, $tier);
    }
}
