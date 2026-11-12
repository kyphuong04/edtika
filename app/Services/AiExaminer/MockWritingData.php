<?php

namespace App\Services\AiExaminer;

/**
 * Dữ liệu GIẢ cho bản mẫu AI Examiner Writing.
 * Nội dung lấy theo ảnh mẫu khách gửi (WEBIE x EDTIKA – AI WRITING).
 * Xóa / thay thế toàn bộ khi có AI service thật.
 */
class MockWritingData
{
    public static function topics(int $task): array
    {
        if ($task === 1) {
            return [
                [
                    'prompt' => 'The line graph illustrates the number of students from the UK, the US and Australia who studied at universities in other countries between 2000 and 2020. Summarise the information by selecting and reporting the main features, and make comparisons where relevant.',
                    'chart_url' => '/assets/design_1/img/ai-examiner/sample-task1-chart.svg',
                ],
                [
                    'prompt' => 'The chart below shows the percentage of people in different age groups who used social media in 2010, 2015 and 2020. Summarise the information by selecting and reporting the main features, and make comparisons where relevant.',
                    'chart_url' => null,
                ],
                [
                    'prompt' => 'The table below gives information about the average household spending on five categories in three countries in 2019. Summarise the information by selecting and reporting the main features, and make comparisons where relevant.',
                    'chart_url' => null,
                ],
            ];
        }

        return [
            ['prompt' => 'Some people think that university education should be free for everyone, regardless of their financial situation. To what extent do you agree or disagree?', 'chart_url' => null],
            ['prompt' => 'Some people believe that online learning can completely replace traditional classroom teaching, while others think that face-to-face lessons remain essential. Discuss both views and give your own opinion.', 'chart_url' => null],
            ['prompt' => 'In many cities, the number of private cars is increasing. What problems does this cause, and what measures could be taken to solve them?', 'chart_url' => null],
        ];
    }

    /** Bài làm demo (nút "Điền bài demo" ở màn làm bài) — khớp với các anchor lỗi bên dưới. */
    public static function demoEssay(int $task): string
    {
        if ($task === 1) {
            return "The line graph illustrates the number of students from the UK, the US and Australia who studied at universities in other countries between 2000 and 2020.\n\n"
                . "Overall, the number of UK and Australian students experienced a slight decline over the period, while the figure for the US experienced a significant increase.\n\n"
                . "In 2000, around 50,000 UK students studied abroad, compared to 420,000 from the US and 23,000 from Australia. By the end of the period, the number of US students increased to approximately 320,000, while the figure for UK and Australian figures rose to around 120,000 and 160,000 respectively.\n\n"
                . "In contrast, the number of US students remained relatively stable throughout the period, fluctuating slightly above and below around 350,000. By 2020, the US still had the largest number of students studying overseas, while the UK and Australia had much smaller figures. This shows that studying abroad became more and more popular among young people in the UK and Australia during these two decades.";
        }

        return "University education is very important for young people because it can help them get a better job in the future. Some people believe that university should be free for all students, no matter if they are rich or poor. I partly agree with this idea because free education can help many people, but I think the government should not pay everything for everyone.\n\n"
            . "Firstly, free university education can give more chances for poor students. Many students from low-income families cannot afford university fees, so they may stop their education after high school. If university is free, they can continue studying and have more opportunities to find good jobs. This can also be good for the country because there will be more educated workers. For example, a country with many graduates can develop new industries faster. However, this does not mean that all students need free education.\n\n"
            . "Secondly, rich families can already pay university fees, so it is not necessary for the government to spend money on them. The government has many other important areas to spend money, such as hospitals, roads and schools. If the government pays for every student, it may cost too much money and this can create problems for the national budget. Therefore, students from rich families should pay by themselves, while poor students should receive more support.\n\n"
            . "In conclusion, I agree that university education should be free for students who cannot afford it, but I do not think it should be completely free for everyone. The government should help poor students more because this is a fairer and more practical way to use public money.";
    }

    /** Văn bản giả lập khi "Tải bài viết tay" (OCR). */
    public static function ocrText(int $task): string
    {
        return self::demoEssay($task);
    }

    /**
     * Feedback theo tiêu chí. Cấu trúc: [criterionKey => [band, summary, priorities, tips, subs => [subKey => [...]]]]
     * Issue 'anchor' = đoạn trích trong bài demo để highlight.
     */
    public static function feedback(int $task): array
    {
        $priorities = $task === 1
            ? ['Từ vựng học thuật (ngữ cảnh – mức 6 → lên 7.0)', 'Hướng liên kết logic', 'Bài đáp ứng đúng yêu cầu đề']
            : ['Phát triển ý tưởng và lập luận sâu hơn', 'Sử dụng từ vựng học thuật phù hợp', 'Đa dạng cấu trúc câu'];

        $taskKey = $task === 1 ? 'ta' : 'tr';

        $first = $task === 1 ? [
            'band' => 6.0,
            'summary' => 'Bài viết đáp ứng yêu cầu đề bài, nêu được các đặc điểm chính của biểu đồ, nhưng một số thông tin quan trọng chưa được đề cập đầy đủ.',
            'priorities' => $priorities,
            'tips' => [['text' => 'Cần ưu tiên cải thiện tiêu chí Task Achievement, xem lại nội dung bài', 'link_label' => 'Cách viết Overview cho Task 1', 'link_url' => '/blog']],
            'subs' => [
                'task_fulfillment' => [
                    'band' => 6.0,
                    'comment' => 'Bài viết trả lời đúng dạng đề, có mở bài, overview và thân bài mô tả số liệu. Tuy nhiên phần so sánh giữa các quốc gia còn mỏng.',
                    'strengths' => ['Đúng dạng bài mô tả biểu đồ đường.', 'Đủ độ dài tối thiểu 150 từ.'],
                    'issues' => [],
                    'improvements' => ['Tăng số câu so sánh trực tiếp giữa các quốc gia.', 'Đảm bảo mỗi đoạn thân bài có một trọng tâm rõ ràng.'],
                ],
                'overview_quality' => [
                    'band' => 6.0,
                    'comment' => 'Có câu overview nhưng nội dung chưa phản ánh đúng xu hướng chính của biểu đồ.',
                    'strengths' => ['Overview được đặt đúng vị trí, ngay sau mở bài.'],
                    'issues' => [],
                    'improvements' => ['Nêu 2–3 đặc điểm nổi bật nhất trong overview.', 'Không đưa số liệu cụ thể vào overview.'],
                ],
                'key_features' => [
                    'band' => 6.0,
                    'comment' => 'Bài viết đã đề cập được các đặc điểm chính của biểu đồ, nhưng chưa làm nổi bật đầy đủ sự khác biệt quan trọng giữa ba nhóm quốc gia.',
                    'strengths' => ['Đã nêu được xu hướng chính của cả ba quốc gia.', 'Có đề cập đến các mốc thời gian quan trọng.', 'Bài viết nhìn chung bám sát yêu cầu của biểu đồ.'],
                    'issues' => [
                        [
                            'title' => 'Missing key comparison',
                            'description' => 'Bài chưa nhấn mạnh rõ sự chênh lệch lớn giữa Mỹ và hai quốc gia còn lại.',
                            'category' => 'Task Achievement / Key features',
                            'anchor' => 'Overall, the number of UK and Australian students experienced a slight decline over the period, while the figure for the US experienced a significant increase.',
                            'explanation' => 'Câu overview đã nêu xu hướng chung nhưng chưa làm nổi bật sự khác biệt quan trọng nhất là Mỹ luôn có số lượng sinh viên cao hơn đáng kể so với hai quốc gia còn lại.',
                            'suggestion' => 'Bổ sung so sánh rõ ràng về mức chênh lệch lớn, nhấn mạnh Mỹ cao nhất trong suốt giai đoạn.',
                            'correction' => 'Overall, the US had by far the highest number of students studying abroad throughout the period, while the figures for the UK and Australia were much lower and generally increased over time.',
                        ],
                        [
                            'title' => 'Inaccurate data reporting',
                            'description' => 'Một số dữ liệu trong bài được nêu chưa chính xác.',
                            'category' => 'Task Achievement / Data',
                            'anchor' => 'increased to approximately 320,000',
                            'explanation' => 'Theo biểu đồ, số sinh viên Mỹ giảm xuống khoảng 330,000 rồi tăng lại khoảng 350,000 vào năm 2020, không phải "tăng lên 320,000".',
                            'suggestion' => 'Đối chiếu lại số liệu cuối kỳ và dùng động từ xu hướng đúng chiều.',
                            'correction' => 'By 2020, the number of US students had fallen slightly to around 350,000.',
                        ],
                    ],
                    'improvements' => ['Nhấn mạnh rõ nhóm có số liệu cao vượt trội và nhóm thấp hơn.', 'Ưu tiên đưa vào overview hoặc câu khái quát các đặc điểm nổi bật nhất.', 'Kết hợp so sánh trực tiếp giữa các nhóm thay vì chỉ mô tả riêng lẻ.'],
                ],
                'data_accuracy' => [
                    'band' => 6.0,
                    'comment' => 'Phần lớn số liệu được trích dẫn đúng, nhưng có chỗ mô tả sai xu hướng.',
                    'strengths' => ['Có số liệu cụ thể cho mốc đầu kỳ.'],
                    'issues' => [],
                    'improvements' => ['Kiểm tra lại đơn vị và chiều tăng/giảm trước khi nộp bài.'],
                ],
            ],
        ] : [
            'band' => 6.0,
            'summary' => 'Bài viết trả lời được câu hỏi và có quan điểm, nhưng các ý chính chưa được phát triển đầy đủ.',
            'priorities' => $priorities,
            'tips' => [['text' => 'Cần ưu tiên cải thiện tiêu chí Task Response, xem lại nội dung bài', 'link_label' => 'Phát triển ý trong Task 2', 'link_url' => '/blog']],
            'subs' => [
                'task_fulfillment' => [
                    'band' => 5.0,
                    'comment' => 'Bài trả lời đáp ứng được yêu cầu đề bài ở mức độ vừa phải, có đưa ra lập trường nhưng đôi lúc không làm rõ mối liên hệ giữa ý tưởng và toàn bộ bài. Các ý chính liên quan đến "regardless of their financial situation" chưa được xử lý đầy đủ.',
                    'strengths' => ['Thesis trả lời trực tiếp yêu cầu agree/disagree.', 'Bài đưa ra lập trường về việc không nên miễn học phí hoàn toàn cho tất cả mọi người.', 'Có nhóm sinh viên có hoàn cảnh khó khăn và gia đình có khả năng chi trả đều được đề cập.'],
                    'issues' => [
                        [
                            'title' => 'Requirement Underdevelopment',
                            'description' => 'Bài đã trả lời yêu cầu nhưng chưa phát triển đầy đủ yếu tố "regardless of their financial situation".',
                            'category' => 'Task Response / Requirement',
                            'anchor' => 'However, this does not mean that all students need free education.',
                            'explanation' => 'Lập luận chủ yếu dựa trên việc gia đình giàu có thể tự chi trả, trong khi chưa giải thích đủ sâu vì sao tình trạng tài chính nên trở thành cơ sở để quyết định chính sách phân bổ hỗ trợ giáo dục đại học.',
                            'suggestion' => 'Làm rõ controlling phrase "regardless of their financial situation".',
                            'correction' => 'However, offering free tuition to every student, regardless of income, would not be the most efficient use of public funds.',
                        ],
                    ],
                    'improvements' => ['Làm rõ hơn controlling phrase "regardless of their financial situation".', 'Giải thích tại sao universal free education có thể không phải cách phân bổ nguồn lực công tối ưu.', 'Phát triển lập luận theo hướng nguyên tắc phân bổ nguồn lực công và hỗ trợ tài chính có mục tiêu.'],
                ],
                'position_clarity' => [
                    'band' => 6.0,
                    'comment' => 'Quan điểm "partly agree" được giữ nhất quán từ mở bài đến kết luận.',
                    'strengths' => ['Kết luận nhắc lại đúng quan điểm ở mở bài.'],
                    'issues' => [],
                    'improvements' => ['Nêu rõ mức độ đồng ý ngay trong câu thesis.'],
                ],
                'idea_development' => [
                    'band' => 5.0,
                    'comment' => 'Mỗi đoạn có ý chính nhưng phần giải thích và ví dụ còn chung chung.',
                    'strengths' => ['Có ví dụ minh họa ở đoạn thân bài 1.'],
                    'issues' => [
                        [
                            'title' => 'Unsupported claim',
                            'description' => 'Ý "gia đình giàu đã có thể tự trả học phí" chưa được giải thích thêm.',
                            'category' => 'Task Response / Development',
                            'anchor' => 'so it is not necessary for the government to spend money on them.',
                            'explanation' => 'Câu kết luận nhanh mà chưa phân tích hệ quả (vd: lãng phí ngân sách, giảm hỗ trợ cho nhóm cần).',
                            'suggestion' => 'Thêm một câu giải thích hệ quả và một ví dụ cụ thể.',
                            'correction' => 'so subsidising them would divert limited public money away from students who genuinely need financial support.',
                        ],
                    ],
                    'improvements' => ['Mỗi ý chính nên theo cấu trúc: luận điểm → giải thích → ví dụ.'],
                ],
                'relevance' => [
                    'band' => 6.0,
                    'comment' => 'Các ý đều liên quan đến đề bài, không lạc đề.',
                    'strengths' => ['Không có đoạn lan man ngoài chủ đề.'],
                    'issues' => [],
                    'improvements' => ['Giữ trọng tâm vào yếu tố tài chính được nêu trong đề.'],
                ],
            ],
        ];

        $cc = [
            'band' => $task === 1 ? 6.0 : 5.5,
            'summary' => 'Bài viết mạch lạc, các đoạn có sự liên kết rõ ràng, sử dụng từ nối đa dạng và phù hợp.',
            'priorities' => $priorities,
            'tips' => [['text' => 'Cần ưu tiên cải thiện tiêu chí Coherence & Cohesion, xem lại nội dung bài', 'link_label' => 'Sử dụng từ nối (Linking words) nâng cao', 'link_url' => '/blog']],
            'subs' => [
                'paragraph_organization' => [
                    'band' => 6.0,
                    'comment' => $task === 1
                        ? 'Bài viết có cấu trúc rõ ràng với đầy đủ 4 phần (mở bài, tổng quan, thân bài, kết luận). Tuy nhiên, một số đoạn còn chưa được tổ chức mạch lạc tối ưu, khiến ý chuyển tiếp giữa các phần chưa thật sự mượt mà.'
                        : 'Bài có bố cục 4 đoạn rõ ràng, mỗi đoạn thân bài có một ý chính.',
                    'strengths' => $task === 1
                        ? ['Đã có cấu trúc 4 đoạn rõ ràng và phù hợp với dạng biểu đồ.', 'Mỗi đoạn có ý chính tương đối rõ.', 'Thông tin được sắp xếp theo trình tự thời gian hợp lý.']
                        : ['Mở bài – thân bài – kết bài tách đoạn rõ ràng.'],
                    'issues' => $task === 1 ? [
                        [
                            'title' => 'Lack of clear introduction',
                            'description' => 'Phần mở bài chưa giới thiệu tổng quát đầy đủ về biểu đồ.',
                            'category' => 'Coherence & Cohesion / Organization',
                            'anchor' => 'The line graph illustrates the number of students from the UK, the US and Australia',
                            'explanation' => 'Mở bài gần như chép lại đề bài, chưa paraphrase đối tượng và khoảng thời gian.',
                            'suggestion' => 'Viết lại câu mở bài bằng từ ngữ của bạn, giữ đủ: đối tượng, đơn vị, thời gian.',
                            'correction' => 'The line graph compares how many students from the UK, the US and Australia pursued university degrees overseas over a 20-year period from 2000.',
                        ],
                    ] : [],
                    'improvements' => $task === 1
                        ? ['Viết câu mở bài rõ ràng hơn, giới thiệu đầy đủ đối tượng (UK, US, Australia), dạng biểu đồ (biểu đồ đường) và khoảng thời gian (2000–2020).', 'Có thể diễn đạt lại bằng một câu tổng quát, súc tích, bao quát toàn bộ nội dung biểu đồ.']
                        : ['Nhóm ý theo chủ đề thay vì liệt kê.'],
                ],
                'logical_progression' => [
                    'band' => $task === 1 ? 6.0 : 5.0,
                    'comment' => 'Trình tự trình bày nhìn chung hợp lý nhưng có chỗ chuyển ý đột ngột.',
                    'strengths' => ['Các đoạn thân bài đi theo trình tự thời gian.'],
                    'issues' => [],
                    'improvements' => ['Dùng câu chủ đề để dẫn dắt mỗi đoạn.'],
                ],
                'cohesive_devices' => [
                    'band' => $task === 1 ? 6.0 : 5.0,
                    'comment' => 'Một số từ nối phù hợp; nên ưu tiên logic thay vì tăng số lượng từ nối.',
                    'strengths' => ['Có sử dụng "In contrast", "while", "compared to".'],
                    'issues' => [],
                    'improvements' => ['Đa dạng từ nối chỉ sự tương phản và nguyên nhân – kết quả.'],
                ],
                'referencing' => [
                    'band' => 6.0,
                    'comment' => $task === 1
                        ? 'Cần thêm reference để giảm lặp tên đối tượng.'
                        : 'Reference nhìn chung rõ và không có ambiguity nghiêm trọng. Tuy nhiên, bài thường lặp lại students, government, university fees thay vì dùng substitution/reference linh hoạt hơn.',
                    'strengths' => $task === 1
                        ? ['Có dùng "the figure" để thay thế danh từ.']
                        : ['"they may stop their education" → they có antecedent rõ là low-income students.', '"this can create problems for the national budget" → this vẫn có thể được hiểu từ preceding clause.'],
                    'issues' => $task === 2 ? [
                        [
                            'title' => 'Unclear Reference',
                            'description' => 'Từ "this" tham chiếu đến cả một mệnh đề dài.',
                            'category' => 'Coherence & Cohesion / Referencing',
                            'anchor' => 'this can create problems for the national budget',
                            'explanation' => 'Trong cụm "this can create problems for the national budget", từ "this" vẫn có thể được hiểu là việc chính phủ trả học phí cho tất cả sinh viên, nhưng reference còn khá broad vì antecedent là cả một proposition. Người đọc vẫn hiểu được ý, nhưng reference chưa đủ precise.',
                            'suggestion' => 'Khi proposition trước dài, thay vague demonstrative như "this" bằng một noun phrase cụ thể.',
                            'correction' => 'Such universal subsidies could place considerable pressure on the national budget.',
                        ],
                    ] : [],
                    'improvements' => $task === 1
                        ? ['Dùng "the former / the latter", "this figure" để tránh lặp.']
                        : ['Cần cải thiện: Reference specificity.', 'Cách cải thiện: khi proposition trước dài, thay vague demonstrative bằng một noun phrase cụ thể.'],
                ],
            ],
        ];

        $gra = [
            'band' => $task === 1 ? 5.5 : 5.0,
            'summary' => 'Sử dụng đa dạng cấu trúc ngữ pháp phức, ít lỗi ngữ pháp. Một số câu còn chưa tự nhiên.',
            'priorities' => $priorities,
            'tips' => [['text' => 'Cần ưu tiên cải thiện tiêu chí Grammatical Range & Accuracy, xem lại nội dung bài', 'link_label' => 'Câu phức và mệnh đề quan hệ', 'link_url' => '/blog']],
            'subs' => [
                'grammar_accuracy' => [
                    'band' => $task === 1 ? 5.5 : 5.0,
                    'comment' => 'Bài viết có một số lỗi ngữ pháp nhỏ, chủ yếu liên quan đến thì, chia động từ và cấu trúc câu. Nhìn chung, mức độ chính xác ngữ pháp ở mức khá, nhưng vẫn cần cải thiện để đạt điểm cao hơn.',
                    'strengths' => ['Sử dụng đa dạng các cấu trúc câu đơn giản và phức tạp.', 'Phần lớn các câu đều đúng ngữ pháp và dễ hiểu.', 'Có khả năng sử dụng các liên từ để kết nối ý tưởng tốt.'],
                    'issues' => $task === 1 ? [
                        [
                            'title' => 'Subject-verb agreement',
                            'description' => 'Sai hòa hợp chủ ngữ – động từ.',
                            'category' => 'Grammar / Agreement',
                            'anchor' => 'the US and Australia',
                            'explanation' => 'Chủ ngữ số nhiều cần động từ chia ở dạng số nhiều tương ứng.',
                            'suggestion' => 'Rà soát chủ ngữ số nhiều và động từ đi kèm.',
                            'correction' => 'students rose / students studied',
                        ],
                        [
                            'title' => 'Tense consistency',
                            'description' => 'Sử dụng thì không nhất quán.',
                            'category' => 'Grammar / Tense',
                            'anchor' => 'while the figure for the US experienced a significant increase.',
                            'explanation' => 'Mô tả số liệu quá khứ nên dùng thống nhất thì quá khứ đơn hoặc quá khứ hoàn thành khi có mốc "by".',
                            'suggestion' => 'Thống nhất thì quá khứ đơn cho các mốc năm trong quá khứ.',
                            'correction' => 'while the figure for the US saw a significant increase.',
                        ],
                        [
                            'title' => 'Article usage',
                            'description' => 'Thiếu / thừa mạo từ.',
                            'category' => 'Grammar / Articles',
                            'anchor' => 'the figure for UK and Australian figures',
                            'explanation' => 'Cụm danh từ bị lặp "figure" và thiếu mạo từ trước "UK".',
                            'suggestion' => 'Bỏ danh từ lặp và thêm mạo từ "the" trước tên quốc gia dạng viết tắt.',
                            'correction' => 'the figures for the UK and Australia',
                        ],
                        [
                            'title' => 'Preposition',
                            'description' => 'Dùng sai giới từ.',
                            'category' => 'Grammar / Prepositions',
                            'anchor' => 'fluctuating slightly above and below around 350,000.',
                            'explanation' => '"above and below around" là thừa giới từ.',
                            'suggestion' => 'Chọn một giới từ phù hợp.',
                            'correction' => 'fluctuating slightly around 350,000.',
                        ],
                    ] : [
                        [
                            'title' => 'Verb pattern',
                            'description' => 'Sai cấu trúc động từ "spend money on".',
                            'category' => 'Grammar / Verb pattern',
                            'anchor' => 'many other important areas to spend money,',
                            'explanation' => 'Cấu trúc đúng là "areas to spend money on".',
                            'suggestion' => 'Kiểm tra giới từ đi kèm động từ.',
                            'correction' => 'many other important areas to spend money on,',
                        ],
                    ],
                    'improvements' => ['Kiểm tra kỹ giới từ đi kèm với động từ mô tả xu hướng và cấu trúc so sánh (e.g., "followed by", "increase in").', 'Rà soát sự hòa hợp giữa chủ ngữ số nhiều ("students") và động từ nguyên mẫu ("rose"/"studied").', 'Đảm bảo tính thống nhất về thì (ưu tiên thì Quá khứ đơn cho số liệu thuộc các mốc năm trong quá khứ).'],
                ],
                'sentence_variety' => [
                    'band' => $task === 1 ? 6.0 : 5.0,
                    'comment' => 'Có kết hợp câu ghép và câu phức nhưng cấu trúc còn lặp lại.',
                    'strengths' => ['Dùng được mệnh đề "while" để so sánh.'],
                    'issues' => [],
                    'improvements' => ['Thêm mệnh đề quan hệ rút gọn và câu bị động khi phù hợp.'],
                ],
                'grammatical_complexity' => [
                    'band' => $task === 1 ? 6.0 : 4.0,
                    'comment' => 'Bài có một số complex sentences nhưng complexity tập trung ở các structures quen thuộc như because, if, while. Structural diversity còn hẹp và chưa thể hiện nhiều loại relationship phức tạp.',
                    'strengths' => ['If university is free, they can continue studying...', 'while poor students should receive more support.'],
                    'issues' => $task === 2 ? [
                        [
                            'title' => 'Limited Structural Diversity',
                            'description' => 'Complex structures có xuất hiện nhưng range còn hẹp.',
                            'category' => 'Grammar / Complexity',
                            'anchor' => 'rich families can already pay university fees,',
                            'explanation' => 'Câu dùng cấu trúc "so" quen thuộc; có thể nâng cấp bằng mệnh đề phân từ hoặc "Given that".',
                            'suggestion' => 'Mở rộng structure theo function chứ không thêm câu dài máy móc.',
                            'correction' => 'Given that affluent families are already able to cover tuition costs, subsidising them may represent an inefficient use of public funds.',
                        ],
                    ] : [],
                    'improvements' => ['Cần cải thiện: Structural diversity.', 'Cách cải thiện: Mở rộng structure theo function chứ không thêm câu dài máy móc.'],
                ],
                'error_impact' => [
                    'band' => 6.0,
                    'comment' => 'Các lỗi nhỏ ít gây cản trở việc truyền đạt ý.',
                    'strengths' => ['Người đọc vẫn hiểu toàn bộ nội dung bài.'],
                    'issues' => [],
                    'improvements' => ['Dành 2–3 phút cuối để soát lỗi.'],
                ],
            ],
        ];

        $lr = [
            'band' => $task === 1 ? 6.0 : 5.5,
            'summary' => 'Vốn từ vựng phong phú, sử dụng nhiều collocation và từ vựng học thuật phù hợp. Một số từ chưa thật sự chính xác trong ngữ cảnh.',
            'priorities' => $priorities,
            'tips' => [['text' => 'Cần ưu tiên cải thiện tiêu chí Lexical Resource, xem lại nội dung bài', 'link_label' => 'Từ vựng mô tả xu hướng', 'link_url' => '/dictionary']],
            'subs' => [
                'vocabulary_range' => [
                    'band' => 6.0,
                    'comment' => 'Bài có vốn từ vựng khá đa dạng, tuy nhiên vẫn còn sử dụng một số từ vựng phổ thông, chưa thật sự học thuật hoặc chưa thể hiện được sự linh tế trong việc lựa chọn từ theo ngữ cảnh.',
                    'strengths' => ['Sử dụng được một số từ vựng học thuật phù hợp với dạng biểu đồ.', 'Có nỗ lực thay đổi từ vựng để tránh lặp lại.', 'Một số cụm từ diễn đạt xu hướng và số liệu khá chính xác.'],
                    'issues' => [
                        [
                            'title' => 'Limited Vocabulary Range',
                            'description' => 'Từ vựng mô tả xu hướng còn lặp lại.',
                            'category' => 'Lexical Resource / Range',
                            'anchor' => $task === 1 ? 'who studied at universities in other countries' : 'get a better job in the future',
                            'explanation' => 'Cụm từ phổ thông, có thể thay bằng cách diễn đạt học thuật hơn.',
                            'suggestion' => 'Dùng từ đồng nghĩa hoặc cụm từ học thuật phù hợp ngữ cảnh.',
                            'correction' => $task === 1 ? 'who pursued tertiary education overseas' : 'secure better employment prospects',
                        ],
                    ],
                    'improvements' => ['Mở rộng vốn từ vựng học thuật liên quan đến mô tả xu hướng, so sánh và số liệu.', 'Tránh lặp lại các từ đơn giản, ưu tiên sử dụng các từ đồng nghĩa hoặc cụm từ học thuật hơn.', 'Đảm bảo lựa chọn từ vựng phù hợp với ngữ cảnh và mức độ trang trọng của bài viết.'],
                ],
                'lexical_precision' => [
                    'band' => 6.0,
                    'comment' => 'Bài viết nhìn chung có sử dụng từ vựng phù hợp với ngữ cảnh, tuy nhiên một số cách diễn đạt vẫn còn khá chung và chưa thật sự chính xác về sắc thái nghĩa.',
                    'strengths' => ['Có sử dụng được các từ mô tả xu hướng và số liệu tương đối phù hợp.', 'Một số cụm diễn đạt học thuật được dùng đúng ngữ cảnh cơ bản.', 'Bài viết nhìn chung vẫn giữ được độ rõ nghĩa khi diễn đạt.'],
                    'issues' => [
                        [
                            'title' => 'Imprecise vocabulary choice',
                            'description' => 'Một số từ/cụm từ được dùng khá chung, chưa thể hiện đối tượng hoặc sắc thái nghĩa một cách thật sự chính xác.',
                            'category' => 'Lexical Resource / Precision',
                            'anchor' => $task === 1 ? 'the figure' : 'pay everything for everyone',
                            'explanation' => 'Từ quá chung khiến người đọc phải đoán đối tượng được nhắc đến.',
                            'suggestion' => 'Chọn cụm danh từ cụ thể, bám sát đối tượng được nói đến.',
                            'correction' => $task === 1 ? 'the number of US students' : 'cover the full cost of tuition for every student',
                        ],
                    ],
                    'improvements' => ['Ưu tiên chọn từ cụ thể hơn thay vì dùng các từ quá chung.', 'Khi mô tả số liệu, dùng cụm từ bám sát đối tượng được nhắc đến.', 'Rà soát lại các từ mang sắc thái nghĩa gần nhau để chọn cách diễn đạt chính xác hơn.'],
                ],
                'collocation' => [
                    'band' => 6.0,
                    'comment' => 'Dùng được một số collocation tự nhiên, vài cụm còn gượng.',
                    'strengths' => ['"experienced a slight decline", "remained relatively stable".'],
                    'issues' => [],
                    'improvements' => ['Học collocation theo cụm: "rise sharply", "a marked increase".'],
                ],
                'lexical_accuracy' => [
                    'band' => 6.0,
                    'comment' => 'Ít lỗi chính tả và word form.',
                    'strengths' => ['Không có lỗi chính tả nghiêm trọng.'],
                    'issues' => [],
                    'improvements' => ['Chú ý dạng từ (noun/verb/adjective) khi paraphrase.'],
                ],
            ],
        ];

        return [$taskKey => $first, 'cc' => $cc, 'gra' => $gra, 'lr' => $lr];
    }

    public static function samples(int $task): array
    {
        if ($task === 1) {
            return [
                '6.5' => "The line graph shows how many students from the UK, the US and Australia studied at universities abroad from 2000 to 2020.\n\nOverall, the US had the most students studying overseas, although its figure went down slightly. In contrast, the numbers for the UK and Australia increased steadily.\n\nIn 2000, about 420,000 American students studied abroad, while there were only around 50,000 from the UK and 20,000 from Australia. The US figure then decreased to about 330,000 in 2015 before rising to 350,000 in 2020.\n\nThe UK and Australia both saw a gradual increase. By 2020, the number of UK students was around 170,000, and Australia reached a similar level at about 165,000.",
                '7.0' => "The line graph shows the number of students from the UK, the US and Australia who studied at universities in other countries between 2000 and 2020.\n\nOverall, the number of UK and Australian students rose steadily over the period, while the figure for the US experienced a slight decline.\n\nIn 2000, around 50,000 UK students studied abroad, compared to 420,000 from the US and 23,000 from Australia. Over the next two decades, the number of UK students increased significantly, reaching approximately 120,000 in 2020. A similar upward trend can be seen for Australia, with the figure growing to about 160,000 students.\n\nIn contrast, the number of US students remained relatively stable throughout the period, fluctuating slightly before falling to around 350,000 in 2020. By the end of the period, the US had the highest number of students studying overseas, followed by Australia, whereas the UK had the lowest figure.",
                '8.0' => "The line graph compares the number of university students from the UK, the US and Australia who pursued their studies overseas over a twenty-year period beginning in 2000.\n\nOverall, the US consistently sent by far the largest number of students abroad, although its figure contracted over the period. Conversely, both the UK and Australia witnessed sustained growth, gradually narrowing the gap.\n\nAt the start of the period, approximately 420,000 American students were enrolled at foreign universities, dwarfing the figures for the UK (around 45,000) and Australia (roughly 20,000). The US figure then declined steadily, bottoming out at about 330,000 in 2015 before recovering modestly to 350,000 by 2020.\n\nMeanwhile, the UK and Australian figures followed remarkably similar upward trajectories. Each roughly quadrupled or more over the two decades, reaching around 170,000 and 165,000 respectively in 2020, by which point the two countries were almost level.",
            ];
        }

        return [
            '6.5' => "Some people say that university education should be free for everyone. I disagree with this idea because free education for all students is too expensive and not fair.\n\nOn the one hand, free university can help poor students. Many students cannot pay high fees, so they stop studying after school. If university is free, more people can study and get better jobs, which is good for society.\n\nOn the other hand, rich students do not need help from the government. Their families can pay the fees easily. The government also needs money for hospitals, roads and schools, so it should not spend too much money on education for everyone.\n\nIn conclusion, I believe that university should be free only for students from poor families, and rich students should pay for their own education.",
            '7.0' => "While some people argue that university education should be free for everyone regardless of income, I disagree with this view. Although financial support is essential for students from disadvantaged backgrounds, making university entirely free for all would be an unfair and inefficient use of public funds.\n\nOn the one hand, waiving tuition fees can significantly improve access to higher education for low-income students. Many capable young people are unable to attend university simply because they cannot afford the high costs. By removing this financial barrier, more people would have the opportunity to gain higher education, which could expand the supply of skilled workers and bring broader social benefits.\n\nOn the other hand, students from wealthy families can already afford to pay for their own education, so providing them with the same level of subsidy is unnecessary. Government budgets are limited, and spending on affluent students could divert resources away from other essential services such as healthcare, primary education and infrastructure. A more reasonable approach is to provide targeted financial support, such as grants or fee waivers, to students whose access to higher education genuinely depends on it.\n\nIn conclusion, university education should not be free for everyone, but financial assistance for disadvantaged students is important and fair.",
            '8.0' => "It is sometimes argued that tertiary education should be provided free of charge to every student, irrespective of their financial means. While I accept that cost should never prevent talented individuals from studying, I believe that universal free tuition is neither equitable nor fiscally sustainable.\n\nAdmittedly, abolishing fees would remove a significant barrier for students from low-income households, many of whom are deterred not by a lack of ability but by the prospect of substantial debt. Widening participation in this way could also strengthen the economy by enlarging the pool of highly skilled graduates.\n\nHowever, extending the same subsidy to affluent families, who can comfortably absorb tuition costs, effectively transfers public money to those who least need it. Since government budgets are finite, every pound spent on such students is a pound unavailable for priorities such as healthcare or early-years education. A means-tested system of grants and fee waivers would therefore achieve the social benefits of free education while directing resources where they make the greatest difference.\n\nIn conclusion, although no student should be excluded from university on financial grounds, a targeted rather than universal approach represents a fairer and more efficient use of public funds.",
        ];
    }

    public static function resources(int $task): array
    {
        return [
            ['title' => 'IELTS Academic Task 1 Guide', 'tag' => 'IELTS Writing', 'url' => '/blog'],
            ['title' => 'Writing Task 1 Strategies', 'tag' => 'IELTS Task 1', 'url' => '/blog'],
            ['title' => 'Graph & Chart Vocabulary Pack', 'tag' => 'Từ vựng', 'url' => '/dictionary'],
            ['title' => 'Sample Answers Band 7–8', 'tag' => 'Bài mẫu', 'url' => '/blog'],
            ['title' => 'Task 2 Essay Structures', 'tag' => 'IELTS Task 2', 'url' => '/blog'],
            ['title' => 'Linking Words nâng cao', 'tag' => 'Coherence', 'url' => '/blog'],
        ];
    }

    public static function courses(): array
    {
        return [
            ['title' => 'Writing Task 1', 'subtitle' => 'Từ cơ bản đến nâng cao', 'lessons' => 12, 'duration' => '2 giờ 30 phút', 'url' => '/classes'],
            ['title' => 'Writing Task 2', 'subtitle' => 'Chiến lược làm bài hiệu quả', 'lessons' => 18, 'duration' => '3 giờ 10 phút', 'url' => '/classes'],
            ['title' => 'Grammar for IELTS', 'subtitle' => 'Ngữ pháp trọng tâm band 6.5+', 'lessons' => 15, 'duration' => '2 giờ 45 phút', 'url' => '/classes'],
            ['title' => 'Academic Vocabulary', 'subtitle' => 'Từ vựng học thuật theo chủ đề', 'lessons' => 20, 'duration' => '3 giờ', 'url' => '/classes'],
        ];
    }
}
