

<?php $__env->startPush('styles_top'); ?>
<style>
    .review-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    /* Header Card */
    .review-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 24px;
    }
    .review-header h1 { 
        margin: 0 0 8px 0; 
        font-size: 24px; 
        font-weight: 600;
    }
    .review-header .subtitle {
        opacity: 0.9;
        font-size: 14px;
    }
    
    /* Stats Summary */
    .stats-summary {
        display: flex;
        gap: 16px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .stat-item {
        background: rgba(255,255,255,0.15);
        padding: 12px 20px;
        border-radius: 10px;
        text-align: center;
        min-width: 100px;
    }
    .stat-value {
        font-size: 24px;
        font-weight: bold;
    }
    .stat-label {
        font-size: 12px;
        opacity: 0.85;
        margin-top: 2px;
    }
    
    /* Action buttons */
    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-grade { 
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-grade-writing { background: #f59e0b; color: white; }
    .btn-grade-speaking { background: #ef4444; color: white; }
    .btn-back { background: rgba(255,255,255,0.2); color: white; }
    .btn-grade:hover { opacity: 0.9; }
    
    /* Section Cards */
    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .section-header {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }
    .section-skill {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
    }
    .skill-listening { background: #dbeafe; color: #1e40af; }
    .skill-reading { background: #d1fae5; color: #065f46; }
    .skill-writing { background: #fef3c7; color: #92400e; }
    .skill-speaking { background: #fee2e2; color: #991b1b; }
    
    .section-stats {
        font-size: 13px;
        color: #6b7280;
    }
    
    /* Question List */
    .questions-list {
        padding: 20px 24px;
    }
    .question-item {
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }
    .question-item:hover {
        border-color: #d1d5db;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .question-item:last-child { margin-bottom: 0; }
    
    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .question-number {
        font-weight: 600;
        color: #374151;
        font-size: 15px;
    }
    .question-text {
        color: #4b5563;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 14px;
    }
    
    /* Badge styles */
    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-correct { background: #d1fae5; color: #065f46; }
    .badge-incorrect { background: #fee2e2; color: #991b1b; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-no-answer { background: #f3f4f6; color: #6b7280; }
    
    /* Answer boxes */
    .answer-box {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .answer-student {
        background: #f9fafb;
        border-left: 3px solid #9ca3af;
    }
    .answer-student.correct {
        background: #ecfdf5;
        border-left-color: #10b981;
    }
    .answer-student.incorrect {
        background: #fef2f2;
        border-left-color: #ef4444;
    }
    .answer-correct-ref {
        background: #ecfdf5;
        border-left: 3px solid #10b981;
    }
    .answer-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 4px;
    }
    .answer-text {
        font-size: 14px;
        color: #1f2937;
    }
    .no-answer {
        color: #9ca3af;
        font-style: italic;
    }
    
    /* Explanation */
    .explanation-box {
        background: #eff6ff;
        border-left: 3px solid #3b82f6;
        padding: 12px 16px;
        border-radius: 8px;
        margin-top: 10px;
    }
    .explanation-box strong {
        color: #1e40af;
        font-size: 12px;
        text-transform: uppercase;
    }
    .explanation-box p {
        margin: 6px 0 0 0;
        font-size: 13px;
        color: #4b5563;
        line-height: 1.6;
    }
    
    /* Next Actions */
    .next-actions {
        background: white;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .next-actions h4 {
        margin: 0 0 16px 0;
        color: #1f2937;
    }
    .next-actions .actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .next-actions .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
    }
    
    /* Data Debug (for teachers) */
    .debug-panel {
        background: #1f2937;
        color: #e5e7eb;
        padding: 16px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 12px;
        margin-top: 24px;
        overflow-x: auto;
    }
    .debug-panel h5 {
        color: #fbbf24;
        margin: 0 0 12px 0;
        font-size: 14px;
    }
    .debug-row {
        display: flex;
        gap: 20px;
        margin-bottom: 6px;
    }
    .debug-label { color: #9ca3af; min-width: 140px; }
    .debug-value { color: #10b981; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    // Calculate stats
    $totalQuestions = 0;
    $answeredQuestions = 0;
    $correctAnswers = 0;
    $incorrectAnswers = 0;
    $pendingGrading = 0;
    
    foreach($test->sections as $section) {
        foreach($section->questions as $question) {
            $totalQuestions++;
            $answer = $attempt->answers->where('question_id', $question->id)->first();
            
            if($answer && $answer->answer_text) {
                $answeredQuestions++;
                if($answer->grading_status === 'pending' || in_array($section->skill, ['writing', 'speaking'])) {
                    $pendingGrading++;
                } elseif($answer->is_correct) {
                    $correctAnswers++;
                } else {
                    $incorrectAnswers++;
                }
            }
        }
    }
    
    $isTeacher = auth()->user()->isTeacher() || auth()->user()->isAdmin() || auth()->user()->isOrganization();
    $isOwner = $attempt->user_id === auth()->id();
?>

<div class="review-container mt-20">
    
    <div class="review-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
            <div>
                <h1>📋 Review Answers</h1>
                <p class="subtitle"><?php echo e($test->title); ?> • Attempt #<?php echo e($attempt->attempt_number); ?></p>
                <?php if(!$isOwner && $isTeacher): ?>
                    <p class="subtitle mt-1">
                        <strong>Student:</strong> <?php echo e($attempt->user->full_name ?? 'Unknown'); ?>

                        (<?php echo e($attempt->user->email ?? ''); ?>)
                    </p>
                <?php endif; ?>
            </div>
            <div class="header-actions">
                <?php if($isTeacher): ?>
                    <?php if($test->has_writing && !$attempt->writing_band): ?>
                        <a href="<?php echo e(route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'writing'])); ?>" class="btn-grade btn-grade-writing">
                            ✍️ Grade Writing
                        </a>
                    <?php endif; ?>
                    <?php if($test->has_speaking && !$attempt->speaking_band): ?>
                        <a href="<?php echo e(route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'speaking'])); ?>" class="btn-grade btn-grade-speaking">
                            🎤 Grade Speaking
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="<?php echo e(route('panel.ielts_tests.results', $attempt->id)); ?>" class="btn-grade btn-back">
                    ← Back to Results
                </a>
            </div>
        </div>
        
        <div class="stats-summary">
            <div class="stat-item">
                <div class="stat-value"><?php echo e($totalQuestions); ?></div>
                <div class="stat-label">Total Questions</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?php echo e($answeredQuestions); ?></div>
                <div class="stat-label">Answered</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?php echo e($correctAnswers); ?></div>
                <div class="stat-label">✓ Correct</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?php echo e($incorrectAnswers); ?></div>
                <div class="stat-label">✗ Incorrect</div>
            </div>
            <?php if($pendingGrading > 0): ?>
                <div class="stat-item">
                    <div class="stat-value"><?php echo e($pendingGrading); ?></div>
                    <div class="stat-label">⏳ Pending</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php $__currentLoopData = $test->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $sectionAnswered = 0;
            $sectionCorrect = 0;
            foreach($section->questions as $q) {
                $ans = $attempt->answers->where('question_id', $q->id)->first();
                if($ans && $ans->answer_text) {
                    $sectionAnswered++;
                    if($ans->is_correct) $sectionCorrect++;
                }
            }
            $sectionTotal = $section->questions->count();
        ?>
        
        <div class="section-card">
            <div class="section-header">
                <div>
                    <h3 class="section-title"><?php echo e($section->title ?: 'Section ' . $loop->iteration); ?></h3>
                    <span class="section-skill skill-<?php echo e($section->skill); ?>"><?php echo e(ucfirst($section->skill)); ?></span>
                </div>
                <div class="section-stats">
                    <?php echo e($sectionAnswered); ?>/<?php echo e($sectionTotal); ?> answered
                    <?php if($section->skill === 'reading' || $section->skill === 'listening'): ?>
                        • <?php echo e($sectionCorrect); ?> correct
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="questions-list">
                <?php $__empty_1 = true; $__currentLoopData = $section->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $answer = $attempt->answers->where('question_id', $question->id)->first();
                        $hasAnswer = $answer && !empty($answer->answer_text);
                        $isCorrect = $answer && $answer->is_correct;
                        $isPending = in_array($section->skill, ['writing', 'speaking']) || ($answer && $answer->grading_status === 'pending');
                        
                        // Determine status
                        if(!$hasAnswer) {
                            $status = 'no-answer';
                            $statusLabel = 'No Answer';
                            $badgeClass = 'badge-no-answer';
                        } elseif($isPending) {
                            $status = 'pending';
                            $statusLabel = 'Pending';
                            $badgeClass = 'badge-pending';
                        } elseif($isCorrect) {
                            $status = 'correct';
                            $statusLabel = 'Correct';
                            $badgeClass = 'badge-correct';
                        } else {
                            $status = 'incorrect';
                            $statusLabel = 'Incorrect';
                            $badgeClass = 'badge-incorrect';
                        }
                    ?>
                    
                    <div class="question-item">
                        <div class="question-header">
                            <span class="question-number">Question <?php echo e($question->question_number); ?></span>
                            <span class="status-badge <?php echo e($badgeClass); ?>"><?php echo e($statusLabel); ?></span>
                        </div>
                        
                        <?php if($question->question_text): ?>
                            <div class="question-text">
                                <?php echo nl2br(e($question->question_text)); ?>

                            </div>
                        <?php endif; ?>
                        
                        
                        <?php if(in_array($question->question_type, ['multiple_choice', 'mcq', 'single_choice']) && $question->answer_options): ?>
                            <?php
                                $options = is_string($question->answer_options) ? json_decode($question->answer_options, true) : $question->answer_options;
                            ?>
                            <?php if(is_array($options)): ?>
                                <div class="mb-3">
                                    <div class="answer-label">Options</div>
                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $optionText = is_array($option) ? ($option['text'] ?? $option['label'] ?? '') : $option;
                                            $optionKey = is_array($option) ? ($option['key'] ?? chr(65 + $loop->index)) : chr(65 + $loop->index);
                                            $isSelected = $hasAnswer && (strtoupper(trim($answer->answer_text)) === strtoupper($optionKey) || $answer->answer_text === $optionText);
                                        ?>
                                        <div style="padding: 6px 0; <?php echo e($isSelected ? 'font-weight: 600; color: #1e40af;' : ''); ?>">
                                            <?php echo e($optionKey); ?>. <?php echo e($optionText); ?>

                                            <?php if($isSelected): ?> <span style="color: #3b82f6;">(Selected)</span> <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        
                        <div class="answer-box answer-student <?php echo e($status); ?>">
                            <div class="answer-label"><?php echo e($isOwner ? 'Your Answer' : 'Student Answer'); ?></div>
                            <?php if($hasAnswer): ?>
                                <div class="answer-text"><?php echo e($answer->answer_text); ?></div>
                            <?php else: ?>
                                <div class="answer-text no-answer">No answer provided</div>
                            <?php endif; ?>
                        </div>
                        
                        
                        <?php if($hasAnswer && !$isPending && $question->auto_gradable && $question->correct_answer && !$isCorrect): ?>
                            <div class="answer-box answer-correct-ref">
                                <div class="answer-label">Correct Answer</div>
                                <div class="answer-text"><?php echo e($question->correct_answer); ?></div>
                            </div>
                        <?php endif; ?>
                        
                        
                        <?php if($answer && $answer->feedback): ?>
                            <div class="explanation-box" style="background: #fef3c7; border-left-color: #f59e0b;">
                                <strong style="color: #92400e;">Teacher Feedback</strong>
                                <p><?php echo e($answer->feedback); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        
                        <?php if($question->explanation && method_exists($test, 'isPracticeTest') && $test->isPracticeTest()): ?>
                            <div class="explanation-box">
                                <strong>💡 Explanation</strong>
                                <p><?php echo e($question->explanation); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-gray py-4">
                        No questions in this section.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div class="next-actions">
        <h4>What's Next?</h4>
        <div class="actions">
            <a href="<?php echo e(route('panel.ielts_tests.results', $attempt->id)); ?>" class="btn btn-primary">
                📊 View Full Results
            </a>
            <a href="<?php echo e(route('panel.ielts_tests.index')); ?>" class="btn btn-outline-primary">
                📚 Browse More Tests
            </a>
            <?php if($isTeacher): ?>
                <a href="<?php echo e(route('panel.ielts_grading.index')); ?>" class="btn btn-outline-secondary">
                    ✍️ Grading Dashboard
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    
    <?php if($isTeacher): ?>
        <div class="debug-panel">
            <h5>🔧 Data Debug (Teacher View)</h5>
            <div class="debug-row">
                <span class="debug-label">Attempt ID:</span>
                <span class="debug-value"><?php echo e($attempt->id); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Student ID:</span>
                <span class="debug-value"><?php echo e($attempt->user_id); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Test ID:</span>
                <span class="debug-value"><?php echo e($test->id); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Status:</span>
                <span class="debug-value"><?php echo e($attempt->status); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Started At:</span>
                <span class="debug-value"><?php echo e($attempt->started_at ? date('Y-m-d H:i:s', $attempt->started_at) : 'N/A'); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Completed At:</span>
                <span class="debug-value"><?php echo e($attempt->completed_at ? date('Y-m-d H:i:s', $attempt->completed_at) : 'N/A'); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Total Answers in DB:</span>
                <span class="debug-value"><?php echo e($attempt->answers->count()); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Listening Score:</span>
                <span class="debug-value"><?php echo e($attempt->listening_score ?? 'null'); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Reading Score:</span>
                <span class="debug-value"><?php echo e($attempt->reading_score ?? 'null'); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Writing Band:</span>
                <span class="debug-value"><?php echo e($attempt->writing_band ?? 'null'); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Speaking Band:</span>
                <span class="debug-value"><?php echo e($attempt->speaking_band ?? 'null'); ?></span>
            </div>
            <div class="debug-row">
                <span class="debug-label">Overall Band:</span>
                <span class="debug-value"><?php echo e($attempt->overall_band ?? 'null'); ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests/review.blade.php ENDPATH**/ ?>