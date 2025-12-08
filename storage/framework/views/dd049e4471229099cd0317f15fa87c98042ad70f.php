<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("quiz")); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <form id="quizHoldingForm" action="<?php echo e(!empty($newQuizStart) ? '/panel/quizzes/'. $newQuizStart->quiz->id .'/update-result' : ''); ?> " method="post">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" name="quiz_result_id" value="<?php echo e(!empty($newQuizStart) ? $newQuizStart->id : ''); ?>" class="form-control" placeholder=""/>
        <input type="hidden" name="attempt_number" value="<?php echo e($numberOfAttempt); ?>" class="form-control" placeholder=""/>
        <input type="hidden" class="js-quiz-question-count" value="<?php echo e($quizQuestions->count()); ?>"/>

        <div class="container mt-80 pb-120">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">

                    
                    <?php echo $__env->make('design_1.panel.quizzes.holding.result.top_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    
                    <div class="quiz-content-card bg-white rounded-32 p-16 mt-20">

                        
                        <div class="position-relative d-flex align-items-center justify-content-between">
                            <div class="d-flex-center bg-gray-100 py-4 px-8 rounded-32 text-gray-500 font-12">
                                <span class=""><?php echo e(trans('public.questions')); ?></span>
                                <span class="js-question-count-text ml-4">1/<?php echo e($quizQuestions->count()); ?></span>
                            </div>

                        </div>

                        
                        <div class="quiz-content-separator d-flex align-items-center mt-16 mb-8">
                            <div class="flex-1 border-top-gray-200"></div>
                        </div>

                        
                        <?php echo $__env->make('design_1.panel.quizzes.holding.result.questions_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    </div>

                </div>
            </div>
        </div>

        <div class="quiz-holding-footer bg-white py-16 soft-shadow-2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8">
                        <div class="d-flex align-items-center justify-content-between">

                            <div class="d-flex align-items-center">
                                <div class="js-previous-btn d-flex-center size-48 rounded-circle bg-gray-100 bg-hover-gray-200 mr-16 text-gray-500">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons ','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>

                                <div class="js-next-btn d-flex-center size-48 rounded-circle bg-gray-100 bg-hover-gray-200 <?php echo e(($quizQuestions->count() > 1) ? 'text-primary cursor-pointer' : 'text-gray-500'); ?>">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons ','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>

                            </div>

                            <?php if(!empty($newQuizStart)): ?>
                                <button type="button" class="js-finish-btn btn btn-lg btn-danger"><?php echo e(trans('update.finish_quiz')); ?></button>
                            <?php else: ?>
                                <a href="/panel/quizzes/my-results" class="btn btn-lg btn-primary"><?php echo e(trans('quiz.my_quizzes')); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="quiz-holding-footer__progressbar"></div>
        </div>

    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var cancelLang = '<?php echo e(trans('public.cancel')); ?>';
        var quizFinishTitle = '<?php echo e(trans('update.finish_quiz')); ?>';
        var quizFinishHint = '<?php echo trans('update.finish_quiz_hint'); ?>';
        var confirmLang = '<?php echo e(trans('update.finish_quiz_confirm')); ?>';
    </script>

    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
    <script src="/assets/default/vendors/jquery.simple.timer/jquery.simple.timer.js"></script>

    <script src="<?php echo e(getDesign1ScriptPath("quiz_start", "panel")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.web.layouts.app', ['appFooter' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/holding/result/index.blade.php ENDPATH**/ ?>