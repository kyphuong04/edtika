<div class="tab-pane mt-3 fade" id="support_tickets" role="tabpanel" aria-labelledby="support_tickets-tab">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table">
                           
                                <tr>
                                    <th><?php echo e(trans('admin/main.title')); ?></th>
                                    <th><?php echo e(trans('admin/main.department')); ?></th>
                                    <th><?php echo e(trans('admin/main.status')); ?></th>
                                    <th><?php echo e(trans('admin/main.created_at')); ?></th>
                                    <th><?php echo e(trans('admin/main.updated_at')); ?></th>
                                    <th class="text-right"><?php echo e(trans('admin/main.actions')); ?></th>
                                </tr>
                           
                                <?php $__currentLoopData = $user->supports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $support): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/supports/<?php echo e($support->id); ?>/conversation">
                                                <?php echo e($support->title); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($support->department ? $support->department->title : trans('admin/main.no_department')); ?></td>
                                        <td>
                                            <?php if($support->status == 'close'): ?>
                                                <span class="badge-status-card text-danger bg-danger-30"><?php echo e(trans('admin/main.close')); ?></span>
                                            <?php elseif($support->status == 'replied'): ?>
                                                <span class="badge-status-card text-warning bg-warning-30"><?php echo e(trans('admin/main.pending_reply')); ?></span>
                                            <?php else: ?>
                                                <span class="badge-status-card text-success bg-success-30"><?php echo e(trans('admin/main.replied')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(dateTimeFormat($support->created_at, 'j M Y | H:i')); ?></td>
                                        <td><?php echo e((!empty($support->updated_at)) ? dateTimeFormat($support->updated_at, 'j M Y | H:i') : '-'); ?></td>
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
                                                <a href="<?php echo e(getAdminPanelUrl()); ?>/supports/<?php echo e($support->id); ?>/conversation" 
                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-sms'); ?>
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
                                                    <span class="text-gray-500 font-14"><?php echo e(trans('public.view')); ?></span>
                                                </a>

                                                <?php if($support->status != 'close'): ?>
                                                    <?php echo $__env->make('admin.includes.delete_button',[
                                                        'url' => getAdminPanelUrl().'/supports/'.$support->id.'/close',
                                                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                        'btnText' => trans('admin/main.close'),
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
                      
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\edtika\resources\views/admin/users/editTabs/support_tickets.blade.php ENDPATH**/ ?>