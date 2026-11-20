{{--
    GĐ7 - Câu hỏi của một quiz để làm ngay trên trang học (Mini Quiz / Mid-term / Final).
    Tên input giữ đúng như trang làm quiz của Rocket (question[ID][answer]...) nên chấm điểm dùng chung.
    Biến: $quiz, $quizQuestions
--}}
<div class="edk-iq-questions">
    @foreach($quizQuestions as $questionIndex => $question)
        @php
            $questionData = $question->question_data ?? [];
            $blankCount = max(1, (int) data_get($questionData, 'blank_count', 1));
            $booleanOptions = data_get($questionData, 'options', []);
            $matchingItems = data_get($questionData, 'items', []);
            $matchingOptions = data_get($questionData, 'options', []);
        @endphp

        <section class="edk-iq-q js-iq-question" data-question-id="{{ $question->id }}">
            <div class="edk-iq-q-head">
                <strong>{{ str_pad($questionIndex + 1, 2, '0', STR_PAD_LEFT) }}. {{ $question->title }}</strong>
                <span class="edk-iq-grade">{{ $question->grade }} {{ trans('bundle_learning.inline_quiz_points') }}</span>
            </div>

            @if(!empty($question->image))
                <div class="edk-iq-media"><img src="{{ $question->image }}" alt=""></div>
            @elseif(!empty($question->video))
                <div class="edk-iq-media">
                    <video controls preload="metadata" playsinline controlsList="nodownload" oncontextmenu="return false;">
                        <source src="{{ $question->video }}" type="video/mp4"/>
                    </video>
                </div>
            @endif

            @if(!empty(data_get($questionData, 'prompt')))
                <div class="edk-iq-prompt">{!! nl2br(e(data_get($questionData, 'prompt'))) !!}</div>
            @endif

            @if($question->type === \App\Models\QuizzesQuestion::$descriptive)
                <textarea name="question[{{ $question->id }}][answer]" rows="6" class="edk-iq-input" placeholder="{{ trans('update.your_answer') }}"></textarea>

            @elseif($question->isTextQuestion())
                @if($blankCount > 1)
                    <div class="edk-iq-blanks">
                        @for($blankIndex = 1; $blankIndex <= $blankCount; $blankIndex++)
                            <input type="text" name="question[{{ $question->id }}][answer][{{ $blankIndex }}]" class="edk-iq-input" placeholder="{{ trans('bundle_learning.inline_quiz_blank', ['number' => $blankIndex]) }}" autocomplete="off">
                        @endfor
                    </div>
                @else
                    <input type="text" name="question[{{ $question->id }}][answer]" class="edk-iq-input" placeholder="{{ trans('update.your_answer') }}" autocomplete="off">
                @endif

            @elseif(in_array($question->type, [\App\Models\QuizzesQuestion::$trueFalseNotGiven, \App\Models\QuizzesQuestion::$yesNoNotGiven]))
                @php
                    $options = is_array($booleanOptions) && !empty($booleanOptions) ? $booleanOptions : ($question->type === \App\Models\QuizzesQuestion::$yesNoNotGiven ? ['YES', 'NO', 'NOT GIVEN'] : ['TRUE', 'FALSE', 'NOT GIVEN']);
                @endphp
                <div class="quiz-options">
                    @foreach($options as $option)
                        <label class="quiz-option"><input type="radio" name="question[{{ $question->id }}][answer]" value="{{ $option }}"><span>{{ $option }}</span></label>
                    @endforeach
                </div>

            @elseif($question->isMatchingQuestion())
                @php
                    $items = is_array($matchingItems) ? $matchingItems : preg_split('/\r\n|\r|\n/', (string) $matchingItems, -1, PREG_SPLIT_NO_EMPTY);
                    $options = is_array($matchingOptions) ? $matchingOptions : preg_split('/\r\n|\r|\n/', (string) $matchingOptions, -1, PREG_SPLIT_NO_EMPTY);
                @endphp
                <div class="edk-iq-matching">
                    @foreach($items as $index => $item)
                        <label class="edk-iq-match-row">
                            <span>{{ $item }}</span>
                            <select name="question[{{ $question->id }}][answer][{{ $index }}]" class="edk-iq-input">
                                <option value="">{{ trans('bundle_learning.inline_quiz_choose') }}</option>
                                @foreach($options as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </label>
                    @endforeach
                </div>

            @else
                <div class="quiz-options">
                    @foreach($question->quizzesQuestionsAnswers as $answer)
                        <label class="quiz-option">
                            <input type="radio" name="question[{{ $question->id }}][answer]" value="{{ $answer->id }}">
                            @if(!empty($answer->image))
                                <img src="{{ url($answer->image) }}" alt="" class="edk-iq-answer-img">
                            @else
                                <span>{{ $answer->title }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            @endif

            <div class="edk-iq-mark js-iq-mark" hidden></div>
        </section>
    @endforeach
</div>
