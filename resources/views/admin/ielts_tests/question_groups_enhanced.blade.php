{{-- Enhanced Question Groups Management View --}}
@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manage Parts & Questions</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.edit', $section->test->id) }}">{{ $section->test->title }}</a></div>
            <div class="breadcrumb-item">{{ $section->title }}</div>
        </div>
    </div>

    <div class="section-body">
        {{-- Test & Section Info --}}
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card border-left-primary">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $section->test->title }}</h5>
                            <p class="text-muted mb-0">
                                <span class="badge badge-{{ 
                                    $section->skill === 'listening' ? 'info' :
                                    ($section->skill === 'reading' ? 'success' :
                                    ($section->skill === 'writing' ? 'warning' : 'danger'))
                                }}">{{ ucfirst($section->skill) }}</span>
                                <span class="ml-2">{{ $section->title }}</span>
                                <span class="mx-2">•</span>
                                <strong>Questions {{ $section->question_start }} - {{ $section->question_end }}</strong>
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.ielts_tests.sections', $section->test->id) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>Back to Sections
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Instructions Alert --}}
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-lightbulb mr-2"></i>
            <strong>Structure:</strong> Create <strong>Parts</strong> (Question Groups) for this section, then add individual questions to each part. Each part can have a different question type with its own title, description, and media files (audio/images).
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>

        {{-- Main Card --}}
        <div class="card">
            {{-- Card Header with Add Button --}}
            <div class="card-header justify-content-between">
                <div>
                    <h5 class="mb-0 font-weight-bold">Parts/Question Groups</h5>
                    <small class="text-muted">Organize questions into logical parts with descriptions and media</small>
                </div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQuestionGroupModal">
                    <i class="fas fa-plus mr-2"></i>Add New Part
                </button>
            </div>

            {{-- Card Body --}}
            <div class="card-body">
                @if($questionGroups->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-layer-group fa-4x text-gray mb-3" style="opacity: 0.5;"></i>
                        <h5 class="text-gray">No Parts Created Yet</h5>
                        <p class="text-muted mb-4">Create the first part for this section. Each part will contain grouped questions.</p>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQuestionGroupModal">
                            <i class="fas fa-plus mr-2"></i>Create First Part
                        </button>
                    </div>
                @else
                    <div class="row">
                        @foreach($questionGroups->sortBy('question_start') as $group)
                        <div class="col-lg-6 mb-4">
                            <div class="card border shadow-sm h-100 group-card" data-group-id="{{ $group->id }}">
                                {{-- Card Header with Type Badge --}}
                                <div class="card-header bg-light d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-2 font-weight-bold">Part {{ $loop->iteration }}: {{ $group->title }}</h6>
                                        <div class="badge-group">
                                            <span class="badge badge-primary">Q{{ $group->question_start }}-{{ $group->question_end }}</span>
                                            <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $group->question_type ?? 'unknown')) }}</span>
                                            @if($group->max_words)
                                                <span class="badge badge-info">Max {{ $group->max_words }} words</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuGroup{{ $group->id }}" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 m-0" aria-labelledby="dropdownMenuGroup{{ $group->id }}">
                                            <a class="dropdown-item" href="javascript:editGroup({{ $group->id }})">
                                                <i class="fas fa-edit mr-2"></i>Edit Part
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.ielts_tests.questions', $group->id) }}">
                                                <i class="fas fa-question-circle mr-2"></i>Manage Questions
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="javascript:deleteGroup({{ $group->id }})">
                                                <i class="fas fa-trash mr-2"></i>Delete Part
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="card-body">
                                    {{-- Description --}}
                                    @if($group->title)
                                        <p class="text-sm text-muted mb-3"><strong>{{ $group->title }}</strong></p>
                                    @endif

                                    {{-- Media Indicators --}}
                                    <div class="media-indicators mb-3">
                                        @if($group->audio_file)
                                            <div class="media-item mb-2">
                                                <i class="fas fa-volume-up text-primary mr-2"></i>
                                                <span class="text-sm">Audio: {{ basename($group->audio_file) }}</span>
                                            </div>
                                        @endif
                                        @if($group->task_image)
                                            <div class="media-item mb-2">
                                                <i class="fas fa-image text-success mr-2"></i>
                                                <span class="text-sm">Image: {{ basename($group->task_image) }}</span>
                                            </div>
                                        @endif
                                        @if($group->video_file)
                                            <div class="media-item mb-2">
                                                <i class="fas fa-video text-danger mr-2"></i>
                                                <span class="text-sm">Video: {{ basename($group->video_file) }}</span>
                                            </div>
                                        @endif
                                        @if($group->passage)
                                            <div class="media-item">
                                                <i class="fas fa-file-text text-warning mr-2"></i>
                                                <span class="text-sm">Passage/Transcript: {{ strlen($group->passage) }} chars</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Instructions (if present) --}}
                                    @if($group->instructions)
                                        <div class="alert alert-light border small mb-3 p-2">
                                            {{ Str::limit($group->instructions, 100) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Footer with Questions Count --}}
                                <div class="card-footer bg-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-sm">
                                            <strong>{{ $group->questions->count() }}</strong> of {{ $group->question_end - $group->question_start + 1 }} questions added
                                        </div>
                                        <a href="{{ route('admin.ielts_tests.questions', $group->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-plus mr-1"></i>Add Questions
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Summary Card --}}
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="card bg-light border">
                                <div class="card-body d-flex justify-content-around">
                                    <div class="text-center">
                                        <h4 class="text-primary">{{ $questionGroups->count() }}</h4>
                                        <small class="text-muted">Parts Created</small>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="text-success">{{ $questionGroups->sum(function($g) { return $g->questions->count(); }) }}</h4>
                                        <small class="text-muted">Questions Added</small>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="text-info">{{ $section->question_end - $section->question_start + 1 }}</h4>
                                        <small class="text-muted">Total Required</small>
                                    </div>
                                    <div class="text-center">
                                        @php
                                            $totalQuestions = $questionGroups->sum(function($g) { return $g->questions->count(); });
                                            $requiredQuestions = $section->question_end - $section->question_start + 1;
                                            $percentage = $requiredQuestions > 0 ? ($totalQuestions / $requiredQuestions) * 100 : 0;
                                        @endphp
                                        <h4 class="text-warning">{{ number_format($percentage, 1) }}%</h4>
                                        <small class="text-muted">Completion</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Add/Edit Question Group Modal --}}
<div class="modal fade" id="addQuestionGroupModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Part (Question Group)</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body" id="groupFormContainer">
                {{-- Loaded via AJAX or include --}}
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles_bottom')
<style>
.group-card {
    transition: all 0.3s ease;
}

.group-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px);
}

.badge-group .badge {
    margin-right: 5px;
    font-size: 12px;
    display: inline-block;
    margin-bottom: 5px;
}

.media-indicators {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 4px;
}

.media-item {
    font-size: 13px;
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}
</style>
@endpush

@push('scripts_bottom')
<script>
$(document).ready(function() {
    $('#addQuestionGroupModal').on('show.bs.modal', function() {
        loadQuestionGroupForm();
    });
});

function loadQuestionGroupForm(groupId = null) {
    const url = groupId 
        ? `/admin/ielts-tests/question-groups/${groupId}/edit`
        : `{{ route('admin.ielts_tests.question_groups.create', $section->id) }}`;
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('groupFormContainer').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load form');
        });
}

function editGroup(groupId) {
    document.getElementById('addQuestionGroupModal').dataset.groupId = groupId;
    $('#addQuestionGroupModal').modal('show');
    loadQuestionGroupForm(groupId);
}

function deleteGroup(groupId) {
    if (confirm('Delete this part and all its questions?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/ielts-tests/question-groups/${groupId}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
