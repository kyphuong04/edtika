<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e($pageTitle); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item active">
                    <?php echo e(trans('update.installment_purchases')); ?>

                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0"><?php echo e($pageTitle); ?></h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_items_in_a_single_place')); ?></p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                                   <a href="<?php echo e(getAdminPanelUrl("/financial/installments/purchases/export")); ?>" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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

                            </div>
                           
                       </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th><?php echo e(trans('admin/main.user')); ?></th>
                                        <th><?php echo e(trans('update.installment_plan')); ?></th> 
                                        <th class="text-center"><?php echo e(trans('update.product')); ?></th>
                                        <th class="text-center"><?php echo e(trans('panel.purchase_date')); ?></th>
                                        <th class="text-center"><?php echo e(trans('financial.total_amount')); ?></th>
                                        <th class="text-center"><?php echo e(trans('update.upfront')); ?></th>
                                        <th class="text-center"><?php echo e(trans('update.installments_count')); ?></th>
                                        <th class="text-center"><?php echo e(trans('update.installments_amount')); ?></th>
                                        <th class="text-center"><?php echo e(trans('update.overdue')); ?></th>
                                        <th class="text-center"><?php echo e(trans('update.overdue_amount')); ?></th>
                                        <th class="text-center"><?php echo e(trans('update.first_unpaid_installment_date')); ?></th>
                                        <th class="text-center"><?php echo e(trans('update.days_left')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.status')); ?></th>
                                        <th><?php echo e(trans('admin/main.actions')); ?></th>
                                    </tr>

                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar mr-2">
                                                        <img src="<?php echo e($order->user->getAvatar()); ?>" alt="<?php echo e($order->user->full_name); ?>">
                                                    </figure>
                                                    <div class="media-body ml-1">
                                                        <div class="mt-0 mb-1"><?php echo e($order->user->full_name); ?></div>

                                                        <?php if($order->user->mobile): ?>
                                                            <div class="text-gray-500 text-small"><?php echo e($order->user->mobile); ?></div>
                                                        <?php endif; ?>

                                                        <?php if($order->user->email): ?>
                                                            <div class="text-gray-500 text-small"><?php echo e($order->user->email); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="">
                                                    <span class="d-block"><?php echo e($order->selectedInstallment->installment->title); ?></span>
                                                    <span class="d-block text-gray-500 font-12 mt-1"><?php echo e(trans('update.target_types_'.$order->selectedInstallment->installment->target_type)); ?></span>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <?php if(!empty($order->webinar_id)): ?>
                                                    <a href="<?php echo e(!empty($order->webinar) ? $order->webinar->getUrl() : ''); ?>"
                                                       target="_blank" class="font-14 text-dark">#<?php echo e($order->webinar_id); ?>-<?php echo e(!empty($order->webinar) ? $order->webinar->title : ''); ?></a>
                                                    <span class="d-block text-gray-500 font-12"><?php echo e(trans('update.target_types_courses')); ?></span>
                                                <?php elseif(!empty($order->bundle_id)): ?>
                                                    <a href="<?php echo e(!empty($order->bundle) ? $order->bundle->getUrl() : ''); ?>"
                                                       target="_blank" class="font-14 text-dark">#<?php echo e($order->bundle_id); ?>-<?php echo e(!empty($order->bundle) ? $order->bundle->title : ''); ?></a>
                                                    <span class="d-block text-gray-500 font-12"><?php echo e(trans('update.target_types_bundles')); ?></span>
                                                <?php elseif(!empty($order->product_id)): ?>
                                                    <a href="<?php echo e(!empty($order->product) ? $order->product->getUrl() : ''); ?>"
                                                       target="_blank" class="font-14 text-dark">#<?php echo e($order->product_id); ?>-<?php echo e(!empty($order->product) ? $order->product->title : ''); ?></a>
                                                    <span class="d-block font-12 text-gray-500"><?php echo e(trans('update.target_types_store_products')); ?></span>
                                                <?php elseif(!empty($order->subscribe_id)): ?>
                                                    <span class="font-14"><?php echo e(trans('admin/main.purchased_subscribe')); ?></span>
                                                    <span class="d-block font-12 text-gray-500"><?php echo e(trans('update.target_types_subscription_packages')); ?></span>
                                                <?php elseif(!empty($order->registration_package_id)): ?>
                                                    <span class="font-14"><?php echo e(trans('update.purchased_registration_package')); ?></span>
                                                    <span class="d-block font-12 text-gray-500"><?php echo e(trans('update.target_types_registration_packages')); ?></span>
                                                <?php else: ?>
                                                    ---
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center"><?php echo e(dateTimeFormat($order->created_at, 'j M Y')); ?></td>

                                            <td class="text-center"><?php echo e(handlePrice($order->getCompletePrice())); ?></td>

                                            <td class="text-center">
                                                <?php if(!empty($order->selectedInstallment->upfront)): ?>
                                                    <?php echo e(($order->selectedInstallment->upfront_type == 'percent') ? $order->selectedInstallment->upfront.'%' : handlePrice($order->selectedInstallment->upfront)); ?>

                                                <?php else: ?>
                                                    --
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center"><?php echo e($order->selectedInstallment->steps_count); ?></td>

                                            <td class="text-center">
                                                <?php
                                                    $stepsFixedAmount = $order->selectedInstallment->steps->where('amount_type', 'fixed_amount')->sum('amount');
                                                    $stepsPercents = $order->selectedInstallment->steps->where('amount_type', 'percent')->sum('amount');
                                                ?>

                                                <span class=""><?php echo e($stepsFixedAmount ? handlePrice($stepsFixedAmount) : ''); ?></span>

                                                <?php if($stepsPercents): ?>
                                                    <span><?php echo e($stepsFixedAmount ? ' + ' : ''); ?><?php echo e($stepsPercents); ?>%</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center"><?php echo e($order->overdue_count); ?></td>

                                            <td class="text-center"><?php echo e(handlePrice($order->overdue_amount)); ?></td>

                                            <td class="text-center"><?php echo e($order->upcoming_date); ?></td>

                                            <td class="text-center"><?php echo e($order->days_left); ?></td>

                                            <td class="text-center">
                                                <?php if($order->status == "pending_verification"): ?>
                                                    <span class="badge-status text-warning bg-warning-30"><?php echo e(trans('update.pending_verification')); ?></span>
                                                <?php elseif($order->status == "open"): ?>
                                                    <span class="badge-status text-primary bg-primary-30"><?php echo e(trans('admin/main.open')); ?></span>
                                                <?php elseif($order->status == "rejected"): ?>
                                                    <span class="badge-status text-danger bg-danger-30"><?php echo e(trans('public.rejected')); ?></span>
                                                <?php elseif($order->status == "canceled"): ?>
                                                    <span class="badge-status text-danger bg-danger-30"><?php echo e(trans('public.canceled')); ?></span>
                                                <?php elseif($order->status == "refunded"): ?>
                                                    <span class="badge-status text-danger bg-danger-30"><?php echo e(trans('update.refunded')); ?></span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_orders')): ?>
                <a href="<?php echo e(getAdminPanelUrl('/financial/installments/orders/'.$order->id.'/details')); ?>" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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
                </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_users_impersonate')): ?>
                <a href="<?php echo e(getAdminPanelUrl()); ?>/users/<?php echo e($order->user_id); ?>/impersonate" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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
                <a href="<?php echo e(getAdminPanelUrl()); ?>/users/<?php echo e($order->user_id); ?>/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_support_send')): ?>
                <a href="<?php echo e(getAdminPanelUrl()); ?>/supports/create?user_id=<?php echo e($order->user_id); ?>" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-message-text-1'); ?>
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

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_orders')): ?>
                <?php echo $__env->make('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl('/financial/installments/orders/'.$order->id.'/cancel'),
                    'btnClass' => 'dropdown-item text-danger d-flex align-items-center mb-3 py-3 px-0 gap-4 font-14',
                    'btnText' => trans('admin/main.cancel'),
                    'btnIcon' => 'close-square',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2'
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php echo $__env->make('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl('/financial/installments/orders/'.$order->id.'/refund'),
                    'btnClass' => 'dropdown-item text-danger d-flex align-items-center mb-0 py-3 px-0 gap-4 font-14',
                    'btnText' => trans('admin/main.refund'),
                    'btnIcon' => 'money-send',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2'
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
                            <?php echo e($orders->appends(request()->input())->links()); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/financial/installments/purchases/index.blade.php ENDPATH**/ ?>