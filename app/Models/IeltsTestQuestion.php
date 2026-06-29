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

            $variants = $this->normalizeTableAnswerVariants(
                $answerItem['answers'] ?? $answerItem['answer'] ?? []
            );

            if (empty($variants)) {
                continue;
            }

            $answers[] = [
                'row' => (int) $answerItem['row'],
                'col' => (int) $answerItem['col'],
                'answers' => $variants,
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

        // Special handling for table completion
        if ($this->question_type === 'table_completion') {
            $expected = [];
            foreach ($this->table_completion_answers_array as $a) {
                $expected["{$a['row']}-{$a['col']}"] = $a['answers'];
            }

            // Parse userAnswer: accept JSON string or array with answers
            $userMap = [];
            if (is_string($userAnswer)) {
                $decoded = json_decode($userAnswer, true);
                if (is_array($decoded)) {
                    if (!empty($decoded['answers']) && is_array($decoded['answers'])) {
                        foreach ($decoded['answers'] as $a) {
                            if (isset($a['row']) && isset($a['col']) && isset($a['answer'])) {
                                $userMap["{$a['row']}-{$a['col']}"] = $a['answer'];
                            }
                        }
                    }
                }
            } elseif (is_array($userAnswer)) {
                if (!empty($userAnswer['answers']) && is_array($userAnswer['answers'])) {
                    foreach ($userAnswer['answers'] as $a) {
                        if (isset($a['row']) && isset($a['col']) && isset($a['answer'])) {
                            $userMap["{$a['row']}-{$a['col']}"] = $a['answer'];
                        }
                    }
                }
            }

            if (empty($expected)) {
                return false;
            }

            foreach ($expected as $key => $correctAnswers) {
                $userAns = $userMap[$key] ?? null;
                if ($userAns === null) {
                    return false;
                }

                $matched = false;
                foreach ((array) $correctAnswers as $correctAnswer) {
                    if ($this->normalizeAnswer($userAns) === $this->normalizeAnswer($correctAnswer)) {
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
