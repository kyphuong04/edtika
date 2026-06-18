<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveCourse extends Model
{
    public $timestamps = false;

    protected $table = 'live_courses';

    protected $guarded = ['id'];

    public static $Active   = 'active';
    public static $Inactive = 'inactive';

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany('App\\User', 'live_course_students', 'live_course_id', 'user_id');
    }

    public function getJoinLink()
    {
        return $this->link;
    }
}
