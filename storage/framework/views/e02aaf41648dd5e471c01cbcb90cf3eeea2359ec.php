<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php echo $__env->make('design_1.panel.certificates.students.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('design_1.panel.certificates.students.most_active_courses', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php if(!empty($certificates) and $certificates->isNotEmpty()): ?>
        <div class="bg-white rounded-24 pt-16 mt-28">

              <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16"><?php echo e(trans('update.generated_certificates')); ?></h3>
                    <p class="font-14 text-gray-500 mt-4"><?php echo e(trans('update.view_and_manage_certificates_generated_for_your_courses')); ?></p>
                </div>
            </div>

            
            <?php echo $__env->make('design_1.panel.certificates.students.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/certificates/students">

                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('quiz.student')); ?></th>
                        <th><?php echo e(trans('update.certificate_id')); ?></th>
                        <th class="text-left"><?php echo e(trans('update.certification_reason')); ?></th>
                        <th><?php echo e(trans('update.certification_type')); ?></th>
                        <th><?php echo e(trans('update.certificate_date')); ?></th>
                        <th class="text-right"><?php echo e(trans('update.controls')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-table-tbody-lists">
                    <?php $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificateItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.certificates.students.item_table',['certificate' => $certificateItem], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                     data-container-items=".js-table-tbody-lists">
                    <?php echo $pagination; ?>

                </div>
            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('design_1.panel.includes.no-result',[
            'file_name' => 'certificates_list.svg',
            'title' => trans('update.student_certificates_no_result'),
            'hint' => nl2br(trans('update.student_certificates_no_result_hint')),
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/certificates/students/index.blade.php ENDPATH**/ ?>