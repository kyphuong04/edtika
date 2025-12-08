<tr>
    <td class="text-left">
        <div class="user-inline-avatar d-flex align-items-center">
            <div class="avatar bg-gray200">
                <img src="<?php echo e($student['avatar']); ?>" class="img-cover" alt="<?php echo e($student['full_name']); ?>" onerror="this.src='/assets/default/img/user/avatar_default.png'">
            </div>
            <div class="ml-10">
                <div class="font-weight-500 font-14 text-dark-blue"><?php echo e($student['full_name']); ?></div>
                <div class="font-12 text-gray"><?php echo e($student['email'] ?? ''); ?></div>
            </div>
        </div>
    </td>
    <td class="text-center align-middle">
        <span class="font-weight-500"><?php echo e($student['courses_enrolled']); ?></span>
    </td>
    <td class="text-center align-middle">
        <span class="font-weight-500 text-success"><?php echo e($student['courses_completed']); ?></span>
    </td>
    <td class="text-center align-middle">
        <?php
            $progressBarColor = 'bg-warning';
            if($student['average_progress'] >= 80) {
                $progressBarColor = 'bg-success';
            } elseif($student['average_progress'] < 50) {
                $progressBarColor = 'bg-danger';
            }
        ?>
        <div class="progress" style="height: 24px; position: relative; min-width: 100px;">
            <div class="progress-bar <?php echo e($progressBarColor); ?>" role="progressbar" 
                 style="width: <?php echo e(max($student['average_progress'], 5)); ?>%;" 
                 aria-valuenow="<?php echo e($student['average_progress']); ?>" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                <span style="position: absolute; left: 0; right: 0; text-align: center; line-height: 24px;">
                    <?php echo e(number_format($student['average_progress'], 1)); ?>%
                </span>
            </div>
        </div>
    </td>
    <td class="text-center align-middle">
        <span class="font-weight-500"><?php echo e($student['total_quiz_results']); ?></span>
    </td>
    <td class="text-center align-middle">
        <?php
            $gradeColor = 'text-warning';
            if($student['average_quiz_grade'] >= 80) {
                $gradeColor = 'text-success';
            } elseif($student['average_quiz_grade'] < 50) {
                $gradeColor = 'text-danger';
            }
        ?>
        <span class="font-weight-bold <?php echo e($gradeColor); ?>">
            <?php echo e(number_format($student['average_quiz_grade'], 1)); ?>

        </span>
    </td>
    <td class="text-center align-middle">
        <div class="btn-group">
            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="12" cy="5" r="1"></circle>
                    <circle cx="12" cy="19" r="1"></circle>
                </svg>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <h6 class="dropdown-header"><?php echo e(trans('panel.student_actions')); ?></h6>
                <a href="/panel/students-tracking/<?php echo e($student['id']); ?>/details" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <?php echo e(trans('panel.view_details')); ?>

                </a>
                <div class="dropdown-divider"></div>
                <a href="/panel/students-tracking/<?php echo e($student['id']); ?>/quiz-results" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <?php echo e(trans('panel.quiz_results')); ?>

                </a>
                <a href="/panel/students-tracking/<?php echo e($student['id']); ?>/assignments" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                    <?php echo e(trans('panel.assignments')); ?>

                </a>
                <div class="dropdown-divider"></div>
                <a href="/panel/students-tracking/<?php echo e($student['id']); ?>/details" class="dropdown-item text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <?php echo e(trans('panel.send_support_message')); ?>

                </a>
            </div>
        </div>
    </td>
</tr>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/students_tracking/student_item.blade.php ENDPATH**/ ?>