{{-- YES / NO / NOT GIVEN Question Type --}}
<div class="idp-question" data-question-id="{{ $question->id }}" data-question-number="{{ $question->order_number }}">
    <span class="idp-question-number">{{ $question->order_number }}</span>
    <span class="idp-question-text">{!! $question->content !!}</span>
    
    <div class="idp-tfng-options">
        <div class="idp-radio-option">
            <input type="radio" 
                   name="question_{{ $question->id }}" 
                   id="q{{ $question->id }}_yes"
                   value="YES"
                   {{ ($userAnswer ?? '') === 'YES' ? 'checked' : '' }}
                   onchange="saveAnswer({{ $question->id }}, 'YES')">
            <label for="q{{ $question->id }}_yes">YES</label>
        </div>
        <div class="idp-radio-option">
            <input type="radio" 
                   name="question_{{ $question->id }}" 
                   id="q{{ $question->id }}_no"
                   value="NO"
                   {{ ($userAnswer ?? '') === 'NO' ? 'checked' : '' }}
                   onchange="saveAnswer({{ $question->id }}, 'NO')">
            <label for="q{{ $question->id }}_no">NO</label>
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
