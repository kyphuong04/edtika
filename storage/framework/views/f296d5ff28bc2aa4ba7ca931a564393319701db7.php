<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("bundles_lists")); ?>">
<?php $__env->stopPush(); ?>

<?php
    $pageHeroImage = getThemePageBackgroundSettings('bundles_lists');
    $pageOverlayImage = getThemePageBackgroundSettings('bundles_lists_overlay_image');
?>

<?php $__env->startSection("content"); ?>
    <main class="pb-120">
        <section class="bundles-lists-hero position-relative">
            <div class="bundles-lists-hero__mask"></div>
            <img src="<?php echo e($pageHeroImage); ?>" class="img-cover" alt="<?php echo e(trans('update.bundles')); ?>"/>
        </section>

        
        <div class="container">
            <div class="bundles-lists-header position-relative">
                <div class="bundles-lists-header__mask"></div>
                <div class="position-relative d-flex align-items-start bg-white rounded-32 z-index-2">
                    <div class="d-flex flex-column p-32">
                        <div class="d-flex-center size-64 rounded-12 bg-warning-30">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>

                        <div class="d-flex align-items-center mt-16 text-gray-500">
                            <a href="/" class="text-gray-500"><?php echo e(getPlatformName()); ?></a>
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mx-4','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <span class=""><?php echo e(trans('update.bundles')); ?></span>
                        </div>

                        <h1 class="font-24 font-weight-bold mt-12"><?php echo e(trans('update.bundles')); ?></h1>
                        <div class="font-12 text-gray-500 mt-8"><?php echo e(trans('update.check_bundled_courses_and_use_them')); ?></div>
                    </div>

                    <?php if(!empty($pageOverlayImage)): ?>
                        <div class="bundles-lists-header__overlay-img">
                            <img src="<?php echo e($pageOverlayImage); ?>" alt="<?php echo e(trans('update.overlay_image')); ?>" class="img-cover">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <form action="<?php echo e($pageBasePath); ?>" class="js-get-view-data-by-timeout-change container mt-24" data-container-id="listsContainer">
            
            <?php echo $__env->make("design_1.web.bundles.lists.includes.top_filters", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="row">
                
                <div class="col-12 col-lg-3 mt-28">
                    <?php echo $__env->make("design_1.web.bundles.lists.includes.left_filters", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                
                <div class="col-12 col-lg-9 mt-4">
                    <div id="listsContainer" class="" data-body=".js-lists-body" data-view-data-path="<?php echo e($pageBasePath); ?>">
                        <div class="js-lists-body row">
                            <?php echo $__env->make("design_1.web.bundles.components.cards.grids.index", ['bundles' => $bundles, 'gridCardClassName' => 'col-12 col-lg-6 mt-24'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        
                        <div id="pagination" class="js-ajax-pagination" data-container-id="listsContainer" data-container-items=".js-lists-body">
                            <?php echo $pagination; ?>

                        </div>
                    </div>


                    
                    <?php if(!empty($seoSettings['bottom_seo_title']) and !empty($seoSettings['bottom_seo_content'])): ?>
                        <section class="bg-gray-100 p-16 rounded-24 border-gray-200 mt-48">
                            <h3 class="font-14"><?php echo e($seoSettings['bottom_seo_title']); ?></h3>
                            <div class="mt-12 text-gray-500"><?php echo nl2br($seoSettings['bottom_seo_content']); ?></div>
                        </section>
                    <?php endif; ?>
                </div>

            </div>
        </form>

    </main>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("range_slider_helpers")); ?>"></script>

    <script src="<?php echo e(getDesign1ScriptPath("bundles_lists")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("design_1.web.layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/bundles/lists/index.blade.php ENDPATH**/ ?>