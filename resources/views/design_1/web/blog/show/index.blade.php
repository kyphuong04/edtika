@extends("design_1.web.layouts.app")

@php
    $appHeader = false;
    $appFooter = false;
    $isEnglish = app()->getLocale() === 'en';
    $nextLocale = $isEnglish ? 'vi' : 'en';
    $nextLocaleLabel = $isEnglish ? 'VI' : 'ENG';
    $isHomeActive = request()->path() === '/';
    $isClassesActive = request()->is('classes') || request()->is('classes/*');
    $isPlacementActive = request()->is('panel/ielts-tests/practice') || request()->is('panel/ielts-tests/practice/*');
    $isMockActive = request()->is('panel/ielts-tests/mock') || request()->is('panel/ielts-tests/mock/*');
    $isDictionaryActive = request()->is('panel/dictionary') || request()->is('panel/dictionary/*');
    $isNewsActive = request()->is('blog') || request()->is('blog/*');

    $headerLinks = [
        ['label' => $isEnglish ? 'Home' : 'Trang chủ', 'url' => '/', 'active' => $isHomeActive],
        ['label' => $isEnglish ? 'Courses' : 'Khóa học', 'url' => '/classes', 'active' => $isClassesActive],
        ['label' => $isEnglish ? 'Placement Test' : 'Kiểm tra đầu vào', 'url' => '/panel/ielts-tests/practice', 'requiresAuth' => true, 'active' => $isPlacementActive],
        ['label' => $isEnglish ? 'Mock Tests' : 'Luyện đề', 'url' => '/panel/ielts-tests/mock', 'requiresAuth' => true, 'active' => $isMockActive],
        ['label' => $isEnglish ? 'Dictionary & Flashcards' : 'Từ điển & Flashcard', 'url' => '/panel/dictionary', 'requiresAuth' => true, 'active' => $isDictionaryActive],
        ['label' => $isEnglish ? 'Knowledge & News' : 'Kiến thức & Tin tức', 'url' => '/blog', 'active' => $isNewsActive],
    ];

    $relatedSidebarPosts = collect();

    if (!empty($post->relatedPosts) && $post->relatedPosts->isNotEmpty()) {
        $relatedSidebarPosts = $post->relatedPosts
            ->pluck('post')
            ->filter();
    }

    $latestPosts = \App\Models\Blog::query()
        ->where('status', 'publish')
        ->where('id', '!=', $post->id)
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get();
@endphp

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("swiperjs") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("show_blog") }}">

    <style>
        .edtika-news-page {
            --edtika-purple: #511d99;
            --edtika-glass: rgba(212, 211, 254, 0.5);
            --edtika-glass-border: rgba(255, 255, 255, 0.58);
            --edtika-shadow: 0 20px 46px rgba(50, 38, 86, 0.16);
            min-height: 100vh;
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            background: linear-gradient(180deg, #ecebf3 0%, #edf0f6 62%, #eaf4f1 100%);
            position: relative;
            overflow: visible;
        }

        .edtika-news-page__container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1312px;
            padding: 0 28px;
            margin: 0 auto;
            overflow: visible;
        }

        .edtika-news-page__header {
            height: 106px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .edtika-news-page__brand {
            font-size: 54px;
            font-weight: 900;
            color: var(--edtika-purple);
            text-decoration: none;
            line-height: 1;
            letter-spacing: -0.02em;
        }

        .edtika-news-page__nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            flex: 1 1 auto;
            margin: 0 24px;
        }

        .edtika-news-page__nav-link {
            color: #1f1a32;
            font-size: 18px;
            line-height: 1.2;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: opacity .2s ease;
        }

        .edtika-news-page__nav-link:hover,
        .edtika-news-page__nav-link.is-active {
            opacity: .86;
            color: #111118;
        }

        .edtika-news-page__actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .edtika-news-page__lang {
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

        .edtika-news-page__lang-thumb {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: var(--edtika-purple);
            transition: left .2s ease, right .2s ease;
        }

        .edtika-news-page__lang.is-alt .edtika-news-page__lang-thumb {
            left: auto;
            right: 5px;
        }

        .edtika-news-page__lang-button {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            border: 0;
            background: transparent;
            color: var(--edtika-purple);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            letter-spacing: .01em;
            justify-content: flex-end;
            padding-right: 9px;
            padding-left: 44px;
        }

        .edtika-news-page__lang.is-alt .edtika-news-page__lang-button {
            justify-content: flex-start;
            padding-left: 8px;
            padding-right: 48px;
        }

        .edtika-news-page__login,
        .edtika-news-page__avatar-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 50px;
            border-radius: 999px;
            box-shadow: 0 8px 18px rgba(33, 24, 56, 0.18);
            text-decoration: none;
            flex-shrink: 0;
        }

        .edtika-news-page__login {
            padding: 0 24px;
            background: var(--edtika-purple);
            color: #fff;
            font-size: 16px;
            font-weight: 700;
        }

        .edtika-news-page__avatar-link {
            width: 50px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.9);
            background: #fff;
        }

        .edtika-news-page__avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .edtika-news-page__hero {
            width: 100%;
            border-radius: 44px;
            overflow: hidden;
            background: #c9c7d5;
            position: relative;
            box-shadow: 0 20px 36px rgba(46, 39, 66, 0.12);
        }

        .edtika-news-page__hero img {
            width: 100%;
            height: auto;
            display: block;
        }

        .edtika-news-page__hero-card {
            width: 100%;
            margin: -54px auto 0;
            border-radius: 38px;
            border: 1px solid var(--edtika-glass-border);
            background: var(--edtika-glass);
            backdrop-filter: blur(18px) saturate(140%);
            -webkit-backdrop-filter: blur(18px) saturate(140%);
            box-shadow: var(--edtika-shadow);
            padding: 32px 34px;
            position: relative;
            z-index: 4;
        }

        .edtika-news-page__hero-icon {
            position: absolute;
            right: 24px;
            top: -14px;
            width: clamp(130px, 15vw, 210px);
            height: auto;
            filter: drop-shadow(0 18px 26px rgba(81, 29, 153, 0.2));
            transform: rotate(8deg);
            pointer-events: none;
        }

        .edtika-news-page__breadcrumb {
            font-size: 13px;
            font-weight: 600;
            color: rgba(34, 26, 58, 0.64);
            margin-bottom: 14px;
        }

        .edtika-news-page__headline {
            margin: 0;
            color: #0f0f14;
            font-size: clamp(30px, 4vw, 48px);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -0.02em;
        }

        .edtika-news-page__subtitle {
            margin: 12px 0 0;
            color: rgba(20, 20, 28, 0.74);
            font-size: 16px;
            line-height: 1.5;
            font-weight: 600;
        }

        .edtika-news-show__content {
            border-radius: 26px;
            border: 1px solid var(--edtika-glass-border);
            background: #fff;
            backdrop-filter: blur(14px) saturate(130%);
            -webkit-backdrop-filter: blur(14px) saturate(130%);
            box-shadow: 0 16px 32px rgba(52, 42, 84, 0.14);
            padding: 26px;
            min-width: 0;
        }

        .edtika-news-show-layout {
            margin-top: 34px;
            display: grid;
            grid-template-columns: minmax(210px, 248px) minmax(0, 1fr) minmax(230px, 280px);
            gap: 18px;
            align-items: start;
            overflow: visible;
        }

        .edtika-news-show-layout > * {
            min-width: 0;
        }

        .edtika-news-show__sidebar-card {
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.58);
            background: rgba(255, 255, 255, 0.84);
            backdrop-filter: blur(14px) saturate(130%);
            -webkit-backdrop-filter: blur(14px) saturate(130%);
            box-shadow: 0 12px 26px rgba(40, 34, 63, 0.12);
            padding: 18px 16px;
            position: relative;
        }

        .edtika-news-show__sidebar--toc {
            border-radius: 26px;
            overflow: visible;
            align-self: start;
            position: sticky;
            top: 96px;
            z-index: 10;
            height: fit-content;
        }

        .edtika-news-show__sidebar--toc .edtika-news-show__sidebar-card {
            border-radius: 26px;
            position: sticky !important;
            top: 96px !important;
            z-index: 12;
        }

        .edtika-news-show__sidebar--related {
            border-radius: 26px;
            overflow: visible;
            align-self: start;
            position: sticky;
            top: 96px;
            z-index: 10;
            height: fit-content;
        }

        .edtika-news-show__sidebar--related .edtika-news-show__sidebar-card {
            border-radius: 26px;
            position: sticky !important;
            top: 96px !important;
            z-index: 12;
        }

        .edtika-news-show__sidebar-title {
            margin: 0;
            font-size: 32px;
            line-height: 1.2;
            font-weight: 800;
            color: #2f3441;
        }

        .edtika-news-show__toc-list {
            margin: 16px 0 0;
            padding: 0;
            list-style: none;
            max-height: calc(100vh - 180px);
            overflow: auto;
        }

        .edtika-news-show__toc-item {
            margin-top: 8px;
        }

        .edtika-news-show__toc-item:first-child {
            margin-top: 0;
        }

        .edtika-news-show__toc-link {
            display: block;
            color: #718096;
            text-decoration: none;
            font-weight: 600;
            line-height: 1.45;
            transition: color .2s ease;
        }

        .edtika-news-show__toc-item.is-level-3 .edtika-news-show__toc-link,
        .edtika-news-show__toc-item.is-level-4 .edtika-news-show__toc-link,
        .edtika-news-show__toc-item.is-level-5 .edtika-news-show__toc-link,
        .edtika-news-show__toc-item.is-level-6 .edtika-news-show__toc-link {
            padding-left: 14px;
            font-size: 14px;
        }

        .edtika-news-show__toc-item.is-active .edtika-news-show__toc-link {
            color: #e54f26;
            font-weight: 800;
        }

        .edtika-news-show__related-list {
            margin-top: 14px;
            max-height: calc(100vh - 190px);
            overflow: auto;
        }

        .edtika-news-show__related-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 12px;
            text-decoration: none;
            color: #1f2430;
        }

        .edtika-news-show__related-item:first-child {
            margin-top: 0;
        }

        .edtika-news-show__related-image {
            width: 86px;
            height: 64px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .edtika-news-show__related-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .edtika-news-show__latest {
            margin-top: 28px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.58);
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 14px 26px rgba(40, 34, 63, 0.1);
            padding: 20px 22px 28px;
        }

        .edtika-news-show__latest-title {
            margin: 0;
            color: #1f2430;
            font-size: 28px;
            line-height: 1.2;
            font-weight: 800;
        }

        .edtika-news-show__description,
        .edtika-news-show__article {
            overflow-wrap: anywhere;
        }

        .edtika-news-show__description {
            margin: 0;
            padding: 18px;
            border-radius: 16px;
            border: 1px solid rgba(17, 17, 24, 0.08);
            background: rgba(255, 255, 255, 0.7);
        }

        .edtika-news-show__article {
            margin-top: 24px;
            line-height: 1.75;
        }

        .edtika-news-show__article img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
        }

        .edtika-footer {
            position: relative;
            isolation: isolate;
            z-index: 1;
            margin-top: 52px;
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            overflow: hidden;
        }

        .edtika-footer::before,
        .edtika-footer::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: -1;
            filter: blur(68px);
            background: linear-gradient(136deg, rgba(134, 34, 255, 0.34), rgba(60, 225, 97, 0.34));
            opacity: .62;
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
            margin: 0;
            color: #511d99;
            font-size: 42px;
            line-height: 1;
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
            background: #511d99;
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
            color: inherit;
            text-decoration: none;
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
            color: #511d99;
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
            background: #511d99;
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
            color: #511d99;
        }

        @media (max-width: 1199px) {
            .edtika-news-page__nav {
                display: none;
            }

            .edtika-news-show-layout {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .edtika-news-show__sidebar-card {
                position: static;
                top: auto;
            }

            .edtika-news-show__sidebar--toc {
                border-radius: 0;
                overflow: visible;
                position: static;
                top: auto;
                z-index: auto;
            }

            .edtika-news-show__sidebar--related {
                border-radius: 0;
                overflow: visible;
                position: static;
                top: auto;
                z-index: auto;
            }

            .edtika-news-show__sidebar--toc .edtika-news-show__sidebar-card {
                border-radius: 22px;
            }

            .edtika-news-show__sidebar--related .edtika-news-show__sidebar-card {
                border-radius: 22px;
                position: static !important;
                top: auto !important;
            }

            .edtika-news-show__sidebar--toc .edtika-news-show__sidebar-card {
                display: block;
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transform: none;
            }

            .edtika-news-show__sidebar--toc {
                order: -1;
            }

            .edtika-news-show__toc-list {
                max-height: none;
            }

            .edtika-footer__top {
                grid-template-columns: 1fr;
                gap: 42px;
                padding-top: 44px;
            }

            .edtika-footer__right {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 28px;
            }

            .edtika-footer__bottom {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .edtika-footer__copyright,
            .edtika-footer__policies {
                text-align: center;
            }
        }

        @media (max-width: 767px) {
            .edtika-news-page__container,
            .edtika-footer__top,
            .edtika-footer__bottom {
                padding-left: 16px;
                padding-right: 16px;
            }

            .edtika-news-page__header {
                height: 84px;
                gap: 12px;
            }

            .edtika-news-page__brand {
                font-size: 38px;
            }

            .edtika-news-page__hero-card {
                padding: 22px 18px;
            }

            .edtika-news-show__content {
                padding: 16px;
            }

            .edtika-news-show__sidebar-title {
                font-size: 26px;
            }

            .edtika-news-show__latest {
                padding: 16px;
            }

            .edtika-news-show__latest-title {
                font-size: 24px;
            }

            .edtika-footer__right {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .edtika-footer__brand {
                font-size: 36px;
            }

            .edtika-footer__description {
                font-size: 16px;
            }
        }
    </style>
@endpush


@section("content")
    <div class="edtika-news-page">
        <div class="edtika-news-page__container">
            <header class="edtika-news-page__header" aria-label="{{ $isEnglish ? 'News header' : 'Đầu trang tin tức' }}">
                <a href="/" class="edtika-news-page__brand" aria-label="EDTIKA Home">EDTIKA</a>

                <nav class="edtika-news-page__nav" aria-label="{{ $isEnglish ? 'Main navigation' : 'Điều hướng chính' }}">
                    @foreach($headerLinks as $headerLink)
                        <a href="{{ $headerLink['url'] }}" class="edtika-news-page__nav-link {{ !empty($headerLink['active']) ? 'is-active' : '' }}" @if(!empty($headerLink['requiresAuth']) && auth()->guest()) data-open-auth-modal="true" @endif>
                            {{ $headerLink['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="edtika-news-page__actions">
                    <div class="edtika-news-page__lang {{ $nextLocale === 'vi' ? 'is-alt' : '' }}">
                        <div class="edtika-news-page__lang-thumb"></div>
                        <form action="/locale" method="post" class="m-0 h-100">
                            {{ csrf_field() }}
                            <button class="edtika-news-page__lang-button" type="submit" name="locale" value="{{ $nextLocale }}">{{ $nextLocaleLabel }}</button>
                        </form>
                    </div>

                    @if(auth()->check())
                        <a href="/panel" class="edtika-news-page__avatar-link" aria-label="{{ auth()->user()->full_name }}" title="{{ auth()->user()->full_name }}">
                            <img src="{{ auth()->user()->getAvatar(80) }}" alt="{{ auth()->user()->full_name }}" class="edtika-news-page__avatar-image">
                        </a>
                    @else
                        <a href="/login" class="edtika-news-page__login" data-open-auth-modal="true">{{ $isEnglish ? 'Log in' : 'Đăng nhập' }}</a>
                    @endif
                </div>
            </header>

            <div class="edtika-news-show-layout">
                <aside class="edtika-news-show__sidebar edtika-news-show__sidebar--toc js-article-toc-wrapper" hidden>
                    <div class="edtika-news-show__sidebar-card js-article-toc-card">
                        <h2 class="edtika-news-show__sidebar-title">{{ $isEnglish ? 'Table of content' : 'Mục lục' }}</h2>
                        <ul class="edtika-news-show__toc-list js-article-toc-list"></ul>
                    </div>
                </aside>

                <section class="edtika-news-show__content edtika-news-show__content--main">
                    @include('design_1.web.blog.show.includes.header')

                    @if(!empty($post->description))
                        <div class="edtika-news-show__description mt-24">
                            {!! nl2br($post->description) !!}
                        </div>
                    @endif

                    <div class="edtika-news-show__article js-blog-article-content">
                        {!! nl2br($post->content) !!}
                    </div>

                    @if($post->enable_comment)
                        @include('design_1.web.blog.show.includes.comments')
                    @endif
                </section>

                @if($relatedSidebarPosts->isNotEmpty())
                    <aside class="edtika-news-show__sidebar edtika-news-show__sidebar--related">
                        <div class="edtika-news-show__sidebar-card">
                            <h2 class="edtika-news-show__sidebar-title">{{ $isEnglish ? 'You may also be interested?' : 'Có thể bạn cũng quan tâm?' }}</h2>

                            <div class="edtika-news-show__related-list">
                                @foreach($relatedSidebarPosts->take(5) as $relatedPost)
                                    <a href="{{ $relatedPost->getUrl() }}" class="edtika-news-show__related-item">
                                        <img src="{{ $relatedPost->image }}" alt="{{ $relatedPost->title }}" class="edtika-news-show__related-image">
                                        <p class="edtika-news-show__related-title">{{ $relatedPost->title }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </aside>
                @endif
            </div>

            @if($latestPosts->isNotEmpty())
                <section class="edtika-news-show__latest">
                    <h2 class="edtika-news-show__latest-title">{{ $isEnglish ? 'Suggested Latest Posts' : 'Gợi ý Bài viết Mới nhất' }}</h2>

                    <div class="row">
                        @include('design_1.web.blog.components.cards.grids.index',['posts' => $latestPosts, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-16"])
                    </div>
                </section>
            @endif
        </div>

        <footer class="edtika-footer" aria-label="{{ $isEnglish ? 'Footer' : 'Chân trang' }}">
            <div class="edtika-footer__top">
                <div>
                    <h3 class="edtika-footer__brand">EDTIKA</h3>
                    <p class="edtika-footer__description">
                        {{ $isEnglish
                            ? 'This platform helps organizations, educators, and learners manage, deliver, and track training activities efficiently.'
                            : 'Nền tảng này được thiết kế để giúp các tổ chức, nhà giáo dục và người học quản lý, cung cấp và theo dõi các hoạt động học tập và đào tạo.' }}
                    </p>
                </div>

                <div class="edtika-footer__right">
                    <div>
                        <h4 class="edtika-footer__column-title">{{ $isEnglish ? 'Support' : 'Hỗ trợ' }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                        <ul class="edtika-footer__list">
                            <li><a href="/panel/ielts-tests/practice" @if(auth()->guest()) data-open-auth-modal="true" @endif>{{ $isEnglish ? 'Placement Test' : 'Kiểm tra đầu vào' }}</a></li>
                            <li><a href="/panel/ielts-tests/mock" @if(auth()->guest()) data-open-auth-modal="true" @endif>{{ $isEnglish ? 'Mock Tests' : 'Luyện đề' }}</a></li>
                            <li><a href="/panel/dictionary" @if(auth()->guest()) data-open-auth-modal="true" @endif>{{ $isEnglish ? 'Dictionary & Flashcards' : 'Từ điển & Flashcard' }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="edtika-footer__column-title">{{ $isEnglish ? 'About' : 'Giới thiệu' }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                        <ul class="edtika-footer__list">
                            <li><a href="/classes">{{ $isEnglish ? 'Courses' : 'Khóa học' }}</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="/blog">{{ $isEnglish ? 'News' : 'Tin tức' }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="edtika-footer__column-title">{{ $isEnglish ? 'Contact' : 'Thông tin liên hệ' }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>

                        <div class="edtika-footer__contact-row">
                            <p class="edtika-footer__contact-label">{{ $isEnglish ? 'Hotline' : 'Số điện thoại/Hotline' }}</p>
                            <p class="edtika-footer__contact-value">0987 654 321</p>
                        </div>

                        <div class="edtika-footer__contact-row">
                            <p class="edtika-footer__contact-label">{{ $isEnglish ? 'Email' : 'Thư điện tử' }}</p>
                            <p class="edtika-footer__contact-value">contact@edtika.com</p>
                        </div>

                        <div class="edtika-footer__contact-row">
                            <p class="edtika-footer__contact-label">{{ $isEnglish ? 'Address' : 'Địa chỉ' }}</p>
                            <p class="edtika-footer__contact-value">{{ $isEnglish ? 'Ho Chi Minh City, Vietnam' : 'Thành phố Hồ Chí Minh, Việt Nam' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="edtika-footer__bottom">
                <p class="edtika-footer__copyright">
                    {{ $isEnglish ? 'Copyright 2026 © ' : 'Bản quyền 2026 © ' }}<span class="edtika-footer__copyright-brand">Edtika.</span> {{ $isEnglish ? 'All rights reserved.' : 'Mọi quyền được bảo lưu.' }}
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
                    {{ $isEnglish ? 'Terms & Policies' : 'Điều khoản & Chính sách' }} <span class="edtika-footer__dot">•</span> {{ $isEnglish ? 'Privacy Policy' : 'Chính sách bảo mật' }}
                </p>
            </div>
        </footer>
    </div>

@endsection

@push('scripts_bottom')
    <script>
        var closeLang = '{{ trans('public.close') }}';
        var shareLang = '{{ trans('public.share') }}';
        var reportCommentLang = '{{ trans('update.report_comment') }}';
        var reportLang = '{{ trans('panel.report') }}';
    </script>

    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>

    <script src="{{ getDesign1ScriptPath("comments") }}"></script>
    <script src="{{ getDesign1ScriptPath("show_blog") }}"></script>

    <script>
        (function ($) {
            "use strict";

            $(function () {
                const $article = $('.js-blog-article-content').first();
                const $tocWrapper = $('.js-article-toc-wrapper');
                const $tocCard = $('.js-article-toc-card');
                const $tocList = $('.js-article-toc-list');

                if (!$article.length || !$tocWrapper.length || !$tocCard.length || !$tocList.length) {
                    return;
                }

                const selectors = 'h1, h2, h3, h4, h5, h6, p:has(> strong:first-child), p:has(> b:first-child), li:has(> strong:first-child), li:has(> b:first-child)';

                const headingNodes = $article.find(selectors).filter(function () {
                    const text = $(this).text().replace(/\s+/g, ' ').trim();
                    return text.length >= 6;
                });

                if (!headingNodes.length) {
                    return;
                }

                $tocWrapper.removeAttr('hidden');

                const headingItems = [];

                headingNodes.each(function (index) {
                    const $node = $(this);
                    const text = $node.text().replace(/\s+/g, ' ').trim();

                    if (!text) {
                        return;
                    }

                    let level = 2;
                    const tagName = ($node.prop('tagName') || '').toLowerCase();

                    if (tagName.charAt(0) === 'h') {
                        const parsedLevel = parseInt(tagName.replace('h', ''), 10);

                        if (!isNaN(parsedLevel)) {
                            level = parsedLevel;
                        }
                    } else if (!/^([IVXLC]+\.|\d+[\.)])\s+/i.test(text)) {
                        level = 3;
                    }

                    let headingId = $node.attr('id');

                    if (!headingId) {
                        headingId = 'post-heading-' + (index + 1);
                        $node.attr('id', headingId);
                    }

                    const $listItem = $('<li>', {
                        'class': 'edtika-news-show__toc-item is-level-' + level,
                        'data-target-id': headingId,
                    });

                    const $link = $('<a>', {
                        'class': 'edtika-news-show__toc-link',
                        'href': '#' + headingId,
                        'text': text,
                    });

                    $listItem.append($link);
                    $tocList.append($listItem);

                    headingItems.push({
                        id: headingId,
                        node: $node.get(0),
                    });
                });

                if (!headingItems.length) {
                    $tocWrapper.attr('hidden', true);
                    return;
                }

                $tocWrapper.removeClass('is-pinned is-visible').css('min-height', '');
                $tocCard.css({
                    left: '',
                    width: '',
                });

                const $tocItems = $tocList.find('.edtika-news-show__toc-item');

                const markActiveHeading = function (headingId) {
                    $tocItems.removeClass('is-active');
                    $tocItems.filter('[data-target-id="' + headingId + '"]').addClass('is-active');
                };

                let activeId = headingItems[0].id;
                markActiveHeading(activeId);

                const observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            activeId = entry.target.id;
                            markActiveHeading(activeId);
                        }
                    });
                }, {
                    rootMargin: '-22% 0px -58% 0px',
                    threshold: [0, 1],
                });

                headingItems.forEach(function (item) {
                    observer.observe(item.node);
                });

                $tocList.on('click', '.edtika-news-show__toc-link', function (event) {
                    event.preventDefault();

                    const targetSelector = $(this).attr('href');
                    const $target = $(targetSelector);

                    if (!$target.length) {
                        return;
                    }

                    const scrollTop = $target.offset().top - 110;

                    $('html, body').animate({ scrollTop: scrollTop }, 250);
                });
            });
        })(jQuery);
    </script>
@endpush
