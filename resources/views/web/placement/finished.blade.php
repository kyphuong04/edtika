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
.pt-mini-header { display:flex; align-items:center; justify-content:space-between; max-width:1000px; margin:0 auto 20px; padding:16px 20px 0; }
.pt-mini-header .pt-brand { font-size:26px; font-weight:900; color:#511D99; text-decoration:none; }

.pt-finished-wrap { max-width:600px; margin:80px auto 100px; padding:0 20px; text-align:center; }
.pt-finished-icon { width:88px; height:88px; border-radius:50%; background:#f5f3ff; color:#511D99; font-size:38px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:24px; }
.pt-finished-title { font-size:26px; font-weight:900; color:#111827; margin-bottom:12px; }
.pt-finished-desc { font-size:15px; color:#6b7280; line-height:1.6; margin-bottom:32px; }
.pt-finished-btn { display:inline-block; width:100%; padding:14px; border-radius:14px; border:none; font-weight:700; font-size:16px; cursor:pointer; background:#511D99; color:#fff; box-shadow:0 8px 20px rgba(81,29,153,.3); text-decoration:none; }
.pt-finished-btn:hover { background:#3f1677; color:#fff; }
.pt-finished-secondary { display:block; margin-top:16px; font-size:14px; color:#9ca3af; }
.pt-finished-secondary a { color:#511D99; font-weight:600; text-decoration:underline; }
</style>
@endpush

@section('content')
<div class="pt-mini-header">
    <a href="/" class="pt-brand">EDTIKA</a>
</div>

<div class="pt-finished-wrap">
    <div class="pt-finished-icon">
        <i class="fas fa-check"></i>
    </div>
    <h2 class="pt-finished-title">Bạn đã hoàn thành bài Placement Test!</h2>
    <p class="pt-finished-desc">
        Đăng nhập (hoặc tạo tài khoản miễn phí) để xem kết quả — bao gồm trình độ
        CEFR của bạn và lưu lại kết quả này vào hồ sơ học tập.
    </p>

    <!-- <a href="{{ route('placement.request_login') }}" class="pt-finished-btn">
        <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập để xem kết quả
    </a>

    <span class="pt-finished-secondary">
        Chưa có tài khoản?
        <a href="{{ route('placement.request_login', ['tab' => 'register']) }}">Đăng ký ngay</a>
    </span> -->

    <a href="#" class="pt-finished-btn" data-open-auth-modal="true">
        <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập để xem kết quả
    </a>

    <span class="pt-finished-secondary">
        Chưa có tài khoản?
        <a href="#" id="ptRegisterTrigger">Đăng ký ngay</a>
    </span>
</div>
<script>
document.getElementById('ptRegisterTrigger').addEventListener('click', function (e) {
    e.preventDefault();

    var loginTrigger = document.querySelector('[data-open-auth-modal="true"]');
    if (loginTrigger) {
        loginTrigger.click(); // mở modal (layout tự set tab login)
    }

    var registerTabBtn = document.querySelector('[data-auth-tab="register"]');
    if (registerTabBtn) {
        registerTabBtn.click(); // chuyển sang tab đăng ký
    }
});
</script>
@endsection