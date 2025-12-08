<div class="no-result default-no-result d-flex-center flex-column bg-white rounded-16 py-120 px-32 mt-20 text-center <?php echo e(!empty($extraClass) ? $extraClass : ''); ?>">
    <div class="no-result-logo <?php echo e(!empty($logoSm) ? 'logo-sm' : ''); ?>">
        <img src="/assets/design_1/img/no-result/<?php echo e($file_name); ?>" alt="<?php echo e($title); ?>" class="img-cover">
    </div>

    <h3 class="font-16 font-weight-bold mt-16"><?php echo e($title); ?></h3>
    <p class="mt-4 font-14 text-gray-500"><?php echo $hint; ?></p>

    <?php if(!empty($btn)): ?>
        <a href="<?php echo e($btn['url']); ?>" class="btn btn-primary mt-16"><?php echo e($btn['text']); ?></a>
    <?php endif; ?>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/includes/no-result.blade.php ENDPATH**/ ?>