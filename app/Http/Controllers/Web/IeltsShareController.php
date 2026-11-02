<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Panel\IeltsAttemptShareController;
use App\Http\Controllers\Panel\IeltsTestController;
use App\Models\IeltsAttemptShare;
use App\Models\IeltsTestAttempt;
use Illuminate\Http\Request;

/**
 * Link chia sẻ bài làm IELTS — xem KHÔNG cần đăng nhập.
 *   /ielts-share/{token}          trang kết quả (Overall)
 *   /ielts-share/{token}/review   bài chữa Listening / Reading (?section=)
 * Writing / Speaking chỉ hiện điểm ở trang kết quả, không công khai bài viết / bản ghi âm.
 *
 * Thẻ og:* nằm trong layout design_1.web.layouts.ielts_share để Facebook,
 * Threads, LinkedIn… hiện Link Card (ảnh 1:1 theo mức điểm + tên đề).
 */
class IeltsShareController extends Controller
{
    private const LAYOUT = 'design_1.web.layouts.ielts_share';

    public function show(Request $request, string $token)
    {
        [$share, $attempt] = $this->resolve($token);
        $panel = app(IeltsTestController::class);

        $data = $panel->attemptResultData($attempt, route('ielts_share.review', $share->token), false);

        // Writing vừa chấm xong / manager đổi ảnh -> cập nhật ảnh của link.
        if ($share->syncBanner($data['tier'])) {
            $share->save();
        }

        $this->countView($request, $share, $attempt);

        return view('design_1.panel.ielts_tests.results', $data + $this->publicView($share, $attempt) + [
            'pageTitle' => IeltsAttemptShareController::shareTitle($attempt),
            'canRetake' => false,
            'retakeUrl' => null,
            'backUrl' => url('/'),
            'isMentorPreview' => false,
            'mentorPreviewExitUrl' => null,
            'shareMeta' => null,
        ]);
    }

    public function review(Request $request, string $token)
    {
        [$share, $attempt] = $this->resolve($token);
        $public = $this->publicView($share, $attempt);

        return app(IeltsTestController::class)->renderReviewPage($attempt, $request, [
            'public' => true,
            'isOwner' => false,
            'viewer' => auth()->user(),
            'reviewUrl' => route('ielts_share.review', $share->token),
            'resultsUrl' => $share->url(),
            'logoUrl' => url('/'),
            'studentName' => $public['publicStudentName'],
            'cta' => $public['cta'],
            'layoutView' => self::LAYOUT,
            'viewExtra' => [
                'og' => $public['og'],
            ],
        ]);
    }

    /** @return array{0: IeltsAttemptShare, 1: IeltsTestAttempt} */
    private function resolve(string $token): array
    {
        abort_unless(preg_match('/^[A-Za-z0-9]{20,40}$/', $token), 404);

        $share = IeltsAttemptShare::where('token', $token)->where('is_active', true)->firstOrFail();

        $attempt = IeltsTestAttempt::withoutGlobalScope('not_preview')
            ->with([
                'test.sections.parts.questionGroups',
                'test.sections.questions.questionGroup',
                'answers',
                'user',
            ])
            ->find($share->attempt_id);

        // Bài đã làm lại (archived) vẫn xem được — đó là kết quả học viên đã chia sẻ.
        $viewable = $attempt
            && $attempt->test
            && empty($attempt->is_preview)
            && (int) $attempt->user_id === (int) $share->user_id
            && in_array($attempt->status, ['completed', IeltsTestAttempt::STATUS_ARCHIVED], true)
            && !empty($attempt->completed_at);

        abort_unless($viewable, 404);

        return [$share, $attempt];
    }

    /** Biến view chung cho 2 trang công khai (layout, og:*, tên học viên, nút kêu gọi). */
    private function publicView(IeltsAttemptShare $share, IeltsTestAttempt $attempt): array
    {
        $image = $share->bannerData();
        $name = $attempt->user->full_name ?? $attempt->user->name ?? null;
        $viewer = auth()->user();
        $isOwner = $viewer && (int) $viewer->id === (int) $attempt->user_id;

        return [
            'layoutView' => self::LAYOUT,
            'attempt' => $attempt,
            'test' => $attempt->test,
            'publicStudentName' => $name ? 'Bài làm của ' . $name : 'Bài làm được chia sẻ',
            'cta' => $isOwner ? null : [
                'label' => 'Làm thử đề này',
                'url' => route('panel.ielts_tests.show', $attempt->test_id),
            ],
            'og' => [
                'title' => IeltsAttemptShareController::shareTitle($attempt),
                'description' => IeltsAttemptShareController::DESCRIPTION,
                'url' => $share->url(),
                'image' => $image,
            ],
        ];
    }

    /** Đếm lượt xem: bỏ qua chính chủ, mỗi phiên trình duyệt chỉ tính 1 lần. */
    private function countView(Request $request, IeltsAttemptShare $share, IeltsTestAttempt $attempt): void
    {
        if ((int) auth()->id() === (int) $attempt->user_id) {
            return;
        }

        $key = 'ielts_share_viewed.' . $share->id;
        if ($request->hasSession() && $request->session()->has($key)) {
            return;
        }

        IeltsAttemptShare::where('id', $share->id)->increment('views');

        if ($request->hasSession()) {
            $request->session()->put($key, 1);
        }
    }
}
