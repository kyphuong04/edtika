<form action="" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-gray-border','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>
                <label class="form-group-label"><?php echo e(trans('public.from')); ?></label>
                <input type="text" name="from" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="<?php echo e(request()->get('from')); ?>">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-gray-border','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>
                <label class="form-group-label"><?php echo e(trans('public.to')); ?></label>
                <input type="text" name="to" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="<?php echo e(request()->get('to')); ?>">
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
            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('public.instructor')); ?></label>
                <select name="instructor_id" class="form-control select2" data-placeholder="<?php echo e(trans('public.all')); ?>">
                    <option value=""><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = $allInstructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allInstructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($allInstructor->id); ?>"><?php echo e($allInstructor->full_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('public.status')); ?></label>
                <select class="form-control select2" id="status" name="status" data-minimum-results-for-search="Infinity">
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
                <select name="sort" class="form-control select2">
                    <option value=""><?php echo e(trans('all')); ?></option>

                    <?php $__currentLoopData = $sortItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sortItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sortItem); ?>" <?php echo e(($sortItem == request()->get('sort')) ? 'selected' : ''); ?>><?php echo e(trans("update.{$sortItem}")); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>


        <div class="col-12 col-lg-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block"><?php echo e(trans('filter')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/my_results/filters.blade.php ENDPATH**/ ?>