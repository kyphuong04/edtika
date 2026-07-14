<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementQuestion extends Model
{
    protected $fillable = [
        'placement_test_id',
        'order_index',
        'type',
        'has_audio',
        'audio_path',
        'question_text',
        'options',
        'correct_answer',
        'points',
    ];

    protected $casts = [
        'has_audio'      => 'boolean',
        'options'        => 'array',
        'correct_answer' => 'array',
        'points'         => 'float',
    ];

    public function placementTest(): BelongsTo
    {
        return $this->belongsTo(PlacementTest::class);
    }

    public function skillLabel(): string
    {
        return $this->has_audio ? 'Listening' : 'Reading/Language use';
    }
}