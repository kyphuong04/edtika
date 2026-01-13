<?php if(!empty($webinar->chapters) and count($webinar->chapters)): ?>
    <ul class="draggable-content-lists draggable-webinar-chapters" data-path="/panel/webinar_chapters/orders" data-drag-class="draggable-webinar-chapters">

        <?php $__currentLoopData = $webinar->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li data-id="<?php echo e($chapter->id); ?>" data-chapter-order="<?php echo e($chapter->order); ?>" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
                <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="webinar_chapter_<?php echo e($chapter->id); ?>">

                    <div class="d-flex align-items-center cursor-pointer" href="#collapsePricePlan<?php echo e($chapter->id); ?>" data-parent="#webinar_chaptersAccordion" role="button" data-toggle="collapse">
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
                            <h5 class="font-14 font-weight-bold"><?php echo e($chapter->title); ?></h5>
                            <p class="mt-4 font-12 text-gray-500"><?php echo e(!empty($chapter->chapterItems) ? count($chapter->chapterItems) : 0); ?> <?php echo e(trans('public.topic')); ?> | <?php echo e(convertMinutesToHourAndMinute($chapter->getDuration())); ?> <?php echo e(trans('public.hr')); ?></p>
                        </div>
                    </div>


                    <div class="d-flex align-items-center">

                        <?php if($chapter->status != \App\Models\WebinarChapter::$chapterActive): ?>
                            <span class="px-8 py-4 bg-danger-30 text-danger font-12 mr-12"><?php echo e(trans('public.disabled')); ?></span>
                        <?php endif; ?>

                        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center mr-12">
                            <button type="button" class="d-flex-center btn-transparent">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </button>

                            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
                                <ul class="my-8">

                                    <?php if($webinar->isWebinar()): ?>
                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <button type="button" class="js-add-course-content-btn" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="session" data-chapter="<?php echo e($chapter->id); ?>">
                                                <?php echo e(trans('public.add_session')); ?>

                                            </button>
                                        </li>
                                    <?php endif; ?>

                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <button type="button" class="js-add-course-content-btn" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="file" data-chapter="<?php echo e($chapter->id); ?>">
                                            <?php echo e(trans('public.add_file')); ?>

                                        </button>
                                    </li>

                                    <?php if(getFeaturesSettings('new_interactive_file')): ?>
                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <button type="button" class="js-add-course-content-btn" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="new_interactive_file" data-chapter="<?php echo e($chapter->id); ?>">
                                                <?php echo e(trans('update.new_interactive_file')); ?>

                                            </button>
                                        </li>
                                    <?php endif; ?>

                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <button type="button" class="js-add-course-content-btn" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="text_lesson" data-chapter="<?php echo e($chapter->id); ?>">
                                            <?php echo e(trans('public.add_text_lesson')); ?>

                                        </button>
                                    </li>

                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <button type="button" class="js-add-course-content-btn" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="quiz" data-chapter="<?php echo e($chapter->id); ?>">
                                            <?php echo e(trans('public.add_quiz')); ?>

                                        </button>
                                    </li>

                                    <?php if(getFeaturesSettings('webinar_assignment_status')): ?>
                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <button type="button" class="js-add-course-content-btn" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="assignment" data-chapter="<?php echo e($chapter->id); ?>">
                                                <?php echo e(trans('update.add_new_assignments')); ?>

                                            </button>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                        </div>

                        <button type="button" class="js-add-chapter btn-transparent text-gray-500 mr-12" data-webinar-id="<?php echo e($webinar->id); ?>" data-chapter="<?php echo e($chapter->id); ?>" data-tippy-content="<?php echo e(trans('public.edit_chapter')); ?>">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-edit-2'); ?>
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
                        </button>

                        <a href="/panel/chapters/<?php echo e($chapter->id); ?>/delete" class="delete-action text-gray-500 mr-12" data-tippy-content="<?php echo e(trans('public.delete')); ?>">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-trash'); ?>
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
                        </a>

                        <span class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="<?php echo e(trans('update.sort')); ?>">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-3'); ?>
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
                        </span>

                        <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapsePricePlan<?php echo e($chapter->id); ?>" data-parent="#webinar_chaptersAccordion" role="button" data-toggle="collapse">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
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
                        </span>
                    </div>

                </div>

                <div id="collapsePricePlan<?php echo e($chapter->id); ?>" class="accordion__collapse show" role="tabpanel">


                    <div class="accordion-content-wrapper mt-20" id="chapterContentAccordion<?php echo e($chapter->id); ?>" role="tablist" aria-multiselectable="true">
                        <?php if(!empty($chapter->chapterItems) and count($chapter->chapterItems)): ?>
                            <ul class="draggable-content-lists draggable-lists-chapter-<?php echo e($chapter->id); ?>" data-path="/panel/webinar_chapters/items/orders" data-drag-class="draggable-lists-chapter-<?php echo e($chapter->id); ?>">
                                <?php $__currentLoopData = $chapter->chapterItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapterItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession and !empty($chapterItem->session)): ?>
                                        <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.session' ,['session' => $chapterItem->session , 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file)): ?>
                                        <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.file' ,['file' => $chapterItem->file , 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson and !empty($chapterItem->textLesson)): ?>
                                        <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.text_lesson' ,['textLesson' => $chapterItem->textLesson, 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment and !empty($chapterItem->assignment)): ?>
                                        <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.assignment' ,['assignment' => $chapterItem->assignment , 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz and !empty($chapterItem->quiz)): ?>
                                        <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.quiz' ,['quizInfo' => $chapterItem->quiz , 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endif; ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php else: ?>
                            <div class="d-flex-center flex-column px-32 py-120 text-center">
                                <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-note-2'); ?>
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
                                </div>
                                <h3 class="font-16 font-weight-bold mt-12"><?php echo e(trans('update.chapter_content_no_result')); ?></h3>
                                <p class="mt-4 font-12 text-gray-500"><?php echo trans('update.chapter_content_no_result_hint'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </ul>
<?php else: ?>
    <div class="d-flex-center flex-column px-32 py-120 text-center">
        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document'); ?>
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
        </div>
        <h3 class="font-16 font-weight-bold mt-12"><?php echo e(trans('update.chapter_no_result')); ?></h3>
        <p class="mt-4 font-12 text-gray-500"><?php echo trans('update.chapter_no_result_hint'); ?></p>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/includes/chapter_contents.blade.php ENDPATH**/ ?>