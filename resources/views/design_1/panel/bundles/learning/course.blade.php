@extends('design_1.panel.layouts.panel')

{{-- "Tổng quan khóa học" của học viên - dựng theo màn #overview trong bản demo của khách, dữ liệu thật --}}

@push('styles_top')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-storefront.css?v=gd5-4">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-learning.css?v=1">
@endpush

@section('content')
    @php
        $lockIcon = '<span class="v10-white-lock" aria-label="' . e(trans('bundle_learning.status_locked')) . '"><svg class="v9-svg" viewBox="0 0 24 24" aria-hidden="true"><rect width="14" height="11" x="5" y="10" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg></span>';
    @endphp

    <div class="edk edk-learning">
        <section id="overview">
            <div class="crumb">
                <a href="/panel/courses/purchases">{{ trans('panel.my_courses') }}</a><span>›</span><b>{{ trans('bundle_learning.overview_crumb') }}</b>
            </div>

            {{-- ===== Hero + Thông tin ===== --}}
            <div class="shell overview-hero">
                <div class="overview-main">
                    <div>
                        <div class="overview-eyebrow">{{ trans('bundle_learning.overview_eyebrow') }}</div>
                        <h1>{{ $bundle->title }}</h1>
                        @if(!empty($bundle->summary))
                            <p class="muted">{{ trim(strip_tags($bundle->summary)) }}</p>
                        @endif
                        <div class="meta-pills">
                            <span class="tag">{{ strtoupper($bundle->exam_type ?: 'ielts') }}</span>
                            <span class="tag outline">{{ $progress['modules_total'] }} module</span>
                            <span class="tag outline">{{ $syllabus['lessons_total'] }} lessons</span>
                            <span class="tag outline">{{ $syllabus['videos_total'] }} video</span>
                        </div>
                    </div>

                    <div class="overview-aside">
                        <h3>{{ trans('bundle_learning.overview_info') }}</h3>
                        <div class="quickstats">
                            <span>{{ trans('bundle_learning.overview_current_module') }}<b>{{ $syllabus['current_module'] }}/{{ $progress['modules_total'] }}</b></span>
                            <span>{{ trans('bundle_learning.overview_videos_watched') }}<b>{{ $syllabus['current_videos_done'] }}/{{ $syllabus['current_videos_total'] }}</b></span>
                            <span>{{ trans('bundle_learning.overview_assessment') }}<b>{{ $syllabus['current_assignments_done'] }}/{{ $syllabus['current_assignments_total'] }}</b></span>
                            <span>{{ trans('bundle_learning.overview_status') }}<b>{{ $syllabus['status_text'] }}</b></span>
                        </div>
                        <a href="{{ $syllabus['next_url'] ?: '#' }}" class="action primary {{ empty($syllabus['next_url']) ? 'is-disabled' : '' }}" style="margin-top:17px;width:100%">
                            {{ $syllabus['started'] ? trans('bundle_learning.overview_continue') : trans('bundle_learning.overview_start') }} →
                        </a>
                    </div>
                </div>
            </div>

            {{-- ===== Lộ trình bài học ===== --}}
            <div class="shell syllabus">
                <div class="syllabus-select">
                    <div>
                        <h2>{{ trans('bundle_learning.syllabus_title') }}</h2>
                        <p class="section-sub">{{ trans('bundle_learning.syllabus_sub') }}</p>
                    </div>
                </div>

                <div class="syllabus-lesson-list">
                    @if(empty($syllabus['modules']))
                        <p class="syllabus-empty">{{ trans('bundle_learning.syllabus_empty') }}</p>
                    @else
                        <div class="syllabus-modules">
                            @foreach($syllabus['modules'] as $module)
                                <details class="syllabus-module" {{ ($module['open'] and $module['unlocked']) ? 'open' : '' }} @if(!$module['unlocked']) data-locked="1" @endif>
                                    <summary>
                                        <span>Module {{ $module['number'] }} · {{ $module['title'] }}</span>
                                        @if($module['unlocked'])
                                            <span>⌄</span>
                                        @else
                                            {!! $lockIcon !!}
                                        @endif
                                    </summary>

                                    <div class="module-lesson-list">
                                        @forelse($module['lessons'] as $lessonIndex => $lesson)
                                            <details class="syllabus-lesson" {{ ($module['open'] and $lessonIndex === $module['open_lesson']) ? 'open' : '' }}>
                                                <summary>
                                                    <span>Lesson {{ str_pad($lesson['number'], 2, '0', STR_PAD_LEFT) }} · {{ $lesson['title'] }} @if($lesson['completed'])<span class="done-mark">✓</span>@endif</span>
                                                    <span class="syllabus-summary-right"><span class="syllabus-video-count">{{ $lesson['videos'] }} video</span>⌄</span>
                                                </summary>

                                                <div class="syllabus-items">
                                                    @foreach($lesson['lines'] as $line)
                                                        <div class="line">
                                                            <span>{{ $line['label'] }}</span>
                                                            @if($line['state'] === 'done')
                                                                <span class="done-mark">✓</span>
                                                            @elseif($line['state'] === 'lock')
                                                                {!! $lockIcon !!}
                                                            @elseif($line['state'] === 'dash')
                                                                <span class="line-state">—</span>
                                                            @elseif($line['state'] === 'text')
                                                                <span class="line-state">{{ $line['text'] }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach

                                                    <div class="line">
                                                        <span>{{ trans('bundle_learning.syllabus_lesson_status') }}</span>
                                                        @if($lesson['status']['state'] === 'done')
                                                            <span class="done-mark">{{ $lesson['status']['text'] }}</span>
                                                        @else
                                                            <span class="line-state">{{ $lesson['status']['text'] }}</span>
                                                        @endif
                                                    </div>

                                                    <a href="{{ $lesson['unlocked'] ? $lesson['url'] : '#' }}" class="action sm primary ed20-lesson-cta {{ $lesson['unlocked'] ? '' : 'is-disabled' }}">{{ trans('bundle_learning.syllabus_enter_lesson') }}</a>
                                                </div>
                                            </details>
                                        @empty
                                            <p class="syllabus-empty">{{ trans('bundle_learning.syllabus_module_empty') }}</p>
                                        @endforelse
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    @endif

                    {{-- ===== Mid-term / Final ===== --}}
                    @if(!empty($progress['checkpoints']))
                        <div class="edk-checkpoints">
                            @foreach($progress['checkpoints'] as $checkpoint)
                                @php
                                    $checkpointQuiz = $checkpointQuizzes->get($checkpoint['quiz_id']);
                                @endphp
                                <div class="edk-checkpoint {{ $checkpoint['type'] === 'final' ? 'is-final' : '' }}">
                                    <div>
                                        <b>{{ $checkpoint['title'] ?: trans('bundle_learning.checkpoint_' . $checkpoint['type']) }}</b>
                                        <small>{{ trans('bundle_learning.checkpoint_requirement', ['done' => $progress['modules_completed'], 'required' => $checkpoint['required_modules']]) }}</small>
                                    </div>
                                    @if($checkpoint['passed'])
                                        <span class="done-mark">✓ {{ trans('bundle_learning.status_completed') }}</span>
                                    @elseif($checkpoint['unlocked'] and !empty($checkpointQuiz) and !empty($checkpointQuiz->webinar))
                                        <a href="/panel/courses/purchases/learning/{{ $checkpointQuiz->webinar->slug }}?type=quiz&item={{ $checkpointQuiz->id }}" class="action sm primary">{{ trans('bundle_learning.start') }} →</a>
                                    @else
                                        {!! $lockIcon !!}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        // Module chưa mở khóa: không cho mở rộng, báo cho học viên
        document.querySelectorAll('.edk-learning .syllabus-module[data-locked] > summary').forEach(function (summary) {
            summary.addEventListener('click', function (event) {
                event.preventDefault();
                if (typeof window.showToast === 'function') {
                    window.showToast('error', '', @json(trans('bundle_learning.syllabus_module_locked')));
                }
            });
        });
    </script>
@endpush
