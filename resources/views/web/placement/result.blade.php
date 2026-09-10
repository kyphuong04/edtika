{{-- TODO: đổi lại @extends cho đúng layout thật của trang chủ (design_1.web...?) --}}
@extends('design_1.web.layouts.app')

@php
    // Các cờ này khiến layout ẩn header/footer mặc định (header xanh "Rocket LMS"),
    // giống cách home/index.blade.php đang làm — để trang tự kiểm soát toàn bộ giao diện.
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $dontShowCookieSecurity = true;
@endphp

@push('styles_top')
<style>
.pt-mini-header { display:flex; align-items:center; justify-content:space-between; max-width:760px; margin:0 auto 20px; padding:16px 20px 0; }
.pt-mini-header .pt-brand { font-size:26px; font-weight:900; color:#511D99; text-decoration:none; }
.pt-mini-header .pt-back-link { font-size:14px; font-weight:600; color:#511D99; text-decoration:none; }
.pt-result-wrap { max-width: 760px; margin: 0 auto; padding: 32px 20px 80px; }
.pt-note-banner { background:#fef3c7; border-left:4px solid #f59e0b; padding:12px 16px; border-radius:10px; margin-bottom:24px; font-size:14px; color:#78350f; }
.pt-result-hero { background:linear-gradient(135deg,#511D99 0%,#7c3aed 100%); color:#fff; border-radius:20px; padding:36px 32px; text-align:center; margin-bottom:24px; }
.pt-result-hero .pt-hero-label { font-size:14px; opacity:.85; margin-bottom:8px; }
.pt-result-hero .pt-hero-level { font-size:52px; font-weight:800; line-height:1; margin-bottom:8px; }
.pt-result-hero .pt-hero-sub { font-size:14px; opacity:.9; }
.pt-breakdown-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:22px 24px; margin-bottom:20px; }
.pt-breakdown-card h4 { font-size:16px; font-weight:700; margin-bottom:16px; color:#111827; }
.pt-breakdown-row { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f3f4f6; }
.pt-breakdown-row:last-child { border-bottom:none; }
.pt-breakdown-level { display:inline-block; background:#f3e8ff; color:#511D99; font-weight:700; font-size:13px; padding:3px 12px; border-radius:8px; }
.pt-speaking-card { background:#eef2ff; border:1px solid #c7d2fe; border-radius:16px; padding:20px 24px; margin-bottom:24px; display:flex; align-items:center; gap:16px; }
.pt-speaking-card i { font-size:28px; color:#511D99; }
.pt-cta-row { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
.pt-cta-btn { border-radius:14px; padding:12px 26px; font-weight:700; font-size:14px; text-decoration:none; display:inline-flex; align-items:center; gap:8px; }
.pt-cta-primary { background:#511D99; color:#fff; }
.pt-cta-secondary { background:#fff; color:#511D99; border:2px solid #511D99; }


.pt-answer-q-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 18px 22px;
    margin-bottom: 20px;
}
.pt-answer-passage-card {
    background: #faf5ff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 18px 22px;
    margin-bottom: 20px;
}
.pt-answer-q-card .badge {
    margin-bottom: 10px;
}

#studentAnswerKeyModal-0 .modal-dialog,
.modal[id^="studentAnswerKeyModal-"] .modal-dialog {
    max-width: 95vw;
}
.modal[id^="studentAnswerKeyModal-"] .modal-body {
    padding: 24px 28px;
    max-height: 75vh;
    overflow-y: auto;
}
.modal[id^="studentAnswerKeyModal-"] .modal-body > div {
    margin-bottom: 18px !important;
}

/* ── Nhóm audio dùng chung cho nhiều câu (VD: câu 9–10) ── */
.pt-audio-group {
    background:#eef2ff; border:1px solid #c7d2fe; border-radius:14px;
    padding:12px 16px; margin-bottom:16px;
}
.pt-audio-group-label {
    font-size:12px; font-weight:700; color:#4338CA;
    text-transform:uppercase; letter-spacing:.4px; margin-bottom:8px;
}
.pt-rich ul,
.pt-rich ol { padding-left: 24px !important; margin-bottom: 10px !important; }
.pt-rich ul { list-style: disc !important; }
.pt-rich ol { list-style: decimal !important; }
.pt-rich li { display: list-item !important; list-style: inherit !important; }
.pt-rich p  { margin-bottom: 8px; }
.pt-rich p:last-child { margin-bottom: 0; }
.pt-rich [style*="text-align: center"]  { text-align: center !important; }
.pt-rich [style*="text-align: right"]   { text-align: right !important; }
.pt-rich [style*="text-align: justify"] { text-align: justify !important; }
</style>
@endpush

@section('content')
<div class="pt-mini-header">
    <a href="/" class="pt-brand">EDTIKA</a>
    <a href="/" class="pt-back-link"><i class="fas fa-arrow-left mr-1"></i>Về trang chủ</a>
</div>
<div class="pt-result-wrap">

    @if($isDemoData)
        <div class="pt-note-banner">
            <i class="fas fa-flask mr-1"></i>
            <strong>Bản xem trước giao diện.</strong> Bạn chưa có kết quả Placement Test thật trong hệ thống —
            đây là dữ liệu mẫu (hardcode) để xem giao diện trước khi nối logic chấm điểm thật.
        </div>
    @endif

    <div class="pt-result-hero">
        <div class="pt-hero-label">Kết quả Placement Test của bạn</div>
        <div class="pt-hero-level">{{ $attempt->final_level }}</div>
        <div class="pt-hero-sub">
            Hoàn thành lúc {{ optional($attempt->completed_at)->format('H:i, d/m/Y') }}
            &middot; Đã làm {{ count($attempt->test_ids_taken ?? []) }}/3 đề
        </div>
    </div>

    <!-- <div class="pt-breakdown-card">
        <h4><i class="fas fa-list-ol mr-2"></i>Chi tiết từng đề</h4>
        @foreach(($attempt->test_ids_taken ?? []) as $i => $testId)
            <div class="pt-breakdown-row">
                <span>Đề {{ $i + 1 }}</span>
                <span>
                    <span class="pt-breakdown-level">{{ ($attempt->scores[$i] ?? '—') }}/10 câu đúng</span>
                </span>
            </div>
        @endforeach
    </div> -->

    <div class="pt-breakdown-card">
        <h4><i class="fas fa-list-ol mr-2"></i>Chi tiết từng đề</h4>
        @foreach(($attempt->test_ids_taken ?? []) as $i => $testId)
            <div class="pt-breakdown-row">
                <span>Đề {{ $i + 1 }}</span>
                <span class="d-flex align-items-center gap-8">
                    <span class="pt-breakdown-level">{{ ($attempt->scores[$i] ?? '—') }}/10 câu đúng</span>
                    @if(!$isDemoData && isset($testBlocks[$i]))
                        <button type="button" class="btn btn-sm" style="border:1px solid #511D99;color:#511D99;border-radius:8px;" data-toggle="modal" data-target="#studentAnswerKeyModal-{{ $i }}">
                            <i class="fas fa-key mr-1"></i>Xem đáp án
                        </button>
                    @endif
                </span>
            </div>
        @endforeach
    </div>

    @if(!$isDemoData)
        @foreach($testBlocks as $block)
            @php
                $blockPassages = collect($block['test']->reading_passages ?? [])->keyBy('position');
            @endphp
            <div class="modal fade" id="studentAnswerKeyModal-{{ $block['index'] }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background:#511D99;color:#fff;">
                            <h5 class="modal-title">
                                Chi tiết bài làm — Đề {{ $block['index'] + 1 }} (Level {{ $block['test']->level }})
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
  
                            @foreach($block['questions'] as $idx => $item)

                                @if($blockPassages->has($idx + 1))
                                    <div class="pt-answer-passage-card">
                                        <strong>Đoạn văn đọc:</strong>
                                        <div class="mt-2">{!! nl2br(e($blockPassages[$idx + 1]['content'])) !!}</div>
                                    </div>
                                @endif

                                @php $q = $item['question']; $isCorrect = $item['is_correct']; @endphp
                                <div class="pt-answer-q-card" style="border-left:5px solid {{ $isCorrect ? '#22c55e' : '#ef4444' }};">
                                    <span style="display:inline-block;background:#f3e8ff;color:#511D99;font-weight:700;font-size:13px;padding:3px 10px;border-radius:8px;">Câu {{ $idx + 1 }}</span>
                                    <span class="float-right badge {{ $isCorrect ? 'badge-success' : 'badge-danger' }}">
                                        {{ $isCorrect ? 'Đúng' : 'Sai' }}
                                    </span>

                                    <!-- @if($q['has_audio'] && $q['audio_url'])
                                        <audio controls style="width:100%;max-width:380px;margin:10px 0;display:block;" src="{{ $q['audio_url'] }}"></audio>
                                    @endif -->

                                    @if($q['type'] === 'multiple_choice')
                                        <div style="font-size:15px;font-weight:600;color:#111827;margin:10px 0;">{!! $q['question_text'] !!}</div>
                                        @foreach($q['options'] as $option)
                                            @php $isGiven = $item['given'] === $option; @endphp
                                            <div class="p-2 mb-1" style="border:2px solid {{ $isGiven ? ($isCorrect ? '#22c55e' : '#ef4444') : '#e5e7eb' }};background:{{ $isGiven ? ($isCorrect ? '#f0fdf4' : '#fef2f2') : '#fff' }};border-radius:8px;">
                                                {{ $option }} @if($isGiven)<strong class="ml-2">(bạn đã chọn)</strong>@endif
                                            </div>
                                        @endforeach

                                    @elseif($q['type'] === 'listening_image_choice')
                                        <div style="font-size:15px;font-weight:600;color:#111827;margin:10px 0;">{!! $q['question_text'] !!}</div>
                                        <div class="row">
                                            @foreach($q['image_options'] as $imgOpt)
                                                @php $isGiven = $item['given'] === $imgOpt['label']; @endphp
                                                <div class="col-4">
                                                    <div class="p-2 text-center mb-2" style="border:2px solid {{ $isGiven ? ($isCorrect ? '#22c55e' : '#ef4444') : '#e5e7eb' }};border-radius:10px;">
                                                        <img src="{{ $imgOpt['url'] }}" style="width:100%;height:90px;object-fit:cover;border-radius:6px;margin-bottom:6px;">
                                                        {{ $imgOpt['label'] }} @if($isGiven)(đã chọn)@endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif($q['type'] === 'error_correction')
                                        <div style="font-size:15px;font-weight:600;color:#111827;margin:10px 0;">{!! $q['question_text'] !!}</div>
                                        <div class="p-2" style="background:{{ $isCorrect ? '#f0fdf4' : '#fef2f2' }};border-radius:8px;">
                                            Bạn trả lời: {{ $item['given'] ?: '(bỏ trống)' }}
                                        </div>

                                    @elseif($q['type'] === 'sentence_completion')
                                        @php $parts = preg_split('/_{2,}/', $q['question_text']); @endphp
                                        <div style="font-size:15px;color:#111827;margin:10px 0;line-height:1.5;">
                                            @foreach($parts as $pIdx => $part)
                                                {!! nl2br(e($part)) !!}
                                                @if($pIdx < count($parts) - 1)
                                                    <strong style="color:{{ $isCorrect ? '#16a34a' : '#dc2626' }};">
                                                        @php $givenArr = is_array($item['given']) ? $item['given'] : []; @endphp
                                                        [{{ $givenArr[$pIdx] ?? '(bỏ trống)' }}]
                                                    </strong>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    <div style="margin-top:10px;font-size:13px;font-weight:700;color:#16a34a;">
                                        Đáp án đúng: {{ $item['correct_display'] }}
                                    </div>
                                </div>
                            @endforeach

                            @if($blockPassages->has(count($block['questions']) + 1))
                                <div class="pt-answer-passage-card">
                                    <strong>Đoạn văn đọc:</strong>
                                    <div class="mt-2">{!! nl2br(e($blockPassages[count($block['questions']) + 1]['content'])) !!}</div>
                                </div>
                            @endif

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <div class="pt-speaking-card">
        <i class="fas fa-microphone-alt"></i>
        <div>
            <div style="font-weight:700;color:#111827;">Phần Speaking đã ghi nhận</div>
            <div style="font-size:13px;color:#4b5563;">
                Câu trả lời Speaking của bạn không ảnh hưởng đến kết quả trên — đã được lưu lại để
                giáo viên nghe và tư vấn lộ trình học phù hợp cho bạn.
            </div>
        </div>
    </div>

    <div class="pt-cta-row">
        <a href="/classes" class="pt-cta-btn pt-cta-primary"><i class="fas fa-graduation-cap"></i>Xem lộ trình học phù hợp</a>
        <a href="/" class="pt-cta-btn pt-cta-secondary"><i class="fas fa-home"></i>Về trang chủ</a>
    </div>

</div>
@endsection