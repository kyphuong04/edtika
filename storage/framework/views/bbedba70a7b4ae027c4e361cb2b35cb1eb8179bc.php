<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
<?php $__env->stopPush(); ?>

<div class="bg-white rounded-16 p-16 mt-32">

    
    <div class="form-group mb-0">
        <h3 class="font-14 font-weight-bold position-relative d-inline-flex is-required"><?php echo e(trans('panel.course_type')); ?></h3>
    </div>

    <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-24 mt-16">
        <?php
            $coursetypes = [
                'webinar' => 'video',
                'course' => 'video-circle',
                'text_lesson' => 'book',
            ];
        ?>
        <?php $__currentLoopData = $coursetypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coursetype => $coursetypeIcon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="create-webinar-course-types custom-input-button position-relative">
                <input type="radio" class="" name="type" id="course_type_<?php echo e($coursetype); ?>" value="<?php echo e($coursetype); ?>" <?php echo e((!empty($webinar) and $webinar->type == $coursetype) ? 'checked' : ''); ?>>
                <label for="course_type_<?php echo e($coursetype); ?>" class="position-relative d-flex-center flex-column p-16 p-lg-32 rounded-16 border-gray-200 text-center bg-white">
                    <div class="create-webinar-course-types__icon-box d-flex-center size-64 rounded-16">
                        <?php echo e(svg("iconsax-bul-{$coursetypeIcon}", ['height' => 32, 'width' => 32, 'class' => ''])); ?>
                    </div>

                    <div class="mt-12 font-14 font-weight-bold"><?php echo e(trans("webinars.{$coursetype}")); ?></div>
                    <p class="mt-4 font-12 text-gray-500"><?php echo e(trans("update.create_{$coursetype}_hint")); ?></p>
                </label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <h3 class="font-14 font-weight-bold mt-24 mb-16"><?php echo e(trans('public.basic_information')); ?></h3>


    <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
        'itemRow' => !empty($webinar) ? $webinar : null,
        'withoutReloadLocale' => false,
        'extraClass' => ''
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if($isOrganization): ?>
        <div class="form-group ">
            <label class="form-group-label is-required bg-white"><?php echo e(trans('public.select_a_teacher')); ?></label>

            <select name="teacher_id" class="select2 <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="" <?php echo e((!empty($webinar) and !empty($webinar->teacher_id)) ? '' : 'selected'); ?>><?php echo e(trans('public.choose_instructor')); ?></option>

                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($teacher->id); ?>" <?php echo e((!empty($webinar) && $webinar->teacher_id == $teacher->id) ? 'selected' : ''); ?>><?php echo e($teacher->full_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block">
                <?php echo e($message); ?>

            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    <?php endif; ?>


    <div class="form-group">
        <label class="form-group-label is-required bg-white"><?php echo e(trans('public.title')); ?></label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-translate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>
        <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e((!empty($webinar) and !empty($webinar->translate($locale))) ? $webinar->translate($locale)->title : old('title')); ?>" placeholder=""/>
        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
            <?php echo e($message); ?>

        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="form-group mt-15">
        <label class="form-group-label"><?php echo e(trans('public.seo_description')); ?></label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-translate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>
        <input type="text" name="seo_description" class="form-control <?php $__errorArgs = ['seo_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> " value="<?php echo e((!empty($webinar) and !empty($webinar->translate($locale))) ? $webinar->translate($locale)->seo_description : old('seo_description')); ?>" placeholder="<?php echo e(trans('forms.50_160_characters_preferred')); ?>"/>
        <?php $__errorArgs = ['seo_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
            <?php echo e($message); ?>

        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="form-group mb-0 mt-24">
        <h3 class="font-14 font-weight-bold position-relative d-inline-flex is-required"><?php echo e(trans('update.thumbnail_&_cover')); ?></h3>
    </div>

    <div class="row">

        <?php echo $__env->make('design_1.panel.webinars.create.includes.media',[
            'media' => !empty($webinar) ? $webinar->thumbnail : null,
            'mediaName' => 'thumbnail',
            'mediaTitle' => trans('update.thumbnail'),
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('design_1.panel.webinars.create.includes.media',[
            'media' => !empty($webinar) ? $webinar->image_cover : null,
            'mediaName' => 'image_cover',
            'mediaTitle' => trans('public.cover_image'),
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


        <div class="col-12 mt-8">
            <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback">
                <?php echo e($message); ?>

            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php $__errorArgs = ['image_cover'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback">
                <?php echo e($message); ?>

            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    
    <h3 class="font-14 font-weight-bold mt-24 mb-16"><?php echo e(trans('update.course_icon')); ?> (<?php echo e(trans('public.optional')); ?>)</h3>

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('update.icon')); ?></label>

                <div class="custom-file bg-white">
                    <input type="file" name="icon" class="js-ajax-upload-file-input js-ajax-icon custom-file-input" data-upload-name="icon" id="iconInput" accept="image/*">
                    <span class="custom-file-text"><?php echo e((!empty($webinar) and !empty($webinar->icon)) ? getFileNameByPath($webinar->icon) : ''); ?></span>
                    <label class="custom-file-label" for="iconInput"><?php echo e(trans('update.browse')); ?></label>
                </div>

                <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback">
                    <?php echo e($message); ?>

                </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <?php if(!empty($webinar) and !empty($webinar->icon)): ?>
                    <a href="<?php echo e(url($webinar->icon)); ?>" target="_blank" class="text-danger mt-4 font-12"><?php echo e(trans('update.preview')); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>


    
    <h3 class="font-14 font-weight-bold mt-24 mb-16"><?php echo e(trans('public.demo_video')); ?> (<?php echo e(trans('public.optional')); ?>)</h3>

    <div class="js-inputs-with-source row">

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('update.video_source')); ?></label>
                <select name="video_demo_source" class="js-upload-source-input form-control <?php $__errorArgs = ['video_demo_source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> select2" data-minimum-results-for-search="Infinity">
                    <?php $__currentLoopData = \App\Enums\UploadSource::allSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($source); ?>" <?php echo e((!empty($webinar) and $webinar->video_demo_source == $source) ? 'selected' : ''); ?>><?php echo e(trans($source)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <?php $__errorArgs = ['video_demo_source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group js-online-upload <?php echo e((empty($webinar) or !in_array($webinar->video_demo_source, \App\Enums\UploadSource::uploadItems)) ? '' : 'd-none'); ?>">
                <span class="has-translation bg-transparent">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-link-21'); ?>
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
                </span>
                <label class="form-group-label"><?php echo e(trans('update.path')); ?></label>
                <input type="text" name="demo_video_path" class="form-control" value="<?php echo e(!empty($webinar) ? $webinar->video_demo : old('demo_video_path')); ?>" placeholder="<?php echo e(trans('update.insert_demo_video_link')); ?>">
            </div>

            <div class="form-group js-local-upload <?php echo e((!empty($webinar) and in_array($webinar->video_demo_source, \App\Enums\UploadSource::uploadItems)) ? '' : 'd-none'); ?>">
                <span class="has-translation bg-transparent">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-export'); ?>
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
                </span>

                <label class="form-group-label"><?php echo e(trans('update.upload_video')); ?></label>
                <div class="custom-file bg-white">
                    <input type="file" name="demo_video_local" class="custom-file-input" id="demo_video_local" accept="video/*">
                    <span class="custom-file-text text-dark"><?php echo e(trans('update.select_a_video')); ?></span>
                    <label class="custom-file-label bg-gray-100" for="demo_video_local"><?php echo e(trans('update.browse')); ?></label>
                </div>
            </div>
        </div>

    </div>

    
    <h3 class="font-14 font-weight-bold mt-24 mb-16"><?php echo e(trans('update.course_summary')); ?></h3>

    <div class="form-group bg-white-editor">
        <label class="form-group-label is-required"><?php echo e(trans('public.summary')); ?></label>
        <textarea name="summary" rows="5" class="form-control <?php $__errorArgs = ['summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('update.course_summary_placeholder')); ?>"><?php echo (!empty($webinar) and !empty($webinar->translate($locale))) ? $webinar->translate($locale)->summary : old('summary'); ?></textarea>
        <?php $__errorArgs = ['summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
            <?php echo e($message); ?>

        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <h3 class="font-14 font-weight-bold mt-24 mb-16"><?php echo e(trans('update.course_description')); ?></h3>

    <div class="form-group bg-white-editor">
        <label class="form-group-label is-required"><?php echo e(trans('public.description')); ?></label>
        <textarea name="description" class="main-summernote form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-height="400" placeholder="<?php echo e(trans('forms.webinar_description_placeholder')); ?>"><?php echo (!empty($webinar) and !empty($webinar->translate($locale))) ? $webinar->translate($locale)->description : old('description'); ?></textarea>
        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
            <?php echo e($message); ?>

        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <?php if($isOrganization): ?>
        <div class="row mt-20">
            <div class="col-12 col-lg-6">
                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="privateSwitch" type="checkbox" name="private" class="custom-control-input" <?php echo e((!empty($webinar) and $webinar->private) ? 'checked' :  ''); ?>>
                        <label class="custom-control-label cursor-pointer" for="privateSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="privateSwitch"><?php echo e(trans('webinars.private')); ?></label>
                    </div>
                </div>
                <p class="text-gray-500 font-12"><?php echo e(trans('webinars.create_private_course_hint')); ?></p>
            </div>
        </div>
    <?php endif; ?>

</div>


<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/steps/step_1.blade.php ENDPATH**/ ?>