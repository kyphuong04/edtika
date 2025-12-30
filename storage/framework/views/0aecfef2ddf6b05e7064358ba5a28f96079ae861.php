<?php
    $getPanelSidebarSettings = getPanelSidebarSettings();
?>


<div id="panelSidebar" class="panel-sidebar bg-white">
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

        <div class="d-flex-center flex-column mt-20 mt-lg-36">
            <div class="panel-sidebar__user-avatar size-64 rounded-circle">
                <img src="<?php echo e($authUser->getAvatar(56)); ?>" alt="<?php echo e($authUser->full_name); ?>" class="img-cover rounded-circle">
            </div>

            <h4 class="font-14 font-weight-bold text-dark mt-8"><?php echo e($authUser->full_name); ?></h4>

            <?php if(!$authUser->isUser()): ?>
                <?php echo $__env->make('design_1.web.components.rate', ['rate' => $authUser->rates(), 'rateClassName' => 'mt-4'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <div class="d-flex align-items-center justify-content-around mt-12 rounded-10 bg-gray p-8">
                <?php if($authUser->isUser()): ?>
                    <div class="d-flex flex-column align-items-center">
                        <span class="font-12 font-weight-bold"><?php echo e(count($authUser->getPurchasedCoursesIds())); ?></span>
                        <span class="font-12 text-gray-500"><?php echo e(trans('panel.classes')); ?></span>
                    </div>

                    <div class="gray-card-divider mx-16"></div>

                    <div class="d-flex flex-column align-items-center">
                        <span class="font-12 font-weight-bold"><?php echo e($authUser->following()->count()); ?></span>
                        <span class="font-12 text-gray-500"><?php echo e(trans('panel.following')); ?></span>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column align-items-center">
                        <span class="font-12 font-weight-bold"><?php echo e($authUser->webinars()->count()); ?></span>
                        <span class="font-12 text-gray-500"><?php echo e(trans('panel.classes')); ?></span>
                    </div>

                    <div class="gray-card-divider mx-16"></div>

                    <div class="d-flex flex-column align-items-center">
                        <span class="font-12 font-weight-bold"><?php echo e($authUser->followers()->count()); ?></span>
                        <span class="font-12 text-gray-500"><?php echo e(trans('panel.followers')); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="sidebarAccordions" class="pb-24">
            
            <?php echo $__env->make('design_1.panel.includes.sidebar.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>


    <?php if(!empty($getPanelSidebarSettings) and !empty($getPanelSidebarSettings['background'])): ?>
        <div class="panel-sidebar__bottom-banner bg-white d-none d-md-block mb-32">
            <a href="<?php echo e(!empty($getPanelSidebarSettings['link']) ? $getPanelSidebarSettings['link'] : ''); ?>" class="">
                <img src="<?php echo e(!empty($getPanelSidebarSettings['background']) ? $getPanelSidebarSettings['background'] : ''); ?>" alt="" class="img-fluid">
            </a>
        </div>
    <?php endif; ?>

</div>

<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/includes/sidebar.blade.php ENDPATH**/ ?>