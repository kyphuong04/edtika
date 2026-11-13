{{-- GĐ5: header EDTIKA cho trang /bundles (cùng kiểu với header AI Examiner), mục "Khóa học" đang chọn --}}
@php
    $sfIsEnglish = mb_strtolower(app()->getLocale()) === 'en';
    $sfNextLocale = $sfIsEnglish ? 'vi' : 'en';
    $sfNavLinks = [
        ['label' => $sfIsEnglish ? 'Home' : 'Trang chủ', 'url' => '/', 'active' => false],
        ['label' => $sfIsEnglish ? 'Courses' : 'Khóa học', 'url' => '/classes', 'active' => true],
        ['label' => $sfIsEnglish ? 'Placement Test' : 'Kiểm tra đầu vào', 'url' => '/placement-test', 'active' => false],
        ['label' => 'AI Examiner', 'url' => '/ai-examiner', 'active' => false],
        ['label' => $sfIsEnglish ? 'Mock Tests' : 'Luyện đề', 'url' => '/panel/ielts-tests/mock', 'active' => false],
        ['label' => $sfIsEnglish ? 'Dictionary & Flashcards' : 'Từ điển & Flashcard', 'url' => '/panel/dictionary', 'active' => false],
        ['label' => $sfIsEnglish ? 'News' : 'Kiến thức & Tin tức', 'url' => '/blog', 'active' => false],
    ];
@endphp

<style>
    .sf-header { background: rgba(255, 255, 255, .92); border-bottom: 1px solid #efe8f7; position: sticky; top: 0; z-index: 50; backdrop-filter: blur(8px); }
    .sf-header__inner { width: min(1320px, 100%); margin: 0 auto; padding: 14px 20px; display: flex; align-items: center; gap: 24px; }
    .sf-header__brand { font-size: 28px; font-weight: 900 !important; letter-spacing: -.5px; color: #5A2B81 !important; flex-shrink: 0; }
    .sf-header__nav { display: flex; align-items: center; gap: 6px; flex: 1; justify-content: center; flex-wrap: wrap; }
    .sf-header__link { padding: 9px 14px; border-radius: 999px; color: #3b2b52 !important; font-size: 14px; font-weight: 500; white-space: nowrap; }
    .sf-header__link:hover { color: #5A2B81 !important; background: #EBE5FC; }
    .sf-header__link.is-active { background: #5A2B81; color: #fff !important; font-weight: 700; }
    .sf-header__actions { display: flex; align-items: center; gap: 14px; flex-shrink: 0; }
    .sf-header__lang { margin: 0; }
    .sf-header__lang button { border: 0; background: none; color: #5A2B81; font-weight: 700; cursor: pointer; }
    .sf-header__avatar img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #EBE5FC; }
    .sf-header__login { padding: 9px 18px; border-radius: 999px; background: #5A2B81; color: #fff !important; font-weight: 700; font-size: 14px; }
    @media (max-width: 991px) {
        .sf-header__inner { flex-wrap: wrap; }
        .sf-header__nav { order: 3; width: 100%; justify-content: flex-start; overflow-x: auto; flex-wrap: nowrap; }
        .sf-header__actions { margin-left: auto; }
    }
</style>

<header class="sf-header">
    <div class="sf-header__inner">
        <a href="/" class="sf-header__brand" aria-label="EDTIKA">EDTIKA</a>

        <nav class="sf-header__nav" aria-label="Điều hướng chính">
            @foreach($sfNavLinks as $sfLink)
                <a href="{{ $sfLink['url'] }}" class="sf-header__link {{ $sfLink['active'] ? 'is-active' : '' }}">{{ $sfLink['label'] }}</a>
            @endforeach
        </nav>

        <div class="sf-header__actions">
            <form action="/locale" method="post" class="sf-header__lang">
                @csrf
                <button type="submit" name="locale" value="{{ $sfNextLocale }}">{{ $sfIsEnglish ? 'ENG' : 'VI' }}</button>
            </form>

            @if(auth()->check())
                <a href="/panel" class="sf-header__avatar" title="{{ auth()->user()->full_name }}">
                    <img src="{{ auth()->user()->getAvatar(80) }}" alt="{{ auth()->user()->full_name }}">
                </a>
            @else
                <a href="/login" class="sf-header__login">{{ $sfIsEnglish ? 'Log in' : 'Đăng nhập' }}</a>
            @endif
        </div>
    </div>
</header>
