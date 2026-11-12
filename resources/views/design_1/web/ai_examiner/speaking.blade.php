@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
@endphp

@push('styles_top')
    <link rel="stylesheet" href="/assets/design_1/css/ai-examiner.css?v=1.0">
@endpush

@section('content')
<div class="aie-page">
    @include('design_1.web.ai_examiner.partials.header')

    <main class="aie-main">
        <div class="aie-container">
            <nav class="aie-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('ai_examiner.index') }}">AI Examiner</a>
                <span class="sep">›</span>
                <span class="is-current">Edtika Speaking AI</span>
            </nav>

            <section class="aie-coming">
                <img src="/assets/design_1/img/ai-examiner/mascot.png" alt="Edti">
                <h1>AI Examiner | Speaking</h1>
                <p>Tính năng chấm chữa Speaking đang được phát triển (dự kiến kết hợp chấm phát âm – độ lưu loát và chấm nội dung bài nói). Khách hàng chưa gửi thiết kế cho phần này.</p>
                <span class="aie-mock-note">Bản mẫu — màn hình giữ chỗ</span>
                <div style="margin-top:26px">
                    <a href="{{ route('ai_examiner.writing') }}" class="aie-btn aie-btn--primary">Thử chấm Writing <span aria-hidden="true">→</span></a>
                </div>
            </section>
        </div>
    </main>

    @include('design_1.web.ai_examiner.partials.footer')
</div>
@endsection
