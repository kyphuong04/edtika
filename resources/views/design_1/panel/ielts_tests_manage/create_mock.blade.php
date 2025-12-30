@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
.form-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
    border: 1px solid #e5e7eb;
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}

.section-number {
    width: 32px;
    height: 32px;
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-right: 12px;
}

.hierarchical-selector {
    padding-left: 20px;
    border-left: 3px solid #e5e7eb;
    margin-left: 10px;
    margin-top: 15px;
}

.hierarchical-selector.active {
    border-left-color: #3b82f6;
}
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="section-title">
            <i class="fas fa-clipboard-list mr-10"></i>
            Create Mock Test
        </h1>
        <a href="{{ route('panel.my_ielts_tests.create') }}" class="btn btn-sm btn-gray">
            <i class="fas fa-arrow-left mr-5"></i>Back
        </a>
    </div>

    {{-- Info Alert --}}
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-5"></i>
        <strong>Mock Test Requirements:</strong> Full IELTS simulation with all 4 skills (Listening, Reading, Writing, Speaking). Total duration: 165 minutes.
    </div>

    <form action="{{ route('panel.my_ielts_tests.store_mock') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="mock">

        {{-- 1. Basic Information --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-number">1</span>
                <h3 class="font-16 font-weight-bold mb-0">Basic Information</h3>
            </div>

            <div class="form-group">
                <label class="input-label">Test Title *</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" required placeholder="e.g. IELTS Full Mock Test - December 2024">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="3"
                          placeholder="Brief description of this mock test...">{{ old('description') }}</textarea>
            </div>
        </div>

        {{-- 2. Test Format --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-number">2</span>
                <h3 class="font-16 font-weight-bold mb-0">Test Format</h3>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Test Type * <small class="text-muted">(Content)</small></label>
                        <select name="format" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <option value="academic" {{ old('format') === 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="general" {{ old('format') === 'general' ? 'selected' : '' }}>General Training</option>
                            <option value="both" {{ old('format') === 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                        <small class="text-muted">Academic or General Training content</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Delivery Method * <small class="text-muted">(Format)</small></label>
                        <select name="delivery_method" class="form-control" required>
                            <option value="">-- Select Method --</option>
                            <option value="computer" {{ old('delivery_method') === 'computer' ? 'selected' : '' }}>Computer-based</option>
                            <option value="paper" {{ old('delivery_method') === 'paper' ? 'selected' : '' }}>Paper-based</option>
                            <option value="both" {{ old('delivery_method') === 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                        <small class="text-muted">How the test will be taken</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Difficulty Level</label>
                        <select name="difficulty_level" class="form-control">
                            <option value="mixed" {{ old('difficulty_level') === 'mixed' ? 'selected' : '' }}>Mixed Difficulty</option>
                            <option value="beginner" {{ old('difficulty_level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('difficulty_level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('difficulty_level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Target Band</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="number" name="target_band_min" class="form-control" 
                                       min="0" max="9" step="0.5" placeholder="Min" value="{{ old('target_band_min', 5.0) }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="target_band_max" class="form-control" 
                                       min="0" max="9" step="0.5" placeholder="Max" value="{{ old('target_band_max', 8.0) }}">
                            </div>
                        </div>
                        <small class="text-muted">e.g., 5.0 - 8.0</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Link to Course (Optional - Hierarchical) --}}
        <div class="form-section">
            <div class="section-header">
                <span class="section-number">3</span>
                <h3 class="font-16 font-weight-bold mb-0">Link to Course (Optional)</h3>
            </div>

            {{-- Standalone --}}
            <div class="custom-control custom-radio mb-15">
                <input type="radio" id="standalone" name="access_type" class="custom-control-input" value="standalone" checked>
                <label class="custom-control-label" for="standalone">
                    <strong>Standalone Test</strong> - Free for all users
                </label>
            </div>

            {{-- Bundle Linking --}}
            <div class="custom-control custom-radio mb-15">
                <input type="radio" id="link_bundle" name="access_type" class="custom-control-input" value="bundle">
                <label class="custom-control-label" for="link_bundle">
                    <strong>Link to Bundle</strong> - Test appears at end of bundle
                </label>
            </div>

            <div id="bundle_selector" class="hierarchical-selector" style="display: none;">
                <div class="form-group">
                    <label class="input-label">Select Bundle *</label>
                    <select name="bundle_id" id="bundle_select" class="form-control">
                        <option value="">-- Select Bundle --</option>
                        {{-- Will be populated dynamically --}}
                    </select>
                </div>

                {{-- Course in Bundle --}}
                <div class="custom-control custom-checkbox mb-10">
                    <input type="checkbox" class="custom-control-input" id="link_course">
                    <label class="custom-control-label" for="link_course">
                        Link to specific Course in Bundle → Test at end of course
                    </label>
                </div>

                <div id="course_selector" class="hierarchical-selector" style="display: none;">
                    <div class="form-group">
                        <label class="input-label">Select Course *</label>
                        <select name="webinar_id" id="course_select" class="form-control">
                            <option value="">-- Select Course --</option>
                        </select>
                    </div>

                    {{-- Lesson in Course --}}
                    <div class="custom-control custom-checkbox mb-10">
                        <input type="checkbox" class="custom-control-input" id="link_lesson">
                        <label class="custom-control-label" for="link_lesson">
                            Link to specific Lesson → Test at end of lesson
                        </label>
                    </div>

                    <div id="lesson_selector" class="hierarchical-selector" style="display: none;">
                        <div class="form-group">
                            <label class="input-label">Select Lesson *</label>
                            <select name="lesson_id" id="lesson_select" class="form-control">
                                <option value="">-- Select Lesson --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('panel.my_ielts_tests.create') }}" class="btn btn-gray">
                <i class="fas fa-times mr-5"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check mr-5"></i>Create Mock Test
            </button>
        </div>
    </form>
</section>

@push('scripts_bottom')
<script>
$(document).ready(function() {
    // Hierarchical selectors
    $('input[name="access_type"]').change(function() {
        if ($(this).val() === 'bundle') {
            $('#bundle_selector').slideDown().addClass('active');
        } else {
            $('#bundle_selector').slideUp().removeClass('active');
            $('#course_selector, #lesson_selector').slideUp();
        }
    });

    $('#link_course').change(function() {
        if ($(this).is(':checked')) {
            $('#course_selector').slideDown().addClass('active');
        } else {
            $('#course_selector').slideUp().removeClass('active');
            $('#lesson_selector').slideUp();
        }
    });

    $('#link_lesson').change(function() {
        if ($(this).is(':checked')) {
            $('#lesson_selector').slideDown().addClass('active');
        } else {
            $('#lesson_selector').slideUp().removeClass('active');
        }
    });
});
</script>
@endpush
@endsection
