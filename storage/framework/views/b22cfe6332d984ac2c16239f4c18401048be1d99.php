<?php if(!empty($advertisingBanners) and count($advertisingBanners)): ?>
    <div class="row">
        <?php $__currentLoopData = $advertisingBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mt-32 mt-lg-48 col-<?php echo e($banner->size); ?>">
                <a href="<?php echo e($banner->link); ?>" class="d-flex rounded-16">
                    <img src="<?php echo e($banner->image); ?>" class="img-cover rounded-16" alt="<?php echo e($banner->title); ?>">
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/components/advertising_banners/page_banner.blade.php ENDPATH**/ ?>