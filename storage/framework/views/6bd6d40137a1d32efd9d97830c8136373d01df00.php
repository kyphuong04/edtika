<html>
<head>
    <title><?php echo e($pageTitle ?? ''); ?><?php echo e(!empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : ''); ?></title>
    <?php echo $__env->make('design_1.web.includes.metas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">

</head>
<body class="play-iframe-page">
<?php if(!empty($iframe)): ?>
    <?php echo $iframe; ?>

<?php else: ?>
    <iframe src="<?php echo e($path); ?>" frameborder="0" allowfullscreen class="interactive-file-iframe"></iframe>
<?php endif; ?>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/free_contents/interactive_file.blade.php ENDPATH**/ ?>