{{-- Sentence Completion Question Type --}}
@php
    $sentences = $question->sentences ?? [['text' => $question->content]];
@endphp

<div class="idp-question-group" data-group-id="{{ $question->id }}">
    @foreach($sentences as $index => $sentence)
        @php
            $qNum = $sentence['number'] ?? ($question->order_number ?? 1) + $index;
            $sentenceId = $sentence['id'] ?? $question->id . '_' . $index;
            $userAnswer = $userAnswers[$sentenceId] ?? ($userAnswer ?? '');
            $sentenceText = is_array($sentence) ? ($sentence['text'] ?? '') : $sentence;
        @endphp
        
        <div class="idp-question" data-question-id="{{ $sentenceId }}" style="margin-bottom: 16px;">
            <span class="idp-question-number">{{ $qNum }}</span>
            
            <span class="idp-sentence-completion">
                @php
                    // Split sentence by blank markers
                    $parts = preg_split('/\[BLANK\]|\{\{BLANK\}\}|___+|\.{3,}/', $sentenceText);
                    $blankCount = count($parts) - 1;
                @endphp
                
                @foreach($parts as $partIndex => $part)
                    <span>{{ $part }}</span>
                    @if($partIndex < $blankCount)
                        <input type="text" 
                               class="idp-text-input"
                               id="answer_{{ $sentenceId }}_{{ $partIndex }}"
                               name="question_{{ $sentenceId }}[]"
                               value="{{ is_array($userAnswer) ? ($userAnswer[$partIndex] ?? '') : $userAnswer }}"
                               placeholder="{{ $qNum }}"
                               onblur="saveSentenceAnswer('{{ $sentenceId }}')">
                    @endif
                @endforeach
            </span>
        </div>
    @endforeach
</div>

<script>
function saveSentenceAnswer(questionId) {
    const inputs = document.querySelectorAll(`input[name="question_${questionId}[]"]`);
    const values = Array.from(inputs).map(input => input.value.trim());
    saveAnswer(questionId, values.length === 1 ? values[0] : values);
}
</script>
