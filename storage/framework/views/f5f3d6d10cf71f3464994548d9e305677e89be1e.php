

<?php $__env->startPush('styles_top'); ?>
<style>
    .flashcard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 24px;
    }
    
    .flashcard {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
        min-height: 200px;
    }
    
    .flashcard:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    
    .flashcard.flipped .flashcard-front {
        transform: rotateY(180deg);
    }
    
    .flashcard.flipped .flashcard-back {
        transform: rotateY(0deg);
    }
    
    .flashcard-front, .flashcard-back {
        backface-visibility: hidden;
        transition: transform 0.6s;
    }
    
    .flashcard-back {
        transform: rotateY(180deg);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 24px;
    }
    
    .flashcard-word {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 12px;
    }
    
    .flashcard-pronunciation {
        font-size: 16px;
        color: #666;
        font-family: 'Courier New', monospace;
        margin-bottom: 16px;
    }
    
    .flashcard-definition {
        font-size: 14px;
        color: #444;
        line-height: 1.6;
    }
    
    .delete-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        background: #ff5252;
        color: white;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
    }
    
    .delete-btn:hover {
        background: #ff1744;
        transform: scale(1.1);
    }
    
    .flip-hint {
        position: absolute;
        bottom: 16px;
        right: 16px;
        font-size: 12px;
        color: #999;
        font-style: italic;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state-icon {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 16px;
    }
    
    .empty-state-text {
        font-size: 18px;
        color: #666;
        margin-bottom: 24px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="font-20 font-weight-bold"><?php echo e(trans('panel.my_flashcards')); ?></h2>
                    <a href="<?php echo e(url('/panel/dictionary')); ?>" class="btn btn-primary btn-sm">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-book'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mr-1','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <?php echo e(trans('panel.back_to_dictionary')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-20">
        <div class="col-12">
            <?php if($flashcards->count() > 0): ?>
                <div class="flashcard-grid">
                    <?php $__currentLoopData = $flashcards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flashcard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flashcard" data-id="<?php echo e($flashcard->id); ?>" onclick="flipCard(this)">
                            <button class="delete-btn" onclick="deleteFlashcard(event, <?php echo e($flashcard->id); ?>)">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-trash'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </button>
                            
                            <div class="flashcard-front">
                                <div class="flashcard-word"><?php echo e($flashcard->word); ?></div>
                                <?php if($flashcard->pronunciation): ?>
                                    <div class="flashcard-pronunciation"><?php echo e($flashcard->pronunciation); ?></div>
                                <?php endif; ?>
                                <div class="flip-hint">Click to see definition</div>
                            </div>
                            
                            <div class="flashcard-back">
                                <div class="flashcard-word mb-3"><?php echo e(trans('panel.definition')); ?></div>
                                <div class="flashcard-definition"><?php echo e($flashcard->definition); ?></div>
                                
                                <?php if($flashcard->example): ?>
                                    <div class="mt-3">
                                        <strong><?php echo e(trans('panel.example')); ?>:</strong>
                                        <div class="text-muted mt-1" style="font-style: italic;"><?php echo e($flashcard->example); ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($flashcard->translation): ?>
                                    <div class="mt-3">
                                        <strong><?php echo e(trans('panel.translation')); ?>:</strong>
                                        <div class="mt-1"><?php echo e($flashcard->translation); ?></div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flip-hint">Click to see word</div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-4">
                    <?php echo e($flashcards->links()); ?>

                </div>
            <?php else: ?>
                <div class="panel-section-card py-40 px-25">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-bookmark-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => '80px','height' => '80px','class' => 'text-gray-300']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="empty-state-text"><?php echo e(trans('panel.no_flashcards_yet')); ?></div>
                        <a href="<?php echo e(url('/panel/dictionary')); ?>" class="btn btn-primary">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-book'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mr-1','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            <?php echo e(trans('panel.start_learning')); ?>

                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
<script>
    function flipCard(card) {
        // Don't flip if clicking delete button
        if (event.target.closest('.delete-btn')) {
            return;
        }
        
        card.classList.toggle('flipped');
    }

    function deleteFlashcard(event, id) {
        event.stopPropagation();
        
        Swal.fire({
            title: '<?php echo e(trans("panel.are_you_sure")); ?>',
            text: '<?php echo e(trans("panel.flashcard_delete_confirm")); ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?php echo e(trans("panel.yes_delete")); ?>',
            cancelButtonText: '<?php echo e(trans("panel.cancel")); ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/panel/dictionary/flashcards/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo e(trans("panel.deleted")); ?>',
                            text: '<?php echo e(trans("panel.flashcard_deleted_successfully")); ?>',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: '<?php echo e(trans("panel.error")); ?>',
                        text: '<?php echo e(trans("panel.something_went_wrong")); ?>'
                    });
                });
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dictionary/flashcards.blade.php ENDPATH**/ ?>