@extends('design_1.panel.layouts.panel')

@push('styles_top')
@php
    $attemptCssPath = 'assets/css/ielts-tests/attempt.css';
    $attemptCssVersion = is_file(public_path($attemptCssPath)) ? filemtime(public_path($attemptCssPath)) : time();
    // CSS dùng chung với trang preview của giáo viên — xem header của file.
    $speakingStageCssPath = 'assets/css/ielts-shared/speaking-stage.css';
    $speakingStageCssVersion = is_file(public_path($speakingStageCssPath))
        ? filemtime(public_path($speakingStageCssPath))
        : time();
@endphp
<link rel="stylesheet" href="{{ asset($attemptCssPath) }}?v={{ $attemptCssVersion }}">
<link rel="stylesheet" href="{{ asset($speakingStageCssPath) }}?v={{ $speakingStageCssVersion }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="attempt-page">
    @if(!empty($isMentorPreview) && !empty($mentorPreviewExitUrl))
    <div class="attempt-mentor-banner">
        <span>Đang xem trước với vai trò giáo viên (mentor preview) — đáp án không được lưu thật.</span>
        <a href="{{ $mentorPreviewExitUrl }}" onclick="return confirm('Thoát chế độ xem trước?');">Thoát preview</a>
    </div>
    @endif

    <div id="attemptRoot" class="exam-root">
        <div class="exam-loading">Đang tải phần thi...</div>
    </div>
</div>
@endsection

@push('scripts_bottom')
@include('design_1.panel.ielts_tests.attempt.config')
@php
    $attemptJsBase = 'assets/js/ielts-tests/attempt/';
    $attemptJsVersion = fn (string $file) => asset($attemptJsBase . $file) . '?v=' . (
        is_file(public_path($attemptJsBase . $file)) ? filemtime(public_path($attemptJsBase . $file)) : time()
    );
    $sharedJsBase = 'assets/js/ielts-shared/';
    $sharedJsVersion = fn (string $file) => asset($sharedJsBase . $file) . '?v=' . (
        is_file(public_path($sharedJsBase . $file)) ? filemtime(public_path($sharedJsBase . $file)) : time()
    );
@endphp
{{-- Màn hình Speaking 2 cột — dùng chung với preview, phải load trước renderers.js --}}
<script src="{{ $sharedJsVersion('speaking-stage.js') }}"></script>
<script src="{{ $attemptJsVersion('state.js') }}"></script>
<script src="{{ $attemptJsVersion('answers.js') }}"></script>
<script src="{{ $attemptJsVersion('renderers.js') }}"></script>
<script src="{{ $attemptJsVersion('layout.js') }}"></script>
<script src="{{ $attemptJsVersion('app.js') }}"></script>
@endpush
