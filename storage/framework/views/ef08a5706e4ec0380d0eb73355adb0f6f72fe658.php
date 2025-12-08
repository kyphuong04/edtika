<tr>
    
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="d-flex-center rounded-circle bg-gray-100  size-48 ">
                <img src="<?php echo e($quizResult->user->getAvatar(48)); ?>" alt="" class="img-fluid rounded-circle">
            </div>

            <div class="ml-12">
                <div class=""><?php echo e($quizResult->user->full_name); ?></div>

                <?php if(!empty($quizResult->user->email)): ?>
                    <div class="mt-4 font-12 text-gray-500"><?php echo e($quizResult->user->email); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </td>

    
    <td class="text-left">
        <div class="d-flex align-items-center">
            <?php if(!empty($quizResult->quiz->icon)): ?>
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <img src="<?php echo e($quizResult->quiz->icon); ?>" alt="" class="img-fluid">
                </div>
            <?php else: ?>
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-tick'); ?>
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
            <?php endif; ?>

            <div class="ml-12">
                <div class=""><?php echo e($quizResult->quiz->title); ?></div>

                <?php if(!empty($quizResult->quiz->webinar)): ?>
                    <div class="mt-4 font-12 text-gray-500"><?php echo e($quizResult->quiz->webinar->title); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </td>

    
    <td class="text-center">
        <span class=""><?php echo e($quizResult->quiz->total_mark ?? '-'); ?></span>
    </td>

    
    <td class="text-center">
        <span class=""><?php echo e($quizResult->quiz->pass_mark ?? '-'); ?></span>
    </td>

    
    <td class="text-center">
        <span class=""><?php echo e($quizResult->user_grade ?? '-'); ?></span>
    </td>

    
    <td class="text-center">
        <span class=""><?php echo e(!empty($quizResult->quiz->attempt) ? $quizResult->quiz->attempt : '-'); ?></span>
    </td>

    
    <td class="text-center">
        <div class="text-center">
            <span class="d-block"><?php echo e(dateTimeFormat($quizResult->created_at, 'j M Y H:i')); ?></span>
            <span class="d-block mt-2 font-12 text-gray-500"><?php echo e(dateTimeFormat($quizResult->created_at, 'j M Y H:i', 1)); ?></span>
        </div>
    </td>

    
    <td class="text-center">
        <?php if($quizResult->status == \App\Models\QuizzesResult::$passed): ?>
            <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success"><?php echo e(trans('quiz.passed')); ?></div>
        <?php elseif($quizResult->status == \App\Models\QuizzesResult::$failed): ?>
            <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-danger-30 font-12 text-danger"><?php echo e(trans('quiz.failed')); ?></div>
        <?php else: ?>
            <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-warning-30 font-12 text-warning"><?php echo e(trans('update.pending_review')); ?></div>
        <?php endif; ?>
    </td>

    
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
                            <a href="/panel/quizzes/<?php echo e($quizResult->quiz_id); ?>/edit" class=""><?php echo e(trans('quiz.edit_quiz')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if($quizResult->status != \App\Models\QuizzesResult::$waiting): ?>
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/results/<?php echo e($quizResult->id); ?>/details" target="_blank" class=""><?php echo e(trans('public.view')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if($quizResult->status == \App\Models\QuizzesResult::$waiting): ?>
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/results/<?php echo e($quizResult->id); ?>/edit" target="_blank" class=""><?php echo e(trans('public.review')); ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/quizzes/results/<?php echo e($quizResult->id); ?>/delete" class="delete-action text-danger "><?php echo e(trans('public.delete')); ?></a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/results/item_table.blade.php ENDPATH**/ ?>