<?php if(!empty($course->chapters) and count($course->chapters)): ?>
    <div id="chaptersAccordion">
        <?php $__currentLoopData = $course->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="js-accordion-parent accordion p-12 rounded-20 bg-gray-100 mb-16">
                <div class="accordion__title d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center cursor-pointer" href="#collapseChapter<?php echo e($chapter->id); ?>" data-parent="#chaptersAccordion" role="button" data-toggle="collapse">
                        <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-category'); ?>
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
                            <div class="font-14 font-weight-bold"><?php echo e($chapter->title); ?></div>
                            <div class="d-flex align-items-center mt-4 font-12 text-gray-500"><?php echo e($chapter->getTopicsCount(true)); ?> <?php echo e(trans('public.parts')); ?></div>
                        </div>
                    </div>

                    <div class="js-accordion-collapse-arrow collapse-arrow-icon d-flex cursor-pointer" href="#collapseChapter<?php echo e($chapter->id); ?>" data-parent="#chaptersAccordion" role="button" data-toggle="collapse">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                </div>

                <div id="collapseChapter<?php echo e($chapter->id); ?>" class="js-accordion-collapse accordion__collapse pt-0 mt-20 border-0 " role="tabpanel">
                    <?php if(!empty($chapter->chapterItems) and count($chapter->chapterItems)): ?>
                        <?php $__currentLoopData = $chapter->chapterItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapterItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession and !empty($chapterItem->session) and $chapterItem->session->status == 'active'): ?>
                                <?php echo $__env->make('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.session' , ['session' => $chapterItem->session, 'type' => \App\Models\WebinarChapter::$chapterSession], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file) and $chapterItem->file->status == 'active'): ?>
                                <?php echo $__env->make('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.file' , ['file' => $chapterItem->file, 'type' => \App\Models\WebinarChapter::$chapterFile], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson and !empty($chapterItem->textLesson) and $chapterItem->textLesson->status == 'active'): ?>
                                <?php echo $__env->make('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.text_lesson' , ['textLesson' => $chapterItem->textLesson, 'type' => \App\Models\WebinarChapter::$chapterTextLesson], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment and !empty($chapterItem->assignment) and $chapterItem->assignment->status == 'active'): ?>
                                <?php echo $__env->make('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.assignment' ,['assignment' => $chapterItem->assignment], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz and !empty($chapterItem->quiz) and $chapterItem->quiz->status == 'active'): ?>
                                <?php echo $__env->make('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.quiz' ,['quiz' => $chapterItem->quiz, 'type' => 'quiz'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/sidebar/tabs/contents/chapters.blade.php ENDPATH**/ ?>