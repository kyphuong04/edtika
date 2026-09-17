<?php

if (!function_exists('ptRichText')) {
    /**
     * In nội dung Placement Test (câu hỏi, đoạn văn đọc, giải thích đáp án)
     * ra trang học viên.
     *
     * Dữ liệu cũ lưu dạng text thuần -> vẫn escape + nl2br như trước để giữ
     * xuống dòng. Dữ liệu mới soạn bằng Summernote đã là HTML -> in thẳng.
     * Nhờ vậy đề cũ và đề mới cùng hiển thị đúng, không cần migration.
     *
     * LƯU Ý: HTML ở đây phải được lọc bằng HTMLPurifier khi LƯU
     * (PlacementTestController::sanitizeRichText), vì hàm này in raw.
     */
    function ptRichText(?string $value): string
    {
        $value = (string) $value;

        if ($value === '') {
            return '';
        }

        $looksLikeHtml = (bool) preg_match(
            '/<(p|br|div|ul|ol|li|strong|b|em|i|u|s|span|h[1-6])\b[^>]*>/i',
            $value
        );

        return $looksLikeHtml ? $value : nl2br(e($value));
    }
}