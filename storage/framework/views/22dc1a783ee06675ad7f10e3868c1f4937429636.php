<?php
    $getPanelSidebarSettings = getPanelSidebarSettings();
?>


<div id="panelSidebar" class="panel-sidebar panel-sidebar--collapsed bg-white">
    <div class="panel-sidebar__contents bg-white <?php echo e((empty($getPanelSidebarSettings) or empty($getPanelSidebarSettings['background'])) ? 'without-bottom-image' : ''); ?>" data-simplebar <?php if((!empty($isRtl))): ?> data-simplebar-direction="rtl" <?php endif; ?>>

        <div class="js-show-panel-sidebar cursor-pointer d-flex d-lg-none">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-dark close-icon','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>

        <div id="sidebarAccordions" class="pb-240">
            
            <?php echo $__env->make('design_1.panel.includes.sidebar.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>


    

</div>

<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/includes/sidebar.blade.php ENDPATH**/ ?>