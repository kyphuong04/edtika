<?php
    $canSale = ($course->canSale() and !$hasBought);
    $authUserJoinedWaitlist = false;

    if (!empty($authUser)) {
        $authUserWaitlist = $course->waitlists()->where('user_id', $authUser->id)->first();
        $authUserJoinedWaitlist = !empty($authUserWaitlist);
    }
?>

<div class="js-enroll-actions-card mt-20 d-flex flex-column px-16">
    <?php if(!$canSale and $course->canJoinToWaitlist()): ?>
        <button type="button" class="btn btn-block btn-primary btn-lg <?php echo e((!$authUserJoinedWaitlist) ? 'js-join-waitlist-user' : 'disabled'); ?>" <?php echo e($authUserJoinedWaitlist ? 'disabled' : ''); ?> data-path="/course/<?php echo e($course->slug); ?>/waitlists/get-modal">
            <?php if($authUserJoinedWaitlist): ?>
                <?php echo e(trans('update.already_joined')); ?>

            <?php else: ?>
                <?php echo e(trans('update.join_waitlist')); ?>

            <?php endif; ?>
        </button>
    <?php elseif($hasBought or !empty($course->getInstallmentOrder())): ?>
        <a href="<?php echo e($course->getLearningPageUrl()); ?>" class="btn btn-block btn-primary btn-lg"><?php echo e(trans('update.go_to_learning_page')); ?></a>
    <?php elseif(!empty($course->price) and $course->price > 0): ?>
        <button type="button" class="btn btn-block btn-primary btn-lg <?php echo e($canSale ? 'js-course-add-to-cart-btn' : ($course->cantSaleStatus($hasBought) .' disabled ')); ?>">
            <?php if(!$canSale): ?>
                <?php if($course->checkCapacityReached()): ?>
                    <?php echo e(trans('update.capacity_reached')); ?>

                <?php else: ?>
                    <?php echo e(trans('update.disabled_add_to_cart')); ?>

                <?php endif; ?>
            <?php else: ?>
                <?php echo e(trans('public.add_to_cart')); ?>

            <?php endif; ?>
        </button>

        <?php if($canSale and !empty($course->points)): ?>
            <a href="<?php echo e(!(auth()->check()) ? '/login' : '#'); ?>" class="<?php echo e((auth()->check()) ? 'js-buy-with-point' : ''); ?> btn btn-outline-warning btn-block btn-lg mt-14 <?php echo e((!$canSale) ? 'disabled' : ''); ?>" rel="nofollow" data-path="/course/<?php echo e($course->slug); ?>/points/get-modal">
                <?php echo trans('update.buy_with_n_points',['points' => $course->points]); ?>

            </a>
        <?php endif; ?>

        <?php if($canSale and !empty(getFeaturesSettings('direct_classes_payment_button_status'))): ?>
            <button type="button" class="btn btn-outline-accent btn-block btn-lg mt-14 js-course-direct-payment">
                <?php echo e(trans('update.buy_now')); ?>

            </button>
        <?php endif; ?>

        <?php if(!empty($installments) and count($installments) and getInstallmentsSettings('display_installment_button')): ?>
            <a href="/course/<?php echo e($course->slug); ?>/installments" class="btn btn-outline-primary btn-block btn-lg mt-14">
                <?php echo e(trans('update.pay_with_installments')); ?>

            </a>
        <?php endif; ?>
    <?php else: ?>
        <a href="<?php echo e($canSale ? '/course/'. $course->slug .'/free' : '#'); ?>" class="btn btn-primary btn-block btn-lg <?php echo e((!$canSale) ? (' disabled ' . $course->cantSaleStatus($hasBought)) : ''); ?>">
            <?php if(!$canSale): ?>
                <?php if($course->checkCapacityReached()): ?>
                    <?php echo e(trans('update.capacity_reached')); ?>

                <?php else: ?>
                    <?php echo e(trans('public.disabled')); ?>

                <?php endif; ?>
            <?php else: ?>
                <?php echo e(trans('public.enroll_on_webinar')); ?>

            <?php endif; ?>
        </a>
    <?php endif; ?>

    <?php if($canSale and $course->subscribe): ?>
        <a href="/subscribes/apply/<?php echo e($course->slug); ?>" class="btn btn-outline-accent btn-block btn-lg mt-14 <?php if(!$canSale): ?> disabled <?php endif; ?>"><?php echo e(trans('public.subscribe')); ?></a>
    <?php endif; ?>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/includes/rightSide/enroll_form.blade.php ENDPATH**/ ?>