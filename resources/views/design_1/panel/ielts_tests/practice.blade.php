@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* =====================================================
   PRACTICE TEST PAGE — Wireframe layout
   ===================================================== */
/* PAGE WRAP */
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
    display: flex; align-items: center;
    justify-content: space-between; gap: 12px; flex-wrap: wrap;
}
.wf-welcome-title {
    font-size: 18px; font-weight: 700; color: #1e293b;
    text-transform: uppercase; letter-spacing: 0.5px; margin: 0;
}
.dark-mode .wf-welcome-title { color: #f1f5f9; }
.wf-welcome-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

/* Switch-courses dropdown */
.wf-switch-dropdown { position: relative; display: inline-block; }
.wf-btn-switch {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border: 1.5px solid #d1d5db;
    border-radius: 20px; background: #fff; color: #374151;
    font-size: 13px; font-weight: 600; cursor: pointer;
    transition: all 0.2s; white-space: nowrap;
}
.dark-mode .wf-btn-switch { background: #1e293b; border-color: #334155; color: #cbd5e1; }
.wf-btn-switch:hover { border-color: #3b82f6; color: #3b82f6; }
.wf-switch-menu {
    display: none; position: absolute; top: calc(100% + 6px); left: 0;
    min-width: 220px; background: #fff; border: 1px solid #e5e7eb;
    border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    z-index: 200; overflow: hidden;
}
.dark-mode .wf-switch-menu { background: #1e293b; border-color: #334155; }
.wf-switch-dropdown.open .wf-switch-menu { display: block; }
.wf-switch-menu-item {
    display: block; padding: 11px 16px; font-size: 13px;
    color: #374151; text-decoration: none; transition: background 0.15s;
}
.dark-mode .wf-switch-menu-item { color: #cbd5e1; }
.wf-switch-menu-item:hover { background: #f1f5f9; text-decoration: none; }
.wf-switch-empty { padding: 12px 16px; font-size: 13px; color: #94a3b8; }

/* Continue button */
.wf-btn-continue {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 20px; background: #1e293b;
    color: #fff; font-size: 13px; font-weight: 600;
    text-decoration: none; transition: all 0.2s; white-space: nowrap;
}
.wf-btn-continue:hover { background: #0f172a; text-decoration: none; color: #fff; }
.wf-bell-wrap { flex-shrink: 0; }
.wf-overall-progress { margin-top: 14px; }
.wf-progress-label {
    display: flex; justify-content: space-between;
    font-size: 11px; color: #94a3b8; margin-bottom: 6px;
}
.wf-progress-track { height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; }
.dark-mode .wf-progress-track { background: #334155; }
.wf-progress-fill {
    height: 100%; border-radius: 3px;
    background: linear-gradient(90deg, #3b82f6, #6366f1); transition: width 0.4s;
}

/* ── Skill Section Header ───────────────────────────── */
.wf-skill-section { margin-bottom: 28px; }
.wf-skill-header {
    background: #fff;
    border-radius: 16px;
    padding: 14px 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.dark-mode .wf-skill-header { background: #1e293b; }
.wf-skill-header-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; flex-shrink: 0;
}
.wf-skill-header-icon.listening { background: #dbeafe; color: #3b82f6; }
.wf-skill-header-icon.reading   { background: #d1fae5; color: #10b981; }
.wf-skill-header-icon.writing   { background: #fef3c7; color: #f59e0b; }
.wf-skill-header-icon.speaking  { background: #fee2e2; color: #ef4444; }
.wf-skill-header-info { flex: 1; }
.wf-skill-header-title {
    font-size: 13px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.5px;
    color: #1e293b; margin-bottom: 2px;
}
.dark-mode .wf-skill-header-title { color: #f1f5f9; }
.wf-skill-header-count { font-size: 11px; color: #94a3b8; margin-bottom: 6px; }
.wf-skill-header-prog {
    height: 5px; background: #f1f5f9; border-radius: 3px; overflow: hidden;
}
.dark-mode .wf-skill-header-prog { background: #334155; }
.wf-skill-header-prog-fill {
    height: 100%; border-radius: 3px;
    transition: width 0.4s;
}
.listening .wf-skill-header-prog-fill { background: #3b82f6; }
.reading   .wf-skill-header-prog-fill { background: #10b981; }
.writing   .wf-skill-header-prog-fill { background: #f59e0b; }
.speaking  .wf-skill-header-prog-fill { background: #ef4444; }

/* ── Accordion ──────────────────────────────────────── */
.wf-skill-header {
    cursor: pointer;
    user-select: none;
    transition: border-radius 0.2s;
}
.wf-skill-section.open .wf-skill-header {
    border-radius: 16px 16px 0 0;
}
.wf-skill-header-chevron {
    margin-left: auto;
    color: #94a3b8;
    font-size: 13px;
    flex-shrink: 0;
    transition: transform 0.25s ease;
}
.wf-skill-section.open .wf-skill-header-chevron {
    transform: rotate(180deg);
}
.wf-skill-rows {
    display: none;
    background: #f0f2f5;
    border-radius: 0 0 18px 18px;
    padding: 14px 14px 6px;
    margin-top: -6px;
}
.dark-mode .wf-skill-rows { background: #0f172a; }
.wf-skill-section.open .wf-skill-rows {
    display: block;
}

/* ── Individual Practice Row ─────────────────────────── */
.wf-practice-row {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    margin-bottom: 10px;
    overflow: hidden;
    transition: box-shadow 0.2s, transform 0.15s;
}
.dark-mode .wf-practice-row { background: #1e293b; border-color: #334155; }
.wf-practice-row:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.09); transform: translateY(-1px); }
.wf-practice-row-inner {
    display: flex;
    align-items: center;
    /* increase padding for greater height */
    padding: 24px 26px;
    gap: 20px;
}
.wf-practice-row-title {
    flex: 1;
    /* larger text for readability */
    font-size: 16px; font-weight: 800;
    color: #1e293b; text-transform: uppercase;
    letter-spacing: 0.5px;
}
.dark-mode .wf-practice-row-title { color: #f1f5f9; }
.wf-practice-row-attempts {
    font-size: 11px; color: #94a3b8; margin-top: 3px; font-weight: 400;
}
.wf-btn-start {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 20px;
    border: 1.5px solid #1e293b; background: transparent;
    color: #1e293b; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: all 0.2s; white-space: nowrap;
    text-decoration: none;
}
.dark-mode .wf-btn-start { border-color: #e2e8f0; color: #e2e8f0; }
.wf-btn-start:hover { background: #1e293b; color: #fff; text-decoration: none; }
.dark-mode .wf-btn-start:hover { background: #e2e8f0; color: #1e293b; }
.wf-btn-retry {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 20px;
    border: 1.5px solid #1e293b; background: #1e293b;
    color: #fff; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: all 0.2s; white-space: nowrap;
    text-decoration: none;
}
.dark-mode .wf-btn-retry { border-color: #e2e8f0; background: #e2e8f0; color: #1e293b; }
.wf-btn-retry:hover { background: #374151; border-color: #374151; color: #fff; text-decoration: none; }
.wf-row-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.wf-practice-row-prog {
    /* thicker progress bar */
    height: 8px;
    background: #e9ecef;
    margin: 0 16px 14px;
    border-radius: 999px;
    overflow: hidden;
}
.dark-mode .wf-practice-row-prog { background: #334155; }
.wf-practice-row-prog-fill {
    height: 100%; border-radius: 999px; transition: width 0.4s;
}
.listening-row .wf-practice-row-prog-fill { background: #3b82f6; }
.reading-row   .wf-practice-row-prog-fill { background: #10b981; }
.writing-row   .wf-practice-row-prog-fill { background: #f59e0b; }
.speaking-row  .wf-practice-row-prog-fill { background: #ef4444; }

/* ── Band Filter Tabs ────────────────────────────────── */
.wf-band-tabs {
    display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px;
}
.wf-band-tab {
    padding: 8px 18px; border-radius: 20px; font-weight: 600;
    font-size: 13px; border: 2px solid #e5e7eb;
    background: white; color: #6b7280;
    transition: all 0.2s; text-decoration: none;
}
.wf-band-tab:hover { border-color: #3b82f6; color: #3b82f6; text-decoration: none; }
.wf-band-tab.active { background: #3b82f6; border-color: #3b82f6; color: white; }

/* ── Empty state ─────────────────────────────────────── */
.wf-empty {
    text-align: center; padding: 60px 20px;
    background: #f9fafb; border-radius: 20px; border: 2px dashed #e5e7eb;
}
.dark-mode .wf-empty { background: #1e293b; border-color: #334155; }
.wf-empty img { max-width: 140px; opacity: 0.7; margin-bottom: 16px; }
.wf-empty h3 { font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px; }
.wf-empty p { color: #6b7280; margin: 0; font-size: 14px; }

@media (max-width: 767px) {
    .wf-skill-header        { flex-wrap: wrap; }
    .wf-practice-row-inner  { flex-wrap: wrap; }
    .wf-btn-start, .wf-btn-retry { font-size: 12px; padding: 7px 14px; }
    .wf-row-actions         { flex-wrap: wrap; }
}

/* compact rows to match wireframe */
.wf-practice-row { min-height: auto !important; }
.wf-practice-row-inner { padding: 14px 18px !important; }
.wf-practice-row-title { font-size: 14px !important; }
.wf-practice-row-prog { height: 5px !important; }

</style>
@endpush

@section('content')
@php
    $totalTests      = $practiceTests->count();
    $completedTests  = $practiceTests->filter(fn($t) => ($t->user_attempts ?? 0) > 0)->count();
    $overallProgress = $totalTests > 0 ? round(($completedTests / $totalTests) * 100) : 0;
    $continueUrl     = route('panel.ielts_tests.practice');
    $listeningTests  = $practiceTests->filter(fn($t) => $t->has_listening);
    $readingTests    = $practiceTests->filter(fn($t) => $t->has_reading);
    $writingTests    = $practiceTests->filter(fn($t) => $t->has_writing);
    $speakingTests   = $practiceTests->filter(fn($t) => $t->has_speaking);
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

            {{-- Skill-grouped sections --}}
            @if($practiceTests->isEmpty())
                <div class="wf-empty">
                    <img src="/assets/default/img/no-results/support.png" alt="">
                    <h3>{{ trans('update.no_practice_tests_available') }}</h3>
                    <p>{{ trans('update.no_practice_tests_hint') }}</p>
                </div>
            @else

            @php
                $skillGroups = [
                    'listening' => ['label' => 'LISTENING', 'icon' => 'fa-headphones', 'skill' => 'listening', 'tests' => $listeningTests],
                    'reading'   => ['label' => 'READING',   'icon' => 'fa-book-open',  'skill' => 'reading',   'tests' => $readingTests],
                    'writing'   => ['label' => 'WRITING',   'icon' => 'fa-pen-fancy',  'skill' => 'writing',   'tests' => $writingTests],
                    'speaking'  => ['label' => 'SPEAKING',  'icon' => 'fa-microphone', 'skill' => 'speaking',  'tests' => $speakingTests],
                ];
            @endphp

            @foreach($skillGroups as $key => $group)
            @if($group['tests']->isNotEmpty())
            @php
                $groupTotal     = $group['tests']->count();
                $groupCompleted = $group['tests']->filter(fn($t) => ($t->user_attempts ?? 0) > 0)->count();
                $groupProgress  = $groupTotal > 0 ? round(($groupCompleted / $groupTotal) * 100) : 0;
            @endphp
            <div class="wf-skill-section">

                {{-- Skill section header (clickable) --}}
                <div class="wf-skill-header {{ $key }}">
                    <div class="wf-skill-header-icon {{ $key }}">
                        <i class="fas {{ $group['icon'] }}"></i>
                    </div>
                    <div class="wf-skill-header-info">
                        <div class="wf-skill-header-title">{{ $group['label'] }}</div>
                        <div class="wf-skill-header-count">{{ $groupTotal }} {{ $groupTotal == 1 ? 'Practice' : 'Practices' }}</div>
                        <div class="wf-skill-header-prog">
                            <div class="wf-skill-header-prog-fill" style="width: {{ $groupProgress }}%"></div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down wf-skill-header-chevron"></i>
                </div>

                {{-- Individual practice rows (hidden until section is open) --}}
                <div class="wf-skill-rows">
                    @foreach($group['tests'] as $test)
                    @php $rowProgress = ($test->user_attempts ?? 0) > 0 ? 100 : 0; @endphp
                    <div class="wf-practice-row {{ $key }}-row">
                        <div class="wf-practice-row-inner">
                            <div style="flex:1;">
                                <div class="wf-practice-row-title">{{ $test->title }}</div>
                                @if(($test->user_attempts ?? 0) > 0)
                                <div class="wf-practice-row-attempts">Completed {{ $test->user_attempts }}x</div>
                                @endif
                            </div>
                            <div class="wf-row-actions">
                                @if(($test->user_attempts ?? 0) > 0)
                                    <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="skill" value="{{ $group['skill'] }}">
                                        <button type="submit" class="wf-btn-retry">Retry</button>
                                    </form>
                                    @if($test->last_attempt)
                                        <a href="{{ route('panel.ielts_tests.results', $test->last_attempt->id) }}" class="wf-btn-start">View result</a>
                                    @endif
                                @else
                                    <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="skill" value="{{ $group['skill'] }}">
                                        <button type="submit" class="wf-btn-start">Start &rarr;</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="wf-practice-row-prog">
                            <div class="wf-practice-row-prog-fill" style="width: {{ $rowProgress }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
            @endif
            @endforeach

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
    // Skill section accordion
    document.querySelectorAll('.wf-skill-header').forEach(function (header) {
        header.addEventListener('click', function () {
            var section = header.closest('.wf-skill-section');
            section.classList.toggle('open');
        });
    });

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

