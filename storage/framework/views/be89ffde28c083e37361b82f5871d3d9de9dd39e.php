<?php $__env->startPush('styles_top'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-12 col-lg-6">
            <?php echo $__env->make('design_1.panel.financial.payout.ready_to_payout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <div class="col-12 col-lg-6 mt-16 mt-lg-0">
            <?php echo $__env->make('design_1.panel.financial.payout.statistics', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    <?php if(!empty($payouts) and !$payouts->isEmpty()): ?>
        <div class="bg-white pt-16 rounded-24 mt-20">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16"><?php echo e(trans('financial.payouts_history')); ?></h3>

                </div>
            </div>

            

            
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/financial/payout">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('financial.account')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.type')); ?></th>
                        <th class="text-center"><?php echo e(trans('panel.amount')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.status')); ?></th>
                        <th class="text-center"><?php echo e(trans('admin/main.actions')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    <?php $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payoutRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.financial.payout.table_items', ['payout' => $payoutRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            'file_name' => 'payout.svg',
            'title' => trans('financial.payout_no_result'),
            'hint' => nl2br(trans('financial.payout_no_result_hint')),
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <div id="requestPayoutModal" class="d-none">
        <div class="d-flex-center flex-column text-center">
            <img src="/assets/design_1/img/panel/payout/payout_request.svg" alt="payout_request" class="" width="154px" height="150px">

            <h5 class="font-14 font-weight-bold mt-16"><?php echo e(trans('update.review_payout_information')); ?></h5>
            <p class="font-12 text-gray-500 mt-8"><?php echo e(trans('update.review_payout_information_hint')); ?></p>
        </div>

        <div class="mt-16 p-16 rounded-12 border-gray-200">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-gray-500"><?php echo e(trans('update.payout_amount')); ?></span>
                <span class=""><?php echo e(handlePrice($readyPayout ?? 0)); ?></span>
            </div>

            <?php if(!empty($authUser->selectedBank) and !empty($authUser->selectedBank->bank)): ?>
                <div class="d-flex align-items-center justify-content-between mt-12">
                    <span class="text-gray-500"><?php echo e(trans('financial.account')); ?></span>
                    <span class=""><?php echo e($authUser->selectedBank->bank->title); ?></span>
                </div>

                <?php $__currentLoopData = $authUser->selectedBank->bank->specifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $selectedBankSpecification = $authUser->selectedBank->specifications->where('user_selected_bank_id', $authUser->selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                    ?>

                    <div class="d-flex align-items-center justify-content-between mt-12">
                        <span class="text-gray-500"><?php echo e($specification->name); ?></span>
                        <span><?php echo e((!empty($selectedBankSpecification)) ? $selectedBankSpecification->value : ''); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var payoutDetailsLang = '<?php echo e(trans('update.payout_details')); ?>';
        var closeLang = '<?php echo e(trans('public.close')); ?>';
        var payoutRequestLang = '<?php echo e(trans('financial.payout_request')); ?>';
        var submitRequestLang = '<?php echo e(trans('update.submit_request')); ?>';
    </script>

    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>

    <script src="/assets/design_1/js/panel/payout.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/financial/payout/index.blade.php ENDPATH**/ ?>