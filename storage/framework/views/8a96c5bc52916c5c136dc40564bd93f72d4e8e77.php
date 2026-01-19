

<?php $__env->startPush('styles_top'); ?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section>
    
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark"><?php echo e(isset($question) ? 'Edit' : 'Add'); ?> Question</h1>
            <p class="text-gray-500 font-14 mt-4"><?php echo e(isset($question) ? 'Update' : 'Create new'); ?> question for <?php echo e(ucfirst($bankType ?? request('bank_type', 'mock'))); ?> bank</p>
        </div>
        <a href="<?php echo e(route('panel.question_bank.' . ($bankType ?? request('bank_type', 'mock')) . '.list')); ?>" class="btn btn-outline-secondary">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>Back to List
        </a>
    </div>

    <form method="POST" action="<?php echo e(isset($question) ? route('panel.question_bank.update', [$bankType, $question->id]) : route('panel.question_bank.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($question)): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <input type="hidden" name="bank_type" value="<?php echo e($bankType ?? request('bank_type', 'mock')); ?>">

        <div class="row">
            <div class="col-lg-8">
                
                <div class="bg-white p-20 rounded-24 mb-24">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Basic Information</h4>

                    <div class="row">
                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Skill *</label>
                            <select name="skill" id="skill" class="form-control" required>
                                <option value="">Select Skill</option>
                                <option value="listening" <?php echo e((isset($question) && $question->skill == 'listening') || old('skill') == 'listening' ? 'selected' : ''); ?>>🎧 Listening</option>
                                <option value="reading" <?php echo e((isset($question) && $question->skill == 'reading') || old('skill') == 'reading' ? 'selected' : ''); ?>>📖 Reading</option>
                                <option value="writing" <?php echo e((isset($question) && $question->skill == 'writing') || old('skill') == 'writing' ? 'selected' : ''); ?>>✍️ Writing</option>
                                <option value="speaking" <?php echo e((isset($question) && $question->skill == 'speaking') || old('skill') == 'speaking' ? 'selected' : ''); ?>>🗣️ Speaking</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Question Type *</label>
                            <select name="question_type" id="question_type" class="form-control" required>
                                <option value="">Select Skill first</option>
                                
                                <option value="multiple_choice" data-skills="listening reading" <?php echo e((isset($question) && $question->question_type == 'multiple_choice') ? 'selected' : ''); ?>>Multiple Choice</option>
                                <option value="fill_blank" data-skills="listening reading" <?php echo e((isset($question) && $question->question_type == 'fill_blank') ? 'selected' : ''); ?>>Fill in the Blank</option>
                                <option value="matching" data-skills="listening reading" <?php echo e((isset($question) && $question->question_type == 'matching') ? 'selected' : ''); ?>>Matching</option>
                                <option value="short_answer" data-skills="listening reading" <?php echo e((isset($question) && $question->question_type == 'short_answer') ? 'selected' : ''); ?>>Short Answer</option>
                                <option value="sentence_completion" data-skills="listening reading" <?php echo e((isset($question) && $question->question_type == 'sentence_completion') ? 'selected' : ''); ?>>Sentence Completion</option>
                                
                                <option value="true_false_ng" data-skills="reading" <?php echo e((isset($question) && $question->question_type == 'true_false_ng') ? 'selected' : ''); ?>>True/False/Not Given</option>
                                <option value="heading_matching" data-skills="reading" <?php echo e((isset($question) && $question->question_type == 'heading_matching') ? 'selected' : ''); ?>>Heading Matching</option>
                                
                                <option value="essay" data-skills="writing" <?php echo e((isset($question) && $question->question_type == 'essay') ? 'selected' : ''); ?>>Essay (Task 2)</option>
                                <option value="report" data-skills="writing" <?php echo e((isset($question) && $question->question_type == 'report') ? 'selected' : ''); ?>>Report/Letter (Task 1)</option>
                                
                                <option value="speaking_part1" data-skills="speaking" <?php echo e((isset($question) && $question->question_type == 'speaking_part1') ? 'selected' : ''); ?>>Part 1 - Introduction</option>
                                <option value="speaking_part2" data-skills="speaking" <?php echo e((isset($question) && $question->question_type == 'speaking_part2') ? 'selected' : ''); ?>>Part 2 - Long Turn</option>
                                <option value="speaking_part3" data-skills="speaking" <?php echo e((isset($question) && $question->question_type == 'speaking_part3') ? 'selected' : ''); ?>>Part 3 - Discussion</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Question Text *</label>
                        <textarea name="question_text" class="form-control" rows="3" required><?php echo e(isset($question) ? $question->question_text : old('question_text')); ?></textarea>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Instruction</label>
                        <textarea name="instruction" class="form-control" rows="2"><?php echo e(isset($question) ? $question->instruction : old('instruction')); ?></textarea>
                        <small class="text-gray-500 font-12">Optional helper text for students</small>
                    </div>
                </div>

                
                <div class="bg-white p-20 rounded-24 mb-24" id="content-section">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Content</h4>

                    
                    <div class="mb-16 conditional-field" data-skills="reading">
                        <label class="font-12 text-gray-500 mb-8">Reading Passage</label>
                        <textarea name="passage" id="passage" class="summernote"><?php echo e(isset($question) ? $question->passage : old('passage')); ?></textarea>
                        <small class="text-gray-500 font-12 mt-8">Use toolbar to format text, add images, etc.</small>
                    </div>

                    
                    <div class="mb-16 conditional-field" data-skills="listening">
                        <label class="font-12 text-gray-500 mb-8">Audio Transcript</label>
                        <textarea name="transcript" class="form-control" rows="6"><?php echo e(isset($question) ? $question->transcript : old('transcript')); ?></textarea>
                    </div>

                    
                    <div class="mb-16 conditional-field" data-skills="listening">
                        <label class="font-12 text-gray-500 mb-8">Audio File</label>
                        <input type="file" name="audio_file" class="form-control" accept="audio/*">
                        <?php if(isset($question) && $question->audio_file): ?>
                            <small class="text-gray-500 font-12 mt-4">Current: <?php echo e(basename($question->audio_file)); ?></small>
                        <?php endif; ?>
                    </div>

                    
                    <div class="mb-16 conditional-field" data-skills="writing">
                        <label class="font-12 text-gray-500 mb-8">Task Image (for charts/diagrams)</label>
                        <input type="file" name="task_image" class="form-control" accept="image/*">
                        <?php if(isset($question) && $question->task_image): ?>
                            <div class="mt-8">
                                <img src="<?php echo e($question->task_image); ?>" alt="Task" style="max-width: 300px; border-radius: 8px;">
                            </div>
                        <?php endif; ?>
                        <small class="text-gray-500 font-12 mt-4">Upload chart/graph/diagram for Task 1</small>
                    </div>

                    
                    <div class="conditional-field" data-types="multiple_choice" style="display: none;">
                        <label class="font-12 text-gray-500 mb-8">Answer Options</label>
                        <div class="row">
                            <?php $__currentLoopData = ['A', 'B', 'C', 'D']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 mb-12">
                                    <input type="text" name="options[<?php echo e($option); ?>]" class="form-control" placeholder="Option <?php echo e($option); ?>" value="<?php echo e(isset($question) && isset($question->options[$option]) ? $question->options[$option] : old("options.$option")); ?>">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div class="conditional-field" data-types="matching heading_matching" style="display: none;">
                        <label class="font-12 text-gray-500 mb-8">Matching Items (one per line, format: item1 | match1)</label>
                        <textarea name="matching_items" class="form-control" rows="5" placeholder="Item 1 | Match A&#10;Item 2 | Match B&#10;Item 3 | Match C"><?php echo e(isset($question) && isset($question->matching_items) ? $question->matching_items : old('matching_items')); ?></textarea>
                    </div>

                    
                    <div class="conditional-field" data-types="speaking_part2" style="display: none;">
                        <label class="font-12 text-gray-500 mb-8">Cue Card Prompts (one per line)</label>
                        <textarea name="cue_card" class="form-control" rows="4" placeholder="You should say:&#10;• What it is&#10;• Where it is&#10;• When you did it&#10;• And explain why..."><?php echo e(isset($question) && isset($question->cue_card) ? $question->cue_card : old('cue_card')); ?></textarea>
                    </div>
                </div>

                
                <div class="bg-white p-20 rounded-24 mb-24">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Answer & Scoring</h4>

                    <div class="row">
                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Correct Answer *</label>
                            <input type="text" name="correct_answer" class="form-control" required value="<?php echo e(isset($question) ? $question->correct_answer : old('correct_answer')); ?>" placeholder="e.g., A, Room 5, renewable energy">
                            <small class="text-gray-500 font-12">For MC: A/B/C/D, For fill-blank: exact answer</small>
                        </div>

                        <div class="col-md-6 mb-16">
                            <label class="font-12 text-gray-500 mb-8">Points</label>
                            <input type="number" name="points" class="form-control" step="0.5" value="<?php echo e(isset($question) ? $question->points : old('points', 1)); ?>">
                        </div>
                    </div>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Explanation (Optional)</label>
                        <textarea name="explanation" class="form-control" rows="3"><?php echo e(isset($question) ? $question->explanation : old('explanation')); ?></textarea>
                        <small class="text-gray-500 font-12">Why this is the correct answer</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                
                <div class="bg-white p-20 rounded-24 mb-24">
                    <h4 class="font-14 font-weight-bold text-dark mb-16">Settings</h4>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Difficulty *</label>
                        <select name="difficulty_level" class="form-control" required>
                            <option value="">Select Level</option>
                            <option value="beginner" <?php echo e((isset($question) && $question->difficulty_level == 'beginner') || old('difficulty_level') == 'beginner' ? 'selected' : ''); ?>>Beginner</option>
                            <option value="intermediate" <?php echo e((isset($question) && $question->difficulty_level == 'intermediate') || old('difficulty_level') == 'intermediate' ? 'selected' : ''); ?>>Intermediate</option>
                            <option value="advanced" <?php echo e((isset($question) && $question->difficulty_level == 'advanced') || old('difficulty_level') == 'advanced' ? 'selected' : ''); ?>>Advanced</option>
                        </select>
                    </div>

                    
                    <?php if(($bankType ?? request('bank_type')) == 'practice'): ?>
                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Target Band</label>
                            <select name="target_band" class="form-control">
                                <option value="">Not specified</option>
                                <option value="5.0" <?php echo e((isset($question) && $question->target_band == '5.0') || old('target_band') == '5.0' ? 'selected' : ''); ?>>5.0</option>
                                <option value="5.5" <?php echo e((isset($question) && $question->target_band == '5.5') || old('target_band') == '5.5' ? 'selected' : ''); ?>>5.5</option>
                                <option value="6.0" <?php echo e((isset($question) && $question->target_band == '6.0') || old('target_band') == '6.0' ? 'selected' : ''); ?>>6.0</option>
                                <option value="6.5" <?php echo e((isset($question) && $question->target_band == '6.5') || old('target_band') == '6.5' ? 'selected' : ''); ?>>6.5</option>
                                <option value="7.0" <?php echo e((isset($question) && $question->target_band == '7.0') || old('target_band') == '7.0' ? 'selected' : ''); ?>>7.0</option>
                                <option value="7.5" <?php echo e((isset($question) && $question->target_band == '7.5') || old('target_band') == '7.5' ? 'selected' : ''); ?>>7.5</option>
                                <option value="8.0" <?php echo e((isset($question) && $question->target_band == '8.0') || old('target_band') == '8.0' ? 'selected' : ''); ?>>8.0+</option>
                            </select>
                        </div>

                        <div class="mb-16">
                            <label class="font-12 text-gray-500 mb-8">Practice Focus</label>
                            <input type="text" name="practice_focus" class="form-control" value="<?php echo e(isset($question) ? $question->practice_focus : old('practice_focus')); ?>" placeholder="e.g., Vocabulary, Grammar">
                        </div>
                    <?php endif; ?>

                    <div class="mb-16">
                        <label class="font-12 text-gray-500 mb-8">Tags</label>
                        <input type="text" name="tags" class="form-control" value="<?php echo e(isset($question) && $question->tags ? implode(', ', $question->tags) : old('tags')); ?>" placeholder="Environment, Technology, Health">
                        <small class="text-gray-500 font-12">Comma-separated topics</small>
                    </div>
                </div>

                
                <div class="bg-white p-20 rounded-24">
                    <button type="submit" class="btn btn-primary w-100 mb-12">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <?php echo e(isset($question) ? 'Update' : 'Create'); ?> Question
                    </button>
                    <a href="<?php echo e(route('panel.question_bank.' . ($bankType ?? request('bank_type', 'mock')) . '.list')); ?>" class="btn btn-outline-secondary w-100">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</section>

<?php $__env->startPush('scripts_bottom'); ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const skillSelect = document.getElementById('skill');
    const typeSelect = document.getElementById('question_type');
    
    // Question types per skill
    const questionTypesBySkill = {
        'listening': ['multiple_choice', 'fill_blank', 'matching', 'short_answer', 'sentence_completion'],
        'reading': ['multiple_choice', 'fill_blank', 'matching', 'short_answer', 'sentence_completion', 'true_false_ng', 'heading_matching'],
        'writing': ['essay', 'report'],
        'speaking': ['speaking_part1', 'speaking_part2', 'speaking_part3']
    };
    
    // Filter question types based on selected skill
    function filterQuestionTypes() {
        const selectedSkill = skillSelect.value;
        const currentType = typeSelect.value;
        
        // Clear current options
        Array.from(typeSelect.options).forEach(option => {
            if (option.value) {
                option.style.display = 'none';
            }
        });
        
        // Show only relevant types
        if (selectedSkill && questionTypesBySkill[selectedSkill]) {
            const validTypes = questionTypesBySkill[selectedSkill];
            Array.from(typeSelect.options).forEach(option => {
                if (validTypes.includes(option.value)) {
                    option.style.display = 'block';
                }
            });
            
            // Reset if current type is not valid for skill
            if (!validTypes.includes(currentType)) {
                typeSelect.value = '';
            }
        }
        
        updateConditionalFields();
    }
    
    function updateConditionalFields() {
        const selectedSkill = skillSelect.value;
        const selectedType = typeSelect.value;
        
        // Hide all conditional fields first
        document.querySelectorAll('.conditional-field').forEach(field => {
            field.style.display = 'none';
        });
        
        // Show fields based on skill
        if (selectedSkill) {
            document.querySelectorAll(`[data-skills*="${selectedSkill}"]`).forEach(field => {
                field.style.display = 'block';
            });
        }
        
        // Show fields based on type
        if (selectedType) {
            document.querySelectorAll(`[data-types*="${selectedType}"]`).forEach(field => {
                field.style.display = 'block';
            });
        }
    }
    
    skillSelect.addEventListener('change', filterQuestionTypes);
    typeSelect.addEventListener('change', updateConditionalFields);
    
    // Initialize Summernote for Reading passage
    $('.summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
    
    // Initial update
    filterQuestionTypes();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/question_bank/create.blade.php ENDPATH**/ ?>