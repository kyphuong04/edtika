{{-- Matching Features Question Type - Table with Radio Buttons --}}
@php
    $matchingQuestions = $question->matching_items ?? [];
    $choices = $question->choices ?? ['A', 'B', 'C'];
@endphp

<div class="idp-question-group" data-group-id="{{ $question->id }}">
    <div class="idp-question-header" style="margin-bottom: 16px;">
        @if(!empty($question->instruction))
            <div class="idp-question-instruction">{!! $question->instruction !!}</div>
        @endif
    </div>
    
    {{-- Choices Legend --}}
    <div style="margin-bottom: 16px; padding: 12px; background: #f9f9f9; border: 1px solid #e0e0e0;">
        @foreach($choices as $letter => $choiceText)
            @php $letterKey = is_numeric($letter) ? chr(65 + $letter) : $letter; @endphp
            <div style="margin-bottom: 6px;">
                <strong>{{ $letterKey }}</strong> &nbsp; {{ $choiceText }}
            </div>
        @endforeach
    </div>
    
    {{-- Matching Table --}}
    <table class="idp-matching-table">
        <thead>
            <tr>
                <th style="width: 60%;">Statement</th>
                @foreach($choices as $letter => $choiceText)
                    @php $letterKey = is_numeric($letter) ? chr(65 + $letter) : $letter; @endphp
                    <th style="width: {{ 40 / count($choices) }}%;">{{ $letterKey }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($matchingQuestions as $index => $item)
                @php
                    $qNum = $item['number'] ?? ($question->start_number + $index);
                    $itemId = $item['id'] ?? $question->id . '_' . $index;
                    $itemUserAnswer = $userAnswers[$itemId] ?? '';
                @endphp
                <tr>
                    <td>
                        <span class="idp-question-number">{{ $qNum }}</span>
                        {{ $item['text'] ?? $item }}
                    </td>
                    @foreach($choices as $letter => $choiceText)
                        @php $letterKey = is_numeric($letter) ? chr(65 + $letter) : $letter; @endphp
                        <td>
                            <input type="radio" 
                                   name="question_{{ $itemId }}"
                                   value="{{ $letterKey }}"
                                   {{ $itemUserAnswer === $letterKey ? 'checked' : '' }}
                                   onchange="saveAnswer('{{ $itemId }}', '{{ $letterKey }}')">
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
