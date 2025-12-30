<?php if(getGeneralSettings('content_translate')): ?>
    <div class="form-group <?php echo e(!empty($className) ? $className : ''); ?>">
        <label class="form-group-label"><?php echo e(trans('language')); ?></label>

        <select name="locale"
                class="form-control select2 <?php echo e(!empty($withoutReloadLocale) ? '' : 'js-reload-when-selected'); ?> <?php echo e(!empty($extraClass) ? $extraClass : ''); ?>" <?php echo !empty($extraData) ? $extraData : ''; ?>

                data-minimum-results-for-search="Infinity"
        >
            <?php $__currentLoopData = getUserLanguagesLists(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option
                    value="<?php echo e(mb_strtolower($lang)); ?>"
                    <?php echo e((mb_strtolower(request()->get('locale', (!empty($itemRow) and !empty($itemRow->locale)) ? $itemRow->locale : app()->getLocale())) == mb_strtolower($lang)) ? 'selected' : ''); ?>>
                    <?php echo e($language); ?> <?php echo e((!empty($itemRow) and empty($itemRow->translate(mb_strtolower($lang)))) ? '('.trans('update.not_defined').')' : ''); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
<?php else: ?>
    <input type="hidden" name="locale" value="<?php echo e(getDefaultLocale()); ?>">
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/includes/locale/locale_select.blade.php ENDPATH**/ ?>