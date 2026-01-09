{{-- Matching Headings - IDP Style --}}
{{-- List of headings (roman numerals), then questions with dropdown/input to select heading --}}

@php
    $headings = is_array($matchingOptions) ? $matchingOptions : json_decode($matchingOptions ?? '[]', true);
@endphp

{{-- Headings list --}}
@if(!empty($headings))
    <div style="margin-bottom: 16px; font-size: 14px;">
        <strong>List of Headings</strong>
        <div style="margin-top: 8px; padding-left: 10px;">
            @foreach($headings as $key => $text)
                <div style="margin-bottom: 4px;">{{ $key }} &nbsp; {{ $text }}</div>
            @endforeach
        </div>
    </div>
@endif

{{-- Questions --}}
@foreach($questions as $q)
    @php
        $qNum = $q->question_number ?? $loop->iteration;
        $saved = $userAnswers[$q->id] ?? '';
        $text = $q->question_text ?? $q->content ?? '';
    @endphp
    <div class="idp-question" data-q-num="{{ $qNum }}" style="display: flex; align-items: center; gap: 10px;">
        <span class="idp-q-num">{{ $qNum }}</span>
        <select class="idp-input" style="min-width: 60px; height: 28px;" onchange="saveAnswer({{ $q->id }}, this.value)">
            <option value="">--</option>
            @foreach($headings as $key => $hText)
                <option value="{{ $key }}" {{ $saved === $key ? 'selected' : '' }}>{{ $key }}</option>
            @endforeach
        </select>
        <span class="idp-q-text">{{ $text }}</span>
    </div>
@endforeach
