<div class="instructor-finder__filters-card position-relative bg-white p-16 rounded-24 mt-28">

    <?php
        $meetingTypes = request()->get('meeting_type', []);
        $selectedPopulations = request()->get('population', []);

        if (!is_array($meetingTypes)) {
            $meetingTypes = [];
        }

        if (!is_array($selectedPopulations)) {
            $selectedPopulations = [];
        }
    ?>

    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersMeetingType" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php echo e(trans('update.meeting_type')); ?>

            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersMeetingType" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </span>
        </div>

        <div id="sidebarFiltersMeetingType" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            <?php $__currentLoopData = ['in_person', 'online']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meetingType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="custom-control custom-checkbox <?php echo e($loop->first ? 'mt-16' : 'mt-12'); ?>">
                    <input type="checkbox" name="meeting_type[]" value="<?php echo e($meetingType); ?>" id="meeting_type_<?php echo e($meetingType); ?>" class="custom-control-input" <?php echo e((in_array($meetingType, $meetingTypes)) ? 'checked' : ''); ?>>
                    <label class="custom-control__label cursor-pointer" for="meeting_type_<?php echo e($meetingType); ?>"><?php echo e(trans("update.{$meetingType}")); ?></label>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersPopulation" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php echo e(trans('update.population')); ?>

            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersPopulation" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </span>
        </div>

        <div id="sidebarFiltersPopulation" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            <?php $__currentLoopData = ['single', 'group']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $population): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="custom-control custom-checkbox <?php echo e($loop->first ? 'mt-16' : 'mt-12'); ?>">
                    <input type="checkbox" name="population[]" value="<?php echo e($population); ?>" id="population_<?php echo e($population); ?>" class="custom-control-input" <?php echo e((in_array($population, $selectedPopulations)) ? 'checked' : ''); ?>>
                    <label class="custom-control__label cursor-pointer" for="population_<?php echo e($population); ?>"><?php echo e(trans("update.{$population}")); ?></label>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>


    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersPrices" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php echo e(trans('price')); ?>

            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersPrices" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </span>
        </div>

        <div id="sidebarFiltersPrices" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            <div class="d-flex align-items-center mt-16">
                <div class="form-group mb-0">
                    <input type="tel" readonly value="<?php echo e($currency.removeCurrencyFromPrice(request()->get('min_price', 0))); ?>" class="js-filters-min-price form-control input-xs bg-white text-center text-gray-500">
                </div>
                <div class="mx-4"></div>
                <div class="form-group mb-0">
                    <input type="tel" readonly value="<?php echo e($currency.removeCurrencyFromPrice(request()->get('max_price', $filterMaxPrice))); ?>" class="js-filters-max-price form-control input-xs bg-white text-center text-gray-500">
                </div>
            </div>

            <div
                    class="range wrunner-value-bottom mt-16"
                    id="priceRange"
                    data-minLimit="<?php echo e(removeCurrencyFromPrice(request()->get('min_price', 0))); ?>"
                    data-maxLimit="<?php echo e(removeCurrencyFromPrice(request()->get('max_price', $filterMaxPrice))); ?>"
                    data-step="100"
            >
                <input type="hidden" name="min_price" value="<?php echo e(removeCurrencyFromPrice(request()->get('min_price'))); ?>">
                <input type="hidden" name="max_price" value="<?php echo e(removeCurrencyFromPrice(request()->get('max_price'))); ?>">
            </div>

        </div>
    </div>

    
    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersDays" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php echo e(trans('update.meeting_time')); ?>

            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersDays" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </span>
        </div>

        <?php
            $days = ['saturday', 'sunday','monday','tuesday','wednesday','thursday','friday'];

            $requestDays = request()->get('day');
            if (!is_array($requestDays)) {
                $requestDays = [$requestDays];
            }
        ?>

        <div id="sidebarFiltersDays" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="custom-control custom-checkbox <?php echo e($loop->first ? 'mt-16' : 'mt-12'); ?>">
                    <input type="checkbox" name="day[]" value="<?php echo e($day); ?>" id="day_<?php echo e($day); ?>" class="custom-control-input" <?php echo e((in_array($day, $requestDays)) ? 'checked' : ''); ?>>
                    <label class="custom-control__label cursor-pointer" for="day_<?php echo e($day); ?>"><?php echo e(trans('panel.'.$day)); ?></label>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="accordion pt-16">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersTimeRange" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php echo e(trans('update.time_range')); ?>

            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersTimeRange" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </span>
        </div>

        <div id="sidebarFiltersTimeRange" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">
            <div class="d-flex align-items-center mt-16">
                <div class="form-group mb-0">
                    <input type="tel" readonly value="<?php echo e(request()->get('min_time', 0)); ?>" class="js-filters-min-time form-control input-xs bg-white text-center text-gray-500">
                </div>
                <div class="mx-4"></div>
                <div class="form-group mb-0">
                    <input type="tel" readonly value="<?php echo e(request()->get('min_time', 23)); ?>" class="js-filters-max-time form-control input-xs bg-white text-center text-gray-500">
                </div>
            </div>

            <div
                    class="range wrunner-value-bottom mt-16"
                    id="timeRange"
                    data-minLimit="<?php echo e(request()->get('min_time', 0)); ?>"
                    data-maxLimit="<?php echo e(request()->get('max_time', 23)); ?>"
                    data-step="1"
            >
                <input type="hidden" name="min_time" value="<?php echo e(request()->get('min_time')); ?>">
                <input type="hidden" name="max_time" value="<?php echo e(request()->get('max_time')); ?>">
            </div>
        </div>
    </div>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/instructor_finder/lists/left_side/other.blade.php ENDPATH**/ ?>