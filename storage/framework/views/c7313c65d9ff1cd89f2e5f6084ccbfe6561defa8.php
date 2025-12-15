

<?php $__env->startSection('page_content'); ?>
    <form method="Post" action="/login" class="">
        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

        <div class="pl-16 pt-16">
            <div class="font-16 font-weight-bold"><?php echo e(trans('update.welcome_back')); ?> 👋</div>
            <h1 class="font-24 mt-4 mb-32"><?php echo e(trans('update.login_to_your_account')); ?></h1>

            
            <?php echo $__env->make('design_1.web.auth.theme_1.includes.login_methods', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="position-relative form-group mt-28 mb-0">
                <label class="form-group-label" for="password"><?php echo e(trans('auth.password')); ?>:</label>
                <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password" aria-describedby="passwordHelp">

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

            <?php if(!empty(getGeneralSecuritySettings('captcha_for_login'))): ?>
                <div class="mt-28 ">
                    <?php echo $__env->make('design_1.web.includes.captcha_input', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            <?php endif; ?>

            <div class="text-right mt-12">
                <a href="/forget-password" target="_blank" class="font-14 text-dark"><?php echo e(trans('auth.forget_your_password')); ?></a>
            </div>

            <button type="button" class="js-submit-form-btn btn btn-primary btn-lg btn-block mt-12"><?php echo e(trans('auth.login')); ?></button>
        </div>
    </form>

    <?php if(session()->has('login_failed_active_session')): ?>
        <div class="pl-16">
            <div class="d-flex align-items-center p-16 rounded-12 border-danger bg-danger-20 mt-16">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <div class="ml-8">
                    <div class="font-14 font-weight-bold text-danger"><?php echo e(session()->get('login_failed_active_session')['title']); ?></div>
                    <div class="mt-4 font-12 text-danger"><?php echo e(session()->get('login_failed_active_session')['msg']); ?></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="d-flex-center flex-column text-center mt-24">
        <div class="font-12 text-gray-500"><?php echo e(trans('update.or_continue_with')); ?></div>

        <div class="d-flex-center gap-20">
            <?php if(!empty(getFeaturesSettings('show_google_login_button'))): ?>
                <a href="/google" target="_blank" class="d-flex-center size-48 bg-gray-100 border-gray-200 rounded-circle mt-16">
                    <img src="/assets/default/img/auth/google.svg" class="img-fluid" alt="google svg" width="24px" height="24px"/>
                </a>
            <?php endif; ?>

            <?php if(!empty(getFeaturesSettings('show_facebook_login_button'))): ?>
                <a href="<?php echo e(url('/facebook/redirect')); ?>" target="_blank" class="d-flex-center size-48 bg-gray-100 border-gray-200 rounded-circle mt-16">
                    <img src="/assets/default/img/auth/facebook.svg" class="img-fluid" alt="facebook svg" width="24px" height="24px"/>
                </a>
            <?php endif; ?>
        </div>

        <div class="font-14 text-gray-500 mt-32"><?php echo e(trans('auth.dont_have_account')); ?></div>

        <a href="/register" class="font-14 font-weight-bold mt-8 text-dark"><?php echo e(trans('auth.signup')); ?></a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.web.auth.theme_1.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/auth/theme_1/login/index.blade.php ENDPATH**/ ?>