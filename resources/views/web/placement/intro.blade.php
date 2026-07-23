@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $dontShowCookieSecurity = true;
@endphp

@push('styles_top')
<style>
.pt-mini-header { display:flex; align-items:center; justify-content:space-between; max-width:1000px; margin:0 auto 20px; padding:16px 20px 0; }
.pt-mini-header .pt-brand { font-size:26px; font-weight:900; color:#511D99; text-decoration:none; }

.pt-intro-wrap { max-width:1000px; margin:0 auto; padding:0 20px 80px; }
.pt-intro-card { background:#fff; border-radius:24px; border:1px solid #e5e7eb; padding:48px; display:grid; grid-template-columns:1fr 1fr; gap:40px; align-items:center; }
.pt-intro-title { font-size:36px; font-weight:900; color:#111827; margin-bottom:24px; }
.pt-intro-bullet { display:flex; gap:12px; margin-bottom:16px; font-size:16px; line-height:1.5; color:#374151; }
.pt-intro-bullet i { color:#511D99; font-size:18px; margin-top:3px; flex-shrink:0; }
.pt-intro-bullet strong { color:#111827; }
.pt-intro-visual { background:#f5f3ff; border-radius:20px; padding:32px; text-align:center; }
.pt-intro-visual i { font-size:96px; color:#511D99; opacity:.85; }
.pt-start-btn { margin-top:28px; background:#511D99; color:#fff; border:none; border-radius:14px; padding:16px 40px; font-weight:700; font-size:16px; cursor:pointer; box-shadow:0 8px 20px rgba(81,29,153,.3); }
.pt-start-btn:hover { opacity:.92; }

@media (max-width: 767px) {
    .pt-intro-card { grid-template-columns:1fr; padding:28px; }
}
</style>
@endpush

@section('content')
<div class="pt-mini-header">
    <a href="/" class="pt-brand">EDTIKA</a>
</div>

<div class="pt-intro-wrap">
    <div class="pt-intro-card">
        <div>
            <h1 class="pt-intro-title">Bài Test Trình Độ IELTS</h1>

            <div class="pt-intro-bullet">
                <i class="fas fa-clock"></i>
                <div><strong>Chỉ khoảng 10 phút</strong> cho phần trắc nghiệm, tối đa 3 đề nối tiếp nhau.</div>
            </div>
            <div class="pt-intro-bullet">
                <i class="fas fa-bullseye"></i>
                <div>Hệ thống <strong>tự động điều chỉnh độ khó</strong> theo kết quả từng đề để đo đúng trình độ hiện tại của bạn.</div>
            </div>
            <div class="pt-intro-bullet">
                <i class="fas fa-chart-line"></i>
                <div>Kết quả quy đổi theo <strong>thang điểm CEFR</strong> (A1 → B2+), dùng để xây lộ trình học phù hợp cho bạn.</div>
            </div>
            <div class="pt-intro-bullet">
                <i class="fas fa-microphone-alt"></i>
                <div>Sau phần trắc nghiệm có thêm <strong>1 câu nói ngắn</strong> — không chấm điểm, chỉ để giáo viên hiểu rõ hơn và tư vấn cho bạn.</div>
            </div>

            @if($isResuming)
                <div class="pt-intro-bullet" style="background:#fef3c7;border-radius:10px;padding:12px 16px;">
                    <i class="fas fa-info-circle" style="color:#b45309;"></i>
                    <div>Bạn đang có 1 bài test dang dở — bấm bên dưới để <strong>tiếp tục</strong> đúng chỗ bạn đang làm, không mất tiến độ.</div>
                </div>
            @endif

            <a href="{{ route('placement.mic_check') }}" class="pt-start-btn" style="display:inline-flex;align-items:center;text-decoration:none;">
                <i class="fas fa-play mr-2"></i>{{ $isResuming ? 'Tiếp tục bài test' : 'Bắt đầu bài test' }}
            </a>
        </div>

        <div class="pt-intro-visual">
            <i class="fas fa-graduation-cap"></i>
        </div>
    </div>
</div>
@endsection