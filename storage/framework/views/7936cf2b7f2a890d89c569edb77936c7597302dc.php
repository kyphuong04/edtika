<div class="instructor-finder__filters-card position-relative bg-white p-16 rounded-24 mt-28">

    
    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFilterInstructors" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php echo e(trans('update.filter_instructors')); ?>

            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFilterInstructors" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </span>
        </div>

        <div id="sidebarFilterInstructors" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            <div class="form-group  mt-24">
                <label class="form-group-label" for="category_id"><?php echo e(trans('public.category')); ?></label>

                <select name="category_id" id="category_id" class="form-control select2">
                    <option value=""><?php echo e(trans('webinars.select_category')); ?></option>

                    <?php if(!empty($categories)): ?>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($category->subCategories) and count($category->subCategories)): ?>
                                <optgroup label="<?php echo e($category->title); ?>">
                                    <?php $__currentLoopData = $category->subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($subCategory->id); ?>" <?php if(request()->get('category_id') == $subCategory->id): ?> selected="selected" <?php endif; ?>><?php echo e($subCategory->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </optgroup>
                            <?php else: ?>
                                <option value="<?php echo e($category->id); ?>" <?php if(request()->get('category_id') == $category->id): ?> selected="selected" <?php endif; ?>><?php echo e($category->title); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group ">
                <label class="form-group-label" for="level_of_training"><?php echo e(trans('update.student_level')); ?></label>

                <select name="level_of_training" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value=""><?php echo e(trans('update.not_preferenced')); ?></option>
                    <option value="beginner" <?php echo e((request()->get('level_of_training') == 'beginner') ? 'selected' : ''); ?>><?php echo e(trans('update.beginner')); ?></option>
                    <option value="middle" <?php echo e((request()->get('level_of_training') == 'middle') ? 'selected' : ''); ?>><?php echo e(trans('update.middle')); ?></option>
                    <option value="expert" <?php echo e((request()->get('level_of_training') == 'expert') ? 'selected' : ''); ?>><?php echo e(trans('update.expert')); ?></option>
                </select>
            </div>

            <div class="form-group ">
                <label class="form-group-label" for="gender"><?php echo e(trans('update.instructor_gender')); ?></label>

                <select name="gender" id="gender" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value=""><?php echo e(trans('update.not_preferenced')); ?></option>

                    <option value="man" <?php echo e((request()->get('gender') == 'man') ? 'selected' : ''); ?>><?php echo e(trans('update.man')); ?></option>
                    <option value="woman" <?php echo e((request()->get('gender') == 'woman') ? 'selected' : ''); ?>><?php echo e(trans('update.woman')); ?></option>
                </select>
            </div>

            <div class="form-group mb-0 ">
                <label class="form-group-label" for="instructor_type"><?php echo e(trans('update.instructor_type')); ?></label>

                <select name="role" id="instructor_type" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value=""><?php echo e(trans('update.not_preferenced')); ?></option>
                    <option value="<?php echo e(\App\Models\Role::$teacher); ?>" <?php echo e((request()->get('role') == \App\Models\Role::$teacher) ? 'selected' : ''); ?>><?php echo e(trans('public.instructor')); ?></option>
                    <option value="<?php echo e(\App\Models\Role::$organization); ?>" <?php echo e((request()->get('role') == \App\Models\Role::$organization) ? 'selected' : ''); ?>><?php echo e(trans('home.organization')); ?></option>
                </select>
            </div>
        </div>
    </div>

    
    

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/instructor_finder/lists/left_side/filters.blade.php ENDPATH**/ ?>