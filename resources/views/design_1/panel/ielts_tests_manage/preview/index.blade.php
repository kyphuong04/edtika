@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="{{ asset('assets/css/ielts-tests-manage/preview.css') }}">
@endpush

@section('content')
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
@endsection

@push('scripts_bottom')
@include('design_1.panel.ielts_tests_manage.preview.config')
{{-- Thứ tự nạp QUAN TRỌNG: state -> renderers -> grading (layout.js cần
     ExamGrading để tô màu navigator) -> layout -> app (bootstrap, chạy sau cùng) --}}
<script src="{{ asset('assets/js/ielts-tests-manage/preview/state.js') }}"></script>
<script src="{{ asset('assets/js/ielts-tests-manage/preview/renderers.js') }}"></script>
<script src="{{ asset('assets/js/ielts-tests-manage/preview/grading.js') }}"></script>
<script src="{{ asset('assets/js/ielts-tests-manage/preview/layout.js') }}"></script>
<script src="{{ asset('assets/js/ielts-tests-manage/preview/app.js') }}"></script>
@endpush
