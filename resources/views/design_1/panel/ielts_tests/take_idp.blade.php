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
            background: #e5e5e5;
            line-height: 1.5;
            overflow: hidden;
            height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* ========== HEADER ========== */
        .idp-header {
            height: 60px;
            background: #fff;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: fixed;
            top: 12px; left: 12px; right: 12px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
        }
        .idp-header-user { display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0; }
        .idp-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #d9d9d9;
            border: 2px solid #bbb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: bold;
            color: #555;
            overflow: hidden;
            flex-shrink: 0;
        }
        .idp-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .idp-username {
            font-size: 14px;
            font-weight: 700;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .idp-timer {
            font-size: 17px;
            font-weight: 700;
            color: #111;
            letter-spacing: 2px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }
        
        /* Finish Button */
        .idp-finish-btn {
            padding: 8px 20px;
            background: #fff;
            border: 1.5px solid #333;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        
        .idp-finish-btn:hover {
            background: #f5f5f5;
            border-color: #000;
        }
        
        .idp-finish-btn::after {
            content: '→';
            font-size: 14px;
        }
        
        /* ========== PART BAR (hidden) ========== */
        .idp-part-bar { display: none; }
        .idp-part-title { display: none; }
        .idp-part-instruction { display: none; }
        
        /* ========== SECTION LABEL BAR ========== */
        .idp-section-label-bar {
            position: fixed;
            top: 84px; left: 12px; right: 12px;
            height: 50px;
            background: #d8d8d8;
            border-radius: 12px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 998;
        }
        .idp-section-label {
            font-size: 18px;
            font-weight: 900;
            color: #111;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        
        /* ========== MAIN LAYOUT ========== */
        .idp-main {
            display: flex;
            position: fixed;
            top: 142px; left: 12px; right: 12px; bottom: 104px;
            border-radius: 12px;
            overflow: hidden;
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
            overflow-x: hidden;
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
            max-width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            table-layout: auto;
        }
        .idp-table th {
            background: #e8e8e8;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #ccc;
            font-weight: bold;
            word-wrap: break-word;
            max-width: 200px;
        }
        .idp-table td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            background: #fff;
            word-wrap: break-word;
            max-width: 250px;
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
            height: 80px;
            background: transparent;
            display: flex;
            align-items: center;
            padding: 0 4px;
            position: fixed;
            bottom: 12px; left: 12px; right: 12px;
            z-index: 1000;
        }
        
        /* Two equal halves */
        .idp-footer-half {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 0;
        }
        
        /* Question Numbers — pill auto-sizes to content */
        .idp-question-numbers {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border-radius: 16px;
            padding: 12px 16px;
            overflow-x: auto;
            flex: 0 1 auto;
            min-width: 0;
            max-width: 100%;
        }
        
        /* Circular Question Button */
        .idp-q-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            border: 1.5px solid #555;
            color: #111;
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        
        .idp-q-circle:hover {
            border-color: #666;
            background: #f5f5f5;
        }
        
        .idp-q-circle.active {
            background: #333;
            color: #fff;
            border-color: #333;
        }
        
        .idp-q-circle.answered {
            background: #d4edda;
            border-color: #28a745;
        }
        
        .idp-q-circle.answered.active {
            background: #28a745;
            color: #fff;
            border-color: #28a745;
        }
        
        /* Navigation Buttons — centered in right half */
        .idp-nav-btns {
            display: flex;
            align-items: center;
            gap: 28px;
            flex-shrink: 0;
        }
        
        .idp-nav-text-btn {
            padding: 13px 22px;
            background: #fff;
            border: 1.5px solid #bbb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        
        .idp-nav-text-btn:hover {
            background: #f5f5f5;
            border-color: #888;
        }
        
        .idp-nav-text-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        
        .idp-nav-text-btn.prev::before {
            content: '←';
        }
        
        .idp-nav-text-btn.next::after {
            content: '→';
        }
        
        /* Scrollbar for question numbers */
        .idp-question-numbers::-webkit-scrollbar {
            height: 5px;
        }
        .idp-question-numbers::-webkit-scrollbar-thumb {
            background: #bbb;
            border-radius: 3px;
        }
        .idp-question-numbers::-webkit-scrollbar-track {
            background: transparent;
        }
        
        /* ========== TABLE COMPLETION STYLES ========== */
        .table-completion-container {
            margin: 20px 0;
            overflow-x: auto;
            width: 100%;
            max-width: 100%;
        }

        .idp-table-completion-styled {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            table-layout: auto;
        }

        .idp-table-completion-styled thead {
            background: #e8e8e8;
        }

        .idp-table-completion-styled th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #c0c0c0;
            color: #000000;
            font-size: 13px;
            word-wrap: break-word;
            max-width: 200px;
        }

        .idp-table-completion-styled td {
            padding: 10px 12px;
            border: 1px solid #c0c0c0;
            vertical-align: top;
            line-height: 1.6;
            font-size: 13px;
            color: #333;
            word-wrap: break-word;
            max-width: 250px;
        }

        .idp-table-completion-styled td .cell-text {
            display: inline;
            margin-bottom: 8px;
            word-wrap: break-word;
        }

        .tc-input-wrapper {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            padding: 4px 6px;
            border-radius: 4px;
            border: none;
            margin: 4px 0;
            flex-wrap: nowrap;
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
            padding: 6px 10px;
            border-radius: 3px;
            font-size: 13px;
            min-width: 100px;
            max-width: 180px;
            width: 100%;
            background: #ffffff;
            transition: all 0.2s ease;
            font-family: inherit;
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
        
        /* ========== RESPONSIVE STYLES ========== */
        @media (max-width: 1200px) {
            .idp-table-completion-styled th {
                padding: 8px 10px;
                font-size: 12px;
                max-width: 150px;
            }
            
            .idp-table-completion-styled td {
                padding: 8px 10px;
                font-size: 12px;
                max-width: 180px;
            }
            
            .idp-table-input {
                min-width: 80px;
                max-width: 150px;
                font-size: 12px;
                padding: 5px 8px;
            }
            
            .tc-question-number {
                min-width: 24px;
                height: 24px;
                font-size: 12px;
            }
        }
        
        @media (max-width: 768px) {
            .idp-table-completion-styled {
                font-size: 11px;
            }
            
            .idp-table-completion-styled th,
            .idp-table-completion-styled td {
                padding: 6px 8px;
                font-size: 11px;
            }
            
            .idp-table-completion-styled th {
                max-width: 120px;
            }
            
            .idp-table-completion-styled td {
                max-width: 140px;
            }
            
            .idp-table-input {
                min-width: 70px;
                max-width: 120px;
                font-size: 11px;
                padding: 4px 6px;
            }
            
            .tc-question-number {
                min-width: 22px;
                height: 22px;
                font-size: 11px;
            }
            
            .tc-input-wrapper {
                gap: 4px;
                padding: 3px 4px;
            }
            
            /* Header responsive */
            .idp-header {
                padding: 0 12px;
                top: 8px; left: 8px; right: 8px;
            }
            
            .idp-finish-btn {
                padding: 6px 14px;
                font-size: 12px;
            }
            
            .idp-username {
                font-size: 12px;
            }
            
            .idp-section-label-bar {
                top: 78px; left: 8px; right: 8px;
            }
            
            /* Footer responsive */
            .idp-footer {
                height: 72px;
                padding: 0 4px;
                bottom: 8px; left: 8px; right: 8px;
            }
            
            .idp-question-numbers {
                padding: 10px 12px;
                gap: 8px;
            }
            
            .idp-q-circle {
                width: 38px;
                height: 38px;
                font-size: 13px;
            }
            
            .idp-nav-text-btn {
                padding: 10px 16px;
                font-size: 12px;
            }
            
            .idp-main {
                bottom: 88px;
                top: 136px;
                left: 8px; right: 8px;
            }
        }
        
        @media (max-width: 480px) {
            .idp-header {
                padding: 0 10px;
            }
            
            .idp-finish-btn {
                padding: 6px 10px;
                font-size: 11px;
            }
            
            .idp-timer {
                font-size: 13px;
                letter-spacing: 1px;
            }
            
            .idp-footer {
                height: 64px;
                padding: 0 4px;
                gap: 8px;
            }
            
            .idp-question-numbers {
                padding: 8px 10px;
                gap: 6px;
                border-radius: 12px;
            }
            
            .idp-q-circle {
                width: 34px;
                height: 34px;
                font-size: 12px;
                border-width: 1px;
            }
            
            .idp-nav-text-btn {
                padding: 8px 12px;
                font-size: 11px;
                border-radius: 6px;
            }
            
            .idp-main {
                bottom: 80px;
            }
        }
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
    @php
        $authUser = auth()->user();
        $userName = strtoupper($authUser->full_name ?? $authUser->name ?? 'TEST TAKER');
        $userAvatar = $authUser->avatar ?? null;
        $userInitial = strtoupper(substr($authUser->full_name ?? $authUser->name ?? 'T', 0, 1));
    @endphp
    <header class="idp-header">
        <div class="idp-header-user">
            <div class="idp-avatar">
                @if($userAvatar)
                    <img src="{{ $userAvatar }}" alt="{{ $userName }}">
                @else
                    {{ $userInitial }}
                @endif
            </div>
            <span class="idp-username">{{ $userName }}</span>
        </div>
        <div class="idp-timer" id="examTimer">TIME: 00:00:00</div>
        <button class="idp-finish-btn" onclick="showModal()">
            @if($test->isMockTest())
                Finish Section
            @else
                Finish
            @endif
        </button>
    </header>

    {{-- SECTION LABEL BAR --}}
    @if($skill !== 'writing')
    <div class="idp-section-label-bar">
        @if($skill === 'speaking')
            <span class="idp-section-label">SPEAKING: PART {{ $currentSection->part_number ?? 1 }}</span>
        @else
            <span class="idp-section-label">{{ strtoupper($skill ?? 'listening') }}</span>
        @endif
    </div>
    @endif

    {{-- MAIN CONTENT --}}
    <main class="idp-main" @if($skill === 'writing') style="top: 84px;" @endif>
        @if($skill === 'speaking')
            {{-- SPEAKING LAYOUT --}}
            @include('design_1.panel.ielts_tests.partials.idp_speaking_complete', [
                'section'      => $currentSection,
                'allQuestions' => $allQuestions,
                'userAnswers'  => $userAnswers,
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
        // Get all questions from current section with their IDs and numbers
        $currentQuestions = $allQuestions->map(function($q) use ($userAnswers) {
            return [
                'id' => $q->id,
                'number' => $q->question_number ?? 0,
                'answered' => !empty($userAnswers[$q->id] ?? null)
            ];
        })->sortBy('number')->values();
        
        $questionNumbers = $currentQuestions->pluck('number')->toArray();
        $firstQuestionNum = $currentQuestions->first()['number'] ?? 1;
    @endphp
    
    <footer class="idp-footer">
        {{-- Left half: Question Number Circles --}}
        <div class="idp-footer-half">
            <div class="idp-question-numbers">
                @foreach($currentQuestions as $index => $qData)
                    <button 
                        class="idp-q-circle {{ $index === 0 ? 'active' : '' }} {{ $qData['answered'] ? 'answered' : '' }}" 
                        data-q-num="{{ $qData['number'] }}"
                        data-q-index="{{ $index }}"
                        onclick="goToQuestion({{ $qData['number'] }}, {{ $index }})">
                        {{ $qData['number'] }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Right half: Navigation Buttons --}}
        <div class="idp-footer-half">
            <div class="idp-nav-btns">
                <button class="idp-nav-text-btn prev" onclick="prevQ()" id="prevBtn">
                    Previous question
                </button>
                <button class="idp-nav-text-btn next" onclick="nextQ()" id="nextBtn">
                    Next question
                </button>
            </div>
        </div>
    </footer>

    {{-- LISTENING AUDIO OVERLAY --}}
    @if($skill === 'listening')
        <div class="idp-audio-overlay" id="audioOverlay">
            <div class="idp-audio-icon">🎧</div>
            <p class="idp-audio-msg">{!! trans('update.ielts_audio_overlay_message') !!}</p>
            <button class="idp-play-btn" onclick="playAudio()">▶ {{ trans('update.ielts_play') }}</button>
        </div>
        <audio id="audioPlayer" src="{{ $currentSection->audio_url ?? '' }}"></audio>
    @endif

    {{-- SUBMIT MODAL --}}
    <div class="idp-modal hidden" id="submitModal">
        <div class="idp-modal-box">
            <div class="idp-modal-title">{{ trans('admin/main.submit') }}</div>
            <p class="idp-modal-text">{{ trans('update.ielts_submit_confirm') }}</p>
            <div class="idp-modal-btns">
                <button class="idp-modal-btn cancel" onclick="hideModal()">{{ trans('admin/main.cancel') }}</button>
                <button class="idp-modal-btn confirm" onclick="submitSection()">{{ trans('admin/main.submit') }}</button>
            </div>
        </div>
    </div>

    <script>
        const attemptId = {{ $attempt->id }};
        const csrf = '{{ csrf_token() }}';
        const saveUrl = '{{ route("panel.ielts_tests.save_answer", $attempt->id) }}';
        const submitUrl = '{{ route("panel.ielts_tests.finish_section", $attempt->id) }}';
        
        // Create map of question ID to question number
        const questionIdToNumber = {};
        @foreach($currentQuestions as $qData)
            questionIdToNumber[{{ $qData['id'] }}] = {{ $qData['number'] }};
        @endforeach
        
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
                
                // Mark question circle as answered if value is not empty
                const qNum = questionIdToNumber[qId];
                if(qNum) {
                    const circle = document.querySelector(`.idp-q-circle[data-q-num="${qNum}"]`);
                    if(circle) {
                        if(value && value.trim()) {
                            circle.classList.add('answered');
                        } else {
                            circle.classList.remove('answered');
                        }
                    }
                }
                
                // Visual feedback on input
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
        
        // Navigation - Track current question
        let currentQuestionIndex = 0;
        const questionNumbers = @json($questionNumbers);
        
        function goToQuestion(num, index) {
            // Scroll to question in the content area
            const questionEl = document.querySelector(`.idp-question-item[data-q-num="${num}"], tr[data-q-num="${num}"], .idp-q-item[data-q-num="${num}"]`);
            if(questionEl) {
                questionEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Update active state on circles
            document.querySelectorAll('.idp-q-circle').forEach(circle => {
                circle.classList.remove('active');
            });
            const activeCircle = document.querySelector(`.idp-q-circle[data-q-num="${num}"]`);
            if(activeCircle) {
                activeCircle.classList.add('active');
            }
            
            // Update current index
            currentQuestionIndex = index;
            updateNavButtons();
        }
        
        function prevQ() {
            if(currentQuestionIndex > 0) {
                currentQuestionIndex--;
                const prevNum = questionNumbers[currentQuestionIndex];
                goToQuestion(prevNum, currentQuestionIndex);
            }
        }
        
        function nextQ() {
            if(currentQuestionIndex < questionNumbers.length - 1) {
                currentQuestionIndex++;
                const nextNum = questionNumbers[currentQuestionIndex];
                goToQuestion(nextNum, currentQuestionIndex);
            }
        }
        
        function updateNavButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            if(prevBtn) {
                prevBtn.disabled = currentQuestionIndex === 0;
            }
            if(nextBtn) {
                nextBtn.disabled = currentQuestionIndex === questionNumbers.length - 1;
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateNavButtons();
        });
        
        // Exam timer - counts up from 00:00:00
        let timerSeconds = 0;
        function tickTimer() {
            timerSeconds++;
            const h = Math.floor(timerSeconds / 3600).toString().padStart(2, '0');
            const m = Math.floor((timerSeconds % 3600) / 60).toString().padStart(2, '0');
            const s = (timerSeconds % 60).toString().padStart(2, '0');
            const el = document.getElementById('examTimer');
            if (el) el.textContent = 'TIME: ' + h + ':' + m + ':' + s;
        }
        let timerInterval = setInterval(tickTimer, 1000);
        
        // Modal
        function showModal() { clearInterval(timerInterval); document.getElementById('submitModal').classList.remove('hidden'); }
        function hideModal() { document.getElementById('submitModal').classList.add('hidden'); timerInterval = setInterval(tickTimer, 1000); }
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
