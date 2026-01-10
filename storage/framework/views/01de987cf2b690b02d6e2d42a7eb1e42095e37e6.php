

<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section>
    
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Create <?php echo e(ucfirst($type)); ?> Test</h1>
            <p class="text-gray-500 font-14 mt-4">
                <?php if($type === 'mock'): ?>
                    Follow IELTS order: Listening → Reading → Writing → Speaking
                <?php else: ?>
                    Add practice questions for specific skills
                <?php endif; ?>
            </p>
        </div>
        <a href="<?php echo e(route('panel.question-groups.index', ['type' => $type])); ?>" class="btn btn-outline-secondary btn-sm">
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

    <?php if($type === 'mock'): ?>
        
        <div class="mock-test-structure">
            <?php
                $skills = [
                    'listening' => [
                        'icon' => 'headphone',
                        'color' => 'primary',
                        'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'sections' => 4,
                        'duration' => '30 minutes',
                        'questions' => '40 questions'
                    ],
                    'reading' => [
                        'icon' => 'book',
                        'color' => 'info',
                        'gradient' => 'linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%)',
                        'sections' => 3,
                        'duration' => '60 minutes',
                        'questions' => '40 questions'
                    ],
                    'writing' => [
                        'icon' => 'edit',
                        'color' => 'warning',
                        'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                        'sections' => 2,
                        'duration' => '60 minutes',
                        'questions' => '2 tasks'
                    ],
                    'speaking' => [
                        'icon' => 'microphone',
                        'color' => 'danger',
                        'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                        'sections' => 3,
                        'duration' => '11-14 minutes',
                        'questions' => '3 parts'
                    ],
                ];
            ?>
            
            <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="skill-section bg-white rounded-16 p-16 mb-16">
                    <div class="d-flex align-items-center justify-content-between">
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
                                <h3 class="font-16 font-weight-bold text-dark mb-4"><?php echo e(ucfirst($skill)); ?></h3>
                                <p class="font-12 text-gray-500 mb-0">
                                    <?php echo e($config['sections']); ?> sections • <?php echo e($config['duration']); ?> • <?php echo e($config['questions']); ?>

                                </p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('panel.question-groups.create', ['type' => 'mock', 'skill' => $skill])); ?>" 
                           class="btn btn-success btn-sm">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-add'); ?>
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
<?php endif; ?>Add <?php echo e(ucfirst($skill)); ?> Section
                        </a>
                    </div>
                    
                    
                    <?php
                        $existingGroups = \App\Models\IeltsQuestionGroup::where('bank_type', 'mock')
                            ->where('skill', $skill)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    ?>
                    
                    <?php if($existingGroups->count() > 0): ?>
                        <div class="mt-12 pt-12 border-top">
                            <p class="font-12 text-gray-500 mb-8">Recent <?php echo e(ucfirst($skill)); ?> sections:</p>
                            <div class="d-flex flex-wrap gap-8">
                                <?php $__currentLoopData = $existingGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('panel.question-groups.show', $group->id)); ?>" 
                                       class="badge badge-light font-12 p-8">
                                        <?php echo e(Str::limit($group->title, 30)); ?>

                                        <span class="text-gray-400 ml-4">(<?php echo e($group->question_count); ?>q)</span>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        
        <div class="bg-info-light rounded-16 p-16">
            <h4 class="font-14 font-weight-bold text-info mb-8">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-info-circle'); ?>
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
<?php endif; ?>IELTS Mock Test Structure
            </h4>
            <ul class="font-14 text-dark mb-0 pl-20">
                <li><strong>Listening:</strong> 4 sections, 10 questions each, total 40 questions</li>
                <li><strong>Reading:</strong> 3 passages (Academic) or 3 sections (General), total 40 questions</li>
                <li><strong>Writing:</strong> Task 1 (Graph/Letter 150 words) + Task 2 (Essay 250 words)</li>
                <li><strong>Speaking:</strong> Part 1 (Intro) + Part 2 (Cue Card) + Part 3 (Discussion)</li>
            </ul>
        </div>
        
    <?php else: ?>
        
        <form method="POST" action="<?php echo e(route('panel.question-groups.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="bank_type" value="practice">
            
            <div class="bg-white rounded-16 p-16 mb-16">
                <h3 class="font-14 font-weight-bold mb-16">Practice Question Group</h3>
                
                <div class="form-group">
                    <label class="form-group-label is-required">Title</label>
                    <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('title')); ?>" placeholder="e.g., Reading - Technology Topic" required>
                </div>
                
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="form-group-label is-required">Skill</label>
                            <select name="skill" id="skillSelectCreate" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="listening">🎧 Listening</option>
                                <option value="reading">📖 Reading</option>
                                <option value="writing">✍️ Writing</option>
                                <option value="speaking">🗣️ Speaking</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="form-group-label">Target Band</label>
                            <select name="target_band" class="form-control">
                                <option value="">-- Select --</option>
                                <?php $__currentLoopData = [5.0, 5.5, 6.0, 6.5, 7.0, 7.5, 8.0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $band): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($band); ?>"><?php echo e($band); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-group">
                            <label class="form-group-label">Focus Area</label>
                            <input type="text" name="practice_focus" class="form-control" placeholder="e.g., Matching Headings">
                        </div>
                    </div>
                </div>
                
                
                <div class="row" id="writingTaskTypeRowCreate" style="display: none;">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-group-label is-required">Writing Task Type</label>
                            <select name="question_type" id="writingTaskTypeCreate" class="form-control">
                                <option value="">-- Select Task Type --</option>
                                <optgroup label="✏️ Task 1 (Academic)">
                                    <option value="task1_graph">📊 Graph/Chart/Table</option>
                                    <option value="task1_map">🗺️ Map/Diagram</option>
                                    <option value="task1_process">🔄 Process</option>
                                </optgroup>
                                <optgroup label="✉️ Task 1 (General)">
                                    <option value="task1_letter">✉️ Letter</option>
                                </optgroup>
                                <optgroup label="📝 Task 2">
                                    <option value="task2_essay">📝 Essay (Opinion/Discussion/Problem-Solution)</option>
                                </optgroup>
                            </select>
                            <small class="form-text text-muted">
                                <strong>Task 1:</strong> 150 words, 20 minutes - Describe visual information<br>
                                <strong>Task 2:</strong> 250 words, 40 minutes - Write an essay
                            </small>
                        </div>
                    </div>
                </div>
                
                
                <div class="row" id="speakingPartTypeRowCreate" style="display: none;">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-group-label is-required">Speaking Part</label>
                            <select name="question_type" id="speakingPartTypeCreate" class="form-control">
                                <option value="">-- Select Part --</option>
                                <option value="part1">🗣️ Part 1 - Introduction & Interview (4-5 minutes)</option>
                                <option value="part2">📝 Part 2 - Individual Long Turn (3-4 minutes)</option>
                                <option value="part3">💭 Part 3 - Two-way Discussion (4-5 minutes)</option>
                            </select>
                            <small class="form-text text-muted">
                                <strong>Part 1:</strong> General questions about yourself and familiar topics<br>
                                <strong>Part 2:</strong> Speak on a given topic for 1-2 minutes<br>
                                <strong>Part 3:</strong> Deeper discussion on abstract ideas
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Instructions</label>
                    <textarea name="instructions" class="form-control" rows="2" 
                              placeholder="Instructions for students"></textarea>
                </div>
            </div>
            
            
            <div class="bg-white rounded-16 p-16 mb-16">
                <h3 class="font-14 font-weight-bold mb-16">Content</h3>
                
                <div class="form-group">
                    <label class="form-group-label">Instructions</label>
                    <textarea name="instructions" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Reading Passage / Text</label>
                    <textarea name="passage" class="summernote form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Audio File (for Listening)</label>
                    <div class="custom-file bg-white">
                        <input type="file" name="audio_file" class="custom-file-input" id="audioFile" accept="audio/*">
                        <label class="custom-file-label" for="audioFile">Choose audio file...</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-group-label">Task Image (for Writing Task 1)</label>
                    <div class="custom-file bg-white">
                        <input type="file" name="task_image" class="custom-file-input" id="taskImage" accept="image/*">
                        <label class="custom-file-label" for="taskImage">Choose image file...</label>
                    </div>
                    <small class="form-text text-muted">Upload graph, chart, diagram, or map for Writing Task 1</small>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
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
        </form>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<style>
.bg-info-light { background-color: rgba(23, 162, 184, 0.1); }
.skill-section:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
</style>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 250,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table']],
                    ['view', ['codeview']]
                ]
            });
            
            // Custom file input label update
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').text(fileName || 'Choose file...');
            });
            
            // Show/hide Writing Task Type and Speaking Part based on skill selection
            $('#skillSelectCreate').on('change', function() {
                const skill = $(this).val();
                
                if (skill === 'writing') {
                    $('#writingTaskTypeRowCreate').slideDown();
                    $('#writingTaskTypeCreate').prop('required', true);
                    $('#speakingPartTypeRowCreate').slideUp();
                    $('#speakingPartTypeCreate').prop('required', false).val('');
                } else if (skill === 'speaking') {
                    $('#speakingPartTypeRowCreate').slideDown();
                    $('#speakingPartTypeCreate').prop('required', true);
                    $('#writingTaskTypeRowCreate').slideUp();
                    $('#writingTaskTypeCreate').prop('required', false).val('');
                } else {
                    $('#writingTaskTypeRowCreate').slideUp();
                    $('#writingTaskTypeCreate').prop('required', false).val('');
                    $('#speakingPartTypeRowCreate').slideUp();
                    $('#speakingPartTypeCreate').prop('required', false).val('');
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/question_groups/create.blade.php ENDPATH**/ ?>