{{-- ──────────────────────────────────────────────────────────────────────────
     IELTS Student Dashboard
     ──────────────────────────────────────────────────────────────────────── --}}
<div class="ielts-dashboard">

    {{-- ── TOP ROW: Welcome bar (8/12) + Profile card (4/12) ──────────── --}}
    <div class="row gx-16">
        <div class="col-12 col-xl-8">
            @include('design_1.panel.dashboard.student.includes.welcome_topbar')
        </div>
        <div class="col-12 col-xl-4 mt-16 mt-xl-0">
            @include('design_1.panel.dashboard.student.includes.right_profile')
        </div>
    </div>

    {{-- ── MAIN ROW: Left content (8/12) + Right sidebar (4/12) ───────── --}}
    <div class="row gx-16 mt-16">

        {{-- ── LEFT COLUMN ─────────────────────────────────────────────── --}}
        <div class="col-12 col-xl-8">

            {{-- Stacked Bar Chart: skill activity last 7 days --}}
            @include('design_1.panel.dashboard.student.includes.skill_activity_chart')

            {{-- Skill Progress Circles + Weak Point --}}
            <div class="row gx-16 mt-16">
                <div class="col-12 col-lg-5">
                    @include('design_1.panel.dashboard.student.includes.skill_progress')
                </div>
                <div class="col-12 col-lg-7 mt-16 mt-lg-0">
                    @include('design_1.panel.dashboard.student.includes.weak_point')
                </div>
            </div>

            {{-- Word of the Day + Mentor Box --}}
            <div class="row gx-16 mt-16">
                <div class="col-12 col-md-5">
                    @include('design_1.panel.dashboard.student.includes.word_of_day')
                </div>
                <div class="col-12 col-md-7 mt-16 mt-md-0">
                    @include('design_1.panel.dashboard.student.includes.mentor_box')
                </div>
            </div>

        </div>

        {{-- ── RIGHT SIDEBAR ────────────────────────────────────────────── --}}
        <div class="col-12 col-xl-4 mt-16 mt-xl-0">

            @include('design_1.panel.dashboard.student.includes.countdown_aim')
            @include('design_1.panel.dashboard.student.includes.streak_ranking')
            @include('design_1.panel.dashboard.student.includes.radar_chart')
            @include('design_1.panel.dashboard.student.includes.top_students_widget')

        </div>

    </div>
</div>
