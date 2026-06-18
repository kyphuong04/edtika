<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class QuizzesQuestion extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'quizzes_questions';
    public $timestamps = false;
    protected $guarded = ['id'];

    static $multiple = 'multiple';
    static $descriptive = 'descriptive';
    static $fillBlank = 'fill_blank';
    static $rewriteSentence = 'rewrite_sentence';
    static $trueFalseNotGiven = 'true_false_not_given';
    static $yesNoNotGiven = 'yes_no_not_given';
    static $matchingHeadings = 'matching_headings';
    static $matchingInformation = 'matching_information';
    static $matchingFeatures = 'matching_features';
    static $matchingSentenceEndings = 'matching_sentence_endings';
    static $sentenceCompletion = 'sentence_completion';
    static $shortAnswer = 'short_answer';

    public $translatedAttributes = ['title', 'correct'];

    protected $casts = [
        'question_data' => 'array',
    ];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getCorrectAttribute()
    {
        return getTranslateAttributeValue($this, 'correct');
    }

    public function getTypeLabelAttribute()
    {
        switch ($this->type) {
            case self::$multiple:
                return trans('quiz.multiple_choice');
            case self::$descriptive:
                return trans('quiz.descriptive');
            case self::$fillBlank:
                return trans('quiz.fill_blank');
            case self::$rewriteSentence:
                return trans('quiz.rewrite_sentence');
            case self::$trueFalseNotGiven:
                return 'True / False / Not Given';
            case self::$yesNoNotGiven:
                return 'Yes / No / Not Given';
            case self::$matchingHeadings:
                return 'Matching Headings';
            case self::$matchingInformation:
                return 'Matching Information';
            case self::$matchingFeatures:
                return 'Matching Features';
            case self::$matchingSentenceEndings:
                return 'Matching Sentence Endings';
            case self::$sentenceCompletion:
                return 'Sentence Completion';
            case self::$shortAnswer:
                return 'Short Answer';
            default:
                return ucfirst(str_replace('_', ' ', (string) $this->type));
        }
    }

    public function getPromptTextAttribute()
    {
        return data_get($this->question_data, 'prompt');
    }

    public function isTextQuestion()
    {
        return in_array($this->type, [self::$fillBlank, self::$rewriteSentence, self::$sentenceCompletion, self::$shortAnswer]);
    }

    public function isChoiceQuestion()
    {
        return $this->type === self::$multiple;
    }

    public function isMatchingQuestion()
    {
        return in_array($this->type, [self::$matchingHeadings, self::$matchingInformation, self::$matchingFeatures, self::$matchingSentenceEndings]);
    }

    public function isAutoGradableQuestion()
    {
        return $this->isChoiceQuestion() || $this->isTextQuestion() || $this->isMatchingQuestion();
    }

    public function requiresManualReview()
    {
        if ($this->type === self::$descriptive) {
            return true;
        }

        if ($this->isTextQuestion()) {
            return data_get($this->question_data, 'grading_mode', 'auto') === 'manual';
        }

        return false;
    }

    public function getAcceptedAnswerGroupsAttribute()
    {
        $acceptedAnswers = data_get($this->question_data, 'accepted_answers');

        if (is_array($acceptedAnswers)) {
            $groups = [];

            foreach ($acceptedAnswers as $group) {
                if (is_array($group)) {
                    $variants = [];

                    foreach ($group as $variant) {
                        $normalized = $this->normalizeQuizAnswer($variant);

                        if ($normalized !== '') {
                            $variants[] = $normalized;
                        }
                    }

                    if (!empty($variants)) {
                        $groups[] = array_values(array_unique($variants));
                    }
                } else {
                    $variants = $this->extractAnswerVariants((string) $group);

                    if (!empty($variants)) {
                        $groups[] = $variants;
                    }
                }
            }

            return $groups;
        }

        if (is_string($acceptedAnswers)) {
            $lines = preg_split('/\r\n|\r|\n/', trim($acceptedAnswers)) ?: [];
            $groups = [];

            foreach ($lines as $line) {
                $variants = $this->extractAnswerVariants($line);

                if (!empty($variants)) {
                    $groups[] = $variants;
                }
            }

            return $groups;
        }

        $correct = trim((string) $this->correct);

        if ($correct !== '') {
            return [$this->extractAnswerVariants($correct)];
        }

        return [];
    }

    public function gradeSubmittedAnswer($submittedAnswer)
    {
        if (in_array($this->type, [self::$trueFalseNotGiven, self::$yesNoNotGiven])) {
            $correctAnswer = $this->normalizeQuizAnswer(data_get($this->question_data, 'correct_answer', ''));
            $submittedValue = $this->normalizeQuizAnswer($submittedAnswer);

            $isCorrect = $correctAnswer !== '' && $submittedValue === $correctAnswer;

            return [
                'is_correct' => $isCorrect,
                'manual_review' => false,
                'score' => $isCorrect ? (int) $this->grade : 0,
            ];
        }

        if ($this->isChoiceQuestion()) {
            if (is_array($submittedAnswer)) {
                $submittedAnswer = array_values(array_filter($submittedAnswer, function ($value) {
                    return $value !== null && $value !== '';
                }));
            }

            $correctAnswers = $this->quizzesQuestionsAnswers()->where('correct', true)->pluck('id')->map(function ($value) {
                return (string) $value;
            })->values()->all();

            if ($this->type === self::$multiple && !empty($correctAnswers)) {
                $submittedIds = is_array($submittedAnswer) ? array_map('strval', $submittedAnswer) : [(string) $submittedAnswer];

                sort($submittedIds);
                sort($correctAnswers);

                $isCorrect = $submittedIds === $correctAnswers;

                return [
                    'is_correct' => $isCorrect,
                    'manual_review' => false,
                    'score' => $isCorrect ? (int) $this->grade : 0,
                ];
            }

            $answer = $this->quizzesQuestionsAnswers()->where('id', $submittedAnswer)->first();

            return [
                'is_correct' => !empty($answer) && (bool) $answer->correct,
                'manual_review' => false,
                'score' => !empty($answer) && (bool) $answer->correct ? (int) $this->grade : 0,
            ];
        }

        if ($this->isMatchingQuestion()) {
            $submittedGroups = $this->normalizeSubmittedAnswerGroups($submittedAnswer);
            $correctGroups = $this->normalizeMatchingCorrectGroups();

            if (empty($submittedGroups) || empty($correctGroups) || count($submittedGroups) !== count($correctGroups)) {
                return [
                    'is_correct' => false,
                    'manual_review' => false,
                    'score' => 0,
                ];
            }

            $isCorrect = true;

            foreach ($correctGroups as $index => $acceptedVariants) {
                $submittedValue = $submittedGroups[$index] ?? null;

                if ($submittedValue === null || !$this->groupHasMatch($submittedValue, $acceptedVariants)) {
                    $isCorrect = false;
                    break;
                }
            }

            return [
                'is_correct' => $isCorrect,
                'manual_review' => false,
                'score' => $isCorrect ? (int) $this->grade : 0,
            ];
        }

        if ($this->requiresManualReview()) {
            return [
                'is_correct' => false,
                'manual_review' => true,
                'score' => 0,
            ];
        }

        $submittedGroups = $this->normalizeSubmittedAnswerGroups($submittedAnswer);
        $acceptedGroups = $this->accepted_answer_groups;

        if (empty($submittedGroups) || empty($acceptedGroups)) {
            return [
                'is_correct' => false,
                'manual_review' => false,
                'score' => 0,
            ];
        }

        $isCorrect = false;

        if (count($acceptedGroups) === 1) {
            $isCorrect = $this->groupHasMatch($submittedGroups[0], $acceptedGroups[0]);
        } else {
            $isCorrect = count($submittedGroups) === count($acceptedGroups);

            if ($isCorrect) {
                foreach ($acceptedGroups as $index => $acceptedVariants) {
                    $submittedValue = $submittedGroups[$index] ?? null;

                    if ($submittedValue === null || !$this->groupHasMatch($submittedValue, $acceptedVariants)) {
                        $isCorrect = false;
                        break;
                    }
                }
            }
        }

        return [
            'is_correct' => $isCorrect,
            'manual_review' => false,
            'score' => $isCorrect ? (int) $this->grade : 0,
        ];
    }

    protected function normalizeSubmittedAnswerGroups($submittedAnswer)
    {
        if (is_array($submittedAnswer)) {
            $groups = [];

            foreach ($submittedAnswer as $value) {
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }

                $normalized = $this->normalizeQuizAnswer($value);

                if ($normalized !== '') {
                    $groups[] = $normalized;
                }
            }

            return $groups;
        }

        $text = trim((string) $submittedAnswer);

        if ($text === '') {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];

        if (count($lines) > 1) {
            $groups = [];

            foreach ($lines as $line) {
                $normalized = $this->normalizeQuizAnswer($line);

                if ($normalized !== '') {
                    $groups[] = $normalized;
                }
            }

            return $groups;
        }

        return [$this->normalizeQuizAnswer($text)];
    }

    protected function extractAnswerVariants($value)
    {
        $variants = preg_split('/\|/', (string) $value) ?: [];
        $normalizedVariants = [];

        foreach ($variants as $variant) {
            $normalized = $this->normalizeQuizAnswer($variant);

            if ($normalized !== '') {
                $normalizedVariants[] = $normalized;
            }
        }

        return array_values(array_unique($normalizedVariants));
    }

    protected function normalizeMatchingCorrectGroups()
    {
        $correctAnswers = data_get($this->question_data, 'correct_answers');

        if (is_array($correctAnswers)) {
            $groups = [];

            foreach ($correctAnswers as $value) {
                $variants = $this->extractAnswerVariants((string) $value);

                if (!empty($variants)) {
                    $groups[] = $variants;
                }
            }

            return $groups;
        }

        if (is_string($correctAnswers)) {
            $lines = preg_split('/\r\n|\r|\n/', trim($correctAnswers)) ?: [];
            $groups = [];

            foreach ($lines as $line) {
                $variants = $this->extractAnswerVariants($line);

                if (!empty($variants)) {
                    $groups[] = $variants;
                }
            }

            return $groups;
        }

        return [];
    }

    protected function groupHasMatch($submittedValue, array $variants)
    {
        $submittedValue = $this->normalizeQuizAnswer($submittedValue);

        foreach ($variants as $variant) {
            if ($submittedValue === $variant) {
                return true;
            }
        }

        return false;
    }

    protected function normalizeQuizAnswer($value)
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        $value = preg_replace('/\s+/u', ' ', $value);

        if (data_get($this->question_data, 'case_sensitive', false)) {
            return $value;
        }

        return mb_strtolower($value);
    }

    public static function allowedTypes()
    {
        return [
            self::$multiple,
            self::$descriptive,
            self::$fillBlank,
            self::$rewriteSentence,
            self::$trueFalseNotGiven,
            self::$yesNoNotGiven,
            self::$matchingHeadings,
            self::$matchingInformation,
            self::$matchingFeatures,
            self::$matchingSentenceEndings,
            self::$sentenceCompletion,
            self::$shortAnswer,
        ];
    }


    public function quizzesQuestionsAnswers()
    {
        return $this->hasMany('App\Models\QuizzesQuestionsAnswer', 'question_id', 'id');
    }


    public function canAccessToEdit($user = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $result = false;

        if (!empty($user)) {
            $quiz = Quiz::find($this->quiz_id);

            $webinar = null;
            if (!empty($quiz->webinar_id)) {
                $webinar = Webinar::query()->find($quiz->webinar_id);
            }

            if ($quiz->creator_id != $user->id and (!empty($webinar) and $webinar->canAccess($user))) {
                $quiz = null;
            }


            if (!empty($quiz)) {
                $result = true;
            }
        }

        return $result;
    }
}


