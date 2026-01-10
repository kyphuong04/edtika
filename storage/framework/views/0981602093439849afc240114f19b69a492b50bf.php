
<?php
    $taskType = 'Task 2'; 
    if ($group->question_type) {
        if (str_starts_with($group->question_type, 'task1_')) { $taskType = 'Task 1'; }
        elseif ($group->question_type === 'task2_essay') { $taskType = 'Task 2'; }
    } elseif (strpos($group->title ?? '', 'Task 1') !== false || strpos($group->title ?? '', 'Task1') !== false) {
        $taskType = 'Task 1';
    }
    
    $taskInfo = [
        'Task 1' => [
            'duration' => '20 minutes', 'minWords' => 150,
            'description' => 'Summarise and describe visual information (graph, chart, diagram, or process)',
            'tips' => ['Identify and describe the main trends', 'Make comparisons where relevant', 'Include key data and figures', 'Write at least 150 words'],
            'icon' => 'fa-chart-line', 'needsImage' => true
        ],
        'Task 2' => [
            'duration' => '40 minutes', 'minWords' => 250,
            'description' => 'Write an essay responding to a point of view, argument or problem',
            'tips' => ['Address all parts of the question', 'Present a clear position', 'Support ideas with examples', 'Write at least 250 words'],
            'icon' => 'fa-file-alt', 'needsImage' => false
        ]
    ];
    $currentTask = $taskInfo[$taskType];
?>

<div class="row">
    
    <div class="col-lg-6">
        <div class="card writing-task-card shadow-sm mb-20">
            <div class="card-header writing-header">
                <h5 class="font-16 font-weight-bold text-white mb-0 d-flex align-items-center">
                    <i class="fas fa-pen-fancy mr-10"></i>
                    <span><?php echo e($group->title ?? 'Writing ' . $taskType); ?></span>
                </h5>
            </div>
            <div class="card-body p-20">
                
                <div class="writing-task-info mb-16">
                    <div class="task-badge <?php echo e(strtolower(str_replace(' ', '-', $taskType))); ?>">
                        <i class="fas <?php echo e($currentTask['icon']); ?> mr-8"></i>
                        IELTS Writing <?php echo e($taskType); ?>

                    </div>
                    <p class="task-description mt-8 text-gray-500 font-13"><?php echo e($currentTask['description']); ?></p>
                </div>
                
                
                <?php if($group->instructions): ?>
                    <div class="writing-instructions mb-16 shadow-none">
                        <div class="d-flex align-items-center mb-8">
                            <i class="fas fa-info-circle text-primary mr-8"></i>
                            <strong class="font-13 text-dark">Instructions</strong>
                        </div>
                        <div class="instruction-content font-14 text-gray-700"><?php echo nl2br(e($group->instructions)); ?></div>
                    </div>
                <?php endif; ?>
                
                
                <?php if($group->passage): ?>
                    <div class="writing-task-description mb-16 border-0">
                        <div class="task-header d-flex align-items-center">
                            <i class="fas fa-clipboard-list mr-8"></i>
                            <span class="font-13 font-weight-bold">Task Description</span>
                        </div>
                        <div class="task-content font-14 line-height-1.8 text-dark"><?php echo $group->passage; ?></div>
                    </div>
                <?php endif; ?>
                
                
                <?php if($group->task_image): ?>
                    <div class="task-image-container mb-16">
                        <h6 class="font-13 font-weight-bold text-gray-700 mb-8">
                            <i class="fas fa-image mr-8 text-primary"></i>Task Visual (Graph/Chart/Diagram)
                        </h6>
                        <div class="task-image-wrapper bg-light p-12 border rounded">
                            <img src="<?php echo e(\Storage::disk('public')->url($group->task_image)); ?>" class="img-fluid rounded shadow-sm">
                        </div>
                    </div>
                <?php endif; ?>
                
                
                <div class="writing-meta mt-16 pt-16 border-top">
                    <div class="row">
                        <div class="col-6">
                            <div class="meta-item font-13 text-gray-600">
                                <i class="fas fa-pencil-alt text-primary mr-8"></i>
                                <span><?php echo e($currentTask['minWords']); ?> words min</span>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <div class="meta-item font-13 text-gray-600 d-inline-flex align-items-center">
                                <i class="fas fa-clock text-primary mr-8"></i>
                                <span><?php echo e($currentTask['duration']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="mt-16 pt-16 border-top">
                    <a href="<?php echo e(route('panel.question-groups.edit', $group->id)); ?>" class="btn btn-outline-primary btn-block font-weight-bold py-10">
                        <i class="fas fa-edit mr-8"></i>Edit Task
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="col-lg-6">
        <div class="card sample-rubric-card shadow-sm mb-20">
            <div class="card-header bg-white border-bottom py-12">
                <h5 class="font-14 font-weight-bold mb-0 text-dark d-flex align-items-center">
                    <i class="fas fa-star text-success mr-8"></i>Sample Answer & Rubric
                </h5>
            </div>
            <div class="card-body p-20">
                <?php
                    $questions = $group->questions()->get();
                    $sampleAnswer = $questions->first();
                ?>
                
                <?php if($sampleAnswer && $sampleAnswer->explanation): ?>
                    <?php if($sampleAnswer->target_band): ?>
                        <div class="text-center mb-20">
                            <div class="band-circle shadow-sm">
                                <span class="band-value"><?php echo e($sampleAnswer->target_band); ?></span>
                                <span class="band-label">Band</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="sample-answer-section mb-16">
                        <h6 class="font-13 font-weight-bold text-success mb-12 d-flex align-items-center">
                            <i class="fas fa-check-circle mr-8"></i>Sample Answer
                        </h6>
                        <div class="sample-content font-14 line-height-1.8 text-gray-700"><?php echo $sampleAnswer->explanation; ?></div>
                    </div>
                    <div class="mt-16 pt-16 border-top">
                        <a href="<?php echo e(route('panel.questions.edit', $sampleAnswer->id)); ?>" class="btn btn-outline-success btn-sm px-16">
                            <i class="fas fa-edit mr-4"></i>Edit Sample
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-40">
                        <div class="empty-icon mb-16">
                            <i class="fas fa-file-signature fa-4x text-gray-200"></i>
                        </div>
                        <p class="text-gray-500 mb-20">No sample answer added yet</p>
                        <button type="button" class="btn btn-success px-24 py-10 shadow-sm font-weight-bold" 
                                data-toggle="modal" data-target="#addSampleModal" data-backdrop="false">
                            <i class="fas fa-plus mr-8"></i>Add Sample Answer
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        
        <div class="card tips-card shadow-sm border-0">
            <div class="card-header tips-header py-12">
                <h6 class="font-13 font-weight-bold text-info mb-0 d-flex align-items-center">
                    <i class="fas fa-lightbulb mr-8"></i><?php echo e($taskType); ?> Writing Tips
                </h6>
            </div>
            <div class="card-body p-16">
                <ul class="tips-list mb-0">
                    <?php $__currentLoopData = $currentTask['tips']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="font-13 text-gray-600 mb-8"><?php echo e($tip); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addSampleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header header-gradient py-16 px-24 border-0">
                <h5 class="modal-title text-white font-16 font-weight-bold">
                    <i class="fas fa-star mr-8"></i>Add Sample Answer
                </h5>
                <button type="button" class="close-custom" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="<?php echo e(route('panel.questions.store', $group->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="question_type" value="writing">
                <input type="hidden" name="question_number" value="1">
                <input type="hidden" name="question_text" value="<?php echo e($taskType); ?> Sample Answer">
                <div class="modal-body p-24">
                    <div class="writing-instructions mb-20" style="background: #f0f7ff; border: 1px solid #dbeafe; padding: 15px; border-radius: 10px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary mr-10 font-18"></i>
                            <div>
                                <span class="d-block text-primary font-weight-bold font-13">Target Band: Band <?php echo e($group->target_band ?? '5.5'); ?></span>
                                <small class="text-gray-500">You can change this in the group settings if needed.</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark mb-8">Sample Answer *</label>
                        <textarea name="explanation" class="form-control border-gray-200" rows="12" 
                                  placeholder="Enter a model answer for this <?php echo e($taskType); ?>..." required 
                                  style="border-radius: 10px; padding: 15px; font-size: 14px; line-height: 1.6;"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-24 py-16">
                    <button type="button" class="btn btn-link text-gray-500 font-weight-bold" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-32 py-10 font-weight-bold rounded-pill shadow-sm">
                        Save Sample Answer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* --- FIX MODAL CHẶN NGƯỜI DÙNG --- */
.modal-backdrop { display: none !important; }
#addSampleModal { background: rgba(0, 0, 0, 0.5) !important; z-index: 9999 !important; }
.close-custom { background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: 0.3s; cursor: pointer; }
.close-custom:hover { background: rgba(255,255,255,0.4); transform: rotate(90deg); }

/* --- KHÔI PHỤC CHI TIẾT GIAO DIỆN GỐC --- */
.writing-task-card { border-radius: 16px; border: none; }
.writing-header { background: linear-gradient(135deg, #1a3a5c 0%, #2e5a8a 100%); padding: 18px 24px; }
.task-badge { display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
.task-badge.task-1 { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; border: 1px solid #fcd34d; }
.task-badge.task-2 { background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af; border: 1px solid #93c5fd; }

.writing-instructions { background: #f0f7ff; border-radius: 12px; padding: 16px; border: 1px solid #dbeafe; }
.writing-task-description { background: linear-gradient(135deg, #f0f7ff 0%, #dbeafe 100%); border-radius: 12px; overflow: hidden; border: 1px solid #bfdbfe; }
.writing-task-description .task-header { background: rgba(26, 58, 92, 0.1); padding: 12px 20px; color: #1a3a5c; }
.writing-task-description .task-content { padding: 20px; }

.band-circle { width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; margin: 0 auto; }
.band-value { font-size: 26px; font-weight: 800; line-height: 1; }
.band-label { font-size: 11px; text-transform: uppercase; opacity: 0.9; }

.sample-answer-section { background: #f0fdf4; border-radius: 12px; padding: 20px; border: 1px solid #bbf7d0; }
.header-gradient { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }

.tips-header { background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border: none; }
.tips-list { padding-left: 20px; }
.tips-list li { list-style-type: disc; line-height: 1.6; }

body.modal-open { overflow: hidden; }
.line-height-1.8 { line-height: 1.8; }
.text-gray-200 { color: #e5e7eb; }
.text-gray-600 { color: #4b5563; }
</style>

<script>
// Auto reload page after sample answer submission
document.addEventListener('DOMContentLoaded', function() {
    const sampleForm = document.querySelector('#addSampleModal form');
    if (sampleForm) {
        sampleForm.addEventListener('submit', function() {
            // Show loading state on button
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-8"></i>Saving...';
            }
        });
    }
});
</script><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/question_groups/partials/writing_task.blade.php ENDPATH**/ ?>