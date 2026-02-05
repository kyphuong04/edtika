<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsMockQuestionBank extends Model
{
    protected $table = 'ielts_mock_question_bank';
    
    public $timestamps = false;
    
    protected $fillable = [
        'group_id',
        'question_order',
        'skill',
        'section_type',
        'question_type',
        'question_text',
        'question_data',
        'table_structure',
        'instruction',
        'passage_text',
        'audio_file',
        'image_file',
        'timestamp_start',
        'timestamp_end',
        'answer_options',
        'correct_answer',
        'alternative_answers',
        'word_limit',
        'marks',
        'explanation',
        'points',
        'difficulty_level',
        'tags',
        'usage_count',
        'last_used_at',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'answer_options' => 'array',
        'table_structure' => 'array',
        'question_data' => 'array',
        'tags' => 'array',
        'points' => 'decimal:1',
        'usage_count' => 'integer',
        'last_used_at' => 'integer',
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];
    
    // ============================================
    // Relationships
    // ============================================
    
    /**
     * Creator relationship
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Updater relationship
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    /**
     * Tests that use this question (through sources link)
     */
    public function testUsages()
    {
        return $this->hasMany(IeltsTestQuestionSource::class, 'bank_question_id')
            ->where('bank_type', 'mock');
    }
    
    /**
     * Question group this question belongs to
     */
    public function group()
    {
        return $this->belongsTo(IeltsQuestionGroup::class, 'group_id');
    }
    
    // ============================================
    // Scopes (Query Filters)
    // ============================================
    
    /**
     * Filter by skill
     */
    public function scopeBySkill($query, $skill)
    {
        return $query->where('skill', $skill);
    }
    
    /**
     * Filter by question type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('question_type', $type);
    }
    
    /**
     * Filter by difficulty
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }
    
    /**
     * Search by question text
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('question_text', 'LIKE', "%{$search}%")
              ->orWhere('passage_text', 'LIKE', "%{$search}%")
              ->orWhere('instruction', 'LIKE', "%{$search}%");
        });
    }
    
    /**
     * Filter by tags (JSON search)
     */
    public function scopeByTag($query, $tag)
    {
        return $query->whereRaw('JSON_CONTAINS(tags, ?)', [json_encode($tag)]);
    }
    
    /**
     * Order by usage count (most/least used)
     */
    public function scopeMostUsed($query)
    {
        return $query->orderBy('usage_count', 'desc');
    }
    
    public function scopeLeastUsed($query)
    {
        return $query->orderBy('usage_count', 'asc');
    }
    
    /**
     * Exclude recently used questions
     */
    public function scopeExcludeRecentlyUsed($query, $days = 30)
    {
        $cutoff = time() - ($days * 24 * 60 * 60);
        return $query->where(function($q) use ($cutoff) {
            $q->whereNull('last_used_at')
              ->orWhere('last_used_at', '<', $cutoff);
        });
    }
    
    /**
     * Order by newest first
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    
    // ============================================
    // Accessors & Mutators
    // ============================================
    
    /**
     * Get skill label with icon
     */
    public function getSkillLabelAttribute()
    {
        $icons = [
            'listening' => '🎧',
            'reading' => '📖',
            'writing' => '✍️',
            'speaking' => '🗣️',
        ];
        
        return ($icons[$this->skill] ?? '') . ' ' . ucfirst($this->skill);
    }
    
    /**
     * Get difficulty badge class
     */
    public function getDifficultyBadgeAttribute()
    {
        $badges = [
            'beginner' => 'success',
            'intermediate' => 'warning',
            'advanced' => 'danger',
        ];
        
        return $badges[$this->difficulty_level] ?? 'secondary';
    }
    
    /**
     * Get question type readable name
     */
    public function getTypeNameAttribute()
    {
        return str_replace('_', ' ', ucwords($this->question_type, '_'));
    }
    
    /**
     * Check if question has options (multiple choice/matching)
     */
    public function getHasOptionsAttribute()
    {
        return in_array($this->question_type, [
            'multiple_choice',
            'matching_information',
            'matching_features',
            'matching_headings',
            'matching_sentence_endings',
            'yes_no_not_given',
            'true_false_not_given',
        ]);
    }
    
    /**
     * Check if auto-gradable
     */
    public function getAutoGradableAttribute()
    {
        return !in_array($this->question_type, [
            'essay',
            'letter_writing',
            'graph_description',
            'report_writing',
            'speaking_prompt',
        ]);
    }
    
    // ============================================
    // Methods
    // ============================================
    
    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => time()]);
    }
    
    /**
     * Get formatted tags as badges
     */
    public function getTagBadges()
    {
        if (empty($this->tags)) {
            return '';
        }
        
        $html = '';
        foreach ($this->tags as $tag) {
            $html .= '<span class="badge badge-light mr-1">' . e($tag) . '</span>';
        }
        
        return $html;
    }
    
    /**
     * Clone question to create duplicate
     */
    public function duplicate()
    {
        $clone = $this->replicate();
        $clone->question_text = $this->question_text . ' (Copy)';
        $clone->usage_count = 0;
        $clone->last_used_at = null;
        $clone->created_at = time();
        $clone->updated_at = time();
        $clone->save();
        
        return $clone;
    }
}
