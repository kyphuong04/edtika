@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
.inline-test-creator {
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

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
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

.questions-list {
    background: #f9fafb;
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
    max-height: 400px;
    overflow-y: auto;
}

.question-item {
    background: white;
    border-left: 4px solid #3b82f6;
    padding: 12px;
    margin-bottom: 8px;
    border-radius: 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.question-item .q-number {
    font-weight: 600;
    color: #6b7280;
    min-width: 40px;
}

.question-item .q-type {
    display: inline-block;
    background: #e0e7ff;
    color: #4c51bf;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.question-actions {
    display: flex;
    gap: 8px;
}

.question-actions .btn {
    padding: 4px 8px;
    font-size: 12px;
}

.add-question-form {
    background: #f0fdf4;
    border: 2px dashed #22c55e;
    border-radius: 8px;
    padding: 20px;
    margin-top: 16px;
}

.add-question-form.hidden {
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

.question-type-select {
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 8px 12px;
}

.question-type-fields {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-top: 12px;
    display: none;
}

.question-type-fields.active {
    display: block;
}

.answer-options-container {
    background: #f3f4f6;
    border-radius: 6px;
    padding: 12px;
    margin-top: 8px;
}

.answer-option-input {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
    align-items: center;
}

.answer-option-input input {
    flex: 1;
    padding: 6px 10px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
}

.answer-option-input .is-correct {
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-add-section {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s ease;
}

.btn-add-section:hover {
    background: #2563eb;
}

.btn-add-question {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: #22c55e;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
}

.btn-add-question:hover {
    background: #16a34a;
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

.error-message {
    color: #dc2626;
    font-size: 13px;
    margin-top: 4px;
}

.success-message {
    color: #16a34a;
    font-size: 13px;
    margin-top: 4px;
}

.section-actions {
    display: flex;
    gap: 8px;
}

.remove-section-btn {
    padding: 4px 8px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

.remove-section-btn:hover {
    background: #dc2626;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    .form-row .form-group {
        min-width: unset;
    }
}
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="section-title">
            <i class="fas fa-plus-circle mr-10"></i>
            Create Complete IELTS Test (With Questions)
        </h1>
        <a href="{{ route('panel.my_ielts_tests.create') }}" class="btn btn-sm btn-gray">
            <i class="fas fa-arrow-left mr-5"></i>Back
        </a>
    </div>

    <div class="alert alert-success">
        <i class="fas fa-lightbulb mr-5"></i>
        <strong>New Flow:</strong> Create a complete test with all sections and questions at once. Submit for approval to CEO/Manager for final review.
    </div>

    <form id="inlineTestForm" action="{{ route('panel.my_ielts_tests.store_inline_complete') }}" method="POST">
        @csrf

        {{-- 1. Test Basic Information --}}
        <div class="test-info-card">
            <h3 class="font-16 font-weight-bold mb-15">
                <i class="fas fa-info-circle mr-8"></i>Test Information
            </h3>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Test Type *</label>
                        <select id="testTypeSelect" name="type" class="form-control @error('type') is-invalid @enderror" required onchange="updateTestTypeRequirements()">
                            <option value="">-- Select Type --</option>
                            <option value="mock" {{ old('type') === 'mock' ? 'selected' : '' }}>Mock Test (Full)</option>
                            <option value="practice" {{ old('type') === 'practice' ? 'selected' : '' }}>Practice Test (Single Section)</option>
                        </select>
                        <small class="text-muted d-block mt-8">
                            <strong>Mock:</strong> All 4 sections required • <strong>Practice:</strong> Any 1 section
                        </small>
                        @error('type')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Test Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" required placeholder="e.g. IELTS Full Mock Test - March 2026">
                        @error('title')
                            <div class="error-message">{{ $message }}</div>
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
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="2"
                          placeholder="Brief description...">{{ old('description') }}</textarea>
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
                        <input type="number" name="target_band_min" class="form-control" 
                               min="0" max="9" step="0.5" value="{{ old('target_band_min', 5.0) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="input-label">Target Band Max</label>
                        <input type="number" name="target_band_max" class="form-control" 
                               min="0" max="9" step="0.5" value="{{ old('target_band_max', 8.0) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Completeness Status --}}
        <div id="completenessStatus" class="completeness-check">
            <strong><i class="fas fa-check-circle mr-8"></i>Test Completeness Status</strong>
            <div id="completenessMessage" style="margin-bottom: 12px; font-size: 13px; color: #666;">
                <i class="fas fa-info-circle mr-6"></i><span id="typeRequirement">Select a test type first</span>
            </div>
            <ul id="completenessChecklist">
                <li><span class="status-listening">○</span> Listening Section <span class="req-listening">(40 questions)</span></li>
                <li><span class="status-reading">○</span> Reading Section <span class="req-reading">(40 questions)</span></li>
                <li><span class="status-writing">○</span> Writing Section <span class="req-writing">(2 questions)</span></li>
                <li><span class="status-speaking">○</span> Speaking Section <span class="req-speaking">(3 questions)</span></li>
            </ul>
        </div>

        {{-- 3. Test Sections with Question Groups & Questions --}}
        <div id="sectionsContainer">
            {{-- Sections will be added here dynamically --}}
        </div>

        {{-- 4. Action Buttons --}}
        <div class="mt-30 d-flex gap-10">
            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                <i class="fas fa-check-circle mr-5"></i>
                Submit Test for Approval
            </button>
            <a href="{{ route('panel.my_ielts_tests.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times mr-5"></i>Cancel
            </a>
        </div>
    </form>

    {{-- Hidden Input for Question Groups JSON --}}
    <input type="hidden" id="questionGroupsJsonField" name="question_groups_data" value=""
</section>

{{-- Hidden Template for Sections --}}
<template id="sectionTemplate">
    <div class="section-container" data-skill="">
        <div class="section-header">
            <div class="section-title">
                <div class="skill-icon"></div>
                <div>
                    <h4 class="mb-0"></h4>
                    <small class="text-muted">Questions: <span class="question-count">0</span></small>
                </div>
            </div>
            <div class="section-actions">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="this.closest('.section-container').querySelector('.add-question-form').classList.toggle('hidden')">
                    <i class="fas fa-plus mr-5"></i>Add Question
                </button>
                <button type="button" class="remove-section-btn" onclick="removeSection(this)">
                    <i class="fas fa-trash mr-3"></i>Remove
                </button>
            </div>
        </div>

        {{-- Section Details --}}
        <div class="form-group">
            <label class="input-label">Duration (minutes)</label>
            <input type="number" name="sections[duration]" class="form-control" min="1" value="30" required>
        </div>

        {{-- Questions List --}}
        <div class="questions-list"></div>

        {{-- Add Question Form --}}
        <div class="add-question-form hidden">
            <h5 class="mb-15"><i class="fas fa-plus-circle mr-8"></i>Add New Question</h5>

            <div class="form-row">
                <div class="form-group" style="flex: 0.5; min-width: 100px;">
                    <label class="input-label">Q#</label>
                    <input type="number" class="form-control question-number" min="1" placeholder="1" required>
                </div>

                <div class="form-group">
                    <label class="input-label">Question Type *</label>
                    <select class="form-control question-type-select" required onchange="changeQuestionType(this)">
                        <option value="">-- Select Type --</option>
                        <optgroup label="Listening/Reading">
                            <option value="multiple_choice">Multiple Choice (A/B/C/D)</option>
                            <option value="true_false_ng">True / False / Not Given</option>
                            <option value="fill_blank">Fill in the Blanks</option>
                            <option value="multiple_select">Multiple Select</option>
                            <option value="sentence_completion">Sentence Completion</option>
                            <option value="note_completion">Note Completion</option>
                            <option value="table_completion">Table Completion</option>
                            <option value="flow_chart">Flow Chart</option>
                            <option value="diagram_label">Diagram Labeling</option>
                        </optgroup>
                        <optgroup label="Writing">
                            <option value="essay">Writing Task</option>
                        </optgroup>
                        <optgroup label="Speaking">
                            <option value="speaking_prompt">Speaking Prompt</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            {{-- Question Text --}}
            <div class="form-row full">
                <div class="form-group">
                    <label class="input-label">Question Text / Prompt *</label>
                    <textarea class="form-control question-text" rows="3" placeholder="Enter question text..." required></textarea>
                </div>
            </div>

            {{-- Answer Options (Dynamic based on type) --}}
            <div class="question-type-fields" data-type="multiple_choice,true_false_ng,multiple_select">
                <label class="input-label d-block mb-10">Answer Options</label>
                <div class="answer-options-container">
                    <div class="answer-option-input">
                        <span class="option-label">A:</span>
                        <input type="text" class="form-control answer-option" placeholder="Option A" required>
                        <label class="is-correct">
                            <input type="radio" name="correct_answer_option" value="A" class="correct-answer-radio" required>
                            Correct
                        </label>
                    </div>
                    <div class="answer-option-input">
                        <span class="option-label">B:</span>
                        <input type="text" class="form-control answer-option" placeholder="Option B" required>
                        <label class="is-correct">
                            <input type="radio" name="correct_answer_option" value="B" class="correct-answer-radio" required>
                            Correct
                        </label>
                    </div>
                    <div class="answer-option-input">
                        <span class="option-label">C:</span>
                        <input type="text" class="form-control answer-option" placeholder="Option C" required>
                        <label class="is-correct">
                            <input type="radio" name="correct_answer_option" value="C" class="correct-answer-radio" required>
                            Correct
                        </label>
                    </div>
                    <div class="answer-option-input">
                        <span class="option-label">D:</span>
                        <input type="text" class="form-control answer-option" placeholder="Option D" required>
                        <label class="is-correct">
                            <input type="radio" name="correct_answer_option" value="D" class="correct-answer-radio" required>
                            Correct
                        </label>
                    </div>
                </div>
            </div>

            {{-- Fill Blank Answer --}}
            <div class="question-type-fields" data-type="fill_blank,sentence_completion">
                <label class="input-label">Correct Answer(s) *</label>
                <textarea class="form-control correct-answer-text" rows="2" placeholder="Possible answers (one per line)..." required></textarea>
                <small class="text-muted">Enter multiple variations separated by line breaks</small>
            </div>

            {{-- Essay / Speaking Answer --}}
            <div class="question-type-fields" data-type="essay,speaking_prompt">
                <label class="input-label">Word Limit (if applicable)</label>
                <input type="number" class="form-control word-limit" min="1" placeholder="e.g., 150 for writing task 1">
            </div>

            <div class="mt-12">
                <button type="button" class="btn btn-success btn-sm" onclick="addQuestion(this)">
                    <i class="fas fa-plus mr-5"></i>Add Question
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="this.closest('.add-question-form').classList.add('hidden')">
                    <i class="fas fa-times mr-5"></i>Cancel
                </button>
            </div>
        </div>
    </div>
</template>

@push('scripts_bottom')
<script>
const SECTIONS_CONFIG = {
    listening: { skill: 'listening', title: 'Listening', icon: '🔊', mock: 40, practice: 0 },
    reading: { skill: 'reading', title: 'Reading', icon: '📖', mock: 40, practice: 0 },
    writing: { skill: 'writing', title: 'Writing', icon: '✍️', mock: 2, practice: 0 },
    speaking: { skill: 'speaking', title: 'Speaking', icon: '🎤', mock: 3, practice: 0 }
};

let currentTestType = null;

let testData = {
    sections: {}
};

// Initialize sections on page load
document.addEventListener('DOMContentLoaded', function() {
    setupFormValidation();
});

function updateTestTypeRequirements() {
    currentTestType = document.getElementById('testTypeSelect').value;
    
    if (!currentTestType) {
        document.getElementById('typeRequirement').textContent = 'Select a test type first';
        return;
    }

    const isMock = currentTestType === 'mock';
    document.getElementById('typeRequirement').textContent = isMock 
        ? 'Mock Test: All 4 sections required' 
        : 'Practice Test: At least 1 section required (any questions)';

    // Update requirement labels
    Object.keys(SECTIONS_CONFIG).forEach(skill => {
        const req = SECTIONS_CONFIG[skill][currentTestType];
        const reqElement = document.querySelector(`.req-${skill}`);
        if (reqElement) {
            if (isMock) {
                reqElement.textContent = `(${req} questions)`;
            } else {
                reqElement.textContent = '(any number)';
            }
        }
    });

    // If sections not initialized yet, initialize them
    if (!document.querySelector('.section-container')) {
        initializeSections();
    }

    updateCompletenessStatus();
}

function getRequiredQuestions(skill) {
    if (!currentTestType) return SECTIONS_CONFIG[skill].mock;
    return SECTIONS_CONFIG[skill][currentTestType] || 0;
}

function initializeSections() {
    const container = document.getElementById('sectionsContainer');
    container.innerHTML = '';

    Object.entries(SECTIONS_CONFIG).forEach(([skill, config]) => {
        const template = document.getElementById('sectionTemplate');
        const section = template.content.cloneNode(true);

        // Set section attributes and content
        const sectionContainer = section.querySelector('.section-container');
        sectionContainer.setAttribute('data-skill', skill);

        section.querySelector('.skill-icon').textContent = config.icon;
        section.querySelector('.skill-icon').classList.add(skill);
        section.querySelector('.section-title h4').textContent = config.title + ' Section';
        section.querySelector('input[name="sections[duration]"]').name = `sections[${skill}][duration]`;

        container.appendChild(section);

        testData.sections[skill] = {
            questions: []
        };
    });
}

function changeQuestionType(selectElement) {
    const type = selectElement.value;
    const form = selectElement.closest('.add-question-form');

    // Hide all type-specific fields
    form.querySelectorAll('.question-type-fields').forEach(field => {
        field.classList.remove('active');
    });

    // Show relevant fields for this type
    if (type) {
        form.querySelectorAll('.question-type-fields').forEach(field => {
            const types = field.getAttribute('data-type').split(',');
            if (types.includes(type)) {
                field.classList.add('active');
            }
        });
    }
}

function addQuestion(button) {
    const form = button.closest('.add-question-form');
    const sectionContainer = form.closest('.section-container');
    const skill = sectionContainer.getAttribute('data-skill');

    // Collect question data
    const questionNumber = form.querySelector('.question-number').value;
    const questionType = form.querySelector('.question-type-select').value;
    const questionText = form.querySelector('.question-text').value;

    if (!questionNumber || !questionType || !questionText) {
        alert('Please fill in all required fields');
        return;
    }

    // Build answer data based on type
    let correctAnswer = '';
    const answerOptions = [];

    if (['multiple_choice', 'true_false_ng', 'multiple_select'].includes(questionType)) {
        const options = form.querySelectorAll('.answer-option');
        const correctRadio = form.querySelector('.correct-answer-radio:checked');

        options.forEach((opt, i) => {
            answerOptions.push(opt.value);
        });

        if (!correctRadio) {
            alert('Please select correct answer');
            return;
        }
        correctAnswer = correctRadio.value;
    } else if (['fill_blank', 'sentence_completion', 'note_completion'].includes(questionType)) {
        correctAnswer = form.querySelector('.correct-answer-text').value;
        if (!correctAnswer) {
            alert('Please enter correct answer(s)');
            return;
        }
    }

    // Create question object
    const question = {
        id: Date.now(), // Temporary ID
        number: questionNumber,
        type: questionType,
        text: questionText,
        options: answerOptions,
        correctAnswer: correctAnswer,
        wordLimit: form.querySelector('.word-limit')?.value || null
    };

    // Add to test data
    testData.sections[skill].questions.push(question);

    // Display question in list
    displayQuestion(sectionContainer, question);

    // Reset form - manually clear all fields since form is a div, not <form>
    form.querySelector('.question-number').value = '';
    form.querySelector('.question-type-select').value = '';
    form.querySelector('.question-text').value = '';
    
    // Clear all answer options
    form.querySelectorAll('.answer-option').forEach(input => input.value = '');
    form.querySelectorAll('.correct-answer-radio').forEach(radio => radio.checked = false);
    form.querySelectorAll('input[name="correct_answer_option"]').forEach(radio => radio.checked = false);
    
    // Clear correct answer text (for fill blanks, etc)
    const correctAnswerText = form.querySelector('.correct-answer-text');
    if (correctAnswerText) correctAnswerText.value = '';
    
    // Clear word limit
    const wordLimit = form.querySelector('.word-limit');
    if (wordLimit) wordLimit.value = '';
    
    form.classList.add('hidden');
    form.querySelectorAll('.question-type-fields').forEach(f => f.classList.remove('active'));

    // Update completeness check
    updateCompletenessStatus();
}

function displayQuestion(sectionContainer, question) {
    const questionsList = sectionContainer.querySelector('.questions-list');

    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-item';
    questionDiv.setAttribute('data-question-id', question.id);

    questionDiv.innerHTML = `
        <div style="flex: 1;">
            <span class="q-number">Q${question.number}:</span>
            <span class="q-type">${question.type}</span>
            <small class="text-muted d-block mt-4" style="margin-top: 4px;">
                ${question.text.substring(0, 50)}${question.text.length > 50 ? '...' : ''}
            </small>
        </div>
        <div class="question-actions">
            <button type="button" class="btn btn-sm btn-outline-warning" onclick="editQuestion(this)">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestion(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    questionsList.appendChild(questionDiv);

    // Update question count
    const count = questionsList.querySelectorAll('.question-item').length;
    sectionContainer.querySelector('.question-count').textContent = count;
}

function removeQuestion(button) {
    const questionItem = button.closest('.question-item');
    const questionId = questionItem.getAttribute('data-question-id');
    const sectionContainer = button.closest('.section-container');
    const skill = sectionContainer.getAttribute('data-skill');

    // Remove from data
    testData.sections[skill].questions = testData.sections[skill].questions.filter(q => q.id != questionId);

    // Remove from UI
    questionItem.remove();

    // Update count
    const count = sectionContainer.querySelectorAll('.question-item').length;
    sectionContainer.querySelector('.question-count').textContent = count;

    updateCompletenessStatus();
}

function removeSection(button) {
    if (confirm('Remove this entire section?')) {
        const sectionContainer = button.closest('.section-container');
        const skill = sectionContainer.getAttribute('data-skill');
        delete testData.sections[skill];
        sectionContainer.remove();
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
        const count = testData.sections[skill]?.questions.length || 0;
        const required = getRequiredQuestions(skill);
        const isComplete = count >= required && count > 0;
        const hasAny = count > 0;
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
        status.classList.remove('completeness-check');
    } else {
        status.classList.remove('success');
        status.classList.add('completeness-check');
    }
}

function setupFormValidation() {
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        e.preventDefault();

        const testType = document.getElementById('testTypeSelect').value;
        if (!testType) {
            alert('Please select a test type (Mock or Practice)');
            return;
        }

        const isMock = testType === 'mock';
        let issues = [];

        if (isMock) {
            // Mock test: ALL sections must be complete
            Object.entries(SECTIONS_CONFIG).forEach(([skill, config]) => {
                const count = testData.sections[skill]?.questions.length || 0;
                const required = config.mock;
                if (count < required) {
                    issues.push(`${config.title}: ${count}/${required} questions`);
                }
            });

            if (issues.length > 0) {
                alert('Mock Test requires all sections to be complete:\n\n' + issues.join('\n'));
                return;
            }
        } else {
            // Practice test: At least 1 section required
            const totalQuestions = Object.values(testData.sections).reduce(
                (sum, section) => sum + (section.questions?.length || 0), 0
            );

            if (totalQuestions === 0) {
                alert('Practice Test requires at least 1 question in any section');
                return;
            }
        }

        // Serialize test data to hidden fields
        serializeTestData();

        // Submit form
        document.getElementById('inlineTestForm').submit();
    });
}

function serializeTestData() {
    const form = document.getElementById('inlineTestForm');

    // Remove old serialized data
    form.querySelectorAll('input[name^="questions_data"]').forEach(el => el.remove());

    // Add serialized data
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'questions_data';
    input.value = JSON.stringify(testData);
    form.appendChild(input);
}

function editQuestion(button) {
    alert('Edit functionality coming soon');
}
</script>
@endpush

@endsection
