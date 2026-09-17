@extends('design_1.panel.layouts.panel')

@section('content')
<section class="mt-30">
    <h1 class="section-title mb-20">Chấm Writing — {{ $attempt->test->title }}</h1>
    <p class="text-muted mb-20">Học viên: <strong>{{ $attempt->user->full_name ?? $attempt->user->name ?? '' }}</strong></p>

    @foreach($tasks as $idx => $task)
    <div class="card mb-20 p-20" style="border-radius:12px;border:1px solid #e5e7eb;">
        <h5 class="mb-10">Task {{ $idx + 1 }}</h5>
        <div class="mb-10" style="font-size:13px;color:#666;">{!! $task['question']->question_text ?? '' !!}</div>
        <div style="white-space:pre-wrap;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:14px;">
            {{ $task['essay'] ?: '(Học viên chưa nộp bài)' }}
        </div>
    </div>
    @endforeach

    <form action="{{ route('panel.ielts_grading.writing.save', $attempt->id) }}" method="POST">
        @csrf
        <div class="card p-20 mb-20" style="border-radius:12px;border:1px solid #e5e7eb;">
            <h5 class="mb-16">Tiêu chí chấm điểm</h5>
            <div class="row">
                @php
                    $labels = [
                        'task_achievement' => 'Task Achievement / Response',
                        'coherence' => 'Coherence & Cohesion',
                        'lexical' => 'Lexical Resource',
                        'grammar' => 'Grammatical Range & Accuracy',
                    ];
                @endphp
                @foreach($criteriaKeys as $key)
                <div class="col-md-6 mb-16">
                    <label class="input-label">{{ $labels[$key] }}</label>
                    <input type="number" name="criteria[{{ $key }}]" class="form-control" min="0" max="9" step="0.5"
                           value="{{ $existingCriteria[$key] ?? '' }}" required>
                </div>
                @endforeach
            </div>
            <div class="mb-0">
                <label class="input-label">Nhận xét chung</label>
                <textarea name="feedback" class="form-control" rows="5">{{ $existingFeedback }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn-1 btn-lg rounded-12">Lưu điểm Writing</button>
    </form>
</section>
@endsection