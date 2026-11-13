<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\BundleVocabularySet;
use App\Models\BundleVocabularyWord;
use App\Models\File;
use App\Models\Quiz;
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
