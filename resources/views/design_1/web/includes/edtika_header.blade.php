{{--
    Header EDTIKA dùng chung cho trang home, trang khóa học (/classes, /bundles) và trang chi tiết khóa học.
    CSS: /assets/design_1/css/edtika-header.css (tự nạp qua @push).
    Trang dùng partial này nên đặt $appHeader = true để tắt header của theme Rocket LMS.
    $edtikaHeaderWrap = false khi trang đã tự bọc trong khung 1312px (như trang home).
--}}
@push('styles_top')
    <link rel="stylesheet" href="/assets/design_1/css/edtika-header.css?v=1">
@endpush

@php
    $edtikaHeaderIsEnglish = mb_strtolower(app()->getLocale()) === 'en';
    $edtikaHeaderNextLocale = $edtikaHeaderIsEnglish ? 'vi' : 'en';
    $edtikaHeaderNextLabel = $edtikaHeaderNextLocale === 'en' ? 'ENG' : 'VIE';
    $edtikaHeaderSwitchClass = $edtikaHeaderNextLocale === 'en' ? 'is-next-eng' : 'is-next-vie';

    $edtikaHeaderText = $edtikaHeaderIsEnglish ? [
        'aria' => 'Header',
        'home' => 'Home',
        'classes' => 'Courses',
        'placementTest' => 'Placement Test',
        'mockTest' => 'Mock Tests',
        'dictionary' => 'Dictionary & Flashcards',
        'news' => 'Knowledge & News',
        'login' => 'Log in',
    ] : [
        'aria' => 'Đầu trang',
        'home' => 'Trang chủ',
        'classes' => 'Khóa học',
        'placementTest' => 'Kiểm tra đầu vào',
        'mockTest' => 'Luyện đề',
        'dictionary' => 'Từ điển & Flashcard',
        'news' => 'Kiến thức & Tin tức',
        'login' => 'Đăng nhập',
    ];

    // "Khóa học" sáng cả ở /classes, /bundles và trang khóa học của học viên
    $edtikaHeaderActive = [
        'home' => request()->path() === '/',
        'classes' => request()->is('classes') || request()->is('classes/*') || request()->is('bundles') || request()->is('bundles/*'),
        'placementTest' => request()->is('placement-test') || request()->is('placement-test/*') || request()->is('panel/ielts-tests/diagnostic') || request()->is('panel/ielts-tests/diagnostic/*'),
        'aiExaminer' => request()->is('ai-examiner') || request()->is('ai-examiner/*'),
        'mockTest' => request()->is('panel/ielts-tests/mock') || request()->is('panel/ielts-tests/mock/*'),
        'dictionary' => request()->is('dictionary') || request()->is('dictionary/*') || request()->is('panel/dictionary') || request()->is('panel/dictionary/*'),
        'news' => request()->is('blog') || request()->is('blog/*'),
    ];
    $edtikaHeaderWrap = $edtikaHeaderWrap ?? true;
@endphp

@if($edtikaHeaderWrap)
    <div class="edtika-site-header">
        <div class="edtika-site-header__container">
@endif

<header class="edtika-homepage__header" aria-label="{{ $edtikaHeaderText['aria'] }}">
    <a href="/" class="text-decoration-none" aria-label="EDTIKA Home">
        <h1 class="edtika-homepage__brand">EDTIKA</h1>
    </a>

    <nav class="edtika-homepage__nav" aria-label="Main Navigation">
        <a href="/" class="edtika-homepage__nav-link {{ $edtikaHeaderActive['home'] ? 'is-active' : '' }}">{{ $edtikaHeaderText['home'] }}</a>
        <a href="/classes" class="edtika-homepage__nav-link {{ $edtikaHeaderActive['classes'] ? 'is-active' : '' }}">{{ $edtikaHeaderText['classes'] }}</a>
        <a href="/placement-test" class="edtika-homepage__nav-link {{ $edtikaHeaderActive['placementTest'] ? 'is-active' : '' }}">{{ $edtikaHeaderText['placementTest'] }}</a>
        <a href="/ai-examiner" class="edtika-homepage__nav-link {{ $edtikaHeaderActive['aiExaminer'] ? 'is-active' : '' }}">AI Examiner</a>

        @if(auth()->check())
            <a href="/panel/ielts-tests/mock" class="edtika-homepage__nav-link {{ $edtikaHeaderActive['mockTest'] ? 'is-active' : '' }}">{{ $edtikaHeaderText['mockTest'] }}</a>
        @else
            <a href="/panel/ielts-tests/mock" class="edtika-homepage__nav-link {{ $edtikaHeaderActive['mockTest'] ? 'is-active' : '' }}" data-open-auth-modal="true">{{ $edtikaHeaderText['mockTest'] }}</a>
        @endif

        <a href="/dictionary" class="edtika-homepage__nav-link {{ $edtikaHeaderActive['dictionary'] ? 'is-active' : '' }}">{{ $edtikaHeaderText['dictionary'] }}</a>
        <a href="/blog" class="edtika-homepage__nav-link {{ $edtikaHeaderActive['news'] ? 'is-active' : '' }}">{{ $edtikaHeaderText['news'] }}</a>
    </nav>

    <div class="edtika-homepage__actions">
        <div class="edtika-lang-switch {{ $edtikaHeaderSwitchClass }}" aria-label="Language Switch">
            <div class="edtika-lang-switch__thumb"></div>

            <form class="edtika-lang-switch__form" action="/locale" method="post">
                {{ csrf_field() }}
                <button class="edtika-lang-switch__button" type="submit" name="locale" value="{{ $edtikaHeaderNextLocale }}" aria-label="Switch language">
                    {{ $edtikaHeaderNextLabel }}
                </button>
            </form>
        </div>

        @if(auth()->check())
            <a href="/panel" class="edtika-user-avatar-btn" aria-label="{{ auth()->user()->full_name }}" title="{{ auth()->user()->full_name }}">
                <img src="{{ auth()->user()->getAvatar(80) }}" alt="{{ auth()->user()->full_name }}" class="edtika-user-avatar-btn__image">
            </a>
        @else
            <a href="/login" class="edtika-login-btn" data-open-auth-modal="true">{{ $edtikaHeaderText['login'] }}</a>
        @endif
    </div>
</header>

@if($edtikaHeaderWrap)
        </div>
    </div>
@endif
