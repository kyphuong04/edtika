

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e($pageTitle); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('update.upcoming_courses')); ?></div>
            </div>
        </div> 

        <div class="section-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"><?php echo e(trans('update.total_courses')); ?></span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-time'); ?>
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
                            <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalCourses); ?></h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"><?php echo e(trans('update.released_courses')); ?></span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-tick'); ?>
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
                            <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($releasedCourses); ?></h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"><?php echo e(trans('update.not_released')); ?></span>
                                <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-remove'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($notReleased); ?></h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"><?php echo e(trans('update.followers')); ?></span>
                                <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-profile-2user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-secondary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($followers); ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.search')); ?></label>
                                    <input name="title" type="text" class="form-control" value="<?php echo e(request()->get('title')); ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('update.release_date')); ?></label>
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
                                        <option value=""><?php echo e(trans('public.all')); ?></option>
                                        <option value="newest" <?php if(request()->get('sort', null) == 'newest'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('public.newest')); ?></option>
                                        <option value="earliest_publish_date" <?php if(request()->get('sort', null) == 'earliest_publish_date'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('update.earliest_publish_date')); ?></option>
                                        <option value="farthest_publish_date" <?php if(request()->get('sort', null) == 'farthest_publish_date'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('update.farthest_publish_date')); ?></option>
                                        <option value="highest_price" <?php if(request()->get('sort', null) == 'highest_price'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('update.highest_price')); ?></option>
                                        <option value="lowest_price" <?php if(request()->get('sort', null) == 'lowest_price'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('update.lowest_price')); ?></option>
                                        <option value="only_not_released_courses" <?php if(request()->get('sort', null) == 'only_not_released_courses'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('update.only_not_released_courses')); ?></option>
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
                                        <option value="active" <?php if(request()->get('status') == 'active'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.published')); ?></option>
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
                               <h5 class="font-14 mb-0"><?php echo e($pageTitle); ?></h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_upcoming_courses_in_a_single_place')); ?></p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinars_export_excel')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl('/upcoming_courses/excel?'. http_build_query(request()->all()))); ?>" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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

                               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_upcoming_courses_create')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl('/upcoming_courses/new')); ?>" target="_blank" class="btn btn-primary">
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
                                       <span class="ml-4 font-12"><?php echo e(trans('admin/main.new')); ?> <?php echo e(trans('update.upcoming_course')); ?></span>
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
                                        <th><?php echo e(trans('admin/main.type')); ?></th>
                                        <th><?php echo e(trans('admin/main.price')); ?></th>
                                        <th><?php echo e(trans('update.followers')); ?></th>
                                        <th><?php echo e(trans('admin/main.start_date')); ?></th>
                                        <th><?php echo e(trans('admin/main.created_at')); ?></th>
                                        <th><?php echo e(trans('admin/main.status')); ?></th>
                                        <th width="120"><?php echo e(trans('admin/main.actions')); ?></th>
                                    </tr>

                                    <?php $__currentLoopData = $upcomingCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $upcomingCourse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="text-center">
                                            <td><?php echo e($upcomingCourse->id); ?></td>

                                            <td width="18%" class="text-left">
                                                <a class="text-dark mt-0 mb-1" href="<?php echo e($upcomingCourse->getUrl()); ?>"><?php echo e($upcomingCourse->title); ?></a>
                                                <?php if(!empty($upcomingCourse->category->title)): ?>
                                                    <div class="text-small text-gray-500"><?php echo e($upcomingCourse->category->title); ?></div>
                                                <?php else: ?>
                                                    <div class="text-small text-warning"><?php echo e(trans('admin/main.no_category')); ?></div>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-left"><?php echo e($upcomingCourse->teacher->full_name); ?></td>

                                            <td class=""><?php echo e(trans('admin/main.'.$upcomingCourse->type)); ?></td>

                                            <td>
                                                <?php if(!empty($upcomingCourse->price) and $upcomingCourse->price > 0): ?>
                                                    <span class="mt-0 mb-1">
                                                        <?php echo e(handlePrice($upcomingCourse->price, true, true)); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <?php echo e(trans('public.free')); ?>

                                                <?php endif; ?>
                                            </td>

                                            <td class="font-12">
                                                <a href="<?php echo e(getAdminPanelUrl('/upcoming_courses/'. $upcomingCourse->id .'/followers')); ?>" target="_blank" class="text-dark"><?php echo e($upcomingCourse->followers_count); ?></a>
                                            </td>

                                            <td><?php echo e(dateTimeFormat($upcomingCourse->publish_date, 'Y M j | H:i')); ?></td>

                                            <td><?php echo e(dateTimeFormat($upcomingCourse->created_at, 'Y M j | H:i')); ?></td>

                                            <td>
                                                <?php if(!empty($upcomingCourse->webinar_id)): ?>
                                                    <span class="badge-status text-success bg-success-30"><?php echo e(trans('update.released')); ?></span>
                                                <?php else: ?>
                                                    <?php switch($upcomingCourse->status):
                                                        case (\App\Models\Webinar::$active): ?>
                                                            <span class="badge-status text-primary bg-primary-30"><?php echo e(trans('admin/main.published')); ?></span>
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
                                                <?php endif; ?>
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

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_upcoming_courses_edit')): ?>
                                                    <?php if($upcomingCourse->status != \App\Models\Webinar::$active): ?>
                                                       
                                                            <?php echo $__env->make('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/approve'),
                                                           'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.approve"),
                                                           'btnIcon' => 'tick-square',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-success mr-2',
                                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        <?php endif; ?>
                                                        <?php if($upcomingCourse->status == \App\Models\Webinar::$pending): ?>
                                                        <?php echo $__env->make('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/reject'),
                                                           'btnClass' => 'dropdown-item  text-danger mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.reject"),
                                                           'btnIcon' => 'close-square',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        <?php endif; ?>
                                                        <?php if($upcomingCourse->status == \App\Models\Webinar::$active): ?>
                                                            <?php echo $__env->make('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/unpublish'),
                                                           'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.unpublish"),
                                                           'btnIcon' => 'gallery-slash',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                                        <?php endif; ?>
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_upcoming_courses_followers')): ?>
                                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/upcoming_courses/<?php echo e($upcomingCourse->id); ?>/followers" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-people'); ?>
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
                                                                <span class="text-gray-500 font-14"><?php echo e(trans('update.followers')); ?></span>
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_upcoming_courses_edit')): ?>
                                                            <a href="<?php echo e(getAdminPanelUrl('/upcoming_courses/'. $upcomingCourse->id .'/edit')); ?>" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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
                                                            'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/delete'),
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
                            <?php echo e($upcomingCourses->appends(request()->input())->links()); ?>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/upcoming_courses/lists.blade.php ENDPATH**/ ?>