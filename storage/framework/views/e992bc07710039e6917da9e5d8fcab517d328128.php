<?php if(
        $authUser->can('admin_themes') or
        $authUser->can('admin_landing_builder')
    ): ?>
    <li class="menu-header"><?php echo e(trans('update.appearance')); ?></li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_themes')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/themes*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-designtools'); ?>
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
            <span><?php echo e(trans('update.themes')); ?></span>
        </a>

        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_themes_create')): ?>
            <li class="<?php echo e((request()->is(getAdminPanelUrl('/themes/create', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/themes/create"><?php echo e(trans('admin/main.new')); ?></a>
                </li>

                <li class="<?php echo e((request()->is(getAdminPanelUrl('/themes', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/themes"><?php echo e(trans('admin/main.list')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_themes_colors')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/themes/colors', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/themes/colors')); ?>"><?php echo e(trans('update.colors_lists')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_themes_fonts')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/themes/fonts', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/themes/fonts')); ?>"><?php echo e(trans('update.fonts_lists')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_themes_headers')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/themes/headers', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/themes/headers')); ?>"><?php echo e(trans('update.headers_lists')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_themes_footers')): ?>
                <li class="<?php echo e((request()->is(getAdminPanelUrl('/themes/footers', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getAdminPanelUrl('/themes/footers')); ?>"><?php echo e(trans('update.footers_lists')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_landing_builder')): ?>
    <li class="nav-item dropdown <?php echo e((request()->is(getLandingBuilderUrl('*', false))) ? 'active' : ''); ?>">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-colorfilter'); ?>
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
            <span><?php echo e(trans('update.landing_builder')); ?></span>
        </a>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_landing_builder_create')): ?>
                <li class="<?php echo e((request()->is(getLandingBuilderUrl('/start', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getLandingBuilderUrl("/start")); ?>"><?php echo e(trans('update.new_landing')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_landing_builder_all_pages')): ?>
                <li class="<?php echo e((request()->is(getLandingBuilderUrl('/all-pages', false))) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(getLandingBuilderUrl('/all-pages')); ?>"><?php echo e(trans('update.landing_pages')); ?></a>
                </li>
            <?php endif; ?>

            
        </ul>
    </li>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/admin/includes/sidebar/appearance.blade.php ENDPATH**/ ?>