<div class=" <?php echo e(!empty($mediaCardClass) ? $mediaCardClass : 'col-6 col-md-4 col-lg-2 mt-16'); ?>">
    <div class="create-media-just-image-card position-relative p-4">
        <label for="<?php echo e($mediaName); ?>" class="create-media-just-image-card__label w-100 h-100 rounded-15 flex-column align-items-center justify-content-center cursor-pointer <?php echo e((!empty($media)) ? 'd-none' : ''); ?>">
            <div class="create-media-just-image-card__circle d-flex-center size-44 rounded-circle bg-primary-20">
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

            <div class="mt-8 font-12 text-primary"><?php echo e($mediaTitle); ?></div>
        </label>

        <div class="create-media-just-image-card__img align-items-center justify-content-center w-100 h-100 <?php echo e((!empty($media)) ? '' : 'd-none'); ?>">
            <img src="<?php echo e((!empty($media)) ? $media : ''); ?>" alt="" class="img-cover rounded-15">

            <div class="create-media-just-image-card__delete-btn d-flex-center cursor-pointer">
                <span class="d-flex-center p-4">
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
            </div>
        </div>

        <input type="file" name="<?php echo e($mediaName); ?>" id="<?php echo e($mediaName); ?>" class="js-create-media-just-image" accept="image/*">
    </div>

    <?php $__errorArgs = [$mediaName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="invalid-feedback d-block mt-8"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/includes/media.blade.php ENDPATH**/ ?>