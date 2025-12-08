<div class="card-with-mask position-relative">
    <div class="mask-8-white"></div>

    <div class="position-relative z-index-2 bg-white rounded-16 p-16 w-100 h-100">
        <h5 class="font-14"><?php echo e(trans('home.order_summary')); ?></h5>

        <div class="d-flex align-items-center justify-content-between mt-20">
            <span class="text-gray-500"><?php echo e(trans('update.subtotal')); ?></span>
            <span class="js-cart-subtotal"><?php echo e(handlePrice($calculatePrices["sub_total"])); ?></span>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <span class="text-gray-500"><?php echo e(trans('update.discount')); ?></span>
            <span class="js-cart-discount"><?php echo e(!empty($calculatePrices["total_discount"]) ? handlePrice($calculatePrices["total_discount"]) : 0); ?></span>
        </div>

        <?php if(!empty($calculatePrices['discountCoupon'])): ?>
            <input type="hidden" name="discount_id" value="<?php echo e($calculatePrices['discountCoupon']->id); ?>">

            <div class="js-coupon-card-in-summary d-flex align-items-center justify-content-between mt-12 p-12 rounded-8 bg-gray-100 border-gray-300">
                <div class="d-flex align-items-center font-12 text-gray-500">
                    <span class=""><?php echo e($calculatePrices['discountCoupon']->code); ?></span>
                    <span class="ml-4 font-weight-bold">(<?php echo e($calculatePrices['discountCoupon']->percent); ?>%)</span>
                </div>

                <button type="button" class="js-remove-coupon-btn btn-transparent">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'close-icon text-danger','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </button>
            </div>
        <?php endif; ?>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="d-flex align-items-center gap-4 text-gray-500">
                <span class=""><?php echo e(trans('cart.tax')); ?></span>

                <?php if(empty($calculatePrices["tax_is_different"])): ?>
                    <span class="">(<?php echo e($calculatePrices["tax"]); ?>%)</span>
                <?php endif; ?>
            </div>

            <span class="js-cart-tax"><?php echo e(!empty($calculatePrices["tax_price"]) ? handlePrice($calculatePrices["tax_price"]) : 0); ?></span>
        </div>

        <?php if(!empty($calculatePrices["product_delivery_fee"])): ?>
            <div class="d-flex align-items-center justify-content-between mt-16">
                <span class="text-gray-500"><?php echo e(trans('update.delivery_fee')); ?></span>
                <span class="js-cart-delivery_fee"><?php echo e(handlePrice($calculatePrices["product_delivery_fee"])); ?></span>
            </div>
        <?php endif; ?>

        <div class="cart-summary-divider"></div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <span class="text-gray-500"><?php echo e(trans('cart.total')); ?></span>
            <span class="js-cart-total font-16 font-weight-bold"><?php echo e(handlePrice($calculatePrices["total"])); ?></span>
        </div>

        <button type="button" class="<?php echo e(!empty($isCartPaymentPage) ? 'js-cart-payment-btn' : 'js-cart-checkout'); ?> btn btn-lg btn-block btn-primary mt-20">
            <?php if(!empty($isCartPaymentPage)): ?>
                <?php echo e(trans('update.pay_now')); ?>

            <?php else: ?>
                <?php echo e(trans('cart.checkout')); ?>

            <?php endif; ?>
        </button>

        <?php if(!empty(getOthersPersonalizationSettings("show_secure_payment_text"))): ?>
            <div class="d-flex-center mt-20">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-shield-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <span class="ml-4 font-12 font-weight-bold text-gray-500"><?php echo e(trans('update.secure_payments_provided')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(!empty(getOthersPersonalizationSettings("secure_payment_image"))): ?>
            <div class="d-flex-center mt-16">
                <img src="<?php echo e(getOthersPersonalizationSettings("secure_payment_image")); ?>" alt="<?php echo e(trans('update.secure_payments_provided')); ?>" class="img-fluid" height="24px">
            </div>
        <?php endif; ?>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/cart/overview/includes/summary.blade.php ENDPATH**/ ?>