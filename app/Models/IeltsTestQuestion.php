<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsTestQuestion extends Model
{
    public $timestamps = false;
    
    protected $table = 'ielts_test_questions';
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'created_at' => 'integer',
        'accept_synonyms' => 'boolean',
        'case_sensitive' => 'boolean',
        'auto_gradable' => 'boolean',
        'points' => 'float',
    ];
    
    // Relationships
    
    public function section()
    {
        return $this->belongsTo(IeltsTestSection::class, 'section_id');
    }
    
    public function questionGroup()
    {
        return $this->belongsTo(IeltsQuestionGroup::class, 'question_group_id');
    }
    
    public function answers()
    {
        return $this->hasMany(IeltsTestAnswer::class, 'question_id');
    }
    
    // Accessors & Mutators
    
    public function getCorrectAnswerArrayAttribute()
    {
        if (is_string($this->correct_answer)) {
            return json_decode($this->correct_answer, true) ?? [$this->correct_answer];
        }
        return $this->correct_answer;
    }
    
    public function getAnswerOptionsArrayAttribute()
    {
        if (is_string($this->answer_options)) {
            return json_decode($this->answer_options, true) ?? [];
        }
        return $this->answer_options ?? [];
    }
    
    /**
     * Alias for answer_options - for compatibility with views that use $q->options
     */
    public function getOptionsAttribute()
    {
        return $this->answer_options_array;
    }
    
    // Helper Methods
    
    public function isMultipleChoice()
    {
        return in_array($this->question_type, ['multiple_choice', 'true_false_ng', 'yes_no_ng']);
    }
    
    public function isMultipleSelect()
    {
        return in_array($this->question_type, ['multiple_select', 'multiple_choice_multiple', 'choose_two', 'choose_three']);
    }
    
    public function isFillBlank()
    {
        return in_array($this->question_type, ['fill_blank', 'sentence_completion', 'note_completion', 'table_completion', 'summary_completion', 'flow_chart', 'diagram_label', 'short_answer']);
    }
    
    public function isEssay()
    {
        return $this->question_type === 'essay';
    }
    
    /**
     * Check if answer is correct
     */
    public function checkAnswer($userAnswer)
    {
        if (!$this->auto_gradable) {
            return null; // Requires manual grading
        }
        
        $correctAnswers = $this->correct_answer_array;
        
        // Normalize answer
        $userAnswer = $this->normalizeAnswer($userAnswer);
        
        // For multiple select, check all selections
        if ($this->isMultipleSelect()) {
            $userSelections = is_array($userAnswer) ? $userAnswer : json_decode($userAnswer, true);
            sort($userSelections);
            sort($correctAnswers);
            return $userSelections === $correctAnswers;
        }
        
        // For single answer
        foreach ($correctAnswers as $correctAnswer) {
            $normalizedCorrect = $this->normalizeAnswer($correctAnswer);
            if ($userAnswer === $normalizedCorrect) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Normalize answer for comparison
     */
    private function normalizeAnswer($answer)
    {
        if (!is_string($answer)) {
            return $answer;
        }
        
        $answer = trim($answer);
        
        if (!$this->case_sensitive) {
            $answer = strtolower($answer);
        }
        
        // Remove extra spaces
        $answer = preg_replace('/\s+/', ' ', $answer);
        
        return $answer;
    }
    
    /**
     * Validate word count for fill-in-blank
     */
    public function validateWordCount($answer)
    {
        if (!$this->max_words) {
            return true;
        }
        
        $wordCount = str_word_count($answer);
        return $wordCount <= $this->max_words;
    }
}
