@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .dictionary-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .user-stats-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        border: 1px solid rgba(81, 29, 153, 0.10);
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
        background: rgba(81, 29, 153, 0.10);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #511D99;
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
        padding: 30px 30px 30px 0;
        margin-bottom: 30px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        border: 1px solid rgba(81, 29, 153, 0.10);
    }
    
    .dark-mode .search-section {
        background: #1e293b;
    }
    
    .search-title {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 15px;
        padding-left: 30px;
    }
    
    .dark-mode .search-title {
        color: #f1f5f9;
    }
    
    .search-wrapper {
        display: flex;
        gap: 10px;
        padding-left: 30px;
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

    .toggle-btn:hover {
        color: #511D99;
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
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        border: 1px solid rgba(81, 29, 153, 0.10);
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    
    .word-list-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        transform: translateY(-2px);
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
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        margin-top: 15px;
        display: none;
        border: 1px solid rgba(81, 29, 153, 0.10);
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
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .filter-input {
        flex: 1;
        min-width: 200px;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .dark-mode .filter-input {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    .filter-btn {
        padding: 10px 20px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .filter-btn:hover {
        color: #511D99;
        border-color: rgba(81, 29, 153, 0.16);
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: rgba(81, 29, 153, 0.08);
        color: #511D99;
        border-color: rgba(81, 29, 153, 0.16);
    }
    
    .dark-mode .filter-btn {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    /* Dictionary Result Styles */
    .dictionary-result-container {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        border: 1px solid rgba(81, 29, 153, 0.10);
    }
    
    .dictionary-result-container.hidden {
        display: none;
    }
    
    .dark-mode .dictionary-result-container {
        background: #1e293b;
    }
    
    .result-card {
        width: 100%;
    }
    
    .result-header {
        margin-bottom: 20px;
    }
    
    .dark-mode .result-header {
        border-color: #374151;
    }
    
    .result-word {
        font-size: 32px;
        font-weight: 700;
        color: #511D99;
        margin: 0;
    }
    
    .dark-mode .result-word {
        color: #f1f5f9;
    }
    
    .part-of-speech-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .part-of-speech-section:last-child {
        border-bottom: none;
    }
    
    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .pos-title {
        font-size: 18px;
        font-weight: 600;
        color: #511D99;
        font-style: italic;
    }
    
    .dark-mode .pos-title {
        color: #e5e7eb;
    }
    
    .btn-save-pos {
        background: #511D99;
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-save-pos:hover {
        background: #421670;
        transform: translateY(-1px);
    }
    
    .btn-save-pos.saved {
        background: #10b981;
    }
    
    .btn-save-pos.saved:hover {
        background: #059669;
    }
    
    .definition-item {
        margin-bottom: 15px;
        padding-left: 20px;
    }
    
    .definition-text {
        color: #374151;
        line-height: 1.6;
        margin-bottom: 5px;
    }
    
    .dark-mode .definition-text {
        color: #d1d5db;
    }
    
    .example-text {
        color: #6b7280;
        font-style: italic;
        margin-left: 15px;
        line-height: 1.5;
    }
    
    .dark-mode .example-text {
        color: #9ca3af;
    }
    
    .btn-back {
        width: 100%;
        padding: 12px;
        background: white;
        border: 1px solid rgba(81, 29, 153, 0.16);
        border-radius: 10px;
        color: #511D99;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-back:hover {
        background: rgba(81, 29, 153, 0.06);
        border-color: rgba(81, 29, 153, 0.22);
    }
    
    .dark-mode .btn-back {
        background: #2d3748;
        border-color: #4a5568;
        color: #e5e7eb;
    }
    
    .dark-mode .btn-back:hover {
        background: #374151;
        border-color: #6b7280;
    }
    }
    
    .dictionary-result-container.hidden {
        display: none;
    }
    
    .dark-mode .dictionary-result-container {
        background: #1e293b;
    }
    
    .result-card {
        max-width: 800px;
    }
    
    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .dark-mode .result-header {
        border-bottom-color: #334155;
    }
    
    .result-word {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        font-style: italic;
    }
    
    .dark-mode .result-word {
        color: #f1f5f9;
    }
    
    .btn-save-word {
        padding: 10px 24px;
        background: #10b981;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }
    
    .btn-save-word:hover {
        background: #059669;
    }
    
    .btn-save-word:disabled {
        background: #6b7280;
        cursor: not-allowed;
    }
    
    .pronunciation-section {
        display: flex;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .pronunciation-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .pronunciation-label {
        font-weight: 700;
        color: #64748b;
        font-size: 14px;
    }
    
    .dark-mode .pronunciation-label {
        color: #94a3b8;
    }
    
    .pronunciation-text {
        color: #1e293b;
        font-size: 16px;
    }
    
    .dark-mode .pronunciation-text {
        color: #f1f5f9;
    }
    
    .pronunciation-audio-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #511D99;
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .pronunciation-audio-btn:hover:not(:disabled) {
        background: #421670;
        transform: scale(1.1);
    }
    
    .pronunciation-audio-btn:disabled {
        background: #d1d5db;
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .pronunciation-audio-btn i {
        font-size: 18px;
    }
    
    .definitions-section {
        margin-top: 20px;
    }
    
    .definition-group {
        margin-bottom: 25px;
    }
    
    .part-of-speech {
        font-size: 18px;
        font-weight: 600;
        color: #511D99;
        margin-bottom: 15px;
        font-style: italic;
    }
    
    .definition-item {
        margin-bottom: 15px;
        padding-left: 20px;
    }
    
    .definition-text {
        color: #1e293b;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 8px;
    }
    
    .dark-mode .definition-text {
        color: #f1f5f9;
    }
    
    .definition-example {
        color: #64748b;
        font-size: 14px;
        font-style: italic;
        padding-left: 15px;
        border-left: 3px solid #e2e8f0;
        margin-top: 5px;
    }
    
    .dark-mode .definition-example {
        color: #94a3b8;
        border-left-color: #334155;
    }
    
    .word-item {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        transition: all 0.3s;
    }
    
    .word-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .dark-mode .word-item {
        background: #1e293b;
    }
    
    .word-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .word-content {
        flex: 1;
    }
    
    .word-text {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 5px;
    }
    
    .dark-mode .word-text {
        color: #f1f5f9;
    }
    
    .word-pronunciation {
        font-size: 14px;
        color: #64748b;
        font-style: italic;
    }
    
    .dark-mode .word-pronunciation {
        color: #94a3b8;
    }
    
    .word-definition {
        font-size: 14px;
        color: #475569;
        margin-top: 5px;
    }
    
    .dark-mode .word-definition {
        color: #cbd5e1;
    }
    
    .learned-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        background: #10b981;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .learned-badge.show {
        display: flex;
    }
    
    .learned-badge:hover::after {
        content: 'Đánh dấu đã học';
        position: absolute;
        bottom: 100%;
        right: 0;
        background: #1e293b;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
        white-space: nowrap;
        margin-bottom: 5px;
    }
    
    .action-bar {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(81, 29, 153, 0.10);
    }
    
    .dark-mode .action-bar {
        border-color: #334155;
    }
    
    .action-btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
    }
    
    .btn-select-all {
        background: #f1f5f9;
        color: #475569;
    }
    
    .btn-deselect-all {
        background: #f1f5f9;
        color: #475569;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .btn-practice {
        background: #511D99;
        color: white;
    }
    
    .btn-practice:hover {
        background: #421670;
    }
    
    .dark-mode .btn-select-all,
    .dark-mode .btn-deselect-all {
        background: #0f172a;
        color: #cbd5e1;
    }
    
    .dark-mode .btn-delete {
        background: #7f1d1d;
        color: #fca5a5;
    }
    
    .stats-widget {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        border: 1px solid rgba(81, 29, 153, 0.10);
    }
    
    .dark-mode .stats-widget {
        background: #1e293b;
    }
    
    .widget-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 15px;
        text-align: center;
    }
    
    .dark-mode .widget-title {
        color: #f1f5f9;
    }
    
    .streak-circles {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .streak-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid rgba(81, 29, 153, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
    }
    
    .streak-circle.active {
        background: #511D99;
        border-color: #511D99;
        color: white;
    }
    
    .ranking-placeholder {
        text-align: center;
        padding: 30px;
        color: #94a3b8;
        font-size: 14px;
    }
    
    .practice-mode-container {
        background: #ffffff;
        border-radius: 20px;
        padding: 32px 28px;
        max-width: 520px;
        margin: 0 auto;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        border: 1px solid rgba(81, 29, 153, 0.10);
    }
    
    .dark-mode .practice-mode-container {
        background: #1e293b;
    }
    
    .score-board {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 28px;
    }
    
    .score-item {
        background: rgba(81, 29, 153, 0.08);
        border-radius: 999px;
        padding: 8px 22px;
        display: inline-flex;
        align-items: center;
    }
    
    .score-label {
        font-size: 14px;
        font-weight: 700;
        color: #511D99;
        letter-spacing: 0.4px;
        display: flex;
        align-items: center;
        gap: 5px;
        margin: 0;
    }
    
    .score-value {
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #511D99 !important;
    }
    
    .score-value.correct {
        color: #333 !important;
    }
    
    .score-value.incorrect {
        color: #333 !important;
    }
    
    .question-text {
        font-size: 17px;
        font-weight: 700;
        color: #111;
        text-align: center;
        margin-bottom: 28px;
        line-height: 1.6;
    }
    
    .dark-mode .question-text {
        color: #f1f5f9;
    }
    
    .answers-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 28px;
        max-width: 360px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .answer-card {
        background: #fff;
        border: 1.5px solid #d1d5db;
        border-radius: 14px;
        padding: 18px 0;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .answer-card:hover {
        border-color: #888;
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
        background: #0f172a;
        border-color: #334155;
    }
    
    .answer-word {
        font-size: 17px;
        font-weight: 700;
        color: #111;
        margin-bottom: 4px;
    }
    
    .dark-mode .answer-word {
        color: #f1f5f9;
    }
    
    .answer-pronunciation {
        font-size: 13px;
        color: #555;
        font-style: italic;
    }
    
    .dark-mode .answer-pronunciation {
        color: #94a3b8;
    }
    
    .practice-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
    }
    
    .practice-btn {
        flex: 0 0 auto;
        padding: 9px 34px;
        border-radius: 999px;
        font-size: 15px;
        font-weight: 500;
        border: 1.5px solid #bbb;
        background: #fff;
        color: #333;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-exit:hover {
        background: #f1f5f9;
        border-color: #999;
    }
    
    .btn-next {
        background: #fff;
        color: #333;
        border-color: #bbb;
    }
    
    .btn-next:hover:not(:disabled) {
        background: #f1f5f9;
        border-color: #999;
    }
    
    .btn-next:disabled {
        background: #e8e8e8;
        color: #aaa;
        border-color: #d1d5db;
        cursor: not-allowed;
    }
    
    .dark-mode .practice-btn {
        background: #0f172a;
        color: #cbd5e1;
        border-color: #334155;
    }
    
    .hidden {
        display: none !important;
    }

    .word-item { cursor: pointer; }
    .word-item:hover { background: rgba(81, 29, 153, 0.04); }
    .dark-mode .word-item:hover { background: #0f172a; }
</style>
@endpush

@section('content')
<div class="dictionary-container">
    {{-- Welcome Bar (Dashboard Style) --}}
    <div class="ielts-welcome-bar bg-white rounded-24 p-16 mb-20">
        {{-- Left: greeting --}}
        <div class="flex-grow-1 min-w-0">
            <h1 class="font-18 font-weight-bold text-dark text-ellipsis mb-0">
                WELCOME, {{ strtoupper(auth()->user()->name ?? auth()->user()->full_name) }}! 👋
            </h1>
        </div>

        {{-- Switch Courses moved to sidebar --}}

        {{-- Continue → --}}
        <a href="/panel/dictionary" class="btn btn-primary btn-sm rounded-pill px-16">
            continue →
        </a>

        {{-- Notification bell --}}
        <a href="/panel/notifications" class="ielts-welcome-bar__bell d-flex-center size-40 rounded-circle bg-gray-100 position-relative text-dark">
            <x-iconsax-bul-notification class="icons" width="20px" height="20px"/>
            @php
                $unreadCount = auth()->user()->getUnReadNotifications()->count();
            @endphp
            @if($unreadCount > 0)
                <span class="position-absolute top-0 end-0 size-16 rounded-circle bg-danger d-flex-center font-10 text-white"
                      style="font-size:9px;top:2px;right:2px;min-width:16px;height:16px;">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
            @endif
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column: Word Lists -->
        <div class="col-lg-8">
            <!-- Search Section -->
            <div class="search-section" style="background:#fff !important; border:1px solid rgba(81,29,153,0.10) !important; box-shadow:0 6px 18px rgba(0,0,0,0.04) !important;">
                <h3 class="search-title">Search English</h3>
                <div class="search-wrapper">
                    <input type="text" class="search-input" id="searchInput" placeholder="Search the word...">
                    <button class="search-btn" id="searchBtn" style="background:#511D99 !important; border-color:#511D99 !important;">Search</button>
                </div>
            </div>

            <!-- Dictionary Search Result -->
            <div class="dictionary-result-container hidden" id="dictionaryResult">
                <div class="result-card">
                    <div class="result-header">
                        <h2 class="result-word" id="resultWord"></h2>
                    </div>
                    
                    <div class="pronunciation-section">
                        <div class="pronunciation-item">
                            <span class="pronunciation-label">UK</span>
                            <span class="pronunciation-text" id="pronunciationUK">/--/</span>
                            <button class="pronunciation-audio-btn" id="audioUKBtn" disabled>
                                <i class="iconsax" data-icon="volume-high"></i>
                            </button>
                        </div>
                        <div class="pronunciation-item">
                            <span class="pronunciation-label">US</span>
                            <span class="pronunciation-text" id="pronunciationUS">/--/</span>
                            <button class="pronunciation-audio-btn" id="audioUSBtn" disabled>
                                <i class="iconsax" data-icon="volume-high"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="definitions-section" id="definitionsSection">
                        <!-- Definitions will be loaded here -->
                    </div>
                    
                    <button class="btn-back" id="backBtn">
                        <i class="iconsax" data-icon="arrow-left"></i> Back
                    </button>
                </div>
            </div>
            <!-- Academic Word Lists Section -->
            <div class="word-lists-section" id="academicWordListsSection">
                <div class="section-header">
                    <h2 class="section-title">Essential IELTS Academic Word List</h2>
                    <button class="toggle-btn active" id="toggleMyWordListBtn" style="background:rgba(81,29,153,0.08) !important; color:#511D99 !important; border:1px solid rgba(81,29,153,0.16) !important;">My Word List</button>
                </div>
                
                @foreach($academicWordLists as $wordList)
                <div class="word-list-card {{ $wordList['is_locked'] ? 'locked' : '' }}"
                    data-list-id="{{ $wordList['source_id'] }}"
                    data-list-type="{{ $wordList['source_type'] }}">
                    @if($wordList['is_locked'])
                        <i class="iconsax lock-icon" data-icon="lock-1"></i>
                    @endif
                    
                    <div class="word-list-header">
                        <h3 class="word-list-name">{{ $wordList['name'] }} ({{ $wordList['band_level'] }})</h3>
                        <span class="word-count-badge" style="background:rgba(81,29,153,0.08) !important; color:#511D99 !important;">Tổng số từ: {{ $wordList['word_count'] }} từ</span>
                    </div>
                    <p class="word-list-description">{{ $wordList['description'] }}</p>
                    
                    <!-- Expanded Content (hidden by default) -->
                    <div class="word-list-expanded" id="expanded-{{ $wordList['id'] }}">
                        <div class="filter-bar">
                            <input type="text" class="filter-input" placeholder="Search the word...">
                            <button class="filter-btn" data-filter="alphabet">A-Z</button>
                            <button class="filter-btn" data-filter="learned">Đã học</button>
                        </div>
                        
                        <div class="words-container" id="words-{{ $wordList['id'] }}">
                            <!-- Words will be loaded here via AJAX -->
                        </div>
                        
                        <div class="action-bar">
                            <button class="action-btn btn-select-all">Select All</button>
                            <button class="action-btn btn-deselect-all">Deselect All</button>
                            <button class="action-btn btn-practice">Practice</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- My Word List Section (hidden by default) -->
            <div class="word-lists-section hidden" id="myWordListSection">
                <div class="section-header">
                    <h2 class="section-title">My Word List</h2>
                    <button class="toggle-btn" id="toggleAcademicListBtn" style="background:#fff !important; color:#511D99 !important; border:1px solid rgba(81,29,153,0.16) !important;">Academic Word List</button>
                </div>
                
                <div class="word-list-card" data-list-id="{{ $myWordList->id }}" data-list-type="my">
                    <div class="word-list-header">
                        <h3 class="word-list-name">{{ $myWordList->name }}</h3>
                        <span class="word-count-badge" style="background:rgba(81,29,153,0.08) !important; color:#511D99 !important;">Tổng số từ: {{ $myWordList->word_count }} từ</span>
                    </div>
                    <p class="word-list-description">{{ $myWordList->description }}</p>
                    
                    <!-- Expanded Content -->
                    <div class="word-list-expanded" id="expanded-my-{{ $myWordList->id }}">
                        <div class="filter-bar">
                            <input type="text" class="filter-input" placeholder="Search the word...">
                            <button class="filter-btn" data-filter="alphabet">A-Z</button>
                            <button class="filter-btn" data-filter="learned">Đã học</button>
                        </div>
                        
                        <div class="words-container" id="words-my-{{ $myWordList->id }}">
                            <!-- Words will be loaded here via AJAX -->
                        </div>
                        
                        <div class="action-bar">
                            <button class="action-btn btn-select-all">Select All</button>
                            <button class="action-btn btn-deselect-all">Deselect All</button>
                            <button class="action-btn btn-delete">Delete</button>
                            <button class="action-btn btn-practice">Practice</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Practice Mode Container (hidden by default) -->
            <div class="practice-mode-container hidden" id="practiceModeContainer">
                <div class="score-board">
                    <div class="score-item">
                        <div class="score-label">CORRECT: <span class="score-value correct" id="correctCount">0</span></div>
                    </div>
                    <div class="score-item">
                        <div class="score-label">INCORRECT: <span class="score-value incorrect" id="incorrectCount">0</span></div>
                    </div>
                </div>
                
                <div class="question-text" id="questionText"></div>
                
                <div class="answers-grid" id="answersGrid">
                    <!-- Answers will be loaded here -->
                </div>
                
                <div class="practice-actions">
                    <button class="practice-btn btn-exit" id="exitPracticeBtn">Exit</button>
                    <button class="practice-btn btn-next" id="nextQuestionBtn" disabled>Next</button>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats -->
        <div class="col-lg-4">
            <!-- Flashcards Widget -->
            <div class="stats-widget" style="background:#fff !important; border:1px solid rgba(81,29,153,0.10) !important; box-shadow:0 6px 18px rgba(0,0,0,0.04) !important;">
                <h3 class="widget-title">FLASHCARDS</h3>
                <div class="ranking-placeholder">
                    <p>Flashcards based on your band level will appear here</p>
                </div>
            </div>

            <!-- Streak Widget -->
            <div class="stats-widget" style="background:#fff !important; border:1px solid rgba(81,29,153,0.10) !important; box-shadow:0 6px 18px rgba(0,0,0,0.04) !important;">
                <h3 class="widget-title">STREAK</h3>
                <div class="streak-circles">
                    @for($i = 1; $i <= 7; $i++)
                        <div class="streak-circle {{ $i <= $userStats['streak'] ? 'active' : '' }}">{{ $i }}</div>
                    @endfor
                </div>
            </div>

            <!-- Ranking Widget -->
            <div class="stats-widget" style="background:#fff !important; border:1px solid rgba(81,29,153,0.10) !important; box-shadow:0 6px 18px rgba(0,0,0,0.04) !important;">
                <h3 class="widget-title">RANKING</h3>
                <div class="ranking-placeholder">
                    <p>Your ranking will appear here</p>
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

    /* =========================================================
     * State
     * ======================================================= */
    let currentWordListId    = null;   // id so cua list dang mo
    let currentWordListType  = null;   // 'academic' | 'bundle' | 'my'
    let currentQuestions     = [];
    let currentQuestionIndex = 0;
    let correctAnswers       = 0;
    let incorrectAnswers     = 0;
    let selectedAnswer       = null;
    let currentAudioUK       = null;
    let currentAudioUS       = null;
    let currentSearchedWord  = null;
    let lastSectionSelector  = '#academicWordListsSection';  // de nut Back quay dung cho

    const CSRF = $('meta[name="csrf-token"]').attr('content');

    /* =========================================================
     * Helpers
     * ======================================================= */

    // Escape HTML — noi dung tu file Excel cua teacher khong duoc tin tuyet doi
    function esc(s) {
        return $('<div>').text(s == null ? '' : s).html();
    }

    // Container .words-container cua list dang mo
    function containerOf($card) {
        return $card.find('.words-container');
    }

    // Cac checkbox dang tick — CHI trong list dang mo, khong quet toan trang
    function checkedIdsIn($scope) {
        let ids = [];
        $scope.find('.word-checkbox:checked').each(function() {
            ids.push($(this).data('word-id'));
        });
        return ids;
    }

    /* =========================================================
     * Toggle Academic <-> My Word List
     * ======================================================= */
    $('#toggleMyWordListBtn').on('click', function() {
        $('#academicWordListsSection').addClass('hidden');
        $('#myWordListSection').removeClass('hidden');
        $(this).removeClass('active');
        $('#toggleAcademicListBtn').addClass('active');
    });

    $('#toggleAcademicListBtn').on('click', function() {
        $('#myWordListSection').addClass('hidden');
        $('#academicWordListsSection').removeClass('hidden');
        $(this).removeClass('active');
        $('#toggleMyWordListBtn').addClass('active');
    });

    /* =========================================================
     * Mo / dong danh sach tu
     * ======================================================= */
    $(document).on('click', '.word-list-card', function() {
        let $card = $(this);

        if ($card.hasClass('locked')) {
            alert('Danh sách này đang khoá. Vui lòng mua khoá học tương ứng để mở.');
            return;
        }

        let listId    = $card.data('list-id');
        let listType  = $card.data('list-type');
        let $expanded = $card.find('.word-list-expanded');

        if ($expanded.hasClass('show')) {
            $expanded.removeClass('show');
            return;
        }

        $('.word-list-expanded').removeClass('show');
        $expanded.addClass('show');

        if (listType === 'my') {
            loadMyWordList();
        } else {
            loadWordList(listId, listType, containerOf($card));
        }
    });

    // Academic hoac Bundle
    function loadWordList(listId, listType, $container) {
        let url = listType === 'bundle'
            ? '/panel/dictionary/bundle-word-lists/' + listId
            : '/panel/dictionary/academic-word-lists/' + listId;

        $container.html('<p>Đang tải...</p>');

        $.get(url, function(response) {
            if (response.success) {
                renderWords(response.data.words, listId, listType, $container);
            } else {
                $container.html('<p>' + esc(response.message || 'Không tải được danh sách.') + '</p>');
            }
        }).fail(function() {
            $container.html('<p>Không tải được danh sách từ.</p>');
        });
    }

    // My Word List
    function loadMyWordList() {
        $.get('/panel/dictionary/my-word-list', function(response) {
            if (!response.success) return;

            let $card = $('.word-list-card[data-list-type="my"]');
            renderWords(response.data.flashcards, response.data.id, 'my', containerOf($card));
        });
    }

    /* =========================================================
     * Render danh sach tu
     * ======================================================= */
    function renderWords(words, listId, listType, $container) {
        $container.empty();

        if (!words || words.length === 0) {
            $container.html('<p>Chưa có từ nào.</p>');
        }

        (words || []).forEach(function(word) {
            let source = word.word_source || 'user';

            $container.append(
                '<div class="word-item" data-word-id="' + word.id + '" data-word-source="' + esc(source) + '">' +
                    '<input type="checkbox" class="word-checkbox" data-word-id="' + word.id + '">' +
                    '<div class="word-content">' +
                        '<div class="word-text">' + esc(word.word) + '</div>' +
                        (word.pronunciation ? '<div class="word-pronunciation">' + esc(word.pronunciation) + '</div>' : '') +
                        '<div class="word-definition">' + esc(word.definition) + '</div>' +
                    '</div>' +
                    '<div class="learned-badge ' + (word.is_learned ? 'show' : '') + '" ' +
                         'data-word-id="' + word.id + '" ' +
                         'data-word-source="' + esc(source) + '" ' +
                         'title="Đánh dấu đã học">' +
                        '<i class="iconsax" data-icon="tick-circle"></i>' +
                    '</div>' +
                '</div>'
            );
        });

        currentWordListId   = listId;
        currentWordListType = listType;
    }

    $(document).on('click', '.word-item', function(e) {
        if ($(e.target).is('.word-checkbox') || $(e.target).closest('.learned-badge').length) return;
        e.stopPropagation();

        let $item  = $(this);
        let source = $item.data('word-source');

        lastSectionSelector = $('#myWordListSection').hasClass('hidden')
            ? '#academicWordListsSection'
            : '#myWordListSection';

        // Academic: id thuoc bang academic_word_list_words, khong phai flashcard
        if (source === 'academic') {
            searchDictionary($item.find('.word-text').text());
            return;
        }

        $.get('/panel/dictionary/flashcards/' + $item.data('word-id') + '/detail', function(res) {
            if (!res.success) return;
            res.data.word_source === 'bundle'
                ? renderTeacherWordDetail(res.data)
                : searchDictionary(res.data.word);
        }).fail(function() {
            alert('Không tải được chi tiết từ.');
        });
    });

    // Ve chi tiet tu noi dung teacher upload
    function renderTeacherWordDetail(w) {
        $('#academicWordListsSection, #myWordListSection, #practiceModeContainer').addClass('hidden');
        $('#dictionaryResult').removeClass('hidden');

        $('#resultWord').text(w.word || '');
        $('#pronunciationUK').text(w.pronunciation || '/--/');
        $('#pronunciationUS').text(w.pronunciation || '/--/');

        currentAudioUK = w.audio_url ? new Audio(w.audio_url) : null;
        currentAudioUS = currentAudioUK;
        $('#audioUKBtn, #audioUSBtn').prop('disabled', !currentAudioUK);

        let html = '<div class="part-of-speech-section">';

        if (w.image_url) {
            html += '<img src="' + esc(w.image_url) + '" alt="" ' +
                    'style="max-width:320px;border-radius:12px;margin-bottom:16px;">';
        }
        if (w.part_of_speech) {
            html += '<div class="pos-header"><span class="pos-title">' + esc(w.part_of_speech) + '</span></div>';
        }
        if (w.definition) {
            html += '<div class="definition-item"><div class="definition-text">' + esc(w.definition) + '</div></div>';
        }
        if (w.translation_vi) {
            html += '<div class="definition-item"><div class="definition-text">' +
                    '<strong>Nghĩa tiếng Việt:</strong> ' + esc(w.translation_vi) + '</div></div>';
        }
        if (w.collocation) {
            html += '<div class="definition-item"><div class="definition-text">' +
                    '<strong>Collocation:</strong> ' + esc(w.collocation) + '</div></div>';
        }
        if (w.example) {
            html += '<div class="definition-item"><div class="example-text">• ' + esc(w.example) + '</div></div>';
        }

        html += '</div>';
        $('#definitionsSection').html(html);
    }

    /* =========================================================
     * Danh dau da hoc
     * ======================================================= */
    $(document).on('click', '.learned-badge', function(e) {
        e.stopPropagation();

        let $badge = $(this);
        let wordId = $badge.data('word-id');

        // Chi academic word list moi co endpoint mark-learned theo word_id.
        // Tu bundle / my word list duoc danh dau tu dong sau khi tra loi dung khi luyen tap.
        if (currentWordListType !== 'academic') {
            return;
        }

        $.post('/panel/dictionary/academic-word-lists/mark-learned', {
            word_id: wordId,
            _token: CSRF
        }, function(response) {
            if (response.success) {
                $badge.addClass('show');
            }
        });
    });

    /* =========================================================
     * Select / Deselect / Delete
     * ======================================================= */
    $(document).on('click', '.btn-select-all', function(e) {
        e.stopPropagation();
        $(this).closest('.word-list-expanded').find('.word-checkbox').prop('checked', true);
    });

    $(document).on('click', '.btn-deselect-all', function(e) {
        e.stopPropagation();
        $(this).closest('.word-list-expanded').find('.word-checkbox').prop('checked', false);
    });

    $(document).on('click', '.btn-delete', function(e) {
        e.stopPropagation();

        let $expanded   = $(this).closest('.word-list-expanded');
        let selectedIds = checkedIdsIn($expanded);

        if (selectedIds.length === 0) {
            alert('Vui lòng chọn từ cần xoá.');
            return;
        }

        if (!confirm('Bạn chắc chắn muốn xoá ' + selectedIds.length + ' từ khỏi danh sách?')) {
            return;
        }

        $.post('/panel/dictionary/my-word-list/bulk-delete', {
            flashcard_ids: selectedIds,
            _token: CSRF
        }, function(response) {
            if (response.success) {
                alert(response.message);
                loadMyWordList();
            }
        }).fail(function() {
            alert('Xoá thất bại. Vui lòng thử lại.');
        });
    });

    /* =========================================================
     * Luyen tap
     * ======================================================= */
    $(document).on('click', '.btn-practice', function(e) {
        e.stopPropagation();

        let $expanded   = $(this).closest('.word-list-expanded');
        let selectedIds = checkedIdsIn($expanded);

        if (selectedIds.length === 0) {
            alert('Vui lòng chọn từ để luyện tập.');
            return;
        }

        startPractice(selectedIds);
    });

    function startPractice(wordIds) {
        let url  = '/panel/dictionary/practice/start-my-word-list';
        let data = { _token: CSRF };

        if (currentWordListType === 'academic') {
            url = '/panel/dictionary/practice/start';
            data.word_list_id = currentWordListId;
            data.word_ids     = wordIds;
        } else if (currentWordListType === 'bundle') {
            url = '/panel/dictionary/practice/start-bundle-word-list';
            data.vocabulary_set_id = currentWordListId;
            data.flashcard_ids     = wordIds;
        } else {
            data.flashcard_ids = wordIds;
        }

        $.post(url, data, function(response) {
            if (!response.success) {
                alert(response.message || 'Không bắt đầu được phiên luyện tập.');
                return;
            }

            currentQuestions     = response.data.questions;
            currentQuestionIndex = 0;
            correctAnswers       = 0;
            incorrectAnswers     = 0;

            $('#academicWordListsSection, #myWordListSection').addClass('hidden');
            $('#practiceModeContainer').removeClass('hidden');

            showQuestion();
        }).fail(function() {
            alert('Không bắt đầu được phiên luyện tập.');
        });
    }

    // API tra ve 2 dinh dang khac nhau:
    //   academic       -> answers: ["word", "word", ...]
    //   my / bundle    -> answers: [{word, pronunciation}, ...]
    // Chuan hoa ve cung 1 dang de tranh loi [object Object]
    function normalizeAnswers(answers) {
        return (answers || []).map(function(a) {
            if (typeof a === 'string') {
                return { word: a, pronunciation: '' };
            }
            return { word: a.word || '', pronunciation: a.pronunciation || '' };
        });
    }

    function showQuestion() {
        if (currentQuestionIndex >= currentQuestions.length) {
            alert('Hoàn thành! Đúng: ' + correctAnswers + ' / Sai: ' + incorrectAnswers);
            exitPractice();
            return;
        }

        let question = currentQuestions[currentQuestionIndex];
        selectedAnswer = null;

        $('#questionText').text(question.question);
        $('#correctCount').text(correctAnswers);
        $('#incorrectCount').text(incorrectAnswers);
        $('#nextQuestionBtn').prop('disabled', true);

        let html = '';
        normalizeAnswers(question.answers).forEach(function(a) {
            html += '<div class="answer-card" data-answer="' + esc(a.word) + '">' +
                        '<div class="answer-word">' + esc(a.word) + '</div>' +
                        (a.pronunciation ? '<div class="answer-pronunciation">' + esc(a.pronunciation) + '</div>' : '') +
                    '</div>';
        });

        $('#answersGrid').html(html);
    }

    $(document).on('click', '.answer-card', function() {
        if (selectedAnswer !== null) return;   // da tra loi roi

        selectedAnswer = String($(this).data('answer'));

        let question  = currentQuestions[currentQuestionIndex];
        let isCorrect = selectedAnswer === question.correct_answer;

        $(this).addClass(isCorrect ? 'correct' : 'incorrect');

        if (!isCorrect) {
            $('.answer-card').filter(function() {
                return String($(this).data('answer')) === question.correct_answer;
            }).addClass('correct');
        }

        isCorrect ? correctAnswers++ : incorrectAnswers++;

        $('#correctCount').text(correctAnswers);
        $('#incorrectCount').text(incorrectAnswers);
        $('#nextQuestionBtn').prop('disabled', false);

        let submitData = {
            selected_answer: selectedAnswer,
            correct_answer:  question.correct_answer,
            _token: CSRF
        };

        if (currentWordListType === 'academic') {
            submitData.word_id = question.word_id;
        } else {
            submitData.flashcard_id = question.flashcard_id;
        }

        $.post('/panel/dictionary/practice/submit-answer', submitData);
    });

    $('#nextQuestionBtn').on('click', function() {
        currentQuestionIndex++;
        showQuestion();
    });

    $('#exitPracticeBtn').on('click', function() {
        exitPractice();
    });

    function exitPractice() {
        $('#practiceModeContainer').addClass('hidden');

        if (currentWordListType === 'my') {
            $('#myWordListSection').removeClass('hidden');
            loadMyWordList();
        } else {
            $('#academicWordListsSection').removeClass('hidden');
            let $expanded = $('.word-list-expanded.show');
            loadWordList(currentWordListId, currentWordListType, $expanded.find('.words-container'));
        }
    }

    /* =========================================================
     * Tra tu dien (API ngoai)
     * ======================================================= */
    $('#searchBtn').on('click', function() {
        let term = $('#searchInput').val().trim();
        if (term) {
            lastSectionSelector = '#academicWordListsSection';
            searchDictionary(term);
        }
    });

    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            $('#searchBtn').click();
        }
    });

    function searchDictionary(word) {
        $.ajax({
            url: '/panel/dictionary/search-first',
            method: 'POST',
            data: { word: word, _token: CSRF },
            beforeSend: function() {
                $('#searchBtn').prop('disabled', true).text('Đang tìm...');
            },
            success: function(response) {
                if (response.success && response.data) {
                    displayDictionaryResult(response.data);
                    currentSearchedWord = word;
                } else {
                    alert(response.message || 'Không tìm thấy từ này.');
                }
            },
            error: function() {
                alert('Tìm kiếm thất bại. Vui lòng thử lại.');
            },
            complete: function() {
                $('#searchBtn').prop('disabled', false).text('Search');
            }
        });
    }

    function displayDictionaryResult(data) {
        $('#academicWordListsSection, #myWordListSection, #practiceModeContainer').addClass('hidden');
        $('#dictionaryResult').removeClass('hidden');

        let wordText = data.headword || data.word || '';
        $('#resultWord').text(wordText);

        let ukPhonetic = '/--/', usPhonetic = '/--/';
        let ukAudio = '', usAudio = '';

        (data.pronunciations || []).forEach(function(p) {
            let lbl = (p.label || '').toUpperCase();
            if (lbl === 'UK') {
                ukPhonetic = p.ipa || '/--/';
                ukAudio    = p.audio || '';
            } else if (lbl === 'US') {
                usPhonetic = p.ipa || '/--/';
                usAudio    = p.audio || '';
            } else if (ukPhonetic === '/--/') {
                ukPhonetic = p.ipa || '/--/';
                ukAudio    = p.audio || '';
            }
        });

        $('#pronunciationUK').text(ukPhonetic);
        $('#pronunciationUS').text(usPhonetic);

        currentAudioUK = ukAudio ? new Audio(ukAudio) : null;
        currentAudioUS = usAudio ? new Audio(usAudio) : null;

        $('#audioUKBtn').prop('disabled', !currentAudioUK);
        $('#audioUSBtn').prop('disabled', !currentAudioUS);

        let html = '';
        if (data.meanings && data.meanings.length > 0) {
            data.meanings.forEach(function(meaning) {
                html += '<div class="part-of-speech-section">';
                html += '<div class="pos-header">';
                html += '<span class="pos-title">' + esc(meaning.partOfSpeech) + '</span>';
                html += '<button class="btn-save-pos" data-pos="' + esc(meaning.partOfSpeech) + '" ' +
                        'data-word="' + esc(wordText) + '">Save</button>';
                html += '</div>';

                (meaning.definitions || []).forEach(function(def, i) {
                    html += '<div class="definition-item">';
                    html += '<div class="definition-text">' + (i + 1) + '. ' + esc(def.definition) + '</div>';
                    if (def.example) {
                        html += '<div class="example-text">• ' + esc(def.example) + '</div>';
                    }
                    html += '</div>';
                });

                html += '</div>';
            });
        } else {
            html = '<p>Không có định nghĩa.</p>';
        }

        $('#definitionsSection').html(html);

        // Luu lai de nut Save doc duoc
        $('#definitionsSection').data('meanings', data.meanings || []);
    }

    // Nut Save theo tung tu loai (delegated — tranh bind chong nhieu lan)
    $(document).on('click', '.btn-save-pos', function(e) {
        e.stopPropagation();

        let $btn         = $(this);
        let word         = $btn.data('word');
        let partOfSpeech = $btn.data('pos');
        let meanings     = $('#definitionsSection').data('meanings') || [];

        let meaning    = meanings.find(function(m) { return m.partOfSpeech === partOfSpeech; });
        let definition = '';
        let example    = '';

        if (meaning && meaning.definitions && meaning.definitions.length > 0) {
            definition = meaning.definitions[0].definition;
            example    = meaning.definitions[0].example || '';
        }

        $.ajax({
            url: '/panel/dictionary/save-flashcard',
            method: 'POST',
            data: {
                word: word,
                part_of_speech: partOfSpeech,
                definition: definition,
                example: example,
                _token: CSRF
            },
            beforeSend: function() {
                $btn.prop('disabled', true).text('Đang lưu...');
            },
            success: function(response) {
                if (response.success) {
                    $btn.addClass('saved').text('Đã lưu');
                    setTimeout(function() { $btn.prop('disabled', false); }, 1000);
                } else {
                    alert(response.message || 'Lưu thất bại.');
                    $btn.prop('disabled', false).text('Save');
                }
            },
            error: function() {
                alert('Lưu thất bại.');
                $btn.prop('disabled', false).text('Save');
            }
        });
    });

    /* =========================================================
     * Back + Audio
     * ======================================================= */
    $('#backBtn').on('click', function() {
        $('#dictionaryResult').addClass('hidden');
        $(lastSectionSelector).removeClass('hidden');

        if (currentAudioUK) { currentAudioUK.pause(); currentAudioUK = null; }
        if (currentAudioUS) { currentAudioUS.pause(); currentAudioUS = null; }
    });

    $('#audioUKBtn').on('click', function() {
        if (currentAudioUK) currentAudioUK.play();
    });

    $('#audioUSBtn').on('click', function() {
        if (currentAudioUS) currentAudioUS.play();
    });

    /* =========================================================
     * Loc / tim trong danh sach
     * ======================================================= */
    $(document).on('click', '.filter-btn', function(e) {
        e.stopPropagation();

        let filter     = $(this).data('filter');
        let $container = $(this).closest('.word-list-expanded').find('.words-container');
        let $words     = $container.find('.word-item');

        if (filter === 'alphabet') {
            let sorted = $words.sort(function(a, b) {
                return $(a).find('.word-text').text().localeCompare($(b).find('.word-text').text());
            });
            $container.html(sorted);
        } else if (filter === 'learned') {
            let showOnlyLearned = !$(this).hasClass('active');
            $words.each(function() {
                let learned = $(this).find('.learned-badge').hasClass('show');
                $(this).toggle(showOnlyLearned ? learned : true);
            });
        }

        $(this).toggleClass('active');
    });

    $(document).on('keyup', '.filter-input', function(e) {
        e.stopPropagation();

        let term       = $(this).val().toLowerCase();
        let $container = $(this).closest('.word-list-expanded').find('.words-container');

        $container.find('.word-item').each(function() {
            let word = $(this).find('.word-text').text().toLowerCase();
            $(this).toggle(word.indexOf(term) !== -1);
        });
    });

    // Chan click ben trong vung mo rong lam sap danh sach
    $(document).on('click', '.word-list-expanded', function(e) {
        e.stopPropagation();
    });

})(jQuery);
</script>
@endpush