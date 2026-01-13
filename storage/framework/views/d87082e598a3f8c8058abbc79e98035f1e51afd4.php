

<?php $__env->startPush('styles_top'); ?>
<style>
    .test-item {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .test-item:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        border-color: #d1d5db;
    }
    .section-stats {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    .section-stats span {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: #6b7280;
    }
    .btn-group-section {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <div>
            <h1 class="section-title">Manage Sections: <?php echo e($test->title); ?></h1>
            <p class="text-gray">Add and organize test sections here. Each section represents a part of your IELTS test.</p>
        </div>
        <div>
            <a href="<?php echo e(route('panel.my_ielts_tests.edit', $test->id)); ?>" class="btn btn-secondary mr-10">
                <i class="fas fa-arrow-left mr-5"></i>Back to Edit
            </a>
            <a href="<?php echo e(route('panel.my_ielts_tests.sections.create', $test->id)); ?>" class="btn btn-primary">
                <i class="fas fa-plus mr-5"></i>Add Section
            </a>
        </div>
    </div>

    
    <?php if($test->type === 'mock_test'): ?>
        <div class="alert alert-info">
            <strong>Mock Test Requirements:</strong> Must have 4 sections (Listening, Reading,  Writing, Speaking)
            <br>
           <small>
                Current: <?php echo e($test->sections->count()); ?>/4 sections |
                <?php if($test->sections->where('skill', 'listening')->count()): ?> ✓ Listening <?php endif; ?>
                <?php if($test->sections->where('skill', 'reading')->count()): ?> ✓ Reading <?php endif; ?>
                <?php if($test->sections->where('skill', 'writing')->count()): ?> ✓ Writing <?php endif; ?>
                <?php if($test->sections->where('skill', 'speaking')->count()): ?> ✓ Speaking <?php endif; ?>
            </small>
        </div>
    <?php endif; ?>

    
<?php if($test->sections->isEmpty()): ?>
        <div class="no-result mt-50">
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h2>No sections yet!</h2>
                <p class="mt-5">Click "Add Section" to create sections in the Admin Panel.</p>
                <div class="alert alert-info mt-20" style="max-width: 600px;">
                    <i class="fas fa-info-circle mr-5"></i>
                    <strong>Note:</strong> Section and question management (upload audio, images, add questions) is available in the Admin Panel for full control and file upload capabilities.
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php $__currentLoopData = $test->sections->sortBy('sort_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 mb-20">
                    <div class="test-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-fill">
                                <div class="d-flex align-items-center mb-10">
                                    <span class="badge badge-<?php echo e($section->skill === 'listening' ? 'primary' : ($section->skill === 'reading' ? 'success' : ($section->skill === 'writing' ? 'warning' : 'danger'))); ?>">
                                        <?php echo e(ucfirst($section->skill)); ?>

                                    </span>
                                    <h4 class="font-16 font-weight-bold mb-0 ml-10"><?php echo e($section->title); ?></h4>
                                </div>

                                <?php if($section->description): ?>
                                    <p class="text-gray font-14 mb-10"><?php echo e($section->description); ?></p>
                                <?php endif; ?>

                                <div class="d-flex align-items-center text-gray font-12">
                                    <div class="mr-20">
                                        <i class="far fa-clock mr-5"></i><?php echo e($section->duration_minutes); ?> min
                                    </div>
                                    <div class="mr-20">
                                        <i class="far fa-list-alt mr-5"></i><?php echo e($section->questions->count()); ?> questions
                                    </div>
                                    <?php if($section->audio_file): ?>
                                        <div class="mr-20">
                                            <i class="fas fa-headphones mr-5"></i>Has audio
                                        </div>
                                    <?php endif; ?>
                                    <?php if($section->passage_text): ?>
                                        <div class="mr-20">
                                            <i class="fas fa-align-left mr-5"></i>Has passage
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div>
                                <a href="<?php echo e(route('panel.my_ielts_tests.questions', [$test->id, $section->id])); ?>" 
                                   class="btn btn-sm btn-info mr-5">
                                    <i class="fas fa-question-circle"></i> Questions
                                </a>
                                <a href="<?php echo e(route('panel.my_ielts_tests.sections.edit', [$test->id, $section->id])); ?>" 
                                   class="btn btn-sm btn-primary mr-5">
                                    <i class="far fa-edit"></i> Edit
                                </a>
                                <a href="<?php echo e(route('panel.my_ielts_tests.sections.delete', [$test->id, $section->id])); ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure? This will delete all questions in this section.')">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <?php if($test->sections->count() >= ($test->type === 'mock_test' ? 4 : 1)): ?>
        <div class="mt-30 text-center">
            <p class="text-gray mb-15">Ready to publish this test?</p>
            <a href="#" class="btn btn-success btn-lg">
                <i class="fas fa-check mr-5"></i>Publish Test
            </a>
        </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests_manage/sections.blade.php ENDPATH**/ ?>