<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("installment_card")); ?>">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("css_stars")); ?>">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("buy_with_points")); ?>">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("reviews_and_comments")); ?>">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("show_course")); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startSection("content"); ?>
    <div class="container position-relative mt-80 pb-120 ">

        
        <?php if(!empty($activeSpecialOffer)): ?>
            <?php echo $__env->make('design_1.web.courses.show.includes.special_offer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        
        <?php echo $__env->make('design_1.web.courses.show.includes.hero', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="d-flex flex-column flex-lg-row gap-24">
            
            <div class="course-body-side position-relative course-body-card flex-1">
                <?php echo $__env->make('design_1.web.courses.show.includes.page_body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


                
                <?php echo $__env->make('design_1.web.components.advertising_banners.page_banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                
            </div>

            
            <div class="course-right-side position-relative">
                <?php echo $__env->make('design_1.web.courses.show.includes.right_side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                
                <?php echo $__env->make('design_1.web.components.advertising_banners.sidebar_banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>

    
    <?php echo $__env->make("design_1.web.courses.show.includes.bottom_fixed_card", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/barrating/jquery.barrating.min.js"></script>
    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/time-counter-down.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("swiper_slider")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("video_player_helpers")); ?>"></script>

    <script>
        var webinarDemoLang = '<?php echo e(trans('webinars.webinar_demo')); ?>';
        var notAccessToastTitleLang = '<?php echo e(trans('public.not_access_toast_lang')); ?>';
        var notAccessToastMsgLang = '<?php echo e(trans('public.not_access_toast_msg_lang')); ?>';
        var closeLang = '<?php echo e(trans('public.close')); ?>';
        var shareLang = '<?php echo e(trans('public.share')); ?>';
        var reportCommentLang = '<?php echo e(trans('update.report_comment')); ?>';
        var reportLang = '<?php echo e(trans('panel.report')); ?>';
        var reportCourseLang = '<?php echo e(trans('update.report_course')); ?>';
        var joinCourseWaitlistLang = '<?php echo e(trans('update.join_course_waitlist')); ?>';
        var joinWaitlistLang = '<?php echo e(trans('update.join_waitlist')); ?>';
        var purchaseWithPointsLang = '<?php echo e(trans('update.purchase_with_points')); ?>';
    </script>

    <script src="<?php echo e(getDesign1ScriptPath("reviews")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("comments")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("show_course")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("design_1.web.layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/index.blade.php ENDPATH**/ ?>