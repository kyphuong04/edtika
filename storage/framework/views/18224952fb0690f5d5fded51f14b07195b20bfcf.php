<tr>
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="<?php echo e($user->getAvatar()); ?>" class="img-cover rounded-circle" alt="<?php echo e($user->full_name); ?>">
            </div>
            <div class=" ml-8">
                <span class="d-block"><?php echo e($user->full_name); ?></span>
                <span class="d-block font-12 text-<?php echo e(($user->status == 'active') ? 'gray-500' : 'danger'); ?>"><?php echo e(($user->status == 'active') ? trans('public.active') : trans('public.inactive')); ?></span>
            </div>
        </div>
    </td>

    <td class="text-left">
        <div class="">
            <span class="d-block"><?php echo e($user->email); ?></span>
            <span class="d-block mt-4 font-12 text-gray-500"><?php echo e(trans('update.id')); ?>: <?php echo e($user->id); ?></span>
        </div>
    </td>

    <td class="text-center">
        <span class=""><?php echo e($user->mobile ?? '-'); ?></span>
    </td>

    <td class="text-center">
        <span class=""><?php echo e($user->webinars->count()); ?></span>
    </td>

    <td class="text-center">
        <span class=""><?php echo e($user->salesCount()); ?></span>
    </td>

    <td class="text-center">
        <span class=""><?php echo e(handlePrice($user->sales())); ?></span>
    </td>

    <td class="text-center"><?php echo e(dateTimeFormat($user->created_at,'j M Y | H:i')); ?></td>

    <td class="text-right">
        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="<?php echo e($user->getProfileUrl()); ?>" target="_blank" class=""><?php echo e(trans('public.profile')); ?></a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/manage/students/<?php echo e($user->id); ?>/edit" class=""><?php echo e(trans('public.edit')); ?></a>
                    </li>

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/manage/students/<?php echo e($user->id); ?>/delete" class="delete-action text-danger"><?php echo e(trans('public.delete')); ?></a>
                    </li>

                </ul>
            </div>
        </div>
    </td>
</tr>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/manage/instructors/table_items.blade.php ENDPATH**/ ?>