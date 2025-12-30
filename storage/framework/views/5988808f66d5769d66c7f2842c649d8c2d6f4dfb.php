<?php $__env->startPush('libraries_top'); ?>
    <link rel="stylesheet" href="/assets/admin/vendor/owl.carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/owl.carousel/owl.theme.min.css">

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <section class="section">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="hero rounded-12 text-white hero-bg-image bg-secondary" data-background="<?php echo e(!empty(getThemePageBackgroundSettings('admin_dashboard')) ? getThemePageBackgroundSettings('admin_dashboard') : ''); ?>">
                    <div class="hero-inner">
                        <h2><?php echo e(trans('admin/main.welcome')); ?>, <?php echo e($authUser->full_name); ?>!</h2>

                        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_quick_access_links')): ?>
                                <div>
                                    <p class="lead"><?php echo e(trans('admin/main.welcome_card_text')); ?></p>

                                    <div class="mt-4 mb-2 d-flex align-items-center gap-16 rounded-24 flex-column flex-md-row">
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/comments/webinars" class="mt-2 mt-md-0 rounded-16 btn btn-outline-white btn-lg btn-icon icon-left">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mr-1 icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?><?php echo e(trans('admin/main.comments')); ?> </a>
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/supports" class="mt-2 mt-md-0 btn rounded-16 btn-outline-white btn-lg btn-icon icon-left">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-sms'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mr-1 icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?><?php echo e(trans('admin/main.tickets')); ?></a>
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/reports/webinars" class="mt-2 mt-md-0 btn rounded-16 btn-outline-white btn-lg btn-icon icon-left">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mr-1 icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?><?php echo e(trans('admin/main.reports')); ?></a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_clear_cache')): ?>
                                <div class="w-xs-to-lg-100">
                                    <p class="lead d-none d-lg-block">&nbsp;</p>

                                    <?php echo $__env->make('admin.includes.delete_button',[
                                                     'url' => getAdminPanelUrl().'/clear-cache',
                                                     'btnClass' => 'rounded-16 text-white border btn-outline-white font-14 btn-lg btn-icon icon-left mt-6',
                                                     'btnText' => trans('admin/main.clear_all_cache'),
                                                     'btnIcon' => 'trash',
                                                     'iconType' => 'lin',
                                                     'iconClass' => 'text-white mr-2',
                                                  ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_daily_sales_statistics')): ?>
                    <?php if(!empty($dailySalesTypeStatistics)): ?>
                        <div class="card card-statistic-2">
                            <div class="card-stats rounded-12">
                                <div class="card-stats-title"><?php echo e(trans('admin/main.daily_sales_type_statistics')); ?></div>

                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo e($dailySalesTypeStatistics['webinarsSales']); ?></div>
                                        <div class="text-gray-500 card-stats-item-label"><?php echo e(trans('admin/main.live_class')); ?></div>
                                    </div>

                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo e($dailySalesTypeStatistics['courseSales']); ?></div>
                                        <div class="text-gray-500 card-stats-item-label"><?php echo e(trans('admin/main.course')); ?></div>
                                    </div>

                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo e($dailySalesTypeStatistics['appointmentSales']); ?></div>
                                        <div class="text-gray-500 card-stats-item-label"><?php echo e(trans('admin/main.appointment')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon size-48 bg-primary-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-bag'); ?>
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
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4><?php echo e(trans('admin/main.today_sales')); ?></h4>
                                </div>
                                <div class="card-body">
                                    <?php echo e($dailySalesTypeStatistics['allSales']); ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>


            <div class="col-lg-4 col-md-4 col-sm-12">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_income_statistics')): ?>
                    <?php if(!empty($getIncomeStatistics)): ?>
                        <div class="card card-statistic-2">
                            <div class="card-stats">
                                <div class="card-stats-title"><?php echo e(trans('admin/main.income_statistics')); ?></div>

                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo e(handlePrice($getIncomeStatistics['todaySales'])); ?></div>
                                        <div class="text-gray-500 card-stats-item-label"><?php echo e(trans('admin/main.today')); ?></div>
                                    </div>

                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo e(handlePrice($getIncomeStatistics['monthSales'])); ?></div>
                                        <div class="text-gray-500 card-stats-item-label"><?php echo e(trans('admin/main.this_month')); ?></div>
                                    </div>

                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo e(handlePrice($getIncomeStatistics['yearSales'])); ?></div>
                                        <div class="text-gray-500 card-stats-item-label"><?php echo e(trans('admin/main.this_year')); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon size-48 bg-primary-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-dollar-square'); ?>
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
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4><?php echo e(trans('admin/main.total_incomes')); ?></h4>
                                </div>
                                <div class="card-body">
                                    <?php echo e(handlePrice($getIncomeStatistics['totalSales'])); ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_total_sales_statistics')): ?>
                    <?php if(!empty($getTotalSalesStatistics)): ?>
                        <div class="card card-statistic-2">
                            <div class="card-stats">
                                <div class="card-stats-title"><?php echo e(trans('admin/main.salescount')); ?></div>

                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo e($getTotalSalesStatistics['todaySales']); ?></div>
                                        <div class="text-gray-500 card-stats-item-label"><?php echo e(trans('admin/main.today')); ?></div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo e($getTotalSalesStatistics['monthSales']); ?></div>
                                        <div class="text-gray-500 card-stats-item-label"><?php echo e(trans('admin/main.this_month')); ?></div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count"><?php echo e($getTotalSalesStatistics['yearSales']); ?></div>
                                        <div class="text-gray-500 card-stats-item-label"><?php echo e(trans('admin/main.this_year')); ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-icon size-48 bg-primary-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-chart-square'); ?>
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

                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4><?php echo e(trans('admin/main.total_sales')); ?></h4>
                                </div>
                                <div class="card-body">
                                    <?php echo e($getTotalSalesStatistics['totalSales']); ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_new_sales')): ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/sales" class="card card-statistic-1">
                        <div class="card-icon bg-primary-40 rounded-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-shopping-cart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4><?php echo e(trans('admin/main.new_sale')); ?></h4>
                            </div>
                            <div class="card-body">
                                <?php echo e($getNewSalesCount); ?>

                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_new_comments')): ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>/comments/webinars" class="card card-statistic-1">
                        <div class="card-icon bg-danger-30 rounded-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-message-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4><?php echo e(trans('admin/main.new_comment')); ?></h4>
                            </div>
                            <div class="card-body">
                                <?php echo e($getNewCommentsCount); ?>

                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_new_tickets')): ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>/supports" class="card card-statistic-1">
                        <div class="card-icon bg-warning-30 rounded-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-sms'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4><?php echo e(trans('admin/main.new_ticket')); ?></h4>
                            </div>
                            <div class="card-body">
                                <?php echo e($getNewTicketsCount); ?>

                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_new_reviews')): ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a class="card card-statistic-1">
                        <div class="card-icon bg-success-30 rounded-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-time'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4><?php echo e(trans('admin/main.pending_review_classes')); ?></h4>
                            </div>
                            <div class="card-body">
                                <?php echo e($getPendingReviewCount); ?>

                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>

        </div>


        <div class="row">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_sales_statistics_chart')): ?>
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><?php echo e(trans('admin/main.sales_statistics')); ?></h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <button type="button" class="js-sale-chart-month btn"><?php echo e(trans('admin/main.month')); ?></button>
                                    <button type="button" class="js-sale-chart-year btn btn-primary"><?php echo e(trans('admin/main.year')); ?></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="position-relative">
                                        <canvas id="saleStatisticsChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <?php if(!empty($getMonthAndYearSalesChartStatistics)): ?>
                                        <div class="statistic-details mt-4 position-relative">
                                            <div class="statistic-details-item">
                                                <span class="text-gray-500">
                                                    <?php if($getMonthAndYearSalesChartStatistics['todaySales']['grow_percent']['status'] == 'up'): ?>
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    <?php else: ?>
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    <?php endif; ?>

                                                    <?php echo e($getMonthAndYearSalesChartStatistics['todaySales']['grow_percent']['percent']); ?>

                                                </span>

                                                <div class="detail-value"><?php echo e(handlePrice($getMonthAndYearSalesChartStatistics['todaySales']['amount'])); ?></div>
                                                <div class="detail-name"><?php echo e(trans('admin/main.today_sales')); ?></div>
                                            </div>
                                            <div class="statistic-details-item">
                                                <span class="text-gray-500">
                                                    <?php if($getMonthAndYearSalesChartStatistics['weekSales']['grow_percent']['status'] == 'up'): ?>
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    <?php else: ?>
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    <?php endif; ?>

                                                    <?php echo e($getMonthAndYearSalesChartStatistics['weekSales']['grow_percent']['percent']); ?>

                                                </span>

                                                <div class="detail-value"><?php echo e(handlePrice($getMonthAndYearSalesChartStatistics['weekSales']['amount'])); ?></div>
                                                <div class="detail-name"><?php echo e(trans('admin/main.week_sales')); ?></div>
                                            </div>
                                            <div class="statistic-details-item">
                                                <span class="text-gray-500">
                                                    <?php if($getMonthAndYearSalesChartStatistics['monthSales']['grow_percent']['status'] == 'up'): ?>
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    <?php else: ?>
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    <?php endif; ?>

                                                    <?php echo e($getMonthAndYearSalesChartStatistics['monthSales']['grow_percent']['percent']); ?>

                                                </span>

                                                <div class="detail-value"><?php echo e(handlePrice($getMonthAndYearSalesChartStatistics['monthSales']['amount'])); ?></div>
                                                <div class="detail-name"><?php echo e(trans('admin/main.month_sales')); ?></div>
                                            </div>
                                            <div class="statistic-details-item">
                                                <span class="text-gray-500">
                                                    <?php if($getMonthAndYearSalesChartStatistics['yearSales']['grow_percent']['status'] == 'up'): ?>
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    <?php else: ?>
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    <?php endif; ?>

                                                    <?php echo e($getMonthAndYearSalesChartStatistics['yearSales']['grow_percent']['percent']); ?>

                                                </span>

                                                <div class="detail-value"><?php echo e(handlePrice($getMonthAndYearSalesChartStatistics['yearSales']['amount'])); ?></div>
                                                <div class="detail-name"><?php echo e(trans('admin/main.year_sales')); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_recent_comments')): ?>
                <div class="col-lg-4 col-md-12 col-12 col-sm-12 <?php if(count($recentComments) < 6): ?> pb-30 <?php endif; ?>">
                    <div class="card <?php if(count($recentComments) < 6): ?> h-100 <?php endif; ?>">
                        <div class="card-header">
                            <h4><?php echo e(trans('admin/main.recent_comments')); ?></h4>
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between">
                            <ul class="list-unstyled list-unstyled-border">
                                <?php $__currentLoopData = $recentComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentComment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="media">
                                        <img class="mr-3 rounded-circle" width="50" height="50" src="<?php echo e($recentComment->user->getAvatar()); ?>" alt="avatar">
                                        <div class="media-body">
                                            <div class="float-right text-gray-500 font-12"><?php echo e(dateTimeFormat($recentComment->created_at, 'j M Y | H:i')); ?></div>
                                            <div class="media-title"><?php echo e($recentComment->user->full_name); ?></div>
                                            <span class="text-small text-gray-500"><?php echo e(truncate($recentComment->comment, 150)); ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>

                            <div class="text-center pt-1 pb-1">
                                <a href="<?php echo e(getAdminPanelUrl()); ?>/comments/webinars" class="btn rounded-16 btn-primary btn-lg btn-round ">
                                    <?php echo e(trans('admin/main.view_all')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>


        <div class="row">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_recent_tickets')): ?>
                <?php if(!empty($recentTickets)): ?>
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-sms'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '80px','height' => '80px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                                <h4><?php echo e(trans('admin/main.recent_tickets')); ?></h4>
                                <div class="card-description"><?php echo e($recentTickets['pendingReply']); ?> <?php echo e(trans('admin/main.pending_reply')); ?></div>
                            </div>

                            <div class="card-body p-0">
                                <div class="tickets-list">

                                    <?php $__currentLoopData = $recentTickets['tickets']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/supports/<?php echo e($ticket->id); ?>/conversation" class="ticket-item">
                                            <div class="ticket-title">
                                                <h4 class="text-dark"><?php echo e($ticket->title); ?></h4>
                                            </div>
                                            <div class="ticket-info">
                                                <div class="text-gray-500"><?php echo e($ticket->user->full_name); ?></div>
                                                <div class="bullet"></div>
                                                <?php if($ticket->status == 'replied' or $ticket->status == 'open'): ?>
                                                    <span class="badge-status-card text-warning bg-warning-30"><?php echo e(trans('admin/main.pending_reply')); ?></span>
                                                <?php elseif($ticket->status == 'close'): ?>
                                                    <span class="badge-status-card text-danger bg-danger-30"><?php echo e(trans('admin/main.close')); ?></span>
                                                <?php else: ?>
                                                    <span class="badge-status-card text-success bg-success-30"><?php echo e(trans('admin/main.replied')); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <div class="text-center pt-10 pb-10">
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/supports" class="btn rounded-16 btn-primary btn-lg btn-round ">
                                            <?php echo e(trans('admin/main.view_all')); ?>

                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_recent_webinars')): ?>
                <?php if(!empty($recentWebinars)): ?>
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '80px','height' => '80px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                                <h4><?php echo e(trans('admin/main.recent_live_classes')); ?></h4>
                                <div class="card-description"><?php echo e($recentWebinars['pendingReviews']); ?> <?php echo e(trans('admin/main.pending_review')); ?></div>
                            </div>
                            <div class="card-body p-0">
                                <div class="tickets-list">
                                    <?php $__currentLoopData = $recentWebinars['webinars']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webinar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/<?php echo e($webinar->id); ?>/edit" class="ticket-item">
                                            <div class="ticket-title">
                                                <h4 class="text-dark"><?php echo e($webinar->title); ?></h4>
                                            </div>

                                            <div class="ticket-info">
                                                <div class="text-gray-500"><?php echo e($webinar->teacher->full_name); ?></div>
                                                <div class="bullet"></div>
                                                <?php switch($webinar->status):
                                                    case (\App\Models\Webinar::$active): ?>
                                                        <?php if($webinar->isProgressing()): ?>
                                                            <span class="badge-status-card text-warning bg-warning-30"><?php echo e(trans('webinars.in_progress')); ?></span>
                                                        <?php elseif($webinar->start_date > time()): ?>
                                                            <span class="badge-status-card text-success bg-success-30"><?php echo e(trans('admin/main.not_conducted')); ?></span>
                                                        <?php else: ?>
                                                            <span class="badge-status-card text-success bg-success-30"><?php echo e(trans('public.finished')); ?></span>
                                                        <?php endif; ?>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$isDraft): ?>
                                                        <span class="badge-status-card text-dark bg-dark-30"><?php echo e(trans('admin/main.is_draft')); ?></span>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$pending): ?>
                                                        <span class="badge-status-card text-warning bg-warning-30"><?php echo e(trans('admin/main.waiting')); ?></span>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$inactive): ?>
                                                        <span class="badge-status-card text-danger bg-danger-30"><?php echo e(trans('public.rejected')); ?></span>
                                                        <?php break; ?>
                                                <?php endswitch; ?>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <div class="text-center pt-10 pb-10">
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars?type=webinar" class="btn rounded-16 btn-primary btn-lg btn-round ">
                                            <?php echo e(trans('admin/main.view_all')); ?>

                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_recent_courses')): ?>
                <?php if(!empty($recentCourses)): ?>
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '80px','height' => '80px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </div>
                                <h4><?php echo e(trans('admin/main.recent_courses')); ?></h4>
                                <div class="card-description"><?php echo e($recentCourses['pendingReviews']); ?> <?php echo e(trans('admin/main.pending_review')); ?></div>
                            </div>
                            <div class="card-body p-0">
                                <div class="tickets-list">


                                    <?php $__currentLoopData = $recentCourses['courses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/<?php echo e($course->id); ?>/edit" class="ticket-item">
                                            <div class="ticket-title">
                                                <h4 class="text-dark"><?php echo e($course->title); ?></h4>
                                            </div>

                                            <div class="ticket-info">
                                                <div class="text-gray-500"><?php echo e($course->teacher->full_name); ?></div>
                                                <div class="bullet"></div>
                                                <?php switch($course->status):
                                                    case (\App\Models\Webinar::$active): ?>
                                                        <?php if($course->isProgressing()): ?>
                                                            <span class="badge-status-card text-warning bg-warning-30"><?php echo e(trans('webinars.in_progress')); ?></span>
                                                        <?php elseif($course->start_date > time()): ?>
                                                            <span class="badge-status-card text-success bg-success-30"><?php echo e(trans('admin/main.not_conducted')); ?></span>
                                                        <?php else: ?>
                                                            <span class="badge-status-card text-success bg-success-30"><?php echo e(trans('public.finished')); ?></span>
                                                        <?php endif; ?>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$isDraft): ?>
                                                        <span class="badge-status-card text-dark bg-dark-30"><?php echo e(trans('admin/main.is_draft')); ?></span>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$pending): ?>
                                                        <span class="badge-status-card text-warning bg-warning-30"><?php echo e(trans('admin/main.waiting')); ?></span>
                                                        <?php break; ?>
                                                    <?php case (\App\Models\Webinar::$inactive): ?>
                                                        <span class="badge-status-card text-danger bg-danger-30"><?php echo e(trans('public.rejected')); ?></span>
                                                        <?php break; ?>
                                                <?php endswitch; ?>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <div class="text-center pt-10 pb-10">
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars?type=course" class="btn rounded-16 btn-primary btn-lg btn-round ">
                                            <?php echo e(trans('admin/main.view_all')); ?>

                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_general_dashboard_users_statistics_chart')): ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><?php echo e(trans('admin/main.new_registration_statistics')); ?></h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="position-relative">
                                        <canvas id="usersStatisticsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/admin/vendor/owl.carousel/owl.carousel.min.js"></script>

    <script src="/assets/admin/js/parts/dashboard.min.js"></script>

    <script>
        (function ($) {
            "use strict";

            <?php if(!empty($getMonthAndYearSalesChart)): ?>
            makeStatisticsChart('saleStatisticsChart', saleStatisticsChart, 'Sale', <?php echo json_encode($getMonthAndYearSalesChart['labels'], 15, 512) ?>,<?php echo json_encode($getMonthAndYearSalesChart['data'], 15, 512) ?>);
            <?php endif; ?>

            <?php if(!empty($usersStatisticsChart)): ?>
            makeStatisticsChart('usersStatisticsChart', usersStatisticsChart, 'Users', <?php echo json_encode($usersStatisticsChart['labels'], 15, 512) ?>,<?php echo json_encode($usersStatisticsChart['data'], 15, 512) ?>);
            <?php endif; ?>

        })(jQuery)
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>