{{-- Fill in the Blank (Default) - IDP Style --}}
{{-- Simple: question number + input --}}

@foreach($questions as $q)
    @php
        $qNum = $q->question_number ?? $loop->iteration;
        $saved = $userAnswers[$q->id] ?? '';
        $text = $q->question_text ?? $q->content ?? '';
    @endphp
    <div class="idp-question" data-q-num="{{ $qNum }}">
        @if(!empty($text))
            <span class="idp-q-num">{{ $qNum }}</span>
            <span class="idp-q-text">{{ $text }}</span>
            <input type="text" class="idp-input" style="margin-left: 8px;"
                   value="{{ $saved }}"
                   oninput="autoSave({{ $q->id }}, this.value)">
        @else
            <span class="idp-q-num">{{ $qNum }}</span>
            <input type="text" class="idp-input idp-input-lg"
                   value="{{ $saved }}"
                   oninput="autoSave({{ $q->id }}, this.value)">
        @endif
    </div>
@endforeach
