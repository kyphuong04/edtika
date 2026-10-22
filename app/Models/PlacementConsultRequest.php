<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementConsultRequest extends Model
{
    public const STATUS_NEW = 'new';

    protected $table = 'placement_consult_requests';

    protected $fillable = [
        'attempt_id',
        'user_id',
        'full_name',
        'mobile',
        'email',
        'course_key',
        'course_title',
        'final_level',
        'status',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(IeltsPlacementAttempt::class, 'attempt_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }
}
