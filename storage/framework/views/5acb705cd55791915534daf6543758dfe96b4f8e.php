<?php if($bundle->price > 0): ?>
    <?php if($bundle->bestTicket() < $bundle->price): ?>
        <span class=""><?php echo e(handlePrice($bundle->bestTicket(), true, true, false, null, true)); ?></span>
        <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through"><?php echo e(handlePrice($bundle->price, true, true, false, null, true)); ?></span>
    <?php else: ?>
        <span class=""><?php echo e(handlePrice($bundle->price, true, true, false, null, true)); ?></span>
    <?php endif; ?>
<?php else: ?>
    <span class=""><?php echo e(trans('public.free')); ?></span>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/bundles/components/price.blade.php ENDPATH**/ ?>