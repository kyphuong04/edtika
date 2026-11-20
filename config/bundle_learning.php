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

    // Trang "Khóa học của tôi" (/panel/courses/purchases) theo design mới (false = danh sách cũ của theme)
    'new_my_courses' => env('BUNDLE_NEW_MY_COURSES', true),

    // GĐ5 - Trang danh sách / chi tiết khóa học theo design mới (false = giao diện cũ)
    'new_storefront' => env('BUNDLE_NEW_STOREFRONT', true),

    // GĐ6 - Lesson theo design: Pre Lesson / Bài Tập / Post Lesson
    // Pre Lesson: phải đánh dấu đã thuộc tối thiểu tỉ lệ này (%) số từ của bộ từ thì mới mở video
    'pre_lesson_pass_percent' => env('BUNDLE_PRE_LESSON_PASS_PERCENT', 80),

    // Bài tập (assignment) kiểu cũ do giáo viên chấm: không còn bắt buộc để hoàn thành lesson
    // (Post Lesson giờ là đề "Bài tập khóa học" chấm tự động). true = quay lại cách cũ.
    'assignments_required' => env('BUNDLE_ASSIGNMENTS_REQUIRED', false),

    // Trang "Chấm bài" (GĐ4) cho giáo viên: ẩn khi Post Lesson chấm tự động
    'teacher_grading_enabled' => env('BUNDLE_TEACHER_GRADING_ENABLED', false),

    // GĐ7 - Quiz làm ngay trên trang học
    // Video dừng ở mốc Mini Quiz và không xem tiếp được cho tới khi làm đạt
    // (không chặn khi bài đang chờ giáo viên chấm hoặc đã hết lượt làm). false = cho phép "Để sau".
    'mini_quiz_blocks_video' => env('BUNDLE_MINI_QUIZ_BLOCKS_VIDEO', true),

    // Giới hạn số dòng để tránh form quá dài
    'max_gifts' => 10,
    'max_highlights' => 12,
];
