@extends('design_1.panel.layouts.panel')

@php
    $assetV = fn (string $path) => asset($path) . '?v=' . (is_file(public_path($path)) ? filemtime(public_path($path)) : time());
    $isListening = ($attemptMeta['skill'] ?? '') === 'listening';
@endphp

@push('styles_top')
{{-- attempt.css: giao diện từng dạng câu hỏi (dùng chung với trang làm bài).
     ielts-results.css: navbar + cửa sổ "Làm lại" (dùng chung với trang kết quả).
     attempt-review.css: tô đúng/sai cho các dạng câu chưa làm lại giao diện.
     review-shell.css: khung trang chữa bài + T/F/NG + sidebar Answer Help. --}}
<link rel="stylesheet" href="{{ $assetV('assets/css/ielts-tests/attempt.css') }}">
<link rel="stylesheet" href="{{ $assetV('assets/css/ielts-tests/ielts-results.css') }}">
<link rel="stylesheet" href="{{ $assetV('assets/css/ielts-tests/attempt-review.css') }}">
<link rel="stylesheet" href="{{ $assetV('assets/css/ielts-tests/review-shell.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="rvx-page">
    @if(!empty($isMentorPreview) && !empty($mentorPreviewExitUrl))
        <div class="rs-preview-banner rvx-preview-banner">
            <span>Đang xem trước với vai trò học viên — bài làm và bản ghi âm sẽ bị xóa khi thoát xem trước.</span>
            <a href="{{ $mentorPreviewExitUrl }}" onclick="return confirm('Thoát chế độ xem trước?');">Thoát preview</a>
        </div>
    @endif

    @include('design_1.panel.ielts_tests.partials.result_topbar', ['topbar' => $topbar])

    {{-- exam-root is-review: giữ để CSS câu hỏi của trang làm bài và lớp tô
         đúng/sai (attempt-review.css) vẫn áp dụng. --}}
    <div id="attemptRoot" class="exam-root is-review rvx-root">
        <div class="exam-body rvx-body">
            <aside class="rvx-left">
                <nav class="rvx-part-tabs" id="rvxPartTabs" aria-label="{{ $isListening ? 'Chọn Part' : 'Chọn Passage' }}"></nav>
                <div class="exam-context rvx-context" id="rvxContext"></div>
                <div class="rvx-pager" id="rvxPager">
                    <button type="button" class="rvx-pager-arrow" data-step="-1" aria-label="Câu trước">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    </button>
                    <div class="rvx-pager-track" id="rvxPagerTrack" role="list"></div>
                    <button type="button" class="rvx-pager-arrow" data-step="1" aria-label="Câu tiếp theo">
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </aside>

            {{-- Thanh kéo đổi độ rộng 2 cột — cùng giao diện với trang làm bài --}}
            <div class="exam-resizer rvx-resizer" id="rvxResizer" role="separator"
                 aria-orientation="vertical" aria-label="Kéo để đổi độ rộng cột" tabindex="0"></div>

            <section class="rvx-right">
                <div class="exam-questions rvx-questions" id="rvxQuestions">
                    <div class="exam-loading">Đang tải bài chữa...</div>
                </div>
                <div class="rvx-footer">
                    <button type="button" class="rvx-nav-btn rvx-nav-btn--prev" id="rvxPrev">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i> Câu trước
                    </button>
                    <button type="button" class="rvx-nav-btn rvx-nav-btn--next" id="rvxNext">
                        Câu tiếp theo <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
            </section>
        </div>
    </div>
</div>

{{-- ── Sidebar Answer Help (mở bằng icon bóng đèn) ──────────────────── --}}
<div class="rvx-drawer" id="rvxDrawer" hidden>
    <div class="rvx-drawer-backdrop" data-drawer-close></div>
    <aside class="rvx-drawer-panel" role="dialog" aria-modal="true" aria-labelledby="rvxDrawerTitle">
        <header class="rvx-drawer-head">
            <span class="rvx-drawer-icon"><i class="far fa-lightbulb" aria-hidden="true"></i></span>
            <h3 id="rvxDrawerTitle">Giải thích đáp án</h3>
            @if(!empty($reviewMeta['reportUrl']))
                <button type="button" class="rvx-report-btn" id="rvxReportOpen">
                    <i class="far fa-flag" aria-hidden="true"></i> Báo lỗi đáp án
                </button>
            @endif
            <button type="button" class="rvx-drawer-close" data-drawer-close aria-label="Đóng">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </header>
        <div class="rvx-drawer-body" id="rvxDrawerBody"></div>
    </aside>
</div>

{{-- ── Cửa sổ "Báo lỗi đáp án" (mở từ nút trong sidebar) ───────────── --}}
@if(!empty($reviewMeta['reportUrl']))
<div class="rvx-report" id="rvxReport" hidden>
    <div class="rvx-report-backdrop" data-report-close></div>
    <form class="rvx-report-dialog" id="rvxReportForm" role="dialog" aria-modal="true"
          aria-labelledby="rvxReportTitle" novalidate>
        <button type="button" class="rvx-report-x" data-report-close aria-label="Đóng">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>

        <h3 class="rvx-report-title" id="rvxReportTitle">Báo lỗi đáp án</h3>
        <p class="rvx-report-sub">Nếu bạn phát hiện đáp án hoặc phần giải thích chưa chính xác, hãy cho chúng tôi biết để cải thiện nội dung nhé!</p>

        <dl class="rvx-report-info">
            <div><dt>Câu hỏi:</dt><dd id="rvxReportQuestion">—</dd></div>
            <div><dt>Đáp án hiện tại:</dt><dd id="rvxReportAnswer">—</dd></div>
            <div><dt>Dạng bài:</dt><dd id="rvxReportSkill">{{ $reviewMeta['skillLabel'] ?? '' }}</dd></div>
        </dl>

        <label class="rvx-report-label" for="rvxReportMessage">Mô tả chi tiết</label>
        <div class="rvx-report-field">
            <textarea id="rvxReportMessage" name="message" rows="5"
                      maxlength="{{ $reviewMeta['reportMax'] ?? 300 }}"
                      placeholder="Nhập nội dung cụ thể (tối đa {{ $reviewMeta['reportMax'] ?? 300 }} ký tự)..."></textarea>
            <span class="rvx-report-count" id="rvxReportCount">0/{{ $reviewMeta['reportMax'] ?? 300 }}</span>
        </div>
        <p class="rvx-report-msg" id="rvxReportMsg" role="status" aria-live="polite" hidden></p>

        <div class="rvx-report-actions">
            <button type="button" class="rvx-report-cancel" data-report-close>Hủy</button>
            <button type="submit" class="rvx-report-submit" id="rvxReportSubmit" disabled>Gửi báo cáo</button>
        </div>
    </form>
</div>
@endif

@if(!empty($canRetake))
    @include('design_1.panel.ielts_tests.partials.retake_modal', ['retakeUrl' => $retakeUrl])
@endif
@endsection

@push('scripts_bottom')
<script>
    window.ATTEMPT_META = @json($attemptMeta);
    window.REVIEW_META = @json($reviewMeta);
    window.REVIEW_SECTION_DATA = @json($sectionData);
    window.REVIEW_SAVED_ANSWERS = @json((object) $savedAnswers->all());
    window.REVIEW_RESULTS = @json((object) $reviewResults);
    window.ATTEMPT_HIGHLIGHTS = @json($highlights ?? (object) []);
</script>
{{-- Cùng bộ máy dựng câu hỏi với trang làm bài. KHÔNG nạp answers.js / app.js —
     review.js thay thế. highlights.js chỉ để hiển thị lại highlight + note
     (review.js khoá thao tác thêm/sửa/xoá). --}}
<script src="{{ $assetV('assets/js/ielts-shared/blank-utils.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/attempt/state.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/attempt/renderers.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/attempt/layout.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/attempt/highlights.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/attempt/review.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/result-shell.js') }}"></script>
@endpush
