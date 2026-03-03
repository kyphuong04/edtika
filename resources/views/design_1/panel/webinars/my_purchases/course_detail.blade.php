@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <style>
        /* ── Reset panel scroll padding ─────────────────────────── */
        #panelContentScrollable { padding: 0 !important; }

        /* ── Hide default panel title/breadcrumb bar ────────────── */
        .panel-title-and-breadcrumb { display: none !important; }

        /* ── Page wrapper ───────────────────────────────────────── */
        .cd-page { padding: 24px 32px 60px; }
        @media (max-width: 991px) { .cd-page { padding: 16px 16px 60px; } }

        /* ── Shared button / bell styles ─────────────────────────── */
        .cd-btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 20px;
            border: 1.5px solid #bbb;
            font-size: 13px;
            font-weight: 500;
            background: #fff;
            color: #333;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            transition: border-color .2s, background .2s;
        }
        .cd-btn-outline:hover { border-color: var(--primary); color: var(--primary); text-decoration: none; }
        .cd-bell-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1.5px solid #ddd;
            background: #fff;
            cursor: pointer;
            color: #555;
            flex-shrink: 0;
            position: relative;
        }
        .cd-bell-btn:hover { border-color: var(--primary); color: var(--primary); }
        .cd-layout {
            display: flex;
            gap: 28px;
            align-items: flex-start;
        }
        .cd-layout__main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .cd-layout__aside {
            width: 360px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        @media (max-width: 1200px) { .cd-layout__aside { width: 320px; } }
        @media (max-width: 991px) {
            .cd-layout { flex-direction: column; }
            .cd-layout__aside { width: 100%; }
        }

        /* ── Welcome card (inside main column) ──────────────────── */
        .cd-welcome-card {
            background: #fff;
            border-radius: 16px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .cd-welcome-card__title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: .4px;
            flex: 1;
            min-width: 120px;
        }
        .cd-welcome-card__progress {
            width: 100%;
            height: 5px;
            background: #e9ecef;
            border-radius: 4px;
            margin-top: 10px;
            overflow: hidden;
        }
        .cd-welcome-card__progress-bar {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
            transition: width .4s ease;
        }

        /* ── Section cards (main column) ────────────────────────── */
        .cd-section-card {
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid #eee;
            padding: 16px 20px;
            cursor: pointer;
            transition: border-color .2s;
        }
        .cd-section-card.is-open { border-color: var(--primary); }
        .cd-section-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .cd-section-card__left { flex: 1; min-width: 0; }
        .cd-section-card__title { font-size: 14px; font-weight: 700; letter-spacing: .4px; }
        .cd-section-card__meta { font-size: 12px; color: #888; margin-top: 3px; }
        .cd-section-card__bar {
            width: 100%;
            height: 4px;
            background: #e9ecef;
            border-radius: 4px;
            margin-top: 10px;
            overflow: hidden;
        }
        .cd-section-card__bar-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
        }
        .cd-section-card__btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 18px;
            border-radius: 20px;
            border: 1.5px solid #ccc;
            font-size: 13px;
            font-weight: 500;
            background: #fff;
            color: #333;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            transition: border-color .2s, color .2s;
            flex-shrink: 0;
        }
        .cd-section-card__btn:hover { border-color: var(--primary); color: var(--primary); text-decoration: none; }
        .cd-section-card__toggle {
            width: 28px; height: 28px; border-radius: 50%;
            border: 1.5px solid #ddd; background: #f9f9f9;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 10px; color: #888; line-height: 1;
            transition: transform .25s, border-color .2s, color .2s;
        }
        .cd-section-card.is-open .cd-section-card__toggle {
            transform: rotate(180deg);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ── Chapter items accordion panel ──────────────────────── */
        .cd-section-card__items {
            display: none;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid #eee;
        }
        .cd-section-card.is-open .cd-section-card__items { display: block; }

        /* ── Lesson timeline ─────────────────────────────────────── */
        .cd-item-list { position: relative; }
        .cd-item-row {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 10px 0; position: relative;
        }
        .cd-item-row:not(:last-child)::after {
            content: ''; position: absolute;
            left: 18px; top: 46px;
            width: 1px; height: calc(100% - 16px);
            background: #ddd; z-index: 0;
        }
        .cd-item-row__circle {
            width: 38px; height: 38px; border-radius: 50%;
            border: 1.5px solid #ccc; background: #f0f0f0;
            flex-shrink: 0; display: flex; align-items: center;
            justify-content: center; position: relative; z-index: 1;
            color: #bbb;
        }
        .cd-item-row__circle.is-passed {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .cd-item-row__info { flex: 1; min-width: 0; padding-top: 3px; }
        .cd-item-row__title { font-size: 13px; font-weight: 600; line-height: 1.45; color: #222; }
        .cd-item-row__type {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px; color: #555; margin-top: 4px;
        }
        .cd-item-row__type-dot {
            width: 16px; height: 16px; border-radius: 50%;
            background: #333; display: inline-flex;
            align-items: center; justify-content: center;
            color: #fff; font-size: 7px; flex-shrink: 0;
        }
        .cd-item-row__type-dot.is-passed { background: var(--primary); }
        .cd-item-row__points { font-size: 11px; color: #888; margin-top: 2px; }

        /* ── Right aside cards ───────────────────────────────────── */
        .cd-aside-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #eee;
            padding: 24px 20px;
        }

        /* User card */
        .cd-user-card { display: flex; align-items: center; gap: 14px; }
        .cd-user-card__avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eee;
            flex-shrink: 0;
        }
        .cd-user-card__name { font-size: 15px; font-weight: 700; }
        .cd-user-card__band { font-size: 12px; color: #666; margin-top: 3px; }

        /* Word of day card */
        .cd-word-card { text-align: center; padding: 8px 4px; }
        .cd-word-card__word { font-size: 16px; font-weight: 700; color: #1a1a1a; }
        .cd-word-card__pronunciation { font-size: 13px; color: #888; margin-top: 6px; }
        .cd-word-card__translation { font-size: 13px; color: #555; margin-top: 10px; }
        .cd-word-card__example { font-size: 13px; color: #333; margin-top: 10px; line-height: 1.6; }

        /* ── Inline chat widget ───────────────────────────────── */
        .cd-chat-card { padding: 0 !important; overflow: hidden; }
        .cd-chat__header {
            padding: 12px 16px; border-bottom: 1px solid #f0f0f0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .cd-chat__header-title { font-size: 13px; font-weight: 700; color: #333; display:flex; align-items:center; gap:5px; }
        .cd-chat__status { font-size: 11px; }
        .cd-chat__body {
            padding: 12px 14px; max-height: 280px; min-height: 80px;
            overflow-y: auto; display: flex; flex-direction: column; gap: 10px;
        }
        .cd-chat__empty-state { text-align: center; color: #aaa; padding: 24px 10px; font-size: 12px; line-height: 1.6; }
        .cd-bubble-row { display: flex; align-items: flex-end; gap: 7px; }
        .cd-bubble-row--me { flex-direction: row-reverse; }
        .cd-bubble-row__avatar { width: 26px; height: 26px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .cd-bubble {
            max-width: 78%; padding: 7px 11px; border-radius: 14px;
            font-size: 12px; line-height: 1.5; word-break: break-word;
        }
        .cd-bubble--other { background: #f0f0f0; color: #333; border-bottom-left-radius: 3px; }
        .cd-bubble--me { background: var(--primary); color: #fff; border-bottom-right-radius: 3px; }
        .cd-bubble__time { font-size: 10px; opacity: .55; display: block; margin-top: 3px; }
        .cd-chat__new-subject { padding: 8px 14px 0; }
        .cd-chat__new-subject label { font-size: 11px; color: #999; display: block; margin-bottom: 3px; }
        .cd-chat__new-subject input {
            width: 100%; border: 1px solid #ddd; border-radius: 8px;
            padding: 6px 10px; font-size: 12px; outline: none;
            transition: border-color .2s;
        }
        .cd-chat__new-subject input:focus { border-color: var(--primary); }
        .cd-chat__footer {
            padding: 8px 12px 10px; border-top: 1px solid #f0f0f0;
            display: flex; align-items: flex-end; gap: 8px;
        }
        .cd-chat__input {
            flex: 1; border: 1px solid #ddd; border-radius: 18px;
            padding: 7px 13px; font-size: 12px; resize: none; outline: none;
            max-height: 78px; overflow-y: auto; line-height: 1.45;
            transition: border-color .2s;
        }
        .cd-chat__input:focus { border-color: var(--primary); }
        .cd-chat__send-btn {
            width: 32px; height: 32px; border-radius: 50%; border: none;
            background: var(--primary); display: flex; align-items: center;
            justify-content: center; cursor: pointer; flex-shrink: 0;
            transition: opacity .2s;
        }
        .cd-chat__send-btn:hover { opacity: .8; }
        .cd-chat__send-btn svg { fill: #fff; }

        /* ── User-card nav dropdown ──────────────────────────────── */
        .cd-user-nav { overflow: visible !important; cursor: pointer; }
        .cd-user-nav .cd-user-nav__dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            min-width: 240px;
            z-index: 9999;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #eee;
            box-shadow: 0 8px 28px rgba(0,0,0,.13);
            padding: 0;
            max-height: 80vh;
            overflow-y: auto;
        }
        .cd-user-nav:hover .cd-user-nav__dropdown { display: block; }
    </style>
@endpush

@section('content')
@php
    $authUser  = auth()->user();
    $panelLearnUrl = url('/panel/courses/purchases/learning/' . $course->slug);

    /* ── Overall course progress ──────────────────────────────── */
    $totalItems    = 0;
    $passedItems   = 0;
    foreach ($course->chapters as $chap) {
        foreach ($chap->chapterItems as $ci) {
            $totalItems++;
            $item = $ci->session ?? $ci->file ?? $ci->textLesson ?? $ci->quiz ?? $ci->assignment ?? null;
            if ($item && method_exists($item, 'checkPassedItem') && $item->checkPassedItem()) {
                $passedItems++;
            }
        }
    }
    $overallProgress = $totalItems > 0 ? round(($passedItems / $totalItems) * 100) : 0;

    /* ── Word of the day ──────────────────────────────────────── */
    $wordOfDay = null;
    try {
        $wordOfDay = \App\Models\AcademicWordListWord::whereHas('academicWordList', function($q){ $q->where('is_active', true); })
            ->inRandomOrder()
            ->first();
    } catch (\Exception $e) {}

    /* ── Latest mentor support conversation ───────────────────── */
    $mentorSupport = null;
    $mentorConversations = collect();
    try {
        $mentorSupport = \App\Models\Support::where('user_id', $authUser->id)
            ->whereNotNull('webinar_id')
            ->where('webinar_id', $course->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($mentorSupport) {
            $mentorConversations = $mentorSupport->conversations()
                ->with(['sender', 'supporter'])
                ->orderBy('id', 'asc')
                ->get();
        }
    } catch (\Exception $e) {}
@endphp

<div class="cd-page">

    {{-- ══ TWO-COLUMN OUTER LAYOUT ████████████████████████████ --}}
    <div class="cd-layout">

        {{-- ── LEFT / MAIN COLUMN ─────────────────────────────── --}}
        <div class="cd-layout__main">

            {{-- Welcome card --}}
            <div class="cd-welcome-card">
                {{-- avatar circle --}}
                <div class="size-40 rounded-circle overflow-hidden flex-shrink-0" style="border:2px solid #eee;">
                    <img src="{{ $authUser->getAvatar(40) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">
                </div>

                {{-- greeting --}}
                <div class="cd-welcome-card__title">
                    WELCOME, {{ mb_strtoupper($authUser->name ?? $authUser->full_name) }}!
                </div>

                {{-- actions --}}
                <div class="d-flex align-items-center flex-wrap gap-8">
                    <a href="/panel/courses/purchases" class="cd-btn-outline">
                        {{ trans('panel.my_courses') }}
                    </a>

                    <a href="{{ $panelLearnUrl }}" class="cd-btn-outline">
                        {{ trans('update.continue_learning') }} &rarr;
                    </a>

                    {{-- notification bell dropdown --}}
                    <div class="language-select position-relative">
                        <div class="cd-bell-btn">
                            <x-iconsax-lin-notification class="icons" width="18px" height="18px"/>
                            @if(!empty($unReadNotifications) && count($unReadNotifications))
                                <span class="panel-header__badge-counter badge-counter">{{ count($unReadNotifications) }}</span>
                            @endif
                        </div>

                        <div class="language-dropdown language-dropdown__notifications py-12" style="right:0;left:auto;">
                            @if(!empty($unReadNotifications) && count($unReadNotifications))
                                <div class="px-12">
                                    <div class="d-flex align-items-center p-12 rounded-12 bg-gray-100">
                                        <div class="d-flex-center size-48 bg-white rounded-circle">
                                            <div class="d-flex-center size-40 bg-primary rounded-circle">
                                                <x-iconsax-bul-notification-bing class="icons text-white" width="24px" height="24px"/>
                                            </div>
                                        </div>
                                        <div class="ml-8">
                                            <h5 class="font-14">{{ count($unReadNotifications) }} {{ trans('panel.notifications') }}</h5>
                                            <a href="/panel/notifications/mark-all-as-read"
                                               class="delete-action d-block mt-4 font-12 cursor-pointer text-gray-500"
                                               data-msg="{{ trans('update.convert_unread_messages_to_read') }}"
                                               data-confirm="{{ trans('update.yes_convert') }}">
                                                {{ trans('update.mark_as_read') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                @foreach($unReadNotifications->take(3) as $unReadNotification)
                                    <a href="/panel/notifications?notification={{ $unReadNotification->id }}"
                                       class="language-dropdown__item d-flex align-items-center w-100 px-16 py-8 text-dark bg-transparent">
                                        <x-iconsax-bul-notification class="icons text-gray-500" width="24px" height="24px"/>
                                        <div class="ml-8">
                                            <h4 class="font-12">{{ $unReadNotification->title }}</h4>
                                            <span class="d-block text-gray-500 font-12 mt-8">{{ dateTimeFormat($unReadNotification->created_at, 'j M Y | H:i') }}</span>
                                        </div>
                                    </a>
                                @endforeach

                                <div class="px-12">
                                    <a href="/panel/notifications" class="btn btn-lg btn-primary btn-block mt-12">
                                        {{ trans('notification.all_notifications') }}
                                    </a>
                                </div>
                            @else
                                <div class="d-flex-center flex-column text-center px-16 py-54">
                                    <div class="d-flex-center size-40 bg-primary rounded-circle">
                                        <x-iconsax-bul-notification-bing class="icons text-white" width="24px" height="24px"/>
                                    </div>
                                    <span class="mt-12 text-gray-500">{{ trans('notification.empty_notifications') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- full-width progress bar --}}
                <div class="cd-welcome-card__progress">
                    <div class="cd-welcome-card__progress-bar" style="width: {{ $overallProgress }}%;"></div>
                </div>
            </div>

            {{-- Chapter / skill section cards --}}
            @if(!empty($course->chapters) && $course->chapters->count())
                @foreach($course->chapters as $chapter)
                    @php
                        $chTotal  = $chapter->chapterItems->count();
                        $chPassed = 0;
                        foreach ($chapter->chapterItems as $ci) {
                            $item = $ci->session ?? $ci->file ?? $ci->textLesson ?? $ci->quiz ?? $ci->assignment ?? null;
                            if ($item && method_exists($item, 'checkPassedItem') && $item->checkPassedItem()) {
                                $chPassed++;
                            }
                        }
                        $chProgress = $chTotal > 0 ? round(($chPassed / $chTotal) * 100) : 0;
                    @endphp

                    <div class="cd-section-card" onclick="cdToggleChapter(this)">

                        {{-- Section header row --}}
                        <div class="cd-section-card__header">
                            <div class="cd-section-card__left">
                                <div class="cd-section-card__title">{{ mb_strtoupper($chapter->title) }}</div>
                                <div class="cd-section-card__meta">
                                    {{ $chapter->getTopicsCount(true) }} {{ trans('public.parts') }}
                                </div>
                                <div class="cd-section-card__bar">
                                    <div class="cd-section-card__bar-fill" style="width: {{ $chProgress }}%;"></div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-8 flex-shrink-0">
                                <a href="{{ $panelLearnUrl }}?chapter={{ $chapter->id }}" class="cd-section-card__btn"
                                   onclick="event.stopPropagation()">
                                    Learn &rarr;
                                </a>
                            </div>
                        </div>

                        {{-- Expandable lesson items --}}
                        <div class="cd-section-card__items">
                            <div class="cd-item-list">
                                @foreach($chapter->chapterItems as $ci)
                                    @php
                                        $ciItem   = $ci->session ?? $ci->file ?? $ci->textLesson ?? $ci->quiz ?? $ci->assignment ?? null;
                                        $ciTitle  = $ciItem ? ($ciItem->title ?? '—') : '—';
                                        $ciType   = $ci->type ?? '';
                                        $ciPassed = $ciItem && method_exists($ciItem, 'checkPassedItem') && $ciItem->checkPassedItem();
                                        $ciLabel  = match($ciType) {
                                            'session'     => 'Video',
                                            'file'        => 'File',
                                            'text_lesson' => 'Text Lesson',
                                            'quiz'        => 'Quizz',
                                            'assignment'  => 'Exercise',
                                            default       => ucfirst(str_replace('_', ' ', $ciType)),
                                        };
                                        $ciPoints = null;
                                        if ($ciType === 'quiz' && !empty($ciItem->total_mark)) {
                                            $ciPoints = $ciItem->total_mark . ' Điểm';
                                        } elseif ($ciType === 'assignment' && !empty($ciItem->score)) {
                                            $ciPoints = $ciItem->score . ' Điểm';
                                        }
                                    @endphp
                                    <div class="cd-item-row">
                                        <div class="cd-item-row__circle {{ $ciPassed ? 'is-passed' : '' }}">
                                            @if($ciPassed)
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="3">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            @else
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                                    <circle cx="12" cy="12" r="8"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="cd-item-row__info">
                                            <div class="cd-item-row__title">{{ $ciTitle }}</div>
                                            <div class="cd-item-row__type">
                                                <span class="cd-item-row__type-dot {{ $ciPassed ? 'is-passed' : '' }}">&#9654;</span>
                                                {{ $ciLabel }}
                                            </div>
                                            @if($ciPoints)
                                                <div class="cd-item-row__points">{{ $ciPoints }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            @else
                @if(!empty($sessionsWithoutChapter) && count($sessionsWithoutChapter))
                    @foreach($sessionsWithoutChapter as $session)
                        <div class="cd-section-card">
                            <div class="cd-section-card__left">
                                <div class="cd-section-card__title">{{ mb_strtoupper($session->title) }}</div>
                                <div class="cd-section-card__bar">
                                    <div class="cd-section-card__bar-fill" style="width:0%;"></div>
                                </div>
                            </div>
                            <a href="{{ $panelLearnUrl }}" class="cd-section-card__btn">
                                Learn &rarr;
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="cd-aside-card text-center text-gray-500" style="padding:40px 20px;">
                        {{ trans('update.learning_page_empty_content_title') }}
                    </div>
                @endif
            @endif

        </div>{{-- /cd-layout__main --}}

        {{-- ── RIGHT ASIDE COLUMN ──────────────────────────────── --}}
        <div class="cd-layout__aside">

            {{-- 1. User card — hover dropdown nav --}}
            <div class="cd-aside-card cd-user-card cd-user-nav position-relative" style="flex-wrap:nowrap; padding:16px 20px;">
                {{-- Trigger row --}}
                <img src="{{ $authUser->getAvatar(52) }}" class="cd-user-card__avatar" alt="{{ $authUser->full_name }}">
                <div style="flex:1; min-width:0;">
                    <div class="cd-user-card__name">{{ mb_strtoupper($authUser->full_name) }}</div>
                    <div class="cd-user-card__band">BAND ESTIMATE: {{ number_format($authUser->band_estimate ?? 5.0, 1) }}</div>
                </div>
                <x-iconsax-lin-arrow-down class="icons text-gray-400" width="16px" height="16px" style="flex-shrink:0; margin-left:auto;"/>

                {{-- Dropdown panel --}}
                <div class="cd-user-nav__dropdown">

                    {{-- User info header --}}
                    <div class="d-flex align-items-center rounded-10 bg-gray p-12" style="margin:8px;">
                        <div class="position-relative" style="flex-shrink:0;">
                            <img src="{{ $authUser->getAvatar(38) }}" class="img-cover rounded-circle" style="width:38px;height:38px;" alt="{{ $authUser->full_name }}">
                            @if($authUser->verified)
                                <div class="dropdown__user-avatar__badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="{{ trans('public.verified') }}">
                                    <x-tick-icon class="icons text-white"/>
                                </div>
                            @endif
                        </div>
                        <div class="ml-8">
                            <div class="font-14 font-weight-bold text-dark">{{ $authUser->full_name }}</div>
                            <span class="text-gray-500 font-12">{{ $authUser->role->caption }}</span>
                        </div>
                    </div>

                    {{-- Nav items --}}
                    <ul style="list-style:none;padding:0;margin:8px 0;">
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl('/') : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                                <x-iconsax-lin-chart-2 class="icons" width="24px" height="24px"/>
                                <span class="ml-8">{{ trans('panel.dashboard') }}</span>
                            </a>
                        </li>
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="/panel/notifications" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                                <x-iconsax-lin-notification class="icons" width="24px" height="24px"/>
                                <span class="ml-8">{{ trans('panel.notifications') }}</span>
                                @if(!empty($unReadNotifications) and count($unReadNotifications))
                                    <span class="count-badge d-inline-flex align-items-center justify-content-center text-white rounded-circle ml-auto font-12 bg-danger">{{ count($unReadNotifications) }}</span>
                                @endif
                            </a>
                        </li>
                        @if(!$authUser->isUser())
                            <li class="navbar-auth-user__dropdown-item">
                                <a href="/panel/courses" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                                    <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                                    <span class="ml-8">{{ trans('update.my_courses') }}</span>
                                </a>
                            </li>
                            <li class="navbar-auth-user__dropdown-item">
                                <a href="/panel/financial/sales" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                                    <x-iconsax-lin-moneys class="icons" width="24px" height="24px"/>
                                    <span class="ml-8">{{ trans('panel.sales') }}</span>
                                </a>
                            </li>
                        @else
                            <li class="navbar-auth-user__dropdown-item">
                                <a href="/panel/courses/purchases" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                                    <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                                    <span class="ml-8">{{ trans('panel.my_classes') }}</span>
                                </a>
                            </li>
                        @endif
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="/panel/support/new" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                                <x-iconsax-lin-message-question class="icons" width="24px" height="24px"/>
                                <span class="ml-8">{{ trans('panel.support') }}</span>
                            </a>
                        </li>
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="{{ $authUser->getProfileUrl() }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                                <x-iconsax-lin-profile class="icons" width="24px" height="24px"/>
                                <span class="ml-8">{{ trans('public.profile') }}</span>
                            </a>
                        </li>
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="/panel/setting" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-dark text-decoration-none">
                                <x-iconsax-lin-setting-2 class="icons" width="24px" height="24px"/>
                                <span class="ml-8">{{ trans('panel.settings') }}</span>
                            </a>
                        </li>
                    </ul>

                    <div style="border-top:1px solid #f0f0f0;margin:4px 0;"></div>

                    {{-- Dark/Light + Language --}}
                    <ul style="list-style:none;padding:0;margin:4px 0;">
                        <li class="navbar-auth-user__dropdown-item">
                            <div class="js-theme-color-toggle theme-color-toggle theme-color-toggle__panel {{ "{$userThemeColorMode}-mode" }} d-flex align-items-center w-100 px-16 py-8 cursor-pointer bg-transparent">
                                <x-iconsax-lin-moon class="dark-icon icons" width="24px" height="24px"/>
                                <x-iconsax-lin-sun-1 class="light-icon icons" width="24px" height="24px"/>
                                <span class="ml-8 dark-icon">Light Mode</span>
                                <span class="ml-8 light-icon">Dark Mode</span>
                            </div>
                        </li>
                        <li class="navbar-auth-user__dropdown-item">
                            <div style="padding:0 8px;">
                                @php $langClassName = 'w-100'; @endphp
                                @include('design_1.panel.includes.header.language')
                            </div>
                        </li>
                    </ul>

                    <div style="border-top:1px solid #f0f0f0;margin:4px 0;"></div>

                    {{-- Logout --}}
                    <ul style="list-style:none;padding:0;margin:4px 0 8px;">
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="/logout" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent text-danger text-decoration-none">
                                <x-iconsax-lin-logout class="icons text-danger" width="24px" height="24px"/>
                                <span class="ml-8 text-danger">{{ trans('panel.log_out') }}</span>
                            </a>
                        </li>
                    </ul>

                </div>{{-- /.cd-user-nav__dropdown --}}
            </div>

            {{-- 2. Word of the Day --}}
            <div class="cd-aside-card">
                <div class="cd-word-card">
                    <div class="cd-word-card__word">food additives</div>
                    <div class="cd-word-card__pronunciation">(n) /fuːd əˈdɪktɪv/</div>
                    <div class="cd-word-card__translation">chất phụ gia thực phẩm</div>
                    <div class="cd-word-card__example">Food additives improve the taste of food.</div>
                </div>
            </div>

            {{-- 3. Mentor / Teacher inline chat --}}
            <div class="cd-aside-card cd-chat-card" id="cd-chat-wrap">

                {{-- Header --}}
                <div class="cd-chat__header">
                    <span class="cd-chat__header-title">
                        <x-iconsax-lin-message-question class="icons" width="14px" height="14px"/>
                        Tin nhắn với Mentor
                    </span>
                    @if($mentorSupport)
                        <span class="cd-chat__status"
                              style="color: {{ $mentorSupport->status == 'close' ? '#e74c3c' : '#27ae60' }}">
                            ● {{ $mentorSupport->status == 'close' ? 'Đã đóng' : 'Đang mở' }}
                        </span>
                    @endif
                </div>

                {{-- Message list --}}
                <div class="cd-chat__body" id="cd-chat-body">
                    @if($mentorSupport && $mentorConversations->count())
                        @foreach($mentorConversations as $conv)
                            @php
                                $isMe      = $conv->sender_id == $authUser->id;
                                $convUser  = $conv->sender ?? null;
                                $convTime  = $conv->created_at ? date('H:i', $conv->created_at) : '';
                            @endphp
                            <div class="cd-bubble-row {{ $isMe ? 'cd-bubble-row--me' : '' }}">
                                @if($convUser)
                                    <img src="{{ $convUser->getAvatar(26) }}"
                                         class="cd-bubble-row__avatar"
                                         alt="{{ $convUser->full_name }}">
                                @endif
                                <div class="cd-bubble {{ $isMe ? 'cd-bubble--me' : 'cd-bubble--other' }}">
                                    {{ $conv->message }}
                                    <span class="cd-bubble__time">{{ $convTime }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="cd-chat__empty-state">
                            <x-iconsax-lin-message-question class="icons" width="28px" height="28px" style="color:#ccc;"/>
                            <div style="margin-top:8px;">Chưa có tin nhắn.<br>Hãy đặt câu hỏi cho giáo viên!</div>
                        </div>
                    @endif
                </div>

                {{-- Subject input (only when no thread yet) --}}
                @if(!$mentorSupport)
                    <div class="cd-chat__new-subject">
                        <label>Tiêu đề</label>
                        <input type="text" id="cd-chat-title" placeholder="Ví dụ: Câu hỏi về bài nghe..." value="Câu hỏi về khóa học">
                    </div>
                @endif

                {{-- Input row --}}
                <div class="cd-chat__footer">
                    <textarea class="cd-chat__input" id="cd-chat-input" rows="1"
                              placeholder="Nhập tin nhắn..."></textarea>
                    <button class="cd-chat__send-btn" id="cd-chat-send" title="Gửi">
                        <svg width="14" height="14" viewBox="0 0 24 24">
                            <path d="M2 21l21-9L2 3v7l15 2-15 2z"/>
                        </svg>
                    </button>
                </div>

            </div>

        </div>{{-- /cd-layout__aside --}}
    </div>{{-- /cd-layout --}}

</div>{{-- /cd-page --}}
@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("show_course") }}"></script>
    <script>
        /* ── Chapter accordion ───────────────────────────────── */
        function cdToggleChapter(card) {
            card.classList.toggle('is-open');
        }

        /* ── Inline mentor chat ──────────────────────────────── */
        const cdCsrf     = '{{ csrf_token() }}';
        const cdCourseId = {{ $course->id }};
        let   cdSupportId = {{ $mentorSupport ? $mentorSupport->id : 'null' }};
        const cdMyAvatar = '{{ $authUser->getAvatar(26) }}';
        const cdMyName   = '{{ addslashes(e($authUser->full_name)) }}';

        document.addEventListener('DOMContentLoaded', function () {
            /* Auto-scroll chat to bottom */
            const body = document.getElementById('cd-chat-body');
            if (body) body.scrollTop = body.scrollHeight;

            /* Auto-resize textarea */
            const inp = document.getElementById('cd-chat-input');
            if (inp) {
                inp.addEventListener('input', function () {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 78) + 'px';
                });
                inp.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        cdChatSend();
                    }
                });
            }

            const btn = document.getElementById('cd-chat-send');
            if (btn) btn.addEventListener('click', cdChatSend);
        });

        function cdChatSend() {
            const inp = document.getElementById('cd-chat-input');
            const msg = inp ? inp.value.trim() : '';
            if (!msg) return;

            if (!cdSupportId) {
                const titleEl = document.getElementById('cd-chat-title');
                const title   = titleEl ? (titleEl.value.trim() || 'Câu hỏi về khóa học') : 'Câu hỏi về khóa học';
                cdFetch('/panel/support/ajax-create', { webinar_id: cdCourseId, title: title, message: msg })
                    .then(function (data) {
                        cdSupportId = data.support_id;
                        var subj = document.querySelector('.cd-chat__new-subject');
                        if (subj) subj.remove();
                        var empty = document.querySelector('.cd-chat__empty-state');
                        if (empty) empty.remove();
                        cdAppendBubble(data.message);
                        inp.value = ''; inp.style.height = 'auto';
                        cdUpdateStatus('open');
                    }).catch(function () {});
            } else {
                cdFetch('/panel/support/' + cdSupportId + '/ajax-reply', { message: msg })
                    .then(function (data) {
                        cdAppendBubble(data.message);
                        inp.value = ''; inp.style.height = 'auto';
                    }).catch(function () {});
            }
        }

        function cdFetch(url, body) {
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': cdCsrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(body),
            }).then(function (res) {
                return res.json().then(function (data) {
                    if (!res.ok) {
                        var msg = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Có lỗi xảy ra.');
                        alert(msg);
                        return Promise.reject(msg);
                    }
                    return data;
                });
            });
        }

        function cdAppendBubble(msg) {
            var body = document.getElementById('cd-chat-body');
            if (!body) return;
            var row = document.createElement('div');
            row.className = 'cd-bubble-row cd-bubble-row--me';
            row.innerHTML = '<img src="' + cdMyAvatar + '" class="cd-bubble-row__avatar" alt="' + cdMyName + '">'
                + '<div class="cd-bubble cd-bubble--me">' + cdEscape(msg.message)
                + '<span class="cd-bubble__time">' + msg.created_at + '</span></div>';
            body.appendChild(row);
            body.scrollTop = body.scrollHeight;
        }

        function cdUpdateStatus(status) {
            var el = document.querySelector('.cd-chat__status');
            if (!el) {
                var hdr = document.querySelector('.cd-chat__header');
                if (hdr) {
                    el = document.createElement('span');
                    el.className = 'cd-chat__status';
                    hdr.appendChild(el);
                }
            }
            if (el) { el.style.color = '#27ae60'; el.textContent = '● Đang mở'; }
        }

        function cdEscape(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }
    </script>
@endpush
