<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IeltsPlacementAttempt extends Model
{
    // Mỗi đề trắc nghiệm: tối đa 10 phút. Riêng Speaking: tối đa 7 phút.
    // Đồng hồ được tính RIÊNG cho từng bước (không còn dùng chung 1 mốc cho
    // cả 3 đề như trước) — xem currentStepTimeLimit()/remainingSeconds().
    public const TEST_TIME_SECONDS = 10 * 60;
    public const SPEAKING_TIME_SECONDS = 7 * 60;
    public const STATUS_ARCHIVED = 'archived';

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
        'scored_steps',
        'started_at',
        'current_step_started_at',
        'completed_at',
        'speaking_recording_path',
        'speaking_question_id',
        'archived_at',        // ← thêm
        'archived_by',        // ← thêm
        'archive_reason',     // ← thêm
    ];

    protected $casts = [
        'test_ids_taken'          => 'array',
        'levels_taken'            => 'array',
        'scores'                  => 'array',
        'started_at'              => 'datetime',
        'current_step_started_at' => 'datetime',
        'completed_at'            => 'datetime',
        'archived_at'             => 'datetime',   
        'scored_steps'            => 'integer',    
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
     * Giới hạn thời gian của BƯỚC HIỆN TẠI: đang làm đề trắc nghiệm -> 10 phút,
     * đang ở bước Speaking -> 7 phút.
     */
    public function currentStepTimeLimit(): int
    {
        return $this->status === 'speaking'
            ? self::SPEAKING_TIME_SECONDS
            : self::TEST_TIME_SECONDS;
    }

    /**
     * Số giây còn lại của BƯỚC HIỆN TẠI, tính từ current_step_started_at.
     * Không còn là đồng hồ chung cho cả 3 đề như bản cũ.
     */
    public function remainingSeconds(): int
    {
        if (!$this->current_step_started_at) {
            return $this->currentStepTimeLimit();
        }

        $elapsed = now()->diffInSeconds($this->current_step_started_at);

        return max(0, $this->currentStepTimeLimit() - $elapsed);
    }

    public function isTimeUp(): bool
    {
        return $this->remainingSeconds() <= 0;
    }

    /**
     * Đề thứ mấy trở đi KHÔNG được tính điểm (chỉ tham khảo) — dựa vào
     * scored_steps. Null nghĩa là mọi đề đều được tính.
     */
    public function isStepScored(int $stepIndexZeroBased): bool
    {
        if ($this->scored_steps === null) {
            return true;
        }

        return $stepIndexZeroBased < $this->scored_steps;
    }

    public function speakingQuestion(): BelongsTo
    {
        return $this->belongsTo(\App\Models\PlacementSpeakingQuestion::class, 'speaking_question_id', 'id');
    }
    
    public function isArchived(): bool
    {
        return $this->status === self::STATUS_ARCHIVED;
    }

    public function archivedBy()
    {
        return $this->belongsTo(\App\User::class, 'archived_by', 'id');
    }
}