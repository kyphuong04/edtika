<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    ?>

    <?php $__env->startPush('styles_top'); ?>

    <?php $__env->stopPush(); ?>

    <?php if(!empty($contents['space_number']) and $contents['space_number'] > 0): ?>
        <div class="position-relative d-flex" style="height: <?php echo e($contents['space_number']); ?>px">

        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/vertical_spacer/index.blade.php ENDPATH**/ ?>