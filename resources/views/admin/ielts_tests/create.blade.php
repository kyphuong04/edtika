@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create IELTS Test</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
            <div class="breadcrumb-item">Create</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Test Information</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ielts_tests.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="input-label">Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">Test Type *</label>
                                <select name="type" class="form-control" id="testType" required>
                                    <option value="">Select type...</option>
                                    <option value="mock" {{ old('type') == 'mock' ? 'selected' : '' }}>Mock Test (Full 4 Skills)</option>
                                    <option value="practice" {{ old('type') == 'practice' ? 'selected' : '' }}>Practice Test (Flexible)</option>
                                    <option value="diagnostic" {{ old('type') == 'diagnostic' ? 'selected' : '' }}>Diagnostic Test</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">Format *</label>
                                <select name="format" class="form-control" required>
                                    <option value="">Select format...</option>
                                    <option value="academic" {{ old('format') == 'academic' ? 'selected' : '' }}>Academic</option>
                                    <option value="general" {{ old('format') == 'general' ? 'selected' : '' }}>General Training</option>
                                    <option value="both" {{ old('format') == 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">Difficulty Level</label>
                                <select name="difficulty_level" class="form-control">
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate" selected>Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="mixed">Mixed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="practiceOptions" style="display: none;">
                        <h5 class="mt-4 mb-3">Practice Test Options</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label">Practice Category</label>
                                    <select name="practice_category_id" class="form-control">
                                        <option value="">Select category...</option>
                                        @foreach($practiceCategories as $skill => $categories)
                                            <optgroup label="{{ ucfirst($skill) }}">
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label">Practice Mode</label>
                                    <select name="practice_mode" class="form-control">
                                        <option value="untimed">Untimed</option>
                                        <option value="timed">Timed</option>
                                        <option value="exam">Exam Mode</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="show_answers_immediately" class="custom-control-input" id="showAnswers" value="1">
                                    <label class="custom-control-label" for="showAnswers">Show answers immediately after submission</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="allow_retake" class="custom-control-input" id="allowRetake" value="1" checked>
                                    <label class="custom-control-label" for="allowRetake">Allow unlimited retakes</label>
                                </div>
                            </div>
                        </div>

                        <h6 class="mt-4 mb-3">Select Skills</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="has_listening" class="custom-control-input" id="hasListening" value="1">
                                    <label class="custom-control-label" for="hasListening">Listening</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="has_reading" class="custom-control-input" id="hasReading" value="1">
                                    <label class="custom-control-label" for="hasReading">Reading</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="has_writing" class="custom-control-input" id="hasWriting" value="1">
                                    <label class="custom-control-label" for="hasWriting">Writing</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="has_speaking" class="custom-control-input" id="hasSpeaking" value="1">
                                    <label class="custom-control-label" for="hasSpeaking">Speaking</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="mockInfo" style="display: none;">
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle mr-2"></i>
                            Mock tests automatically include all 4 skills (Listening, Reading, Writing, Speaking) with fixed durations:
                            Listening (30 min), Reading (60 min), Writing (60 min), Speaking (15 min)
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Target Band</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">Minimum Band</label>
                                <select name="target_band_min" class="form-control">
                                    <option value="">No minimum</option>
                                    @for($i = 1.0; $i <= 9.0; $i += 0.5)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">Maximum Band</label>
                                <select name="target_band_max" class="form-control">
                                    <option value="">No maximum</option>
                                    @for($i = 1.0; $i <= 9.0; $i += 0.5)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Create Test
                        </button>
                        <a href="{{ route('admin.ielts_tests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts_bottom')
<script>
    document.getElementById('testType').addEventListener('change', function() {
        const practiceOptions = document.getElementById('practiceOptions');
        const mockInfo = document.getElementById('mockInfo');
        
        if (this.value === 'practice') {
            practiceOptions.style.display = 'block';
            mockInfo.style.display = 'none';
        } else if (this.value === 'mock') {
            practiceOptions.style.display = 'none';
            mockInfo.style.display = 'block';
        } else {
            practiceOptions.style.display = 'none';
            mockInfo.style.display = 'none';
        }
    });
</script>
@endpush
