@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Create {{ ucfirst($type) }} Test</h1>
            <p class="text-gray-500 font-14 mt-4">
                @if($type === 'mock')
                    Follow IELTS order: Listening → Reading → Writing → Speaking
                @else
                    Add practice questions for specific skills
                @endif
            </p>
        </div>
        <a href="{{ route('panel.question-groups.index', ['type' => $type]) }}" class="btn btn-outline-secondary btn-sm">
            <x-iconsax-bul-arrow-left class="icons mr-8" width="16px" height="16px"/>Back
        </a>
    </div>

    @if($type === 'mock')
        {{-- MOCK TEST: Show 4 skills in order --}}
        <div class="mock-test-structure">
            @php
                $skills = [
                    'listening' => [
                        'icon' => 'headphone',
                        'color' => 'primary',
                        'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'sections' => 4,
                        'duration' => '30 minutes',
                        'questions' => '40 questions'
                    ],
                    'reading' => [
                        'icon' => 'book',
                        'color' => 'info',
                        'gradient' => 'linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%)',
                        'sections' => 3,
                        'duration' => '60 minutes',
                        'questions' => '40 questions'
                    ],
                    'writing' => [
                        'icon' => 'edit',
                        'color' => 'warning',
                        'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                        'sections' => 2,
                        'duration' => '60 minutes',
                        'questions' => '2 tasks'
                    ],
                    'speaking' => [
                        'icon' => 'microphone',
                        'color' => 'danger',
                        'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                        'sections' => 3,
                        'duration' => '11-14 minutes',
                        'questions' => '3 parts'
                    ],
                ];
            @endphp
            
            @foreach($skills as $skill => $config)
                <div class="skill-section bg-white rounded-16 p-16 mb-16">
                    <div class="d-flex align-items-center justify-content-between">
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
                                <h3 class="font-16 font-weight-bold text-dark mb-4">{{ ucfirst($skill) }}</h3>
                                <p class="font-12 text-gray-500 mb-0">
                                    {{ $config['sections'] }} sections • {{ $config['duration'] }} • {{ $config['questions'] }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('panel.question-groups.create', ['type' => 'mock', 'skill' => $skill]) }}" 
                           class="btn btn-success btn-sm">
                            <x-iconsax-bul-add class="icons mr-8" width="16px" height="16px"/>Add {{ ucfirst($skill) }} Section
                        </a>
                    </div>
                    
                    {{-- Show existing groups for this skill --}}
                    @php
                        $existingGroups = \App\Models\IeltsQuestionGroup::where('bank_type', 'mock')
                            ->where('skill', $skill)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($existingGroups->count() > 0)
                        <div class="mt-12 pt-12 border-top">
                            <p class="font-12 text-gray-500 mb-8">Recent {{ ucfirst($skill) }} sections:</p>
                            <div class="d-flex flex-wrap gap-8">
                                @foreach($existingGroups as $group)
                                    <a href="{{ route('panel.question-groups.show', $group->id) }}" 
                                       class="badge badge-light font-12 p-8">
                                        {{ Str::limit($group->title, 30) }}
                                        <span class="text-gray-400 ml-4">({{ $group->question_count }}q)</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        {{-- Quick tip --}}
        <div class="bg-info-light rounded-16 p-16">
            <h4 class="font-14 font-weight-bold text-info mb-8">
                <x-iconsax-bul-info-circle class="icons mr-8" width="16px" height="16px"/>IELTS Mock Test Structure
            </h4>
            <ul class="font-14 text-dark mb-0 pl-20">
                <li><strong>Listening:</strong> 4 sections, 10 questions each, total 40 questions</li>
                <li><strong>Reading:</strong> 3 passages (Academic) or 3 sections (General), total 40 questions</li>
                <li><strong>Writing:</strong> Task 1 (Graph/Letter 150 words) + Task 2 (Essay 250 words)</li>
                <li><strong>Speaking:</strong> Part 1 (Intro) + Part 2 (Cue Card) + Part 3 (Discussion)</li>
            </ul>
        </div>
        
    @else
        {{-- PRACTICE: Allow any skill --}}
        <form method="POST" action="{{ route('panel.question-groups.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="bank_type" value="practice">
            
            <div class="bg-white rounded-16 p-16 mb-16">
                <h3 class="font-14 font-weight-bold mb-16">Practice Question Group</h3>
                
                <div class="form-group">
                    <label class="form-group-label is-required">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title') }}" placeholder="e.g., Reading - Technology Topic" required>
                </div>
                
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="form-group-label is-required">Skill</label>
                            <select name="skill" id="skillSelectCreate" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="listening">🎧 Listening</option>
                                <option value="reading">📖 Reading</option>
                                <option value="writing">✍️ Writing</option>
                                <option value="speaking">🗣️ Speaking</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="form-group-label">Target Band</label>
                            <select name="target_band" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach([5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0] as $band)
                                    <option value="{{ $band }}">{{ $band }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="form-group-label">Focus Area</label>
                            <input type="text" name="practice_focus" class="form-control" placeholder="e.g., Matching Headings">
                        </div>
                    </div>
                </div>
                
                {{-- Writing Task Type (shown only for Writing skill) --}}
                <div class="row" id="writingTaskTypeRowCreate" style="display: none;">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-group-label is-required">Writing Task Type</label>
                            <select name="question_type" id="writingTaskTypeCreate" class="form-control">
                                <option value="">-- Select Task Type --</option>
                                <optgroup label="✏️ Task 1 (Academic)">
                                    <option value="task1_graph">📊 Graph/Chart/Table</option>
                                    <option value="task1_map">🗺️ Map/Diagram</option>
                                    <option value="task1_process">🔄 Process</option>
                                </optgroup>
                                <optgroup label="✉️ Task 1 (General)">
                                    <option value="task1_letter">✉️ Letter</option>
                                </optgroup>
                                <optgroup label="📝 Task 2">
                                    <option value="task2_essay">📝 Essay (Opinion/Discussion/Problem-Solution)</option>
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
                <div class="row" id="speakingPartTypeRowCreate" style="display: none;">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-group-label is-required">Speaking Part</label>
                            <select name="question_type" id="speakingPartTypeCreate" class="form-control">
                                <option value="">-- Select Part --</option>
                                <option value="part1">🗣️ Part 1 - Introduction & Interview (4-5 minutes)</option>
                                <option value="part2">📝 Part 2 - Individual Long Turn (3-4 minutes)</option>
                                <option value="part3">💭 Part 3 - Two-way Discussion (4-5 minutes)</option>
                            </select>
                            <small class="form-text text-muted">
                                <strong>Part 1:</strong> General questions about yourself and familiar topics<br>
                                <strong>Part 2:</strong> Speak on a given topic for 1-2 minutes<br>
                                <strong>Part 3:</strong> Deeper discussion on abstract ideas
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Instructions</label>
                    <textarea name="instructions" class="form-control" rows="2" 
                              placeholder="Instructions for students"></textarea>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="bg-white rounded-16 p-16 mb-16">
                <h3 class="font-14 font-weight-bold mb-16">Content</h3>
                
                <div class="form-group">
                    <label class="form-group-label">Instructions</label>
                    <textarea name="instructions" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Reading Passage / Text</label>
                    <textarea name="passage" class="summernote form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Audio File (for Listening)</label>
                    <div class="custom-file bg-white">
                        <input type="file" name="audio_file" class="custom-file-input" id="audioFile" accept="audio/*">
                        <label class="custom-file-label" for="audioFile">Choose audio file...</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Task Image (for Writing Task 1)</label>
                    <div class="custom-file bg-white">
                        <input type="file" name="task_image" class="custom-file-input" id="taskImage" accept="image/*">
                        <label class="custom-file-label" for="taskImage">Choose image file...</label>
                    </div>
                    <small class="form-text text-muted">Upload graph, chart, diagram, or map for Writing Task 1</small>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <x-iconsax-bul-tick-circle class="icons mr-8" width="16px" height="16px"/>Create & Add Questions
                </button>
            </div>
        </form>
    @endif
</section>
@endsection

<style>
.bg-info-light { background-color: rgba(23, 162, 184, 0.1); }
.skill-section:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
</style>

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 250,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table']],
                    ['view', ['codeview']]
                ]
            });
            
            // Custom file input label update
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').text(fileName || 'Choose file...');
            });
            
            // Show/hide Writing Task Type and Speaking Part based on skill selection
            $('#skillSelectCreate').on('change', function() {
                const skill = $(this).val();
                
                if (skill === 'writing') {
                    $('#writingTaskTypeRowCreate').slideDown();
                    $('#writingTaskTypeCreate').prop('required', true);
                    $('#speakingPartTypeRowCreate').slideUp();
                    $('#speakingPartTypeCreate').prop('required', false).val('');
                } else if (skill === 'speaking') {
                    $('#speakingPartTypeRowCreate').slideDown();
                    $('#speakingPartTypeCreate').prop('required', true);
                    $('#writingTaskTypeRowCreate').slideUp();
                    $('#writingTaskTypeCreate').prop('required', false).val('');
                } else {
                    $('#writingTaskTypeRowCreate').slideUp();
                    $('#writingTaskTypeCreate').prop('required', false).val('');
                    $('#speakingPartTypeRowCreate').slideUp();
                    $('#speakingPartTypeCreate').prop('required', false).val('');
                }
            });
        });
    </script>
@endpush
