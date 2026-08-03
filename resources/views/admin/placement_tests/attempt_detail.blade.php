@extends('admin.layouts.app')

@push('styles_top')
<style>
.pt-q-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:20px; margin-bottom:16px; }
.pt-q-num { display:inline-block; background:#f3e8ff; color:#511D99; font-weight:700; font-size:13px; padding:3px 10px; border-radius:8px; }
.pt-q-text { font-size:15px; font-weight:600; color:#111827; margin:10px 0; line-height:1.5; }
.pt-option { padding:8px 12px; border:2px solid #e5e7eb; border-radius:8px; margin-bottom:6px; }
.pt-img-options { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:8px; }
.pt-img-option { border:2px solid #e5e7eb; border-radius:10px; padding:8px; text-align:center; }
.pt-img-option img { width:100%; height:100px; object-fit:cover; border-radius:6px; margin-bottom:6px; }
.pt-correct-line { margin-top:10px; font-size:13px; font-weight:700; color:#16a34a; }
.pt-test-header { background:#511D99; color:#fff; border-radius:12px; padding:14px 20px; margin:26px 0 14px; }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Chi tiết bài làm — {{ $user->name }}</h1>
    </div>

    <div class="section-body">
        <div class="mb-20" style="background:#f5f3ff;border-radius:12px;padding:16px;">
            <strong>Level cuối cùng: {{ $attempt->final_level }}</strong>
            &middot; Hoàn thành lúc {{ optional($attempt->completed_at)->format('d/m/Y H:i') }}
        </div>

        @foreach($testBlocks as $block)
            <div class="pt-test-header d-flex justify-content-between align-items-center">
                <span>Đề {{ $block['index'] + 1 }} &middot; Level {{ $block['test']->level }} &middot; {{ $block['test']->title }}</span>
                @if(!$block['is_scored'])
                    <span class="badge badge-warning">Tham khảo — không tính điểm</span>
                @endif
            </div>

            @if($block['test']->reading_passage)
                <div class="pt-q-card" style="background:#faf5ff;">
                    <strong>Đoạn văn đọc:</strong>
                    <div class="mt-2">{!! nl2br(e($block['test']->reading_passage)) !!}</div>
                </div>
            @endif

            @foreach($block['questions'] as $idx => $item)
                @php $q = $item['question']; $isCorrect = $item['is_correct']; @endphp
                <div class="pt-q-card" style="border-left:5px solid {{ $isCorrect ? '#22c55e' : '#ef4444' }};">
                    <span class="pt-q-num">Câu {{ $idx + 1 }}</span>
                    <span class="badge {{ $isCorrect ? 'badge-success' : 'badge-danger' }} float-right">
                        {{ $isCorrect ? 'Đúng' : 'Sai' }}
                    </span>

                    @if($q['has_audio'] && $q['audio_url'])
                        <audio controls style="width:100%;max-width:380px;margin:8px 0;" src="{{ $q['audio_url'] }}"></audio>
                    @endif

                    @if($q['type'] === 'multiple_choice')
                        <div class="pt-q-text">{!! $q['question_text'] !!}</div>
                        @foreach($q['options'] as $option)
                            @php $isGiven = $item['given'] === $option; @endphp
                            <div class="pt-option" style="{{ $isGiven ? ($isCorrect ? 'border-color:#22c55e;background:#f0fdf4;' : 'border-color:#ef4444;background:#fef2f2;') : '' }}">
                                {{ $option }} @if($isGiven)<strong class="ml-2">(học viên chọn)</strong>@endif
                            </div>
                        @endforeach

                    @elseif($q['type'] === 'listening_image_choice')
                        <div class="pt-q-text">{!! $q['question_text'] !!}</div>
                        <div class="pt-img-options">
                            @foreach($q['image_options'] as $imgOpt)
                                @php $isGiven = $item['given'] === $imgOpt['label']; @endphp
                                <div class="pt-img-option" style="{{ $isGiven ? ($isCorrect ? 'border-color:#22c55e;' : 'border-color:#ef4444;') : '' }}">
                                    <img src="{{ $imgOpt['url'] }}">
                                    {{ $imgOpt['label'] }} @if($isGiven)(đã chọn)@endif
                                </div>
                            @endforeach
                        </div>

                    @elseif($q['type'] === 'error_correction')
                        <div class="pt-q-text">{!! $q['question_text'] !!}</div>
                        <div class="p-2" style="background:{{ $isCorrect ? '#f0fdf4' : '#fef2f2' }};border-radius:8px;">
                            Học viên trả lời: {{ $item['given'] ?: '(bỏ trống)' }}
                        </div>

                    @elseif($q['type'] === 'sentence_completion')
                        @php $parts = preg_split('/_{2,}/', $q['question_text']); @endphp
                        <div class="pt-q-text" style="font-weight:400;">
                            @foreach($parts as $pIdx => $part)
                                {!! nl2br(e($part)) !!}
                                @if($pIdx < count($parts) - 1)
                                    <strong style="color:{{ $isCorrect ? '#16a34a' : '#dc2626' }};">
                                        [{{ $item['given'][$pIdx] ?? '(bỏ trống)' }}]
                                    </strong>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <div class="pt-correct-line">Đáp án đúng: {{ $item['correct_display'] }}</div>
                    @if($item['answer_help'])
                        <div class="mt-2 p-2" style="background:#eff6ff;border-left:3px solid #3b82f6;border-radius:6px;font-size:13px;color:#1e40af;">
                            <i class="fas fa-lightbulb mr-1"></i><strong>Giải thích:</strong> {{ $item['answer_help'] }}
                        </div>
                    @endif
                </div>
            @endforeach
        @endforeach

        <div class="pt-q-card">
            <h5>Phần thi Speaking</h5>
            @if($attempt->speakingQuestion)
                <div class="mb-2 text-muted">{{ $attempt->speakingQuestion->question_text }}</div>
            @endif
            @if($speakingAudioUrl)
                <audio controls style="width:100%;max-width:400px;" src="{{ $speakingAudioUrl }}"></audio>
            @else
                <div class="text-muted">Học viên chưa ghi âm phần này.</div>
            @endif
        </div>
    </div>
</section>
@endsection