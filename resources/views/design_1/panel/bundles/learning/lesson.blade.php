@extends('design_1.panel.layouts.panel')

{{-- GĐ3: tổng quan một lesson --}}

@push('styles_top')
    @include('design_1.panel.bundles.learning.partials.styles')
@endpush

@section('content')
    @php
        $counts = $lesson['counts'];
        $avgVideoSeconds = $counts['videos_total'] > 0 ? (int)round($counts['video_seconds'] / $counts['videos_total']) : 0;
        $assignmentsPassed = $counts['assignments_total'] > 0 && $counts['assignments_done'] >= $counts['assignments_total'];
        $firstUndoneItem = $chapterItems->firstWhere('done', false) ?? $chapterItems->first();
        $startUrl = !empty($firstUndoneItem)
            ? $learningUrl . '?type=' . $firstUndoneItem['type'] . '&item=' . $firstUndoneItem['id']
            : $learningUrl;
        $typeLabels = [
            'file' => 'Video',
            'session' => trans('bundle_learning.type_session'),
            'text_lesson' => trans('bundle_learning.type_text_lesson'),
            'quiz' => 'Quiz',
            'assignment' => trans('bundle_learning.type_assignment'),
        ];
    @endphp

    <div class="bl-page">
        <div class="bl-crumb mb-12">
            <a href="/panel/courses/purchases">{{ trans('bundle_learning.my_courses') }}</a>
            <span>›</span>
            <a href="{{ $courseUrl }}">{{ $bundle->title }}</a>
            <span>›</span>
            <b>{{ $chapter->title }}</b>
        </div>

        <div class="bl-overview">
            <section class="bl-card">
                <span class="bl-tag bl-tag--outline">{{ trans('bundle_learning.lesson_overview') }} · {{ trans('bundle_learning.module') }} {{ $module['order_index'] + 1 }}</span>
                <h1 class="bl-title">{{ $chapter->title }}</h1>
                <p class="bl-muted mb-0">{{ $module['title'] }}</p>

                <div class="bl-pills">
                    <span class="bl-tag">{{ strtoupper($bundle->exam_type ?: 'ielts') }}</span>
                    @if(!empty($module['skill']))
                        <span class="bl-tag bl-tag--outline">{{ trans('bundle_learning.skill_' . $module['skill']) }}</span>
                    @endif
                    @if($avgVideoSeconds > 0)
                        <span class="bl-tag bl-tag--outline">{{ trans('bundle_learning.minutes_per_video', ['minutes' => max(1, round($avgVideoSeconds / 60))]) }}</span>
                    @endif
                    <span class="bl-tag bl-tag--outline">{{ $counts['videos_total'] }} video</span>
                    <span class="bl-tag bl-tag--outline">{{ $counts['quizzes_total'] }} quiz</span>
                </div>

                {{-- Pre Lesson: Word List --}}
                @if($words->isNotEmpty())
                    <h2 class="bl-module__title mt-24">Pre Lesson · Word List</h2>
                    <p class="bl-muted bl-small">{{ trans('bundle_learning.pre_lesson_hint') }}</p>
                    <div class="table-responsive">
                        <table class="bl-words">
                            <thead>
                            <tr>
                                <th>{{ trans('bundle_learning.word') }}</th>
                                <th>{{ trans('bundle_learning.meaning') }}</th>
                                <th>{{ trans('bundle_learning.example') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($words as $word)
                                <tr>
                                    <td>
                                        <b>{{ $word->word }}</b>
                                        @if(!empty($word->part_of_speech)) <span class="bl-muted bl-small">({{ $word->part_of_speech }})</span> @endif
                                        @if(!empty($word->pronunciation)) <div class="bl-muted bl-small">{{ $word->pronunciation }}</div> @endif
                                    </td>
                                    <td>{{ $word->translation_vi ?: $word->definition }}</td>
                                    <td class="bl-muted">{{ $word->example }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="/panel/dictionary" class="bl-link bl-small d-inline-block mt-8">{{ trans('bundle_learning.practice_in_dictionary') }} →</a>
                @endif

                {{-- Nội dung lesson --}}
                <h2 class="bl-module__title mt-24">{{ trans('bundle_learning.lesson_content') }}</h2>
                @if($chapterItems->isEmpty())
                    <p class="bl-muted">{{ trans('bundle_learning.no_lessons') }}</p>
                @else
                    <ul class="bl-items mt-8">
                        @foreach($chapterItems as $item)
                            <li>
                                <div>
                                    <div class="bl-item__type">
                                        {{ $item['is_mini_quiz'] ? 'Mini Quiz' : ($typeLabels[$item['type']] ?? $item['type']) }}
                                    </div>
                                    <a href="{{ $learningUrl }}?type={{ $item['type'] }}&item={{ $item['id'] }}" class="font-weight-bold text-dark">{{ $item['title'] }}</a>
                                </div>
                                @if($item['done'])
                                    <span class="bl-done">✓</span>
                                @else
                                    <span class="bl-muted bl-small">{{ trans('bundle_learning.status_not_started') }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <aside class="bl-card align-self-start">
                <h3 class="font-16 font-weight-bold mb-0">{{ trans('bundle_learning.lesson_information') }}</h3>
                <div class="bl-quickstats">
                    <span>{{ trans('bundle_learning.module') }}<b>{{ $module['order_index'] + 1 }}/{{ $progress['modules_total'] }}</b></span>
                    <span>{{ trans('bundle_learning.videos_watched') }}<b>{{ $counts['videos_done'] }}/{{ $counts['videos_total'] }}</b></span>
                    <span>Post Lesson<b>{{ $counts['assignments_done'] }}/{{ $counts['assignments_total'] }}</b></span>
                    <span>{{ trans('bundle_learning.result') }}<b>{{ $counts['assignments_total'] > 0 ? ($assignmentsPassed ? trans('bundle_learning.passed') : trans('bundle_learning.not_passed')) : '-' }}</b></span>
                </div>

                <div class="bl-progress-row">
                    <div class="bl-bar"><i style="width: {{ $lesson['progress'] }}%"></i></div>
                    <span class="bl-progress-num">{{ round($lesson['progress']) }}%</span>
                </div>

                <a href="{{ $startUrl }}" class="bl-btn bl-btn--primary w-100">{{ $lesson['started'] ? trans('bundle_learning.continue') : trans('bundle_learning.start') }} →</a>
                <a href="{{ $courseUrl }}" class="bl-btn w-100">← {{ trans('bundle_learning.back_to_course') }}</a>
            </aside>
        </div>
    </div>
@endsection
