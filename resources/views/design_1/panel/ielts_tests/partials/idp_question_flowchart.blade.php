{{-- Flow Chart Completion Question Type --}}
@php
    $flowItems = $question->flow_items ?? [];
@endphp

<div class="idp-question-group idp-flowchart" data-group-id="{{ $question->id }}">
    @if(!empty($question->title))
        <div class="idp-note-title" style="margin-bottom: 16px;">{{ $question->title }}</div>
    @endif
    
    @foreach($flowItems as $index => $item)
        <div class="idp-flowchart-box {{ isset($item['blank']) ? 'active' : '' }}">
            @if(isset($item['blank']))
                @php
                    $itemId = $item['id'] ?? $question->id . '_' . $index;
                    $qNum = $item['number'] ?? '';
                    $userAnswer = $userAnswers[$itemId] ?? '';
                @endphp
                
                @if(!empty($item['text_before']))
                    {{ $item['text_before'] }}
                @endif
                
                @if($qNum)
                    <span class="idp-question-number">{{ $qNum }}</span>
                @endif
                
                <input type="text" 
                       class="idp-text-input"
                       id="answer_{{ $itemId }}"
                       name="question_{{ $itemId }}"
                       value="{{ $userAnswer }}"
                       placeholder="{{ $qNum ?: '...' }}"
                       style="width: 140px;"
                       onblur="saveAnswer('{{ $itemId }}', this.value)">
                
                @if(!empty($item['text_after']))
                    {{ $item['text_after'] }}
                @endif
            @else
                {{ is_array($item) ? ($item['text'] ?? '') : $item }}
            @endif
        </div>
        
        @if($index < count($flowItems) - 1)
            <div class="idp-flowchart-arrow">↓</div>
        @endif
    @endforeach
</div>
