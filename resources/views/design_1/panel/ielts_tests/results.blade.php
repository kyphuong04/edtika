@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .results-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .band-score-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        margin-bottom: 30px;
    }
    .band-score-display {
        font-size: 72px;
        font-weight: bold;
        line-height: 1;
        margin: 20px 0;
    }
    .skill-score-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        transition: transform 0.2s;
    }
    .skill-score-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    .score-bar {
        height: 12px;
        background: #e5e7eb;
        border-radius: 6px;
        overflow: hidden;
        margin-top: 10px;
    }
    .score-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #3b82f6);
        transition: width 0.5s ease;
        border-radius: 6px;
    }
    .band-descriptor {
        background: #f9fafb;
        border-left: 4px solid #3b82f6;
        padding: 15px 20px;
        border-radius: 8px;
        margin-top: 15px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    .stat-box {
        background: #f9fafb;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="results-container mt-30">
    {{-- Overall Band Score --}}
    <div class="band-score-card">
        <h2 class="mb-0">Overall Band Score</h2>
        @if($attempt->overall_band)
            <div class="band-score-display">{{ $attempt->overall_band }}</div>
            <p class="mb-0 font-18">
                @if($attempt->overall_band >= 8.0)
                    Very Good User
                @elseif($attempt->overall_band >= 7.0)
                    Good User
                @elseif($attempt->overall_band >= 6.0)
                    Competent User
                @elseif($attempt->overall_band >= 5.0)
                    Modest User
                @else
                    Limited User
                @endif
            </p>
        @else
            <div class="band-score-display">
                <i class="fas fa-clock"></i>
            </div>
            <p class="mb-0 font-18">Pending Manual Grading</p>
            <p class="mb-0 font-14 mt-2 opacity-75">
                Writing and Speaking sections are being graded by instructors
            </p>
        @endif
    </div>

    {{-- Test Info --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">Test</h6>
                    <p class="mb-0 font-weight-bold">{{ $test->title }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">Attempt</h6>
                    <p class="mb-0 font-weight-bold">#{{ $attempt->attempt_number }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">Completed</h6>
                    <p class="mb-0 font-weight-bold">{{ dateTimeFormat($attempt->completed_at, 'j M Y') }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">Duration</h6>
                    <p class="mb-0 font-weight-bold">{{ gmdate('H:i:s', $attempt->time_spent) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Skill Breakdown --}}
    <div class="row">
        @if($test->has_listening)
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-info mb-2">Listening</span>
                        <h4 class="mb-0">
                            @if($attempt->listening_band)
                                Band {{ $attempt->listening_band }}
                            @else
                                Pending
                            @endif
                        </h4>
                    </div>
                    <i class="fas fa-headphones fa-2x text-primary opacity-50"></i>
                </div>
                
                @if($attempt->listening_score !== null)
                    <div class="d-flex justify-content-between text-gray font-14 mb-2">
                        <span>Score</span>
                        <span class="font-weight-bold">{{ $attempt->listening_score }} / 40</span>
                    </div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: {{ ($attempt->listening_score / 40) * 100 }}%"></div>
                    </div>
                    
                    <div class="band-descriptor">
                        <strong>Correct Answers:</strong> {{ $attempt->listening_score }}
                    </div>
                @else
                    <p class="text-gray font-14 mb-0">Auto-grading in progress...</p>
                @endif
            </div>
        </div>
        @endif

        @if($test->has_reading)
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-success mb-2">Reading</span>
                        <h4 class="mb-0">
                            @if($attempt->reading_band)
                                Band {{ $attempt->reading_band }}
                            @else
                                Pending
                            @endif
                        </h4>
                    </div>
                    <i class="fas fa-book-open fa-2x text-success opacity-50"></i>
                </div>
                
                @if($attempt->reading_score !== null)
                    <div class="d-flex justify-content-between text-gray font-14 mb-2">
                        <span>Score</span>
                        <span class="font-weight-bold">{{ $attempt->reading_score }} / 40</span>
                    </div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: {{ ($attempt->reading_score / 40) * 100 }}%"></div>
                    </div>
                    
                    <div class="band-descriptor">
                        <strong>Correct Answers:</strong> {{ $attempt->reading_score }}
                    </div>
                @else
                    <p class="text-gray font-14 mb-0">Auto-grading in progress...</p>
                @endif
            </div>
        </div>
        @endif

        @if($test->has_writing)
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-warning mb-2">Writing</span>
                        <h4 class="mb-0">
                            @if($attempt->writing_band)
                                Band {{ $attempt->writing_band }}
                            @else
                                <span class="text-warning">Pending Grading</span>
                            @endif
                        </h4>
                    </div>
                    <i class="fas fa-pencil-alt fa-2x text-warning opacity-50"></i>
                </div>
                
                @if($attempt->writing_band)
                    <div class="band-descriptor">
                        <strong>Task Achievement:</strong> Band {{ $attempt->writing_band }}<br>
                        <strong>Coherence & Cohesion:</strong> Band {{ $attempt->writing_band }}<br>
                        <strong>Lexical Resource:</strong> Band {{ $attempt->writing_band }}<br>
                        <strong>Grammar & Accuracy:</strong> Band {{ $attempt->writing_band }}
                    </div>
                @else
                    <p class="text-gray font-14 mb-0">
                        <i class="fas fa-clock mr-1"></i>
                        Your writing is being carefully reviewed by an instructor
                    </p>
                @endif
            </div>
        </div>
        @endif

        @if($test->has_speaking)
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-danger mb-2">Speaking</span>
                        <h4 class="mb-0">
                            @if($attempt->speaking_band)
                                Band {{ $attempt->speaking_band }}
                            @else
                                <span class="text-warning">Pending Grading</span>
                            @endif
                        </h4>
                    </div>
                    <i class="fas fa-microphone fa-2x text-danger opacity-50"></i>
                </div>
                
                @if($attempt->speaking_band)
                    <div class="band-descriptor">
                        <strong>Fluency & Coherence:</strong> Band {{ $attempt->speaking_band }}<br>
                        <strong>Lexical Resource:</strong> Band {{ $attempt->speaking_band }}<br>
                        <strong>Grammatical Range:</strong> Band {{ $attempt->speaking_band }}<br>
                        <strong>Pronunciation:</strong> Band {{ $attempt->speaking_band }}
                    </div>
                @else
                    <p class="text-gray font-14 mb-0">
                        <i class="fas fa-clock mr-1"></i>
                        Your speaking is being carefully reviewed by an instructor
                    </p>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Overall Statistics --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Test Statistics</h5>
        </div>
        <div class="card-body">
            <div class="stats-grid">
                <div class="stat-box">
                    <i class="fas fa-tasks fa-2x text-primary mb-2"></i>
                    <h6 class="text-gray mb-1">Total Questions</h6>
                    <h4 class="mb-0">{{ $attempt->total_questions }}</h4>
                </div>
                <div class="stat-box">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h6 class="text-gray mb-1">Answered</h6>
                    <h4 class="mb-0">{{ $attempt->answered_questions }}</h4>
                </div>
                <div class="stat-box">
                    <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                    <h6 class="text-gray mb-1">Progress</h6>
                    <h4 class="mb-0">{{ round($attempt->progress_percentage, 1) }}%</h4>
                </div>
                <div class="stat-box">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h6 class="text-gray mb-1">Time Spent</h6>
                    <h4 class="mb-0">{{ gmdate('H:i', $attempt->time_spent) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Next Steps</h5>
                    <p class="text-gray mb-0">Review your answers or take another test</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('panel.ielts_tests.review', $attempt->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye mr-2"></i>
                        Review Answers
                    </a>
                    <a href="{{ route('panel.ielts_tests.index') }}" class="btn btn-primary">
                        <i class="fas fa-th-list mr-2"></i>
                        Back to Tests
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Band Score Guide --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">IELTS Band Score Guide</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 9.0 - Expert User</h6>
                    <p class="text-gray font-14">Full operational command of the language</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 8.0 - Very Good User</h6>
                    <p class="text-gray font-14">Fully operational with occasional inaccuracies</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 7.0 - Good User</h6>
                    <p class="text-gray font-14">Operational command with occasional inaccuracies</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 6.0 - Competent User</h6>
                    <p class="text-gray font-14">Effective command despite inaccuracies</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 5.0 - Modest User</h6>
                    <p class="text-gray font-14">Partial command, copes with overall meaning</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 4.0 - Limited User</h6>
                    <p class="text-gray font-14">Basic competence in familiar situations</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
