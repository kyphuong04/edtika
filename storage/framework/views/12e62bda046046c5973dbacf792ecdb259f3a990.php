<div class="d-flex">
    <div class="review-rate-box position-relative">
        <div class="review-rate-box__mask"></div>

        <div class="position-relative d-flex-center flex-column text-center bg-gray-100 border-gray-200 rounded-12 p-14 p-lg-20 z-index-2 w-100 h-100">
            <div class="font-44 font-weight-bold"><?php echo e($itemRow->getRate()); ?></div>

            <?php echo $__env->make('design_1.web.components.rate', [
                 'rate' => $itemRow->getRate(),
                 'rateCount' => false,
                 'rateClassName' => 'mt-8'
             ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="mt-8 font-12 text-gray-500"><?php echo e($itemRow->getRateCount()); ?>  <?php echo e(trans('product.reviews')); ?></div>
        </div>
    </div>

    <div class="flex-1 ml-24">

        <?php $__currentLoopData = $reviewOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reviewOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $optionRate = $itemRow->reviews->avg($reviewOption) ?? 0;
                $ratePercent = ($optionRate > 0) ? ($optionRate / 5 * 100) : 0;
                $rateCount = ($optionRate > 0) ? round($optionRate, 1) : 0;
            ?>

            <div class="mt-16">
                <div class="font-12 text-gray-500"><?php echo e(trans("product.{$reviewOption}")); ?> (<?php echo e($rateCount); ?>)</div>
                <div class="review-progress position-relative mt-8 rounded-4 bg-gray-100">
                    <span class="review-progress__bar rounded-4 bg-warning" style="width: <?php echo e($ratePercent); ?>%"></span>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/components/reviews/rate_card.blade.php ENDPATH**/ ?>