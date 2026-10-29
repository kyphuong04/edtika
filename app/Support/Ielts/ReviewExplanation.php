<?php

namespace App\Support\Ielts;

use App\Models\IeltsTest;
use App\Models\IeltsTestQuestion;

/**
 * Giải thích chi tiết theo TỪNG SỐ CÂU cho sidebar "Giải thích đáp án"
 * (trang chữa bài Reading / Listening).
 *
 * Lưu trong question_data.explain (giáo viên nhập ở trình tạo đề):
 *
 *   {
 *     "slots": {                     // khoá = vị trí ô trong câu hỏi (0, 1, 2…)
 *       "0": {
 *         "translation": "Người viết không khuyến nghị…",   // dịch câu hỏi (chữ thường)
 *         "analysis":    "<p>…</p>",                         // phân tích / vì sao đáp án đúng (HTML)
 *         "evidence":    "câu trích trong bài đọc",           // Reading: tìm & tô trong passage
 *         "vocab":       [{ "term": "writer", "meaning": "người viết" }],
 *         "paraphrase":  { "rows": [{ "question": "not recommend", "passage": "does not advise" }],
 *                          "note": "<p>…</p>" },
 *         "wrong": {
 *           "options": { "A": "<p>…</p>", "NOT GIVEN": "…" },  // MCQ / TFNG / Matching / Drag & drop
 *           "common":  [{ "answer": "papers", "reason": "<p>…</p>" }]  // dạng điền từ
 *         }
 *       }
 *     }
 *   }
 *
 * Phần này KHÔNG được gửi xuống trang làm bài (xem stripAnswerFields()).
 * Câu chưa có giải thích kiểu mới -> trang chữa bài dùng ô explanation cũ.
 */
class ReviewExplanation
{
    /** Cấu hình HTMLPurifier cho phép bảng (config/purifier.php -> ielts_review). */
    public const PURIFIER = 'ielts_review';

    private const TEXT_MAX = 2000;
    private const LIST_MAX = 30;

    public static function html($value): ?string
    {
        if (!is_string($value) || trim(strip_tags($value, '<img><table>')) === '') {
            return null;
        }

        $out = clean($value, self::PURIFIER);

        return trim(strip_tags($out, '<img><table>')) === '' ? null : $out;
    }

    public static function text($value, int $max = self::TEXT_MAX): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $value), ENT_QUOTES | ENT_HTML5, 'UTF-8')));

        return $text === '' ? null : mb_substr($text, 0, $max);
    }

    /** Dữ liệu sạch cho trang chữa bài, hoặc null nếu câu chưa có. */
    public static function forQuestion(IeltsTestQuestion $question): ?array
    {
        $data = $question->question_data;
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        $raw = is_array($data) ? ($data['explain'] ?? null) : null;
        $slots = is_array($raw) ? ($raw['slots'] ?? null) : null;
        if (!is_array($slots)) {
            return null;
        }

        $out = [];
        foreach ($slots as $index => $slot) {
            if (!is_numeric($index) || !is_array($slot)) {
                continue;
            }

            $clean = self::slot($slot);
            if ($clean) {
                $out[(string) (int) $index] = $clean;
            }
        }

        return $out ? ['slots' => $out] : null;
    }

    private static function slot(array $slot): ?array
    {
        $vocab = [];
        foreach (array_slice((array) ($slot['vocab'] ?? []), 0, self::LIST_MAX) as $row) {
            $term = self::text($row['term'] ?? null, 200);
            $meaning = self::text($row['meaning'] ?? null, 400);
            if ($term) {
                $vocab[] = ['term' => $term, 'meaning' => $meaning];
            }
        }

        $para = is_array($slot['paraphrase'] ?? null) ? $slot['paraphrase'] : [];
        $rows = [];
        foreach (array_slice((array) ($para['rows'] ?? []), 0, self::LIST_MAX) as $row) {
            $q = self::text($row['question'] ?? null, 300);
            $p = self::text($row['passage'] ?? null, 300);
            if ($q || $p) {
                $rows[] = ['question' => $q, 'passage' => $p];
            }
        }

        $wrong = is_array($slot['wrong'] ?? null) ? $slot['wrong'] : [];
        $options = [];
        foreach ((array) ($wrong['options'] ?? []) as $key => $reason) {
            $k = self::text($key, 40);
            $r = self::html($reason);
            if ($k !== null && $r) {
                $options[$k] = $r;
            }
        }

        $common = [];
        foreach (array_slice((array) ($wrong['common'] ?? []), 0, self::LIST_MAX) as $row) {
            $a = self::text($row['answer'] ?? null, 200);
            $r = self::html($row['reason'] ?? null);
            if ($a && $r) {
                $common[] = ['answer' => $a, 'reason' => $r];
            }
        }

        $out = array_filter([
            'translation' => self::text($slot['translation'] ?? null),
            'analysis' => self::html($slot['analysis'] ?? null),
            'evidence' => self::text($slot['evidence'] ?? null),
            'vocab' => $vocab,
            'paraphrase' => array_filter([
                'rows' => $rows,
                'note' => self::html($para['note'] ?? null),
            ]),
            'wrong' => array_filter([
                'options' => $options,
                'common' => $common,
            ]),
        ]);

        return $out ?: null;
    }

    /** [{ title, description, url }] — chỉ link http(s). */
    public static function resources(IeltsTest $test): array
    {
        $raw = $test->review_resources;
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }

        $out = [];
        foreach (array_slice(is_array($raw) ? $raw : [], 0, 10) as $row) {
            if (!is_array($row)) {
                continue;
            }
            $title = self::text($row['title'] ?? null, 200);
            if (!$title) {
                continue;
            }
            $url = trim((string) ($row['url'] ?? ''));
            $out[] = [
                'title' => $title,
                'description' => self::text($row['description'] ?? null, 400),
                'url' => preg_match('~^https?://~i', $url) ? $url : null,
            ];
        }

        return $out;
    }
}
