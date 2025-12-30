<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
<?php $__env->stopPush(); ?>

<div class="bg-white rounded-16 p-16 mt-32">


    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-category-2'); ?>
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
                <h5 class="font-14 font-weight-bold"><?php echo e(trans('public.chapters')); ?></h5>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.define_different_sections_and_organize_the_content_inside_them')); ?></p>
            </div>
        </div>

        <div class="js-add-chapter d-flex align-items-center cursor-pointer" data-webinar-id="<?php echo e($webinar->id); ?>">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            <span class="text-primary ml-4"><?php echo e(trans('public.new_chapter')); ?></span>
        </div>
    </div>

    
    <?php echo $__env->make('design_1.panel.webinars.create.includes.chapter_contents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>



<?php if($webinar->isWebinar()): ?>
    <div id="newSessionForm" class="d-none">
        <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.session',['webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php endif; ?>

<div id="newFileForm" class="d-none">
    <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.file',['webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<?php if(getFeaturesSettings('new_interactive_file')): ?>
    <div id="newInteractiveFileForm" class="d-none">
        <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.interactive_file',['webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php endif; ?>

<div id="newTextLessonForm" class="d-none">
    <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.text_lesson',['webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<?php if(getFeaturesSettings('webinar_assignment_status')): ?>
    <div id="newAssignmentForm" class="d-none">
        <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.assignment',['webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php endif; ?>

<div id="newQuizForm" class="d-none">
    <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.quiz',['webinar' => $webinar, 'quizInfo' => null, 'webinarChapterPages' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<div id="changeChapterModalHtml" class="d-none">
    <?php echo $__env->make("design_1.panel.webinars.create.modals.change_chapter", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var newChapterLang = '<?php echo e(trans('public.new_chapter')); ?>';
        var editChapterLang = '<?php echo e(trans('public.edit_chapter')); ?>';
        var saveLang = '<?php echo e(trans('public.save')); ?>';
        var closeLang = '<?php echo e(trans('public.close')); ?>';
        var saveSuccessLang = '<?php echo e(trans('webinars.success_store')); ?>';
        var quizzesSectionLang = '<?php echo e(trans('quiz.quizzes_section')); ?>';
        var newQuestionLang = '<?php echo e(trans('update.new_question')); ?>';
        var editQuestionLang = '<?php echo e(trans('update.edit_question')); ?>';
        var changeChapterLang = '<?php echo e(trans('update.change_chapter')); ?>';

    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    <script src="/assets/design_1/js/panel/quiz_create.min.js"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/steps/step_4.blade.php ENDPATH**/ ?>