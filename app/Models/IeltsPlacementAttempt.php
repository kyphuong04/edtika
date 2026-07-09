<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Result record for one Adaptive Placement Test attempt.
 *
 * Tracks the 3 branching sub-tests a student took (each level chosen
 * adaptively from the previous sub-test's score), the final CEFR level
 * reached, and the follow-up Speaking recording kept for teacher review.
 * The Speaking result never affects final_level.
 */
class IeltsPlacementAttempt extends Model
{
    public $timestamps = false;

    protected $table = 'ielts_placement_attempts';

    protected $guarded = ['id'];

    protected $casts = [
        'started_at' => 'integer',
        'completed_at' => 'integer',
        'speaking_reviewed_at' => 'integer',
        'created_at' => 'integer',
        'updated_at' => 'integer',
        'total_time_seconds' => 'integer',
        'current_step' => 'integer',
        'step_1_score' => 'integer',
        'step_2_score' => 'integer',
        'step_3_score' => 'integer',
    ];

    public const LEVELS = ['A1', 'A2', 'B1', 'B2', 'B2+'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function speakingReviewer()
    {
        return $this->belongsTo(\App\User::class, 'speaking_reviewed_by');
    }

    public function step1Test()
    {
        return $this->belongsTo(IeltsTest::class, 'step_1_test_id');
    }

    public function step1Attempt()
    {
        return $this->belongsTo(IeltsTestAttempt::class, 'step_1_attempt_id');
    }

    public function step2Test()
    {
        return $this->belongsTo(IeltsTest::class, 'step_2_test_id');
    }

    public function step2Attempt()
    {
        return $this->belongsTo(IeltsTestAttempt::class, 'step_2_attempt_id');
    }

    public function step3Test()
    {
        return $this->belongsTo(IeltsTest::class, 'step_3_test_id');
    }

    public function step3Attempt()
    {
        return $this->belongsTo(IeltsTestAttempt::class, 'step_3_attempt_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Status helpers
    |--------------------------------------------------------------------------
    */

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function hasSpeakingRecording()
    {
        return !empty($this->speaking_audio_path);
    }

    public function isSpeakingReviewed()
    {
        return !empty($this->speaking_reviewed_by) && !empty($this->speaking_reviewed_at);
    }

    /*
    |--------------------------------------------------------------------------
    | Step read/write helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Get the recorded data (test_id, attempt_id, level, score) for step 1, 2, or 3.
     *
     * @param int $stepNumber 1, 2, or 3
     */
    public function getStep(int $stepNumber): array
    {
        return [
            'test_id' => $this->{"step_{$stepNumber}_test_id"} ?? null,
            'attempt_id' => $this->{"step_{$stepNumber}_attempt_id"} ?? null,
            'level' => $this->{"step_{$stepNumber}_level"} ?? null,
            'score' => $this->{"step_{$stepNumber}_score"} ?? null,
        ];
    }

    /**
     * Record the result of a completed step and advance current_step
     * (unless this was step 3, the last one).
     *
     * @param int    $stepNumber 1, 2, or 3
     * @param int    $testId     The IeltsTest id served for this step
     * @param int    $attemptId  The IeltsTestAttempt id created for this step
     * @param string $level      CEFR level of the test served (A1/A2/B1/B2/B2+)
     * @param int    $score      Number of correct answers out of 10
     */
    public function recordStep(int $stepNumber, int $testId, int $attemptId, string $level, int $score): void
    {
        $this->{"step_{$stepNumber}_test_id"} = $testId;
        $this->{"step_{$stepNumber}_attempt_id"} = $attemptId;
        $this->{"step_{$stepNumber}_level"} = $level;
        $this->{"step_{$stepNumber}_score"} = $score;

        if ($stepNumber < 3) {
            $this->current_step = $stepNumber + 1;
        }

        $this->updated_at = time();
        $this->save();
    }

    /**
     * Mark the whole placement attempt as completed with its final CEFR level.
     * Automatically computes total_time_seconds from started_at.
     */
    public function markCompleted(string $finalLevel): void
    {
        $this->final_level = $finalLevel;
        $this->status = 'completed';
        $this->completed_at = time();

        if (!empty($this->started_at)) {
            $this->total_time_seconds = $this->completed_at - $this->started_at;
        }

        $this->updated_at = time();
        $this->save();
    }

    /**
     * Attach the Speaking recording. Review fields (reviewed_by / notes) are
     * filled in separately later by a teacher/staff member.
     */
    public function attachSpeakingRecording(int $questionId, string $audioPath): void
    {
        $this->speaking_question_id = $questionId;
        $this->speaking_audio_path = $audioPath;
        $this->updated_at = time();
        $this->save();
    }

    public function markSpeakingReviewed(int $reviewerUserId, ?string $notes = null): void
    {
        $this->speaking_reviewed_by = $reviewerUserId;
        $this->speaking_reviewed_at = time();
        $this->speaking_review_notes = $notes;
        $this->updated_at = time();
        $this->save();
    }
}