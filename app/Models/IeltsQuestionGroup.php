<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IeltsQuestionGroup extends Model
{
    use SoftDeletes;

    protected $table = 'ielts_question_groups';

    protected $fillable = [
        'creator_id',
        'bank_type',
        'skill',
        'title',
        'description',
        'passage',
        'transcript',
        'audio_file',
        'task_image',
        'target_band',
        'practice_focus',
        'tags',
        'difficulty_level',
        'usage_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'target_band' => 'decimal:1',
        'usage_count' => 'integer',
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function mockQuestions()
    {
        return $this->hasMany(IeltsMockQuestionBank::class, 'group_id')
                    ->orderBy('question_order');
    }

    public function practiceQuestions()
    {
        return $this->hasMany(IeltsPracticeQuestionBank::class, 'group_id')
                    ->orderBy('question_order');
    }

    // Get questions based on bank_type
    public function questions()
    {
        if ($this->bank_type === 'mock') {
            return $this->mockQuestions();
        }
        return $this->practiceQuestions();
    }

    /*
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     */

    public function scopeByBankType($query, $bankType)
    {
        return $query->where('bank_type', $bankType);
    }

    public function scopeBySkill($query, $skill)
    {
        return $query->where('skill', $skill);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('passage', 'LIKE', "%{$term}%");
        });
    }

    public function scopeWithTags($query, array $tags)
    {
        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhereJsonContains('tags', $tag);
            }
        });
    }

    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /*
     |--------------------------------------------------------------------------
     | Accessors
     |--------------------------------------------------------------------------
     */

    public function getSkillLabelAttribute()
    {
        return ucfirst($this->skill);
    }

    public function getQuestionCountAttribute()
    {
        if ($this->bank_type === 'mock') {
            return $this->mockQuestions()->count();
        }
        return $this->practiceQuestions()->count();
    }

    public function getDifficultyBadgeAttribute()
    {
        return [
            'beginner' => 'success',
            'intermediate' => 'warning',
            'advanced' => 'danger',
        ][$this->difficulty_level] ?? 'secondary';
    }

    public function getBankTypeLabelAttribute()
    {
        return $this->bank_type === 'mock' ? 'Mock' : 'Practice';
    }

    /*
     |--------------------------------------------------------------------------
     | Mutators
     |--------------------------------------------------------------------------
     */

    public function setTagsAttribute($value)
    {
        if (is_string($value)) {
            // Convert comma-separated string to array
            $tags = array_map('trim', explode(',', $value));
            $this->attributes['tags'] = json_encode(array_filter($tags));
        } else {
            $this->attributes['tags'] = json_encode($value);
        }
    }

    /*
     |--------------------------------------------------------------------------
     | Methods
     |--------------------------------------------------------------------------
     */

    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    public function hasQuestions()
    {
        return $this->question_count > 0;
    }

    public function getContentType()
    {
        if ($this->skill === 'reading' && $this->passage) {
            return 'passage';
        }
        if ($this->skill === 'listening' && ($this->audio_file || $this->transcript)) {
            return 'audio';
        }
        if ($this->skill === 'writing' && $this->task_image) {
            return 'image';
        }
        return 'text';
    }
}
