<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("quiz")); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <div class="container my-64">
        <div class="row justify-content-center">
            <div class="col-12 mt-72 col-lg-8">
                <div class="bg-white p-16 rounded-32">

                    
                    <div class="d-flex align-items-center flex-wrap gap-16">

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-timer-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <div class="ml-8">
                                <?php if(!empty($quiz->time)): ?>
                                    <span class="d-block font-16 font-weight-bold"><?php echo e($quiz->time); ?> <?php echo e(trans('update.mins')); ?></span>
                                <?php else: ?>
                                    <span class="d-block font-16 font-weight-bold"><?php echo e(trans('quiz.unlimited')); ?></span>
                                <?php endif; ?>

                                <span class="d-block font-12 text-gray-500"><?php echo e(trans('update.quiz_time')); ?></span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold"><?php echo e($totalQuestionsCount); ?></span>
                                <span class="d-block font-12 text-gray-500"><?php echo e(trans('public.questions')); ?></span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold"><?php echo e($quiz->pass_mark); ?>/<?php echo e($quizQuestions->sum('grade')); ?></span>
                                <span class="d-block font-12 text-gray-500"><?php echo e(trans('public.pass_mark')); ?></span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center bg-gray-100 p-24 rounded-16 flex-1">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-refresh-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <div class="ml-8">
                                <span class="d-block font-16 font-weight-bold"><?php echo e($attemptCount); ?>/<?php echo e(!empty($quiz->attempt) ? $quiz->attempt : trans('update.unlimited')); ?></span>
                                <span class="d-block font-12 text-gray-500"><?php echo e(trans('update.attempts')); ?></span>
                            </div>
                        </div>

                    </div>


                    <div class="d-flex-center flex-column text-center mt-48">
                        <div class="d-flex-center size-80">
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
<?php $component->withAttributes(['class' => 'icons text-success','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <h1 class="font-24 font-weight-bold mt-12"><?php echo e($quiz->title); ?></h1>

                        <?php if(!empty($quiz->description)): ?>
                            <p class="mt-8 font-14 text-gray-500 mx-32"><?php echo e($quiz->description); ?></p>
                        <?php endif; ?>

                        <div class="quiz-overview-center-line mt-8 bg-gray-400"></div>

                        <div class="size-48 rounded-circle mt-8">
                            <img src="<?php echo e($quiz->creator->getAvatar(48)); ?>" alt="<?php echo e($quiz->creator->full_name); ?>" class="img-cover rounded-circle">
                        </div>

                        <div class="mt-8 font-12 font-weight-bold text-gray-500"><?php echo e($webinar->title); ?></div>

                        <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('public.by')); ?> <?php echo e($quiz->creator->full_name); ?></div>

                        <?php
                            $canStart = true;

                            if (!$quiz->checkCanAccessByExpireDays()) {
                                $canStart = false;
                            }

                            if (!$quiz->checkUserCanStartByAttempt()) {
                                $canStart = false;
                            }

                            $expireTimestamp = $quiz->getExpireTimestamp();
                        ?>

                        <?php if($canStart): ?>
                            <a href="/panel/quizzes/<?php echo e($quiz->id); ?>/start" class="btn btn-primary btn-lg mt-16"><?php echo e(trans('update.start_quiz')); ?></a>
                        <?php endif; ?>


                        <div class="d-flex align-items-center flex-wrap gap-32 mb-48">

                            <?php if(!empty($quiz->certificate)): ?>
                                <div class="d-flex align-items-center mt-16">
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
                                    <span class="ml-4 text-gray-500 font-12"><?php echo e(trans('update.include_certificate')); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($expireTimestamp)): ?>
                                <?php
                                    $expireToday = checkTimestampInToday($expireTimestamp);
                                    $expireClassName = $expireToday ? 'text-warning' : ($expireTimestamp < time() ? 'text-danger' : 'text-gray-500')
                                ?>

                                <div class="d-flex align-items-center mt-16">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons '.e($expireClassName).'','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="ml-4 font-12 <?php echo e($expireClassName); ?>"><?php echo e(trans('update.expired_on_date', ['date' => dateTimeFormat($expireTimestamp, 'j M Y H:i')])); ?></span>
                                </div>
                            <?php endif; ?>

                        </div>

                    </div>

                    <?php if($quiz->hasDescriptiveQuestion()): ?>
                        <div class="d-flex align-items-center mt-48 border-dashed border-gray-300 rounded-16 p-12">
                            <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-award'); ?>
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

                            <div class="ml-8">
                                <h5 class="font-14 font-weight-bold"><?php echo e(trans('update.descriptive_quiz')); ?></h5>
                                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.your_quiz_result_will_be_displayed_after_instructor_review')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.web.layouts.app', ['appFooter' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/holding/overview.blade.php ENDPATH**/ ?>