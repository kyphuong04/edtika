<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<?php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
?>

<head>
    <?php echo $__env->make('design_1.web.includes.metas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title><?php echo e($pageTitle ?? ''); ?><?php echo e(!empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : ''); ?></title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">
    <link rel="stylesheet" href="/assets/design_1/css/panel.min.css">

    <?php if($isRtl): ?>
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('styles_top'); ?>
    <?php echo $__env->yieldPushContent('scripts_top'); ?>

    <style>
        <?php echo !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : ''; ?>


        <?php echo getThemeFontsSettings(); ?>


        <?php echo getThemeColorsSettings(); ?>


        /* Collapsed Sidebar Styles */
        .panel-sidebar.panel-sidebar--collapsed {
            width: 80px !important;
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
<body class="<?php echo e($isRtl ? 'rtl' : ''); ?> <?php echo e("{$userThemeColorMode}-mode"); ?>">

<?php
    $isPanel = true;
?>

<div id="panel_app">

    <?php if(!empty($justContent)): ?>
        <?php echo $__env->yieldContent('content'); ?>
    <?php else: ?>

        <?php echo $__env->make('design_1.panel.includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="d-flex">
            <?php echo $__env->make('design_1.panel.includes.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="panel-content flex-fill">
                <?php echo $__env->make('design_1.panel.includes.title_and_breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php if(!empty($panelContentFull)): ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php else: ?>
                    <div id="panelContentScrollable" class="panel-content__scrollable px-24 px-lg-32 pt-20 pb-40" data-simplebar <?php if((!empty($isRtl))): ?> data-simplebar-direction="rtl" <?php endif; ?>>
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>

    
    <?php if($authUser->checkAccessToAIContentFeature()): ?>
        <?php echo $__env->make('design_1.panel.ai_contents.generator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    
    <?php echo $__env->make('design_1.web.cart.drawer.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('design_1.web.includes.advertise_modal.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<!-- Template JS File -->

<!-- Template JS File -->
<script>
    var siteDomain = '<?php echo e(url('')); ?>';
    var deleteAlertTitle = '<?php echo e(trans('public.are_you_sure')); ?>';
    var deleteAlertHint = '<?php echo e(trans('public.deleteAlertHint')); ?>';
    var deleteAlertConfirm = '<?php echo e(trans('public.deleteAlertConfirm')); ?>';
    var deleteAlertCancel = '<?php echo e(trans('public.cancel')); ?>';
    var deleteAlertSuccess = '<?php echo e(trans('public.success')); ?>';
    var deleteAlertFail = '<?php echo e(trans('public.fail')); ?>';
    var deleteAlertFailHint = '<?php echo e(trans('public.deleteAlertFailHint')); ?>';
    var deleteAlertSuccessHint = '<?php echo e(trans('public.deleteAlertSuccessHint')); ?>';
    var forbiddenRequestToastTitleLang = '<?php echo e(trans('public.forbidden_request_toast_lang')); ?>';
    var forbiddenRequestToastMsgLang = '<?php echo e(trans('public.forbidden_request_toast_msg_lang')); ?>';
    var priceInvalidHintLang = '<?php echo e(trans('update.price_invalid_hint')); ?>';
    var clearLang = '<?php echo e(trans('clear')); ?>';
    var loadingDataPleaseWaitLang = '<?php echo e(trans('update.loading_data,_please_wait')); ?>';
    var requestSuccessLang = '<?php echo e(trans('request_success')); ?>';
    var saveSuccessLang = '<?php echo e(trans('success_store')); ?>';
    var requestFailedLang = '<?php echo e(trans('request_failed')); ?>';
    var oopsLang = '<?php echo e(trans('oops')); ?>';
    var somethingWentWrongLang = '<?php echo e(trans('something_went_wrong')); ?>';
    var deleteRequestLang = '<?php echo e(trans('update.delete_request')); ?>';
    var deleteRequestTitleLang = '<?php echo e(trans('update.delete_request_title')); ?>';
    var deleteRequestDescriptionLang = '<?php echo e(trans('update.delete_request_description')); ?>';
    var requestDetailsLang = '<?php echo e(trans('update.request_details')); ?>';
    var sendRequestLang = '<?php echo e(trans('update.send_request')); ?>';
    var closeLang = '<?php echo e(trans('public.close')); ?>';
    var generatedContentLang = '<?php echo e(trans('update.generated_content')); ?>';
    var copyLang = '<?php echo e(trans('public.copy')); ?>';
    var doneLang = '<?php echo e(trans('public.done')); ?>';
    var jsCurrentCurrency = '<?php echo e($currency); ?>';
    var defaultLocale = '<?php echo e(getUserLocale()); ?>';
    var appLocale = '<?php echo e(app()->getLocale()); ?>';
    var dangerCloseIcon = `<?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '24','height' => '24']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>`;
    var directSendIcon = `<?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-direct-send'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24','height' => '24']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>`;
    var closeIcon = `<?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'close-icon','width' => '25px','height' => '25px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>`;
    var bulDangerIcon = `<?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-danger'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>`;
    var defaultAvatarPath = "<?php echo e(getDefaultAvatarPath()); ?>";
    var themeColorsMode = <?php echo json_encode(getThemeColorsMode(), 15, 512) ?>;
</script>

<script type="text/javascript" src="/assets/design_1/js/app.min.js"></script>
<script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script defer src="/assets/design_1/js/parts/content_delete.min.js"></script>

<?php if(session()->has('toast')): ?>
    <script>
        (function () {
            "use strict";

            showToast('<?php echo e(session()->get('toast')['status']); ?>', '<?php echo e(session()->get('toast')['title'] ?? ''); ?>', '<?php echo e(session()->get('toast')['msg'] ?? ''); ?>')
        })(jQuery)
    </script>
<?php endif; ?>

<?php echo $__env->make('design_1.web.includes.purchase_notifications', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php echo $__env->yieldPushContent('styles_bottom'); ?>
<?php echo $__env->yieldPushContent('scripts_bottom'); ?>

<?php echo $__env->yieldPushContent('scripts_bottom2'); ?>

<script>

    <?php if(session()->has('registration_package_limited')): ?>
    (function () {
        "use strict";

        handleFireSwalModal('<?php echo session()->get('registration_package_limited'); ?>', 32)
    })(jQuery)

    <?php echo e(session()->forget('registration_package_limited')); ?>

    <?php endif; ?>

    <?php echo !empty($themeCustomCssAndJs['js']) ? $themeCustomCssAndJs['js'] : ''; ?>

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
</html><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/layouts/panel.blade.php ENDPATH**/ ?>