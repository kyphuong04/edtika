<?php

namespace App\Services\Bundle;

use App\Models\Bundle;
use App\Models\BundleLessonTest;
use App\Models\BundlePreLessonProgress;
use App\Models\BundleVocabularySet;
use App\Models\BundleVocabularyWord;
use App\Models\Flashcard;
use App\Models\IeltsTest;
use App\Models\UserWordProgress;
use App\Models\WebinarChapter;
use App\Models\WordList;
use Illuminate\Support\Facades\DB;

/**
 * GĐ6 - Hoạt động theo design trong mỗi lesson:
 * - Pre Lesson: bộ từ (Từ điển của teacher) → học viên đánh dấu đã thuộc >= X% số từ thì mở video.
 *   "Đã thuộc" = user_word_progress.is_learned của flashcard tương ứng, nên đồng bộ với Từ điển & Flashcard.
 * - Bài Tập (exercise) / Post Lesson (post): đề IELTS loại 'lesson', chấm tự động. Xong = có lượt làm đã nộp.
 *
 * Mọi dữ liệu lấy theo lô (vài query cố định) cho toàn bộ lesson của khóa.
 */
class LessonActivityService
{
    /**
     * @param array $chapters [chapter_id => pre_vocabulary_set_id|null]
     * @param int|null $userId
     * @return array [chapter_id => ['pre' => array|null, 'exercise' => array, 'post' => array]]
     */
    public function forChapters(array $chapters, $userId): array
    {
        $result = [];
        foreach ($chapters as $chapterId => $setId) {
            $result[$chapterId] = ['pre' => null, 'exercise' => [], 'post' => []];
        }

        if (empty($chapters)) {
            return $result;
        }

        $this->fillPreLessons($result, $chapters, $userId);
        $this->fillTests($result, array_keys($chapters), $userId);

        return $result;
    }

    public static function requiredWords(int $total): int
    {
        if ($total <= 0) {
            return 0;
        }

        $percent = max(1, min(100, (int)config('bundle_learning.pre_lesson_pass_percent', 80)));

        return (int)min($total, max(1, ceil($total * $percent / 100)));
    }

    /* ---------------- Pre Lesson ---------------- */

    private function fillPreLessons(array &$result, array $chapters, $userId): void
    {
        $setIds = array_values(array_unique(array_filter($chapters)));
        if (empty($setIds)) {
            return;
        }

        // Chỉ bộ từ đã được duyệt mới dùng làm Pre Lesson
        $approvedSetIds = BundleVocabularySet::query()
            ->whereIn('id', $setIds)
            ->where('status', BundleVocabularySet::STATUS_APPROVED)
            ->pluck('id')
            ->map(function ($id) {
                return (int)$id;
            })
            ->all();

        if (empty($approvedSetIds)) {
            return;
        }

        $wordsBySet = [];
        BundleVocabularyWord::query()
            ->whereIn('vocabulary_set_id', $approvedSetIds)
            ->get(['id', 'vocabulary_set_id'])
            ->each(function ($word) use (&$wordsBySet) {
                $wordsBySet[(int)$word->vocabulary_set_id][] = (int)$word->id;
            });

        $knownWordIds = [];
        $completedChapterIds = [];

        if (!empty($userId)) {
            $allWordIds = array_merge([], ...array_values($wordsBySet ?: [[]]));
            $knownWordIds = $this->knownWordIds($userId, $allWordIds);

            $completedChapterIds = BundlePreLessonProgress::query()
                ->where('user_id', $userId)
                ->whereIn('chapter_id', array_keys($chapters))
                ->whereNotNull('completed_at')
                ->pluck('chapter_id')
                ->map(function ($id) {
                    return (int)$id;
                })
                ->all();
        }

        foreach ($chapters as $chapterId => $setId) {
            $setId = (int)$setId;
            $words = $wordsBySet[$setId] ?? [];

            if (empty($setId) || !in_array($setId, $approvedSetIds, true) || empty($words)) {
                continue;
            }

            $known = count(array_intersect($words, $knownWordIds));
            $required = self::requiredWords(count($words));

            $result[$chapterId]['pre'] = [
                'set_id' => $setId,
                'total' => count($words),
                'known' => $known,
                'required' => $required,
                // Đã xác nhận hoàn thành thì giữ nguyên, kể cả khi sau đó bỏ đánh dấu từ trong Flashcard
                'done' => in_array((int)$chapterId, $completedChapterIds, true),
            ];
        }
    }

    /**
     * @return int[] id các từ (bundle_vocabulary_words) học viên đã thuộc
     */
    public function knownWordIds(int $userId, array $wordIds): array
    {
        if (empty($wordIds)) {
            return [];
        }

        return DB::table('user_flashcards')
            ->join('user_word_progress', function ($join) use ($userId) {
                $join->on('user_word_progress.flashcard_id', '=', 'user_flashcards.id')
                    ->where('user_word_progress.user_id', '=', $userId)
                    ->where('user_word_progress.is_learned', '=', 1);
            })
            ->where('user_flashcards.user_id', $userId)
            ->where('user_flashcards.source', Flashcard::SOURCE_BUNDLE)
            ->whereIn('user_flashcards.bundle_vocabulary_word_id', $wordIds)
            ->distinct()
            ->pluck('user_flashcards.bundle_vocabulary_word_id')
            ->map(function ($id) {
                return (int)$id;
            })
            ->all();
    }

    /**
     * Tạo flashcard cho các từ của Pre Lesson và đưa vào Word List "Khóa học: ..." của học viên
     * (hiện ở tab My Word List trong Từ điển & Flashcard).
     *
     * @return array [word_id => flashcard_id]
     */
    public function syncPreLessonFlashcards($user, Bundle $bundle, WebinarChapter $chapter, BundleVocabularySet $set): array
    {
        $words = $set->words()->get();
        $wordList = $this->courseWordList($user, $bundle);

        $map = [];
        $syncData = [];
        $order = (int)DB::table('flashcard_word_list')->where('word_list_id', $wordList->id)->max('order');

        foreach ($words as $word) {
            $flashcard = Flashcard::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'source' => Flashcard::SOURCE_BUNDLE,
                    'bundle_vocabulary_word_id' => $word->id,
                ],
                [
                    'vocabulary_set_id' => $word->vocabulary_set_id,
                    'word' => $word->word,
                    'part_of_speech' => $word->part_of_speech,
                    'pronunciation' => $word->pronunciation,
                    'definition' => $word->definition ?: ($word->translation_vi ?: $word->word),
                    'example' => $word->example,
                    'translation' => $word->translation_vi,
                ]
            );

            $map[(int)$word->id] = (int)$flashcard->id;
            $syncData[$flashcard->id] = ['order' => ++$order];
        }

        if (!empty($syncData)) {
            // Không gỡ từ học viên đã có trong Word List (chỉ thêm từ mới)
            $existing = DB::table('flashcard_word_list')
                ->where('word_list_id', $wordList->id)
                ->whereIn('flashcard_id', array_keys($syncData))
                ->pluck('flashcard_id')
                ->all();

            $syncData = array_diff_key($syncData, array_flip($existing));

            if (!empty($syncData)) {
                $wordList->flashcards()->syncWithoutDetaching($syncData);
                $wordList->updateWordCount();
            }
        }

        BundlePreLessonProgress::query()->updateOrCreate(
            ['user_id' => $user->id, 'chapter_id' => $chapter->id],
            [
                'bundle_id' => $bundle->id,
                'vocabulary_set_id' => $set->id,
                'word_list_id' => $wordList->id,
                'updated_at' => time(),
            ]
        );

        return $map;
    }

    /**
     * Đánh dấu / bỏ đánh dấu một từ đã thuộc (ghi vào tiến độ flashcard của Từ điển).
     */
    public function setWordKnown($user, int $flashcardId, bool $known, ?int $wordListId = null): void
    {
        if ($known) {
            UserWordProgress::markAsLearned($user->id, $flashcardId, $wordListId);
            return;
        }

        UserWordProgress::query()
            ->where('user_id', $user->id)
            ->where('flashcard_id', $flashcardId)
            ->update(['is_learned' => false, 'learned_at' => null]);
    }

    public function completePreLesson($user, int $chapterId): void
    {
        BundlePreLessonProgress::query()
            ->where('user_id', $user->id)
            ->where('chapter_id', $chapterId)
            ->whereNull('completed_at')
            ->update(['completed_at' => time(), 'updated_at' => time()]);
    }

    private function courseWordList($user, Bundle $bundle): WordList
    {
        $existingId = BundlePreLessonProgress::query()
            ->where('user_id', $user->id)
            ->where('bundle_id', $bundle->id)
            ->whereNotNull('word_list_id')
            ->orderByDesc('id')
            ->value('word_list_id');

        if (!empty($existingId)) {
            $wordList = WordList::query()->where('user_id', $user->id)->find($existingId);
            if (!empty($wordList)) {
                return $wordList;
            }
        }

        // category 'folder' = Word List do học viên quản lý, hiện trong tab My Word List
        return WordList::query()->create([
            'user_id' => $user->id,
            'category' => 'folder',
            'name' => mb_substr(trans('bundle_learning.pre_lesson_word_list_name', ['title' => $bundle->title]), 0, 100),
            'description' => trans('bundle_learning.pre_lesson_word_list_desc'),
            'is_public' => false,
            'word_count' => 0,
        ]);
    }

    /* ---------------- Bài Tập / Post Lesson ---------------- */

    private function fillTests(array &$result, array $chapterIds, $userId): void
    {
        $links = BundleLessonTest::query()
            ->whereIn('chapter_id', $chapterIds)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($links->isEmpty()) {
            return;
        }

        // Học viên chỉ thấy đề đã được duyệt và đang bật
        $tests = IeltsTest::query()
            ->whereIn('id', $links->pluck('ielts_test_id')->unique()->all())
            ->where('type', 'lesson')
            ->where('status', 'published')
            ->where('is_active', 1)
            ->get(['id', 'title', 'total_duration', 'has_listening', 'has_reading'])
            ->keyBy('id');

        $latestAttempts = [];
        if (!empty($userId) && $tests->isNotEmpty()) {
            // Lượt đã nộp gần nhất của mỗi đề (không tính bài xem trước của giáo viên)
            DB::table('ielts_test_attempts')
                ->where('user_id', $userId)
                ->whereIn('test_id', $tests->keys()->all())
                ->where('status', 'completed')
                ->where(function ($query) {
                    $query->whereNull('is_preview')->orWhere('is_preview', 0);
                })
                ->orderBy('id')
                ->get(['id', 'test_id', 'overall_band', 'listening_band', 'reading_band', 'listening_score', 'reading_score', 'total_questions'])
                ->each(function ($attempt) use (&$latestAttempts) {
                    $latestAttempts[(int)$attempt->test_id] = $attempt;
                });
        }

        foreach ($links as $link) {
            $test = $tests->get($link->ielts_test_id);
            if (empty($test) || !in_array($link->role, BundleLessonTest::$roles, true)) {
                continue;
            }

            $attempt = $latestAttempts[(int)$test->id] ?? null;
            $correct = !empty($attempt) ? (int)round((float)$attempt->listening_score + (float)$attempt->reading_score) : null;

            $result[$link->chapter_id][$link->role][] = [
                'link_id' => (int)$link->id,
                'test_id' => (int)$test->id,
                'title' => $test->title,
                'duration' => (int)$test->total_duration,
                'done' => !empty($attempt),
                'attempt_id' => !empty($attempt) ? (int)$attempt->id : null,
                'band' => !empty($attempt) && !is_null($attempt->overall_band) ? (float)$attempt->overall_band : null,
                'correct' => $correct,
                'total_questions' => !empty($attempt) ? (int)$attempt->total_questions : null,
            ];
        }
    }
}
