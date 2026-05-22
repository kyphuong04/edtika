@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* ============================================================
   MOCK TEST PAGE  –  Wireframe-style 2-column layout
   ============================================================ */

.wf-mock-page {
    --primary: #511D99;
    --primary-hover: #451884;
    --wf-accent: #511D99;
    --wf-accent-hover: #451884;
}
.wf-mock-page .text-primary,
.wf-mock-page .text-primary:hover,
.wf-mock-page .text-primary:focus {
    color: var(--wf-accent) !important;
}
.wf-mock-page .bg-primary,
.wf-mock-page .btn-primary,
.wf-mock-page .btn-primary:hover,
.wf-mock-page .btn-primary:focus,
.wf-mock-page .btn-primary:active,
.wf-mock-page .btn-primary:not(:disabled):not(.disabled):active {
    background-color: var(--wf-accent) !important;
    border-color: var(--wf-accent) !important;
    color: #fff !important;
}
.wf-mock-page .border-primary {
    border-color: var(--wf-accent) !important;
}

/* ── Welcome Bar (Dashboard-style Bootstrap) ────────────────────────────────────────── */
.ielts-welcome-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
}

.ielts-welcome-bar h1 {
    flex-grow: 1;
    min-width: 0;
    margin: 0;
}

.ielts-welcome-bar__progress {
    margin-top: 8px;
}

.ielts-welcome-bar__track {
    height: 6px;
    background: #f1f5f9;
}

.dark-mode .ielts-welcome-bar__track {
    background: #334155;
}

.ielts-welcome-bar__fill {
    height: 100%;
    background: var(--wf-accent);
    transition: width 0.6s ease;
}

.ielts-welcome-bar__bell {
    flex-shrink: 0;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}

.ielts-welcome-bar__bell:hover {
    background-color: #f3f4f6 !important;
    text-decoration: none;
}

.dark-mode .ielts-welcome-bar__bell:hover {
    background-color: #334155 !important;
}

@media (max-width: 767px) {
    .ielts-welcome-bar {
        gap: 8px;
    }
    .ielts-welcome-bar h1 {
        font-size: 16px;
    }
}

/* Overall progress bar (legacy) */
.wf-overall-progress { margin-top: 14px; }
.wf-progress-label {
    display: flex; justify-content: space-between;
    font-size: 11px; color: #94a3b8; margin-bottom: 6px;
}
.wf-progress-track {
    height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;
}
.dark-mode .wf-progress-track { background: #334155; }
.wf-progress-fill {
    height: 100%; border-radius: 3px;
    background: linear-gradient(90deg, var(--wf-accent), var(--wf-accent-hover));
    transition: width 0.4s ease;
}

/* Daily limit badge */
.wf-limit-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 16px; border-radius: 12px; font-size: 13px;
    font-weight: 600; margin-bottom: 18px;
}
.wf-limit-badge.success { background: #d1fae5; color: #059669; }
.wf-limit-badge.warning { background: #fee2e2; color: #dc2626; }

/* ── Mock Test Cards ─────────────────────────────────── */
.wf-test-list { display: flex; flex-direction: column; gap: 22px; }

.wf-test-card {
    background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 28px;
    padding: 24px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
}

.dark-mode .wf-test-card {
    background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
    border-color: rgba(148, 163, 184, 0.14);
}

.wf-test-card__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 18px;
}

.wf-test-card__title {
    font-size: 30px;
    font-weight: 800;
    color: #294e78;
    line-height: 1.15;
    margin-bottom: 6px;
}

.dark-mode .wf-test-card__title { color: #dbeafe; }

.wf-test-card__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px 14px;
    align-items: center;
    color: #94a3b8;
    font-size: 13px;
}

.wf-test-card__pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    background: rgba(37, 99, 235, 0.08);
    color: #294e78;
    font-weight: 600;
}

.dark-mode .wf-test-card__pill {
    background: rgba(96, 165, 250, 0.12);
    color: #dbeafe;
}

.wf-test-card__progress {
    min-width: 120px;
    text-align: right;
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
}

.wf-skill-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

.wf-skill-card {
    min-height: 278px;
    border-radius: 28px;
    border: 1.5px solid rgba(148, 163, 184, 0.28);
    background: #fff;
    padding: 22px 18px 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    text-align: center;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
}

.dark-mode .wf-skill-card {
    background: #0b1220;
    border-color: rgba(148, 163, 184, 0.18);
}

.wf-skill-card.is-disabled { opacity: 0.6; }

.wf-skill-card__top {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
}

.wf-skill-card__icon {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    border: 2px solid var(--skill-color);
    color: var(--skill-color);
    background: var(--skill-soft);
}

.wf-skill-card__title {
    font-size: 23px;
    font-weight: 800;
    color: #294e78;
    line-height: 1.15;
}

.dark-mode .wf-skill-card__title { color: #dbeafe; }

.wf-skill-card__button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    max-width: 220px;
    padding: 14px 18px;
    border-radius: 999px;
    border: none;
    color: #fff;
    font-size: 18px;
    font-weight: 800;
    background: linear-gradient(180deg, var(--skill-color) 0%, rgba(0,0,0,0.08) 100%);
    box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
}

.wf-skill-card__button:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(0,0,0,0.16);
}

.wf-skill-card__button:disabled,
.wf-full-test__button:disabled {
    opacity: 0.45;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}

.wf-skill-card__hint {
    margin-top: 10px;
    font-size: 12px;
    color: #94a3b8;
}

.wf-skill-card__footer {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.wf-skill-card__key {
    width: 68px;
    height: 68px;
    margin-top: 12px;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--skill-color);
    font-size: 24px;
    background: #fff;
}

.dark-mode .wf-skill-card__key {
    background: #0b1220;
    border-color: #475569;
}

.wf-full-test {
    margin-top: 22px;
    background: linear-gradient(90deg, #edf2f7 0%, #e5ebf2 100%);
    border-radius: 28px;
    padding: 18px 22px 18px 24px;
    display: flex;
    align-items: center;
    gap: 18px;
    position: relative;
    overflow: hidden;
}

.wf-full-test__ribbon {
    position: absolute;
    left: -2px;
    top: 10px;
    background: #e11d48;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.3px;
    padding: 6px 16px;
    transform: rotate(-45deg) translate(-18px, -12px);
    transform-origin: left top;
}

.wf-full-test__info {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
    flex: 1;
    padding-left: 26px;
}

.wf-full-test__icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #294e78;
    background: rgba(255, 255, 255, 0.7);
}

.wf-full-test__title {
    font-size: 27px;
    font-weight: 800;
    color: #294e78;
    line-height: 1.1;
}

.wf-full-test__subtitle {
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    margin-top: 4px;
}

.wf-full-test__progress {
    width: 100%;
    max-width: 360px;
    flex: 0 0 360px;
}

.wf-full-test__progress-track {
    height: 44px;
    border-radius: 999px;
    border: 2px solid #2f4f75;
    background: rgba(255, 255, 255, 0.32);
    overflow: hidden;
    position: relative;
}

.wf-full-test__progress-fill {
    position: absolute;
    inset: 0 auto 0 0;
    width: 0;
    background: linear-gradient(90deg, rgba(81, 29, 153, 0.15), rgba(81, 29, 153, 0.24));
}

.wf-full-test__progress-label {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 400;
    color: #294e78;
}

.wf-full-test__start {
    margin-left: auto;
}

.wf-full-test__button {
    min-width: 300px;
    height: 78px;
    padding: 0 28px;
    border: none;
    border-radius: 999px;
    background: #2f4f75;
    color: #fff;
    font-size: 22px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 14px 24px rgba(47, 79, 117, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.wf-full-test__button:hover {
    transform: translateY(-1px);
    box-shadow: 0 18px 28px rgba(47, 79, 117, 0.28);
}

.wf-full-test__button i,
.wf-skill-card__button i {
    font-size: 19px;
}

.wf-full-test__button.disabled {
    background: #cbd5e1;
    color: #64748b;
    box-shadow: none;
}

@media (max-width: 1199px) {
    .wf-skill-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .wf-full-test { flex-wrap: wrap; }
    .wf-full-test__progress { flex: 1 1 280px; max-width: none; }
    .wf-full-test__start { margin-left: 0; width: 100%; }
    .wf-full-test__button { width: 100%; min-width: 0; }
}

@media (max-width: 767px) {
    .wf-test-card { padding: 18px; border-radius: 22px; }
    .wf-test-card__header { flex-direction: column; }
    .wf-test-card__title { font-size: 22px; }
    .wf-skill-grid { grid-template-columns: 1fr; }
    .wf-skill-card { min-height: 240px; }
    .wf-full-test {
        padding: 18px;
        gap: 14px;
        border-radius: 22px;
    }
    .wf-full-test__ribbon { top: 6px; }
    .wf-full-test__info { padding-left: 18px; }
    .wf-full-test__title { font-size: 20px; }
    .wf-full-test__progress-track { height: 38px; }
    .wf-full-test__progress-label { font-size: 18px; }
    .wf-full-test__button { height: 62px; font-size: 18px; }
}

/* Empty state */
.wf-empty {
    text-align: center; padding: 60px 20px;
    background: #f9fafb; border-radius: 20px; border: 2px dashed #e5e7eb;
}
.dark-mode .wf-empty { background: #1e293b; border-color: #334155; }
.wf-empty img { max-width: 140px; opacity: 0.7; margin-bottom: 16px; }
.wf-empty h3 { font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px; }
.dark-mode .wf-empty h3 { color: #f1f5f9; }
.wf-empty p { color: #6b7280; margin: 0; font-size: 14px; }

@media (max-width: 767px) {
    .wf-section-row { flex-wrap: wrap; }
    .wf-section-meta { min-width: 0; }
    .wf-row-progress { width: 100%; }
    .wf-welcome-actions { gap: 8px; }
    .wf-btn-switch, .wf-btn-continue { font-size: 12px; padding: 7px 12px; }
    .wf-row-actions { flex-wrap: wrap; gap: 6px; }
    .wf-btn-view-result { font-size: 12px; padding: 7px 14px; }
}
</style>
@endpush

@section('content')
@php
    $totalTests      = $mockTests->count();
    $completedTests  = $mockTests->filter(fn($t) => $t->user_attempts > 0)->count();
    $overallProgress = $totalTests > 0 ? round(($completedTests / $totalTests) * 100) : 0;
    $lastAttempt = \App\Models\IeltsTestAttempt::where('user_id', auth()->id())
        ->where('status', 'in_progress')
        ->orderBy('updated_at', 'desc')
        ->first();
    $continueUrl = $lastAttempt
        ? route('panel.ielts_tests.take', $lastAttempt->id)
        : route('panel.ielts_tests.mock');
@endphp
<div class="wf-mock-page">
<div class="wf-page-wrap">
    <div class="row">

        {{-- LEFT COLUMN --}}
        <div class="col-12 col-lg-8 mb-20">

            {{-- Welcome Bar (Dashboard Style) --}}
            <div class="ielts-welcome-bar bg-white rounded-24 p-16 mb-20">
                {{-- Left: greeting --}}
                <div class="flex-grow-1 min-w-0">
                    <h1 class="font-18 font-weight-bold text-dark text-ellipsis mb-0">
                        WELCOME, {{ strtoupper($authUser->full_name) }}! 👋
                    </h1>
                    {{-- Overall progress bar --}}
                    <div class="ielts-welcome-bar__progress mt-8">
                        <div class="ielts-welcome-bar__track rounded-pill" style="height:6px;">
                            <div class="ielts-welcome-bar__fill rounded-pill" style="width:{{ $overallProgress }}%;height:6px;transition:width .6s ease;"></div>
                        </div>
                    </div>
                </div>

                {{-- Switch Courses dropdown --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-16 dropdown-toggle" type="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Switch courses
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow rounded-16 border-0 mt-8" style="min-width:220px;">
                        @forelse($enrolledCourses as $course)
                            <a class="dropdown-item d-flex align-items-center gap-8 py-8 px-12"
                               href="{{ $course->getLearningPageUrl() }}">
                                <div class="size-32 rounded-8 bg-gray-100 flex-shrink-0">
                                    <img src="{{ $course->getIcon() }}" alt="" class="img-cover rounded-8">
                                </div>
                                <span class="font-12 text-dark">{{ truncate($course->title, 28) }}</span>
                            </a>
                        @empty
                            <span class="dropdown-item font-12 text-gray-500">No courses enrolled</span>
                        @endforelse
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item font-12 text-primary" href="/panel/courses/purchases">All courses</a>
                    </div>
                </div>

                {{-- Continue → --}}
                <a href="{{ $continueUrl }}"
                   class="btn btn-primary btn-sm rounded-pill px-16">
                    continue &rarr;
                </a>

                {{-- Notification bell --}}
                <a href="/panel/notifications" class="ielts-welcome-bar__bell d-flex-center size-40 rounded-circle bg-gray-100 position-relative text-dark">
                    <x-iconsax-bul-notification class="icons" width="20px" height="20px"/>
                    @php
                        $unreadCount = !empty($unReadNotifications) ? count($unReadNotifications) : 0;
                    @endphp
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 end-0 size-16 rounded-circle bg-danger d-flex-center font-10 text-white"
                              style="font-size:9px;top:2px;right:2px;min-width:16px;height:16px;">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </a>
            </div>

            {{-- Daily limit badge --}}
            @if(isset($remainingToday) && $remainingToday > 0)
                <div class="wf-limit-badge success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $remainingToday }} attempt{{ $remainingToday != 1 ? 's' : '' }} remaining today (daily limit: {{ $dailyLimit ?? 2 }})</span>
                </div>
            @elseif(isset($remainingToday) && $remainingToday <= 0)
                <div class="wf-limit-badge warning">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ trans('update.no_attempts_left_today') }}</span>
                </div>
            @endif

            {{-- Mock Test Cards --}}
            @if($mockTests->isEmpty())
                <div class="wf-empty">
                    <img src="/assets/default/img/no-results/support.png" alt="">
                    <h3>{{ trans('update.no_mock_tests_available') }}</h3>
                    <p>{{ trans('update.no_mock_tests_hint') }}</p>
                </div>
            @else
                <div class="wf-test-list">
                    @foreach($mockTests as $test)
                    @php
                        $fullProgress = $test->user_attempts > 0 ? 100 : 0;
                        $skillCards = [
                            ['key' => 'listening', 'label' => 'Listening', 'icon' => 'fa-headphones-simple', 'color' => '#2ea8c7', 'soft' => 'rgba(46, 168, 199, 0.12)', 'hint' => 'Listening skill'],
                            ['key' => 'reading', 'label' => 'Reading', 'icon' => 'fa-book-open', 'color' => '#2f7a3f', 'soft' => 'rgba(47, 122, 63, 0.12)', 'hint' => 'Reading skill'],
                            ['key' => 'writing', 'label' => 'Writing', 'icon' => 'fa-pen-nib', 'color' => '#f29a3b', 'soft' => 'rgba(242, 154, 59, 0.12)', 'hint' => 'Writing skill'],
                            ['key' => 'speaking', 'label' => 'Speaking', 'icon' => 'fa-microphone-lines', 'color' => '#bb5b72', 'soft' => 'rgba(187, 91, 114, 0.12)', 'hint' => 'Speaking skill'],
                        ];
                        $canTakeTest = $test->can_take === true;
                    @endphp
                    <div class="wf-test-card">
                        <div class="wf-test-card__header">
                            <div>
                                <div class="wf-test-card__title">{{ $test->title }}</div>
                                <div class="wf-test-card__meta">
                                    <span class="wf-test-card__pill"><i class="fas fa-layer-group"></i> 4 skills</span>
                                    <span>{{ $test->total_duration ?? 165 }} min</span>
                                    @if($test->user_attempts > 0)
                                        <span>{{ $test->user_attempts }} attempt{{ $test->user_attempts != 1 ? 's' : '' }}</span>
                                    @endif
                                    @if($test->best_attempt && $test->best_attempt->overall_band)
                                        <span class="wf-best-score"><i class="fas fa-star"></i> Band {{ $test->best_attempt->overall_band }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="wf-test-card__progress">{{ $fullProgress }}%</div>
                        </div>

                        <div class="wf-skill-grid">
                            @foreach($skillCards as $skill)
                                @php
                                    $hasSkill = $test->{'has_' . $skill['key']} ?? false;
                                    $skillCanTake = $canTakeTest && $hasSkill;
                                @endphp
                                <div class="wf-skill-card {{ $skillCanTake ? '' : 'is-disabled' }}" style="--skill-color: {{ $skill['color'] }}; --skill-soft: {{ $skill['soft'] }};">
                                    <div class="wf-skill-card__top">
                                        <div class="wf-skill-card__icon">
                                            <i class="fas {{ $skill['icon'] }}"></i>
                                        </div>
                                        <div class="wf-skill-card__title">{{ $skill['label'] }}</div>
                                    </div>

                                    <div class="wf-skill-card__footer">
                                        @if($skillCanTake)
                                            <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" style="margin:0;width:100%;display:flex;justify-content:center;">
                                                @csrf
                                                <input type="hidden" name="skill" value="{{ $skill['key'] }}">
                                                <button type="submit" class="wf-skill-card__button">
                                                    <i class="fas fa-bolt"></i>
                                                    Làm bài
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="wf-skill-card__button" disabled>
                                                <i class="fas fa-lock"></i>
                                                @if(!$hasSkill)
                                                    Not available
                                                @else
                                                    Locked
                                                @endif
                                            </button>
                                        @endif
                                        <div class="wf-skill-card__hint">{{ $skill['hint'] }}</div>
                                        <div class="wf-skill-card__key">
                                            <i class="fas fa-key"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="wf-full-test">
                            <div class="wf-full-test__ribbon">NEW</div>
                            <div class="wf-full-test__info">
                                <div class="wf-full-test__icon">
                                    <i class="fas fa-th-large"></i>
                                </div>
                                <div>
                                    <div class="wf-full-test__title">Full Test</div>
                                    <div class="wf-full-test__subtitle">Take all 4 skills together</div>
                                </div>
                            </div>
                            <div class="wf-full-test__progress">
                                <div class="wf-full-test__progress-track">
                                    <div class="wf-full-test__progress-fill" style="width: {{ $fullProgress }}%"></div>
                                    <div class="wf-full-test__progress-label">{{ $fullProgress }}%</div>
                                </div>
                            </div>
                            <div class="wf-full-test__start">
                                @if($canTakeTest)
                                    <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="wf-full-test__button">
                                            <i class="fas fa-bolt"></i>
                                            Start
                                        </button>
                                    </form>
                                @elseif($test->can_take === 'daily_limit')
                                    <button type="button" class="wf-full-test__button disabled" disabled>
                                        <i class="fas fa-clock"></i>
                                        Daily limit
                                    </button>
                                @elseif($test->can_take === 'max_attempts')
                                    <button type="button" class="wf-full-test__button disabled" disabled>
                                        <i class="fas fa-lock"></i>
                                        Max attempts
                                    </button>
                                @elseif($test->can_take === 'not_enrolled')
                                    <a href="{{ route('panel.ielts_tests.show', $test->id) }}" class="wf-full-test__button" style="background:#f59e0b;text-decoration:none;">
                                        <i class="fas fa-shopping-cart"></i>
                                        Enroll
                                    </a>
                                @else
                                    <button type="button" class="wf-full-test__button disabled" disabled>
                                        <i class="fas fa-lock"></i>
                                        Locked
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>{{-- end left col --}}

        {{-- RIGHT COLUMN – sidebar --}}
        <div class="col-12 col-lg-4" style="display:flex;flex-direction:column;">
            @include('design_1.panel.ielts_tests.partials.sidebar')
        </div>
    </div>{{-- end row --}}
</div>
</div>
@endsection

@push('scripts_bottom')
<script>
(function () {
    var switchBtn  = document.getElementById('wfSwitchBtn');
    var switchDrop = document.getElementById('wfSwitchDropdown');
    if (switchBtn && switchDrop) {
        switchBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var opening = !switchDrop.classList.contains('open');
            switchDrop.classList.toggle('open');
            // Close the profile dropdown if we are opening the switch menu
            if (opening) {
                var profileCard = document.getElementById('wfProfileCard');
                if (profileCard) profileCard.classList.remove('open');
            }
        });
        document.addEventListener('click', function () {
            switchDrop.classList.remove('open');
        });
    }
})();
</script>
@endpush


