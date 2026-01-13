<?php if(!empty($course->quizzes) and $course->quizzes->count()): ?>
    <?php $__currentLoopData = $course->quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quizRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $checkSequenceContent = $quizRow->checkSequenceContent();
            $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));
            $hasSequenceContentError = (!empty($checkSequenceContent) and $sequenceContentHasError);

            $quizStatus = $quizRow->getStatusByUser();
        ?>


        <div
                class="sidebar-content-item d-flex align-items-center justify-content-between mb-12 p-12 rounded-20 bg-gray-100 cursor-pointer <?php echo e($hasSequenceContentError ? 'js-sequence-content-error-modal' : 'js-content-tab-item'); ?>"
                data-type="quiz"
                data-id="<?php echo e($quizRow->id); ?>"
                data-passed-error="<?php echo e(!empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : ''); ?>"
                data-access-days-error="<?php echo e(!empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : ''); ?>"
        >
            <div class="d-flex align-items-center">
                <div class="position-relative d-flex-center size-48 rounded-12 bg-primary-20">
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

                    <?php if($hasSequenceContentError): ?>
                        <div class="sidebar-item-lock-icon d-flex-center rounded-circle bg-white">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-lock-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="ml-8">
                    <div class="font-weight-bold font-14 text-dark"><?php echo e(truncate($quizRow->title, 27)); ?></div>

                    <div class="d-flex align-items-center mt-4 font-12 text-gray-500">
                        <span class=""><?php echo e($quizRow->quizQuestions->count()); ?> <?php echo e(trans('public.questions')); ?></span>
                        <span class="sidebar-item-dot-separator mx-4 bg-gray-300"></span>
                        <span class=""><?php echo e($quizRow->time); ?> <?php echo e(trans('update.mins')); ?></span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-8">
                <?php if($quizStatus == "passed"): ?>
                    <div class="d-flex-center px-8 py-4 rounded-32 bg-success-30 text-success font-12"><?php echo e(trans('quiz.passed')); ?></div>
                <?php elseif($quizStatus == "failed"): ?>
                    <div class="d-flex-center px-8 py-4 rounded-32 bg-danger-30 text-danger font-12"><?php echo e(trans('quiz.failed')); ?></div>
                <?php elseif($quizStatus == "waiting"): ?>
                    <div class="d-flex-center px-8 py-4 rounded-32 bg-warning-30 text-warning font-12"><?php echo e(trans('quiz.waiting')); ?></div>
                <?php elseif($quizStatus == "not_participated"): ?>
                    <div class="d-flex-center px-8 py-4 rounded-32 bg-gray-200 text-gray-500 font-12"><?php echo e(trans('update.not_participated')); ?></div>
                <?php endif; ?>
            </div>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/sidebar/tabs/quizzes.blade.php ENDPATH**/ ?>