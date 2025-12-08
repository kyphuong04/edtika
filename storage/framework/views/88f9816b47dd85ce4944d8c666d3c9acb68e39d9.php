<?php
    $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
?>

<?php if($carts->whereNotNull('webinar_id')->count()): ?>
    <div class="card-before-line px-16">
        <h5 class="font-14 mb-16"><?php echo e(trans('update.courses')); ?></h5>

        <?php $__currentLoopData = $carts->whereNotNull('webinar_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('design_1.web.cart.overview.includes.item_cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<?php if($carts->whereNotNull('bundle_id')->count()): ?>
    <div class="card-before-line px-16">
        <h5 class="font-14 mb-16"><?php echo e(trans('update.bundles')); ?></h5>

        <?php $__currentLoopData = $carts->whereNotNull('bundle_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('design_1.web.cart.overview.includes.item_cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<?php if($carts->whereNotNull('reserve_meeting_id')->count()): ?>
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16"><?php echo e(trans('panel.meetings')); ?></h5>

        <?php $__currentLoopData = $carts->whereNotNull('reserve_meeting_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('design_1.web.cart.overview.includes.item_cards.meeting', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>


<?php if($carts->whereNotNull('product_order_id')->count()): ?>
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16"><?php echo e(trans('update.products')); ?></h5>

        <?php $__currentLoopData = $carts->whereNotNull('product_order_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('design_1.web.cart.overview.includes.item_cards.product', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/cart/overview/includes/cart_items.blade.php ENDPATH**/ ?>