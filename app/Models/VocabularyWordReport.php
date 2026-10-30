<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Báo lỗi từ vựng do học viên gửi từ cửa sổ chi tiết từ (Từ điển & Flashcard).
 * Người nhận là giáo viên tạo bộ từ (teacher_id), xem tại
 * Panel > Bảng thông báo > Thông báo từ vựng lỗi.
 * Thời gian lưu dạng Unix timestamp (int) giống ielts_answer_reports.
 */
class VocabularyWordReport extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_RESOLVED = 'resolved';

    public const SOURCE_BUNDLE = 'bundle';
    public const SOURCE_ACADEMIC = 'academic';

    public const MESSAGE_MAX = 300;

    public const SOURCE_LABELS = [
        self::SOURCE_BUNDLE => 'Bộ từ khoá học',
        self::SOURCE_ACADEMIC => 'Academic Word List',
    ];

    protected $table = 'vocabulary_word_reports';

    protected $guarded = ['id'];

    protected $dateFormat = 'U';

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

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function isBundle(): bool
    {
        return $this->source === self::SOURCE_BUNDLE;
    }

    public function getSourceLabelAttribute(): string
    {
        return self::SOURCE_LABELS[$this->source] ?? ucfirst((string) $this->source);
    }

    /**
     * Người nhận báo lỗi của một bộ từ: chỉ khi người tạo bộ từ là giáo viên
     * (role teacher) và không phải chính người báo lỗi.
     */
    public static function recipientFor($creator, $reporter = null): ?\App\User
    {
        if (!$creator || !method_exists($creator, 'isTeacher') || !$creator->isTeacher()) {
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
