
<?php if(!empty($course->tickets) and count($course->tickets)): ?>
    <div class="mt-16 px-16">
        <h6 class="font-12 font-weight-bold mb-16"><?php echo e(trans('update.select_a_pricing_plan')); ?></h6>

        <?php $__currentLoopData = $course->tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $ticketIsValid = $ticket->isValid();
            ?>

            <div class="course-right-side__price-plan position-relative custom-input-button position-relative mt-12">
                <input class="form-check-input" type="radio"
                       <?php echo e((!$ticketIsValid) ? 'disabled' : ''); ?>

                       data-discount-price="<?php echo e(handlePrice($ticket->getPriceWithDiscount($course->price, !empty($activeSpecialOffer) ? $activeSpecialOffer : null))); ?>"
                       value="<?php echo e(($ticketIsValid) ? $ticket->id : ''); ?>"
                       name="ticket_id"
                       id="courseOff<?php echo e($ticket->id); ?>">

                <label for="courseOff<?php echo e($ticket->id); ?>" class="position-relative d-flex flex-column align-items-start p-16 rounded-12 bg-white <?php echo e((!$ticketIsValid) ? 'disabled' : ''); ?>">
                    <span class="course-price-plan-title font-12 font-weight-bold"><?php echo e($ticket->title); ?> (<?php echo e($ticket->discount); ?>% <?php echo e(trans('public.off')); ?>)</span>
                    <span class="course-price-plan-subtitle font-12 mt-8"><?php echo e($ticket->getSubTitle()); ?></span>
                </label>

                <?php if(!$ticketIsValid): ?>
                    <span class="course-right-side__price-plan-expired px-8 py-4 bg-danger-20 text-danger font-12 rounded-8"><?php echo e(trans('panel.expired')); ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>


<?php if($course->price > 0): ?>
    <?php
        $realPrice = handleCoursePagePrice($course->price);
    ?>

    <div id="priceBox" class="d-flex align-items-end justify-content-center  mt-20 px-16">
        <?php if(!empty($activeSpecialOffer)): ?>
            <div class="d-flex align-items-center text-center mr-16">
                <?php
                    $priceWithDiscount = handleCoursePagePrice($course->getPrice());
                ?>

                <div id="priceWithDiscount" class="d-block font-24 font-weight-bold">
                    <?php echo e($priceWithDiscount['price']); ?>

                </div>

                <?php if(!empty($priceWithDiscount['tax'])): ?>
                    <span class="d-block font-12 text-gray-500 ml-4">+ <?php echo e($priceWithDiscount['tax']); ?> <?php echo e(trans('cart.tax')); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="d-flex align-items-center text-center">
            <div id="realPrice"
                 data-value="<?php echo e($course->price); ?>"
                 data-special-offer="<?php echo e(!empty($activeSpecialOffer) ? $activeSpecialOffer->percent : ''); ?>"
                 class="d-block <?php if(!empty($activeSpecialOffer)): ?> font-14 text-gray-500 text-decoration-line-through <?php else: ?> font-24 font-weight-bold <?php endif; ?>">
                <?php echo e($realPrice['price']); ?>

            </div>

            <?php if(!empty($realPrice['tax']) and empty($activeSpecialOffer)): ?>
                <span class="d-block font-12 text-gray-500 ml-4">+ <?php echo e($realPrice['tax']); ?> <?php echo e(trans('cart.tax')); ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="d-flex align-items-center justify-content-center mt-20 px-16">
        <span class="font-24 font-weight-bold"><?php echo e(trans('public.free')); ?></span>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/includes/rightSide/price.blade.php ENDPATH**/ ?>