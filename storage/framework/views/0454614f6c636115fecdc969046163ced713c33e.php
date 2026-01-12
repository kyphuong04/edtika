

<?php $__env->startSection('content'); ?>
<section>
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
        <h1 class="section-title"><?php echo e(trans('panel.word_lists')); ?></h1>
        
        <button type="button" class="btn btn-primary btn-sm mt-3 mt-md-0" data-toggle="modal" data-target="#createWordListModal">
            <i class="iconsax" data-icon="add-circle"></i>
            <?php echo e(trans('panel.create_word_list')); ?>

        </button>
    </div>

    <!-- Search Box -->
    <div class="activities-container mt-25 p-20 p-lg-35">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="searchIcon">
                                <i class="iconsax" data-icon="search-normal-1"></i>
                            </span>
                        </div>
                        <input type="text" id="searchWordList" class="form-control" placeholder="Search English" aria-describedby="searchIcon">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- IELTS Word Lists -->
    <?php if($ieltsWordLists && $ieltsWordLists->count() > 0): ?>
    <div class="mt-25">
        <h2 class="section-title"><?php echo e(trans('panel.ielts_word_lists')); ?></h2>
        
        <div class="row mt-15">
            <?php $__currentLoopData = $ieltsWordLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wordList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-lg-6 mt-20 word-list-card" data-name="<?php echo e($wordList->name); ?>">
                <div class="webinar-card webinar-list d-flex">
                    <div class="image-box">
                        <span class="badge badge-primary"><?php echo e($wordList->level); ?></span>
                    </div>

                    <div class="webinar-card-body w-100 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="/panel/dictionary/word-lists/<?php echo e($wordList->id); ?>">
                                <h3 class="webinar-title"><?php echo e($wordList->name); ?></h3>
                            </a>
                        </div>

                        <?php if($wordList->description): ?>
                        <p class="mt-10 text-gray font-14"><?php echo e($wordList->description); ?></p>
                        <?php endif; ?>

                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <div class="d-flex align-items-center">
                                <i class="iconsax text-gray" data-icon="document-text"></i>
                                <span class="ml-5 text-gray font-14"><?php echo e(number_format($wordList->word_count)); ?> <?php echo e(trans('panel.words')); ?></span>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <i class="iconsax text-warning" data-icon="lock"></i>
                                <span class="ml-5 text-gray font-14">Premium</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- My Word Lists -->
    <div class="mt-35">
        <h2 class="section-title"><?php echo e(trans('panel.my_word_list')); ?></h2>
        
        <div class="row mt-15">
            <?php if($userWordLists && $userWordLists->count() > 0): ?>
                <?php $__currentLoopData = $userWordLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wordList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-lg-6 mt-20 word-list-card" data-name="<?php echo e($wordList->name); ?>">
                    <div class="webinar-card webinar-list d-flex">
                        <div class="image-box">
                            <span class="badge badge-success">Custom</span>
                        </div>

                        <div class="webinar-card-body w-100 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="/panel/dictionary/word-lists/<?php echo e($wordList->id); ?>">
                                    <h3 class="webinar-title"><?php echo e($wordList->name); ?></h3>
                                </a>
                                
                                <div class="btn-group">
                                    <button type="button" class="btn-transparent text-gray dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="iconsax" data-icon="more"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/panel/dictionary/word-lists/<?php echo e($wordList->id); ?>">
                                            <i class="iconsax mr-2" data-icon="eye"></i>
                                            <?php echo e(trans('panel.view_list')); ?>

                                        </a>
                                        <button class="dropdown-item edit-word-list" data-id="<?php echo e($wordList->id); ?>" data-name="<?php echo e($wordList->name); ?>" data-description="<?php echo e($wordList->description); ?>">
                                            <i class="iconsax mr-2" data-icon="edit"></i>
                                            <?php echo e(trans('panel.edit_list')); ?>

                                        </button>
                                        <button class="dropdown-item text-danger delete-word-list" data-id="<?php echo e($wordList->id); ?>">
                                            <i class="iconsax mr-2" data-icon="trash"></i>
                                            <?php echo e(trans('panel.delete_list')); ?>

                                        </button>
                                    </div>
                                </div>
                            </div>

                            <?php if($wordList->description): ?>
                            <p class="mt-10 text-gray font-14"><?php echo e($wordList->description); ?></p>
                            <?php endif; ?>

                            <div class="d-flex align-items-center justify-content-between mt-auto">
                                <div class="d-flex align-items-center">
                                    <i class="iconsax text-gray" data-icon="document-text"></i>
                                    <span class="ml-5 text-gray font-14"><?php echo e(number_format($wordList->word_count)); ?> <?php echo e(trans('panel.words')); ?></span>
                                </div>
                                
                                <div class="d-flex align-items-center">
                                    <i class="iconsax text-success" data-icon="tick-circle"></i>
                                    <span class="ml-5 text-gray font-14">My List</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="no-result default-no-result mt-50">
                        <div class="no-result-logo">
                            <img src="/assets/default/img/no-results/lists.png" alt="">
                        </div>
                        <div class="d-flex align-items-center flex-column mt-30 text-center">
                            <h2 class="section-title"><?php echo e(trans('panel.no_word_lists_yet')); ?></h2>
                            <p class="mt-5 text-center"><?php echo e(trans('panel.create_your_first_list')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Create Word List Modal -->
<div class="modal fade" id="createWordListModal" tabindex="-1" role="dialog" aria-labelledby="createWordListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createWordListModalLabel"><?php echo e(trans('panel.create_word_list')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createWordListForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="wordListName"><?php echo e(trans('panel.list_name')); ?> *</label>
                        <input type="text" class="form-control" id="wordListName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="wordListDescription"><?php echo e(trans('panel.list_description')); ?></label>
                        <textarea class="form-control" id="wordListDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('panel.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(trans('public.create')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Word List Modal -->
<div class="modal fade" id="editWordListModal" tabindex="-1" role="dialog" aria-labelledby="editWordListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWordListModalLabel"><?php echo e(trans('panel.edit_list')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editWordListForm">
                <input type="hidden" id="editWordListId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editWordListName"><?php echo e(trans('panel.list_name')); ?> *</label>
                        <input type="text" class="form-control" id="editWordListName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editWordListDescription"><?php echo e(trans('panel.list_description')); ?></label>
                        <textarea class="form-control" id="editWordListDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('panel.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(trans('public.save')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
<script>
    (function($) {
        "use strict";

        // Search word lists
        $('#searchWordList').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            $('.word-list-card').each(function() {
                const listName = $(this).data('name').toLowerCase();
                
                if (listName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Create word list
        $('#createWordListForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                name: $('#wordListName').val(),
                description: $('#wordListDescription').val()
            };

            $.ajax({
                url: '/panel/dictionary/word-lists/create',
                method: 'POST',
                data: formData,
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
        });

        // Edit word list button
        $('.edit-word-list').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const description = $(this).data('description');
            
            $('#editWordListId').val(id);
            $('#editWordListName').val(name);
            $('#editWordListDescription').val(description);
            
            $('#editWordListModal').modal('show');
        });

        // Update word list
        $('#editWordListForm').on('submit', function(e) {
            e.preventDefault();
            
            const id = $('#editWordListId').val();
            const formData = {
                name: $('#editWordListName').val(),
                description: $('#editWordListDescription').val()
            };

            $.ajax({
                url: `/panel/dictionary/word-lists/${id}`,
                method: 'PUT',
                data: formData,
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
        });

        // Delete word list
        $('.delete-word-list').on('click', function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: '<?php echo e(trans("panel.are_you_sure")); ?>',
                text: '<?php echo e(trans("panel.flashcard_delete_confirm")); ?>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<?php echo e(trans("panel.yes_delete")); ?>',
                cancelButtonText: '<?php echo e(trans("panel.cancel")); ?>'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/panel/dictionary/word-lists/${id}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '<?php echo e(trans("panel.deleted")); ?>',
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

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/dictionary/word-lists.blade.php ENDPATH**/ ?>