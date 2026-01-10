{{-- Multiple Choice (Single Answer) - IDP Style --}}
{{-- Question number + question text, then radio options A, B, C, D --}}

@foreach($questions as $q)
    @php
        $qNum = $q->question_number ?? $loop->iteration;
        $saved = $userAnswers[$q->id] ?? '';
        $options = is_array($q->options) ? $q->options : json_decode($q->options ?? '[]', true);
        if(empty($options) && !empty($q->option_a)) {
            $options = [];
            if($q->option_a) $options['A'] = $q->option_a;
            if($q->option_b) $options['B'] = $q->option_b;
            if($q->option_c) $options['C'] = $q->option_c;
            if($q->option_d) $options['D'] = $q->option_d;
        }
    @endphp
    <div class="idp-question" data-q-num="{{ $qNum }}">
        <div style="margin-bottom: 8px;">
            <span class="idp-q-num">{{ $qNum }}</span>
            <span class="idp-q-text">{{ $q->question_text ?? $q->content ?? '' }}</span>
        </div>
        <div class="idp-options">
            @foreach($options as $key => $text)
                <label class="idp-option">
                    <input type="radio" name="q_{{ $q->id }}" value="{{ $key }}"
                           {{ $saved === $key ? 'checked' : '' }}
                           onchange="saveAnswer({{ $q->id }}, '{{ $key }}')">
                    <span><strong>{{ $key }}</strong> {{ $text }}</span>
                </label>
            @endforeach
        </div>
    </div>
@endforeach
