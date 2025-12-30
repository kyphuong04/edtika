<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'overall_band' => 'float',
        'progress_percentage' => 'float',
    ];
    
    // Relationships
    
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
    
    // Scopes
    
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
    
    // Helper Methods
    
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
    
    /**
     * Get next section to navigate to
     */
    public function getNextSection()
    {
        $skillOrder = ['listening', 'reading', 'writing', 'speaking'];
        $currentIndex = array_search($this->current_skill, $skillOrder);
        
        if ($currentIndex === false || $currentIndex >= count($skillOrder) - 1) {
            return null; // Last section
        }
        
        $nextSkill = $skillOrder[$currentIndex + 1];
        
        return $this->test->sections()
            ->where('skill', $nextSkill)
            ->orderBy('sort_order')
            ->first();
    }
    
    /**
     * Update progress percentage
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
     * Mark section as completed
     */
    public function completeSection($skill)
    {
        $completedField = $skill . '_completed';
        $finishedField = $skill . '_finished_at';
        
        $this->{$completedField} = true;
        $this->{$finishedField} = time();
        $this->updated_at = time();
        $this->save();
    }
    
    /**
     * Calculate time remaining
     */
    public function getTimeRemaining()
    {
        if ($this->remaining_time_seconds !== null) {
            return $this->remaining_time_seconds;
        }
        
        $totalDuration = $this->test->total_duration * 60; // Convert to seconds
        $elapsed = time() - $this->started_at;
        
        return max(0, $totalDuration - $elapsed);
    }
    
    /**
     * Check if time has expired
     */
    public function hasExpired()
    {
        return $this->getTimeRemaining() <= 0;
    }
}
