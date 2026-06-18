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

        {{-- Events Calendar --}}
        <div class="mt-16">
            @include('design_1.panel.dashboard.instructor.includes.events_calendar')
        </div>
    </div>
</div>

@else
{{-- ════════════════════════════════════════════════════════════
     ORGANIZATION ADMIN SALES DASHBOARD
══════════════════════════════════════════════════════════════ --}}
@include('design_1.panel.dashboard.instructor.includes.organ_admin_dashboard')
@endif
