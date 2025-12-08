<?php
    $cardName = getThemeContentCardStyle("course");
?>

<?php $__env->startPush('styles_top'); ?>
    <?php if(empty($withoutStyles)): ?>
        <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("course_cards/{$cardName}")); ?>">
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="<?php echo e(!empty($gridCardClassName) ? $gridCardClassName : ''); ?>">
        <?php echo $__env->make("design_1.web.courses.components.cards.grids.{$cardName}", ['course' => $course], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/components/cards/grids/index.blade.php ENDPATH**/ ?>