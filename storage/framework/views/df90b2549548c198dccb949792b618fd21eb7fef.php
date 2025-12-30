<?php if(!empty($cashbackRules) and count($cashbackRules) and !empty($itemPrice) and $itemPrice > 0): ?>
    <?php $__currentLoopData = $cashbackRules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cashbackRule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card-with-mask position-relative <?php echo e(!empty($cashbackRulesCardClassName) ? $cashbackRulesCardClassName : ''); ?>">
            <div class="mask-8-white z-index-1"></div>
            <div class="position-relative d-flex align-items-center bg-white p-16 rounded-16 z-index-2 w-100 h-100">
                <div class="d-flex-center size-56 rounded-circle bg-success-20">
                    <div class="d-flex-center size-40 rounded-circle bg-success">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-empty-wallet-change'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                </div>
                <div class="ml-8">
                    <h6 class="font-14 font-weight-bold text-dark"><?php echo e(trans('update.get_cashback')); ?></h6>

                    <?php if(!empty($itemType) and $itemType == 'meeting'): ?>
                        <?php if($cashbackRule->min_amount): ?>
                            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.by_reserving_a_this_meeting_you_will_get_amount_as_cashback_for_orders_more_than_min_amount',['amount' => handlePrice($cashbackRule->getAmount($itemPrice)), 'min_amount' => handlePrice($cashbackRule->min_amount)])); ?></div>
                        <?php else: ?>
                            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.by_reserving_a_this_meeting_you_will_get_amount_as_cashback',['amount' => handlePrice($cashbackRule->getAmount($itemPrice))])); ?></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if($cashbackRule->min_amount): ?>
                            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.by_purchasing_this_product_you_will_get_amount_as_cashback_for_orders_more_than_min_amount',['amount' => handlePrice($cashbackRule->getAmount($itemPrice)), 'min_amount' => handlePrice($cashbackRule->min_amount)])); ?></div>
                        <?php else: ?>
                            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.by_purchasing_this_product_you_will_get_amount_as_cashback',['amount' => handlePrice($cashbackRule->getAmount($itemPrice))])); ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/cashback/alert_card.blade.php ENDPATH**/ ?>