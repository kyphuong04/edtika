@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit IELTS Test</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>{{ $test->title }}</h4>
                <div class="card-header-action">
                    <span class="badge badge-{{ $test->status === 'published' ? 'success' : ($test->status === 'pending_approval' ? 'warning' : 'secondary') }}">
                        {{ ucfirst(str_replace('_', ' ', $test->status)) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ielts_tests.update', $test->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="input-label">Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $test->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $test->description) }}</textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Test type and format cannot be changed after creation. Current: <strong>{{ ucfirst($test->type) }} - {{ ucfirst($test->format) }}</strong>
                    </div>

                    @if($test->isPracticeTest())
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
                                                <option value="{{ $category->id }}" {{ $test->practice_category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
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
                                    <option value="untimed" {{ $test->practice_mode == 'untimed' ? 'selected' : '' }}>Untimed</option>
                                    <option value="timed" {{ $test->practice_mode == 'timed' ? 'selected' : '' }}>Timed</option>
                                    <option value="exam" {{ $test->practice_mode == 'exam' ? 'selected' : '' }}>Exam Mode</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="show_answers_immediately" class="custom-control-input" id="showAnswers" value="1" 
                                       {{ $test->show_answers_immediately ? 'checked' : '' }}>
                                <label class="custom-control-label" for="showAnswers">Show answers immediately after submission</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="allow_retake" class="custom-control-input" id="allowRetake" value="1"
                                       {{ $test->allow_retake ? 'checked' : '' }}>
                                <label class="custom-control-label" for="allowRetake">Allow unlimited retakes</label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <h5 class="mt-4 mb-3">Target Band</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">Minimum Band</label>
                                <select name="target_band_min" class="form-control">
                                    <option value="">No minimum</option>
                                    @for($i = 1.0; $i <= 9.0; $i += 0.5)
                                        <option value="{{ $i }}" {{ $test->target_band_min == $i ? 'selected' : '' }}>{{ $i }}</option>
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
                                        <option value="{{ $i }}" {{ $test->target_band_max == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Update Test
                        </button>
                        <a href="{{ route('admin.ielts_tests.sections', $test->id) }}" class="btn btn-success">
                            <i class="fas fa-list mr-2"></i>
                            Manage Sections
                        </a>
                        <a href="{{ route('admin.ielts_tests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Test Statistics --}}
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>{{ $test->sections->count() }}</h4>
                        <p class="text-gray-700  mb-0">Sections</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>{{ $test->sections->sum(function($s) { return $s->questions->count(); }) }}</h4>
                        <p class="text-gray-700 mb-0">Total Questions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4>{{ $test->attempts->count() }}</h4>
                        <p class="text-gray-700 mb-0">Attempts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
