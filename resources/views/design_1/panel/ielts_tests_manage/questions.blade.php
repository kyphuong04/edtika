@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
.question-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    margin-bottom: 16px;
}

.question-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.10);
    border-color: #3b82f6;
}

.question-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid #f3f4f6;
}

.question-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    font-weight: 700;
    font-size: 16px;
    margin-right: 15px;
    flex-shrink: 0;
}

.question-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.question-body {
    padding: 20px;
}

.question-text {
    font-size: 15px;
    color: #1f2937;
    line-height: 1.6;
    margin-bottom: 15px;
    font-weight: 500;
}

.question-instruction {
    background: #f9fafb;
    border-left: 3px solid #3b82f6;
    padding: 12px 15px;
    margin-bottom: 15px;
    border-radius: 6px;
    font-size: 13px;
    color: #6b7280;
}

.options-list {
    list-style: none;
    padding: 0;
    margin: 15px 0 0 0;
}

.options-list li {
    padding: 10px 15px;
    margin-bottom: 8px;
    background: #f9fafb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.options-list li:hover {
    background: #f3f4f6;
}

.options-list li.correct-answer {
    background: #d1fae5;
    border-left: 4px solid #10b981;
    font-weight: 600;
    color: #065f46;
}

.correct-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #d1fae5;
    color: #065f46;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    margin-top: 12px;
}

.question-actions {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.type-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
}

.type-badge.multiple-choice { background: #dbeafe; color: #1e40af; }
.type-badge.fill-blank { background: #d1fae5; color: #065f46; }
.type-badge.essay { background: #fed7aa; color: #92400e; }
.type-badge.true-false { background: #e9d5ff; color: #6b21a8; }
.type-badge.matching { background: #fce7f3; color: #9f1239; }

.points-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    background: #f3f4f6;
    color: #374151;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.auto-grade-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 10px;
    background: #d1fae5;
    color: #065f46;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.manual-grade-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 10px;
    background: #fed7aa;
    color: #92400e;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
}

.section-info {
    display: flex;
    gap: 20px;
    margin-top: 12px;
    flex-wrap: wrap;
}

.section-info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255,255,255,0.9);
    font-size: 14px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    color: #d1d5db;
    margin-bottom: 20px;
}

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
</style>
@endpush

@section('content')
<section class="mt-30">
    {{-- Section Header --}}
    <div class="section-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="font-24 font-weight-bold mb-2">Manage Questions</h1>
                <p class="mb-0" style="opacity: 0.9;">{{ $section->title }}</p>
                <div class="section-info">
                    <div class="section-info-item">
                        <i class="fas fa-list-ol"></i>
                        <span>Questions {{ $section->question_start }} - {{ $section->question_end }}</span>
                    </div>
                    <div class="section-info-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ $section->duration_minutes }} minutes</span>
                    </div>
                    <div class="section-info-item">
                        <i class="fas fa-tasks"></i>
                        <span>{{ $section->questions->count() }} questions created</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Bar --}}
    <div class="action-bar">
        <a href="{{ route('panel.my_ielts_tests.sections', $test->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to Sections
        </a>
        <a href="{{ route('panel.my_ielts_tests.questions.create', [$test->id, $section->id]) }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Add Question
        </a>
    </div>

    {{-- Questions List --}}
    @if($section->questions->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-clipboard-question"></i>
            </div>
            <h3 class="font-20 font-weight-bold text-gray-800 mb-2">No Questions Yet</h3>
            <p class="text-gray-600 mb-4">Start adding questions to build your test section</p>
            <a href="{{ route('panel.my_ielts_tests.questions.create', [$test->id, $section->id]) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus mr-2"></i>Create First Question
            </a>
        </div>
    @else
        <div class="questions-container">
            @foreach($section->questions->sortBy('question_number') as $question)
            <div class="question-card">
                {{-- Question Header --}}
                <div class="question-header">
                    <div class="d-flex align-items-start flex-fill">
                        <div class="question-number">
                            {{ $question->question_number }}
                        </div>
                        <div class="flex-fill">
                            <div class="question-meta mb-2">
                                <span class="type-badge {{ str_replace('_', '-', $question->question_type) }}">
                                    {{ ucwords(str_replace('_', ' ', $question->question_type)) }}
                                </span>
                                
                                @if($question->auto_gradable)
                                    <span class="auto-grade-badge">
                                        <i class="fas fa-robot"></i>
                                        Auto-Grade
                                    </span>
                                @else
                                    <span class="manual-grade-badge">
                                        <i class="fas fa-user"></i>
                                        Manual
                                    </span>
                                @endif
                                
                                <span class="points-badge">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ $question->points }} {{ $question->points == 1 ? 'pt' : 'pts' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-actions">
                        <a href="{{ route('panel.my_ielts_tests.questions.edit', [$test->id, $section->id, $question->id]) }}" 
                           class="btn btn-warning btn-icon"
                           title="Edit Question">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('panel.my_ielts_tests.questions.delete', [$test->id, $section->id, $question->id]) }}" 
                           class="btn btn-danger btn-icon"
                           onclick="return confirm('Delete this question? This action cannot be undone.')"
                           title="Delete Question">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>

                {{-- Question Body --}}
                <div class="question-body">
                    {{-- Instruction --}}
                    @if($question->instruction)
                        <div class="question-instruction">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Instructions:</strong> {{ $question->instruction }}
                        </div>
                    @endif

                    {{-- Question Text --}}
                    <div class="question-text">
                        {!! nl2br(e($question->question_text)) !!}
                    </div>

                    {{-- Options for Multiple Choice --}}
                    @if($question->question_type === 'multiple_choice' && $question->answer_options)
                        <div class="mt-3">
                            <strong class="d-block mb-2 text-gray-700" style="font-size: 13px;">
                                <i class="fas fa-list-ul mr-1"></i>Answer Options:
                            </strong>
                            <ul class="options-list">
                                @php
                                    $options = json_decode($question->answer_options, true) ?? [];
                                    $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
                                @endphp
                                @foreach($options as $index => $option)
                                    <li class="{{ $option == $question->correct_answer ? 'correct-answer' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>
                                                <strong>{{ $letters[$index] ?? ($index + 1) }}.</strong> {{ $option }}
                                            </span>
                                            @if($option == $question->correct_answer)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>Correct Answer
                                                </span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif($question->auto_gradable && $question->correct_answer)
                        {{-- Correct Answer for Auto-gradable Questions --}}
                        <div class="correct-indicator">
                            <i class="fas fa-check-circle"></i>
                            <span>Correct Answer: <strong>{{ $question->correct_answer }}</strong></span>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
