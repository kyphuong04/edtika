@extends('design_1.panel.layouts.panel')

@section('content')
    <section>
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('panel.student_details') }}</h2>
            <a href="/panel/my-students" class="btn btn-sm btn-primary">
                <i class="fa fa-arrow-left mr-2"></i>{{ trans('panel.back_to_list') }}
            </a>
        </div>

        {{-- Student Info Card --}}
        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-12 col-md-3 text-center">
                    <div class="avatar-lg mx-auto">
                        <img src="{{ $student->avatar ?? '/assets/default/img/user/avatar_default.png' }}" 
                             class="img-cover rounded-circle" 
                             alt="{{ $student->full_name }}">
                    </div>
                    <h3 class="mt-15 font-weight-bold">{{ $student->full_name }}</h3>
                    <p class="text-gray">{{ $student->email }}</p>
                    @if(!empty($student->bio))
                        <p class="text-gray font-14 mt-10">{{ $student->bio }}</p>
                    @endif
                </div>

                <div class="col-12 col-md-9 mt-20 mt-md-0">
                    <div class="row">
                        <div class="col-6 col-lg-3 mb-20">
                            <div class="d-flex flex-column">
                                <span class="font-weight-500 text-secondary font-14">{{ trans('panel.enrolled_courses') }}</span>
                                <span class="font-30 font-weight-bold text-dark-blue mt-5">{{ count($coursesData) }}</span>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 mb-20">
                            <div class="d-flex flex-column">
                                <span class="font-weight-500 text-secondary font-14">{{ trans('panel.completed_courses') }}</span>
                                <span class="font-30 font-weight-bold text-success mt-5">
                                    {{ collect($coursesData)->where('progress', '>=', 100)->count() }}
                                </span>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 mb-20">
                            <div class="d-flex flex-column">
                                <span class="font-weight-500 text-secondary font-14">{{ trans('panel.average_progress') }}</span>
                                <span class="font-30 font-weight-bold text-primary mt-5">
                                    {{ count($coursesData) > 0 ? number_format(collect($coursesData)->avg('progress'), 1) : 0 }}%
                                </span>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3 mb-20">
                            <div class="d-flex flex-column">
                                <span class="font-weight-500 text-secondary font-14">{{ trans('panel.average_quiz_grade') }}</span>
                                <span class="font-30 font-weight-bold text-warning mt-5">
                                    {{ count($coursesData) > 0 ? number_format(collect($coursesData)->avg('average_grade'), 1) : 0 }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-20">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#supportMessageModal">
                                <i class="fa fa-envelope mr-2"></i>{{ trans('panel.send_support_message') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enrolled Courses --}}
        <div class="mt-30">
            <h3 class="section-title">{{ trans('panel.enrolled_courses') }}</h3>
            
            @if(!empty($coursesData) && count($coursesData) > 0)
                <div class="panel-section-card py-20 px-25 mt-20">
                    <div class="row">
                        @foreach($coursesData as $courseData)
                            <div class="col-12 col-lg-6 mb-20">
                                <div class="webinar-card">
                                    <div class="webinar-card-body p-15">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div class="flex-grow-1">
                                                <h4 class="font-16 font-weight-bold text-dark-blue">
                                                    {{ $courseData['webinar']->title }}
                                                </h4>
                                                <div class="mt-10">
                                                    <div class="d-flex align-items-center justify-content-between mb-10">
                                                        <span class="font-14 text-gray">{{ trans('panel.progress') }}</span>
                                                        <span class="font-14 font-weight-500">{{ number_format($courseData['progress'], 1) }}%</span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" 
                                                             style="width: {{ $courseData['progress'] }}%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-15 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="font-12 text-gray">{{ trans('panel.quizzes') }}: </span>
                                                        <span class="font-12 font-weight-500">{{ $courseData['quiz_count'] }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-12 text-gray">{{ trans('panel.avg_grade') }}: </span>
                                                        <span class="font-12 font-weight-500">{{ number_format($courseData['average_grade'], 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-12 text-gray">{{ trans('panel.enrolled') }}: </span>
                                                        <span class="font-12 font-weight-500">{{ dateTimeFormat($courseData['enrolled_at'], 'j M Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-15">
                                            <a href="/panel/students-tracking/{{ $student->id }}/progress/{{ $courseData['webinar']->id }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                {{ trans('panel.view_detailed_progress') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                @include('design_1.panel.includes.no-result',[
                    'file_name' => 'course_list.svg',
                    'title' => trans('panel.no_courses_found'),
                ])
            @endif
        </div>

        {{-- Recent Quiz Results --}}
        @if(!empty($recentQuizResults) && $recentQuizResults->count() > 0)
            <div class="mt-30">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="section-title">{{ trans('panel.recent_quiz_results') }}</h3>
                    <a href="/panel/students-tracking/{{ $student->id }}/quiz-results" class="btn btn-sm btn-outline-primary">
                        {{ trans('panel.view_all') }}
                    </a>
                </div>

                <div class="panel-section-card py-20 px-25 mt-20">
                    <div class="table-responsive">
                        <table class="table custom-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ trans('panel.quiz') }}</th>
                                    <th>{{ trans('panel.course') }}</th>
                                    <th>{{ trans('panel.grade') }}</th>
                                    <th>{{ trans('panel.status') }}</th>
                                    <th>{{ trans('panel.date') }}</th>
                                    <th>{{ trans('public.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentQuizResults as $result)
                                    <tr>
                                        <td class="text-left">{{ $result->quiz->title }}</td>
                                        <td>{{ $result->quiz->webinar->title ?? 'N/A' }}</td>
                                        <td>
                                            <span class="font-weight-bold">{{ $result->user_grade }}</span>
                                        </td>
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Support Tickets --}}
        @if(!empty($supportTickets) && $supportTickets->count() > 0)
            <div class="mt-30">
                <h3 class="section-title">{{ trans('panel.recent_support_tickets') }}</h3>

                <div class="panel-section-card py-20 px-25 mt-20">
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>{{ trans('panel.title') }}</th>
                                    <th>{{ trans('panel.course') }}</th>
                                    <th>{{ trans('panel.status') }}</th>
                                    <th>{{ trans('panel.date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supportTickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->title }}</td>
                                        <td>{{ $ticket->webinar->title ?? 'N/A' }}</td>
                                        <td>
                                            @if($ticket->status == 'open')
                                                <span class="badge badge-primary">{{ trans('panel.open') }}</span>
                                            @elseif($ticket->status == 'close')
                                                <span class="badge badge-secondary">{{ trans('panel.closed') }}</span>
                                            @else
                                                <span class="badge badge-info">{{ trans('panel.supporter_replied') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ dateTimeFormat($ticket->created_at, 'j M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- Support Message Modal --}}
    <div class="modal fade" id="supportMessageModal" tabindex="-1" role="dialog" aria-labelledby="supportMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title font-weight-bold" id="supportMessageModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 8px;">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        {{ trans('panel.send_support_message') }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="supportMessageForm">
                    <div class="modal-body" style="padding: 30px;">
                        <div class="alert alert-info" style="border-left: 4px solid #17a2b8;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            {{ trans('panel.support_message_info') }}
                        </div>
                        
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                                {{ trans('panel.select_course') }}
                            </label>
                            <select name="webinar_id" class="form-control" style="border-radius: 8px; padding: 10px;" required>
                                <option value="">{{ trans('panel.select_course') }}</option>
                                @foreach($coursesData as $courseData)
                                    <option value="{{ $courseData['webinar']->id }}">{{ $courseData['webinar']->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                {{ trans('panel.message') }}
                            </label>
                            <textarea name="message" class="form-control" rows="6" style="border-radius: 8px; padding: 12px;" placeholder="{{ trans('panel.type_your_message_here') }}" required></textarea>
                            <small class="text-muted">{{ trans('panel.min_characters', ['count' => 10]) }}</small>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 20px 30px;">
                        <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 8px; padding: 10px 20px;">
                            {{ trans('panel.close') }}
                        </button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 10px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                            {{ trans('panel.send') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        $(document).ready(function() {
            $('#supportMessageForm').on('submit', function(e) {
                e.preventDefault();
                
                console.log('Form submitted'); // Debug
                
                var form = $(this);
                var submitBtn = form.find('button[type="submit"]');
                var originalBtnText = submitBtn.html();
                var message = form.find('textarea[name="message"]').val().trim();
                var webinarId = form.find('select[name="webinar_id"]').val();
                
                console.log('Message:', message); // Debug
                console.log('Webinar ID:', webinarId); // Debug
                
                // Validate course selection
                if(!webinarId) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ trans('panel.validation_error') }}',
                        text: '{{ trans('panel.please_select_course') }}'
                    });
                    return;
                }
                
                // Validate message length
                if(message.length < 10) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ trans('panel.validation_error') }}',
                        text: '{{ trans('panel.message_too_short') }}'
                    });
                    return;
                }
                
                // Disable button and show loading
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>{{ trans('panel.sending') }}...'
                );
                
                var formData = {
                    webinar_id: webinarId,
                    message: message,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                
                console.log('Sending data:', formData); // Debug
                
                $.ajax({
                    url: '/panel/students-tracking/{{ $student->id }}/support-message',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log('Success response:', response); // Debug
                        
                        if(response.success) {
                            $('#supportMessageModal').modal('hide');
                            form[0].reset();
                            
                            Swal.fire({
                                icon: 'success',
                                title: '{{ trans('panel.success') }}',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ trans('panel.error') }}',
                                text: response.message || '{{ trans('panel.error_occurred') }}'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.responseText); // Debug
                        console.error('Status:', status); // Debug
                        console.error('Error:', error); // Debug
                        
                        var errorMsg = '{{ trans('panel.error_occurred') }}';
                        
                        if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            // Validation errors
                            var errors = xhr.responseJSON.errors;
                            errorMsg = Object.values(errors).flat().join('<br>');
                        } else if(xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if(xhr.status === 403) {
                            errorMsg = '{{ trans('panel.access_denied') }}';
                        } else if(xhr.status === 500) {
                            errorMsg = '{{ trans('panel.server_error') }}';
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: '{{ trans('panel.error') }}',
                            html: errorMsg
                        });
                    },
                    complete: function() {
                        // Re-enable button
                        submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });
            
            // Reset form when modal is closed
            $('#supportMessageModal').on('hidden.bs.modal', function() {
                $('#supportMessageForm')[0].reset();
            });
        });
    </script>
@endpush
