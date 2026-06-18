@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">


    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-category-2 class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('public.chapters') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.define_different_sections_and_organize_the_content_inside_them') }}</p>
            </div>
        </div>

        <div class="js-add-chapter d-flex align-items-center cursor-pointer" data-webinar-id="{{ $webinar->id }}">
            <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
            <span class="text-primary ml-4">{{ trans('public.new_chapter') }}</span>
        </div>
    </div>

    {{-- Chapter Items --}}
    @include('design_1.panel.webinars.create.includes.chapter_contents')

</div>



@if($webinar->isWebinar())
    <div id="newSessionForm" class="d-none">
        @include('design_1.panel.webinars.create.includes.accordions.session',['webinar' => $webinar])
    </div>
@endif

<div id="newFileForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.file',['webinar' => $webinar])
</div>

@if(getFeaturesSettings('new_interactive_file'))
    <div id="newInteractiveFileForm" class="d-none">
        @include('design_1.panel.webinars.create.includes.accordions.interactive_file',['webinar' => $webinar])
    </div>
@endif

<div id="newTextLessonForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.text_lesson',['webinar' => $webinar])
</div>

@if(getFeaturesSettings('webinar_assignment_status'))
    <div id="newAssignmentForm" class="d-none">
        @include('design_1.panel.webinars.create.includes.accordions.assignment',['webinar' => $webinar])
    </div>
@endif

<div id="newQuizForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.quiz',['webinar' => $webinar, 'quizInfo' => null, 'webinarChapterPages' => true])
</div>

<div id="changeChapterModalHtml" class="d-none">
    @include("design_1.panel.webinars.create.modals.change_chapter")
</div>

@push('scripts_bottom')
    <script>
        var newChapterLang = '{{ trans('public.new_chapter') }}';
        var editChapterLang = '{{ trans('public.edit_chapter') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var quizzesSectionLang = '{{ trans('quiz.quizzes_section') }}';
        var newQuestionLang = '{{ trans('update.new_question') }}';
        var editQuestionLang = '{{ trans('update.edit_question') }}';
        var changeChapterLang = '{{ trans('update.change_chapter') }}';

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

            function getNextDataIndex($el, attrName, fallback) {
                let current = parseInt($el.attr(attrName), 10);

                if (Number.isNaN(current)) {
                    current = fallback;
                }

                $el.attr(attrName, current + 1);
                return current;
            }

            function renderOptionRow(namePrefix, questionIndex, answerIndex) {
                const singleId = generateId('quiz_single');
                const multiId = generateId('quiz_multi');

                return `
                    <div class="quiz-option-row d-flex align-items-center mb-8" data-answer-index="${answerIndex}">
                        <input type="text" class="form-control form-control-sm" name="${namePrefix}[questions][${questionIndex}][options][${answerIndex}][title]" placeholder="Option text">
                        <div class="custom-control custom-radio ml-8 js-correct-single-wrap">
                            <input type="radio" class="custom-control-input" id="${singleId}" name="${namePrefix}[questions][${questionIndex}][correct_answer]" value="${answerIndex}">
                            <label class="custom-control__label cursor-pointer" for="${singleId}">Correct</label>
                        </div>
                        <div class="custom-control custom-checkbox ml-8 js-correct-multiple-wrap d-none">
                            <input type="checkbox" class="custom-control-input" id="${multiId}" name="${namePrefix}[questions][${questionIndex}][correct_answers][]" value="${answerIndex}">
                            <label class="custom-control__label cursor-pointer" for="${multiId}">Correct</label>
                        </div>
                        <button type="button" class="btn btn-xs btn-outline-danger ml-8 js-remove-quiz-option">Remove</button>
                    </div>
                `;
            }

            function renderPairRow(namePrefix, questionIndex, pairIndex) {
                return `
                    <div class="quiz-pair-row d-flex align-items-center mb-8" data-pair-index="${pairIndex}">
                        <input type="text" class="form-control form-control-sm" name="${namePrefix}[questions][${questionIndex}][pairs][${pairIndex}][prompt]" placeholder="Left item / statement">
                        <input type="text" class="form-control form-control-sm ml-8" name="${namePrefix}[questions][${questionIndex}][pairs][${pairIndex}][answer]" placeholder="Match answer">
                        <button type="button" class="btn btn-xs btn-outline-danger ml-8 js-remove-quiz-pair">Remove</button>
                    </div>
                `;
            }

            function renderQuestionItem(namePrefix, questionIndex) {
                return `
                    <div class="quiz-question-item border rounded-8 p-12 mb-12" data-question-index="${questionIndex}" data-next-answer-index="0" data-next-pair-index="0">
                        <div class="d-flex align-items-center justify-content-between mb-8">
                            <div>
                                <label class="form-group-label mb-0">Question</label>
                                <p class="font-12 text-gray-500 mb-0 js-quiz-question-summary"></p>
                            </div>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-xs btn-outline-secondary mr-8 js-toggle-quiz-question" aria-expanded="true" title="Expand/Collapse">
                                    <span class="js-toggle-quiz-question-icon">&#9660;</span>
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-danger js-remove-quiz-question">Remove</button>
                            </div>
                        </div>

                        <div class="quiz-question-body">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-12">
                                    <label class="form-group-label mb-6">Question type</label>
                                    <select class="form-control js-quiz-question-type" name="${namePrefix}[questions][${questionIndex}][type]">
                                        <option value="multiple_choice_single">Single Answer</option>
                                        <option value="multiple_choice_multiple">Multiple Answers</option>
                                        <option value="true_false_not_given">True / False / Not Given</option>
                                        <option value="yes_no_not_given">Yes / No / Not Given</option>
                                        <option value="matching_headings">Matching Headings</option>
                                        <option value="matching_information">Matching Information</option>
                                        <option value="matching_features">Matching Features</option>
                                        <option value="matching_sentence_endings">Matching Sentence Endings</option>
                                        <option value="sentence_completion">Sentence Completion</option>
                                        <option value="summary_completion">Summary Completion</option>
                                        <option value="note_completion">Note Completion</option>
                                        <option value="table_completion">Table Completion</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-12">
                                    <label class="form-group-label mb-6">Max words</label>
                                    <input type="number" min="1" class="form-control" name="${namePrefix}[questions][${questionIndex}][max_words]" placeholder="e.g. 3">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-12">
                                    <label class="form-group-label mb-6">Target band</label>
                                    <input type="number" min="0" max="9" step="0.5" class="form-control" name="${namePrefix}[questions][${questionIndex}][target_band]" placeholder="e.g. 6.5">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-12">
                            <label class="form-group-label mb-6">Question / Prompt</label>
                            <textarea rows="2" class="form-control" name="${namePrefix}[questions][${questionIndex}][title]" placeholder="Question text or prompt"></textarea>
                        </div>

                        <div class="quiz-type-section js-quiz-type-section" data-types="multiple_choice_single,multiple_choice_multiple,matching_headings,matching_information,matching_features,matching_sentence_endings">
                            <div class="quiz-options-wrapper mt-12">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-group-label mb-0">Options / Choices</label>
                                    <button type="button" class="btn btn-xs btn-outline-primary js-add-quiz-option">Add option</button>
                                </div>
                                <div class="quiz-options-list mt-8"></div>
                            </div>
                        </div>

                        <div class="quiz-type-section js-quiz-type-section" data-types="true_false_not_given,yes_no_not_given">
                            <div class="form-group mt-12 mb-0">
                                <label class="form-group-label mb-6">Correct answer</label>
                                <select class="form-control js-quiz-fixed-correct-answer" name="${namePrefix}[questions][${questionIndex}][correct_answer]">
                                    <option value="">Select answer</option>
                                    <option value="true" data-for-type="true_false_not_given">True</option>
                                    <option value="false" data-for-type="true_false_not_given">False</option>
                                    <option value="not_given" data-for-type="true_false_not_given">Not Given</option>
                                    <option value="yes" data-for-type="yes_no_not_given">Yes</option>
                                    <option value="no" data-for-type="yes_no_not_given">No</option>
                                    <option value="not_given" data-for-type="yes_no_not_given">Not Given</option>
                                </select>
                            </div>
                        </div>

                        <div class="quiz-type-section js-quiz-type-section" data-types="matching_headings,matching_information,matching_features,matching_sentence_endings">
                            <div class="quiz-pairs-wrapper mt-12">
                                <div class="d-flex align-items-center justify-content-between mb-8">
                                    <label class="form-group-label mb-0">Matching pairs</label>
                                    <button type="button" class="btn btn-xs btn-outline-primary js-add-quiz-pair">Add pair</button>
                                </div>
                                <div class="quiz-pairs-list"></div>
                            </div>
                        </div>

                        <div class="quiz-type-section js-quiz-type-section" data-types="sentence_completion,summary_completion,note_completion,table_completion">
                            <div class="form-group mt-12">
                                <label class="form-group-label mb-6">Correct answer</label>
                                <input type="text" class="form-control" name="${namePrefix}[questions][${questionIndex}][correct_answer]" placeholder="Correct answer">
                            </div>
                            <div class="form-group mb-0">
                                <label class="form-group-label mb-6">Alternative answers (one per line)</label>
                                <textarea rows="3" class="form-control" name="${namePrefix}[questions][${questionIndex}][alternative_answers]" placeholder="Optional alternatives"></textarea>
                            </div>
                        </div>

                        <div class="form-group mt-12 mb-0">
                            <label class="form-group-label mb-6">Answer help / explanation</label>
                            <textarea rows="2" class="form-control" name="${namePrefix}[questions][${questionIndex}][explanation]" placeholder="Optional guidance"></textarea>
                        </div>
                        </div>
                    </div>
                `;
            }

            function setQuestionCollapsed($questionItem, collapsed) {
                const $body = $questionItem.find('.quiz-question-body').first();
                const $toggle = $questionItem.find('.js-toggle-quiz-question').first();
                const $icon = $questionItem.find('.js-toggle-quiz-question-icon').first();

                if (!$body.length || !$toggle.length || !$icon.length) {
                    return;
                }

                $body.toggleClass('d-none', collapsed);
                $toggle.attr('aria-expanded', collapsed ? 'false' : 'true');
                $icon.html(collapsed ? '&#9658;' : '&#9660;');
            }

            function updateQuestionSummary($questionItem) {
                const prompt = (($questionItem.find('textarea[name$="[title]"]').first().val()) || '').trim();
                const typeText = (($questionItem.find('.js-quiz-question-type option:selected').text()) || '').trim();
                const summary = prompt !== '' ? prompt : (typeText !== '' ? typeText : 'Question');

                $questionItem.find('.js-quiz-question-summary').first().text(summary);
            }

            function refreshQuestionTypeUI($questionItem) {
                const type = $questionItem.find('.js-quiz-question-type').val() || 'multiple_choice_single';

                $questionItem.find('.js-quiz-type-section').each(function () {
                    const types = ($(this).data('types') || '').toString().split(',');
                    const isVisible = types.indexOf(type) !== -1;

                    $(this).toggleClass('d-none', !isVisible);
                    $(this).find('input, select, textarea').prop('disabled', !isVisible);
                });

                const isSingle = type === 'multiple_choice_single';
                const isMulti = type === 'multiple_choice_multiple';

                $questionItem.find('.js-correct-single-wrap').toggleClass('d-none', !isSingle);
                $questionItem.find('.js-correct-multiple-wrap').toggleClass('d-none', !isMulti);
                $questionItem.find('.js-correct-single-wrap input[type="radio"]').prop('disabled', !isSingle);
                $questionItem.find('.js-correct-multiple-wrap input[type="checkbox"]').prop('disabled', !isMulti);

                if (!isSingle) {
                    $questionItem.find('.js-correct-single-wrap input[type="radio"]').prop('checked', false);
                }

                if (!isMulti) {
                    $questionItem.find('.js-correct-multiple-wrap input[type="checkbox"]').prop('checked', false);
                }

                const $fixedSelect = $questionItem.find('.js-quiz-fixed-correct-answer');
                if ($fixedSelect.length) {
                    const current = $fixedSelect.val();
                    let validCurrent = false;

                    $fixedSelect.find('option').each(function () {
                        const optionType = $(this).data('for-type');

                        if (!optionType) {
                            $(this).prop('disabled', false).prop('hidden', false);
                            return;
                        }

                        const visible = optionType === type;
                        $(this).prop('disabled', !visible).prop('hidden', !visible);

                        if (visible && $(this).val() === current) {
                            validCurrent = true;
                        }
                    });

                    if (!validCurrent) {
                        $fixedSelect.val('');
                    }
                }
            }

            $('body').on('click', '.js-add-quiz-question', function (e) {
                e.preventDefault();

                const $builder = $(this).closest('.interactive-quiz-builder');
                const namePrefix = getNamePrefix($builder);
                const $list = $builder.find('.quiz-questions-list');
                const questionIndex = getNextDataIndex($builder, 'data-next-question-index', $list.find('.quiz-question-item').length);

                $list.append(renderQuestionItem(namePrefix, questionIndex));

                const $newItem = $list.find('.quiz-question-item').last();

                for (let i = 0; i < 2; i += 1) {
                    const answerIndex = getNextDataIndex($newItem, 'data-next-answer-index', i);
                    $newItem.find('.quiz-options-list').append(renderOptionRow(namePrefix, questionIndex, answerIndex));
                }

                refreshQuestionTypeUI($newItem);
            });

            $('body').on('click', '.js-remove-quiz-question', function (e) {
                e.preventDefault();
                $(this).closest('.quiz-question-item').remove();
            });

            $('body').on('change', '.js-quiz-question-type', function () {
                const $questionItem = $(this).closest('.quiz-question-item');
                refreshQuestionTypeUI($questionItem);
                updateQuestionSummary($questionItem);
            });

            $('body').on('input', '.quiz-question-item textarea[name$="[title]"]', function () {
                updateQuestionSummary($(this).closest('.quiz-question-item'));
            });

            $('body').on('click', '.js-toggle-quiz-question', function (e) {
                e.preventDefault();

                const $questionItem = $(this).closest('.quiz-question-item');
                const isExpanded = $(this).attr('aria-expanded') === 'true';

                setQuestionCollapsed($questionItem, isExpanded);
            });

            $('body').on('click', '.js-add-quiz-option', function (e) {
                e.preventDefault();

                const $questionItem = $(this).closest('.quiz-question-item');
                const $builder = $(this).closest('.interactive-quiz-builder');
                const namePrefix = getNamePrefix($builder);
                const questionIndex = $questionItem.data('question-index');
                const answerIndex = getNextDataIndex($questionItem, 'data-next-answer-index', $questionItem.find('.quiz-option-row').length);

                $questionItem.find('.quiz-options-list').append(renderOptionRow(namePrefix, questionIndex, answerIndex));
                refreshQuestionTypeUI($questionItem);
            });

            $('body').on('click', '.js-remove-quiz-option', function (e) {
                e.preventDefault();
                $(this).closest('.quiz-option-row').remove();
            });

            $('body').on('click', '.js-add-quiz-pair', function (e) {
                e.preventDefault();

                const $questionItem = $(this).closest('.quiz-question-item');
                const $builder = $(this).closest('.interactive-quiz-builder');
                const namePrefix = getNamePrefix($builder);
                const questionIndex = $questionItem.data('question-index');
                const pairIndex = getNextDataIndex($questionItem, 'data-next-pair-index', $questionItem.find('.quiz-pair-row').length);

                $questionItem.find('.quiz-pairs-list').append(renderPairRow(namePrefix, questionIndex, pairIndex));
            });

            $('body').on('click', '.js-remove-quiz-pair', function (e) {
                e.preventDefault();
                $(this).closest('.quiz-pair-row').remove();
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
                let index = 0;

                $list.find('.lecture-note-item').each(function () {
                    const current = parseInt($(this).data('note-index'), 10);
                    if (!Number.isNaN(current) && current >= index) {
                        index = current + 1;
                    }
                });

                $list.append(renderLectureNoteItem(namePrefix, index));
            });

            $('body').on('click', '.js-remove-lecture-note', function (e) {
                e.preventDefault();
                $(this).closest('.lecture-note-item').remove();
            });

            $('.interactive-quiz-builder .quiz-question-item').each(function () {
                const $questionItem = $(this);
                refreshQuestionTypeUI($questionItem);
                updateQuestionSummary($questionItem);
                setQuestionCollapsed($questionItem, true);
            });

        })(jQuery);
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    <script src="/assets/design_1/js/panel/quiz_create.min.js"></script>
@endpush
