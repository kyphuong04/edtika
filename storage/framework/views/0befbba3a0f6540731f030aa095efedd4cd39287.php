

<?php $__env->startSection('content'); ?>
<section>
    
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark"><?php echo e(ucfirst($bankType)); ?> Question Groups</h1>
            <p class="text-gray-500 font-14 mt-4">Manage groups of questions with shared passages/audio</p>
        </div>
        <div class="d-flex gap-8">
            <a href="<?php echo e(route('panel.question_bank.' . $bankType . '.list')); ?>" class="btn btn-outline-secondary">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-document'); ?>
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
                Standalone Questions
            </a>
            <a href="<?php echo e(route('panel.question_bank.groups.create', $bankType)); ?>" class="btn btn-primary">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-add-circle'); ?>
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
                Create Group
            </a>
        </div>
    </div>

    
    <div class="bg-white p-16 rounded-24 mb-24">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3 mb-8">
                <label class="font-12 text-gray-500 mb-8">Skill</label>
                <select name="skill" class="form-control">
                    <option value="">All Skills</option>
                    <option value="listening" <?php echo e(request('skill') == 'listening' ? 'selected' : ''); ?>>🎧 Listening</option>
                    <option value="reading" <?php echo e(request('skill') == 'reading' ? 'selected' : ''); ?>>📖 Reading</option>
                    <option value="writing" <?php echo e(request('skill') == 'writing' ? 'selected' : ''); ?>>✍️ Writing</option>
                    <option value="speaking" <?php echo e(request('skill') == 'speaking' ? 'selected' : ''); ?>>🗣️ Speaking</option>
                </select>
            </div>

            <div class="col-md-3 mb-8">
                <label class="font-12 text-gray-500 mb-8">Difficulty</label>
                <select name="difficulty" class="form-control">
                    <option value="">All Levels</option>
                    <option value="beginner" <?php echo e(request('difficulty') == 'beginner' ? 'selected' : ''); ?>>Beginner</option>
                    <option value="intermediate" <?php echo e(request('difficulty') == 'intermediate' ? 'selected' : ''); ?>>Intermediate</option>
                    <option value="advanced" <?php echo e(request('difficulty') == 'advanced' ? 'selected' : ''); ?>>Advanced</option>
                </select>
            </div>

            <div class="col-md-4 mb-8">
                <label class="font-12 text-gray-500 mb-8">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search group title..." value="<?php echo e(request('search')); ?>">
            </div>

            <div class="col-md-2 mb-8">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    
    <?php if($groups->count() > 0): ?>
        <div class="d-grid grid-columns-auto grid-lg-columns-2 gap-20 mb-24">
            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-20 rounded-24 hover-shadow" style="transition: all 0.3s;">
                    
                    <div class="d-flex align-items-start justify-content-between mb-16">
                        <div class="flex-1">
                            <h4 class="font-16 font-weight-bold text-dark mb-8"><?php echo e($group->title); ?></h4>
                            <div class="d-flex align-items-center gap-8 flex-wrap">
                                
                                <span class="badge badge-<?php echo e($group->skill === 'listening' ? 'primary' : ($group->skill === 'reading' ? 'info' : ($group->skill === 'writing' ? 'danger' : 'success'))); ?>">
                                    <?php echo e($group->skill_label); ?>

                                </span>
                                
                                
                                <span class="badge badge-<?php echo e($group->difficulty_badge); ?>">
                                    <?php echo e(ucfirst($group->difficulty_level)); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    
                    <?php if($group->description): ?>
                        <p class="text-gray-600 font-14 mb-12" style="line-height: 1.5;">
                            <?php echo e(Str::limit($group->description, 120)); ?>

                        </p>
                    <?php endif; ?>

                    
                    <div class="d-flex align-items-center gap-16 mb-16 pb-16" style="border-bottom: 1px solid #f3f4f6;">
                        <div class="d-flex align-items-center gap-6">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-message-question'); ?>
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
                            <span class="font-14 text-gray-600"><?php echo e($group->question_count); ?> questions</span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-6">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-chart'); ?>
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
                            <span class="font-14 text-gray-600">Used <?php echo e($group->usage_count); ?>x</span>
                        </div>
                    </div>

                    
                    <?php if($group->tags && is_array($group->tags)): ?>
                        <div class="d-flex flex-wrap gap-6 mb-16">
                            <?php $__currentLoopData = array_slice($group->tags, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge badge-light font-12"><?php echo e($tag); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(count($group->tags) > 3): ?>
                                <span class="badge badge-light font-12">+<?php echo e(count($group->tags) - 3); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    
                    <div class="d-flex gap-8">
                        <a href="<?php echo e(route('panel.question_bank.groups.edit', [$bankType, $group->id])); ?>" class="btn btn-sm btn-outline-primary flex-1">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-edit'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-4','width' => '14px','height' => '14px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            Edit
                        </a>
                        <a href="<?php echo e(route('panel.question_bank.groups.delete', [$bankType, $group->id])); ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete this group and <?php echo e($group->question_count); ?> questions?')">
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
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php echo e($groups->appends(request()->query())->links()); ?>

    <?php else: ?>
        
        <div class="bg-white p-40 rounded-24 text-center">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-folder-open'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400 mb-16','width' => '64px','height' => '64px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            <h3 class="font-18 font-weight-bold text-dark mb-8">No Question Groups Yet</h3>
            <p class="text-gray-500 font-14 mb-24">Create your first group to organize questions under one passage or audio</p>
            <a href="<?php echo e(route('panel.question_bank.groups.create', $bankType)); ?>" class="btn btn-primary">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-add-circle'); ?>
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
                Create First Group
            </a>
        </div>
    <?php endif; ?>
</section>

<style>
.hover-shadow {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.hover-shadow:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/question_bank/group_list.blade.php ENDPATH**/ ?>