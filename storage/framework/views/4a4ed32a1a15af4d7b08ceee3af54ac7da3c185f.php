<div class="bg-white py-16 rounded-24 w-100 mt-24">
    <div class="px-16">
        <h4 class="font-14 font-weight-bold text-dark"><?php echo e(trans('update.learning_activity')); ?></h4>
    </div>

    <?php if(!empty($learningActivity['haveLearningActivity'])): ?>

        <?php if(!empty($learningActivity['activityStats'])): ?>
            <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16 px-16">
                
                <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                    <div class="d-flex flex-column pt-8">
                        <span class="text-gray-500 font-12"><?php echo e(trans('update.daily_time')); ?></span>
                        <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($learningActivity['activityStats']['today']); ?></span>
                    </div>

                    <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-timer'); ?>
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
                        <span class="text-gray-500 font-12"><?php echo e(trans('update.month_time')); ?></span>
                        <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($learningActivity['activityStats']['month']); ?></span>
                    </div>

                    <div class="d-flex-center size-48 rounded-12 bg-success-40">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-timer-1'); ?>
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
                        <span class="text-gray-500 font-12"><?php echo e(trans('update.total_time')); ?></span>
                        <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($learningActivity['activityStats']['year']); ?></span>
                    </div>

                    <div class="d-flex-center size-48 rounded-12 bg-warning-40">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clock-1'); ?>
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
        <?php endif; ?>

        
        <div class="px-16 mt-24 pt-24 border-top-gray-100">

            <?php $__env->startPush('scripts_bottom'); ?>
                <script>
                    var learningActivityChartLabels = <?php echo json_encode($learningActivity['learningActivityChart']['labels'], 15, 512) ?>;
                    var learningActivityChartData = <?php echo json_encode($learningActivity['learningActivityChart']['data'], 15, 512) ?>;
                </script>
            <?php $__env->stopPush(); ?>

            
            <div id="learningActivityChart" class="student-dashboard__learning-activity-chart"></div>

            
            <?php if(!empty($learningActivity['topActivityCourses']) and count($learningActivity['topActivityCourses'])): ?>
                <div class="mt-20">
                    <h5 class="font-14 text-dark"><?php echo e(trans('update.top_learnings')); ?></h5>

                    <div class="d-grid grid-columns-2 gap-16 mt-16">
                        <?php $__currentLoopData = $learningActivity['topActivityCourses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topActivityCourse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($topActivityCourse->getLearningPageUrl()); ?>" target="_blank" class="text-decoration-none">
                                <div class="d-flex align-items-center rounded-12 bg-gray-100 p-16">
                                    <div class="size-48 rounded-8 bg-gray-100">
                                        <img src="<?php echo e($topActivityCourse->getIcon()); ?>" alt="" class="img-cover rounded-8">
                                    </div>
                                    <div class="ml-8">
                                        <span class="font-weight-bold text-dark"><?php echo e(truncate($topActivityCourse->title, 25)); ?></span>
                                        <div class="font-12 text-gray-500 mt-4"><?php echo e($topActivityCourse->total_time_spent); ?> <?php echo e(trans('public.minutes')); ?></div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    <?php else: ?>
        
        <div class="px-16">

            <div class="d-flex-center flex-column text-center p-60 rounded-16 border-dashed border-gray-200 bg-gray-100 mt-16">
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
                <h5 class="mt-12 font-14 text-dark"><?php echo e(trans('update.no_activity!')); ?></h5>
                <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.start_learning_today_by_watching_your_purchased_courses_enroll_on_new_courses')); ?></div>

                <div class="d-flex align-items-center gap-8 mt-28 p-8 rounded-16 bg-white">
                    <a href="/panel/courses" class="d-flex-center p-16 rounded-12 border-dashed border-gray-200 bg-white bg-hover-gray-100">
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
                        <span class="text-dark ml-8"><?php echo e(trans('update.my_courses')); ?></span>
                    </a>

                    <a href="/classes" class="d-flex-center p-16 rounded-12 bg-white bg-hover-gray-100">
                        <span class="text-dark mr-8"><?php echo e(trans('update.explore_courses')); ?></span>
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
                    </a>
                </div>
            </div>

            
            <?php if(!empty($learningActivity['continueLearningCourses']) and count($learningActivity['continueLearningCourses'])): ?>
                <div class="mt-20">
                    <h5 class="font-14 text-dark"><?php echo e(trans('update.continue_learning')); ?></h5>

                    <div class="d-grid grid-columns-auto grid-lg-columns-2 gap-16 mt-16">
                        <?php $__currentLoopData = $learningActivity['continueLearningCourses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $learningActivityContinueLearningCourse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($learningActivityContinueLearningCourse->getLearningPageUrl()); ?>" target="_blank" class="text-decoration-none">
                                <div class="d-flex align-items-center rounded-12 bg-gray-100 p-16">
                                    <div class="size-48 rounded-8 bg-gray-100">
                                        <img src="<?php echo e($learningActivityContinueLearningCourse->getIcon()); ?>" alt="" class="img-cover rounded-8">
                                    </div>
                                    <div class="ml-8">
                                        <span class="font-weight-bold text-dark"><?php echo e($learningActivityContinueLearningCourse->title); ?></span>

                                        <div class="progress-card d-flex bg-white mt-8">
                                            <div class="progress-bar bg-primary" style="width: <?php echo e($learningActivityContinueLearningCourse->getProgress(true)); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/includes/learning_activity.blade.php ENDPATH**/ ?>