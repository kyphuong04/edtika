<div class="panel-section-card py-20 px-25 mt-20">
    <div class="table-responsive">
        <table class="table text-center custom-table">
            <thead>
                <tr>
                    <th class="text-left">{{ trans('public.student') }}</th>
                    <th>{{ trans('public.email') }}</th>
                    <th>{{ trans('public.courses') }}</th>
                    <th>{{ trans('public.progress') }}</th>
                    <th>{{ trans('public.quiz_grade') }}</th>
                    <th>{{ trans('public.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td class="text-left">
                        <div class="user-inline-avatar d-flex align-items-center">
                            <div class="avatar bg-gray200">
                                <img src="{{ $student['avatar'] }}" class="img-cover" alt="{{ $student['full_name'] }}">
                            </div>
                            <div class="ml-10">
                                <span class="font-weight-500">{{ $student['full_name'] }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <span class="text-gray">{{ $student['email'] }}</span>
                    </td>
                    <td class="align-middle">
                        <span class="font-weight-500">{{ $student['courses_enrolled'] }}</span> 
                        <small class="text-gray">enrolled</small>
                        <br>
                        <span class="text-success font-weight-500">{{ $student['courses_completed'] }}</span>
                        <small class="text-gray">completed</small>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex flex-column align-items-center">
                            <div class="progress" style="width: 100px; height: 10px;">
                                <div class="progress-bar bg-primary" 
                                     role="progressbar" 
                                     style="width: {{ $student['average_progress'] }}%" 
                                     aria-valuenow="{{ $student['average_progress'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="mt-5 font-weight-500">{{ $student['average_progress'] }}%</small>
                        </div>
                    </td>
                    <td class="align-middle">
                        <span class="font-weight-500">{{ $student['average_quiz_grade'] }}%</span>
                        <br>
                        <small class="text-gray">({{ $student['total_quiz_results'] }} {{ trans('public.quizzes') }})</small>
                    </td>
                    <td class="align-middle text-right">
                        <a href="/panel/students-tracking/{{ $student['id'] }}/activity"
                           class="btn btn-sm btn-outline-primary mr-6">
                            <i class="fa fa-history"></i>
                            <span class="d-none d-md-inline ml-5">Activity</span>
                        </a>
                        <a href="/panel/students-tracking/{{ $student['id'] }}/details" 
                           class="btn btn-sm btn-primary">
                            <i class="fa fa-eye"></i>
                            <span class="d-none d-md-inline ml-5">{{ trans('public.view') }}</span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
