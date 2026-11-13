<?php

namespace App\Services\Bundle;

use App\Models\Bundle;
use App\Models\Sale;
use App\Models\Webinar;
use Illuminate\Support\Collection;

/**
 * Chặn học viên vào module / lesson / Mid-term / Final đang bị khóa theo quy tắc của bundle.
 *
 * Chỉ áp dụng khi học viên có quyền vào module (webinar) NHỜ mua bundle.
 * Không áp dụng cho: admin, chủ module, giáo viên đồng giảng, học viên mua lẻ module.
 */
class BundleLearningGate
{
    const MODULE_LOCKED = 'module_locked';
    const LESSON_LOCKED = 'lesson_locked';
    const CHECKPOINT_LOCKED = 'checkpoint_locked';

    /** @var BundleProgressService */
    private $progressService;

    /** Cache tiến độ trong một request: [bundleId_userId => progress] */
    private $progressCache = [];

    public function __construct(BundleProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * @param Webinar $course module đang học
     * @param \App\User|null $user
     * @param int|null $chapterId lesson của item (null = chỉ kiểm tra module)
     * @param int|null $quizId nếu item là quiz (để nhận diện Mid-term/Final)
     * @return array|null null = được vào; ngược lại ['reason' => ..., 'bundle' => Bundle]
     */
    public function check(Webinar $course, $user, ?int $chapterId = null, ?int $quizId = null): ?array
    {
        if (empty($user) or $this->isExempt($course, $user)) {
            return null;
        }

        $bundles = $this->purchasedBundlesForWebinar($course, $user);

        if ($bundles->isEmpty()) {
            return null;
        }

        $firstBlock = null;

        // Học viên có thể mua nhiều bundle chứa cùng module: chỉ cần một bundle cho phép là được vào
        foreach ($bundles as $bundle) {
            $block = $this->checkInBundle($bundle, $course, $user, $chapterId, $quizId);

            if (is_null($block)) {
                return null;
            }

            if (is_null($firstBlock)) {
                $firstBlock = $block;
            }
        }

        return $firstBlock;
    }

    /**
     * Tiến độ của học viên trong bundle (có cache trong request).
     */
    public function progress(Bundle $bundle, $user): array
    {
        $key = $bundle->id . '_' . (!empty($user) ? $user->id : 0);

        if (!isset($this->progressCache[$key])) {
            $this->progressCache[$key] = $this->progressService->forUser($bundle, $user);
        }

        return $this->progressCache[$key];
    }

    /**
     * Các bundle active chứa module này mà học viên đã mua.
     */
    public function purchasedBundlesForWebinar(Webinar $course, $user): Collection
    {
        return Bundle::query()
            ->where('status', Bundle::$active)
            ->whereHas('bundleWebinars', function ($query) use ($course) {
                $query->where('webinar_id', $course->id);
            })
            ->get()
            ->filter(function (Bundle $bundle) use ($user) {
                return $bundle->checkUserHasBought($user);
            })
            ->values();
    }

    private function checkInBundle(Bundle $bundle, Webinar $course, $user, ?int $chapterId, ?int $quizId): ?array
    {
        $progress = $this->progress($bundle, $user);

        // Mid-term / Final: chỉ theo điều kiện mở của checkpoint
        if (!empty($quizId)) {
            $checkpoint = $this->progressService->findCheckpointByQuiz($progress, $quizId);

            if (!empty($checkpoint)) {
                return $checkpoint['unlocked'] ? null : $this->block(self::CHECKPOINT_LOCKED, $bundle);
            }
        }

        if (!$this->progressService->canAccessModule($progress, (int)$course->id)) {
            return $this->block(self::MODULE_LOCKED, $bundle);
        }

        if (!empty($chapterId) and !$this->progressService->canAccessLesson($progress, $chapterId)) {
            return $this->block(self::LESSON_LOCKED, $bundle);
        }

        return null;
    }

    private function isExempt(Webinar $course, $user): bool
    {
        if ($user->isAdmin() or $course->isOwner($user->id)) {
            return true;
        }

        if (method_exists($course, 'isPartnerTeacher') and $course->isPartnerTeacher($user->id)) {
            return true;
        }

        // Mua lẻ module thì không bị ràng buộc bởi quy tắc của bundle
        return Sale::query()
            ->where('buyer_id', $user->id)
            ->where('webinar_id', $course->id)
            ->whereNull('refund_at')
            ->exists();
    }

    private function block(string $reason, Bundle $bundle): array
    {
        return [
            'reason' => $reason,
            'bundle' => $bundle,
        ];
    }
}
