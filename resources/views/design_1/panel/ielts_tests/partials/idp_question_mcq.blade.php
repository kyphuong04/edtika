{{-- Multiple Choice (Single Answer) Question Type --}}
<div class="idp-question" data-question-id="{{ $question->id }}" data-question-number="{{ $question->order_number }}">
    <span class="idp-question-number">{{ $question->order_number }}</span>
    <span class="idp-question-text">{!! $question->content !!}</span>
    
    <div class="idp-tfng-options">
        @foreach($question->options ?? [] as $optionKey => $option)
            @php
                $optionLetter = chr(65 + $loop->index); // A, B, C, D...
                $optionValue = is_array($option) ? ($option['value'] ?? $option['text'] ?? $option) : $option;
                $optionText = is_array($option) ? ($option['text'] ?? $option['value'] ?? $option) : $option;
            @endphp
            <div class="idp-radio-option">
                <input type="radio" 
                       name="question_{{ $question->id }}" 
                       id="q{{ $question->id }}_{{ $optionLetter }}"
                       value="{{ $optionLetter }}"
                       {{ ($userAnswer ?? '') === $optionLetter ? 'checked' : '' }}
                       onchange="saveAnswer({{ $question->id }}, '{{ $optionLetter }}')">
                <label for="q{{ $question->id }}_{{ $optionLetter }}">
                    <strong>{{ $optionLetter }}</strong> &nbsp; {{ $optionText }}
                </label>
            </div>
        @endforeach
    </div>
</div>
