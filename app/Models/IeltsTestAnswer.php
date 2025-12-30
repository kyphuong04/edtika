<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsTestAnswer extends Model
{
    public $timestamps = false;
    
    protected $table = 'ielts_test_answers';
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'answered_at' => 'integer',
        'modified_at' => 'integer',
        'graded_at' => 'integer',
        'is_correct' => 'boolean',
        'points_earned' => 'float',
        'writing_bands' => 'array',
        'speaking_bands' => 'array',
    ];
    
    // Relationships
    
    public function attempt()
    {
        return $this->belongsTo(IeltsTestAttempt::class, 'attempt_id');
    }
    
    public function question()
    {
        return $this->belongsTo(IeltsTestQuestion::class, 'question_id');
    }
    
    public function grader()
    {
        return $this->belongsTo(\App\User::class, 'graded_by');
    }
    
    // Accessors
    
    public function getAnswerOptionsArrayAttribute()
    {
        if (is_string($this->answer_options)) {
            return json_decode($this->answer_options, true) ?? [];
        }
        return $this->answer_options ?? [];
    }
    
    // Helper Methods
    
    public function isGraded()
    {
        return $this->is_correct !== null;
    }
    
    public function needsManualGrading()
    {
        return !$this->question->auto_gradable && !$this->isGraded();
    }
    
    /**
     * Auto-grade this answer
     */
    public function autoGrade()
    {
        if (!$this->question->auto_gradable) {
            return false;
        }
        
        $isCorrect = $this->question->checkAnswer($this->answer_text ?? $this->answer_options);
        
        $this->is_correct = $isCorrect;
        $this->points_earned = $isCorrect ? $this->question->points : 0;
        $this->save();
        
        return true;
    }
    
    /**
     * Manually grade with feedback
     */
    public function manualGrade($isCorrect, $pointsEarned, $feedback = null, $bands = null)
    {
        $this->is_correct = $isCorrect;
        $this->points_earned = $pointsEarned;
        $this->grader_feedback = $feedback;
        $this->graded_by = auth()->id();
        $this->graded_at = time();
        
        // Store band scores for Writing/Speaking
        if ($bands && is_array($bands)) {
            $section = $this->question->section;
            if ($section->skill === 'writing') {
                $this->writing_bands = $bands;
            } elseif ($section->skill === 'speaking') {
                $this->speaking_bands = $bands;
            }
        }
        
        $this->save();
    }
}
