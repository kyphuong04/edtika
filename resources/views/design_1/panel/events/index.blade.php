@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css"/>
    <style>
        .lc-inline-unit {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lc-inline-unit input {
            min-width: 0;
            flex: 1 1 auto;
        }

        .lc-inline-unit__text {
            white-space: nowrap;
            color: #94a3b8;
            font-size: 14px;
        }

        .lc-modal-close {
            border: 0 !important;
            outline: 0 !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: 0;
            width: 32px;
            height: 32px;
            line-height: 1;
            color: #6b7280;
        }

        .lc-modal-close:hover {
            color: #111827;
            background: transparent !important;
        }
    </style>
@endpush

@section('content')
    {{-- Page header with Create Live Course button --}}
    @if(auth()->user()->isTeacher() || auth()->user()->isAdmin() || auth()->user()->isOrganization() || auth()->user()->isManager() || auth()->user()->isCeo())
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="font-20 font-weight-bold text-dark">{{ trans('update.events_calendar') }}</h1>
        <button type="button" class="btn btn-sm d-flex align-items-center gap-8" data-toggle="modal" data-target="#createLiveCourseModal">
            <x-iconsax-bul-video class="icons" width="18px" height="18px"/>
            <span>{{ trans('update.create_live_course_from_calendar') }}</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="bg-white p-16 rounded-24">
                <div class="pb-6 border-bottom-gray-100">
                    <h3 class="font-14 font-weight-bold text-dark">{{ trans('update.select_a_date') }}</h3>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.select_a_date_from_the_calendar_and_check_events') }}</p>
                </div>

                <div class="dashboard-events-calendar mt-20">
                    <input type="hidden" id="inlineEventsCalender" value="">
                    <div id="dashboardEventsCalendar"></div>
                </div>

            </div>
        </div>

        <div class="js-day-events-card col-12 col-lg-6 mt-20 mt-lg-0 rounded-24">
            {{-- Day Events --}}
            @include('design_1.panel.events.day_events')
        </div>

        <div class="col-12 col-lg-3 mt-20 mt-lg-0">
            {{-- Upcoming Events --}}
            <div class="bg-white p-16 rounded-24">
                <div class="pb-6 border-bottom-gray-100">
                    <h3 class="d-flex align-items-center font-14 font-weight-bold text-dark">{{ trans('update.upcoming_events') }}</h3>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.check_upcoming_events_and_add_them_to_reminder') }}</p>
                </div>

                {{-- Card --}}
                @if(!empty($upcomingEvents) and count($upcomingEvents))
                    @foreach($upcomingEvents as $upcomingEvent)
                        <div class="js-upcoming-event-card d-flex align-items-center p-12 rounded-16 mt-16 bg-gray-100 cursor-pointer" data-day="{{ $upcomingEvent['event_at'] }}">
                            <div class="events-calendar__upcoming-event-date-box d-flex-center flex-column text-center rounded-8 bg-gray-200">
                                <span class="font-weight-bold text-dark">{{ dateTimeFormat($upcomingEvent['event_at'], 'j') }}</span>
                                <span class="font-12 text-gray-400 mt-2">{{ dateTimeFormat($upcomingEvent['event_at'], 'M') }}</span>
                            </div>
                            <div class="ml-8">
                                <div class="">{{ trans("update.{$upcomingEvent['title']}") }}</div>
                                <p class="font-12 text-gray-500 mt-8">{{ $upcomingEvent['subtitle'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>

@endsection

@push('panel_modals')
    {{-- Create Live Course Modal (rendered outside simplebar to avoid stacking-context issues) --}}
    @if(auth()->user()->isTeacher() || auth()->user()->isAdmin() || auth()->user()->isOrganization() || auth()->user()->isManager() || auth()->user()->isCeo())
    <div class="modal fade" id="createLiveCourseModal" tabindex="-1" role="dialog" aria-labelledby="createLiveCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-16">
                <div class="modal-header border-bottom-gray-100 px-20 py-16">
                    <h5 class="modal-title font-16 font-weight-bold" id="createLiveCourseModalLabel">
                        <x-iconsax-bul-video class="icons mr-8" width="20px" height="20px" style="color: #511D99"/>
                        {{ trans('update.create_live_course_from_calendar') }}
                    </h5>
                    <button type="button" class="close lc-modal-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-20 py-16">
                    <form id="createLiveCourseForm">
                        @csrf

                        {{-- Session API (local / zoom) --}}
                        <div class="mb-16">
                            <label class="font-12 text-gray-500 d-block mb-8">{{ trans('webinars.select_session_api') }} <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                @foreach(getFeaturesSettings('available_session_apis') as $sessionApi)
                                    @if(in_array($sessionApi, ['local', 'zoom']))
                                        <div class="custom-control custom-radio mr-12 mb-24">
                                            <input type="radio" name="session_api" id="lc_api_{{ $sessionApi }}" value="{{ $sessionApi }}" @if($sessionApi === 'local') checked @endif class="custom-control-input">
                                            <label class="custom-control__label cursor-pointer pl-0" for="lc_api_{{ $sessionApi }}">{{ trans('update.session_api_' . $sessionApi) }}</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Language --}}
                        <div class="form-group">
                            <label class="form-group-label">{{ trans('language') }}</label>
                            <select name="language" id="lc_language" class="form-control">
                                @foreach(getUserLanguagesLists() as $lang => $langLabel)
                                    <option value="{{ $lang }}" @if($lang === app()->getLocale()) selected @endif>{{ $langLabel }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Password --}}
                        <div class="form-group js-lc-api-secret">
                            <label class="form-group-label">{{ trans('auth.password') }}</label>
                            <input type="text" name="api_secret" id="lc_api_secret" class="form-control" autocomplete="new-password">
                        </div>

                        {{-- Title --}}
                        <div class="form-group">
                            <label class="form-group-label is-required">{{ trans('public.title') }}</label>
                            <input type="text" name="title" id="lc_title" class="form-control" placeholder="{{ trans('forms.maximum_255_characters') }}">
                            <div class="text-danger font-12 mt-4 js-lc-error-title"></div>
                        </div>

                        {{-- Start date --}}
                        <div class="form-group">
                            <label class="form-group-label is-required">{{ trans('public.date') }}</label>
                            <input type="text" name="start_date" id="lc_start_date" class="form-control datetimepicker" autocomplete="off" placeholder="YYYY-MM-DD HH:MM">
                            <div class="text-danger font-12 mt-4 js-lc-error-start_date"></div>
                        </div>

                        {{-- Duration --}}
                        <div class="form-group">
                            <label class="form-group-label is-required">{{ trans('public.duration') }}</label>
                            <div class="lc-inline-unit">
                                <input type="number" name="duration" id="lc_duration" class="form-control" min="1" placeholder="60">
                                <span class="lc-inline-unit__text">{{ trans('public.minutes') }}</span>
                            </div>
                            <div class="text-danger font-12 mt-4 js-lc-error-duration"></div>
                        </div>

                        {{-- Link (local only) --}}
                        <div class="form-group js-lc-link-group">
                            <label class="form-group-label is-required">{{ trans('public.link') }}</label>
                            <input type="url" name="link" id="lc_link" class="form-control" placeholder="https://">
                            <div class="text-danger font-12 mt-4 js-lc-error-link"></div>
                        </div>

                        {{-- Zoom auto-create notice --}}
                        <div class="js-lc-zoom-notice d-none form-group">
                            <div class="alert alert-info p-10 font-12 mb-0 rounded-8">
                                {{ trans('update.zoom_meeting_will_be_created_automatically') }}
                                <a href="/panel/setting/step/zoom" target="_blank" class="font-weight-bold">{{ trans('public.go_to_settings') }}</a>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label class="form-group-label">{{ trans('public.description') }}</label>
                            <textarea name="description" id="lc_description" class="form-control" rows="4"></textarea>
                        </div>

                        {{-- Extra time to join --}}
                        @if(!empty(getFeaturesSettings('extra_time_to_join_status')) && getFeaturesSettings('extra_time_to_join_status'))
                        <div class="form-group">
                            <label class="form-group-label">{{ trans('update.extra_time_to_join') }}</label>
                            <div class="lc-inline-unit">
                                <input type="number" name="extra_time_to_join" id="lc_extra_time" class="form-control" min="0" value="{{ getFeaturesSettings('extra_time_to_join_default_value') ?? 20 }}">
                                <span class="lc-inline-unit__text">{{ trans('public.minutes') }}</span>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="extra_time_to_join" value="{{ getFeaturesSettings('extra_time_to_join_default_value') ?? 20 }}">
                        @endif

                        {{-- Active toggle --}}
                        <div class="form-group d-flex align-items-center">
                            <div class="custom-switch mr-8">
                                <input id="lcStatusSwitch" type="checkbox" name="status" value="1" class="custom-control-input" checked>
                                <label class="custom-control-label cursor-pointer" for="lcStatusSwitch"></label>
                            </div>
                            <label class="cursor-pointer mb-0" for="lcStatusSwitch">{{ trans('public.active') }}</label>
                        </div>

                        {{-- Notification Students --}}
                        <div class="border-top pt-16 mt-8">
                            <h6 class="font-13 font-weight-bold mb-4">{{ trans('update.live_course_notify_group_title') }}</h6>
                            <p class="font-12 text-gray-500 mb-24">{{ trans('update.live_course_notify_group_hint') }}</p>
                            <div class="form-group mb-0" id="lc_student_select_wrapper">
                                <label class="form-group-label">Gửi thông báo đến học viên</label>
                                <select
                                    name="student_ids[]"
                                    id="lc_student_ids"
                                    class="form-control select2"
                                    data-placeholder="Chọn 1 hoặc nhiều học viên"
                                    multiple
                                >
                                    @if(!empty($students) and count($students))
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->full_name }} @if(!empty($student->email)) ({{ $student->email }}) @endif</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group mt-16 mb-0">
                                <label class="form-group-label">Khóa học</label>
                                <select name="bundle_id" id="lc_bundle_id" class="form-control">
                                    <option value="">Không gửi thông báo theo khóa học</option>
                                    @foreach($bundles as $bundle)
                                        <option value="{{ $bundle->id }}">{{ $bundle->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Alert area --}}
                        <div id="lcFormAlert" class="mt-16" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer border-top-gray-100 px-20 py-16 d-flex justify-content-between">
                    <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">{{ trans('public.cancel') }}</button>
                    <button type="button" id="btnSaveLiveCourse" class="btn btn-primary btn-sm">
                        <span class="js-btn-text">{{ trans('public.save') }}</span>
                        <span class="js-btn-spinner d-none spinner-border spinner-border-sm ml-4" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endpush

@push("scripts_bottom")
    <script>
        var $eventsWithTimestamp = @json((!empty($eventsWithTimestamp) and count($eventsWithTimestamp)) ? $eventsWithTimestamp : []);
    </script>

    <script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
    <script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script src="/assets/design_1/js/panel/events_calendar.min.js"></script>

    <script>
    (function () {
        function initLiveCourseStudentSelect() {
            var $input = $('#lc_student_ids');

            if (!$input.length || !jQuery().select2) {
                return;
            }

            if ($input.hasClass('select2-hidden-accessible')) {
                $input.select2('destroy');
            }

            $input.select2({
                placeholder: $input.attr('data-placeholder') || 'Chọn học viên',
                width: '100%',
                closeOnSelect: false,
                dropdownParent: $('#lc_student_select_wrapper')
            });
        }

        function initLiveCourseDatePicker() {
            var $input = $('#lc_start_date');

            if (!$input.length || !jQuery().daterangepicker) {
                return;
            }

            if ($input.data('daterangepicker')) {
                $input.data('daterangepicker').remove();
            }

            $input.daterangepicker({
                parentEl: '#createLiveCourseModal .modal-body',
                locale: {
                    format: 'YYYY-MM-DD HH:mm',
                    cancelLabel: (typeof clearLang !== 'undefined' ? clearLang : 'Clear')
                },
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                autoUpdateInput: false,
                showDropdowns: true,
                drops: 'down'
            });

            $input.off('apply.daterangepicker.lc').on('apply.daterangepicker.lc', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm'));
                $(this).removeClass('is-invalid');
                $('.js-lc-error-start_date').text('');
            });

            $input.off('cancel.daterangepicker.lc').on('cancel.daterangepicker.lc', function () {
                $(this).val('');
            });

            // Ensure picker opens when user clicks/focuses the field inside modal
            $input.off('focus.lc click.lc').on('focus.lc click.lc', function () {
                var instance = $(this).data('daterangepicker');
                if (instance) {
                    instance.show();
                }
            });
        }

        // Init datetimepicker inside modal every time it opens
        $('#createLiveCourseModal').on('shown.bs.modal', function () {
            initLiveCourseStudentSelect();
            initLiveCourseDatePicker();
        });

        // Toggle link/zoom visibility based on session_api
        $('input[name="session_api"]').on('change', function () {
            var isLocal = $(this).val() === 'local';
            $('.js-lc-link-group').toggle(isLocal);
            $('.js-lc-zoom-notice').toggleClass('d-none', isLocal);
            $('.js-lc-api-secret').toggle(isLocal);
        });

        // Reset form on close
        $('#createLiveCourseModal').on('hidden.bs.modal', function () {
            $('#createLiveCourseForm')[0].reset();
            $('#lcFormAlert').hide().html('');
            $('.js-lc-error-title, .js-lc-error-start_date, .js-lc-error-link, .js-lc-error-duration').text('');
            $('#lc_title, #lc_start_date, #lc_link, #lc_duration').removeClass('is-invalid');
            if ($('#lc_start_date').data('daterangepicker')) {
                $('#lc_start_date').data('daterangepicker').remove();
            }

            if ($('#lc_student_ids').hasClass('select2-hidden-accessible')) {
                $('#lc_student_ids').select2('destroy');
            }

            $('#lc_start_date').val('');
            // Reset session_api to local
            $('#lc_api_local').prop('checked', true).trigger('change');
            // Re-check status switch
            $('#lcStatusSwitch').prop('checked', true);
        });

        $('#btnSaveLiveCourse').on('click', function () {
            var $btn = $(this);
            var $spinner = $btn.find('.js-btn-spinner');
            var $text = $btn.find('.js-btn-text');

            // Clear previous errors
            $('#lcFormAlert').hide().html('');
            $('.js-lc-error-title, .js-lc-error-start_date, .js-lc-error-link, .js-lc-error-duration').text('');
            $('#lc_title, #lc_start_date, #lc_link, #lc_duration').removeClass('is-invalid');

            var formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                title:             $('#lc_title').val(),
                session_api:       $('input[name="session_api"]:checked').val(),
                language:          $('#lc_language').val(),
                api_secret:        $('#lc_api_secret').val(),
                link:              $('#lc_link').val(),
                start_date:        $('#lc_start_date').val(),
                duration:          $('#lc_duration').val(),
                description:       $('#lc_description').val(),
                extra_time_to_join: $('#lc_extra_time').val() || $('input[name="extra_time_to_join"]').val(),
                status:            $('#lcStatusSwitch').is(':checked') ? 1 : 0,
                student_ids:       $('#lc_student_ids').val() || [],
                bundle_id:         $('#lc_bundle_id').val(),
            };

            $btn.prop('disabled', true);
            $spinner.removeClass('d-none');
            $text.text('{{ trans('public.loading') }}');

            $.ajax({
                url: '/panel/events/live-course/store',
                method: 'POST',
                data: formData,
                success: function (res) {
                    if (res.code === 200) {
                        $('#createLiveCourseModal').modal('hide');
                        window.location.href = res.edit_url;
                    }
                },
                error: function (xhr) {
                    var data = xhr.responseJSON || {};
                    var errors = data.errors || null;

                    if (data.status === 'zoom_token_invalid') {
                        $('#lcFormAlert').show().html('<div class="alert alert-danger p-10 font-12 mb-0 rounded-8">' + (data.zoom_error_msg || 'Zoom error') + ' &mdash; <a href="/panel/setting/step/8" target="_blank">{{ trans('public.go_to_settings') }}</a></div>');
                    } else if (errors) {
                        if (errors.title)      { $('#lc_title').addClass('is-invalid'); $('.js-lc-error-title').text(errors.title[0]); }
                        if (errors.start_date) { $('#lc_start_date').addClass('is-invalid'); $('.js-lc-error-start_date').text(errors.start_date[0]); }
                        if (errors.link)       { $('#lc_link').addClass('is-invalid'); $('.js-lc-error-link').text(errors.link[0]); }
                        if (errors.duration)   { $('#lc_duration').addClass('is-invalid'); $('.js-lc-error-duration').text(errors.duration[0]); }
                        var firstGeneric = errors.student_ids || errors.bundle_id || null;
                        if (firstGeneric) { $('#lcFormAlert').show().html('<div class="alert alert-danger p-10 font-12 mb-0 rounded-8">' + firstGeneric[0] + '</div>'); }
                    } else {
                        var msg = data.msg || '{{ trans('public.error') }}';
                        $('#lcFormAlert').show().html('<div class="alert alert-danger p-10 font-12 mb-0 rounded-8">' + msg + '</div>');
                    }
                },
                complete: function () {
                    $btn.prop('disabled', false);
                    $spinner.addClass('d-none');
                    $text.text('{{ trans('public.save') }}');
                }
            });
        });
    })();
    </script>
@endpush
