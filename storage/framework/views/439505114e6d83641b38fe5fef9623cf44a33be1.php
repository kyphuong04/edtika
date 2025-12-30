<?php if(!empty($currencies) and count($currencies)): ?>
    <?php
        $userCurrency = currency();
    ?>

    <div class="js-currency-select theme-header-1__dropdown position-relative">
        <form action="/set-currency" method="post">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="currency" value="<?php echo e($userCurrency); ?>">

            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($userCurrency == $currencyItem->currency): ?>
                    <div class="d-flex align-items-center gap-8">
                        <div class="size-32 d-flex-center bg-white-10 rounded-8">
                            <span class="font-12 text-white opacity-75"><?php echo e(currencySign($currencyItem->currency)); ?></span>
                        </div>
                        <span class="js-lang-title text-white opacity-75 d-none d-md-flex"><?php echo e($currencyItem->currency); ?></span>
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white opacity-75','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </form>

        <div class="header-1-dropdown-menu py-8">

            <div class="py-8 px-16 font-12 text-gray-500"><?php echo e(trans('update.select_a_currency')); ?></div>

            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="js-currency-dropdown-item header-1-dropdown-menu__item cursor-pointer <?php echo e(($userCurrency == $currencyItem->currency) ? 'active' : ''); ?>" data-value="<?php echo e($currencyItem->currency); ?>" data-title="<?php echo e($currencyItem->currency); ?>">
                    <div class=" d-flex align-items-center justify-content-between w-100 px-16 py-8 bg-transparent">
                        <span class="text-gray-500 text-dark"><?php echo e(currenciesLists($currencyItem->currency)); ?></span>

                        <div class="header-1-dropdown-menu__item-sign-box position-relative d-flex-center rounded-8">
                            <?php echo e(currencySign($currencyItem->currency)); ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/theme/headers/header_1/includes/currency.blade.php ENDPATH**/ ?>