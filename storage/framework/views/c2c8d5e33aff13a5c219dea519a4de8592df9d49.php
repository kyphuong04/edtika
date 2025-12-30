<div class="language-select position-relative">
    <div class="size-32 position-relative d-flex-center bg-gray-100 rounded-8">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>

        <?php if(!empty($unReadNotifications) and count($unReadNotifications)): ?>
            <span class="panel-header__badge-counter badge-counter"><?php echo e(count($unReadNotifications)); ?></span>
        <?php endif; ?>
    </div>

    <div class="language-dropdown language-dropdown__notifications py-12">
        <?php if(!empty($unReadNotifications) and count($unReadNotifications)): ?>
            <div class="px-12">

                <div class="d-flex align-items-center p-12 rounded-12 bg-gray-100">
                    <div class="d-flex-center size-48 bg-white rounded-circle">
                        <div class="d-flex-center size-40 bg-primary rounded-circle">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-notification-bing'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-14"><?php echo e(count($unReadNotifications)); ?> <?php echo e(trans('panel.notifications')); ?></h5>
                        <a href="/panel/notifications/mark-all-as-read" class="delete-action d-block mt-4 font-12 cursor-pointer text-gray-500" data-msg="<?php echo e(trans('update.convert_unread_messages_to_read')); ?>" data-confirm="<?php echo e(trans('update.yes_convert')); ?>">
                            <?php echo e(trans('update.mark_as_read')); ?>

                        </a>
                    </div>
                </div>

            </div>

            <?php $__currentLoopData = $unReadNotifications->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unReadNotification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="/panel/notifications?notification=<?php echo e($unReadNotification->id); ?>" class="language-dropdown__item  d-flex align-items-center w-100 px-16 py-8 text-dark bg-transparent">
                    <div class="">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-notification'); ?>
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
                    <div class="ml-8">
                        <h4 class="font-12"><?php echo e($unReadNotification->title); ?></h4>
                        <span class="d-block text-gray-500 font-12 mt-8"><?php echo e(dateTimeFormat($unReadNotification->created_at, 'j M Y | H:i')); ?></span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="px-12">
                <a href="/panel/notifications" class="btn btn-lg btn-primary btn-block mt-12"><?php echo e(trans('notification.all_notifications')); ?></a>
            </div>
        <?php else: ?>
            <div class="d-flex-center flex-column text-center px-16 py-54">
                <div class="d-flex-center size-40 bg-primary rounded-circle">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-notification-bing'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>

                <span class="mt-12 text-gray-500"><?php echo e(trans('notification.empty_notifications')); ?></span>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/includes/header/notification.blade.php ENDPATH**/ ?>