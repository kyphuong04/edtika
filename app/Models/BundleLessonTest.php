<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * GĐ6 - Đề "Bài tập khóa học" (ielts_tests.type = lesson) gắn vào một lesson (webinar_chapters).
 * role = exercise: Bài Tập giáo viên giao thêm cho cả lớp (luyện thêm, không bắt buộc)
 * role = post: Post Lesson (không giới hạn số bài, chấm tự động, không ràng buộc mở lesson sau)
 */
class BundleLessonTest extends Model
{
    const ROLE_EXERCISE = 'exercise';
    const ROLE_POST = 'post';

    public static $roles = [self::ROLE_EXERCISE, self::ROLE_POST];

    protected $table = 'bundle_lesson_tests';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function chapter()
    {
        return $this->belongsTo(WebinarChapter::class, 'chapter_id', 'id');
    }

    public function test()
    {
        return $this->belongsTo(IeltsTest::class, 'ielts_test_id', 'id');
    }

    /**
     * Trang lesson của học viên có gắn đề này (để quay lại sau khi làm xong), null nếu không tìm được.
     */
    public static function lessonUrlForUser(int $testId, $user): ?string
    {
        if (empty($user)) {
            return null;
        }

        $links = static::query()->where('ielts_test_id', $testId)->get(['chapter_id']);

        foreach ($links as $link) {
            $chapter = WebinarChapter::query()->find($link->chapter_id, ['id', 'webinar_id']);
            if (empty($chapter)) {
                continue;
            }

            $bundles = Bundle::query()
                ->where('status', Bundle::$active)
                ->whereHas('bundleWebinars', function ($query) use ($chapter) {
                    $query->where('webinar_id', $chapter->webinar_id);
                })
                ->get();

            foreach ($bundles as $bundle) {
                if ($bundle->checkUserHasBought($user)) {
                    return '/panel/courses/purchases/' . $bundle->slug . '/lessons/' . $chapter->id;
                }
            }
        }

        return null;
    }
}
