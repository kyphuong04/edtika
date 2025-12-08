<div class="card-with-mask mb-20 <?php echo e(!empty($className) ? $className : ''); ?>">
    <div class="mask-8-white z-index-1 border-dashed border-gray-300"></div>

    <div class="position-relative z-index-2 d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-8 rounded-16 border-gray-200 w-100 h-100">
        <div class="d-flex">
            <div class="cart-item__image rounded-8 bg-gray-200">
                <img src="<?php echo e($cartItemInfo['imgPath']); ?>" class="img-cover rounded-8" alt="<?php echo e($cartItemInfo['title']); ?>">
            </div>
            <div class="ml-8">
                <a href="<?php echo e($cartItemInfo['itemUrl'] ?? '#!'); ?>" target="_blank">
                    <h6 class="font-12 text-dark"><?php echo e($cartItemInfo['title']); ?></h6>
                </a>

                <?php if(!is_null($cartItemInfo['rate'])): ?>
                    <?php echo $__env->make('design_1.web.components.rate', [
                         'rate' => $cartItemInfo['rate'],
                         'rateCount' => $cartItemInfo['rateCount'],
                         'rateClassName' => 'mt-8'
                     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>

                <a href="<?php echo e($cartItemInfo['profileUrl']); ?>" target="_blank" class="d-flex align-items-center mt-16">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-profile'); ?>
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
                    <span class="ml-4 font-12 text-gray-500"><?php echo e($cartItemInfo['teacherName']); ?></span>
                </a>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between justify-content-lg-start mt-16 mt-lg-0">
            <div class="mr-0 mr-lg-72">
                <?php if(!empty($cartItemInfo['discountPrice'])): ?>
                    <div class="d-flex flex-column">
                        <span class="font-16 font-weight-bold text-primary"><?php echo e(handlePrice($cartItemInfo['discountPrice'], true, true, false, null, true, $cartTaxType)); ?></span>
                        <span class="text-decoration-line-through font-12 text-gray-500 mt-4"><?php echo e(handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType)); ?></span>
                    </div>
                <?php else: ?>
                    <span class="font-16 font-weight-bold text-primary"><?php echo e(handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType)); ?></span>
                <?php endif; ?>
            </div>

            <a href="/cart/<?php echo e($cartItem->getId()); ?>/delete" class="delete-action d-flex-center size-40 rounded-circle bg-gray-100 bg-hover-gray-200">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-close-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </a>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/cart/overview/includes/item_cards/course.blade.php ENDPATH**/ ?>