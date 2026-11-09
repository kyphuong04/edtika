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
    /** Bài nộp đến trễ tối đa chừng này giây sau khi hết giờ vẫn được chấm (trễ mạng / đồng hồ trình duyệt). */
    public const SUBMIT_GRACE_SECONDS = 60;
    /** Màn đếm ngược "5, 4, 3..." trước đề 1 — không tính vào 10 phút làm bài. */
    public const START_COUNTDOWN_SECONDS = 5;
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

        return max(0, $this->currentStepTimeLimit() - $this->elapsedSeconds());
    }

    /**
     * Số giây đã trôi qua của bước hiện tại — CÓ DẤU: âm nếu bước chưa bắt đầu
     * (đề 1 bắt đầu sau màn đếm ngược 5 giây). diffInSeconds() mặc định lấy trị
     * tuyệt đối nên phải truyền false.
     */
    public function elapsedSeconds(): int
    {
        if (!$this->current_step_started_at) {
            return 0;
        }

        return (int) $this->current_step_started_at->diffInSeconds(now(), false);
    }

    public function isTimeUp(): bool
    {
        return $this->remainingSeconds() <= 0;
    }

    /**
     * Đã quá hạn nộp của bước hiện tại + khoảng ân hạn chưa. Dùng để không
     * nhận đáp án của bài nộp quá muộn (VD: giữ trang mở rất lâu rồi mới nộp).
     */
    public function isPastSubmitGrace(): bool
    {
        if (!$this->current_step_started_at) {
            return false;
        }

        return $this->elapsedSeconds() > $this->currentStepTimeLimit() + self::SUBMIT_GRACE_SECONDS;
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