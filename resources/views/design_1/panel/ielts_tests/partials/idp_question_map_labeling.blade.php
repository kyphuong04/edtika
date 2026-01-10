{{-- Map/Diagram Labeling Question Type --}}
@php
    $labels = $question->labels ?? [];
    $imagePath = $question->image ?? '';
@endphp

<div class="idp-question-group" data-group-id="{{ $question->id }}">
    @if(!empty($question->instruction))
        <div class="idp-question-instruction" style="margin-bottom: 16px;">
            {!! $question->instruction !!}
        </div>
    @endif
    
    {{-- Word Bank (if provided) --}}
    @if(!empty($question->word_bank))
        <div class="idp-word-bank" style="margin-bottom: 16px;">
            @foreach($question->word_bank as $word)
                <div class="idp-word-item" draggable="true" data-word="{{ $word }}">
                    {{ $word }}
                </div>
            @endforeach
        </div>
    @endif
    
    {{-- Map/Diagram Image with Labels --}}
    <div class="idp-map-container" style="position: relative; display: inline-block;">
        @if(!empty($imagePath))
            <img src="{{ $imagePath }}" alt="Map/Diagram" class="idp-map-image" style="max-width: 100%;">
        @endif
        
        {{-- Label Positions --}}
        @foreach($labels as $index => $label)
            @php
                $labelId = $label['id'] ?? $question->id . '_label_' . $index;
                $qNum = $label['number'] ?? '';
                $userAnswer = $userAnswers[$labelId] ?? '';
                $posTop = $label['top'] ?? (10 + $index * 8) . '%';
                $posLeft = $label['left'] ?? '10%';
            @endphp
            
            <div class="idp-map-label" style="top: {{ $posTop }}; left: {{ $posLeft }};">
                @if($qNum)
                    <span class="idp-question-number" style="margin-right: 4px;">{{ $qNum }}</span>
                @endif
                
                @if(isset($label['blank']))
                    <input type="text" 
                           class="idp-text-input"
                           id="answer_{{ $labelId }}"
                           name="question_{{ $labelId }}"
                           value="{{ $userAnswer }}"
                           placeholder="{{ $qNum ?: '...' }}"
                           style="width: 100px;"
                           onblur="saveAnswer('{{ $labelId }}', this.value)">
                @else
                    {{ $label['text'] ?? '' }}
                @endif
            </div>
        @endforeach
    </div>
    
    {{-- Alternative: List format for map labels --}}
    @if(empty($imagePath) && !empty($labels))
        <div style="margin-top: 16px;">
            @foreach($labels as $index => $label)
                @php
                    $labelId = $label['id'] ?? $question->id . '_label_' . $index;
                    $qNum = $label['number'] ?? ($question->start_number ?? 1) + $index;
                    $userAnswer = $userAnswers[$labelId] ?? '';
                @endphp
                
                <div class="idp-question" style="margin-bottom: 12px;">
                    <span class="idp-question-number">{{ $qNum }}</span>
                    
                    @if(!empty($label['description']))
                        <span style="font-size: 14px; color: #666; margin-right: 8px;">
                            {{ $label['description'] }}
                        </span>
                    @endif
                    
                    <input type="text" 
                           class="idp-text-input"
                           id="answer_{{ $labelId }}"
                           name="question_{{ $labelId }}"
                           value="{{ $userAnswer }}"
                           placeholder="{{ $qNum }}"
                           style="width: 140px;"
                           onblur="saveAnswer('{{ $labelId }}', this.value)">
                </div>
            @endforeach
        </div>
    @endif
</div>
