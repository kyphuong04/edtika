@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <style>
        #panelContentScrollable { padding: 0 !important; }
        .panel-title-and-breadcrumb { display: none !important; }

        .bd-page { padding: 0px 32px 60px; }
        @media (max-width: 991px) { .bd-page { padding: 16px 16px 60px; } }

        .bd-layout {
            display: flex;
            gap: 28px;
            align-items: flex-start;
        }
        .bd-layout__main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .bd-layout__aside {
            width: 360px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        @media (max-width: 1200px) { .bd-layout__aside { width: 320px; } }
        @media (max-width: 991px) {
            .bd-layout { flex-direction: column; }
            .bd-layout__aside { width: 100%; }
        }

        .bd-welcome-card,
        .bd-panel-card,
        .bd-module-card,
        .bd-aside-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }

        .bd-welcome-card {
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .bd-welcome-card__title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: .4px;
            flex: 1;
            min-width: 120px;
        }
        .bd-welcome-card__progress {
            width: 100%;
            height: 5px;
            background: #e9ecef;
            border-radius: 4px;
            margin-top: 10px;
            overflow: hidden;
        }
        .bd-welcome-card__progress-bar {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
            transition: width .4s ease;
        }

        .bd-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .bd-btn-outline,
        .bd-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }
        .bd-btn-outline {
            border: 1.5px solid #bbb;
            background: #fff;
            color: #333;
        }
        .bd-btn-outline:hover { border-color: #511D99; color: #511D99; text-decoration: none; }
        .bd-btn-primary {
            background: #511D99;
            border: 1.5px solid #511D99;
            color: #fff;
        }
        .bd-btn-primary:hover { opacity: .92; color: #fff; text-decoration: none; }

        .bd-bell-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1.5px solid #ddd;
            background: #fff;
            color: #555;
            flex-shrink: 0;
            position: relative;
        }

        .bd-module-card {
            padding: 20px 24px 22px;
        }
        .bd-module-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }
        .bd-module-card__title { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px; }
        .bd-module-card__subtitle { font-size: 13px; color: #6b7280; }
        .bd-module-list {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 16px;
        }
        .bd-module-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 14px;
            color: #111827;
        }
        .bd-module-row:last-child { margin-bottom: 0; }
        .bd-module-row__action {
            font-size: 12px;
            font-weight: 500;
            color: #2563eb;
            text-decoration: none;
            padding: 4px 10px;
            border-radius: 6px;
            flex-shrink: 0;
        }
        .bd-module-row__action:hover { background: #eff6ff; text-decoration: none; }

        .bd-module-toggle-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            color: #374151;
            flex-shrink: 0;
        }
        .bd-module-toggle-btn.is-open { transform: rotate(180deg); }
        .bd-module-toggle-btn { transition: transform .18s ease; }

        .bd-module-items {
            overflow: hidden;
            max-height: 0;
            transition: max-height .36s ease, opacity .28s ease, padding .28s ease;
            opacity: 0;
            padding: 0 12px;
        }
        .bd-module-items.open {
            opacity: 1;
        }

        .bd-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            margin-bottom: 4px;
        }
        .bd-back-link:hover { color: #111827; text-decoration: none; }

        .bd-aside-card { padding: 18px 20px; }
        .bd-profile {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .bd-profile__avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eee;
            flex-shrink: 0;
        }
        .bd-profile__name { font-size: 15px; font-weight: 700; }
        .bd-profile__band { font-size: 12px; color: #666; margin-top: 3px; }

        .bd-word { text-align: center; padding: 8px 4px; }
        .bd-word__word { font-size: 16px; font-weight: 700; color: #1a1a1a; }
        .bd-word__pronunciation { font-size: 13px; color: #888; margin-top: 6px; }
        .bd-word__translation { font-size: 13px; color: #555; margin-top: 10px; }
        .bd-word__example { font-size: 13px; color: #333; margin-top: 10px; line-height: 1.6; }

        .bd-chat-card { padding: 0 !important; overflow: hidden; }
        .bd-chat__header {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .bd-chat__header-title { font-size: 13px; font-weight: 700; color: #333; display:flex; align-items:center; gap:5px; }
        .bd-chat__status { font-size: 11px; }
        .bd-chat__body {
            padding: 12px 14px;
            max-height: 280px;
            min-height: 80px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .bd-chat__empty-state { text-align: center; color: #aaa; padding: 24px 10px; font-size: 12px; line-height: 1.6; }
        .bd-bubble-row { display: flex; align-items: flex-end; gap: 7px; }
        .bd-bubble-row--me { flex-direction: row-reverse; }
        .bd-bubble-row__avatar { width: 26px; height: 26px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .bd-bubble {
            max-width: 78%;
            padding: 7px 11px;
            border-radius: 14px;
            font-size: 12px;
            line-height: 1.5;
            word-break: break-word;
        }
        .bd-bubble--other { background: #f0f0f0; color: #333; border-bottom-left-radius: 3px; }
        .bd-bubble--me { background: var(--primary); color: #fff; border-bottom-right-radius: 3px; }
        .bd-bubble__time { font-size: 10px; opacity: .55; display: block; margin-top: 3px; }
        .bd-chat__new-subject { padding: 8px 14px 0; }
        .bd-chat__new-subject label { font-size: 11px; color: #999; display: block; margin-bottom: 3px; }
        .bd-chat__new-subject input {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 12px;
            outline: none;
        }
        .bd-chat__new-subject input:focus { border-color: var(--primary); }
        .bd-chat__footer {
            padding: 8px 12px 10px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }
        .bd-chat__input {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 18px;
            padding: 7px 13px;
            font-size: 12px;
            resize: none;
            outline: none;
            max-height: 78px;
            overflow-y: auto;
            line-height: 1.45;
        }
        .bd-chat__input:focus { border-color: var(--primary); }
        .bd-chat__send-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }
        .bd-chat__send-btn svg { fill: #fff; }
    </style>
@endpush

@section('content')
@php
    $authUser = $authUser ?? auth()->user();
    $allowManage = $allowManage ?? ($authUser && ($authUser->isTeacher() || $authUser->isAdmin() || $authUser->isManager() || $authUser->isCeo() || $authUser->isOrganization()));
    $backUrl = $backUrl ?? '/panel/bundles';

    $bundleWebinars = collect();
    if (!empty($bundle->bundleWebinars)) {
        foreach ($bundle->bundleWebinars as $bundleWebinar) {
            if (!empty($bundleWebinar->webinar)) {
                $bundleWebinars->push($bundleWebinar->webinar);
            }
        }
    }

    $firstWebinar = $bundleWebinars->first();
    $firstWebinarLearningUrl = $firstWebinar ? url('/panel/courses/purchases/learning/' . $firstWebinar->slug) : '/panel/courses/purchases';
    $bundleProgress = isset($bundleProgress) ? $bundleProgress : 0;
    $mentorSupport = $mentorSupport ?? null;
    $mentorConversations = $mentorConversations ?? collect();
    $wordOfDay = $wordOfDay ?? null;
    $supportWebinarId = $firstWebinar ? $firstWebinar->id : null;
    $bundleModulesCount = $bundleModulesCount ?? $bundleWebinars->count();
    $bundleWebinarIds = $bundleWebinars->pluck('id')->filter()->values()->toArray();

    $teacherStudentsSupport = collect();
    $supportMessages = [
        'totalTickets' => 0,
        'openTickets' => 0,
        'supports' => collect(),
    ];

    try {
        if (!empty($bundleWebinarIds)) {
            $failedQuizItems = \App\Models\QuizzesResult::where('status', \App\Models\QuizzesResult::$failed)
                ->whereHas('quiz', function ($q) use ($bundleWebinarIds) {
                    $q->whereIn('webinar_id', $bundleWebinarIds);
                })
                ->with(['user', 'quiz'])
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get()
                ->filter(function ($r) {
                    return !empty($r->user);
                })
                ->map(function ($r) {
                    return [
                        'user' => $r->user,
                        'reason' => 'quiz_failed',
                        'detail' => $r->quiz->title ?? '',
                    ];
                });

            $lowBandItems = \App\Models\IeltsTestAttempt::whereNotNull('completed_at')
                ->whereNotNull('overall_band')
                ->where('overall_band', '<', 5.0)
                ->with(['user', 'test'])
                ->orderBy('completed_at', 'desc')
                ->limit(6)
                ->get()
                ->filter(function ($a) {
                    return !empty($a->user);
                })
                ->map(function ($a) {
                    return [
                        'user' => $a->user,
                        'reason' => 'low_band',
                        'detail' => number_format($a->overall_band, 1),
                    ];
                });

            $seen = [];
            $teacherStudentsSupport = $failedQuizItems->concat($lowBandItems)->filter(function ($item) use (&$seen) {
                $uid = $item['user']->id ?? null;
                if (!$uid || isset($seen[$uid])) {
                    return false;
                }
                $seen[$uid] = true;
                return true;
            })->take(6)->values();

            $supportQuery = \App\Models\Support::query()
                ->select('*', \Illuminate\Support\Facades\DB::raw("case
                    when status = 'open' then 'a'
                    when status = 'replied' then 'a'
                    when status = 'supporter_replied' then 'b'
                    when status = 'close' then 'c'
                    end as status_order
                "))
                ->whereIn('webinar_id', $bundleWebinarIds)
                ->whereHas('user');

            $supportMessages = [
                'totalTickets' => deepClone($supportQuery)->count(),
                'openTickets' => deepClone($supportQuery)->where('status', '!=', 'close')->count(),
                'supports' => deepClone($supportQuery)
                    ->orderBy('status_order', 'asc')
                    ->with([
                        'conversations' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        },
                        'user',
                    ])
                    ->limit(10)
                    ->get(),
            ];
        }
    } catch (\Exception $e) {}
@endphp

<div class="bd-page">
    <div class="bd-layout">
        <div class="bd-layout__main">

            @php
                $teacherId = $authUser->id ?? auth()->id();
                $webinarIds = \App\Models\Webinar::where('teacher_id', $teacherId)->pluck('id')->toArray();

                $webinarReviewCount = 0;
                $webinarReviewSum = 0.0;
                if (!empty($webinarIds)) {
                    $rq = \App\Models\WebinarReview::whereIn('webinar_id', $webinarIds)->where('status', 'active');
                    $webinarReviewCount = (int)$rq->count();
                    $webinarReviewSum = $webinarReviewCount > 0 ? (float)$rq->sum('rates') : 0.0;
                }

                $gradingRatingCount = (int) (class_exists('\App\Models\IeltsGradingRating') ? \App\Models\IeltsGradingRating::where('instructor_id', $teacherId)->count() : 0);
                $gradingRatingSum = $gradingRatingCount > 0 ? (float) (class_exists('\App\Models\IeltsGradingRating') ? \App\Models\IeltsGradingRating::where('instructor_id', $teacherId)->sum('rating') : 0) : 0.0;

                $totalRatings = $webinarReviewCount + $gradingRatingCount;
                $avgRating = $totalRatings > 0 ? min(round(($webinarReviewSum + $gradingRatingSum) / $totalRatings, 1), 5) : 0.0;

                $teacherRating = [
                    'avgRating' => $avgRating,
                    'reviewCount' => $totalRatings,
                ];
            @endphp

            @include('design_1.panel.dashboard.instructor.includes.teacher_welcome_bar')

            <a href="{{ $backUrl }}" class="bd-back-link">&#8592; Quay lại</a>

            <div class="bd-module-card">
                <div class="bd-module-card__header">
                    <div>
                        <div class="bd-module-card__title">{{ $bundle->title }}</div>
                        <div class="bd-module-card__subtitle">
                            Tổng số lượng bài học:
                            <strong>{{ $totalLessons }} bài</strong>
                        </div>
                    </div>
                    @if($allowManage)
                        <a href="/panel/bundles/{{ $bundle->id }}/module/create" class="bd-btn-primary">Tạo</a>
                    @endif
                </div>

                <div class="bd-module-list">
                    @if($bundleWebinars->count())
                        @foreach($bundleWebinars as $webinar)
                            <div class="bd-module-section">
                                <div class="bd-module-row bd-module-row--toggle" data-webinar-id="{{ $webinar->id }}">
                                    <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                                        <strong style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ mb_strtoupper($webinar->title) }}</strong>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        @if($allowManage)
                                            <a href="/panel/bundles/{{ $bundle->id }}/module/{{ $webinar->id }}/edit" class="bd-module-row__action" style="background:#fff;border:1px solid #511D99;color:#511D99;">Sửa</a>
                                            <form method="POST" action="/panel/bundles/{{ $bundle->id }}/module/{{ $webinar->id }}/delete" onsubmit="return confirm('Gửi yêu cầu xóa module này để manager/CEO phê duyệt?');" style="display:inline-block;margin:0;">
                                                @csrf
                                                <button type="submit" class="bd-module-row__action" style="background:#fff;border:1px solid #d02a2a;color:#d02a2a;">Yêu cầu xóa</button>
                                            </form>
                                            {{-- <button type="button" class="bd-module-toggle-btn" data-webinar-id="{{ $webinar->id }}" aria-expanded="false" title="Mở rộng">▾</button>
                                        @else
                                            <a href="/panel/courses/purchases/learning/{{ $webinar->slug }}" class="bd-module-row__action">{{ trans('update.continue_learning') }}</a>
                                            <button type="button" class="bd-module-toggle-btn" data-webinar-id="{{ $webinar->id }}" aria-expanded="false" title="Mở rộng">▾</button> --}}
                                        @endif
                                    </div>
                                </div>

                                <div class="bd-module-items" id="bd-module-items-{{ $webinar->id }}" style="display:none; margin-top:10px; padding:12px; background:#fff; border-radius:8px; border:1px solid #eef2f7;">
                                    <div class="bd-module-items__list">
                                        @php
                                            $items = collect();
                                            if (!empty($webinar->sessions)) { foreach ($webinar->sessions as $s) { $items->push((object)['type' => 'session', 'id' => $s->id, 'title' => $s->title ?? ('Session #' . $s->id)]); } }
                                            if (!empty($webinar->files)) { foreach ($webinar->files as $f) { $items->push((object)['type' => 'file', 'id' => $f->id, 'title' => $f->title ?? ($f->file_name ?? 'File #' . $f->id)]); } }
                                            if (!empty($webinar->textLessons)) { foreach ($webinar->textLessons as $t) { $items->push((object)['type' => 'text_lesson', 'id' => $t->id, 'title' => $t->title ?? ('Bài viết #' . $t->id)]); } }
                                            if (!empty($webinar->quizzes)) { foreach ($webinar->quizzes as $q) { $items->push((object)['type' => 'quiz', 'id' => $q->id, 'title' => $q->title ?? ('Quiz #' . $q->id), 'points' => $q->pass_mark ?? null]); } }
                                            if (!empty($webinar->assignments)) { foreach ($webinar->assignments as $a) { $items->push((object)['type' => 'assignment', 'id' => $a->id, 'title' => $a->title ?? ('Assignment #' . $a->id)]); } }
                                        @endphp

                                        @if($items->count())
                                            @foreach($items as $it)
                                                <div class="bd-item-row" style="display:flex;align-items:center;gap:12px;padding:10px 6px;border-bottom:1px solid #f3f4f6;">
                                                    <div style="width:44px;flex-shrink:0;display:flex;align-items:center;justify-content:center;border-radius:50%;background:#f3f4f6;color:#555;font-size:12px;">
                                                        @if($it->type == 'session')
                                                            ▶
                                                        @elseif($it->type == 'file')
                                                            ⬇
                                                        @elseif($it->type == 'text_lesson')
                                                            ✎
                                                        @elseif($it->type == 'quiz')
                                                            ?
                                                        @else
                                                            •
                                                        @endif
                                                    </div>
                                                    <div style="flex:1;min-width:0;">
                                                        <a href="/panel/courses/purchases/learning/{{ $webinar->slug }}?item={{ $it->id }}&type={{ $it->type }}" style="font-weight:600;color:#111;text-decoration:none;">{{ $it->title }}</a>
                                                        @if(!empty($it->points))
                                                            <div style="font-size:12px;color:#888;margin-top:4px;">{{ $it->points }} Điểm</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center" style="color:#9ca3af;padding:8px;">{{ trans('update.no_result_bundle_hint') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-40" style="color:#9ca3af; font-size:14px;">
                            {{ trans('update.no_result_bundle_hint') }}
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="bd-layout__aside">
            @include('design_1.panel.dashboard.instructor.includes.students_needing_support')

            @include('design_1.panel.dashboard.instructor.includes.support_messages')
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath('show_course') }}"></script>
    <script>
        const bdCsrf = '{{ csrf_token() }}';
        const bdSupportIdInitial = {{ $mentorSupport ? $mentorSupport->id : 'null' }};
        const bdMyAvatar = '{{ $authUser->getAvatar(26) }}';
        let bdSupportId = bdSupportIdInitial;
        const bdSupportWebinarId = {{ $supportWebinarId ?: 'null' }};

        document.addEventListener('DOMContentLoaded', function () {
            const body = document.getElementById('bd-chat-body');
            if (body) body.scrollTop = body.scrollHeight;

            const input = document.getElementById('bd-chat-input');
            if (input) {
                /* start closed with zero max-height for smooth transition */
                display:block; max-height:0; overflow:hidden; margin-top:10px; padding:0 12px; background:#fff; border-radius:8px; border:1px solid #eef2f7; transition: max-height .36s ease, padding .28s ease, opacity .28s ease;
                opacity:0;
                    this.style.height = 'auto';
                                        <div class="bd-module-items__list" style="padding:8px 0;">
                                            @php
                                                $items = collect();
                                                if (!empty($webinar->sessions)) { foreach ($webinar->sessions as $s) { $items->push(['model' => $s, 'type' => 'session', 'id' => $s->id, 'title' => $s->title ?? ('Session #' . $s->id), 'passed' => (method_exists($s, 'checkPassedItem') ? (bool)$s->checkPassedItem() : false)]); } }
                                                if (!empty($webinar->files)) { foreach ($webinar->files as $f) { $items->push(['model' => $f, 'type' => 'file', 'id' => $f->id, 'title' => $f->title ?? ($f->file_name ?? 'File #' . $f->id), 'passed' => (method_exists($f, 'checkPassedItem') ? (bool)$f->checkPassedItem() : false)]); } }
                                                if (!empty($webinar->textLessons)) { foreach ($webinar->textLessons as $t) { $items->push(['model' => $t, 'type' => 'text_lesson', 'id' => $t->id, 'title' => $t->title ?? ('Bài viết #' . $t->id), 'passed' => (method_exists($t, 'checkPassedItem') ? (bool)$t->checkPassedItem() : false)]); } }
                                                if (!empty($webinar->quizzes)) { foreach ($webinar->quizzes as $q) { $items->push(['model' => $q, 'type' => 'quiz', 'id' => $q->id, 'title' => $q->title ?? ('Quiz #' . $q->id), 'points' => $q->pass_mark ?? null, 'passed' => (method_exists($q, 'checkPassedItem') ? (bool)$q->checkPassedItem() : false)]); } }
                                                if (!empty($webinar->assignments)) { foreach ($webinar->assignments as $a) { $items->push(['model' => $a, 'type' => 'assignment', 'id' => $a->id, 'title' => $a->title ?? ('Assignment #' . $a->id), 'passed' => (method_exists($a, 'checkPassedItem') ? (bool)$a->checkPassedItem() : false)]); } }
                                            @endphp

                                            @if($items->count())
                                                @foreach($items as $it)
                                                    <div class="bd-item-row" style="display:flex;align-items:center;gap:12px;padding:10px 6px;border-bottom:1px solid #f3f4f6;">
                                                        <div style="width:44px;flex-shrink:0;display:flex;align-items:center;justify-content:center;border-radius:50%;background:#f3f4f6;color:#555;font-size:12px;">
                                                            @if($it['type'] == 'session')
                                                                {{-- play icon --}} 
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7L8 5z" fill="#6b7280"/></svg>
                                                            @elseif($it['type'] == 'file')
                                                                {{-- download/file icon --}}
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3v10" stroke="#6b7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 13l4 4 4-4" stroke="#6b7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 21H4" stroke="#6b7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                            @elseif($it['type'] == 'text_lesson')
                                                                {{-- article icon --}}
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 7h10M7 11h10M7 15h7" stroke="#6b7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                            @elseif($it['type'] == 'quiz')
                                                                {{-- quiz icon --}}
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9" stroke="#6b7280" stroke-width="1.5"/><path d="M9.5 9.5h5v5h-5z" fill="#6b7280" opacity="0.08"/></svg>
                                                            @else
                                                                {{-- generic dot --}}
                                                                <div style="width:8px;height:8px;border-radius:50%;background:#6b7280;"></div>
                                                            @endif
                                                        </div>
                                                        <div style="flex:1;min-width:0;">
                                                            @php $passed = !empty($it['passed']); @endphp
                                                            <a href="/panel/courses/purchases/learning/{{ $webinar->slug }}?item={{ $it['id'] }}&type={{ $it['type'] }}" style="font-weight:600;color:{{ $passed ? 'var(--primary)' : '#111' }};text-decoration:none;">{{ $it['title'] }}</a>
                                                            @if(!empty($it['points']))
                                                                <div style="font-size:12px;color:#888;margin-top:4px;">{{ $it['points'] }} Điểm</div>
                                                            @endif
                                                        </div>
                                                        @if($passed)
                                                            <div style="flex-shrink:0; color:var(--primary); font-weight:700; font-size:12px;">Hoàn thành</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center" style="color:#9ca3af;padding:8px;">{{ trans('update.no_result_bundle_hint') }}</div>
                                            @endif
        }

        function bdFetch(url, body) {
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': bdCsrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(body),
            }).then(function (res) {
                return res.json().then(function (data) {
                    if (!res.ok) {
                        const msg = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Có lỗi xảy ra.');
                        alert(msg);
                        return Promise.reject(msg);
                    }
                    return data;
                });
            });
        }

        function bdAppendBubble(messageText) {
            const body = document.getElementById('bd-chat-body');
            if (!body) return;

            const row = document.createElement('div');
            row.className = 'bd-bubble-row bd-bubble-row--me';
            row.innerHTML = '\n                <img src="' + bdMyAvatar + '" class="bd-bubble-row__avatar" alt="Me">\n                <div class="bd-bubble bd-bubble--me">' +
                (messageText || '') +
                '<span class="bd-bubble__time">' + new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" }) + '</span></div>';
            body.appendChild(row);
            body.scrollTop = body.scrollHeight;
        }

        function bdUpdateStatus(status) {
            const header = document.querySelector('.bd-chat__status');
            if (!header) return;
            header.style.color = status === 'close' ? '#e74c3c' : '#27ae60';
            header.textContent = '● ' + (status === 'close' ? 'Đã đóng' : 'Đang mở');
        }
        
        /* Module accordion toggle with smooth animation and auto-open first */
        function toggleModulePanel(btn) {
            const webinarId = btn.dataset.webinarId;
            const panel = document.getElementById('bd-module-items-' + webinarId);
            if (!panel) return;
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            if (expanded) {
                // collapse
                // ensure current height is set then animate to 0
                panel.style.maxHeight = panel.scrollHeight + 'px';
                requestAnimationFrame(function () {
                    panel.style.maxHeight = '0px';
                    panel.classList.remove('open');
                });

                btn.setAttribute('aria-expanded', 'false');
                btn.classList.remove('is-open');

                panel.addEventListener('transitionend', function handler(e) {
                    if (e.propertyName !== 'max-height') return;
                    panel.style.display = 'none';
                    panel.removeEventListener('transitionend', handler);
                });
            } else {
                // expand
                panel.style.display = 'block';
                // set to current 0 then animate to scrollHeight
                panel.style.maxHeight = '0px';
                requestAnimationFrame(function () {
                    panel.classList.add('open');
                    panel.style.maxHeight = panel.scrollHeight + 'px';
                });

                panel.addEventListener('transitionend', function handler(e) {
                    if (e.propertyName !== 'max-height') return;
                    // clear maxHeight so content can grow naturally
                    panel.style.maxHeight = 'none';
                    panel.removeEventListener('transitionend', handler);
                });

                btn.setAttribute('aria-expanded', 'true');
                btn.classList.add('is-open');
            }
        }

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.bd-module-toggle-btn');
            if (!btn) return;
            toggleModulePanel(btn);
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Auto-open first module
            const firstBtn = document.querySelector('.bd-module-toggle-btn');
            if (firstBtn) {
                // open after slight delay to allow layout
                setTimeout(function () { toggleModulePanel(firstBtn); }, 120);
            }
        });
    </script>
@endpush
