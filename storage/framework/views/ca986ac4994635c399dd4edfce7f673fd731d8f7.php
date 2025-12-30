<?php
    $getUserLanguageAndLocale = getUserLanguagesLists();
?>

<div class="js-language-select language-select position-relative <?php echo e(!empty($langClassName) ? $langClassName : ''); ?>">
    <form action="/locale" method="post">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" name="locale" value="<?php echo e(app()->getLocale()); ?>">

        <?php $__currentLoopData = $getUserLanguageAndLocale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeSign => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(mb_strtolower(app()->getLocale()) == mb_strtolower($localeSign)): ?>
                <div class="language-toggle d-flex align-items-center">
                    <div class="size-32 d-flex-center bg-gray-100 rounded-8">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-global'); ?>
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
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </form>

    <div class="language-dropdown py-8">

        <div class="py-8 px-16 font-12 text-gray-500"><?php echo e(trans('update.select_a_language')); ?></div>

        <?php $__currentLoopData = $getUserLanguageAndLocale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeSign => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="js-language-dropdown-item language-dropdown__item cursor-pointer <?php echo e((mb_strtolower(app()->getLocale()) == mb_strtolower($localeSign)) ? 'active' : ''); ?>" data-value="<?php echo e($localeSign); ?>" data-title="<?php echo e($language); ?>">
                <div class=" d-flex align-items-center w-100 px-16 py-8 text-dark bg-transparent">
                    <div class="language-dropdown__flag">
                        <img src="<?php echo e(asset('vendor/blade-country-flags/4x3-'. mb_strtolower(localeToCountryCode(mb_strtoupper($localeSign))) .'.svg')); ?>" class="img-cover" alt="<?php echo e($language); ?> <?php echo e(trans('flag')); ?>"/>
                    </div>
                    <span class="ml-8 font-14"><?php echo e($language); ?></span>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/includes/header/language.blade.php ENDPATH**/ ?>