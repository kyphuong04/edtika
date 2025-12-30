@extends('design_1.panel.layouts.panel')

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <div>
            <h1 class="section-title">Add New Section</h1>
            <p class="text-gray">{{ $test->title }}</p>
        </div>
        <a href="{{ route('panel.my_ielts_tests.sections', $test->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-5"></i>Back to Sections
        </a>
    </div>

    <form action="{{ route('panel.my_ielts_tests.sections.store', $test->id) }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-20">Section Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Skill *</label>
                                    <select name="skill" class="form-control @error('skill') is-invalid @enderror" required>
                                        <option value="">Select skill...</option>
                                        <option value="listening" {{ old('skill') === 'listening' ? 'selected' : '' }}>Listening</option>
                                        <option value="reading" {{ old('skill') === 'reading' ? 'selected' : '' }}>Reading</option>
                                        <option value="writing" {{ old('skill') === 'writing' ? 'selected' : '' }}>Writing</option>
                                        <option value="speaking" {{ old('skill') === 'speaking' ? 'selected' : '' }}>Speaking</option>
                                    </select>
                                    @error('skill')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Section Number *</label>
                                    <input type="number" name="section_number" class="form-control" value="{{ old('section_number', 1) }}" required>
                                    <small class="text-muted">Internal section identifier (e.g., 1, 2, 3)</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" placeholder="e.g., Part 1 - Social Conversation" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Instructions</label>
                            <textarea name="instructions" class="form-control" rows="3" 
                                      placeholder="Instructions for students...">{{ old('instructions') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Question Start *</label>
                                    <input type="number" name="question_start" class="form-control" 
                                           value="{{ old('question_start', 1) }}" required min="1">
                                    <small class="text-muted">First question number</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Question End *</label>
                                    <input type="number" name="question_end" class="form-control" 
                                           value="{{ old('question_end', 10) }}" required min="1">
                                    <small class="text-muted">Last question number</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Duration (minutes)</label>
                                    <input type="number" name="duration_minutes" class="form-control" 
                                           value="{{ old('duration_minutes', 20) }}" min="1">
                                </div>
                            </div>
                        </div>

                        {{-- Listening Transcript --}}
                        <div class="form-group" id="listening-transcript-field" style="display: none;">
                            <label>Listening Transcript / Audio Script *</label>
                            <textarea name="listening_transcript" class="form-control" rows="10" 
                                      placeholder="Enter the audio transcript here... This will be shown to students for reference after completing the listening section.">{{ old('listening_transcript') }}</textarea>
                            <small class="text-muted">Type or paste the audio script/transcript for the listening section</small>
                        </div>

                        {{-- Reading Passage / Writing Prompt --}}
                        <div class="form-group" id="passage-text-field">
                            <label>Reading Passage / Writing Prompt</label>
                            <textarea name="passage_text" class="form-control" rows="10" 
                                      placeholder="Enter the reading passage or writing prompt here...">{{ old('passage_text') }}</textarea>
                            <small class="text-muted">Required for Reading and Writing sections</small>
                        </div>

                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" 
                                   value="{{ old('sort_order', $test->sections->count() + 1) }}" min="1">
                            <small class="text-muted">Order in which this section appears in the test</small>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-5"></i>
                    <strong>Note:</strong> For advanced features like audio/image uploads, please use the 
                    <a href="{{ getAdminPanelUrl('/ielts-tests/' . $test->id . '/sections') }}" target="_blank">Admin Panel</a>. 
                    After creating the section, you can add questions there.
                </div>

                <div class="d-flex align-items-center justify-content-end">
                    <a href="{{ route('panel.my_ielts_tests.sections', $test->id) }}" class="btn btn-secondary mr-10">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus mr-5"></i>Create Section
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>

@push('scripts_bottom')
<script>
$(document).ready(function() {
    // Show/hide fields based on skill selection
    $('select[name="skill"]').on('change', function() {
        var skill = $(this).val();
        
        if (skill === 'listening') {
            $('#listening-transcript-field').slideDown();
            $('#passage-text-field').slideUp();
        } else if (skill === 'reading' || skill === 'writing') {
            $('#listening-transcript-field').slideUp();
            $('#passage-text-field').slideDown();
        } else {
            $('#listening-transcript-field').slideUp();
            $('#passage-text-field').slideUp();
        }
    });
    
    // Prevent double form submission
    $('form').on('submit', function() {
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        
        // Disable submit button
        $submitBtn.prop('disabled', true);
        
        // Change button text to show processing
        var originalText = $submitBtn.html();
        $submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Creating...');
        
        // Re-enable after 3 seconds (in case of validation errors)
        setTimeout(function() {
            $submitBtn.prop('disabled', false);
            $submitBtn.html(originalText);
        }, 3000);
    });
});
</script>
@endpush
@endsection
