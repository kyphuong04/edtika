<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IeltsPlacementAttempt extends Model
{
    public const TOTAL_TIME_SECONDS = 10 * 60; // 10 phút chung cho tối đa 3 đề

    protected $fillable = [
        'user_id',
        'status',
        'current_step',
        'current_test_id',
        'test_ids_taken',
        'levels_taken',
        'scores',
        'current_level',
        'final_level',
        'started_at',
        'completed_at',
        'speaking_recording_path',
    ];

    protected $casts = [
        'test_ids_taken' => 'array',
        'levels_taken'   => 'array',
        'scores'         => 'array',
        'started_at'     => 'datetime',
        'completed_at'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Số giây còn lại trong đồng hồ đếm 10 phút CHUNG cho cả 3 đề
     * (tính từ started_at, không reset khi chuyển sang đề tiếp theo).
     */
    public function remainingSeconds(): int
    {
        if (!$this->started_at) {
            return self::TOTAL_TIME_SECONDS;
        }

        $elapsed = now()->diffInSeconds($this->started_at);

        return max(0, self::TOTAL_TIME_SECONDS - $elapsed);
    }

    public function isTimeUp(): bool
    {
        return $this->remainingSeconds() <= 0;
    }
}