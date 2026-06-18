@php
    $questionType = $questionType ?? (!empty($question_edit) ? $question_edit->type : \App\Models\QuizzesQuestion::$trueFalseNotGiven);
    $questionData = !empty($question_edit) && !empty($question_edit->question_data) ? $question_edit->question_data : [];
    $promptValue = data_get($questionData, 'prompt', '');
    $correctAnswerValue = data_get($questionData, 'correct_answer', '');
    $isYesNo = ($questionType === \App\Models\QuizzesQuestion::$yesNoNotGiven);
    $defaultOptions = $isYesNo ? ['YES', 'NO', 'NOT GIVEN'] : ['TRUE', 'FALSE', 'NOT GIVEN'];
    $optionsValue = data_get($questionData, 'options', $defaultOptions);
    if (is_array($optionsValue)) {
        $optionsValue = implode("\n", $optionsValue);
    }
@endphp

<div class="@if(!empty($quiz)) booleanQuestionModal{{ $quiz->id }} @endif {{ empty($question_edit) && !request()->ajax() ? 'd-none' : ''}}">
    <div class="custom-modal-body p-16">
        <div class="quiz-questions-form" data-action="/panel/quizzes-questions/{{ empty($question_edit) ? 'store' : $question_edit->id.'/update' }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="ajax[quiz_id]" value="{{ !empty($quiz) ? $quiz->id :'' }}">
            <input type="hidden" name="ajax[type]" value="{{ $questionType }}">

            <div class="row mt-24">
                <div class="col-12">
                    @include('design_1.panel.includes.locale.locale_select',[ 'itemRow' => !empty($question_edit) ? $question_edit : null, 'withoutReloadLocale' => true, 'extraClass' => 'js-quiz-question-locale' ])
                </div>

                <div class="col-12 col-md-8">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('quiz.question_title') }}</label>
                        <input type="text" name="ajax[title]" class="js-ajax-title form-control" value="{{ !empty($question_edit) ? $question_edit->title : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('quiz.grade') }}</label>
                        <input type="text" name="ajax[grade]" class="js-ajax-grade form-control" value="{{ !empty($question_edit) ? $question_edit->grade : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.negative_grade') }}</label>
                        <input type="text" name="ajax[negative_grade]" class="js-ajax-negative_grade form-control" value="{{ !empty($question_edit) ? $question_edit->negative_grade : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('quiz.question_prompt') }}</label>
                        <textarea name="ajax[question_data][prompt]" rows="5" class="js-ajax-question-data-prompt form-control">{{ $promptValue }}</textarea>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label">Options</label>
                        <textarea name="ajax[question_data][options]" rows="4" class="js-ajax-question-data-options form-control">{{ $optionsValue }}</textarea>
                        <p class="font-12 text-gray-500 mt-4">One option per line.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label">Correct answer</label>
                            <input type="text" name="ajax[question_data][correct_answer]" class="js-ajax-question-data-correct-answer form-control" value="{{ $correctAnswerValue }}" placeholder="TRUE, FALSE, YES, NO, NOT GIVEN">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="save-question btn btn-sm btn-primary">{{ trans('public.save') }}</button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-2">{{ trans('public.close') }}</button>
            </div>
        </div>
    </div>
</div>