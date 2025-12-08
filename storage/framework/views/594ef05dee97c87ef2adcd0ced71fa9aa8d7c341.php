

<?php $__env->startSection('content'); ?>
    <section>
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title"><?php echo e(trans('panel.assignments')); ?> - <?php echo e($student->full_name); ?></h2>
            <a href="/panel/students-tracking/<?php echo e($student->id); ?>/details" class="btn btn-sm btn-primary">
                <i class="fa fa-arrow-left mr-2"></i><?php echo e(trans('panel.back')); ?>

            </a>
        </div>

        <?php if($assignmentHistories->count() > 0): ?>
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="table-responsive">
                    <table class="table custom-table text-center">
                        <thead>
                            <tr>
                                <th class="text-left"><?php echo e(trans('panel.assignment')); ?></th>
                                <th><?php echo e(trans('panel.course')); ?></th>
                                <th><?php echo e(trans('panel.deadline')); ?></th>
                                <th><?php echo e(trans('panel.grade')); ?></th>
                                <th><?php echo e(trans('panel.status')); ?></th>
                                <th><?php echo e(trans('panel.submission_date')); ?></th>
                                <th><?php echo e(trans('public.action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $assignmentHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-left"><?php echo e($history->assignment->title); ?></td>
                                    <td><?php echo e($history->assignment->webinar->title ?? 'N/A'); ?></td>
                                    <td><?php echo e(dateTimeFormat($history->assignment->deadline, 'j M Y')); ?></td>
                                    <td>
                                        <?php if($history->grade !== null): ?>
                                            <span class="font-weight-bold"><?php echo e($history->grade); ?></span>
                                            <span class="text-gray">/ <?php echo e($history->assignment->total_mark ?? 100); ?></span>
                                        <?php else: ?>
                                            <span class="text-gray"><?php echo e(trans('panel.not_graded')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($history->status == 'passed'): ?>
                                            <span class="badge badge-success"><?php echo e(trans('panel.passed')); ?></span>
                                        <?php elseif($history->status == 'pending'): ?>
                                            <span class="badge badge-warning"><?php echo e(trans('panel.pending')); ?></span>
                                        <?php elseif($history->status == 'not_passed'): ?>
                                            <span class="badge badge-danger"><?php echo e(trans('panel.not_passed')); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><?php echo e($history->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(dateTimeFormat($history->created_at, 'j M Y H:i')); ?></td>
                                    <td>
                                        <a href="/panel/assignments/<?php echo e($history->assignment_id); ?>/students" 
                                           class="btn btn-sm btn-primary">
                                            <?php echo e(trans('panel.view')); ?>

                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="mt-30">
                    <?php echo e($assignmentHistories->links()); ?>

                </div>
            </div>
        <?php else: ?>
            <?php echo $__env->make('design_1.panel.includes.no-result',[
                'file_name' => 'assignment.svg',
                'title' => trans('panel.no_assignments_found'),
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/students_tracking/assignments.blade.php ENDPATH**/ ?>