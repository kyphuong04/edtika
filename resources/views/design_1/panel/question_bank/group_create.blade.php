@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Create Question Group</h1>
            <p class="text-gray-500 font-14 mt-4">Create multiple questions under one passage or audio</p>
        </div>
        <a href="{{ route('panel.question_bank.groups', $bankType) }}" class="btn btn-outline-secondary">
            <x-iconsax-lin-arrow-left class="icons mr-8" width="16px" height="16px"/>Back to Groups
        </a>
    </div>

    <form method="POST" action="{{ route('panel.question_bank.groups.store') }}" enctype="multipart/form-data" id="groupForm">
        @csrf
        <input type="hidden" name="bank_type" value="{{ $bankType }}">
        <input type="hidden" name="questions" id="questionsData" value="[]">

        <div class="row">
            <div class="col-lg-8">
                {{-- Step 1: Group Information --}}
                <div class="bg-white p-20 rounded-24 mb-24" id="step1">
                    <div class="d-flex align-items-center mb-16">
                        <div class="d-flex-center size-32 rounded-12 bg-primary text-white font-14 font-weight-bold mr-12">1</div>
                        <h4 class="font-16 font-weight-bold text-dark">Group Information</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Skill *</label>
                            <select name="skill" id="skill" class="form-control" required>
                                <option value="">Select Skill</option>
                                <option value="listening">🎧 Listening</option>
                                <option value="reading">📖 Reading</option>
                                <option value="writing">✍️ Writing</option>
                                <option value="speaking">🗣️ Speaking</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Difficulty *</label>
                            <select name="difficulty_level" class="form-control" required>
                                <option value="">Select Level</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Group Title *</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g., Climate Change and Ocean Ecosystems">
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief description of this question group"></textarea>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Tags</label>
                        <input type="text" name="tags" class="form-control" placeholder="environment, science, climate">
                        <small class="text-gray-500 font-12">Comma-separated topics</small>
                    </div>

                    {{-- Skill-specific content --}}
                    <div id="readingContent" class="skill-content" style="display: none;">
                        <label class="font-12 text-gray-500 mb-8">Reading Passage</label>
                        <textarea name="passage" id="passage" class="summernote"></textarea>
                    </div>

                    <div id="listeningContent" class="skill-content" style="display: none;">
                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Audio Transcript</label>
                            <textarea name="transcript" class="form-control" rows="6" placeholder="Full transcript of the audio..."></textarea>
                        </div>
                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Audio File</label>
                            <input type="file" name="audio_file" class="form-control" accept="audio/*">
                        </div>
                    </div>

                    <div id="writingContent" class="skill-content" style="display: none;">
                        <label class="font-12 text-gray-500 mb-8">Task Image (Chart/Diagram for Task 1)</label>
                        <input type="file" name="task_image" class="form-control" accept="image/*">
                    </div>
                </div>

                {{-- Step 2: Add Questions --}}
                <div class="bg-white p-20 rounded-24 mb-24" id="step2">
                    <div class="d-flex align-items-center justify-content-between mb-16">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-32 rounded-12 bg-primary text-white font-14 font-weight-bold mr-12">2</div>
                            <h4 class="font-16 font-weight-bold text-dark">Add Questions</h4>
                        </div>
                        <span class="font-14 text-gray-600" id="questionCount">0 questions</span>
                    </div>

                    <div id="questionsContainer">
                        {{-- Questions will be added here dynamically --}}
                    </div>

                    <button type="button" id="addQuestionBtn" class="btn btn-outline-primary w-100">
                        <x-iconsax-bul-add-circle class="icons mr-8" width="16px" height="16px"/>
                        Add Question
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Summary Card --}}
                <div class="bg-white p-20 rounded-24 mb-24 sticky-top" style="top: 20px;">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Summary</h4>
                    
                    <div class="mb-12">
                        <span class="text-gray-500 font-12">Bank Type:</span>
                        <div class="font-14 text-dark font-weight-500">{{ ucfirst($bankType) }}</div>
                    </div>

                    <div class="mb-12">
                        <span class="text-gray-500 font-12">Skill:</span>
                        <div class="font-14 text-dark font-weight-500" id="summarySkill">-</div>
                    </div>

                    <div class="mb-12">
                        <span class="text-gray-500 font-12">Questions:</span>
                        <div class="font-14 text-dark font-weight-500" id="summaryQuestions">0</div>
                    </div>

                    <hr class="my-16">

                    <button type="submit" class="btn btn-primary w-100 mb-12" id="submitBtn" disabled>
                        <x-iconsax-bul-tick-circle class="icons mr-8" width="16px" height="16px"/>
                        Create Group
                    </button>
                    <a href="{{ route('panel.question_bank.groups', $bankType) }}" class="btn btn-outline-secondary w-100">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</section>

@push('scripts_bottom')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
let questions = [];
let questionCounter = 0;

$(document).ready(function() {
    // Init Summernote
    $('.summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });

    // Skill change handler
    $('#skill').on('change', function() {
        const skill = $(this).val();
        $('.skill-content').hide();
        
        if (skill === 'reading') {
            $('#readingContent').show();
        } else if (skill === 'listening') {
            $('#listeningContent').show();
        } else if (skill === 'writing') {
            $('#writingContent').show();
        }
        
        $('#summarySkill').text(skill ? skill.charAt(0).toUpperCase() + skill.slice(1) : '-');
        updateSubmitButton();
    });

    // Add question
    $('#addQuestionBtn').on('click', function() {
        questionCounter++;
        const questionHtml = `
            <div class="question-item p-16 mb-16 rounded-16 bg-gray-100" data-question-id="${questionCounter}">
                <div class="d-flex align-items-center justify-content-between mb-12">
                    <span class="font-14 font-weight-bold text-dark">Question ${questionCounter}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-12">
                        <label class="font-12 text-gray-500 mb-6">Type *</label>
                        <select class="form-control question-type" required>
                            <option value="">Select Type</option>
                            <optgroup label="Multiple Choice">
                                <option value="multiple_choice">Multiple Choice (1 answer)</option>
                                <option value="multiple_choice_two">Multiple Choice (2 answers)</option>
                            </optgroup>
                            <optgroup label="True/False/Not Given">
                                <option value="true_false_ng">True/False/Not Given</option>
                                <option value="yes_no_ng">Yes/No/Not Given</option>
                            </optgroup>
                            <optgroup label="Matching">
                                <option value="matching_headings">Matching Headings</option>
                                <option value="matching_information">Matching Information</option>
                                <option value="matching_sentence_endings">Matching Sentence Endings</option>
                            </optgroup>
                            <optgroup label="Completion">
                                <option value="fill_blank">Fill in the Blank</option>
                                <option value="note_completion">Note/Form Completion</option>
                                <option value="summary_completion">Summary Completion</option>
                                <option value="summary_completion_wordbank">Summary (Word Bank)</option>
                                <option value="sentence_completion">Sentence Completion</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="short_answer">Short Answer</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                
                <div class="mb-12">
                    <label class="font-12 text-gray-500 mb-6">Question Text *</label>
                    <textarea class="form-control question-text" rows="2" required placeholder="The question text..."></textarea>
                </div>

                <!-- Dynamic fields container - changes based on question type -->
                <div class="type-specific-fields"></div>
            </div>
        `;
        
        $('#questionsContainer').append(questionHtml);
        updateQuestionCount();
        updateSubmitButton();
    });

    // Remove question
    $(document).on('click', '.remove-question', function() {
        $(this).closest('.question-item').remove();
        updateQuestionCount();
        updateSubmitButton();
    });

    // Show type-specific fields when question type changes
    $(document).on('change', '.question-type', function() {
        const $item = $(this).closest('.question-item');
        const type = $(this).val();
        const $container = $item.find('.type-specific-fields');
        
        let fieldsHtml = '';
        const qId = $item.data('question-id');
        
        switch(type) {
            case 'multiple_choice':
                fieldsHtml = `
                    <div class="alert alert-info py-8 px-12 mb-12">
                        <small><i class="fas fa-info-circle"></i> Enter options and select the correct one. Click + to add more options.</small>
                    </div>
                    <div class="row mb-8">
                        <div class="col-md-6">
                            <label class="font-12 text-gray-500 mb-6">Number of correct answers</label>
                            <select class="form-control correct-count" style="width: 150px;">
                                <option value="1" selected>1 answer</option>
                                <option value="2">2 answers</option>
                                <option value="3">3 answers</option>
                                <option value="5">5 answers</option>
                            </select>
                        </div>
                    </div>
                    <div class="options-list" data-question-id="${qId}">
                        <div class="row option-row mb-8" data-option="A">
                            <div class="col-11">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><input type="checkbox" value="A" class="correct-check"></span>
                                    </div>
                                    <input type="text" class="form-control option-input" data-option="A" placeholder="Option A" required>
                                </div>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn" style="display:none;"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="row option-row mb-8" data-option="B">
                            <div class="col-11">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><input type="checkbox" value="B" class="correct-check"></span>
                                    </div>
                                    <input type="text" class="form-control option-input" data-option="B" placeholder="Option B" required>
                                </div>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn" style="display:none;"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="row option-row mb-8" data-option="C">
                            <div class="col-11">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><input type="checkbox" value="C" class="correct-check"></span>
                                    </div>
                                    <input type="text" class="form-control option-input" data-option="C" placeholder="Option C" required>
                                </div>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="row option-row mb-8" data-option="D">
                            <div class="col-11">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><input type="checkbox" value="D" class="correct-check"></span>
                                    </div>
                                    <input type="text" class="form-control option-input" data-option="D" placeholder="Option D" required>
                                </div>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary add-option-btn mt-8">
                        <i class="fas fa-plus"></i> Add Option
                    </button>
                `;
                break;
                
            case 'multiple_choice_two':
                // Same as multiple_choice but with correct_count = 2
                fieldsHtml = `
                    <div class="alert alert-info py-8 px-12 mb-12">
                        <small><i class="fas fa-info-circle"></i> Enter options and check the correct answers. Click + to add more options.</small>
                    </div>
                    <div class="row mb-8">
                        <div class="col-md-6">
                            <label class="font-12 text-gray-500 mb-6">Number of correct answers</label>
                            <select class="form-control correct-count" style="width: 150px;">
                                <option value="1">1 answer</option>
                                <option value="2" selected>2 answers</option>
                                <option value="3">3 answers</option>
                                <option value="5">5 answers</option>
                            </select>
                        </div>
                    </div>
                    <div class="options-list" data-question-id="${qId}">
                        <div class="row option-row mb-8" data-option="A">
                            <div class="col-11">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><input type="checkbox" value="A" class="correct-check"></span>
                                    </div>
                                    <input type="text" class="form-control option-input" data-option="A" placeholder="Option A" required>
                                </div>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn" style="display:none;"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="row option-row mb-8" data-option="B">
                            <div class="col-11">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><input type="checkbox" value="B" class="correct-check"></span>
                                    </div>
                                    <input type="text" class="form-control option-input" data-option="B" placeholder="Option B" required>
                                </div>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn" style="display:none;"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="row option-row mb-8" data-option="C">
                            <div class="col-11">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><input type="checkbox" value="C" class="correct-check"></span>
                                    </div>
                                    <input type="text" class="form-control option-input" data-option="C" placeholder="Option C" required>
                                </div>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="row option-row mb-8" data-option="D">
                            <div class="col-11">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><input type="checkbox" value="D" class="correct-check"></span>
                                    </div>
                                    <input type="text" class="form-control option-input" data-option="D" placeholder="Option D" required>
                                </div>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary add-option-btn mt-8">
                        <i class="fas fa-plus"></i> Add Option
                    </button>
                `;
                break;
                
            case 'true_false_ng':
                fieldsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="font-12 text-gray-500 mb-6">Correct Answer *</label>
                            <select class="form-control question-answer" required>
                                <option value="">Select Answer</option>
                                <option value="TRUE">TRUE</option>
                                <option value="FALSE">FALSE</option>
                                <option value="NOT GIVEN">NOT GIVEN</option>
                            </select>
                        </div>
                    </div>
                `;
                break;
                
            case 'yes_no_ng':
                fieldsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="font-12 text-gray-500 mb-6">Correct Answer *</label>
                            <select class="form-control question-answer" required>
                                <option value="">Select Answer</option>
                                <option value="YES">YES</option>
                                <option value="NO">NO</option>
                                <option value="NOT GIVEN">NOT GIVEN</option>
                            </select>
                        </div>
                    </div>
                `;
                break;
                
            case 'matching_headings':
            case 'matching_information':
            case 'matching_sentence_endings':
                fieldsHtml = `
                    <div class="alert alert-info py-8 px-12 mb-12">
                        <small><i class="fas fa-info-circle"></i> Enter the correct matching option (e.g., i, ii, iii or A, B, C)</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="font-12 text-gray-500 mb-6">Correct Answer *</label>
                            <input type="text" class="form-control question-answer" required placeholder="e.g., iv, B, or iii">
                        </div>
                    </div>
                `;
                break;
                
            case 'fill_blank':
            case 'note_completion':
            case 'sentence_completion':
                fieldsHtml = `
                    <div class="row">
                        <div class="col-md-6 mb-12">
                            <label class="font-12 text-gray-500 mb-6">Word Limit</label>
                            <select class="form-control word-limit">
                                <option value="1">ONE WORD ONLY</option>
                                <option value="2">NO MORE THAN TWO WORDS</option>
                                <option value="3">NO MORE THAN THREE WORDS</option>
                                <option value="number">ONE WORD AND/OR A NUMBER</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-12">
                            <label class="font-12 text-gray-500 mb-6">Correct Answer *</label>
                            <input type="text" class="form-control question-answer" required placeholder="The correct word(s)">
                        </div>
                    </div>
                `;
                break;
                
            case 'summary_completion':
                fieldsHtml = `
                    <div class="row">
                        <div class="col-md-6 mb-12">
                            <label class="font-12 text-gray-500 mb-6">Word Limit</label>
                            <select class="form-control word-limit">
                                <option value="1">ONE WORD ONLY</option>
                                <option value="2" selected>NO MORE THAN TWO WORDS</option>
                                <option value="3">NO MORE THAN THREE WORDS</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-12">
                            <label class="font-12 text-gray-500 mb-6">Correct Answer *</label>
                            <input type="text" class="form-control question-answer" required placeholder="Word from passage">
                        </div>
                    </div>
                `;
                break;
                
            case 'summary_completion_wordbank':
                fieldsHtml = `
                    <div class="alert alert-info py-8 px-12 mb-12">
                        <small><i class="fas fa-info-circle"></i> Enter words for the word bank (comma separated)</small>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-12">
                            <label class="font-12 text-gray-500 mb-6">Word Bank *</label>
                            <input type="text" class="form-control word-bank" required placeholder="difficult, complex, original, admired, material, easy, fundamental">
                        </div>
                        <div class="col-md-6">
                            <label class="font-12 text-gray-500 mb-6">Correct Answer *</label>
                            <input type="text" class="form-control question-answer" required placeholder="e.g., complex">
                        </div>
                    </div>
                `;
                break;
                
            case 'short_answer':
                fieldsHtml = `
                    <div class="row">
                        <div class="col-md-6 mb-12">
                            <label class="font-12 text-gray-500 mb-6">Word Limit</label>
                            <select class="form-control word-limit">
                                <option value="1">ONE WORD ONLY</option>
                                <option value="2">NO MORE THAN TWO WORDS</option>
                                <option value="3" selected>NO MORE THAN THREE WORDS</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-12">
                            <label class="font-12 text-gray-500 mb-6">Correct Answer *</label>
                            <input type="text" class="form-control question-answer" required placeholder="The answer">
                        </div>
                    </div>
                `;
                break;
                
            default:
                fieldsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="font-12 text-gray-500 mb-6">Correct Answer *</label>
                            <input type="text" class="form-control question-answer" required placeholder="Enter answer">
                        </div>
                    </div>
                `;
        }
        
        $container.html(fieldsHtml);
    });

    // Add option handler
    $(document).on('click', '.add-option-btn', function() {
        const $item = $(this).closest('.question-item');
        const $list = $item.find('.options-list');
        const optionCount = $list.find('.option-row').length;
        
        if (optionCount >= 8) {
            Swal.fire({
                title: 'Limit Reached',
                text: 'Maximum 8 options allowed',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#1a3a5c'
            });
            return;
        }
        
        const letters = 'ABCDEFGH';
        const nextLetter = letters[optionCount];
        const qId = $list.data('question-id');
        
        const newOption = `
            <div class="row option-row mb-8" data-option="${nextLetter}">
                <div class="col-11">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><input type="checkbox" value="${nextLetter}" class="correct-check"></span>
                        </div>
                        <input type="text" class="form-control option-input" data-option="${nextLetter}" placeholder="Option ${nextLetter}" required>
                    </div>
                </div>
                <div class="col-1 d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn"><i class="fas fa-times"></i></button>
                </div>
            </div>
        `;
        $list.append(newOption);
        updateRemoveButtons($list);
    });

    // Remove option handler
    $(document).on('click', '.remove-option-btn', function() {
        const $row = $(this).closest('.option-row');
        const $list = $row.closest('.options-list');
        
        if ($list.find('.option-row').length <= 2) {
            Swal.fire({
                title: 'Cannot Remove',
                text: 'Minimum 2 options required',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#1a3a5c'
            });
            return;
        }
        
        $row.remove();
        
        // Relabel remaining options
        const letters = 'ABCDEFGH';
        $list.find('.option-row').each(function(index) {
            const letter = letters[index];
            $(this).attr('data-option', letter);
            $(this).find('.correct-check').val(letter);
            $(this).find('.option-input').attr('data-option', letter).attr('placeholder', 'Option ' + letter);
        });
        
        updateRemoveButtons($list);
    });

    function updateRemoveButtons($list) {
        const count = $list.find('.option-row').length;
        $list.find('.remove-option-btn').each(function(index) {
            // Hide remove for first 2 options (minimum required)
            $(this).toggle(index >= 2 || count > 2);
        });
    }

    // Form submit
    $('#groupForm').on('submit', function(e) {
        e.preventDefault();
        
        // Collect questions
        questions = [];
        $('.question-item').each(function() {
            const $item = $(this);
            const type = $item.find('.question-type').val();
            
            const question = {
                question_type: type,
                question_text: $item.find('.question-text').val(),
                points: 1
            };
            
            // Get correct answer based on type
            if (type === 'multiple_choice' || type === 'multiple_choice_two') {
                // Collect checked answers
                const checked = [];
                $item.find('.correct-check:checked').each(function() {
                    checked.push($(this).val());
                });
                question.correct_answer = checked.join(', ');
                
                // Collect all options dynamically
                question.options = {};
                $item.find('.option-input').each(function() {
                    const optLetter = $(this).data('option');
                    const optValue = $(this).val();
                    if (optValue) {
                        question.options[optLetter] = optValue;
                    }
                });
                
                // Store correct count
                question.correct_count = $item.find('.correct-count').val() || '1';
            } else {
                question.correct_answer = $item.find('.question-answer').val();
            }
            
            // Get word limit if applicable
            const wordLimit = $item.find('.word-limit').val();
            if (wordLimit) {
                question.word_limit = wordLimit;
            }
            
            // Get word bank if applicable
            const wordBank = $item.find('.word-bank').val();
            if (wordBank) {
                question.word_bank = wordBank.split(',').map(w => w.trim());
            }
            
            questions.push(question);
        });
        
        $('#questionsData').val(JSON.stringify(questions));
        this.submit();
    });

    function updateQuestionCount() {
        const count = $('.question-item').length;
        $('#questionCount').text(`${count} question${count !== 1 ? 's' : ''}`);
        $('#summaryQuestions').text(count);
    }

    function updateSubmitButton() {
        const hasSkill = $('#skill').val() !== '';
        const hasQuestions = $('.question-item').length > 0;
        $('#submitBtn').prop('disabled', !(hasSkill && hasQuestions));
    }
});
</script>
@endpush
@endsection

