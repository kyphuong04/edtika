<?php
    $getUserLanguageAndLocale = getUserLanguagesLists();
?>

<div class="js-language-select theme-header-1__dropdown position-relative">
    <form action="/locale" method="post">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" name="locale" value="<?php echo e(app()->getLocale()); ?>">

        <?php $__currentLoopData = $getUserLanguageAndLocale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeSign => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(mb_strtolower(app()->getLocale()) == mb_strtolower($localeSign)): ?>
                <div class="d-flex align-items-center gap-8">
                    <div class="size-32 d-flex-center bg-white-10 rounded-8">
                        <img src="<?php echo e(asset('vendor/blade-country-flags/4x3-'. mb_strtolower(localeToCountryCode(mb_strtoupper($localeSign))) .'.svg')); ?>" class="img-fluid" width="16px" height="16px" alt="<?php echo e($language); ?> <?php echo e(trans('flag')); ?>"/>
                    </div>
                    <span class="js-lang-title text-white opacity-75 d-none d-md-flex"><?php echo e($language); ?></span>
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-down'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white opacity-75','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </form>

    <div class="header-1-dropdown-menu py-8 mx-w-200">

        <div class="py-8 px-16 font-12 text-gray-500"><?php echo e(trans('update.select_a_language')); ?></div>

        <?php $__currentLoopData = $getUserLanguageAndLocale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeSign => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="js-language-dropdown-item header-1-dropdown-menu__item cursor-pointer <?php echo e((mb_strtolower(app()->getLocale()) == mb_strtolower($localeSign)) ? 'active' : ''); ?>" data-value="<?php echo e($localeSign); ?>" data-title="<?php echo e($language); ?>">
                <div class=" d-flex align-items-center w-100 px-16 py-8 text-dark bg-transparent">
                    <div class="header-1-dropdown-menu__flag">
                        <img src="<?php echo e(asset('vendor/blade-country-flags/4x3-'. mb_strtolower(localeToCountryCode(mb_strtoupper($localeSign))) .'.svg')); ?>" class="img-cover" alt="<?php echo e($language); ?> <?php echo e(trans('flag')); ?>"/>
                    </div>
                    <span class="ml-8 font-14"><?php echo e($language); ?></span>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/theme/headers/header_1/includes/language.blade.php ENDPATH**/ ?>