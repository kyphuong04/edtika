<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark"><?php echo e(trans('update.open_meetings')); ?></h4>

    <?php if(!empty($openMeetings['totalMeetings'])): ?>
        <a href="/panel/meetings/reservation" target="_blank" class="">
            <div class="d-flex align-items-center justify-content-between p-12 rounded-16 bg-gray-100 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-profile-2user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-weight-bold text-dark"><?php echo e($openMeetings['totalMeetings']); ?></span>
                        <span class="d-block font-12 text-gray-500 mt-4"><?php echo e(trans('update.open_meetings')); ?></span>
                    </div>
                </div>

                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>
        </a>

        
        <?php $__currentLoopData = $openMeetings['reserveMeetings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $openReserveMeeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-gray-100 rounded-16 p-12 mt-16">
                <div class="d-flex align-items-center justify-content-between bg-white p-12 rounded-12">
                    <div class="d-flex align-items-center">
                        <div class="size-48 rounded-circle bg-gray-100">
                            <img src="<?php echo e($openReserveMeeting->meeting->creator->getAvatar()); ?>" alt="" class="rounded-circle img-cover">
                        </div>
                        <div class="ml-8">
                            <h6 class="font-14 text-dark"><?php echo e(truncate($openReserveMeeting->meeting->creator->full_name, 28)); ?></h6>
                            <div class="d-flex align-items-center gap-8 font-12 text-gray-500 mt-4">
                                <span class=""><?php echo e(dateTimeFormat($openReserveMeeting->start_at, 'j M Y')); ?></span>

                                <div class="d-flex align-items-center font-12 text-gray-500">
                                    <span class=""><?php echo e(dateTimeFormat($openReserveMeeting->start_at, 'H:i')); ?></span>
                                    <span class="mx-2">-</span>
                                    <span class=""><?php echo e(dateTimeFormat($openReserveMeeting->end_at, 'H:i')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-16">
                    <div class="d-flex align-items-center gap-16">
                        <?php if($openReserveMeeting->student_count > 1): ?>
                            <div class="d-flex-center p-8 rounded-8 bg-gray-200 font-12 text-gray-500"><?php echo e(trans('update.group')); ?></div>
                        <?php endif; ?>

                        <div class="d-flex-center p-8 rounded-8 bg-gray-200 font-12 text-gray-500"><?php echo e(trans('update.'.$openReserveMeeting->meeting_type)); ?></div>
                    </div>

                    <div class="d-flex align-items-center gap-16">
                        <a href="<?php echo e($openReserveMeeting->addToCalendarLink()); ?>" target="_blank" class="d-flex-center size-40 rounded-circle bg-gray-200 bg-hover-gray-300"
                           data-tippy-content="<?php echo e(trans('public.add_to_calendar')); ?>"
                        >
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-notification-bing'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </a>

                        <div class="js-join-to-meeting-session d-flex-center size-40 rounded-circle bg-gray-200 bg-hover-gray-300 cursor-pointer"
                             data-tippy-content="<?php echo e(trans('footer.join')); ?>"
                             data-path="/panel/meetings/<?php echo e($openReserveMeeting->id); ?>/join-modal"
                        >
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>

                    </div>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        

    <?php else: ?>
        
        <div class="d-flex-center flex-column text-center mt-20 border-dashed border-gray-200 bg-gray-100 p-32 rounded-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>
            <h5 class="font-14 text-dark mt-12"><?php echo e(trans('update.no_meeting!')); ?></h5>
            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.you_don’t_have_any_meetings_you_can_find_your_desired_instructor_and_book_a_meeting')); ?></div>
        </div>

        <?php if(!empty($openMeetings['instructors']) and count($openMeetings['instructors'])): ?>
            <div class="d-flex-center m-16">
                
                <?php $__currentLoopData = $openMeetings['instructors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $openMeetingsInstructorKey => $openMeetingsInstructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $extraClass = "";
                        $isSecondItem = $openMeetingsInstructorKey == 1;

                        if ($openMeetingsInstructorKey != 1) { // Not Second Item
                            $extraClass = "student-dashboard__no-meeting-avatars";

                            if ($openMeetingsInstructorKey == 0) {
                                $extraClass .= " avatar-1";
                            } else {
                                $extraClass .= " avatar-3";
                            }
                        }
                    ?>

                    <div class="d-flex-center <?php echo e($isSecondItem ? ' position-relative z-index-2 size-68' : 'size-48'); ?> bg-gray-100 rounded-circle <?php echo e($extraClass); ?>">
                        <div class="<?php echo e($isSecondItem ? 'size-60' : 'size-40'); ?> rounded-circle">
                            <img src="<?php echo e($openMeetingsInstructor->getAvatar()); ?>" alt="" class="img-cover rounded-circle">
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h6 class="font-14 text-dark"><?php echo e(trans('update.find_an_instructor')); ?></h6>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.book_a_meeting_right_now...')); ?></p>
            </div>

            <a href="/instructor-finder" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </a>
        </div>
    <?php endif; ?>

</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/includes/open_meetings.blade.php ENDPATH**/ ?>