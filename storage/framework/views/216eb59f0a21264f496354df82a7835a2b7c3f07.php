<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("cart_page")); ?>">
<?php $__env->stopPush(); ?>

<?php
    $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));
    $userCurrency = currency();
    $invalidChannels = [];
?>

<?php $__env->startSection("content"); ?>
    <section class="container my-56 position-relative">
        <div class="d-flex-center flex-column text-center">
            <h1 class="font-32"><?php echo e(trans('update.checkout')); ?></h1>
            <p class="mt-8 font-16 text-gray-500"><?php echo e(handlePrice($calculatePrices["total"], true, true, false, null, true) . ' ' . trans('cart.for_items',['count' => $count])); ?></p>
        </div>

        <form action="/payments/payment-request" method="post">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">

            <div class="row">
                
                <div class="col-12 col-md-7 col-lg-9 mt-32 mb-104">

                    
                    <?php if(!empty($totalCashbackAmount)): ?>
                        <?php echo $__env->make('design_1.web.cart.overview.includes.cashback_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>

                    <div class="card-with-mask position-relative">
                        <div class="mask-8-white"></div>

                        <div class="position-relative z-index-2 bg-white rounded-16 py-16">
                            <div class="card-before-line px-16">
                                <h3 class="font-14"><?php echo e(trans('update.select_a_payment_gateway')); ?></h3>
                            </div>

                            <div class="d-grid grid-columns-2 grid-lg-columns-3 gap-24 px-16 mt-16">
                                
                                <div class="payment-channel-card position-relative">
                                    <input type="radio" name="gateway" id="gateway_vnpay" data-class="VNPay" value="vnpay">
                                    <label class="position-relative w-100 d-block cursor-pointer" for="gateway_vnpay">
                                        <div class="gateway-mask"></div>
                                        <div class="gateway-card position-relative z-index-2 d-flex-center flex-column rounded-16 bg-white w-100 h-100 text-center">
                                            <div class="d-flex-center size-48 bg-gray-100">
                                                <img src="/assets/design_1/img/payment-gateways/vnpay.svg" alt="VNPay" class="img-fluid">
                                            </div>
                                            <h6 class="font-14 mt-12">VNPay</h6>
                                        </div>
                                    </label>
                                </div>

                                
                                <div class="payment-channel-card position-relative">
                                    <input type="radio" name="gateway" id="gateway_momo" data-class="Momo" value="momo">
                                    <label class="position-relative w-100 d-block cursor-pointer" for="gateway_momo">
                                        <div class="gateway-mask"></div>
                                        <div class="gateway-card position-relative z-index-2 d-flex-center flex-column rounded-16 bg-white w-100 h-100 text-center">
                                            <div class="d-flex-center size-48 bg-gray-100">
                                                <img src="/assets/design_1/img/payment-gateways/momo.svg" alt="Momo" class="img-fluid">
                                            </div>
                                            <h6 class="font-14 mt-12">Momo</h6>
                                        </div>
                                    </label>
                                </div>

                                
                                <div class="payment-channel-card position-relative">
                                    <input type="radio" name="gateway" id="gateway_visa" data-class="Visa" value="visa">
                                    <label class="position-relative w-100 d-block cursor-pointer" for="gateway_visa">
                                        <div class="gateway-mask"></div>
                                        <div class="gateway-card position-relative z-index-2 d-flex-center flex-column rounded-16 bg-white w-100 h-100 text-center">
                                            <div class="d-flex-center size-48 bg-gray-100">
                                                <img src="/assets/design_1/img/payment-gateways/visa.svg" alt="Visa" class="img-fluid">
                                            </div>
                                            <h6 class="font-14 mt-12">Visa Card</h6>
                                        </div>
                                    </label>
                                </div>

                                
                            </div>

                        </div>
                    </div>


                </div>

                
                <div class="col-12 col-md-5 col-lg-3 mt-32">
                    <div class="cart-right-side-section">
                        

                        <div class="js-cart-summary-container">
                            <?php echo $__env->make('design_1.web.cart.overview.includes.summary', ['isCartPaymentPage' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                    </div>
                </div>
            </div>

        </form>

    </section>

    <?php if(!empty($razorpay) and $razorpay): ?>
        <form action="/payments/verify/Razorpay" method="get">
            <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">

            <script src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="<?php echo e(getRazorpayApiKey()['api_key']); ?>"
                    data-amount="<?php echo e((int)($order->total_amount * 100)); ?>"
                    data-buttontext=""
                    data-description="Rozerpay"
                    data-currency="<?php echo e(currency()); ?>"
                    data-image="<?php echo e($generalSettings['logo']); ?>"
                    data-prefill.name="<?php echo e($order->user->full_name); ?>"
                    data-prefill.email="<?php echo e($order->user->email); ?>"
                    data-theme.color="#43d477">
            </script>
        </form>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var hasErrors = '<?php echo e((!empty($errors) and count($errors)) ? 'true' : 'false'); ?>';
        var hasErrorsHintLang = '<?php echo e(trans('update.please_check_the_errors_in_the_shipping_form')); ?>';
        var selectPaymentGatewayLang = '<?php echo e(trans('update.select_a_payment_gateway')); ?>';
        var pleaseWaitLang = '<?php echo e(trans('update.please_wait')); ?>';
        var transferringToLang = '<?php echo e(trans('update.transferring_to_the_payment_gateway')); ?>';
    </script>
    <script src="<?php echo e(getDesign1ScriptPath("cart_page")); ?>"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make("design_1.web.layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/cart/payment/index.blade.php ENDPATH**/ ?>