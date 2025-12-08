

<?php $__env->startSection('content'); ?>
    <section>
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title"><?php echo e(trans('panel.quiz_results')); ?> - <?php echo e($student->full_name); ?></h2>
            <a href="/panel/students-tracking/<?php echo e($student->id); ?>/details" class="btn btn-sm btn-primary">
                <i class="fa fa-arrow-left mr-2"></i><?php echo e(trans('panel.back')); ?>

            </a>
        </div>

        <?php if($quizResults->count() > 0): ?>
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="table-responsive">
                    <table class="table custom-table text-center">
                        <thead>
                            <tr>
                                <th class="text-left"><?php echo e(trans('panel.quiz')); ?></th>
                                <th><?php echo e(trans('panel.course')); ?></th>
                                <th><?php echo e(trans('panel.grade')); ?></th>
                                <th><?php echo e(trans('panel.pass_mark')); ?></th>
                                <th><?php echo e(trans('panel.status')); ?></th>
                                <th><?php echo e(trans('panel.date')); ?></th>
                                <th><?php echo e(trans('public.action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $quizResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-left"><?php echo e($result->quiz->title); ?></td>
                                    <td><?php echo e($result->quiz->webinar->title ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="font-weight-bold"><?php echo e($result->user_grade); ?></span>
                                        <span class="text-gray">/ <?php echo e($result->quiz->total_mark ?? 100); ?></span>
                                    </td>
                                    <td><?php echo e($result->quiz->pass_mark ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if($result->status == 'passed'): ?>
                                            <span class="badge badge-success"><?php echo e(trans('quiz.passed')); ?></span>
                                        <?php elseif($result->status == 'failed'): ?>
                                            <span class="badge badge-danger"><?php echo e(trans('quiz.failed')); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-warning"><?php echo e(trans('quiz.waiting')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(dateTimeFormat($result->created_at, 'j M Y H:i')); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/panel/quizzes/results/<?php echo e($result->id); ?>/details" 
                                               class="btn btn-sm btn-primary">
                                                <?php echo e(trans('panel.view')); ?>

                                            </a>
                                            <?php if($result->status == 'waiting'): ?>
                                                <a href="/panel/quizzes/results/<?php echo e($result->id); ?>/edit" 
                                                   class="btn btn-sm btn-warning ml-2">
                                                    <?php echo e(trans('panel.review')); ?>

                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="mt-30">
                    <?php echo e($quizResults->links()); ?>

                </div>
            </div>
        <?php else: ?>
            <?php echo $__env->make('design_1.panel.includes.no-result',[
                'file_name' => 'quiz.svg',
                'title' => trans('panel.no_quiz_results_found'),
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/students_tracking/quiz_results.blade.php ENDPATH**/ ?>