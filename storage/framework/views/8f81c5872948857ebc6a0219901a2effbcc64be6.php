<div class="position-relative bundles-lists-filters">
    <div class="bundles-lists-filters__mask"></div>

    <div id="leftFiltersAccordion" class="position-relative bg-white py-16 rounded-24 z-index-2">

        
        <div class="accordion card-before-line card-before-line__4-12 pb-16 px-16 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersTypes" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <?php echo e(trans('public.type')); ?>

                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersTypes" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </span>
            </div>

            <div id="leftFiltersTypes" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                <?php $__currentLoopData = ['webinar','course','text_lesson']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="custom-control custom-checkbox <?php echo e($loop->first ? 'mt-16' : 'mt-12'); ?>">
                        <input type="checkbox" name="type[]" value="<?php echo e($typeOption); ?>" id="filter_type_<?php echo e($typeOption); ?>" class="custom-control-input">
                        <label class="custom-control__label cursor-pointer" for="filter_type_<?php echo e($typeOption); ?>"><?php echo e(trans('webinars.'.$typeOption)); ?></label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="accordion card-before-line card-before-line__4-12 pb-16 px-16 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersMoreOptions" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <?php echo e(trans('site.more_options')); ?>

                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersMoreOptions" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </span>
            </div>

            <?php
                $moreOptions = [
                    'supported_courses',
                    'quiz_included',
                    'certificate_included',
                    'assignment_included',
                    'course_forum_included',
                    'point_courses',
                ];
            ?>

            <div id="leftFiltersMoreOptions" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                <?php $__currentLoopData = $moreOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moreOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="custom-control custom-checkbox <?php echo e($loop->first ? 'mt-16' : 'mt-12'); ?>">
                        <input type="checkbox" name="moreOptions[]" value="<?php echo e($moreOption); ?>" id="filter_type_<?php echo e($moreOption); ?>" class="custom-control-input">
                        <label class="custom-control__label cursor-pointer" for="filter_type_<?php echo e($moreOption); ?>"><?php echo e(trans('update.'.$moreOption)); ?></label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersPrices" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <?php echo e(trans('public.price')); ?>

                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersPrices" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </span>
            </div>

            <div id="leftFiltersPrices" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">

                <div class="d-flex align-items-center mt-16">
                    <div class="form-group mb-0">
                        <input type="tel" readonly value="<?php echo e(trans('update.free')); ?>" class="js-filters-min-price form-control input-xs bg-white text-center text-gray-500">
                    </div>
                    <div class="mx-4"></div>
                    <div class="form-group mb-0">
                        <input type="tel" readonly value="<?php echo e(handlePrice($filterMaxPrice)); ?>" class="js-filters-max-price form-control input-xs bg-white text-center text-gray-500">
                    </div>
                </div>

                <div
                    class="course-list-price-range range wrunner-value-bottom no-bottom-value-note mt-8"
                    id="priceRange"
                    data-minLimit="<?php echo e(0); ?>"
                    data-maxLimit="<?php echo e($filterMaxPrice); ?>"
                    data-step="100"
                >
                    <input type="hidden" name="min_price" value="" class="js-range-input-view-data">
                    <input type="hidden" name="max_price" value="" class="js-range-input-view-data">
                </div>

            </div>
        </div>

        
        <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersInstructor" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <?php echo e(trans('public.instructor')); ?>

                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersInstructor" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </span>
            </div>

            <div id="leftFiltersInstructor" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                <div class="form-group mb-0  mt-24">
                    <label class="form-group-label"><?php echo e(trans('update.course_instructor')); ?></label>
                    <select name="instructor" class="form-control searchable-select bg-white" data-allow-clear="true" data-placeholder="<?php echo e(trans('update.search_and_select_instructor')); ?>"
                            data-api-path="/users/search"
                            data-item-column-name="full_name"
                            data-option="just_teachers"
                    >

                    </select>
                </div>
            </div>
        </div>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/bundles/lists/includes/left_filters.blade.php ENDPATH**/ ?>