@extends('design_1.panel.layouts.panel', ['hidePanelTitleBar' => true])

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("create-course") }}">
    <style>
        /* ─── Module Editor Top Bar ─────────────────────────── */
        .module-editor-topbar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            background: #fff;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }

        .module-editor-topbar__back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 50px;
            border: 1.5px solid #d1d5db;
            background: #fff;
            color: #374151;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none !important;
            white-space: nowrap;
            transition: border-color .2s, color .2s;
        }

        .module-editor-topbar__back:hover {
            border-color: #6b7280;
            color: #111827;
        }

        .module-editor-topbar__bundle-name {
            padding: 9px 20px;
            background: #f3f4f6;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            white-space: nowrap;
        }

        .module-editor-topbar__title-input {
            flex: 1;
            min-width: 180px;
            border: 1.5px solid #e5e7eb;
            border-radius: 50px;
            padding: 9px 20px;
            font-size: 14px;
            font-weight: 500;
            color: #111827;
            background: #fff;
            outline: none;
            transition: border-color .2s;
        }

        .module-editor-topbar__title-input:focus {
            border-color: #1f2937;
        }

        /* ─── Override: hide the progress bar from bottom actions ─ */
        .create-course-bottom-progress { display: none !important; }
        /* Remove bottom padding no longer needed for fixed bar */
        .pb-100 { padding-bottom: 0 !important; }

        /* ─── Section header redesign to match wireframe ────── */
        .module-sections-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            margin-bottom: 12px;
        }

        .module-sections-header__info h5 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .module-sections-header__info p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }

        .module-sections-header__btn {
            padding: 8px 20px;
            border-radius: 50px;
            border: 1.5px solid #d1d5db;
            background: #fff;
            color: #374151;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: border-color .2s, color .2s;
        }

        .module-sections-header__btn:hover {
            border-color: #1f2937;
            color: #111827;
        }

        /* ─── Indent lesson items inside each section ───────── */
        .accordion-content-wrapper {
            padding-left: 20px;
        }
    </style>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    <form method="post"
          action="/panel/courses/{{ $webinar->id }}/update"
          id="webinarForm"
          enctype="multipart/form-data">

        {{ csrf_field() }}

        {{-- Hidden fields needed for step 1 title update --}}
        <input type="hidden" name="current_step" value="1" id="currentStepInput">
        <input type="hidden" name="draft"     value="no" id="forDraft">
        <input type="hidden" name="get_next"  value="no" id="getNext">
        <input type="hidden" name="get_step"  value="0"  id="getStep">
        <input type="hidden" name="type"      value="{{ $webinar->type }}">
        <input type="hidden" name="locale"    value="{{ $locale }}">
        <input type="hidden" name="summary"   value="{{ htmlspecialchars($webinar->summary ?: ($webinar->title ?? 'New Module')) }}">
        <input type="hidden" name="description" value="{{ htmlspecialchars($webinar->description ?: ($webinar->title ?? 'New Module')) }}">
        <input type="hidden" name="message_for_reviewer" id="bundleMessageForReviewer" value="{{ $webinar->message_for_reviewer ?? '' }}">

        <div class="pb-100">

            {{-- ── TOP BAR ──────────────────────────────────── --}}
            <div class="module-editor-topbar">
                <a href="{{ $bundle ? '/panel/bundles/' . $bundle->id . '/modules' : '/panel/courses' }}" class="module-editor-topbar__back">
                    &#8592; Back
                </a>

                @if($bundle)
                <div class="module-editor-topbar__bundle-name">
                    {{ strtoupper($bundle->title) }}
                </div>
                @endif

                <input type="text"
                       name="title"
                       value="{{ $webinar->title }}"
                       class="module-editor-topbar__title-input"
                       placeholder="Title"
                       required>
            </div>

            {{-- ── SECTIONS HEADER (replaces built-in) ─────── --}}
            <div class="module-sections-header">
                <div class="module-sections-header__info">
                    <h5>Sections</h5>
                    <p>Organize Your Course Content By Defining Different Sections.</p>
                </div>
                <button type="button"
                        class="module-sections-header__btn js-add-chapter"
                        data-webinar-id="{{ $webinar->id }}">
                    New Section
                </button>
            </div>

            {{-- ── CHAPTER / CURRICULUM CONTENT ────────────── --}}
            <div class="bg-white rounded-16 p-16">
                {{-- Chapter Items --}}
                @include('design_1.panel.webinars.create.includes.chapter_contents')
            </div>

            {{-- ── HIDDEN ACCORDION FORMS (for add lesson modals) ─ --}}
            @if($webinar->isWebinar())
                <div id="newSessionForm" class="d-none">
                    @include('design_1.panel.webinars.create.includes.accordions.session', ['webinar' => $webinar])
                </div>
            @endif

            <div id="newFileForm" class="d-none">
                @include('design_1.panel.webinars.create.includes.accordions.file', ['webinar' => $webinar])
            </div>

            @if(getFeaturesSettings('new_interactive_file'))
                <div id="newInteractiveFileForm" class="d-none">
                    @include('design_1.panel.webinars.create.includes.accordions.interactive_file', ['webinar' => $webinar])
                </div>
            @endif

            <div id="newTextLessonForm" class="d-none">
                @include('design_1.panel.webinars.create.includes.accordions.text_lesson', ['webinar' => $webinar])
            </div>

            @if(getFeaturesSettings('webinar_assignment_status'))
                <div id="newAssignmentForm" class="d-none">
                    @include('design_1.panel.webinars.create.includes.accordions.assignment', ['webinar' => $webinar])
                </div>
            @endif

            <div id="newQuizForm" class="d-none">
                @include('design_1.panel.webinars.create.includes.accordions.quiz', [
                    'webinar' => $webinar,
                    'quizInfo' => null,
                    'webinarChapterPages' => true,
                ])
            </div>

            <div id="changeChapterModalHtml" class="d-none">
                @include('design_1.panel.webinars.create.modals.change_chapter')
            </div>

        </div>

        {{-- ── INLINE BOTTOM ACTIONS ─────────────────────── --}}
        <div class="d-flex align-items-center justify-content-between mt-32">
            <button type="button" id="saveAsDraft" class="btn btn-outline-secondary px-24 py-12 rounded-50">
                Save As Draft
            </button>
            <button type="button" id="bundleSubmitForReview" class="btn btn-primary px-24 py-12 rounded-50">
                Submit For Review
            </button>
        </div>

    </form>
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang      = '{{ trans('webinars.success_store') }}';
        var zoomJwtTokenInvalid  = '{{ trans('webinars.zoom_jwt_token_invalid') }}';
        var hasZoomApiToken      = '{{ (!empty($authUser->zoomApi) and !empty($authUser->zoomApi->api_key) and !empty($authUser->zoomApi->api_secret)) ? 'true' : 'false' }}';
        var editChapterLang      = '{{ trans('public.edit_chapter') }}';
        var newChapterLang       = '{{ trans('public.new_chapter') }}';
        var saveLang             = '{{ trans('public.save') }}';
        var closeLang            = '{{ trans('public.close') }}';
        var quizzesSectionLang   = '{{ trans('quiz.quizzes_section') }}';
        var newQuestionLang      = '{{ trans('update.new_question') }}';
        var editQuestionLang     = '{{ trans('update.edit_question') }}';
        var changeChapterLang    = '{{ trans('update.change_chapter') }}';

        // After save: redirect back to the bundle modules page instead of /panel/courses
        var afterSaveRedirectUrl = '{{ $bundle ? '/panel/bundles/' . $bundle->id . '/modules' : '/panel/courses' }}';

        var bundleMessageToReviewerLang = '{{ trans('public.message_to_reviewer') }}';
        var bundleAgreeRulesLang        = '{{ trans('public.agree_rules') }}';
        var bundleSendForReviewLang     = '{{ trans('public.send_for_review') }}';
        var bundleSiteMessageLang       = '{{ trans('site.message') }}';
    </script>

    <script>
        (function ($) {
            "use strict";

            function generateId(prefix) {
                return `${prefix}_${Date.now()}_${Math.floor(Math.random() * 10000)}`;
            }

            function getNamePrefix($el) {
                return $el.data('name-prefix');
            }

            function renderAnswerRow(namePrefix, questionIndex, answerIndex) {
                const correctId = generateId('quiz_correct');
                return `
                    <div class="quiz-answer-row d-flex align-items-center" data-answer-index="${answerIndex}">
                        <input type="text" class="form-control form-control-sm" name="${namePrefix}[questions][${questionIndex}][answers][${answerIndex}][title]" placeholder="Answer">
                        <div class="custom-control custom-radio ml-8">
                            <input type="radio" class="custom-control-input" id="${correctId}" name="${namePrefix}[questions][${questionIndex}][correct_answer]" value="${answerIndex}">
                            <label class="custom-control__label cursor-pointer" for="${correctId}">Correct</label>
                        </div>
                        <button type="button" class="btn btn-xs btn-outline-danger ml-8 js-remove-quiz-answer">Remove</button>
                    </div>
                `;
            }

            function renderQuestionItem(namePrefix, questionIndex) {
                return `
                    <div class="quiz-question-item border rounded-8 p-12 mb-12" data-question-index="${questionIndex}">
                        <div class="d-flex align-items-center justify-content-between mb-8">
                            <label class="form-group-label mb-0">Question</label>
                            <button type="button" class="btn btn-xs btn-outline-danger js-remove-quiz-question">Remove</button>
                        </div>
                        <input type="text" class="form-control" name="${namePrefix}[questions][${questionIndex}][title]" placeholder="Question text">

                        <div class="quiz-answers-wrapper mt-12">
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="form-group-label mb-0">Answers</label>
                                <button type="button" class="btn btn-xs btn-outline-primary js-add-quiz-answer">Add answer</button>
                            </div>
                            <div class="quiz-answers-list mt-8"></div>
                        </div>
                    </div>
                `;
            }

            $('body').on('click', '.js-add-quiz-question', function (e) {
                e.preventDefault();
                const $builder = $(this).closest('.interactive-quiz-builder');
                const namePrefix = getNamePrefix($builder);
                const $list = $builder.find('.quiz-questions-list');
                const index = $list.find('.quiz-question-item').length;
                $list.append(renderQuestionItem(namePrefix, index));
            });

            $('body').on('click', '.js-remove-quiz-question', function (e) {
                e.preventDefault();
                $(this).closest('.quiz-question-item').remove();
            });

            $('body').on('click', '.js-add-quiz-answer', function (e) {
                e.preventDefault();
                const $questionItem = $(this).closest('.quiz-question-item');
                const $builder = $(this).closest('.interactive-quiz-builder');
                const namePrefix = getNamePrefix($builder);
                const questionIndex = $questionItem.data('question-index');
                const $answersList = $questionItem.find('.quiz-answers-list');
                const answerIndex = $answersList.find('.quiz-answer-row').length;
                $answersList.append(renderAnswerRow(namePrefix, questionIndex, answerIndex));
            });

            $('body').on('click', '.js-remove-quiz-answer', function (e) {
                e.preventDefault();
                $(this).closest('.quiz-answer-row').remove();
            });

            function renderLectureNoteItem(namePrefix, index) {
                return `
                    <div class="lecture-note-item border rounded-8 p-12 mb-12" data-note-index="${index}">
                        <div class="d-flex align-items-center justify-content-between mb-8">
                            <label class="form-group-label mb-0">Note</label>
                            <button type="button" class="btn btn-xs btn-outline-danger js-remove-lecture-note">Remove</button>
                        </div>
                        <input type="text" class="form-control mb-8" name="${namePrefix}[${index}][title]" placeholder="Note title">
                        <textarea class="form-control" name="${namePrefix}[${index}][content]" rows="4" placeholder="Note content"></textarea>
                    </div>
                `;
            }

            $('body').on('click', '.js-add-lecture-note', function (e) {
                e.preventDefault();
                const $builder = $(this).closest('.lecture-notes-builder');
                const namePrefix = getNamePrefix($builder);
                const $list = $builder.find('.lecture-notes-list');
                const index = $list.find('.lecture-note-item').length;
                $list.append(renderLectureNoteItem(namePrefix, index));
            });

            $('body').on('click', '.js-remove-lecture-note', function (e) {
                e.preventDefault();
                $(this).closest('.lecture-note-item').remove();
            });

        })(jQuery);
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/design_1/js/panel/quiz_create.min.js"></script>

    <script src="/assets/design_1/js/panel/create_webinar.min.js"></script>
    <script src="/assets/design_1/js/panel/webinar_content_locale.min.js"></script>

    <script>
        // Override the post-save redirect to go back to the bundle
        $(document).on('webinar:saved', function() {
            window.location.href = afterSaveRedirectUrl;
        });

        // Steps nav shortcuts expected by create_webinar.js
        var undefinedActiveSessionLang = '{{ trans('webinars.undefined_active_session') }}';
        var selectChapterLang          = '{{ trans('update.select_chapter') }}';
        var liveSessionInfoLang        = '{{ trans('update.live_session_info') }}';
        var joinTheSessionLang         = '{{ trans('update.join_the_session') }}';

        // Submit For Review modal
        (function ($) {
            var closeIconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>';

            $('body').on('click', '#bundleSubmitForReview', function (e) {
                e.preventDefault();

                var existingMessage = $('#bundleMessageForReviewer').val() || '';

                var bodyHtml = '<div class="form-group mt-8">'
                    + '<label class="form-group-label">' + bundleSiteMessageLang + '</label>'
                    + '<textarea id="bundleReviewerMessage" rows="6" class="form-control">' + $('<div>').text(existingMessage).html() + '</textarea>'
                    + '</div>'
                    + '<div class="form-group mt-16 mb-0">'
                    + '<div class="d-flex align-items-center">'
                    + '<div class="custom-switch mr-8">'
                    + '<input type="checkbox" id="bundleReviewRules" class="custom-control-input">'
                    + '<label class="custom-control-label cursor-pointer" for="bundleReviewRules"></label>'
                    + '</div>'
                    + '<label class="cursor-pointer mb-0" for="bundleReviewRules">' + bundleAgreeRulesLang + '</label>'
                    + '</div>'
                    + '<div id="bundleReviewRulesError" class="text-danger mt-8 d-none">{{ trans('public.agree_rules') }}</div>'
                    + '</div>';

                var footerHtml = '<div class="d-flex align-items-center justify-content-end">'
                    + '<button type="button" id="bundleConfirmSubmit" class="btn btn-sm btn-primary">' + bundleSendForReviewLang + '</button>'
                    + '<button type="button" class="close-swl btn btn-sm btn-danger ml-8">' + closeLang + '</button>'
                    + '</div>';

                Swal.fire({
                    html: makeModalHtml(bundleMessageToReviewerLang, closeIconSvg, bodyHtml, footerHtml),
                    showCancelButton: false,
                    showConfirmButton: false,
                    width: '40rem',
                });
            });

            $('body').on('click', '#bundleConfirmSubmit', function (e) {
                e.preventDefault();

                if (!$('#bundleReviewRules').is(':checked')) {
                    $('#bundleReviewRulesError').removeClass('d-none');
                    return;
                }

                $('#bundleMessageForReviewer').val($('#bundleReviewerMessage').val());

                if (!$('#bundleRulesInput').length) {
                    $('#webinarForm').append('<input type="hidden" id="bundleRulesInput" name="rules" value="on">');
                }

                Swal.close();
                $('#forDraft').val(0);
                $(this).addClass('loadingbar').prop('disabled', true);
                $('#webinarForm').trigger('submit');
            });
        })(jQuery);
    </script>
@endpush
