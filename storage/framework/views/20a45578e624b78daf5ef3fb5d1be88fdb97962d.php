<?php if(!empty($advertisingBannersSidebar) and count($advertisingBannersSidebar)): ?>
    <div class="row">
        <?php $__currentLoopData = $advertisingBannersSidebar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarBanner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mt-32 mt-16-48 col-<?php echo e($sidebarBanner->size); ?>">
                <a href="<?php echo e($sidebarBanner->link); ?>" class="d-flex sidebar-ads rounded-16">
                    <img src="<?php echo e($sidebarBanner->image); ?>" class="img-cover rounded-16" alt="<?php echo e($sidebarBanner->title); ?>">
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/components/advertising_banners/sidebar_banner.blade.php ENDPATH**/ ?>