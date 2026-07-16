<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IeltsPlacementAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'current_step',
        'test_ids_taken',
        'scores',
        'current_level',
        'final_level',
        'started_at',
        'completed_at',
        'speaking_recording_path',
    ];

    protected $casts = [
        'test_ids_taken' => 'array',
        'scores'         => 'array',
        'started_at'     => 'datetime',
        'completed_at'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}