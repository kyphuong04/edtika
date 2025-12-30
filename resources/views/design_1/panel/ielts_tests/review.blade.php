@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .review-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .question-review {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .answer-correct {
        background: #d1fae5;
        border-left: 4px solid #10b981;
        padding: 15px;
        border-radius: 8px;
    }
    .answer-incorrect {
        background: #fee2e2;
        border-left: 4px solid #ef4444;
        padding: 15px;
        border-radius: 8px;
    }
    .answer-pending {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 15px;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="review-container mt-30">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Review Answers</h1>
            <p class="text-gray mt-2">{{ $test->title }} - Attempt #{{ $attempt->attempt_number }}</p>
        </div>
        <a href="{{ route('panel.ielts_tests.results', $attempt->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Results
        </a>
    </div>

    @foreach($test->sections as $section)
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h4 class="mb-0">{{ $section->title }}</h4>
                <p class="mb-0 mt-1 opacity-90">{{ ucfirst($section->skill) }}</p>
            </div>
            <div class="card-body">
                @foreach($section->questions as $index => $question)
                    @php
                        $answer = $attempt->answers->where('question_id', $question->id)->first();
                        $isCorrect = $answer && $answer->is_correct;
                        $isPending = $answer && $answer->grading_status === 'pending';
                    @endphp

                    <div class="question-review">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="mb-0">Question {{ $question->question_number }}</h5>
                            @if($isPending)
                                <span class="badge badge-warning">Pending Grading</span>
                            @elseif($isCorrect)
                                <span class="badge badge-success">
                                    <i class="fas fa-check mr-1"></i>
                                    Correct
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times mr-1"></i>
                                    Incorrect
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <p class="font-16">{!! nl2br(e($question->question_text)) !!}</p>
                        </div>

                        @if($question->question_type === 'multiple_choice')
                            <div class="mb-3">
                                <strong>Options:</strong>
                                <ul class="mt-2">
                                    @foreach(json_decode($question->answer_options) as $option)
                                        <li class="{{ $option == $answer->answer_text ? 'font-weight-bold' : '' }}">
                                            {{ $option }}
                                            @if($option == $answer->answer_text)
                                                <span class="text-primary">(Your answer)</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($answer)
                            <div class="mb-3 {{ $isPending ? 'answer-pending' : ($isCorrect ? 'answer-correct' : 'answer-incorrect') }}">
                                <strong>Your Answer:</strong>
                                <p class="mb-0 mt-1">{{ $answer->answer_text }}</p>
                            </div>

                            @if(!$isPending && $question->auto_gradable)
                                <div class="answer-correct">
                                    <strong>Correct Answer:</strong>
                                    <p class="mb-0 mt-1">{{ $question->correct_answer }}</p>
                                </div>
                            @endif

                            @if($answer->feedback)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Instructor Feedback:</strong>
                                    <p class="mb-0 mt-2">{{ $answer->feedback }}</p>
                                    @if($answer->graded_by)
                                        <small class="text-gray">
                                            Graded by: {{ $answer->grader->full_name ?? 'System' }}
                                        </small>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="alert alert-secondary">
                                <i class="fas fa-info-circle mr-2"></i>
                                No answer provided
                            </div>
                        @endif

                        @if($question->explanation && $test->isPracticeTest())
                            <div class="mt-3 p-3 border-left border-primary" style="background: #f0f9ff;">
                                <strong class="text-primary">
                                    <i class="fas fa-lightbulb mr-2"></i>
                                    Explanation:
                                </strong>
                                <p class="mb-0 mt-2">{{ $question->explanation }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="card">
        <div class="card-body text-center">
            <h5 class="mb-3">What's Next?</h5>
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('panel.ielts_tests.results', $attempt->id) }}" class="btn btn-primary">
                    <i class="fas fa-chart-bar mr-2"></i>
                    View Full Results
                </a>
                <a href="{{ route('panel.ielts_tests.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-th-list mr-2"></i>
                    Browse More Tests
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
