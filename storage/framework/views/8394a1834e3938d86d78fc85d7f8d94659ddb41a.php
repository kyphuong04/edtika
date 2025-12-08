<?php if(!empty($nextSession) and $nextSession->date > time() and checkTimestampInToday($nextSession->date)): ?>
    <div class="js-next-session-info d-flex align-items-center cursor-pointer" data-webinar-id="<?php echo e($course->id); ?>">
        <div class="d-flex-center">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary ','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark"><?php echo e(trans('update.today’s_live_session')); ?></h5>
            <p class="font-12 text-gray-500"><?php echo e(trans('update.create_a_live_session_now')); ?></p>
        </div>
    </div>
<?php elseif(!is_null($course->capacity)): ?>
    <?php
        $percent = 0;

        $salesCount = $course->sales()->count();

        if ($salesCount > 0) {
            $percent = (!empty($course->capacity) and $course->capacity > 0) ? (($salesCount * 100) / $course->capacity) : 0;
        }
    ?>

    <?php if($percent < 100): ?>
        <div class="w-100">
            <div class="d-flex align-items-center gap-4 font-12">
                <span class="font-weight-bold text-dark"><?php echo e(round($percent,1)); ?>%</span>
                <span class="text-gray-500"><?php echo e(trans('update.capacity_reached')); ?></span>
            </div>

            <div class="progress-bar d-flex mt-8 rounded-4 bg-gray-100 w-100">
                <span class="bg-success rounded-4" style="width: <?php echo e($percent); ?>%"></span>
            </div>
        </div>
    <?php else: ?>
        
        <?php if($course->isWebinar() and $course->start_date < time() and !$course->isProgressing()): ?>
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-36 bg-success rounded-circle">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '36px','height' => '36px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>

                <div class="ml-8">
                    <h5 class="font-12 font-weight-bold text-dark"><?php echo e(trans('update.course_finished!')); ?></h5>
                    <p class="font-12 text-gray-500"><?php echo e(trans('update.you_did_it_perfectly...')); ?></p>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-36 bg-success rounded-circle">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '36px','height' => '36px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>

                <div class="ml-8">
                    <h5 class="font-12 font-weight-bold text-dark"><?php echo e(trans('update.capacity_reached')); ?>!</h5>
                    <p class="font-12 text-gray-500"><?php echo e(trans('update.all_seats_were_been_sold')); ?></p>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

<?php else: ?>
    
    <?php
        $avgLearningPercent = $course->getAverageLearning();
    ?>

    <div class="w-100">
        <div class="d-flex align-items-center gap-4 font-12">
            <span class="font-weight-bold text-dark"><?php echo e($avgLearningPercent); ?>%</span>
            <span class="text-gray-500"><?php echo e(trans('update.av._learning_progress')); ?></span>
        </div>

        <div class="progress-bar d-flex mt-8 rounded-4 bg-gray-100 w-100">
            <span class="bg-success rounded-4" style="width: <?php echo e($avgLearningPercent); ?>%"></span>
        </div>
    </div>

<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/my_courses/course_card/progress_and_chart.blade.php ENDPATH**/ ?>