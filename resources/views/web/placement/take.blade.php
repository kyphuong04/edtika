@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $dontShowCookieSecurity = true;

    $hasSharedPassage = $questions->contains(fn ($q) => $q['linked_to_passage']) && !empty($test->reading_passage);
@endphp

@push('styles_top')
<style>
.pt-mini-header { display:flex; align-items:center; justify-content:space-between; max-width:900px; margin:0 auto 20px; padding:16px 20px 0; }
.pt-mini-header .pt-brand { font-size:26px; font-weight:900; color:#511D99; text-decoration:none; }
.pt-play-wrap { max-width: 900px; margin: 0 auto; padding: 0 20px 80px; }
.pt-play-header { display:flex; align-items:center; justify-content:space-between; background:#511D99; color:#fff; border-radius:16px; padding:20px 24px; margin-bottom:24px; }
.pt-play-header .pt-step-label { font-size:13px; opacity:.85; margin-bottom:4px; }
.pt-play-header .pt-level-label { font-size:22px; font-weight:800; }
.pt-timer { background:rgba(255,255,255,.15); border-radius:12px; padding:10px 18px; font-size:20px; font-weight:700; font-variant-numeric: tabular-nums; }
.pt-timer.is-warning { background:#fbbf24; color:#78350f; }
.pt-passage-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:20px; margin-bottom:18px; }
.pt-passage-card h5 { font-weight:700; margin-bottom:10px; color:#511D99; }
.pt-q-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:20px; margin-bottom:18px; box-shadow:0 1px 3px rgba(0,0,0,.04); }
.pt-q-num { display:inline-block; background:#f3e8ff; color:#511D99; font-weight:700; font-size:13px; padding:3px 10px; border-radius:8px; margin-bottom:10px; }
.pt-q-text { font-size:16px; font-weight:600; color:#111827; margin-bottom:14px; line-height:1.5; }
.pt-option { display:flex; align-items:center; gap:10px; padding:10px 14px; border:2px solid #e5e7eb; border-radius:10px; margin-bottom:8px; cursor:pointer; transition:.15s; }
.pt-option:hover { border-color:#c4b5fd; background:#faf5ff; }
.pt-option input { accent-color:#511D99; }
.pt-word-bank { display:flex; flex-wrap:wrap; gap:8px; background:#f5f3ff; border:1px dashed #a78bfa; border-radius:10px; padding:12px 14px; margin-bottom:14px; }
.pt-word-chip { background:#fff; border:1px solid #ddd6fe; color:#511D99; font-weight:600; font-size:13px; padding:4px 12px; border-radius:20px; }
.pt-blank-input { border:none; border-bottom:2px solid #a78bfa; padding:2px 6px; min-width:110px; text-align:center; font-weight:600; color:#511D99; background:transparent; }
.pt-blank-input:focus { outline:none; border-color:#511D99; }
.pt-blank-hint { font-size:12px; color:#9333ea; font-weight:600; margin-left:4px; }
.pt-img-options { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
.pt-img-option { border:2px solid #e5e7eb; border-radius:12px; padding:10px; text-align:center; cursor:pointer; }
.pt-img-option:hover { border-color:#c4b5fd; }
.pt-img-option img { width:100%; height:110px; object-fit:cover; border-radius:8px; margin-bottom:8px; background:#f3f4f6; }
.pt-audio-btn { display:inline-flex; align-items:center; gap:8px; background:#eef2ff; color:#511D99; border:none; border-radius:20px; padding:6px 16px; font-weight:600; font-size:13px; margin-bottom:10px; cursor:pointer; }
.pt-submit-actions { position:sticky; bottom:16px; display:flex; justify-content:center; margin-top:24px; }
.pt-submit-btn { background:#511D99; color:#fff; border:none; border-radius:14px; padding:14px 32px; font-weight:700; font-size:15px; box-shadow:0 8px 20px rgba(81,29,153,.3); cursor:pointer; }
.pt-submit-btn:disabled { opacity:.6; cursor:not-allowed; }

.pt-countdown-overlay {
    position:fixed; inset:0; z-index:2000; display:none;
    align-items:center; justify-content:center; flex-direction:column;
    background:linear-gradient(180deg,#511D99 0%,#7c3aed 100%);
}
.pt-countdown-overlay.is-open { display:flex; }
.pt-countdown-label { color:#fff; font-size:22px; font-weight:700; margin-bottom:24px; opacity:.9; }
.pt-countdown-number { color:#fff; font-size:140px; font-weight:900; line-height:1; }
.pt-content-hidden { visibility:hidden; }
</style>
@endpush

@section('content')
<div id="ptPageContent" class="{{ $showCountdown ? 'pt-content-hidden' : '' }}">
<div class="pt-mini-header">
    <a href="/" class="pt-brand">EDTIKA</a>
</div>

<div class="pt-play-wrap">
    <div class="pt-play-header">
        <div>
            <div class="pt-step-label">Đề {{ $attempt->current_step }} / 3 &middot; Level {{ $test->level }}</div>
            <div class="pt-level-label">{{ $test->title }}</div>
        </div>
        <div class="pt-timer" id="ptCountdown">--:--</div>
    </div>

    @if($hasSharedPassage)
        <div class="pt-passage-card">
            <h5><i class="fas fa-book-open mr-2"></i>Đoạn văn đọc</h5>
            <div>{!! nl2br(e($test->reading_passage)) !!}</div>
        </div>
    @endif

    <form id="placementTakeForm" action="{{ route('placement.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="test_id" value="{{ $test->id }}">

        @foreach($questions as $index => $q)
            <div class="pt-q-card">
                <span class="pt-q-num">Câu {{ $index + 1 }}@if($q['has_audio']) &middot; Listening @endif</span>

                @if($q['has_audio'] && $q['audio_url'])
                    <div>
                        <button type="button" class="pt-audio-btn" onclick="document.getElementById('audio-{{ $q['id'] }}').play()">
                            <i class="fas fa-play"></i> Nghe audio
                        </button>
                        <audio id="audio-{{ $q['id'] }}" src="{{ $q['audio_url'] }}" preload="none"></audio>
                    </div>
                @endif

                @if($q['type'] === 'multiple_choice')
                    <div class="pt-q-text">{!! $q['question_text'] !!}</div>
                    @foreach($q['options'] as $optIndex => $option)
                        <label class="pt-option">
                            <input type="radio" name="answers[{{ $q['id'] }}]" value="{{ $option }}" required>
                            {{ chr(65 + $optIndex) }}. {{ $option }}
                        </label>
                    @endforeach

                @elseif($q['type'] === 'listening_image_choice')
                    <div class="pt-q-text">{!! $q['question_text'] !!}</div>
                    <div class="pt-img-options">
                        @foreach($q['image_options'] as $imgOpt)
                            <label class="pt-img-option">
                                <img src="{{ $imgOpt['url'] }}" alt="{{ $imgOpt['label'] }}">
                                <input type="radio" name="answers[{{ $q['id'] }}]" value="{{ $imgOpt['label'] }}" required>
                                {{ $imgOpt['label'] }}
                            </label>
                        @endforeach
                    </div>

                @elseif($q['type'] === 'error_correction')
                    <div class="pt-q-text">{!! $q['question_text'] !!}</div>
                    <input type="text" class="form-control" name="answers[{{ $q['id'] }}]" placeholder="Nhập lại câu đúng hoàn chỉnh..." required>

                @elseif($q['type'] === 'sentence_completion')
                    @if(!empty($q['word_bank']))
                        <div class="pt-word-bank">
                            @foreach($q['word_bank'] as $word)
                                <span class="pt-word-chip">{{ $word }}</span>
                            @endforeach
                        </div>
                    @endif

                    @php
                        // Tách question_text theo dấu ___ để chèn input ngay tại vị trí chỗ trống.
                        $parts = preg_split('/_{2,}/', $q['question_text']);
                    @endphp

                    <div class="pt-q-text" style="font-weight:400;">
                        @foreach($parts as $partIndex => $part)
                            {!! nl2br(e($part)) !!}
                            @if($partIndex < count($parts) - 1)
                                <input type="text" class="pt-blank-input" name="answers[{{ $q['id'] }}][]" placeholder="..." required>
                                @if(!empty($q['blank_hints'][$partIndex]))
                                    <span class="pt-blank-hint">({{ $q['blank_hints'][$partIndex] }})</span>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        <div class="pt-submit-actions">
            <button type="submit" class="pt-submit-btn" id="ptSubmitBtn">
                <i class="fas fa-check mr-2"></i>Nộp bài đề này
            </button>
        </div>
    </form>
</div>
</div>{{-- /ptPageContent --}}

@if($showCountdown)
    <div class="pt-countdown-overlay is-open" id="ptCountdownOverlay">
        <div class="pt-countdown-label">Bài test sẽ bắt đầu trong</div>
        <div class="pt-countdown-number" id="ptCountdownNumber">5</div>
    </div>
@endif

<script>
    function startExamTimer() {
        var seconds = {{ (int) $remainingSeconds }};
        var el = document.getElementById('ptCountdown');
        var form = document.getElementById('placementTakeForm');
        var submitBtn = document.getElementById('ptSubmitBtn');
        var autoSubmitted = false;

        function render() {
            var m = String(Math.floor(Math.max(seconds, 0) / 60)).padStart(2, '0');
            var s = String(Math.max(seconds, 0) % 60).padStart(2, '0');
            el.textContent = m + ':' + s;
            el.classList.toggle('is-warning', seconds <= 60);
        }

        render();

        var timer = setInterval(function () {
            seconds--;
            render();

            if (seconds <= 0 && !autoSubmitted) {
                autoSubmitted = true;
                clearInterval(timer);
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-hourglass-end mr-2"></i>Hết giờ, đang tự nộp bài...';
                // Hết giờ -> tự nộp bài với đáp án hiện có (kể cả còn thiếu),
                // để server chấm điểm những gì đã trả lời và finalize kết quả.
                form.submit();
            }
        }, 1000);

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang nộp bài...';
        });
    }

    @if($showCountdown)
        (function () {
            // Nội dung đề thi ĐÃ có sẵn trong trang (chỉ đang bị ẩn bằng CSS
            // visibility:hidden) — countdown 5->1 chạy hoàn toàn ở client, không
            // phụ thuộc mạng. Đồng hồ 10 phút của bài thi chỉ bắt đầu chạy SAU
            // khi countdown này kết thúc và nội dung được hiện ra.
            var overlay = document.getElementById('ptCountdownOverlay');
            var numberEl = document.getElementById('ptCountdownNumber');
            var pageContent = document.getElementById('ptPageContent');
            var count = 5;
            numberEl.textContent = count;

            var timer = setInterval(function () {
                count--;
                if (count <= 0) {
                    clearInterval(timer);
                    overlay.classList.remove('is-open');
                    pageContent.classList.remove('pt-content-hidden');
                    startExamTimer();
                    return;
                }
                numberEl.textContent = count;
            }, 1000);
        })();
    @else
        startExamTimer();
    @endif
</script>
@endsection