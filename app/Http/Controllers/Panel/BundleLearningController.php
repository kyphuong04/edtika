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
     * Trang "Khóa học của tôi" theo màn #library của design: các khóa học (bundle) học viên đã mua
     * kèm tiến độ, bên dưới là các khóa chưa mua (mờ, rê chuột để xem chi tiết / mua).
     * Lọc Skill / Duration / Content Type và phân trang làm ở phía trình duyệt.
     */
    public function myCourses(Request $request)
    {
        $user = auth()->user();
        $ownedIds = $user->getPurchasedBundlesIds();

        $owned = empty($ownedIds) ? collect() : Bundle::query()
            ->whereIn('id', $ownedIds)
            ->where('status', Bundle::$active)
            ->orderByDesc('id')
            ->get();

        $unowned = Bundle::query()
            ->where('status', Bundle::$active)
            ->whereNull('hidden_at')
            ->whereNotIn('id', $owned->pluck('id')->all())
            ->orderByDesc('id')
            ->get();

        $cards = collect();
        foreach ($owned->concat($unowned)->values() as $position => $bundle) {
            $cards->push($this->libraryCard($bundle, $owned->contains('id', $bundle->id) ? $user : null, $position));
        }

        // Khóa (module) mua lẻ không nằm trong khóa học nào đã mua - giữ lối vào học
        $ownedWebinarIds = \Illuminate\Support\Facades\DB::table('bundle_webinars')->whereIn('bundle_id', $owned->pluck('id')->all())->pluck('webinar_id')->all();
        $otherWebinars = Webinar::query()
            ->whereIn('id', \App\Models\Sale::query()
                ->where('buyer_id', $user->id)
                ->where('type', 'webinar')
                ->whereNull('refund_at')
                ->whereNotNull('webinar_id')
                ->pluck('webinar_id'))
            ->whereNotIn('id', $ownedWebinarIds)
            ->where('status', Webinar::$active)
            ->get();

        return view('design_1.panel.bundles.learning.my_courses', [
            'pageTitle' => trans('panel.my_courses'),
            'cards' => $cards,
            'otherWebinars' => $otherWebinars,
            'shortVideoMaxSeconds' => (int)config('bundle_learning.short_video_max_seconds', 900),
            'breadcrumbs' => [
                ['text' => trans('update.platform'), 'url' => '/'],
                ['text' => trans('panel.dashboard'), 'url' => '/panel'],
                ['text' => trans('panel.my_courses'), 'url' => null],
            ],
        ]);
    }

    private function libraryCard(Bundle $bundle, $user, int $position): array
    {
        $progress = $this->gate->progress($bundle, $user);
        $webinarIds = collect($progress['modules'])->pluck('webinar_id')->all();

        $skills = collect($progress['modules'])->pluck('skill')->filter()->unique()->values();
        $lessons = collect($progress['modules'])->sum(function ($module) {
            return count($module['lessons']);
        });

        $miniQuizzes = empty($webinarIds) ? 0 : Quiz::query()
            ->whereIn('webinar_id', $webinarIds)
            ->whereNotNull('file_id')
            ->where('status', 'active')
            ->count();

        $counts = $progress['counts'];
        $avgVideoSeconds = $counts['videos_total'] > 0 ? (int)round($counts['video_seconds'] / $counts['videos_total']) : 0;

        // Ảnh minh họa theo design: theo kỹ năng, còn lại xoay vòng
        $skill = $skills->count() === 1 ? $skills->first() : null;
        $covers = ['diamond', 'bars', 'target', 'book'];
        $cover = $skill === 'writing' ? 'pencil' : ($skill === 'speaking' ? 'speech' : $covers[$position % count($covers)]);

        return [
            'id' => $bundle->id,
            'slug' => $bundle->slug,
            'title' => $bundle->title,
            'summary' => trim(strip_tags((string)$bundle->summary)),
            'exam' => strtoupper($bundle->exam_type ?: 'ielts'),
            'skill' => $skill,
            'skill_label' => $skill ? trans('bundle_learning.skill_' . $skill) : ($skills->count() > 1 ? trans('bundle_learning.skills_count', ['count' => $skills->count()]) : null),
            'skills' => $skills->all(),
            'modules' => $progress['modules_total'],
            'lessons' => $lessons,
            'videos' => $counts['videos_total'],
            'mini_quizzes' => $miniQuizzes,
            'avg_video_seconds' => $avgVideoSeconds,
            'owned' => !empty($user),
            'progress' => (int)round($progress['progress']),
            'started' => $counts['items_done'] > 0,
            'completed' => $progress['completed'],
            'cover' => '/assets/design_1/img/bundles/covers/' . $cover . '.webp',
        ];
    }

    /**
     * GĐ6 - Trang học một lesson theo design: khung nội dung (Pre Lesson / Video / Bài Tập / Post Lesson)
     * + cột "Nội dung bài học" (module → lesson → Pre Lesson / Video Learning / Post Lesson).
     *
     * ?view=pre | video (&item=file_id) | exercise | post (&test=link_id)
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
        $lessonUrl = $courseUrl . '/lessons/' . $lesson['chapter_id'];
        $canBypass = $this->canBypassLocks($bundle, $user);

        if (!$lesson['unlocked'] and !$canBypass) {
            return redirect($courseUrl)->with(['toast' => [
                'title' => trans('public.request_failed'),
                'msg' => trans('bundle_learning.lesson_locked_msg'),
                'status' => 'error',
            ]]);
        }

        $webinar = Webinar::query()->findOrFail($module['webinar_id']);
        $chapter = WebinarChapter::query()->findOrFail($lesson['chapter_id']);
        $contentUnlocked = ($lesson['content_unlocked'] or $canBypass);

        // Item của lesson theo thứ tự soạn
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
                    'id' => (int)$chapterItem->item_id,
                    'title' => $model->title,
                    'done' => $doneMap[$key],
                    'is_video' => ($chapterItem->type === 'file' and $model->isVideo()),
                    'is_mini_quiz' => ($chapterItem->type === 'quiz' and !empty($model->file_id)),
                    'file_id' => ($chapterItem->type === 'quiz' and !empty($model->file_id)) ? (int)$model->file_id : null,
                    'show_at_second' => ($chapterItem->type === 'quiz') ? $model->show_at_second : null,
                    'model' => $model,
                ];
            })
            ->filter()
            ->values();

        $videos = $chapterItems->where('is_video', true)->values();
        $otherItems = $chapterItems->filter(function ($item) {
            return !$item['is_video'] and !$item['is_mini_quiz'] and $item['type'] !== 'assignment';
        })->values();
        $miniQuizzes = $chapterItems->where('is_mini_quiz', true)->values();

        // Chế độ xem hiện tại
        $view = in_array($request->get('view'), ['pre', 'video', 'exercise', 'post', 'checkpoint'], true) ? $request->get('view') : null;
        $pre = $lesson['pre'];

        // GĐ7: Mid-term / Final làm ngay trong khung player
        $currentCheckpoint = null;
        if ($view === 'checkpoint') {
            $checkpointQuizId = (int)$request->get('quiz');
            $currentCheckpoint = collect($progress['checkpoints'])->first(function ($checkpoint) use ($checkpointQuizId) {
                return !empty($checkpoint['quiz_id']) and (int)$checkpoint['quiz_id'] === $checkpointQuizId;
            });

            if (empty($currentCheckpoint) or (!$currentCheckpoint['unlocked'] and !$canBypass)) {
                return redirect($lessonUrl)->with(['toast' => [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('bundle_learning.checkpoint_locked_msg'),
                    'status' => 'error',
                ]]);
            }
        }

        if (!$contentUnlocked and in_array($view, [null, 'video', 'exercise', 'post'], true)) {
            $view = 'pre';
        }

        if (is_null($view)) {
            if (!empty($pre) and !$pre['done']) {
                $view = 'pre';
            } elseif ($videos->isNotEmpty()) {
                $view = 'video';
            } elseif (!empty($lesson['post'])) {
                $view = 'post';
            } else {
                $view = 'video';
            }
        }

        if ($view === 'pre' and empty($pre)) {
            $view = 'video';
        }

        // Video đang xem: ?item=, không có thì video đầu tiên chưa xong
        $currentVideo = null;
        if ($view === 'video' and $videos->isNotEmpty()) {
            $currentVideo = $videos->firstWhere('id', (int)$request->get('item'))
                ?? $videos->firstWhere('done', false)
                ?? $videos->first();
        }

        // Bài Tập / Post Lesson đang chọn
        $currentTest = null;
        if (in_array($view, ['exercise', 'post'], true)) {
            $tests = collect($lesson[$view]);
            $currentTest = $tests->firstWhere('link_id', (int)$request->get('test'))
                ?? $tests->firstWhere('done', false)
                ?? $tests->first();
        }

        // Pre Lesson: từ của bộ từ + flashcard của học viên (tạo khi mở Pre Lesson)
        $preWords = collect();
        $knownWordIds = [];
        $flashcardMap = [];
        if (!empty($pre) and $view === 'pre') {
            $preSet = BundleVocabularySet::query()->find($pre['set_id']);
            if (!empty($preSet)) {
                $flashcardMap = $this->activityService()->syncPreLessonFlashcards($user, $bundle, $chapter, $preSet);
                $preWords = $preSet->words()->get();
                $knownWordIds = $this->activityService()->knownWordIds($user->id, $preWords->pluck('id')->all());
            }
        }

        // Ghi chú cá nhân của video đang xem (tab Ghi chú)
        $personalNote = null;
        if (!empty($currentVideo)) {
            $personalNote = \App\Models\CoursePersonalNote::query()
                ->where('user_id', $user->id)
                ->where('course_id', $webinar->id)
                ->where('targetable_id', $currentVideo['id'])
                ->where('targetable_type', 'file')
                ->first();
        }

        $checkpointQuizIds = collect($progress['checkpoints'])->pluck('quiz_id')->filter()->all();
        $checkpointQuizzes = Quiz::query()->whereIn('id', $checkpointQuizIds)->with('webinar')->get()->keyBy('id');

        // Thông tin bài Mid-term / Final đang mở
        $checkpointInfo = null;
        if (!empty($currentCheckpoint)) {
            $checkpointQuiz = $checkpointQuizzes->get($currentCheckpoint['quiz_id']);

            if (empty($checkpointQuiz)) {
                return redirect($lessonUrl);
            }

            $inlineQuiz = $this->inlineQuizService();
            $checkpointQuestions = $inlineQuiz->questions($checkpointQuiz);
            $checkpointInfo = [
                'quiz' => $checkpointQuiz,
                'questions_count' => $checkpointQuestions->count(),
                'total_grade' => (int)$checkpointQuestions->sum('grade'),
                'attempts_left' => $inlineQuiz->attemptsLeft($checkpointQuiz, $user),
                'can_submit' => $inlineQuiz->canSubmit($checkpointQuiz, $user),
                'last_result' => $inlineQuiz->lastResult($checkpointQuiz, $user),
                'inline_url' => '/panel/courses/bundle-learning/quizzes/' . $checkpointQuiz->id,
            ];
        }

        return view('design_1.panel.bundles.learning.lesson', [
            'pageTitle' => $chapter->title,
            'bundle' => $bundle,
            'progress' => $progress,
            'module' => $module,
            'lesson' => $lesson,
            'webinar' => $webinar,
            'chapter' => $chapter,
            'chapterItems' => $chapterItems,
            'videos' => $videos,
            'otherItems' => $otherItems,
            'miniQuizzes' => $miniQuizzes,
            'view' => $view,
            'contentUnlocked' => $contentUnlocked,
            'currentVideo' => $currentVideo,
            'currentTest' => $currentTest,
            'pre' => $pre,
            'preWords' => $preWords,
            'knownWordIds' => $knownWordIds,
            'flashcardMap' => $flashcardMap,
            'personalNote' => $personalNote,
            'checkpointQuizzes' => $checkpointQuizzes,
            'currentCheckpoint' => $currentCheckpoint,
            'checkpointInfo' => $checkpointInfo,
            'courseUrl' => $courseUrl,
            'lessonUrl' => $lessonUrl,
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
     * GĐ6 - Pre Lesson: đánh dấu / bỏ đánh dấu một từ đã thuộc (đồng bộ với Từ điển & Flashcard).
     */
    public function preLessonWord(Request $request, $slug, $chapterId)
    {
        [$bundle, $lesson, $user] = $this->resolvePreLesson($slug, (int)$chapterId);

        $wordId = (int)$request->input('word_id');
        $known = filter_var($request->input('known'), FILTER_VALIDATE_BOOLEAN);

        $setWordIds = BundleVocabularyWord::query()->where('vocabulary_set_id', $lesson['pre']['set_id'])->pluck('id')->map(function ($id) {
            return (int)$id;
        })->all();

        if (!in_array($wordId, $setWordIds, true)) {
            return response()->json(['code' => 422], 422);
        }

        $flashcard = \App\Models\Flashcard::query()
            ->where('user_id', $user->id)
            ->where('source', \App\Models\Flashcard::SOURCE_BUNDLE)
            ->where('bundle_vocabulary_word_id', $wordId)
            ->first();

        if (empty($flashcard)) {
            return response()->json(['code' => 422], 422);
        }

        $wordListId = \App\Models\BundlePreLessonProgress::query()
            ->where('user_id', $user->id)
            ->where('chapter_id', $lesson['chapter_id'])
            ->value('word_list_id');

        $this->activityService()->setWordKnown($user, (int)$flashcard->id, $known, !empty($wordListId) ? (int)$wordListId : null);

        $knownCount = count($this->activityService()->knownWordIds($user->id, $setWordIds));

        return response()->json([
            'code' => 200,
            'known' => $knownCount,
            'total' => count($setWordIds),
            'required' => $lesson['pre']['required'],
        ]);
    }

    /**
     * GĐ6 - Pre Lesson: xác nhận đã thuộc đủ từ -> mở Video Learning của lesson.
     */
    public function preLessonComplete(Request $request, $slug, $chapterId)
    {
        [$bundle, $lesson, $user] = $this->resolvePreLesson($slug, (int)$chapterId);

        $setWordIds = BundleVocabularyWord::query()->where('vocabulary_set_id', $lesson['pre']['set_id'])->pluck('id')->all();
        $knownCount = count($this->activityService()->knownWordIds($user->id, $setWordIds));

        if ($knownCount < $lesson['pre']['required']) {
            return response()->json([
                'code' => 422,
                'message' => trans('bundle_learning.pre_lesson_need_more', ['count' => $lesson['pre']['required']]),
            ], 422);
        }

        $this->activityService()->completePreLesson($user, (int)$lesson['chapter_id']);

        return response()->json([
            'code' => 200,
            'redirect' => '/panel/courses/purchases/' . $bundle->slug . '/lessons/' . $lesson['chapter_id'] . '?view=video',
        ]);
    }

    /**
     * @return array [Bundle, array lesson, User]
     */
    private function resolvePreLesson(string $slug, int $chapterId): array
    {
        $user = auth()->user();

        $bundle = Bundle::query()
            ->where('slug', $slug)
            ->where('status', Bundle::$active)
            ->firstOrFail();

        $this->abortIfNoAccess($bundle, $user);

        $progress = $this->gate->progress($bundle, $user);
        [$module, $lesson] = $this->findLesson($progress, $chapterId);

        if (empty($lesson) or empty($lesson['pre']) or (!$lesson['unlocked'] and !$this->canBypassLocks($bundle, $user))) {
            abort(404);
        }

        return [$bundle, $lesson, $user];
    }

    private function activityService(): \App\Services\Bundle\LessonActivityService
    {
        return app(\App\Services\Bundle\LessonActivityService::class);
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

        // GĐ7: chờ giáo viên chấm (câu tự luận) thì không chặn video
        $waitingQuizIds = $miniQuizzes->isEmpty() ? [] : QuizzesResult::query()
            ->where('user_id', $user->id)
            ->where('status', QuizzesResult::$waiting)
            ->where('results', '!=', '')
            ->whereIn('quiz_id', $miniQuizzes->pluck('id'))
            ->pluck('quiz_id')
            ->map(function ($id) {
                return (int)$id;
            })
            ->all();
        $inlineQuiz = $this->inlineQuizService();
        $blocksVideo = (bool)config('bundle_learning.mini_quiz_blocks_video', true);

        return response()->json([
            'code' => 200,
            'file_id' => $file->id,
            'duration' => $file->duration_seconds,
            'last_position' => !empty($watch) ? (int)$watch->last_position : 0,
            'completed' => (!empty($watch) && $watch->isCompleted()),
            'mini_quizzes' => $miniQuizzes->map(function (Quiz $quiz) use ($passedQuizIds, $waitingQuizIds, $learningUrl, $inlineQuiz, $user, $blocksVideo) {
                $passed = in_array((int)$quiz->id, $passedQuizIds, true);
                $waiting = in_array((int)$quiz->id, $waitingQuizIds, true);
                $canSubmit = $inlineQuiz->canSubmit($quiz, $user);

                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'show_at_second' => (int)$quiz->show_at_second,
                    'passed' => $passed,
                    'waiting' => $waiting,
                    'url' => $learningUrl . '?type=quiz&item=' . $quiz->id,
                    // GĐ7: làm quiz ngay trên trang (không rời video)
                    'inline_url' => '/panel/courses/bundle-learning/quizzes/' . $quiz->id,
                    // Video dừng ở mốc quiz cho tới khi làm đạt (trừ khi đang chờ chấm hoặc đã hết lượt)
                    'blocking' => ($blocksVideo and !$passed and !$waiting and $canSubmit),
                ];
            })->values(),
        ]);
    }

    /**
     * GĐ7 - Nội dung một quiz để làm ngay trên trang (Mini Quiz trong video, Mid-term / Final trong khung player).
     */
    public function inlineQuiz(Request $request, $quizId)
    {
        $user = auth()->user();
        $quiz = Quiz::query()->with('webinar')->findOrFail($quizId);
        $service = $this->inlineQuizService();

        $deny = $service->denyReason($quiz, $user);
        if (!empty($deny)) {
            return response()->json(['code' => 403, 'message' => $deny], 403);
        }

        $questions = $service->questions($quiz);
        $lastResult = $service->lastResult($quiz, $user);

        return response()->json([
            'code' => 200,
            'id' => $quiz->id,
            'title' => $quiz->title,
            'time' => (int)$quiz->time,
            'pass_mark' => (int)$quiz->pass_mark,
            'total_grade' => (int)$questions->sum('grade'),
            'questions_count' => $questions->count(),
            'attempts_left' => $service->attemptsLeft($quiz, $user),
            'can_submit' => $service->canSubmit($quiz, $user),
            'passed' => $service->hasPassed($quiz, $user),
            'last_status' => optional($lastResult)->status,
            'last_grade' => !empty($lastResult) ? (int)$lastResult->user_grade : null,
            'html' => view('design_1.panel.bundles.learning.inline_quiz_form', [
                'quiz' => $quiz,
                'quizQuestions' => $questions,
            ])->render(),
        ]);
    }

    /**
     * GĐ7 - Nộp bài quiz làm trên trang. Chấm giống trang quiz của Rocket (cùng bảng quizzes_results).
     */
    public function inlineQuizSubmit(Request $request, $quizId)
    {
        $user = auth()->user();
        $quiz = Quiz::query()->with('webinar')->findOrFail($quizId);
        $service = $this->inlineQuizService();

        $deny = $service->denyReason($quiz, $user);
        if (!empty($deny)) {
            return response()->json(['code' => 403, 'message' => $deny], 403);
        }

        if (!$service->canSubmit($quiz, $user)) {
            return response()->json(['code' => 422, 'message' => trans('bundle_learning.inline_quiz_no_attempts')], 422);
        }

        $answers = $request->input('question', []);
        $submitted = $service->submit($quiz, $user, is_array($answers) ? $answers : []);
        /** @var QuizzesResult $result */
        $result = $submitted['result'];

        $messages = [
            QuizzesResult::$passed => trans('bundle_learning.inline_quiz_passed'),
            QuizzesResult::$failed => trans('bundle_learning.inline_quiz_failed'),
            QuizzesResult::$waiting => trans('bundle_learning.inline_quiz_waiting'),
        ];
        $attemptsLeft = $service->attemptsLeft($quiz, $user);

        return response()->json([
            'code' => 200,
            'status' => $result->status,
            'message' => $messages[$result->status] ?? '',
            'grade' => (int)$result->user_grade,
            'total_grade' => $service->totalGrade($quiz),
            'pass_mark' => (int)$quiz->pass_mark,
            'questions' => $submitted['questions'],
            'attempts_left' => $attemptsLeft,
            'can_retry' => ($result->status === QuizzesResult::$failed and (is_null($attemptsLeft) or $attemptsLeft > 0)),
            'result_url' => '/panel/quizzes/results/' . $result->id . '/details',
        ]);
    }

    /**
     * GĐ7 - Mở Mid-term / Final từ trang tổng quan: vào trang lesson cuối của module điều kiện, làm bài trong khung player.
     */
    public function checkpoint(Request $request, $slug, $quizId)
    {
        $this->authorize("panel_webinars_my_purchases");

        $user = auth()->user();
        $bundle = Bundle::query()
            ->where('slug', $slug)
            ->where('status', Bundle::$active)
            ->firstOrFail();

        $this->abortIfNoAccess($bundle, $user);

        $progress = $this->gate->progress($bundle, $user);
        $courseUrl = '/panel/courses/purchases/' . $bundle->slug;
        $chapterId = $this->checkpointHostChapterId($progress, (int)$quizId);

        if (empty($chapterId)) {
            return redirect($courseUrl);
        }

        return redirect($courseUrl . '/lessons/' . $chapterId . '?view=checkpoint&quiz=' . (int)$quizId);
    }

    /**
     * Lesson "chứa" khung làm Mid-term / Final: lesson cuối của module thứ required_modules.
     */
    private function checkpointHostChapterId(array $progress, int $quizId): ?int
    {
        $checkpoint = collect($progress['checkpoints'])->first(function ($checkpoint) use ($quizId) {
            return (int)$checkpoint['quiz_id'] === $quizId;
        });

        if (empty($checkpoint) or empty($progress['modules'])) {
            return null;
        }

        $moduleIndex = max(1, min(count($progress['modules']), (int)$checkpoint['required_modules'])) - 1;
        $lessons = $progress['modules'][$moduleIndex]['lessons'] ?? [];

        // Module rỗng: lấy lesson cuối của module gần nhất phía trước có lesson
        while (empty($lessons) and $moduleIndex > 0) {
            $moduleIndex--;
            $lessons = $progress['modules'][$moduleIndex]['lessons'] ?? [];
        }

        $lastLesson = end($lessons);

        return !empty($lastLesson) ? (int)$lastLesson['chapter_id'] : null;
    }

    private function inlineQuizService(): \App\Services\Bundle\InlineQuizService
    {
        return app(\App\Services\Bundle\InlineQuizService::class);
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

        $assignmentsRequired = (bool)config('bundle_learning.assignments_required', false);

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
                // GĐ6: Pre Lesson (bộ từ gắn với lesson) - xong khi đã thuộc đủ từ và xác nhận
                if (!empty($lesson['pre'])) {
                    $lines[] = $lesson['pre']['done']
                        ? ['label' => trans('bundle_learning.syllabus_pre_lesson', ['count' => $lesson['pre']['total']]), 'state' => 'done']
                        : ($locked
                            ? ['label' => trans('bundle_learning.syllabus_pre_lesson', ['count' => $lesson['pre']['total']]), 'state' => 'lock']
                            : ['label' => trans('bundle_learning.syllabus_pre_lesson', ['count' => $lesson['pre']['total']]), 'state' => 'text', 'text' => $lesson['pre']['known'] . '/' . $lesson['pre']['required']]);
                }
                $contentLocked = $locked || !$lesson['content_unlocked'];

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

                        $lines[] = ['label' => $label, 'state' => $done ? 'done' : ($contentLocked ? 'lock' : 'dash')];
                        continue;
                    }

                    $prefix = [
                        'file' => trans('bundle_learning.syllabus_file'),
                        'text_lesson' => trans('bundle_learning.syllabus_text_lesson'),
                        'session' => trans('bundle_learning.syllabus_session'),
                        'quiz' => 'Quiz',
                    ][$item['type']] ?? '';

                    $lines[] = ['label' => trim($prefix . ' · ' . $title, ' ·'), 'state' => $item['done'] ? 'done' : ($contentLocked ? 'lock' : 'dash')];
                }

                // GĐ6: Bài Tập giáo viên giao (luyện thêm) và Post Lesson (đề chấm tự động)
                if (!empty($lesson['exercise'])) {
                    $exerciseDone = count(array_filter($lesson['exercise'], function ($test) {
                        return $test['done'];
                    }));
                    $lines[] = [
                        'label' => trans('bundle_learning.syllabus_exercise', ['count' => count($lesson['exercise'])]),
                        'state' => 'text',
                        'text' => $exerciseDone . '/' . count($lesson['exercise']),
                    ];
                }

                if (!empty($lesson['post'])) {
                    $lines[] = [
                        'label' => trans('bundle_learning.syllabus_post_lesson', ['count' => count($lesson['post'])]),
                        'state' => 'text',
                        'text' => $lesson['counts']['posts_done'] . '/' . $lesson['counts']['posts_total'],
                    ];
                }

                if ($assignments['total'] > 0 && $assignmentsRequired) {
                    $lines[] = [
                        'label' => trans('bundle_learning.syllabus_post_lesson', ['count' => $assignments['total']]),
                        'state' => 'text',
                        'text' => $assignments['done'] . '/' . $assignments['total'],
                    ];
                }

                if ($lesson['completed']) {
                    $status = ['state' => 'done', 'text' => trans('bundle_learning.syllabus_status_passed')];
                } elseif ($assignmentsRequired && $assignments['pending'] > 0) {
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
            // GĐ6: "Assessment" = các bài Post Lesson đã làm trong module hiện tại
            'current_assignments_done' => $current['counts']['posts_done'] ?? 0,
            'current_assignments_total' => $current['counts']['posts_total'] ?? 0,
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
