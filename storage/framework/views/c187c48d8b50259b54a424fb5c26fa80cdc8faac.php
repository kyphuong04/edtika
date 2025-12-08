<?php if($courseRow->price > 0): ?>
    <?php if($courseRow->bestTicket() < $courseRow->price): ?>
        <span class=""><?php echo e(handlePrice($courseRow->bestTicket(), true, true, false, null, true)); ?></span>
        <span class="font-14 font-weight-400 text-gray-500 text-decoration-line-through <?php echo e(!empty($discountedPriceClass) ? $discountedPriceClass : 'ml-8'); ?>"><?php echo e(handlePrice($courseRow->price, true, true, false, null, true)); ?></span>
    <?php else: ?>
        <span class=""><?php echo e(handlePrice($courseRow->price, true, true, false, null, true)); ?></span>
    <?php endif; ?>
<?php else: ?>
    <span class=""><?php echo e(trans('public.free')); ?></span>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/components/price_horizontal.blade.php ENDPATH**/ ?>