@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .grading-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .grading-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 24px;
    }
    
    .grading-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }
    
    .stat-card {
        background: rgba(255,255,255,0.15);
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 13px;
        opacity: 0.9;
        margin-top: 6px;
    }
    
    .filter-bar {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }
    
    .filter-group select {
        padding: 8px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
        cursor: pointer;
    }
    
    .attempt-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.2s;
        border-left: 4px solid transparent;
    }
    
    .attempt-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .attempt-card.pending-writing {
        border-left-color: #f59e0b;
    }
    
    .attempt-card.pending-speaking {
        border-left-color: #ef4444;
    }
    
    .attempt-card.graded {
        border-left-color: #10b981;
    }
    
    .attempt-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    
    .student-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .student-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 16px;
    }
    
    .student-name {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }
    
    .test-name {
        font-size: 13px;
        color: #6b7280;
    }
    
    .attempt-badges {
        display: flex;
        gap: 8px;
    }
    
    .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-mock {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .badge-practice {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .badge-graded {
        background: #d1fae5;
        color: #065f46;
    }
    
    .attempt-skills {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
    }
    
    .skill-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        background: #f9fafb;
        border-radius: 8px;
        font-size: 13px;
    }
    
    .skill-item.needs-grading {
        background: #fef3c7;
        color: #92400e;
    }
    
    .skill-item.graded {
        background: #d1fae5;
        color: #065f46;
    }
    
    .skill-icon {
        width: 20px;
        height: 20px;
    }
    
    .attempt-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-grade {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    
    .btn-grade-writing {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #fff;
    }
    
    .btn-grade-writing:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    
    .btn-grade-speaking {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
    }
    
    .btn-grade-speaking:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
    
    .btn-view {
        background: #f3f4f6;
        color: #374151;
    }
    
    .btn-view:hover {
        background: #e5e7eb;
    }
    
    .completed-date {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 12px;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 12px;
    }
    
    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }
    
    .empty-state h3 {
        font-size: 18px;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 14px;
        color: #6b7280;
    }
</style>
@endpush

@section('content')
<div class="grading-container mt-20">
    {{-- Header --}}
    <div class="grading-header">
        <h1 class="mb-2">{{ trans('update.ielts_grading_dashboard') }}</h1>
        <p class="mb-0 opacity-85">{{ trans('update.ielts_grading_dashboard_hint') }}</p>
        
        <div class="grading-stats">
            <div class="stat-card">
                <div class="stat-number">{{ $pendingWriting }}</div>
                <div class="stat-label">✍️ {{ trans('update.writing_pending') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $pendingSpeaking }}</div>
                <div class="stat-label">🎤 {{ trans('update.speaking_pending') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $pendingWriting + $pendingSpeaking }}</div>
                <div class="stat-label">📋 {{ trans('update.total_pending') }}</div>
            </div>
        </div>
    </div>
    
    {{-- Filters --}}
    <div class="filter-bar">
        <div class="filter-group">
            <label>{{ trans('admin/main.status') }}:</label>
            <select onchange="applyFilter('status', this.value)">
                <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>{{ trans('admin/main.pending') }}</option>
                <option value="graded" {{ $currentStatus === 'graded' ? 'selected' : '' }}>{{ trans('update.graded') }}</option>
                <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>{{ trans('update.ielts_all') }}</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label>{{ trans('update.test_type') }}:</label>
            <select onchange="applyFilter('type', this.value)">
                <option value="all" {{ $currentType === 'all' ? 'selected' : '' }}>{{ trans('update.all_types') }}</option>
                <option value="mock" {{ $currentType === 'mock' ? 'selected' : '' }}>{{ trans('update.mock_tests') }}</option>
                <option value="practice" {{ $currentType === 'practice' ? 'selected' : '' }}>{{ trans('update.practice_tests') }}</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label>{{ trans('update.section_skill') }}:</label>
            <select onchange="applyFilter('skill', this.value)">
                <option value="all" {{ $currentSkill === 'all' ? 'selected' : '' }}>{{ trans('update.ielts_all_skills') }}</option>
                <option value="writing" {{ $currentSkill === 'writing' ? 'selected' : '' }}>{{ trans('update.writing_only') }}</option>
                <option value="speaking" {{ $currentSkill === 'speaking' ? 'selected' : '' }}>{{ trans('update.speaking_only') }}</option>
            </select>
        </div>
    </div>
    
    {{-- Attempts List --}}
    @if($attempts->count() > 0)
        @foreach($attempts as $attempt)
            @php
                $needsWriting = $attempt->test->has_writing && !$attempt->writing_band;
                $needsSpeaking = $attempt->test->has_speaking && !$attempt->speaking_band;
                $cardClass = $needsWriting ? 'pending-writing' : ($needsSpeaking ? 'pending-speaking' : 'graded');
            @endphp
            
            <div class="attempt-card {{ $cardClass }}">
                <div class="attempt-header">
                    <div class="student-info">
                        <div class="student-avatar">
                            {{ strtoupper(substr($attempt->user->full_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="student-name">{{ $attempt->user->full_name ?? trans('update.unknown_user') }}</div>
                            <div class="test-name">{{ $attempt->test->title ?? trans('update.unknown_test') }}</div>
                        </div>
                    </div>
                    
                    <div class="attempt-badges">
                        <span class="badge {{ $attempt->test->test_type === 'mock' ? 'badge-mock' : 'badge-practice' }}">
                            {{ trans('update.' . ($attempt->test->test_type ?? 'test')) }}
                        </span>
                        @if($needsWriting || $needsSpeaking)
                            <span class="badge badge-pending">{{ trans('update.pending_grading') }}</span>
                        @else
                            <span class="badge badge-graded">{{ trans('update.graded') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="attempt-skills">
                    @php
                        // Count actual answers for each skill
                        $listeningAnswers = $attempt->answers->filter(fn($a) => $a->question && $a->question->section && $a->question->section->skill === 'listening')->count();
                        $readingAnswers = $attempt->answers->filter(fn($a) => $a->question && $a->question->section && $a->question->section->skill === 'reading')->count();
                    @endphp
                    
                    @if($attempt->test->has_listening)
                        <div class="skill-item graded">
                            <span>🎧</span>
                            {{ trans('update.listening') }}: <strong>{{ $attempt->listening_score ?? 0 }}/40</strong>
                            <small class="text-muted">({{ $listeningAnswers }} {{ trans('update.ielts_answered') }})</small>
                        </div>
                    @endif
                    
                    @if($attempt->test->has_reading)
                        <div class="skill-item graded">
                            <span>📖</span>
                            {{ trans('update.reading') }}: <strong>{{ $attempt->reading_score ?? 0 }}/40</strong>
                            <small class="text-muted">({{ $readingAnswers }} {{ trans('update.ielts_answered') }})</small>
                        </div>
                    @endif
                    
                    @if($attempt->test->has_writing)
                        <div class="skill-item {{ $needsWriting ? 'needs-grading' : 'graded' }}">
                            <span>✍️</span>
                            {{ trans('update.writing') }}: <strong>{{ $attempt->writing_band ? trans('update.band_score', ['score' => $attempt->writing_band]) : trans('update.needs_grading') }}</strong>
                        </div>
                    @endif
                    
                    @if($attempt->test->has_speaking)
                        <div class="skill-item {{ $needsSpeaking ? 'needs-grading' : 'graded' }}">
                            <span>🎤</span>
                            {{ trans('update.speaking') }}: <strong>{{ $attempt->speaking_band ? trans('update.band_score', ['score' => $attempt->speaking_band]) : trans('update.needs_grading') }}</strong>
                        </div>
                    @endif
                </div>
                
                <div class="attempt-actions">
                    @if($needsWriting)
                        <a href="{{ route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'writing']) }}" class="btn-grade btn-grade-writing">
                            <span>✍️</span> {{ trans('update.grade_writing') }}
                        </a>
                    @endif
                    
                    @if($needsSpeaking)
                        <a href="{{ route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'speaking']) }}" class="btn-grade btn-grade-speaking">
                            <span>🎤</span> {{ trans('update.grade_speaking') }}
                        </a>
                    @endif
                    
                    <a href="{{ route('panel.ielts_tests.review', $attempt->id) }}" class="btn-grade btn-view" target="_blank">
                        <span>👁️</span> {{ trans('update.view_full_attempt') }}
                    </a>
                </div>
                
                <div class="completed-date">
                    {{ trans('admin/main.completed') }}: {{ $attempt->completed_at ? date('M j, Y \a\t g:i A', $attempt->completed_at) : 'N/A' }}
                </div>
            </div>
        @endforeach
        
        {{-- Pagination --}}
        <div class="mt-4">
            {{ $attempts->appends(request()->query())->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <h3>{{ trans('update.no_attempts_found') }}</h3>
            <p>{{ trans('update.no_attempts_matching_filters') }}</p>
        </div>
    @endif
</div>

<script>
function applyFilter(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.location.href = url.toString();
}
</script>
@endsection
