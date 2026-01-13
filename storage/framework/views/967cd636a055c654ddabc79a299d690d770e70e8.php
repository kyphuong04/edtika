<div id="learningPageSidebar" class="learning-page__sidebar">
    <div class="learning-page__sidebar-header px-16 border-bottom-gray-200">
        
        <div class="d-block d-lg-none">
            <?php echo $__env->make('design_1.web.courses.learning_page.includes.top_header.course_tools', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>


        <div class="js-toggle-show-learning-page-sidebar-drawer cursor-pointer">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons close-icon text-gray-500','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>
    </div>

    <div class="learning-page__sidebar-content py-12" data-simplebar <?php if((!empty($isRtl))): ?> data-simplebar-direction="rtl" <?php endif; ?>>

        <div class="px-12">
            
            <div class="card-with-mask position-relative mb-24">
                <div class="mask-8-white bg-primary-20"></div>
                <div class="position-relative z-index-2 bg-primary p-8 pb-12 rounded-16">
                    <div class="d-flex align-items-center justify-content-between bg-white rounded-12">
                        <div class="d-flex align-items-center p-12">
                            <div class="size-40 rounded-circle">
                                <img src="<?php echo e($user->getAvatar(40)); ?>" alt="<?php echo e($user->full_name); ?>" class="img-cover rounded-circle">
                            </div>
                            <div class="ml-8">
                                <h4 class="font-14 text-dark"><?php echo e($user->full_name); ?></h4>
                                <p class="mt-2 font-12 text-gray-500"><?php echo e($user->role->caption); ?></p>
                            </div>
                        </div>

                        <div class="d-flex-center size-40 p-6">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-teacher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons sidebar-teacher-icon','width' => '40px','height' => '40px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                    </div>

                    <?php
                        $percent = $course->getProgress(true);
                    ?>

                    <div class="learning-progress d-flex align-items-center mt-12 rounded-4 bg-gray-100 p-2">
                        <div class="js-course-learning-progress-bar-percent learning-progress__bar rounded-4 bg-primary" style="width: <?php echo e($percent); ?>%"></div>
                    </div>
                    <div class="d-flex align-items-center gap-4 mt-4 font-12 text-white opacity-75">
                        <span class="js-course-learning-progress-percent"><?php echo e($percent); ?>%</span>
                        <span class=""><?php echo e(trans('update.study_progress')); ?></span>
                    </div>

                </div>
            </div>

            
            <?php if(!empty($course->access_days) and !empty($saleItem)): ?>
                <?php
                    $courseExpired = $course->getExpiredAccessDays($saleItem->created_at, $saleItem->gift_id)
                ?>

                <div class="d-flex align-items-center bg-warning-10 border-warning rounded-12 p-12 mt-16">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-danger'); ?>
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
                    <span class="font-12 text-warning ml-4"><?php echo trans('update.course_expires_on_date', ['date' => dateTimeFormat($courseExpired, 'j M Y')]); ?></span>
                </div>
            <?php endif; ?>

            
            <?php if(!empty($course->start_date)): ?>
                <div class="d-flex align-items-center bg-gray-100 border-gray-300 rounded-12 p-12 mt-16">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clock-1'); ?>
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
                    <span class="font-12 text-gray-500 ml-4"><?php echo trans('update.course_will_be_started_on_date', ['date' => dateTimeFormat($course->start_date, 'j M Y')]); ?></span>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="mt-16 pt-12 px-12 border-top-gray-100">

            <div class="custom-tabs">
                <div class="position-relative sidebar-tabs d-flex align-items-center justify-content-between gap-4 p-8 bg-gray-100 rounded-30">
                    <div class="navbar-item d-flex-center cursor-pointer py-8 px-24 rounded-20 active" data-tab-toggle data-tab-href="#contentTab">
                        <span class=""><?php echo e(trans('update.content')); ?></span>
                    </div>

                    <div class="navbar-item d-flex-center cursor-pointer py-8 px-24 rounded-20" data-tab-toggle data-tab-href="#quizzesTab">
                        <span class=""><?php echo e(trans('quiz.quizzes')); ?></span>
                    </div>

                    <div class="navbar-item d-flex-center cursor-pointer py-8 px-24 rounded-20" data-tab-toggle data-tab-href="#certificatesTab">
                        <span class=""><?php echo e(trans('panel.certificates')); ?></span>
                    </div>
                </div>

                <div class="custom-tabs-body mt-12">
                    <div class="custom-tabs-content active" id="contentTab">
                        <?php echo $__env->make('design_1.web.courses.learning_page.includes.sidebar.tabs.contents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <div class="custom-tabs-content" id="quizzesTab">
                        <?php echo $__env->make('design_1.web.courses.learning_page.includes.sidebar.tabs.quizzes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <div class="custom-tabs-content" id="certificatesTab">
                        <?php echo $__env->make('design_1.web.courses.learning_page.includes.sidebar.tabs.certificates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/sidebar/index.blade.php ENDPATH**/ ?>