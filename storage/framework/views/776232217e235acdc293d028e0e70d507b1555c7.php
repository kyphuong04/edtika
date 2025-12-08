<div class="form-group">
    <div class="register-mobile-form-group position-relative <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <label class="form-group-label"><?php echo e(trans('public.phone')); ?> <?php echo e(!empty($optional) ? "(". trans('public.optional') .")" : ''); ?></label>

        <div class="row">
            <div class="col-4 h-100 pr-0">
                <select name="country_code" class="form-control country-code-select2">
                    <?php $__currentLoopData = getCountriesMobileCode(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country => $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($code); ?>" <?php if($code == old('country_code')): ?> selected <?php endif; ?>><?php echo e($country); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-8 h-100 pl-4">
                <input type="tel" name="mobile" class="register-mobile-form-group__input">
            </div>
        </div>
    </div>

    <?php $__errorArgs = ['mobile'];
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
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/auth/theme_1/includes/mobile_field.blade.php ENDPATH**/ ?>