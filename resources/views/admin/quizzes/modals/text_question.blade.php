@php
    $questionType = $questionType ?? (!empty($question_edit) ? $question_edit->type : \App\Models\QuizzesQuestion::$fillBlank);
    $questionData = !empty($question_edit) && !empty($question_edit->question_data) ? $question_edit->question_data : [];
    $promptValue = data_get($questionData, 'prompt', '');
    $acceptedAnswersValue = data_get($questionData, 'accepted_answers', '');
    $blankCountValue = data_get($questionData, 'blank_count', 1);
    $gradingModeValue = data_get($questionData, 'grading_mode', 'auto');
    $caseSensitiveChecked = (bool) data_get($questionData, 'case_sensitive', false);
    $isRewriteSentence = ($questionType === \App\Models\QuizzesQuestion::$rewriteSentence);
    $questionLabel = match ($questionType) {
        \App\Models\QuizzesQuestion::$rewriteSentence => trans('quiz.add_rewrite_sentence'),
        \App\Models\QuizzesQuestion::$sentenceCompletion => 'Sentence Completion',
        \App\Models\QuizzesQuestion::$shortAnswer => 'Short Answer',
        default => trans('quiz.add_fill_blank'),
    };
    $questionHint = match ($questionType) {
        \App\Models\QuizzesQuestion::$rewriteSentence => trans('quiz.rewrite_sentence'),
        \App\Models\QuizzesQuestion::$sentenceCompletion => 'Write the missing words in the sentence.',
        \App\Models\QuizzesQuestion::$shortAnswer => 'Write a short answer based on the prompt.',
        default => trans('quiz.fill_blank'),
    };
@endphp

@php
    $modalSuffix = match ($questionType) {
        \App\Models\QuizzesQuestion::$rewriteSentence => 'Rewrite',
        \App\Models\QuizzesQuestion::$sentenceCompletion => 'SentenceCompletion',
        \App\Models\QuizzesQuestion::$shortAnswer => 'ShortAnswer',
        default => 'FillBlank',
    };
@endphp

<div class="@if(!empty($quiz)) textQuestionModal{{ $quiz->id }}{{ $modalSuffix }} @endif {{ empty($question_edit) && !request()->ajax() ? 'd-none' : ''}}">
    <div class="custom-modal-body">
        <h2 class="section-title after-line">{{ $questionLabel }}</h2>

        <div class="quiz-questions-form" data-action="{{ getAdminPanelUrl() }}/quizzes-questions/{{ empty($question_edit) ? 'store' : $question_edit->id.'/update' }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="ajax[quiz_id]" value="{{ !empty($quiz) ? $quiz->id :'' }}">
            <input type="hidden" name="ajax[type]" value="{{ $questionType }}">

            <div class="row mt-3">
                @if(!empty(getGeneralSettings('content_translate')))
                    <div class="col-12">
                        <div class="form-group">
                            <label class="input-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[locale]"
                                    class="form-control {{ !empty($question_edit) ? 'js-quiz-question-locale' : '' }}"
                                    data-id="{{ !empty($question_edit) ? $question_edit->id : '' }}"
                            >
                                @foreach($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}" {{ (!empty($question_edit) and !empty($question_edit->locale)) ? (mb_strtolower($question_edit->locale) == mb_strtolower($lang) ? 'selected' : '') : (app()->getLocale() == $lang ? 'selected' : '') }}>{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="ajax[locale]" value="{{ $defaultLocale }}">
                @endif

                <div class="col-12 col-md-8">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.question_title') }}</label>
                        <textarea type="text" name="ajax[title]" class="js-ajax-title form-control" rows="1">{{ !empty($question_edit) ? $question_edit->title : '' }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.grade') }}</label>
                        <input type="text" name="ajax[grade]" class="js-ajax-grade form-control" value="{{ !empty($question_edit) ? $question_edit->grade : '' }}"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="input-label">{{ trans('update.negative_grade') }}</label>
                        <input type="text" name="ajax[negative_grade]" class="js-ajax-negative_grade form-control" value="{{ !empty($question_edit) ? $question_edit->negative_grade : '' }}"/>
                        <div class="invalid-feedback"></div>
                        <p class="font-12 text-gray-500 mt-4">{{ trans('update.leave_empty_for_no_negative') }}</p>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.question_prompt') }}</label>
                        <textarea name="ajax[question_data][prompt]" rows="5" class="js-ajax-question-data-prompt form-control">{{ $promptValue }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.blank_count') }}</label>
                        <input type="number" name="ajax[question_data][blank_count]" min="1" value="{{ $blankCountValue }}" class="js-ajax-question-data-blank-count form-control"/>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.grading_mode') }}</label>
                        <select name="ajax[question_data][grading_mode]" class="js-ajax-question-data-grading-mode form-control">
                            <option value="auto" {{ $gradingModeValue === 'auto' ? 'selected' : '' }}>{{ trans('quiz.auto_grade') }}</option>
                            <option value="manual" {{ $gradingModeValue === 'manual' ? 'selected' : '' }}>{{ trans('quiz.manual_review') }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="input-label">Case sensitive</label>
                        <div class="custom-control custom-switch mt-8">
                            <input id="caseSensitiveSwitch{{ !empty($question_edit) ? $question_edit->id : 'record' }}" type="checkbox" name="ajax[question_data][case_sensitive]" value="1" class="custom-control-input js-ajax-question-data-case-sensitive" {{ $caseSensitiveChecked ? 'checked' : '' }}>
                            <label class="custom-control-label" for="caseSensitiveSwitch{{ !empty($question_edit) ? $question_edit->id : 'record' }}"></label>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.accepted_answers') }}</label>
                        <textarea name="ajax[question_data][accepted_answers]" class="js-ajax-question-data-accepted-answers form-control" rows="6">{{ $acceptedAnswersValue }}</textarea>
                        <p class="font-12 text-gray-500 mt-4">{{ $questionHint }}</p>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-end mt-3">
                <button type="button" class="save-question btn btn-sm btn-primary">{{ trans('public.save') }}</button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-2">{{ trans('public.close') }}</button>
            </div>
        </div>
    </div>
</div>
