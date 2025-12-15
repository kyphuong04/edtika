

<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
    <div class="dashboard-body">
        <?php if($authUser->isUser()): ?>
            <div class="student-dashboard">
                <?php echo $__env->make('design_1.panel.dashboard.student.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        <?php else: ?>
            <div class="instructor-dashboard">
                <?php echo $__env->make('design_1.panel.dashboard.instructor.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts_bottom"); ?>
    <script>
        var learningActivityLang = '<?php echo e(trans('update.learning_activity')); ?>';
        var minsLang = '<?php echo e(trans('update.mins')); ?>';
        var noticeLang = '<?php echo e(trans('update.notice')); ?>';
        var joinTheSessionLang = '<?php echo e(trans('update.join_the_session')); ?>';
        var joinTheMeetingLang = '<?php echo e(trans('update.join_the_meeting')); ?>';
        var passwordLang = '<?php echo e(trans('auth.password')); ?>';

        var $eventsWithTimestamp = <?php echo json_encode((!empty($eventsWithTimestamp) and count($eventsWithTimestamp)) ? $eventsWithTimestamp : [], 15, 512) ?>;
    </script>

    <script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
    <script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>
    <script src="/assets/design_1/vendor/apexcharts/apexcharts.js"></script>

    <script src="/assets/design_1/js/panel/meeting_requests.min.js"></script>
    <script src="/assets/design_1/js/panel/events_calendar.min.js"></script>
    <script src="/assets/design_1/js/panel/dashboard.min.js"></script>
<?php $__env->stopPush(); ?>

<?php if(!empty($giftModal)): ?>
    <?php $__env->startPush('scripts_bottom2'); ?>
        <script>
            (function () {
                "use strict";

                handleFireSwalModal('<?php echo $giftModal; ?>', 32)
            })(jQuery)
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make("design_1.panel.layouts.panel", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/index.blade.php ENDPATH**/ ?>