{{-- 
    IDP IELTS CBT Interface - 100% Exact Replica
    Supports all question types from official IDP interface
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IELTS {{ ucfirst($currentSection->skill ?? 'Test') }}</title>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            color: #000;
            background: #fff;
            line-height: 1.5;
            overflow: hidden;
            height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* ========== HEADER ========== */
        .idp-header {
            height: 40px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
        }
        .idp-header-left { display: flex; align-items: center; gap: 16px; }
        .idp-logo { font-size: 22px; font-weight: bold; color: #E31837; font-style: italic; font-family: serif; }
        .idp-test-taker { color: #333; font-size: 13px; }
        .idp-audio-playing { color: #333; font-size: 12px; display: flex; align-items: center; gap: 4px; }
        .idp-audio-playing::before { content: "🔊"; }
        .idp-header-right { display: flex; align-items: center; gap: 12px; }
        .idp-header-icon { color: #666; font-size: 18px; cursor: pointer; }
        
        /* ========== PART BAR ========== */
        .idp-part-bar {
            height: 50px;
            background: #e8e8e8;
            display: flex;
            align-items: center;
            padding: 0 20px;
            position: fixed;
            top: 40px; left: 0; right: 0;
            z-index: 999;
        }
        .idp-part-title { color: #000; font-size: 15px; font-weight: bold; }
        .idp-part-instruction { color: #000; font-size: 13px; margin-left: 8px; }
        .idp-part-instruction a { color: #0066CC; }
        
        /* ========== MAIN LAYOUT ========== */
        .idp-main {
            display: flex;
            position: fixed;
            top: 90px; left: 0; right: 0; bottom: 44px;
        }
        
        /* Left Panel */
        .idp-left {
            flex: 1;
            background: #fff;
            overflow-y: auto;
            padding: 20px 28px;
            border-right: 1px solid #ccc;
        }
        .idp-passage-title { 
            font-family: Arial, sans-serif;
            font-size: 17px; 
            font-weight: bold; 
            margin-bottom: 12px; 
            line-height: 1.5; 
        }
        .idp-passage-note { 
            font-family: Arial, sans-serif;
            font-size: 14px; 
            font-style: italic; 
            color: #666; 
            margin-bottom: 12px; 
        }
        .idp-passage-text { 
            font-family: Arial, sans-serif;
            font-size: 16px; 
            line-height: 1.5; 
            text-align: justify;
            color: #000;
        }
        .idp-passage-text p { margin-bottom: 14px; }
        
        /* Force Arial on ALL passage content elements - Override any inline styles */
        .idp-passage-text,
        .idp-passage-text *,
        .idp-passage-text p,
        .idp-passage-text span,
        .idp-passage-text div,
        .idp-passage-text strong,
        .idp-passage-text em,
        .idp-passage-text i,
        .idp-passage-text b {
            font-family: Arial, sans-serif !important;
        }
        
        /* Divider */
        .idp-divider {
            width: 18px;
            background: #e5e5e5;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: col-resize;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
        }
        .idp-divider-icon { color: #888; font-size: 12px; }
        
        /* Right Panel */
        .idp-right {
            flex: 1;
            background: rgb(249, 249, 249);
            overflow-y: auto;
            padding: 20px 28px;
        }
        
        
        /* ========== QUESTION STYLING ========== */
        .idp-questions-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }
        .idp-questions-instruction {
            font-size: 15px;
            margin-bottom: 20px;
            line-height: 1.6;
            color: #000;
        }
        .idp-questions-instruction strong {
            font-weight: bold;
        }
        .idp-help-link {
            float: right;
            color: #0066CC;
            font-size: 14px;
            text-decoration: none;
            font-weight: normal;
        }
        .idp-help-link:hover { text-decoration: underline; }
        
        /* Question Number Badge */
        .idp-q-num {
            display: inline-block;
            min-width: 18px;
            margin-right: 8px;
            font-size: 16px;
            font-weight: bold;
            color: #000;
            text-align: left;
        }
        
        /* Question Text - Black color like IDP */
        .idp-q-text {
            color: #000;
            font-size: 16px;
            line-height: 1.5;
        }
        
        /* Radio/Checkbox Options */
        .idp-options { margin-top: 8px; margin-left: 28px; }
        .idp-option {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 6px;
            cursor: pointer;
        }
        .idp-option input[type="radio"],
        .idp-option input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-top: 2px;
            cursor: pointer;
        }
        .idp-option label {
            font-size: 16px;
            color: #000;
            cursor: pointer;
            line-height: 1.5;
        }
        
        /* Input Box - Dashed blue border */
        .idp-input {
            display: inline-block;
            min-width: 80px;
            height: 24px;
            border: 1px dashed #0066CC;
            padding: 2px 8px;
            font-size: 14px;
            text-align: center;
            background: #fff;
        }
        .idp-input:focus {
            outline: none;
            border: 2px solid #0066CC;
            border-style: solid;
        }
        
        /* Larger input for sentence completion */
        .idp-input-lg {
            min-width: 120px;
            height: 26px;
        }
        
        /* Question Item Container */
        .idp-question {
            margin-bottom: 14px;
            line-height: 1.8;
        }
        
        /* Word Bank */
        .idp-word-bank {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 16px;
        }
        .idp-word {
            padding: 4px 10px;
            border: 1px solid #999;
            background: #fff;
            font-size: 13px;
            cursor: grab;
        }
        .idp-word:hover { background: #f0f0f0; }
        .idp-word.used { opacity: 0.4; text-decoration: line-through; }
        
        /* Matching Table */
        .idp-match-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 12px;
        }
        .idp-match-table th {
            background: #fff;
            padding: 8px 10px;
            text-align: center;
            border: 1px solid #ccc;
            font-weight: bold;
        }
        .idp-match-table td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            background: #fff;
        }
        .idp-match-table td:first-child { text-align: left; }
        .idp-match-table td:not(:first-child) { text-align: center; width: 40px; }
        .idp-match-table input[type="radio"] { width: 16px; height: 16px; }
        
        /* Note/Form Completion */
        .idp-note-title { font-weight: bold; font-size: 15px; margin-bottom: 10px; }
        .idp-note-section { margin-bottom: 16px; }
        .idp-note-subtitle { font-weight: bold; font-size: 14px; margin-bottom: 6px; }
        .idp-note-list { list-style: disc; padding-left: 20px; }
        .idp-note-list li { margin-bottom: 6px; font-size: 14px; line-height: 1.8; }
        .idp-note-bullet { list-style: none; padding-left: 0; }
        .idp-note-bullet li::before { content: "•"; margin-right: 8px; }
        .idp-note-dash { list-style: none; padding-left: 20px; }
        .idp-note-dash li::before { content: "-"; margin-right: 8px; }
        
        /* Flow Chart */
        .idp-flowchart { padding: 10px 0; }
        .idp-flow-box {
            background: #f5f5f5;
            border: 1px solid #ccc;
            padding: 10px 14px;
            margin-bottom: 4px;
            font-size: 14px;
            line-height: 1.6;
        }
        .idp-flow-box.highlight { background: #fff; border-color: #0066CC; }
        .idp-flow-arrow { text-align: center; font-size: 16px; color: #666; margin: 2px 0; }
        
        /* Table Completion */
        .idp-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .idp-table th {
            background: #e8e8e8;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #ccc;
            font-weight: bold;
        }
        .idp-table td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            background: #fff;
        }
        
        /* Map/Diagram - Two column layout */
        .idp-map-layout { display: flex; gap: 20px; }
        .idp-map-image { flex: 1; }
        .idp-map-image img { max-width: 100%; border: 1px solid #ccc; }
        .idp-map-questions { flex: 1; }
        
        /* Group Separator */
        .idp-group-separator {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        
        /* ========== WRITING SECTION ========== */
        .idp-writing-layout { display: flex; height: 100%; }
        .idp-writing-left {
            flex: 1;
            padding: 20px 28px;
            overflow-y: auto;
            background: #fff;
            border-right: 1px solid #ccc;
        }
        .idp-writing-right {
            flex: 1;
            padding: 20px;
            background: #f5f5f0;
            display: flex;
            flex-direction: column;
        }
        .idp-textarea {
            flex: 1;
            width: 100%;
            border: 1px solid #ccc;
            padding: 12px;
            font-size: 15px;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            resize: none;
        }
        .idp-textarea:focus { outline: 2px solid #0066CC; }
        .idp-word-count { text-align: right; padding: 6px 0; font-size: 13px; color: #666; }
        
        /* ========== LISTENING OVERLAY ========== */
        .idp-audio-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.97);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        .idp-audio-overlay.hidden { display: none; }
        .idp-audio-icon { font-size: 60px; color: #555; margin-bottom: 20px; }
        .idp-audio-msg { font-size: 15px; text-align: center; max-width: 450px; margin-bottom: 20px; line-height: 1.5; }
        .idp-play-btn {
            padding: 12px 32px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .idp-play-btn:hover { background: #333; }
        
        /* ========== FOOTER ========== */
        .idp-footer {
            height: 44px;
            background: #fff;
            border-top: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 1000;
        }
        .idp-parts-nav { display: flex; align-items: center; gap: 16px; }
        .idp-part-group { display: flex; align-items: center; gap: 8px; }
        .idp-part-label { font-size: 13px; color: #000; font-weight: normal; }
        .idp-part-label.active { font-weight: bold; }
        .idp-q-nums { 
            display: flex; 
            align-items: center;
            gap: 4px;
        }
        .idp-q-num-footer {
            font-size: 13px;
            color: #666;
            font-weight: normal;
        }
        
        .idp-nav-btns { display: flex; align-items: center; gap: 8px; }
        .idp-nav-arrow {
            width: 40px; height: 40px;
            border-radius: 4px;
            border: none;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .idp-nav-arrow.prev { background: #d8d8d8; color: #fff; }
        .idp-nav-arrow.next { background: #1a1a1a; color: #fff; }
        .idp-nav-arrow:hover { opacity: 0.85; }
        .idp-submit-btn {
            width: 40px; height: 40px;
            background: #f0f0f0;
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.2s;
        }
        .idp-submit-btn:hover { background: #e5e5e5; }
        
        /* ========== TABLE COMPLETION STYLES ========== */
        .table-completion-container {
            margin: 20px 0;
            overflow-x: auto;
        }

        .idp-table-completion-styled {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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
            font-size: 14px;
        }

        .idp-table-completion-styled td {
            padding: 12px 16px;
            border: 1px solid #c0c0c0;
            vertical-align: top;
            line-height: 1.6;
            font-size: 14px;
            color: #333;
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
            margin: 4px 0;
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
            flex-shrink: 0;
        }

        .idp-table-input {
            border: 2px dashed #000000;
            padding: 6px 12px;
            border-radius: 3px;
            font-size: 14px;
            min-width: 120px;
            background: #ffffff;
            transition: all 0.2s ease;
            font-family: inherit;
            white-space: nowrap;
        }

        .idp-table-input:focus {
            outline: none;
            border-color: #333333;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        .idp-table-input:hover {
            border-color: #333333;
        }

        /* Fallback style for simple question list */
        .idp-questions-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .idp-question-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .idp-q-label {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        
        /* Scrollbar */
        .idp-left::-webkit-scrollbar, .idp-right::-webkit-scrollbar { width: 8px; }
        .idp-left::-webkit-scrollbar-thumb, .idp-right::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        
        /* Modal */
        .idp-modal { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 3000; }
        .idp-modal.hidden { display: none; }
        .idp-modal-box { background: #fff; padding: 28px 36px; border-radius: 8px; text-align: center; max-width: 400px; }
        .idp-modal-title { font-size: 17px; font-weight: bold; margin-bottom: 12px; }
        .idp-modal-text { font-size: 14px; margin-bottom: 20px; }
        .idp-modal-btns { display: flex; gap: 10px; justify-content: center; }
        .idp-modal-btn { padding: 8px 24px; font-size: 14px; border: none; border-radius: 4px; cursor: pointer; }
        .idp-modal-btn.cancel { background: #ddd; }
        .idp-modal-btn.confirm { background: #CC6600; color: #fff; }
    </style>
</head>
<body>
    @php
        $skill = $currentSection->skill ?? 'reading';
        $partNum = $currentSection->section_number ?? 1;
        $allQuestions = $questions ?? collect([]);
        $firstQ = $allQuestions->first();
        $lastQ = $allQuestions->last();
        $qStart = $firstQ->question_number ?? 1;
        $qEnd = $lastQ->question_number ?? ($qStart + $allQuestions->count() - 1);
        
        // Group questions by question_type for proper rendering
        // If questions have the same type and consecutive order, group them together
        $groupedQuestions = collect([]);
        $currentGroup = [];
        $currentType = null;
        
        foreach($allQuestions as $q) {
            $qType = $q->question_type ?? 'fill_blank';
            
            // Start new group if type changes
            if($qType !== $currentType) {
                if(!empty($currentGroup)) {
                    $groupedQuestions->push(collect($currentGroup));
                }
                $currentGroup = [$q];
                $currentType = $qType;
            } else {
                $currentGroup[] = $q;
            }
        }
        
        // Add last group
        if(!empty($currentGroup)) {
            $groupedQuestions->push(collect($currentGroup));
        }
        
        // Helper function to get instruction text based on question type
        if (!function_exists('getQuestionInstruction')) {
            function getQuestionInstruction($type) {
                $instructions = [
                    'true_false_not_given' => 'Choose <strong>TRUE</strong> if the statement agrees with the information given in the text, choose <strong>FALSE</strong> if the statement contradicts the information, or choose <strong>NOT GIVEN</strong> if there is no information on this.',
                    'tfng' => 'Choose <strong>TRUE</strong> if the statement agrees with the information given in the text, choose <strong>FALSE</strong> if the statement contradicts the information, or choose <strong>NOT GIVEN</strong> if there is no information on this.',
                    
                    'yes_no_not_given' => 'Choose <strong>YES</strong> if the statement agrees with the views of the writer, choose <strong>NO</strong> if the statement contradicts the views of the writer, or choose <strong>NOT GIVEN</strong> if it is impossible to say what the writer thinks about this.',
                    'ynng' => 'Choose <strong>YES</strong> if the statement agrees with the views of the writer, choose <strong>NO</strong> if the statement contradicts the views of the writer, or choose <strong>NOT GIVEN</strong> if it is impossible to say what the writer thinks about this.',
                    
                    'multiple_choice' => 'Choose the correct answer.',
                    'mcq' => 'Choose the correct answer.',
                    'single_choice' => 'Choose the correct answer.',
                    'multiple_choice_single' => 'Choose the correct answer.',
                    
                    'multiple_choice_multiple' => 'Choose <strong>TWO</strong> correct answers.',
                    'mcq_multiple' => 'Choose <strong>TWO</strong> correct answers.',
                    'choose_two' => 'Choose <strong>TWO</strong> correct answers.',
                    'choose_three' => 'Choose <strong>THREE</strong> correct answers.',
                    
                    'sentence_completion' => 'Complete the sentences. Write <strong>ONE WORD ONLY</strong> from the text for each answer.',
                    'short_answer' => 'Answer the questions. Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> for each answer.',
                    
                    'summary_completion' => 'Complete the summary using the list of words below.',
                    'summary' => 'Complete the summary using the list of words below.',
                    
                    'note_completion' => 'Complete the notes. Write <strong>ONE WORD ONLY</strong> from the text for each answer.',
                    'form_completion' => 'Complete the form. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
                    
                    'table_completion' => 'Complete the table. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
                    
                    'flowchart' => 'Complete the flow-chart. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
                    'flow_chart' => 'Complete the flow-chart. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
                    
                    'matching' => 'Match each statement with the correct option.',
                    'matching_features' => 'Match each statement with the correct option. You may use any option more than once.',
                    'matching_information' => 'Match each statement with the correct paragraph.',
                    'matching_headings' => 'Choose the correct heading for each paragraph from the list of headings.',
                    
                    'map_labeling' => 'Label the map/plan. Choose the correct labels from the box.',
                    'diagram_labeling' => 'Label the diagram. Choose the correct labels from the box.',
                    
                    'drag_drop' => 'Drag the correct answer to each gap.',
                    'dragdrop' => 'Drag the correct answer to each gap.',
                    
                    // Fallback for any other types
                    'fill_blank' => 'Complete the gaps. Write your answer in the box provided.',
                ];
                
                return $instructions[$type] ?? 'Answer the questions below.';
            }
        }
    @endphp

    {{-- HEADER --}}
    <header class="idp-header">
        <div class="idp-header-left">
            <span class="idp-logo">IELTS</span>
            <span class="idp-test-taker">Test taker ID</span>
            @if($skill === 'listening')
                <span class="idp-audio-playing">Audio is playing</span>
            @elseif($skill === 'speaking')
                <span class="idp-audio-playing" style="color: #E31837;">🎤 Speaking Mode</span>
            @endif
        </div>
        <div class="idp-header-right">
            <span class="idp-header-icon">📶</span>
            <span class="idp-header-icon">🔔</span>
            <span class="idp-header-icon">☰</span>
        </div>
    </header>

    {{-- PART BAR --}}
    <div class="idp-part-bar">
        <span class="idp-part-title">Part {{ $partNum }}</span>
        <span class="idp-part-instruction">
            @if($skill === 'reading')
                Read the text and answer questions <a href="#">{{ $qStart }}-{{ $qEnd }}</a>.
            @elseif($skill === 'listening')
                Listen and answer questions {{ $qStart }}-{{ $qEnd }}.
            @elseif($skill === 'writing')
                @if($partNum == 1)
                    You should spend about 20 minutes on this task. Write at least 150 words.
                @else
                    You should spend about 40 minutes on this task. Write at least 250 words.
                @endif
            @elseif($skill === 'speaking')
                @if($partNum == 1)
                    Answer the examiner's questions about yourself and familiar topics.
                @elseif($partNum == 2)
                    You have 1 minute to prepare, then speak for 1-2 minutes on the topic.
                @else
                    Discuss more abstract ideas and issues related to Part 2.
                @endif
            @endif
        </span>
    </div>

    {{-- MAIN CONTENT --}}
    <main class="idp-main">
        @if($skill === 'speaking')
            {{-- SPEAKING LAYOUT - Complete with Mic Check --}}
            @include('design_1.panel.ielts_tests.partials.idp_speaking_complete', [
                'section' => $currentSection,
                'question' => $firstQ,
                'userAnswer' => $userAnswers[$firstQ->id ?? 0] ?? ''
            ])
        @elseif($skill === 'writing')
            {{-- WRITING LAYOUT - Complete with Word Count --}}
            @include('design_1.panel.ielts_tests.partials.idp_writing_complete', [
                'section' => $currentSection,
                'question' => $firstQ,
                'userAnswer' => $userAnswers[$firstQ->id ?? 0] ?? ''
            ])
        @elseif($skill === 'listening' && empty($currentSection->passage_text))
            {{-- LISTENING FULL WIDTH (no passage) --}}
            <div class="idp-right" style="flex: none; width: 100%;">
                @include('design_1.panel.ielts_tests.partials.idp_questions_panel', [
                    'groupedQuestions' => $groupedQuestions,
                    'userAnswers' => $userAnswers,
                    'skill' => $skill
                ])
            </div>
        @else
            {{-- READING / LISTENING WITH PASSAGE --}}
            <div class="idp-left" id="leftPanel">
                @if(!empty($currentSection->passage_title))
                    <h2 class="idp-passage-title">{{ $currentSection->passage_title }}</h2>
                @endif
                @if(!empty($currentSection->subtitle))
                    <p class="idp-passage-note">{{ $currentSection->subtitle }}</p>
                @endif
                <div class="idp-passage-text">
                    {!! $currentSection->passage_text ?? $currentSection->content ?? '' !!}
                </div>
            </div>
            
            <div class="idp-divider" id="divider"><span class="idp-divider-icon">↔</span></div>
            
            <div class="idp-right" id="rightPanel">
                @include('design_1.panel.ielts_tests.partials.idp_questions_panel', [
                    'groupedQuestions' => $groupedQuestions,
                    'userAnswers' => $userAnswers,
                    'skill' => $skill
                ])
            </div>
        @endif
    </main>

    {{-- FOOTER --}}
    @php
        $sections = $test->sections()->where('skill', $skill)->orderBy('sort_order')->get();
        $partsData = [];
        foreach($sections as $idx => $sec) {
            $secQs = $sec->questions()->orderBy('question_number')->pluck('question_number', 'id')->toArray();
            $answered = 0;
            foreach($secQs as $qid => $qnum) {
                if(!empty($userAnswers[$qid] ?? null)) $answered++;
            }
            $partsData[$sec->section_number ?? ($idx+1)] = [
                'section_id' => $sec->id,
                'questions' => array_values($secQs),
                'answered' => $answered,
                'total' => count($secQs)
            ];
        }
    @endphp
    
    <footer class="idp-footer">
        <div class="idp-parts-nav">
            @foreach($partsData as $pNum => $pInfo)
                <div class="idp-part-group">
                    <span class="idp-part-label {{ $pNum == $partNum ? 'active' : '' }}">Part {{ $pNum }}</span>
                    <div class="idp-q-nums">
                        @foreach($pInfo['questions'] as $qn)
                            <span class="idp-q-num-footer">{{ $qn }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="idp-nav-btns">
            <button class="idp-nav-arrow prev" onclick="prevQ()">←</button>
            <button class="idp-nav-arrow next" onclick="nextQ()">→</button>
            <button class="idp-submit-btn" onclick="showModal()">✓</button>
        </div>
    </footer>

    {{-- LISTENING AUDIO OVERLAY --}}
    @if($skill === 'listening')
        <div class="idp-audio-overlay" id="audioOverlay">
            <div class="idp-audio-icon">🎧</div>
            <p class="idp-audio-msg">You will be listening to an audio clip during this test. You will not be permitted to pause or rewind the audio while answering the questions.<br><br>To continue, click Play.</p>
            <button class="idp-play-btn" onclick="playAudio()">▶ Play</button>
        </div>
        <audio id="audioPlayer" src="{{ $currentSection->audio_url ?? '' }}"></audio>
    @endif

    {{-- SUBMIT MODAL --}}
    <div class="idp-modal hidden" id="submitModal">
        <div class="idp-modal-box">
            <div class="idp-modal-title">Submit Section?</div>
            <p class="idp-modal-text">Are you sure you want to submit? You cannot change your answers after submission.</p>
            <div class="idp-modal-btns">
                <button class="idp-modal-btn cancel" onclick="hideModal()">Cancel</button>
                <button class="idp-modal-btn confirm" onclick="submitSection()">Submit</button>
            </div>
        </div>
    </div>

    <script>
        const attemptId = {{ $attempt->id }};
        const csrf = '{{ csrf_token() }}';
        const saveUrl = '{{ route("panel.ielts_tests.save_answer", $attempt->id) }}';
        const submitUrl = '{{ route("panel.ielts_tests.finish_section", $attempt->id) }}';
        
        // Save answer with error handling and logging
        function saveAnswer(qId, value) {
            console.log('💾 Saving answer:', { questionId: qId, value: value });
            
            fetch(saveUrl, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ question_id: qId, answer_text: value })
            })
            .then(r => {
                if (!r.ok) {
                    console.error('❌ Save failed with status:', r.status);
                    throw new Error('Network response was not ok');
                }
                return r.json();
            })
            .then(d => {
                console.log('✅ Answer saved successfully:', d);
                const btn = document.querySelector(`.idp-q-btn[data-num="${qId}"]`);
                if(btn && value) btn.classList.add('answered');
                
                // Visual feedback
                const input = document.querySelector(`[data-qid="${qId}"]`);
                if (input) {
                    input.style.borderColor = '#10b981';
                    setTimeout(() => { input.style.borderColor = ''; }, 1000);
                }
            })
            .catch(err => {
                console.error('❌ Error saving answer:', err);
                // Visual feedback for error
                const input = document.querySelector(`[data-qid="${qId}"]`);
                if (input) {
                    input.style.borderColor = '#ef4444';
                    setTimeout(() => { input.style.borderColor = ''; }, 2000);
                }
            });
        }
        
        let saveTimer;
        function autoSave(qId, value) {
            clearTimeout(saveTimer);
            saveTimer = setTimeout(() => saveAnswer(qId, value), 600);
        }
        
        // Navigation
        function goTo(num) {
            const el = document.querySelector(`[data-q-num="${num}"]`);
            if(el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        function prevQ() { /* implement */ }
        function nextQ() { /* implement */ }
        
        // Modal
        function showModal() { document.getElementById('submitModal').classList.remove('hidden'); }
        function hideModal() { document.getElementById('submitModal').classList.add('hidden'); }
        function submitSection() {
            const btn = document.querySelector('#submitModal .confirm');
            if(btn) {
                btn.disabled = true;
                btn.textContent = 'Submitting...';
            }
            
            fetch(submitUrl, { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                } 
            })
            .then(r => {
                if(!r.ok) throw new Error('Network response was not ok: ' + r.status);
                return r.json();
            })
            .then(d => {
                console.log('Submit response:', d);
                if(d.redirect) {
                    window.location.href = d.redirect;
                } else if(d.error) {
                    alert('Error: ' + d.error);
                    if(btn) {
                        btn.disabled = false;
                        btn.textContent = 'Submit';
                    }
                }
            })
            .catch(err => {
                console.error('Submit error:', err);
                alert('An error occurred while submitting. Please try again.');
                if(btn) {
                    btn.disabled = false;
                    btn.textContent = 'Submit';
                }
            });
        }
        
        // Audio
        function playAudio() {
            document.getElementById('audioOverlay').classList.add('hidden');
            const audio = document.getElementById('audioPlayer');
            if(audio) audio.play();
        }
        
        // Word count for writing
        function updateWordCount() {
            const ta = document.getElementById('writingAnswer');
            const wc = document.getElementById('wordCount');
            if(ta && wc) {
                const words = ta.value.trim() ? ta.value.trim().split(/\s+/).length : 0;
                wc.textContent = words;
            }
        }
        
        // Resizable divider
        document.addEventListener('DOMContentLoaded', function() {
            const divider = document.getElementById('divider');
            const left = document.getElementById('leftPanel');
            const right = document.getElementById('rightPanel');
            if(divider && left && right) {
                let resizing = false;
                divider.addEventListener('mousedown', () => { resizing = true; document.body.style.cursor = 'col-resize'; });
                document.addEventListener('mousemove', e => {
                    if(!resizing) return;
                    const pct = (e.clientX / window.innerWidth) * 100;
                    if(pct > 20 && pct < 80) {
                        left.style.flex = `0 0 ${pct}%`;
                        right.style.flex = `0 0 ${100-pct-1.5}%`;
                    }
                });
                document.addEventListener('mouseup', () => { resizing = false; document.body.style.cursor = ''; });
            }
            updateWordCount();
        });
    </script>
</body>
</html>
