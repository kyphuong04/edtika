<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsPracticeProgress extends Model
{
    public $timestamps = false;
    
    protected $table = 'ielts_practice_progress';
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'updated_at' => 'integer',
        'first_attempt_date' => 'integer',
        'last_practice_date' => 'integer',
        'accuracy_percentage' => 'float',
        'average_score' => 'float',
        'best_score' => 'float',
        'latest_score' => 'float',
        'improvement_rate' => 'float',
        'weak_question_types' => 'array',
        'weak_topics' => 'array',
    ];
    
    // Relationships
    
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
    
    public function category()
    {
        return $this->belongsTo(IeltsPracticeCategory::class, 'category_id');
    }
    
    // Helper Methods
    
    public function updateStats($attemptScore, $questionsAttempted, $questionsCorrect, $timeSpent)
    {
        // Update counts
        $this->total_practices += 1;
        $this->total_questions_attempted += $questionsAttempted;
        $this->total_questions_correct += $questionsCorrect;
        $this->total_time_spent_minutes += $timeSpent;
        
        // Calculate accuracy
        if ($this->total_questions_attempted > 0) {
            $this->accuracy_percentage = ($this->total_questions_correct / $this->total_questions_attempted) * 100;
        }
        
        // Update scores
        $this->latest_score = $attemptScore;
        
        if ($this->best_score === null || $attemptScore > $this->best_score) {
            $this->best_score = $attemptScore;
        }
        
        // Calculate average (simple moving average)
        if ($this->average_score === null) {
            $this->average_score = $attemptScore;
        } else {
            $this->average_score = (($this->average_score * ($this->total_practices - 1)) + $attemptScore) / $this->total_practices;
        }
        
        // Update dates
        if ($this->first_attempt_date === null) {
            $this->first_attempt_date = time();
        }
        $this->last_practice_date = time();
        
        // Calculate improvement rate
        if ($this->first_attempt_date && $this->total_practices > 1) {
            $firstScore = $this->average_score; // Approximate
            $currentScore = $this->latest_score;
            if ($firstScore > 0) {
                $this->improvement_rate = (($currentScore - $firstScore) / $firstScore) * 100;
            }
        }
        
        $this->updated_at = time();
        $this->save();
    }
    
    public function addWeakQuestionType($questionType)
    {
        $weakTypes = $this->weak_question_types ?? [];
        if (!in_array($questionType, $weakTypes)) {
            $weakTypes[] = $questionType;
            $this->weak_question_types = $weakTypes;
            $this->save();
        }
    }
    
    public function removeWeakQuestionType($questionType)
    {
        $weakTypes = $this->weak_question_types ?? [];
        $weakTypes = array_diff($weakTypes, [$questionType]);
        $this->weak_question_types = array_values($weakTypes);
        $this->save();
    }
}
