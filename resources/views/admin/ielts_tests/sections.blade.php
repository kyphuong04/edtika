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
            <div class="breadcrumb-item">{{ trans('update.ielts_manage_sections') }}</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header justify-content-between">
                <div>
                    <h4 class="mb-0">{{ $test->title }}</h4>
                    <p class="text-gray mb-0 mt-1">{{ trans('update.ielts_manage_sections_hint') }}</p>
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
                        <h5>{{ trans('update.ielts_no_sections_yet') }}</h5>
                        <p class="text-gray">{{ trans('update.ielts_create_first_section_hint') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('update.ielts_section_order') }}</th>
                                    <th>{{ trans('update.ielts_title') }}</th>
                                    <th>{{ trans('update.ielts_section_skill') }}</th>
                                    <th>{{ trans('update.ielts_section_questions') }}</th>
                                    <th>{{ trans('update.ielts_duration') }}</th>
                                    <th>{{ trans('update.ielts_section_media') }}</th>
                                    <th>{{ trans('update.ielts_section_actions') }}</th>
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
                                            {{ $section->duration_minutes }} {{ trans('update.ielts_min') }}
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
                                            <a href="{{ route('admin.ielts_tests.question_groups', $section->id) }}" 
                                               class="btn btn-sm btn-primary" title="Manage Question Groups">
                                                <i class="fas fa-layer-group"></i>
                                                Question Groups
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
                <h5 class="mb-0">{{ trans('update.ielts_mock_test_requirements') }}</h5>
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
                    @if($test->isMockTest())
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>{{ trans('update.ielts_follow_lrws_order') }}:</strong>
                            <ol class="mb-0 mt-2">
                                <li>Create Listening sections first (30 min total)</li>
                                <li>Then Reading sections (60 min total)</li>
                                <li>Then Writing sections (60 min total)</li>
                                <li>Finally Speaking sections (11-14 min total)</li>
                            </ol>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Skill *</label>
                                <select name="skill" class="form-control" id="sectionSkill" required>
                                    <option value="">Select skill...</option>
                                    @if($test->has_listening)
                                        <option value="listening">Listening</option>
                                    @endif
                                    @if($test->has_reading)
                                        <option value="reading">Reading</option>
                                    @endif
                                    @if($test->has_writing)
                                        <option value="writing">Writing</option>
                                    @endif
                                    @if($test->has_speaking)
                                        <option value="speaking">Speaking</option>
                                    @endif
                                </select>
                                <small class="text-gray">
                                    @if($test->isMockTest())
                                        Follow order: L → R → W → S
                                    @else
                                        Only {{ ucfirst($test->getPrimarySkill()) }} available for this practice test
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Section Number *</label>
                                <input type="number" name="section_number" class="form-control" required>
                                <small class="text-gray">e.g., 1, 2, 3 for each skill</small>
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
                                <label>{{ trans('update.ielts_duration_minutes') }}</label>
                                <input type="number" name="duration_minutes" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- Listening-specific fields --}}
                    <div id="listeningFields" class="skill-fields" style="display: none;">
                        <h6 class="mt-3 mb-2 text-primary"><i class="fas fa-headphones mr-2"></i>Listening Section</h6>
                        <div class="form-group">
                            <label>Audio File * <span class="text-danger">(Required for Listening)</span></label>
                            <input type="file" name="audio_file" class="form-control" accept="audio/*">
                        </div>
                    </div>

                    {{-- Reading-specific fields --}}
                    <div id="readingFields" class="skill-fields" style="display: none;">
                        <h6 class="mt-3 mb-2 text-success"><i class="fas fa-book-open mr-2"></i>Reading Section</h6>
                        <div class="form-group">
                            <label>Reading Passage * <span class="text-danger">(Required for Reading)</span></label>
                            <textarea name="passage_text" class="form-control" rows="10" placeholder="Paste the reading passage here..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Passage Title</label>
                            <input type="text" name="passage_title" class="form-control" placeholder="e.g., The History of Time">
                        </div>
                    </div>

                    {{-- Writing-specific fields --}}
                    <div id="writingFields" class="skill-fields" style="display: none;">
                        <h6 class="mt-3 mb-2 text-warning"><i class="fas fa-pencil-alt mr-2"></i>Writing Section</h6>
                        <div class="form-group">
                            <label>Task Type *</label>
                            <select name="writing_task_type" class="form-control">
                                <option value="">Select task type...</option>
                                <option value="task1">Task 1 - Describe graph/chart/diagram/letter</option>
                                <option value="task2">Task 2 - Essay</option>
                            </select>
                        </div>
                    </div>

                    {{-- Speaking-specific fields --}}
                    <div id="speakingFields" class="skill-fields" style="display: none;">
                        <h6 class="mt-3 mb-2 text-danger"><i class="fas fa-microphone mr-2"></i>Speaking Section</h6>
                        <div class="form-group">
                            <label>Part Type *</label>
                            <select name="speaking_part_type" class="form-control">
                                <option value="">Select part type...</option>
                                <option value="part1">Part 1 - Introduction & Interview</option>
                                <option value="part2">Part 2 - Long Turn (Cue Card)</option>
                                <option value="part3">Part 3 - Discussion</option>
                            </select>
                        </div>
                    </div>

                    {{-- Common image field --}}
                    <div class="form-group mt-3">
                        <label>Image File (Optional)</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                        <small class="text-gray">For diagrams, maps, charts, etc.</small>
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
document.addEventListener('DOMContentLoaded', function() {
    const skillSelect = document.getElementById('sectionSkill');
    const skillFields = document.querySelectorAll('.skill-fields');

    if (skillSelect) {
        skillSelect.addEventListener('change', function() {
            // Hide all skill-specific fields
            skillFields.forEach(field => field.style.display = 'none');

            // Show relevant fields based on selected skill
            const selectedSkill = this.value;
            if (selectedSkill === 'listening') {
                document.getElementById('listeningFields').style.display = 'block';
            } else if (selectedSkill === 'reading') {
                document.getElementById('readingFields').style.display = 'block';
            } else if (selectedSkill === 'writing') {
                document.getElementById('writingFields').style.display = 'block';
            } else if (selectedSkill === 'speaking') {
                document.getElementById('speakingFields').style.display = 'block';
            }
        });
    }

    // Form validation before submit
    document.querySelector('#addSectionModal form').addEventListener('submit', function(e) {
        const skill = skillSelect.value;
        
        // Validate Listening - must have audio
        if (skill === 'listening') {
            const audioFile = document.querySelector('input[name="audio_file"]');
            if (!audioFile.value && !audioFile.files.length) {
                e.preventDefault();
                alert('Audio file is required for Listening sections!');
                return false;
            }
        }
        
        // Validate Reading - must have passage
        if (skill === 'reading') {
            const passageText = document.querySelector('textarea[name="passage_text"]');
            if (!passageText.value.trim()) {
                e.preventDefault();
                alert('Reading passage text is required for Reading sections!');
                return false;
            }
        }
    });
});
</script>
@endpush

@push('scripts_bottom')
<script>
function editSection(sectionId) {
    // TODO: Implement edit modal
    alert('Edit functionality coming soon. Use delete and re-create for now.');
}
</script>
@endpush
