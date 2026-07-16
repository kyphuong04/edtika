
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
.pt-mini-header { display:flex; align-items:center; justify-content:space-between; max-width:900px; margin:0 auto 20px; padding:16px 20px 0; }
.pt-mini-header .pt-brand { font-size:26px; font-weight:900; color:#511D99; text-decoration:none; }
.pt-mini-header .pt-back-link { font-size:14px; font-weight:600; color:#511D99; text-decoration:none; }
.pt-play-wrap { max-width: 900px; margin: 0 auto; padding: 32px 20px 80px; }
.pt-play-header { display:flex; align-items:center; justify-content:space-between; background:#511D99; color:#fff; border-radius:16px; padding:20px 24px; margin-bottom:24px; }
.pt-play-header .pt-step-label { font-size:13px; opacity:.85; margin-bottom:4px; }
.pt-play-header .pt-level-label { font-size:22px; font-weight:800; }
.pt-timer { background:rgba(255,255,255,.15); border-radius:12px; padding:10px 18px; font-size:20px; font-weight:700; font-variant-numeric: tabular-nums; }
.pt-note-banner { background:#fef3c7; border-left:4px solid #f59e0b; padding:12px 16px; border-radius:10px; margin-bottom:24px; font-size:14px; color:#78350f; }
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
.pt-img-options { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
.pt-img-option { border:2px solid #e5e7eb; border-radius:12px; padding:10px; text-align:center; cursor:pointer; }
.pt-img-option:hover { border-color:#c4b5fd; }
.pt-img-option img { width:100%; height:110px; object-fit:cover; border-radius:8px; margin-bottom:8px; background:#f3f4f6; }
.pt-audio-btn { display:inline-flex; align-items:center; gap:8px; background:#eef2ff; color:#511D99; border:none; border-radius:20px; padding:6px 16px; font-weight:600; font-size:13px; margin-bottom:10px; cursor:pointer; }
.pt-demo-actions { position:sticky; bottom:16px; display:flex; justify-content:center; margin-top:24px; }
.pt-demo-btn { background:#511D99; color:#fff; border:none; border-radius:14px; padding:14px 32px; font-weight:700; font-size:15px; box-shadow:0 8px 20px rgba(81,29,153,.3); cursor:pointer; }
</style>
@endpush

@section('content')
<div class="pt-mini-header">
    <a href="/" class="pt-brand">EDTIKA</a>
    <a href="/" class="pt-back-link"><i class="fas fa-arrow-left mr-1"></i>Về trang chủ</a>
</div>
<div class="pt-play-wrap">

    <div class="pt-note-banner">
        <i class="fas fa-flask mr-1"></i>
        <strong>Bản xem trước giao diện.</strong> Nội dung câu hỏi bên dưới đang là dữ liệu mẫu (hardcode),
        chưa lấy đề thật từ hệ thống bạn vừa tạo — sẽ được nối ở bước tiếp theo. Đáp án bấm vào cũng
        chưa được chấm/lưu.
    </div>

    <div class="pt-play-header">
        <div>
            <div class="pt-step-label">Đề {{ $attempt->current_step ?? 1 }} / 3 &middot; Đang làm đề Level B1</div>
            <div class="pt-level-label">Adaptive Placement Test</div>
        </div>
        <div class="pt-timer" id="ptCountdown">10:00</div>
    </div>

    {{-- Câu 1: Multiple Choice --}}
    <div class="pt-q-card">
        <span class="pt-q-num">Câu 1</span>
        <div class="pt-q-text">My brother ___ football every Sunday.</div>
        <label class="pt-option"><input type="radio" name="q1"> A. play</label>
        <label class="pt-option"><input type="radio" name="q1"> B. plays</label>
        <label class="pt-option"><input type="radio" name="q1"> C. playing</label>
        <label class="pt-option"><input type="radio" name="q1"> D. played</label>
    </div>

    {{-- Câu 2: Sentence Completion có Word Bank --}}
    <div class="pt-q-card">
        <span class="pt-q-num">Câu 2</span>
        <div class="pt-q-text">Complete the conversation with one suitable word from the box.</div>
        <div class="pt-word-bank">
            <span class="pt-word-chip">do</span>
            <span class="pt-word-chip">learn</span>
            <span class="pt-word-chip">make</span>
            <span class="pt-word-chip">take</span>
        </div>
        <div class="pt-q-text" style="font-weight:400;">
            Anna: What do you usually do after school?<br>
            Tom: I usually go home and <input type="text" class="pt-blank-input" placeholder="..."> my homework.
        </div>
    </div>

    {{-- Câu 3: Error Correction --}}
    <div class="pt-q-card">
        <span class="pt-q-num">Câu 3</span>
        <div class="pt-q-text">Find and correct the mistake in the sentence.</div>
        <div class="pt-q-text" style="font-weight:400;">She <u>go</u> to school every day by bus because it is very fast and cheap.</div>
        <input type="text" class="form-control" placeholder="Nhập lại câu đúng hoàn chỉnh...">
    </div>

    {{-- Câu 4: Listening - chọn ảnh --}}
    <div class="pt-q-card">
        <span class="pt-q-num">Câu 4 &middot; Listening</span>
        <button type="button" class="pt-audio-btn"><i class="fas fa-play"></i> Nghe audio</button>
        <div class="pt-q-text">Which one is Laura's brother?</div>
        <div class="pt-img-options">
            <label class="pt-img-option">
                <img src="https://placehold.co/200x140?text=A" alt="A">
                <input type="radio" name="q4"> A
            </label>
            <label class="pt-img-option">
                <img src="https://placehold.co/200x140?text=B" alt="B">
                <input type="radio" name="q4"> B
            </label>
            <label class="pt-img-option">
                <img src="https://placehold.co/200x140?text=C" alt="C">
                <input type="radio" name="q4"> C
            </label>
        </div>
    </div>

    <div class="text-center text-muted mb-4" style="font-size:13px;">
        ... (Đề thật sẽ có đủ 10 câu, đang hiển thị 4 câu mẫu minh hoạ đủ 4 dạng) ...
    </div>

    <div class="pt-demo-actions">
        <form action="{{ route('placement.demo_complete') }}" method="POST">
            @csrf
            <button type="submit" class="pt-demo-btn">
                <i class="fas fa-forward mr-2"></i>Hoàn thành bộ 3 đề (demo) &rarr; Xem trang kết quả
            </button>
        </form>
    </div>
</div>

<script>
    // Demo countdown thuần hiển thị — chưa gắn logic nộp bài tự động khi hết giờ.
    (function () {
        let seconds = 10 * 60;
        const el = document.getElementById('ptCountdown');
        setInterval(function () {
            if (seconds <= 0) return;
            seconds--;
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            el.textContent = m + ':' + s;
        }, 1000);
    })();
</script>
@endsection