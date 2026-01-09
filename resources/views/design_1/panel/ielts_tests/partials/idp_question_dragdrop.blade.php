{{-- Drag and Drop / Word Bank Question Type --}}
@php
    $wordBank = $question->word_bank ?? [];
    $sentences = $question->sentences ?? [];
@endphp

<div class="idp-question-group" data-group-id="{{ $question->id }}">
    {{-- Word Bank --}}
    <div class="idp-word-bank" id="wordBank_{{ $question->id }}">
        @foreach($wordBank as $index => $word)
            <div class="idp-word-item" 
                 draggable="true"
                 data-word="{{ $word }}"
                 id="word_{{ $question->id }}_{{ $index }}">
                {{ $word }}
            </div>
        @endforeach
    </div>
    
    {{-- Sentences with Drop Zones --}}
    <div class="idp-sentences-container" style="margin-top: 16px;">
        @foreach($sentences as $index => $sentence)
            @php
                $qNum = $sentence['number'] ?? ($question->start_number + $index);
                $sentenceId = $sentence['id'] ?? $question->id . '_' . $index;
                $userAnswer = $userAnswers[$sentenceId] ?? '';
            @endphp
            <div class="idp-sentence-item" style="margin-bottom: 12px; line-height: 2;">
                <span class="idp-question-number">{{ $qNum }}</span>
                
                @php
                    // Replace [BLANK] with drop zone
                    $sentenceText = $sentence['text'] ?? $sentence;
                    $parts = preg_split('/\[BLANK\]|\{\{BLANK\}\}|___+/', $sentenceText);
                @endphp
                
                @foreach($parts as $partIndex => $part)
                    <span>{{ $part }}</span>
                    @if($partIndex < count($parts) - 1)
                        <span class="idp-drop-zone {{ !empty($userAnswer) ? 'filled' : '' }}" 
                              data-question-id="{{ $sentenceId }}"
                              data-number="{{ $qNum }}"
                              ondrop="handleDrop(event, '{{ $sentenceId }}')"
                              ondragover="handleDragOver(event)">
                            {{ $userAnswer ?? '' }}
                        </span>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<script>
function handleDragOver(event) {
    event.preventDefault();
}

function handleDrop(event, questionId) {
    event.preventDefault();
    const word = event.dataTransfer.getData('text');
    const dropZone = event.target;
    
    // If zone already has content, put it back
    if (dropZone.textContent.trim()) {
        const oldWord = dropZone.textContent.trim();
        // Find and restore old word
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
    
    // Save answer
    saveAnswer(questionId, word);
}

// Make all word items draggable
document.querySelectorAll('.idp-word-item').forEach(item => {
    item.addEventListener('dragstart', function(e) {
        e.dataTransfer.setData('text', this.dataset.word);
    });
});

// Allow clicking drop zone to clear
document.querySelectorAll('.idp-drop-zone').forEach(zone => {
    zone.addEventListener('dblclick', function() {
        if (this.textContent.trim()) {
            const word = this.textContent.trim();
            // Restore word to bank
            document.querySelectorAll('.idp-word-item').forEach(item => {
                if (item.dataset.word === word) {
                    item.classList.remove('used');
                }
            });
            this.textContent = '';
            this.classList.remove('filled');
            saveAnswer(this.dataset.questionId, '');
        }
    });
});
</script>
