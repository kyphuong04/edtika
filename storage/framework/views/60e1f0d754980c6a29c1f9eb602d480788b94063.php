<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    ?>

    <?php $__env->startPush('styles_top'); ?>
        <link rel="stylesheet" href="<?php echo e(getLandingComponentStylePath("featured_courses")); ?>">
    <?php $__env->stopPush(); ?>

    <div class="featured-courses-section position-relative">

        <?php if(!empty($contents['floating_background'])): ?>
            <div class="featured-courses-section__floating-background d-flex-center">
                <img src="<?php echo e($contents['floating_background']); ?>" alt="floating_background" class="">
            </div>
        <?php endif; ?>

        <div class="container">
            <div class="d-flex-center flex-column text-center">
                <?php if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title'])): ?>
                    <div class="d-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary"><?php echo e($contents['main_content']['pre_title']); ?></div>
                <?php endif; ?>

                <?php if(!empty($contents['main_content']) and !empty($contents['main_content']['title'])): ?>
                    <h2 class="mt-8 font-32 text-dark"><?php echo e($contents['main_content']['title']); ?></h2>
                <?php endif; ?>

                <?php if(!empty($contents['main_content']) and !empty($contents['main_content']['description'])): ?>
                    <p class="mt-16 font-16 text-gray-500"><?php echo e($contents['main_content']['description']); ?></p>
                <?php endif; ?>
            </div>

            <?php if(!empty($contents['featured_courses']) and is_array($contents['featured_courses'])): ?>
                <?php if(!empty($contents['enable_slider']) and $contents['enable_slider'] == "on"): ?>
                    <div class="position-relative mt-16">
                        <div class="swiper-container js-make-swiper featured-courses-swiper pb-24"
                             data-item="featured-courses-swiper"
                             data-autoplay="true"
                             data-autoplay-delay="5000"
                        >
                            <div class="swiper-wrapper py-8">
                                <?php $__currentLoopData = $contents['featured_courses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featuredRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="swiper-slide">
                                        <?php echo $__env->make('landingBuilder.front.components.featured_courses.featured_card', ['featured' => $featuredRow, 'disableOverlayImage' => true ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $contents['featured_courses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featuredRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="<?php echo e($loop->first ? 'mt-24' : ''); ?> <?php echo e((!empty($featuredRow['overlay_image'])) ? 'mb-56' : 'mb-16'); ?>">
                            <?php echo $__env->make('landingBuilder.front.components.featured_courses.featured_card', ['featured' => $featuredRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/featured_courses/index.blade.php ENDPATH**/ ?>