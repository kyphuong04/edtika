@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
@php
    $skillConfig = [
        'listening' => [
            'icon' => 'headphone',
            'color' => 'primary',
            'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'sections' => ['Section 1', 'Section 2', 'Section 3', 'Section 4'],
            'hint' => 'Each section has 10 questions. Upload audio file for students to listen.'
        ],
        'reading' => [
            'icon' => 'book',
            'color' => 'info',
            'gradient' => 'linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%)',
            'sections' => ['Passage 1', 'Passage 2', 'Passage 3'],
            'hint' => 'Each passage has 13-14 questions. Add the reading passage text below.'
        ],
        'writing' => [
            'icon' => 'edit',
            'color' => 'warning',
            'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'sections' => ['Task 1', 'Task 2'],
            'hint' => 'Task 1: Graph/Chart/Diagram (150 words). Task 2: Essay (250 words).'
        ],
        'speaking' => [
            'icon' => 'microphone',
            'color' => 'danger',
            'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'sections' => ['Part 1 - Introduction', 'Part 2 - Cue Card', 'Part 3 - Discussion'],
            'hint' => 'Part 1: 4-5 minutes. Part 2: 1 min prep + 2 min talk. Part 3: 4-5 minutes discussion.'
        ],
    ];
    $config = $skillConfig[$skill] ?? $skillConfig['reading'];
@endphp

<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div class="d-flex align-items-center">
            <div class="skill-icon d-flex-center rounded-12 mr-16" 
                 style="width: 48px; height: 48px; background: {{ $config['gradient'] }};">
                @if($skill === 'listening')
                    <x-iconsax-bul-headphone class="icons text-white" width="24px" height="24px"/>
                @elseif($skill === 'reading')
                    <x-iconsax-bul-book class="icons text-white" width="24px" height="24px"/>
                @elseif($skill === 'writing')
                    <x-iconsax-bul-edit class="icons text-white" width="24px" height="24px"/>
                @else
                    <x-iconsax-bul-microphone class="icons text-white" width="24px" height="24px"/>
                @endif
            </div>
            <div>
                <h1 class="font-20 font-weight-bold text-dark">Add {{ ucfirst($skill) }} Section</h1>
                <p class="text-gray-500 font-14 mt-4 mb-0">{{ $config['hint'] }}</p>
            </div>
        </div>
        <a href="{{ route('panel.question-groups.create', ['type' => 'mock']) }}" class="btn btn-outline-secondary btn-sm">
            <x-iconsax-bul-arrow-left class="icons mr-8" width="16px" height="16px"/>Back
        </a>
    </div>

    <form method="POST" action="{{ route('panel.question-groups.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="bank_type" value="mock">
        <input type="hidden" name="skill" value="{{ $skill }}">
        
        {{-- Basic Info --}}
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold mb-16">Section Information</h3>
            
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="form-group">
                        <label class="form-group-label is-required">Section Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="e.g., {{ $config['sections'][0] }} - Campus Tour" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Target Band</label>
                        <select name="target_band" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach([5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0] as $band)
                                <option value="{{ $band }}">{{ $band }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-group-label">Instructions</label>
                <textarea name="instructions" class="form-control" rows="2" 
                          placeholder="e.g., Answer questions 1-10 based on the recording below">{{ old('instructions') }}</textarea>
            </div>
        </div>
        
        {{-- Content based on skill --}}
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold mb-16">Content</h3>
            
            @if($skill === 'listening')
                {{-- Listening: Audio upload --}}
                <div class="form-group">
                    <label class="form-group-label is-required">Audio File</label>
                    <div class="custom-file bg-white">
                        <input type="file" name="audio_file" class="custom-file-input" id="audioFile" accept="audio/*" required>
                        <label class="custom-file-label" for="audioFile">Choose audio file...</label>
                    </div>
                    <small class="text-gray-500">MP3, WAV, M4A - Max 50MB</small>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Transcript (optional)</label>
                    <textarea name="transcript" class="summernote form-control">{{ old('transcript') }}</textarea>
                </div>
                
            @elseif($skill === 'reading')
                {{-- Reading: Passage text --}}
                <div class="form-group">
                    <label class="form-group-label is-required">Reading Passage</label>
                    <textarea name="passage" class="summernote form-control" required>{{ old('passage') }}</textarea>
                    <small class="text-gray-500">Paste the full reading passage with proper paragraphs</small>
                </div>
                
            @elseif($skill === 'writing')
                {{-- Writing: Task type + image --}}
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="form-group-label is-required">Task Type</label>
                            <select name="section_type" class="form-control" required>
                                <option value="task1_graph">Task 1 - Graph/Chart (Academic)</option>
                                <option value="task1_letter">Task 1 - Letter (General)</option>
                                <option value="task1_process">Task 1 - Process/Diagram</option>
                                <option value="task2_essay">Task 2 - Essay</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="form-group-label">Task Image</label>
                            <div class="custom-file bg-white">
                                <input type="file" name="task_image" class="custom-file-input" id="taskImage" accept="image/*">
                                <label class="custom-file-label" for="taskImage">Choose image...</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label is-required">Task Description</label>
                    <textarea name="passage" class="summernote form-control" required>{{ old('passage') }}</textarea>
                </div>
                
            @elseif($skill === 'speaking')
                {{-- Speaking: Part selection --}}
                <div class="form-group">
                    <label class="form-group-label is-required">Part</label>
                    <select name="section_type" class="form-control" required>
                        <option value="part1">Part 1 - Introduction & Interview</option>
                        <option value="part2">Part 2 - Individual Long Turn (Cue Card)</option>
                        <option value="part3">Part 3 - Two-way Discussion</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label is-required">Topic</label>
                    <input type="text" name="topic" class="form-control" placeholder="e.g., Holidays, Technology, Education" required>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Description / Notes</label>
                    <textarea name="passage" class="form-control" rows="3">{{ old('passage') }}</textarea>
                </div>
            @endif
        </div>
        
        {{-- Actions --}}
        <div class="bg-white rounded-16 p-16">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('panel.question-groups.create', ['type' => 'mock']) }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <x-iconsax-bul-tick-circle class="icons mr-8" width="16px" height="16px"/>Create & Add Questions
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
                height: 300,
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
        });
    </script>
@endpush
