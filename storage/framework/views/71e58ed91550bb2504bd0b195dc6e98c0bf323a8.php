

<?php $__env->startSection('content'); ?>
<section>
    
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Question Bank</h1>
            <p class="text-gray-500 font-14 mt-4">Centralized repository for Mock and Practice test questions</p>
        </div>
        <div class="d-flex align-items-center gap-8">
            <a href="<?php echo e(route('panel.question_bank.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus mr-8"></i>Add Question
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="importDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-excel mr-8"></i>Import
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="importDropdown">
                    <h6 class="dropdown-header">Import by Skill</h6>
                    <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'listening')); ?>">
                        🎧 Listening
                    </a>
                    <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'reading')); ?>">
                        📖 Reading
                    </a>
                    <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'writing')); ?>">
                        ✍️ Writing
                    </a>
                    <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'speaking')); ?>">
                        🗣️ Speaking
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="d-grid grid-columns-auto grid-lg-columns-4 gap-16 mb-24">
        
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">Total Questions</span>
                <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($mockStats['total'] + $practiceStats['total']); ?></span>
                <span class="text-gray-500 font-12 mt-4"><?php echo e($mockStats['total']); ?>M • <?php echo e($practiceStats['total']); ?>P</span>
            </div>
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-archive-book'); ?>
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
        </div>

        
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">Top Skill</span>
                <?php
                    $allSkills = [
                        'Listening' => $mockStats['listening'] + $practiceStats['listening'],
                        'Reading' => $mockStats['reading'] + $practiceStats['reading'],
                        'Writing' => $mockStats['writing'] + $practiceStats['writing'],
                        'Speaking' => $mockStats['speaking'] + $practiceStats['speaking'],
                    ];
                    $maxSkill = array_keys($allSkills, max($allSkills))[0];
                    $maxCount = max($allSkills);
                ?>
                <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($maxSkill); ?></span>
                <span class="text-gray-500 font-12 mt-4"><?php echo e($maxCount); ?> questions</span>
            </div>
            <div class="d-flex-center size-48 rounded-12 bg-success-40">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-award'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>
        </div>

        
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">This Week</span>
                <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($recentMock->count() + $recentPractice->count()); ?></span>
                <span class="text-gray-500 font-12 mt-4">New questions</span>
            </div>
            <div class="d-flex-center size-48 rounded-12 bg-info-40">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clock'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-info','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>
        </div>

        
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">Coverage</span>
                <?php
                    $totalSkills = 4;
                    $coveredSkills = 0;
                    if ($mockStats['listening'] > 0 || $practiceStats['listening'] > 0) $coveredSkills++;
                    if ($mockStats['reading'] > 0 || $practiceStats['reading'] > 0) $coveredSkills++;
                    if ($mockStats['writing'] > 0 || $practiceStats['writing'] > 0) $coveredSkills++;
                    if ($mockStats['speaking'] > 0 || $practiceStats['speaking'] > 0) $coveredSkills++;
                    $coveragePercent = round(($coveredSkills / $totalSkills) * 100);
                ?>
                <span class="font-24 font-weight-bold mt-16 text-dark"><?php echo e($coveragePercent); ?>%</span>
                <span class="text-gray-500 font-12 mt-4"><?php echo e($coveredSkills); ?>/4 skills</span>
            </div>
            <div class="d-flex-center size-48 rounded-12 bg-warning-40">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="row mb-24">
        <div class="col-lg-6">
            <div class="bg-white p-20 rounded-24">
                <h4 class="font-14 font-weight-bold text-dark mb-16">📘 Mock Bank Overview</h4>
                
                <div class="d-flex align-items-center justify-content-between mb-12">
                    <span class="text-gray-600 font-14">Total Questions:</span>
                    <span class="font-16 font-weight-bold text-dark"><?php echo e($mockStats['total']); ?></span>
                </div>
                
                <div class="px-12 py-8 bg-gray-100 rounded-12 mb-8">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-gray-600 font-13">├─ Standalone:</span>
                        <span class="font-14 font-weight-500 text-info"><?php echo e($mockStats['standalone']); ?></span>
                    </div>
                </div>
                
                <div class="px-12 py-8 bg-gray-100 rounded-12 mb-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-gray-600 font-13">└─ In Groups:</span>
                        <span class="font-14 font-weight-500 text-primary"><?php echo e($mockStats['grouped']); ?></span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-between pt-12 border-top">
                    <span class="text-gray-600 font-14">Question Groups:</span>
                    <span class="font-18 font-weight-bold text-primary"><?php echo e($mockStats['groups']); ?></span>
                </div>
                
                <a href="<?php echo e(route('panel.question_bank.groups', 'mock')); ?>" class="btn btn-outline-primary w-100 mt-16">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-note-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    View Mock Groups
                </a>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="bg-white p-20 rounded-24">
                <h4 class="font-14 font-weight-bold text-dark mb-16">📗 Practice Bank Overview</h4>
                
                <div class="d-flex align-items-center justify-content-between mb-12">
                    <span class="text-gray-600 font-14">Total Questions:</span>
                    <span class="font-16 font-weight-bold text-dark"><?php echo e($practiceStats['total']); ?></span>
                </div>
                
                <div class="px-12 py-8 bg-gray-100 rounded-12 mb-8">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-gray-600 font-13">├─ Standalone:</span>
                        <span class="font-14 font-weight-500 text-info"><?php echo e($practiceStats['standalone']); ?></span>
                    </div>
                </div>
                
                <div class="px-12 py-8 bg-gray-100 rounded-12 mb-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-gray-600 font-13">└─ In Groups:</span>
                        <span class="font-14 font-weight-500 text-success"><?php echo e($practiceStats['grouped']); ?></span>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-between pt-12 border-top">
                    <span class="text-gray-600 font-14">Question Groups:</span>
                    <span class="font-18 font-weight-bold text-success"><?php echo e($practiceStats['groups']); ?></span>
                </div>
                
                <a href="<?php echo e(route('panel.question_bank.groups', 'practice')); ?>" class="btn btn-outline-success w-100 mt-16">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-task-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    View Practice Groups
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 mb-24">
            
            <div class="bg-white p-20 rounded-24">
                <h4 class="font-14 font-weight-bold text-dark">Question Distribution by Skill</h4>
                
                <div class="d-grid grid-columns-auto grid-lg-columns-4 gap-16 mt-16">
                    <?php
                        $skills = [
                            ['name' => 'Listening', 'icon' => '🎧', 'mock' => $mockStats['listening'], 'practice' => $practiceStats['listening'], 'color' => 'primary'],
                            ['name' => 'Reading', 'icon' => '📖', 'mock' => $mockStats['reading'], 'practice' => $practiceStats['reading'], 'color' => 'success'],
                            ['name' => 'Writing', 'icon' => '✍️', 'mock' => $mockStats['writing'], 'practice' => $practiceStats['writing'], 'color' => 'warning'],
                            ['name' => 'Speaking', 'icon' => '🗣️', 'mock' => $mockStats['speaking'], 'practice' => $practiceStats['speaking'], 'color' => 'info'],
                        ];
                    ?>

                    <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="text-center p-16 rounded-16 bg-gray-100">
                            <div style="font-size: 32px;"><?php echo e($skill['icon']); ?></div>
                            <h5 class="font-14 font-weight-bold text-dark mt-12"><?php echo e($skill['name']); ?></h5>
                            <div class="font-24 font-weight-bold text-<?php echo e($skill['color']); ?> mt-8">
                                <?php echo e($skill['mock'] + $skill['practice']); ?>

                            </div>
                            <div class="font-12 text-gray-500 mt-4">
                                M: <?php echo e($skill['mock']); ?> • P: <?php echo e($skill['practice']); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="bg-white p-20 rounded-24 mt-24">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-14 font-weight-bold text-dark">Recent Mock Questions</h4>
                    <a href="<?php echo e(route('panel.question_bank.mock.list')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>

                <?php if($recentMock->count() > 0): ?>
                    <?php $__currentLoopData = $recentMock->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-start justify-content-between mt-16 pt-16 border-top">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center gap-8 mb-8">
                                    <span class="badge badge-<?php echo e($question->difficulty_badge); ?>">
                                        <?php echo e(ucfirst($question->difficulty_level)); ?>

                                    </span>
                                    <span class="badge badge-light"><?php echo e($question->type_name); ?></span>
                                </div>
                                <h5 class="font-14 text-dark mb-4">
                                    <?php echo e($question->skill_label); ?>: <?php echo e(\Illuminate\Support\Str::limit($question->question_text, 60)); ?>

                                </h5>
                                <span class="font-12 text-gray-500">
                                    <i class="fas fa-chart-line mr-4"></i>Used <?php echo e($question->usage_count); ?> times
                                </span>
                            </div>
                            <a href="<?php echo e(route('panel.question_bank.edit', ['mock', $question->id])); ?>" 
                               class="btn btn-sm btn-light">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="d-flex-center flex-column text-center bg-gray-100 border-dashed border-gray-200 rounded-16 mt-16 p-40">
                        <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-book'); ?>
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
                        <h4 class="mt-12 font-14 text-dark">No mock questions yet</h4>
                        <div class="font-12 text-gray-500 mt-4">Start building your question bank</div>
                        <a href="<?php echo e(route('panel.question_bank.create', ['bank_type' => 'mock'])); ?>" 
                           class="btn btn-primary btn-sm mt-16">
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
<?php endif; ?>Add First Question
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            
            <div class="bg-white p-20 rounded-24 mb-24">
                <h4 class="font-14 font-weight-bold text-dark mb-16">Quick Actions</h4>
                
                <a href="<?php echo e(route('panel.question_bank.mock.list')); ?>" 
                   class="d-flex align-items-center p-12 rounded-12 bg-gray-100 bg-hover-gray-200 mb-8">
                    <div class="d-flex-center size-32 rounded-8 bg-primary-40 mr-12">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-note-2'); ?>
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
                    </div>
                    <span class="font-14 text-dark">Browse Mock Questions</span>
                </a>

                <a href="<?php echo e(route('panel.question_bank.practice.list')); ?>" 
                   class="d-flex align-items-center p-12 rounded-12 bg-gray-100 bg-hover-gray-200 mb-8">
                    <div class="d-flex-center size-32 rounded-8 bg-success-40 mr-12">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-task-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <span class="font-14 text-dark">Browse Practice Questions</span>
                </a>


                <div class="dropdown w-100">
                    <button class="d-flex align-items-center p-12 rounded-12 bg-gray-100 bg-hover-gray-200 mb-8 border-0 w-100 text-left dropdown-toggle" type="button" id="quickImportDropdown" data-toggle="dropdown">
                        <div class="d-flex-center size-32 rounded-8 bg-info-40 mr-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-info','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <span class="font-14 text-dark">Import from Excel</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="quickImportDropdown">
                        <h6 class="dropdown-header">Select Skill to Import</h6>
                        <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'listening')); ?>">
                            <small>🎧</small> Listening
                        </a>
                        <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'reading')); ?>">
                            <small>📖</small> Reading
                        </a>
                        <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'writing')); ?>">
                            <small>✍️</small> Writing
                        </a>
                        <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'speaking')); ?>">
                            <small>🗣️</small> Speaking
                        </a>
                    </div>
                </div>


                <button onclick="alert('Generate Test - Coming Soon!')" 
                        class="d-flex align-items-center p-12 rounded-12 bg-gray-100 bg-hover-gray-200 border-0 w-100 text-left">
                    <div class="d-flex-center size-32 rounded-8 bg-warning-40 mr-12">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-magic-star'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <span class="font-14 text-dark">Generate Random Test</span>
                </button>

                <?php if($mockStats['total'] === 0 || $practiceStats['total'] === 0): ?>
                    <div class="alert alert-warning mt-16 p-12 rounded-12" role="alert">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <span class="font-12 ml-8">
                            <?php if($mockStats['total'] === 0): ?>
                                No Mock questions yet!
                            <?php elseif($practiceStats['total'] === 0): ?>
                                No Practice questions yet!
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="bg-white p-20 rounded-24">
                <div class="d-flex align-items-center justify-content-between mb-16">
                    <h4 class="font-14 font-weight-bold text-dark">Recent Practice</h4>
                    <a href="<?php echo e(route('panel.question_bank.practice.list')); ?>" class="btn btn-sm btn-outline-success">View All</a>
                </div>

                <?php if($recentPractice->count() > 0): ?>
                    <?php $__currentLoopData = $recentPractice->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex flex-column mt-12 pt-12 border-top">
                            <div class="d-flex align-items-center gap-8 mb-8">
                                <span class="badge badge-<?php echo e($question->difficulty_badge); ?>"><?php echo e(ucfirst($question->difficulty_level)); ?></span>
                                <?php if($question->target_band): ?>
                                    <span class="badge badge-info">B<?php echo e($question->target_band); ?></span>
                                <?php endif; ?>
                            </div>
                            <h5 class="font-13 text-dark"><?php echo e(\Illuminate\Support\Str::limit($question->question_text, 60)); ?></h5>
                            <span class="font-12 text-gray-500 mt-4">
                                <?php echo e($question->skill_label); ?> • Used <?php echo e($question->usage_count); ?>x
                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="d-flex-center flex-column text-center bg-gray-100 border-dashed border-gray-200 rounded-16 p-32">
                        <div class="d-flex-center size-48 rounded-12 bg-success-40">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-cup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <h4 class="mt-12 font-14 text-dark">No practice questions</h4>
                        <a href="<?php echo e(route('panel.question_bank.create', ['bank_type' => 'practice'])); ?>" 
                           class="btn btn-success btn-sm mt-12">
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
<?php endif; ?>Add Question
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.bg-primary-40 { background-color: rgba(88, 103, 221, 0.1); }
.bg-success-40 { background-color: rgba(40, 199, 111, 0.1); }
.bg-info-40 { background-color: rgba(0, 184, 217, 0.1); }
.bg-warning-40 { background-color: rgba(255, 159, 67, 0.1); }
.d-grid { display: grid; }
.grid-columns-auto { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
@media (min-width: 992px) {
    .grid-lg-columns-4 { grid-template-columns: repeat(4, 1fr); }
}
.gap-8 { gap: 8px; }
.gap-16 { gap: 16px; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/question_bank/dashboard.blade.php ENDPATH**/ ?>