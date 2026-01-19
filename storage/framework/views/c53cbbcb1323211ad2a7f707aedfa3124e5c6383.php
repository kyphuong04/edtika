<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(!empty($comments) and !$comments->isEmpty()): ?>
        <div class="bg-white pt-16 rounded-24">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16"><?php echo e(trans('panel.comments')); ?></h3>
                    <p class="font-14 text-gray-500 mt-4"><?php echo e(trans('update.view_blog_posts_and_related_statistics')); ?></p>
                </div>
            </div>

            
        <?php echo $__env->make('design_1.panel.blog.comments.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/blog/comments">
            <table class="table panel-table">
                <thead>
                <tr>
                    <th class="text-left"><?php echo e(trans('panel.user')); ?></th>
                    <th class="text-left"><?php echo e(trans('admin/main.post')); ?></th>
                    <th class="text-center"><?php echo e(trans('panel.comment')); ?></th>
                    <th class="text-center"><?php echo e(trans('public.status')); ?></th>
                    <th class="text-center"><?php echo e(trans('public.date')); ?></th>
                </tr>
                </thead>
                <tbody class="js-table-body-lists">
                <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commentRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('design_1.panel.blog.comments.table_items', ['comment' => $commentRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
        'file_name' => 'blog_comments.svg',
        'title' => trans('panel.comments_no_result'),
        'hint' =>  nl2br(trans('panel.comments_no_result_hint')) ,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
<script>
    var commentLang = '<?php echo e(trans('panel.comment')); ?>';
</script>

<script src="/assets/default/vendors/moment.min.js"></script>
<script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>

<script src="/assets/design_1/js/panel/blog_comments.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/blog/comments/index.blade.php ENDPATH**/ ?>