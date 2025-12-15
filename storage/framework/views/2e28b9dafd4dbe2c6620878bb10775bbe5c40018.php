<?php $__currentLoopData = \App\Mixins\Panel\SidebarItems::getItems(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarSection => $sidebarMenus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(!empty($sidebarMenus) and count($sidebarMenus)): ?>
        <div class="mt-16">
            <span class="d-block font-12 font-weight-bold text-gray-400 text-uppercase pl-32 pr-20 mb-8"><?php echo e(trans("update.{$sidebarSection}")); ?></span>

            <?php $__currentLoopData = $sidebarMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarMenuName => $sidebarMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isActiveMainManu = false;
                    $mainUrl = str_replace('/panel', 'panel', $sidebarMenu['url']);

                    if ($mainUrl == "panel" and request()->path() == "panel") {
                        $isActiveMainManu = true;
                    } else if ($mainUrl != "panel") {
                        $isActiveMainManu = (request()->is($mainUrl) or request()->is($mainUrl.'*'));

                        if (!$isActiveMainManu and !empty($sidebarMenu['extraUrl'])) {
                            $extraUrl = str_replace('/panel', 'panel', $sidebarMenu['extraUrl']);
                            $isActiveMainManu = (request()->is($extraUrl) or request()->is($extraUrl.'*'));
                        }
                    }
                ?>

                <?php if(!empty($sidebarMenu['items'])): ?>
                    <div class="accordion ">
                        <div class="panel-sidebar__menu accordion__title d-flex align-items-center justify-content-between pl-32 pr-20 <?php echo e(($isActiveMainManu) ? 'sidenav-item-active' : ''); ?>">
                            <div class="d-flex align-items-center flex-1 cursor-pointer" href="#collapseSidebar<?php echo e($sidebarMenuName); ?>" data-parent="#sidebarAccordions" role="button" data-toggle="collapse">
                                <span class=" <?php echo e($isActiveMainManu ? 'text-primary' : 'text-gray-500'); ?>"><?php echo $sidebarMenu['icon']; ?></span>
                                <span class="ml-8 font-14 <?php echo e($isActiveMainManu ? 'text-primary' : 'text-dark'); ?>"><?php echo e($sidebarMenu['text']); ?></span>
                            </div>

                            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseSidebar<?php echo e($sidebarMenuName); ?>" data-parent="#sidebarAccordions" role="button" data-toggle="collapse">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '12px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </span>
                        </div>

                        <div id="collapseSidebar<?php echo e($sidebarMenuName); ?>" class="accordion__collapse border-top-0 pt-0 mt-0 <?php echo e($isActiveMainManu ? 'show' : ''); ?>" role="tabpanel">

                            <?php $__currentLoopData = $sidebarMenu['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarMenuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $itemUrl = str_replace('/panel', 'panel', $sidebarMenuItem['url']);
                                    $isActiveItemManu = (request()->is($itemUrl));
                                ?>

                                <a href="<?php echo e($sidebarMenuItem['url']); ?>" class="d-flex align-items-center panel-sidebar__menu-item text-gray-500 font-14 pl-32 pr-20 <?php echo e(($isActiveItemManu) ? 'text-primary' : ''); ?>">
                                    <span class=""><?php echo e($sidebarMenuItem['text']); ?></span>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e($sidebarMenu['url']); ?>" class="panel-sidebar__menu d-flex align-items-center justify-content-between pl-32 pr-20 <?php echo e(($isActiveMainManu) ? 'sidenav-item-active' : ''); ?>">
                        <div class="d-flex align-items-center flex-1 cursor-pointer">
                            <span class=" <?php echo e($isActiveMainManu ? 'text-primary' : (!empty($sidebarMenu['className']) ? $sidebarMenu['className'] : 'text-gray-500')); ?>"><?php echo $sidebarMenu['icon']; ?></span>
                            <span class="ml-8 font-14 <?php echo e($isActiveMainManu ? 'text-primary' : (!empty($sidebarMenu['className']) ? $sidebarMenu['className'] : 'text-dark')); ?>"><?php echo e($sidebarMenu['text']); ?></span>
                        </div>

                    </a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/includes/sidebar/items.blade.php ENDPATH**/ ?>