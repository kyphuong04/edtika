<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsPracticeCategory extends Model
{
    public $timestamps = false;
    
    protected $table = 'ielts_practice_categories';
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'created_at' => 'integer',
        'is_active' => 'boolean',
    ];
    
    // Relationships
    
    public function tests()
    {
        return $this->hasMany(IeltsTest::class, 'practice_category_id');
    }
    
    public function progressRecords()
    {
        return $this->hasMany(IeltsPracticeProgress::class, 'category_id');
    }
    
    // Scopes
    
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    
    public function scopeBySkill($query, $skill)
    {
        return $query->where('skill', $skill);
    }
    
    public function scopeByType($query, $type)
    {
        return $query->where('category_type', $type);
    }
    
    // Helper Methods
    
    public function getTestsCount()
    {
        return $this->tests()->where('is_active', 1)->count();
    }
    
    public function getUserProgress($userId)
    {
        return $this->progressRecords()->where('user_id', $userId)->first();
    }
}
