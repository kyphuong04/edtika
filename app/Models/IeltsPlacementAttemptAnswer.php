<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsPlacementAttemptAnswer extends Model
{
    protected $fillable = [
        'attempt_id',
        'placement_test_id',
        'placement_question_id',
        'answer_given',
        'is_correct',
    ];

    protected $casts = [
        'answer_given' => 'array',
        'is_correct'   => 'boolean',
    ];
}