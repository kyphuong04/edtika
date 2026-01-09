{{-- Drag & Drop with Word Bank - IDP Style --}}
{{-- Word bank chips at top, drop zones below --}}

@php
    $wordBankItems = is_array($wordBank) ? $wordBank : json_decode($wordBank ?? '[]', true);
@endphp

@if(!empty($wordBankItems))
    <div class="idp-word-bank" id="wordBank">
        @foreach($wordBankItems as $idx => $word)
            <span class="idp-word" draggable="true" data-word="{{ $word }}" id="word_{{ $idx }}">{{ $word }}</span>
        @endforeach
    </div>
@endif

<div style="margin-top: 16px;">
    @foreach($questions as $q)
        @php
            $qNum = $q->question_number ?? $loop->iteration;
            $saved = $userAnswers[$q->id] ?? '';
            $text = $q->question_text ?? $q->content ?? '';
        @endphp
        <div class="idp-question" data-q-num="{{ $qNum }}" style="margin-bottom: 10px;">
            @if(!empty($text))
                <span class="idp-q-num">{{ $qNum }}</span>
                <span class="idp-q-text">{{ $text }}</span>
                <div class="idp-drop-zone" 
                     data-qid="{{ $q->id }}"
                     ondrop="handleDrop(event)" 
                     ondragover="event.preventDefault()"
                     style="min-width: 100px; min-height: 30px; border: 2px dashed #0066CC; 
                            display: inline-flex; align-items: center; padding: 4px 10px; margin-left: 8px;
                            background: {{ !empty($saved) ? '#d4edff' : '#fff' }};">
                    {{ $saved }}
                </div>
            @else
                <span class="idp-q-num">{{ $qNum }}</span>
                <div class="idp-drop-zone" 
                     data-qid="{{ $q->id }}"
                     ondrop="handleDrop(event)" 
                     ondragover="event.preventDefault()"
                     style="min-width: 120px; min-height: 30px; border: 2px dashed #0066CC; 
                            display: inline-flex; align-items: center; padding: 4px 10px;
                            background: {{ !empty($saved) ? '#d4edff' : '#fff' }};">
                    {{ $saved }}
                </div>
            @endif
        </div>
    @endforeach
</div>

<script>
document.querySelectorAll('.idp-word').forEach(word => {
    word.addEventListener('dragstart', e => {
        e.dataTransfer.setData('text/plain', word.dataset.word);
        e.dataTransfer.setData('wordId', word.id);
    });
});

function handleDrop(e) {
    e.preventDefault();
    const word = e.dataTransfer.getData('text/plain');
    const wordId = e.dataTransfer.getData('wordId');
    const zone = e.target.closest('.idp-drop-zone');
    const qId = zone.dataset.qid;
    
    // Clear previous content
    zone.textContent = word;
    zone.style.background = '#d4edff';
    
    // Mark word as used
    const wordEl = document.getElementById(wordId);
    if(wordEl) wordEl.classList.add('used');
    
    // Save answer
    saveAnswer(parseInt(qId), word);
}
</script>
