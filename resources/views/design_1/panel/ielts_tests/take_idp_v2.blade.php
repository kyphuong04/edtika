{{-- 
    IDP IELTS CBT Interface - 100% Exact Replica
    Based on official IDP/British Council Computer-Based Test
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IELTS {{ ucfirst($currentSection->skill ?? 'Test') }} - {{ $test->title ?? 'Test' }}</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* =============================================
           IDP IELTS CBT - 100% EXACT REPLICA
           ============================================= */
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #000000;
            background: #ffffff;
            line-height: 1.5;
            overflow: hidden;
            height: 100vh;
        }
        
        /* =============================================
           HEADER - Exact IDP Style
           ============================================= */
        .idp-header {
            height: 48px;
            background: #000000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .idp-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .idp-logo {
            font-size: 22px;
            font-weight: bold;
            color: #E31837;
            font-style: italic;
            letter-spacing: 1px;
        }
        
        .idp-test-taker {
            color: #ffffff;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .idp-test-taker-label {
            color: #ffffff;
            font-weight: normal;
        }
        
        .idp-audio-indicator {
            color: #ffffff;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .idp-audio-indicator::before {
            content: "🔊";
            font-size: 14px;
        }
        
        .idp-header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .idp-header-icon {
            color: #ffffff;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.9;
        }
        
        .idp-header-icon:hover {
            opacity: 1;
        }
        
        /* =============================================
           PART BAR - Orange/Brown Bar
           ============================================= */
        .idp-part-bar {
            height: 56px;
            background: #CC6600;
            display: flex;
            align-items: center;
            padding: 0 20px;
            position: fixed;
            top: 48px;
            left: 0;
            right: 0;
            z-index: 999;
        }
        
        .idp-part-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .idp-part-title {
            color: #ffffff;
            font-size: 15px;
            font-weight: bold;
        }
        
        .idp-part-instruction {
            color: #ffffff;
            font-size: 14px;
            font-weight: normal;
        }
        
        .idp-part-instruction a {
            color: #00BFFF;
            text-decoration: underline;
        }
        
        /* =============================================
           MAIN CONTENT AREA
           ============================================= */
        .idp-main {
            display: flex;
            position: fixed;
            top: 104px; /* header + part bar */
            left: 0;
            right: 0;
            bottom: 52px; /* footer height */
            background: #f5f5f5;
        }
        
        /* Left Panel - Reading Passage / Listening Content */
        .idp-panel-left {
            flex: 1;
            background: #ffffff;
            overflow-y: auto;
            padding: 24px 32px;
            border-right: 1px solid #d9d9d9;
        }
        
        .idp-passage-title {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 20px;
        }
        
        .idp-passage-note {
            font-size: 14px;
            font-style: italic;
            color: #666666;
            margin-bottom: 16px;
        }
        
        .idp-passage-text {
            font-size: 16px;
            line-height: 1.7;
            color: #000000;
            text-align: justify;
        }
        
        .idp-passage-text p {
            margin-bottom: 16px;
        }
        
        /* Highlighted text (user can highlight) */
        .idp-highlight-yellow {
            background: #FFFF00;
        }
        
        /* Divider - Resizable */
        .idp-divider {
            width: 24px;
            background: #e8e8e8;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: col-resize;
            flex-shrink: 0;
            border-left: 1px solid #d9d9d9;
            border-right: 1px solid #d9d9d9;
        }
        
        .idp-divider-icon {
            color: #999999;
            font-size: 16px;
        }
        
        /* Right Panel - Questions */
        .idp-panel-right {
            flex: 1;
            background: #f7f7f7;
            overflow-y: auto;
            padding: 24px 32px;
        }
        
        /* Question Group Header */
        .idp-question-header {
            margin-bottom: 20px;
        }
        
        .idp-question-range {
            font-size: 16px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 8px;
        }
        
        .idp-question-instruction {
            font-size: 15px;
            color: #000000;
            line-height: 1.5;
        }
        
        .idp-question-instruction strong {
            font-weight: bold;
        }
        
        /* Help Button */
        .idp-help-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            float: right;
            color: #0066CC;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .idp-help-btn:hover {
            text-decoration: underline;
        }
        
        /* =============================================
           QUESTION TYPES - Exact IDP Styling
           ============================================= */
        
        /* Single Question Item */
        .idp-question {
            margin-bottom: 24px;
        }
        
        .idp-question-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 24px;
            padding: 0 6px;
            border: 1px solid #CC6600;
            color: #CC6600;
            font-size: 13px;
            font-weight: bold;
            margin-right: 8px;
            background: #ffffff;
        }
        
        .idp-question-text {
            font-size: 15px;
            color: #0066CC;
            display: inline;
        }
        
        /* TRUE/FALSE/NOT GIVEN Options */
        .idp-tfng-options {
            margin-top: 12px;
            padding-left: 36px;
        }
        
        .idp-radio-option {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        
        .idp-radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #000000;
        }
        
        .idp-radio-option label {
            font-size: 15px;
            color: #000000;
            cursor: pointer;
        }
        
        /* Multiple Choice (Checkbox) */
        .idp-checkbox-options {
            margin-top: 12px;
        }
        
        .idp-checkbox-option {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        
        .idp-checkbox-option input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin-top: 2px;
            accent-color: #000000;
        }
        
        .idp-checkbox-option label {
            font-size: 15px;
            color: #000000;
            cursor: pointer;
            line-height: 1.4;
        }
        
        /* Fill in Blank / Text Input */
        .idp-text-input {
            display: inline-block;
            width: 120px;
            height: 28px;
            border: 1px solid #0066CC;
            border-style: dashed;
            padding: 2px 8px;
            font-size: 15px;
            color: #000000;
            background: #ffffff;
            text-align: center;
        }
        
        .idp-text-input:focus {
            outline: 2px solid #0066CC;
            border-style: solid;
        }
        
        .idp-text-input::placeholder {
            color: #0066CC;
        }
        
        /* Sentence Completion with Input */
        .idp-sentence-completion {
            font-size: 15px;
            color: #000000;
            line-height: 2;
        }
        
        /* Matching Table */
        .idp-matching-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 14px;
        }
        
        .idp-matching-table th {
            background: #ffffff;
            padding: 12px 16px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #d9d9d9;
            min-width: 40px;
        }
        
        .idp-matching-table td {
            padding: 12px 16px;
            border: 1px solid #d9d9d9;
            background: #ffffff;
        }
        
        .idp-matching-table td:first-child {
            text-align: left;
        }
        
        .idp-matching-table td:not(:first-child) {
            text-align: center;
        }
        
        .idp-matching-table input[type="radio"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        /* Word Bank / Drag Drop */
        .idp-word-bank {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
            padding: 16px;
            background: #ffffff;
            border: 1px solid #d9d9d9;
        }
        
        .idp-word-item {
            padding: 6px 12px;
            border: 1px solid #d9d9d9;
            background: #ffffff;
            font-size: 14px;
            cursor: grab;
            user-select: none;
        }
        
        .idp-word-item:active {
            cursor: grabbing;
        }
        
        .idp-word-item.used {
            opacity: 0.5;
            text-decoration: line-through;
        }
        
        /* Drop Zone */
        .idp-drop-zone {
            display: inline-block;
            min-width: 150px;
            height: 28px;
            border: 1px dashed #0066CC;
            background: #ffffff;
            vertical-align: middle;
            text-align: center;
            line-height: 26px;
            color: #0066CC;
            font-size: 14px;
        }
        
        .idp-drop-zone.filled {
            border-style: solid;
            color: #000000;
        }
        
        /* Flow Chart */
        .idp-flowchart {
            padding: 16px;
        }
        
        .idp-flowchart-box {
            background: #f0f0f0;
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 4px;
            font-size: 14px;
            position: relative;
        }
        
        .idp-flowchart-box.active {
            background: #e6f7ff;
            border: 1px solid #1890ff;
        }
        
        .idp-flowchart-arrow {
            text-align: center;
            font-size: 20px;
            color: #666666;
            margin: 4px 0;
        }
        
        /* Note/Form Completion */
        .idp-note-section {
            margin-bottom: 20px;
        }
        
        .idp-note-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 12px;
        }
        
        .idp-note-list {
            list-style: disc;
            padding-left: 24px;
        }
        
        .idp-note-list li {
            margin-bottom: 8px;
            font-size: 15px;
        }
        
        .idp-note-list li::marker {
            color: #000000;
        }
        
        /* Table Completion */
        .idp-table-completion {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        .idp-table-completion th {
            background: #e8e8e8;
            padding: 10px 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #d9d9d9;
        }
        
        .idp-table-completion td {
            padding: 10px 12px;
            border: 1px solid #d9d9d9;
            background: #ffffff;
        }
        
        /* Map/Diagram Labeling */
        .idp-map-container {
            position: relative;
            display: inline-block;
        }
        
        .idp-map-image {
            max-width: 100%;
            height: auto;
        }
        
        .idp-map-label {
            position: absolute;
            padding: 4px 8px;
            background: #ffffff;
            border: 1px solid #000000;
            font-size: 13px;
            font-weight: bold;
        }
        
        /* =============================================
           WRITING TASK SPECIFIC
           ============================================= */
        .idp-writing-container {
            display: flex;
            height: 100%;
        }
        
        .idp-writing-left {
            flex: 1;
            padding: 24px 32px;
            overflow-y: auto;
            background: #ffffff;
            border-right: 1px solid #d9d9d9;
        }
        
        .idp-writing-task {
            font-size: 15px;
            line-height: 1.7;
            color: #000000;
        }
        
        .idp-writing-task ul {
            list-style: disc;
            padding-left: 24px;
            margin: 12px 0;
        }
        
        .idp-writing-task li {
            margin-bottom: 8px;
        }
        
        .idp-writing-right {
            flex: 1;
            padding: 24px 32px;
            background: #f7f7f7;
            display: flex;
            flex-direction: column;
        }
        
        .idp-writing-textarea {
            flex: 1;
            width: 100%;
            border: 1px solid #d9d9d9;
            padding: 16px;
            font-size: 16px;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            resize: none;
            background: #ffffff;
        }
        
        .idp-writing-textarea:focus {
            outline: 2px solid #0066CC;
        }
        
        .idp-word-count {
            text-align: right;
            padding: 8px 0;
            font-size: 14px;
            color: #666666;
        }
        
        /* Chart/Graph Image for Task 1 */
        .idp-task-image {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border: 1px solid #d9d9d9;
        }
        
        /* =============================================
           LISTENING SPECIFIC
           ============================================= */
        .idp-audio-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        
        .idp-audio-icon {
            font-size: 80px;
            color: #666666;
            margin-bottom: 24px;
        }
        
        .idp-audio-message {
            font-size: 16px;
            color: #000000;
            text-align: center;
            max-width: 500px;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        
        .idp-audio-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 32px;
            background: #000000;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        
        .idp-audio-btn:hover {
            background: #333333;
        }
        
        /* =============================================
           FOOTER - Bottom Navigation
           ============================================= */
        .idp-footer {
            height: 52px;
            background: #ffffff;
            border-top: 1px solid #d9d9d9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        /* Part Tabs */
        .idp-part-tabs {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        
        .idp-part-tab {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .idp-part-tab-label {
            font-size: 14px;
            color: #000000;
            font-weight: 500;
        }
        
        .idp-part-tab-label.active {
            font-weight: bold;
        }
        
        /* Question Numbers */
        .idp-question-numbers {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .idp-q-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 4px;
            font-size: 13px;
            color: #000000;
            cursor: pointer;
            border: 1px solid transparent;
        }
        
        .idp-q-num:hover {
            background: #f0f0f0;
        }
        
        .idp-q-num.current {
            border: 2px solid #CC6600;
            font-weight: bold;
        }
        
        .idp-q-num.answered {
            background: #e6f7ff;
        }
        
        .idp-q-num.flagged {
            background: #fff7e6;
        }
        
        /* Part Progress */
        .idp-part-progress {
            font-size: 13px;
            color: #666666;
            margin-left: 8px;
        }
        
        /* Navigation Buttons */
        .idp-nav-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .idp-nav-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #333333;
            color: #ffffff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
        }
        
        .idp-nav-btn:hover {
            background: #555555;
        }
        
        .idp-nav-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }
        
        .idp-nav-btn.prev {
            background: #888888;
        }
        
        .idp-submit-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            color: #666666;
            border: 1px solid #d9d9d9;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            margin-left: 8px;
        }
        
        .idp-submit-btn:hover {
            background: #f5f5f5;
        }
        
        /* =============================================
           SCROLLBAR STYLING
           ============================================= */
        .idp-panel-left::-webkit-scrollbar,
        .idp-panel-right::-webkit-scrollbar {
            width: 8px;
        }
        
        .idp-panel-left::-webkit-scrollbar-track,
        .idp-panel-right::-webkit-scrollbar-track {
            background: #f0f0f0;
        }
        
        .idp-panel-left::-webkit-scrollbar-thumb,
        .idp-panel-right::-webkit-scrollbar-thumb {
            background: #cccccc;
            border-radius: 4px;
        }
        
        .idp-panel-left::-webkit-scrollbar-thumb:hover,
        .idp-panel-right::-webkit-scrollbar-thumb:hover {
            background: #999999;
        }
        
        /* =============================================
           RESPONSIVE
           ============================================= */
        @media (max-width: 1024px) {
            .idp-main {
                flex-direction: column;
            }
            
            .idp-panel-left,
            .idp-panel-right {
                flex: none;
                height: 50%;
            }
            
            .idp-divider {
                width: 100%;
                height: 24px;
                cursor: row-resize;
            }
        }
    </style>
    
    @php
    // Helper function to get question instruction based on type
    function getQuestionInstruction($type, $maxWords = null) {
        $instructions = [
            'true_false_not_given' => 'Choose <strong>TRUE</strong> if the statement agrees with the information given in the text, choose <strong>FALSE</strong> if the statement contradicts the information, or choose <strong>NOT GIVEN</strong> if there is no information on this.',
            
            'yes_no_not_given' => 'Choose <strong>YES</strong> if the statement agrees with the views of the writer, choose <strong>NO</strong> if the statement contradicts the views of the writer, or choose <strong>NOT GIVEN</strong> if it is impossible to say what the writer thinks about this.',
            
            'multiple_choice_single' => 'Choose the correct answer.',
            
            'multiple_choice_multiple' => 'Choose <strong>TWO</strong> correct answers.',
            
            'sentence_completion' => 'Complete the sentences. Write <strong>ONE WORD ONLY</strong> from the text for each answer.',
            
            'sentence_completion_two_words' => 'Complete the sentences. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
            
            'sentence_completion_three_words' => 'Complete the sentences. Write <strong>NO MORE THAN THREE WORDS</strong> from the text for each answer.',
            
            'sentence_endings' => 'Complete each sentence with the correct ending. Choose the correct answer and move it into the gap.',
            
            'note_completion' => 'Complete the notes. Write <strong>ONE WORD ONLY</strong> from the text for each answer.',
            
            'note_completion_two_words' => 'Complete the notes. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
            
            'note_completion_three_words' => 'Complete the notes. Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> from the text for each answer.',
            
            'summary_completion_list' => 'Complete the summary using the list of words. Choose the correct answer and move it into the gap.',
            
            'summary_completion_text' => 'Complete the summary. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
            
            'table_completion' => 'Complete the table. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
            
            'flow_chart_completion' => 'Complete the flow-chart. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
            
            'diagram_completion' => 'Label the diagram. Write <strong>NO MORE THAN TWO WORDS</strong> from the text for each answer.',
            
            'matching_headings' => 'Choose the correct heading for each paragraph from the list of headings.',
            
            'matching_information' => 'Match each statement with the correct paragraph.',
            
            'matching_features' => 'Match each statement with the correct option. You may use any option more than once.',
            
            'matching_sentence_endings' => 'Complete each sentence with the correct ending.',
            
            'short_answer' => 'Answer the questions. Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> for each answer.',
            
            'plan_map_diagram_labelling' => 'Label the plan/map/diagram. Choose the correct labels from the box and write them in the correct gap.',
        ];
        
        return $instructions[$type] ?? 'Answer the questions below.';
    }
    
    // Helper to group consecutive questions of same type
    function groupQuestions($questions) {
        if (empty($questions)) return [];
        
        $groups = [];
        $currentGroup = null;
        
        foreach ($questions as $question) {
            $type = $question->type ?? 'multiple_choice_single';
            
            if ($currentGroup === null || $currentGroup['type'] !== $type) {
                // Start new group
                if ($currentGroup !== null) {
                    $groups[] = $currentGroup;
                }
                
                $currentGroup = [
                    'type' => $type,
                    'start' => $question->order_number ?? $question->id,
                    'end' => $question->order_number ?? $question->id,
                    'questions' => [$question]
                ];
            } else {
                // Add to current group
                $currentGroup['end'] = $question->order_number ?? $question->id;
                $currentGroup['questions'][] = $question;
            }
        }
        
        // Add last group
        if ($currentGroup !== null) {
            $groups[] = $currentGroup;
        }
        
        return $groups;
    }
    @endphp
</head>
<body>
    {{-- Header --}}
    <header class="idp-header">
        <div class="idp-header-left">
            <div class="idp-logo">IELTS</div>
            <div class="idp-test-taker">
                <span class="idp-test-taker-label">Test taker ID</span>
            </div>
            @if(($currentSection->skill ?? '') === 'listening')
                <div class="idp-audio-indicator">Audio is playing</div>
            @endif
        </div>
        <div class="idp-header-right">
            <span class="idp-header-icon">📶</span>
            <span class="idp-header-icon">🔔</span>
            <span class="idp-header-icon">☰</span>
        </div>
    </header>
    
    {{-- Part Bar --}}
    <div class="idp-part-bar">
        <div class="idp-part-info">
            <div class="idp-part-title">Part {{ $currentPart ?? 1 }}</div>
            <div class="idp-part-instruction">
                @if(($currentSection->skill ?? '') === 'reading')
                    Read the text and answer questions <a href="#">{{ $questionStart ?? 1 }}-{{ $questionEnd ?? 3 }}</a>.
                @elseif(($currentSection->skill ?? '') === 'listening')
                    Listen and answer questions {{ $questionStart ?? 1 }}-{{ $questionEnd ?? 10 }}.
                @elseif(($currentSection->skill ?? '') === 'writing')
                    You should spend about {{ $taskTime ?? 20 }} minutes on this task. Write at least {{ $minWords ?? 150 }} words.
                @endif
            </div>
        </div>
    </div>
    
    {{-- Main Content --}}
    <main class="idp-main">
        @if(($currentSection->skill ?? '') === 'writing')
            {{-- Writing Layout --}}
            <div class="idp-writing-container">
                <div class="idp-writing-left">
                    @if(!empty($taskImage))
                        <img src="{{ $taskImage }}" alt="Task Image" class="idp-task-image">
                    @endif
                    <div class="idp-writing-task">
                        {!! $taskContent ?? 'Write about the following topic...' !!}
                    </div>
                </div>
                <div class="idp-divider">
                    <span class="idp-divider-icon">↔</span>
                </div>
                <div class="idp-writing-right">
                    <textarea class="idp-writing-textarea" 
                              id="writingAnswer" 
                              placeholder="Start writing here..."
                              oninput="updateWordCount()"></textarea>
                    <div class="idp-word-count">Words: <span id="wordCount">0</span></div>
                </div>
            </div>
        @else
            {{-- Reading/Listening Layout --}}
            <div class="idp-panel-left">
                @if(($currentSection->skill ?? '') === 'reading')
                    <h2 class="idp-passage-title">{{ $passageTitle ?? 'Reading Passage' }}</h2>
                    @if(!empty($passageNote))
                        <p class="idp-passage-note">{{ $passageNote }}</p>
                    @endif
                    <div class="idp-passage-text">
                        {!! $passageContent ?? '' !!}
                    </div>
                @elseif(($currentSection->skill ?? '') === 'listening')
                    {{-- Listening content like flowcharts, notes, maps --}}
                    <div class="idp-listening-content">
                        {!! $listeningContent ?? '' !!}
                    </div>
                @endif
            </div>
            
            <div class="idp-divider">
                <span class="idp-divider-icon">↔</span>
            </div>
            
            
            <div class="idp-panel-right">
                {{-- Questions Container with Grouping --}}
                @php
                    $questionGroups = groupQuestions($questions ?? []);
                @endphp
                
                @foreach($questionGroups as $group)
                    {{-- Question Group Header --}}
                    <div class="idp-question-header">
                        <a href="#" class="idp-help-btn">📧 Help</a>
                        <div class="idp-question-range">Questions {{ $group['start'] }}-{{ $group['end'] }}</div>
                        <div class="idp-question-instruction">
                            {!! getQuestionInstruction($group['type']) !!}
                        </div>
                    </div>
                    
                    {{-- Questions in this group --}}
                    <div class="idp-questions-container">
                        @foreach($group['questions'] as $question)
                            @include('design_1.panel.ielts_tests.partials.idp_question_' . ($question->type ?? 'mcq'), ['question' => $question])
                        @endforeach
                    </div>
                @endforeach
            </div>

        @endif
    </main>
    
    {{-- Footer Navigation --}}
    <footer class="idp-footer">
        <div class="idp-part-tabs">
            @foreach($parts ?? [] as $partNum => $part)
                <div class="idp-part-tab">
                    <span class="idp-part-tab-label {{ $partNum == ($currentPart ?? 1) ? 'active' : '' }}">
                        Part {{ $partNum }}
                    </span>
                    <div class="idp-question-numbers">
                        @foreach($part['questions'] ?? [] as $qNum)
                            <span class="idp-q-num {{ $qNum == ($currentQuestion ?? 1) ? 'current' : '' }} {{ in_array($qNum, $answeredQuestions ?? []) ? 'answered' : '' }}">
                                {{ $qNum }}
                            </span>
                        @endforeach
                    </div>
                    <span class="idp-part-progress">{{ $part['answered'] ?? 0 }} of {{ $part['total'] ?? 10 }}</span>
                </div>
            @endforeach
        </div>
        
        <div class="idp-nav-buttons">
            <button class="idp-nav-btn prev" onclick="previousQuestion()" {{ ($currentQuestion ?? 1) <= 1 ? 'disabled' : '' }}>
                ←
            </button>
            <button class="idp-nav-btn next" onclick="nextQuestion()">
                →
            </button>
            <button class="idp-submit-btn" onclick="confirmSubmit()">
                ✓
            </button>
        </div>
    </footer>
    
    {{-- Audio Overlay for Listening --}}
    @if(($currentSection->skill ?? '') === 'listening' && ($showAudioOverlay ?? false))
        <div class="idp-audio-overlay" id="audioOverlay">
            <div class="idp-audio-icon">🎧</div>
            <div class="idp-audio-message">
                You will be listening to an audio clip during this test. You will not be permitted to pause or rewind the audio while answering the questions.
                <br><br>
                To continue, click Play.
            </div>
            <button class="idp-audio-btn" onclick="startAudio()">
                ▶ Play
            </button>
        </div>
    @endif
    
    <script>
        // Word Count for Writing
        function updateWordCount() {
            const textarea = document.getElementById('writingAnswer');
            const wordCountSpan = document.getElementById('wordCount');
            if (textarea && wordCountSpan) {
                const text = textarea.value.trim();
                const words = text ? text.split(/\s+/).length : 0;
                wordCountSpan.textContent = words;
            }
        }
        
        // Navigation
        function previousQuestion() {
            // AJAX call to save current answer and go to previous
            console.log('Previous question');
        }
        
        function nextQuestion() {
            // AJAX call to save current answer and go to next
            console.log('Next question');
        }
        
        function confirmSubmit() {
            if (confirm('Are you sure you want to submit this section?')) {
                // Submit section
            }
        }
        
        // Audio for Listening
        function startAudio() {
            document.getElementById('audioOverlay').style.display = 'none';
            // Start audio playback
        }
        
        // Drag and Drop for Word Bank
        let draggedWord = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Make word items draggable
            document.querySelectorAll('.idp-word-item').forEach(item => {
                item.draggable = true;
                item.addEventListener('dragstart', function(e) {
                    draggedWord = this;
                    e.dataTransfer.setData('text', this.textContent);
                });
            });
            
            // Setup drop zones
            document.querySelectorAll('.idp-drop-zone').forEach(zone => {
                zone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                });
                
                zone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    if (draggedWord) {
                        this.textContent = draggedWord.textContent;
                        this.classList.add('filled');
                        draggedWord.classList.add('used');
                    }
                });
            });
            
            // Resizable divider
            const divider = document.querySelector('.idp-divider');
            const leftPanel = document.querySelector('.idp-panel-left, .idp-writing-left');
            const rightPanel = document.querySelector('.idp-panel-right, .idp-writing-right');
            
            if (divider && leftPanel && rightPanel) {
                let isResizing = false;
                
                divider.addEventListener('mousedown', function(e) {
                    isResizing = true;
                    document.body.style.cursor = 'col-resize';
                });
                
                document.addEventListener('mousemove', function(e) {
                    if (!isResizing) return;
                    
                    const containerWidth = leftPanel.parentElement.offsetWidth;
                    const newLeftWidth = (e.clientX / containerWidth) * 100;
                    
                    if (newLeftWidth > 20 && newLeftWidth < 80) {
                        leftPanel.style.flex = `0 0 ${newLeftWidth}%`;
                        rightPanel.style.flex = `0 0 ${100 - newLeftWidth - 3}%`;
                    }
                });
                
                document.addEventListener('mouseup', function() {
                    isResizing = false;
                    document.body.style.cursor = '';
                });
            }
        });
    </script>
</body>
</html>
