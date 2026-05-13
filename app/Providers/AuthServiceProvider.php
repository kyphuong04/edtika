<?php

namespace App\Providers;

use App\Models\Api\CourseForumAnswer;
use App\Models\Webinar;
use App\Models\CourseForum;
use App\Models\Section;
use App\Policies\CourseForumAnswerPolicy;
use App\Policies\CourseForumPolicy;
use App\Policies\WebinarPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        CourseForum::class => CourseForumPolicy::class,
        CourseForumAnswer::class => CourseForumAnswerPolicy::class,
        Webinar::class => WebinarPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Nếu bảng sections chưa tồn tại thì bỏ qua.
        if (!Schema::hasTable('sections')) {
            return;
        }

        $minutes = 60 * 60; // 1 hour

        $sections = Cache::remember('sections', $minutes, function () {
            return Section::all();
        });

        foreach ($sections as $section) {
            Gate::define($section->name, function ($user) use ($section) {
                return $user->hasPermission($section->name);
            });
        }
    }
}