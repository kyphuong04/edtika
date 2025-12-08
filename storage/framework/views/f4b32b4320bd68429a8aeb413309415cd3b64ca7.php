<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("swiperjs")); ?>">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("show_blog")); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startSection("content"); ?>
    <div class="blog-show-hero position-relative">
        <div class="blog-show-hero__mask"></div>
        <img src="<?php echo e($post->image); ?>" alt="<?php echo e($post->title); ?>" class="img-cover">
    </div>

    <div class="container position-relative blog-show-body pb-120">
        <div class="blog-show-cover-image position-relative rounded-32">
            <img src="<?php echo e($post->image); ?>" alt="<?php echo e($post->title); ?>" class="img-cover rounded-32">
        </div>

        
        <?php echo $__env->make('design_1.web.blog.show.includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <div class="mt-28 p-16 rounded-16 border-gray-200 bg-gray-100">
            <?php echo nl2br($post->description); ?>

        </div>

        
        <div class="mt-24">
            <?php echo nl2br($post->content); ?>

        </div>

        
        <?php echo $__env->make('design_1.web.blog.show.includes.author_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.web.blog.show.includes.suggested_post', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php if($post->enable_comment): ?>
            <?php echo $__env->make('design_1.web.blog.show.includes.comments', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

    </div>

    
    <?php echo $__env->make('design_1.web.blog.show.includes.fixed_bottom', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var closeLang = '<?php echo e(trans('public.close')); ?>';
        var shareLang = '<?php echo e(trans('public.share')); ?>';
        var reportCommentLang = '<?php echo e(trans('update.report_comment')); ?>';
        var reportLang = '<?php echo e(trans('panel.report')); ?>';
    </script>

    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("swiper_slider")); ?>"></script>

    <script src="<?php echo e(getDesign1ScriptPath("comments")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("show_blog")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("design_1.web.layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/blog/show/index.blade.php ENDPATH**/ ?>