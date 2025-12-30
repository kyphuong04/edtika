@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Edit: {{ $group->title }}</h1>
            <p class="text-gray-500 font-14 mt-4">{{ $group->skill_label }} • {{ ucfirst($group->difficulty_level) }} • {{ $group->question_count }} questions</p>
        </div>
        <a href="{{ route('panel.question_bank.groups', $bankType) }}" class="btn btn-outline-secondary">
            <x-iconsax-lin-arrow-left class="icons mr-8" width="16px" height="16px"/>Back to Groups
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Group Info Tab --}}
            <div class="bg-white p-20 rounded-24 mb-24">
                <h4 class="font-16 font-weight-bold text-dark mb-16">Group Information</h4>
                
                <form method="POST" action="{{ route('panel.question_bank.groups.update', [$bankType, $group->id]) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Skill</label>
                            <input type="text" class="form-control" value="{{ $group->skill_label }}" disabled>
                        </div>

                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Difficulty *</label>
                            <select name="difficulty_level" class="form-control" required>
                                <option value="beginner" {{ $group->difficulty_level == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ $group->difficulty_level == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ $group->difficulty_level == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Title *</label>
                        <input type="text" name="title" class="form-control" required value="{{ $group->title }}">
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ $group->description }}</textarea>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Tags</label>
                        <input type="text" name="tags" class="form-control" value="{{ is_array($group->tags) ? implode(', ', $group->tags) : '' }}">
                    </div>

                    @if($group->skill === 'reading' && $group->passage)
                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Reading Passage</label>
                            <textarea name="passage" class="summernote">{{ $group->passage }}</textarea>
                        </div>
                    @endif

                    @if($group->skill === 'listening')
                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Transcript</label>
                            <textarea name="transcript" class="form-control" rows="6">{{ $group->transcript }}</textarea>
                        </div>
                        
                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Audio File</label>
                            <input type="file" name="audio_file" class="form-control" accept="audio/*">
                            @if($group->audio_file)
                                <small class="text-gray-500 font-12 mt-4">Current: {{ basename($group->audio_file) }}</small>
                            @endif
                        </div>
                    @endif

                    @if($group->skill === 'writing')
                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Task Image</label>
                            <input type="file" name="task_image" class="form-control" accept="image/*">
                            @if($group->task_image)
                                <div class="mt-8">
                                    <img src="{{ asset('storage/' . $group->task_image) }}" style="max-width: 300px; border-radius: 8px;">
                                </div>
                            @endif
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        <x-iconsax-bul-tick-circle class="icons mr-8" width="16px" height="16px"/>
                        Update Group Info
                    </button>
                </form>
            </div>

            {{-- Questions List --}}
            <div class="bg-white p-20 rounded-24 mb-24">
                <div class="d-flex align-items-center justify-content-between mb-16">
                    <h4 class="font-16 font-weight-bold text-dark">Questions ({{ $group->question_count }})</h4>
                    <a href="{{ route('panel.question_bank.create') }}?bank_type={{ $bankType }}&group_id={{ $group->id }}" class="btn btn-sm btn-primary">
                        <x-iconsax-bul-add-circle class="icons mr-4" width="14px" height="14px"/>
                        Add Question
                    </a>
                </div>

                @php
                    $questions = $group->bank_type === 'mock' ? $group->mockQuestions : $group->practiceQuestions;
                @endphp

                @if($questions->count() > 0)
                    <div class="d-flex flex-column gap-12">
                        @foreach($questions->sortBy('question_order') as $question)
                            <div class="d-flex align-items-start gap-12 p-16 rounded-16 bg-gray-100">
                                <div class="d-flex-center size-32 rounded-12 bg-primary text-white font-14 font-weight-bold flex-shrink-0">
                                    {{ $question->question_order ?? $loop->iteration }}
                                </div>
                                
                                <div class="flex-1">
                                    <div class="d-flex align-items-start justify-content-between mb-8">
                                        <div>
                                            <div class="font-14 font-weight-500 text-dark mb-4">{{ Str::limit($question->question_text, 80) }}</div>
                                            <div class="d-flex gap-6">
                                                <span class="badge badge-secondary font-12">{{ str_replace('_', ' ', $question->question_type) }}</span>
                                                <span class="badge badge-success font-12">{{ $question->correct_answer }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-6">
                                            <a href="{{ route('panel.question_bank.edit', [$bankType, $question->id]) }}" class="btn btn-sm btn-outline-primary">
                                                <x-iconsax-lin-edit class="icons" width="14px" height="14px"/>
                                            </a>
                                            <a href="{{ route('panel.question_bank.delete', [$bankType, $question->id]) }}" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Delete this question?')">
                                                <x-iconsax-bul-trash class="icons" width="14px" height="14px"/>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-40">
                        <x-iconsax-bul-message-question class="icons text-gray-400 mb-12" width="48px" height="48px"/>
                        <p class="text-gray-500 font-14 mb-16">No questions in this group yet</p>
                        <a href="{{ route('panel.question_bank.create') }}?bank_type={{ $bankType }}&group_id={{ $group->id }}" class="btn btn-primary">
                            Add First Question
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Stats Card --}}
            <div class="bg-white p-20 rounded-24 mb-24">
                <h4 class="font-14 font-weight-bold text-dark mb-16">Statistics</h4>
                
                <div class="mb-12">
                    <span class="text-gray-500 font-12">Questions:</span>
                    <div class="font-18 text-dark font-weight-bold">{{ $group->question_count }}</div>
                </div>

                <div class="mb-12">
                    <span class="text-gray-500 font-12">Used in Tests:</span>
                    <div class="font-18 text-dark font-weight-bold">{{ $group->usage_count }}</div>
                </div>

                <div class="mb-12">
                    <span class="text-gray-500 font-12">Created:</span>
                    <div class="font-14 text-gray-600">{{ $group->created_at->format('M d, Y') }}</div>
                </div>

                <div class="mb-12">
                    <span class="text-gray-500 font-12">Last Updated:</span>
                    <div class="font-14 text-gray-600">{{ $group->updated_at->format('M d, Y') }}</div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-white p-20 rounded-24 border-danger">
                <h4 class="font-14 font-weight-bold text-danger mb-12">Danger Zone</h4>
                <p class="text-gray-600 font-12 mb-16">Deleting this group will permanently remove all {{ $group->question_count }} questions.</p>
                <a href="{{ route('panel.question_bank.groups.delete', [$bankType, $group->id]) }}" 
                   class="btn btn-danger w-100"
                   onclick="return confirm('Are you sure? This will delete {{ $group->question_count }} questions!')">
                    <x-iconsax-bul-trash class="icons mr-8" width="16px" height="16px"/>
                    Delete Group
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts_bottom')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
});
</script>
@endpush
@endsection
