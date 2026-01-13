<div class="d-flex-center flex-column text-center mt-24">
    <div class="">
        <img src="/assets/design_1/img/panel/quiz/status/pending.svg" alt="<?php echo e(trans('quiz.passed')); ?>" class="img-fluid" width="294.63px" height="280px">
    </div>

    <h1 class="font-24 font-weight-bold mt-16"><?php echo e(trans('update.quiz_passed_successfully!')); ?></h1>
    <p class="text-gray-500 mt-8"><?php echo e(trans('update.quiz_passed_successfully_hint')); ?></p>

    <div class="d-flex-center size-48 mt-36">
        <?php if(!empty($quiz->icon)): ?>
            <img src="<?php echo e($quiz->icon); ?>" class="img-cover rounded-12">
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '48px','height' => '48px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="mt-8 font-12 font-weight-bold text-gray-500"><?php echo e($quiz->title); ?></div>
    <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('public.by')); ?> <?php echo e($quiz->creator->full_name); ?></div>

    <div class="d-flex align-items-center flex-wrap gap-16 mt-16">
        <?php if($quiz->certificate): ?>
            <a href="/panel/quizzes/results/<?php echo e($quizResult->id); ?>/showCertificate" class="btn btn-primary btn-lg"><?php echo e(trans('quiz.download_certificate')); ?></a>
        <?php elseif(!empty($webinar)): ?>
            <a href="<?php echo e($webinar->getLearningPageUrl()); ?>" class="btn btn-primary btn-lg"><?php echo e(trans('update.back_to_learning_page')); ?></a>
        <?php endif; ?>

        <a href="/panel/quizzes/results/<?php echo e($quizResult->id); ?>/details" class="btn btn-outline-primary btn-lg"><?php echo e(trans('update.view_answers')); ?></a>
    </div>

    <?php if($quiz->certificate): ?>
        <div class="d-flex align-items-center font-12 text-gray-500 mt-16">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-medal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            <span class="ml-4"><?php echo e(trans('update.you_achieved_a_certificate_for_passing_this_quiz')); ?></span>
        </div>
    <?php endif; ?>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/holding/status/passed.blade.php ENDPATH**/ ?>