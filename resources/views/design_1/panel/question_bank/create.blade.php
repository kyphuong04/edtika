@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">{{ isset($question) ? 'Edit' : 'Add' }} Question</h1>
            <p class="text-gray-500 font-14 mt-4">{{ isset($question) ? 'Update' : 'Create new' }} question for {{ ucfirst($bankType ?? request('bank_type', 'mock')) }} bank</p>
        </div>
        <a href="{{ route('panel.question_bank.' . ($bankType ?? request('bank_type', 'mock')) . '.list') }}" class="btn btn-outline-secondary">
            <x-iconsax-lin-arrow-left class="icons mr-8" width="16px" height="16px"/>Back to List
        </a>
    </div>

    <form method="POST" action="{{ isset($question) ? route('panel.question_bank.update', [$bankType, $question->id]) : route('panel.question_bank.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($question))
            @method('PUT')
        @endif

        <input type="hidden" name="bank_type" value="{{ $bankType ?? request('bank_type', 'mock') }}">

        <div class="row">
            <div class="col-lg-8">
                {{-- Basic Info --}}
                <div class="bg-white p-20 rounded-24 mb-24">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Basic Information</h4>

                    <div class="row">
                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Skill *</label>
                            <select name="skill" id="skill" class="form-control" required>
                                <option value="">Select Skill</option>
                                <option value="listening" {{ (isset($question) && $question->skill == 'listening') || old('skill') == 'listening' ? 'selected' : '' }}>🎧 Listening</option>
                                <option value="reading" {{ (isset($question) && $question->skill == 'reading') || old('skill') == 'reading' ? 'selected' : '' }}>📖 Reading</option>
                                <option value="writing" {{ (isset($question) && $question->skill == 'writing') || old('skill') == 'writing' ? 'selected' : '' }}>✍️ Writing</option>
                                <option value="speaking" {{ (isset($question) && $question->skill == 'speaking') || old('skill') == 'speaking' ? 'selected' : '' }}>🗣️ Speaking</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Question Type *</label>
                            <select name="question_type" id="question_type" class="form-control" required>
                                <option value="">Select Skill first</option>
                                {{-- Listening Types --}}
                                <option value="multiple_choice" data-skills="listening reading" {{ (isset($question) && $question->question_type == 'multiple_choice') ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="fill_blank" data-skills="listening reading" {{ (isset($question) && $question->question_type == 'fill_blank') ? 'selected' : '' }}>Fill in the Blank</option>
                                <option value="matching" data-skills="listening reading" {{ (isset($question) && $question->question_type == 'matching') ? 'selected' : '' }}>Matching</option>
                                <option value="short_answer" data-skills="listening reading" {{ (isset($question) && $question->question_type == 'short_answer') ? 'selected' : '' }}>Short Answer</option>
                                <option value="sentence_completion" data-skills="listening reading" {{ (isset($question) && $question->question_type == 'sentence_completion') ? 'selected' : '' }}>Sentence Completion</option>
                                {{-- Reading Specific --}}
                                <option value="true_false_ng" data-skills="reading" {{ (isset($question) && $question->question_type == 'true_false_ng') ? 'selected' : '' }}>True/False/Not Given</option>
                                <option value="heading_matching" data-skills="reading" {{ (isset($question) && $question->question_type == 'heading_matching') ? 'selected' : '' }}>Heading Matching</option>
                                {{-- Writing Types --}}
                                <option value="essay" data-skills="writing" {{ (isset($question) && $question->question_type == 'essay') ? 'selected' : '' }}>Essay (Task 2)</option>
                                <option value="report" data-skills="writing" {{ (isset($question) && $question->question_type == 'report') ? 'selected' : '' }}>Report/Letter (Task 1)</option>
                                {{-- Speaking Types --}}
                                <option value="speaking_part1" data-skills="speaking" {{ (isset($question) && $question->question_type == 'speaking_part1') ? 'selected' : '' }}>Part 1 - Introduction</option>
                                <option value="speaking_part2" data-skills="speaking" {{ (isset($question) && $question->question_type == 'speaking_part2') ? 'selected' : '' }}>Part 2 - Long Turn</option>
                                <option value="speaking_part3" data-skills="speaking" {{ (isset($question) && $question->question_type == 'speaking_part3') ? 'selected' : '' }}>Part 3 - Discussion</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Question Text *</label>
                        <textarea name="question_text" class="form-control" rows="3" required>{{ isset($question) ? $question->question_text : old('question_text') }}</textarea>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Instruction</label>
                        <textarea name="instruction" class="form-control" rows="2">{{ isset($question) ? $question->instruction : old('instruction') }}</textarea>
                        <small class="text-gray-500 font-12">Optional helper text for students</small>
                    </div>
                </div>

                {{-- Content (Conditional) --}}
                <div class="bg-white p-20 rounded-24 mb-24" id="content-section">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Content</h4>

                    {{-- Passage (Reading) - WITH RICH TEXT EDITOR --}}
                    <div class="mb-16 conditional-field" data-skills="reading">
                        <label class="font-12 text-gray-500 mb-8">Reading Passage</label>
                        <textarea name="passage" id="passage" class="summernote">{{ isset($question) ? $question->passage : old('passage') }}</textarea>
                        <small class="text-gray-500 font-12 mt-8">Use toolbar to format text, add images, etc.</small>
                    </div>

                    {{-- Transcript (Listening) --}}
                    <div class="mb-16 conditional-field" data-skills="listening">
                        <label class="font-12 text-gray-500 mb-8">Audio Transcript</label>
                        <textarea name="transcript" class="form-control" rows="6">{{ isset($question) ? $question->transcript : old('transcript') }}</textarea>
                    </div>

                    {{-- Audio File (Listening) --}}
                    <div class="mb-16 conditional-field" data-skills="listening">
                        <label class="font-12 text-gray-500 mb-8">Audio File</label>
                        <input type="file" name="audio_file" class="form-control" accept="audio/*">
                        @if(isset($question) && $question->audio_file)
                            <small class="text-gray-500 font-12 mt-4">Current: {{ basename($question->audio_file) }}</small>
                        @endif
                    </div>

                    {{-- Writing Task Image --}}
                    <div class="mb-16 conditional-field" data-skills="writing">
                        <label class="font-12 text-gray-500 mb-8">Task Image (for charts/diagrams)</label>
                        <input type="file" name="task_image" class="form-control" accept="image/*">
                        @if(isset($question) && $question->task_image)
                            <div class="mt-8">
                                <img src="{{ $question->task_image }}" alt="Task" style="max-width: 300px; border-radius: 8px;">
                            </div>
                        @endif
                        <small class="text-gray-500 font-12 mt-4">Upload chart/graph/diagram for Task 1</small>
                    </div>

                    {{-- Options (Multiple Choice) --}}
                    <div class="conditional-field" data-types="multiple_choice" style="display: none;">
                        <label class="font-12 text-gray-500 mb-8">Answer Options</label>
                        <div class="row">
                            @foreach(['A', 'B', 'C', 'D'] as $option)
                                <div class="col-md-6 mb-12">
                                    <input type="text" name="options[{{ $option }}]" class="form-control" placeholder="Option {{ $option }}" value="{{ isset($question) && isset($question->options[$option]) ? $question->options[$option] : old("options.$option") }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Matching Pairs --}}
                    <div class="conditional-field" data-types="matching heading_matching" style="display: none;">
                        <label class="font-12 text-gray-500 mb-8">Matching Items (one per line, format: item1 | match1)</label>
                        <textarea name="matching_items" class="form-control" rows="5" placeholder="Item 1 | Match A&#10;Item 2 | Match B&#10;Item 3 | Match C">{{ isset($question) && isset($question->matching_items) ? $question->matching_items : old('matching_items') }}</textarea>
                    </div>

                    {{-- Speaking Cue Card (Part 2) --}}
                    <div class="conditional-field" data-types="speaking_part2" style="display: none;">
                        <label class="font-12 text-gray-500 mb-8">Cue Card Prompts (one per line)</label>
                        <textarea name="cue_card" class="form-control" rows="4" placeholder="You should say:&#10;• What it is&#10;• Where it is&#10;• When you did it&#10;• And explain why...">{{ isset($question) && isset($question->cue_card) ? $question->cue_card : old('cue_card') }}</textarea>
                    </div>
                </div>

                {{-- Answer --}}
                <div class="bg-white p-20 rounded-24 mb-24">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Answer & Scoring</h4>

                    <div class="row">
                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Correct Answer *</label>
                            <input type="text" name="correct_answer" class="form-control" required value="{{ isset($question) ? $question->correct_answer : old('correct_answer') }}" placeholder="e.g., A, Room 5, renewable energy">
                            <small class="text-gray-500 font-12">For MC: A/B/C/D, For fill-blank: exact answer</small>
                        </div>

                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Points</label>
                            <input type="number" name="points" class="form-control" step="0.5" value="{{ isset($question) ? $question->points : old('points', 1) }}">
                        </div>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Explanation (Optional)</label>
                        <textarea name="explanation" class="form-control" rows="3">{{ isset($question) ? $question->explanation : old('explanation') }}</textarea>
                        <small class="text-gray-500 font-12">Why this is the correct answer</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Settings --}}
                <div class="bg-white p-20 rounded-24 mb-24">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Settings</h4>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Difficulty *</label>
                        <select name="difficulty_level" class="form-control" required>
                            <option value="">Select Level</option>
                            <option value="beginner" {{ (isset($question) && $question->difficulty_level == 'beginner') || old('difficulty_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ (isset($question) && $question->difficulty_level == 'intermediate') || old('difficulty_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ (isset($question) && $question->difficulty_level == 'advanced') || old('difficulty_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>

                    {{-- Practice-specific fields --}}
                    @if(($bankType ?? request('bank_type')) == 'practice')
                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Target Band</label>
                            <select name="target_band" class="form-control">
                                <option value="">Not specified</option>
                                <option value="5.0" {{ (isset($question) && $question->target_band == '5.0') || old('target_band') == '5.0' ? 'selected' : '' }}>5.0</option>
                                <option value="5.5" {{ (isset($question) && $question->target_band == '5.5') || old('target_band') == '5.5' ? 'selected' : '' }}>5.5</option>
                                <option value="6.0" {{ (isset($question) && $question->target_band == '6.0') || old('target_band') == '6.0' ? 'selected' : '' }}>6.0</option>
                                <option value="6.5" {{ (isset($question) && $question->target_band == '6.5') || old('target_band') == '6.5' ? 'selected' : '' }}>6.5</option>
                                <option value="7.0" {{ (isset($question) && $question->target_band == '7.0') || old('target_band') == '7.0' ? 'selected' : '' }}>7.0</option>
                                <option value="7.5" {{ (isset($question) && $question->target_band == '7.5') || old('target_band') == '7.5' ? 'selected' : '' }}>7.5</option>
                                <option value="8.0" {{ (isset($question) && $question->target_band == '8.0') || old('target_band') == '8.0' ? 'selected' : '' }}>8.0+</option>
                            </select>
                        </div>

                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Practice Focus</label>
                            <input type="text" name="practice_focus" class="form-control" value="{{ isset($question) ? $question->practice_focus : old('practice_focus') }}" placeholder="e.g., Vocabulary, Grammar">
                        </div>
                    @endif

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Tags</label>
                        <input type="text" name="tags" class="form-control" value="{{ isset($question) && $question->tags ? implode(', ', $question->tags) : old('tags') }}" placeholder="Environment, Technology, Health">
                        <small class="text-gray-500 font-12">Comma-separated topics</small>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white p-20 rounded-24">
                    <button type="submit" class="btn btn-primary w-100 mb-12">
                        <x-iconsax-bul-tick-circle class="icons mr-8" width="16px" height="16px"/>
                        {{ isset($question) ? 'Update' : 'Create' }} Question
                    </button>
                    <a href="{{ route('panel.question_bank.' . ($bankType ?? request('bank_type', 'mock')) . '.list') }}" class="btn btn-outline-secondary w-100">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</section>

@push('scripts_bottom')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const skillSelect = document.getElementById('skill');
    const typeSelect = document.getElementById('question_type');
    
    // Question types per skill
    const questionTypesBySkill = {
        'listening': ['multiple_choice', 'fill_blank', 'matching', 'short_answer', 'sentence_completion'],
        'reading': ['multiple_choice', 'fill_blank', 'matching', 'short_answer', 'sentence_completion', 'true_false_ng', 'heading_matching'],
        'writing': ['essay', 'report'],
        'speaking': ['speaking_part1', 'speaking_part2', 'speaking_part3']
    };
    
    // Filter question types based on selected skill
    function filterQuestionTypes() {
        const selectedSkill = skillSelect.value;
        const currentType = typeSelect.value;
        
        // Clear current options
        Array.from(typeSelect.options).forEach(option => {
            if (option.value) {
                option.style.display = 'none';
            }
        });
        
        // Show only relevant types
        if (selectedSkill && questionTypesBySkill[selectedSkill]) {
            const validTypes = questionTypesBySkill[selectedSkill];
            Array.from(typeSelect.options).forEach(option => {
                if (validTypes.includes(option.value)) {
                    option.style.display = 'block';
                }
            });
            
            // Reset if current type is not valid for skill
            if (!validTypes.includes(currentType)) {
                typeSelect.value = '';
            }
        }
        
        updateConditionalFields();
    }
    
    function updateConditionalFields() {
        const selectedSkill = skillSelect.value;
        const selectedType = typeSelect.value;
        
        // Hide all conditional fields first
        document.querySelectorAll('.conditional-field').forEach(field => {
            field.style.display = 'none';
        });
        
        // Show fields based on skill
        if (selectedSkill) {
            document.querySelectorAll(`[data-skills*="${selectedSkill}"]`).forEach(field => {
                field.style.display = 'block';
            });
        }
        
        // Show fields based on type
        if (selectedType) {
            document.querySelectorAll(`[data-types*="${selectedType}"]`).forEach(field => {
                field.style.display = 'block';
            });
        }
    }
    
    skillSelect.addEventListener('change', filterQuestionTypes);
    typeSelect.addEventListener('change', updateConditionalFields);
    
    // Initialize Summernote for Reading passage
    $('.summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
    
    // Initial update
    filterQuestionTypes();
});
</script>
@endpush
@endsection
