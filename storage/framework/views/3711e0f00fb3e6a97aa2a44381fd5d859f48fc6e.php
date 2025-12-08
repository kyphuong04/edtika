<li data-id="<?php echo e(!empty($extraDescription) ? $extraDescription->id :''); ?>" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="d-flex align-items-center justify-content-between " role="tab" id="<?php echo e($extraDescriptionType); ?>_<?php echo e(!empty($extraDescription) ? $extraDescription->id :'record'); ?>">
        <div class="font-weight-bold text-dark-blue" href="#collapseExtraDescription<?php echo e(!empty($extraDescription) ? $extraDescription->id :'record'); ?>" aria-controls="collapseExtraDescription<?php echo e(!empty($extraDescription) ? $extraDescription->id :'record'); ?>" data-parent="#<?php echo e($extraDescriptionParentAccordion); ?>" role="button" data-toggle="collapse" aria-expanded="true">
            <?php if(!empty($extraDescription) and !empty($extraDescription->value)): ?>
                <span><?php echo e(truncate($extraDescription->value, 45)); ?></span>
            <?php else: ?>
                <span><?php echo e(trans('update.new_item')); ?></span>
            <?php endif; ?>
        </div>

        <?php if(!empty($extraDescription)): ?>
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
                                <a href="/panel/webinar-extra-description/<?php echo e($extraDescription->id); ?>/delete" class="delete-action text-danger"><?php echo e(trans('public.delete')); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseExtraDescription<?php echo e(!empty($extraDescription) ? $extraDescription->id :'record'); ?>" data-parent="#<?php echo e($extraDescriptionParentAccordion); ?>" role="button" data-toggle="collapse">
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

    <div id="collapseExtraDescription<?php echo e(!empty($extraDescription) ? $extraDescription->id :'record'); ?>" aria-labelledby="<?php echo e($extraDescriptionType); ?>_<?php echo e(!empty($extraDescription) ? $extraDescription->id :'record'); ?>" class=" accordion__collapse <?php if(empty($extraDescription)): ?> show <?php endif; ?>" role="tabpanel">
        <div class="js-content-form extra_description-form mt-16" data-action="/panel/webinar-extra-description/<?php echo e(!empty($extraDescription) ? $extraDescription->id . '/update' : 'store'); ?>">
            <input type="hidden" name="ajax[<?php echo e(!empty($extraDescription) ? $extraDescription->id : 'new'); ?>][webinar_id]" value="<?php echo e(!empty($webinar) ? $webinar->id :''); ?>">
            <input type="hidden" name="ajax[<?php echo e(!empty($extraDescription) ? $extraDescription->id : 'new'); ?>][type]" value="<?php echo e($extraDescriptionType); ?>">

            <div class="row">
                <div class="col-12">

                    <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($extraDescription) ? $extraDescription : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-webinar-content-locale',
                        'extraData' => "data-webinar-id='".(!empty($webinar) ? $webinar->id : '')."'  data-id='".(!empty($extraDescription) ? $extraDescription->id : '')."'  data-relation='webinarExtraDescription' data-fields='value'"
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('public.title')); ?></label>
                        <input type="text" name="ajax[<?php echo e(!empty($extraDescription) ? $extraDescription->id : 'new'); ?>][value]" class="js-ajax-value form-control" value="<?php echo e(!empty($extraDescription) ? $extraDescription->value : ''); ?>"/>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
            </div>

            <div class=" d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary"><?php echo e(trans('public.save')); ?></button>
            </div>
        </div>
    </div>
</li>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/includes/accordions/extra_description.blade.php ENDPATH**/ ?>