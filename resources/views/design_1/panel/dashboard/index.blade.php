@extends("design_1.panel.layouts.panel")

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>
    <style>
        /* ── IELTS Dashboard Styles ───────────────────────────────────── */
        .ielts-dashboard { padding-bottom: 32px; }

        /* Student dashboard primary accent: blue -> purple */
        .student-dashboard,
        .student-dashboard .ielts-dashboard {
            --primary: #511D99;
            --primary-hover: #451884;
        }

        .student-dashboard .text-primary,
        .student-dashboard .text-primary:hover,
        .student-dashboard .text-primary:focus {
            color: #511D99 !important;
        }

        .student-dashboard .bg-primary,
        .student-dashboard .btn-primary,
        .student-dashboard .btn-primary:hover,
        .student-dashboard .btn-primary:focus,
        .student-dashboard .btn-primary:active,
        .student-dashboard .btn-primary:not(:disabled):not(.disabled):active {
            background-color: #511D99 !important;
            border-color: #511D99 !important;
            color: #fff !important;
        }

        .student-dashboard .btn-outline-primary,
        .student-dashboard .btn-outline-primary:hover,
        .student-dashboard .btn-outline-primary:focus,
        .student-dashboard .btn-outline-primary:active {
            border-color: #511D99 !important;
            color: #511D99 !important;
        }

        .student-dashboard .btn-outline-primary:hover,
        .student-dashboard .btn-outline-primary:focus,
        .student-dashboard .btn-outline-primary:active {
            background-color: rgba(81, 29, 153, 0.1) !important;
        }

        .student-dashboard .border-primary {
            border-color: #511D99 !important;
        }

        /* Welcome bar */
        .ielts-welcome-bar { min-height: 64px; }
        .ielts-welcome-bar__bell { text-decoration: none; }
        .ielts-welcome-bar__bell:hover { background: #E5E7EB !important; }

        /* Row gap overrides */
        .gx-16 { --bs-gutter-x: 16px; }

        /* Skill progress circles button hover */
        .ielts-skill-circle-btn { transition: transform .18s; color: inherit; }
        .ielts-skill-circle-btn:hover { transform: translateY(-3px); }
        .ielts-skill-circle-btn:hover span { color: var(--primary) !important; }

        /* Weak-point bars */
        .ielts-weak-bar:hover { opacity: .85; }

        /* Misc card touches */
        .border-left-primary { border-left: 3px solid var(--primary) !important; }
        .border-success-200  { border-color: #A7F3D0 !important; }

        /* Right column spacing */
        .col-xl-4 .bg-white + .bg-white,
        .col-xl-4 .bg-white { word-break: break-word; }

        .font-10 { font-size: 10px !important; }
        .font-11 { font-size: 11px !important; }
        .font-22 { font-size: 22px !important; }
        .font-28 { font-size: 28px !important; }
        .gap-10  { gap: 10px !important; }
        .gap-12  { gap: 12px !important; }
        .gap-16  { gap: 16px !important; }
        .g-12    { gap: 12px !important; }
        .g-12.row { margin: -6px; }
        .g-12.row > [class*="col"] { padding: 6px; }

        /* badge utils */
        .badge-warning-light  { background:#FEF3C7; color:#92400E; border-radius:8px; padding:2px 8px; }
        .badge-success-light  { background:#D1FAE5; color:#065F46; border-radius:8px; padding:2px 8px; }
        .badge-danger-light   { background:#FEE2E2; color:#991B1B; border-radius:8px; padding:2px 8px; }

        /* Teacher IELTS dashboard specific */
        .teacher-welcome-bar { min-height: 120px; border: 1px solid #f3f4f6; padding: 16px 0; }
        .teacher-welcome-bar .language-select { position: relative; }
        .teacher-welcome-bar .language-dropdown { right: 0; left: auto; }
        .gx-16 { --bs-gutter-x: 1rem; }
        .badge-gray { background:#f3f4f6; color:#374151; border-radius:8px; padding:2px 8px; }

        @media (max-width: 767px) {
            .teacher-welcome-bar { flex-direction: column; align-items: flex-start !important; gap: 12px; }
        }
    </style>
@endpush

@section("content")
    <div class="dashboard-body">
        @if($authUser->isUser() || $authUser->isStudent())
            <div class="student-dashboard">
                @include('design_1.panel.dashboard.student.index')
            </div>
        @else
            <div class="instructor-dashboard">
                @include('design_1.panel.dashboard.instructor.index')
            </div>
        @endif
    </div>
@endsection

@push("scripts_bottom")
    <script>
        var learningActivityLang = '{{ trans('update.learning_activity') }}';
        var minsLang = '{{ trans('update.mins') }}';
        var noticeLang = '{{ trans('update.notice') }}';
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}';
        var joinTheMeetingLang = '{{ trans('update.join_the_meeting') }}';
        var passwordLang = '{{ trans('auth.password') }}';

        var $eventsWithTimestamp = @json((!empty($eventsWithTimestamp) and count($eventsWithTimestamp)) ? $eventsWithTimestamp : []);
    </script>

    <script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
    <script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>
    <script src="/assets/design_1/vendor/apexcharts/apexcharts.js"></script>

    <script src="/assets/design_1/js/panel/meeting_requests.min.js"></script>
    <script src="/assets/design_1/js/panel/events_calendar.min.js"></script>
    <script src="/assets/design_1/js/panel/dashboard.min.js"></script>
@endpush

@if(!empty($giftModal))
    @push('scripts_bottom2')
        <script>
            (function () {
                "use strict";

                handleFireSwalModal('{!! $giftModal !!}', 32)
            })(jQuery)
        </script>
    @endpush
@endif
