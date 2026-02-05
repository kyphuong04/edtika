{{-- Note/Form Completion Question Type --}}
@php
    $sections = $question->sections ?? [];
@endphp

<div class="idp-question-group" data-group-id="{{ $question->id }}">
    @if(!empty($question->title))
        <div class="idp-note-title">{{ $question->title }}</div>
    @endif
    
    @foreach($sections as $sectionIndex => $section)
        <div class="idp-note-section">
            @if(!empty($section['title']))
                <div style="font-weight: bold; margin-bottom: 8px; font-size: 15px;">
                    {{ $section['title'] }}
                </div>
            @endif
            
            <ul class="idp-note-list">
                @foreach($section['items'] ?? [] as $itemIndex => $item)
                    <li>
                        @php
                            $rowType = is_array($item) && isset($item['row_type']) ? $item['row_type'] : 'question';
                        @endphp
                        
                        @if($rowType === 'header')
                            {{-- Header row - just display text, no input --}}
                            <div style="font-weight: 600; color: #333; margin-top: 8px;">
                                {{ is_array($item) ? ($item['text'] ?? '') : $item }}
                            </div>
                        @elseif(is_array($item) && isset($item['blank']))
                            {{-- Question row - has blank and input --}}
                            @php
                                $itemId = $item['id'] ?? $question->id . '_' . $sectionIndex . '_' . $itemIndex;
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
                                   onblur="saveAnswer('{{ $itemId }}', this.value)">
                            
                            @if(!empty($item['text_after']))
                                {{ $item['text_after'] }}
                            @endif
                        @elseif(is_array($item))
                            {{ $item['text'] ?? '' }}
                        @else
                            {{ $item }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
