@extends('design_1.panel.layouts.panel')

@push('styles_top')
@php
    $previewCssPath = 'assets/css/ielts-tests-manage/preview.css';
    $previewCssVersion = is_file(public_path($previewCssPath)) ? filemtime(public_path($previewCssPath)) : time();
@endphp
<link rel="stylesheet" href="{{ asset($previewCssPath) }}?v={{ $previewCssVersion }}">
@endpush

@section('content')
<div class="preview-page">
    <div class="preview-banner">
        <div class="preview-banner-text">
            <i class="fas fa-eye mr-8"></i>
            <strong>Đang xem trước như học viên.</strong>
            Câu trả lời ở đây chỉ để kiểm tra nội dung đề, không được lưu hay tính điểm thật.
        </div>
        <a href="{{ $backUrl }}" class="preview-banner-back">
            <i class="fas fa-arrow-left mr-5"></i>Quay lại chỉnh sửa
        </a>
    </div>

    <div id="examRoot" class="exam-root">
        <div class="exam-loading">Đang tải đề thi...</div>
    </div>
</div>
@endsection

@push('scripts_bottom')
@include('design_1.panel.ielts_tests_manage.preview.config')
@php
    $previewJsBase = 'assets/js/ielts-tests-manage/preview/';
    $previewJsVersion = function (string $filename) use ($previewJsBase) {
        $fullPath = public_path($previewJsBase . $filename);
        $version = is_file($fullPath) ? filemtime($fullPath) : time();
        return asset($previewJsBase . $filename) . '?v=' . $version;
    };
@endphp
<script src="{{ $previewJsVersion('state.js') }}"></script>
<script src="{{ $previewJsVersion('renderers.js') }}"></script>
<script src="{{ $previewJsVersion('grading.js') }}"></script>
<script src="{{ $previewJsVersion('layout.js') }}"></script>
<script src="{{ $previewJsVersion('app.js') }}"></script>
@endpush
