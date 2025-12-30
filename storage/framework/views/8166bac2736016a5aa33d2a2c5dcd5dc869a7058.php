<?php if($course->canSale() and !empty(getGiftsGeneralSettings('status')) and !empty(getGiftsGeneralSettings('allow_sending_gift_for_courses'))): ?>
    <a href="/gift/course/<?php echo e($course->slug); ?>" class="">
        <div class="course-show__gift-card card-with-mask position-relative mt-28">
            <div class="mask-8-white border-gray-200"></div>

            <div class="course-show__gift-card-box position-relative z-index-2 d-flex align-items-center p-16 rounded-16">
                <div class="course-show__gift-card-icon-1 d-flex-center size-56 rounded-circle">
                    <div class="course-show__gift-card-icon-2 d-flex-center size-40 rounded-circle">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-gift'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icon','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                </div>

                <div class="ml-8">
                    <h3 class="course-show__gift-card-title font-14"><?php echo e(trans('update.send_course_as_a_gift')); ?></h3>
                    <div class="course-show__gift-card-subtitle mt-4 font-12"><?php echo e(trans('update.send_it_as_gift_to_your_friends')); ?></div>
                </div>
            </div>
        </div>
    </a>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/includes/rightSide/send_gift.blade.php ENDPATH**/ ?>