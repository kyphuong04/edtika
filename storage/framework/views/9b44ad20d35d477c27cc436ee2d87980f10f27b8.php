<div class="instructor-dashboard__hello-box <?php echo e($authUser->isAdmin() ? 'organ-hello-box' : ''); ?> p-16 rounded-24 mt-54 w-100">

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
                <?php if($authUser->isAdmin()): ?>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document'); ?>
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
                                <span class="d-block font-weight-bold text-white"><?php echo e($helloBox['instructorsCount']); ?></span>
                                <span class="d-block mt-4 text-white opacity-75 text-ellipsis"><?php echo e(trans('update.instructors')); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-box-1'); ?>
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
                                <span class="d-block font-weight-bold text-white"><?php echo e($helloBox['studentsCount']); ?></span>
                                <span class="d-block mt-4 text-white opacity-75 text-ellipsis"><?php echo e(trans('update.students')); ?></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document'); ?>
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
                                <span class="d-block font-weight-bold text-white"><?php echo e($helloBox['productsCount']); ?></span>
                                <span class="d-block mt-4 text-white opacity-75 text-ellipsis"><?php echo e(trans('update.products')); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 bg-white-20 rounded-circle">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-box-1'); ?>
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
                                <span class="d-block font-weight-bold text-white"><?php echo e($helloBox['bundlesCount']); ?></span>
                                <span class="d-block mt-4 text-white opacity-75 text-ellipsis"><?php echo e(trans('update.bundles')); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 col-lg-6 position-relative d-none d-lg-block">
            <div class="hello-box-user-vector d-flex justify-content-end">
                <img src="/assets/design_1/img/panel/dashboard/<?php echo e($authUser->isAdmin() ? 'organ' : 'instructor'); ?>/hello-box-user-vector.png" alt="" class="img-fluid">
            </div>
        </div>
    </div>

    <?php if(!empty($helloBox['coursesCount']) and !empty($helloBox['manageCourses']) and count($helloBox['manageCourses'])): ?>
        <div class="mt-52">
            <h4 class="font-14 font-weight-bold text-white"><?php echo e(trans('update.manage_courses')); ?></h4>

            <div class="row mt-12">
                <?php $__currentLoopData = $helloBox['manageCourses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manageCourse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="instructor-dashboard__hello-box-course-col col-12 col-lg-6 mt-12">
                        <a href="<?php echo e($manageCourse->getUrl()); ?>" target="_blank" class="d-block">
                            <div class="card-with-mask position-relative">
                                <div class="mask-8-white"></div>
                                <div class="position-relative z-index-2 bg-white py-16 rounded-16">
                                    <div class="d-flex align-items-center px-16 mb-16">
                                        <div class="size-40 bg-gray-100 rounded-8">
                                            <img src="<?php echo e($manageCourse->getIcon()); ?>" alt="" class="img-cover rounded-8">
                                        </div>
                                        <div class="ml-8">
                                            <h6 class="font-12 text-dark text-ellipsis"><?php echo e(truncate($manageCourse->title, 30)); ?></h6>
                                            <div class="font-12 mt-4 text-gray-500"><?php echo e(!empty($manageCourse->category) ? $manageCourse->category->title : trans('update.no_category')); ?></div>
                                        </div>
                                    </div>

                                    <?php
                                        $manageCourseProgress = $manageCourse->getAverageLearning();
                                    ?>

                                    <div class="mb-12 px-16">
                                        <div class="progress-card d-flex bg-gray-100">
                                            <div class="progress-bar bg-primary" style="width: <?php echo e($manageCourseProgress); ?>%"></div>
                                        </div>

                                        <div class="d-flex align-items-center gap-4 mt-8 font-12">
                                            <span class="font-weight-bold text-dark"><?php echo e($manageCourseProgress); ?>%</span>
                                            <span class="text-gray-500"><?php echo e(trans('update.average_learning')); ?></span>
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
                                            <span class="font-weight-bold text-dark"><?php echo e(count($manageCourse->getStudentsIds())); ?></span>
                                            <span class="text-gray-500"><?php echo e(trans('public.students')); ?></span>
                                        </div>

                                        <div class="d-flex align-items-center text-gray-500 gap-4">
                                            <span class="font-12 font-weight-bold"><?php echo e(trans('update.view_details')); ?></span>
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
        </div>
    <?php else: ?>
        
        <div class="row mt-108">
            <?php if($authUser->isAdmin()): ?>
                <div class="col-12 col-lg-6">
                    <a href="/panel/manage/instructors/new">
                        <div class="card-with-mask position-relative">
                            <div class="mask-8-white"></div>

                            <div class="position-relative z-index-2 d-flex align-items-center p-24 rounded-16 bg-white">
                                <div class="size-28">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-briefcase'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                                <div class="ml-8">
                                    <h6 class="font-14 text-dark"><?php echo e(trans('update.create_an_instructor')); ?></h6>
                                    <p class="mt-8 font-12 text-gray-500"><?php echo e(trans('update.and_let_them_create_courses')); ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-lg-6">
                    <a href="/panel/manage/students/new">
                        <div class="card-with-mask position-relative">
                            <div class="mask-8-white"></div>

                            <div class="position-relative z-index-2 d-flex align-items-center p-24 rounded-16 bg-white">
                                <div class="size-28">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-teacher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                                <div class="ml-8">
                                    <h6 class="font-14 text-dark"><?php echo e(trans('update.create_an_student')); ?></h6>
                                    <p class="mt-8 font-12 text-gray-500"><?php echo e(trans('update.and_let_them_enjoy_learning')); ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php else: ?>
                <div class="col-12 col-lg-6">
                    <div class="card-with-mask position-relative">
                        <div class="mask-8-white"></div>

                        <div class="position-relative z-index-2 d-flex align-items-center p-16 rounded-16 bg-white">
                            <div class="">
                                <h6 class="font-14 text-dark"><?php echo e(trans('update.create_your_first_course')); ?></h6>
                                <p class="mt-8 font-12 text-gray-500"><?php echo e(trans('update.start_making_money_today')); ?></p>
                            </div>

                            <div class="instructor-dashboard__hello-box-money-image">
                                <img src="/assets/design_1/img/panel/dashboard/instructor/money.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/instructor/includes/hello_box.blade.php ENDPATH**/ ?>