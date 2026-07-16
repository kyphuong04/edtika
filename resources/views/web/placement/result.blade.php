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

    <div class="pt-breakdown-card">
        <h4><i class="fas fa-list-ol mr-2"></i>Chi tiết từng đề</h4>
        @foreach(($attempt->test_ids_taken ?? []) as $i => $testId)
            <div class="pt-breakdown-row">
                <span>Đề {{ $i + 1 }}</span>
                <span>
                    <span class="pt-breakdown-level">{{ ($attempt->scores[$i] ?? '—') }}/10 câu đúng</span>
                </span>
            </div>
        @endforeach
    </div>

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