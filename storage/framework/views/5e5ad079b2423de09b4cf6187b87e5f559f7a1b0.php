<?php if($authUser->can('admin_blog') or
                $authUser->can('admin_pages') or
                $authUser->can('admin_additional_pages') or
                $authUser->can('admin_testimonials') or
                $authUser->can('admin_tags') or
                $authUser->can('admin_regions') or
                $authUser->can('admin_store') or
                $authUser->can('admin_forms') or
                $authUser->can('admin_ai_contents') or
                $authUser->can('admin_content_delete_requests_lists') or
                $authUser->can('admin_instructor_finder')
            ): ?>
    <li class="menu-header"><?php echo e(trans('admin/main.content')); ?></li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/store*', false)) or request()->is(getAdminPanelUrl('/comments/products*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-shop'); ?>
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
            <span><?php echo e(trans('update.store')); ?></span>
        </a>
        <ul class="dropdown-menu">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_new_product')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/products/create', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/products/create"><?php echo e(trans('update.new_product')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_products')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/products', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/products"><?php echo e(trans('update.products')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_in_house_products')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/in-house-products', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/in-house-products"><?php echo e(trans('update.in-house-products')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_products_orders')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/orders', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/orders"><?php echo e(trans('update.orders')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_in_house_orders')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/in-house-orders', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/in-house-orders"><?php echo e(trans('update.in-house-orders')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_products_sellers')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/sellers', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/sellers"><?php echo e(trans('update.sellers')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_categories_list')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/categories', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/categories"><?php echo e(trans('admin/main.categories')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_filters_list')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/filters', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/filters"><?php echo e(trans('update.filters')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_specifications')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/specifications', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/specifications"><?php echo e(trans('update.specifications')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_discounts')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/discounts', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/discounts"><?php echo e(trans('admin/main.discounts')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_products_comments')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/comments/products*', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/comments/products"><?php echo e(trans('admin/main.comments')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_products_comments_reports')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/comments/products/reports', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/comments/products/reports"><?php echo e(trans('admin/main.comments_reports')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_products_reviews')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/reviews', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/reviews"><?php echo e(trans('admin/main.reviews')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_top_categories')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/top-categories', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl("/store/top-categories")); ?>"><?php echo e(trans('update.top_categories')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_featured_products')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/featured-products', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl("/store/featured-products")); ?>"><?php echo e(trans('update.featured_products')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_featured_categories')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/featured-categories', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl("/store/featured-categories")); ?>"><?php echo e(trans('update.featured_categories')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_store_settings')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/store/settings', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/store/settings"><?php echo e(trans('admin/main.settings')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_blog')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/blog*', false)) and !request()->is(getAdminPanelUrl('/blog/comments', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-brush'); ?>
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
            <span><?php echo e(trans('admin/main.blog')); ?></span>
        </a>
        <ul class="dropdown-menu">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_blog_create')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/blog/create', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/blog/create"><?php echo e(trans('admin/main.new_post')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_blog_lists')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/blog', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/blog"><?php echo e(trans('site.posts')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_blog_categories')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/blog/categories', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/blog/categories"><?php echo e(trans('admin/main.categories')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_blog_featured_categories')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/blog/featured-categories', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/blog/featured-categories"><?php echo e(trans('update.featured_categories')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_blog_featured_contents')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/blog/featured-contents', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/blog/featured-contents"><?php echo e(trans('update.featured_contents')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_pages')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/pages*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-3square'); ?>
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
            <span><?php echo e(trans('admin/main.pages')); ?></span>
        </a>

        <ul class="dropdown-menu">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_pages_create')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/pages/create', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/pages/create"><?php echo e(trans('admin/main.new_page')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_pages_list')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/pages', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/pages"><?php echo e(trans('admin/main.list')); ?></a>
                </li>
            <?php endif; ?>

        </ul>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_additional_pages')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/additional_page*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-monitor'); ?>
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
            <span><?php echo e(trans('admin/main.additional_pages_title')); ?></span></a>
        <ul class="dropdown-menu">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_additional_pages_errors')): ?>
                <?php
                    $isErrorPageActive = (request()->is(getAdminPanelUrl('/additional_page/404', false)) or request()->is(getAdminPanelUrl('/additional_page/500', false)) or request()->is(getAdminPanelUrl('/additional_page/419', false)) or request()->is(getAdminPanelUrl('/additional_page/403', false)))
                ?>

                <li class="<?php echo e($isErrorPageActive ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/additional_page/404"><?php echo e(trans('admin/main.errors_settings')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_additional_pages_contact_us')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/additional_page/contact_us', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/additional_page/contact_us"><?php echo e(trans('admin/main.contact_us')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_additional_pages_navbar_links')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/additional_page/navbar_links', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/additional_page/navbar_links"><?php echo e(trans('admin/main.top_navbar')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_testimonials')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/testimonials*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-smileys'); ?>
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
            <span><?php echo e(trans('admin/main.testimonials')); ?></span>
        </a>
        <ul class="dropdown-menu">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_testimonials_create')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/testimonials/create', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/testimonials/create"><?php echo e(trans('admin/main.new')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_testimonials_list')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/testimonials', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/testimonials"><?php echo e(trans('admin/main.list')); ?></a>
                </li>
            <?php endif; ?>

        </ul>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_tags')): ?>
    <li class="<?php echo e((request()->is(getAdminPanelUrl('/tags', false))) ? 'active' : ''); ?>">
        <a href="<?php echo e(getAdminPanelUrl()); ?>/tags" class="nav-link">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tag-2'); ?>
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
            <span><?php echo e(trans('admin/main.tags')); ?></span>
        </a>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_regions')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/regions*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-map'); ?>
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
            <span><?php echo e(trans('update.regions')); ?></span>
        </a>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_regions_countries')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/regions/countries', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/regions/countries"><?php echo e(trans('update.countries')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_regions_provinces')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/regions/provinces', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/regions/provinces"><?php echo e(trans('update.provinces')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_regions_cities')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/regions/cities', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/regions/cities"><?php echo e(trans('update.cities')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_regions_districts')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/regions/districts', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/regions/districts"><?php echo e(trans('update.districts')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forms')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/forms*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-note-2'); ?>
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
            <span><?php echo e(trans('update.form_builder')); ?></span>
        </a>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forms_create')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/forms/create', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/forms/create"><?php echo e(trans('admin/main.new')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forms_lists')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/forms', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/forms"><?php echo e(trans('admin/main.list')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forms_submissions')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/forms/submissions', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/forms/submissions"><?php echo e(trans('update.submissions')); ?></a>
                </li>
            <?php endif; ?>

        </ul>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_ai_contents')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/ai-contents*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-cpu-charge'); ?>
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
            <span><?php echo e(trans('update.ai_contents')); ?></span>
        </a>
        <ul class="dropdown-menu">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_ai_contents_templates_create')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/ai-contents/templates/create', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/ai-contents/templates/create"><?php echo e(trans('update.new_template')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_ai_contents_templates_lists')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/ai-contents/templates', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/ai-contents/templates"><?php echo e(trans('update.service_template')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_ai_contents_lists')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/ai-contents/lists', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/ai-contents/lists"><?php echo e(trans('update.generated_contents')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_ai_contents_settings')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/ai-contents/settings', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/ai-contents/settings"><?php echo e(trans('update.settings')); ?></a>
                </li>
            <?php endif; ?>

        </ul>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_content_delete_requests_lists')): ?>
    <li class="nav-item <?php echo e((request()->is(getAdminPanelUrl('/content-delete-requests*', false))) ? 'active' : ''); ?>">
        <a href="<?php echo e(getAdminPanelUrl("/content-delete-requests")); ?>" class="nav-link">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-video-remove'); ?>
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
            <span><?php echo e(trans('update.content_delete_requests')); ?></span>
        </a>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_instructor_finder')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/instructor-finder*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-user-search'); ?>
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
            <span><?php echo e(trans('update.instructor_finder')); ?></span>
        </a>
        <ul class="dropdown-menu">

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_instructor_finder_settings')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/instructor-finder/settings', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/instructor-finder/settings"><?php echo e(trans('update.settings')); ?></a>
                </li>
            <?php endif; ?>

        </ul>
    </li>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/includes/sidebar/content.blade.php ENDPATH**/ ?>