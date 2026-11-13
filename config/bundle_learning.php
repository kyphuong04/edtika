<?php

/**
 * Cấu hình học tập cho khóa học (bundle) - GĐ1.
 */
return [
    // Xem tới tỉ lệ này của video thì tính là "đã xem" (0.9 = 90%)
    'video_complete_ratio' => env('BUNDLE_VIDEO_COMPLETE_RATIO', 0.9),

    // Kỹ năng dùng cho bộ lọc "Khóa học của tôi"
    'skills' => ['listening', 'reading', 'writing', 'speaking'],

    // Ngưỡng thời lượng (giây) để chia bộ lọc "Dưới 15 phút / Từ 15 phút"
    'short_video_max_seconds' => 15 * 60,

    // GĐ2 - Form tạo khóa học
    // Hiện lại các tùy chọn giá cũ (thời hạn truy cập, subscribe, gói giá) - design mới không dùng
    'show_legacy_pricing' => env('BUNDLE_SHOW_LEGACY_PRICING', false),

    // Hiện mục FAQ ở bước 5 (khách chưa chốt bỏ hay giữ)
    'show_faq' => env('BUNDLE_SHOW_FAQ', true),

    // Biểu tượng cho quà tặng khóa học
    'gift_icons' => ['gift', 'book', 'sparkles', 'pen', 'box', 'cup'],

    // GĐ3 - Học viên học
    // Dùng trang "Khóa học của tôi" mới cho bundle (false = quay lại giao diện module cũ)
    'new_learning_ui' => env('BUNDLE_NEW_LEARNING_UI', true),

    // Video phát được bằng player (tải lên, YouTube, Vimeo) chỉ được đánh dấu "đã xem" tự động, không cho tự tick
    'auto_video_completion' => env('BUNDLE_AUTO_VIDEO_COMPLETION', true),

    // GĐ5 - Trang danh sách / chi tiết khóa học theo design mới (false = giao diện cũ)
    'new_storefront' => env('BUNDLE_NEW_STOREFRONT', true),

    // Giới hạn số dòng để tránh form quá dài
    'max_gifts' => 10,
    'max_highlights' => 12,
];
