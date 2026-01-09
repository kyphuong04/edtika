<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Student's answer to a specific IELTS test question.
 * 
 * Handles both auto-graded questions (listening/reading) and manually graded
 * ones (writing/speaking), including audio recordings and band score breakdowns.
 */
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

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    
    public function getAnswerOptionsArrayAttribute()
    {
        if (is_string($this->answer_options)) {
            return json_decode($this->answer_options, true) ?? [];
        }
        return $this->answer_options ?? [];
    }
    
    /**
     * Get the audio recording URL for speaking answers.
     * 
     * Checks both file_url and answer_text for backward compatibility.
     * 
     * @return string|null
     */
    public function getAudioUrlAttribute()
    {
        if (!empty($this->file_url)) {
            return $this->file_url;
        }
        
        // For older records, the URL might be stored in answer_text
        if (!empty($this->answer_text)) {
            if (str_starts_with($this->answer_text, '/storage/speaking_answers/') 
                || str_contains($this->answer_text, '.webm') 
                || str_contains($this->answer_text, '.mp3')) {
                return $this->answer_text;
            }
        }
        
        return null;
    }
    
    /**
     * Check whether this answer includes an audio recording.
     * 
     * @return bool
     */
    public function hasAudioRecording()
    {
        return !empty($this->audio_url);
    }

    // grading status
    
    public function isGraded()
    {
        return $this->is_correct !== null;
    }
    
    public function needsManualGrading()
    {
        return !$this->question->auto_gradable && !$this->isGraded();
    }
    
    /**
     * Automatically grade this answer against the correct answer.
     * 
     * Only works for questions that support auto-grading (listening/reading).
     * 
     * @return bool Whether grading was successful
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
     * Manually grade this answer with detailed feedback.
     * 
     * Used for writing and speaking sections that require human evaluation.
     * 
     * @param bool $isCorrect Whether the answer is correct
     * @param float $pointsEarned Points awarded
     * @param string|null $feedback Optional grader comments
     * @param array|null $bands Optional band score breakdown
     * @return void
     */
    public function manualGrade($isCorrect, $pointsEarned, $feedback = null, $bands = null)
    {
        $this->is_correct = $isCorrect;
        $this->points_earned = $pointsEarned;
        $this->grader_feedback = $feedback;
        $this->graded_by = auth()->id();
        $this->graded_at = time();
        
        // Save detailed band scores if provided
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
