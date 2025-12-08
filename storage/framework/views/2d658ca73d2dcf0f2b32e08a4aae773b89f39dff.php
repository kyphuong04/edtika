<?php if(!empty($pendingReviewQuizzesResults) and count($pendingReviewQuizzesResults)): ?>
    <div class="mt-28">
        <div class="">
            <h3 class="font-16 text-dark"><?php echo e(trans('update.pending_review_quizzes')); ?></h3>
            <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.you_have_quizzes_waiting_for_your_review_please_review_them_to_calculate_your_student_grade')); ?></p>
        </div>

        <div class="row">

            <?php $__currentLoopData = $pendingReviewQuizzesResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendingReviewQuizResult): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-md-6 col-lg-3 mt-16">
                    <div class="position-relative most-active-assignment-card">
                        <div class="most-active-assignment-card__mask"></div>

                        <a href="<?php echo e("/panel/quizzes/results/{$pendingReviewQuizResult->id}/edit"); ?>" class="d-block text-decoration-none">
                            <div class="position-relative z-index-2 bg-white p-20 rounded-16">
                                <div class="d-flex align-items-center">
                                    <?php if(!empty($pendingReviewQuizResult->quiz->icon)): ?>
                                        <div class="d-flex-center size-64">
                                            <img src="<?php echo e($pendingReviewQuizResult->quiz->icon); ?>" alt="" class="img-fluid">
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex-center size-64 rounded-circle bg-gray-100">
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

                                    <div class="ml-8">
                                        <h6 class="font-14 font-weight-bold text-dark"><?php echo e(truncate($pendingReviewQuizResult->quiz->title, 28)); ?></h6>

                                        <?php if(!empty($pendingReviewQuizResult->quiz->webinar)): ?>
                                            <p class="font-12 text-gray-500 mt-4"><?php echo e(truncate($pendingReviewQuizResult->quiz->webinar->title, 32)); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-20 pt-20 border-top-gray-100">
                                    <div class="d-flex align-items-center">
                                        <div class="size-40 rounded-circle bg-gray-100">
                                            <img src="<?php echo e($pendingReviewQuizResult->user->getAvatar(40)); ?>" alt="" class="img-cover rounded-circle">
                                        </div>
                                        <div class="ml-8">
                                            <h6 class="font-14 text-dark"><?php echo e($pendingReviewQuizResult->user->full_name); ?></h6>
                                            <p class="font-12 text-gray-500 mt-2"><?php echo e(dateTimeFormat($pendingReviewQuizResult->created_at, 'j M Y H:i')); ?></p>
                                        </div>
                                    </div>

                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/results/pending_reviews.blade.php ENDPATH**/ ?>