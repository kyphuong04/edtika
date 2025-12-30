

<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("system_status_pages")); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
    <section class="container mt-96 mb-104 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="system-status-page-section position-relative">
                    <div class="system-status-page-section__mask"></div>

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 p-24 pt-64 p-lg-40 text-center z-index-2">

                        <?php if(!empty($errorSettings['right_float_image'])): ?>
                            <div class="system-status-page-right-float-image">
                                <img src="<?php echo e($errorSettings['right_float_image']); ?>" alt="<?php echo e(trans('update.right_float_image')); ?>" class="img-cover">
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($errorSettings['image'])): ?>
                            <div class="system-status-page-image">
                                <img src="<?php echo e($errorSettings['image']); ?>" alt="<?php echo e($errorSettings['title'] ?? ''); ?>" class="img-cover">
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($errorSettings['title'])): ?>
                            <h1 class="font-16 font-weight-bold mt-16"><?php echo e($errorSettings['title']); ?></h1>
                        <?php endif; ?>

                        <?php if(!empty($errorSettings['description'])): ?>
                            <p class="font-14 text-gray-500 mt-4"><?php echo nl2br($errorSettings['description']); ?></p>
                        <?php endif; ?>

                        <?php if(!empty($errorSettings['button']) and !empty($errorSettings['button']['title']) and !empty($errorSettings['button']['link'])): ?>
                            <a href="<?php echo e($errorSettings['button']['link']); ?>" class="btn btn-primary btn-lg mt-24"><?php echo e($errorSettings['button']['title']); ?></a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make("design_1.web.layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/errors/errors.blade.php ENDPATH**/ ?>