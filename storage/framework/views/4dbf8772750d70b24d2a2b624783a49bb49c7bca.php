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

    <?php if($isRtl): ?>
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
    <?php endif; ?>

    <?php if(!empty($themeHeaderData['component_name'])): ?>
        <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("theme/headers/{$themeHeaderData['component_name']}")); ?>">
    <?php endif; ?>

    <?php if(!empty($themeFooterData['component_name'])): ?>
        <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("theme/footers/{$themeFooterData['component_name']}")); ?>">
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('styles_top'); ?>
    <?php echo $__env->yieldPushContent('scripts_top'); ?>

    <style>
        <?php echo !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : ''; ?>


        <?php echo getThemeFontsSettings(); ?>


        <?php echo getThemeColorsSettings(); ?>

    </style>

</head>

<body class="bg-gray <?php echo e($isRtl ? 'rtl' : ''); ?> <?php echo e("{$userThemeColorMode}-mode"); ?>">

<div id="app">

    <?php if(!empty($floatingBar) and $floatingBar->position == 'top'): ?>
        <?php echo $__env->make('design_1.web.includes.floating_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if(!isset($appHeader) and !empty($themeHeaderData['component_name'])): ?>
        <?php echo $__env->make("design_1.web.theme.headers.{$themeHeaderData['component_name']}.index", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    
    <?php echo $__env->yieldContent('content'); ?>

    <?php if(!isset($appFooter) and !empty($themeFooterData['component_name'])): ?>
        <?php echo $__env->make("design_1.web.theme.footers.{$themeFooterData['component_name']}.index", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php echo $__env->make('design_1.web.includes.advertise_modal.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(!empty($floatingBar) and $floatingBar->position == 'bottom'): ?>
        <?php echo $__env->make('design_1.web.includes.floating_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    
    <?php echo $__env->make('design_1.web.cart.drawer.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>

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
    var requestSuccessLang = '<?php echo e(trans('public.request_success')); ?>';
    var saveSuccessLang = '<?php echo e(trans('webinars.success_store')); ?>';
    var requestFailedLang = '<?php echo e(trans('public.request_failed')); ?>';
    var oopsLang = '<?php echo e(trans('update.oops')); ?>';
    var somethingWentWrongLang = '<?php echo e(trans('update.something_went_wrong')); ?>';
    var loadingDataPleaseWaitLang = '<?php echo e(trans('update.loading_data,_please_wait')); ?>';
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
<script defer src="/assets/design_1/js/parts/content_delete.min.js"></script>

<?php if(empty($justMobileApp) and checkShowCookieSecurityDialog() and empty($dontShowCookieSecurity)): ?>
    <?php echo $__env->make('design_1.web.includes.cookie_security.cookie-security', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

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
</body>
</html>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/layouts/app.blade.php ENDPATH**/ ?>