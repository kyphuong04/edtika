<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tiến độ xem một video của học viên.
 * Khi xem đủ ngưỡng sẽ tự tạo dòng course_learning để tính vào tiến độ khóa học.
 */
class VideoWatchProgress extends Model
{
    protected $table = 'video_watch_progress';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    public function isCompleted(): bool
    {
        return !empty($this->completed_at);
    }

    /**
     * Ghi nhận vị trí xem. Gọi định kỳ từ player (ví dụ mỗi 10-15 giây và khi video kết thúc).
     *
     * @param int $userId
     * @param File $file
     * @param int $position vị trí hiện tại (giây)
     * @param int|null $duration thời lượng video player đọc được (giây)
     */
    public static function record(int $userId, File $file, int $position, ?int $duration = null): self
    {
        $now = time();
        $duration = $duration ?: ($file->duration_seconds ?: null);

        $progress = self::query()->firstOrNew([
            'user_id' => $userId,
            'file_id' => $file->id,
        ]);

        if (!$progress->exists) {
            $progress->created_at = $now;
        }

        $position = max(0, $position);
        if (!empty($duration)) {
            $position = min($position, $duration);
            $progress->duration_seconds = $duration;
        }

        $progress->last_position = $position;
        $progress->max_position = max((int)$progress->max_position, $position);
        $progress->updated_at = $now;

        $ratio = (float)config('bundle_learning.video_complete_ratio', 0.9);

        if (!$progress->isCompleted() and !empty($duration) and $progress->max_position >= (int)floor($duration * $ratio)) {
            $progress->completed_at = $now;

            CourseLearning::query()->firstOrCreate(
                ['user_id' => $userId, 'file_id' => $file->id],
                ['created_at' => $now]
            );
        }

        $progress->save();

        // Lưu thời lượng vào file nếu teacher chưa nhập
        if (empty($file->duration_seconds) and !empty($duration)) {
            $file->duration_seconds = $duration;
            $file->save();
        }

        return $progress;
    }
}
