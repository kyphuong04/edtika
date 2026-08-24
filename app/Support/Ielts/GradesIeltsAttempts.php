<?php

namespace App\Support\Ielts;

use App\Models\IeltsTestAttempt;
use Illuminate\Support\Facades\Schema;

/**
 * Nguồn sự thật DUY NHẤT cho việc chấm điểm/tính band 1 attempt — dùng
 * chung bởi IeltsTestController (luồng tự động khi nộp bài) và
 * IeltsTestGradingController (mentor chấm tay Writing/Speaking).
 *
 * Trước trait này, autoGradeListening()/autoGradeReading() HARDCODE đúng
 * 2 skill -> Grammar/Vocabulary (nếu đề có) không bao giờ được chấm dù
 * auto_gradable=true. Đồng thời logic "hoàn tất attempt" bị lặp lại ở 4
 * nơi khác nhau (finishSection/submitTest/autoSubmitTest/autoSubmitSkill)
 * — gộp về 1 chỗ để tránh lệch nhau.
 */
trait GradesIeltsAttempts
{
    private array $ieltsBandConversionTable = [
        40 => 9.0, 39 => 8.5, 38 => 8.5, 37 => 8.0, 36 => 8.0,
        35 => 7.5, 34 => 7.5, 33 => 7.0, 32 => 7.0, 31 => 6.5,
        30 => 6.5, 29 => 6.5, 28 => 6.0, 27 => 6.0, 26 => 6.0,
        25 => 5.5, 24 => 5.5, 23 => 5.5, 22 => 5.0, 21 => 5.0,
        20 => 5.0, 19 => 5.0, 18 => 4.5, 17 => 4.5, 16 => 4.5,
        15 => 4.0, 14 => 4.0, 13 => 4.0, 12 => 3.5, 11 => 3.5,
        10 => 3.0, 9 => 3.0, 8 => 2.5, 7 => 2.5, 6 => 2.0,
        5 => 2.0, 4 => 1.5, 3 => 1.0, 2 => 1.0, 1 => 0.5, 0 => 0.0,
    ];

    /**
     * Auto-chấm mọi câu auto_gradable trong 1 skill. Cộng dồn điểm vào cột
     * "<skill>_score" nếu cột đó tồn tại (Listening/Reading/Writing/
     * Speaking có sẵn cột; Grammar/Vocabulary không có cột riêng nhưng
     * từng IeltsTestAnswer vẫn được set is_correct/points_earned đúng).
     */
    public function autoGradeSkill(IeltsTestAttempt $attempt, string $skill): void
    {
        $answers = $attempt->answers()
            ->whereHas('question.section', fn ($q) => $q->where('skill', $skill))
            ->with('question')
            ->get();

        $correctCount = 0;

        foreach ($answers as $answer) {
            if (!$answer->question || !$answer->question->auto_gradable) {
                continue;
            }

            $answer->autoGrade();

            if ($answer->is_correct) {
                $correctCount++;
            }
        }

        $scoreField = $skill . '_score';
        if (Schema::hasColumn($attempt->getTable(), $scoreField)) {
            $attempt->{$scoreField} = $correctCount;
            $attempt->save();
        }
    }

    public function scoreToBand(?float $score, string $skill): ?float
    {
        if ($score === null) {
            return null;
        }

        $rounded = (int) round($score);

        return $this->ieltsBandConversionTable[$rounded] ?? null;
    }

    /**
     * Overall band = trung bình cộng các skill IELTS CHÍNH THỨC có mặt
     * trong đề (Listening/Reading/Writing/Speaking) — KHÔNG bao giờ tính
     * Grammar/Vocabulary. Làm tròn theo đúng quy tắc IELTS thật (.25 lên
     * .5, .75 lên nguyên) — tương đương round(avg*2)/2.
     *
     * Chỉ tính khi ĐỦ band của MỌI skill có mặt — Writing/Speaking cần
     * mentor chấm tay nên có thể còn thiếu lúc attempt vừa completed.
     * An toàn gọi lại nhiều lần, tự tính lại khi có thêm skill được chấm.
     */
    public function refreshOverallBand(IeltsTestAttempt $attempt): void
    {
        $officialSkills = ['listening', 'reading', 'writing', 'speaking'];

        $presentSkills = $attempt->test->sections()
            ->whereIn('skill', $officialSkills)
            ->distinct()
            ->pluck('skill');

        $bands = [];

        foreach ($presentSkills as $skill) {
            $band = $attempt->{$skill . '_band'};

            if ($band === null) {
                return; // còn thiếu -> chưa đủ điều kiện, giữ nguyên overall_band hiện tại
            }

            $bands[] = (float) $band;
        }

        if (empty($bands)) {
            return;
        }

        $average = array_sum($bands) / count($bands);
        $attempt->overall_band = round($average * 2) / 2;
        $attempt->save();
    }

    /**
     * Điểm kết thúc DUY NHẤT của 1 attempt — gọi khi hết giờ tự động nộp,
     * khi học viên bấm nộp section cuối, hoặc submit toàn bài (legacy).
     */
    public function finalizeAttempt(IeltsTestAttempt $attempt): void
    {
        $skills = $attempt->test->sections()->distinct()->pluck('skill')->filter();

        foreach ($skills as $skill) {
            $this->autoGradeSkill($attempt, $skill);
        }

        $attempt->refresh();
        $attempt->listening_band = $this->scoreToBand($attempt->listening_score, 'listening');
        $attempt->reading_band = $this->scoreToBand($attempt->reading_score, 'reading');
        $attempt->save();

        $this->refreshOverallBand($attempt);

        $attempt->update([
            'status' => 'completed',
            'completed_at' => time(),
            'updated_at' => time(),
        ]);
    }
}