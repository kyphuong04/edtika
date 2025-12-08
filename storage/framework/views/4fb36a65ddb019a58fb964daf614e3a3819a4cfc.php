<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark"><?php echo e(trans('update.visitors_statistics')); ?></h4>

    <?php if(!empty($visitorsStatistics['totalVisitorsCount'])): ?>
        <div class="d-flex align-items-center gap-64 mt-20">
            <div class="">
                <h6 class="font-24 text-dark"><?php echo e(shortNumbers($visitorsStatistics['totalVisitorsCount'])); ?></h6>
                <div class="text-gray-500 mt-8"><?php echo e(trans('update.total_visitors')); ?></div>
            </div>

            <div class="">
                <h6 class="font-24 text-dark"><?php echo e(shortNumbers($visitorsStatistics['monthVisitorsCount'])); ?></h6>
                <div class="text-gray-500 mt-8"><?php echo e(trans('update.month_visitors')); ?></div>
            </div>
        </div>

        
        <div id="visitorsStatisticsChart" class="instructor-dashboard__visitors-statistics-chart mt-24 pt-20 border-top-gray-100">

        </div>

        <?php $__env->startPush('scripts_bottom'); ?>
            <script>
                var visitorsLang = '<?php echo e(trans('update.visitors')); ?>'
                var instructorVisitorsChartLabels = <?php echo json_encode($visitorsStatistics['chart']['labels'], 15, 512) ?>;
                var instructorVisitorsChartData = <?php echo json_encode($visitorsStatistics['chart']['datasets'], 15, 512) ?>;
            </script>
        <?php $__env->stopPush(); ?>

        
        <?php if(!empty($visitorsStatistics['topViews']) and count($visitorsStatistics['topViews'])): ?>
            <div class="bg-gray-100 p-16 rounded-16 mt-20">
                <h5 class="font-14 text-dark"><?php echo e(trans('update.top_views')); ?></h5>

                
                <?php $__currentLoopData = $visitorsStatistics['topViews']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topView): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex align-items-center mt-16">
                        <div class="size-48 rounded-12">
                            <img src="<?php echo e($topView->getItemImage()); ?>" alt="" class="img-cover rounded-8">
                        </div>
                        <div class="ml-8">
                            <h6 class="font-14 text-dark"><?php echo e(truncate($topView->getItemTitle(), 28)); ?></h6>
                            <div class="font-12 text-gray-500 mt-4"><?php echo e(shortNumbers($topView->total)); ?></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="d-flex-center flex-column bg-gray-100 border-dashed border-gray-200 text-center mt-16 p-32 rounded-16">
            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-chart-2'); ?>
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
            <h4 class="font-14 text-dark mt-12"><?php echo e(trans('update.no_visitor!')); ?></h4>
            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.you_don’t_have_visitors_create_various_content_types_and_attract_visitors')); ?></div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h6 class="font-14 text-dark"><?php echo e(trans('update.promote_courses')); ?></h6>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.promote_your_courses_and_get_more_visitors')); ?></p>
            </div>

            <a href="" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </a>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/instructor/includes/visitors_statistics.blade.php ENDPATH**/ ?>