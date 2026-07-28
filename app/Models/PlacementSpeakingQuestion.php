<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementSpeakingQuestion extends Model
{
    protected $fillable = [
        'question_text',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}