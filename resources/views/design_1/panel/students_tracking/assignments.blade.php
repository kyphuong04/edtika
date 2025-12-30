@extends('design_1.panel.layouts.panel')

@section('content')
    <section>
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('panel.assignments') }} - {{ $student->full_name }}</h2>
            <a href="javascript:history.back()" class="btn btn-sm btn-primary">
                <i class="fa fa-arrow-left mr-2"></i>{{ trans('panel.back') }}
            </a>
        </div>

        @if($assignmentHistories->count() > 0)
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="table-responsive">
                    <table class="table custom-table text-center">
                        <thead>
                            <tr>
                                <th class="text-left">{{ trans('panel.assignment') }}</th>
                                <th>{{ trans('panel.course') }}</th>
                                <th>{{ trans('panel.deadline') }}</th>
                                <th>{{ trans('panel.grade') }}</th>
                                <th>{{ trans('panel.status') }}</th>
                                <th>{{ trans('panel.submission_date') }}</th>
                                <th>{{ trans('public.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignmentHistories as $history)
                                <tr>
                                    <td class="text-left">{{ $history->assignment->title }}</td>
                                    <td>{{ $history->assignment->webinar->title ?? 'N/A' }}</td>
                                    <td>{{ dateTimeFormat($history->assignment->deadline, 'j M Y') }}</td>
                                    <td>
                                        @if($history->grade !== null)
                                            <span class="font-weight-bold">{{ $history->grade }}</span>
                                            <span class="text-gray">/ {{ $history->assignment->total_mark ?? 100 }}</span>
                                        @else
                                            <span class="text-gray">{{ trans('panel.not_graded') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($history->status == 'passed')
                                            <span class="badge badge-success">{{ trans('panel.passed') }}</span>
                                        @elseif($history->status == 'pending')
                                            <span class="badge badge-warning">{{ trans('panel.pending') }}</span>
                                        @elseif($history->status == 'not_passed')
                                            <span class="badge badge-danger">{{ trans('panel.not_passed') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $history->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ dateTimeFormat($history->created_at, 'j M Y H:i') }}</td>
                                    <td>
                                        <a href="/panel/assignments/{{ $history->assignment_id }}/students" 
                                           class="btn btn-sm btn-primary">
                                            {{ trans('panel.view') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-30">
                    {{ $assignmentHistories->links() }}
                </div>
            </div>
        @else
            @include('design_1.panel.includes.no-result',[
                'file_name' => 'assignment.svg',
                'title' => trans('panel.no_assignments_found'),
            ])
        @endif
    </section>
@endsection
