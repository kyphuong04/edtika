

<?php
    $siteGeneralSettings = getGeneralSettings();
?>

<?php $__env->startSection('content'); ?>
    <div class="content-box position-relative w-100">
        <div class="content-box__mask"></div>

        <div class="position-relative z-index-2 bg-white py-32 px-16 rounded-24">
            <div class="d-flex-center flex-column text-center">
                <div class="content-box__logo">
                    <img src="<?php echo e($siteGeneralSettings['logo'] ?? ''); ?>" alt="logo" class="">
                </div>

                <h1 class="font-24 mt-16"><?php echo e(trans('update.welcome_back_to_site!', ['site' => $siteGeneralSettings['site_name'] ?? ''])); ?></h1>
                <p class="font-12 text-gray-500 mt-8"><?php echo e(trans('update.login_to_your_account_and_manage_everything')); ?></p>

            </div>

            <form method="POST" action="<?php echo e(getAdminPanelUrl("/login")); ?>" class="mt-28" novalidate="">
                <?php echo e(csrf_field()); ?>


                <div class="form-group">
                    <label class="form-group-label bg-white"><?php echo e(trans('public.email')); ?></label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control bg-white  <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback">
                        <?php echo e($message); ?>

                    </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group position-relative">
                    <label class="form-group-label bg-white"><?php echo e(trans('auth.password')); ?></label>
                    <input name="password" type="password" class="form-control bg-white  <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                    <div class="password-input-visibility cursor-pointer size-24">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-eye-slash'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons-eye-slash text-gray-400 d-none','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-eye'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons-eye text-gray-400 ','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>

                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback">
                        <?php echo e($message); ?>

                    </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <?php if(!empty(getGeneralSecuritySettings('captcha_for_admin_login'))): ?>
                    <div class="mt-28 ">
                        <?php echo $__env->make('design_1.web.includes.captcha_input', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                <?php endif; ?>


                <div class="custom-control custom-checkbox mt-20">
                    <input type="checkbox" name="remember" id="rememberSwitch" class="custom-control-input">
                    <label class="custom-control__label cursor-pointer" for="rememberSwitch"><?php echo e(trans('auth.remember_me')); ?></label>
                </div>

                <button type="submit" class="btn btn-primary btn-xlg btn-block mt-16"><?php echo e(trans('auth.login')); ?></button>
            </form>

            <div class="d-flex-center flex-column mt-20">
                <a href="<?php echo e(getAdminPanelUrl("/forget-password")); ?>" class="text-dark"><?php echo e(trans('auth.forget_your_password')); ?></a>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.auth.new.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/admin/auth/new/login.blade.php ENDPATH**/ ?>