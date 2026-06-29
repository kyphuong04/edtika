{{-- Note/Form Completion - IDP Style --}}
{{-- Structured list with bullets/dashes and input blanks --}}

@if(!empty($title))
    <div class="idp-note-title">{{ $title }}</div>
@endif

<div class="idp-note-bullet" style="padding: 0;">
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
            } elseif (is_string($savedRaw) && str_contains($savedRaw, '|')) {
                $savedAnswers = array_map('trim', explode('|', $savedRaw));
            } elseif (!empty($savedRaw)) {
                $savedAnswers = [(string) $savedRaw];
            }

            $hasBlank = preg_match('/_{2,}|\[\s*\d*\s*\]|____/', (string) $text) === 1;
        @endphp

        @if(!$hasBlank && !empty($q->section_title))
            <div style="font-weight: bold; margin-top: 12px; margin-bottom: 6px;">
                {{ $q->section_title }}
            </div>
        @elseif($hasBlank)
            @php $blankIndex = -1; @endphp
            <div class="idp-question idp-note-question" data-q-num="{{ $baseQNum }}" style="margin-bottom: 10px;">
                {!! preg_replace_callback(
                    '/_{2,}|\[\s*\d*\s*\]|____/',
                    function () use (&$blankIndex, $q, $baseQNum, $savedAnswers) {
                        $blankIndex++;
                        $blankQNum = $baseQNum + $blankIndex;
                        $value = $savedAnswers[$blankIndex] ?? '';

                        return '<span class="idp-note-blank-wrap" data-q-num="' . $blankQNum . '">' .
                               '<span class="idp-q-num">' . $blankQNum . '</span>' .
                               '<input type="text" class="idp-input" data-qid="' . $q->id . '" data-q-num="' . $blankQNum . '" data-blank-index="' . $blankIndex . '" value="' . e($value) . '" oninput="saveNoteCompletionAnswer(this)">' .
                               '</span>';
                    },
                    (string) $text
                ) !!}
            </div>
        @else
            <div class="idp-question idp-note-question" data-q-num="{{ $baseQNum }}" style="margin-bottom: 10px;">
                <span class="idp-q-text">{!! $text !!}</span>
            </div>
        @endif
    @endforeach
</div>

<script>
if (typeof window.saveNoteCompletionAnswer !== 'function') {
    window.saveNoteCompletionAnswer = function (el) {
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
