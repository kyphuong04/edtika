<div class="js-show-message card-with-dashed-mask d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-12 rounded-16 mt-20 cursor-pointer <?php echo e(!empty($notification->notificationStatus) ? 'js-seen-at' : ''); ?>"
     data-id="<?php echo e($notification->id); ?>"
     id="showNotificationMessage<?php echo e($notification->id); ?>"
>
    <div class="d-flex align-items-center">

        <div class="position-relative d-flex-center size-56 rounded-12 bg-primary">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-notification-bing'); ?>
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

            <?php if(empty($notification->notificationStatus)): ?>
                <span class="notification-badge"></span>
            <?php endif; ?>
        </div>

        <div class="ml-12">
            <h6 class="js-notification-title font-14 font-weight-bold"><?php echo e($notification->title); ?></h6>
            <p class="mt-4 font-12 text-gray-500"><?php echo truncate($notification->message, 150, true); ?></p>
        </div>
    </div>

    <span class="js-notification-time text-gray-500 mt-16 mt-lg-0"><?php echo e(dateTimeFormat($notification->created_at, 'j M Y | H:i')); ?></span>

    <input type="hidden" class="js-notification-message" value="<?php echo $notification->message; ?>">
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/notifications/notif_card.blade.php ENDPATH**/ ?>