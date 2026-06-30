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
        :root {
            --idp-primary: #511D99;
            --idp-primary-deep: #3f1777;
            --idp-primary-soft: rgba(81, 29, 153, 0.10);
            --idp-primary-soft-strong: rgba(81, 29, 153, 0.18);
            --idp-surface: #ffffff;
            --idp-surface-soft: #ffffff;
            --idp-border: rgba(81, 29, 153, 0.14);
            --idp-border-strong: rgba(81, 29, 153, 0.28);
            --idp-text: #1f163a;
            --idp-muted: #675a84;
            --idp-shadow: 0 18px 48px rgba(71, 40, 131, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            min-height: 100%;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            color: var(--idp-text);
            background: linear-gradient(180deg, #ecebf3 0%, #edf0f6 62%, #eaf4f1 100%);
            line-height: 1.5;
            overflow: hidden;
            height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body::before {
            content: none;
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
            box-shadow: none;
            border: none;
        }
        .idp-header-user { display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0; }
        .idp-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(81, 29, 153, 0.18), rgba(255, 255, 255, 0.96));
            border: 2px solid rgba(81, 29, 153, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: bold;
            color: var(--idp-primary-deep);
            overflow: hidden;
            flex-shrink: 0;
        }
        .idp-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .idp-username {
            font-size: 14px;
            font-weight: 700;
            color: var(--idp-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .idp-timer {
            font-size: 17px;
            font-weight: 700;
            color: var(--idp-primary-deep);
            letter-spacing: 2px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }
        
        /* Finish Button */
        .idp-finish-btn {
            padding: 8px 20px;
            background: var(--idp-primary);
            border: 1px solid var(--idp-primary);
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        
        .idp-finish-btn:hover {
            background: var(--idp-primary-deep);
            border-color: var(--idp-primary-deep);
        }
        
        .idp-finish-btn::after {
            content: '→';
            font-size: 14px;
        }

        .idp-exit-preview-btn {
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid var(--idp-border-strong);
            background: #fff;
            color: var(--idp-primary-deep);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .idp-exit-preview-btn:hover {
            background: var(--idp-primary-soft);
            color: var(--idp-primary-deep);
        }
        
        /* ========== PART BAR ========== */
        .idp-part-bar {
            position: fixed;
            top: 140px;
            left: 12px;
            right: 12px;
            min-height: 40px;
            background: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            z-index: 997;
            overflow-x: auto;
        }
        .idp-part-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            border-radius: 999px;
            border: 1px solid var(--idp-border-strong);
            color: var(--idp-primary-deep);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            transition: all .2s ease;
            background: #fff;
        }
        .idp-part-chip:hover {
            background: var(--idp-primary-soft);
            color: var(--idp-primary-deep);
        }
        .idp-part-chip.active {
            background: var(--idp-primary);
            border-color: var(--idp-primary);
            color: #fff;
        }
        
        /* ========== SECTION LABEL BAR ========== */
        .idp-section-label-bar {
            position: fixed;
            top: 84px; left: 12px; right: 12px;
            height: 50px;
            background: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 998;
            box-shadow: none;
            border: none;
        }
        .idp-section-label {
            font-size: 18px;
            font-weight: 900;
            color: var(--idp-primary-deep);
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
            box-shadow: none;
            border: none;
            background: #fff;
        }
        
        /* Left Panel */
        .idp-left {
            flex: 1;
            background: #fff;
            overflow-y: auto;
            padding: 20px 28px;
            border-right: none;
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
            color: var(--idp-muted); 
            margin-bottom: 12px; 
        }
        .idp-passage-text { 
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            color: inherit;
            text-align: initial;
            white-space: normal;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        .idp-part-instructions {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            color: inherit;
            margin-bottom: 14px;
            white-space: normal;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        .idp-part-media {
            margin-bottom: 14px;
        }

        .idp-part-media-item {
            margin-bottom: 10px;
        }

        .idp-part-media-item img,
        .idp-part-media-item video,
        .idp-part-media-item audio {
            max-width: 100%;
            height: auto;
        }
        
        /* Divider */
        .idp-divider {
            width: 18px;
            background: #f5f6fb;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: col-resize;
            border-left: none;
            border-right: none;
        }
        .idp-divider-icon { color: var(--idp-primary-deep); font-size: 12px; }
        
        /* Right Panel */
        .idp-right {
            flex: 1;
            background: #fff;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px 28px;
        }

        .idp-audio-inline {
            background: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 12px;
            box-shadow: none;
        }

        .idp-audio-inline audio {
            width: 100%;
            display: block;
        }
        
        
        /* ========== QUESTION STYLING ========== */
        .idp-questions-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--idp-text);
        }
        .idp-questions-instruction {
            font-size: 15px;
            margin-bottom: 20px;
            line-height: 1.6;
            color: var(--idp-text);
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
            color: var(--idp-text);
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
            color: var(--idp-text);
            cursor: pointer;
            line-height: 1.5;
        }
        
        /* Input Box - Dashed blue border */
        .idp-input {
            display: inline-block;
            min-width: 80px;
            height: 24px;
            border: 1px dashed var(--idp-primary);
            padding: 2px 8px;
            font-size: 14px;
            text-align: center;
            background: #fff;
        }
        .idp-input:focus {
            outline: none;
            border: 2px solid var(--idp-primary);
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
            border: 1px solid rgba(81, 29, 153, 0.22);
            background: #fff;
            font-size: 13px;
            cursor: grab;
        }
        .idp-word:hover { background: rgba(81, 29, 153, 0.06); }
        .idp-word.used { opacity: 0.4; text-decoration: line-through; }
        
        /* Matching Table */
        .idp-match-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 12px;
        }
        .idp-match-table th {
            background: rgba(81, 29, 153, 0.06);
            padding: 8px 10px;
            text-align: center;
            border: 1px solid var(--idp-border);
            font-weight: bold;
        }
        .idp-match-table td {
            padding: 8px 10px;
            border: 1px solid var(--idp-border);
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
            background: #fff;
            border: 1px solid var(--idp-border);
            padding: 10px 14px;
            margin-bottom: 4px;
            font-size: 14px;
            line-height: 1.6;
        }
        .idp-flow-box.highlight { background: rgba(81, 29, 153, 0.06); border-color: var(--idp-primary); }
        .idp-flow-arrow { text-align: center; font-size: 16px; color: var(--idp-muted); margin: 2px 0; }
        
        /* Table Completion */
        .idp-table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            table-layout: auto;
        }
        .idp-table th {
            background: rgba(81, 29, 153, 0.08);
            padding: 8px 10px;
            text-align: left;
            border: 1px solid var(--idp-border);
            font-weight: bold;
            word-wrap: break-word;
            max-width: 200px;
        }
        .idp-table td {
            padding: 8px 10px;
            border: 1px solid var(--idp-border);
            background: #fff;
            word-wrap: break-word;
            max-width: 250px;
        }
        
        /* Map/Diagram - Two column layout */
        .idp-map-layout { display: flex; gap: 20px; }
        .idp-map-image { flex: 1; }
        .idp-map-image img { max-width: 100%; border: 1px solid var(--idp-border); }
        .idp-map-questions { flex: 1; }
        
        /* Group Separator */
        .idp-group-separator {
            border: none;
            border-top: 1px solid var(--idp-border);
            margin: 20px 0;
        }
        
        /* ========== WRITING SECTION ========== */
        .idp-writing-layout { display: flex; height: 100%; }
        .idp-writing-left {
            flex: 1;
            padding: 20px 28px;
            overflow-y: auto;
            background: #fff;
            border-right: none;
        }
        .idp-writing-right {
            flex: 1;
            padding: 20px;
            background: #fff;
            display: flex;
            flex-direction: column;
        }
        .idp-textarea {
            flex: 1;
            width: 100%;
            border: 1px solid var(--idp-border);
            padding: 12px;
            font-size: 15px;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            resize: none;
        }
        .idp-textarea:focus { outline: 2px solid var(--idp-primary); }
        .idp-word-count { text-align: right; padding: 6px 0; font-size: 13px; color: var(--idp-muted); }
        
        /* ========== LISTENING OVERLAY ========== */
        .idp-audio-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(245, 246, 251, 0.97);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        .idp-audio-overlay.hidden { display: none; }
        .idp-audio-icon { font-size: 60px; color: var(--idp-primary-deep); margin-bottom: 20px; }
        .idp-audio-msg { font-size: 15px; text-align: center; max-width: 450px; margin-bottom: 20px; line-height: 1.5; }
        .idp-play-btn {
            padding: 12px 32px;
            background: var(--idp-primary);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .idp-play-btn:hover { background: var(--idp-primary-deep); }
        
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
            border: none;
            box-shadow: none;
        }
        
        /* Circular Question Button */
        .idp-q-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            border: 1.5px solid var(--idp-border-strong);
            color: var(--idp-text);
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
            border-color: var(--idp-primary);
            background: rgba(81, 29, 153, 0.06);
        }
        
        .idp-q-circle.active {
            background: var(--idp-primary);
            color: #fff;
            border-color: var(--idp-primary);
        }
        
        .idp-q-circle.answered {
            background: rgba(81, 29, 153, 0.12);
            border-color: var(--idp-primary-soft-strong);
        }
        
        .idp-q-circle.answered.active {
            background: var(--idp-primary-deep);
            color: #fff;
            border-color: var(--idp-primary-deep);
        }
        
        /* Navigation Buttons — centered in right half */
        .idp-nav-btns {
            display: flex;
            align-items: center;
            gap: 28px;
            flex-shrink: 0;
        }

        .idp-part-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .idp-part-nav-btn {
            padding: 9px 14px;
            border: 1px solid var(--idp-border-strong);
            border-radius: 8px;
            text-decoration: none;
            color: var(--idp-primary-deep);
            font-size: 13px;
            font-weight: 700;
            background: #fff;
            transition: all .2s ease;
            white-space: nowrap;
        }

        .idp-part-nav-btn:hover {
            background: var(--idp-primary-soft);
            color: var(--idp-primary-deep);
        }

        .idp-part-nav-btn.disabled {
            pointer-events: none;
            opacity: .45;
        }
        
        .idp-nav-text-btn {
            padding: 13px 22px;
            background: var(--idp-primary);
            border: 1px solid var(--idp-primary);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        
        .idp-nav-text-btn:hover {
            background: var(--idp-primary-deep);
            border-color: var(--idp-primary-deep);
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
            background: rgba(81, 29, 153, 0.35);
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
            box-shadow: none;
            table-layout: auto;
            border-radius: 12px;
            overflow: hidden;
        }

        .idp-table-completion-styled thead {
            background: rgba(81, 29, 153, 0.08);
        }

        .idp-table-completion-styled th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid var(--idp-border);
            color: var(--idp-text);
            font-size: 13px;
            word-wrap: break-word;
            max-width: 200px;
        }

        .idp-table-completion-styled td {
            padding: 10px 12px;
            border: 1px solid var(--idp-border);
            vertical-align: top;
            line-height: 1.6;
            font-size: 13px;
            color: var(--idp-text);
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
            background: var(--idp-primary);
            color: #ffffff;
            font-weight: bold;
            font-size: 13px;
            border-radius: 50%;
            padding: 4px;
            flex-shrink: 0;
        }

        .idp-table-input {
            border: 2px dashed var(--idp-primary);
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
            border-color: var(--idp-primary-deep);
            box-shadow: 0 0 0 3px rgba(81, 29, 153, 0.10);
        }

        .idp-table-input:hover {
            border-color: var(--idp-primary-deep);
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
        .idp-left::-webkit-scrollbar-thumb, .idp-right::-webkit-scrollbar-thumb { background: rgba(81, 29, 153, 0.28); border-radius: 4px; }
        
        /* Modal */
        .idp-modal { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 3000; }
        .idp-modal.hidden { display: none; }
        .idp-modal-box { background: #fff; padding: 28px 36px; border-radius: 14px; text-align: center; max-width: 400px; border: none; box-shadow: none; }
        .idp-modal-title { font-size: 17px; font-weight: bold; margin-bottom: 12px; }
        .idp-modal-text { font-size: 14px; margin-bottom: 20px; }
        .idp-modal-btns { display: flex; gap: 10px; justify-content: center; }
        .idp-modal-btn { padding: 8px 24px; font-size: 14px; border: none; border-radius: 4px; cursor: pointer; }
        .idp-modal-btn.cancel { background: rgba(81, 29, 153, 0.10); color: var(--idp-primary-deep); }
        .idp-modal-btn.confirm { background: var(--idp-primary); color: #fff; }

        /* ========== STUDENT HIGHLIGHT / NOTE ========== */
        .idp-user-highlight {
            background: #fff1a8;
            border-radius: 3px;
            padding: 0 1px;
            cursor: pointer;
            box-decoration-break: clone;
            -webkit-box-decoration-break: clone;
        }

        .idp-user-highlight.has-note {
            background: #ffd98a;
            border-bottom: 1px dashed rgba(81, 29, 153, 0.65);
        }

        .idp-annotate-toolbar {
            position: fixed;
            z-index: 2500;
            display: none;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid var(--idp-border-strong);
            box-shadow: 0 8px 26px rgba(33, 20, 59, 0.18);
            border-radius: 10px;
            padding: 6px;
        }

        .idp-annotate-btn {
            border: 1px solid var(--idp-border-strong);
            background: #fff;
            color: var(--idp-primary-deep);
            border-radius: 8px;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            line-height: 1;
        }

        .idp-annotate-btn:hover {
            background: var(--idp-primary-soft);
        }

        .idp-annotate-note-pop {
            position: fixed;
            z-index: 2550;
            display: none;
            width: min(340px, calc(100vw - 24px));
            background: #fff;
            border: 1px solid var(--idp-border-strong);
            border-radius: 12px;
            box-shadow: 0 14px 36px rgba(33, 20, 59, 0.2);
            padding: 12px;
        }

        .idp-annotate-note-title {
            font-size: 12px;
            color: var(--idp-muted);
            margin-bottom: 6px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .idp-annotate-note-text {
            font-size: 13px;
            color: var(--idp-text);
            line-height: 1.5;
            margin-bottom: 10px;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .idp-annotate-note-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-end;
        }

        .idp-annotate-note-btn {
            border: 1px solid var(--idp-border-strong);
            background: #fff;
            color: var(--idp-primary-deep);
            border-radius: 7px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .idp-annotate-note-btn.delete {
            border-color: rgba(220, 38, 38, 0.45);
            color: #b42323;
        }
        
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

            .idp-part-bar {
                top: 130px;
                left: 8px;
                right: 8px;
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

            .idp-part-chip {
                padding: 7px 12px;
                font-size: 12px;
            }

            .idp-part-nav-btn {
                padding: 8px 10px;
                font-size: 11px;
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
        
        // Load section question groups for auto-assignment of questions without group_id
        $sectionGroups = \App\Models\IeltsQuestionGroup::where('section_id', $currentSection->id)
            ->orderBy('question_start')
            ->get()
            ->keyBy('id');

        // Auto-assign question_group_id from number range for questions that don't have it
        foreach($allQuestions as $q) {
            if(!$q->question_group_id && $sectionGroups->isNotEmpty()) {
                $matched = $sectionGroups->first(fn($g) =>
                    $q->question_number >= $g->question_start && $q->question_number <= $g->question_end
                );
                if($matched) {
                    $q->question_group_id = $matched->id;
                    $q->setRelation('questionGroup', $matched);
                }
            }
        }

        // Group questions by question_group_id (preferred) or question_type as fallback
        $groupedQuestions = collect([]);
        $currentGroupKey = null;
        $currentGroup = [];

        foreach($allQuestions as $q) {
            $groupKey = $q->question_group_id
                ? 'grp_' . $q->question_group_id
                : 'type_' . ($q->question_type ?? 'fill_blank');

            if($groupKey !== $currentGroupKey) {
                if(!empty($currentGroup)) {
                    $groupedQuestions->push(collect($currentGroup));
                }
                $currentGroup = [$q];
                $currentGroupKey = $groupKey;
            } else {
                $currentGroup[] = $q;
            }
        }

        // Add last group
        if(!empty($currentGroup)) {
            $groupedQuestions->push(collect($currentGroup));
        }

        // Resolve active part (from query/controller first, then question fallback)
        $sectionParts = isset($sectionParts) && $sectionParts instanceof \Illuminate\Support\Collection
            ? $sectionParts->values()
            : collect($sectionParts ?? [])->values();

        $currentPartId = !empty($activePartId) ? (int) $activePartId : null;
        $currentPart = $currentPartId ? $sectionParts->firstWhere('id', $currentPartId) : null;

        if (!$currentPart) {
            $currentPartId = $firstQ->part_id ?? null;
            $currentPart = $currentPartId ? \App\Models\IeltsTestPart::find($currentPartId) : null;
        }

        if (!$currentPart && $sectionParts->isNotEmpty()) {
            $currentPart = $sectionParts->first();
            $currentPartId = $currentPart->id ?? null;
        }

        if (!$currentPart) {
            $currentPart = \App\Models\IeltsTestPart::where('section_id', $currentSection->id)
                ->orderBy('sort_order')
                ->first();
            $currentPartId = $currentPart->id ?? null;
        }

        $hasPartSwitcher = $skill !== 'writing' && $sectionParts->count() > 1;
        $currentPartIndex = !empty($currentPartId)
            ? $sectionParts->search(fn($part) => (int) $part->id === (int) $currentPartId)
            : null;
        $previousPart = (is_numeric($currentPartIndex) && $currentPartIndex > 0)
            ? $sectionParts->get($currentPartIndex - 1)
            : null;
        $nextPart = (is_numeric($currentPartIndex) && $currentPartIndex < ($sectionParts->count() - 1))
            ? $sectionParts->get($currentPartIndex + 1)
            : null;

        $currentPartAudioUrl = null;
        if (!empty($currentPart->audio_file)) {
            $audioFile = $currentPart->audio_file;
            if (str_starts_with($audioFile, '/') || str_starts_with($audioFile, 'http')) {
                $currentPartAudioUrl = $audioFile;
            } else {
                $currentPartAudioUrl = \Storage::disk('public')->url($audioFile);
            }
        }
        $resolvedListeningAudioUrl = $currentPartAudioUrl ?? ($currentSection->audio_url ?? null);

        $currentPartTitle = $currentPart->title ?? null;
        $currentPartInstructions = $currentPart->instructions ?? null;
        $currentPartPassage = $currentPart->passage ?? null;

        $currentPartImageUrl = null;
        if (!empty($currentPart->task_image)) {
            $partImageFile = $currentPart->task_image;
            if (str_starts_with($partImageFile, '/') || str_starts_with($partImageFile, 'http')) {
                $currentPartImageUrl = $partImageFile;
            } else {
                $currentPartImageUrl = \Storage::disk('public')->url($partImageFile);
            }
        }

        $currentPartVideoUrl = null;
        if (!empty($currentPart->video_file)) {
            $partVideoFile = $currentPart->video_file;
            if (str_starts_with($partVideoFile, '/') || str_starts_with($partVideoFile, 'http')) {
                $currentPartVideoUrl = $partVideoFile;
            } else {
                $currentPartVideoUrl = \Storage::disk('public')->url($partVideoFile);
            }
        }

        // Inline builder stores the main reading body in part passage.
        // Prefer part data first so preview shows full author-entered content.
        $resolvedPassageTitle = $currentPartTitle ?? $currentSection->passage_title;
        $resolvedPassageText = $currentPartPassage ?? $currentSection->passage_text ?? $currentSection->content ?? '';

        $normalizePossiblyTruncatedRichText = static function ($html) {
            return $html;
        };

        $displayPartInstructions = $normalizePossiblyTruncatedRichText($currentPartInstructions);
        $displayPassageText = $normalizePossiblyTruncatedRichText($resolvedPassageText);
        $hasPartLeftPanelContent = !empty($resolvedPassageTitle)
            || !empty($currentPartInstructions)
            || !empty($resolvedPassageText)
            || !empty($currentPartAudioUrl)
            || !empty($currentPartImageUrl)
            || !empty($currentPartVideoUrl);
        
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
                    'drag_drop_disappear' => 'Choose the correct option from the list and drag it to each gap. Each option can only be used once.',
                    'drag_drop_reuse' => 'Choose the correct option from the list and drag it to each gap. Options may be used more than once.',
                    
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
        @if(!empty($isMentorPreview) && !empty($mentorPreviewExitUrl))
            <a class="idp-exit-preview-btn" href="{{ $mentorPreviewExitUrl }}" onclick="return confirm('Thoát chế độ xem trước và quay lại màn hình tạo đề?');">
                Exit Preview
            </a>
        @endif
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

    @if($hasPartSwitcher)
        <div class="idp-part-bar">
            @foreach($sectionParts as $partIndex => $part)
                <a
                    class="idp-part-chip {{ (int) $part->id === (int) $currentPartId ? 'active' : '' }}"
                    href="{{ route('panel.ielts_tests.take', ['attemptId' => $attempt->id, 'part_id' => $part->id]) }}"
                >
                    Part {{ $partIndex + 1 }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    <main class="idp-main" @if($skill === 'writing') style="top: 84px;" @elseif($hasPartSwitcher) style="top: 188px;" @endif>
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
                'questions' => $allQuestions,
                'userAnswers' => $userAnswers,
                'userAnswer' => $userAnswers[$firstQ->id ?? 0] ?? ''
            ])
        @elseif($skill === 'listening' && empty($currentSection->passage_text) && !$hasPartLeftPanelContent)
            {{-- LISTENING FULL WIDTH (no passage) --}}
            <div class="idp-right" id="rightPanel" style="flex: none; width: 100%;">
                @if($attempt->test->isPracticeTest() && !empty($resolvedListeningAudioUrl))
                    <div class="idp-audio-inline">
                        <audio id="audioPlayer" controls>
                            <source src="{{ $resolvedListeningAudioUrl }}" type="audio/mpeg">
                            Your browser does not support audio playback.
                        </audio>
                    </div>
                @endif
                @include('design_1.panel.ielts_tests.partials.idp_questions_panel', [
                    'groupedQuestions' => $groupedQuestions,
                    'userAnswers' => $userAnswers,
                    'skill' => $skill
                ])
            </div>
        @else
            {{-- READING / LISTENING WITH PASSAGE --}}
            <div class="idp-left" id="leftPanel">
                @if(!empty($resolvedPassageTitle))
                    <h2 class="idp-passage-title">{{ $resolvedPassageTitle }}</h2>
                @endif
                @if(!empty($currentSection->subtitle))
                    <p class="idp-passage-note">{{ $currentSection->subtitle }}</p>
                @endif

                @if(!empty($displayPartInstructions))
                    <div class="idp-part-instructions">{!! $displayPartInstructions !!}</div>
                @endif

                @if(!empty($currentPartAudioUrl) || !empty($currentPartImageUrl) || !empty($currentPartVideoUrl))
                    <div class="idp-part-media">
                        @if(!empty($currentPartAudioUrl))
                            <div class="idp-part-media-item">
                                <audio controls>
                                    <source src="{{ $currentPartAudioUrl }}" type="audio/mpeg">
                                    Your browser does not support audio playback.
                                </audio>
                            </div>
                        @endif

                        @if(!empty($currentPartImageUrl))
                            <div class="idp-part-media-item">
                                <img src="{{ $currentPartImageUrl }}" alt="Part image">
                            </div>
                        @endif

                        @if(!empty($currentPartVideoUrl))
                            <div class="idp-part-media-item">
                                <video controls>
                                    <source src="{{ $currentPartVideoUrl }}" type="video/mp4">
                                    Your browser does not support video playback.
                                </video>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="idp-passage-text">
                    {!! $displayPassageText !!}
                </div>
            </div>
            
            <div class="idp-divider" id="divider"><span class="idp-divider-icon">↔</span></div>
            
            <div class="idp-right" id="rightPanel">
                @if($skill === 'listening' && $attempt->test->isPracticeTest() && !empty($resolvedListeningAudioUrl) && !$hasPartLeftPanelContent)
                    <div class="idp-audio-inline">
                        <audio id="audioPlayer" controls>
                            <source src="{{ $resolvedListeningAudioUrl }}" type="audio/mpeg">
                            Your browser does not support audio playback.
                        </audio>
                    </div>
                @endif
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
        // Expand table completion questions so every blank becomes its own navigable item
        $currentQuestions = collect();
        $displayQuestionCursor = (int) ($allQuestions->first()->question_number ?? 1);
        foreach ($allQuestions as $q) {
            $questionType = $q->question_type ?? 'fill_blank';
            $savedAnswerJson = $userAnswers[$q->id] ?? '';
            $savedAnswerData = is_string($savedAnswerJson) ? json_decode($savedAnswerJson, true) : $savedAnswerJson;

            if ($questionType === 'table_completion') {                $tableStructure = $q->table_structure ?? null;

                if (!$tableStructure && !empty($q->question_data)) {
                    $questionData = is_array($q->question_data)
                        ? $q->question_data
                        : json_decode($q->question_data, true);

                    if (is_array($questionData) && isset($questionData['table_structure'])) {
                        $tableStructure = $questionData['table_structure'];
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

                $savedAnswers = [];
                if (is_array($savedAnswerData) && !empty($savedAnswerData['answers']) && is_array($savedAnswerData['answers'])) {
                    $savedAnswers = $savedAnswerData['answers'];
                }

                for ($blankIndex = 0; $blankIndex < $blankCount; $blankIndex++) {
                    $currentQuestions->push([
                        'id' => $q->id,
                        'number' => $displayQuestionCursor,
                        'answered' => !empty($savedAnswers[$blankIndex]['answer'] ?? null),
                    ]);
                    $displayQuestionCursor++;
                }

                continue;
            }

            // Drag & Drop types also have multiple blanks per question
            if (in_array($questionType, ['drag_drop_disappear', 'drag_drop_reuse'])) {
                $rawText    = $q->question_text ?? '';
                $blankCount = max(1, substr_count($rawText, '___'));

                $savedAnswers = [];
                if (is_array($savedAnswerData)) {
                    $savedAnswers = $savedAnswerData;
                }

                for ($blankIndex = 0; $blankIndex < $blankCount; $blankIndex++) {
                    $currentQuestions->push([
                        'id'       => $q->id,
                        'number'   => $displayQuestionCursor,
                        'answered' => !empty($savedAnswers[$blankIndex] ?? null),
                    ]);
                    $displayQuestionCursor++;
                }

                continue;
            }

            if (in_array($questionType, ['summary_completion', 'sentence_completion', 'short_answer'])) {
                $rawText = (string) ($q->question_text ?? '');
                $matches = [];
                preg_match_all('/_{2,}|\[\s*\d*\s*\]|____/', $rawText, $matches);
                $blankCount = !empty($matches[0]) ? count($matches[0]) : 0;

                $savedAnswers = [];
                if (is_array($savedAnswerData) && isset($savedAnswerData['answers']) && is_array($savedAnswerData['answers'])) {
                    $savedAnswers = array_values(array_map(static function ($item) {
                        return is_array($item) ? ($item['answer'] ?? '') : (string) $item;
                    }, $savedAnswerData['answers']));
                } elseif (is_array($savedAnswerData)) {
                    $savedAnswers = array_values(array_map(static function ($item) {
                        return is_array($item) ? ($item['answer'] ?? '') : (string) $item;
                    }, $savedAnswerData));
                } elseif (is_string($savedAnswerJson) && str_contains($savedAnswerJson, '|')) {
                    $savedAnswers = array_map('trim', explode('|', $savedAnswerJson));
                } elseif (!empty($savedAnswerJson)) {
                    $savedAnswers = [(string) $savedAnswerJson];
                }

                $blankCount = max(1, $blankCount, count($savedAnswers));

                for ($blankIndex = 0; $blankIndex < $blankCount; $blankIndex++) {
                    $currentQuestions->push([
                        'id'       => $q->id,
                        'number'   => $displayQuestionCursor,
                        'answered' => !empty(trim((string) ($savedAnswers[$blankIndex] ?? ''))),
                    ]);
                    $displayQuestionCursor++;
                }

                continue;
            }

            if (in_array($questionType, ['note_completion', 'form_completion'])) {
                $rawText = (string) ($q->question_text ?? '');
                $matches = [];
                preg_match_all('/_{2,}|\[\s*\d*\s*\]|____/', $rawText, $matches);
                $blankCount = !empty($matches[0]) ? count($matches[0]) : 0;

                if ($blankCount === 0 && !empty($q->correct_answer) && is_string($q->correct_answer) && str_contains($q->correct_answer, '|')) {
                    $blankCount = count(array_filter(array_map('trim', explode('|', $q->correct_answer)), static fn($item) => $item !== ''));
                }

                $savedAnswers = [];
                if (is_array($savedAnswerData) && isset($savedAnswerData['answers']) && is_array($savedAnswerData['answers'])) {
                    $savedAnswers = array_values(array_map(static function ($item) {
                        return is_array($item) ? ($item['answer'] ?? '') : (string) $item;
                    }, $savedAnswerData['answers']));
                } elseif (is_array($savedAnswerData)) {
                    $savedAnswers = array_values(array_map(static function ($item) {
                        return is_array($item) ? ($item['answer'] ?? '') : (string) $item;
                    }, $savedAnswerData));
                } elseif (is_string($savedAnswerJson) && str_contains($savedAnswerJson, '|')) {
                    $savedAnswers = array_map('trim', explode('|', $savedAnswerJson));
                } elseif (!empty($savedAnswerJson)) {
                    $savedAnswers = [(string) $savedAnswerJson];
                }

                $blankCount = max(1, $blankCount, count($savedAnswers));

                for ($blankIndex = 0; $blankIndex < $blankCount; $blankIndex++) {
                    $currentQuestions->push([
                        'id'       => $q->id,
                        'number'   => $displayQuestionCursor,
                        'answered' => !empty(trim((string) ($savedAnswers[$blankIndex] ?? ''))),
                    ]);
                    $displayQuestionCursor++;
                }

                continue;
            }

            $currentQuestions->push([
                'id' => $q->id,
                'number' => $displayQuestionCursor,
                'answered' => !empty($userAnswers[$q->id] ?? null)
            ]);
            $displayQuestionCursor++;
        }

        $currentQuestions = $currentQuestions->sortBy('number')->values();
        
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
                @if($hasPartSwitcher)
                    <div class="idp-part-nav">
                        <a
                            class="idp-part-nav-btn {{ empty($previousPart) ? 'disabled' : '' }}"
                            href="{{ !empty($previousPart) ? route('panel.ielts_tests.take', ['attemptId' => $attempt->id, 'part_id' => $previousPart->id]) : '#' }}"
                        >
                            Prev part
                        </a>
                        <a
                            class="idp-part-nav-btn {{ empty($nextPart) ? 'disabled' : '' }}"
                            href="{{ !empty($nextPart) ? route('panel.ielts_tests.take', ['attemptId' => $attempt->id, 'part_id' => $nextPart->id]) : '#' }}"
                        >
                            Next part
                        </a>
                    </div>
                @endif
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
    @if($skill === 'listening' && $attempt->test->isMockTest() && !empty($resolvedListeningAudioUrl))
        <div class="idp-audio-overlay" id="audioOverlay">
            <div class="idp-audio-icon">🎧</div>
            <p class="idp-audio-msg">{!! trans('update.ielts_audio_overlay_message') !!}</p>
            <button class="idp-play-btn" onclick="playAudio()">▶ {{ trans('update.ielts_play') }}</button>
        </div>
        <audio id="audioPlayer" src="{{ $resolvedListeningAudioUrl }}"></audio>
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

    <div class="idp-annotate-toolbar" id="annotateToolbar">
        <button type="button" class="idp-annotate-btn" id="annotateHighlightBtn">Highlight</button>
        <button type="button" class="idp-annotate-btn" id="annotateNoteBtn">Add note</button>
        <button type="button" class="idp-annotate-btn" id="annotateUndoBtn">Undo</button>
        <button type="button" class="idp-annotate-btn" id="annotateCancelBtn">Cancel</button>
    </div>

    <div class="idp-annotate-note-pop" id="annotateNotePop">
        <div class="idp-annotate-note-title">Your note</div>
        <div class="idp-annotate-note-text" id="annotateNoteText"></div>
        <div class="idp-annotate-note-actions">
            <button type="button" class="idp-annotate-note-btn" id="annotateNoteEdit">Edit</button>
            <button type="button" class="idp-annotate-note-btn delete" id="annotateNoteDelete">Delete</button>
            <button type="button" class="idp-annotate-note-btn" id="annotateNoteClose">Close</button>
        </div>
    </div>

    <script>
        const attemptId = {{ $attempt->id }};
        const csrf = '{{ csrf_token() }}';
        const saveUrl = '{{ route("panel.ielts_tests.save_answer", $attempt->id) }}';
        const submitUrl = '{{ route("panel.ielts_tests.finish_section", $attempt->id) }}';
        const sectionId = {{ (int) ($currentSection->id ?? 0) }};
        const activePartId = {{ (int) ($currentPartId ?? 0) }};
        const noteStorageKey = `ielts_notes_attempt_${attemptId}_section_${sectionId}_part_${activePartId}`;
        
        // Create map of question ID to question number
        const questionIdToNumber = {};
        @foreach($currentQuestions as $qData)
            questionIdToNumber[{{ $qData['id'] }}] = {{ $qData['number'] }};
        @endforeach
        
        // Save answer with error handling and logging
        function saveAnswer(qId, value, qNumOverride = null) {
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
                const qNum = qNumOverride || questionIdToNumber[qId];
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
        const isWritingSkill = '{{ $skill }}' === 'writing';

        // Highlight / note state
        let pendingSelectionRange = null;
        let pendingSelectionContainer = null;
        let activeHighlightNode = null;
        let suppressToolbarAutoHideUntil = 0;
        
        function goToQuestion(num, index) {
            if (isWritingSkill && window.WfWriting && typeof window.WfWriting.goToQuestionNum === 'function') {
                window.WfWriting.goToQuestionNum(num);
            }

            // Scroll to question in the content area
            const questionEl = document.querySelector(`.idp-question-item[data-q-num="${num}"], tr[data-q-num="${num}"], .idp-q-item[data-q-num="${num}"], .tc-cell-input[data-q-num="${num}"], .tc-input-wrapper[data-q-num="${num}"]`);
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
            initStudentAnnotations();
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

        function getStoredAnnotations() {
            try {
                const raw = localStorage.getItem(noteStorageKey);
                const parsed = raw ? JSON.parse(raw) : [];
                return Array.isArray(parsed) ? parsed : [];
            } catch (error) {
                console.warn('Cannot read notes from storage:', error);
                return [];
            }
        }

        function setStoredAnnotations(annotations) {
            try {
                localStorage.setItem(noteStorageKey, JSON.stringify(Array.isArray(annotations) ? annotations : []));
            } catch (error) {
                console.warn('Cannot save notes to storage:', error);
            }
        }

        function getAnnotationContainers() {
            const ids = ['leftPanel', 'rightPanel'];
            return ids
                .map((id) => document.getElementById(id))
                .filter((el) => !!el);
        }

        function isAnnotatableRange(range) {
            if (!range || range.collapsed) {
                return false;
            }

            const containers = getAnnotationContainers();
            if (!containers.length) {
                return false;
            }

            const commonNode = range.commonAncestorContainer.nodeType === 1
                ? range.commonAncestorContainer
                : range.commonAncestorContainer.parentElement;

            if (!commonNode) {
                return false;
            }

            const activeContainer = containers.find((container) => container.contains(commonNode));
            if (!activeContainer) {
                return false;
            }

            const blockedSelector = 'input, textarea, select, button, audio, video, .idp-q-circle, .idp-nav-text-btn, .idp-user-highlight';
            const startElement = range.startContainer.nodeType === 1 ? range.startContainer : range.startContainer.parentElement;
            const endElement = range.endContainer.nodeType === 1 ? range.endContainer : range.endContainer.parentElement;

            if ((startElement && startElement.closest(blockedSelector)) || (endElement && endElement.closest(blockedSelector))) {
                return false;
            }

            pendingSelectionContainer = activeContainer;
            return true;
        }

        function getTextOffsetWithinContainer(container, node, offset) {
            const walker = document.createTreeWalker(container, NodeFilter.SHOW_TEXT, null);
            let total = 0;
            let current;
            while ((current = walker.nextNode())) {
                if (current === node) {
                    return total + offset;
                }
                total += current.nodeValue.length;
            }
            return -1;
        }

        function serializeRange(container, range) {
            const startNode = range.startContainer;
            const endNode = range.endContainer;
            const start = getTextOffsetWithinContainer(container, startNode, range.startOffset);
            const end = getTextOffsetWithinContainer(container, endNode, range.endOffset);

            if (start < 0 || end < 0 || end <= start) {
                return null;
            }

            return { start, end };
        }

        function locateTextPosition(container, targetOffset) {
            const walker = document.createTreeWalker(container, NodeFilter.SHOW_TEXT, null);
            let total = 0;
            let current;
            while ((current = walker.nextNode())) {
                const nextTotal = total + current.nodeValue.length;
                if (targetOffset <= nextTotal) {
                    return {
                        node: current,
                        offset: Math.max(0, targetOffset - total)
                    };
                }
                total = nextTotal;
            }
            return null;
        }

        function buildRangeFromOffsets(container, start, end) {
            const startPos = locateTextPosition(container, start);
            const endPos = locateTextPosition(container, end);
            if (!startPos || !endPos) {
                return null;
            }

            const range = document.createRange();
            range.setStart(startPos.node, startPos.offset);
            range.setEnd(endPos.node, endPos.offset);
            return range;
        }

        function wrapRangeWithHighlight(range, annotation) {
            if (!range || range.collapsed) {
                return null;
            }

            const wrapper = document.createElement('span');
            wrapper.className = 'idp-user-highlight';
            wrapper.dataset.annotationId = annotation.id;
            wrapper.dataset.annotationContainer = annotation.containerId;
            wrapper.dataset.note = annotation.note || '';
            if (annotation.note) {
                wrapper.classList.add('has-note');
            }

            const fragment = range.extractContents();
            wrapper.appendChild(fragment);
            range.insertNode(wrapper);
            return wrapper;
        }

        function showAnnotateToolbar(range) {
            const toolbar = document.getElementById('annotateToolbar');
            if (!toolbar || !range) {
                return;
            }

            const rect = range.getBoundingClientRect();
            if (!rect || (!rect.width && !rect.height)) {
                return;
            }

            const top = Math.max(8, rect.top + window.scrollY - 44);
            const left = Math.max(8, rect.left + window.scrollX + (rect.width / 2) - 110);

            toolbar.style.top = `${top}px`;
            toolbar.style.left = `${left}px`;
            toolbar.style.display = 'flex';
        }

        function hideAnnotateToolbar() {
            const toolbar = document.getElementById('annotateToolbar');
            if (toolbar) {
                toolbar.style.display = 'none';
            }
            pendingSelectionRange = null;
            pendingSelectionContainer = null;
        }

        function hideNotePopover() {
            const pop = document.getElementById('annotateNotePop');
            if (pop) {
                pop.style.display = 'none';
            }
            activeHighlightNode = null;
        }

        function applyPendingSelection(addNote) {
            if (!pendingSelectionRange || !pendingSelectionContainer) {
                return;
            }

            const text = pendingSelectionRange.toString().trim();
            if (!text) {
                hideAnnotateToolbar();
                return;
            }

            const note = addNote ? window.prompt('Nhap ghi chu cho doan vua boi den (co the de trong):', '') : '';
            if (addNote && note === null) {
                hideAnnotateToolbar();
                return;
            }

            const serialized = serializeRange(pendingSelectionContainer, pendingSelectionRange);
            if (!serialized) {
                hideAnnotateToolbar();
                return;
            }

            const annotation = {
                id: `ann_${Date.now()}_${Math.random().toString(36).slice(2, 8)}`,
                containerId: pendingSelectionContainer.id,
                start: serialized.start,
                end: serialized.end,
                note: (note || '').trim(),
                excerpt: text,
                createdAt: Date.now()
            };

            wrapRangeWithHighlight(pendingSelectionRange, annotation);

            const annotations = getStoredAnnotations();
            annotations.push(annotation);
            setStoredAnnotations(annotations);

            const selection = window.getSelection();
            if (selection) {
                selection.removeAllRanges();
            }

            hideAnnotateToolbar();
        }

        function removeAnnotation(annotationId) {
            if (!annotationId) {
                return;
            }

            const highlight = document.querySelector(`.idp-user-highlight[data-annotation-id="${annotationId}"]`);
            if (highlight && highlight.parentNode) {
                const parent = highlight.parentNode;
                while (highlight.firstChild) {
                    parent.insertBefore(highlight.firstChild, highlight);
                }
                parent.removeChild(highlight);
            }

            const next = getStoredAnnotations().filter((item) => item.id !== annotationId);
            setStoredAnnotations(next);
        }

        function undoLastAnnotation() {
            const annotations = getStoredAnnotations();
            if (!annotations.length) {
                return;
            }

            const latest = [...annotations].sort((a, b) => (b.createdAt || 0) - (a.createdAt || 0))[0];
            if (!latest || !latest.id) {
                return;
            }

            removeAnnotation(latest.id);
            hideAnnotateToolbar();
            hideNotePopover();
        }

        function editAnnotation(annotationId) {
            const annotations = getStoredAnnotations();
            const target = annotations.find((item) => item.id === annotationId);
            if (!target) {
                return;
            }

            const nextNote = window.prompt('Cap nhat ghi chu:', target.note || '');
            if (nextNote === null) {
                return;
            }

            target.note = nextNote.trim();
            setStoredAnnotations(annotations);

            const highlight = document.querySelector(`.idp-user-highlight[data-annotation-id="${annotationId}"]`);
            if (highlight) {
                highlight.dataset.note = target.note;
                highlight.classList.toggle('has-note', !!target.note);
            }
        }

        function restoreAnnotations() {
            const annotations = getStoredAnnotations();
            if (!annotations.length) {
                return;
            }

            annotations.forEach((annotation) => {
                const container = document.getElementById(annotation.containerId || '');
                if (!container) {
                    return;
                }

                const range = buildRangeFromOffsets(container, annotation.start, annotation.end);
                if (!range || range.collapsed) {
                    return;
                }

                wrapRangeWithHighlight(range, annotation);
            });
        }

        function initStudentAnnotations() {
            const toolbar = document.getElementById('annotateToolbar');
            if (!toolbar) {
                return;
            }

            restoreAnnotations();

            document.addEventListener('mouseup', function () {
                const selection = window.getSelection();
                if (!selection || selection.rangeCount === 0) {
                    hideAnnotateToolbar();
                    return;
                }

                const range = selection.getRangeAt(0);
                if (!isAnnotatableRange(range)) {
                    hideAnnotateToolbar();
                    return;
                }

                pendingSelectionRange = range.cloneRange();
                showAnnotateToolbar(range);
                suppressToolbarAutoHideUntil = Date.now() + 350;
            });

            document.getElementById('annotateHighlightBtn')?.addEventListener('click', function () {
                applyPendingSelection(false);
            });

            document.getElementById('annotateNoteBtn')?.addEventListener('click', function () {
                applyPendingSelection(true);
            });

            document.getElementById('annotateUndoBtn')?.addEventListener('click', function () {
                undoLastAnnotation();
            });

            document.getElementById('annotateCancelBtn')?.addEventListener('click', function () {
                const selection = window.getSelection();
                if (selection) {
                    selection.removeAllRanges();
                }
                hideAnnotateToolbar();
            });

            document.addEventListener('click', function (event) {
                if (Date.now() < suppressToolbarAutoHideUntil) {
                    return;
                }

                const highlight = event.target.closest('.idp-user-highlight');
                const toolbarClicked = event.target.closest('#annotateToolbar');
                const notePop = document.getElementById('annotateNotePop');

                if (highlight) {
                    const note = highlight.dataset.note || '';
                    const annotationId = highlight.dataset.annotationId;
                    if (!note) {
                        return;
                    }

                    activeHighlightNode = highlight;
                    const rect = highlight.getBoundingClientRect();
                    notePop.style.left = `${Math.max(8, rect.left + window.scrollX)}px`;
                    notePop.style.top = `${Math.max(8, rect.bottom + window.scrollY + 8)}px`;
                    document.getElementById('annotateNoteText').textContent = note;
                    notePop.dataset.annotationId = annotationId;
                    notePop.style.display = 'block';
                    hideAnnotateToolbar();
                    return;
                }

                if (!toolbarClicked && !event.target.closest('#annotateNotePop')) {
                    hideAnnotateToolbar();
                    hideNotePopover();
                }
            });

            document.getElementById('annotateNoteClose')?.addEventListener('click', function () {
                hideNotePopover();
            });

            document.getElementById('annotateNoteDelete')?.addEventListener('click', function () {
                const pop = document.getElementById('annotateNotePop');
                const annotationId = pop.dataset.annotationId;
                removeAnnotation(annotationId);
                hideNotePopover();
            });

            document.getElementById('annotateNoteEdit')?.addEventListener('click', function () {
                const pop = document.getElementById('annotateNotePop');
                const annotationId = pop.dataset.annotationId;
                editAnnotation(annotationId);

                const highlight = document.querySelector(`.idp-user-highlight[data-annotation-id="${annotationId}"]`);
                const updated = highlight?.dataset.note || '';
                if (!updated) {
                    hideNotePopover();
                    return;
                }

                document.getElementById('annotateNoteText').textContent = updated;
            });

            document.addEventListener('keydown', function (event) {
                const key = (event.key || '').toLowerCase();
                if (!(event.ctrlKey || event.metaKey) || key !== 'z') {
                    return;
                }

                const tag = (event.target?.tagName || '').toLowerCase();
                const isEditable = event.target?.isContentEditable || ['input', 'textarea', 'select'].includes(tag);
                if (isEditable) {
                    return;
                }

                event.preventDefault();
                undoLastAnnotation();
            });
        }
    </script>
</body>
</html>
