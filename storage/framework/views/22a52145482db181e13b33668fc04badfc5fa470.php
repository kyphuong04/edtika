<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("learning_page_noticeboards")); ?>">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("learning_page")); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="learning-page d-flex">
        <div class="learning-page__main">
            
            <?php echo $__env->make('design_1.web.courses.learning_page.includes.top_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <?php echo $__env->make('design_1.web.courses.learning_page.includes.main_content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        
        <?php echo $__env->make('design_1.web.courses.learning_page.includes.sidebar.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    
    <?php echo $__env->make('design_1.web.courses.learning_page.noticeboards.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var courseUrl = '<?php echo e($course->getUrl()); ?>';
        var courseSlug = '<?php echo e($course->slug); ?>';
        var courseLearningUrl = '<?php echo e($course->getLearningPageUrl()); ?>';
        var defaultItemType = '<?php echo e(!empty(request()->get('type')) ? request()->get('type') : (!empty($userLearningLastView) ? $userLearningLastView->item_type : '')); ?>'
        var defaultItemId = '<?php echo e(!empty(request()->get('item')) ? request()->get('item') : (!empty($userLearningLastView) ? $userLearningLastView->item_id : '')); ?>'
        var loadFirstContent = <?php echo e((!empty($dontAllowLoadFirstContent) and $dontAllowLoadFirstContent) ? 'false' : 'true'); ?>; // allow to load first content when request item is empty
        // Langs
        var learningPageEmptyContentTitleLang = '<?php echo e(trans('update.learning_page_empty_content_title')); ?>';
        var learningPageEmptyContentHintLang = '<?php echo e(trans('update.learning_page_empty_content_hint')); ?>';
        var pleaseWaitLang = '<?php echo e(trans('update.please_wait')); ?>';
        var pleaseWaitForTheContentLang = '<?php echo e(trans('update.please_wait_for_the_content_to_load')); ?>';
        var newCourseNoteLang = '<?php echo e(trans('update.new_course_note')); ?>';
        var editCourseNoteLang = '<?php echo e(trans('update.edit_course_note')); ?>';
        var courseNoteLang = '<?php echo e(trans('update.course_note')); ?>';
        var saveNoteLang = '<?php echo e(trans('update.save_note')); ?>';
        var deleteNoteLang = '<?php echo e(trans('update.delete_note')); ?>';
        var submittedOnLang = '<?php echo e(trans('update.submitted_on')); ?>';
        var editLang = '<?php echo e(trans('public.edit')); ?>';
        var accessDeniedLang = '<?php echo e(trans('update.access_denied')); ?>';
        var noteLang = '<?php echo e(trans('update.note')); ?>';
        var accessDeniedModalFooterHintLang = '<?php echo e(trans('update.your_access_will_be_delegated_automatically')); ?>';
        var rateAssignmentLang = '<?php echo e(trans('update.rate_assignment')); ?>';
        var passGradeLang = '<?php echo e(trans('update.pass_grade')); ?>';
        var submitGradeLang = '<?php echo e(trans('update.submit_grade')); ?>';
        var submitQuestionLang = '<?php echo e(trans('update.submit_question')); ?>';
        var courseCompletedLang = '<?php echo e(trans('update.course_completed')); ?>';
    </script>

    <script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>

    <script src="<?php echo e(getDesign1ScriptPath("video_player_helpers")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("learning_page_noticeboards")); ?>"></script>
    <script src="<?php echo e(getDesign1ScriptPath("learning_page")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.web.layouts.app', ['appFooter' => false, 'appHeader' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/index.blade.php ENDPATH**/ ?>