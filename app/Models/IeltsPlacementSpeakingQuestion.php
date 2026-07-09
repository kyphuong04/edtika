<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A single Speaking question in the pool used for the follow-up Speaking
 * step of the Adaptive Placement Test. One question is served at random
 * per attempt via pickRandom().
 */
class IeltsPlacementSpeakingQuestion extends Model
{
    public $timestamps = false;

    protected $table = 'ielts_placement_speaking_questions';

    protected $guarded = ['id'];

    protected $casts = [
        'prep_time_seconds' => 'integer',
        'answer_time_seconds' => 'integer',
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];

    // Relationships

    public function creator()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }

    public function placementAttempts()
    {
        return $this->hasMany(IeltsPlacementAttempt::class, 'speaking_question_id');
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helpers

    /**
     * Pick one random active question from the bank.
     * Returns null if the bank is empty (caller should handle this case).
     */
    public static function pickRandom(): ?self
    {
        return self::active()->inRandomOrder()->first();
    }
}