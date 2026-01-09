{{-- Table Completion Question Type --}}
@php
    $tableHeaders = $question->headers ?? [];
    $tableRows = $question->rows ?? [];
@endphp

<div class="idp-question-group" data-group-id="{{ $question->id }}">
    @if(!empty($question->title))
        <div class="idp-note-title">{{ $question->title }}</div>
    @endif
    
    <table class="idp-table-completion">
        @if(!empty($tableHeaders))
            <thead>
                <tr>
                    @foreach($tableHeaders as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            @foreach($tableRows as $rowIndex => $row)
                <tr>
                    @foreach($row as $cellIndex => $cell)
                        <td>
                            @if(is_array($cell) && isset($cell['blank']))
                                @php
                                    $cellId = $cell['id'] ?? $question->id . '_' . $rowIndex . '_' . $cellIndex;
                                    $qNum = $cell['number'] ?? '';
                                    $userAnswer = $userAnswers[$cellId] ?? '';
                                @endphp
                                
                                @if($qNum)
                                    <span class="idp-question-number">{{ $qNum }}</span>
                                @endif
                                
                                <input type="text" 
                                       class="idp-text-input"
                                       id="answer_{{ $cellId }}"
                                       name="question_{{ $cellId }}"
                                       value="{{ $userAnswer }}"
                                       placeholder="{{ $qNum ?: '...' }}"
                                       style="width: 100px;"
                                       onblur="saveAnswer('{{ $cellId }}', this.value)">
                            @elseif(is_array($cell))
                                {{ $cell['text'] ?? '' }}
                            @else
                                {{ $cell }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
