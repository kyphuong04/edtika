<li data-id="<?php echo e(!empty($chapterItem) ? $chapterItem->id :''); ?>" class="accordion bg-white border-gray-200 p-12 rounded-16 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between " role="tab" id="text_lesson_<?php echo e(!empty($textLesson) ? $textLesson->id :'record'); ?>">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseTextLesson<?php echo e(!empty($textLesson) ? $textLesson->id :'record'); ?>" aria-controls="collapseTextLesson<?php echo e(!empty($textLesson) ? $textLesson->id :'record'); ?>" data-parent="#chapterContentAccordion<?php echo e(!empty($chapter) ? $chapter->id :''); ?>" role="button" data-toggle="collapse" aria-expanded="true">
            <div class="d-flex mr-8">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-document-text'); ?>
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
            </div>

            <div class="font-14 font-weight-bold d-block"><?php echo e(!empty($textLesson) ? $textLesson->title . ($textLesson->accessibility == 'free' ? " (". trans('public.free') .")" : '') : trans('public.add_new_test_lesson')); ?></div>
        </div>

        <div class="d-flex align-items-center">

            <?php if(!empty($textLesson)): ?>
                <?php if($textLesson->status != \App\Models\WebinarChapter::$chapterActive): ?>
                    <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8"><?php echo e(trans('public.disabled')); ?></span>
                <?php endif; ?>


                <div class="js-change-content-chapter cursor-pointer mr-12" data-item-id="<?php echo e($textLesson->id); ?>" data-item-type="<?php echo e(\App\Models\WebinarChapterItem::$chapterTextLesson); ?>" data-chapter-id="<?php echo e(!empty($chapter) ? $chapter->id : ''); ?>" data-tippy-content="<?php echo e(trans('public.edit_chapter')); ?>">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-category-2'); ?>
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
                </div>


                <div class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="<?php echo e(trans('update.sort')); ?>">
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
                </div>


                <a href="/panel/text-lesson/<?php echo e($textLesson->id); ?>/delete" class="delete-action d-flex text-gray-500 mr-12">
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
            <?php endif; ?>

            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseTextLesson<?php echo e(!empty($textLesson) ? $textLesson->id :'record'); ?>" aria-controls="collapseTextLesson<?php echo e(!empty($textLesson) ? $textLesson->id :'record'); ?>" data-parent="#chapterContentAccordion<?php echo e(!empty($chapter) ? $chapter->id :''); ?>" role="button" data-toggle="collapse" aria-expanded="true">
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
            </div>
        </div>
    </div>

    <div id="collapseTextLesson<?php echo e(!empty($textLesson) ? $textLesson->id :'record'); ?>" class=" collapse <?php if(empty($textLesson)): ?> show <?php endif; ?>" role="tabpanel">
        <div class="js-content-form text_lesson-form" data-action="/panel/text-lesson/<?php echo e(!empty($textLesson) ? $textLesson->id . '/update' : 'store'); ?>">
            <input type="hidden" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][webinar_id]" value="<?php echo e(!empty($webinar) ? $webinar->id :''); ?>">


            <div class="mt-20">
                <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
                    'itemRow' => !empty($textLesson) ? $textLesson : null,
                    'withoutReloadLocale' => true,
                    'extraClass' => 'js-webinar-content-locale',
                    'extraData' => "data-webinar-id='".(!empty($webinar) ? $webinar->id : '')."'  data-id='".(!empty($textLesson) ? $textLesson->id : '')."'  data-relation='textLessons' data-fields='title,summary,content'"
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <?php if(!empty($textLesson)): ?>
                <div class="form-group ">
                    <label class="form-group-label"><?php echo e(trans('public.chapter')); ?></label>
                    <select name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][chapter_id]" class="js-ajax-chapter_id form-control select2">
                        <?php $__currentLoopData = $webinar->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ch->id); ?>" <?php echo e(($textLesson->chapter_id == $ch->id) ? 'selected' : ''); ?>><?php echo e($ch->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            <?php else: ?>
                <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('public.title')); ?></label>
                <input type="text" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][title]" class="js-ajax-title form-control" value="<?php echo e(!empty($textLesson) ? $textLesson->title : ''); ?>" placeholder=""/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('public.study_time')); ?></label>
                <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4"><?php echo e(trans('public.minutes')); ?></span>
                <input type="number" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][study_time]" class="js-ajax-study_time form-control" value="<?php echo e(!empty($textLesson) ? $textLesson->study_time : ''); ?>" placeholder="<?php echo e(trans('forms.maximum_255_characters')); ?>"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('public.image')); ?></label>

                <div class="custom-file bg-white">
                    <input type="file" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][image]" class="js-ajax-upload-file-input js-ajax-file_upload custom-file-input" data-upload-name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][image]" id="text_lesson_image_<?php echo e(!empty($textLesson) ? $textLesson->id :'record'); ?>">
                    <span class="custom-file-text"><?php echo e((!empty($textLesson) and !empty($textLesson->image)) ? getFileNameByPath($textLesson->image) : ''); ?></span>
                    <label class="custom-file-label" for="text_lesson_image_<?php echo e(!empty($textLesson) ? $textLesson->id :'record'); ?>"><?php echo e(trans('update.browse')); ?></label>
                </div>

                <div class="invalid-feedback d-block"></div>

                <?php if(!empty($textLesson) and !empty($textLesson->image)): ?>
                    <a href="<?php echo e($textLesson->image); ?>" target="_blank" class="font-12 text-primary mt-8"><?php echo e(trans('update.preview')); ?></a>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="font-14 text-gray-500 bg-white"><?php echo e(trans('public.accessibility')); ?></label>

                <div class="d-flex align-items-center js-ajax-accessibility mt-12">

                    <div class="custom-control custom-radio mr-12">
                        <input type="radio" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][accessibility]" id="accessibilityRadio1_<?php echo e(!empty($textLesson) ? $textLesson->id : 'record'); ?>" value="free" class="custom-control-input" <?php if(empty($textLesson) or (!empty($textLesson) and $textLesson->accessibility == 'free')): ?> checked="checked" <?php endif; ?>>
                        <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio1_<?php echo e(!empty($textLesson) ? $textLesson->id : 'record'); ?>"><?php echo e(trans('public.free')); ?></label>
                    </div>

                    <div class="custom-control custom-radio mr-12">
                        <input type="radio" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][accessibility]" id="accessibilityRadio2_<?php echo e(!empty($textLesson) ? $textLesson->id : 'record'); ?>" value="paid" class="custom-control-input" <?php if(empty($textLesson) or (!empty($textLesson) and $textLesson->accessibility == 'paid')): ?> checked="checked" <?php endif; ?>>
                        <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio2_<?php echo e(!empty($textLesson) ? $textLesson->id : 'record'); ?>"><?php echo e(trans('public.paid')); ?></label>
                    </div>
                </div>

                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group ">
                <label class="form-group-label d-block"><?php echo e(trans('public.attachments')); ?></label>

                <?php
                    $textLessonAttachmentsFileIds = [];

                    if (!empty($textLesson)) {
                        $textLessonAttachmentsFileIds = $textLesson->attachments->pluck('file_id')->toArray();
                    }
                ?>

                <select class="js-ajax-attachments form-control <?php echo e(!empty($textLesson) ? 'select2' : 'attachments-select2'); ?>" multiple="multiple" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][attachments][]" data-placeholder="<?php echo e(trans('public.choose_attachments')); ?>">
                    <option></option>

                    <?php if(!empty($webinar->files) and count($webinar->files)): ?>
                        <?php $__currentLoopData = $webinar->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filesInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($filesInfo->id); ?>" <?php if(!empty($textLesson) and in_array($filesInfo->id, $textLessonAttachmentsFileIds)): ?> selected <?php endif; ?>><?php echo e($filesInfo->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('public.summary')); ?></label>
                <textarea name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][summary]" class="js-ajax-summary form-control" rows="6"><?php echo e(!empty($textLesson) ? $textLesson->summary : ''); ?></textarea>
                <div class="invalid-feedback"></div>
            </div>


            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('public.content')); ?></label>
                <div class="content-summernote js-ajax-file_path">
                    <textarea class="js-content-summernote-input form-control <?php echo e(!empty($textLesson) ? 'js-content-summernote' : ''); ?>"><?php echo e(!empty($textLesson) ? $textLesson->content : ''); ?></textarea>
                    <textarea name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][content]" class="js-hidden-content-summernote <?php echo e(!empty($textLesson) ? 'js-hidden-content-'.$textLesson->id : ''); ?> d-none"><?php echo e(!empty($textLesson) ? $textLesson->content : ''); ?></textarea>
                </div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="textLessonStatusSwitch<?php echo e(!empty($textLesson) ? $textLesson->id : '_record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][status]" class="custom-control-input" <?php echo e((empty($textLesson) or $textLesson->status == \App\Models\File::$Active) ? 'checked' : ''); ?>>
                    <label class="custom-control-label cursor-pointer" for="textLessonStatusSwitch<?php echo e(!empty($textLesson) ? $textLesson->id : '_record'); ?>"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="textLessonStatusSwitch<?php echo e(!empty($textLesson) ? $textLesson->id : '_record'); ?>"><?php echo e(trans('public.active')); ?></label>
                </div>
            </div>


            <?php if(getFeaturesSettings('sequence_content_status')): ?>
                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="textLessonSequenceContentSwitch<?php echo e(!empty($textLesson) ? $textLesson->id : '_record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][sequence_content]" class="js-sequence-content-switch custom-control-input" <?php echo e((!empty($textLesson) and ($textLesson->check_previous_parts or !empty($textLesson->access_after_day))) ? 'checked' : ''); ?>>
                        <label class="custom-control-label cursor-pointer" for="textLessonSequenceContentSwitch<?php echo e(!empty($textLesson) ? $textLesson->id : '_record'); ?>"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="textLessonSequenceContentSwitch<?php echo e(!empty($textLesson) ? $textLesson->id : '_record'); ?>"><?php echo e(trans('update.sequence_content')); ?></label>
                    </div>
                </div>

                <div class="js-sequence-content-inputs pl-4 <?php echo e((!empty($textLesson) and ($textLesson->check_previous_parts or !empty($textLesson->access_after_day))) ? '' : 'd-none'); ?>">
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="checkPreviousPartsSwitch<?php echo e(!empty($textLesson) ? $textLesson->id : '_record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][check_previous_parts]" class="custom-control-input" <?php echo e((empty($textLesson) or $textLesson->check_previous_parts) ? 'checked' : ''); ?>>
                            <label class="custom-control-label cursor-pointer" for="checkPreviousPartsSwitch<?php echo e(!empty($textLesson) ? $textLesson->id : '_record'); ?>"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="checkPreviousPartsSwitch<?php echo e(!empty($textLesson) ? $textLesson->id : '_record'); ?>"><?php echo e(trans('update.check_previous_parts')); ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('update.access_after_day')); ?></label>
                        <input type="number" name="ajax[<?php echo e(!empty($textLesson) ? $textLesson->id : 'new'); ?>][access_after_day]" value="<?php echo e((!empty($textLesson)) ? $textLesson->access_after_day : ''); ?>" class="js-ajax-access_after_day form-control" placeholder="<?php echo e(trans('update.access_after_day_placeholder')); ?>"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            <?php endif; ?>


            <div class="mt-20 d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-course-content btn btn-lg btn-primary"><?php echo e(trans('public.save')); ?></button>

                <?php if(empty($textLesson)): ?>
                    <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion"><?php echo e(trans('public.close')); ?></button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</li>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/includes/accordions/text_lesson.blade.php ENDPATH**/ ?>