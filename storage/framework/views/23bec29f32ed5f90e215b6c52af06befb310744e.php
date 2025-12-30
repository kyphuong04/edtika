<?php $__env->startPush('libraries_top'); ?>

<?php $__env->stopPush(); ?>

<?php
    $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('admin/main.students')); ?> <?php echo e(trans('admin/main.list')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a><?php echo e(trans('admin/main.students')); ?></a></div>
                <div class="breadcrumb-item"><a href="#"><?php echo e(trans('admin/main.users_list')); ?></a></div>
            </div>
        </div>
    </section>

    <div class="section-body">
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
<?php $component->withName('iconsax-bul-profile-2user'); ?>
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
                            <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.organizations_students')); ?></span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-courthouse'); ?>
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
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalOrganizationsStudents); ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.inactive_students')); ?></span>
                            <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-user-remove'); ?>
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
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($inactiveStudents); ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.ban_students')); ?></span>
                            <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-user-minus'); ?>
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
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($banStudents); ?></h5>
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
                                    <option value="purchased_classes_asc" <?php if(request()->get('sort') == 'purchased_classes_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.purchased_classes_ascending')); ?></option>
                                    <option value="purchased_classes_desc" <?php if(request()->get('sort') == 'purchased_classes_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.purchased_classes_descending')); ?></option>

                                    <option value="purchased_classes_amount_asc" <?php if(request()->get('sort') == 'purchased_classes_amount_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.purchased_classes_amount_ascending')); ?></option>
                                    <option value="purchased_classes_amount_desc" <?php if(request()->get('sort') == 'purchased_classes_amount_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.purchased_classes_amount_descending')); ?></option>


                                    <option value="purchased_appointments_asc" <?php if(request()->get('sort') == 'purchased_appointments_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.purchased_appointments_ascending')); ?></option>
                                    <option value="purchased_appointments_desc" <?php if(request()->get('sort') == 'purchased_appointments_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.purchased_appointments_descending')); ?></option>

                                    <option value="purchased_appointments_amount_asc" <?php if(request()->get('sort') == 'purchased_appointments_amount_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.purchased_appointments_amount_ascending')); ?></option>
                                    <option value="purchased_appointments_amount_desc" <?php if(request()->get('sort') == 'purchased_appointments_amount_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.purchased_appointments_amount_descending')); ?></option>

                                    <option value="register_asc" <?php if(request()->get('sort') == 'register_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.register_date_ascending')); ?></option>
                                    <option value="register_desc" <?php if(request()->get('sort') == 'register_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.register_date_descending')); ?></option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.organization')); ?></label>
                                <select name="organization_id" data-plugin-selectTwo class="form-control populate">
                                    <option value=""><?php echo e(trans('admin/main.select_organization')); ?></option>
                                    <?php $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($organization->id); ?>" <?php if(request()->get('organization_id') == $organization->id): ?> selected <?php endif; ?>><?php echo e($organization->full_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <label class="input-label"><?php echo e(trans('admin/main.status')); ?></label>
                                <select name="status" data-plugin-selectTwo class="form-control populate">
                                    <option value=""><?php echo e(trans('admin/main.all_status')); ?></option>
                                    <option value="active_verified" <?php if(request()->get('status') == 'active_verified'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.active_verified')); ?></option>
                                    <option value="active_notVerified" <?php if(request()->get('status') == 'active_notVerified'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.active_not_verified')); ?></option>
                                    <option value="inactive" <?php if(request()->get('status') == 'inactive'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.inactive')); ?></option>
                                    <option value="ban" <?php if(request()->get('status') == 'ban'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.banned')); ?></option>
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
    </div>

    <div class="card">


    <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0"><?php echo e($pageTitle); ?></h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_students_in_a_single_place')); ?></p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_users_export_excel')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl()); ?>/students/excel?<?php echo e(http_build_query(request()->all())); ?>" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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

                               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_quizzes_create')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl()); ?>/users/create" target="_blank" class="btn btn-primary">
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
                                       <span class="ml-4 font-12"><?php echo e(trans('admin/main.new')); ?> <?php echo e(trans('admin/main.user')); ?></span>
                                   </a>
                               <?php endif; ?>

                            </div>
                           
                       </div>



        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table custom-table font-14">
                    <tr>
                        <th>ID</th>
                        <th class="text-left"><?php echo e(trans('admin/main.name')); ?></th>
                        <th><?php echo e(trans('admin/main.classes')); ?></th>
                        <th><?php echo e(trans('admin/main.appointments')); ?></th>
                        <th><?php echo e(trans('admin/main.wallet_charge')); ?></th>
                        <th><?php echo e(trans('admin/main.income')); ?></th>
                        <th><?php echo e(trans('admin/main.user_group')); ?></th>
                        <th><?php echo e(trans('admin/main.register_date')); ?></th>
                        <th><?php echo e(trans('admin/main.status')); ?></th>
                        <th width="120"><?php echo e(trans('admin/main.actions')); ?></th>
                    </tr>

                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr>
                            <td>
                                <a href="<?php echo e($user->getProfileUrl()); ?>" class="text-dark" target="_blank"><?php echo e($user->id); ?></a>
                            </td>

                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <figure class="avatar mr-2">
                                        <img src="<?php echo e($user->getAvatar()); ?>" alt="<?php echo e($user->full_name); ?>">
                                    </figure>
                                    <div class="media-body ml-1">
                                        <div class="mt-0 mb-1"><?php echo e($user->full_name); ?></div>

                                        <?php if($registerMethod == 'mobile'): ?>
                                        <?php if($user->mobile): ?>
                                            <div class="text-small font-12 text-gray-500"><?php echo e($user->mobile); ?></div>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        <?php if($user->email): ?>
                                            <div class="text-small font-12 text-gray-500"><?php echo e($user->email); ?></div>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="media-body">
                                    <div class="text-dark mt-0 mb-1 "><?php echo e($user->classesPurchasedsCount); ?></div>
                                    <div class="text-small font-12 text-gray-500"><?php echo e(handlePrice($user->classesPurchasedsSum)); ?></div>
                                </div>
                            </td>

                            <td>
                                <div class="media-body">
                                    <div class="text-dark mt-0 mb-1"><?php echo e($user->meetingsPurchasedsCount); ?></div>
                                    <div class="text-small font-12 text-gray-500"><?php echo e(handlePrice($user->meetingsPurchasedsSum)); ?></div>
                                </div>
                            </td>

                            <td><?php echo e(handlePrice($user->getAccountingBalance())); ?></td>

                            <td><?php echo e(handlePrice($user->getIncome())); ?></td>

                            <td>
                                <?php echo e(!empty($user->userGroup) ? $user->userGroup->group->name : ''); ?>

                            </td>

                            <td><?php echo e(dateTimeFormat($user->created_at, 'j M Y | H:i')); ?></td>

                            <td>
                                <?php if($user->ban and !empty($user->ban_end_at) and $user->ban_end_at > time()): ?>
                                    <span class="badge-status text-danger bg-danger-30"><?php echo e(trans('admin/main.ban')); ?></span>
                                    <div class="text-small font-12 text-gray-500">Until <?php echo e(dateTimeFormat($user->ban_end_at, 'Y/m/j')); ?></div>
                                <?php else: ?>
                                    <span class="badge-status <?php echo e(($user->status == 'active') ? 'text-success bg-success-30' : 'text-warning bg-warning-30'); ?>"><?php echo e(trans('admin/main.'.$user->status)); ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center" width="120">
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
                <a href="<?php echo e(getAdminPanelUrl()); ?>/users/<?php echo e($user->id); ?>/impersonate" 
                   target="_blank"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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
                <a href="<?php echo e(getAdminPanelUrl()); ?>/users/<?php echo e($user->id); ?>/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_users_delete')): ?>
                <?php echo $__env->make('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/users/'.$user->id.'/delete',
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans('admin/main.delete'),
                    'btnIcon' => 'trash',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2',
                    'deleteConfirmMsg' => trans('update.user_delete_confirm_msg')
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
            <?php echo e($users->appends(request()->input())->links()); ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/users/students.blade.php ENDPATH**/ ?>