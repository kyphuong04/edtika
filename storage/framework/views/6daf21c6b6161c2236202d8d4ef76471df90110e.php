<div class="row mt-10">
    <div class="col-12">
        <div class="accordion-content-wrapper mt-15" id="chapterAccordion" role="tablist" aria-multiselectable="true">
            <?php if(!empty($webinar->chapters) and count($webinar->chapters)): ?>
                <ul class="draggable-content-lists draggable-lists-chapter" data-drag-class="draggable-lists-chapter" data-order-table="webinar_chapters">
                    <?php $__currentLoopData = $webinar->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <li data-id="<?php echo e(!empty($chapter) ? $chapter->id :''); ?>" data-chapter-order="<?php echo e($chapter->order); ?>" class="accordion-row bg-white rounded-12 mt-20 py-15 py-lg-30 px-10 px-lg-20">
                            <div class="d-flex align-items-center justify-content-between " role="tab" id="chapter_<?php echo e(!empty($chapter) ? $chapter->id :'record'); ?>">
                                <div class="d-flex align-items-center" href="#collapseChapter<?php echo e(!empty($chapter) ? $chapter->id :'record'); ?>" aria-controls="collapseChapter<?php echo e(!empty($chapter) ? $chapter->id :'record'); ?>" data-parent="#chapterAccordion" role="button" data-toggle="collapse" aria-expanded="true">
                                    <span class="chapter-icon mr-10">
                                        <i data-feather="grid" class=""></i>
                                    </span>
                                    <div class="">
                                        <span class="font-weight-bold text-dark-blue d-block cursor-pointer"><?php echo e(!empty($chapter) ? $chapter->title : trans('public.add_new_chapter')); ?></span>
                                        <span class="font-12 text-gray-500 d-block">
                                            <?php echo e(!empty($chapter->chapterItems) ? count($chapter->chapterItems) : 0); ?> <?php echo e(trans('public.topic')); ?>

                                            | <?php echo e(convertMinutesToHourAndMinute($chapter->getDuration())); ?> <?php echo e(trans('public.hr')); ?>

                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">

                                    <?php if($chapter->status != \App\Models\WebinarChapter::$chapterActive): ?>
                                        <span class="disabled-content-badge mr-10"><?php echo e(trans('public.disabled')); ?></span>
                                    <?php endif; ?>

                                    <div class="btn-group dropdown table-actions">
                                        <button type="button" class="add-course-content-btn mr-10 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i data-feather="plus" class=""></i>
                                        </button>
                                        <div class="dropdown-menu ">
                                            <?php if($webinar->isWebinar()): ?>
                                                <button type="button" class="js-add-course-content-btn d-block mb-10 btn-transparent" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="session" data-chapter="<?php echo e(!empty($chapter) ? $chapter->id :''); ?>">
                                                    <?php echo e(trans('public.add_session')); ?>

                                                </button>
                                            <?php endif; ?>

                                            <button type="button" class="js-add-course-content-btn d-block mb-10 btn-transparent" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="file" data-chapter="<?php echo e(!empty($chapter) ? $chapter->id :''); ?>">
                                                <?php echo e(trans('public.add_file')); ?>

                                            </button>

                                            <?php if(getFeaturesSettings('new_interactive_file')): ?>
                                                <button type="button" class="js-add-course-content-btn d-block mb-10 btn-transparent" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="new_interactive_file" data-chapter="<?php echo e(!empty($chapter) ? $chapter->id :''); ?>">
                                                    <?php echo e(trans('update.new_interactive_file')); ?>

                                                </button>
                                            <?php endif; ?>


                                            <button type="button" class="js-add-course-content-btn d-block mb-10 btn-transparent" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="text_lesson" data-chapter="<?php echo e(!empty($chapter) ? $chapter->id :''); ?>">
                                                <?php echo e(trans('public.add_text_lesson')); ?>

                                            </button>

                                            <button type="button" class="js-add-course-content-btn d-block mb-10 btn-transparent" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="quiz" data-chapter="<?php echo e(!empty($chapter) ? $chapter->id :''); ?>">
                                                <?php echo e(trans('public.add_quiz')); ?>

                                            </button>

                                            <?php if(getFeaturesSettings('webinar_assignment_status')): ?>
                                                <button type="button" class="js-add-course-content-btn d-block mb-10 btn-transparent" data-webinar-id="<?php echo e($webinar->id); ?>" data-type="assignment" data-chapter="<?php echo e(!empty($chapter) ? $chapter->id :''); ?>">
                                                    <?php echo e(trans('update.add_new_assignments')); ?>

                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <button type="button" class="js-add-chapter btn-transparent text-gray-500" data-webinar-id="<?php echo e($webinar->id); ?>" data-chapter="<?php echo e($chapter->id); ?>" data-locale="<?php echo e(mb_strtoupper($chapter->locale)); ?>">
                                        <i data-feather="edit-3" class="mr-10 cursor-pointer" height="20"></i>
                                    </button>

                                    <a href="<?php echo e(getAdminPanelUrl()); ?>/chapters/<?php echo e($chapter->id); ?>/delete" class="delete-action btn btn-sm btn-transparent text-gray-500">
                                        <i data-feather="trash-2" class="mr-10 cursor-pointer" height="20"></i>
                                    </a>

                                    <i data-feather="move" class="move-icon mr-10 cursor-pointer text-gray-500" height="20"></i>

                                    <i class="collapse-chevron-icon feather-chevron-up text-gray-500" data-feather="chevron-down" height="20" href="#collapseChapter<?php echo e(!empty($chapter) ? $chapter->id :'record'); ?>" aria-controls="collapseChapter<?php echo e(!empty($chapter) ? $chapter->id :'record'); ?>" data-parent="#chapterAccordion" role="button" data-toggle="collapse" aria-expanded="true"></i>
                                </div>
                            </div>

                            <div id="collapseChapter<?php echo e(!empty($chapter) ? $chapter->id :'record'); ?>" aria-labelledby="chapter_<?php echo e(!empty($chapter) ? $chapter->id :'record'); ?>" class=" collapse show" role="tabpanel">
                                <div class="panel-collapse text-gray-500">

                                    <div class="accordion-content-wrapper mt-15" id="chapterContentAccordion<?php echo e(!empty($chapter) ? $chapter->id :''); ?>" role="tablist" aria-multiselectable="true">
                                        <?php if(!empty($chapter->chapterItems) and count($chapter->chapterItems)): ?>
                                            <ul class="draggable-content-lists draggable-lists-chapter-<?php echo e($chapter->id); ?>" data-drag-class="draggable-lists-chapter-<?php echo e($chapter->id); ?>" data-order-table="webinar_chapter_items">
                                                <?php $__currentLoopData = $chapter->chapterItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapterItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession and !empty($chapterItem->session)): ?>
                                                        <?php echo $__env->make('admin.webinars.create_includes.accordions.session' ,['session' => $chapterItem->session , 'chapter' => $chapter, 'chapterItem' => $chapterItem], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                    <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file)): ?>
                                                        <?php echo $__env->make('admin.webinars.create_includes.accordions.file' ,['file' => $chapterItem->file , 'chapter' => $chapter, 'chapterItem' => $chapterItem], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                    <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson and !empty($chapterItem->textLesson)): ?>
                                                        <?php echo $__env->make('admin.webinars.create_includes.accordions.text-lesson' ,['textLesson' => $chapterItem->textLesson , 'chapter' => $chapter, 'chapterItem' => $chapterItem], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                    <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment and !empty($chapterItem->assignment)): ?>
                                                        <?php echo $__env->make('admin.webinars.create_includes.accordions.assignment' ,['assignment' => $chapterItem->assignment , 'chapter' => $chapter, 'chapterItem' => $chapterItem], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                    <?php elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz and !empty($chapterItem->quiz)): ?>
                                                        <?php echo $__env->make('admin.webinars.create_includes.accordions.quiz' ,['quizInfo' => $chapterItem->quiz , 'chapter' => $chapter, 'chapterItem' => $chapterItem], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                                <h3 class="font-16 font-weight-bold mt-12 text-dark"><?php echo e(trans('update.chapter_content_no_result')); ?></h3>
                                                <p class="mt-4 font-12 text-gray-500"><?php echo trans('update.chapter_content_no_result_hint'); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

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
        </div>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/webinars/create_includes/accordions/chapter.blade.php ENDPATH**/ ?>