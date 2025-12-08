<?php $__env->startPush('libraries_top'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('admin/main.type_'.$classesType.'s')); ?> <?php echo e(trans('admin/main.list')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('admin/main.classes')); ?></div>

                <div class="breadcrumb-item"><?php echo e(trans('admin/main.type_'.$classesType.'s')); ?></div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.total')); ?> <?php echo e(trans('admin/main.type_'.$classesType.'s')); ?></span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
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
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalWebinars); ?></h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.pending_review')); ?> <?php echo e(trans('admin/main.type_'.$classesType.'s')); ?></span>
                                <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-time'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalPendingWebinars); ?></h5>
                        </div>
                    </div>
                </div>

                <?php if($classesType == 'webinar'): ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card-statistic">
                            <div class="card-statistic__mask"></div>
                            <div class="card-statistic__wrap">
                                <div class="d-flex align-items-start justify-content-between">
                                    <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.inprogress_live_classes')); ?></span>
                                    <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-timer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-accent','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    </div>
                                </div>

                                <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($inProgressWebinars); ?></h5>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card-statistic">
                            <div class="card-statistic__mask"></div>
                            <div class="card-statistic__wrap">
                                <div class="d-flex align-items-start justify-content-between">
                                    <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.total_durations')); ?></span>
                                    <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clock'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-accent','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    </div>
                                </div>

                                <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e(convertMinutesToHourAndMinute($totalDurations)); ?> <?php echo e(trans('home.hours')); ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.total_sales')); ?></span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-bag'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalSales); ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">
                        <input type="hidden" name="type" value="<?php echo e(request()->get('type')); ?>">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.search')); ?></label>
                                    <input name="title" type="text" class="form-control" value="<?php echo e(request()->get('title')); ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.start_date')); ?></label>
                                    <div class="input-group">
                                        <input type="date" id="from" class="text-center form-control" name="from" value="<?php echo e(request()->get('from')); ?>" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.end_date')); ?></label>
                                    <div class="input-group">
                                        <input type="date" id="to" class="text-center form-control" name="to" value="<?php echo e(request()->get('to')); ?>" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.filters')); ?></label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value=""><?php echo e(trans('admin/main.filter_type')); ?></option>
                                        <option value="has_discount" <?php if(request()->get('sort') == 'has_discount'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.discounted_classes')); ?></option>
                                        <option value="sales_asc" <?php if(request()->get('sort') == 'sales_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.sales_ascending')); ?></option>
                                        <option value="sales_desc" <?php if(request()->get('sort') == 'sales_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.sales_descending')); ?></option>
                                        <option value="price_asc" <?php if(request()->get('sort') == 'price_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.Price_ascending')); ?></option>
                                        <option value="price_desc" <?php if(request()->get('sort') == 'price_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.Price_descending')); ?></option>
                                        <option value="income_asc" <?php if(request()->get('sort') == 'income_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.Income_ascending')); ?></option>
                                        <option value="income_desc" <?php if(request()->get('sort') == 'income_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.Income_descending')); ?></option>
                                        <option value="created_at_asc" <?php if(request()->get('sort') == 'created_at_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.create_date_ascending')); ?></option>
                                        <option value="created_at_desc" <?php if(request()->get('sort') == 'created_at_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.create_date_descending')); ?></option>
                                        <option value="updated_at_asc" <?php if(request()->get('sort') == 'updated_at_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.update_date_ascending')); ?></option>
                                        <option value="updated_at_desc" <?php if(request()->get('sort') == 'updated_at_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.update_date_descending')); ?></option>
                                        <option value="public_courses" <?php if(request()->get('sort') == 'public_courses'): ?> selected <?php endif; ?>><?php echo e(trans('update.public_courses')); ?></option>
                                        <option value="courses_private" <?php if(request()->get('sort') == 'courses_private'): ?> selected <?php endif; ?>><?php echo e(trans('update.courses_private')); ?></option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.instructor')); ?></label>
                                    <select name="teacher_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                            data-placeholder="Search teachers">

                                        <?php if(!empty($teachers) and $teachers->count() > 0): ?>
                                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($teacher->id); ?>" selected><?php echo e($teacher->full_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.category')); ?></label>
                                    <select name="category_id" data-plugin-selectTwo class="form-control populate">
                                        <option value=""><?php echo e(trans('admin/main.all_categories')); ?></option>

                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(!empty($category->subCategories) and count($category->subCategories)): ?>
                                                <optgroup label="<?php echo e($category->title); ?>">
                                                    <?php $__currentLoopData = $category->subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($subCategory->id); ?>" <?php if(request()->get('category_id') == $subCategory->id): ?> selected="selected" <?php endif; ?>><?php echo e($subCategory->title); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                            <?php else: ?>
                                                <option value="<?php echo e($category->id); ?>" <?php if(request()->get('category_id') == $category->id): ?> selected="selected" <?php endif; ?>><?php echo e($category->title); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.status')); ?></label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value=""><?php echo e(trans('admin/main.all_status')); ?></option>
                                        <option value="pending" <?php if(request()->get('status') == 'pending'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.pending_review')); ?></option>
                                        <?php if($classesType == 'webinar'): ?>
                                            <option value="active_not_conducted" <?php if(request()->get('status') == 'active_not_conducted'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.publish_not_conducted')); ?></option>
                                            <option value="active_in_progress" <?php if(request()->get('status') == 'active_in_progress'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.publish_inprogress')); ?></option>
                                            <option value="active_finished" <?php if(request()->get('status') == 'active_finished'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.publish_finished')); ?></option>
                                        <?php else: ?>
                                            <option value="active" <?php if(request()->get('status') == 'active'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.published')); ?></option>
                                        <?php endif; ?>
                                        <option value="inactive" <?php if(request()->get('status') == 'inactive'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.rejected')); ?></option>
                                        <option value="is_draft" <?php if(request()->get('status') == 'is_draft'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.draft')); ?></option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg"><?php echo e(trans('admin/main.show_results')); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <div>
                                <h5 class="font-14 mb-0"><?php echo e(trans('admin/main.type_'.$classesType.'s')); ?></h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_courses_in_a_single_place')); ?></p>
                            </div>

                            <div class="d-flex align-items-center gap-12">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinars_export_excel')): ?>
                                    <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/excel?<?php echo e(http_build_query(request()->all())); ?>" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-import-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        <span class="ml-4 font-12"><?php echo e(trans('admin/main.export_xls')); ?></span>
                                    </a>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinars_create')): ?>
                                    <a href="<?php echo e(getAdminPanelUrl("/webinars/create")); ?>" target="_blank" class="btn btn-primary">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        <span class="ml-4 font-12"><?php echo e(trans('admin/main.webinar_new_page_title')); ?></span>
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>

                        <div class="card-body">
                            <div>
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th><?php echo e(trans('admin/main.id')); ?></th>
                                        <th class="text-left"><?php echo e(trans('admin/main.title')); ?></th>
                                        <th class="text-left"><?php echo e(trans('admin/main.instructor')); ?></th>
                                        <th><?php echo e(trans('admin/main.price')); ?></th>
                                        <th><?php echo e(trans('admin/main.sales')); ?></th>
                                        <th><?php echo e(trans('admin/main.income')); ?></th>
                                        <th><?php echo e(trans('admin/main.students_count')); ?></th>
                                        <th><?php echo e(trans('admin/main.created_at')); ?></th>
                                        <?php if($classesType == 'webinar'): ?>
                                            <th><?php echo e(trans('admin/main.start_date')); ?></th>
                                            <th><?php echo e(trans('admin/main.live_status')); ?></th>
                                        <?php else: ?>
                                            <th><?php echo e(trans('admin/main.updated_at')); ?></th>
                                        <?php endif; ?>
                                        <th><?php echo e(trans('admin/main.status')); ?></th>
                                        <th width="120"><?php echo e(trans('admin/main.actions')); ?></th>
                                    </tr>

                                    <?php $__currentLoopData = $webinars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webinar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="text-center">
                                            <td><?php echo e($webinar->id); ?></td>
                                            <td width="18%" class="text-left">
                                                <a class="text-dark mt-0 mb-1 " href="<?php echo e($webinar->getUrl()); ?>"><?php echo e($webinar->title); ?></a>
                                                <?php if(!empty($webinar->category->title)): ?>
                                                    <div class="text-small text-gray-500"><?php echo e($webinar->category->title); ?></div>
                                                <?php else: ?>
                                                    <div class="text-small text-warning"><?php echo e(trans('admin/main.no_category')); ?></div>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-left"><?php echo e($webinar->teacher->full_name); ?></td>

                                            <td>
                                                <?php if(!empty($webinar->price) and $webinar->price > 0): ?>
                                                    <span class="mt-0 mb-1">
                                                        <?php echo e(handlePrice($webinar->price, true, true)); ?>

                                                    </span>

                                                    <?php if($webinar->getDiscountPercent() > 0): ?>
                                                        <div class="badge-status text-danger bg-danger-30"><?php echo e($webinar->getDiscountPercent()); ?>% <?php echo e(trans('admin/main.off')); ?></div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?php echo e(trans('public.free')); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="text-dark mt-0 mb-1 ">
                                                    <?php echo e($webinar->sales->count()); ?>

                                                </span>

                                                <?php if(!empty($webinar->capacity)): ?>
                                                    <div class="font-12 text-gray-500"><?php echo e(trans('admin/main.capacity')); ?> : <?php echo e($webinar->getWebinarCapacity()); ?></div>
                                                <?php endif; ?>
                                            </td>

                                            <td><?php echo e(handlePrice($webinar->sales->sum('total_amount'))); ?></td>

                                            <td class="font-12">
                                                <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/<?php echo e($webinar->id); ?>/students" target="_blank" class=""><?php echo e($webinar->sales->count()); ?></a>
                                            </td>

                                            <td><?php echo e(dateTimeFormat($webinar->created_at, 'Y M j | H:i')); ?></td>

                                            <?php if($classesType == 'webinar'): ?>
                                                <td><?php echo e(dateTimeFormat($webinar->start_date, 'Y M j | H:i')); ?></td>
                                                <?php if($webinar->isWebinar()): ?>
                                                <td>
                                                <?php switch($webinar->status):
                                                    case (\App\Models\Webinar::$active): ?>
                                                        <?php if($webinar->isWebinar()): ?>
                                                            <?php if($webinar->start_date > time()): ?>
                                                                <div class="font-14 badge-status text-danger"><?php echo e(trans('admin/main.not_conducted')); ?></div>
                                                            <?php elseif($webinar->isProgressing()): ?>
                                                                <div class="font-14 badge-status text-warning"><?php echo e(trans('webinars.in_progress')); ?></div>
                                                            <?php else: ?>
                                                                <div class=" font-14 badge-status text-success"><?php echo e(trans('public.finished')); ?></div>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php break; ?>
                                                         <?php case (\App\Models\Webinar::$isDraft): ?>
                                                        <span class="font-14 badge-status">-</span>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$pending): ?>
                                                        <span class="font-14 badge-status">-</span>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$inactive): ?>
                                                        <span class="font-14 badge-status">-</span>
                                                        <?php break; ?>

                                                <?php endswitch; ?>
                                               </td>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <td><?php echo e(dateTimeFormat($webinar->updated_at, 'Y M j | H:i')); ?></td>
                                            <?php endif; ?>

                                            <td>
                                                <?php switch($webinar->status):
                                                    case (\App\Models\Webinar::$active): ?>
                                                        <div class="badge-status text-success bg-success-30"><?php echo e(trans('admin/main.published')); ?></div>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$isDraft): ?>
                                                        <span class="badge-status text-dark bg-dark-30"><?php echo e(trans('admin/main.is_draft')); ?></span>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$pending): ?>
                                                        <span class="badge-status text-warning bg-warning-30"><?php echo e(trans('admin/main.waiting')); ?></span>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$inactive): ?>
                                                        <span class="badge-status text-danger bg-danger-30"><?php echo e(trans('public.rejected')); ?></span>
                                                        <?php break; ?>
                                                <?php endswitch; ?>
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

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinars_edit')): ?>
                                                            <?php if(in_array($webinar->status, [\App\Models\Webinar::$pending, \App\Models\Webinar::$inactive])): ?>

                                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                                   'url' => getAdminPanelUrl().'/webinars/'.$webinar->id.'/approve',
                                                                   'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                                   'btnText' => trans("admin/main.approve"),
                                                                   'btnIcon' => 'tick-square',
                                                                   'iconType' => 'lin',
                                                                   'iconClass' => 'text-success mr-2',
                                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endif; ?>
                                                            <?php if($webinar->status == \App\Models\Webinar::$pending): ?>
                                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                                   'url' => getAdminPanelUrl().'/webinars/'.$webinar->id.'/reject',
                                                                   'btnClass' => 'dropdown-item  text-danger mb-3 py-3 px-0 font-14',
                                                                   'btnText' => trans("admin/main.reject"),
                                                                   'btnIcon' => 'close-square',
                                                                   'iconType' => 'lin',
                                                                   'iconClass' => 'text-danger mr-2',
                                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endif; ?>
                                                            <?php if($webinar->status == \App\Models\Webinar::$active): ?>

                                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                               'url' => getAdminPanelUrl().'/webinars/'.$webinar->id.'/unpublish',
                                                               'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                                               'btnText' => trans("admin/main.unpublish"),
                                                               'btnIcon' => 'gallery-slash',
                                                               'iconType' => 'lin',
                                                               'iconClass' => 'text-danger mr-2',
                                                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinar_notification_to_students')): ?>
                                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/<?php echo e($webinar->id); ?>/sendNotification" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-notification-bing'); ?>
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
                                                                <span class="text-gray-500 font-14"><?php echo e(trans('notification.send_notification')); ?></span>
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinar_students_lists')): ?>
                                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/<?php echo e($webinar->id); ?>/students" class="dropdown-item d-flex btn-transparent align-items-center mb-3 py-3 px-0 gap-4">
                                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-teacher'); ?>
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
                                                                <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.students')); ?></span>
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinar_statistics')): ?>
                                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/<?php echo e($webinar->id); ?>/statistics" class="dropdown-item btn-transparent d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-graph'); ?>
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
                                                                <span class="text-gray-500 font-14"><?php echo e(trans('update.statistics')); ?></span>
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_support_send')): ?>
                                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/supports/create?user_id=<?php echo e($webinar->teacher->id); ?>" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-sms-tracking'); ?>
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
                                                                <span class="text-gray-500 font-14"><?php echo e(trans('site.send_message')); ?></span>
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinars_edit')): ?>
                                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/<?php echo e($webinar->id); ?>/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinars_delete')): ?>

                                                            <?php echo $__env->make('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/webinars/'.$webinar->id.'/delete',
                                                           'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.delete"),
                                                           'btnIcon' => 'trash',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>


                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <?php echo e($webinars->appends(request()->input())->links()); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/webinars/lists.blade.php ENDPATH**/ ?>