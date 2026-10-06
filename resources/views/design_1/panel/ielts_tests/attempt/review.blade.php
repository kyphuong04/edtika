@extends('design_1.panel.layouts.panel')

@push('styles_top')
@php
    $assetV = fn (string $path) => asset($path) . '?v=' . (is_file(public_path($path)) ? filemtime(public_path($path)) : time());
@endphp
<link rel="stylesheet" href="{{ $assetV('assets/css/ielts-tests/attempt.css') }}">
<link rel="stylesheet" href="{{ $assetV('assets/css/ielts-tests/attempt-review.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="attempt-page">
    @if(!empty($isMentorPreview) && !empty($mentorPreviewExitUrl))
    <div class="attempt-mentor-banner">
        <span>Đang xem trước với vai trò học viên — bài làm và bản ghi âm sẽ bị xóa khi thoát xem trước.</span>
        <a href="{{ $mentorPreviewExitUrl }}" onclick="return confirm('Thoát chế độ xem trước?');">Thoát preview</a>
    </div>
    @endif

    <div id="attemptRoot" class="exam-root">
        <div class="exam-loading">Đang tải bài chữa...</div>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
    window.ATTEMPT_META = @json($attemptMeta);
    window.REVIEW_META = @json($reviewMeta);
    window.REVIEW_SECTION_DATA = @json($sectionData);
    window.REVIEW_SAVED_ANSWERS = @json((object) $savedAnswers->all());
    window.REVIEW_RESULTS = @json((object) $reviewResults);
</script>
@php
    $assetV = fn (string $path) => asset($path) . '?v=' . (is_file(public_path($path)) ? filemtime(public_path($path)) : time());
@endphp
{{-- Cùng bộ máy UI với trang làm bài. KHÔNG nạp answers.js / highlights.js /
     app.js — review.js thay thế cả 3. --}}
<script src="{{ $assetV('assets/js/ielts-shared/blank-utils.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/attempt/state.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/attempt/renderers.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/attempt/layout.js') }}"></script>
<script src="{{ $assetV('assets/js/ielts-tests/attempt/review.js') }}"></script>
@endpush