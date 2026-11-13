<?php

namespace App\Services\Bundle;

use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\WebinarAssignment;
use App\Models\WebinarAssignmentHistory;
use App\Models\WebinarAssignmentHistoryMessage;
use Illuminate\Support\Facades\DB;

/**
 * GĐ4 - Chấm bài (Post Lesson / Bài tập) dùng chung cho learning page và màn chấm bài theo bundle.
 */
class AssignmentGradingService
{
    /**
     * @param WebinarAssignmentHistory $history bài nộp của học viên
     * @param int $grade điểm
     * @param \App\User $grader người chấm (đã kiểm tra quyền bằng WebinarAssignment::canBeGradedBy)
     * @param string|null $comment nhận xét, lưu thành tin nhắn trong bài nộp
     */
    public function grade(WebinarAssignmentHistory $history, int $grade, $grader, ?string $comment = null): WebinarAssignmentHistory
    {
        /** @var WebinarAssignment $assignment */
        $assignment = $history->assignment;
        $wasPassed = ($history->status === WebinarAssignmentHistory::$passed);

        $status = ($grade >= (int)$assignment->pass_grade)
            ? WebinarAssignmentHistory::$passed
            : WebinarAssignmentHistory::$notPassed;

        DB::transaction(function () use ($history, $grade, $status, $grader, $comment) {
            $history->update([
                'status' => $status,
                'grade' => $grade,
            ]);

            $comment = trim((string)$comment);
            if ($comment !== '') {
                WebinarAssignmentHistoryMessage::query()->create([
                    'assignment_history_id' => $history->id,
                    'sender_id' => $grader->id,
                    'message' => $comment,
                    'created_at' => time(),
                ]);
            }
        });

        // Điểm thưởng chỉ cộng lần đầu đạt
        if ($status === WebinarAssignmentHistory::$passed and !$wasPassed) {
            $reward = RewardAccounting::calculateScore(Reward::PASS_ASSIGNMENT);
            RewardAccounting::makeRewardAccounting($history->student_id, $reward, Reward::PASS_ASSIGNMENT, $assignment->id);
        }

        $webinar = $assignment->webinar;
        $instructor = $history->instructor;
        $student = $history->student;

        if (!empty($student)) {
            sendNotification('instructor_set_grade', [
                '[instructor.name]' => !empty($instructor) ? $instructor->full_name : $grader->full_name,
                '[c.title]' => !empty($webinar) ? $webinar->title : '',
                '[student.name]' => $student->full_name,
                '[assignment_grade]' => $grade,
            ], $student->id);
        }

        return $history->fresh();
    }
}
