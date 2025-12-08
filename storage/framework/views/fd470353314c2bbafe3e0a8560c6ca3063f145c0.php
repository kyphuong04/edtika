<form action="/panel/quizzes" method="get" class="px-16">
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
                <label class="form-group-label"><?php echo e(trans('quiz.quiz_or_webinar')); ?></label>
                <select name="quiz_id" class="form-control select2" data-placeholder="<?php echo e(trans('public.all')); ?>">
                    <option value="all"><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = $allQuizzesLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allQuiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($allQuiz->id); ?>" <?php if(request()->get('quiz_id') == $allQuiz->id): ?> selected <?php endif; ?>><?php echo e($allQuiz->title .' - '. ($allQuiz->webinar ? $allQuiz->webinar->title : '-')); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('public.total_mark')); ?></label>
                <input type="text" name="total_mark" class="form-control" value="<?php echo e(request()->get('total_mark','')); ?>"/>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('public.questions')); ?></label>
                <select class="form-control select2" id="questions_type" name="questions_type" data-minimum-results-for-search="Infinity">
                    <option value=""><?php echo e(trans('public.all')); ?></option>
                    <option value="multiple" ><?php echo e(trans('update.multiple')); ?></option>
                    <option value="descriptive" ><?php echo e(trans('quiz.descriptive')); ?></option>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('public.status')); ?></label>
                <select class="form-control select2" id="status" name="status" data-minimum-results-for-search="Infinity">
                    <option value="all"><?php echo e(trans('public.all')); ?></option>
                    <option value="active" ><?php echo e(trans('public.active')); ?></option>
                    <option value="inactive" ><?php echo e(trans('public.inactive')); ?></option>
                </select>
            </div>
        </div>

        <?php
            $sorts = [
                'questions_asc',
                'questions_desc',
                'time_asc',
                'time_desc',
                'pass_mark_asc',
                'pass_mark_desc',
                'create_date_asc',
                'create_date_desc',
            ];
        ?>
        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('update.filters')); ?></label>
                <select class="form-control select2" id="sort" name="sort" data-minimum-results-for-search="Infinity">
                    <option value="all"><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = $sorts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sort): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sort); ?>"><?php echo e(trans("update.{$sort}")); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block"><?php echo e(trans('filter')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/lists/filters.blade.php ENDPATH**/ ?>