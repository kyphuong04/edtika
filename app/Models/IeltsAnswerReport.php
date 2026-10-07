<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Báo lỗi đáp án / phần giải thích do học viên gửi từ trang chữa bài.
 * Người nhận là giáo viên tạo đề (teacher_id), xem tại
 * Panel > Bảng thông báo > Thông báo đề lỗi.
 * Thời gian lưu dạng Unix timestamp (int) giống các bảng IELTS khác.
 */
class IeltsAnswerReport extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_RESOLVED = 'resolved';

    public const MESSAGE_MAX = 300;

    protected $table = 'ielts_answer_reports';

    protected $guarded = ['id'];

    protected $dateFormat = 'U';

    public const SKILL_LABELS = [
        'listening' => 'IELTS Listening',
        'reading' => 'IELTS Reading',
        'writing' => 'IELTS Writing',
        'speaking' => 'IELTS Speaking',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(\App\User::class, 'teacher_id');
    }

    public function resolver()
    {
        return $this->belongsTo(\App\User::class, 'resolved_by');
    }

    public function test()
    {
        return $this->belongsTo(IeltsTest::class, 'test_id');
    }

    public function question()
    {
        return $this->belongsTo(IeltsTestQuestion::class, 'question_id');
    }

    public function attempt()
    {
        return $this->belongsTo(IeltsTestAttempt::class, 'attempt_id')->withoutGlobalScope('not_preview');
    }

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function getSkillLabelAttribute(): string
    {
        return self::SKILL_LABELS[$this->skill] ?? ('IELTS ' . ucfirst((string) $this->skill));
    }

    /**
     * Giáo viên tạo đề có nhận báo lỗi không. Chỉ đề do role teacher tạo mới
     * có nút "Báo lỗi đáp án"; người tạo không tự báo lỗi đề của mình.
     */
    public static function recipientFor(?IeltsTest $test, $reporter = null): ?\App\User
    {
        $creator = $test ? $test->creator : null;

        if (!$creator || !$creator->isTeacher()) {
            return null;
        }

        if ($reporter && (int) $reporter->id === (int) $creator->id) {
            return null;
        }

        return $creator;
    }

    /** Số báo cáo chưa xử lý gửi tới 1 giáo viên (hiện trên sidebar). */
    public static function newCountFor(int $teacherId): int
    {
        return (int) self::where('teacher_id', $teacherId)
            ->where('status', self::STATUS_NEW)
            ->count();
    }
}
