<div class="bg-white p-16 rounded-24 w-100">
    <h4 class="font-14 font-weight-bold text-dark"><?php echo e(trans('update.courses_overview')); ?></h4>

    <?php if(!empty($coursesOverview['courses']) and count($coursesOverview['courses'])): ?>
        <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16">
            
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12"><?php echo e(trans('update.total_courses')); ?></span>
                    <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($coursesOverview['totalCourses']); ?></span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
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

            
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12"><?php echo e(trans('update.completed_courses')); ?></span>
                    <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($coursesOverview['completedCourses']); ?></span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-success-40">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
            </div>

            
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12"><?php echo e(trans('update.open_courses')); ?></span>
                    <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($coursesOverview['openCourses']); ?></span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-warning-40">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-more-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
            </div>
        </div>

        
        <?php $__currentLoopData = $coursesOverview['courses']->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $overviewBoxCourse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($overviewBoxCourse->getLearningPageUrl()); ?>" target="_blank" class="d-block">
                <div class="row align-items-center mt-24">
                    <div class="col-12 col-lg-5">
                        <div class="d-flex align-items-center flex-1">
                            <div class="size-48 rounded-12 bg-gray-100">
                                <img src="<?php echo e($overviewBoxCourse->getIcon()); ?>" alt="" class="img-cover rounded-12">
                            </div>
                            <div class="ml-8">
                                <h5 class="font-14 text-dark text-ellipsis d-none d-lg-block"><?php echo e(truncate($overviewBoxCourse->title, 23)); ?></h5>
                                <h5 class="font-14 text-dark text-ellipsis d-block d-lg-none"><?php echo e(truncate($overviewBoxCourse->title, 45)); ?></h5>
                                <span class="d-block mt-4 font-12 text-gray-500"><?php echo e(trans('public.by')); ?> <?php echo e($overviewBoxCourse->teacher->full_name); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php
                        $overviewBoxCourseProgress = $overviewBoxCourse->getProgress(true);
                        $overviewBoxCourseQuizzes = $overviewBoxCourse->getQuizzesLearningProgressStat();
                        $overviewBoxCourseAssignments = $overviewBoxCourse->getAssignmentsLearningProgressStat();
                    ?>

                    <div class="col-lg-4 d-none d-lg-block">
                        <div class="d-flex align-items-center gap-4 font-12">
                            <span class="font-weight-bold text-dark"><?php echo e($overviewBoxCourseProgress); ?>%</span>
                            <span class="text-gray-500"><?php echo e(trans('update.progress')); ?></span>
                        </div>

                        <div class="progress-card d-flex bg-gray-100 mt-8">
                            <div class="progress-bar bg-primary" style="width: <?php echo e($overviewBoxCourseProgress); ?>%"></div>
                        </div>
                    </div>

                    <div class="col-lg-3 d-none d-lg-block">
                        <div class="d-flex align-items-center justify-content-end">
                            
                            <?php if(!empty($overviewBoxCourseQuizzes['count'])): ?>
                                <div class="d-flex align-items-center gap-4">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="font-12 text-gray-500"><?php echo e($overviewBoxCourseQuizzes['passed']); ?>/<?php echo e($overviewBoxCourseQuizzes['count']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($overviewBoxCourseQuizzes['count']) and !empty($overviewBoxCourseAssignments['count'])): ?>
                                <div class="size-4 bg-gray-300 rounded-circle mx-12"></div>
                            <?php endif; ?>

                            
                            <?php if(!empty($overviewBoxCourseAssignments['count'])): ?>
                                <div class="d-flex align-items-center gap-4">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="font-12 text-gray-500"><?php echo e($overviewBoxCourseAssignments['passed']); ?>/<?php echo e($overviewBoxCourseAssignments['count']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php else: ?>
        
        <div class="bg-gray-100 border-dashed border-gray-200 rounded-16 mt-16">
            <div class="d-flex-center flex-column text-center pt-32 pb-28">
                <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
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

                <h4 class="mt-12 font-14 text-dark"><?php echo e(trans('update.no_course!')); ?></h4>
                <div class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.you_don’t_have_any_upcoming_live_session_you_can_find_your_desired_live_course')); ?></div>
            </div>

            <?php if(!empty($coursesOverview['overviewCoursesFromAdmin']) and count($coursesOverview['overviewCoursesFromAdmin'])): ?>
                <div class="d-grid grid-columns-2 gap-16 px-16 pb-24">
                    <?php $__currentLoopData = $coursesOverview['overviewCoursesFromAdmin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $overviewCourseFromAdmin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($overviewCourseFromAdmin->getUrl()); ?>" target="_blank" class="text-decoration-none">
                            <div class="card-with-mask position-relative">
                                <div class="mask-8-white"></div>
                                <div class="position-relative z-index-2 bg-white py-16 rounded-16">
                                    <div class="d-flex align-items-center px-16">
                                        <div class="size-40 rounded-8 bg-gray-100">
                                            <img src="<?php echo e($overviewCourseFromAdmin->getIcon()); ?>" alt="" class="img-cover rounded-8">
                                        </div>
                                        <div class="ml-8">
                                            <h6 class="font-12 text-dark text-ellipsis"><?php echo e(truncate($overviewCourseFromAdmin->title, 23)); ?></h6>

                                            <?php echo $__env->make('design_1.web.components.rate', [
                                                'rate' => $overviewCourseFromAdmin->getRate(),
                                                'rateCount' => $overviewCourseFromAdmin->getRateCount(),
                                                'rateClassName' => 'mt-8',
                                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                    </div>

                                    <?php
                                        $overviewCourseFromAdminCreator = $overviewCourseFromAdmin->creator;
                                    ?>

                                    <div class="d-flex align-items-center justify-content-between px-16 pt-16 mt-16 border-top-gray-200">
                                        <div class="d-flex align-items-center">
                                            <div class="size-32 rounded-circle bg-gray-100">
                                                <img src="<?php echo e($overviewCourseFromAdminCreator->getAvatar(32)); ?>" alt="" class="img-cover rounded-circle">
                                            </div>
                                            <div class="ml-8">
                                                <h5 class="font-12 text-dark"><?php echo e($overviewCourseFromAdminCreator->full_name); ?></h5>
                                                <span class="d-block font-12 text-gray-500 mt-2"><?php echo e($overviewCourseFromAdminCreator->role->caption); ?></span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center text-gray-500 gap-4">
                                            <span class="font-12 font-weight-bold"><?php echo e(trans('update.enroll_now')); ?></span>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h5 class="font-14 text-dark"><?php echo e(trans('update.need_more_courses')); ?></h5>
                <div class="mt-2 font-12 text-gray-500"><?php echo e(trans('update.explore_all_courses_and_find_a_professional_course')); ?></div>
            </div>

            <a href="/classes" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
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
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/includes/courses_overview.blade.php ENDPATH**/ ?>