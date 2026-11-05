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

        /* ── Word status badges ───────────────────────────────── */
    .word-badges {
        position: absolute;
        top: 12px;
        right: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        line-height: 1;
    }

    .word-badge {
        font-size: 15px;
        cursor: pointer;
        opacity: 0.22;
        transition: opacity 0.2s, transform 0.15s;
        user-select: none;
    }
    .word-badge:hover { transform: scale(1.2); opacity: 0.8; }
    .word-badge.on { opacity: 1; }

    .badge-learned { color: #16a34a; cursor: default; }
    .badge-learned:hover { transform: none; }
    .badge-review  { color: #dc2626; }
    .badge-star    { color: #f59e0b; }

    /* ── Filter chips ─────────────────────────────────────── */
    .status-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .status-chip {
        padding: 8px 16px;
        border: 1px solid #d1d5db;
        border-radius: 50px;
        background: transparent;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .status-chip:hover { border-color: #511D99; color: #511D99; }
    .status-chip.active {
        background: rgba(81, 29, 153, 0.10);
        border-color: #511D99;
        color: #511D99;
    }
    .dark-mode .status-chip { border-color: #334155; color: #94a3b8; }

    /* ── Nút trạng thái trong flashcard ───────────────────── */
    .flashcard-status-btns {
        display: flex;
        justify-content: center;
        gap: 14px;
        margin-top: 14px;
    }
    .flashcard-status-btn {
        padding: 7px 18px;
        border-radius: 50px;
        border: 1.5px solid #d1d5db;
        background: transparent;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }
    #fcReviewBtn.on { border-color: #dc2626; color: #dc2626; background: #fef2f2; }
    #fcStarBtn.on   { border-color: #f59e0b; color: #b45309; background: #fffbeb; }

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
    

    /* ── Word detail modal ─────────────────────────────────── */
    .word-detail-modal {
        position: fixed;
        inset: 0;
        display: none;
        z-index: 99999;
    }
    .word-detail-modal.is-open { display: block; }
 
    .word-detail-modal__backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 10, 40, 0.45);
    }
 
    .word-detail-modal__dialog {
        position: relative;
        width: min(720px, calc(100vw - 24px));
        margin: 40px auto;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 30px 70px rgba(40, 16, 90, 0.28);
        max-height: calc(100vh - 80px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .dark-mode .word-detail-modal__dialog { background: #1e293b; }
 
    .word-detail-modal__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 20px 10px 26px;
    }
 
    .word-detail-modal__heading {
        flex: 1;
        min-width: 0;
    }
 
    .word-detail-modal__close {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 50%;
        background: transparent;
        color: #475569;
        font-size: 30px;
        line-height: 1;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s, color 0.15s;
    }
    .word-detail-modal__close:hover {
        background: rgba(81, 29, 153, 0.08);
        color: #511D99;
    }
    .word-detail-modal__close:focus-visible {
        outline: 2px solid #511D99;
        outline-offset: 2px;
    }
    .dark-mode .word-detail-modal__close { color: #cbd5e1; }
 
    /* Chỉ phần thân cuộn, phần tiêu đề (từ + phát âm) luôn đứng yên */
    .word-detail-modal__body {
        padding: 4px 16px 24px 26px;
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
        scrollbar-width: thin;
        scrollbar-color: #7c4dcc transparent;
    }
    .word-detail-modal__body::-webkit-scrollbar { width: 6px; }
    .word-detail-modal__body::-webkit-scrollbar-track { background: transparent; }
    .word-detail-modal__body::-webkit-scrollbar-thumb {
        background: #7c4dcc;
        border-radius: 10px;
    }
 
    /* ── Tiêu đề: từ + IPA + nút loa ── */
    .wdc-title-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        column-gap: 18px;
        row-gap: 8px;
    }
 
    .wdc-word {
        margin: 0;
        font-size: 34px;
        font-weight: 800;
        line-height: 1.15;
        color: #511D99;
        word-break: break-word;
    }
    .dark-mode .wdc-word { color: #c4b5fd; }
 
    .wdc-pron-group {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
 
    .wdc-pron-label {
        padding: 2px 7px;
        border-radius: 6px;
        background: rgba(81, 29, 153, 0.10);
        color: #511D99;
        font-size: 11px;
        font-weight: 700;
    }
 
    .wdc-ipa {
        font-size: 17px;
        color: #334155;
    }
    .dark-mode .wdc-ipa { color: #cbd5e1; }
 
    .wdc-audio-btn {
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border: none;
        border-radius: 50%;
        background: rgba(81, 29, 153, 0.12);
        color: #511D99;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }
    .wdc-audio-btn:hover { background: rgba(81, 29, 153, 0.20); }
    .wdc-audio-btn:focus-visible {
        outline: 2px solid #511D99;
        outline-offset: 2px;
    }
    .wdc-audio-btn.is-playing {
        background: #511D99;
        color: #ffffff;
    }
    .dark-mode .wdc-audio-btn {
        background: rgba(196, 181, 253, 0.16);
        color: #c4b5fd;
    }
 
    /* ── Loại từ + nghĩa ── */
    .wdc-meaning-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px 12px;
    }
 
    .wdc-pos {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 8px;
        background: rgba(81, 29, 153, 0.10);
        color: #511D99;
        font-size: 13px;
        font-weight: 700;
    }
    .dark-mode .wdc-pos {
        background: rgba(196, 181, 253, 0.14);
        color: #c4b5fd;
    }
 
    .wdc-meaning {
        font-size: 15px;
        font-weight: 600;
        color: #334155;
    }
    .dark-mode .wdc-meaning { color: #e2e8f0; }
 
    .wdc-definition {
        margin-top: 8px;
        font-size: 14px;
        line-height: 1.55;
        color: #64748b;
    }
    .dark-mode .wdc-definition { color: #94a3b8; }
 
    /* ── Collocation / Example ── */
    .wdc-section-title {
        margin: 22px 0 10px;
        padding-left: 20px;
        font-size: 17px;
        font-weight: 800;
        color: #511D99;
    }
    .dark-mode .wdc-section-title { color: #c4b5fd; }
 
    .wdc-pill {
        padding: 12px 20px;
        border: 1px solid rgba(81, 29, 153, 0.06);
        border-radius: 20px;
        background: #f1edfb;
        text-align: left;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.6;
        color: #1e293b;
    }
    .wdc-pill + .wdc-pill { margin-top: 10px; }
    .dark-mode .wdc-pill {
        background: #0f172a;
        border-color: rgba(196, 181, 253, 0.12);
        color: #e2e8f0;
    }
 
    .wdc-highlight {
        color: #7446c4;
        font-weight: 700;
    }
    .dark-mode .wdc-highlight { color: #c4b5fd; }
 
    /* ── Ảnh minh hoạ ── */
    .wdc-image-wrap {
        margin-top: 22px;
        border-radius: 18px;
        overflow: hidden;
        aspect-ratio: 3 / 2;
        background: #f1edfb;
    }
    .wdc-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
 
    /* ── Nhiều nghĩa (từ tra từ điển ngoài) ── */
    .wdc-meaning-group + .wdc-meaning-group {
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px dashed rgba(81, 29, 153, 0.18);
    }
 
    .wdc-def-item {
        margin-top: 10px;
        font-size: 14px;
        line-height: 1.6;
        color: #334155;
    }
    .wdc-def-item strong { color: #511D99; }
    .dark-mode .wdc-def-item { color: #e2e8f0; }
    .wdc-def-item .wdc-pill { margin-top: 8px; }
 
    .wdc-syn {
        margin-top: 10px;
        font-size: 13px;
        color: #64748b;
    }
    .wdc-syn strong { color: #475569; }
    .dark-mode .wdc-syn,
    .dark-mode .wdc-syn strong { color: #94a3b8; }
 
    .wdm-loading,
    .wdm-empty {
        padding: 30px 0;
        text-align: center;
        color: #94a3b8;
    }
 
    @media (max-width: 575px) {
        .word-detail-modal__dialog {
            margin: 12px auto;
            max-height: calc(100vh - 24px);
            border-radius: 20px;
        }
        .word-detail-modal__header { padding: 14px 12px 8px 18px; }
        .word-detail-modal__body { padding: 4px 12px 20px 18px; }
        .wdc-word { font-size: 28px; }
        .wdc-pill { padding: 10px 14px; }
    }

        /* ── Tab bar cho 2 word list ───────────────────────────── */
    .wordlist-tabs {
        display: flex;
        gap: 6px;
        background: #f1f5f9;
        border-radius: 999px;
        padding: 5px;
        margin-bottom: 18px;
        width: fit-content;
    }
    .dark-mode .wordlist-tabs { background: #0f172a; }

    .wordlist-tab {
        border: none;
        background: transparent;
        padding: 9px 22px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .wordlist-tab:hover { color: #511D99; }

    .wordlist-tab.active {
        background: #ffffff;
        color: #511D99;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .dark-mode .wordlist-tab { color: #94a3b8; }
    .dark-mode .wordlist-tab.active {
        background: #1e293b;
        color: #c4b5fd;
    }

    .wordlist-tab__count {
        display: inline-block;
        margin-left: 6px;
        padding: 1px 8px;
        border-radius: 999px;
        background: rgba(81, 29, 153, 0.10);
        font-size: 12px;
        font-weight: 700;
    }
    .wordlist-tab.active .wordlist-tab__count {
        background: rgba(81, 29, 153, 0.16);
    }

    @media (max-width: 575px) {
        .wordlist-tabs { width: 100%; }
        .wordlist-tab { flex: 1; padding: 9px 10px; font-size: 13px; }
    }

    .flashcard-counter {
        font-size: 15px;
        font-weight: 700;
        color: #511D99;
        min-width: 70px;
        text-align: center;
        user-select: none;
    }
    .dark-mode .flashcard-counter { color: #c4b5fd; }

    /* ═════════════════════════════════════════════════════════
     * Practice: modal chọn chế độ, Quiz, modal kết quả (pq-*)
     * ═════════════════════════════════════════════════════════ */
    .btn-flashcard .pq-crown { color: #f5b301; vertical-align: -3px; margin-left: 4px; }

    .pq-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 76px;
        padding: 9px 22px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.2;
        white-space: nowrap;
        cursor: pointer;
        transition: background .2s, border-color .2s, color .2s, opacity .2s;
    }
    .pq-btn:disabled { opacity: .45; cursor: not-allowed; }
    .pq-btn--outline { background: #fff; color: #511D99; border: 1.5px solid #511D99; }
    .pq-btn--outline:not(:disabled):hover { background: #f5f0fd; }
    .pq-btn--soft { background: #efe9fb; color: #511D99; border: 1.5px solid transparent; }
    .pq-btn--soft:not(:disabled):hover { background: #e4dbf7; }
    .pq-btn--primary { background: #511D99; color: #fff; border: 1.5px solid #511D99; }
    .pq-btn--primary:not(:disabled):hover { background: #43177f; border-color: #43177f; }
    .pq-btn--lg { padding: 12px 22px; font-size: 15px; border-radius: 10px; width: 100%; }

    /* ── Quiz ─────────────────────────────────────────────── */
    .practice-mode-container.pq-quiz { padding: 22px 24px 18px; }
    .pq-quiz__badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 999px;
        background: #efe9fb;
        color: #511D99;
        font-size: 12px;
        font-weight: 600;
    }
    .pq-quiz__prompt {
        margin: 16px 0 18px;
        font-size: 24px;
        font-weight: 800;
        line-height: 1.35;
        color: #1e1b4b;
        word-break: break-word;
    }
    .pq-quiz__options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 12px;
    }
    .pq-option {
        box-sizing: border-box;
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
        min-height: 54px;
        padding: 12px 16px;
        background: #fff;
        border: 1.5px solid #e3dcf2;
        border-radius: 8px;
        text-align: left;
        color: #1e1b4b;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .pq-option:not(:disabled):hover { border-color: #b49de0; }
    .pq-option:disabled { cursor: default; opacity: 1; }
    .pq-option__radio {
        position: relative;
        flex: 0 0 22px;
        width: 22px;
        height: 22px;
        border: 2px solid #9a80d1;
        border-radius: 50%;
        background: #fff;
    }
    .pq-option__text { flex: 1; min-width: 0; word-break: break-word; }
    .pq-option__mark {
        flex: 0 0 26px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .pq-option__mark svg { width: 14px; height: 14px; }

    /* Vừa chọn – đang chờ chấm */
    .pq-option.is-selected { border-color: #511D99; background: #f3eefc; }
    .pq-option.is-selected .pq-option__radio { border-color: #511D99; }
    .pq-option.is-selected .pq-option__radio::after,
    .pq-option.is-correct.is-chosen .pq-option__radio::after,
    .pq-option.is-incorrect .pq-option__radio::after {
        content: '';
        position: absolute;
        inset: 4px;
        border-radius: 50%;
        background: currentColor;
    }
    .pq-option.is-selected .pq-option__radio { color: #511D99; }

    /* Đúng */
    .pq-option.is-correct { border-color: #4ade80; background: #f0fdf4; color: #15803d; }
    .pq-option.is-correct .pq-option__radio { border-color: #16a34a; color: #16a34a; }
    .pq-option.is-correct .pq-option__mark { display: inline-flex; background: #dcfce7; color: #16a34a; }
    /* Đáp án đúng được tô lại khi chọn sai / bỏ qua: radio đặc + dấu tick */
    .pq-option.is-correct:not(.is-chosen) .pq-option__radio {
        background: #16a34a;
        border-color: #16a34a;
    }
    .pq-option.is-correct:not(.is-chosen) .pq-option__radio::after {
        content: '';
        position: absolute;
        left: 6px;
        top: 2px;
        width: 6px;
        height: 11px;
        border: solid #fff;
        border-width: 0 2.5px 2.5px 0;
        transform: rotate(45deg);
    }

    /* Sai */
    .pq-option.is-incorrect { border-color: #fb7185; background: #fff1f2; color: #e11d48; }
    .pq-option.is-incorrect .pq-option__radio { border-color: #e11d48; color: #e11d48; }
    .pq-option.is-incorrect .pq-option__mark { display: inline-flex; background: #ffe4e6; color: #e11d48; }

    /* ── Điền từ (Word Completion) ────────────────────────── */
    .pq-fill { margin-top: 14px; }
    .pq-fill__sentences {
        margin: 0 0 14px;
        padding-left: 22px;
        list-style: disc;
        color: #111827;
        font-size: 16px;
        line-height: 1.55;
    }
    .pq-fill__sentences li { margin-bottom: 4px; padding-left: 2px; }
    .pq-fill__sentences li::marker { color: #111827; }
    .pq-fill__blank { letter-spacing: 0; white-space: nowrap; }
    .pq-fill__meaning { list-style: none; margin-left: -22px; color: #475569; font-size: 15px; }
    .pq-fill__meaning strong { color: #1e1b4b; }
    .pq-fill__input,
    .pq-fill__box {
        display: flex;
        align-items: center;
        box-sizing: border-box;
        width: 100%;
        min-height: 44px;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 15px;
    }
    .pq-fill__input {
        border: 1.5px solid #d9cdf0;
        background: #fff;
        color: #1e1b4b;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .pq-fill__input::placeholder { color: #94a3b8; }
    .pq-fill__input:focus { border-color: #511D99; box-shadow: 0 0 0 3px rgba(81, 29, 153, .12); }
    .pq-fill__result { display: flex; flex-direction: column; gap: 8px; }
    .pq-fill__box {
        justify-content: space-between;
        gap: 12px;
        font-weight: 700;
        word-break: break-word;
    }
    .pq-fill__box svg { flex: 0 0 18px; width: 18px; height: 18px; }
    .pq-fill__box.is-correct { border: 1.5px solid #4ade80; background: #f0fdf4; color: #166534; }
    .pq-fill__box.is-incorrect { border: 1.5px solid #fb7185; background: #fff1f2; color: #9f1239; }
    .pq-fill__box.is-empty { font-style: italic; font-weight: 500; }
    .pq-fill__hint {
        margin-top: 10px;
        padding: 4px 18px;
        border: 1.5px solid #511D99;
        border-radius: 999px;
        background: #f3eefc;
        color: #511D99;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: .02em;
    }
    .pq-fill__hint:disabled { cursor: default; }
    .pq-fill__hint:not(:disabled):hover { background: #e9e0fa; }
    .dark-mode .pq-fill__sentences,
    .dark-mode .pq-fill__sentences li::marker { color: #f1f5f9; }
    .dark-mode .pq-fill__input { background: #0f172a; border-color: #334155; color: #f1f5f9; }

    /* ── Nghe Viết (Dictation) ───────────────────────────── */
    .pq-audio {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 18px;
        min-height: 148px;
        padding: 20px 16px;
        margin-bottom: 14px;
        border: 1.5px solid #d9cdf0;
        border-radius: 8px;
        background: #fff;
        overflow: hidden;
    }
    .pq-audio.hidden { display: none !important; }
    .pq-audio__wave {
        display: flex;
        align-items: center;
        gap: 5px;
        height: 56px;
    }
    .pq-audio__wave span {
        flex: 0 0 4px;
        width: 4px;
        min-height: 3px;
        border-radius: 3px;
        background: #4c1d95;
        transform-origin: center;
    }
    .pq-audio__wave span:nth-child(3n+1) { background: #9b5de5; }
    .pq-audio__wave span:nth-child(-n+4) { background: #a78bfa; }
    .pq-audio__wave.is-right span:nth-last-child(-n+4) { background: #a78bfa; }
    .pq-audio.is-playing .pq-audio__wave span { animation: pqWave .9s ease-in-out infinite; }
    @keyframes pqWave {
        0%, 100% { transform: scaleY(1); }
        50%      { transform: scaleY(.35); }
    }
    .pq-audio__play {
        flex: 0 0 auto;
        width: 64px;
        height: 64px;
        border: none;
        border-radius: 50%;
        background: #511D99;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding-left: 4px;
        cursor: pointer;
        box-shadow: 0 0 0 9px rgba(81, 29, 153, .10), 0 0 0 18px rgba(81, 29, 153, .05);
        transition: transform .15s, background .2s;
    }
    .pq-audio__play:hover { background: #43177f; }
    .pq-audio__play:active { transform: scale(.96); }
    .pq-audio.is-playing .pq-audio__play { animation: pqPulse 1.2s ease-out infinite; }
    @keyframes pqPulse {
        0%   { box-shadow: 0 0 0 0 rgba(81, 29, 153, .30), 0 0 0 0 rgba(81, 29, 153, .12); }
        100% { box-shadow: 0 0 0 12px rgba(81, 29, 153, 0), 0 0 0 22px rgba(81, 29, 153, 0); }
    }
    .pq-audio__msg { margin: -6px 0 10px; font-size: 13px; color: #e11d48; }
    .pq-audio__msg:empty { display: none; }
    .dark-mode .pq-audio { background: #0f172a; border-color: #334155; }

    @media (max-width: 575.98px) {
        .pq-audio { gap: 10px; min-height: 120px; padding: 16px 8px; }
        .pq-audio__wave { gap: 3px; }
        .pq-audio__wave span { flex-basis: 3px; width: 3px; }
        .pq-audio__wave span:nth-child(-n+6) { display: none; }
        .pq-audio__wave.is-right span:nth-child(-n+6) { display: block; }
        .pq-audio__wave.is-right span:nth-last-child(-n+6) { display: none; }
        .pq-audio__play { width: 54px; height: 54px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .pq-audio.is-playing .pq-audio__wave span,
        .pq-audio.is-playing .pq-audio__play { animation: none; }
    }

    .pq-quiz__skipped.is-fill { margin: 0 0 8px; }
    .pq-quiz__skipped {
        margin: -6px 0 12px;
        font-size: 13px;
        font-weight: 600;
        color: #e11d48;
    }

    .pq-quiz__footer {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px 26px;
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid #eeeaf6;
    }
    .pq-quiz__counter {
        min-width: 48px;
        text-align: center;
        font-size: 14px;
        font-weight: 700;
        color: #1e1b4b;
        user-select: none;
    }

    .dark-mode .pq-quiz__prompt,
    .dark-mode .pq-quiz__counter { color: #f1f5f9; }
    .dark-mode .pq-option { background: #1e293b; border-color: #334155; color: #f1f5f9; }
    .dark-mode .pq-option.is-selected { background: rgba(81, 29, 153, .25); border-color: #a78bfa; }
    .dark-mode .pq-option.is-correct { background: rgba(22, 163, 74, .15); color: #4ade80; }
    .dark-mode .pq-option.is-incorrect { background: rgba(225, 29, 72, .15); color: #fb7185; }
    .dark-mode .pq-quiz__footer { border-top-color: #334155; }
    .dark-mode .pq-btn--outline { background: transparent; color: #c4b5fd; border-color: #a78bfa; }

    @media (max-width: 575.98px) {
        .practice-mode-container.pq-quiz { padding: 18px 16px 14px; }
        .pq-quiz__prompt { font-size: 20px; }
        .pq-quiz__options { grid-template-columns: 1fr; }
        .pq-quiz__footer { gap: 10px; }
        .pq-quiz__footer .pq-btn { padding: 8px 14px; min-width: 0; }
    }

    /* ── Modal chung ──────────────────────────────────────── */
    .pq-modal {
        position: fixed;
        inset: 0;
        z-index: 99998;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .pq-modal.is-open { display: flex; }
    .pq-modal__backdrop {
        position: absolute;
        inset: 0;
        background: rgba(30, 20, 60, .35);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
    }
    .pq-modal__dialog {
        position: relative;
        width: 100%;
        max-height: calc(100vh - 32px);
        overflow-y: auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 24px 60px rgba(40, 16, 90, .25);
    }
    .dark-mode .pq-modal__dialog { background: #1e293b; }
    .pq-modal__close {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: 1.5px solid #511D99;
        background: #fff;
        color: #511D99;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    /* ── Modal chọn chế độ ───────────────────────────────── */
    .pq-mode { max-width: 460px; padding: 26px 28px 22px; text-align: center; }
    .pq-mode__icon {
        width: 96px;
        height: 96px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #efe9fb;
        color: #511D99;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pq-mode__title { margin: 0; font-size: 24px; font-weight: 800; color: #511D99; }
    .pq-mode__divider { height: 1px; background: #e7e0f3; margin: 14px 0 18px; }
    .pq-mode__options { display: flex; flex-direction: column; gap: 16px; text-align: left; padding: 0 10px; }
    .pq-mode__option {
        display: flex;
        align-items: center;
        gap: 14px;
        margin: 0;
        font-size: 16px;
        font-weight: 500;
        color: #1e1b4b;
        cursor: pointer;
    }
    .pq-mode__option.is-disabled { cursor: not-allowed; opacity: .5; }
    .pq-mode__input { position: absolute; opacity: 0; width: 1px; height: 1px; pointer-events: none; }
    .pq-mode__box {
        position: relative;
        flex: 0 0 24px;
        width: 24px;
        height: 24px;
        border: 2px solid #9a80d1;
        border-radius: 5px;
        background: #fff;
        transition: background .15s, border-color .15s;
    }
    .pq-mode__input:checked + .pq-mode__box { background: #511D99; border-color: #511D99; }
    .pq-mode__input:checked + .pq-mode__box::after {
        content: '';
        position: absolute;
        left: 7px;
        top: 2px;
        width: 6px;
        height: 12px;
        border: solid #fff;
        border-width: 0 2.5px 2.5px 0;
        transform: rotate(45deg);
    }
    .pq-mode__input:focus-visible + .pq-mode__box { outline: 2px solid #b49de0; outline-offset: 2px; }
    .pq-mode__soon {
        margin-left: auto;
        padding: 2px 8px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
    }
    .pq-mode__start {
        width: 100%;
        margin-top: 46px;
        padding: 13px 20px;
        border: none;
        border-radius: 999px;
        background: #511D99;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s;
    }
    .pq-mode__start:not(:disabled):hover { background: #43177f; }
    .pq-mode__start:disabled { background: #c9bce6; cursor: not-allowed; }
    .dark-mode .pq-mode__option { color: #f1f5f9; }

    /* ── Modal kết quả ───────────────────────────────────── */
    .pq-result { max-width: 640px; padding: 40px 30px 22px; }
    .pq-result__close {
        position: absolute;
        top: 10px;
        right: 12px;
        border: none;
        background: transparent;
        color: #511D99;
        cursor: pointer;
        padding: 4px;
    }
    .pq-result__score {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 22px;
        padding: 8px 16px;
        border: 1px solid #d9cdf0;
        border-radius: 8px;
        background: #f3eefc;
    }
    .pq-result__score-label { font-size: 15px; color: #511D99; }
    .pq-result__score-value { font-size: 32px; font-weight: 800; color: #3b1673; }
    .pq-result__cols {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin-top: 10px;
        border-top: 1px solid #ece7f5;
        border-bottom: 1px solid #ece7f5;
    }
    .pq-result__col { padding: 12px 0 10px; }
    .pq-result__col + .pq-result__col { border-left: 1px solid #ece7f5; }
    .pq-result__col:first-child { padding-right: 10px; }
    .pq-result__col:last-child { padding-left: 10px; }
    .pq-result__pill {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid;
        font-size: 15px;
    }
    .pq-result__pill strong { font-size: 22px; font-weight: 800; }
    .pq-result__pill.is-learned { background: #ecfdf5; border-color: #a7f3d0; color: #15803d; }
    .pq-result__pill.is-review { background: #fff1f2; border-color: #fecdd3; color: #e11d48; }
    .pq-result__sep { width: 1px; height: 18px; background: currentColor; opacity: .4; }
    .pq-result__list {
        list-style: none;
        margin: 10px 0 0;
        padding: 0 6px;
        max-height: 150px;
        overflow-y: auto;
        text-align: center;
        font-size: 15px;
        line-height: 1.85;
        color: #2a2a8c;
        scrollbar-width: thin;
        scrollbar-color: #7c4ddb #f1edf9;
    }
    .pq-result__list::-webkit-scrollbar { width: 6px; }
    .pq-result__list::-webkit-scrollbar-track { background: #f1edf9; border-radius: 6px; }
    .pq-result__list::-webkit-scrollbar-thumb { background: #7c4ddb; border-radius: 6px; }
    .pq-result__empty { color: #94a3b8; font-size: 14px; }
    .pq-result__actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 18px;
    }
    .dark-mode .pq-result__list { color: #c7d2fe; }

    @media (max-width: 575.98px) {
        .pq-result { padding: 36px 16px 16px; }
        .pq-result__score-value { font-size: 26px; }
        .pq-result__pill { font-size: 13px; gap: 6px; padding: 6px 8px; }
        .pq-result__pill strong { font-size: 18px; }
        .pq-result__actions { grid-template-columns: 1fr; }
    }
    /* ═════════════════════════════════════════════════════════
     * Báo lỗi từ vựng (nút trong cửa sổ chi tiết từ + modal)
     * ═════════════════════════════════════════════════════════ */
    .word-detail-modal__actions {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 42px;
    }
    .word-detail-modal__report {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        height: 36px;
        padding: 0 16px;
        border: 1.5px solid #511D99;
        border-radius: 10px;
        background: #fff;
        color: #511D99;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
        transition: background .15s;
    }
    .word-detail-modal__report:hover { background: #f5f0fd; }
    .word-detail-modal__report:focus-visible { outline: 2px solid #511D99; outline-offset: 2px; }
    .word-detail-modal__report.hidden { display: none !important; }
    .dark-mode .word-detail-modal__report { background: transparent; color: #c4b5fd; border-color: #a78bfa; }

    .vw-report {
        position: fixed;
        inset: 0;
        z-index: 100001; /* trên cửa sổ chi tiết từ (99999) */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .vw-report[hidden] { display: none; }
    .vw-report__backdrop {
        position: absolute;
        inset: 0;
        background: rgba(30, 20, 60, .35);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
    }
    .vw-report__dialog {
        position: relative;
        box-sizing: border-box;
        width: 100%;
        max-width: 560px;
        max-height: calc(100vh - 32px);
        overflow-y: auto;
        margin: 0;
        padding: 30px 32px 26px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 24px 60px rgba(40, 16, 90, .25);
    }
    .vw-report__x {
        position: absolute;
        top: 18px;
        right: 20px;
        padding: 4px;
        border: none;
        background: transparent;
        color: #511D99;
        cursor: pointer;
        line-height: 0;
    }
    .vw-report__title {
        margin: 0 36px 10px 0;
        font-size: 30px;
        font-weight: 800;
        line-height: 1.2;
        color: #511D99;
    }
    .vw-report__sub { margin: 0 0 22px; font-size: 14px; line-height: 1.55; color: #5b4a86; }
    .vw-report__label { display: block; margin: 0 0 8px; font-size: 16px; font-weight: 700; color: #511D99; }
    .vw-report__textarea {
        box-sizing: border-box;
        display: block;
        width: 100%;
        min-height: 132px;
        padding: 12px 14px;
        border: 1.5px solid #d9cdf0;
        border-radius: 10px;
        background: #fcfbff;
        color: #1e1b4b;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.5;
        resize: vertical;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .vw-report__textarea::placeholder { color: #a3a3b8; }
    .vw-report__textarea:focus { border-color: #511D99; box-shadow: 0 0 0 3px rgba(81, 29, 153, .12); }
    .vw-report__textarea:disabled { opacity: .7; }
    .vw-report__count { margin-top: 6px; text-align: right; font-size: 13px; color: #6b6b85; }
    .vw-report__count.is-full { color: #e11d48; font-weight: 600; }
    .vw-report__msg { margin: 10px 0 0; padding: 8px 12px; border-radius: 8px; font-size: 13px; }
    .vw-report__msg.is-success { background: #ecfdf5; color: #15803d; }
    .vw-report__msg.is-error { background: #fff1f2; color: #e11d48; }
    .vw-report__actions { display: flex; justify-content: center; gap: 12px; margin-top: 26px; }
    .vw-report__btn {
        min-width: 180px;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s, opacity .2s;
    }
    .vw-report__btn--outline { background: #fff; color: #511D99; border: 1.5px solid #511D99; }
    .vw-report__btn--outline:hover { background: #f5f0fd; }
    .vw-report__btn--primary { background: #511D99; color: #fff; border: 1.5px solid #511D99; }
    .vw-report__btn--primary:not(:disabled):hover { background: #43177f; }
    .vw-report__btn--primary:disabled { opacity: .5; cursor: not-allowed; }
    .vw-report__dialog.is-sent .vw-report__btn--primary { display: none; }
    .dark-mode .vw-report__dialog { background: #1e293b; }
    .dark-mode .vw-report__textarea { background: #0f172a; border-color: #334155; color: #f1f5f9; }

    @media (max-width: 575.98px) {
        .word-detail-modal__report { padding: 0 10px; }
        .word-detail-modal__report span { display: none; }
        .vw-report__dialog { padding: 24px 18px 18px; }
        .vw-report__title { font-size: 24px; }
        .vw-report__actions { flex-direction: column-reverse; }
        .vw-report__btn { width: 100%; min-width: 0; }
    }

    /* ═════════════════════════════════════════════════════════
     * Khung ảnh minh hoạ từ vựng: ảnh 1:1, 3:2, dọc hay ngang đều
     * hiện trọn vẹn (contain), không méo, không cắt. Phần trống được
     * phủ bằng chính ảnh đó làm mờ.
     * ═════════════════════════════════════════════════════════ */
    .vc-media {
        position: relative;
        overflow: hidden;
        isolation: isolate;
        background: #f3effb;
    }
    .vc-media__bg {
        position: absolute;
        inset: -24px;
        z-index: 0;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        filter: blur(18px) saturate(1.1);
        opacity: .5;
        transform: scale(1.05);
        pointer-events: none;
    }
    .vc-media__img {
        position: absolute !important;
        inset: 0;
        z-index: 1;
        width: 100% !important;
        height: 100% !important;
        max-width: none !important;
        max-height: none !important;
        margin: 0 !important;
        object-fit: contain !important;
        object-position: center;
        display: block;
    }
    .vc-media__fallback {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 14px;
    }
    .dark-mode .vc-media { background: #0f172a; }

    /* Thẻ flashcard (Luyện tập nâng cao PRO): khung 3:2, rộng tối đa 340px */
    #flashcardVisual.vc-media {
        width: 100%;
        max-width: 340px;
        aspect-ratio: 3 / 2;
        height: auto;
        margin-bottom: 6px;
        border-radius: 14px;
    }
    /* Cửa sổ chi tiết từ: giữ khung 3:2 nhưng ảnh không còn bị cắt */
    .wdc-image-wrap.vc-media img { object-fit: contain; }

    /* ═════════════════════════════════════════════════════════
     * Dictionary Hub (dh-*) + Word List dạng folder (wf-*)
     * ═════════════════════════════════════════════════════════ */
    :root {
        --dh-primary: #5B2A86;
        --dh-primary-dark: #4A2170;
        --dh-soft: #EFE9F8;
        --dh-softer: #F7F4FC;
        --dh-border: #E3DEEE;
        --dh-text: #2E2A3B;
        --dh-muted: #6E6783;
    }

    .dh-card {
        background: #fff;
        border: 1px solid var(--dh-border);
        border-radius: 20px;
        padding: 24px 24px 22px;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(60, 30, 110, .05);
    }
    .dh-title {
        margin: 0;
        font-size: 30px;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -.01em;
        color: var(--dh-primary);
    }
    .dh-sub { margin: 6px 0 0; font-size: 13px; color: var(--dh-muted); }
    .dh-label {
        display: block;
        margin: 18px 0 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--dh-primary);
    }

    .dh-search { position: relative; display: flex; gap: 10px; margin: 0; }
    .dh-search__field {
        position: relative;
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
        height: 46px;
        padding: 0 8px 0 14px;
        border: 1px solid #D9D3E6;
        border-radius: 12px;
        background: #fff;
        transition: border-color .2s, box-shadow .2s;
    }
    .dh-search__field:focus-within { border-color: var(--dh-primary); box-shadow: 0 0 0 3px rgba(91, 42, 134, .12); }
    .dh-search__icon { display: inline-flex; color: #8E879E; margin-right: 10px; flex-shrink: 0; }
    .dh-search__icon svg, .wf-search__icon svg { width: 18px; height: 18px; }
    .dh-search__input {
        flex: 1;
        min-width: 0;
        height: 100%;
        border: none;
        outline: none;
        background: transparent;
        font-size: 15px;
        color: var(--dh-text);
    }
    .dh-search__input::placeholder { color: #8E879E; }
    .dh-search__clear {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;
        background: transparent;
        color: #8E879E;
        cursor: pointer;
    }
    .dh-search__clear:hover { background: var(--dh-softer); color: var(--dh-primary); }
    .dh-search__btn {
        flex-shrink: 0;
        height: 46px;
        padding: 0 20px;
        border: none;
        border-radius: 12px;
        background: var(--dh-primary);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
        cursor: pointer;
        transition: background .2s, opacity .2s;
    }
    .dh-search__btn:hover { background: var(--dh-primary-dark); }
    .dh-search__btn:disabled { opacity: .7; cursor: progress; }

    /* Lịch sử tìm kiếm */
    .dh-history {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        z-index: 60;
        max-height: 360px;
        overflow-y: auto;
        padding: 8px;
        background: #fff;
        border: 1px solid var(--dh-border);
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(40, 20, 80, .14);
    }
    .dh-history[hidden] { display: none; }
    .dh-history__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 10px 8px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--dh-muted);
    }
    .dh-history__clear-all {
        border: none;
        background: transparent;
        color: var(--dh-primary);
        font-size: 12px;
        font-weight: 700;
        text-transform: none;
        letter-spacing: 0;
        cursor: pointer;
        padding: 2px 4px;
    }
    .dh-history__item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 6px 0 10px;
        border-radius: 10px;
        cursor: pointer;
    }
    .dh-history__item:hover,
    .dh-history__item.is-active { background: var(--dh-softer); }
    .dh-history__icon { display: inline-flex; color: #A49DB4; flex-shrink: 0; }
    .dh-history__text {
        flex: 1;
        min-width: 0;
        padding: 9px 0;
        font-size: 14px;
        color: var(--dh-text);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .dh-history__text mark { background: transparent; color: var(--dh-primary); font-weight: 700; padding: 0; }
    .dh-history__remove {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;
        background: transparent;
        color: #A49DB4;
        cursor: pointer;
    }
    .dh-history__remove:hover { background: #fff; color: #E11D48; }

    /* Banner (trạng thái chưa tra) */
    .dh-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        min-height: 160px;
        margin-top: 16px;
        padding: 20px 32px;
        background: #F6F3FB;
        border: 1px solid var(--dh-border);
        border-radius: 16px;
        overflow: hidden;
    }
    .dh-banner.hidden { display: none !important; }
    .dh-banner__title { margin: 0; font-size: 24px; font-weight: 800; line-height: 1.3; color: #8A7AA3; }
    .dh-banner__sub { margin: 10px 0 0; font-size: 13px; color: #A69CB8; }
    .dh-banner__img { flex-shrink: 0; height: 130px; width: auto; user-select: none; pointer-events: none; }

    /* Kết quả */
    .dh-results { margin-top: 16px; display: flex; flex-direction: column; gap: 14px; }
    .dh-results.hidden { display: none !important; }
    .dh-entry {
        padding: 20px 22px;
        background: #fff;
        border: 1px solid var(--dh-border);
        border-radius: 16px;
    }
    .dh-entry__head { display: flex; align-items: center; flex-wrap: wrap; gap: 8px 12px; }
    .dh-entry__word {
        margin: 0 4px 0 0;
        font-size: 34px;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -.02em;
        color: var(--dh-primary);
        word-break: break-word;
    }
    .dh-entry__pos {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        background: var(--dh-soft);
        color: var(--dh-primary);
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }
    .dh-entry__ipa { font-size: 15px; color: #3F3A4D; }
    .dh-entry__audio {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;
        background: var(--dh-soft);
        color: var(--dh-primary);
        cursor: pointer;
        transition: transform .15s;
    }
    .dh-entry__audio:hover { transform: scale(1.06); }
    .dh-entry__audio.playing { background: var(--dh-primary); color: #fff; }
    .dh-entry__level {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 46px;
        padding: 4px 12px;
        border: 1.5px solid var(--dh-primary);
        border-radius: 999px;
        color: var(--dh-primary);
        font-size: 13px;
        font-weight: 800;
    }
    .dh-entry__meaning { margin: 14px 0 0; font-size: 16px; font-weight: 700; color: var(--dh-primary); }
    .dh-entry__def { margin: 6px 0 0; font-size: 14px; line-height: 1.6; color: var(--dh-muted); }
    .dh-entry__section { margin: 16px 0 8px; font-size: 15px; font-weight: 700; color: var(--dh-primary); }
    .dh-entry__colloc {
        padding: 12px 16px;
        background: #F5F1FB;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.6;
        color: #3F3A4D;
    }
    .dh-entry__colloc p { margin: 0; }
    .dh-entry__colloc p + p { margin-top: 8px; font-style: italic; color: #7C7391; }
    .dh-entry__examples { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 8px; }
    .dh-entry__example { display: flex; align-items: flex-start; gap: 10px; }
    .dh-entry__num {
        flex: 0 0 28px;
        height: 28px;
        margin-top: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--dh-soft);
        color: var(--dh-primary);
        font-size: 13px;
        font-weight: 700;
    }
    .dh-entry__example-text {
        flex: 1;
        min-width: 0;
        padding: 8px 14px;
        border-radius: 10px;
        background: var(--dh-softer);
        font-size: 14px;
        line-height: 1.5;
        color: var(--dh-text);
    }
    .dh-entry__example-text strong { color: var(--dh-primary); }
    .dh-entry__syn { margin: 12px 0 0; font-size: 13px; color: var(--dh-muted); }
    .dh-entry__syn strong { color: var(--dh-primary); }
    .dh-entry__save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        padding: 8px 16px;
        border: none;
        border-radius: 999px;
        background: var(--dh-primary);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s;
    }
    .dh-entry__save:hover { background: var(--dh-primary-dark); }
    .dh-entry__save.is-saved { background: #16A34A; }

    .dh-state {
        padding: 22px 20px;
        background: #fff;
        border: 1px solid var(--dh-border);
        border-radius: 16px;
        text-align: center;
        color: var(--dh-muted);
        font-size: 14px;
    }
    .dh-state strong { color: var(--dh-primary); }
    .dh-state__title { margin: 0 0 6px; font-size: 16px; font-weight: 700; color: var(--dh-primary); }
    .dh-skeleton { height: 18px; border-radius: 8px; background: linear-gradient(90deg, #F1EDF8 25%, #E7E1F3 50%, #F1EDF8 75%); background-size: 200% 100%; animation: dhShimmer 1.2s linear infinite; }
    .dh-skeleton + .dh-skeleton { margin-top: 14px; }
    @keyframes dhShimmer { from { background-position: 200% 0; } to { background-position: -200% 0; } }

    /* ── Tabs My Word List / Academic Word List ── */
    .wordlist-tabs {
        display: flex !important;
        gap: 14px !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 0 18px !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    .wordlist-tab {
        flex: 1 !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        gap: 10px;
        height: 48px;
        padding: 0 14px !important;
        border: 1.5px solid var(--dh-primary) !important;
        border-radius: 12px !important;
        background: #fff !important;
        color: var(--dh-primary) !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        box-shadow: none !important;
    }
    .wordlist-tab.active {
        background: var(--dh-primary) !important;
        color: #fff !important;
        box-shadow: 0 6px 16px rgba(91, 42, 134, .16) !important;
    }
    .wordlist-tab .wordlist-tab__count {
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: var(--dh-soft) !important;
        color: var(--dh-primary) !important;
        font-size: 12px;
        font-weight: 700;
    }
    .wordlist-tab.active .wordlist-tab__count { background: #fff !important; color: var(--dh-primary) !important; }

    /* ── Folder Word List ── */
    .wf-view.hidden { display: none !important; }
    .wf-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
    .wf-title { margin: 0; font-size: 20px; font-weight: 800; color: var(--dh-primary); }
    .wf-sub { margin: 4px 0 0; font-size: 13px; color: var(--dh-muted); }
    .wf-create {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border: none;
        border-radius: 10px;
        background: var(--dh-primary);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }
    .wf-create:hover { background: var(--dh-primary-dark); }
    .wf-create:disabled { opacity: .5; cursor: not-allowed; }
    .wf-search {
        display: flex;
        align-items: center;
        height: 44px;
        margin: 14px 0 16px;
        padding: 0 8px 0 14px;
        border: 1px solid #D9D3E6;
        border-radius: 12px;
        background: #fff;
    }
    .wf-search:focus-within { border-color: var(--dh-primary); }
    .wf-search__icon { display: inline-flex; color: var(--dh-primary); margin-right: 10px; }
    .wf-search__input { flex: 1; min-width: 0; border: none; outline: none; background: transparent; font-size: 14px; color: var(--dh-text); }
    .wf-search__input::placeholder { color: #8E879E; }
    .wf-search__clear { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border: none; border-radius: 50%; background: transparent; color: #8E879E; cursor: pointer; }
    .wf-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px; }
    .wf-card {
        display: flex;
        gap: 14px;
        padding: 12px;
        background: #fff;
        border: 1px solid var(--dh-border);
        border-radius: 16px;
        transition: border-color .2s, box-shadow .2s;
    }
    .wf-card:hover { border-color: #CDBFE6; box-shadow: 0 10px 26px rgba(60, 30, 110, .07); }
    .wf-card__thumb {
        flex: 0 0 112px;
        width: 112px;
        aspect-ratio: 1 / 1;
        align-self: flex-start;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px;
        border: none;
        border-radius: 12px;
        background: #F1EBFA;
        color: var(--dh-primary);
        cursor: pointer;
    }
    .wf-card__thumb-label {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }
    .wf-card__body { flex: 1; min-width: 0; display: flex; flex-direction: column; padding-top: 2px; }
    .wf-card__name {
        margin: 0;
        padding: 0;
        border: none;
        background: transparent;
        text-align: left;
        font-size: 15px;
        font-weight: 700;
        color: var(--dh-primary);
        cursor: pointer;
        word-break: break-word;
    }
    .wf-card__name:hover { text-decoration: underline; }
    .wf-card__desc {
        margin: 4px 0 0;
        font-size: 13px;
        line-height: 1.45;
        color: var(--dh-muted);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        word-break: break-word;
    }
    .wf-card__foot { margin-top: auto; padding-top: 10px; display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; }
    .wf-card__count { display: inline-block; padding: 3px 10px; border-radius: 999px; background: #F4F0FA; color: var(--dh-text); font-size: 12px; font-weight: 700; }
    .wf-card__practice {
        display: inline-block;
        margin-top: 0;
        padding: 6px 14px;
        border: none;
        border-radius: 999px;
        background: var(--dh-primary);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }
    .wf-card__practice:hover { background: var(--dh-primary-dark); }
    .wf-card__practice:disabled { opacity: .45; cursor: not-allowed; }
    .wf-empty { grid-column: 1 / -1; padding: 30px; border: 1px dashed var(--dh-border); border-radius: 18px; text-align: center; color: var(--dh-muted); }
    .wf-limit { margin: 10px 0 0; font-size: 12px; color: var(--dh-muted); }
    .wf-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 0 0 10px;
        padding: 4px 2px;
        border: none;
        background: transparent;
        color: var(--dh-primary);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }
    .wf-back:hover { text-decoration: underline; }
    #wfDetailView .word-list-card[data-list-type="my"] { cursor: default; }
    .wf-detail-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .wf-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border: 1px solid var(--dh-border);
        border-radius: 8px;
        background: #fff;
        color: var(--dh-primary);
        cursor: pointer;
    }
    .wf-icon-btn:hover { border-color: var(--dh-primary); }
    .wf-icon-btn.is-danger { color: #E11D48; }
    .wf-icon-btn.is-danger:hover { border-color: #E11D48; }
    .wf-icon-btn.hidden { display: none !important; }
    .wf-words-empty { padding: 26px 12px; text-align: center; color: var(--dh-muted); font-size: 14px; }

    /* ── Modal lưu từ / tạo Word List ── */
    .pq-modal__dialog.dh-modal {
        max-width: 480px;
        padding: 24px 26px 24px;
        border: 1px solid var(--dh-primary);
        border-radius: 16px;
    }
    .dh-modal__close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--dh-border);
        border-radius: 50%;
        background: #fff;
        color: var(--dh-primary);
        cursor: pointer;
    }
    .dh-modal__title { margin: 0 40px 0 0; font-size: 20px; font-weight: 800; color: var(--dh-primary); }
    .dh-modal__sub { margin: 10px 0 0; font-size: 14px; color: var(--dh-muted); word-break: break-word; }
    .dh-modal__sub strong { color: var(--dh-text); }
    .dh-modal__label { display: block; margin: 16px 0 6px; font-size: 13px; font-weight: 700; color: var(--dh-primary); }
    .dh-modal__optional { font-weight: 500; color: var(--dh-muted); }
    .dh-modal__select,
    .dh-modal__input {
        box-sizing: border-box;
        display: block;
        width: 100%;
        height: 44px;
        padding: 0 12px;
        border: 1px solid #D9D3E6;
        border-radius: 10px;
        background: #fff;
        color: var(--dh-text);
        font-family: inherit;
        font-size: 14px;
        outline: none;
    }
    .dh-modal__textarea { height: auto; padding: 10px 12px; font-size: 14px; resize: vertical; }
    .dh-modal__select:focus,
    .dh-modal__input:focus { border-color: var(--dh-primary); box-shadow: 0 0 0 3px rgba(91, 42, 134, .12); }
    .dh-modal__link {
        margin-top: 8px;
        padding: 0;
        border: none;
        background: transparent;
        color: var(--dh-primary);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }
    .dh-modal__link:hover { text-decoration: underline; }
    .dh-modal__link.hidden { display: none !important; }
    .dh-modal__msg { margin: 12px 0 0; padding: 8px 12px; border-radius: 8px; font-size: 13px; }
    .dh-modal__msg.is-success { background: #ECFDF5; color: #15803D; }
    .dh-modal__msg.is-error { background: #FFF1F2; color: #E11D48; }
    .dh-modal__submit {
        display: block;
        width: 100%;
        height: 44px;
        margin-top: 20px;
        border: none;
        border-radius: 999px;
        background: var(--dh-primary);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }
    .dh-modal__submit:hover { background: var(--dh-primary-dark); }
    .dh-modal__submit:disabled { opacity: .55; cursor: not-allowed; }

    .dark-mode .dh-card,
    .dark-mode .dh-entry,
    .dark-mode .dh-state,
    .dark-mode .wf-card { background: #1E293B; border-color: #334155; }
    .dark-mode .dh-search__field,
    .dark-mode .wf-search,
    .dark-mode .dh-history { background: #0F172A; border-color: #334155; }
    .dark-mode .dh-search__input,
    .dark-mode .wf-search__input,
    .dark-mode .dh-history__text { color: #F1F5F9; }
    .dark-mode .dh-banner { background: #0F172A; border-color: #334155; }
    .dark-mode .dh-title,
    .dark-mode .dh-entry__word,
    .dark-mode .wf-title,
    .dark-mode .wf-card__name { color: #C4B5FD; }

    @media (max-width: 767.98px) {
        .dh-card { padding: 18px 16px; border-radius: 16px; }
        .dh-title { font-size: 24px; }
        .dh-search { flex-direction: column; }
        /* Dropdown lịch sử nằm dưới cả nút "Tra từ", không che nút */
        .dh-search__field { position: static; }
        .dh-search__field, .dh-search__btn { height: 44px; }
        .dh-banner { padding: 16px 18px; min-height: 0; }
        .dh-banner__title { font-size: 18px; }
        .dh-banner__img { height: 80px; }
        .dh-entry { padding: 16px; }
        .dh-entry__word { font-size: 28px; }
        .dh-entry__level { margin-left: 0; }
        .wordlist-tabs { gap: 10px !important; }
        .wordlist-tab { min-width: 0; height: 44px; padding: 0 10px !important; font-size: 13px !important; white-space: normal !important; line-height: 1.2; gap: 8px; }
        .wordlist-tab .wordlist-tab__count { min-width: 24px; height: 24px; font-size: 12px; }
        .dh-banner__title br { display: none; }
        .dh-banner { gap: 12px; }
        .wf-head { flex-direction: column; }
        .wf-grid { grid-template-columns: 1fr; }
        .wf-card__thumb { flex-basis: 92px; width: 92px; }
        .pq-modal__dialog.dh-modal { padding: 20px 18px; }
        .dh-modal__title { font-size: 18px; }
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
            <!-- Dictionary Hub: tra từ + kết quả (kết quả thay chỗ banner) -->
            <section class="dh-card" id="dictionaryHub">
                <h1 class="dh-title">DICTIONARY HUB</h1>
                <p class="dh-sub">Tra cứu từ vựng tiếng Anh và lưu vào Word List để ôn luyện.</p>

                <label class="dh-label" for="searchInput">Tìm kiếm từ tiếng Anh</label>
                <form class="dh-search" id="dhSearchForm" role="search" autocomplete="off" novalidate>
                    <div class="dh-search__field" id="dhSearchField">
                        <span class="dh-search__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span>
                        <input type="text" class="dh-search__input" id="searchInput" maxlength="100"
                               placeholder="Nhập từ vựng" autocomplete="off" autocapitalize="off" spellcheck="false"
                               role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="dhHistory">
                        <button type="button" class="dh-search__clear" id="dhClearBtn" aria-label="Xoá nội dung tìm kiếm"><svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg></button>

                        <div class="dh-history" id="dhHistory" role="listbox" aria-label="Lịch sử tìm kiếm" hidden></div>
                    </div>
                    <button type="submit" class="dh-search__btn" id="searchBtn">Tra từ &rarr;</button>
                </form>

                <div class="dh-banner" id="dhBanner">
                    <div class="dh-banner__text">
                        <h2 class="dh-banner__title">Tra từ cực nhanh, lưu từ <br>cực gọn với Edtika</h2>
                        <p class="dh-banner__sub">Nhập từ vựng vào thanh tìm kiếm để bắt đầu tra cứu.</p>
                    </div>
                    <img class="dh-banner__img" src="{{ asset('assets/design_1/img/dictionary/dictionary-hub-mascot.png') }}" alt="" aria-hidden="true">
                </div>

                <div class="dh-results hidden" id="dictionaryResult" aria-live="polite"></div>
            </section>

            <!-- Tab bar -->
            <div class="wordlist-tabs" id="wordlistTabs">
                <button class="wordlist-tab active" data-tab="my">
                    My Word List
                    <span class="wordlist-tab__count" id="tabCountMy">{{ count($wordFolders) }}</span>
                </button>
                <button class="wordlist-tab" data-tab="academic">
                    Academic Word List
                    <span class="wordlist-tab__count">{{ count($academicWordLists) }}</span>
                </button>
            </div>

            <!-- Academic Word Lists Section -->
            <div class="word-lists-section hidden" id="academicWordListsSection">
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
                            <button class="status-chip" data-sort="alphabet">A-Z</button>
                        </div>
                        <div class="status-filters">
                            <button class="status-chip" data-status="new">Chưa học</button>
                            <button class="status-chip" data-status="learned">✔ Đã học</button>
                            <button class="status-chip" data-status="review">❗ Cần ôn tập</button>
                            <button class="status-chip" data-status="star">⭐ Gắn sao</button>
                        </div>
                        
                        <div class="words-container" id="words-{{ $wordList['id'] }}">
                            <!-- Words will be loaded here via AJAX -->
                        </div>
                        
                        <div class="action-bar">
                            <button class="action-btn btn-select-all">{{ trans('panel.select_all') }}</button>
                            <button class="action-btn btn-deselect-all">{{ trans('panel.deselect_all') }}</button>
                            <button class="action-btn btn-practice">{{ trans('panel.practice') }}</button>
                            <button class="action-btn btn-practice btn-flashcard">Luyện tập nâng cao PRO <svg class="pq-crown" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3 7.5l4.6 3.9L12 4.5l4.4 6.9L21 7.5l-1.9 11H4.9L3 7.5z"/></svg></button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- My Word List: các folder từ vựng của học viên -->
            <div class="word-lists-section" id="myWordListSection">
                {{-- Danh sách folder --}}
                <div class="wf-view" id="wfGridView">
                    <div class="wf-head">
                        <div>
                            <h2 class="wf-title">My Word List</h2>
                            <p class="wf-sub">Tạo và quản lý danh sách từ vựng cá nhân.</p>
                        </div>
                        <button type="button" class="wf-create" id="wfCreateBtn">
                            <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
                            Tạo mới
                        </button>
                    </div>

                    <div class="wf-search">
                        <span class="wf-search__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span>
                        <input type="text" class="wf-search__input" id="wfSearchInput" placeholder="Tìm Word List đã tạo, chủ đề, mô tả..." aria-label="Tìm Word List">
                        <button type="button" class="wf-search__clear" id="wfSearchClear" aria-label="Xoá tìm kiếm"><svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg></button>
                    </div>

                    <div class="wf-grid" id="wfGrid"></div>
                    <p class="wf-limit" id="wfLimitNote"></p>
                </div>

                {{-- Chi tiết 1 folder --}}
                <div class="wf-view hidden" id="wfDetailView">
                    <button type="button" class="wf-back" id="wfBackBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Tất cả Word List
                    </button>
                    <div class="word-list-card" data-list-id="{{ $myWordList->id }}" data-list-type="my">
                    <div class="word-list-header">
                        <h3 class="word-list-name" id="myWordListName">{{ $myWordList->name }}</h3>
                        <div class="wf-detail-actions">
                            <span class="word-count-badge" id="myWordListCount">Tổng số từ: {{ $myWordList->word_count }} từ</span>
                            <button type="button" class="wf-icon-btn" id="wfEditBtn" title="Sửa Word List" aria-label="Sửa Word List">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 20h4L19 9l-4-4L4 16v4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M13.5 6.5l4 4" stroke="currentColor" stroke-width="2"/></svg>
                            </button>
                            <button type="button" class="wf-icon-btn is-danger" id="wfDeleteBtn" title="Xoá Word List" aria-label="Xoá Word List">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>
                    <p class="word-list-description" id="myWordListDesc">{{ $myWordList->description }}</p>
                    
                    <!-- Expanded Content (shown by default) -->
                    <div class="word-list-expanded show" id="expanded-my-{{ $myWordList->id }}">
                        <div class="filter-bar">
                            <input type="text" class="filter-input" placeholder="{{ trans('panel.search_the_word') }}">
                            <button class="status-chip" data-sort="alphabet">A-Z</button>
                        </div>
                        <div class="status-filters">
                            <button class="status-chip" data-status="new">Chưa học</button>
                            <button class="status-chip" data-status="learned">✔ Đã học</button>
                            <button class="status-chip" data-status="review">❗ Cần ôn tập</button>
                            <button class="status-chip" data-status="star">⭐ Gắn sao</button>
                        </div>
                        
                        <div class="words-container" id="words-my-{{ $myWordList->id }}">
                            <!-- Words will be loaded here via AJAX -->
                        </div>
                        
                        <div class="action-bar">
                            <button class="action-btn btn-select-all">{{ trans('panel.select_all') }}</button>
                            <button class="action-btn btn-deselect-all">{{ trans('panel.deselect_all') }}</button>
                            <button class="action-btn btn-delete">{{ trans('panel.delete') }}</button>
                            <button class="action-btn btn-practice">{{ trans('panel.practice') }}</button>
                            <button class="action-btn btn-practice btn-flashcard">Luyện tập nâng cao PRO <svg class="pq-crown" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3 7.5l4.6 3.9L12 4.5l4.4 6.9L21 7.5l-1.9 11H4.9L3 7.5z"/></svg></button>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Practice Mode Container: Quiz (hidden by default) -->
            <div class="practice-mode-container pq-quiz hidden" id="practiceModeContainer">
                <span class="pq-quiz__badge" id="pqModeBadge">Trắc nghiệm (Quiz)</span>
                <h2 class="pq-quiz__prompt" id="pqPrompt"></h2>

                <div class="pq-quiz__options" id="pqOptions" role="radiogroup" aria-labelledby="pqPrompt">
                    <!-- Options are rendered by JS -->
                </div>

                {{-- Điền từ (Word Completion) + Nghe Viết (Dictation): dùng chung ô nhập & ô kết quả --}}
                <div class="pq-fill hidden" id="pqFill">
                    @php
                        // Chiều cao các vạch sóng âm (px), bên phải là bản đối xứng
                        $pqWaveBars = [3, 3, 5, 7, 10, 14, 22, 12, 26, 34, 16, 22, 12, 28, 44, 24, 36, 18, 10];
                    @endphp
                    <div class="pq-audio hidden" id="pqAudio">
                        <div class="pq-audio__wave" aria-hidden="true">
                            @foreach($pqWaveBars as $i => $h)
                                <span style="height: {{ $h }}px; animation-delay: -{{ ($i % 7) * 0.13 }}s;"></span>
                            @endforeach
                        </div>
                        <button type="button" class="pq-audio__play" id="pqPlayBtn" aria-label="Phát âm thanh">
                            <svg width="30" height="30" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.5v13a1 1 0 0 0 1.5.86l10.4-6.5a1 1 0 0 0 0-1.72L9.5 4.64A1 1 0 0 0 8 5.5z" fill="currentColor"/></svg>
                        </button>
                        <div class="pq-audio__wave is-right" aria-hidden="true">
                            @foreach(array_reverse($pqWaveBars) as $i => $h)
                                <span style="height: {{ $h }}px; animation-delay: -{{ ($i % 7) * 0.13 }}s;"></span>
                            @endforeach
                        </div>
                    </div>
                    <div class="pq-audio__msg" id="pqAudioMsg" role="status"></div>

                    <ul class="pq-fill__sentences" id="pqFillSentences"></ul>
                    <input type="text" class="pq-fill__input" id="pqFillInput"
                           placeholder="Điền từ vào ô trống..." aria-label="Điền từ vào ô trống"
                           autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false">
                    <div class="pq-fill__result" id="pqFillResult"></div>
                    <button type="button" class="pq-fill__hint" id="pqHintBtn">Gợi ý: <span id="pqHintText"></span></button>
                </div>

                <div class="pq-quiz__footer">
                    <button type="button" class="pq-btn pq-btn--outline" id="pqExitBtn">Thoát</button>
                    <button type="button" class="pq-btn pq-btn--soft" id="pqPrevBtn">&lt; Trước đó</button>
                    <span class="pq-quiz__counter" id="pqCounter">0 / 0</span>
                    <button type="button" class="pq-btn pq-btn--outline" id="pqSkipBtn">Bỏ qua</button>
                    <button type="button" class="pq-btn pq-btn--primary" id="pqNextBtn">Tiếp theo &gt;</button>
                </div>
            </div>

            <!-- Flashcard Practice Container (hidden by default) -->
            <div class="practice-mode-container hidden" id="flashcardPracticeContainer" style="text-align:center;">
                <div style="max-width:760px;margin:0 auto;padding:20px;">

                    <div id="flashcardCard" style="position:relative;background:#fff;border:1px solid rgba(81,29,153,0.08);border-radius:18px;padding:30px 26px;min-height:420px;display:flex;flex-direction:column;justify-content:flex-start;align-items:center; cursor: pointer;">

                        {{-- ── FRONT SIDE LABEL ───────────────────────── --}}
                        <div id="flashcardFrontLabel" style="display:inline-flex;align-items:center;justify-content:center;padding:7px 22px;border-radius:999px;background:#511D99;color:#fff;font-weight:800;font-size:13px;letter-spacing:0.06em;margin-bottom:18px;">MẶT TRƯỚC</div>
                        {{-- ── BACK SIDE LABEL ────────────────────────── --}}
                        <div id="flashcardBackLabel" style="display:none;align-items:center;justify-content:center;padding:7px 22px;border-radius:999px;background:#511D99;color:#fff;font-weight:800;font-size:13px;letter-spacing:0.06em;margin-bottom:10px;">MẶT SAU</div>

                        {{-- ── FRONT: topic + word + part of speech + illustration ── --}}
                        <div id="flashcardFrontGroup" style="width:100%;display:flex;flex-direction:column;align-items:center;">
                            <div id="flashcardTopic" style="font-size:13px;font-weight:700;letter-spacing:0.04em;color:#3b6fa8;text-transform:uppercase;margin-bottom:6px;"></div>

                            <div id="flashcardWord" style="font-size:46px;font-weight:800;color:#1e2a4a;line-height:1.1;margin-bottom:14px;text-align:center;">-</div>

                            <div id="flashcardPartOfSpeech" style="display:inline-flex;align-items:center;gap:8px;font-size:15px;color:#334155;line-height:1.2;margin-bottom:18px;"></div>

                            {{-- Ảnh tỉ lệ nào (1:1, 3:2, dọc...) cũng hiện trọn trong khung, không méo / cắt --}}
                            <div id="flashcardVisual" class="vc-media">
                                <div class="vc-media__bg" id="flashcardImageBg" aria-hidden="true"></div>
                                <img id="flashcardImage" class="vc-media__img" src="" alt="" style="display:none;">
                                <div id="flashcardImageFallback" class="vc-media__fallback">No image</div>
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

                    <div class="flashcard-status-btns">
                        <button type="button" class="flashcard-status-btn" id="fcReviewBtn">❗ Cần ôn tập</button>
                        <button type="button" class="flashcard-status-btn" id="fcStarBtn">⭐ Gắn sao</button>
                    </div>

                    <div style="display:flex;justify-content:center;align-items:center;gap:20px;margin-top:18px;flex-wrap:wrap;">
                        <button class="practice-btn btn-exit" id="flashcardExitBtn">{{ trans('panel.exit') }}</button>
                        <button class="practice-btn btn-next" id="flashcardPrevBtn" disabled>‹ Trước đó</button>
                        <span class="flashcard-counter" id="flashcardCounter">0 / 0</span>
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
<div class="word-detail-modal" id="wordDetailModal" aria-hidden="true">
    <div class="word-detail-modal__backdrop js-word-detail-close"></div>
    <div class="word-detail-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="wordDetailModalTitle">
        <div class="word-detail-modal__header">
            <div class="word-detail-modal__heading" id="wordDetailModalHeading"></div>
            <div class="word-detail-modal__actions">
                <button type="button" class="word-detail-modal__report hidden" id="wdReportBtn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 21V4.5m0 0h11.2a.8.8 0 0 1 .64 1.28L14.5 9l2.34 3.22a.8.8 0 0 1-.64 1.28H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Báo lỗi</span>
                </button>
                <button type="button" class="word-detail-modal__close js-word-detail-close" aria-label="Đóng">&times;</button>
            </div>
        </div>
        <div class="word-detail-modal__body" id="wordDetailModalBody">
            <div class="wdm-loading">Đang tải...</div>
        </div>
    </div>
</div>

{{-- ── Modal: báo lỗi từ vựng (mở từ nút "Báo lỗi" trong cửa sổ chi tiết từ) ── --}}
<div class="vw-report" id="vwReport" aria-hidden="true" hidden>
    <div class="vw-report__backdrop" data-vw-report-close></div>
    <form class="vw-report__dialog" id="vwReportForm" role="dialog" aria-modal="true"
          aria-labelledby="vwReportTitle" novalidate>
        <button type="button" class="vw-report__x" data-vw-report-close aria-label="Đóng">
            <svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
        </button>

        <h3 class="vw-report__title" id="vwReportTitle">Báo lỗi từ vựng</h3>
        <p class="vw-report__sub">Nếu bạn phát hiện nội dung từ vựng hoặc phần giải nghĩa chưa chính xác, hãy cho chúng tôi biết để cải thiện nội dung nhé!</p>

        <label class="vw-report__label" for="vwReportMessage">Mô tả chi tiết</label>
        <textarea class="vw-report__textarea" id="vwReportMessage" name="message" rows="5"
                  maxlength="{{ \App\Models\VocabularyWordReport::MESSAGE_MAX }}"
                  placeholder="Nhập nội dung cụ thể (tối đa {{ \App\Models\VocabularyWordReport::MESSAGE_MAX }} ký tự)..."></textarea>
        <div class="vw-report__count" id="vwReportCount">0/{{ \App\Models\VocabularyWordReport::MESSAGE_MAX }}</div>
        <p class="vw-report__msg" id="vwReportMsg" role="status" aria-live="polite" hidden></p>

        <div class="vw-report__actions">
            <button type="button" class="vw-report__btn vw-report__btn--outline" id="vwReportCancel" data-vw-report-close>Hủy</button>
            <button type="submit" class="vw-report__btn vw-report__btn--primary" id="vwReportSubmit" disabled>Gửi báo cáo</button>
        </div>
    </form>
</div>

{{-- ── Modal: lưu từ vào 1 Word List (folder) ──────────────── --}}
<div class="pq-modal" id="dhSaveModal" aria-hidden="true">
    <div class="pq-modal__backdrop js-dh-save-close"></div>
    <form class="pq-modal__dialog dh-modal" id="dhSaveForm" role="dialog" aria-modal="true" aria-labelledby="dhSaveTitle" novalidate>
        <button type="button" class="dh-modal__close js-dh-save-close" aria-label="Đóng"><svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg></button>
        <h3 class="dh-modal__title" id="dhSaveTitle">Lưu vào My Word List</h3>
        <p class="dh-modal__sub">Thêm &ldquo;<strong id="dhSaveWord"></strong>&rdquo; vào một bộ từ cá nhân.</p>

        <label class="dh-modal__label" for="dhSaveFolder">Chọn Word List</label>
        <select class="dh-modal__select" id="dhSaveFolder"></select>
        <button type="button" class="dh-modal__link" id="dhSaveNewFolder">+ Tạo Word List mới</button>

        <p class="dh-modal__msg" id="dhSaveMsg" role="status" aria-live="polite" hidden></p>
        <button type="submit" class="dh-modal__submit" id="dhSaveSubmit">Lưu từ vựng</button>
    </form>
</div>

{{-- ── Modal: tạo / sửa Word List (folder) ──────────────────── --}}
<div class="pq-modal" id="wfFolderModal" aria-hidden="true">
    <div class="pq-modal__backdrop js-wf-folder-close"></div>
    <form class="pq-modal__dialog dh-modal" id="wfFolderForm" role="dialog" aria-modal="true" aria-labelledby="wfFolderTitle" novalidate>
        <button type="button" class="dh-modal__close js-wf-folder-close" aria-label="Đóng"><svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg></button>
        <h3 class="dh-modal__title" id="wfFolderTitle">Tạo Word List mới</h3>
        <p class="dh-modal__sub" id="wfFolderSub">Bạn có thể tạo tối đa {{ $wordFolderLimit }} Word List.</p>

        <label class="dh-modal__label" for="wfFolderName">Tên Word List</label>
        <input type="text" class="dh-modal__input" id="wfFolderName" maxlength="60" placeholder="Ví dụ: IELTS Environment">

        <label class="dh-modal__label" for="wfFolderDesc">Mô tả <span class="dh-modal__optional">(không bắt buộc)</span></label>
        <textarea class="dh-modal__input dh-modal__textarea" id="wfFolderDesc" maxlength="255" rows="3" placeholder="Chủ đề, mục tiêu ôn luyện..."></textarea>

        <p class="dh-modal__msg" id="wfFolderMsg" role="status" aria-live="polite" hidden></p>
        <button type="submit" class="dh-modal__submit" id="wfFolderSubmit">Tạo Word List</button>
    </form>
</div>

{{-- ── Modal: chọn chế độ luyện tập ─────────────────────────── --}}
<div class="pq-modal" id="pqModeModal" aria-hidden="true">
    <div class="pq-modal__backdrop js-pq-mode-close"></div>
    <div class="pq-modal__dialog pq-mode" role="dialog" aria-modal="true" aria-labelledby="pqModeTitle">
        <button type="button" class="pq-modal__close js-pq-mode-close" aria-label="Đóng">
            <svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></svg>
        </button>

        <div class="pq-mode__icon" aria-hidden="true">
            <svg width="58" height="58" viewBox="0 0 64 64" fill="none">
                <rect x="12" y="10" width="34" height="44" rx="5" stroke="currentColor" stroke-width="4"/>
                <rect x="21" y="5" width="16" height="9" rx="3" fill="currentColor"/>
                <circle cx="21" cy="26" r="3" fill="currentColor"/>
                <path d="M28 26h11" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                <circle cx="21" cy="38" r="3" fill="currentColor"/>
                <path d="M28 38h8" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                <path d="M55 24l4 4-17 17-6 2 2-6 17-17z" fill="currentColor"/>
            </svg>
        </div>

        <h3 class="pq-mode__title" id="pqModeTitle">Thiết lập chế độ luyện tập</h3>
        <div class="pq-mode__divider"></div>

        {{-- Chỉ chọn được 1 chế độ. --}}
        <div class="pq-mode__options">
            <label class="pq-mode__option">
                <input type="checkbox" class="pq-mode__input" value="dictation">
                <span class="pq-mode__box" aria-hidden="true"></span>
                <span class="pq-mode__label">Nghe Viết (Dictation)</span>
            </label>
            <label class="pq-mode__option">
                <input type="checkbox" class="pq-mode__input" value="quiz">
                <span class="pq-mode__box" aria-hidden="true"></span>
                <span class="pq-mode__label">Trắc Nghiệm (Quiz)</span>
            </label>
            <label class="pq-mode__option">
                <input type="checkbox" class="pq-mode__input" value="completion">
                <span class="pq-mode__box" aria-hidden="true"></span>
                <span class="pq-mode__label">Điền từ (Word Completion)</span>
            </label>
        </div>

        <button type="button" class="pq-mode__start" id="pqStartBtn" disabled>Bắt đầu luyện tập</button>
    </div>
</div>

{{-- ── Modal: kết quả sau khi luyện tập ─────────────────────── --}}
<div class="pq-modal" id="pqResultModal" aria-hidden="true">
    <div class="pq-modal__backdrop js-pq-result-finish"></div>
    <div class="pq-modal__dialog pq-result" role="dialog" aria-modal="true" aria-labelledby="pqResultTitle">
        <button type="button" class="pq-result__close js-pq-result-finish" aria-label="Đóng">
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
        </button>

        <div class="pq-result__score">
            <span class="pq-result__score-label" id="pqResultTitle">Số câu đúng</span>
            <strong class="pq-result__score-value" id="pqResultScore">0 / 0</strong>
        </div>

        <div class="pq-result__cols">
            <div class="pq-result__col">
                <div class="pq-result__pill is-learned">
                    <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span>Đã học</span>
                    <span class="pq-result__sep"></span>
                    <strong id="pqLearnedCount">0</strong>
                </div>
                <ul class="pq-result__list" id="pqLearnedList"></ul>
            </div>
            <div class="pq-result__col">
                <div class="pq-result__pill is-review">
                    <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></svg>
                    <span>Cần học lại</span>
                    <span class="pq-result__sep"></span>
                    <strong id="pqReviewCount">0</strong>
                </div>
                <ul class="pq-result__list" id="pqReviewList"></ul>
            </div>
        </div>

        <div class="pq-result__actions">
            <button type="button" class="pq-btn pq-btn--outline pq-btn--lg" id="pqRetryBtn">Làm lại</button>
            <button type="button" class="pq-btn pq-btn--outline pq-btn--lg" id="pqRetryWrongBtn">Luyện từ sai</button>
            <button type="button" class="pq-btn pq-btn--primary pq-btn--lg js-pq-result-finish">Hoàn thành</button>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
(function($) {
    "use strict";

    $('#wordDetailModal').appendTo('body');

    // ── Khung ảnh minh hoạ (.vc-media): nền mờ lấy từ chính ảnh ─────────
    function vcCssUrl(url) {
        return 'url("' + String(url || '').replace(/["\\\n\r]/g, function(ch) {
            return encodeURIComponent(ch);
        }) + '")';
    }

    function vcSetMediaBg(selector, url) {
        const el = document.querySelector(selector);
        if (!el) return;
        el.style.backgroundImage = url ? vcCssUrl(url) : 'none';
    }

    let currentWordListId = null;
    let currentWordListType = null;
    let currentWordListSourceType = null;
    let currentWordListSourceId = null;
    let currentWordData = null;
    let practicedCorrectIds = new Set(); // word/flashcard IDs answered correctly in normal practice
    let lastSelectedIds = [];

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
        // Widget flashcard ở cột phải đã gỡ khỏi trang → bỏ qua, tránh lỗi JS làm dừng các bước sau renderWords()
        if (!document.getElementById('sidebarCardImage')) return;

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


    /* =================================================================
     * DICTIONARY HUB: tra từ, lịch sử tìm kiếm, lưu từ vào Word List
     * ================================================================= */
    const WF_LIMIT = {{ (int) $wordFolderLimit }};
    let wordFolders = @json($wordFolders);
    let currentFolderId = {{ (int) $myWordList->id }};

    let dhSearching = false;
    let dhEntries = [];
    let dhHistory = null;          // null = chưa tải từ server
    let dhHistoryItems = [];       // các mục đang hiện trong dropdown
    let dhHistoryActive = -1;
    let dhHistorySuppressed = false; // vừa bấm Esc / vừa tra → không tự mở lại khi lịch sử tải xong
    let dhLastFolderId = null;     // folder vừa lưu gần nhất → chọn sẵn lần sau
    let dhSaveEntry = null;
    let dhSaveTrigger = null;

    const DH_ICON_AUDIO = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>';
    const DH_ICON_PLUS = '<svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>';
    const DH_ICON_CLOCK = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="2"/><path d="M12 7.5V12l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>';
    const DH_ICON_X = '<svg width="12" height="12" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></svg>';
    const WF_ICON_FOLDER = '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3.5 7.5a2 2 0 0 1 2-2h4l2 2h7a2 2 0 0 1 2 2v7.5a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2v-9.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M3.5 10h17" stroke="currentColor" stroke-width="1.6"/></svg>';

    function dhEsc(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function dhCsrf() {
        return $('meta[name="csrf-token"]').attr('content');
    }

    function dhRegexEscape(str) {
        return String(str).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Tô đậm từ đang tra trong câu ví dụ (đã escape HTML)
    function dhHighlight(text, word) {
        const safe = dhEsc(text);
        const w = String(word || '').trim();
        if (!w) return safe;
        try {
            return safe.replace(new RegExp('(' + dhRegexEscape(dhEsc(w)) + '[a-z]*)', 'gi'), '<strong>$1</strong>');
        } catch (e) {
            return safe;
        }
    }

    function dhIpa(value) {
        const ipa = String(value || '').trim().replace(/^\/+|\/+$/g, '').trim();
        return ipa ? '/' + ipa + '/' : '';
    }

    // Đưa modal ra <body> (giữ thứ tự: modal tạo folder nằm trên modal lưu từ)
    const $dhSaveModal = $('#dhSaveModal').appendTo('body');
    const $wfFolderModal = $('#wfFolderModal').appendTo('body');

    /* ---------- Banner / kết quả ---------- */
    function dhShowBanner() {
        dhEntries = [];
        $('#dictionaryResult').addClass('hidden').empty();
        $('#dhBanner').removeClass('hidden');
    }

    function dhShowResults(html) {
        $('#dhBanner').addClass('hidden');
        $('#dictionaryResult').removeClass('hidden').html(html);
    }

    $('#dhSearchForm').on('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    $('#dhClearBtn').on('click', function() {
        dhHistorySuppressed = false;
        $('#searchInput').val('').trigger('focus');
        dhShowBanner();
        dhLoadHistory(dhRenderHistory);
    });

    function performSearch(term) {
        term = String(term === undefined ? $('#searchInput').val() : term).trim();

        if (term === '') {
            $('#searchInput').trigger('focus');
            return;
        }
        if (dhSearching) return;

        $('#searchInput').val(term);
        dhHistorySuppressed = true;
        dhCloseHistory();
        dhSearching = true;
        $('#searchBtn').prop('disabled', true).text('Đang tra...');

        dhShowResults(
            '<div class="dh-entry" aria-busy="true">'
            + '<div class="dh-skeleton" style="width:38%;height:46px"></div>'
            + '<div class="dh-skeleton" style="width:30%"></div>'
            + '<div class="dh-skeleton" style="width:80%"></div>'
            + '<div class="dh-skeleton" style="width:64%"></div>'
            + '</div>'
        );

        $.ajax({
            url: '/panel/dictionary/search-first',
            method: 'POST',
            data: { query: term, history: 1, _token: dhCsrf() }
        })
        .done(function(res) {
            if (res && res.success && res.data) {
                dhHistory = null; // tải lại lịch sử ở lần mở dropdown sau
                dhRenderEntries(res.data, term);
            } else {
                dhRenderNotFound(term);
            }
        })
        .fail(function(xhr) {
            const msg = xhr && xhr.responseJSON && xhr.responseJSON.message;
            dhShowResults(
                '<div class="dh-state"><p class="dh-state__title">Không tra được từ lúc này</p>'
                + dhEsc(msg || 'Vui lòng thử lại sau ít phút.') + '</div>'
            );
        })
        .always(function() {
            dhSearching = false;
            $('#searchBtn').prop('disabled', false).html('Tra từ &rarr;');
        });
    }

    function dhRenderNotFound(term) {
        dhEntries = [];
        dhShowResults(
            '<div class="dh-state"><p class="dh-state__title">Không tìm thấy từ &ldquo;' + dhEsc(term) + '&rdquo;</p>'
            + 'Kiểm tra lại chính tả hoặc thử tra dạng gốc của từ (ví dụ: <strong>run</strong> thay vì <strong>running</strong>).</div>'
        );
    }

    // API cũ không có entries → dựng tạm từ meanings
    function dhEntriesFromMeanings(data) {
        const pron = (data.pronunciations || [])[0] || {};
        return (data.meanings || []).map(function(m) {
            const defs = (m.definitions || []).map(function(d) { return d.definition; }).filter(Boolean);
            const examples = (m.definitions || []).map(function(d) { return d.example; }).filter(Boolean);
            return {
                part_of_speech: m.partOfSpeech || '',
                translation: '',
                definition: defs[0] || '',
                examples: examples.slice(0, 3),
                synonyms: (m.synonyms || []).slice(0, 6),
                pronunciation: pron.ipa || '',
                audio_url: pron.audio || ''
            };
        });
    }

    function dhRenderEntries(data, term) {
        currentWordData = data;
        const word = data.headword || data.word || term;
        const entries = (Array.isArray(data.entries) && data.entries.length) ? data.entries : dhEntriesFromMeanings(data);

        if (!entries.length) {
            dhRenderNotFound(term);
            return;
        }

        dhEntries = entries.map(function(e) { return Object.assign({ word: word }, e); });
        dhShowResults(dhEntries.map(dhEntryHtml).join(''));
    }

    function dhEntryHtml(e, index) {
        const word = e.word;
        const ipa = dhIpa(e.pronunciation);
        let audio = String(e.audio_url || '');
        if (audio.indexOf('//') === 0) audio = 'https:' + audio;

        let html = '<article class="dh-entry">';

        html += '<header class="dh-entry__head">'
            + '<h2 class="dh-entry__word">' + dhEsc(word) + '</h2>'
            + (e.part_of_speech ? '<span class="dh-entry__pos">' + dhEsc(posLabel(e.part_of_speech)) + '</span>' : '')
            + (ipa ? '<span class="dh-entry__ipa">' + dhEsc(ipa) + '</span>' : '')
            + '<button type="button" class="dh-entry__audio js-dh-audio" data-audio="' + dhEsc(audio) + '" data-word="' + dhEsc(word) + '" aria-label="Phát âm ' + dhEsc(word) + '">' + DH_ICON_AUDIO + '</button>'
            + (e.level ? '<span class="dh-entry__level" title="Trình độ CEFR">' + dhEsc(e.level) + '</span>' : '')
            + '</header>';

        const translation = String(e.translation || '').trim();
        const definition = String(e.definition || '').trim();

        if (translation) {
            html += '<p class="dh-entry__meaning">' + dhEsc(translation) + '</p>';
        }
        if (definition && definition.toLowerCase() !== translation.toLowerCase()) {
            html += translation
                ? '<p class="dh-entry__def">' + dhEsc(definition) + '</p>'
                : '<p class="dh-entry__meaning">' + dhEsc(definition) + '</p>';
        }

        // Collocation: dòng đầu là các cụm từ, các dòng sau (nếu có) là câu ví dụ — in nghiêng
        const collocLines = String(e.collocation || '').split(/\r?\n/).map(function(l) { return l.trim(); }).filter(Boolean);
        if (collocLines.length) {
            html += '<h3 class="dh-entry__section">Collocation</h3><div class="dh-entry__colloc">'
                + collocLines.map(function(l) { return '<p>' + dhEsc(l) + '</p>'; }).join('')
                + '</div>';
        }

        const examples = (e.examples || []).filter(Boolean);
        if (examples.length) {
            html += '<h3 class="dh-entry__section">Examples</h3><ol class="dh-entry__examples">';
            examples.forEach(function(ex, i) {
                html += '<li class="dh-entry__example"><span class="dh-entry__num">' + (i + 1) + '</span>'
                    + '<span class="dh-entry__example-text">' + dhHighlight(ex, word) + '</span></li>';
            });
            html += '</ol>';
        }

        if (Array.isArray(e.synonyms) && e.synonyms.length) {
            html += '<p class="dh-entry__syn"><strong>Từ đồng nghĩa:</strong> ' + dhEsc(e.synonyms.join(', ')) + '</p>';
        }

        html += '<button type="button" class="dh-entry__save js-dh-save" data-index="' + index + '">'
            + DH_ICON_PLUS + '<span>Lưu vào My Word List</span></button>';

        return html + '</article>';
    }

    $(document).on('click', '.js-dh-audio', function() {
        playAudio(this);
    });

    /* ---------- Lịch sử tìm kiếm ---------- */
    function dhLoadHistory(done) {
        if (dhHistory !== null) {
            if (typeof done === 'function') done();
            return;
        }

        $.get('/panel/dictionary/search-history')
            .done(function(res) {
                dhHistory = (res && res.success && Array.isArray(res.data)) ? res.data : [];
            })
            .fail(function() {
                dhHistory = [];
            })
            .always(function() {
                if (typeof done === 'function') done();
            });
    }

    function dhCloseHistory() {
        dhHistoryItems = [];
        dhHistoryActive = -1;
        $('#dhHistory').prop('hidden', true).empty();
        $('#searchInput').attr('aria-expanded', 'false').removeAttr('aria-activedescendant');
    }

    function dhRenderHistory() {
        const $input = $('#searchInput');
        if (!$input.is(':focus') || dhHistorySuppressed) return;

        const q = String($input.val() || '').trim().toLowerCase();
        dhHistoryItems = (dhHistory || []).filter(function(h) {
            return !q || String(h.query).indexOf(q) !== -1;
        }).slice(0, 10);

        if (!dhHistoryItems.length) {
            dhCloseHistory();
            return;
        }

        let html = '<div class="dh-history__head"><span>Tìm kiếm gần đây</span>'
            + '<button type="button" class="dh-history__clear-all" data-history-clear>Xoá tất cả</button></div>';

        dhHistoryItems.forEach(function(h, i) {
            let text = dhEsc(h.query);
            if (q) {
                text = text.replace(new RegExp('(' + dhRegexEscape(dhEsc(q)) + ')', 'i'), '<mark>$1</mark>');
            }
            html += '<div class="dh-history__item" role="option" id="dhHistOpt' + i + '" data-index="' + i + '" aria-selected="false">'
                + '<span class="dh-history__icon">' + DH_ICON_CLOCK + '</span>'
                + '<span class="dh-history__text">' + text + '</span>'
                + '<button type="button" class="dh-history__remove" data-history-remove="' + i + '" aria-label="Xoá &ldquo;' + dhEsc(h.query) + '&rdquo; khỏi lịch sử">' + DH_ICON_X + '</button>'
                + '</div>';
        });

        dhHistoryActive = -1;
        $('#dhHistory').html(html).prop('hidden', false);
        $input.attr('aria-expanded', 'true');
    }

    function dhSetHistoryActive(index) {
        const $items = $('#dhHistory .dh-history__item');
        if (!$items.length) return;

        dhHistoryActive = (index + $items.length) % $items.length;
        $items.removeClass('is-active').attr('aria-selected', 'false');

        const $active = $items.eq(dhHistoryActive).addClass('is-active').attr('aria-selected', 'true');
        $('#searchInput').attr('aria-activedescendant', $active.attr('id'));
        $active[0].scrollIntoView({ block: 'nearest' });
    }

    $('#searchInput')
        .on('focus click', function() {
            dhHistorySuppressed = false;
            dhLoadHistory(dhRenderHistory);
        })
        .on('input', function() {
            dhHistorySuppressed = false;
            dhRenderHistory();
        })
        .on('keydown', function(e) {
            const open = !$('#dhHistory').prop('hidden');

            if (e.key === 'ArrowDown' && open) {
                e.preventDefault();
                dhSetHistoryActive(dhHistoryActive + 1);
            } else if (e.key === 'ArrowUp' && open) {
                e.preventDefault();
                dhSetHistoryActive(dhHistoryActive - 1);
            } else if (e.key === 'Enter' && open && dhHistoryActive >= 0 && dhHistoryItems[dhHistoryActive]) {
                e.preventDefault();
                performSearch(dhHistoryItems[dhHistoryActive].query);
            } else if (e.key === 'Escape') {
                dhHistorySuppressed = true;
                if (open) {
                    e.stopPropagation();
                    dhCloseHistory();
                }
            }
        });

    // mousedown: chạy trước khi ô tìm kiếm mất focus
    $('#dhHistory').on('mousedown', function(e) {
        e.preventDefault();

        const $remove = $(e.target).closest('[data-history-remove]');
        if ($remove.length) {
            const item = dhHistoryItems[parseInt($remove.attr('data-history-remove'), 10)];
            if (!item) return;

            dhHistory = (dhHistory || []).filter(function(h) { return h.query !== item.query; });
            dhRenderHistory();

            if (item.id) {
                $.post('/panel/dictionary/search-history/delete', { id: item.id, _token: dhCsrf() });
            }
            return;
        }

        if ($(e.target).closest('[data-history-clear]').length) {
            dhHistory = [];
            dhCloseHistory();
            $.post('/panel/dictionary/search-history/delete', { all: 1, _token: dhCsrf() });
            return;
        }

        const $item = $(e.target).closest('.dh-history__item');
        if ($item.length) {
            const picked = dhHistoryItems[parseInt($item.attr('data-index'), 10)];
            if (picked) performSearch(picked.query);
        }
    });

    $('#dhSearchField').on('focusout', function() {
        setTimeout(function() {
            if (!$.contains(document.getElementById('dhSearchField'), document.activeElement)) {
                dhCloseHistory();
            }
        }, 120);
    });

    /* ---------- Lưu từ vào Word List ---------- */
    function dhFillFolderSelect(selectedId) {
        const $select = $('#dhSaveFolder').empty();

        wordFolders.forEach(function(f) {
            $('<option></option>')
                .val(f.id)
                .text(f.name + ' (' + (f.word_count || 0) + ' từ)')
                .appendTo($select);
        });

        const wanted = wfFolderById(selectedId) ? selectedId
            : (wfFolderById(dhLastFolderId) ? dhLastFolderId
            : (wordFolders[0] ? wordFolders[0].id : ''));
        $select.val(String(wanted));

        $('#dhSaveNewFolder').toggleClass('hidden', wordFolders.length >= WF_LIMIT);
    }

    function dhSaveMessage(text, type) {
        $('#dhSaveMsg').text(text || '')
            .attr('class', 'dh-modal__msg' + (type ? ' is-' + type : ''))
            .prop('hidden', !text);
    }

    $(document).on('click', '.js-dh-save', function() {
        const entry = dhEntries[parseInt($(this).attr('data-index'), 10)];
        if (!entry) return;

        dhSaveEntry = entry;
        dhSaveTrigger = this;

        $('#dhSaveWord').text(entry.word);
        dhFillFolderSelect(null);
        dhSaveMessage('', '');
        $('#dhSaveSubmit').prop('disabled', false).text('Lưu từ vựng');

        pqOpenModal($dhSaveModal);
        setTimeout(function() { $('#dhSaveFolder').trigger('focus'); }, 30);
    });

    function dhCloseSaveModal() {
        pqCloseModal($dhSaveModal);
        if (dhSaveTrigger && document.body.contains(dhSaveTrigger)) dhSaveTrigger.focus();
    }

    $(document).on('click', '.js-dh-save-close', dhCloseSaveModal);

    $('#dhSaveNewFolder').on('click', function() {
        wfOpenFolderModal(null, function(folder) {
            dhFillFolderSelect(folder.id);
        });
    });

    $('#dhSaveForm').on('submit', function(e) {
        e.preventDefault();

        const entry = dhSaveEntry;
        const folderId = $('#dhSaveFolder').val();
        if (!entry || !folderId) return;

        const $submit = $('#dhSaveSubmit').prop('disabled', true).text('Đang lưu...');
        dhSaveMessage('', '');

        $.ajax({
            url: '/panel/dictionary/my-word-list/add-word',
            method: 'POST',
            data: {
                _token: dhCsrf(),
                folder_id: folderId,
                word: entry.word,
                part_of_speech: entry.part_of_speech || '',
                definition: entry.definition || '',
                translation: entry.translation || '',
                pronunciation: String(entry.pronunciation || '').replace(/^\/+|\/+$/g, '').trim(),
                example: (entry.examples || []).join('\n'),
                collocation: entry.collocation || '',
                audio_url: entry.audio_url || ''
            }
        })
        .done(function(res) {
            if (!res || !res.success) {
                dhSaveMessage((res && res.message) || 'Không lưu được từ, hãy thử lại.', 'error');
                $submit.prop('disabled', false).text('Lưu từ vựng');
                return;
            }

            const folder = res.data && res.data.folder;
            if (folder) {
                wfUpsertFolder(folder);
                dhLastFolderId = folder.id;

                // Đang mở đúng folder này thì tải lại danh sách từ
                if (String(folder.id) === String(currentFolderId) && !$('#wfDetailView').hasClass('hidden')) {
                    loadMyWordList();
                }
            }

            dhSaveMessage(res.message || 'Đã lưu từ vựng.', 'success');
            $submit.text('Đã lưu ✓');

            if (dhSaveTrigger) {
                $(dhSaveTrigger).addClass('is-saved').find('span').text('Đã lưu' + (folder ? ' vào ' + folder.name : '') + ' · Lưu thêm');
            }

            setTimeout(dhCloseSaveModal, 900);
        })
        .fail(function(xhr) {
            const data = (xhr && xhr.responseJSON) || {};
            const firstError = data.errors ? Object.values(data.errors)[0] : null;
            dhSaveMessage(
                (Array.isArray(firstError) ? firstError[0] : firstError) || data.message
                    || (xhr && xhr.status === 419 ? 'Phiên làm việc đã hết hạn, hãy tải lại trang.' : 'Không lưu được từ, hãy thử lại.'),
                'error'
            );
            $submit.prop('disabled', false).text('Lưu từ vựng');
        });
    });

    /* =================================================================
     * MY WORD LIST: các folder từ vựng (tối đa WF_LIMIT)
     * ================================================================= */
    let wfFolderModalCallback = null;
    let wfEditingId = null;

    function wfFolderById(id) {
        if (id === null || id === undefined || id === '') return null;
        return wordFolders.find(function(f) { return String(f.id) === String(id); }) || null;
    }

    function wfUpsertFolder(folder) {
        const index = wordFolders.findIndex(function(f) { return String(f.id) === String(folder.id); });
        if (index === -1) {
            wordFolders.push(folder);
        } else {
            wordFolders[index] = Object.assign({}, wordFolders[index], folder);
        }
        wfRenderGrid();
    }

    function wfCardHtml(f) {
        const count = parseInt(f.word_count, 10) || 0;
        return '<article class="wf-card" data-folder-id="' + dhEsc(f.id) + '">'
            + '<button type="button" class="wf-card__thumb js-wf-open" aria-label="Mở ' + dhEsc(f.name) + '">'
            + WF_ICON_FOLDER + '<span class="wf-card__thumb-label">' + dhEsc(f.name) + '</span></button>'
            + '<div class="wf-card__body">'
            + '<button type="button" class="wf-card__name js-wf-open">' + dhEsc(f.name) + '</button>'
            + (f.description ? '<p class="wf-card__desc">' + dhEsc(f.description) + '</p>' : '')
            + '<div class="wf-card__foot"><span class="wf-card__count">' + count + ' từ</span>'
            + '<button type="button" class="wf-card__practice js-wf-practice"' + (count ? '' : ' disabled title="Word List chưa có từ nào"') + '>Luyện tập</button>'
            + '</div></div></article>';
    }

    function wfRenderGrid() {
        const q = String($('#wfSearchInput').val() || '').trim().toLowerCase();
        const list = wordFolders.filter(function(f) {
            return !q || (String(f.name) + ' ' + String(f.description || '')).toLowerCase().indexOf(q) !== -1;
        });

        $('#wfGrid').html(list.length
            ? list.map(wfCardHtml).join('')
            : '<div class="wf-empty">' + (q ? 'Không tìm thấy Word List phù hợp.' : 'Chưa có Word List nào.') + '</div>');

        const full = wordFolders.length >= WF_LIMIT;
        $('#tabCountMy').text(wordFolders.length);
        $('#wfCreateBtn').prop('disabled', full)
            .attr('title', full ? 'Bạn đã tạo tối đa ' + WF_LIMIT + ' Word List' : '');
        $('#wfLimitNote').text('Đã tạo ' + wordFolders.length + '/' + WF_LIMIT + ' Word List'
            + (full ? ' · xoá bớt Word List để tạo mới.' : '.'));
    }

    function wfRefreshFolders() {
        $.get('/panel/dictionary/folders').done(function(res) {
            if (res && res.success && res.data) {
                wordFolders = res.data.folders || [];
                wfRenderGrid();
            }
        });
    }

    function wfDefaultFolder() {
        return wordFolders.find(function(f) { return f.is_default; }) || wordFolders[0] || null;
    }

    function wfShowGrid() {
        lastSelectedIds = [];
        $('#wfDetailView').addClass('hidden');
        $('#wfGridView').removeClass('hidden');
    }

    function wfOpenFolder(id, opts) {
        const folder = wfFolderById(id);
        if (!folder) return;

        currentFolderId = folder.id;
        lastSelectedIds = [];
        updateMyWordListMeta(folder);

        $('#wfGridView').addClass('hidden');
        $('#wfDetailView').removeClass('hidden');
        $('#wfDetailView .word-list-expanded').addClass('show');
        $('#myWordListSection .words-container').first().html('<p class="wf-words-empty">Đang tải...</p>');

        loadMyWordList(function() {
            if (opts && opts.practice) wfPracticeAll();
        });
    }

    // Nút "Luyện tập" trên thẻ folder: chọn sẵn tất cả từ rồi mở modal chọn dạng bài
    function wfPracticeAll() {
        const $expanded = $('#wfDetailView .word-list-expanded');
        const $boxes = $expanded.find('.word-checkbox');

        if (!$boxes.length) {
            alert('Word List này chưa có từ nào để luyện tập.');
            return;
        }

        $boxes.prop('checked', true);
        $expanded.find('.btn-practice:not(.btn-flashcard)').first().trigger('click');
    }

    $(document).on('click', '.js-wf-open', function() {
        wfOpenFolder($(this).closest('.wf-card').attr('data-folder-id'));
    });

    $(document).on('click', '.js-wf-practice', function() {
        wfOpenFolder($(this).closest('.wf-card').attr('data-folder-id'), { practice: true });
    });

    $('#wfBackBtn').on('click', function() {
        wfShowGrid();
        wfRenderGrid();
    });

    $('#wfSearchInput').on('input', wfRenderGrid);
    $('#wfSearchClear').on('click', function() {
        $('#wfSearchInput').val('').trigger('focus');
        wfRenderGrid();
    });

    /* ---------- Tạo / sửa / xoá folder ---------- */
    function wfFolderMessage(text, type) {
        $('#wfFolderMsg').text(text || '')
            .attr('class', 'dh-modal__msg' + (type ? ' is-' + type : ''))
            .prop('hidden', !text);
    }

    function wfOpenFolderModal(folder, callback) {
        if (!folder && wordFolders.length >= WF_LIMIT) {
            alert('Bạn chỉ có thể tạo tối đa ' + WF_LIMIT + ' Word List.');
            return;
        }

        wfEditingId = folder ? folder.id : null;
        wfFolderModalCallback = typeof callback === 'function' ? callback : null;

        $('#wfFolderTitle').text(folder ? 'Sửa Word List' : 'Tạo Word List mới');
        $('#wfFolderSub').text(folder
            ? 'Đổi tên hoặc mô tả cho Word List của bạn.'
            : 'Đã tạo ' + wordFolders.length + '/' + WF_LIMIT + ' Word List.');
        $('#wfFolderName').val(folder ? folder.name : '');
        $('#wfFolderDesc').val(folder ? (folder.description || '') : '');
        $('#wfFolderSubmit').prop('disabled', false).text(folder ? 'Lưu thay đổi' : 'Tạo Word List');
        wfFolderMessage('', '');

        pqOpenModal($wfFolderModal);
        setTimeout(function() { $('#wfFolderName').trigger('focus'); }, 30);
    }

    $(document).on('click', '.js-wf-folder-close', function() {
        pqCloseModal($wfFolderModal);
    });

    $('#wfCreateBtn').on('click', function() {
        wfOpenFolderModal(null, function(folder) {
            wfOpenFolder(folder.id);
        });
    });

    $('#wfEditBtn').on('click', function(e) {
        e.stopPropagation();
        const folder = wfFolderById(currentFolderId);
        if (folder) wfOpenFolderModal(folder);
    });

    $('#wfFolderForm').on('submit', function(e) {
        e.preventDefault();

        const name = String($('#wfFolderName').val() || '').trim();
        if (!name) {
            wfFolderMessage('Hãy nhập tên Word List.', 'error');
            $('#wfFolderName').trigger('focus');
            return;
        }

        const editingId = wfEditingId;
        const $submit = $('#wfFolderSubmit').prop('disabled', true).text('Đang lưu...');
        wfFolderMessage('', '');

        $.ajax({
            url: editingId
                ? '/panel/dictionary/folders/' + encodeURIComponent(editingId) + '/update'
                : '/panel/dictionary/folders/create',
            method: 'POST',
            data: { _token: dhCsrf(), name: name, description: String($('#wfFolderDesc').val() || '').trim() }
        })
        .done(function(res) {
            if (!res || !res.success || !res.data) {
                wfFolderMessage((res && res.message) || 'Không lưu được Word List.', 'error');
                $submit.prop('disabled', false).text(editingId ? 'Lưu thay đổi' : 'Tạo Word List');
                return;
            }

            wfUpsertFolder(res.data);
            if (String(res.data.id) === String(currentFolderId)) {
                updateMyWordListMeta(res.data);
            }

            pqCloseModal($wfFolderModal);

            const cb = wfFolderModalCallback;
            wfFolderModalCallback = null;
            if (cb) cb(res.data);
        })
        .fail(function(xhr) {
            const data = (xhr && xhr.responseJSON) || {};
            const firstError = data.errors ? Object.values(data.errors)[0] : null;
            wfFolderMessage(
                (Array.isArray(firstError) ? firstError[0] : firstError) || data.message || 'Không lưu được Word List.',
                'error'
            );
            $submit.prop('disabled', false).text(editingId ? 'Lưu thay đổi' : 'Tạo Word List');
        });
    });

    $('#wfDeleteBtn').on('click', function(e) {
        e.stopPropagation();

        const folder = wfFolderById(currentFolderId);
        if (!folder || folder.is_default) return;

        if (!confirm('Xoá Word List "' + folder.name + '"?\nCác từ chỉ nằm trong Word List này cũng sẽ bị xoá.')) {
            return;
        }

        $.post('/panel/dictionary/folders/' + encodeURIComponent(folder.id) + '/delete', { _token: dhCsrf() })
            .done(function(res) {
                if (!res || !res.success) {
                    alert((res && res.message) || 'Không xoá được Word List.');
                    return;
                }

                wordFolders = wordFolders.filter(function(f) { return String(f.id) !== String(folder.id); });
                const fallback = wfDefaultFolder();
                currentFolderId = fallback ? fallback.id : null;

                wfShowGrid();
                wfRenderGrid();
            })
            .fail(function(xhr) {
                alert((xhr && xhr.responseJSON && xhr.responseJSON.message) || 'Không xoá được Word List.');
            });
    });

    $(document).on('keydown', function(e) {
        if (e.key !== 'Escape') return;

        if ($wfFolderModal.hasClass('is-open')) {
            pqCloseModal($wfFolderModal);
        } else if ($dhSaveModal.hasClass('is-open')) {
            dhCloseSaveModal();
        }
    });

    // Hiện danh sách folder ngay khi mở trang (tab My Word List là tab mặc định)
    wfRenderGrid();

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

        // ── Tab switching ────────────────────────────────────────────────
    $(document).on('click', '.wordlist-tab', function() {
        const tab = $(this).data('tab');
        lastSelectedIds = []; 

        $('.wordlist-tab').removeClass('active');
        $(this).addClass('active');

        // Đang trong chế độ luyện tập thì thoát trước
        pqClearTimer();
        $('#practiceModeContainer, #flashcardPracticeContainer').addClass('hidden');

        if (tab === 'my') {
            $('#academicWordListsSection').addClass('hidden');
            $('#myWordListSection').removeClass('hidden');

            // Về danh sách folder và đồng bộ lại số từ
            wfShowGrid();
            wfRenderGrid();
            wfRefreshFolders();
        } else {
            $('#myWordListSection').addClass('hidden');
            $('#academicWordListsSection').removeClass('hidden');
        }
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

        // Folder My Word List luôn mở sẵn (quay lại bằng nút "Tất cả Word List")
        if (listType === 'my') return;

        let sourceId = $card.data('source-id');
        let sourceType = $card.data('source-type');
        let expandedSection = $card.find('.word-list-expanded');

        if (expandedSection.hasClass('show')) {
            expandedSection.removeClass('show');
        } else {
            lastSelectedIds = [];
            // Chỉ thu gọn các bộ từ Academic khác — không đụng tới folder My Word List
            $('#academicWordListsSection .word-list-expanded').removeClass('show');
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

    // Load từ của folder đang mở (currentFolderId)
    function loadMyWordList(done) {
        const folderId = currentFolderId;

        $.ajax({
            url: '/panel/dictionary/my-word-list',
            method: 'GET',
            data: folderId ? { folder_id: folderId } : {},
            success: function(response) {
                if (!response.success || String(response.data.id) !== String(currentFolderId || response.data.id)) return;

                const words = response.data.flashcards || [];
                updateMyWordListMeta(response.data);
                renderWords(words, response.data.id, 'my', 'my', response.data.id);

                if (!words.length) {
                    $('#myWordListSection .words-container').first().html(
                        '<p class="wf-words-empty">Word List này chưa có từ nào. Tra từ ở Dictionary Hub phía trên rồi bấm "Lưu vào My Word List".</p>'
                    );
                }

                if (typeof done === 'function') done(response.data);
            },
            error: function(xhr) {
                console.error('Error loading my word list:', xhr);
                // Folder đã bị xoá ở tab khác → quay về danh sách folder
                if (xhr && xhr.status === 404) {
                    const fallback = wfDefaultFolder();
                    currentFolderId = fallback ? fallback.id : null;
                    wfShowGrid();
                    wfRefreshFolders();
                }
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
            let pronunciation = word.pronunciation ? `<span class="word-pronunciation">${dhEsc(dhIpa(word.pronunciation))}</span>` : '';
            let wordSource = word.word_source || sourceType || listType;

            let needsReview = !!word.needs_review;
            let isStarred   = !!word.is_starred;
            let isLearned   = (!!word.is_learned || practicedCorrectIds.has(word.id)) && !needsReview;

            let wordHtml = `
                <div class="word-item" data-word-id="${dhEsc(word.id)}" data-word="${dhEsc(word.word)}"
                     data-word-source="${dhEsc(wordSource)}"
                     data-reportable="${word.can_report ? 1 : 0}"
                     data-learned="${isLearned ? 1 : 0}"
                     data-review="${needsReview ? 1 : 0}"
                     data-star="${isStarred ? 1 : 0}">
                    <input type="checkbox" class="word-checkbox" data-word-id="${word.id}">
                    <div class="word-content">
                        <div class="word-title-row">
                            <span class="word-text">${dhEsc(word.word)}</span>
                            ${pronunciation}
                        </div>
                        <div class="word-definition">${dhEsc(listType === 'my' && word.translation ? word.translation : word.definition)}</div>
                    </div>
                    <div class="word-badges">
                        <span class="word-badge badge-learned ${isLearned ? 'on' : ''}"
                              title="Đã học" style="${isLearned ? '' : 'display:none;'}">&#10004;</span>
                        <span class="word-badge badge-review ${needsReview ? 'on' : ''}"
                              data-field="needs_review" title="Cần ôn tập">&#10071;</span>
                        <span class="word-badge badge-star ${isStarred ? 'on' : ''}"
                              data-field="is_starred" title="Gắn sao">&#9733;</span>
                    </div>
                </div>
            `;
            container.append(wordHtml);
        });

        currentWordListId = listId;
        currentWordListType = listType;
        currentWordListSourceType = sourceType || listType;
        currentWordListSourceId = sourceId || listId;

        restoreSelection();

        // Sidebar widget must follow the currently opened vocabulary set.
        setSidebarCardsFromWords(words);
    }

    function restoreSelection() {
        if (!lastSelectedIds.length) return;

        lastSelectedIds.forEach(function(id) {
            $('.word-checkbox[data-word-id="' + id + '"]').prop('checked', true);
        });
    }

    function updateMyWordListMeta(data) {
        if (!data) return;

        const totalWords = Number(data.word_count || 0);

        $('#myWordListSection .word-list-card[data-list-type="my"]')
            .attr('data-list-id', data.id);

        $('#myWordListName').text(data.name || 'My Word List');
        $('#myWordListDesc').text(data.description || '');
        $('#myWordListCount').text('Tổng số từ: ' + totalWords + ' từ');
        $('#wfDeleteBtn').toggleClass('hidden', !!data.is_default);

        // Đồng bộ số từ trên thẻ folder
        const folder = wfFolderById(data.id);
        if (folder) {
            folder.word_count = totalWords;
            folder.name = data.name || folder.name;
            folder.description = data.description;
            wfRenderGrid();
        }
    }

        // Bật/tắt "cần ôn tập" và "gắn sao"
    $(document).on('click', '.word-badge[data-field]', function(e) {
        e.stopPropagation();

        const $badge = $(this);
        const $item  = $badge.closest('.word-item');
        const field  = $badge.data('field');
        const value  = $badge.hasClass('on') ? 0 : 1;
        const source = $item.data('word-source');
        const id     = $item.data('word-id');

        const data = {
            field: field,
            value: value,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        if (source === 'academic') { data.word_id = id; } else { data.flashcard_id = id; }

        $.post('/panel/dictionary/word-status/toggle', data, function(res) {
            if (!res.success) return;

            $badge.toggleClass('on', !!value);

            if (field === 'needs_review') {
                $item.attr('data-review', res.needs_review ? 1 : 0);
                $item.attr('data-learned', res.is_learned ? 1 : 0);
                $item.find('.badge-learned')
                     .toggleClass('on', !!res.is_learned)
                     .toggle(!!res.is_learned);
            } else {
                $item.attr('data-star', res.is_starred ? 1 : 0);
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
        lastSelectedIds = []; 
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
                folder_id: currentFolderId,
                flashcard_ids: selectedIds,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    lastSelectedIds = lastSelectedIds.filter(function(id) {   // ← THÊM
                        return selectedIds.indexOf(id) === -1;                 // ← THÊM
                    });      
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

    /* =================================================================
     * LUYỆN TẬP: chọn chế độ -> Quiz -> kết quả
     * ================================================================= */
    const PQ_REVEAL_DELAY = 1000;   // ms giữ trạng thái "vừa chọn" (tím) trước khi hiện đúng/sai
    const PQ_MODE_LABELS = {
        quiz: 'Trắc nghiệm (Quiz)',
        dictation: 'Nghe Viết (Dictation)',
        completion: 'Điền từ (Word Completion)'
    };

    const pq = {
        ctx: null,          // bộ từ đang luyện: { listId, listType, sourceType, sourceId }
        mode: null,         // 'quiz' | 'dictation' | 'completion'
        roundIds: [],       // id các từ của vòng hiện tại (dùng cho "Làm lại")
        questions: [],
        answers: [],        // mỗi phần tử: null | { selected, status: 'pending'|'correct'|'incorrect'|'skipped' }
        hints: [],          // Điền từ: true = đã mở gợi ý 50%
        drafts: [],         // Điền từ: chữ đang gõ dở của từng câu
        index: 0,
        pending: false,
        timer: null,
        loading: false
    };

    const PQ_ICON_CHECK = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    const PQ_ICON_CROSS = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>';

    function pqEsc(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function pqCsrf() {
        return $('meta[name="csrf-token"]').attr('content');
    }

    function pqOpenModal($modal) {
        $modal.addClass('is-open').attr('aria-hidden', 'false');
        $('body').css('overflow', 'hidden');
    }

    function pqCloseModal($modal) {
        $modal.removeClass('is-open').attr('aria-hidden', 'true');
        if (!$('.pq-modal.is-open').length) {
            $('body').css('overflow', '');
        }
    }

    function pqClearTimer() {
        pqStopAudio();
        if (pq.timer) {
            clearTimeout(pq.timer);
            pq.timer = null;
        }
        pq.pending = false;
    }

    // Đưa modal ra <body> để không bị cắt bởi layout panel
    const $pqModeModal = $('#pqModeModal').appendTo('body');
    const $pqResultModal = $('#pqResultModal').appendTo('body');

    // ── 1. Nhấn "Luyện tập" (không phải nút PRO) → mở modal chọn chế độ ──
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

        lastSelectedIds = selectedIds.slice();
        pq.roundIds = selectedIds.slice();
        pq.ctx = {
            listId: currentWordListId,
            listType: currentWordListType,
            sourceType: currentWordListSourceType,
            sourceId: currentWordListSourceId
        };

        // Mặc định không tích sẵn ô nào
        $pqModeModal.find('.pq-mode__input').prop('checked', false);
        $('#pqStartBtn').prop('disabled', true);
        pqOpenModal($pqModeModal);
    });

    // Chỉ cho chọn 1 trong 3 chế độ
    $(document).on('change', '.pq-mode__input', function() {
        if (this.checked) {
            $('.pq-mode__input').not(this).prop('checked', false);
        }
        $('#pqStartBtn').prop('disabled', !$('.pq-mode__input:checked').length);
    });

    $(document).on('click', '.js-pq-mode-close', function() {
        pqCloseModal($pqModeModal);
    });

    $('#pqStartBtn').on('click', function() {
        const mode = $('.pq-mode__input:checked').val();
        if (!mode) return;

        pqCloseModal($pqModeModal);
        pq.mode = mode;

        if (mode === 'quiz' || mode === 'completion' || mode === 'dictation') {
            pqStartSession(pq.roundIds);
        } else {
            alert('Chế độ này đang được phát triển.');
        }
    });

    // ── 2. Tải câu hỏi (dùng chung cho Quiz / Điền từ) ───────────
    function pqStartSession(wordIds) {
        if (pq.loading || !pq.ctx || !wordIds.length) return;

        const ctx = pq.ctx;
        let url = '/panel/dictionary/practice/start-my-word-list';
        const data = { _token: pqCsrf() };

        if (ctx.listType === 'academic') {
            if (ctx.sourceType === 'bundle') {
                url = '/panel/dictionary/practice/start-bundle-word-list';
                data.vocabulary_set_id = ctx.sourceId;
                data.flashcard_ids = wordIds;
            } else {
                url = '/panel/dictionary/practice/start';
                data.word_list_id = ctx.sourceId;
                data.word_ids = wordIds;
            }
        } else {
            data.folder_id = ctx.listId;
            data.flashcard_ids = wordIds;
        }

        pq.loading = true;

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                if (!response.success || !response.data || !(response.data.questions || []).length) {
                    alert((response && response.message) || '{{ trans('panel.failed_to_start_practice') }}');
                    return;
                }

                pqClearTimer();
                pq.roundIds = wordIds.slice();
                pq.questions = response.data.questions.map(pqNormalizeQuestion);
                pq.answers = pq.questions.map(function() { return null; });
                pq.hints = pq.questions.map(function() { return false; });
                pq.drafts = pq.questions.map(function() { return ''; });
                pq.index = 0;

                $('#pqModeBadge').text(PQ_MODE_LABELS[pq.mode] || PQ_MODE_LABELS.quiz);
                $('#flashcardPracticeContainer, #dictionaryResult').addClass('hidden');
                $('#academicWordListsSection, #myWordListSection').addClass('hidden');
                $('#practiceModeContainer').removeClass('hidden');

                pqRenderQuestion();

                const top = $('#practiceModeContainer').offset().top - 90;
                if (window.scrollY > top) {
                    window.scrollTo({ top: Math.max(top, 0), behavior: 'smooth' });
                }
            },
            error: function(xhr) {
                console.error('Error starting practice:', xhr);
                const msg = xhr && xhr.responseJSON && xhr.responseJSON.message;
                alert(msg || '{{ trans('panel.failed_to_start_practice') }}');
            },
            complete: function() {
                pq.loading = false;
            }
        });
    }

    // API Academic và My/Bundle trả về hơi khác nhau → gom về 1 dạng
    function pqNormalizeQuestion(q) {
        const isAcademic = !!q.word_id;
        return {
            id: isAcademic ? q.word_id : q.flashcard_id,
            idKey: isAcademic ? 'word_id' : 'flashcard_id',
            prompt: q.question || q.definition || '',
            correct: String(q.correct_answer || ''),
            options: (q.answers || []).map(function(a) {
                return String((a && typeof a === 'object') ? (a.word || '') : a);
            }),
            sentencesHtml: pqBuildSentencesHtml(q),
            audioUrl: q.audio_url || ''
        };
    }

    /* ---------- Nghe Viết: phát âm thanh ----------
     * Ưu tiên file audio của từ (từ giáo viên upload). Không có, hoặc file lỗi,
     * thì dùng giọng đọc tiếng Anh của trình duyệt (Web Speech API). */
    let pqAudioEl = null;
    let pqAudioToken = 0;

    function pqSetPlaying(on) {
        $('#pqAudio').toggleClass('is-playing', !!on);
    }

    function pqStopAudio() {
        pqAudioToken++;
        if (pqAudioEl) {
            pqAudioEl.pause();
            pqAudioEl.onended = pqAudioEl.onerror = null;
            pqAudioEl = null;
        }
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }
        pqSetPlaying(false);
    }

    function pqPickVoice() {
        if (!('speechSynthesis' in window)) return null;
        const voices = window.speechSynthesis.getVoices() || [];
        return voices.find(function(v) { return /^en[-_]US/i.test(v.lang); })
            || voices.find(function(v) { return /^en[-_]GB/i.test(v.lang); })
            || voices.find(function(v) { return /^en/i.test(v.lang); })
            || null;
    }

    function pqSpeak(text, token) {
        if (!('speechSynthesis' in window) || typeof SpeechSynthesisUtterance === 'undefined') {
            $('#pqAudioMsg').text('Trình duyệt không hỗ trợ phát âm. Vui lòng dùng Chrome, Edge hoặc Safari bản mới.');
            pqSetPlaying(false);
            return;
        }

        const utter = new SpeechSynthesisUtterance(text);
        const voice = pqPickVoice();
        utter.lang = voice ? voice.lang : 'en-US';
        if (voice) utter.voice = voice;
        utter.rate = 0.85;
        utter.onstart = function() { if (token === pqAudioToken) pqSetPlaying(true); };
        utter.onend = utter.onerror = function() { if (token === pqAudioToken) pqSetPlaying(false); };

        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utter);
        pqSetPlaying(true);
    }

    function pqPlayCurrent() {
        const q = pq.questions[pq.index];
        if (!q) return;

        pqStopAudio();
        $('#pqAudioMsg').text('');
        const token = pqAudioToken;

        let url = String(q.audioUrl || '').trim();
        if (!url) {
            pqSpeak(q.correct, token);
            return;
        }
        if (url.indexOf('//') === 0) url = 'https:' + url;

        const audio = new Audio(url);
        pqAudioEl = audio;
        pqSetPlaying(true);

        // onerror và play().catch có thể cùng xảy ra → chỉ fallback 1 lần
        const fallback = function() {
            if (token !== pqAudioToken || pqAudioEl !== audio) return;
            pqAudioEl = null;
            pqSpeak(q.correct, token);
        };

        audio.onended = function() { if (token === pqAudioToken) pqSetPlaying(false); };
        audio.onerror = fallback;

        const playing = audio.play();
        if (playing && typeof playing.catch === 'function') {
            playing.catch(function(err) {
                // Trình duyệt chặn tự phát → chờ người dùng bấm nút Play
                if (err && err.name === 'NotAllowedError') {
                    if (token === pqAudioToken) pqSetPlaying(false);
                    return;
                }
                fallback();
            });
        }
    }

    $('#pqPlayBtn').on('click', function() {
        pqPlayCurrent();
        $('#pqFillInput:visible').trigger('focus');
    });

    // Một số trình duyệt nạp danh sách giọng đọc trễ
    if ('speechSynthesis' in window && typeof window.speechSynthesis.addEventListener === 'function') {
        window.speechSynthesis.addEventListener('voiceschanged', function() {});
    }

    /* ---------- Điền từ: câu ví dụ có ô trống + gợi ý ---------- */
    const PQ_BLANK = '<span class="pq-fill__blank">________</span>';

    // Khớp từ gốc và một số biến thể thường gặp (recycle → recycled/recycling, study → studies)
    function pqWordRegex(word) {
        const w = String(word || '').trim();
        if (!w) return null;

        const esc = function(str) { return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); };
        const lower = w.toLowerCase();
        const alts = [esc(w) + '(?:s|es|ed|d|ing|ly|er|ers)?'];

        if (/e$/.test(lower) && w.length > 2) {
            alts.push(esc(w.slice(0, -1)) + '(?:ing|ed|er|ers)');
        }
        if (/[^aeiou]y$/.test(lower) && w.length > 2) {
            alts.push(esc(w.slice(0, -1)) + '(?:ies|ied|ier|iest)');
        }

        return new RegExp('(^|[^\\p{L}\\p{N}])(' + alts.join('|') + ')(?=$|[^\\p{L}\\p{N}])', 'giu');
    }

    // Tách example thành các câu, chỉ giữ câu có chứa từ cần điền và thay từ đó bằng ô trống
    function pqBuildSentencesHtml(q) {
        const raw = String(q.example || '')
            .replace(/<br\s*\/?>/gi, '\n')
            .replace(/<[^>]*>/g, ' ')
            .replace(/&nbsp;/g, ' ');

        const sentences = [];
        raw.split(/\r?\n|\s+\|\s+/).forEach(function(line) {
            line = line.replace(/^\s*(?:[-•*]|\d+[.)])\s+/, '').trim();
            if (!line) return;
            (line.match(/[^.!?]+[.!?]+["”’)]*|[^.!?]+$/g) || [line]).forEach(function(part) {
                part = part.trim();
                if (part) sentences.push(part);
            });
        });

        const items = [];
        sentences.forEach(function(sentence) {
            const re = pqWordRegex(q.correct_answer);
            if (!re || items.length >= 3) return;

            let out = '';
            let last = 0;
            let found = false;
            let m;

            while ((m = re.exec(sentence)) !== null) {
                const start = m.index + m[1].length;
                out += pqEsc(sentence.slice(last, start)) + PQ_BLANK;
                last = start + m[2].length;
                found = true;
                if (m[0].length === 0) re.lastIndex++;
            }

            if (found) {
                items.push('<li>' + out + pqEsc(sentence.slice(last)) + '</li>');
            }
        });

        if (items.length) {
            return items.join('');
        }

        // Không có câu ví dụ chứa từ → dùng nghĩa của từ làm đề
        const meaning = q.translation || q.definition || q.question || '';
        return meaning
            ? '<li class="pq-fill__meaning">Chưa có câu ví dụ. Điền từ có nghĩa: <strong>' + pqEsc(meaning) + '</strong></li>'
            : '<li class="pq-fill__meaning">Chưa có câu ví dụ cho từ này.</li>';
    }

    // Gợi ý: chưa mở → toàn dấu *, mở → hiện 50% số chữ cái đầu (cart → ca**)
    function pqMaskWord(word, revealHalf) {
        const chars = Array.from(String(word || ''));
        const isLetter = function(ch) { return /[\p{L}\p{N}]/u.test(ch); };
        const total = chars.filter(isLetter).length;
        const show = revealHalf ? Math.max(1, Math.floor(total / 2)) : 0;
        let n = 0;

        return chars.map(function(ch) {
            if (!isLetter(ch)) return ch;
            n++;
            return n <= show ? ch : '*';
        }).join('');
    }

    // Giống normalizePracticeAnswer() phía server
    function pqNormalizeAnswer(value) {
        return String(value || '')
            .toLowerCase()
            .replace(/[’‘`]/g, "'")
            .replace(/\s+/g, ' ')
            .replace(/^[\s.,!?;:"'()]+|[\s.,!?;:"'()]+$/g, '');
    }

    function pqFillBox(type, text, icon) {
        const empty = !String(text || '').trim();
        return '<div class="pq-fill__box ' + type + (empty ? ' is-empty' : '') + '">'
             + '<span>' + (empty ? '(Bỏ trống)' : pqEsc(text)) + '</span>' + icon + '</div>';
    }

    function pqRenderFill(q, a) {
        $('#pqFillSentences').html(q.sentencesHtml);

        const $input = $('#pqFillInput');
        const $result = $('#pqFillResult');
        const $hint = $('#pqHintBtn');

        if (!a) {
            $result.empty();
            $input.removeClass('hidden').val(pq.drafts[pq.index] || '');
            $hint.prop('disabled', !!pq.hints[pq.index]);
            $('#pqHintText').text(pqMaskWord(q.correct, !!pq.hints[pq.index]));
            setTimeout(function() { $input.trigger('focus'); }, 0);

            // Nghe Viết: tự phát âm thanh khi mở câu mới
            if (pq.mode === 'dictation') {
                pqPlayCurrent();
            }
            return;
        }

        // Đã trả lời / bỏ qua: hiện kết quả và gợi ý đầy đủ
        $input.addClass('hidden');
        let html = '';
        if (a.status === 'correct') {
            html = pqFillBox('is-correct', a.selected, PQ_ICON_CHECK);
        } else {
            if (a.status === 'incorrect') {
                html += pqFillBox('is-incorrect', a.selected, PQ_ICON_CROSS);
            }
            html += pqFillBox('is-correct', q.correct, PQ_ICON_CHECK);
        }
        $result.html(html);
        $hint.prop('disabled', true);
        $('#pqHintText').text(q.correct);
    }

    function pqSubmitFill() {
        if (pq.answers[pq.index]) return;

        const value = String($('#pqFillInput').val() || '').trim();
        if (!value) {
            $('#pqFillInput').trigger('focus');
            return;
        }

        const q = pq.questions[pq.index];
        const isCorrect = pqNormalizeAnswer(value) === pqNormalizeAnswer(q.correct);

        pq.answers[pq.index] = { selected: value, status: isCorrect ? 'correct' : 'incorrect' };
        pq.drafts[pq.index] = '';
        pqTrackLocal(q, isCorrect);
        pqSubmitAnswer(q, value, false);
        pqRenderQuestion();
    }

    $('#pqFillInput').on('input', function() {
        pq.drafts[pq.index] = $(this).val();
    }).on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            pqSubmitFill();
        }
    });

    $('#pqHintBtn').on('click', function() {
        if (pq.answers[pq.index] || pq.hints[pq.index]) return;
        pq.hints[pq.index] = true;
        $('#pqHintText').text(pqMaskWord(pq.questions[pq.index].correct, true));
        $(this).prop('disabled', true);
        $('#pqFillInput').trigger('focus');
    });

    // ── 3. Hiển thị câu hỏi ──────────────────────────────────────
    function pqRenderQuestion() {
        const q = pq.questions[pq.index];
        if (!q) return;

        const a = pq.answers[pq.index];
        const locked = !!a;
        const isDictation = pq.mode === 'dictation';
        const isFill = pq.mode === 'completion' || isDictation;   // 2 dạng gõ đáp án

        pqStopAudio();
        $('#pqAudioMsg').text('');
        $('#pqPrompt, #pqOptions').toggleClass('hidden', isFill);
        $('#pqFill').toggleClass('hidden', !isFill);
        $('#pqAudio').toggleClass('hidden', !isDictation);
        $('#pqFillSentences, #pqHintBtn').toggleClass('hidden', isDictation);
        $('#pqFillInput').attr('placeholder', isDictation
            ? 'Nhập đáp án bạn nghe được vào đây...'
            : 'Điền từ vào ô trống...');

        $('#pqSkippedNote').remove();
        if (a && a.status === 'skipped') {
            $('<div class="pq-quiz__skipped" id="pqSkippedNote">Bạn đã bỏ qua câu này</div>')
                .toggleClass('is-fill', isFill)
                .insertBefore(isFill ? '#pqFillResult' : '#pqOptions');
        }

        $('#pqCounter').text((pq.index + 1) + ' / ' + pq.questions.length);

        if (isFill) {
            pqRenderFill(q, a);
            pqUpdateNav();
            return;
        }

        $('#pqPrompt').text(q.prompt);

        let html = '';
        q.options.forEach(function(word, i) {
            const classes = ['pq-option'];
            let mark = '';
            const isChosen = !!a && a.selected === word;
            const isRight = word === q.correct;

            if (a && a.status === 'pending' && isChosen) {
                classes.push('is-selected');
            } else if (a && a.status !== 'pending') {
                if (isChosen) classes.push('is-chosen');
                if (isRight) {
                    classes.push('is-correct');
                    mark = PQ_ICON_CHECK;
                } else if (isChosen) {
                    classes.push('is-incorrect');
                    mark = PQ_ICON_CROSS;
                }
            }

            html += '<button type="button" class="' + classes.join(' ') + '" data-index="' + i + '"'
                  + ' role="radio" aria-checked="' + (isChosen ? 'true' : 'false') + '"'
                  + (locked ? ' disabled' : '') + '>'
                  + '<span class="pq-option__radio" aria-hidden="true"></span>'
                  + '<span class="pq-option__text">' + pqEsc(word) + '</span>'
                  + '<span class="pq-option__mark" aria-hidden="true">' + mark + '</span>'
                  + '</button>';
        });

        $('#pqOptions').html(html);
        pqUpdateNav();
    }

    function pqUpdateNav() {
        const answered = !!pq.answers[pq.index];
        $('#pqPrevBtn').prop('disabled', pq.pending || pq.index === 0);
        $('#pqSkipBtn').prop('disabled', pq.pending || answered);
        $('#pqNextBtn').prop('disabled', pq.pending);
    }

    // ── 4. Chọn đáp án: tím → (sau PQ_REVEAL_DELAY) xanh/đỏ ──────
    $(document).on('click', '#pqOptions .pq-option', function() {
        if (pq.pending || pq.answers[pq.index]) return;

        const qIndex = pq.index;
        const q = pq.questions[qIndex];
        const selected = q.options[$(this).data('index')];

        pq.answers[qIndex] = { selected: selected, status: 'pending' };
        pq.pending = true;
        pqRenderQuestion();

        pq.timer = setTimeout(function() {
            pq.timer = null;
            pq.pending = false;

            const isCorrect = selected === q.correct;
            pq.answers[qIndex].status = isCorrect ? 'correct' : 'incorrect';
            pqTrackLocal(q, isCorrect);
            pqSubmitAnswer(q, selected, false);

            if (pq.index === qIndex) {
                pqRenderQuestion();
            } else {
                pqUpdateNav();
            }
        }, PQ_REVEAL_DELAY);
    });

    // Bỏ qua / Tiếp theo khi chưa chọn → tính là sai
    function pqMarkSkipped(qIndex) {
        const q = pq.questions[qIndex];
        pq.answers[qIndex] = { selected: null, status: 'skipped' };
        pqTrackLocal(q, false);
        pqSubmitAnswer(q, null, true);
    }

    function pqTrackLocal(q, isCorrect) {
        if (isCorrect) {
            practicedCorrectIds.add(q.id);
        } else {
            practicedCorrectIds.delete(q.id);
        }
    }

    function pqSubmitAnswer(q, selected, skipped) {
        const data = {
            _token: pqCsrf(),
            selected_answer: selected || '',
            skipped: skipped ? 1 : 0
        };
        data[q.idKey] = q.id;

        $.ajax({
            url: '/panel/dictionary/practice/submit-answer',
            method: 'POST',
            data: data
        }).fail(function(xhr) {
            console.error('Error submitting answer:', xhr);
        });
    }

    function pqGoNext() {
        if (pq.index < pq.questions.length - 1) {
            pq.index++;
            pqRenderQuestion();
        } else {
            pqShowResults();
        }
    }

    $('#pqNextBtn').on('click', function() {
        if (pq.pending || !pq.questions.length) return;

        // Điền từ / Nghe Viết: đã gõ mà chưa nộp → chấm trước, ở lại câu này để xem đúng/sai
        if ((pq.mode === 'completion' || pq.mode === 'dictation') && !pq.answers[pq.index]
            && String($('#pqFillInput').val() || '').trim() !== '') {
            pqSubmitFill();
            return;
        }

        if (!pq.answers[pq.index]) {
            pqMarkSkipped(pq.index);
        }
        pqGoNext();
    });

    $('#pqSkipBtn').on('click', function() {
        if (pq.pending || !pq.questions.length || pq.answers[pq.index]) return;
        pqMarkSkipped(pq.index);
        pqGoNext();
    });

    // Quay lại: chỉ xem lại, không làm lại được
    $('#pqPrevBtn').on('click', function() {
        if (pq.pending || pq.index === 0) return;
        pq.index--;
        pqRenderQuestion();
    });

    $('#pqExitBtn').on('click', function() {
        const answeredCount = pq.answers.filter(function(a) { return !!a; }).length;
        if (answeredCount > 0 && answeredCount < pq.questions.length
            && !confirm('Bạn muốn thoát phiên luyện tập? Kết quả các câu đã làm vẫn được lưu.')) {
            return;
        }
        exitPractice();
    });

    // ── 5. Kết quả ───────────────────────────────────────────────
    function pqShowResults() {
        const learned = [];
        const review = [];
        let correctCount = 0;

        pq.questions.forEach(function(q, i) {
            const a = pq.answers[i];
            if (a && a.status === 'correct') {
                correctCount++;
                learned.push(q);
            } else {
                review.push(q);
            }
        });

        const listHtml = function(items) {
            if (!items.length) {
                return '<li class="pq-result__empty">Không có từ nào</li>';
            }
            return items.map(function(q) { return '<li>' + pqEsc(q.correct) + '</li>'; }).join('');
        };

        $('#pqResultScore').text(correctCount + ' / ' + pq.questions.length);
        $('#pqLearnedCount').text(learned.length);
        $('#pqReviewCount').text(review.length);
        $('#pqLearnedList').html(listHtml(learned)).scrollTop(0);
        $('#pqReviewList').html(listHtml(review)).scrollTop(0);

        pq.wrongIds = review.map(function(q) { return q.id; });
        $('#pqRetryWrongBtn').prop('disabled', pq.wrongIds.length === 0);

        pqOpenModal($pqResultModal);
    }

    // Làm lại: cùng bộ từ, cùng chế độ
    $('#pqRetryBtn').on('click', function() {
        pqCloseModal($pqResultModal);
        pqStartSession(pq.roundIds);
    });

    // Luyện từ sai: chỉ các từ sai/bỏ qua, cùng chế độ
    $('#pqRetryWrongBtn').on('click', function() {
        if (!pq.wrongIds || !pq.wrongIds.length) return;
        pqCloseModal($pqResultModal);
        pqStartSession(pq.wrongIds.slice());
    });

    // Hoàn thành / nút X / click nền
    $(document).on('click', '.js-pq-result-finish', function() {
        pqCloseModal($pqResultModal);
        exitPractice();
    });

    $(document).on('keydown', function(e) {
        if (e.key !== 'Escape') return;
        if ($pqModeModal.hasClass('is-open')) {
            pqCloseModal($pqModeModal);
        } else if ($pqResultModal.hasClass('is-open')) {
            pqCloseModal($pqResultModal);
            exitPractice();
        }
    });

    function exitPractice() {
        pqClearTimer();
        $('#practiceModeContainer').addClass('hidden');
        $('#flashcardPracticeContainer').addClass('hidden');

        const ctx = pq.ctx || {
            listId: currentWordListId,
            listType: currentWordListType,
            sourceType: currentWordListSourceType,
            sourceId: currentWordListSourceId
        };

        // Reload danh sách để cập nhật trạng thái đã học / cần ôn
        if (ctx.listType === 'academic') {
            $('#academicWordListsSection').removeClass('hidden');
            loadAcademicWordList(ctx.listId, ctx.sourceType, ctx.sourceId);
        } else {
            $('#myWordListSection').removeClass('hidden');
            loadMyWordList();
        }
    }

        // Lọc theo nhiều trạng thái + sắp xếp A-Z
    $(document).on('click', '.status-chip', function(e) {
        e.stopPropagation();
        $(this).toggleClass('active');

        const $expanded  = $(this).closest('.word-list-expanded');
        const $container = $expanded.find('.words-container');
        const $words     = $container.find('.word-item');

        if ($expanded.find('.status-chip[data-sort="alphabet"]').hasClass('active')) {
            const sorted = $words.get().sort(function(a, b) {
                return String($(a).data('word')).localeCompare(String($(b).data('word')));
            });
            $container.append(sorted);
        }

        const active = [];
        $expanded.find('.status-chip[data-status].active').each(function() {
            active.push($(this).data('status'));
        });

        if (active.length === 0) {
            $words.show();
            return;
        }

        $words.each(function() {
            const $w      = $(this);
            const learned = $w.attr('data-learned') === '1';
            const review  = $w.attr('data-review')  === '1';
            const star    = $w.attr('data-star')    === '1';

            let match = false;
            if (active.indexOf('new')     !== -1 && !learned && !review) match = true;
            if (active.indexOf('learned') !== -1 && learned)             match = true;
            if (active.indexOf('review')  !== -1 && review)              match = true;
            if (active.indexOf('star')    !== -1 && star)                match = true;

            $w.toggle(match);
        });
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

        lastSelectedIds = selectedIds.slice();
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
            endpoint = '/panel/dictionary/my-word-list?folder_id=' + encodeURIComponent(currentWordListId || currentFolderId || '');
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
                        example: item.example || '',
                        needs_review: !!item.needs_review,     
                        is_starred: !!item.is_starred     
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
            'adv': 'Trạng từ', 'adverb': 'Trạng từ',
            'prep': 'Giới từ', 'preposition': 'Giới từ',
            'conj': 'Liên từ', 'conjunction': 'Liên từ',
            'pron': 'Đại từ', 'pronoun': 'Đại từ',
            'interj': 'Thán từ', 'interjection': 'Thán từ', 'exclamation': 'Thán từ',
            'det': 'Từ hạn định', 'determiner': 'Từ hạn định',
            'phrasal verb': 'Cụm động từ', 'phrase': 'Cụm từ', 'idiom': 'Thành ngữ'
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
            vcSetMediaBg('#flashcardImageBg', '');
            $('#flashcardImageFallback').show();
            $('#flashcardCard').css('justify-content', 'flex-start');
            $('#flashcardFrontGroup, #flashcardFrontLabel').show();
            $('#flashcardBackLabel').hide();
            $('#flashcardBack').hide();
            $('#flashcardCounter').text('0 / 0');
            $('#flashcardPrevBtn, #flashcardNextBtn').prop('disabled', true);
            $('#fcReviewBtn, #fcStarBtn').removeClass('on');
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
            const imageUrl = card.image_url;
            imageEl.style.display = 'none';
            fallbackEl.style.display = 'flex';
            vcSetMediaBg('#flashcardImageBg', '');
            imageEl.onload = function() {
                fallbackEl.style.display = 'none';
                imageEl.style.display = 'block';
                vcSetMediaBg('#flashcardImageBg', imageUrl);
            };
            imageEl.onerror = function() {
                imageEl.style.display = 'none';
                fallbackEl.style.display = 'flex';
                vcSetMediaBg('#flashcardImageBg', '');
            };
            imageEl.src = imageUrl;
        } else {
            $('#flashcardImage').hide().attr('src', '');
            vcSetMediaBg('#flashcardImageBg', '');
            $('#flashcardImageFallback').show();
        }

        flashcardFlipped = false;
        $('#flashcardCounter').text((flashcardIndex + 1) + ' / ' + flashcardCards.length);
        $('#flashcardPrevBtn').prop('disabled', flashcardIndex === 0);
        $('#flashcardNextBtn').prop('disabled', flashcardIndex === flashcardCards.length - 1);
        $('#flashcardTurnBtn').text('Lật thẻ');
        $('#fcReviewBtn').toggleClass('on', !!card.needs_review);
        $('#fcStarBtn').toggleClass('on', !!card.is_starred);
    }

    $('#flashcardCard').on('click', function(e) {
        // Bỏ qua (không lật thẻ) nếu người dùng click vào nút Audio phát âm
        if ($(e.target).closest('#flashcardAudioBtn').length) {
            return;
        }

        if (!flashcardCards.length) return;
        
        flashcardFlipped = !flashcardFlipped;
        
        if (flashcardFlipped) {
            // Lật ra mặt sau
            $('#flashcardFrontGroup, #flashcardFrontLabel').hide();
            $('#flashcardBackLabel').css('display', 'inline-flex');
            $('#flashcardBack').css('display', 'block');
        } else {
            // Lật về mặt trước
            $('#flashcardBack').hide();
            $('#flashcardBackLabel').hide();
            $('#flashcardFrontLabel').show();
            $('#flashcardFrontGroup').show();
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

        button.prop('disabled', true).addClass('is-playing');

        const resetButton = function() {
            button.prop('disabled', false).removeClass('is-playing');
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

    $('#fcReviewBtn, #fcStarBtn').on('click', function() {
        if (!flashcardCards.length) return;

        const $btn  = $(this);
        const field = $btn.attr('id') === 'fcReviewBtn' ? 'needs_review' : 'is_starred';
        const value = $btn.hasClass('on') ? 0 : 1;
        const card  = flashcardCards[flashcardIndex];

        const data = {
            field: field,
            value: value,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        if (currentWordListSourceType === 'academic') {
            data.word_id = card.id;
        } else {
            data.flashcard_id = card.id;
        }

        $.post('/panel/dictionary/word-status/toggle', data, function(res) {
            if (!res.success) return;
            $btn.toggleClass('on', !!value);
            card.needs_review = res.needs_review;
            card.is_starred   = res.is_starred;
        });
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

    const wordDetailCache = {};
    const $wordDetailModal = $('#wordDetailModal');
    const $wordDetailHeading = $('#wordDetailModalHeading');
    const $wordDetailModalBody = $('#wordDetailModalBody');
 
    let wdRequestId = 0;        // bỏ qua response cũ nếu người dùng đã mở từ khác
    let wdLastFocus = null;
    let wdCurrentAudio = null;
 
    const WD_AUDIO_ICON = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        + '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>'
        + '<path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>'
        + '<path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>';
 
    // ── Helpers ──────────────────────────────────────────────────────
    function wdEsc(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }
 
    // "/.../", "//...//", "[...]" hay "..." đều về đúng dạng "/.../"
    function wdNormalizeIpa(ipa) {
        const clean = String(ipa || '').trim().replace(/^[\/\[\s]+|[\/\]\s]+$/g, '');
        return clean ? '/' + clean + '/' : '';
    }
 
    function wdSplitList(raw, separator) {
        return String(raw || '')
            .split(separator)
            .map(function (item) { return item.trim().replace(/^["“]+|["”]+$/g, '').trim(); })
            .filter(Boolean);
    }
 
    // Tô màu từ vựng (và dạng thêm s/es/ed/ing) trong câu ví dụ
    function wdHighlight(text, word) {
        const safeText = wdEsc(text);
        const target = String(word || '').trim();
        if (!target) return safeText;
 
        try {
            const pattern = wdEsc(target).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const re = new RegExp('(^|[^\\p{L}])(' + pattern + '(?:s|es|ed|d|ing)?)(?=[^\\p{L}]|$)', 'giu');
            return safeText.replace(re, '$1<span class="wdc-highlight">$2</span>');
        } catch (e) {
            return safeText;
        }
    }
 
    function wdAudioButton(audioUrl, word, label) {
        return '<button type="button" class="wdc-audio-btn js-wd-audio"'
            + ' data-audio="' + wdEsc(audioUrl) + '"'
            + ' data-word="' + wdEsc(word) + '"'
            + ' aria-label="' + wdEsc(label ? 'Phát âm ' + label : 'Phát âm') + '">'
            + WD_AUDIO_ICON
            + '</button>';
    }
 
    function wdHeadingHtml(word, pronunciations) {
        let html = '<div class="wdc-title-row">';
        html += '<h2 class="wdc-word" id="wordDetailModalTitle">' + wdEsc(word) + '</h2>';
 
        (pronunciations || []).forEach(function (p) {
            const ipa = wdNormalizeIpa(p.ipa);
            html += '<span class="wdc-pron-group">';
            if (p.label) html += '<span class="wdc-pron-label">' + wdEsc(p.label) + '</span>';
            if (ipa) html += '<span class="wdc-ipa">' + wdEsc(ipa) + '</span>';
            html += wdAudioButton(p.audio, word, p.label);
            html += '</span>';
        });
 
        html += '</div>';
        return html;
    }
 
    function wdEmpty(message) {
        return '<div class="wdm-empty">' + wdEsc(message) + '</div>';
    }
 
    // ── Render: từ do teacher upload (bundle) ─────────────────────────
    function renderTeacherWordDetail(d) {
        if (!d) {
            return { heading: '', body: wdEmpty('Không tìm thấy dữ liệu.') };
        }
 
        const word = d.word || '';
        const translation = String(d.translation_vi || '').trim();
        const definition = String(d.definition || '').trim();
        let body = '';
 
        if (d.part_of_speech || translation) {
            body += '<div class="wdc-meaning-row">';
            if (d.part_of_speech) {
                body += '<span class="wdc-pos">' + wdEsc(posLabel(d.part_of_speech)) + '</span>';
            }
            if (translation) {
                body += '<span class="wdc-meaning">' + wdEsc(translation) + '</span>';
            }
            body += '</div>';
        }
 
        // Chỉ hiện định nghĩa tiếng Anh khi khác nghĩa tiếng Việt (tránh lặp 2 dòng giống nhau)
        if (definition && definition.toLowerCase() !== translation.toLowerCase()) {
            body += '<div class="wdc-definition">' + wdEsc(definition) + '</div>';
        }
 
        const collocations = wdSplitList(d.collocation, /[\n,;]+/);
        if (collocations.length) {
            body += '<div class="wdc-section-title">Collocation</div>';
            body += '<div class="wdc-pill">' + wdEsc(collocations.join(', ')) + '</div>';
        }
 
        // Nhiều câu ví dụ: xuống dòng trong ô Excel (Alt+Enter) hoặc ngăn cách bằng "|"
        const examples = wdSplitList(d.example, /\r?\n|\|/);
        if (examples.length) {
            body += '<div class="wdc-section-title">Example</div>';
            examples.forEach(function (example) {
                body += '<div class="wdc-pill">' + wdHighlight(example, word) + '</div>';
            });
        }
 
        if (d.image_url) {
            // Khung 3:2, ảnh hiện trọn (contain) + nền mờ từ chính ảnh → không méo, không cắt
            body += '<div class="wdc-image-wrap vc-media">'
                + '<div class="vc-media__bg" aria-hidden="true" style="background-image:' + wdEsc(vcCssUrl(d.image_url)) + '"></div>'
                + '<img class="vc-media__img" src="' + wdEsc(d.image_url) + '" alt="' + wdEsc('Hình minh hoạ cho ' + word) + '"'
                + ' loading="lazy" onerror="this.parentNode.remove()">'
                + '</div>';
        }
 
        return {
            heading: wdHeadingHtml(word, [{ ipa: d.pronunciation, audio: d.audio_url }]),
            body: body || wdEmpty('Từ này chưa có nội dung chi tiết.')
        };
    }
 
    // ── Render: từ tra từ điển ngoài (academic / học viên tự lưu) ─────
    function renderDictionaryWordDetail(data) {
        if (!data) {
            return { heading: '', body: wdEmpty('Không tìm thấy dữ liệu.') };
        }
 
        const word = data.headword || data.word || '';
        const pronunciations = (Array.isArray(data.pronunciations) ? data.pronunciations : [])
            .map(function (p) {
                return { label: p.label || '', ipa: p.ipa || p.text || '', audio: p.audio || '' };
            });
 
        let body = '';
 
        if (Array.isArray(data.meanings) && data.meanings.length) {
            data.meanings.forEach(function (m) {
                body += '<div class="wdc-meaning-group">';
 
                if (m.partOfSpeech) {
                    body += '<div class="wdc-meaning-row"><span class="wdc-pos">' + wdEsc(posLabel(m.partOfSpeech)) + '</span></div>';
                }
 
                (Array.isArray(m.definitions) ? m.definitions : []).slice(0, 4).forEach(function (def, idx) {
                    if (!def.definition && !def.example) return;
 
                    body += '<div class="wdc-def-item">';
                    if (def.definition) {
                        body += '<strong>' + (idx + 1) + '.</strong> ' + wdEsc(def.definition);
                    }
                    if (def.example) {
                        body += '<div class="wdc-pill">' + wdHighlight(def.example, word) + '</div>';
                    }
                    body += '</div>';
                });
 
                if (Array.isArray(m.synonyms) && m.synonyms.length) {
                    body += '<div class="wdc-syn"><strong>Synonyms:</strong> ' + wdEsc(m.synonyms.join(', ')) + '</div>';
                }
                if (Array.isArray(m.antonyms) && m.antonyms.length) {
                    body += '<div class="wdc-syn"><strong>Antonyms:</strong> ' + wdEsc(m.antonyms.join(', ')) + '</div>';
                }
 
                body += '</div>';
            });
        }
 
        return {
            heading: wdHeadingHtml(word, pronunciations.length ? pronunciations : [{}]),
            body: body || wdEmpty('Chưa có định nghĩa chi tiết.')
        };
    }
 
    // ── Audio ────────────────────────────────────────────────────────
    function wdStopAudio() {
        if (wdCurrentAudio) {
            wdCurrentAudio.pause();
            wdCurrentAudio = null;
        }
        if (window.speechSynthesis) {
            window.speechSynthesis.cancel();
        }
        $('.js-wd-audio.is-playing').removeClass('is-playing');
    }
 
    function speakWordDetail(word, btnEl) {
        const done = function () { if (btnEl) btnEl.classList.remove('is-playing'); };
 
        if (!word || !window.speechSynthesis) {
            done();
            return;
        }
 
        window.speechSynthesis.cancel();
        const utter = new SpeechSynthesisUtterance(String(word).trim());
        utter.lang = 'en-US';
        utter.rate = 0.9;
        utter.onend = done;
        utter.onerror = done;
        window.speechSynthesis.speak(utter);
    }
 
    $(document).on('click', '.js-wd-audio', function () {
        const btn = this;
        const word = btn.getAttribute('data-word') || '';
        let url = btn.getAttribute('data-audio') || '';
 
        if (url.startsWith('//')) url = 'https:' + url;
 
        wdStopAudio();
        btn.classList.add('is-playing');
 
        if (!url) {
            speakWordDetail(word, btn);
            return;
        }
 
        wdCurrentAudio = new Audio(url);
        wdCurrentAudio.onended = function () { btn.classList.remove('is-playing'); };
        wdCurrentAudio.play().catch(function () {
            // Link audio lỗi / bị chặn → đọc bằng giọng trình duyệt
            speakWordDetail(word, btn);
        });
    });
 
    // ── Mở / đóng modal ──────────────────────────────────────────────
    function openWordDetailModal(wordText) {
        wdLastFocus = document.activeElement;
        wdStopAudio();
        wdSetReportTarget(null);
 
        $wordDetailHeading.html(wdHeadingHtml(wordText, []));
        $wordDetailModalBody.html('<div class="wdm-loading">Đang tải...</div>').scrollTop(0);
 
        $wordDetailModal.addClass('is-open').attr('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
 
        setTimeout(function () {
            $wordDetailModal.find('.word-detail-modal__close').trigger('focus');
        }, 30);
 
        return ++wdRequestId;
    }
 
    function showWordDetail(requestId, result) {
        if (requestId !== wdRequestId || !$wordDetailModal.hasClass('is-open')) return;
 
        if (result.heading) $wordDetailHeading.html(result.heading);
        $wordDetailModalBody.html(result.body).scrollTop(0);
    }
 
    function closeWordDetailModal() {
        wdRequestId++;
        wdStopAudio();
        vwReport.close(true);
 
        $wordDetailModal.removeClass('is-open').attr('aria-hidden', 'true');
        document.body.style.overflow = '';
 
        if (wdLastFocus && typeof wdLastFocus.focus === 'function') {
            wdLastFocus.focus();
        }
    }
 
    $(document).on('click', '.word-item', function (e) {
        // Không mở modal khi bấm checkbox hoặc các icon trạng thái
        if ($(e.target).closest('.word-checkbox, .word-badges, .learned-badge').length) {
            return;
        }
 
        const $item      = $(this);
        const wordText   = $item.data('word');
        const wordSource = $item.data('word-source');
        const wordId     = $item.data('word-id');
 
        if (!wordText) return;
 
        const requestId = openWordDetailModal(wordText);

        // Nút "Báo lỗi": chỉ từ thuộc bộ từ do giáo viên tạo (bundle / academic)
        wdSetReportTarget(
            String($item.attr('data-reportable')) === '1' && (wordSource === 'bundle' || wordSource === 'academic')
                ? { source: wordSource, id: wordId, word: String(wordText) }
                : null
        );
 
        // ── Từ do teacher upload: lấy nội dung gốc từ bundle_vocabulary_words ──
        if (wordSource === 'bundle') {
            const cacheKey = 'bundle:' + wordId;
 
            if (wordDetailCache[cacheKey]) {
                showWordDetail(requestId, renderTeacherWordDetail(wordDetailCache[cacheKey]));
                return;
            }
 
            $.get('/panel/dictionary/flashcards/' + wordId + '/detail')
                .done(function (res) {
                    if (res.success && res.data) {
                        wordDetailCache[cacheKey] = res.data;
                        showWordDetail(requestId, renderTeacherWordDetail(res.data));
                    } else {
                        showWordDetail(requestId, { heading: '', body: wdEmpty('Không tìm thấy dữ liệu.') });
                    }
                })
                .fail(function () {
                    showWordDetail(requestId, { heading: '', body: wdEmpty('Không tải được dữ liệu. Đóng lại rồi thử mở lần nữa.') });
                });
 
            return;
        }
 
        // ── Academic / từ học viên tự lưu: tra từ điển ngoài ──
        const cacheKey = 'dict:' + wordText;
 
        if (wordDetailCache[cacheKey]) {
            showWordDetail(requestId, renderDictionaryWordDetail(wordDetailCache[cacheKey]));
            return;
        }
 
        $.ajax({
            url: '/panel/dictionary/search-first',
            method: 'POST',
            data: {
                query: wordText,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function (response) {
            if (response.success && response.data) {
                wordDetailCache[cacheKey] = response.data;
                showWordDetail(requestId, renderDictionaryWordDetail(response.data));
            } else {
                showWordDetail(requestId, { heading: '', body: wdEmpty('Không tìm thấy dữ liệu.') });
            }
        })
        .fail(function () {
            showWordDetail(requestId, { heading: '', body: wdEmpty('Không tải được dữ liệu. Đóng lại rồi thử mở lần nữa.') });
        });
    });
 
    $(document).on('click', '.js-word-detail-close', closeWordDetailModal);
 
    $(document).on('keydown', function (e) {
        if (e.key !== 'Escape') return;

        // Đang mở cửa sổ báo lỗi (nằm trên) → chỉ đóng cửa sổ đó
        if (vwReport.isOpen()) {
            vwReport.close();
            return;
        }

        if ($wordDetailModal.hasClass('is-open')) {
            closeWordDetailModal();
        }
    });

    // ── Báo lỗi từ vựng ────────────────────────────────────────────
    let wdReportTarget = null;

    function wdSetReportTarget(target) {
        wdReportTarget = target;
        $('#wdReportBtn').toggleClass('hidden', !target);
    }

    const vwReport = {
        $root: $('#vwReport').appendTo('body'),
        $form: $('#vwReportForm'),
        $text: $('#vwReportMessage'),
        $count: $('#vwReportCount'),
        $submit: $('#vwReportSubmit'),
        $cancel: $('#vwReportCancel'),
        $msg: $('#vwReportMsg'),
        target: null,
        sending: false,
        closeTimer: null,
        returnFocus: null,

        max() {
            return parseInt(this.$text.attr('maxlength'), 10) || 300;
        },

        isOpen() {
            return !this.$root.prop('hidden');
        },

        open(target, trigger) {
            if (!target) return;
            clearTimeout(this.closeTimer);

            const sameWord = this.target && this.target.source === target.source
                && String(this.target.id) === String(target.id);
            const wasSent = this.$form.hasClass('is-sent');

            this.target = target;
            this.returnFocus = trigger || document.activeElement;

            // Giữ nội dung đang gõ dở nếu mở lại đúng từ đó
            if (!sameWord || wasSent) this.$text.val('');
            this.$form.removeClass('is-sent');
            this.$text.prop('disabled', false);
            this.$cancel.text('Hủy');
            this.showMessage('', '');
            this.update();

            this.$root.prop('hidden', false).attr('aria-hidden', 'false');
            setTimeout(() => this.$text.trigger('focus'), 30);
        },

        close(silent) {
            if (!this.isOpen()) return;
            clearTimeout(this.closeTimer);
            this.$root.prop('hidden', true).attr('aria-hidden', 'true');

            if (!silent && this.returnFocus && document.body.contains(this.returnFocus)) {
                this.returnFocus.focus();
            }
            this.returnFocus = null;
        },

        update() {
            const len = this.$text.val().length;
            this.$count.text(len + '/' + this.max()).toggleClass('is-full', len >= this.max());
            this.$submit.prop('disabled', this.sending
                || this.$form.hasClass('is-sent')
                || this.$text.val().trim() === '');
        },

        showMessage(text, type) {
            this.$msg.text(text || '')
                .attr('class', 'vw-report__msg' + (type ? ' is-' + type : ''))
                .prop('hidden', !text);
        },

        send() {
            const message = this.$text.val().trim();
            if (!this.target || !message || this.sending) return;

            this.sending = true;
            this.$submit.text('Đang gửi...');
            this.update();
            this.showMessage('', '');

            $.ajax({
                url: '/panel/dictionary/word-report',
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    source: this.target.source,
                    id: this.target.id,
                    message: message
                }
            })
            .done((data) => {
                if (data && data.status === 'ok') {
                    this.$form.addClass('is-sent');
                    this.$text.prop('disabled', true);
                    this.$cancel.text('Đóng');
                    this.showMessage(data.message || 'Đã gửi báo cáo. Cảm ơn bạn!', 'success');
                    this.closeTimer = setTimeout(() => this.close(), 2200);
                } else {
                    this.showMessage((data && data.message) || 'Không gửi được báo cáo, hãy thử lại.', 'error');
                }
            })
            .fail((xhr) => {
                const data = (xhr && xhr.responseJSON) || {};
                const firstError = data.errors ? Object.values(data.errors)[0] : null;
                const text = (Array.isArray(firstError) ? firstError[0] : firstError)
                    || data.message
                    || (xhr && xhr.status === 419 ? 'Phiên làm việc đã hết hạn, hãy tải lại trang.' : 'Không gửi được báo cáo, hãy thử lại.');
                this.showMessage(text, 'error');
            })
            .always(() => {
                this.sending = false;
                this.$submit.text('Gửi báo cáo');
                this.update();
            });
        }
    };

    $('#wdReportBtn').on('click', function () {
        vwReport.open(wdReportTarget, this);
    });

    vwReport.$text.on('input', function () { vwReport.update(); });
    vwReport.$form.on('submit', function (e) {
        e.preventDefault();
        vwReport.send();
    });
    $(document).on('click', '[data-vw-report-close]', function () {
        vwReport.close();
    });
    

})(jQuery);
</script>
@endpush