<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('admin/main.balances')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('admin/main.balances')); ?></div>
            </div>
        </div>
        <div class="section-filters">
            <section class="card">
                <div class="card-body">
                    <div class="mt-3">
                        <form action="<?php echo e(getAdminPanelUrl()); ?>/financial/documents" method="get" class="row mb-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.start_date')); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i class="fa fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                        <input type="text" name="from" autocomplete="off" class="form-control datefilter"
                                               aria-describedby="dateInputGroupPrepend"
                                               value="<?php echo e(request()->get('from',null)); ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label"><?php echo e(trans('admin/main.end_date')); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i class="fa fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                        <input type="text" name="to" autocomplete="off" class="form-control datefilter"
                                               aria-describedby="dateInputGroupPrepend"
                                               value="<?php echo e(request()->get('to',null)); ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label
                                        class="input-label d-block"><?php echo e(trans('/admin/main.user')); ?></label>
                                    <select name="user[]" multiple="" class="form-control search-user-select2"
                                            data-placeholder="<?php echo e(trans('/admin/main.search_user_or_instructor')); ?>">
                                        <?php if( request()->get('user',null)): ?>
                                            <?php $__currentLoopData = request()->get('user'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($userId); ?>"
                                                        selected="selected"><?php echo e($users[$userId]->full_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label d-block"><?php echo e(trans('admin/main.class')); ?></label>
                                    <select name="webinar" class="form-control search-webinar-select2"
                                            data-placeholder="<?php echo e(trans('admin/main.search_webinar')); ?>">
                                        <?php if(request()->get('webinar',null)): ?>
                                            <option value="<?php echo e(request()->get('webinar',null)); ?>"
                                                    selected="selected"><?php echo e($webinar ? $webinar->title : ''); ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label d-block"><?php echo e(trans('admin/main.type')); ?></label>
                                    <select name="type" class="form-control">
                                        <option value="all"
                                                <?php if(request()->get('type',null) == 'all'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('public.all')); ?></option>
                                        <option value="addiction"
                                                <?php if(request()->get('type',null) == 'addiction'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('admin/main.addiction')); ?></option>
                                        <option value="deduction"
                                                <?php if(request()->get('type',null) == 'deduction'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('admin/main.deduction')); ?></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label d-block"><?php echo e(trans('admin/main.type_account')); ?></label>
                                    <select name="type_account" class="form-control">
                                        <option value="all"
                                                <?php if(request()->get('type_account',null) == 'all'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('public.all')); ?></option>
                                        <option value="asset"
                                                <?php if(request()->get('type_account',null) == 'asset'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('admin/main.asset')); ?></option>
                                        <option value="income"
                                                <?php if(request()->get('type_account',null) == 'income'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('admin/main.income')); ?></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3 d-flex align-items-center justify-content-end">
                                <button type="submit" class="btn btn-primary w-100"><?php echo e(trans('admin/main.show_results')); ?></button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <div class="section-body">


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0"><?php echo e(trans('admin/main.balances')); ?></h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_transactions_in_a_single_place')); ?></p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                                   <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/documents/excel?{{ http_build_query(request()->all())" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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

                                   <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_documents_create')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/documents/new" target="_blank" class="btn btn-primary">
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
                                       <span class="ml-4 font-12"><?php echo e(trans('admin/main.add_new')); ?></span>
                                   </a>
                                    <?php endif; ?>

                            </div>
                           
                       </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th class="text-left"><?php echo e(trans('admin/main.title')); ?></th>
                                        <th class="text-left"><?php echo e(trans('admin/main.user')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.tax')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.system')); ?></th>
                                        <th><?php echo e(trans('admin/main.amount')); ?></th>
                                        <th><?php echo e(trans('admin/main.type')); ?></th>
                                        <th><?php echo e(trans('admin/main.creator')); ?></th>
                                        <th><?php echo e(trans('admin/main.type_account')); ?></th>
                                        <th><?php echo e(trans('public.date_time')); ?></th>
                                        <th><?php echo e(trans('public.controls')); ?></th>
                                    </tr>


                                    <?php if($documents->count() > 0): ?>
                                        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-left">
                                                    <div class="text-left">
                                                        <?php if($document->is_cashback): ?>
                                                            <span class="d-block"><?php echo e(trans('update.cashback')); ?></span>
                                                        <?php endif; ?>

                                                        <?php if(!empty($document->webinar_id)): ?>
                                                            <?php if(!$document->is_cashback): ?>
                                                                <span class="d-block "><?php echo e(trans('admin/main.item_purchased')); ?></span>
                                                            <?php endif; ?>

                                                            <a href="<?php echo e(!empty($document->webinar) ? $document->webinar->getUrl() : ''); ?>"
                                                               target="_blank" class="font-12 text-gray-500">#<?php echo e($document->webinar_id); ?>-<?php echo e(!empty($document->webinar) ? $document->webinar->title : ''); ?></a>
                                                        <?php elseif(!empty($document->bundle_id)): ?>
                                                            <?php if(!$document->is_cashback): ?>
                                                                <span class="d-block "><?php echo e(trans('update.bundle_purchased')); ?></span>
                                                            <?php endif; ?>

                                                            <a href="<?php echo e(!empty($document->bundle) ? $document->bundle->getUrl() : ''); ?>"
                                                               target="_blank" class="font-12 text-gray-500">#<?php echo e($document->bundle_id); ?>-<?php echo e(!empty($document->bundle) ? $document->bundle->title : ''); ?></a>
                                                        <?php elseif(!empty($document->product_id)): ?>
                                                            <?php if(!$document->is_cashback): ?>
                                                                <span class="d-block "><?php echo e(trans('update.product_purchased')); ?></span>
                                                            <?php endif; ?>

                                                            <a href="<?php echo e(!empty($document->product) ? $document->product->getUrl() : ''); ?>"
                                                               target="_blank" class="font-12 text-gray-500">#<?php echo e($document->product_id); ?>-<?php echo e(!empty($document->product) ? $document->product->title : ''); ?></a>
                                                        <?php elseif(!empty($document->meeting_time_id)): ?>
                                                            <?php if(!$document->is_cashback): ?>
                                                                <span class="d-block "><?php echo e(trans('admin/main.item_purchased')); ?></span>
                                                            <?php endif; ?>

                                                            <a href="" target="_blank" class="font-12 text-gray-500">#<?php echo e($document->meeting_time_id); ?> <?php echo e(trans('admin/main.meeting')); ?></a>
                                                        <?php elseif(!empty($document->subscribe_id)): ?>
                                                            <span class="<?php echo e((!$document->is_cashback) ? 'd-block ' : 'font-12 text-gray-500'); ?>"><?php echo e(trans('admin/main.purchased_subscribe')); ?></span>
                                                        <?php elseif(!empty($document->promotion_id)): ?>
                                                            <span class="<?php echo e((!$document->is_cashback) ? 'd-block ' : 'font-12 text-gray-500'); ?>"><?php echo e(trans('admin/main.purchased_promotion')); ?></span>
                                                        <?php elseif(!empty($document->registration_package_id)): ?>
                                                            <span class="<?php echo e((!$document->is_cashback) ? 'd-block ' : 'font-12 text-gray-500'); ?>"><?php echo e(trans('update.purchased_registration_package')); ?></span>
                                                        <?php elseif($document->store_type == \App\Models\Accounting::$storeManual): ?>
                                                            <span class="<?php echo e((!$document->is_cashback) ? 'd-block ' : 'font-12 text-gray-500'); ?>"><?php echo e(trans('admin/main.manual_document')); ?></span>
                                                        <?php else: ?>
                                                            <?php if($document->is_cashback): ?>
                                                                <span class="font-12 text-gray-500"><?php echo e($document->description); ?></span>
                                                            <?php else: ?>
                                                                <span class="d-block"><?php echo e(trans('admin/main.automatic_document')); ?></span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>

                                                <td class="text-left">
                                                    <?php if(!empty($document->user)): ?>
                                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/users/<?php echo e($document->user_id); ?>/edit" target="_blank"
                                                           class="text-dark"><?php echo e($document->user->full_name); ?></a>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php if($document->tax): ?>
                                                        <span class="fas fa-check"></span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php if($document->system): ?>
                                                        <span class="fas fa-check"></span>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <span><?php echo e(handlePrice($document->amount)); ?></span>
                                                </td>

                                                <td>
                                                    <?php switch($document->type):
                                                        case (\App\Models\Accounting::$addiction): ?>
                                                            <span class="badge-status text-success bg-success-30"><?php echo e(trans('admin/main.addiction')); ?></span>
                                                            <?php break; ?>
                                                        <?php case (\App\Models\Accounting::$deduction): ?>
                                                            <span class="badge-status text-danger bg-danger-30"><?php echo e(trans('admin/main.deduction')); ?></span>
                                                            <?php break; ?>
                                                    <?php endswitch; ?>
                                                </td>

                                                <td>
                                                    <?php if($document->creator_id): ?>
                                                        <span><?php echo e(trans('admin/main.admin')); ?></span>
                                                    <?php else: ?>
                                                        <span><?php echo e(trans('admin/main.automatic')); ?></span>
                                                    <?php endif; ?>
                                                </td>

                                                <td width="20%">
                                                    <?php echo e(trans('admin/main.'.$document->type_account)); ?>

                                                </td>

                                                <td><?php echo e(dateTimeFormat($document->created_at, 'j F Y H:i')); ?></td>

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
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_documents_print')): ?>
                <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/documents/<?php echo e($document->id); ?>/print"
                   class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-printer'); ?>
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
                    <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.print')); ?></span>
                </a>
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
                            <?php echo e($documents->appends(request()->input())->links()); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/financial/documents/lists.blade.php ENDPATH**/ ?>