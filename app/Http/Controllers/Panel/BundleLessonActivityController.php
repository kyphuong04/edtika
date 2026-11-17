<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\BundleLessonTest;
use App\Models\BundleVocabularySet;
use App\Models\IeltsTest;
use App\Models\WebinarChapter;
use Illuminate\Http\Request;

/**
 * GĐ6 - Teacher gắn hoạt động vào từng lesson trong trình soạn module:
 * - Pre Lesson: chọn một bộ từ trong Từ điển (bộ từ teacher đã tạo)
 * - Bài Tập: đề "Bài tập khóa học" giao thêm cho cả lớp (luyện thêm)
 * - Post Lesson: các đề "Bài tập khóa học" (không giới hạn số bài)
 *
 * Mọi request đều là AJAX, trả về HTML của khung hoạt động để thay tại chỗ.
 */
class BundleLessonActivityController extends Controller
{
    public function updatePreLesson(Request $request, $bundleId, $chapterId)
    {
        [$bundle, $chapter, $user] = $this->resolve($bundleId, $chapterId);

        $setId = $request->input('vocabulary_set_id');

        if (!empty($setId)) {
            $set = $this->teacherWordSetsQuery($user)->find((int)$setId);

            if (empty($set)) {
                return $this->error(trans('bundle_learning.activity_word_set_invalid'));
            }

            if ($set->status !== BundleVocabularySet::STATUS_APPROVED) {
                return $this->error(trans('bundle_learning.activity_word_set_not_approved'));
            }
        }

        WebinarChapter::query()->where('id', $chapter->id)->update([
            'pre_vocabulary_set_id' => !empty($setId) ? (int)$setId : null,
        ]);

        return $this->panel($bundle, $chapter->fresh(), $user, trans('bundle_learning.activity_saved'));
    }

    public function addTest(Request $request, $bundleId, $chapterId)
    {
        [$bundle, $chapter, $user] = $this->resolve($bundleId, $chapterId);

        $role = $request->input('role');
        if (!in_array($role, BundleLessonTest::$roles, true)) {
            return $this->error(trans('bundle_learning.activity_test_invalid'));
        }

        $test = $this->teacherLessonTestsQuery($user)->find((int)$request->input('ielts_test_id'));
        if (empty($test)) {
            return $this->error(trans('bundle_learning.activity_test_invalid'));
        }

        $exists = BundleLessonTest::query()
            ->where('chapter_id', $chapter->id)
            ->where('ielts_test_id', $test->id)
            ->where('role', $role)
            ->exists();

        if ($exists) {
            return $this->error(trans('bundle_learning.activity_test_exists'));
        }

        BundleLessonTest::query()->create([
            'chapter_id' => $chapter->id,
            'ielts_test_id' => $test->id,
            'role' => $role,
            'sort_order' => ((int)BundleLessonTest::query()->where('chapter_id', $chapter->id)->where('role', $role)->max('sort_order')) + 1,
            'created_by' => $user->id,
            'created_at' => time(),
        ]);

        return $this->panel($bundle, $chapter, $user, trans('bundle_learning.activity_saved'));
    }

    public function removeTest(Request $request, $bundleId, $chapterId, $linkId)
    {
        [$bundle, $chapter, $user] = $this->resolve($bundleId, $chapterId);

        BundleLessonTest::query()
            ->where('chapter_id', $chapter->id)
            ->where('id', (int)$linkId)
            ->delete();

        return $this->panel($bundle, $chapter, $user, trans('bundle_learning.activity_saved'));
    }

    public function moveTest(Request $request, $bundleId, $chapterId, $linkId)
    {
        [$bundle, $chapter, $user] = $this->resolve($bundleId, $chapterId);

        $link = BundleLessonTest::query()->where('chapter_id', $chapter->id)->findOrFail((int)$linkId);
        $siblings = BundleLessonTest::query()
            ->where('chapter_id', $chapter->id)
            ->where('role', $link->role)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->values();

        $index = $siblings->search(function ($item) use ($link) {
            return $item->id === $link->id;
        });
        $target = $index + ((int)$request->input('dir') < 0 ? -1 : 1);

        if ($index !== false && $target >= 0 && $target < $siblings->count()) {
            $ordered = $siblings->all();
            [$ordered[$index], $ordered[$target]] = [$ordered[$target], $ordered[$index]];

            foreach ($ordered as $position => $item) {
                BundleLessonTest::query()->where('id', $item->id)->update(['sort_order' => $position + 1]);
            }
        }

        return $this->panel($bundle, $chapter, $user, trans('bundle_learning.activity_saved'));
    }

    /* ---------------- Dữ liệu cho trình soạn module ---------------- */

    /**
     * Dữ liệu dùng chung cho mọi lesson trong trình soạn module: bộ từ + đề "Bài tập khóa học" của teacher.
     */
    public static function editorOptions($user): array
    {
        $controller = new static();

        return [
            'wordSets' => $controller->teacherWordSetsQuery($user)
                ->orderByDesc('id')
                ->get(['id', 'name', 'status', 'words_count']),
            'tests' => $controller->teacherLessonTestsQuery($user)
                ->orderByDesc('id')
                ->get(['id', 'title', 'status', 'is_active', 'has_listening', 'has_reading', 'show_answers_immediately']),
        ];
    }

    /* ---------------- Helpers ---------------- */

    private function teacherWordSetsQuery($user)
    {
        return BundleVocabularySet::query()->where('created_by', $user->id);
    }

    private function teacherLessonTestsQuery($user)
    {
        return IeltsTest::query()->where('type', 'lesson')->where('created_by', $user->id);
    }

    /**
     * @return array [Bundle, WebinarChapter, User]
     */
    private function resolve($bundleId, $chapterId): array
    {
        $this->authorize('panel_webinars_create');

        $user = auth()->user();

        $bundle = Bundle::query()
            ->where('id', $bundleId)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)->orWhere('teacher_id', $user->id);
            })
            ->firstOrFail();

        $chapter = WebinarChapter::query()->findOrFail($chapterId);

        // Lesson phải thuộc một module của khóa học này
        $belongs = $bundle->bundleWebinars()->where('webinar_id', $chapter->webinar_id)->exists();
        if (!$belongs) {
            abort(404);
        }

        return [$bundle, $chapter, $user];
    }

    private function panel(Bundle $bundle, WebinarChapter $chapter, $user, ?string $message = null)
    {
        $html = (string)view('design_1.panel.bundles.module_editor.lesson_activities', [
            'bundle' => $bundle,
            'chapter' => $chapter,
            'lessonActivityOptions' => self::editorOptions($user),
        ]);

        return response()->json([
            'code' => 200,
            'html' => $html,
            'message' => $message,
        ]);
    }

    private function error(string $message)
    {
        return response()->json([
            'code' => 422,
            'message' => $message,
        ], 422);
    }
}
