{{-- Matching (Table with Radio Buttons) - IDP Style --}}
{{-- Table: First column = question text, other columns = A, B, C, D, E options --}}

@php
    $matchOptions = is_array($matchingOptions) ? $matchingOptions : json_decode($matchingOptions ?? '[]', true);

    if (empty($matchOptions) && isset($questions) && $questions->count()) {
        $firstQuestionOptions = $questions->first()->answer_options ?? $questions->first()->options ?? [];
        if (is_string($firstQuestionOptions)) {
            $firstQuestionOptions = json_decode($firstQuestionOptions, true) ?? [];
        }

        if (is_array($firstQuestionOptions)) {
            $hasAssociativeKeys = array_keys($firstQuestionOptions) !== range(0, count($firstQuestionOptions) - 1);

            if ($hasAssociativeKeys) {
                $matchOptions = $firstQuestionOptions;
            } elseif (!empty($firstQuestionOptions)) {
                $matchOptions = [];
                foreach ($firstQuestionOptions as $idx => $value) {
                    $letter = chr(65 + $idx);
                    $matchOptions[$letter] = $value;
                }
            }
        }
    }

    $optionKeys = array_keys($matchOptions);
    if(empty($optionKeys)) {
        $optionKeys = ['A', 'B', 'C', 'D', 'E'];
    }
@endphp

@if(!empty($matchOptions))
    <div class="idp-options" style="margin-bottom: 10px; margin-left: 0;">
        @foreach($matchOptions as $label => $desc)
            <div style="font-size:14px; margin-bottom:4px;"><strong>{{ $label }}.</strong> {!! $desc !!}</div>
        @endforeach
    </div>
@endif

<table class="idp-match-table">
    <thead>
        <tr>
            <th style="width: auto;"></th>
            @foreach($optionKeys as $opt)
                <th>{{ $opt }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($questions as $q)
            @php
                $qNum = $q->question_number ?? $loop->iteration;
                $saved = $userAnswers[$q->id] ?? '';
            @endphp
            <tr data-q-num="{{ $qNum }}">
                <td>
                    <span class="idp-q-num">{{ $qNum }}</span>
                    <span class="idp-q-text">{!! $q->question_text ?? $q->content ?? '' !!}</span>
                </td>
                @foreach($optionKeys as $opt)
                    <td>
                        <input type="radio" name="q_{{ $q->id }}" value="{{ $opt }}"
                               {{ $saved === $opt ? 'checked' : '' }}
                               onchange="saveAnswer({{ $q->id }}, '{{ $opt }}')">
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
