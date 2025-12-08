<?php if(!empty($themeHeaderData['contents'])): ?>
    <div id="themeHeaderVacuum"></div>
    <div class="theme-header-1">
        
        <?php if(!empty($themeHeaderData['contents']['top_navbar'])): ?>
            <?php echo $__env->make('design_1.web.theme.headers.header_1.top_nav', ['themeHeaderTopNavData' => $themeHeaderData['contents']['top_navbar']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        
        <?php echo $__env->make('design_1.web.theme.headers.header_1.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/theme/headers/header_1/index.blade.php ENDPATH**/ ?>