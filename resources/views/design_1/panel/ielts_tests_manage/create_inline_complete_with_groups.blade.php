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
    document.addEventListener('input', function(event) {
        const target = event.target;
        if (!target || !target.classList) {
            return;
        }

        if (target.classList.contains('question-text-input')) {
            const form = target.closest('.question-inline-form');
            if (form && form.querySelector('.note-completion-answers')) {
                renderNoteCompletionAnswerInputs(form);
            }
        }

        if (target.classList.contains('tc-cell-text') || target.classList.contains('tc-cell-answer') || target.classList.contains('tc-col-title') || target.classList.contains('tc-row-title')) {
            const form = target.closest('.question-inline-form');
            if (!form) {
                return;
            }

            if (target.classList.contains('tc-cell-text')) {
                const td = target.closest('td');
                const answerWrap = td ? td.querySelector('.tc-answer-wrap') : null;
                const answerList = td ? td.querySelector('.tc-answer-list') : null;
                if (answerWrap) {
                    const hasBlank = target.value.includes('___');
                    answerWrap.style.display = hasBlank ? 'block' : 'none';
                    if (!hasBlank) {
                        if (answerList) {
                            answerList.innerHTML = makeTableCellAnswerListHTML();
                        }
                    }
                }
            }

            updateTableCompletionState(form);
        }
    });

    document.addEventListener('change', function(event) {
        const target = event.target;
        if (!target || !target.classList || !target.classList.contains('question-type-select')) {
            return;
        }

        const form = target.closest('.question-inline-form');
        if (!form || !form.querySelector('.note-completion-answers')) {
            return;
        }

        renderNoteCompletionAnswerInputs(form);
    });
});

function setupFilePreviews() {
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
            preview = input.closest('.col-md-6')?.querySelector('.group-image-preview');
        } else if (input.classList.contains('group-video-file')) {
            preview = input.closest('.col-md-6')?.querySelector('.group-video-preview');
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

        const sectionMediaBlock = section.querySelector('.section-media-block');
        if (sectionMediaBlock) {
            sectionMediaBlock.classList.toggle('hidden', skill !== 'listening');
        }

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

    const audioInput = form.querySelector('.group-audio-file');
    const audioFile = audioInput && audioInput.files.length ? audioInput.files[0] : null;
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
    const titleEl = form ? form.querySelector('.group-title-input') : null;
    const title = titleEl ? titleEl.value.trim() : '';
    const qTypeEl = form ? form.querySelector('.group-type-select') : null;
    const questionType = qTypeEl ? (qTypeEl.value || 'short_answer') : 'short_answer';
    const maxWordsEl = form ? form.querySelector('.group-max-words') : null;
    const maxWords = maxWordsEl ? maxWordsEl.value : '';
    const targetBandEl = form ? form.querySelector('.group-target-band') : null;
    const targetBand = targetBandEl ? targetBandEl.value : '';
    const passageEl = form ? form.querySelector('.group-passage') : null;
    const passage = passageEl ? passageEl.value : '';
    const groupTaskImageUrlEl = form.querySelector('.group-task-image-url');
    const groupTaskImageUrl = groupTaskImageUrlEl ? (groupTaskImageUrlEl.value || '').trim() : null;

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
        task_image: groupTaskImageUrl || null,
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
    const gImg = form.querySelector('.group-task-image-url'); if (gImg) gImg.value = '';
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
                        <textarea class="form-control group-passage" rows="3" placeholder="Optional instructions or passage for this task..."></textarea>
                    </div>
                </div>
                <div class="mt-12">
                    <button type="button" class="btn btn-primary btn-sm" onclick="addQuestionGroup(this)">
                        <i class="fas fa-plus mr-5"></i>Create Group
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAddGroupForm(this)">
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
            </div>`;
        }

        const groupsListEl = partDiv.querySelector('.groups-list');
        if (groupsListEl) {
            groupsListEl.insertAdjacentHTML('afterend', groupInlineFormHTML);
        }
    } catch (e) {
        console.error('Error inserting group inline form', e);
    }

    updatePartStats(partDiv, part);
}

function displayQuestionGroup(partItem, part, group) {
    const groupsList = partItem.querySelector('.groups-list');

    const groupDiv = document.createElement('div');
    groupDiv.className = 'group-item';
    groupDiv.setAttribute('data-group-id', group.id);
    groupDiv.setAttribute('data-group-task-image', group.task_image || '');

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
            <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleQuestionForm(this)" title="Add new question">
                <i class="fas fa-plus mr-2"></i> Question
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
        const wasHidden = form.classList.contains('hidden');
        form.classList.toggle('hidden');

        // Reset form when opening
        if (wasHidden) {
            resetQuestionForm(form);
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

function resetQuestionForm(form) {
    const textInput = form.querySelector('.question-text-input');
    if (textInput) textInput.value = '';

    const explanationInput = form.querySelector('.question-explanation-input');
    if (explanationInput) explanationInput.value = '';

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

    const noteSummary = form.querySelector('.note-completion-summary');
    if (noteSummary) noteSummary.innerHTML = 'Type the note text with <code>___</code> for each blank.';

    const tcWrap = form.querySelector('.tc-builder-wrap');
    if (tcWrap) tcWrap.classList.add('hidden');

    form.querySelectorAll('.tc-col-title, .tc-row-title, .tc-cell-text, .tc-cell-answer').forEach(i => i.value = '');
    form.querySelectorAll('.mc-option-row input[type="text"]').forEach(i => i.value = '');
    form.querySelectorAll('.mc-option-row input[type="radio"], .mc-option-row input[type="checkbox"]').forEach(i => i.checked = false);

    // Reset to default 2 options for multiple choice
    const optionsList = form.querySelector('.mc-options-list');
    if (optionsList) {
        optionsList.innerHTML = makeMCOptionRow('radio', 0, optionsList.getAttribute('data-group-name')) +
                                makeMCOptionRow('radio', 1, optionsList.getAttribute('data-group-name'));
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

    // Load question data into form
    const textInput = form.querySelector('.question-text-input');
    if (textInput) textInput.value = question.text || '';

    const explanationInput = form.querySelector('.question-explanation-input');
    if (explanationInput) explanationInput.value = question.explanation || '';

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

        optionsList.innerHTML = (question.options || []).map((option, i) => {
            const isCorrect = qType === 'multiple_choice_multiple'
                ? (question.correctAnswers || []).includes(option)
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
    } else if (qType === 'note_completion') {
        renderNoteCompletionAnswerInputs(form, normalizeNoteCompletionAnswers(question.correctAnswer));
        bindNoteCompletionLivePreview(form);
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
                let headHtml = '<tr><th style="min-width:160px;">Row / Column</th>';
                headers.forEach((h, i) => {
                    headHtml += `<th><input type="text" class="tc-col-title" data-col="${i}" placeholder="Column ${i + 1}" value="${escapeHtml(h)}"></th>`;
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
                    const rowLabel = (row.row_label !== undefined) ? row.row_label : (typeof row === 'object' && !Array.isArray(row) ? '' : '');
                    
                    bodyHtml += `<tr><th><input type="text" class="tc-row-title" data-row="${r}" placeholder="Row ${r + 1}" value="${escapeHtml(rowLabel)}"></th>`;
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
                    
                    // Show answer wrap if cell has ___
                    if (cellValue && cellValue.includes('___')) {
                        const answerWrap = td.querySelector('.tc-answer-wrap');
                        const answerList = td.querySelector('.tc-answer-list');
                        
                        if (answerWrap) {
                            answerWrap.style.display = 'block';
                            answerWrap.classList.remove('hidden');
                        }
                        
                        // Populate answer inputs
                        const key = `${rowIdx}-${colIdx}`;
                        const cellAnswers = existingAnswers[key] || [];
                        console.log(`DEBUG: Cell has ___, populating answers for key=${key}:`, cellAnswers);
                        
                        if (answerList && cellAnswers.length > 0) {
                            answerList.innerHTML = cellAnswers.map((answer, i) => 
                                makeTableCellAnswerRowHTML(answer, i > 0)
                            ).join('');
                            console.log(`DEBUG: Populated ${cellAnswers.length} answers`);
                        }
                    }
                });
            }
            tcWrap.classList.remove('hidden');
        }
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

    renderQuestionsList(groupItem, group);
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
    const textInput = form.querySelector('.question-text-input');
    const text = textInput ? textInput.value.trim() : '';
    const explanationInput = form.querySelector('.question-explanation-input');
    const explanation = explanationInput ? explanationInput.value.trim() : '';
    const pointsValue = parseFloat(form.querySelector('.question-points-input').value);
    const points = Number.isFinite(pointsValue) ? pointsValue : 0;

    if (!text) {
        alert('Question text is required.');
        return;
    }

    let questionData = { id: Date.now(), type: qType, text, explanation: explanation || null, points };

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

    } else if (qType === 'note_completion') {
        const noteAnswers = collectNoteCompletionAnswers(form);
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
        questionData.correctAnswer = JSON.stringify(noteAnswers);
        questionData.slotCount = noteAnswers.length;

    } else if (qType === 'table_completion') {
        updateTableCompletionState(form);
        const structureInput = form.querySelector('.tc-table-structure-json');
        const answersInput = form.querySelector('.tc-table-answers-json');
        const tableStructure = structureInput && structureInput.value ? JSON.parse(structureInput.value) : { headers: [], rows: [] };
        const tableAnswers = answersInput && answersInput.value ? JSON.parse(answersInput.value) : { answers: [] };

        if (!text) {
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
            if (qImg) {
                questionData.question_data = questionData.question_data || {};
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
            if (qImg) {
                questionData.question_data = questionData.question_data || {};
                questionData.question_data.task_image = qImg;
            }
        } catch (e) {
            // ignore
        }

        group.questions.push(questionData);
    }

    // Reset form
    resetQuestionForm(form);
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
    list.innerHTML = group.questions.map((question, qIndex) => {
        const slotCount = question.slotCount || 1;
        const slotLabel = slotCount > 1
            ? `Q${slotIndex}–Q${slotIndex + slotCount - 1}`
            : `Q${slotIndex}`;
        slotIndex += slotCount;

        let detailHTML = '';
        const qType = question.type || group.question_type || 'short_answer';

            if (qType === 'table_completion' && question.table_structure) {
                const blankCount = (question.table_structure.answers || []).length;
                const colCount = (question.table_structure.headers || []).length;
                detailHTML = `<div style="font-size:12px;margin-top:4px;">
                    <span class="question-answer-badge">Table: ${colCount} cols, ${blankCount} blanks</span>
                </div>`;
            } else if (qType === 'note_completion') {
                const blankCount = (Array.isArray(question.correctAnswers) ? question.correctAnswers.length : normalizeNoteCompletionAnswers(question.correctAnswer).length) || (question.slotCount || 1);
                detailHTML = `<div style="font-size:12px;margin-top:4px;">
                    <span class="question-answer-badge">Note: ${blankCount} blanks</span>
                </div>`;
            } else if (qType === 'multiple_choice_single' || qType === 'multiple_choice_multiple') {
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

        return `<div class="mb-12" style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px;position:relative;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                <div style="flex:1;">
                    <span class="question-badge">${slotLabel}</span>
                    <div style="margin-top:8px;font-weight:500;color:#1f2937;">${escapeHtml(question.text)}</div>
                    ${question.explanation ? `<div style="margin-top:6px;font-size:12px;color:#0f766e;font-weight:600;">Answer Help: ${escapeHtml(question.explanation)}</div>` : ''}
                    ${detailHTML}
                </div>
                <div style="display:flex;gap:6px;flex-shrink:0;">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editQuestion(this, ${qIndex})" title="Edit question">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteQuestion(this, ${qIndex})" title="Delete question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>`;
    }).join('');
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

function countNoteCompletionBlanks(text) {
    const matches = String(text || '').match(/_{2,}/g);
    return matches ? matches.length : 0;
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
        return rawAnswer.map(value => String(value).trim()).filter(Boolean);
    }

    if (rawAnswer && typeof rawAnswer === 'object') {
        if (Array.isArray(rawAnswer.answers)) {
            return rawAnswer.answers.map(value => String(value).trim()).filter(Boolean);
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
                return parsed.map(value => String(value).trim()).filter(Boolean);
            }
        } catch (error) {
            // fall back to plain text handling
        }

        return text.includes('\n')
            ? text.split(/\r?\n/).map(value => value.trim()).filter(Boolean)
            : [text];
    }

    if (rawAnswer === null || rawAnswer === undefined) {
        return [];
    }

    const text = String(rawAnswer).trim();
    return text ? [text] : [];
}

function makeNoteCompletionAnswerRowHTML(value = '', index = 0) {
    return `<div class="note-answer-item d-flex align-items-center mb-2" style="gap:8px;width:100%;">
        <span style="width:72px;flex:0 0 auto;font-size:13px;font-weight:600;color:#6b7280;">Blank ${index + 1}</span>
        <input type="text" class="form-control form-control-sm note-completion-answer-input" data-blank-index="${index}" placeholder="Answer for blank ${index + 1}" value="${escapeHtml(value)}" style="flex:1; min-width:0;">
    </div>`;
}

function renderNoteCompletionAnswerInputs(form, values = []) {
    const textInput = form.querySelector('.question-text-input');
    const summary = form.querySelector('.note-completion-summary');
    const container = form.querySelector('.note-completion-answers');

    if (!summary || !container) {
        return;
    }

    const blankCount = countNoteCompletionBlanks(textInput ? textInput.value : '');
    summary.innerHTML = blankCount > 0
        ? `Detected <strong>${blankCount}</strong> blank${blankCount > 1 ? 's' : ''}. Enter one answer per blank in order.`
        : 'Type the note text with <code>___</code> for each blank.';

    if (blankCount === 0) {
        container.innerHTML = '';
        return;
    }

    const normalizedValues = Array.isArray(values) ? values : normalizeNoteCompletionAnswers(values);
    container.innerHTML = Array.from({ length: blankCount }, (_, index) => makeNoteCompletionAnswerRowHTML(normalizedValues[index] || '', index)).join('');
}

function collectNoteCompletionAnswers(form) {
    if (!form) {
        return [];
    }

    return Array.from(form.querySelectorAll('.note-completion-answer-input'))
        .map(input => input.value.trim())
        .filter(Boolean);
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
        <input type="text" class="form-control form-control-sm tc-cell-answer" placeholder="Đáp án" value="${escapeHtml(value)}" style="flex:1; min-width:0;">
        <button type="button" class="btn btn-sm tc-remove-answer-btn" onclick="removeTableCellAnswer(this)" title="Xóa đáp án" aria-label="Xóa đáp án" style="flex:0 0 auto; border:1px solid #ef4444; color:#ef4444; background:#fff; width:26px; height:26px; padding:0; display:inline-flex; align-items:center; justify-content:center; border-radius:999px; line-height:1; font-size:18px; font-weight:700;">
            ×
        </button>
    </div>`;
}

function makeTableCellAnswerListHTML(existingAnswers = []) {
    const answers = existingAnswers.length ? existingAnswers : [''];
    return answers.map((answer, index) => makeTableCellAnswerRowHTML(answer, index > 0)).join('');
}

function makeTableCellEditor(rowIndex, colIndex, existingAnswers = []) {
    return `<td data-row="${rowIndex}" data-col="${colIndex}">
        <textarea class="tc-cell-text" rows="3" placeholder="Nhập nội dung ô. Dùng ___ cho blank"></textarea>
        <div class="tc-answer-wrap hidden" style="display:none; margin-top:6px;">
            <div class="tc-answer-list">
                ${makeTableCellAnswerListHTML(existingAnswers)}
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
    const rows = Math.max(1, parseInt(rowsInput?.value, 10) || 3);
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

    let headHtml = '<tr><th style="min-width:160px;">Row / Column</th>';
    for (let c = 0; c < cols; c++) {
        headHtml += `<th><input type="text" class="tc-col-title" data-col="${c}" placeholder="Cột ${c + 1}"></th>`;
    }
    headHtml += '</tr>';
    head.innerHTML = headHtml;

    let bodyHtml = '';
    for (let r = 0; r < rows; r++) {
        bodyHtml += `<tr><th><input type="text" class="tc-row-title" data-row="${r}" placeholder="Hàng ${r + 1}"></th>`;
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
        const rowLabel = tr.querySelector('.tc-row-title')?.value.trim() || '';
        const cells = [];
        tr.querySelectorAll('td').forEach(td => {
            const textarea = td.querySelector('.tc-cell-text');
            const answerWrap = td.querySelector('.tc-answer-wrap');
            const answerList = td.querySelector('.tc-answer-list');
            const answerInputs = td.querySelectorAll('.tc-cell-answer');
            const value = (textarea?.value || '').trim();
            cells.push(value);

            if (value.includes('___')) {
                if (answerWrap) {
                    answerWrap.style.display = 'block';
                }

                if (answerList && !answerList.querySelector('.tc-answer-item')) {
                    answerList.innerHTML = makeTableCellAnswerListHTML();
                }

                const cellAnswers = Array.from(answerInputs)
                    .map(input => (input.value || '').trim())
                    .filter(Boolean);

                if (cellAnswers.length) {
                    answers.push({ row: rowIndex, col: parseInt(td.dataset.col, 10), answers: cellAnswers });
                }
            } else {
                if (answerWrap) {
                    answerWrap.style.display = 'none';
                }

                if (answerList) {
                    answerList.innerHTML = makeTableCellAnswerListHTML();
                }
            }
        });
        // Store row as object with cells and row_label to preserve data in JSON serialization
        rows.push({
            cells: cells,
            row_label: rowLabel
        });
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

    let html = '';

    if (questionType === 'essay') {
        html += `<div class="form-row">
            <div class="form-group">
                <label class="input-label">Prompt / Task *</label>
                <textarea class="form-control question-text-input" rows="3" placeholder="Enter the essay prompt or task"></textarea>
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
                <textarea class="form-control question-explanation-input" rows="3" placeholder="Provide a model answer or guidance for review..."></textarea>
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
        html += `<div class="form-row">
            <div class="form-group">
                <label class="input-label">Question Text *</label>
                <textarea class="form-control question-text-input" rows="2" placeholder="Enter the question"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Answer Help</label>
                <textarea class="form-control question-explanation-input" rows="2" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
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
        html += `<div class="form-row">
            <div class="form-group">
                <label class="input-label">Statement *</label>
                <textarea class="form-control question-text-input" rows="2" placeholder="Enter the statement to evaluate"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Answer Help</label>
                <textarea class="form-control question-explanation-input" rows="2" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
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
        html += `<div class="form-row">
            <div class="form-group">
                <label class="input-label">Statement / Item *</label>
                <textarea class="form-control question-text-input" rows="2" placeholder="Enter the statement or item to match"></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Answer Help</label>
                <textarea class="form-control question-explanation-input" rows="2" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">Correct Match (Answer)</label>
                <input type="text" class="form-control question-answer-input" placeholder="e.g. Paragraph A, Section 2, Feature X">
            </div>
            <div class="form-group" style="max-width:120px;">
                <label class="input-label">Points</label>
                <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
            </div>
        </div>`;

    } else if (isCompletion) {
        if (questionType === 'table_completion') {
            html += `<div class="form-row">
                <div class="form-group flex-fill">
                    <label class="input-label">Table Title / Instruction *</label>
                    <textarea class="form-control question-text-input" rows="2" placeholder="Nhập tiêu đề hoặc hướng dẫn cho bảng"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group flex-fill">
                    <label class="input-label">Answer Help</label>
                    <textarea class="form-control question-explanation-input" rows="2" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
                </div>
            </div>
            <div class="form-row align-items-end">
                <div class="form-group" style="max-width:110px;">
                    <label class="input-label">Rows</label>
                    <input type="number" class="form-control tc-num-rows" min="1" max="12" value="3">
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
                    <button type="button" class="btn btn-sm btn-primary" onclick="buildTableCompletionBuilder(this)">
                        <i class="fas fa-table"></i> Create Table
                    </button>
                </div>
            </div>
            <div class="alert alert-info py-2 px-3 mb-12">
                Nhập nội dung trong từng ô. Ô nào có <code>___</code> sẽ là chỗ trống cho học viên điền đáp án.
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
            html += `<div class="form-row">
                <div class="form-group flex-fill">
                    <label class="input-label">Question / Note Text *</label>
                    <textarea class="form-control question-text-input" rows="2" placeholder="Enter the note text and use ___ for each blank"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group flex-fill">
                    <label class="input-label">Answer Help</label>
                    <textarea class="form-control question-explanation-input" rows="2" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
                </div>
            </div>
            <div class="alert alert-info py-2 px-3 mb-12 note-completion-summary">
                Type the note text with <code>___</code> for each blank.
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
            html += `<div class="form-row">
                <div class="form-group">
                    <label class="input-label">Question / Sentence * <small class="text-muted">(use ___ for blank)</small></label>
                    <textarea class="form-control question-text-input" rows="2" placeholder="${placeholder}"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="input-label">Answer Help</label>
                    <textarea class="form-control question-explanation-input" rows="2" placeholder="Optional hint, model answer, or explanation for review..."></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="input-label">Correct Answer(s)</label>
                    <input type="text" class="form-control question-answer-input" placeholder="Enter the word(s) that fill the blank">
                </div>
                <div class="form-group" style="max-width:120px;">
                    <label class="input-label">Points</label>
                    <input type="number" class="form-control question-points-input" min="0" step="0.025" value="0.225">
                </div>
            </div>`;
        }

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

        // Ensure section-level audio inputs are preserved into the form before native submit
        preserveSectionAudioFiles();
        document.getElementById('questionGroupsData').value = JSON.stringify(testData);
        document.getElementById('testForm').submit();
    });
}

    // Ensure section audio inputs are preserved whenever the form is submitted
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('testForm');
        if (form) {
            form.addEventListener('submit', function() {
                preserveSectionAudioFiles();
            });
        }
    });
</script>
@endpush

@endsection
