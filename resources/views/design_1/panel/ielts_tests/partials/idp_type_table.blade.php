{{-- Table Completion - IDP Style --}}
{{-- Render table exactly as created by admin with questions in their positions --}}

@php
    // Get table structure from first question or tableData
    $firstQuestion = $questions->first();
    $tableStructure = null;
    
    // Try multiple sources for table structure
    if ($tableData) {
        $tableStructure = is_array($tableData) ? $tableData : json_decode($tableData, true);
    } elseif ($firstQuestion) {
        // Check table_structure field (direct field)
        if (isset($firstQuestion->table_structure)) {
            $tableStructure = is_array($firstQuestion->table_structure) 
                ? $firstQuestion->table_structure 
                : json_decode($firstQuestion->table_structure, true);
        }
        
        // Fallback: check question_data field (JSON field with table_structure inside)
        if (empty($tableStructure) && !empty($firstQuestion->question_data)) {
            $questionData = is_array($firstQuestion->question_data) 
                ? $firstQuestion->question_data 
                : json_decode($firstQuestion->question_data, true);
            
            if ($questionData && isset($questionData['table_structure'])) {
                $tableStructure = $questionData['table_structure'];
            }
        }
        
        // Fallback: check content field (might contain JSON)
        if (empty($tableStructure) && !empty($firstQuestion->content)) {
            $decoded = json_decode($firstQuestion->content, true);
            if ($decoded && isset($decoded['table_structure'])) {
                $tableStructure = $decoded['table_structure'];
            }
        }
        
        // Fallback: check instruction field (might contain JSON)
        if (empty($tableStructure) && !empty($firstQuestion->instruction)) {
            $decoded = json_decode($firstQuestion->instruction, true);
            if ($decoded && isset($decoded['table_structure'])) {
                $tableStructure = $decoded['table_structure'];
            }
        }
    }
    
    // Create a map of questions by their number for quick lookup
    $questionMap = [];
    foreach ($questions as $q) {
        $questionMap[$q->question_number] = $q;
    }
@endphp

@if(!empty($tableStructure) && !empty($tableStructure['rows']))
    <div class="table-completion-container">
        <table class="idp-table-completion-styled">
            @if(!empty($tableStructure['headers']))
            <thead>
                <tr>
                    @foreach($tableStructure['headers'] as $header)
                        <th>{!! nl2br(e($header)) !!}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            @foreach($tableStructure['rows'] as $rowIndex => $row)
                <tr>
                    @foreach($row as $colIndex => $cellContent)
                        <td>
                            @php
                                // Check if cell contains question markers like [1], [2], etc.
                                if (preg_match('/\[(\d+)\]/', $cellContent)) {
                                    // Split content into parts: text and [N] markers
                                    // Pattern (\[\d+\]) captures markers, text between them is also captured
                                    $parts = preg_split('/(\[\d+\])/', $cellContent, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                                } else {
                                    $parts = null;
                                }
                            @endphp
                            
                            @if($parts)
                                {{-- Cell with question(s) - render text and inputs --}}
                                @foreach($parts as $part)
                                    @php
                                        // Check if this part is a question marker [N]
                                        if (preg_match('/\[(\d+)\]/', $part, $matches)) {
                                            $questionNumber = (int)$matches[1];
                                            $question = $questionMap[$questionNumber] ?? null;
                                            $savedAnswer = $question ? ($userAnswers[$question->id] ?? '') : '';
                                        } else {
                                            $question = null;
                                        }
                                    @endphp
                                    
                                    @if($question)
                                        {{-- Render input wrapper for question --}}
                                        <div class="tc-input-wrapper">
                                            <span class="tc-question-number">{{ $questionNumber }}</span>
                                            <input type="text" 
                                                   class="idp-table-input"
                                                   value="{{ $savedAnswer }}"
                                                   placeholder=""
                                                   oninput="autoSave({{ $question->id }}, this.value)"
                                                   autocomplete="off"
                                                   spellcheck="false"
                                                   data-qid="{{ $question->id }}">
                                        </div>
                                    @else
                                        {{-- Render regular text --}}
                                        <span class="cell-text">{!! nl2br(e($part)) !!}</span>
                                    @endif
                                @endforeach
                            @else
                                {{-- Regular cell without question --}}
                                {!! nl2br(e($cellContent)) !!}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
@else
    {{-- Fallback: Simple list when no table structure --}}
    <div class="idp-questions-list">
        @foreach($questions as $q)
            @php
                $qNum = $q->question_number ?? $loop->iteration;
                $saved = $userAnswers[$q->id] ?? '';
                $text = $q->question_text ?? $q->content ?? '';
            @endphp
            <div class="idp-question-item" data-q-num="{{ $qNum }}">
                <div class="idp-q-label">
                    <span class="idp-q-num">{{ $qNum }}</span>
                    <span class="idp-q-text">{{ $text }}</span>
                </div>
                <div class="idp-text-input-wrapper">
                    <input type="text" 
                           class="idp-text-input" 
                           value="{{ $saved }}"
                           oninput="autoSave({{ $q->id }}, this.value)"
                           placeholder="Type your answer here..."
                           autocomplete="off"
                           spellcheck="false"
                           data-qid="{{ $q->id }}">
                </div>
            </div>
        @endforeach
    </div>
@endif
