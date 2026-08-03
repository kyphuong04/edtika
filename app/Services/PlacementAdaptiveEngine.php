<?php

namespace App\Services;

/**
 * Adaptive Placement Test — Engine phân luồng theo điểm.
 *
 * LƯU Ý QUAN TRỌNG (đã đổi so với bản gốc):
 * 4 nhánh trước đây DỪNG ngay ở bước 2 (B1->A2 <5, B1->A2 5-6, B1->B2 <6,
 * B1->B2 6-8) giờ KHÔNG dừng nữa. Chúng trả về action 'continue_locked':
 *   - final_level: CHỐT NGAY, không đổi dù đề 3 điểm gì.
 *   - next_level: level của đề thứ 3 (LUÔN bằng chính final_level vừa chốt),
 *     đề này chỉ mang tính tham khảo cho giáo viên/manager, không tính điểm.
 * Controller khi thấy action này phải: (1) chốt final_level vào attempt ngay,
 * (2) vẫn giao đề 3 bình thường, (3) khi đề 3 nộp xong, KHÔNG gọi lại engine
 * nữa mà đi thẳng tới bước Speaking/hoàn tất với final_level đã có.
 *
 * Bước 1 (luôn là đề B1):
 *   <7  -> đề 2: A2
 *   =7  -> đề 2: B1
 *   >7  -> đề 2: B2
 *
 * Bước 2:
 *   Từ B1->A2:  <5 -> CHỐT A1, đề 3 tham khảo (A1)
 *               5-6 -> CHỐT A2, đề 3 tham khảo (A2)
 *               >=7 -> đề 3: B1 (tiếp tục thật)
 *   Từ B1->B1:  <7 -> đề 3: A2 | =7 -> đề 3: B1 | >7 -> đề 3: B2
 *   Từ B1->B2:  <6 -> CHỐT B1, đề 3 tham khảo (B1)
 *               6-8 -> CHỐT B2, đề 3 tham khảo (B2)
 *               >=9 -> đề 3: B2+ (tiếp tục thật)
 *
 * Bước 3 (chỉ áp dụng cho các nhánh KHÔNG bị khoá ở bước 2):
 *   B1->A2->B1: <6 -> DỪNG A2 | >=6 -> DỪNG B1
 *   B1->B1->A2: <5 -> DỪNG A1 | 5-8 -> DỪNG A2 | >=9 -> DỪNG B1
 *   B1->B1->B1: <6 -> DỪNG A2 | >=6 -> DỪNG B1
 *   B1->B1->B2: <9 -> DỪNG B1 | >=9 -> DỪNG B2
 *   B1->B2->B2+: <8 -> DỪNG B2 | >=8 -> DỪNG B2+
 */
class PlacementAdaptiveEngine
{
    public const FIRST_LEVEL = 'B1';
    public const MAX_STEPS = 3;

    /**
     * @param array $levelsTaken Level của các đề ĐÃ làm, theo đúng thứ tự, ĐÃ bao gồm đề vừa nộp.
     * @param array $scores      Điểm (số câu đúng, 0-10) tương ứng theo đúng thứ tự với $levelsTaken.
     * @return array
     *   ['action' => 'stop', 'final_level' => string]
     *   ['action' => 'continue', 'next_level' => string]
     *   ['action' => 'continue_locked', 'next_level' => string, 'final_level' => string]
     */
    public function decideNext(array $levelsTaken, array $scores): array
    {
        $step = count($levelsTaken);
        $lastScore = end($scores);

        if ($step === 1) {
            if ($lastScore < 7) return $this->continueTo('A2');
            if ($lastScore === 7) return $this->continueTo('B1');
            return $this->continueTo('B2');
        }

        if ($step === 2) {
            $path = implode('->', $levelsTaken);

            switch ($path) {
                case 'B1->A2':
                    if ($lastScore < 5) return $this->continueLocked('A1');
                    if ($lastScore <= 6) return $this->continueLocked('A2');
                    return $this->continueTo('B1');

                case 'B1->B1':
                    if ($lastScore < 7) return $this->continueTo('A2');
                    if ($lastScore === 7) return $this->continueTo('B1');
                    return $this->continueTo('B2');

                case 'B1->B2':
                    if ($lastScore < 6) return $this->continueLocked('B1');
                    if ($lastScore <= 8) return $this->continueLocked('B2');
                    return $this->continueTo('B2+');
            }
        }

        if ($step === 3) {
            $path = implode('->', $levelsTaken);

            switch ($path) {
                case 'B1->A2->B1':
                    return $lastScore < 6 ? $this->stop('A2') : $this->stop('B1');

                case 'B1->B1->A2':
                    if ($lastScore < 5) return $this->stop('A1');
                    if ($lastScore <= 8) return $this->stop('A2');
                    return $this->stop('B1');

                case 'B1->B1->B1':
                    return $lastScore < 6 ? $this->stop('A2') : $this->stop('B1');

                case 'B1->B1->B2':
                    return $lastScore < 9 ? $this->stop('B1') : $this->stop('B2');

                case 'B1->B2->B2+':
                    return $lastScore < 8 ? $this->stop('B2') : $this->stop('B2+');
            }
        }

        // Fallback an toàn — không đúng lý thuyết sẽ không bao giờ tới đây.
        return $this->stop(end($levelsTaken) ?: self::FIRST_LEVEL);
    }

    private function stop(string $finalLevel): array
    {
        return ['action' => 'stop', 'final_level' => $finalLevel];
    }

    private function continueTo(string $nextLevel): array
    {
        return ['action' => 'continue', 'next_level' => $nextLevel];
    }

    /**
     * Chốt final_level ngay, nhưng vẫn giao thêm 1 đề (đề 3) CÙNG level để
     * làm tham khảo — không tính vào kết quả.
     */
    private function continueLocked(string $lockedLevel): array
    {
        return ['action' => 'continue_locked', 'next_level' => $lockedLevel, 'final_level' => $lockedLevel];
    }
}