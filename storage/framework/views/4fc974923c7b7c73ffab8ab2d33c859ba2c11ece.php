<div class="course-special-offer-card ">
    <div class="position-relative w-100 h-100">
        <div class="course-special-offer-card__mask"></div>
        <div class="position-relative d-flex align-items-center justify-content-between bg-white p-16 pl-12 rounded-24 w-100 h-100 z-index-2">
            <div class="d-flex align-items-center">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-receipt-disscount'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <div class="ml-8">
                    <span class="d-block font-24 font-weight-bold text-dark"><?php echo e($activeSpecialOffer->percent); ?>%</span>
                    <span class="d-block font-14 text-gray-500"><?php echo e(trans('panel.special_offer')); ?></span>
                </div>
            </div>

            <?php
                $remainingTimes = $activeSpecialOffer->getRemainingTimes()
            ?>

            <div id="offerCountDown" class="time-counter-down d-flex flex-column justify-content-center p-12 rounded-8 border-gray-200"
                 data-day="<?php echo e($remainingTimes['day']); ?>"
                 data-hour="<?php echo e($remainingTimes['hour']); ?>"
                 data-minute="<?php echo e($remainingTimes['minute']); ?>"
                 data-second="<?php echo e($remainingTimes['second']); ?>">

                <div class="d-flex align-items-center font-14 font-weight-bold w-100">
                    <span class="days">0</span>
                    <span class="mx-4">:</span>
                    <span class="hours">0</span>
                    <span class="mx-4">:</span>
                    <span class="minutes">0</span>
                    <span class="mx-4">:</span>
                    <span class="seconds">0</span>
                </div>

                <div class="d-flex align-items-center font-8 mt-4 text-gray-500 w-100">
                    <span class="mr-8"><?php echo e(trans('public.day')); ?></span>
                    <span class="pl-4 mr-8"><?php echo e(trans('update.hr')); ?></span>
                    <span class="pl-4 mr-8"><?php echo e(trans('public.min')); ?></span>
                    <span class="pl-4"><?php echo e(trans('public.sec')); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/includes/special_offer.blade.php ENDPATH**/ ?>