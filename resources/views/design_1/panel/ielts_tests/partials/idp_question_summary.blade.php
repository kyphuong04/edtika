{{-- Summary Completion Question Type --}}
@php
    $summaryText = $question->summary_text ?? $question->content ?? '';
@endphp

<div class="idp-question-group" data-group-id="{{ $question->id }}">
    @if(!empty($question->title))
        <div class="idp-note-title" style="margin-bottom: 16px;">{{ $question->title }}</div>
    @endif
    
    {{-- Word Bank (if provided) --}}
    @if(!empty($question->word_bank))
        <div class="idp-word-bank" style="margin-bottom: 16px;">
            @foreach($question->word_bank as $index => $word)
                <div class="idp-word-item" 
                     draggable="true" 
                     data-word="{{ $word }}"
                     id="summary_word_{{ $question->id }}_{{ $index }}">
                    {{ $word }}
                </div>
            @endforeach
        </div>
    @endif
    
    {{-- Summary Text with Blanks --}}
    <div class="idp-summary-text" style="line-height: 2; font-size: 15px;">
        @php
            // Parse summary text and replace blanks with inputs/drop zones
            $blankPattern = '/\[(\d+)\]|\{\{(\d+)\}\}/';
            $parts = preg_split($blankPattern, $summaryText, -1, PREG_SPLIT_DELIM_CAPTURE);
        @endphp
        
        @foreach($parts as $partIndex => $part)
            @if(is_numeric($part))
                @php
                    $qNum = (int) $part;
                    $blankId = $question->id . '_blank_' . $qNum;
                    $userAnswer = $userAnswers[$blankId] ?? '';
                @endphp
                
                <span class="idp-question-number">{{ $qNum }}</span>
                
                @if(!empty($question->word_bank))
                    {{-- Use drop zone for word bank --}}
                    <span class="idp-drop-zone {{ !empty($userAnswer) ? 'filled' : '' }}" 
                          data-question-id="{{ $blankId }}"
                          data-number="{{ $qNum }}"
                          ondrop="handleSummaryDrop(event, '{{ $blankId }}')"
                          ondragover="handleDragOver(event)"
                          ondblclick="clearSummaryDropZone(this, '{{ $blankId }}')"
                          style="min-width: 120px;">
                        {{ $userAnswer }}
                    </span>
                @else
                    {{-- Use text input --}}
                    <input type="text" 
                           class="idp-text-input"
                           id="answer_{{ $blankId }}"
                           name="question_{{ $blankId }}"
                           value="{{ $userAnswer }}"
                           placeholder="{{ $qNum }}"
                           style="width: 120px;"
                           onblur="saveAnswer('{{ $blankId }}', this.value)">
                @endif
            @elseif(!empty($part))
                <span>{{ $part }}</span>
            @endif
        @endforeach
    </div>
</div>

<script>
function handleSummaryDrop(event, questionId) {
    event.preventDefault();
    const word = event.dataTransfer.getData('text');
    const dropZone = event.target;
    
    // If zone already has content, restore the old word
    if (dropZone.textContent.trim()) {
        const oldWord = dropZone.textContent.trim();
        document.querySelectorAll('.idp-word-item.used').forEach(item => {
            if (item.dataset.word === oldWord) {
                item.classList.remove('used');
            }
        });
    }
    
    dropZone.textContent = word;
    dropZone.classList.add('filled');
    
    // Mark word as used
    document.querySelectorAll('.idp-word-item').forEach(item => {
        if (item.dataset.word === word) {
            item.classList.add('used');
        }
    });
    
    saveAnswer(questionId, word);
}

function clearSummaryDropZone(element, questionId) {
    if (element.textContent.trim()) {
        const word = element.textContent.trim();
        document.querySelectorAll('.idp-word-item').forEach(item => {
            if (item.dataset.word === word) {
                item.classList.remove('used');
            }
        });
        element.textContent = '';
        element.classList.remove('filled');
        saveAnswer(questionId, '');
    }
}
</script>
