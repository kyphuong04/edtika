


<?php $__env->startPush('styles_top'); ?>
    
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="/assets/design_1/landing_builder/front.min.css">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

    <?php if(!empty($landingItem)): ?>
        <?php $__currentLoopData = $landingItem->components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $component): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if ($__env->exists("landingBuilder.front.components.{$component->landingBuilderComponent->name}.index", ['landingComponent' => $component])) echo $__env->make("landingBuilder.front.components.{$component->landingBuilderComponent->name}.index", ['landingComponent' => $component], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    
    <script src="/assets/vendors/typed/typedjs.js"></script>

    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("video_player_helpers")); ?>"></script>
    <script src="/assets/design_1/landing_builder/js/front.min.js"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.web.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/landingBuilder/front/landing/index.blade.php ENDPATH**/ ?>