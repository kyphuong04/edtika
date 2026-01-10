{{-- TRUE / FALSE / NOT GIVEN Question Type --}}
<div class="idp-question" data-question-id="{{ $question->id }}" data-question-number="{{ $question->order_number }}">
    <span class="idp-question-number">{{ $question->order_number }}</span>
    <span class="idp-question-text">{!! $question->content !!}</span>
    
    <div class="idp-tfng-options">
        <div class="idp-radio-option">
            <input type="radio" 
                   name="question_{{ $question->id }}" 
                   id="q{{ $question->id }}_true"
                   value="TRUE"
                   {{ ($userAnswer ?? '') === 'TRUE' ? 'checked' : '' }}
                   onchange="saveAnswer({{ $question->id }}, 'TRUE')">
            <label for="q{{ $question->id }}_true">TRUE</label>
        </div>
        <div class="idp-radio-option">
            <input type="radio" 
                   name="question_{{ $question->id }}" 
                   id="q{{ $question->id }}_false"
                   value="FALSE"
                   {{ ($userAnswer ?? '') === 'FALSE' ? 'checked' : '' }}
                   onchange="saveAnswer({{ $question->id }}, 'FALSE')">
            <label for="q{{ $question->id }}_false">FALSE</label>
        </div>
        <div class="idp-radio-option">
            <input type="radio" 
                   name="question_{{ $question->id }}" 
                   id="q{{ $question->id }}_ng"
                   value="NOT GIVEN"
                   {{ ($userAnswer ?? '') === 'NOT GIVEN' ? 'checked' : '' }}
                   onchange="saveAnswer({{ $question->id }}, 'NOT GIVEN')">
            <label for="q{{ $question->id }}_ng">NOT GIVEN</label>
        </div>
    </div>
</div>
