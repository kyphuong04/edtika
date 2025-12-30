<div class="course-hero d-flex flex-column justify-content-end rounded-32 px-20 bg-gray-200">
    <div class="course-hero__mask rounded-32"></div>

    <img src="<?php echo e($course->getImageCover()); ?>" class="course-hero__cover-img img-cover rounded-32" alt="<?php echo e($course->title); ?>"/>

    <div class="course-hero__content position-relative z-index-3">
        <?php if(!empty($course->category)): ?>
            <div class="d-flex align-items-center text-white opacity-50">
                <a href="/classes" class="text-white"><?php echo e(trans('update.courses')); ?></a>
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white mx-2','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <a href="<?php echo e($course->category->getUrl()); ?>" class="text-white"><?php echo e($course->category->title); ?></a>
            </div>
        <?php endif; ?>

        <div class="d-flex align-items-center flex-wrap gap-12 mt-4">
            <h1 class="course-hero__title font-32 font-weight-bold text-white text-ellipsis"><?php echo e($course->title); ?></h1>

            
            <div class="d-flex flex-wrap align-items-center gap-12">
                
                <?php if(!empty($course->feature)): ?>
                    <div class="d-flex-center p-4 pr-8 rounded-32 bg-success">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-verify'); ?>
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
                        <span class="ml-4 font-12 text-white"><?php echo e(trans('update.featured')); ?></span>
                    </div>
                <?php endif; ?>

                
                <?php $__currentLoopData = $course->allBadges(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseBadge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex-center gap-4 p-4 pr-8 rounded-32" style="background-color: <?php echo e($courseBadge->background); ?>; color: <?php echo e($courseBadge->color); ?>;">
                        <?php if(!empty($courseBadge->icon)): ?>
                            <div class="size-24">
                                <img src="<?php echo e($courseBadge->icon); ?>" alt="<?php echo e($courseBadge->title); ?>" class="img-cover">
                            </div>
                        <?php endif; ?>
                        <span class="font-12"><?php echo e($courseBadge->title); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <?php if(!empty($course->summary)): ?>
            <div class="mt-8 text-white opacity-50"><?php echo nl2br($course->summary); ?></div>
        <?php endif; ?>

        <div class="d-flex align-items-center flex-wrap gap-24 mt-12">
            
            <?php echo $__env->make('design_1.web.components.rate', [
                  'rate' => $course->getRate(),
                 'rateCount' => $course->getRateCount(),
                 'rateClassName' => ''
             ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <div class="d-flex align-items-center font-12 text-white">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-teacher'); ?>
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
                <span class="mx-4 font-weight-bold"><?php echo e($course->getSalesCount()); ?></span>
                <span class="opacity-50"><?php echo e(trans('quiz.students')); ?></span>
            </div>

            
            <div class="d-flex align-items-center font-12 text-white">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-2'); ?>
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
                <span class="mx-4 font-weight-bold"><?php echo e($course->getAllLessonsCount()); ?></span>
                <span class="opacity-50"><?php echo e(trans('update.lectures')); ?></span>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-20">
            <div class="d-flex align-items-center">
                <div class="size-40 rounded-circle">
                    <img src="<?php echo e($course->teacher->getAvatar(40)); ?>" class="img-cover rounded-circle" alt="<?php echo e($course->teacher->full_name); ?>">
                </div>

                <div class="ml-8">
                    <a href="<?php echo e($course->teacher->getProfileUrl()); ?>" target="_blank" class="font-14 font-weight-bold text-white"><?php echo e($course->teacher->full_name); ?></a>
                    <p class="mt-2 font-12 text-white"><?php echo e($course->teacher->role->caption); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/includes/hero.blade.php ENDPATH**/ ?>