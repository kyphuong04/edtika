<?php $__env->startSection('content'); ?>

    <?php if(!empty($supports) and !$supports->isEmpty()): ?>
        <div class="row">
            <div class="col-12 col-lg-4">
                <?php echo $__env->make('design_1.panel.support.conversations.lists', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="col-12 col-lg-8 mt-20 mt-lg-0">
                <?php echo $__env->make('design_1.panel.support.conversations.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('design_1.panel.includes.no-result',[
            'file_name' => 'support_tickets.svg',
            'title' => trans('panel.support_no_result'),
            'hint' => nl2br(trans('panel.support_no_result_hint')),
            'extraClass' => 'mt-0',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/design_1/js/panel/conversations.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/support/conversations/index.blade.php ENDPATH**/ ?>