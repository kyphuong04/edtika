<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("auth/theme_1")); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
    <section class="container mt-96 mb-104 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="auth-page-card position-relative">
                    <div class="auth-page-card__mask"></div>

                    <div class="position-relative bg-white rounded-32 p-16 z-index-2">
                        <div class="row">
                            <div class="col-12 col-lg-6">

                                <?php echo $__env->yieldContent("page_content"); ?>

                            </div>

                            <div class="col-12 col-lg-6 d-none d-lg-block">
                                <?php echo $__env->make('design_1.web.auth.theme_1.includes.slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    <script src="<?php echo e(getDesign1ScriptPath("auth_theme_1")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.web.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/auth/theme_1/layout.blade.php ENDPATH**/ ?>