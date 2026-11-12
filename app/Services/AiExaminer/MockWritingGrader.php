<?php

namespace App\Services\AiExaminer;

/**
 * Bộ chấm GIẢ cho bản mẫu giao diện.
 * - Giả lập thời gian chấm từng tiêu chí dựa trên created_at.
 * - Trả kết quả đúng cấu trúc trong WritingGraderInterface.
 * - Nếu bài làm không chứa đoạn anchor mẫu, tự gắn lỗi vào một câu trong bài của học viên
 *   để giao diện highlight/badge vẫn hiển thị được.
 */
class MockWritingGrader implements WritingGraderInterface
{
    public function submit(array $submission): array
    {
        $submission['job_id'] = 'mock_' . $submission['id'];
        $submission['status'] = 'processing';

        return $submission;
    }

    public function status(array $submission): array
    {
        $criteria = config('ai_examiner.writing.criteria.' . $submission['task']);
        $perCriterion = max(1, (int)config('ai_examiner.mock_seconds_per_criterion', 2));
        $elapsed = max(0, microtime(true) - ($submission['created_at'] ?? time()));
        $total = count($criteria);
        $doneCount = min($total, (int)floor($elapsed / $perCriterion));

        $items = [];
        $index = 0;
        foreach ($criteria as $key => $criterion) {
            $items[] = [
                'key' => $key,
                'name' => $criterion['name'],
                'status' => $index < $doneCount ? 'done' : ($index === $doneCount ? 'processing' : 'waiting'),
            ];
            $index++;
        }

        $progress = $doneCount >= $total ? 100 : (int)min(99, round(($elapsed / ($perCriterion * $total)) * 100));

        return [
            'status' => $doneCount >= $total ? 'done' : 'processing',
            'progress' => max(6, $progress),
            'done' => $doneCount,
            'total' => $total,
            'criteria' => $items,
            'error' => null,
        ];
    }

    public function result(array $submission): array
    {
        $task = (int)$submission['task'];
        $essay = (string)$submission['essay'];
        $definitions = config('ai_examiner.writing.criteria.' . $task);
        $feedback = MockWritingData::feedback($task);
        $sentences = $this->sentences($essay);
        $usedFallback = 0;

        $criteria = [];
        foreach ($definitions as $key => $definition) {
            $data = $feedback[$key];
            $no = 0;
            $subs = [];

            foreach ($definition['subs'] as $subKey => $subName) {
                $sub = $data['subs'][$subKey];
                $issues = [];

                foreach ($sub['issues'] as $issue) {
                    $no++;
                    [$start, $end, $anchor] = $this->locate($essay, $issue['anchor'], $sentences, $usedFallback);
                    $issues[] = array_merge($issue, [
                        'no' => $no,
                        'anchor' => $anchor,
                        'start' => $start,
                        'end' => $end,
                    ]);
                }

                $subs[] = [
                    'key' => $subKey,
                    'name' => $subName,
                    'band' => (float)$sub['band'],
                    'comment' => $sub['comment'],
                    'strengths' => $sub['strengths'],
                    'issues' => $issues,
                    'improvements' => $sub['improvements'],
                ];
            }

            $criteria[] = [
                'key' => $key,
                'code' => $definition['code'],
                'name' => $definition['name'],
                'icon' => $definition['icon'],
                'band' => (float)$data['band'],
                'summary' => $data['summary'],
                'priorities' => $data['priorities'],
                'tips' => $data['tips'],
                'sub_criteria' => $subs,
            ];
        }

        $bands = array_column($criteria, 'band');
        $focus = $criteria[array_search(min($bands), $bands)]['key'];

        $samples = [];
        foreach (MockWritingData::samples($task) as $band => $text) {
            $samples[$band] = ['text' => $text, 'words' => self::wordCount($text)];
        }

        return [
            'submission_id' => $submission['id'],
            'task' => $task,
            'model_version' => 'mock-0.1',
            'overall_band' => self::roundBand(array_sum($bands) / count($bands)),
            'focus_criterion' => $focus,
            'criteria' => $criteria,
            'samples' => $samples,
        ];
    }

    /** Làm tròn band theo quy tắc IELTS (.25 → .5, .75 → 1.0). */
    public static function roundBand(float $value): float
    {
        $whole = floor($value);
        $fraction = $value - $whole;

        if ($fraction < 0.25) {
            return $whole;
        }

        return $fraction < 0.75 ? $whole + 0.5 : $whole + 1;
    }

    public static function wordCount(string $text): int
    {
        return preg_match_all('/\S+/u', $text);
    }

    /** Tìm vị trí anchor trong bài (offset ký tự, UTF-8). Không thấy → mượn một câu trong bài. */
    private function locate(string $essay, string $anchor, array $sentences, int &$usedFallback): array
    {
        $pos = mb_strpos($essay, $anchor);
        if ($pos !== false) {
            return [$pos, $pos + mb_strlen($anchor), $anchor];
        }

        if (empty($sentences)) {
            return [null, null, $anchor];
        }

        $sentence = $sentences[$usedFallback % count($sentences)];
        $usedFallback++;

        return [$sentence['start'], $sentence['end'], $sentence['text']];
    }

    private function sentences(string $essay): array
    {
        $result = [];
        if (preg_match_all('/[^.!?\n]+[.!?]?/u', $essay, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as [$text, $byteOffset]) {
                $trimmed = trim($text);
                if (mb_strlen($trimmed) < 25) {
                    continue;
                }
                $start = mb_strlen(substr($essay, 0, $byteOffset)) + (mb_strlen($text) - mb_strlen(ltrim($text)));
                $result[] = ['text' => $trimmed, 'start' => $start, 'end' => $start + mb_strlen($trimmed)];
            }
        }

        return $result;
    }
}
