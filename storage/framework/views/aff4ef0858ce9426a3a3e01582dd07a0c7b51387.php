<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e($pageTitle); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('admin/main.payouts')); ?></div>
            </div>
        </div>


        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <input type="hidden" name="payout" value="<?php echo e(request()->get('payout')); ?>">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.search')); ?></label>
                                    <input type="text" class="form-control text-center" name="search" value="<?php echo e(request()->get('search')); ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.start_date')); ?></label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="<?php echo e(request()->get('from')); ?>" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.end_date')); ?></label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="<?php echo e(request()->get('to')); ?>" placeholder="End Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.role')); ?></label>
                                    <select name="role_id" data-plugin-selectTwo class="form-control populate">
                                        <option value=""><?php echo e(trans('admin/main.all_roles')); ?></option>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->id); ?>" <?php if($role->id == request()->get('role_id')): ?> selected <?php endif; ?>><?php echo e($role->caption); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.user')); ?></label>
                                    <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search teachers">

                                        <?php if(!empty($users) and $users->count() > 0): ?>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user_filter->id); ?>" selected><?php echo e($user_filter->full_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.bank')); ?></label>
                                    <select name="account_type" data-plugin-selectTwo class="form-control populate">
                                        <option value=""><?php echo e(trans('admin/main.all_banks')); ?></option>

                                        <?php $__currentLoopData = $offlineBanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offlineBank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($offlineBank->id); ?>" <?php if(request()->get('account_type') == $offlineBank->id): ?> selected <?php endif; ?>><?php echo e($offlineBank->title); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.filters')); ?></label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">Filter Type</option>
                                        <option value="amount_asc" <?php if(request()->get('sort') == 'amount_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.amount_ascending')); ?></option>
                                        <option value="amount_desc" <?php if(request()->get('sort') == 'amount_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.amount_descending')); ?></option>
                                        <option value="created_at_asc" <?php if(request()->get('sort') == 'created_at_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.last_payout_date_ascending')); ?></option>
                                        <option value="created_at_desc" <?php if(request()->get('sort') == 'created_at_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.last_payout_date_descending')); ?></option>
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
                               <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_payout_in_a_single_place')); ?></p>
                           </div>

                            <div class="d-flex align-items-center gap-12">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_payouts_export_excel')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/payouts/excel?<?php echo e(http_build_query(request()->all())); ?>" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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


                            </div>

                       </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th><?php echo e(trans('admin/main.user')); ?></th>
                                        <th><?php echo e(trans('admin/main.role')); ?></th>
                                        <th><?php echo e(trans('admin/main.payout_amount')); ?></th>
                                        <th class=""><?php echo e(trans('admin/main.bank')); ?></th>

                                        <th><?php echo e(trans('admin/main.phone')); ?></th>
                                        <th width="180px"><?php echo e(trans('admin/main.last_payout_date')); ?></th>

                                        <?php if(request()->get('payout') == 'history'): ?>
                                            <th><?php echo e(trans('admin/main.status')); ?></th>
                                        <?php endif; ?>

                                        <th width="150px"><?php echo e(trans('admin/main.actions')); ?></th>
                                    </tr>

                                    <?php if($payouts->count() > 0): ?>
                                        <?php $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <tr>
                                                <td class="text-left">
                                                    <span class="d-block"><?php echo e($payout->user->full_name); ?></span>
                                                </td>

                                                <td><?php echo e($payout->user->role->caption); ?></td>

                                                <td><?php echo e(handlePrice($payout->amount)); ?></td>


                                                <?php if(!empty($payout->userSelectedBank->bank)): ?>
                                                <td class="">
                                                    <?php
                                                        $bank = $payout->userSelectedBank->bank;
                                                    ?>
                                                    <div class="font-weight-500"><?php echo e($bank->title); ?></div>

                                                    
                                                    <input type="hidden" class="js-bank-details" data-name="<?php echo e(trans("admin/main.bank")); ?>" value="<?php echo e($bank->title); ?>">
                                                    <?php $__currentLoopData = $bank->specifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $selectedBankSpecification = $payout->userSelectedBank->specifications->where('user_selected_bank_id', $payout->userSelectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                                                        ?>

                                                        <?php if(!empty($selectedBankSpecification)): ?>
                                                            <input type="hidden" class="js-bank-details" data-name="<?php echo e($specification->name); ?>" value="<?php echo e($selectedBankSpecification->value); ?>">
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </td>
                                                <?php else: ?>
                                                <td>-</td>
                                                <?php endif; ?>

                                                <td><?php echo e($payout->user->mobile); ?></td>

                                                <td><?php echo e(dateTimeFormat($payout->created_at, 'j M Y H:i')); ?></td>

                                                <?php if(request()->get('payout') == 'history'): ?>
                                                    <td>
                                                        <span class="badge-status <?php echo e(($payout->status == 'done') ? 'text-success bg-success-30' : 'text-danger bg-danger-30'); ?>"><?php echo e(trans('public.'.$payout->status)); ?></span>
                                                    </td>
                                                <?php endif; ?>


                                                <td width="80px">
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
            <button type="button" class="js-show-details dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-eye'); ?>
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
                <span class="text-gray-500 font-14"><?php echo e(trans('update.show_details')); ?></span>
            </button>

            <?php if(request()->get('payout') == 'requests' and $payout->status === \App\Models\Payout::$waiting): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_payouts_payout')): ?>
                    <?php echo $__env->make('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl().'/financial/payouts/'.$payout->id.'/payout',
                        'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                        'btnText' => trans('admin/main.payout'),
                        'btnIcon' => 'card',
                        'iconType' => 'lin',
                        'iconClass' => 'text-success mr-2'
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_payouts_reject')): ?>
                    <?php echo $__env->make('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl().'/financial/payouts/'.$payout->id.'/reject',
                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                        'btnText' => trans('public.reject'),
                        'btnIcon' => 'close-circle',
                        'iconType' => 'lin',
                        'iconClass' => 'text-danger mr-2'
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</td>

                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <?php echo e($payouts->appends(request()->input())->links()); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5><?php echo e(trans('admin/main.hints')); ?></h5></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold"><?php echo e(trans('admin/main.payout_list_hint_title_1')); ?></div>
                        <div class=" text-small font-600-bold"><?php echo e(trans('admin/main.payout_list_hint_description_1')); ?></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold"><?php echo e(trans('admin/main.payout_list_hint_title_2')); ?></div>
                        <div class=" text-small font-600-bold"><?php echo e(trans('admin/main.payout_list_hint_description_2')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var payoutDetailsLang = '<?php echo e(trans('update.payout_details')); ?>';
        var closeLang = '<?php echo e(trans('public.close')); ?>';
    </script>

    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/admin/js/parts/payout.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/financial/payout/lists.blade.php ENDPATH**/ ?>