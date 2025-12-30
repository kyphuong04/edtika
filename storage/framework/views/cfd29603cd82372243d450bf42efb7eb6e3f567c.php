<?php if(!empty($themeFooterData['contents'])): ?>
    <?php
        $themeFooterContent = $themeFooterData['contents'];
        $themeFooterBackground = null;
        $themeFooterDarkBackground = null;
        $themeFooterBackgroundColor = "secondary";

        if (!empty($themeFooterData['contents']['dark_mode_background'])) {
            $themeFooterDarkBackground = $themeFooterData['contents']['dark_mode_background'];
        }

        if (!empty($themeFooterData['contents']['background'])) {
            $themeFooterBackground = $themeFooterData['contents']['background'];
        }

        if (!empty($themeFooterData['contents']['background_color'])) {
            $themeFooterBackgroundColor = $themeFooterData['contents']['background_color'];
        }
    ?>

    <div class="theme-footer-1 has-newsletter position-relative">
        <div class="theme-footer-1__section position-relative">
            <div class="theme-footer-1__section-bg-wrapper light-only" style="background-color: var(<?php echo e("--".$themeFooterBackgroundColor); ?>); <?php echo e((!empty($themeFooterBackground) ? "background-image: url({$themeFooterBackground}); " : '')); ?>"></div>
            <div class="theme-footer-1__section-bg-wrapper dark-only" style="background-color: var(<?php echo e("--".$themeFooterBackgroundColor); ?>); <?php echo e((!empty($themeFooterDarkBackground) ? "background-image: url({$themeFooterDarkBackground}); " : '')); ?>"></div>


            
            <?php if(!empty($themeFooterContent['newsletter']) and $themeFooterContent['newsletter']['enable'] == "on"): ?>
                <?php echo $__env->make('design_1.web.theme.footers.footer_1.newsletter', ['newsletterData' => $themeFooterContent['newsletter']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <div class="position-relative z-index-2">

                <div class="container position-relative">
                    <div class="row">
                        <div class="col-12 col-lg-5">
                            <?php if(!empty($themeFooterContent['cta'])): ?>
                                <div class="d-inline-flex-center gap-8 border-2 border-white rounded-32 bg-white-10 text-white px-16 py-12">
                                    <?php if(!empty($themeFooterContent['cta']['emoji'])): ?>
                                        <div class="size-24">
                                            <img src="<?php echo e($themeFooterContent['cta']['emoji']); ?>" alt="footer cta btn icon" class="img-fluid" width="24px" height="24px">
                                        </div>
                                    <?php endif; ?>

                                    <?php if(!empty($themeFooterContent['cta']['pre_title'])): ?>
                                        <span class=""><?php echo e($themeFooterContent['cta']['pre_title']); ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if(!empty($themeFooterContent['cta']['title'])): ?>
                                    <h3 class="mt-16 font-44 text-white mr-0 mr-lg-48"><?php echo e($themeFooterContent['cta']['title']); ?></h3>
                                <?php endif; ?>

                                <?php if(!empty($themeFooterContent['cta']['button']) and !empty($themeFooterContent['cta']['button']['label'])): ?>
                                    <a href="<?php echo e((!empty($themeFooterContent['cta']['button']['url'])) ? $themeFooterContent['cta']['button']['url'] : ''); ?>" class="btn-flip-effect btn btn-xlg btn-primary gap-8 mt-32" data-text="<?php echo e($themeFooterContent['cta']['button']['label']); ?>">
                                        <?php if(!empty($themeFooterContent['cta']['button']['icon'])): ?>
                                            <?php echo e(svg("iconsax-{$themeFooterContent['cta']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])); ?>
                                        <?php endif; ?>

                                        <span class="btn-flip-effect__text"><?php echo e($themeFooterContent['cta']['button']['label']); ?></span>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div class="col-6 col-lg-2 mt-32 mt-lg-0">
                            <?php if(!empty($themeFooterContent['links_1_section_title'])): ?>
                                <h4 class="font-16 text-white"><?php echo e($themeFooterContent['links_1_section_title']); ?></h4>
                            <?php endif; ?>

                            <?php if(!empty($themeFooterContent['specific_links']) and is_array($themeFooterContent['specific_links'])): ?>
                                <?php $__currentLoopData = $themeFooterContent['specific_links']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specificLink1Data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!empty($specificLink1Data['title']) and !empty($specificLink1Data['url'])): ?>
                                        <a href="<?php echo e($specificLink1Data['url']); ?>" target="_blank" class="btn-flip-effect btn-flip-effect__slow-effect btn-flip-effect__left-0 d-block font-16 text-white opacity-70 <?php echo e($loop->first ? 'mt-16' : 'mt-12'); ?>" data-text="<?php echo e($specificLink1Data['title']); ?>">
                                            <span class="btn-flip-effect__text"><?php echo e($specificLink1Data['title']); ?></span>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>

                        <div class="col-6 col-lg-2 mt-32 mt-lg-0">
                            <?php if(!empty($themeFooterContent['links_2_section_title'])): ?>
                                <h4 class="font-16 text-white"><?php echo e($themeFooterContent['links_2_section_title']); ?></h4>
                            <?php endif; ?>

                            <?php if(!empty($themeFooterContent['specific_links_2']) and is_array($themeFooterContent['specific_links_2'])): ?>
                                <?php $__currentLoopData = $themeFooterContent['specific_links_2']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specificLink2Data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!empty($specificLink2Data['title']) and !empty($specificLink2Data['url'])): ?>
                                        <a href="<?php echo e($specificLink2Data['url']); ?>" target="_blank" class="btn-flip-effect btn-flip-effect__slow-effect btn-flip-effect__left-0 d-block font-16 text-white opacity-70 <?php echo e($loop->first ? 'mt-16' : 'mt-12'); ?>" data-text="<?php echo e($specificLink2Data['title']); ?>">
                                            <span class="btn-flip-effect__text"><?php echo e($specificLink2Data['title']); ?></span>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-lg-3 mt-32 mt-lg-0">
                            <?php if(!empty($themeFooterContent['contact'])): ?>
                                <?php if(!empty($themeFooterContent['contact']['section_title'])): ?>
                                    <h4 class="font-16 text-white"><?php echo e($themeFooterContent['contact']['section_title']); ?></h4>
                                <?php endif; ?>

                                <?php if(!empty($themeFooterContent['contact']['address'])): ?>
                                    <div class="d-flex align-items-start gap-8 mt-20">
                                        <div class="size-24">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-location'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        </div>
                                        <span class="font-16 text-white opacity-70"><?php echo e($themeFooterContent['contact']['address']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($themeFooterContent['contact']['phone'])): ?>
                                    <div class="d-flex align-items-start gap-8 mt-16">
                                        <div class="size-24">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-call-calling'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        </div>
                                        <span class="font-16 text-white opacity-70"><?php echo e($themeFooterContent['contact']['phone']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($themeFooterContent['contact']['mobile'])): ?>
                                    <div class="d-flex align-items-start gap-8 mt-16">
                                        <div class="size-24">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-mobile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        </div>
                                        <span class="font-16 text-white opacity-70"><?php echo e($themeFooterContent['contact']['mobile']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($themeFooterContent['contact']['email'])): ?>
                                    <div class="d-flex align-items-start gap-8 mt-16">
                                        <div class="size-24">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-sms'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        </div>
                                        <span class="font-16 text-white opacity-70"><?php echo e($themeFooterContent['contact']['email']); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>


                    </div>
                </div>

                <div class="theme-footer-1__bottom-section-divider"></div>

                <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between py-24 px-16 gap-16">
                    <?php if(!empty($themeFooterContent['copyright_text'])): ?>
                        <div class="font-14 text-white opacity-70"><?php echo e($themeFooterContent['copyright_text']); ?></div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center justify-content-center gap-16 gap-lg-24">
                        <?php if(!empty($themeFooterContent['social_media']) and is_array($themeFooterContent['social_media'])): ?>
                            <?php
                                $appSocials = getSocials();
                                if (!empty($appSocials) and count($appSocials)) {
                                    $appSocials = collect($appSocials)->sortBy('order')->toArray();
                                }
                            ?>

                            <?php $__currentLoopData = $appSocials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $socialKey => $socialValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(in_array($socialKey, $themeFooterContent['social_media'])): ?>
                                    <?php if(!empty($socialValue['title']) and !empty($socialValue['link']) and !empty($socialValue['image'])): ?>
                                        <a href="<?php echo e($socialValue['link']); ?>" target="_blank" rel="nofollow" title="<?php echo e($socialValue['title']); ?>" class="d-flex-center size-24">
                                            <img src="<?php echo e($socialValue['image']); ?>" alt="<?php echo e($socialValue['title']); ?>" class="img-cover">
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/theme/footers/footer_1/index.blade.php ENDPATH**/ ?>