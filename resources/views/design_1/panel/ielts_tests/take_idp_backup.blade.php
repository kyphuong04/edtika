@extends('design_1.panel.layouts.panel_fullscreen')

@push('styles_top')
{{-- Google Fonts - Exact IDP fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

<style>
/* ============================================
   IDP IELTS Computer-Based Test Interface
   EXACT REPLICA - Version 2.0
   Based on official IDP/British Council CBT
   ============================================ */

/* === ROOT VARIABLES (IDP Exact Color Scheme) === */
    /* Primary Colors - IDP Brand */
    --idp-red: #E31837;
    --idp-dark-red: #c41230;
    --idp-black: #2d2d2d;
    --idp-grey: #f4f5f7;
    --idp-light-grey: #e5e5e5;
    
    /* Interface Colors */
    --idp-header-bg: #ffffff;
    --idp-header-border: #e5e5e5;
    --idp-nav-bg: #ffffff;
    --idp-panel-bg: #ffffff;
    --idp-white: #ffffff;
    
    /* Text Colors */
    --idp-text-primary: #2d2d2d;
    --idp-text-secondary: #555555;
    --idp-text-muted: #757575;
    
    /* Border */
    --idp-border: #e0e0e0;
    
    /* States */
    --idp-highlight-yellow: #fff9c4;
    --idp-selected-bg: #e6f7ff;
    
    /* Typography */
    --idp-font-ui: 'Plus Jakarta Sans', sans-serif;
    --idp-font-reading: 'Plus Jakarta Sans', sans-serif;
    
    /* Layout */
    --idp-header-height: 70px;
    --idp-footer-height: 60px;

/* === RESET & BASE STYLES === */
*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html, body {
    height: 100%;
    overflow: hidden;
}

body.idp-test-mode {
    font-family: var(--idp-font-ui);
    font-size: var(--idp-fs-base);
    color: var(--idp-text-primary);
    background: var(--idp-panel-bg);
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* === MAIN CONTAINER === */
.idp-test-wrapper {
    display: flex;
    flex-direction: column;
    height: 100vh;
    width: 100vw;
    overflow: hidden;
    background: var(--idp-panel-bg);
}

/* ============================================
   HEADER BAR - Top Navigation
   ============================================ */
.idp-header {
    height: var(--idp-header-height);
    background: var(--idp-header-bg);
    border-bottom: 1px solid var(--idp-header-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 40px;
    flex-shrink: 0;
    z-index: 100;
}

.idp-header__left {
    display: flex;
    align-items: center;
    gap: var(--idp-space-xl);
}

/* Logo */
.idp-logo {
    display: flex;
    align-items: center;
    gap: var(--idp-space-sm);
}

.idp-logo__text {
    font-size: 24px;
    font-weight: 800;
    color: var(--idp-red);
    letter-spacing: 0.5px;
    font-family: var(--idp-font-ui);
}

.idp-logo__text span {
    color: var(--idp-black);
    font-weight: 400;
}

/* Section Badge */
.idp-section-badge {
    display: flex;
    align-items: center;
    gap: var(--idp-space-md);
    padding-left: var(--idp-space-xl);
    border-left: 1px solid rgba(255,255,255,0.15);
}

.idp-section-badge__skill {
    background: var(--idp-red);
    color: var(--idp-white);
    padding: var(--idp-space-xs) var(--idp-space-md);
    border-radius: var(--idp-radius-sm);
    font-size: var(--idp-fs-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.idp-section-badge__skill--listening { background: #1890ff; }
.idp-section-badge__skill--reading { background: #52c41a; }
.idp-section-badge__skill--writing { background: #722ed1; }
.idp-section-badge__skill--speaking { background: #fa541c; }

.idp-section-badge__title {
    color: rgba(255,255,255,0.9);
    font-size: var(--idp-fs-sm);
    font-weight: 400;
    max-width: 250px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Header Center - Timer */
.idp-header__center {
    display: flex;
    align-items: center;
    gap: var(--idp-space-2xl);
}

/* Timer */
.idp-timer {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 4px;
    color: var(--idp-text-primary);
    font-weight: 700;
    font-size: 20px;
    font-family: var(--idp-font-ui);
}

.idp-timer__icon {
    color: var(--idp-text-secondary);
    font-size: 18px;
}

.idp-timer__display {
    color: var(--idp-red); /* IDP style: Red Timer Text */
}

/* Warnings remain similar but adjust colors for light bg */
.idp-timer--warning .idp-timer__display {
    color: #faad14;
}

.idp-timer--danger .idp-timer__display {
    color: #ff4d4f;
    animation: timer-blink 0.5s ease-in-out infinite;
}

@keyframes timer-blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Progress Info */
.idp-progress {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.idp-progress__text {
    color: rgba(255,255,255,0.9);
    font-size: var(--idp-fs-sm);
}

.idp-progress__bar {
    width: 120px;
    height: 4px;
    background: rgba(255,255,255,0.2);
    border-radius: 2px;
    overflow: hidden;
}

.idp-progress__fill {
    height: 100%;
    background: var(--idp-success);
    border-radius: 2px;
    transition: width 0.3s ease;
}

/* Header Right - Controls */
.idp-header__right {
    display: flex;
    align-items: center;
    gap: var(--idp-space-sm);
}

.idp-header-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: transparent;
    border: none;
    border-radius: 4px;
    color: var(--idp-text-primary);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: var(--idp-font-ui);
}

.idp-header-btn:hover {
    background: var(--idp-grey);
}

.idp-header-btn--primary {
    background: var(--idp-black);
    color: var(--idp-white);
}

.idp-header-btn--primary:hover {
    background: #444;
}

.idp-header-btn i {
    font-size: 16px;
    color: var(--idp-text-muted);
}
.idp-header-btn--primary i {
    color: var(--idp-white);
}

/* ============================================
   MAIN CONTENT AREA - Split Panels
   ============================================ */
.idp-main {
    flex: 1;
    display: flex;
    overflow: hidden;
    position: relative;
}

/* ============================================
   LEFT PANEL - PASSAGE / READING MATERIAL
   ============================================ */
.idp-passage-panel {
    display: flex;
    flex-direction: column;
    background: var(--idp-white);
    border-right: 1px solid var(--idp-border-medium);
    overflow: hidden;
    min-width: 300px;
}

/* Passage Toolbar */
.idp-passage-toolbar {
    height: var(--idp-toolbar-height);
    background: var(--idp-panel-bg);
    border-bottom: 1px solid var(--idp-border-light);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 var(--idp-space-lg);
    flex-shrink: 0;
}

.idp-passage-toolbar__title {
    font-size: var(--idp-fs-sm);
    font-weight: 600;
    color: var(--idp-text-primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.idp-passage-toolbar__tools {
    display: flex;
    align-items: center;
    gap: var(--idp-space-xs);
}

.idp-tool-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--idp-space-xs);
    padding: var(--idp-space-xs) var(--idp-space-sm);
    background: var(--idp-white);
    border: 1px solid var(--idp-border-medium);
    border-radius: var(--idp-radius-sm);
    color: var(--idp-text-secondary);
    font-size: var(--idp-fs-xs);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: var(--idp-font-ui);
}

.idp-tool-btn:hover {
    background: var(--idp-panel-bg);
    border-color: var(--idp-border-dark);
}

.idp-tool-btn--active {
    background: var(--idp-highlight-yellow);
    border-color: #e6db00;
    color: var(--idp-text-primary);
}

.idp-tool-btn i {
    font-size: 12px;
}

/* Font Size Controls */
.idp-font-controls {
    display: flex;
    align-items: center;
    gap: 2px;
    margin-left: var(--idp-space-sm);
    padding-left: var(--idp-space-sm);
    border-left: 1px solid var(--idp-border-light);
}

.idp-font-btn {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--idp-white);
    border: 1px solid var(--idp-border-medium);
    border-radius: var(--idp-radius-sm);
    color: var(--idp-text-secondary);
    font-size: var(--idp-fs-sm);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}

.idp-font-btn:hover {
    background: var(--idp-panel-bg);
}

.idp-font-btn:first-child {
    border-radius: var(--idp-radius-sm) 0 0 var(--idp-radius-sm);
    border-right: none;
}

.idp-font-btn:last-child {
    border-radius: 0 var(--idp-radius-sm) var(--idp-radius-sm) 0;
}

/* Passage Content */
.idp-passage-content {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: var(--idp-space-2xl) var(--idp-space-3xl);
    background: var(--idp-white);
}

.idp-passage-content::-webkit-scrollbar {
    width: 10px;
}

.idp-passage-content::-webkit-scrollbar-track {
    background: var(--idp-panel-bg);
}

.idp-passage-content::-webkit-scrollbar-thumb {
    background: var(--idp-border-dark);
    border-radius: 5px;
}

.idp-passage-content::-webkit-scrollbar-thumb:hover {
    background: var(--idp-text-muted);
}

/* Passage Text Styles */
.idp-passage-text {
    font-family: var(--idp-font-reading);
    font-size: var(--idp-fs-md);
    line-height: 1.9;
    color: var(--idp-text-primary);
}

.idp-passage-text h1,
.idp-passage-text h2,
.idp-passage-text h3 {
    font-family: var(--idp-font-reading);
    font-weight: 700;
    color: var(--idp-text-primary);
    margin-bottom: var(--idp-space-lg);
}

.idp-passage-text h1 { font-size: var(--idp-fs-2xl); }
.idp-passage-text h2 { font-size: var(--idp-fs-xl); }
.idp-passage-text h3 { font-size: var(--idp-fs-lg); }

.idp-passage-text p {
    margin-bottom: 1.5em;
    text-align: justify;
    hyphens: auto;
}

.idp-passage-text strong,
.idp-passage-text b {
    font-weight: 700;
}

.idp-passage-text em,
.idp-passage-text i {
    font-style: italic;
}

/* Paragraph Labels */
.idp-paragraph-label {
    display: inline-block;
    font-weight: 700;
    color: var(--idp-info);
    margin-right: var(--idp-space-sm);
    min-width: 24px;
}

/* Text Highlighting */
.idp-highlight--yellow {
    background: var(--idp-highlight-yellow);
    padding: 0 2px;
    border-radius: 2px;
}

.idp-highlight--green {
    background: var(--idp-highlight-green);
    padding: 0 2px;
    border-radius: 2px;
}

.idp-highlight--blue {
    background: var(--idp-highlight-blue);
    padding: 0 2px;
    border-radius: 2px;
}

.idp-highlight--pink {
    background: var(--idp-highlight-pink);
    padding: 0 2px;
    border-radius: 2px;
}

/* Selection styling */
.idp-passage-text::selection {
    background: var(--idp-highlight-yellow);
}

/* ============================================
   RESIZE HANDLE - Draggable Divider
   ============================================ */
.idp-resize-handle {
    width: 6px;
    background: var(--idp-border-medium);
    cursor: col-resize;
    flex-shrink: 0;
    transition: background 0.15s ease;
    position: relative;
}

.idp-resize-handle:hover {
    background: var(--idp-info);
}

.idp-resize-handle::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 2px;
    height: 30px;
    background: repeating-linear-gradient(
        to bottom,
        var(--idp-text-muted) 0px,
        var(--idp-text-muted) 3px,
        transparent 3px,
        transparent 6px
    );
}

/* ============================================
   RIGHT PANEL - QUESTIONS
   ============================================ */
.idp-question-panel {
    display: flex;
    flex-direction: column;
    background: var(--idp-white);
    overflow: hidden;
    min-width: 400px;
}

/* Question Toolbar */
.idp-question-toolbar {
    height: var(--idp-toolbar-height);
    background: var(--idp-panel-bg);
    border-bottom: 1px solid var(--idp-border-light);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 var(--idp-space-lg);
    flex-shrink: 0;
}

.idp-question-toolbar__info {
    font-size: var(--idp-fs-sm);
    font-weight: 600;
    color: var(--idp-text-primary);
}

.idp-question-toolbar__type {
    display: inline-block;
    background: var(--idp-info);
    color: var(--idp-white);
    padding: 2px var(--idp-space-sm);
    border-radius: var(--idp-radius-sm);
    font-size: var(--idp-fs-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Question Content Area */
.idp-question-scroll {
    flex: 1;
    overflow-y: auto;
    padding: var(--idp-space-xl);
    background: var(--idp-white);
}

.idp-question-scroll::-webkit-scrollbar {
    width: 10px;
}

.idp-question-scroll::-webkit-scrollbar-track {
    background: var(--idp-panel-bg);
}

.idp-question-scroll::-webkit-scrollbar-thumb {
    background: var(--idp-border-dark);
    border-radius: 5px;
}

/* Question Group */
.idp-question-group {
    margin-bottom: var(--idp-space-3xl);
}

.idp-question-group:last-child {
    margin-bottom: 0;
}

/* Group Instructions */
.idp-group-instruction {
    background: var(--idp-info-bg);
    border-left: 4px solid var(--idp-info);
    padding: var(--idp-space-lg) var(--idp-space-xl);
    margin-bottom: var(--idp-space-xl);
    border-radius: 0 var(--idp-radius-md) var(--idp-radius-md) 0;
}

.idp-group-instruction p {
    margin: 0;
    font-size: var(--idp-fs-base);
    line-height: 1.6;
    color: var(--idp-text-primary);
}

.idp-group-instruction strong {
    color: var(--idp-info);
    font-weight: 600;
}

/* Individual Question Card - Minimalist */
.idp-question {
    background: var(--idp-white);
    padding: 24px 0;
    margin-bottom: 0;
    border-bottom: 1px solid var(--idp-border);
    border-radius: 0;
}

.idp-question:hover {
    box-shadow: none;
}

.idp-question:last-child {
    border-bottom: none;
}

/* Question States */
.idp-question--current {
    border-color: var(--idp-current);
    box-shadow: 0 0 0 3px rgba(24, 144, 255, 0.15);
}

.idp-question--answered {
    border-left: 4px solid var(--idp-answered);
}

.idp-question--flagged {
    border-left: 4px solid var(--idp-flagged);
}

.idp-question--answered.idp-question--flagged {
    border-left: 4px solid;
    border-image: linear-gradient(to bottom, var(--idp-answered) 50%, var(--idp-flagged) 50%) 1;
}

/* Question Header */
.idp-question__header {
    display: flex;
    align-items: flex-start;
    gap: var(--idp-space-md);
    margin-bottom: var(--idp-space-lg);
}

/* Question Number Badge */
.idp-question__number {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    background: var(--idp-blue);
    color: var(--idp-white);
    border-radius: 50%;
    font-size: var(--idp-fs-sm);
    font-weight: 700;
    flex-shrink: 0;
}

/* Question Text */
.idp-question__text {
    flex: 1;
    font-size: var(--idp-fs-base);
    line-height: 1.7;
    color: var(--idp-text-primary);
}

.idp-question__text p {
    margin: 0;
}

/* Flag Button */
.idp-question__flag {
    position: absolute;
    top: var(--idp-space-md);
    right: var(--idp-space-md);
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: 1px solid var(--idp-border-light);
    border-radius: var(--idp-radius-md);
    color: var(--idp-text-muted);
    cursor: pointer;
    transition: all 0.15s ease;
}

.idp-question__flag:hover {
    border-color: var(--idp-flagged);
    color: var(--idp-flagged);
    background: var(--idp-warning-bg);
}

.idp-question__flag--active {
    background: var(--idp-flagged);
    border-color: var(--idp-flagged);
    color: var(--idp-white);
}

.idp-question__flag--active:hover {
    background: #d48806;
    color: var(--idp-white);
}

/* ============================================
   ANSWER INPUT STYLES - IDP Exact
   ============================================ */

/* Multiple Choice Options */
.idp-answer-options {
    display: flex;
    flex-direction: column;
    gap: var(--idp-space-sm);
}

/* Answer Options - Minimalist Radio */
.idp-answer-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.idp-answer-option {
    display: flex;
    align-items: center;
    padding: 8px 0;
    border: none;
    border-radius: 0;
    cursor: pointer;
    background: transparent;
}

.idp-answer-option:hover {
    background: transparent;
}

.idp-answer-option--selected {
    background: transparent;
    border: none;
    box-shadow: none;
}

.idp-answer-option__radio {
    width: 24px;
    height: 24px;
    border: 2px solid #ccc;
    border-radius: 50%;
    margin-right: 12px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.idp-answer-option--selected .idp-answer-option__radio {
    border-color: var(--idp-black);
    background: transparent;
}

.idp-answer-option--selected .idp-answer-option__radio::after {
    content: '';
    width: 14px;
    height: 14px;
    background: var(--idp-black);
    border-radius: 50%;
}

.idp-answer-option__label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    background: var(--idp-panel-bg);
    border: 1px solid var(--idp-border-light);
    border-radius: var(--idp-radius-sm);
    font-weight: 600;
    font-size: var(--idp-fs-sm);
    margin-right: var(--idp-space-md);
    flex-shrink: 0;
    color: var(--idp-text-primary);
}

.idp-answer-option--selected .idp-answer-option__label {
    background: var(--idp-info);
    border-color: var(--idp-info);
    color: var(--idp-white);
}

.idp-answer-option__text {
    flex: 1;
    font-size: var(--idp-fs-base);
    line-height: 1.6;
    color: var(--idp-text-primary);
}

/* Text Input - Minimalist */
.idp-text-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--idp-border);
    border-radius: 4px;
    font-size: 16px;
    font-family: var(--idp-font-ui);
    transition: all 0.2s ease;
    background: #f9f9f9;
}

.idp-text-input:focus {
    outline: none;
    border-color: var(--idp-info);
    box-shadow: 0 0 0 3px rgba(24, 144, 255, 0.1);
}

.idp-text-input::placeholder {
    color: var(--idp-text-muted);
}

.idp-text-input--filled {
    border-color: var(--idp-success);
    background: var(--idp-success-bg);
}

.idp-word-limit {
    display: block;
    margin-top: var(--idp-space-xs);
    font-size: var(--idp-fs-xs);
    color: var(--idp-text-muted);
}

/* True/False/Not Given Options */
.idp-tfng-options {
    display: flex;
    gap: var(--idp-space-md);
    flex-wrap: wrap;
}

.idp-tfng-option {
    flex: 1;
    min-width: 100px;
    padding: var(--idp-space-md) var(--idp-space-lg);
    border: 2px solid var(--idp-border-medium);
    border-radius: var(--idp-radius-md);
    text-align: center;
    cursor: pointer;
    font-weight: 600;
    font-size: var(--idp-fs-sm);
    transition: all 0.15s ease;
    background: var(--idp-white);
    color: var(--idp-text-primary);
}

.idp-tfng-option:hover {
    border-color: var(--idp-info);
    background: var(--idp-info-bg);
}

.idp-tfng-option--selected {
    background: var(--idp-info);
    border-color: var(--idp-info);
    color: var(--idp-white);
}

/* Matching - Dropdown Select */
.idp-matching-select {
    min-width: 70px;
    padding: var(--idp-space-sm) var(--idp-space-md);
    border: 2px solid var(--idp-border-medium);
    border-radius: var(--idp-radius-md);
    font-size: var(--idp-fs-base);
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    background: var(--idp-white);
    font-family: var(--idp-font-ui);
}

.idp-matching-select:focus {
    outline: none;
    border-color: var(--idp-info);
}

.idp-matching-select--filled {
    border-color: var(--idp-success);
    background: var(--idp-success-bg);
}

/* Essay/Writing Textarea */
.idp-essay-container {
    display: flex;
    flex-direction: column;
}

.idp-essay-textarea {
    width: 100%;
    min-height: 350px;
    padding: var(--idp-space-xl);
    border: 2px solid var(--idp-border-medium);
    border-radius: var(--idp-radius-md);
    font-family: var(--idp-font-reading);
    font-size: var(--idp-fs-md);
    line-height: 1.9;
    resize: vertical;
    background: var(--idp-white);
}

.idp-essay-textarea:focus {
    outline: none;
    border-color: var(--idp-info);
    box-shadow: 0 0 0 3px rgba(24, 144, 255, 0.1);
}

.idp-word-counter {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--idp-space-md) 0;
    font-size: var(--idp-fs-sm);
    color: var(--idp-text-secondary);
}

.idp-word-count {
    font-weight: 600;
    color: var(--idp-text-primary);
}

.idp-word-count--warning {
    color: var(--idp-warning);
}

.idp-word-count--success {
    color: var(--idp-success);
}

/* ============================================
   BOTTOM NAVIGATION BAR - IDP Style
   ============================================ */
.idp-nav-bar {
    height: var(--idp-footer-height);
    background: var(--idp-nav-bg);
    border-top: 1px solid var(--idp-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 40px;
    flex-shrink: 0;
}

.idp-nav-bar__left {
    display: flex;
    align-items: center;
    gap: var(--idp-space-sm);
}

.idp-nav-btn {
    display: flex;
    align-items: center;
    gap: var(--idp-space-xs);
    padding: var(--idp-space-sm) var(--idp-space-lg);
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: var(--idp-radius-md);
    color: var(--idp-white);
    font-size: var(--idp-fs-sm);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: var(--idp-font-ui);
}

.idp-nav-btn:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.3);
}

.idp-nav-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.idp-nav-btn--primary {
    background: var(--idp-info);
    border-color: var(--idp-info);
}

.idp-nav-btn--primary:hover {
    background: #096dd9;
}

.idp-nav-btn--success {
    background: var(--idp-success);
    border-color: var(--idp-success);
}

.idp-nav-btn--success:hover {
    background: #389e0d;
}

.idp-nav-btn i {
    font-size: var(--idp-fs-xs);
}

/* Question Navigation Palette */
.idp-nav-bar__center {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: nowrap;
    overflow-x: auto;
    max-width: 500px;
    padding: 4px 0;
}

.idp-nav-bar__center::-webkit-scrollbar {
    height: 4px;
}

.idp-nav-bar__center::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 2px;
}

.idp-nav-q {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 50%; /* CIRCLE for light theme */
    color: var(--idp-text-primary);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
}

.idp-nav-q:hover {
    background: var(--idp-light-grey);
}

.idp-nav-q--current {
    background: var(--idp-black);
    color: var(--idp-white);
}

.idp-nav-q--answered {
    background: #e6f7ff; /* Light blue bg */
    color: var(--idp-text-primary);
    position: relative;
}
/* Underline for answered */
.idp-nav-q--answered::after {
    content: '';
    position: absolute;
    bottom: 4px;
    width: 12px;
    height: 2px;
    background: var(--idp-black);
    border-radius: 1px;
}

.idp-nav-q--flagged {
    background: var(--idp-highlight-yellow);
}

/* Nav Right - Legend & Submit */
.idp-nav-bar__right {
    display: flex;
    align-items: center;
    gap: var(--idp-space-lg);
}

.idp-nav-legend {
    display: flex;
    align-items: center;
    gap: var(--idp-space-lg);
}

.idp-legend-item {
    display: flex;
    align-items: center;
    gap: var(--idp-space-xs);
    font-size: var(--idp-fs-xs);
    color: rgba(255,255,255,0.8);
}

.idp-legend-dot {
    width: 12px;
    height: 12px;
    border-radius: var(--idp-radius-sm);
}

.idp-legend-dot--answered { background: var(--idp-answered); }
.idp-legend-dot--flagged { background: var(--idp-flagged); }
.idp-legend-dot--current { background: var(--idp-current); }

/* ============================================
   SAVE INDICATOR
   ============================================ */
.idp-save-indicator {
    position: fixed;
    bottom: 70px;
    right: var(--idp-space-xl);
    background: var(--idp-success);
    color: var(--idp-white);
    padding: var(--idp-space-sm) var(--idp-space-lg);
    border-radius: var(--idp-radius-md);
    font-size: var(--idp-fs-sm);
    font-weight: 500;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: var(--idp-space-sm);
    box-shadow: var(--idp-shadow-lg);
}

.idp-save-indicator--show {
    opacity: 1;
    transform: translateY(0);
}

/* ============================================
   REVIEW MODAL - IDP Style
   ============================================ */
.idp-review-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.75);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.idp-review-modal--show {
    opacity: 1;
    visibility: visible;
}

.idp-review-content {
    background: var(--idp-white);
    border-radius: var(--idp-radius-lg);
    width: 90%;
    max-width: 700px;
    max-height: 85vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: var(--idp-shadow-lg);
}

.idp-review-header {
    background: var(--idp-header-bg);
    color: var(--idp-white);
    padding: var(--idp-space-lg) var(--idp-space-2xl);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.idp-review-header h2 {
    margin: 0;
    font-size: var(--idp-fs-lg);
    font-weight: 600;
}

.idp-review-close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.1);
    border: none;
    border-radius: var(--idp-radius-md);
    color: var(--idp-white);
    font-size: var(--idp-fs-xl);
    cursor: pointer;
    transition: all 0.15s ease;
}

.idp-review-close:hover {
    background: rgba(255,255,255,0.2);
}

.idp-review-body {
    flex: 1;
    overflow-y: auto;
    padding: var(--idp-space-2xl);
}

.idp-review-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--idp-space-lg);
    margin-bottom: var(--idp-space-2xl);
}

.idp-review-stat {
    background: var(--idp-panel-bg);
    padding: var(--idp-space-lg);
    border-radius: var(--idp-radius-md);
    text-align: center;
}

.idp-review-stat__value {
    font-size: var(--idp-fs-3xl);
    font-weight: 700;
    color: var(--idp-info);
}

.idp-review-stat__value--success { color: var(--idp-success); }
.idp-review-stat__value--warning { color: var(--idp-warning); }

.idp-review-stat__label {
    font-size: var(--idp-fs-sm);
    color: var(--idp-text-secondary);
    margin-top: var(--idp-space-xs);
}

.idp-review-grid {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    gap: var(--idp-space-sm);
}

.idp-review-q {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--idp-border-medium);
    border-radius: var(--idp-radius-md);
    font-size: var(--idp-fs-sm);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    position: relative;
    background: var(--idp-white);
}

.idp-review-q:hover {
    border-color: var(--idp-info);
    transform: scale(1.05);
}

.idp-review-q--answered {
    background: var(--idp-success-bg);
    border-color: var(--idp-success);
}

.idp-review-q--unanswered {
    background: var(--idp-warning-bg);
    border-color: var(--idp-warning);
}

.idp-review-q--flagged::after {
    content: '🚩';
    position: absolute;
    font-size: 9px;
    top: -4px;
    right: -4px;
}

.idp-review-footer {
    padding: var(--idp-space-lg) var(--idp-space-2xl);
    border-top: 1px solid var(--idp-border-light);
    display: flex;
    justify-content: space-between;
    background: var(--idp-panel-bg);
}

/* ============================================
   AUDIO PLAYER - Listening Section
   ============================================ */
.idp-audio-player {
    background: linear-gradient(135deg, var(--idp-header-bg) 0%, var(--idp-header-secondary) 100%);
    padding: var(--idp-space-lg) var(--idp-space-2xl);
    display: flex;
    align-items: center;
    gap: var(--idp-space-xl);
    color: var(--idp-white);
    flex-shrink: 0;
}

.idp-audio-icon {
    width: 48px;
    height: 48px;
    background: var(--idp-info);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--idp-fs-lg);
    flex-shrink: 0;
}

.idp-audio-controls {
    flex: 1;
}

.idp-audio-title {
    font-size: var(--idp-fs-sm);
    opacity: 0.8;
    margin-bottom: var(--idp-space-sm);
}

.idp-audio-player audio {
    width: 100%;
    height: 36px;
}

/* ============================================
   SUB-HEADER - Part Info & Instructions
   ============================================ */
.idp-sub-header {
    background: #f5f5f5;
    padding: 12px 40px;
    border-bottom: 1px solid var(--idp-border);
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex-shrink: 0;
}

.idp-part-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--idp-text-primary);
}

.idp-part-instruction {
    font-size: 14px;
    color: var(--idp-text-primary);
}

/* ============================================
   NOTES PANEL - Slide Out
   ============================================ */
.idp-notes-panel {
    position: fixed;
    top: var(--idp-header-height);
    right: -350px;
    width: 350px;
    height: calc(100vh - var(--idp-header-height) - var(--idp-nav-height));
    background: var(--idp-white);
    box-shadow: -4px 0 15px rgba(0,0,0,0.15);
    transition: right 0.3s ease;
    z-index: 500;
    display: flex;
    flex-direction: column;
}

.idp-notes-panel--open {
    right: 0;
}

.idp-notes-header {
    padding: var(--idp-space-md) var(--idp-space-lg);
    background: var(--idp-panel-bg);
    border-bottom: 1px solid var(--idp-border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.idp-notes-header h3 {
    margin: 0;
    font-size: var(--idp-fs-base);
    font-weight: 600;
}

.idp-notes-close {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: 1px solid var(--idp-border-light);
    border-radius: var(--idp-radius-sm);
    font-size: var(--idp-fs-lg);
    cursor: pointer;
    color: var(--idp-text-secondary);
    transition: all 0.15s ease;
}

.idp-notes-close:hover {
    background: var(--idp-error-bg);
    border-color: var(--idp-error);
    color: var(--idp-error);
}

.idp-notes-body {
    flex: 1;
    padding: var(--idp-space-lg);
    overflow: hidden;
}

.idp-notes-textarea {
    width: 100%;
    height: 100%;
    border: 1px solid var(--idp-border-light);
    border-radius: var(--idp-radius-md);
    padding: var(--idp-space-md);
    font-size: var(--idp-fs-sm);
    font-family: var(--idp-font-ui);
    line-height: 1.6;
    resize: none;
}

.idp-notes-textarea:focus {
    outline: none;
    border-color: var(--idp-info);
}

/* ============================================
   RESPONSIVE STYLES
   ============================================ */
@media (max-width: 1200px) {
    .idp-nav-bar__center {
        max-width: 350px;
    }
    
    .idp-nav-legend {
        display: none;
    }
}

@media (max-width: 1024px) {
    .idp-main {
        flex-direction: column;
    }
    
    .idp-passage-panel,
    .idp-question-panel {
        width: 100% !important;
        height: 50%;
        min-width: unset;
    }
    
    .idp-resize-handle {
        width: 100%;
        height: 6px;
        cursor: row-resize;
    }
    
    .idp-resize-handle::before {
        width: 30px;
        height: 2px;
        background: repeating-linear-gradient(
            to right,
            var(--idp-text-muted) 0px,
            var(--idp-text-muted) 3px,
            transparent 3px,
            transparent 6px
        );
    }
    
    .idp-nav-bar__center {
        display: none;
    }
    
    .idp-header__center {
        gap: var(--idp-space-md);
    }
    
    .idp-section-badge__title {
        display: none;
    }
}

/* === PRINT STYLES === */
@media print {
    .idp-header,
    .idp-nav-bar,
    .idp-tool-btn,
    .idp-nav-btn {
        display: none !important;
    }
    
    .idp-passage-panel,
    .idp-question-panel {
        width: 100% !important;
        height: auto !important;
    }
}
</style>
@endpush

@section('content')
<div class="idp-test-wrapper" x-data="idpTestApp()" @keydown.window="handleKeyboard($event)">
    
    {{-- TOP HEADER --}}
    {{-- TOP HEADER --}}
    <header class="idp-header">
        <div class="idp-header__left">
            <div class="idp-logo">
                <img src="https://ielts.idp.com/assets/img/idp-ielts-logo.svg" alt="IELTS" style="height: 32px;">
                <span class="ml-3 font-weight-bold" style="color: #333; font-size: 14px;">Test taker ID</span>
            </div>
        </div>
        
        <div class="idp-header__right">
             {{-- WiFi Icon --}}
             <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #333;">
                <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
                <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                <line x1="12" y1="20" x2="12.01" y2="20"></line>
            </svg>
            
            {{-- Bell Icon --}}
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #333; margin-left: 15px;">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            
            {{-- User Icon --}}
             <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #333; margin-left: 15px;">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
    </header>
    
    {{-- SUB HEADER --}}
    <div class="idp-sub-header">
        <div class="idp-part-title">Part <span x-text="currentSection.section_number"></span></div>
        <div class="idp-part-instruction">Read the text and answer questions <span x-text="currentSection.question_start"></span>-<span x-text="currentSection.question_end"></span>.</div>
    </div>
    
    {{-- AUDIO PLAYER FOR LISTENING --}}
    @if($currentSection->skill === 'listening' && $currentSection->hasAudio())
    <div class="idp-audio-player">
        <div class="idp-audio-icon">
            <i class="fas fa-headphones"></i>
        </div>
        <div class="idp-audio-controls">
            <div class="idp-audio-title">{{ $currentSection->title }} - Audio</div>
            <audio id="listeningAudio" 
                   controls 
                   @if($test->isMockTest()) controlsList="nodownload noplaybackrate" @endif
                   @ended="handleAudioEnd()">
                <source src="{{ $currentSection->audio_url }}" type="audio/mpeg">
                <source src="{{ $currentSection->audio_url }}" type="audio/wav">
                <source src="{{ $currentSection->audio_url }}" type="audio/ogg">
                Your browser does not support the audio element.
            </audio>
        </div>
        @if($test->isMockTest())
        <div class="idp-audio-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Mock test: Audio plays once only
        </div>
        @endif
    </div>
    @endif
    
    {{-- MAIN CONTENT AREA --}}
    <main class="idp-main">
        {{-- LEFT: PASSAGE PANEL --}}
        <div class="idp-passage-panel" :style="`width: ${panelWidth}%`">
            <div class="idp-passage-toolbar">
                <span class="idp-passage-toolbar__title">
                    @if($currentSection->skill === 'reading')
                        Reading Passage
                    @elseif($currentSection->skill === 'listening')
                        Listening Instructions
                    @else
                        Task Description
                    @endif
                </span>
                <div class="idp-passage-toolbar__tools">
                    <button class="idp-tool-btn" :class="{'idp-tool-btn--active': highlightMode}" @click="toggleHighlight()">
                        <i class="fas fa-highlighter"></i>
                        Highlight
                    </button>
                    <button class="idp-tool-btn" @click="clearHighlights()">
                        <i class="fas fa-eraser"></i>
                    </button>
                    <div class="idp-font-controls">
                        <button class="idp-font-btn" @click="decreaseFontSize()">A-</button>
                        <button class="idp-font-btn" @click="increaseFontSize()">A+</button>
                    </div>
                </div>
            </div>
            
            <div class="idp-passage-content" 
                 id="passageContent"
                 :style="`font-size: ${fontSize}px`"
                 @mouseup="handleTextSelection()">
                <div class="idp-passage-text">
                    @if($currentSection->passage_title)
                        <h3>{{ $currentSection->passage_title }}</h3>
                    @endif
                    
                    @if($currentSection->passage_text)
                        {!! $currentSection->passage_text !!}
                    @elseif($currentSection->skill === 'writing')
                        <div class="writing-task">
                            @foreach($questions as $index => $question)
                                <div class="task-item" style="margin-bottom: 30px;">
                                    <h4>Task {{ $index + 1 }}</h4>
                                    <p>{!! nl2br(e($question->question_text)) !!}</p>
                                    @if($question->image_file)
                                        <img src="{{ $question->image_file }}" alt="Task Image" style="max-width: 100%; margin: 20px 0;">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- RESIZE HANDLE --}}
        <div class="idp-resize-handle" 
             @mousedown="startResize($event)"></div>
        
        {{-- RIGHT: QUESTIONS PANEL --}}
        <div class="idp-question-panel" :style="`width: ${100 - panelWidth}%`">
            <div class="idp-question-header">
                <div class="idp-question-info">
                    Questions <span x-text="currentSection.question_start"></span> - <span x-text="currentSection.question_end"></span>
                </div>
                <span class="idp-question-type-badge" x-text="getQuestionTypeName(questions[currentQuestionIndex]?.question_type)"></span>
            </div>
            
            <div class="idp-question-content">
                {{-- Question Groups --}}
                @php
                    $groupedQuestions = $questions->groupBy('question_group_id');
                @endphp
                
                @foreach($groupedQuestions as $groupId => $groupQuestions)
                    @php
                        $firstQuestion = $groupQuestions->first();
                        $group = $firstQuestion->questionGroup ?? null;
                    @endphp
                    
                    <div class="idp-question-group">
                        {{-- Group Instructions --}}
                        @if($group && $group->instruction)
                            <div class="idp-group-instruction">
                                <p>{!! nl2br(e($group->instruction)) !!}</p>
                            </div>
                        @endif
                        
                        {{-- Questions in Group --}}
                        @foreach($groupQuestions as $qIndex => $question)
                            <div class="idp-question" 
                                 :class="{
                                     'idp-question--current': currentQuestionIndex === {{ $loop->parent->index * 100 + $loop->index }},
                                     'idp-question--answered': answers[{{ $question->id }}],
                                     'idp-question--flagged': flaggedQuestions.includes({{ $question->id }})
                                 }"
                                 id="question-{{ $question->id }}"
                                 data-question-id="{{ $question->id }}">
                                
                                {{-- Flag Button --}}
                                <button class="idp-question__flag"
                                        :class="{'idp-question__flag--active': flaggedQuestions.includes({{ $question->id }})}"
                                        @click="toggleFlag({{ $question->id }})"
                                        title="Flag for review">
                                    <i class="fas fa-flag"></i>
                                </button>
                                
                                {{-- Question Header --}}
                                <div class="idp-question__header">
                                    <span class="idp-question__number">{{ $question->question_number ?? $loop->iteration }}</span>
                                    <div class="idp-question__text">{!! $question->question_text !!}</div>
                                </div>
                                
                                {{-- Answer Input Based on Type --}}
                                @include('design_1.panel.ielts_tests.partials.question_types', ['question' => $question])
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </main>
    
    {{-- BOTTOM NAVIGATION --}}
    <div class="idp-nav-bar">
        <div class="idp-nav-bar__left">
            <span style="font-weight: 700; color: #333; margin-right: 15px;">Part <span x-text="currentSection.section_number"></span></span>
            
            {{-- Pagination --}}
            <div style="display: flex; gap: 5px;">
                 <template x-for="n in 3"> {{-- Example pagination --}}
                    <div class="idp-nav-pagination" 
                         :class="{'idp-nav-pagination--active': n === 1}"
                         style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; border-radius: 4px; font-weight: 600;"
                         :style="n === 1 ? 'background: #E6F7FF; color: #1890FF;' : 'color: #333;'"
                         x-text="n">
                    </div>
                 </template>
            </div>
        </div>
        
        <div class="idp-nav-bar__right">
            <button class="idp-nav-arrow" style="width: 40px; height: 40px; background: #e0e0e0; border: none; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 10px; cursor: pointer;">
                <i class="fas fa-arrow-left" style="color: #666;"></i>
            </button>
            <button class="idp-nav-arrow" style="width: 40px; height: 40px; background: #333; border: none; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                <i class="fas fa-arrow-right" style="color: #fff;"></i>
            </button>
        </div>
    </div>
                <div class="idp-legend-item">
                    <div class="idp-legend-dot idp-legend-dot--current"></div>
                    <span>Current</span>
                </div>
            </div>
            
            <button class="idp-nav-btn idp-nav-btn--success" @click="finishSection()">
                <i class="fas fa-check"></i>
                Submit Section
            </button>
        </div>
    </nav>
    
    {{-- NOTES PANEL --}}
    <div class="idp-notes-panel" :class="{'idp-notes-panel--open': showNotes}">
        <div class="idp-notes-header">
            <h3><i class="fas fa-sticky-note"></i> Notes</h3>
            <button class="idp-notes-close" @click="showNotes = false">&times;</button>
        </div>
        <div class="idp-notes-body">
            <textarea class="idp-notes-textarea" 
                      x-model="notes"
                      placeholder="Write your notes here. Notes are not saved or submitted."></textarea>
        </div>
    </div>
    
    {{-- REVIEW MODAL --}}
    <div class="idp-review-modal" :class="{'idp-review-modal--show': showReviewModal}">
        <div class="idp-review-content">
            <div class="idp-review-header">
                <h2><i class="fas fa-list-check"></i> Review Your Answers</h2>
                <button class="idp-review-close" @click="showReviewModal = false">&times;</button>
            </div>
            
            <div class="idp-review-body">
                <div class="idp-review-summary">
                    <div class="idp-review-stat">
                        <div class="idp-review-stat__value idp-review-stat__value--success" x-text="answeredCount"></div>
                        <div class="idp-review-stat__label">Answered</div>
                    </div>
                    <div class="idp-review-stat">
                        <div class="idp-review-stat__value idp-review-stat__value--warning" x-text="totalQuestions - answeredCount"></div>
                        <div class="idp-review-stat__label">Unanswered</div>
                    </div>
                    <div class="idp-review-stat">
                        <div class="idp-review-stat__value" x-text="flaggedQuestions.length"></div>
                        <div class="idp-review-stat__label">Flagged</div>
                    </div>
                </div>
                
                <h4 style="margin-bottom: 15px; font-size: 14px; font-weight: 600;">Question Overview</h4>
                <div class="idp-review-grid">
                    <template x-for="(q, index) in questions" :key="q.id">
                        <div class="idp-review-q"
                             :class="{
                                 'idp-review-q--answered': answers[q.id],
                                 'idp-review-q--unanswered': !answers[q.id],
                                 'idp-review-q--flagged': flaggedQuestions.includes(q.id)
                             }"
                             @click="goToQuestion(index); showReviewModal = false"
                             x-text="index + 1">
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="idp-review-footer">
                <button class="idp-nav-btn" @click="showReviewModal = false">
                    <i class="fas fa-arrow-left"></i>
                    Continue Test
                </button>
                <button class="idp-nav-btn idp-nav-btn--success" @click="finishSection()">
                    <i class="fas fa-check"></i>
                    Submit Section
                </button>
            </div>
        </div>
    </div>
    
    {{-- SAVE INDICATOR --}}
    <div class="idp-save-indicator" :class="{'idp-save-indicator--show': showSaveIndicator}">
        <i class="fas fa-check-circle"></i>
        Answer saved
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
function idpTestApp() {
    return {
        // Data
        attemptId: {{ $attempt->id }},
        test: @json($test),
        currentSection: @json($currentSection),
        questions: @json($questions),
        answers: @json($userAnswers ?? []),
        timeRemaining: {{ $attempt->remaining_time_seconds ?? 3600 }},
        currentQuestionIndex: 0,
        flaggedQuestions: [],
        notes: '',
        
        // UI State
        showNotes: false,
        showReviewModal: false,
        showSaveIndicator: false,
        highlightMode: false,
        fontSize: 16,
        panelWidth: 50,
        isResizing: false,
        timer: null,
        
        // Initialization
        init() {
            this.startTimer();
            this.loadSavedState();
            this.setupAutoSave();
            this.setupBeforeUnload();
            this.setupResize();
            document.body.classList.add('idp-test-mode');
            
            // Mock test audio handling
            @if($test->isMockTest() && $currentSection->skill === 'listening')
            this.setupMockAudio();
            @endif
        },
        
        // Timer
        startTimer() {
            this.timer = setInterval(() => {
                this.timeRemaining--;
                if (this.timeRemaining <= 0) {
                    this.autoSubmit();
                }
            }, 1000);
        },
        
        formatTime(seconds) {
            if (seconds < 0) seconds = 0;
            const hrs = Math.floor(seconds / 3600);
            const mins = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            if (hrs > 0) {
                return `${hrs}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }
            return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        },
        
        // Computed
        get totalQuestions() {
            return this.questions.length;
        },
        
        get answeredCount() {
            return Object.keys(this.answers).filter(k => this.answers[k] && this.answers[k].toString().trim() !== '').length;
        },
        
        // Setup resize handler
        setupResize() {
            const resizeHandle = document.querySelector('.idp-resize-handle');
            if (resizeHandle) {
                resizeHandle.addEventListener('mousedown', (e) => this.startResize(e));
            }
        },
        
        // Navigation
        nextQuestion() {
            if (this.currentQuestionIndex < this.totalQuestions - 1) {
                this.currentQuestionIndex++;
                this.scrollToQuestion();
            }
        },
        
        previousQuestion() {
            if (this.currentQuestionIndex > 0) {
                this.currentQuestionIndex--;
                this.scrollToQuestion();
            }
        },
        
        goToQuestion(index) {
            this.currentQuestionIndex = index;
            this.scrollToQuestion();
        },
        
        scrollToQuestion() {
            const questionId = this.questions[this.currentQuestionIndex]?.id;
            if (questionId) {
                const element = document.getElementById(`question-${questionId}`);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        },
        
        // Keyboard navigation
        handleKeyboard(event) {
            if (event.target.tagName === 'TEXTAREA' || event.target.tagName === 'INPUT') return;
            
            switch(event.key) {
                case 'ArrowRight':
                case 'ArrowDown':
                    event.preventDefault();
                    this.nextQuestion();
                    break;
                case 'ArrowLeft':
                case 'ArrowUp':
                    event.preventDefault();
                    this.previousQuestion();
                    break;
                case 'f':
                case 'F':
                    this.toggleFlag(this.questions[this.currentQuestionIndex]?.id);
                    break;
                case 'r':
                case 'R':
                    this.showReviewModal = !this.showReviewModal;
                    break;
            }
        },
        
        // Answer saving
        saveAnswer(questionId, value) {
            this.answers[questionId] = value;
            
            fetch('{{ route("panel.ielts_tests.save_answer", $attempt->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question_id: questionId,
                    answer_text: value
                })
            })
            .then(response => response.json())
            .then(data => {
                this.showSaveIndicator = true;
                setTimeout(() => this.showSaveIndicator = false, 1500);
            })
            .catch(error => console.error('Save error:', error));
        },
        
        // Flagging
        toggleFlag(questionId) {
            if (!questionId) return;
            
            const index = this.flaggedQuestions.indexOf(questionId);
            if (index === -1) {
                this.flaggedQuestions.push(questionId);
            } else {
                this.flaggedQuestions.splice(index, 1);
            }
            this.saveState();
        },
        
        // Text highlighting
        toggleHighlight() {
            this.highlightMode = !this.highlightMode;
        },
        
        handleTextSelection() {
            if (!this.highlightMode) return;
            
            const selection = window.getSelection();
            if (selection.rangeCount > 0 && selection.toString().trim()) {
                const range = selection.getRangeAt(0);
                const span = document.createElement('span');
                span.className = 'idp-highlight--yellow';
                try {
                    range.surroundContents(span);
                } catch(e) {
                    // Selection spans multiple elements
                }
                selection.removeAllRanges();
            }
        },
        
        clearHighlights() {
            const highlights = document.querySelectorAll('[class^="idp-highlight"]');
            highlights.forEach(el => {
                const text = el.textContent;
                el.replaceWith(text);
            });
        },
        
        // Font size
        increaseFontSize() {
            if (this.fontSize < 24) {
                this.fontSize += 2;
                this.saveState();
            }
        },
        
        decreaseFontSize() {
            if (this.fontSize > 12) {
                this.fontSize -= 2;
                this.saveState();
            }
        },
        
        // Panel resize
        startResize(e) {
            this.isResizing = true;
            document.addEventListener('mousemove', this.handleResize.bind(this));
            document.addEventListener('mouseup', this.stopResize.bind(this));
        },
        
        handleResize(e) {
            if (!this.isResizing) return;
            const containerWidth = document.querySelector('.idp-main').offsetWidth;
            const newWidth = (e.clientX / containerWidth) * 100;
            if (newWidth >= 30 && newWidth <= 70) {
                this.panelWidth = newWidth;
            }
        },
        
        stopResize() {
            this.isResizing = false;
            document.removeEventListener('mousemove', this.handleResize);
            document.removeEventListener('mouseup', this.stopResize);
        },
        
        // Notes
        toggleNotes() {
            this.showNotes = !this.showNotes;
        },
        
        toggleHelp() {
            Swal.fire({
                title: 'Keyboard Shortcuts',
                html: `
                    <div class="text-left">
                        <p><strong>Navigation:</strong></p>
                        <ul>
                            <li><kbd>→</kbd> / <kbd>↓</kbd> : Next question</li>
                            <li><kbd>←</kbd> / <kbd>↑</kbd> : Previous question</li>
                            <li><kbd>F</kbd> : Flag current question</li>
                        </ul>
                        <p class="mt-3"><strong>Mouse Tools:</strong></p>
                        <ul>
                            <li>Highlight: Select text after enabling highlight mode</li>
                            <li>Resize: Drag the center divider to adjust panel sizes</li>
                        </ul>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Got it!',
                confirmButtonColor: '#1a3a5c'
            });
        },
        
        // Question type helper
        getQuestionTypeName(type) {
            const types = {
                'multiple_choice': 'Multiple Choice',
                'fill_blank': 'Fill in the Blank',
                'true_false': 'True / False',
                'true_false_not_given': 'True / False / Not Given',
                'yes_no_not_given': 'Yes / No / Not Given',
                'matching': 'Matching',
                'matching_headings': 'Matching Headings',
                'matching_features': 'Matching Features',
                'matching_sentence_endings': 'Matching Sentence Endings',
                'sentence_completion': 'Sentence Completion',
                'summary_completion': 'Summary Completion',
                'note_completion': 'Note Completion',
                'table_completion': 'Table Completion',
                'flow_chart_completion': 'Flow Chart Completion',
                'diagram_labelling': 'Diagram Labelling',
                'short_answer': 'Short Answer',
                'essay': 'Essay Writing',
                'essay_task1': 'Task 1 Writing',
                'essay_task2': 'Task 2 Writing'
            };
            return types[type] || type;
        },
        
        // Word count for essays
        countWords(text) {
            if (!text) return 0;
            return text.trim().split(/\s+/).filter(w => w.length > 0).length;
        },
        
        // State management
        saveState() {
            localStorage.setItem(`ielts_test_${this.attemptId}`, JSON.stringify({
                flaggedQuestions: this.flaggedQuestions,
                notes: this.notes,
                fontSize: this.fontSize,
                panelWidth: this.panelWidth
            }));
        },
        
        loadSavedState() {
            const saved = localStorage.getItem(`ielts_test_${this.attemptId}`);
            if (saved) {
                const state = JSON.parse(saved);
                this.flaggedQuestions = state.flaggedQuestions || [];
                this.notes = state.notes || '';
                this.fontSize = state.fontSize || 17;
                this.panelWidth = state.panelWidth || 50;
            }
        },
        
        setupAutoSave() {
            setInterval(() => this.saveState(), 10000);
        },
        
        setupBeforeUnload() {
            window.addEventListener('beforeunload', (e) => {
                e.preventDefault();
                e.returnValue = 'You have unsaved progress. Are you sure you want to leave?';
            });
        },
        
        // Mock audio handling
        setupMockAudio() {
            const audio = document.getElementById('listeningAudio');
            if (audio) {
                audio.addEventListener('ended', () => {
                    audio.removeAttribute('controls');
                    audio.parentElement.innerHTML = '<div class="text-center text-warning p-3"><i class="fas fa-info-circle mr-2"></i>Audio has finished. You cannot replay it.</div>';
                });
            }
        },
        
        handleAudioEnd() {
            // Audio ended event handler
        },
        
        // Submit
        finishSection() {
            const unanswered = this.totalQuestions - this.answeredCount;
            let message = 'Are you sure you want to submit this section?';
            
            if (unanswered > 0) {
                message = `You have ${unanswered} unanswered question(s). Are you sure you want to submit?`;
            }
            
            if (this.flaggedQuestions.length > 0) {
                message += `\n\nYou also have ${this.flaggedQuestions.length} flagged question(s) for review.`;
            }
            
            if (confirm(message)) {
                clearInterval(this.timer);
                window.removeEventListener('beforeunload', () => {});
                
                fetch('{{ route("panel.ielts_tests.finish_section", $attempt->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    localStorage.removeItem(`ielts_test_${this.attemptId}`);
                    if (data.status === 'completed') {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                });
            }
        },
        
        autoSubmit() {
            clearInterval(this.timer);
            Swal.fire({
                title: 'Time\'s Up!',
                text: 'Your test will be submitted automatically.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#1a3a5c',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = '{{ route("panel.ielts_tests.submit", $attempt->id) }}';
            });
        }
    }
}
</script>
@endpush
