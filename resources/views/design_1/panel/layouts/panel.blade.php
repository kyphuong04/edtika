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
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">
    <link rel="stylesheet" href="/assets/design_1/css/panel.min.css">

    @if($isRtl)
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings() !!}

        /* Collapsed Sidebar Styles */
        .panel-sidebar.panel-sidebar--collapsed {
            width: 70px !important;
            transition: width 0.3s ease;
        }
        
        .panel-sidebar.panel-sidebar--collapsed .sidebar-text,
        .panel-sidebar.panel-sidebar--collapsed .sidebar-section-title,
        .panel-sidebar.panel-sidebar--collapsed .collapse-arrow-icon {
            display: none !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed .panel-sidebar__menu,
        .panel-sidebar.panel-sidebar--collapsed .panel-sidebar__menu-item {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed .panel-sidebar__menu-item:before {
            display: none !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed .sidebar-icon {
            margin: 0 !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed .mt-16 {
            margin-top: 0 !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed .accordion__collapse {
            display: none !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover {
            width: 258px !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover .sidebar-text,
        .panel-sidebar.panel-sidebar--collapsed:hover .sidebar-section-title {
            display: inline-block !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover .collapse-arrow-icon {
            display: flex !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover .panel-sidebar__menu {
            justify-content: flex-start !important;
            padding-left: 32px !important;
            padding-right: 20px !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover .panel-sidebar__menu-item {
            justify-content: flex-start !important;
            padding-left: 56px !important;
            padding-right: 20px !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover .panel-sidebar__menu-item:before {
            display: block !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover .sidebar-icon {
            margin-right: 8px !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover .mt-16 {
            margin-top: 1rem !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover .accordion__collapse {
            display: block !important;
        }
        
        .panel-sidebar__contents {
            overflow-x: hidden;
        }
        
        .sidebar-text,
        .sidebar-section-title,
        .sidebar-icon,
        .collapse-arrow-icon {
            transition: all 0.3s ease;
        }
        
        .sidebar-text {
            white-space: nowrap;
        }
        
        .sidebar-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            flex-shrink: 0;
        }
        
        /* Panel Content Adjustment */
        .panel-sidebar {
            position: fixed;
            left: 0;
            top: 70px;
            height: calc(100vh - 70px);
            z-index: 100;
        }
        
        .panel-content {
            width: calc(100vw - 258px) !important;
            margin-left: 258px !important;
            transition: width 0.3s ease, margin-left 0.3s ease !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed + .panel-content {
            width: calc(100vw - 70px) !important;
            margin-left: 70px !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover + .panel-content {
            width: calc(100vw - 258px) !important;
            margin-left: 258px !important;
        }
        
        .panel-bottom-bar {
            width: calc(100% - 258px) !important;
            transition: width 0.3s ease !important;
            left: 258px !important;
        }
        
        body:has(.panel-sidebar.panel-sidebar--collapsed) .panel-bottom-bar {
            width: calc(100% - 70px) !important;
            left: 70px !important;
        }
        
        body:has(.panel-sidebar.panel-sidebar--collapsed:hover) .panel-bottom-bar {
            width: calc(100% - 258px) !important;
            left: 258px !important;
        }
        
        @media (max-width: 991px) {
            .panel-sidebar {
                position: fixed !important;
                left: auto !important;
            }
            .panel-content {
                width: 100vw !important;
                margin-left: 0 !important;
            }
            .panel-bottom-bar {
                width: 100% !important;
                left: 0 !important;
            }
        }
    </style>

</head>
<body class="{{ $isRtl ? 'rtl' : '' }} {{ "{$userThemeColorMode}-mode" }}">

@php
    $isPanel = true;
@endphp

<div id="panel_app">

    @if(!empty($justContent))
        @yield('content')
    @else

        @include('design_1.panel.includes.header')

        <div class="d-flex">
            @include('design_1.panel.includes.sidebar')

            <div class="panel-content flex-fill">
                @include('design_1.panel.includes.title_and_breadcrumb')

                @if(!empty($panelContentFull))
                    @yield('content')
                @else
                    <div id="panelContentScrollable" class="panel-content__scrollable px-24 px-lg-32 pt-20 pb-40" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
                        @yield('content')
                    </div>
                @endif

            </div>
        </div>
    @endif

    {{-- AI Contents --}}
    @if($authUser->checkAccessToAIContentFeature())
        @include('design_1.panel.ai_contents.generator')
    @endif

    {{-- Cart Drawer --}}
    @include('design_1.web.cart.drawer.index')

    @include('design_1.web.includes.advertise_modal.index')
</div>
<!-- Template JS File -->

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
    var loadingDataPleaseWaitLang = '{{ trans('update.loading_data,_please_wait') }}';
    var requestSuccessLang = '{{ trans('request_success') }}';
    var saveSuccessLang = '{{ trans('success_store') }}';
    var requestFailedLang = '{{ trans('request_failed') }}';
    var oopsLang = '{{ trans('oops') }}';
    var somethingWentWrongLang = '{{ trans('something_went_wrong') }}';
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
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script defer src="/assets/design_1/js/parts/content_delete.min.js"></script>

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
</script>

<script src="/assets/design_1/js/parts/general.min.js"></script>
<script src="/assets/design_1/js/panel/public.min.js"></script>

<script>
    // Handle sidebar hover to adjust content width
    (function() {
        const sidebar = document.getElementById('panelSidebar');
        const content = document.querySelector('.panel-content');
        
        if (sidebar && content) {
            sidebar.addEventListener('mouseenter', function() {
                if (this.classList.contains('panel-sidebar--collapsed')) {
                    content.style.width = 'calc(100vw - 258px)';
                    content.style.marginLeft = '258px';
                }
            });
            
            sidebar.addEventListener('mouseleave', function() {
                if (this.classList.contains('panel-sidebar--collapsed')) {
                    content.style.width = 'calc(100vw - 70px)';
                    content.style.marginLeft = '70px';
                }
            });
            
            // Initialize content width based on sidebar state
            if (sidebar.classList.contains('panel-sidebar--collapsed')) {
                content.style.width = 'calc(100vw - 70px)';
                content.style.marginLeft = '70px';
            } else {
                content.style.width = 'calc(100vw - 258px)';
                content.style.marginLeft = '258px';
            }
        }
    })();
</script>
</body>
</html>
