<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a section within an IELTS test (e.g., Listening Part 1, Reading Passage 2).
 * 
 * Each section belongs to a skill and may link to a reusable Question Group from the bank.
 * Stores duration, question range, audio/passage content, and display order.
 */
class IeltsTestSection extends Model
{
    public $timestamps = false;
    
    protected $table = 'ielts_test_sections';
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'created_at' => 'integer',
        'auto_start' => 'boolean',
    ];
    
    // Relationships
    
    public function test()
    {
        return $this->belongsTo(IeltsTest::class, 'test_id');
    }
    
    /**
     * Link to Question Group (Part)
     */
    public function questionGroup()
    {
        return $this->belongsTo(IeltsQuestionGroup::class, 'question_group_id');
    }
    
    /**
     * Questions that appear in this specific section.
     * 
     * Questions are stored in the ielts_test_questions table.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(IeltsTestQuestion::class, 'section_id')->orderBy('sort_order');
    }
    
    /**
     * Get all questions as a collection (for use in views)
     */
    public function getQuestionsFromGroup()
    {
        return $this->questions()->get();
    }
    
   // query scopes
    
    public function scopeListening($query)
    {
        return $query->where('skill', 'listening');
    }
    
    public function scopeReading($query)
    {
        return $query->where('skill', 'reading');
    }
    
    public function scopeWriting($query)
    {
        return $query->where('skill', 'writing');
    }
    
    public function scopeSpeaking($query)
    {
        return $query->where('skill', 'speaking');
    }
    //content & metadata
    
    public function getTotalQuestionsAttribute()
    {
        // If this section uses a question group, get the count from there
        if ($this->question_group_id && $this->questionGroup) {
            return $this->questionGroup->question_count;
        }
        
        // Otherwise, calculate based on question number range
        if ($this->question_end && $this->question_start) {
            return $this->question_end - $this->question_start + 1;
        }
        
        return 0;
    }
    
    public function getQuestionRangeAttribute()
    {
        return "Q{$this->question_start}-{$this->question_end}";
    }
    
    public function hasAudio()
    {
        // Check this section's own audio file first
        if (!empty($this->audio_file)) {
            return true;
        }
        // Fall back to the linked question group's audio
        if ($this->question_group_id && $this->questionGroup) {
            return !empty($this->questionGroup->audio_file);
        }
        return false;
    }
    
    public function hasPassage()
    {
        // Check this section's own passage first
        if (!empty($this->passage_text)) {
            return true;
        }
        // Fall back to the linked question group's passage
        if ($this->question_group_id && $this->questionGroup) {
            return !empty($this->questionGroup->passage);
        }
        return false;
    }
    
    /**
     * Retrieve passage text from this section or its linked question group.
     * 
     * @param mixed $value
     * @return string|null
     */
    public function getPassageTextAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        if ($this->question_group_id && $this->questionGroup) {
            return $this->questionGroup->passage;
        }
        return null;
    }
    
    /**
     * Retrieve audio file path from this section or its linked question group.
     * 
     * @param mixed $value
     * @return string|null
     */
    public function getAudioFileAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        // Fall back to the question group's audio path (or audio_file if audio_path isn't set)
        if ($this->question_group_id && $this->questionGroup) {
            return $this->questionGroup->audio_path ?? $this->questionGroup->audio_file;
        }
        return null;
    }
    
    /**
     * Get a properly formatted URL for audio playback.
     * 
     * @return string|null
     */
    public function getAudioUrlAttribute()
    {
        // Prefer audio from the linked question group (most reliable source)
        if ($this->question_group_id && $this->questionGroup && $this->questionGroup->audio_url) {
            return $this->questionGroup->audio_url;
        }
        
        // Otherwise, check this section's own audio_file
        $audioFile = $this->getRawOriginal('audio_file') ?? $this->attributes['audio_file'] ?? null;
        
        if (!$audioFile) {
            return null;
        }
        
        // If it's already a full URL or absolute path, use it as-is
        if (str_starts_with($audioFile, '/') || str_starts_with($audioFile, 'http')) {
            return $audioFile;
        }
        
        // Otherwise, generate URL using Laravel's storage system
        return \Storage::disk('public')->url($audioFile);
    }
    
    public function hasImage()
    {
        return !empty($this->image_file);
    }

    public function hasVideo()
    {
        return !empty($this->attributes['video_file'] ?? null);
    }

    /**
     * Get a properly formatted URL for video playback.
     *
     * @return string|null
     */
    public function getVideoUrlAttribute()
    {
        $videoFile = $this->attributes['video_file'] ?? null;

        if (!$videoFile) {
            // Fallback: check the linked question group's video_file
            if ($this->question_group_id && $this->questionGroup && $this->questionGroup->video_file) {
                $groupVideo = $this->questionGroup->video_file;
                if (str_starts_with($groupVideo, '/') || str_starts_with($groupVideo, 'http')) {
                    return $groupVideo;
                }
                return \Storage::disk('public')->url($groupVideo);
            }
            return null;
        }

        if (str_starts_with($videoFile, '/') || str_starts_with($videoFile, 'http')) {
            return $videoFile;
        }

        return \Storage::disk('public')->url($videoFile);
    }
}
