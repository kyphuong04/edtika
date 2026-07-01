@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .dictionary-container {
        max-width: 100%;
        margin: 0;
    }
    
    .user-stats-card {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
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
        background: #511D99;
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
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 14px 18px;
        margin-bottom: 16px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }
    .dark-mode .dict-profile-card {
        background: #1e293b;
        box-shadow: 0 6px 18px rgba(0,0,0,0.18);
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
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transform: translateY(8px);
        transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
        padding-bottom: 6px;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
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
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
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
        border-color: #511D99;
    }
    
    .dark-mode .search-input {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    .search-btn {
        padding: 12px 30px;
        background: #511D99;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .search-btn:hover {
        background: #421670;
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
        background: rgba(81, 29, 153, 0.08);
        color: #511D99;
        border: 1px solid rgba(81, 29, 153, 0.16);
    }
    
    .dark-mode .toggle-btn {
        background: #0f172a;
        color: #94a3b8;
    }
    
    .word-list-card {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        cursor: default;
        transition: all 0.3s;
        position: relative;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }
    
    .word-list-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
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
        color: #511D99;
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
        background: rgba(81, 29, 153, 0.08);
        color: #511D99;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .dark-mode .word-count-badge {
        background: #1e293b;
        color: #c4b5fd;
    }
    
    .word-list-description {
        color: #64748b;
        font-size: 14px;
        margin: 0;
        background: #ffffff;
        border-radius: 14px;
        padding: 12px 14px;
    }
    
    .dark-mode .word-list-description {
        color: #94a3b8;
    }
    
    .word-list-expanded {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        margin-top: 15px;
        display: none;
        border: 1px solid rgba(81, 29, 153, 0.10);
    }

    .dark-mode .word-list-expanded {
        background: #1e293b;
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
        background: rgba(255, 255, 255, 0.5);
        color: #1e293b;
        outline: none;
        transition: border-color 0.2s;
    }
    .filter-input:focus { border-color: #511D99; }

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
        background: rgba(255, 255, 255, 0.5);
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
    .filter-select:focus { border-color: #511D99; }
    .dark-mode .filter-select {
        background-color: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }

    /* Keep long vocabulary lists inside a scrollable panel */
    .words-container {
        max-height: min(58vh, 520px);
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 6px;
    }

    .words-container::-webkit-scrollbar {
        width: 8px;
    }

    .words-container::-webkit-scrollbar-track {
        background: #eef2ff;
        border-radius: 10px;
    }

    .words-container::-webkit-scrollbar-thumb {
        background: rgba(81, 29, 153, 0.35);
        border-radius: 10px;
    }

    .words-container::-webkit-scrollbar-thumb:hover {
        background: rgba(81, 29, 153, 0.5);
    }

    /* ── Word item card ── */
    .word-item {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 16px;
        padding: 14px 40px 14px 14px; /* right padding leaves room for badge */
        margin-bottom: 10px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        position: relative;
        transition: border-color 0.2s, box-shadow 0.2s;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }
    .word-item:hover {
        border-color: rgba(81, 29, 153, 0.18);
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    .dark-mode .word-item {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.18);
    }
    .dark-mode .word-item:hover { border-color: #c4b5fd; }

    .word-checkbox {
        width: 18px;
        height: 18px;
        margin-top: 3px;   /* align with first text line */
        flex-shrink: 0;
        cursor: pointer;
        accent-color: #511D99;
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
        border: 1.5px solid rgba(81, 29, 153, 0.16);
        color: #511D99;
    }
    .btn-select-all:hover, .btn-deselect-all:hover {
        border-color: rgba(81, 29, 153, 0.22);
        background: rgba(81, 29, 153, 0.06);
    }
    .btn-delete {
        border: 1.5px solid #dc2626;
        color: #dc2626;
    }
    .btn-delete:hover { background: #fee2e2; }
    .btn-practice {
        border: 1.5px solid #511D99;
        color: #511D99;
    }
    .btn-practice:hover { background: rgba(81, 29, 153, 0.08); }

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
        border-color: #c4b5fd;
        color: #c4b5fd;
    }
    .dark-mode .btn-practice:hover { background: #1e1b4b; }
    
    .stats-widget {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
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
        background: #ffffff;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid rgba(81, 29, 153, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        color: #6b7280;
        transition: all 0.3s;
    }
    
    .streak-circle.active {
        background: #511D99;
        border-color: #511D99;
        color: white;
        box-shadow: 0 4px 12px rgba(81, 29, 153, 0.20);
    }
    
    .dark-mode .streak-circle {
        background: #1e293b;
        border-color: #334155;
    }
    
    .dark-mode .streak-circle.active {
        background: #511D99;
        border-color: #511D99;
    }
    
    .ranking-placeholder {
        text-align: center;
        padding: 40px 20px;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid rgba(81, 29, 153, 0.10);
    }
    
    .dark-mode .ranking-placeholder {
        background: #1e293b;
        color: #64748b;
    }

    .flashcard-preview {
        text-align: center;
    }

    .flashcard-info {
        padding: 20px;
        background: #ffffff;
        border-radius: 14px;
        margin-bottom: 15px;
        border: 1px solid rgba(81, 29, 153, 0.10);
    }

    .dark-mode .flashcard-info {
        background: #1e293b;
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
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 40px 30px;
        margin-bottom: 30px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
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
        border: 2px solid rgba(81, 29, 153, 0.16);
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
        color: #511D99;
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
        background: #ffffff;
        border: 2px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 18px 14px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }
    
    .answer-card:hover {
        border-color: #511D99;
        transform: scale(1.02);
    }
    
    .answer-card.selected {
        border-color: #511D99;
        background: rgba(81, 29, 153, 0.08);
    }
    
    .answer-card.correct {
        border-color: #10b981;
        background: #f0fdf4;
    }
    
    .answer-card.incorrect {
        border-color: #ef4444;
        background: #fff1f2;
    }
    
    .dark-mode .answer-card {
        background: #1e293b;
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
        color: #511D99;
        font-style: italic;
    }
    
    .dark-mode .answer-pronunciation {
        color: #511D99;
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
        color: #511D99;
        border: 2px solid #511D99;
    }
    
    .btn-next:hover {
        background: #511D99;
        border-color: #511D99;
        color: white;
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
        background: #511D99;
        border-color: #511D99;
        color: white;
    }
    
    .dark-mode .btn-next {
        background: transparent;
        color: #511D99;
        border-color: #511D99;
    }
    
    .dark-mode .btn-next:hover {
        background: #511D99;
        border-color: #511D99;
        color: white;
    }
    
    .hidden {
        display: none !important;
    }

    /* Dictionary Result Styles */
    .dictionary-result-container {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
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
        color: #511D99;
        font-style: italic;
        margin-bottom: 20px;
    }

    .dark-mode .result-pronunciation {
        color: #c4b5fd;
    }

    .result-pos {
        display: inline-block;
        background: rgba(81, 29, 153, 0.08);
        color: #511D99;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .dark-mode .result-pos {
        background: #1e1b4b;
        color: #c4b5fd;
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
        background: #511D99;
        color: white;
    }

    .result-btn-primary:hover {
        background: #421670;
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
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 12px;
        padding: 6px 12px;
    }

    .dark-mode .pronunciation-item {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.18);
    }

    .pron-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #511D99;
        color: #fff;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .pron-ipa {
        font-size: 16px;
        color: #511D99;
        font-style: italic;
    }

    .dark-mode .pron-ipa {
        color: #c4b5fd;
    }

    .pron-audio-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #511D99;
        font-size: 20px;
        display: flex;
        align-items: center;
        padding: 2px 4px;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .pron-audio-btn:hover {
        background: rgba(81, 29, 153, 0.08);
    }

    .dark-mode .pron-audio-btn {
        color: #c4b5fd;
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
        color: #511D99;
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

        .words-container {
            max-height: 46vh;
        }
    }
    
    @media (min-width: 992px) {
        
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

    /* ── Sidebar Flashcard Widget ─────────────────────────── */
    .stats-widget {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 22px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }
    .dark-mode .stats-widget {
        background: #1e293b;
    }
    .sidebar-flashcard-widget { padding: 0; }
    .sidebar-card-display {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 16px;
        min-height: 140px;
        margin-bottom: 14px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }
    .dark-mode .sidebar-card-display { background: #1e293b; }

    .sidebar-card-image {
        width: 100%;
        height: 140px;
        object-fit: cover;
        display: block;
        border-radius: 16px;
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
        background: #ffffff;
        border-radius: 16px;
        color: #94a3b8;
        font-size: 13px;
    }

    /* definition overlay that appears when card is turned */
    .sidebar-card-overlay {
        position: absolute;
        inset: 0;
        background: rgba(81, 29, 153, 0.72);
        border-radius: 16px;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 14px;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }
    .sidebar-card-overlay.show { display: flex; }
    .sidebar-card-display-text {
        font-size: 13px;
        color: #511D99;
        text-align: left;
        line-height: 1.45;
        white-space: normal;
        width: 100%;
    }
    .sidebar-card-display-text strong {
        color: #511D99;
        font-weight: 700;
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
    .sidebar-nav-btn:hover { color: #511D99; }
    .sidebar-nav-btn:disabled { color: #cbd5e1; cursor: not-allowed; }
    .dark-mode .sidebar-nav-btn { color: #94a3b8; }
    .sidebar-card-counter { font-size: 13px; color: #64748b; }
    .dark-mode .sidebar-card-counter { color: #94a3b8; }

    /* ── Profile Card (same height as search section) ─────── */
    .dict-profile-card {
        position: relative;
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        margin-bottom: 16px;
        cursor: pointer;
        padding: 24px 28px;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
    }
    .dark-mode .dict-profile-card {
        background: #1e293b;
        box-shadow: 0 6px 18px rgba(0,0,0,0.18);
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
        border: 3px solid rgba(81, 29, 153, 0.10);
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
    @if(!empty($canManageBundleVocabulary) && $canManageBundleVocabulary)
        <div class="d-flex align-items-center justify-content-end mb-16">
            <a href="{{ url('/panel/dictionary/bundle-vocabulary/manage') }}" class="btn btn-outline-primary btn-sm">
                {{ trans('panel.bundle_vocabulary_library') }}
                @if(!empty($bundleVocabularyPendingCount) && $bundleVocabularyPendingCount > 0)
                    <span class="badge badge-warning ml-8">{{ $bundleVocabularyPendingCount }}</span>
                @endif
            </a>
        </div>
    @endif

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
                     data-source-id="{{ $wordList['source_id'] }}"
                     data-source-type="{{ $wordList['source_type'] }}"
                     data-list-type="academic">
                    @if($wordList['is_locked'])
                        <i class="iconsax lock-icon" data-icon="lock-1"></i>
                    @endif
                    
                    <div class="word-list-header">
                        <h3 class="word-list-name">
                            {{ $wordList['name'] }}
                            @if($wordList['source_type'] === 'academic')
                                (Band {{ $wordList['band_level'] }})
                            @else
                                (Bundle)
                            @endif
                        </h3>
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
                            <button class="action-btn btn-practice btn-flashcard">{{ 'Luyện tập bằng Flashcard' }}</button>
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
                            <button class="action-btn btn-practice btn-flashcard">{{ 'Luyện tập bằng Flashcard' }}</button>
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

            <!-- Flashcard Practice Container (hidden by default) -->
            <div class="practice-mode-container hidden" id="flashcardPracticeContainer" style="text-align:center;">
                <div style="max-width:760px;margin:0 auto;padding:20px;">

                    <div id="flashcardCard" style="position:relative;background:#fff;border:1px solid rgba(81,29,153,0.08);border-radius:18px;padding:30px 26px;min-height:420px;display:flex;flex-direction:column;justify-content:flex-start;align-items:center;">

                        {{-- ── FRONT SIDE LABEL ───────────────────────── --}}
                        <div id="flashcardFrontLabel" style="display:inline-flex;align-items:center;justify-content:center;padding:7px 22px;border-radius:999px;background:#511D99;color:#fff;font-weight:800;font-size:13px;letter-spacing:0.06em;margin-bottom:18px;">MẶT TRƯỚC</div>
                        {{-- ── BACK SIDE LABEL ────────────────────────── --}}
                        <div id="flashcardBackLabel" style="display:none;align-items:center;justify-content:center;padding:7px 22px;border-radius:999px;background:#511D99;color:#fff;font-weight:800;font-size:13px;letter-spacing:0.06em;margin-bottom:10px;">MẶT SAU</div>

                        {{-- ── FRONT: topic + word + part of speech + illustration ── --}}
                        <div id="flashcardFrontGroup" style="width:100%;display:flex;flex-direction:column;align-items:center;">
                            <div id="flashcardTopic" style="font-size:13px;font-weight:700;letter-spacing:0.04em;color:#3b6fa8;text-transform:uppercase;margin-bottom:6px;"></div>

                            <div id="flashcardWord" style="font-size:46px;font-weight:800;color:#1e2a4a;line-height:1.1;margin-bottom:14px;text-align:center;">-</div>

                            <div id="flashcardPartOfSpeech" style="display:inline-flex;align-items:center;gap:8px;font-size:15px;color:#334155;line-height:1.2;margin-bottom:18px;"></div>

                            <div id="flashcardVisual" style="width:100%;max-width:340px;height:200px;margin-bottom:6px;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                <img id="flashcardImage" src="" alt="" style="max-width:100%;max-height:100%;display:none;object-fit:contain;">
                                <div id="flashcardImageFallback" style="display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:14px;padding:16px;">No image</div>
                            </div>
                        </div>

                        {{-- ── BACK: word + IPA + audio icon + Vietnamese meaning + collocations + example ── --}}
                        <div id="flashcardBack" style="display:none;width:100%;text-align:center;">
                            <div style="position:relative;display:flex;align-items:center;justify-content:center;margin-bottom:4px;">
                                <span id="flashcardBackWord" style="font-size:24px;font-weight:800;color:#511D99;">-</span>
                                <span id="flashcardBackPos" style="font-size:18px;font-weight:700;color:#511D99;margin-left:4px;"></span>
                                <button type="button" id="flashcardAudioBtn" style="display:none;position:absolute;right:0;top:50%;transform:translateY(-50%);border:none;background:transparent;color:#511D99;cursor:pointer;padding:4px;">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M3 10v4h4l5 5V5L7 10H3z"/><path d="M16.5 12c0-1.77-.92-3.29-2.5-4.03v8.06c1.58-.74 2.5-2.26 2.5-4.03z" /><path d="M14 4.3v2.06c2.89.86 5 3.54 5 6.64s-2.11 5.78-5 6.64v2.06c4.01-.91 7-4.49 7-8.7s-2.99-7.79-7-8.7z"/></svg>
                                </button>
                            </div>
                            <span id="flashcardAudioText" style="display:none;"></span>

                            <div id="flashcardIpa" style="font-size:14px;color:#64748b;margin-bottom:16px;">-</div>

                            <div id="flashcardMeaning" style="font-size:20px;font-weight:800;letter-spacing:0.02em;color:#1e293b;margin-bottom:18px;">-</div>

                            <div style="border-top:1px dashed #cbd5e1;width:100%;margin-bottom:18px;"></div>

                            <div id="flashcardCollocationBlock" style="text-align:left;margin-bottom:14px;">
                                <strong style="font-size:14px;color:#1e293b;">Collocations:</strong>
                                <ul id="flashcardCollocationList" style="margin:8px 0 0;padding-left:20px;font-size:14px;color:#334155;line-height:1.8;"></ul>
                            </div>

                            <div id="flashcardExampleBlock" style="text-align:left;font-size:14px;color:#334155;line-height:1.7;">
                                <strong style="color:#1e293b;">Example:</strong>
                                <span id="flashcardExample">-</span>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;justify-content:center;gap:20px;margin-top:18px;flex-wrap:wrap;">
                        <button class="practice-btn btn-exit" id="flashcardExitBtn">{{ trans('panel.exit') }}</button>
                        <button class="practice-btn btn-next" id="flashcardPrevBtn" disabled>‹ Trước đó</button>
                        <button class="practice-btn btn-next" id="flashcardTurnBtn">Lật thẻ</button>
                        <button class="practice-btn btn-next" id="flashcardNextBtn" disabled>Tiếp theo ›</button>
                    </div>
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
                <h3 class="widget-title" style="text-align:center;text-transform:uppercase;">{{ trans('panel.flashcards') }}</h3>
                <div class="sidebar-flashcard-widget" id="sidebarFlashcardWidget">

                    <div style="text-align:center;">
                        <div id="sidebarSideLabel" style="display:inline-flex;align-items:center;justify-content:center;padding:5px 16px;border-radius:999px;background:#511D99;color:#fff;font-weight:800;font-size:11px;letter-spacing:0.05em;margin-bottom:12px;">MẶT TRƯỚC</div>
                    </div>

                    <!-- FRONT: word + part of speech badge -->
                    <div class="sidebar-card-meta" id="sidebarCardFrontMeta" style="margin-bottom:14px;">
                        <div class="sidebar-card-word" id="sidebarCardWord" style="font-size:22px;font-weight:800;color:#511D99;">-</div>
                
                    </div>

                    <!-- FRONT: illustration -->
                    <div class="sidebar-card-display" id="sidebarCardDisplay" style="border:none;box-shadow:none;background:transparent;">
                        <div class="sidebar-card-img-placeholder" id="sidebarCardPlaceholder" style="background:transparent;"></div>
                        <img id="sidebarCardImage" class="sidebar-card-image" src="" alt="" style="display:none;height:120px;object-fit:contain;border-radius:0;">
                    </div>

                    <!-- BACK: meaning + details -->
                    <div id="sidebarCardBack" style="display:none;text-align:center;padding:4px 2px 0;">
                        <div style="position:relative;display:flex;align-items:center;justify-content:center;margin-bottom:4px;">
                            <span id="sidebarBackWord" style="font-size:16px;font-weight:800;color:#511D99;">-</span>
                            <span id="sidebarBackPos" style="font-size:13px;font-weight:700;color:#511D99;margin-left:4px;"></span>
                        </div>
                        <div id="sidebarBackIpa" style="font-size:12px;color:#64748b;margin-bottom:10px;">-</div>
                        <div id="sidebarBackMeaning" style="font-size:15px;font-weight:800;color:#1e293b;text-transform:uppercase;margin-bottom:12px;">-</div>
                        <div style="border-top:1px dashed #cbd5e1;width:100%;margin-bottom:12px;"></div>
                        <div id="sidebarCollocationBlock" style="text-align:left;font-size:12px;margin-bottom:10px;">
                            <strong style="color:#1e293b;">Collocations:</strong>
                            <ul id="sidebarCollocationList" style="margin:6px 0 0;padding-left:16px;color:#334155;line-height:1.6;"></ul>
                        </div>
                        <div id="sidebarExampleBlock" style="text-align:left;font-size:12px;color:#334155;line-height:1.5;">
                            <strong style="color:#1e293b;">Example:</strong> <span id="sidebarExample">-</span>
                        </div>
                    </div>

                    <!-- Turn Button -->
                    <div class="text-center" style="margin: 12px 0 10px;">
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
    let currentWordListSourceType = null;
    let currentWordListSourceId = null;
    let currentQuestions = [];
    let currentWordData = null;
    let currentQuestionIndex = 0;
    let correctAnswers = 0;
    let incorrectAnswers = 0;
    let selectedAnswer = null;
    let practicedCorrectIds = new Set(); // word/flashcard IDs answered correctly in normal practice

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

    function setSidebarCardsFromWords(words) {
        const safeWords = Array.isArray(words) ? words : [];

        sidebarCards = safeWords.map(function(item) {
            return {
                id: item.id,
                word: item.word || item.headword || '',
                part_of_speech: item.part_of_speech || '',
                pronunciation: item.pronunciation || '',
                definition: item.definition || '',
                translation: item.translation || item.translation_vi || '',
                audio_url: item.audio_url || '',
                collocation: item.collocation || '',
                example: item.example || '',
                image_url: item.image_url || ''
            };
        });

        sidebarIndex = 0;
        sidebarFlipped = false;
        renderSidebarCard();
    }

    function sidebarRenderCollocationList(raw) {
        const $list = $('#sidebarCollocationList');
        $list.empty();

        if (!raw) {
            $('#sidebarCollocationBlock').hide();
            return;
        }

        const items = Array.isArray(raw)
            ? raw
            : String(raw).split(/[\n,;]+/).map(s => s.trim()).filter(Boolean);

        if (!items.length) {
            $('#sidebarCollocationBlock').hide();
            return;
        }

        items.forEach(function(item) {
            $list.append($('<li></li>').text(item));
        });
        $('#sidebarCollocationBlock').show();
    }
    function sidebarPosLabel(pos) {
        const map = {
            'n': 'Danh từ', 'noun': 'Danh từ',
            'v': 'Động từ', 'verb': 'Động từ',
            'adj': 'Tính từ', 'adjective': 'Tính từ',
            'adv': 'Trạng từ', 'adverb': 'Trạng từ'
        };
        if (!pos) return '';
        const key = pos.toLowerCase().trim();
        return map[key] || pos;
    }

    function renderSidebarCard() {
        if (!sidebarCards.length) {
            $('#sidebarCardWord').text('-');
            $('#sidebarCardPron').text('');
            $('#sidebarCardImage').hide();
            $('#sidebarCardPlaceholder').show();
            $('#sidebarCardCounter').text('Card 0 of 0');
            $('#sidebarPrevBtn, #sidebarNextBtn').prop('disabled', true);
            $('#sidebarCardFrontMeta, #sidebarCardDisplay, #sidebarSideLabel').show();
            $('#sidebarCardBack').hide();
            return;
        }
        sidebarFlipped = false;
        const card = sidebarCards[sidebarIndex];

        // Front
        $('#sidebarCardWord').text(card.word || '');
        $('#sidebarCardPron').html(
            (card.part_of_speech ? '<span style="display:inline-flex;align-items:center;justify-content:center;padding:3px 10px;border-radius:999px;background:#511D99;color:#fff;font-weight:700;font-size:12px;">(' + card.part_of_speech.charAt(0) + ')</span>' : '') +
            (sidebarPosLabel(card.part_of_speech) ? ' <span style="font-size:13px;color:#334155;">| ' + sidebarPosLabel(card.part_of_speech) + '</span>' : '')
        );
        // Back
        $('#sidebarBackWord').text(card.word || '');
        $('#sidebarBackPos').text(card.part_of_speech ? '(' + card.part_of_speech + ')' : '');
        $('#sidebarBackMeaning').text(card.translation || card.definition || '-');
        $('#sidebarBackIpa').text(card.pronunciation ? '/' + card.pronunciation + '/' : '-');
        sidebarRenderCollocationList(card.collocation);
        $('#sidebarExample').text(card.example || '-');

        $('#sidebarCardCounter').text('Card ' + (sidebarIndex + 1) + ' of ' + sidebarCards.length);
        $('#sidebarPrevBtn').prop('disabled', sidebarIndex === 0);
        $('#sidebarNextBtn').prop('disabled', sidebarIndex === sidebarCards.length - 1);

        // Reset to front
        $('#sidebarCardFrontMeta, #sidebarCardDisplay').show();
        $('#sidebarSideLabel').text('MẶT TRƯỚC').show();
        $('#sidebarCardBack').hide();
        $('#sidebarTurnBtn').text('{{ trans("panel.turn") }}');

        // Load word illustration
        if (card.word) {
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
            if (card.image_url) {
                img.src = card.image_url;
            } else {
                const word = encodeURIComponent(card.word.toLowerCase());
                img.src = 'https://loremflickr.com/280/140/' + word + '?lock=' + sidebarIndex;
            }
            img.alt = card.word;
        }
    }

    $('#sidebarTurnBtn').on('click', function() {
        if (!sidebarCards.length) return;
        sidebarFlipped = !sidebarFlipped;

        if (sidebarFlipped) {
            $('#sidebarCardFrontMeta, #sidebarCardDisplay').hide();
            $('#sidebarSideLabel').text('MẶT SAU');
            $('#sidebarCardBack').show();
            $(this).text('Ẩn');
        } else {
            $('#sidebarCardFrontMeta, #sidebarCardDisplay').show();
            $('#sidebarSideLabel').text('MẶT TRƯỚC');
            $('#sidebarCardBack').hide();
            $(this).text('{{ trans("panel.turn") }}');
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
        let sourceId = $card.data('source-id');
        let sourceType = $card.data('source-type');
        let expandedSection = $card.find('.word-list-expanded');

        if (expandedSection.hasClass('show')) {
            expandedSection.removeClass('show');
        } else {
            $('.word-list-expanded').removeClass('show');
            expandedSection.addClass('show');
            if (listType === 'academic') {
                loadAcademicWordList(listId, sourceType, sourceId);
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
    function loadAcademicWordList(listId, sourceType, sourceId) {
        const resolvedSourceType = sourceType || 'academic';
        const resolvedSourceId = sourceId || listId;
        const endpoint = resolvedSourceType === 'bundle'
            ? '/panel/dictionary/bundle-word-lists/' + resolvedSourceId
            : '/panel/dictionary/academic-word-lists/' + resolvedSourceId;

        $.ajax({
            url: endpoint,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    renderWords(response.data.words, listId, 'academic', resolvedSourceType, resolvedSourceId);
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
                    updateMyWordListMeta(response.data);
                    renderWords(response.data.flashcards, response.data.id, 'my', 'my', response.data.id);
                }
            },
            error: function(error) {
                console.error('Error loading my word list:', error);
            }
        });
    }

    // Render words in the list
    function renderWords(words, listId, listType, sourceType, sourceId) {
        let container = listType === 'academic'
            ? $('#words-' + listId)
            : $('#myWordListSection .words-container').first();
        
        container.empty();

        words.forEach(function(word) {
            let pronunciation = word.pronunciation ? `<span class="word-pronunciation">/${word.pronunciation}/</span>` : '';
            let showTick = word.is_learned || practicedCorrectIds.has(word.id);
            let wordSource = word.word_source || sourceType || listType;
            let wordHtml = `
                <div class="word-item" data-word-id="${word.id}" data-word="${word.word}" data-word-source="${wordSource}">
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
        currentWordListSourceType = sourceType || listType;
        currentWordListSourceId = sourceId || listId;

        // Sidebar widget must follow the currently opened vocabulary set.
        setSidebarCardsFromWords(words);
    }

    function updateMyWordListMeta(data) {
        if (!data) return;

        const section = $('#myWordListSection');
        const card = section.find('.word-list-card').first();

        card.attr('data-list-id', data.id || card.data('list-id'));

        section.find('.word-list-name').first().text(data.name || '{{ trans('panel.my_word_list') }}');
        section.find('.word-list-description').first().text(data.description || '');

        const totalWords = Number(data.word_count || 0);
        section.find('.word-count-badge').first().text('Tổng số từ: ' + totalWords + ' từ');

        $('#toggleMyWordListBtn').text('{{ trans('panel.my_word_list') }}');
    }

    // Mark word as learned
    $(document).on('click', '.learned-badge', function(e) {
        e.stopPropagation();

        const wordSource = $(this).closest('.word-item').data('word-source');
        if (wordSource !== 'academic') {
            $(this).toggleClass('show');
            return;
        }
        
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
    $(document).on('click', '.btn-practice:not(.btn-flashcard)', function(e) {
        e.stopPropagation();
        
        let selectedIds = [];
        $(this).closest('.word-list-expanded').find('.word-checkbox:checked').each(function() {
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
        let url = '/panel/dictionary/practice/start-my-word-list';
        
        let data = {
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        if (currentWordListType === 'academic') {
            if (currentWordListSourceType === 'bundle') {
                url = '/panel/dictionary/practice/start-bundle-word-list';
                data.vocabulary_set_id = currentWordListSourceId;
                data.flashcard_ids = wordIds;
            } else {
                url = '/panel/dictionary/practice/start';
                data.word_list_id = currentWordListSourceId;
                data.word_ids = wordIds;
            }
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
                    $('#flashcardPracticeContainer').addClass('hidden');
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

            // Show the learned tick for any correctly answered item in normal practice.
            const practicedId = question.word_id || question.flashcard_id;
            if (practicedId) {
                practicedCorrectIds.add(practicedId);
                $(`.learned-badge[data-word-id="${practicedId}"]`).addClass('show');
            }
        } else {
            incorrectAnswers++;
        }
        
        $('#correctCount').text(correctAnswers);
        $('#incorrectCount').text(incorrectAnswers);
        $('#nextQuestionBtn').prop('disabled', false);
        
        // Submit answer to server
        let wordKey = (currentWordListType === 'academic' && currentWordListSourceType !== 'bundle') ? 'word_id' : 'flashcard_id';
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
        $('#flashcardPracticeContainer').addClass('hidden');
        
        if (currentWordListType === 'academic') {
            $('#academicWordListsSection').removeClass('hidden');
        } else {
            $('#myWordListSection').removeClass('hidden');
        }
        
        // Reload the word list to show updated learned/tick status
        if (currentWordListType === 'academic') {
            loadAcademicWordList(currentWordListId, currentWordListSourceType, currentWordListSourceId);
        } else {
            loadMyWordList();
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

    // ---- Flashcard practice handlers ----
    let flashcardCards = [];
    let flashcardIndex = 0;
    let flashcardFlipped = false;

    function shuffleArray(items) {
        const arr = items.slice();

        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            const temp = arr[i];
            arr[i] = arr[j];
            arr[j] = temp;
        }

        return arr;
    }

    $(document).on('click', '.btn-flashcard', function(e) {
        e.stopPropagation();

        let selectedIds = [];
        $(this).closest('.word-list-expanded').find('.word-checkbox:checked').each(function() {
            selectedIds.push($(this).data('word-id'));
        });

        if (selectedIds.length === 0) {
            alert('Vui lòng chọn từ để luyện tập bằng flashcard');
            return;
        }

        startFlashcardPractice(selectedIds);
    });

    function startFlashcardPractice(selectedIds) {
        let endpoint = '';

        if (currentWordListType === 'academic') {
            if (currentWordListSourceType === 'bundle') {
                endpoint = '/panel/dictionary/bundle-word-lists/' + currentWordListSourceId;
            } else {
                endpoint = '/panel/dictionary/academic-word-lists/' + currentWordListSourceId;
            }
        } else {
            endpoint = '/panel/dictionary/my-word-list';
        }

        $.ajax({
            url: endpoint,
            method: 'GET',
            success: function(res) {
                if (!res.success) {
                    alert(res.message || 'Không thể tải từ vựng');
                    return;
                }

                let list = [];
                if (res.data && res.data.words) list = res.data.words;
                else if (res.data && res.data.flashcards) list = res.data.flashcards;
                else if (Array.isArray(res)) list = res;

                // Filter chosen words
                const chosen = list.filter(function(item) {
                    return selectedIds.indexOf(item.id) !== -1;
                });

                if (chosen.length === 0) {
                    alert('Không có từ hợp lệ để luyện tập');
                    return;
                }

                const mappedCards = chosen.map(function(item) {
                    return {
                        id: item.id,
                        word: item.word || item.headword || '',
                        part_of_speech: item.part_of_speech || '',
                        pronunciation: item.pronunciation || item.pronunciations && item.pronunciations[0] && (item.pronunciations[0].ipa || item.pronunciations[0].text) || '',
                        definition: item.definition || item.translation || '',
                        translation: item.translation || item.translation_vi || '',
                        image_url: item.image_url || '',
                        audio_url: item.audio_url || '',
                        collocation: item.collocation || '',
                        example: item.example || ''
                    };
                });

                flashcardCards = shuffleArray(mappedCards);

                flashcardIndex = 0;
                flashcardFlipped = false;

                // Show flashcard UI
                $('#practiceModeContainer').addClass('hidden');
                $('#academicWordListsSection, #myWordListSection').addClass('hidden');
                $('#flashcardPracticeContainer').removeClass('hidden');
                renderFlashcard();
            },
            error: function(err) {
                console.error('Flashcard load error', err);
                alert('Lỗi khi tải flashcards');
            }
        });
    }

    function posLabel(pos) {
        const map = {
            'n': 'Danh từ', 'noun': 'Danh từ',
            'v': 'Động từ', 'verb': 'Động từ',
            'adj': 'Tính từ', 'adjective': 'Tính từ',
            'adv': 'Trạng từ', 'adverb': 'Trạng từ'
        };
        if (!pos) return '';
        const key = pos.toLowerCase().trim();
        return map[key] || pos;
    }

    function renderCollocationList(raw) {
        const $list = $('#flashcardCollocationList');
        $list.empty();

        if (!raw) {
            $('#flashcardCollocationBlock').hide();
            return;
        }

        const items = Array.isArray(raw)
            ? raw
            : String(raw).split(/[\n,;]+/).map(s => s.trim()).filter(Boolean);

        if (!items.length) {
            $('#flashcardCollocationBlock').hide();
            return;
        }

        items.forEach(function(item) {
            $list.append($('<li></li>').text(item));
        });
        $('#flashcardCollocationBlock').show();
    }

    function renderExampleHighlighted(example, word) {
        if (!example) return '-';
        if (!word) return example;

        try {
            const escaped = word.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const re = new RegExp('(' + escaped + ')', 'ig');
            return $('<div></div>').text(example).html().replace(re, '<strong style="text-decoration:underline;">$1</strong>');
        } catch (e) {
            return example;
        }
    }

    function renderFlashcard() {
        if (!flashcardCards.length) {
            $('#flashcardTopic').text('');
            $('#flashcardWord').text('-');
            $('#flashcardPartOfSpeech').text('');
            $('#flashcardBackWord').text('-');
            $('#flashcardBackPos').text('');
            $('#flashcardMeaning').text('-');
            $('#flashcardIpa').text('-');
            renderCollocationList('');
            $('#flashcardExample').html('-');
            $('#flashcardAudioBtn').hide().data('audio', '');
            $('#flashcardImage').hide().attr('src', '');
            $('#flashcardImageFallback').show();
            $('#flashcardCard').css('justify-content', 'flex-start');
            $('#flashcardFrontGroup, #flashcardFrontLabel').show();
            $('#flashcardBackLabel').hide();
            $('#flashcardBack').hide();
            $('#flashcardPrevBtn, #flashcardNextBtn').prop('disabled', true);
            return;
        }

        const card = flashcardCards[flashcardIndex];
        const posText = posLabel(card.part_of_speech);

        // Front side
        $('#flashcardTopic').text(card.topic || '');
        $('#flashcardWord').text(card.word);
        $('#flashcardPartOfSpeech').html(
            (card.part_of_speech ? '<span style="display:inline-flex;align-items:center;justify-content:center;padding:3px 12px;border-radius:999px;background:#511D99;color:#fff;font-weight:700;font-size:13px;">' + card.part_of_speech + '</span>' : '')
        );

        // Back side
        $('#flashcardBackWord').text(card.word);
        $('#flashcardBackPos').text(card.part_of_speech ? '(' + card.part_of_speech + ')' : '');
        $('#flashcardMeaning').text(card.translation || card.definition || '-');
        $('#flashcardIpa').text(card.pronunciation ? '/' + card.pronunciation + '/' : '-');
        renderCollocationList(card.collocation);
        $('#flashcardExample').html(renderExampleHighlighted(card.example, card.word));

        if (card.audio_url) {
            $('#flashcardAudioBtn').show().data('audio', card.audio_url);
        } else {
            $('#flashcardAudioBtn').hide().data('audio', '');
        }

        // Always reset each navigated card to front side.
        $('#flashcardCard').css('justify-content', 'flex-start');
        $('#flashcardFrontGroup, #flashcardFrontLabel').show();
        $('#flashcardBackLabel').hide();
        $('#flashcardBack').hide();

        if (card.image_url) {
            const imageEl = document.getElementById('flashcardImage');
            const fallbackEl = document.getElementById('flashcardImageFallback');
            imageEl.style.display = 'none';
            fallbackEl.style.display = 'flex';
            imageEl.onload = function() {
                fallbackEl.style.display = 'none';
                imageEl.style.display = 'block';
            };
            imageEl.onerror = function() {
                imageEl.style.display = 'none';
                fallbackEl.style.display = 'flex';
            };
            imageEl.src = card.image_url;
        } else {
            $('#flashcardImage').hide().attr('src', '');
            $('#flashcardImageFallback').show();
        }

        flashcardFlipped = false;
        $('#flashcardPrevBtn').prop('disabled', flashcardIndex === 0);
        $('#flashcardNextBtn').prop('disabled', flashcardIndex === flashcardCards.length - 1);
        $('#flashcardTurnBtn').text('Lật thẻ');
    }

    $('#flashcardTurnBtn').on('click', function() {
        if (!flashcardCards.length) return;
        flashcardFlipped = !flashcardFlipped;
        if (flashcardFlipped) {
            $('#flashcardCard').css('justify-content', 'flex-start');
            $('#flashcardFrontGroup, #flashcardFrontLabel').hide();
            $('#flashcardBackLabel').css('display', 'inline-flex');
            $('#flashcardBack').css('display', 'block');
            $('#flashcardTurnBtn').text('Ẩn');
        } else {
            $('#flashcardCard').css('justify-content', 'flex-start');
            $('#flashcardBack').hide();
            $('#flashcardBackLabel').hide();
            $('#flashcardFrontLabel').show();
            $('#flashcardFrontGroup').show();
            $('#flashcardTurnBtn').text('Lật thẻ');
        }
    });

    $('#flashcardAudioBtn').on('click', function() {
        let url = $(this).data('audio');
        if (!url) {
            return;
        }

        if (typeof url === 'string' && url.startsWith('//')) {
            url = 'https:' + url;
        }

        const audio = new Audio(url);
        const button = $(this);
        button.prop('disabled', true).text('Đang phát...');

        const resetButton = function() {
            button.prop('disabled', false).text('Nghe phát âm');
        };

        audio.onended = resetButton;
        audio.onerror = resetButton;

        const playPromise = audio.play();
        if (playPromise !== undefined) {
            playPromise.catch(function() {
                resetButton();
                window.open(url, '_blank', 'noopener,noreferrer');
            });
        }
    });

    $('#flashcardNextBtn').on('click', function() {
        if (flashcardIndex < flashcardCards.length - 1) {
            flashcardIndex++;
            renderFlashcard();
        }
    });

    $('#flashcardPrevBtn').on('click', function() {
        if (flashcardIndex > 0) {
            flashcardIndex--;
            renderFlashcard();
        }
    });

    $('#flashcardExitBtn').on('click', function() {
        $('#flashcardPracticeContainer').addClass('hidden');
        $('#practiceModeContainer').addClass('hidden');
        if (currentWordListType === 'academic') {
            $('#academicWordListsSection').removeClass('hidden');
        } else {
            $('#myWordListSection').removeClass('hidden');
        }
        // reload lists to refresh any progress UI
        if (currentWordListType === 'academic') {
            loadAcademicWordList(currentWordListId, currentWordListSourceType, currentWordListSourceId);
        } else {
            loadMyWordList();
        }
    });

})(jQuery);
</script>
@endpush