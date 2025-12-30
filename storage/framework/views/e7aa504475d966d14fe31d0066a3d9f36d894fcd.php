<div class="<?php if(!empty($quiz)): ?> multipleQuestionModal<?php echo e($quiz->id); ?> <?php endif; ?>">
    <div class="custom-modal-body">
        <div class="quiz-questions-form" data-action="/panel/quizzes-questions/<?php echo e(empty($question_edit) ? 'store' : $question_edit->id.'/update'); ?>">

            <input type="hidden" name="ajax[quiz_id]" value="<?php echo e(!empty($quiz) ? $quiz->id :''); ?>">
            <input type="hidden" name="ajax[type]" value="<?php echo e(\App\Models\QuizzesQuestion::$multiple); ?>">

            <div class="row mt-24">
                <div class="col-12">
                    <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($question_edit) ? $question_edit : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-quiz-question-locale'
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('quiz.question_title')); ?></label>
                        <input type="text" name="ajax[title]" class="js-ajax-title form-control" value="<?php echo e(!empty($question_edit) ? $question_edit->title : ''); ?>"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('quiz.grade')); ?></label>
                        <input type="text" name="ajax[grade]" class="js-ajax-grade form-control" value="<?php echo e(!empty($question_edit) ? $question_edit->grade : ''); ?>"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('update.negative_grade')); ?></label>
                        <input type="text" name="ajax[negative_grade]" class="js-ajax-negative_grade form-control" value="<?php echo e(!empty($question_edit) ? $question_edit->negative_grade : ''); ?>"/>
                        <span class="invalid-feedback"></span>
                        <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.leave_empty_for_no_negative')); ?></p>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('public.image')); ?> (<?php echo e(trans('public.optional')); ?>)</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="ajax[image]" class="js-ajax-upload-file-input js-ajax-image custom-file-input" data-upload-name="ajax[image]" id="imageInput_<?php echo e(!empty($question_edit) ? $question_edit->id : 'record'); ?>" accept="image/*">
                            <span class="custom-file-text"><?php echo e((!empty($question_edit) and !empty($question_edit->image)) ? getFileNameByPath($question_edit->image) : ''); ?></span>
                            <label class="custom-file-label" for="imageInput_<?php echo e(!empty($question_edit) ? $question_edit->id : 'record'); ?>"><?php echo e(trans('update.browse')); ?></label>
                        </div>

                        <div class="invalid-feedback d-block"></div>

                        <?php if(!empty($question_edit) and !empty($question_edit->image)): ?>
                            <a href="<?php echo e($question_edit->image); ?>" target="_blank" class="font-12 text-primary mt-8"><?php echo e(trans('update.preview')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('update.video')); ?> (<?php echo e(trans('public.optional')); ?>)</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="ajax[video]" class="js-ajax-upload-file-input js-ajax-video custom-file-input" data-upload-name="ajax[video]" id="videoInput_<?php echo e(!empty($question_edit) ? $question_edit->id : 'record'); ?>" accept="video/*">
                            <span class="custom-file-text"><?php echo e((!empty($question_edit) and !empty($question_edit->video)) ? getFileNameByPath($question_edit->video) : ''); ?></span>
                            <label class="custom-file-label" for="videoInput_<?php echo e(!empty($question_edit) ? $question_edit->id : 'record'); ?>"><?php echo e(trans('update.browse')); ?></label>
                        </div>

                        <div class="invalid-feedback d-block"></div>

                        <?php if(!empty($question_edit) and !empty($question_edit->video)): ?>
                            <a href="<?php echo e($question_edit->video); ?>" target="_blank" class="font-12 text-primary mt-8"><?php echo e(trans('update.preview')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>


                <p class="font-12 text-gray-500 col-12"><?php echo e(trans('update.quiz_question_image_validation_by_video')); ?></p>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-20 p-12 rounded-16 border-dashed border-gray-300">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <div class="ml-8">
                        <h4 class="font-14 font-weight-bold"><?php echo e(trans('public.answers')); ?></h4>
                        <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.define_answers_for_this_question')); ?></p>
                    </div>
                </div>

                <div class="add-answer-btn d-flex align-items-center cursor-pointer">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="font-14 text-primary"><?php echo e(trans('quiz.add_an_answer')); ?></span>
                </div>
            </div>

            <div class="form-group mb-0">
                <input type="hidden" class="js-ajax-current_answer">
                <div class="invalid-feedback d-block"></div>
            </div>

            <div class="add-answer-container pb-20">

                <?php if(!empty($question_edit->quizzesQuestionsAnswers) and !$question_edit->quizzesQuestionsAnswers->isEmpty()): ?>
                    <?php $__currentLoopData = $question_edit->quizzesQuestionsAnswers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.quizzes.create.modals.multiple_answer_form',['answer' => $answer], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <?php echo $__env->make('design_1.panel.quizzes.create.modals.multiple_answer_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/create/modals/multiple_question.blade.php ENDPATH**/ ?>