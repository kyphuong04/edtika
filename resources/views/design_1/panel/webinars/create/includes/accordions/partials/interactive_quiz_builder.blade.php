@php
    $interactiveQuizData = !empty($quizData) && is_array($quizData) ? $quizData : [];
    $interactiveQuizQuestions = !empty($interactiveQuizData['questions']) && is_array($interactiveQuizData['questions']) ? $interactiveQuizData['questions'] : [];

    $interactiveQuizTypeOptions = [
        'multiple_choice_single' => 'Single Answer',
        'multiple_choice_multiple' => 'Multiple Answers',
        'true_false_not_given' => 'True / False / Not Given',
        'yes_no_not_given' => 'Yes / No / Not Given',
        'matching_headings' => 'Matching Headings',
        'matching_information' => 'Matching Information',
        'matching_features' => 'Matching Features',
        'matching_sentence_endings' => 'Matching Sentence Endings',
        'sentence_completion' => 'Sentence Completion',
        'summary_completion' => 'Summary Completion',
        'note_completion' => 'Note Completion',
        'table_completion' => 'Table Completion',
    ];

    $fixedTypeAnswers = [
        'true_false_not_given' => [
            'true' => 'True',
            'false' => 'False',
            'not_given' => 'Not Given',
        ],
        'yes_no_not_given' => [
            'yes' => 'Yes',
            'no' => 'No',
            'not_given' => 'Not Given',
        ],
    ];
@endphp

<div class="interactive-quiz-builder" data-name-prefix="{{ $namePrefix }}" data-next-question-index="{{ count($interactiveQuizQuestions) }}">
    <div class="form-group">
        <label class="form-group-label">Quiz title</label>
        <input type="text" name="{{ $namePrefix }}[title]" class="form-control" placeholder="Enter quiz title" value="{{ $interactiveQuizData['title'] ?? '' }}">
    </div>

    <div class="quiz-questions-wrapper">
        <div class="d-flex align-items-center justify-content-between mb-8">
            <label class="form-group-label mb-0">Questions</label>
            <button type="button" class="btn btn-sm btn-outline-primary js-add-quiz-question">
                <x-iconsax-lin-add class="icons" width="14px" height="14px"/>
                Add question
            </button>
        </div>

        <div class="quiz-questions-list">
            @foreach($interactiveQuizQuestions as $questionIndex => $question)
                @php
                    $questionType = $question['type'] ?? 'multiple_choice_single';
                    if (!array_key_exists($questionType, $interactiveQuizTypeOptions)) {
                        $questionType = 'multiple_choice_single';
                    }

                    $questionOptions = [];
                    if (!empty($question['options']) && is_array($question['options'])) {
                        $questionOptions = $question['options'];
                    } elseif (!empty($question['answers']) && is_array($question['answers'])) {
                        $questionOptions = $question['answers'];
                    }

                    $questionPairs = !empty($question['pairs']) && is_array($question['pairs']) ? $question['pairs'] : [];
                    $questionCorrectAnswer = $question['correct_answer'] ?? null;
                    $questionCorrectAnswers = !empty($question['correct_answers']) && is_array($question['correct_answers'])
                        ? $question['correct_answers']
                        : [];
                    $questionAlternativeAnswers = !empty($question['alternative_answers'])
                        ? (is_array($question['alternative_answers']) ? implode("\n", $question['alternative_answers']) : $question['alternative_answers'])
                        : '';
                @endphp
                <div class="quiz-question-item border rounded-8 p-12 mb-12" data-question-index="{{ $questionIndex }}" data-next-answer-index="{{ count($questionOptions) }}" data-next-pair-index="{{ count($questionPairs) }}">
                    <div class="d-flex align-items-center justify-content-between mb-8">
                        <div>
                            <label class="form-group-label mb-0">Question {{ $loop->iteration }}</label>
                            <p class="font-12 text-gray-500 mb-0 js-quiz-question-summary">{{ $question['title'] ?? '' }}</p>
                        </div>

                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-xs btn-outline-secondary mr-8 js-toggle-quiz-question" aria-expanded="false" title="Expand/Collapse">
                                <span class="js-toggle-quiz-question-icon">&#9658;</span>
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-danger js-remove-quiz-question">Remove</button>
                        </div>
                    </div>

                    <div class="quiz-question-body d-none">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-12">
                                <label class="form-group-label mb-6">Question type</label>
                                <select class="form-control js-quiz-question-type" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][type]">
                                    @foreach($interactiveQuizTypeOptions as $typeValue => $typeLabel)
                                        <option value="{{ $typeValue }}" {{ $questionType === $typeValue ? 'selected' : '' }}>{{ $typeLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-12">
                                <label class="form-group-label mb-6">Max words</label>
                                <input type="number" min="1" class="form-control" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][max_words]" value="{{ $question['max_words'] ?? '' }}" placeholder="e.g. 3">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-12">
                                <label class="form-group-label mb-6">Target band</label>
                                <input type="number" min="0" max="9" step="0.5" class="form-control" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][target_band]" value="{{ $question['target_band'] ?? '' }}" placeholder="e.g. 6.5">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-12">
                        <label class="form-group-label mb-6">Question / Prompt</label>
                        <textarea rows="2" class="form-control" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][title]" placeholder="Question text or prompt">{{ $question['title'] ?? '' }}</textarea>
                    </div>

                    <div class="quiz-type-section js-quiz-type-section" data-types="multiple_choice_single,multiple_choice_multiple,matching_headings,matching_information,matching_features,matching_sentence_endings">
                        <div class="quiz-options-wrapper mt-12">
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="form-group-label mb-0">Options / Choices</label>
                                <button type="button" class="btn btn-xs btn-outline-primary js-add-quiz-option">Add option</button>
                            </div>

                            <div class="quiz-options-list mt-8">
                                @foreach($questionOptions as $answerIndex => $answer)
                                    @php
                                        $answerTitle = is_array($answer) ? ($answer['title'] ?? '') : (string) $answer;
                                        $singleChecked = ((string) $questionCorrectAnswer === (string) $answerIndex);
                                        $multipleChecked = in_array((string) $answerIndex, array_map('strval', $questionCorrectAnswers), true);
                                    @endphp
                                    <div class="quiz-option-row d-flex align-items-center mb-8" data-answer-index="{{ $answerIndex }}">
                                        <input type="text" class="form-control form-control-sm" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][options][{{ $answerIndex }}][title]" placeholder="Option text" value="{{ $answerTitle }}">
                                        <div class="custom-control custom-radio ml-8 js-correct-single-wrap {{ $questionType === 'multiple_choice_single' ? '' : 'd-none' }}">
                                            <input type="radio" class="custom-control-input" id="quiz_single_{{ $questionIndex }}_{{ $answerIndex }}" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][correct_answer]" value="{{ $answerIndex }}" {{ $singleChecked ? 'checked' : '' }}>
                                            <label class="custom-control__label cursor-pointer" for="quiz_single_{{ $questionIndex }}_{{ $answerIndex }}">Correct</label>
                                        </div>
                                        <div class="custom-control custom-checkbox ml-8 js-correct-multiple-wrap {{ $questionType === 'multiple_choice_multiple' ? '' : 'd-none' }}">
                                            <input type="checkbox" class="custom-control-input" id="quiz_multi_{{ $questionIndex }}_{{ $answerIndex }}" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][correct_answers][]" value="{{ $answerIndex }}" {{ $multipleChecked ? 'checked' : '' }}>
                                            <label class="custom-control__label cursor-pointer" for="quiz_multi_{{ $questionIndex }}_{{ $answerIndex }}">Correct</label>
                                        </div>
                                        <button type="button" class="btn btn-xs btn-outline-danger ml-8 js-remove-quiz-option">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="quiz-type-section js-quiz-type-section" data-types="true_false_not_given,yes_no_not_given">
                        <div class="form-group mt-12 mb-0">
                            <label class="form-group-label mb-6">Correct answer</label>
                            <select class="form-control js-quiz-fixed-correct-answer" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][correct_answer]">
                                <option value="">Select answer</option>
                                @foreach($fixedTypeAnswers as $fixedType => $fixedOptions)
                                    @foreach($fixedOptions as $fixedValue => $fixedLabel)
                                        <option value="{{ $fixedValue }}" data-for-type="{{ $fixedType }}" {{ (string) $questionCorrectAnswer === (string) $fixedValue ? 'selected' : '' }}>{{ $fixedLabel }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="quiz-type-section js-quiz-type-section" data-types="matching_headings,matching_information,matching_features,matching_sentence_endings">
                        <div class="quiz-pairs-wrapper mt-12">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <label class="form-group-label mb-0">Matching pairs</label>
                                <button type="button" class="btn btn-xs btn-outline-primary js-add-quiz-pair">Add pair</button>
                            </div>
                            <div class="quiz-pairs-list">
                                @foreach($questionPairs as $pairIndex => $pair)
                                    <div class="quiz-pair-row d-flex align-items-center mb-8" data-pair-index="{{ $pairIndex }}">
                                        <input type="text" class="form-control form-control-sm" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][pairs][{{ $pairIndex }}][prompt]" placeholder="Left item / statement" value="{{ $pair['prompt'] ?? '' }}">
                                        <input type="text" class="form-control form-control-sm ml-8" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][pairs][{{ $pairIndex }}][answer]" placeholder="Match answer" value="{{ $pair['answer'] ?? '' }}">
                                        <button type="button" class="btn btn-xs btn-outline-danger ml-8 js-remove-quiz-pair">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="quiz-type-section js-quiz-type-section" data-types="sentence_completion,summary_completion,note_completion,table_completion">
                        <div class="form-group mt-12">
                            <label class="form-group-label mb-6">Correct answer</label>
                            <input type="text" class="form-control" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][correct_answer]" value="{{ (is_array($questionCorrectAnswer) ? '' : $questionCorrectAnswer) }}" placeholder="Correct answer">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-group-label mb-6">Alternative answers (one per line)</label>
                            <textarea rows="3" class="form-control" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][alternative_answers]" placeholder="Optional alternatives">{{ $questionAlternativeAnswers }}</textarea>
                        </div>
                    </div>

                    <div class="form-group mt-12 mb-0">
                        <label class="form-group-label mb-6">Answer help / explanation</label>
                        <textarea rows="2" class="form-control" name="{{ $namePrefix }}[questions][{{ $questionIndex }}][explanation]" placeholder="Optional guidance">{{ $question['explanation'] ?? '' }}</textarea>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
