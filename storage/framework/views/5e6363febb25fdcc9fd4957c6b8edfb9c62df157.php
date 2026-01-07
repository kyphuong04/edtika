<div class="bg-white p-16 rounded-24">
    <div class="row">
        <div class="col-12 col-lg-6">
            <h5 class="font-16 font-weight-bold"><?php echo e(trans('update.ready_to_payout')); ?></h5>
            <h3 class="font-44 font-weight-bold mt-12"><?php echo e(handlePrice($readyPayout ?? 0)); ?></h3>

            <ul class="mt-8">
                <li class="text-gray-500 mb-6"><?php echo e(trans('update.payout_condition_1')); ?></li>
                <li class="text-gray-500 mb-6"><?php echo e(trans('update.payout_condition_2')); ?></li>
                <li class="text-gray-500 mb-6"><?php echo e(trans('update.payout_condition_3')); ?></li>
            </ul>

            <div class="d-flex align-items-center mt-12">
                <button type="button" class="js-request-payout-modal btn btn-primary flex-1" <?php echo e((!$authUser->financial_approval) ? 'disabled' : ''); ?>>
                    <?php echo e(trans('financial.request_payout')); ?>

                </button>

                <a href="/panel/setting/step/financial" class="btn bg-gray-100 text-gray-500 ml-8 flex-1"><?php echo e(trans('update.payout_information')); ?></a>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-16 mt-lg-0">
            <?php if(!empty($selectedBank) and $authUser->financial_approval): ?>
                <div class="position-relative">
                    <div class="payout-bank-card-mask"></div>
                    <div class="payout-bank-card d-flex flex-column w-100 z-index-2">
                        <img src="/assets/design_1/img/panel/payout/circle-left-top.svg" alt="" class="circle-left-top">
                        <img src="/assets/design_1/img/panel/payout/circle-bottom-right.svg" alt="" class="circle-bottom-right mr-24">
                        <img src="/assets/design_1/img/panel/payout/logo_mask.svg" alt="" class="logo-mask-right">

                        <div class="first-rectangle"></div>
                        <div class="second-rectangle"></div>

                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <div class="">
                                <span class="d-block font-16 font-weight-bold text-white"><?php echo e($selectedBank->bank->title); ?></span>
                                <span class="d-block font-12 text-white"><?php echo e($selectedBank->payouts->where('status', 'done')->count()); ?> <?php echo e(trans('admin/main.payouts')); ?></span>
                            </div>

                            <div class="position-relative z-index-3 d-flex-center size-48 rounded-12 bg-white">
                                <img src="<?php echo e($selectedBank->bank->logo); ?>" alt="" class="img-fluid">
                            </div>
                        </div>

                        <div class="mt-auto w-100">
                            <?php if(!empty($selectedBank) and !empty($selectedBank->bank)): ?>
                                <?php $__currentLoopData = $selectedBank->bank->specifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $selectedBankSpecification = $selectedBank->specifications->where('user_selected_bank_id', $selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                                    ?>

                                    <?php if(!empty($selectedBankSpecification) and !empty($selectedBankSpecification->value)): ?>
                                        <div class="d-flex align-items-center justify-content-between font-12 text-white w-100 mt-12">
                                            <span class=""><?php echo e($specification->name); ?></span>
                                            <span class=""><?php echo e($selectedBankSpecification->value); ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php else: ?>
                <div class="d-flex-center flex-column text-center p-32 w-100 h-100 rounded-16 bg-gray-100 soft-shadow-2 border-dashed border-<?php echo e(empty($selectedBank) ? 'primary' : 'warning'); ?>">
                    <div class="d-flex-center size-44 bg-<?php echo e(empty($selectedBank) ? 'primary' : 'warning'); ?>-20 rounded-circle">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-wallet-minus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-'.e(empty($selectedBank) ? 'primary' : 'warning').'','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <h4 class="font-14 mt-8 text-<?php echo e(empty($selectedBank) ? 'primary' : 'warning'); ?>"><?php echo e(trans('update.payout_account')); ?></h4>

                    <?php if(empty($selectedBank)): ?>
                        <p class="font-12 mt-4 text-primary"><?php echo e(trans('update.define_your_payout_information_to_get_payout')); ?></p>
                    <?php else: ?>
                        <p class="font-12 mt-4 text-warning"><?php echo e(trans('update.your_payout_information_is_awaiting_admin_approval')); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/financial/payout/ready_to_payout.blade.php ENDPATH**/ ?>