<?php $__env->startPush("styles_top"); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('design_1.panel.bundles.my_bundles.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php if(!empty($bundles) and !$bundles->isEmpty()): ?>
        <div id="tableListContainer" class="" data-view-data-path="/panel/bundles">
            <div class="js-page-bundles-lists row">
                <?php $__currentLoopData = $bundles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bundleItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12 col-md-6 col-lg-4 mt-20">
                        <?php echo $__env->make("design_1.panel.bundles.my_bundles.grid_card", ['bundle' => $bundleItem], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                 data-container-items=".js-page-bundles-lists">
                <?php echo $pagination; ?>

            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('design_1.panel.includes.no-result',[
            'file_name' => 'bundles.svg',
            'title' => trans('update.you_not_have_any_bundle'),
            'hint' =>  trans('update.no_result_bundle_hint') ,
            'btn' => ['url' => '/panel/bundles/new','text' => trans('update.create_a_bundle') ]
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/bundles/my_bundles/index.blade.php ENDPATH**/ ?>