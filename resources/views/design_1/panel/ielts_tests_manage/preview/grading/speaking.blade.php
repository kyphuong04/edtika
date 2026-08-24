@extends('design_1.panel.layouts.panel')

@section('content')
<section class="mt-30">
    <h1 class="section-title mb-20">Chấm Speaking — {{ $attempt->test->title }}</h1>
    <p class="text-muted mb-20">Học viên: <strong>{{ $attempt->user->full_name ?? $attempt->user->name ?? '' }}</strong></p>

    <form action="{{ route('panel.ielts_grading.speaking.save', $attempt->id) }}" method="POST">
        @csrf

        @foreach($parts as $partIdx => $part)
        @php $section = $part['section']; @endphp
        <div class="card mb-20 p-20" style="border-radius:12px;border:1px solid #e5e7eb;">
            <h5 class="mb-16">Part {{ $partIdx + 1 }}</h5>

            @foreach($part['questions'] as $q)
            <div class="mb-16" style="padding-bottom:12px;border-bottom:1px solid #f3f4f6;">
                <div style="font-size:13px;color:#666;margin-bottom:6px;">{{ $q['question']->question_text }}</div>
                @if($q['audioUrl'])
                    <audio controls src="{{ $q['audioUrl'] }}" style="width:100%;"></audio>
                @else
                    <span class="text-muted font-12">(Học viên chưa ghi âm)</span>
                @endif
            </div>
            @endforeach

            @php
                $labels = [
                    'fluency' => 'Fluency and Coherence',
                    'lexical' => 'Vocabulary',
                    'grammar' => 'Grammatical Range & Accuracy',
                    'pronunciation' => 'Pronunciation',
                ];
                $existing = $part['existing'];
            @endphp
            <div class="row">
                @foreach($criteriaKeys as $key)
                <div class="col-md-3 mb-16">
                    <label class="input-label">{{ $labels[$key] }}</label>
                    <input type="number" name="parts[{{ $section->id }}][{{ $key }}]" class="form-control"
                           min="0" max="9" step="0.5" value="{{ $existing[$key] ?? '' }}" required>
                </div>
                @endforeach
            </div>
            <div>
                <label class="input-label">Nhận xét cho Part này</label>
                <textarea name="parts[{{ $section->id }}][feedback]" class="form-control" rows="3">{{ $existing['feedback'] ?? '' }}</textarea>
            </div>
        </div>
        @endforeach

        <button type="submit" class="btn-1 btn-lg rounded-12">Lưu điểm Speaking</button>
    </form>
</section>
@endsection