<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(!empty($comments) and !$comments->isEmpty()): ?>
        <div class="bg-white pt-16 rounded-24">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16"><?php echo e(trans('panel.my_comments')); ?></h3>

                </div>
            </div>

            
            <?php echo $__env->make('design_1.panel.store.my_comments.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/store/products/my-comments">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('update.product')); ?></th>
                        <th class="text-center"><?php echo e(trans('panel.comment')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.status')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.date')); ?></th>
                        <th class="text-right"><?php echo e(trans('update.controls')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commentRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.store.my_comments.table_items', ['comment' => $commentRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                    <?php echo $pagination; ?>

                </div>
            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('design_1.panel.includes.no-result',[
            'file_name' => 'store_product_comments.svg',
            'title' => trans('panel.my_comments_no_result'),
            'hint' =>  nl2br(trans('panel.my_comments_no_result_hint')),
            'extraClass' => 'mt-0',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var commentLang = '<?php echo e(trans('panel.comment')); ?>';
        var replyToCommentLang = '<?php echo e(trans('panel.reply_to_the_comment')); ?>';
        var editCommentLang = '<?php echo e(trans('panel.edit_comment')); ?>';
        var saveLang = '<?php echo e(trans('public.save')); ?>';
        var closeLang = '<?php echo e(trans('public.close')); ?>';
        var failedLang = '<?php echo e(trans('quiz.failed')); ?>';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>

    <script src="/assets/design_1/js/panel/comments.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/store/my_comments/index.blade.php ENDPATH**/ ?>