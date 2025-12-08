<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("cart_page")); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
    <section class="container mt-56 mb-80 position-relative">
        <div class="d-flex-center flex-column text-center">
            <h1 class="font-32"><?php echo e(trans('update.cart')); ?></h1>
            <p class="mt-8 font-16 text-gray-500"><?php echo e(handlePrice($calculatePrices["sub_total"], true, true, false, null, true) . ' ' . trans('cart.for_items',['count' => $carts->count()])); ?></p>
        </div>

        <form action="/cart/checkout" method="post" id="cartForm">
            <?php echo e(csrf_field()); ?>


            <div class="row mb-160">
                
                <div class="col-12 col-md-7 col-lg-9 mt-32 mb-104">

                    
                    <?php if(!empty($totalCashbackAmount)): ?>
                        <?php echo $__env->make('design_1.web.cart.overview.includes.cashback_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>

                    <?php if(!empty($userGroup) and !empty($userGroup->discount)): ?>
                        <?php echo $__env->make('design_1.web.cart.overview.includes.user_group_discount', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>

                    <div class="card-with-mask position-relative">
                        <div class="mask-8-white"></div>

                        <div class="position-relative z-index-2 bg-white rounded-16 py-16">
                            
                            <?php echo $__env->make('design_1.web.cart.overview.includes.cart_items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <?php if($hasPhysicalProduct): ?>
                                <?php echo $__env->make('design_1.web.cart.overview.includes.shipping_and_delivery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php endif; ?>

                        </div>
                    </div>

                    
                    <?php echo $__env->make('design_1.web.cart.overview.includes.coupon', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                
                <div class="col-12 col-md-5 col-lg-3 mt-32">
                    <div class="cart-right-side-section">
                        

                        <div class="js-cart-summary-container">
                            <?php echo $__env->make('design_1.web.cart.overview.includes.summary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                    </div>
                </div>
            </div>

        </form>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var selectRegionDefaultVal = '';
        var selectStateLang = '<?php echo e(trans('update.choose_a_state')); ?>';
        var selectCityLang = '<?php echo e(trans('update.choose_a_city')); ?>';
        var selectDistrictLang = '<?php echo e(trans('update.all_districts')); ?>';
        var couponLang = '<?php echo e(trans('update.coupon')); ?>';
        var enterCouponLang = '<?php echo e(trans('update.please_enter_your_discount_code')); ?>';
        var removeCouponTitleLang = '<?php echo e(trans('update.remove_coupon_title')); ?>';
        var removeCouponHintLang = '<?php echo e(trans('update.remove_coupon_massage_hint')); ?>';
        var cancelLang = '<?php echo e(trans('public.cancel')); ?>';
        var removeLang = '<?php echo e(trans('public.remove')); ?>';
        var hasErrors = '<?php echo e((!empty($errors) and count($errors)) ? 'true' : 'false'); ?>';
        var hasErrorsHintLang = '<?php echo e(trans('update.please_check_the_errors_in_the_shipping_form')); ?>';
    </script>

    <script src="<?php echo e(getDesign1ScriptPath("get_regions")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("cart_page")); ?>"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make("design_1.web.layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/cart/overview/index.blade.php ENDPATH**/ ?>