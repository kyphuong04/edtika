<?php

namespace App\Models\Traits;

use App\Services\Bundle\BundleCompletionService;

/**
 * GĐ4 - Gắn vào các model ghi nhận tiến độ (course_learning, quizzes_results, webinar_assignment_history).
 * Khi một bản ghi chuyển sang "đã xong", kiểm tra học viên đã hoàn thành bundle chưa để cấp chứng chỉ.
 * Chạy sau khi trả response nên không làm chậm thao tác của học viên/giáo viên.
 *
 * Model dùng trait phải khai báo:
 * - bundleCompletionUserId(): ?int
 * - bundleCompletionWebinarId(): ?int
 * - bundleCompletionReached(): bool  (bản ghi vừa chuyển sang trạng thái hoàn thành)
 */
trait TriggersBundleCompletion
{
    public static function bootTriggersBundleCompletion()
    {
        static::saved(function ($model) {
            if (!$model->bundleCompletionReached()) {
                return;
            }

            $userId = $model->bundleCompletionUserId();
            $webinarId = $model->bundleCompletionWebinarId();

            if (empty($userId)) {
                return;
            }

            dispatch(function () use ($userId, $webinarId) {
                app(BundleCompletionService::class)->handle((int)$userId, !empty($webinarId) ? (int)$webinarId : null);
            })->afterResponse();
        });
    }
}
