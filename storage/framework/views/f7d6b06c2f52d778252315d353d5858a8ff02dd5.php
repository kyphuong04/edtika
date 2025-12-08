<div class="tab-pane mt-3 fade" id="badges" role="tabpanel" aria-labelledby="badges-tab">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="<?php echo e(getAdminPanelUrl()); ?>/users/<?php echo e($user->id .'/badgesUpdate'); ?>" method="Post">
                <?php echo e(csrf_field()); ?>


                <div class="form-group">
                    <select name="badge_id" class="form-control <?php $__errorArgs = ['badge_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value=""><?php echo e(trans('admin/main.select_badge')); ?></option>

                        <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($badge->id); ?>"><?php echo e($badge->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['badge_id'];
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

                <div class=" mt-4">
                    <button class="btn btn-primary"><?php echo e(trans('admin/main.submit')); ?></button>
                </div>
            </form>

        </div>

        <div class="col-12">
            <div class="mt-5">
                <h5><?php echo e(trans('admin/main.custom_badges')); ?></h5>

                <div class="table-responsive mt-3">
                    <table class="table custom-table table-md">
                        <tr>
                            <th><?php echo e(trans('admin/main.title')); ?></th>
                            <th><?php echo e(trans('admin/main.image')); ?></th>
                            <th><?php echo e(trans('admin/main.condition')); ?></th>
                            <th><?php echo e(trans('admin/main.description')); ?></th>
                            <th class="text-center"><?php echo e(trans('admin/main.created_at')); ?></th>
                            <th><?php echo e(trans('admin/main.actions')); ?></th>
                        </tr>

                        <?php if(!empty($user->customBadges)): ?>
                            <?php $__currentLoopData = $user->customBadges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customBadge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    $condition = json_decode($customBadge->badge->condition);
                                ?>

                                <tr>
                                    <td><?php echo e($customBadge->badge->title); ?></td>
                                    <td>
                                        <img src="<?php echo e($customBadge->badge->image); ?>" width="24"/>
                                    </td>
                                    <td><?php echo e($condition->from); ?> to <?php echo e($condition->to); ?></td>
                                    <td width="25%">
                                        <p><?php echo e($customBadge->badge->description); ?></p>
                                    </td>
                                    <td class="text-center"><?php echo e(dateTimeFormat($customBadge->badge->created_at,'j M Y')); ?></td>
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
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_users_edit')): ?>
                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/users/'.$user->id.'/deleteBadge/'.$customBadge->id,
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
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="mt-5">
                <h5><?php echo e(trans('admin/main.auto_badges')); ?></h5>

                <div class="table-responsive mt-3">
                    <table class="table custom-table table-md">
                        <tr>
                            <th><?php echo e(trans('admin/main.title')); ?></th>
                            <th><?php echo e(trans('admin/main.image')); ?></th>
                            <th><?php echo e(trans('admin/main.condition')); ?></th>
                            <th><?php echo e(trans('admin/main.description')); ?></th>
                            <th><?php echo e(trans('admin/main.created_at')); ?></th>
                        </tr>

                        <?php if(!empty($userBadges)): ?>
                            <?php $__currentLoopData = $userBadges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $badgeCondition = json_decode($badge->condition);
                                ?>

                                <tr>
                                    <td><?php echo e($badge->title); ?></td>
                                    <td>
                                        <img src="<?php echo e($badge->image); ?>" width="24"/>
                                    </td>
                                    <td><?php echo e($badgeCondition->from); ?> to <?php echo e($badgeCondition->to); ?></td>
                                    <td width="25%">
                                        <p><?php echo e($badge->description); ?></p>
                                    </td>
                                    <td><?php echo e(dateTimeFormat($badge->created_at,'j M Y')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/users/editTabs/badges.blade.php ENDPATH**/ ?>