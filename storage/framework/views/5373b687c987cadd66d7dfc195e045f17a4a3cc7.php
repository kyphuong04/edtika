<div class="bg-white py-16 rounded-16">
    <div class="px-16 mb-16">
        <h4 class="font-16"><?php echo e(trans('panel.conversations')); ?></h4>

        <div class="d-flex align-items-center mt-16">
            <div class="conversation-search-box flex-1 form-group d-flex align-items-center mb-0 rounded-12 bg-gray-100 py-4 px-8">
                <input type="text" name="search" class="form-control flex-1 bg-transparent border-0" value="<?php echo e(request()->get('search')); ?>" placeholder="<?php echo e(trans('public.search')); ?>">

                <button type="button" class="btn-transparent ml-8 p-4">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-search-normal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </button>
            </div>

            <div class="actions-dropdown position-relative d-flex ml-12">
                <button type="button" class="btn-transparent d-flex-center size-48 rounded-12 bg-gray-100">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-setting-4'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32 dropdown-menu-left">
                    <ul class="my-8">

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-status="all" class="js-conversation-status"><?php echo e(trans('update.all_tickets')); ?></button>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-status="replied" class="js-conversation-status"><?php echo e(trans('update.replied_tickets')); ?></button>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-status="open" class="js-conversation-status"><?php echo e(trans('update.waiting_tickets')); ?></button>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-status="close" class="js-conversation-status"><?php echo e(trans('update.closed_tickets')); ?></button>
                        </li>

                    </ul>
                </div>
            </div>

        </div>
    </div>

    <div class="support-conversation-card" data-simplebar <?php if((!empty($isRtl))): ?> data-simplebar-direction="rtl" <?php endif; ?>>

        <?php $__currentLoopData = $supports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $support): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $lastConversation = $support->conversations->first();
            ?>

            <a href="/panel/support/<?php echo e($support->id); ?>/conversations" class="js-conversation-lists js-conversation-status-<?php echo e($support->status); ?>">
                <div class="d-flex align-items-center px-16 py-12 support-conversation-item <?php echo e((!empty($selectSupport) and $selectSupport->id == $support->id) ? 'active' : ''); ?>">
                    <div class="size-48 rounded-circle mb-16 bg-gray-200">
                        <img src="<?php echo e((!empty($support->webinar) and $support->webinar->teacher_id != $authUser->id) ? $support->webinar->teacher->getAvatar() : $support->user->getAvatar()); ?>"
                             alt=""
                             class="js-avatar-img img-cover rounded-circle">
                    </div>

                    <div class="ml-8">
                        <h6 class="font-14 text-dark"><?php echo e((!empty($support->webinar) and $support->webinar->teacher_id != $authUser->id) ? $support->webinar->teacher->full_name : $support->user->full_name); ?></h6>
                        <p class="font-12 mt-6 text-gray-500"><?php echo e(truncate($support->title, 40)); ?></p>

                        <div class="d-flex align-items-center mt-8">
                            <span class="font-12 text-gray-500"><?php echo e(!empty($lastConversation) ? dateTimeFormat($lastConversation->created_at,'j M Y | H:i') : dateTimeFormat($support->created_at,'j M Y | H:i')); ?></span>
                            <span class="size-4 rounded-circle bg-gray-300 mx-8"></span>

                            <?php if($support->status == 'close'): ?>
                                <span class="badge-status rounded-8 font-10 py-4 px-6 bg-danger-30 text-danger "><?php echo e(trans('panel.closed')); ?></span>
                            <?php elseif($support->status == 'supporter_replied'): ?>
                                <span class="badge-status rounded-8 font-10 py-4 px-6 bg-primary-30 text-primary"><?php echo e(trans('panel.replied')); ?></span>
                            <?php else: ?>
                                <span class="badge-status rounded-8 font-10 py-4 px-6 bg-warning-30 text-warning"><?php echo e(trans('public.waiting')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/support/conversations/lists.blade.php ENDPATH**/ ?>