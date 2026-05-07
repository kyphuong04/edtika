@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* =====================================================
   PRACTICE TEST PAGE — Wireframe layout
   ===================================================== */

.wf-practice-page {
    --primary: #511D99;
    --primary-hover: #451884;
    --wf-accent: #511D99;
    --wf-accent-hover: #451884;
}
.wf-practice-page .text-primary,
.wf-practice-page .text-primary:hover,
.wf-practice-page .text-primary:focus {
    color: var(--wf-accent) !important;
}
.wf-practice-page .bg-primary,
.wf-practice-page .btn-primary,
.wf-practice-page .btn-primary:hover,
.wf-practice-page .btn-primary:focus,
.wf-practice-page .btn-primary:active,
.wf-practice-page .btn-primary:not(:disabled):not(.disabled):active {
    background-color: var(--wf-accent) !important;
    border-color: var(--wf-accent) !important;
    color: #fff !important;
}
.wf-practice-page .border-primary {
    border-color: var(--wf-accent) !important;
}

/* PAGE WRAP */
.wf-page-wrap { padding: 24px 0; }

/* ── Welcome Bar (Dashboard-style Bootstrap) ─────────────────────────────────────── */
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

/* Overall progress bar (legacy) */
.wf-overall-progress { margin-top: 14px; }
.wf-progress-label {
    display: flex; justify-content: space-between;
    font-size: 11px; color: #94a3b8; margin-bottom: 6px;
}
.wf-progress-track { height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; }
.dark-mode .wf-progress-track { background: #334155; }
.wf-progress-fill {
    height: 100%; border-radius: 3px;
    background: linear-gradient(90deg, var(--wf-accent), var(--wf-accent-hover)); transition: width 0.4s;
}

/* ── Skill Section Header ───────────────────────────── */
.wf-skill-section { margin-bottom: 28px; }
.wf-skill-header {
    background: rgba(212, 211, 254, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.62);
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
.wf-skill-header-icon.listening { background: rgba(81, 29, 153, 0.14); color: var(--wf-accent); }
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
.listening .wf-skill-header-prog-fill { background: var(--wf-accent); }
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
    background: rgba(212, 211, 254, 0.32);
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
    background: rgba(244, 243, 255, 0.92);
    border: 1.5px solid rgba(214, 210, 243, 0.95);
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
.listening-row .wf-practice-row-prog-fill { background: var(--wf-accent); }
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
    background: rgba(244, 243, 255, 0.92); color: #6b7280;
    transition: all 0.2s; text-decoration: none;
}
.wf-band-tab:hover { border-color: var(--wf-accent); color: var(--wf-accent); text-decoration: none; }
.wf-band-tab.active { background: var(--wf-accent); border-color: var(--wf-accent); color: white; }

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

<div class="wf-practice-page">
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

