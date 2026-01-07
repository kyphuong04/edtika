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
                <label class="form-group-label"><?php echo e(trans('webinars.webinar')); ?></label>
                <select name="webinar_id" class="form-control select2">
                    <option value=""><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = $userWebinars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webinar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($webinar->id); ?>" <?php if(request()->get('webinar_id',null) == $webinar->id): ?> selected <?php endif; ?>><?php echo e($webinar->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>


        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('quiz.student')); ?></label>

                <select name="student_id" class="form-control select2">
                    <option value=""><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($student->id); ?>" ><?php echo e($student->full_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>


        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('update.course_category')); ?></label>

                <select name="category_id" class="form-control select2">
                    <option value=""><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryFilter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($categoryFilter->id); ?>"><?php echo e($categoryFilter->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('public.type')); ?></label>
                <select class="form-control select2" id="type" name="type" data-minimum-results-for-search="Infinity">
                    <option value=""><?php echo e(trans('public.all')); ?></option>

                    <?php $__currentLoopData = ['live_class','text_lesson','course','meeting','product','bundle']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saleType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($saleType); ?>"><?php echo e(trans("update.{$saleType}")); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <?php
            $sortItems = [
                'price_asc',
                'price_desc',
                'create_date_asc',
                'create_date_desc',
            ];
        ?>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label"><?php echo e(trans('filters')); ?></label>
                <select name="sort" class="form-control select2">
                    <option value=""><?php echo e(trans('public.all')); ?></option>

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
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/financial/sales/filters.blade.php ENDPATH**/ ?>