

<?php $__env->startSection('content'); ?>
<section>
    
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Mock Question Bank</h1>
            <p class="text-gray-500 font-14 mt-4">Browse by topic • <?php echo e($questions->total()); ?> questions</p>
        </div>
        <div class="d-flex gap-8">
            
            <div class="btn-group">
                <a href="?<?php echo e(http_build_query(array_merge(request()->except('view'), ['view' => 'table']))); ?>" 
                   class="btn btn-sm btn-<?php echo e($viewMode === 'table' ? 'primary' : 'outline-secondary'); ?>">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-menu'); ?>
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
                <a href="?<?php echo e(http_build_query(array_merge(request()->except('view'), ['view' => 'card']))); ?>" 
                   class="btn btn-sm btn-<?php echo e($viewMode === 'card' ? 'primary' : 'outline-secondary'); ?>">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-element-3'); ?>
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
<?php endif; ?>Add
            </a>
        </div>
    </div>

    
    <form method="GET" class="bg-white p-16 rounded-24 mb-16">
        <div class="row align-items-center">
            <div class="col-md-2">
                <select name="skill" class="form-control form-control-sm">
                    <option value="">All Skills</option>
                    <option value="listening" <?php echo e(request('skill') == 'listening' ? 'selected' : ''); ?>>🎧 Listening</option>
                    <option value="reading" <?php echo e(request('skill') == 'reading' ? 'selected' : ''); ?>>📖 Reading</option>
                    <option value="writing" <?php echo e(request('skill') == 'writing' ? 'selected' : ''); ?>>✍️ Writing</option>
                    <option value="speaking" <?php echo e(request('skill') == 'speaking' ? 'selected' : ''); ?>>🗣️ Speaking</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="difficulty" class="form-control form-control-sm">
                    <option value="">All Levels</option>
                    <option value="beginner" <?php echo e(request('difficulty') == 'beginner' ? 'selected' : ''); ?>>⚡ Beginner</option>
                    <option value="intermediate" <?php echo e(request('difficulty') == 'intermediate' ? 'selected' : ''); ?>>⚡ Intermediate</option>
                    <option value="advanced" <?php echo e(request('difficulty') == 'advanced' ? 'selected' : ''); ?>>⚡ Advanced</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="🔍 Search question text..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
        </div>
        <input type="hidden" name="view" value="<?php echo e($viewMode); ?>">
        <input type="hidden" name="topic" value="<?php echo e(request('topic')); ?>">
    </form>

    <div class="row">
        
        <div class="col-lg-3">
            <div class="bg-white rounded-24 p-16 sticky-sidebar">
                <h4 class="font-12 font-weight-bold text-gray-500 mb-12 text-uppercase">Topics</h4>
                
                <a href="?" class="topic-item d-flex align-items-center justify-content-between p-12 rounded-12 mb-4 <?php echo e(!request('topic') ? 'active' : ''); ?>">
                    <span class="font-14">All Topics</span>
                    <span class="badge badge-light"><?php echo e($questions->total()); ?></span>
                </a>

                <?php if($topics && count($topics) > 0): ?>
                    <?php $__currentLoopData = array_slice($topics, 0, 15); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="?topic=<?php echo e(urlencode($topic)); ?>&view=<?php echo e($viewMode); ?>" 
                           class="topic-item d-flex align-items-center justify-content-between p-12 rounded-12 mb-4 <?php echo e(request('topic') === $topic ? 'active' : ''); ?>">
                            <span class="font-14"><?php echo e(ucfirst($topic)); ?></span>
                            <span class="badge"><?php echo e($count); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center p-16 text-gray-500 font-12">No topics yet</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="col-lg-9">
            <?php if($questions->count() > 0): ?>
                <?php if($viewMode === 'card'): ?>
                    
                    <div class="question-grid">
                        <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="question-card">
                                
                                <?php
                                    $skillInfo = [
                                        'listening' => ['icon' => '🎧', 'color' => 'skill-listening', 'label' => 'Listening'],
                                        'reading' => ['icon' => '📖', 'color' => 'skill-reading', 'label' => 'Reading'],
                                        'writing' => ['icon' => '✍️', 'color' => 'skill-writing', 'label' => 'Writing'],
                                        'speaking' => ['icon' => '🗣️', 'color' => 'skill-speaking', 'label' => 'Speaking'],
                                    ];
                                    $skill = $skillInfo[$question->skill] ?? ['icon' => '📝', 'color' => 'skill-default', 'label' => ucfirst($question->skill)];
                                ?>
                                
                                <div class="skill-indicator <?php echo e($skill['color']); ?>">
                                    <span class="skill-icon"><?php echo e($skill['icon']); ?></span>
                                    <span class="skill-label"><?php echo e($skill['label']); ?></span>
                                </div>

                                
                                <h5 class="question-text"><?php echo e(\Illuminate\Support\Str::limit($question->question_text, 120)); ?></h5>

                                
                                <div class="question-meta">
                                    <div class="meta-badges">
                                        <span class="badge badge-<?php echo e($question->difficulty_badge); ?>">
                                            <?php echo e(ucfirst($question->difficulty_level)); ?>

                                        </span>
                                        <span class="badge badge-light"><?php echo e($question->type_name); ?></span>
                                    </div>
                                    <div class="usage-count">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-chart-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        <span><?php echo e($question->usage_count); ?></span>
                                    </div>
                                </div>

                                
                                <?php if($question->tags && count($question->tags) > 0): ?>
                                    <div class="question-tags">
                                        <?php $__currentLoopData = array_slice($question->tags, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="?topic=<?php echo e(urlencode($tag)); ?>&view=card" class="tag-link"><?php echo e($tag); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>

                                
                                <div class="question-actions">
                                    <a href="<?php echo e(route('panel.question_bank.edit', ['mock', $question->id])); ?>" class="btn-action btn-edit">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-edit'); ?>
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
                                    <a href="<?php echo e(route('panel.question_bank.delete', ['mock', $question->id])); ?>" 
                                       class="btn-action btn-delete" onclick="return confirm('Delete this question?')">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-trash'); ?>
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
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    
                    <div class="question-table-container">
                        <table class="question-table">
                            <thead>
                                <tr>
                                    <th width="50">Skill</th>
                                    <th>Question</th>
                                    <th width="120">Level</th>
                                    <th width="80">Used</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $skillInfo = [
                                            'listening' => ['icon' => '🎧', 'color' => 'skill-listening', 'label' => 'L'],
                                            'reading' => ['icon' => '📖', 'color' => 'skill-reading', 'label' => 'R'],
                                            'writing' => ['icon' => '✍️', 'color' => 'skill-writing', 'label' => 'W'],
                                            'speaking' => ['icon' => '🗣️', 'color' => 'skill-speaking', 'label' => 'S'],
                                        ];
                                        $skill = $skillInfo[$question->skill] ?? ['icon' => '📝', 'color' => '', 'label' => '?'];
                                    ?>
                                    <tr>
                                        <td class="skill-cell">
                                            <div class="skill-badge <?php echo e($skill['color']); ?>">
                                                <span class="skill-icon-sm"><?php echo e($skill['icon']); ?></span>
                                            </div>
                                        </td>
                                        <td class="question-cell">
                                            <div class="question-title"><?php echo e(\Illuminate\Support\Str::limit($question->question_text, 100)); ?></div>
                                            <div class="question-info">
                                                <span class="info-badge"><?php echo e($question->type_name); ?></span>
                                                <?php if($question->tags): ?>
                                                    <?php $__currentLoopData = array_slice($question->tags, 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <a href="?topic=<?php echo e(urlencode($tag)); ?>&view=table" class="tag-link-sm"><?php echo e($tag); ?></a>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="level-cell">
                                            <span class="badge badge-<?php echo e($question->difficulty_badge); ?>">
                                                <?php echo e(ucfirst($question->difficulty_level)); ?>

                                            </span>
                                        </td>
                                        <td class="usage-cell">
                                            <div class="usage-indicator">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-chart-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                <span><?php echo e($question->usage_count); ?></span>
                                            </div>
                                        </td>
                                        <td class="actions-cell">
                                            <div class="table-actions">
                                                <a href="<?php echo e(route('panel.question_bank.edit', ['mock', $question->id])); ?>" class="btn-action-sm">
                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-edit'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                </a>
                                                <a href="<?php echo e(route('panel.question_bank.delete', ['mock', $question->id])); ?>" 
                                                   class="btn-action-sm btn-delete" onclick="return confirm('Delete?')">
                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-trash'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

                        
                        <div class="table-pagination">
                            <?php echo e($questions->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                
                <div class="empty-state">
                    <div class="empty-icon">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-search-normal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '48px','height' => '48px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <h4><?php echo e(request('topic') ? 'No "'.request('topic').'" questions' : 'No questions found'); ?></h4>
                    <p>Try adjusting filters or add new questions</p>
                    <a href="<?php echo e(route('panel.question_bank.create', ['bank_type' => 'mock'])); ?>" class="btn btn-primary">
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
<?php endif; ?>Add Mock Question
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Sidebar Topics */
.sticky-sidebar {
    position: sticky;
    top: 20px;
}

.topic-item {
    text-decoration: none;
    color: #374151;
    transition: all 0.2s;
}

.topic-item:hover {
    background-color: #f3f4f6;
    transform: translateX(4px);
}

.topic-item.active {
    background-color: rgba(88, 103, 221, 0.1);
    color: #5867dd;
    font-weight: 600;
}

.topic-item.active .badge {
    background-color: #5867dd;
    color: white;
}

/* Card View */
.question-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.question-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.question-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.skill-indicator {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}

.skill-listening {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.skill-reading {
    background: linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%);
    color: white;
}

.skill-writing {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.skill-speaking {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.question-text {
    font-size: 15px;
    line-height: 1.6;
    color: #1f2937;
    margin-bottom: 16px;
    min-height: 48px;
}

.question-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.meta-badges {
    display: flex;
    gap: 6px;
}

.usage-count {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #6b7280;
    font-size: 13px;
}

.question-tags {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

.tag-link {
    font-size: 12px;
    padding: 4px 10px;
    background: #f3f4f6;
    border-radius: 6px;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.2s;
}

.tag-link:hover {
    background: #e5e7eb;
    color: #374151;
}

.question-actions {
    display: flex;
    gap: 8px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.btn-action {
    flex: 1;
    padding: 8px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    border: 1px solid #e5e7eb;
}

.btn-edit:hover {
    background: #f3f4f6;
    border-color: #5867dd;
    color: #5867dd;
}

.btn-delete:hover {
    background: #fee2e2;
    border-color: #ef4444;
    color: #ef4444;
}

/* Table View */
.question-table-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.question-table {
    width: 100%;
    border-collapse: collapse;
}

.question-table thead {
    background: #f9fafb;
}

.question-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    border-bottom: 2px solid #e5e7eb;
}

.question-table tbody tr {
    transition: all 0.2s;
    border-bottom: 1px solid #f3f4f6;
}

.question-table tbody tr:hover {
    background: #f9fafb;
}

.question-table td {
    padding: 16px;
    vertical-align: middle;
}

.skill-cell {
    text-align: center;
}

.skill-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    font-size: 20px;
}

.skill-badge.skill-listening {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.skill-badge.skill-reading {
    background: linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%);
}

.skill-badge.skill-writing {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.skill-badge.skill-speaking {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.question-cell {
    max-width: 500px;
}

.question-title {
    font-size: 14px;
    color: #1f2937;
    margin-bottom: 6px;
    line-height: 1.5;
}

.question-info {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.info-badge {
    font-size: 11px;
    padding: 2px 8px;
    background: #f3f4f6;
    border-radius: 4px;
    color: #6b7280;
}

.tag-link-sm {
    font-size: 11px;
    padding: 2px 8px;
    background: #eff6ff;
    border-radius: 4px;
    color: #3b82f6;
    text-decoration: none;
}

.tag-link-sm:hover {
    background: #dbeafe;
}

.level-cell {
    text-align: center;
}

.usage-cell {
    text-align: center;
}

.usage-indicator {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #6b7280;
}

.actions-cell {
    text-align: center;
}

.table-actions {
    display: flex;
    gap: 6px;
    justify-content: center;
}

.btn-action-sm {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.btn-action-sm:hover {
    background: #f3f4f6;
    border-color: #5867dd;
}

.btn-action-sm.btn-delete:hover {
    background: #fee2e2;
    border-color: #ef4444;
}

.table-pagination {
    padding: 20px;
    border-top: 1px solid #f3f4f6;
}

/* Empty State */
.empty-state {
    background: white;
    border-radius: 16px;
    padding: 80px 40px;
    text-align: center;
}

.empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    border-radius: 16px;
    background: #f3f4f6;
    color: #9ca3af;
    margin-bottom: 20px;
}

.empty-state h4 {
    font-size: 18px;
    color: #1f2937;
    margin-bottom: 8px;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 24px;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/question_bank/mock_list.blade.php ENDPATH**/ ?>