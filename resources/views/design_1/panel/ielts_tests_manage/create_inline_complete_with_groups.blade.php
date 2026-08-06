@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
<style>

    /* --- FIX LỖI HOVER & ACTIVE CHO NÚT CĂN LỀ --- */

/* 1. Sửa lỗi Hover bị trắng: Đổi nền sang tím nhạt, giữ icon màu tím */
.note-editor .note-toolbar .note-btn:hover {
    background-color: #e0e7ff !important;
    border-color: #511D99 !important;
    color: #511D99 !important;
}
.note-editor .note-toolbar .note-btn:hover i {
    color: #511D99 !important;
}

/* 2. Trạng thái Active (Đang được chọn): Nền tím, Icon trắng */
.note-editor .note-toolbar .note-btn.active-align {
    background-color: #511D99 !important;
    color: #ffffff !important;
    border-color: #511D99 !important;
}
.note-editor .note-toolbar .note-btn.active-align i {
    color: #ffffff !important;
}

/* 2. Ép trình duyệt phải nhận diện và ưu tiên inline style (style="text-align:...") do Summernote sinh ra */
.note-editor .note-editable [style*="text-align: left"],
.part-rich-content [style*="text-align: left"],
.group-rich-content [style*="text-align: left"],
.question-card-body [style*="text-align: left"] {
    text-align: left !important;
}

.note-editor .note-editable [style*="text-align: center"],
.part-rich-content [style*="text-align: center"],
.group-rich-content [style*="text-align: center"],
.question-card-body [style*="text-align: center"] {
    text-align: center !important;
}

.note-editor .note-editable [style*="text-align: right"],
.part-rich-content [style*="text-align: right"],
.group-rich-content [style*="text-align: right"],
.question-card-body [style*="text-align: right"] {
    text-align: right !important;
}

.note-editor .note-editable [style*="text-align: justify"],
.part-rich-content [style*="text-align: justify"],
.group-rich-content [style*="text-align: justify"],
.question-card-body [style*="text-align: justify"] {
    text-align: justify !important;
}

/* 3. Dự phòng trường hợp Summernote sinh ra class thay vì inline style (tùy version Bootstrap) */
.note-editor .note-editable .text-left, .part-rich-content .text-left, .group-rich-content .text-left { text-align: left !important; }
.note-editor .note-editable .text-center, .part-rich-content .text-center, .group-rich-content .text-center { text-align: center !important; }
.note-editor .note-editable .text-right, .part-rich-content .text-right, .group-rich-content .text-right { text-align: right !important; }
.note-editor .note-editable .text-justify, .part-rich-content .text-justify, .group-rich-content .text-justify { text-align: justify !important; }

.part-rich-content,
.group-rich-content,
.question-card-body {
    white-space: pre-wrap; /* Giúp hiển thị đúng dấu xuống dòng \n nếu text thuần */
    word-wrap: break-word;
}
.part-rich-content p,
.group-rich-content p,
.question-card-body p {
    margin-bottom: 8px !important; /* Bắt buộc có khoảng cách giữa các đoạn văn <p> */
}
.part-rich-content p:last-child,
.group-rich-content p:last-child,
.question-card-body p:last-child {
    margin-bottom: 0 !important;
}


.note-editor .note-editable ul {
    list-style: disc !important;
    padding-left: 24px !important;
    margin: 0 0 10px 0 !important;
}
.note-editor .note-editable ol {
    list-style: decimal !important;
    padding-left: 24px !important;
    margin: 0 0 10px 0 !important;
}
.note-editor .note-editable ul li,
.note-editor .note-editable ol li {
    list-style: inherit !important;
    display: list-item !important;
}

/* Đồng bộ hiển thị khi render lại nội dung (phần preview part/group instructions, collapsible richtext) */
.part-rich-content ul,
.group-rich-content ul,
.question-card-body ul {
    list-style: disc !important;
    padding-left: 24px !important;
}
.part-rich-content ol,
.group-rich-content ol,
.question-card-body ol {
    list-style: decimal !important;
    padding-left: 24px !important;
}
.test-creator-enhanced {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
}

.hidden {
    display: none !important;
}

.test-info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.section-container {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.section-container.focused {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}

.skill-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    font-weight: bold;
}

.skill-icon.listening { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.skill-icon.reading { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.skill-icon.writing { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.skill-icon.speaking { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.skill-icon.grammar { background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); }
.skill-icon.vocabulary { background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%); }

.group-item {
    background: #f9fafb;
    border-left: 4px solid #511D99;
    padding: 16px;
    margin-bottom: 12px;
    border-radius: 6px;
    position: relative;
}

.part-item {
    background: #f8fffb;
    border: 2px solid #bbf7d0;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
    position: relative;
}

.part-item .part-title {
    font-weight: 700;
    font-size: 16px;
    color: #14532d;
}

.title-expand-wrap {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding-right: 220px;
    margin-bottom: 8px;
}

.collapsible-title {
    flex: 1;
    min-width: 0;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-word;
}

.collapsible-title.expanded {
    display: block;
    -webkit-line-clamp: unset;
}

.title-toggle-btn {
    flex-shrink: 0;
}

.collapsible-richtext {
    position: relative;
    transition: max-height 0.2s ease;
}

.collapsible-richtext.collapsed {
    max-height: 220px;
    overflow: hidden;
}

.collapsible-richtext.collapsed::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 64px;
    background: linear-gradient(to bottom, rgba(248, 255, 251, 0), rgba(248, 255, 251, 1));
    pointer-events: none;
}

.group-item .collapsible-richtext.collapsed::after {
    background: linear-gradient(to bottom, rgba(249, 250, 251, 0), rgba(249, 250, 251, 1));
}

.content-toggle-wrap {
    margin-bottom: 8px;
}

.part-item .part-meta {
    display: flex;
    gap: 16px;
    font-size: 13px;
    color: #4b5563;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.part-item .part-meta span {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.part-item .part-media {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.part-item .part-actions {
    display: flex;
    gap: 8px;
    position: absolute;
    top: 12px;
    right: 12px;
}

.part-item .groups-list {
    margin-top: 12px;
}

.group-item .group-title {
    font-weight: 600;
    font-size: 16px;
    color: #1f2937;
}

.question-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 8px;
}

.question-card-main {
    flex: 1;
    min-width: 0;
}

.question-title-wrap {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-top: 8px;
}

.question-title-text {
    font-weight: 700;
    color: #111827;
}

.question-preview-text {
    margin-top: 6px;
    font-weight: 500;
    color: #1f2937;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.question-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.group-item .group-meta {
    display: flex;
    gap: 16px;
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.group-item .group-meta span {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.group-item .group-media {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.group-item .media-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #e0e7ff;
    color: #511D99;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.group-item .group-actions {
    display: flex;
    gap: 8px;
    position: absolute;
    top: 12px;
    right: 12px;
}

.add-group-form {
    background: #f0fdf4;
    border: 2px dashed #22c55e;
    border-radius: 8px;
    padding: 20px;
    margin-top: 16px;
}

.add-part-form {
    background: #eff6ff;
    border: 2px dashed #511D99;
    border-radius: 8px;
    padding: 20px;
    margin-top: 16px;
}

.add-group-form.hidden {
    display: none;
}

.add-part-form.hidden {
    display: none;
}

.form-row {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.form-row .form-group {
    flex: 1;
    min-width: 200px;
    margin-bottom: 0;
}

.form-row.full .form-group {
    flex: 100%;
}

.file-upload-area {
    background: white;
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 8px;
}

.file-upload-area:hover {
    border-color: #511D99;
    background: #ffff;
}

.file-upload-area input[type="file"] {
    display: none;
}

.file-preview {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #e0e7ff;
    color: #511D99;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    margin-top: 8px;
}

.file-preview .remove-file-btn {
    border: none;
    background: transparent;
    color: #ef4444;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    font-size: 14px;
}

.questions-list {
    background: #f9fafb;
    border-radius: 8px;
    padding: 12px;
    margin-top: 12px;
    max-height: none;
    overflow-y: visible;
}

.question-badge {
    display: inline-block;
    background: #f3e8ff;
    color: #7c3aed;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 11px;
    margin-top: 4px;
    font-weight: 600;
}

.btn-add-group {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #22c55e;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.btn-add-group:hover {
    background: #16a34a;
}

.btn-add-part {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #511D99;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.btn-add-part:hover {
    background: #511D99;
}

.completeness-check {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 24px;
}

.completeness-check.success {
    background: #d1fae5;
    border-left-color: #10b981;
}

.completeness-check ul {
    margin: 0;
    padding-left: 20px;
}

.completeness-check li {
    margin: 4px 0;
    font-size: 14px;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    .title-expand-wrap {
        padding-right: 0;
    }
    .group-item .group-actions {
        position: static;
        margin-top: 12px;
    }
    .part-item .part-actions {
        position: static;
        margin-top: 12px;
    }
}

.mc-option-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px 10px;
}
.mc-option-row input[type="text"] {
    flex: 1;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 13px;
}
.mc-option-row .correct-marker {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: #374151;
    white-space: nowrap;
}
.mc-option-row .btn-remove-option {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    padding: 0 4px;
    font-size: 14px;
    line-height: 1;
}
.mc-options-container {
    background: #ffffff;
    border: 1px solid #d3b8f8;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 8px;
}
.question-type-badge {
    display: inline-block;
    background: #ffffff;
    color: #511D99;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 4px;
}
.question-answer-badge {
    display: inline-block;
    background: #d1fae5;
    color: #065f46;
    padding: 1px 6px;
    border-radius: 3px;
    font-size: 11px;
    margin-left: 4px;
}

.btn {
    border-radius: 12px;
    background: #fff;
    color: #511D99;
    border: 1.5px solid #511D99;
    transition: background .15s, color .15s, border-color .15s;
}

.btn:hover {
    background: #511D99;
    color: #fff;
    border-color: #511D99;
}

.btn-1 {
    border-radius: 12px;
    background: #511D99;
    color: #fff;
    border: 1.5px solid #511D99;
    transition: background .15s, color .15s, border-color .15s;
}
.btn-1:hover {
    background: #fff;
    color: #511D99;
    border-color: #511D99;
}
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="section-title">
            <i class="fas fa-plus-circle mr-10"></i>
            {{ $pageTitle ?? 'Create IELTS Test with Parts and Question Groups' }}
        </h1>
        <a href="{{ $cancelUrl ?? route('panel.my_ielts_tests.create') }}" class="btn btn-sm rounded-12 d-none d-md-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;">
            <i class="fas fa-arrow-left mr-5"></i>Back
        </a>
    </div>

    <div class="alert alert-info mb-24">
        <i class="fas fa-info-circle mr-10"></i>
        <strong>Hierarchical Structure:</strong> Test → Sections → Parts → Question Groups → Questions
    </div>

    <form id="testForm" action="{{ $formAction ?? route('panel.my_ielts_tests.store_with_groups') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Test Information --}}
        <div class="test-info-card">
            <h3 class="font-16 font-weight-bold mb-20">
                <i class="fas fa-info-circle mr-8"></i>Test Information
            </h3>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Test Type *</label>
                        <select id="testTypeSelect" name="type" class="form-control @error('type') is-invalid @enderror" required onchange="updateTestRequirements()">
                            <option value="">-- Select Type --</option>
                            <option value="mock" {{ old('type', $test->type ?? '') === 'mock' ? 'selected' : '' }}>Mock Test (All 4 Skills)</option>
                            <option value="practice" {{ old('type', $test->type ?? '') === 'practice' ? 'selected' : '' }}>Practice Test (1 Skill)</option>
                            <option value="diagnostic" {{ old('type', $test->type ?? '') === 'diagnostic' ? 'selected' : '' }}>Diagnostic Test (Bài test đầu vào)</option>
                        </select>
                        @error('type')
                            <div class="text-danger font-size-sm mt-5">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Test Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $test->title ?? '') }}" required placeholder="e.g. Full Mock Test - March 2026">
                        @error('title')
                            <div class="text-danger font-size-sm mt-5">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Format *</label>
                        <select name="format" class="form-control @error('format') is-invalid @enderror" required>
                            <option value="">-- Select --</option>
                            <option value="academic" {{ old('format', $test->format ?? '') === 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="general" {{ old('format', $test->format ?? '') === 'general' ? 'selected' : '' }}>General Training</option>
                            <option value="both" {{ old('format', $test->format ?? '') === 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                        @error('format')
                            <div class="text-danger font-size-sm mt-5">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control js-richtext-editor" data-height="150" rows="2" placeholder="Brief description...">{{ old('description', $test->description ?? '') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Difficulty Level</label>
                        <select name="difficulty_level" class="form-control">
                            <option value="intermediate" {{ old('difficulty_level', $test->difficulty_level ?? 'intermediate') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="beginner" {{ old('difficulty_level', $test->difficulty_level ?? '') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="advanced" {{ old('difficulty_level', $test->difficulty_level ?? '') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                            <option value="mixed" {{ old('difficulty_level', $test->difficulty_level ?? '') === 'mixed' ? 'selected' : '' }}>Mixed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Target Band Min</label>
                        <input type="number" name="target_band_min" class="form-control" min="0" max="9" step="0.5" value="{{ old('target_band_min', $test->target_band_min ?? 5.0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Target Band Max</label>
                        <input type="number" name="target_band_max" class="form-control" min="0" max="9" step="0.5" value="{{ old('target_band_max', $test->target_band_max ?? 8.0) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Completeness Status --}}
        <div id="completenessStatus" class="completeness-check">
            <strong><i class="fas fa-check-circle mr-8"></i>Test Completeness</strong>
            <ul id="completenessChecklist" style="margin-top: 12px;">
                <li><span class="status-listening">○</span> Listening <span class="req-listening">(4 parts, 40 questions)</span></li>
                <li><span class="status-reading">○</span> Reading <span class="req-reading">(3 parts, 40 questions)</span></li>
                <li><span class="status-writing">○</span> Writing <span class="req-writing">(2 parts, 2 tasks)</span></li>
                <li><span class="status-speaking">○</span> Speaking <span class="req-speaking">(3 parts, 3 prompts)</span></li>
                <li><span class="status-grammar">○</span> Grammar <span class="req-grammar">(at least 1 part)</span></li>
                <li><span class="status-vocabulary">○</span> Vocabulary <span class="req-vocabulary">(at least 1 part)</span></li>
            </ul>
        </div>

        {{-- Sections with Question Groups --}}
        <div id="sectionsContainer">
            {{-- Sections will be added dynamically --}}
        </div>

        {{-- Hidden field for question groups data --}}
        <input type="hidden" id="questionGroupsData" name="question_groups_data" value="">
        <input type="hidden" id="submitActionInput" name="submit_action" value="submit">
        <div id="uploadedFilesContainer" style="display:none;"></div>

        {{-- Action Buttons --}}
        <div class="mt-30 d-flex justify-content-between align-items-center gap-8 flex-wrap">
            <div id="autosaveStatus" class="text-muted font-12">
                Auto Save: chưa có bản nháp cục bộ
            </div>
            <div class="d-flex justify-content-end align-items-center gap-8 flex-wrap">
            <a href="{{ $cancelUrl ?? route('panel.my_ielts_tests.index') }}" class="btn btn-lg rounded-12 d-none d-md-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;">
                <i class="fas fa-times mr-5"></i>Cancel
            </a>
            <button type="submit" name="submit_action" value="draft" formnovalidate class="btn btn-outline-secondary btn-lg rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" id="saveDraftBtn">
                <i class="fas fa-save mr-5"></i>Lưu nháp
            </button>
            <button type="submit" name="submit_action" value="preview" class="btn btn-outline-primary btn-lg rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" id="previewBtn">
                <i class="fas fa-eye mr-5"></i>Xem trước như học viên
            </button>
            <button type="submit" name="submit_action" value="submit" class="btn-1 btn-lg rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" id="submitBtn">
                {{ $submitButtonText ?? 'Submit Test for Approval' }}
            </button>
            </div>
        </div>
    </form>

    {{-- Template for Section --}}
    <template id="sectionTemplate">
        <div class="section-container" data-skill="">
            <div class="section-header">
                <div>
                    <div class="skill-icon"></div>
                    <h4 class="mb-0 mt-8"></h4>
                    <small class="text-muted">Parts: <span class="part-count">0</span> | Groups: <span class="group-count">0</span> | Questions: <span class="question-count">0</span></small>
                </div>
                <button type="button" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="toggleAddPartForm(this)">
                    <i class="fas fa-plus mr-5"></i>Add Part
                </button>
            </div>

            <div class="section-media-block hidden mb-15">
                <div class="form-group mb-0">
                    <label class="input-label font-weight-bold">Listening Audio for the whole section</label>
                    <div class="file-upload-area" onclick="this.querySelector('input[type=file]').click()">
                        <i class="fas fa-headphones fa-2x text-muted mb-10"></i>
                        <p class="mb-0 small">Click to upload one audio file for all Listening parts</p>
                        <input type="file" class="section-audio-file" name="section_media[listening][audio]" accept="audio/*">
                    </div>
                    <div class="file-preview section-audio-preview" style="display:none;"></div>
                    <small class="text-muted d-block mt-2">This audio is shared by every part in the Listening section.</small>
                </div>
            </div>

            <div class="parts-list"></div>

            {{-- Add Part Form --}}
            <div class="add-part-form hidden">
                <h5 class="mb-20"><i class="fas fa-plus-circle mr-8"></i>Add New Part</h5>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="input-label">Part Title / Prompt *</label>
                        <textarea class="form-control part-title-input" rows="2" placeholder="e.g. Part 1 - Social Conversation"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="input-label">Part Instructions</label>
                        <textarea class="form-control part-instructions-input js-richtext-editor" data-height="150" rows="2" placeholder="Optional instructions for this part..."></textarea>
                    </div>
                </div>

                {{-- Media Uploads --}}
                <div class="form-row full">
                    <div class="form-group">
                        <label class="input-label">📁 Media Files</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="input-label">Audio File (MP3)</label>
                        <div class="file-upload-area" onclick="this.querySelector('input[type=file]').click()">
                            <i class="fas fa-volume-up fa-2x text-muted mb-10"></i>
                            <p class="mb-0 small">Click to upload audio</p>
                            <input type="file" class="group-audio-file" accept="audio/*">
                        </div>
                        <div class="file-preview group-audio-preview" style="display:none;"></div>
                    </div>

                    <div class="col-md-4">
                        <label class="input-label">Image File (JPG, PNG)</label>
                        <div class="file-upload-area" onclick="this.querySelector('input[type=file]').click()">
                            <i class="fas fa-image fa-2x text-muted mb-10"></i>
                            <p class="mb-0 small">Click to upload image</p>
                            <input type="file" class="group-image-file" accept="image/*">
                        </div>
                        <div class="file-preview group-image-preview" style="display:none;"></div>
                    </div>

                    <div class="col-md-4">
                        <label class="input-label">Video File (MP4)</label>
                        <div class="file-upload-area" onclick="this.querySelector('input[type=file]').click()">
                            <i class="fas fa-video fa-2x text-muted mb-10"></i>
                            <p class="mb-0 small">Click to upload video</p>
                            <input type="file" class="group-video-file" accept="video/*">
                        </div>
                        <div class="file-preview group-video-preview" style="display:none;"></div>
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group mt-12">
                        <label class="input-label">Part Description</label>
                        <textarea class="form-control part-passage js-richtext-editor" data-height="180" rows="3" placeholder="Enter reading passage, transcript, or notes for this part..."></textarea>
                    </div>
                </div>

                <div class="mt-12">
                    <button type="button" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="addPart(this)">
                        <i class="fas fa-plus mr-5"></i>Create Part
                    </button>
                    <button type="button" class="btn btn-sm rounded-12 d-none d-md-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="toggleAddPartForm(this)">
                        <i class="fas fa-times mr-5"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </template>
</section>

@push('scripts_bottom')
<script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
<script>
const SECTIONS_CONFIG = {
    listening: { skill: 'listening', title: 'Listening', icon: '🔊', mockParts: 4, mockQuestions: 40 },
    reading: { skill: 'reading', title: 'Reading', icon: '📖', mockParts: 3, mockQuestions: 40 },
    writing: { skill: 'writing', title: 'Writing', icon: '✍️', mockParts: 2, mockQuestions: 2 },
    speaking: { skill: 'speaking', title: 'Speaking', icon: '🎤', mockParts: 3, mockQuestions: 3 },
    grammar: { skill: 'grammar', title: 'Grammar', icon: '🧩', mockParts: 0, mockQuestions: 0 },
    vocabulary: { skill: 'vocabulary', title: 'Vocabulary', icon: '📚', mockParts: 0, mockQuestions: 0 }
};

let currentTestType = @json($currentTestType ?? null);
let uploadSequence = 0;
const AUTOSAVE_INTERVAL_MS = 15000;
const AUTOSAVE_MAX_AGE_MS = 7 * 24 * 60 * 60 * 1000;
const AUTOSAVE_DEBOUNCE_MS = 1200;
const AUTOSAVE_STORAGE_KEY = 'ielts-inline-autosave:{{ auth()->id() }}:{{ isset($test) ? ('test-' . $test->id) : 'new' }}';
const AUTOSAVE_SERVER_SAVE_URL = @json(route('panel.my_ielts_tests.autosave_inline.save'));
const AUTOSAVE_SERVER_LOAD_URL = @json(route('panel.my_ielts_tests.autosave_inline.get'));
const AUTOSAVE_CSRF_TOKEN = @json(csrf_token());
const AUTOSAVE_TEST_ID = @json(isset($test) ? (int) $test->id : null);
const AUTOSAVE_RESTORE_ENABLED = @json($autosaveRestoreEnabled ?? true);
let autosaveServerSeed = @json($autosavePayload ?? null);
let autosaveTimer = null;
let autosaveDebounceTimer = null;
let autosaveLastSignature = '';
let autosaveLastServerSignature = '';
let autosaveServerRequestInFlight = false;
let autosavePendingServerPayload = null;

@php
    $inlineTestData = $testData ?? [
        'sections' => [
            'listening' => ['parts' => []],
            'reading' => ['parts' => []],
            'writing' => ['parts' => []],
            'speaking' => ['parts' => []],
            'grammar' => ['parts' => []],
            'vocabulary' => ['parts' => []],
        ],
    ];
@endphp

let testData = @json($inlineTestData);

const ANSWER_HELP_EDITOR_TOOLBAR = [
    ['style', ['style']],
    ['font', ['bold', 'italic', 'underline', 'clear']],
    ['color', ['foreColor', 'backColor']],
    ['para', ['ul', 'ol']], // Bỏ 'paragraph' ở đây
    ['alignment', ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify']], // Thêm nhóm 4 nút căn lề độc lập
    ['view', ['codeview']]
];

const CONTENT_EDITOR_TOOLBAR = [
    ['style', ['style']],
    ['font', ['bold', 'italic', 'underline', 'clear']],
    ['color', ['foreColor', 'backColor']],
    ['para', ['ul', 'ol']], // Bỏ 'paragraph' ở đây
    ['alignment', ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify']], // Thêm nhóm 4 nút căn lề độc lập
    ['insert', ['picture', 'link']],
    ['view', ['codeview']]
];

// 1. Hàm tự viết để bám sát và căn lề đúng thẻ cha
function safeAlign(context, alignValue) {
    var range = context.invoke('editor.createRange');
    if (!range) return;

    var $node = $(range.sc).closest('p, div, h1, h2, h3, h4, h5, h6, td, li');

    if (!$node.length || $node.hasClass('note-editable')) {
        context.invoke('editor.formatBlock', 'p');
        range = context.invoke('editor.createRange');
        $node = $(range.sc).closest('p, div, h1, h2, h3, h4, h5, h6, td, li');
    }

    if ($node.length && !$node.hasClass('note-editable')) {
        // Áp dụng style căn lề
        $node.css('text-align', alignValue);
        
        // CẬP NHẬT GIAO DIỆN NÚT BẤM NGAY LẬP TỨC
        var $toolbar = context.layoutInfo.toolbar;
        $toolbar.find('.custom-align-btn').removeClass('active-align');
        $toolbar.find('.align-btn-' + alignValue).addClass('active-align');

        // Lưu trạng thái
        context.invoke('editor.saveRange');
        context.triggerEvent('change', context.invoke('code'));
    }
}

// 2. Gắn hàm an toàn vào 4 nút bấm
const customAlignmentButtons = {
    alignLeft: function (context) {
        var ui = $.summernote.ui;
        return ui.button({
            className: 'custom-align-btn align-btn-left active-align', // Mặc định sáng nút trái
            contents: '<i class="fas fa-align-left"></i>',
            tooltip: 'Căn trái',
            click: function () { safeAlign(context, 'left'); }
        }).render();
    },
    alignCenter: function (context) {
        var ui = $.summernote.ui;
        return ui.button({
            className: 'custom-align-btn align-btn-center',
            contents: '<i class="fas fa-align-center"></i>',
            tooltip: 'Căn giữa',
            click: function () { safeAlign(context, 'center'); }
        }).render();
    },
    alignRight: function (context) {
        var ui = $.summernote.ui;
        return ui.button({
            className: 'custom-align-btn align-btn-right',
            contents: '<i class="fas fa-align-right"></i>',
            tooltip: 'Căn phải',
            click: function () { safeAlign(context, 'right'); }
        }).render();
    },
    alignJustify: function (context) {
        var ui = $.summernote.ui;
        return ui.button({
            className: 'custom-align-btn align-btn-justify',
            contents: '<i class="fas fa-align-justify"></i>',
            tooltip: 'Căn đều hai bên',
            click: function () { safeAlign(context, 'justify'); }
        }).render();
    }
};

function getEditorHtmlValue(element) {
    if (!element) {
        return '';
    }

    const $element = $(element);
    if (jQuery().summernote && $element.next('.note-editor').length) {
        return ($element.summernote('code') || '').trim();
    }

    return ($element.val() || '').trim();
}

function setEditorHtmlValue(element, value) {
    if (!element) {
        return;
    }

    const $element = $(element);
    if (jQuery().summernote && $element.next('.note-editor').length) {
        $element.summernote('code', value || '');
        return;
    }

    $element.val(value || '');
}

function editorHtmlToPlainText(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html || '';
    return (tmp.textContent || tmp.innerText || '').replace(/\u00a0/g, ' ').trim();
}

function getEditorPlainTextValue(element) {
    return editorHtmlToPlainText(getEditorHtmlValue(element));
}

function getAnswerHelpValue(element) {
    return getEditorHtmlValue(element);
}

function setAnswerHelpValue(element, value) {
    setEditorHtmlValue(element, value);
}

function getContentEditorValue(element) {
    return getEditorHtmlValue(element);
}

function getContentEditorPlainValue(element) {
    return getEditorPlainTextValue(element);
}

function setContentEditorValue(element, value) {
    setEditorHtmlValue(element, value);
}

function initAnswerHelpEditors(context) {
    if (!jQuery().summernote) {
        return;
    }

    const $context = context ? $(context) : $(document);
    const $editors = $context.find('.js-answer-help-editor').filter(function () {
        return !$(this).next('.note-editor').length;
    });

    if (!$editors.length) {
        return;
    }

    makeSummernote($editors, 180, undefined, {
        toolbar: ANSWER_HELP_EDITOR_TOOLBAR,
        buttons: customAlignmentButtons
    });
}

function fixSummernoteDropdowns(context) {
    const $context = context ? $(context) : $(document);

    $context.find('.note-toolbar .dropdown-toggle').off('click.snfix').on('click.snfix', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $menu = $(this).next('.dropdown-menu');

        // Đóng các dropdown khác đang mở (toàn trang)
        $('.note-toolbar .dropdown-menu').not($menu).removeClass('show').hide();

        const isOpen = $menu.hasClass('show') || $menu.is(':visible');
        if (isOpen) {
            $menu.removeClass('show').hide();
        } else {
            $menu.addClass('show').show();
        }
    });

    // Click ra ngoài toolbar thì đóng hết dropdown
    if (!window.__snfixCloseBound) {
        window.__snfixCloseBound = true;
        $(document).on('click.snfixclose', function (e) {
            if (!$(e.target).closest('.note-toolbar .dropdown').length) {
                $('.note-toolbar .dropdown-menu').removeClass('show').hide();
            }
        });
    }
}

const RICHTEXT_IMAGE_UPLOAD_URL = @json(route('panel.my_ielts_tests.richtext_image_upload'));
const RICHTEXT_IMAGE_CSRF_TOKEN = @json(csrf_token());

function uploadRichTextImageFile(file, editorEl) {
    const formData = new FormData();
    formData.append('file', file);

    fetch(RICHTEXT_IMAGE_UPLOAD_URL, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-CSRF-TOKEN': RICHTEXT_IMAGE_CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
        .then(function (response) {
            if (!response.ok) {
                // Đọc body lỗi (thường là JSON validation errors từ Laravel)
                // để biết chính xác lý do fail thay vì throw chung chung.
                return response.json().then(function (errorBody) {
                    const message = errorBody && errorBody.errors && errorBody.errors.file
                        ? errorBody.errors.file.join(' ')
                        : (errorBody && errorBody.message) || ('HTTP ' + response.status);
                    throw new Error(message);
                });
            }
            return response.json();
        })
        .then(function (result) {
            if (result && result.success && result.url) {
                $(editorEl).summernote('insertImage', result.url, function ($image) {
                    $image.css('max-width', '100%');
                });
            } else {
                console.error('Upload response không hợp lệ:', result);
                alert('Tải ảnh lên thất bại. Vui lòng thử lại.');
            }
        })
        .catch(function (error) {
            console.error('Lỗi upload ảnh rich-text:', error);
            alert('Tải ảnh lên thất bại: ' + error.message);
        });
}

function initContentEditors(context) {
    if (!jQuery().summernote) {
        return;
    }

    const $context = context ? $(context) : $(document);
    const $editors = $context.find('.js-richtext-editor').filter(function () {
        return !$(this).next('.note-editor').length;
    });

    if (!$editors.length) {
        return;
    }

    $editors.each(function () {
        const editor = $(this);
        const height = parseInt(editor.attr('data-height') || '160', 10);

        makeSummernote(editor, Number.isFinite(height) ? height : 160, undefined, {
            toolbar: CONTENT_EDITOR_TOOLBAR,
            buttons: customAlignmentButtons,
            callbacks: {
                onChange: function () {
                    if (this.classList.contains('question-text-input')) {
                        const form = this.closest('.question-inline-form');
                        if (form && form.querySelector('.note-completion-answers')) {
                            renderNoteCompletionAnswerInputs(form);
                        }
                    }

                    this.dispatchEvent(new Event('input', { bubbles: true }));
                },
                // Chặn hành vi mặc định (base64) — thay bằng upload thật lên server.
                // Ảnh dán/kéo-thả trực tiếp cũng đi qua callback này.
                onImageUpload: function (files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadRichTextImageFile(files[i], this);
                    }
                }
            }
        });
    });
    fixSummernoteDropdowns($context);
}



function updateAutosaveStatus(message, isError = false) {
    const statusEl = document.getElementById('autosaveStatus');
    if (!statusEl) {
        return;
    }

    statusEl.textContent = message;
    statusEl.classList.toggle('text-danger', isError);
    statusEl.classList.toggle('text-muted', !isError);
}

function buildAutosavePayload() {
    const typeEl = document.querySelector('select[name="type"]');
    const titleEl = document.querySelector('input[name="title"]');
    const formatEl = document.querySelector('select[name="format"]');
    const descEl = document.querySelector('textarea[name="description"]');
    const difficultyEl = document.querySelector('select[name="difficulty_level"]');
    const bandMinEl = document.querySelector('input[name="target_band_min"]');
    const bandMaxEl = document.querySelector('input[name="target_band_max"]');

    return {
        version: 1,
        path: window.location.pathname,
        savedAt: Date.now(),
        form: {
            type: typeEl ? typeEl.value : '',
            title: titleEl ? titleEl.value : '',
            format: formatEl ? formatEl.value : '',
            description: descEl ? getEditorHtmlValue(descEl) : '',
            difficulty_level: difficultyEl ? difficultyEl.value : '',
            target_band_min: bandMinEl ? bandMinEl.value : '',
            target_band_max: bandMaxEl ? bandMaxEl.value : ''
        },
        testData: testData
    };
}

function getAutosaveSignature(payload) {
    return JSON.stringify({
        form: payload.form,
        testData: payload.testData
    });
}

function saveAutosaveSnapshot(force = false) {
    try {
        const payload = buildAutosavePayload();
        const signature = getAutosaveSignature(payload);

        if (!force && signature === autosaveLastSignature && signature === autosaveLastServerSignature) {
            return;
        }

        if (typeof window.localStorage !== 'undefined') {
            window.localStorage.setItem(AUTOSAVE_STORAGE_KEY, JSON.stringify(payload));
        }

        autosaveLastSignature = signature;
        saveAutosaveSnapshotToServer(payload, signature, force);

        const savedTime = new Date(payload.savedAt).toLocaleTimeString();
        updateAutosaveStatus('Auto Save: đã lưu lúc ' + savedTime + ' (file upload vẫn cần lưu thủ công)');
    } catch (error) {
        updateAutosaveStatus('Auto Save lỗi: không thể lưu bản nháp cục bộ', true);
    }
}

function performServerAutosaveRequest(payload, signature, force) {
    autosaveServerRequestInFlight = true;

    fetch(AUTOSAVE_SERVER_SAVE_URL, {
        method: 'POST',
        credentials: 'same-origin',
        keepalive: !!force,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': AUTOSAVE_CSRF_TOKEN
        },
        body: JSON.stringify({
            test_id: AUTOSAVE_TEST_ID,
            payload: payload
        })
    }).then(function (response) {
        if (!response.ok) {
            throw new Error('Autosave server error');
        }

        return response.json();
    }).then(function (result) {
        if (result && result.success) {
            autosaveLastServerSignature = signature;
        }
    }).catch(function () {
        updateAutosaveStatus('Auto Save: đã lưu tạm trên trình duyệt, đang chờ đồng bộ server...', true);
    }).finally(function () {
        autosaveServerRequestInFlight = false;

        if (autosavePendingServerPayload) {
            const pending = autosavePendingServerPayload;
            autosavePendingServerPayload = null;
            performServerAutosaveRequest(pending.payload, pending.signature, pending.force);
        }
    });
}

function saveAutosaveSnapshotToServer(payload, signature, force = false) {
    if (!force && signature === autosaveLastServerSignature) {
        return;
    }

    if (autosaveServerRequestInFlight) {
        autosavePendingServerPayload = {
            payload: payload,
            signature: signature,
            force: force
        };
        return;
    }

    performServerAutosaveRequest(payload, signature, force);
}

function queueAutosaveSnapshot(force = false) {
    if (autosaveDebounceTimer) {
        window.clearTimeout(autosaveDebounceTimer);
        autosaveDebounceTimer = null;
    }

    if (force) {
        saveAutosaveSnapshot(true);
        return;
    }

    autosaveDebounceTimer = window.setTimeout(function () {
        saveAutosaveSnapshot(false);
        autosaveDebounceTimer = null;
    }, AUTOSAVE_DEBOUNCE_MS);
}

function loadAutosaveSnapshot() {
    if (typeof window.localStorage === 'undefined') {
        return null;
    }

    try {
        const raw = window.localStorage.getItem(AUTOSAVE_STORAGE_KEY);
        if (!raw) {
            return null;
        }

        const payload = JSON.parse(raw);
        if (!payload || !payload.savedAt || !payload.form || !payload.testData) {
            return null;
        }

        if ((Date.now() - payload.savedAt) > AUTOSAVE_MAX_AGE_MS) {
            window.localStorage.removeItem(AUTOSAVE_STORAGE_KEY);
            return null;
        }

        return payload;
    } catch (error) {
        return null;
    }
}

async function fetchServerAutosaveSnapshot() {
    try {
        const query = AUTOSAVE_TEST_ID ? ('?test_id=' + encodeURIComponent(String(AUTOSAVE_TEST_ID))) : '';
        const response = await fetch(AUTOSAVE_SERVER_LOAD_URL + query, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            return null;
        }

        const result = await response.json();
        return (result && result.success && result.payload) ? result.payload : null;
    } catch (error) {
        return null;
    }
}

function pickLatestAutosaveSnapshot(localPayload, serverPayload) {
    if (!localPayload && !serverPayload) {
        return null;
    }

    if (!localPayload) {
        return serverPayload;
    }

    if (!serverPayload) {
        return localPayload;
    }

    const localSavedAt = Number(localPayload.savedAt || 0);
    const serverSavedAt = Number(serverPayload.savedAt || 0);

    return serverSavedAt >= localSavedAt ? serverPayload : localPayload;
}

function normalizeSections(rawSections) {
    const normalized = {
        listening: { parts: [] },
        reading: { parts: [] },
        writing: { parts: [] },
        speaking: { parts: [] },
        grammar: { parts: [] },
        vocabulary: { parts: [] }
    };

    if (!rawSections || typeof rawSections !== 'object') {
        return normalized;
    }

    Object.keys(normalized).forEach((skill) => {
        const source = rawSections[skill];
        if (source && Array.isArray(source.parts)) {
            normalized[skill] = source;
        }
    });

    return normalized;
}

function applyAutosaveSnapshot(payload) {
    const typeEl = document.querySelector('select[name="type"]');
    const titleEl = document.querySelector('input[name="title"]');
    const formatEl = document.querySelector('select[name="format"]');
    const descEl = document.querySelector('textarea[name="description"]');
    const difficultyEl = document.querySelector('select[name="difficulty_level"]');
    const bandMinEl = document.querySelector('input[name="target_band_min"]');
    const bandMaxEl = document.querySelector('input[name="target_band_max"]');

    if (typeEl) typeEl.value = payload.form.type || '';
    if (titleEl) titleEl.value = payload.form.title || '';
    if (formatEl) formatEl.value = payload.form.format || '';
    if (difficultyEl) difficultyEl.value = payload.form.difficulty_level || 'intermediate';
    if (bandMinEl) bandMinEl.value = payload.form.target_band_min || '';
    if (bandMaxEl) bandMaxEl.value = payload.form.target_band_max || '';
    if (descEl) setEditorHtmlValue(descEl, payload.form.description || '');

    currentTestType = payload.form.type || currentTestType;
    testData = {
        sections: normalizeSections(payload.testData.sections)
    };

    if (currentTestType) {
        updateTestRequirements();
        renderExistingTestData();
        updateCompletenessStatus();
    }

    autosaveLastSignature = getAutosaveSignature(buildAutosavePayload());
    const savedAt = new Date(payload.savedAt).toLocaleString();
    updateAutosaveStatus('Đã khôi phục bản nháp cục bộ lúc ' + savedAt + ' (không bao gồm file upload)');
}

async function maybeRestoreAutosaveSnapshot() {
    if (!AUTOSAVE_RESTORE_ENABLED) {
        updateAutosaveStatus('Auto Save: đang bật cho phiên hiện tại (không khôi phục bản nháp cũ)');
        return;
    }

    const params = new URLSearchParams(window.location.search || '');
    const isPreviewReturn = params.get('restore_state') === '1';
    const isEditingExistingTest = !!AUTOSAVE_TEST_ID;

    // Avoid overriding persisted DB data on normal edit page loads.
    // Only allow snapshot restore when creating a new test or explicitly returning from preview.
    if (isEditingExistingTest && !isPreviewReturn) {
        updateAutosaveStatus('Auto Save: dùng dữ liệu máy chủ cho bản chỉnh sửa hiện tại');
        return;
    }

    const localPayload = loadAutosaveSnapshot();
    let serverPayload = autosaveServerSeed;

    const fetchedServerPayload = await fetchServerAutosaveSnapshot();
    if (fetchedServerPayload) {
        serverPayload = fetchedServerPayload;
    }

    const payload = pickLatestAutosaveSnapshot(localPayload, serverPayload);
    if (!payload) {
        return;
    }

    if (typeof window.localStorage !== 'undefined') {
        try {
            window.localStorage.setItem(AUTOSAVE_STORAGE_KEY, JSON.stringify(payload));
        } catch (error) {
            // Ignore local storage write errors and continue with in-memory restore.
        }
    }

    applyAutosaveSnapshot(payload);
}

function startAutosaveLoop() {
    if (autosaveTimer) {
        window.clearInterval(autosaveTimer);
    }

    autosaveTimer = window.setInterval(function () {
        saveAutosaveSnapshot(false);
    }, AUTOSAVE_INTERVAL_MS);
}

document.addEventListener('DOMContentLoaded', function() {
    setupFormValidation();
    setupFilePreviews();
    initAnswerHelpEditors(document);
    initContentEditors(document);
    
    if (currentTestType) {
        const typeSelect = document.getElementById('testTypeSelect');
        if (typeSelect) {
            typeSelect.value = currentTestType;
        }
        updateTestRequirements();
        renderExistingTestData();
        updateCompletenessStatus();
    }
    
    // Restore from localStorage AFTER testData is initialized (after exiting preview)
    restoreFormStateFromLocalStorage();

    maybeRestoreAutosaveSnapshot().finally(function () {
        startAutosaveLoop();
    });

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            queueAutosaveSnapshot(true);
        }
    });

    window.addEventListener('beforeunload', function () {
        queueAutosaveSnapshot(true);
    });

    document.addEventListener('input', function(event) {
        const target = event.target;
        if (!target || !target.classList) {
            return;
        }

        queueAutosaveSnapshot(false);

        if (target.classList.contains('question-text-input')) {
            const form = target.closest('.question-inline-form');
            if (form && form.querySelector('.note-completion-answers')) {
                renderNoteCompletionAnswerInputs(form);
            }

            if (form && form.querySelector('.completion-answers')) {
                renderCompletionAnswerInputs(form);
            }
        }

        if (target.classList.contains('tc-cell-text') || target.classList.contains('tc-cell-answer') || target.classList.contains('tc-col-title')) {
            const form = target.closest('.question-inline-form');
            if (!form) {
                return;
            }

            if (target.classList.contains('tc-cell-text')) {
                const td = target.closest('td');
                syncTableCellAnswerInputs(td);
            }

            updateTableCompletionState(form);
        }
    });

    document.addEventListener('change', function(event) {
        const target = event.target;
        if (!target || !target.classList) {
            return;
        }

        queueAutosaveSnapshot(false);

        if (!target.classList.contains('question-type-select')) {
            return;
        }

        const form = target.closest('.question-inline-form');
        if (!form || !form.querySelector('.note-completion-answers')) {
            return;
        }

        renderNoteCompletionAnswerInputs(form);
    });

    const autosaveObservedRoot = document.getElementById('sectionsContainer');
    if (autosaveObservedRoot && typeof MutationObserver !== 'undefined') {
        const autosaveMutationObserver = new MutationObserver(function () {
            queueAutosaveSnapshot(false);
        });

        autosaveMutationObserver.observe(autosaveObservedRoot, {
            childList: true,
            subtree: true
        });
    }
});

function setupFilePreviews() {
    function renderFilePreview(input, preview) {
        if (!preview) {
            return;
        }

        if (input.files && input.files[0]) {
            preview.innerHTML = `${escapeHtml(input.files[0].name)}
                <button type="button" class="remove-file-btn" title="Remove file" onclick="removeSelectedFile(this)">
                    <i class="fas fa-times"></i>
                </button>`;
            preview.style.display = 'inline-flex';
        } else {
            preview.innerHTML = '';
            preview.style.display = 'none';
        }
    }

    document.addEventListener('change', function(event) {
        const input = event.target;
        if (!input || input.type !== 'file') {
            return;
        }

        let preview = null;
        if (input.classList.contains('section-audio-file')) {
            preview = input.closest('.section-media-block')?.querySelector('.section-audio-preview');
        } else if (input.classList.contains('group-audio-file')) {
            preview = input.closest('.col-md-4')?.querySelector('.group-audio-preview');
        } else if (input.classList.contains('group-image-file')) {
            preview = input.closest('.col-md-4')?.querySelector('.group-image-preview');
        } else if (input.classList.contains('group-video-file')) {
            preview = input.closest('.col-md-4')?.querySelector('.group-video-preview');
        }

        renderFilePreview(input, preview);
    });
}

function removeSelectedFile(button) {
    const preview = button.closest('.file-preview');
    if (!preview) {
        return;
    }

    const block = preview.closest('.col-md-4, .section-media-block');
    if (!block) {
        return;
    }

    let input = null;
    if (preview.classList.contains('section-audio-preview')) {
        input = block.querySelector('.section-audio-file:not(.preserved-upload-input)') || block.querySelector('.section-audio-file');
    } else if (preview.classList.contains('group-audio-preview')) {
        input = block.querySelector('.group-audio-file:not(.preserved-upload-input)') || block.querySelector('.group-audio-file');
    } else if (preview.classList.contains('group-image-preview')) {
        input = block.querySelector('.group-image-file:not(.preserved-upload-input)') || block.querySelector('.group-image-file');
    } else if (preview.classList.contains('group-video-preview')) {
        input = block.querySelector('.group-video-file:not(.preserved-upload-input)') || block.querySelector('.group-video-file');
    }

    if (input) {
        input.value = '';
    }

    preview.innerHTML = '';
    preview.style.display = 'none';
}

function updateTestRequirements() {
    currentTestType = document.getElementById('testTypeSelect').value;

    if (!currentTestType) {
        return;
    }

    const isMock = currentTestType === 'mock';

    Object.keys(SECTIONS_CONFIG).forEach(skill => {
        const reqElement = document.querySelector(`.req-${skill}`);
        if (reqElement) {
            if (isMock) {
                if (SECTIONS_CONFIG[skill].mockParts > 0) {
                    reqElement.textContent = `(${SECTIONS_CONFIG[skill].mockParts} parts, ${SECTIONS_CONFIG[skill].mockQuestions} questions)`;
                } else {
                    reqElement.textContent = '(optional)';
                }
            } else {
                reqElement.textContent = '(at least 1 part)';
            }
        }
    });

    if (!document.querySelector('.section-container')) {
        initializeSections();
    }

    updateCompletenessStatus();
}

function initializeSections() {
    const container = document.getElementById('sectionsContainer');
    container.innerHTML = '';

    Object.entries(SECTIONS_CONFIG).forEach(([skill, config]) => {
        const template = document.getElementById('sectionTemplate');
        const section = template.content.cloneNode(true);

        const sectionContainer = section.querySelector('.section-container');
        sectionContainer.setAttribute('data-skill', skill);

        section.querySelector('.skill-icon').textContent = config.icon;
        section.querySelector('.skill-icon').classList.add(skill);
        section.querySelector('.section-header h4').textContent = config.title + ' Section';

        const sectionMediaBlock = section.querySelector('.section-media-block');
        if (sectionMediaBlock) {
            sectionMediaBlock.classList.toggle('hidden', skill !== 'listening');
        }

        container.appendChild(section);
        initAnswerHelpEditors(container);
        initContentEditors(container);
    });
}

function renderExistingTestData() {
    const container = document.getElementById('sectionsContainer');
    if (!container || !testData || !testData.sections) {
        return;
    }

    Object.entries(testData.sections).forEach(([skill, sectionData]) => {
        const sectionEl = container.querySelector(`.section-container[data-skill="${skill}"]`);
        if (!sectionEl || !sectionData || !Array.isArray(sectionData.parts)) {
            return;
        }

        renderSectionParts(sectionEl, skill);
    });
}

function renderSectionParts(sectionEl, skill) {
    if (!sectionEl || !skill || !testData.sections[skill]) {
        return;
    }

    const partsList = sectionEl.querySelector('.parts-list');
    if (!partsList) {
        return;
    }

    partsList.innerHTML = '';

    const sectionData = testData.sections[skill];
    (sectionData.parts || []).forEach((part) => {
        const audioFile = part?.files?.audio || part.audio_file || null;
        const imageFile = part?.files?.image || part.image_file || null;
        const videoFile = part?.files?.video || part.video_file || null;
        const partItem = displayPart(sectionEl, part, audioFile, imageFile, videoFile);

        (part.groups || []).forEach((group) => {
            displayQuestionGroup(partItem, part, group);
        });

        updatePartStats(partItem, part);
    });

    updateSectionStats(sectionEl);
    renumberSectionQuestions(sectionEl);
}

function resetPartEditorState(form) {
    if (!form) {
        return;
    }

    form.removeAttribute('data-edit-mode');
    form.removeAttribute('data-edit-part-id');

    const heading = form.querySelector('h5');
    if (heading) {
        heading.innerHTML = '<i class="fas fa-plus-circle mr-8"></i>Add New Part';
    }

    const submitBtn = form.querySelector('button[onclick="addPart(this)"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-plus mr-5"></i>Create Part';
    }
}

function resetGroupEditorState(form) {
    if (!form) {
        return;
    }

    form.removeAttribute('data-edit-mode');
    form.removeAttribute('data-edit-group-id');

    const submitBtn = form.querySelector('button[onclick="addQuestionGroup(this)"]');
    if (submitBtn) {
        if (form.querySelector('.group-type-select') && form.querySelector('select.group-type-select')) {
            submitBtn.innerHTML = '<i class="fas fa-plus mr-5"></i>Create Group & Add Questions';
        } else {
            submitBtn.innerHTML = '<i class="fas fa-plus mr-5"></i>Create Group';
        }
    }
}

function toggleAddPartForm(button) {
    const form = button.closest('.section-container').querySelector('.add-part-form');
    if (form.classList.contains('hidden')) {
        resetPartEditorState(form);
    }
    form.classList.toggle('hidden');
}

function toggleAddGroupForm(button) {
    const form = button.closest('.part-item').querySelector('.group-inline-form');
    if (form.classList.contains('hidden')) {
        resetGroupEditorState(form);
    }
    form.classList.toggle('hidden');
}

function addPart(button) {
    const section = button.closest('.section-container');
    const skill = section.getAttribute('data-skill');
    const form = section.querySelector('.add-part-form');
    const isEditMode = form.getAttribute('data-edit-mode') === 'true';
    const editPartId = form.getAttribute('data-edit-part-id');

    const title = form.querySelector('.part-title-input').value.trim();
    const instructions = getContentEditorValue(form.querySelector('.part-instructions-input'));
    const passage = getContentEditorValue(form.querySelector('.part-passage'));

    const audioInput = form.querySelector('.group-audio-file');
    const audioFile = audioInput && audioInput.files.length ? audioInput.files[0] : null;
    const imageFile = form.querySelector('.group-image-file').files[0];
    const videoFile = form.querySelector('.group-video-file').files[0];

    if (!title) {
        alert('Please fill in Part Title');
        return;
    }

    if (isEditMode) {
        const part = testData.sections[skill].parts.find(p => String(p.id) === String(editPartId));
        if (!part) {
            alert('Part not found. Please try again.');
            return;
        }

        const uploadId = part.upload_id || `part_${Date.now()}_${++uploadSequence}`;
        const fileInputNames = preserveSelectedFiles(form, uploadId);

        part.upload_id = uploadId;
        part.title = title;
        part.instructions = instructions || null;
        part.passage = passage || null;
        part.file_input_names = fileInputNames;
        part.files = {
            audio: audioFile ? audioFile.name : (part.files?.audio || null),
            image: imageFile ? imageFile.name : (part.files?.image || null),
            video: videoFile ? videoFile.name : (part.files?.video || null)
        };
    } else {
        const uploadId = `part_${Date.now()}_${++uploadSequence}`;
        const fileInputNames = preserveSelectedFiles(form, uploadId);

        const part = {
            id: Date.now(),
            upload_id: uploadId,
            title,
            instructions: instructions || null,
            passage: passage || null,
            groups: [],
            file_input_names: fileInputNames,
            files: {
                audio: audioFile ? audioFile.name : null,
                image: imageFile ? imageFile.name : null,
                video: videoFile ? videoFile.name : null
            }
        };

        testData.sections[skill].parts.push(part);
    }

    renderSectionParts(section, skill);

    form.querySelector('.part-title-input').value = '';
    setContentEditorValue(form.querySelector('.part-instructions-input'), '');
    setContentEditorValue(form.querySelector('.part-passage'), '');
    form.querySelectorAll('input[type="file"]:not(.preserved-upload-input)').forEach(input => input.value = '');
    form.querySelectorAll('.file-preview').forEach(preview => preview.style.display = 'none');
    resetPartEditorState(form);

    form.classList.add('hidden');
    updateSectionStats(section);
    updateCompletenessStatus();
}

function addQuestionGroup(button) {
    const partItem = button.closest('.part-item');
    const section = button.closest('.section-container');
    const skill = section.getAttribute('data-skill');
    const partId = partItem.getAttribute('data-part-id');
    const part = testData.sections[skill].parts.find(p => String(p.id) === String(partId));

    if (!part) {
        alert('Part not found. Please try again.');
        return;
    }

    const form = partItem.querySelector('.group-inline-form');
    const isEditMode = form.getAttribute('data-edit-mode') === 'true';
    const editGroupId = form.getAttribute('data-edit-group-id');
    const titleEl = form ? form.querySelector('.group-title-input') : null;
    const title = titleEl ? titleEl.value.trim() : '';
    const qTypeEl = form ? form.querySelector('.group-type-select') : null;
    const questionType = qTypeEl ? (qTypeEl.value || 'short_answer') : 'short_answer';
    const maxWordsEl = form ? form.querySelector('.group-max-words') : null;
    const maxWords = maxWordsEl ? maxWordsEl.value : '';
    const targetBandEl = form ? form.querySelector('.group-target-band') : null;
    const targetBand = targetBandEl ? targetBandEl.value : '';
    const passageEl = form ? form.querySelector('.group-passage') : null;
    const passage = passageEl ? getContentEditorValue(passageEl) : '';
    const groupTaskImageUrlEl = form.querySelector('.group-task-image-url');
    const groupTaskImageUrl = groupTaskImageUrlEl ? (groupTaskImageUrlEl.value || '').trim() : null;

    if (!title || !questionType) {
        alert('Please fill in Group Title and Question Type');
        return;
    }

    if (isEditMode) {
        const group = part.groups.find(g => String(g.id) === String(editGroupId));
        if (!group) {
            alert('Question group not found. Please try again.');
            return;
        }

        const uploadId = group.upload_id || `group_${Date.now()}_${++uploadSequence}`;
        const fileInputNames = preserveSelectedFiles(form, uploadId);

        group.upload_id = uploadId;
        group.title = title;
        group.question_type = questionType;
        group.max_words = maxWords || null;
        group.target_band = targetBand || null;
        group.passage = passage || null;
        group.task_image = groupTaskImageUrl || null;
        group.file_input_names = fileInputNames;
        group.files = group.files || {};
    } else {
        const uploadId = `group_${Date.now()}_${++uploadSequence}`;
        const fileInputNames = preserveSelectedFiles(form, uploadId);

        const group = {
            id: Date.now(),
            upload_id: uploadId,
            title,
            question_type: questionType,
            max_words: maxWords || null,
            target_band: targetBand || null,
            passage: passage || null,
            task_image: groupTaskImageUrl || null,
            questions: [],
            file_input_names: fileInputNames,
            files: {}
        };

        part.groups.push(group);
    }

    renderSectionParts(section, skill);

    form.querySelector('.group-title-input').value = '';
    const typeSelect = form.querySelector('.group-type-select');
    if (typeSelect && typeSelect.tagName.toLowerCase() === 'select') {
        typeSelect.value = 'multiple_choice_single';
    }
    const maxWordsInput = form.querySelector('.group-max-words');
    if (maxWordsInput) {
        maxWordsInput.value = '';
    }

    const targetBandInput = form.querySelector('.group-target-band');
    if (targetBandInput) {
        targetBandInput.value = '';
    }
    setContentEditorValue(form.querySelector('.group-passage'), '');
    const gImg = form.querySelector('.group-task-image-url'); if (gImg) gImg.value = '';
    form.querySelectorAll('input[type="file"]:not(.preserved-upload-input)').forEach(input => input.value = '');
    form.querySelectorAll('.file-preview').forEach(preview => preview.style.display = 'none');
    resetGroupEditorState(form);

    form.classList.add('hidden');
    updatePartStats(partItem, part);
    updateSectionStats(section);
    updateCompletenessStatus();
}

function preserveSelectedFiles(form, uploadId) {
    const hiddenContainer = document.getElementById('uploadedFilesContainer');
    const mapping = {};

    const fileConfigs = [
        { selector: '.group-audio-file', type: 'audio', accept: 'audio/*' },
        { selector: '.group-image-file', type: 'image', accept: 'image/*' },
        { selector: '.group-video-file', type: 'video', accept: 'video/*' }
    ];

    fileConfigs.forEach(config => {
        const input = form.querySelector(config.selector);
        if (!input) {
            return;
        }

        const uploadArea = input.closest('.file-upload-area');
        const inputClass = input.className;

        if (input.files && input.files[0]) {
            input.name = `group_media[${uploadId}][${config.type}]`;
            input.classList.add('preserved-upload-input');
            hiddenContainer.appendChild(input);
            mapping[config.type] = `group_media.${uploadId}.${config.type}`;

            if (uploadArea) {
                const replacement = document.createElement('input');
                replacement.type = 'file';
                replacement.className = inputClass;
                replacement.accept = config.accept;
                uploadArea.appendChild(replacement);
            }
        }
    });

    return mapping;
}

// Preserve section-level audio inputs before form submit so the server receives them
function preserveSectionAudioFiles() {
    const hiddenContainer = document.getElementById('uploadedFilesContainer');
    const sectionInputs = document.querySelectorAll('.section-audio-file');
    sectionInputs.forEach(input => {
        if (input.files && input.files[0] && !input.classList.contains('preserved-upload-input')) {
            input.name = 'section_media[listening][audio]';
            input.classList.add('preserved-upload-input');
            hiddenContainer.appendChild(input);
        }
    });
}

function displayPart(section, part, audioFile, imageFile, videoFile) {
    const partsList = section.querySelector('.parts-list');

    const partDiv = document.createElement('div');
    partDiv.className = 'part-item';
    partDiv.setAttribute('data-part-id', part.id);

    let mediaHTML = '';
    if (audioFile || imageFile || videoFile) {
        mediaHTML = '<div class="part-media">';
        if (audioFile) mediaHTML += '<span class="media-badge"><i class="fas fa-volume-up"></i> Audio</span>';
        if (imageFile) mediaHTML += '<span class="media-badge"><i class="fas fa-image"></i> Image</span>';
        if (videoFile) mediaHTML += '<span class="media-badge"><i class="fas fa-video"></i> Video</span>';
        mediaHTML += '</div>';
    }

    const hasPartRichContent = Boolean(part.instructions || part.passage);
    const partRichContentHTML = hasPartRichContent
        ? `<div class="part-rich-content collapsible-richtext collapsed mb-8" style="font-size: 14px;">
                ${part.instructions ? `<div class="text-muted mb-8">${part.instructions}</div>` : ''}
                ${part.passage ? `<div class="text-muted">${part.passage}</div>` : ''}
           </div>
           <div class="content-toggle-wrap">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleRichContent(this)" title="Expand content">
                    <i class="fas fa-chevron-down"></i>
                </button>
           </div>`
        : '';

    partDiv.innerHTML = `
        <div class="title-expand-wrap">
            <div class="part-title collapsible-title">${escapeHtml(part.title)}</div>
        </div>
        <div class="part-meta">
            <span><i class="fas fa-align-left"></i> Part</span>
            <span class="group-total"><i class="fas fa-layer-group"></i> 0 groups</span>
            <span class="question-total"><i class="fas fa-list-ol"></i> 0 questions</span>
        </div>
        ${mediaHTML}
        ${partRichContentHTML}
        <div class="groups-list"></div>
        <div class="part-actions">
            <button type="button" class="btn btn-sm btn-outline-secondary title-toggle-btn" onclick="toggleCollapsibleTitle(this)" title="Expand title">
                <i class="fas fa-chevron-down"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editPartTitle(this)" title="Edit part title">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="toggleAddGroupForm(this)">
                <i class="fas fa-plus"></i> Group
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePart(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    partsList.appendChild(partDiv);

    // Insert group inline form variant based on section skill (writing/speaking use simplified essay form)
    try {
        const sectionEl = section; // passed into displayPart as 'section'
        const skill = sectionEl ? sectionEl.getAttribute('data-skill') : '';
        let groupInlineFormHTML = '';
        if (skill === 'writing' || skill === 'speaking') {
            groupInlineFormHTML = `
            <div class="group-inline-form hidden mt-12 p-12" style="background:#fff;border:1px solid #dbeafe;border-radius:8px;">
                <div class="form-row">
                    <div class="form-group">
                        <label class="input-label">Question Group Title / Prompt</label>
                        <textarea class="form-control group-title-input" rows="2" placeholder="Enter group title or prompt"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="input-label">Question Type</label>
                        <div class="text-muted">Essay (Writing / Speaking)</div>
                        <input type="hidden" class="group-type-select" value="essay">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="input-label">Optional Task Image URL</label>
                        <input type="text" class="form-control group-task-image-url" placeholder="e.g. https://... or leave blank">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="input-label">Group Instructions / Passage</label>
                        <textarea class="form-control group-passage js-richtext-editor" data-height="160" rows="3" placeholder="Optional instructions or passage for this task..."></textarea>
                    </div>
                </div>
                <div class="mt-12">
                    <button type="button" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="addQuestionGroup(this)">
                        <i class="fas fa-plus mr-5"></i>Create Group
                    </button>
                    <button type="button" class="btn btn-sm rounded-12 d-none d-md-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="toggleAddGroupForm(this)">
                        <i class="fas fa-times mr-5"></i>Cancel
                    </button>
                </div>
            </div>`;
        } else {
            groupInlineFormHTML = `
            <div class="group-inline-form hidden mt-12 p-12" style="background:#fff;border:1px solid #dbeafe;border-radius:8px;">
                <div class="form-row">
                    <div class="form-group">
                        <label class="input-label">Question Group Title / Prompt</label>
                        <textarea class="form-control group-title-input" rows="2" placeholder="Enter group title or prompt"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="input-label">Question Type</label>
                        <select class="form-control group-type-select">
                            <optgroup label="Multiple Choice">
                                <option value="multiple_choice_single">Single Answer</option>
                                <option value="multiple_choice_multiple">Multiple Answers</option>
                            </optgroup>
                            <optgroup label="True/False/Not Given">
                                <option value="true_false_not_given">True / False / Not Given</option>
                                <option value="yes_no_not_given">Yes / No / Not Given</option>
                            </optgroup>
                            <optgroup label="Matching">
                                <option value="matching_headings">Matching Headings</option>
                                <option value="matching_information">Matching Information</option>
                                <option value="matching_features">Matching Features</option>
                                <option value="matching_sentence_endings">Matching Sentence Endings</option>
                            </optgroup>
                            <optgroup label="Completion">
                                <option value="sentence_completion">Sentence Completion</option>
                                <option value="summary_completion">Summary Completion</option>
                                <option value="note_completion">Note Completion</option>
                                <option value="table_completion">Table Completion</option>
                                <option value="diagram_labeling">Diagram Labeling</option>
                            </optgroup>
                            <optgroup label="Drag &amp; Drop">
                                <option value="drag_drop_disappear">Drag &amp; Drop (Remove from List)</option>
                                <option value="drag_drop_reuse">Drag &amp; Drop (Keep in List)</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="short_answer">Short Answer</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="input-label">Max Words</label>
                        <input type="number" class="form-control group-max-words" min="1" placeholder="e.g. 3">
                    </div>
                    <div class="form-group">
                        <label class="input-label">Target Band</label>
                        <input type="number" class="form-control group-target-band" min="4" max="9" step="0.5" placeholder="e.g. 6.5">
                    </div>
                </div>
                <div class="form-row full">
                    <div class="form-group">
                        <label class="input-label">Passage / Instructions</label>
                        <textarea class="form-control group-passage js-richtext-editor" data-height="160" rows="3" placeholder="Enter reading passage, instructions, or transcript..."></textarea>
                    </div>
                </div>
                <div class="mt-8">
                    <button type="button" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="addQuestionGroup(this)">
                        <i class="fas fa-plus mr-5"></i>Create Group & Add Questions
                    </button>
                    <button type="button" class="btn btn-sm rounded-12 d-none d-md-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="toggleAddGroupForm(this)">
                        <i class="fas fa-times mr-5"></i>Cancel
                    </button>
                </div>
            </div>`;
        }

        const groupsListEl = partDiv.querySelector('.groups-list');
        if (groupsListEl) {
            groupsListEl.insertAdjacentHTML('afterend', groupInlineFormHTML);
        }
        initContentEditors(partDiv);
    } catch (e) {
        console.error('Error inserting group inline form', e);
    }

    updatePartStats(partDiv, part);

    return partDiv;
}

function displayQuestionGroup(partItem, part, group) {
    const groupsList = partItem.querySelector('.groups-list');

    const groupDiv = document.createElement('div');
    groupDiv.className = 'group-item';
    groupDiv.setAttribute('data-group-id', group.id);
    groupDiv.setAttribute('data-group-task-image', group.task_image || '');

    const hasGroupRichContent = Boolean(group.passage);
    const groupRichContentHTML = hasGroupRichContent
        ? `<div class="group-rich-content collapsible-richtext collapsed mb-8 text-muted" style="font-size: 14px;">${group.passage}</div>
           <div class="content-toggle-wrap">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleRichContent(this)" title="Expand content">
                    <i class="fas fa-chevron-down"></i>
                </button>
           </div>`
        : '';

    groupDiv.innerHTML = `
        <div class="title-expand-wrap">
            <div class="group-title collapsible-title">${escapeHtml(group.title)}</div>
        </div>
        <div class="group-meta">
            <span><i class="fas fa-tags"></i> ${escapeHtml(getQuestionTypeLabel(group.question_type))}</span>
            <span class="question-total"><i class="fas fa-list-ol"></i> 0 questions</span>
            ${group.max_words ? `<span><i class="fas fa-font"></i> ${escapeHtml(String(group.max_words))} word(s) / number(s) max</span>` : ''}
            ${group.target_band ? `<span><i class="fas fa-bullseye"></i> Band ${escapeHtml(String(group.target_band))}</span>` : ''}
        </div>
        ${groupRichContentHTML}
        <div class="questions-list" style="display:none;"></div>
        <div class="question-inline-form hidden mt-12 p-12" style="background:#fff;border:1px solid #dbeafe;border-radius:8px;">
            ${getQuestionFormHTML(group.question_type)}
            <div class="mt-8">
                <button type="button" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="saveQuestionToGroup(this)">
                    <i class="fas fa-check mr-5"></i>Save Question
                </button>
                <button type="button" class="btn btn-sm rounded-12 d-none d-md-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="toggleQuestionForm(this)">
                    <i class="fas fa-times mr-5"></i>Cancel
                </button>
            </div>
        </div>
        <div class="group-actions">
            <button type="button" class="btn btn-sm btn-outline-secondary title-toggle-btn" onclick="toggleCollapsibleTitle(this)" title="Expand title">
                <i class="fas fa-chevron-down"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editGroupTitle(this)" title="Edit question group title">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="toggleQuestionForm(this)" title="Add new question">
                <i class="fas fa-plus mr-2"></i> Question
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeGroup(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    groupsList.appendChild(groupDiv);

    // Render question cards first so they are visible even if rich editor init fails.
    renderQuestionsList(groupDiv, group);

    try {
        initAnswerHelpEditors(groupDiv);
        initContentEditors(groupDiv);
    } catch (error) {
        console.warn('Editor init failed for group card, keeping question list visible:', error);
    }
    updatePartStats(partItem, part);

    return groupDiv;
}

function toggleQuestionForm(button) {
    const groupItem = button.closest('.group-item');
    const form = groupItem.querySelector('.question-inline-form');
    if (form) {
        const wasHidden = form.classList.contains('hidden');
        form.classList.toggle('hidden');

        // Reset form when opening
        if (wasHidden) {
            resetQuestionForm(form);
            initAnswerHelpEditors(form);
            initContentEditors(form);
            initializeMatchingBuilder(form);
            if (form.querySelector('.note-completion-answers')) {
                renderNoteCompletionAnswerInputs(form);
                bindNoteCompletionLivePreview(form);
            }
            // If group has a default task image (writing/speaking), prefill question image input
            try {
                const groupTaskImage = groupItem.getAttribute('data-group-task-image') || '';
                const qImgInput = form.querySelector('.question-task-image-url');
                if (qImgInput && groupTaskImage) {
                    qImgInput.value = groupTaskImage;
                }
            } catch (e) {
                // ignore
            }
        }
    }
}

function toggleCollapsibleTitle(button) {
    const container = button.closest('.part-item, .group-item, .mb-12, .question-card-header');
    if (!container) {
        return;
    }

    const titleEl = container.querySelector('.title-expand-wrap .collapsible-title, .question-title-wrap .collapsible-title');
    if (!titleEl) {
        return;
    }

    const expanded = titleEl.classList.toggle('expanded');
    const icon = button.querySelector('i');
    if (icon) {
        icon.className = expanded ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
    }
    button.setAttribute('title', expanded ? 'Collapse title' : 'Expand title');
}

function toggleRichContent(button) {
    const item = button.closest('.part-item, .group-item');
    if (!item) {
        return;
    }

    const content = item.querySelector('.part-rich-content, .group-rich-content');
    if (!content) {
        return;
    }

    const isCollapsed = content.classList.toggle('collapsed');
    const icon = button.querySelector('i');
    if (icon) {
        icon.className = isCollapsed ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
    }
    button.setAttribute('title', isCollapsed ? 'Expand content' : 'Collapse content');
}

function toggleQuestionCollapse(button, questionIndex) {
    const groupItem = button.closest('.group-item');
    const section = button.closest('.section-container');
    const partItem = button.closest('.part-item');
    if (!groupItem || !section || !partItem) {
        return;
    }

    const skill = section.getAttribute('data-skill');
    const groupId = groupItem.getAttribute('data-group-id');
    const partId = partItem.getAttribute('data-part-id');
    const part = (testData.sections[skill]?.parts || []).find(p => String(p.id) === String(partId));
    const group = part ? (part.groups || []).find(g => String(g.id) === String(groupId)) : null;

    if (!group || !group.questions || !group.questions[questionIndex]) {
        return;
    }

    const currentCollapsed = group.questions[questionIndex].collapsed !== false;
    group.questions[questionIndex].collapsed = !currentCollapsed;

    const updatedQuestion = group.questions[questionIndex];
    if (updatedQuestion) {
        updatedQuestion.titleExpanded = updatedQuestion.collapsed === false;
    }

    renumberSectionQuestions(section);
}

function editPartTitle(button) {
    const partItem = button.closest('.part-item');
    const section = button.closest('.section-container');
    if (!partItem || !section) {
        return;
    }

    const skill = section.getAttribute('data-skill');
    const partId = partItem.getAttribute('data-part-id');
    const part = (testData.sections[skill]?.parts || []).find(item => String(item.id) === String(partId));

    if (!part) {
        alert('Part not found. Please try again.');
        return;
    }

    const form = section.querySelector('.add-part-form');
    if (!form) {
        return;
    }

    form.classList.remove('hidden');
    form.setAttribute('data-edit-mode', 'true');
    form.setAttribute('data-edit-part-id', String(part.id));

    const heading = form.querySelector('h5');
    if (heading) {
        heading.innerHTML = '<i class="fas fa-edit mr-8"></i>Edit Part';
    }

    const submitBtn = form.querySelector('button[onclick="addPart(this)"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-save mr-5"></i>Update Part';
    }

    const titleInput = form.querySelector('.part-title-input');
    if (titleInput) {
        titleInput.value = part.title || '';
        titleInput.focus();
    }

    setContentEditorValue(form.querySelector('.part-instructions-input'), part.instructions || '');
    setContentEditorValue(form.querySelector('.part-passage'), part.passage || '');

    const filePreviews = form.querySelectorAll('.file-preview');
    if (filePreviews.length >= 3) {
        const previewValues = [part.files?.audio, part.files?.image, part.files?.video];
        filePreviews.forEach((preview, index) => {
            const value = previewValues[index] || '';
            if (value) {
                preview.textContent = value;
                preview.style.display = 'inline-flex';
            } else {
                preview.textContent = '';
                preview.style.display = 'none';
            }
        });
    }
}

function editGroupTitle(button) {
    const groupItem = button.closest('.group-item');
    const partItem = button.closest('.part-item');
    const section = button.closest('.section-container');
    if (!groupItem || !partItem || !section) {
        return;
    }

    const skill = section.getAttribute('data-skill');
    const partId = partItem.getAttribute('data-part-id');
    const groupId = groupItem.getAttribute('data-group-id');
    const part = (testData.sections[skill]?.parts || []).find(item => String(item.id) === String(partId));
    const group = part ? (part.groups || []).find(item => String(item.id) === String(groupId)) : null;

    if (!group) {
        alert('Question group not found. Please try again.');
        return;
    }

    const form = partItem.querySelector('.group-inline-form');
    if (!form) {
        return;
    }

    form.classList.remove('hidden');
    form.setAttribute('data-edit-mode', 'true');
    form.setAttribute('data-edit-group-id', String(group.id));

    const submitBtn = form.querySelector('button[onclick="addQuestionGroup(this)"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-save mr-5"></i>Update Group';
    }

    const titleInput = form.querySelector('.group-title-input');
    if (titleInput) {
        titleInput.value = group.title || '';
        titleInput.focus();
    }

    const typeInput = form.querySelector('.group-type-select');
    if (typeInput) {
        typeInput.value = group.question_type || typeInput.value || 'short_answer';
    }

    const maxWordsInput = form.querySelector('.group-max-words');
    if (maxWordsInput) {
        maxWordsInput.value = group.max_words || '';
    }

    const targetBandInput = form.querySelector('.group-target-band');
    if (targetBandInput) {
        targetBandInput.value = group.target_band || '';
    }

    setContentEditorValue(form.querySelector('.group-passage'), group.passage || '');

    const groupImageInput = form.querySelector('.group-task-image-url');
    if (groupImageInput) {
        groupImageInput.value = group.task_image || '';
    }
}

function resetQuestionForm(form) {
    const textInput = form.querySelector('.question-text-input');
    if (textInput) setContentEditorValue(textInput, '');

    const titleInput = form.querySelector('.question-title-input');
    if (titleInput) titleInput.value = '';

    const explanationInput = form.querySelector('.question-explanation-input');
    if (explanationInput) setAnswerHelpValue(explanationInput, '');

    const answerInput = form.querySelector('.question-answer-input');
    if (answerInput) answerInput.value = '';

    const qTaskImg = form.querySelector('.question-task-image-url');
    if (qTaskImg) qTaskImg.value = '';

    const answerSelect = form.querySelector('.question-answer-select');
    if (answerSelect) answerSelect.selectedIndex = 0;

    const pointsInput = form.querySelector('.question-points-input');
    if (pointsInput) pointsInput.value = '0.225';

    const noteAnswerWrap = form.querySelector('.note-completion-answers');
    if (noteAnswerWrap) noteAnswerWrap.innerHTML = '';

    const completionAnswerWrap = form.querySelector('.completion-answers');
    if (completionAnswerWrap) completionAnswerWrap.innerHTML = '';

    const noteSummary = form.querySelector('.note-completion-summary');
    if (noteSummary) noteSummary.innerHTML = 'Type the note text with <code>___</code> for each blank.';

    const completionSummary = form.querySelector('.completion-summary');
    if (completionSummary) completionSummary.innerHTML = 'Type the sentence/summary text with <code>___</code> for each blank.';

    const tcWrap = form.querySelector('.tc-builder-wrap');
    if (tcWrap) tcWrap.classList.add('hidden');

    const matchingWrap = form.querySelector('.matching-matrix-wrap');
    if (matchingWrap) matchingWrap.innerHTML = '';

    const matchingStatementsCount = form.querySelector('.matching-statements-count');
    if (matchingStatementsCount) matchingStatementsCount.value = '4';

    const matchingColumnsCount = form.querySelector('.matching-columns-count');
    if (matchingColumnsCount) matchingColumnsCount.value = '5';

    form.querySelectorAll('.tc-col-title, .tc-cell-text, .tc-cell-answer').forEach(i => i.value = '');
    form.querySelectorAll('.mc-option-row input[type="text"]').forEach(i => i.value = '');
    form.querySelectorAll('.mc-option-row input[type="radio"], .mc-option-row input[type="checkbox"]').forEach(i => i.checked = false);

    // Reset to default 2 options for multiple choice
    const optionsList = form.querySelector('.mc-options-list');
    if (optionsList) {
        const inputType = optionsList.getAttribute('data-input-type') || 'radio';
        const groupName = optionsList.getAttribute('data-group-name') || ('mc_correct_' + Date.now());
        optionsList.setAttribute('data-group-name', groupName);
        optionsList.innerHTML = makeMCOptionRow(inputType, 0, groupName) +
                                makeMCOptionRow(inputType, 1, groupName);
    }

    // Remove edit mode data
    form.removeAttribute('data-edit-mode');
    form.removeAttribute('data-edit-index');
}

function editQuestion(button, questionIndex) {
    const groupItem = button.closest('.group-item');
    const section = button.closest('.section-container');
    const skill = section.getAttribute('data-skill');
    const groupId = groupItem.getAttribute('data-group-id');
    const partItem = button.closest('.part-item');
    const partId = partItem.getAttribute('data-part-id');
    const part = testData.sections[skill].parts.find(p => String(p.id) === String(partId));

    if (!part) return;

    const group = part.groups.find(g => String(g.id) === String(groupId));
    if (!group || !group.questions[questionIndex]) return;

    const question = group.questions[questionIndex];
    console.log('DEBUG: editQuestion called with question:', question);
    if (question.table_structure) {
        console.log('DEBUG: table_structure type:', typeof question.table_structure);
        console.log('DEBUG: table_structure content:', question.table_structure);
        console.log('DEBUG: table_structure.rows type:', typeof question.table_structure.rows);
        console.log('DEBUG: table_structure.rows:', question.table_structure.rows);
    }

    const form = groupItem.querySelector('.question-inline-form');
    const qType = group.question_type;
    const titleInput = form.querySelector('.question-title-input');
    const questionTitle = question.title || (question.question_data && question.question_data.title) || '';

    // Load question data into form
    const textInput = form.querySelector('.question-text-input');
    if (textInput) setContentEditorValue(textInput, question.text || '');
    if (titleInput) titleInput.value = questionTitle;

    const explanationInput = form.querySelector('.question-explanation-input');
    if (explanationInput) setAnswerHelpValue(explanationInput, question.explanation || '');

    // Populate task image URL if present
    const qTaskImageInput = form.querySelector('.question-task-image-url');
    if (qTaskImageInput) {
        qTaskImageInput.value = (question.question_data && question.question_data.task_image) ? question.question_data.task_image : (group.task_image || '');
    }

    const pointsInput = form.querySelector('.question-points-input');
    if (pointsInput) pointsInput.value = question.points || 0.225;

    // Load type-specific data
    if (qType === 'multiple_choice_single' || qType === 'multiple_choice_multiple') {
        const optionsList = form.querySelector('.mc-options-list');
        const inputType = qType === 'multiple_choice_single' ? 'radio' : 'checkbox';
        const groupName = optionsList.getAttribute('data-group-name') || ('mc_grp_' + Date.now());
        const normalizedCorrectAnswers = normalizeMultipleChoiceCorrectAnswers(question);

        optionsList.setAttribute('data-input-type', inputType);
        optionsList.setAttribute('data-group-name', groupName);

        optionsList.innerHTML = (question.options || []).map((option, i) => {
            const isCorrect = qType === 'multiple_choice_multiple'
                ? normalizedCorrectAnswers.includes(option)
                : question.correctAnswer === option;
            return `<div class="mc-option-row">
                <span style="font-size:13px;font-weight:600;color:#6b7280;min-width:20px;">${String.fromCharCode(65 + i)}.</span>
                <input type="text" placeholder="Enter option text" value="${escapeHtml(option)}">
                <label class="correct-marker">
                    <input type="${inputType}" name="${groupName}" ${isCorrect ? 'checked' : ''}>
                    Correct
                </label>
                <button type="button" class="btn-remove-option" onclick="removeMCOption(this)" title="Remove option"><i class="fas fa-times"></i></button>
            </div>`;
        }).join('');
    } else if (qType === 'true_false_not_given' || qType === 'yes_no_not_given') {
        const answerSelect = form.querySelector('.question-answer-select');
        if (answerSelect) answerSelect.value = question.correctAnswer || '';
    } else if (['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings'].includes(qType)) {
        const matchingQuestions = (group.questions || []).filter(item => (item.type || qType) === qType);
        const primaryMatchingQuestion = matchingQuestions[0] || question;
        const optionMap = normalizeMatchingOptionsMap(primaryMatchingQuestion.options);
        const optionCount = Math.max(2, Object.keys(optionMap).length || 5);
        const matchingRows = matchingQuestions.length
            ? matchingQuestions.map(item => ({
                text: item.text || '',
                correctAnswer: item.correctAnswer || ''
            }))
            : [{
                text: question.text || '',
                correctAnswer: question.correctAnswer || ''
            }];

        const statementsInput = form.querySelector('.matching-statements-count');
        const columnsInput = form.querySelector('.matching-columns-count');
        if (statementsInput) statementsInput.value = String(matchingRows.length);
        if (columnsInput) columnsInput.value = String(optionCount);

        buildMatchingMatrixInForm(form, matchingRows.length, optionCount, {
            options: optionMap,
            rows: matchingRows
        });
    } else if (qType === 'note_completion') {
        renderNoteCompletionAnswerInputs(form, normalizeNoteCompletionAnswers(question.correctAnswers || question.correctAnswer));
        bindNoteCompletionLivePreview(form);
    } else if (qType === 'sentence_completion' || qType === 'summary_completion' || qType === 'diagram_labeling') {
        renderCompletionAnswerInputs(form, normalizeNoteCompletionAnswers(question.correctAnswers || question.correctAnswer));
    } else if (qType === 'table_completion' && question.table_structure) {
        const tcWrap = form.querySelector('.tc-builder-wrap');
        const structureInput = form.querySelector('.tc-table-structure-json');
        const answersInput = form.querySelector('.tc-table-answers-json');

        if (structureInput) structureInput.value = JSON.stringify(question.table_structure);
        if (answersInput) {
            answersInput.value = JSON.stringify({ answers: question.table_structure.answers || [] });
            form.__tableAnswers = question.table_structure.answers || [];
        }

        if (tcWrap && question.table_structure.rows) {
            const headers = question.table_structure.headers || [];
            const rows = question.table_structure.rows || [];

            console.log('DEBUG: Restoring table, rows structure:', rows, 'headers:', headers);

            const head = form.querySelector('.tc-builder-head');
            const body = form.querySelector('.tc-builder-body');

            if (head && body) {
                let headHtml = '<tr>';
                headers.forEach((h, i) => {
                    headHtml += `<th><input type="text" class="tc-col-title form-control" data-col="${i}" placeholder="Column ${i + 1}" value="${escapeHtml(h)}"></th>`;
                });
                headHtml += '</tr>';
                head.innerHTML = headHtml;

                const existingAnswers = {};
                (question.table_structure.answers || []).forEach((item) => {
                    const key = `${item.row}-${item.col}`;
                    existingAnswers[key] = Array.isArray(item.answers) ? item.answers : normalizeTableCellAnswers(item.answers);
                });

                let bodyHtml = '';
                rows.forEach((row, r) => {
                    // Handle both old format (array) and new format (object with cells property)
                    const rowCells = row.cells || row;

                    bodyHtml += '<tr>';
                    for (let c = 0; c < headers.length; c++) {
                        const key = `${r}-${c}`;
                        bodyHtml += makeTableCellEditor(r, c, existingAnswers[key] || []);
                    }
                    bodyHtml += '</tr>';
                });
                body.innerHTML = bodyHtml;

                // Fill cell values using data attributes
                console.log('DEBUG: Starting to fill cell values, total cells:', form.querySelectorAll('.tc-cell-text').length);
                form.querySelectorAll('.tc-cell-text').forEach((textarea, idx) => {
                    const td = textarea.closest('td');
                    const rowIdx = parseInt(td.getAttribute('data-row'), 10);
                    const colIdx = parseInt(td.getAttribute('data-col'), 10);
                    
                    // Handle both old format (array) and new format (object with cells property)
                    const rowData = rows[rowIdx];
                    const cellValue = rowData ? (rowData.cells ? rowData.cells[colIdx] : rowData[colIdx]) : undefined;
                    
                    console.log(`DEBUG: Cell ${idx} (row=${rowIdx}, col=${colIdx}): value="${cellValue}", rowData=${JSON.stringify(rowData)}`);
                    if (cellValue) {
                        textarea.value = cellValue;
                        console.log(`DEBUG: Filled textarea with "${cellValue}"`);
                    }
                    
                    // Sync answer inputs to exact blank count for this cell.
                    syncTableCellAnswerInputs(td);
                });
            }
            tcWrap.classList.remove('hidden');
        }
    } else if (qType === 'drag_drop_disappear' || qType === 'drag_drop_reuse') {
        // Populate Draggable Options
        const ddOptionsList = form.querySelector('.dd-options-list');
        if (ddOptionsList) {
            ddOptionsList.innerHTML = (question.options || []).map((opt) => `
                <div class="dd-option-row d-flex align-items-center mb-2" style="gap:8px;">
                    <input type="text" class="form-control form-control-sm dd-option-input" placeholder="Option text" value="${escapeHtml(opt)}">
                    <button type="button" class="btn btn-sm btn-outline-danger dd-remove-option-btn" onclick="removeDDOption(this)" title="Remove"><i class="fas fa-times"></i></button>
                </div>
            `).join('');
        }
        
        // Populate Correct Answers per Blank
        const ddAnswersList = form.querySelector('.dd-answers-list');
        const ddSummary = form.querySelector('.dd-blank-summary');
        if (ddAnswersList) {
            const answers = question.correctAnswers || [];
            const blankCount = answers.length || countNoteCompletionBlanks(question.text || '');
            
            if (ddSummary) {
                ddSummary.innerHTML = blankCount > 0
                    ? `Detected <strong>${blankCount}</strong> blank${blankCount > 1 ? 's' : ''}. Set the correct option per blank.`
                    : 'Type the text with <code>___</code> for each blank.';
            }
            
            ddAnswersList.innerHTML = answers.map((ans, i) => `
                <div class="d-flex align-items-center mb-2" style="gap:8px;">
                    <span style="width:72px;flex:0 0 auto;font-size:13px;font-weight:600;color:#6b7280;">Blank ${i + 1}</span>
                    <input type="text" class="form-control form-control-sm dd-answer-input" data-blank-index="${i}" placeholder="Correct option for blank ${i + 1}" value="${escapeHtml(ans)}" style="flex:1;min-width:0;">
                </div>
            `).join('');
        }
        
        console.log('✓ Drag & drop form populated with', (question.options || []).length, 'options and', (question.correctAnswers || []).length, 'answers');
    } else {
        const answerInput = form.querySelector('.question-answer-input');
        if (answerInput) answerInput.value = question.correctAnswer || '';
    }

    // Mark as edit mode
    form.setAttribute('data-edit-mode', 'true');
    form.setAttribute('data-edit-index', questionIndex);

    // Show form
    form.classList.remove('hidden');
}

function deleteQuestion(button, questionIndex) {
    if (!confirm('Delete this question?')) return;

    const groupItem = button.closest('.group-item');
    const section = button.closest('.section-container');
    const skill = section.getAttribute('data-skill');
    const groupId = groupItem.getAttribute('data-group-id');
    const partItem = button.closest('.part-item');
    const partId = partItem.getAttribute('data-part-id');
    const part = testData.sections[skill].parts.find(p => String(p.id) === String(partId));

    if (!part) return;

    const group = part.groups.find(g => String(g.id) === String(groupId));
    if (!group) return;

    group.questions.splice(questionIndex, 1);

    renumberSectionQuestions(section);
    updatePartStats(partItem, part);
    updateSectionStats(section);
    updateCompletenessStatus();
}

function saveQuestionToGroup(button) {
    const groupItem = button.closest('.group-item');
    const section = button.closest('.section-container');
    const skill = section.getAttribute('data-skill');
    const groupId = groupItem.getAttribute('data-group-id');
    const partItem = button.closest('.part-item');
    const partId = partItem.getAttribute('data-part-id');
    const part = testData.sections[skill].parts.find(p => String(p.id) === String(partId));

    if (!part) {
        alert('Part not found. Please try again.');
        return;
    }

    const group = part.groups.find(g => String(g.id) === String(groupId));

    if (!group) {
        alert('Group not found. Please try again.');
        return;
    }

    const form = groupItem.querySelector('.question-inline-form');
    const qType = group.question_type || 'short_answer';
    const titleInput = form.querySelector('.question-title-input');
    const title = titleInput ? titleInput.value.trim() : '';
    const textInput = form.querySelector('.question-text-input');
    const text = textInput ? getContentEditorValue(textInput) : '';
    const textPlain = textInput ? getContentEditorPlainValue(textInput) : '';
    const explanationInput = form.querySelector('.question-explanation-input');
    const explanation = getAnswerHelpValue(explanationInput);
    const pointsValue = parseFloat(form.querySelector('.question-points-input').value);
    const points = Number.isFinite(pointsValue) ? pointsValue : 0;
    const isMatchingType = ['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings'].includes(qType);
    const isDragDropType = ['drag_drop_disappear', 'drag_drop_reuse'].includes(qType);

    if (!textPlain && !isMatchingType && !isDragDropType) {
        alert('Question text is required.');
        return;
    }

    let questionData = { id: Date.now(), type: qType, text, explanation: explanation || null, points, title: title || null };
    questionData.question_data = {
        ...(questionData.question_data || {}),
    };
    if (title) {
        questionData.question_data.title = title;
    }

    if (qType === 'multiple_choice_single') {
        const optionRows = form.querySelectorAll('.mc-option-row');
        const options = [];
        let correctAnswer = null;
        optionRows.forEach((row, idx) => {
            const optText = row.querySelector('input[type="text"]').value.trim();
            if (optText) {
                options.push(optText);
                if (row.querySelector('input[type="radio"]').checked) {
                    correctAnswer = optText;
                }
            }
        });
        if (options.length < 2) { alert('Please add at least 2 options.'); return; }
        if (!correctAnswer) { alert('Please select the correct answer.'); return; }
        questionData.options = options;
        questionData.correctAnswer = correctAnswer;

    } else if (qType === 'multiple_choice_multiple') {
        const optionRows = form.querySelectorAll('.mc-option-row');
        const options = [];
        const correctAnswers = [];
        optionRows.forEach((row) => {
            const optText = row.querySelector('input[type="text"]').value.trim();
            if (optText) {
                options.push(optText);
                const selectionInput = row.querySelector('input[type="checkbox"], .correct-marker input');
                if (selectionInput && selectionInput.checked) {
                    correctAnswers.push(optText);
                }
            }
        });
        if (options.length < 2) { alert('Please add at least 2 options.'); return; }
        if (correctAnswers.length === 0) { alert('Please select at least one correct answer.'); return; }
        questionData.options = options;
        questionData.correctAnswers = correctAnswers;
        questionData.correctAnswer = correctAnswers.join(', ');
        questionData.slotCount = correctAnswers.length;

    } else if (qType === 'true_false_not_given' || qType === 'yes_no_not_given') {
        const answerSelect = form.querySelector('.question-answer-select');
        questionData.correctAnswer = answerSelect ? answerSelect.value : null;

    } else if (isMatchingType) {
        const statementRows = Array.from(form.querySelectorAll('.matching-statement-row'));
        const columnCountInput = form.querySelector('.matching-columns-count');
        const columnCount = Math.max(2, Math.min(26, parseInt(columnCountInput ? columnCountInput.value : '0', 10) || 0));

        if (!statementRows.length || !columnCount) {
            alert('Please build the matching matrix first.');
            return;
        }

        const optionsMap = {};
        for (let idx = 0; idx < columnCount; idx++) {
            const key = matchingKeyFromIndex(idx);
            optionsMap[key] = key;
        }

        if (Object.keys(optionsMap).length < 2) {
            alert('Please create at least 2 answer columns.');
            return;
        }

        const matchingQuestions = [];

        for (const row of statementRows) {
            const statementInput = row.querySelector('.matching-statement-text');
            const correctSelect = row.querySelector('.matching-correct-select');

            const statementText = statementInput ? statementInput.value.trim() : '';
            if (!statementText) {
                continue;
            }

            const selectedAnswer = correctSelect ? String(correctSelect.value || '').trim().toUpperCase() : '';
            if (!selectedAnswer || !Object.prototype.hasOwnProperty.call(optionsMap, selectedAnswer)) {
                alert('Please choose a valid correct answer for every non-empty statement.');
                return;
            }

            matchingQuestions.push({
                id: Date.now() + matchingQuestions.length,
                type: qType,
                text: statementText,
                explanation: explanation || null,
                points,
                options: { ...optionsMap },
                correctAnswer: selectedAnswer
            });
        }

        if (!matchingQuestions.length) {
            alert('Please enter at least one statement.');
            return;
        }

        const isEditModeMatching = form.getAttribute('data-edit-mode') === 'true';
        const editIndexMatching = parseInt(form.getAttribute('data-edit-index'), 10);

        if (isEditModeMatching && Number.isFinite(editIndexMatching)) {
            const existingMatchingQuestions = (group.questions || []).filter(item => (item.type || qType) === qType);

            const updatedMatchingQuestions = matchingQuestions.map((item, index) => {
                const existing = existingMatchingQuestions[index] || {};
                const nextItem = {
                    ...existing,
                    ...item,
                    title: title || null,
                    question_data: {
                        ...(existing.question_data || {}),
                    }
                };

                if (title) {
                    nextItem.question_data.title = title;
                }

                return nextItem;
            });

            group.questions = updatedMatchingQuestions;

            resetQuestionForm(form);
            initializeMatchingBuilder(form);
            form.classList.add('hidden');

            renumberSectionQuestions(section);
            updatePartStats(partItem, part);
            updateSectionStats(section);
            updateCompletenessStatus();
            return;
        } else {
            matchingQuestions.forEach((item) => {
                item.title = title || null;
                item.question_data = {
                    ...(item.question_data || {}),
                };
                if (title) {
                    item.question_data.title = title;
                }
            });
            matchingQuestions.forEach((item) => {
                group.questions.push(item);
            });

            resetQuestionForm(form);
            initializeMatchingBuilder(form);
            form.classList.add('hidden');

            renumberSectionQuestions(section);
            updatePartStats(partItem, part);
            updateSectionStats(section);
            updateCompletenessStatus();
            return;
        }

    } else if (qType === 'sentence_completion' || qType === 'summary_completion' || qType === 'diagram_labeling') {
        const completionAnswers = collectCompletionAnswers(form);
        const completionAnswerGroups = collectCompletionAnswerGroups(form);
        const blankCount = countNoteCompletionBlanks(text);

        if (blankCount === 0) {
            alert('Please include at least one blank using ___.');
            return;
        }

        if (completionAnswers.length !== blankCount) {
            alert(`Please enter ${blankCount} answer${blankCount > 1 ? 's' : ''} for the ${blankCount} blank${blankCount > 1 ? 's' : ''}.`);
            return;
        }

        questionData.correctAnswers = completionAnswers;
        questionData.correctAnswer = JSON.stringify(completionAnswerGroups);
        questionData.slotCount = completionAnswerGroups.length;

    } else if (qType === 'note_completion') {
        const noteAnswers = collectNoteCompletionAnswers(form);
        const noteAnswerGroups = collectNoteCompletionAnswerGroups(form);
        const blankCount = countNoteCompletionBlanks(text);

        if (blankCount === 0) {
            alert('Please include at least one blank using ___.');
            return;
        }

        if (noteAnswers.length !== blankCount) {
            alert(`Please enter ${blankCount} answer${blankCount > 1 ? 's' : ''} for the ${blankCount} blank${blankCount > 1 ? 's' : ''}.`);
            return;
        }

        questionData.correctAnswers = noteAnswers;
    questionData.correctAnswer = JSON.stringify(noteAnswerGroups);
    questionData.slotCount = noteAnswerGroups.length;

    } else if (qType === 'table_completion') {
        updateTableCompletionState(form);
        const structureInput = form.querySelector('.tc-table-structure-json');
        const answersInput = form.querySelector('.tc-table-answers-json');
        const tableStructure = structureInput && structureInput.value ? JSON.parse(structureInput.value) : { headers: [], rows: [] };
        const tableAnswers = answersInput && answersInput.value ? JSON.parse(answersInput.value) : { answers: [] };

        const blankCells = Array.from(form.querySelectorAll('.tc-builder-body td')).filter((td) => {
            const textValue = String(td.querySelector('.tc-cell-text')?.value || '');
            return getCompletionBlankCount(textValue) > 0;
        });

        for (const td of blankCells) {
            const rowNumber = (parseInt(td.getAttribute('data-row'), 10) || 0) + 1;
            const colNumber = (parseInt(td.getAttribute('data-col'), 10) || 0) + 1;
            const textValue = String(td.querySelector('.tc-cell-text')?.value || '');
            const blankCount = getCompletionBlankCount(textValue);
            const answerValues = getTableCellAnswerValues(td);
            const filledCount = answerValues.filter(Boolean).length;

            if (answerValues.length !== blankCount || filledCount !== blankCount) {
                alert(`Cell R${rowNumber}C${colNumber} has ${blankCount} blank${blankCount > 1 ? 's' : ''}. Please fill ${blankCount} answer${blankCount > 1 ? 's' : ''}.`);
                return;
            }
        }

        if (!textPlain) {
            alert('Table title / instruction is required.');
            return;
        }

        if (!tableStructure.rows || !tableStructure.rows.length) {
            alert('Please create the table first.');
            return;
        }

        if (!tableAnswers.answers || !tableAnswers.answers.length) {
            alert('Please add at least one blank cell with answer using ___.');
            return;
        }

        questionData.table_structure = {
            headers: tableStructure.headers || [],
            rows: tableStructure.rows || [],
            answers: tableAnswers.answers || []
        };
        questionData.correctAnswer = JSON.stringify(tableAnswers.answers);
        questionData.slotCount = tableAnswers.answers.length;

    } else if (isDragDropType) {
        const ddOptions = collectDDOptions(form);
        const ddAnswers = collectDDAnswers(form);
        const blankCount = countNoteCompletionBlanks(text);

        if (!textPlain) {
            alert('Please enter the question text with ___ blanks.');
            return;
        }

        if (blankCount === 0) {
            alert('Please include at least one blank using ___.');
            return;
        }

        if (ddOptions.length < 1) {
            alert('Please add at least one draggable option.');
            return;
        }

        if (ddAnswers.length !== blankCount) {
            alert(`Please enter ${blankCount} correct answer${blankCount > 1 ? 's' : ''} for the ${blankCount} blank${blankCount > 1 ? 's' : ''} (click "Sync Blanks from Text" first).`);
            return;
        }

        for (let i = 0; i < ddAnswers.length; i++) {
            if (!ddAnswers[i]) {
                alert(`Blank ${i + 1} has no correct answer.`);
                return;
            }
        }

        questionData.options = ddOptions;          // 'options' key → controller reads $questionData['options']
        questionData.correctAnswers = ddAnswers;
        questionData.correctAnswer = JSON.stringify(ddAnswers);
        questionData.slotCount = blankCount;
        // NOTE: do NOT put drag_type in question_data – question_data column is longtext (not JSON)
        //       and the type is already encoded in questionData.type / the DB question_type column.

    } else {
        const answerInput = form.querySelector('.question-answer-input');
        questionData.correctAnswer = answerInput ? answerInput.value.trim() || null : null;
    }

    // Check if in edit mode
    const isEditMode = form.getAttribute('data-edit-mode') === 'true';
    const editIndex = parseInt(form.getAttribute('data-edit-index'), 10);

    if (isEditMode && Number.isFinite(editIndex)) {
        // Attach task image from question-level input or fallback to group's task_image when editing
        try {
            const qImgInput = form.querySelector('.question-task-image-url');
            const qImg = qImgInput ? (qImgInput.value || '').trim() : (group.task_image || null);
            questionData.question_data = questionData.question_data || {};
            if (qImg) {
                questionData.question_data.task_image = qImg;
            }
        } catch (e) {}

        // Update existing question
        group.questions[editIndex] = { ...group.questions[editIndex], ...questionData };
    } else {
        // Add new question
        // Attach task image from question-level input or fallback to group's task_image
        try {
            const qImgInput = form.querySelector('.question-task-image-url');
            const qImg = qImgInput ? (qImgInput.value || '').trim() : (group.task_image || null);
            questionData.question_data = questionData.question_data || {};
            if (qImg) {
                questionData.question_data.task_image = qImg;
            }
        } catch (e) {
            // ignore
        }

        group.questions.push(questionData);
    }

    // Reset form
    resetQuestionForm(form);
    initializeMatchingBuilder(form);
    form.classList.add('hidden');

    renumberSectionQuestions(section);
    updatePartStats(partItem, part);
    updateSectionStats(section);
    updateCompletenessStatus();
}

function getGroupSlotCount(group) {
    const questions = normalizeQuestionsForRender(group ? group.questions : []);
    return questions.reduce((sum, q) => {
        const slotCount = Number.isFinite(Number(q.slotCount)) ? Number(q.slotCount) : 1;
        return sum + Math.max(1, slotCount);
    }, 0);
}

function renumberSectionQuestions(sectionEl) {
    if (!sectionEl) return;
    const skill = sectionEl.getAttribute('data-skill');
    let offset = 0;

    sectionEl.querySelectorAll('.part-item').forEach(partEl => {
        partEl.querySelectorAll('.group-item').forEach(groupEl => {
            const partId = partEl.getAttribute('data-part-id');
            const groupId = groupEl.getAttribute('data-group-id');
            const part = (testData.sections[skill]?.parts || []).find(p => String(p.id) === String(partId));
            const group = part ? (part.groups || []).find(g => String(g.id) === String(groupId)) : null;

            if (group) {
                // renderQuestionsList(groupEl, group, offset);
                renderQuestionsList(groupEl, group, offset + 1);
                offset += getGroupSlotCount(group);
            }
        });
    });
}

function renderQuestionsList(groupItem, group, startNumber = 1) {
    const list = groupItem.querySelector('.questions-list');
    const totalSpan = groupItem.querySelector('.question-total');
    const normalizedQuestions = normalizeQuestionsForRender(group ? group.questions : []);

    if (group) {
        group.questions = normalizedQuestions;
    }

    const totalSlots = normalizedQuestions.reduce((sum, q) => sum + (q.slotCount || 1), 0);
    const totalQuestions = normalizedQuestions.length;

    if (totalSpan) {
        totalSpan.innerHTML = `<i class="fas fa-list-ol"></i> ${totalSlots} question${totalSlots !== 1 ? 's' : ''}`;
    }

    if (!list) {
        return;
    }

    if (totalQuestions === 0) {
        list.style.display = 'none';
        list.innerHTML = '';
        return;
    }

    list.style.display = 'block';
    let slotIndex = startNumber;
    const renderedCards = normalizedQuestions.map((question, qIndex) => {
        const qType = (question && question.type) || group.question_type || 'short_answer';
        const slotCountBase = Number.isFinite(Number(question && question.slotCount)) ? Number(question.slotCount) : 1;
        const slotCount = ['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings'].includes(qType)
            ? 1
            : Math.max(1, slotCountBase);
        const slotLabel = slotCount > 1
            ? `Q${slotIndex}–Q${slotIndex + slotCount - 1}`
            : `Q${slotIndex}`;
        slotIndex += slotCount;

        try {
            const isCollapsed = question.collapsed !== false;
            const isTitleExpanded = question.titleExpanded === true || !isCollapsed;
            const titleValue = question.title || (question.question_data && question.question_data.title) || '';
            const qText = (
                question.text
                || question.question_text
                || (question.question_data && question.question_data.statement)
                || question.statement
                || ''
            );

            let detailHTML = '';

            if (qType === 'table_completion' && question.table_structure) {
                const blankCount = (question.table_structure.answers || []).length;
                const colCount = (question.table_structure.headers || []).length;
                detailHTML = `<div style="font-size:12px;margin-top:4px;">
                    <span class="question-answer-badge">Table: ${colCount} cols, ${blankCount} blanks</span>
                </div>`;
            } else if (qType === 'drag_drop_disappear' || qType === 'drag_drop_reuse') {
                const opts = (question.options || []);
                const ans = Array.isArray(question.correctAnswers) ? question.correctAnswers : [];
                detailHTML = `<div style="font-size:12px;margin-top:4px;">
                    <span class="question-answer-badge">${opts.length} option${opts.length !== 1 ? 's' : ''}, ${ans.length} blank${ans.length !== 1 ? 's' : ''}</span>
                    <span style="margin-left:6px;color:#16a34a;">✓ ${ans.map(a => escapeHtml(a)).join(', ')}</span>
                </div>`;
            } else if (qType === 'note_completion') {
                const blankCount = (Array.isArray(question.correctAnswers) ? question.correctAnswers.length : normalizeNoteCompletionAnswers(question.correctAnswer).length) || (question.slotCount || 1);
                detailHTML = `<div style="font-size:12px;margin-top:4px;">
                    <span class="question-answer-badge">Note: ${blankCount} blanks</span>
                </div>`;
            } else if (qType === 'multiple_choice_single' || qType === 'multiple_choice_multiple') {
                const normalizedCorrectAnswers = normalizeMultipleChoiceCorrectAnswers(question);
                const opts = (question.options || []).map((o, i) => {
                    const isCorrect = qType === 'multiple_choice_multiple'
                        ? normalizedCorrectAnswers.includes(o)
                        : question.correctAnswer === o;
                    return `<span style="margin-right:6px;color:${isCorrect ? '#16a34a' : '#374151'};font-weight:${isCorrect ? '700' : '400'};">${String.fromCharCode(65 + i)}. ${escapeHtml(o)}${isCorrect ? ' ✓' : ''}</span>`;
                }).join('');
                detailHTML = `<div style="font-size:12px;margin-top:4px;">${opts}</div>`;
            } else if (['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings'].includes(qType)) {
                const matchingAnswer = String(question.correctAnswer || question.correct_answer || '').trim();
                detailHTML = matchingAnswer
                    ? `<span class="question-answer-badge">✓ ${escapeHtml(matchingAnswer)}</span>`
                    : '';
            } else if (question.correctAnswer) {
                detailHTML = `<span class="question-answer-badge">✓ ${escapeHtml(question.correctAnswer)}</span>`;
            }

            return `<div class="mb-12" style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px;position:relative;">
                <div class="question-card-header">
                    <div class="question-card-main">
                        <span class="question-badge">${slotLabel}</span>
                        ${titleValue ? `<div class="question-title-wrap"><div class="question-title-text collapsible-title ${isTitleExpanded ? 'expanded' : ''}">${escapeHtml(titleValue)}</div></div>` : ''}
                        <div class="question-preview-text">${escapeHtml(editorHtmlToPlainText(qText))}</div>
                    </div>
                    <div class="question-actions">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleQuestionCollapse(this, ${qIndex})" title="${isCollapsed ? 'Expand question' : 'Collapse question'}">
                            <i class="fas ${isCollapsed ? 'fa-chevron-down' : 'fa-chevron-up'}"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editQuestion(this, ${qIndex})" title="Edit question">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteQuestion(this, ${qIndex})" title="Delete question">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="question-card-body ${isCollapsed ? 'hidden' : ''}" style="margin-top:8px;">
                    <div style="font-size:13px;color:#1f2937;line-height:1.45;">${qText}</div>
                    ${question.explanation ? `<div style="margin-top:6px;font-size:12px;color:#0f766e;font-weight:600;">Answer Help:</div><div style="font-size:12px;color:#0f766e;">${question.explanation}</div>` : ''}
                    ${detailHTML}
                </div>
            </div>`;
        } catch (error) {
            console.warn('Failed to render a question item', error, question);
            const fallbackText = safeQuestionTextForRender(question);
            const fallbackAnswer = safeQuestionAnswerForRender(question);
            const fallbackIsCollapsed = question && question.collapsed !== false;
            const fallbackLabel = slotLabel;

            return `<div class="mb-12" style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px;position:relative;">
                <div class="question-card-header">
                    <div class="question-card-main">
                        <span class="question-badge">${fallbackLabel}</span>
                        <div class="question-preview-text">${escapeHtml(editorHtmlToPlainText(fallbackText))}</div>
                    </div>
                    <div class="question-actions">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleQuestionCollapse(this, ${qIndex})" title="${fallbackIsCollapsed ? 'Expand question' : 'Collapse question'}">
                            <i class="fas ${fallbackIsCollapsed ? 'fa-chevron-down' : 'fa-chevron-up'}"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editQuestion(this, ${qIndex})" title="Edit question">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteQuestion(this, ${qIndex})" title="Delete question">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="question-card-body ${fallbackIsCollapsed ? 'hidden' : ''}" style="margin-top:8px;">
                    <div style="font-size:13px;color:#1f2937;line-height:1.45;">${escapeHtml(fallbackText || '(content unavailable)')}</div>
                    ${fallbackAnswer ? `<span class="question-answer-badge">✓ ${escapeHtml(fallbackAnswer)}</span>` : ''}
                </div>
            </div>`;
        }
    }).filter(Boolean);

    list.innerHTML = renderedCards.join('');

    if (!renderedCards.length) {
        list.style.display = 'none';
    }
}

function normalizeQuestionsForRender(rawQuestions) {
    if (Array.isArray(rawQuestions)) {
        return rawQuestions
            .map((item, index) => normalizeQuestionForRender(item, index))
            .filter(Boolean);
    }

    if (rawQuestions && typeof rawQuestions === 'object') {
        return Object.keys(rawQuestions)
            .sort((a, b) => {
                const aNum = Number(a);
                const bNum = Number(b);
                if (Number.isFinite(aNum) && Number.isFinite(bNum)) {
                    return aNum - bNum;
                }

                return String(a).localeCompare(String(b));
            })
            .map((key) => rawQuestions[key])
            .map((item, index) => normalizeQuestionForRender(item, index))
            .filter(Boolean);
    }

    return [];
}

function normalizeQuestionForRender(item, index) {
    if (!item && item !== 0) {
        return null;
    }

    if (typeof item === 'object') {
        const normalized = { ...item };

        if (!normalized.text && normalized.question_text) {
            normalized.text = normalized.question_text;
        }

        if (!normalized.correctAnswer && normalized.correct_answer) {
            normalized.correctAnswer = normalized.correct_answer;
        }

        if ((normalized.type === 'multiple_choice_multiple' || normalized.question_type === 'multiple_choice_multiple') && !Array.isArray(normalized.correctAnswers)) {
            normalized.correctAnswers = normalizeMultipleChoiceCorrectAnswers(normalized);
        }

        return normalized;
    }

    // Defensive fallback for malformed payloads that contain primitive entries.
    return {
        id: `legacy_${index}`,
        type: 'short_answer',
        text: String(item),
        correctAnswer: '',
        slotCount: 1,
    };
}

function safeQuestionTextForRender(question) {
    if (!question || typeof question !== 'object') {
        return String(question || '');
    }

    const directText = question.text || question.question_text || question.statement;
    if (directText) {
        return String(directText);
    }

    if (question.question_data && typeof question.question_data === 'object') {
        return String(question.question_data.statement || question.question_data.title || '');
    }

    return '';
}

function safeQuestionAnswerForRender(question) {
    if (!question || typeof question !== 'object') {
        return '';
    }

    const answer = question.correctAnswer || question.correct_answer;
    return answer == null ? '' : String(answer);
}

function normalizeMultipleChoiceCorrectAnswers(question) {
    if (!question || typeof question !== 'object') {
        return [];
    }

    if (Array.isArray(question.correctAnswers)) {
        return question.correctAnswers.map(value => String(value).trim()).filter(Boolean);
    }

    const raw = question.correctAnswer || question.correct_answer;

    if (Array.isArray(raw)) {
        return raw.map(value => String(value).trim()).filter(Boolean);
    }

    if (typeof raw === 'string') {
        const text = raw.trim();
        if (!text) {
            return [];
        }

        try {
            const parsed = JSON.parse(text);
            if (Array.isArray(parsed)) {
                return parsed.map(value => String(value).trim()).filter(Boolean);
            }
        } catch (error) {
            // Fall back to comma-separated parsing.
        }

        return text.split(',').map(value => value.trim()).filter(Boolean);
    }

    return [];
}

function getQuestionTypeLabel(type) {
    const labels = {
        essay: 'Essay (Writing / Speaking)',
        multiple_choice_single: 'Multiple Choice – Single Answer',
        multiple_choice_multiple: 'Multiple Choice – Multiple Answers',
        true_false_not_given: 'True / False / Not Given',
        yes_no_not_given: 'Yes / No / Not Given',
        matching_headings: 'Matching Headings',
        matching_information: 'Matching Information',
        matching_features: 'Matching Features',
        matching_sentence_endings: 'Matching Sentence Endings',
        sentence_completion: 'Sentence Completion',
        summary_completion: 'Summary Completion',
        note_completion: 'Note Completion',
        table_completion: 'Table Completion',
        diagram_labeling: 'Diagram Labeling',
        short_answer: 'Short Answer',
        drag_drop_disappear: 'Drag & Drop – Remove from List',
        drag_drop_reuse: 'Drag & Drop – Keep in List',
    };
    return labels[type] || type;
}

function makeMCOptionRow(inputType, optionIndex, groupName) {
    return `<div class="mc-option-row">
        <span style="font-size:13px;font-weight:600;color:#6b7280;min-width:20px;">${String.fromCharCode(65 + optionIndex)}.</span>
        <input type="text" placeholder="Enter option text">
        <label class="correct-marker">
            <input type="${inputType}" name="${groupName}">
            Correct
        </label>
        <button type="button" class="btn-remove-option" onclick="removeMCOption(this)" title="Remove option"><i class="fas fa-times"></i></button>
    </div>`;
}

function normalizeTableCellAnswers(rawAnswer) {
    if (Array.isArray(rawAnswer)) {
        return rawAnswer.map(value => String(value).trim()).filter(Boolean);
    }

    if (rawAnswer && typeof rawAnswer === 'object') {
        if (Array.isArray(rawAnswer.answers)) {
            return rawAnswer.answers.map(value => String(value).trim()).filter(Boolean);
        }

        if (rawAnswer.answer !== undefined) {
            return normalizeTableCellAnswers(rawAnswer.answer);
        }
    }

    if (typeof rawAnswer === 'string') {
        const text = rawAnswer.trim();
        if (!text) {
            return [];
        }

        try {
            const parsed = JSON.parse(text);
            if (Array.isArray(parsed)) {
                return parsed.map(value => String(value).trim()).filter(Boolean);
            }
        } catch (error) {
            // fall back to plain text handling
        }

        return text.includes('|')
            ? text.split('|').map(value => value.trim()).filter(Boolean)
            : [text];
    }

    if (rawAnswer === null || rawAnswer === undefined) {
        return [];
    }

    const text = String(rawAnswer).trim();
    return text ? [text] : [];
}

function normalizeCompletionBlankSource(value) {
    return editorHtmlToPlainText(String(value || ''))
        .replace(/[_\uFF3F\u2017]/g, '_')
        .replace(/\u200B/g, ' ')
        .replace(/\uFEFF/g, ' ');
}

function hasCompletionBlank(value) {
    const normalized = normalizeCompletionBlankSource(value);
    return /(?:_\s*){2,}/.test(normalized);
}

function getCompletionBlankCount(value) {
    const normalized = normalizeCompletionBlankSource(value);
    const matches = normalized.match(/(?:_\s*){2,}/g);
    return matches ? matches.length : 0;
}

function countNoteCompletionBlanks(text) {
    return getCompletionBlankCount(text);
}

function getInlineQuestionType(form) {
    if (!form) {
        return '';
    }

    return form.querySelector('.question-type-select')?.value
        || form.closest('.group-item')?.querySelector('.question-type-select')?.value
        || '';
}

function normalizeNoteCompletionAnswers(rawAnswer) {
    if (Array.isArray(rawAnswer)) {
        return rawAnswer.map(value => formatCompletionAnswerVariants(value)).filter(Boolean);
    }

    if (rawAnswer && typeof rawAnswer === 'object') {
        if (Array.isArray(rawAnswer.answers)) {
            return rawAnswer.answers.map(value => formatCompletionAnswerVariants(value)).filter(Boolean);
        }

        if (rawAnswer.answer !== undefined) {
            return normalizeNoteCompletionAnswers(rawAnswer.answer);
        }
    }

    if (typeof rawAnswer === 'string') {
        const text = rawAnswer.trim();
        if (!text) {
            return [];
        }

        try {
            const parsed = JSON.parse(text);
            if (Array.isArray(parsed)) {
                return normalizeNoteCompletionAnswers(parsed);
            }
        } catch (error) {
            // fall back to plain text handling
        }

        if (text.includes('|')) {
            return text.split('|').map(value => formatCompletionAnswerVariants(value)).filter(Boolean);
        }

        return text.includes('\n')
            ? text.split(/\r?\n/).map(value => formatCompletionAnswerVariants(value)).filter(Boolean)
            : [formatCompletionAnswerVariants(text)];
    }

    if (rawAnswer === null || rawAnswer === undefined) {
        return [];
    }

    const text = String(rawAnswer).trim();
    return text ? [formatCompletionAnswerVariants(text)] : [];
}

function splitCompletionAnswerVariants(rawValue) {
    if (Array.isArray(rawValue)) {
        return rawValue
            .map(value => String(value).trim())
            .filter(Boolean);
    }

    const text = String(rawValue ?? '').trim();
    if (!text) {
        return [];
    }

    return text
        .split(/\s*\/\s*/)
        .map(value => value.trim())
        .filter(Boolean);
}

function formatCompletionAnswerVariants(rawValue) {
    const variants = splitCompletionAnswerVariants(rawValue);
    return variants.join(' / ');
}

function collectCompletionAnswerGroupsFromInputs(inputs) {
    return Array.from(inputs)
        .map(input => splitCompletionAnswerVariants(input.value))
        .filter(group => group.length > 0);
}

function makeNoteCompletionAnswerRowHTML(value = '', index = 0) {
    return `<div class="note-answer-item d-flex align-items-center mb-2" style="gap:8px;width:100%;">
        <span style="width:72px;flex:0 0 auto;font-size:13px;font-weight:600;color:#6b7280;">Blank ${index + 1}</span>
        <input type="text" class="form-control form-control-sm note-completion-answer-input" data-blank-index="${index}" placeholder="Answer(s) for blank ${index + 1}" value="${escapeHtml(value)}" style="flex:1; min-width:0;">
    </div>`;
}

// ── Drag & Drop helpers ────────────────────────────────────────────────────

function collectDDOptions(form) {
    return Array.from(form.querySelectorAll('.dd-option-input'))
        .map(inp => inp.value.trim())
        .filter(Boolean);
}

function collectDDAnswers(form) {
    return Array.from(form.querySelectorAll('.dd-answer-input'))
        .map(inp => inp.value.trim());
}

function addDDOption(button) {
    const list = button.closest('.dd-options-section').querySelector('.dd-options-list');
    const div = document.createElement('div');
    div.className = 'dd-option-row d-flex align-items-center mb-2';
    div.style.gap = '8px';
    div.innerHTML = `<input type="text" class="form-control form-control-sm dd-option-input" placeholder="Option text">
        <button type="button" class="btn btn-sm btn-outline-danger dd-remove-option-btn" onclick="removeDDOption(this)" title="Remove"><i class="fas fa-times"></i></button>`;
    list.appendChild(div);
}

function removeDDOption(button) {
    const list = button.closest('.dd-options-section').querySelector('.dd-options-list');
    const rows = list.querySelectorAll('.dd-option-row');
    if (rows.length <= 1) { alert('You need at least one option.'); return; }
    button.closest('.dd-option-row').remove();
}

function syncDDAnswerInputs(button) {
    const form = button.closest('.question-inline-form');
    if (!form) return;
    const textInput = form.querySelector('.question-text-input');
    const text = textInput ? getContentEditorValue(textInput) : '';
    const blankCount = countNoteCompletionBlanks(text);
    const container = form.querySelector('.dd-answers-list');
    const summary = form.querySelector('.dd-blank-summary');
    if (!container) return;

    const existing = Array.from(container.querySelectorAll('.dd-answer-input')).map(i => i.value);

    if (summary) {
        summary.innerHTML = blankCount > 0
            ? `Detected <strong>${blankCount}</strong> blank${blankCount > 1 ? 's' : ''}. Set the correct option per blank.`
            : 'Type the text with <code>___</code> for each blank.';
    }

    container.innerHTML = '';
    for (let i = 0; i < blankCount; i++) {
        const val = existing[i] || '';
        const row = document.createElement('div');
        row.className = 'd-flex align-items-center mb-2';
        row.style.gap = '8px';
        row.innerHTML = `<span style="width:72px;flex:0 0 auto;font-size:13px;font-weight:600;color:#6b7280;">Blank ${i + 1}</span>
            <input type="text" class="form-control form-control-sm dd-answer-input" data-blank-index="${i}" placeholder="Correct option for blank ${i + 1}" value="${escapeHtml(val)}" style="flex:1;min-width:0;">`;
        container.appendChild(row);
    }
}

// ── End Drag & Drop helpers ────────────────────────────────────────────────

function makeCompletionAnswerRowHTML(value = '', index = 0) {
    return `<div class="completion-answer-item d-flex align-items-center mb-2" style="gap:8px;width:100%;">
        <span style="width:72px;flex:0 0 auto;font-size:13px;font-weight:600;color:#6b7280;">Blank ${index + 1}</span>
        <input type="text" class="form-control form-control-sm completion-answer-input" data-blank-index="${index}" placeholder="Answer(s) for blank ${index + 1}" value="${escapeHtml(value)}" style="flex:1; min-width:0;">
    </div>`;
}

function renderNoteCompletionAnswerInputs(form, values = []) {
    const textInput = form.querySelector('.question-text-input');
    const summary = form.querySelector('.note-completion-summary');
    const container = form.querySelector('.note-completion-answers');

    if (!summary || !container) {
        return;
    }

    const blankCount = countNoteCompletionBlanks(textInput ? getContentEditorValue(textInput) : '');
    summary.innerHTML = blankCount > 0
        ? `Detected <strong>${blankCount}</strong> blank${blankCount > 1 ? 's' : ''}. Enter one or more accepted answers per blank, separated by <code>/</code>.`
        : 'Type the note text with <code>___</code> for each blank.';

    if (blankCount === 0) {
        container.innerHTML = '';
        return;
    }

    const normalizedValues = Array.isArray(values) ? values : normalizeNoteCompletionAnswers(values);
    container.innerHTML = Array.from({ length: blankCount }, (_, index) => makeNoteCompletionAnswerRowHTML(normalizedValues[index] || '', index)).join('');
}

function renderCompletionAnswerInputs(form, values = []) {
    const textInput = form.querySelector('.question-text-input');
    const summary = form.querySelector('.completion-summary');
    const container = form.querySelector('.completion-answers');

    if (!summary || !container) {
        return;
    }

    const blankCount = countNoteCompletionBlanks(textInput ? getContentEditorValue(textInput) : '');
    summary.innerHTML = blankCount > 0
        ? `Detected <strong>${blankCount}</strong> blank${blankCount > 1 ? 's' : ''}. Enter one or more accepted answers per blank, separated by <code>/</code>.`
        : 'Type the sentence/summary text with <code>___</code> for each blank.';

    if (blankCount === 0) {
        container.innerHTML = '';
        return;
    }

    const normalizedValues = Array.isArray(values) ? values : normalizeNoteCompletionAnswers(values);
    container.innerHTML = Array.from({ length: blankCount }, (_, index) => makeCompletionAnswerRowHTML(normalizedValues[index] || '', index)).join('');
}

function collectNoteCompletionAnswers(form) {
    if (!form) {
        return [];
    }

    return collectCompletionAnswerGroupsFromInputs(form.querySelectorAll('.note-completion-answer-input'))
        .map(group => group.join(' / '));
}

function collectNoteCompletionAnswerGroups(form) {
    if (!form) {
        return [];
    }

    return collectCompletionAnswerGroupsFromInputs(form.querySelectorAll('.note-completion-answer-input'));
}

function collectCompletionAnswers(form) {
    if (!form) {
        return [];
    }

    return collectCompletionAnswerGroupsFromInputs(form.querySelectorAll('.completion-answer-input'))
        .map(group => group.join(' / '));
}

function collectCompletionAnswerGroups(form) {
    if (!form) {
        return [];
    }

    return collectCompletionAnswerGroupsFromInputs(form.querySelectorAll('.completion-answer-input'));
}

function bindNoteCompletionLivePreview(form) {
    if (!form || form.dataset.noteCompletionPreviewBound === '1' || !form.querySelector('.note-completion-answers')) {
        return;
    }

    const textInput = form.querySelector('.question-text-input');
    if (!textInput) {
        return;
    }

    form.dataset.noteCompletionPreviewBound = '1';
    textInput.addEventListener('input', function() {
        renderNoteCompletionAnswerInputs(form);
    });
    textInput.addEventListener('change', function() {
        renderNoteCompletionAnswerInputs(form);
    });
}

function makeTableCellAnswerRowHTML(value = '', removable = false) {
    return `<div class="tc-answer-item d-flex align-items-center mb-2" style="gap:8px;width:100%;">
        <input type="text" class="form-control form-control-sm tc-cell-answer" placeholder="Đáp án cho blank (dùng / cho đáp án thay thế)" value="${escapeHtml(value)}" style="flex:1; min-width:0;">
    </div>`;
}

function makeTableCellAnswerListHTML(existingAnswers = [], desiredCount = 1) {
    const safeCount = Math.max(0, parseInt(desiredCount, 10) || 0);
    if (safeCount === 0) {
        return '';
    }

    const normalized = Array.from({ length: safeCount }, (_, index) => {
        return typeof existingAnswers[index] === 'string' ? existingAnswers[index] : '';
    });

    const answers = normalized.length ? normalized : [''];
    return answers.map((answer, index) => makeTableCellAnswerRowHTML(answer, index > 0)).join('');
}

function getTableCellAnswerValues(td) {
    if (!td) {
        return [];
    }

    return Array.from(td.querySelectorAll('.tc-cell-answer')).map(input => String(input.value || '').trim());
}

function syncTableCellAnswerInputs(td, explicitBlankCount = null) {
    if (!td) {
        return 0;
    }

    const textarea = td.querySelector('.tc-cell-text');
    const answerWrap = td.querySelector('.tc-answer-wrap');
    const answerList = td.querySelector('.tc-answer-list');
    const addBtn = td.querySelector('.tc-add-answer-btn');
    const value = textarea ? String(textarea.value || '') : '';
    const blankCount = explicitBlankCount === null ? getCompletionBlankCount(value) : Math.max(0, parseInt(explicitBlankCount, 10) || 0);

    if (!answerWrap || !answerList) {
        return blankCount;
    }

    if (blankCount <= 0) {
        answerWrap.style.display = 'none';
        answerWrap.classList.add('hidden');
        answerList.innerHTML = '';
        if (addBtn) {
            addBtn.style.display = 'none';
        }
        return 0;
    }

    const existingValues = getTableCellAnswerValues(td);
    if (existingValues.length !== blankCount) {
        answerList.innerHTML = makeTableCellAnswerListHTML(existingValues, blankCount);
    }
    answerWrap.style.display = 'block';
    answerWrap.classList.remove('hidden');

    if (addBtn) {
        addBtn.style.display = 'none';
    }

    return blankCount;
}

function makeTableCellEditor(rowIndex, colIndex, existingAnswers = []) {
    return `<td data-row="${rowIndex}" data-col="${colIndex}">
        <textarea class="tc-cell-text form-control" rows="3" placeholder="Nhập nội dung ô. Dùng ___ cho blank"></textarea>
        <div class="tc-answer-wrap hidden" style="display:none; margin-top:6px;">
            <div class="tc-answer-list">
                ${makeTableCellAnswerListHTML(existingAnswers, Math.max(1, existingAnswers.length || 1))}
            </div>
            <button type="button" class="btn btn-sm btn-link p-0 tc-add-answer-btn" onclick="addTableCellAnswer(this)">
                <i class="fas fa-plus"></i> Thêm đáp án
            </button>
        </div>
    </td>`;
}

function addTableCellAnswer(button) {
    const wrap = button.closest('.tc-answer-wrap');
    const list = wrap ? wrap.querySelector('.tc-answer-list') : null;
    if (!list) {
        return;
    }

    list.insertAdjacentHTML('beforeend', makeTableCellAnswerRowHTML('', true));

    const lastInput = list.querySelector('.tc-answer-item:last-child .tc-cell-answer');
    if (lastInput) {
        lastInput.focus();
    }

    const form = button.closest('.question-inline-form');
    if (form) {
        updateTableCompletionState(form);
    }
}

function removeTableCellAnswer(button) {
    const row = button.closest('.tc-answer-item');
    const list = row ? row.closest('.tc-answer-list') : null;
    if (!row || !list) {
        return;
    }

    if (list.querySelectorAll('.tc-answer-item').length > 1) {
        row.remove();
    } else {
        const input = row.querySelector('.tc-cell-answer');
        if (input) {
            input.value = '';
        }
    }

    const form = button.closest('.question-inline-form');
    if (form) {
        updateTableCompletionState(form);
    }
}

function buildTableCompletionBuilder(button) {
    const form = button.closest('.question-inline-form');
    if (!form) return;

    const rowsInput = form.querySelector('.tc-num-rows');
    const colsInput = form.querySelector('.tc-num-cols');
    const totalRows = Math.max(2, parseInt(rowsInput?.value, 10) || 3);
    const rows = Math.max(1, totalRows - 1);
    const cols = Math.max(1, parseInt(colsInput?.value, 10) || 3);
    const wrap = form.querySelector('.tc-builder-wrap');
    const head = form.querySelector('.tc-builder-head');
    const body = form.querySelector('.tc-builder-body');

    if (!wrap || !head || !body) return;

    const existingAnswers = {};
    if (Array.isArray(form.__tableAnswers)) {
        form.__tableAnswers.forEach((item) => {
            const key = `${item.row}-${item.col}`;
            existingAnswers[key] = Array.isArray(item.answers) ? item.answers : normalizeTableCellAnswers(item.answer);
        });
    }

    let headHtml = '<tr>';
    for (let c = 0; c < cols; c++) {
        headHtml += `<th><input type="text" class="tc-col-title form-control" data-col="${c}" placeholder="Cột ${c + 1}"></th>`;
    }
    headHtml += '</tr>';
    head.innerHTML = headHtml;

    let bodyHtml = '';
    for (let r = 0; r < rows; r++) {
        bodyHtml += '<tr>';
        for (let c = 0; c < cols; c++) {
            const key = `${r}-${c}`;
            bodyHtml += makeTableCellEditor(r, c, existingAnswers[key] || []);
        }
        bodyHtml += '</tr>';
    }
    body.innerHTML = bodyHtml;
    wrap.classList.remove('hidden');
    updateTableCompletionState(form);
}

function updateTableCompletionState(form) {
    const rows = [];
    const answers = [];
    const headers = [];

    form.querySelectorAll('.tc-col-title').forEach((input, idx) => {
        headers[idx] = input.value.trim();
    });

    form.querySelectorAll('.tc-builder-body tr').forEach((tr, rowIndex) => {
        const cells = [];
        tr.querySelectorAll('td').forEach(td => {
            const textarea = td.querySelector('.tc-cell-text');
            const value = (textarea?.value || '').trim();
            cells.push(value);

            const blankCount = syncTableCellAnswerInputs(td);
            if (blankCount > 0) {
                const cellAnswers = getTableCellAnswerValues(td);
                answers.push({
                    row: rowIndex,
                    col: parseInt(td.dataset.col, 10),
                    answers: cellAnswers,
                    blank_count: blankCount
                });
            }
        });
        rows.push(cells);
    });

    const structureInput = form.querySelector('.tc-table-structure-json');
    const answersInput = form.querySelector('.tc-table-answers-json');
    if (structureInput) {
        structureInput.value = JSON.stringify({ headers, rows });
    }
    if (answersInput) {
        answersInput.value = JSON.stringify({ answers });
        form.__tableAnswers = answers;
    }
}

function getQuestionFormHTML(questionType) {
    const isMCSingle = questionType === 'multiple_choice_single';
    const isMCMultiple = questionType === 'multiple_choice_multiple';
    const isTFNG = questionType === 'true_false_not_given';
    const isYNNG = questionType === 'yes_no_not_given';
    const isMatching = ['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings'].includes(questionType);
    const isCompletion = ['sentence_completion', 'summary_completion', 'note_completion', 'table_completion', 'diagram_labeling'].includes(questionType);
    const isDragDrop = questionType === 'drag_drop_disappear' || questionType === 'drag_drop_reuse';

    let html = '';
    const titleField = `<div class="form-row">
        <div class="form-group">
            <label class="input-label">Question Title</label>
            <input type="text" class="form-control question-title-input" placeholder="Optional short title for this question">
        </div>
    </div>`;

    if (questionType === 'essay') {
        html += `${titleField}<div class="form-row">
            <div class="form-group">
                <label class="input-label">Prompt / Task *</label>
                <textarea class="form-control question-text-input js-richtext-editor" data-height="160" rows="3" placeholder="Enter the essay prompt or task"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Optional Task Image URL</label>
                <input type="text" class="form-control question-task-image-url" placeholder="e.g. https://... or leave blank">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Model Answer</label>
                <textarea class="form-control question-explanation-input js-answer-help-editor" rows="3" data-height="180" placeholder="Provide a model answer or guidance for review..."></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
            </div>
        </div>`;
        return html;
    }

    html = `<div class="mb-8"><span class="question-type-badge">${getQuestionTypeLabel(questionType)}</span></div>`;

    if (isMCSingle || isMCMultiple) {
        const inputType = isMCSingle ? 'radio' : 'checkbox';
        const groupName = 'mc_correct_' + Date.now();
        const hint = isMCSingle ? 'Select the one correct answer.' : 'Check all correct answers.';
        html += `${titleField}<div class="form-row">
            <div class="form-group">
                <label class="input-label">Question Text *</label>
                <textarea class="form-control question-text-input js-richtext-editor" data-height="150" rows="2" placeholder="Enter the question"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Answer Help</label>
                <textarea class="form-control question-explanation-input js-answer-help-editor" rows="2" data-height="180" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
            </div>
        </div>
        <div class="mc-options-container">
            <label class="input-label">Answer Options <small class="text-muted">(${hint})</small></label>
            <div class="mc-options-list" data-input-type="${inputType}" data-group-name="${groupName}">
                ${makeMCOptionRow(inputType, 0, groupName)}
                ${makeMCOptionRow(inputType, 1, groupName)}
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary mt-8" onclick="addMCOption(this)">
                <i class="fas fa-plus mr-4"></i> Add Option
            </button>
        </div>
        <div class="form-row">
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
            </div>
        </div>`;

    } else if (isTFNG || isYNNG) {
        const opts = isTFNG
            ? ['True', 'False', 'Not Given']
            : ['Yes', 'No', 'Not Given'];
        const optionsHTML = opts.map(o => `<option value="${o}">${o}</option>`).join('');
        html += `${titleField}<div class="form-row">
            <div class="form-group">
                <label class="input-label">Statement *</label>
                <textarea class="form-control question-text-input js-richtext-editor" data-height="150" rows="2" placeholder="Enter the statement to evaluate"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Answer Help</label>
                <textarea class="form-control question-explanation-input js-answer-help-editor" rows="2" data-height="180" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Correct Answer</label>
                <select class="form-control question-answer-select">${optionsHTML}</select>
            </div>
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
            </div>
        </div>`;

    } else if (isMatching) {
        html += `${titleField}<div class="form-row">
            <div class="form-group">
                <label class="input-label">Answer Help</label>
                <textarea class="form-control question-explanation-input js-answer-help-editor" rows="2" data-height="180" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group" style="max-width:160px;">
                <label class="input-label">Statements</label>
                <input type="number" class="form-control matching-statements-count" min="1" max="40" value="4">
            </div>
            <div class="form-group" style="max-width:160px;">
                <label class="input-label">Answer Columns</label>
                <input type="number" class="form-control matching-columns-count" min="2" max="26" value="5">
            </div>
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
            </div>
            <div class="form-group d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="generateMatchingMatrix(this)">
                    <i class="fas fa-table mr-4"></i> Build Matrix
                </button>
            </div>
        </div>
        <div class="alert alert-info py-2 px-3 mb-12">
            Create statements and answer columns like the test interface. Each statement will be saved as one question row.
        </div>
        <div class="matching-matrix-wrap"></div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Question Text / Prompt (optional)</label>
                <textarea class="form-control question-text-input js-richtext-editor" data-height="150" rows="2" placeholder="Optional shared prompt for this matching set"></textarea>
            </div>
        </div>`;

    } else if (isCompletion) {
        if (questionType === 'table_completion') {
            html += `${titleField}<div class="form-row">
                <div class="form-group flex-fill">
                    <label class="input-label">Table Title / Instruction *</label>
                    <textarea class="form-control question-text-input js-richtext-editor" data-height="150" rows="2" placeholder="Nhập tiêu đề hoặc hướng dẫn cho bảng"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group flex-fill">
                    <label class="input-label">Answer Help</label>
                    <textarea class="form-control question-explanation-input js-answer-help-editor" rows="2" data-height="180" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
                </div>
            </div>
            <div class="form-row align-items-end">
                <div class="form-group" style="max-width:110px;">
                    <label class="input-label">Rows (include header)</label>
                    <input type="number" class="form-control tc-num-rows" min="2" max="12" value="3">
                </div>
                <div class="form-group" style="max-width:110px;">
                    <label class="input-label">Cols</label>
                    <input type="number" class="form-control tc-num-cols" min="1" max="8" value="3">
                </div>
                <div class="form-group" style="max-width:120px;">
                    <label class="input-label">Points</label>
                    <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
                </div>
                <div class="form-group">
                    <button type="button" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;" onclick="buildTableCompletionBuilder(this)">
                        <i class="fas fa-table"></i> Create Table
                    </button>
                </div>
            </div>
            <div class="alert alert-info py-2 px-3 mb-12">
                Nhập nội dung trong từng ô. Ô nào có <code>___</code> sẽ là chỗ trống cho học viên điền đáp án. Dùng <code>/</code> trong ô đáp án để thêm nhiều phương án đúng.
            </div>
            <div class="tc-builder-wrap hidden">
                <div class="table-responsive" style="overflow-x:auto;">
                    <table class="table table-bordered tc-inline-table">
                        <thead class="tc-builder-head"></thead>
                        <tbody class="tc-builder-body"></tbody>
                    </table>
                </div>
                <input type="hidden" class="tc-table-structure-json">
                <input type="hidden" class="tc-table-answers-json">
            </div>`;
        } else if (questionType === 'note_completion') {
            html += `${titleField}<div class="form-row">
                <div class="form-group flex-fill">
                    <label class="input-label">Question / Note Text *</label>
                    <textarea class="form-control question-text-input js-richtext-editor" data-height="150" rows="2" placeholder="Enter the note text and use ___ for each blank"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group flex-fill">
                    <label class="input-label">Answer Help</label>
                    <textarea class="form-control question-explanation-input js-answer-help-editor" rows="2" data-height="180" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
                </div>
            </div>
            <div class="alert alert-info py-2 px-3 mb-12 note-completion-summary">
                Type the note text with <code>___</code> for each blank. Use <code>/</code> inside an answer field for multiple accepted answers.
            </div>
            <div class="note-completion-answers"></div>
            <div class="form-row mt-8">
                <div class="form-group" style="max-width:120px;">
                    <label class="input-label">Points</label>
                    <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
                </div>
            </div>`;
        } else {
            const placeholder = 'Enter the sentence/text (use ___ to indicate the blank)';
            html += `${titleField}<div class="form-row">
                <div class="form-group">
                    <label class="input-label">Question / Sentence * <small class="text-muted">(use ___ for blank)</small></label>
                    <textarea class="form-control question-text-input js-richtext-editor" data-height="150" rows="2" placeholder="${placeholder}"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="input-label">Answer Help</label>
                    <textarea class="form-control question-explanation-input js-answer-help-editor" rows="2" data-height="180" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
                </div>
            </div>
            <div class="alert alert-info py-2 px-3 mb-12 completion-summary">
                Type the sentence/summary text with <code>___</code> for each blank. Use <code>/</code> inside an answer field for multiple accepted answers.
            </div>
            <div class="completion-answers"></div>
            <div class="form-row">
                <div class="form-group" style="max-width:120px;">
                    <label class="input-label">Points</label>
                    <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
                </div>
            </div>`;
        }

    } else if (isDragDrop) {
        const ddLabel = questionType === 'drag_drop_disappear'
            ? 'Drag &amp; Drop – Remove from List'
            : 'Drag &amp; Drop – Keep in List';
        html += `<div class="mb-8"><span class="question-type-badge">${ddLabel}</span></div>
        ${titleField}
        <div class="form-row">
            <div class="form-group flex-fill">
                <label class="input-label">Question / Passage * <small class="text-muted">(use ___ for each blank)</small></label>
                <textarea class="form-control question-text-input js-richtext-editor" data-height="160" rows="3" placeholder="Enter the text. Use ___ where students should drop an option."></textarea>
            </div>
        </div>
        <div class="alert alert-info py-2 px-3 mb-12 dd-blank-summary">
            Type the text with <code>___</code> for each blank. Add options below, then set the correct answer per blank.
        </div>
        <div class="form-row">
            <div class="form-group flex-fill">
                <label class="input-label">Answer Help</label>
                <textarea class="form-control question-explanation-input js-answer-help-editor" rows="2" data-height="180" placeholder="Optional hint or explanation for review..."></textarea>
            </div>
        </div>
        <div class="dd-options-section" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px;margin-bottom:12px;">
            <label class="input-label">Draggable Options <small class="text-muted">(the pool of answer chips)</small></label>
            <div class="dd-options-list" style="margin-bottom:8px;">
                <div class="dd-option-row d-flex align-items-center mb-2" style="gap:8px;">
                    <input type="text" class="form-control form-control-sm dd-option-input" placeholder="Option text">
                    <button type="button" class="btn btn-sm btn-outline-danger dd-remove-option-btn" onclick="removeDDOption(this)" title="Remove"><i class="fas fa-times"></i></button>
                </div>
                <div class="dd-option-row d-flex align-items-center mb-2" style="gap:8px;">
                    <input type="text" class="form-control form-control-sm dd-option-input" placeholder="Option text">
                    <button type="button" class="btn btn-sm btn-outline-danger dd-remove-option-btn" onclick="removeDDOption(this)" title="Remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addDDOption(this)"><i class="fas fa-plus mr-4"></i> Add Option</button>
        </div>
        <div class="dd-answers-section" style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:14px;margin-bottom:12px;">
            <label class="input-label">Correct Answer per Blank <small class="text-muted">(must exactly match one of the options above)</small></label>
            <div class="dd-answers-list"></div>
            <button type="button" class="btn btn-sm btn-outline-primary mt-8" onclick="syncDDAnswerInputs(this)"><i class="fas fa-sync mr-4"></i> Sync Blanks from Text</button>
        </div>
        <div class="form-row">
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points (per blank)</label>
                <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
            </div>
        </div>`;

    } else {
        html += `${titleField}<div class="form-row">
            <div class="form-group">
                <label class="input-label">Question *</label>
                <textarea class="form-control question-text-input js-richtext-editor" data-height="150" rows="2" placeholder="Enter the question"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Model Answer / Keywords</label>
                <input type="text" class="form-control question-answer-input" placeholder="Enter model answer or key words">
            </div>
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
            </div>
        </div>`;
    }

    return html;
}

function addMCOption(button) {
    const container = button.closest('.mc-options-container').querySelector('.mc-options-list');
    const inputType = container.getAttribute('data-input-type') || 'radio';
    const groupName = container.getAttribute('data-group-name') || ('mc_grp_' + Date.now());
    const currentCount = container.querySelectorAll('.mc-option-row').length;
    const div = document.createElement('div');
    div.innerHTML = makeMCOptionRow(inputType, currentCount, groupName);
    container.appendChild(div.firstElementChild);
}

function removeMCOption(button) {
    const container = button.closest('.mc-options-list');
    const rows = container.querySelectorAll('.mc-option-row');
    if (rows.length <= 2) {
        alert('You need at least 2 options.');
        return;
    }
    button.closest('.mc-option-row').remove();
    container.querySelectorAll('.mc-option-row').forEach((row, i) => {
        const label = row.querySelector('span');
        if (label) label.textContent = String.fromCharCode(65 + i) + '.';
    });
}

function matchingKeyFromIndex(index) {
    let value = Number(index) + 1;
    let key = '';

    while (value > 0) {
        const remainder = (value - 1) % 26;
        key = String.fromCharCode(65 + remainder) + key;
        value = Math.floor((value - 1) / 26);
    }

    return key;
}

function normalizeMatchingOptionsMap(rawOptions) {
    if (!rawOptions) {
        return {};
    }

    if (Array.isArray(rawOptions)) {
        const map = {};
        rawOptions.forEach((value, index) => {
            map[matchingKeyFromIndex(index)] = String(value || '').trim();
        });
        return map;
    }

    if (typeof rawOptions === 'object') {
        const map = {};
        Object.keys(rawOptions).forEach((key) => {
            const normalizedKey = String(key || '').trim().toUpperCase();
            if (!normalizedKey) {
                return;
            }

            map[normalizedKey] = String(rawOptions[key] || '').trim();
        });
        return map;
    }

    return {};
}

function buildMatchingMatrixInForm(form, statementCount, columnCount, seed = null) {
    if (!form) {
        return;
    }

    const wrap = form.querySelector('.matching-matrix-wrap');
    if (!wrap) {
        return;
    }

    const safeStatements = Math.max(1, Math.min(40, parseInt(statementCount, 10) || 4));
    const safeColumns = Math.max(2, Math.min(26, parseInt(columnCount, 10) || 5));

    const rowSeed = Array.isArray(seed && seed.rows ? seed.rows : null) ? seed.rows : [];

    const statementRows = [];
    for (let i = 0; i < safeStatements; i++) {
        const row = rowSeed[i] || {};
        const statementText = String(row.text || '').trim();
        const selectedAnswer = String(row.correctAnswer || '').trim().toUpperCase();

        const selectOptions = [];
        for (let j = 0; j < safeColumns; j++) {
            const key = matchingKeyFromIndex(j);
            selectOptions.push(`<option value="${key}" ${selectedAnswer === key ? 'selected' : ''}>${key}</option>`);
        }

        statementRows.push(`<tr class="matching-statement-row" data-row-index="${i}">
            <td style="width:60px;vertical-align:middle;"><strong>${i + 1}</strong></td>
            <td>
                <input type="text" class="form-control form-control-sm matching-statement-text" placeholder="Statement ${i + 1}" value="${escapeHtml(statementText)}">
            </td>
            <td style="width:130px;">
                <select class="form-control form-control-sm matching-correct-select">${selectOptions.join('')}</select>
            </td>
        </tr>`);
    }

    wrap.innerHTML = `<div class="matching-builder-card" style="border:1px solid #dbeafe;border-radius:8px;padding:12px;background:#fff;">
        <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-bordered mb-0" style="min-width:620px;">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Statement</th>
                        <th style="width:130px;">Correct</th>
                    </tr>
                </thead>
                <tbody>
                    ${statementRows.join('')}
                </tbody>
            </table>
        </div>
    </div>`;
}

function initializeMatchingBuilder(form) {
    const matrixWrap = form ? form.querySelector('.matching-matrix-wrap') : null;
    if (!matrixWrap) {
        return;
    }

    const statementsInput = form.querySelector('.matching-statements-count');
    const columnsInput = form.querySelector('.matching-columns-count');

    buildMatchingMatrixInForm(
        form,
        statementsInput ? statementsInput.value : 4,
        columnsInput ? columnsInput.value : 5
    );
}

function generateMatchingMatrix(button) {
    const form = button ? button.closest('.question-inline-form') : null;
    if (!form) {
        return;
    }

    const statementsInput = form.querySelector('.matching-statements-count');
    const columnsInput = form.querySelector('.matching-columns-count');

    buildMatchingMatrixInForm(
        form,
        statementsInput ? statementsInput.value : 4,
        columnsInput ? columnsInput.value : 5
    );
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text == null ? '' : String(text);
    return div.innerHTML;
}

function updateSectionStats(section) {
    const skill = section.getAttribute('data-skill');
    const parts = testData.sections[skill].parts;
    const partCount = parts.length;
    const groupCount = parts.reduce((sum, part) => sum + (part.groups?.length || 0), 0);
    const questionCount = parts.reduce((sum, part) => {
        return sum + (part.groups || []).reduce((groupSum, group) => groupSum + getGroupSlotCount(group), 0);
    }, 0);

    const partCountEl = section.querySelector('.part-count');
    if (partCountEl) {
        partCountEl.textContent = partCount;
    }

    section.querySelector('.group-count').textContent = groupCount;
    section.querySelector('.question-count').textContent = questionCount;
}

function updatePartStats(partItem, part) {
    const groupCount = part ? part.groups.length : partItem.querySelectorAll('.group-item').length;
    const questionCount = part ? part.groups.reduce((sum, group) => sum + getGroupSlotCount(group), 0) : 0;

    const groupCountEl = partItem.querySelector('.group-total');
    const questionCountEl = partItem.querySelector('.question-total');

    if (groupCountEl) {
        groupCountEl.innerHTML = `<i class="fas fa-layer-group"></i> ${groupCount} groups`;
    }

    if (questionCountEl) {
        questionCountEl.innerHTML = `<i class="fas fa-list-ol"></i> ${questionCount} questions`;
    }
}

function removePart(button) {
    if (confirm('Remove this part and all of its groups?')) {
        const partItem = button.closest('.part-item');
        const section = button.closest('.section-container');
        const skill = section.getAttribute('data-skill');
        const partId = partItem.getAttribute('data-part-id');

        testData.sections[skill].parts = testData.sections[skill].parts.filter(part => String(part.id) !== String(partId));
        partItem.remove();
        updateSectionStats(section);
        updateCompletenessStatus();
    }
}

function removeGroup(button) {
    if (confirm('Remove this question group?')) {
        const groupItem = button.closest('.group-item');
        const groupId = groupItem.getAttribute('data-group-id');
        const partItem = button.closest('.part-item');
        const section = button.closest('.section-container');
        const skill = section.getAttribute('data-skill');
        const part = testData.sections[skill].parts.find(p => String(p.id) === String(partItem.getAttribute('data-part-id')));

        if (part) {
            part.groups = part.groups.filter(g => String(g.id) !== String(groupId));
        }

        groupItem.remove();
        updatePartStats(partItem, part || null);
        updateSectionStats(section);
        updateCompletenessStatus();
    }
}

function updateCompletenessStatus() {
    if (!currentTestType) return;

    const checklist = document.getElementById('completenessChecklist');
    const isMock = currentTestType === 'mock';
    let hasAtLeastOneSection = false;
    let allMockComplete = true;

    Object.entries(SECTIONS_CONFIG).forEach(([skill, config]) => {
        const parts = testData.sections[skill].parts;
        const partCount = parts.length;
        const isRequiredForMock = isMock && config.mockParts > 0;
        const isComplete = isRequiredForMock ? partCount >= config.mockParts : partCount > 0;
        const hasAny = partCount > 0;
        const statusSpan = checklist.querySelector(`.status-${skill}`);

        if (statusSpan) {
            if (isMock && !isRequiredForMock) {
                statusSpan.textContent = hasAny ? '✓' : '○';
                statusSpan.style.color = hasAny ? '#10b981' : '#d1d5db';
            } else {
                statusSpan.textContent = isComplete ? '✓' : (hasAny ? '◐' : '○');
                statusSpan.style.color = isComplete ? '#10b981' : (hasAny ? '#f59e0b' : '#d1d5db');
            }
        }

        if (hasAny) hasAtLeastOneSection = true;
        if (isRequiredForMock && !isComplete) allMockComplete = false;
    });

    const status = document.getElementById('completenessStatus');
    const isComplete = isMock ? allMockComplete : hasAtLeastOneSection;

    if (isComplete) {
        status.classList.add('success');
    } else {
        status.classList.remove('success');
    }
}

function setupFormValidation() {
    document.getElementById('testForm').addEventListener('submit', function(e) {
        e.preventDefault();

        saveAutosaveSnapshot(true);

        const submitter = e.submitter || document.activeElement;
        const submitAction = submitter && submitter.getAttribute && submitter.getAttribute('name') === 'submit_action'
            ? submitter.value
            : 'submit';

        const submitActionInput = document.getElementById('submitActionInput');
        if (submitActionInput) {
            submitActionInput.value = submitAction;
        }

        // Preserve unsaved edits (especially Answer Help) from currently open edit forms.
        syncOpenQuestionEditFormsForDraft();

        if (submitAction === 'draft' || submitAction === 'preview') {
            if (!syncOpenMatchingFormsIntoTestData()) {
                return;
            }

            if (submitAction === 'preview') {
                let totalParts = 0;
                Object.values(testData.sections).forEach(section => {
                    totalParts += section.parts.length;
                });

                if (totalParts === 0) {
                    alert('Please add at least 1 part before previewing the test.');
                    return;
                }
                
                // SAVE form state to localStorage BEFORE navigating to preview
                saveFormStateToLocalStorage();
            }

            preserveSectionAudioFiles();
            document.getElementById('questionGroupsData').value = JSON.stringify(testData);
            document.getElementById('testForm').submit();
            return;
        }

        const testType = document.getElementById('testTypeSelect').value;
        if (!testType) {
            alert('Please select a test type');
            return;
        }

        const isMock = testType === 'mock';
        let issues = [];

        if (isMock) {
            Object.entries(SECTIONS_CONFIG).forEach(([skill, config]) => {
                const parts = testData.sections[skill].parts;
                if (config.mockParts > 0 && parts.length < config.mockParts) {
                    issues.push(`${config.title}: need ${config.mockParts} parts`);
                }

                if (config.mockParts > 0 && parts.length === 0) {
                    issues.push(`${config.title}: missing parts`);
                }

                parts.forEach((part, index) => {
                    if (!part.groups || part.groups.length === 0) {
                        issues.push(`${config.title} Part ${index + 1}: missing at least 1 question group`);
                    }
                });
            });

            if (issues.length > 0) {
                alert('Mock Test requires the IELTS part structure in each section:\n\n' + issues.join('\n'));
                return;
            }
        } else {
            let totalParts = 0;
            Object.values(testData.sections).forEach(section => {
                totalParts += section.parts.length;
            });

            if (totalParts === 0) {
                alert('Practice Test requires at least 1 part');
                return;
            }

            const partsWithoutGroups = [];
            Object.entries(testData.sections).forEach(([skill, section]) => {
                section.parts.forEach((part, index) => {
                    if (!part.groups || part.groups.length === 0) {
                        partsWithoutGroups.push(`${skill} Part ${index + 1}`);
                    }
                });
            });

            if (partsWithoutGroups.length > 0) {
                alert('Every part must contain at least 1 question group:\n\n' + partsWithoutGroups.join('\n'));
                return;
            }
        }

        // Ensure section-level audio inputs are preserved into the form before native submit
        preserveSectionAudioFiles();
        document.getElementById('questionGroupsData').value = JSON.stringify(testData);
        document.getElementById('testForm').submit();
    });
}

function resolveOpenQuestionFormContext(form) {
    const groupItem = form ? form.closest('.group-item') : null;
    const partItem = form ? form.closest('.part-item') : null;
    const section = form ? form.closest('.section-container') : null;

    if (!groupItem || !partItem || !section) {
        return null;
    }

    const skill = section.getAttribute('data-skill');
    const partId = partItem.getAttribute('data-part-id');
    const groupId = groupItem.getAttribute('data-group-id');
    const part = testData.sections[skill]?.parts?.find(p => String(p.id) === String(partId));
    const group = part ? (part.groups || []).find(g => String(g.id) === String(groupId)) : null;

    if (!part || !group) {
        return null;
    }

    return { groupItem, partItem, section, skill, part, group };
}

function syncOpenQuestionEditFormsForDraft() {
    const openForms = Array.from(document.querySelectorAll('.group-item .question-inline-form'))
        .filter(form => !form.classList.contains('hidden'));

    openForms.forEach((form) => {
        if (form.getAttribute('data-edit-mode') !== 'true') {
            return;
        }

        const editIndex = parseInt(form.getAttribute('data-edit-index'), 10);
        if (!Number.isFinite(editIndex)) {
            return;
        }

        const ctx = resolveOpenQuestionFormContext(form);
        if (!ctx) {
            return;
        }

        const qType = getInlineQuestionType(form) || (ctx.group.question_type || 'short_answer');
        const existingQuestion = (ctx.group.questions || [])[editIndex];

        if (!existingQuestion) {
            return;
        }

        const explanationInput = form.querySelector('.question-explanation-input');
        const explanation = explanationInput ? getAnswerHelpValue(explanationInput) : null;

        const titleInput = form.querySelector('.question-title-input');
        const title = titleInput ? titleInput.value.trim() : '';

        const textInput = form.querySelector('.question-text-input');
        const text = textInput ? getContentEditorValue(textInput) : existingQuestion.text;

        const pointsInput = form.querySelector('.question-points-input');
        const pointsValue = pointsInput ? parseFloat(pointsInput.value) : NaN;
        const points = Number.isFinite(pointsValue) ? pointsValue : existingQuestion.points;

        const patchQuestion = function (question) {
            const nextQuestion = {
                ...question,
                explanation: explanation || null,
                points,
                title: title || null,
                text,
                question_data: {
                    ...(question.question_data || {})
                }
            };

            if (title) {
                nextQuestion.question_data.title = title;
            }

            return nextQuestion;
        };

        if (['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings'].includes(qType)) {
            ctx.group.questions = (ctx.group.questions || []).map((question) => {
                if ((question.type || qType) !== qType) {
                    return question;
                }

                return patchQuestion(question);
            });
        } else {
            ctx.group.questions[editIndex] = patchQuestion(existingQuestion);
        }

        renderQuestionsList(ctx.groupItem, ctx.group);
        updatePartStats(ctx.partItem, ctx.part);
        updateSectionStats(ctx.section);
    });

    updateCompletenessStatus();
}

    // Ensure section audio inputs are preserved whenever the form is submitted
// ─── Form State Preservation (localStorage for preview exit) ─────────────────

/**
 * Get test ID from either Blade context or URL
 */
function getTestId() {
    // Try Blade variable first
    let testId = @json($test->id ?? null);
    if (testId) return testId;
    
    // Fallback: extract from URL (e.g., /114/edit-inline)
    const match = window.location.pathname.match(/\/(\d+)\/edit-inline/);
    if (match) return match[1];
    
    return null;
}

/**
 * Save current form state to localStorage before navigating away
 * Used to restore data when user exits preview mode
 */
function saveFormStateToLocalStorage() {
    try {
        const testId = getTestId();
        if (!testId) {
            console.log('No testId found, skipping localStorage save');
            return;
        }
        
        const key = 'ielts_test_form_state_' + testId;
        const state = {
            sections: testData.sections,
            timestamp: Date.now(),
            version: 1
        };
        
        localStorage.setItem(key, JSON.stringify(state));
        console.log('✓ Form state saved to localStorage for test', testId);
    } catch (err) {
        console.error('Failed to save form state:', err);
    }
}

function syncOpenMatchingFormsIntoTestData() {
    const openForms = Array.from(document.querySelectorAll('.group-item .question-inline-form'))
        .filter(form => !form.classList.contains('hidden'));

    for (const form of openForms) {
        const qType = getInlineQuestionType(form);
        if (!['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings'].includes(qType)) {
            continue;
        }

        const hasContent = Array.from(form.querySelectorAll('.matching-statement-text'))
            .some(input => String(input.value || '').trim() !== '');

        if (!hasContent) {
            continue;
        }

        const saveButton = form.querySelector('button[onclick="saveQuestionToGroup(this)"]');
        if (!saveButton) {
            continue;
        }

        saveQuestionToGroup(saveButton);

        if (!form.classList.contains('hidden')) {
            return false;
        }
    }

    return true;
}

/**
 * Restore form state from localStorage after returning from preview
 * Called on page load to rebuild the form with previous data
 */
function restoreFormStateFromLocalStorage() {
    try {
        const params = new URLSearchParams(window.location.search || '');
        const restoreState = params.get('restore_state');
        if (restoreState !== '1') {
            console.log('ℹ Skip local restore (not returning from preview)');
            return;
        }

        const testId = getTestId();
        if (!testId) {
            console.log('⚠ No testId found, skipping localStorage restore');
            return;
        }
        
        const key = 'ielts_test_form_state_' + testId;
        const saved = localStorage.getItem(key);
        
        if (!saved) {
            console.log('ℹ No saved form state found for test', testId);
            return;
        }
        
        const state = JSON.parse(saved);
        if (!state.sections) {
            console.log('⚠ Invalid saved state (no sections)');
            return;
        }
        
        // Only restore if it's recent (less than 30 minutes old)
        const ageMinutes = (Date.now() - state.timestamp) / (60 * 1000);
        if (ageMinutes > 30) {
            console.log('ℹ Saved state is too old (' + ageMinutes.toFixed(1) + ' minutes), discarding');
            localStorage.removeItem(key);
            return;
        }
        
        console.log('✓ Restoring form state from preview exit (saved ' + ageMinutes.toFixed(1) + ' minutes ago)');
        
        // Merge the restored state into testData
        if (window.testData && window.testData.sections) {
            testData.sections = state.sections;
            console.log('✓ Merging restored data into testData...');
            
            // Re-render all sections to show restored data
            renderExistingTestData();
            updateCompletenessStatus();
            
            console.log('✓ Form state successfully restored!');
            
            // Clear localStorage after successful restore
            localStorage.removeItem(key);
        } else {
            console.warn('⚠ testData not initialized, cannot restore');
        }
        
    } catch (err) {
        console.error('✗ Failed to restore form state:', err);
    }
}

/**
 * Clear saved form state for a test
 */
function clearFormStateLocalStorage() {
    try {
        const testId = getTestId();
        if (!testId) return;
        
        const key = 'ielts_test_form_state_' + testId;
        localStorage.removeItem(key);
        console.log('Form state cleared for test', testId);
    } catch (err) {
        console.error('Failed to clear form state:', err);
    }
}

// ─── End Form State Preservation ──────────────────────────────────────────────

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('testForm');
        if (form) {
            form.addEventListener('submit', function() {
                preserveSectionAudioFiles();
            });
        }
    });

// --- AUTO UPDATE TRẠNG THÁI NÚT CĂN LỀ KHI DI CHUYỂN CON TRỎ ---
$(document).on('click keyup', '.note-editable', function() {
    var $editable = $(this);
    var $toolbar = $editable.siblings('.note-toolbar');

    var selection = window.getSelection();
    if (!selection.rangeCount) return;
    var node = selection.getRangeAt(0).startContainer;
    
    var $block = $(node).closest('p, div, h1, h2, h3, h4, h5, h6, td, li');

    var currentAlign = 'left'; 
    if ($block.length && !$block.hasClass('note-editable')) {
        // Lấy giá trị CSS
        currentAlign = $block.css('text-align') || 'left';
        
        // Quét thêm trực tiếp vào HTML style để chống lỗi trình duyệt
        var styleAttr = $block.attr('style') || '';
        if (styleAttr.indexOf('text-align: center') !== -1) currentAlign = 'center';
        else if (styleAttr.indexOf('text-align: right') !== -1) currentAlign = 'right';
        else if (styleAttr.indexOf('text-align: justify') !== -1) currentAlign = 'justify';
        else if (styleAttr.indexOf('text-align: left') !== -1) currentAlign = 'left';
    }
    
    // Xử lý các từ khóa lạ của trình duyệt (Chrome/Safari)
    if (currentAlign === 'start') currentAlign = 'left';
    if (currentAlign === 'end') currentAlign = 'right';
    if (currentAlign === '-webkit-center') currentAlign = 'center';

    // Đổi màu nút
    $toolbar.find('.custom-align-btn').removeClass('active-align');

    if (currentAlign === 'center') {
        $toolbar.find('.align-btn-center').addClass('active-align');
    } else if (currentAlign === 'right') {
        $toolbar.find('.align-btn-right').addClass('active-align');
    } else if (currentAlign === 'justify') {
        $toolbar.find('.align-btn-justify').addClass('active-align');
    } else {
        $toolbar.find('.align-btn-left').addClass('active-align');
    }
});
</script>
@endpush

@endsection
