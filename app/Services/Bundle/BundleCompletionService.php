<?php

namespace App\Services\Bundle;

use App\Models\Bundle;
use App\User;
use Illuminate\Support\Facades\Log;

/**
 * GĐ4 - Khi học viên hoàn thành toàn bộ khóa (mọi module + Mid-term/Final đã đạt)
 * thì cấp chứng chỉ bundle (nếu khóa bật chứng chỉ).
 *
 * Được gọi sau mỗi thay đổi tiến độ: xem xong video, quiz đạt, bài tập được chấm đạt.
 */
class BundleCompletionService
{
    /** @var BundleProgressService */
    private $progressService;

    public function __construct(BundleProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * @param int $userId học viên
     * @param int|null $webinarId module vừa có thay đổi; null = kiểm tra mọi bundle đã mua
     */
    public function handle(int $userId, ?int $webinarId = null): void
    {
        try {
            $user = User::query()->find($userId);
            if (empty($user)) {
                return;
            }

            $bundles = Bundle::query()
                ->where('status', Bundle::$active)
                ->where('certificate', true)
                ->when(!empty($webinarId), function ($query) use ($webinarId) {
                    $query->whereHas('bundleWebinars', function ($query) use ($webinarId) {
                        $query->where('webinar_id', $webinarId);
                    });
                })
                ->get();

            foreach ($bundles as $bundle) {
                if (!$bundle->checkUserHasBought($user)) {
                    continue;
                }

                $progress = $this->progressService->forUser($bundle, $user);

                if ($progress['completed']) {
                    // Hàm có sẵn, tự bỏ qua nếu đã cấp
                    $bundle->makeCertificateForUser($user);
                }
            }
        } catch (\Throwable $exception) {
            // Không được làm hỏng luồng nộp bài / làm quiz chỉ vì lỗi cấp chứng chỉ
            Log::error('BundleCompletionService: ' . $exception->getMessage(), [
                'user_id' => $userId,
                'webinar_id' => $webinarId,
            ]);
        }
    }
}
