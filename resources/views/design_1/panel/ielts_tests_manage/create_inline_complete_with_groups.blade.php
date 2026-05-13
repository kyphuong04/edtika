@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
.test-creator-enhanced {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
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

.group-item {
    background: #f9fafb;
    border-left: 4px solid #3b82f6;
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
    margin-bottom: 8px;
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
    color: #4c51bf;
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
    border: 2px dashed #3b82f6;
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
    border-color: #3b82f6;
    background: #f0f9ff;
}

.file-upload-area input[type="file"] {
    display: none;
}

.file-preview {
    display: inline-block;
    background: #e0e7ff;
    color: #4c51bf;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    margin-top: 8px;
}

.questions-list {
    background: #f9fafb;
    border-radius: 8px;
    padding: 12px;
    margin-top: 12px;
    max-height: 200px;
    overflow-y: auto;
}

.question-badge {
    display: inline-block;
    background: #f3e8ff;
    color: #7c3aed;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 11px;
    margin-top: 4px;
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
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.btn-add-part:hover {
    background: #1d4ed8;
}

.completeness-check {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 12px 16px;
    border-radius: 4px;
    margin-bottom: 16px;
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
    .group-item .group-actions {
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
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 8px;
}
.question-type-badge {
    display: inline-block;
    background: #e0e7ff;
    color: #3730a3;
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
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="section-title">
            <i class="fas fa-plus-circle mr-10"></i>
            Create IELTS Test with Parts and Question Groups
        </h1>
        <a href="{{ route('panel.my_ielts_tests.create') }}" class="btn btn-sm btn-gray">
            <i class="fas fa-arrow-left mr-5"></i>Back
        </a>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-5"></i>
        <strong>Hierarchical Structure:</strong> Test → Sections → Parts → Question Groups → Questions
    </div>

    <form id="testForm" action="{{ route('panel.my_ielts_tests.store_with_groups') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Test Information --}}
        <div class="test-info-card">
            <h3 class="font-16 font-weight-bold mb-15">
                <i class="fas fa-info-circle mr-8"></i>Test Information
            </h3>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Test Type *</label>
                        <select id="testTypeSelect" name="type" class="form-control @error('type') is-invalid @enderror" required onchange="updateTestRequirements()">
                            <option value="">-- Select Type --</option>
                            <option value="mock" {{ old('type') === 'mock' ? 'selected' : '' }}>Mock Test (All 4 Skills)</option>
                            <option value="practice" {{ old('type') === 'practice' ? 'selected' : '' }}>Practice Test (1 Skill)</option>
                        </select>
                        @error('type')
                            <div class="text-danger font-size-sm mt-5">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Test Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Full Mock Test - March 2026">
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
                            <option value="academic" {{ old('format') === 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="general" {{ old('format') === 'general' ? 'selected' : '' }}>General Training</option>
                        </select>
                        @error('format')
                            <div class="text-danger font-size-sm mt-5">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Brief description...">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Difficulty Level</label>
                        <select name="difficulty_level" class="form-control">
                            <option value="intermediate" selected>Intermediate</option>
                            <option value="beginner">Beginner</option>
                            <option value="advanced">Advanced</option>
                            <option value="mixed">Mixed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Target Band Min</label>
                        <input type="number" name="target_band_min" class="form-control" min="0" max="9" step="0.5" value="{{ old('target_band_min', 5.0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Target Band Max</label>
                        <input type="number" name="target_band_max" class="form-control" min="0" max="9" step="0.5" value="{{ old('target_band_max', 8.0) }}">
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
            </ul>
        </div>

        {{-- Sections with Question Groups --}}
        <div id="sectionsContainer">
            {{-- Sections will be added dynamically --}}
        </div>

        {{-- Hidden field for question groups data --}}
        <input type="hidden" id="questionGroupsData" name="question_groups_data" value="">
        <div id="uploadedFilesContainer" style="display:none;"></div>

        {{-- Action Buttons --}}
        <div class="mt-30 d-flex gap-10">
            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                <i class="fas fa-check-circle mr-5"></i>Submit Test for Approval
            </button>
            <a href="{{ route('panel.my_ielts_tests.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times mr-5"></i>Cancel
            </a>
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
                <button type="button" class="btn btn-primary btn-sm" onclick="toggleAddPartForm(this)">
                    <i class="fas fa-plus mr-5"></i>Add Part
                </button>
            </div>

            <div class="parts-list"></div>

            {{-- Add Part Form --}}
            <div class="add-part-form hidden">
                <h5 class="mb-15"><i class="fas fa-plus-circle mr-8"></i>Add New Part</h5>

                <div class="form-row full">
                    <div class="form-group">
                        <label class="input-label">Part Title / Prompt *</label>
                        <textarea class="form-control part-title-input" rows="2" placeholder="e.g. Part 1 - Social Conversation"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="input-label">Part Instructions</label>
                        <textarea class="form-control part-instructions-input" rows="2" placeholder="Optional instructions for this part..."></textarea>
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
                        <label class="input-label">Audio File (MP3, WAV)</label>
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
                    <div class="form-group">
                        <label class="input-label">Part Description</label>
                        <textarea class="form-control part-passage" rows="3" placeholder="Enter reading passage, transcript, or notes for this part..."></textarea>
                    </div>
                </div>

                <div class="mt-12">
                    <button type="button" class="btn btn-primary btn-sm" onclick="addPart(this)">
                        <i class="fas fa-plus mr-5"></i>Create Part
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAddPartForm(this)">
                        <i class="fas fa-times mr-5"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </template>
</section>

@push('scripts_bottom')
<script>
const SECTIONS_CONFIG = {
    listening: { skill: 'listening', title: 'Listening', icon: '🔊', mockParts: 4, mockQuestions: 40 },
    reading: { skill: 'reading', title: 'Reading', icon: '📖', mockParts: 3, mockQuestions: 40 },
    writing: { skill: 'writing', title: 'Writing', icon: '✍️', mockParts: 2, mockQuestions: 2 },
    speaking: { skill: 'speaking', title: 'Speaking', icon: '🎤', mockParts: 3, mockQuestions: 3 }
};

let currentTestType = null;
let uploadSequence = 0;

let testData = {
    sections: {
        listening: { parts: [] },
        reading: { parts: [] },
        writing: { parts: [] },
        speaking: { parts: [] }
    }
};

document.addEventListener('DOMContentLoaded', function() {
    setupFormValidation();
    setupFilePreviews();
});

function setupFilePreviews() {
    document.addEventListener('change', function(event) {
        const input = event.target;
        if (!input || input.type !== 'file') {
            return;
        }

        let preview = null;
        if (input.classList.contains('group-audio-file')) {
            preview = input.closest('.col-md-4')?.querySelector('.group-audio-preview');
        } else if (input.classList.contains('group-image-file')) {
            preview = input.closest('.col-md-4')?.querySelector('.group-image-preview');
        } else if (input.classList.contains('group-video-file')) {
            preview = input.closest('.col-md-4')?.querySelector('.group-video-preview');
        }

        if (!preview) {
            return;
        }

        if (input.files && input.files[0]) {
            preview.textContent = input.files[0].name;
            preview.style.display = 'inline-block';
        } else {
            preview.textContent = '';
            preview.style.display = 'none';
        }
    });
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
                reqElement.textContent = `(${SECTIONS_CONFIG[skill].mockParts} parts, ${SECTIONS_CONFIG[skill].mockQuestions} questions)`;
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

        container.appendChild(section);
    });
}

function toggleAddPartForm(button) {
    const form = button.closest('.section-container').querySelector('.add-part-form');
    form.classList.toggle('hidden');
}

function toggleAddGroupForm(button) {
    const form = button.closest('.part-item').querySelector('.group-inline-form');
    form.classList.toggle('hidden');
}

function addPart(button) {
    const section = button.closest('.section-container');
    const skill = section.getAttribute('data-skill');
    const form = section.querySelector('.add-part-form');

    const title = form.querySelector('.part-title-input').value.trim();
    const instructions = form.querySelector('.part-instructions-input').value.trim();
    const passage = form.querySelector('.part-passage').value.trim();

    const audioFile = form.querySelector('.group-audio-file').files[0];
    const imageFile = form.querySelector('.group-image-file').files[0];
    const videoFile = form.querySelector('.group-video-file').files[0];

    if (!title) {
        alert('Please fill in Part Title');
        return;
    }

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

    displayPart(section, part, audioFile, imageFile, videoFile);

    form.querySelector('.part-title-input').value = '';
    form.querySelector('.part-instructions-input').value = '';
    form.querySelector('.part-passage').value = '';
    form.querySelectorAll('input[type="file"]:not(.preserved-upload-input)').forEach(input => input.value = '');
    form.querySelectorAll('.file-preview').forEach(preview => preview.style.display = 'none');

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
    const title = form.querySelector('.group-title-input').value.trim();
    const questionType = form.querySelector('.group-type-select').value;
    const maxWords = form.querySelector('.group-max-words').value;
    const targetBand = form.querySelector('.group-target-band').value;
    const passage = form.querySelector('.group-passage').value;

    if (!title || !questionType) {
        alert('Please fill in Group Title and Question Type');
        return;
    }

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
        questions: [],
        file_input_names: fileInputNames,
        files: {}
    };

    part.groups.push(group);

    displayQuestionGroup(partItem, part, group);

    form.querySelector('.group-title-input').value = '';
    form.querySelector('.group-type-select').value = 'multiple_choice_single';
    form.querySelector('.group-max-words').value = '';
    form.querySelector('.group-target-band').value = '';
    form.querySelector('.group-passage').value = '';
    form.querySelectorAll('input[type="file"]:not(.preserved-upload-input)').forEach(input => input.value = '');
    form.querySelectorAll('.file-preview').forEach(preview => preview.style.display = 'none');

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

    partDiv.innerHTML = `
        <div class="part-title">${escapeHtml(part.title)}</div>
        <div class="part-meta">
            <span><i class="fas fa-align-left"></i> Part</span>
            <span class="group-total"><i class="fas fa-layer-group"></i> 0 groups</span>
            <span class="question-total"><i class="fas fa-list-ol"></i> 0 questions</span>
        </div>
        ${mediaHTML}
        ${part.instructions ? `<div class="text-muted mb-8"><small>${escapeHtml(part.instructions)}</small></div>` : ''}
        ${part.passage ? `<div class="mb-8"><small class="text-muted">${escapeHtml(part.passage)}</small></div>` : ''}
        <div class="groups-list"></div>
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
                    <textarea class="form-control group-passage" rows="3" placeholder="Enter reading passage, instructions, or transcript..."></textarea>
                </div>
            </div>
            <div class="mt-8">
                <button type="button" class="btn btn-sm btn-primary" onclick="addQuestionGroup(this)">
                    <i class="fas fa-plus mr-5"></i>Create Group & Add Questions
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="toggleAddGroupForm(this)">
                    <i class="fas fa-times mr-5"></i>Cancel
                </button>
            </div>
        </div>
        <div class="part-actions">
            <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleAddGroupForm(this)">
                <i class="fas fa-plus"></i> Group
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePart(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    partsList.appendChild(partDiv);

    updatePartStats(partDiv, part);
}

function displayQuestionGroup(partItem, part, group) {
    const groupsList = partItem.querySelector('.groups-list');

    const groupDiv = document.createElement('div');
    groupDiv.className = 'group-item';
    groupDiv.setAttribute('data-group-id', group.id);

    groupDiv.innerHTML = `
        <div class="group-title">${escapeHtml(group.title)}</div>
        <div class="group-meta">
            <span><i class="fas fa-tags"></i> ${escapeHtml(getQuestionTypeLabel(group.question_type))}</span>
            <span class="question-total"><i class="fas fa-list-ol"></i> 0 questions</span>
            ${group.max_words ? `<span><i class="fas fa-font"></i> ${escapeHtml(String(group.max_words))} words max</span>` : ''}
            ${group.target_band ? `<span><i class="fas fa-bullseye"></i> Band ${escapeHtml(String(group.target_band))}</span>` : ''}
        </div>
        ${group.passage ? `<div class="mb-8 text-muted"><small>${escapeHtml(group.passage)}</small></div>` : ''}
        <div class="questions-list" style="display:none;"></div>
        <div class="question-inline-form hidden mt-12 p-12" style="background:#fff;border:1px solid #dbeafe;border-radius:8px;">
            ${getQuestionFormHTML(group.question_type)}
            <div class="mt-8">
                <button type="button" class="btn btn-sm btn-primary" onclick="saveQuestionToGroup(this)">
                    <i class="fas fa-check mr-5"></i>Save Question
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="toggleQuestionForm(this)">
                    <i class="fas fa-times mr-5"></i>Cancel
                </button>
            </div>
        </div>
        <div class="group-actions">
            <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleQuestionForm(this)">
                <i class="fas fa-plus"></i> Q
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeGroup(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    groupsList.appendChild(groupDiv);

    renderQuestionsList(groupDiv, group);
    updatePartStats(partItem, part);
}

function toggleQuestionForm(button) {
    const groupItem = button.closest('.group-item');
    const form = groupItem.querySelector('.question-inline-form');
    if (form) {
        form.classList.toggle('hidden');
    }
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
    const textInput = form.querySelector('.question-text-input');
    const text = textInput ? textInput.value.trim() : '';
    const points = parseInt(form.querySelector('.question-points-input').value, 10) || 1;

    if (!text) {
        alert('Question text is required.');
        return;
    }

    let questionData = { id: Date.now(), type: qType, text, points };

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
                if (row.querySelector('input[type="checkbox"]').checked) {
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

    } else {
        const answerInput = form.querySelector('.question-answer-input');
        questionData.correctAnswer = answerInput ? answerInput.value.trim() || null : null;
    }

    group.questions.push(questionData);

    if (textInput) textInput.value = '';
    const answerInput = form.querySelector('.question-answer-input');
    if (answerInput) answerInput.value = '';
    const answerSelect = form.querySelector('.question-answer-select');
    if (answerSelect) answerSelect.selectedIndex = 0;
    const pointsInput = form.querySelector('.question-points-input');
    if (pointsInput) pointsInput.value = '1';
    form.querySelectorAll('.mc-option-row input[type="text"]').forEach(i => i.value = '');
    form.querySelectorAll('.mc-option-row input[type="radio"], .mc-option-row input[type="checkbox"]').forEach(i => i.checked = false);
    form.classList.add('hidden');

    renderQuestionsList(groupItem, group);
    updatePartStats(partItem, part);
    updateSectionStats(section);
    updateCompletenessStatus();
}

function getGroupSlotCount(group) {
    return (group.questions || []).reduce((sum, q) => sum + (q.slotCount || 1), 0);
}

function renderQuestionsList(groupItem, group) {
    const list = groupItem.querySelector('.questions-list');
    const totalSpan = groupItem.querySelector('.question-total');
    const totalSlots = getGroupSlotCount(group);
    const totalQuestions = group.questions.length;

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
    let slotIndex = 1;
    list.innerHTML = group.questions.map((question) => {
        const slotCount = question.slotCount || 1;
        const slotLabel = slotCount > 1
            ? `Q${slotIndex}–Q${slotIndex + slotCount - 1}`
            : `Q${slotIndex}`;
        slotIndex += slotCount;

        let detailHTML = '';
        const qType = question.type || group.question_type || 'short_answer';

        if (qType === 'multiple_choice_single' || qType === 'multiple_choice_multiple') {
            const opts = (question.options || []).map((o, i) => {
                const isCorrect = qType === 'multiple_choice_multiple'
                    ? (question.correctAnswers || []).includes(o)
                    : question.correctAnswer === o;
                return `<span style="margin-right:6px;color:${isCorrect ? '#16a34a' : '#374151'};font-weight:${isCorrect ? '700' : '400'};">${String.fromCharCode(65 + i)}. ${escapeHtml(o)}${isCorrect ? ' ✓' : ''}</span>`;
            }).join('');
            detailHTML = `<div style="font-size:12px;margin-top:4px;">${opts}</div>`;
        } else if (question.correctAnswer) {
            detailHTML = `<span class="question-answer-badge">✓ ${escapeHtml(question.correctAnswer)}</span>`;
        }

        return `<div class="mb-8">
            <span class="question-badge">${slotLabel}</span>
            ${escapeHtml(question.text)}
            ${detailHTML}
        </div>`;
    }).join('');
}

function getQuestionTypeLabel(type) {
    const labels = {
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

function getQuestionFormHTML(questionType) {
    const isMCSingle = questionType === 'multiple_choice_single';
    const isMCMultiple = questionType === 'multiple_choice_multiple';
    const isTFNG = questionType === 'true_false_not_given';
    const isYNNG = questionType === 'yes_no_not_given';
    const isMatching = ['matching_headings', 'matching_information', 'matching_features', 'matching_sentence_endings'].includes(questionType);
    const isCompletion = ['sentence_completion', 'summary_completion', 'note_completion', 'table_completion', 'diagram_labeling'].includes(questionType);

    let html = `<div class="mb-8"><span class="question-type-badge">${getQuestionTypeLabel(questionType)}</span></div>`;

    if (isMCSingle || isMCMultiple) {
        const inputType = isMCSingle ? 'radio' : 'checkbox';
        const groupName = 'mc_correct_' + Date.now();
        const hint = isMCSingle ? 'Select the one correct answer.' : 'Check all correct answers.';
        html += `<div class="form-row">
            <div class="form-group">
                <label class="input-label">Question Text *</label>
                <textarea class="form-control question-text-input" rows="2" placeholder="Enter the question"></textarea>
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
                <input type="number" class="form-control question-points-input" min="1" step="1" value="1">
            </div>
        </div>`;

    } else if (isTFNG || isYNNG) {
        const opts = isTFNG
            ? ['True', 'False', 'Not Given']
            : ['Yes', 'No', 'Not Given'];
        const optionsHTML = opts.map(o => `<option value="${o}">${o}</option>`).join('');
        html += `<div class="form-row">
            <div class="form-group">
                <label class="input-label">Statement *</label>
                <textarea class="form-control question-text-input" rows="2" placeholder="Enter the statement to evaluate"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Correct Answer</label>
                <select class="form-control question-answer-select">${optionsHTML}</select>
            </div>
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="1" step="1" value="1">
            </div>
        </div>`;

    } else if (isMatching) {
        html += `<div class="form-row">
            <div class="form-group">
                <label class="input-label">Statement / Item *</label>
                <textarea class="form-control question-text-input" rows="2" placeholder="Enter the statement or item to match"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Correct Match (Answer)</label>
                <input type="text" class="form-control question-answer-input" placeholder="e.g. Paragraph A, Section 2, Feature X">
            </div>
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="1" step="1" value="1">
            </div>
        </div>`;

    } else if (isCompletion) {
        const placeholder = questionType === 'table_completion'
            ? 'Enter the cell label or row/column description (use ___ for blank)'
            : 'Enter the sentence/text (use ___ to indicate the blank)';
        html += `<div class="form-row">
            <div class="form-group">
                <label class="input-label">Question / Sentence * <small class="text-muted">(use ___ for blank)</small></label>
                <textarea class="form-control question-text-input" rows="2" placeholder="${placeholder}"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Correct Answer(s)</label>
                <input type="text" class="form-control question-answer-input" placeholder="Enter the word(s) that fill the blank">
            </div>
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="1" step="1" value="1">
            </div>
        </div>`;

    } else {
        html += `<div class="form-row">
            <div class="form-group">
                <label class="input-label">Question *</label>
                <textarea class="form-control question-text-input" rows="2" placeholder="Enter the question"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Model Answer / Keywords</label>
                <input type="text" class="form-control question-answer-input" placeholder="Enter model answer or key words">
            </div>
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="1" step="1" value="1">
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
        const isComplete = isMock ? partCount >= config.mockParts : partCount > 0;
        const hasAny = partCount > 0;
        const statusSpan = checklist.querySelector(`.status-${skill}`);

        if (statusSpan) {
            statusSpan.textContent = isComplete ? '✓' : (hasAny ? '◐' : '○');
            statusSpan.style.color = isComplete ? '#10b981' : (hasAny ? '#f59e0b' : '#d1d5db');
        }

        if (hasAny) hasAtLeastOneSection = true;
        if (isMock && !isComplete) allMockComplete = false;
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
                if (parts.length < config.mockParts) {
                    issues.push(`${config.title}: need ${config.mockParts} parts`);
                }

                if (parts.length === 0) {
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

        document.getElementById('questionGroupsData').value = JSON.stringify(testData);
        document.getElementById('testForm').submit();
    });
}
</script>
@endpush

@endsection
