{{-- Multiple Choice (Multiple Answers) Question Type - Choose TWO, THREE, etc. --}}
<div class="idp-question" data-question-id="{{ $question->id }}" data-question-number="{{ $question->order_number }}">
    <span class="idp-question-number">{{ $question->order_number }}</span>
    <span class="idp-question-text">{!! $question->content !!}</span>
    
    @if(!empty($question->instruction))
        <div style="margin-top: 8px; font-size: 14px; color: #666;">
            {{ $question->instruction }}
        </div>
    @endif
    
    <div class="idp-checkbox-options" style="margin-top: 12px; padding-left: 36px;">
        @foreach($question->options ?? [] as $optionKey => $option)
            @php
                $optionLetter = chr(65 + $loop->index); // A, B, C, D, E...
                $optionText = is_array($option) ? ($option['text'] ?? $option['value'] ?? $option) : $option;
                $selectedAnswers = is_array($userAnswer ?? null) ? $userAnswer : [];
            @endphp
            <div class="idp-checkbox-option">
                <input type="checkbox" 
                       name="question_{{ $question->id }}[]" 
                       id="q{{ $question->id }}_{{ $optionLetter }}"
                       value="{{ $optionLetter }}"
                       {{ in_array($optionLetter, $selectedAnswers) ? 'checked' : '' }}
                       onchange="saveMultipleAnswer({{ $question->id }})">
                <label for="q{{ $question->id }}_{{ $optionLetter }}">
                    <strong>{{ $optionLetter }}</strong> &nbsp; {{ $optionText }}
                </label>
            </div>
        @endforeach
    </div>
</div>

<script>
function saveMultipleAnswer(questionId) {
    const checkboxes = document.querySelectorAll(`input[name="question_${questionId}[]"]:checked`);
    const values = Array.from(checkboxes).map(cb => cb.value);
    saveAnswer(questionId, values);
}
</script>
