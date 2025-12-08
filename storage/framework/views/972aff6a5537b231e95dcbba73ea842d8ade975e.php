<div class="blog-show-body__header position-relative px-24">
    <div class="blog-show-body__header-mask"></div>

    <div class="position-relative bg-white p-24 rounded-32 z-index-2">
        <div class="d-flex align-items-center text-white">
            <a href="/" class="text-gray-500"><?php echo e(getPlatformName()); ?></a>
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500 mx-2','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            <a href="/blog" class="text-gray-500"><?php echo e(trans('home.blog')); ?></a>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-12 mt-12">
            <h1 class="font-24 "><?php echo e($post->title); ?></h1>

            <?php
                $postBadges = $post->allBadges();
            ?>

            <?php if(count($postBadges)): ?>
                <div class="d-flex flex-wrap align-items-center gap-12">
                    <?php $__currentLoopData = $postBadges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $postBadge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-inline-flex align-items-center gap-4 p-4 pr-8 rounded-32 font-12" style="background-color: <?php echo e($postBadge->background); ?>; color: <?php echo e($postBadge->color); ?>;">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-note-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <span class=""><?php echo e($postBadge->title); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
        <p class="mt-12 font-12 text-gray-500"><?php echo e($post->subtitle); ?></p>

        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-24">
            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center">

                <a href="<?php echo e($post->author->getProfileUrl()); ?>" target="_blank" class="d-flex align-items-center">
                    <div class="size-40 rounded-circle">
                        <img src="<?php echo e($post->author->getAvatar(40)); ?>" alt="<?php echo e($post->author->full_name); ?>" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400"><?php echo e(trans('update.written_by')); ?></span>
                        <span class="d-block font-weight-bold text-gray-500 mt-2"><?php echo e($post->author->full_name); ?></span>
                    </div>
                </a>

                <div class="blog-show-body__header-first-item-line ml-lg-24 pl-lg-12 d-flex align-items-center mt-16 mt-lg-0">
                    <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
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
                        <span class="d-block font-12 text-gray-400"><?php echo e(trans('update.published_on')); ?></span>
                        <span class="d-block font-weight-bold text-gray-500 mt-2"><?php echo e(dateTimeFormat($post->created_at, 'j M Y')); ?></span>
                    </div>
                </div>

                <?php if(!empty($post->study_time)): ?>
                    <div class="d-flex align-items-center ml-lg-24 mt-16 mt-lg-0">
                        <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
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
                            <span class="d-block font-weight-bold text-gray-500 mt-2"><?php echo e($post->study_time); ?> <?php echo e(trans('update.mins')); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-flex align-items-center ml-lg-24 mt-16 mt-lg-0">
                    <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-2'); ?>
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
                        <span class="d-block font-12 text-gray-400"><?php echo e(trans('public.category')); ?></span>
                        <a href="<?php echo e($post->category->getUrl()); ?>" class="d-block font-weight-bold text-gray-500 mt-2"><?php echo e($post->category->title); ?></a>
                    </div>
                </div>

            </div>

            <div class="js-share-post d-flex-center size-40 bg-gray-100 rounded-circle cursor-pointer mt-16 mt-lg-0"
                 data-path="/blog/<?php echo e($post->slug); ?>/share-modal" data-tippy-content="<?php echo e(trans('update.share_this_post_with_others')); ?>"
            >
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-share'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>

        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/blog/show/includes/header.blade.php ENDPATH**/ ?>