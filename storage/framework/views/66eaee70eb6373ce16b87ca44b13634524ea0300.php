<?php
    $w = !empty($width) ? str_replace("px", '', $width) : 16;
    $h = !empty($height) ? str_replace("px", '', $height) : 16;
?>

<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 <?php echo e($w); ?> <?php echo e($h); ?>" fill="none" width="<?php echo e($w); ?>px" height="<?php echo e($h); ?>px" class="<?php echo e($class ?? ''); ?>">
    <path d="M3 7.48222L6.17305 11L13 4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/components/tick-icon.blade.php ENDPATH**/ ?>