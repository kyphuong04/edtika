<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('design_1.panel.quizzes.lists.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(!empty($quizzes) and !$quizzes->isEmpty()): ?>
        <div class="bg-white pt-16 rounded-24 mt-20">
             <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16"><?php echo e(trans('quiz.quizzes')); ?></h3>
                    <p class="font-14 text-gray-500 mt-4"><?php echo e(trans('update.manage_all_quizzes_in_a_single_place')); ?></p>
                </div>
            </div>

            
            <?php echo $__env->make('design_1.panel.quizzes.lists.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/quizzes">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('public.title')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.questions')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.time')); ?> <span class="braces">(<?php echo e(trans('public.min')); ?>)</span></th>
                        <th class="text-center"><?php echo e(trans('public.total_mark')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.pass_mark')); ?></th>
                        <th class="text-center"><?php echo e(trans('quiz.students')); ?></th>
                        
                        <th class="text-center"><?php echo e(trans('public.status')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.date_created')); ?></th>
                        <th class="text-right"><?php echo e(trans('public.controls')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    <?php $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.quizzes.lists.table_items', ['quiz' => $quiz], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            'file_name' => 'quizzes.svg',
            'title' => trans('quiz.quiz_no_result'),
            'hint' => nl2br(trans('quiz.quiz_no_result_hint')),
            'btn' => ['url' => '/panel/quizzes/new','text' => trans('quiz.create_a_quiz')]
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/lists/index.blade.php ENDPATH**/ ?>