<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsTest extends Model
{
    public $timestamps = false;
    
    protected $table = 'ielts_tests';
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
        'submitted_for_approval_at' => 'integer',
        'approved_at' => 'integer',
        'has_listening' => 'boolean',
        'has_reading' => 'boolean',
        'has_writing' => 'boolean',
        'has_speaking' => 'boolean',
        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'require_enrollment' => 'boolean',
        'is_lead_test' => 'boolean',
        'show_answers_immediately' => 'boolean',
        'allow_retake' => 'boolean',
        'target_band_min' => 'float',
        'target_band_max' => 'float',
    ];
    
    // Relationships
    
    public function sections()
    {
        return $this->hasMany(IeltsTestSection::class, 'test_id')->orderBy('sort_order');
    }
    
    public function attempts()
    {
        return $this->hasMany(IeltsTestAttempt::class, 'test_id');
    }
    
    public function practiceCategory()
    {
        return $this->belongsTo(IeltsPracticeCategory::class, 'practice_category_id');
    }
    
    public function webinar()
    {
        return $this->belongsTo(\App\Webinar::class, 'webinar_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }
    
    public function approver()
    {
        return $this->belongsTo(\App\User::class, 'approved_by');
    }
    
    // Scopes
    
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    
    public function scopeMockTests($query)
    {
        return $query->where('type', 'mock');
    }
    
    public function scopePracticeTests($query)
    {
        return $query->where('type', 'practice');
    }
    
    public function scopeLeadTests($query)
    {
        return $query->where('is_lead_test', 1);
    }
    
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }
    
    // Helper Methods
    
    public function isMockTest()
    {
        return $this->type === 'mock';
    }
    
    public function isPracticeTest()
    {
        return $this->type === 'practice';
    }
    
    public function isPublished()
    {
        return $this->status === 'published';
    }
    
    public function isPendingApproval()
    {
        return $this->status === 'pending_approval';
    }
    
    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }
    
    public function getTotalDurationAttribute()
    {
        return ($this->listening_duration ?? 0) + 
               ($this->reading_duration ?? 0) + 
               ($this->writing_duration ?? 0) + 
               ($this->speaking_duration ?? 0);
    }
    
    public function getFormattedDurationAttribute()
    {
        $minutes = $this->total_duration;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$mins}min";
        }
        return "{$mins} min";
    }
    
    /**
     * Get user's attempts count for this test
     */
    public function getUserAttemptsCount($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
    }
    
    /**
     * Get user's best attempt
     */
    public function getUserBestAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->orderBy('overall_band', 'desc')
            ->first();
    }
    
    /**
     * Check if user can take this test
     */
    public function canUserTake($userId)
    {
        // Check if enrolled (if required)
        if ($this->require_enrollment && $this->webinar_id) {
            $enrolled = \App\Sale::where('webinar_id', $this->webinar_id)
                ->where('buyer_id', $userId)
                ->where('type', 'webinar')
                ->where('status', 'success')
                ->exists();
            
            if (!$enrolled) {
                return false;
            }
        }
        
        // Check retake limit
        if (!$this->allow_retake) {
            $attemptsCount = $this->getUserAttemptsCount($userId);
            // Mock tests: max 3 attempts
            if ($this->isMockTest() && $attemptsCount >= 3) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validate mock test requirements
     */
    public function validateMockTestStructure()
    {
        if (!$this->isMockTest()) {
            return ['valid' => true];
        }
        
        $errors = [];
        
        // Must have all 4 skills
        if (!$this->has_listening) $errors[] = 'Missing Listening section';
        if (!$this->has_reading) $errors[] = 'Missing Reading section';
        if (!$this->has_writing) $errors[] = 'Missing Writing section';
        if (!$this->has_speaking) $errors[] = 'Missing Speaking section';
        
        // Check durations
        if ($this->listening_duration != 30) $errors[] = 'Listening must be 30 minutes';
        if ($this->reading_duration != 60) $errors[] = 'Reading must be 60 minutes';
        if ($this->writing_duration != 60) $errors[] = 'Writing must be 60 minutes';
        if ($this->speaking_duration != 15) $errors[] = 'Speaking must be 15 minutes';
        
        // Check sections
        $listeningSections = $this->sections()->where('skill', 'listening')->count();
        if ($listeningSections != 4) {
            $errors[] = "Listening must have 4 parts (found {$listeningSections})";
        }
        
        $readingSections = $this->sections()->where('skill', 'reading')->count();
        if ($readingSections != 3) {
            $errors[] = "Reading must have 3 passages (found {$readingSections})";
        }
        
        $writingSections = $this->sections()->where('skill', 'writing')->count();
        if ($writingSections != 2) {
            $errors[] = "Writing must have 2 tasks (found {$writingSections})";
        }
        
        $speakingSections = $this->sections()->where('skill', 'speaking')->count();
        if ($speakingSections != 3) {
            $errors[] = "Speaking must have 3 parts (found {$speakingSections})";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
