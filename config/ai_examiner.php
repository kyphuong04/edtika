<?php

/*
|--------------------------------------------------------------------------
| EDTIKA AI Examiner (Writing / Speaking) — cấu hình bản mẫu UI
|--------------------------------------------------------------------------
| Đây là cấu hình cho BẢN MẪU giao diện. Phần chấm hiện dùng dữ liệu giả
| (App\Services\AiExaminer\MockWritingGrader). Khi team AI bàn giao service
| thật, chỉ cần viết class mới implement WritingGraderInterface và đổi
| 'driver' bên dưới — controller và giao diện giữ nguyên.
*/

return [

    // 'mock' = dữ liệu giả. Sau này: 'http' (gọi AI service của team AI).
    'driver' => env('AI_EXAMINER_DRIVER', 'mock'),

    // Endpoint AI service thật (chưa dùng ở bản mẫu)
    'service_url' => env('AI_EXAMINER_SERVICE_URL'),
    'service_key' => env('AI_EXAMINER_SERVICE_KEY'),

    // Mock: số giây giả lập để chấm xong MỖI tiêu chí (màn "Đang chấm bài của bạn...")
    'mock_seconds_per_criterion' => 2,

    // Video demo trên trang giới thiệu (theo yêu cầu: do Manager quản lý).
    // Bản mẫu đọc từ .env; sau này chuyển vào trang quản trị.
    'intro_video_url' => env('AI_EXAMINER_INTRO_VIDEO_URL'),

    // Có bật nút "Giáo viên chấm" hay không (prototype V4 của khách có, ảnh mẫu chưa chốt)
    'teacher_grading_enabled' => false,

    'writing' => [
        'tasks' => [
            1 => [
                'label' => 'Task 1 (biểu đồ, bảng, bản đồ)',
                'short' => 'Writing Task 1',
                'min_words' => 150,
                'has_chart' => true,
                'placeholder' => 'Viết bài Task 1 của bạn tại đây...',
            ],
            2 => [
                'label' => 'Task 2 (bài luận)',
                'short' => 'Writing Task 2',
                'min_words' => 250,
                'has_chart' => false,
                'placeholder' => 'Viết bài Task 2 của bạn tại đây...',
            ],
        ],

        // Mức bài mẫu (dropdown "Band 7.0+")
        'sample_bands' => ['6.5', '7.0', '8.0'],

        /*
         | 4 tiêu chí lớn + tiêu chí con. Đây là "khung cứng" cần CHỐT với team AI:
         | AI phải trả kết quả đúng các key này.
         | Ghi chú: danh sách tiêu chí con của LR trong ảnh mẫu và prototype V4 của khách
         | đang khác nhau — tạm dùng theo ảnh mẫu.
         */
        'criteria' => [
            1 => [
                'ta' => ['code' => 'TA', 'name' => 'Task Achievement', 'icon' => 'chart', 'subs' => [
                    'task_fulfillment' => 'Task Fulfillment',
                    'overview_quality' => 'Overview Quality',
                    'key_features' => 'Key feature selection & coverage',
                    'data_accuracy' => 'Accuracy of data / information',
                ]],
                'cc' => ['code' => 'CC', 'name' => 'Coherence & Cohesion', 'icon' => 'link', 'subs' => [
                    'paragraph_organization' => 'Paragraph Organization',
                    'logical_progression' => 'Logical Progression',
                    'cohesive_devices' => 'Cohesive Devices',
                    'referencing' => 'Referencing & Substitution',
                ]],
                'gra' => ['code' => 'GRA', 'name' => 'Grammatical Range & Accuracy', 'icon' => 'doc', 'subs' => [
                    'grammar_accuracy' => 'Grammar Accuracy',
                    'sentence_variety' => 'Sentence Variety',
                    'grammatical_complexity' => 'Grammatical Complexity',
                    'error_impact' => 'Error Impact',
                ]],
                'lr' => ['code' => 'LR', 'name' => 'Lexical Resource', 'icon' => 'book', 'subs' => [
                    'vocabulary_range' => 'Vocabulary Range & Sophistication',
                    'lexical_precision' => 'Lexical Precision',
                    'collocation' => 'Collocation & Naturalness',
                    'lexical_accuracy' => 'Lexical Accuracy',
                ]],
            ],
            2 => [
                'tr' => ['code' => 'TR', 'name' => 'Task Response', 'icon' => 'chart', 'subs' => [
                    'task_fulfillment' => 'Task Fulfillment',
                    'position_clarity' => 'Position Clarity & Consistency',
                    'idea_development' => 'Idea Development',
                    'relevance' => 'Relevance',
                ]],
                'cc' => ['code' => 'CC', 'name' => 'Coherence & Cohesion', 'icon' => 'link', 'subs' => [
                    'paragraph_organization' => 'Paragraph Organization',
                    'logical_progression' => 'Logical Progression',
                    'cohesive_devices' => 'Cohesive Devices',
                    'referencing' => 'Referencing & Substitution',
                ]],
                'gra' => ['code' => 'GRA', 'name' => 'Grammatical Range & Accuracy', 'icon' => 'doc', 'subs' => [
                    'grammar_accuracy' => 'Grammar Accuracy',
                    'sentence_variety' => 'Sentence Variety',
                    'grammatical_complexity' => 'Grammatical Complexity',
                    'error_impact' => 'Error Impact',
                ]],
                'lr' => ['code' => 'LR', 'name' => 'Lexical Resource', 'icon' => 'book', 'subs' => [
                    'vocabulary_range' => 'Vocabulary Range & Sophistication',
                    'lexical_precision' => 'Lexical Precision',
                    'collocation' => 'Collocation & Naturalness',
                    'lexical_accuracy' => 'Lexical Accuracy',
                ]],
            ],
        ],
    ],
];
