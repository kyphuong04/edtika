<div data-action="<?php echo e(!empty($quiz) ? ('/panel/quizzes/'. $quiz->id .'/update') : ('/panel/quizzes/store')); ?>" class="js-content-form quiz-form">

    
    <div class="mt-24">
        <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($quiz) ? $quiz : null,
                'withoutReloadLocale' => true,
                'extraClass' => 'js-quiz-locale'
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <?php if(empty($selectedWebinar)): ?>
        <div class="form-group ">
            <label class="form-group-label"><?php echo e(trans('panel.webinar')); ?></label>
            <select name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][webinar_id]" class="js-ajax-webinar_id form-control select2">
                <option <?php echo e(!empty($quiz) ? 'disabled' : 'selected disabled'); ?> value=""><?php echo e(trans('panel.choose_webinar')); ?></option>

                <?php $__currentLoopData = $webinars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webinar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($webinar->id); ?>" <?php echo e((!empty($quiz) and $quiz->webinar_id == $webinar->id) ? 'selected' : ''); ?>><?php echo e($webinar->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <div class="invalid-feedback"></div>
        </div>
    <?php else: ?>
        <input type="hidden" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][webinar_id]" value="<?php echo e($selectedWebinar->id); ?>">
    <?php endif; ?>

    <div class="form-group ">
        <label class="form-group-label"><?php echo e(trans('public.chapter')); ?></label>
        <select name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][chapter_id]" class="js-ajax-chapter_id form-control <?php echo e((!empty($inWebinarPage) and empty($quiz)) ? 'js-make-select2-item' : 'select2'); ?>">
            <option value=""><?php echo e(trans('update.choose_a_chapter')); ?></option>
            <?php if(!empty($chapters)): ?>
                <?php $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($ch->id); ?>" <?php echo e((!empty($quiz) and $quiz->chapter_id == $ch->id) ? 'selected' : ''); ?>><?php echo e($ch->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </select>

        <div class="invalid-feedback"></div>
    </div>


    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('public.title')); ?></label>
        <input type="text" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][title]" class="js-ajax-title form-control" value="<?php echo e((!empty($quiz) and !empty($quiz->translate($locale))) ? $quiz->translate($locale)->title : ''); ?>"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('public.description')); ?></label>
        <textarea type="text" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][description]" rows="3" class="js-ajax-description form-control"><?php echo e((!empty($quiz) and !empty($quiz->translate($locale))) ? $quiz->translate($locale)->description : ''); ?></textarea>
        <div class="invalid-feedback"></div>
    </div>

    <h4 class="font-16 font-weight-bold my-24"><?php echo e(trans('update.quiz_options')); ?></h4>

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('update.icon')); ?></label>

        <div class="custom-file bg-white">
            <input type="file" name="icon" class="js-ajax-upload-file-input js-ajax-icon custom-file-input" data-upload-name="icon" id="iconInput" accept="image/*">
            <span class="custom-file-text"><?php echo e((!empty($quiz) and !empty($quiz->icon)) ? getFileNameByPath($quiz->icon) : ''); ?></span>
            <label class="custom-file-label" for="iconInput"><?php echo e(trans('update.browse')); ?></label>
        </div>

        <?php $__errorArgs = ['icon'];
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

        <?php if(!empty($quiz) and !empty($quiz->icon)): ?>
            <a href="<?php echo e(url($quiz->icon)); ?>" target="_blank" class="text-danger mt-4"><?php echo e(trans('update.preview')); ?></a>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('public.time')); ?></label>
        <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4"><?php echo e(trans('public.minutes')); ?></span>
        <input type="number" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][time]" value="<?php echo e(!empty($quiz) ? $quiz->time : old('time')); ?>" class="js-ajax-time form-control" placeholder="<?php echo e(trans('forms.empty_means_unlimited')); ?>" min="0"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('quiz.number_of_attemps')); ?></label>
        <input type="number" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][attempt]" value="<?php echo e(!empty($quiz) ? $quiz->attempt : old('attempt')); ?>" class="js-ajax-attempt form-control" placeholder="<?php echo e(trans('forms.empty_means_unlimited')); ?>" min="0"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('quiz.pass_mark')); ?></label>
        <input type="number" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][pass_mark]" value="<?php echo e(!empty($quiz) ? $quiz->pass_mark : old('pass_mark')); ?>" class="js-ajax-pass_mark form-control" min="0"/>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('update.expiry_days')); ?></label>
        <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4"><?php echo e(trans('public.days')); ?></span>
        <input type="number" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][expiry_days]" value="<?php echo e(!empty($quiz) ? $quiz->expiry_days : old('expiry_days')); ?>" class="js-ajax-expiry_days form-control" min="0"/>
        <div class="invalid-feedback"></div>

        <p class="font-12 text-gray-500 mt-8"><?php echo e(trans('update.quiz_expiry_days_hint')); ?></p>
    </div>

    <?php if(!empty($quiz)): ?>
        <div class="form-group d-flex align-items-center mt-16">
            <div class="custom-switch mr-8">
                <input id="displayLimitedQuestionsSwitch<?php echo e($quiz->id); ?>" type="checkbox" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][display_limited_questions]" class="js-ajax-display_limited_questions custom-control-input" <?php echo e(($quiz->display_limited_questions) ? 'checked' : ''); ?>>
                <label class="custom-control-label cursor-pointer" for="displayLimitedQuestionsSwitch<?php echo e($quiz->id); ?>"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="displayLimitedQuestionsSwitch<?php echo e($quiz->id); ?>"><?php echo e(trans('update.display_limited_questions')); ?></label>
            </div>
        </div>

        <div class="form-group js-display-limited-questions-count-field <?php echo e(($quiz->display_limited_questions) ? '' : 'd-none'); ?>">
            <label class="form-group-label"><?php echo e(trans('update.number_of_questions')); ?> (<?php echo e(trans('update.total_questions')); ?>: <?php echo e($quiz->quizQuestions->count()); ?>)</label>
            <input type="number" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][display_number_of_questions]" value="<?php echo e($quiz->display_number_of_questions); ?>" class="js-ajax-display_number_of_questions form-control" min="1"/>
            <div class="invalid-feedback"></div>
        </div>
    <?php endif; ?>

    <div class="form-group d-flex align-items-center mt-16">
        <div class="custom-switch mr-8">
            <input id="displayQuestionsRandomlySwitch<?php echo e(!empty($quiz) ? $quiz->id : 'record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][display_questions_randomly]" class="custom-control-input" <?php echo e((!empty($quiz) && $quiz->display_questions_randomly) ? 'checked' : ''); ?>>
            <label class="custom-control-label cursor-pointer" for="displayQuestionsRandomlySwitch<?php echo e(!empty($quiz) ? $quiz->id : 'record'); ?>"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="displayQuestionsRandomlySwitch<?php echo e(!empty($quiz) ? $quiz->id : 'record'); ?>"><?php echo e(trans('update.display_questions_randomly')); ?></label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center mt-16">
        <div class="custom-switch mr-8">
            <input id="certificateSwitch<?php echo e(!empty($quiz) ? $quiz->id : 'record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][certificate]" class="custom-control-input" <?php echo e((!empty($quiz) && $quiz->certificate) ? 'checked' : ''); ?>>
            <label class="custom-control-label cursor-pointer" for="certificateSwitch<?php echo e(!empty($quiz) ? $quiz->id : 'record'); ?>"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="certificateSwitch<?php echo e(!empty($quiz) ? $quiz->id : 'record'); ?>"><?php echo e(trans('quiz.certificate_included')); ?></label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center mt-16">
        <div class="custom-switch mr-8">
            <input id="statusSwitch<?php echo e(!empty($quiz) ? $quiz->id : 'record'); ?>" type="checkbox" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][status]" class="custom-control-input" <?php echo e((!empty($quiz) && $quiz->status == 'active') ? 'checked' : ''); ?>>
            <label class="custom-control-label cursor-pointer" for="statusSwitch<?php echo e(!empty($quiz) ? $quiz->id : 'record'); ?>"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="statusSwitch<?php echo e(!empty($quiz) ? $quiz->id : 'record'); ?>"><?php echo e(trans('quiz.active_quiz')); ?></label>
        </div>
    </div>

    <input type="hidden" name="ajax[<?php echo e(!empty($quiz) ? $quiz->id : 'new'); ?>][is_webinar_page]" value="<?php if(!empty($inWebinarPage) and $inWebinarPage): ?> 1 <?php else: ?> 0 <?php endif; ?>">

    <?php if(!empty($inWebinarPage)): ?>
        <div class="mt-20 d-flex align-items-center justify-content-end">
            <button type="button" class="js-save-course-content btn btn-lg btn-primary"><?php echo e(trans('public.save')); ?></button>

            <?php if(empty($quiz)): ?>
                <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion"><?php echo e(trans('public.close')); ?></button>
            <?php endif; ?>
        </div>
    <?php elseif(!empty($isQuizPage)): ?>



    <?php endif; ?>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/create/quiz_form.blade.php ENDPATH**/ ?>