<?php $__env->startSection('content'); ?>

    <?php if(!empty($notifications) and !$notifications->isEmpty()): ?>
        <div class="card-with-dashed-mask d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-16 rounded-16">
            <div class="">
                <h4 class="font-14 font-weight-bold"><?php echo e(trans('update.manage_notifications')); ?></h4>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.simply_mark_all_available_notifications_as_read_or_clear_them')); ?></p>
            </div>

            <div class="d-flex align-items-center mt-16 mt-lg-0">

                <a href="/panel/notifications/mark-all-as-read" class="delete-action cursor-pointer text-primary font-14 font-weight-bold" data-msg="<?php echo e(trans('update.convert_unread_messages_to_read')); ?>" data-confirm="<?php echo e(trans('update.yes_convert')); ?>">
                    <?php echo e(trans('update.mark_all_as_read')); ?>

                </a>

            </div>
        </div>

        
        <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/notifications">
            <div class="js-notifications-lists">
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notificationRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('design_1.panel.notifications.notif_card', ['notification' => $notificationRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-notifications-lists">
                <?php echo $pagination; ?>

            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('design_1.panel.includes.no-result',[
            'file_name' => 'notifications.svg',
           'title' => trans('panel.notification_no_result'),
           'hint' => nl2br(trans('panel.notification_no_result_hint')),
           'extraClass' => 'mt-0',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        (function ($) {
            "use strict";

            <?php if(!empty(request()->get('notification'))): ?>
            setTimeout(() => {
                $('body #showNotificationMessage<?php echo e(request()->get('notification')); ?>').trigger('click');

                let url = window.location.href;
                url = url.split('?')[0];
                window.history.pushState("object or string", "Title", url);
            }, 400);
            <?php endif; ?>
        })(jQuery)
    </script>

    <script>
        var viewNotificationLang = '<?php echo e(trans('update.view_notification')); ?>';
        var closeLang = '<?php echo e(trans('public.close')); ?>';
    </script>

    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>

    <script src="/assets/design_1/js/panel/notifications.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/notifications/index.blade.php ENDPATH**/ ?>