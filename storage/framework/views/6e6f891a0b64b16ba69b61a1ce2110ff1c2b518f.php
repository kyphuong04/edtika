<html lang="<?php echo e(app()->getLocale()); ?>">
<?php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
?>
<head>
    <?php echo $__env->make('design_1.web.includes.metas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title><?php echo e($pageTitle ?? ''); ?> </title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">


    <?php echo $__env->yieldPushContent('libraries_top'); ?>

    <link rel="stylesheet" href="/assets/admin/css/style.css">
    <link rel="stylesheet" href="/assets/admin/css/custom.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="/assets/admin/css/components.css">
    <link rel="stylesheet" href="/assets/admin/css/extra.min.css">
    <?php if($isRtl): ?>
        <link rel="stylesheet" href="/assets/admin/css/rtl.css">
    <?php endif; ?>
    <link rel="stylesheet" href="/assets/admin/vendor/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">

    <?php echo $__env->yieldPushContent('styles_top'); ?>
    <?php echo $__env->yieldPushContent('scripts_top'); ?>

    <style>
        <?php echo !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : ''; ?>


        <?php echo getThemeFontsSettings(); ?>


        <?php echo getThemeColorsSettings(true); ?>

    </style>
</head>
<body class="sidebar-mini sidebar-hover-expand <?php echo e($isRtl ? 'rtl' : ''); ?>">

<div id="app">
    <div class="main-wrapper">
        <?php echo $__env->make('admin.includes.header.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('admin.includes.sidebar.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


        <div class="main-content">

            <?php echo $__env->yieldContent('content'); ?>

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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('public.close')); ?></button>
                </div>
            </div>
        </div>
    </div>

</div>


<?php if(!empty(getAiContentsSettingsName("status")) and !empty(getAiContentsSettingsName("active_for_admin_panel"))): ?>
    <?php echo $__env->make('admin.includes.aiContent.generator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<script>
    window.adminPanelPrefix = '<?php echo e(getAdminPanelUrl()); ?>';
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
<!-- Template JS File -->
<script src="/assets/admin/js/scripts.js"></script>


<script src="/assets/admin/js/admin.min.js"></script>

<?php echo $__env->yieldPushContent('styles_bottom'); ?>
<?php echo $__env->yieldPushContent('scripts_bottom'); ?>

<script>
    (function () {
        "use strict";

        <?php if(session()->has('toast')): ?>
        showToast('<?php echo e(session()->get('toast')['status']); ?>', '<?php echo e(session()->get('toast')['title'] ?? ''); ?>', '<?php echo e(session()->get('toast')['msg'] ?? ''); ?>')
        <?php endif; ?>
    })(jQuery);


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
    var generatedContentLang = '<?php echo e(trans('update.generated_content')); ?>';
    var copyLang = '<?php echo e(trans('public.copy')); ?>';
    var doneLang = '<?php echo e(trans('public.done')); ?>';
    var priceInvalidHintLang = '<?php echo e(trans('update.price_invalid_hint')); ?>';
</script>

<script src="/assets/admin/js/custom.js?v=<?php echo e(time()); ?>"></script>
<script src="/assets/admin/js/parts/ai-content-generator.min.js"></script>

<script>
    <?php echo !empty($themeCustomCssAndJs['js']) ? $themeCustomCssAndJs['js'] : ''; ?>

</script>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>