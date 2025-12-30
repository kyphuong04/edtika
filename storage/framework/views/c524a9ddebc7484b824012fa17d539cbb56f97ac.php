<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark"><?php echo e(trans('panel.support_messages')); ?></h4>

    
    <?php if(!empty($supportMessages['totalTickets'])): ?>

        <div class="d-grid grid-columns-2 gap-16 mt-16">
            
            <div class="d-flex align-items-start justify-content-between bg-gray-100 rounded-16 p-16">
                <div class="">
                    <span class="d-block font-16 font-weight-bold text-dark"><?php echo e($supportMessages['openTickets']); ?></span>
                    <span class="d-block font-12 text-gray-500 mt-8"><?php echo e(trans('update.open_tickets')); ?></span>
                </div>

                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-message-notif'); ?>
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

            
            <div class="d-flex align-items-start justify-content-between bg-gray-100 rounded-16 p-16">
                <div class="">
                    <span class="d-block font-16 font-weight-bold text-dark"><?php echo e($supportMessages['totalTickets']); ?></span>
                    <span class="d-block font-12 text-gray-500 mt-8"><?php echo e(trans('update.total_tickets')); ?></span>
                </div>

                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-messages'); ?>
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

        <?php if(!empty($supportMessages['supports']) and count($supportMessages['supports'])): ?>
            <div class="student-dashboard__support-messages" data-simplebar <?php if((!empty($isRtl))): ?> data-simplebar-direction="rtl" <?php endif; ?>>
                <?php $__currentLoopData = $supportMessages['supports']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $support): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-gray-100 rounded-16 p-12 pb-20 mt-16">
                        <div class="bg-white p-12 rounded-12">
                            <?php
                                $supportUser = $support->user;
                                $lastConversation = $support->conversations->first();
                            ?>

                            <div class="d-flex align-items-center">
                                <div class="size-40 rounded-circle">
                                    <img src="<?php echo e($supportUser->getAvatar(40)); ?>" alt="" class="img-cover rounded-circle">
                                </div>
                                <div class="ml-8">
                                    <h5 class="font-14 text-dark"><?php echo e(truncate($support->title, 25)); ?></h5>

                                    <div class="d-flex align-items-center gap-8 font-12 text-gray-500 mt-4">
                                        <span class="font-weight-bold"><?php echo e($supportUser->full_name); ?></span>
                                        <span class="size-4 rounded-16 bg-gray-300"></span>
                                        <span class=""><?php echo e(dateTimeFormat($support->created_at, 'j M Y H:i')); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-20 font-12 text-gray-500 white-space-pre-wrap"><?php echo e(truncate($lastConversation->message, 100)); ?></div>
                        </div>

                        <div class="d-flex align-items-center mt-20">
                            <?php if(!empty($support->department)): ?>
                                <div class="size-32 rounded-8 bg-gray-100">
                                    <img src="<?php echo e(getPlatformLogo()); ?>" alt="" class="img-cover rounded-8">
                                </div>
                                <div class="ml-4 font-12 text-gray-500"><?php echo e($support->department->title); ?></div>
                            <?php elseif(!empty($support->webinar)): ?>
                                <div class="size-32 rounded-8 bg-gray-100">
                                    <img src="<?php echo e($support->webinar->getIcon()); ?>" alt="" class="img-cover rounded-8">
                                </div>
                                <div class="ml-4 font-12 text-gray-500"><?php echo e($support->webinar->title); ?></div>
                            <?php endif; ?>
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
<?php $component->withName('iconsax-bul-messages'); ?>
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
            <h4 class="font-14 text-dark mt-12"><?php echo e(trans('update.no_support_ticket!')); ?></h4>
            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.you_can_enable_support_for_your_courses_and_encourage_students')); ?></div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/instructor/includes/support_messages.blade.php ENDPATH**/ ?>