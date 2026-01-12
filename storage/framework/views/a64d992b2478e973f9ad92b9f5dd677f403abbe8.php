<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("courses_lists")); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
    <main class="pb-120">

        <?php
            $pageHeroImage = !empty($category->cover_image) ? $category->cover_image : getThemePageBackgroundSettings('categories');
        ?>

        <section class="courses-lists-hero position-relative">
            <div class="courses-lists-hero__mask"></div>
            <img src="<?php echo e($pageHeroImage); ?>" class="img-cover" alt="<?php echo e(trans('update.search_categories')); ?>"/>
        </section>


        
        <div class="container">
            <div class="courses-lists-header position-relative">
                <div class="courses-lists-header__mask"></div>
                <div class="position-relative d-flex align-items-start bg-white rounded-32 z-index-2">
                    <div class="d-flex flex-column p-32">
                        <div class="d-flex-center size-64 rounded-12 " style="background-color: <?php echo e($category->icon2_box_color); ?>">
                            <img src="<?php echo e($category->icon2); ?>" alt="<?php echo e($category->title); ?>" class="img-fluid" width="32px" height="32px">
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
                            <span class=""><?php echo e(trans('update.courses')); ?></span>
                        </div>

                        <h1 class="font-24 font-weight-bold mt-12"><?php echo e($category->title); ?></h1>
                        <div class="font-12 text-gray-500 mt-8"><?php echo e($category->subtitle); ?></div>
                    </div>

                    <div class="courses-lists-header__overlay-img">
                        <img src="<?php echo e($category->overlay_image); ?>" alt="<?php echo e($category->title); ?>" class="img-cover">
                    </div>
                </div>
            </div>
        </div>

        
        <?php echo $__env->make("design_1.web.courses.lists.includes.featured_courses", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <form action="<?php echo e($pageBasePath); ?>" class="js-get-view-data-by-timeout-change container mt-24" data-container-id="listsContainer">
            
            <?php echo $__env->make("design_1.web.courses.lists.includes.top_filters", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="row">
                
                <div class="col-12 col-lg-3 mt-28">
                    <?php echo $__env->make("design_1.web.courses.lists.includes.left_filters", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                
                <div class="col-12 col-lg-9 mt-4">
                    <div id="listsContainer" class="" data-body=".js-lists-body" data-view-data-path="<?php echo e($pageBasePath); ?>">
                        <div class="js-lists-body row">
                            <?php if(request()->get('card') == "list"): ?>
                                <?php echo $__env->make('design_1.web.courses.components.cards.rows.index',['courses' => $courses, 'rowCardClassName' => "col-12 mt-24"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php else: ?>
                                <?php echo $__env->make('design_1.web.courses.components.cards.grids.index',['courses' => $courses, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-24"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php endif; ?>
                        </div>

                        
                        <div id="pagination" class="js-ajax-pagination" data-container-id="listsContainer" data-container-items=".js-lists-body">
                            <?php echo $pagination; ?>

                        </div>
                    </div>


                    
                    <?php if(!empty($category->bottom_seo_title) and !empty($category->bottom_seo_content)): ?>
                        <section class="bg-gray-100 p-16 rounded-24 border-gray-200 mt-48">
                            <h3 class="font-14"><?php echo e($category->bottom_seo_title); ?></h3>
                            <div class="mt-12 text-gray-500"><?php echo nl2br($category->bottom_seo_content); ?></div>
                        </section>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </main>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("swiper_slider")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>

    <script src="<?php echo e(getDesign1ScriptPath("range_slider_helpers")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("courses_lists")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("design_1.web.layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/lists/with_category.blade.php ENDPATH**/ ?>