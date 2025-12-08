<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-timepicker/bootstrap-timepicker.min.css">

    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <style>
        .bootstrap-timepicker-widget table td input {
            width: 35px !important;
        }

        .select2-container {
            z-index: 1212 !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(!empty($bundle) ?trans('/admin/main.edit'): trans('admin/main.new')); ?> <?php echo e(trans('update.bundle')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>/bundles"><?php echo e(trans('update.bundles')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(!empty($bundle) ?trans('/admin/main.edit'): trans('admin/main.new')); ?></div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">

                            <form method="post" action="<?php echo e(getAdminPanelUrl()); ?>/bundles/<?php echo e(!empty($bundle) ? $bundle->id.'/update' : 'store'); ?>" id="webinarForm" class="webinar-form" enctype="multipart/form-data">
                                <?php echo e(csrf_field()); ?>

                                <section>
                                    <h2 class="section-title after-line"><?php echo e(trans('public.basic_information')); ?></h2>

                                    <div class="row">
                                        <div class="col-12 col-md-5">

                                            <?php if(!empty(getGeneralSettings('content_translate'))): ?>
                                                <div class="form-group">
                                                    <label class="input-label"><?php echo e(trans('auth.language')); ?></label>
                                                    <select name="locale" class="form-control <?php echo e(!empty($bundle) ? 'js-edit-content-locale' : ''); ?>">
                                                        <?php $__currentLoopData = $userLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($lang); ?>" <?php if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)): ?> selected <?php endif; ?>><?php echo e($language); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <?php $__errorArgs = ['locale'];
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
                                            <?php else: ?>
                                                <input type="hidden" name="locale" value="<?php echo e(getDefaultLocale()); ?>">
                                            <?php endif; ?>


                                            <div class="form-group mt-15">
                                                <label class="input-label"><?php echo e(trans('public.title')); ?></label>
                                                <input type="text" name="title" value="<?php echo e(!empty($bundle) ? $bundle->title : old('title')); ?>" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder=""/>
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
                                                <label class="input-label"><?php echo e(trans('update.required_points')); ?></label>
                                                <input type="text" name="points" value="<?php echo e(!empty($bundle) ? $bundle->points : old('points')); ?>" class="form-control <?php $__errorArgs = ['points'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Empty means inactive this mode"/>
                                                <div class="text-gray-500 text-small mt-1"><?php echo e(trans('update.product_points_hint')); ?></div>
                                                <?php $__errorArgs = ['points'];
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
                                                <label class="input-label"><?php echo e(trans('update.bundle_url')); ?></label>
                                                <input type="text" name="slug" value="<?php echo e(!empty($bundle) ? $bundle->slug : old('slug')); ?>" class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder=""/>
                                                <div class="text-gray-500 text-small mt-1"><?php echo e(trans('update.bundle_url_hint')); ?></div>
                                                <?php $__errorArgs = ['slug'];
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

                                            <?php if(!empty($bundle) and $bundle->creator->isOrganization()): ?>
                                                <div class="form-group mt-15 ">
                                                    <label class="input-label d-block"><?php echo e(trans('admin/main.organization')); ?></label>

                                                    <select class="form-control" disabled readonly data-placeholder="<?php echo e(trans('public.search_instructor')); ?>">
                                                        <option selected><?php echo e($bundle->creator->full_name); ?></option>
                                                    </select>
                                                </div>
                                            <?php endif; ?>


                                            <div class="form-group mt-15 ">
                                                <label class="input-label d-block"><?php echo e(trans('admin/main.select_a_instructor')); ?></label>


                                                <select name="teacher_id" data-search-option="except_user" class="form-control search-user-select2"
                                                        data-placeholder="<?php echo e(trans('public.select_a_teacher')); ?>"
                                                >
                                                    <?php if(!empty($bundle)): ?>
                                                        <option value="<?php echo e($bundle->teacher->id); ?>" selected><?php echo e($bundle->teacher->full_name); ?></option>
                                                    <?php else: ?>
                                                        <option selected disabled><?php echo e(trans('public.select_a_teacher')); ?></option>
                                                    <?php endif; ?>
                                                </select>

                                                <?php $__errorArgs = ['teacher_id'];
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
                                                <label class="input-label"><?php echo e(trans('public.seo_description')); ?></label>
                                                <input type="text" name="seo_description" value="<?php echo e(!empty($bundle) ? $bundle->seo_description : old('seo_description')); ?>" class="form-control <?php $__errorArgs = ['seo_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                                                <div class="text-gray-500 text-small mt-1"><?php echo e(trans('admin/main.seo_description_hint')); ?></div>
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

                                            <div class="form-group mt-15">
                                                <label class="input-label"><?php echo e(trans('public.thumbnail_image')); ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text admin-file-manager" data-input="thumbnail" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="thumbnail" id="thumbnail" value="<?php echo e(!empty($bundle) ? $bundle->thumbnail : old('thumbnail')); ?>" class="form-control <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                                                    <div class="input-group-append">
                                                        <button type="button" class="input-group-text admin-file-view" data-input="thumbnail">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
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
                                                </div>
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label"><?php echo e(trans('public.cover_image')); ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text admin-file-manager" data-input="cover_image" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="image_cover" id="cover_image" value="<?php echo e(!empty($bundle) ? $bundle->image_cover : old('image_cover')); ?>" class="form-control <?php $__errorArgs = ['image_cover'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                                                    <div class="input-group-append">
                                                        <button type="button" class="input-group-text admin-file-view" data-input="cover_image">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
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

                                            <div class="form-group mt-25">
                                                <label class="input-label"><?php echo e(trans('public.demo_video')); ?> (<?php echo e(trans('public.optional')); ?>)</label>


                                                <div class="">
                                                    <label class="input-label font-12"><?php echo e(trans('public.source')); ?></label>
                                                    <select name="video_demo_source"
                                                            class="js-video-demo-source form-control"
                                                    >
                                                        <?php $__currentLoopData = getFeaturesSettings('available_sources'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($source); ?>" <?php if(!empty($bundle) and $bundle->video_demo_source == $source): ?> selected <?php endif; ?>><?php echo e(trans('update.file_source_'.$source)); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="js-video-demo-other-inputs form-group mt-0 <?php echo e((empty($bundle) or !in_array($bundle->video_demo_source, ['secure_host', 's3'])) ? '' : 'd-none'); ?>">
                                                <label class="input-label font-12"><?php echo e(trans('update.path')); ?></label>
                                                <div class="input-group js-video-demo-path-input">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="js-video-demo-path-upload input-group-text admin-file-manager <?php echo e((empty($bundle) or empty($bundle->video_demo_source) or $bundle->video_demo_source == 'upload') ? '' : 'd-none'); ?>" data-input="demo_video" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>

                                                        <button type="button" class="js-video-demo-path-links rounded-left input-group-text input-group-text-rounded-left  <?php echo e((empty($bundle) or empty($bundle->video_demo_source) or $bundle->video_demo_source == 'upload') ? 'd-none' : ''); ?>">
                                                            <i class="fa fa-link"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="video_demo" id="demo_video" value="<?php echo e(!empty($bundle) ? $bundle->video_demo : old('video_demo')); ?>" class="form-control <?php $__errorArgs = ['video_demo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                                                    <?php $__errorArgs = ['video_demo'];
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

                                            <div class="form-group js-video-demo-file-input <?php echo e((!empty($bundle) and in_array($bundle->video_demo_source, ['secure_host', 's3'])) ? '' : 'd-none'); ?>">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text">
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                    </div>
                                                    <div class="custom-file js-ajax-s3_file">
                                                        <input type="file" name="video_demo_file" class="custom-file-input cursor-pointer" id="video_demo_file" accept="video/*">
                                                        <label class="custom-file-label cursor-pointer" for="video_demo_file"><?php echo e(trans('update.choose_file')); ?></label>
                                                    </div>

                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label"><?php echo e(trans('public.summary')); ?></label>
                                                <textarea name="summary" rows="5" class="form-control <?php $__errorArgs = ['summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('forms.webinar_summary_placeholder')); ?>"><?php echo !empty($bundle) ? $bundle->summary : old('summary'); ?></textarea>
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

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mt-15">
                                                <label class="input-label"><?php echo e(trans('public.description')); ?></label>
                                                <textarea id="summernote" name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('forms.webinar_description_placeholder')); ?>"><?php echo !empty($bundle) ? $bundle->description : old('description'); ?></textarea>
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
                                    </div>
                                </section>

                                <section class="mt-3">
                                    <h2 class="section-title after-line"><?php echo e(trans('public.additional_information')); ?></h2>
                                    <div class="row">
                                        <div class="col-12 col-md-6">

                                            <div class="form-group mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="" for="includeCertificateSwitch"><?php echo e(trans('update.bundle_completion_certificate')); ?></label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="certificate" class="custom-control-input" id="includeCertificateSwitch" <?php echo e(!empty($bundle) && $bundle->certificate ? 'checked' : ''); ?>>
                                                        <label class="custom-control-label" for="includeCertificateSwitch"></label>
                                                    </div>
                                                </div>

                                                <p class="mt-10 font-12 font-12 text-gray-500">- <?php echo e(trans('update.bundle_completion_certificate_hint')); ?></p>
                                            </div>


                                            <div class="form-group mt-3 d-flex align-items-center justify-content-between">
                                                <label class="" for="subscribeSwitch"><?php echo e(trans('public.subscribe')); ?></label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="subscribe" class="custom-control-input" id="subscribeSwitch" <?php echo e(!empty($bundle) && $bundle->subscribe ? 'checked' : ''); ?>>
                                                    <label class="custom-control-label" for="subscribeSwitch"></label>
                                                </div>
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label"><?php echo e(trans('update.access_days')); ?></label>
                                                <input type="text" name="access_days" value="<?php echo e(!empty($bundle) ? $bundle->access_days : old('access_days')); ?>" class="form-control <?php $__errorArgs = ['access_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                                                <?php $__errorArgs = ['access_days'];
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
                                                <p class="mt-1">- <?php echo e(trans('update.access_days_input_hint')); ?></p>
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label"><?php echo e(trans('public.price')); ?> (<?php echo e($currency); ?>)</label>
                                                <input type="text" name="price" value="<?php echo e(!empty($bundle) ? $bundle->price : old('price')); ?>" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('public.0_for_free')); ?>"/>
                                                <?php $__errorArgs = ['price'];
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

                                            
                                            <?php if(!empty($bundle)): ?>
                                                <?php echo $__env->make('admin.product_badges.content_include', ['itemTarget' => $bundle], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endif; ?>


                                            <div class="form-group mt-15">
                                                <label class="input-label d-block"><?php echo e(trans('public.tags')); ?></label>
                                                <input type="text" name="tags" data-max-tag="5" value="<?php echo e(!empty($bundle) ? implode(',',$bundleTags) : ''); ?>" class="form-control inputtags" placeholder="<?php echo e(trans('public.type_tag_name_and_press_enter')); ?> (<?php echo e(trans('admin/main.max')); ?> : 5)"/>
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label"><?php echo e(trans('public.category')); ?></label>

                                                <select id="categories" class="custom-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="category_id" required>
                                                    <option <?php echo e(!empty($bundle) ? '' : 'selected'); ?> disabled><?php echo e(trans('public.choose_category')); ?></option>
                                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(!empty($category->subCategories) and count($category->subCategories)): ?>
                                                            <optgroup label="<?php echo e($category->title); ?>">
                                                                <?php $__currentLoopData = $category->subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($subCategory->id); ?>" <?php echo e((!empty($bundle) and $bundle->category_id == $subCategory->id) ? 'selected' : ''); ?>><?php echo e($subCategory->title); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </optgroup>
                                                        <?php else: ?>
                                                            <option value="<?php echo e($category->id); ?>" <?php echo e((!empty($bundle) and $bundle->category_id == $category->id) ? 'selected' : ''); ?>><?php echo e($category->title); ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>

                                                <?php $__errorArgs = ['category_id'];
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
                                    </div>

                                    <div class="form-group mt-15 <?php echo e((!empty($bundleCategoryFilters) and count($bundleCategoryFilters)) ? '' : 'd-none'); ?>" id="categoriesFiltersContainer">
                                        <span class="input-label d-block"><?php echo e(trans('public.category_filters')); ?></span>
                                        <div id="categoriesFiltersCard" class="row mt-3">

                                            <?php if(!empty($bundleCategoryFilters) and count($bundleCategoryFilters)): ?>
                                                <?php $__currentLoopData = $bundleCategoryFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-12 col-md-3">
                                                        <div class="webinar-category-filters">
                                                            <strong class="category-filter-title d-block"><?php echo e($filter->title); ?></strong>
                                                            <div class="py-10"></div>

                                                            <?php $__currentLoopData = $filter->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="form-group mt-3 d-flex align-items-center justify-content-between">
                                                                    <label class="font-12 text-gray-500 font-14" for="filterOptions<?php echo e($option->id); ?>"><?php echo e($option->title); ?></label>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="filters[]" value="<?php echo e($option->id); ?>" <?php echo e(((!empty($bundleFilterOptions) && in_array($option->id,$bundleFilterOptions)) ? 'checked' : '')); ?> class="custom-control-input" id="filterOptions<?php echo e($option->id); ?>">
                                                                        <label class="custom-control-label" for="filterOptions<?php echo e($option->id); ?>"></label>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </section>

                                <?php if(!empty($bundle)): ?>
                                    <section class="mt-30">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="section-title after-line"><?php echo e(trans('admin/main.price_plans')); ?></h2>
                                            <button id="webinarAddTicket" type="button" class="btn btn-primary btn-sm mt-3"><?php echo e(trans('admin/main.add_price_plan')); ?></button>
                                        </div>

                                        <div class="row mt-10">
                                            <div class="col-12">

                                                <?php if(!empty($tickets) and !$tickets->isEmpty()): ?>
                                                    <div class="table-responsive">
                                                        <table class="table custom-table text-center font-14">

                                                            <tr>
                                                                <th><?php echo e(trans('public.title')); ?></th>
                                                                <th><?php echo e(trans('public.discount')); ?></th>
                                                                <th><?php echo e(trans('public.capacity')); ?></th>
                                                                <th><?php echo e(trans('public.date')); ?></th>
                                                                <th width="80px"><?php echo e(trans('admin/main.action')); ?></th>
                                                            </tr>

                                                            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <th scope="row"><?php echo e($ticket->title); ?></th>
                                                                    <td><?php echo e($ticket->discount); ?>%</td>
                                                                    <td><?php echo e($ticket->capacity); ?></td>
                                                                    <td><?php echo e(dateTimeFormat($ticket->start_date,'j F Y')); ?> - <?php echo e((new DateTime())->setTimestamp($ticket->end_date)->format('j F Y')); ?></td>
                                                                    <td>
                                                                        <div class="btn-group dropdown table-actions position-relative">
                                                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
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

                                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                                <button type="button"
                                                                                        data-ticket-id="<?php echo e($ticket->id); ?>"
                                                                                        data-webinar-id="<?php echo e(!empty($bundle) ? $bundle->id : ''); ?>"
                                                                                        class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 edit-ticket">
                                                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-edit-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500 mr-2','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                                                    <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.edit')); ?></span>
                                                                                </button>

                                                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                                                    'url' => getAdminPanelUrl().'/tickets/'.$ticket->id.'/delete',
                                                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                                    'btnText' => trans('admin/main.delete'),
                                                                                    'btnIcon' => 'trash',
                                                                                    'iconType' => 'lin',
                                                                                    'iconClass' => 'text-danger mr-2'
                                                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                                                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-receipt-2'); ?>
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
                                                        <h3 class="font-16 font-weight-bold mt-12"><?php echo e(trans('public.ticket_no_result')); ?></h3>
                                                        <p class="mt-4 font-12 text-gray-500"><?php echo trans('public.ticket_no_result_hint'); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </section>


                                    <section class="mt-30">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="section-title after-line"><?php echo e(trans('product.courses')); ?></h2>
                                            <button id="bundleAddNewCourses" type="button" class="btn btn-primary btn-sm mt-3"><?php echo e(trans('update.add_new_course')); ?></button>
                                        </div>

                                        <div class="row mt-10">
                                            <div class="col-12">
                                                <?php if(!empty($bundleWebinars) and !$bundleWebinars->isEmpty()): ?>
                                                    <div class="table-responsive">
                                                        <table class="table custom-table text-center font-14">

                                                            <tr>
                                                                <th><?php echo e(trans('public.title')); ?></th>
                                                                <th class="text-left"><?php echo e(trans('public.instructor')); ?></th>
                                                                <th><?php echo e(trans('public.price')); ?></th>
                                                                <th><?php echo e(trans('public.publish_date')); ?></th>
                                                               <th width="80px"><?php echo e(trans('admin/main.action')); ?></th>
                                                            </tr>

                                                            <?php $__currentLoopData = $bundleWebinars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bundleWebinar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if(!empty($bundleWebinar->webinar->title)): ?>
                                                                    <tr>
                                                                        <th><?php echo e($bundleWebinar->webinar->title); ?></th>
                                                                        <td class="text-left"><?php echo e($bundleWebinar->webinar->teacher->full_name); ?></td>
                                                                        <td><?php echo e(!empty($bundleWebinar->webinar->price) ? handlePrice($bundleWebinar->webinar->price) : trans("public.free")); ?></td>
                                                                        <td><?php echo e(dateTimeFormat($bundleWebinar->webinar->created_at,'j F Y | H:i')); ?></td>

                                                                        <td>
                                                                            <div class="btn-group dropdown table-actions position-relative">
                                                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
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

                                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                                    <button type="button"
                                                                                            data-item-id="<?php echo e($bundleWebinar->id); ?>"
                                                                                            data-bundle-id="<?php echo e(!empty($bundle) ? $bundle->id : ''); ?>"
                                                                                            class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 edit-bundle-webinar">
                                                                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-edit-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500 mr-2','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                                                        <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.edit')); ?></span>
                                                                                    </button>

                                                                                    <?php echo $__env->make('admin.includes.delete_button',[
                                                                                        'url' => getAdminPanelUrl().'/bundle-webinars/'.$bundleWebinar->id.'/delete',
                                                                                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                                        'btnText' => trans('admin/main.delete'),
                                                                                        'btnIcon' => 'trash',
                                                                                        'iconType' => 'lin',
                                                                                        'iconClass' => 'text-danger mr-2'
                                                                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                                                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
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
                                                        <h3 class="font-16 font-weight-bold mt-12"><?php echo e(trans('update.bundle_webinar_no_result')); ?></h3>
                                                        <p class="mt-4 font-12 text-gray-500"><?php echo trans('update.bundle_webinar_no_result_hint'); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </section>

                                    
                                    <?php echo $__env->make('admin.webinars.relatedCourse.add_related_course', [
                                            'relatedCourseItemId' => $bundle->id,
                                             'relatedCourseItemType' => 'bundle',
                                             'relatedCourses' => $bundle->relatedCourses
                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                    <section class="mt-30">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="section-title after-line"><?php echo e(trans('public.faq')); ?></h2>
                                            <button id="webinarAddFAQ" type="button" class="btn btn-primary btn-sm mt-3"><?php echo e(trans('public.add_faq')); ?></button>
                                        </div>

                                        <div class="row mt-10">
                                            <div class="col-12">
                                                <?php if(!empty($faqs) and !$faqs->isEmpty()): ?>
                                                    <div class="table-responsive">
                                                        <table class="table custom-table text-center font-14">

                                                            <tr>
                                                                <th><?php echo e(trans('public.title')); ?></th>
                                                                <th><?php echo e(trans('public.answer')); ?></th>
                                                                <th width="80px"><?php echo e(trans('admin/main.action')); ?></th>
                                                            </tr>

                                                            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <th><?php echo e($faq->title); ?></th>
                                                                    <td>
                                                                        <button type="button" class="js-get-faq-description btn btn-sm btn-gray200"><?php echo e(trans('public.view')); ?></button>
                                                                        <input type="hidden" value="<?php echo e($faq->answer); ?>"/>
                                                                    </td>

                                                                    <td>
                                                                        <div class="btn-group dropdown table-actions position-relative">
                                                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
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

                                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                                <button type="button"
                                                                                        data-faq-id="<?php echo e($faq->id); ?>"
                                                                                        data-bundle-id="<?php echo e(!empty($bundle) ? $bundle->id : ''); ?>"
                                                                                        class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 edit-faq">
                                                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-edit-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500 mr-2','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                                                    <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.edit')); ?></span>
                                                                                </button>

                                                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                                                    'url' => getAdminPanelUrl().'/faqs/'.$faq->id.'/delete',
                                                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                                    'btnText' => trans('admin/main.delete'),
                                                                                    'btnIcon' => 'trash',
                                                                                    'iconType' => 'lin',
                                                                                    'iconClass' => 'text-danger mr-2'
                                                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                                                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-message-question'); ?>
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
                                                        <h3 class="font-16 font-weight-bold mt-12"><?php echo e(trans('public.faq_no_result')); ?></h3>
                                                        <p class="mt-4 font-12 text-gray-500"><?php echo trans('public.faq_no_result_hint'); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </section>
                                <?php endif; ?>

                                <section class="mt-3">
                                    <h2 class="section-title after-line"><?php echo e(trans('public.message_to_reviewer')); ?></h2>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mt-15">
                                                <textarea name="message_for_reviewer" rows="10" class="form-control"><?php echo e((!empty($bundle) and $bundle->message_for_reviewer) ? $bundle->message_for_reviewer : old('message_for_reviewer')); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <input type="hidden" name="draft" value="no" id="forDraft"/>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" id="saveAndPublish" class="btn btn-success"><?php echo e(!empty($bundle) ? trans('admin/main.save_and_publish') : trans('admin/main.save_and_continue')); ?></button>

                                        <?php if(!empty($bundle)): ?>
                                            <button type="button" id="saveReject" class="btn btn-warning"><?php echo e(trans('public.reject')); ?></button>

                                            <?php echo $__env->make('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/bundles/'. $bundle->id .'/delete',
                                                    'btnText' => trans('public.delete'),
                                                    'hideDefaultClass' => true,
                                                    'btnClass' => 'btn btn-danger'
                                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>


                            <?php echo $__env->make('admin.bundles.modals.bundle-webinar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo $__env->make('admin.bundles.modals.ticket', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo $__env->make('admin.bundles.modals.faq', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var saveSuccessLang = '<?php echo e(trans('webinars.success_store')); ?>';
        var titleLang = '<?php echo e(trans('admin/main.title')); ?>';
    </script>

    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/admin/js/parts/webinar.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/bundles/create.blade.php ENDPATH**/ ?>