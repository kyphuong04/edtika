<div class="learning-page__top-header d-flex align-items-center justify-content-between w-100 bg-white py-16 pl-32 pr-24">
    <div class="">
        <a href="<?php echo e($course->getUrl()); ?>" class="font-16 font-weight-bold text-dark d-flex mb-4">
            <span class=""><?php echo e($course->title); ?></span>
        </a>

        <div class="d-none d-lg-flex">
            <?php echo $__env->make('design_1.panel.includes.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    <div class="d-flex align-items-center gap-16">

        
        <div class="d-none d-lg-block">
            <?php echo $__env->make('design_1.web.courses.learning_page.includes.top_header.course_tools', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <div class="learning-page__line-separator d-none d-lg-block"></div>

        
        <?php
            $hasNotification = (!empty($course->noticeboards_count) and $course->noticeboards_count > 0);
        ?>

        <div class="position-relative d-flex-center size-48 rounded-circle <?php echo e((!empty($hasNotification)) ? 'bg-primary-20 cursor-pointer js-show-noticeboards' : 'bg-gray-100'); ?>"
             data-path="<?php echo e($course->getNoticeboardsPageUrl()); ?>"
        >
            <div class="d-flex-center size-32 rounded-circle <?php echo e((!empty($hasNotification)) ? 'bg-primary' : 'bg-gray-200'); ?>">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-notification-bing'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons '.e((!empty($hasNotification)) ? 'text-white' : 'text-gray-500').'','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>

            <?php if($hasNotification): ?>
                <span class="learning-page-notify-counter d-flex-center p-4 rounded-circle bg-danger font-12 text-white"><?php echo e($course->noticeboards_count); ?></span>
            <?php endif; ?>
        </div>

        <div class="js-toggle-show-learning-page-sidebar-drawer d-flex d-lg-none ml-16 cursor-pointer">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/top_header.blade.php ENDPATH**/ ?>