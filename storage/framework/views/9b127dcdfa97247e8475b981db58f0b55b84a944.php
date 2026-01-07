<div class="custom-tabs-content active">
    <div class="bg-white rounded-16 py-16 border-gray-200">
        <h3 class="font-14 font-weight-bold px-16"><?php echo e(trans('update.login_history')); ?></h3>

        <div class="table-responsive-lg mt-16">
            <table class="table panel-table">
                <thead>
                <tr>
                    <th class="text-left"><?php echo e(trans('update.os')); ?></th>
                    <th class="text-center"><?php echo e(trans('update.browser')); ?></th>
                    <th class="text-center"><?php echo e(trans('update.device')); ?></th>
                    <th class="text-center"><?php echo e(trans('update.ip_address')); ?></th>
                    <th class="text-center"><?php echo e(trans('update.country')); ?></th>
                    <th class="text-center"><?php echo e(trans('update.city')); ?></th>
                    <th class="text-center"><?php echo e(trans('update.session_start')); ?></th>
                    <th class="text-center"><?php echo e(trans('update.session_end')); ?></th>
                    <th class="text-center"><?php echo e(trans('public.duration')); ?></th>
                    <th class="text-right"><?php echo e(trans('admin/main.actions')); ?></th>
                </tr>
                </thead>
                <tbody class="">
                <?php if(!empty($userLoginHistories)): ?>
                    <?php $__currentLoopData = $userLoginHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr>
                            <td class="text-left"><?php echo e($session->os ?? '-'); ?></td>

                            <td class="text-center"><?php echo e($session->browser ?? '-'); ?></td>

                            <td class="text-center"><?php echo e($session->device ?? '-'); ?></td>

                            <td class="text-center"><?php echo e($session->ip ?? '-'); ?></td>

                            <td class="text-center"><?php echo e($session->country ?? '-'); ?></td>

                            <td class="text-center"><?php echo e($session->city ?? '-'); ?></td>

                            <td class="text-center"><?php echo e(dateTimeFormat($session->session_start_at, 'j M Y H:i')); ?></td>

                            <td class="text-center"><?php echo e(!empty($session->session_end_at) ? dateTimeFormat($session->session_end_at, 'j M Y H:i') : '-'); ?></td>

                            <td class="text-center"><?php echo e($session->getDuration()); ?></td>

                            <td class="text-right">

                                <?php if(empty($session->session_end_at)): ?>
                                    <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                                        <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        </button>

                                        <div class="actions-dropdown__dropdown-menu dropdown-menu-top-32">
                                            <ul class="my-8">

                                                <li class="actions-dropdown__dropdown-menu-item">
                                                    <a
                                                        href="/panel/users/login-history/<?php echo e($session->id); ?>/end-session"
                                                        data-msg="<?php echo e(trans('update.this_device_will_be_logout_from_your_account')); ?>"
                                                        data-confirm="<?php echo e(trans('update.end_session')); ?>"
                                                        class="delete-action ">
                                                        <?php echo e(trans('update.end_session')); ?>

                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    -
                                <?php endif; ?>

                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/settings/tabs/login_history.blade.php ENDPATH**/ ?>