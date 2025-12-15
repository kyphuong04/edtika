<div class="theme-header-1__dropdown position-relative">
    <div class="d-inline-flex align-items-center gap-8 p-16 rounded-12 bg-gray-100">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-category'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        <span class="text-gray-500"><?php echo e(trans('categories.categories')); ?></span>
    </div>

    <div class="header-1-dropdown-menu auth-user-info-dropdown-menu py-12">

        <ul class="theme-header-1__categories">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="header-1-dropdown-menu__item position-relative">
                    <a href="<?php echo e($category->getUrl()); ?>" class="d-flex align-items-center justify-content-between w-100 px-16 py-8  <?php echo e((!empty($category->subCategories) and count($category->subCategories)) ? 'js-has-subcategory' : ''); ?>">
                        <div class="d-flex align-items-center">
                            <?php if(!empty($category->icon)): ?>
                                <img src="<?php echo e($category->icon); ?>" class="cat-dropdown-menu-icon mr-8" alt="<?php echo e($category->title); ?> icon">
                            <?php endif; ?>

                            <span class=""><?php echo e($category->title); ?></span>
                        </div>

                        <?php if(!empty($category->subCategories) and count($category->subCategories)): ?>
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <?php endif; ?>
                    </a>

                    <?php if(!empty($category->subCategories) and count($category->subCategories)): ?>
                        <ul class="header-1-dropdown-menu__sub-menu py-12">
                            <?php $__currentLoopData = $category->subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="">
                                    <a href="<?php echo e($subCategory->getUrl()); ?>" class="d-flex align-items-center w-100 px-16 py-8">
                                        <div class="d-flex align-items-center w-100">
                                            <?php if(!empty($subCategory->icon)): ?>
                                                <img src="<?php echo e($subCategory->icon); ?>" class="cat-dropdown-menu-icon mr-8" alt="<?php echo e($subCategory->title); ?> icon">
                                            <?php endif; ?>

                                            <span class=""><?php echo e($subCategory->title); ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/theme/headers/header_1/includes/categories.blade.php ENDPATH**/ ?>