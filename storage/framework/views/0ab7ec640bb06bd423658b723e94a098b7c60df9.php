<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    ?>

    <?php $__env->startPush('styles_top'); ?>
        <link rel="stylesheet" href="<?php echo e(getLandingComponentStylePath("two_columns_hero")); ?>">
    <?php $__env->stopPush(); ?>

    <div class="two-columns-hero-section " <?php if(!empty($contents['background'])): ?> style="background-image: url(<?php echo e($contents['background']); ?>)" <?php endif; ?>>
        <div class="container h-100">
            <div class="row h-100 flex-column-reverse flex-lg-row">
                <div class="col-12 col-lg-5 two-columns-hero-section__content">
                    
                    <?php if(!empty($contents['upper_cta']) and (!empty($contents['upper_cta']['badge_text']) or !empty($contents['upper_cta']['main_text']))): ?>
                        <a href="<?php echo e(!empty($contents['upper_cta']['url']) ? $contents['upper_cta']['url'] : ''); ?>" target="_blank" class="">
                            <div class="d-inline-flex align-items-center gap-8 p-8 pr-16 rounded-32 border-2 border-dark">
                                <?php if(!empty($contents['upper_cta']['badge_text'])): ?>
                                    <div class="d-flex-center gap-4 p-6 pr-10 rounded-16 bg-primary">
                                        <?php if(!empty($contents['upper_cta']['icon'])): ?>
                                            <?php echo e(svg("iconsax-{$contents['upper_cta']['icon']}", ['width' => '20px', 'height' => '20px', 'class' => "icons text-white two-columns-hero-section__upper-cta-badge-icon"])); ?>
                                        <?php endif; ?>

                                        <span class="font-14 text-white"><?php echo e($contents['upper_cta']['badge_text']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($contents['upper_cta']['main_text'])): ?>
                                    <span class="font-14 text-dark"><?php echo e($contents['upper_cta']['main_text']); ?></span>
                                <?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-dark','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </div>
                        </a>
                    <?php endif; ?>

                    
                    <?php if(!empty($contents['main_content'])): ?>
                        <h1 class="d-inline-flex flex-column font-64 mt-24">
                            <div class="d-inline-flex align-items-center gap-12 font-64">
                                <?php if(!empty($contents['main_content']['title_line_1'])): ?>
                                    <span class="text-dark"><?php echo e($contents['main_content']['title_line_1']); ?></span>
                                <?php endif; ?>

                                <?php if(!empty($contents['main_content']['highlight_words']) and is_array($contents['main_content']['highlight_words'])): ?>
                                    <?php if(count($contents['main_content']['highlight_words']) > 1): ?>
                                        <?php $__env->startPush('scripts_bottom'); ?>
                                            <script>
                                                var twoColumnsHeroHighlightWords = <?php echo json_encode(array_values($contents['main_content']['highlight_words']), 15, 512) ?>;

                                                $(document).ready(function () {
                                                    handleHighlightWords(twoColumnsHeroHighlightWords, 'js-two-columns-hero-highlight-words-card')
                                                })
                                            </script>
                                        <?php $__env->stopPush(); ?>

                                        <div
                                            class="js-two-columns-hero-highlight-words-card text-primary"
                                            data-type-speed="50"
                                            data-back-speed="25"
                                            data-delay="1500"
                                        ><?php echo e(array_values($contents['main_content']['highlight_words'])[0]); ?></div>
                                    <?php else: ?>
                                        <?php $__currentLoopData = $contents['main_content']['highlight_words']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $highlightWord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="text-primary"><?php echo e($highlightWord); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <?php if(!empty($contents['main_content']['title_line_2'])): ?>
                                <span class="mt-4 text-dark"><?php echo e($contents['main_content']['title_line_2']); ?></span>
                            <?php endif; ?>
                        </h1>

                        <?php if(!empty($contents['main_content']['description'])): ?>
                            <div class="mt-16 font-16 text-gray-500"><?php echo nl2br($contents['main_content']['description']); ?></div>
                        <?php endif; ?>

                        <?php if(!empty($contents['main_content']['primary_button']) or !empty($contents['main_content']['secondary_button'])): ?>
                            <div class="d-flex align-items-lg-center flex-column flex-lg-row mt-32 gap-16">
                                
                                <?php if(!empty($contents['main_content']['primary_button']) and !empty($contents['main_content']['primary_button']['label'])): ?>
                                    <a href="<?php echo e(!empty($contents['main_content']['primary_button']['url']) ? $contents['main_content']['primary_button']['url'] : ''); ?>" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="<?php echo e($contents['main_content']['primary_button']['label']); ?>">
                                        <?php if(!empty($contents['main_content']['primary_button']['icon'])): ?>
                                            <?php echo e(svg("iconsax-{$contents['main_content']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])); ?>
                                        <?php endif; ?>

                                        <span class="btn-flip-effect__text text-white"><?php echo e($contents['main_content']['primary_button']['label']); ?></span>
                                    </a>
                                <?php endif; ?>

                                
                                <?php if(!empty($contents['main_content']['secondary_button']) and !empty($contents['main_content']['secondary_button']['label'])): ?>
                                    <a href="<?php echo e(!empty($contents['main_content']['secondary_button']['url']) ? $contents['main_content']['secondary_button']['url'] : ''); ?>" class="btn-flip-effect btn-flip-effect__text-dark btn btn-xlg gap-8" data-text="<?php echo e($contents['main_content']['secondary_button']['label']); ?>">
                                        <?php if(!empty($contents['main_content']['secondary_button']['icon'])): ?>
                                            <?php echo e(svg("iconsax-{$contents['main_content']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])); ?>
                                        <?php endif; ?>

                                        <span class="btn-flip-effect__text text-dark"><?php echo e($contents['main_content']['secondary_button']['label']); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    
                    <?php if(!empty($contents['students_widget']) and !empty($contents['students_widget']['title'])): ?>
                        <a href="<?php echo e(!empty($contents['students_widget']['url']) ? $contents['students_widget']['url'] : ''); ?>" class="">
                            <div class="d-inline-flex align-items-center gap-8 mt-40 mt-lg-80 p-12 rounded-32 bg-gray-400-20 backdrop-filter-blur-2">
                                <?php if(!empty($contents['students_widget_avatars']) and is_array($contents['students_widget_avatars'])): ?>
                                    <div class="d-flex align-items-center overlay-avatars overlay-avatars-20">
                                        <?php $__currentLoopData = $contents['students_widget_avatars']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avatar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="overlay-avatars__item size-40 rounded-circle border-0">
                                                <img src="<?php echo e($avatar); ?>" alt="avatar" class="img-cover rounded-circle">
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="">
                                    <div class="d-flex align-items-center">
                                        <?php $__currentLoopData = [1,2,3,4,5]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-star-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <?php if(!empty($contents['students_widget']['title'])): ?>
                                        <h5 class="font-14 mt-4 text-dark mr-8"><?php echo e($contents['students_widget']['title']); ?></h5>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="col-lg-1"></div>

                <div class="col-12 col-lg-6">
                    <?php if(!empty($contents['image_content'])): ?>
                        <div class="two-columns-hero-section__images-side position-relative d-flex justify-content-end w-100 h-100 px-lg-24">

                            <?php if(!empty($contents['image_content']['type'])): ?>
                                <div class="d-flex-center two-columns-hero-section__main-img">
                                    <?php if($contents['image_content']['type'] == "lottie_json" and !empty($contents['image_content']['lottie_json'])): ?>
                                        <?php $__env->startPush('scripts_bottom'); ?>
                                            <script src="/assets/default/vendors/lottie/lottie-player.js"></script>
                                        <?php $__env->stopPush(); ?>

                                        <lottie-player src="<?php echo e($contents['image_content']['lottie_json']); ?>" background="transparent" speed="1" class="w-100 h-100" loop autoplay></lottie-player>
                                    <?php elseif($contents['image_content']['type'] == "image" and !empty($contents['image_content']['image'])): ?>
                                        <img src="<?php echo e($contents['image_content']['image']); ?>" alt="hero" class="img-cover">
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($contents['image_content']['spinning_image'])): ?>
                                <div class="d-flex-center two-columns-hero-section__spinning-img">
                                    <img src="<?php echo e($contents['image_content']['spinning_image']); ?>" alt="spinning_image" class="img-cover">
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($contents['image_content']['overlay_image'])): ?>
                                <div class="d-flex-center two-columns-hero-section__overlay-img">
                                    <img src="<?php echo e($contents['image_content']['overlay_image']); ?>" alt="overlay_image" class="img-cover">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/two_columns_hero/index.blade.php ENDPATH**/ ?>