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
        'linked_to_passage',
        'question_text',
        'options',
        'word_bank',
        'blank_hints',
        'correct_answer',
        'points',
    ];

    protected $casts = [
        'has_audio'          => 'boolean',
        'linked_to_passage'  => 'boolean',
        'options'            => 'array',
        'word_bank'          => 'array',
        'blank_hints'        => 'array',
        'correct_answer'     => 'array',
        'points'             => 'float',
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