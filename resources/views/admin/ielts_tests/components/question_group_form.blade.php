{{-- Question Group Form Component (Reusable) --}}
@props(['action', 'method' => 'POST', 'group' => null, 'section' => null])

<form action="{{ $action }}" method="{{ $method === 'POST' ? 'POST' : 'POST' }}" enctype="multipart/form-data" class="question-group-form">
    @if($method === 'PUT')
        @method('PUT')
    @endif
    @csrf

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-7">
            {{-- Title & Description --}}
            <div class="form-group">
                <label class="input-label font-weight-bold">Title / Question Prompt <span class="text-danger">*</span></label>
                <textarea name="title" class="form-control" rows="3" placeholder="e.g., The housing officer takes some details from the girl. Complete the following form with NO MORE THAN THREE WORDS AND/OR A NUMBER for each answer." required>{{ old('title', $group?->title) }}</textarea>
                <small class="form-text text-muted">This is what students will see as the question group description</small>
            </div>

            {{-- Question Type & Range --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label font-weight-bold">Question Type <span class="text-danger">*</span></label>
                        <select name="question_type" class="form-control" required>
                            <option value="">Select type...</option>
                            <option value="multiple_choice" {{ old('question_type', $group?->question_type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="matching" {{ old('question_type', $group?->question_type) == 'matching' ? 'selected' : '' }}>Matching</option>
                            <option value="fill_blanks" {{ old('question_type', $group?->question_type) == 'fill_blanks' ? 'selected' : '' }}>Fill in the Blanks</option>
                            <option value="table_completion" {{ old('question_type', $group?->question_type) == 'table_completion' ? 'selected' : '' }}>Table Completion</option>
                            <option value="true_false_not_given" {{ old('question_type', $group?->question_type) == 'true_false_not_given' ? 'selected' : '' }}>True/False/Not Given</option>
                            <option value="short_answer" {{ old('question_type', $group?->question_type) == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                            <option value="flowchart" {{ old('question_type', $group?->question_type) == 'flowchart' ? 'selected' : '' }}>Flowchart Completion</option>
                            <option value="diagram_labeling" {{ old('question_type', $group?->question_type) == 'diagram_labeling' ? 'selected' : '' }}>Diagram Labeling</option>
                            <option value="multiple_select" {{ old('question_type', $group?->question_type) == 'multiple_select' ? 'selected' : '' }}>Multiple Select</option>
                            <option value="essay_writing" {{ old('question_type', $group?->question_type) == 'essay_writing' ? 'selected' : '' }}>Essay Writing</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label font-weight-bold">Question Number Range</label>
                        <div class="input-group">
                            <input type="number" name="question_start" class="form-control" placeholder="Start" value="{{ old('question_start', $group?->question_start) }}" required>
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text">-</span>
                            </div>
                            <input type="number" name="question_end" class="form-control" placeholder="End" value="{{ old('question_end', $group?->question_end) }}" required>
                        </div>
                        <small class="form-text text-muted">e.g., 1-5 or 15-20</small>
                    </div>
                </div>
            </div>

            {{-- Additional Fields --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Maximum Words (if applicable)</label>
                        <input type="number" name="max_words" class="form-control" placeholder="e.g., 3" value="{{ old('max_words', $group?->max_words) }}" min="1">
                        <small class="form-text text-muted">For writing tasks</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Target Band</label>
                        <select name="target_band" class="form-control">
                            <option value="">No specific target</option>
                            @for($i = 4.0; $i <= 9.0; $i += 0.5)
                                <option value="{{ $i }}" {{ old('target_band', $group?->target_band) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="form-group">
                <label class="input-label">Instructions</label>
                <textarea name="instructions" class="form-control" rows="2" placeholder="Additional instructions or notes for this group...">{{ old('instructions', $group?->instructions) }}</textarea>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-5">
            {{-- Media Upload Section --}}
            <div class="card bg-light border-0 rounded">
                <div class="card-header bg-gradient-primary text-white font-weight-bold">
                    <i class="fas fa-cloud-upload-alt mr-2"></i>Media Files
                </div>
                <div class="card-body">
                    {{-- Audio File --}}
                    <div class="form-group">
                        <label class="input-label font-weight-bold">Audio File</label>
                        <div class="custom-file">
                            <input type="file" name="audio_file" class="custom-file-input audio-input" id="audioFile" accept=".mp3,.wav,.ogg,.m4a">
                            <label class="custom-file-label" for="audioFile">
                                @if($group?->audio_file)
                                    <i class="fas fa-check text-success mr-2"></i>{{ basename($group->audio_file) }}
                                @else
                                    Choose audio file...
                                @endif
                            </label>
                        </div>
                        <small class="form-text text-muted d-block mt-2">
                            @if($group?->audio_file)
                                <i class="fas fa-check-circle text-success mr-1"></i>Current: {{ $group->audio_file }}
                                <br><a href="#" class="text-danger small" onclick="removeMedia('audio', this)">Remove</a>
                            @endif
                        </small>
                    </div>

                    {{-- Image File --}}
                    <div class="form-group">
                        <label class="input-label font-weight-bold">Image File</label>
                        <div class="custom-file">
                            <input type="file" name="task_image" class="custom-file-input image-input" id="imageFile" accept=".jpg,.jpeg,.png,.gif,.webp">
                            <label class="custom-file-label" for="imageFile">
                                @if($group?->task_image)
                                    <i class="fas fa-check text-success mr-2"></i>{{ basename($group->task_image) }}
                                @else
                                    Choose image...
                                @endif
                            </label>
                        </div>
                        @if($group?->task_image)
                            <div class="mt-2">
                                <img src="{{ $group->task_image }}" alt="Task Image" class="img-thumbnail" style="max-height: 150px;">
                                <br><a href="#" class="text-danger small" onclick="removeMedia('image', this)">Remove</a>
                            </div>
                        @endif
                    </div>

                    {{-- Video File --}}
                    <div class="form-group">
                        <label class="input-label font-weight-bold">Video File</label>
                        <div class="custom-file">
                            <input type="file" name="video_file" class="custom-file-input video-input" id="videoFile" accept=".mp4,.avi,.mov,.webm">
                            <label class="custom-file-label" for="videoFile">
                                @if($group?->video_file)
                                    <i class="fas fa-check text-success mr-2"></i>{{ basename($group->video_file) }}
                                @else
                                    Choose video...
                                @endif
                            </label>
                        </div>
                        <small class="form-text text-muted d-block mt-2">
                            @if($group?->video_file)
                                <i class="fas fa-check-circle text-success mr-1"></i>Current: {{ $group->video_file }}
                                <br><a href="#" class="text-danger small" onclick="removeMedia('video', this)">Remove</a>
                            @endif
                        </small>
                    </div>

                    {{-- Passage/Transcript --}}
                    <div class="form-group">
                        <label class="input-label font-weight-bold">Passage / Transcript</label>
                        <textarea name="passage" class="form-control form-control-sm" rows="5" placeholder="Full text for reading comprehension or transcript for listening...">{{ old('passage', $group?->passage) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Actions --}}
    <div class="form-group mt-4 pt-3 border-top">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save mr-2"></i>
            {{ isset($group) ? 'Update Question Group' : 'Create Question Group' }}
        </button>
        <a href="{{ route('admin.ielts_tests.question_groups', $section->id ?? request('section_id')) }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-times mr-2"></i>Cancel
        </a>
    </div>
</form>

@push('scripts_bottom')
<script>
document.querySelectorAll('.audio-input, .image-input, .video-input').forEach(input => {
    input.addEventListener('change', function(e) {
        const label = this.nextElementSibling;
        if (this.files.length > 0) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
            label.textContent = `✓ ${fileName} (${fileSize}MB)`;
            label.classList.add('text-success');
        }
    });
});

function removeMedia(type, link) {
    event.preventDefault();
    // Remove via AJAX or form submission
    alert('Remove ' + type + ' functionality');
}
</script>
@endpush
