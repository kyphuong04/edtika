<div class="panel-header d-flex align-items-center bg-white px-24 px-lg-0">

    <div class="panel-header__logo-box">
        <a href="/" class="panel-header__logo d-inline-flex-center mr-16 mr-lg-0 ml-lg-32">
            <?php if(!empty($generalSettings['logo'])): ?>
                <img src="<?php echo e($generalSettings['logo']); ?>" class="img-fluid light-only" alt="<?php echo e($generalSettings['site_name'] ?? 'site'); ?>">
            <?php endif; ?>

            <?php if(!empty($generalSettings['dark_mode_logo'])): ?>
                <img src="<?php echo e($generalSettings['dark_mode_logo']); ?>" class="img-fluid dark-only" alt="<?php echo e($generalSettings['site_name'] ?? 'site'); ?>">
            <?php endif; ?>
        </a>
    </div>

    <div class="panel-header__contents d-flex align-items-center justify-content-between h-100 border-bottom-gray-200">

        <div class="d-flex align-items-center">

            
            <div class="js-theme-color-toggle theme-color-toggle theme-color-toggle__panel <?php echo e("{$userThemeColorMode}-mode"); ?> d-flex-center size-16 bg-gray-100 rounded-8 mr-16 mr-lg-32">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-moon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'dark-icon icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-sun-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'light-icon icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>

            <?php if(!empty($navbarPages) and count($navbarPages)): ?>
                <div class="d-none d-lg-flex align-items-center">
                    <?php $__currentLoopData = $navbarPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navbarPage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($navbarPage['link']); ?>" class="navbar-item navbar-item-h-70 d-flex align-items-center mr-16 mr-lg-32 text-gray-500"><?php echo e($navbarPage['title']); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="d-flex align-items-center">

            <?php if($authUser->checkAccessToAIContentFeature()): ?>
                <div class="js-show-ai-content-drawer d-none d-lg-flex align-items-center justify-content-center size-32 rounded-8 bg-gray-100 mr-16 cursor-pointer">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-cpu-charge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
            <?php endif; ?>


            <?php echo $__env->make('design_1.panel.includes.header.currency', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            <div class="mx-16">
                <?php echo $__env->make('design_1.panel.includes.header.language', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="panel-header__contents-with-line d-flex align-items-center gap-16 mr-16">

                <div class="js-view-cart-drawer size-32 position-relative d-flex-center bg-gray-100 rounded-8 cursor-pointer">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-bag-happy'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="js-cart-counter panel-header__badge-counter badge-counter bg-success font-12 <?php echo e(($userCartCount < 1) ? 'd-none' : ''); ?>"><?php echo e($userCartCount); ?></span>
                </div>

                
                <div class="d-none d-lg-flex">
                    <?php echo $__env->make('design_1.panel.includes.header.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>

            <?php echo $__env->make('design_1.panel.includes.header.auth_user_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/includes/header.blade.php ENDPATH**/ ?>