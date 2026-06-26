@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $isEnglish = mb_strtolower(app()->getLocale()) === 'en';
    $nextLocale = $isEnglish ? 'vi' : 'en';
    $nextLocaleLabel = $nextLocale === 'en' ? 'ENG' : 'VIE';
    $langSwitchClass = $nextLocale === 'en' ? 'is-next-eng' : 'is-next-vie';
    $isHomeActive = request()->path() === '/';
    $isClassesActive = request()->is('classes') || request()->is('classes/*');
    $isPlacementActive = request()->is('panel/ielts-tests/diagnostic') || request()->is('panel/ielts-tests/diagnostic/*');
    $isMockActive = request()->is('panel/ielts-tests/mock') || request()->is('panel/ielts-tests/mock/*');
    $isDictionaryActive = request()->is('dictionary') || request()->is('dictionary/*') || request()->is('panel/dictionary') || request()->is('panel/dictionary/*');
    $isNewsActive = request()->is('blog') || request()->is('blog/*');
    $isGuestDictionary = !auth()->check();
    $publishedBundleVocabularySets = $publishedBundleVocabularySets ?? collect();
@endphp

@push('styles_top')
<style>
    :root {
        --edtika-primary: #511D99;
        --edtika-text: #101014;
        --edtika-page-bg-1: #ecebf3;
        --edtika-page-bg-2: #eaf4f1;
        --edtika-blob-start: rgba(134, 34, 255, 0.34);
        --edtika-blob-end: rgba(60, 225, 97, 0.34);
    }

    .edtika-homepage {
        min-height: 100vh;
        background: linear-gradient(180deg, var(--edtika-page-bg-1) 0%, #edf0f6 62%, var(--edtika-page-bg-2) 100%);
        overflow: hidden;
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
    }

    .edtika-homepage__container {
        width: 100%;
        max-width: 1312px;
        margin: 0 auto;
        padding: 0 28px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
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

    .edtika-login-btn {
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

    .public-dictionary-page {
        padding: 34px 0 56px;
        flex: 1 0 auto;
    }

    .dictionary-container {
        max-width: 980px;
        margin: 0 auto;
    }

    .edtika-footer {
        position: relative;
        isolation: isolate;
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
        opacity: 0.62;
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
        padding: 56px 28px 30px;
        max-width: 1312px;
        margin: 0 auto;
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
        padding: 24px 28px 22px;
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 16px;
        position: relative;
        max-width: 1312px;
        margin: 0 auto;
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

    .search-section {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 26px;
        margin-bottom: 24px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }

    .search-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 14px;
    }

    .search-hint {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 14px;
    }

    .search-wrapper {
        display: flex;
        gap: 10px;
    }

    .search-input {
        flex: 1;
        padding: 12px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: #511D99;
    }

    .search-btn {
        padding: 12px 28px;
        background: #511D99;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .search-btn:hover {
        background: #421670;
    }

    .search-btn:disabled {
        background: #7c5cad;
        cursor: not-allowed;
    }

    .word-list-store-section {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 22px;
        margin-bottom: 24px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }

    .word-list-store-header {
        margin-bottom: 14px;
    }

    .word-list-store-title {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
    }

    .word-list-store-subtitle {
        margin: 8px 0 0;
        font-size: 14px;
        color: #64748b;
    }

    .word-list-store-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .word-list-package-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 14px;
        background: linear-gradient(180deg, #ffffff 0%, #faf7ff 100%);
    }

    .word-list-package-name {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .word-list-package-meta {
        margin-top: 8px;
        font-size: 13px;
        color: #6b7280;
    }

    .word-list-package-prices {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .word-list-package-price-sale {
        font-size: 20px;
        font-weight: 800;
        color: #511D99;
    }

    .word-list-package-price-original {
        font-size: 13px;
        color: #6b7280;
        text-decoration: line-through;
    }

    .word-list-package-discount {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 24px;
        padding: 0 8px;
        border-radius: 999px;
        background: rgba(16, 185, 129, 0.12);
        color: #047857;
        font-size: 12px;
        font-weight: 700;
    }

    .word-list-package-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 12px;
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        background: #511D99;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .word-list-package-btn:hover,
    .word-list-package-btn:focus {
        color: #fff;
        text-decoration: none;
        background: #421670;
    }

    .word-list-store-empty {
        border: 1px dashed #dbe3ee;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        color: #64748b;
        font-size: 14px;
    }

    .wordlist-preview-modal {
        position: fixed;
        inset: 0;
        display: none;
        z-index: 1300;
    }

    .wordlist-preview-modal.is-open {
        display: block;
    }

    .wordlist-preview-modal__backdrop {
        position: absolute;
        inset: 0;
        background: rgba(2, 6, 23, 0.5);
    }

    .wordlist-preview-modal__dialog {
        position: relative;
        width: min(680px, calc(100vw - 24px));
        margin: 48px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.3);
        overflow: hidden;
        max-height: calc(100vh - 96px);
        display: flex;
        flex-direction: column;
    }

    .wordlist-preview-modal__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
        padding: 16px 18px;
        border-bottom: 1px solid #eceef4;
    }

    .wordlist-preview-modal__title {
        margin: 0;
        font-size: 20px;
        line-height: 1.2;
        font-weight: 800;
        color: #0f172a;
    }

    .wordlist-preview-modal__subtitle {
        margin: 6px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .wordlist-preview-modal__close {
        border: none;
        background: transparent;
        font-size: 28px;
        line-height: 1;
        color: #475569;
        cursor: pointer;
        padding: 0;
    }

    .wordlist-preview-modal__body {
        padding: 18px;
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
    }

    .wordlist-preview-state {
        min-height: 22px;
        color: #64748b;
        font-size: 14px;
    }

    .wordlist-preview-progress {
        margin-bottom: 12px;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
    }

    .wordlist-preview-content-wrap {
        display: grid;
        gap: 12px;
    }

    .wordlist-preview-block {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        background: #f8fafc;
    }

    .wordlist-preview-block--feature {
        background: #f7f2ff;
    }

    .wordlist-preview-block__label {
        margin: 0 0 8px;
        color: #334155;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .wordlist-preview-block__content {
        margin: 0;
        color: #0f172a;
        font-size: 15px;
        line-height: 1.65;
        white-space: pre-line;
    }

    .wordlist-preview-block__content.is-empty {
        color: #64748b;
        font-style: italic;
    }

    .dictionary-result-container {
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 18px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    }

    .dictionary-placeholder {
        min-height: 90px;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-size: 14px;
        border: 1px dashed #dbe3ee;
        border-radius: 14px;
        padding: 12px;
    }

    .hidden {
        display: none !important;
    }

    .result-word {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .result-pos {
        display: inline-block;
        background: rgba(81, 29, 153, 0.08);
        color: #511D99;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .result-definition {
        font-size: 16px;
        color: #475569;
        margin: 10px 0;
        line-height: 1.6;
    }

    .result-example {
        font-size: 15px;
        color: #64748b;
        font-style: italic;
        margin: 8px 0;
        padding-left: 15px;
        border-left: 3px solid #e2e8f0;
    }

    .pronunciations-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
        align-items: center;
    }

    .pronunciation-item {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        border: 1px solid rgba(81, 29, 153, 0.10);
        border-radius: 12px;
        padding: 6px 12px;
    }

    .pron-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #511D99;
        color: #fff;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .pron-ipa {
        font-size: 16px;
        color: #511D99;
        font-style: italic;
    }

    .pron-audio-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #511D99;
        font-size: 20px;
        display: flex;
        align-items: center;
        padding: 2px 4px;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .pron-audio-btn:hover {
        background: rgba(81, 29, 153, 0.08);
    }

    .result-meaning-group {
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .result-meaning-group:last-child {
        border-bottom: none;
    }

    .def-entry {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .def-entry:last-of-type {
        border-bottom: none;
    }

    .def-content {
        flex: 1;
        min-width: 0;
    }

    .btn-save-def {
        flex-shrink: 0;
        align-self: flex-start;
        padding: 6px 18px;
        background: #374151;
        color: #fff;
        border: none;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
        margin-top: 2px;
    }

    .btn-save-def:hover {
        background: #1f2937;
    }

    .btn-save-def.is-locked {
        background: #64748b;
    }

    .btn-save-def:disabled {
        cursor: default;
    }

    .def-num {
        font-weight: 700;
        color: #511D99;
        margin-right: 4px;
    }

    .result-synonyms,
    .result-antonyms {
        font-size: 13px;
        color: #64748b;
        margin-top: 6px;
    }

    .result-synonyms strong,
    .result-antonyms strong {
        color: #475569;
    }

    .dict-result-back-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin: 24px auto 4px;
        padding: 10px 32px;
        border: 2px solid #cbd5e1;
        border-radius: 50px;
        background: transparent;
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: border-color 0.2s, color 0.2s, background 0.2s;
    }

    .dict-result-back-btn:hover {
        border-color: #94a3b8;
        background: #f1f5f9;
        color: #1e293b;
    }

    @media (max-width: 991px) {
        .edtika-homepage__container {
            padding: 0 16px;
        }

        .edtika-homepage__header {
            height: auto;
            flex-wrap: wrap;
            padding: 22px 0 8px;
        }

        .edtika-homepage__brand {
            font-size: 40px;
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
            font-size: 16px;
        }

        .edtika-homepage__actions {
            margin-left: auto;
        }

        .public-dictionary-page {
            padding: 22px 0 40px;
        }

        .search-section,
        .dictionary-result-container {
            padding: 16px;
        }

        .search-wrapper {
            flex-direction: column;
        }

        .word-list-store-grid {
            grid-template-columns: 1fr;
        }

        .wordlist-preview-modal__dialog {
            margin: 14px auto;
            width: calc(100vw - 16px);
            max-height: calc(100vh - 28px);
        }

        .wordlist-flashcard__word {
            font-size: 28px;
        }

        .search-btn {
            width: 100%;
        }

        .def-entry {
            flex-direction: column;
        }

        .btn-save-def {
            width: 100%;
        }

        .edtika-footer__top {
            grid-template-columns: 1fr;
            gap: 24px;
            padding: 24px 16px 16px;
        }

        .edtika-footer__right {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .edtika-footer__bottom {
            grid-template-columns: 1fr;
            text-align: left;
            gap: 10px;
            padding: 16px;
        }

        .edtika-footer__policies {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')
<div class="edtika-homepage">
    <div class="edtika-homepage__container">
        <header class="edtika-homepage__header" aria-label="{{ $isEnglish ? 'Dictionary Header' : 'Đầu trang từ điển' }}">
            <a href="/" class="text-decoration-none" aria-label="EDTIKA Home">
                <h1 class="edtika-homepage__brand">EDTIKA</h1>
            </a>

            <nav class="edtika-homepage__nav" aria-label="{{ $isEnglish ? 'Main Navigation' : 'Điều hướng chính' }}">
                <a href="/" class="edtika-homepage__nav-link {{ $isHomeActive ? 'is-active' : '' }}">{{ $isEnglish ? 'Home' : 'Trang chủ' }}</a>
                <a href="/classes" class="edtika-homepage__nav-link {{ $isClassesActive ? 'is-active' : '' }}">{{ $isEnglish ? 'Courses' : 'Khóa học' }}</a>
                @if(auth()->check())
                    <a href="/panel/ielts-tests/diagnostic" class="edtika-homepage__nav-link {{ $isPlacementActive ? 'is-active' : '' }}">{{ $isEnglish ? 'Placement Test' : 'Kiểm tra đầu vào' }}</a>
                @else
                    <a href="/panel/ielts-tests/diagnostic" class="edtika-homepage__nav-link {{ $isPlacementActive ? 'is-active' : '' }}" data-open-auth-modal="true">{{ $isEnglish ? 'Placement Test' : 'Kiểm tra đầu vào' }}</a>
                @endif
                @if(auth()->check())
                    <a href="/panel/ielts-tests/mock" class="edtika-homepage__nav-link {{ $isMockActive ? 'is-active' : '' }}">{{ $isEnglish ? 'Mock Tests' : 'Luyện đề' }}</a>
                @else
                    <a href="/panel/ielts-tests/mock" class="edtika-homepage__nav-link {{ $isMockActive ? 'is-active' : '' }}" data-open-auth-modal="true">{{ $isEnglish ? 'Mock Tests' : 'Luyện đề' }}</a>
                @endif
                <a href="/dictionary" class="edtika-homepage__nav-link {{ $isDictionaryActive ? 'is-active' : '' }}">{{ $isEnglish ? 'Dictionary & Flashcards' : 'Từ điển & Flashcard' }}</a>
                <a href="/blog" class="edtika-homepage__nav-link {{ $isNewsActive ? 'is-active' : '' }}">{{ $isEnglish ? 'News' : 'Kiến thức & Tin tức' }}</a>
            </nav>

            <div class="edtika-homepage__actions">
                <div class="edtika-lang-switch {{ $langSwitchClass }}" aria-label="{{ $isEnglish ? 'Language switch' : 'Chuyển ngôn ngữ' }}">
                    <div class="edtika-lang-switch__thumb"></div>
                    <form class="edtika-lang-switch__form" action="/locale" method="post">
                        {{ csrf_field() }}
                        <button class="edtika-lang-switch__button" type="submit" name="locale" value="{{ $nextLocale }}" aria-label="{{ $isEnglish ? 'Switch language' : 'Đổi ngôn ngữ' }}">{{ $nextLocaleLabel }}</button>
                    </form>
                </div>

                @if(auth()->check())
                    <a href="/panel" class="edtika-user-avatar-btn" aria-label="{{ auth()->user()->full_name }}" title="{{ auth()->user()->full_name }}">
                        <img src="{{ auth()->user()->getAvatar(80) }}" alt="{{ auth()->user()->full_name }}" class="edtika-user-avatar-btn__image">
                    </a>
                @else
                    <a href="/login" class="edtika-login-btn" data-open-auth-modal="true">{{ $isEnglish ? 'Log in' : 'Đăng nhập' }}</a>
                @endif
            </div>
        </header>

        <section class="public-dictionary-page">
            <div class="dictionary-container">
                <div class="search-section">
                    <h3 class="search-title">{{ trans('panel.search_english') }}</h3>
                    <p class="search-hint">{{ app()->getLocale() === 'en' ? 'Search words freely. Login is required only when saving to My Word List.' : 'Bạn có thể tra từ tự do. Chỉ cần đăng nhập khi muốn lưu vào My Word List.' }}</p>

                    <div class="search-wrapper">
                        <input type="text" class="search-input" id="guestDictionarySearchInput" placeholder="{{ trans('panel.search_the_word') }}">
                        <button class="search-btn" id="guestDictionarySearchBtn">{{ trans('panel.search') }}</button>
                    </div>
                </div>

                <div class="dictionary-result-container hidden" id="guestDictionaryResult"></div>

                <div class="dictionary-result-container" id="guestDictionaryPlaceholder">
                    <div class="dictionary-placeholder">{{ app()->getLocale() === 'en' ? 'Enter a word to start searching.' : 'Nhập từ vựng để bắt đầu tra cứu.' }}</div>
                </div>

                <div class="word-list-store-section">
                    <div class="word-list-store-header">
                        <h3 class="word-list-store-title">{{ trans('panel.bundle_vocabulary_store_title') }}</h3>
                        <p class="word-list-store-subtitle">{{ trans('panel.bundle_vocabulary_store_subtitle') }}</p>
                    </div>

                    @if($publishedBundleVocabularySets->isNotEmpty())
                        <div class="word-list-store-grid">
                            @foreach($publishedBundleVocabularySets as $set)
                                <article class="word-list-package-card">
                                    <h4 class="word-list-package-name">{{ $set['set_name'] }}</h4>

                                    <div class="word-list-package-meta">
                                        {{ $set['bundle_slug'] ? ('Bundle: ' . $set['bundle_slug']) : ('Bundle #' . $set['id']) }}
                                        · {{ $set['word_count'] }} {{ app()->getLocale() === 'en' ? 'words' : 'từ' }}
                                    </div>

                                    <div class="word-list-package-prices">
                                        <span class="word-list-package-price-sale">{{ $set['sale_price_label'] }}</span>
                                        <span class="word-list-package-price-original">{{ $set['original_price_label'] }}</span>

                                        @if($set['discount_percent'] > 0)
                                            <span class="word-list-package-discount">-{{ $set['discount_percent'] }}%</span>
                                        @endif
                                    </div>

                                    <button class="word-list-package-btn js-open-wordlist-preview" type="button" data-set-id="{{ $set['id'] }}" data-set-name="{{ $set['set_name'] }}">{{ trans('panel.bundle_vocabulary_store_view_bundle') }}</button>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="word-list-store-empty">{{ trans('panel.bundle_vocabulary_store_empty') }}</div>
                    @endif
                </div>
            </div>
        </section>

        <div class="wordlist-preview-modal" id="wordListPreviewModal" aria-hidden="true">
            <div class="wordlist-preview-modal__backdrop js-wordlist-preview-close"></div>
            <div class="wordlist-preview-modal__dialog" role="dialog" aria-modal="true" aria-label="Word List preview">
                <div class="wordlist-preview-modal__header">
                    <div>
                        <h3 class="wordlist-preview-modal__title" id="wordListPreviewTitle">{{ trans('panel.bundle_vocabulary_preview_title') }}</h3>
                        <p class="wordlist-preview-modal__subtitle">{{ trans('panel.bundle_vocabulary_preview_subtitle') }}</p>
                    </div>
                    <button type="button" class="wordlist-preview-modal__close js-wordlist-preview-close" aria-label="Close">&times;</button>
                </div>

                <div class="wordlist-preview-modal__body">
                    <div class="wordlist-preview-state" id="wordListPreviewState"></div>

                    <div class="wordlist-preview-content-wrap hidden" id="wordListPreviewContentWrap">
                        <div class="wordlist-preview-block">
                            <p class="wordlist-preview-block__label">{{ trans('panel.bundle_vocabulary_preview_intro_label') }}</p>
                            <p class="wordlist-preview-block__content" id="wordListPreviewIntro"></p>
                        </div>

                        <div class="wordlist-preview-block wordlist-preview-block--feature">
                            <p class="wordlist-preview-block__label">{{ trans('panel.bundle_vocabulary_preview_feature_label') }}</p>
                            <p class="wordlist-preview-block__content" id="wordListPreviewFeature"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="edtika-footer" aria-label="{{ $isEnglish ? 'Dictionary Footer' : 'Chân trang từ điển' }}">
            <div class="edtika-footer__top">
                <div>
                    <h3 class="edtika-footer__brand">EDTIKA</h3>
                    <p class="edtika-footer__description">{{ $isEnglish ? 'This platform is designed to help institutions, educators, and learners manage, deliver, and track learning activities effectively.' : 'Nền tảng này được thiết kế để giúp các tổ chức, nhà giáo dục và người học quản lý, cung cấp và theo dõi các hoạt động học tập hiệu quả.' }}</p>
                </div>

                <div class="edtika-footer__right">
                    <div>
                        <h4 class="edtika-footer__column-title">{{ $isEnglish ? 'Support' : 'Hỗ trợ' }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                        <ul class="edtika-footer__list">
                            <li><a href="/panel/ielts-tests/diagnostic">{{ $isEnglish ? 'Placement Test' : 'Kiểm tra đầu vào' }}</a></li>
                            <li><a href="/panel/ielts-tests/mock">{{ $isEnglish ? 'Mock Tests' : 'Luyện đề' }}</a></li>
                            <li><a href="/dictionary">{{ $isEnglish ? 'Dictionary & Flashcards' : 'Từ điển & Flashcard' }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="edtika-footer__column-title">{{ $isEnglish ? 'Introduction' : 'Giới thiệu' }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                        <ul class="edtika-footer__list">
                            <li><a href="/classes">{{ $isEnglish ? 'Courses' : 'Khóa học' }}</a></li>
                            <li><a href="/#faq">FAQ</a></li>
                            <li><a href="/blog">{{ $isEnglish ? 'News' : 'Tin tức' }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="edtika-footer__column-title">{{ $isEnglish ? 'Contact Information' : 'Thông tin liên hệ' }}<span class="edtika-footer__title-mark" aria-hidden="true"></span></h4>
                        <div class="edtika-footer__contact-row">
                            <p class="edtika-footer__contact-label">{{ $isEnglish ? 'Hotline' : 'Số điện thoại/Hotline' }}</p>
                            <p class="edtika-footer__contact-value">0987 654 321</p>
                        </div>
                        <div class="edtika-footer__contact-row">
                            <p class="edtika-footer__contact-label">Email</p>
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
                    {{ $isEnglish ? 'Copyright 2026' : 'Bản quyền 2026' }} &copy; <span class="edtika-footer__copyright-brand">Edtika.</span> {{ $isEnglish ? 'All rights reserved.' : 'Mọi quyền được bảo lưu.' }}
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
                    {{ $isEnglish ? 'Terms & Policies' : 'Điều khoản & Chính sách' }}<span class="edtika-footer__dot">•</span>{{ $isEnglish ? 'Privacy Policy' : 'Chính sách bảo mật' }}
                </p>
            </div>
        </footer>
    </div>
</div>
@endsection

@push('scripts_bottom')
<script>
(function ($) {
    'use strict';

    var isGuest = @json($isGuestDictionary);
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var dictionaryResult = $('#guestDictionaryResult');
    var dictionaryPlaceholder = $('#guestDictionaryPlaceholder');
    var wordListPreviewModal = $('#wordListPreviewModal');
    var wordListPreviewState = $('#wordListPreviewState');
    var wordListPreviewTitle = $('#wordListPreviewTitle');
    var wordListPreviewContentWrap = $('#wordListPreviewContentWrap');
    var wordListPreviewIntro = $('#wordListPreviewIntro');
    var wordListPreviewFeature = $('#wordListPreviewFeature');

    function escAttr(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function showMessage(message) {
        dictionaryPlaceholder.removeClass('hidden').find('.dictionary-placeholder').text(message);
        dictionaryResult.addClass('hidden').html('');
    }

    function performSearch() {
        var searchTerm = ($('#guestDictionarySearchInput').val() || '').trim();

        if (!searchTerm) {
            showMessage('{{ app()->getLocale() === 'en' ? 'Please enter a word.' : 'Vui lòng nhập từ cần tra.' }}');
            return;
        }

        $('#guestDictionarySearchBtn').prop('disabled', true).text('{{ trans('panel.searching') }}...');

        $.ajax({
            url: '/dictionary/search-first',
            method: 'POST',
            data: {
                query: searchTerm,
                _token: csrfToken
            },
            success: function (response) {
                if (!response || !response.success || !response.data) {
                    showMessage('{{ trans('panel.word_not_found') }}');
                    return;
                }

                renderSearchResult(response.data);
            },
            error: function (xhr) {
                var msg = '{{ trans('panel.search_error') }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showMessage(msg);
            },
            complete: function () {
                $('#guestDictionarySearchBtn').prop('disabled', false).text('{{ trans('panel.search') }}');
            }
        });
    }

    function renderSearchResult(data) {
        dictionaryPlaceholder.addClass('hidden');

        var wordLabel = data.headword || data.word || '';
        var html = '<div class="result-word">' + escAttr(wordLabel) + '</div>';

        if (Array.isArray(data.pronunciations) && data.pronunciations.length > 0) {
            html += '<div class="pronunciations-row">';
            data.pronunciations.forEach(function (pron) {
                var label = pron.label || '';
                var ipa = pron.ipa || pron.text || '';
                var audio = pron.audio || '';

                if (audio && audio.indexOf('//') === 0) {
                    audio = 'https:' + audio;
                }

                html += '<div class="pronunciation-item">';
                if (label) {
                    html += '<span class="pron-label">' + escAttr(label) + '</span>';
                }
                if (ipa) {
                    html += '<span class="pron-ipa">/' + escAttr(ipa) + '/</span>';
                }
                html += '<button class="pron-audio-btn" type="button" data-audio="' + escAttr(audio) + '" data-word="' + escAttr(wordLabel) + '">';
                html += '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">';
                html += '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>';
                html += '<path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>';
                html += '<path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>';
                html += '</svg>';
                html += '</button>';
                html += '</div>';
            });
            html += '</div>';
        }

        if (Array.isArray(data.meanings) && data.meanings.length > 0) {
            data.meanings.forEach(function (meaning) {
                html += '<div class="result-meaning-group">';

                if (meaning.partOfSpeech) {
                    html += '<div class="result-pos">' + escAttr(meaning.partOfSpeech) + '</div>';
                }

                if (Array.isArray(meaning.definitions) && meaning.definitions.length > 0) {
                    meaning.definitions.slice(0, 4).forEach(function (defObj, idx) {
                        var partOfSpeech = meaning.partOfSpeech || '';
                        var definition = defObj.definition || '';
                        var example = defObj.example || '';

                        html += '<div class="def-entry">';
                        html += '<div class="def-content">';
                        html += '<div class="result-definition"><span class="def-num">' + (idx + 1) + '.</span> ' + escAttr(definition) + '</div>';
                        if (example) {
                            html += '<div class="result-example">&quot;' + escAttr(example) + '&quot;</div>';
                        }
                        if (Array.isArray(defObj.synonyms) && defObj.synonyms.length) {
                            html += '<div class="result-synonyms"><strong>Synonyms:</strong> ' + escAttr(defObj.synonyms.join(', ')) + '</div>';
                        }
                        if (Array.isArray(defObj.antonyms) && defObj.antonyms.length) {
                            html += '<div class="result-antonyms"><strong>Antonyms:</strong> ' + escAttr(defObj.antonyms.join(', ')) + '</div>';
                        }
                        html += '</div>';

                        html += '<button type="button" class="btn-save-def save-definition-btn' + (isGuest ? ' is-locked' : '') + '"';
                        html += ' data-word="' + escAttr(wordLabel) + '"';
                        html += ' data-definition="' + escAttr(definition) + '"';
                        html += ' data-pos="' + escAttr(partOfSpeech) + '"';
                        html += ' data-example="' + escAttr(example) + '">';
                        html += isGuest ? '{{ app()->getLocale() === 'en' ? 'Login to Save' : 'Đăng nhập để lưu' }}' : 'Save';
                        html += '</button>';
                        html += '</div>';
                    });
                }

                if (Array.isArray(meaning.synonyms) && meaning.synonyms.length) {
                    html += '<div class="result-synonyms"><strong>Synonyms:</strong> ' + escAttr(meaning.synonyms.join(', ')) + '</div>';
                }
                if (Array.isArray(meaning.antonyms) && meaning.antonyms.length) {
                    html += '<div class="result-antonyms"><strong>Antonyms:</strong> ' + escAttr(meaning.antonyms.join(', ')) + '</div>';
                }

                html += '</div>';
            });
        }

        html += '<div class="text-center mt-8 mb-4">';
        html += '<button class="dict-result-back-btn" id="dictResultBackBtn" type="button">';
        html += '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
        html += '{{ trans('panel.back') }}';
        html += '</button>';
        html += '</div>';

        dictionaryResult.removeClass('hidden').html(html);
    }

    function playAudio(audioUrl, wordText) {
        var url = audioUrl || '';
        if (url && url.indexOf('//') === 0) {
            url = 'https:' + url;
        }

        if (url) {
            var audio = new Audio(url);
            audio.play().catch(function () {
                speakWord(wordText);
            });
            return;
        }

        speakWord(wordText);
    }

    function closeResult() {
        dictionaryResult.addClass('hidden').html('');
        dictionaryPlaceholder.removeClass('hidden');
    }

    function speakWord(word) {
        if (!word || !window.speechSynthesis) {
            return;
        }

        window.speechSynthesis.cancel();
        var utter = new SpeechSynthesisUtterance(word.trim());
        utter.lang = 'en-US';
        utter.rate = 0.9;
        window.speechSynthesis.speak(utter);
    }

    function openAuthModal() {
        var authModal = document.getElementById('edtikaAuthModal');
        if (!authModal) {
            window.location.href = '/login';
            return;
        }

        authModal.classList.add('is-open');
        authModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function openWordListPreviewModal() {
        wordListPreviewModal.addClass('is-open').attr('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeWordListPreviewModal() {
        wordListPreviewModal.removeClass('is-open').attr('aria-hidden', 'true');
        document.body.style.overflow = '';
        wordListPreviewContentWrap.addClass('hidden');
        wordListPreviewState.text('');
        wordListPreviewIntro.text('').removeClass('is-empty');
        wordListPreviewFeature.text('').removeClass('is-empty');
    }

    function setPreviewLoadingState(message) {
        wordListPreviewState.text(message || '');
        wordListPreviewContentWrap.addClass('hidden');
        wordListPreviewIntro.text('').removeClass('is-empty');
        wordListPreviewFeature.text('').removeClass('is-empty');
    }

    function renderWordListPackageInfo(data) {
        var introContent = (data && data.intro_content ? String(data.intro_content) : '').trim();
        var featureContent = (data && data.feature_content ? String(data.feature_content) : '').trim();

        if (!introContent && !featureContent) {
            setPreviewLoadingState('{{ trans('panel.bundle_vocabulary_preview_empty') }}');
            return;
        }

        if (introContent) {
            wordListPreviewIntro.text(introContent).removeClass('is-empty');
        } else {
            wordListPreviewIntro.text('{{ trans('panel.bundle_vocabulary_preview_no_intro') }}').addClass('is-empty');
        }

        if (featureContent) {
            wordListPreviewFeature.text(featureContent).removeClass('is-empty');
        } else {
            wordListPreviewFeature.text('{{ trans('panel.bundle_vocabulary_preview_no_feature') }}').addClass('is-empty');
        }

        wordListPreviewContentWrap.removeClass('hidden');
        wordListPreviewState.text('');
    }

    function fetchAndOpenWordListPreview(setId, setName) {
        openWordListPreviewModal();
        wordListPreviewTitle.text(setName || '{{ trans('panel.bundle_vocabulary_preview_title') }}');
        setPreviewLoadingState('{{ trans('panel.loading') }}...');

        $.ajax({
            url: '/dictionary/word-list-packages/' + setId + '/preview',
            method: 'GET',
            success: function (res) {
                if (!res || !res.success || !res.data) {
                    setPreviewLoadingState('{{ trans('panel.bundle_vocabulary_preview_open_error') }}');
                    return;
                }

                renderWordListPackageInfo(res.data);
            },
            error: function () {
                setPreviewLoadingState('{{ trans('panel.bundle_vocabulary_preview_open_error') }}');
            }
        });
    }

    $('#guestDictionarySearchBtn').on('click', performSearch);

    $('#guestDictionarySearchInput').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            performSearch();
        }
    });

    $(document).on('click', '.pron-audio-btn', function () {
        playAudio($(this).data('audio') || '', $(this).data('word') || '');
    });

    $(document).on('click', '.save-definition-btn', function () {
        var $btn = $(this);

        if (isGuest) {
            openAuthModal();
            return;
        }

        var word = $btn.data('word') || '';
        var definition = $btn.data('definition') || '';
        var partOfSpeech = $btn.data('pos') || '';
        var example = $btn.data('example') || '';

        if (!word || !definition) {
            return;
        }

        $btn.prop('disabled', true).text('{{ trans('panel.adding') }}...');

        $.ajax({
            url: '/panel/dictionary/my-word-list/add-word',
            method: 'POST',
            data: {
                word: word,
                definition: definition,
                part_of_speech: partOfSpeech,
                example: example,
                pronunciation: '',
                _token: csrfToken
            },
            success: function (res) {
                if (res && res.success) {
                    $btn.text('{{ trans('panel.added') }}').prop('disabled', true);
                    return;
                }

                var msg = res && res.message ? res.message : '{{ trans('panel.failed_to_add_word') }}';
                alert(msg);
                $btn.prop('disabled', false).text('Save');
            },
            error: function (xhr) {
                if (xhr.status === 401 || xhr.status === 419) {
                    openAuthModal();
                    $btn.prop('disabled', false).text('Save');
                    return;
                }

                var msg = '{{ trans('panel.failed_to_add_word') }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
                $btn.prop('disabled', false).text('Save');
            }
        });
    });

    $(document).on('click', '#dictResultBackBtn', closeResult);

    $(document).on('click', '.js-open-wordlist-preview', function () {
        var setId = $(this).data('set-id');
        var setName = $(this).data('set-name') || '{{ trans('panel.bundle_vocabulary_preview_title') }}';

        if (!setId) {
            return;
        }

        fetchAndOpenWordListPreview(setId, setName);
    });

    $(document).on('click', '.js-wordlist-preview-close', closeWordListPreviewModal);

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && wordListPreviewModal.hasClass('is-open')) {
            closeWordListPreviewModal();
        }
    });
})(jQuery);
</script>
@endpush
