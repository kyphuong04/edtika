<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesQuestionsAnswer;
use App\Models\Translation\QuizzesQuestionsAnswerTranslation;
use App\Models\Translation\QuizzesQuestionTranslation;
use App\Models\Webinar;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Support\Facades\Validator;

class QuizQuestionController extends Controller
{

    public function getForm(Request $request)
    {
        $user = auth()->user();
        $quizId = $request->get('quiz');
        $type = $request->get('type', QuizzesQuestion::$descriptive);

        $quiz = Quiz::where('id', $quizId)->first();

        if (!empty($quiz) and $quiz->canAccessToEdit($user) and in_array($type, QuizzesQuestion::allowedTypes())) {
            $data = [
                'quiz' => $quiz,
                'questionType' => $type,
            ];

            $html = (string) view()->make($this->getQuestionModalViewPath($type), $data);

            return response()->json([
                'html' => $html
            ], 200);
        }

        return response()->json([], 422);
    }

    public function store(Request $request)
    {
        $data = $request->get('ajax');
        $locale = $request->get('locale');

        $rules = [
            'quiz_id' => 'required|exists:quizzes,id',
            'title' => 'required',
            'grade' => 'required|integer',
            'type' => 'required|in:' . implode(',', QuizzesQuestion::allowedTypes()),
            'question_data' => 'nullable|array',
            'negative_grade' => 'nullable|integer|min:0',
        ];

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validate->errors()
            ], 422);
        }


        if (!empty($data['image']) and !empty($data['video'])) {
            return response()->json([
                'code' => 422,
                'errors' => [
                    'image' => [trans('update.quiz_question_image_validation_by_video')],
                    'video' => [trans('update.quiz_question_image_validation_by_video')],
                ]
            ], 422);
        }

        $user = auth()->user();

        if ($data['type'] == QuizzesQuestion::$multiple and !empty($data['answers'])) {
            $answers = $data['answers'];

            $hasCorrect = false;
            foreach ($answers as $answer) {
                if (isset($answer['correct'])) {
                    $hasCorrect = true;
                }
            }

            if (!$hasCorrect) {
                return response([
                    'code' => 422,
                    'errors' => [
                        'current_answer' => [trans('quiz.current_answer_required')]
                    ],
                ], 422);
            }
        }

        $data['question_data'] = $this->prepareQuestionData($data);

        if (in_array($data['type'], [QuizzesQuestion::$fillBlank, QuizzesQuestion::$rewriteSentence, QuizzesQuestion::$sentenceCompletion, QuizzesQuestion::$shortAnswer, QuizzesQuestion::$trueFalseNotGiven, QuizzesQuestion::$yesNoNotGiven, QuizzesQuestion::$matchingHeadings, QuizzesQuestion::$matchingInformation, QuizzesQuestion::$matchingFeatures, QuizzesQuestion::$matchingSentenceEndings]) && empty(data_get($data, 'question_data.prompt'))) {
            return response()->json([
                'code' => 422,
                'errors' => [
                    'question_data.prompt' => [trans('quiz.question_prompt')],
                ],
            ], 422);
        }

        $quiz = Quiz::where('id', $data['quiz_id'])->first();

        if (!empty($quiz) and $quiz->canAccessToEdit($user)) {
            $order = QuizzesQuestion::query()->where('quiz_id', $quiz->id)->count() + 1;

            $quizQuestion = QuizzesQuestion::create([
                'quiz_id' => $data['quiz_id'],
                'creator_id' => $user->id,
                'grade' => $data['grade'],
                'negative_grade' => $data['negative_grade'] ?? null,
                'type' => $data['type'],
                'question_data' => $data['question_data'],
                'order' => $order,
                'created_at' => time()
            ]);

            if (!empty($quizQuestion)) {
                QuizzesQuestionTranslation::updateOrCreate([
                    'quizzes_question_id' => $quizQuestion->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'correct' => $data['correct'] ?? null,
                ]);

                $this->handleUploadForQuestion($request, $quizQuestion);
            }

            $quiz->increaseTotalMark($quizQuestion->grade);

            if ($quizQuestion->type == QuizzesQuestion::$multiple and !empty($data['answers'])) {

                foreach ($answers as $key => $answer) {
                    $file = !empty($request->file("ajax.answers.{$key}.file")) ? $request->file("ajax.answers.{$key}.file") : null;

                    if (!empty($answer['title']) or !empty($file)) {
                        $questionAnswer = QuizzesQuestionsAnswer::create([
                            'question_id' => $quizQuestion->id,
                            'creator_id' => $user->id,
                            'correct' => isset($answer['correct']) ? true : false,
                            'created_at' => time()
                        ]);

                        if (!empty($questionAnswer)) {
                            QuizzesQuestionsAnswerTranslation::updateOrCreate([
                                'quizzes_questions_answer_id' => $questionAnswer->id,
                                'locale' => mb_strtolower($locale),
                            ], [
                                'title' => $answer['title'],
                            ]);

                            $this->handleUploadForAnswer($questionAnswer, $file);
                        }
                    }
                }
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('webinars.success_store'),
            ], 200);
        }

        return response()->json([
            'code' => 422
        ], 422);
    }

    public function edit(Request $request, $question_id)
    {
        $user = auth()->user();

        $question = QuizzesQuestion::where('id', $question_id)->first();

        if (!empty($question)) {
            $quiz = Quiz::find($question->quiz_id);

            if (!empty($quiz) and $quiz->canAccessToEdit($user)) {

                $locale = $request->get('locale', app()->getLocale());

                $data = [
                    'pageTitle' => $question->title,
                    'quiz' => $quiz,
                    'question_edit' => $question,
                    'userLanguages' => getUserLanguagesLists(),
                    'locale' => mb_strtolower($locale),
                    'defaultLocale' => getDefaultLocale(),
                ];

                if ($question->type == QuizzesQuestion::$multiple) {
                    $html = (string) view()->make('design_1.panel.quizzes.create.modals.multiple_question', $data);
                } elseif (in_array($question->type, [QuizzesQuestion::$fillBlank, QuizzesQuestion::$rewriteSentence, QuizzesQuestion::$sentenceCompletion, QuizzesQuestion::$shortAnswer])) {
                    $data['questionType'] = $question->type;
                    $html = (string) view()->make('design_1.panel.quizzes.create.modals.text_question', $data);
                } elseif (in_array($question->type, [QuizzesQuestion::$trueFalseNotGiven, QuizzesQuestion::$yesNoNotGiven])) {
                    $data['questionType'] = $question->type;
                    $html = (string) view()->make('design_1.panel.quizzes.create.modals.boolean_question', $data);
                } elseif ($question->isMatchingQuestion()) {
                    $data['questionType'] = $question->type;
                    $html = (string) view()->make('design_1.panel.quizzes.create.modals.matching_question', $data);
                } else {
                    $html = (string) view()->make('design_1.panel.quizzes.create.modals.descriptive_question', $data);
                }

                return response()->json([
                    'html' => $html
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function getQuestionByLocale(Request $request, $id)
    {
        $user = auth()->user();

        $question = QuizzesQuestion::where('id', $id)
            ->with('quizzesQuestionsAnswers')
            ->first();

        if (!empty($question)) {

            $quiz = Quiz::find($question->quiz_id);

            if (!empty($quiz) and $quiz->canAccessToEdit($user)) {

                $locale = $request->get('locale', app()->getLocale());

                foreach ($question->translatedAttributes as $attribute) {
                    try {
                        $question->$attribute = $question->translate(mb_strtolower($locale))->$attribute;
                    } catch (\Exception $e) {
                        $question->$attribute = null;
                    }
                }

                if (!empty($question->quizzesQuestionsAnswers) and count($question->quizzesQuestionsAnswers)) {
                    foreach ($question->quizzesQuestionsAnswers as $answer) {
                        foreach ($answer->translatedAttributes as $att) {
                            try {
                                $answer->$att = $answer->translate(mb_strtolower($locale))->$att;
                            } catch (\Exception $e) {
                                $answer->$att = null;
                            }
                        }
                    }
                }

                return response()->json([
                    'question' => $question
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function update(Request $request, $id)
    {
        $data = $request->get('ajax');
        $locale = $request->get('locale');

        $rules = [
            'quiz_id' => 'required|exists:quizzes,id',
            'title' => 'required',
            'grade' => 'required',
            'type' => 'required|in:' . implode(',', QuizzesQuestion::allowedTypes()),
            'question_data' => 'nullable|array',
            'negative_grade' => 'nullable|integer|min:0',
        ];

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validate->errors()
            ], 422);
        }

        if (!empty($data['image']) and !empty($data['video'])) {
            return response()->json([
                'code' => 422,
                'errors' => [
                    'image' => [trans('update.quiz_question_image_validation_by_video')],
                    'video' => [trans('update.quiz_question_image_validation_by_video')],
                ]
            ], 422);
        }

        if ($data['type'] == QuizzesQuestion::$multiple and !empty($data['answers'])) {
            $answers = $data['answers'];

            $hasCorrect = false;
            foreach ($answers as $answer) {
                if (isset($answer['correct'])) {
                    $hasCorrect = true;
                }
            }

            if (!$hasCorrect) {
                return response([
                    'code' => 422,
                    'errors' => [
                        'current_answer' => [trans('quiz.current_answer_required')]
                    ],
                ], 422);
            }
        }

        $data['question_data'] = $this->prepareQuestionData($data);

        if (in_array($data['type'], [QuizzesQuestion::$fillBlank, QuizzesQuestion::$rewriteSentence]) && empty(data_get($data, 'question_data.prompt'))) {
            return response()->json([
                'code' => 422,
                'errors' => [
                    'question_data.prompt' => [trans('quiz.question_prompt')],
                ],
            ], 422);
        }


        $user = auth()->user();

        $quiz = Quiz::where('id', $data['quiz_id'])->first();

        if (!empty($quiz) and $quiz->canAccessToEdit($user)) {
            $quizQuestion = QuizzesQuestion::where('id', $id)
                ->where('quiz_id', $quiz->id)
                ->first();

            if (!empty($quizQuestion)) {
                $quiz_total_grade = $quiz->total_mark - $quizQuestion->grade;

                $quizQuestion->update([
                    'quiz_id' => $data['quiz_id'],
                    'grade' => $data['grade'],
                    'negative_grade' => $data['negative_grade'] ?? null,
                    'type' => $data['type'],
                    'question_data' => $data['question_data'],
                    'updated_at' => time()
                ]);

                QuizzesQuestionTranslation::updateOrCreate([
                    'quizzes_question_id' => $quizQuestion->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'correct' => $data['correct'] ?? null,
                ]);

                // Upload image or Video
                $this->handleUploadForQuestion($request, $quizQuestion);

                $quiz_total_grade = ($quiz_total_grade > 0 ? $quiz_total_grade : 0) + $data['grade'];
                $quiz->update(['total_mark' => $quiz_total_grade]);;

                if ($data['type'] == QuizzesQuestion::$multiple and !empty($data['answers'])) {
                    $answers = $data['answers'];

                    if ($quizQuestion->type == QuizzesQuestion::$multiple and $answers) {
                        $oldAnswerIds = QuizzesQuestionsAnswer::where('question_id', $quizQuestion->id)->pluck('id')->toArray();

                        foreach ($answers as $key => $answer) {
                            $file = !empty($request->file("ajax.answers.{$key}.file")) ? $request->file("ajax.answers.{$key}.file") : null;

                            if (!empty($answer['title']) or !empty($file)) {

                                if (count($oldAnswerIds)) {
                                    $oldAnswerIds = array_filter($oldAnswerIds, function ($item) use ($key) {
                                        return $item != $key;
                                    });
                                }

                                $quizQuestionsAnswer = QuizzesQuestionsAnswer::where('id', $key)->first();

                                if (!empty($quizQuestionsAnswer)) {
                                    $quizQuestionsAnswer->update([
                                        'question_id' => $quizQuestion->id,
                                        'creator_id' => $user->id,
                                        'correct' => isset($answer['correct']) ? true : false,
                                        'created_at' => time()
                                    ]);
                                } else {
                                    $quizQuestionsAnswer = QuizzesQuestionsAnswer::create([
                                        'question_id' => $quizQuestion->id,
                                        'creator_id' => $user->id,
                                        'correct' => isset($answer['correct']) ? true : false,
                                        'created_at' => time()
                                    ]);
                                }

                                if ($quizQuestionsAnswer) {
                                    QuizzesQuestionsAnswerTranslation::updateOrCreate([
                                        'quizzes_questions_answer_id' => $quizQuestionsAnswer->id,
                                        'locale' => mb_strtolower($locale),
                                    ], [
                                        'title' => $answer['title'],
                                    ]);

                                    $this->handleUploadForAnswer($quizQuestionsAnswer, $file);
                                }
                            }
                        }

                        if (count($oldAnswerIds)) {
                            QuizzesQuestionsAnswer::whereIn('id', $oldAnswerIds)->delete();
                        }
                    }
                }

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.question_updated_successful'),
                ], 200);
            }
        }

        return response()->json([
            'code' => 422
        ], 422);
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();

        $question = QuizzesQuestion::where('id', $id)
            ->first();

        if (!empty($question) and $question->canAccessToEdit($user)) {
            $question->delete();

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
    }

    private function handleUploadForQuestion(Request $request, $question)
    {
        $destination = "quizzes/questions/{$question->id}";
        $imagePath = !empty($question->image) ? $question->image : null;
        $videoPath = !empty($question->video) ? $question->video : null;

        if (!empty($request->file('ajax.image'))) {
            if (!empty($imagePath)) {
                $this->removeFile($imagePath);
            }

            $imagePath = $this->uploadFile($request->file('ajax.image'), $destination, "question-image-{$question->id}", auth()->id());
        }

        if (!empty($request->file('ajax.video'))) {
            if (!empty($videoPath)) {
                $this->removeFile($videoPath);
            }

            $videoPath = $this->uploadFile($request->file('ajax.video'), $destination, "question-video-{$question->id}", auth()->id());
        }

        $question->update([
            'image' => $imagePath,
            'video' => $videoPath,
        ]);

    }

    private function handleUploadForAnswer($answer, $file = null)
    {
        $destination = "quizzes/questions/{$answer->question_id}";
        $imagePath = !empty($answer->image) ? $answer->image : null;

        if (!empty($file)) {
            if (!empty($imagePath)) {
                $this->removeFile($imagePath);
            }

            $imagePath = $this->uploadFile($file, $destination, "answer-image-{$answer->id}", auth()->id());
        }

        $answer->update([
            'image' => $imagePath
        ]);
    }

    private function getQuestionModalViewPath($type)
    {
        if ($type === QuizzesQuestion::$multiple) {
            return 'design_1.panel.quizzes.create.modals.multiple_question';
        }

        if (in_array($type, [QuizzesQuestion::$fillBlank, QuizzesQuestion::$rewriteSentence, QuizzesQuestion::$sentenceCompletion, QuizzesQuestion::$shortAnswer])) {
            return 'design_1.panel.quizzes.create.modals.text_question';
        }

        if (in_array($type, [QuizzesQuestion::$trueFalseNotGiven, QuizzesQuestion::$yesNoNotGiven])) {
            return 'design_1.panel.quizzes.create.modals.boolean_question';
        }

        if (in_array($type, [QuizzesQuestion::$matchingHeadings, QuizzesQuestion::$matchingInformation, QuizzesQuestion::$matchingFeatures, QuizzesQuestion::$matchingSentenceEndings])) {
            return 'design_1.panel.quizzes.create.modals.matching_question';
        }

        return 'design_1.panel.quizzes.create.modals.descriptive_question';
    }

    private function prepareQuestionData(array $data)
    {
        $questionData = $data['question_data'] ?? [];

        if (!is_array($questionData)) {
            $questionData = [];
        }

        if (in_array($data['type'], [QuizzesQuestion::$fillBlank, QuizzesQuestion::$rewriteSentence, QuizzesQuestion::$sentenceCompletion, QuizzesQuestion::$shortAnswer])) {
            if (empty($questionData['grading_mode'])) {
                $questionData['grading_mode'] = 'auto';
            }

            if (empty($questionData['blank_count'])) {
                $questionData['blank_count'] = 1;
            }

            if (isset($questionData['accepted_answers']) && is_string($questionData['accepted_answers'])) {
                $questionData['accepted_answers'] = trim($questionData['accepted_answers']);
            }

            if (isset($questionData['prompt']) && is_string($questionData['prompt'])) {
                $questionData['prompt'] = trim($questionData['prompt']);
            }
        }

        if (in_array($data['type'], [QuizzesQuestion::$trueFalseNotGiven, QuizzesQuestion::$yesNoNotGiven])) {
            if (empty($questionData['options'])) {
                $questionData['options'] = $data['type'] === QuizzesQuestion::$yesNoNotGiven
                    ? ['YES', 'NO', 'NOT GIVEN']
                    : ['TRUE', 'FALSE', 'NOT GIVEN'];
            }

            if (isset($questionData['correct_answer']) && is_string($questionData['correct_answer'])) {
                $questionData['correct_answer'] = trim($questionData['correct_answer']);
            }
        }

        if (in_array($data['type'], [QuizzesQuestion::$matchingHeadings, QuizzesQuestion::$matchingInformation, QuizzesQuestion::$matchingFeatures, QuizzesQuestion::$matchingSentenceEndings])) {
            foreach (['items', 'options', 'correct_answers'] as $field) {
                if (isset($questionData[$field]) && is_string($questionData[$field])) {
                    $questionData[$field] = preg_split('/\r\n|\r|\n/', trim($questionData[$field])) ?: [];
                }
            }
        }

        return $questionData;
    }

}


