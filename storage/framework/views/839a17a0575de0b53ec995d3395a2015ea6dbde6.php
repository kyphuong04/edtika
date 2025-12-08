<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("profile")); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <div class="profile-cover-card">
        <img src="<?php echo e($user->getCover()); ?>" class="img-cover" alt=""/>
    </div>

    <div class="profile-container">
        <div class="container mb-104">
            <div class="row">

                <div class="col-12 col-md-4 col-lg-3">
                    <?php echo $__env->make('design_1.web.users.profile.includes.left_side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <div class="col-12 col-md-8 col-lg-9 mt-32 mt-md-0">
                    <div class="profile-card-has-mask position-relative bg-white pt-24 pb-20 rounded-24">
                        <div class="custom-tabs">

                            <div class="profile-tabs-items d-flex align-items-center gap-16 gap-lg-32 border-bottom-gray-200 px-24">
                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold <?php echo e((empty(request()->get('tab')) or request()->get('tab') == 'about') ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#aboutTab">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-profile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="ml-4"><?php echo e(trans('public.about')); ?></span>
                                </div>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold <?php echo e((request()->get('tab') == 'webinars') ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#coursesTab">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-video-play'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="ml-4"><?php echo e(trans('update.courses')); ?></span>
                                </div>

                                <?php if($user->isAdmin()): ?>
                                    <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold <?php echo e((request()->get('tab') == 'instructors') ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#instructorsTab">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-teacher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        <span class="ml-4"><?php echo e(trans('home.instructors')); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty(getStoreSettings('status')) and getStoreSettings('status')): ?>
                                    <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold <?php echo e((request()->get('tab') == 'products') ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#productsTab">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        <span class="ml-4"><?php echo e(trans('update.products')); ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold <?php echo e((request()->get('tab') == 'posts') ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#articlesTab">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="ml-4"><?php echo e(trans('update.articles')); ?></span>
                                </div>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold <?php echo e((request()->get('tab') == 'forum') ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#forumTab">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="ml-4"><?php echo e(trans('update.forum')); ?></span>
                                </div>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold <?php echo e((request()->get('tab') == 'badges') ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#badgesTab">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-medal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="ml-4"><?php echo e(trans('site.badges')); ?></span>
                                </div>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold <?php echo e((request()->get('tab') == 'appointments') ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#reserveMeetingTab">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="ml-4"><?php echo e(trans('public.reserve_a_meeting')); ?></span>
                                </div>
                            </div>

                            <div class="custom-tabs-body">

                                <div class="custom-tabs-content px-16  <?php echo e((empty(request()->get('tab')) or request()->get('tab') == 'about') ? 'active' : ''); ?>" id="aboutTab">
                                    <?php echo $__env->make('design_1.web.users.profile.tabs.about', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="custom-tabs-content px-16 <?php echo e((request()->get('tab') == 'webinars') ? 'active' : ''); ?>" id="coursesTab">
                                    <?php echo $__env->make('design_1.web.users.profile.tabs.courses', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <?php if($user->isAdmin()): ?>
                                    <div class="custom-tabs-content px-16 <?php echo e((request()->get('tab') == 'instructors') ? 'active' : ''); ?>" id="instructorsTab">
                                        <?php echo $__env->make('design_1.web.users.profile.tabs.instructors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="custom-tabs-content px-16 <?php echo e((request()->get('tab') == 'products') ? 'active' : ''); ?>" id="productsTab">
                                    <?php echo $__env->make('design_1.web.users.profile.tabs.products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="custom-tabs-content px-16 <?php echo e((request()->get('tab') == 'posts') ? 'active' : ''); ?>" id="articlesTab">
                                    <?php echo $__env->make('design_1.web.users.profile.tabs.articles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="custom-tabs-content px-16 <?php echo e((request()->get('tab') == 'forum') ? 'active' : ''); ?>" id="forumTab">
                                    <?php echo $__env->make('design_1.web.users.profile.tabs.forum', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="custom-tabs-content px-16 <?php echo e((request()->get('tab') == 'badges') ? 'active' : ''); ?>" id="badgesTab">
                                    <?php echo $__env->make('design_1.web.users.profile.tabs.badges', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="custom-tabs-content px-16 <?php echo e((request()->get('tab') == 'appointments') ? 'active' : ''); ?>" id="reserveMeetingTab">
                                    <?php echo $__env->make('design_1.web.users.profile.tabs.reserveMeeting.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var unFollowLang = '<?php echo e(trans('panel.unfollow')); ?>';
        var followLang = '<?php echo e(trans('panel.follow')); ?>';
        var sendMessageLang = '<?php echo e(trans('site.send_message')); ?>';
        var reservedLang = '<?php echo e(trans('meeting.reserved')); ?>';
        var messageSuccessSentLang = '<?php echo e(trans('site.message_success_sent')); ?>';
    </script>


    <script src="<?php echo e(getDesign1ScriptPath("profile")); ?>"></script>

    <?php if(!empty($user->live_chat_js_code) and !empty(getFeaturesSettings('show_live_chat_widget'))): ?>
        <script>
            (function () {
                "use strict"

                <?php echo $user->live_chat_js_code; ?>

            })(jQuery)
        </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.web.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/users/profile/index.blade.php ENDPATH**/ ?>