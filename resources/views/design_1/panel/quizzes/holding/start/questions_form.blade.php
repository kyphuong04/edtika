@foreach($quizQuestions as $key => $question)
    @php
        $questionData = $question->question_data ?? [];
        $blankCount = max(1, (int) data_get($questionData, 'blank_count', 1));
        $booleanOptions = data_get($questionData, 'options', []);
        $matchingItems = data_get($questionData, 'items', []);
        $matchingOptions = data_get($questionData, 'options', []);
    @endphp

    <fieldset class="question-step question-step-{{ $key + 1 }}">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="font-weight-bold font-16">{{ $question->title }}</h3>

            <div class="d-flex-center text-gray-500 font-12">
                <x-iconsax-lin-verify class="icons" width="16px" height="16px"/>
                <span class="ml-4">{{ $question->grade }}</span>
            </div>
        </div>

        @if(!empty($question->image) or !empty($question->video))
            <div class="quiz-question-media-card rounded-16 my-32">
                @if(!empty($question->image))
                    <img src="{{ $question->image }}" class="img-cover rounded-16" alt="">
                @else
                    <video id="questionVideo{{ $question->id }}" class="js-init-plyr-io plyr-io-video" oncontextmenu="return false;" controlsList="nodownload" controls preload="auto" width="100%" data-setup='{"fluid": true}'>
                        <source src="{{ $question->video }}" type="video/mp4"/>
                    </video>
                @endif
            </div>
        @endif

        @if(!empty(data_get($questionData, 'prompt')))
            <div class="bg-gray-100 rounded-16 p-16 mt-24">
                <div class="font-14 text-dark">
                    {!! nl2br(e(data_get($questionData, 'prompt'))) !!}
                </div>
            </div>
        @endif

        @if($question->type === \App\Models\QuizzesQuestion::$descriptive)
            <div class="form-group mt-24 mb-0">
                <label class="form-group-label">{{ trans('update.your_answer') }}</label>
                <textarea name="question[{{ $question->id }}][answer]" rows="15" class="form-control"></textarea>
            </div>
        @elseif($question->isTextQuestion())
            <div class="form-group mt-24 mb-0">
                <label class="form-group-label">{{ trans('update.your_answer') }}</label>

                @if($blankCount > 1)
                    <div class="row">
                        @for($blankIndex = 1; $blankIndex <= $blankCount; $blankIndex++)
                            <div class="col-12 {{ $blankCount > 2 ? 'col-md-6' : '' }} mt-12">
                                <input type="text" name="question[{{ $question->id }}][answer][{{ $blankIndex }}]" class="form-control" placeholder="{{ trans('quiz.blank_count') }} {{ $blankIndex }}">
                            </div>
                        @endfor
                    </div>
                @else
                    <textarea name="question[{{ $question->id }}][answer]" rows="8" class="form-control"></textarea>
                @endif
            </div>
        @elseif(in_array($question->type, [\App\Models\QuizzesQuestion::$trueFalseNotGiven, \App\Models\QuizzesQuestion::$yesNoNotGiven]))
            @php
                $options = is_array($booleanOptions) && !empty($booleanOptions) ? $booleanOptions : ($question->type === \App\Models\QuizzesQuestion::$yesNoNotGiven ? ['YES', 'NO', 'NOT GIVEN'] : ['TRUE', 'FALSE', 'NOT GIVEN']);
            @endphp

            <div class="question-multi-answers mt-24">
                @foreach($options as $option)
                    <div class="answer-item">
                        <input id="asw-{{ $question->id }}-{{ $loop->index }}" type="radio" name="question[{{ $question->id }}][answer]" value="{{ $option }}">

                        <label for="asw-{{ $question->id }}-{{ $loop->index }}" class="answer-label d-flex-center text-center p-16 rounded-16 border-gray-200 cursor-pointer w-100 h-100">
                            <span class="font-14 font-weight-bold">{{ $option }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        @elseif($question->isMatchingQuestion())
            @php
                $items = is_array($matchingItems) ? $matchingItems : preg_split('/\r\n|\r|\n/', (string) $matchingItems, -1, PREG_SPLIT_NO_EMPTY);
                $options = is_array($matchingOptions) ? $matchingOptions : preg_split('/\r\n|\r|\n/', (string) $matchingOptions, -1, PREG_SPLIT_NO_EMPTY);
            @endphp

            <div class="form-group mt-24">
                <label class="form-group-label">{{ trans('update.your_answer') }}</label>
                <div class="row">
                    @foreach($items as $index => $item)
                        <div class="col-12 col-md-6 mt-12">
                            <div class="p-16 rounded-16 border-gray-200">
                                <div class="font-14 font-weight-bold mb-8">{{ $item }}</div>
                                <select name="question[{{ $question->id }}][answer][{{ $index }}]" class="form-control">
                                    <option value="">-- Select --</option>
                                    @foreach($options as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="question-multi-answers mt-24">
                @foreach($question->quizzesQuestionsAnswers as $key => $answer)
                    <div class="answer-item">
                        <input id="asw-{{ $answer->id }}" type="radio" name="question[{{ $question->id }}][answer]" value="{{ $answer->id }}">

                        <label for="asw-{{ $answer->id }}" class="answer-label d-flex-center text-center p-16 rounded-16 border-gray-200 cursor-pointer w-100 h-100">
                            @if(!$answer->image)
                                <span class="font-14 font-weight-bold">{{ $answer->title }}</span>
                            @else
                                <div class="image-container rounded-16">
                                    <img src="{{ url($answer->image) }}" class="img-cover rounded-16" alt="">
                                </div>
                            @endif
                        </label>

                    </div>
                @endforeach
            </div>
        @endif

    </fieldset>
@endforeach

