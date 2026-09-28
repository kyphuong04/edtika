<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsAttemptHighlight extends Model
{
    protected $table = 'ielts_attempt_highlights';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function attempt()
    {
        return $this->belongsTo(IeltsTestAttempt::class, 'attempt_id');
    }
}