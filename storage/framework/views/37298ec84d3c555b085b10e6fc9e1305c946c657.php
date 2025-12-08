<form action="" method="get" class="px-16">

    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('public.search')); ?></label>
                <input type="text" name="search" class="form-control" value="<?php echo e(request()->get('search')); ?>" placeholder="<?php echo e(trans('search')); ?>">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('update.last_submission_date_range')); ?></label>
                <input type="text" name="last_submission_date" class="form-control date-range-picker" data-format="YYYY/MM/DD" value="">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('quiz.quiz')); ?></label>
                <select name="quiz_id" class="form-control select2" data-placeholder="<?php echo e(trans('public.all')); ?>">
                    <option value=""><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = $allQuizzesLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allQuiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($allQuiz->id); ?>"><?php echo e($allQuiz->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('product.course')); ?></label>
                <select name="course_id" class="form-control select2" data-placeholder="<?php echo e(trans('public.all')); ?>">
                    <option value=""><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = $allCoursesLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allCourseList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($allCourseList->id); ?>"><?php echo e($allCourseList->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('quiz.student')); ?></label>
                <select name="student_id" class="form-control select2">
                    <option value=""><?php echo e(trans('all')); ?></option>

                    <?php $__currentLoopData = $allStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allStudent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($allStudent->id); ?>"><?php echo e($allStudent->full_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('public.status')); ?></label>
                <select name="status" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value=""><?php echo e(trans('all')); ?></option>

                    <?php $__currentLoopData = ['passed', 'failed', 'waiting']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>"><?php echo e(trans($status)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <?php
            $sortItems = [
                'grade_asc',
                'grade_desc',
                'create_date_asc',
                'create_date_desc',
            ];
        ?>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('filters')); ?></label>
                <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value=""><?php echo e(trans('all')); ?></option>

                    <?php $__currentLoopData = $sortItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sortItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sortItem); ?>" <?php echo e(($sortItem == request()->get('sort')) ? 'selected' : ''); ?>><?php echo e(trans("update.{$sortItem}")); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block"><?php echo e(trans('update.filter')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/results/filters.blade.php ENDPATH**/ ?>