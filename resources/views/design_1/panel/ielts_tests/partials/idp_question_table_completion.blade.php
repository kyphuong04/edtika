{{-- Table Completion Question Type for Students --}}
@php
    // Parse question data to get table structure
    $questionData = is_string($question->question_data) ? json_decode($question->question_data, true) : $question->question_data;
    $tableStructure = $questionData['table_structure'] ?? null;
    
    if ($tableStructure) {
        $tableHeaders = $tableStructure['headers'] ?? [];
        $tableRows = $tableStructure['rows'] ?? [];
    } else {
        $tableHeaders = [];
        $tableRows = [];
    }
@endphp

<div class="idp-question-group table-completion-group" data-group-id="{{ $question->id }}">
    @if(!empty($question->title))
        <div class="idp-note-title">{{ $question->title }}</div>
    @endif
    
    @if(!empty($tableHeaders) && !empty($tableRows))
        <div class="table-completion-container">
            <table class="idp-table-completion-styled">
                <thead>
                    <tr>
                        @foreach($tableHeaders as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($tableRows as $rowIndex => $row)
                        <tr>
                            @foreach($row as $colIndex => $cellContent)
                                @php
                                    // Check if this cell has a question marker [11], [12], etc.
                                    $questionMatch = null;
                                    if (preg_match('/\[(\d+)\]/', $cellContent, $matches)) {
                                        $questionNumber = $matches[1];
                                        $cellText = trim(preg_replace('/\[(\d+)\]/', '', $cellContent));
                                        $questionMatch = $questionNumber;
                                        
                                        // Find the saved answer for this question
                                        $cellId = 'tc_' . $question->id . '_q' . $questionNumber;
                                        $userAnswer = $userAnswers[$cellId] ?? '';
                                    }
                                @endphp
                                
                                <td>
                                    @if($questionMatch)
                                        {{-- Cell with question input --}}
                                        @if($cellText)
                                            <span class="cell-text">{{ $cellText }}</span>
                                        @endif
                                        <div class="tc-input-wrapper">
                                            <span class="tc-question-number">{{ $questionMatch }}</span>
                                            <input type="text" 
                                                   class="idp-table-input"
                                                   id="answer_{{ $cellId }}"
                                                   name="question_{{ $cellId }}"
                                                   value="{{ $userAnswer }}"
                                                   placeholder="{{ $questionMatch }}"
                                                   onblur="saveAnswer('{{ $cellId }}', this.value)">
                                        </div>
                                    @else
                                        {{-- Regular cell content --}}
                                        {{ $cellContent }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning">
            <strong>Table structure not found.</strong> Please contact your instructor.
        </div>
    @endif
</div>

<style>
.table-completion-container {
    margin: 20px 0;
    overflow-x: auto;
}

.idp-table-completion-styled {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    background: #ffffff;
}

.idp-table-completion-styled thead {
    background: #e8e8e8;
}

.idp-table-completion-styled th {
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    border: 1px solid #c0c0c0;
    color: #000000;
}

.idp-table-completion-styled td {
    padding: 12px 16px;
    border: 1px solid #c0c0c0;
    vertical-align: top;
    line-height: 1.6;
    white-space: nowrap;
}

.idp-table-completion-styled td .cell-text {
    display: inline;
    margin-bottom: 8px;
    white-space: nowrap;
}

.tc-input-wrapper {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    padding: 6px 10px;
    border-radius: 4px;
    border: none;
    white-space: nowrap;
}

.tc-question-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    background: #000000;
    color: #ffffff;
    font-weight: bold;
    font-size: 13px;
    border-radius: 50%;
    padding: 4px;
}

.idp-table-input {
    border: 2px dashed #00b4d8;
    padding: 6px 12px;
    border-radius: 3px;
    font-size: 14px;
    min-width: 120px;
    background: #ffffff;
    transition: all 0.2s;
    white-space: nowrap;
}

.idp-table-input:focus {
    outline: none;
    border-color: #333333;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
}
</style>
