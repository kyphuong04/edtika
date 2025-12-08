<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('design_1.panel.quizzes.my_results.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    
    <?php if(!empty($pendingQuizzes) and count($pendingQuizzes)): ?>
        <?php echo $__env->make('design_1.panel.quizzes.my_results.pending', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>


    <?php if(!empty($quizzesResults) and !$quizzesResults->isEmpty()): ?>
         <div class="bg-white pt-16 rounded-24 mt-28">

             <div class="px-16 mb-24 pb-16 border-bottom-gray-200">
                <h3 class="font-16"><?php echo e(trans('update.my_results')); ?></h3>
                <p class="mt-4 text-gray-500"><?php echo e(trans('update.view_and_manage_your_quiz_results')); ?></p>
            </div>

            
            <?php echo $__env->make('design_1.panel.quizzes.my_results.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            
             <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/quizzes/my-results">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('update.instructor')); ?></th>
                        <th class="text-left"><?php echo e(trans('quiz.quiz')); ?></th>
                        <th class="text-center"><?php echo e(trans('quiz.quiz_grade')); ?></th>
                        <th class="text-center"><?php echo e(trans('quiz.student_grade')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.status')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.date')); ?></th>
                        <th class="text-right"><?php echo e(trans('public.controls')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">

                    <?php $__currentLoopData = $quizzesResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.quizzes.my_results.table_items', ['quizResult' => $result], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            'file_name' => 'my_results.svg',
            'title' => trans('quiz.quiz_result_no_result'),
            'hint' => trans('quiz.quiz_result_no_result_hint'),
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>

    <script src="/assets/design_1/js/panel/quiz_list.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/my_results/index.blade.php ENDPATH**/ ?>