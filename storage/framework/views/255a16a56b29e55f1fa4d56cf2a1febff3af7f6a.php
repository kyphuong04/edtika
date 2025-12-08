<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('/admin/main.edit')); ?> <?php echo e(trans('admin/main.user')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>/users"><?php echo e(trans('admin/main.users')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('/admin/main.edit')); ?></div>
            </div>
        </div>

        <?php if(!empty(session()->has('msg'))): ?>
            <div class="alert alert-success my-25">
                <?php echo e(session()->get('msg')); ?>

            </div>
        <?php endif; ?>


        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row mb-0">
                        <div class="col-md-3">
                            <div class="card mb-3">
                                <div class="card-body text-center position-relative">
                                    <div class="position-absolute" style="top: 15px; right: 15px;">

                                        <div class="btn-group dropdown table-actions position-relative">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" style="outline: none !important; box-shadow: none !important;">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
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
                                            </button>


                                            <div class="dropdown-menu dropdown-menu-right">

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_users_impersonate')): ?>
                                                    <a href="<?php echo e(getAdminPanelUrl()); ?>/users/<?php echo e($user->id); ?>/impersonate" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-login'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500 mr-2','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                        <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.login')); ?></span>
                                                    </a>
                                                <?php endif; ?>


                                                <a href="<?php echo e($user->getProfileUrl()); ?>" target="_blank" class="dropdown-item d-flex align-items-center py-3 px-0 gap-4">
                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-user-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500 mr-2','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                    <span class="text-gray-500 font-14"><?php echo e(trans('public.profile')); ?></span>
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="avatar-container mb-3">
                                        <img src="<?php echo e($user->getAvatar()); ?>" class="img-fluid rounded-circle" width="120" height="120" alt="<?php echo e($user->full_name); ?>" style="border: 3px solid #f1f1f1; padding: 3px;">
                                    </div>

                                    <h4 class="mt-2"><?php echo e($user->full_name); ?></h4>

                                    <?php if($user->isTeacher() || $user->isAdmin() || $user->isAdmin()): ?>
                                        <div class="d-inline-flex align-items-center mt-1">
                                            <?php
                                                $userRates = $user->rates();
                                            ?>

                                            <?php echo $__env->make('admin.webinars.includes.rate',['rate' => $userRates, 'className' => 'mt-2', 'showRateStars' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                    <?php endif; ?>

                                    <p class="text-muted"><?php echo e($user->email); ?></p>
                                </div>
                            </div>

                            <div class="row mt-0 mb-0">
                                <div class="col-4">
                                    <div class="card mb-3">
                                        <div class="card-body pt-10 pb-10 text-center">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-dollar-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mb-1 text-gray-500','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                            <h6 class="font-14 text-muted"><?php echo e(trans('financial.total_income')); ?></h6>
                                            <p class="mt-2 font-20"><?php echo e(handlePrice($user->getIncome())); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php if(!empty($user) and ($user->isTeacher() || $user->isAdmin() || $user->isAdmin())): ?>
                                    <div class="col-4 mb-3">
                                        <div class="card mb-0">
                                            <div class="card-body pt-10 pb-10 text-center">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-shopping-cart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mb-1 text-gray-500','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                <h6 class="font-14 text-muted">Sales</h6>
                                                <p class="mt-2 font-20"><?php echo e($user->salesCount()); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($user) and ($user->isUser())): ?>
                                    <div class="col-4 mb-3">
                                        <div class="card mb-0">
                                            <div class="card-body pt-10 pb-10 text-center">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mb-1 text-gray-500','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                <h6 class="font-14 text-muted"><?php echo e(trans('update.enrolled_courses')); ?></h6>
                                                <p class="mt-2 font-20"><?php echo e(App\Models\Sale::where('buyer_id', $user->id)->whereNotNull('webinar_id')->whereNull('refund_at')->count()); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-4 mb-0">
                                    <div class="card mb-0">
                                        <div class="card-body pt-10 pb-10 text-center">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mb-1 text-gray-500','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                            <h6 class="font-14 text-muted"><?php echo e(trans('panel.meetings')); ?></h6>
                                            <p class="mt-2 font-20"><?php echo e($user->reserveMeetings()->count()); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-0">
                                <div class="card-header">
                                    <h4 class="text-dark"><?php echo e(trans('update.user_statistics')); ?></h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <?php if(!empty($user) and ($user->isAdmin() or $user->isTeacher())): ?>
                                            <div class="col-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-play'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mb-3 mr-3 text-gray-500','width' => '26px','height' => '26px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                    <div class="flex-grow-1">
                                                        <span class="font-14 text-gray-500"><?php echo e(trans('update.enrolled_courses')); ?></span>
                                                        <span class="font-16 font-weight-bold d-block"><?php echo e(App\Models\Sale::where('buyer_id', $user->id)->whereNotNull('webinar_id')->whereNull('refund_at')->count()); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mb-3 mr-3 text-gray-500','width' => '26px','height' => '26px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                    <div class="flex-grow-1">
                                                        <span class="font-14 text-gray-500"><?php echo e(trans('update.published_courses')); ?></span>
                                                        <span class="font-16 font-weight-bold d-block"><?php echo e(($user->isTeacher() || $user->isAdmin()) ? $user->getActiveWebinars(true) : 0); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endif; ?>

                                        <div class="col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-shopping-cart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mb-3 mr-3 text-gray-500','width' => '26px','height' => '26px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                <div class="flex-grow-1">
                                                    <span class="font-14 text-gray-500"><?php echo e(trans('update.total_purchases')); ?></span>
                                                    <span class="font-16 font-weight-bold d-block"><?php echo e(handlePrice($user->getPurchaseAmounts())); ?></span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-sms'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mb-3 mr-3 text-gray-500','width' => '26px','height' => '26px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                <div class="flex-grow-1">
                                                    <span class="font-14 text-gray-500"><?php echo e(trans('panel.support_tickets')); ?></span>
                                                    <span class="font-16 font-weight-bold d-block"><?php echo e($user->supports()->count()); ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-archive-book'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mb-3 mr-3 text-gray-500','width' => '26px','height' => '26px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                <div class="flex-grow-1">
                                                    <span class="font-14 text-gray-500"><?php echo e(trans('panel.certificates')); ?></span>
                                                    <span class="font-16 font-weight-bold d-block"><?php echo e($user->certificates()->count()); ?></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">

                                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link <?php if(empty($becomeInstructor) and empty(request()->get('tab'))): ?> active <?php endif; ?>" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><?php echo e(trans('admin/main.main_general')); ?></a>
                                        </li>

                                        <?php if(!empty($formFieldsHtml)): ?>
                                            <li class="nav-item">
                                                <a class="nav-link" id="extra_information-tab" data-toggle="tab" href="#extra_information" role="tab" aria-controls="extra_information" aria-selected="true"><?php echo e(trans('site.extra_information')); ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <li class="nav-item">
                                            <a class="nav-link" id="images-tab" data-toggle="tab" href="#images" role="tab" aria-controls="images" aria-selected="true"><?php echo e(trans('auth.images')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial" role="tab" aria-controls="financial" aria-selected="true"><?php echo e(trans('admin/main.financial')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="occupations-tab" data-toggle="tab" href="#occupations" role="tab" aria-controls="occupations" aria-selected="true"><?php echo e(trans('site.occupations')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="badges-tab" data-toggle="tab" href="#badges" role="tab" aria-controls="badges" aria-selected="true"><?php echo e(trans('admin/main.badges')); ?></a>
                                        </li>

                                        <?php if(!empty($user) and ($user->isAdmin() or $user->isTeacher())): ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_update_user_registration_package')): ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="registrationPackage-tab" data-toggle="tab" href="#registrationPackage" role="tab" aria-controls="registrationPackage" aria-selected="true"><?php echo e(trans('update.registration_package')); ?></a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if(!empty($user) and ($user->isAdmin() or $user->isTeacher())): ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_update_user_meeting_settings')): ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="meetingSettings-tab" data-toggle="tab" href="#meetingSettings" role="tab" aria-controls="meetingSettings" aria-selected="true"><?php echo e(trans('update.meeting_settings')); ?></a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if(!empty($becomeInstructor)): ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php if(!empty($becomeInstructor)): ?> active <?php endif; ?>" id="become_instructor-tab" data-toggle="tab" href="#become_instructor" role="tab" aria-controls="become_instructor" aria-selected="true"><?php echo e(trans('admin/main.become_instructor_info')); ?></a>
                                            </li>
                                        <?php endif; ?>


                                        <li class="nav-item">
                                            <a class="nav-link" id="purchased_courses-tab" data-toggle="tab" href="#purchased_courses" role="tab" aria-controls="purchased_courses" aria-selected="true"><?php echo e(trans('update.purchased_courses')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="purchased_bundles-tab" data-toggle="tab" href="#purchased_bundles" role="tab" aria-controls="purchased_bundles" aria-selected="true"><?php echo e(trans('update.purchased_bundles')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="purchased_products-tab" data-toggle="tab" href="#purchased_products" role="tab" aria-controls="purchased_products" aria-selected="true"><?php echo e(trans('update.purchased_products')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="topics-tab" data-toggle="tab" href="#topics" role="tab" aria-controls="topics" aria-selected="true"><?php echo e(trans('update.forum_topics')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="support_tickets-tab" data-toggle="tab" href="#support_tickets" role="tab" aria-controls="support_tickets" aria-selected="true"><?php echo e(trans('admin/main.tickets')); ?></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link <?php echo e((request()->get('tab') == "loginHistory") ? 'active' : ''); ?>" href="<?php echo e(getAdminPanelUrl("/users/{$user->id}/edit?tab=loginHistory")); ?>" role="tab" aria-controls="loginHistory" aria-selected="true"><?php echo e(trans('update.login_history')); ?></a>
                                        </li>

                                    </ul>

                                    <div class="tab-content" id="myTabContent2">

                                        <?php echo $__env->make('admin.users.editTabs.general', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php if(!empty($formFieldsHtml)): ?>
                                            <?php echo $__env->make('admin.users.editTabs.extra_information', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php endif; ?>

                                        <?php echo $__env->make('admin.users.editTabs.images', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php echo $__env->make('admin.users.editTabs.financial', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php echo $__env->make('admin.users.editTabs.occupations', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php echo $__env->make('admin.users.editTabs.badges', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php if(!empty($user) and ($user->isAdmin() or $user->isTeacher())): ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_update_user_registration_package')): ?>
                                                <?php echo $__env->make('admin.users.editTabs.registration_package', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if(!empty($user) and ($user->isAdmin() or $user->isTeacher())): ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_update_user_meeting_settings')): ?>
                                                <?php echo $__env->make('admin.users.editTabs.meeting_settings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if(!empty($becomeInstructor)): ?>
                                            <?php echo $__env->make('admin.users.editTabs.become_instructor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php endif; ?>

                                        <?php echo $__env->make('admin.users.editTabs.purchased_courses', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php echo $__env->make('admin.users.editTabs.purchased_bundles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php echo $__env->make('admin.users.editTabs.purchased_products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php echo $__env->make('admin.users.editTabs.topics', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php echo $__env->make('admin.users.editTabs.support_tickets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        <?php echo $__env->make('admin.users.editTabs.login_history', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>


    <script>
        var saveSuccessLang = '<?php echo e(trans('webinars.success_store')); ?>';
    </script>

    <script src="/assets/admin/js/parts/webinar_students.min.js"></script>
    <script src="/assets/admin/js/parts/user_edit.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>