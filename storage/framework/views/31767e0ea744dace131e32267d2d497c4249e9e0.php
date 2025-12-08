<?php $__env->startPush('styles_top'); ?>

<?php $__env->stopPush(); ?>

<div class="bg-white rounded-16 p-16 mt-32">
    <h3 class="font-14 font-weight-bold"><?php echo e(trans('public.message_to_reviewer')); ?></h3>


    <div class="form-group mt-24">
        <label class="form-group-label"><?php echo e(trans('site.message')); ?></label>
        <textarea name="message_for_reviewer" rows="10" class="form-control"><?php echo e((!empty($webinar) and $webinar->message_for_reviewer) ? $webinar->message_for_reviewer : old('message_for_reviewer')); ?></textarea>
    </div>

    <div class="form-group">
        <div class="d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="rulesSwitch" type="checkbox" name="rules" class="custom-control-input">
                <label class="custom-control-label cursor-pointer" for="rulesSwitch"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="rulesSwitch"><?php echo e(trans('public.agree_rules')); ?></label>
            </div>
        </div>

        <?php $__errorArgs = ['rules'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger mt-8">
            <?php echo e($message); ?>

        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <?php
        $contentReviewInformationSetting = getContentReviewInformationSettings();
    ?>

    <?php if(!empty($contentReviewInformationSetting)): ?>
        <div class="bg-gray-100 mt-16 p-16 rounded-16 border-gray-300">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <h4 class="font-14 mb-12"><?php echo e(trans('update.tips_and_policies')); ?></h4>

                    <?php if(!empty($contentReviewInformationSetting['description'])): ?>
                        <div class="text-gray-500"><?php echo nl2br($contentReviewInformationSetting['description']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4"></div>
                <div class="col-12 col-lg-4 mt-16 mt-lg-0">
                    <?php if(!empty($contentReviewInformationSetting['image'])): ?>
                        <img src="<?php echo e($contentReviewInformationSetting['image']); ?>" alt="<?php echo e(trans('update.tips_and_policies')); ?>" class="img-fluid w-100">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts_bottom'); ?>

<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/steps/step_8.blade.php ENDPATH**/ ?>