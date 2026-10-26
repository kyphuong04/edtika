<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Yêu cầu tư vấn lộ trình học — học viên gửi từ trang kết quả Placement Test,
 * admin/manager/ceo xử lý ở trang admin "Yêu cầu tư vấn".
 */
class PlacementConsultRequest extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_CLOSED = 'closed';

    /** status => [nhãn, class badge của admin] */
    public const STATUSES = [
        self::STATUS_NEW       => ['Mới', 'badge-danger'],
        self::STATUS_CONTACTED => ['Đã liên hệ', 'badge-warning'],
        self::STATUS_CLOSED    => ['Đã đóng', 'badge-success'],
    ];

    protected $table = 'placement_consult_requests';

    protected $fillable = [
        'attempt_id',
        'user_id',
        'full_name',
        'mobile',
        'email',
        'course_key',
        'course_title',
        'final_level',
        'status',
        'admin_note',
        'handled_by',
        'handled_at',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(IeltsPlacementAttempt::class, 'attempt_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'handled_by', 'id');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status][0] ?? (string) $this->status;
    }

    public function statusBadgeClass(): string
    {
        return self::STATUSES[$this->status][1] ?? 'badge-secondary';
    }

    /**
     * Số yêu cầu chưa xử lý — hiển thị badge trên sidebar admin. Nuốt lỗi để
     * sidebar không vỡ nếu môi trường nào đó chưa chạy migration.
     */
    public static function newCount(): int
    {
        static $count = null;

        if ($count === null) {
            try {
                $count = static::where('status', self::STATUS_NEW)->count();
            } catch (\Throwable $e) {
                $count = 0;
            }
        }

        return $count;
    }
}
