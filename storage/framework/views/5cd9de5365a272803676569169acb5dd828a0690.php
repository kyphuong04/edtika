<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('design_1.panel.manage.students.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(!empty($users) and !$users->isEmpty()): ?>
        <div class="bg-white pt-16 rounded-24 mt-20">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16"><?php echo e(trans('panel.students_list')); ?></h3>

                </div>
            </div>

            
            <?php echo $__env->make('design_1.panel.manage.students.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/manage/students">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('auth.name')); ?></th>
                        <th class="text-left"><?php echo e(trans('auth.email')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.phone')); ?></th>
                        <th class="text-center"><?php echo e(trans('webinars.webinars')); ?></th>
                        <th class="text-center"><?php echo e(trans('quiz.quizzes')); ?></th>
                        <th class="text-center"><?php echo e(trans('panel.certificates')); ?></th>
                        <th class="text-center"><?php echo e(trans('public.date')); ?></th>
                        <th class="text-center"><?php echo e(trans('update.controls')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.manage.students.table_items', ['user' => $userRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            'file_name' => 'students.svg',
            'title' => trans('panel.students_no_result'),
            'hint' =>  nl2br(trans('panel.students_no_result_hint')),
            'btn' => ['url' => '/panel/manage/students/new','text' => trans('panel.add_an_student')]
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/manage/students/index.blade.php ENDPATH**/ ?>