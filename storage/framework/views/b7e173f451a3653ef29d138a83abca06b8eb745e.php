<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $hasDiscountWebinars = $frontComponentsDataMixins->getDiscountedCoursesData();
    ?>

    <?php if($hasDiscountWebinars->isNotEmpty()): ?>
        <?php $__env->startPush('styles_top'); ?>
            <link rel="stylesheet" href="<?php echo e(getLandingComponentStylePath("discounted_courses")); ?>">
        <?php $__env->stopPush(); ?>

        <div class="container">
            <div class="discounted-courses-section position-relative " <?php if(!empty($contents['background'])): ?> style="background-image: url(<?php echo e($contents['background']); ?>)" <?php endif; ?>>

                <div class="">
                    <?php if(!empty($contents['main_content'])): ?>
                        <?php if(!empty($contents['main_content']['title'])): ?>
                            <h2 class="font-32 text-white"><?php echo e($contents['main_content']['title']); ?></h2>
                        <?php endif; ?>

                        <?php if(!empty($contents['main_content']['subtitle'])): ?>
                            <p class="mt-12 font-16 text-white opacity-70"><?php echo nl2br($contents['main_content']['subtitle']); ?></p>
                        <?php endif; ?>

                        <?php if(!empty($contents['main_content']['icon'])): ?>
                            <div class="discounted-courses-section__floating-icon d-flex-center">
                                <img src="<?php echo e($contents['main_content']['icon']); ?>" alt="icon">
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <?php echo $__env->make('design_1.web.courses.components.cards.grids.index',['courses' => $hasDiscountWebinars, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <?php if(!empty($contents['cta_section'])): ?>
                    <?php
                        $discountedCourseHasCtaContents = true;

                        if (
                            (empty($contents['cta_section']['button']) or empty($contents['cta_section']['button']['label'])) and
                            empty($contents['cta_section']['icon']) and
                            empty($contents['cta_section']['title_bold_text']) and
                            empty($contents['cta_section']['title_regular_text'])
                        ) {
                            $discountedCourseHasCtaContents = false;
                        }
                    ?>

                    <?php if($discountedCourseHasCtaContents): ?>
                        <div class="d-flex-center flex-column text-center mt-32">
                            <?php if(!empty($contents['cta_section']['button']) and !empty($contents['cta_section']['button']['label'])): ?>
                                <a href="<?php echo e(!empty($contents['cta_section']['button']['url']) ? $contents['cta_section']['button']['url'] : ''); ?>" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mb-16" data-text="<?php echo e($contents['cta_section']['button']['label']); ?>">
                                    <?php if(!empty($contents['cta_section']['button']['icon'])): ?>
                                        <?php echo e(svg("iconsax-{$contents['cta_section']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])); ?>
                                    <?php endif; ?>

                                    <span class="btn-flip-effect__text"><?php echo e($contents['cta_section']['button']['label']); ?></span>
                                </a>
                            <?php endif; ?>

                            <div class="d-flex align-items-center gap-4 text-white">
                                <?php if(!empty($contents['cta_section']['icon'])): ?>
                                    <?php echo e(svg("iconsax-{$contents['cta_section']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])); ?>
                                <?php endif; ?>

                                <?php if(!empty($contents['cta_section']['title_bold_text'])): ?>
                                    <span class="font-weight-bold font-16 opacity-70"><?php echo e($contents['cta_section']['title_bold_text']); ?></span>
                                <?php endif; ?>

                                <?php if(!empty($contents['cta_section']['title_regular_text'])): ?>
                                    <span class="opacity-70 font-16"><?php echo e($contents['cta_section']['title_regular_text']); ?></span>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/discounted_courses/index.blade.php ENDPATH**/ ?>