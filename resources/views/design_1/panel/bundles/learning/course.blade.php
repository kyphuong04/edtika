@extends('design_1.panel.layouts.panel')

{{-- GĐ3: "Khóa học của tôi" theo design mới --}}

@push('styles_top')
    @include('design_1.panel.bundles.learning.partials.styles')
@endpush

@section('content')
    @php
        $counts = $progress['counts'];
        $moduleCount = $progress['modules_total'];
        $courseLessonUrl = '/panel/courses/purchases/' . $bundle->slug . '/lessons/';
    @endphp

    <div class="bl-page">
        <div class="bl-layout">
            {{-- ===== Sidebar: đổi khóa học ===== --}}
            <aside class="bl-sidebar">
                <div class="bl-card">
                    <div class="bl-eyebrow">{{ trans('bundle_learning.my_courses') }}</div>
                    <ul class="bl-course-switch">
                        @foreach($purchasedBundles as $purchasedBundle)
                            <li>
                                <a href="/panel/courses/purchases/{{ $purchasedBundle->slug }}" class="{{ $purchasedBundle->id === $bundle->id ? 'is-active' : '' }}">{{ $purchasedBundle->title }}</a>
                            </li>
                        @endforeach
                        <li><a href="/panel/courses/purchases">{{ trans('bundle_learning.all_courses') }}</a></li>
                    </ul>
                </div>

                <div class="bl-card mt-16">
                    <div class="bl-eyebrow">{{ trans('bundle_learning.support') }}</div>
                    <a href="/panel/support/new" class="bl-link">{{ trans('bundle_learning.ask_teacher') }} →</a>
                </div>
            </aside>

            <main class="bl-main">
                {{-- ===== Hero ===== --}}
                <section class="bl-card bl-hero">
                    <div class="bl-crumb">
                        <a href="/panel/courses/purchases">{{ trans('bundle_learning.my_courses') }}</a>
                        <span>›</span>
                        <b>{{ $bundle->title }}</b>
                    </div>
                    <h1 class="bl-title">{{ $bundle->title }}</h1>
                    @if(!empty($bundle->summary))
                        <p class="bl-muted">{!! nl2br(e(strip_tags($bundle->summary))) !!}</p>
                    @endif

                    <div class="bl-progress-row">
                        <div class="bl-bar"><i style="width: {{ $progress['progress'] }}%"></i></div>
                        <span class="bl-progress-num">{{ round($progress['progress']) }}%</span>
                    </div>
                </section>

                {{-- ===== 3 ô tiến độ ===== --}}
                <section class="bl-stats">
                    <div class="bl-card bl-stat">
                        <span class="bl-stat__icon">▶</span>
                        <div>
                            <div class="bl-muted bl-small">{{ trans('bundle_learning.stat_videos') }}</div>
                            <div class="bl-stat__value">{{ $counts['videos_done'] }}/{{ $counts['videos_total'] }} video</div>
                        </div>
                    </div>
                    <div class="bl-card bl-stat">
                        <span class="bl-stat__icon">✓</span>
                        <div>
                            <div class="bl-muted bl-small">{{ trans('bundle_learning.stat_quizzes') }}</div>
                            <div class="bl-stat__value">{{ $counts['quizzes_done'] }}/{{ $counts['quizzes_total'] }} quiz</div>
                        </div>
                    </div>
                    <div class="bl-card bl-stat">
                        <span class="bl-stat__icon">↗</span>
                        <div>
                            <div class="bl-muted bl-small">{{ trans('bundle_learning.stat_progress') }}</div>
                            <div class="bl-stat__value">{{ round($progress['progress']) }}%</div>
                        </div>
                    </div>
                </section>

                {{-- ===== Bộ lọc ===== --}}
                <section class="bl-card bl-filters">
                    <div class="bl-filter">
                        <label for="blFilterSkill">{{ trans('bundle_learning.filter_skill') }}</label>
                        <select id="blFilterSkill" class="form-control js-bl-filter">
                            <option value="all">{{ trans('bundle_learning.all') }}</option>
                            @foreach(\App\Models\Webinar::SKILLS as $skill)
                                <option value="{{ $skill }}">{{ trans('bundle_learning.skill_' . $skill) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="bl-filter">
                        <label for="blFilterDuration">{{ trans('bundle_learning.filter_duration') }}</label>
                        <select id="blFilterDuration" class="form-control js-bl-filter">
                            <option value="all">{{ trans('bundle_learning.all') }}</option>
                            <option value="short">{{ trans('bundle_learning.duration_short', ['minutes' => round($shortVideoMaxSeconds / 60)]) }}</option>
                            <option value="long">{{ trans('bundle_learning.duration_long', ['minutes' => round($shortVideoMaxSeconds / 60)]) }}</option>
                        </select>
                    </div>
                    <div class="bl-filter">
                        <label for="blFilterStatus">{{ trans('bundle_learning.filter_status') }}</label>
                        <select id="blFilterStatus" class="form-control js-bl-filter">
                            <option value="all">{{ trans('bundle_learning.all') }}</option>
                            <option value="todo">{{ trans('bundle_learning.status_not_started') }}</option>
                            <option value="doing">{{ trans('bundle_learning.status_in_progress') }}</option>
                            <option value="done">{{ trans('bundle_learning.status_completed') }}</option>
                            <option value="locked">{{ trans('bundle_learning.status_locked') }}</option>
                        </select>
                    </div>
                </section>

                {{-- ===== Lesson theo module ===== --}}
                @forelse($progress['modules'] as $module)
                    <section class="bl-module js-bl-module">
                        <div class="bl-module__head">
                            <div>
                                <span class="bl-tag">{{ trans('bundle_learning.module') }} {{ $module['order_index'] + 1 }}/{{ $moduleCount }}</span>
                                @if(!empty($module['skill']))
                                    <span class="bl-tag bl-tag--outline">{{ trans('bundle_learning.skill_' . $module['skill']) }}</span>
                                @endif
                                <h2 class="bl-module__title">{{ $module['title'] }}</h2>
                            </div>
                            <div class="bl-module__meta">
                                @if(!$module['unlocked'])
                                    <span class="bl-lock">🔒 {{ trans('bundle_learning.status_locked') }}</span>
                                @elseif($module['completed'] and $module['counts']['items_total'] > 0)
                                    <span class="bl-done">✓ {{ trans('bundle_learning.status_completed') }}</span>
                                @else
                                    <span class="bl-muted bl-small">{{ round($module['progress']) }}%</span>
                                @endif
                            </div>
                        </div>

                        <div class="bl-grid">
                            @forelse($module['lessons'] as $lesson)
                                @php
                                    $lessonCounts = $lesson['counts'];
                                    $avgVideoSeconds = $lessonCounts['videos_total'] > 0 ? (int)round($lessonCounts['video_seconds'] / $lessonCounts['videos_total']) : 0;
                                    if (!$lesson['unlocked']) {
                                        $lessonStatus = 'locked';
                                    } elseif ($lesson['completed'] and $lessonCounts['items_total'] > 0) {
                                        $lessonStatus = 'done';
                                    } elseif ($lesson['started']) {
                                        $lessonStatus = 'doing';
                                    } else {
                                        $lessonStatus = 'todo';
                                    }
                                    $durationGroup = $avgVideoSeconds <= 0 ? 'unknown' : ($avgVideoSeconds < $shortVideoMaxSeconds ? 'short' : 'long');
                                @endphp

                                <article class="bl-card bl-lesson js-bl-lesson"
                                         data-skill="{{ $module['skill'] ?? '' }}"
                                         data-duration="{{ $durationGroup }}"
                                         data-status="{{ $lessonStatus }}">
                                    <div class="bl-lesson__cover bl-skill-{{ $module['skill'] ?? 'none' }}">
                                        <span class="bl-lesson__mark">{{ strtoupper($bundle->exam_type ?: 'ielts') }}</span>
                                        <span class="bl-lesson__num">{{ str_pad($lesson['order_index'] + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>

                                    <div class="bl-lesson__body">
                                        <h3 class="bl-lesson__title">{{ $lesson['title'] }}</h3>
                                        <p class="bl-muted bl-small">{{ $module['title'] }}</p>

                                        <div class="bl-metrics">
                                            <div><span>◷</span>{{ trans('bundle_learning.duration') }}<b>{{ $avgVideoSeconds > 0 ? trans('bundle_learning.minutes_per_video', ['minutes' => max(1, round($avgVideoSeconds / 60))]) : '-' }}</b></div>
                                            <div><span>▣</span>{{ trans('bundle_learning.lessons_videos') }}<b>{{ $lessonCounts['videos_total'] }} video</b></div>
                                            <div><span>✓</span>Quiz<b>{{ $lessonCounts['quizzes_total'] }}</b></div>
                                            <div><span>✎</span>{{ trans('bundle_learning.assignments') }}<b>{{ $lessonCounts['assignments_total'] }}</b></div>
                                        </div>

                                        <div class="bl-state-line">
                                            <span>{{ trans('bundle_learning.status_' . ['locked' => 'locked', 'done' => 'completed', 'doing' => 'in_progress', 'todo' => 'not_started'][$lessonStatus]) }}</span>
                                            <span>{{ round($lesson['progress']) }}%</span>
                                        </div>
                                        <div class="bl-bar"><i style="width: {{ $lesson['progress'] }}%"></i></div>

                                        @if($lessonStatus === 'locked')
                                            <button type="button" class="bl-btn" disabled>{{ trans('bundle_learning.locked_btn') }}</button>
                                        @else
                                            <a href="{{ $courseLessonUrl . $lesson['chapter_id'] }}" class="bl-btn bl-btn--primary">
                                                {{ $lessonStatus === 'todo' ? trans('bundle_learning.start') : trans('bundle_learning.continue') }} →
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            @empty
                                <p class="bl-muted bl-small">{{ trans('bundle_learning.no_lessons') }}</p>
                            @endforelse
                        </div>
                    </section>
                @empty
                    <section class="bl-card"><p class="bl-muted mb-0">{{ trans('bundle_learning.no_modules') }}</p></section>
                @endforelse

                <p class="bl-card bl-muted d-none js-bl-empty">{{ trans('bundle_learning.no_filter_result') }}</p>

                {{-- ===== Mid-term / Final ===== --}}
                @if(!empty($progress['checkpoints']))
                    <section class="bl-card bl-checkpoints">
                        <h2 class="bl-module__title">{{ trans('bundle_learning.checkpoints') }}</h2>
                        @foreach($progress['checkpoints'] as $checkpoint)
                            @php
                                $checkpointQuiz = $checkpointQuizzes->get($checkpoint['quiz_id']);
                            @endphp
                            <div class="bl-checkpoint">
                                <div>
                                    <b>{{ $checkpoint['title'] ?: trans('bundle_learning.checkpoint_' . $checkpoint['type']) }}</b>
                                    <div class="bl-muted bl-small">{{ trans('bundle_learning.checkpoint_requirement', ['done' => $progress['modules_completed'], 'required' => $checkpoint['required_modules']]) }}</div>
                                </div>
                                @if($checkpoint['passed'])
                                    <span class="bl-done">✓ {{ trans('bundle_learning.status_completed') }}</span>
                                @elseif($checkpoint['unlocked'] and !empty($checkpointQuiz) and !empty($checkpointQuiz->webinar))
                                    <a href="/panel/courses/purchases/learning/{{ $checkpointQuiz->webinar->slug }}?type=quiz&item={{ $checkpointQuiz->id }}" class="bl-btn bl-btn--primary">{{ trans('bundle_learning.start') }} →</a>
                                @else
                                    <span class="bl-lock">🔒 {{ trans('bundle_learning.status_locked') }}</span>
                                @endif
                            </div>
                        @endforeach
                    </section>
                @endif
            </main>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        (function () {
            var filters = document.querySelectorAll('.js-bl-filter');

            function applyFilters() {
                var skill = document.getElementById('blFilterSkill').value;
                var duration = document.getElementById('blFilterDuration').value;
                var status = document.getElementById('blFilterStatus').value;
                var visibleTotal = 0;

                document.querySelectorAll('.js-bl-module').forEach(function (module) {
                    var visibleInModule = 0;

                    module.querySelectorAll('.js-bl-lesson').forEach(function (lesson) {
                        var show = (skill === 'all' || lesson.dataset.skill === skill)
                            && (duration === 'all' || lesson.dataset.duration === duration)
                            && (status === 'all' || lesson.dataset.status === status);

                        lesson.classList.toggle('d-none', !show);
                        if (show) {
                            visibleInModule++;
                        }
                    });

                    module.classList.toggle('d-none', visibleInModule === 0);
                    visibleTotal += visibleInModule;
                });

                var empty = document.querySelector('.js-bl-empty');
                if (empty) {
                    empty.classList.toggle('d-none', visibleTotal > 0);
                }
            }

            filters.forEach(function (select) {
                select.addEventListener('change', applyFilters);
            });
        })();
    </script>
@endpush
