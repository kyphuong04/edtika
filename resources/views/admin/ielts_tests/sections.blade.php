@extends('admin.layouts.app')

@push('libraries_top')
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manage Sections</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
            <div class="breadcrumb-item">Sections</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header justify-content-between">
                <div>
                    <h4 class="mb-0">{{ $test->title }}</h4>
                    <p class="text-gray mb-0 mt-1">Configure test sections and content</p>
                </div>
                <div>
                    <a href="{{ route('admin.ielts_tests.edit', $test->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Test
                    </a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSectionModal">
                        <i class="fas fa-plus mr-2"></i>
                        Add Section
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($test->sections->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-gray mb-3"></i>
                        <h5>No sections yet</h5>
                        <p class="text-gray">Create your first section to start adding questions</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Title</th>
                                    <th>Skill</th>
                                    <th>Questions</th>
                                    <th>Duration</th>
                                    <th>Media</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($test->sections->sortBy('sort_order') as $section)
                                <tr>
                                    <td>{{ $section->sort_order }}</td>
                                    <td>
                                        <strong>{{ $section->title }}</strong>
                                        <small class="d-block text-gray">Q{{ $section->question_start }} - Q{{ $section->question_end }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $section->skill === 'listening' ? 'info' : 
                                            ($section->skill === 'reading' ? 'success' : 
                                            ($section->skill === 'writing' ? 'warning' : 'danger')) 
                                        }}">
                                            {{ ucfirst($section->skill) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $section->questions->count() }}</strong> questions
                                    </td>
                                    <td>
                                        @if($section->duration_minutes)
                                            {{ $section->duration_minutes }} min
                                        @else
                                            <span class="text-gray">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($section->audio_file)
                                            <i class="fas fa-volume-up text-primary" title="Has audio"></i>
                                        @endif
                                        @if($section->image_file)
                                            <i class="fas fa-image text-success" title="Has image"></i>
                                        @endif
                                        @if($section->passage_text)
                                            <i class="fas fa-file-alt text-info" title="Has passage"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.ielts_tests.questions', $section->id) }}" 
                                               class="btn btn-sm btn-primary" title="Manage Questions">
                                                <i class="fas fa-question-circle"></i>
                                                Questions
                                            </a>
                                            <button type="button" class="btn btn-sm btn-warning" title="Edit" 
                                                    onclick="editSection({{ $section->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.ielts_tests.sections.delete', $section->id) }}" 
                                               class="btn btn-sm btn-danger" title="Delete"
                                               onclick="return confirm('Delete this section and all its questions?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Progress Card --}}
        @if($test->isMockTest())
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Mock Test Requirements</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <i class="fas fa-headphones fa-2x {{ $test->sections->where('skill', 'listening')->count() > 0 ? 'text-success' : 'text-gray' }}"></i>
                            <p class="mb-0 mt-2">Listening</p>
                            <small class="text-gray">{{ $test->sections->where('skill', 'listening')->count() }} section(s)</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <i class="fas fa-book-open fa-2x {{ $test->sections->where('skill', 'reading')->count() > 0 ? 'text-success' : 'text-gray' }}"></i>
                            <p class="mb-0 mt-2">Reading</p>
                            <small class="text-gray">{{ $test->sections->where('skill', 'reading')->count() }} section(s)</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <i class="fas fa-pencil-alt fa-2x {{ $test->sections->where('skill', 'writing')->count() > 0 ? 'text-success' : 'text-gray' }}"></i>
                            <p class="mb-0 mt-2">Writing</p>
                            <small class="text-gray">{{ $test->sections->where('skill', 'writing')->count() }} section(s)</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <i class="fas fa-microphone fa-2x {{ $test->sections->where('skill', 'speaking')->count() > 0 ? 'text-success' : 'text-gray' }}"></i>
                            <p class="mb-0 mt-2">Speaking</p>
                            <small class="text-gray">{{ $test->sections->where('skill', 'speaking')->count() }} section(s)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- Add Section Modal --}}
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Section</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.ielts_tests.sections.store', $test->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Skill *</label>
                                <select name="skill" class="form-control" required>
                                    <option value="">Select skill...</option>
                                    <option value="listening">Listening</option>
                                    <option value="reading">Reading</option>
                                    <option value="writing">Writing</option>
                                    <option value="speaking">Speaking</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Section Number *</label>
                                <input type="number" name="section_number" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Part 1 - Social Conversation" required>
                    </div>

                    <div class="form-group">
                        <label>Instructions</label>
                        <textarea name="instructions" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Question Start *</label>
                                <input type="number" name="question_start" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Question End *</label>
                                <input type="number" name="question_end" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Duration (minutes)</label>
                                <input type="number" name="duration_minutes" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Audio File (Listening)</label>
                        <input type="file" name="audio_file" class="form-control" accept="audio/*">
                    </div>

                    <div class="form-group">
                        <label>Image File</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label>Reading Passage</label>
                        <textarea name="passage_text" class="form-control" rows="10"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ $test->sections->count() + 1 }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Section</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
function editSection(sectionId) {
    // TODO: Implement edit modal
    alert('Edit functionality coming soon. Use delete and re-create for now.');
}
</script>
@endpush
