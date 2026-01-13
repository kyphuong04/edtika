<div class="add-answer-card position-relative rounded-16 border-gray-200 p-16 pt-24 mt-20 <?php echo e((empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'main-answer-box' : ''); ?>">

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('quiz.answer_title')); ?></label>
        <input type="text" name="ajax[answers][<?php echo e(!empty($answer) ? $answer->id : 'ans_tmp'); ?>][title]" class=" form-control <?php echo e(!empty($answer) ? 'js-ajax-answer-title-'.$answer->id : ''); ?>" value="<?php echo e(!empty($answer) ? $answer->title : ''); ?>"/>
    </div>


    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('quiz.answer_image')); ?></label>

        <div class="custom-file bg-white">
            <input type="file" name="ajax[answers][<?php echo e(!empty($answer) ? $answer->id : 'ans_tmp'); ?>][file]" class="js-ajax-upload-file-input js-ajax-image custom-file-input" data-upload-name="ajax[answers][<?php echo e(!empty($answer) ? $answer->id : 'ans_tmp'); ?>][file]" id="imageInput_<?php echo e(!empty($answer) ? $answer->id : '_ans_tmp'); ?>" accept="image/*">
            <span class="custom-file-text"><?php echo e((!empty($answer) and !empty($answer->image)) ? getFileNameByPath($answer->image) : ''); ?></span>
            <label class="custom-file-label" for="imageInput_<?php echo e(!empty($answer) ? $answer->id : '_ans_tmp'); ?>"><?php echo e(trans('browse')); ?></label>
        </div>

        <?php if(!empty($answer) and !empty($answer->image)): ?>
            <a href="<?php echo e($answer->image); ?>" target="_blank" class="font-12 text-primary mt-8"><?php echo e(trans('update.preview')); ?></a>
        <?php endif; ?>

        <div class="invalid-feedback d-block"></div>
    </div>

    <div class="d-flex align-items-center justify-content-between">
        <div class="form-group mb-0 d-flex align-items-center js-switch-parent">
            <div class="custom-switch mr-8">
                <input id="correctAnswerSwitch_<?php echo e(!empty($answer) ? $answer->id : ''); ?>" type="checkbox" name="ajax[answers][<?php echo e(!empty($answer) ? $answer->id : 'ans_tmp'); ?>][correct]" class="js-switch custom-control-input" <?php echo e((!empty($answer) and $answer->correct) ? 'checked' : ''); ?>>
                <label class="custom-control-label cursor-pointer js-switch" for="correctAnswerSwitch_<?php echo e(!empty($answer) ? $answer->id : ''); ?>"></label>
            </div>

            <div class="">
                <label class="cursor-pointer js-switch" for="correctAnswerSwitch_<?php echo e(!empty($answer) ? $answer->id : ''); ?>"><?php echo e(trans('quiz.correct_answer')); ?></label>
            </div>
        </div>


        <div class="d-flex-center cursor-pointer answer-remove <?php echo e((!empty($answer) and !empty($loop) and $loop->iteration > 1) ? '' : 'd-none'); ?>">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-trash'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>

    </div>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/create/modals/multiple_answer_form.blade.php ENDPATH**/ ?>