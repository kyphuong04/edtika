<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\BundleVocabularySet;
use App\Models\BundleVocabularyWord;
use App\Models\File;
use App\Models\Quiz;
use App\Models\Session;
use App\Models\TextLesson;
use App\Models\WebinarAssignment;
use App\Models\WebinarAssignmentHistory;
use App\Models\QuizzesResult;
use App\Models\VideoWatchProgress;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use App\Services\Bundle\BundleLearningGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * GĐ3 - Học viên học khóa học (bundle) theo design mới.
 * - course(): trang "Khóa học của tôi" của một bundle
 * - lesson(): trang tổng quan một lesson
 * - fileState() / videoProgress(): API cho player (ghi nhận xem video, Mini Quiz)
 */
class BundleLearningController extends Controller
{
    /** @var BundleLearningGate */
    private $gate;

    public function __construct(BundleLearningGate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * Trang khóa học của học viên. Gọi từ MyPurchasedCoursesController@showCourse.
     */
    public function course(Request $request, Bundle $bundle)
    {
        $user = auth()->user();

        $this->abortIfNoAccess($bundle, $user);

        $progress = $this->gate->progress($bundle, $user);

        // Đường dẫn tới Mid-term/Final (mở learning page của module chứa quiz)
        $checkpointQuizIds = collect($progress['checkpoints'])->pluck('quiz_id')->filter()->all();
        $checkpointQuizzes = Quiz::query()->whereIn('id', $checkpointQuizIds)->with('webinar')->get()->keyBy('id');

        // Các khóa khác học viên đã mua (đổi khóa nhanh)
        $purchasedBundles = Bundle::query()
            ->whereIn('id', $user->getPurchasedBundlesIds())
            ->where('status', Bundle::$active)
            ->get();

        return view('design_1.panel.bundles.learning.course', [
            'pageTitle' => $bundle->title,
            'bundle' => $bundle,
            'progress' => $progress,
            'syllabus' => $this->buildSyllabus($bundle, $user, $progress),
            'checkpointQuizzes' => $checkpointQuizzes,
            'purchasedBundles' => $purchasedBundles,
            'shortVideoMaxSeconds' => (int)config('bundle_learning.short_video_max_seconds', 900),
            'breadcrumbs' => [
                ['text' => trans('update.platform'), 'url' => '/'],
                ['text' => trans('panel.dashboard'), 'url' => '/panel'],
                ['text' => trans('panel.my_courses'), 'url' => '/panel/courses/purchases'],
                ['text' => $bundle->title, 'url' => null],
            ],
        ]);
    }

    /**
     * Trang tổng quan một lesson (chapter) của bundle.
     */
    public function lesson(Request $request, $slug, $chapterId)
    {
        $this->authorize("panel_webinars_my_purchases");

        $user = auth()->user();

        $bundle = Bundle::query()
            ->where('slug', $slug)
            ->where('status', Bundle::$active)
            ->firstOrFail();

        $this->abortIfNoAccess($bundle, $user);

        $progress = $this->gate->progress($bundle, $user);
        [$module, $lesson] = $this->findLesson($progress, (int)$chapterId);

        if (empty($module) or empty($lesson)) {
            abort(404);
        }

        $courseUrl = '/panel/courses/purchases/' . $bundle->slug;

        if (!$lesson['unlocked'] and !$this->canBypassLocks($bundle, $user)) {
            return redirect($courseUrl)->with(['toast' => [
                'title' => trans('public.request_failed'),
                'msg' => trans('bundle_learning.lesson_locked_msg'),
                'status' => 'error',
            ]]);
        }

        $webinar = Webinar::query()->findOrFail($module['webinar_id']);
        $chapter = WebinarChapter::query()->findOrFail($lesson['chapter_id']);

        // Danh sách item của lesson theo thứ tự soạn
        $doneMap = [];
        foreach ($lesson['items'] as $item) {
            $doneMap[$item['type'] . '_' . $item['id']] = $item['done'];
        }

        $chapterItems = WebinarChapterItem::query()
            ->where('chapter_id', $chapter->id)
            ->orderBy('order')
            ->with(['file', 'session', 'textLesson', 'quiz', 'assignment'])
            ->get()
            ->map(function ($chapterItem) use ($doneMap) {
                $relation = [
                    'file' => 'file',
                    'session' => 'session',
                    'text_lesson' => 'textLesson',
                    'quiz' => 'quiz',
                    'assignment' => 'assignment',
                ][$chapterItem->type] ?? null;

                $model = !empty($relation) ? $chapterItem->{$relation} : null;
                $key = $chapterItem->type . '_' . $chapterItem->item_id;

                // Bỏ item không còn active (không có trong danh sách tính tiến độ)
                if (empty($model) or !array_key_exists($key, $doneMap)) {
                    return null;
                }

                return [
                    'type' => $chapterItem->type,
                    'id' => $chapterItem->item_id,
                    'title' => $model->title,
                    'done' => $doneMap[$key],
                    'is_video' => ($chapterItem->type === 'file' and $model->isVideo()),
                    'is_mini_quiz' => ($chapterItem->type === 'quiz' and !empty($model->file_id)),
                ];
            })
            ->filter()
            ->values();

        // Pre Lesson: Word List đã duyệt gắn với lesson này
        $wordSets = BundleVocabularySet::query()
            ->where('bundle_id', $bundle->id)
            ->where('chapter_id', $chapter->id)
            ->where('status', BundleVocabularySet::STATUS_APPROVED)
            ->get();

        $words = $wordSets->isEmpty() ? collect() : BundleVocabularyWord::query()
            ->whereIn('vocabulary_set_id', $wordSets->pluck('id'))
            ->orderBy('vocabulary_set_id')
            ->orderBy('sort_order')
            ->limit(200)
            ->get();

        return view('design_1.panel.bundles.learning.lesson', [
            'pageTitle' => $chapter->title,
            'bundle' => $bundle,
            'progress' => $progress,
            'module' => $module,
            'lesson' => $lesson,
            'webinar' => $webinar,
            'chapter' => $chapter,
            'chapterItems' => $chapterItems,
            'words' => $words,
            'courseUrl' => $courseUrl,
            'learningUrl' => '/panel/courses/purchases/learning/' . $webinar->slug,
            'breadcrumbs' => [
                ['text' => trans('update.platform'), 'url' => '/'],
                ['text' => trans('panel.my_courses'), 'url' => '/panel/courses/purchases'],
                ['text' => $bundle->title, 'url' => $courseUrl],
                ['text' => $chapter->title, 'url' => null],
            ],
        ]);
    }

    /**
     * Trạng thái một video cho player: vị trí xem gần nhất, đã xong chưa, Mini Quiz.
     */
    public function fileState(Request $request, $fileId)
    {
        $user = auth()->user();
        $file = $this->findAccessibleFile($user, (int)$fileId);

        if (empty($file)) {
            return response()->json(['code' => 403], 403);
        }

        $watch = VideoWatchProgress::query()
            ->where('user_id', $user->id)
            ->where('file_id', $file->id)
            ->first();

        $miniQuizzes = Quiz::query()
            ->where('file_id', $file->id)
            ->where('status', Quiz::ACTIVE)
            ->orderBy('show_at_second')
            ->get();

        $passedQuizIds = $miniQuizzes->isEmpty() ? [] : QuizzesResult::query()
            ->where('user_id', $user->id)
            ->where('status', QuizzesResult::$passed)
            ->whereIn('quiz_id', $miniQuizzes->pluck('id'))
            ->pluck('quiz_id')
            ->map(function ($id) {
                return (int)$id;
            })
            ->all();

        $learningUrl = '/panel/courses/purchases/learning/' . $file->webinar->slug;

        return response()->json([
            'code' => 200,
            'file_id' => $file->id,
            'duration' => $file->duration_seconds,
            'last_position' => !empty($watch) ? (int)$watch->last_position : 0,
            'completed' => (!empty($watch) && $watch->isCompleted()),
            'mini_quizzes' => $miniQuizzes->map(function (Quiz $quiz) use ($passedQuizIds, $learningUrl) {
                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'show_at_second' => (int)$quiz->show_at_second,
                    'passed' => in_array((int)$quiz->id, $passedQuizIds, true),
                    'url' => $learningUrl . '?type=quiz&item=' . $quiz->id,
                ];
            })->values(),
        ]);
    }

    /**
     * Player gửi vị trí xem định kỳ. Xem đủ ngưỡng thì video được tính là đã học.
     */
    public function videoProgress(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'file_id' => 'required|integer',
            'position' => 'required|numeric|min:0',
            'duration' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 422, 'errors' => $validator->errors()], 422);
        }

        $file = $this->findAccessibleFile($user, (int)$request->get('file_id'));

        if (empty($file) or !$file->isVideo()) {
            return response()->json(['code' => 403], 403);
        }

        $duration = $request->get('duration');
        $duration = (is_numeric($duration) and $duration > 0) ? (int)round($duration) : null;

        $watch = VideoWatchProgress::record($user->id, $file, (int)floor($request->get('position')), $duration);

        return response()->json([
            'code' => 200,
            'completed' => $watch->isCompleted(),
            'max_position' => (int)$watch->max_position,
        ]);
    }

    /* ---------------- Helpers ---------------- */

    /**
     * Dữ liệu "Tổng quan khóa học" theo design: module → lesson → các dòng (Pre Lesson, Video · Mini Quiz,
     * bài đọc / quiz, Post Lesson) + tình trạng, cùng thông tin module hiện tại cho khối "Thông tin".
     */
    private function buildSyllabus(Bundle $bundle, $user, array $progress): array
    {
        $courseUrl = '/panel/courses/purchases/' . $bundle->slug;
        $modules = $progress['modules'];

        $chapterIds = [];
        $idsByType = ['file' => [], 'quiz' => [], 'assignment' => [], 'text_lesson' => [], 'session' => []];
        foreach ($modules as $module) {
            foreach ($module['lessons'] as $lesson) {
                $chapterIds[] = $lesson['chapter_id'];
                foreach ($lesson['items'] as $item) {
                    $idsByType[$item['type']][] = $item['id'];
                }
            }
        }

        // Thứ tự item theo trình soạn module
        $itemOrder = [];
        if (!empty($chapterIds)) {
            WebinarChapterItem::query()
                ->whereIn('chapter_id', $chapterIds)
                ->orderBy('order')
                ->get(['type', 'item_id', 'order'])
                ->each(function ($chapterItem, $index) use (&$itemOrder) {
                    $itemOrder[$chapterItem->type . '_' . $chapterItem->item_id] = $index;
                });
        }

        $titles = [];
        $miniQuizFileIds = [];
        $models = [
            'file' => File::class,
            'quiz' => Quiz::class,
            'assignment' => WebinarAssignment::class,
            'text_lesson' => TextLesson::class,
            'session' => Session::class,
        ];
        foreach ($models as $type => $class) {
            if (empty($idsByType[$type])) {
                continue;
            }
            foreach ($class::query()->whereIn('id', array_unique($idsByType[$type]))->get() as $model) {
                $titles[$type . '_' . $model->id] = $model->title;
                if ($type === 'quiz' && !empty($model->file_id)) {
                    $miniQuizFileIds[$model->id] = (int)$model->file_id;
                }
            }
        }

        // Bài Post Lesson đã nộp, đang chờ giáo viên chấm
        $pendingAssignmentIds = empty($idsByType['assignment']) ? [] : WebinarAssignmentHistory::query()
            ->where('student_id', $user->id)
            ->where('status', WebinarAssignmentHistory::$pending)
            ->whereIn('assignment_id', $idsByType['assignment'])
            ->pluck('assignment_id')
            ->map(function ($id) {
                return (int)$id;
            })
            ->all();

        // Pre Lesson: số từ trong Word List (đã duyệt) gắn với từng lesson
        $wordCounts = [];
        if (!empty($chapterIds)) {
            $sets = BundleVocabularySet::query()
                ->where('bundle_id', $bundle->id)
                ->whereIn('chapter_id', $chapterIds)
                ->where('status', BundleVocabularySet::STATUS_APPROVED)
                ->get(['id', 'chapter_id']);

            if ($sets->isNotEmpty()) {
                $wordsBySet = BundleVocabularyWord::query()
                    ->whereIn('vocabulary_set_id', $sets->pluck('id'))
                    ->selectRaw('vocabulary_set_id, count(*) as total')
                    ->groupBy('vocabulary_set_id')
                    ->pluck('total', 'vocabulary_set_id');

                foreach ($sets as $set) {
                    $wordCounts[$set->chapter_id] = ($wordCounts[$set->chapter_id] ?? 0) + (int)($wordsBySet[$set->id] ?? 0);
                }
            }
        }

        // Module hiện tại: module đầu tiên đã mở mà chưa hoàn thành
        $currentIndex = null;
        foreach ($modules as $index => $module) {
            if ($module['unlocked'] && !$module['completed']) {
                $currentIndex = $index;
                break;
            }
        }
        if (is_null($currentIndex)) {
            $currentIndex = max(0, count($modules) - 1);
        }

        $resultModules = [];
        $lessonsTotal = 0;
        $nextLessonUrl = null;

        foreach ($modules as $moduleIndex => $module) {
            $resultLessons = [];

            foreach ($module['lessons'] as $lessonIndex => $lesson) {
                $lessonsTotal++;
                $locked = !$lesson['unlocked'];
                $items = $lesson['items'];
                usort($items, function ($a, $b) use ($itemOrder) {
                    return ($itemOrder[$a['type'] . '_' . $a['id']] ?? PHP_INT_MAX) <=> ($itemOrder[$b['type'] . '_' . $b['id']] ?? PHP_INT_MAX);
                });

                // Mini Quiz gom vào dòng video của nó
                $miniByFile = [];
                foreach ($items as $item) {
                    if ($item['type'] === 'quiz' && isset($miniQuizFileIds[$item['id']])) {
                        $miniByFile[$miniQuizFileIds[$item['id']]][] = $item;
                    }
                }

                $lines = [];
                if (!empty($wordCounts[$lesson['chapter_id']])) {
                    $lines[] = [
                        'label' => trans('bundle_learning.syllabus_pre_lesson', ['count' => $wordCounts[$lesson['chapter_id']]]),
                        'state' => $locked ? 'lock' : 'none',
                    ];
                }

                $videoNumber = 0;
                $miniNumber = 0;
                $assignments = ['total' => 0, 'done' => 0, 'pending' => 0];

                foreach ($items as $item) {
                    $key = $item['type'] . '_' . $item['id'];
                    $title = $titles[$key] ?? '';

                    if ($item['type'] === 'quiz' && isset($miniQuizFileIds[$item['id']])) {
                        continue;
                    }

                    if ($item['type'] === 'assignment') {
                        $assignments['total']++;
                        $assignments['done'] += $item['done'] ? 1 : 0;
                        $assignments['pending'] += (!$item['done'] && in_array($item['id'], $pendingAssignmentIds, true)) ? 1 : 0;
                        continue;
                    }

                    if ($item['type'] === 'file' && $item['is_video']) {
                        $videoNumber++;
                        $label = 'Video ' . str_pad($videoNumber, 2, '0', STR_PAD_LEFT) . ($title !== '' ? ' · ' . $title : '');
                        $done = $item['done'];

                        foreach ($miniByFile[$item['id']] ?? [] as $miniQuiz) {
                            $miniNumber++;
                            $label .= ' · Mini Quiz ' . str_pad($miniNumber, 2, '0', STR_PAD_LEFT);
                            $done = $done && $miniQuiz['done'];
                        }

                        $lines[] = ['label' => $label, 'state' => $done ? 'done' : ($locked ? 'lock' : 'dash')];
                        continue;
                    }

                    $prefix = [
                        'file' => trans('bundle_learning.syllabus_file'),
                        'text_lesson' => trans('bundle_learning.syllabus_text_lesson'),
                        'session' => trans('bundle_learning.syllabus_session'),
                        'quiz' => 'Quiz',
                    ][$item['type']] ?? '';

                    $lines[] = ['label' => trim($prefix . ' · ' . $title, ' ·'), 'state' => $item['done'] ? 'done' : ($locked ? 'lock' : 'dash')];
                }

                if ($assignments['total'] > 0) {
                    $lines[] = [
                        'label' => trans('bundle_learning.syllabus_post_lesson', ['count' => $assignments['total']]),
                        'state' => 'text',
                        'text' => $assignments['done'] . '/' . $assignments['total'],
                    ];
                }

                if ($lesson['completed']) {
                    $status = ['state' => 'done', 'text' => trans('bundle_learning.syllabus_status_passed')];
                } elseif ($assignments['pending'] > 0) {
                    $status = ['state' => 'text', 'text' => trans('bundle_learning.syllabus_status_waiting')];
                } else {
                    $status = ['state' => 'text', 'text' => trans('bundle_learning.syllabus_status_incomplete')];
                }

                $url = $courseUrl . '/lessons/' . $lesson['chapter_id'];
                if (is_null($nextLessonUrl) && $lesson['unlocked'] && !$lesson['completed']) {
                    $nextLessonUrl = $url;
                }

                $resultLessons[] = [
                    'number' => $lessonIndex + 1,
                    'title' => $lesson['title'],
                    'videos' => $lesson['counts']['videos_total'],
                    'completed' => $lesson['completed'],
                    'unlocked' => $lesson['unlocked'],
                    'url' => $url,
                    'lines' => $lines,
                    'status' => $status,
                ];
            }

            // Lesson đang học được mở sẵn: lesson đầu tiên đã mở mà chưa xong trong module hiện tại
            $openLessonIndex = null;
            foreach ($resultLessons as $index => $resultLesson) {
                if ($resultLesson['unlocked'] && !$resultLesson['completed']) {
                    $openLessonIndex = $index;
                    break;
                }
            }

            $resultModules[] = [
                'number' => $moduleIndex + 1,
                'title' => $module['title'],
                'unlocked' => $module['unlocked'],
                'open' => $moduleIndex === $currentIndex,
                'open_lesson' => $openLessonIndex ?? 0,
                'lessons' => $resultLessons,
            ];
        }

        $current = $modules[$currentIndex] ?? null;
        $counts = $progress['counts'];

        if ($progress['completed']) {
            $statusText = trans('bundle_learning.status_completed');
        } elseif ($counts['items_done'] > 0) {
            $statusText = trans('bundle_learning.status_in_progress');
        } else {
            $statusText = trans('bundle_learning.status_not_started');
        }

        return [
            'modules' => $resultModules,
            'lessons_total' => $lessonsTotal,
            'videos_total' => $counts['videos_total'],
            'current_module' => is_null($current) ? 0 : $currentIndex + 1,
            'current_videos_done' => $current['counts']['videos_done'] ?? 0,
            'current_videos_total' => $current['counts']['videos_total'] ?? 0,
            'current_assignments_done' => $current['counts']['assignments_done'] ?? 0,
            'current_assignments_total' => $current['counts']['assignments_total'] ?? 0,
            'status_text' => $statusText,
            'started' => $counts['items_done'] > 0,
            'next_url' => $nextLessonUrl,
        ];
    }

    private function abortIfNoAccess(Bundle $bundle, $user): void
    {
        if (empty($user) or (!$bundle->checkUserHasBought($user) and !$this->canBypassLocks($bundle, $user))) {
            abort(403);
        }
    }

    private function canBypassLocks(Bundle $bundle, $user): bool
    {
        return !empty($user) and ($user->isAdmin() or $bundle->isOwner($user->id));
    }

    private function findLesson(array $progress, int $chapterId): array
    {
        foreach ($progress['modules'] as $module) {
            foreach ($module['lessons'] as $lesson) {
                if ((int)$lesson['chapter_id'] === $chapterId) {
                    return [$module, $lesson];
                }
            }
        }

        return [null, null];
    }

    /**
     * Video học viên được xem: đã mua module (trực tiếp hoặc qua bundle) và lesson không bị khóa.
     */
    private function findAccessibleFile($user, int $fileId): ?File
    {
        if (empty($user)) {
            return null;
        }

        $file = File::query()
            ->where('id', $fileId)
            ->where('status', File::$Active)
            ->with('webinar')
            ->first();

        if (empty($file) or empty($file->webinar) or !$file->webinar->checkUserHasBought($user)) {
            return null;
        }

        $block = $this->gate->check($file->webinar, $user, !empty($file->chapter_id) ? (int)$file->chapter_id : null);

        return is_null($block) ? $file : null;
    }
}
