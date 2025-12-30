<a href="/panel/financial/payout" class="d-block">
<div class="bg-white p-16 rounded-24 mt-24">
    <div class="card-with-mask position-relative">
        <div class="dashboard-current-balance-box__mask"></div>

        <div class="position-relative dashboard-current-balance-box rounded-16 z-index-2">
            <div class="d-flex flex-column pt-16 pb-24 px-16 text-white">
                <span class="font-16 font-weight-bold"><?php echo e(trans('update.current_balance')); ?></span>
                <span class="font-12 opacity-75"><?php echo e(dateTimeFormat(time(), 'j M Y H:i')); ?></span>
                <span class="mt-24 font-44 font-weight-bold"><?php echo e(!empty($authUserBalanceCharge) ? handlePrice($authUserBalanceCharge) : trans('update.no_balance')); ?></span>
            </div>

            <div class="dashboard-current-balance-box__footer p-16">
                <span class="font-12 text-white opacity-75">
                    <?php if(!empty($authUserReadyPayout)): ?>
                        <?php echo e(trans('update.amount_ready_to_payout', ['amount' => handlePrice($authUserReadyPayout)])); ?>

                    <?php else: ?>
                        <?php echo e(trans('update.no_balance')); ?>

                    <?php endif; ?>
                </span>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mt-32">
        <div class="">
            <span class="d-block font-16 font-weight-bold text-dark"><?php echo e(trans('update.wallet')); ?></span>
            <span class="d-block font-12 text-gray-500 mt-4"><?php echo e(trans('update.manage_your_balance')); ?></span>
        </div>

        <a href="/panel/financial/payout" target="_blank" class="d-flex-center size-40 rounded-circle border-gray-200 bg-hover-gray-100">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
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
        </a>
    </div>
</div>
</a>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/includes/current_balance.blade.php ENDPATH**/ ?>