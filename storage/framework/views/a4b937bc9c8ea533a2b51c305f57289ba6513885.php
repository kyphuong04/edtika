<div class="card-with-mask position-relative mt-28">
    <div class="mask-8-white"></div>

    <div class="position-relative z-index-2 row align-items-center bg-white rounded-16 py-16 px-8 w-100 h-100">
        <div class="col-12 col-lg-4">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-56 bg-primary-20 rounded-circle">
                    <div class="d-flex-center size-40 bg-primary rounded-circle">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-ticket'); ?>
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
                    <h5 class="font-14"><?php echo e(trans('update.have_a_coupon')); ?></h5>
                    <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.validate_it_using_the_following_input')); ?></p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8 mt-16 mt-lg-0">
            <div class="d-flex align-items-center">
                <input type="text" name="coupon" class="js-ajax-coupon form-control mr-12" placeholder="<?php echo e(trans('cart.enter_your_code_here')); ?>">

                <button type="button" class="js-validate-coupon-btn cart-coupon-btn btn btn-primary btn-lg"><?php echo e(trans('cart.validate')); ?></button>
                <button type="button" class="js-remove-coupon-btn d-none cart-coupon-btn btn btn-danger btn-lg"><?php echo e(trans('public.remove')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/cart/overview/includes/coupon.blade.php ENDPATH**/ ?>