<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php echo $__env->make('design_1.panel.quizzes.results.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('design_1.panel.quizzes.results.pending_reviews', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(!empty($quizzesResults) and !$quizzesResults->isEmpty()): ?>
        <div class="bg-white pt-16 rounded-24 mt-28">
            <div class="px-16 mb-24 pb-16 border-bottom-gray-200">
                <h2 class="font-16 text-dark"><?php echo e(trans("update.student_results")); ?></h2>
                <p class="mt-4 text-gray-500"><?php echo e(trans('update.view_and_manage_your_students_quiz_results')); ?></p>
            </div>

            
            <?php echo $__env->make('design_1.panel.quizzes.results.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/quizzes/results">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('quiz.student')); ?></th>
                        <th class="text-left"><?php echo e(trans('quiz.quiz')); ?></th>
                        <th class="text-center"><?php echo e(trans('update.total_grade')); ?></th>
                        <th class="text-center"><?php echo e(trans('update.pass_grade')); ?></th>
                        <th class="text-center"><?php echo e(trans('quiz.student_grade')); ?></th>
                        <th class="text-center"><?php echo e(trans('quiz.attempts')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.date')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.status')); ?></th>
                        <th class="text-right"><?php echo e(trans('public.controls')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-body-lists">
                    <?php $__currentLoopData = $quizzesResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quizResultRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.quizzes.results.item_table', ['quizResult' => $quizResultRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-body-lists">
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
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/results/index.blade.php ENDPATH**/ ?>