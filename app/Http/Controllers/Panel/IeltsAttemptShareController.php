<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsAttemptShare;
use App\Models\IeltsShareBanner;
use App\Models\IeltsTestAttempt;

/**
 * Cửa sổ "Chia sẻ bài làm" ở trang kết quả / chữa bài của học viên.
 *  - store  : mở cửa sổ -> tạo (hoặc bật lại) link công khai, trả ảnh 1:1 + link.
 *  - shuffle: "Đổi ảnh khác" — chọn ảnh khác cùng mức.
 *  - disable: "Tắt link" — người có link sẽ nhận 404.
 * Chỉ chính học viên làm bài; bài xem trước của giáo viên không chia sẻ được.
 */
class IeltsAttemptShareController extends Controller
{
    public const BRAND = 'Edtika';

    public const DESCRIPTION = 'Luyện IELTS thông minh cùng Edtika';

    /** Cấu hình cho partials/share_modal (null = không hiện nút chia sẻ). */
    public static function modalMeta(IeltsTestAttempt $attempt): array
    {
        return [
            'storeUrl' => route('panel.ielts_tests.share.store', $attempt->id),
            'shuffleUrl' => route('panel.ielts_tests.share.shuffle', $attempt->id),
            'disableUrl' => route('panel.ielts_tests.share.disable', $attempt->id),
        ];
    }

    public static function shareTitle(IeltsTestAttempt $attempt): string
    {
        return trim(($attempt->test->title ?? 'IELTS') . ' - ' . self::BRAND);
    }

    public static function caption(IeltsTestAttempt $attempt): string
    {
        return 'Mới làm xong ' . ($attempt->test->title ?? 'đề IELTS') . ' trên ' . self::BRAND . '. Kết quả của mình nè!';
    }

    public function store($attemptId)
    {
        $attempt = $this->ownAttempt($attemptId);
        $tier = $this->tierFor($attempt);

        $share = IeltsAttemptShare::firstOrNew(['attempt_id' => $attempt->id]);

        if (!$share->exists) {
            $share->forceFill([
                'user_id' => $attempt->user_id,
                'test_id' => $attempt->test_id,
                'token' => IeltsAttemptShare::newToken(),
            ]);
        }

        $share->is_active = true;
        $share->syncBanner($tier);
        $share->save();

        return $this->payload($attempt, $share);
    }

    public function shuffle($attemptId)
    {
        $attempt = $this->ownAttempt($attemptId);
        $share = IeltsAttemptShare::where('attempt_id', $attempt->id)->firstOrFail();

        $tier = $this->tierFor($attempt);
        $picked = IeltsShareBanner::pickFor($tier, $share->tier === $tier ? $share->banner_id : null);

        $share->forceFill([
            'tier' => $tier,
            'banner_id' => $picked ? $picked->id : null,
            'is_active' => true,
        ])->save();

        return $this->payload($attempt, $share);
    }

    public function disable($attemptId)
    {
        $attempt = $this->ownAttempt($attemptId);
        $share = IeltsAttemptShare::where('attempt_id', $attempt->id)->first();

        if ($share && $share->is_active) {
            $share->forceFill(['is_active' => false])->save();
        }

        return response()->json(['status' => 'ok', 'active' => false]);
    }

    private function ownAttempt($attemptId): IeltsTestAttempt
    {
        $attempt = IeltsTestAttempt::withoutGlobalScope('not_preview')
            ->with(['test.sections.parts.questionGroups', 'test.sections.questions', 'answers'])
            ->findOrFail($attemptId);

        if ((int) $attempt->user_id !== (int) auth()->id()) {
            abort(403);
        }

        if ($attempt->status !== 'completed' || !empty($attempt->is_preview)) {
            abort(response()->json([
                'status' => 'error',
                'message' => !empty($attempt->is_preview)
                    ? 'Bài làm xem trước không chia sẻ được.'
                    : 'Bài làm chưa được nộp.',
            ], 422));
        }

        return $attempt;
    }

    /** Mức ảnh theo đúng logic banner trang kết quả. */
    private function tierFor(IeltsTestAttempt $attempt): string
    {
        return app(IeltsTestController::class)
            ->attemptResultData($attempt, route('panel.ielts_tests.review', $attempt->id))['tier'];
    }

    private function payload(IeltsTestAttempt $attempt, IeltsAttemptShare $share)
    {
        $tier = $share->tier ?: 'mid';

        return response()->json([
            'status' => 'ok',
            'share' => [
                'url' => $share->url(),
                'active' => (bool) $share->is_active,
                'views' => (int) $share->views,
                'tier' => $tier,
                'tierLabel' => IeltsShareBanner::TIERS[$tier]['label'] ?? '',
                'image' => $share->bannerData(),
                'canShuffle' => IeltsShareBanner::activeCount($tier) > 1,
                'title' => self::shareTitle($attempt),
                'description' => self::DESCRIPTION,
                'caption' => self::caption($attempt),
            ],
        ]);
    }
}
