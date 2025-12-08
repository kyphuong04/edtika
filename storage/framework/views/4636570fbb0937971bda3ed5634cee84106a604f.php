<?php
    $themeSettings = getThemeAuthenticationPagesSettings();
    $sliderBg = (!empty($themeSettings) and !empty($themeSettings['slider_background_image'])) ? $themeSettings['slider_background_image'] : null;
    $sliders = (!empty($themeSettings) and !empty($themeSettings['slider_contents']) and is_array($themeSettings['slider_contents'])) ? $themeSettings['slider_contents'] : [];
?>

<div class="auth-slider-container w-100 rounded-16 bg-gray-100" <?php if(!empty($sliderBg)): ?> style="background-image: url('<?php echo e($sliderBg); ?>')" <?php endif; ?>>
    <?php if(!empty($sliders)): ?>
        <div class="position-relative h-100 w-100">
            <div class="swiper-container js-make-swiper auth-theme-slider pb-0 h-100"
                 data-item="auth-theme-slider"
                 data-autoplay="true"
                 data-loop="true"
                 data-pagination="auth-theme-slider-pagination"
            >
                <div class="swiper-wrapper py-0 ">
                    <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide">
                            <div class="d-flex-center flex-column text-center h-90 p-16">
                                <?php if(!empty($slider['image'])): ?>
                                    <div class="auth-slider-image d-flex-center">
                                        <img src="<?php echo e($slider['image']); ?>" alt="image" class="img-fluid">
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($slider['title'])): ?>
                                    <h4 class="font-16 mt-16"><?php echo e($slider['title']); ?></h4>
                                <?php endif; ?>

                                <?php if(!empty($slider['subtitle'])): ?>
                                    <div class="font-14 mt-8 text-gray-500"><?php echo e($slider['subtitle']); ?></div>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="swiper-pagination auth-theme-slider-pagination"></div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/auth/theme_1/includes/slider.blade.php ENDPATH**/ ?>