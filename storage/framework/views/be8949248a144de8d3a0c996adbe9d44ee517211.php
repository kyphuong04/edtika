

<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $skillConfig = [
        'listening' => [
            'icon' => 'headphone',
            'color' => 'primary',
            'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'sections' => ['Section 1', 'Section 2', 'Section 3', 'Section 4'],
            'hint' => 'Each section has 10 questions. Upload audio file for students to listen.'
        ],
        'reading' => [
            'icon' => 'book',
            'color' => 'info',
            'gradient' => 'linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%)',
            'sections' => ['Passage 1', 'Passage 2', 'Passage 3'],
            'hint' => 'Each passage has 13-14 questions. Add the reading passage text below.'
        ],
        'writing' => [
            'icon' => 'edit',
            'color' => 'warning',
            'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'sections' => ['Task 1', 'Task 2'],
            'hint' => 'Task 1: Graph/Chart/Diagram (150 words). Task 2: Essay (250 words).'
        ],
        'speaking' => [
            'icon' => 'microphone',
            'color' => 'danger',
            'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'sections' => ['Part 1 - Introduction', 'Part 2 - Cue Card', 'Part 3 - Discussion'],
            'hint' => 'Part 1: 4-5 minutes. Part 2: 1 min prep + 2 min talk. Part 3: 4-5 minutes discussion.'
        ],
    ];
    $config = $skillConfig[$skill] ?? $skillConfig['reading'];
?>

<section>
    
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div class="d-flex align-items-center">
            <div class="skill-icon d-flex-center rounded-12 mr-16" 
                 style="width: 48px; height: 48px; background: <?php echo e($config['gradient']); ?>;">
                <?php if($skill === 'listening'): ?>
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-headphone'); ?>
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
                <?php elseif($skill === 'reading'): ?>
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-book'); ?>
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
                <?php elseif($skill === 'writing'): ?>
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-edit'); ?>
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
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-microphone'); ?>
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
                <?php endif; ?>
            </div>
            <div>
                <h1 class="font-20 font-weight-bold text-dark">Add <?php echo e(ucfirst($skill)); ?> Section</h1>
                <p class="text-gray-500 font-14 mt-4 mb-0"><?php echo e($config['hint']); ?></p>
            </div>
        </div>
        <a href="<?php echo e(route('panel.question-groups.create', ['type' => 'mock'])); ?>" class="btn btn-outline-secondary btn-sm">
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

    <form method="POST" action="<?php echo e(route('panel.question-groups.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="bank_type" value="mock">
        <input type="hidden" name="skill" value="<?php echo e($skill); ?>">
        
        
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold mb-16">Section Information</h3>
            
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="form-group">
                        <label class="form-group-label is-required">Section Title</label>
                        <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('title')); ?>" 
                               placeholder="e.g., <?php echo e($config['sections'][0]); ?> - Campus Tour" required>
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
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Target Band</label>
                        <select name="target_band" class="form-control">
                            <option value="">-- Select --</option>
                            <?php $__currentLoopData = [5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0, 8.5, 9.0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $band): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($band); ?>"><?php echo e($band); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-group-label">Instructions</label>
                <textarea name="instructions" class="form-control" rows="2" 
                          placeholder="e.g., Answer questions 1-10 based on the recording below"><?php echo e(old('instructions')); ?></textarea>
            </div>
        </div>
        
        
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold mb-16">Content</h3>
            
            <?php if($skill === 'listening'): ?>
                
                <div class="form-group">
                    <label class="form-group-label is-required">Audio File</label>
                    <div class="custom-file bg-white">
                        <input type="file" name="audio_file" class="custom-file-input" id="audioFile" accept="audio/*" required>
                        <label class="custom-file-label" for="audioFile">Choose audio file...</label>
                    </div>
                    <small class="text-gray-500">MP3, WAV, M4A - Max 50MB</small>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Transcript (optional)</label>
                    <textarea name="transcript" class="summernote form-control"><?php echo e(old('transcript')); ?></textarea>
                </div>
                
            <?php elseif($skill === 'reading'): ?>
                
                <div class="form-group">
                    <label class="form-group-label is-required">Reading Passage</label>
                    <textarea name="passage" class="summernote form-control" required><?php echo e(old('passage')); ?></textarea>
                    <small class="text-gray-500">Paste the full reading passage with proper paragraphs</small>
                </div>
                
            <?php elseif($skill === 'writing'): ?>
                
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="form-group-label is-required">Task Type</label>
                            <select name="section_type" class="form-control" required>
                                <option value="task1_graph">Task 1 - Graph/Chart (Academic)</option>
                                <option value="task1_letter">Task 1 - Letter (General)</option>
                                <option value="task1_process">Task 1 - Process/Diagram</option>
                                <option value="task2_essay">Task 2 - Essay</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="form-group-label">Task Image</label>
                            <div class="custom-file bg-white">
                                <input type="file" name="task_image" class="custom-file-input" id="taskImage" accept="image/*">
                                <label class="custom-file-label" for="taskImage">Choose image...</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label is-required">Task Description</label>
                    <textarea name="passage" class="summernote form-control" required><?php echo e(old('passage')); ?></textarea>
                </div>
                
            <?php elseif($skill === 'speaking'): ?>
                
                <div class="form-group">
                    <label class="form-group-label is-required">Part</label>
                    <select name="section_type" class="form-control" required>
                        <option value="part1">Part 1 - Introduction & Interview</option>
                        <option value="part2">Part 2 - Individual Long Turn (Cue Card)</option>
                        <option value="part3">Part 3 - Two-way Discussion</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label is-required">Topic</label>
                    <input type="text" name="topic" class="form-control" placeholder="e.g., Holidays, Technology, Education" required>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Description / Notes</label>
                    <textarea name="passage" class="form-control" rows="3"><?php echo e(old('passage')); ?></textarea>
                </div>
            <?php endif; ?>
        </div>
        
        
        <div class="bg-white rounded-16 p-16">
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?php echo e(route('panel.question-groups.create', ['type' => 'mock'])); ?>" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-success">
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
<?php endif; ?>Create & Add Questions
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
                height: 300,
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
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/question_groups/create_skill.blade.php ENDPATH**/ ?>