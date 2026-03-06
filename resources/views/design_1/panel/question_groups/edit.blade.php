@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Edit Group</h1>
            <p class="text-gray-500 font-14 mt-4">{{ $group->title }}</p>
        </div>
        <a href="{{ route('panel.question-groups.show', $group->id) }}" class="btn btn-outline-secondary btn-sm">
            <x-iconsax-bul-arrow-left class="icons mr-8" width="16px" height="16px"/>Back
        </a>
    </div>

    <form method="POST" action="{{ route('panel.question-groups.update', $group->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        {{-- Basic Info --}}
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold mb-16">Basic Information</h3>
            
            <div class="form-group">
                <label class="form-group-label is-required">Group Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $group->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label is-required">Skill</label>
                        <select name="skill" id="skillSelect" class="form-control" required>
                            <option value="reading" {{ $group->skill == 'reading' ? 'selected' : '' }}>📖 Reading</option>
                            <option value="listening" {{ $group->skill == 'listening' ? 'selected' : '' }}>🎧 Listening</option>
                            <option value="writing" {{ $group->skill == 'writing' ? 'selected' : '' }}>✍️ Writing</option>
                            <option value="speaking" {{ $group->skill == 'speaking' ? 'selected' : '' }}>🗣️ Speaking</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Target Band</label>
                        <select name="target_band" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach([5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0] as $band)
                                <option value="{{ $band }}" {{ $group->target_band == $band ? 'selected' : '' }}>{{ $band }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="draft" {{ $group->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ $group->status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="approved" {{ $group->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                </div>
            </div>
            
            {{-- Writing Task Type (shown only for Writing skill) --}}
            <div class="row" id="writingTaskTypeRow" style="display: {{ $group->skill == 'writing' ? 'flex' : 'none' }};">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-group-label is-required">Writing Task Type</label>
                        
                        @if(empty($group->question_type))
                            <div class="alert alert-warning mb-2" style="font-size: 13px; padding: 10px;">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Please select a Task Type</strong> - This group doesn't have a task type set yet.
                            </div>
                        @endif
                        
                        <select name="question_type" id="writingTaskType" class="form-control" {{ $group->skill == 'writing' ? 'required' : '' }}>
                            <option value="">-- Select Task Type --</option>
                            <optgroup label="✏️ Task 1 (Academic)">
                                <option value="task1_graph" {{ $group->question_type == 'task1_graph' ? 'selected' : '' }}>📊 Graph/Chart/Table</option>
                                <option value="task1_map" {{ $group->question_type == 'task1_map' ? 'selected' : '' }}>🗺️ Map/Diagram</option>
                                <option value="task1_process" {{ $group->question_type == 'task1_process' ? 'selected' : '' }}>🔄 Process</option>
                            </optgroup>
                            <optgroup label="✉️ Task 1 (General)">
                                <option value="task1_letter" {{ $group->question_type == 'task1_letter' ? 'selected' : '' }}>✉️ Letter</option>
                            </optgroup>
                            <optgroup label="📝 Task 2">
                                <option value="task2_essay" {{ $group->question_type == 'task2_essay' ? 'selected' : '' }}>📝 Essay (Opinion/Discussion/Problem-Solution)</option>
                            </optgroup>
                        </select>
                        <small class="form-text text-muted">
                            <strong>Task 1:</strong> 150 words, 20 minutes - Describe visual information<br>
                            <strong>Task 2:</strong> 250 words, 40 minutes - Write an essay
                        </small>
                    </div>
                </div>
            </div>
            
            {{-- Speaking Part Type (shown only for Speaking skill) --}}
            <div class="row" id="speakingPartTypeRow" style="display: {{ $group->skill == 'speaking' ? 'flex' : 'none' }};">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-group-label is-required">Speaking Part</label>
                        <select name="question_type" id="speakingPartType" class="form-control" {{ $group->skill == 'speaking' ? 'required' : '' }}>
                            <option value="">-- Select Speaking Part --</option>
                            <option value="part1" {{ in_array($group->question_type, ['part1', 'part1_questions']) ? 'selected' : '' }}>Part 1: Introduction & Interview (4-5 mins)</option>
                            <option value="part2" {{ in_array($group->question_type, ['part2', 'part2_cue_card']) ? 'selected' : '' }}>Part 2: Long Turn / Cue Card (3-4 mins)</option>
                            <option value="part3" {{ in_array($group->question_type, ['part3', 'part3_discussion']) ? 'selected' : '' }}>Part 3: Two-way Discussion (4-5 mins)</option>
                        </select>
                        <small class="form-text text-muted">
                            <strong>Part 1:</strong> Personal questions about familiar topics<br>
                            <strong>Part 2:</strong> Cue card with 1 min preparation + 2 min speaking<br>
                            <strong>Part 3:</strong> Abstract discussion questions
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Content --}}
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold mb-16">Content</h3>
            
            <div class="form-group">
                <label class="form-group-label">Instructions</label>
                <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $group->instructions) }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-group-label">Reading Passage / Text</label>
                <textarea name="passage" class="summernote form-control">{{ old('passage', $group->passage) }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-group-label">Audio File (for Listening)</label>
                @if($group->audio_path)
                    <div class="mb-8">
                        <audio controls class="w-100">
                            <source src="{{ $group->audio_url }}" type="audio/mpeg">
                        </audio>
                    </div>
                @endif
                <div class="custom-file bg-white">
                    <input type="file" name="audio_file" class="custom-file-input" id="audioFile" accept="audio/*">
                    <label class="custom-file-label" for="audioFile">
                        {{ $group->audio_path ? 'Replace audio...' : 'Choose audio file...' }}
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-group-label">Task Image (for Writing Task 1)</label>
                @if($group->task_image)
                    <div class="mb-8">
                        <img src="{{ \Storage::disk('public')->url($group->task_image) }}" alt="Task Image" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                @endif
                <div class="custom-file bg-white">
                    <input type="file" name="task_image" class="custom-file-input" id="taskImage" accept="image/*">
                    <label class="custom-file-label" for="taskImage">
                        {{ $group->task_image ? 'Replace image...' : 'Choose image file...' }}
                    </label>
                </div>
                <small class="form-text text-muted">Upload graph, chart, diagram, or map for Writing Task 1</small>
            </div>
            
            @if($group->skill === 'speaking')
            <div class="form-group">
                <label class="form-group-label">Video File (for Speaking)</label>
                @if($group->video_file)
                    <div class="mb-8">
                        <video controls class="w-100 rounded" style="max-height: 240px;">
                            <source src="{{ \Storage::disk('public')->url($group->video_file) }}" type="video/mp4">
                        </video>
                    </div>
                @endif
                <div class="custom-file bg-white">
                    <input type="file" name="video_file" class="custom-file-input" id="videoFile" accept="video/*">
                    <label class="custom-file-label" for="videoFile">
                        {{ $group->video_file ? 'Replace video...' : 'Choose video file...' }}
                    </label>
                </div>
                <small class="text-gray-500">MP4, WebM, MOV - Max 200MB.</small>
            </div>
            @endif
        </div>
        
        {{-- Actions --}}
        <div class="bg-white rounded-16 p-16">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('panel.question-groups.show', $group->id) }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <x-iconsax-bul-tick-square class="icons mr-8" width="16px" height="16px"/>Save Changes
                </button>
            </div>
        </div>
    </form>
</section>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 250,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table']],
                    ['view', ['codeview']]
                ]
            });
            
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').text(fileName || 'Choose file...');
            });
            
            // Show/hide Writing Task Type and Speaking Part based on skill selection
            $('#skillSelect').on('change', function() {
                const skill = $(this).val();
                
                // Writing Task Type
                if (skill === 'writing') {
                    $('#writingTaskTypeRow').slideDown();
                    $('#writingTaskType').prop('required', true);
                    $('#speakingPartTypeRow').slideUp();
                    $('#speakingPartType').prop('required', false).val('');
                } 
                // Speaking Part
                else if (skill === 'speaking') {
                    $('#speakingPartTypeRow').slideDown();
                    $('#speakingPartType').prop('required', true);
                    $('#writingTaskTypeRow').slideUp();
                    $('#writingTaskType').prop('required', false).val('');
                } 
                // Other skills
                else {
                    $('#writingTaskTypeRow').slideUp();
                    $('#writingTaskType').prop('required', false).val('');
                    $('#speakingPartTypeRow').slideUp();
                    $('#speakingPartType').prop('required', false).val('');
                }
            });
        });
    </script>
@endpush
