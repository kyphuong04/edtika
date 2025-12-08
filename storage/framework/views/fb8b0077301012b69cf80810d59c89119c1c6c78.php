<?php $__currentLoopData = $quizQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <fieldset class="question-step question-step-<?php echo e($key + 1); ?>">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="font-weight-bold font-16"><?php echo e($question->title); ?></h3>

            <div class="d-flex-center text-gray-500 font-12">
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
            <div class="form-group mt-24 mb-0">
                <label class="form-group-label"><?php echo e(trans('update.your_answer')); ?></label>
                <textarea name="question[<?php echo e($question->id); ?>][answer]" rows="15" class="form-control"></textarea>
            </div>
        <?php else: ?>
            <div class="question-multi-answers mt-24">
                <?php $__currentLoopData = $question->quizzesQuestionsAnswers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="answer-item">
                        <input id="asw-<?php echo e($answer->id); ?>" type="radio" name="question[<?php echo e($question->id); ?>][answer]" value="<?php echo e($answer->id); ?>">

                        <label for="asw-<?php echo e($answer->id); ?>" class="answer-label d-flex-center text-center p-16 rounded-16 border-gray-200 cursor-pointer w-100 h-100">
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

<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/quizzes/holding/start/questions_form.blade.php ENDPATH**/ ?>