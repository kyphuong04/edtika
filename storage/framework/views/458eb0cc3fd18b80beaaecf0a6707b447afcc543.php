<div class="row">
    <div class="col-12 col-lg-6">
        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.hello_box', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <div class="<?php echo e((!empty($helloBox['continueLearningCourses']) and count($helloBox['continueLearningCourses'])) ? 'mt-128' : 'mt-84'); ?>">
            <?php echo $__env->make('design_1.panel.dashboard.student.includes.courses_overview', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.my_assignments', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.learning_activity', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.subscribe_plan', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.current_balance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.noticeboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.support_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.my_quizzes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.events_calendar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.upcoming_live_sessions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.open_meetings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    </div>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/index.blade.php ENDPATH**/ ?>