@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .test-details-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Test Header */
    .test-header {
        background: #f8fafc;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid #e5e7eb;
    }
    .test-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }
    .test-header p {
        color: #6b7280;
        margin-bottom: 0;
    }
    .test-meta {
        display: flex;
        gap: 24px;
        margin-top: 16px;
        flex-wrap: wrap;
    }
    .test-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #6b7280;
    }
    .test-meta-item i {
        color: #9ca3af;
    }

    /* Skill Cards Grid */
    .skills-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    @media (max-width: 992px) {
        .skills-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 576px) {
        .skills-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Skill Card */
    .skill-card {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 30px 20px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
    }
    .skill-card:hover {
        border-color: transparent;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        transform: translateY(-4px);
    }
    .skill-card.listening { border-top: 4px solid #3b82f6; }
    .skill-card.reading { border-top: 4px solid #10b981; }
    .skill-card.writing { border-top: 4px solid #f59e0b; }
    .skill-card.speaking { border-top: 4px solid #ef4444; }

    .skill-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 28px;
    }
    .skill-card.listening .skill-icon { background: #dbeafe; color: #3b82f6; }
    .skill-card.reading .skill-icon { background: #d1fae5; color: #10b981; }
    .skill-card.writing .skill-icon { background: #fef3c7; color: #f59e0b; }
    .skill-card.speaking .skill-icon { background: #fee2e2; color: #ef4444; }

    .skill-name {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
    }
    .skill-duration {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 20px;
    }

    /* Start Button */
    .btn-skill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 28px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-skill:hover {
        transform: scale(1.05);
        text-decoration: none;
    }
    .btn-skill.listening { background: #3b82f6; color: white; }
    .btn-skill.reading { background: #10b981; color: white; }
    .btn-skill.writing { background: #f59e0b; color: white; }
    .btn-skill.speaking { background: #ef4444; color: white; }
    .btn-skill.listening:hover { background: #2563eb; color: white; }
    .btn-skill.reading:hover { background: #059669; color: white; }
    .btn-skill.writing:hover { background: #d97706; color: white; }
    .btn-skill.speaking:hover { background: #dc2626; color: white; }

    .btn-skill.disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }
    .btn-skill.disabled:hover {
        transform: none;
    }

    /* Key/Unlock Icon */
    .skill-status {
        margin-top: 16px;
        font-size: 20px;
        color: #d1d5db;
    }
    .skill-status.unlocked { color: #10b981; }
    .skill-status.completed { color: #3b82f6; }

    /* Attempts Section */
    .attempts-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #e5e7eb;
    }
    .attempts-section h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1f2937;
    }
    .attempt-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
        margin-bottom: 12px;
    }
    .attempt-item:last-child {
        margin-bottom: 0;
    }
    .attempt-info h4 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 4px;
        color: #1f2937;
    }
    .attempt-info p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .attempt-score {
        font-size: 24px;
        font-weight: 700;
        color: #3b82f6;
    }
    .attempt-score small {
        font-size: 14px;
        font-weight: 400;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')
<div class="test-details-container mt-30">
    <!-- Test Header -->
    <div class="test-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div>
                <h1>{{ $test->title }}</h1>
                <p>{{ $test->description ?? trans('update.ielts_practice_all_skills_hint') }}</p>
            </div>
            <span class="badge badge-{{ $test->type === 'mock' ? 'primary' : 'info' }} badge-lg">
                {{ trans('update.' . $test->type) }} {{ trans('update.test') }}
            </span>
        </div>
        <div class="test-meta">
            <div class="test-meta-item">
                <i class="far fa-clock"></i>
                <span>{{ $test->total_duration }} {{ trans('update.minutes') }}</span>
            </div>
            <div class="test-meta-item">
                <i class="fas fa-signal"></i>
                <span>{{ trans('update.' . ($test->difficulty_level ?? 'intermediate')) }}</span>
            </div>
            @if($test->target_band_min && $test->target_band_max)
            <div class="test-meta-item">
                <i class="fas fa-bullseye"></i>
                <span>{{ trans('update.band') }} {{ $test->target_band_min }} - {{ $test->target_band_max }}</span>
            </div>
            @endif
            <div class="test-meta-item">
                <i class="fas fa-redo"></i>
                <span>{{ $attempts->count() }} {{ trans('update.attempts') }}</span>
            </div>
        </div>
    </div>

    <!-- Skills Grid -->
    <div class="skills-grid">
        @if($test->has_listening)
        <div class="skill-card listening">
            <div class="skill-icon">
                <i class="fas fa-headphones"></i>
            </div>
            <div class="skill-name">{{ trans('update.listening') }}</div>
            <div class="skill-duration">{{ $test->listening_duration ?? 30 }} {{ trans('update.minutes') }}</div>
            @if($canTake)
            <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="skill" value="listening">
                <button type="submit" class="btn-skill listening">
                    <i class="fas fa-bolt"></i> {{ trans('update.take_test') }}
                </button>
            </form>
            @else
            <span class="btn-skill disabled">
                <i class="fas fa-lock"></i> {{ trans('update.locked') }}
            </span>
            @endif
            <div class="skill-status unlocked">
                <i class="fas fa-key"></i>
            </div>
        </div>
        @endif

        @if($test->has_reading)
        <div class="skill-card reading">
            <div class="skill-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="skill-name">{{ trans('update.reading') }}</div>
            <div class="skill-duration">{{ $test->reading_duration ?? 60 }} {{ trans('update.minutes') }}</div>
            @if($canTake)
            <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="skill" value="reading">
                <button type="submit" class="btn-skill reading">
                    <i class="fas fa-bolt"></i> {{ trans('update.take_test') }}
                </button>
            </form>
            @else
            <span class="btn-skill disabled">
                <i class="fas fa-lock"></i> {{ trans('update.locked') }}
            </span>
            @endif
            <div class="skill-status unlocked">
                <i class="fas fa-key"></i>
            </div>
        </div>
        @endif

        @if($test->has_writing)
        <div class="skill-card writing">
            <div class="skill-icon">
                <i class="fas fa-pen-fancy"></i>
            </div>
            <div class="skill-name">{{ trans('update.writing') }}</div>
            <div class="skill-duration">{{ $test->writing_duration ?? 60 }} {{ trans('update.minutes') }}</div>
            @if($canTake)
            <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="skill" value="writing">
                <button type="submit" class="btn-skill writing">
                    <i class="fas fa-bolt"></i> {{ trans('update.take_test') }}
                </button>
            </form>
            @else
            <span class="btn-skill disabled">
                <i class="fas fa-lock"></i> {{ trans('update.locked') }}
            </span>
            @endif
            <div class="skill-status unlocked">
                <i class="fas fa-key"></i>
            </div>
        </div>
        @endif

        @if($test->has_speaking)
        <div class="skill-card speaking">
            <div class="skill-icon">
                <i class="fas fa-microphone"></i>
            </div>
            <div class="skill-name">{{ trans('update.speaking') }}</div>
            <div class="skill-duration">{{ $test->speaking_duration ?? 15 }} {{ trans('update.minutes') }}</div>
            @if($canTake)
            <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="skill" value="speaking">
                <button type="submit" class="btn-skill speaking">
                    <i class="fas fa-bolt"></i> {{ trans('update.take_test') }}
                </button>
            </form>
            @else
            <span class="btn-skill disabled">
                <i class="fas fa-lock"></i> {{ trans('update.locked') }}
            </span>
            @endif
            <div class="skill-status unlocked">
                <i class="fas fa-key"></i>
            </div>
        </div>
        @endif
    </div>

    <!-- Previous Attempts -->
    @if($attempts->isNotEmpty())
    <div class="attempts-section">
        <h3><i class="fas fa-history mr-2"></i>{{ trans('update.previous_attempts') }}</h3>
        @foreach($attempts as $attempt)
        <div class="attempt-item">
            <div class="attempt-info">
                <h4>{{ trans('update.attempt_number', ['number' => $attempt->attempt_number]) }}</h4>
                <p>{{ dateTimeFormat($attempt->created_at, 'j M Y, H:i') }} • {{ trans('admin/main.' . $attempt->status) }}</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                @if($attempt->status === 'completed')
                    @if($attempt->overall_band)
                    <div class="attempt-score">
                        {{ $attempt->overall_band }}<small>/9.0</small>
                    </div>
                    @endif
                    <a href="{{ route('panel.ielts_tests.results', $attempt->id) }}" class="btn btn-sm btn-primary">
                        {{ trans('update.view_results') }}
                    </a>
                @elseif($attempt->status === 'in_progress')
                    <a href="{{ route('panel.ielts_tests.take', $attempt->id) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-play mr-1"></i> {{ trans('admin/main.continue') }}
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
