<a href="<?php echo e($post->getUrl()); ?>" class="text-decoration-none d-block">
    <div class="blog-section__post-card position-relative rounded-24 <?php echo e(!empty($className) ? $className : ''); ?>">
        <div class="position-relative">
            <img src="<?php echo e($post->image); ?>" alt="<?php echo e($post->title); ?>" class="blog-section__post-card-img img-cover rounded-24">
        </div>

        <div class="blog-section__post-card-footer p-16">
            <div class="d-flex flex-column justify-content-end w-100 h-100">
                <h3 class="font-16 text-white"><?php echo e($post->title); ?></h3>

                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-12">
                    <div class="d-flex align-items-center">
                        <div class="size-36 rounded-circle bg-gray-100">
                            <img src="<?php echo e($post->author->getAvatar(36)); ?>" alt="<?php echo e($post->author->ful_name); ?>" class="img-cover rounded-circle">
                        </div>
                        <div class="ml-4">
                            <h5 class="font-14 text-white"><?php echo e($post->author->full_name); ?></h5>
                            <p class="font-12 text-white mt-2"><?php echo e(trans('public.in')); ?> <?php echo e($post->category->title); ?></p>
                        </div>
                    </div>

                    <?php if(!empty($showPostStats)): ?>
                        <div class="position-relative d-inline-flex align-items-center rounded-16 px-12 py-10 bg-dark-20">
                            <div class="d-flex align-items-center">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                <span class="ml-4 font-14 text-white"><?php echo e(dateTimeFormat($post->created_at, 'j M Y')); ?></span>
                            </div>

                            <div class="blog-section__post-card-footer-divider"></div>

                            <div class="d-flex align-items-center">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-clock-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                <span class="ml-4 font-14 text-white"><?php echo e($post->study_time); ?></span>
                            </div>

                            <div class="blog-section__post-card-footer-divider"></div>

                            <div class="d-flex align-items-center">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                <span class="ml-4 font-14 text-white"><?php echo e($post->comments_count); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</a>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/blog/post_card.blade.php ENDPATH**/ ?>