@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .test-details-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .test-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 16px;
        margin-bottom: 30px;
    }
    .attempt-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.2s;
    }
    .attempt-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="test-details-container mt-30">
    <div class="test-hero">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="mb-2">{{ $test->title }}</h1>
                <p class="mb-0 opacity-90">{{ $test->description }}</p>
            </div>
            <span class="badge badge-light badge-lg">
                {{ ucfirst($test->type) }} Test
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Test Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-gray mb-1">Format</h6>
                            <p class="mb-0 font-weight-bold">{{ ucfirst($test->format) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-gray mb-1">Duration</h6>
                            <p class="mb-0 font-weight-bold">{{ $test->total_duration }} minutes</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-gray mb-1">Skills</h6>
                            <div>
                                @if($test->has_listening)
                                    <span class="badge badge-info mr-1">Listening</span>
                                @endif
                                @if($test->has_reading)
                                    <span class="badge badge-success mr-1">Reading</span>
                                @endif
                                @if($test->has_writing)
                                    <span class="badge badge-warning mr-1">Writing</span>
                                @endif
                                @if($test->has_speaking)
                                    <span class="badge badge-danger mr-1">Speaking</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-gray mb-1">Difficulty</h6>
                            <p class="mb-0 font-weight-bold">{{ ucfirst($test->difficulty_level) }}</p>
                        </div>
                    </div>

                    @if($test->target_band_min && $test->target_band_max)
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-bullseye mr-2"></i>
                        Target band: {{ $test->target_band_min }} - {{ $test->target_band_max }}
                    </div>
                    @endif
                </div>
            </div>

            @if($attempts->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Your Attempts</h5>
                </div>
                <div class="card-body">
                    @foreach($attempts as $attempt)
                    <div class="attempt-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Attempt #{{ $attempt->attempt_number }}</h6>
                                <p class="text-gray font-14 mb-0">
                                    {{ dateTimeFormat($attempt->created_at, 'j M Y, H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                @if($attempt->status === 'completed')
                                    <div class="mb-2">
                                        @if($attempt->overall_band)
                                            <span class="font-24 font-weight-bold text-primary">
                                                {{ $attempt->overall_band }}
                                            </span>
                                            <small class="text-gray">/ 9.0</small>
                                        @else
                                            <span class="badge badge-warning">Pending Grading</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('panel.ielts_tests.results', $attempt->id) }}" class="btn btn-sm btn-primary">
                                        View Results
                                    </a>
                                @elseif($attempt->status === 'in_progress')
                                    <a href="{{ route('panel.ielts_tests.take', $attempt->id) }}" class="btn btn-sm btn-success">
                                        Continue
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card sticky-top" style="top: 100px;">
                <div class="card-body text-center">
                    <h5 class="mb-3">Ready to Start?</h5>
                    
                    @if($canTake)
                        <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg btn-block mb-3">
                                <i class="fas fa-play mr-2"></i>
                                Start Test
                            </button>
                        </form>
                        
                        <p class="text-gray font-14">
                            @if($test->isMockTest())
                                You have {{ 3 - $attempts->count() }} attempt(s) remaining
                            @else
                                Unlimited attempts available
                            @endif
                        </p>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Maximum attempts reached
                        </div>
                    @endif

                    <hr>

                    <div class="text-left">
                        <h6 class="mb-3">Test Structure</h6>
                        @if($test->has_listening)
                        <div class="mb-2">
                            <i class="fas fa-headphones text-primary mr-2"></i>
                            <span>Listening: {{ $test->listening_duration }} min</span>
                        </div>
                        @endif
                        @if($test->has_reading)
                        <div class="mb-2">
                            <i class="fas fa-book-open text-success mr-2"></i>
                            <span>Reading: {{ $test->reading_duration }} min</span>
                        </div>
                        @endif
                        @if($test->has_writing)
                        <div class="mb-2">
                            <i class="fas fa-pencil-alt text-warning mr-2"></i>
                            <span>Writing: {{ $test->writing_duration }} min</span>
                        </div>
                        @endif
                        @if($test->has_speaking)
                        <div class="mb-2">
                            <i class="fas fa-microphone text-danger mr-2"></i>
                            <span>Speaking: {{ $test->speaking_duration }} min</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
