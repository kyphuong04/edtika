<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\TextLesson;
use App\Models\TextLessonAttachment;
use App\Models\Translation\TextLessonTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapterItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class TextLessonsController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['new'];
        $imageFileUpload = $request->file('ajax.new.image');

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required',
            'study_time' => 'required|numeric',
            'image' => 'nullable',
            'accessibility' => 'required|' . Rule::in(File::$accessibility),
            'summary' => 'required',
            'content' => 'required',
            'interactive_quiz' => 'nullable|array',
            'lecture_notes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
            $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
            $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
        } else {
            $data['check_previous_parts'] = false;
            $data['access_after_day'] = null;
        }

        if (!empty($webinar) and $webinar->canAccess($user)) {
            $lessonsCount = TextLesson::where('webinar_id', $data['webinar_id'])->count();


            if (!empty($imageFileUpload)) {
                $data['image'] = $this->uploadFile($imageFileUpload, "webinars/{$webinar->id}/text_lessons", null, $webinar->creator_id);
            }

            $textLesson = TextLesson::create([
                'creator_id' => $user->id,
                'webinar_id' => $data['webinar_id'],
                'chapter_id' => $data['chapter_id'],
                'image' => $data['image'] ?? null,
                'study_time' => $data['study_time'],
                'accessibility' => $data['accessibility'],
                'order' => $lessonsCount + 1,
                'check_previous_parts' => $data['check_previous_parts'],
                'access_after_day' => $data['access_after_day'],
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? TextLesson::$Active : TextLesson::$Inactive,
                'created_at' => time(),
            ]);

            if ($textLesson) {
                $locale = $request->get('locale', getDefaultLocale());

                TextLessonTranslation::updateOrCreate([
                    'text_lesson_id' => $textLesson->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'summary' => $data['summary'],
                    'content' => $data['content'],
                    'interactive_quiz' => $this->normalizeInteractiveQuiz($data['interactive_quiz'] ?? null),
                    'lecture_notes' => $this->normalizeLectureNotes($data['lecture_notes'] ?? null),
                ]);

                if (!empty($data['attachments'])) {
                    $attachments = $data['attachments'];
                    $this->saveAttachments($textLesson, $attachments);
                }

                WebinarChapterItem::makeItem($textLesson->creator_id, $textLesson->chapter_id, $textLesson->id, WebinarChapterItem::$chapterTextLesson);
            }

            $webinar->update([
                'updated_at' => time()
            ]);

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];
        $imageFileUpload = $request->file("ajax.{$id}.image");

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required',
            'study_time' => 'required|numeric',
            'image' => 'nullable',
            'accessibility' => 'required|' . Rule::in(File::$accessibility),
            'summary' => 'required',
            'content' => 'required',
            'interactive_quiz' => 'nullable|array',
            'lecture_notes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
            $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
            $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
        } else {
            $data['check_previous_parts'] = false;
            $data['access_after_day'] = null;
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            $textLesson = TextLesson::where('id', $id)
                ->where(function ($query) use ($user, $webinar) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('webinar_id', $webinar->id);
                })
                ->first();

            if (!empty($textLesson)) {
                $changeChapter = ($data['chapter_id'] != $textLesson->chapter_id);
                $oldChapterId = $textLesson->chapter_id;

                if (!empty($imageFileUpload)) {
                    $data['image'] = $this->uploadFile($imageFileUpload, "webinars/{$webinar->id}/text_lessons", null, $webinar->creator_id);
                } else {
                    $data['image'] = $textLesson->image;
                }


                $textLesson->update([
                    'chapter_id' => $data['chapter_id'],
                    'image' => $data['image'] ?? null,
                    'study_time' => $data['study_time'],
                    'accessibility' => $data['accessibility'],
                    'check_previous_parts' => $data['check_previous_parts'],
                    'access_after_day' => $data['access_after_day'],
                    'status' => (!empty($data['status']) and $data['status'] == 'on') ? TextLesson::$Active : TextLesson::$Inactive,
                    'updated_at' => time(),
                ]);

                if ($changeChapter) {
                    WebinarChapterItem::changeChapter($textLesson->creator_id, $oldChapterId, $textLesson->chapter_id, $textLesson->id, WebinarChapterItem::$chapterTextLesson);
                }

                $locale = $request->get('locale', getDefaultLocale());

                TextLessonTranslation::updateOrCreate([
                    'text_lesson_id' => $textLesson->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'summary' => $data['summary'],
                    'content' => $data['content'],
                    'interactive_quiz' => $this->normalizeInteractiveQuiz($data['interactive_quiz'] ?? null),
                    'lecture_notes' => $this->normalizeLectureNotes($data['lecture_notes'] ?? null),
                ]);

                $textLesson->attachments()->delete();

                if (!empty($data['attachments'])) {
                    $attachments = $data['attachments'];
                    $this->saveAttachments($textLesson, $attachments);
                }

                $webinar->update([
                    'updated_at' => time()
                ]);

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        abort(403);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $textLesson = TextLesson::where('id', $id)
            ->first();

        if (!empty($textLesson)) {
            $webinar = Webinar::query()->find($textLesson->webinar_id);

            if ($textLesson->creator_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {

                WebinarChapterItem::where('user_id', $textLesson->creator_id)
                    ->where('item_id', $textLesson->id)
                    ->where('type', WebinarChapterItem::$chapterTextLesson)
                    ->delete();

                $textLesson->delete();
            }
        }

        return response()->json([
            'code' => 200,
        ], 200);
    }

    private function saveAttachments($textLesson, $attachments)
    {
        if (!empty($attachments)) {

            if (!is_array($attachments)) {
                $attachments = [$attachments];
            }

            foreach ($attachments as $attachment_id) {
                if (!empty($attachment_id)) {
                    TextLessonAttachment::create([
                        'text_lesson_id' => $textLesson->id,
                        'file_id' => $attachment_id,
                        'created_at' => time(),
                    ]);
                }
            }
        }
    }

    private function normalizeInteractiveQuiz($interactiveQuiz)
    {
        if (empty($interactiveQuiz) || !is_array($interactiveQuiz)) {
            return null;
        }

        $allowedTypes = [
            'multiple_choice_single',
            'multiple_choice_multiple',
            'true_false_not_given',
            'yes_no_not_given',
            'matching_headings',
            'matching_information',
            'matching_features',
            'matching_sentence_endings',
            'sentence_completion',
            'summary_completion',
            'note_completion',
            'table_completion',
        ];

        $title = trim((string) ($interactiveQuiz['title'] ?? ''));
        $questions = collect($interactiveQuiz['questions'] ?? [])
            ->map(function ($question) use ($allowedTypes) {
                if (!is_array($question)) {
                    return null;
                }

                $questionType = trim((string) ($question['type'] ?? 'multiple_choice_single'));
                if (!in_array($questionType, $allowedTypes, true)) {
                    $questionType = 'multiple_choice_single';
                }

                $questionTitle = trim((string) ($question['title'] ?? ''));

                $questionOptions = $question['options'] ?? ($question['answers'] ?? []);
                $options = collect(is_array($questionOptions) ? $questionOptions : [])
                    ->map(function ($answer) {
                        if (!is_array($answer)) {
                            return null;
                        }

                        $answerTitle = trim((string) ($answer['title'] ?? ''));

                        if ($answerTitle === '') {
                            return null;
                        }

                        return ['title' => $answerTitle];
                    })
                    ->filter()
                    ->values()
                    ->all();

                $pairs = collect($question['pairs'] ?? [])
                    ->map(function ($pair) {
                        if (!is_array($pair)) {
                            return null;
                        }

                        $prompt = trim((string) ($pair['prompt'] ?? ''));
                        $answer = trim((string) ($pair['answer'] ?? ''));

                        if ($prompt === '' && $answer === '') {
                            return null;
                        }

                        return [
                            'prompt' => $prompt,
                            'answer' => $answer,
                        ];
                    })
                    ->filter()
                    ->values()
                    ->all();

                $correctAnswer = null;
                if (isset($question['correct_answer'])) {
                    if (is_scalar($question['correct_answer'])) {
                        $value = trim((string) $question['correct_answer']);
                        $correctAnswer = ($value !== '') ? $value : null;
                    }
                }

                $correctAnswers = collect($question['correct_answers'] ?? [])
                    ->map(function ($value) {
                        if (!is_scalar($value)) {
                            return null;
                        }

                        $value = trim((string) $value);
                        return ($value === '') ? null : $value;
                    })
                    ->filter()
                    ->values()
                    ->all();

                $alternativeAnswers = $question['alternative_answers'] ?? [];
                if (is_string($alternativeAnswers)) {
                    $alternativeAnswers = preg_split('/\r\n|\r|\n/', $alternativeAnswers);
                }

                $alternativeAnswers = collect(is_array($alternativeAnswers) ? $alternativeAnswers : [])
                    ->map(function ($value) {
                        if (!is_scalar($value)) {
                            return null;
                        }

                        $value = trim((string) $value);
                        return ($value === '') ? null : $value;
                    })
                    ->filter()
                    ->values()
                    ->all();

                $explanation = trim((string) ($question['explanation'] ?? ''));

                $maxWords = (isset($question['max_words']) && $question['max_words'] !== '') ? (int) $question['max_words'] : null;
                $targetBand = (isset($question['target_band']) && $question['target_band'] !== '') ? (float) $question['target_band'] : null;

                $hasContent = ($questionTitle !== '')
                    || !empty($options)
                    || !empty($pairs)
                    || ($correctAnswer !== null)
                    || !empty($correctAnswers)
                    || !empty($alternativeAnswers)
                    || ($explanation !== '')
                    || ($maxWords !== null)
                    || ($targetBand !== null);

                if (!$hasContent) {
                    return null;
                }

                $normalized = [
                    'type' => $questionType,
                    'title' => $questionTitle,
                ];

                if (!empty($options)) {
                    $normalized['options'] = $options;
                    $normalized['answers'] = $options;
                }

                if (!empty($pairs)) {
                    $normalized['pairs'] = $pairs;
                }

                if ($correctAnswer !== null) {
                    $normalized['correct_answer'] = $correctAnswer;
                }

                if (!empty($correctAnswers)) {
                    $normalized['correct_answers'] = $correctAnswers;
                }

                if (!empty($alternativeAnswers)) {
                    $normalized['alternative_answers'] = $alternativeAnswers;
                }

                if ($explanation !== '') {
                    $normalized['explanation'] = $explanation;
                }

                if ($maxWords !== null && $maxWords > 0) {
                    $normalized['max_words'] = $maxWords;
                }

                if ($targetBand !== null && $targetBand >= 0) {
                    $normalized['target_band'] = $targetBand;
                }

                return $normalized;
            })
            ->filter()
            ->values()
            ->all();

        if ($title === '' && empty($questions)) {
            return null;
        }

        return [
            'title' => $title,
            'questions' => $questions,
        ];
    }

    private function normalizeLectureNotes($lectureNotes)
    {
        if (empty($lectureNotes) || !is_array($lectureNotes)) {
            return null;
        }

        $notes = collect($lectureNotes)
            ->map(function ($note) {
                if (!is_array($note)) {
                    return null;
                }

                $title = trim((string) ($note['title'] ?? ''));
                $content = trim((string) ($note['content'] ?? ''));

                if ($title === '' && $content === '') {
                    return null;
                }

                return [
                    'title' => $title,
                    'content' => $content,
                ];
            })
            ->filter()
            ->values()
            ->all();

        return empty($notes) ? null : $notes;
    }
}




