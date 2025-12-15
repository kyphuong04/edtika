<?php if(!empty($currencies) and count($currencies)): ?>
    <?php
        $userCurrency = currency();
    ?>

    <div class="js-currency-select language-select position-relative <?php echo e(!empty($currencyClassName) ? $currencyClassName : ''); ?>">
        <form action="/set-currency" method="post">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="currency" value="<?php echo e($userCurrency); ?>">

            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($userCurrency == $currencyItem->currency): ?>
                    <div class="size-32 d-flex-center bg-gray-100 rounded-8 p-4 text-gray-500 font-12"><?php echo e($currencyItem->currency); ?></div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </form>

        <div class="language-dropdown py-8 ">
            <div class="py-8 px-16 font-12 text-gray-500"><?php echo e(trans('update.select_a_currency')); ?></div>

            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="js-currency-dropdown-item language-dropdown__item cursor-pointer <?php echo e(($userCurrency == $currencyItem->currency) ? 'active' : ''); ?>" data-value="<?php echo e($currencyItem->currency); ?>" data-title="<?php echo e($currencyItem->currency); ?>">
                    <div class=" d-flex align-items-center justify-content-between w-100 px-16 py-8 text-dark">
                        <span class="ml-8 font-14"><?php echo e(currenciesLists($currencyItem->currency)); ?></span>

                        <div class="language-dropdown__item-sign-box position-relative d-flex-center rounded-8">
                            <?php echo e(currencySign($currencyItem->currency)); ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/includes/header/currency.blade.php ENDPATH**/ ?>