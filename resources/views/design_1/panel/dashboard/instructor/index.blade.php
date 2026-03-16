@if($authUser->isTeacher())

{{-- Main row: left 2/3 holds welcome/chart/queues, right 1/3 holds support + messages --}}
<div class="row gx-16">
    {{-- Left block (2/3) --}}
    <div class="col-12 col-xl-8">
        {{-- Welcome bar --}}
        @include('design_1.panel.dashboard.instructor.includes.teacher_welcome_bar')

        {{-- Stacked bar chart --}}
        @include('design_1.panel.dashboard.instructor.includes.grading_chart')

        {{-- Speaking + Writing queues --}}
        <div class="row gx-16">
            <div class="col-12 col-md-6">
                @include('design_1.panel.dashboard.instructor.includes.speaking_queue')
            </div>
            <div class="col-12 col-md-6">
                @include('design_1.panel.dashboard.instructor.includes.writing_queue')
            </div>
        </div>
    </div>

    {{-- Right block (1/3) --}}
    <div class="col-12 col-xl-4">
        {{-- Students needing support --}}
        @include('design_1.panel.dashboard.instructor.includes.students_needing_support')

        {{-- Messages (support tickets) --}}
        @include('design_1.panel.dashboard.instructor.includes.teacher_messages')
    </div>
</div>

@else
{{-- ════════════════════════════════════════════════════════════
     ORIGINAL ADMIN / ORGANIZATION DASHBOARD
══════════════════════════════════════════════════════════════ --}}
<div class="row">
    <div class="col-12 col-lg-6">
        {{-- Hello Box --}}
        @include('design_1.panel.dashboard.instructor.includes.hello_box')

        {{-- Courses Overview --}}
        <div class="mt-128">
            @include('design_1.panel.dashboard.instructor.includes.courses_overview')
        </div>

        {{-- Sales Overview --}}
        @include('design_1.panel.dashboard.instructor.includes.sales_overview')

        {{-- Pending Student Assignments --}}
        @include('design_1.panel.dashboard.instructor.includes.pending_student_assignments')

    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        {{-- Registration Plan --}}
        @include('design_1.panel.dashboard.instructor.includes.registration_plan')

        {{-- Current Balance (No different with Student Dashboard) --}}
        @include('design_1.panel.dashboard.student.includes.current_balance')

        {{-- Noticeboard (No different with Student Dashboard) --}}
        @include('design_1.panel.dashboard.student.includes.noticeboard')

        {{-- Support Messages --}}
        @include('design_1.panel.dashboard.instructor.includes.support_messages')

        {{-- Visitors Statistics --}}
        @include('design_1.panel.dashboard.instructor.includes.visitors_statistics')

    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        {{-- Events Calendar  (No different with Student Dashboard) --}}
        @include('design_1.panel.dashboard.student.includes.events_calendar')

        {{-- Organization --}}

        {{-- Top Instructors --}}
        @include('design_1.panel.dashboard.instructor.includes.top_instructors')

        {{-- Top Students --}}
        @include('design_1.panel.dashboard.instructor.includes.top_students')

    </div>
</div>
@endif
