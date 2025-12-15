<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'roles';
    protected $guarded = ['id'];
    public $timestamps = false;


    static $admin = 'admin';
    static $user = 'user';           // Lead - not purchased yet
    static $student = 'student';     // Enrolled student (purchased course)
    static $teacher = 'teacher';     // Instructor
    static $manager = 'manager';     // Manager
    static $ceo = 'ceo';             // CEO

    public $translatedAttributes = ['caption'];

    public function getCaptionAttribute()
    {
        return getTranslateAttributeValue($this, 'caption');
    }


    public function canDelete()
    {
        return !in_array($this->name, [self::$admin, self::$user, self::$student, self::$teacher, self::$manager, self::$ceo]);
    }

    public function users()
    {
        return $this->hasMany('App\User', 'role_id', 'id');
    }

    public function isDefaultRole()
    {
        return in_array($this->name, [self::$admin, self::$user, self::$student, self::$teacher, self::$manager, self::$ceo]);
    }

    public function isMainAdminRole()
    {
        return $this->name == self::$admin;
    }

    public static function getUserRoleId()
    {
        $id = 1; // user role id

        $role = self::where('name', self::$user)->first();

        return !empty($role) ? $role->id : $id;
    }

    public static function getStudentRoleId()
    {
        $id = 2; // student role id

        $role = self::where('name', self::$student)->first();

        return !empty($role) ? $role->id : $id;
    }

    public static function getTeacherRoleId()
    {
        $id = 3; // teacher role id (changed from 4 to 3)

        $role = self::where('name', self::$teacher)->first();

        return !empty($role) ? $role->id : $id;
    }

    public static function getManagerRoleId()
    {
        $id = 5; // manager role id

        $role = self::where('name', self::$manager)->first();

        return !empty($role) ? $role->id : $id;
    }

    public static function getCeoRoleId()
    {
        $id = 6; // ceo role id

        $role = self::where('name', self::$ceo)->first();

        return !empty($role) ? $role->id : $id;
    }
}



