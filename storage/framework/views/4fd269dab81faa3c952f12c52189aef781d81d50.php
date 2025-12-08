<?php if(!empty($file) and $file->storage == 'upload_archive'): ?>
    <?php echo $__env->make('design_1.panel.webinars.create.includes.accordions.interactive_file',['file' => $file], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php else: ?>
    <li data-id="<?php echo e(!empty($chapterItem) ? $chapterItem->id :''); ?>" class="accordion bg-white border-gray-200 p-12 rounded-16 mt-16">
        <div class="accordion__title d-flex align-items-center justify-content-between " role="tab" id="file_<?php echo e(!empty($file) ? $file->id :'record'); ?>">
            <div class="d-flex align-items-center cursor-pointer" href="#collapseFile<?php echo e(!empty($file) ? $file->id :'record'); ?>" aria-controls="collapseFile<?php echo e(!empty($file) ? $file->id :'record'); ?>" data-parent="#chapterContentAccordion<?php echo e(!empty($chapter) ? $chapter->id :''); ?>" role="button" data-toggle="collapse" aria-expanded="true">
                <div class="d-flex mr-8">
                    <?php
                        $fileIcon = !empty($file) ? $file->getIconXByType() : 'document';
                    ?>

                    <?php echo e(svg("iconsax-lin-{$fileIcon}", ['height' => 20, 'width' => 20, 'class' => 'text-gray-500'])); ?>
                </div>

                <div class="font-14 font-weight-bold d-block"><?php echo e(!empty($file) ? $file->title : trans('public.add_new_files')); ?></div>
            </div>

            <div class="d-flex align-items-center">

                <?php if(!empty($file)): ?>

                    <?php if($file->accessibility == 'free'): ?>
                        <span class="px-8 py-4 bg-primary-20 text-primary font-12 mr-12 rounded-8"><?php echo e(trans('public.free')); ?></span>
                    <?php endif; ?>

                    <?php if($file->status != \App\Models\WebinarChapter::$chapterActive): ?>
                        <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8"><?php echo e(trans('public.disabled')); ?></span>
                    <?php endif; ?>

                    <div class="js-change-content-chapter cursor-pointer mr-12" data-item-id="<?php echo e($file->id); ?>" data-item-type="<?php echo e(\App\Models\WebinarChapterItem::$chapterFile); ?>" data-chapter-id="<?php echo e(!empty($chapter) ? $chapter->id : ''); ?>" data-tippy-content="<?php echo e(trans('public.edit_chapter')); ?>">
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


                    <a href="/panel/files/<?php echo e($file->id); ?>/delete" class="delete-action d-flex text-gray-500 mr-12">
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

                <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseFile<?php echo e(!empty($file) ? $file->id :'record'); ?>" aria-controls="collapseFile<?php echo e(!empty($file) ? $file->id :'record'); ?>" data-parent="#chapterContentAccordion<?php echo e(!empty($chapter) ? $chapter->id :''); ?>" role="button" data-toggle="collapse" aria-expanded="true">
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

        <div id="collapseFile<?php echo e(!empty($file) ? $file->id :'record'); ?>" class=" collapse <?php if(empty($file)): ?> show <?php endif; ?>" role="tabpanel">
            <div class="js-content-form file-form" data-action="/panel/files/<?php echo e(!empty($file) ? $file->id . '/update' : 'store'); ?>">
                <input type="hidden" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][webinar_id]" value="<?php echo e(!empty($webinar) ? $webinar->id :''); ?>">

                <div class="mt-20">
                    <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($file) ? $file : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-webinar-content-locale',
                        'extraData' => "data-webinar-id='".(!empty($webinar) ? $webinar->id : '')."'  data-id='".(!empty($file) ? $file->id : '')."'  data-relation='files' data-fields='title,description'"
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <?php if(!empty($file)): ?>
                    <div class="form-group ">
                        <label class="form-group-label"><?php echo e(trans('public.chapter')); ?></label>
                        <select name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][chapter_id]" class="js-ajax-chapter_id form-control select2">
                            <?php $__currentLoopData = $webinar->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ch->id); ?>" <?php echo e(($file->chapter_id == $ch->id) ? 'selected' : ''); ?>><?php echo e($ch->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-group-label"><?php echo e(trans('public.title')); ?></label>
                    <input type="text" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][title]" class="js-ajax-title form-control" value="<?php echo e(!empty($file) ? $file->title : ''); ?>" placeholder="<?php echo e(trans('forms.maximum_255_characters')); ?>"/>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label class="form-group-label"><?php echo e(trans('public.source')); ?></label>
                    <select name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][storage]"
                            class="js-file-storage form-control"
                    >
                        <?php $__currentLoopData = getFeaturesSettings('available_sources'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($source); ?>" <?php if(!empty($file) and $file->storage == $source): ?> selected <?php endif; ?>><?php echo e(trans('update.file_source_'.$source)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-14 text-gray-500 bg-white"><?php echo e(trans('public.accessibility')); ?></label>

                    <div class="d-flex align-items-center js-ajax-accessibility mt-12">

                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][accessibility]" id="accessibilityRadio1_<?php echo e(!empty($file) ? $file->id : 'record'); ?>" value="free" class="custom-control-input" <?php if(empty($file) or (!empty($file) and $file->accessibility == 'free')): ?> checked="checked" <?php endif; ?>>
                            <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio1_<?php echo e(!empty($file) ? $file->id : 'record'); ?>"><?php echo e(trans('public.free')); ?></label>
                        </div>

                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][accessibility]" id="accessibilityRadio2_<?php echo e(!empty($file) ? $file->id : 'record'); ?>" value="paid" class="custom-control-input" <?php if(empty($file) or (!empty($file) and $file->accessibility == 'paid')): ?> checked="checked" <?php endif; ?>>
                            <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio2_<?php echo e(!empty($file) ? $file->id : 'record'); ?>"><?php echo e(trans('public.paid')); ?></label>
                        </div>
                    </div>

                    <div class="invalid-feedback"></div>
                </div>

                <div class="js-secure-host-upload-type-field form-group <?php echo e((!empty($file) and $file->storage == "secure_host") ? '' : 'd-none'); ?>">
                    <label class="font-14 text-gray-500 bg-white"><?php echo e(trans('update.upload_type')); ?></label>

                    <div class="d-flex align-items-center js-ajax-secure_host_upload_type mt-12">

                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][secure_host_upload_type]" id="uploadTypeRadio1_<?php echo e(!empty($file) ? $file->id : 'record'); ?>" value="direct" class="custom-control-input" <?php echo e((empty($file) or $file->secure_host_upload_type == 'direct') ? 'checked' : ''); ?>>
                            <label class="custom-control__label cursor-pointer pl-0" for="uploadTypeRadio1_<?php echo e(!empty($file) ? $file->id : 'record'); ?>"><?php echo e(trans('update.direct')); ?></label>
                        </div>

                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][secure_host_upload_type]" id="uploadTypeRadio2_<?php echo e(!empty($file) ? $file->id : 'record'); ?>" value="manual" class="custom-control-input" <?php echo e((!empty($file) and $file->secure_host_upload_type == 'manual') ? 'checked' : ''); ?>>
                            <label class="custom-control__label cursor-pointer pl-0" for="uploadTypeRadio2_<?php echo e(!empty($file) ? $file->id : 'record'); ?>"><?php echo e(trans('public.manual')); ?></label>
                        </div>
                    </div>

                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group js-file-url-input <?php echo e((!empty($file) and (in_array($file->storage, \App\Models\File::$urlInputSources) or ($file->storage == 'secure_host' and $file->secure_host_upload_type == 'manual'))) ? '' : 'd-none'); ?>">
                    <label class="form-group-label"><?php echo e(trans('public.link')); ?></label>
                    <span class="has-translation bg-transparent"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>
                    <input type="text" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][file_url]" value="<?php echo e((!empty($file)) ? $file->file : ''); ?>" class="js-ajax-file_url form-control" placeholder="<?php echo e(trans('update.enter_file_url')); ?>"/>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group js-file-upload-input <?php echo e((!empty($file) and (in_array($file->storage, ['upload', 's3']) or ($file->storage == 'secure_host' and $file->secure_host_upload_type == 'direct'))) ? '' : 'd-none'); ?>">
                    <label class="form-group-label"><?php echo e(trans('update.choose_file')); ?></label>

                    <div class="custom-file bg-white">
                        <input type="file" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][file_upload]" class="js-ajax-upload-file-input js-ajax-file_upload custom-file-input" data-upload-name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][file_upload]" id="file_upload_input_<?php echo e(!empty($file) ? $file->id : 'record'); ?>" >
                        <span class="custom-file-text"><?php echo e((!empty($file) and !empty($file->file)) ? getFileNameByPath($file->file) : ''); ?></span>
                        <label class="custom-file-label" for="file_upload_input_<?php echo e(!empty($file) ? $file->id : 'record'); ?>"><?php echo e(trans('update.browse')); ?></label>
                    </div>

                    <div class="invalid-feedback d-block"></div>

                    
                </div>

                <div class="row js-file-type-volume d-none">
                    <div class="col-6 js-file-type-field">
                        <div class="form-group">
                            <label class="form-group-label"><?php echo e(trans('webinars.file_type')); ?></label>

                            <select name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][file_type]" class="js-ajax-file_type form-control">
                                <option value=""><?php echo e(trans('webinars.select_file_type')); ?></option>

                                <?php $__currentLoopData = \App\Models\File::$fileTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fileType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($fileType); ?>" <?php if(!empty($file) and $file->file_type == $fileType): ?> selected <?php endif; ?>><?php echo e(trans('update.file_type_'.$fileType)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="col-6 js-file-volume-field">
                        <div class="form-group">
                            <label class="form-group-label"><?php echo e(trans('webinars.file_volume')); ?></label>
                            <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4"><?php echo e(trans('update.mb')); ?></span>
                            <input type="number" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][volume]" value="<?php echo e((!empty($file)) ? $file->volume : ''); ?>" class="js-ajax-volume form-control" placeholder="<?php echo e(trans('webinars.online_file_volume')); ?>"/>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-group-label"><?php echo e(trans('public.description')); ?></label>
                    <textarea name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][description]" class="js-ajax-description form-control" rows="6"><?php echo e(!empty($file) ? $file->description : ''); ?></textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="js-online_viewer-input">
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="online_viewerSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][online_viewer]" class="custom-control-input" <?php echo e((!empty($file) and $file->online_viewer) ? 'checked' : ''); ?>>
                            <label class="custom-control-label cursor-pointer" for="online_viewerSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="online_viewerSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"><?php echo e(trans('update.online_viewer')); ?></label>
                        </div>
                    </div>
                </div>

                <div class="js-downloadable-input">
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="downloadableSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][downloadable]" class="custom-control-input" <?php echo e((empty($file) or $file->downloadable) ? 'checked' : ''); ?>>
                            <label class="custom-control-label cursor-pointer" for="downloadableSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="downloadableSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"><?php echo e(trans('home.downloadable')); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="fileStatusSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][status]" class="custom-control-input" <?php echo e((empty($file) or $file->status == \App\Models\File::$Active) ? 'checked' : ''); ?>>
                        <label class="custom-control-label cursor-pointer" for="fileStatusSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="fileStatusSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"><?php echo e(trans('public.active')); ?></label>
                    </div>
                </div>


                <?php if(getFeaturesSettings('sequence_content_status')): ?>
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="fileSequenceContentSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][sequence_content]" class="js-sequence-content-switch custom-control-input" <?php echo e((!empty($file) and ($file->check_previous_parts or !empty($file->access_after_day))) ? 'checked' : ''); ?>>
                            <label class="custom-control-label cursor-pointer" for="fileSequenceContentSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="fileSequenceContentSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"><?php echo e(trans('update.sequence_content')); ?></label>
                        </div>
                    </div>

                    <div class="js-sequence-content-inputs pl-4 <?php echo e((!empty($file) and ($file->check_previous_parts or !empty($file->access_after_day))) ? '' : 'd-none'); ?>">
                        <div class="form-group d-flex align-items-center">
                            <div class="custom-switch mr-8">
                                <input id="checkPreviousPartsSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][check_previous_parts]" class="custom-control-input" <?php echo e((empty($file) or $file->check_previous_parts) ? 'checked' : ''); ?>>
                                <label class="custom-control-label cursor-pointer" for="checkPreviousPartsSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"></label>
                            </div>

                            <div class="">
                                <label class="cursor-pointer" for="checkPreviousPartsSwitch<?php echo e(!empty($file) ? $file->id : '_record'); ?>"><?php echo e(trans('update.check_previous_parts')); ?></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-group-label"><?php echo e(trans('update.access_after_day')); ?></label>
                            <input type="number" name="ajax[<?php echo e(!empty($file) ? $file->id : 'new'); ?>][access_after_day]" value="<?php echo e((!empty($file)) ? $file->access_after_day : ''); ?>" class="js-ajax-access_after_day form-control" placeholder="<?php echo e(trans('update.access_after_day_placeholder')); ?>"/>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="progress d-none">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>
                </div>

                <div class="mt-20 d-flex align-items-center justify-content-end">
                    <button type="button" class="js-save-course-content btn btn-lg btn-primary"><?php echo e(trans('public.save')); ?></button>

                    <?php if(empty($file)): ?>
                        <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion"><?php echo e(trans('public.close')); ?></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </li>
<?php endif; ?>


<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var filePathPlaceHolderBySource = {
            upload: '<?php echo e(trans('update.file_source_upload_placeholder')); ?>',
            youtube: '<?php echo e(trans('update.file_source_youtube_placeholder')); ?>',
            vimeo: '<?php echo e(trans('update.file_source_vimeo_placeholder')); ?>',
            external_link: '<?php echo e(trans('update.file_source_external_link_placeholder')); ?>',
            google_drive: '<?php echo e(trans('update.file_source_google_drive_placeholder')); ?>',
            dropbox: '<?php echo e(trans('update.file_source_dropbox_placeholder')); ?>',
            iframe: '<?php echo e(trans('update.file_source_iframe_placeholder')); ?>',
            s3: '<?php echo e(trans('update.file_source_s3_placeholder')); ?>',
        }
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/includes/accordions/file.blade.php ENDPATH**/ ?>