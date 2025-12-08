<div class="post-bottom-fixed-card bg-white">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between h-100">
        <div class="d-flex align-items-center">
            <div class="post-bottom-fixed-card__post-img rounded-8">
                <img src="<?php echo e($post->image); ?>" class="img-cover rounded-8" alt="<?php echo e($post->title); ?>">
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500"><?php echo e(trans('update.you_are_studing')); ?></div>
                <div class="mt-4 font-14 font-weight-bold"><?php echo e($post->title); ?></div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-24 mt-16 mt-lg-0">

            <div class="d-flex align-items-center">
                <a href="<?php echo e($post->author->getProfileUrl()); ?>" target="_blank" class="d-flex align-items-center">
                    <div class="size-40 rounded-circle">
                        <img src="<?php echo e($post->author->getAvatar(40)); ?>" alt="<?php echo e($post->author->full_name); ?>" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400"><?php echo e(trans('update.written_by')); ?></span>
                        <span class="d-block font-14 font-weight-bold text-gray-500"><?php echo e($post->author->full_name); ?></span>
                    </div>
                </a>
            </div>

            <?php if(!empty( $post->study_time )): ?>
                <div class="d-flex align-items-center pl-24 border-left-gray-200">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-clock-1'); ?>
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
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400"><?php echo e(trans('public.study_time')); ?></span>
                        <span class="d-block font-14 font-weight-bold text-gray-500"><?php echo e($post->study_time); ?> <?php echo e(trans('update.mins')); ?></span>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<div class="post-bottom-fixed-card__progress">
    <div class="progress-line"></div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/blog/show/includes/fixed_bottom.blade.php ENDPATH**/ ?>