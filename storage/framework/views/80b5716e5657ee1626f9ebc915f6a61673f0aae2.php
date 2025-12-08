<?php if(!empty($topMentorsInstructors) and count($topMentorsInstructors)): ?>
    <div class="position-relative bg-primary rounded-24 py-16">
        <div class="instructor-finder__top-mentors-mask"></div>

        <div class="position-relative z-index-2">

            <div class="px-16">
                <h5 class="instructor-finder__filters-title filters-title-white font-14 font-weight-bold text-white"><?php echo e(trans('update.top_mentors')); ?></h5>
            </div>

            <div class="swiper-container js-make-swiper top-mentors-instructors pb-0"
                 data-item="top-mentors-instructors"
                 data-autoplay="true"
                 data-breakpoints="1440:1,769:1,320:1"
                 data-navigation="true"
            >
                <div class="swiper-button-next instructor-finder__top-mentors-slider-navigation rounded-circle bg-white-20">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>

                <div class="swiper-button-prev instructor-finder__top-mentors-slider-navigation rounded-circle bg-white-20">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>

                <div class="swiper-wrapper py-32">
                    <?php $__currentLoopData = $topMentorsInstructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topMentorInstructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide">
                            <div class="position-relative d-flex-center flex-column text-center">

                                <a href="<?php echo e($topMentorInstructor->getProfileUrl()); ?>" class="">
                                    <div class="instructor-finder__top-mentors-avatar size-80 rounded-circle">
                                        <img src="<?php echo e($topMentorInstructor->getAvatar(80)); ?>" alt="<?php echo e($topMentorInstructor->full_name); ?>" class="position-relative img-cover rounded-circle">
                                    </div>
                                </a>

                                <a href="<?php echo e($topMentorInstructor->getProfileUrl()); ?>" class="">
                                    <h6 class="mt-28 font-16 font-weight-bold text-white"><?php echo e($topMentorInstructor->full_name); ?></h6>
                                </a>

                                <div class="position-relative mt-16 d-flex align-items-center w-100">
                                    <div class="d-flex-center flex-column text-center flex-1">
                                        <div class="font-14 font-weight-bold text-white"><?php echo e($topMentorInstructor->total_meetings ?? 0); ?></div>
                                        <div class="mt-4 font-12 text-white"><?php echo e(trans('panel.total_meetings')); ?></div>
                                    </div>

                                    <div class="instructor-finder__top-mentors-divider"></div>

                                    <div class="d-flex-center flex-column text-center flex-1">
                                        <div class="font-14 font-weight-bold text-white"><?php echo e(!empty($topMentorInstructor->meeting_hours) ? $topMentorInstructor->meeting_hours : 0); ?></div>
                                        <div class="mt-4 font-12 text-white"><?php echo e(trans('update.meeting_hours')); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/instructor_finder/lists/left_side/top_mentors.blade.php ENDPATH**/ ?>