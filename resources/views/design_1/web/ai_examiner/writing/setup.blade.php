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
            <div class="aie-setup">
                <nav class="aie-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('ai_examiner.index') }}">AI Examiner</a>
                    <span class="sep">›</span>
                    <span class="is-current">Edtika Writing AI</span>
                </nav>

                <h1 class="aie-setup__title">AI Examiner<em>|</em>Writing</h1>
                <p class="aie-setup__desc">Tạo đề theo chủ đề yêu thích và thiết lập yêu cầu bài viết.</p>

                <form id="aieWritingForm" novalidate>
                    {{-- 1. Chọn dạng bài --}}
                    <section class="aie-card">
                        <h2 class="aie-card__title">1. Chọn dạng bài</h2>
                        <div class="aie-task-switch" role="tablist">
                            @foreach($tasks as $taskNo => $taskItem)
                                <button type="button" role="tab" data-aie-task="{{ $taskNo }}" class="{{ $activeTask === $taskNo ? 'is-active' : '' }}" aria-selected="{{ $activeTask === $taskNo ? 'true' : 'false' }}">{{ $taskItem['label'] }}</button>
                            @endforeach
                        </div>

                        {{-- 2. Nhập chủ đề --}}
                        <div class="aie-field__head" style="margin-top:22px">
                            <h2 class="aie-card__title" style="margin:0">2. Nhập chủ đề</h2>
                            <button type="button" class="aie-link-btn" id="aieRandomTopic">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 11a8 8 0 0 0-14.3-4.9L4 8M4 4v4h4M4 13a8 8 0 0 0 14.3 4.9L20 16M20 20v-4h-4"/></svg>
                                Chọn đề ngẫu nhiên
                            </button>
                        </div>

                        <div class="aie-field" data-aie-field="prompt">
                            <label class="aie-label" for="aieTopic">Chủ đề <span class="req">*</span></label>
                            <textarea id="aieTopic" name="prompt" class="aie-textarea aie-textarea--topic" style="margin-top:8px" placeholder="Nhập chủ đề bạn muốn chấm (hoặc dán đề bài vào đây)..."></textarea>
                            <div class="aie-error">Vui lòng nhập đề bài.</div>
                        </div>

                        <div class="aie-field" data-aie-field="chart" id="aieChartField">
                            <label class="aie-label" style="display:block;margin:12px 0 8px">Biểu đồ / bảng / bản đồ / hình ảnh <span class="req">*</span></label>
                            <div class="aie-dropzone" id="aieChartZone" tabindex="0" role="button" aria-label="Tải ảnh biểu đồ">
                                <input type="file" id="aieChartInput" accept="image/*">
                                <div class="aie-dropzone__empty">
                                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="10" width="34" height="28" rx="4"/><circle cx="16" cy="19" r="3"/><path d="M6 33l10-9 8 7 6-5 10 8"/><circle cx="42" cy="38" r="9" fill="#fff"/><path d="M42 33v10M37 38h10"/></svg>
                                    <div>Tải ảnh hình hoặc kéo thả vào đây</div>
                                </div>
                                <div class="aie-dropzone__preview">
                                    <img id="aieChartPreview" alt="Biểu đồ đề bài">
                                    <button type="button" class="aie-dropzone__remove" id="aieChartRemove" aria-label="Xóa ảnh">&times;</button>
                                </div>
                            </div>
                            <div class="aie-error">Vui lòng tải lên biểu đồ / bảng / bản đồ / hình ảnh của đề bài.</div>
                        </div>
                    </section>

                    {{-- 3. Bài làm của bạn --}}
                    <section class="aie-card">
                        <h2 class="aie-card__title">3. Bài làm của bạn</h2>
                        <div class="aie-field" data-aie-field="essay">
                            <label class="aie-label" for="aieEssay">Bài viết <span class="req">*</span></label>
                            <textarea id="aieEssay" name="essay" class="aie-textarea aie-textarea--essay" style="margin-top:8px"></textarea>
                            <div class="aie-error">Vui lòng nhập bài viết.</div>
                            <div class="aie-wordcount" id="aieWordCount"><b>0</b> từ · Giới hạn tối thiểu: <span id="aieMinWords">150</span> từ</div>
                        </div>

                        <div class="aie-setup__actions">
                            <button type="button" class="aie-btn aie-btn--outline" id="aieHandwritten">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4M7 9l5-5 5 5M4 15v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4"/></svg>
                                Tải bài viết tay
                            </button>
                            <input type="file" id="aieHandInput" accept="image/*" hidden>
                            <button type="submit" class="aie-btn aie-btn--primary" id="aieSubmit">Chấm chữa <span aria-hidden="true">→</span></button>
                        </div>

                        <div class="aie-setup__demo">
                            <span class="aie-mock-note">Bản mẫu: kết quả chấm là dữ liệu giả</span>
                            <button type="button" class="aie-link-btn" id="aieFillDemo">Điền đề + bài demo</button>
                        </div>
                    </section>
                </form>

                @include('design_1.web.ai_examiner.partials.practice_banner')
            </div>
        </div>
    </main>

    @include('design_1.web.ai_examiner.partials.footer')

    @include('design_1.web.ai_examiner.partials.login_modal')

    {{-- Cảnh báo bài viết chưa đủ số từ tối thiểu --}}
    <div class="aie-modal" id="aieShortModal" aria-hidden="true">
        <div class="aie-modal__dialog" role="dialog" aria-modal="true">
            <button type="button" class="aie-modal__close" data-aie-close aria-label="Đóng">&times;</button>
            <h3>Bài viết chưa đủ số từ</h3>
            <p id="aieShortText">Bài của bạn chưa đạt số từ tối thiểu. Bài thi thật sẽ bị trừ điểm. Bạn vẫn muốn chấm?</p>
            <div class="aie-modal__actions">
                <button type="button" class="aie-btn aie-btn--soft" data-aie-close>Viết tiếp</button>
                <button type="button" class="aie-btn aie-btn--primary" id="aieShortConfirm">Vẫn chấm chữa</button>
            </div>
        </div>
    </div>

    <div class="aie-overlay" id="aieOcrOverlay"><div class="aie-spinner"></div><div>Đang nhận dạng bài viết tay...</div></div>
    <div class="aie-toast" id="aieToast" role="status"></div>
</div>
@endsection

@push('scripts_bottom')
    <script>
        window.AIE_SETUP = {
            activeTask: {{ $activeTask }},
            tasks: @json($tasks),
            demo: @json($demo),
            isGuest: @json(auth()->guest()),
            resume: @json($resume),
            csrf: @json(csrf_token()),
            urls: {
                randomTopic: @json(route('ai_examiner.writing.random_topic')),
                ocr: @json(route('ai_examiner.writing.ocr')),
                submit: @json(route('ai_examiner.writing.submit')),
                setup: @json(route('ai_examiner.writing'))
            }
        };
    </script>
    <script src="/assets/design_1/js/ai-examiner/common.js?v=1.0"></script>
    <script src="/assets/design_1/js/ai-examiner/setup.js?v=1.0"></script>
@endpush
