<html lang="{{ app()->getLocale() }}">
@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
@endphp
<head>
    @include('design_1.web.includes.metas')
    <title>{{ $pageTitle ?? '' }} </title>

    <!-- General CSS File -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">


    @stack('libraries_top')

    <link rel="stylesheet" href="/assets/admin/css/style.css">
    <link rel="stylesheet" href="/assets/admin/css/custom.css?v={{ time() }}">
    <link rel="stylesheet" href="/assets/admin/css/components.css">
    <link rel="stylesheet" href="/assets/admin/css/extra.min.css">
    @if($isRtl)
        <link rel="stylesheet" href="/assets/admin/css/rtl.css">
    @endif
    <link rel="stylesheet" href="/assets/admin/vendor/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings(true) !!}

        :root {
            --main-font-family: 'Roboto', sans-serif !important;
            --glass-surface-bg: rgba(212, 211, 254, 0.5);
            --glass-surface-border: rgba(255, 255, 255, 0.42);
            --glass-surface-shadow: 0 14px 32px rgba(58, 65, 111, 0.16);
            --admin-card-radius: 18px;
        }
        body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, textarea, select, .btn, div {
            font-family: 'Roboto', sans-serif !important;
        }

        .main-sidebar,
        .main-sidebar #sidebar-wrapper {
            background: var(--glass-surface-bg) !important;
            border-right: 1px solid var(--glass-surface-border);
            box-shadow: var(--glass-surface-shadow);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .main-content {
            margin-top: 18px !important;
            padding-top: 0 !important;
        }

        .main-content .card,
        .main-content .card .card-header,
        .main-content .card .card-body,
        .main-content .card .card-footer,
        .main-content .card-statistic__wrap,
        .main-content .map-card,
        .main-content :is(div, section, article, aside, button, a).bg-white,
        .main-content :is(div, section, article, aside, button, a)[style*="background:#fff"],
        .main-content :is(div, section, article, aside, button, a)[style*="background: #fff"],
        .main-content :is(div, section, article, aside, button, a)[style*="background-color:#fff"],
        .main-content :is(div, section, article, aside, button, a)[style*="background-color: #fff"] {
            background: var(--glass-surface-bg) !important;
            border-color: var(--glass-surface-border) !important;
            box-shadow: var(--glass-surface-shadow) !important;
            backdrop-filter: blur(14px) !important;
            -webkit-backdrop-filter: blur(14px) !important;
            border-radius: var(--admin-card-radius) !important;
        }

        .main-content .card .card-header:first-child {
            border-top-left-radius: var(--admin-card-radius) !important;
            border-top-right-radius: var(--admin-card-radius) !important;
        }

        .main-content .card .card-footer:last-child {
            border-bottom-left-radius: var(--admin-card-radius) !important;
            border-bottom-right-radius: var(--admin-card-radius) !important;
        }

        .main-content .card-statistic-1 .card-icon,
        .main-content .card-statistic-2 .card-icon,
        .main-content .card-statistic-2 .card-stats {
            border-radius: calc(var(--admin-card-radius) - 6px) !important;
        }

        .main-content .rounded-circle.bg-white,
        .main-content .badge.bg-white,
        .main-content .btn.bg-white {
            border: 0 !important;
            box-shadow: none !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }
    </style>
    <link rel="stylesheet" href="/assets/design_1/css/overrides.css">
</head>
<body class="sidebar-mini sidebar-hover-expand {{ $isRtl ? 'rtl' : '' }}">

<div id="app">
    <div class="main-wrapper">
        @include('admin.includes.sidebar.index')

        @include('admin.includes.header.index')


        <div class="main-content">

            @yield('content')

        </div>
    </div>

    <div class="modal fade" id="fileViewModal" tabindex="-1" aria-labelledby="fileViewModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <img src="" class="img-fluid" alt="">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('public.close') }}</button>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- AI Contents --}}
@if(!empty(getAiContentsSettingsName("status")) and !empty(getAiContentsSettingsName("active_for_admin_panel")))
    @include('admin.includes.aiContent.generator')
@endif

<script>
    window.adminPanelPrefix = '{{ getAdminPanelUrl() }}';
</script>

<!-- General JS Scripts -->
<script src="/assets/admin/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="/assets/admin/vendor/poper/popper.min.js"></script>
<script src="/assets/admin/vendor/bootstrap/bootstrap.min.js"></script>
<script src="/assets/admin/vendor/nicescroll/jquery.nicescroll.min.js"></script>
<script src="/assets/admin/vendor/moment/moment.min.js"></script>
<script src="/assets/admin/js/stisla.js"></script>
<script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>


<script src="/assets/admin/vendor/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/default/vendors/select2/select2.min.js"></script>

<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        window.adminPanelPrefix = '{{ getAdminPanelUrl() }}';
        var inputTooShortLang = '{{ trans('public.input_too_short') }}';
        var noResultsLang = '{{ trans('admin/main.no_result_found') }}';
        var searchingLang = '{{ trans('admin/main.searching') }}';
    </script>

    <!-- Template JS File -->
    <script src="/assets/admin/js/scripts.js"></script>
    <script src="/assets/admin/js/admin.min.js"></script>

    <script>
        if (jQuery().select2) {
            $.fn.select2.defaults.set('language', {
                inputTooShort: function () {
                    return inputTooShortLang;
                },
                noResults: function () {
                    return noResultsLang;
                },
                searching: function () {
                    return searchingLang;
                }
            });
        }
    </script>

    @stack('styles_bottom')
    @stack('scripts_bottom')

    <script>
        (function () {
            "use strict";

            @if(session()->has('toast'))
            showToast('{{ session()->get('toast')['status'] }}', '{{ session()->get('toast')['title'] ?? '' }}', '{{ session()->get('toast')['msg'] ?? '' }}')
            @endif
        })(jQuery);


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
        var generatedContentLang = '{{ trans('update.generated_content') }}';
        var copyLang = '{{ trans('public.copy') }}';
        var doneLang = '{{ trans('public.done') }}';
        var priceInvalidHintLang = '{{ trans('update.price_invalid_hint') }}';
    </script>

    <script src="/assets/admin/js/custom.js?v={{ time() }}"></script>
    <script src="/assets/admin/js/parts/ai-content-generator.min.js"></script>

<script>
    {!! !empty($themeCustomCssAndJs['js']) ? $themeCustomCssAndJs['js'] : '' !!}
</script>
</body>
</html>
