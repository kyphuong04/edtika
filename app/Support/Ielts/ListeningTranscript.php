<?php

namespace App\Support\Ielts;

/**
 * Chuẩn hoá transcript Listening có cấu trúc (ielts_test_parts.transcript_data)
 * gửi lên từ trình tạo đề.
 *
 *   speakers: [{ id: "s1", name: "Jen", narrator: false }]
 *   lines:    [{ speaker: "s1", text: "Hi, [[q1]]I booked a room[[/q1]].", start: 12.5 }]
 *
 * Toàn bộ là chữ thường (strip_tags) — trang chữa bài hiển thị bằng textContent,
 * không chèn HTML. Đoạn đáp án dùng cú pháp [[qN]]...[[/qN]].
 */
class ListeningTranscript
{
    public const MAX_SPEAKERS = 12;
    public const MAX_LINES = 1000;
    public const MAX_TEXT = 3000;
    public const MAX_NAME = 40;
    public const MAX_SECONDS = 36000;

    /**
     * @param mixed $raw mảng hoặc chuỗi JSON
     * @return array|null null khi không có dòng nào
     */
    public static function sanitize($raw): ?array
    {
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }

        if (!is_array($raw)) {
            return null;
        }

        $speakers = [];
        foreach (array_slice((array) ($raw['speakers'] ?? []), 0, self::MAX_SPEAKERS) as $index => $speaker) {
            if (!is_array($speaker)) {
                continue;
            }

            $id = preg_replace('/[^a-z0-9_-]/i', '', (string) ($speaker['id'] ?? ''));
            if ($id === '') {
                $id = 's' . ($index + 1);
            }
            $id = substr($id, 0, 20);

            if (isset($speakers[$id])) {
                continue;
            }

            $name = self::plain($speaker['name'] ?? '', self::MAX_NAME);

            $speakers[$id] = [
                'id' => $id,
                'name' => $name !== '' ? $name : ('Speaker ' . ($index + 1)),
                'narrator' => !empty($speaker['narrator']),
            ];
        }

        $lines = [];
        foreach (array_slice((array) ($raw['lines'] ?? []), 0, self::MAX_LINES) as $line) {
            if (!is_array($line)) {
                continue;
            }

            $text = self::plain($line['text'] ?? '', self::MAX_TEXT);
            if ($text === '') {
                continue;
            }

            $speakerId = (string) ($line['speaker'] ?? '');

            $start = $line['start'] ?? null;
            if ($start === '' || $start === null || !is_numeric($start)) {
                $start = null;
            } else {
                $start = round(max(0, min(self::MAX_SECONDS, (float) $start)), 2);
            }

            $lines[] = [
                'speaker' => isset($speakers[$speakerId]) ? $speakerId : null,
                'text' => $text,
                'start' => $start,
            ];
        }

        if (!$lines) {
            return null;
        }

        return [
            'speakers' => array_values($speakers),
            'lines' => $lines,
        ];
    }

    /** Chữ thường 1 dòng: bỏ thẻ HTML, gộp khoảng trắng, cắt độ dài. */
    private static function plain($value, int $max): string
    {
        $value = html_entity_decode(strip_tags((string) $value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = trim(preg_replace('/\s+/u', ' ', $value));

        return mb_substr($value, 0, $max);
    }
}
