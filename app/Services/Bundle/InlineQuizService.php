<?php

namespace App\Services\Bundle;

use App\Models\Quiz;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesResult;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\WebinarChapterItem;

/**
 * GĐ7 - Làm quiz ngay trong trang học (theo design):
 * - Mini Quiz hiện trên video khi tới mốc thời gian
 * - Mid-term / Final Test hiện trong khung nội dung của trang lesson
 *
 * Chấm điểm giống hệt QuizController@quizzesStoreResult (dùng QuizzesQuestion::gradeSubmittedAnswer, cùng định dạng
 * kết quả), nên trang kết quả / chấm tay của Rocket LMS và tiến độ khóa học vẫn dùng chung dữ liệu.
 */
class InlineQuizService
{
    /** @var BundleLearningGate */
    private $gate;

    public function __construct(BundleLearningGate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * Học viên có được làm quiz này không.
     *
     * @return string|null null = được làm; ngược lại là thông báo lỗi
     */
    public function denyReason(Quiz $quiz, $user): ?string
    {
        if ($quiz->status !== Quiz::ACTIVE or empty($quiz->webinar)) {
            return trans('public.request_failed');
        }

        // Giáo viên / admin xem thử: không bị khóa theo lộ trình, không cần mua
        if ($user->isAdmin() or $quiz->webinar->isOwner($user->id) or (int)$quiz->creator_id === (int)$user->id) {
            return null;
        }

        if (!$quiz->webinar->checkUserHasBought($user)) {
            return trans('cart.you_not_purchased_this_course');
        }

        if (!empty($quiz->expiry_days) and !$quiz->checkCanAccessByExpireDays($user)) {
            return trans('update.your_access_to_this_quiz_has_been_expired');
        }

        $chapterId = $quiz->chapter_id ?: WebinarChapterItem::query()
            ->where('type', WebinarChapterItem::$chapterQuiz)
            ->where('item_id', $quiz->id)
            ->value('chapter_id');

        // Khóa theo lộ trình khóa học: lesson / Pre Lesson / điều kiện Mid-term, Final
        $block = $this->gate->check($quiz->webinar, $user, !empty($chapterId) ? (int)$chapterId : null, (int)$quiz->id);
        if (!empty($block)) {
            return trans('bundle_learning.' . $block['reason'] . '_msg');
        }

        return null;
    }

    public function questions(Quiz $quiz)
    {
        // Cùng thứ tự với trang làm quiz của Rocket (không xáo trộn để học viên làm lại vẫn quen vị trí)
        $query = QuizzesQuestion::query()
            ->where('quiz_id', $quiz->id)
            ->with('quizzesQuestionsAnswers')
            ->orderBy('order')
            ->orderBy('id');

        if ($quiz->display_limited_questions and !empty($quiz->display_number_of_questions)) {
            $query->take((int)$quiz->display_number_of_questions);
        }

        return $query->get();
    }

    public function totalGrade(Quiz $quiz): int
    {
        return (int)$this->questions($quiz)->sum('grade');
    }

    /**
     * Số lần làm còn lại: null = không giới hạn.
     */
    public function attemptsLeft(Quiz $quiz, $user): ?int
    {
        if (empty($quiz->attempt)) {
            return null;
        }

        $done = QuizzesResult::query()->where('quiz_id', $quiz->id)->where('user_id', $user->id)->count();

        return max(0, (int)$quiz->attempt - $done);
    }

    public function lastResult(Quiz $quiz, $user): ?QuizzesResult
    {
        return QuizzesResult::query()
            ->where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('results', '!=', '')
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Còn được nộp bài không (đã đạt thì vẫn cho làm lại để ôn, nếu quiz không giới hạn lượt).
     */
    public function canSubmit(Quiz $quiz, $user): bool
    {
        $left = $this->attemptsLeft($quiz, $user);

        return is_null($left) or $left > 0;
    }

    public function hasPassed(Quiz $quiz, $user): bool
    {
        return QuizzesResult::query()
            ->where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('status', QuizzesResult::$passed)
            ->exists();
    }

    /**
     * Chấm và lưu một lượt làm.
     *
     * @param array $answers [question_id => ['answer' => mixed]]
     * @return array ['result' => QuizzesResult, 'questions' => [question_id => ['correct' => bool, 'manual' => bool]]]
     */
    public function submit(Quiz $quiz, $user, array $answers): array
    {
        $questions = $this->questions($quiz)->keyBy('id');
        $results = [];
        $perQuestion = [];
        $totalMark = 0;
        $status = '';

        foreach ($questions as $questionId => $question) {
            $answer = $answers[$questionId]['answer'] ?? null;
            $hasAnswer = is_array($answer) ? !empty(array_filter($answer, function ($value) {
                return $value !== null && $value !== '';
            })) : ($answer !== null && trim((string)$answer) !== '');

            $perQuestion[$questionId] = ['correct' => false, 'manual' => false, 'answered' => $hasAnswer];

            if (!$hasAnswer) {
                continue;
            }

            /** @var QuizzesQuestion $question */
            $graded = $question->gradeSubmittedAnswer($answer);

            $results[$questionId] = [
                'answer' => $answer,
                'status' => (bool)$graded['is_correct'],
                'grade' => $question->grade,
                'negative_grade' => $question->negative_grade ?? null,
                'manual_review' => $graded['manual_review'],
            ];

            if ($graded['is_correct']) {
                $totalMark += (int)$graded['score'];
            } elseif (!$graded['manual_review'] and !empty($question->negative_grade)) {
                $totalMark -= (int)$question->negative_grade;
            }

            if ($graded['manual_review']) {
                $status = QuizzesResult::$waiting;
            }

            $perQuestion[$questionId]['correct'] = (bool)$graded['is_correct'];
            $perQuestion[$questionId]['manual'] = (bool)$graded['manual_review'];
        }

        if (empty($status)) {
            $status = ($totalMark >= (int)$quiz->pass_mark) ? QuizzesResult::$passed : QuizzesResult::$failed;
        }

        $attemptNumber = QuizzesResult::query()->where('quiz_id', $quiz->id)->where('user_id', $user->id)->count() + 1;
        $results['attempt_number'] = $attemptNumber;

        $quizResult = QuizzesResult::query()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'results' => json_encode($results),
            'user_grade' => $totalMark,
            'status' => $status,
            'created_at' => time(),
        ]);

        $this->afterSubmit($quiz, $quizResult, $user);

        return ['result' => $quizResult, 'questions' => $perQuestion];
    }

    /**
     * Thông báo chấm tay + điểm thưởng: giống QuizController@quizzesStoreResult.
     */
    private function afterSubmit(Quiz $quiz, QuizzesResult $quizResult, $user): void
    {
        if ($quizResult->status == QuizzesResult::$waiting) {
            sendNotification('waiting_quiz', [
                '[c.title]' => $quiz->webinar ? $quiz->webinar->title : '-',
                '[student.name]' => $user->full_name,
                '[q.title]' => $quiz->title,
            ], $quiz->creator_id);

            sendNotification('content_review_request', [
                '[u.name]' => $user->full_name,
                '[item_title]' => $quiz->title,
                '[content_type]' => 'Quiz',
                '[link]' => getAdminPanelUrl("/quizzes/{$quiz->id}/results/{$quizResult->id}/review"),
            ], 1);
        }

        if ($quizResult->status == QuizzesResult::$passed) {
            $passTheQuizReward = RewardAccounting::calculateScore(Reward::PASS_THE_QUIZ);
            RewardAccounting::makeRewardAccounting($quizResult->user_id, $passTheQuizReward, Reward::PASS_THE_QUIZ, $quiz->id, true);

            if ($quiz->certificate) {
                $certificateReward = RewardAccounting::calculateScore(Reward::CERTIFICATE);
                RewardAccounting::makeRewardAccounting($quizResult->user_id, $certificateReward, Reward::CERTIFICATE, $quiz->id, true);
            }
        }
    }
}
