<?php if(!empty($featured['course'])): ?>
    <?php
        $featuredCourseItem = \App\Models\Webinar::query()->where('id', $featured['course'])->where('status', 'active')->first();
    ?>

    <?php if(!empty($featuredCourseItem)): ?>
        <div class="featured-courses-section__item-card position-relative rounded-32 bg-gray-100">
            <a href="<?php echo e($featuredCourseItem->getUrl()); ?>" class="text-decoration-none d-block">
                <?php if(!empty($featured['cover_image'])): ?>
                    <img src="<?php echo e($featured['cover_image']); ?>" alt="cover" class="img-cover rounded-32">
                <?php endif; ?>

                <?php if(!empty($featured['overlay_image']) and empty($disableOverlayImage)): ?>
                    <div class="featured-courses-section__item-card-overlay-image d-flex-center">
                        <img src="<?php echo e($featured['overlay_image']); ?>" alt="overlay" class="img-fluid">
                    </div>
                <?php endif; ?>
            </a>

            <div class="featured-courses-section__item-card-course-box d-flex flex-column bg-white rounded-16 p-16 text-left">
                <a href="<?php echo e($featuredCourseItem->getUrl()); ?>" class="text-decoration-none">
                    <h3 class="font-24 text-dark"><?php echo e($featuredCourseItem->title); ?></h3>
                </a>

                <?php echo $__env->make('design_1.web.components.rate', ['rate' => $featuredCourseItem->getRate(), 'showRateStars' => true, 'rateClassName' => 'mt-16'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="d-flex align-items-center gap-8 mt-16">
                    <a href="<?php echo e($featuredCourseItem->teacher->getProfileUrl()); ?>" target="_blank" class="size-48 rounded-circle bg-gray-100">
                        <img src="<?php echo e($featuredCourseItem->teacher->getAvatar(48)); ?>" alt="<?php echo e($featuredCourseItem->teacher->full_name); ?>" class="img-cover rounded-circle">
                    </a>
                    <div class="">
                        <a href="<?php echo e($featuredCourseItem->teacher->getProfileUrl()); ?>" target="_blank" class="">
                            <h5 class="font-16 text-dark"><?php echo e($featuredCourseItem->teacher->full_name); ?></h5>
                        </a>

                        <div class="d-inline-flex align-items-center gap-4 mt-2 font-14 text-gray-500">
                            <span class=""><?php echo e(trans('public.in')); ?></span>
                            <a href="<?php echo e($featuredCourseItem->category->getUrl()); ?>" target="_blank" class="font-14 text-gray-500"><?php echo e($featuredCourseItem->category->title); ?></a>
                        </div>
                    </div>
                </div>

                <a href="<?php echo e($featuredCourseItem->getUrl()); ?>" class="text-decoration-none">
                    <div class="featured-courses-section__item-card-course-box-description p-16 mt-16 rounded-16 border-gray-100" style="cursor: pointer;">
                        <div class="text-gray-500 font-16"><?php echo e($featuredCourseItem->summary); ?></div>

                        <?php if(!empty($featured['checked_items']) and is_array($featured['checked_items'])): ?>
                            <div class="mt-16">
                                <h4 class="font-16 text-dark"><?php echo e(trans('update.this_course_includes')); ?>:</h4>

                                <?php $__currentLoopData = $featured['checked_items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checkedItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex align-items-center mt-12">
                                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tick-icon','data' => ['class' => 'icons text-success','width' => '16px','height' => '16px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('tick-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                        <span class="ml-4 font-14 text-gray-500"><?php echo e($checkedItem); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>

                <div class="d-flex align-items-center justify-content-between mt-auto">
                    <a href="<?php echo e($featuredCourseItem->getUrl()); ?>" class="text-decoration-none">
                        <div class="d-flex-center p-8 rounded-8 bg-gray-100 text-gray-500" style="cursor: pointer;">
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
                            <span class="ml-4vfont-16"><?php echo e(convertMinutesToHourAndMinute($featuredCourseItem->duration)); ?> <?php echo e(trans('home.hours')); ?></span>
                        </div>
                    </a>

                    <a href="<?php echo e($featuredCourseItem->getUrl()); ?>" class="text-decoration-none">
                        <div class="d-flex align-items-end gap-12 font-24 text-primary" style="cursor: pointer;">
                            <?php if(!empty($featuredCourseItem->price) and $featuredCourseItem->price > 0): ?>
                                <?php if($featuredCourseItem->bestTicket() < $featuredCourseItem->price): ?>
                                    <span class="text-decoration-line-through text-gray-500 font-16"><?php echo e(($featuredCourseItem->bestTicket() > 0) ? handlePrice($featuredCourseItem->bestTicket(), true, true, false, null, true) : trans('public.free')); ?></span>
                                    <span class="font-weight-bold"><?php echo e(handlePrice($featuredCourseItem->price, true, true, false, null, true)); ?></span>
                                <?php else: ?>
                                    <span class="font-weight-bold"><?php echo e(handlePrice($featuredCourseItem->price, true, true, false, null, true)); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="font-weight-bold"><?php echo e(trans('public.free')); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/featured_courses/featured_card.blade.php ENDPATH**/ ?>