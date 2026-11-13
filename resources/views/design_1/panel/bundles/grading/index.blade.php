@extends('design_1.panel.layouts.panel')

{{-- GĐ4: danh sách bài nộp cần chấm của một khóa học --}}

@push('styles_top')
    @include('design_1.panel.bundles.learning.partials.styles')
    @include('design_1.panel.bundles.grading.partials.styles')
@endpush

@section('content')
    @php
        $statusLabels = [
            'pending' => trans('bundle_learning.grading_status_pending'),
            'passed' => trans('bundle_learning.passed'),
            'not_passed' => trans('bundle_learning.not_passed'),
            'all' => trans('bundle_learning.all'),
        ];
        $totalCount = collect($statusCounts)->sum();
        $baseQuery = array_filter(['module' => $moduleId ?: null, 'lesson' => $chapterId ?: null, 'search' => $search ?: null]);
    @endphp

    <div class="bl-page">
        <div class="bl-crumb mb-12">
            <a href="/panel/bundles">{{ trans('update.bundles') }}</a>
            <span>›</span>
            <a href="/panel/bundles/{{ $bundle->id }}/modules">{{ $bundle->title }}</a>
            <span>›</span>
            <b>{{ trans('bundle_learning.grading_title') }}</b>
        </div>

        <section class="bl-card">
            <h1 class="bl-title mb-4">{{ trans('bundle_learning.grading_title') }}</h1>
            <p class="bl-muted mb-0">{{ trans('bundle_learning.grading_hint') }}</p>

            {{-- Tab trạng thái --}}
            <div class="bg-tabs mt-16">
                @foreach($statusLabels as $statusKey => $statusLabel)
                    @php
                        $count = $statusKey === 'all' ? $totalCount : (int)($statusCounts[$statusKey] ?? 0);
                    @endphp
                    <a href="?{{ http_build_query($baseQuery + ['status' => $statusKey]) }}" class="bg-tab {{ $status === $statusKey ? 'is-active' : '' }}">
                        {{ $statusLabel }} <span class="bg-tab__count">{{ $count }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Bộ lọc --}}
            <form method="get" class="bl-filters mt-16 mb-0">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="bl-filter">
                    <label for="gradingModule">{{ trans('bundle_learning.module') }}</label>
                    <select id="gradingModule" name="module" class="form-control" onchange="this.form.lesson.value=''; this.form.submit()">
                        <option value="">{{ trans('bundle_learning.all') }}</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ $moduleId === $module->id ? 'selected' : '' }}>{{ $module->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bl-filter">
                    <label for="gradingLesson">{{ trans('bundle_learning.lesson') }}</label>
                    <select id="gradingLesson" name="lesson" class="form-control" onchange="this.form.submit()">
                        <option value="">{{ trans('bundle_learning.all') }}</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}" {{ $chapterId === $lesson->id ? 'selected' : '' }}>{{ $lesson->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bl-filter">
                    <label for="gradingSearch">{{ trans('bundle_learning.student') }}</label>
                    <input id="gradingSearch" type="text" name="search" value="{{ $search }}" class="form-control" placeholder="{{ trans('bundle_learning.search_student') }}">
                </div>
                <div class="bl-filter d-flex align-items-end" style="flex: 0 0 auto; min-width: 0;">
                    <button type="submit" class="bl-btn bl-btn--primary mt-0">{{ trans('bundle_learning.filter') }}</button>
                </div>
            </form>
        </section>

        <section class="bl-card mt-16 p-0">
            @if($histories->isEmpty())
                <p class="bl-muted p-24 mb-0 text-center">{{ trans('bundle_learning.grading_empty') }}</p>
            @else
                <div class="table-responsive">
                    <table class="bg-table">
                        <thead>
                        <tr>
                            <th>{{ trans('bundle_learning.student') }}</th>
                            <th>{{ trans('bundle_learning.assignment') }}</th>
                            <th>{{ trans('bundle_learning.submitted_at') }}</th>
                            <th>{{ trans('bundle_learning.grade') }}</th>
                            <th>{{ trans('bundle_learning.filter_status') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($histories as $history)
                            @php
                                $assignment = $history->assignment;
                            @endphp
                            <tr>
                                <td>
                                    <b>{{ optional($history->student)->full_name }}</b>
                                    <div class="bl-muted bl-small">{{ optional($history->student)->email }}</div>
                                </td>
                                <td>
                                    <b>{{ optional($assignment)->title }}</b>
                                    <div class="bl-muted bl-small">
                                        {{ optional(optional($assignment)->webinar)->title }}
                                        @if(!empty($assignment) and !empty($assignment->chapter)) · {{ $assignment->chapter->title }} @endif
                                    </div>
                                </td>
                                <td class="bl-small">{{ !empty($history->messages_max_created_at) ? dateTimeFormat($history->messages_max_created_at, 'j M Y H:i') : '-' }}</td>
                                <td>{{ !is_null($history->grade) ? $history->grade . '/' . optional($assignment)->grade : '-' }}</td>
                                <td><span class="bg-status bg-status--{{ $history->status }}">{{ $statusLabels[$history->status] ?? $history->status }}</span></td>
                                <td class="text-right">
                                    <a href="/panel/bundles/{{ $bundle->id }}/grading/{{ $history->id }}" class="bl-btn {{ $history->status === 'pending' ? 'bl-btn--primary' : '' }} mt-0">
                                        {{ $history->status === 'pending' ? trans('bundle_learning.grade_action') : trans('bundle_learning.view') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-16">
                    {{ $histories->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
