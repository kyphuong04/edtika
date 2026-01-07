<?php $__env->startPush("styles_top"); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(!empty($favorites) and !$favorites->isEmpty()): ?>
        <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/courses/favorites">
            <div class="js-table-body-lists row">
                <?php $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $favoriteRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12 col-md-6 col-lg-3 col-xl-2 mt-20">
                        <?php echo $__env->make("design_1.panel.webinars.favorites.grid_card", ['favorite' => $favoriteRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                <?php echo $pagination; ?>

            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('design_1.panel.includes.no-result',[
            'file_name' => 'favorites.svg',
            'title' => trans('panel.no_result_favorites'),
            'hint' =>  trans('panel.no_result_favorites_hint'),
            'extraClass' => 'mt-0',
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>

    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/favorites/index.blade.php ENDPATH**/ ?>