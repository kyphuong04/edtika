<div class="js-noticeboards-drawer noticeboards-drawer bg-white py-16">
    <div class="d-flex align-items-center pb-16 border-bottom-gray-bg px-16">
        <button type="button" class="js-noticeboards-drawer-close d-flex btn-transparent">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '25px','height' => '25px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </button>

        <span class="font-14 font-weight-bold ml-8"><?php echo e(trans("update.course_notices")); ?> (<?php echo e($course->noticeboards_count); ?>)</span>
    </div>

    <div class="noticeboards-drawer__body p-16" data-simplebar <?php if((!empty($isRtl) and $isRtl)): ?> data-simplebar-direction="rtl" <?php endif; ?>>
        <div id="noticeboardsDrawerBody"></div>
    </div>
</div>
<div class="noticeboards-drawer-mask"></div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/noticeboards/index.blade.php ENDPATH**/ ?>