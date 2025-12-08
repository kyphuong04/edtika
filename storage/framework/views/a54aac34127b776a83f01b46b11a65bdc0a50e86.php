<div class="theme-footer-1__newsletter">
    <div class="container position-relative">
        <div class="theme-footer-1__newsletter-mask"></div>

        <div class="position-relative z-index-2 bg-white p-16 rounded-24">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6">
                    <div class="">
                        <div class="d-flex align-items-center gap-4">
                            <?php if(!empty($newsletterData['title'])): ?>
                                <h4 class="font-20"><?php echo e($newsletterData['title']); ?></h4>
                            <?php endif; ?>

                            <?php if(!empty($newsletterData['emoji'])): ?>
                                <div class="theme-footer-1__newsletter-emoji">
                                    <img src="<?php echo e($newsletterData['emoji']); ?>" alt="emoji" class="img-fluid" width="20px" height="20px">
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if(!empty($newsletterData['subtitle'])): ?>
                            <div class="mt-8 font-14 text-gray-500"><?php echo e($newsletterData['subtitle']); ?></div>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="col-12 col-lg-6 mt-16 mt-lg-0 d-flex justify-content-end">
                    <div class="js-newsletter-form newsletter-form d-flex align-items-center justify-content-between p-12 rounded-12 border-gray-200">
                        <div class="form-group mb-0 flex-1">
                            <div class="d-flex align-items-center gap-8 px-12 flex-1">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-sms'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                <input type="email" name="newsletter_email" class="js-ajax-newsletter_email flex-1" placeholder="<?php echo e(trans('footer.enter_email_here')); ?>">
                            </div>

                            <div class="invalid-feedback d-block position-absolute position-bottom-0"></div>
                        </div>

                        <button type="button" class="js-submit-newsletter-btn btn btn-primary btn-lg text-white"><?php echo e(trans('footer.join')); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/theme/footers/footer_1/newsletter.blade.php ENDPATH**/ ?>