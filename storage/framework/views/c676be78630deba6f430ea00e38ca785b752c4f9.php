<a href="<?php echo e($bundle->getUrl()); ?>" class="text-decoration-none d-block">
<div class="bundle-card position-relative">
        <div class="bundle-card__image bg-gray-200 rounded-16">
            <img src="<?php echo e($bundle->getImage()); ?>" class="img-cover rounded-16" alt="<?php echo e($bundle->title); ?>">
        </div>

    <div class="bundle-card__content d-flex flex-column rounded-16 p-16 bg-white">
            <h3 class="bundle-card__title font-16 text-dark"><?php echo e($bundle->title); ?></h3>

            <div>
                <a href="<?php echo e($bundle->getUrl()); ?>" class="text-decoration-none">
        <?php echo $__env->make("design_1.web.components.rate", [
            'rate' => round($bundle->getRate(),1),
            'rateCount' => $bundle->getRateCount(),
            'rateClassName' => 'mt-8',
            'rateCountFont' => 'font-12',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </a>
            </div>

            <div class="d-flex align-items-center mt-28 mb-20" onclick="event.stopPropagation()">
                <a href="<?php echo e($bundle->teacher->getProfileUrl()); ?>" target="_blank" class="size-32 rounded-circle" onclick="event.stopPropagation()">
                    <img src="<?php echo e($bundle->teacher->getAvatar(32)); ?>" class="img-cover rounded-circle" alt="<?php echo e($bundle->teacher->full_name); ?>">
                </a>

            <div class="d-flex flex-column ml-4">
                    <a href="<?php echo e($bundle->teacher->getProfileUrl()); ?>" target="_blank" class="font-14 font-weight-bold text-dark" onclick="event.stopPropagation()"><?php echo e($bundle->teacher->full_name); ?></a>

                <?php if(!empty($bundle->category)): ?>
                    <div class="d-inline-flex align-items-center gap-4 mt-2 font-14 text-gray-500">
                        <span class=""><?php echo e(trans('public.in')); ?></span>
                            <a href="<?php echo e($bundle->category->getUrl()); ?>" target="_blank" class="font-12 text-gray-500 text-ellipsis" onclick="event.stopPropagation()"><?php echo e($bundle->category->title); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-auto">
            <div class="d-flex align-items-center">
                    <a href="<?php echo e($bundle->getUrl()); ?>" class="d-flex align-items-center text-decoration-none" style="color: inherit;">
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
                <span class="ml-4 font-14 text-gray-500"><?php echo e(count($bundle->bundleWebinars)); ?> <?php echo e(trans('product.courses')); ?></span>
                    </a>
            </div>

            <div class="d-flex align-items-center font-16 font-weight-bold text-primary">
                    <a href="<?php echo e($bundle->getUrl()); ?>" class="text-decoration-none text-primary">
                <?php echo $__env->make("design_1.web.bundles.components.price", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</a>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/bundles/components/cards/grids/grid_card_1.blade.php ENDPATH**/ ?>