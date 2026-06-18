<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Represents a single test attempt by a student.
 * 
 * Each attempt tracks progress through listening, reading, writing, and speaking
 * sections, along with scores and band calculations.
 */
class IeltsTestAttempt extends Model
{
    public $timestamps = false;
    
    protected $table = 'ielts_test_attempts';
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'started_at' => 'integer',
        'paused_at' => 'integer',
        'completed_at' => 'integer',
        'updated_at' => 'integer',
        'listening_finished_at' => 'integer',
        'reading_finished_at' => 'integer',
        'writing_finished_at' => 'integer',
        'speaking_finished_at' => 'integer',
        'listening_completed' => 'boolean',
        'reading_completed' => 'boolean',
        'writing_completed' => 'boolean',
        'speaking_completed' => 'boolean',
        'listening_score' => 'float',
        'reading_score' => 'float',
        'writing_score' => 'float',
        'speaking_score' => 'float',
        'listening_band' => 'float',
        'reading_band' => 'float',
        'writing_band' => 'float',
        'speaking_band' => 'float',
        'overall_band' => 'float',
        'progress_percentage' => 'float',
        'writing_criteria' => 'array',
        'speaking_criteria' => 'array',
        'writing_graded_at' => 'integer',
        'speaking_graded_at' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function test()
    {
        return $this->belongsTo(IeltsTest::class, 'test_id');
    }
    
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
    
    public function answers()
    {
        return $this->hasMany(IeltsTestAnswer::class, 'attempt_id');
    }
    
    public function currentSection()
    {
        return $this->belongsTo(IeltsTestSection::class, 'current_section_id');
    }
    
    public function quizResult()
    {
        return $this->belongsTo(\App\QuizzesResult::class, 'quiz_result_id');
    }
    
    public function writingGrader()
    {
        return $this->belongsTo(\App\User::class, 'writing_graded_by');
    }
    
    public function speakingGrader()
    {
        return $this->belongsTo(\App\User::class, 'speaking_graded_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Checks
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
    
    public function isPaused()
    {
        return $this->status === 'paused';
    }
    
    /*
    |--------------------------------------------------------------------------
    | Section Navigation
    |--------------------------------------------------------------------------
    */

    /**
     * Find the next section in sequence.
     *
     * @return IeltsTestSection|null
     */
    public function getNextSection()
    {
        $allSections = $this->test->sections()->orderBy('sort_order')->get();

        if ($allSections->isEmpty()) {
            return null;
        }

        $currentSectionId = $this->current_section_id;
        $currentIndex = -1;

        foreach ($allSections as $index => $section) {
            if ($section->id == $currentSectionId) {
                $currentIndex = $index;
                break;
            }
        }

        if ($currentIndex >= 0 && $currentIndex < $allSections->count() - 1) {
            return $allSections[$currentIndex + 1];
        }

        return null;
    }
    
    /*
    |--------------------------------------------------------------------------
    | Progress Tracking
    |--------------------------------------------------------------------------
    */

    /**
     * Update the progress percentage based on answered questions.
     *
     * @return void
     */
    public function updateProgress()
    {
        $totalAnswered = $this->answers()->whereNotNull('answer_text')->count();
        $this->total_questions_answered = $totalAnswered;

        if ($this->total_questions > 0) {
            $this->progress_percentage = ($totalAnswered / $this->total_questions) * 100;
        }

        $this->updated_at = time();
        $this->save();
    }
    
    /**
     * Mark a specific skill section as completed.
     *
     * @param string $skill One of: listening, reading, writing, speaking
     * @return void
     */
    public function completeSection($skill)
    {
        $completedField = $skill . '_completed';
        $finishedField = $skill . '_finished_at';

        if (Schema::hasColumn($this->getTable(), $completedField)) {
            $this->{$completedField} = true;
        }

        if (Schema::hasColumn($this->getTable(), $finishedField)) {
            $this->{$finishedField} = time();
        }

        $this->updated_at = time();
        $this->save();
    }
    
    /*
    |--------------------------------------------------------------------------
    | Time Management
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate how much time is left for this attempt.
     *
     * @return int Seconds remaining
     */
    public function getTimeRemaining()
    {
        if ($this->remaining_time_seconds !== null) {
            return $this->remaining_time_seconds;
        }

        $totalDuration = $this->test->total_duration * 60;
        $elapsed = time() - $this->started_at;

        return max(0, $totalDuration - $elapsed);
    }

    /**
     * Check whether time has run out.
     *
     * @return bool
     */
    public function hasExpired()
    {
        $totalDuration = $this->test->total_duration ?? 0;

        if ($totalDuration <= 0) {
            return false;
        }

        return $this->getTimeRemaining() <= 0;
    }
}
