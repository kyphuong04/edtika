

<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <style>
        /* Top Stats Cards */
        .bg-primary-30 {
            background-color: rgba(84, 130, 255, 0.15);
        }
        
        .bg-success-30 {
            background-color: rgba(34, 182, 127, 0.15);
        }
        
        .bg-info-30 {
            background-color: rgba(0, 188, 212, 0.15);
        }
        
        .bg-warning-30 {
            background-color: rgba(255, 159, 67, 0.15);
        }
        
        .size-48 {
            width: 48px;
            height: 48px;
        }
        
        .d-flex-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .rounded-24 {
            border-radius: 24px;
        }
        
        .rounded-12 {
            border-radius: 12px;
        }
        
        .line-height-1 {
            line-height: 1;
        }
        
        .mb-16 {
            margin-bottom: 16px;
        }
        
        .mt-8 {
            margin-top: 8px;
        }
        
        .mt-12 {
            margin-top: 12px;
        }
        
        .p-16 {
            padding: 16px;
        }
        
        /* Progress Bar */
        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: visible;
        }
        
        .progress-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            transition: width 0.6s ease;
            border-radius: 10px;
        }
        
        /* Table Styles */
        .custom-table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 15px 10px;
        }
        
        .custom-table tbody td {
            border-bottom: 1px solid #e9ecef;
            padding: 15px 10px;
            vertical-align: middle;
        }
        
        .custom-table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .custom-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .user-inline-avatar .avatar {
            border-radius: 50%;
            overflow: hidden;
            width: 42px;
            height: 42px;
        }
        
        .btn-transparent {
            background: transparent;
            border: none;
            color: #6c757d;
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-transparent:hover {
            color: #495057;
            transform: scale(1.1);
        }
        
        .btn-transparent:focus {
            outline: none;
            box-shadow: none;
        }
        
        /* Dropdown Menu Styles */
        .dropdown-menu {
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 8px 0;
            min-width: 200px;
        }
        
        .dropdown-header {
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .dropdown-item {
            padding: 10px 16px;
            font-size: 14px;
            color: #495057;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }
        
        .dropdown-item svg {
            flex-shrink: 0;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #007bff;
            padding-left: 20px;
        }
        
        .dropdown-divider {
            margin: 8px 0;
            border-top: 1px solid #e9ecef;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section>
        <h2 class="section-title"><?php echo e(trans('panel.students_tracking')); ?></h2>

        
        <div class="row">
            <div class="col-6 col-lg-3 mb-16">
                <div class="bg-white p-16 rounded-24">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8 font-14"><?php echo e(trans('panel.total_students')); ?></span>
                        <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                            <img src="/assets/default/img/stats/student.svg" width="24" height="24" alt="Students">
                        </div>
                    </div>
                    <h5 class="font-24 mt-12 line-height-1 font-weight-bold"><?php echo e($totalStudents); ?></h5>
                </div>
            </div>

            <div class="col-6 col-lg-3 mb-16">
                <div class="bg-white p-16 rounded-24">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8 font-14"><?php echo e(trans('panel.total_courses')); ?></span>
                        <div class="size-48 d-flex-center bg-success-30 rounded-12">
                            <img src="/assets/default/img/stats/course.svg" width="24" height="24" alt="Courses">
                        </div>
                    </div>
                    <h5 class="font-24 mt-12 line-height-1 font-weight-bold"><?php echo e($allWebinars->count()); ?></h5>
                </div>
            </div>

            <div class="col-6 col-lg-3 mb-16">
                <div class="bg-white p-16 rounded-24">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8 font-14"><?php echo e(trans('panel.average_progress')); ?></span>
                        <div class="size-48 d-flex-center bg-info-30 rounded-12">
                            <img src="/assets/default/img/activity/webinars.svg" width="24" height="24" alt="Progress">
                        </div>
                    </div>
                    <h5 class="font-24 mt-12 line-height-1 font-weight-bold text-primary">
                        <?php echo e(number_format(collect($students)->avg('average_progress'), 1)); ?>%
                    </h5>
                </div>
            </div>

            <div class="col-6 col-lg-3 mb-16">
                <div class="bg-white p-16 rounded-24">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8 font-14"><?php echo e(trans('panel.average_grade')); ?></span>
                        <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                            <img src="/assets/default/img/activity/58.svg" width="24" height="24" alt="Grade">
                        </div>
                    </div>
                    <h5 class="font-24 mt-12 line-height-1 font-weight-bold text-warning">
                        <?php echo e(number_format(collect($students)->avg('average_quiz_grade'), 1)); ?>

                    </h5>
                </div>
            </div>
        </div>

        
        <div class="mt-20 panel-section-card p-20">
            <form action="<?php echo e(url()->current()); ?>" method="get">
                <div class="row align-items-end">
                    <div class="col-12 col-lg-4 mb-15 mb-lg-0">
                        <label class="text-gray font-14 font-weight-500 mb-10"><?php echo e(trans('panel.course')); ?></label>
                        <select name="webinar_id" class="form-control">
                            <option value="all"><?php echo e(trans('panel.all_courses')); ?></option>
                            <?php $__currentLoopData = $allWebinars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webinar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($webinar->id); ?>" <?php echo e(request()->get('webinar_id') == $webinar->id ? 'selected' : ''); ?>>
                                    <?php echo e($webinar->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-12 col-lg-4 mb-15 mb-lg-0">
                        <label class="text-gray font-14 font-weight-500 mb-10"><?php echo e(trans('panel.search')); ?></label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="<?php echo e(trans('panel.search_student')); ?>" 
                               value="<?php echo e(request()->get('search')); ?>">
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary flex-grow-1 mr-10">
                                <?php echo e(trans('panel.show_results')); ?>

                            </button>
                            <a href="<?php echo e(url('/panel/students-tracking/export')); ?>" class="btn btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        
        <?php if(!empty($students) and count($students) > 0): ?>
            <div id="tableListContainer" class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                <tr>
                                    <th class="text-left"><?php echo e(trans('panel.student')); ?></th>
                                    <th class="text-center"><?php echo e(trans('panel.enrolled_courses')); ?></th>
                                    <th class="text-center"><?php echo e(trans('panel.completed_courses')); ?></th>
                                    <th class="text-center"><?php echo e(trans('panel.average_progress')); ?></th>
                                    <th class="text-center"><?php echo e(trans('panel.quiz_results')); ?></th>
                                    <th class="text-center"><?php echo e(trans('panel.average_grade')); ?></th>
                                    <th class="text-center"><?php echo e(trans('public.action')); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo $__env->make('design_1.panel.students_tracking.student_item', ['student' => $student], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                <?php if(!empty($pagination)): ?>
                    <div class="mt-30">
                        <div class="d-flex justify-content-center">
                            <?php echo $pagination; ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php echo $__env->make('design_1.panel.includes.no-result',[
                'file_name' => 'student.svg',
                'title' => trans('panel.no_students_found'),
                'hint' => trans('panel.no_students_hint'),
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>

    <script>
        var undefinedActiveSessionLang = '<?php echo e(trans('webinars.undefined_active_session')); ?>';
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/students_tracking/index.blade.php ENDPATH**/ ?>