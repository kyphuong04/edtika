{{-- Matching Information Question Type (for Reading) --}}
@php
    $statements = $question->statements ?? [];
    $choices = $question->choices ?? [];
@endphp

<div class="idp-question-group" data-group-id="{{ $question->id }}">
    {{-- List of Choices/Names --}}
    @if(!empty($choices))
        <div style="margin-bottom: 20px; padding: 16px; background: #ffffff; border: 1px solid #d0d0d0;">
            @foreach($choices as $letter => $choice)
                @php $letterKey = is_numeric($letter) ? chr(65 + $letter) : $letter; @endphp
                <div style="margin-bottom: 8px; font-size: 14px;">
                    <strong>{{ $letterKey }}</strong> &nbsp; {{ is_array($choice) ? ($choice['text'] ?? $choice) : $choice }}
                </div>
            @endforeach
        </div>
    @endif
    
    {{-- Statements with dropdown --}}
    @foreach($statements as $index => $statement)
        @php
            $qNum = $statement['number'] ?? ($question->start_number ?? 1) + $index;
            $statementId = $statement['id'] ?? $question->id . '_stmt_' . $index;
            $userAnswer = $userAnswers[$statementId] ?? '';
        @endphp
        
        <div class="idp-question" style="margin-bottom: 16px;">
            <span class="idp-question-num">{{ $qNum }}</span>
            <span class="idp-question-text">{{ is_array($statement) ? ($statement['text'] ?? $statement) : $statement }}</span>
            
            <div style="margin-top: 8px; padding-left: 32px;">
                <select class="idp-select-answer"
                        id="answer_{{ $statementId }}"
                        name="question_{{ $statementId }}"
                        onchange="saveAnswer('{{ $statementId }}', this.value)"
                        style="padding: 6px 12px; font-size: 14px; border: 1px solid #d0d0d0; min-width: 150px;">
                    <option value="">--</option>
                    @foreach($choices as $letter => $choice)
                        @php $letterKey = is_numeric($letter) ? chr(65 + $letter) : $letter; @endphp
                        <option value="{{ $letterKey }}" {{ $userAnswer === $letterKey ? 'selected' : '' }}>
                            {{ $letterKey }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endforeach
</div>
