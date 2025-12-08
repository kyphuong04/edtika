@extends('design_1.panel.layouts.panel')

@section('content')
    <section>
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('panel.course_progress') }}</h2>
            <a href="/panel/students-tracking/{{ $student->id }}/details" class="btn btn-sm btn-primary">
                <i class="fa fa-arrow-left mr-2"></i>{{ trans('panel.back') }}
            </a>
        </div>

        {{-- Course Info --}}
        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3 class="font-20 font-weight-bold text-dark-blue">{{ $webinar->title }}</h3>
                    <div class="mt-10">
                        <span class="text-gray font-14">{{ trans('panel.student') }}: </span>
                        <span class="font-weight-500">{{ $student->full_name }}</span>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-right mt-20 mt-md-0">
                    <div class="d-flex flex-column">
                        <span class="text-gray font-14">{{ trans('panel.overall_progress') }}</span>
                        <span class="font-30 font-weight-bold text-primary">{{ number_format($progress, 1) }}%</span>
                    </div>
                    <div class="progress mt-10">
                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Course Content Progress --}}
        <div class="mt-30">
            <h3 class="section-title">{{ trans('panel.content_progress') }}</h3>
            
            @if(!empty($allItems) && count($allItems) > 0)
                <div class="panel-section-card py-20 px-25 mt-20">
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ trans('panel.chapter') }}</th>
                                    <th class="text-left">{{ trans('panel.content') }}</th>
                                    <th class="text-center">{{ trans('panel.type') }}</th>
                                    <th class="text-center">{{ trans('panel.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allItems as $item)
                                    <tr>
                                        <td class="text-left">{{ $item['chapter'] }}</td>
                                        <td class="text-left">{{ $item['title'] }}</td>
                                        <td class="text-center">
                                            @php
                                                $typeIcon = 'fa-file';
                                                $typeLabel = ucfirst($item['type']);
                                                
                                                switch($item['type']) {
                                                    case 'file':
                                                        $typeIcon = 'fa-file-alt';
                                                        break;
                                                    case 'session':
                                                        $typeIcon = 'fa-video';
                                                        break;
                                                    case 'text_lesson':
                                                        $typeIcon = 'fa-book';
                                                        break;
                                                    case 'quiz':
                                                        $typeIcon = 'fa-question-circle';
                                                        break;
                                                    case 'assignment':
                                                        $typeIcon = 'fa-tasks';
                                                        break;
                                                }
                                            @endphp
                                            <i class="fa {{ $typeIcon }} mr-2"></i>{{ $typeLabel }}
                                        </td>
                                        <td class="text-center">
                                            @if($item['completed'])
                                                <span class="badge badge-success">
                                                    <i class="fa fa-check mr-1"></i>{{ trans('panel.completed') }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ trans('panel.not_completed') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                @include('design_1.panel.includes.no-result',[
                    'file_name' => 'content.svg',
                    'title' => trans('panel.no_content_found'),
                ])
            @endif
        </div>

        {{-- Quiz Results --}}
        @if(!empty($quizResults) && $quizResults->count() > 0)
            <div class="mt-30">
                <h3 class="section-title">{{ trans('panel.quiz_results') }}</h3>
                
                <div class="panel-section-card py-20 px-25 mt-20">
                    <div class="table-responsive">
                        <table class="table custom-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ trans('panel.quiz') }}</th>
                                    <th>{{ trans('panel.grade') }}</th>
                                    <th>{{ trans('panel.pass_mark') }}</th>
                                    <th>{{ trans('panel.status') }}</th>
                                    <th>{{ trans('panel.date') }}</th>
                                    <th>{{ trans('public.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quizResults as $result)
                                    <tr>
                                        <td class="text-left">{{ $result->quiz->title }}</td>
                                        <td>
                                            <span class="font-weight-bold">{{ $result->user_grade }}</span>
                                            <span class="text-gray">/ {{ $result->quiz->total_mark ?? 100 }}</span>
                                        </td>
                                        <td>{{ $result->quiz->pass_mark ?? 'N/A' }}</td>
                                        <td>
                                            @if($result->status == 'passed')
                                                <span class="badge badge-success">{{ trans('quiz.passed') }}</span>
                                            @elseif($result->status == 'failed')
                                                <span class="badge badge-danger">{{ trans('quiz.failed') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ trans('quiz.waiting') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ dateTimeFormat($result->created_at, 'j M Y H:i') }}</td>
                                        <td>
                                            <a href="/panel/quizzes/results/{{ $result->id }}/details" 
                                               class="btn btn-sm btn-primary">
                                                {{ trans('panel.view') }}
                                            </a>
                                            @if($result->status == 'waiting')
                                                <a href="/panel/quizzes/results/{{ $result->id }}/edit" 
                                                   class="btn btn-sm btn-warning">
                                                    {{ trans('panel.review') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Assignments --}}
        @if(!empty($assignments) && $assignments->count() > 0)
            <div class="mt-30">
                <h3 class="section-title">{{ trans('panel.assignments') }}</h3>
                
                <div class="panel-section-card py-20 px-25 mt-20">
                    <div class="table-responsive">
                        <table class="table custom-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ trans('panel.assignment') }}</th>
                                    <th>{{ trans('panel.deadline') }}</th>
                                    <th>{{ trans('panel.grade') }}</th>
                                    <th>{{ trans('panel.status') }}</th>
                                    <th>{{ trans('panel.submission_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                    @php
                                        $submission = $assignment->histories->first();
                                    @endphp
                                    <tr>
                                        <td class="text-left">{{ $assignment->title }}</td>
                                        <td>{{ dateTimeFormat($assignment->deadline, 'j M Y') }}</td>
                                        <td>
                                            @if($submission && $submission->grade !== null)
                                                <span class="font-weight-bold">{{ $submission->grade }}</span>
                                                <span class="text-gray">/ {{ $assignment->total_mark ?? 100 }}</span>
                                            @else
                                                <span class="text-gray">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission)
                                                @if($submission->status == 'passed')
                                                    <span class="badge badge-success">{{ trans('panel.passed') }}</span>
                                                @elseif($submission->status == 'pending')
                                                    <span class="badge badge-warning">{{ trans('panel.pending') }}</span>
                                                @elseif($submission->status == 'not_passed')
                                                    <span class="badge badge-danger">{{ trans('panel.not_passed') }}</span>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary">{{ trans('panel.not_submitted') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission)
                                                {{ dateTimeFormat($submission->created_at, 'j M Y H:i') }}
                                            @else
                                                <span class="text-gray">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
