<div class="bg-white p-16 rounded-24 w-100 mt-24">
    <h4 class="font-14 font-weight-bold text-dark"><?php echo e(trans('update.sales_overview')); ?></h4>

    <?php if(!empty($salesOverview['hasSalesOverviewData'])): ?>
        <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16">
            
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12"><?php echo e(trans('update.course_sales')); ?></span>
                    <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e(!empty($salesOverview['totalCourseSales']) ? handlePrice($salesOverview['totalCourseSales']) : '-'); ?></span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
            </div>

            
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12"><?php echo e(trans('update.product_sales')); ?></span>
                    <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e(!empty($salesOverview['totalProductSales']) ? handlePrice($salesOverview['totalProductSales']) : '-'); ?></span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-success-40">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-box-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
            </div>

            
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12"><?php echo e(trans('update.meeting_sales')); ?></span>
                    <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e(!empty($salesOverview['totalMeetingSales']) ? handlePrice($salesOverview['totalMeetingSales']) : '-'); ?></span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-warning-40">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="d-flex align-items-center gap-40 gap-lg-80 mt-24 py-20 border-top-gray-100 border-bottom-gray-100">
            
            <div class="">
                <span class="d-block font-24 font-weight-bold text-dark"><?php echo e(!empty($salesOverview['monthSalesAmount']) ? handlePrice($salesOverview['monthSalesAmount']) : '-'); ?></span>
                <span class="d-block text-gray-500 mt-8"><?php echo e(trans('update.month_sales')); ?></span>
            </div>

            
            <div class="">
                <span class="d-block font-24 font-weight-bold text-dark"><?php echo e(!empty($salesOverview['yearSalesAmount']) ? handlePrice($salesOverview['yearSalesAmount']) : '-'); ?></span>
                <span class="d-block text-gray-500 mt-8"><?php echo e(trans('update.year_sales')); ?></span>
            </div>

            
            <div class="">
                <span class="d-block font-24 font-weight-bold text-dark"><?php echo e(!empty($salesOverview['totalSalesAmount']) ? handlePrice($salesOverview['totalSalesAmount']) : '-'); ?></span>
                <span class="d-block text-gray-500 mt-8"><?php echo e(trans('financial.total_sales')); ?></span>
            </div>
        </div>

        
        <div id="instructorSalesOverviewChart" class="instructor-dashboard__sales-overview-chart mt-24"></div>

        <?php $__env->startPush('scripts_bottom'); ?>
            <script>
                var courseSalesLang = '<?php echo e(trans('update.course_sales')); ?>';
                var meetingSalesLang = '<?php echo e(trans('update.meeting_sales')); ?>';
                var productSalesLang = '<?php echo e(trans('update.product_sales')); ?>';
                var instructorSalesOverviewChartLabels = <?php echo json_encode($salesOverview['chart']['labels'], 15, 512) ?>;
                var instructorSalesOverviewChartCourseSales = <?php echo json_encode($salesOverview['chart']['courseSales'], 15, 512) ?>;
                var instructorSalesOverviewChartMeetingSales = <?php echo json_encode($salesOverview['chart']['meetingSales'], 15, 512) ?>;
                var instructorSalesOverviewChartProductSales = <?php echo json_encode($salesOverview['chart']['productSales'], 15, 512) ?>;
            </script>
        <?php $__env->stopPush(); ?>

    <?php else: ?>
        
        <div class="d-flex-center flex-column text-center bg-gray-100 border-dashed border-gray-200 rounded-16 mt-16 p-60">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-money-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>

            <h4 class="mt-12 font-14 text-dark"><?php echo e(trans('update.no_sale!')); ?></h4>
            <div class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.instructor_dashboard_sales_overview_no_sales_hint')); ?></div>

            <div class="d-grid grid-columns-2 gap-8 mt-28 p-8 rounded-16 bg-white">
                
                <a href="/panel/courses/new" target="_blank" class="btn btn-xlg border-dashed border-gray-200 rounded-16 bg-white bg-hover-gray-100">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-play-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-8 text-dark"><?php echo e(trans('update.create_a_course')); ?></span>
                </a>

                
                <a href="/panel/store/products/new" target="_blank" class="btn btn-xlg border-dashed border-gray-200 rounded-16 bg-white bg-hover-gray-100">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-8 text-dark"><?php echo e(trans('update.create_a_product')); ?></span>
                </a>

                
                <a href="/panel/bundles/new" target="_blank" class="btn btn-xlg border-dashed border-gray-200 rounded-16 bg-white bg-hover-gray-100">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-box-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-8 text-dark"><?php echo e(trans('update.create_a_bundle')); ?></span>
                </a>

                
                <a href="/panel/meetings/settings" target="_blank" class="btn btn-xlg border-dashed border-gray-200 rounded-16 bg-white bg-hover-gray-100">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-profile-2user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-8 text-dark"><?php echo e(trans('update.meeting_settings')); ?></span>
                </a>

            </div>
        </div>
    <?php endif; ?>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/instructor/includes/sales_overview.blade.php ENDPATH**/ ?>