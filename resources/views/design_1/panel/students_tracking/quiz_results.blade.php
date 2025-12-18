@extends('design_1.panel.layouts.panel')

@section('content')
    <section>
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('panel.quiz_results') }} - {{ $student->full_name }}</h2>
            <a href="javascript:history.back()" class="btn btn-sm btn-primary">
                <i class="fa fa-arrow-left mr-2"></i>{{ trans('panel.back') }}
            </a>
        </div>

        @if($quizResults->count() > 0)
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="table-responsive">
                    <table class="table custom-table text-center">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('panel.quiz') }}</th>
                                <th>{{ trans('panel.course') }}</th>
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
                                    <td>{{ $result->quiz->webinar->title ?? 'N/A' }}</td>
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
                                        <div class="btn-group">
                                            <a href="/panel/quizzes/results/{{ $result->id }}/details" 
                                               class="btn btn-sm btn-primary">
                                                {{ trans('panel.view') }}
                                            </a>
                                            @if($result->status == 'waiting')
                                                <a href="/panel/quizzes/results/{{ $result->id }}/edit" 
                                                   class="btn btn-sm btn-warning ml-2">
                                                    {{ trans('panel.review') }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-30">
                    {{ $quizResults->links() }}
                </div>
            </div>
        @else
            @include('design_1.panel.includes.no-result',[
                'file_name' => 'quiz.svg',
                'title' => trans('panel.no_quiz_results_found'),
            ])
        @endif
    </section>
@endsection
