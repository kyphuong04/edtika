<div class="custom-tabs-content active">
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.account_&_security')); ?></h3>

                <div class="form-group">
                    <label class="form-group-label"><?php echo e(trans('auth.name')); ?></label>
                    <input type="text" name="full_name" value="<?php echo e((!empty($user) and empty($new_user)) ? $user->full_name : old('full_name')); ?>" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder=""/>
                    <?php $__errorArgs = ['full_name'];
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
                    <label class="form-group-label"><?php echo e(trans('public.email')); ?></label>
                    <input type="text" name="email" value="<?php echo e((!empty($user) and empty($new_user)) ? $user->email : old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder=""/>
                    <?php $__errorArgs = ['email'];
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
                    <div class="register-mobile-form-group position-relative bg-white <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <label class="form-group-label"><?php echo e(trans('public.phone')); ?></label>

                        <div class="row">
                            <div class="col-4 h-100 pr-0">
                                <select name="country_code" class="form-control country-code-select2">
                                    <?php $__currentLoopData = getCountriesMobileCode(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country => $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($code); ?>" <?php if($code == old('country_code')): ?> selected <?php endif; ?>><?php echo e($country); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-8 h-100 pl-4">
                                <input type="tel" name="mobile" class="register-mobile-form-group__input bg-white">
                            </div>
                        </div>
                    </div>

                    <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block">
                        <?php echo e($message); ?>

                    </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-group-label"><?php echo e(trans('auth.password')); ?></label>
                    <input type="password" name="password" value="<?php echo e(old('password')); ?>" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder=""/>
                    <?php $__errorArgs = ['password'];
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

                <div class="form-group mb-0">
                    <label class="form-group-label"><?php echo e(trans('auth.password_repeat')); ?></label>
                    <input type="password" name="password_confirmation" value="<?php echo e(old('password_confirmation')); ?>" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder=""/>
                    <?php $__errorArgs = ['password_confirmation'];
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

            <?php if(empty($new_user) and empty($edit_new_user)): ?>
                <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                    <h3 class="font-14 font-weight-bold"><?php echo e(trans('update.delete_account')); ?></h3>

                    <?php if(!empty($user->deleteAccountRequest)): ?>
                        <div class="d-flex-center flex-column mt-16 text-center">
                            <div class="d-flex-center size-48 bg-warning rounded-12">
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

                            <h5 class="font-14 font-weight-bold text-dark mt-12"><?php echo e(trans('update.pending_review_request')); ?></h5>
                            <p class="mt-8 font-14 text-gray-500"><?php echo e(trans('update.delete_account_request_pending_review_hint')); ?></p>
                        </div>
                    <?php else: ?>
                        <p class="mt-16 font-14 text-gray-500"><?php echo e(trans('update.delete_account_hint')); ?></p>

                        <a href="/panel/setting/deleteAccount" class="delete-action btn btn-outline-danger btn-block mt-16" data-confirm="<?php echo e(trans('update.delete_account_modal_confirm_btn_text')); ?>" data-msg="<?php echo e(trans('update.delete_account_modal_hint')); ?>"><?php echo e(trans('update.delete_account')); ?></a>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </div>

        <div class="col-12 col-lg-4 mt-20 mt-lg-0">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.localization')); ?></h3>

                <?php if(!empty($userLanguages)): ?>
                    <div class="form-group mb-0">
                        <label class="form-group-label"><?php echo e(trans('auth.language')); ?></label>
                        <select name="language" class="form-control select2" data-minimum-results-for-search="Infinity">
                            <option value=""><?php echo e(trans('auth.language')); ?></option>
                            <?php $__currentLoopData = $userLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lang); ?>" <?php if(!empty($user) and mb_strtolower($user->language) == mb_strtolower($lang)): ?> selected <?php endif; ?>><?php echo e($language); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['language'];
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
                <?php endif; ?>

                <div class="form-group  mb-0 mt-20">
                    <label class="form-group-label"><?php echo e(trans('update.timezone')); ?></label>
                    <select name="timezone" class="form-control select2" data-allow-clear="false">
                        <option value="" <?php echo e(empty($user->timezone) ? 'selected' : ''); ?> disabled><?php echo e(trans('public.select')); ?></option>
                        <?php $__currentLoopData = getListOfTimezones(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($timezone); ?>" <?php if(!empty($user) and $user->timezone == $timezone): ?> selected <?php endif; ?>><?php echo e($timezone); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['timezone'];
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

                <?php if(!empty($currencies) and count($currencies)): ?>
                    <?php
                        $userCurrency = currency();
                    ?>

                    <div class="form-group  mb-0 mt-20">
                        <label class="form-group-label"><?php echo e(trans('update.currency')); ?></label>
                        <select name="currency" class="form-control select2" data-allow-clear="false">
                            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($currencyItem->currency); ?>" <?php echo e(($userCurrency == $currencyItem->currency) ? 'selected' : ''); ?>><?php echo e(currenciesLists($currencyItem->currency)); ?> (<?php echo e(currencySign($currencyItem->currency)); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['currency'];
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
                <?php endif; ?>
            </div>

            <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.vacation_mode')); ?></h3>

                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="vacationModeSwitch" type="checkbox" name="offline" class="custom-control-input" <?php echo e($user->offline ? 'checked' : ''); ?>>
                        <label class="custom-control-label cursor-pointer" for="vacationModeSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="vacationModeSwitch"><?php echo e(trans('update.enable_vacation_mode')); ?></label>
                    </div>
                </div>

                <div class="form-group mt-20">
                    <label class="form-group-label"><?php echo e(trans('panel.offline_message')); ?></label>
                    <textarea name="offline_message" class="form-control" rows="6"><?php echo e($user->offline_message); ?></textarea>
                </div>

                <p class="font-14 text-gray-500"><?php echo e(trans('update.vacation_mode_message_hint')); ?></p>
            </div>

        </div>

        <div class="col-12 col-lg-4 mt-20 mt-lg-0">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.account_options')); ?></h3>

                <div class="d-flex-center mt-36 mb-20">
                    <img src="/assets/design_1/img/panel/settings/account_options.svg" alt="" class="img-fluid" width="231px" height="200px">
                </div>

                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="newsletterSwitch" type="checkbox" name="join_newsletter" class="custom-control-input" <?php echo e((!empty($user) and $user->newsletter) ? 'checked' : ''); ?>>
                        <label class="custom-control-label cursor-pointer" for="newsletterSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="newsletterSwitch"><?php echo e(trans('auth.join_newsletter')); ?></label>
                    </div>
                </div>


                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="publicMessagesSwitch" type="checkbox" name="public_message" class="custom-control-input" <?php echo e((!empty($user) and $user->public_message) ? 'checked' : ''); ?>>
                        <label class="custom-control-label cursor-pointer" for="publicMessagesSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="publicMessagesSwitch"><?php echo e(trans('update.enable_profile_messages')); ?></label>
                    </div>
                </div>

                <div class="form-group d-flex align-items-center mb-0">
                    <div class="custom-switch mr-8">
                        <input id="enableProfileStatisticsSwitch" type="checkbox" name="enable_profile_statistics" class="custom-control-input" <?php echo e((!empty($user) and $user->enable_profile_statistics) ? 'checked' : ''); ?>>
                        <label class="custom-control-label cursor-pointer" for="enableProfileStatisticsSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="enableProfileStatisticsSwitch"><?php echo e(trans('update.enable_profile_statistics')); ?></label>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/settings/tabs/basic_information.blade.php ENDPATH**/ ?>