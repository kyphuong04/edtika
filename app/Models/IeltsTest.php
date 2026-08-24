<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A complete IELTS test containing listening, reading, writing, and speaking sections.
 * 
 * Can be either a full mock test (all 4 skills) or practice test (focus on specific skills).
 * Tracks approval workflow, enrollment requirements, and retake policies.
 */
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

    public function scopeDiagnosticTests($query)
    {
        return $query->where('type', 'diagnostic');
    }

    public function isDiagnosticTest()
    {
        return $this->type === 'diagnostic';
    }

    /**
     * Practice & Diagnostic tests both use flexible (seekable/replayable) audio.
     * Only Mock tests use the strict "no rewind" exam-style audio overlay.
     */
    public function usesFlexibleAudioPlayback()
    {
        return $this->isPracticeTest() || $this->isDiagnosticTest();
    }
    
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
        return $this->belongsTo(\App\Models\Webinar::class, 'webinar_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }
    
    public function approver()
    {
        return $this->belongsTo(\App\User::class, 'approved_by');
    }

    public function feedbacks()
    {
        return $this->hasMany(IeltsTestFeedback::class, 'test_id')->orderByDesc('created_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */
    
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

    /*
    |--------------------------------------------------------------------------
    | Test Type Checks
    |--------------------------------------------------------------------------
    */
    
    public function isMockTest()
    {
        return $this->type === 'mock';
    }
    
    public function isPracticeTest()
    {
        return $this->type === 'practice';
    }
    
    /**
     * Determine which skill is the main focus for practice tests.
     * 
     * @return string|null 'listening', 'reading', 'writing', 'speaking', or null
     */
    public function getPrimarySkill()
    {
        if ($this->has_listening) return 'listening';
        if ($this->has_reading) return 'reading';
        if ($this->has_writing) return 'writing';
        if ($this->has_speaking) return 'speaking';
        return null;
    }
    /*
    |--------------------------------------------------------------------------
    | Status Management
    |--------------------------------------------------------------------------
    */
    
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
    /*
    |--------------------------------------------------------------------------
    | Duration Calculations
    |--------------------------------------------------------------------------
    */
    
    public function getTotalDurationAttribute()
    {
        // First, check if sections have duration set
        if ($this->relationLoaded('sections') && $this->sections->count() > 0) {
            return $this->sections->sum('duration_minutes') ?: 0;
        }
        
        // If sections aren't loaded yet, query the database
        $sectionDuration = $this->sections()->sum('duration_minutes');
        if ($sectionDuration > 0) {
            return $sectionDuration;
        }
        
        // Fall back to legacy skill-specific duration fields
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
    /*
    |--------------------------------------------------------------------------
    | User Attempt Tracking
    |--------------------------------------------------------------------------
    */
    
    /**
     * Count how many times a user has completed this test.
     * 
     * @param int $userId
     * @return int
     */
    public function getUserAttemptsCount($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
    }
    
    /**
     * Find a user's highest-scoring attempt on this test.
     * 
     * @param int $userId
     * @return IeltsTestAttempt|null
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
     * Determine whether a user is allowed to take this test.
     * 
     * Checks enrollment, daily limits (for mocks), and per-test attempt limits.
     * 
     * @param int $userId
     * @return true|string Returns true if allowed, or reason code if not: 'daily_limit', 'max_attempts', 'not_enrolled'
     */
    public function canUserTake($userId)
    {
        // First, verify enrollment if this test requires it
        if ($this->require_enrollment && $this->webinar_id) {
            $enrolled = \App\Sale::where('webinar_id', $this->webinar_id)
                ->where('buyer_id', $userId)
                ->where('type', 'webinar')
                ->where('status', 'success')
                ->exists();
            
            if (!$enrolled) {
                return 'not_enrolled';
            }
        }
        
        // For mock tests, enforce daily limit and per-test attempt limit
        if ($this->isMockTest()) {
            // $dailyLimit = (int) (getIeltsSettings('mock_tests_per_day') ?? 2);
            // $todayAttempts = self::getUserMockAttemptsToday($userId);
            
            // if ($todayAttempts >= $dailyLimit) {
            //     return 'daily_limit';
            // }
            
            // // Also check the max attempts per individual test (default 3)
            // if (!$this->allow_retake) {
            //     $attemptsCount = $this->getUserAttemptsCount($userId);
            //     if ($attemptsCount >= 3) {
            //         return 'max_attempts';
            //     }
            // }
        }

        // Diagnostic tests: intended as a one-time placement test by default.
        // Allow retake only if explicitly enabled on the test.
        if ($this->isDiagnosticTest() && !$this->allow_retake) {
            $attemptsCount = $this->getUserAttemptsCount($userId);
            if ($attemptsCount >= 1) {
                return 'max_attempts';
            }
        }
        
        return true;
    }
    
    /**
     * Count how many mock tests a user has attempted today (across ALL mock tests).
     * 
     * @param int $userId
     * @return int
     */
    public static function getUserMockAttemptsToday($userId)
    {
        $todayStart = strtotime('today 00:00:00');
        $todayEnd = strtotime('today 23:59:59');
        
        return IeltsTestAttempt::whereHas('test', function($q) {
                $q->where('type', 'mock');
            })
            ->where('user_id', $userId)
            ->whereBetween('started_at', [$todayStart, $todayEnd])
            ->count();
    }
    
    /**
     * How many more mock tests can the user take today?
     * 
     * @param int $userId
     * @return int Number of remaining attempts
     */
    public static function getRemainingMockTestsToday($userId)
    {
        $dailyLimit = (int) (getIeltsSettings('mock_tests_per_day') ?? 2);
        $todayAttempts = self::getUserMockAttemptsToday($userId);
        return max(0, $dailyLimit - $todayAttempts);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */
    
    /**
     * Quick validation check for mock test structure.
     * 
     * @return array ['valid' => bool, 'errors' => string[]]
     */
    public function validateMockTestStructure()
    {
        if (!$this->isMockTest()) {
            return ['valid' => true];
        }
        
        $errors = [];
        
        $sectionCount = $this->sections()->count();
        if ($sectionCount === 0) {
            $errors[] = 'Test must have at least one section';
        }
        
        $totalQuestions = 0;
        foreach ($this->sections as $section) {
            $totalQuestions += $section->questions()->count();
        }
        
        if ($totalQuestions === 0) {
            $errors[] = 'Test must have at least one question';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Comprehensive validation before publishing a full mock test.
     * 
     * Ensures all 4 skills are present with proper sections.
     * 
     * @return array ['valid' => bool, 'errors' => string[]]
     */
    public function validateFullMockTestStructure()
    {
        $errors = [];
        
        // All 4 skills must be included
        if (!$this->has_listening) $errors[] = 'Missing Listening section';
        if (!$this->has_reading) $errors[] = 'Missing Reading section';
        if (!$this->has_writing) $errors[] = 'Missing Writing section';
        if (!$this->has_speaking) $errors[] = 'Missing Speaking section';
        
        // Validate that each enabled skill has at least one section
        $listeningSections = $this->sections()->where('skill', 'listening')->count();
        if ($this->has_listening && $listeningSections < 1) {
            $errors[] = "Listening should have at least 1 part (found {$listeningSections})";
        }
        
        $readingSections = $this->sections()->where('skill', 'reading')->count();
        if ($this->has_reading && $readingSections < 1) {
            $errors[] = "Reading should have at least 1 passage (found {$readingSections})";
        }
        
        $writingSections = $this->sections()->where('skill', 'writing')->count();
        if ($this->has_writing && $writingSections < 1) {
            $errors[] = "Writing should have at least 1 task (found {$writingSections})";
        }
        
        $speakingSections = $this->sections()->where('skill', 'speaking')->count();
        if ($this->has_speaking && $speakingSections < 1) {
            $errors[] = "Speaking should have at least 1 part (found {$speakingSections})";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
}
