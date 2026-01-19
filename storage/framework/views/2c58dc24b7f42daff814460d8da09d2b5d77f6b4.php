<div class="tab-pane mt-3 fade <?php if(!empty($social)): ?> show active <?php endif; ?>" id="socials" role="tabpanel" aria-labelledby="socials-tab">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <form action="<?php echo e(getAdminPanelUrl()); ?>/settings/socials/store" method="post">
                <?php echo e(csrf_field()); ?>


                <input type="hidden" name="page" value="general">
                <input type="hidden" name="social" value="<?php echo e(!empty($socialKey) ? $socialKey : 'newSocial'); ?>">

                <div class="form-group">
                    <label><?php echo e(trans('admin/main.title')); ?></label>
                    <input type="text" name="value[title]" value="<?php echo e((!empty($social)) ? $social->title : old('value.title')); ?>" class="form-control  <?php $__errorArgs = ['value.title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                    <?php $__errorArgs = ['value.title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback">
                        <?php echo e($message); ?>

                    </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="input-label"><?php echo e(trans('admin/main.icon')); ?></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager" data-input="image" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="value[image]" id="image" value="<?php echo e((!empty($social)) ? $social->image : old('value.image')); ?>" class="form-control <?php $__errorArgs = ['value.image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                        <?php $__errorArgs = ['value.image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo e(trans('public.link')); ?></label>
                    <input type="text" name="value[link]" value="<?php echo e((!empty($social)) ? $social->link : old('value.link')); ?>" class="form-control  <?php $__errorArgs = ['value.link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                    <?php $__errorArgs = ['value.link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback">
                        <?php echo e($message); ?>

                    </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label><?php echo e(trans('admin/main.order')); ?></label>
                    <input type="number" name="value[order]" value="<?php echo e((!empty($social)) ? $social->order : old('value.order')); ?>" class="form-control  <?php $__errorArgs = ['value.order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                    <?php $__errorArgs = ['value.order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback">
                        <?php echo e($message); ?>

                    </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn btn-success mt-3"><?php echo e(trans('admin/main.submit')); ?></button>
            </form>
        </div>
    </div>

    <div class="table-responsive mt-5">
        <table class="table custom-table font-14">
            <tr>
                <th><?php echo e(trans('admin/main.icon')); ?></th>
                <th><?php echo e(trans('public.title')); ?></th>
                <th><?php echo e(trans('public.link')); ?></th>
                <th><?php echo e(trans('public.controls')); ?></th>
            </tr>

            <?php if(!empty($itemValue)): ?>
                <?php
                    if (!is_array($itemValue)) {
                        $itemValue = json_decode($itemValue, true);
                    }
                ?>

                <?php if(!empty($itemValue) and is_array($itemValue)): ?>
                    <?php $__currentLoopData = $itemValue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <img src="<?php echo e($val['image']); ?>" width="24"/>
                            </td>
                            <td><?php echo e($val['title']); ?></td>
                            <td><a href="<?php echo e($val['link']); ?>" target="_blank"><?php echo e(trans('public.view')); ?></a></td>
                            <td width="80px">
                                <div class="btn-group dropdown table-actions position-relative">
                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
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
                                        <a href="<?php echo e(getAdminPanelUrl()); ?>/settings/socials/<?php echo e($key); ?>/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-edit-2'); ?>
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
                                            <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.edit')); ?></span>
                                        </a>

                                        <?php echo $__env->make('admin.includes.delete_button',[
                                            'url' => getAdminPanelUrl().'/settings/socials/'. $key .'/delete',
                                            'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                            'btnText' => trans("admin/main.delete"),
                                            'btnIcon' => 'trash',
                                            'iconType' => 'lin',
                                            'iconClass' => 'text-danger mr-2',
                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/settings/general/socials.blade.php ENDPATH**/ ?>