<?php if(!empty($activeSubscribe)): ?>
    <div class="student-dashboard__active-subscribe-card p-16 rounded-24">
        <h3 class="font-16 text-white"><?php echo e($activeSubscribe->title); ?></h3>
        <p class="font-12 text-white mt-8 opacity-75"><?php echo e(trans('update.active_subscription')); ?></p>

        <div class="mt-16">
            <div class="d-flex align-items-center gap-4 font-12 text-white">
                <span class="font-weight-bold"><?php echo e($activeSubscribe->remained_days); ?></span>
                <span class=""><?php echo e(trans('update.remaining_days')); ?></span>
            </div>

            <div class="progress-card d-flex bg-white mt-8">
                <div class="progress-bar bg-primary" style="width: <?php echo e($activeSubscribe->remained_days_percent); ?>%"></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="student-dashboard__no-subscribe-card p-16 rounded-24">
        <h3 class="font-16 text-white"><?php echo e(trans('update.upgrade_to_pro!')); ?></h3>
        <p class="font-12 text-white mt-8 opacity-75"><?php echo nl2br(trans('update.purchase_a_subscription_plan_and_get_courses_for_free')); ?></p>

        <a href="/panel/financial/subscribes" target="_blank" class="d-inline-flex-center px-16 py-8 rounded-32 bg-white text-primary mt-16"><?php echo e(trans('update.upgrade')); ?></a>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/includes/subscribe_plan.blade.php ENDPATH**/ ?>