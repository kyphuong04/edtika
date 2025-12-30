<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $freeCoursesBackgroundColor = "secondary";
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        if (!empty($contents['background_color'])) {
            $freeCoursesBackgroundColor = $contents['background_color'];
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $freeWebinars = $frontComponentsDataMixins->getFreeCoursesData();
    ?>

    <?php if($freeWebinars->isNotEmpty()): ?>
        <?php $__env->startPush('styles_top'); ?>
            <link rel="stylesheet" href="<?php echo e(getLandingComponentStylePath("free_courses")); ?>">
        <?php $__env->stopPush(); ?>

        <div class="free-courses-section position-relative" style="background-color: var(<?php echo e("--".$freeCoursesBackgroundColor); ?>); <?php echo e((!empty($contents['background']) ? "background-image: url({$contents['background']}); " : '')); ?>"v>
            <div class="container py-40 py-lg-80">
                <div class="d-flex-center flex-column text-center">
                    <?php if(!empty($contents['main_content'])): ?>
                        <?php if(!empty($contents['main_content']['title'])): ?>
                            <h2 class="font-32 text-white"><?php echo e($contents['main_content']['title']); ?></h2>
                        <?php endif; ?>

                        <?php if(!empty($contents['main_content']['subtitle'])): ?>
                            <p class="mt-12 font-16 text-white opacity-70"><?php echo nl2br($contents['main_content']['subtitle']); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <?php echo $__env->make('design_1.web.courses.components.cards.grids.index',['courses' => $freeWebinars, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-28"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <?php if(!empty($contents['cta_section'])): ?>
                    <?php
                        $freeCourseHasCtaContents = true;

                        if (
                            empty($contents['cta_section']['title']) and
                            empty($contents['cta_section']['description']) and
                            empty($contents['cta_section']['link_title']) and
                            empty($contents['cta_section']['icon'])
                        ) {
                            $freeCourseHasCtaContents = false;
                        }
                    ?>

                    <?php if($freeCourseHasCtaContents): ?>
                        <div class="row justify-content-center mt-48 mt-lg-64">
                            <div class="col-12 col-md-8 col-lg-6">
                                <div class="d-flex align-items-start justify-content-between gap-40 gap-lg-80">
                                    <div class="">
                                        <?php if(!empty($contents['cta_section']['title'])): ?>
                                            <h3 class="font-24 text-white"><?php echo e($contents['cta_section']['title']); ?></h3>
                                        <?php endif; ?>

                                        <?php if(!empty($contents['cta_section']['description'])): ?>
                                            <p class="text-white font-16 opacity-70 mt-8"><?php echo e($contents['cta_section']['description']); ?></p>
                                        <?php endif; ?>

                                        <?php if(!empty($contents['cta_section']['link_title'])): ?>
                                            <a href="<?php echo e(!empty($contents['cta_section']['url']) ? $contents['cta_section']['url'] : ''); ?>" class="font-16 btn-flip-effect btn-flip-effect__right-0 d-inline-flex align-items-center mt-12 font-weight-bold text-white" data-text="<?php echo e($contents['cta_section']['link_title']); ?>">
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
                                                <span class="btn-flip-effect__text ml-4"><?php echo e($contents['cta_section']['link_title']); ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(!empty($contents['cta_section']['icon'])): ?>
                                        <div class="free-courses-section__cta-icon d-flex-center">
                                            <img src="<?php echo e($contents['cta_section']['icon']); ?>" alt="icon">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/free_courses/index.blade.php ENDPATH**/ ?>