<div class="d-grid grid-columns-4 gap-16 mt-16">

    
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500"><?php echo e(trans('update.deadline')); ?></span>

            <div class="d-flex align-items-end font-24 font-weight-bold">
                <?php if($assignmentDeadline): ?>
                    <?php if(is_bool($assignmentDeadline)): ?>
                        <span class=""><?php echo e(trans('update.unlimited')); ?></span>
                    <?php else: ?>
                        <span class=""><?php echo e(ceil($assignmentDeadline)); ?></span>
                        <span class="font-12 text-gray-500 font-weight-400 ml-4"><?php echo e(trans('public.days')); ?></span>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-danger"><?php echo e(trans('panel.expired')); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-primary-40">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
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

    
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500"><?php echo e(trans('update.submission_times')); ?></span>

            <div class="d-flex align-items-end font-24 font-weight-bold">
                <?php if(!empty($assignment->attempts)): ?>
                    <?php echo e($submissionTimes); ?>/<?php echo e($assignment->attempts); ?>

                <?php else: ?>
                    <?php echo e(trans('update.unlimited')); ?>

                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-success-40">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-refresh-2'); ?>
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

    
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500"><?php echo e(trans('quiz.your_grade')); ?></span>

            <div class="d-flex align-items-end font-24 font-weight-bold">
                <?php echo e($assignmentHistory->grade ?? 0); ?>/<?php echo e($assignment->grade); ?>

            </div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-warning-40">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-2'); ?>
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

    
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500"><?php echo e(trans('update.pass_grade')); ?></span>

            <div class="d-flex align-items-end font-24 font-weight-bold">
                <?php echo e($assignment->pass_grade); ?>

            </div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-danger-40">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>
    </div>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/assignment/top_stats.blade.php ENDPATH**/ ?>