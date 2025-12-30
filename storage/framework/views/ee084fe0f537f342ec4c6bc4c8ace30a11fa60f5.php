<div class="course-right-side-section position-relative mt-28">
    <div class="course-right-side-section__mask"></div>

    <div class="position-relative bg-white rounded-24 p-16 z-index-2">

        <?php if(!empty($webinarPartnerTeacher)): ?>
            <span class="course-right-side__teacher-invited py-4 px-8 rounded-32 bg-primary font-12 text-white"><?php echo e(trans('update.invited')); ?></span>
        <?php endif; ?>


        <div class="d-flex align-items-center">
            <div class="position-relative size-64 rounded-circle">
                <img src="<?php echo e($userRow->getAvatar(64)); ?>" alt="<?php echo e($userRow->full_name); ?>" class="img-cover rounded-circle">

                <?php if($userRow->verified): ?>
                    <div class="course-right-side__teacher-verified-badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="<?php echo e(trans('public.verified')); ?>">
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tick-icon','data' => ['class' => 'icons text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('tick-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="ml-8">
                <a href="<?php echo e($userRow->getProfileUrl()); ?>" target="_blank" class="">
                    <div class="d-block font-weight-bold text-dark"><?php echo e($userRow->full_name); ?></div>
                </a>

                <p class="mt-2 font-12 text-gray-500"><?php echo e($userRow->bio); ?></p>
            </div>
        </div>

        <?php
            $userRowRates = $userRow->rates(true);
        ?>

        <div class="position-relative d-flex align-items-center flex-wrap gap-12 mt-32 pt-36 pr-16 pl-20 pb-20 rounded-12 border-gray-200">

            <div class="course-right-side__teacher-rate-card p-8 rounded-24 bg-gray-100">
                <?php echo $__env->make('design_1.web.components.rate', [
                        'rate' => $userRowRates['rate'],
                        'rateCount' => $userRowRates['count'],
                        'rateClassName' => '',
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <?php $__currentLoopData = $userRow->getBadges(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userBadge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="size-32 rounded-8" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?php echo (!empty($userBadge->badge_id) ? nl2br($userBadge->badge->description) : nl2br($userBadge->description)); ?>">
                    <img src="<?php echo e(!empty($userBadge->badge_id) ? $userBadge->badge->image : $userBadge->image); ?>" class="img-cover rounded-8" alt="<?php echo e(!empty($userBadge->badge_id) ? $userBadge->badge->title : $userBadge->title); ?>">
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="d-flex align-items-center gap-16 mt-16">
            <a href="<?php echo e($userRow->getProfileUrl()); ?>" target="_blank" class="btn btn-primary btn-lg flex-1"><?php echo e(trans('public.profile')); ?></a>

            <?php if($userRow->hasMeeting()): ?>
                <a href="<?php echo e($userRow->getMeetingReservationUrl()); ?>" target="_blank" class="d-inline-flex-center size-48 rounded-12 border-2 border-gray-400">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
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
                </a>
            <?php endif; ?>
        </div>

        <?php if($userRow->offline): ?>
            <div class="mt-16 p-12 rounded-12 border-gray-200 bg-gray-100">
                <div class="d-flex align-items-center">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-profile-delete'); ?>
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
                    <span class="ml-4 font-12 font-weight-bold text-gray-500"><?php echo e(trans('update.the_instructor_is_currently_unavailable')); ?></span>
                </div>

                <div class="mt-12 text-gray-500"><?php echo nl2br($userRow->offline_message); ?></div>
            </div>
        <?php endif; ?>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/includes/rightSide/teacher.blade.php ENDPATH**/ ?>