<div class="">
    <img src="/assets/design_1/img/courses/learning_page/quiz_<?php echo e($quiz->result->status); ?>.svg" alt="" class="img-fluid" width="285px" height="212px">
</div>

<h3 class="font-16 text-dark mt-12">
    <?php echo trans("update.learning_page_quiz_{$quiz->result->status}_title"); ?>

</h3>

<div class="text-gray-500 mt-8"><?php echo nl2br(trans("update.learning_page_quiz_{$quiz->result->status}_hint")); ?></div>

<div class="learning-page-quiz-overview-center-line mt-8 bg-gray-400"></div>

<?php if(!empty($quiz->icon)): ?>
    <div class="d-flex-center size-120 mt-8">
        <img src="<?php echo e($quiz->icon); ?>" alt="" class="img-cover">
    </div>
<?php else: ?>
    <div class="d-flex-center size-120 mt-8 rounded-circle bg-primary">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '64px','height' => '64px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
    </div>
<?php endif; ?>

<h4 class="mt-8 font-12 text-dark"><?php echo e($quiz->title); ?></h4>


<div class="d-flex align-items-center flex-wrap gap-16 gap-lg-40 mt-16 text-left">
    
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>
        <div class="ml-8">
            <div class="font-12 text-gray-500"><?php echo e(trans('public.pass_mark')); ?></div>
            <div class="font-weight-bold text-gray-500 mt-2"><?php echo e($quiz->pass_mark); ?>/<?php echo e($quiz->questions_grade); ?></div>
        </div>
    </div>

    
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>
        <div class="ml-8">
            <div class="font-12 text-gray-500"><?php echo e(trans('quiz.your_grade')); ?></div>
            <div class="font-weight-bold text-gray-500 mt-2"><?php echo e($quiz->result->user_grade); ?></div>
        </div>
    </div>

    
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-refresh-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>
        <div class="ml-8">
            <div class="font-12 text-gray-500"><?php echo e(trans('update.attempts')); ?></div>
            <div class="font-weight-bold text-gray-500 mt-2"><?php echo e($quiz->result_count ?? 0); ?>/<?php echo e(!empty($quiz->attempt) ? $quiz->attempt : trans('update.unlimited')); ?></div>
        </div>
    </div>

    
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-award'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>
        <div class="ml-8">
            <div class="font-12 text-gray-500"><?php echo e(trans('public.status')); ?></div>

            <?php if($quiz->result->status == "passed"): ?>
                <div class="font-weight-bold mt-2 text-success"><?php echo e(trans('quiz.passed')); ?></div>
            <?php elseif($quiz->result->status == "failed"): ?>
                <div class="font-weight-bold mt-2 text-danger"><?php echo e(trans('quiz.failed')); ?></div>
            <?php else: ?>
                <div class="font-weight-bold mt-2 text-warning"><?php echo e(trans('quiz.waiting')); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="d-flex align-items-center gap-12 mt-24">
    <?php if($quiz->result->status == "passed"): ?>
        <?php if($quiz->can_download_certificate): ?>
            <a href="/panel/quizzes/results/<?php echo e($quiz->result->id); ?>/showCertificate" target="_blank" class="btn btn-primary btn-lg"><?php echo e(trans('update.download_certificate')); ?></a>
        <?php endif; ?>

        <a href="/panel/quizzes/<?php echo e($quiz->result->id); ?>/result" target="_blank" class="btn btn-outline-primary btn-lg"><?php echo e(trans('update.view_answers')); ?></a>

    <?php elseif($quiz->result->status == "failed"): ?>
        <?php if($quiz->can_try): ?>
            <a href="/panel/quizzes/<?php echo e($quiz->id); ?>/overview" target="_blank" class="btn btn-primary btn-lg"><?php echo e(trans('public.try_again')); ?></a>
        <?php else: ?>
            <a href="/panel/quizzes/<?php echo e($quizResult->id); ?>/result" target="_blank" class="btn btn-primary btn-lg"><?php echo e(trans('update.view_answers')); ?></a>
        <?php endif; ?>

        <a href="/panel/quizzes/my-results" target="_blank" class="btn btn-outline-primary btn-lg"><?php echo e(trans('update.my_results')); ?></a>
    <?php endif; ?>
</div>

<?php if($quiz->result->status == "passed"): ?>
    <div class="d-flex align-items-center mt-16 font-12 text-gray-500">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-medal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        <span class="ml-4"><?php echo e(trans('update.you_achieved_a_certificate_for_passing_this_quiz')); ?></span>
    </div>
<?php elseif($quiz->result->status == "failed" and $quiz->can_try): ?>
    <div class="d-flex align-items-center mt-16 font-12 text-gray-500">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-refresh-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        <span class="ml-4">
            <?php if($quiz->remaining_try_again == "unlimited"): ?>
                <?php echo e(trans('update.you_have_unlimited_chances_to_try_again_this_quiz')); ?>

            <?php else: ?>
                <?php echo e(trans('update.you_have_n_other_chances_to_try_again_this_quiz', ['count' => $quiz->remaining_try_again])); ?>

            <?php endif; ?>
        </span>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/quiz/result_status.blade.php ENDPATH**/ ?>