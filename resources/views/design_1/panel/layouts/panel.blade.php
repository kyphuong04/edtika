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
    <link rel="stylesheet" href="/assets/design_1/css/panel.min.css">

    @if($isRtl)
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        :root {
            --main-font-family: 'Roboto', sans-serif !important;
        }
        body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, textarea, select, .btn, div {
            font-family: 'Roboto', sans-serif !important;
        }

        {!! !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings() !!}

        :root {
            --glass-surface-bg: rgba(212, 211, 254, 0.5);
            --glass-surface-border: rgba(255, 255, 255, 0.42);
            --glass-surface-shadow: 0 14px 32px rgba(58, 65, 111, 0.16);
        }

        #panelSidebar,
        #panelSidebar .panel-sidebar__contents,
        #panelSidebar .panel-sidebar__pinned-bottom {
            background: var(--glass-surface-bg) !important;
            border: 1px solid var(--glass-surface-border);
            box-shadow: var(--glass-surface-shadow);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        #panelSidebar .panel-sidebar__scroll-area,
        #panelSidebar #sidebarAccordions,
        #panelSidebar #sidebarBottomAccordions {
            background: transparent !important;
        }

        .panel-content :is(div, section, article, aside).bg-white,
        .panel-content :is(div, section, article, aside)[class*="card"]:not([class*="__"]),
        .panel-content :is(div, section, article, aside)[class*="box"]:not([class*="__"]),
        .panel-content :is(div, section, article, aside)[class*="panel-section"]:not([class*="__"]),
        .panel-content :is(div, section, article, aside)[class*="section-card"]:not([class*="__"]),
        .panel-content :is(div, section, article, aside)[style*="background:#fff"],
        .panel-content :is(div, section, article, aside)[style*="background: #fff"],
        .panel-content :is(div, section, article, aside)[style*="background-color:#fff"],
        .panel-content :is(div, section, article, aside)[style*="background-color: #fff"],
        .panel-content .panel-section-card,
        .panel-content .card {
            background: var(--glass-surface-bg) !important;
            border: 1px solid var(--glass-surface-border) !important;
            box-shadow: var(--glass-surface-shadow) !important;
            backdrop-filter: blur(14px) !important;
            -webkit-backdrop-filter: blur(14px) !important;
        }

        .panel-content .rounded-circle.bg-white,
        .panel-content .badge.bg-white,
        .panel-content .btn.bg-white {
            border: 0 !important;
            box-shadow: none !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        /* ── Hide top header + title bar globally ───────────────── */
        .panel-header { display: none !important; }
        .panel-title-and-breadcrumb { display: none !important; }
        #panelSidebar  { top: 0 !important; height: 100vh !important; }
        #panelSidebar .panel-sidebar__contents { max-height: 100vh !important; display: flex !important; flex-direction: column !important; height: 100% !important; }
        #panelSidebar .panel-sidebar__scroll-area { flex: 1 !important; display: flex !important; flex-direction: column !important; overflow-y: auto !important; overflow-x: hidden !important; min-height: 0 !important; }
        #panelSidebar #sidebarAccordions { width: 100% !important; min-height: 100% !important; display: flex !important; flex-direction: column !important; justify-content: space-evenly !important; padding: 8px 0 !important; }
        #panelSidebar #sidebarAccordions > .mt-16 { margin-top: 0 !important; }
        .panel-content__scrollable { height: 100vh !important; }

        /* ── Collapsed sidebar: 90px wide + bigger icons (global) ── */
        #panelSidebar.panel-sidebar--collapsed { width: 90px !important; }
        #panelSidebar.panel-sidebar--collapsed:hover { width: 258px !important; }
        #panelSidebar .sidebar-icon svg,
        #panelSidebar .sidebar-icon .icons { width: 30px !important; height: 30px !important; }
        #panelSidebar.panel-sidebar--collapsed .panel-sidebar__menu,
        #panelSidebar.panel-sidebar--collapsed .panel-sidebar__menu-item { height: 52px !important; }

        /* Sidebar Default State - Expanded */
        .panel-sidebar:not(.panel-sidebar--collapsed) .sidebar-icon {
            margin-right: 8px !important;
        }
        
        .panel-sidebar:not(.panel-sidebar--collapsed) .panel-sidebar__menu > div,
        .panel-sidebar:not(.panel-sidebar--collapsed) .panel-sidebar__menu-item > div {
            display: flex;
            align-items: center;
        }

        /* Sidebar Hover Effects */
        .panel-sidebar__menu,
        .panel-sidebar__menu-item {
            height: 40px;
            transition: background-color 0.2s ease, transform 0.1s ease;
            cursor: pointer;
            border-radius: 8px;
            margin: 2px 0;
        }
        
        .panel-sidebar__menu:hover,
        .panel-sidebar__menu-item:hover {
            background-color: #F3F4F6 !important;
        }
        
        .accordion .panel-sidebar__menu.accordion__title:hover {
            background-color: #F3F4F6 !important;
        }
        
        a.panel-sidebar__menu:hover,
        a.panel-sidebar__menu-item:hover {
            background-color: #F3F4F6 !important;
            text-decoration: none;
        }
        
        div.panel-sidebar__menu:hover {
            background-color: #F3F4F6 !important;
        }
        
        .panel-sidebar__panel-user-menu,
        .panel-sidebar__panel-user-menu-item {
            height: 48px;
            transition: background-color 0.2s ease;
            cursor: pointer;
            border-radius: 8px;
            margin: 2px 0;
        }
        
        .panel-sidebar__panel-user-menu:hover,
        .panel-sidebar__panel-user-menu-item:hover {
            background-color: #F3F4F6 !important;
        }

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
            display: flex !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed .panel-sidebar__menu > div,
        .panel-sidebar.panel-sidebar--collapsed .panel-sidebar__menu-item > div {
            justify-content: center !important;
            width: 100%;
        }
        
        .panel-sidebar.panel-sidebar--collapsed .panel-sidebar__menu-item:before {
            display: none !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed .sidebar-icon {
            margin: 0 auto !important;
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
            text-align: left !important;
        }
        
        .panel-sidebar.panel-sidebar--collapsed:hover .panel-sidebar__menu > div {
            justify-content: flex-start !important;
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
            margin: 0 !important;
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
            display: flex;
            flex-direction: column;
            height: 100%;
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
            width: calc(100vw - 90px) !important;
            margin-left: 90px !important;
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
            width: calc(100% - 90px) !important;
            left: 90px !important;
        }
        
        body:has(.panel-sidebar.panel-sidebar--collapsed:hover) .panel-bottom-bar {
            width: calc(100% - 258px) !important;
            left: 258px !important;
        }

        /* ── Desktop: equal left gap for sidebar (matches right padding 40px) ── */
        @media (min-width: 992px) {
            #panelSidebar {
                left: 90px !important;
                top: 16px !important;
                height: calc(100vh - 32px) !important;
                border-radius: 16px;
                overflow: hidden;
            }
            #panelSidebar .panel-sidebar__contents { max-height: calc(100vh - 32px) !important; height: calc(100vh - 32px) !important; }
            .panel-content {
                /* 258px sidebar + 40px left gap = 298px */
                width: calc(100vw - 298px) !important;
                margin-left: 298px !important;
            }
            .panel-sidebar.panel-sidebar--collapsed + .panel-content {
                /* 90px sidebar + 40px left gap = 130px */
                width: calc(100vw - 130px) !important;
                margin-left: 130px !important;
            }
            .panel-sidebar.panel-sidebar--collapsed:hover + .panel-content {
                width: calc(100vw - 298px) !important;
                margin-left: 298px !important;
            }
            .panel-bottom-bar {
                width: calc(100% - 298px) !important;
                left: 298px !important;
            }
            body:has(.panel-sidebar.panel-sidebar--collapsed) .panel-bottom-bar {
                width: calc(100% - 130px) !important;
                left: 130px !important;
            }
            body:has(.panel-sidebar.panel-sidebar--collapsed:hover) .panel-bottom-bar {
                width: calc(100% - 298px) !important;
                left: 298px !important;
            }
        }
        
        @media (max-width: 991px) {
            #panelSidebar {
                left: auto !important;
                top: 0 !important;
                height: 100vh !important;
                border-radius: 0 !important;
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

        /* ── Equal outer padding — both sides of all panel pages ── */
        .panel-content {
            box-sizing: border-box;
            padding: 16px 28px 0;
        }
        @media (min-width: 992px) {
            .panel-content {
                padding: 16px 70px 0;
            }
        }
        /* Remove internal padding from scrollable — outer .panel-content handles spacing */
        #panelContentScrollable {
            padding-left: 0 !important;
            padding-right: 0 !important;
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
                @if(empty($hidePanelTitleBar))
                    @include('design_1.panel.includes.title_and_breadcrumb')
                @endif

                @if(!empty($panelContentFull))
                    @yield('content')
                @else
                    <div id="panelContentScrollable" class="panel-content__scrollable px-24 px-lg-32 pb-40" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
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

    {{-- Page-level modals (rendered outside simplebar to avoid stacking-context issues) --}}
    @stack('panel_modals')
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
<script>
    // Force sidebar to always remain expanded - disable collapse/expand behaviour
    (function() {
        localStorage.setItem('panelSidebarCollapsed', 'false');
        var s = document.getElementById('panelSidebar');
        if (s) s.classList.remove('panel-sidebar--collapsed');
    })();
</script>
</body>
</html>