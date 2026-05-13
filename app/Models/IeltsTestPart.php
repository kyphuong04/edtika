<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsTestPart extends Model
{
    public $timestamps = false;

    protected $table = 'ielts_test_parts';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];

    // Relationships

    public function section()
    {
        return $this->belongsTo(IeltsTestSection::class, 'section_id');
    }

    public function questionGroups()
    {
        return $this->hasMany(IeltsQuestionGroup::class, 'part_id')->orderBy('id');
    }

    public function questions()
    {
        return $this->hasMany(IeltsTestQuestion::class, 'part_id')->orderBy('id');
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helpers

    public function getQuestionCount()
    {
        return $this->questions()->count();
    }

    public function getGroupCount()
    {
        return $this->questionGroups()->count();
    }
}
