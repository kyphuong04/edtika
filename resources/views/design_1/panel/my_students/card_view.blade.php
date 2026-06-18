<div class="row mt-20">
    @foreach($students as $student)
    <div class="col-12 col-md-6 col-lg-4 mt-20">
        <div class="webinar-card student-card panel-section-card p-15">
            {{-- Student Header --}}
            <div class="d-flex align-items-center mb-15">
                <div class="webinar-card-image image-cover">
                    <img src="{{ $student['avatar'] }}" class="rounded-circle" alt="{{ $student['full_name'] }}" width="50" height="50">
                </div>
                <div class="ml-10 flex-grow-1">
                    <h3 class="card-title font-16 font-weight-bold">
                        {{ $student['full_name'] }}
                    </h3>
                    <span class="d-block font-12 text-gray mt-5">{{ $student['email'] }}</span>
                </div>
            </div>
            
            {{-- Student Stats --}}
            <div class="student-stats">
                <div class="d-flex align-items-center justify-content-between mt-15 pb-10 border-bottom">
                    <span class="text-gray font-14">
                        <i class="fa fa-book-open text-gray"></i>
                        {{ trans('public.courses') }}
                    </span>
                    <span class="font-weight-500">
                        {{ $student['courses_enrolled'] }}  enrolled / {{ $student['courses_completed'] }} completed
                    </span>
                </div>
                
                <div class="mt-15 pb-10 border-bottom">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <span class="text-gray font-14">
                            <i class="fa fa-chart-line text-gray"></i>
                            {{ trans('public.average_progress') }}
                        </span>
                        <span class="font-weight-500">{{ $student['average_progress'] }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary" 
                             role="progressbar" 
                             style="width: {{ $student['average_progress'] }}%" 
                             aria-valuenow="{{ $student['average_progress'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-between mt-15">
                    <span class="text-gray font-14">
                        <i class="fa fa-check-circle text-gray"></i>
                        {{ trans('public.quiz_results') }}
                    </span>
                    <span class="font-weight-500">
                        {{ $student['average_quiz_grade'] }}% 
                        <small class="text-gray">({{ $student['total_quiz_results'] }} {{ trans('public.quizzes') }})</small>
                    </span>
                </div>
            </div>
            
            {{-- Actions --}}
            <div class="mt-20 d-flex align-items-center gap-8">
                <a href="/panel/students-tracking/{{ $student['id'] }}/activity"
                   class="btn btn-sm btn-outline-primary flex-1">
                    <i class="fa fa-history"></i> Activity
                </a>
                <a href="/panel/students-tracking/{{ $student['id'] }}/details" 
                   class="btn btn-sm btn-primary flex-1">
                    <i class="fa fa-eye"></i> {{ trans('public.view_details') }}
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
