<div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
    <div class="d-flex-center size-40 bg-white border-gray-200 rounded-8 cursor-pointer">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
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
    </div>

    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
        <ul class="my-8">

            <?php if($course->status == \App\Models\Webinar::$active): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_webinars_learning_page')): ?>
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="<?php echo e($course->getLearningPageUrl()); ?>" target="_blank" class=""><?php echo e(trans('update.learning_page')); ?></a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_webinars_create')): ?>
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/<?php echo e($course->id); ?>/edit" class=""><?php echo e(trans('public.edit')); ?></a>
                </li>
            <?php endif; ?>

            <?php if($course->isWebinar()): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_webinars_create')): ?>
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/courses/<?php echo e($course->id); ?>/step/4" class=""><?php echo e(trans('public.sessions')); ?></a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_webinars_create')): ?>
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/<?php echo e($course->id); ?>/step/4" class=""><?php echo e(trans('public.files')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_webinars_export_students_list')): ?>
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/<?php echo e($course->id); ?>/export-students-list" class=""><?php echo e(trans('public.export_list')); ?></a>
                </li>
            <?php endif; ?>

            <?php if($authUser->id == $course->creator_id): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_webinars_duplicate')): ?>
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/courses/<?php echo e($course->id); ?>/duplicate" class=""><?php echo e(trans('public.duplicate')); ?></a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_webinars_statistics')): ?>
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/<?php echo e($course->id); ?>/statistics" class=""><?php echo e(trans('update.statistics')); ?></a>
                </li>
            <?php endif; ?>

            <?php if($course->creator_id == $authUser->id): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_webinars_delete')): ?>
                    <li class="actions-dropdown__dropdown-menu-item">
                        <?php echo $__env->make('design_1.panel.includes.content_delete_btn', [
                            'deleteContentUrl' => "/panel/courses/{$course->id}/delete",
                            'deleteContentClassName' => ' text-danger',
                            'deleteContentItem' => $course,
                            'deleteContentItemType' => "course",
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

        </ul>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/my_courses/course_card/actions_dropdown.blade.php ENDPATH**/ ?>