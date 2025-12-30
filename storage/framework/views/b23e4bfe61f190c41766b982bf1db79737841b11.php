<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
<?php $__env->stopPush(); ?>

<div class="bg-white rounded-16 p-16 mt-32">
    <h3 class="font-14 font-weight-bold"><?php echo e(trans('update.taxonomy')); ?></h3>

    <div class="form-group  mt-24">
        <label class="form-group-label is-required"><?php echo e(trans('public.category')); ?></label>

        <select name="category_id" id="categories" class="select2 <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <option <?php echo e((!empty($bundle) and !empty($bundle->category_id)) ? '' : 'selected'); ?> disabled><?php echo e(trans('public.choose_category')); ?></option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($category->subCategories) and $category->subCategories->count() > 0): ?>
                    <optgroup label="<?php echo e($category->title); ?>">
                        <?php $__currentLoopData = $category->subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subCategory->id); ?>" <?php echo e(((!empty($bundle) and $bundle->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : ''); ?>><?php echo e($subCategory->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                <?php else: ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(((!empty($bundle) and $bundle->category_id == $category->id) or old('category_id') == $category->id) ? 'selected' : ''); ?>><?php echo e($category->title); ?></option>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block">
            <?php echo e($message); ?>

        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mt-24 <?php echo e((!empty($bundleCategoryFilters) and count($bundleCategoryFilters)) ? '' : 'd-none'); ?>" id="categoriesFiltersContainer">
        <h3 class="font-14 font-weight-bold"><?php echo e(trans('public.category_filters')); ?></h3>

        <div id="categoriesFiltersCard" class="row">
            <?php if(!empty($bundleCategoryFilters) and count($bundleCategoryFilters)): ?>
                <?php $__currentLoopData = $bundleCategoryFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12 col-md-3 mt-16">
                        <div class="create-course-filter-card bg-white p-16 rounded-12 border-gray-200">
                            <h5 class="font-14 font-weight-bold mb-16"><?php echo e($filter->title); ?></h5>

                            <?php
                                $bundleFilterOptions = $bundle->filterOptions->pluck('filter_option_id')->toArray();

                                if (!empty(old('filters'))) {
                                    $bundleFilterOptions = array_merge($bundleFilterOptions, old('filters'));
                                }
                            ?>

                            <?php $__currentLoopData = $filter->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="custom-control custom-checkbox <?php echo e($loop->first ? '' : 'mt-12'); ?>">
                                    <input type="checkbox" name="filters[]" value="<?php echo e($option->id); ?>" id="filterOptions<?php echo e($option->id); ?>" class="custom-control-input" <?php echo e(((!empty($bundleFilterOptions) && in_array($option->id, $bundleFilterOptions)) ? 'checked' : '')); ?>>
                                    <label class="custom-control__label cursor-pointer" for="filterOptions<?php echo e($option->id); ?>"><?php echo e($option->title); ?></label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group tagsinput-bg-white mt-15">
        <label class="form-group-label d-block"><?php echo e(trans('public.tags')); ?></label>
        <input type="text" name="tags" data-max-tag="5" value="<?php echo e(!empty($bundle) ? implode(',',$bundleTags) : ''); ?>" class="form-control inputtags" placeholder="<?php echo e(trans('public.type_tag_name_and_press_enter')); ?> (<?php echo e(trans('forms.max')); ?> : 5)"/>
    </div>


</div>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/bundles/create/steps/step_2.blade.php ENDPATH**/ ?>