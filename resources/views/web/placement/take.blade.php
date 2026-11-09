@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $dontShowCookieSecurity = true;
@endphp

@push('styles_top')
<link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css">
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
.pt-img-option img {
    width: 100%;
    aspect-ratio: 3 / 2;
    height: auto;
    object-fit: contain;
    background: #f8fafc;
    border-radius: 8px;
    margin-bottom: 8px;
    display: block;
}

.pt-audio-btn { display:inline-flex; align-items:center; gap:8px; background:#eef2ff; color:#511D99; border:none; border-radius:20px; padding:6px 16px; font-weight:600; font-size:13px; margin-bottom:10px; cursor:pointer; }
.pt-submit-actions { position:sticky; bottom:16px; display:flex; justify-content:center; margin-top:24px; }
.pt-submit-btn { background:#511D99; color:#fff; border:none; border-radius:14px; padding:14px 32px; font-weight:700; font-size:15px; box-shadow:0 8px 20px rgba(81,29,153,.3); cursor:pointer; }
.pt-submit-btn:disabled { opacity:.6; cursor:not-allowed; }
.pt-notice { background:#eef2ff; border:1px solid #c7d2fe; color:#3730a3; border-radius:12px; padding:12px 16px; margin-bottom:16px; font-size:14px; font-weight:600; }
.pt-submit-error { background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; border-radius:12px; padding:12px 16px; margin-bottom:12px; font-size:14px; }
.pt-submit-error[hidden] { display:none; }

.pt-countdown-overlay {
    position:fixed; inset:0; z-index:2000; display:none;
    align-items:center; justify-content:center; flex-direction:column;
    background:linear-gradient(180deg,#511D99 0%,#7c3aed 100%);
}
.pt-countdown-overlay.is-open { display:flex; }
.pt-countdown-label { color:#fff; font-size:22px; font-weight:700; margin-bottom:24px; opacity:.9; }
.pt-countdown-number { color:#fff; font-size:140px; font-weight:900; line-height:1; }
.pt-content-hidden { visibility:hidden; }

.pt-audio-player { display:flex; align-items:center; gap:10px; background:#eef2ff; border-radius:18px; padding:8px 14px; margin-bottom:12px; max-width:380px; }
.pt-audio-play-toggle { width:34px; height:34px; border-radius:50%; background:#511D99; color:#fff; border:none; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; font-size:13px; }
.pt-audio-play-toggle:hover { background:#3f1677; }
.pt-audio-time, .pt-audio-duration { font-size:12px; font-weight:700; color:#511D99; white-space:nowrap; min-width:34px; }
.pt-audio-track { flex:1; height:5px; background:#ddd6fe; border-radius:99px; position:relative; cursor:pointer; min-width:70px; }
.pt-audio-fill { position:absolute; left:0; top:0; height:100%; background:#511D99; border-radius:99px; width:0%; pointer-events:none; }

/* ── Nhóm audio dùng chung cho nhiều câu (VD: câu 9–10) ── */
.pt-audio-group {
    background:#eef2ff; border:1px solid #c7d2fe; border-radius:14px;
    padding:12px 16px; margin-bottom:16px;
}
.pt-audio-group-label {
    font-size:12px; font-weight:700; color:#4338CA;
    text-transform:uppercase; letter-spacing:.4px; margin-bottom:8px;
}
.pt-audio-group .pt-audio-player { margin-bottom:0; background:#fff; }

/* ── Hiển thị nội dung rich text soạn từ admin ────────────────────────
   Theme reset ul/ol về list-style:none nên phải đè bằng !important.
   HTMLPurifier chuẩn hoá style về dạng "text-align:center" (không dấu
   cách) nên selector phải khớp cả hai biến thể. */
.pt-rich ul,
.pt-rich ol { padding-left: 24px !important; margin-bottom: 10px !important; }
.pt-rich ul { list-style: disc !important; }
.pt-rich ol { list-style: decimal !important; }
.pt-rich li { display: list-item !important; list-style: inherit !important; }
.pt-rich p  { margin-bottom: 8px; }
.pt-rich p:last-child { margin-bottom: 0; }
.pt-rich [style*="text-align:center"],
.pt-rich [style*="text-align: center"]  { text-align: center !important; }
.pt-rich [style*="text-align:right"],
.pt-rich [style*="text-align: right"]   { text-align: right !important; }
.pt-rich [style*="text-align:justify"],
.pt-rich [style*="text-align: justify"] { text-align: justify !important; }
.pt-rich [style*="text-align:left"],
.pt-rich [style*="text-align: left"]    { text-align: left !important; }

.pt-instruction {
    background: #f5f3ff;
    border-left: 4px solid #a78bfa;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 12px;
    font-size: 14px;
    font-weight: 600;
    color: #4c1d95;
    line-height: 1.5;
}


/* ── Hàng đầu: số thứ tự + đề bài trên CÙNG MỘT DÒNG ─────────────────
   Nội dung câu hỏi tách xuống dòng riêng để chiếm trọn chiều rộng,
   dễ đọc với câu dài. */
.pt-q-head {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.pt-q-head .pt-q-num {
    flex-shrink: 0;
    margin-bottom: 0;
}

/* Đề bài: khung tím nhạt kéo dài hết phần còn lại của hàng. */
.pt-q-head .pt-instruction {
    flex: 1;
    min-width: 0;
    margin-bottom: 0;
    border-left: none;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 13.5px;
}

/* Đề bài do Summernote sinh ra bọc trong <p> -> gỡ margin để không
   làm lệch chiều cao hàng. */
.pt-q-head .pt-instruction p { margin: 0; }

@media (max-width: 575px) {
    .pt-q-head { display: block; }
    .pt-q-head .pt-q-num { display: inline-block; margin-bottom: 8px; }
    .pt-q-head .pt-instruction { margin-top: 4px; }
}

/* ── Khoảng cách trong thẻ đoạn văn ───────────────────────────────────
   Rule gốc .pt-word-bank chỉ có margin-bottom (dùng cho word bank của
   câu sentence_completion); trong thẻ đoạn văn cần cả margin-top để
   tách khỏi tiêu đề. */
.pt-passage-card .pt-word-bank {
    margin-top: 14px;
    margin-bottom: 18px;
}
.pt-passage-card { margin-bottom: 24px; }
.pt-passage-card h5 { margin-bottom: 14px; }

@media (max-width: 575px) {
    /* Màn hình hẹp: cho nội dung xuống dòng riêng cho dễ đọc. */
    .pt-q-head { display: block; }
    .pt-q-head .pt-q-num { display: inline-block; margin-bottom: 8px; }
    .pt-q-head .pt-q-text > p:first-child { display: block; }
}
/* Nhãn nhóm audio + đề bài nằm cùng hàng. Nhãn viết hoa, đề bài giữ
   nguyên chữ thường nên phải tách text-transform ra khỏi hàng cha. */
.pt-audio-group-head {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}
.pt-audio-group-head .pt-audio-group-label {
    margin-bottom: 0;
    flex-shrink: 0;
}
.pt-audio-group-instruction {
    background: #fff;
    border-radius: 8px;
    padding: 5px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #4c1d95;
    line-height: 1.4;
    flex: 1;
    min-width: 0;
}

/* Tiêu đề đoạn văn + đề bài trên cùng một hàng, giống nhóm audio. */
.pt-passage-head {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}
.pt-passage-head h5 {
    margin-bottom: 0;
    flex-shrink: 0;
}
.pt-passage-instruction {
    background: #f5f3ff;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 13.5px;
    font-weight: 600;
    color: #4c1d95;
    line-height: 1.4;
    flex: 1;
    min-width: 0;
}

@media (max-width: 575px) {
    .pt-passage-head { display: block; }
    .pt-passage-head h5 { margin-bottom: 8px; }
}
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

    @if(!empty($notice))
        <div class="pt-notice" role="status"><i class="fas fa-info-circle mr-2"></i>{{ $notice }}</div>
    @endif

    @php
        $passages = collect($test->reading_passages ?? [])->keyBy('position');
    @endphp

    <form id="placementTakeForm" action="{{ route('placement.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="test_id" value="{{ $test->id }}">
        <input type="hidden" name="step" value="{{ $attempt->current_step }}">

        @foreach($questions as $index => $q)

            @if($passages->has($index + 1))
                @php $psg = $passages[$index + 1]; @endphp
                <div class="pt-passage-card">
                    <div class="pt-passage-head">
                        <h5><i class="fas fa-book-open mr-2"></i>Đoạn văn đọc</h5>

                        @if(!empty($psg['instruction']))
                            <div class="pt-passage-instruction">{{ $psg['instruction'] }}</div>
                        @endif
                    </div>

                    @if(!empty($psg['word_bank']))
                        <div class="pt-word-bank">
                            @foreach($psg['word_bank'] as $word)
                                <span class="pt-word-chip">{{ $word }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="pt-rich">{!! ptRichText($psg['content']) !!}</div>
                </div>
            @endif

            
            @if(!empty($q['audio_group_start']) && $q['audio_url'])
                <div class="pt-audio-group">
                    <div class="pt-audio-group-head">
                        <div class="pt-audio-group-label">
                            <i class="fas fa-headphones mr-1"></i>Audio cho câu {{ $q['audio_group_range'] ?? ($index + 1) }}
                        </div>

                        @if(!empty($q['audio_group_instruction']))
                            <div class="pt-audio-group-instruction">{{ $q['audio_group_instruction'] }}</div>
                        @endif
                    </div>

                    <div class="pt-audio-player" data-audio-player>
                        <audio id="audio-{{ $q['id'] }}" src="{{ $q['audio_url'] }}" preload="metadata"></audio>
                        <button type="button" class="pt-audio-play-toggle" data-audio-toggle>
                            <i class="fas fa-play"></i>
                        </button>
                        <span class="pt-audio-time" data-audio-time>00:00</span>
                        <div class="pt-audio-track" data-audio-track>
                            <div class="pt-audio-fill" data-audio-fill></div>
                        </div>
                        <span class="pt-audio-duration" data-audio-duration>00:00</span>
                    </div>
                </div>
            @endif

            <div class="pt-q-card">

                <div class="pt-q-head">
                    <span class="pt-q-num">Câu {{ $index + 1 }}@if($q['has_audio']) &middot; Listening @endif</span>
                    @if(!empty($q['instruction']))
                        <div class="pt-instruction pt-rich">{!! ptRichText($q['instruction']) !!}</div>
                    @endif
                </div>
                @if($q['type'] !== 'sentence_completion')
                    <div class="pt-q-text pt-rich">{!! ptRichText($q['question_text']) !!}</div>
                @endif

                @if($q['type'] === 'multiple_choice')
                    @foreach($q['options'] as $optIndex => $option)
                        <label class="pt-option">
                            <input type="radio" name="answers[{{ $q['id'] }}]" value="{{ $option }}" required>
                            {{ chr(65 + $optIndex) }}. {{ $option }}
                        </label>
                    @endforeach

                @elseif($q['type'] === 'listening_image_choice')
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
                    <input type="text" class="form-control" name="answers[{{ $q['id'] }}]"
                        placeholder="Nhập lại câu đúng hoàn chỉnh..." required>

                @elseif($q['type'] === 'sentence_completion')
                    @if(!empty($q['word_bank']))
                        <div class="pt-word-bank">
                            @foreach($q['word_bank'] as $word)
                                <span class="pt-word-chip">{{ $word }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tách question_text theo dấu ___ để chèn input ngay tại
                         vị trí chỗ trống. Dạng này là TEXT THUẦN, không qua
                         rich text editor, nên vẫn dùng nl2br(e()). --}}
                    @php $parts = preg_split('/_{2,}/', $q['question_text']); @endphp

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

        @if($passages->has(count($questions) + 1))
            @php $psgLast = $passages[count($questions) + 1]; @endphp
            <div class="pt-passage-card">
                <div class="pt-passage-head">
                    <h5><i class="fas fa-book-open mr-2"></i>Đoạn văn đọc</h5>

                    @if(!empty($psgLast['instruction']))
                        <div class="pt-passage-instruction">{{ $psgLast['instruction'] }}</div>
                    @endif
                </div>

                @if(!empty($psgLast['word_bank']))
                    <div class="pt-word-bank">
                        @foreach($psgLast['word_bank'] as $word)
                            <span class="pt-word-chip">{{ $word }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="pt-rich">{!! ptRichText($psgLast['content']) !!}</div>
            </div>
        @endif

        <div class="pt-submit-error" id="ptSubmitError" role="alert" hidden></div>
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
    /* ═══════════════════════════════════════════════════════════════════
       Nộp bài + đồng hồ.
       - Nộp bằng fetch (cả bấm nút lẫn tự nộp khi hết giờ), server trả URL
         đích -> location.replace(): không tạo thêm mục lịch sử nên bấm Back
         không quay lại được đề vừa nộp.
       - Chỉ nộp 1 lần (cờ `submitting` dùng chung cho nút và đồng hồ).
       - Hết phiên / sai token (419) -> lấy token mới qua /ping rồi gửi lại.
       - Ping ~2 phút/lần để giữ phiên, đồng bộ đồng hồ với server, và phát
         hiện trang đã cũ (đề này đã được nộp ở tab khác) -> tải lại.
       ═══════════════════════════════════════════════════════════════════ */
    var PT = {
        pingUrl: @json(route('placement.ping')),
        takeUrl: @json(route('placement.take')),
        step: {{ (int) $attempt->current_step }},
        testId: {{ (int) $test->id }},
        timeLimit: {{ (int) \App\Models\IeltsPlacementAttempt::TEST_TIME_SECONDS }},
        // Mốc hết giờ theo đồng hồ máy, tính từ số giây server còn lại lúc render.
        deadline: Date.now() + {{ (int) $remainingSeconds }} * 1000,
        submitting: false
    };

    // Trang được khôi phục từ bộ đệm Back/Forward -> tải lại để server trả đúng đề hiện tại.
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) location.reload();
    });

    function ptPing() {
        return fetch(PT.pingUrl, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
            cache: 'no-store'
        }).then(function (res) { return res.ok ? res.json() : null; }).then(function (data) {
            if (!data) return null;
            if (data.token) {
                document.querySelectorAll('input[name="_token"]').forEach(function (i) { i.value = data.token; });
            }
            return data;
        });
    }

    function startExamTimer() {
        var el = document.getElementById('ptCountdown');
        var form = document.getElementById('placementTakeForm');
        var submitBtn = document.getElementById('ptSubmitBtn');
        var errorBox = document.getElementById('ptSubmitError');

        function secondsLeft() {
            return Math.min(PT.timeLimit, Math.max(0, Math.ceil((PT.deadline - Date.now()) / 1000)));
        }

        function render() {
            var left = secondsLeft();
            el.textContent = String(Math.floor(left / 60)).padStart(2, '0') + ':' + String(left % 60).padStart(2, '0');
            el.classList.toggle('is-warning', left <= 60);
        }

        function setBusy(html) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = html;
        }

        function showError(msg) {
            errorBox.textContent = msg;
            errorBox.hidden = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-redo mr-2"></i>Thử nộp lại';
            PT.submitting = false;
        }

        function send(retried) {
            var body = new FormData(form);
            return fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: body,
                credentials: 'same-origin'
            }).then(function (res) {
                if (res.status === 419 && !retried) {
                    // Token hết hạn -> lấy token mới rồi gửi lại 1 lần.
                    return ptPing().then(function () { return send(true); });
                }
                return res.json().catch(function () { return null; }).then(function (data) {
                    if (res.ok && data && data.redirect) {
                        location.replace(data.redirect);
                        return;
                    }
                    if (!retried) {
                        return ptPing().then(function () { return send(true); });
                    }
                    throw new Error('HTTP ' + res.status);
                });
            });
        }

        function submitAnswers(isAuto) {
            if (PT.submitting) return;
            PT.submitting = true;
            clearInterval(timer);
            errorBox.hidden = true;
            setBusy(isAuto
                ? '<i class="fas fa-hourglass-end mr-2"></i>Hết giờ, đang tự nộp bài...'
                : '<i class="fas fa-spinner fa-spin mr-2"></i>Đang nộp bài...');

            send(false).catch(function () {
                // Mất mạng: thử lại sau vài giây (tối đa 3 lần) trước khi báo lỗi —
                // đáp án vẫn còn nguyên trên trang, không bị mất.
                var tries = 0;
                (function retry() {
                    tries++;
                    setTimeout(function () {
                        send(true).catch(function () {
                            if (tries < 3) return retry();
                            showError('Không kết nối được máy chủ. Đáp án của bạn vẫn còn trên trang — kiểm tra mạng rồi bấm "Thử nộp lại".');
                        });
                    }, 3000);
                })();
            });
        }

        render();
        var timer = setInterval(function () {
            render();
            if (secondsLeft() <= 0) {
                // Hết giờ -> tự nộp bài với đáp án hiện có (kể cả còn thiếu).
                submitAnswers(true);
            }
        }, 1000);

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            submitAnswers(false);
        });

        // Giữ phiên + đồng bộ giờ với server + phát hiện trang đã cũ.
        setInterval(function () {
            if (PT.submitting) return;
            ptPing().then(function (data) {
                if (!data || PT.submitting) return;
                if (data.status !== 'in_progress' || Number(data.step) !== PT.step || Number(data.test_id) !== PT.testId) {
                    location.replace(PT.takeUrl);
                    return;
                }
                if (typeof data.remaining === 'number') {
                    PT.deadline = Date.now() + data.remaining * 1000;
                }
            }).catch(function () {});
        }, 120000);
    }

    @if($showCountdown)
        (function () {
            // Nội dung đề thi ĐÃ có sẵn trong trang (chỉ đang bị ẩn bằng CSS
            // visibility:hidden) — countdown 5->1 chạy hoàn toàn ở client, không
            // phụ thuộc mạng. Đồng hồ 10 phút của bài thi chỉ bắt đầu chạy SAU
            // khi countdown này kết thúc (server cũng tính giờ từ mốc đó).
            var overlay = document.getElementById('ptCountdownOverlay');
            var numberEl = document.getElementById('ptCountdownNumber');
            var pageContent = document.getElementById('ptPageContent');
            var count = {{ (int) \App\Models\IeltsPlacementAttempt::START_COUNTDOWN_SECONDS }};
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

    (function () {
    function formatTime(sec) {
        sec = isFinite(sec) ? sec : 0;
        var m = String(Math.floor(sec / 60)).padStart(2, '0');
        var s = String(Math.floor(sec % 60)).padStart(2, '0');
        return m + ':' + s;
    }

    document.querySelectorAll('[data-audio-player]').forEach(function (wrapper) {
        var audio = wrapper.querySelector('audio');
        var toggleBtn = wrapper.querySelector('[data-audio-toggle]');
        var timeEl = wrapper.querySelector('[data-audio-time]');
        var durationEl = wrapper.querySelector('[data-audio-duration]');
        var track = wrapper.querySelector('[data-audio-track]');
        var fill = wrapper.querySelector('[data-audio-fill]');

        audio.addEventListener('loadedmetadata', function () {
            durationEl.textContent = formatTime(audio.duration);
        });

        toggleBtn.addEventListener('click', function () {
            if (audio.paused) {
                // Chỉ cho phát 1 audio tại 1 thời điểm — tự dừng các audio khác
                // đang phát khi học viên bấm nghe câu khác.
                document.querySelectorAll('[data-audio-player] audio').forEach(function (a) {
                    if (a !== audio && !a.paused) {
                        a.pause();
                    }
                });
                audio.play();
                toggleBtn.innerHTML = '<i class="fas fa-pause"></i>';
            } else {
                audio.pause();
                toggleBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
        });

        audio.addEventListener('timeupdate', function () {
            timeEl.textContent = formatTime(audio.currentTime);
            var pct = audio.duration ? (audio.currentTime / audio.duration) * 100 : 0;
            fill.style.width = pct + '%';
        });

        audio.addEventListener('ended', function () {
            toggleBtn.innerHTML = '<i class="fas fa-play"></i>';
            fill.style.width = '0%';
            timeEl.textContent = '00:00';
        });

        // Tua bằng cách bấm trực tiếp lên thanh track
        track.addEventListener('click', function (e) {
            if (!audio.duration) return;
            var rect = track.getBoundingClientRect();
            var pct = Math.min(1, Math.max(0, (e.clientX - rect.left) / rect.width));
            audio.currentTime = pct * audio.duration;
        });
    });
})();
</script>
@endsection
