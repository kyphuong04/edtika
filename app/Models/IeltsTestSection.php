<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    
    public function questions()
    {
        return $this->hasMany(IeltsTestQuestion::class, 'section_id')->orderBy('sort_order');
    }
    
    // Scopes
    
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
    
    // Helper Methods
    
    public function getTotalQuestionsAttribute()
    {
        return $this->question_end - $this->question_start + 1;
    }
    
    public function getQuestionRangeAttribute()
    {
        return "Q{$this->question_start}-{$this->question_end}";
    }
    
    public function hasAudio()
    {
        return !empty($this->audio_file);
    }
    
    public function hasPassage()
    {
        return !empty($this->passage_text);
    }
    
    public function hasImage()
    {
        return !empty($this->image_file);
    }
}
