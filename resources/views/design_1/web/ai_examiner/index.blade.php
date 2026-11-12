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

            {{-- B2: Màn hình giới thiệu Edtika AI Examiner (Writing & Speaking) --}}
            <section class="aie-intro__hero">
                <h1 class="aie-intro__title">Chấm chữa IELTS<br>Writing &amp; Speaking với<br><span>Edtika AI Examiner</span></h1>
                <p class="aie-intro__sub">Công cụ hỗ trợ chấm chữa Writing &amp; Speaking đắc lực cho giáo viên IELTS và học viên.</p>

                <div class="aie-intro__ctas">
                    <a href="{{ route('ai_examiner.writing') }}" class="aie-btn aie-btn--primary">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        Chấm Writing <span aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('ai_examiner.speaking') }}" class="aie-btn aie-btn--outline">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="9" y="3" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3"/></svg>
                        Chấm Speaking <span aria-hidden="true">→</span>
                    </a>
                </div>

                {{-- Video demo — theo yêu cầu do Manager quản lý (bản mẫu đọc từ config ai_examiner.intro_video_url) --}}
                <div class="aie-intro__video">
                    @if(!empty($introVideoUrl))
                        @if(preg_match('/youtube\.com|youtu\.be|vimeo\.com/i', $introVideoUrl))
                            <iframe src="{{ $introVideoUrl }}" title="Edtika AI Examiner demo" allowfullscreen></iframe>
                        @else
                            <video src="{{ $introVideoUrl }}" controls preload="metadata"></video>
                        @endif
                    @else
                        <div class="aie-intro__video-mock">
                            <div class="aie-intro__fake" aria-hidden="true">
                                <div class="aie-intro__fake-side"><i></i><i></i><i></i><i></i><i></i></div>
                                <div class="aie-intro__fake-main">
                                    <b>AI Examiner | Writing</b>
                                    <i class="is-primary"></i><i></i><i style="height:70px"></i>
                                </div>
                            </div>
                            <button type="button" class="aie-intro__play" aria-label="Video demo">
                                <svg width="34" height="34" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            </button>
                            <span class="aie-intro__video-caption">Video demo — Manager sẽ cập nhật trong trang quản trị</span>
                        </div>
                    @endif
                </div>
            </section>

            {{-- 3 giá trị cốt lõi --}}
            <section class="aie-intro__features">
                <span class="aie-intro__eyebrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.2 7.8L22 12l-7.8 2.2L12 22l-2.2-7.8L2 12l7.8-2.2z"/></svg>
                    Tính năng nổi bật
                </span>
                <h2>Trải nghiệm chấm chữa IELTS <span>nhanh chóng, chính xác và thông minh</span></h2>

                <div class="aie-intro__cards">
                    <article class="aie-intro__card">
                        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 16c7-3 14-3 22 2 8-5 15-5 22-2v28c-7-3-14-3-22 2-8-5-15-5-22-2z"/><path d="M28 18v28"/><circle cx="40" cy="10" r="5"/><path d="M40 1v2M47 4l-1.5 1.5M33 4l1.5 1.5"/></svg>
                        <h3>Kết quả nhanh chóng</h3>
                        <p>Trả kết quả tức thì sau khi nộp, thay thế hoàn toàn trải nghiệm chờ đợi của các mô hình chấm chữa truyền thống.</p>
                    </article>
                    <article class="aie-intro__card">
                        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="10" width="30" height="38" rx="4"/><path d="M14 20h4M22 20h10M14 29h4M22 29h10M14 38h4M22 38h10"/><rect x="30" y="4" width="20" height="12" rx="2" fill="currentColor"/><text x="40" y="13" font-size="7" fill="#fff" stroke="none" text-anchor="middle" font-weight="700">IELTS</text></svg>
                        <h3>Band điểm chuẩn xác</h3>
                        <p>Framework AI được nghiên cứu và huấn luyện bởi đội ngũ Edtika R&amp;D để chấm điểm sát thực tế theo 4 tiêu chí bài thi IELTS.</p>
                    </article>
                    <article class="aie-intro__card">
                        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 48h44"/><rect x="10" y="34" width="7" height="14"/><rect x="22" y="26" width="7" height="22"/><rect x="34" y="18" width="7" height="30"/><path d="M8 26l12-10 9 6 17-14M40 8h6v6"/></svg>
                        <h3>Bứt phá band điểm</h3>
                        <p>Sửa lỗi chi tiết, khắc phục các điểm mù trong tư duy làm bài và đề xuất hướng cải thiện giúp người học nâng band hiệu quả.</p>
                    </article>
                </div>
            </section>
        </div>
    </main>

    @include('design_1.web.ai_examiner.partials.footer')
</div>
@endsection
