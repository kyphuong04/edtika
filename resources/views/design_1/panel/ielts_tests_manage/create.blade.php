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
    .skill-checkbox {
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .skill-checkbox:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .skill-checkbox input:checked + label {
        color: #3b82f6;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="section-title">Create IELTS Test</h1>
        <a href="{{ route('panel.my_ielts_tests') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-5"></i>Back to My Tests
        </a>
    </div>

    <form action="{{ route('panel.my_ielts_tests.store') }}" method="POST">
        @csrf

        {{-- Basic Information --}}
        <div class="form-section">
            <h3 class="font-16 font-weight-bold mb-20">Basic Information</h3>

            <div class="form-group">
                <label class="input-label">Test Title *</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" required placeholder="e.g. IELTS Academic Mock Test 1">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="3"
                          placeholder="Brief description of this test...">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Test Type *</label>
                        <select name="type" id="testType" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <option value="mock" {{ old('type') === 'mock' ? 'selected' : '' }}>
                                Mock Test (Full 4-skill test)
                            </option>
                            <option value="practice" {{ old('type') === 'practice' ? 'selected' : '' }}>
                                Practice Test (Select skills)
                            </option>
                        </select>
                        <small class="text-muted">Duration is auto-calculated from sections.</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Test Format *</label>
                        <select name="format" class="form-control" required>
                            <option value="">-- Select Format --</option>
                            <option value="computer" {{ old('format') === 'computer' ? 'selected' : '' }}>Computer-based</option>
                            <option value="paper" {{ old('format') === 'paper' ? 'selected' : '' }}>Paper-based</option>
                            <option value="both" {{ old('format') === 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                        <small class="text-muted">Delivery format for this test.</small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    
                    {{-- Mock Test Info --}}
                    <div id="mockTestInfo" class="alert alert-info mt-15" style="display: none;">
                        <strong><i class="fas fa-info-circle mr-5"></i>Mock Test:</strong>
                        <ul class="mb-0 mt-10">
                            <li>Full IELTS simulation with all 4 skills (Listening, Reading, Writing, Speaking)</li>
                            <li>Typically 180 minutes (3 hours)</li>
                            <li>Used for comprehensive assessment</li>
                            <li>Students take in exam-like conditions</li>
                        </ul>
                    </div>
                    
                    {{-- Practice Test Info --}}
                    <div id="practiceTestInfo" class="alert alert-success mt-15" style="display: none;">
                        <strong><i class="fas fa-info-circle mr-5"></i>Practice Test:</strong>
                        <ul class="mb-0 mt-10">
                            <li>Focus on specific skills (choose 1 or more)</li>
                            <li>Flexible duration (set per section)</li>
                            <li>Can be categorized by topic/difficulty</li>
                            <li>Ideal for targeted practice</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Skills Selection (for practice tests) --}}
        <div class="form-section" id="skillsSection" style="display: none;">
            <h3 class="font-16 font-weight-bold mb-20">Select Skills (Practice Test Only)</h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="skill-checkbox">
                        <input type="checkbox" name="has_listening" id="listening" value="1"
                               {{ old('has_listening') ? 'checked' : '' }}>
                        <label for="listening" class="mb-0 ml-10">
                            <i class="fas fa-headphones mr-5"></i>Listening
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="skill-checkbox">
                        <input type="checkbox" name="has_reading" id="reading" value="1"
                               {{ old('has_reading') ? 'checked' : '' }}>
                        <label for="reading" class="mb-0 ml-10">
                            <i class="fas fa-book-open mr-5"></i>Reading
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="skill-checkbox">
                        <input type="checkbox" name="has_writing" id="writing" value="1"
                               {{ old('has_writing') ? 'checked' : '' }}>
                        <label for="writing" class="mb-0 ml-10">
                            <i class="fas fa-pen mr-5"></i>Writing
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="skill-checkbox">
                        <input type="checkbox" name="has_speaking" id="speaking" value="1"
                               {{ old('has_speaking') ? 'checked' : '' }}>
                        <label for="speaking" class="mb-0 ml-10">
                            <i class="fas fa-microphone mr-5"></i>Speaking
                        </label>
                    </div>
                </div>
            </div>

            <div class="row mt-20">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Practice Mode</label>
                        <select name="practice_mode" class="form-control">
                            <option value="timed">Timed (Recommended time)</option>
                            <option value="untimed">Untimed (No limit)</option>
                            <option value="exam_mode">Exam Mode (Strict timing)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Practice Category (Optional)</label>
                        <select name="practice_category_id" class="form-control">
                            <option value="">-- None --</option>
                            @foreach($categories as $skill => $cats)
                                <optgroup label="{{ ucfirst($skill) }}">
                                    @foreach($cats as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Target Band --}}
        <div class="form-section">
            <h3 class="font-16 font-weight-bold mb-20">Target Band (Optional)</h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Minimum Band</label>
                        <input type="number" name="target_band_min" class="form-control"
                               value="{{ old('target_band_min') }}" min="1" max="9" step="0.5"
                               placeholder="e.g. 6.0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Maximum Band</label>
                        <input type="number" name="target_band_max" class="form-control"
                               value="{{ old('target_band_max') }}" min="1" max="9" step="0.5"
                               placeholder="e.g. 7.5">
                    </div>
                </div>
            </div>
        </div>

        {{-- Access Settings --}}
        <div class="form-section">
            <h3 class="font-16 font-weight-bold mb-20">Access Settings</h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="is_free" class="custom-control-input" id="isFree" value="1"
                                   {{ old('is_free') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="isFree">
                                <strong>Free Access</strong> - Students can take without purchase
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="diagnosticOption" style="display: none;">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="is_lead_test" class="custom-control-input" id="isDiagnostic" value="1"
                                   {{ old('is_lead_test') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="isDiagnostic">
                                <strong>Diagnostic Test</strong> - Entry level assessment
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="require_enrollment" class="custom-control-input" id="requireEnrollment" value="1"
                                   {{ old('require_enrollment') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="requireEnrollment">
                                <strong>Require Course Enrollment</strong> - Link to a course
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="courseSelect" style="display: none;">
                    <div class="form-group">
                        <label class="input-label">Link to Course</label>
                        <select name="webinar_id" class="form-control">
                            <option value="">-- Select Course --</option>
                            @if(isset($courses))
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('webinar_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted">Students must complete this course to take the test</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex align-items-center justify-content-end">
            <a href="{{ route('panel.my_ielts_tests') }}" class="btn btn-secondary mr-10">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus mr-5"></i>Create Test & Add Sections
            </button>
        </div>
    </form>
</section>
@endsection

@push('scripts_bottom')
<script>
    const testTypeSelect = document.getElementById('testType');
    const skillsSection = document.getElementById('skillsSection');
    const mockTestInfo = document.getElementById('mockTestInfo');
    const practiceTestInfo = document.getElementById('practiceTestInfo');
    const diagnosticOption = document.getElementById('diagnosticOption');
    const requireEnrollment = document.getElementById('requireEnrollment');
    const courseSelect = document.getElementById('courseSelect');

    testTypeSelect.addEventListener('change', function() {
        if (this.value === 'practice') {
            skillsSection.style.display = 'block';
            mockTestInfo.style.display = 'none';
            practiceTestInfo.style.display = 'block';
            diagnosticOption.style.display = 'none';
        } else if (this.value === 'mock') {
            skillsSection.style.display = 'none';
            mockTestInfo.style.display = 'block';
            practiceTestInfo.style.display = 'none';
            diagnosticOption.style.display = 'block';
        } else {
            skillsSection.style.display = 'none';
            mockTestInfo.style.display = 'none';
            practiceTestInfo.style.display = 'none';
            diagnosticOption.style.display = 'none';
        }
    });

    // Toggle course select when require enrollment is checked
    requireEnrollment.addEventListener('change', function() {
        courseSelect.style.display = this.checked ? 'block' : 'none';
    });

    // On load
    if (testTypeSelect.value === 'practice') {
        skillsSection.style.display = 'block';
        practiceTestInfo.style.display = 'block';
    } else if (testTypeSelect.value === 'mock') {
        mockTestInfo.style.display = 'block';
        diagnosticOption.style.display = 'block';
    }
    
    if (requireEnrollment.checked) {
        courseSelect.style.display = 'block';
    }
</script>
@endpush

