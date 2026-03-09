@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* ============================================================
   MOCK TEST PAGE  –  Wireframe-style 2-column layout
   ============================================================ */

.wf-page-wrap { padding: 24px 0; }

/* ── Welcome Card ────────────────────────────────────────── */
.wf-welcome-card {
    background: #fff;
    border-radius: 20px;
    padding: 20px 24px 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    margin-bottom: 20px;
}
.dark-mode .wf-welcome-card { background: #1e293b; }

.wf-welcome-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.wf-welcome-title {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}
.dark-mode .wf-welcome-title { color: #f1f5f9; }

.wf-welcome-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

/* Switch-courses dropdown */
.wf-switch-dropdown { position: relative; display: inline-block; }
.wf-btn-switch {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 1.5px solid #d1d5db;
    border-radius: 20px;
    background: #fff;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.dark-mode .wf-btn-switch { background: #1e293b; border-color: #334155; color: #cbd5e1; }
.wf-btn-switch:hover { border-color: #3b82f6; color: #3b82f6; }
.wf-switch-menu {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    min-width: 220px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    z-index: 200;
    overflow: hidden;
}
.dark-mode .wf-switch-menu { background: #1e293b; border-color: #334155; }
.wf-switch-dropdown.open .wf-switch-menu { display: block; }
.wf-switch-menu-item {
    display: block;
    padding: 11px 16px;
    font-size: 13px;
    color: #374151;
    text-decoration: none;
    transition: background 0.15s;
}
.dark-mode .wf-switch-menu-item { color: #cbd5e1; }
.wf-switch-menu-item:hover { background: #f1f5f9; text-decoration: none; }
.dark-mode .wf-switch-menu-item:hover { background: #0f172a; }
.wf-switch-empty { padding: 12px 16px; font-size: 13px; color: #94a3b8; }

/* Continue button */
.wf-btn-continue {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 20px;
    background: #1e293b;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.wf-btn-continue:hover { background: #0f172a; text-decoration: none; color: #fff; }

.wf-bell-wrap { flex-shrink: 0; }

/* Overall progress bar */
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
    background: linear-gradient(90deg, #3b82f6, #6366f1);
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
    background: #fff;
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
    background: linear-gradient(90deg, #6366f1, #a78bfa);
}

.wf-skill-badges { display: flex; gap: 5px; flex-wrap: wrap; flex-shrink: 0; }
.wf-skill-badge {
    width: 26px; height: 26px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700;
}
.wf-skill-badge.L { background: #dbeafe; color: #3b82f6; }
.wf-skill-badge.R { background: #d1fae5; color: #10b981; }
.wf-skill-badge.W { background: #fef3c7; color: #f59e0b; }
.wf-skill-badge.S { background: #fee2e2; color: #ef4444; }

.wf-row-start { flex-shrink: 0; }
.wf-btn-row-start {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 20px; border-radius: 20px; background: #6366f1;
    color: #fff; font-size: 13px; font-weight: 600; border: none;
    cursor: pointer; transition: background 0.2s, transform 0.15s;
    text-decoration: none; white-space: nowrap;
}
.wf-btn-row-start:hover { background: #4f46e5; text-decoration: none; color: #fff; transform: scale(1.03); }
.wf-btn-row-start.disabled { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; transform: none; }
.wf-btn-view-result {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: 20px; background: transparent;
    color: #6366f1; font-size: 13px; font-weight: 600;
    border: 1.5px solid #6366f1; cursor: pointer;
    transition: all 0.2s; text-decoration: none; white-space: nowrap;
}
.wf-btn-view-result:hover { background: #6366f1; color: #fff; text-decoration: none; }
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
<div class="wf-page-wrap">
    <div class="row">

        {{-- LEFT COLUMN --}}
        <div class="col-12 col-lg-8 mb-20">

            {{-- Welcome Card --}}
            <div class="wf-welcome-card">
                <div class="wf-welcome-row">
                    <h1 class="wf-welcome-title">WELCOME, {{ strtoupper($authUser->full_name) }}!</h1>
                    <div class="wf-welcome-actions">
                        <div class="wf-switch-dropdown" id="wfSwitchDropdown">
                            <button class="wf-btn-switch" id="wfSwitchBtn">
                                Switch courses <i class="fas fa-chevron-down" style="font-size:11px;"></i>
                            </button>
                            <div class="wf-switch-menu">
                                @forelse($enrolledCourses as $course)
                                    <a href="{{ $course->getLearningPageUrl() }}" class="wf-switch-menu-item">{{ Str::limit($course->title, 40) }}</a>
                                @empty
                                    <div class="wf-switch-empty">No enrolled courses</div>
                                @endforelse
                            </div>
                        </div>
                        <a href="{{ $continueUrl }}" class="wf-btn-continue">continue &rarr;</a>
                        <div class="wf-bell-wrap language-select position-relative">
                            <div class="size-32 position-relative d-flex-center bg-gray-100 rounded-8" style="cursor:pointer;">
                                <x-iconsax-lin-notification class="icons text-gray-500" width="20px" height="20px"/>
                                @if(!empty($unReadNotifications) and count($unReadNotifications))
                                    <span class="panel-header__badge-counter badge-counter">{{ count($unReadNotifications) }}</span>
                                @endif
                            </div>
                            <div class="language-dropdown language-dropdown__notifications py-12">
                                @if(!empty($unReadNotifications) and count($unReadNotifications))
                                    <div class="px-12">
                                        <div class="d-flex align-items-center p-12 rounded-12 bg-gray-100">
                                            <div class="d-flex-center size-48 bg-white rounded-circle">
                                                <div class="d-flex-center size-40 bg-primary rounded-circle">
                                                    <x-iconsax-bul-notification-bing class="icons text-white" width="24px" height="24px"/>
                                                </div>
                                            </div>
                                            <div class="ml-8">
                                                <h5 class="font-14">{{ count($unReadNotifications) }} {{ trans('panel.notifications') }}</h5>
                                                <a href="/panel/notifications/mark-all-as-read" class="delete-action d-block mt-4 font-12 cursor-pointer text-gray-500" data-msg="{{ trans('update.convert_unread_messages_to_read') }}" data-confirm="{{ trans('update.yes_convert') }}">
                                                    {{ trans('update.mark_as_read') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($unReadNotifications->take(3) as $unReadNotification)
                                        <a href="/panel/notifications?notification={{ $unReadNotification->id }}" class="language-dropdown__item d-flex align-items-center w-100 px-16 py-8 text-dark bg-transparent">
                                            <div><x-iconsax-bul-notification class="icons text-gray-500" width="24px" height="24px"/></div>
                                            <div class="ml-8">
                                                <h4 class="font-12">{{ $unReadNotification->title }}</h4>
                                                <span class="d-block text-gray-500 font-12 mt-8">{{ dateTimeFormat($unReadNotification->created_at, 'j M Y | H:i') }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                    <div class="px-12">
                                        <a href="/panel/notifications" class="btn btn-lg btn-primary btn-block mt-12">{{ trans('notification.all_notifications') }}</a>
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
                </div>
                <div class="wf-overall-progress">
                    <div class="wf-progress-label">
                        <span>Overall progress</span>
                        <span>{{ $completedTests }}/{{ $totalTests }} tests</span>
                    </div>
                    <div class="wf-progress-track">
                        <div class="wf-progress-fill" style="width: {{ $overallProgress }}%"></div>
                    </div>
                </div>
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


