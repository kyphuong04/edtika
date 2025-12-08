<div class="tab-pane mt-3 fade <?php echo e((request()->get('tab') == "loginHistory") ? 'active show' : ''); ?>" id="loginHistory" role="tabpanel" aria-labelledby="loginHistory-tab">
    <div class="row">

        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="section-title after-line m-0 mr-12"><?php echo e(trans('update.login_history')); ?></h5>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_user_login_history_end_session')): ?>
                    <?php echo $__env->make('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl("/users/{$user->id}/end-all-login-sessions"),
                        'noBtnTransparent' => true,
                        'btnText' => trans('update.end_all_sessions'),
                        'btnClass' => "btn btn-primary text-white"
                       ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            </div>

            <div class="table-responsive mt-1">
                <table class="table custom-table font-14">
                    <tr>
                        <th><?php echo e(trans('update.os')); ?></th>
                        <th><?php echo e(trans('update.browser')); ?></th>
                        <th><?php echo e(trans('update.device')); ?></th>
                        <th><?php echo e(trans('update.ip_address')); ?></th>
                        <th><?php echo e(trans('update.country')); ?></th>
                        <th><?php echo e(trans('update.city')); ?></th>
                        <th><?php echo e(trans('update.lat,long')); ?></th>
                        <th><?php echo e(trans('update.session_start')); ?></th>
                        <th><?php echo e(trans('update.session_end')); ?></th>
                        <th><?php echo e(trans('public.duration')); ?></th>
                        <th width="120"><?php echo e(trans('admin/main.actions')); ?></th>
                    </tr>

                    <?php if(!empty($userLoginHistories)): ?>
                        <?php $__currentLoopData = $userLoginHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <td><?php echo e($session->os ?? '-'); ?></td>

                                <td><?php echo e($session->browser ?? '-'); ?></td>

                                <td><?php echo e($session->device ?? '-'); ?></td>

                                <td><?php echo e($session->ip ?? '-'); ?></td>

                                <td><?php echo e($session->country ?? '-'); ?></td>

                                <td><?php echo e($session->city ?? '-'); ?></td>

                                <td><?php echo e($session->location ?? '-'); ?></td>

                                <td><?php echo e(dateTimeFormat($session->session_start_at, 'j M Y H:i')); ?></td>

                                <td><?php echo e(!empty($session->session_end_at) ? dateTimeFormat($session->session_end_at, 'j M Y H:i') : '-'); ?></td>

                                <td><?php echo e($session->getDuration()); ?></td>

                                <td class="text-center">
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
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_user_login_history_end_session')): ?>
                                      <?php if(empty($session->session_end_at)): ?>
                                          <?php echo $__env->make('admin.includes.delete_button',[
                                              'url' => getAdminPanelUrl().'/users/login-history/'.$session->id.'/end-session',
                                              'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                              'btnText' => trans('update.end_session'),
                                              'btnIcon' => 'logout',
                                              'iconType' => 'lin',
                                              'iconClass' => 'text-danger mr-2'
                                          ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                      <?php endif; ?>
                                  <?php endif; ?>

                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_user_login_history_delete')): ?>
                                      <?php echo $__env->make('admin.includes.delete_button',[
                                          'url' => getAdminPanelUrl().'/users/login-history/'.$session->id.'/delete',
                                          'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                          'btnText' => trans('admin/main.delete'),
                                          'btnIcon' => 'trash',
                                          'iconType' => 'lin',
                                          'iconClass' => 'text-danger mr-2'
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

            <?php if(!empty($userLoginHistories)): ?>
                <div class="card-footer text-center">
                    <?php echo e($userLoginHistories->appends(request()->input())->links()); ?>

                </div>
            <?php endif; ?>


        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/admin/users/editTabs/login_history.blade.php ENDPATH**/ ?>