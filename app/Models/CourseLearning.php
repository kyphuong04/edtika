<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLearning extends Model
{
    use \App\Models\Traits\TriggersBundleCompletion;

    protected $table = 'course_learning';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    /**
     * Các cột học viên được phép đánh dấu "đã học", kèm model của item tương ứng.
     * Không bao giờ dùng trực tiếp tên cột lấy từ request.
     */
    const ITEM_COLUMNS = [
        'file_id' => File::class,
        'session_id' => Session::class,
        'text_lesson_id' => TextLesson::class,
    ];

    /**
     * Kiểm tra cột có nằm trong whitelist và item có thuộc đúng khóa học (webinar) không.
     */
    public static function isValidItem($column, $itemId, $webinarId): bool
    {
        if (!is_string($column) or !array_key_exists($column, self::ITEM_COLUMNS) or empty($itemId)) {
            return false;
        }

        $modelClass = self::ITEM_COLUMNS[$column];

        return $modelClass::query()
            ->where('id', $itemId)
            ->where('webinar_id', $webinarId)
            ->exists();
    }

    /* ---------- GĐ4: kiểm tra hoàn thành bundle khi học xong video/bài đọc ---------- */

    public function bundleCompletionReached(): bool
    {
        return $this->wasRecentlyCreated;
    }

    public function bundleCompletionUserId(): ?int
    {
        return $this->user_id;
    }

    public function bundleCompletionWebinarId(): ?int
    {
        foreach (self::ITEM_COLUMNS as $column => $modelClass) {
            if (!empty($this->{$column})) {
                return $modelClass::query()->where('id', $this->{$column})->value('webinar_id');
            }
        }

        return null;
    }
}
