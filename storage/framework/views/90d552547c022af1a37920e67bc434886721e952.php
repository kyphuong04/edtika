<div class="post-author-info-card position-relative mt-32 mt-lg-60">
    <div class="post-author-info-card__mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row align-items-start gap-lg-24 bg-white px-16 rounded-24 z-index-3">
        <div class="post-author-info-card__details d-flex flex-column flex-1 py-16">
            <div class="d-flex align-items-center <?php echo e(empty($post->author->about) ? 'mb-24' : ''); ?>">
                <div class="d-flex-center size-80 rounded-12 bg-gray-200">
                    <a href="<?php echo e($post->author->getProfileUrl()); ?>" target="_blank">
                        <img src="<?php echo e($post->author->getAvatar(80)); ?>" alt="<?php echo e($post->author->full_name); ?>" class="img-cover rounded-12">
                    </a>
                </div>
                <div class="ml-12 flex-1">
                    <h6 class="font-14 font-weight-bold text-dark">
                        <a href="<?php echo e($post->author->getProfileUrl()); ?>" target="_blank" class="text-dark"><?php echo e($post->author->full_name); ?></a>
                    </h6>

                    <?php
                        $authorRates = $post->author->rates(true);
                    ?>

                    <?php if(!empty($authorRates['rate'])): ?>
                        <?php echo $__env->make('design_1.web.components.rate', [
                            'rate' => $authorRates['rate'],
                            'rateCount' => $authorRates['count'],
                            'rateClassName' => 'mt-4',
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>

                    <div class="d-flex align-items-center gap-12 mt-8">
                        <div class="d-flex align-items-center p-8 rounded-16 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-video-play'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <span class="mx-4 font-weight-bold"><?php echo e($post->author->getTeacherCoursesCount()); ?></span>
                            <span class=""><?php echo e(trans('update.courses')); ?></span>
                        </div>

                        <div class="d-flex align-items-center p-8 rounded-16 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <span class="mx-4 font-weight-bold"><?php echo e($post->author->blog_count); ?></span>
                            <span class=""><?php echo e(trans('update.articles')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(!empty($post->author->about)): ?>
                <div class="post-author-info-card__details-about my-16 text-gray-500 <?php echo e((!empty($post->author->someRandomPosts) and count($post->author->someRandomPosts)) ? 'mb-16' : ''); ?>"><?php echo $post->author->about; ?></div>
            <?php endif; ?>

            <?php if(!empty($post->author->someRandomPosts) and count($post->author->someRandomPosts)): ?>
                <div class="mt-auto">
                    <h5 class="font-14"><?php echo e(trans('update.more_from_user', ['user' => $post->author->full_name])); ?></h5>
                    <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-24 mt-8 ">
                        <?php $__currentLoopData = $post->author->someRandomPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $authorPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center">
                                <div class="author-random-post-image rounded-8 bg-gray-200">
                                    <img src="<?php echo e($authorPost->image); ?>" alt="<?php echo e($authorPost->title); ?>" class="img-cover rounded-8">
                                </div>
                                <div class="ml-8">
                                    <a href="<?php echo e($authorPost->getUrl()); ?>">
                                        <h3 class="font-14 text-dark"><?php echo e(truncate($authorPost->title, 30)); ?></h3>
                                    </a>
                                    <span class="mt-8 font-12 text-gray-500"><?php echo e(dateTimeFormat($authorPost->created_at, 'j M Y')); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="post-author-info-card__secondary-img position-relative">
            <img src="<?php echo e($post->author->getProfileSecondaryImage()); ?>" alt="<?php echo e($post->author->full_name); ?>" class="img-cover">
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/blog/show/includes/author_info.blade.php ENDPATH**/ ?>