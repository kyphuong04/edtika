<div class="profile-card-has-mask bg-white py-16 rounded-24 w-100">
    <div class="d-flex-center flex-column text-center px-16">

        <div class="profile-avatar-card size-80 rounded-circle mt-32">
            <img src="<?php echo e($user->getAvatar(80)); ?>" alt="<?php echo e($user->full_name); ?>" class="img-cover rounded-circle">

            <?php if($user->verified): ?>
                <div class="profile-avatar-card__verified-badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="<?php echo e(trans('public.verified')); ?>">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tick-icon','data' => ['class' => 'icons text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('tick-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <h4 class="mt-16 font-16 font-weight-bold"><?php echo e($user->full_name); ?></h4>

        <?php echo $__env->make('design_1.web.components.rate', ['rate' => $userRates['rate'], 'rateCount' => $userRates['count'], 'rateClassName' => 'mt-8'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="profile-followers-card position-relative d-flex align-items-center justify-content-around mt-16 w-100">
            <div class="flex-1 text-center">
                <span class="d-block font-14 font-weight-bold"><?php echo e(shortNumbers($userFollowers->count())); ?></span>
                <span class="d-block mt-4 font-12 text-gray-500"><?php echo e(trans('panel.followers')); ?></span>
            </div>

            <div class="flex-1 text-center">
                <span class="d-block font-14 font-weight-bold"><?php echo e(shortNumbers($userFollowing->count())); ?></span>
                <span class="d-block mt-4 font-12 text-gray-500"><?php echo e(trans('panel.following')); ?></span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-12 mt-16 w-100">
            <button type="button" id="followToggle" data-user-id="<?php echo e($user->username); ?>" class="btn btn-<?php echo e((!empty($authUserIsFollower)) ? 'danger' : 'primary'); ?> btn-lg flex-1">
                <?php if(!empty($authUserIsFollower)): ?>
                    <?php echo e(trans('panel.unfollow')); ?>

                <?php else: ?>
                    <?php echo e(trans('panel.follow')); ?>

                <?php endif; ?>
            </button>

            <?php if($user->public_message): ?>
                <button type="button" class="js-send-message d-flex-center size-48 rounded-12 border-2 border-gray-400 bg-white bg-hover-gray-100" data-path="/users/<?php echo e($user->getUsername()); ?>/get-send-message-form" data-tippy-content="<?php echo e(trans('site.send_message')); ?>">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-sms'); ?>
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
                </button>
            <?php endif; ?>
        </div>

        <?php
            $socials = getSocials();
            if (!empty($socials) and count($socials)) {
                $socials = collect($socials)->sortBy('order')->toArray();
            }

            $userSocials = !empty($user->socials) ? json_decode($user->socials, true) : [];
        ?>

        
        <?php if(count($socials) and !empty($userSocials)): ?>
            <div class="d-flex-center gap-20 flex-wrap mt-16 w-100">

                <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $socialKey => $socialValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($socialValue['title']) and !empty($userSocials[$socialKey])): ?>
                        <a href="<?php echo e($userSocials[$socialKey]); ?>" class="" target="_blank" rel="nofollow">
                            <?php if(!empty($socialValue['image'])): ?>
                                <img src="<?php echo e($socialValue['image']); ?>" alt="<?php echo e($socialValue['title']); ?>" class="img-fluid" width="24px" height="24px">
                            <?php else: ?>
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-mobile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icon text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

    </div>


    <div class="pt-24 mt-24 px-16 border-top-gray-200">

        <div class="text-center text-gray-500 mb-8"><?php echo e(trans('update.member_since')); ?> <?php echo e(dateTimeFormat($user->created_at, 'M Y')); ?></div>

        <?php if($user->offline): ?>
            <div class="mt-24 p-12 rounded-12 border-warning bg-warning-10">
                <h5 class="font-14 font-weight-bold text-warning"><?php echo e(trans('update.the_user_is_temporarily_unavailable')); ?></h5>
                <p class="mt-8 font-12 text-warning opacity-75"><?php echo e(trans('update.the_user_is_temporarily_unavailable_hint')); ?></p>
            </div>
        <?php endif; ?>

    </div>

</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/users/profile/includes/left_side.blade.php ENDPATH**/ ?>