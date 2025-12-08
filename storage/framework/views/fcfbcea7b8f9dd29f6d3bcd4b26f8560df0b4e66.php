<tr>
    <td class="text-left">
        <span class="d-block"><?php echo e($quiz->title); ?></span>
        <span class="font-12 text-gray-500 d-block">
                                                <?php if(!empty($quiz->webinar)): ?>
                <?php echo e($quiz->webinar->title); ?>

            <?php else: ?>
                <?php echo e(trans('panel.not_assign_any_webinar')); ?>

            <?php endif; ?>
                                </span>
    </td>

    <td class="text-center align-middle">
        <?php echo e($quiz->quizQuestions->count()); ?>

        <?php if(($quiz->display_limited_questions and !empty($quiz->display_number_of_questions))): ?>
            <span class="font-12 text-gray-500">(<?php echo e(trans('public.active')); ?>: <?php echo e($quiz->display_number_of_questions); ?>)</span>
        <?php endif; ?>
    </td>

    <td class="text-center align-middle"><?php echo e($quiz->time); ?></td>

    <td class="text-center align-middle"><?php echo e($quiz->quizQuestions->sum('grade')); ?></td>

    <td class="text-center align-middle"><?php echo e($quiz->pass_mark); ?></td>

    <td class="text-center align-middle">
        <span class="d-block"><?php echo e($quiz->quizResults->pluck('user_id')->count()); ?></span>

        <?php if(!empty($quiz->userSuccessRate) and $quiz->userSuccessRate > 0): ?>
            <span class="font-12 text-gray-500 d-block"><?php echo e($quiz->userSuccessRate); ?>% <?php echo e(trans('quiz.passed')); ?></span>
        <?php endif; ?>
    </td>

    <td class="text-center">
        <?php if($quiz->status === \App\Models\Quiz::ACTIVE): ?>
            <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success"><?php echo e(trans('admin/main.active')); ?></span>
        <?php else: ?>
            <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-danger-30 font-12 text-danger"><?php echo e(trans('admin/main.inactive')); ?></span>
        <?php endif; ?>
    </td>

    <td class="text-center align-middle"><?php echo e(dateTimeFormat($quiz->created_at, 'j M Y H:i')); ?></td>

    <td class="text-right">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
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

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_quizzes_create')): ?>
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/<?php echo e($quiz->id); ?>/edit" class=""><?php echo e(trans('public.edit')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('panel_quizzes_delete')): ?>
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/<?php echo e($quiz->id); ?>/delete" data-item-id="1" class="d-flex align-items-center w-100 px-16 py-8 btn-transparent text-danger delete-action"><?php echo e(trans('public.delete')); ?></a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>

    </td>

</tr>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/lists/table_items.blade.php ENDPATH**/ ?>