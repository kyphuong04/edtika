{{-- Summary Completion with Word Bank - IDP Style --}}
{{-- Word bank at top, then text with blanks (can be drag-drop or input) --}}

@php
    $wordBankItems = is_array($wordBank) ? $wordBank : json_decode($wordBank ?? '[]', true);
@endphp

@if(!empty($wordBankItems))
    <div class="idp-word-bank">
        @foreach($wordBankItems as $word)
            <span class="idp-word" draggable="true" data-word="{{ $word }}">{{ $word }}</span>
        @endforeach
    </div>
@endif

@foreach($questions as $q)
    @php
        $qNum = $q->question_number ?? $loop->iteration;
        $saved = $userAnswers[$q->id] ?? '';
        $text = $q->question_text ?? $q->content ?? '';
    @endphp
    <div class="idp-question" data-q-num="{{ $qNum }}">
        @if(preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text))
            {{-- Replace blank with input --}}
            <span class="idp-q-text">
                {!! preg_replace_callback(
                    '/_{2,}|\[\s*\d*\s*\]|____/',
                    function($m) use ($q, $qNum, $saved) {
                        return '<span class="idp-q-num">' . $qNum . '</span> ' .
                               '<input type="text" class="idp-input" value="' . e($saved) . '" 
                                       oninput="autoSave(' . $q->id . ', this.value)"
                                       ondrop="dropWord(event, ' . $q->id . ')" ondragover="event.preventDefault()">';
                    },
                    e($text),
                    1
                ) !!}
            </span>
        @else
            <span class="idp-q-num">{{ $qNum }}</span>
            <input type="text" class="idp-input" value="{{ $saved }}"
                   oninput="autoSave({{ $q->id }}, this.value)"
                   ondrop="dropWord(event, {{ $q->id }})" ondragover="event.preventDefault()">
        @endif
    </div>
@endforeach

<script>
// Drag and drop for word bank
document.querySelectorAll('.idp-word').forEach(word => {
    word.addEventListener('dragstart', e => {
        e.dataTransfer.setData('text/plain', word.dataset.word);
    });
});

function dropWord(e, qId) {
    e.preventDefault();
    const word = e.dataTransfer.getData('text/plain');
    e.target.value = word;
    autoSave(qId, word);
    // Mark word as used
    document.querySelectorAll(`.idp-word[data-word="${word}"]`).forEach(w => w.classList.add('used'));
}
</script>
