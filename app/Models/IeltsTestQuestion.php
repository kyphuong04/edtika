<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IeltsTestQuestion extends Model
{
    public $timestamps = false;
    
    protected $table = 'ielts_test_questions';
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'created_at' => 'integer',
        'accept_synonyms' => 'boolean',
        'case_sensitive' => 'boolean',
        'auto_gradable' => 'boolean',
        'points' => 'float',
        'answer_options' => 'array',
        'question_data' => 'array',
        'table_structure' => 'array',
        'flow_data' => 'array',
    ];
    
    // Relationships
    
    public function section()
    {
        return $this->belongsTo(IeltsTestSection::class, 'section_id');
    }

    public function part()
    {
        return $this->belongsTo(IeltsTestPart::class, 'part_id');
    }
    
    public function questionGroup()
    {
        return $this->belongsTo(IeltsQuestionGroup::class, 'question_group_id');
    }
    
    public function answers()
    {
        return $this->hasMany(IeltsTestAnswer::class, 'question_id');
    }
    
    // Accessors & Mutators
    
    public function getCorrectAnswerArrayAttribute()
    {
        $raw = $this->correct_answer;

        if (is_array($raw)) {
            return $raw;
        }

        if ($raw === null || $raw === '') {
            return [];
        }

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);

            // Chỉ nhận kết quả decode khi là mảng hoặc chuỗi JSON ("\"abc\"").
            // Không nhận true/false/null/số — giữ nguyên chuỗi gốc.
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($decoded)) {
                    return $decoded;
                }
                if (is_string($decoded)) {
                    return [$decoded];
                }
            }
        }

        return [$raw];
    }

    public function getFormattedCorrectAnswerAttribute()
    {
        if ($this->usesCompletionAnswerGroups()) {
            $groups = $this->normalizeCompletionAnswerGroups($this->correct_answer_array);

            return implode(' | ', array_map(
                fn ($variants) => implode(' / ', $this->flattenAnswerValues($variants)),
                $groups
            ));
        }

        $raw = $this->correct_answer_array;

        if (is_array($raw)) {
            return implode(', ', array_map(
                fn ($value) => implode(' / ', $this->flattenAnswerValues($value)),
                $raw
            ));
        }

        return (string) $raw;
    }
    
    public function getAnswerOptionsArrayAttribute()
    {
        if (is_string($this->answer_options)) {
            return json_decode($this->answer_options, true) ?? [];
        }
        return $this->answer_options ?? [];
    }
    
    /**
     * Alias for answer_options - for compatibility with views that use $q->options
     */
    public function getOptionsAttribute()
    {
        return $this->answer_options_array;
    }

    // SAU
    public function getTableCompletionAnswersArrayAttribute()
    {
        $table = is_array($this->table_structure)
            ? $this->table_structure
            : (is_string($this->table_structure) ? json_decode($this->table_structure, true) : null);

        if (!is_array($table) || empty($table['answers']) || !is_array($table['answers'])) {
            return [];
        }

        $answers = [];

        foreach ($table['answers'] as $answerItem) {
            if (!isset($answerItem['row'], $answerItem['col'])) {
                continue;
            }

            $rawBlanks = $answerItem['answers'] ?? $answerItem['answer'] ?? [];
            if (!is_array($rawBlanks)) {
                $rawBlanks = [$rawBlanks];
            }

            // Mỗi phần tử trong $rawBlanks ứng với 1 BLANK RIÊNG trong cell này
            // (không gộp phẳng như trước) — value có thể chứa nhiều biến thể
            // phân tách bằng "/", VD "yellow / Yellow".
            $perBlankVariants = [];
            foreach ($rawBlanks as $blankValue) {
                $perBlankVariants[] = $this->normalizeTableAnswerVariants($blankValue);
            }

            if (empty($perBlankVariants)) {
                continue;
            }

            $answers[] = [
                'row' => (int) $answerItem['row'],
                'col' => (int) $answerItem['col'],
                // MẢNG THEO THỨ TỰ BLANK — mỗi phần tử là mảng biến thể chấp
                // nhận được cho ĐÚNG blank ở vị trí đó. Khác bản cũ (gộp phẳng
                // toàn bộ variants của cả cell thành 1 danh sách, làm mất vị
                // trí blank khi cell có >1 blank).
                'answers' => $perBlankVariants,
            ];
        }

        return $answers;
    }
    
    // Helper Methods
    
    public function isMultipleChoice()
    {
        return in_array($this->question_type, ['multiple_choice', 'true_false_ng', 'yes_no_ng']);
    }
    
    public function isMultipleSelect()
    {
        return in_array($this->question_type, ['multiple_select', 'multiple_choice_multiple', 'choose_two', 'choose_three']);
    }
    
    public function isFillBlank()
    {
        return in_array($this->question_type, ['fill_blank', 'sentence_completion', 'note_completion', 'table_completion', 'summary_completion', 'flow_chart', 'diagram_label', 'short_answer']);
    }
    
    public function isEssay()
    {
        return $this->question_type === 'essay';
    }

    public function isMatchingType()
    {
        return in_array($this->question_type, [
            'matching_headings',
            'matching_information',
            'matching_features',
            'matching_sentence_endings',
        ], true);
    }

    public function isDragDropType()
    {
        return in_array($this->question_type, ['drag_drop_disappear', 'drag_drop_reuse'], true);
    }
    
    public function checkAnswer($userAnswer)
    {
        if (!$this->auto_gradable) {
            return null; // Requires manual grading
        }

        $slots = $this->gradeSlots($userAnswer);

        return !empty($slots) && !in_array(false, array_column($slots, 'correct'), true);
    }

        /*
    |--------------------------------------------------------------------------
    | Chấm theo TỪNG Ô (slot)
    |--------------------------------------------------------------------------
    | 1 slot = 1 số thứ tự câu hỏi IELTS = 1 điểm. Nguồn sự thật DUY NHẤT cho:
    |  - điểm (GradesIeltsAttempts cộng số slot đúng)
    |  - số câu 1 dòng IeltsTestQuestion chiếm (slotCount())
    |  - tô màu đúng/sai từng ô ở trang chữa bài
    |
    | Mỗi slot: ['submitted' => ?string, 'accepted' => string[], 'correct' => bool]
    | Riêng table_completion có thêm 'cell' => "row-col" và 'parts' (từng blank
    | trong ô đó, cùng cấu trúc).
    */

    public function gradeSlots($userAnswer): array
    {
        if ($this->question_type === 'table_completion') {
            return $this->gradeTableSlots($userAnswer);
        }

        if ($this->isMultipleSelect()) {
            return $this->gradeMultipleSelectSlots($userAnswer);
        }

        if ($this->usesCompletionAnswerGroups()) {
            $expected = $this->normalizeCompletionAnswerGroups($this->correct_answer_array);
            return $this->gradeOrderedSlots($expected, $userAnswer);
        }

        if ($this->isDragDropType()) {
            return $this->gradeOrderedSlots($this->expectedOrderedList(), $userAnswer);
        }

        // 1 ô: MCQ 1 đáp án, True/False/NG, Yes/No/NG, Matching, loại khác.
        $accepted = $this->flattenAnswerValues($this->correct_answer_array);
        $submitted = is_array($userAnswer)
            ? ($this->flattenAnswerValues($userAnswer)[0] ?? null)
            : $userAnswer;
        $submitted = $submitted === null ? null : trim((string) $submitted);

        return [$this->makeSlot($submitted === '' ? null : $submitted, $accepted)];
    }

    /** Số câu (số thứ tự) mà dòng câu hỏi này chiếm. Tối thiểu 1. */
    public function slotCount(): int
    {
        if (!$this->auto_gradable) {
            return 1; // Writing / Speaking
        }

        return max(1, count($this->gradeSlots(null)));
    }

    /** So 1 giá trị học viên với danh sách đáp án chấp nhận được. */
    private function makeSlot(?string $submitted, array $accepted): array
    {
        $accepted = array_values($accepted);
        $correct = false;

        if ($submitted !== null && $submitted !== '') {
            $needle = $this->normalizeAnswer($submitted);
            foreach ($accepted as $variant) {
                if ($needle === $this->normalizeAnswer((string) $variant)) {
                    $correct = true;
                    break;
                }
            }
        }

        return ['submitted' => $submitted, 'accepted' => $accepted, 'correct' => $correct];
    }

    /**
     * Completion / drag & drop: so THEO VỊ TRÍ. Giữ nguyên ô rỗng để ô sau
     * không bị dồn lên (bug cũ của normalizeSubmittedCompletionAnswers()).
     */
    private function gradeOrderedSlots(array $expected, $userAnswer): array
    {
        $submitted = $this->parseSubmittedOrderedAnswers($userAnswer);
        $slots = [];

        foreach (array_values($expected) as $index => $accepted) {
            $value = isset($submitted[$index]) ? trim((string) $submitted[$index]) : '';
            $slots[] = $this->makeSlot($value === '' ? null : $value, (array) $accepted);
        }

        return $slots;
    }

    /** Đáp án đúng của drag & drop dạng danh sách có thứ tự (mảng / JSON / "a|b" / xuống dòng). */
    private function expectedOrderedList(): array
    {
        $raw = $this->correct_answer_array;

        if (is_array($raw) && count($raw) === 1 && is_string(reset($raw))) {
            $raw = reset($raw);
        }

        if (is_array($raw)) {
            return array_values(array_map(fn ($value) => $this->flattenAnswerValues($value), $raw));
        }

        $text = trim((string) $raw);
        if ($text === '') {
            return [];
        }

        $parts = preg_split('/\s*\|\s*|\r\n|\r|\n/', $text);

        return array_values(array_filter(array_map(
            fn ($part) => $this->flattenAnswerValues($part),
            $parts
        )));
    }

    /**
     * Table completion: 1 slot = 1 Ô có chỗ trống (khớp cách renderers.js
     * đánh số: mỗi ô 1 số). Ô đúng khi MỌI blank trong ô đúng.
     */
    private function gradeTableSlots($userAnswer): array
    {
        $decoded = is_string($userAnswer) ? json_decode($userAnswer, true) : $userAnswer;
        $userMap = [];

        foreach ((is_array($decoded) ? ($decoded['answers'] ?? []) : []) as $cell) {
            if (!is_array($cell) || !isset($cell['row'], $cell['col'])) {
                continue;
            }

            $key = $cell['row'] . '-' . $cell['col'];

            if (isset($cell['answers']) && is_array($cell['answers'])) {
                $userMap[$key] = array_values($cell['answers']);
            } elseif (isset($cell['answer'])) {
                $userMap[$key] = [$cell['answer']];
            }
        }

        $cells = $this->table_completion_answers_array ?: [];
        usort($cells, fn ($a, $b) => [(int) $a['row'], (int) $a['col']] <=> [(int) $b['row'], (int) $b['col']]);

        $slots = [];
        foreach ($cells as $cell) {
            $key = $cell['row'] . '-' . $cell['col'];
            $parts = [];

            foreach (array_values((array) ($cell['answers'] ?? [])) as $blankIndex => $variants) {
                $value = isset($userMap[$key][$blankIndex]) ? trim((string) $userMap[$key][$blankIndex]) : '';
                $parts[] = $this->makeSlot($value === '' ? null : $value, $this->flattenAnswerValues($variants));
            }

            $filled = array_values(array_filter(array_column($parts, 'submitted'), fn ($v) => $v !== null));

            $slots[] = [
                'cell' => $key,
                'submitted' => $filled ? implode(' / ', $filled) : null,
                'accepted' => array_map(fn ($part) => implode(' / ', $part['accepted']), $parts),
                'correct' => !empty($parts) && !in_array(false, array_column($parts, 'correct'), true),
                'parts' => $parts,
            ];
        }

        return $slots;
    }

    /**
     * Multiple choice nhiều đáp án ("Choose TWO"): mỗi đáp án đúng = 1 slot.
     * Chọn dư so với số đáp án yêu cầu -> trừ đi số lựa chọn dư, tránh học
     * viên tick hết để ăn điểm.
     */
    private function gradeMultipleSelectSlots($userAnswer): array
    {
        $correct = $this->flattenAnswerValues($this->correct_answer_array);
        if (count($correct) === 1 && str_contains($correct[0], ',')) {
            $correct = array_values(array_filter(array_map('trim', explode(',', $correct[0]))));
        }

        $picked = $userAnswer;
        if (is_string($picked)) {
            $decoded = json_decode($picked, true);
            $picked = is_array($decoded) ? $decoded : array_map('trim', explode(',', $picked));
        }

        $pickedNorm = array_values(array_unique(array_map(
            fn ($value) => $this->normalizeAnswer($value),
            $this->flattenAnswerValues($picked)
        )));

        $slots = [];
        foreach ($correct as $option) {
            $hit = in_array($this->normalizeAnswer($option), $pickedNorm, true);
            $slots[] = ['submitted' => $hit ? $option : null, 'accepted' => [$option], 'correct' => $hit];
        }

        $extra = max(0, count($pickedNorm) - count($correct));
        for ($i = count($slots) - 1; $i >= 0 && $extra > 0; $i--) {
            if ($slots[$i]['correct']) {
                $slots[$i]['correct'] = false;
                $extra--;
            }
        }

        return $slots;
    }

    /** Làm phẳng chuỗi / mảng / mảng lồng nhiều tầng thành danh sách chuỗi đã trim, bỏ rỗng. */
    private function flattenAnswerValues($value): array
    {
        if (!is_array($value)) {
            $text = trim((string) $value);
            return $text === '' ? [] : [$text];
        }

        $out = [];
        array_walk_recursive($value, function ($item) use (&$out) {
            $text = trim((string) $item);
            if ($text !== '') {
                $out[] = $text;
            }
        });

        return $out;
    }
    
    /**
     * Normalize answer for comparison
     */
    private function normalizeAnswer($answer)
    {
        if (!is_string($answer)) {
            return $answer;
        }
        
        $answer = trim($answer);
        
        if (!$this->case_sensitive) {
            $answer = strtolower($answer);
        }
        
        // Remove extra spaces
        $answer = preg_replace('/\s+/', ' ', $answer);
        
        return $answer;
    }

    /**
     * Matching — so sánh không phân biệt hoa/thường + khoảng trắng thừa
     * (qua normalizeAnswer), khớp đúng cách grading.js xử lý ở client preview.
     */
    private function checkMatchingAnswer($userAnswer, array $correctAnswers): bool
    {
        if (empty($correctAnswers)) {
            return false;
        }

        $normalizedUser = $this->normalizeAnswer(
            is_array($userAnswer) ? ($userAnswer[0] ?? null) : $userAnswer
        );

        foreach ($correctAnswers as $correctAnswer) {
            if ($normalizedUser === $this->normalizeAnswer($correctAnswer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Drag & drop — so khớp THEO TỪNG VỊ TRÍ (blank thứ idx phải khớp đáp án
     * thứ idx), khác với multiple choice (khớp "có mặt trong tập" không quan
     * tâm vị trí). $userAnswer kỳ vọng là mảng CÓ THỨ TỰ hoặc JSON mảng —
     * xem parseSubmittedOrderedAnswers().
     */
    private function checkDragDropAnswer($userAnswer, array $correctAnswers): bool
    {
        if (empty($correctAnswers)) {
            return false;
        }

        $submitted = $this->parseSubmittedOrderedAnswers($userAnswer);

        if (count($submitted) !== count($correctAnswers)) {
            return false;
        }

        foreach ($correctAnswers as $index => $correctAnswer) {
            $submittedValue = $submitted[$index] ?? null;

            if ($submittedValue === null) {
                return false;
            }

            if ($this->normalizeAnswer($submittedValue) !== $this->normalizeAnswer($correctAnswer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Chuẩn hoá answer_text của học viên thành mảng CÓ THỨ TỰ theo từng
     * blank. Chấp nhận: mảng PHP sẵn có, chuỗi JSON mảng, chuỗi phân tách
     * bằng "|", hoặc 1 giá trị đơn (coi là mảng 1 phần tử). Đây là HỢP ĐỒNG
     * (wire format) mà phía client (Lớp 4/5) phải tuân theo khi gửi answer_text
     * cho câu hỏi drag&drop: JSON.stringify(mảng string theo đúng thứ tự blank).
     */
    private function parseSubmittedOrderedAnswers($value): array
    {
        if (is_array($value)) {
            // Định dạng cũ (trước Lớp 4): { answers: [ {answer: "..."}, ... ] }
            if (isset($value['answers']) && is_array($value['answers'])) {
                $value = $value['answers'];
            }

            return array_values(array_map(function ($item) {
                if (is_array($item)) {
                    $first = $item['answer'] ?? ($item[0] ?? '');
                    return is_array($first)
                        ? ($this->flattenAnswerValues($first)[0] ?? '')
                        : (string) ($first ?? '');
                }

                return (string) ($item ?? '');
            }, $value));
        }

        if ($value === null) {
            return [];
        }

        $text = trim((string) $value);
        if ($text === '') {
            return [];
        }

        if (str_starts_with($text, '[') || str_starts_with($text, '{')) {
            $decoded = json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $this->parseSubmittedOrderedAnswers($decoded);
            }
        }

        if (str_contains($text, '|')) {
            return array_values(array_map('trim', explode('|', $text)));
        }

        return [$text];
    }

    private function normalizeTableAnswerVariants($answer)
    {
        if (is_array($answer)) {
            $variants = [];
            foreach ($answer as $value) {
                foreach ($this->normalizeTableAnswerVariants($value) as $variant) {
                    $variants[] = $variant;
                }
            }

            return array_values(array_filter($variants));
        }

        if ($answer === null || $answer === '') {
            return [];
        }

        if (is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $this->normalizeTableAnswerVariants($decoded);
            }

            if (str_contains($answer, '|')) {
                return array_values(array_filter(array_map('trim', explode('|', $answer))));
            }

            if (str_contains($answer, '/')) {
                return array_values(array_filter(array_map('trim', preg_split('/\s*\/\s*/', $answer))));
            }

            return [trim($answer)];
        }

        $text = trim((string) $answer);
        return $text !== '' ? [$text] : [];
    }

    private function usesCompletionAnswerGroups(): bool
    {
        return in_array($this->question_type, [
            'sentence_completion',
            'summary_completion',
            'note_completion',
            'diagram_labeling',
            'diagram_label',
            // Short Answer: 1 ô nhập, cho phép nhiều đáp án bằng "/".
            // Nhánh này đọc được cả đáp án cũ (chuỗi) lẫn mới (mảng).
            'short_answer',
        ], true);
    }

    private function normalizeCompletionAnswerGroups($value): array
    {
        if (is_array($value)) {
            if ($value === []) {
                return [];
            }

            $hasNestedArrays = false;
            foreach ($value as $item) {
                if (is_array($item)) {
                    $hasNestedArrays = true;
                    break;
                }
            }

            if ($hasNestedArrays) {
                $groups = [];
                foreach ($value as $group) {
                    $variants = $this->normalizeCompletionAnswerVariants($group);
                    if (!empty($variants)) {
                        $groups[] = $variants;
                    }
                }

                return $groups;
            }

            $groups = [];
            foreach ($value as $item) {
                $variants = $this->normalizeCompletionAnswerVariants($item);
                if (!empty($variants)) {
                    $groups[] = [$variants[0]];
                }
            }

            return $groups;
        }

        if ($value === null) {
            return [];
        }

        $text = trim((string) $value);
        if ($text === '') {
            return [];
        }

        if (str_contains($text, '|')) {
            $groups = [];
            foreach (explode('|', $text) as $part) {
                $variants = $this->normalizeCompletionAnswerVariants($part);
                if (!empty($variants)) {
                    $groups[] = [$variants[0]];
                }
            }

            return $groups;
        }

        if (str_contains($text, "\n") || str_contains($text, "\r")) {
            $groups = [];
            foreach (preg_split('/\r\n|\r|\n/', $text) as $part) {
                $variants = $this->normalizeCompletionAnswerVariants($part);
                if (!empty($variants)) {
                    $groups[] = [$variants[0]];
                }
            }

            return $groups;
        }

        $variants = $this->normalizeCompletionAnswerVariants($text);
        return !empty($variants) ? [$variants] : [];
    }

    private function normalizeCompletionAnswerVariants($value): array
    {
        if (is_array($value)) {
            return $this->flattenAnswerValues($value);
        }
        $text = trim((string) $value);
        if ($text === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\s*\/\s*/', $text))));
    }

    private function normalizeSubmittedCompletionAnswers($value): array
    {
        if (is_array($value)) {
            $groups = [];
            foreach ($value as $item) {
                if (is_array($item)) {
                    $variants = array_values(array_filter(array_map(function ($subItem) {
                        return $this->normalizeAnswer($subItem);
                    }, $item)));
                } else {
                    $normalized = $this->normalizeAnswer($item);
                    $variants = $normalized !== '' ? [$normalized] : [];
                }

                if (!empty($variants)) {
                    $groups[] = $variants;
                }
            }

            return $groups;
        }

        if ($value === null) {
            return [];
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return [];
            }

            if (str_starts_with($trimmed, '[') || str_starts_with($trimmed, '{')) {
                $decoded = json_decode($trimmed, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $this->normalizeSubmittedCompletionAnswers($decoded);
                }
            }

            if (str_contains($trimmed, '|')) {
                $groups = [];
                foreach (explode('|', $trimmed) as $part) {
                    $normalized = $this->normalizeAnswer($part);
                    if ($normalized !== '') {
                        $groups[] = [$normalized];
                    }
                }

                return $groups;
            }

            if (str_contains($trimmed, "\n") || str_contains($trimmed, "\r")) {
                $groups = [];
                foreach (preg_split('/\r\n|\r|\n/', $trimmed) as $part) {
                    $normalized = $this->normalizeAnswer($part);
                    if ($normalized !== '') {
                        $groups[] = [$normalized];
                    }
                }

                return $groups;
            }

            $normalized = $this->normalizeAnswer($trimmed);
            return $normalized !== '' ? [[$normalized]] : [];
        }

        $normalized = $this->normalizeAnswer((string) $value);
        return $normalized !== '' ? [[$normalized]] : [];
    }
    
    /**
     * Validate word count for fill-in-blank
     */
    public function validateWordCount($answer)
    {
        if (!$this->max_words) {
            return true;
        }
        
        $wordCount = str_word_count($answer);
        return $wordCount <= $this->max_words;
    }
}
