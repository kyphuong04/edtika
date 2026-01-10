

<?php $__env->startSection('content'); ?>
<section class="qb-dashboard">
    
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Question Bank</h1>
            <p class="text-gray-500 font-14 mt-4">Centralized repository for Mock and Practice test questions</p>
        </div>
        <div class="d-flex align-items-center gap-12">
            <a href="<?php echo e(route('panel.question_bank.create')); ?>" class="btn btn-primary">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-add-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                Add Question
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    Import
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <h6 class="dropdown-header">Import by Skill</h6>
                    <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'listening')); ?>">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-headphone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8 text-primary','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?> Listening
                    </a>
                    <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'reading')); ?>">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-book'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8 text-primary','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?> Reading
                    </a>
                    <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'writing')); ?>">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-edit-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8 text-primary','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?> Writing
                    </a>
                    <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'speaking')); ?>">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-microphone-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-8 text-primary','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?> Speaking
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="qb-kpi-grid mb-24">
        
        <div class="qb-kpi-card">
            <div class="qb-kpi-icon bg-primary-10">
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
            <div class="qb-kpi-content">
                <span class="qb-kpi-label">Total Questions</span>
                <span class="qb-kpi-value"><?php echo e($mockStats['total'] + $practiceStats['total']); ?></span>
                <span class="qb-kpi-meta"><?php echo e($mockStats['total']); ?>M • <?php echo e($practiceStats['total']); ?>P</span>
            </div>
        </div>

        
        <div class="qb-kpi-card">
            <div class="qb-kpi-icon bg-primary-10">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-award'); ?>
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
            <div class="qb-kpi-content">
                <span class="qb-kpi-label">Top Skill</span>
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
                <span class="qb-kpi-value"><?php echo e($maxSkill); ?></span>
                <span class="qb-kpi-meta"><?php echo e($maxCount); ?> questions</span>
            </div>
        </div>

        
        <div class="qb-kpi-card">
            <div class="qb-kpi-icon bg-primary-10">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-calendar-tick'); ?>
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
            <div class="qb-kpi-content">
                <span class="qb-kpi-label">This Week</span>
                <span class="qb-kpi-value"><?php echo e($recentMock->count() + $recentPractice->count()); ?></span>
                <span class="qb-kpi-meta">New questions</span>
            </div>
        </div>

        
        <div class="qb-kpi-card">
            <div class="qb-kpi-icon bg-primary-10">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-chart-success'); ?>
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
            <div class="qb-kpi-content">
                <span class="qb-kpi-label">Coverage</span>
                <?php
                    $coveredSkills = 0;
                    if ($mockStats['listening'] > 0 || $practiceStats['listening'] > 0) $coveredSkills++;
                    if ($mockStats['reading'] > 0 || $practiceStats['reading'] > 0) $coveredSkills++;
                    if ($mockStats['writing'] > 0 || $practiceStats['writing'] > 0) $coveredSkills++;
                    if ($mockStats['speaking'] > 0 || $practiceStats['speaking'] > 0) $coveredSkills++;
                    $coveragePercent = round(($coveredSkills / 4) * 100);
                ?>
                <span class="qb-kpi-value"><?php echo e($coveragePercent); ?>%</span>
                <span class="qb-kpi-meta"><?php echo e($coveredSkills); ?>/4 skills</span>
            </div>
        </div>
    </div>

    
    <div class="row mb-24">
        <div class="col-lg-6 mb-16 mb-lg-0">
            <div class="qb-bank-card qb-bank-mock">
                <div class="qb-bank-header">
                    <div class="qb-bank-icon">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-note-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <div>
                        <h4 class="qb-bank-title">Mock Bank</h4>
                        <p class="qb-bank-subtitle">Full test simulations</p>
                    </div>
                </div>
                
                <div class="qb-bank-stats">
                    <div class="qb-bank-stat">
                        <span class="qb-stat-value"><?php echo e($mockStats['total']); ?></span>
                        <span class="qb-stat-label">Questions</span>
                    </div>
                    <div class="qb-bank-stat">
                        <span class="qb-stat-value"><?php echo e($mockStats['groups']); ?></span>
                        <span class="qb-stat-label">Groups</span>
                    </div>
                    <div class="qb-bank-stat">
                        <span class="qb-stat-value"><?php echo e($mockStats['grouped']); ?></span>
                        <span class="qb-stat-label">In Groups</span>
                    </div>
                </div>
                
                <a href="<?php echo e(route('panel.question_bank.groups', 'mock')); ?>" class="qb-bank-btn">
                    View Mock Groups
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons ml-8','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </a>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="qb-bank-card qb-bank-practice">
                <div class="qb-bank-header">
                    <div class="qb-bank-icon">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-task-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <div>
                        <h4 class="qb-bank-title">Practice Bank</h4>
                        <p class="qb-bank-subtitle">Skill-focused exercises</p>
                    </div>
                </div>
                
                <div class="qb-bank-stats">
                    <div class="qb-bank-stat">
                        <span class="qb-stat-value"><?php echo e($practiceStats['total']); ?></span>
                        <span class="qb-stat-label">Questions</span>
                    </div>
                    <div class="qb-bank-stat">
                        <span class="qb-stat-value"><?php echo e($practiceStats['groups']); ?></span>
                        <span class="qb-stat-label">Groups</span>
                    </div>
                    <div class="qb-bank-stat">
                        <span class="qb-stat-value"><?php echo e($practiceStats['grouped']); ?></span>
                        <span class="qb-stat-label">In Groups</span>
                    </div>
                </div>
                
                <a href="<?php echo e(route('panel.question_bank.groups', 'practice')); ?>" class="qb-bank-btn">
                    View Practice Groups
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons ml-8','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-8 mb-24">
            <div class="qb-section-card">
                <h4 class="qb-section-title">Question Distribution by Skill</h4>
                
                <div class="qb-skills-grid">
                    
                    <div class="qb-skill-card">
                        <div class="qb-skill-icon qb-skill-listening">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-headphone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <h5 class="qb-skill-name">Listening</h5>
                        <span class="qb-skill-count"><?php echo e($mockStats['listening'] + $practiceStats['listening']); ?></span>
                        <span class="qb-skill-breakdown">M: <?php echo e($mockStats['listening']); ?> • P: <?php echo e($practiceStats['listening']); ?></span>
                    </div>

                    
                    <div class="qb-skill-card">
                        <div class="qb-skill-icon qb-skill-reading">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-book'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <h5 class="qb-skill-name">Reading</h5>
                        <span class="qb-skill-count"><?php echo e($mockStats['reading'] + $practiceStats['reading']); ?></span>
                        <span class="qb-skill-breakdown">M: <?php echo e($mockStats['reading']); ?> • P: <?php echo e($practiceStats['reading']); ?></span>
                    </div>

                    
                    <div class="qb-skill-card">
                        <div class="qb-skill-icon qb-skill-writing">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-edit-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <h5 class="qb-skill-name">Writing</h5>
                        <span class="qb-skill-count"><?php echo e($mockStats['writing'] + $practiceStats['writing']); ?></span>
                        <span class="qb-skill-breakdown">M: <?php echo e($mockStats['writing']); ?> • P: <?php echo e($practiceStats['writing']); ?></span>
                    </div>

                    
                    <div class="qb-skill-card">
                        <div class="qb-skill-icon qb-skill-speaking">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-microphone-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <h5 class="qb-skill-name">Speaking</h5>
                        <span class="qb-skill-count"><?php echo e($mockStats['speaking'] + $practiceStats['speaking']); ?></span>
                        <span class="qb-skill-breakdown">M: <?php echo e($mockStats['speaking']); ?> • P: <?php echo e($practiceStats['speaking']); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="qb-section-card mt-24">
                <div class="d-flex align-items-center justify-content-between mb-16">
                    <h4 class="qb-section-title mb-0">Recent Mock Questions</h4>
                    <a href="<?php echo e(route('panel.question_bank.mock.list')); ?>" class="qb-link-btn">
                        View All <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons ml-4','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </a>
                </div>

                <?php if($recentMock->count() > 0): ?>
                    <div class="qb-question-list">
                        <?php $__currentLoopData = $recentMock->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="qb-question-item">
                                <div class="qb-question-skill qb-skill-<?php echo e($question->skill); ?>">
                                    <?php if($question->skill === 'listening'): ?>
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-headphone'); ?>
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
                                    <?php elseif($question->skill === 'reading'): ?>
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-book'); ?>
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
                                    <?php elseif($question->skill === 'writing'): ?>
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-edit-2'); ?>
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
                                    <?php else: ?>
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-microphone-2'); ?>
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
                                    <?php endif; ?>
                                </div>
                                <div class="qb-question-content">
                                    <div class="qb-question-badges">
                                        <span class="badge badge-<?php echo e($question->difficulty_badge); ?>"><?php echo e(ucfirst($question->difficulty_level)); ?></span>
                                        <span class="badge badge-light"><?php echo e($question->type_name); ?></span>
                                    </div>
                                    <h5 class="qb-question-text"><?php echo e(\Illuminate\Support\Str::limit($question->question_text, 70)); ?></h5>
                                    <span class="qb-question-meta">Used <?php echo e($question->usage_count); ?> times</span>
                                </div>
                                <a href="<?php echo e(route('panel.question_bank.edit', ['mock', $question->id])); ?>" class="qb-question-edit">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-edit'); ?>
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
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="qb-empty-state">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '48px','height' => '48px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <h5>No mock questions yet</h5>
                        <p>Start building your question bank</p>
                        <a href="<?php echo e(route('panel.question_bank.create', ['bank_type' => 'mock'])); ?>" class="btn btn-primary btn-sm">
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

        
        <div class="col-lg-4">
            
            <div class="qb-section-card mb-24">
                <h4 class="qb-section-title">Quick Actions</h4>
                
                <div class="qb-quick-actions">
                    <a href="<?php echo e(route('panel.question_bank.mock.list')); ?>" class="qb-action-item">
                        <div class="qb-action-icon bg-primary-10">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-note-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <span>Browse Mock Questions</span>
                    </a>

                    <a href="<?php echo e(route('panel.question_bank.practice.list')); ?>" class="qb-action-item">
                        <div class="qb-action-icon bg-primary-10">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-task-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <span>Browse Practice Questions</span>
                    </a>

                    <div class="dropdown w-100">
                        <button class="qb-action-item dropdown-toggle w-100 border-0" type="button" data-toggle="dropdown">
                            <div class="qb-action-icon bg-primary-10">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </div>
                            <span>Import from Excel</span>
                        </button>
                        <div class="dropdown-menu w-100">
                            <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'listening')); ?>">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-headphone'); ?>
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
<?php endif; ?> Listening
                            </a>
                            <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'reading')); ?>">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-book'); ?>
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
<?php endif; ?> Reading
                            </a>
                            <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'writing')); ?>">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-edit-2'); ?>
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
<?php endif; ?> Writing
                            </a>
                            <a class="dropdown-item" href="<?php echo e(route('panel.question_bank.import', 'speaking')); ?>">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-microphone-2'); ?>
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
<?php endif; ?> Speaking
                            </a>
                        </div>
                    </div>

                    <a href="<?php echo e(route('panel.question_bank.groups.create', 'mock')); ?>" class="qb-action-item">
                        <div class="qb-action-icon bg-primary-10">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-folder-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <span>Create Question Group</span>
                    </a>
                </div>
            </div>

            
            <div class="qb-section-card">
                <div class="d-flex align-items-center justify-content-between mb-16">
                    <h4 class="qb-section-title mb-0">Recent Practice</h4>
                    <a href="<?php echo e(route('panel.question_bank.practice.list')); ?>" class="qb-link-btn">
                        View All <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons ml-4','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </a>
                </div>

                <?php if($recentPractice->count() > 0): ?>
                    <div class="qb-practice-list">
                        <?php $__currentLoopData = $recentPractice->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="qb-practice-item">
                                <div class="qb-practice-badges">
                                    <span class="badge badge-<?php echo e($question->difficulty_badge); ?>"><?php echo e(ucfirst($question->difficulty_level)); ?></span>
                                    <?php if($question->target_band): ?>
                                        <span class="badge badge-primary">B<?php echo e($question->target_band); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h5 class="qb-practice-text"><?php echo e(\Illuminate\Support\Str::limit($question->question_text, 60)); ?></h5>
                                <span class="qb-practice-meta"><?php echo e($question->skill_label); ?> • Used <?php echo e($question->usage_count); ?>x</span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="qb-empty-state-sm">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-cup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '40px','height' => '40px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <h5>No practice questions</h5>
                        <a href="<?php echo e(route('panel.question_bank.create', ['bank_type' => 'practice'])); ?>" class="btn btn-primary btn-sm mt-12">
                            Add Question
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
/* Dashboard Variables */
.qb-dashboard {
    --primary-color: #1a3a5c;
    --primary-light: rgba(26, 58, 92, 0.1);
    --primary-gradient: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%);
}

/* KPI Grid */
.qb-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media (max-width: 992px) {
    .qb-kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .qb-kpi-grid {
        grid-template-columns: 1fr;
    }
}

.qb-kpi-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.qb-kpi-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    flex-shrink: 0;
}

.bg-primary-10 {
    background: var(--primary-light);
}

.qb-kpi-content {
    display: flex;
    flex-direction: column;
}

.qb-kpi-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

.qb-kpi-value {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-top: 4px;
}

.qb-kpi-meta {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 2px;
}

/* Bank Cards */
.qb-bank-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    height: 100%;
}

.qb-bank-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.qb-bank-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: var(--primary-gradient);
    color: white;
}

.qb-bank-title {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.qb-bank-subtitle {
    font-size: 13px;
    color: #6b7280;
    margin: 4px 0 0;
}

.qb-bank-stats {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.qb-bank-stat {
    flex: 1;
    text-align: center;
    padding: 16px 12px;
    background: #f9fafb;
    border-radius: 12px;
}

.qb-stat-value {
    display: block;
    font-size: 24px;
    font-weight: 700;
    color: var(--primary-color);
}

.qb-stat-label {
    display: block;
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.qb-bank-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 14px 20px;
    background: var(--primary-gradient);
    color: white;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s;
}

.qb-bank-btn:hover {
    color: white;
    opacity: 0.9;
    transform: translateY(-1px);
}

.qb-bank-practice .qb-bank-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.qb-bank-practice .qb-stat-value {
    color: #10b981;
}

.qb-bank-practice .qb-bank-btn {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* Section Card */
.qb-section-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.qb-section-title {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 20px;
}

/* Skills Grid */
.qb-skills-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media (max-width: 768px) {
    .qb-skills-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.qb-skill-card {
    text-align: center;
    padding: 24px 16px;
    background: #f9fafb;
    border-radius: 16px;
    transition: all 0.2s;
}

.qb-skill-card:hover {
    background: #f3f4f6;
    transform: translateY(-2px);
}

.qb-skill-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    margin: 0 auto 16px;
    color: white;
}

.qb-skill-listening {
    background: var(--primary-gradient);
}

.qb-skill-reading {
    background: linear-gradient(135deg, #2e5a8a 0%, #3b82f6 100%);
}

.qb-skill-writing {
    background: var(--primary-gradient);
}

.qb-skill-speaking {
    background: linear-gradient(135deg, #2e5a8a 0%, #3b82f6 100%);
}

.qb-skill-name {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 8px;
}

.qb-skill-count {
    display: block;
    font-size: 28px;
    font-weight: 700;
    color: var(--primary-color);
}

.qb-skill-breakdown {
    display: block;
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
}

/* Question List */
.qb-question-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.qb-question-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 12px;
    transition: all 0.2s;
}

.qb-question-item:hover {
    background: #f3f4f6;
}

.qb-question-skill {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    flex-shrink: 0;
    color: white;
}

.qb-question-skill.qb-skill-listening { background: var(--primary-gradient); }
.qb-question-skill.qb-skill-reading { background: linear-gradient(135deg, #2e5a8a 0%, #3b82f6 100%); }
.qb-question-skill.qb-skill-writing { background: var(--primary-gradient); }
.qb-question-skill.qb-skill-speaking { background: linear-gradient(135deg, #2e5a8a 0%, #3b82f6 100%); }

.qb-question-content {
    flex: 1;
    min-width: 0;
}

.qb-question-badges {
    display: flex;
    gap: 6px;
    margin-bottom: 8px;
}

.qb-question-text {
    font-size: 14px;
    font-weight: 500;
    color: #1f2937;
    margin: 0;
    line-height: 1.5;
}

.qb-question-meta {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
}

.qb-question-edit {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #6b7280;
    transition: all 0.2s;
}

.qb-question-edit:hover {
    background: white;
    color: var(--primary-color);
}

/* Quick Actions */
.qb-quick-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.qb-action-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: #f9fafb;
    border-radius: 12px;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
}

.qb-action-item:hover {
    background: #f3f4f6;
    color: var(--primary-color);
}

.qb-action-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    flex-shrink: 0;
}

/* Practice List */
.qb-practice-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.qb-practice-item {
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.qb-practice-item:first-child {
    padding-top: 0;
    border-top: none;
}

.qb-practice-badges {
    display: flex;
    gap: 6px;
    margin-bottom: 8px;
}

.qb-practice-text {
    font-size: 13px;
    font-weight: 500;
    color: #1f2937;
    margin: 0;
    line-height: 1.5;
}

.qb-practice-meta {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
}

/* Link Button */
.qb-link-btn {
    display: inline-flex;
    align-items: center;
    color: var(--primary-color);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
}

.qb-link-btn:hover {
    color: #2e5a8a;
}

/* Empty States */
.qb-empty-state {
    text-align: center;
    padding: 40px 20px;
    background: #f9fafb;
    border-radius: 12px;
}

.qb-empty-state h5 {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin: 16px 0 4px;
}

.qb-empty-state p {
    font-size: 13px;
    color: #9ca3af;
    margin-bottom: 16px;
}

.qb-empty-state-sm {
    text-align: center;
    padding: 32px 16px;
    background: #f9fafb;
    border-radius: 12px;
}

.qb-empty-state-sm h5 {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin: 12px 0 0;
}

/* Utilities */
.gap-12 { gap: 12px; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/question_bank/dashboard.blade.php ENDPATH**/ ?>