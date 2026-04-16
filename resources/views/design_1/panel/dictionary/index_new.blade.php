@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .dictionary-container {
        max-width: 100%;
        margin: 0;
        padding: 0 30px;
    }
    
    .user-stats-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .dark-mode .user-stats-card {
        background: #1e293b;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 20px;
    }
    
    .user-name {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    .dark-mode .user-name {
        color: #f1f5f9;
    }

    /* ── Dictionary Profile Card ─────────────────────────────── */
    .dict-profile-card {
        position: relative;
        background: #fff;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .dark-mode .dict-profile-card {
        background: #1e293b;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .dict-profile-trigger {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        user-select: none;
    }
    .dict-profile-avatar {
        width: 44px;
        min-width: 44px;
        height: 44px;
        border-radius: 50%;
        overflow: hidden;
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        flex-shrink: 0;
    }
    .dict-profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .dict-profile-info {
        flex: 1;
        min-width: 0;
    }
    .dict-profile-name {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: 0.4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .dark-mode .dict-profile-name { color: #f1f5f9; }
    .dict-profile-band {
        font-size: 11px;
        color: #64748b;
        margin-top: 2px;
    }
    .dark-mode .dict-profile-band { color: #94a3b8; }
    .dict-profile-chevron {
        flex-shrink: 0;
        color: #94a3b8;
        transition: transform 0.25s ease;
    }
    .dict-profile-card.open .dict-profile-chevron {
        transform: rotate(180deg);
    }
    .dict-profile-dropdown {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        left: 0;
        width: 100%;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transform: translateY(8px);
        transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
        padding-bottom: 6px;
    }
    .dark-mode .dict-profile-dropdown {
        background: #1e293b;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .dict-profile-card.open .dict-profile-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .band-estimate {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }
    
    .dark-mode .band-estimate {
        color: #94a3b8;
    }
    
    .search-section {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .dark-mode .search-section {
        background: #1e293b;
    }
    
    .search-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 15px;
    }
    
    .dark-mode .search-title {
        color: #f1f5f9;
    }
    
    .search-wrapper {
        display: flex;
        gap: 10px;
    }
    
    .search-input {
        flex: 1;
        padding: 12px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .dark-mode .search-input {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    .search-btn {
        padding: 12px 30px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .search-btn:hover {
        background: #2563eb;
    }
    
    .word-lists-section {
        margin-bottom: 30px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    .dark-mode .section-title {
        color: #f1f5f9;
    }
    
    .toggle-btn {
        padding: 8px 20px;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .toggle-btn.active {
        background: #3b82f6;
        color: white;
    }
    
    .dark-mode .toggle-btn {
        background: #0f172a;
        color: #94a3b8;
    }
    
    .word-list-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        cursor: default;
        transition: all 0.3s;
        position: relative;
    }
    
    .word-list-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .dark-mode .word-list-card {
        background: #1e293b;
    }
    
    .word-list-card.locked {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .word-list-card.locked:hover {
        transform: none;
    }
    
    .lock-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        color: #94a3b8;
        font-size: 20px;
    }
    
    .word-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        cursor: pointer;
        user-select: none;
    }
    
    .word-list-header:hover .word-list-name {
        color: #3b82f6;
    }
    
    .word-list-name {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    .dark-mode .word-list-name {
        color: #f1f5f9;
    }
    
    .word-count-badge {
        padding: 4px 12px;
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .dark-mode .word-count-badge {
        background: #0c4a6e;
        color: #7dd3fc;
    }
    
    .word-list-description {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }
    
    .dark-mode .word-list-description {
        color: #94a3b8;
    }
    
    .word-list-expanded {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        margin-top: 15px;
        display: none;
    }

    .dark-mode .word-list-expanded {
        background: #0f172a;
    }

    .word-list-expanded.show {
        display: block;
    }

    .filter-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
    }

    .filter-input {
        flex: 1;
        padding: 10px 18px;
        border: 1px solid #d1d5db;
        border-radius: 50px;
        font-size: 14px;
        background: #fff;
        color: #1e293b;
        outline: none;
        transition: border-color 0.2s;
    }
    .filter-input:focus { border-color: #3b82f6; }

    .dark-mode .filter-input {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }

    .filter-select {
        padding: 10px 16px;
        border: 1px solid #d1d5db;
        border-radius: 50px;
        font-size: 14px;
        background: #fff;
        color: #475569;
        cursor: pointer;
        outline: none;
        min-width: 110px;
        transition: border-color 0.2s;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 32px;
    }
    .filter-select:focus { border-color: #3b82f6; }
    .dark-mode .filter-select {
        background-color: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }

    /* ── Word item card ── */
    .word-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 40px 14px 14px; /* right padding leaves room for badge */
        margin-bottom: 10px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        position: relative;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .word-item:hover {
        border-color: #93c5fd;
        box-shadow: 0 2px 8px rgba(59,130,246,0.08);
    }
    .dark-mode .word-item {
        background: #1e293b;
        border-color: #334155;
    }
    .dark-mode .word-item:hover { border-color: #3b82f6; }

    .word-checkbox {
        width: 18px;
        height: 18px;
        margin-top: 3px;   /* align with first text line */
        flex-shrink: 0;
        cursor: pointer;
        accent-color: #3b82f6;
    }

    .word-content { flex: 1; min-width: 0; }

    /* Word + pronunciation on SAME line */
    .word-title-row {
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 4px;
    }
    .word-text {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }
    .dark-mode .word-text { color: #f1f5f9; }

    .word-pronunciation {
        font-size: 13px;
        color: #64748b;
        font-style: italic;
    }
    .dark-mode .word-pronunciation { color: #94a3b8; }

    .word-definition {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }
    .dark-mode .word-definition { color: #cbd5e1; }

    /* Learned tick — plain ✓ at top-right, no circle */
    .learned-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 14px;
        font-weight: 700;
        color: #10b981;
        display: none;
        cursor: pointer;
        line-height: 1;
        transition: opacity 0.2s;
    }
    .learned-badge.show { display: block; }
    .learned-badge:hover { opacity: 0.7; }

    .action-bar {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    .dark-mode .action-bar { border-color: #334155; }

    .action-btn {
        padding: 9px 22px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        background: transparent;
    }
    .btn-select-all, .btn-deselect-all {
        border: 1.5px solid #94a3b8;
        color: #475569;
    }
    .btn-select-all:hover, .btn-deselect-all:hover {
        border-color: #64748b;
        background: #f1f5f9;
    }
    .btn-delete {
        border: 1.5px solid #dc2626;
        color: #dc2626;
    }
    .btn-delete:hover { background: #fee2e2; }
    .btn-practice {
        border: 1.5px solid #3b82f6;
        color: #3b82f6;
    }
    .btn-practice:hover { background: #dbeafe; }

    .dark-mode .btn-select-all,
    .dark-mode .btn-deselect-all {
        border-color: #475569;
        color: #cbd5e1;
    }
    .dark-mode .btn-select-all:hover,
    .dark-mode .btn-deselect-all:hover { background: #0f172a; }
    .dark-mode .btn-delete {
        border-color: #f87171;
        color: #f87171;
    }
    .dark-mode .btn-delete:hover { background: #7f1d1d; }
    .dark-mode .btn-practice {
        border-color: #60a5fa;
        color: #60a5fa;
    }
    .dark-mode .btn-practice:hover { background: #1e3a5f; }
    
    .stats-widget {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .dark-mode .stats-widget {
        background: #1e293b;
    }
    
    .widget-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .dark-mode .widget-title {
        color: #f1f5f9;
    }
    
    .streak-circles {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }
    
    .streak-circle {
        background: #f8fafc;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        color: #94a3b8;
        transition: all 0.3s;
    }
    
    .streak-circle.active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .dark-mode .streak-circle {
        background: #0f172a;
        border-color: #334155;
    }
    
    .dark-mode .streak-circle.active {
        background: #3b82f6;
        border-color: #3b82f6;
    }
    
    .ranking-placeholder {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
        font-size: 14px;
        line-height: 1.6;
        background: #f8fafc;
        border-radius: 8px;
    }
    
    .dark-mode .ranking-placeholder {
        background: #0f172a;
        color: #64748b;
    }

    .flashcard-preview {
        text-align: center;
    }

    .flashcard-info {
        padding: 20px;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .dark-mode .flashcard-info {
        background: #0f172a;
    }

    .flashcard-info p {
        font-size: 14px;
        line-height: 1.6;
    }

    .flashcard-info .text-muted {
        color: #64748b !important;
        font-size: 13px;
    }

    .dark-mode .flashcard-info .text-muted {
        color: #94a3b8 !important;
    }
    
    .practice-mode-container {
        background: #fff;
        border-radius: 12px;
        padding: 40px 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .dark-mode .practice-mode-container {
        background: #1e293b;
    }
    
    .score-board {
        display: flex;
        justify-content: center;
        gap: 50px;
        margin-bottom: 40px;
        margin-top: 0;
    }
    
    .score-item {
        text-align: center;
        border: 2px solid #cbd5e1;
        border-radius: 30px;
        padding: 12px 40px;
        min-width: 160px;
    }

    .dark-mode .score-item {
        border-color: #334155;
    }
    
    .score-label {
        font-size: 14px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dark-mode .score-label {
        color: #94a3b8;
    }
    
    .score-value {
        font-size: inherit;
        font-weight: inherit;
        color: inherit;
    }
    
    .score-value.correct {
        color: inherit;
    }
    
    .score-value.incorrect {
        color: inherit;
    }
    
    .question-text {
        font-size: 16px;
        color: #1e293b;
        text-align: center;
        margin-bottom: 45px;
        margin-top: 10px;
        line-height: 1.6;
        font-weight: 500;
    }
    
    .dark-mode .question-text {
        color: #f1f5f9;
    }
    
    .answers-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
        margin-bottom: 40px;
    }
    
    .answer-card {
        background: #f8fafc;
        border: 2px solid #cbd5e1;
        border-radius: 18px;
        padding: 18px 14px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .answer-card:hover {
        border-color: #3b82f6;
        transform: scale(1.02);
    }
    
    .answer-card.selected {
        border-color: #3b82f6;
        background: #dbeafe;
    }
    
    .answer-card.correct {
        border-color: #10b981;
        background: #d1fae5;
    }
    
    .answer-card.incorrect {
        border-color: #ef4444;
        background: #fee2e2;
    }
    
    .dark-mode .answer-card {
        background: #0f172a;
        border-color: #334155;
    }
    
    .answer-word {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .dark-mode .answer-word {
        color: #f1f5f9;
    }
    
    .answer-pronunciation {
        font-size: 13px;
        color: #64748b;
        font-style: italic;
    }
    
    .dark-mode .answer-pronunciation {
        color: #94a3b8;
    }
    
    .practice-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 35px;
        margin-top: 25px;
        padding-top: 15px;
    }
    
    .practice-btn {
        padding: 8px 26px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
    }
    
    .btn-exit {
        background: transparent;
        color: #475569;
        border: 2px solid #cbd5e1;
    }
    
    .btn-exit:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }
    
    .btn-next {
        background: transparent;
        color: #475569;
        border: 2px solid #cbd5e1;
    }
    
    .btn-next:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }
    
    .btn-next:disabled {
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: #94a3b8;
        cursor: not-allowed;
    }
    
    .dark-mode .btn-exit {
        background: transparent;
        color: #cbd5e1;
        border-color: #475569;
    }
    
    .dark-mode .btn-exit:hover {
        background: #0f172a;
        border-color: #64748b;
    }
    
    .dark-mode .btn-next {
        background: transparent;
        color: #cbd5e1;
        border-color: #475569;
    }
    
    .dark-mode .btn-next:hover {
        background: #0f172a;
        border-color: #64748b;
    }
    
    .hidden {
        display: none !important;
    }

    /* Dictionary Result Styles */
    .dictionary-result-container {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .dark-mode .dictionary-result-container {
        background: #1e293b;
    }

    .result-word {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .dark-mode .result-word {
        color: #f1f5f9;
    }

    .result-pronunciation {
        font-size: 18px;
        color: #3b82f6;
        font-style: italic;
        margin-bottom: 20px;
    }

    .dark-mode .result-pronunciation {
        color: #60a5fa;
    }

    .result-pos {
        display: inline-block;
        background: #e0f2fe;
        color: #0284c7;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .dark-mode .result-pos {
        background: #0c4a6e;
        color: #7dd3fc;
    }

    .result-definition {
        font-size: 16px;
        color: #475569;
        margin: 10px 0;
        line-height: 1.6;
    }

    .dark-mode .result-definition {
        color: #cbd5e1;
    }

    .result-example {
        font-size: 15px;
        color: #64748b;
        font-style: italic;
        margin: 8px 0;
        padding-left: 15px;
        border-left: 3px solid #e2e8f0;
    }

    .dark-mode .result-example {
        color: #94a3b8;
        border-left-color: #334155;
    }

    .result-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .result-btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .result-btn-primary {
        background: #3b82f6;
        color: white;
    }

    .result-btn-primary:hover {
        background: #2563eb;
    }

    .result-btn-secondary {
        background: #f1f5f9;
        color: #475569;
    }

    .result-btn-secondary:hover {
        background: #e2e8f0;
    }

    .dark-mode .result-btn-secondary {
        background: #0f172a;
        color: #cbd5e1;
    }

    /* Pronunciation row with UK / US audio buttons */
    .pronunciations-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
        align-items: center;
    }

    .pronunciation-item {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 12px;
    }

    .dark-mode .pronunciation-item {
        background: #0f172a;
        border-color: #334155;
    }

    .pron-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #3b82f6;
        color: #fff;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .pron-ipa {
        font-size: 16px;
        color: #3b82f6;
        font-style: italic;
    }

    .dark-mode .pron-ipa {
        color: #60a5fa;
    }

    .pron-audio-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #3b82f6;
        font-size: 20px;
        display: flex;
        align-items: center;
        padding: 2px 4px;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .pron-audio-btn:hover {
        background: #dbeafe;
    }

    .dark-mode .pron-audio-btn {
        color: #60a5fa;
    }

    .dark-mode .pron-audio-btn:hover {
        background: #1e3a5f;
    }

    .pron-audio-btn.playing {
        color: #16a34a;
        animation: pulse-audio 0.6s ease infinite alternate;
    }

    @keyframes pulse-audio {
        from { opacity: 1; }
        to   { opacity: 0.5; }
    }

    .result-meaning-group {
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .dark-mode .result-meaning-group {
        border-bottom-color: #1e293b;
    }

    .result-meaning-group:last-child {
        border-bottom: none;
    }

    .def-entry {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .dark-mode .def-entry {
        border-bottom-color: #1e293b;
    }

    .def-entry:last-of-type {
        border-bottom: none;
    }

    .def-content {
        flex: 1;
        min-width: 0;
    }

    .btn-save-def {
        flex-shrink: 0;
        align-self: flex-start;
        padding: 6px 18px;
        background: #374151;
        color: #fff;
        border: none;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
        margin-top: 2px;
    }

    .btn-save-def:hover {
        background: #1f2937;
    }

    .btn-save-def:disabled {
        cursor: default;
    }

    .dark-mode .btn-save-def {
        background: #475569;
    }

    .dark-mode .btn-save-def:hover {
        background: #334155;
    }

    .def-num {
        font-weight: 700;
        color: #3b82f6;
        margin-right: 4px;
    }

    .result-synonyms,
    .result-antonyms {
        font-size: 13px;
        color: #64748b;
        margin-top: 6px;
    }

    .dark-mode .result-synonyms,
    .dark-mode .result-antonyms {
        color: #94a3b8;
    }

    .result-synonyms strong,
    .result-antonyms strong {
        color: #475569;
    }

    .dark-mode .result-synonyms strong,
    .dark-mode .result-antonyms strong {
        color: #94a3b8;
    }

    /* Back button at bottom of dictionary result */
    .dict-result-back-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin: 24px auto 4px;
        padding: 10px 32px;
        border: 2px solid #cbd5e1;
        border-radius: 50px;
        background: transparent;
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: border-color 0.2s, color 0.2s, background 0.2s;
    }
    .dict-result-back-btn:hover {
        border-color: #94a3b8;
        background: #f1f5f9;
        color: #1e293b;
    }
    .dark-mode .dict-result-back-btn {
        border-color: #475569;
        color: #94a3b8;
    }
    .dark-mode .dict-result-back-btn:hover {
        border-color: #64748b;
        background: #0f172a;
        color: #f1f5f9;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .col-lg-8,
        .col-lg-4 {
            margin-bottom: 30px;
        }
        
        .dictionary-container {
            padding: 0 10px;
        }
    }
    
    @media (min-width: 992px) {
        .dictionary-container {
            padding: 0 20px;
        }
        
        /* Add gap between columns */
        .row {
            margin-left: -10px;
            margin-right: -10px;
        }
        
        .col-lg-8,
        .col-lg-4 {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
    
    @media (min-width: 1200px) {
        .dictionary-container {
            padding: 0 40px;
        }
        
        .row {
            margin-left: -15px;
            margin-right: -15px;
        }
        
        .col-lg-8,
        .col-lg-4 {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
    
    @media (min-width: 1400px) {
        .dictionary-container {
            padding: 0 50px;
        }
    }

    /* ── Sidebar Flashcard Widget ─────────────────────────── */
    .sidebar-flashcard-widget { padding: 0; }
    .sidebar-card-display {
        background: #e2e8f0;
        border-radius: 10px;
        min-height: 140px;
        margin-bottom: 14px;
        position: relative;
        overflow: hidden;
    }
    .dark-mode .sidebar-card-display { background: #0f172a; }

    .sidebar-card-image {
        width: 100%;
        height: 140px;
        object-fit: cover;
        display: block;
        border-radius: 10px;
        transition: opacity 0.3s;
    }
    .sidebar-card-image.loading {
        opacity: 0;
    }
    /* placeholder shown while image loads or when no word */
    .sidebar-card-img-placeholder {
        width: 100%;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e2e8f0;
        border-radius: 10px;
        color: #94a3b8;
        font-size: 13px;
    }

    /* definition overlay that appears when card is turned */
    .sidebar-card-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.78);
        border-radius: 10px;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 14px;
        backdrop-filter: blur(2px);
    }
    .sidebar-card-overlay.show { display: flex; }
    .sidebar-card-display-text {
        font-size: 13px;
        color: #f1f5f9;
        text-align: center;
        line-height: 1.6;
    }
    .sidebar-card-meta { text-align: center; margin-bottom: 10px; }
    .sidebar-card-word {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }
    .dark-mode .sidebar-card-word { color: #f1f5f9; }
    .sidebar-card-pron {
        font-size: 13px;
        color: #64748b;
        font-style: italic;
        margin-top: 2px;
    }
    .dark-mode .sidebar-card-pron { color: #94a3b8; }
    .sidebar-card-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 8px;
    }
    .sidebar-nav-btn {
        background: none;
        border: none;
        font-size: 24px;
        color: #64748b;
        cursor: pointer;
        padding: 0 4px;
        line-height: 1;
        transition: color 0.2s;
    }
    .sidebar-nav-btn:hover { color: #3b82f6; }
    .sidebar-nav-btn:disabled { color: #cbd5e1; cursor: not-allowed; }
    .dark-mode .sidebar-nav-btn { color: #94a3b8; }
    .sidebar-card-counter { font-size: 13px; color: #64748b; }
    .dark-mode .sidebar-card-counter { color: #94a3b8; }

    /* ── Profile Card (same height as search section) ─────── */
    .dict-profile-card {
        position: relative;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 16px;
        cursor: pointer;
        padding: 24px 28px;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    .dark-mode .dict-profile-card {
        background: #1e293b;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .dict-profile-card__trigger {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        user-select: none;
        flex: 1;
    }
    .dict-profile-card__avatar {
        width: 72px;
        min-width: 72px;
        height: 72px;
        border-radius: 50%;
        border: 3px solid #e2e8f0;
        overflow: hidden;
        background: #e5e7eb;
        flex-shrink: 0;
    }
    .dict-profile-card__avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .dict-profile-card__info { text-align: center; }
    .dict-profile-card__name {
        font-size: 17px;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .dark-mode .dict-profile-card__name { color: #f1f5f9; }
    .dict-profile-card__band {
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 5px;
    }
    .dark-mode .dict-profile-card__band { color: #94a3b8; }
    /* Chevron rotates when open */
    .dict-profile-card__chevron {
        flex-shrink: 0;
        color: #94a3b8;
        transition: transform 0.25s ease;
    }
    .dict-profile-card.open .dict-profile-card__chevron {
        transform: rotate(180deg);
    }
    /* Dropdown – reuses .navbar-auth-user__dropdown styles from the global sheet */
    .dict-profile-card .navbar-auth-user__dropdown {
        /* override navbar default positioning for our taller card */
        top: calc(100% + 4px) !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
    }
    /* Click-open state overrides the hover-only :not(:hover) rule */
    .dict-profile-card.open > .navbar-auth-user__dropdown {
        visibility: visible !important;
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
</style>
@endpush

@section('content')
<div class="dictionary-container">
    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column: Search, Word Lists (66%) -->
        <div class="col-lg-8">
            <!-- Search Section -->
            <div class="search-section">
                <h3 class="search-title">{{ trans('panel.search_english') }}</h3>
                <div class="search-wrapper">
                    <input type="text" class="search-input" id="searchInput" placeholder="{{ trans('panel.search_the_word') }}">
                    <button class="search-btn" id="searchBtn">{{ trans('panel.search') }}</button>
                </div>
            </div>

            <!-- Dictionary Result Container (hidden by default) -->
            <div class="dictionary-result-container hidden" id="dictionaryResult">
                <!-- Search results will be displayed here -->
            </div>

            <!-- Academic Word Lists Section -->
            <div class="word-lists-section hidden" id="academicWordListsSection">
                <div class="section-header">
                    <h2 class="section-title">{{ trans('panel.essential_ielts_academic_word_list') }}</h2>
                    <button class="toggle-btn" id="toggleMyWordListBtn">{{ trans('panel.my_word_list') }}</button>
                </div>
                
                @foreach($academicWordLists as $wordList)
                <div class="word-list-card {{ $wordList['is_locked'] ? 'locked' : '' }}" 
                     data-list-id="{{ $wordList['id'] }}"
                     data-list-type="academic">
                    @if($wordList['is_locked'])
                        <i class="iconsax lock-icon" data-icon="lock-1"></i>
                    @endif
                    
                    <div class="word-list-header">
                        <h3 class="word-list-name">{{ $wordList['name'] }} (Band {{ $wordList['band_level'] }})</h3>
                        <span class="word-count-badge">Tổng số từ: {{ $wordList['word_count'] }} từ</span>
                    </div>
                    <p class="word-list-description">{{ $wordList['description'] }}</p>
                    
                    <!-- Expanded Content (hidden by default) -->
                    <div class="word-list-expanded" id="expanded-{{ $wordList['id'] }}">
                        <div class="filter-bar">
                            <input type="text" class="filter-input" placeholder="{{ trans('panel.search_the_word') }}">
                            <select class="filter-select" data-list-id="{{ $wordList['id'] }}" data-list-type="academic">
                                <option value="">Filter</option>
                                <option value="alphabet">A-Z</option>
                                <option value="learned">Đã học</option>
                            </select>
                        </div>
                        
                        <div class="words-container" id="words-{{ $wordList['id'] }}">
                            <!-- Words will be loaded here via AJAX -->
                        </div>
                        
                        <div class="action-bar">
                            <button class="action-btn btn-select-all">{{ trans('panel.select_all') }}</button>
                            <button class="action-btn btn-deselect-all">{{ trans('panel.deselect_all') }}</button>
                            <button class="action-btn btn-practice">{{ trans('panel.practice') }}</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- My Word List Section (default view) -->
            <div class="word-lists-section" id="myWordListSection">
                <div class="word-list-card" data-list-id="{{ $myWordList->id }}" data-list-type="my">
                    <div class="word-list-header">
                        <h3 class="word-list-name">{{ $myWordList->name }}</h3>
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                            <span class="word-count-badge">Tổng số từ: {{ $myWordList->word_count }} từ</span>
                            <button class="toggle-btn active" id="toggleAcademicListBtn">Academic Word List</button>
                        </div>
                    </div>
                    <p class="word-list-description">{{ $myWordList->description }}</p>
                    
                    <!-- Expanded Content (shown by default) -->
                    <div class="word-list-expanded show" id="expanded-my-{{ $myWordList->id }}">
                        <div class="filter-bar">
                            <input type="text" class="filter-input" placeholder="{{ trans('panel.search_the_word') }}">
                            <select class="filter-select" data-list-id="{{ $myWordList->id }}" data-list-type="my">
                                <option value="">Filter</option>
                                <option value="alphabet">A-Z</option>
                                <option value="learned">Đã học</option>
                            </select>
                        </div>
                        
                        <div class="words-container" id="words-my-{{ $myWordList->id }}">
                            <!-- Words will be loaded here via AJAX -->
                        </div>
                        
                        <div class="action-bar">
                            <button class="action-btn btn-select-all">{{ trans('panel.select_all') }}</button>
                            <button class="action-btn btn-deselect-all">{{ trans('panel.deselect_all') }}</button>
                            <button class="action-btn btn-delete">{{ trans('panel.delete') }}</button>
                            <button class="action-btn btn-practice">{{ trans('panel.practice') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Practice Mode Container (hidden by default) -->
            <div class="practice-mode-container hidden" id="practiceModeContainer">
                <div class="score-board">
                    <div class="score-item">
                        <div class="score-label">{{ strtoupper(trans('panel.correct')) }}: <span class="score-value correct" id="correctCount">0</span></div>
                    </div>
                    <div class="score-item">
                        <div class="score-label">{{ strtoupper(trans('panel.incorrect')) }}: <span class="score-value incorrect" id="incorrectCount">0</span></div>
                    </div>
                </div>
                
                <div class="question-text" id="questionText"></div>
                
                <div class="answers-grid" id="answersGrid">
                    <!-- Answers will be loaded here -->
                </div>
                
                <div class="practice-actions">
                    <button class="practice-btn btn-exit" id="exitPracticeBtn">{{ trans('panel.exit') }}</button>
                    <button class="practice-btn btn-next" id="nextQuestionBtn" disabled>{{ trans('panel.next') }}</button>
                </div>
            </div>
        </div>

        <!-- Right Column: Flashcards, Streak, Ranking (33%) -->
        <div class="col-lg-4">

            <!-- User Profile Card (same height as search section, with dropdown) -->
            <div class="navbar-auth-user dict-profile-card" id="dictProfileCard">

                <!-- Trigger -->
                <div class="dict-profile-card__trigger" id="dictProfileTrigger">
                    <div class="dict-profile-card__avatar">
                        <img src="{{ $authUser->getAvatar(72) }}" alt="{{ $authUser->full_name }}" class="img-cover rounded-circle">
                    </div>
                    <div class="dict-profile-card__info">
                        <div class="dict-profile-card__name">{{ strtoupper($authUser->full_name) }}</div>
                        <div class="dict-profile-card__band">BAND ESTIMATE: {{ number_format($userStats['band_estimate'], 1) }}</div>
                    </div>
                    <x-iconsax-lin-arrow-down class="dict-profile-card__chevron icons text-gray-500" width="14px" height="14px"/>
                </div>

                <!-- Dropdown – reuses existing navbar-auth-user__dropdown global styles -->
                <div class="navbar-auth-user__dropdown is-panel-nav" id="dictProfileDropdown">
                    <div class="d-flex align-items-center m-4 rounded-10 bg-gray p-12">
                        <div class="dropdown__user-avatar position-relative">
                            <img src="{{ $authUser->getAvatar(38) }}" class="img-cover rounded-circle" alt="{{ $authUser->full_name }}">
                            @if($authUser->verified)
                                <div class="dropdown__user-avatar__badge d-flex-center rounded-circle size-16 p-2 bg-primary">
                                    <x-tick-icon class="icons text-white"/>
                                </div>
                            @endif
                        </div>
                        <div class="ml-8 flex-1">
                            <div class="font-14 font-weight-bold text-dark">{{ $authUser->full_name }}</div>
                            <span class="mt-4 text-gray-500 font-12">{{ $authUser->role->caption }}</span>
                        </div>
                    </div>

                    <ul class="my-8">
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="{{ ($authUser->isAdmin()) ? getAdminPanelUrl('/') : '/panel' }}" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                                <x-iconsax-lin-chart-2 class="icons" width="24px" height="24px"/>
                                <span class="ml-8">{{ trans('panel.dashboard') }}</span>
                            </a>
                        </li>
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="/panel/notifications" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                                <x-iconsax-lin-notification class="icons" width="24px" height="24px"/>
                                <span class="ml-8">{{ trans('panel.notifications') }}</span>
                            </a>
                        </li>
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="/panel/courses/purchases" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                                <x-iconsax-lin-video-play class="icons" width="24px" height="24px"/>
                                <span class="ml-8">My Courses</span>
                            </a>
                        </li>
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="/panel/setting" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                                <x-iconsax-lin-profile class="icons" width="24px" height="24px"/>
                                <span class="ml-8">{{ trans('public.profile') }}</span>
                            </a>
                        </li>
                    </ul>

                    <div style="border-top:1px solid #f0f0f0;margin:4px 0;"></div>

                    <ul class="my-4">
                        <li class="navbar-auth-user__dropdown-item">
                            <a href="/logout" class="d-flex align-items-center w-100 px-16 py-8 bg-transparent">
                                <x-iconsax-lin-logout class="icons text-danger" width="24px" height="24px"/>
                                <span class="ml-8 text-danger">{{ trans('panel.log_out') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Flashcards Widget -->
            <div class="stats-widget">
                <h3 class="widget-title">{{ trans('panel.flashcards') }}</h3>
                <div class="sidebar-flashcard-widget" id="sidebarFlashcardWidget">

                    <!-- Card Display Area -->
                    <div class="sidebar-card-display" id="sidebarCardDisplay">
                        <!-- Word illustration image -->
                        <div class="sidebar-card-img-placeholder" id="sidebarCardPlaceholder"></div>
                        <img id="sidebarCardImage" class="sidebar-card-image" src="" alt="" style="display:none;">
                        <!-- Definition overlay (shown when card is turned) -->
                        <div class="sidebar-card-overlay" id="sidebarCardOverlay">
                            <span class="sidebar-card-display-text" id="sidebarCardDisplayText"></span>
                        </div>
                    </div>

                    <!-- Word + Pronunciation -->
                    <div class="sidebar-card-meta">
                        <div class="sidebar-card-word" id="sidebarCardWord">-</div>
                        <div class="sidebar-card-pron" id="sidebarCardPron"></div>
                    </div>

                    <!-- Turn Button -->
                    <div class="text-center" style="margin: 8px 0 10px;">
                        <button class="btn btn-outline-secondary btn-sm px-4" id="sidebarTurnBtn"
                                style="border-radius: 20px; min-width: 90px;">
                            {{ trans('panel.turn') }}
                        </button>
                    </div>

                    <!-- Navigation -->
                    <div class="sidebar-card-nav">
                        <button class="sidebar-nav-btn" id="sidebarPrevBtn" disabled>&#8249;</button>
                        <span class="sidebar-card-counter" id="sidebarCardCounter">Card 0 of 0</span>
                        <button class="sidebar-nav-btn" id="sidebarNextBtn" disabled>&#8250;</button>
                    </div>
                </div>
            </div>

            <!-- Streak Widget -->
            <div class="stats-widget">
                <h3 class="widget-title">{{ trans('panel.streak') }}</h3>
                <div class="streak-circles">
                    @for($i = 1; $i <= 7; $i++)
                        <div class="streak-circle {{ $i <= $userStats['streak'] ? 'active' : '' }}">{{ $i }}</div>
                    @endfor
                </div>
            </div>

            <!-- Ranking Widget -->
            <div class="stats-widget">
                <h3 class="widget-title">{{ trans('panel.ranking') }}</h3>
                <div class="ranking-placeholder">
                    <p>{{ trans('panel.ranking_hint') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
(function($) {
    "use strict";

    let currentWordListId = null;
    let currentWordListType = null;
    let currentQuestions = [];
    let currentWordData = null;
    let currentQuestionIndex = 0;
    let correctAnswers = 0;
    let incorrectAnswers = 0;
    let selectedAnswer = null;
    let practicedCorrectIds = new Set(); // flashcard IDs answered correctly in practice

    // ── Profile card: hover (CSS handles it) + click toggle (JS) ──────
    $('#dictProfileTrigger').on('click', function(e) {
        e.stopPropagation();
        $('#dictProfileCard').toggleClass('open');
    });
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#dictProfileCard').length) {
            $('#dictProfileCard').removeClass('open');
        }
    });

    // ── Equalise profile-card height to search-section height ──────
    function equalizeTopCards() {
        var h = $('.search-section').outerHeight();
        if (h) { $('#dictProfileCard').css('min-height', h + 'px'); }
    }
    $(window).on('load resize', equalizeTopCards);
    equalizeTopCards();

    // ── Sidebar Flashcard Widget ──────────────────────────────────────
    let sidebarCards = [];
    let sidebarIndex = 0;
    let sidebarFlipped = false;

    function loadSidebarFlashcards() {
        $.ajax({
            url: '/panel/dictionary/my-word-list',
            method: 'GET',
            success: function(response) {
                if (response.success && response.data && response.data.flashcards) {
                    sidebarCards = response.data.flashcards;
                    sidebarIndex = 0;
                    renderSidebarCard();
                }
            }
        });
    }

    function renderSidebarCard() {
        if (!sidebarCards.length) {
            $('#sidebarCardWord').text('-');
            $('#sidebarCardPron').text('');
            $('#sidebarCardDisplayText').text('');
            $('#sidebarCardOverlay').removeClass('show');
            $('#sidebarCardImage').hide();
            $('#sidebarCardPlaceholder').show();
            $('#sidebarCardCounter').text('Card 0 of 0');
            $('#sidebarPrevBtn, #sidebarNextBtn').prop('disabled', true);
            return;
        }
        sidebarFlipped = false;
        const card = sidebarCards[sidebarIndex];
        const posLabel = card.part_of_speech ? ' (' + card.part_of_speech + ')' : '';
        $('#sidebarCardWord').text((card.word || '') + posLabel);
        $('#sidebarCardPron').text(card.pronunciation ? '/' + card.pronunciation + '/' : '');
        $('#sidebarCardDisplayText').text('');
        $('#sidebarCardOverlay').removeClass('show');
        $('#sidebarCardCounter').text('Card ' + (sidebarIndex + 1) + ' of ' + sidebarCards.length);
        $('#sidebarPrevBtn').prop('disabled', sidebarIndex === 0);
        $('#sidebarNextBtn').prop('disabled', sidebarIndex === sidebarCards.length - 1);

        // Load word illustration
        if (card.word) {
            const word = encodeURIComponent(card.word.toLowerCase());
            const img = document.getElementById('sidebarCardImage');
            const placeholder = document.getElementById('sidebarCardPlaceholder');
            img.style.display = 'none';
            placeholder.style.display = 'flex';
            img.onload = function() {
                placeholder.style.display = 'none';
                img.style.display = 'block';
            };
            img.onerror = function() {
                img.style.display = 'none';
                placeholder.style.display = 'flex';
            };
            img.src = 'https://loremflickr.com/280/140/' + word + '?lock=' + sidebarIndex;
            img.alt = card.word;
        }
    }

    $('#sidebarTurnBtn').on('click', function() {
        if (!sidebarCards.length) return;
        const card = sidebarCards[sidebarIndex];
        if (!sidebarFlipped) {
            sidebarFlipped = true;
            $('#sidebarCardDisplayText').text(card.definition || '');
            $('#sidebarCardOverlay').addClass('show');
        } else {
            sidebarFlipped = false;
            $('#sidebarCardDisplayText').text('');
            $('#sidebarCardOverlay').removeClass('show');
        }
    });

    $('#sidebarNextBtn').on('click', function() {
        if (sidebarIndex < sidebarCards.length - 1) { sidebarIndex++; renderSidebarCard(); }
    });

    $('#sidebarPrevBtn').on('click', function() {
        if (sidebarIndex > 0) { sidebarIndex--; renderSidebarCard(); }
    });

    // ── Page init ────────────────────────────────────────────────────────

    // Auto-load My Word List on page init
    loadMyWordList();
    loadSidebarFlashcards();

    // Search functionality
    let isSearching = false; // Prevent duplicate searches

    $('#searchBtn').on('click', function(e) {
        e.preventDefault();
        performSearch();
    });

    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            performSearch();
        }
    });

    function performSearch() {
        // Prevent duplicate calls
        if (isSearching) {
            return;
        }

        const searchTerm = $('#searchInput').val().trim();
        
        if (searchTerm === '') {
            alert('Please enter a word to search');
            return;
        }

        isSearching = true;

        $.ajax({
            url: '/panel/dictionary/search-first',
            method: 'POST',
            data: {
                query: searchTerm,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#searchBtn').prop('disabled', true).text('{{ trans('panel.searching') }}...');
            },
            success: function(response) {
                if (response.success && response.data) {
                    displaySearchResult(response.data);
                    $('#dictionaryResult').removeClass('hidden');
                } else {
                    alert('{{ trans('panel.word_not_found') }}: ' + searchTerm);
                }
            },
            error: function(xhr, status, error) {
                console.error('Search error:', xhr.responseText);
                
                let errorMessage = '{{ trans('panel.search_error') }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            },
            complete: function() {
                $('#searchBtn').prop('disabled', false).text('{{ trans('panel.search') }}');
                isSearching = false;
            }
        });
    }

    function displaySearchResult(data) {
        console.log('Dictionary data:', data); // Debug log

        // Word heading
        let html = `<div class="result-word">${data.headword || data.word || 'Unknown'}</div>`;

        // ── Pronunciations: UK / US buttons with audio ──────────────────
        if (data.pronunciations && data.pronunciations.length > 0) {
            html += '<div class="pronunciations-row">';
            const wordForSpeech = data.headword || data.word || '';

            data.pronunciations.forEach(function(pron) {
                let label = pron.label || '';
                let ipa   = pron.ipa   || pron.text || '';
                let audio = pron.audio || '';

                // Fix protocol-relative URL on client side too
                if (audio && audio.startsWith('//')) {
                    audio = 'https:' + audio;
                }

                html += '<div class="pronunciation-item">';
                if (label) {
                    html += `<span class="pron-label">${label}</span>`;
                }
                if (ipa) {
                    html += `<span class="pron-ipa">/${ipa}/</span>`;
                }
                // Always show audio button (fallback to TTS if no URL)
                html += `<button class="pron-audio-btn" data-audio="${audio}" data-word="${wordForSpeech}" title="Play ${label} pronunciation" onclick="playAudio(this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                        <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                    </svg>
                </button>`;
                html += '</div>';
            });

            html += '</div>';
        }

        // ── Meanings ─────────────────────────────────────────────────────
        const wordLabel = data.headword || data.word || '';
        function escAttr(s) { return String(s || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;'); }

        if (data.meanings && data.meanings.length > 0) {
            data.meanings.forEach(function(meaning) {
                html += '<div class="result-meaning-group">';

                if (meaning.partOfSpeech) {
                    html += `<div class="result-pos">${meaning.partOfSpeech}</div>`;
                }

                if (meaning.definitions && meaning.definitions.length > 0) {
                    // Show max 4 definitions per part-of-speech, each with its own Save button
                    meaning.definitions.slice(0, 4).forEach(function(defObj, i) {
                        html += '<div class="def-entry">';
                        html += '<div class="def-content">';
                        if (defObj.definition) {
                            html += `<div class="result-definition"><span class="def-num">${i + 1}.</span> ${defObj.definition}</div>`;
                        }
                        if (defObj.example) {
                            html += `<div class="result-example">"${defObj.example}"</div>`;
                        }
                        if (defObj.synonyms && defObj.synonyms.length > 0) {
                            html += `<div class="result-synonyms"><strong>Synonyms:</strong> ${defObj.synonyms.join(', ')}</div>`;
                        }
                        if (defObj.antonyms && defObj.antonyms.length > 0) {
                            html += `<div class="result-antonyms"><strong>Antonyms:</strong> ${defObj.antonyms.join(', ')}</div>`;
                        }
                        html += '</div>'; // .def-content
                        html += `<button class="btn-save-def save-definition-btn" data-word="${escAttr(wordLabel)}" data-definition="${escAttr(defObj.definition)}" data-pos="${escAttr(meaning.partOfSpeech)}" data-example="${escAttr(defObj.example)}">Save</button>`;
                        html += '</div>'; // .def-entry
                    });
                }

                // Part-of-speech level synonyms/antonyms
                if (meaning.synonyms && meaning.synonyms.length > 0) {
                    html += `<div class="result-synonyms"><strong>Synonyms:</strong> ${meaning.synonyms.join(', ')}</div>`;
                }
                if (meaning.antonyms && meaning.antonyms.length > 0) {
                    html += `<div class="result-antonyms"><strong>Antonyms:</strong> ${meaning.antonyms.join(', ')}</div>`;
                }

                html += '</div>';
            });
        }
        // Fallback to old senses structure
        else if (data.senses && data.senses.length > 0) {
            data.senses.forEach(function(sense) {
                if (sense.part_of_speech) {
                    html += `<div class="result-pos">${sense.part_of_speech}</div>`;
                }
                if (sense.definitions && sense.definitions.length > 0) {
                    sense.definitions.forEach(function(definition) {
                        html += '<div class="def-entry">';
                        html += '<div class="def-content">';
                        html += `<div class="result-definition">${definition}</div>`;
                        html += '</div>';
                        html += `<button class="btn-save-def save-definition-btn" data-word="${escAttr(wordLabel)}" data-definition="${escAttr(definition)}" data-pos="${escAttr(sense.part_of_speech)}" data-example="">Save</button>`;
                        html += '</div>';
                    });
                }
                if (sense.examples && sense.examples.length > 0) {
                    sense.examples.forEach(function(example) {
                        html += `<div class="result-example">"${example}"</div>`;
                    });
                }
            });
        }

        // Back button
        html += `<div class="text-center mt-8 mb-4">
            <button class="dict-result-back-btn" id="dictResultBackBtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                {{ trans('panel.back') }}
            </button>
        </div>`;

        // Store current word data for use by action buttons
        currentWordData = data;

        $('#dictionaryResult').html(html);

        // Back button handler
        $('#dictResultBackBtn').on('click', function() {
            $('#dictionaryResult').addClass('hidden').html('');
        });

        // Re-init icon rendering if iconsax is available
        if (typeof iconsax !== 'undefined') { try { iconsax.replace(); } catch(e) {} }
    }

    // Play pronunciation audio
    function playAudio(btnEl) {
        let url = btnEl ? btnEl.getAttribute('data-audio') : null;
        const wordText = btnEl ? btnEl.getAttribute('data-word') : null;

        // Fix protocol-relative URLs (e.g. //api.dictionaryapi.dev/...)
        if (url && url.startsWith('//')) {
            url = 'https:' + url;
        }

        console.log('Playing audio:', url);

        // Mark button as playing
        document.querySelectorAll('.pron-audio-btn.playing').forEach(function(b) {
            b.classList.remove('playing');
        });
        btnEl.classList.add('playing');

        if (url) {
            // Use audio file from API
            const audio = new Audio(url);
            audio.volume = 1.0;
            const playPromise = audio.play();
            if (playPromise !== undefined) {
                playPromise.catch(function(e) {
                    console.warn('Audio file failed, trying TTS fallback:', e, 'URL:', url);
                    // Fallback to browser TTS
                    speakWord(wordText || document.querySelector('.result-word')?.textContent, btnEl);
                });
            }
            audio.addEventListener('ended', function() {
                btnEl.classList.remove('playing');
            });
        } else if (wordText || document.querySelector('.result-word')) {
            // No audio URL — use browser speech synthesis
            speakWord(wordText || document.querySelector('.result-word').textContent, btnEl);
        } else {
            btnEl.classList.remove('playing');
        }
    }

    // Browser speech synthesis fallback
    function speakWord(word, btnEl) {
        if (!word || !window.speechSynthesis) {
            if (btnEl) btnEl.classList.remove('playing');
            return;
        }
        window.speechSynthesis.cancel();
        const utter = new SpeechSynthesisUtterance(word.trim());
        utter.lang = 'en-US';
        utter.rate = 0.9;
        utter.onend = function() { if (btnEl) btnEl.classList.remove('playing'); };
        utter.onerror = function() { if (btnEl) btnEl.classList.remove('playing'); };
        window.speechSynthesis.speak(utter);
    }

    // Save individual definition to My Word List
    $(document).on('click', '.save-definition-btn', function() {
        const $btn = $(this);
        const word         = $btn.data('word')       || '';
        const definition   = $btn.data('definition') || '';
        const partOfSpeech = $btn.data('pos')        || '';
        const example      = $btn.data('example')    || '';
        let pronunciation  = '';

        if (!word) {
            alert('{{ trans('panel.no_word_selected') }}');
            return;
        }
        if (!definition) {
            alert('{{ trans('panel.word_definition_not_found') }}');
            return;
        }

        if (currentWordData && currentWordData.pronunciations && currentWordData.pronunciations.length > 0) {
            pronunciation = currentWordData.pronunciations[0].ipa || currentWordData.pronunciations[0].text || '';
        }

        $btn.prop('disabled', true).text('{{ trans('panel.adding') }}...');

        $.ajax({
            url: '/panel/dictionary/my-word-list/add-word',
            method: 'POST',
            data: {
                word: word,
                definition: definition,
                part_of_speech: partOfSpeech,
                example: example,
                pronunciation: pronunciation,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.success) {
                    $btn.text('{{ trans('panel.added') }} ✓').css({'background': '#22c55e', 'color': '#fff'}).prop('disabled', true);
                    // Refresh word list if currently expanded
                    if ($('#words-my-{{ $myWordList->id }}').closest('.word-list-expanded').hasClass('show')) {
                        loadMyWordList();
                    }
                } else {
                    if (res.message && res.message.toLowerCase().indexOf('already') !== -1) {
                        $btn.text('{{ trans('panel.already_in_list') }}').css({'background': '#94a3b8', 'color': '#fff'}).prop('disabled', true);
                    } else {
                        alert(res.message || '{{ trans('panel.failed_to_add_word') }}');
                        $btn.prop('disabled', false).text('Save').removeAttr('style');
                    }
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '{{ trans('panel.failed_to_add_word') }}';
                if (msg.toLowerCase().indexOf('already') !== -1) {
                    $btn.text('{{ trans('panel.already_in_list') }}').css({'background': '#94a3b8', 'color': '#fff'}).prop('disabled', true);
                } else {
                    alert(msg);
                    $btn.prop('disabled', false).text('Save').removeAttr('style');
                }
            }
        });
    });

    // Toggle between Academic and My Word List
    $('#toggleMyWordListBtn').on('click', function() {
        $('#academicWordListsSection').addClass('hidden');
        $('#myWordListSection').removeClass('hidden');
        $(this).removeClass('active');
        $('#toggleAcademicListBtn').addClass('active');

        // Auto-expand My Word List
        const $myCard = $('#myWordListSection .word-list-card');
        const $expanded = $myCard.find('.word-list-expanded');
        if (!$expanded.hasClass('show')) {
            $expanded.addClass('show');
            loadMyWordList();
        }
    });

    $('#toggleAcademicListBtn').on('click', function() {
        $('#myWordListSection').addClass('hidden');
        $('#academicWordListsSection').removeClass('hidden');
        $(this).removeClass('active');
        $('#toggleMyWordListBtn').addClass('active');
    });

    // Click on word list HEADER to expand/collapse (not the whole card)
    $(document).on('click', '.word-list-card .word-list-header, .word-list-card .word-list-description', function(e) {
        const $card = $(this).closest('.word-list-card');

        if ($card.hasClass('locked')) {
            alert('{{ trans('panel.word_list_locked_hint') }}');
            return;
        }

        let listId   = $card.data('list-id');
        let listType = $card.data('list-type');
        let expandedSection = $card.find('.word-list-expanded');

        if (expandedSection.hasClass('show')) {
            expandedSection.removeClass('show');
        } else {
            $('.word-list-expanded').removeClass('show');
            expandedSection.addClass('show');
            if (listType === 'academic') {
                loadAcademicWordList(listId);
            } else {
                loadMyWordList();
            }
        }
    });

    // Prevent any click inside the expanded content from bubbling to the card
    $(document).on('click', '.word-list-expanded', function(e) {
        e.stopPropagation();
    });

    // Load Academic Word List
    function loadAcademicWordList(listId) {
        $.ajax({
            url: '/panel/dictionary/academic-word-lists/' + listId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    renderWords(response.data.words, listId, 'academic');
                }
            },
            error: function(error) {
                console.error('Error loading word list:', error);
            }
        });
    }

    // Load My Word List
    function loadMyWordList() {
        $.ajax({
            url: '/panel/dictionary/my-word-list',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    renderWords(response.data.flashcards, response.data.id, 'my');
                }
            },
            error: function(error) {
                console.error('Error loading my word list:', error);
            }
        });
    }

    // Render words in the list
    function renderWords(words, listId, listType) {
        let container = listType === 'academic' ? 
            $('#words-' + listId) : 
            $('#words-my-' + listId);
        
        container.empty();

        words.forEach(function(word) {
            let pronunciation = word.pronunciation ? `<span class="word-pronunciation">/${word.pronunciation}/</span>` : '';
            let showTick = word.is_learned || (listType === 'my' && practicedCorrectIds.has(word.id));
            let wordHtml = `
                <div class="word-item" data-word-id="${word.id}" data-word="${word.word}">
                    <input type="checkbox" class="word-checkbox" data-word-id="${word.id}">
                    <div class="word-content">
                        <div class="word-title-row">
                            <span class="word-text">${word.word}</span>
                            ${pronunciation}
                        </div>
                        <div class="word-definition">${word.definition}</div>
                    </div>
                    <span class="learned-badge ${showTick ? 'show' : ''}"
                          data-word-id="${word.id}"
                          title="{{ trans('panel.mark_as_learned') }}">&#10003;</span>
                </div>
            `;
            container.append(wordHtml);
        });

        currentWordListId = listId;
        currentWordListType = listType;
    }

    // Mark word as learned
    $(document).on('click', '.learned-badge', function(e) {
        e.stopPropagation();
        
        let wordId = $(this).data('word-id');
        
        $.ajax({
            url: '/panel/dictionary/academic-word-lists/mark-learned',
            method: 'POST',
            data: {
                word_id: wordId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(`.learned-badge[data-word-id="${wordId}"]`).addClass('show');
                }
            }
        });
    });

    // Select All
    $(document).on('click', '.btn-select-all', function(e) {
        e.stopPropagation();
        $(this).closest('.word-list-expanded').find('.word-checkbox').prop('checked', true);
    });

    // Deselect All
    $(document).on('click', '.btn-deselect-all', function(e) {
        e.stopPropagation();
        $(this).closest('.word-list-expanded').find('.word-checkbox').prop('checked', false);
    });

    // Delete words from My Word List
    $(document).on('click', '.btn-delete', function(e) {
        e.stopPropagation();
        
        let selectedIds = [];
        $(this).closest('.word-list-expanded').find('.word-checkbox:checked').each(function() {
            selectedIds.push($(this).data('word-id'));
        });

        if (selectedIds.length === 0) {
            alert('{{ trans('panel.please_select_words_to_delete') }}');
            return;
        }

        if (!confirm('{{ trans('panel.confirm_bulk_delete_words') }}')) {
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).text('...');

        $.ajax({
            url: '/panel/dictionary/my-word-list/bulk-delete',
            method: 'POST',
            data: {
                flashcard_ids: selectedIds,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Remove deleted word items from DOM immediately
                    selectedIds.forEach(function(id) {
                        $(`.word-item[data-word-id="${id}"]`).fadeOut(200, function() { $(this).remove(); });
                    });
                    // Refresh to update word count badge
                    setTimeout(function() { loadMyWordList(); }, 300);
                } else {
                    alert(response.message || '{{ trans('panel.failed_to_delete_word') }}');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : '{{ trans('panel.failed_to_delete_word') }}';
                alert(msg);
            },
            complete: function() {
                $btn.prop('disabled', false).text('{{ trans('panel.delete') }}');
            }
        });
    });

    // Start Practice
    $(document).on('click', '.btn-practice', function(e) {
        e.stopPropagation();
        
        let selectedIds = [];
        $('.word-checkbox:checked').each(function() {
            selectedIds.push($(this).data('word-id'));
        });

        if (selectedIds.length === 0) {
            alert('{{ trans('panel.please_select_words_to_practice') }}');
            return;
        }

        startPractice(selectedIds);
    });

    // Start Practice Session
    function startPractice(wordIds) {
        let url = currentWordListType === 'academic' ? 
            '/panel/dictionary/practice/start' : 
            '/panel/dictionary/practice/start-my-word-list';
        
        let data = {
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        if (currentWordListType === 'academic') {
            data.word_list_id = currentWordListId;
            data.word_ids = wordIds;
        } else {
            data.flashcard_ids = wordIds;
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    currentQuestions = response.data.questions;
                    currentQuestionIndex = 0;
                    correctAnswers = 0;
                    incorrectAnswers = 0;
                    
                    // Hide word lists and show practice mode
                    $('#academicWordListsSection, #myWordListSection').addClass('hidden');
                    $('#practiceModeContainer').removeClass('hidden');
                    
                    showQuestion();
                }
            },
            error: function(error) {
                console.error('Error starting practice:', error);
                alert('{{ trans('panel.failed_to_start_practice') }}');
            }
        });
    }

    // Show Question
    function showQuestion() {
        if (currentQuestionIndex >= currentQuestions.length) {
            // Practice finished – show results
            const total = currentQuestions.length;
            $('#questionText').html(
                `<div style="text-align:center;padding:20px 0;">
                    <div style="font-size:48px;margin-bottom:10px;">🎉</div>
                    <div style="font-size:22px;font-weight:700;margin-bottom:8px;">{{ trans('panel.practice') }} Complete!</div>
                    <div style="font-size:16px;color:#64748b;">${correctAnswers} / ${total} correct</div>
                </div>`
            );
            $('#answersGrid').html('');
            $('#nextQuestionBtn').prop('disabled', true);
            return;
        }

        let question = currentQuestions[currentQuestionIndex];
        selectedAnswer = null;
        
        $('#questionText').text(question.question);
        $('#correctCount').text(correctAnswers);
        $('#incorrectCount').text(incorrectAnswers);
        $('#nextQuestionBtn').prop('disabled', true);
        
        let answersHtml = '';
        question.answers.forEach(function(answer) {
            const answerWord = (typeof answer === 'object' && answer !== null) ? answer.word : answer;
            const answerPron = (typeof answer === 'object' && answer !== null && answer.pronunciation) ? answer.pronunciation : '';
            const pronHtml = answerPron ? `<div class="answer-pronunciation">/${answerPron}/</div>` : '';
            answersHtml += `
                <div class="answer-card" data-answer="${answerWord}">
                    <div class="answer-word">${answerWord}</div>
                    ${pronHtml}
                </div>
            `;
        });
        
        $('#answersGrid').html(answersHtml);
    }

    // Select Answer
    $(document).on('click', '.answer-card', function() {
        if (selectedAnswer !== null) return; // Already answered
        
        selectedAnswer = $(this).data('answer');
        let question = currentQuestions[currentQuestionIndex];
        let isCorrect = selectedAnswer === question.correct_answer;
        
        $(this).addClass(isCorrect ? 'correct' : 'incorrect');
        
        // Show correct answer if wrong
        if (!isCorrect) {
            $(`.answer-card[data-answer="${question.correct_answer}"]`).addClass('correct');
        }
        
        // Update scores
        if (isCorrect) {
            correctAnswers++;
            // Show the green tick on the word item in My Word List and remember it
            if (currentWordListType === 'my' && question.flashcard_id) {
                practicedCorrectIds.add(question.flashcard_id);
                $(`.learned-badge[data-word-id="${question.flashcard_id}"]`).addClass('show');
            }
        } else {
            incorrectAnswers++;
        }
        
        $('#correctCount').text(correctAnswers);
        $('#incorrectCount').text(incorrectAnswers);
        $('#nextQuestionBtn').prop('disabled', false);
        
        // Submit answer to server
        let wordKey = currentWordListType === 'academic' ? 'word_id' : 'flashcard_id';
        let submitData = {
            [wordKey]: question[wordKey],
            selected_answer: selectedAnswer,
            correct_answer: question.correct_answer,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: '/panel/dictionary/practice/submit-answer',
            method: 'POST',
            data: submitData
        });
    });

    // Next Question
    $('#nextQuestionBtn').on('click', function() {
        currentQuestionIndex++;
        showQuestion();
    });

    // Exit Practice
    $('#exitPracticeBtn').on('click', function() {
        exitPractice();
    });

    function exitPractice() {
        $('#practiceModeContainer').addClass('hidden');
        
        if (currentWordListType === 'academic') {
            $('#academicWordListsSection').removeClass('hidden');
        } else {
            $('#myWordListSection').removeClass('hidden');
        }
        
        // Reload the word list to show updated learned/tick status
        if (currentWordListType === 'academic') {
            loadAcademicWordList(currentWordListId);
            practicedCorrectIds.clear();
        } else {
            loadMyWordList(); // renderWords will re-apply ticks from practicedCorrectIds
            // Clear after a brief delay so renderWords finishes first
            setTimeout(function() { practicedCorrectIds.clear(); }, 1500);
        }
    }

    // Filter select handler (replaces old .filter-btn click)
    $(document).on('change', '.filter-select', function(e) {
        e.stopPropagation();
        const filter = $(this).val();
        const container = $(this).closest('.word-list-expanded').find('.words-container');
        const words = container.find('.word-item');

        // Reset visibility first
        words.show();

        if (filter === 'alphabet') {
            const sorted = words.get().sort(function(a, b) {
                return $(a).data('word').localeCompare($(b).data('word'));
            });
            container.append(sorted);
        } else if (filter === 'learned') {
            words.each(function() {
                if (!$(this).find('.learned-badge').hasClass('show')) $(this).hide();
            });
        }
    });

    // Filter functionality (old .filter-btn — kept for backward compat)

    // Search within word list
    $(document).on('keyup', '.filter-input', function(e) {
        e.stopPropagation();
        
        let searchTerm = $(this).val().toLowerCase();
        let container = $(this).closest('.word-list-expanded').find('.words-container');
        
        container.find('.word-item').each(function() {
            let word = $(this).find('.word-text').text().toLowerCase();
            if (word.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

})(jQuery);
</script>
@endpush
