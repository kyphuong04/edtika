<tr>
    
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="<?php echo e($user->getAvatar()); ?>" class="img-cover rounded-circle" alt="<?php echo e($user->full_name); ?>">
            </div>
            <div class="ml-8">
                <span class="d-block font-weight-bold"><?php echo e($user->full_name); ?></span>
            </div>
        </div>
    </td>

    
    <td class="text-left">
        <?php if($user->email): ?>
            <span class="text-gray-700 dark:text-gray-200"><?php echo e($user->email); ?></span>
        <?php else: ?>
            <span class="text-gray-700 dark:text-gray-200">-</span>
        <?php endif; ?>
    </td>

    
    <td class="text-center">
        <?php if($user->mobile): ?>
            <span><?php echo e($user->mobile); ?></span>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>

    
    <td class="text-center">
        <?php
            // Count webinars this student is enrolled in
            $webinarCount = \App\Models\Sale::where('buyer_id', $user->id)
                ->whereNull('refund_at')
                ->whereNotNull('webinar_id')
                ->distinct('webinar_id')
                ->count('webinar_id');
        ?>
        <span><?php echo e($webinarCount); ?></span>
    </td>

    
    <td class="text-center">
        <?php
            // Count quiz results for this student
            $quizCount = \App\Models\QuizzesResult::where('user_id', $user->id)->count();
        ?>
        <span><?php echo e($quizCount); ?></span>
    </td>

    
    <td class="text-center">
        <?php
            // Count certificates earned by this student
            $certificateCount = \App\Models\Certificate::where('student_id', $user->id)->count();
        ?>
        <span><?php echo e($certificateCount); ?></span>
    </td>

    
    <td class="text-center">
        <span><?php echo e(dateTimeFormat($user->purchase_date ?? $user->created_at, 'j M Y')); ?></span>
    </td>

    
    <td class="text-center">
        <?php if(!empty($user->id)): ?>
        <div class="actions-dropdown position-relative d-flex justify-content-center align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32 bg-white dark:bg-dark-blue-deep">
                <ul class="my-8">
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="<?php echo e($user->getProfileUrl()); ?>" target="_blank" class="text-gray-700 dark:text-gray-100">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-2','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <?php echo e(trans('public.profile')); ?>

                        </a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/students-tracking/<?php echo e($user->id); ?>/quizResults" class="text-gray-700 dark:text-gray-100">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-document-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-2','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <?php echo e(trans('panel.quiz_results')); ?>

                        </a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/students-tracking/<?php echo e($user->id); ?>/assignments" class="text-gray-700 dark:text-gray-100">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-task-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-2','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <?php echo e(trans('panel.assignments')); ?>

                        </a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/support/new" target="_blank" class="text-primary dark:text-primary-light">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-2','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            Support Ticket
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </td>
</tr>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/my_students/table_item.blade.php ENDPATH**/ ?>