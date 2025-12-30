<li data-id="<?php echo e(!empty($bundleWebinar) ? $bundleWebinar->id :''); ?>" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="bundleWebinar_<?php echo e(!empty($bundleWebinar) ? $bundleWebinar->id :'record'); ?>">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapseBundleWebinar<?php echo e(!empty($bundleWebinar) ? $bundleWebinar->id :'record'); ?>" data-parent="#bundleWebinarsAccordion" role="button" data-toggle="collapse">
            <span><?php echo e((!empty($bundleWebinar) and !empty($bundleWebinar->webinar)) ? $bundleWebinar->webinar->title : trans('update.add_new_course')); ?></span>
        </div>

        <?php if(!empty($bundleWebinar)): ?>
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
                                <a href="/panel/bundle-webinars/<?php echo e($bundleWebinar->id); ?>/delete" class="delete-action text-danger"><?php echo e(trans('public.delete')); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseBundleWebinar<?php echo e(!empty($bundleWebinar) ? $bundleWebinar->id :'record'); ?>" data-parent="#bundleWebinarsAccordion" role="button" data-toggle="collapse">
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

    <div id="collapseBundleWebinar<?php echo e(!empty($bundleWebinar) ? $bundleWebinar->id :'record'); ?>" class="accordion__collapse <?php echo e(empty($bundleWebinar) ? 'show' : ''); ?>" role="tabpanel">
        <div class="js-content-form js-bundle-webinars-form" data-action="/panel/bundle-webinars/<?php echo e(!empty($bundleWebinar) ? $bundleWebinar->id . '/update' : 'store'); ?>">
            <input type="hidden" name="ajax[<?php echo e(!empty($bundleWebinar) ? $bundleWebinar->id : 'new'); ?>][bundle_id]" value="<?php echo e(!empty($bundle) ? $bundle->id :''); ?>">

            <div class="form-group mt-20">
                <label class="form-group-label"><?php echo e(trans('panel.select_course')); ?></label>

                <select name="ajax[<?php echo e(!empty($bundleWebinar) ? $bundleWebinar->id : 'new'); ?>][webinar_id]" class="js-ajax-webinar_id form-control select2" data-allow-clear="false" data-placeholder="<?php echo e(trans('update.search_courses')); ?>">
                    <option value=""><?php echo e(trans('panel.select_course')); ?></option>

                    <?php if(!empty($webinars)): ?>
                        <?php $__currentLoopData = $webinars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webinar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($webinar->id); ?>" <?php echo e((!empty($bundleWebinar) and $bundleWebinar->webinar_id == $webinar->id) ? 'selected' : ''); ?>><?php echo e($webinar->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
                <div class="invalid-feedback"></div>

                <div class="mt-8">
                    <p class="font-12 text-gray-500">- <?php echo e(trans('update.bundle_webinars_required_hint')); ?></p>
                </div>
            </div>


            <div class="mt-30 d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary"><?php echo e(trans('public.save')); ?></button>

                <?php if(!empty($bundleWebinar)): ?>
                    <a href="/panel/bundle-webinars/<?php echo e($bundleWebinar->id); ?>/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion"><?php echo e(trans('delete')); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</li>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/bundles/create/includes/accordions/courses.blade.php ENDPATH**/ ?>