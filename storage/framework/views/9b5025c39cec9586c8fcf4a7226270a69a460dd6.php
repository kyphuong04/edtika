<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $bestSaleCourses = $frontComponentsDataMixins->getBestSellingCoursesData();
    ?>

    <?php if($bestSaleCourses->isNotEmpty()): ?>
        <?php $__env->startPush('styles_top'); ?>
            <link rel="stylesheet" href="<?php echo e(getLandingComponentStylePath("best_selling_courses")); ?>">
        <?php $__env->stopPush(); ?>

        <div class="container">
            <div class="best-selling-courses-section position-relative" <?php if(!empty($contents['background'])): ?> style="background-image: url(<?php echo e($contents['background']); ?>)" <?php endif; ?>>

                <?php if(!empty($contents['main_content']['icon'])): ?>
                    <div class="best-selling-courses-section__floating-icon d-flex-center">
                        <img src="<?php echo e($contents['main_content']['icon']); ?>" alt="icon">
                    </div>
                <?php endif; ?>


                <div class="row h-100">
                    <div class="col-12 col-md-6 col-lg-3 position-relative h-100 pt-0 pt-lg-48">
                        <?php if(!empty($contents['main_content'])): ?>
                            <?php if(!empty($contents['main_content']['title'])): ?>
                                <h2 class="font-32 text-white mr-8"><?php echo e($contents['main_content']['title']); ?></h2>
                            <?php endif; ?>

                            <?php if(!empty($contents['main_content']['subtitle'])): ?>
                                <p class="mt-20 text-white opacity-70 mr-8 font-16"><?php echo nl2br($contents['main_content']['subtitle']); ?></p>
                            <?php endif; ?>

                            <a href="/classes?sort=bestsellers" target="_blank" class="btn-flip-effect btn-flip-effect__no-side d-inline-flex align-items-center font-16 gap-8 font-weight-bold text-white mt-16" data-text="<?php echo e(trans('update.view_more')); ?>">
                                <span class="btn-flip-effect__text"><?php echo e(trans('update.view_more')); ?></span>
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    
                    <?php echo $__env->make('design_1.web.courses.components.cards.grids.index',['courses' => $bestSaleCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24 "], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/best_selling_courses/index.blade.php ENDPATH**/ ?>