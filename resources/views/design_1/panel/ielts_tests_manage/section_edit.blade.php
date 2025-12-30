@extends('design_1.panel.layouts.panel')

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="section-title">Edit Section: {{ $section->title }}</h1>
        <a href="{{ route('panel.my_ielts_tests.sections', $test->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-5"></i>Back to Sections
        </a>
    </div>

    <form action="{{ route('panel.my_ielts_tests.sections.update', [$test->id, $section->id]) }}" method="POST">
        @csrf

        <div class="form-section">
            <h3 class="font-16 font-weight-bold mb-20">Section Information</h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Skill *</label>
                        <select name="skill" class="form-control" required>
                            <option value="listening" {{ $section->skill === 'listening' ? 'selected' : '' }}>Listening</option>
                            <option value="reading" {{ $section->skill === 'reading' ? 'selected' : '' }}>Reading</option>
                            <option value="writing" {{ $section->skill === 'writing' ? 'selected' : '' }}>Writing</option>
                            <option value="speaking" {{ $section->skill === 'speaking' ? 'selected' : '' }}>Speaking</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Duration (minutes) *</label>
                        <input type="number" name="duration_minutes" class="form-control" 
                               value="{{ old('duration_minutes', $section->duration_minutes) }}" required min="1">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="input-label">Section Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $section->title) }}" required>
            </div>

            <div class="form-group">
                <label class="input-label">Instructions</label>
                <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $section->instructions) }}</textarea>
            </div>

            <div class="form-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $section->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" 
                               value="{{ old('sort_order', $section->sort_order) }}" min="1">
                    </div>
                </div>
            </div>
        </div>

        @if(in_array($section->skill, ['reading', 'writing']))
            <div class="form-section">
                <h3 class="font-16 font-weight-bold mb-20">Passage/Prompt</h3>
                <div class="form-group">
                    <label class="input-label">Passage Text</label>
                    <textarea name="passage_text" class="form-control" rows="8">{{ old('passage_text', $section->passage_text) }}</textarea>
                </div>
            </div>
        @endif

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-5"></i>
            <strong>Questions:</strong> This section has {{ $section->questions->count() }} question(s). 
            To manage questions with advanced features, please use the 
            <a href="{{ getAdminPanelUrl('/ielts-tests/' . $test->id . '/sections/' . $section->id . '/edit') }}" target="_blank">Admin Panel</a>.
        </div>

        <div class="d-flex align-items-center justify-content-end">
            <a href="{{ route('panel.my_ielts_tests.sections', $test->id) }}" class="btn btn-secondary mr-10">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-5"></i>Update Section
            </button>
        </div>
    </form>
</section>
@endsection
