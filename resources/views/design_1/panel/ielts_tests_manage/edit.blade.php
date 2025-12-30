@extends('design_1.panel.layouts.panel')

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="section-title">Edit: {{ $test->title }}</h1>
        <div>
            <a href="{{ route('panel.my_ielts_tests.sections', $test->id) }}" class="btn btn-primary mr-10">
                <i class="fas fa-list mr-5"></i>Manage Sections
            </a>
            <a href="{{ route('panel.my_ielts_tests') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-5"></i>Back
            </a>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="row mb-20">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="text-gray font-12">Sections</div>
                <div class="font-24 font-weight-bold text-primary">{{ $test->sections->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="text-gray font-12">Questions</div>
                <div class="font-24 font-weight-bold text-info">
                    {{ $test->sections->sum(function($s) { return $s->questions->count(); }) }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="text-gray font-12">Attempts</div>
                <div class="font-24 font-weight-bold text-success">{{ $test->attempts->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="text-gray font-12">Status</div>
                <div class="font-16 font-weight-bold">
                    <span class="status-badge status-{{ $test->status }}">
                        {{ ucfirst(str_replace('_', ' ', $test->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('panel.my_ielts_tests.update', $test->id) }}" method="POST">
        @csrf

        <div class="form-section">
            <h3 class="font-16 font-weight-bold mb-20">Basic Information</h3>

            <div class="form-group">
                <label class="input-label">Test Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $test->title) }}" required>
            </div>

            <div class="form-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $test->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Test Type</label>
                        <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $test->type)) }}" disabled>
                        <small class="text-muted">Type cannot be changed after creation</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Total Duration (minutes) *</label>
                        <input type="number" name="total_duration" class="form-control"
                               value="{{ old('total_duration', $test->total_duration) }}" required min="1">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="font-16 font-weight-bold mb-20">Target Band</h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Minimum Band</label>
                        <input type="number" name="target_band_min" class="form-control"
                               value="{{ old('target_band_min', $test->target_band_min) }}" min="1" max="9" step="0.5">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Maximum Band</label>
                        <input type="number" name="target_band_max" class="form-control"
                               value="{{ old('target_band_max', $test->target_band_max) }}" min="1" max="9" step="0.5">
                    </div>
                </div>
            </div>
        </div>

        {{-- Practice Settings (if applicable) --}}
        @if($test->type === 'practice_test')
            <div class="form-section">
                <h3 class="font-16 font-weight-bold mb-20">Practice Settings</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label">Practice Mode</label>
                            <select name="practice_mode" class="form-control">
                                <option value="timed" {{ $test->practice_mode === 'timed' ? 'selected' : '' }}>Timed</option>
                                <option value="untimed" {{ $test->practice_mode === 'untimed' ? 'selected' : '' }}>Untimed</option>
                                <option value="exam_mode" {{ $test->practice_mode === 'exam_mode' ? 'selected' : '' }}>Exam Mode</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label">Practice Category</label>
                            <select name="practice_category_id" class="form-control">
                                <option value="">-- None --</option>
                                @foreach($categories as $skill => $cats)
                                    <optgroup label="{{ ucfirst($skill) }}">
                                        @foreach($cats as $cat)
                                            <option value="{{ $cat->id }}" {{ $test->practice_category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="d-flex align-items-center justify-content-between mt-30">
            <a href="{{ route('panel.my_ielts_tests') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-5"></i>Back to My Tests
            </a>
            
            <div>
                @if($test->status === 'draft' || $test->status === 'rejected')
                    <button type="submit" class="btn btn-primary mr-10">
                        <i class="fas fa-save mr-5"></i>Save Changes
                    </button>
                    
                    @if($test->sections->count() > 0)
                        <a href="{{ route('panel.my_ielts_tests.submit_approval', $test->id) }}" 
                           class="btn btn-success"
                           onclick="return confirm('Submit this test for approval? You won\'t be able to edit it until it\'s reviewed.')">
                            <i class="fas fa-paper-plane mr-5"></i>Submit for Approval
                        </a>
                    @else
                        <button type="button" class="btn btn-secondary" disabled title="Add sections first">
                            <i class="fas fa-paper-plane mr-5"></i>Submit for Approval
                        </button>
                    @endif
                @elseif($test->status === 'pending_approval')
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-clock mr-5"></i>This test is pending approval by Manager/CEO
                    </div>
                @elseif($test->status === 'approved')
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-5"></i>Save Changes
                    </button>
                    <span class="badge badge-success ml-10">Approved</span>
                @endif
            </div>
        </div>
    </form>
</section>
@endsection
