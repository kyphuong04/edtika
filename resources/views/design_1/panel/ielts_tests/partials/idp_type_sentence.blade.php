{{-- Sentence Completion / Short Answer - IDP Style --}}
{{-- Question number + sentence with blank OR just input after question --}}

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
        
        // Check if text has blank placeholder
        $hasBlank = preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text);
    @endphp
    <div class="idp-question" data-q-num="{{ $baseQNum }}">
        @if($hasBlank)
            {{-- Inline input in sentence --}}
            <span class="idp-q-text">
                @php $blankIndex = -1; @endphp
                {!! preg_replace_callback(
                    '/_{2,}|\[\s*\d*\s*\]|____/',
                    function () use (&$blankIndex, $q, $baseQNum, $savedAnswers) {
                        $blankIndex++;
                        $blankQNum = $baseQNum + $blankIndex;
                        $value = $savedAnswers[$blankIndex] ?? '';

                        return '<span class="idp-q-num">' . $blankQNum . '</span> ' .
                               '<input type="text" class="idp-input idp-input-lg" data-qid="' . $q->id . '" data-q-num="' . $blankQNum . '" data-blank-index="' . $blankIndex . '" value="' . e($value) . '" oninput="saveSentenceCompletionAnswer(this)">';
                    },
                    (string) $text
                ) !!}
            </span>
        @else
            {{-- Question then input --}}
            <div style="margin-bottom: 6px;">
                <span class="idp-q-num">{{ $baseQNum }}</span>
                <span class="idp-q-text">{!! $text !!}</span>
            </div>
            <div style="margin-left: 28px;">
                <input type="text" class="idp-input idp-input-lg" 
                       value="{{ $savedAnswers[0] ?? '' }}"
                       data-qid="{{ $q->id }}"
                       data-q-num="{{ $baseQNum }}"
                       data-blank-index="0"
                       oninput="saveSentenceCompletionAnswer(this)">
            </div>
        @endif
    </div>
@endforeach

<script>
if (typeof window.saveSentenceCompletionAnswer !== 'function') {
    window.saveSentenceCompletionAnswer = function (el) {
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
