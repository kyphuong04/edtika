<?php if(!empty($featuredInstructors) and count($featuredInstructors)): ?>
    <div class="position-relative mt-32">
        <div class="swiper-container js-make-swiper top-featured-instructors pb-0"
             data-item="top-featured-instructors"
             data-autoplay="true"
             data-loop="true"
             data-breakpoints="1440:4.8,769:3.4,320:1.4"
        >
            <div class="swiper-wrapper py-0  mx-16 mx-md-32">
                <?php $__currentLoopData = $featuredInstructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featuredInstructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="swiper-slide">
                        <a href="<?php echo e($featuredInstructor->getProfileUrl()); ?>" target="_blank" class="">
                            <div class="d-flex align-items-center bg-gray-100 p-12 rounded-pill overflow-hidden">
                                <div class="size-48 rounded-circle">
                                    <img src="<?php echo e($featuredInstructor->getAvatar(48)); ?>" alt="<?php echo e($featuredInstructor->full_name); ?>" class="img-cover rounded-circle">
                                </div>

                                <div class="ml-8">
                                    <h6 class="font-14 font-weight-bold text-dark text-ellipsis"><?php echo e($featuredInstructor->full_name); ?></h6>

                                    <?php if(!empty($featuredInstructor->meeting) and !empty($featuredInstructor->meeting->meetingTimes) and count($featuredInstructor->meeting->meetingTimes)): ?>
                                        <?php
                                            $featuredInstructorPrice = (!empty($featuredInstructor->meeting)) ? $featuredInstructor->meeting->amount : 0;
                                            $featuredInstructorDiscount = (!empty($featuredInstructorPrice) and !empty($featuredInstructor->meeting) and !empty($featuredInstructor->meeting->discount) and $featuredInstructor->meeting->discount > 0) ? $featuredInstructor->meeting->discount : 0;
                                        ?>

                                        <div class="d-flex align-items-start font-12 text-gray-500 mt-4">
                                            <?php if(!empty($featuredInstructorPrice) and $featuredInstructorPrice > 0): ?>
                                                <div class="d-flex flex-column">
                                                    <span class=""><?php echo e(handlePrice(!empty($featuredInstructorDiscount) ? ($featuredInstructorPrice - ($featuredInstructorPrice * $featuredInstructorDiscount / 100)) : $featuredInstructorPrice)); ?></span>

                                                    <?php if(!empty($featuredInstructorDiscount)): ?>
                                                        <span class=" text-decoration-line-through"><?php echo e(handlePrice($featuredInstructorPrice)); ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <span class="">/<?php echo e(trans('update.hr.')); ?></span>
                                            <?php else: ?>
                                                <span class=""><?php echo e(trans('public.free')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.not_available_for_meeting')); ?></div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/instructor_finder/lists/top_featured_instructors.blade.php ENDPATH**/ ?>