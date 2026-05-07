@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $dontShowCookieSecurity = true;
    $isEnglish = mb_strtolower(app()->getLocale()) === 'en';
    $nextLocale = $isEnglish ? 'vi' : 'en';
    $nextLocaleLabel = $nextLocale === 'en' ? 'ENG' : 'VIE';
    $langSwitchClass = $nextLocale === 'en' ? 'is-next-eng' : 'is-next-vie';
    $locale = $isEnglish ? 'en' : 'vi';
    $isHomeActive = request()->path() === '/';
    $isClassesActive = request()->is('classes') || request()->is('classes/*');
    $isPlacementActive = request()->is('panel/ielts-tests/practice') || request()->is('panel/ielts-tests/practice/*');
    $isMockActive = request()->is('panel/ielts-tests/mock') || request()->is('panel/ielts-tests/mock/*');
    $isDictionaryActive = request()->is('panel/dictionary') || request()->is('panel/dictionary/*');
    $isNewsActive = request()->is('blog') || request()->is('blog/*');

    $homeText = [
        'vi' => [
            'header' => [
                'mainNavAria' => 'Main Navigation',
                'languageSwitchAria' => 'Language Switch',
                'switchLanguageAria' => 'Switch language',
                'loginButton' => 'Đăng nhập',
            ],
            'nav' => [
                'home' => 'Trang chủ',
                'classes' => 'Khóa học',
                'placementTest' => 'Kiểm tra đầu vào',
                'mockTest' => 'Luyện đề',
                'dictionary' => 'Từ điển & Flashcard',
                'news' => 'Tin tức',
            ],
            'hero' => [
                'line1' => 'Chinh Phục IELTS',
                'line2Prefix' => 'cùng',
                'line2Highlight' => 'Edtika AI',
                'line3' => '& Tutor đồng hành 1:1',
                'subtextLine1' => 'Tạm biệt cách học IELTS lỗi thời. Trải nghiệm nền tảng học tập ứng dụng AI thông minh,',
                'subtextLine2' => 'kết hợp cùng đội ngũ mentor hỗ trợ 1:1 từ Edtika',
                'cta' => 'Học thử ngay',
            ],
            'why' => [
                'aria' => 'Vì sao nên luyện thi IELTS cùng Edtika?',
                'titlePrefix' => 'Vì sao nên luyện thi IELTS cùng',
                'titleHighlight' => 'Edtika',
                'cards' => [
                    [
                        'alt' => 'Chi phí hợp lý',
                        'title' => 'Chi phí hợp lý, không lo học phí!',
                        'desc' => 'Học phí cực tốt, nhiều ưu đãi đi kèm. Hệ thống đánh giá trình độ đầu vào và xây dựng lộ trình học riêng cho từng học viên để tập trung vào kỹ năng còn yếu, tăng tốc ở phần đã vững.',
                    ],
                    [
                        'alt' => 'Luyện tập như thi thật',
                        'title' => 'Luyện tập như thi thật',
                        'desc' => 'Làm quen áp lực phòng thi ngay từ đầu. Hệ thống bài luyện bám sát đề thật, giúp bạn làm quen kỹ năng làm bài và tâm lý thi cử.',
                    ],
                    [
                        'alt' => 'Mentor hỗ trợ 1:1',
                        'title' => 'Mentor hỗ trợ 1:1',
                        'desc' => 'Luôn có giảng viên sẵn sàng hướng dẫn. Mentor giàu kinh nghiệm sửa lỗi theo sát quá trình học, giải đáp thắc mắc và đưa ra chiến lược cải thiện điểm số.',
                    ],
                    [
                        'alt' => 'Học mọi lúc mọi nơi',
                        'title' => 'Học mọi lúc mọi nơi',
                        'desc' => 'Học linh hoạt theo lịch của riêng bạn. Chỉ cần thiết bị kết nối internet, bạn có thể học và luyện tập bất cứ lúc nào, tối ưu quỹ thời gian hiệu quả.',
                    ],
                    [
                        'alt' => 'Ghi nhớ từ vựng dễ dàng',
                        'title' => 'Ghi nhớ từ vựng dễ dàng',
                        'desc' => 'Flash card sẵn sàng để học ngay. Bộ flashcards từ vựng được phân nhóm theo band điểm và chủ đề giúp bạn ôn luyện đều đặn, nhớ lâu hơn.',
                    ],
                    [
                        'alt' => 'Từ điển tích hợp tiện lợi',
                        'title' => 'Từ điển tích hợp tiện lợi',
                        'desc' => 'Tra nghĩa ngay khi đang học. Từ điển tích hợp trong giao diện học tập giúp bạn tra nhanh, phát âm và ví dụ ngay lập tức mà không cần rời bài học.',
                    ],
                    [
                        'alt' => 'Lộ trình học cá nhân hóa',
                        'title' => 'Lộ trình học cá nhân hóa',
                        'desc' => 'Học đúng thứ bạn cần, không lãng phí thời gian. Hệ thống đánh giá chính xác trình độ để đề xuất lộ trình riêng và mục tiêu rõ ràng cho từng giai đoạn.',
                    ],
                    [
                        'alt' => 'Bài giảng chuẩn hóa',
                        'title' => 'Bài giảng chuẩn hóa',
                        'desc' => 'Bài giảng chuẩn Cambridge, trình bày rõ ràng và dễ tiếp thu. Nội dung được chuẩn hóa giúp bạn học bài bản và tiến bộ bền vững.',
                    ],
                ],
            ],
            'flow' => [
                'aria' => 'Được Thiết Kế Cho Trải Nghiệm Học Tập Dễ Dàng',
                'titlePrefix' => 'Được Thiết Kế Cho',
                'titleHighlight' => 'Trải Nghiệm Học Tập Dễ Dàng',
                'subtitle' => 'Bạn sẽ học tập và ôn luyện như thế nào trên nền tảng của Edtika?',
                'steps' => [
                    ['label' => 'Kiểm tra trình độ đầu vào', 'description' => 'Kiểm tra bằng bài kiểm tra nhanh hoặc bài thi mô phỏng kỳ thi thật'],
                    ['label' => 'Bài giảng chuẩn hoá', 'description' => 'Bài giảng được chuẩn hoá theo từng band điểm, rõ ràng và dễ theo dõi trên mọi thiết bị.'],
                    ['label' => 'Bài tập ôn luyện đa dạng', 'description' => 'Hệ thống bài tập đa dạng theo từng kỹ năng giúp bạn luyện tập đều và tiến bộ liên tục.'],
                    ['label' => 'Phòng thi mô phỏng như kỳ thi thật', 'description' => 'Phòng thi mô phỏng tái tạo áp lực thật để bạn tự tin hơn khi bước vào kỳ thi chính thức.'],
                    ['label' => 'AI chấm chữa Speaking và Writing', 'description' => 'AI chấm chữa Speaking và Writing nhanh chóng, chỉ ra lỗi trọng tâm và gợi ý cải thiện cụ thể.'],
                    ['label' => 'Mentor chấm chữa Speaking và Writing', 'description' => 'Mentor theo sát và chấm chữa chi tiết Speaking/Writing để bạn cải thiện theo lộ trình cá nhân.'],
                    ['label' => 'Ghi nhớ từ vựng bằng Flashcard', 'description' => 'Ghi nhớ từ vựng bằng flashcard thông minh, ôn tập theo nhịp học và mục tiêu band điểm của bạn.'],
                ],
            ],
            'bundles' => [
                'aria' => 'Khám phá các khóa học của Edtika',
                'titlePrefix' => 'Khám phá các khóa học của',
                'titleHighlight' => 'Edtika',
                'buy' => 'Mua',
                'view' => 'Xem',
                'placementCta' => 'Kiểm tra đầu vào',
                'detailCta' => 'Chi tiết khóa học',
                'billing' => 'hoá đơn theo tháng',
            ],
            'faq' => [
                'aria' => 'EDTIKA luôn giải đáp mọi thắc mắc của bạn',
                'titleHtml' => '<span>EDTIKA</span><span>LUÔN</span><span>GIẢI&nbsp;ĐÁP</span><span>MỌI</span><span>THẮC&nbsp;MẮC</span><span>CỦA&nbsp;BẠN!</span>',
                'panelAria' => 'Danh sách câu hỏi thường gặp',
            ],
            'consultation' => [
                'aria' => 'Bạn muốn được tư vấn thêm về khóa học?',
                'title' => 'Bạn muốn được tư vấn thêm về khóa học?',
                'subtitleLine1' => 'Đội ngũ tư vấn viên của Edtika luôn sẵn sàng phục vụ bạn.',
                'subtitleLine2' => 'Hãy để lại thông tin bên dưới, chúng tôi sẽ liên hệ bạn ngay khi có thể nhé!',
                'studentName' => 'Họ và tên học viên',
                'studentNamePlaceholder' => 'Nhập họ và tên của bạn',
                'registerRole' => 'Vai trò người đăng ký',
                'registerRolePlaceholder' => '-- Bỏ qua nếu bạn tự đăng ký --',
                'roleParent' => 'Phụ huynh',
                'roleTeacher' => 'Giáo viên',
                'roleAgent' => 'Đại lý',
                'roleOther' => 'Khác',
                'email' => 'Email',
                'phone' => 'Số điện thoại',
                'course' => 'Khóa học bạn đang quan tâm',
                'coursePlaceholder' => '-- Chọn khóa học --',
                'courseBasic' => 'Khóa học cơ bản',
                'message' => 'Nội dung',
                'messagePlaceholder' => 'Hãy cho chúng tôi biết thắc mắc của bạn...',
                'submit' => 'Gửi',
            ],
            'blog' => [
                'aria' => 'Bài viết mới nhất từ Edtika',
                'titlePrefix' => 'Bài viết mới nhất từ',
                'titleHighlight' => 'Edtika',
                'subtitle' => 'Đừng bỏ lỡ! Chúng tôi liên tục cập nhật những nội dung học tập và thông tin mới nhất!',
                'pageAriaPrefix' => 'Trang',
            ],
            'footer' => [
                'description' => 'Nền tảng này được thiết kế để giúp các tổ chức, nhà giáo dục và người học quản lý, cung cấp và theo dõi các hoạt động học tập và đào tạo.',
                'supportTitle' => 'Hỗ trợ',
                'supportLinks' => ['Kiểm tra đầu vào', 'Luyện đề', 'Từ điển & Flashcard'],
                'introTitle' => 'Giới thiệu',
                'introLinks' => ['Khóa học', 'FAQ', 'Tin tức'],
                'contactTitle' => 'Thông tin liên hệ',
                'hotlineLabel' => 'Số điện thoại/Hotline',
                'hotlineValue' => '0987 654 321',
                'emailLabel' => 'Thư điện tử',
                'emailValue' => 'contact@edtika.com',
                'addressLabel' => 'Địa chỉ',
                'addressValue' => 'Thành phố Hồ Chí Minh, Việt Nam',
                'copyright' => 'Bản quyền 2026',
                'allRightsReserved' => 'Mọi quyền được bảo lưu.',
                'terms' => 'Điều khoản & Chính sách',
                'privacy' => 'Chính sách bảo mật',
            ],
            'auth' => [
                'dialogAria' => 'Đăng nhập và đăng ký',
                'closeAria' => 'Đóng',
                'tabsAria' => 'Auth Tabs',
                'loginTab' => 'Đăng nhập',
                'registerTab' => 'Đăng ký',
                'loginTitle' => 'Đăng nhập vào tài khoản của bạn',
                'loginMethodsAria' => 'Login Methods',
                'emailMethod' => 'Email',
                'phoneMethod' => 'Điện thoại',
                'password' => 'Mật khẩu',
                'forgotPassword' => 'Bạn quên mật khẩu?',
                'noAccount' => 'Bạn chưa có tài khoản?',
                'hasAccount' => 'Bạn đã có tài khoản?',
                'registerTitle' => 'Tạo tài khoản mới',
                'fullName' => 'Họ và tên',
                'confirmPassword' => 'Nhập lại mật khẩu',
                'sliderImageAlt' => 'Auth slider image',
            ],
        ],
        'en' => [
            'header' => [
                'mainNavAria' => 'Main Navigation',
                'languageSwitchAria' => 'Language Switch',
                'switchLanguageAria' => 'Switch language',
                'loginButton' => 'Log in',
            ],
            'nav' => [
                'home' => 'Home',
                'classes' => 'Courses',
                'placementTest' => 'Placement Test',
                'mockTest' => 'Mock Tests',
                'dictionary' => 'Dictionary & Flashcards',
                'news' => 'News',
            ],
            'hero' => [
                'line1' => 'Conquer IELTS',
                'line2Prefix' => 'with',
                'line2Highlight' => 'Edtika AI',
                'line3' => '& 1:1 Tutor Support',
                'subtextLine1' => 'Say goodbye to outdated IELTS learning. Experience an AI-powered learning platform,',
                'subtextLine2' => 'combined with dedicated 1:1 mentor support from Edtika.',
                'cta' => 'Start Free Trial',
            ],
            'why' => [
                'aria' => 'Why learn IELTS with Edtika?',
                'titlePrefix' => 'Why learn IELTS with',
                'titleHighlight' => 'Edtika',
                'cards' => [
                    ['alt' => 'Affordable cost', 'title' => 'Affordable pricing, no tuition stress!', 'desc' => 'Great tuition plans with valuable perks. Our system evaluates your entry level and builds a personalized roadmap to focus on weak skills and accelerate strong ones.'],
                    ['alt' => 'Real-test practice', 'title' => 'Practice like the real exam', 'desc' => 'Get used to exam pressure from day one. Practice materials follow real test formats so you build exam strategy and confidence.'],
                    ['alt' => '1:1 mentor support', 'title' => '1:1 Mentor support', 'desc' => 'Experienced mentors guide you closely, correct mistakes, answer questions, and give practical strategies to improve scores.'],
                    ['alt' => 'Learn anywhere', 'title' => 'Learn anytime, anywhere', 'desc' => 'Study on your own schedule. With an internet-connected device, you can learn and practice whenever you want.'],
                    ['alt' => 'Easy vocabulary retention', 'title' => 'Remember vocabulary easily', 'desc' => 'Ready-to-use flashcards grouped by band level and topic help you review consistently and retain words longer.'],
                    ['alt' => 'Integrated dictionary', 'title' => 'Built-in dictionary convenience', 'desc' => 'Look up meanings instantly while learning. The integrated dictionary lets you check definitions, pronunciation, and examples without leaving your lesson.'],
                    ['alt' => 'Personalized roadmap', 'title' => 'Personalized learning roadmap', 'desc' => 'Learn exactly what you need. The system accurately assesses your level and proposes a clear, personalized plan for each stage.'],
                    ['alt' => 'Standardized lessons', 'title' => 'Standardized lessons', 'desc' => 'Cambridge-aligned lessons are clearly structured and easy to follow, helping you make steady and sustainable progress.'],
                ],
            ],
            'flow' => [
                'aria' => 'Designed for an easy learning experience',
                'titlePrefix' => 'Designed for',
                'titleHighlight' => 'an Easy Learning Experience',
                'subtitle' => 'How will you learn and practice on Edtika?',
                'steps' => [
                    ['label' => 'Placement assessment', 'description' => 'Assess your level through a quick test or a full mock exam simulation.'],
                    ['label' => 'Standardized lessons', 'description' => 'Lessons are standardized by band level, clear, and easy to follow across devices.'],
                    ['label' => 'Diverse practice exercises', 'description' => 'A wide range of skill-based exercises helps you practice consistently and improve over time.'],
                    ['label' => 'Real exam simulation room', 'description' => 'Mock exam rooms recreate real pressure so you can enter the official exam with confidence.'],
                    ['label' => 'AI scoring for Speaking & Writing', 'description' => 'AI quickly grades Speaking and Writing, highlights key errors, and suggests focused improvements.'],
                    ['label' => 'Mentor feedback for Speaking & Writing', 'description' => 'Mentors provide detailed corrections and guidance based on your personal learning roadmap.'],
                    ['label' => 'Flashcard vocabulary retention', 'description' => 'Use smart flashcards to retain vocabulary according to your study pace and target band score.'],
                ],
            ],
            'bundles' => [
                'aria' => 'Explore Edtika courses',
                'titlePrefix' => 'Explore courses from',
                'titleHighlight' => 'Edtika',
                'buy' => 'Buy',
                'view' => 'View',
                'placementCta' => 'Placement Test',
                'detailCta' => 'Course Details',
                'billing' => 'monthly billing',
            ],
            'faq' => [
                'aria' => 'EDTIKA always answers your questions',
                'titleHtml' => '<span>EDTIKA</span><span>ALWAYS</span><span>ANSWERS</span><span>ALL</span><span>YOUR</span><span>QUESTIONS!</span>',
                'panelAria' => 'Frequently asked questions list',
            ],
            'consultation' => [
                'aria' => 'Need more course consultation?',
                'title' => 'Need more consultation about the course?',
                'subtitleLine1' => 'Edtika consultants are always ready to support you.',
                'subtitleLine2' => 'Leave your details below and we will contact you as soon as possible!',
                'studentName' => 'Student full name',
                'studentNamePlaceholder' => 'Enter your full name',
                'registerRole' => 'Registrant role',
                'registerRolePlaceholder' => '-- Skip if self-registering --',
                'roleParent' => 'Parent',
                'roleTeacher' => 'Teacher',
                'roleAgent' => 'Agent',
                'roleOther' => 'Other',
                'email' => 'Email',
                'phone' => 'Phone number',
                'course' => 'Course you are interested in',
                'coursePlaceholder' => '-- Select a course --',
                'courseBasic' => 'Beginner course',
                'message' => 'Message',
                'messagePlaceholder' => 'Let us know your questions...',
                'submit' => 'Send',
            ],
            'blog' => [
                'aria' => 'Latest posts from Edtika',
                'titlePrefix' => 'Latest posts from',
                'titleHighlight' => 'Edtika',
                'subtitle' => 'Stay updated! We continuously publish new learning content and useful updates.',
                'pageAriaPrefix' => 'Page',
            ],
            'footer' => [
                'description' => 'This platform is designed to help organizations, educators, and learners manage, deliver, and track learning and training activities.',
                'supportTitle' => 'Support',
                'supportLinks' => ['Placement Test', 'Mock Tests', 'Dictionary & Flashcards'],
                'introTitle' => 'About',
                'introLinks' => ['Courses', 'FAQ', 'News'],
                'contactTitle' => 'Contact Info',
                'hotlineLabel' => 'Phone/Hotline',
                'hotlineValue' => '0987 654 321',
                'emailLabel' => 'Email',
                'emailValue' => 'contact@edtika.com',
                'addressLabel' => 'Address',
                'addressValue' => 'Ho Chi Minh City, Vietnam',
                'copyright' => 'Copyright 2026',
                'allRightsReserved' => 'All rights reserved.',
                'terms' => 'Terms & Policies',
                'privacy' => 'Privacy Policy',
            ],
            'auth' => [
                'dialogAria' => 'Log in and Sign up',
                'closeAria' => 'Close',
                'tabsAria' => 'Auth Tabs',
                'loginTab' => 'Log in',
                'registerTab' => 'Sign up',
                'loginTitle' => 'Log in to your account',
                'loginMethodsAria' => 'Login Methods',
                'emailMethod' => 'Email',
                'phoneMethod' => 'Phone',
                'password' => 'Password',
                'forgotPassword' => 'Forgot your password?',
                'noAccount' => "Don't have an account?",
                'hasAccount' => 'Already have an account?',
                'registerTitle' => 'Create a new account',
                'fullName' => 'Full name',
                'confirmPassword' => 'Confirm password',
                'sliderImageAlt' => 'Auth slider image',
            ],
        ],
    ];

    $bundleCards = [
        [
            'name' => 'BAND 6.0 - 6.5',
            'detail_url' => url('/classes/Sample-Course'),
            'price' => '1.499.000 đ',
            'billing' => $homeText[$locale]['bundles']['billing'],
            'items' => $isEnglish
                ? [
                    'Get an integrated advanced curriculum set',
                    'Listening & Reading practice with detailed explanations',
                    'Close Speaking & Writing feedback from IELTS 8.0+ teachers',
                ]
                : [
                    'Sở hữu bộ giáo trình tích hợp chuyên sâu',
                    'Luyện đề Listening & Reading có giải thích đáp án chi tiết',
                    'Được chấm chữa sát sao Speaking & Writing với giáo viên IELTS 8.0+',
                ],
        ],
        [
            'name' => 'BAND 6.0 - 6.5',
            'detail_url' => url('/classes/Sample-Course'),
            'price' => '1.499.000 đ',
            'billing' => $homeText[$locale]['bundles']['billing'],
            'items' => $isEnglish
                ? [
                    'Get an integrated advanced curriculum set',
                    'Listening & Reading practice with detailed explanations',
                    'Close Speaking & Writing feedback from IELTS 8.0+ teachers',
                ]
                : [
                    'Sở hữu bộ giáo trình tích hợp chuyên sâu',
                    'Luyện đề Listening & Reading có giải thích đáp án chi tiết',
                    'Được chấm chữa sát sao Speaking & Writing với giáo viên IELTS 8.0+',
                ],
        ],
        [
            'name' => 'BAND 6.5+',
            'detail_url' => url('/classes/Sample-Course'),
            'price' => '1.499.000 đ',
            'billing' => $homeText[$locale]['bundles']['billing'],
            'items' => $isEnglish
                ? [
                    'Get an integrated advanced curriculum set',
                    'Listening & Reading practice with detailed explanations',
                    'Close Speaking & Writing feedback from IELTS 8.0+ teachers',
                    'Personalized and tailored study planning',
                ]
                : [
                    'Sở hữu bộ giáo trình tích hợp chuyên sâu',
                    'Luyện đề Listening & Reading có giải thích đáp án chi tiết',
                    'Được chấm chữa sát sao Speaking & Writing với giáo viên IELTS 8.0+',
                    'Cá nhân hóa kế hoạch học tập một cách chuyên biệt',
                ],
        ],
        [
            'name' => 'BAND 6.0 - 6.5',
            'detail_url' => url('/classes/Sample-Course'),
            'price' => '1.499.000 đ',
            'billing' => $homeText[$locale]['bundles']['billing'],
            'items' => $isEnglish
                ? [
                    'Get an integrated advanced curriculum set',
                    'Listening & Reading practice with detailed explanations',
                    'Close Speaking & Writing feedback from IELTS 8.0+ teachers',
                ]
                : [
                    'Sở hữu bộ giáo trình tích hợp chuyên sâu',
                    'Luyện đề Listening & Reading có giải thích đáp án chi tiết',
                    'Được chấm chữa sát sao Speaking & Writing với giáo viên IELTS 8.0+',
                ],
        ],
        [
            'name' => 'BAND 6.0 - 6.5',
            'detail_url' => url('/classes/Sample-Course'),
            'price' => '1.499.000 đ',
            'billing' => $homeText[$locale]['bundles']['billing'],
            'items' => $isEnglish
                ? [
                    'Get an integrated advanced curriculum set',
                    'Listening & Reading practice with detailed explanations',
                    'Close Speaking & Writing feedback from IELTS 8.0+ teachers',
                ]
                : [
                    'Sở hữu bộ giáo trình tích hợp chuyên sâu',
                    'Luyện đề Listening & Reading có giải thích đáp án chi tiết',
                    'Được chấm chữa sát sao Speaking & Writing với giáo viên IELTS 8.0+',
                ],
        ],
    ];
    $bundleCarouselCards = array_merge($bundleCards, $bundleCards, $bundleCards);
    $activeBundleIndex = intdiv(count($bundleCarouselCards), 2);
    $faqItems = $isEnglish
        ? [
            ['question' => 'Who is this course suitable for?', 'answer' => 'The course is suitable for learners from beginner level to band 6.5+. You start with a free placement test, then the system builds a roadmap that matches your current level and target band.'],
            ['question' => 'How does the personalized roadmap work?', 'answer' => 'After your placement test, the system analyzes strengths and weaknesses in each skill, then proposes a tailored plan focusing on areas that need improvement.'],
            ['question' => 'Which devices can I use to study?', 'answer' => 'You can study on desktop, tablet, or mobile phone. With internet access, you can learn anytime and anywhere.'],
            ['question' => 'Does the course include real-test style practice?', 'answer' => 'Yes. The platform provides mock tests and practice sets that simulate real IELTS structure, timing, and difficulty.'],
            ['question' => 'How do mentors support me?', 'answer' => 'Mentors answer questions, correct your work, and provide detailed feedback so you can improve effectively.'],
            ['question' => 'Can I track my progress?', 'answer' => 'Yes. Your dashboard shows scores, progress, and skills that need improvement so you always know your next focus.'],
            ['question' => 'How long should I study daily?', 'answer' => 'It depends on your target band and current level, but 30-60 minutes per day can still be effective when following the right roadmap.'],
            ['question' => 'How long does it take to improve IELTS band score?', 'answer' => 'This depends on current level and target band. With focused practice and personalized guidance, many learners improve 0.5-1.0 band within a few months.'],
            ['question' => 'Can I start right away?', 'answer' => 'Yes. Start with a free placement test and receive a suitable study roadmap immediately.'],
        ]
        : [
            ['question' => 'Khóa học này phù hợp với trình độ nào?', 'answer' => 'Khóa học phù hợp với người học từ mất gốc đến band 6.5+. Bạn sẽ bắt đầu bằng bài test trình độ miễn phí, sau đó hệ thống xây dựng lộ trình học phù hợp với trình độ hiện tại và band mục tiêu của bạn.'],
            ['question' => 'Lộ trình học cá nhân hoá hoạt động như thế nào?', 'answer' => 'Sau khi làm bài test đầu vào, hệ thống sẽ phân tích điểm mạnh và điểm yếu của bạn ở từng kỹ năng. Từ đó, nền tảng đề xuất lộ trình học riêng, tập trung vào những kỹ năng cần cải thiện để giúp bạn đạt band mục tiêu nhanh hơn.'],
            ['question' => 'Tôi có thể học trên những thiết bị nào?', 'answer' => 'Bạn có thể học trên máy tính, tablet hoặc điện thoại. Chỉ cần kết nối internet, bạn có thể truy cập vào nền tảng và học mọi lúc mọi nơi.'],
            ['question' => 'Khóa học có luyện đề giống thi thật không?', 'answer' => 'Có. Nền tảng cung cấp hệ thống mock test và bài luyện tập mô phỏng kỳ thi IELTS thật về cấu trúc đề, thời gian và độ khó. Giúp bạn làm quen với áp lực phòng thi và cải thiện kỹ năng làm bài.'],
            ['question' => 'Mentor sẽ hỗ trợ như thế nào?', 'answer' => 'Mentor sẽ giải đáp thắc mắc, chữa bài và đưa ra feedback chi tiết để giúp bạn cải thiện kỹ năng. Bạn có thể đối thoại trực tiếp trong quá trình học khi gặp khó khăn.'],
            ['question' => 'Tôi có thể theo dõi tiến bộ học tập của mình không?', 'answer' => 'Có. Hệ thống dashboard hiển thị điểm số, tiến độ học tập và kỹ năng cần cải thiện, giúp bạn biết mình đang tiến bộ đến đâu và cần tập trung vào phần nào tiếp theo.'],
            ['question' => 'Mỗi ngày cần học bao lâu để đạt hiệu quả?', 'answer' => 'Thời gian học phụ thuộc vào mục tiêu band và trình độ hiện tại. Tuy nhiên, chỉ cần 30-60 phút học mỗi ngày, bạn vẫn có thể cải thiện trình độ nếu học theo đúng lộ trình.'],
            ['question' => 'Tôi cần học bao lâu để tăng band IELTS?', 'answer' => 'Thời gian phụ thuộc vào trình độ hiện tại và band mục tiêu. Với lộ trình học cá nhân hoá và luyện tập đúng trọng tâm, nhiều học viên có thể cải thiện 0.5-1 band trong vài tháng.'],
            ['question' => 'Tôi có thể bắt đầu học ngay không?', 'answer' => 'Có. Bạn có thể bắt đầu bằng bài test trình độ miễn phí để hệ thống đánh giá band hiện tại và đề xuất lộ trình học phù hợp.'],
        ];

    $blogArticles = $isEnglish
        ? [
            ['thumbnail' => asset('store/icons/image.png'), 'author' => 'Tracy Tran', 'date' => '04/04/2024', 'title' => 'How to find the perfect mentor for your learning journey?'],
            ['thumbnail' => asset('store/icons/image.png'), 'author' => 'Tracy Tran', 'date' => '04/04/2024', 'title' => '11 tips to study more effectively and efficiently.'],
            ['thumbnail' => asset('store/icons/image.png'), 'author' => 'Tracy Tran', 'date' => '04/04/2024', 'title' => 'Unlock your potential in school and in life.'],
        ]
        : [
            ['thumbnail' => asset('store/icons/image.png'), 'author' => 'Tracy Tran', 'date' => '04/04/2024', 'title' => 'Làm thế nào để tìm được Mentor hoàn hảo cho hành trình học tập của bạn?'],
            ['thumbnail' => asset('store/icons/image.png'), 'author' => 'Tracy Tran', 'date' => '04/04/2024', 'title' => '11 lời khuyên giúp bạn học tập nâng cao suất và hiệu quả.'],
            ['thumbnail' => asset('store/icons/image.png'), 'author' => 'Tracy Tran', 'date' => '04/04/2024', 'title' => 'Khái phá tiềm năng của bạn trong học đường và cuộc sống.'],
        ];

    $flowSteps = $homeText[$locale]['flow']['steps'];
    $whyCards = $homeText[$locale]['why']['cards'];
    $t = $homeText[$locale];

    $authThemeSettings = getThemeAuthenticationPagesSettings();
    $authSliderBackground = (!empty($authThemeSettings) and !empty($authThemeSettings['slider_background_image'])) ? $authThemeSettings['slider_background_image'] : null;
    $authSliderSlides = (!empty($authThemeSettings) and !empty($authThemeSettings['slider_contents']) and is_array($authThemeSettings['slider_contents']))
        ? array_values($authThemeSettings['slider_contents'])
        : [];

    if (empty($authSliderSlides)) {
        $authSliderSlides = [
            [
                'image' => asset('store/icons/—Pngtree—abstract purple line wave background_5542852 1.png'),
                'title' => '',
                'subtitle' => '',
            ],
        ];
    }

    $authSliderSlides = array_slice($authSliderSlides, 0, 3);

    while (count($authSliderSlides) < 3) {
        $authSliderSlides[] = $authSliderSlides[count($authSliderSlides) - 1];
    }
@endphp

@push('styles_top')
    <style>
        :root {
            --edtika-primary: #511D99;
            --edtika-text: #101014;
            --edtika-muted: #6f6f76;
            --edtika-page-bg-1: #ecebf3;
            --edtika-page-bg-2: #eaf4f1;
            --edtika-blob-start: rgba(134, 34, 255, 0.34);
            --edtika-blob-end: rgba(60, 225, 97, 0.34);
        }

        .edtika-homepage {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--edtika-page-bg-1) 0%, #edf0f6 62%, var(--edtika-page-bg-2) 100%);
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
            overflow: hidden;
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
        }

        #app {
            width: 100% !important;
            max-width: none !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }

        #app > .cart-drawer,
        #app > .cart-drawer-mask,
        body > .purchase-notifications-card {
            position: fixed !important;
        }

        .edtika-homepage__hero::before,
        .edtika-homepage__hero::after,
        .edtika-why::before,
        .edtika-why::after,
        .edtika-flow::before,
        .edtika-flow::after,
        .edtika-bundles::before,
        .edtika-bundles::after,
        .edtika-faq::before,
        .edtika-faq::after,
        .edtika-consultation::before,
        .edtika-consultation::after,
        .edtika-footer::before,
        .edtika-footer::after {
            opacity: 0.62;
        }

        .edtika-homepage__container {
            width: 100%;
            max-width: 1312px;
            margin: 0 auto;
            padding: 0 28px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            flex: 1 0 auto;
        }

        .edtika-homepage__content {
            min-height: 0;
            flex: 1 0 auto;
        }

        .edtika-homepage__header {
            height: 106px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .edtika-homepage__brand {
            margin: 0;
            font-size: 54px;
            line-height: 1;
            font-weight: 900;
            letter-spacing: -0.02em;
            color: var(--edtika-primary);
        }

        .edtika-homepage__nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            flex: 1 1 auto;
            margin: 0 24px;
        }

        .edtika-homepage__nav-link {
            color: var(--edtika-text);
            font-size: 18px;
            line-height: 1.2;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .edtika-homepage__nav-link:hover,
        .edtika-homepage__nav-link:focus {
            color: var(--edtika-text);
            text-decoration: none;
            opacity: 0.86;
        }

        .edtika-homepage__nav-link.is-active {
            font-weight: 900;
            opacity: 1;
            color: #111118;
        }

        .edtika-homepage__actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .edtika-lang-switch {
            position: relative;
            width: 80px;
            height: 44px;
            border: 2px solid #d8cdea;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 8px 18px rgba(33, 24, 56, 0.12);
            overflow: hidden;
            padding-left: 8px;
        }

        .edtika-lang-switch__form {
            display: block;
            height: 100%;
        }

        .edtika-lang-switch__thumb {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--edtika-primary);
            transition: left 0.2s ease, right 0.2s ease;
            z-index: 2;
        }

        .edtika-lang-switch.is-next-vie .edtika-lang-switch__thumb {
            left: auto;
            right: 5px;
        }

        .edtika-lang-switch__button {
            width: 100%;
            height: 100%;
            border: 0;
            background: transparent;
            color: var(--edtika-primary);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            z-index: 3;
            letter-spacing: 0.01em;
        }

        .edtika-lang-switch.is-next-eng .edtika-lang-switch__button {
            justify-content: flex-end;
            padding-right: 9px;
            padding-left: 44px;
        }

        .edtika-lang-switch.is-next-vie .edtika-lang-switch__button {
            justify-content: flex-start;
            padding-left: 8px;
            padding-right: 48px;
        }

        .edtika-login-btn,
        .edtika-cta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 50px;
            border-radius: 999px;
            background: var(--edtika-primary);
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            border: 0;
            transition: opacity 0.2s ease;
        }

        .edtika-login-btn {
            padding: 0 24px;
        }

        .edtika-user-avatar-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            padding: 0;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 18px rgba(33, 24, 56, 0.18);
            background: #fff;
            flex-shrink: 0;
        }

        .edtika-user-avatar-btn__image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .edtika-cta-btn {
            height: 54px;
            padding: 0 44px;
            font-size: 20px;
            font-weight: 700;
        }

        .edtika-login-btn:hover,
        .edtika-login-btn:focus,
        .edtika-cta-btn:hover,
        .edtika-cta-btn:focus {
            color: #fff;
            text-decoration: none;
            opacity: 0.92;
        }

        .edtika-homepage__hero {
            position: relative;
            isolation: isolate;
            text-align: center;
            padding: 56px 0 88px;
        }

        .edtika-homepage__hero::before,
        .edtika-homepage__hero::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: -1;
            filter: blur(56px);
            background: linear-gradient(132deg, var(--edtika-blob-start), var(--edtika-blob-end));
        }

        .edtika-homepage__hero::before {
            width: 470px;
            height: 320px;
            left: -180px;
            top: -10px;
            border-radius: 52% 48% 61% 39% / 38% 46% 54% 62%;
            transform: rotate(-14deg);
        }

        .edtika-homepage__hero::after {
            width: 500px;
            height: 300px;
            right: -190px;
            top: 32px;
            border-radius: 42% 58% 38% 62% / 52% 37% 63% 48%;
            transform: rotate(10deg);
        }

        .edtika-homepage__headline {
            margin: 0;
            color: var(--edtika-text);
            font-size: 86px;
            line-height: 1.16;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .edtika-homepage__headline-highlight {
            color: var(--edtika-primary);
        }

        .edtika-homepage__subtext {
            max-width: 980px;
            margin: 40px auto 0;
            color: var(--edtika-muted);
            font-size: 20px;
            line-height: 1.5;
            font-weight: 600;
        }

        .edtika-homepage__cta-wrap {
            margin-top: 30px;
        }

        .edtika-why {
            position: relative;
            isolation: isolate;
            padding: 180px 0 84px;
        }

        .edtika-why::before,
        .edtika-why::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: -1;
            filter: blur(64px);
            background: linear-gradient(138deg, var(--edtika-blob-start), var(--edtika-blob-end));
        }

        .edtika-why::before {
            width: 620px;
            height: 420px;
            left: -220px;
            top: 120px;
            border-radius: 61% 39% 48% 52% / 57% 36% 64% 43%;
            transform: rotate(-11deg);
        }

        .edtika-why::after {
            width: 560px;
            height: 410px;
            right: -200px;
            top: 210px;
            border-radius: 43% 57% 65% 35% / 39% 53% 47% 61%;
            transform: rotate(16deg);
        }

        .edtika-why__title {
            margin: 0;
            text-align: center;
            color: var(--edtika-text);
            font-size: 52px;
            line-height: 1.2;
            font-weight: 900;
            letter-spacing: -0.01em;
        }

        .edtika-why__title span {
            color: var(--edtika-primary);
        }

        .edtika-why__grid {
            margin-top: 64px;
            display: grid;
            grid-template-columns: repeat(3, 342px);
            justify-content: center;
            column-gap: 54px;
            row-gap: 44px;
            align-items: start;
        }

        .edtika-why__card {
            position: relative;
            border-radius: 18px;
            background: rgba(212, 211, 254, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 9px 18px rgba(23, 15, 44, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 78px 24px 22px;
            min-height: 246px;
            transition: background-color 0.22s ease, box-shadow 0.22s ease;
        }

        .edtika-why__card:hover {
            background: rgba(212, 211, 254, 0.34);
            box-shadow: 0 14px 24px rgba(23, 15, 44, 0.24), inset 0 1px 0 rgba(255, 255, 255, 0.62);
        }

        .edtika-why__icon {
            position: absolute;
            right: 22px;
            top: -40px;
            transform: scale(var(--icon-scale, 1));
            transform-origin: top right;
            width: 112px;
            height: 112px;
            object-fit: contain;
            filter: drop-shadow(0 12px 16px rgba(83, 48, 145, 0.32));
            pointer-events: none;
            user-select: none;
        }

        .edtika-why__card-title {
            margin: 0;
            color: #1a1a22;
            font-size: 20px;
            line-height: 1.28;
            font-weight: 800;
        }

        .edtika-why__card-desc {
            margin: 10px 0 0;
            color: #646375;
            font-size: 14px;
            line-height: 1.52;
            font-weight: 600;
        }

        .edtika-why__card--cost {
            grid-column: 1;
            grid-row: 1;
            --icon-scale: 1.2;
        }

        .edtika-why__card--practice {
            grid-column: 3;
            grid-row: 1;
            --icon-scale: 1.2;
        }

        .edtika-why__card--mentor {
            grid-column: 2;
            grid-row: 2;
            margin-top: -116px;
            --icon-scale: 1.08;
        }

        .edtika-why__card--anywhere {
            grid-column: 1;
            grid-row: 2;
            margin-top: 26px;
            margin-left: -26px;
            --icon-scale: 1;
        }

        .edtika-why__card--vocab {
            grid-column: 3;
            grid-row: 2;
            margin-top: 26px;
            margin-right: -26px;
            --icon-scale: 1.14;
        }

        .edtika-why__card--dictionary {
            grid-column: 1;
            grid-row: 3;
            margin-top: 40px;
            --icon-scale: 1.16;
        }

        .edtika-why__card--roadmap {
            grid-column: 2;
            grid-row: 3;
            margin-top: -14px;
            --icon-scale: 1.12;
        }

        .edtika-why__card--lecture {
            grid-column: 3;
            grid-row: 3;
            margin-top: 40px;
            --icon-scale: 1.34;
        }

        .edtika-flow {
            position: relative;
            isolation: isolate;
            padding: 92px 0 96px;
        }

        .edtika-flow::before,
        .edtika-flow::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: -1;
            filter: blur(58px);
            background: linear-gradient(126deg, var(--edtika-blob-start), var(--edtika-blob-end));
        }

        .edtika-flow::before {
            width: 560px;
            height: 330px;
            left: -160px;
            top: 74px;
            border-radius: 56% 44% 60% 40% / 42% 58% 52% 48%;
            transform: rotate(-10deg);
        }

        .edtika-flow::after {
            width: 620px;
            height: 360px;
            right: -180px;
            bottom: 24px;
            border-radius: 45% 55% 36% 64% / 54% 39% 61% 46%;
            transform: rotate(11deg);
        }

        .edtika-flow__title {
            margin: 0;
            text-align: center;
            color: var(--edtika-text);
            font-size: 42px;
            line-height: 1.2;
            font-weight: 900;
            letter-spacing: -0.01em;
        }

        .edtika-flow__title span {
            color: var(--edtika-primary);
        }

        .edtika-flow__subtitle {
            margin: 20px 0 0;
            text-align: center;
            color: #666672;
            font-size: 22px;
            line-height: 1.38;
            font-weight: 500;
        }

        .edtika-flow__layout {
            margin-top: 44px;
            display: grid;
            grid-template-columns: 286px 1fr;
            column-gap: 30px;
            align-items: stretch;
        }

        .edtika-flow__steps {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            gap: 11px;
            padding-top: 2px;
            padding-right: 30px;
        }

        .edtika-flow__step {
            position: relative;
            z-index: 2;
            width: 100%;
            min-height: 60px;
            border: 1px solid rgba(255, 255, 255, 0.46);
            border-radius: 18px;
            background: rgba(212, 211, 254, 0.22);
            box-shadow: 0 10px 18px rgba(28, 19, 50, 0.14);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: #111119;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 14px;
            text-align: left;
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease, width 0.2s ease, margin-right 0.2s ease;
        }

        .edtika-flow__step:hover {
            background: rgba(212, 211, 254, 0.34);
            transform: translateY(-1px);
        }

        .edtika-flow__step.is-active {
            z-index: 4;
            background: rgba(255, 255, 255, 0.16);
            box-shadow: 0 16px 24px rgba(28, 19, 50, 0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            margin-right: -30px;
            width: calc(100% + 30px);
            transform: translateY(-2px);
        }

        .edtika-flow__step.is-active::after {
            display: none;
        }

        .edtika-flow__step-no {
            color: var(--edtika-primary);
            font-size: 24px;
            line-height: 1;
            font-weight: 900;
            min-width: 24px;
            text-align: center;
        }

        .edtika-flow__step-label {
            font-size: 15px;
            line-height: 1.28;
            font-weight: 700;
            color: #121218;
        }

        .edtika-flow__panel {
            position: relative;
            z-index: 2;
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.42);
            background: rgba(255, 255, 255, 0.16);
            box-shadow: 0 18px 34px rgba(27, 18, 49, 0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 12px 16px 14px;
            height: calc(7 * 60px + 6 * 11px + 2px);
            min-height: 0;
            display: flex;
            flex-direction: column;
        }

        .edtika-flow__laptop {
            position: relative;
            width: 100%;
            max-width: 540px;
            margin: 8px auto 0;
            filter: drop-shadow(0 22px 26px rgba(20, 20, 32, 0.2));
        }

        .edtika-flow__laptop-screen {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            border: 7px solid #0c0c0f;
            border-bottom-width: 9px;
            border-radius: 22px 22px 10px 10px;
            overflow: hidden;
            background: linear-gradient(128deg, #f4f4f7 4%, #c0c3dc 34%, #8f89d8 58%, #dad8e8 82%);
        }

        .edtika-flow__laptop-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 18% 112%, rgba(255, 255, 255, 0.82) 0%, rgba(255, 255, 255, 0) 38%), radial-gradient(circle at 108% 20%, rgba(255, 255, 255, 0.84) 0%, rgba(255, 255, 255, 0) 40%);
            pointer-events: none;
        }

        .edtika-flow__laptop-screen.is-mode-2 {
            background: linear-gradient(130deg, #eceefa 6%, #c7d4eb 40%, #aba4df 66%, #eceaf6 90%);
        }

        .edtika-flow__laptop-screen.is-mode-3 {
            background: linear-gradient(122deg, #eef3ff 8%, #cfdae8 38%, #9d94dc 60%, #d5d4f0 90%);
        }

        .edtika-flow__laptop-screen.is-mode-4 {
            background: linear-gradient(124deg, #f8f4fb 8%, #d2d8ea 42%, #958fd9 64%, #f2ebf8 90%);
        }

        .edtika-flow__laptop-screen.is-mode-5 {
            background: linear-gradient(122deg, #f2f5ff 6%, #d6deee 37%, #9491d8 62%, #ece9fa 90%);
        }

        .edtika-flow__laptop-screen.is-mode-6 {
            background: linear-gradient(124deg, #f3f4fc 10%, #c8d6e8 42%, #8a85cf 64%, #ebe8f5 90%);
        }

        .edtika-flow__laptop-screen.is-mode-7 {
            background: linear-gradient(126deg, #f6f3fb 10%, #ccd7ea 44%, #8e86d4 66%, #f1eef8 90%);
        }

        .edtika-flow__laptop-base {
            position: relative;
            width: calc(100% + 70px);
            margin-left: -35px;
            margin-top: -2px;
            height: 18px;
            border-radius: 0 0 20px 20px;
            background: linear-gradient(180deg, #d8d8d8 0%, #a8a8a8 45%, #8d8d8d 100%);
        }

        .edtika-flow__laptop-base::after {
            content: "";
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
            width: 120px;
            height: 8px;
            border-radius: 0 0 10px 10px;
            background: #7f7f7f;
        }

        .edtika-flow__description {
            margin: 20px 0 0;
            text-align: center;
            color: #111116;
            font-size: 15px;
            line-height: 1.42;
            font-weight: 500;
            padding: 0 18px;
        }

        .edtika-bundles {
            position: relative;
            isolation: isolate;
            padding: 50px 0 82px;
        }

        .edtika-bundles::before,
        .edtika-bundles::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: -1;
            filter: blur(66px);
            background: linear-gradient(134deg, var(--edtika-blob-start), var(--edtika-blob-end));
        }

        .edtika-bundles::before {
            width: 650px;
            height: 340px;
            left: -220px;
            top: 130px;
            border-radius: 60% 40% 44% 56% / 52% 45% 55% 48%;
            transform: rotate(-9deg);
        }

        .edtika-bundles::after {
            width: 650px;
            height: 360px;
            right: -220px;
            top: 110px;
            border-radius: 39% 61% 58% 42% / 47% 38% 62% 53%;
            transform: rotate(12deg);
        }

        .edtika-bundles__title {
            margin: 0;
            text-align: center;
            color: #12121a;
            font-size: 54px;
            line-height: 1.08;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .edtika-bundles__title span {
            color: var(--edtika-primary);
        }

        .edtika-bundles__viewport {
            margin-top: 30px;
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            overflow-x: auto;
            overflow-y: visible;
            padding: 10px 0 24px;
            scrollbar-width: none;
            box-shadow: none;
        }

        .edtika-bundles__viewport::-webkit-scrollbar {
            display: none;
        }

        .edtika-bundles__track {
            width: max-content;
            display: flex;
            align-items: stretch;
            gap: 22px;
            padding: 0;
            box-shadow: none;
        }

        .edtika-bundles__card {
            width: 312px;
            min-width: 312px;
            min-height: 586px;
            display: flex;
            flex-direction: column;
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: rgba(212, 211, 254, 0.08);
            box-shadow: 0 12px 18px rgba(31, 21, 56, 0.14);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            overflow: hidden;
            scroll-snap-align: center;
            cursor: pointer;
            transition: transform 0.22s ease, background-color 0.22s ease, box-shadow 0.22s ease;
        }

        .edtika-bundles__card.is-active {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 20px 30px rgba(31, 21, 56, 0.22);
        }

        .edtika-bundles__card-top {
            height: 196px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .edtika-bundles__card-top img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            opacity: 0.82;
        }

        .edtika-bundles__card-body {
            padding: 18px 18px 14px;
            display: flex;
            flex-direction: column;
            height: 100%;
            background: rgba(255, 255, 255, 0.02);
        }

        .edtika-bundles__name {
            margin: 0;
            color: #171822;
            font-size: 28px;
            line-height: 1.16;
            font-weight: 900;
            letter-spacing: -0.01em;
            text-align: center;
        }

        .edtika-bundles__name small {
            font-size: 0.8em;
        }

        .edtika-bundles__features {
            list-style: none;
            margin: 12px 0 10px;
            padding: 0;
            display: grid;
            gap: 8px;
        }

        .edtika-bundles__features li {
            position: relative;
            padding-left: 18px;
            color: #2f313a;
            font-size: 13px;
            line-height: 1.22;
            font-weight: 500;
        }

        .edtika-bundles__features li::before {
            content: "\2713";
            position: absolute;
            left: 0;
            top: 0;
            color: var(--edtika-primary);
            font-size: 12px;
            font-weight: 900;
        }

        .edtika-bundles__bottom {
            margin-top: auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
            column-gap: 8px;
        }

        .edtika-bundles__price-wrap {
            min-width: 0;
            flex: 1 1 auto;
        }

        .edtika-bundles__price {
            margin-top: 0;
            color: #1a1a23;
            font-size: 18px;
            line-height: 0.98;
            font-weight: 900;
            letter-spacing: -0.02em;
            white-space: nowrap;
        }

        .edtika-bundles__billing {
            margin-top: 4px;
            color: #454750;
            font-size: 10px;
            line-height: 1.12;
            font-weight: 500;
            white-space: nowrap;
        }

        .edtika-bundles__actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            flex-shrink: 0;
            align-self: end;
        }

        .edtika-bundles__btn {
            min-width: 74px;
            height: 32px;
            padding: 0 10px;
            border-radius: 999px;
            border: 2px solid var(--edtika-primary);
            font-size: 14px;
            line-height: 1;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .edtika-bundles__btn--buy {
            color: var(--edtika-primary);
            background: transparent;
        }

        .edtika-bundles__btn--buy:hover,
        .edtika-bundles__btn--buy:focus {
            background: var(--edtika-primary);
            color: #fff;
            text-decoration: none;
        }

        .edtika-bundles__btn--view {
            color: #fff;
            background: var(--edtika-primary);
        }

        .edtika-bundles__btn--view:hover,
        .edtika-bundles__btn--view:focus {
            background: #fff;
            color: var(--edtika-primary);
            text-decoration: none;
        }

        .edtika-bundles__footer-actions {
            margin-top: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 22px;
        }

        .edtika-bundles__cta {
            min-width: 340px;
            height: 58px;
            border-radius: 999px;
            border: 2px solid var(--edtika-primary);
            background: transparent;
            color: var(--edtika-primary);
            font-size: 24px;
            line-height: 1;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .edtika-bundles__cta:hover,
        .edtika-bundles__cta:focus {
            background: var(--edtika-primary);
            color: #fff;
            text-decoration: none;
        }

        .edtika-faq {
            position: relative;
            isolation: isolate;
            padding: 18px 0 88px;
        }

        .edtika-faq::before,
        .edtika-faq::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: -1;
            filter: blur(62px);
            background: linear-gradient(130deg, var(--edtika-blob-start), var(--edtika-blob-end));
        }

        .edtika-faq::before {
            width: 560px;
            height: 360px;
            left: -180px;
            top: 40px;
            border-radius: 51% 49% 39% 61% / 60% 43% 57% 40%;
            transform: rotate(-10deg);
        }

        .edtika-faq::after {
            width: 560px;
            height: 340px;
            right: -170px;
            bottom: 34px;
            border-radius: 40% 60% 58% 42% / 35% 58% 42% 65%;
            transform: rotate(14deg);
        }

        .edtika-faq__layout {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            gap: 100px;
            align-items: start;
        }

        .edtika-faq__title {
            margin: 0;
            display: block;
            text-align: left;
            font-size: 60px;
            line-height: 1.12;
            font-weight: 900;
            letter-spacing: -0.02em;
            color: #111217;
            text-transform: uppercase;
            white-space: normal;
        }

        .edtika-faq__title span {
            display: block;
            width: 100%;
            text-align: left;
            white-space: nowrap;
        }

        .edtika-faq__title span:first-child {
            color: var(--edtika-primary);
        }

        .edtika-faq__panel {
            border-radius: 34px;
            border: 1px solid rgba(255, 255, 255, 0.44);
            background: rgba(212, 211, 254, 0.23);
            box-shadow: 0 20px 34px rgba(29, 20, 52, 0.16);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            max-height: 656px;
            padding: 40px;
            overflow: hidden;
            display: flex;
        }

        .edtika-faq__scroll {
            flex: 1 1 auto;
            min-height: 0;
            max-height: calc(656px - 80px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #511D99 transparent;
            padding-right: 20px;
            padding-bottom: 40px;
            box-sizing: border-box;
            scrollbar-gutter: stable;
        }

        .edtika-faq__scroll::-webkit-scrollbar {
            width: 8px;
        }

        .edtika-faq__scroll::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 999px;
        }

        .edtika-faq__scroll::-webkit-scrollbar-thumb {
            background: #511D99;
            border-radius: 999px;
        }

        .edtika-faq__item {
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.5);
            margin-bottom: 14px;
            overflow: hidden;
        }

        .edtika-faq__item:last-child {
            margin-bottom: 0;
        }

        .edtika-faq__question {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: center;
            font-size: 17px;
            line-height: 1.35;
            font-weight: 800;
            color: #111217;
            padding: 18px 50px 18px 20px;
            cursor: pointer;
            position: relative;
        }

        .edtika-faq__question::after {
            content: "\2304";
            position: absolute;
            right: 24px;
            top: 50%;
            transform: translateY(-50%) rotate(180deg);
            font-size: 20px;
            line-height: 1;
            color: #111217;
            transition: transform 0.2s ease;
        }

        .edtika-faq__answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.24s ease;
        }

        .edtika-faq__answer-inner {
            border-top: 1px solid rgba(167, 163, 204, 0.5);
            padding: 14px 26px 16px;
            text-align: center;
            font-size: 14px;
            line-height: 1.36;
            font-weight: 600;
            color: #50505a;
        }

        .edtika-faq__item.is-open .edtika-faq__question::after {
            transform: translateY(-50%) rotate(0deg);
        }

        .edtika-faq__item.is-open .edtika-faq__answer {
            max-height: 280px;
        }

        /* Consultation Form Section */
        .edtika-consultation {
            position: relative;
            isolation: isolate;
            padding: 80px 20px;
        }

        .edtika-consultation::before,
        .edtika-consultation::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: -1;
            filter: blur(60px);
            background: linear-gradient(129deg, var(--edtika-blob-start), var(--edtika-blob-end));
        }

        .edtika-consultation::before {
            width: 540px;
            height: 300px;
            left: -160px;
            top: 80px;
            border-radius: 63% 37% 42% 58% / 52% 36% 64% 48%;
            transform: rotate(-12deg);
        }

        .edtika-consultation::after {
            width: 600px;
            height: 340px;
            right: -190px;
            top: 116px;
            border-radius: 44% 56% 61% 39% / 41% 59% 41% 59%;
            transform: rotate(10deg);
        }

        .edtika-consultation__container {
            max-width: 100%;
            margin: 0 auto;
        }

        .edtika-consultation__title {
            margin: 0 0 24px;
            color: var(--edtika-text);
            font-size: 48px;
            line-height: 1.25;
            font-weight: 900;
            letter-spacing: -0.01em;
            text-align: center;
        }

        .edtika-consultation__subtitle {
            margin: 0 0 48px;
            color: var(--edtika-muted);
            font-size: 18px;
            line-height: 1.5;
            font-weight: 600;
            text-align: center;
        }

        .edtika-consultation__panel {
            background: rgba(212, 211, 254, 0.23);
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.46);
            box-shadow: 0 10px 18px rgba(28, 19, 50, 0.14);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 48px 200px;
            width: 100%;
        }

        .edtika-consultation__form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .edtika-consultation__field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .edtika-consultation__label {
            color: var(--edtika-text);
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
        }

        .edtika-consultation__input,
        .edtika-consultation__select,
        .edtika-consultation__textarea {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            padding: 14px 20px;
            color: var(--edtika-text);
            font-size: 16px;
            line-height: 1.5;
            font-family: inherit;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .edtika-consultation__input::placeholder,
        .edtika-consultation__select::placeholder,
        .edtika-consultation__textarea::placeholder {
            color: rgba(16, 16, 20, 0.5);
        }

        .edtika-consultation__input:focus,
        .edtika-consultation__select:focus,
        .edtika-consultation__textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.7);
            border-color: rgba(0, 0, 0, 0.3);
        }

        .edtika-consultation__row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .edtika-consultation__textarea {
            resize: vertical;
            min-height: 120px;
            padding: 14px 20px;
        }

        .edtika-consultation__submit {
            align-self: center;
            margin-top: 24px;
            height: 54px;
            padding: 0 44px;
            background: #511D99;
            color: #fff;
            border: 0;
            border-radius: 999px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s ease, background 0.2s ease;
        }

        .edtika-consultation__submit:hover,
        .edtika-consultation__submit:focus {
            opacity: 0.92;
            outline: none;
        }

        /* Blog Articles Section */
        .edtika-blog {
            padding: 80px 20px;
        }

        .edtika-blog__container {
            max-width: 100%;
            margin: 0 auto;
        }

        .edtika-blog__title {
            margin: 0 0 24px;
            color: var(--edtika-text);
            font-size: 48px;
            line-height: 1.25;
            font-weight: 900;
            letter-spacing: -0.01em;
            text-align: center;
        }

        .edtika-blog__title-highlight {
            color: var(--edtika-primary);
        }

        .edtika-blog__subtitle {
            margin: 0 0 48px;
            color: var(--edtika-muted);
            font-size: 18px;
            line-height: 1.5;
            font-weight: 600;
            text-align: center;
        }

        .edtika-blog__grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .edtika-blog__card {
            background: rgba(212, 211, 254, 0.2);
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.46);
            box-shadow: 0 10px 18px rgba(28, 19, 50, 0.14);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .edtika-blog__card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 24px rgba(28, 19, 50, 0.18);
        }

        .edtika-blog__thumbnail {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
        }

        .edtika-blog__content {
            padding: 24px 20px;
        }

        .edtika-blog__meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .edtika-blog__author {
            color: var(--edtika-text);
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
        }

        .edtika-blog__date {
            color: var(--edtika-muted);
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .edtika-blog__date::before {
            content: '📅';
            font-size: 12px;
        }

        .edtika-blog__article-title {
            margin: 0;
            color: var(--edtika-text);
            font-size: 18px;
            font-weight: 700;
            line-height: 1.4;
            letter-spacing: -0.005em;
        }

        .edtika-blog__pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 48px;
        }

        .edtika-blog__pagination-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(212, 211, 254, 0.4);
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            border: none;
            padding: 0;
        }

        .edtika-blog__pagination-dot:hover {
            background: rgba(212, 211, 254, 0.6);
            transform: scale(1.1);
        }

        .edtika-blog__pagination-dot.is-active {
            background: var(--edtika-primary);
            width: 32px;
            border-radius: 6px;
            transform: scale(1);
        }

        .edtika-auth-modal {
            position: fixed;
            inset: 0;
            z-index: 1200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(16, 16, 20, 0.38);
        }

        .edtika-auth-modal.is-open {
            display: flex;
        }

        .edtika-auth-modal__dialog {
            width: min(1180px, 100%);
            height: min(734px, calc(100vh - 40px));
            overflow: hidden;
            border-radius: 36px;
            background: rgba(212, 211, 254, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.46);
            box-shadow: 0 24px 56px rgba(28, 19, 50, 0.24);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: relative;
        }

        .edtika-auth-modal__close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 0;
            background: rgba(81, 29, 153, 0.18);
            color: #2e1454;
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
            z-index: 3;
        }

        .edtika-auth-modal__content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            height: 100%;
            min-height: 0;
        }

        .edtika-auth-modal__form-side {
            padding: 72px 60px 52px;
            overflow-y: auto;
            min-height: 0;
            max-height: 100%;
        }

        .edtika-auth-modal__tabs {
            display: inline-flex;
            gap: 8px;
            padding: 4px;
            border-radius: 999px;
            border: 1px solid rgba(81, 29, 153, 0.2);
            background: rgba(255, 255, 255, 0.36);
            margin-bottom: 28px;
        }

        .edtika-auth-modal__tab {
            border: 0;
            border-radius: 999px;
            min-width: 132px;
            height: 38px;
            padding: 0 18px;
            font-weight: 700;
            font-size: 14px;
            color: #2a2a33;
            background: transparent;
            cursor: pointer;
        }

        .edtika-auth-modal__tab.is-active {
            color: #fff;
            background: #511D99;
        }

        .edtika-auth-pane {
            display: none;
        }

        .edtika-auth-pane.is-active {
            display: block;
        }

        .edtika-auth-pane__title {
            margin: 0 0 24px;
            font-size: 42px;
            line-height: 1.18;
            color: var(--edtika-text);
            font-weight: 900;
        }

        .edtika-auth-methods {
            display: flex;
            gap: 6px;
            padding: 4px;
            border: 1px solid rgba(81, 29, 153, 0.24);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.34);
            margin-bottom: 22px;
        }

        .edtika-auth-method {
            flex: 1;
            height: 40px;
            border-radius: 999px;
            border: 0;
            background: transparent;
            color: #2f2f38;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .edtika-auth-method.is-active {
            background: #511D99;
            color: #fff;
        }

        .edtika-auth-role-switch {
            display: flex;
            gap: 4px;
            padding: 4px;
            border: 1px solid rgba(81, 29, 153, 0.28);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.34);
        }

        .edtika-auth-role-option {
            flex: 1;
            margin: 0;
            cursor: pointer;
        }

        .edtika-auth-role-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .edtika-auth-role-option span {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            border-radius: 999px;
            color: #2f2f38;
            font-size: 14px;
            font-weight: 700;
            transition: background-color .2s ease, color .2s ease;
        }

        .edtika-auth-role-option input:checked + span {
            background: #511D99;
            color: #fff;
        }

        .edtika-auth-field {
            margin-bottom: 14px;
        }

        .edtika-auth-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #2f2f38;
        }

        .edtika-auth-input-wrap {
            position: relative;
        }

        .edtika-auth-input {
            width: 100%;
            height: 44px;
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.16);
            background: rgba(255, 255, 255, 0.5);
            padding: 0 14px;
            font-size: 15px;
            color: #1f1f27;
        }

        .edtika-auth-input:focus {
            outline: none;
            border-color: rgba(81, 29, 153, 0.45);
            background: rgba(255, 255, 255, 0.68);
        }

        .edtika-auth-input-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9797a3;
            font-size: 16px;
            pointer-events: none;
        }

        .edtika-auth-forgot {
            display: block;
            width: 100%;
            text-align: right;
            margin-top: 2px;
            margin-bottom: 18px;
            color: #2f2f38;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .edtika-auth-submit {
            width: 100%;
            height: 48px;
            border: 0;
            border-radius: 999px;
            background: #511D99;
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
        }

        .edtika-auth-check {
            margin: 4px 0 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            font-size: 14px;
            color: #2f2f38;
        }

        .edtika-auth-check input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .edtika-auth-check__box {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 1px solid rgba(81, 29, 153, 0.4);
            background: rgba(255, 255, 255, 0.6);
            color: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 900;
            transition: background-color .2s ease, border-color .2s ease, color .2s ease;
        }

        .edtika-auth-check input:checked + .edtika-auth-check__box {
            background: #511D99;
            border-color: #511D99;
            color: #fff;
        }

        .edtika-auth-check__text strong {
            font-weight: 800;
            color: #15151d;
        }

        .edtika-auth-switch-note {
            margin-top: 20px;
            text-align: center;
            color: #6b6b76;
            font-size: 14px;
        }

        .edtika-auth-switch-note button {
            border: 0;
            background: transparent;
            color: #15151d;
            font-weight: 700;
            cursor: pointer;
            padding: 0;
        }

        .edtika-auth-modal__slider-side {
            background: rgba(16, 16, 20, 0.18);
            padding: 28px;
            display: flex;
            align-items: stretch;
            justify-content: center;
            min-height: 0;
        }

        .edtika-auth-slider {
            width: 100%;
            height: 100%;
            min-height: 0;
            border-radius: 26px;
            overflow: hidden;
            position: relative;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.22);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .edtika-auth-slider__slides {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .edtika-auth-slider__slide {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 24px 30px 72px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.34s ease;
            background: rgba(255, 255, 255, 0.88);
        }

        .edtika-auth-slider__slide.is-active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .edtika-auth-slider__image-wrap {
            width: min(78%, 360px);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 220px;
        }

        .edtika-auth-slider__image {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .edtika-auth-slider__title {
            margin: 14px 0 0;
            color: #1b2450;
            font-size: 34px;
            line-height: 1.22;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .edtika-auth-slider__subtitle {
            margin: 10px 0 0;
            color: #8da0c2;
            font-size: 19px;
            line-height: 1.42;
            font-weight: 500;
        }

        .edtika-auth-slider__pagination {
            position: absolute;
            left: 50%;
            bottom: 20px;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            align-items: center;
            z-index: 2;
        }

        .edtika-auth-slider__pagination button {
            width: 10px;
            height: 10px;
            border: 0;
            border-radius: 999px;
            background: rgba(81, 29, 153, 0.36);
            transition: width 0.24s ease, background-color 0.24s ease;
            padding: 0;
        }

        .edtika-auth-slider__pagination button.is-active {
            width: 30px;
            background: #511D99;
        }

        .edtika-footer {
            position: relative;
            isolation: isolate;
            margin-top: 0;
            padding-top: 0;
            flex-shrink: 0;
            margin-top: auto;
            overflow: hidden;
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
        }

        .edtika-footer::before,
        .edtika-footer::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: -1;
            filter: blur(68px);
            background: linear-gradient(136deg, var(--edtika-blob-start), var(--edtika-blob-end));
        }

        .edtika-footer::before {
            width: 640px;
            height: 330px;
            left: -210px;
            top: 120px;
            border-radius: 55% 45% 58% 42% / 48% 39% 61% 52%;
            transform: rotate(-12deg);
        }

        .edtika-footer::after {
            width: 640px;
            height: 350px;
            right: -220px;
            top: 120px;
            border-radius: 38% 62% 46% 54% / 62% 43% 57% 38%;
            transform: rotate(13deg);
        }

        .edtika-footer__top {
            display: grid;
            grid-template-columns: minmax(340px, 1.02fr) minmax(740px, 1.98fr);
            gap: 132px;
            align-items: start;
            padding: 56px 0 30px;
            max-width: 1312px;
            margin: 0 auto;
            padding-left: 28px;
            padding-right: 28px;
        }

        .edtika-footer__right {
            display: grid;
            grid-template-columns: 1fr 1fr 1.25fr;
            gap: 56px;
            align-items: start;
        }

        .edtika-footer__brand {
            font-size: 42px;
            line-height: 1;
            margin: 0;
            color: #511D99;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .edtika-footer__description {
            margin-top: 22px;
            margin-bottom: 0;
            font-size: 18px;
            line-height: 1.5;
            color: #26262f;
            max-width: 430px;
        }

        .edtika-footer__column-title {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
            font-weight: 800;
            color: #111118;
        }

        .edtika-footer__title-mark {
            display: block;
            margin-top: 5px;
            width: 28px;
            height: 4px;
            border-radius: 999px;
            background: #511D99;
        }

        .edtika-footer__list {
            margin: 22px 0 0;
            padding: 0;
            list-style: none;
        }

        .edtika-footer__list li {
            margin: 0 0 14px;
            font-size: 16px;
            line-height: 1.35;
            color: #1f1f27;
        }

        .edtika-footer__list a {
            text-decoration: none;
            color: inherit;
        }

        .edtika-footer__list a:hover,
        .edtika-footer__list a:focus {
            color: #511D99;
        }

        .edtika-footer__list .is-accent {
            color: #511D99;
            font-weight: 700;
        }

        .edtika-footer__contact-row {
            margin-top: 20px;
        }

        .edtika-footer__contact-row:first-child {
            margin-top: 22px;
        }

        .edtika-footer__contact-label {
            margin: 0;
            font-size: 16px;
            line-height: 1.25;
            font-weight: 800;
            color: #101018;
        }

        .edtika-footer__contact-value {
            margin-top: 8px;
            margin-bottom: 0;
            font-size: 16px;
            line-height: 1.45;
            color: #1f1f27;
        }

        .edtika-footer__bottom {
            padding: 24px 0 22px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 16px;
            position: relative;
            max-width: 1312px;
            margin: 0 auto;
            padding-left: 28px;
            padding-right: 28px;
        }

        .edtika-footer__bottom::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
            width: 100vw;
            height: 1px;
            background: rgba(16, 16, 20, 0.16);
        }

        .edtika-footer__copyright {
            margin: 0;
            font-size: 16px;
            color: #21212a;
        }

        .edtika-footer__copyright-brand {
            color: #511D99;
            font-weight: 700;
        }

        .edtika-footer__social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .edtika-footer__social-link {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #fff;
            background: #511D99;
        }

        .edtika-footer__social-link svg {
            width: 13px;
            height: 13px;
            color: #fff;
        }

        .edtika-footer__policies {
            margin: 0;
            text-align: right;
            font-size: 16px;
            color: #1f1f27;
        }

        .edtika-footer__dot {
            display: inline-block;
            margin: 0 6px;
            color: #511D99;
        }

        @media (max-width: 1399px) {
            .edtika-homepage__brand {
                font-size: 44px;
            }

            .edtika-homepage__nav {
                gap: 24px;
            }

            .edtika-homepage__nav-link {
                font-size: 16px;
            }

            .edtika-lang-switch,
            .edtika-login-btn {
                height: 46px;
            }

            .edtika-lang-switch {
                width: 100px;
                height: 42px;
            }

            .edtika-lang-switch__thumb {
                top: 5px;
                left: 5px;
                width: 28px;
                height: 28px;
            }

            .edtika-lang-switch.is-next-vie .edtika-lang-switch__thumb {
                left: auto;
                right: 5px;
            }

            .edtika-lang-switch__button {
                font-size: 13px;
            }

            .edtika-lang-switch.is-next-eng .edtika-lang-switch__button {
                padding-left: 40px;
                padding-right: 8px;
            }

            .edtika-lang-switch.is-next-vie .edtika-lang-switch__button {
                padding-right: 44px;
                padding-left: 7px;
            }

            .edtika-login-btn {
                padding: 0 24px;
                font-size: 15px;
            }

            .edtika-homepage__hero {
                padding-top: 72px;
            }

            .edtika-homepage__headline {
                font-size: 72px;
            }

            .edtika-homepage__subtext {
                max-width: 920px;
                font-size: 18px;
            }

            .edtika-cta-btn {
                height: 52px;
                padding: 0 36px;
                font-size: 18px;
            }

            .edtika-why {
                padding-bottom: 66px;
            }

            .edtika-why__title {
                font-size: 42px;
            }

            .edtika-why__grid {
                margin-top: 58px;
                grid-template-columns: repeat(3, 296px);
                justify-content: center;
                column-gap: 28px;
                row-gap: 30px;
            }

            .edtika-why__card {
                min-height: 222px;
                padding: 72px 20px 20px;
            }

            .edtika-why__icon {
                width: 96px;
                height: 96px;
                top: -34px;
                right: 18px;
            }

            .edtika-why__card-title {
                font-size: 18px;
            }

            .edtika-why__card-desc {
                font-size: 13px;
            }

            .edtika-why__card--mentor {
                margin-top: -72px;
            }

            .edtika-why__card--anywhere,
            .edtika-why__card--vocab {
                margin-top: 14px;
            }

            .edtika-why__card--anywhere {
                margin-left: -16px;
            }

            .edtika-why__card--vocab {
                margin-right: -16px;
            }

            .edtika-why__card--roadmap {
                margin-top: -2px;
            }

            .edtika-why__card--dictionary,
            .edtika-why__card--lecture {
                margin-top: 22px;
            }

            .edtika-flow {
                padding-top: 74px;
                padding-bottom: 78px;
            }

            .edtika-flow__title {
                font-size: 30px;
            }

            .edtika-flow__subtitle {
                font-size: 18px;
            }

            .edtika-flow__layout {
                margin-top: 34px;
                grid-template-columns: 248px 1fr;
                column-gap: 20px;
            }

            .edtika-flow__steps {
                padding-right: 20px;
            }

            .edtika-flow__step {
                min-height: 54px;
                border-radius: 16px;
                padding: 8px 11px;
            }

            .edtika-flow__step.is-active::after {
                display: none;
            }

            .edtika-flow__step.is-active {
                width: calc(100% + 20px);
                margin-right: -20px;
            }

            .edtika-flow__step-no {
                font-size: 22px;
                min-width: 22px;
            }

            .edtika-flow__step-label {
                font-size: 14px;
            }

            .edtika-flow__panel {
                height: calc(7 * 54px + 6 * 11px + 2px);
                min-height: 0;
                border-radius: 24px;
                padding: 10px 14px 12px;
            }

            .edtika-flow__laptop {
                max-width: 460px;
            }

            .edtika-flow__description {
                font-size: 15px;
            }

            .edtika-bundles {
                padding-bottom: 68px;
            }

            .edtika-bundles__title {
                font-size: 46px;
            }

            .edtika-bundles__track {
                gap: 22px;
                padding: 0;
            }

            .edtika-bundles__card {
                width: 288px;
                min-width: 288px;
                min-height: 568px;
            }

            .edtika-bundles__card-top {
                height: 186px;
            }

            .edtika-bundles__name {
                font-size: 26px;
                justify-content: center;
                align-items: center;

            }

            .edtika-bundles__price {
                font-size: 17px;
            }

            .edtika-bundles__btn {
                min-width: 66px;
                height: 30px;
                font-size: 12px;
            }

            .edtika-bundles__cta {
                min-width: 320px;
                font-size: 22px;
            }

            .edtika-faq {
                padding-top: 14px;
                padding-bottom: 74px;
            }

            .edtika-faq__title {
                font-size: 52px;
                line-height: 1.18;
            }

            .edtika-faq__question {
                font-size: 14px;
                padding: 12px 42px 12px 16px;
            }

            .edtika-faq__answer-inner {
                font-size: 12px;
                padding: 10px 18px 12px;
            }

            .edtika-consultation {
                padding: 60px 0;
            }

            .edtika-consultation__title {
                font-size: 42px;
                margin-bottom: 18px;
            }

            .edtika-consultation__subtitle {
                font-size: 16px;
                margin-bottom: 36px;
            }

            .edtika-consultation__panel {
                padding: 40px 100px;
            }

            .edtika-consultation__input,
            .edtika-consultation__select,
            .edtika-consultation__textarea {
                padding: 12px 16px;
                font-size: 15px;
            }

            .edtika-consultation__submit {
                height: 50px;
                padding: 0 36px;
                font-size: 16px;
            }
        }

        @media (max-width: 991px) {
            .edtika-homepage__header {
                height: auto;
                padding-top: 24px;
                flex-wrap: wrap;
            }

            .edtika-homepage__brand {
                font-size: 34px;
            }

            .edtika-homepage__nav {
                order: 3;
                width: 100%;
                justify-content: flex-start;
                overflow-x: auto;
                gap: 18px;
                margin: 0;
                padding-bottom: 6px;
            }

            .edtika-homepage__nav-link {
                font-size: 18px;
            }

            .edtika-homepage__actions {
                margin-left: auto;
            }

            .edtika-homepage__hero {
                padding-top: 52px;
                padding-bottom: 70px;
            }

            .edtika-homepage__headline {
                font-size: 56px;
            }

            .edtika-homepage__subtext {
                margin-top: 34px;
                font-size: 24px;
            }

            .edtika-homepage__cta-wrap {
                margin-top: 34px;
            }

            .edtika-cta-btn {
                width: 100%;
                max-width: 320px;
                height: 56px;
                font-size: 30px;
            }

            .edtika-why {
                padding-top: 10px;
                padding-bottom: 48px;
            }

            .edtika-why__title {
                font-size: 34px;
            }

            .edtika-why__grid {
                margin-top: 34px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                column-gap: 18px;
                row-gap: 26px;
            }

            .edtika-why__card {
                min-height: 220px;
                padding-top: 66px;
                margin-top: 0 !important;
                grid-column: auto !important;
                grid-row: auto !important;
            }

            .edtika-why__icon {
                width: 90px;
                height: 90px;
                top: -30px;
                right: 16px;
            }

            .edtika-why__card-title {
                font-size: 24px;
            }

            .edtika-why__card-desc {
                font-size: 17px;
            }

            .edtika-flow {
                padding-top: 58px;
                padding-bottom: 58px;
            }

            .edtika-flow__title {
                font-size: 34px;
            }

            .edtika-flow__subtitle {
                margin-top: 12px;
                font-size: 21px;
            }

            .edtika-flow__layout {
                margin-top: 24px;
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .edtika-flow__steps {
                gap: 10px;
                padding-right: 0;
            }

            .edtika-flow__step {
                min-height: 56px;
                padding: 10px 14px;
            }

            .edtika-flow__step.is-active::after {
                display: none;
            }

            .edtika-flow__step.is-active {
                margin-right: 0;
                width: 100%;
            }

            .edtika-flow__step-no {
                font-size: 26px;
                min-width: 26px;
            }

            .edtika-flow__step-label {
                font-size: 19px;
            }

            .edtika-flow__panel {
                margin-left: 0;
                min-height: auto;
                padding: 20px 16px 24px;
                border-radius: 20px;
            }

            .edtika-flow__laptop {
                max-width: 680px;
            }

            .edtika-flow__laptop-screen {
                border-width: 5px;
                border-bottom-width: 7px;
                border-radius: 16px 16px 8px 8px;
            }

            .edtika-flow__laptop-base {
                width: calc(100% + 38px);
                margin-left: -19px;
            }

            .edtika-flow__description {
                margin-top: 16px;
                font-size: 18px;
                padding: 0 4px;
            }

            .edtika-bundles {
                padding-top: 44px;
                padding-bottom: 56px;
            }

            .edtika-bundles__title {
                font-size: 40px;
            }

            .edtika-bundles__viewport {
                margin-top: 20px;
                margin-left: calc(50% - 50vw);
                margin-right: calc(50% - 50vw);
                padding: 6px 0 20px;
            }

            .edtika-bundles__track {
                gap: 14px;
                padding: 0 12px;
            }

            .edtika-bundles__card {
                width: 264px;
                min-width: 264px;
                min-height: 540px;
            }

            .edtika-bundles__card-top {
                height: 170px;
            }

            .edtika-bundles__name {
                font-size: 24px;
            }

            .edtika-bundles__features li {
                font-size: 12px;
            }

            .edtika-bundles__price {
                font-size: 15px;
            }

            .edtika-bundles__btn {
                min-width: 64px;
                height: 32px;
                font-size: 12px;
            }

            .edtika-bundles__footer-actions {
                margin-top: 20px;
                flex-wrap: wrap;
                gap: 12px;
            }

            .edtika-bundles__cta {
                min-width: 300px;
                height: 52px;
                font-size: 22px;
            }

            .edtika-faq {
                padding-top: 10px;
                padding-bottom: 56px;
            }

            .edtika-faq__title {
                font-size: 46px;
                line-height: 1.08;
                text-align: center;
            }

            .edtika-faq__item {
                border-radius: 22px;
                margin-bottom: 10px;
            }

            .edtika-faq__question {
                font-size: 15px;
                padding: 12px 38px 12px 14px;
            }

            .edtika-faq__question::after {
                right: 14px;
            }

            .edtika-faq__answer-inner {
                font-size: 13px;
                padding: 10px 14px 12px;
            }

            .edtika-consultation {
                padding: 50px 20px;
            }

            .edtika-consultation__title {
                font-size: 38px;
                margin-bottom: 16px;
            }

            .edtika-consultation__subtitle {
                font-size: 16px;
                margin-bottom: 32px;
            }

            .edtika-consultation__row {
                grid-template-columns: 1fr;
            }

            .edtika-consultation__input,
            .edtika-consultation__select,
            .edtika-consultation__textarea {
                padding: 120px 16px;
                font-size: 15px;
            }

            .edtika-blog__grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 24px;
                max-width: 100%;
            }

            .edtika-blog__title {
                font-size: 42px;
                margin-bottom: 18px;
            }

            .edtika-blog__subtitle {
                font-size: 16px;
                margin-bottom: 36px;
            }

            .edtika-blog__card {
                border-radius: 24px;
            }

            .edtika-blog__thumbnail {
                height: 220px;
            }

            .edtika-blog__content {
                padding: 20px 18px;
            }

            .edtika-blog__article-title {
                font-size: 17px;
            }

            .edtika-blog__pagination {
                margin-top: 36px;
                gap: 10px;
            }

            .edtika-blog__pagination-dot {
                width: 10px;
                height: 10px;
            }

            .edtika-blog__pagination-dot.is-active {
                width: 28px;
            }

            .edtika-auth-modal__content {
                grid-template-columns: 1fr;
            }

            .edtika-auth-modal__slider-side {
                display: none;
            }

            .edtika-auth-modal__form-side {
                padding: 64px 26px 28px;
            }

            .edtika-auth-pane__title {
                font-size: 34px;
            }

            .edtika-homepage__hero::before,
            .edtika-homepage__hero::after,
            .edtika-why::before,
            .edtika-why::after,
            .edtika-flow::before,
            .edtika-flow::after,
            .edtika-bundles::before,
            .edtika-bundles::after,
            .edtika-faq::before,
            .edtika-faq::after,
            .edtika-consultation::before,
            .edtika-consultation::after,
            .edtika-footer::before,
            .edtika-footer::after {
                filter: blur(44px);
                opacity: 0.56;
            }

            .edtika-footer__top {
                grid-template-columns: 1fr;
                gap: 28px;
                padding-top: 30px;
            }

            .edtika-footer__right {
                grid-template-columns: 1fr 1fr;
                gap: 30px 24px;
            }

            .edtika-footer__brand {
                font-size: 38px;
            }

            .edtika-footer__description {
                font-size: 17px;
                max-width: 100%;
            }

            .edtika-footer__column-title {
                font-size: 22px;
            }

            .edtika-footer__list li {
                font-size: 16px;
            }

            .edtika-footer__contact-label {
                font-size: 16px;
            }

            .edtika-footer__contact-value {
                font-size: 16px;
            }

            .edtika-footer__bottom {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .edtika-footer__policies {
                text-align: center;
            }
        }

        @media (max-width: 575px) {
            .edtika-homepage__container {
                padding: 0 14px;
            }

            .edtika-footer__top,
            .edtika-footer__bottom {
                padding-left: 14px;
                padding-right: 14px;
            }

            .edtika-homepage__actions {
                width: 100%;
                justify-content: space-between;
            }

            .edtika-login-btn {
                height: 44px;
                font-size: 18px;
                padding: 0 16px;
            }

            .edtika-lang-switch {
                width: 96px;
                height: 40px;
            }

            .edtika-lang-switch__thumb {
                top: 5px;
                left: 5px;
                width: 26px;
                height: 26px;
            }

            .edtika-lang-switch.is-next-vie .edtika-lang-switch__thumb {
                left: auto;
                right: 5px;
            }

            .edtika-lang-switch__button {
                font-size: 12px;
            }

            .edtika-lang-switch.is-next-eng .edtika-lang-switch__button {
                padding-left: 36px;
                padding-right: 8px;
            }

            .edtika-lang-switch.is-next-vie .edtika-lang-switch__button {
                padding-right: 40px;
                padding-left: 7px;
            }

            .edtika-homepage__headline {
                font-size: 42px;
                line-height: 1.2;
            }

            .edtika-homepage__subtext {
                font-size: 20px;
            }

            .edtika-why {
                padding-bottom: 36px;
            }

            .edtika-why__title {
                font-size: 30px;
                line-height: 1.25;
            }

            .edtika-why__grid {
                grid-template-columns: 1fr;
                row-gap: 22px;
            }

            .edtika-why__card {
                min-height: auto;
                padding: 64px 16px 18px;
            }

            .edtika-why__icon {
                width: 82px;
                height: 82px;
                top: -26px;
                right: 14px;
            }

            .edtika-why__card-title {
                font-size: 22px;
            }

            .edtika-why__card-desc {
                margin-top: 8px;
                font-size: 16px;
            }

            .edtika-flow {
                padding-top: 42px;
                padding-bottom: 44px;
            }

            .edtika-flow__title {
                font-size: 30px;
            }

            .edtika-flow__subtitle {
                margin-top: 10px;
                font-size: 18px;
            }

            .edtika-flow__step {
                min-height: 52px;
            }

            .edtika-flow__step-no {
                font-size: 22px;
            }

            .edtika-flow__step-label {
                font-size: 17px;
            }

            .edtika-flow__laptop {
                max-width: 100%;
            }

            .edtika-flow__description {
                font-size: 16px;
            }

            .edtika-bundles {
                padding-bottom: 44px;
            }

            .edtika-bundles__title {
                font-size: 34px;
                line-height: 1.14;
            }

            .edtika-bundles__card {
                width: min(84vw, 270px);
                min-width: min(84vw, 270px);
                min-height: 516px;
            }

            .edtika-bundles__card-top {
                height: 158px;
            }

            .edtika-bundles__name {
                font-size: 23px;
                align-self: center;
            }

            .edtika-bundles__price {
                font-size: 14px;
            }

            .edtika-bundles__billing {
                font-size: 12px;
            }

            .edtika-bundles__footer-actions {
                margin-top: 16px;
                gap: 10px;
            }

            .edtika-bundles__cta {
                min-width: 100%;
                width: 100%;
                font-size: 21px;
            }

            .edtika-faq {
                padding-bottom: 40px;
            }

            .edtika-faq__title {
                font-size: 32px;
                line-height: 1.12;
            }

            .edtika-faq__item {
                border-radius: 24px;
                margin-bottom: 12px;
            }

            .edtika-faq__question {
                font-size: 15px;
                line-height: 1.35;
                padding: 14px 40px 14px 16px;
            }

            .edtika-faq__question::after {
                right: 14px;
                font-size: 20px;
            }

            .edtika-faq__answer-inner {
                font-size: 13px;
                padding: 12px 18px 14px;
            }

            .edtika-consultation {
                padding: 40px 20px;
            }

            .edtika-consultation__title {
                font-size: 32px;
                margin-bottom: 12px;
            }

            .edtika-consultation__subtitle {
                font-size: 15px;
                margin-bottom: 28px;
            }

            .edtika-consultation__input,
            .edtika-consultation__select,
            .edtika-consultation__textarea {
                padding: 11px 14px;
                font-size: 14px;
                border-radius: 18px;
            }

            .edtika-consultation__submit {
                height: 48px;
                padding: 0 32px;
                font-size: 15px;
                margin-top: 16px;
            }

            .edtika-consultation__form {
                gap: 16px;
            }

            .edtika-blog {
                padding: 40px 20px;
            }

            .edtika-blog__title {
                font-size: 32px;
                margin-bottom: 12px;
            }

            .edtika-blog__subtitle {
                font-size: 15px;
                margin-bottom: 28px;
            }

            .edtika-blog__grid {
                grid-template-columns: 1fr;
                gap: 20px;
                max-width: 100%;
            }

            .edtika-blog__card {
                border-radius: 22px;
            }

            .edtika-blog__thumbnail {
                height: 200px;
            }

            .edtika-blog__content {
                padding: 18px 16px;
            }

            .edtika-blog__article-title {
                font-size: 16px;
            }

            .edtika-blog__author {
                font-size: 13px;
            }

            .edtika-blog__date {
                font-size: 13px;
            }

            .edtika-blog__pagination {
                margin-top: 32px;
                gap: 8px;
            }

            .edtika-blog__pagination-dot {
                width: 10px;
                height: 10px;
            }

            .edtika-blog__pagination-dot.is-active {
                width: 24px;
            }

            .edtika-auth-modal {
                padding: 12px;
            }

            .edtika-auth-modal__dialog {
                border-radius: 24px;
                height: calc(100vh - 24px);
            }

            .edtika-auth-modal__form-side {
                padding: 56px 16px 20px;
            }

            .edtika-auth-pane__title {
                font-size: 30px;
                margin-bottom: 18px;
            }

            .edtika-auth-modal__tabs {
                width: 100%;
            }

            .edtika-auth-modal__tab {
                flex: 1;
                min-width: 0;
            }

            .edtika-homepage__hero::before,
            .edtika-homepage__hero::after,
            .edtika-why::before,
            .edtika-why::after,
            .edtika-flow::before,
            .edtika-flow::after,
            .edtika-bundles::before,
            .edtika-bundles::after,
            .edtika-faq::before,
            .edtika-faq::after,
            .edtika-consultation::before,
            .edtika-consultation::after,
            .edtika-footer::before,
            .edtika-footer::after {
                width: 280px;
                height: 180px;
                filter: blur(32px);
                opacity: 0.42;
            }

            .edtika-footer {
                margin-top: auto;
            }

            .edtika-footer__top {
                grid-template-columns: 1fr;
                gap: 24px;
                padding-top: 24px;
            }

            .edtika-footer__right {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .edtika-footer__brand {
                font-size: 36px;
            }

            .edtika-footer__description {
                margin-top: 14px;
                font-size: 16px;
                line-height: 1.5;
            }

            .edtika-footer__column-title {
                font-size: 21px;
            }

            .edtika-footer__title-mark {
                width: 42px;
                height: 4px;
                margin-top: 4px;
            }

            .edtika-footer__list {
                margin-top: 16px;
            }

            .edtika-footer__list li {
                margin-bottom: 10px;
                font-size: 16px;
            }

            .edtika-footer__contact-row,
            .edtika-footer__contact-row:first-child {
                margin-top: 16px;
            }

            .edtika-footer__contact-label {
                font-size: 16px;
            }

            .edtika-footer__contact-value {
                margin-top: 4px;
                font-size: 16px;
            }

            .edtika-footer__bottom {
                padding-top: 14px;
                padding-bottom: 14px;
            }

            .edtika-footer__copyright,
            .edtika-footer__policies {
                font-size: 15px;
            }

            .edtika-footer__social-link {
                width: 24px;
                height: 24px;
                font-size: 12px;
            }

            .edtika-footer__social-link svg {
                width: 10px;
                height: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="edtika-homepage">
        <div class="edtika-homepage__container">
            <div class="edtika-homepage__content">
            <header class="edtika-homepage__header" aria-label="{{ $isEnglish ? 'Homepage Header' : 'Đầu trang chủ' }}">
                <a href="/" class="text-decoration-none" aria-label="EDTIKA Home">
                    <h1 class="edtika-homepage__brand">EDTIKA</h1>
                </a>

                <nav class="edtika-homepage__nav" aria-label="{{ $t['header']['mainNavAria'] }}">
                    <a href="/" class="edtika-homepage__nav-link {{ $isHomeActive ? 'is-active' : '' }}">{{ $t['nav']['home'] }}</a>
                    <a href="/classes" class="edtika-homepage__nav-link {{ $isClassesActive ? 'is-active' : '' }}">{{ $t['nav']['classes'] }}</a>
                    @if(auth()->check())
                        <a href="/panel/ielts-tests/practice" class="edtika-homepage__nav-link {{ $isPlacementActive ? 'is-active' : '' }}">{{ $t['nav']['placementTest'] }}</a>
                    @else
                        <a href="/panel/ielts-tests/practice" class="edtika-homepage__nav-link {{ $isPlacementActive ? 'is-active' : '' }}" data-open-auth-modal="true">{{ $t['nav']['placementTest'] }}</a>
                    @endif
                    @if(auth()->check())
                        <a href="/panel/ielts-tests/mock" class="edtika-homepage__nav-link {{ $isMockActive ? 'is-active' : '' }}">{{ $t['nav']['mockTest'] }}</a>
                    @else
                        <a href="/panel/ielts-tests/mock" class="edtika-homepage__nav-link {{ $isMockActive ? 'is-active' : '' }}" data-open-auth-modal="true">{{ $t['nav']['mockTest'] }}</a>
                    @endif
                    @if(auth()->check())
                        <a href="/panel/dictionary" class="edtika-homepage__nav-link {{ $isDictionaryActive ? 'is-active' : '' }}">{{ $t['nav']['dictionary'] }}</a>
                    @else
                        <a href="/panel/dictionary" class="edtika-homepage__nav-link {{ $isDictionaryActive ? 'is-active' : '' }}" data-open-auth-modal="true">{{ $t['nav']['dictionary'] }}</a>
                    @endif
                    <a href="/blog" class="edtika-homepage__nav-link {{ $isNewsActive ? 'is-active' : '' }}">{{ $t['nav']['news'] }}</a>
                </nav>

                <div class="edtika-homepage__actions">
                    <div class="edtika-lang-switch {{ $langSwitchClass }}" aria-label="{{ $t['header']['languageSwitchAria'] }}">
                        <div class="edtika-lang-switch__thumb"></div>

                        <form class="edtika-lang-switch__form" action="/locale" method="post">
                            {{ csrf_field() }}
                            <button class="edtika-lang-switch__button" type="submit" name="locale" value="{{ $nextLocale }}" aria-label="{{ $t['header']['switchLanguageAria'] }}">
                                {{ $nextLocaleLabel }}
                            </button>
                        </form>
                    </div>

                    @if(auth()->check())
                        <a href="/panel" class="edtika-user-avatar-btn" aria-label="{{ auth()->user()->full_name }}" title="{{ auth()->user()->full_name }}">
                            <img src="{{ auth()->user()->getAvatar(80) }}" alt="{{ auth()->user()->full_name }}" class="edtika-user-avatar-btn__image">
                        </a>
                    @else
                        <a href="/login" class="edtika-login-btn" data-open-auth-modal="true">{{ $t['header']['loginButton'] }}</a>
                    @endif
                </div>
            </header>

            <section class="edtika-homepage__hero" aria-label="{{ $isEnglish ? 'Homepage Hero' : 'Khu vực mở đầu trang chủ' }}">
                <h2 class="edtika-homepage__headline">
                    {{ $t['hero']['line1'] }}<br>
                    {{ $t['hero']['line2Prefix'] }} <span class="edtika-homepage__headline-highlight">{{ $t['hero']['line2Highlight'] }}</span><br>
                    {{ $t['hero']['line3'] }}
                </h2>

                <p class="edtika-homepage__subtext">
                    {{ $t['hero']['subtextLine1'] }}<br>
                    {{ $t['hero']['subtextLine2'] }}
                </p>

                <div class="edtika-homepage__cta-wrap">
                    <a href="/classes" class="edtika-cta-btn">{{ $t['hero']['cta'] }}</a>
                </div>
            </section>

            <section class="edtika-why" aria-label="{{ $t['why']['aria'] }}">
                <h3 class="edtika-why__title">{{ $t['why']['titlePrefix'] }} <span>{{ $t['why']['titleHighlight'] }}</span>?</h3>

                <div class="edtika-why__grid">
                    <article class="edtika-why__card edtika-why__card--cost">
                        <img class="edtika-why__icon" src="{{ asset('store/icons/wallet.png') }}" alt="{{ $whyCards[0]['alt'] }}">
                        <h4 class="edtika-why__card-title">{{ $whyCards[0]['title'] }}</h4>
                        <p class="edtika-why__card-desc">{{ $whyCards[0]['desc'] }}</p>
                    </article>

                    <article class="edtika-why__card edtika-why__card--practice">
                        <img class="edtika-why__icon" src="{{ asset('store/icons/calendar.png') }}" alt="{{ $whyCards[1]['alt'] }}">
                        <h4 class="edtika-why__card-title">{{ $whyCards[1]['title'] }}</h4>
                        <p class="edtika-why__card-desc">{{ $whyCards[1]['desc'] }}</p>
                    </article>

                    <article class="edtika-why__card edtika-why__card--mentor">
                        <img class="edtika-why__icon" src="{{ asset('store/icons/users.png') }}" alt="{{ $whyCards[2]['alt'] }}">
                        <h4 class="edtika-why__card-title">{{ $whyCards[2]['title'] }}</h4>
                        <p class="edtika-why__card-desc">{{ $whyCards[2]['desc'] }}</p>
                    </article>

                    <article class="edtika-why__card edtika-why__card--anywhere">
                        <img class="edtika-why__icon" src="{{ asset('store/icons/pin.png') }}" alt="{{ $whyCards[3]['alt'] }}">
                        <h4 class="edtika-why__card-title">{{ $whyCards[3]['title'] }}</h4>
                        <p class="edtika-why__card-desc">{{ $whyCards[3]['desc'] }}</p>
                    </article>

                    <article class="edtika-why__card edtika-why__card--vocab">
                        <img class="edtika-why__icon" src="{{ asset('store/icons/mail.png') }}" alt="{{ $whyCards[4]['alt'] }}">
                        <h4 class="edtika-why__card-title">{{ $whyCards[4]['title'] }}</h4>
                        <p class="edtika-why__card-desc">{{ $whyCards[4]['desc'] }}</p>
                    </article>

                    <article class="edtika-why__card edtika-why__card--dictionary">
                        <img class="edtika-why__icon" src="{{ asset('store/icons/bookmark.png') }}" alt="{{ $whyCards[5]['alt'] }}">
                        <h4 class="edtika-why__card-title">{{ $whyCards[5]['title'] }}</h4>
                        <p class="edtika-why__card-desc">{{ $whyCards[5]['desc'] }}</p>
                    </article>

                    <article class="edtika-why__card edtika-why__card--roadmap">
                        <img class="edtika-why__icon" src="{{ asset('store/icons/chat.png') }}" alt="{{ $whyCards[6]['alt'] }}">
                        <h4 class="edtika-why__card-title">{{ $whyCards[6]['title'] }}</h4>
                        <p class="edtika-why__card-desc">{{ $whyCards[6]['desc'] }}</p>
                    </article>

                    <article class="edtika-why__card edtika-why__card--lecture">
                        <img class="edtika-why__icon" src="{{ asset('store/icons/video player.png') }}" alt="{{ $whyCards[7]['alt'] }}">
                        <h4 class="edtika-why__card-title">{{ $whyCards[7]['title'] }}</h4>
                        <p class="edtika-why__card-desc">{{ $whyCards[7]['desc'] }}</p>
                    </article>
                </div>
            </section>

            <section class="edtika-flow" aria-label="{{ $t['flow']['aria'] }}">
                <h3 class="edtika-flow__title">{{ $t['flow']['titlePrefix'] }} <span>{{ $t['flow']['titleHighlight'] }}</span></h3>
                <p class="edtika-flow__subtitle">{{ $t['flow']['subtitle'] }}</p>

                <div class="edtika-flow__layout">
                    <div class="edtika-flow__steps">
                        @foreach($flowSteps as $flowIndex => $flowStep)
                            <button type="button" class="edtika-flow__step {{ $flowIndex === 0 ? 'is-active' : '' }}" data-flow-index="{{ $flowIndex + 1 }}" data-flow-mode="{{ $flowIndex + 1 }}" data-flow-description="{{ $flowStep['description'] }}">
                                <span class="edtika-flow__step-no">{{ $flowIndex + 1 }}</span>
                                <span class="edtika-flow__step-label">{{ $flowStep['label'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="edtika-flow__panel">
                        <div class="edtika-flow__laptop">
                            <div id="edtikaFlowLaptopScreen" class="edtika-flow__laptop-screen is-mode-1"></div>
                            <div class="edtika-flow__laptop-base"></div>
                        </div>

                        <p id="edtikaFlowDescription" class="edtika-flow__description">{{ $flowSteps[0]['description'] }}</p>
                    </div>
                </div>
            </section>

            <section class="edtika-bundles" aria-label="{{ $t['bundles']['aria'] }}">
                <h3 class="edtika-bundles__title">{{ $t['bundles']['titlePrefix'] }} <span>{{ $t['bundles']['titleHighlight'] }}</span></h3>

                <div class="edtika-bundles__viewport">
                    <div class="edtika-bundles__track">
                        @foreach($bundleCarouselCards as $bundleIndex => $bundleCard)
                            <article class="edtika-bundles__card {{ $bundleIndex === $activeBundleIndex ? 'is-active' : '' }}" data-detail-url="{{ $bundleCard['detail_url'] }}">
                                <div class="edtika-bundles__card-top">
                                    <img src="{{ asset('store/icons/—Pngtree—abstract purple line wave background_5542852 1.png') }}" alt="{{ $isEnglish ? 'Abstract background' : 'Nền trừu tượng' }}">
                                </div>

                                <div class="edtika-bundles__card-body">
                                    <h4 class="edtika-bundles__name">{{ $bundleCard['name'] }}</h4>

                                    <ul class="edtika-bundles__features">
                                        @foreach($bundleCard['items'] as $bundleItem)
                                            <li>{{ $bundleItem }}</li>
                                        @endforeach
                                    </ul>

                                    <div class="edtika-bundles__bottom">
                                        <div class="edtika-bundles__price-wrap">
                                            <div class="edtika-bundles__price">{{ $bundleCard['price'] }}</div>
                                            <div class="edtika-bundles__billing">{{ $bundleCard['billing'] }}</div>
                                        </div>

                                        <div class="edtika-bundles__actions">
                                            <a href="/bundles" class="edtika-bundles__btn edtika-bundles__btn--buy">{{ $t['bundles']['buy'] }}</a>
                                            <a href="{{ $bundleCard['detail_url'] }}" class="edtika-bundles__btn edtika-bundles__btn--view">{{ $t['bundles']['view'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div class="edtika-bundles__footer-actions">
                    <a href="/panel/ielts-tests/practice" class="edtika-bundles__cta" @if(auth()->guest()) data-open-auth-modal="true" @endif>{{ $t['bundles']['placementCta'] }}</a>
                    <a href="{{ $bundleCarouselCards[$activeBundleIndex]['detail_url'] }}" class="edtika-bundles__cta" id="bundleDetailCta">{{ $t['bundles']['detailCta'] }}</a>
                </div>
            </section>

            <section class="edtika-faq" aria-label="{{ $t['faq']['aria'] }}">
                <div class="edtika-faq__layout">
                    <h3 class="edtika-faq__title">{!! $t['faq']['titleHtml'] !!}</h3>

                    <div class="edtika-faq__panel" role="region" aria-label="{{ $t['faq']['panelAria'] }}">
                        <div class="edtika-faq__scroll">
                            @foreach($faqItems as $faqIndex => $faqItem)
                                <article class="edtika-faq__item {{ $faqIndex === 0 ? 'is-open' : '' }}">
                                    <button type="button" class="edtika-faq__question" aria-expanded="{{ $faqIndex === 0 ? 'true' : 'false' }}">
                                        {{ $faqItem['question'] }}
                                    </button>

                                    <div class="edtika-faq__answer" {{ $faqIndex === 0 ? 'style=max-height:240px;' : '' }}>
                                        <div class="edtika-faq__answer-inner">
                                            {{ $faqItem['answer'] }}
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="edtika-consultation" aria-label="{{ $t['consultation']['aria'] }}">
                <div class="edtika-consultation__container">
                    <h3 class="edtika-consultation__title">{{ $t['consultation']['title'] }}</h3>
                    <p class="edtika-consultation__subtitle">
                        {{ $t['consultation']['subtitleLine1'] }}<br>
                        {{ $t['consultation']['subtitleLine2'] }}
                    </p>

                    <div class="edtika-consultation__panel">
                        <form class="edtika-consultation__form" method="POST" action="/consultation">
                            @csrf

                            <div class="edtika-consultation__field">
                                <label class="edtika-consultation__label" for="consultation-name">{{ $t['consultation']['studentName'] }}</label>
                                <input 
                                    type="text" 
                                    id="consultation-name" 
                                    class="edtika-consultation__input" 
                                    name="name" 
                                    placeholder="{{ $t['consultation']['studentNamePlaceholder'] }}" 
                                    required
                                />
                            </div>

                            <div class="edtika-consultation__field">
                                <label class="edtika-consultation__label" for="consultation-role">{{ $t['consultation']['registerRole'] }}</label>
                                <select 
                                    id="consultation-role" 
                                    class="edtika-consultation__select" 
                                    name="role"
                                >
                                    <option value="">{{ $t['consultation']['registerRolePlaceholder'] }}</option>
                                    <option value="parent">{{ $t['consultation']['roleParent'] }}</option>
                                    <option value="teacher">{{ $t['consultation']['roleTeacher'] }}</option>
                                    <option value="agent">{{ $t['consultation']['roleAgent'] }}</option>
                                    <option value="other">{{ $t['consultation']['roleOther'] }}</option>
                                </select>
                            </div>

                            <div class="edtika-consultation__row">
                                <div class="edtika-consultation__field">
                                    <label class="edtika-consultation__label" for="consultation-email">{{ $t['consultation']['email'] }}</label>
                                    <input 
                                        type="email" 
                                        id="consultation-email" 
                                        class="edtika-consultation__input" 
                                        name="email" 
                                        placeholder="your@email.com" 
                                        required
                                    />
                                </div>

                                <div class="edtika-consultation__field">
                                    <label class="edtika-consultation__label" for="consultation-phone">{{ $t['consultation']['phone'] }}</label>
                                    <input 
                                        type="tel" 
                                        id="consultation-phone" 
                                        class="edtika-consultation__input" 
                                        name="phone" 
                                        placeholder="+84 9xx xxxx xxx" 
                                        required
                                    />
                                </div>
                            </div>

                            <div class="edtika-consultation__field">
                                <label class="edtika-consultation__label" for="consultation-course">{{ $t['consultation']['course'] }}</label>
                                <select 
                                    id="consultation-course" 
                                    class="edtika-consultation__select" 
                                    name="course"
                                    required
                                >
                                    <option value="">{{ $t['consultation']['coursePlaceholder'] }}</option>
                                    <option value="band-6-6.5">BAND 6.0 - 6.5</option>
                                    <option value="band-6.5">BAND 6.5+</option>
                                    <option value="band-7">BAND 7.0+</option>
                                    <option value="beginner">{{ $t['consultation']['courseBasic'] }}</option>
                                    <option value="other">{{ $t['consultation']['roleOther'] }}</option>
                                </select>
                            </div>

                            <div class="edtika-consultation__field">
                                <label class="edtika-consultation__label" for="consultation-message">{{ $t['consultation']['message'] }}</label>
                                <textarea 
                                    id="consultation-message" 
                                    class="edtika-consultation__textarea" 
                                    name="message" 
                                    placeholder="{{ $t['consultation']['messagePlaceholder'] }}"
                                ></textarea>
                            </div>

                            <button type="submit" class="edtika-consultation__submit">{{ $t['consultation']['submit'] }}</button>
                        </form>
                    </div>
                </div>
            </section>

            <section class="edtika-blog" aria-label="{{ $t['blog']['aria'] }}">
                <div class="edtika-blog__container">
                    <h3 class="edtika-blog__title">{{ $t['blog']['titlePrefix'] }} <span class="edtika-blog__title-highlight">{{ $t['blog']['titleHighlight'] }}</span></h3>
                    <p class="edtika-blog__subtitle">{{ $t['blog']['subtitle'] }}</p>

                    <div class="edtika-blog__grid">
                        @foreach($blogArticles as $article)
                            <article class="edtika-blog__card">
                                <img src="{{ $article['thumbnail'] }}" alt="{{ $article['title'] }}" class="edtika-blog__thumbnail">
                                <div class="edtika-blog__content">
                                    <div class="edtika-blog__meta">
                                        <span class="edtika-blog__author">{{ $article['author'] }}</span>
                                        <span class="edtika-blog__date">{{ $article['date'] }}</span>
                                    </div>
                                    <h4 class="edtika-blog__article-title">{{ $article['title'] }}</h4>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="edtika-blog__pagination">
                        <button class="edtika-blog__pagination-dot is-active" data-page="1" aria-label="{{ $t['blog']['pageAriaPrefix'] }} 1"></button>
                        <button class="edtika-blog__pagination-dot" data-page="2" aria-label="{{ $t['blog']['pageAriaPrefix'] }} 2"></button>
                        <button class="edtika-blog__pagination-dot" data-page="3" aria-label="{{ $t['blog']['pageAriaPrefix'] }} 3"></button>
                    </div>
                </div>
            </section>
            </div>

            <footer class="edtika-footer" aria-label="{{ $isEnglish ? 'Homepage Footer' : 'Chân trang chủ' }}">
                <div class="edtika-footer__top">
                    <div>
                        <h3 class="edtika-footer__brand">EDTIKA</h3>
                        <p class="edtika-footer__description">{{ $t['footer']['description'] }}</p>
                    </div>

                    <div class="edtika-footer__right">
                        <div>
                            <h4 class="edtika-footer__column-title">{{ $t['footer']['supportTitle'] }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>

                            <ul class="edtika-footer__list">
                                @foreach($t['footer']['supportLinks'] as $supportLink)
                                    <li><a href="#">{{ $supportLink }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <h4 class="edtika-footer__column-title">{{ $t['footer']['introTitle'] }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>

                            <ul class="edtika-footer__list">
                                @foreach($t['footer']['introLinks'] as $introLink)
                                    <li>
                                        <a href="#">{{ $introLink }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <h4 class="edtika-footer__column-title">{{ $t['footer']['contactTitle'] }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>

                            <div class="edtika-footer__contact-row">
                                <p class="edtika-footer__contact-label">{{ $t['footer']['hotlineLabel'] }}</p>
                                <p class="edtika-footer__contact-value">{{ $t['footer']['hotlineValue'] }}</p>
                            </div>

                            <div class="edtika-footer__contact-row">
                                <p class="edtika-footer__contact-label">{{ $t['footer']['emailLabel'] }}</p>
                                <p class="edtika-footer__contact-value">{{ $t['footer']['emailValue'] }}</p>
                            </div>

                            <div class="edtika-footer__contact-row">
                                <p class="edtika-footer__contact-label">{{ $t['footer']['addressLabel'] }}</p>
                                <p class="edtika-footer__contact-value">{{ $t['footer']['addressValue'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="edtika-footer__bottom">
                    <p class="edtika-footer__copyright">
                        {{ $t['footer']['copyright'] }} &copy; <span class="edtika-footer__copyright-brand">Edtika.</span> {{ $t['footer']['allRightsReserved'] }}
                    </p>

                    <div class="edtika-footer__social" aria-label="Social links">
                        <a class="edtika-footer__social-link" href="#" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M13.74 20V12.7H16.21L16.58 9.86H13.74V8.05C13.74 7.23 13.97 6.68 15.15 6.68H16.68V4.14C16.42 4.1 15.52 4 14.47 4C12.29 4 10.8 5.33 10.8 7.77V9.86H8.34V12.7H10.8V20H13.74Z" fill="currentColor"/>
                            </svg>
                        </a>
                        <a class="edtika-footer__social-link" href="#" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect x="4" y="4" width="16" height="16" rx="5" stroke="currentColor" stroke-width="2"/>
                                <circle cx="12" cy="12" r="3.6" stroke="currentColor" stroke-width="2"/>
                                <circle cx="16.7" cy="7.3" r="1" fill="currentColor"/>
                            </svg>
                        </a>
                        <a class="edtika-footer__social-link" href="#" aria-label="LinkedIn">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M7.23 9.5H4.75V17.5H7.23V9.5Z" fill="currentColor"/>
                                <path d="M6 8.36C6.79 8.36 7.43 7.72 7.43 6.93C7.43 6.14 6.79 5.5 6 5.5C5.21 5.5 4.57 6.14 4.57 6.93C4.57 7.72 5.21 8.36 6 8.36Z" fill="currentColor"/>
                                <path d="M11.1 9.5H8.72V17.5H11.19V13.54C11.19 12.5 11.39 11.49 12.68 11.49C13.95 11.49 13.97 12.68 13.97 13.61V17.5H16.45V13.11C16.45 10.95 15.99 9.29 13.47 9.29C12.26 9.29 11.45 9.95 11.1 10.57V9.5Z" fill="currentColor"/>
                            </svg>
                        </a>
                    </div>

                    <p class="edtika-footer__policies">
                        {{ $t['footer']['terms'] }}<span class="edtika-footer__dot">•</span>{{ $t['footer']['privacy'] }}
                    </p>
                </div>
            </footer>

            <div class="edtika-auth-modal" id="edtikaAuthModal" aria-hidden="true">
                <div class="edtika-auth-modal__dialog" role="dialog" aria-modal="true" aria-label="{{ $t['auth']['dialogAria'] }}">
                    <button type="button" class="edtika-auth-modal__close" id="edtikaAuthModalClose" aria-label="{{ $t['auth']['closeAria'] }}">&times;</button>

                    <div class="edtika-auth-modal__content">
                        <div class="edtika-auth-modal__form-side">
                            <div class="edtika-auth-modal__tabs" role="tablist" aria-label="{{ $t['auth']['tabsAria'] }}">
                                <button type="button" class="edtika-auth-modal__tab is-active" data-auth-tab="login">{{ $t['auth']['loginTab'] }}</button>
                                <button type="button" class="edtika-auth-modal__tab" data-auth-tab="register">{{ $t['auth']['registerTab'] }}</button>
                            </div>

                            <div class="edtika-auth-pane is-active" data-auth-pane="login">
                                <h3 class="edtika-auth-pane__title">{{ $t['auth']['loginTitle'] }}</h3>

                                @if(session()->has('login_failed_active_session'))
                                    <div class="mb-16 p-16 rounded-12 border-danger bg-danger-20">
                                        <div class="font-14 font-weight-bold text-danger">{{ session()->get('login_failed_active_session')['title'] ?? trans('update.login_failed') }}</div>
                                        <div class="mt-4 font-12 text-danger">{{ session()->get('login_failed_active_session')['msg'] ?? trans('update.device_limit_reached_please_try_again') }}</div>
                                    </div>
                                @endif

                                <div class="edtika-auth-methods" role="tablist" aria-label="{{ $t['auth']['loginMethodsAria'] }}">
                                    <button type="button" class="edtika-auth-method is-active" data-login-method="email">{{ $t['auth']['emailMethod'] }}</button>
                                    <button type="button" class="edtika-auth-method" data-login-method="phone">{{ $t['auth']['phoneMethod'] }}</button>
                                </div>

                                <form method="POST" action="/login">
                                    @csrf
                                    <input type="hidden" name="type" id="edtikaLoginType" value="email">

                                    <div class="edtika-auth-field" data-login-field="email">
                                        <label class="edtika-auth-label" for="edtikaLoginEmail">{{ $t['auth']['emailMethod'] }} *</label>
                                        <input id="edtikaLoginEmail" class="edtika-auth-input" type="email" name="email" autocomplete="email">
                                    </div>

                                    <div class="edtika-auth-field" data-login-field="phone" style="display: none;">
                                        <label class="edtika-auth-label" for="edtikaLoginPhone">{{ $t['auth']['phoneMethod'] }} *</label>
                                        <input id="edtikaLoginPhone" class="edtika-auth-input" type="text" name="mobile" autocomplete="tel">
                                    </div>

                                    <div class="edtika-auth-field">
                                        <label class="edtika-auth-label" for="edtikaLoginPassword">{{ $t['auth']['password'] }} *</label>
                                        <div class="edtika-auth-input-wrap">
                                            <input id="edtikaLoginPassword" class="edtika-auth-input" type="password" name="password" autocomplete="current-password">
                                            <span class="edtika-auth-input-icon">◌</span>
                                        </div>
                                    </div>

                                    <a class="edtika-auth-forgot" href="/forget-password">{{ $t['auth']['forgotPassword'] }}</a>
                                    <button type="submit" class="edtika-auth-submit">{{ $t['auth']['loginTab'] }}</button>
                                </form>

                                <div class="edtika-auth-switch-note">
                                    {{ $t['auth']['noAccount'] }} <button type="button" data-auth-tab-switch="register">{{ $t['auth']['registerTab'] }}</button>
                                </div>
                            </div>

                            <div class="edtika-auth-pane" data-auth-pane="register">
                                <h3 class="edtika-auth-pane__title">{{ $t['auth']['registerTitle'] }}</h3>

                                <form method="POST" action="/register">
                                    @csrf

                                    <div class="edtika-auth-field">
                                        <label class="edtika-auth-label">{{ app()->getLocale() === 'en' ? 'Choose role' : 'Chọn vai trò' }}</label>

                                        <div class="edtika-auth-role-switch">
                                            <label class="edtika-auth-role-option">
                                                <input type="radio" name="account_type" value="user" checked>
                                                <span>{{ app()->getLocale() === 'en' ? 'Student' : 'Học viên' }}</span>
                                            </label>

                                            <label class="edtika-auth-role-option">
                                                <input type="radio" name="account_type" value="teacher">
                                                <span>{{ app()->getLocale() === 'en' ? 'Teacher' : 'Giảng viên' }}</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="edtika-auth-field">
                                        <label class="edtika-auth-label" for="edtikaRegisterEmail">{{ $t['auth']['emailMethod'] }} *</label>
                                        <input id="edtikaRegisterEmail" class="edtika-auth-input" type="email" name="email" autocomplete="email">
                                    </div>

                                    <div class="edtika-auth-field">
                                        <label class="edtika-auth-label" for="edtikaRegisterPhone">{{ app()->getLocale() === 'en' ? 'Phone (Optional)' : 'Điện thoại (Tùy chọn)' }}</label>
                                        <input id="edtikaRegisterPhone" class="edtika-auth-input" type="text" name="mobile" autocomplete="tel">
                                    </div>

                                    <div class="edtika-auth-field">
                                        <label class="edtika-auth-label" for="edtikaRegisterFullName">{{ $t['auth']['fullName'] }} *</label>
                                        <input id="edtikaRegisterFullName" class="edtika-auth-input" type="text" name="full_name" autocomplete="name">
                                    </div>

                                    <div class="edtika-auth-field">
                                        <label class="edtika-auth-label" for="edtikaRegisterPassword">{{ $t['auth']['password'] }} *</label>
                                        <div class="edtika-auth-input-wrap">
                                            <input id="edtikaRegisterPassword" class="edtika-auth-input" type="password" name="password" autocomplete="new-password">
                                            <span class="edtika-auth-input-icon">◌</span>
                                        </div>
                                    </div>

                                    <div class="edtika-auth-field">
                                        <label class="edtika-auth-label" for="edtikaRegisterPasswordConfirmation">{{ $t['auth']['confirmPassword'] }} *</label>
                                        <div class="edtika-auth-input-wrap">
                                            <input id="edtikaRegisterPasswordConfirmation" class="edtika-auth-input" type="password" name="password_confirmation" autocomplete="new-password">
                                            <span class="edtika-auth-input-icon">◌</span>
                                        </div>
                                    </div>

                                    <label class="edtika-auth-check">
                                        <input type="checkbox" name="term" value="1" required>
                                        <span class="edtika-auth-check__box">✓</span>
                                        <span class="edtika-auth-check__text">
                                            {{ app()->getLocale() === 'en' ? 'I agree to the' : 'Tôi đồng ý với' }} <strong>{{ app()->getLocale() === 'en' ? 'terms & rules' : 'điều khoản & quy tắc' }}</strong>
                                        </span>
                                    </label>

                                    <button type="submit" class="edtika-auth-submit">{{ $t['auth']['registerTab'] }}</button>
                                </form>

                                <div class="edtika-auth-switch-note">
                                    {{ $t['auth']['hasAccount'] }} <button type="button" data-auth-tab-switch="login">{{ $t['auth']['loginTab'] }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="edtika-auth-modal__slider-side">
                            <div class="edtika-auth-slider" @if(!empty($authSliderBackground)) style="background-image: url('{{ $authSliderBackground }}'); background-size: cover; background-position: center;" @endif>
                                <div class="edtika-auth-slider__slides">
                                    @foreach($authSliderSlides as $authSlideIndex => $authSlide)
                                        @php
                                            $authSlideTitle = $authSlide['title'] ?? '';
                                            $authSlideSubtitle = $authSlide['subtitle'] ?? '';

                                            $authTitleMap = [
                                                'Affordable Quality Education' => trans('update.affordable_quality_education'),
                                                'Advance Your Career' => trans('update.advance_your_career'),
                                                'Instant Certificate Access' => trans('update.instant_certificate_access'),
                                            ];

                                            $authSubtitleMap = [
                                                'High-value courses at accessible prices' => trans('update.high_value_courses_at_accessible_prices'),
                                                'Build your resume with proven expertise' => trans('update.build_your_resume_with_proven_expertise'),
                                                'Download certificates right after completion' => trans('update.download_certificates_right_after_completion'),
                                            ];

                                            if (!empty($authSlideTitle) && array_key_exists($authSlideTitle, $authTitleMap)) {
                                                $authSlideTitle = $authTitleMap[$authSlideTitle];
                                            }

                                            if (!empty($authSlideSubtitle) && array_key_exists($authSlideSubtitle, $authSubtitleMap)) {
                                                $authSlideSubtitle = $authSubtitleMap[$authSlideSubtitle];
                                            }
                                        @endphp

                                        <div class="edtika-auth-slider__slide {{ $authSlideIndex === 0 ? 'is-active' : '' }}" data-auth-slide>
                                            @if(!empty($authSlide['image']))
                                                <div class="edtika-auth-slider__image-wrap">
                                                    <img class="edtika-auth-slider__image" src="{{ $authSlide['image'] }}" alt="{{ $t['auth']['sliderImageAlt'] }}">
                                                </div>
                                            @endif

                                            @if(!empty($authSlideTitle))
                                                <h4 class="edtika-auth-slider__title">{{ $authSlideTitle }}</h4>
                                            @endif

                                            @if(!empty($authSlideSubtitle))
                                                <p class="edtika-auth-slider__subtitle">{{ $authSlideSubtitle }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="edtika-auth-slider__pagination" aria-label="Auth modal slider pagination">
                                    @foreach($authSliderSlides as $authSlideIndex => $authSlide)
                                        <button type="button" class="{{ $authSlideIndex === 0 ? 'is-active' : '' }}" data-auth-slider-dot aria-label="Slide {{ $authSlideIndex + 1 }}" aria-current="{{ $authSlideIndex === 0 ? 'true' : 'false' }}"></button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        (function () {
            var flowSteps = document.querySelectorAll('.edtika-flow__step');
            var flowDescription = document.getElementById('edtikaFlowDescription');
            var flowLaptopScreen = document.getElementById('edtikaFlowLaptopScreen');
            var bundlesViewport = document.querySelector('.edtika-bundles__viewport');
            var bundleCards = document.querySelectorAll('.edtika-bundles__card');
            var faqItems = document.querySelectorAll('.edtika-faq__item');
            var bundleDetailCta = document.getElementById('bundleDetailCta');
            var authModal = document.getElementById('edtikaAuthModal');
            var authModalCloseBtn = document.getElementById('edtikaAuthModalClose');
            var authModalOpenBtns = document.querySelectorAll('[data-open-auth-modal="true"]');
            var authTabs = document.querySelectorAll('[data-auth-tab]');
            var authPanes = document.querySelectorAll('[data-auth-pane]');
            var authTabSwitchBtns = document.querySelectorAll('[data-auth-tab-switch]');
            var loginMethodBtns = document.querySelectorAll('[data-login-method]');
            var loginFieldBlocks = document.querySelectorAll('[data-login-field]');
            var loginTypeInput = document.getElementById('edtikaLoginType');
            var authSliderSlides = document.querySelectorAll('[data-auth-slide]');
            var authSliderDots = document.querySelectorAll('[data-auth-slider-dot]');
            var authSliderIntervalId = null;
            var authSliderActiveIndex = 0;
            var authSliderDelay = 3400;
            var authLoginFailedSession = @json(session()->get('login_failed_active_session'));
            var authModalShouldOpen = @json(session()->get('auth_modal_open', false));

            var setActiveAuthSlide = function (nextIndex) {
                if (!authSliderSlides.length) {
                    return;
                }

                var totalSlides = authSliderSlides.length;
                authSliderActiveIndex = ((nextIndex % totalSlides) + totalSlides) % totalSlides;

                authSliderSlides.forEach(function (slide, slideIndex) {
                    slide.classList.toggle('is-active', slideIndex === authSliderActiveIndex);
                });

                authSliderDots.forEach(function (dot, dotIndex) {
                    var isCurrent = dotIndex === authSliderActiveIndex;
                    dot.classList.toggle('is-active', isCurrent);
                    dot.setAttribute('aria-current', isCurrent ? 'true' : 'false');
                });
            };

            var stopAuthSliderAutoplay = function () {
                if (authSliderIntervalId) {
                    window.clearInterval(authSliderIntervalId);
                    authSliderIntervalId = null;
                }
            };

            var startAuthSliderAutoplay = function () {
                if (authSliderIntervalId || authSliderSlides.length < 2) {
                    return;
                }

                authSliderIntervalId = window.setInterval(function () {
                    if (!authModal || !authModal.classList.contains('is-open')) {
                        return;
                    }

                    setActiveAuthSlide(authSliderActiveIndex + 1);
                }, authSliderDelay);
            };

            var setAuthTab = function (tabName) {
                authTabs.forEach(function (tab) {
                    tab.classList.toggle('is-active', tab.getAttribute('data-auth-tab') === tabName);
                });

                authPanes.forEach(function (pane) {
                    pane.classList.toggle('is-active', pane.getAttribute('data-auth-pane') === tabName);
                });
            };

            var openAuthModal = function () {
                if (!authModal) {
                    return;
                }

                authModal.classList.add('is-open');
                authModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                setAuthTab('login');
                startAuthSliderAutoplay();
            };

            var closeAuthModal = function () {
                if (!authModal) {
                    return;
                }

                authModal.classList.remove('is-open');
                authModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                stopAuthSliderAutoplay();
            };

            authModalOpenBtns.forEach(function (btn) {
                btn.addEventListener('click', function (event) {
                    event.preventDefault();
                    openAuthModal();
                });
            });

            if (authModalCloseBtn) {
                authModalCloseBtn.addEventListener('click', closeAuthModal);
            }

            if (authModal) {
                authModal.addEventListener('click', function (event) {
                    if (event.target === authModal) {
                        closeAuthModal();
                    }
                });
            }

            window.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeAuthModal();
                }
            });

            authTabs.forEach(function (tabBtn) {
                tabBtn.addEventListener('click', function () {
                    setAuthTab(tabBtn.getAttribute('data-auth-tab'));
                });
            });

            authTabSwitchBtns.forEach(function (tabSwitchBtn) {
                tabSwitchBtn.addEventListener('click', function () {
                    setAuthTab(tabSwitchBtn.getAttribute('data-auth-tab-switch'));
                });
            });

            var setLoginMethod = function (method) {
                loginMethodBtns.forEach(function (methodBtn) {
                    methodBtn.classList.toggle('is-active', methodBtn.getAttribute('data-login-method') === method);
                });

                loginFieldBlocks.forEach(function (fieldBlock) {
                    var isTarget = fieldBlock.getAttribute('data-login-field') === method;
                    fieldBlock.style.display = isTarget ? 'block' : 'none';
                });

                if (loginTypeInput) {
                    loginTypeInput.value = method === 'phone' ? 'mobile' : 'email';
                }
            };

            loginMethodBtns.forEach(function (methodBtn) {
                methodBtn.addEventListener('click', function () {
                    setLoginMethod(methodBtn.getAttribute('data-login-method'));
                });
            });

            setLoginMethod('email');

            if (authLoginFailedSession || authModalShouldOpen) {
                openAuthModal();
            }

            if (authSliderSlides.length) {
                setActiveAuthSlide(0);
                authSliderDots.forEach(function (dot, dotIndex) {
                    dot.addEventListener('click', function () {
                        setActiveAuthSlide(dotIndex);
                    });
                });
            }

            if (!flowSteps.length || !flowDescription || !flowLaptopScreen) {
                return;
            }

            var setActiveFlowStep = function (targetStep) {
                flowSteps.forEach(function (step) {
                    step.classList.remove('is-active');
                });

                targetStep.classList.add('is-active');

                var description = targetStep.getAttribute('data-flow-description') || '';
                var mode = targetStep.getAttribute('data-flow-mode') || '1';

                flowDescription.textContent = description;

                flowLaptopScreen.className = 'edtika-flow__laptop-screen';
                flowLaptopScreen.classList.add('is-mode-' + mode);
            };

            flowSteps.forEach(function (step) {
                step.addEventListener('click', function () {
                    setActiveFlowStep(step);
                });
            });

            var centerBundleCard = function (targetCard, useSmoothScroll) {
                if (!bundlesViewport || !targetCard) {
                    return;
                }

                var viewportRect = bundlesViewport.getBoundingClientRect();
                var cardRect = targetCard.getBoundingClientRect();
                var viewportCenter = viewportRect.left + (viewportRect.width / 2);
                var cardCenter = cardRect.left + (cardRect.width / 2);
                var nextScrollLeft = bundlesViewport.scrollLeft + (cardCenter - viewportCenter);
                var maxScrollLeft = bundlesViewport.scrollWidth - bundlesViewport.clientWidth;
                var clampedScrollLeft = Math.min(Math.max(0, nextScrollLeft), Math.max(0, maxScrollLeft));

                bundlesViewport.scrollTo({
                    left: clampedScrollLeft,
                    behavior: useSmoothScroll ? 'smooth' : 'auto'
                });
            };

            var setActiveBundleCard = function (targetCard, useSmoothScroll) {
                bundleCards.forEach(function (card) {
                    card.classList.remove('is-active');
                });

                targetCard.classList.add('is-active');
                if (bundleDetailCta && targetCard && targetCard.getAttribute('data-detail-url')) {
                    bundleDetailCta.setAttribute('href', targetCard.getAttribute('data-detail-url'));
                }
                centerBundleCard(targetCard, useSmoothScroll);
            };

            bundleCards.forEach(function (card) {
                card.addEventListener('click', function () {
                    setActiveBundleCard(card, true);
                });
            });

            var initialActiveBundle = document.querySelector('.edtika-bundles__card.is-active');
            setTimeout(function () {
                centerBundleCard(initialActiveBundle, false);
                if (bundleDetailCta && initialActiveBundle && initialActiveBundle.getAttribute('data-detail-url')) {
                    bundleDetailCta.setAttribute('href', initialActiveBundle.getAttribute('data-detail-url'));
                }
            }, 0);

            window.addEventListener('resize', function () {
                var currentActiveBundle = document.querySelector('.edtika-bundles__card.is-active');
                centerBundleCard(currentActiveBundle, false);
            });

            window.addEventListener('load', function () {
                var currentActiveBundle = document.querySelector('.edtika-bundles__card.is-active');
                centerBundleCard(currentActiveBundle, false);
                if (bundleDetailCta && currentActiveBundle && currentActiveBundle.getAttribute('data-detail-url')) {
                    bundleDetailCta.setAttribute('href', currentActiveBundle.getAttribute('data-detail-url'));
                }
            });

            faqItems.forEach(function (item) {
                var questionButton = item.querySelector('.edtika-faq__question');
                var answerBlock = item.querySelector('.edtika-faq__answer');

                if (!questionButton || !answerBlock) {
                    return;
                }

                if (item.classList.contains('is-open')) {
                    answerBlock.style.maxHeight = answerBlock.scrollHeight + 'px';
                }

                questionButton.addEventListener('click', function () {
                    var isOpen = item.classList.contains('is-open');

                    faqItems.forEach(function (otherItem) {
                        var otherButton = otherItem.querySelector('.edtika-faq__question');
                        var otherAnswer = otherItem.querySelector('.edtika-faq__answer');

                        otherItem.classList.remove('is-open');

                        if (otherButton) {
                            otherButton.setAttribute('aria-expanded', 'false');
                        }

                        if (otherAnswer) {
                            otherAnswer.style.maxHeight = '0px';
                        }
                    });

                    if (!isOpen) {
                        item.classList.add('is-open');
                        questionButton.setAttribute('aria-expanded', 'true');
                        answerBlock.style.maxHeight = answerBlock.scrollHeight + 'px';
                    }
                });
            });

            window.addEventListener('resize', function () {
                var openFaqItem = document.querySelector('.edtika-faq__item.is-open');

                if (!openFaqItem) {
                    return;
                }

                var openAnswer = openFaqItem.querySelector('.edtika-faq__answer');

                if (openAnswer) {
                    openAnswer.style.maxHeight = openAnswer.scrollHeight + 'px';
                }
            });

            // Blog Pagination
            var blogPaginationDots = document.querySelectorAll('.edtika-blog__pagination-dot');

            blogPaginationDots.forEach(function (dot) {
                dot.addEventListener('click', function () {
                    blogPaginationDots.forEach(function (d) {
                        d.classList.remove('is-active');
                    });
                    dot.classList.add('is-active');
                });
            });
        })();
    </script>
@endpush

