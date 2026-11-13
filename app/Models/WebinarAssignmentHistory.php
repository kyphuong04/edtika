<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebinarAssignmentHistory extends Model
{
    use \App\Models\Traits\TriggersBundleCompletion;

    protected $table = 'webinar_assignment_history';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $assignmentHistoryStatus = ['pending', 'passed', 'not_passed', 'not_submitted'];
    static $pending = 'pending';
    static $passed = 'passed';
    static $notPassed = 'not_passed';
    static $notSubmitted = 'not_submitted';

    public function instructor()
    {
        return $this->belongsTo('App\User', 'instructor_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo('App\User', 'student_id', 'id');
    }

    public function assignment()
    {
        return $this->belongsTo('App\Models\WebinarAssignment', 'assignment_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\WebinarAssignmentHistoryMessage', 'assignment_history_id', 'id');
    }

    /* ---------- GĐ4: kiểm tra hoàn thành bundle khi bài tập được chấm đạt ---------- */

    public function bundleCompletionReached(): bool
    {
        return $this->status === self::$passed && ($this->wasRecentlyCreated || $this->wasChanged('status'));
    }

    public function bundleCompletionUserId(): ?int
    {
        return $this->student_id;
    }

    public function bundleCompletionWebinarId(): ?int
    {
        return !empty($this->assignment) ? $this->assignment->webinar_id : null;
    }
}
