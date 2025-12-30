<div class="row">
    <div class="col-12 col-lg-6">
        
        <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.hello_box', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <div class="mt-128">
            <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.courses_overview', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        
        <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.sales_overview', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.pending_student_assignments', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        
        <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.registration_plan', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.current_balance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.noticeboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.support_messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.visitors_statistics', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        
        <?php echo $__env->make('design_1.panel.dashboard.student.includes.events_calendar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php if($authUser->isTeacher()): ?>
            
            <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.upcoming_live_sessions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.review_student_quizzes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.open_meetings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php else: ?>
            

            
            <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.top_instructors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <?php echo $__env->make('design_1.panel.dashboard.instructor.includes.top_students', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/instructor/index.blade.php ENDPATH**/ ?>