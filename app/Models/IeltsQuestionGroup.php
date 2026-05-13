<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class IeltsQuestionGroup extends Model
{
    use SoftDeletes;

    protected $table = 'ielts_question_groups';

    protected $fillable = [
        'section_id',
        'part_id',
        'creator_id',
        'bank_type',
        'skill',
        'question_type',
        'question_start',
        'question_end',
        'title',
        'description',
        'instructions',
        'passage',
        'transcript',
        'audio_file',
        'audio_path',
        'task_image',
        'video_file',
        'max_words',
        'target_band',
        'practice_focus',
        'tags',
        'difficulty_level',
        'usage_count',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'tags' => 'array',
        'target_band' => 'decimal:1',
        'usage_count' => 'integer',
    ];

    protected $appends = ['audio_url'];

    
    public function getAudioUrlAttribute()
    {
        $audioPath = $this->audio_path ?? $this->audio_file;
        
        if (!$audioPath) {
            return null;
        }
        
        // If path starts with / or http, it's already a full path
        if (str_starts_with($audioPath, '/') || str_starts_with($audioPath, 'http')) {
            return $audioPath;
        }
        
        // Use the public disk which points to /store
        return Storage::disk('public')->url($audioPath);
    }


    public function section()
    {
        return $this->belongsTo(IeltsTestSection::class, 'section_id');
    }

    public function part()
    {
        return $this->belongsTo(IeltsTestPart::class, 'part_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\User::class, 'creator_id');
    }

    public function mockQuestions()
    {
        return $this->hasMany(IeltsMockQuestionBank::class, 'group_id')
                    ->orderBy('id');
    }

    public function practiceQuestions()
    {
        return $this->hasMany(IeltsPracticeQuestionBank::class, 'group_id')
                    ->orderBy('id');
    }

    // Get questions based on bank_type
    public function questions()
    {
        if ($this->bank_type === 'mock') {
            return $this->mockQuestions();
        }
        return $this->practiceQuestions();
    }


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

    /**
     * Get human-readable question type label
     */
    public function getQuestionTypeLabel()
    {
        $types = [
            'multiple_choice' => 'Multiple Choice',
            'fill_blank' => 'Fill in the Blanks',
            'true_false_not_given' => 'True / False / Not Given',
            'yes_no_not_given' => 'Yes / No / Not Given',
            'matching_headings' => 'Matching Headings',
            'matching_information' => 'Matching Information',
            'matching_features' => 'Matching Features',
            'matching_sentence_endings' => 'Matching Sentence Endings',
            'sentence_completion' => 'Sentence Completion',
            'summary_completion' => 'Summary Completion',
            'note_completion' => 'Note Completion',
            'table_completion' => 'Table Completion',
            'flow_chart_completion' => 'Flow Chart Completion',
            'diagram_labeling' => 'Diagram Labeling',
            'map_labeling' => 'Map Labeling',
            'form_completion' => 'Form Completion',
            'short_answer' => 'Short Answer Questions',
            'matching' => 'Matching',
            'task1_graph' => 'Task 1 - Graph/Chart',
            'task1_map' => 'Task 1 - Map/Diagram',
            'task1_process' => 'Task 1 - Process',
            'task1_letter' => 'Task 1 - Letter',
            'task2_essay' => 'Task 2 - Essay',
            'part1_questions' => 'Part 1 - Interview',
            'part2_cue_card' => 'Part 2 - Cue Card',
            'part3_discussion' => 'Part 3 - Discussion',
        ];

        return $types[$this->question_type] ?? ucwords(str_replace('_', ' ', $this->question_type));
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
