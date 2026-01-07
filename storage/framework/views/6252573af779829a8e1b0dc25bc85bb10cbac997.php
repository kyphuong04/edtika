<div class="custom-tabs-content active">
    <?php if($user->financial_approval): ?>
        <div class="d-flex align-items-center bg-primary-20 text-primary p-12 rounded-12">
            <div class="d-flex-center size-48 bg-primary rounded-12">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>
            <div class="ml-8">
                <span class="d-block font-14 font-weight-bold"><?php echo e(trans('update.your_identity_verified')); ?></span>
                <span class="d-block font-12 mt-4"><?php echo e(trans('site.identity_and_financial_verified')); ?></span>
            </div>
        </div>
    <?php else: ?>
        <div class="d-flex align-items-center bg-warning-20 text-warning p-12 rounded-12">
            <div class="d-flex-center size-48 bg-warning rounded-12">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-more-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>
            <div class="ml-8">
                <span class="d-block font-14 font-weight-bold"><?php echo e(trans('update.identity_approval')); ?></span>
                <span class="d-block font-12 mt-4"><?php echo e(trans('site.identity_and_financial_not_verified')); ?></span>
            </div>
        </div>
    <?php endif; ?>


    <div class="row">
        <div class="col-12 col-lg-4 mt-20">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.payout_account')); ?></h3>


                <div class="form-group ">
                    <label class="form-group-label"><?php echo e(trans('financial.select_account_type')); ?></label>
                    <select name="bank_id" class="js-user-bank-input form-control select2 <?php $__errorArgs = ['bank_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php echo e(($user->financial_approval) ? 'disabled' : ''); ?>>
                        <option selected disabled><?php echo e(trans('financial.select_account_type')); ?></option>

                        <?php $__currentLoopData = $userBanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userBank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($userBank->id); ?>" <?php if(!empty($user->selectedBank) and $user->selectedBank->user_bank_id == $userBank->id): ?> selected="selected" <?php endif; ?> data-specifications="<?php echo e(json_encode($userBank->specifications->pluck('name','id')->toArray())); ?>"><?php echo e($userBank->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <?php $__errorArgs = ['bank_id'];
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

                <div class="js-bank-specifications-card">
                    <?php if(!empty($user) and !empty($user->selectedBank) and !empty($user->selectedBank->bank)): ?>
                        <?php $__currentLoopData = $user->selectedBank->bank->specifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $selectedBankSpecification = $user->selectedBank->specifications->where('user_selected_bank_id', $user->selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                            ?>

                            <div class="form-group">
                                <label class="form-group-label"><?php echo e($specification->name); ?></label>
                                <input type="text" name="bank_specifications[<?php echo e($specification->id); ?>]" value="<?php echo e((!empty($selectedBankSpecification)) ? $selectedBankSpecification->value : ''); ?>" class="form-control" <?php echo e(($user->financial_approval) ? 'disabled' : ''); ?>/>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <div class="col-12 col-lg-8 mt-20">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.identity_documents')); ?></h3>


                <div class="position-relative custom-input-file">
                    <label for="identity_scan" class="w-100 h-100 rounded-15 d-flex-center flex-column cursor-pointer p-28 border-gray-400 border-dashed">
                        <div class="d-flex-center size-44 rounded-circle <?php echo e(($user->financial_approval) ? 'bg-gray-400-20' : 'bg-primary-20'); ?>">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-direct-send'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons '.e(($user->financial_approval) ? 'text-gray-400' : 'text-primary').'','width' => '24','height' => '24']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>

                        <div class="js-file-name-span mt-8 font-12 <?php echo e(($user->financial_approval) ? 'text-gray-400' : 'text-primary'); ?>"><?php echo e(trans('financial.identity_scan')); ?></div>
                    </label>

                    <input type="file" name="identity_scan" id="identity_scan" class="custom-file-input" <?php echo e(($user->financial_approval) ? 'disabled' : ''); ?>>
                </div>


                <div class="position-relative custom-input-file mt-16">
                    <label for="certificate" class="w-100 h-100 rounded-15 d-flex-center flex-column cursor-pointer p-28 border-gray-400 border-dashed">
                        <div class="d-flex-center size-44 rounded-circle <?php echo e(($user->financial_approval) ? 'bg-gray-400-20' : 'bg-primary-20'); ?>">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-direct-send'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons '.e(($user->financial_approval) ? 'text-gray-400' : 'text-primary').'','width' => '24','height' => '24']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>

                        <div class="js-file-name-span mt-8 font-12 <?php echo e(($user->financial_approval) ? 'text-gray-400' : 'text-primary'); ?>"><?php echo e(trans('public.certificate_and_documents')); ?></div>
                    </label>

                    <input type="file" name="certificate" id="certificate" class="custom-file-input" <?php echo e(($user->financial_approval) ? 'disabled' : ''); ?>>
                </div>


            </div>
        </div>
    </div>
</div>


<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/settings/tabs/financial.blade.php ENDPATH**/ ?>