<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
    $isEnglish = mb_strtolower(app()->getLocale()) === 'en';
    $authModalText = [
        'dialogAria' => $isEnglish ? 'Sign in and register' : 'Đăng nhập và đăng ký',
        'closeAria' => $isEnglish ? 'Close' : 'Đóng',
        'tabsAria' => 'Auth Tabs',
        'loginTab' => $isEnglish ? 'Log in' : 'Đăng nhập',
        'registerTab' => $isEnglish ? 'Register' : 'Đăng ký',
        'loginTitle' => $isEnglish ? 'Log in to your account' : 'Đăng nhập vào tài khoản của bạn',
        'loginMethodsAria' => $isEnglish ? 'Login Methods' : 'Phương thức đăng nhập',
        'emailMethod' => 'Email',
        'phoneMethod' => $isEnglish ? 'Phone' : 'Điện thoại',
        'password' => $isEnglish ? 'Password' : 'Mật khẩu',
        'forgotPassword' => $isEnglish ? 'Forgot password?' : 'Bạn quên mật khẩu?',
        'noAccount' => $isEnglish ? "Don't have an account?" : 'Bạn chưa có tài khoản?',
        'hasAccount' => $isEnglish ? 'Already have an account?' : 'Bạn đã có tài khoản?',
        'registerTitle' => $isEnglish ? 'Create a new account' : 'Tạo tài khoản mới',
        'fullName' => $isEnglish ? 'Full name' : 'Họ và tên',
        'confirmPassword' => $isEnglish ? 'Confirm password' : 'Nhập lại mật khẩu',
        'sliderImageAlt' => 'Auth slider image',
    ];

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

        .edtika-auth-input-wrap .edtika-auth-input {
            padding-right: 46px;
        }

        .edtika-auth-input:focus {
            outline: none;
            border-color: rgba(81, 29, 153, 0.45);
            background: rgba(255, 255, 255, 0.68);
        }

        .edtika-auth-input-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #511D99; /* prominent brand purple */
            font-size: 18px;
            width: 36px;
            height: 36px;
            border: 0;
            background: rgba(81,29,153,0.08);
            padding: 6px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            pointer-events: auto;
            box-shadow: 0 1px 2px rgba(0,0,0,0.06);
            transition: background .12s ease, transform .08s ease, color .12s ease;
            z-index: 2;
        }

        .edtika-auth-input-icon:hover {
            background: rgba(81,29,153,0.12);
            transform: translateY(-50%) scale(1.03);
            color: #3b0f9c;
        }

        .edtika-auth-input-icon:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(81,29,153,0.12);
        }

        .edtika-auth-input-icon i {
            pointer-events: none;
        }

        .edtika-auth-forgot {
            display: block;
            width: 100%;
            text-align: right;
            margin-top: 2px;
            margin-bottom: 18px;
            border: 0;
            background: transparent;
            padding: 0;
            cursor: pointer;
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
        <div class="edtika-auth-modal__dialog" role="dialog" aria-modal="true" aria-label="{{ $authModalText['dialogAria'] }}">
            <button type="button" class="edtika-auth-modal__close" id="edtikaAuthModalClose" aria-label="{{ $authModalText['closeAria'] }}">&times;</button>

            <div class="edtika-auth-modal__content">
                <div class="edtika-auth-modal__form-side">
                    <div class="edtika-auth-modal__tabs" role="tablist" aria-label="{{ $authModalText['tabsAria'] }}">
                        <button type="button" class="edtika-auth-modal__tab is-active" data-auth-tab="login">{{ $authModalText['loginTab'] }}</button>
                        <button type="button" class="edtika-auth-modal__tab" data-auth-tab="register">{{ $authModalText['registerTab'] }}</button>
                    </div>

                    <div class="edtika-auth-pane is-active" data-auth-pane="login">
                        <h3 class="edtika-auth-pane__title">{{ $authModalText['loginTitle'] }}</h3>

                        @if(session()->has('login_failed_active_session'))
                            <div class="mb-16 p-16 rounded-12 border-danger bg-danger-20">
                                <div class="font-14 font-weight-bold text-danger">{{ session()->get('login_failed_active_session')['title'] ?? trans('update.login_failed') }}</div>
                                <div class="mt-4 font-12 text-danger">{{ session()->get('login_failed_active_session')['msg'] ?? trans('update.device_limit_reached_please_try_again') }}</div>
                            </div>
                        @endif

                        <div class="edtika-auth-methods" role="tablist" aria-label="{{ $authModalText['loginMethodsAria'] }}">
                            <button type="button" class="edtika-auth-method is-active" data-login-method="email">{{ $authModalText['emailMethod'] }}</button>
                            <button type="button" class="edtika-auth-method" data-login-method="phone">{{ $authModalText['phoneMethod'] }}</button>
                        </div>

                        <form method="POST" action="/login">
                            @csrf
                            <input type="hidden" name="type" id="edtikaLoginType" value="email">

                            <div class="edtika-auth-field" data-login-field="email">
                                <label class="edtika-auth-label" for="edtikaLoginEmail">Email *</label>
                                <input id="edtikaLoginEmail" class="edtika-auth-input" type="email" name="email" autocomplete="email">
                            </div>

                            <div class="edtika-auth-field" data-login-field="phone" style="display: none;">
                                <label class="edtika-auth-label" for="edtikaLoginPhone">{{ $authModalText['phoneMethod'] }} *</label>
                                <input id="edtikaLoginPhone" class="edtika-auth-input" type="text" name="mobile" autocomplete="tel">
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaLoginPassword">{{ $authModalText['password'] }} *</label>
                                <div class="edtika-auth-input-wrap">
                                    <input id="edtikaLoginPassword" class="edtika-auth-input" type="password" name="password" autocomplete="current-password">
                                    <button type="button" class="edtika-auth-input-icon" data-password-toggle data-password-target="edtikaLoginPassword" aria-label="{{ app()->getLocale() === 'en' ? 'Show password' : 'Hiển thị mật khẩu' }}">
                                        <x-iconsax-lin-eye-slash class="icons-eye-slash d-none" width="24px" height="24px"/>
                                        <x-iconsax-lin-eye class="icons-eye" width="24px" height="24px"/>
                                    </button>
                                </div>
                            </div>

                            <button type="button" class="edtika-auth-forgot" data-auth-tab-switch="forgot">{{ $authModalText['forgotPassword'] }}</button>
                            <button type="submit" class="edtika-auth-submit">{{ $authModalText['loginTab'] }}</button>
                        </form>

                        <div class="edtika-auth-switch-note">
                            {{ $authModalText['noAccount'] }} <button type="button" data-auth-tab-switch="register">{{ $authModalText['registerTab'] }}</button>
                        </div>
                    </div>

                    <div class="edtika-auth-pane" data-auth-pane="register">
                        <h3 class="edtika-auth-pane__title">{{ $authModalText['registerTitle'] }}</h3>

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
                                <label class="edtika-auth-label" for="edtikaRegisterEmail">Email *</label>
                                <input id="edtikaRegisterEmail" class="edtika-auth-input" type="email" name="email" autocomplete="email">
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaRegisterPhone">{{ app()->getLocale() === 'en' ? 'Phone (Optional)' : 'Điện thoại (Tùy chọn)' }}</label>
                                <input id="edtikaRegisterPhone" class="edtika-auth-input" type="text" name="mobile" autocomplete="tel">
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaRegisterFullName">{{ $authModalText['fullName'] }} *</label>
                                <input id="edtikaRegisterFullName" class="edtika-auth-input" type="text" name="full_name" autocomplete="name">
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaRegisterPassword">{{ $authModalText['password'] }} *</label>
                                <div class="edtika-auth-input-wrap">
                                    <input id="edtikaRegisterPassword" class="edtika-auth-input" type="password" name="password" autocomplete="new-password">
                                    <button type="button" class="edtika-auth-input-icon" data-password-toggle data-password-target="edtikaRegisterPassword" aria-label="{{ app()->getLocale() === 'en' ? 'Show password' : 'Hiển thị mật khẩu' }}">
                                        <x-iconsax-lin-eye-slash class="icons-eye-slash d-none" width="24px" height="24px"/>
                                        <x-iconsax-lin-eye class=" icons-eye" width="24px" height="24px"/>
                                    </button>
                                </div>
                            </div>

                            <div class="edtika-auth-field">
                                <label class="edtika-auth-label" for="edtikaRegisterPasswordConfirmation">{{ $authModalText['confirmPassword'] }} *</label>
                                <div class="edtika-auth-input-wrap">
                                    <input id="edtikaRegisterPasswordConfirmation" class="edtika-auth-input" type="password" name="password_confirmation" autocomplete="new-password">
                                    <button type="button" class="edtika-auth-input-icon" data-password-toggle data-password-target="edtikaRegisterPasswordConfirmation" aria-label="{{ app()->getLocale() === 'en' ? 'Show password confirmation' : 'Hiển thị nhập lại mật khẩu' }}">
                                        <x-iconsax-lin-eye-slash class="icons-eye-slash d-none" width="24px" height="24px"/>
                                        <x-iconsax-lin-eye class="icons-eye" width="24px" height="24px"/>
                                    </button>
                                </div>
                            </div>

                            <label class="edtika-auth-check">
                                <input type="checkbox" name="term" value="1" required>
                                <span class="edtika-auth-check__box">✓</span>
                                <span class="edtika-auth-check__text">
                                    {{ app()->getLocale() === 'en' ? 'I agree to the' : 'Tôi đồng ý với' }} <strong>{{ app()->getLocale() === 'en' ? 'terms & rules' : 'điều khoản & quy tắc' }}</strong>
                                </span>
                            </label>

                            <button type="submit" class="edtika-auth-submit">{{ $authModalText['registerTab'] }}</button>
                        </form>

                        <div class="edtika-auth-switch-note">
                            {{ $authModalText['hasAccount'] }} <button type="button" data-auth-tab-switch="login">{{ $authModalText['loginTab'] }}</button>
                        </div>
                    </div>

                    <div class="edtika-auth-pane" data-auth-pane="forgot">
                        <h3 class="edtika-auth-pane__title">{{ trans('update.recover_your_password') }}</h3>

                        <form method="POST" action="/forget-password">
                            @csrf
                            <input type="hidden" name="type" id="edtikaForgotType" value="email">

                            <div class="edtika-auth-methods" role="tablist" aria-label="{{ app()->getLocale() === 'en' ? 'Forgot password methods' : 'Phương thức lấy lại mật khẩu' }}">
                                <button type="button" class="edtika-auth-method is-active" data-forgot-method="email">{{ $authModalText['emailMethod'] }}</button>
                                <button type="button" class="edtika-auth-method" data-forgot-method="phone">{{ $authModalText['phoneMethod'] }}</button>
                            </div>

                            <div class="edtika-auth-field" data-forgot-field="email">
                                <label class="edtika-auth-label" for="edtikaForgotEmail">Email *</label>
                                <input id="edtikaForgotEmail" class="edtika-auth-input" type="email" name="email" autocomplete="email">
                            </div>

                            <div class="edtika-auth-field" data-forgot-field="phone" style="display: none;">
                                <label class="edtika-auth-label" for="edtikaForgotPhone">{{ $authModalText['phoneMethod'] }} *</label>
                                <input id="edtikaForgotPhone" class="edtika-auth-input" type="text" name="mobile" autocomplete="tel">
                            </div>

                            @if(!empty(getGeneralSecuritySettings('captcha_for_forgot_pass')))
                                <div class="edtika-auth-field">
                                    @include('design_1.web.includes.captcha_input')
                                </div>
                            @endif

                            <button type="submit" class="edtika-auth-submit">{{ trans('auth.reset_password') }}</button>
                        </form>

                        <div class="edtika-auth-switch-note">
                            <button type="button" data-auth-tab-switch="login">{{ $authModalText['loginTab'] }}</button>
                            <span> / </span>
                            <button type="button" data-auth-tab-switch="register">{{ $authModalText['registerTab'] }}</button>
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
                                            <img class="edtika-auth-slider__image" src="{{ $authSlide['image'] }}" alt="{{ $authModalText['sliderImageAlt'] }}">
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
        var forgotMethodBtns = document.querySelectorAll('[data-forgot-method]');
        var forgotFieldBlocks = document.querySelectorAll('[data-forgot-field]');
        var forgotTypeInput = document.getElementById('edtikaForgotType');
        var passwordToggleBtns = document.querySelectorAll('[data-password-toggle]');
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
                loginTypeInput.value = method;
            }
        };

        loginMethodBtns.forEach(function (methodBtn) {
            methodBtn.addEventListener('click', function () {
                setLoginMethod(methodBtn.getAttribute('data-login-method'));
            });
        });

        var setForgotMethod = function (method) {
            forgotMethodBtns.forEach(function (methodBtn) {
                methodBtn.classList.toggle('is-active', methodBtn.getAttribute('data-forgot-method') === method);
            });

            forgotFieldBlocks.forEach(function (fieldBlock) {
                var isTarget = fieldBlock.getAttribute('data-forgot-field') === method;
                fieldBlock.style.display = isTarget ? 'block' : 'none';
            });

            if (forgotTypeInput) {
                forgotTypeInput.value = method === 'phone' ? 'mobile' : 'email';
            }
        };

        forgotMethodBtns.forEach(function (methodBtn) {
            methodBtn.addEventListener('click', function () {
                setForgotMethod(methodBtn.getAttribute('data-forgot-method'));
            });
        });

        passwordToggleBtns.forEach(function (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                var targetId = toggleBtn.getAttribute('data-password-target');
                var targetInput = targetId ? document.getElementById(targetId) : null;

                if (!targetInput) {
                    return;
                }

                var isHidden = targetInput.getAttribute('type') === 'password';
                targetInput.setAttribute('type', isHidden ? 'text' : 'password');

                var nowHidden = targetInput.getAttribute('type') === 'password';

                var icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye', !nowHidden);
                    icon.classList.toggle('fa-eye-slash', nowHidden);
                } else {
                    var eye = toggleBtn.querySelector('.icons-eye');
                    var eyeSlash = toggleBtn.querySelector('.icons-eye-slash');
                    if (eye && eyeSlash) {
                        eye.classList.toggle('d-none', nowHidden);
                        eyeSlash.classList.toggle('d-none', !nowHidden);
                    }
                }
            });
        });

        authSliderDots.forEach(function (dot, dotIndex) {
            dot.addEventListener('click', function () {
                setActiveAuthSlide(dotIndex);
            });
        });

        setLoginMethod('email');
        setForgotMethod('email');

        if (authLoginFailedSession || authModalShouldOpen) {
            openAuthModal();
        }

        if (authSliderSlides.length) {
            setActiveAuthSlide(0);
        }
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
<script>
    // Fallback delegated handler for password toggle buttons
    (function () {
        'use strict';

        document.addEventListener('click', function (e) {
            var btn = e.target && e.target.closest ? e.target.closest('[data-password-toggle]') : null;
            if (!btn) return;

            var targetId = btn.getAttribute('data-password-target');
            var targetInput = targetId ? document.getElementById(targetId) : null;

            if (!targetInput) {
                // try to find input inside same wrap
                var wrap = btn.closest('.edtika-auth-input-wrap') || btn.closest('.form-group');
                if (wrap) {
                    targetInput = wrap.querySelector('input[type="password"], input[type="text"]');
                }
            }

            if (!targetInput) return;

            var wasHidden = targetInput.getAttribute('type') === 'password';
            targetInput.setAttribute('type', wasHidden ? 'text' : 'password');

            var nowHidden = targetInput.getAttribute('type') === 'password';

            var icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !nowHidden);
                icon.classList.toggle('fa-eye-slash', nowHidden);
            }

            var eye = btn.querySelector('.icons-eye');
            var eyeSlash = btn.querySelector('.icons-eye-slash');
            if (eye && eyeSlash) {
                eye.classList.toggle('d-none', nowHidden);
                eyeSlash.classList.toggle('d-none', !nowHidden);
            }
        });
    })();
</script>
</body>
</html>
