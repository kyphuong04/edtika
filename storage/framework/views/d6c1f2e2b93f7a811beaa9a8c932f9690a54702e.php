<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<?php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
    $userThemeColorMode = getUserThemeColorMode();
    $pageBackgroundImage = getThemePageBackgroundSettings("admin_login");
?>

<head>
    <?php echo $__env->make('design_1.web.includes.metas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title><?php echo e($pageTitle ?? ''); ?><?php echo e(!empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : ''); ?></title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">
    <link rel="stylesheet" href="/assets/admin/css/extra.min.css">

    <?php if($isRtl): ?>
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
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

    <div class="admin-auth-pages">
        <div class="admin-auth-pages__bg bg-gray-100">
            <?php if(!empty($pageBackgroundImage)): ?>
                <img src="<?php echo e($pageBackgroundImage); ?>" alt="<?php echo e(trans('update.background')); ?>" class="img-cover">
            <?php endif; ?>
        </div>

        <div class="admin-auth-pages__contents px-16 px-lg-0">
            <?php echo $__env->yieldContent('content'); ?>
        </div>

        <div class="admin-auth-pages__footer d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white px-24 py-14">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
                <div class="ml-8">
                    <h5 class="font-14">Rocket LMS</h5>
                    <a href="https://codecanyon.net/user/rocketsoft/portfolio" class="font-12 text-gray-500 mt-4">All rights reserved for Rocket Soft on Codecanyon</a>
                </div>
            </div>

            <div class="d-flex align-items-center gap-24 mt-16 mt-lg-0">
                <a href="/" class="font-12 text-gray-500 "><?php echo e(trans('navbar.home')); ?></a>
                <a href="/classes?sort=newest" class="font-12 text-gray-500 "><?php echo e(trans('product.courses')); ?></a>
                <a href="/contact" class="font-12 text-gray-500 "><?php echo e(trans('site.contact_us')); ?></a>
            </div>

        </div>
    </div>
</div>

<!-- Template JS File -->
<script>

    var themeColorsMode = <?php echo json_encode(getThemeColorsMode(), 15, 512) ?>;
</script>

<script type="text/javascript" src="/assets/design_1/js/app.min.js"></script>
<script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
<script defer src="/assets/design_1/js/parts/content_delete.min.js"></script>

<?php if(session()->has('toast')): ?>
    <script>
        (function () {
            "use strict";

            showToast('<?php echo e(session()->get('toast')['status']); ?>', '<?php echo e(session()->get('toast')['title'] ?? ''); ?>', '<?php echo e(session()->get('toast')['msg'] ?? ''); ?>')
        })(jQuery)
    </script>
<?php endif; ?>

<?php echo $__env->yieldPushContent('styles_bottom'); ?>
<?php echo $__env->yieldPushContent('scripts_bottom'); ?>

<script src="/assets/admin/js/parts/auth_pages.min.js"></script>
<script src="/assets/design_1/js/parts/general.min.js"></script>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/auth/new/layout.blade.php ENDPATH**/ ?>