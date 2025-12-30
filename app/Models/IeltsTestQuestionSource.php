<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsTestQuestionSource extends Model
{
    protected $table = 'ielts_test_question_sources';
    
    public $timestamps = false;
    
    protected $fillable = [
        'test_id',
        'test_question_id',
        'bank_type',
        'bank_question_id',
        'created_at',
    ];
    
    protected $casts = [
        'created_at' => 'integer',
    ];
    
    // ============================================
    // Relationships
    // ============================================
    
    /**
     * Test that this source belongs to
     */
    public function test()
    {
        return $this->belongsTo(IeltsTest::class, 'test_id');
    }
    
    /**
     * Test question in the actual test
     */
    public function testQuestion()
    {
        return $this->belongsTo(IeltsTestQuestion::class, 'test_question_id');
    }
    
    /**
     * Source question from Mock Bank
     */
    public function mockBankQuestion()
    {
        return $this->belongsTo(IeltsMockQuestionBank::class, 'bank_question_id');
    }
    
    /**
     * Source question from Practice Bank
     */
    public function practiceBankQuestion()
    {
        return $this->belongsTo(IeltsPracticeQuestionBank::class, 'bank_question_id');
    }
    
    /**
     * Polymorphic accessor for source question (Mock or Practice)
     */
    public function getSourceQuestionAttribute()
    {
        if ($this->bank_type === 'mock') {
            return $this->mockBankQuestion;
        } else {
            return $this->practiceBankQuestion;
        }
    }
    
    // ============================================
    // Scopes
    // ============================================
    
    /**
     * Filter by bank type
     */
    public function scopeFromMockBank($query)
    {
        return $query->where('bank_type', 'mock');
    }
    
    public function scopeFromPracticeBank($query)
    {
        return $query->where('bank_type', 'practice');
    }
    
    /**
     * Filter by test
     */
    public function scopeForTest($query, $testId)
    {
        return $query->where('test_id', $testId);
    }
    
    // ============================================
    // Static Methods
    // ============================================
    
    /**
     * Link test question to bank question
     */
    public static function linkQuestion($testId, $testQuestionId, $bankType, $bankQuestionId)
    {
        return static::create([
            'test_id' => $testId,
            'test_question_id' => $testQuestionId,
            'bank_type' => $bankType,
            'bank_question_id' => $bankQuestionId,
            'created_at' => time(),
        ]);
    }
    
    /**
     * Get usage statistics for a bank question
     */
    public static function getUsageStats($bankType, $bankQuestionId)
    {
        return static::where('bank_type', $bankType)
            ->where('bank_question_id', $bankQuestionId)
            ->count();
    }
}
