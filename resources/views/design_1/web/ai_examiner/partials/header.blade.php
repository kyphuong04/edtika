@php
    $aieIsEnglish = mb_strtolower(app()->getLocale()) === 'en';
    $aieNextLocale = $aieIsEnglish ? 'vi' : 'en';
    $aieNavLinks = [
        ['label' => $aieIsEnglish ? 'Home' : 'Trang chủ', 'url' => '/', 'active' => false],
        ['label' => $aieIsEnglish ? 'Courses' : 'Khóa học', 'url' => '/classes', 'active' => false],
        ['label' => $aieIsEnglish ? 'Placement Test' : 'Kiểm tra đầu vào', 'url' => '/placement-test', 'active' => false],
        ['label' => 'AI Examiner', 'url' => '/ai-examiner', 'active' => true],
        ['label' => $aieIsEnglish ? 'Mock Tests' : 'Luyện đề', 'url' => '/panel/ielts-tests/mock', 'active' => false, 'requiresAuth' => true],
        ['label' => $aieIsEnglish ? 'Dictionary & Flashcards' : 'Từ điển & Flashcard', 'url' => '/dictionary', 'active' => false],
        ['label' => $aieIsEnglish ? 'News' : 'Kiến thức & Tin tức', 'url' => '/blog', 'active' => false],
    ];
@endphp

<header class="aie-header">
    <div class="aie-header__inner">
        <a href="/" class="aie-header__brand" aria-label="EDTIKA">EDTIKA</a>

        <nav class="aie-header__nav" aria-label="Điều hướng chính">
            @foreach($aieNavLinks as $aieLink)
                <a href="{{ $aieLink['url'] }}" class="aie-header__link {{ $aieLink['active'] ? 'is-active' : '' }}" @if(!empty($aieLink['requiresAuth']) && auth()->guest()) data-open-auth-modal="true" @endif>{{ $aieLink['label'] }}</a>
            @endforeach
        </nav>

        <div class="aie-header__actions">
            <form action="/locale" method="post" class="aie-header__lang">
                @csrf
                <button type="submit" name="locale" value="{{ $aieNextLocale }}" aria-label="Đổi ngôn ngữ">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.6-3.8-9S9.5 5.6 12 3z"/></svg>
                    <span>{{ $aieIsEnglish ? 'ENG' : 'VI' }}</span>
                </button>
            </form>

            @if(auth()->check())
                <a href="/panel" class="aie-header__avatar" title="{{ auth()->user()->full_name }}">
                    <img src="{{ auth()->user()->getAvatar(80) }}" alt="{{ auth()->user()->full_name }}">
                </a>
            @else
                <a href="/login" class="aie-header__login" data-open-auth-modal="true">{{ $aieIsEnglish ? 'Log in' : 'Đăng nhập' }}</a>
            @endif
        </div>
    </div>
</header>
