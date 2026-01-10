

<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section>
    
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Edit Group</h1>
            <p class="text-gray-500 font-14 mt-4"><?php echo e($group->title); ?></p>
        </div>
        <a href="<?php echo e(route('panel.question-groups.show', $group->id)); ?>" class="btn btn-outline-secondary btn-sm">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-arrow-left'); ?>
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
<?php endif; ?>Back
        </a>
    </div>

    <form method="POST" action="<?php echo e(route('panel.question-groups.update', $group->id)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold mb-16">Basic Information</h3>
            
            <div class="form-group">
                <label class="form-group-label is-required">Group Title</label>
                <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       value="<?php echo e(old('title', $group->title)); ?>" required>
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label is-required">Skill</label>
                        <select name="skill" id="skillSelect" class="form-control" required>
                            <option value="reading" <?php echo e($group->skill == 'reading' ? 'selected' : ''); ?>>📖 Reading</option>
                            <option value="listening" <?php echo e($group->skill == 'listening' ? 'selected' : ''); ?>>🎧 Listening</option>
                            <option value="writing" <?php echo e($group->skill == 'writing' ? 'selected' : ''); ?>>✍️ Writing</option>
                            <option value="speaking" <?php echo e($group->skill == 'speaking' ? 'selected' : ''); ?>>🗣️ Speaking</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Target Band</label>
                        <select name="target_band" class="form-control">
                            <option value="">-- Select --</option>
                            <?php $__currentLoopData = [5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $band): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($band); ?>" <?php echo e($group->target_band == $band ? 'selected' : ''); ?>><?php echo e($band); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="draft" <?php echo e($group->status == 'draft' ? 'selected' : ''); ?>>Draft</option>
                            <option value="pending" <?php echo e($group->status == 'pending' ? 'selected' : ''); ?>>Pending Review</option>
                            <option value="approved" <?php echo e($group->status == 'approved' ? 'selected' : ''); ?>>Approved</option>
                        </select>
                    </div>
                </div>
            </div>
            
            
            <div class="row" id="writingTaskTypeRow" style="display: <?php echo e($group->skill == 'writing' ? 'flex' : 'none'); ?>;">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-group-label is-required">Writing Task Type</label>
                        <select name="question_type" id="writingTaskType" class="form-control" <?php echo e($group->skill == 'writing' ? 'required' : ''); ?>>
                            <option value="">-- Select Task Type --</option>
                            <optgroup label="✏️ Task 1 (Academic)">
                                <option value="task1_graph" <?php echo e($group->question_type == 'task1_graph' ? 'selected' : ''); ?>>📊 Graph/Chart/Table</option>
                                <option value="task1_map" <?php echo e($group->question_type == 'task1_map' ? 'selected' : ''); ?>>🗺️ Map/Diagram</option>
                                <option value="task1_process" <?php echo e($group->question_type == 'task1_process' ? 'selected' : ''); ?>>🔄 Process</option>
                            </optgroup>
                            <optgroup label="✉️ Task 1 (General)">
                                <option value="task1_letter" <?php echo e($group->question_type == 'task1_letter' ? 'selected' : ''); ?>>✉️ Letter</option>
                            </optgroup>
                            <optgroup label="📝 Task 2">
                                <option value="task2_essay" <?php echo e($group->question_type == 'task2_essay' ? 'selected' : ''); ?>>📝 Essay (Opinion/Discussion/Problem-Solution)</option>
                            </optgroup>
                        </select>
                        <small class="form-text text-muted">
                            <strong>Task 1:</strong> 150 words, 20 minutes - Describe visual information<br>
                            <strong>Task 2:</strong> 250 words, 40 minutes - Write an essay
                        </small>
                    </div>
                </div>
            </div>
            
            
            <div class="row" id="speakingPartTypeRow" style="display: <?php echo e($group->skill == 'speaking' ? 'flex' : 'none'); ?>;">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-group-label is-required">Speaking Part</label>
                        <select name="question_type" id="speakingPartType" class="form-control" <?php echo e($group->skill == 'speaking' ? 'required' : ''); ?>>
                            <option value="">-- Select Speaking Part --</option>
                            <option value="part1" <?php echo e($group->question_type == 'part1' ? 'selected' : ''); ?>>Part 1: Introduction & Interview (4-5 mins)</option>
                            <option value="part2" <?php echo e($group->question_type == 'part2' ? 'selected' : ''); ?>>Part 2: Long Turn / Cue Card (3-4 mins)</option>
                            <option value="part3" <?php echo e($group->question_type == 'part3' ? 'selected' : ''); ?>>Part 3: Two-way Discussion (4-5 mins)</option>
                        </select>
                        <small class="form-text text-muted">
                            <strong>Part 1:</strong> Personal questions about familiar topics<br>
                            <strong>Part 2:</strong> Cue card with 1 min preparation + 2 min speaking<br>
                            <strong>Part 3:</strong> Abstract discussion questions
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold mb-16">Content</h3>
            
            <div class="form-group">
                <label class="form-group-label">Instructions</label>
                <textarea name="instructions" class="form-control" rows="3"><?php echo e(old('instructions', $group->instructions)); ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-group-label">Reading Passage / Text</label>
                <textarea name="passage" class="summernote form-control"><?php echo e(old('passage', $group->passage)); ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-group-label">Audio File (for Listening)</label>
                <?php if($group->audio_path): ?>
                    <div class="mb-8">
                        <audio controls class="w-100">
                            <source src="<?php echo e($group->audio_url); ?>" type="audio/mpeg">
                        </audio>
                    </div>
                <?php endif; ?>
                <div class="custom-file bg-white">
                    <input type="file" name="audio_file" class="custom-file-input" id="audioFile" accept="audio/*">
                    <label class="custom-file-label" for="audioFile">
                        <?php echo e($group->audio_path ? 'Replace audio...' : 'Choose audio file...'); ?>

                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-group-label">Task Image (for Writing Task 1)</label>
                <?php if($group->task_image): ?>
                    <div class="mb-8">
                        <img src="<?php echo e(\Storage::disk('public')->url($group->task_image)); ?>" alt="Task Image" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                <?php endif; ?>
                <div class="custom-file bg-white">
                    <input type="file" name="task_image" class="custom-file-input" id="taskImage" accept="image/*">
                    <label class="custom-file-label" for="taskImage">
                        <?php echo e($group->task_image ? 'Replace image...' : 'Choose image file...'); ?>

                    </label>
                </div>
                <small class="form-text text-muted">Upload graph, chart, diagram, or map for Writing Task 1</small>
            </div>
        </div>
        
        
        <div class="bg-white rounded-16 p-16">
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?php echo e(route('panel.question-groups.show', $group->id)); ?>" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-square'); ?>
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
<?php endif; ?>Save Changes
                </button>
            </div>
        </div>
    </form>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 250,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table']],
                    ['view', ['codeview']]
                ]
            });
            
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').text(fileName || 'Choose file...');
            });
            
            // Show/hide Writing Task Type and Speaking Part based on skill selection
            $('#skillSelect').on('change', function() {
                const skill = $(this).val();
                
                // Writing Task Type
                if (skill === 'writing') {
                    $('#writingTaskTypeRow').slideDown();
                    $('#writingTaskType').prop('required', true);
                    $('#speakingPartTypeRow').slideUp();
                    $('#speakingPartType').prop('required', false).val('');
                } 
                // Speaking Part
                else if (skill === 'speaking') {
                    $('#speakingPartTypeRow').slideDown();
                    $('#speakingPartType').prop('required', true);
                    $('#writingTaskTypeRow').slideUp();
                    $('#writingTaskType').prop('required', false).val('');
                } 
                // Other skills
                else {
                    $('#writingTaskTypeRow').slideUp();
                    $('#writingTaskType').prop('required', false).val('');
                    $('#speakingPartTypeRow').slideUp();
                    $('#speakingPartType').prop('required', false).val('');
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/question_groups/edit.blade.php ENDPATH**/ ?>