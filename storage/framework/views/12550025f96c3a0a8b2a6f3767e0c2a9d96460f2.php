<div class="bg-white py-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark px-16"><?php echo e(trans('panel.noticeboard')); ?></h4>

    
    <?php if(!empty($unreadNoticeboards) and count($unreadNoticeboards)): ?>
        <div class="student-dashboard__noticeboards-lists px-16" data-simplebar <?php if((!empty($isRtl))): ?> data-simplebar-direction="rtl" <?php endif; ?>>
            <?php $__currentLoopData = $unreadNoticeboards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unreadNoticeboard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="js-noticeboard-card bg-gray-100 rounded-16 p-12 mt-16 position-relative cursor-pointer">
                    <div class="js-show-noticeboard-info d-none" data-id="<?php echo e($unreadNoticeboard->id); ?>"></div>
                    
                    <a href="javascript:void(0);" class="stretched-link" onclick="$(this).closest('.js-noticeboard-card').find('.js-show-noticeboard-info').trigger('click')"></a>
                    
                    <div class="bg-white p-16 rounded-12">
                        <h5 class="font-14 text-dark"><?php echo truncate($unreadNoticeboard->title, 25); ?></h5>
                        <div class="mt-8 font-12 text-gray-500"><?php echo truncate(strip_tags($unreadNoticeboard->message), 150); ?></div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-8 mt-8">
                        <div class="js-noticeboard-sender-info d-flex align-items-center">
                            <?php if($unreadNoticeboard->sender_type = 'platform'): ?>
                                <div class="d-flex-center size-40 rounded-circle bg-primary">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-teacher'); ?>
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
                            <?php else: ?>
                                <?php
                                    $senderAvatar = !empty($unreadNoticeboard->senderUser) ? $unreadNoticeboard->senderUser->getAvatar(40) : getDefaultAvatarPath();
                                ?>

                                <div class="size-40 rounded-circle">
                                    <img src="<?php echo e($senderAvatar); ?>" alt="" class="img-cover rounded-circle">
                                </div>
                            <?php endif; ?>

                            <div class="ml-8">
                                <span class="d-block font-weight-bold"><?php echo e(($unreadNoticeboard->sender_type = 'platform') ? trans('update.platform') : $unreadNoticeboard->sender); ?></span>
                                <span class="d-block font-12 text-gray-500 mt-4"><?php echo e(dateTimeFormat($unreadNoticeboard->created_at, 'j M Y H:i')); ?></span>
                            </div>
                        </div>

                        <div class="">
                            <div class="d-flex-center size-40 rounded-circle border-gray-200 bg-hover-white">
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
                            </div>
                            <input type="hidden" class="js-noticeboard-message" value="<?php echo e($unreadNoticeboard->message); ?>">
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        
        <div class="d-flex-center flex-column text-center mt-16 p-32 rounded-16 border-dashed border-gray-200 bg-gray-100">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-notification-bing'); ?>
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
            <h5 class="mt-12 font-14 text-dark"><?php echo e(trans('update.no_notice!')); ?></h5>
            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.you_don’t_have_any_notices_from_the_administrator_at_this_moment')); ?></div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts_bottom'); ?>
<script>
    "use strict";
    $(document).ready(function() {
        $(document).on('click', '.js-noticeboard-card', function(e) {
            if (!$(e.target).closest('.js-show-noticeboard-info').length) {
                $(this).find('.js-show-noticeboard-info').trigger('click');
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/includes/noticeboard.blade.php ENDPATH**/ ?>