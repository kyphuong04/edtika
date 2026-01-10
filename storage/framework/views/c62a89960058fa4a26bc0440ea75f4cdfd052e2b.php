

<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <style>
        .passage-container {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            position: sticky;
            top: 20px;
        }
        .passage-content {
            font-size: 15px;
            line-height: 1.8;
            color: #374151;
        }
        .passage-content p { margin-bottom: 16px; text-align: justify; }
        .question-form-section {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
        }
        .questions-list .question-item {
            background: #fff;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        .questions-list .question-item:hover {
            border-color: #28c76f;
            box-shadow: 0 2px 8px rgba(40, 199, 111, 0.1);
        }
        .skill-badge-listening { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .skill-badge-reading { background: linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%); }
        .skill-badge-writing { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .skill-badge-speaking { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section>
    
    <div class="bg-white rounded-16 shadow-sm p-24 mb-24" style="border-radius: 12px;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                
                <div class="skill-badge skill-badge-<?php echo e($group->skill); ?> d-flex-center rounded-12 mr-16" style="width: 56px; height: 56px;">
                    <?php if($group->skill === 'listening'): ?>
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-headphone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <?php elseif($group->skill === 'reading'): ?>
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-book'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <?php elseif($group->skill === 'writing'): ?>
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-edit'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '28px','height' => '28px']); ?>
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
<?php $component->withAttributes(['class' => 'icons text-white','width' => '28px','height' => '28px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <?php endif; ?>
                </div>
                <div>
                    <h1 class="font-20 font-weight-bold text-dark mb-4"><?php echo e($group->title); ?></h1>
                    <div class="d-flex align-items-center gap-8">
                        
                        <span class="badge" style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; <?php echo e($group->bank_type === 'mock' ? 'background: #e3f2fd; color: #1976d2;' : 'background: #e8f5e9; color: #388e3c;'); ?>">
                            <?php echo e(ucfirst($group->bank_type)); ?>

                        </span>
                        
                        
                        <span class="badge" style="display: inline-block; padding: 5px 12px; border-radius: 16px; font-size: 11px; font-weight: 600; 
                            <?php if($group->skill === 'reading'): ?> background: #dbeafe; color: #1e40af;
                            <?php elseif($group->skill === 'listening'): ?> background: #e0e7ff; color: #4338ca;
                            <?php elseif($group->skill === 'writing'): ?> background: #fce7f3; color: #be185d;
                            <?php elseif($group->skill === 'speaking'): ?> background: #d1fae5; color: #065f46;
                            <?php endif; ?>">
                            <?php echo e(ucfirst($group->skill)); ?>

                        </span>
                        
                        
                        <?php if($group->target_band): ?>
                            <span class="badge" style="background: #fdf6b2; color: #723b13; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                <i class="fas fa-star mr-1" style="font-size: 10px;"></i>Band <?php echo e($group->target_band); ?>

                            </span>
                        <?php endif; ?>
                        
                        
                        <?php if(in_array($group->skill, ['reading', 'listening'])): ?>
                            <span class="badge" style="background: #f0f9ff; color: #0369a1; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                <i class="fas fa-list-ol mr-1" style="font-size: 10px;"></i><?php echo e($group->questions->count()); ?> questions
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            
            <div class="d-flex gap-8">
                <a href="<?php echo e(route('panel.question-groups.index', ['type' => $group->bank_type])); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-6"></i>Back
                </a>
                <a href="<?php echo e(route('panel.question-groups.edit', $group->id)); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-edit mr-6"></i>Edit
                </a>
                <?php if($group->status === 'draft' && $group->questions->count() > 0): ?>
                    <form action="<?php echo e(route('panel.question-groups.submit-approval', $group->id)); ?>" method="POST" class="d-inline" 
                          onsubmit="return confirm('Submit this question group for approval?')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-paper-plane mr-6"></i>Submit for Approval
                        </button>
                    </form>
                <?php elseif($group->status === 'pending'): ?>
                    <span class="btn btn-warning btn-sm" style="cursor: default;">
                        <i class="fas fa-clock mr-6"></i>Pending Approval
                    </span>
                <?php elseif($group->status === 'approved'): ?>
                    <span class="btn btn-success btn-sm" style="cursor: default;">
                        <i class="fas fa-check mr-6"></i>Approved
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if(in_array($group->skill, ['reading', 'listening'])): ?>
        
        <div class="row">
            
            <div class="col-lg-5">
                <div class="passage-container bg-white rounded-16 p-20">
                    <?php if($group->instructions): ?>
                        <div class="bg-info-light rounded-12 p-12 mb-16">
                            <p class="font-14 text-dark mb-0"><strong>Instructions:</strong> <?php echo e($group->instructions); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($group->skill === 'reading'): ?>
                        <?php if($group->passage): ?>
                            <div class="passage-content"><?php echo $group->passage; ?></div>
                        <?php else: ?>
                            <div class="text-center py-32">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400 mb-12','width' => '48px','height' => '48px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                <p class="text-gray-500">No passage added yet</p>
                                <a href="<?php echo e(route('panel.question-groups.edit', $group->id)); ?>" class="btn btn-sm btn-outline-primary">Add Passage</a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if($group->audio_url): ?>
                            <div class="audio-player-card mb-16">
                                <div class="audio-header">
                                    <i class="fas fa-headphones-alt text-purple-500 mr-8"></i>
                                    <span class="font-weight-bold">Listening Audio</span>
                                </div>
                                <audio controls class="w-100" preload="metadata">
                                    <source src="<?php echo e($group->audio_url); ?>" type="audio/mpeg">
                                    <source src="<?php echo e($group->audio_url); ?>" type="audio/wav">
                                    <source src="<?php echo e($group->audio_url); ?>" type="audio/ogg">
                                    Your browser does not support the audio element.
                                </audio>
                                <div class="audio-debug mt-8">
                                    <small class="text-muted">
                                        <i class="fas fa-link mr-4"></i>
                                        <a href="<?php echo e($group->audio_url); ?>" target="_blank" class="text-muted">
                                            Open audio in new tab
                                        </a>
                                    </small>
                                </div>
                            </div>
                            <?php if($group->transcript): ?>
                                <h5 class="font-12 font-weight-bold text-gray-500 mt-16 mb-8">
                                    <i class="fas fa-file-alt mr-8"></i>Transcript
                                </h5>
                                <div class="passage-content"><?php echo $group->transcript; ?></div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-32">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-headphone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400 mb-12','width' => '48px','height' => '48px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                <p class="text-gray-500">No audio uploaded yet</p>
                                <a href="<?php echo e(route('panel.question-groups.edit', $group->id)); ?>" class="btn btn-sm btn-outline-primary">Upload Audio</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            
            <div class="col-lg-7">
                <?php echo $__env->make('design_1.panel.question_groups.partials.question_form_rl', ['group' => $group], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
        
    <?php elseif($group->skill === 'writing'): ?>
        
        <?php echo $__env->make('design_1.panel.question_groups.partials.writing_task', ['group' => $group], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
    <?php elseif($group->skill === 'speaking'): ?>
        
        <?php echo $__env->make('design_1.panel.question_groups.partials.speaking_part', ['group' => $group], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Collapse toggle (form loading handled in question_form_rl.blade.php)
            $('[data-toggle="collapse"]').on('click', function() {
                $(this).find('.collapse-arrow').toggleClass('rotate-180');
            });

            // Delete question confirmation with SweetAlert2
            $('.delete-question-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                
                Swal.fire({
                    title: 'Delete Question?',
                    text: 'This will permanently delete this question!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-trash mr-8"></i>Yes, Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Action button hover effects
            $('.action-btn').hover(
                function() { $(this).css('transform', 'scale(1.1)'); },
                function() { $(this).css('transform', 'scale(1)'); }
            );
        });
    </script>
    <style>
        .rotate-180 { transform: rotate(180deg); }
        .bg-info-light { background-color: rgba(23, 162, 184, 0.1); }
        .bg-primary-light { background-color: rgba(102, 126, 234, 0.1); }
        
        /* Gap utilities */
        .gap-8 { gap: 8px; }
        
        /* Action button styles */
        .action-btn { transition: all 0.2s ease; }
        
        /* Audio Player Styles */
        .audio-player-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 16px;
            color: #fff;
        }
        .audio-player-card .audio-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        .audio-player-card audio {
            width: 100%;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
        }
        .audio-player-card audio::-webkit-media-controls-panel {
            background: rgba(255,255,255,0.95);
        }
        .audio-player-card .audio-debug {
            opacity: 0.7;
        }
        .audio-player-card .audio-debug a {
            color: rgba(255,255,255,0.8) !important;
        }
        .audio-player-card .audio-debug a:hover {
            color: #fff !important;
            text-decoration: underline;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/question_groups/show.blade.php ENDPATH**/ ?>