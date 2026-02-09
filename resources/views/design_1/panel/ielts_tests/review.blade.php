@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .review-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    /* Header Card */
    .review-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 24px;
    }
    .review-header h1 { 
        margin: 0 0 8px 0; 
        font-size: 24px; 
        font-weight: 600;
    }
    .review-header .subtitle {
        opacity: 0.9;
        font-size: 14px;
    }
    
    /* Stats Summary */
    .stats-summary {
        display: flex;
        gap: 16px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .stat-item {
        background: rgba(255,255,255,0.15);
        padding: 12px 20px;
        border-radius: 10px;
        text-align: center;
        min-width: 100px;
    }
    .stat-value {
        font-size: 24px;
        font-weight: bold;
    }
    .stat-label {
        font-size: 12px;
        opacity: 0.85;
        margin-top: 2px;
    }
    
    /* Action buttons */
    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-grade { 
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-grade-writing { background: #f59e0b; color: white; }
    .btn-grade-speaking { background: #ef4444; color: white; }
    .btn-back { background: rgba(255,255,255,0.2); color: white; }
    .btn-grade:hover { opacity: 0.9; }
    
    /* Section Cards */
    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .section-header {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }
    .section-skill {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
    }
    .skill-listening { background: #dbeafe; color: #1e40af; }
    .skill-reading { background: #d1fae5; color: #065f46; }
    .skill-writing { background: #fef3c7; color: #92400e; }
    .skill-speaking { background: #fee2e2; color: #991b1b; }
    
    .section-stats {
        font-size: 13px;
        color: #6b7280;
    }
    
    /* Question List */
    .questions-list {
        padding: 20px 24px;
    }
    .question-item {
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }
    .question-item:hover {
        border-color: #d1d5db;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .question-item:last-child { margin-bottom: 0; }
    
    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .question-number {
        font-weight: 600;
        color: #374151;
        font-size: 15px;
    }
    .question-text {
        color: #4b5563;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 14px;
    }
    
    /* Badge styles */
    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-correct { background: #d1fae5; color: #065f46; }
    .badge-incorrect { background: #fee2e2; color: #991b1b; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-no-answer { background: #f3f4f6; color: #6b7280; }
    
    /* Answer boxes */
    .answer-box {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .answer-student {
        background: #f9fafb;
        border-left: 3px solid #9ca3af;
    }
    .answer-student.correct {
        background: #ecfdf5;
        border-left-color: #10b981;
    }
    .answer-student.incorrect {
        background: #fef2f2;
        border-left-color: #ef4444;
    }
    .answer-correct-ref {
        background: #ecfdf5;
        border-left: 3px solid #10b981;
    }
    .answer-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 4px;
    }
    .answer-text {
        font-size: 14px;
        color: #1f2937;
    }
    .no-answer {
        color: #9ca3af;
        font-style: italic;
    }
    
    /* Explanation */
    .explanation-box {
        background: #eff6ff;
        border-left: 3px solid #3b82f6;
        padding: 12px 16px;
        border-radius: 8px;
        margin-top: 10px;
    }
    .explanation-box strong {
        color: #1e40af;
        font-size: 12px;
        text-transform: uppercase;
    }
    .explanation-box p {
        margin: 6px 0 0 0;
        font-size: 13px;
        color: #4b5563;
        line-height: 1.6;
    }
    
    /* Next Actions */
    .next-actions {
        background: white;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .next-actions h4 {
        margin: 0 0 16px 0;
        color: #1f2937;
    }
    .next-actions .actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .next-actions .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
    }
    
    /* Data Debug (for teachers) */
    .debug-panel {
        background: #1f2937;
        color: #e5e7eb;
        padding: 16px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 12px;
        margin-top: 24px;
        overflow-x: auto;
    }
    .debug-panel h5 {
        color: #fbbf24;
        margin: 0 0 12px 0;
        font-size: 14px;
    }
    .debug-row {
        display: flex;
        gap: 20px;
        margin-bottom: 6px;
    }
    .debug-label { color: #9ca3af; min-width: 140px; }
    .debug-value { color: #10b981; }
</style>
@endpush

@section('content')
@php
    // Calculate stats
    $totalQuestions = 0;
    $answeredQuestions = 0;
    $correctAnswers = 0;
    $incorrectAnswers = 0;
    $pendingGrading = 0;
    
    foreach($test->sections as $section) {
        foreach($section->questions as $question) {
            $totalQuestions++;
            $answer = $attempt->answers->where('question_id', $question->id)->first();
            
            if($answer && $answer->answer_text) {
                $answeredQuestions++;
                if($answer->grading_status === 'pending' || in_array($section->skill, ['writing', 'speaking'])) {
                    $pendingGrading++;
                } elseif($answer->is_correct) {
                    $correctAnswers++;
                } else {
                    $incorrectAnswers++;
                }
            }
        }
    }
    
    $isTeacher = auth()->user()->isTeacher() || auth()->user()->isAdmin() || auth()->user()->isOrganization();
    $isOwner = $attempt->user_id === auth()->id();
@endphp

<div class="review-container mt-20">
    {{-- Header --}}
    <div class="review-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
            <div>
                <h1>{{ trans('update.review_answers') }}</h1>
                <p class="subtitle">{{ $test->title }} • {{ trans('update.attempt_number', ['number' => $attempt->attempt_number]) }}</p>
                @if(!$isOwner && $isTeacher)
                    <p class="subtitle mt-1">
                        <strong>{{ trans('update.student') }}:</strong> {{ $attempt->user->full_name ?? trans('update.unknown') }}
                        ({{ $attempt->user->email ?? '' }})
                    </p>
                @endif
            </div>
            <div class="header-actions">
                @if($isTeacher)
                    @if($test->has_writing && !$attempt->writing_band)
                        <a href="{{ route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'writing']) }}" class="btn-grade btn-grade-writing">
                            {{ trans('update.grade_writing') }}
                        </a>
                    @endif
                    @if($test->has_speaking && !$attempt->speaking_band)
                        <a href="{{ route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'speaking']) }}" class="btn-grade btn-grade-speaking">
                            {{ trans('update.grade_speaking') }}
                        </a>
                    @endif
                @endif
                <a href="{{ route('panel.ielts_tests.results', $attempt->id) }}" class="btn-grade btn-back">
                    ← {{ trans('update.back_to_results') }}
                </a>
            </div>
        </div>
        
        <div class="stats-summary">
            <div class="stat-item">
                <div class="stat-value">{{ $totalQuestions }}</div>
                <div class="stat-label">{{ trans('update.total_questions') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $answeredQuestions }}</div>
                <div class="stat-label">{{ trans('update.answered_stat') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $correctAnswers }}</div>
                <div class="stat-label">✓ {{ trans('update.correct') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $incorrectAnswers }}</div>
                <div class="stat-label">✗ {{ trans('update.incorrect') }}</div>
            </div>
            @if($pendingGrading > 0)
                <div class="stat-item">
                    <div class="stat-value">{{ $pendingGrading }}</div>
                    <div class="stat-label">⏳ {{ trans('admin/main.pending') }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Sections --}}
    @foreach($test->sections as $section)
        @php
            $sectionAnswered = 0;
            $sectionCorrect = 0;
            foreach($section->questions as $q) {
                $ans = $attempt->answers->where('question_id', $q->id)->first();
                if($ans && $ans->answer_text) {
                    $sectionAnswered++;
                    if($ans->is_correct) $sectionCorrect++;
                }
            }
            $sectionTotal = $section->questions->count();
        @endphp
        
        <div class="section-card">
            <div class="section-header">
                <div>
                    <h3 class="section-title">{{ $section->title ?: trans('update.part') . ' ' . $loop->iteration }}</h3>
                    <span class="section-skill skill-{{ $section->skill }}">{{ mb_strtoupper(trans('update.' . $section->skill)) }}</span>
                </div>
                <div class="section-stats">
                    {{ $sectionAnswered }}/{{ $sectionTotal }} {{ trans('update.ielts_answered') }}
                    @if($section->skill === 'reading' || $section->skill === 'listening')
                        • {{ $sectionCorrect }} {{ trans('update.correct') }}
                    @endif
                </div>
            </div>
            
            <div class="questions-list">
                @forelse($section->questions as $question)
                    @php
                        $answer = $attempt->answers->where('question_id', $question->id)->first();
                        $hasAnswer = $answer && !empty($answer->answer_text);
                        $isCorrect = $answer && $answer->is_correct;
                        $isPending = in_array($section->skill, ['writing', 'speaking']) || ($answer && $answer->grading_status === 'pending');
                        
                        // Determine status
                        if(!$hasAnswer) {
                            $status = 'no-answer';
                            $statusLabel = trans('update.no_answer');
                            $badgeClass = 'badge-no-answer';
                        } elseif($isPending) {
                            $status = 'pending';
                            $statusLabel = trans('admin/main.pending');
                            $badgeClass = 'badge-pending';
                        } elseif($isCorrect) {
                            $status = 'correct';
                            $statusLabel = trans('update.correct');
                            $badgeClass = 'badge-correct';
                        } else {
                            $status = 'incorrect';
                            $statusLabel = trans('update.incorrect');
                            $badgeClass = 'badge-incorrect';
                        }
                    @endphp
                    
                    <div class="question-item">
                        <div class="question-header">
                            <span class="question-number">{{ trans('update.question') }} {{ $question->question_number }}</span>
                            <span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </div>
                        
                        @if($question->question_text)
                            <div class="question-text">
                                {!! nl2br(e($question->question_text)) !!}
                            </div>
                        @endif
                        
                        {{-- Multiple choice options --}}
                        @if(in_array($question->question_type, ['multiple_choice', 'mcq', 'single_choice']) && $question->answer_options)
                            @php
                                $options = is_string($question->answer_options) ? json_decode($question->answer_options, true) : $question->answer_options;
                            @endphp
                            @if(is_array($options))
                                <div class="mb-3">
                                    <div class="answer-label">{{ trans('update.options') }}</div>
                                    @foreach($options as $key => $option)
                                        @php
                                            $optionText = is_array($option) ? ($option['text'] ?? $option['label'] ?? '') : $option;
                                            $optionKey = is_array($option) ? ($option['key'] ?? chr(65 + $loop->index)) : chr(65 + $loop->index);
                                            $isSelected = $hasAnswer && (strtoupper(trim($answer->answer_text)) === strtoupper($optionKey) || $answer->answer_text === $optionText);
                                        @endphp
                                        <div style="padding: 6px 0; {{ $isSelected ? 'font-weight: 600; color: #1e40af;' : '' }}">
                                            {{ $optionKey }}. {{ $optionText }}
                                            @if($isSelected) <span style="color: #3b82f6;">({{ trans('update.selected') }})</span> @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                        
                        {{-- Student's Answer --}}
                        <div class="answer-box answer-student {{ $status }}">
                            <div class="answer-label">{{ $isOwner ? trans('update.your_answer') : trans('update.students_answer') }}</div>
                            @if($hasAnswer)
                                <div class="answer-text">{{ $answer->answer_text }}</div>
                            @else
                                <div class="answer-text no-answer">{{ trans('update.no_answer_provided') }}</div>
                            @endif
                        </div>
                        
                        {{-- Correct Answer (for auto-graded questions) --}}
                        @if($hasAnswer && !$isPending && $question->auto_gradable && $question->correct_answer && !$isCorrect)
                            <div class="answer-box answer-correct-ref">
                                <div class="answer-label">{{ trans('update.correct_answer') }}</div>
                                <div class="answer-text">{{ $question->correct_answer }}</div>
                            </div>
                        @endif
                        
                        {{-- Feedback from teacher --}}
                        @if($answer && $answer->feedback)
                            <div class="explanation-box" style="background: #fef3c7; border-left-color: #f59e0b;">
                                <strong style="color: #92400e;">{{ trans('update.teacher_feedback_box') }}</strong>
                                <p>{{ $answer->feedback }}</p>
                            </div>
                        @endif
                        
                        {{-- Explanation (for practice tests) --}}
                        @if($question->explanation && method_exists($test, 'isPracticeTest') && $test->isPracticeTest())
                            <div class="explanation-box">
                                <strong>{{ trans('update.explanation_label') }}</strong>
                                <p>{{ $question->explanation }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-gray py-4">
                        {{ trans('update.no_questions_in_section') }}
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach

    {{-- Next Actions --}}
    <div class="next-actions">
        <h4>{{ trans('update.whats_next') }}</h4>
        <div class="actions">
            <a href="{{ route('panel.ielts_tests.results', $attempt->id) }}" class="btn btn-primary">
                {{ trans('update.view_full_results') }}
            </a>
            <a href="{{ route('panel.ielts_tests.index') }}" class="btn btn-outline-primary">
                {{ trans('update.browse_more_tests') }}
            </a>
            @if($isTeacher)
                <a href="{{ route('panel.ielts_grading.index') }}" class="btn btn-outline-secondary">
                {{ trans('update.grading_dashboard') }}
                </a>
            @endif
        </div>
    </div>
    
    {{-- Debug Panel for Teachers --}}
    @if($isTeacher)
        <div class="debug-panel">
            <h5>🔧 Data Debug (Teacher View)</h5>
            <div class="debug-row">
                <span class="debug-label">Attempt ID:</span>
                <span class="debug-value">{{ $attempt->id }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Student ID:</span>
                <span class="debug-value">{{ $attempt->user_id }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Test ID:</span>
                <span class="debug-value">{{ $test->id }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Status:</span>
                <span class="debug-value">{{ $attempt->status }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Started At:</span>
                <span class="debug-value">{{ $attempt->started_at ? date('Y-m-d H:i:s', $attempt->started_at) : 'N/A' }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Completed At:</span>
                <span class="debug-value">{{ $attempt->completed_at ? date('Y-m-d H:i:s', $attempt->completed_at) : 'N/A' }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Total Answers in DB:</span>
                <span class="debug-value">{{ $attempt->answers->count() }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Listening Score:</span>
                <span class="debug-value">{{ $attempt->listening_score ?? 'null' }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Reading Score:</span>
                <span class="debug-value">{{ $attempt->reading_score ?? 'null' }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Writing Band:</span>
                <span class="debug-value">{{ $attempt->writing_band ?? 'null' }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Speaking Band:</span>
                <span class="debug-value">{{ $attempt->speaking_band ?? 'null' }}</span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Overall Band:</span>
                <span class="debug-value">{{ $attempt->overall_band ?? 'null' }}</span>
            </div>
        </div>
    @endif
</div>
@endsection
