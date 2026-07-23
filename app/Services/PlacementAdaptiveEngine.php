<?php

namespace App\Services;

/**
 * Adaptive Placement Test — Engine phân luồng theo điểm.
 *
 * Cài đặt CHÍNH XÁC theo bảng luật đã chốt (điểm số nguyên 0-10):
 *
 * Bước 1 (luôn là đề B1):
 *   <7  -> đề 2: A2
 *   =7  -> đề 2: B1
 *   >7  -> đề 2: B2
 *
 * Bước 2:
 *   Từ B1->A2 (bước1<7):  <5 -> DỪNG A1 | 5-6 -> DỪNG A2 | >=7 -> đề 3: B1
 *   Từ B1->B1 (bước1=7):  <7 -> đề 3: A2 | =7 -> đề 3: B1 | >7 -> đề 3: B2
 *   Từ B1->B2 (bước1>7):  <6 -> DỪNG B1 | 6-8 -> DỪNG B2 | >=9 -> đề 3: B2+
 *
 * Bước 3:
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
     * @param array $levelsTaken Level của các đề ĐÃ làm, theo đúng thứ tự, ĐÃ bao gồm đề vừa nộp. Vd ["B1","A2"]
     * @param array $scores      Điểm (số câu đúng, 0-10) tương ứng theo đúng thứ tự với $levelsTaken.
     * @return array ['action' => 'stop', 'final_level' => string]
     *             hoặc ['action' => 'continue', 'next_level' => string]
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
                    if ($lastScore < 5) return $this->stop('A1');
                    if ($lastScore <= 6) return $this->stop('A2');
                    return $this->continueTo('B1');

                case 'B1->B1':
                    if ($lastScore < 7) return $this->continueTo('A2');
                    if ($lastScore === 7) return $this->continueTo('B1');
                    return $this->continueTo('B2');

                case 'B1->B2':
                    if ($lastScore < 6) return $this->stop('B1');
                    if ($lastScore <= 8) return $this->stop('B2');
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

        // Fallback an toàn — không đúng lý thuyết sẽ không bao giờ tới đây, nhưng
        // tránh crash nếu dữ liệu bất thường (vd bị sửa tay trong DB).
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
}