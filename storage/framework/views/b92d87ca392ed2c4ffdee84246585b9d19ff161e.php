<div class="bg-white p-16 rounded-24">

    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between p-16 rounded-12 bg-gray-100 border-gray-200">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-300 rounded-12">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-teacher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold"><?php echo e(trans('update.curriculum_overview')); ?></h5>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.this_course_includes_n_modules_n_lessons_and_n_hours_of_materials', ['chapters' => $course->chapters->count(), 'sections' => $webinarContentCount, 'hours' => convertMinutesToHourAndMinute($course->getAllChaptersDurations())])); ?></p>
            </div>
        </div>

        <?php if($hasBought or !empty($course->getInstallmentOrder())): ?>
            <a href="<?php echo e($course->getLearningPageUrl()); ?>" class="d-flex align-items-center text-gray-500 mt-16 mt-lg-0">
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
                <span class="ml-4"><?php echo e(trans('update.learning_page')); ?></span>
            </a>
        <?php endif; ?>
    </div>


    

    <?php if(!empty($course->chapters) and count($course->chapters)): ?>
        <section class="">
            <?php echo $__env->make('design_1.web.courses.show.tabs.contents.chapters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </section>
    <?php endif; ?>

    
    <?php if(!empty($sessionsWithoutChapter) and count($sessionsWithoutChapter)): ?>
        <section class="mt-16" id="sessionsAccordion">
            <?php $__currentLoopData = $sessionsWithoutChapter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('design_1.web.courses.show.tabs.contents.sessions' , ['session' => $session, 'accordionParent' => 'sessionsAccordion'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>
    <?php endif; ?>

    
    <?php if(!empty($filesWithoutChapter) and count($filesWithoutChapter)): ?>
        <section class="mt-16" id="filesAccordion">
            <?php $__currentLoopData = $filesWithoutChapter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('design_1.web.courses.show.tabs.contents.files' , ['file' => $file, 'accordionParent' => 'filesAccordion'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>
    <?php endif; ?>

    

    <?php if(!empty($textLessonsWithoutChapter) and count($textLessonsWithoutChapter)): ?>
        <section class="mt-16" id="textLessonsAccordion">
            <?php $__currentLoopData = $textLessonsWithoutChapter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $textLesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('design_1.web.courses.show.tabs.contents.text_lessons' , ['textLesson' => $textLesson, 'accordionParent' => 'textLessonsAccordion'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>
    <?php endif; ?>


    
    <?php if(!empty($quizzes) and $quizzes->count() > 0): ?>
        <section class="mt-16" id="quizAccordion">
            <?php $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('design_1.web.courses.show.tabs.contents.quiz' , ['quiz' => $quiz, 'accordionParent' => 'quizAccordion'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>
    <?php endif; ?>

    
    <?php echo $__env->make('design_1.web.courses.show.tabs.contents.all_certificates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/tabs/content.blade.php ENDPATH**/ ?>