{{--
    Footer EDTIKA dùng chung cho trang home, trang khóa học (/classes, /bundles) và trang chi tiết khóa học.
    CSS: /assets/design_1/css/edtika-footer.css (tự nạp qua @push).
    Trang dùng partial này nên đặt $appFooter = true để tắt footer của theme Rocket LMS.
--}}
@push('styles_top')
    <link rel="stylesheet" href="/assets/design_1/css/edtika-footer.css?v=1">
@endpush

@php
    $edtikaFooterIsEnglish = app()->getLocale() === 'en';
    $edtikaFooter = $edtikaFooterIsEnglish ? [
        'aria' => 'Footer',
        'description' => 'This platform is designed to help organizations, educators, and learners manage, deliver, and track learning and training activities.',
        'supportTitle' => 'Support',
        'supportLinks' => [
            ['label' => 'Placement Test', 'url' => '/placement-test'],
            ['label' => 'Mock Tests', 'url' => '/panel/ielts-tests/mock'],
            ['label' => 'Dictionary & Flashcards', 'url' => '/panel/dictionary'],
        ],
        'introTitle' => 'About',
        'introLinks' => [
            ['label' => 'Courses', 'url' => '/classes'],
            ['label' => 'FAQ', 'url' => '#'],
            ['label' => 'News', 'url' => '/blog'],
        ],
        'contactTitle' => 'Contact Info',
        'hotlineLabel' => 'Phone/Hotline',
        'hotlineValue' => '0987 654 321',
        'emailLabel' => 'Email',
        'emailValue' => 'contact@edtika.com',
        'addressLabel' => 'Address',
        'addressValue' => 'Ho Chi Minh City, Vietnam',
        'copyright' => 'Copyright ' . date('Y'),
        'allRightsReserved' => 'All rights reserved.',
        'terms' => 'Terms & Policies',
        'privacy' => 'Privacy Policy',
    ] : [
        'aria' => 'Chân trang',
        'description' => 'Nền tảng này được thiết kế để giúp các tổ chức, nhà giáo dục và người học quản lý, cung cấp và theo dõi các hoạt động học tập và đào tạo.',
        'supportTitle' => 'Hỗ trợ',
        'supportLinks' => [
            ['label' => 'Kiểm tra đầu vào', 'url' => '/placement-test'],
            ['label' => 'Luyện đề', 'url' => '/panel/ielts-tests/mock'],
            ['label' => 'Từ điển & Flashcard', 'url' => '/panel/dictionary'],
        ],
        'introTitle' => 'Giới thiệu',
        'introLinks' => [
            ['label' => 'Khóa học', 'url' => '/classes'],
            ['label' => 'FAQ', 'url' => '#'],
            ['label' => 'Tin tức', 'url' => '/blog'],
        ],
        'contactTitle' => 'Thông tin liên hệ',
        'hotlineLabel' => 'Số điện thoại/Hotline',
        'hotlineValue' => '0987 654 321',
        'emailLabel' => 'Thư điện tử',
        'emailValue' => 'contact@edtika.com',
        'addressLabel' => 'Địa chỉ',
        'addressValue' => 'Thành phố Hồ Chí Minh, Việt Nam',
        'copyright' => 'Bản quyền ' . date('Y'),
        'allRightsReserved' => 'Mọi quyền được bảo lưu.',
        'terms' => 'Điều khoản & Chính sách',
        'privacy' => 'Chính sách bảo mật',
    ];
@endphp

<footer class="edtika-footer" aria-label="{{ $edtikaFooter['aria'] }}">
    <div class="edtika-footer__top">
        <div>
            <h3 class="edtika-footer__brand">EDTIKA</h3>
            <p class="edtika-footer__description">{{ $edtikaFooter['description'] }}</p>
        </div>

        <div class="edtika-footer__right">
            <div>
                <h4 class="edtika-footer__column-title">{{ $edtikaFooter['supportTitle'] }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>

                <ul class="edtika-footer__list">
                    @foreach($edtikaFooter['supportLinks'] as $edtikaFooterLink)
                        <li><a href="{{ $edtikaFooterLink['url'] }}">{{ $edtikaFooterLink['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="edtika-footer__column-title">{{ $edtikaFooter['introTitle'] }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>

                <ul class="edtika-footer__list">
                    @foreach($edtikaFooter['introLinks'] as $edtikaFooterLink)
                        <li><a href="{{ $edtikaFooterLink['url'] }}">{{ $edtikaFooterLink['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="edtika-footer__column-title">{{ $edtikaFooter['contactTitle'] }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>

                <div class="edtika-footer__contact-row">
                    <p class="edtika-footer__contact-label">{{ $edtikaFooter['hotlineLabel'] }}</p>
                    <p class="edtika-footer__contact-value">{{ $edtikaFooter['hotlineValue'] }}</p>
                </div>

                <div class="edtika-footer__contact-row">
                    <p class="edtika-footer__contact-label">{{ $edtikaFooter['emailLabel'] }}</p>
                    <p class="edtika-footer__contact-value">{{ $edtikaFooter['emailValue'] }}</p>
                </div>

                <div class="edtika-footer__contact-row">
                    <p class="edtika-footer__contact-label">{{ $edtikaFooter['addressLabel'] }}</p>
                    <p class="edtika-footer__contact-value">{{ $edtikaFooter['addressValue'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="edtika-footer__bottom">
        <p class="edtika-footer__copyright">
            {{ $edtikaFooter['copyright'] }} &copy; <span class="edtika-footer__copyright-brand">Edtika.</span> {{ $edtikaFooter['allRightsReserved'] }}
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
            {{ $edtikaFooter['terms'] }}<span class="edtika-footer__dot">•</span>{{ $edtikaFooter['privacy'] }}
        </p>
    </div>
</footer>
