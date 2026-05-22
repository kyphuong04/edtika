@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manage Question Groups</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.sections', $section->test_id) }}">{{ trans('update.ielts_manage_sections') }}</a></div>
            <div class="breadcrumb-item">{{ trans('update.ielts_manage_question_groups') }}</div>
        </div>
    </div>

    <div class="section-body">
        {{-- Instructions Alert --}}
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>{{ trans('update.ielts_about_question_groups') }}:</strong> {{ trans('update.ielts_about_question_groups_desc') }}
            <br><strong>{{ trans('update.ielts_question_group_example') }}:</strong> {{ trans('update.ielts_question_group_example_desc') }}
        </div>

        <div class="card">
            <div class="card-header justify-content-between">
                <div>
                    <h4 class="mb-0">{{ $section->title }}</h4>
                    <p class="text-gray mb-0 mt-1">
                        <span class="badge badge-{{
                            $section->skill === 'listening' ? 'info' :
                            ($section->skill === 'reading' ? 'success' :
                            ($section->skill === 'writing' ? 'warning' : 'danger'))
                        }}">{{ ucfirst($section->skill) }}</span>
                        <span class="ml-2">Questions {{ $section->question_start }} - {{ $section->question_end }}</span>
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.ielts_tests.sections', $section->test_id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ trans('update.ielts_back_to_sections') }}
                    </a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQuestionGroupModal">
                        <i class="fas fa-layer-group mr-2"></i>
                        {{ trans('update.ielts_add_question_group') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($questionGroups->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-layer-group fa-3x text-gray mb-3"></i>
                        <h5>{{ trans('update.ielts_no_question_groups_yet') }}</h5>
                        <p class="text-gray">{{ trans('update.ielts_create_first_question_group_hint') }}</p>
                        <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#addQuestionGroupModal">
                            <i class="fas fa-plus mr-2"></i>
                            {{ trans('update.ielts_add_question_group') }}
                        </button>
                    </div>
                @else
                    @foreach($questionGroups as $group)
                    <div class="card mb-4 border-left-primary" style="border-left: 4px solid {{ 
                        $group->question_type === 'multiple_choice' ? '#4e73df' : 
                        ($group->question_type === 'true_false_not_given' ? '#1cc88a' : 
                        ($group->question_type === 'matching' ? '#36b9cc' : '#f6c23e')) 
                    }};">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $group->title }}</h5>
                                    <div class="mt-2">
                                        <span class="badge badge-primary">{{ $group->getQuestionTypeLabel() }}</span>
                                        <span class="badge badge-secondary ml-1">
                                            Questions {{ $group->question_start }} - {{ $group->question_end }}
                                            ({{ $group->question_end - $group->question_start + 1 }} questions)
                                        </span>
                                        @if($group->max_words)
                                            <span class="badge badge-info ml-1">Max {{ $group->max_words }} words</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('admin.ielts_tests.question_groups.questions', $group->id) }}" 
                                       class="btn btn-sm btn-primary" title="Manage Questions">
                                        <i class="fas fa-question-circle"></i>
                                        Questions ({{ $group->questions_count ?? 0 }})
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning" 
                                            onclick="editQuestionGroup({{ $group->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.ielts_tests.question_groups.delete', $group->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Delete this question group and all its questions?')" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            @if($group->instructions)
                                <div class="mb-3">
                                    <strong><i class="fas fa-info-circle mr-1"></i>Instructions:</strong>
                                    <p class="mb-0 mt-1 text-gray">{{ $group->instructions }}</p>
                                </div>
                            @endif

                            @if($section->skill === 'reading' && $group->passage)
                                <div class="mb-3">
                                    <strong><i class="fas fa-book-open mr-1"></i>Reading Passage:</strong>
                                    <div class="border rounded p-3 mt-2 bg-light">
                                        <p class="mb-0">{{ Str::limit($group->passage, 300) }}</p>
                                        @if(strlen($group->passage) > 300)
                                            <button type="button" class="btn btn-link btn-sm p-0 mt-2" 
                                                    onclick="showFullPassage({{ $group->id }})">
                                                Show full passage...
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($section->skill === 'listening' && $section->audio_file)
                                <div class="mb-3">
                                    <strong><i class="fas fa-volume-up mr-1"></i>Audio File:</strong>
                                    <audio controls class="w-100 mt-2">
                                        <source src="{{ $section->audio_file }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            @endif

                            @if($section->skill === 'speaking' && !empty($group->video_file))
                                <div class="mb-3">
                                    <strong><i class="fas fa-video mr-1"></i>Video File:</strong>
                                    <video controls controlsList="nodownload" class="w-100 mt-2" style="max-height:260px;border-radius:6px;background:#000;">
                                        <source src="{{ $group->video_file }}" type="video/mp4">
                                        <source src="{{ $group->video_file }}" type="video/webm">
                                        Your browser does not support the video element.
                                    </video>
                                </div>
                            @endif

                            @if($group->task_image)
                                <div class="mb-3">
                                    <strong><i class="fas fa-image mr-1"></i>{{ trans('update.ielts_task_image') }}:</strong>
                                    <div class="mt-2">
                                        <img src="{{ $group->task_image }}" alt="Task" class="img-thumbnail" style="max-width: 400px;">
                                    </div>
                                </div>
                            @endif

                            {{-- Question Type Specific Info --}}
                            @if($group->question_type === 'matching_headings')
                                <div class="mb-2">
                                    <strong>Headings Available:</strong>
                                    <span class="badge badge-info ml-2">{{ count(json_decode($group->options_data ?? '[]')) }} headings</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Add Question Group Modal --}}
<div class="modal fade" id="addQuestionGroupModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Question Group</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.ielts_tests.question_groups.store', $section->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Group Title *</label>
                                <input type="text" name="title" class="form-control" required 
                                       placeholder="e.g., Questions 1-5, Multiple Choice about main ideas">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Question Type *</label>
                                <select name="question_type" class="form-control" id="questionTypeSelect" required>
                                    <option value="">Select type...</option>
                                    <optgroup label="Common Types">
                                        <option value="multiple_choice">Multiple Choice</option>
                                        <option value="fill_blank">Fill in the Blanks</option>
                                    </optgroup>
                                    @if($section->skill === 'reading')
                                        <optgroup label="Reading Specific">
                                            <option value="true_false_not_given">True / False / Not Given</option>
                                            <option value="yes_no_not_given">Yes / No / Not Given</option>
                                            <option value="matching_headings">Matching Headings</option>
                                            <option value="matching_information">Matching Information</option>
                                            <option value="matching_features">Matching Features</option>
                                            <option value="matching_sentence_endings">Matching Sentence Endings</option>
                                            <option value="sentence_completion">Sentence Completion</option>
                                            <option value="summary_completion">Summary Completion</option>
                                            <option value="note_completion">Note Completion</option>
                                            <option value="table_completion">Table Completion</option>
                                            <option value="flow_chart_completion">Flow Chart Completion</option>
                                            <option value="diagram_labeling">Diagram Labeling</option>
                                            <option value="short_answer">Short Answer Questions</option>
                                        </optgroup>
                                    @endif
                                    @if($section->skill === 'listening')
                                        <optgroup label="Listening Specific">
                                            <option value="note_completion">Note Completion</option>
                                            <option value="form_completion">Form Completion</option>
                                            <option value="table_completion">Table Completion</option>
                                            <option value="flow_chart_completion">Flow Chart Completion</option>
                                            <option value="map_labeling">Map Labeling</option>
                                            <option value="diagram_labeling">Diagram Labeling</option>
                                            <option value="matching">Matching</option>
                                        </optgroup>
                                    @endif
                                    @if($section->skill === 'writing')
                                        <optgroup label="Writing Tasks">
                                            <option value="task1_graph">Task 1 - Describe Graph/Chart</option>
                                            <option value="task1_map">Task 1 - Describe Map/Diagram</option>
                                            <option value="task1_process">Task 1 - Describe Process</option>
                                            <option value="task1_letter">Task 1 - Letter (General)</option>
                                            <option value="task2_essay">Task 2 - Essay</option>
                                        </optgroup>
                                    @endif
                                    @if($section->skill === 'speaking')
                                        <optgroup label="Speaking Parts">
                                            <option value="part1">Part 1 - Introduction & Interview</option>
                                            <option value="part2">Part 2 - Long Turn (Cue Card)</option>
                                            <option value="part3">Part 3 - Discussion</option>
                                        </optgroup>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Question Numbers Start *</label>
                                <input type="number" name="question_start" class="form-control" required 
                                       min="{{ $section->question_start }}" max="{{ $section->question_end }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Question Numbers End *</label>
                                <input type="number" name="question_end" class="form-control" required 
                                       min="{{ $section->question_start }}" max="{{ $section->question_end }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('update.ielts_instructions_for_students') }}</label>
                        <textarea name="instructions" class="form-control" rows="3" 
                                  placeholder="e.g., Choose the correct letter A, B, C or D"></textarea>
                    </div>

                    {{-- Reading Passage --}}
                    @if($section->skill === 'reading')
                        <div class="form-group">
                            <label>Reading Passage (if different from section passage)</label>
                            <textarea name="passage" class="form-control" rows="8"></textarea>
                            <small class="text-gray">Leave empty to use section's main passage</small>
                        </div>
                    @endif

                    {{-- Listening Audio --}}
                    @if($section->skill === 'listening')
                        <div class="form-group">
                            <label>Audio File (if different from section audio)</label>
                            <input type="file" name="audio_file" class="form-control" accept="audio/*">
                            <small class="text-gray">Leave empty to use section's main audio</small>
                        </div>
                    @endif

                    {{-- Speaking Video --}}
                    @if($section->skill === 'speaking')
                        <div class="form-group">
                            <label>Video File <span class="text-muted">(Optional &mdash; shown to students as the question prompt)</span></label>
                            <input type="file" name="video_file" class="form-control" accept="video/*">
                            <small class="text-gray">Upload a video question for this group (MP4 recommended). Overrides the section-level video for this group.</small>
                        </div>
                    @endif

                    {{-- Task Image --}}
                    <div class="form-group">
                        <label>Task Image (for diagrams, maps, charts, etc.)</label>
                        <input type="file" name="task_image" class="form-control" accept="image/*">
                    </div>

                    {{-- Word Limit --}}
                    @if(in_array($section->skill, ['writing', 'reading', 'listening']))
                        <div class="form-group">
                            <label>Maximum Words (for fill-in-the-blank, short answer)</label>
                            <input type="number" name="max_words" class="form-control" placeholder="e.g., 1, 2, 3">
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Question Group</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
function editQuestionGroup(groupId) {
    alert('Edit functionality coming soon. Use delete and re-create for now.');
}

function showFullPassage(groupId) {
    // TODO: Show full passage in modal
    alert('Full passage view coming soon.');
}
</script>
@endpush
