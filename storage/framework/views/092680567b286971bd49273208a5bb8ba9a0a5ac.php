<a href="<?php echo e($course->getUrl()); ?>" class="text-decoration-none d-block">
    <div class="course-grid-card-1 position-relative">
    <div class="course-grid-card-1__mask"></div>

    <div class="position-relative z-index-2">
            <div class="course-grid-card-1__image bg-gray-200">
                <?php if($course->bestTicket() && $course->bestTicket(true)['percent'] > 0): ?>
                    <div class="position-absolute z-index-1 bg-accent d-flex align-items-center justify-content-center py-4 px-8 mt-12 ml-12 rounded-pill">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-discount-shape'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <span class="ml-4 text-white font-12"><?php echo e($course->bestTicket(true)['percent']); ?>% <?php echo e(trans('public.off')); ?></span>
                    </div>
                <?php endif; ?>
                <img src="<?php echo e($course->getImage()); ?>" class="img-cover" alt="<?php echo e($course->title); ?>">
            </div>

        <div class="course-grid-card-1__body d-flex flex-column py-12">
            <div class="d-flex flex-column px-12 w-100">
                    <h3 class="course-title font-16 font-weight-bold text-dark"><?php echo e(clean($course->title,'title')); ?></h3>

                <?php echo $__env->make('design_1.web.components.rate', ['rate' => $course->getRate(), 'rateCount' => $course->getRateCount(), 'rateClassName' => 'mt-12'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="d-flex align-items-center my-16" onclick="event.stopPropagation()">
                        <a href="<?php echo e($course->teacher->getProfileUrl()); ?>" target="_blank" class="size-32 rounded-circle" onclick="event.stopPropagation()">
                        <img src="<?php echo e($course->teacher->getAvatar(32)); ?>" class="img-cover rounded-circle" alt="<?php echo e($course->teacher->full_name); ?>">
                        </a>

                    <div class="d-flex flex-column ml-4">
                            <a href="<?php echo e($course->teacher->getProfileUrl()); ?>" target="_blank" class="font-14 font-weight-bold text-dark" onclick="event.stopPropagation()"><?php echo e($course->teacher->full_name); ?></a>

                        <?php if(!empty($course->category)): ?>
                            <div class="d-inline-flex align-items-center gap-4 mt-2 font-12 text-gray-500">
                                <span class=""><?php echo e(trans('public.in')); ?></span>
                                    <a href="<?php echo e($course->category->getUrl()); ?>" target="_blank" class="font-14 text-gray-500 text-ellipsis" onclick="event.stopPropagation()"><?php echo e($course->category->title); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-auto pt-12 border-top-gray-100 px-12">
                <div class="d-flex align-items-center font-16 font-weight-bold text-primary">
                        <a href="<?php echo e($course->getUrl()); ?>" class="text-decoration-none text-primary">
                    <?php if(!empty($showCoursePoints)): ?>
                        <span><?php echo e(trans('update.n_points', ['count' => $course->points])); ?></span>
                    <?php else: ?>
                        <?php echo $__env->make("design_1.web.courses.components.price_horizontal", ['courseRow' => $course], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>
                        </a>
                </div>

                <div class="d-flex align-items-center font-14 text-gray-500">
                        <a href="<?php echo e($course->getUrl()); ?>" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-clock-1'); ?>
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
                    <span class="ml-4"><?php echo e(convertMinutesToHourAndMinute($course->duration)); ?></span>
                    <span class="ml-4"><?php echo e(trans('home.hours')); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/components/cards/grids/grid_card_1.blade.php ENDPATH**/ ?>