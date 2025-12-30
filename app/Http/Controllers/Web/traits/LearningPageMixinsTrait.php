<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\TimeSpentOnCourse;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

trait LearningPageMixinsTrait
{
    public function getCourse($slug, $user = null, $relation = null, $relationWith = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $query = Webinar::where('slug', $slug)
            ->where('status', 'active');

        if (!empty($relation)) {
            $query->with([
                "{$relation}" => function ($query) use ($relation, $relationWith) {
                    if ($relation == 'forums') {
                        $query->orderBy('pin', 'desc');
                    }

                    $query->orderBy('created_at', 'desc');

                    if (!empty($relationWith)) {
                        $query->with($relationWith);
                    }
                }
            ])->withCount([
                "{$relation}"
            ]);
        }

        $query->with([
            'chapters' => function ($query) use ($user) {
                $query->where('status', WebinarChapter::$chapterActive);
                $query->orderBy('order', 'asc');

                $query->with([
                    'chapterItems' => function ($query) {
                        $query->orderBy('order', 'asc');
                    }
                ]);
            }
        ]);

        $course = $query->first();

        if (!empty($course) and ($course->checkUserHasBought($user) or !empty($course->getInstallmentOrder()))) {
            $isPrivate = $course->private;
            $hasBought = $course->checkUserHasBought($user);

            if (!empty($user) and ($user->id == $course->creator_id or $user->organ_id == $course->creator_id or $user->isAdmin() or $hasBought)) {
                $isPrivate = false;
            }

            if ($isPrivate) {
                return 'not_access';
            }

            return $course;
        }

        return 'not_access';
    }

    private function handleStartTrackingTime($courseId, $userId)
    {
        $time = time();

        TimeSpentOnCourse::query()->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'page' => "learning_page",
            'entry_time' => $time,
            'exit_time' => $time + 10, // After entering the page, we record the last time every 10 seconds. So at the beginning, we also record the exit time 10 seconds earlier.
            'seconds_spent' => 10,
        ]);
    }

    public function trackSpentTime(Request $request, $courseSlug)
    {
        $course = $this->getCourse($courseSlug);

        if ($course == 'not_access') {
            abort(404);
        }

        $user = auth()->user();

        // Check Concurrent Learning
        if (!$this->checkConcurrentLearning($user)) {
            return response()->json([
                'code' => 403,
                'status' => 'error',
                'msg' => trans('update.concurrent_learning_limit_reached') // You might need to add this translation or use a hardcoded string
            ]);
        }

        $trackingTime = TimeSpentOnCourse::query()->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->orderBy('entry_time', 'desc')
            ->first();

        $forceReload = true;

        if (!empty($trackingTime)) {
            $forceReload = false;
            $time = time();
            $exitTime = $time + 10;
            $secondsSpent = $exitTime - $trackingTime->entry_time;

            $trackingTime->update([
                'exit_time' => $exitTime,
                'seconds_spent' => $secondsSpent,
            ]);
        }

        return response()->json([
            'code' => 200,
            'force_reload' => $forceReload,
        ]);
    }

    public function checkConcurrentLearning($user)
    {
        // CEO, Manager, Admin, and Teacher are exempt from concurrent learning restrictions
        // They can watch videos/learn on multiple devices simultaneously
        if ($user->isCeo() or $user->isManager() or $user->isAdmin() or $user->isTeacher()) {
            return true;
        }

        // For Students and Users: Only one device can be learning at a time
        // Use cache to track which session is currently active for this user
        $cacheKey = 'learning_session_' . $user->id;
        $currentSessionId = session()->getId();
        $activeSessionId = Cache::get($cacheKey);

        // If another session is already learning, block this request
        if (!empty($activeSessionId) and $activeSessionId !== $currentSessionId) {
            return false; // Another device is currently learning
        }

        // Mark this session as the active learning session
        // Cache expires after 5 minutes of inactivity (auto-release lock)
        Cache::put($cacheKey, $currentSessionId, 300); // 300 seconds = 5 minutes

        return true; // Allow learning
    }
}


