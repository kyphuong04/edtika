

<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("create-course")); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <form method="post" action="/panel/courses/<?php echo e(!empty($webinar) ? $webinar->id .'/update' : 'store'); ?>" id="webinarForm" enctype="multipart/form-data">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" name="current_step" value="<?php echo e(!empty($currentStep) ? $currentStep : 1); ?>">
        <input type="hidden" name="draft" value="no" id="forDraft"/>
        <input type="hidden" name="get_next" value="no" id="getNext"/>
        <input type="hidden" name="get_step" value="0" id="getStep"/>


        <div class="container mt-80 pb-100">
            
            <?php echo $__env->make('design_1.panel.webinars.create.includes.progress', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <?php echo $__env->make("design_1.panel.webinars.create.steps.step_{$currentStep}", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>


        
        <?php echo $__env->make('design_1.panel.webinars.create.includes.bottom_actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var saveSuccessLang = '<?php echo e(trans('webinars.success_store')); ?>';
        var zoomJwtTokenInvalid = '<?php echo e(trans('webinars.zoom_jwt_token_invalid')); ?>';
        var hasZoomApiToken = '<?php echo e((!empty($authUser->zoomApi) and !empty($authUser->zoomApi->api_key) and !empty($authUser->zoomApi->api_secret)) ? 'true' : 'false'); ?>';
        var editChapterLang = '<?php echo e(trans('public.edit_chapter')); ?>';
    </script>

    <script src="/assets/design_1/js/panel/create_webinar.min.js"></script>
    <script src="/assets/design_1/js/panel/webinar_content_locale.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.web.layouts.app', ['appFooter' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/index.blade.php ENDPATH**/ ?>