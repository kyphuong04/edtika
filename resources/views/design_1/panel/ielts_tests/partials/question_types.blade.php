{{-- 
    IDP-Style Question Type Renderers
    Renders answer inputs based on question type
    
    NOTE: This partial is included inside parent Alpine component (idpTestApp)
    So we can directly access: answers, saveAnswer(), countWords() from parent
--}}

@php
    $questionId = $question->id;
    $questionType = $question->question_type;
    $answerOptions = $question->answer_options ?? [];
    
    // Parse JSON if needed
    if (is_string($answerOptions)) {
        $answerOptions = json_decode($answerOptions, true) ?? [];
    }
@endphp

{{-- MULTIPLE CHOICE --}}
@if($questionType === 'multiple_choice')
    <div class="idp-answer-options">
        @foreach($answerOptions as $index => $option)
            @php $escapedOption = addslashes($option); @endphp
            <label class="idp-answer-option" 
                   :class="{'idp-answer-option--selected': answers[{{ $questionId }}] === '{{ $escapedOption }}'}"
                   @click="saveAnswer({{ $questionId }}, '{{ $escapedOption }}')">
                <input type="radio" 
                       name="q_{{ $questionId }}" 
                       value="{{ $option }}"
                       :checked="answers[{{ $questionId }}] === '{{ $escapedOption }}'"
                       style="display: none;">
                <span class="idp-answer-label">{{ chr(65 + $index) }}</span>
                <span class="idp-answer-text">{{ $option }}</span>
            </label>
        @endforeach
    </div>

{{-- TRUE/FALSE --}}
@elseif($questionType === 'true_false')
    <div class="idp-tfng-options">
        <div class="idp-tfng-option" 
             :class="{'idp-tfng-option--selected': answers[{{ $questionId }}] === 'True'}"
             @click="saveAnswer({{ $questionId }}, 'True')">
            TRUE
        </div>
        <div class="idp-tfng-option" 
             :class="{'idp-tfng-option--selected': answers[{{ $questionId }}] === 'False'}"
             @click="saveAnswer({{ $questionId }}, 'False')">
            FALSE
        </div>
    </div>

{{-- TRUE/FALSE/NOT GIVEN --}}
@elseif($questionType === 'true_false_not_given')
    <div class="idp-tfng-options">
        <div class="idp-tfng-option" 
             :class="{'idp-tfng-option--selected': answers[{{ $questionId }}] === 'True'}"
             @click="saveAnswer({{ $questionId }}, 'True')">
            TRUE
        </div>
        <div class="idp-tfng-option" 
             :class="{'idp-tfng-option--selected': answers[{{ $questionId }}] === 'False'}"
             @click="saveAnswer({{ $questionId }}, 'False')">
            FALSE
        </div>
        <div class="idp-tfng-option" 
             :class="{'idp-tfng-option--selected': answers[{{ $questionId }}] === 'Not Given'}"
             @click="saveAnswer({{ $questionId }}, 'Not Given')">
            NOT GIVEN
        </div>
    </div>

{{-- YES/NO/NOT GIVEN --}}
@elseif($questionType === 'yes_no_not_given')
    <div class="idp-tfng-options">
        <div class="idp-tfng-option" 
             :class="{'idp-tfng-option--selected': answers[{{ $questionId }}] === 'Yes'}"
             @click="saveAnswer({{ $questionId }}, 'Yes')">
            YES
        </div>
        <div class="idp-tfng-option" 
             :class="{'idp-tfng-option--selected': answers[{{ $questionId }}] === 'No'}"
             @click="saveAnswer({{ $questionId }}, 'No')">
            NO
        </div>
        <div class="idp-tfng-option" 
             :class="{'idp-tfng-option--selected': answers[{{ $questionId }}] === 'Not Given'}"
             @click="saveAnswer({{ $questionId }}, 'Not Given')">
            NOT GIVEN
        </div>
    </div>

{{-- FILL IN THE BLANK / SHORT ANSWER --}}
@elseif(in_array($questionType, ['fill_blank', 'short_answer', 'sentence_completion', 'summary_completion', 'note_completion', 'table_completion', 'flow_chart_completion', 'diagram_labelling']))
    <div class="idp-text-input-wrapper">
        <input type="text" 
               class="idp-text-input"
               :value="answers[{{ $questionId }}] || ''"
               @input.debounce.500ms="saveAnswer({{ $questionId }}, $event.target.value)"
               placeholder="Type your answer here..."
               autocomplete="off"
               spellcheck="false">
        @if($question->max_words)
            <small class="idp-word-limit">Maximum {{ $question->max_words }} word(s)</small>
        @endif
    </div>

{{-- MATCHING --}}
@elseif(in_array($questionType, ['matching', 'matching_headings', 'matching_features', 'matching_sentence_endings', 'matching_information']))
    <div class="idp-matching-container">
        @php
            $matchingOptions = $question->matching_options ?? $answerOptions;
            if (is_string($matchingOptions)) {
                $matchingOptions = json_decode($matchingOptions, true) ?? [];
            }
            // Default to A-H if no options
            if (empty($matchingOptions)) {
                $matchingOptions = range('A', 'H');
            }
        @endphp
        
        <select class="idp-matching-select"
                :value="answers[{{ $questionId }}] || ''"
                @change="saveAnswer({{ $questionId }}, $event.target.value)">
            <option value="">-</option>
            @foreach($matchingOptions as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>
    </div>

{{-- ESSAY / WRITING TASK --}}
@elseif(in_array($questionType, ['essay', 'essay_task1', 'essay_task2', 'writing']))
    <div class="idp-essay-container">
        <textarea class="idp-essay-textarea"
                  :value="answers[{{ $questionId }}] || ''"
                  @input.debounce.1000ms="saveAnswer({{ $questionId }}, $event.target.value)"
                  placeholder="Write your answer here..."
                  spellcheck="true"></textarea>
        
        <div class="idp-word-counter">
            <span>
                Word count: 
                <span class="idp-word-count" 
                      :class="{
                          'idp-word-count--warning': countWords(answers[{{ $questionId }}] || '') < {{ $question->min_words ?? 150 }},
                          'idp-word-count--success': countWords(answers[{{ $questionId }}] || '') >= {{ $question->min_words ?? 150 }}
                      }"
                      x-text="countWords(answers[{{ $questionId }}] || '')">0</span>
                @if($question->min_words)
                    / minimum {{ $question->min_words }}
                @endif
            </span>
            @if($question->max_words)
                <span>Maximum: {{ $question->max_words }} words</span>
            @endif
        </div>
    </div>

{{-- MULTIPLE ANSWERS (Checkboxes) --}}
@elseif($questionType === 'multiple_answers')
    <div class="idp-answer-options">
        @foreach($answerOptions as $index => $option)
            @php 
                $escapedOption = addslashes($option);
            @endphp
            <label class="idp-answer-option" 
                   :class="{'idp-answer-option--selected': (answers[{{ $questionId }}] || '').includes('{{ $escapedOption }}')}"
                   @click="
                       let current = answers[{{ $questionId }}] || '';
                       let arr = current ? current.split(', ').filter(x => x) : [];
                       let idx = arr.indexOf('{{ $escapedOption }}');
                       if (idx === -1) arr.push('{{ $escapedOption }}');
                       else arr.splice(idx, 1);
                       saveAnswer({{ $questionId }}, arr.join(', '));
                   ">
                <input type="checkbox" 
                       :checked="(answers[{{ $questionId }}] || '').includes('{{ $escapedOption }}')"
                       style="margin-right: 12px;">
                <span class="idp-answer-label">{{ chr(65 + $index) }}</span>
                <span class="idp-answer-text">{{ $option }}</span>
            </label>
        @endforeach
        @if($question->max_answers)
            <small class="text-muted mt-2 d-block">Select up to {{ $question->max_answers }} answer(s)</small>
        @endif
    </div>

{{-- LIST SELECTION (Dropdown) --}}
@elseif($questionType === 'list_selection')
    <div class="idp-dropdown-wrapper">
        <select class="idp-text-input"
                :value="answers[{{ $questionId }}] || ''"
                @change="saveAnswer({{ $questionId }}, $event.target.value)">
            <option value="">-- Select an answer --</option>
            @foreach($answerOptions as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>
    </div>

{{-- MAP/PLAN/DIAGRAM LABELLING --}}
@elseif($questionType === 'map_labelling')
    <div class="idp-matching-container">
        @php
            $labelOptions = $question->label_options ?? range('A', 'Z');
            if (is_string($labelOptions)) {
                $labelOptions = json_decode($labelOptions, true) ?? range('A', 'Z');
            }
        @endphp
        
        <select class="idp-matching-select"
                :value="answers[{{ $questionId }}] || ''"
                @change="saveAnswer({{ $questionId }}, $event.target.value)">
            <option value="">-</option>
            @foreach(array_slice($labelOptions, 0, 12) as $label)
                <option value="{{ $label }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

{{-- DEFAULT FALLBACK --}}
@else
    <div class="idp-text-input-wrapper">
        <input type="text" 
               class="idp-text-input"
               :value="answers[{{ $questionId }}] || ''"
               @input.debounce.500ms="saveAnswer({{ $questionId }}, $event.target.value)"
               placeholder="Enter your answer..."
               autocomplete="off">
    </div>
@endif

<style>
/* Additional styles for question types */
.idp-word-limit {
    display: block;
    margin-top: 8px;
    color: #666;
    font-size: 13px;
}

.idp-dropdown-wrapper select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    padding-right: 40px;
}

.text-muted {
    color: #6b7280;
}

.d-block {
    display: block;
}
</style>
