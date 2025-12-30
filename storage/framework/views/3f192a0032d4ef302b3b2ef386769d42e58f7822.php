<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
<?php $__env->stopPush(); ?>

<div class="bg-white rounded-16 p-16 mt-32">

    <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('public.basic_information')); ?></h3>


    <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
        'itemRow' => !empty($bundle) ? $bundle : null,
        'withoutReloadLocale' => false,
        'extraClass' => ''
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if($isOrganization): ?>
        <div class="form-group ">
            <label class="form-group-label"><?php echo e(trans('public.select_a_teacher')); ?></label>

            <select name="teacher_id" class="select2 <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="" <?php echo e((!empty($bundle) and !empty($bundle->teacher_id)) ? '' : 'selected'); ?>><?php echo e(trans('public.choose_instructor')); ?></option>

                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($teacher->id); ?>" <?php echo e((!empty($bundle) && $bundle->teacher_id == $teacher->id) ? 'selected' : ''); ?>><?php echo e($teacher->full_name); ?></option>
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
        <label class="form-group-label is-required"><?php echo e(trans('public.title')); ?></label>
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
unset($__errorArgs, $__bag); ?>" value="<?php echo e((!empty($bundle) and !empty($bundle->translate($locale))) ? $bundle->translate($locale)->title : old('title')); ?>" placeholder=""/>
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
unset($__errorArgs, $__bag); ?> " value="<?php echo e((!empty($bundle) and !empty($bundle->translate($locale))) ? $bundle->translate($locale)->seo_description : old('seo_description')); ?>" placeholder="<?php echo e(trans('forms.50_160_characters_preferred')); ?>"/>
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


    <div class="form-group mb-0">
        <h3 class="font-14 font-weight-bold position-relative d-inline-flex is-required"><?php echo e(trans('update.thumbnail_&_cover')); ?></h3>
    </div>

    <div class="row">

        <?php echo $__env->make('design_1.panel.bundles.create.includes.media',[
            'media' => !empty($bundle) ? $bundle->thumbnail : null,
            'mediaName' => 'thumbnail',
            'mediaTitle' => trans('update.thumbnail'),
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('design_1.panel.bundles.create.includes.media',[
            'media' => !empty($bundle) ? $bundle->image_cover : null,
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

    
    <h3 class="font-14 font-weight-bold my-24"><?php echo e(trans('public.demo_video')); ?> (<?php echo e(trans('public.optional')); ?>)</h3>

    <div class="js-inputs-with-source row">

        <div class="col-12 col-md-6">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('update.video_source')); ?></label>
                <select name="video_demo_source" class="js-upload-source-input form-control select2 <?php $__errorArgs = ['video_demo_source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-minimum-results-for-search="Infinity">
                    <?php $__currentLoopData = \App\Enums\UploadSource::allSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($source); ?>" <?php echo e((!empty($bundle) and $bundle->video_demo_source == $source) ? 'selected' : ''); ?>><?php echo e(trans($source)); ?></option>
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

        <div class="col-12 col-md-6">
            <div class="form-group js-online-upload <?php echo e((empty($bundle) or !in_array($bundle->video_demo_source, \App\Enums\UploadSource::uploadItems)) ? '' : 'd-none'); ?>">
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
                <input type="text" name="demo_video_path" class="form-control" value="<?php echo e(!empty($bundle) ? $bundle->video_demo : old('demo_video_path')); ?>" placeholder="<?php echo e(trans('update.insert_demo_video_link')); ?>">
            </div>

            <div class="form-group js-local-upload <?php echo e((!empty($bundle) and in_array($bundle->video_demo_source, \App\Enums\UploadSource::uploadItems)) ? '' : 'd-none'); ?>">
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

    
    <h3 class="font-14 font-weight-bold my-24"><?php echo e(trans('update.bundle_summary')); ?></h3>

    <div class="form-group bg-white-editor">
        <label class="form-group-label"><?php echo e(trans('public.summary')); ?></label>
        <textarea name="summary" rows="5" class="form-control <?php $__errorArgs = ['summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('update.bundle_summary_placeholder')); ?>"><?php echo (!empty($bundle) and !empty($bundle->translate($locale))) ? $bundle->translate($locale)->summary : old('summary'); ?></textarea>
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


    
    <h3 class="font-14 font-weight-bold my-24"><?php echo e(trans('update.bundle_description')); ?></h3>

    <div class="form-group bg-white-editor">
        <label class="form-group-label is-required"><?php echo e(trans('public.description')); ?></label>
        <textarea name="description" class="main-summernote form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-height="400" placeholder="<?php echo e(trans('forms.webinar_description_placeholder')); ?>"><?php echo (!empty($bundle) and !empty($bundle->translate($locale))) ? $bundle->translate($locale)->description : old('description'); ?></textarea>
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

</div>


<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/bundles/create/steps/step_1.blade.php ENDPATH**/ ?>