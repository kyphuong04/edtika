<?php

namespace App\Support\Ielts;

use App\Models\IeltsTestPart;
use App\Models\IeltsTestQuestion;
use App\Models\IeltsTestSection;
use Illuminate\Support\Facades\Storage;

/**
 * Nguồn sự thật DUY NHẤT cho việc build payload câu hỏi IELTS ra JSON —
 * bao gồm cả việc tính slotCount (số thứ tự câu hỏi mà 1 IeltsTestQuestion
 * chiếm, ví dụ 1 table_completion có 4 blank -> slotCount = 4).
 *
 * Trước trait này, logic "đếm blank" bị viết tay riêng lẻ ở nhiều nơi
 * (form tạo đề JS, IeltsTestInlineController, take_idp.blade.php,
 * idp_questions_panel.blade.php) và dễ lệch nhau -> số thứ tự câu hỏi hiển
 * thị sai giữa các màn hình. Mọi nơi cần build/hiển thị dữ liệu câu hỏi từ
 * DB (preview cho giáo viên, trang làm bài thật cho học viên) PHẢI dùng
 * trait này thay vì tự viết lại.
 *
 * Dùng bởi:
 *  - IeltsTestInlineController (giáo viên tạo/sửa/preview đề)
 *  - IeltsTestController (học viên làm bài thật) — xem attemptSectionData()
 */
trait BuildsIeltsQuestionPayload
{
    /**
     * Build dữ liệu đầy đủ của 1 Section (parts -> groups -> questions),
     * bao gồm cả đáp án đúng. Dùng cho giáo viên (preview/edit). KHÔNG
     * dùng thẳng cho học viên — phải qua stripSectionAnswers() trước.
     */
    public function buildSectionData(IeltsTestSection $section): array
    {
        $section->loadMissing(['parts.questionGroups', 'questions']);

        $partsPayload = [];
        $questionsByPart = $section->questions->groupBy('part_id');

        foreach ($section->parts as $part) {
            $partQuestions = $questionsByPart->get($part->id, collect());

            $partEntry = [
                'id' => $part->id,
                'title' => $part->title ?: ('Part ' . $part->sort_order),
                // Số part thật (1/2/3) — UI Speaking cần để biết thời gian
                // chuẩn bị / trả lời của từng part.
                'part_number' => $this->resolvePartNumber($part, count($partsPayload)),
                'instructions' => $part->instructions,
                'passage' => $part->passage,
                'transcript' => $part->transcript,
                'files' => [
                    'audio' => $part->audio_file ?: null,
                    'image' => $part->task_image ?: null,
                    'video' => $part->video_file ?: null,
                ],
                'groups' => [],
            ];

            $groups = $part->questionGroups;
            if ($groups->isEmpty() && $partQuestions->isNotEmpty()) {
                // Đề cũ / dữ liệu legacy chưa có question group tường minh
                // -> coi cả Part là 1 group ảo, giữ hành vi tương thích
                // ngược với buildInlineTestData() gốc.
                $groups = collect([(object) [
                    'id' => null,
                    'title' => $part->title ?: ('Part ' . $part->sort_order),
                    'question_type' => $partQuestions->first()->question_type ?? 'short_answer',
                    'max_words' => null,
                    'target_band' => null,
                    'passage' => $part->passage,
                    'task_image' => $part->task_image,
                ]]);
            }

            foreach ($groups as $group) {
                $groupQuestions = $group->id
                    ? $partQuestions->where('question_group_id', $group->id)
                    : $partQuestions;

                $partEntry['groups'][] = [
                    'id' => $group->id,
                    'title' => $group->title ?: $partEntry['title'],
                    'question_type' => $this->normalizeQuestionType(
                        $group->question_type ?? ($groupQuestions->first()->question_type ?? 'short_answer')
                    ),
                    'max_words' => $group->max_words,
                    'target_band' => $group->target_band,
                    'passage' => $group->passage ?: $part->passage,
                    'task_image' => $group->task_image ?: $part->task_image,
                    'files' => [
                        'audio' => $group->audio_path ?? $group->audio_file ?? null,
                        'image' => $group->task_image ?? null,
                        'video' => $group->video_file ?? null,
                    ],
                    'questions' => $groupQuestions->sortBy('question_number')
                        ->map(fn (IeltsTestQuestion $question) => $this->buildQuestionPayload($question))
                        ->values()
                        ->all(),
                ];
            }

            $partsPayload[] = $partEntry;
        }

        return [
            'skill' => $section->skill,
            'files' => [
                // Dùng raw attribute (chưa resolve URL) — audio dùng chung
                // cho cả section (VD Listening 1 file cho 4 part) đã có
                // accessor fallback sang question group trong
                // IeltsTestSection::getAudioFileAttribute().
                'audio' => $section->audio_file ?? null,
                'image' => $section->image_file ?? null,
                'video' => $section->video_file ?? null,
            ],
            'parts' => $partsPayload,
        ];
    }

    /**
     * Số part thật: ưu tiên chữ số trong title ("Part 2" -> 2), sau đó
     * sort_order, cuối cùng là vị trí trong danh sách (0-based -> +1).
     */
    public function resolvePartNumber($part, int $index): int
    {
        $title = (string) ($part->title ?? '');
        if (preg_match('/(\d+)/', $title, $matches)) {
            $fromTitle = (int) $matches[1];
            if ($fromTitle > 0) {
                return $fromTitle;
            }
        }

        $sortOrder = (int) ($part->sort_order ?? 0);
        if ($sortOrder > 0) {
            return $sortOrder;
        }

        return $index + 1;
    }

    /**
     * Build payload đầy đủ (kèm đáp án đúng) cho 1 câu hỏi. Đây là nơi DUY
     * NHẤT tính slotCount — mọi UI hiển thị số thứ tự câu hỏi phải đọc giá
     * trị này thay vì tự đếm lại.
     */
    public function buildQuestionPayload(IeltsTestQuestion $question): array
    {
        $questionType = $this->normalizeQuestionType($question->question_type ?? 'short_answer');
        $answerOptions = $question->answer_options ?? [];
        $correctAnswer = $question->correct_answer;
        $correctAnswers = null;
        $correctAnswerGroups = null;

        if (is_string($correctAnswer)) {
            $decodedCorrect = json_decode($correctAnswer, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $correctAnswer = $decodedCorrect;
            }
        }

        if (in_array($questionType, ['note_completion', 'sentence_completion', 'summary_completion', 'diagram_labeling'], true)) {
            $correctAnswerGroups = $this->normalizeCompletionAnswerGroups($correctAnswer);
            $correctAnswers = array_map(
                static fn (array $group) => implode(' / ', $group),
                $correctAnswerGroups
            );
        } elseif (is_array($correctAnswer) && in_array($questionType, [
            'multiple_choice_multiple',
            'drag_drop_disappear',
            'drag_drop_reuse',
        ], true)) {
            $correctAnswers = array_values(array_filter(array_map('trim', $correctAnswer)));
        } elseif (is_string($correctAnswer) && $questionType === 'multiple_choice_multiple') {
            $correctAnswers = array_values(array_filter(array_map('trim', explode(',', $correctAnswer))));
        } elseif (is_string($correctAnswer) && in_array($questionType, [
            'drag_drop_disappear',
            'drag_drop_reuse',
        ], true)) {
            $trimmedAnswer = trim($correctAnswer);

            if ($trimmedAnswer !== '') {
                if (str_contains($trimmedAnswer, '|')) {
                    $correctAnswers = array_values(array_filter(array_map('trim', explode('|', $trimmedAnswer))));
                } elseif (str_contains($trimmedAnswer, "\n") || str_contains($trimmedAnswer, "\r")) {
                    $correctAnswers = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $trimmedAnswer))));
                } else {
                    $correctAnswers = [$trimmedAnswer];
                }
            }
        }

        $questionData = $question->question_data;
        if (is_string($questionData)) {
            $decodedQuestionData = json_decode($questionData, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $questionData = $decodedQuestionData;
            }
        }

        $tableStructure = $question->table_structure;
        if (is_string($tableStructure)) {
            $decodedTableStructure = json_decode($tableStructure, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $tableStructure = $decodedTableStructure;
            }
        }

        $payload = [
            'id' => $question->id,
            'type' => $questionType,
            'title' => is_array($questionData) ? ($questionData['title'] ?? null) : null,
            'text' => $question->question_text,
            'instruction' => $question->instruction,
            'explanation' => $question->explanation,
            // Audio đọc sẵn câu hỏi (Speaking). Path thô — resolve URL ở
            // resolveSectionMediaUrls().
            'question_audio' => $question->question_audio ?: null,
            'hint' => $question->hint ?: null,
            'model_answer' => $this->resolveModelAnswer($question, $questionData),
            'points' => $question->points,
            'options' => is_array($answerOptions) ? $answerOptions : [],
            'correctAnswer' => $correctAnswer,
            'question_data' => is_array($questionData) ? $questionData : null,
            'table_structure' => is_array($tableStructure) ? $tableStructure : null,
            'slotCount' => 1,
        ];

        if ($correctAnswers !== null) {
            $payload['correctAnswers'] = $correctAnswers;
            $payload['slotCount'] = max(1, count($correctAnswers));
        }

        if ($correctAnswerGroups !== null) {
            $payload['correctAnswerGroups'] = $correctAnswerGroups;
        }

        // table_completion không đi qua nhánh correctAnswers/correctAnswerGroups
        // ở trên -> slotCount tính riêng = số cell có blank. Đây là điểm mà
        // trước đây bị bỏ sót ở bản build cho preview, và bị tính SAI KHÁC
        // NHAU giữa take_idp.blade.php và idp_questions_panel.blade.php khi
        // chưa gộp về đây.
        if ($questionType === 'table_completion' && is_array($tableStructure)) {
            $tableAnswerCount = count($tableStructure['answers'] ?? []);
            if ($tableAnswerCount > 0) {
                $payload['slotCount'] = $tableAnswerCount;
            }
        }

        return $payload;
    }

    /**
     * Model Answer (bài mẫu) của câu Speaking/Writing. Dữ liệu hiện có nằm ở
     * nhiều chỗ tuỳ đời đề: question_data.model_answer / sample_answer, hoặc
     * fallback về explanation (chỗ giáo viên hay gõ bài mẫu vào).
     */
    public function resolveModelAnswer(IeltsTestQuestion $question, $questionData): ?string
    {
        if (is_array($questionData)) {
            foreach (['model_answer', 'sample_answer', 'modelAnswer', 'sampleAnswer'] as $key) {
                if (!empty($questionData[$key]) && is_string($questionData[$key])) {
                    return $questionData[$key];
                }
            }
        }

        return $question->explanation ?: null;
    }

    /**
     * Bỏ mọi field có thể lộ đáp án đúng khỏi payload 1 câu hỏi. Dùng khi
     * trả dữ liệu cho HỌC VIÊN đang làm bài (attempt) — không bao giờ được
     * gửi đáp án đúng xuống client trước khi nộp bài.
     *
     * $options:
     *   keep_hint         => giữ lại 'hint' (Speaking cần gợi ý khi làm bài)
     *   keep_model_answer => giữ lại 'model_answer' (chỉ practice test)
     */
    public function stripAnswerFields(array $questionPayload, array $options = []): array
    {
        unset(
            $questionPayload['correctAnswer'],
            $questionPayload['correctAnswers'],
            $questionPayload['correctAnswerGroups'],
            $questionPayload['explanation']
        );

        if (empty($options['keep_hint'])) {
            unset($questionPayload['hint']);
        }

        if (empty($options['keep_model_answer'])) {
            unset($questionPayload['model_answer']);
        }

        if (!empty($questionPayload['table_structure']) && is_array($questionPayload['table_structure'])) {
            // Giữ headers/rows để render bảng, chỉ bỏ 'answers' (chứa đáp
            // án đúng + số blank từng ô -> vẫn cần blank_count để render ô
            // trống, xem ghi chú trong table_structure gốc nếu có field
            // riêng cho việc đó; ở đây ta bỏ toàn bộ answers vì nó chứa cả
            // 'answers' lẫn vị trí, phía client tự đếm blank từ text ô như
            // preview đang làm qua regex ___).
            unset($questionPayload['table_structure']['answers']);
        }

        return $questionPayload;
    }

    /**
     * Áp stripAnswerFields() cho toàn bộ cây parts -> groups -> questions
     * của 1 section đã build qua buildSectionData(). $options được chuyển
     * thẳng xuống stripAnswerFields().
     */
    public function stripSectionAnswers(array $sectionData, array $options = []): array
    {
        if (empty($sectionData['parts']) || !is_array($sectionData['parts'])) {
            return $sectionData;
        }

        foreach ($sectionData['parts'] as &$part) {
            if (empty($part['groups']) || !is_array($part['groups'])) {
                continue;
            }

            foreach ($part['groups'] as &$group) {
                if (empty($group['questions']) || !is_array($group['questions'])) {
                    continue;
                }

                $group['questions'] = array_map(
                    fn (array $question) => $this->stripAnswerFields($question, $options),
                    $group['questions']
                );
            }
            unset($group);
        }
        unset($part);

        return $sectionData;
    }

    /**
     * Resolve mọi path lưu trữ (part/group/question) trong 1 section-shape
     * thành URL thật (Storage::url()). Áp dụng SAU stripSectionAnswers()
     * hoặc trước đều được — không đụng field đáp án.
     */
    public function resolveSectionMediaUrls(array $sectionData): array
    {
        if (!empty($sectionData['files']) && is_array($sectionData['files'])) {
            $sectionData['files'] = $this->resolveFileGroupUrls($sectionData['files']);
        }

        if (empty($sectionData['parts']) || !is_array($sectionData['parts'])) {
            return $sectionData;
        }

        foreach ($sectionData['parts'] as &$part) {
            if (!empty($part['files']) && is_array($part['files'])) {
                $part['files'] = $this->resolveFileGroupUrls($part['files']);
            }

            if (empty($part['groups']) || !is_array($part['groups'])) {
                continue;
            }

            foreach ($part['groups'] as &$group) {
                if (!empty($group['files']) && is_array($group['files'])) {
                    $group['files'] = $this->resolveFileGroupUrls($group['files']);
                }

                if (!empty($group['task_image'])) {
                    $group['task_image'] = $this->resolveMediaUrl($group['task_image']);
                }

                if (empty($group['questions']) || !is_array($group['questions'])) {
                    continue;
                }

                foreach ($group['questions'] as &$question) {
                    if (!empty($question['question_data']['task_image'])) {
                        $question['question_data']['task_image'] = $this->resolveMediaUrl(
                            $question['question_data']['task_image']
                        );
                    }

                    if (!empty($question['question_audio'])) {
                        $question['question_audio'] = $this->resolveMediaUrl($question['question_audio']);
                    }
                }
                unset($question);
            }
            unset($group);
        }
        unset($part);

        return $sectionData;
    }

    public function resolveFileGroupUrls(array $files): array
    {
        foreach ($files as $key => $value) {
            $files[$key] = $this->resolveMediaUrl($value);
        }

        return $files;
    }

    public function resolveMediaUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (preg_match('#^https?://#i', $path) || str_starts_with($path, '/')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    public function normalizeQuestionType(string $type): string
    {
        $mapping = [
            'multiple_choice' => 'multiple_choice_single',
            'multiple_choice_single' => 'multiple_choice_single',
            'multiple_choice_multiple' => 'multiple_choice_multiple',
            'true_false_not_given' => 'true_false_not_given',
            'yes_no_not_given' => 'yes_no_not_given',
            'matching_headings' => 'matching_headings',
            'matching_information' => 'matching_information',
            'matching_features' => 'matching_features',
            'matching_sentence_endings' => 'matching_sentence_endings',
            'sentence_completion' => 'sentence_completion',
            'summary_completion' => 'summary_completion',
            'note_completion' => 'note_completion',
            'table_completion' => 'table_completion',
            'diagram_labeling' => 'diagram_labeling',
            'diagram_label' => 'diagram_labeling',
            'short_answer' => 'short_answer',
            'essay' => 'essay',
            'speaking_prompt' => 'essay',
            'drag_drop_disappear' => 'drag_drop_disappear',
            'drag_drop_reuse' => 'drag_drop_reuse',
        ];

        return $mapping[$type] ?? $type;
    }

    public function normalizeCompletionAnswerGroups($value): array
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

        if (str_starts_with($text, '[') || str_starts_with($text, '{')) {
            $decoded = json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $this->normalizeCompletionAnswerGroups($decoded);
            }
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

    public function normalizeCompletionAnswerVariants($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(
                static fn ($item) => trim((string) $item),
                $value
            )));
        }

        $text = trim((string) $value);
        if ($text === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\s*\/\s*/', $text))));
    }
}