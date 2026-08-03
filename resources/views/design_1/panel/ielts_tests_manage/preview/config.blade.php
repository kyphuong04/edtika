{{--
    Nơi DUY NHẤT chứa Blade trong toàn bộ trang Preview.
    Mọi file .js khác (state.js, renderers.js, layout.js, app.js) là JS thuần.
--}}
@php
    $previewTestMeta = [
        'id' => $test->id,
        'title' => $test->title,
        'type' => $test->type,
        'format' => $test->format,
    ];
@endphp
<script>
    window.PREVIEW_DATA = @json($previewData);
    window.PREVIEW_TEST_META = @json($previewTestMeta);
    window.PREVIEW_BACK_URL = @json($backUrl);
</script>