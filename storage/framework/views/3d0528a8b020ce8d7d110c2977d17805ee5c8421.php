<?php if(
                $authUser->can('admin_forum') or
                $authUser->can('admin_featured_topics')
                ): ?>
    <li class="menu-header"><?php echo e(trans('update.forum')); ?></li>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forum')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/forums*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-device-message'); ?>
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
                        <span><?php echo e(trans('update.forums')); ?></span>
                    </a>
                    <ul class="dropdown-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forum_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/forums/create', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/forums/create"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forum_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/forums', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/forums"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_forum_settings')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/forums/settings', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/forums/settings"><?php echo e(trans('admin/main.settings')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_featured_topics')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/featured-topics*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-message-favorite'); ?>
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
                        <span><?php echo e(trans('update.featured_topics')); ?></span>
                    </a>
                    <ul class="dropdown-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_featured_topics_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/featured-topics/create', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/featured-topics/create"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_featured_topics_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/featured-topics', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/featured-topics"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>
                       
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_recommended_topics')): ?>
                <li class="nav-item dropdown <?php echo e((request()->is(getAdminPanelUrl('/recommended-topics*', false))) ? 'active' : ''); ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-like-tag'); ?>
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
                        <span><?php echo e(trans('update.recommended_topics')); ?></span>
                    </a>
                    <ul class="dropdown-menu">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_recommended_topics_create')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/recommended-topics/create', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/recommended-topics/create"><?php echo e(trans('admin/main.new')); ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_recommended_topics_list')): ?>
                            <li class="<?php echo e((request()->is(getAdminPanelUrl('/recommended-topics', false))) ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(getAdminPanelUrl()); ?>/recommended-topics"><?php echo e(trans('admin/main.list')); ?></a>
                            </li>
                        <?php endif; ?>
                       
                    </ul>
                </li>
            <?php endif; ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/admin/includes/sidebar/forum.blade.php ENDPATH**/ ?>