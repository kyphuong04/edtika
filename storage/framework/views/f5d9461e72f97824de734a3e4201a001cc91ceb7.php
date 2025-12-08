<?php $__currentLoopData = $quizQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <fieldset class="question-step question-step-<?php echo e($key + 1); ?>">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="font-weight-bold font-16"><?php echo e($question->title); ?></h3>

            <?php if($question->type === \App\Models\QuizzesQuestion::$descriptive): ?>
                <?php
                    $userGrade = (!empty($userAnswers[$question->id]) and !empty($userAnswers[$question->id]["grade"])) ? $userAnswers[$question->id]["grade"] : 0;
                ?>

                <div class="d-flex-center font-12 <?php echo e($userGrade == 0 ? 'text-danger' : ($userGrade < $question->grade ? 'text-warning' : 'text-success')); ?>">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-verify'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-4"><?php echo e($userGrade); ?>/<?php echo e($question->grade); ?></span>
                </div>
            <?php else: ?>
                <?php
                    $userAnswerCorrectly = false;

                    foreach($question->quizzesQuestionsAnswers as $key => $answer) {
                        if($answer->correct and (!empty($userAnswers[$question->id]) and (int)$userAnswers[$question->id]["answer"] ===  $answer->id)) {
                            $userAnswerCorrectly = true;
                        }
                    }
                ?>

                <div class="d-flex-center font-12 <?php echo e($userAnswerCorrectly ? 'text-success' : 'text-danger'); ?>">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-verify'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-4"><?php echo e($question->grade); ?></span>
                </div>
            <?php endif; ?>

        </div>

        <?php if(!empty($question->image) or !empty($question->video)): ?>
            <div class="quiz-question-media-card rounded-16 my-32">
                <?php if(!empty($question->image)): ?>
                    <img src="<?php echo e($question->image); ?>" class="img-cover rounded-16" alt="">
                <?php else: ?>
                    <video id="questionVideo<?php echo e($question->id); ?>" class="js-init-plyr-io plyr-io-video" oncontextmenu="return false;" controlsList="nodownload" controls preload="auto" width="100%" data-setup='{"fluid": true}'>
                        <source src="<?php echo e($question->video); ?>" type="video/mp4"/>
                    </video>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <?php if($question->type === \App\Models\QuizzesQuestion::$descriptive): ?>
            <div class="form-group mt-24">
                <label class="form-group-label"><?php echo e(trans('update.your_answer')); ?></label>
                <textarea name="question[<?php echo e($question->id); ?>][answer]" rows="10" disabled class="form-control"><?php echo e((!empty($userAnswers[$question->id]) and !empty($userAnswers[$question->id]["answer"])) ? $userAnswers[$question->id]["answer"] : ''); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-group-label"><?php echo e(trans('quiz.correct_answer')); ?></label>
                <textarea rows="10" name="question[<?php echo e($question->id); ?>][correct_answer]" <?php if(empty($newQuizStart) or $newQuizStart->quiz->creator_id != $authUser->id): ?> disabled <?php endif; ?> class="form-control bg-gray-100"><?php echo e($question->correct); ?></textarea>
            </div>

            <?php if(!empty($newQuizStart) and $newQuizStart->quiz->creator_id == $authUser->id): ?>
                <div class="form-group">
                    <label class="form-group-label"><?php echo e(trans('quiz.grade')); ?></label>
                    <input type="text" name="question[<?php echo e($question->id); ?>][grade]" value="<?php echo e((!empty($userAnswers[$question->id]) and !empty($userAnswers[$question->id]["grade"])) ? $userAnswers[$question->id]["grade"] : 0); ?>" class="form-control">
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="question-multi-answers mt-24">
                <?php $__currentLoopData = $question->quizzesQuestionsAnswers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isUserAnswer = (!empty($userAnswers[$question->id]) and (int)$userAnswers[$question->id]["answer"] ===  $answer->id);
                    ?>

                    <div class="answer-item">
                        <?php if($answer->correct): ?>
                            <div class="correct-answer d-flex align-items-center">
                                <div class="d-flex-center bg-primary py-4 pr-8 pl-4 rounded-32">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                    <span class="font-12 text-white ml-4"><?php echo e(trans('quiz.correct')); ?></span>
                                </div>

                                <?php if($isUserAnswer): ?>
                                    <div class="d-flex-center bg-success py-4 pr-8 pl-4 rounded-32 ml-8">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        <span class="font-12 text-white ml-4"><?php echo e(!empty($newQuizStart) ? trans('quiz.student_answer') : trans('update.your_answer')); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if($isUserAnswer and !$answer->correct): ?>
                            <div class="correct-answer d-flex-center bg-danger py-4 pr-8 pl-4 rounded-32">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-close-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                <span class="font-12 text-white ml-4"><?php echo e(!empty($newQuizStart) ? trans('quiz.student_answer') : trans('update.your_answer')); ?></span>
                            </div>
                        <?php endif; ?>

                        <input id="asw-<?php echo e($answer->id); ?>" type="radio" disabled name="question[<?php echo e($question->id); ?>][answer]" value="<?php echo e($answer->id); ?>" <?php echo e($isUserAnswer ? 'checked' : ''); ?>>

                        <label for="asw-<?php echo e($answer->id); ?>" class="answer-label d-flex-center text-center p-16 rounded-16 border-gray-200 cursor-pointer w-100 h-100 <?php echo e(($isUserAnswer and !$answer->correct) ? 'is-wrong-answer' : ''); ?>">
                            <?php if(!$answer->image): ?>
                                <span class="font-14 font-weight-bold"><?php echo e($answer->title); ?></span>
                            <?php else: ?>
                                <div class="image-container rounded-16">
                                    <img src="<?php echo e(url($answer->image)); ?>" class="img-cover rounded-16" alt="">
                                </div>
                            <?php endif; ?>
                        </label>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

    </fieldset>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/holding/result/questions_form.blade.php ENDPATH**/ ?>