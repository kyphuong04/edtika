{{-- 
    IDP Questions Panel - Renders all question types grouped by question_group_id or question_type
    Each group has its own instructions and question type layout
--}}

@php
    $prevPartId = null;
    $displayQuestionCursor = null;
@endphp

@foreach($groupedQuestions as $groupKey => $questions)
    @php
        // Get first question to determine group info
        $firstQ = $questions->first();
        
        // Try to get group from first question's question_group_id
        $group = null;
        if($firstQ && $firstQ->question_group_id) {
            $group = $firstQ->questionGroup ?? \App\Models\IeltsQuestionGroup::find($firstQ->question_group_id);
        }
        
        // Determine question type - group type takes priority when group exists
        $questionType = ($group ? ($group->question_type ?? null) : null) ?? $firstQ->question_type ?? 'fill_blank';
        
        // Get group instructions if available
        $instructions = $group->instructions ?? $group->instruction ?? '';
        $title = $group->title ?? '';
        $wordBank = $group->word_bank ?? [];
        $matchingOptions = $group->matching_options ?? [];
        
        // Convert to array if JSON string
        if(is_string($wordBank)) {
            $wordBank = json_decode($wordBank, true) ?? [];
        }
        if(is_string($matchingOptions)) {
            $matchingOptions = json_decode($matchingOptions, true) ?? [];
        }

        $currentPartId = $group->part_id ?? null;
        $showPartLabel = $currentPartId && $currentPartId !== $prevPartId;
        if($showPartLabel) $prevPartId = $currentPartId;

        if ($displayQuestionCursor === null) {
            $displayQuestionCursor = (int) ($firstQ->question_number ?? 1);
        }

        $questionRangeStart = $displayQuestionCursor;
        $displayQuestionCount = max(1, (int) $questions->count());

        if ($questionType === 'table_completion') {            $tableStructure = $firstQ->table_structure ?? null;

            if (!$tableStructure && !empty($firstQ->question_data)) {
                $firstQuestionData = is_array($firstQ->question_data)
                    ? $firstQ->question_data
                    : json_decode($firstQ->question_data, true);

                if (is_array($firstQuestionData) && isset($firstQuestionData['table_structure'])) {
                    $tableStructure = $firstQuestionData['table_structure'];
                }
            }

            if (is_string($tableStructure)) {
                $tableStructure = json_decode($tableStructure, true);
            }

            $blankCount = 1;
            if (is_array($tableStructure)) {
                if (!empty($tableStructure['answers']) && is_array($tableStructure['answers'])) {
                    $blankCount = count($tableStructure['answers']);
                } elseif (!empty($tableStructure['rows']) && is_array($tableStructure['rows'])) {
                    $blankCount = 0;
                    foreach ($tableStructure['rows'] as $row) {
                        foreach ((array) $row as $cellContent) {
                            $blankCount += substr_count((string) $cellContent, '___');
                        }
                    }
                    $blankCount = max(1, $blankCount);
                }
            }

            $displayQuestionCount = max(1, $blankCount);
        }

        if (in_array($questionType, ['drag_drop_disappear', 'drag_drop_reuse'])) {
            $ddBlankCount = 0;
            foreach ($questions as $ddQ) {
                $ddBlankCount += max(1, substr_count($ddQ->question_text ?? '', '___'));
            }
            $displayQuestionCount = max(1, $ddBlankCount);
        }

        if (in_array($questionType, ['note_completion', 'form_completion'])) {
            $noteBlankCount = 0;
            foreach ($questions as $noteQ) {
                $noteText = (string) ($noteQ->question_text ?? '');
                $matches = [];
                preg_match_all('/_{2,}|\[\s*\d*\s*\]|____/', $noteText, $matches);
                $blankInText = !empty($matches[0]) ? count($matches[0]) : 0;

                if ($blankInText === 0 && !empty($noteQ->correct_answer) && is_string($noteQ->correct_answer) && str_contains($noteQ->correct_answer, '|')) {
                    $blankInText = count(array_filter(array_map('trim', explode('|', $noteQ->correct_answer)), static fn($item) => $item !== ''));
                }

                $noteBlankCount += max(1, $blankInText);
            }

            $displayQuestionCount = max(1, $noteBlankCount);
        }

        if (in_array($questionType, ['summary_completion', 'sentence_completion', 'short_answer'])) {
            $completionBlankCount = 0;
            foreach ($questions as $completionQ) {
                $completionText = (string) ($completionQ->question_text ?? '');
                $matches = [];
                preg_match_all('/_{2,}|\[\s*\d*\s*\]|____/', $completionText, $matches);
                $blankInText = !empty($matches[0]) ? count($matches[0]) : 0;

                if ($blankInText === 0 && !empty($completionQ->correct_answer) && is_string($completionQ->correct_answer) && str_contains($completionQ->correct_answer, '|')) {
                    $blankInText = count(array_filter(array_map('trim', explode('|', $completionQ->correct_answer)), static fn($item) => $item !== ''));
                }

                $completionBlankCount += max(1, $blankInText);
            }

            $displayQuestionCount = max(1, $completionBlankCount);
        }

        $questionRangeEnd = $questionRangeStart + $displayQuestionCount - 1;
        $displayQuestionCursor = $questionRangeEnd + 1;
    @endphp

    <!-- @if($showPartLabel)
        @php $part = \App\Models\IeltsTestPart::find($currentPartId); @endphp
        @if($part)
        <div style="background:#1a3a5c;color:#fff;padding:8px 16px;font-weight:700;font-size:13px;letter-spacing:.5px;margin-bottom:4px;">
            {{ strtoupper($part->title ?? 'PART') }}
        </div>
        @endif
    @elseif(!$loop->first)
        <hr class="idp-group-separator">
    @endif -->
    @if(!$showPartLabel && !$loop->first)
        <hr class="idp-group-separator">
    @endif

    {{-- Group Header & Instructions --}}
    <div class="idp-questions-header">
        Questions {{ $questionRangeStart }}–{{ $questionRangeEnd }}
    </div>
    @if(!empty($title))
    <div class="idp-questions-title" style="font-weight:600;font-size:14px;margin:6px 0 4px;color:#1e293b;">
        {!! $title !!}
    </div>
    @endif
    <div class="idp-questions-instruction">
        {!! !empty($instructions) ? $instructions : getQuestionInstruction($questionType) !!}
    </div>

    {{-- Render questions based on type --}}
    @switch($questionType)
        @case('true_false_not_given')
        @case('true_false_ng')
        @case('tfng')
            @include('design_1.panel.ielts_tests.partials.idp_type_tfng', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('yes_no_not_given')
        @case('yes_no_ng')
        @case('ynng')
            @include('design_1.panel.ielts_tests.partials.idp_type_ynng', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('multiple_choice')
        @case('mcq')
        @case('single_choice')
        @case('multiple_choice_single')
            @include('design_1.panel.ielts_tests.partials.idp_type_mcq', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('multiple_choice_multiple')
        @case('multiple_select')
        @case('mcq_multiple')
        @case('choose_two')
        @case('choose_three')
            @include('design_1.panel.ielts_tests.partials.idp_type_mcq_multiple', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('sentence_completion')
        @case('short_answer')
            @include('design_1.panel.ielts_tests.partials.idp_type_sentence', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
            @break
            
        @case('summary_completion')
        @case('summary')
            @include('design_1.panel.ielts_tests.partials.idp_type_summary', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'wordBank' => $wordBank
            ])
            @break
            
        @case('matching')
        @case('matching_features')
        @case('matching_information')
        @case('matching_sentence_endings')
            @include('design_1.panel.ielts_tests.partials.idp_type_matching', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'matchingOptions' => $matchingOptions
            ])
            @break
            
        @case('matching_headings')
            @include('design_1.panel.ielts_tests.partials.idp_type_matching_headings', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'matchingOptions' => $matchingOptions
            ])
            @break
            
        @case('note_completion')
        @case('form_completion')
            @include('design_1.panel.ielts_tests.partials.idp_type_note', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'title' => $title
            ])
            @break
            
        @case('table_completion')
            @include('design_1.panel.ielts_tests.partials.idp_type_table', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'tableData' => $group->table_data ?? null,
                'displayStartNumber' => $questionRangeStart,
            ])
            @break
            
        @case('flowchart')
        @case('flow_chart')
            @include('design_1.panel.ielts_tests.partials.idp_type_flowchart', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'flowData' => $group->flow_data ?? null
            ])
            @break
            
        @case('map_labeling')
        @case('diagram_labeling')
        @case('diagram_label')
            @include('design_1.panel.ielts_tests.partials.idp_type_map', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'imageUrl' => $group->image_url ?? null,
                'matchingOptions' => $matchingOptions
            ])
            @break
            
        @case('drag_drop')
        @case('dragdrop')
            @include('design_1.panel.ielts_tests.partials.idp_type_dragdrop', [
                'questions' => $questions,
                'userAnswers' => $userAnswers,
                'wordBank' => $wordBank
            ])
            @break

        @case('drag_drop_disappear')
            @include('design_1.panel.ielts_tests.partials.idp_type_dragdrop_matching', [
                'questions'   => $questions,
                'userAnswers' => $userAnswers,
                'dragType'    => 'disappear',
            ])
            @break

        @case('drag_drop_reuse')
            @include('design_1.panel.ielts_tests.partials.idp_type_dragdrop_matching', [
                'questions'   => $questions,
                'userAnswers' => $userAnswers,
                'dragType'    => 'reuse',
            ])
            @break
            
        @default
            {{-- Default: Simple fill in blank --}}
            @include('design_1.panel.ielts_tests.partials.idp_type_fill_blank', [
                'questions' => $questions,
                'userAnswers' => $userAnswers
            ])
    @endswitch
@endforeach
