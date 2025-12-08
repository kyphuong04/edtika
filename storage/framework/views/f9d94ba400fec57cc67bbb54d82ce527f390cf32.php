<div class="bundle-grid-card position-relative">
    <div class="bundle-grid-card__image bg-gray-200 ">
        <img src="<?php echo e($bundle->getImage()); ?>" class="img-cover" alt="<?php echo e($bundle->title); ?>">
    </div>

    <div class="bundle-grid-card__content d-flex flex-column  p-16 bg-white">

        <div class="d-flex align-items-start justify-content-between">
            <div class="">
                <a href="<?php echo e($bundle->getUrl()); ?>" target="_blank">
                    <h3 class="bundle-grid-card__title font-14 text-dark"><?php echo e($bundle->title); ?></h3>
                </a>

                <?php echo $__env->make("design_1.web.components.rate", [
                    'rate' => round($bundle->getRate(),1),
                    'rateCount' => $bundle->reviews()->where('status', 'active')->count(),
                    'rateClassName' => 'mt-8',
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="actions-dropdown position-relative ml-16">
                <div class="webinar-card-actions-btn d-flex-center size-40 rounded-8 bg-gray-100">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                    <ul class="my-8">

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_bundles_create')): ?>
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/bundles/<?php echo e($bundle->id); ?>/edit" class=""><?php echo e(trans('public.edit')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_bundles_courses')): ?>
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/bundles/<?php echo e($bundle->id); ?>/courses" class=""><?php echo e(trans('product.courses')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if($authUser->id == $bundle->teacher_id or $authUser->id == $bundle->creator_id): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_bundles_export_students_list')): ?>
                                <li class="actions-dropdown__dropdown-menu-item">
                                    <a href="/panel/bundles/<?php echo e($bundle->id); ?>/export-students-list" class=""><?php echo e(trans('public.export_list')); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if($bundle->creator_id == $authUser->id): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_bundles_delete')): ?>
                                <li class="actions-dropdown__dropdown-menu-item">
                                    <?php echo $__env->make('design_1.panel.includes.content_delete_btn', [
                                        'deleteContentUrl' => "/panel/bundles/{$bundle->id}/delete",
                                        'deleteContentClassName' => ' text-danger',
                                        'deleteContentItem' => $bundle,
                                        'deleteContentItemType' => "bundle",
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </div>

        <div class="d-grid grid-columns-2 gap-16 my-16 p-16 rounded-8 border-gray-200 mb-16">
            <div class="d-flex align-items-center font-12 text-gray-500">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-teacher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <span class="ml-4 font-weight-bold"><?php echo e(count($bundle->sales)); ?></span>
                <span class="ml-4"><?php echo e(trans('public.students')); ?></span>
            </div>

            <div class="d-flex align-items-center font-12 text-gray-500">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <span class="ml-4 font-weight-bold"><?php echo e(count($bundle->bundleWebinars)); ?></span>
                <span class="ml-4"><?php echo e(trans('update.lessons')); ?></span>
            </div>

            <div class="d-flex align-items-center font-12 text-gray-500">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-moneys'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <span class="ml-4 font-weight-bold"><?php echo e(handlePrice($bundle->sales->sum('amount'))); ?></span>
                <span class="ml-4"><?php echo e(trans('panel.sales')); ?></span>
            </div>

            <div class="d-flex align-items-center font-12 text-gray-500">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-clock-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <span class="ml-4 font-weight-bold"><?php echo e(convertMinutesToHourAndMinute($bundle->getBundleDuration())); ?></span>
                <span class="ml-4"><?php echo e(trans('home.hours')); ?></span>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-auto">
            <div class="d-flex align-items-center">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-video-play'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <span class="ml-4 font-12 text-gray-500"><?php echo e(count($bundle->bundleWebinars)); ?> <?php echo e(trans('product.courses')); ?></span>
            </div>

            <div class="d-flex align-items-center font-16 font-weight-bold text-success">
                <?php echo $__env->make("design_1.web.bundles.components.price", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/bundles/my_bundles/grid_card.blade.php ENDPATH**/ ?>