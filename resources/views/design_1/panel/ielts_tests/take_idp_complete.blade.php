{{-- 
    IDP IELTS CBT Interface - 100% Exact Replica
    Based on official IDP/British Council Computer-Based Test
    Complete Implementation with Full JavaScript Functionality
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IELTS {{ ucfirst($currentSection->skill ?? 'Test') }}</title>
    
    <style>
        /* =============================================
           IDP IELTS CBT - EXACT REPLICA CSS
           Based on Official IDP Interface
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
           HEADER - Black Bar with Red IELTS Logo
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
            gap: 20px;
        }
        
        .idp-logo {
            font-size: 24px;
            font-weight: bold;
            color: #E31837;
            font-style: italic;
            letter-spacing: 0.5px;
            font-family: 'Times New Roman', serif;
        }
        
        .idp-test-taker {
            color: #ffffff;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .idp-audio-status {
            color: #ffffff;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: 16px;
        }
        
        .idp-audio-status.playing::before {
            content: "";
            display: inline-block;
            width: 14px;
            height: 14px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z'/%3E%3C/svg%3E") no-repeat center;
            background-size: contain;
        }
        
        .idp-header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .idp-header-icon {
            color: #ffffff;
            font-size: 18px;
            cursor: pointer;
            opacity: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }
        
        .idp-header-icon:hover {
            opacity: 1;
        }
        
        .idp-header-icon svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }
        
        /* =============================================
           PART BAR - Orange/Brown Section Header
           ============================================= */
        .idp-part-bar {
            height: 60px;
            background: #CC6600;
            display: flex;
            align-items: center;
            padding: 0 24px;
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
            font-size: 16px;
            font-weight: bold;
        }
        
        .idp-part-instruction {
            color: #ffffff;
            font-size: 14px;
            font-weight: normal;
        }
        
        .idp-part-link {
            color: #87CEEB;
            text-decoration: underline;
            cursor: pointer;
        }
        
        /* =============================================
           MAIN CONTENT - Split Panel Layout
           ============================================= */
        .idp-main {
            display: flex;
            position: fixed;
            top: 108px;
            left: 0;
            right: 0;
            bottom: 52px;
            background: #f5f5f5;
        }
        
        /* Left Panel - Passage/Content */
        .idp-panel-left {
            flex: 1;
            background: #ffffff;
            overflow-y: auto;
            padding: 28px 36px;
            border-right: 1px solid #cccccc;
            position: relative;
        }
        
        .idp-passage-title {
            font-size: 20px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .idp-passage-subtitle {
            font-size: 14px;
            font-style: italic;
            color: #666666;
            margin-bottom: 20px;
        }
        
        .idp-passage-text {
            font-size: 16px;
            line-height: 1.8;
            color: #000000;
            text-align: justify;
        }
        
        .idp-passage-text p {
            margin-bottom: 18px;
            text-indent: 0;
        }
        
        .idp-passage-text strong,
        .idp-passage-text b {
            font-weight: bold;
        }
        
        .idp-passage-text em,
        .idp-passage-text i {
            font-style: italic;
        }
        
        /* Text highlighting */
        .idp-highlight {
            background-color: #FFFF00;
            padding: 0 2px;
        }
        
        /* Resizable Divider */
        .idp-divider {
            width: 20px;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: col-resize;
            flex-shrink: 0;
            border-left: 1px solid #cccccc;
            border-right: 1px solid #cccccc;
            user-select: none;
        }
        
        .idp-divider:hover {
            background: #d0d0d0;
        }
        
        .idp-divider-icon {
            color: #888888;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: -2px;
        }
        
        /* Right Panel - Questions */
        .idp-panel-right {
            flex: 1;
            background: #f7f7f7;
            overflow-y: auto;
            padding: 28px 36px;
        }
        
        /* Question Header */
        .idp-question-header {
            margin-bottom: 24px;
            position: relative;
        }
        
        .idp-help-link {
            position: absolute;
            top: 0;
            right: 0;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #0066CC;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .idp-help-link:hover {
            text-decoration: underline;
        }
        
        .idp-question-range {
            font-size: 17px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 10px;
        }
        
        .idp-question-instruction {
            font-size: 15px;
            color: #000000;
            line-height: 1.6;
        }
        
        .idp-question-instruction strong,
        .idp-question-instruction b {
            font-weight: bold;
        }
        
        /* =============================================
           QUESTION STYLING - All Types
           ============================================= */
        
        .idp-questions-container {
            margin-top: 24px;
        }
        
        /* Single Question */
        .idp-question {
            margin-bottom: 20px;
        }
        
        .idp-question-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 22px;
            padding: 0 6px;
            border: 1px solid #CC6600;
            color: #CC6600;
            font-size: 12px;
            font-weight: bold;
            margin-right: 8px;
            background: #ffffff;
            vertical-align: middle;
        }
        
        .idp-question-text {
            font-size: 15px;
            color: #0066CC;
            display: inline;
            vertical-align: middle;
        }
        
        /* TRUE/FALSE/NOT GIVEN & YES/NO/NOT GIVEN */
        .idp-options-list {
            margin-top: 10px;
            padding-left: 32px;
        }
        
        .idp-option-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            cursor: pointer;
        }
        
        .idp-option-item input[type="radio"],
        .idp-option-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #000000;
            flex-shrink: 0;
        }
        
        .idp-option-item label {
            font-size: 15px;
            color: #000000;
            cursor: pointer;
            line-height: 1.4;
        }
        
        /* Fill in Blank / Text Input */
        .idp-input-blank {
            display: inline-block;
            width: 120px;
            height: 26px;
            border: 1px dashed #0066CC;
            padding: 2px 8px;
            font-size: 14px;
            color: #000000;
            background: #ffffff;
            text-align: center;
            vertical-align: middle;
        }
        
        .idp-input-blank:focus {
            outline: none;
            border: 2px solid #0066CC;
            border-style: solid;
        }
        
        .idp-input-blank::placeholder {
            color: #0066CC;
            font-size: 12px;
        }
        
        /* Matching Table */
        .idp-match-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 14px;
        }
        
        .idp-match-table th {
            background: #ffffff;
            padding: 10px 12px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #d0d0d0;
        }
        
        .idp-match-table td {
            padding: 10px 12px;
            border: 1px solid #d0d0d0;
            background: #ffffff;
        }
        
        .idp-match-table td:first-child {
            text-align: left;
        }
        
        .idp-match-table td:not(:first-child) {
            text-align: center;
            width: 60px;
        }
        
        /* Word Bank */
        .idp-word-bank {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
            padding: 16px;
            background: #ffffff;
            border: 1px solid #d0d0d0;
        }
        
        .idp-word-chip {
            padding: 5px 12px;
            border: 1px solid #d0d0d0;
            background: #ffffff;
            font-size: 14px;
            cursor: grab;
            user-select: none;
            transition: all 0.15s;
        }
        
        .idp-word-chip:hover {
            background: #f0f0f0;
        }
        
        .idp-word-chip:active {
            cursor: grabbing;
        }
        
        .idp-word-chip.used {
            opacity: 0.4;
            text-decoration: line-through;
        }
        
        /* Drop Zone */
        .idp-drop-target {
            display: inline-block;
            min-width: 140px;
            height: 26px;
            border: 1px dashed #0066CC;
            background: #ffffff;
            vertical-align: middle;
            text-align: center;
            line-height: 24px;
            color: #0066CC;
            font-size: 14px;
        }
        
        .idp-drop-target.has-value {
            border-style: solid;
            color: #000000;
        }
        
        .idp-drop-target.drag-over {
            background: #e6f3ff;
            border-color: #0044aa;
        }
        
        /* =============================================
           WRITING SECTION
           ============================================= */
        .idp-writing-layout {
            display: flex;
            height: 100%;
        }
        
        .idp-writing-task {
            flex: 1;
            padding: 28px 36px;
            overflow-y: auto;
            background: #ffffff;
            border-right: 1px solid #cccccc;
        }
        
        .idp-writing-task h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 16px;
        }
        
        .idp-writing-task p {
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 16px;
        }
        
        .idp-writing-task ul {
            list-style: disc;
            padding-left: 24px;
            margin-bottom: 16px;
        }
        
        .idp-writing-task li {
            margin-bottom: 8px;
            font-size: 15px;
        }
        
        .idp-task-image {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border: 1px solid #d0d0d0;
        }
        
        .idp-writing-answer {
            flex: 1;
            padding: 28px 36px;
            background: #f7f7f7;
            display: flex;
            flex-direction: column;
        }
        
        .idp-essay-area {
            flex: 1;
            width: 100%;
            border: 1px solid #cccccc;
            padding: 16px;
            font-size: 16px;
            font-family: Arial, sans-serif;
            line-height: 1.7;
            resize: none;
            background: #ffffff;
        }
        
        .idp-essay-area:focus {
            outline: 2px solid #0066CC;
        }
        
        .idp-word-counter {
            text-align: right;
            padding: 10px 0;
            font-size: 14px;
            color: #666666;
        }
        
        /* =============================================
           LISTENING SECTION - Audio Overlay
           ============================================= */
        .idp-audio-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.98);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        
        .idp-audio-overlay.hidden {
            display: none;
        }
        
        .idp-headphone-icon {
            font-size: 72px;
            color: #555555;
            margin-bottom: 24px;
        }
        
        .idp-audio-message {
            font-size: 16px;
            color: #000000;
            text-align: center;
            max-width: 480px;
            margin-bottom: 28px;
            line-height: 1.6;
        }
        
        .idp-play-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 36px;
            background: #000000;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .idp-play-btn:hover {
            background: #333333;
        }
        
        /* Hidden audio element */
        #listeningAudio {
            display: none;
        }
        
        /* =============================================
           FOOTER - Bottom Navigation Bar
           ============================================= */
        .idp-footer {
            height: 52px;
            background: #ffffff;
            border-top: 1px solid #cccccc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        /* Part Navigation */
        .idp-parts-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .idp-part-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .idp-part-label {
            font-size: 14px;
            color: #000000;
            font-weight: 500;
            cursor: pointer;
        }
        
        .idp-part-label.active {
            font-weight: bold;
        }
        
        .idp-q-numbers {
            display: flex;
            align-items: center;
            gap: 3px;
        }
        
        .idp-q-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 4px;
            font-size: 12px;
            color: #000000;
            cursor: pointer;
            border: 1px solid transparent;
            background: transparent;
        }
        
        .idp-q-btn:hover {
            background: #f0f0f0;
        }
        
        .idp-q-btn.current {
            border: 2px solid #CC6600;
            font-weight: bold;
        }
        
        .idp-q-btn.answered {
            background: #e0f0ff;
        }
        
        .idp-q-btn.flagged {
            background: #fff3e0;
        }
        
        .idp-progress-text {
            font-size: 12px;
            color: #888888;
            margin-left: 6px;
        }
        
        /* Navigation Buttons */
        .idp-nav-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .idp-nav-arrow {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #555555;
            color: #ffffff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }
        
        .idp-nav-arrow:hover {
            background: #333333;
        }
        
        .idp-nav-arrow:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }
        
        .idp-nav-arrow.prev {
            background: #888888;
        }
        
        .idp-check-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            color: #666666;
            border: 1px solid #cccccc;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 6px;
        }
        
        .idp-check-btn:hover {
            background: #f5f5f5;
        }
        
        /* =============================================
           SCROLLBAR
           ============================================= */
        .idp-panel-left::-webkit-scrollbar,
        .idp-panel-right::-webkit-scrollbar,
        .idp-writing-task::-webkit-scrollbar,
        .idp-writing-answer::-webkit-scrollbar {
            width: 8px;
        }
        
        .idp-panel-left::-webkit-scrollbar-track,
        .idp-panel-right::-webkit-scrollbar-track,
        .idp-writing-task::-webkit-scrollbar-track,
        .idp-writing-answer::-webkit-scrollbar-track {
            background: #f0f0f0;
        }
        
        .idp-panel-left::-webkit-scrollbar-thumb,
        .idp-panel-right::-webkit-scrollbar-thumb,
        .idp-writing-task::-webkit-scrollbar-thumb,
        .idp-writing-answer::-webkit-scrollbar-thumb {
            background: #c0c0c0;
            border-radius: 4px;
        }
        
        .idp-panel-left::-webkit-scrollbar-thumb:hover,
        .idp-panel-right::-webkit-scrollbar-thumb:hover,
        .idp-writing-task::-webkit-scrollbar-thumb:hover,
        .idp-writing-answer::-webkit-scrollbar-thumb:hover {
            background: #a0a0a0;
        }
        
        /* =============================================
           CONFIRMATION MODAL
           ============================================= */
        .idp-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3000;
        }
        
        .idp-modal-overlay.hidden {
            display: none;
        }
        
        .idp-modal-box {
            background: #ffffff;
            padding: 32px 40px;
            border-radius: 8px;
            max-width: 420px;
            text-align: center;
        }
        
        .idp-modal-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 16px;
        }
        
        .idp-modal-text {
            font-size: 15px;
            color: #333333;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        
        .idp-modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        
        .idp-modal-btn {
            padding: 10px 28px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .idp-modal-btn.cancel {
            background: #e0e0e0;
            color: #333333;
        }
        
        .idp-modal-btn.cancel:hover {
            background: #d0d0d0;
        }
        
        .idp-modal-btn.confirm {
            background: #CC6600;
            color: #ffffff;
        }
        
        .idp-modal-btn.confirm:hover {
            background: #b35500;
        }
    </style>
</head>
<body>
    @php
        $skill = $currentSection->skill ?? 'reading';
        $currentPartNum = $currentSection->part_number ?? 1;
        $questionGroups = $questions->groupBy('question_group_id');
        $allQuestions = $questions;
        $firstQ = $allQuestions->first();
        $lastQ = $allQuestions->last();
        $questionStart = $firstQ->order_number ?? 1;
        $questionEnd = $lastQ->order_number ?? $questionStart + $allQuestions->count() - 1;
        
        // Get group instruction
        $currentGroup = $firstQ->questionGroup ?? null;
        $groupInstruction = $currentGroup->instruction ?? '';
    @endphp

    {{-- HEADER --}}
    <header class="idp-header">
        <div class="idp-header-left">
            <div class="idp-logo">IELTS</div>
            <div class="idp-test-taker">Test taker ID</div>
            @if($skill === 'listening')
                <div class="idp-audio-status playing" id="audioStatus">Audio is playing</div>
            @endif
        </div>
        <div class="idp-header-right">
            <span class="idp-header-icon">
                <svg viewBox="0 0 24 24"><path d="M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z"/></svg>
            </span>
            <span class="idp-header-icon">
                <svg viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
            </span>
            <span class="idp-header-icon">
                <svg viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
            </span>
        </div>
    </header>
    
    {{-- PART BAR --}}
    <div class="idp-part-bar">
        <div class="idp-part-info">
            <div class="idp-part-title">Part {{ $currentPartNum }}</div>
            <div class="idp-part-instruction">
                @if($skill === 'reading')
                    Read the text and answer questions <span class="idp-part-link">{{ $questionStart }}-{{ $questionEnd }}</span>.
                @elseif($skill === 'listening')
                    Listen and answer questions {{ $questionStart }}-{{ $questionEnd }}.
                @elseif($skill === 'writing')
                    @if($currentPartNum == 1)
                        You should spend about 20 minutes on this task. Write at least 150 words.
                    @else
                        You should spend about 40 minutes on this task. Write at least 250 words.
                    @endif
                @endif
            </div>
        </div>
    </div>
    
    {{-- MAIN CONTENT --}}
    <main class="idp-main">
        @if($skill === 'writing')
            {{-- WRITING LAYOUT --}}
            <div class="idp-writing-layout">
                <div class="idp-writing-task">
                    @if(!empty($currentSection->image_url))
                        <img src="{{ $currentSection->image_url }}" alt="Task Image" class="idp-task-image">
                    @endif
                    <div class="idp-task-content">
                        {!! $currentSection->passage ?? $currentSection->content ?? '' !!}
                    </div>
                </div>
                <div class="idp-divider">
                    <span class="idp-divider-icon">↔</span>
                </div>
                <div class="idp-writing-answer">
                    <textarea class="idp-essay-area" 
                              id="writingAnswer" 
                              data-question-id="{{ $firstQ->id ?? 0 }}"
                              placeholder="Start writing your answer here..."
                              oninput="updateWordCount(); autoSaveWriting();">{{ $userAnswers[$firstQ->id ?? 0] ?? '' }}</textarea>
                    <div class="idp-word-counter">Words: <span id="wordCount">0</span></div>
                </div>
            </div>
        @else
            {{-- READING / LISTENING LAYOUT --}}
            <div class="idp-panel-left" id="leftPanel">
                @if($skill === 'reading')
                    @if(!empty($currentSection->title))
                        <h2 class="idp-passage-title">{{ $currentSection->title }}</h2>
                    @endif
                    @if(!empty($currentSection->subtitle))
                        <p class="idp-passage-subtitle">{{ $currentSection->subtitle }}</p>
                    @endif
                    <div class="idp-passage-text">
                        {!! $currentSection->passage ?? $currentSection->content ?? '' !!}
                    </div>
                @elseif($skill === 'listening')
                    {{-- Listening content - could be flowchart, map, notes --}}
                    <div class="idp-listening-content">
                        {!! $currentSection->content ?? '' !!}
                    </div>
                @endif
            </div>
            
            <div class="idp-divider" id="divider">
                <span class="idp-divider-icon">↔</span>
            </div>
            
            <div class="idp-panel-right" id="rightPanel">
                <div class="idp-question-header">
                    <a href="#" class="idp-help-link">✉ Help</a>
                    <div class="idp-question-range">Questions {{ $questionStart }}-{{ $questionEnd }}</div>
                    <div class="idp-question-instruction">
                        @if(!empty($groupInstruction))
                            {!! $groupInstruction !!}
                        @else
                            Do the following statements agree with the information given in the text?
                            <br><br>
                            Write<br>
                            <strong>TRUE</strong> if the statement agrees with the information<br>
                            <strong>FALSE</strong> if the statement contradicts the information<br>
                            <strong>NOT GIVEN</strong> if there is no information on this
                        @endif
                    </div>
                </div>
                
                <div class="idp-questions-container">
                    @foreach($allQuestions as $question)
                        @php
                            $qType = $question->type ?? 'tfng';
                            $qNum = $question->order_number ?? $loop->iteration;
                            $qId = $question->id;
                            $answer = $userAnswers[$qId] ?? '';
                        @endphp
                        
                        <div class="idp-question" data-q-id="{{ $qId }}" data-q-num="{{ $qNum }}">
                            @switch($qType)
                                @case('tfng')
                                @case('true_false_not_given')
                                    <span class="idp-question-num">{{ $qNum }}</span>
                                    <span class="idp-question-text">{!! $question->content !!}</span>
                                    <div class="idp-options-list">
                                        <div class="idp-option-item">
                                            <input type="radio" name="q{{ $qId }}" id="q{{ $qId }}_t" value="TRUE" {{ $answer === 'TRUE' ? 'checked' : '' }} onchange="saveAnswer({{ $qId }}, 'TRUE')">
                                            <label for="q{{ $qId }}_t">TRUE</label>
                                        </div>
                                        <div class="idp-option-item">
                                            <input type="radio" name="q{{ $qId }}" id="q{{ $qId }}_f" value="FALSE" {{ $answer === 'FALSE' ? 'checked' : '' }} onchange="saveAnswer({{ $qId }}, 'FALSE')">
                                            <label for="q{{ $qId }}_f">FALSE</label>
                                        </div>
                                        <div class="idp-option-item">
                                            <input type="radio" name="q{{ $qId }}" id="q{{ $qId }}_ng" value="NOT GIVEN" {{ $answer === 'NOT GIVEN' ? 'checked' : '' }} onchange="saveAnswer({{ $qId }}, 'NOT GIVEN')">
                                            <label for="q{{ $qId }}_ng">NOT GIVEN</label>
                                        </div>
                                    </div>
                                    @break
                                    
                                @case('ynng')
                                @case('yes_no_not_given')
                                    <span class="idp-question-num">{{ $qNum }}</span>
                                    <span class="idp-question-text">{!! $question->content !!}</span>
                                    <div class="idp-options-list">
                                        <div class="idp-option-item">
                                            <input type="radio" name="q{{ $qId }}" id="q{{ $qId }}_y" value="YES" {{ $answer === 'YES' ? 'checked' : '' }} onchange="saveAnswer({{ $qId }}, 'YES')">
                                            <label for="q{{ $qId }}_y">YES</label>
                                        </div>
                                        <div class="idp-option-item">
                                            <input type="radio" name="q{{ $qId }}" id="q{{ $qId }}_n" value="NO" {{ $answer === 'NO' ? 'checked' : '' }} onchange="saveAnswer({{ $qId }}, 'NO')">
                                            <label for="q{{ $qId }}_n">NO</label>
                                        </div>
                                        <div class="idp-option-item">
                                            <input type="radio" name="q{{ $qId }}" id="q{{ $qId }}_ng" value="NOT GIVEN" {{ $answer === 'NOT GIVEN' ? 'checked' : '' }} onchange="saveAnswer({{ $qId }}, 'NOT GIVEN')">
                                            <label for="q{{ $qId }}_ng">NOT GIVEN</label>
                                        </div>
                                    </div>
                                    @break
                                    
                                @case('mcq')
                                @case('multiple_choice')
                                    <span class="idp-question-num">{{ $qNum }}</span>
                                    <span class="idp-question-text">{!! $question->content !!}</span>
                                    <div class="idp-options-list">
                                        @foreach(json_decode($question->options ?? '[]', true) as $idx => $opt)
                                            @php $letter = chr(65 + $idx); @endphp
                                            <div class="idp-option-item">
                                                <input type="radio" name="q{{ $qId }}" id="q{{ $qId }}_{{ $letter }}" value="{{ $letter }}" {{ $answer === $letter ? 'checked' : '' }} onchange="saveAnswer({{ $qId }}, '{{ $letter }}')">
                                                <label for="q{{ $qId }}_{{ $letter }}"><strong>{{ $letter }}</strong> &nbsp; {{ is_array($opt) ? ($opt['text'] ?? $opt['value'] ?? $opt) : $opt }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break
                                    
                                @case('fill_blank')
                                @case('short_answer')
                                @case('gap_fill')
                                @case('sentence_completion')
                                    <span class="idp-question-num">{{ $qNum }}</span>
                                    @if(!empty($question->content))
                                        <span class="idp-question-text">{!! $question->content !!}</span>
                                    @endif
                                    <input type="text" class="idp-input-blank" 
                                           id="input_{{ $qId }}" 
                                           value="{{ $answer }}"
                                           placeholder="{{ $qNum }}"
                                           onblur="saveAnswer({{ $qId }}, this.value)"
                                           onkeyup="autoSaveDebounce({{ $qId }}, this.value)">
                                    @break
                                    
                                @default
                                    <span class="idp-question-num">{{ $qNum }}</span>
                                    <span class="idp-question-text">{!! $question->content !!}</span>
                                    <input type="text" class="idp-input-blank" 
                                           id="input_{{ $qId }}" 
                                           value="{{ $answer }}"
                                           placeholder="{{ $qNum }}"
                                           onblur="saveAnswer({{ $qId }}, this.value)">
                            @endswitch
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </main>
    
    {{-- FOOTER --}}
    @php
        // Build parts/questions navigation
        $sections = $test->sections()->where('skill', $skill)->orderBy('sort_order')->get();
        $partsData = [];
        $globalQNum = 0;
        
        foreach($sections as $sec) {
            $secQuestions = $sec->questions()->orderBy('sort_order')->get();
            $qNumbers = [];
            foreach($secQuestions as $sq) {
                $qNumbers[] = $sq->order_number ?? ++$globalQNum;
            }
            $answered = collect($qNumbers)->filter(fn($n) => !empty($userAnswers[$n] ?? null))->count();
            
            $partsData[$sec->part_number ?? $loop->iteration] = [
                'section_id' => $sec->id,
                'questions' => $qNumbers,
                'answered' => $answered,
                'total' => count($qNumbers),
            ];
        }
        
        $currentQ = $questionStart;
    @endphp
    
    <footer class="idp-footer">
        <div class="idp-parts-nav">
            @foreach($partsData as $partNum => $partInfo)
                <div class="idp-part-group" data-part="{{ $partNum }}" data-section="{{ $partInfo['section_id'] }}">
                    <span class="idp-part-label {{ $partNum == $currentPartNum ? 'active' : '' }}">Part {{ $partNum }}</span>
                    <div class="idp-q-numbers">
                        @foreach($partInfo['questions'] as $qn)
                            <span class="idp-q-btn {{ $qn == $currentQ ? 'current' : '' }} {{ !empty($userAnswers[$qn] ?? null) ? 'answered' : '' }}" 
                                  data-num="{{ $qn }}"
                                  onclick="goToQuestion({{ $qn }})">{{ $qn }}</span>
                        @endforeach
                    </div>
                    <span class="idp-progress-text">{{ $partInfo['answered'] }} of {{ $partInfo['total'] }}</span>
                </div>
            @endforeach
        </div>
        
        <div class="idp-nav-controls">
            <button class="idp-nav-arrow prev" onclick="prevQuestion()" id="btnPrev">←</button>
            <button class="idp-nav-arrow next" onclick="nextQuestion()" id="btnNext">→</button>
            <button class="idp-check-btn" onclick="showSubmitModal()">✓</button>
        </div>
    </footer>
    
    {{-- LISTENING AUDIO OVERLAY --}}
    @if($skill === 'listening')
        <div class="idp-audio-overlay" id="audioOverlay">
            <div class="idp-headphone-icon">🎧</div>
            <div class="idp-audio-message">
                You will be listening to an audio clip during this test. You will not be permitted to pause or rewind the audio while answering the questions.
                <br><br>
                To continue, click <strong>Play</strong>.
            </div>
            <button class="idp-play-btn" onclick="startListeningAudio()">
                ▶ Play
            </button>
        </div>
        <audio id="listeningAudio" src="{{ $currentSection->audio_url ?? '' }}"></audio>
    @endif
    
    {{-- SUBMIT CONFIRMATION MODAL --}}
    <div class="idp-modal-overlay hidden" id="submitModal">
        <div class="idp-modal-box">
            <div class="idp-modal-title">Submit Section?</div>
            <div class="idp-modal-text">
                Are you sure you want to submit this section? You will not be able to change your answers after submission.
            </div>
            <div class="idp-modal-buttons">
                <button class="idp-modal-btn cancel" onclick="hideSubmitModal()">Cancel</button>
                <button class="idp-modal-btn confirm" onclick="submitSection()">Submit</button>
            </div>
        </div>
    </div>
    
    <script>
        // Configuration
        const attemptId = {{ $attempt->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const saveAnswerUrl = '{{ route("panel.ielts_tests.save_answer", $attempt->id) }}';
        const finishSectionUrl = '{{ route("panel.ielts_tests.finish_section", $attempt->id) }}';
        
        let currentQuestionNum = {{ $currentQ }};
        let debounceTimer = null;
        
        // Save answer
        function saveAnswer(questionId, value) {
            fetch(saveAnswerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    question_id: questionId,
                    answer_text: value
                })
            })
            .then(res => res.json())
            .then(data => {
                // Update answered status in footer
                updateQuestionStatus(questionId, value);
            })
            .catch(err => console.error('Save error:', err));
        }
        
        function autoSaveDebounce(questionId, value) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                saveAnswer(questionId, value);
            }, 500);
        }
        
        function updateQuestionStatus(questionId, value) {
            // Find question element and update footer
            const qElement = document.querySelector(`[data-q-id="${questionId}"]`);
            if (qElement) {
                const qNum = qElement.dataset.qNum;
                const footerBtn = document.querySelector(`.idp-q-btn[data-num="${qNum}"]`);
                if (footerBtn) {
                    if (value && value.trim()) {
                        footerBtn.classList.add('answered');
                    } else {
                        footerBtn.classList.remove('answered');
                    }
                }
            }
        }
        
        // Navigation
        function goToQuestion(num) {
            const targetQ = document.querySelector(`[data-q-num="${num}"]`);
            if (targetQ) {
                targetQ.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Update current
                document.querySelectorAll('.idp-q-btn.current').forEach(btn => btn.classList.remove('current'));
                document.querySelector(`.idp-q-btn[data-num="${num}"]`).classList.add('current');
                currentQuestionNum = num;
            }
        }
        
        function prevQuestion() {
            const allQBtns = [...document.querySelectorAll('.idp-q-btn')];
            const currentIdx = allQBtns.findIndex(btn => parseInt(btn.dataset.num) === currentQuestionNum);
            if (currentIdx > 0) {
                goToQuestion(parseInt(allQBtns[currentIdx - 1].dataset.num));
            }
        }
        
        function nextQuestion() {
            const allQBtns = [...document.querySelectorAll('.idp-q-btn')];
            const currentIdx = allQBtns.findIndex(btn => parseInt(btn.dataset.num) === currentQuestionNum);
            if (currentIdx < allQBtns.length - 1) {
                goToQuestion(parseInt(allQBtns[currentIdx + 1].dataset.num));
            }
        }
        
        // Submit
        function showSubmitModal() {
            document.getElementById('submitModal').classList.remove('hidden');
        }
        
        function hideSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }
        
        function submitSection() {
            fetch(finishSectionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(err => console.error('Submit error:', err));
        }
        
        // Writing
        function updateWordCount() {
            const textarea = document.getElementById('writingAnswer');
            if (textarea) {
                const text = textarea.value.trim();
                const words = text ? text.split(/\s+/).length : 0;
                document.getElementById('wordCount').textContent = words;
            }
        }
        
        let writingAutoSaveTimer;
        function autoSaveWriting() {
            clearTimeout(writingAutoSaveTimer);
            writingAutoSaveTimer = setTimeout(() => {
                const textarea = document.getElementById('writingAnswer');
                if (textarea) {
                    saveAnswer(textarea.dataset.questionId, textarea.value);
                }
            }, 1000);
        }
        
        // Listening Audio
        function startListeningAudio() {
            document.getElementById('audioOverlay').classList.add('hidden');
            const audio = document.getElementById('listeningAudio');
            if (audio) {
                audio.play();
            }
        }
        
        // Resizable divider
        document.addEventListener('DOMContentLoaded', function() {
            const divider = document.getElementById('divider');
            const leftPanel = document.getElementById('leftPanel');
            const rightPanel = document.getElementById('rightPanel');
            
            if (divider && leftPanel && rightPanel) {
                let isResizing = false;
                
                divider.addEventListener('mousedown', function(e) {
                    isResizing = true;
                    document.body.style.cursor = 'col-resize';
                    document.body.style.userSelect = 'none';
                });
                
                document.addEventListener('mousemove', function(e) {
                    if (!isResizing) return;
                    
                    const container = leftPanel.parentElement;
                    const containerRect = container.getBoundingClientRect();
                    const newLeftWidth = ((e.clientX - containerRect.left) / containerRect.width) * 100;
                    
                    if (newLeftWidth > 25 && newLeftWidth < 75) {
                        leftPanel.style.flex = `0 0 ${newLeftWidth}%`;
                        rightPanel.style.flex = `0 0 ${100 - newLeftWidth - 2}%`;
                    }
                });
                
                document.addEventListener('mouseup', function() {
                    isResizing = false;
                    document.body.style.cursor = '';
                    document.body.style.userSelect = '';
                });
            }
            
            // Initialize word count for writing
            updateWordCount();
        });
    </script>
</body>
</html>
