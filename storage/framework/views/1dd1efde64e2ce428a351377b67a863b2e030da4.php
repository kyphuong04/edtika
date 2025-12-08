

<?php $__env->startSection('content'); ?>
    <section>
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title"><?php echo e(trans('panel.course_progress')); ?></h2>
            <a href="/panel/students-tracking/<?php echo e($student->id); ?>/details" class="btn btn-sm btn-primary">
                <i class="fa fa-arrow-left mr-2"></i><?php echo e(trans('panel.back')); ?>

            </a>
        </div>

        
        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3 class="font-20 font-weight-bold text-dark-blue"><?php echo e($webinar->title); ?></h3>
                    <div class="mt-10">
                        <span class="text-gray font-14"><?php echo e(trans('panel.student')); ?>: </span>
                        <span class="font-weight-500"><?php echo e($student->full_name); ?></span>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-right mt-20 mt-md-0">
                    <div class="d-flex flex-column">
                        <span class="text-gray font-14"><?php echo e(trans('panel.overall_progress')); ?></span>
                        <span class="font-30 font-weight-bold text-primary"><?php echo e(number_format($progress, 1)); ?>%</span>
                    </div>
                    <div class="progress mt-10">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo e($progress); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-30">
            <h3 class="section-title"><?php echo e(trans('panel.content_progress')); ?></h3>
            
            <?php if(!empty($allItems) && count($allItems) > 0): ?>
                <div class="panel-section-card py-20 px-25 mt-20">
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th class="text-left"><?php echo e(trans('panel.chapter')); ?></th>
                                    <th class="text-left"><?php echo e(trans('panel.content')); ?></th>
                                    <th class="text-center"><?php echo e(trans('panel.type')); ?></th>
                                    <th class="text-center"><?php echo e(trans('panel.status')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $allItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-left"><?php echo e($item['chapter']); ?></td>
                                        <td class="text-left"><?php echo e($item['title']); ?></td>
                                        <td class="text-center">
                                            <?php
                                                $typeIcon = 'fa-file';
                                                $typeLabel = ucfirst($item['type']);
                                                
                                                switch($item['type']) {
                                                    case 'file':
                                                        $typeIcon = 'fa-file-alt';
                                                        break;
                                                    case 'session':
                                                        $typeIcon = 'fa-video';
                                                        break;
                                                    case 'text_lesson':
                                                        $typeIcon = 'fa-book';
                                                        break;
                                                    case 'quiz':
                                                        $typeIcon = 'fa-question-circle';
                                                        break;
                                                    case 'assignment':
                                                        $typeIcon = 'fa-tasks';
                                                        break;
                                                }
                                            ?>
                                            <i class="fa <?php echo e($typeIcon); ?> mr-2"></i><?php echo e($typeLabel); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php if($item['completed']): ?>
                                                <span class="badge badge-success">
                                                    <i class="fa fa-check mr-1"></i><?php echo e(trans('panel.completed')); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">
                                                    <?php echo e(trans('panel.not_completed')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <?php echo $__env->make('design_1.panel.includes.no-result',[
                    'file_name' => 'content.svg',
                    'title' => trans('panel.no_content_found'),
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
        </div>

        
        <?php if(!empty($quizResults) && $quizResults->count() > 0): ?>
            <div class="mt-30">
                <h3 class="section-title"><?php echo e(trans('panel.quiz_results')); ?></h3>
                
                <div class="panel-section-card py-20 px-25 mt-20">
                    <div class="table-responsive">
                        <table class="table custom-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left"><?php echo e(trans('panel.quiz')); ?></th>
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
                                            <a href="/panel/quizzes/results/<?php echo e($result->id); ?>/details" 
                                               class="btn btn-sm btn-primary">
                                                <?php echo e(trans('panel.view')); ?>

                                            </a>
                                            <?php if($result->status == 'waiting'): ?>
                                                <a href="/panel/quizzes/results/<?php echo e($result->id); ?>/edit" 
                                                   class="btn btn-sm btn-warning">
                                                    <?php echo e(trans('panel.review')); ?>

                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if(!empty($assignments) && $assignments->count() > 0): ?>
            <div class="mt-30">
                <h3 class="section-title"><?php echo e(trans('panel.assignments')); ?></h3>
                
                <div class="panel-section-card py-20 px-25 mt-20">
                    <div class="table-responsive">
                        <table class="table custom-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left"><?php echo e(trans('panel.assignment')); ?></th>
                                    <th><?php echo e(trans('panel.deadline')); ?></th>
                                    <th><?php echo e(trans('panel.grade')); ?></th>
                                    <th><?php echo e(trans('panel.status')); ?></th>
                                    <th><?php echo e(trans('panel.submission_date')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $submission = $assignment->histories->first();
                                    ?>
                                    <tr>
                                        <td class="text-left"><?php echo e($assignment->title); ?></td>
                                        <td><?php echo e(dateTimeFormat($assignment->deadline, 'j M Y')); ?></td>
                                        <td>
                                            <?php if($submission && $submission->grade !== null): ?>
                                                <span class="font-weight-bold"><?php echo e($submission->grade); ?></span>
                                                <span class="text-gray">/ <?php echo e($assignment->total_mark ?? 100); ?></span>
                                            <?php else: ?>
                                                <span class="text-gray">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($submission): ?>
                                                <?php if($submission->status == 'passed'): ?>
                                                    <span class="badge badge-success"><?php echo e(trans('panel.passed')); ?></span>
                                                <?php elseif($submission->status == 'pending'): ?>
                                                    <span class="badge badge-warning"><?php echo e(trans('panel.pending')); ?></span>
                                                <?php elseif($submission->status == 'not_passed'): ?>
                                                    <span class="badge badge-danger"><?php echo e(trans('panel.not_passed')); ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><?php echo e(trans('panel.not_submitted')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($submission): ?>
                                                <?php echo e(dateTimeFormat($submission->created_at, 'j M Y H:i')); ?>

                                            <?php else: ?>
                                                <span class="text-gray">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/students_tracking/course_progress.blade.php ENDPATH**/ ?>