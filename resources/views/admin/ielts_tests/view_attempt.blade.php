@extends('admin.layouts.app')

@push('styles_top')
<style>
    .skill-card {
        border-radius: 12px;
        margin-bottom: 20px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .skill-header {
        padding: 16px 20px;
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .skill-header.listening { background: linear-gradient(135deg, #1a3a5c 0%, #2d5a87 100%); }
    .skill-header.reading { background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); }
    .skill-header.writing { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); }
    .skill-header.speaking { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
    
    .skill-header h5 {
        color: white;
        margin: 0;
        font-weight: 600;
    }
    .skill-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 16px;
    }
    .answer-item {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }
    .answer-item:last-child {
        border-bottom: none;
    }
    .question-text {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
        border-left: 4px solid #3b82f6;
    }
    .student-answer {
        background: #fff7ed;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
        border-left: 4px solid #f59e0b;
    }
    .grading-form {
        background: #f0fdf4;
        padding: 16px;
        border-radius: 8px;
        margin-top: 12px;
    }
    .band-input {
        width: 80px;
        text-align: center;
        font-weight: bold;
        font-size: 16px;
    }
    .graded-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: #dcfce7;
        color: #166534;
        border-radius: 20px;
        font-weight: 500;
    }
    .criteria-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #e5e7eb;
    }
    .criteria-row:last-child {
        border-bottom: none;
    }
    .overall-score {
        font-size: 24px;
        font-weight: bold;
        color: #1f2937;
    }
    .audio-player-container {
        margin: 12px 0;
        padding: 12px;
        background: #f1f5f9;
        border-radius: 8px;
    }
    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .progress-ring {
        width: 80px;
        height: 80px;
    }
</style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.attempts') }}">Grading</a></div>
                <div class="breadcrumb-item">View Attempt</div>
            </div>
        </div>

        {{-- Student Info & Summary --}}
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="summary-card">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $attempt->user->getAvatar(60) }}" class="rounded-circle mr-3" width="60" height="60" alt="">
                        <div>
                            <h5 class="mb-0">{{ $attempt->user->full_name }}</h5>
                            <small class="text-muted">{{ $attempt->user->email }}</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted d-block">Test</small>
                            <span class="font-weight-bold">{{ $attempt->test->title }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Completed</small>
                            <span class="font-weight-bold">{{ $attempt->completed_at ? date('d/m/Y H:i', $attempt->completed_at) : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="summary-card">
                    <h6 class="mb-3">Score Summary</h6>
                    <div class="row">
                        <div class="col-md-2 col-4 text-center mb-3">
                            <div class="skill-badge" style="background: #1a3a5c; color: white;">
                                L: {{ $attempt->listening_score ? number_format($attempt->listening_score, 1) : '-' }}
                            </div>
                        </div>
                        <div class="col-md-2 col-4 text-center mb-3">
                            <div class="skill-badge" style="background: #3b82f6; color: white;">
                                R: {{ $attempt->reading_score ? number_format($attempt->reading_score, 1) : '-' }}
                            </div>
                        </div>
                        <div class="col-md-2 col-4 text-center mb-3">
                            <div class="skill-badge" style="background: #8b5cf6; color: white;">
                                W: {{ $attempt->writing_score ? number_format($attempt->writing_score, 1) : '-' }}
                            </div>
                        </div>
                        <div class="col-md-2 col-4 text-center mb-3">
                            <div class="skill-badge" style="background: #10b981; color: white;">
                                S: {{ $attempt->speaking_score ? number_format($attempt->speaking_score, 1) : '-' }}
                            </div>
                        </div>
                        <div class="col-md-4 col-8 text-center mb-3">
                            <div class="skill-badge" style="background: #1f2937; color: white; font-size: 20px; padding: 10px 24px;">
                                Overall: {{ $attempt->overall_band ? number_format($attempt->overall_band, 1) : '-' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Writing Grading Progress</label>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" style="width: {{ $writingProgress }}%; background: #8b5cf6;"></div>
                            </div>
                            <small class="text-muted">{{ $writingProgress }}% completed</small>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Speaking Grading Progress</label>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" style="width: {{ $speakingProgress }}%; background: #10b981;"></div>
                            </div>
                            <small class="text-muted">{{ $speakingProgress }}% completed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Navigation --}}
        <div class="mb-4">
            <a href="#listening-section" class="btn btn-sm mr-2" style="background: #1a3a5c; color: white;">
                <x-iconsax-lin-headphones width="16" height="16"/> Listening ({{ count($answersBySkill['listening']) }})
            </a>
            <a href="#reading-section" class="btn btn-sm mr-2" style="background: #3b82f6; color: white;">
                <x-iconsax-lin-book width="16" height="16"/> Reading ({{ count($answersBySkill['reading']) }})
            </a>
            <a href="#writing-section" class="btn btn-sm mr-2" style="background: #8b5cf6; color: white;">
                <x-iconsax-lin-edit width="16" height="16"/> Writing ({{ count($answersBySkill['writing']) }})
            </a>
            <a href="#speaking-section" class="btn btn-sm" style="background: #10b981; color: white;">
                <x-iconsax-lin-microphone width="16" height="16"/> Speaking ({{ count($answersBySkill['speaking']) }})
            </a>
        </div>

        {{-- Listening Section (Auto-graded) --}}
        @if(count($answersBySkill['listening']) > 0)
        <div class="skill-card" id="listening-section">
            <div class="skill-header listening">
                <h5><x-iconsax-bul-headphones width="24" height="24" class="mr-2"/> Listening Answers</h5>
                <span class="skill-badge bg-white text-dark">
                    {{ $attempt->listening_score ? number_format($attempt->listening_score, 1) : 'Auto-graded' }}
                </span>
            </div>
            <div class="card-body">
                @foreach($answersBySkill['listening'] as $answer)
                    <div class="answer-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <span class="badge badge-secondary mr-2">Q{{ $answer->question->question_number ?? $loop->iteration }}</span>
                                <span class="font-weight-500">{{ $answer->question->question_text }}</span>
                            </div>
                            @if($answer->is_correct !== null)
                                @if($answer->is_correct)
                                    <span class="badge badge-success"><x-iconsax-lin-tick-circle width="14" height="14"/> Correct</span>
                                @else
                                    <span class="badge badge-danger"><x-iconsax-lin-close-circle width="14" height="14"/> Incorrect</span>
                                @endif
                            @endif
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Student's Answer:</small>
                            <strong class="ml-2">{{ $answer->answer_text ?: 'No answer' }}</strong>
                            @if(!$answer->is_correct && $answer->question->correct_answer)
                                <span class="text-success ml-3">
                                    <small>Correct:</small> <strong>{{ $answer->question->correct_answer }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Reading Section (Auto-graded) --}}
        @if(count($answersBySkill['reading']) > 0)
        <div class="skill-card" id="reading-section">
            <div class="skill-header reading">
                <h5><x-iconsax-bul-book width="24" height="24" class="mr-2"/> Reading Answers</h5>
                <span class="skill-badge bg-white text-dark">
                    {{ $attempt->reading_score ? number_format($attempt->reading_score, 1) : 'Auto-graded' }}
                </span>
            </div>
            <div class="card-body">
                @foreach($answersBySkill['reading'] as $answer)
                    <div class="answer-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <span class="badge badge-secondary mr-2">Q{{ $answer->question->question_number ?? $loop->iteration }}</span>
                                <span class="font-weight-500">{{ $answer->question->question_text }}</span>
                            </div>
                            @if($answer->is_correct !== null)
                                @if($answer->is_correct)
                                    <span class="badge badge-success"><x-iconsax-lin-tick-circle width="14" height="14"/> Correct</span>
                                @else
                                    <span class="badge badge-danger"><x-iconsax-lin-close-circle width="14" height="14"/> Incorrect</span>
                                @endif
                            @endif
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Student's Answer:</small>
                            <strong class="ml-2">{{ $answer->answer_text ?: 'No answer' }}</strong>
                            @if(!$answer->is_correct && $answer->question->correct_answer)
                                <span class="text-success ml-3">
                                    <small>Correct:</small> <strong>{{ $answer->question->correct_answer }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Writing Section (Manual Grading) --}}
        @if(count($answersBySkill['writing']) > 0)
        <div class="skill-card" id="writing-section">
            <div class="skill-header writing">
                <h5><x-iconsax-bul-edit width="24" height="24" class="mr-2"/> Writing Answers</h5>
                <span class="skill-badge bg-white text-dark">
                    {{ $attempt->writing_score ? number_format($attempt->writing_score, 1) : 'Needs Grading' }}
                </span>
            </div>
            <div class="card-body">
                @foreach($answersBySkill['writing'] as $answer)
                    <div class="answer-item" id="writing-answer-{{ $answer->id }}">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge badge-purple mr-2" style="background: #8b5cf6; color: white;">
                                    Task {{ $answer->question->question_number ?? $loop->iteration }}
                                </span>
                                @if($answer->graded_at)
                                    <span class="graded-badge">
                                        <x-iconsax-lin-tick-circle width="16" height="16"/>
                                        Graded by {{ $answer->grader->full_name ?? 'System' }}
                                    </span>
                                @endif
                            </div>
                            @if($answer->points_earned)
                                <span class="skill-badge" style="background: #8b5cf6; color: white;">
                                    Band {{ number_format($answer->points_earned, 1) }}
                                </span>
                            @endif
                        </div>
                        
                        {{-- Question --}}
                        <div class="question-text">
                            <strong>Task:</strong><br>
                            {!! nl2br(e($answer->question->question_text)) !!}
                        </div>
                        
                        {{-- Student's Answer --}}
                        <div class="student-answer">
                            <strong>Student's Response:</strong>
                            <div class="mt-2" style="white-space: pre-wrap;">{{ $answer->answer_text ?: 'No response submitted' }}</div>
                            @if($answer->answer_text)
                                <small class="text-muted">Word count: {{ str_word_count($answer->answer_text) }}</small>
                            @endif
                        </div>
                        
                        {{-- Grading Form --}}
                        @if($answer->graded_at)
                            {{-- Show existing grades --}}
                            <div class="grading-form">
                                <h6 class="mb-3">Grading Details</h6>
                                <div class="row">
                                    @php $bands = $answer->writing_bands ?? []; @endphp
                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">Task Achievement</small>
                                        <span class="font-weight-bold">{{ $bands['task_achievement'] ?? '-' }}</span>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">Coherence & Cohesion</small>
                                        <span class="font-weight-bold">{{ $bands['coherence_cohesion'] ?? '-' }}</span>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">Lexical Resource</small>
                                        <span class="font-weight-bold">{{ $bands['lexical_resource'] ?? '-' }}</span>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">Grammar</small>
                                        <span class="font-weight-bold">{{ $bands['grammatical_range'] ?? '-' }}</span>
                                    </div>
                                </div>
                                @if($answer->grader_feedback)
                                    <div class="mt-3">
                                        <small class="text-muted d-block">Feedback</small>
                                        <p class="mb-0">{{ $answer->grader_feedback }}</p>
                                    </div>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="showGradingForm('writing', {{ $answer->id }})">
                                    <x-iconsax-lin-edit width="16" height="16"/> Re-grade
                                </button>
                            </div>
                        @else
                            {{-- Grading form --}}
                            <div class="grading-form" id="grading-form-writing-{{ $answer->id }}">
                                <h6 class="mb-3"><x-iconsax-lin-award width="18" height="18"/> Grade Writing</h6>
                                <form class="grading-form-writing" data-answer-id="{{ $answer->id }}">
                                    <div class="row">
                                        <div class="col-md-3 col-6 mb-3">
                                            <label class="small font-weight-bold">Task Achievement</label>
                                            <select name="task_achievement" class="form-control band-input" required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <label class="small font-weight-bold">Coherence & Cohesion</label>
                                            <select name="coherence_cohesion" class="form-control band-input" required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <label class="small font-weight-bold">Lexical Resource</label>
                                            <select name="lexical_resource" class="form-control band-input" required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <label class="small font-weight-bold">Grammar</label>
                                            <select name="grammatical_range" class="form-control band-input" required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Feedback (optional)</label>
                                        <textarea name="feedback" class="form-control" rows="3" placeholder="Provide feedback for the student..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <x-iconsax-lin-tick-circle width="18" height="18"/> Submit Grade
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Speaking Section (Manual Grading) --}}
        @if(count($answersBySkill['speaking']) > 0)
        <div class="skill-card" id="speaking-section">
            <div class="skill-header speaking">
                <h5><x-iconsax-bul-microphone width="24" height="24" class="mr-2"/> Speaking Answers</h5>
                <span class="skill-badge bg-white text-dark">
                    {{ $attempt->speaking_score ? number_format($attempt->speaking_score, 1) : 'Needs Grading' }}
                </span>
            </div>
            <div class="card-body">
                @foreach($answersBySkill['speaking'] as $answer)
                    <div class="answer-item" id="speaking-answer-{{ $answer->id }}">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge mr-2" style="background: #10b981; color: white;">
                                    Part {{ $answer->question->question_number ?? $loop->iteration }}
                                </span>
                                @if($answer->graded_at)
                                    <span class="graded-badge">
                                        <x-iconsax-lin-tick-circle width="16" height="16"/>
                                        Graded by {{ $answer->grader->full_name ?? 'System' }}
                                    </span>
                                @endif
                            </div>
                            @if($answer->points_earned)
                                <span class="skill-badge" style="background: #10b981; color: white;">
                                    Band {{ number_format($answer->points_earned, 1) }}
                                </span>
                            @endif
                        </div>
                        
                        {{-- Question --}}
                        <div class="question-text">
                            <strong>Question:</strong><br>
                            {!! nl2br(e($answer->question->question_text)) !!}
                        </div>
                        
                        {{-- Audio Recording --}}
                        @if($answer->audio_url)
                            <div class="audio-player-container">
                                <strong><x-iconsax-lin-microphone-2 width="18" height="18"/> Student's Recording:</strong>
                                <audio controls class="w-100 mt-2">
                                    <source src="{{ $answer->audio_url }}" type="audio/webm">
                                    <source src="{{ $answer->audio_url }}" type="audio/mp3">
                                    Your browser does not support audio playback.
                                </audio>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <x-iconsax-lin-warning-2 width="18" height="18"/> No audio recording submitted
                            </div>
                        @endif
                        
                        {{-- Transcript if available (only show if answer_text is not an audio URL) --}}
                        @if($answer->answer_text && !$answer->hasAudioRecording())
                            <div class="student-answer">
                                <strong>Transcript:</strong>
                                <div class="mt-2">{{ $answer->answer_text }}</div>
                            </div>
                        @elseif($answer->answer_text && $answer->hasAudioRecording() && $answer->answer_text !== $answer->audio_url)
                            {{-- Show transcript if it exists and is different from audio URL --}}
                            <div class="student-answer">
                                <strong>Transcript:</strong>
                                <div class="mt-2">{{ $answer->answer_text }}</div>
                            </div>
                        @endif
                        
                        {{-- Grading Form --}}
                        @if($answer->graded_at)
                            {{-- Show existing grades --}}
                            <div class="grading-form">
                                <h6 class="mb-3">Grading Details</h6>
                                <div class="row">
                                    @php $bands = $answer->speaking_bands ?? []; @endphp
                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">Fluency & Coherence</small>
                                        <span class="font-weight-bold">{{ $bands['fluency_coherence'] ?? '-' }}</span>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">Lexical Resource</small>
                                        <span class="font-weight-bold">{{ $bands['lexical_resource'] ?? '-' }}</span>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">Grammar</small>
                                        <span class="font-weight-bold">{{ $bands['grammatical_range'] ?? '-' }}</span>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <small class="text-muted d-block">Pronunciation</small>
                                        <span class="font-weight-bold">{{ $bands['pronunciation'] ?? '-' }}</span>
                                    </div>
                                </div>
                                @if($answer->grader_feedback)
                                    <div class="mt-3">
                                        <small class="text-muted d-block">Feedback</small>
                                        <p class="mb-0">{{ $answer->grader_feedback }}</p>
                                    </div>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="showGradingForm('speaking', {{ $answer->id }})">
                                    <x-iconsax-lin-edit width="16" height="16"/> Re-grade
                                </button>
                            </div>
                        @else
                            {{-- Grading form --}}
                            <div class="grading-form" id="grading-form-speaking-{{ $answer->id }}">
                                <h6 class="mb-3"><x-iconsax-lin-award width="18" height="18"/> Grade Speaking</h6>
                                <form class="grading-form-speaking" data-answer-id="{{ $answer->id }}">
                                    <div class="row">
                                        <div class="col-md-3 col-6 mb-3">
                                            <label class="small font-weight-bold">Fluency & Coherence</label>
                                            <select name="fluency_coherence" class="form-control band-input" required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <label class="small font-weight-bold">Lexical Resource</label>
                                            <select name="lexical_resource" class="form-control band-input" required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <label class="small font-weight-bold">Grammar</label>
                                            <select name="grammatical_range" class="form-control band-input" required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <label class="small font-weight-bold">Pronunciation</label>
                                            <select name="pronunciation" class="form-control band-input" required>
                                                <option value="">-</option>
                                                @for($i = 0; $i <= 9; $i += 0.5)
                                                    <option value="{{ $i }}">{{ number_format($i, 1) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Feedback (optional)</label>
                                        <textarea name="feedback" class="form-control" rows="3" placeholder="Provide feedback for the student..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <x-iconsax-lin-tick-circle width="18" height="18"/> Submit Grade
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Back Button --}}
        <div class="mt-4">
            <a href="{{ route('admin.ielts_tests.attempts') }}" class="btn btn-secondary">
                <x-iconsax-lin-arrow-left width="18" height="18"/> Back to Attempts
            </a>
        </div>
    </section>
@endsection

@push('scripts_bottom')
<script>
    // Handle Writing grading forms
    document.querySelectorAll('.grading-form-writing').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const answerId = this.dataset.answerId;
            const formData = new FormData(this);
            
            submitGrade(answerId, formData, 'writing');
        });
    });
    
    // Handle Speaking grading forms
    document.querySelectorAll('.grading-form-speaking').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const answerId = this.dataset.answerId;
            const formData = new FormData(this);
            
            submitGrade(answerId, formData, 'speaking');
        });
    });
    
    function submitGrade(answerId, formData, skill) {
        const submitBtn = document.querySelector(`form[data-answer-id="${answerId}"] button[type="submit"]`);
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
        
        // Convert FormData to object
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        
        fetch(`{{ url('/admin/ielts-tests/answers') }}/${answerId}/grade`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Graded!',
                    text: `Band Score: ${result.band_score}`,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Failed to submit grade'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while submitting the grade'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }
    
    function showGradingForm(skill, answerId) {
        // For re-grading, we need to show the form again
        // This could be enhanced with a modal
        Swal.fire({
            title: 'Re-grade this answer?',
            text: 'This will allow you to update the grades.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, re-grade',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload(); // Simple reload for now - could be enhanced
            }
        });
    }
</script>
@endpush
