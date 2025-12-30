<div id="companyLogos" class="d-grid grid-column-138-auto gap-16 ">

    <?php $__currentLoopData = $webinar->webinarExtraDescription->where('type', 'company_logos'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $companyLogo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="js-create-media-col">
            <div class="create-media-card position-relative p-4">
                <div class="create-media-card__img position-relative d-flex align-items-center justify-content-center w-100 h-100">
                    <img src="<?php echo e($companyLogo->value); ?>" alt="" class="img-cover rounded-15">

                    <a href="/panel/webinar-extra-description/<?php echo e($companyLogo->id); ?>/delete" class="delete-action create-media-card__delete-btn d-flex align-items-center justify-content-center">
                        <span class="d-flex align-items-center justify-content-center p-4">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '24','height' => '24']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="js-create-media-col">
        <div class="create-media-card position-relative p-4">
            <label for="createMedia" class="create-media-card__label w-100 h-100 rounded-15 flex-column align-items-center justify-content-center cursor-pointer">
                <div class="create-media-card__circle d-flex align-items-center justify-content-center rounded-circle">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-direct-send'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24','height' => '24']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>

                <div class="mt-8 font-12 text-primary"><?php echo e(trans('update.select_a_logo')); ?></div>
            </label>

            <input type="file" name="companyLogos[]" id="createMedia" class="js-create-media" data-col="" data-parent-id="companyLogos" data-label="<?php echo e(trans('update.select_a_logo')); ?>" accept="">
        </div>
    </div>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/includes/accordions/company_logos.blade.php ENDPATH**/ ?>