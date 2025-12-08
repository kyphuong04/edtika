<?php if($authUser->can('admin_documents') or
                $authUser->can('admin_sales_list') or
                $authUser->can('admin_payouts') or
                $authUser->can('admin_offline_payments_list') or
                $authUser->can('admin_subscribe') or
                $authUser->can('admin_registration_packages') or
                $authUser->can('admin_installments')
            ): ?>
    <li class="menu-header"><?php echo e(trans('admin/main.financial')); ?></li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_documents')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/financial/documents*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-receipt-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <span><?php echo e(trans('admin/main.balances')); ?></span>
                    </a>
                    <ul class="dropdown-menu">

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_documents_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/documents', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/documents"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_documents_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/documents/new', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/documents/new"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_sales_list')): ?>
                <li class="nav-item <?php echo e((request()->is(getAdminPanelUrl('/financial/sales*', false))) ? 'active' : ''); ?>">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/sales" class="nav-link">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-bag-timer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <span><?php echo e(trans('admin/main.sales_list')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_payouts')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/financial/payouts*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-empty-wallet-change'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?> <span><?php echo e(trans('admin/main.payout')); ?></span></a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_payouts_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/payouts', false)) and request()->get('payout') == 'requests') ? 'active' : ''); ?>">
                                <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/payouts?payout=requests" class="nav-link <?php if(!empty($sidebarBeeps['payoutRequest']) and $sidebarBeeps['payoutRequest']): ?> beep beep-sidebar <?php endif; ?>">
                                    <span><?php echo e(trans('panel.requests')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_payouts_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/payouts', false)) and request()->get('payout') == 'history') ? 'active' : ''); ?>">
                                <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/payouts?payout=history" class="nav-link">
                                    <span><?php echo e(trans('public.history')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_offline_payments_list')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/financial/offline_payments*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-moneys'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?> <span><?php echo e(trans('admin/main.offline_payments')); ?></span></a>
                    <ul class="dropdown-menu">
                        <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/offline_payments', false)) and request()->get('page_type') == 'requests') ? 'active' : ''); ?>">
                            <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/offline_payments?page_type=requests" class="nav-link <?php if(!empty($sidebarBeeps['offlinePayments']) and $sidebarBeeps['offlinePayments']): ?> beep beep-sidebar <?php endif; ?>">
                                <span><?php echo e(trans('panel.requests')); ?></span>
                            </a>
                        </li>

                        <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/offline_payments', false)) and request()->get('page_type') == 'history') ? 'active' : ''); ?>">
                            <a href="<?php echo e(getAdminPanelUrl()); ?>/financial/offline_payments?page_type=history" class="nav-link">
                                <span><?php echo e(trans('public.history')); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_subscribe')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/financial/subscribes*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-flash-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <span><?php echo e(trans('admin/main.subscribes')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_subscribe_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/subscribes', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/subscribes"><?php echo e(trans('admin/main.packages')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_subscribe_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/subscribes/new', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/subscribes/new"><?php echo e(trans('admin/main.new_package')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>


            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_rewards')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/rewards*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-cup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <span><?php echo e(trans('update.rewards')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_rewards_history')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/rewards', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/rewards"><?php echo e(trans('public.history')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_rewards_items')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/rewards/items', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/rewards/items"><?php echo e(trans('update.conditions')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_rewards_settings')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/rewards/settings', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/rewards/settings"><?php echo e(trans('admin/main.settings')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_registration_packages')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/financial/registration-packages*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-diamonds'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <span><?php echo e(trans('update.registration_packages')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_registration_packages_lists')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/registration-packages', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/registration-packages"><?php echo e(trans('admin/main.packages')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_registration_packages_new')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/registration-packages/new', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/registration-packages/new"><?php echo e(trans('admin/main.new_package')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_registration_packages_reports')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/registration-packages/reports', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/registration-packages/reports"><?php echo e(trans('admin/main.reports')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_registration_packages_settings')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/registration-packages/settings', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/registration-packages/settings"><?php echo e(trans('admin/main.settings')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/financial/installments*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-convert-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <span><?php echo e(trans('update.installments')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/installments/create', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/financial/installments/create')); ?>"><?php echo e(trans('update.new_plan')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/installments', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/financial/installments')); ?>"><?php echo e(trans('update.plans')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_purchases')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/installments/purchases', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/financial/installments/purchases')); ?>"><?php echo e(trans('update.purchases')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_overdue_lists')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/installments/overdue', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/financial/installments/overdue')); ?>"><?php echo e(trans('update.overdue')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_overdue_history')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/installments/overdue_history', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/financial/installments/overdue_history')); ?>"><?php echo e(trans('update.overdue_history')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_verification_requests')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/installments/verification_requests', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/financial/installments/verification_requests')); ?>"><?php echo e(trans('update.verification_requests')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_verified_users')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/installments/verified_users', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/financial/installments/verified_users')); ?>"><?php echo e(trans('update.verified_users')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_installments_settings')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/installments/settings', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/financial/installments/settings')); ?>"><?php echo e(trans('admin/main.settings')); ?></a>
                            </li>
                        <?php endif; ?>

        </ul>
    </li>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/includes/sidebar/financial.blade.php ENDPATH**/ ?>