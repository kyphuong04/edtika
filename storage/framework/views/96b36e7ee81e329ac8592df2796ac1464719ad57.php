<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('design_1.panel.financial.sales.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(!empty($sales) and !$sales->isEmpty()): ?>
        <div class="bg-white pt-16 rounded-24 mt-20">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16"><?php echo e(trans('financial.sales_history')); ?></h3>

                </div>
            </div>

            
            <?php echo $__env->make('design_1.panel.financial.sales.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/financial/sales">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('quiz.student')); ?></th>
                        <th class="text-left"><?php echo e(trans('product.content')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.price')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.discount')); ?></th>
                        <th class="text-center"><?php echo e(trans('financial.total_amount')); ?></th>
                        <th class="text-center"><?php echo e(trans('financial.income')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.type')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.date')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saleRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.financial.sales.table_items', ['sale' => $saleRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
          'file_name' => 'sales.svg',
          'title' => trans('financial.sales_no_result'),
          'hint' => nl2br(trans('financial.sales_no_result_hint')),
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/financial/sales/index.blade.php ENDPATH**/ ?>