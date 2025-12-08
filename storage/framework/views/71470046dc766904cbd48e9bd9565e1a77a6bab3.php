<div class="bg-white py-16 rounded-24">
    <div class="px-16 pb-16 border-bottom-gray-100">
        <h4 class="font-16 font-weight-bold"><?php echo e(trans('update.payout_statistics')); ?></h4>
    </div>

    <div class="px-16 d-flex align-items-center justify-content-between mt-20">
        <div class="">

            <div class="">
                <span class="d-block text-gray-500"><?php echo e(trans('financial.account_charge')); ?></span>
                <span class="d-block font-16 font-weight-bold mt-4"><?php echo e($accountCharge ? handlePrice($accountCharge) : 0); ?></span>
            </div>

            <div class="mt-16">
                <span class="d-block text-gray-500"><?php echo e(trans('financial.ready_to_payout')); ?></span>
                <span class="d-block font-16 font-weight-bold mt-4"><?php echo e(handlePrice($readyPayout ?? 0)); ?></span>
            </div>

            <div class="mt-16">
                <span class="d-block text-gray-500"><?php echo e(trans('financial.total_income')); ?></span>
                <span class="d-block font-16 font-weight-bold mt-4"><?php echo e(handlePrice($totalIncome ?? 0)); ?></span>
            </div>

        </div>

        <div class="">
            <img src="/assets/design_1/img/panel/payout/payout-statistics-icon.svg" alt="" class="" width="120px" height="120px">
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/financial/payout/statistics.blade.php ENDPATH**/ ?>