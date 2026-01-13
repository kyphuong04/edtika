

<?php $__env->startPush('styles_top'); ?>
<style>
    .results-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .band-score-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        margin-bottom: 30px;
    }
    .band-score-display {
        font-size: 72px;
        font-weight: bold;
        line-height: 1;
        margin: 20px 0;
    }
    .skill-score-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        transition: transform 0.2s;
    }
    .skill-score-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    .score-bar {
        height: 12px;
        background: #e5e7eb;
        border-radius: 6px;
        overflow: hidden;
        margin-top: 10px;
    }
    .score-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #3b82f6);
        transition: width 0.5s ease;
        border-radius: 6px;
    }
    .band-descriptor {
        background: #f9fafb;
        border-left: 4px solid #3b82f6;
        padding: 15px 20px;
        border-radius: 8px;
        margin-top: 15px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    .stat-box {
        background: #f9fafb;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="results-container mt-30">
    
    <div class="band-score-card">
        <h2 class="mb-0">Overall Band Score</h2>
        <?php if($attempt->overall_band): ?>
            <div class="band-score-display"><?php echo e($attempt->overall_band); ?></div>
            <p class="mb-0 font-18">
                <?php if($attempt->overall_band >= 8.0): ?>
                    Very Good User
                <?php elseif($attempt->overall_band >= 7.0): ?>
                    Good User
                <?php elseif($attempt->overall_band >= 6.0): ?>
                    Competent User
                <?php elseif($attempt->overall_band >= 5.0): ?>
                    Modest User
                <?php else: ?>
                    Limited User
                <?php endif; ?>
            </p>
        <?php else: ?>
            <div class="band-score-display">
                <i class="fas fa-clock"></i>
            </div>
            <p class="mb-0 font-18">Pending Manual Grading</p>
            <p class="mb-0 font-14 mt-2 opacity-75">
                Writing and Speaking sections are being graded by instructors
            </p>
        <?php endif; ?>
    </div>

    
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">Test</h6>
                    <p class="mb-0 font-weight-bold"><?php echo e($test->title); ?></p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">Attempt</h6>
                    <p class="mb-0 font-weight-bold">#<?php echo e($attempt->attempt_number); ?></p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">Completed</h6>
                    <p class="mb-0 font-weight-bold"><?php echo e(dateTimeFormat($attempt->completed_at, 'j M Y')); ?></p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-gray mb-1">Duration</h6>
                    <p class="mb-0 font-weight-bold"><?php echo e(gmdate('H:i:s', $attempt->time_spent)); ?></p>
                </div>
            </div>
        </div>
    </div>

    
    <?php
        // Band conversion function
        if (!function_exists('rawToBand')) {
            function rawToBand($raw, $skill = 'listening') {
                $conversionTable = [
                    39 => 9.0, 40 => 9.0,
                    37 => 8.5, 38 => 8.5,
                    35 => 8.0, 36 => 8.0,
                    33 => 7.5, 34 => 7.5,
                    30 => 7.0, 31 => 7.0, 32 => 7.0,
                    27 => 6.5, 28 => 6.5, 29 => 6.5,
                    23 => 6.0, 24 => 6.0, 25 => 6.0, 26 => 6.0,
                    18 => 5.5, 19 => 5.5, 20 => 5.5, 21 => 5.5, 22 => 5.5,
                    16 => 5.0, 17 => 5.0,
                    13 => 4.5, 14 => 4.5, 15 => 4.5,
                    11 => 4.0, 12 => 4.0,
                    8 => 3.5, 9 => 3.5, 10 => 3.5,
                    6 => 3.0, 7 => 3.0,
                    4 => 2.5, 5 => 2.5,
                    3 => 2.0,
                    2 => 1.0,
                    1 => 1.0,
                    0 => 0.0
                ];
                return $conversionTable[$raw] ?? 0;
            }
        }
    ?>
    <div class="row">
        <?php if($test->has_listening): ?>
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-info mb-2">Listening</span>
                        <h4 class="mb-0">
                            <?php if($attempt->listening_score !== null): ?>
                                Band <?php echo e(rawToBand($attempt->listening_score)); ?>

                            <?php else: ?>
                                Pending
                            <?php endif; ?>
                        </h4>
                    </div>
                    <i class="fas fa-headphones fa-2x text-primary opacity-50"></i>
                </div>
                
                <?php if($attempt->listening_score !== null): ?>
                    <div class="d-flex justify-content-between text-gray font-14 mb-2">
                        <span>Score</span>
                        <span class="font-weight-bold"><?php echo e($attempt->listening_score); ?> / 40</span>
                    </div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: <?php echo e(($attempt->listening_score / 40) * 100); ?>%"></div>
                    </div>
                    
                    <div class="band-descriptor">
                        <strong>Correct Answers:</strong> <?php echo e($attempt->listening_score); ?>

                    </div>
                <?php else: ?>
                    <p class="text-gray font-14 mb-0">Auto-grading in progress...</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($test->has_reading): ?>
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-success mb-2">Reading</span>
                        <h4 class="mb-0">
                            <?php if($attempt->reading_score !== null): ?>
                                Band <?php echo e(rawToBand($attempt->reading_score)); ?>

                            <?php else: ?>
                                Pending
                            <?php endif; ?>
                        </h4>
                    </div>
                    <i class="fas fa-book-open fa-2x text-success opacity-50"></i>
                </div>
                
                <?php if($attempt->reading_score !== null): ?>
                    <div class="d-flex justify-content-between text-gray font-14 mb-2">
                        <span>Score</span>
                        <span class="font-weight-bold"><?php echo e($attempt->reading_score); ?> / 40</span>
                    </div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: <?php echo e(($attempt->reading_score / 40) * 100); ?>%"></div>
                    </div>
                    
                    <div class="band-descriptor">
                        <strong>Correct Answers:</strong> <?php echo e($attempt->reading_score); ?>

                    </div>
                <?php else: ?>
                    <p class="text-gray font-14 mb-0">Auto-grading in progress...</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($test->has_writing): ?>
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-warning mb-2">Writing</span>
                        <h4 class="mb-0">
                            <?php if($attempt->writing_band): ?>
                                Band <?php echo e($attempt->writing_band); ?>

                            <?php else: ?>
                                <span class="text-warning">Pending Grading</span>
                            <?php endif; ?>
                        </h4>
                    </div>
                    <i class="fas fa-pencil-alt fa-2x text-warning opacity-50"></i>
                </div>
                
                <?php if($attempt->writing_band): ?>
                    <?php
                        $writingCriteria = $attempt->writing_criteria ?? [];
                    ?>
                    <div class="band-descriptor">
                        <strong>Task Achievement:</strong> Band <?php echo e($writingCriteria['task_response'] ?? $attempt->writing_band); ?><br>
                        <strong>Coherence & Cohesion:</strong> Band <?php echo e($writingCriteria['coherence_cohesion'] ?? $attempt->writing_band); ?><br>
                        <strong>Lexical Resource:</strong> Band <?php echo e($writingCriteria['lexical_resource'] ?? $attempt->writing_band); ?><br>
                        <strong>Grammar & Accuracy:</strong> Band <?php echo e($writingCriteria['grammatical_accuracy'] ?? $attempt->writing_band); ?>

                    </div>
                    <?php if($attempt->writing_feedback): ?>
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong class="d-block mb-1"><i class="fas fa-comment-dots text-primary mr-1"></i> Teacher Feedback:</strong>
                            <p class="mb-0 text-gray"><?php echo e($attempt->writing_feedback); ?></p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-gray font-14 mb-0">
                        <i class="fas fa-clock mr-1"></i>
                        Your writing is being carefully reviewed by an instructor
                    </p>
                    
                    <?php if(auth()->user()->isTeacher() || auth()->user()->isAdmin() || auth()->user()->isOrganization()): ?>
                        <a href="<?php echo e(route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'writing'])); ?>" class="btn btn-warning btn-sm mt-3">
                            <i class="fas fa-pen mr-1"></i> Grade Writing Now
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($test->has_speaking): ?>
        <div class="col-md-6">
            <div class="skill-score-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-danger mb-2">Speaking</span>
                        <h4 class="mb-0">
                            <?php if($attempt->speaking_band): ?>
                                Band <?php echo e($attempt->speaking_band); ?>

                            <?php else: ?>
                                <span class="text-warning">Pending Grading</span>
                            <?php endif; ?>
                        </h4>
                    </div>
                    <i class="fas fa-microphone fa-2x text-danger opacity-50"></i>
                </div>
                
                <?php if($attempt->speaking_band): ?>
                    <?php
                        $speakingCriteria = $attempt->speaking_criteria ?? [];
                    ?>
                    <div class="band-descriptor">
                        <strong>Fluency & Coherence:</strong> Band <?php echo e($speakingCriteria['fluency_coherence'] ?? $attempt->speaking_band); ?><br>
                        <strong>Lexical Resource:</strong> Band <?php echo e($speakingCriteria['lexical_resource'] ?? $attempt->speaking_band); ?><br>
                        <strong>Grammatical Range:</strong> Band <?php echo e($speakingCriteria['grammatical_range'] ?? $attempt->speaking_band); ?><br>
                        <strong>Pronunciation:</strong> Band <?php echo e($speakingCriteria['pronunciation'] ?? $attempt->speaking_band); ?>

                    </div>
                    <?php if($attempt->speaking_feedback): ?>
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong class="d-block mb-1"><i class="fas fa-comment-dots text-primary mr-1"></i> Teacher Feedback:</strong>
                            <p class="mb-0 text-gray"><?php echo e($attempt->speaking_feedback); ?></p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-gray font-14 mb-0">
                        <i class="fas fa-clock mr-1"></i>
                        Your speaking is being carefully reviewed by an instructor
                    </p>
                    
                    <?php if(auth()->user()->isTeacher() || auth()->user()->isAdmin() || auth()->user()->isOrganization()): ?>
                        <a href="<?php echo e(route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'speaking'])); ?>" class="btn btn-danger btn-sm mt-3">
                            <i class="fas fa-microphone mr-1"></i> Grade Speaking Now
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Test Statistics</h5>
        </div>
        <div class="card-body">
            <div class="stats-grid">
                <div class="stat-box">
                    <i class="fas fa-tasks fa-2x text-primary mb-2"></i>
                    <h6 class="text-gray mb-1">Total Questions</h6>
                    <h4 class="mb-0"><?php echo e($attempt->total_questions); ?></h4>
                </div>
                <div class="stat-box">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h6 class="text-gray mb-1">Answered</h6>
                    <h4 class="mb-0"><?php echo e($attempt->answered_questions); ?></h4>
                </div>
                <div class="stat-box">
                    <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                    <h6 class="text-gray mb-1">Progress</h6>
                    <h4 class="mb-0"><?php echo e(round($attempt->progress_percentage, 1)); ?>%</h4>
                </div>
                <div class="stat-box">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h6 class="text-gray mb-1">Time Spent</h6>
                    <h4 class="mb-0"><?php echo e(gmdate('H:i', $attempt->time_spent)); ?></h4>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Next Steps</h5>
                    <p class="text-gray mb-0">Review your answers or take another test</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('panel.ielts_tests.review', $attempt->id)); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-eye mr-2"></i>
                        Review Answers
                    </a>
                    <a href="<?php echo e(route('panel.ielts_tests.index')); ?>" class="btn btn-primary">
                        <i class="fas fa-th-list mr-2"></i>
                        Back to Tests
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">IELTS Band Score Guide</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 9.0 - Expert User</h6>
                    <p class="text-gray font-14">Full operational command of the language</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 8.0 - Very Good User</h6>
                    <p class="text-gray font-14">Fully operational with occasional inaccuracies</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 7.0 - Good User</h6>
                    <p class="text-gray font-14">Operational command with occasional inaccuracies</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 6.0 - Competent User</h6>
                    <p class="text-gray font-14">Effective command despite inaccuracies</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 5.0 - Modest User</h6>
                    <p class="text-gray font-14">Partial command, copes with overall meaning</p>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Band 4.0 - Limited User</h6>
                    <p class="text-gray font-14">Basic competence in familiar situations</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests/results.blade.php ENDPATH**/ ?>