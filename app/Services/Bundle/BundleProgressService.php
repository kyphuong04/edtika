<?php

namespace App\Services\Bundle;

use App\Models\Bundle;
use App\Models\BundleCheckpoint;
use App\Models\File;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use App\Models\WebinarAssignmentHistory;
use App\Models\QuizzesResult;
use Illuminate\Support\Facades\DB;

/**
 * Tính tiến độ học của một học viên trong một khóa học (bundle).
 *
 * Cấu trúc: Bundle -> Module (Webinar) -> Lesson (WebinarChapter) -> Item
 * Item gồm: file/video, session, text lesson (xong khi có dòng course_learning),
 * assignment (xong khi được chấm passed), quiz (xong khi kết quả passed).
 *
 * Toàn bộ dữ liệu được lấy bằng một số query cố định (khoảng 10 query)
 * bất kể khóa có bao nhiêu module/lesson, thay cho các vòng lặp N+1 của Webinar::getProgress().
 *
 * Kết quả trả về là mảng thuần để dùng được cho cả Blade lẫn JSON.
 */
class BundleProgressService
{
    /**
     * @param Bundle $bundle
     * @param \App\User|null $user  null = chỉ lấy cấu trúc, mọi item tính là chưa xong
     * @return array
     */
    public function forUser(Bundle $bundle, $user = null): array
    {
        $userId = !empty($user) ? $user->id : null;

        $modules = $this->loadModules($bundle);
        $webinarIds = $modules->pluck('id')->all();

        $checkpoints = $bundle->checkpoints()->get();
        $checkpointQuizIds = $checkpoints->pluck('quiz_id')->filter()->map(function ($id) {
            return (int)$id;
        })->values()->all();
        $passedCheckpointQuizIds = $this->loadPassedQuizIds($userId, $checkpointQuizIds);

        // Quiz Mid-term/Final không tính vào tiến độ module, tránh vòng chờ lẫn nhau
        $items = $this->loadItems($webinarIds, $checkpointQuizIds);
        $done = $this->loadDoneItems($userId, $items);
        $lessons = $this->loadLessons($webinarIds);

        $sequentialModules = (bool)$bundle->sequential_modules;
        $sequentialLessons = is_null($bundle->sequential_lessons) ? true : (bool)$bundle->sequential_lessons;

        $resultModules = [];
        $bundleCounts = $this->emptyCounts();
        $previousModuleCompleted = true;
        $completedModulesCount = 0;

        foreach ($modules->values() as $moduleIndex => $webinar) {
            $moduleItems = $items[$webinar->id] ?? [];
            $moduleCounts = $this->countItems($moduleItems, $done);

            $moduleUnlocked = (!$sequentialModules || $moduleIndex === 0 || $previousModuleCompleted);
            $moduleCompleted = $this->isCompleted($moduleCounts);

            // Lesson trong module, theo thứ tự chapter
            $resultLessons = [];
            $previousLessonCompleted = true;

            foreach (($lessons[$webinar->id] ?? []) as $lessonIndex => $chapter) {
                $lessonItems = array_values(array_filter($moduleItems, function ($item) use ($chapter) {
                    return $item['chapter_id'] == $chapter->id;
                }));
                $lessonCounts = $this->countItems($lessonItems, $done);
                $lessonCompleted = $this->isCompleted($lessonCounts);

                $lessonUnlocked = ($moduleUnlocked && (!$sequentialLessons || $lessonIndex === 0 || $previousLessonCompleted));

                $resultLessons[] = [
                    'chapter_id' => $chapter->id,
                    'title' => $chapter->title,
                    'order_index' => $lessonIndex,
                    'progress' => $this->percent($lessonCounts),
                    'completed' => $lessonCompleted,
                    'unlocked' => $lessonUnlocked,
                    'started' => $lessonCounts['items_done'] > 0,
                    'counts' => $lessonCounts,
                    'items' => array_map(function ($item) use ($done) {
                        return $item + ['done' => !empty($done[$item['type']][$item['id']])];
                    }, $lessonItems),
                ];

                $previousLessonCompleted = $lessonCompleted;
            }

            $resultModules[] = [
                'webinar_id' => $webinar->id,
                'slug' => $webinar->slug,
                'title' => $webinar->title,
                'skill' => $webinar->skill,
                'order_index' => $moduleIndex,
                'progress' => $this->percent($moduleCounts),
                'completed' => $moduleCompleted,
                'unlocked' => $moduleUnlocked,
                'counts' => $moduleCounts,
                'lessons' => $resultLessons,
            ];

            foreach ($moduleCounts as $key => $value) {
                $bundleCounts[$key] += $value;
            }

            if ($moduleCompleted) {
                $completedModulesCount++;
            }

            $previousModuleCompleted = $moduleCompleted;
        }

        $totalModules = count($resultModules);
        $resultCheckpoints = [];
        $allCheckpointsPassed = true;

        foreach ($checkpoints as $checkpoint) {
            $required = $checkpoint->getRequiredModulesCount($totalModules);
            $passed = (!empty($checkpoint->quiz_id) && in_array((int)$checkpoint->quiz_id, $passedCheckpointQuizIds, true));

            if (!$passed) {
                $allCheckpointsPassed = false;
            }

            $resultCheckpoints[] = [
                'id' => $checkpoint->id,
                'type' => $checkpoint->type,
                'title' => $checkpoint->title,
                'quiz_id' => $checkpoint->quiz_id,
                'required_modules' => $required,
                'unlocked' => $completedModulesCount >= $required,
                'passed' => $passed,
            ];
        }

        return [
            'bundle_id' => $bundle->id,
            'user_id' => $userId,
            'progress' => $this->percent($bundleCounts),
            'counts' => $bundleCounts,
            'modules_total' => $totalModules,
            'modules_completed' => $completedModulesCount,
            'completed' => ($totalModules > 0 && $completedModulesCount === $totalModules && $allCheckpointsPassed),
            'modules' => $resultModules,
            'checkpoints' => $resultCheckpoints,
        ];
    }

    /**
     * Học viên có được vào module này không.
     */
    public function canAccessModule(array $progress, int $webinarId): bool
    {
        foreach ($progress['modules'] as $module) {
            if ((int)$module['webinar_id'] === $webinarId) {
                return $module['unlocked'];
            }
        }

        return false;
    }

    /**
     * Checkpoint (Mid-term/Final) ứng với quiz này, null nếu quiz không phải checkpoint.
     */
    public function findCheckpointByQuiz(array $progress, int $quizId): ?array
    {
        foreach ($progress['checkpoints'] as $checkpoint) {
            if ((int)$checkpoint['quiz_id'] === $quizId) {
                return $checkpoint;
            }
        }

        return null;
    }

    /**
     * Học viên có được vào lesson này không.
     */
    public function canAccessLesson(array $progress, int $chapterId): bool
    {
        foreach ($progress['modules'] as $module) {
            foreach ($module['lessons'] as $lesson) {
                if ((int)$lesson['chapter_id'] === $chapterId) {
                    return $lesson['unlocked'];
                }
            }
        }

        return false;
    }

    /* ---------------- Load dữ liệu ---------------- */

    private function loadModules(Bundle $bundle)
    {
        $webinarIds = DB::table('bundle_webinars')
            ->where('bundle_id', $bundle->id)
            ->orderByRaw('`order` IS NULL, `order` ASC')
            ->orderBy('id')
            ->pluck('webinar_id')
            ->all();

        if (empty($webinarIds)) {
            return collect();
        }

        $webinars = Webinar::query()
            ->whereIn('id', $webinarIds)
            ->where('status', Webinar::$active)
            ->get()
            ->keyBy('id');

        // Giữ đúng thứ tự trong bundle_webinars
        return collect($webinarIds)
            ->map(function ($id) use ($webinars) {
                return $webinars->get($id);
            })
            ->filter()
            ->values();
    }

    /**
     * @return array [webinar_id => [WebinarChapter, ...]] theo thứ tự order
     */
    private function loadLessons(array $webinarIds): array
    {
        if (empty($webinarIds)) {
            return [];
        }

        $chapters = WebinarChapter::query()
            ->whereIn('webinar_id', $webinarIds)
            ->where('status', 'active')
            ->orderByRaw('`order` IS NULL, `order` ASC')
            ->orderBy('id')
            ->get();

        $result = [];
        foreach ($chapters as $chapter) {
            $result[$chapter->webinar_id][] = $chapter;
        }

        return $result;
    }

    /**
     * Lấy tất cả item đang active của các module.
     *
     * @return array [webinar_id => [['type','id','chapter_id','is_video'], ...]]
     */
    private function loadItems(array $webinarIds, array $excludedQuizIds = []): array
    {
        $items = [];

        if (empty($webinarIds)) {
            return $items;
        }

        $sources = [
            'file' => 'files',
            'session' => 'sessions',
            'text_lesson' => 'text_lessons',
            'assignment' => 'webinar_assignments',
            'quiz' => 'quizzes',
        ];

        foreach ($sources as $type => $table) {
            $columns = ['id', 'webinar_id', 'chapter_id'];
            if ($type === 'file') {
                $columns[] = 'file_type';
                $columns[] = 'duration_seconds';
            }

            $rows = DB::table($table)
                ->whereIn('webinar_id', $webinarIds)
                ->where('status', 'active')
                ->get($columns);

            foreach ($rows as $row) {
                if ($type === 'quiz' && in_array((int)$row->id, $excludedQuizIds, true)) {
                    continue;
                }

                $items[$row->webinar_id][] = [
                    'type' => $type,
                    'id' => (int)$row->id,
                    'chapter_id' => $row->chapter_id,
                    'is_video' => ($type === 'file' && in_array($row->file_type, File::$videoTypes)),
                    'seconds' => ($type === 'file' && !empty($row->duration_seconds)) ? (int)$row->duration_seconds : 0,
                ];
            }
        }

        return $items;
    }

    /**
     * @return array ['file' => [id => true], 'session' => [...], ...]
     */
    private function loadDoneItems($userId, array $items): array
    {
        $done = [
            'file' => [],
            'session' => [],
            'text_lesson' => [],
            'assignment' => [],
            'quiz' => [],
        ];

        if (empty($userId) || empty($items)) {
            return $done;
        }

        $idsByType = $done;
        foreach ($items as $moduleItems) {
            foreach ($moduleItems as $item) {
                $idsByType[$item['type']][] = $item['id'];
            }
        }

        // File, session, text lesson: bảng course_learning
        $learningColumns = ['file' => 'file_id', 'session' => 'session_id', 'text_lesson' => 'text_lesson_id'];
        $hasLearningItems = (!empty($idsByType['file']) || !empty($idsByType['session']) || !empty($idsByType['text_lesson']));

        if ($hasLearningItems) {
            $rows = DB::table('course_learning')
                ->where('user_id', $userId)
                ->where(function ($query) use ($idsByType, $learningColumns) {
                    foreach ($learningColumns as $type => $column) {
                        if (!empty($idsByType[$type])) {
                            $query->orWhereIn($column, $idsByType[$type]);
                        }
                    }
                })
                ->get(['file_id', 'session_id', 'text_lesson_id']);

            foreach ($rows as $row) {
                foreach ($learningColumns as $type => $column) {
                    if (!empty($row->{$column})) {
                        $done[$type][(int)$row->{$column}] = true;
                    }
                }
            }
        }

        // Assignment: được giáo viên chấm passed
        if (!empty($idsByType['assignment'])) {
            $passedAssignmentIds = DB::table('webinar_assignment_history')
                ->where('student_id', $userId)
                ->where('status', WebinarAssignmentHistory::$passed)
                ->whereIn('assignment_id', $idsByType['assignment'])
                ->distinct()
                ->pluck('assignment_id');

            foreach ($passedAssignmentIds as $id) {
                $done['assignment'][(int)$id] = true;
            }
        }

        // Quiz: có ít nhất một lần passed
        foreach ($this->loadPassedQuizIds($userId, $idsByType['quiz']) as $id) {
            $done['quiz'][(int)$id] = true;
        }

        return $done;
    }

    private function loadPassedQuizIds($userId, array $quizIds): array
    {
        if (empty($userId) || empty($quizIds)) {
            return [];
        }

        return DB::table('quizzes_results')
            ->where('user_id', $userId)
            ->where('status', QuizzesResult::$passed)
            ->whereIn('quiz_id', $quizIds)
            ->distinct()
            ->pluck('quiz_id')
            ->map(function ($id) {
                return (int)$id;
            })
            ->all();
    }

    /* ---------------- Tính toán ---------------- */

    private function emptyCounts(): array
    {
        return [
            'items_total' => 0,
            'items_done' => 0,
            'videos_total' => 0,
            'videos_done' => 0,
            'video_seconds' => 0,
            'quizzes_total' => 0,
            'quizzes_done' => 0,
            'assignments_total' => 0,
            'assignments_done' => 0,
        ];
    }

    private function countItems(array $items, array $done): array
    {
        $counts = $this->emptyCounts();

        foreach ($items as $item) {
            $isDone = !empty($done[$item['type']][$item['id']]);

            $counts['items_total']++;
            $counts['items_done'] += $isDone ? 1 : 0;

            if ($item['is_video']) {
                $counts['videos_total']++;
                $counts['videos_done'] += $isDone ? 1 : 0;
                $counts['video_seconds'] += $item['seconds'];
            }

            if ($item['type'] === 'quiz') {
                $counts['quizzes_total']++;
                $counts['quizzes_done'] += $isDone ? 1 : 0;
            }

            if ($item['type'] === 'assignment') {
                $counts['assignments_total']++;
                $counts['assignments_done'] += $isDone ? 1 : 0;
            }
        }

        return $counts;
    }

    /**
     * Module/lesson chưa có nội dung được coi là hoàn thành để không chặn các phần sau.
     */
    private function isCompleted(array $counts): bool
    {
        return $counts['items_done'] >= $counts['items_total'];
    }

    private function percent(array $counts): float
    {
        if ($counts['items_total'] <= 0) {
            return 0;
        }

        return round(($counts['items_done'] * 100) / $counts['items_total'], 2);
    }
}
