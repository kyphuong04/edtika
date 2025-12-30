<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
<?php $__env->stopPush(); ?>

<div class="bg-white rounded-16 p-16 mt-32">
    <h3 class="font-14 font-weight-bold"><?php echo e(trans('update.taxonomy')); ?></h3>

    <div class="form-group  mt-24">
        <label class="form-group-label is-required"><?php echo e(trans('public.category')); ?></label>

        <select name="category_id" id="categories" class="select2 <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <option <?php echo e((!empty($webinar) and !empty($webinar->category_id)) ? '' : 'selected'); ?> disabled><?php echo e(trans('public.choose_category')); ?></option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($category->subCategories) and $category->subCategories->count() > 0): ?>
                    <optgroup label="<?php echo e($category->title); ?>">
                        <?php $__currentLoopData = $category->subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subCategory->id); ?>" <?php echo e(((!empty($webinar) and $webinar->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : ''); ?>><?php echo e($subCategory->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                <?php else: ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(((!empty($webinar) and $webinar->category_id == $category->id) or old('category_id') == $category->id) ? 'selected' : ''); ?>><?php echo e($category->title); ?></option>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <?php $__errorArgs = ['category_id'];
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

    <div class="mt-24 <?php echo e((!empty($webinarCategoryFilters) and count($webinarCategoryFilters)) ? '' : 'd-none'); ?>" id="categoriesFiltersContainer">
        <h3 class="font-14 font-weight-bold"><?php echo e(trans('public.category_filters')); ?></h3>

        <div id="categoriesFiltersCard" class="row">
            <?php if(!empty($webinarCategoryFilters) and count($webinarCategoryFilters)): ?>
                <?php $__currentLoopData = $webinarCategoryFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12 col-md-3 mt-16">
                        <div class="create-course-filter-card bg-white p-16 rounded-12 border-gray-200">
                            <h5 class="font-14 font-weight-bold mb-16"><?php echo e($filter->title); ?></h5>

                            <?php
                                $webinarFilterOptions = $webinar->filterOptions->pluck('filter_option_id')->toArray();

                                if (!empty(old('filters'))) {
                                    $webinarFilterOptions = array_merge($webinarFilterOptions, old('filters'));
                                }
                            ?>

                            <?php $__currentLoopData = $filter->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="custom-control custom-checkbox <?php echo e($loop->first ? '' : 'mt-12'); ?>">
                                    <input type="checkbox" name="filters[]" value="<?php echo e($option->id); ?>" id="filterOptions<?php echo e($option->id); ?>" class="custom-control-input" <?php echo e(((!empty($webinarFilterOptions) && in_array($option->id, $webinarFilterOptions)) ? 'checked' : '')); ?>>
                                    <label class="custom-control__label cursor-pointer" for="filterOptions<?php echo e($option->id); ?>"><?php echo e($option->title); ?></label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>

    

    <h3 class="font-14 font-weight-bold my-24"><?php echo e(trans('update.course_options')); ?></h3>

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('public.capacity')); ?></label>
        <input type="number" name="capacity" value="<?php echo e((!empty($webinar) and !empty($webinar->capacity)) ? $webinar->capacity : old('capacity')); ?>" class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('forms.capacity_placeholder')); ?>"/>
        <?php $__errorArgs = ['capacity'];
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
        <p class="text-gray-500 mt-5 font-12"><?php echo e(trans('forms.empty_means_unlimited')); ?></p>
    </div>


    <div class="row">

        <?php if($webinar->isWebinar()): ?>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <span class="has-translation bg-transparent"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-gray-border','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>
                    <label class="form-group-label is-required"><?php echo e(trans('public.start_date')); ?></label>
                    <input type="text" name="start_date" class="form-control datetimepicker js-default-init-date-picker <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e((!empty($webinar) and $webinar->start_date) ? dateTimeFormat($webinar->start_date, 'Y-m-d H:i', false, false, $webinar->timezone) : old('start_date')); ?>" autocomplete="off">

                    <?php $__errorArgs = ['start_date'];
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
        <?php endif; ?>

        <div class="col-12 <?php if($webinar->isWebinar()): ?> col-md-6 <?php endif; ?>">
            <div class="form-group">
                <span class="has-translation px-8 bg-gray-100 w-auto text-gray-500"><?php echo e(trans('public.minutes')); ?></span>
                <label class="form-group-label is-required"><?php echo e(trans('public.duration')); ?></label>
                <input type="text" name="duration" class="form-control <?php $__errorArgs = ['duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e((!empty($webinar) and !empty($webinar->duration)) ? $webinar->duration : old('duration')); ?>">

                <?php $__errorArgs = ['duration'];
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

    <?php if($webinar->isWebinar() and getFeaturesSettings('timezone_in_create_webinar')): ?>
        <?php
            $selectedTimezone = getGeneralSettings('default_time_zone');

            if (!empty($webinar->timezone)) {
                $selectedTimezone = $webinar->timezone;
            } elseif (!empty($authUser) and !empty($authUser->timezone)) {
                $selectedTimezone = $authUser->timezone;
            }
        ?>

        <div class="form-group ">
            <label class="form-group-label"><?php echo e(trans('update.timezone')); ?></label>
            <select name="timezone" class="form-control select2" data-allow-clear="false">
                <?php $__currentLoopData = getListOfTimezones(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($timezone); ?>" <?php if($selectedTimezone == $timezone): ?> selected <?php endif; ?>><?php echo e($timezone); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['timezone'];
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
    <?php endif; ?>

    <?php if(!empty(getFeaturesSettings("course_forum_status"))): ?>
        <div class="form-group">
            <div class="d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="forumSwitch" type="checkbox" name="forum" class="custom-control-input" <?php echo e((!empty($webinar) and $webinar->forum) ? 'checked' :  ''); ?>>
                    <label class="custom-control-label cursor-pointer" for="forumSwitch"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="forumSwitch"><?php echo e(trans('update.course_forum')); ?></label>
                </div>
            </div>

            <p class="font-12 text-gray-500 mt-8">- <?php echo e(trans('update.panel_course_forum_hint')); ?></p>
        </div>
    <?php endif; ?>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="supportSwitch" type="checkbox" name="support" class="custom-control-input" <?php echo e((!empty($webinar) and $webinar->support) ? 'checked' :  ''); ?>>
            <label class="custom-control-label cursor-pointer" for="supportSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="supportSwitch"><?php echo e(trans('webinars.support')); ?></label>
        </div>
    </div>

    <?php if(!empty(getCertificateMainSettings("status"))): ?>
        <div class="form-group">
            <div class="d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="certificateSwitch" type="checkbox" name="certificate" class="custom-control-input" <?php echo e((!empty($webinar) and $webinar->certificate) ? 'checked' :  ''); ?>>
                    <label class="custom-control-label cursor-pointer" for="certificateSwitch"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="certificateSwitch"><?php echo e(trans('update.include_certificate')); ?></label>
                </div>
            </div>

            <p class="font-12 text-gray-500 mt-8">- <?php echo e(trans('update.certificate_completion_hint')); ?></p>
        </div>
    <?php endif; ?>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="downloadableSwitch" type="checkbox" name="downloadable" class="custom-control-input" <?php echo e((!empty($webinar) and $webinar->downloadable) ? 'checked' :  ''); ?>>
            <label class="custom-control-label cursor-pointer" for="downloadableSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="downloadableSwitch"><?php echo e(trans('home.downloadable')); ?></label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="partnerInstructorSwitch" type="checkbox" name="partner_instructor" class="custom-control-input" <?php echo e((!empty($webinar) and $webinar->partner_instructor) ? 'checked' :  ''); ?>>
            <label class="custom-control-label cursor-pointer" for="partnerInstructorSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="partnerInstructorSwitch"><?php echo e(trans('public.partner_instructor')); ?></label>
        </div>
    </div>


    <div id="partnerInstructorInput" class="form-group  <?php echo e((!empty($webinar) and $webinar->partner_instructor) ? '' : 'd-none'); ?>">
        <label class="form-group-label d-block"><?php echo e(trans('public.select_a_partner_teacher')); ?></label>

        <select name="partners[]" class="form-control searchable-select bg-white" multiple data-allow-clear="false" data-placeholder="<?php echo e(trans('public.search_instructor')); ?>"
                data-api-path="/users/search"
                data-item-column-name="full_name"
                data-option=""
                
        >
            <?php if(!empty($webinar->webinarPartnerTeacher)): ?>
                <?php $__currentLoopData = $webinar->webinarPartnerTeacher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partnerTeacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option selected value="<?php echo e($partnerTeacher->teacher->id); ?>"><?php echo e($partnerTeacher->teacher->full_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </select>

        <div class="text-gray-500 font-12 mt-8"><?php echo e(trans('admin/main.invited_instructor_hint')); ?></div>

        <?php $__errorArgs = ['partners'];
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

    <div class="form-group tagsinput-bg-white mt-15">
        <label class="form-group-label d-block"><?php echo e(trans('public.tags')); ?></label>
        <input type="text" name="tags" data-max-tag="5" value="<?php echo e(!empty($webinar) ? implode(',',$webinarTags) : ''); ?>" class="form-control inputtags" placeholder="<?php echo e(trans('public.type_tag_name_and_press_enter')); ?> (<?php echo e(trans('forms.max')); ?> : 5)"/>
    </div>


</div>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/steps/step_2.blade.php ENDPATH**/ ?>