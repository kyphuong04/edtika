<?php
    $advertisingModalSettings = getAdvertisingModalSettings();
?>

<?php if(!empty($advertisingModalSettings)): ?>
    <?php $__env->startPush('scripts_bottom'); ?>
        <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("advertising_modals")); ?>">

        <script>
            var hasAdvertisingModal = true;
            var openingDelayAdvertisingModal = Number(<?php echo e(!empty($advertisingModalSettings['opening_delay']) ? $advertisingModalSettings['opening_delay'] : 0); ?>);
        </script>

        <script src="/assets/design_1/js/parts/time-counter-down.min.js"></script>
        <script src="<?php echo e(getDesign1ScriptPath("advertising_modals")); ?>"></script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/includes/advertise_modal/index.blade.php ENDPATH**/ ?>