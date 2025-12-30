@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    #multipleChoiceOptions {
        animation: fadeIn 0.3s;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <div>
            <h1 class="section-title">Edit Question</h1>
            <p class="text-gray">{{ $section->title }} (Q{{ $section->question_start }} - Q{{ $section->question_end }})</p>
        </div>
        <a href="{{ route('panel.my_ielts_tests.questions', [$test->id, $section->id]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-5"></i>Back to Questions
        </a>
    </div>

    <form action="{{ route('panel.my_ielts_tests.questions.update', [$test->id, $section->id, $question->id]) }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-body">
                <h5 class="mb-20">Question Information</h5>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Question Number *</label>
                            <input type="number" name="question_number" class="form-control @error('question_number') is-invalid @enderror" 
                                   value="{{ old('question_number', $question->question_number) }}" required 
                                   min="{{ $section->question_start }}" max="{{ $section->question_end }}">
                            <small class="text-muted">Between {{ $section->question_start }} and {{ $section->question_end }}</small>
                            @error('question_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Question Type *</label>
                            <select name="question_type" class="form-control" id="questionType" required>
                                <option value="">Select type...</option>
                                <option value="multiple_choice" {{ old('question_type', $question->question_type) === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="fill_blank" {{ old('question_type', $question->question_type) === 'fill_blank' ? 'selected' : '' }}>Fill in the Blank</option>
                                <option value="true_false" {{ old('question_type', $question->question_type) === 'true_false' ? 'selected' : '' }}>True/False</option>
                                <option value="matching" {{ old('question_type', $question->question_type) === 'matching' ? 'selected' : '' }}>Matching</option>
                                <option value="short_answer" {{ old('question_type', $question->question_type) === 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                <option value="essay" {{ old('question_type', $question->question_type) === 'essay' ? 'selected' : '' }}>Essay</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Points *</label>
                            <input type="number" name="points" class="form-control" value="{{ old('points', $question->points) }}" required min="0" step="0.5">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Question Text *</label>
                    <textarea name="question_text" class="form-control @error('question_text') is-invalid @enderror" 
                              rows="4" required placeholder="Enter the question...">{{ old('question_text', $question->question_text) }}</textarea>
                    @error('question_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Instructions (Optional)</label>
                    <textarea name="instruction" class="form-control" rows="2" 
                              placeholder="Special instructions for this question...">{{ old('instruction', $question->instruction) }}</textarea>
                </div>

                <div id="multipleChoiceOptions" style="display: none;">
                    <h6 class="mb-15">Answer Options</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Option A</label>
                                <input type="text" name="option_a" class="form-control" value="{{ old('option_a') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Option B</label>
                                <input type="text" name="option_b" class="form-control" value="{{ old('option_b') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Option C</label>
                                <input type="text" name="option_c" class="form-control" value="{{ old('option_c') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Option D</label>
                                <input type="text" name="option_d" class="form-control" value="{{ old('option_d') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Correct Answer</label>
                            <input type="text" name="correct_answer" class="form-control" value="{{ old('correct_answer', $question->correct_answer) }}">
                            <small class="text-muted">For auto-gradable questions only</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Accept Synonyms (comma-separated)</label>
                            <input type="text" name="accept_synonyms" class="form-control" value="{{ old('accept_synonyms', $question->accept_synonyms) }}" 
                                   placeholder="e.g., happy, glad, joyful">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox mt-15">
                            <input type="checkbox" name="auto_gradable" class="custom-control-input" id="autoGrade" value="1" {{ old('auto_gradable', $question->auto_gradable) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="autoGrade">Auto-gradable</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox mt-15">
                            <input type="checkbox" name="case_sensitive" class="custom-control-input" id="caseSensitive" value="1" {{ old('case_sensitive', $question->case_sensitive) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="caseSensitive">Case sensitive</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Max Words (for text answers)</label>
                            <input type="number" name="max_words" class="form-control" value="{{ old('max_words', $question->max_words) }}" min="1">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Explanation (for practice tests)</label>
                    <textarea name="explanation" class="form-control" rows="3" 
                              placeholder="Explain the correct answer...">{{ old('explanation', $question->explanation) }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-end mt-20">
            <a href="{{ route('panel.my_ielts_tests.questions', [$test->id, $section->id]) }}" class="btn btn-secondary mr-10">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-5"></i>Update Question
            </button>
        </div>
    </form>
</section>
@endsection

@push('scripts_bottom')
<script>
document.getElementById('questionType').addEventListener('change', function() {
    const optionsDiv = document.getElementById('multipleChoiceOptions');
    if (this.value === 'multiple_choice') {
        optionsDiv.style.display = 'block';
    } else {
        optionsDiv.style.display = 'none';
    }
});

// On load
if (document.getElementById('questionType').value === 'multiple_choice') {
    document.getElementById('multipleChoiceOptions').style.display = 'block';
}
</script>
@endpush
