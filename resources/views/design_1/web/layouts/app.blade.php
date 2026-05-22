<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
@endphp

<head>
    @include('design_1.web.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">

    @if($isRtl)
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
    @endif

    @if(!empty($themeHeaderData['component_name']))
        <link rel="stylesheet" href="{{ getDesign1StylePath("theme/headers/{$themeHeaderData['component_name']}") }}">
    @endif

    @if(!empty($themeFooterData['component_name']))
        <link rel="stylesheet" href="{{ getDesign1StylePath("theme/footers/{$themeFooterData['component_name']}") }}">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings() !!}

        :root {
            --main-font-family: 'Roboto', sans-serif !important;
        }
        body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, textarea, select, .btn, div {
            font-family: 'Roboto', sans-serif !important;
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
            color: #15151d;
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
            background: linear-gradient(145deg, rgba(73, 25, 138, 0.86), rgba(126, 83, 198, 0.84));
        }

        .edtika-auth-slider__image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
        }

        .edtika-auth-slider__pagination {
            position: absolute;
            left: 50%;
            bottom: 22px;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
        }

        .edtika-auth-slider__pagination span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.44);
        }

        .edtika-auth-slider__pagination span.is-active {
            background: #fff;
        }

        @media (max-width: 992px) {
            .edtika-auth-modal__content {
                grid-template-columns: 1fr;
            }

            .edtika-auth-modal__slider-side {
                display: none;
            }

            .edtika-auth-modal__form-side {
                padding: 56px 22px 34px;
            }
        }
    </style>

    <link rel="stylesheet" href="/assets/design_1/css/overrides.css">

</head>

<body class="bg-gray {{ $isRtl ? 'rtl' : '' }} {{ "{$userThemeColorMode}-mode" }}">

<div id="app">

    @if(!empty($floatingBar) and $floatingBar->position == 'top')
        @include('design_1.web.includes.floating_bar')
    @endif

    @if(!isset($appHeader) and !empty($themeHeaderData['component_name']))
        @include("design_1.web.theme.headers.{$themeHeaderData['component_name']}.index")
    @endif

    {{-- Content --}}
    @yield('content')

    <div class="edtika-auth-modal" id="edtikaAuthModal" aria-hidden="true">
        <div class="edtika-auth-modal__dialog" role="dialog" aria-modal="true" aria-label="Auth dialog">
            <button type="button" class="edtika-auth-modal__close" id="edtikaAuthModalClose" aria-label="Close">&times;</button>

            <div class="edtika-auth-modal__content">
                <div class="edtika-auth-modal__form-side">
                    <div class="edtika-auth-modal__tabs" role="tablist" aria-label="Auth tabs">
                        <button type="button" class="edtika-auth-modal__tab is-active" data-auth-tab="login">{{ trans('auth.login') }}</button>
                        <button type="button" class="edtika-auth-modal__tab" data-auth-tab="register">{{ trans('auth.register') }}</button>
                    </div>

                    <div class="edtika-auth-pane is-active" data-auth-pane="login">
                        <h3 class="edtika-auth-pane__title">{{ trans('auth.login') }}</h3>

                        <div class="edtika-auth-methods" role="tablist" aria-label="Login methods">
                            <button type="button" class="edtika-auth-method is-active" data-login-method="email">Email</button>
                            <button type="button" class="edtika-auth-method" data-login-method="phone">Phone</button>
                        </div>

                        <form method="POST" action="/login">
                            @csrf
                            <input type="hidden" name="type" id="edtikaLoginType" value="email">

                            <div class="edtika-auth-field" data-login-field="email">
                                <label class="edtika-auth-label" for="edtikaLoginEmail">Email *</label>
                                <input id="edtikaLoginEmail" class="edtika-auth-input" type="email" name="email" autocomplete="email">
                            </div>

                            <div class="edtika-auth-field" data-login-field="phone" style="display: none;">
                                <label class="edtika-auth-label" for="edtikaLoginPhone">Phone *</label>
                                <input id="edtikaLoginPhone" class="edtika-auth-input" type="text" name="mobile" autocomplete="tel">
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaLoginPassword">{{ trans('auth.password') }} *</label>
                                <div class="edtika-auth-input-wrap">
                                    <input id="edtikaLoginPassword" class="edtika-auth-input" type="password" name="password" autocomplete="current-password">
                                    <span class="edtika-auth-input-icon">◌</span>
                                </div>
                            </div>

                            <a class="edtika-auth-forgot" href="/forget-password">{{ trans('auth.forgot_password') }}</a>
                            <button type="submit" class="edtika-auth-submit">{{ trans('auth.login') }}</button>
                        </form>

                        <div class="edtika-auth-switch-note">
                            {{ trans('auth.no_account') ?? 'Don\'t have an account?' }} <button type="button" data-auth-tab-switch="register">{{ trans('auth.register') }}</button>
                        </div>
                    </div>

                    <div class="edtika-auth-pane" data-auth-pane="register">
                        <h3 class="edtika-auth-pane__title">{{ trans('auth.register') }}</h3>

                        <form method="POST" action="/register">
                            @csrf

                            @php
                                $registerIsEnglish = app()->getLocale() === 'en';
                            @endphp

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label">{{ $registerIsEnglish ? 'Choose role' : 'Chọn vai trò' }}</label>

                                <div class="edtika-auth-role-switch">
                                    <label class="edtika-auth-role-option">
                                        <input type="radio" name="account_type" value="user" checked>
                                        <span>{{ $registerIsEnglish ? 'Student' : 'Học viên' }}</span>
                                    </label>

                                    <label class="edtika-auth-role-option">
                                        <input type="radio" name="account_type" value="teacher">
                                        <span>{{ $registerIsEnglish ? 'Teacher' : 'Giảng viên' }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaRegisterEmail">Email *</label>
                                <input id="edtikaRegisterEmail" class="edtika-auth-input" type="email" name="email" autocomplete="email">
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaRegisterPhone">{{ $registerIsEnglish ? 'Phone (Optional)' : 'Điện thoại (Tùy chọn)' }}</label>
                                <input id="edtikaRegisterPhone" class="edtika-auth-input" type="text" name="mobile" autocomplete="tel">
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaRegisterFullName">Full name *</label>
                                <input id="edtikaRegisterFullName" class="edtika-auth-input" type="text" name="full_name" autocomplete="name">
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaRegisterPassword">{{ trans('auth.password') }} *</label>
                                <div class="edtika-auth-input-wrap">
                                    <input id="edtikaRegisterPassword" class="edtika-auth-input" type="password" name="password" autocomplete="new-password">
                                    <span class="edtika-auth-input-icon">◌</span>
                                </div>
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaRegisterPasswordConfirmation">Confirm password *</label>
                                <div class="edtika-auth-input-wrap">
                                    <input id="edtikaRegisterPasswordConfirmation" class="edtika-auth-input" type="password" name="password_confirmation" autocomplete="new-password">
                                    <span class="edtika-auth-input-icon">◌</span>
                                </div>
                            </div>

                            <label class="edtika-auth-check">
                                <input type="checkbox" name="term" value="1" required>
                                <span class="edtika-auth-check__box">✓</span>
                                <span class="edtika-auth-check__text">
                                    {{ $registerIsEnglish ? 'I agree to the' : 'Tôi đồng ý với' }} <strong>{{ $registerIsEnglish ? 'terms & rules' : 'điều khoản & quy tắc' }}</strong>
                                </span>
                            </label>

                            <button type="submit" class="edtika-auth-submit">{{ trans('auth.register') }}</button>
                        </form>

                        <div class="edtika-auth-switch-note">
                            {{ trans('auth.has_account') ?? 'Already have an account?' }} <button type="button" data-auth-tab-switch="login">{{ trans('auth.login') }}</button>
                        </div>
                    </div>
                </div>

                <div class="edtika-auth-modal__slider-side">
                    <div class="edtika-auth-slider">
                        <img class="edtika-auth-slider__image" src="{{ asset('store/icons/—Pngtree—abstract purple line wave background_5542852 1.png') }}" alt="Auth slider image">

                        <div class="edtika-auth-slider__pagination" aria-hidden="true">
                            <span class="is-active"></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!isset($appFooter) and !empty($themeFooterData['component_name']))
        @include("design_1.web.theme.footers.{$themeFooterData['component_name']}.index")
    @endif

    @include('design_1.web.includes.advertise_modal.index')

    @if(!empty($floatingBar) and $floatingBar->position == 'bottom')
        @include('design_1.web.includes.floating_bar')
    @endif

    {{-- Cart Drawer --}}
    @include('design_1.web.cart.drawer.index')

</div>

<!-- Template JS File -->
<script>
    var siteDomain = '{{ url('') }}';
    var deleteAlertTitle = '{{ trans('public.are_you_sure') }}';
    var deleteAlertHint = '{{ trans('public.deleteAlertHint') }}';
    var deleteAlertConfirm = '{{ trans('public.deleteAlertConfirm') }}';
    var deleteAlertCancel = '{{ trans('public.cancel') }}';
    var deleteAlertSuccess = '{{ trans('public.success') }}';
    var deleteAlertFail = '{{ trans('public.fail') }}';
    var deleteAlertFailHint = '{{ trans('public.deleteAlertFailHint') }}';
    var deleteAlertSuccessHint = '{{ trans('public.deleteAlertSuccessHint') }}';
    var forbiddenRequestToastTitleLang = '{{ trans('public.forbidden_request_toast_lang') }}';
    var forbiddenRequestToastMsgLang = '{{ trans('public.forbidden_request_toast_msg_lang') }}';
    var priceInvalidHintLang = '{{ trans('update.price_invalid_hint') }}';
    var clearLang = '{{ trans('clear') }}';
    var requestSuccessLang = '{{ trans('public.request_success') }}';
    var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    var requestFailedLang = '{{ trans('public.request_failed') }}';
    var oopsLang = '{{ trans('update.oops') }}';
    var somethingWentWrongLang = '{{ trans('update.something_went_wrong') }}';
    var loadingDataPleaseWaitLang = '{{ trans('update.loading_data,_please_wait') }}';
    var deleteRequestLang = '{{ trans('update.delete_request') }}';
    var deleteRequestTitleLang = '{{ trans('update.delete_request_title') }}';
    var deleteRequestDescriptionLang = '{{ trans('update.delete_request_description') }}';
    var requestDetailsLang = '{{ trans('update.request_details') }}';
    var sendRequestLang = '{{ trans('update.send_request') }}';
    var closeLang = '{{ trans('public.close') }}';
    var generatedContentLang = '{{ trans('update.generated_content') }}';
    var copyLang = '{{ trans('public.copy') }}';
    var doneLang = '{{ trans('public.done') }}';
    var jsCurrentCurrency = '{{ $currency }}';
    var defaultLocale = '{{ getUserLocale() }}';
    var appLocale = '{{ app()->getLocale() }}';
    var dangerCloseIcon = `<x-iconsax-lin-add class="icons text-danger" width="24" height="24"/>`;
    var directSendIcon = `<x-iconsax-lin-direct-send class="icons text-primary" width="24" height="24"/>`;
    var closeIcon = `<x-iconsax-lin-add class="close-icon" width="25px" height="25px"/>`;
    var bulDangerIcon = `<x-iconsax-bul-danger class="icons text-white" width="32px" height="32px"/>`;
    var defaultAvatarPath = "{{ getDefaultAvatarPath() }}";
    var themeColorsMode = @json(getThemeColorsMode());
</script>


<script type="text/javascript" src="/assets/design_1/js/app.min.js"></script>
<script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
<script defer src="/assets/design_1/js/parts/content_delete.min.js"></script>

<script>
    (function () {
        'use strict';

        var authModal = document.getElementById('edtikaAuthModal');
        var authModalCloseBtn = document.getElementById('edtikaAuthModalClose');
        var authModalOpenBtns = document.querySelectorAll('[data-open-auth-modal="true"]');
        var authTabs = document.querySelectorAll('[data-auth-tab]');
        var authPanes = document.querySelectorAll('[data-auth-pane]');
        var authTabSwitchBtns = document.querySelectorAll('[data-auth-tab-switch]');
        var loginMethodBtns = document.querySelectorAll('[data-login-method]');
        var loginFieldBlocks = document.querySelectorAll('[data-login-field]');
        var loginTypeInput = document.getElementById('edtikaLoginType');

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
        };

        var closeAuthModal = function () {
            if (!authModal) {
                return;
            }

            authModal.classList.remove('is-open');
            authModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
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
                loginTypeInput.value = method;
            }
        };

        loginMethodBtns.forEach(function (methodBtn) {
            methodBtn.addEventListener('click', function () {
                setLoginMethod(methodBtn.getAttribute('data-login-method'));
            });
        });

        setLoginMethod('email');
    })();
</script>

@if(empty($justMobileApp) and checkShowCookieSecurityDialog() and empty($dontShowCookieSecurity))
    @include('design_1.web.includes.cookie_security.cookie-security')
@endif

@if(session()->has('toast'))
    <script>
        (function () {
            "use strict";

            showToast('{{ session()->get('toast')['status'] }}', '{{ session()->get('toast')['title'] ?? '' }}', '{{ session()->get('toast')['msg'] ?? '' }}')
        })(jQuery)
    </script>
@endif

@include('design_1.web.includes.purchase_notifications')


@stack('styles_bottom')
@stack('scripts_bottom')

@stack('scripts_bottom2')

<script>

    @if(session()->has('registration_package_limited'))
    (function () {
        "use strict";

        handleFireSwalModal('{!! session()->get('registration_package_limited') !!}', 32)
    })(jQuery)

    {{ session()->forget('registration_package_limited') }}
    @endif

    {!! !empty($themeCustomCssAndJs['js']) ? $themeCustomCssAndJs['js'] : '' !!}

    (function ($) {
        if ($.fn.select2) {
            $.fn.select2.defaults.set('language', {
                errorLoading: function () {
                    return '{{ trans('update.select2_error_loading') }}';
                },
                inputTooLong: function (args) {
                    var overChars = args.input.length - args.maximum;
                    return '{{ trans('update.select2_input_too_long') }}'.replace(':count', overChars);
                },
                inputTooShort: function (args) {
                    var remaining = args.minimum - args.input.length;
                    return '{{ trans('update.select2_input_too_short') }}'.replace(':count', remaining);
                },
                loadingMore: function () {
                    return '{{ trans('update.select2_loading_more') }}';
                },
                maximumSelected: function (args) {
                    return '{{ trans('update.select2_maximum_selected') }}'.replace(':count', args.maximum);
                },
                noResults: function () {
                    return '{{ trans('update.select2_no_results') }}';
                },
                searching: function () {
                    return '{{ trans('update.select2_searching') }}';
                },
                removeAllItems: function () {
                    return '{{ trans('update.select2_remove_all_items') }}';
                }
            });
        }
    })(jQuery);
</script>

<script src="/assets/design_1/js/parts/general.min.js"></script>
</body>
</html>
