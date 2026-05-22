{{-- Table Completion Question Type for Students --}}
@php
    $questionData = is_array($question->question_data ?? null)
        ? $question->question_data
        : (is_string($question->question_data ?? null) ? json_decode($question->question_data, true) : []);

    $tableStructure = $question->table_structure
        ?? ($questionData['table_structure'] ?? null);

    if (is_string($tableStructure)) {
        $tableStructure = json_decode($tableStructure, true);
    }

    $tableHeaders = $tableStructure['headers'] ?? [];
    $tableRows = $tableStructure['rows'] ?? [];

    $savedAnswerJson = $userAnswers[$question->id] ?? '';
    $savedAnswerData = is_string($savedAnswerJson) ? json_decode($savedAnswerJson, true) : $savedAnswerJson;
    $savedMap = [];
    if (is_array($savedAnswerData) && !empty($savedAnswerData['answers']) && is_array($savedAnswerData['answers'])) {
        foreach ($savedAnswerData['answers'] as $answerItem) {
            if (isset($answerItem['row'], $answerItem['col'])) {
                $savedMap[$answerItem['row'] . '-' . $answerItem['col']] = $answerItem['answer'] ?? '';
            }
        }
    }
@endphp

<div class="idp-question-group table-completion-group" data-group-id="{{ $question->id }}">
    @if(!empty($question->title))
        <div class="idp-note-title">{{ $question->title }}</div>
    @endif

    @if(!empty($tableHeaders) || !empty($tableRows))
        <div class="table-completion-container">
            <table class="idp-table-completion-styled">
                @if(!empty($tableHeaders))
                    <thead>
                        <tr>
                            @foreach($tableHeaders as $header)
                                <th>{!! nl2br(e($header)) !!}</th>
                            @endforeach
                        </tr>
                    </thead>
                @endif
                <tbody>
                    @foreach($tableRows as $rowIndex => $row)
                        <tr>
                            @foreach($row as $colIndex => $cellContent)
                                @php
                                    $cellText = is_string($cellContent) ? $cellContent : (string) $cellContent;
                                    $cellKey = $rowIndex . '-' . $colIndex;
                                    $savedValue = $savedMap[$cellKey] ?? '';
                                    $parts = preg_split('/(___)/', $cellText, -1, PREG_SPLIT_DELIM_CAPTURE);
                                    $hasBlank = is_array($parts) && count($parts) > 1;
                                @endphp
                                <td>
                                    @if($hasBlank)
                                        @foreach($parts as $part)
                                            @if($part === '___')
                                                <span class="tc-input-wrapper">
                                                    <input type="text"
                                                           class="idp-table-input tc-cell-input"
                                                           value="{{ $savedValue }}"
                                                           autocomplete="off"
                                                           spellcheck="false"
                                                           data-question-id="{{ $question->id }}"
                                                           data-row="{{ $rowIndex }}"
                                                           data-col="{{ $colIndex }}"
                                                           oninput="saveTableAnswer(this)">
                                                </span>
                                            @elseif(trim($part) !== '')
                                                <span class="cell-text">{!! nl2br(e($part)) !!}</span>
                                            @endif
                                        @endforeach
                                    @else
                                        {!! nl2br(e($cellText)) !!}
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
    line-height: 1.7;
    white-space: normal;
}

.cell-text {
    display: inline;
}

.tc-input-wrapper {
    display: inline-flex;
    align-items: center;
    vertical-align: middle;
    margin: 0 6px;
}

.idp-table-input {
    border: 2px dashed #111827;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 14px;
    min-width: 180px;
    background: #ffffff;
    transition: all 0.2s;
}

.idp-table-input:focus {
    outline: none;
    border-color: #0f172a;
    box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.12);
}
</style>

<script>
if (typeof window.saveTableAnswer !== 'function') {
    window.saveTableAnswer = function (el) {
        const questionId = el.dataset.questionId;
        if (!questionId || typeof saveAnswer !== 'function') {
            return;
        }

        const inputs = document.querySelectorAll(`.tc-cell-input[data-question-id="${questionId}"]`);
        const answers = [];

        inputs.forEach((input) => {
            answers.push({
                row: parseInt(input.dataset.row, 10),
                col: parseInt(input.dataset.col, 10),
                answer: input.value || ''
            });
        });

        saveAnswer(questionId, JSON.stringify({ answers }));
    };
}
</script>
