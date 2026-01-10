{{-- Fill in the Blank / Gap Fill / Short Answer Question Type --}}
<div class="idp-question" data-question-id="{{ $question->id }}" data-question-number="{{ $question->order_number }}">
    <span class="idp-question-number">{{ $question->order_number }}</span>
    
    @if(!empty($question->content_before))
        <span style="font-size: 15px; color: #000;">{{ $question->content_before }}</span>
    @endif
    
    <input type="text" 
           class="idp-text-input"
           id="answer_{{ $question->id }}"
           name="question_{{ $question->id }}"
           value="{{ $userAnswer ?? '' }}"
           placeholder="{{ $question->order_number }}"
           onblur="saveAnswer({{ $question->id }}, this.value)"
           onkeyup="autoSave({{ $question->id }}, this.value)">
    
    @if(!empty($question->content_after))
        <span style="font-size: 15px; color: #000;">{{ $question->content_after }}</span>
    @endif
    
    @if(!empty($question->content) && empty($question->content_before) && empty($question->content_after))
        <span class="idp-question-text">{!! $question->content !!}</span>
    @endif
</div>

<script>
let autoSaveTimeout;
function autoSave(questionId, value) {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        saveAnswer(questionId, value);
    }, 500);
}
</script>
