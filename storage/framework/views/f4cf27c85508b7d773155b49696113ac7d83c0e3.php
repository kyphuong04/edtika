<li data-id="<?php echo e(!empty($faq) ? $faq->id :''); ?>" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="faq_<?php echo e(!empty($faq) ? $faq->id :'record'); ?>">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapseFaq<?php echo e(!empty($faq) ? $faq->id :'record'); ?>" data-parent="#faqsAccordion" role="button" data-toggle="collapse">
            <span><?php echo e(!empty($faq) ? $faq->title : trans('update.new_faq')); ?></span>
        </div>

        <?php if(!empty($faq)): ?>
            <div class="d-flex align-items-center">
                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-3'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/faqs/<?php echo e($faq->id); ?>/delete" class="delete-action text-danger"><?php echo e(trans('public.delete')); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseFaq<?php echo e(!empty($faq) ? $faq->id :'record'); ?>" data-parent="#faqsAccordion" role="button" data-toggle="collapse">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </span>
            </div>
        <?php endif; ?>

    </div>

    <div id="collapseFaq<?php echo e(!empty($faq) ? $faq->id :'record'); ?>" class="accordion__collapse <?php echo e(empty($faq) ? 'show' : ''); ?>" role="tabpanel">
        <div class="js-content-form js-faq-form mt-20" data-action="/panel/faqs/<?php echo e(!empty($faq) ? $faq->id . '/update' : 'store'); ?>">
            <input type="hidden" name="ajax[<?php echo e(!empty($faq) ? $faq->id : 'new'); ?>][bundle_id]" value="<?php echo e(!empty($bundle) ? $bundle->id :''); ?>">

            <div class="row">
                <div class="col-12 col-lg-12">

                    <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($faq) ? $faq : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-webinar-content-locale',
                        'extraData' => "data-bundle-id='".(!empty($bundle) ? $bundle->id : '')."'  data-id='".(!empty($faq) ? $faq->id : '')."'  data-relation='faqs' data-fields='title,answer'"
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('update.question')); ?></label>

                        <span class="has-translation bg-gray-300 rounded-8 p-8"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-translate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>

                        <input type="text" name="ajax[<?php echo e(!empty($faq) ? $faq->id : 'new'); ?>][title]" class="js-ajax-title form-control" value="<?php echo e(!empty($faq) ? $faq->title : ''); ?>"/>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('public.answer')); ?></label>

                        <span class="has-translation bg-gray-300 rounded-8 p-8"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-translate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>

                        <textarea name="ajax[<?php echo e(!empty($faq) ? $faq->id : 'new'); ?>][answer]" class="js-ajax-answer form-control" rows="6"><?php echo e(!empty($faq) ? $faq->answer : ''); ?></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="mt-30 d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary"><?php echo e(trans('public.save')); ?></button>

                <?php if(!empty($faq)): ?>
                    <a href="/panel/faqs/<?php echo e($faq->id); ?>/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion"><?php echo e(trans('delete')); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</li>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/bundles/create/includes/accordions/faq.blade.php ENDPATH**/ ?>