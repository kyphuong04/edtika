

<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('design_1.panel.my_students.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="bg-white pt-16 pb-16 rounded-24 mt-20">
        <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
            <div class="">
                <h3 class="font-16"><?php echo e(trans('update.my_students')); ?></h3>
            </div>
        </div>

        <?php echo $__env->make('design_1.panel.my_students.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    
    <?php if(!empty($students) and !$students->isEmpty()): ?>
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="table-responsive-lg">
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
                    <tbody>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.my_students.table_item', ['user' => $student], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                
                <div class="card-footer text-center">
                    <?php echo e($students->appends(request()->input())->links()); ?>

                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="mt-20">
            <?php echo $__env->make('design_1.panel.includes.no-result',[
                'file_name' => 'students.svg',
                'title' => trans('panel.students_no_result'),
                'hint' => trans('panel.no_students_enrolled_hint'),
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/my_students/index.blade.php ENDPATH**/ ?>