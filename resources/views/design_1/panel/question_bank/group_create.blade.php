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
                        <x-iconsax-bul-trash class="icons" width="14px" height="14px"/>
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-12">
                        <label class="font-12 text-gray-500 mb-6">Type</label>
                        <select class="form-control question-type" required>
                            <option value="">Select Type</option>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="fill_blank">Fill in the Blank</option>
                            <option value="true_false_ng">True/False/Not Given</option>
                            <option value="short_answer">Short Answer</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-12">
                        <label class="font-12 text-gray-500 mb-6">Correct Answer</label>
                        <input type="text" class="form-control question-answer" required placeholder="e.g., A, True, London">
                    </div>
                </div>
                
                <div class="mb-12">
                    <label class="font-12 text-gray-500 mb-6">Question Text</label>
                    <textarea class="form-control question-text" rows="2" required placeholder="The question text..."></textarea>
                </div>

                <div class="options-container" style="display: none;">
                    <label class="font-12 text-gray-500 mb-6">Options (for MC)</label>
                    <div class="row">
                        <div class="col-6 mb-8"><input type="text" class="form-control option-a" placeholder="Option A"></div>
                        <div class="col-6 mb-8"><input type="text" class="form-control option-b" placeholder="Option B"></div>
                        <div class="col-6 mb-8"><input type="text" class="form-control option-c" placeholder="Option C"></div>
                        <div class="col-6 mb-8"><input type="text" class="form-control option-d" placeholder="Option D"></div>
                    </div>
                </div>
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

    // Show options for multiple choice
    $(document).on('change', '.question-type', function() {
        const $item = $(this).closest('.question-item');
        if ($(this).val() === 'multiple_choice') {
            $item.find('.options-container').show();
        } else {
            $item.find('.options-container').hide();
        }
    });

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
                correct_answer: $item.find('.question-answer').val(),
                points: 1
            };
            
            if (type === 'multiple_choice') {
                question.options = {
                    A: $item.find('.option-a').val(),
                    B: $item.find('.option-b').val(),
                    C: $item.find('.option-c').val(),
                    D: $item.find('.option-d').val()
                };
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
