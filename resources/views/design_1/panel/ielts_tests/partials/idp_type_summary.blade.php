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
        $baseQNum = (int) ($q->question_number ?? $loop->iteration);
        $savedRaw = $userAnswers[$q->id] ?? '';
        $text = $q->question_text ?? $q->content ?? '';

        $savedAnswers = [];
        $decodedSaved = is_string($savedRaw) ? json_decode($savedRaw, true) : $savedRaw;

        if (is_array($decodedSaved) && isset($decodedSaved['answers']) && is_array($decodedSaved['answers'])) {
            $savedAnswers = array_values(array_map(static function ($item) {
                return is_array($item) ? ($item['answer'] ?? '') : (string) $item;
            }, $decodedSaved['answers']));
        } elseif (is_array($decodedSaved)) {
            $savedAnswers = array_values(array_map(static function ($item) {
                return is_array($item) ? ($item['answer'] ?? '') : (string) $item;
            }, $decodedSaved));
        } elseif (is_string($savedRaw) && str_contains($savedRaw, '|')) {
            $savedAnswers = array_map('trim', explode('|', $savedRaw));
        } elseif (!empty($savedRaw)) {
            $savedAnswers = [(string) $savedRaw];
        }
    @endphp
    <div class="idp-question" data-q-num="{{ $baseQNum }}">
        @if(preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text))
            {{-- Replace blank with input --}}
            <span class="idp-q-text">
                @php $blankIndex = -1; @endphp
                {!! preg_replace_callback(
                    '/_{2,}|\[\s*\d*\s*\]|____/',
                    function() use (&$blankIndex, $q, $baseQNum, $savedAnswers) {
                        $blankIndex++;
                        $blankQNum = $baseQNum + $blankIndex;
                        $value = $savedAnswers[$blankIndex] ?? '';

                        return '<span class="idp-q-num">' . $blankQNum . '</span> ' .
                               '<input type="text" class="idp-input" data-qid="' . $q->id . '" data-q-num="' . $blankQNum . '" data-blank-index="' . $blankIndex . '" value="' . e($value) . '" ' .
                                       'oninput="saveSummaryCompletionAnswer(this)" ' .
                                       'ondrop="dropWord(event, this)" ondragover="event.preventDefault()">';
                    },
                    (string) $text
                ) !!}
            </span>
        @else
            <span class="idp-q-num">{{ $baseQNum }}</span>
            <input type="text" class="idp-input" data-qid="{{ $q->id }}" data-q-num="{{ $baseQNum }}" data-blank-index="0" value="{{ $savedAnswers[0] ?? '' }}"
                   oninput="saveSummaryCompletionAnswer(this)"
                   ondrop="dropWord(event, this)" ondragover="event.preventDefault()">
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

function dropWord(e, inputEl) {
    e.preventDefault();
    const word = e.dataTransfer.getData('text/plain');
    if (!inputEl) {
        return;
    }

    inputEl.value = word;
    if (typeof window.saveSummaryCompletionAnswer === 'function') {
        window.saveSummaryCompletionAnswer(inputEl);
    }

    // Mark word as used
    document.querySelectorAll(`.idp-word[data-word="${word}"]`).forEach(w => w.classList.add('used'));
}

if (typeof window.saveSummaryCompletionAnswer !== 'function') {
    window.saveSummaryCompletionAnswer = function (el) {
        const questionId = el.dataset.qid;
        if (!questionId || typeof saveAnswer !== 'function') {
            return;
        }

        const inputs = Array.from(document.querySelectorAll(`.idp-input[data-qid="${questionId}"][data-blank-index]`))
            .sort((a, b) => Number(a.dataset.blankIndex || 0) - Number(b.dataset.blankIndex || 0));

        const values = inputs.map((input) => input.value || '');
        const serialized = values.join('|');
        const qNum = parseInt(el.dataset.qNum || '', 10) || null;

        saveAnswer(questionId, serialized, qNum);
    };
}
</script>
