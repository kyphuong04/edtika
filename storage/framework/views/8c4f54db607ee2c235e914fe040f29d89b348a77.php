<?php if($authUser->can('admin_discount_codes') or
                $authUser->can('admin_cart_discount') or
                $authUser->can('admin_abandoned_cart') or
                $authUser->can('admin_product_discount') or
                $authUser->can('admin_feature_webinars') or
                $authUser->can('admin_gift') or
                $authUser->can('admin_promotion') or
                $authUser->can('admin_advertising') or
                $authUser->can('admin_newsletters') or
                $authUser->can('admin_advertising_modal') or
                $authUser->can('admin_registration_bonus') or
                $authUser->can('admin_floating_bar_create') or
                $authUser->can('admin_purchase_notifications') or
                $authUser->can('admin_product_badges')
            ): ?>
    <li class="menu-header"><?php echo e(trans('admin/main.marketing')); ?></li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_discount_codes')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/financial/discounts*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-ticket-discount'); ?>
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
                        <span><?php echo e(trans('admin/main.discount_codes')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_discount_codes_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/discounts', false)) and empty(request()->get('instructor_coupons'))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/discounts"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_discount_codes_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/discounts/new', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/discounts/new"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_discount_codes_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/discounts', false)) and !empty(request()->get('instructor_coupons'))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/discounts?instructor_coupons=1"><?php echo e(trans('update.instructor_coupons')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_cart_discount_controls')): ?>
                <li class="nav-item <?php echo e((request()->is(getAdminPanelUrl('/cart_discount*', false))) ? 'active' : ''); ?>">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>/cart_discount" class="nav-link">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-shopping-cart'); ?>
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
                        <span><?php echo e(trans('update.cart_discount')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_product_discount')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/financial/special_offers*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-receipt-discount'); ?>
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
                        <span><?php echo e(trans('admin/main.special_offers')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_product_discount_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/special_offers', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/special_offers"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_product_discount_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/special_offers/new', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/special_offers/new"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_abandoned_cart')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/abandoned-cart*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
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
                        <span><?php echo e(trans('update.abandoned_cart')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_abandoned_cart_rules')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/abandoned-cart/rules/create', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl("/abandoned-cart/rules/create")); ?>"><?php echo e(trans('update.new_rule')); ?></a>
                            </li>

                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/abandoned-cart/rules', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl("/abandoned-cart/rules")); ?>"><?php echo e(trans('update.rules')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_abandoned_cart_users')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/abandoned-cart/users-carts', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl("/abandoned-cart/users-carts")); ?>"><?php echo e(trans('update.users_carts')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_abandoned_cart_settings')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/abandoned-cart/settings', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl("/abandoned-cart/settings")); ?>"><?php echo e(trans('update.settings')); ?></a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_feature_webinars')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/webinars/features*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-crown'); ?>
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
                        <span><?php echo e(trans('admin/main.feature_webinars')); ?></span>
                    </a>
                    <ul class="dropdown-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_feature_webinars_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/webinars/features/create', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/webinars/features/create"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_feature_webinars')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/webinars/features', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/webinars/features"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>
                       
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_cashback')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/cashback*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
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
<?php endif; ?>
                        <span><?php echo e(trans('update.cashback')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_cashback_rules')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/cashback/rules/new', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/cashback/rules/new')); ?>"><?php echo e(trans('update.new_rule')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_cashback_rules')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/cashback/rules', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/cashback/rules')); ?>"><?php echo e(trans('update.rules')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_cashback_transactions')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/cashback/transactions', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/cashback/transactions')); ?>"><?php echo e(trans('update.transactions')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_cashback_history')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/cashback/history', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/cashback/history')); ?>"><?php echo e(trans('update.history')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_gift')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/gifts*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-gift'); ?>
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
                        <span><?php echo e(trans('update.gifts')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_gift_history')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/gifts', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl("/gifts")); ?>"><?php echo e(trans('public.history')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_gift_settings')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/gifts/settings', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl("/gifts/settings")); ?>"><?php echo e(trans('admin/main.settings')); ?></a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_promotion')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/financial/promotions*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-ranking'); ?>
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
                        <span><?php echo e(trans('admin/main.promotions')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_promotion_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/promotions', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/promotions"><?php echo e(trans('admin/main.plans')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_promotion_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/promotions/sales', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/promotions/sales"><?php echo e(trans('admin/main.promotion_sales')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_promotion_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/financial/promotions/new', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/financial/promotions/new"><?php echo e(trans('admin/main.new_plan')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_advertising')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/advertising*', false)) and !request()->is(getAdminPanelUrl('/advertising_modal*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-gallery'); ?>
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
                        <span><?php echo e(trans('admin/main.ad_banners')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_advertising_banners')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/advertising/banners', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/advertising/banners"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_advertising_banners_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/advertising/banners/new', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/advertising/banners/new"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_newsletters')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/newsletters*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-wifi-square'); ?>
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
                        <span><?php echo e(trans('admin/main.newsletters')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_newsletters_lists')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/newsletters', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/newsletters"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_newsletters_send')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/newsletters/send', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/newsletters/send"><?php echo e(trans('admin/main.send')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_newsletters_history')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/newsletters/history', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/newsletters/history"><?php echo e(trans('public.history')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_referrals')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/referrals*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-hierarchy-square-2'); ?>
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
                        <span><?php echo e(trans('panel.affiliate')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_referrals_history')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/referrals/history', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/referrals/history"><?php echo e(trans('public.history')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_referrals_users')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/referrals/users', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/referrals/users"><?php echo e(trans('admin/main.affiliate_users')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_registration_bonus')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/registration_bonus*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-box'); ?>
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
                        <span><?php echo e(trans('update.registration_bonus')); ?></span>
                    </a>
                    <ul class="dropdown-menu">

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_registration_bonus_history')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/registration_bonus/history', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/registration_bonus/history')); ?>"><?php echo e(trans('update.bonus_history')); ?></a>
                            </li>
                        <?php endif; ?>


                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_registration_bonus_settings')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/registration_bonus/settings', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/registration_bonus/settings')); ?>"><?php echo e(trans('admin/main.settings')); ?></a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_advertising_modal_config')): ?>
                <li class="nav-item <?php echo e((request()->is(getAdminPanelUrl('/advertising_modal*', false))) ? 'active' : ''); ?>">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>/advertising_modal" class="nav-link">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-colors-square'); ?>
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
                        <span><?php echo e(trans('update.advertising_modal')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_floating_bar_create')): ?>
                <li class="nav-item <?php echo e((request()->is(getAdminPanelUrl('/floating_bars*', false))) ? 'active' : ''); ?>">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>/floating_bars" class="nav-link">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-keyboard-open'); ?>
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
                        <span><?php echo e(trans('update.top_bottom_bar')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_purchase_notifications')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/purchase_notifications*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-shopping-bag'); ?>
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
                        <span><?php echo e(trans('update.purchase_notifications')); ?></span>
                    </a>
                    <ul class="dropdown-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_purchase_notifications_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/purchase_notifications/create', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/purchase_notifications/create')); ?>"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_purchase_notifications_lists')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/purchase_notifications', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/purchase_notifications')); ?>"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_product_badges')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/product-badges*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-receipt'); ?>
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
                        <span><?php echo e(trans('update.product_badges')); ?></span>
                    </a>
                    <ul class="dropdown-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_product_badges_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/product-badges/create', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/product-badges/create')); ?>"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_product_badges_lists')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/product-badges', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/product-badges')); ?>"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>

        </ul>
    </li>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/includes/sidebar/marketing.blade.php ENDPATH**/ ?>