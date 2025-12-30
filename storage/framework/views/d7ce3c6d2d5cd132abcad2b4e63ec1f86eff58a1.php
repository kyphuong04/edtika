<div class="bg-white p-16 rounded-24">
    <h4 class="font-14 text-dark"><?php echo e(trans('update.events_calendar')); ?></h4>
    <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.manage_your_activities_on_the_calendar')); ?></p>

    <div class="dashboard-events-calendar mt-20">
        <input type="hidden" id="inlineEventsCalender" value="">
        <div id="dashboardEventsCalendar"></div>
    </div>

    

    <div class="d-flex align-items-center justify-content-between mt-20">
        <div class="">
            <h4 class="font-14 text-dark"><?php echo e(trans('update.upcoming_events')); ?></h4>
            <p class="font-12 text-gray-500 mt-4"><?php echo e(!empty($totalEvents) ? $totalEvents : 0); ?> <?php echo e(trans('update.total_events')); ?></p>
        </div>

        <a href="/panel/events" target="_blank" class="d-flex-center size-40 rounded-circle border-gray-200 bg-hover-gray-100">
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

    <?php if(!empty($upcomingEvents) and count($upcomingEvents)): ?>
        <?php $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $upcomingEventName => $upcomingEvent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="/panel/events?date=<?php echo e($upcomingEvent['event_at']); ?>" target="_blank" class="d-flex align-items-center mt-16 p-12 rounded-16 bg-gray-100 text-dark">
                <div class="dashboard-events-calendar__day-box d-flex-center flex-column text-center rounded-8 bg-gray-200">
                    <span class="font-weight-bold text-dark"><?php echo e(dateTimeFormat($upcomingEvent['event_at'], 'j')); ?></span>
                    <span class="font-12 text-gray-400 mt-2"><?php echo e(dateTimeFormat($upcomingEvent['event_at'], 'D')); ?></span>
                </div>
                <div class="ml-8">
                    <div class=""><?php echo e(trans("update.{$upcomingEvent['title']}")); ?></div>
                    <p class="font-12 text-gray-500 mt-4"><?php echo e($upcomingEvent['subtitle']); ?></p>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php else: ?>
        
        <div class="d-flex-center flex-column text-center mt-20 border-dashed border-gray-200 bg-gray-100 p-32 rounded-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-calendar-2'); ?>
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
            <h5 class="font-14 text-dark mt-12"><?php echo e(trans('update.no_events!')); ?></h5>
            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.there_are_no_events_on_your_website')); ?></div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/includes/events_calendar.blade.php ENDPATH**/ ?>