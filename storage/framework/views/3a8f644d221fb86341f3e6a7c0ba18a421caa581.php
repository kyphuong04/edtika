<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">

    <style>
        .select2-container {
            z-index: 1212 !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e($webinar->title); ?> - <?php echo e($pageTitle); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a></div>
                <div class="breadcrumb-item"><a><?php echo e($pageTitle); ?></a></div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card-statistic">
                <div class="card-statistic__mask"></div>
                <div class="card-statistic__wrap">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.total_students')); ?></span>
                        <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-user'); ?>
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
                    <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalStudents); ?></h5>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card-statistic">
                <div class="card-statistic__mask"></div>
                <div class="card-statistic__wrap">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8"><?php echo e(trans('update.active_students')); ?></span>
                        <div class="d-flex-center size-48 bg-success-30 rounded-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-user-tick'); ?>
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
                    <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalActiveStudents); ?></h5>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card-statistic">
                <div class="card-statistic__mask"></div>
                <div class="card-statistic__wrap">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8"><?php echo e(trans('update.expire_students')); ?></span>
                        <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-user-remove'); ?>
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
                    <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalExpireStudents); ?></h5>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card-statistic">
                <div class="card-statistic__mask"></div>
                <div class="card-statistic__wrap">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8"><?php echo e(trans('update.average_learning')); ?></span>
                        <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-percentage-circle'); ?>
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
                    <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($averageLearning); ?></h5>
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
                            <input name="full_name" type="text" class="form-control" value="<?php echo e(request()->get('full_name')); ?>">
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
                                <option value="rate_asc" <?php if(request()->get('sort') == 'rate_asc'): ?> selected <?php endif; ?>><?php echo e(trans('update.rate_ascending')); ?></option>
                                <option value="rate_desc" <?php if(request()->get('sort') == 'rate_desc'): ?> selected <?php endif; ?>><?php echo e(trans('update.rate_descending')); ?></option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label"><?php echo e(trans('admin/main.users_group')); ?></label>
                            <select name="group_id" data-plugin-selectTwo class="form-control populate">
                                <option value=""><?php echo e(trans('admin/main.select_users_group')); ?></option>
                                <?php $__currentLoopData = $userGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($userGroup->id); ?>" <?php if(request()->get('group_id') == $userGroup->id): ?> selected <?php endif; ?>><?php echo e($userGroup->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label"><?php echo e(trans('admin/main.role')); ?></label>
                            <select name="role_id" class="form-control">
                                <option value=""><?php echo e(trans('admin/main.all_roles')); ?></option>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>" <?php if($role->id == request()->get('role_id')): ?> selected <?php endif; ?>><?php echo e($role->caption); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="input-label"><?php echo e(trans('admin/main.status')); ?></label>
                            <select name="status" data-plugin-selectTwo class="form-control populate">
                                <option value=""><?php echo e(trans('admin/main.all_status')); ?></option>
                                <option value="active" <?php if(request()->get('status') == 'active'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.active')); ?></option>
                                <option value="expire" <?php if(request()->get('status') == 'expire'): ?> selected <?php endif; ?>><?php echo e(trans('panel.expired')); ?></option>
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

    <div class="card">

                        <div class="card-header justify-content-between">

                            <div>
                               <h5 class="font-14 mb-0"><?php echo e($pageTitle); ?></h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_items_in_a_single_place')); ?></p>
                           </div>

                            <div class="d-flex align-items-center gap-12">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinar_notification_to_students')): ?>
                            <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/<?php echo e($webinar->id); ?>/sendNotification" target="_blank" class="btn btn-primary">
                                       <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-notification'); ?>
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
                                       <span class="ml-4 font-12"><?php echo e(trans('notification.send_notification')); ?></span>
                                   </a>
                               <?php endif; ?>

                               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_enrollment_add_student_to_items')): ?>
                                   <a type="button" id="addStudentToCourse" href="<?php echo e(getAdminPanelUrl()); ?>/users/create"  class="btn btn-primary">
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
                                       <span class="ml-4 font-12"><?php echo e(trans('update.add_student_to_course')); ?></span>
                                   </a>
                               <?php endif; ?>

                            </div>

                       </div>

        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table custom-table font-14">
                    <tr>
                        <th class="text-left">ID</th>
                        <th class="text-left"><?php echo e(trans('admin/main.name')); ?></th>
                        <th><?php echo e(trans('admin/main.rate')); ?>(5)</th>
                        <th><?php echo e(trans('update.learning')); ?></th>
                        <th><?php echo e(trans('admin/main.user_group')); ?></th>
                        <th><?php echo e(trans('panel.purchase_date')); ?></th>
                        <th><?php echo e(trans('admin/main.status')); ?></th>
                        <th width="120"><?php echo e(trans('admin/main.actions')); ?></th>
                    </tr>

                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr>
                            <td class="text-left"><?php echo e($student->id ?? '-'); ?></td>
                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <figure class="avatar mr-2">
                                        <img src="<?php echo e($student->getAvatar()); ?>" alt="<?php echo e($student->full_name); ?>">
                                    </figure>
                                    <div class="media-body ml-1">
                                        <div class="mt-0 mb-1 font-weight-bold"><?php echo e($student->full_name); ?></div>

                                        <?php if($student->mobile): ?>
                                            <div class="text-primary text-small font-600-bold"><?php echo e($student->mobile); ?></div>
                                        <?php endif; ?>

                                        <?php if($student->email): ?>
                                            <div class="text-primary text-small font-600-bold"><?php echo e($student->email); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span><?php echo e($student->rates ?? '-'); ?></span>
                            </td>

                            <td>
                                <span><?php echo e($student->learning); ?>%</span>
                            </td>

                            <td>
                                <?php if(!empty($student->getUserGroup())): ?>
                                    <span><?php echo e($student->getUserGroup()->name); ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td><?php echo e(dateTimeFormat($student->purchase_date, 'j M Y | H:i')); ?></td>

                            <td>
                                <?php if(empty($student->id)): ?>
                                    
                                    <div class="mt-0 mb-1 font-weight-bold text-warning"><?php echo e(trans('update.unregistered')); ?></div>
                                <?php elseif(!empty($webinar->access_days) and !$webinar->checkHasExpiredAccessDays($student->purchase_date, $student->gift_id)): ?>
                                    <div class="mt-0 mb-1 font-weight-bold text-warning"><?php echo e(trans('panel.expired')); ?></div>
                                <?php elseif(!$student->access_to_purchased_item): ?>
                                    <div class="mt-0 mb-1 font-weight-bold text-danger"><?php echo e(trans('update.access_blocked')); ?></div>
                                <?php else: ?>
                                    <div class="mt-0 mb-1 font-weight-bold text-success"><?php echo e(trans('admin/main.active')); ?></div>
                                <?php endif; ?>
                            </td>

                             <td class="text-center mb-2" width="120">
                                <?php if(!empty($student->id)): ?>
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
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_users_impersonate')): ?>
                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/users/<?php echo e($student->id); ?>/impersonate" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-user-square'); ?>
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
                                                <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.login')); ?></span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_users_edit')): ?>
                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/users/<?php echo e($student->id); ?>/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_webinar_students_delete')): ?>
                                            <?php if(!$student->access_to_purchased_item): ?>
                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/enrollments/'. $student->sale_id .'/enable-access',
                                                    'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                    'btnText' => trans('update.enable-student-access'),
                                                    'btnIcon' => 'tick-square',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-success mr-2',
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php else: ?>
                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/enrollments/'. $student->sale_id .'/block-access',
                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans('update.block_access'),
                                                    'btnIcon' => 'lock',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-danger mr-2',
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
        </div>

        <div class="card-footer text-center">
            <?php echo e($students->appends(request()->input())->links()); ?>

        </div>

    </div>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5><?php echo e(trans('admin/main.hints')); ?></h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold"><?php echo e(trans('admin/main.students_hint_title_1')); ?></div>
                        <div class=" text-small font-600-bold"><?php echo e(trans('admin/main.students_hint_description_1')); ?></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold"><?php echo e(trans('admin/main.students_hint_title_2')); ?></div>
                        <div class=" text-small font-600-bold"><?php echo e(trans('admin/main.students_hint_description_2')); ?></div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold"><?php echo e(trans('admin/main.students_hint_title_3')); ?></div>
                        <div class="text-small font-600-bold"><?php echo e(trans('admin/main.students_hint_description_3')); ?></div>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <div id="addStudentToCourseModal" class="d-none">
        <h3 class="section-title after-line"><?php echo e(trans('update.add_student_to_course')); ?></h3>
        <div class="mt-25">
            <form action="<?php echo e(getAdminPanelUrl()); ?>/enrollments/store" method="post">
                <input type="hidden" name="webinar_id" value="<?php echo e($webinar->id); ?>">

                <div class="form-group">
                    <label class="input-label d-block"><?php echo e(trans('admin/main.user')); ?></label>
                    <select name="user_id" class="form-control user-search" data-placeholder="<?php echo e(trans('public.search_user')); ?>">

                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="d-flex align-items-center justify-content-end mt-3">
                    <button type="button" class="js-save-manual-add btn btn-sm btn-primary"><?php echo e(trans('public.save')); ?></button>
                    <button type="button" class="close-swl btn btn-sm btn-danger ml-2"><?php echo e(trans('public.close')); ?></button>
                </div>
            </form>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>


    <script>
        var saveSuccessLang = '<?php echo e(trans('webinars.success_store')); ?>';
    </script>

    <script src="/assets/admin/js/parts/webinar_students.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/webinars/students.blade.php ENDPATH**/ ?>