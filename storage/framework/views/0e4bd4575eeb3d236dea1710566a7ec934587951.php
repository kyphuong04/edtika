

<?php $__env->startSection('content'); ?>
<section>
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
        <div>
            <a href="/panel/dictionary/word-lists" class="text-gray">
                <i class="iconsax" data-icon="arrow-left-2"></i>
                <?php echo e(trans('panel.back')); ?>

            </a>
            <h1 class="section-title mt-10"><?php echo e($wordList->name); ?></h1>
            <?php if($wordList->description): ?>
            <p class="text-gray mt-5"><?php echo e($wordList->description); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="d-flex align-items-center mt-3 mt-md-0">
            <span class="badge badge-<?php echo e($wordList->user_id ? 'success' : 'primary'); ?> mr-15">
                <?php echo e($wordList->level ?? 'Custom'); ?>

            </span>
            <span class="text-gray font-weight-500">
                <?php echo e(number_format($wordList->word_count)); ?> <?php echo e(trans('panel.words')); ?>

            </span>
        </div>
    </div>

    <!-- Words Grid -->
    <div class="mt-25">
        <?php if($wordList->flashcards && $wordList->flashcards->count() > 0): ?>
        <div class="row">
            <?php $__currentLoopData = $wordList->flashcards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flashcard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-md-6 col-lg-4 mt-20">
                <div class="flashcard-item" data-id="<?php echo e($flashcard->id); ?>">
                    <div class="flashcard-inner">
                        <div class="flashcard-front">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h3 class="font-weight-bold"><?php echo e($flashcard->word); ?></h3>
                                    <?php if($wordList->user_id): ?>
                                    <button class="btn btn-sm btn-transparent text-danger remove-from-list" data-flashcard-id="<?php echo e($flashcard->id); ?>">
                                        <i class="iconsax" data-icon="trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($flashcard->pronunciation): ?>
                                <p class="text-primary mt-10"><?php echo e($flashcard->pronunciation); ?></p>
                                <?php endif; ?>
                                
                                <div class="mt-auto">
                                    <small class="text-gray"><?php echo e(trans('panel.definition')); ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flashcard-back">
                            <div class="d-flex flex-column h-100">
                                <div>
                                    <h4 class="font-weight-bold mb-15"><?php echo e(trans('panel.definition')); ?></h4>
                                    <p><?php echo e($flashcard->definition); ?></p>
                                </div>
                                
                                <?php if($flashcard->example): ?>
                                <div class="mt-15">
                                    <h5 class="font-weight-bold mb-10"><?php echo e(trans('panel.example')); ?></h5>
                                    <p class="font-italic text-gray"><?php echo e($flashcard->example); ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($flashcard->translation): ?>
                                <div class="mt-auto pt-15 border-top">
                                    <small class="text-primary"><?php echo e($flashcard->translation); ?></small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mt-30">
            <?php echo e($wordList->flashcards->links()); ?>

        </div>
        <?php else: ?>
        <div class="no-result default-no-result mt-50">
            <div class="no-result-logo">
                <img src="/assets/default/img/no-results/lists.png" alt="">
            </div>
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h2 class="section-title"><?php echo e(trans('panel.no_flashcards_yet')); ?></h2>
                <p class="mt-5 text-center">Start adding words to this list from the dictionary</p>
                <a href="/panel/dictionary" class="btn btn-primary mt-15">
                    <i class="iconsax" data-icon="search-normal-1"></i>
                    <?php echo e(trans('panel.back_to_dictionary')); ?>

                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles_top'); ?>
<style>
    .flashcard-item {
        perspective: 1000px;
        cursor: pointer;
        height: 250px;
    }

    .flashcard-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: left;
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }

    .flashcard-item.flipped .flashcard-inner {
        transform: rotateY(180deg);
    }

    .flashcard-front,
    .flashcard-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        background: #fff;
        display: flex;
        flex-direction: column;
    }

    .flashcard-front {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }

    .flashcard-front h3 {
        color: #fff;
        font-size: 24px;
    }

    .flashcard-front .text-primary {
        color: #ffd700 !important;
    }

    .flashcard-back {
        transform: rotateY(180deg);
        background: #fff;
    }

    .remove-from-list {
        opacity: 0;
        transition: opacity 0.3s;
    }

    .flashcard-item:hover .remove-from-list {
        opacity: 1;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
<script>
    (function($) {
        "use strict";

        // Flip flashcard on click
        $('.flashcard-item').on('click', function(e) {
            // Don't flip if clicking remove button
            if ($(e.target).closest('.remove-from-list').length > 0) {
                return;
            }
            $(this).toggleClass('flipped');
        });

        // Remove word from list
        $('.remove-from-list').on('click', function(e) {
            e.stopPropagation();
            
            const flashcardId = $(this).data('flashcard-id');
            const wordListId = <?php echo e($wordList->id); ?>;
            
            Swal.fire({
                title: '<?php echo e(trans("panel.are_you_sure")); ?>',
                text: 'Remove this word from the list?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<?php echo e(trans("public.yes")); ?>',
                cancelButtonText: '<?php echo e(trans("panel.cancel")); ?>'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/panel/dictionary/word-lists/remove-word',
                        method: 'POST',
                        data: {
                            word_list_id: wordListId,
                            flashcard_id: flashcardId
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '<?php echo e(trans("panel.success")); ?>',
                                    text: response.message,
                                    timer: 2000
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: '<?php echo e(trans("panel.error")); ?>',
                                text: '<?php echo e(trans("panel.something_went_wrong")); ?>'
                            });
                        }
                    });
                }
            });
        });

    })(jQuery);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dictionary/word-list-detail.blade.php ENDPATH**/ ?>