{{-- Matching (Table with Radio Buttons) - IDP Style --}}
{{-- Table: First column = question text, other columns = A, B, C, D, E options --}}

@php
    $matchOptions = is_array($matchingOptions) ? $matchingOptions : json_decode($matchingOptions ?? '[]', true);
    $optionKeys = array_keys($matchOptions);
    if(empty($optionKeys)) {
        $optionKeys = ['A', 'B', 'C', 'D', 'E'];
    }
@endphp

{{-- Legend/Options explanation --}}
@if(!empty($matchOptions))
    <div style="margin-bottom: 12px; font-size: 13px;">
        @foreach($matchOptions as $key => $text)
            <div><strong>{{ $key }}</strong> &nbsp; {{ $text }}</div>
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
                    <span class="idp-q-text">{{ $q->question_text ?? $q->content ?? '' }}</span>
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
