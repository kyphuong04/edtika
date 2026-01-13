<div class="position-relative courses-lists-filters">
    <div class="courses-lists-filters__mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between gap-20 bg-white px-24 py-12 rounded-24 z-index-2">
        <div class="d-flex align-items-center flex-wrap gap-48">
            <?php $__currentLoopData = ['upcoming', 'free', 'discount', 'downloadable']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topFilter1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-group mb-0 d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="top_filter_<?php echo e($topFilter1); ?>" type="checkbox" name="<?php echo e($topFilter1); ?>" value="on" class="custom-control-input">
                        <label class="custom-control-label cursor-pointer" for="top_filter_<?php echo e($topFilter1); ?>"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="top_filter_<?php echo e($topFilter1); ?>"><?php echo e(trans("update.{$topFilter1}")); ?></label>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="d-flex align-items-center gap-16">

            <div class="courses-lists-sort-input form-group  mb-0">
                <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option disabled selected><?php echo e(trans('public.sort_by')); ?></option>
                    <option value=""><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = ['newest', 'expensive', 'inexpensive', 'bestsellers', 'best_rates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filterSort): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($filterSort); ?>" <?php echo e((request()->get('sort') == $filterSort) ? 'selected' : ''); ?>><?php echo e(trans("public.{$filterSort}")); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="courses-lists-card-view position-relative">
                <input type="radio" class="" name="card" id="card_grid_input" value="grid" <?php echo e((request()->get("card", 'grid') == "grid") ? 'checked' : ''); ?>>
                <label for="card_grid_input" class="position-relative d-flex-center cursor-pointer">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-category'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </label>
            </div>

            <div class="courses-lists-card-view position-relative">
                <input type="radio" class="" name="card" id="card_list_input" value="list" <?php echo e((request()->get("card") == "list") ? 'checked' : ''); ?>>
                <label for="card_list_input" class="position-relative d-flex-center cursor-pointer">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-row-vertical'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </label>
            </div>


        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/lists/includes/top_filters.blade.php ENDPATH**/ ?>