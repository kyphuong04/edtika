<?php
    $nextSession = $course->nextSession();

    /*$lastSession = $course->lastSession();
    $isProgressing = false;

    if($course->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
        $isProgressing=true;
    }*/

?>

<div class="panel-course-card-1 position-relative <?php echo e(!empty($isInvitedCoursesPage) ? 'is-invited-course-card' : ''); ?>">
    <div class="card-mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row gap-12 z-index-2 bg-white p-12 rounded-24">
        <a href="<?php echo e($course->getUrl()); ?>" target="_blank" class="d-flex flex-column flex-lg-row gap-12 flex-grow-1 text-decoration-none">
            
            <div class="panel-course-card-1__image position-relative rounded-16 bg-gray-100">
                <img src="<?php echo e($course->getImage()); ?>" alt="" class="img-cover rounded-16">
                
                <?php echo $__env->make("design_1.panel.webinars.my_courses.course_card.badges", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php if($course->isWebinar()): ?>
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-video'); ?>
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
                <?php elseif($course->isTextCourse()): ?>
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-note-2'); ?>
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
                <?php elseif($course->isCourse()): ?>
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-video-play'); ?>
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
                <?php endif; ?>
            </div>

            
            <div class="panel-course-card-1__content flex-1 d-flex flex-column">
                <div class="bg-gray-100 p-16 rounded-16 mb-12">
                    <div class="d-flex align-items-start justify-content-between gap-12">
                        <div class="">
                            <h3 class="font-16 text-dark"><?php echo e(truncate($course->title, 46)); ?></h3>
                            
                            <?php echo $__env->make("design_1.web.components.rate", [
                                'rate' => round($course->getRate(),1),
                                'rateCount' => $course->reviews()->where('status', 'active')->count(),
                                'rateClassName' => 'mt-8',
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>

                    
                    <?php echo $__env->make("design_1.panel.webinars.my_courses.course_card.stats", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    
                </div>

                
                <div class="row align-items-center justify-content-between mt-auto">
                    <div class="col-10">
                        <?php echo $__env->make("design_1.panel.webinars.my_courses.course_card.progress_and_chart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    
                    <div class="col-2 d-flex align-items-center justify-content-end font-16 font-weight-bold text-primary">
                        <?php if($course->price > 0): ?>
                            <?php if($course->bestTicket() < $course->price): ?>
                                <span class=""><?php echo e(handlePrice($course->bestTicket(), true, true, false, null, true)); ?></span>
                                <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through"><?php echo e(handlePrice($course->price, true, true, false, null, true)); ?></span>
                            <?php else: ?>
                                <span class=""><?php echo e(handlePrice($course->price, true, true, false, null, true)); ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class=""><?php echo e(trans('public.free')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </a>
    </div>

    
    <div class="actions-dropdown-container position-absolute" style="top: 28px; right: 28px; z-index: 10;">
        <?php echo $__env->make("design_1.panel.webinars.my_courses.course_card.actions_dropdown", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/my_courses/course_card/index.blade.php ENDPATH**/ ?>