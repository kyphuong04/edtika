<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white p-16 rounded-16 mb-56">

        <div class="js-quiz-main-page-form row">
            
            <div class="col-12 col-lg-6">
                <h3 class="font-16 font-weight-bold"><?php echo e(trans('public.basic_information')); ?></h3>

                <?php echo $__env->make('design_1.panel.quizzes.create.quiz_form', ['isQuizPage' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="col-12 col-lg-6">
                <?php echo $__env->make('design_1.panel.quizzes.create.questions_list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between border-top-gray-100 col-12 pt-16 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-14"><?php echo e(trans('update.notice')); ?></h5>
                        <p class="mt-2 font-12 text-gray-500"><?php echo e(trans('update.the_Support_message_sending_hint')); ?></p>
                    </div>
                </div>

                <button type="button" class="js-submit-quiz-form-main-page btn btn-primary mt-20 mt-lg-0"><?php echo e(trans('public.save')); ?></button>
            </div>

        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var saveSuccessLang = '<?php echo e(trans('webinars.success_store')); ?>';
        var quizzesSectionLang = '<?php echo e(trans('quiz.quizzes_section')); ?>';
        var newQuestionLang = '<?php echo e(trans('update.new_question')); ?>';
        var editQuestionLang = '<?php echo e(trans('update.edit_question')); ?>';
        var saveLang = '<?php echo e(trans('public.save')); ?>';
        var closeLang = '<?php echo e(trans('public.close')); ?>';
    </script>

    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script src="/assets/design_1/js/panel/quiz_create.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/create/index.blade.php ENDPATH**/ ?>