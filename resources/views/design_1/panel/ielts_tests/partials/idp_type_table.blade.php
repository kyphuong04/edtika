{{-- Table Completion - IDP Style --}}
{{-- Render the admin-created table structure exactly, preserving static cells and blank cells marked with ___ --}}

@php
    $firstQuestion = $questions->first();

    // Try to get structure from explicit tableData first
    $tableStructure = null;
    if (isset($tableData) && $tableData) {
        $tableStructure = is_array($tableData) ? $tableData : json_decode($tableData, true);
    }

    // If not provided, scan all questions in this group to find any question that contains table_structure
    $sourceQuestion = null;
    if (!$tableStructure && $questions && $questions->count() > 0) {
        foreach ($questions as $q) {
            $candidate = $q->table_structure ?? null;
            if (!$candidate && !empty($q->question_data)) {
                $qd = is_array($q->question_data) ? $q->question_data : json_decode($q->question_data, true);
                if (is_array($qd) && isset($qd['table_structure'])) {
                    $candidate = $qd['table_structure'];
                }
            }

            if ($candidate) {
                $sourceQuestion = $q;
                $tableStructure = $candidate;
                break;
            }
        }
    }

    // Fallback to first question's direct table_structure if still not found
    if (!$tableStructure && $firstQuestion) {
        $tableStructure = $firstQuestion->table_structure ?? null;
        if (!$tableStructure && !empty($firstQuestion->question_data)) {
            $questionData = is_array($firstQuestion->question_data)
                ? $firstQuestion->question_data
                : json_decode($firstQuestion->question_data, true);

            if (is_array($questionData) && isset($questionData['table_structure'])) {
                $tableStructure = $questionData['table_structure'];
                $sourceQuestion = $firstQuestion;
            }
        }
    }

    if (is_string($tableStructure)) {
        $tableStructure = json_decode($tableStructure, true);
    }

    $tableHeaders = $tableStructure['headers'] ?? [];
    $tableRows = $tableStructure['rows'] ?? [];
    $blankStartNumber = (int) ($firstQuestion->question_number ?? 1);
    $blankSequence = 0;

    // Use title/instruction from firstQuestion by default, but prefer sourceQuestion if it has them
    $title = $firstQuestion->question_text ?? $firstQuestion->title ?? '';
    $instruction = $firstQuestion->instruction ?? '';
    if ($sourceQuestion) {
        $title = $title ?: ($sourceQuestion->question_text ?? $sourceQuestion->title ?? '');
        $instruction = $instruction ?: ($sourceQuestion->instruction ?? '');
    }

    // Determine which question ID we save answers to (prefer firstQuestion, fallback to sourceQuestion)
    $saveQuestionId = $firstQuestion->id ?? ($sourceQuestion->id ?? null);
    $savedAnswerJson = $saveQuestionId ? ($userAnswers[$saveQuestionId] ?? '') : '';
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

@if(!empty($tableHeaders) || !empty($tableRows))
    <div class="table-completion-container" data-question-id="{{ $saveQuestionId ?? '' }}">
        @if(!empty($title))
            <div class="idp-note-title">{!! nl2br(e($title)) !!}</div>
        @endif

        @if(!empty($instruction))
            <div class="table-completion-instruction">{!! $instruction !!}</div>
        @endif

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
                                            @php $blankSequence++; @endphp
                                            @php $blankNumber = $blankStartNumber + $blankSequence - 1; @endphp
                                            <span class="tc-input-wrapper">
                                                <span class="tc-blank-number">{{ $blankNumber }}</span>
                                                <input type="text"
                                                       class="idp-table-input tc-cell-input"
                                                       data-q-num="{{ $blankNumber }}"
                                                       value="{{ $savedValue }}"
                                                       autocomplete="off"
                                                       spellcheck="false"
                                                       data-question-id="{{ $saveQuestionId ?? '' }}"
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

<style>
.table-completion-container {
    margin: 20px 0;
    overflow-x: auto;
}

.table-completion-instruction {
    margin: 8px 0 14px;
    font-size: 14px;
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

.tc-blank-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 24px;
    margin-right: 8px;
    padding: 0 6px;
    border-radius: 999px;
    background: #1a3a5c;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    flex-shrink: 0;
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

        const questionNumber = parseInt(el.dataset.qNum || '', 10) || null;
        saveAnswer(questionId, JSON.stringify({ answers }), questionNumber);
    };
}
</script>
