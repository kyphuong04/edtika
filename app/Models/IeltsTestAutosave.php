<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsTestAutosave extends Model
{
    public $timestamps = false;

    protected $table = 'ielts_test_autosaves';

    protected $guarded = ['id'];

    protected $casts = [
        'user_id' => 'integer',
        'test_id' => 'integer',
        'saved_at_ms' => 'integer',
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function test()
    {
        return $this->belongsTo(IeltsTest::class, 'test_id');
    }
}
