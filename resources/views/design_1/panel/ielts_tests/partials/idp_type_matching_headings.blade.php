{{-- Matching Headings (Matrix) - IDP Style --}}

@php
    $headings = is_array($matchingOptions) ? $matchingOptions : json_decode($matchingOptions ?? '[]', true);

    if (empty($headings) && isset($questions) && $questions->count()) {
        $firstQuestionOptions = $questions->first()->answer_options ?? $questions->first()->options ?? [];
        if (is_string($firstQuestionOptions)) {
            $firstQuestionOptions = json_decode($firstQuestionOptions, true) ?? [];
        }

        if (is_array($firstQuestionOptions)) {
            $hasAssociativeKeys = array_keys($firstQuestionOptions) !== range(0, count($firstQuestionOptions) - 1);

            if ($hasAssociativeKeys) {
                $headings = $firstQuestionOptions;
            } elseif (!empty($firstQuestionOptions)) {
                $headings = [];
                foreach ($firstQuestionOptions as $idx => $value) {
                    $letter = chr(65 + $idx);
                    $headings[$letter] = $value;
                }
            }
        }
    }

    $headingKeys = array_keys($headings);
    if (empty($headingKeys)) {
        $headingKeys = ['A', 'B', 'C', 'D', 'E'];
    }
@endphp

<table class="idp-match-table">
    <thead>
        <tr>
            <th style="width: auto;"></th>
            @foreach($headingKeys as $key)
                <th>{{ $key }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($questions as $q)
            @php
                $qNum = $q->question_number ?? $loop->iteration;
                $saved = $userAnswers[$q->id] ?? '';
                $text = $q->question_text ?? $q->content ?? '';
            @endphp
            <tr data-q-num="{{ $qNum }}">
                <td>
                    <span class="idp-q-num">{{ $qNum }}</span>
                    <span class="idp-q-text">{!! $text !!}</span>
                </td>
                @foreach($headingKeys as $key)
                    <td>
                        <input type="radio"
                               name="q_{{ $q->id }}"
                               value="{{ $key }}"
                               {{ $saved === $key ? 'checked' : '' }}
                               onchange="saveAnswer({{ $q->id }}, '{{ $key }}')">
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
