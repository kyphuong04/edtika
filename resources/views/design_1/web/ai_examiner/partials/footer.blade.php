@php $aieFooterEn = mb_strtolower(app()->getLocale()) === 'en'; @endphp

<footer class="edtika-footer" aria-label="{{ $aieFooterEn ? 'Footer' : 'Chân trang' }}">
    <div class="edtika-footer__top">
        <div>
            <h3 class="edtika-footer__brand">EDTIKA</h3>
            <p class="edtika-footer__description">{{ $aieFooterEn ? 'This platform is designed to help institutions, educators, and learners manage, deliver, and track learning activities effectively.' : 'Nền tảng này được thiết kế để giúp các tổ chức, nhà giáo dục và người học quản lý, cung cấp và theo dõi các hoạt động học tập hiệu quả.' }}</p>
        </div>

        <div class="edtika-footer__right">
            <div>
                <h4 class="edtika-footer__column-title">{{ $aieFooterEn ? 'Support' : 'Hỗ trợ' }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                <ul class="edtika-footer__list">
                    <li><a href="/placement-test">{{ $aieFooterEn ? 'Placement Test' : 'Kiểm tra đầu vào' }}</a></li>
                    <li><a href="/ai-examiner">AI Examiner</a></li>
                    <li><a href="/panel/ielts-tests/mock">{{ $aieFooterEn ? 'Mock Tests' : 'Luyện đề' }}</a></li>
                    <li><a href="/dictionary">{{ $aieFooterEn ? 'Dictionary & Flashcards' : 'Từ điển & Flashcard' }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="edtika-footer__column-title">{{ $aieFooterEn ? 'Introduction' : 'Giới thiệu' }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                <ul class="edtika-footer__list">
                    <li><a href="/classes">{{ $aieFooterEn ? 'Courses' : 'Khóa học' }}</a></li>
                    <li><a href="/#faq">FAQ</a></li>
                    <li><a href="/blog">{{ $aieFooterEn ? 'News' : 'Tin tức' }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="edtika-footer__column-title">{{ $aieFooterEn ? 'Contact Information' : 'Thông tin liên hệ' }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                <div class="edtika-footer__contact-row">
                    <p class="edtika-footer__contact-label">{{ $aieFooterEn ? 'Hotline' : 'Số điện thoại/Hotline' }}</p>
                    <p class="edtika-footer__contact-value">0987 654 321</p>
                </div>
                <div class="edtika-footer__contact-row">
                    <p class="edtika-footer__contact-label">Email</p>
                    <p class="edtika-footer__contact-value">contact@edtika.com</p>
                </div>
                <div class="edtika-footer__contact-row">
                    <p class="edtika-footer__contact-label">{{ $aieFooterEn ? 'Address' : 'Địa chỉ' }}</p>
                    <p class="edtika-footer__contact-value">{{ $aieFooterEn ? 'Ho Chi Minh City, Vietnam' : 'Thành phố Hồ Chí Minh, Việt Nam' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="edtika-footer__bottom">
        <p class="edtika-footer__copyright">
            {{ $aieFooterEn ? 'Copyright 2026' : 'Bản quyền 2026' }} &copy; <span class="edtika-footer__copyright-brand">Edtika.</span> {{ $aieFooterEn ? 'All rights reserved.' : 'Mọi quyền được bảo lưu.' }}
        </p>

        <div class="edtika-footer__social" aria-label="Social links">
            <a class="edtika-footer__social-link" href="#" aria-label="Facebook">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M13.74 20V12.7H16.21L16.58 9.86H13.74V8.05C13.74 7.23 13.97 6.68 15.15 6.68H16.68V4.14C16.42 4.1 15.52 4 14.47 4C12.29 4 10.8 5.33 10.8 7.77V9.86H8.34V12.7H10.8V20H13.74Z" fill="currentColor"/></svg>
            </a>
            <a class="edtika-footer__social-link" href="#" aria-label="Instagram">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3.6" stroke="currentColor" stroke-width="2"/><circle cx="16.7" cy="7.3" r="1" fill="currentColor"/></svg>
            </a>
            <a class="edtika-footer__social-link" href="#" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M7.23 9.5H4.75V17.5H7.23V9.5Z" fill="currentColor"/><path d="M6 8.36C6.79 8.36 7.43 7.72 7.43 6.93C7.43 6.14 6.79 5.5 6 5.5C5.21 5.5 4.57 6.14 4.57 6.93C4.57 7.72 5.21 8.36 6 8.36Z" fill="currentColor"/><path d="M11.1 9.5H8.72V17.5H11.19V13.54C11.19 12.5 11.39 11.49 12.68 11.49C13.95 11.49 13.97 12.68 13.97 13.61V17.5H16.45V13.11C16.45 10.95 15.99 9.29 13.47 9.29C12.26 9.29 11.45 9.95 11.1 10.57V9.5Z" fill="currentColor"/></svg>
            </a>
        </div>

        <p class="edtika-footer__policies">
            {{ $aieFooterEn ? 'Terms & Policies' : 'Điều khoản & Chính sách' }}<span class="edtika-footer__dot">•</span>{{ $aieFooterEn ? 'Privacy Policy' : 'Chính sách bảo mật' }}
        </p>
    </div>
</footer>
