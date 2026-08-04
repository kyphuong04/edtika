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

    // Thứ tự skill CỐ ĐỊNH dùng để đánh số câu hỏi liên tục toàn bài.
    // PHẢI khớp với question_number thật đã lưu DB (xem
    // IeltsTestInlineController::SKILL_ORDER / reorderSectionsBySkill()).
    // Truyền từ server để state.js không phải tự giữ 1 bản literal riêng
    // dễ bị trôi lệch theo thời gian.
    $previewSkillOrder = $skillOrder ?? ['listening', 'reading', 'writing', 'speaking', 'grammar', 'vocabulary'];
@endphp
<script>
    window.PREVIEW_DATA = @json($previewData);
    window.PREVIEW_TEST_META = @json($previewTestMeta);
    window.PREVIEW_BACK_URL = @json($backUrl);
    window.PREVIEW_SKILL_ORDER = @json($previewSkillOrder);
</script>