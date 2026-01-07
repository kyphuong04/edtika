<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php echo $__env->make('design_1.panel.certificates.my_achievements.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('design_1.panel.certificates.my_achievements.potential_certificates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php if(!empty($certificatesItems) and $certificatesItems->isNotEmpty()): ?>
        <div class="bg-white rounded-24 pt-16 mt-28">
            <div class="px-16">
                <h4 class="font-16 font-weight-bold"><?php echo e(trans('update.active_certificates')); ?></h4>
                <p class="font-14 text-gray-500 mt-4"><?php echo e(trans('update.view_and_manage_quiz_and_completion_certificates')); ?></p>
            </div>

            <div class="d-flex align-items-center border-bottom-gray-100 border-top-gray-100 mt-16 px-16">

                <?php
                    $tabs = [
                        'quiz' => 'clipboard-tick',
                        'completion' => 'tick-circle',
                    ];
                ?>

                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tabName => $tabIcon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="js-get-view-data-by-tab navbar-item navbar-item-h-52 d-flex align-items-center mr-20 mr-md-40 cursor-pointer <?php echo e($loop->first ? 'active' : ''); ?>"
                         data-filter-name="source" data-filter-value="<?php echo e($tabName); ?>"
                         data-container-id="tableListContainer"
                    >
                        <?php echo e(svg("iconsax-lin-{$tabIcon}", ['width' => '20px', 'height' => '20px', 'class' => 'icons'])); ?>

                        <span class="ml-4"><?php echo e(trans("update.{$tabName}_certificates")); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>


            
            <?php echo $__env->make('design_1.panel.certificates.my_achievements.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/certificates/my-achievements">

                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('update.certificate_title')); ?></th>
                        <th><?php echo e(trans('update.certificate_id')); ?></th>
                        <th class="js-specific-th js-quiz-th"><?php echo e(trans('quiz.minimum_grade')); ?></th>
                        <th class="js-specific-th js-quiz-th"><?php echo e(trans('quiz.my_grade')); ?></th>
                        <th class="js-specific-th js-quiz-th"><?php echo e(trans('update.total_grade')); ?></th>
                        <th><?php echo e(trans('update.last_certificate')); ?></th>
                        <th class="text-right"><?php echo e(trans('controls')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-table-tbody-lists">
                    <?php $__currentLoopData = $certificatesItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificateItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.certificates.my_achievements.quiz_item_table',['quizItem' => $certificateItem], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            'title' => trans('quiz.my_certificates_no_result'),
            'hint' => nl2br(trans('quiz.my_certificates_no_result_hint')),
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    <script src="/assets/design_1/js/panel/certificates.min.js"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/certificates/my_achievements/index.blade.php ENDPATH**/ ?>