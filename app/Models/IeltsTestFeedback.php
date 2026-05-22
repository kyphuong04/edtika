<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsTestFeedback extends Model
{
    public $timestamps = false;

    protected $table = 'ielts_test_feedbacks';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'integer',
    ];

    public function test()
    {
        return $this->belongsTo(IeltsTest::class, 'test_id');
    }

    public function sender()
    {
        return $this->belongsTo(\App\User::class, 'sender_id');
    }
}
