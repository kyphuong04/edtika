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

.wf-page-wrap { padding: 24px 0; }

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

/* ── Section Rows ─────────────────────────────────────── */
.wf-sections-list { display: flex; flex-direction: column; gap: 12px; }

.wf-section-row {
    background: rgba(212, 211, 254, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.62);
    border-radius: 16px;
    padding: 18px 22px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: box-shadow 0.2s, transform 0.2s;
}
.dark-mode .wf-section-row { background: #1e293b; }
.wf-section-row:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.1); transform: translateY(-1px); }

.wf-section-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.wf-section-icon.mock  { background: #ede9fe; color: #7c3aed; }
.wf-section-icon.info  { background: #f0f9ff; color: #0ea5e9; }

.wf-section-meta { flex: 0 0 auto; min-width: 160px; }
.wf-section-name {
    font-size: 14px; font-weight: 700; color: #1e293b;
    text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 3px;
}
.dark-mode .wf-section-name { color: #f1f5f9; }
.wf-section-count { font-size: 12px; color: #94a3b8; }

.wf-row-progress { flex: 1; min-width: 80px; }
.wf-row-progress-track {
    height: 5px; background: #f1f5f9; border-radius: 3px; overflow: hidden;
}
.dark-mode .wf-row-progress-track { background: #334155; }
.wf-row-progress-fill {
    height: 100%; border-radius: 3px;
    background: linear-gradient(90deg, var(--wf-accent), var(--wf-accent-hover));
}

.wf-skill-badges { display: flex; gap: 5px; flex-wrap: wrap; flex-shrink: 0; }
.wf-skill-badge {
    width: 26px; height: 26px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700;
}
.wf-skill-badge.L { background: rgba(81, 29, 153, 0.14); color: var(--wf-accent); }
.wf-skill-badge.R { background: #d1fae5; color: #10b981; }
.wf-skill-badge.W { background: #fef3c7; color: #f59e0b; }
.wf-skill-badge.S { background: #fee2e2; color: #ef4444; }

.wf-row-start { flex-shrink: 0; }
.wf-btn-row-start {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 20px; border-radius: 20px; background: var(--wf-accent);
    color: #fff; font-size: 13px; font-weight: 600; border: none;
    cursor: pointer; transition: background 0.2s, transform 0.15s;
    text-decoration: none; white-space: nowrap;
}
.wf-btn-row-start:hover { background: var(--wf-accent-hover); text-decoration: none; color: #fff; transform: scale(1.03); }
.wf-btn-row-start.disabled { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; transform: none; }
.wf-btn-view-result {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: 20px; background: transparent;
    color: var(--wf-accent); font-size: 13px; font-weight: 600;
    border: 1.5px solid var(--wf-accent); cursor: pointer;
    transition: all 0.2s; text-decoration: none; white-space: nowrap;
}
.wf-btn-view-result:hover { background: var(--wf-accent); color: #fff; text-decoration: none; }
.wf-row-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

.wf-best-score {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 20px;
    background: #d1fae5; color: #059669; font-size: 11px; font-weight: 600; flex-shrink: 0;
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

            {{-- Mock Test Rows --}}
            @if($mockTests->isEmpty())
                <div class="wf-empty">
                    <img src="/assets/default/img/no-results/support.png" alt="">
                    <h3>{{ trans('update.no_mock_tests_available') }}</h3>
                    <p>{{ trans('update.no_mock_tests_hint') }}</p>
                </div>
            @else
                <div class="wf-sections-list">
                    @foreach($mockTests as $test)
                    @php $rowProgress = $test->user_attempts > 0 ? 100 : 0; @endphp
                    <div class="wf-section-row">
                        <div class="wf-section-icon mock"><i class="fas fa-clipboard-list"></i></div>
                        <div class="wf-section-meta">
                            <div class="wf-section-name">{{ $test->title }}</div>
                            <div class="wf-section-count">
                                {{ $test->total_duration ?? 165 }} min
                                @if($test->user_attempts > 0) &bull; {{ $test->user_attempts }} attempt{{ $test->user_attempts != 1 ? 's' : '' }} @endif
                            </div>
                        </div>
                        <div class="wf-skill-badges">
                            @if($test->has_listening)<span class="wf-skill-badge L">L</span>@endif
                            @if($test->has_reading)  <span class="wf-skill-badge R">R</span>@endif
                            @if($test->has_writing)  <span class="wf-skill-badge W">W</span>@endif
                            @if($test->has_speaking) <span class="wf-skill-badge S">S</span>@endif
                        </div>
                        <div class="wf-row-progress">
                            <div class="wf-row-progress-track">
                                <div class="wf-row-progress-fill" style="width: {{ $rowProgress }}%"></div>
                            </div>
                        </div>
                        @if($test->best_attempt && $test->best_attempt->overall_band)
                            <div class="wf-best-score"><i class="fas fa-star"></i> Band {{ $test->best_attempt->overall_band }}</div>
                        @endif
                        <div class="wf-row-start">
                            @if($test->can_take === true)
                                <div class="wf-row-actions">
                                    <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="wf-btn-row-start">
                                            <i class="fas fa-play"></i>
                                            {{ $test->user_attempts > 0 ? 'Retry' : trans('update.start_test') }}
                                        </button>
                                    </form>
                                    @if($test->user_attempts > 0 && $test->last_attempt)
                                        <a href="{{ route('panel.ielts_tests.results', $test->last_attempt->id) }}" class="wf-btn-view-result">View result</a>
                                    @endif
                                </div>
                            @elseif($test->can_take === 'daily_limit')
                                <span class="wf-btn-row-start disabled"><i class="fas fa-clock"></i> Daily limit</span>
                            @elseif($test->can_take === 'max_attempts')
                                <span class="wf-btn-row-start disabled"><i class="fas fa-lock"></i> Max attempts</span>
                            @elseif($test->can_take === 'not_enrolled')
                                <a href="{{ route('panel.ielts_tests.show', $test->id) }}" class="wf-btn-row-start" style="background:#f59e0b;">
                                    <i class="fas fa-shopping-cart"></i> Enroll
                                </a>
                            @else
                                <span class="wf-btn-row-start disabled"><i class="fas fa-lock"></i> Locked</span>
                            @endif
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


