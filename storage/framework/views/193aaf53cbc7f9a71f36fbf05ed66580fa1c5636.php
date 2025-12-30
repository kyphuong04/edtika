<?php $__env->startPush('libraries_top'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e($pageTitle); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e($pageTitle); ?></div>
            </div>
        </div>

        <div class="section-body">
            <?php
                $unreadNotificationsIds = [];
                if(!empty($unreadNotifications) and count($unreadNotifications)) {
                    $unreadNotificationsIds=$unreadNotifications->pluck('id')->toArray();
                }
            ?>

            <div class="card">

                <div class="card-header justify-content-between">
                            <div>
                               <h5 class="font-14 mb-0"><?php echo e($pageTitle); ?></h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_items_in_a_single_place')); ?></p>
                           </div>

                            <div class="d-flex align-items-center gap-12">

                               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_notifications_send')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl("/notifications/send")); ?>" target="_blank" class="btn btn-primary">
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
                                       <span class="ml-4 font-12"><?php echo e(trans('notification.send_notification')); ?></span>
                                   </a>
                               <?php endif; ?>

                            </div>
                       </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left"><?php echo e(trans('admin/main.title')); ?></th>
                                <th class="text-center"><?php echo e(trans('notification.sender')); ?></th>
                                <th class="text-center"><?php echo e(trans('site.message')); ?></th>
                                <th class="text-center"><?php echo e(trans('admin/main.status')); ?></th>
                                <th class="text-center"><?php echo e(trans('admin/main.created_at')); ?></th>
                                <th width="80px"><?php echo e(trans('public.controls')); ?></th>
                            </tr>

                            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($notification->title); ?></td>

                                    <td class="text-center"><?php echo e($notification->sender); ?></td>

                                    <td class="text-center">
                                        <button type="button" data-item-id="<?php echo e($notification->id); ?>" class="js-show-description btn btn-sm btn-outline-primary"><?php echo e(trans('admin/main.show')); ?></button>
                                        <input type="hidden" value="<?php echo e(nl2br($notification->message)); ?>">
                                    </td>

                                    <td class="text-center">
                                        <?php if(in_array($notification->id,$unreadNotificationsIds)): ?>
                                            <span class="badge-status text-danger bg-danger-30"><?php echo e(trans('admin/main.unread')); ?></span>
                                        <?php else: ?>
                                            <span class="badge-status text-success bg-success-30"><?php echo e(trans('admin/main.read')); ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center font-12"><?php echo e(dateTimeFormat($notification->created_at,'j M Y | H:i')); ?></td>

                                    <td class="text-center">

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_notifications_delete')): ?>
                                        <?php echo $__env->make('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/notifications/'. $notification->id.'/delete',
                                                           'btnIcon' => 'trash',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger ml-10',
                                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <?php echo e($notifications->appends(request()->input())->links()); ?>

                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="notificationMessageModal" tabindex="-1" aria-labelledby="notificationMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationMessageLabel"><?php echo e(trans('admin/main.contacts_message')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('admin/main.close')); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/admin/js/parts/notifications.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/notifications/lists.blade.php ENDPATH**/ ?>