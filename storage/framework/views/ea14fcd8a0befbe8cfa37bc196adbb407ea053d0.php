<div class="tab-pane mt-0 fade" id="purchased_products" role="tabpanel" aria-labelledby="purchased_products-tab">
    <div class="row">

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_enrollment_add_student_to_items')): ?>
            <div class="col-12 col-md-6">
                <h5 class="section-title after-line"><?php echo e(trans('update.add_student_to_product')); ?></h5>

                <form action="<?php echo e(getAdminPanelUrl()); ?>/enrollments/store" method="Post">

                    <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">

                    <div class="form-group">
                        <label class="input-label"><?php echo e(trans('update.product')); ?></label>
                        <select name="product_id" class="form-control search-product-select2"
                                data-placeholder="<?php echo e(trans('update.search_product')); ?>">

                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class=" mt-4">
                        <button type="button" class="js-save-manual-add btn btn-primary"><?php echo e(trans('admin/main.submit')); ?></button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <div class="col-12">
            <div class="mt-5">
                <h5 class="section-title after-line"><?php echo e(trans('update.manual_added_products')); ?></h5>

                <div class="table-responsive mt-3">
                    <table class="table custom-table table-md">
                        <tr>
                            <th><?php echo e(trans('update.product')); ?></th>
                            <th><?php echo e(trans('admin/main.type')); ?></th>
                            <th><?php echo e(trans('admin/main.price')); ?></th>
                            <th><?php echo e(trans('update.seller')); ?></th>
                            <th class="text-center"><?php echo e(trans('update.added_date')); ?></th>
                            <th class="text-right"><?php echo e(trans('admin/main.actions')); ?></th>
                        </tr>

                        <?php if(!empty($manualAddedProducts)): ?>
                            <?php $__currentLoopData = $manualAddedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manualAddedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <td width="25%">
                                        <a href="<?php echo e(!empty($manualAddedProduct->productOrder->product) ? $manualAddedProduct->productOrder->product->getUrl() : '#!'); ?>" target="_blank" class=""><?php echo e(!empty($manualAddedProduct->productOrder->product) ? $manualAddedProduct->productOrder->product->title : trans('update.deleted_item')); ?></a>
                                    </td>

                                    <td>
                                        <?php if(!empty($manualAddedProduct->productOrder->product)): ?>
                                            <?php echo e(trans('update.product_type_'.$manualAddedProduct->productOrder->product->type)); ?>

                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if(!empty($manualAddedProduct->productOrder->product)): ?>
                                            <?php echo e(!empty($manualAddedProduct->productOrder->product->price) ? handlePrice($manualAddedProduct->productOrder->product->price) : '-'); ?>

                                        <?php else: ?>
                                            <?php echo e(!empty($manualAddedProduct->amount) ? handlePrice($manualAddedProduct->amount) : '-'); ?>

                                        <?php endif; ?>
                                    </td>

                                    <td width="25%">
                                        <?php if(!empty($manualAddedProduct->productOrder->product)): ?>
                                            <p><?php echo e($manualAddedProduct->productOrder->product->creator->full_name); ?></p>
                                        <?php else: ?>
                                            <p><?php echo e(!empty($manualAddedProduct->seller) ? $manualAddedProduct->seller->full_name : trans('update.deleted_seller')); ?></p>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center"><?php echo e(dateTimeFormat($manualAddedProduct->created_at,'j M Y | H:i')); ?></td>

                                    <td class="text-right">
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
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_enrollment_block_access')): ?>
                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/enrollments/'. $manualAddedProduct->id .'/block-access',
                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans('update.block_access'),
                                                    'btnIcon' => 'close-circle',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-danger mr-2',
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </table>
                    <p class="font-12 text-gray-500 mt-1 mb-0"><?php echo e(trans('update.manual_add_hint')); ?></p>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="mt-5">
                <h5 class="section-title after-line"><?php echo e(trans('update.manual_disabled_products')); ?></h5>

                <div class="table-responsive mt-3">
                    <table class="table custom-table table-md">
                        <tr>
                            <th><?php echo e(trans('update.product')); ?></th>
                            <th><?php echo e(trans('admin/main.type')); ?></th>
                            <th><?php echo e(trans('admin/main.price')); ?></th>
                            <th><?php echo e(trans('update.seller')); ?></th>
                            <th class="text-right"><?php echo e(trans('admin/main.actions')); ?></th>
                        </tr>

                        <?php if(!empty($manualDisabledProducts)): ?>
                            <?php $__currentLoopData = $manualDisabledProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manualDisabledProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <td width="25%">
                                        <a href="<?php echo e(!empty($manualDisabledProduct->productOrder->product) ? $manualDisabledProduct->productOrder->product->getUrl() : '#!'); ?>" target="_blank" class=""><?php echo e(!empty($manualDisabledProduct->productOrder->product) ? $manualDisabledProduct->productOrder->product->title : trans('update.deleted_item')); ?></a>
                                    </td>

                                    <td>
                                        <?php if(!empty($manualDisabledProduct->productOrder->product)): ?>
                                            <?php echo e(trans('update.product_type_'.$manualDisabledProduct->productOrder->product->type)); ?>

                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if(!empty($manualDisabledProduct->productOrder->product)): ?>
                                            <?php echo e(!empty($manualDisabledProduct->productOrder->product->price) ? handlePrice($manualDisabledProduct->productOrder->product->price) : '-'); ?>

                                        <?php else: ?>
                                            <?php echo e(!empty($manualDisabledProduct->amount) ? handlePrice($manualDisabledProduct->amount) : '-'); ?>

                                        <?php endif; ?>
                                    </td>

                                    <td width="25%">
                                        <?php if(!empty($manualDisabledProduct->productOrder->product)): ?>
                                            <p><?php echo e($manualDisabledProduct->productOrder->product->creator->full_name); ?></p>
                                        <?php else: ?>
                                            <p><?php echo e($manualDisabledProduct->seller->full_name); ?></p>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-right">
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
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_enrollment_block_access')): ?>
                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/enrollments/'. $manualDisabledProduct->id .'/enable-access',
                                                    'btnClass' => 'dropdown-item text-success mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans('update.enable-student-access'),
                                                    'btnIcon' => 'tick-circle',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-success mr-2',
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </table>
                    <p class="font-12 text-gray-500 mt-1 mb-0"><?php echo e(trans('update.manual_remove_hint')); ?></p>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="mt-5">
                <h5 class="section-title after-line"><?php echo e(trans('panel.purchased')); ?></h5>

                <div class="table-responsive mt-3">
                    <table class="table custom-table table-md">
                        <tr>
                            <th><?php echo e(trans('update.product')); ?></th>
                            <th><?php echo e(trans('admin/main.type')); ?></th>
                            <th><?php echo e(trans('admin/main.price')); ?></th>
                            <th><?php echo e(trans('update.seller')); ?></th>
                            <th class="text-center"><?php echo e(trans('panel.purchase_date')); ?></th>
                            <th class="right"><?php echo e(trans('admin/main.actions')); ?></th>
                        </tr>

                        <?php if(!empty($purchasedProducts)): ?>
                            <?php $__currentLoopData = $purchasedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchasedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td width="25%">
                                        <a href="<?php echo e(!empty($purchasedProduct->productOrder->product) ? $purchasedProduct->productOrder->product->getUrl() : '#!'); ?>" target="_blank" class=""><?php echo e(!empty($purchasedProduct->productOrder->product) ? $purchasedProduct->productOrder->product->title : trans('update.deleted_item')); ?></a>
                                    </td>
                                    <td>
                                        <?php if(!empty($purchasedProduct->productOrder->product)): ?>
                                            <?php echo e(trans('update.product_type_'.$purchasedProduct->productOrder->product->type)); ?>

                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if(!empty($purchasedProduct->productOrder->product)): ?>
                                            <?php echo e(!empty($purchasedProduct->productOrder->product->price) ? handlePrice($purchasedProduct->productOrder->product->price) : '-'); ?>

                                        <?php else: ?>
                                            <?php echo e(!empty($purchasedProduct->amount) ? handlePrice($purchasedProduct->amount) : '-'); ?>

                                        <?php endif; ?>
                                    </td>

                                    <td width="25%">
                                        <?php if(!empty($purchasedProduct->productOrder->product)): ?>
                                            <p><?php echo e($purchasedProduct->productOrder->product->creator->full_name); ?></p>
                                        <?php else: ?>
                                            <p><?php echo e(!empty($purchasedProduct->seller) ? $purchasedProduct->seller->full_name : trans('update.deleted_seller')); ?></p>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php echo e(dateTimeFormat($purchasedProduct->created_at,'j M Y | H:i')); ?>

                                    </td>

                                    <td class="text-right">
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
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_enrollment_block_access')): ?>
                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/enrollments/'. $purchasedProduct->id .'/block-access',
                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans('update.block_access'),
                                                    'btnIcon' => 'close-circle',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-danger mr-2',
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>


                                
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </table>
                    <p class="font-12 text-gray-500 mt-1 mb-0"><?php echo e(trans('update.purchased_hint')); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/users/editTabs/purchased_products.blade.php ENDPATH**/ ?>