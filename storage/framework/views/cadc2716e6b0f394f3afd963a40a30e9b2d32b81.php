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
            <section class="card">

                <div class="card-header justify-content-between">
                            <div>
                                <h5 class="font-14 mb-0"><?php echo e($pageTitle); ?></h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_courses_in_a_single_place')); ?></p>
                            </div>

                            <div class="d-flex align-items-center gap-12">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_agora_history_export')): ?>
                                      <div class="text-right">
                                          <a href="<?php echo e(getAdminPanelUrl()); ?>/agora_history/excel" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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
                                    <?php endif; ?>

                            </div>
                        </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table text-center font-14">

                            <tr>
                                <th class="text-left"><?php echo e(trans('admin/main.course')); ?></th>
                                <th class="text-left"><?php echo e(trans('admin/main.session')); ?></th>
                                <th class="text-center"><?php echo e(trans('update.session_duration')); ?></th>
                                <th class="text-center"><?php echo e(trans('admin/main.start_date')); ?></th>
                                <th class="text-center"><?php echo e(trans('admin/main.end_date')); ?></th>
                                <th class="text-center"><?php echo e(trans('update.meeting_duration')); ?></th>
                            </tr>

                            <?php $__currentLoopData = $agoraHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agoraHistory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $meetingDuration = ($agoraHistory->end_at - $agoraHistory->start_at) / 60;
                                ?>

                                <tr>
                                    <td class="text-left"><?php echo e((!empty($agoraHistory->session) and !empty($agoraHistory->session->webinar)) ? $agoraHistory->session->webinar->title : trans('update.deleted_session')); ?></td>
                                    <td class="text-left"><?php echo e(!empty($agoraHistory->session) ? $agoraHistory->session->title : trans('update.deleted_session')); ?></td>
                                    <td><?php echo e(!empty($agoraHistory->session) ? convertMinutesToHourAndMinute($agoraHistory->session->duration) : '-'); ?></td>
                                    <td><?php echo e(dateTimeFormat($agoraHistory->start_at, 'j M Y | H:i')); ?></td>
                                    <td><?php echo e(dateTimeFormat($agoraHistory->end_at, 'j M Y | H:i')); ?></td>
                                    <td class="<?php echo e(!empty($agoraHistory->session) ? (($meetingDuration > $agoraHistory->session->duration) ? 'text-danger' : 'text-success') : ''); ?>">
                                        <?php echo e(convertMinutesToHourAndMinute($meetingDuration)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <?php echo e($agoraHistories->links()); ?>

                </div>
            </section>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/agora_history/index.blade.php ENDPATH**/ ?>