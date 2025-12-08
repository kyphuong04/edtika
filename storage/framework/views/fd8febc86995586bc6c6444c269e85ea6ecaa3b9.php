<div class="student-dashboard__hello-box bg-primary p-16 rounded-24 mt-54 w-100">

    <div class="row">
        <div class="col-12 col-lg-6">
            <h1 class="font-24 font-weight-bold text-white text-ellipsis"><?php echo e(trans('update.hello_user', ['user' => $authUser->full_name])); ?> 👋</h1>
            <p class="mt-8 font-14 text-white opacity-75 text-ellipsis"><?php echo e(trans('update.welcome_and_let’s_start_effective_education_today!')); ?></p>

            <div class="row mt-24">
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-weight-bold text-white"><?php echo e($helloBox['coursesCount']); ?></span>
                            <span class="d-block mt-4 text-white opacity-75 text-ellipsis"><?php echo e(trans('update.courses')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-weight-bold text-white"><?php echo e($helloBox['meetingsCount']); ?></span>
                            <span class="d-block mt-4 text-white opacity-75 text-ellipsis"><?php echo e(trans('panel.meetings')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-24">
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-award'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-weight-bold text-white"><?php echo e($helloBox['certificatesCount']); ?></span>
                            <span class="d-block mt-4 text-white opacity-75 text-ellipsis"><?php echo e(trans('panel.certificates')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-weight-bold text-white"><?php echo e($helloBox['passedQuizCount']); ?></span>
                            <span class="d-block mt-4 text-white opacity-75 text-ellipsis"><?php echo e(trans('update.passed_quizzes')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 position-relative d-none d-lg-block">
            <div class="hello-box-user-vector d-flex justify-content-end">
                <img src="/assets/design_1/img/panel/dashboard/student/hello-box-user-vector.svg" alt="" class="img-fluid">
            </div>
        </div>
    </div>

    <div class="mt-52">
        <?php if(!empty($helloBox['continueLearningCourses']) and count($helloBox['continueLearningCourses'])): ?>
            <h4 class="font-14 font-weight-bold text-white"><?php echo e(trans('update.continue_learning')); ?></h4>

            <div class="row">
                <?php $__currentLoopData = $helloBox['continueLearningCourses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $continueLearningCourse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="student-dashboard__hello-box-course-col col-12 col-lg-6 mt-12">
                        <a href="<?php echo e($continueLearningCourse->getLearningPageUrl()); ?>" target="_blank" class="d-block">
                            <div class="card-with-mask position-relative">
                                <div class="mask-8-white"></div>
                                <div class="position-relative z-index-2 bg-white py-16 rounded-16">
                                    <div class="d-flex align-items-center px-16 mb-16">
                                        <div class="size-40 bg-gray-100 rounded-8">
                                            <img src="<?php echo e($continueLearningCourse->getIcon()); ?>" alt="" class="img-cover rounded-8">
                                        </div>
                                        <div class="ml-8">
                                            <h6 class="font-12 text-dark text-ellipsis"><?php echo e(truncate($continueLearningCourse->title, 30)); ?></h6>
                                            <div class="font-12 mt-4 text-gray-500"><?php echo e(dateTimeFormat($continueLearningCourse->created_at, 'j M Y')); ?></div>
                                        </div>
                                    </div>

                                    <?php
                                        $continueLearningCourseProgress = $continueLearningCourse->getProgress(true);
                                    ?>

                                    <div class="mb-12 px-16">
                                        <div class="progress-card d-flex bg-gray-100">
                                            <div class="progress-bar bg-primary" style="width: <?php echo e($continueLearningCourseProgress); ?>%"></div>
                                        </div>

                                        <div class="d-flex align-items-center gap-4 mt-8 font-12">
                                            <span class="font-weight-bold text-dark"><?php echo e($continueLearningCourseProgress); ?>%</span>
                                            <span class="text-gray-500"><?php echo e(trans('update.completed')); ?></span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between px-16 pt-16 border-top-gray-200">
                                        <div class="d-flex align-items-center gap-4 font-12">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
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
                                            <span class="font-weight-bold text-dark"><?php echo e($continueLearningCourse->getAllLessonsCount()); ?></span>
                                            <span class="text-gray-500"><?php echo e(trans('update.lessons')); ?></span>
                                        </div>

                                        <div class="d-flex align-items-center text-gray-500 gap-4">
                                            <span class="font-12 font-weight-bold"><?php echo e(trans('update.continue_learning')); ?></span>
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
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php elseif(!empty($helloBox['enrollOnCoursesFromAdmin']) and count($helloBox['enrollOnCoursesFromAdmin'])): ?>
            <h4 class="font-14 font-weight-bold text-white"><?php echo e(trans('update.enroll_on_course')); ?></h4>

            <div class="row mt-12">
                <?php $__currentLoopData = $helloBox['enrollOnCoursesFromAdmin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollOnCourseFromAdmin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6">
                        <a href="<?php echo e($enrollOnCourseFromAdmin->getUrl()); ?>" target="_blank" class="d-block">
                            <div class="card-with-mask position-relative">
                                <div class="mask-8-white"></div>
                                <div class="position-relative z-index-2 bg-white py-16 rounded-16">
                                    <div class="d-flex align-items-center px-16 mb-16">
                                        <div class="size-40 bg-gray-100 rounded-8">
                                            <img src="<?php echo e($enrollOnCourseFromAdmin->getIcon()); ?>" alt="" class="img-cover rounded-8">
                                        </div>
                                        <div class="ml-8">
                                            <h6 class="font-12 text-dark text-ellipsis"><?php echo e(truncate($enrollOnCourseFromAdmin->title, 30)); ?></h6>
                                            <div class="font-12 mt-4 text-gray-500"><?php echo e($enrollOnCourseFromAdmin->teacher->full_name); ?></div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between px-16 pt-16 border-top-gray-200">
                                        <div class="d-flex align-items-center gap-4 font-12">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
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
                                            <span class="font-weight-bold text-dark"><?php echo e($enrollOnCourseFromAdmin->getAllLessonsCount()); ?></span>
                                            <span class="text-gray-500"><?php echo e(trans('update.lessons')); ?></span>
                                        </div>

                                        <div class="d-flex align-items-center text-gray-500 gap-4">
                                            <span class="font-12 font-weight-bold"><?php echo e(trans('update.enroll')); ?></span>
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
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/includes/hello_box.blade.php ENDPATH**/ ?>