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
        <h2 class="mb-0">{{ trans('update.overall_band_score') }}</h2>
        @if($attempt->overall_band)
            <div class="band-score-display">{{ $attempt->overall_band }}</div>
            <p class="mb-0 font-18">
                @if($attempt->overall_band >= 8.0)
                    {{ trans('update.very_good_user') }}
                @elseif($attempt->overall_band >= 7.0)
                    {{ trans('update.good_user') }}
                @elseif($attempt->overall_band >= 6.0)
                    {{ trans('update.competent_user') }}
                @elseif($attempt->overall_band >= 5.0)
                    {{ trans('update.modest_user') }}
                @else
                    {{ trans('update.limited_user') }}
                @endif
            </p>
        @else
            <div class="band-score-display">
                <i class="fas fa-clock"></i>
            </div>
            <p class="mb-0 font-18">{{ trans('update.pending_manual_grading') }}</p>
            <p class="mb-0 font-14 mt-2 opacity-75">
                {{ trans('update.grading_by_instructors_hint') }}
            </p>
        @endif
    </div>

    {{-- Test Info --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">{{ trans('update.test') }}</h6>
                    <p class="mb-0 font-weight-bold">{{ $test->title }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">{{ trans('update.attempt') }}</h6>
                    <p class="mb-0 font-weight-bold">#{{ $attempt->attempt_number }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">{{ trans('admin/main.completed') }}</h6>
                    <p class="mb-0 font-weight-bold">{{ dateTimeFormat($attempt->completed_at, 'j M Y') }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">{{ trans('update.duration') }}</h6>
                    <p class="mb-0 font-weight-bold">{{ gmdate('H:i:s', $attempt->time_spent) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Skill Breakdown --}}
    @php
        // Band conversion function
        if (!function_exists('rawToBand')) {
            function rawToBand($raw, $skill = 'listening') {
                $conversionTable = [
                    39 => 9.0, 40 => 9.0,
                    37 => 8.5, 38 => 8.5,
                    35 => 8.0, 36 => 8.0,
                    33 => 7.5, 34 => 7.5,
                    30 => 7.0, 31 => 7.0, 32 => 7.0,
                    27 => 6.5, 28 => 6.5, 29 => 6.5,
                    23 => 6.0, 24 => 6.0, 25 => 6.0, 26 => 6.0,
                    18 => 5.5, 19 => 5.5, 20 => 5.5, 21 => 5.5, 22 => 5.5,
                    16 => 5.0, 17 => 5.0,
                    13 => 4.5, 14 => 4.5, 15 => 4.5,
                    11 => 4.0, 12 => 4.0,
                    8 => 3.5, 9 => 3.5, 10 => 3.5,
                    6 => 3.0, 7 => 3.0,
                    4 => 2.5, 5 => 2.5,
                    3 => 2.0,
                    2 => 1.0,
                    1 => 1.0,
                    0 => 0.0
                ];
                return $conversionTable[$raw] ?? 0;
            }
        }
    @endphp
    <div class="row">
        @if($test->has_listening)
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-info mb-2">{{ trans('update.listening') }}</span>
                        <h4 class="mb-0">
                            @if($attempt->listening_score !== null)
                                {{ trans('update.band') }} {{ rawToBand($attempt->listening_score) }}
                            @else
                                {{ trans('admin/main.pending') }}
                            @endif
                        </h4>
                    </div>
                    <i class="fas fa-headphones fa-2x text-primary opacity-50"></i>
                </div>
                
                @if($attempt->listening_score !== null)
                    <div class="d-flex justify-content-between text-gray font-14 mb-2">
                        <span>{{ trans('update.score') }}</span>
                        <span class="font-weight-bold">{{ $attempt->listening_score }} / 40</span>
                    </div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: {{ ($attempt->listening_score / 40) * 100 }}%"></div>
                    </div>
                    
                    <div class="band-descriptor">
                        <strong>{{ trans('update.correct_answers') }}:</strong> {{ $attempt->listening_score }}
                    </div>
                @else
                    <p class="text-gray font-14 mb-0">{{ trans('update.auto_grading_in_progress') }}</p>
                @endif
            </div>
        </div>
        @endif

        @if($test->has_reading)
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-success mb-2">{{ trans('update.reading') }}</span>
                        <h4 class="mb-0">
                            @if($attempt->reading_score !== null)
                                {{ trans('update.band') }} {{ rawToBand($attempt->reading_score) }}
                            @else
                                {{ trans('admin/main.pending') }}
                            @endif
                        </h4>
                    </div>
                    <i class="fas fa-book-open fa-2x text-success opacity-50"></i>
                </div>
                
                @if($attempt->reading_score !== null)
                    <div class="d-flex justify-content-between text-gray font-14 mb-2">
                        <span>{{ trans('update.score') }}</span>
                        <span class="font-weight-bold">{{ $attempt->reading_score }} / 40</span>
                    </div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: {{ ($attempt->reading_score / 40) * 100 }}%"></div>
                    </div>
                    
                    <div class="band-descriptor">
                        <strong>{{ trans('update.correct_answers') }}:</strong> {{ $attempt->reading_score }}
                    </div>
                @else
                    <p class="text-gray font-14 mb-0">{{ trans('update.auto_grading_in_progress') }}</p>
                @endif
            </div>
        </div>
        @endif

        @if($test->has_writing)
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-warning mb-2">{{ trans('update.writing') }}</span>
                        <h4 class="mb-0">
                            @if($attempt->writing_band)
                                {{ trans('update.band') }} {{ $attempt->writing_band }}
                            @else
                                <span class="text-warning">{{ trans('update.pending_grading') }}</span>
                            @endif
                        </h4>
                    </div>
                    <i class="fas fa-pencil-alt fa-2x text-warning opacity-50"></i>
                </div>
                
                @if($attempt->writing_band)
                    @php
                        $writingCriteria = $attempt->writing_criteria ?? [];
                    @endphp
                    <div class="band-descriptor">
                        <strong>{{ trans('update.task_achievement') }}:</strong> {{ trans('update.band') }} {{ $writingCriteria['task_response'] ?? $attempt->writing_band }}<br>
                        <strong>{{ trans('update.coherence_cohesion') }}:</strong> {{ trans('update.band') }} {{ $writingCriteria['coherence_cohesion'] ?? $attempt->writing_band }}<br>
                        <strong>{{ trans('update.lexical_resource') }}:</strong> {{ trans('update.band') }} {{ $writingCriteria['lexical_resource'] ?? $attempt->writing_band }}<br>
                        <strong>{{ trans('update.grammatical_accuracy') }}:</strong> {{ trans('update.band') }} {{ $writingCriteria['grammatical_accuracy'] ?? $attempt->writing_band }}
                    </div>
                    @if($attempt->writing_feedback)
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong class="d-block mb-1"><i class="fas fa-comment-dots text-primary mr-1"></i> {{ trans('update.teacher_feedback_box') }}:</strong>
                            <p class="mb-0 text-gray">{{ $attempt->writing_feedback }}</p>
                        </div>
                    @endif
                @else
                    <p class="text-gray font-14 mb-0">
                        <i class="fas fa-clock mr-1"></i>
                        {{ trans('update.writing_review_hint') }}
                    </p>
                    {{-- Grade button for teachers/admins --}}
                    @if(auth()->user()->isTeacher() || auth()->user()->isAdmin() || auth()->user()->isOrganization())
                        <a href="{{ route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'writing']) }}" class="btn btn-warning btn-sm mt-3">
                            <i class="fas fa-pen mr-1"></i> {{ trans('update.grade_writing') }}
                        </a>
                    @endif
                @endif
            </div>
        </div>
        @endif

        @if($test->has_speaking)
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-danger mb-2">{{ trans('update.speaking') }}</span>
                        <h4 class="mb-0">
                            @if($attempt->speaking_band)
                                {{ trans('update.band') }} {{ $attempt->speaking_band }}
                            @else
                                <span class="text-warning">{{ trans('update.pending_grading') }}</span>
                            @endif
                        </h4>
                    </div>
                    <i class="fas fa-microphone fa-2x text-danger opacity-50"></i>
                </div>
                
                @if($attempt->speaking_band)
                    @php
                        $speakingCriteria = $attempt->speaking_criteria ?? [];
                    @endphp
                    <div class="band-descriptor">
                        <strong>{{ trans('update.fluency_coherence') }}:</strong> {{ trans('update.band') }} {{ $speakingCriteria['fluency_coherence'] ?? $attempt->speaking_band }}<br>
                        <strong>{{ trans('update.lexical_resource') }}:</strong> {{ trans('update.band') }} {{ $speakingCriteria['lexical_resource'] ?? $attempt->speaking_band }}<br>
                        <strong>{{ trans('update.grammatical_range') }}:</strong> {{ trans('update.band') }} {{ $speakingCriteria['grammatical_range'] ?? $attempt->speaking_band }}<br>
                        <strong>{{ trans('update.pronunciation') }}:</strong> {{ trans('update.band') }} {{ $speakingCriteria['pronunciation'] ?? $attempt->speaking_band }}
                    </div>
                    @if($attempt->speaking_feedback)
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong class="d-block mb-1"><i class="fas fa-comment-dots text-primary mr-1"></i> {{ trans('update.teacher_feedback_box') }}:</strong>
                            <p class="mb-0 text-gray">{{ $attempt->speaking_feedback }}</p>
                        </div>
                    @endif
                @else
                    <p class="text-gray font-14 mb-0">
                        <i class="fas fa-clock mr-1"></i>
                        {{ trans('update.speaking_review_hint') }}
                    </p>
                    {{-- Grade button for teachers/admins --}}
                    @if(auth()->user()->isTeacher() || auth()->user()->isAdmin() || auth()->user()->isOrganization())
                        <a href="{{ route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'speaking']) }}" class="btn btn-danger btn-sm mt-3">
                            <i class="fas fa-microphone mr-1"></i> {{ trans('update.grade_speaking') }}
                        </a>
                    @endif
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Overall Statistics --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">{{ trans('update.test_statistics') }}</h5>
        </div>
        <div class="card-body">
            <div class="stats-grid">
                <div class="stat-box">
                    <i class="fas fa-tasks fa-2x text-primary mb-2"></i>
                    <h6 class="text-gray mb-1">{{ trans('update.total_questions') }}</h6>
                    <h4 class="mb-0">{{ $attempt->total_questions }}</h4>
                </div>
                <div class="stat-box">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h6 class="text-gray mb-1">{{ trans('update.answered_stat') }}</h6>
                    <h4 class="mb-0">{{ $attempt->answered_questions }}</h4>
                </div>
                <div class="stat-box">
                    <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                    <h6 class="text-gray mb-1">{{ trans('update.progress') }}</h6>
                    <h4 class="mb-0">{{ round($attempt->progress_percentage, 1) }}%</h4>
                </div>
                <div class="stat-box">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h6 class="text-gray mb-1">{{ trans('update.time_spent') }}</h6>
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
                    <h5 class="mb-1">{{ trans('update.whats_next') }}</h5>
                    <p class="text-gray mb-0">{{ trans('update.next_steps_hint') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('panel.ielts_tests.review', $attempt->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye mr-2"></i>
                        {{ trans('update.review_answers') }}
                    </a>
                    <a href="{{ route('panel.ielts_tests.index') }}" class="btn btn-primary">
                        <i class="fas fa-th-list mr-2"></i>
                        {{ trans('update.back_to_tests') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Band Score Guide --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">{{ trans('update.ielts_band_score_guide') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold">{{ trans('update.band_9_expert_user') }}</h6>
                    <p class="text-gray font-14">{{ trans('update.band_9_desc') }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">{{ trans('update.band_8_very_good_user') }}</h6>
                    <p class="text-gray font-14">{{ trans('update.band_8_desc') }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">{{ trans('update.band_7_good_user') }}</h6>
                    <p class="text-gray font-14">{{ trans('update.band_7_desc') }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">{{ trans('update.band_6_competent_user') }}</h6>
                    <p class="text-gray font-14">{{ trans('update.band_6_desc') }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">{{ trans('update.band_5_modest_user') }}</h6>
                    <p class="text-gray font-14">{{ trans('update.band_5_desc') }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">{{ trans('update.band_4_limited_user') }}</h6>
                    <p class="text-gray font-14">{{ trans('update.band_4_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
