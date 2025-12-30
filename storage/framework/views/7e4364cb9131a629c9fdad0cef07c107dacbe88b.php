<?php if(!empty($registrationPlan)): ?>
    <div class="instructor-dashboard__active-registration-card p-16 rounded-24">
        <h3 class="font-16 text-white"><?php echo e($registrationPlan->title); ?></h3>
        <p class="font-12 text-white mt-8 opacity-75"><?php echo e(trans('update.active_service_package')); ?></p>

        <div class="mt-16">
            <div class="d-flex align-items-center gap-4 font-12 text-white">
                <span class="font-weight-bold"><?php echo e($registrationPlan->days_remained ?? trans('update.unlimited')); ?></span>
                <span class=""><?php echo e(trans('update.remaining_days')); ?></span>
            </div>

            <div class="progress-card d-flex bg-white mt-8">
                <div class="progress-bar bg-primary" style="width: <?php echo e($registrationPlan->remained_days_percent ?? 0); ?>%"></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="instructor-dashboard__no-registration-card p-16 rounded-24">
        <h3 class="font-16 text-white"><?php echo e(trans('update.upgrade_to_pro!')); ?></h3>
        <p class="font-12 text-white mt-8 opacity-75"><?php echo nl2br(trans('update.purchase_a_subscription_plan_and_get_courses_for_free')); ?></p>

        <a href="/panel/financial/registration-packages" target="_blank" class="d-inline-flex-center px-16 py-8 rounded-32 bg-white text-primary mt-16"><?php echo e(trans('update.upgrade')); ?></a>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/instructor/includes/registration_plan.blade.php ENDPATH**/ ?>