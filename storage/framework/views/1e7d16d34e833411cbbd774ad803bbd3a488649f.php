<div class="<?php if(!empty($quiz)): ?> descriptiveQuestionModal<?php echo e($quiz->id); ?> <?php endif; ?>">
    <div class="custom-modal-body p-16">

        <div class="quiz-questions-form" data-action="/panel/quizzes-questions/<?php echo e(empty($question_edit) ? 'store' : $question_edit->id.'/update'); ?>">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
            <input type="hidden" name="ajax[quiz_id]" value="<?php echo e(!empty($quiz) ? $quiz->id :''); ?>">
            <input type="hidden" name="ajax[type]" value="<?php echo e(\App\Models\QuizzesQuestion::$descriptive); ?>">

            <div class="row mt-24">

                <div class="col-12">
                    <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($question_edit) ? $question_edit : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-quiz-question-locale'
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <div class="col-12 col-md-8">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('quiz.question_title')); ?></label>
                        <input type="text" name="ajax[title]" class="js-ajax-title form-control" value="<?php echo e(!empty($question_edit) ? $question_edit->title : ''); ?>"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('quiz.grade')); ?></label>
                        <input type="text" name="ajax[grade]" class="js-ajax-grade form-control" value="<?php echo e(!empty($question_edit) ? $question_edit->grade : ''); ?>"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('public.image')); ?> (<?php echo e(trans('public.optional')); ?>)</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="ajax[image]" class="js-ajax-upload-file-input js-ajax-image custom-file-input" data-upload-name="ajax[image]" id="imageInput_<?php echo e(!empty($question_edit) ? $question_edit->id : 'record'); ?>" accept="image/*">
                            <span class="custom-file-text"></span>
                            <label class="custom-file-label" for="imageInput_<?php echo e(!empty($question_edit) ? $question_edit->id : 'record'); ?>"><?php echo e(trans('update.browse')); ?></label>
                        </div>

                        <div class="invalid-feedback d-block"></div>

                        <?php if(!empty($question_edit) and !empty($question_edit->image)): ?>
                            <a href="<?php echo e($question_edit->image); ?>" target="_blank" class="font-12 text-primary mt-8"><?php echo e(trans('update.preview')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('update.video')); ?> (<?php echo e(trans('public.optional')); ?>)</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="ajax[video]" class="js-ajax-upload-file-input js-ajax-video custom-file-input" data-upload-name="ajax[video]" id="videoInput_<?php echo e(!empty($question_edit) ? $question_edit->id : 'record'); ?>" accept="video/*">
                            <span class="custom-file-text"></span>
                            <label class="custom-file-label" for="videoInput_<?php echo e(!empty($question_edit) ? $question_edit->id : 'record'); ?>"><?php echo e(trans('update.browse')); ?></label>
                        </div>

                        <div class="invalid-feedback d-block"></div>

                        <?php if(!empty($question_edit) and !empty($question_edit->video)): ?>
                            <a href="<?php echo e($question_edit->video); ?>" target="_blank" class="font-12 text-primary mt-8"><?php echo e(trans('update.preview')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <p class="font-12 text-gray-500 col-12"><?php echo e(trans('update.quiz_question_image_validation_by_video')); ?></p>

            <div class="row mt-20">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('quiz.correct_answer')); ?></label>
                        <textarea name="ajax[correct]" class="js-ajax-correct form-control" rows="10"><?php echo e(!empty($question_edit) ? $question_edit->correct : ''); ?></textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/create/modals/descriptive_question.blade.php ENDPATH**/ ?>