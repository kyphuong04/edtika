<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsGradingRating extends Model
{
    protected $table = 'ielts_grading_ratings';

    protected $fillable = [
        'attempt_id',
        'student_id',
        'instructor_id',
        'skill',
        'rating',
    ];

    public function attempt()
    {
        return $this->belongsTo(IeltsTestAttempt::class, 'attempt_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
