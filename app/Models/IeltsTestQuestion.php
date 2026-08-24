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
        if (is_string($this->correct_answer)) {
            return json_decode($this->correct_answer, true) ?? [$this->correct_answer];
        }
        return $this->correct_answer;
    }

    public function getFormattedCorrectAnswerAttribute()
    {
        if ($this->usesCompletionAnswerGroups()) {
            $groups = $this->normalizeCompletionAnswerGroups($this->correct_answer_array);

            return implode(' | ', array_map(static function (array $variants) {
                return implode(' / ', $variants);
            }, $groups));
        }

        if (is_array($this->correct_answer_array)) {
            return implode(', ', array_map(static function ($value) {
                return is_array($value) ? implode(' / ', $value) : (string) $value;
            }, $this->correct_answer_array));
        }

        return $this->correct_answer;
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
    
    /**
     * Check if answer is correct
     */
    public function checkAnswer($userAnswer)
    {
        if (!$this->auto_gradable) {
            return null; // Requires manual grading
        }
        
        $correctAnswers = $this->correct_answer_array;

        // If no correct answer is stored, cannot grade
        if (empty($correctAnswers)) {
            return false;
        }

        // Ensure it's an array
        if (!is_array($correctAnswers)) {
            $correctAnswers = [$correctAnswers];
        }

        // SAU
        if ($this->usesCompletionAnswerGroups()) {
            $expectedGroups = $this->normalizeCompletionAnswerGroups($correctAnswers);
            $submittedGroups = $this->normalizeSubmittedCompletionAnswers($userAnswer);

            if (empty($expectedGroups) || count($expectedGroups) !== count($submittedGroups)) {
                return false;
            }

            foreach ($expectedGroups as $index => $variants) {
                $submittedValue = $submittedGroups[$index][0] ?? null;
                if ($submittedValue === null) {
                    return false;
                }

                $matched = false;
                foreach ($variants as $variant) {
                    if ($submittedValue === $this->normalizeAnswer($variant)) {
                        $matched = true;
                        break;
                    }
                }

                if (!$matched) {
                    return false;
                }
            }

            return true;
        }

        // Matching (headings/information/features/sentence_endings): mỗi
        // dòng IeltsTestQuestion ứng với 1 statement, đáp án đúng là 1 giá
        // trị đơn (key cột, VD "A"). Trước patch này nó rơi vào nhánh so
        // sánh chung ở cuối hàm và TÌNH CỜ vẫn đúng (vì đáp án chỉ có 1
        // giá trị) — tách riêng để không phụ thuộc ngầm vào hành vi đó.
        if ($this->isMatchingType()) {
            return $this->checkMatchingAnswer($userAnswer, $correctAnswers);
        }

        // Drag & drop (disappear/reuse): nhiều blank trong 1 câu, đáp án
        // đúng lưu MẢNG CÓ THỨ TỰ (correct_answer_array[idx] = đáp án của
        // blank thứ idx). Trước patch này nó cũng rơi vào nhánh so sánh
        // chung — nhưng nhánh đó dùng logic "khớp BẤT KỲ phần tử nào"
        // (đúng cho multiple choice) chứ không phải "khớp ĐÚNG VỊ TRÍ"
        // (cần cho drag&drop) -> gần như luôn chấm sai. Đây là bug thật.
        if ($this->isDragDropType()) {
            return $this->checkDragDropAnswer($userAnswer, $correctAnswers);
        }
        
        // Normalize answer
        $userAnswer = $this->normalizeAnswer($userAnswer);
        
        // For multiple select, check all selections
        if ($this->isMultipleSelect()) {
            $userSelections = is_array($userAnswer) ? $userAnswer : json_decode($userAnswer, true);
            if (!is_array($userSelections)) {
                return false;
            }
            sort($userSelections);
            sort($correctAnswers);
            return $userSelections === $correctAnswers;
        }
        
        // For single answer
        foreach ($correctAnswers as $correctAnswer) {
            $normalizedCorrect = $this->normalizeAnswer($correctAnswer);
            if ($userAnswer === $normalizedCorrect) {
                return true;
            }
        }

        if ($this->question_type === 'table_completion') {
            $expected = [];
            foreach ($this->table_completion_answers_array as $a) {
                // $a['answers'] giờ là mảng THEO THỨ TỰ BLANK, mỗi phần tử
                // là mảng biến thể chấp nhận được cho blank đó.
                $expected["{$a['row']}-{$a['col']}"] = $a['answers'];
            }

            if (empty($expected)) {
                return false;
            }

            // userAnswer kỳ vọng: JSON { answers: [{ row, col, answers: [v0, v1, ...] }] }
            // answers[i] = mảng theo thứ tự blank trong cell. Vẫn chấp nhận
            // dạng cũ { answer: "..." } (1 blank) để tương thích ngược.
            $decoded = is_string($userAnswer) ? json_decode($userAnswer, true) : $userAnswer;
            $userMap = [];

            if (is_array($decoded) && !empty($decoded['answers']) && is_array($decoded['answers'])) {
                foreach ($decoded['answers'] as $a) {
                    if (!isset($a['row']) || !isset($a['col'])) {
                        continue;
                    }

                    $key = "{$a['row']}-{$a['col']}";

                    if (isset($a['answers']) && is_array($a['answers'])) {
                        $userMap[$key] = $a['answers'];
                    } elseif (isset($a['answer'])) {
                        $userMap[$key] = [$a['answer']];
                    }
                }
            }

            foreach ($expected as $key => $expectedPerBlank) {
                $submittedPerBlank = $userMap[$key] ?? null;

                if ($submittedPerBlank === null || count($submittedPerBlank) !== count($expectedPerBlank)) {
                    return false;
                }

                foreach ($expectedPerBlank as $blankIndex => $acceptedVariants) {
                    $submittedValue = $submittedPerBlank[$blankIndex] ?? null;

                    if ($submittedValue === null) {
                        return false;
                    }

                    $matched = false;
                    foreach ((array) $acceptedVariants as $variant) {
                        if ($this->normalizeAnswer($submittedValue) === $this->normalizeAnswer($variant)) {
                            $matched = true;
                            break;
                        }
                    }

                    if (!$matched) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
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
            return array_values(array_map(
                static fn ($item) => is_array($item) ? ($item[0] ?? '') : (string) $item,
                $value
            ));
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
        return in_array($this->question_type, ['sentence_completion', 'summary_completion', 'note_completion', 'diagram_labeling', 'diagram_label'], true);
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
            return array_values(array_filter(array_map(function ($item) {
                return trim((string) $item);
            }, $value)));
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
