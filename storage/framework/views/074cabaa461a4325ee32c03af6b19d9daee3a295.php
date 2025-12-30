<?php
    $themeSpecificLinks = (new \App\Mixins\Themes\ThemeHeaderMixins())->getHeader1NavbarSpecificLinks($themeHeaderData['contents']);
    $themeSpecificButton = (new \App\Mixins\Themes\ThemeHeaderMixins())->getHeader1NavbarSpecificButton($themeHeaderData['contents']);
?>

<div id="themeHeaderSticky" class="theme-header-1__main">
    <div class="container h-100 position-relative">
        <div class="theme-header-1__main-mask"></div>

        <div class="position-relative z-index-2 bg-white rounded-24 w-100 h-100 p-16">
            <div class="row align-items-center h-100">
                
                <div class="col-6 col-lg-2">
                    <a href="/" class="theme-header-1__logo text-left d-block">
                        <?php if(!empty($generalSettings['logo'])): ?>
                            <img src="<?php echo e($generalSettings['logo']); ?>" class="img-fluid light-only" alt="<?php echo e($generalSettings['site_name'] ?? 'site'); ?>">
                        <?php endif; ?>

                        <?php if(!empty($generalSettings['dark_mode_logo'])): ?>
                            <img src="<?php echo e($generalSettings['dark_mode_logo']); ?>" class="img-fluid dark-only" alt="<?php echo e($generalSettings['site_name'] ?? 'site'); ?>">
                        <?php endif; ?>
                    </a>
                </div>

                
                <div class="col-6 col-lg-2 d-flex align-items-center justify-content-end">
                    <?php echo $__env->make('design_1.web.theme.headers.header_1.includes.categories', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                
                <div class="col-6 col-lg-5 mt-12 mt-lg-0">
                    <?php if(!empty($themeSpecificLinks) and count($themeSpecificLinks)): ?>
                        <div class="d-flex align-items-center gap-16 gap-lg-32">
                            <?php $__currentLoopData = $themeSpecificLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $themeSpecificLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e($themeSpecificLink['url']); ?>" class="text-dark"><?php echo e($themeSpecificLink['title']); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="col-6 col-lg-3 mt-12 mt-lg-0 d-flex align-items-center justify-content-end">
                    <?php if(!empty($themeSpecificButton) and !empty($themeSpecificButton['title'])): ?>
                        <a href="<?php echo e($themeSpecificButton['url']); ?>" class="btn-flip-effect btn btn-primary btn-lg gap-8 text-white" data-text="<?php echo e($themeSpecificButton['title']); ?>">
                            <?php if(!empty($themeSpecificButton['icon'])): ?>
                                <?php echo e(svg("iconsax-{$themeSpecificButton['icon']}", ['width' => '20px', 'height' => '20px', 'class' => "icons"])); ?>
                            <?php endif; ?>

                            <span class="btn-flip-effect__text text-white"><?php echo e($themeSpecificButton['title']); ?></span>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/theme/headers/header_1/main.blade.php ENDPATH**/ ?>