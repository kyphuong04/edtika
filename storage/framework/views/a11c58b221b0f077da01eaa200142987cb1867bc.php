<div class="position-relative bundles-lists-filters">
    <div class="bundles-lists-filters__mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between flex-column flex-md-row gap-20 bg-white px-24 py-12 rounded-24 z-index-2">
        <div class="d-flex align-items-center gap-20 gap-lg-48">
            <?php $__currentLoopData = ['free', 'discount']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topFilter1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-group mb-0 d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="top_filter_<?php echo e($topFilter1); ?>" type="checkbox" name="<?php echo e($topFilter1); ?>" value="on" class="custom-control-input">
                        <label class="custom-control-label cursor-pointer" for="top_filter_<?php echo e($topFilter1); ?>"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="top_filter_<?php echo e($topFilter1); ?>"><?php echo e(trans("update.{$topFilter1}")); ?></label>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="bundles-lists-sort-input form-group  mb-0">
            <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity">
                <option disabled selected><?php echo e(trans('public.sort_by')); ?></option>
                <option value=""><?php echo e(trans('public.all')); ?></option>

                <?php $__currentLoopData = ['newest', 'earliest_publish_date', 'farthest_publish_date', 'highest_price', 'lowest_price']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filterSort): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($filterSort); ?>"><?php echo e(trans("update.{$filterSort}")); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/bundles/lists/includes/top_filters.blade.php ENDPATH**/ ?>