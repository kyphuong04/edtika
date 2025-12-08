<tr>
    <td class="text-left">
        <div class="user-inline-avatar d-flex align-items-center">
            <div class="size-48 bg-gray-200 rounded-circle">
                <img src="<?php echo e($quizResult->quiz->creator->getAvatar()); ?>" class="js-avatar-img img-cover rounded-circle" alt="">
            </div>
            <div class=" ml-8">
                <span class="d-block"><?php echo e($quizResult->quiz->creator->full_name); ?></span>
                <span class="mt-4 font-12 text-gray-500 d-block"><?php echo e($quizResult->quiz->creator->email); ?></span>
            </div>
        </div>
    </td>

    <td class="text-left">
        <span class="d-block"><?php echo e($quizResult->quiz->title); ?></span>
        <span class="font-12 text-gray-500 d-block"><?php echo e($quizResult->quiz->webinar->title); ?></span>
    </td>

    <td class="text-center"><?php echo e($quizResult->quiz->quizQuestions->sum('grade')); ?></td>

    <td class="text-center"><?php echo e($quizResult->user_grade); ?></td>

    <td class="text-center">
        <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 text-<?php echo e(($quizResult->status == 'passed') ? 'success' : ($quizResult->status == 'waiting' ? 'warning' : 'danger')); ?> bg-<?php echo e(($quizResult->status == 'passed') ? 'success' : ($quizResult->status == 'waiting' ? 'warning' : 'danger')); ?>-30">
            <?php echo e(trans('quiz.'.$quizResult->status)); ?>

        </span>

        <?php if($quizResult->status =='failed' and $quizResult->can_try): ?>
            <span class="d-block mt-4 font-12 text-gray-500"><?php echo e(trans('quiz.quiz_chance_remained',['count' => $quizResult->count_can_try])); ?></span>
        <?php endif; ?>
    </td>

    <td class="text-center"><?php echo e(dateTimeFormat($quizResult->created_at,'j M Y H:i')); ?></td>

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

                    <?php if((!$quizResult->can_try and $quizResult->status != 'waiting') or ($quizResult->status == 'passed')): ?>
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/results/<?php echo e($quizResult->id); ?>/details" target="_blank" class=""><?php echo e(trans('public.view_answers')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if($quizResult->status != 'passed'): ?>
                        <?php if($quizResult->can_try): ?>
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/quizzes/<?php echo e($quizResult->quiz->id); ?>/overview" class=""><?php echo e(trans('public.try_again')); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="<?php echo e($quizResult->quiz->webinar->getUrl()); ?>" class=""><?php echo e(trans('webinars.webinar_page')); ?></a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/my_results/table_items.blade.php ENDPATH**/ ?>