{{-- List Selection Question Type (Select from a list) --}}
@php
    $options = is_string($question->options) ? json_decode($question->options, true) : ($question->options ?? []);
    $userAnswer = $userAnswers[$question->id] ?? '';
@endphp

<div class="idp-question" data-q-id="{{ $question->id }}" data-q-num="{{ $question->order_number }}">
    <span class="idp-question-num">{{ $question->order_number }}</span>
    
    @if(!empty($question->content))
        <span class="idp-question-text">{!! $question->content !!}</span>
    @endif
    
    <div style="margin-top: 8px;">
        <select class="idp-select-answer"
                id="answer_{{ $question->id }}"
                name="question_{{ $question->id }}"
                onchange="saveAnswer({{ $question->id }}, this.value)"
                style="padding: 6px 12px; font-size: 14px; border: 1px solid #d0d0d0; min-width: 200px;">
            <option value="">-- Select answer --</option>
            @foreach($options as $idx => $opt)
                @php 
                    $optValue = is_array($opt) ? ($opt['value'] ?? $opt['text'] ?? $opt) : $opt;
                    $optText = is_array($opt) ? ($opt['text'] ?? $opt['value'] ?? $opt) : $opt;
                @endphp
                <option value="{{ $optValue }}" {{ $userAnswer === $optValue ? 'selected' : '' }}>
                    {{ $optText }}
                </option>
            @endforeach
        </select>
    </div>
</div>
