@php
    $appHeader = true;
    $appFooter = true;
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

    if (!empty($selectedCategory) and !empty($selectedCategory->cover_image)) {
        $pageHeroImage = $selectedCategory->cover_image;
    } else {
        $pageHeroImage = getThemePageBackgroundSettings('blog_lists');
    }

    $blogPageTitle = $isEnglish ? 'Knowledge & News' : 'Kiến thức & Tin tức';
    if (!empty($selectedAuthor)) {
        $blogPageTitle = $selectedAuthor->full_name;
    } elseif (!empty($selectedCategory)) {
        $blogPageTitle = $selectedCategory->title;
    }

    $blogPageSubtitle = $isEnglish
        ? 'Latest updates, learning tips, and IELTS roadmap insights from Edtika.'
        : 'Cập nhật mới nhất, mẹo học tập và lộ trình IELTS từ Edtika.';

    if (!empty($selectedAuthor)) {
        $blogPageSubtitle = $isEnglish
            ? 'Posts and learning insights from this author.'
            : 'Các bài viết và chia sẻ học tập từ tác giả này.';
    } elseif (!empty($selectedCategory)) {
        $blogPageSubtitle = $isEnglish
            ? 'Curated posts in this topic.'
            : 'Tổng hợp bài viết theo chuyên mục bạn quan tâm.';
    }
@endphp

@extends("design_1.web.layouts.app")

@push("styles_top")
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
            overflow: hidden;
        }

        .edtika-news-page__container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1312px;
            padding: 0 28px;
            margin: 0 auto;
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

        .edtika-news-page__nav-link.is-active {
            font-weight: 900;
            opacity: 1;
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

        .edtika-news-page__hero-card {
            width: 100%;
            margin: 20px auto 0;
            border-radius: 38px;
            border: 1px solid var(--edtika-glass-border);
            background: var(--edtika-glass);
            backdrop-filter: blur(18px) saturate(140%);
            -webkit-backdrop-filter: blur(18px) saturate(140%);
            box-shadow: var(--edtika-shadow);
            padding: 32px 34px;
            position: relative;
            z-index: 4;
            overflow: hidden;
        }

        .edtika-news-page__hero-card::before {
            content: '';
            position: absolute;
            right: -62px;
            bottom: -62px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(81, 29, 153, 0.22) 0%, rgba(81, 29, 153, 0) 70%);
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
            font-size: clamp(40px, 5vw, 58px);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -0.02em;
            text-align: center;
        }

        .edtika-news-page__subtitle {
            margin: 16px auto 0;
            max-width: 740px;
            text-align: center;
            color: rgba(20, 20, 28, 0.74);
            font-size: 16px;
            line-height: 1.5;
            font-weight: 600;
        }

        .edtika-news-page__section {
            padding: 34px 0 0;
        }

        .edtika-news-page__category-section {
            margin-bottom: 34px;
        }

        .edtika-news-page__category-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 14px;
        }

        .edtika-news-page__category-title {
            margin: 0;
            font-size: clamp(28px, 3vw, 40px);
            line-height: 1.12;
            font-weight: 900;
            color: #15151d;
            letter-spacing: -0.01em;
        }

        .edtika-news-page__category-link {
            font-size: 15px;
            font-weight: 700;
            color: #511d99;
            text-decoration: none;
            white-space: nowrap;
        }

        .edtika-news-page__grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .edtika-news-card {
            border-radius: 26px;
            border: 1px solid var(--edtika-glass-border);
            background: var(--edtika-glass);
            backdrop-filter: blur(14px) saturate(130%);
            -webkit-backdrop-filter: blur(14px) saturate(130%);
            box-shadow: 0 16px 32px rgba(52, 42, 84, 0.14);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        .edtika-news-card__thumb {
            width: 100%;
            height: 190px;
            object-fit: cover;
            background: #d6d4df;
        }

        .edtika-news-card__body {
            padding: 16px 16px 14px;
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
        }

        .edtika-news-card__title {
            margin: 0;
            font-size: 20px;
            line-height: 1.3;
            font-weight: 800;
            color: #13131a;
        }

        .edtika-news-card__title a {
            color: inherit;
            text-decoration: none;
        }

        .edtika-news-card__meta {
            margin-top: auto;
            padding-top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-top: 1px solid rgba(17, 17, 24, 0.1);
        }

        .edtika-news-card__author {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            color: #1b1b24;
            font-size: 13px;
            font-weight: 700;
        }

        .edtika-news-card__author img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.75);
        }

        .edtika-news-card__meta-icons {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: rgba(28, 28, 38, 0.78);
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .edtika-news-card__meta-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .edtika-news-card__meta-item img {
            width: 14px;
            height: 14px;
            object-fit: contain;
        }

        .edtika-news-page__pagination {
            margin-top: 28px;
            padding: 14px 16px;
            border-radius: 20px;
            border: 1px solid var(--edtika-glass-border);
            background: var(--edtika-glass);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 14px 28px rgba(52, 42, 84, 0.13);
        }

        .edtika-news-page__empty {
            grid-column: 1 / -1;
            border-radius: 24px;
            border: 1px solid var(--edtika-glass-border);
            background: var(--edtika-glass);
            backdrop-filter: blur(14px) saturate(130%);
            -webkit-backdrop-filter: blur(14px) saturate(130%);
            box-shadow: 0 16px 32px rgba(52, 42, 84, 0.14);
            padding: 34px 20px;
            text-align: center;
            color: rgba(19, 19, 26, 0.78);
            font-size: 17px;
            font-weight: 700;
            line-height: 1.45;
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

        .edtika-footer__list a,
        .edtika-footer__text {
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

            .edtika-news-page__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .edtika-news-page__hero-card {
                padding: 24px 20px;
            }

            .edtika-news-page__hero-icon {
                width: 128px;
                right: 10px;
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
                font-size: 40px;
            }

            .edtika-news-page__hero-card {
                margin-top: 14px;
                border-radius: 26px;
                padding: 18px 14px;
            }

            .edtika-news-page__hero-icon {
                width: 94px;
                top: -8px;
                right: 8px;
            }

            .edtika-news-page__headline {
                font-size: 34px;
            }

            .edtika-news-page__subtitle {
                font-size: 14px;
                margin-top: 10px;
            }

            .edtika-news-page__grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .edtika-news-card__thumb {
                height: 178px;
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
                    <div class="edtika-news-page__lang {{ $nextLocale === 'vi' ? 'is-alt' : '' }}" aria-label="{{ $isEnglish ? 'Language switch' : 'Chuyển ngôn ngữ' }}">
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

            <section class="edtika-news-page__hero-card" aria-label="{{ $isEnglish ? 'News summary' : 'Tổng quan tin tức' }}">
                <img src="{{ asset('store/icons/Document.png') }}" alt="Document icon" class="edtika-news-page__hero-icon">

                <div class="edtika-news-page__breadcrumb">{{ getPlatformName() }} &gt; {{ $isEnglish ? 'News' : 'Tin tức' }}</div>
                <h1 class="edtika-news-page__headline">{{ $blogPageTitle }}</h1>
                <p class="edtika-news-page__subtitle">{{ $blogPageSubtitle }}</p>
            </section>

            <section class="edtika-news-page__section">
                @if(empty($selectedCategory) and empty($selectedAuthor) and !empty($categorySections) and count($categorySections) > 0)
                    @foreach($categorySections as $categorySection)
                        <div class="edtika-news-page__category-section">
                            <div class="edtika-news-page__category-header">
                                <h2 class="edtika-news-page__category-title">{{ $categorySection['category']->title }}</h2>
                                <a href="{{ $categorySection['category']->getUrl() }}" class="edtika-news-page__category-link">{{ $isEnglish ? 'View all' : 'Xem tất cả' }}</a>
                            </div>

                            <div class="edtika-news-page__grid">
                                @foreach($categorySection['posts'] as $post)
                                    <article class="edtika-news-card">
                                        <a href="{{ $post->getUrl() }}">
                                            <img src="{{ $post->image }}" class="edtika-news-card__thumb" alt="{{ $post->title }}">
                                        </a>

                                        <div class="edtika-news-card__body">
                                            <h3 class="edtika-news-card__title">
                                                <a href="{{ $post->getUrl() }}">{{ $post->title }}</a>
                                            </h3>

                                            <p class="mt-10 mb-0 text-gray-500 font-14" style="line-height: 1.55;">{{ \Illuminate\Support\Str::limit(strip_tags($post->description), 150) }}</p>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="edtika-news-page__grid">
                        @forelse($posts as $post)
                            <article class="edtika-news-card">
                                <a href="{{ $post->getUrl() }}">
                                    <img src="{{ $post->image }}" class="edtika-news-card__thumb" alt="{{ $post->title }}">
                                </a>

                                <div class="edtika-news-card__body">
                                    <h3 class="edtika-news-card__title">
                                        <a href="{{ $post->getUrl() }}">{{ $post->title }}</a>
                                    </h3>

                                    <div class="edtika-news-card__meta">
                                        <span class="edtika-news-card__author">
                                            <img src="{{ !empty($post->author) ? $post->author->getAvatar(32) : asset('store/icons/users.png') }}" alt="{{ !empty($post->author) ? $post->author->full_name : 'Author' }}">
                                            <span>{{ !empty($post->author) ? $post->author->full_name : ($isEnglish ? 'Author' : 'Tác giả') }}</span>
                                        </span>

                                        <span class="edtika-news-card__meta-icons">
                                            <span class="edtika-news-card__meta-item">
                                                <img src="{{ asset('store/icons/calendar.png') }}" alt="Calendar icon">
                                                <span>{{ dateTimeFormat($post->created_at, 'j M Y') }}</span>
                                            </span>

                                            <span class="edtika-news-card__meta-item">
                                                <img src="{{ asset('store/icons/chat.png') }}" alt="Comment icon">
                                                <span>{{ $post->comments_count }}</span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="edtika-news-page__empty">
                                {{ $isEnglish ? 'No news articles are available at the moment.' : 'Hiện tại chưa có bài viết nào.' }}
                            </div>
                        @endforelse
                    </div>

                    @if(!empty($pagination) && count($posts) > 0)
                        <div class="edtika-news-page__pagination">
                            {!! $pagination !!}
                        </div>
                    @endif
                @endif
            </section>
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
