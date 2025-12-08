<?php if(!empty($dayEvents) and !empty($dayEvents['total'])): ?>
    <?php
        $icons = [
            'courses_expirations' => 'video-play',
            'quiz_expirations' => 'clipboard-tick',
            'live_sessions' => 'video',
            'assignment_expirations' => 'note',
            'bundle_expirations' => 'box',
            'subscription_expirations' => 'crown',
            'registration_package_expirations' => 'cup',
            'installments' => 'graph',
            'meetings' => 'profile-2user',
            'live_class_start' => 'video',
        ];
    ?>


    <div class="bg-white p-16 rounded-24">
        <div class="pb-6 border-bottom-gray-100">
            <h3 class="d-flex align-items-center font-14 font-weight-bold text-dark"><?php echo e(trans('update.events_for')); ?> <span class="js-selected-date ml-4"><?php echo e(dateTimeFormat(!empty($dayTimestamp) ? $dayTimestamp : time(), 'j M Y')); ?></span></h3>
            <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.check_events_and_add_them_to_reminder')); ?></p>
        </div>

        
        <?php $__currentLoopData = $dayEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventName => $dayEventItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!empty($dayEventItems) and is_array($dayEventItems)): ?>
                <?php $__currentLoopData = $dayEventItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($event) and is_array($event)): ?>
                        <?php
                            $icon = $icons[$eventName];
                        ?>

                        <div class="d-flex align-items-center justify-content-between bg-gray-100 p-12 rounded-16 mt-16">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-48 rounded-8 bg-gray-200">
                                    <?php echo e(svg("iconsax-bul-{$icon}", ['height' => '24px', 'width' => '24px', 'class' => 'icons text-primary'])); ?>
                                </div>
                                <div class="ml-8">
                                    <div class=""><?php echo e(trans("update.{$eventName}")); ?></div>
                                    <p class="font-12 text-gray-500 mt-4"><?php echo e($event['subtitle']); ?></p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-16">
                                <?php if(!empty($event['time'])): ?>
                                    <div class="d-inline-flex p-8 rounded-8 bg-gray-200 font-12 text-gray-500"><?php echo e($event['time']); ?></div>
                                <?php endif; ?>

                                <a href="<?php echo e($event['add_to_calendar_url']); ?>" target="_blank" class="d-flex-center size-40 bg-white rounded-circle bg-hover-gray-200">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-notification-bing'); ?>
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
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <?php echo $__env->make('design_1.panel.includes.no-result',[
        'file_name' => 'events.svg',
        'title' => trans('update.events_no_result'),
        'hint' => nl2br(trans('update.events_no_result_hint')),
        'extraClass' => 'mt-0',
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/events/day_events.blade.php ENDPATH**/ ?>