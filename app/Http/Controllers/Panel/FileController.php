<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\BunnyCDN\BunnyVideoStream;
use App\Models\File;
use App\Models\Translation\FileTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Validator;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->get('ajax')['new'];
        $fileUpload = $request->file('ajax.new.file_upload');

        if (!empty($fileUpload)) {
            $data['file_upload'] = $fileUpload;
        }

        if (empty($data['storage'])) {
            $data['storage'] = 'upload';
        }

        $sourceRequiredFileType = ['external_link', 's3', 'google_drive', 'upload'];
        $sourceRequiredFileVolume = ['external_link', 'google_drive'];
        $sourceDefaultFileTypeAndVolume = ['youtube', 'vimeo', 'iframe', 'secure_host'];

        if (in_array($data['storage'], $sourceDefaultFileTypeAndVolume)) {
            $data['file_type'] = 'video';
            $data['volume'] = !empty($data['volume']) ? $data['volume'] : 0;
        }

        $rules = [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|max:255',
            'accessibility' => 'required|' . Rule::in(File::$accessibility),
            'file_url' => 'required',
            'file_type' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileType)),
            'volume' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileVolume)),
            'description' => 'nullable',
            'interactive_quiz' => 'nullable|array',
            'lecture_notes' => 'nullable|array',
        ];

        if ($data['storage'] == 'upload_archive') {
            $rules['file_url'] = 'nullable';
            $rules['file_upload'] = 'required|file|mimes:zip|max:2097152'; // 2GB max size
            $rules['interactive_type'] = 'required';
            $rules['interactive_file_name'] = Rule::requiredIf($data['interactive_type'] == 'custom');
        }

        if (in_array($data['storage'], ['upload', 's3'])) {
            $rules['file_url'] = 'nullable';
            $rules['file_upload'] = $this->handleUploadAndS3FileValidationByType($data['file_type'] ?? null);
        }

        if ($data['storage'] == 'secure_host') {
            $rules['file_url'] = 'nullable';
            $rules['file_upload'] = 'required|file|mimes:mp4,avi,mkv,mov,wmv,flv,webm|max:2097152'; // 2GB max size

            if ($data['secure_host_upload_type'] == "manual") {
                $rules['file_upload'] = 'nullable';
                $rules['file_url'] = 'required';
                $rules['volume'] = 'required';
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data['downloadable'] = !empty($data['downloadable']);
        if (in_array($data['storage'], ['youtube', 'vimeo', 'iframe', 'google_drive', 'upload_archive'])) {
            $data['downloadable'] = false;
        } elseif (in_array($data['storage'], ['external_link', 's3']) and $data['file_type'] != 'video') {
            $data['downloadable'] = true;
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
            $volume = 0;
            $fileInfos = null;

            if ($data['storage'] == 'upload_archive') {
                $data['file_url'] = $this->uploadFile($fileUpload, "webinars/{$webinar->id}/files", null, $webinar->creator_id);
                $fileInfos = $this->fileInfo($data['file_url']);

                if (empty($fileInfos) or $fileInfos['extension'] != 'zip') {
                    return response([
                        'code' => 422,
                        'errors' => [
                            'file_url' => [trans('validation.mimes', ['attribute' => 'file', 'values' => 'zip'])]
                        ],
                    ], 422);
                }

                $volume = convertToMB($fileInfos['size'] ?? 0);
                $fileInfos['extension'] = 'archive';
                $data['interactive_file_path'] = $this->handleUnZipFile($webinar, $data);
            } elseif ($data['storage'] == 'upload') {
                $volume = convertToMB($fileUpload->getSize());

                $data['file_url'] = $this->uploadFile($fileUpload, "webinars/{$webinar->id}/files", null, $webinar->creator_id);
            } elseif (in_array($data['storage'], ['s3', 'secure_host'])) {

                if ($data['storage'] == 's3') {
                    $data['volume'] = convertToMB($fileUpload->getSize());
                    $result = $this->uploadFileToS3($fileUpload);
                } else {
                    if ($data['secure_host_upload_type'] == "direct") {
                        $data['volume'] = convertToMB($fileUpload->getSize());
                        $result = $this->uploadFileToBunny($webinar, $fileUpload);
                    } else {
                        $result['status'] = true;
                        $result['path'] = $data['file_url'];
                    }
                }

                if (!$result['status']) {
                    return $result['path'];
                }

                $data['file_url'] = $result['path'];
                $fileInfos['extension'] = $data['file_type'];
                $fileInfos['size'] = $data['volume'];

                if ($data['storage'] == 'secure_host' and $data['secure_host_upload_type'] == "manual") {
                    $volume = $data['volume'];
                } else {
                    $volume = convertToMB(($data['volume'] ?? 0));
                }

            } else {
                $volume = !empty($data['volume']) ? $data['volume'] : 0; // input is MB
            }

            $file = File::create([
                'creator_id' => $user->id,
                'webinar_id' => $data['webinar_id'],
                'chapter_id' => $data['chapter_id'],
                'file' => $data['file_url'],
                'volume' => $volume,
                'file_type' => !empty($fileInfos) ? $fileInfos['extension'] : $data['file_type'],
                'accessibility' => $data['accessibility'],
                'storage' => $data['storage'],
                'secure_host_upload_type' => $data['secure_host_upload_type'] ?? null,
                'interactive_type' => $data['interactive_type'] ?? null,
                'interactive_file_name' => $data['interactive_file_name'] ?? null,
                'interactive_file_path' => $data['interactive_file_path'] ?? null,
                'online_viewer' => (!empty($data['online_viewer']) and $data['online_viewer'] == 'on'),
                'downloadable' => $data['downloadable'],
                'check_previous_parts' => $data['check_previous_parts'],
                'access_after_day' => $data['access_after_day'],
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? File::$Active : File::$Inactive,
                'created_at' => time()
            ]);

            if (!empty($file)) {
                $locale = $request->get('locale', getDefaultLocale());

                FileTranslation::updateOrCreate([
                    'file_id' => $file->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'interactive_quiz' => $this->normalizeInteractiveQuiz($data['interactive_quiz'] ?? null),
                    'lecture_notes' => $this->normalizeLectureNotes($data['lecture_notes'] ?? null),
                ]);

                WebinarChapterItem::makeItem($file->creator_id, $file->chapter_id, $file->id, WebinarChapterItem::$chapterFile);
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

    private function handleUnZipFile($webinar, $data)
    {
        $path = $data['file_url'];
        $interactiveType = $data['interactive_type'] ?? null;
        $interactiveFileName = $data['interactive_file_name'] ?? null;

        $storage = Storage::disk('public');

        $fileInfo = $this->fileInfo($path);

        $extractPath = "{$webinar->creator_id}/webinars/{$webinar->id}/files/{$fileInfo['name']}";
        $storageExtractPath = $storage->url($extractPath);

        if (!$storage->exists($extractPath)) {
            $storage->makeDirectory($extractPath);

            $filePath = public_path($path);

            $zip = new \ZipArchive();
            $res = $zip->open($filePath);

            if ($res) {
                $zip->extractTo(public_path($storageExtractPath));

                $zip->close();
            }
        }

        $fileName = 'index.html';

        if ($interactiveType == 'i_spring') {
            $fileName = 'story.html';
        } elseif ($interactiveType == 'custom') {
            $fileName = $interactiveFileName;
        }

        return $storageExtractPath . '/' . $fileName;
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

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $webinar = Webinar::query()->find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {
            $file = File::query()->where('id', $id)
                ->where(function ($query) use ($user, $webinar) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('webinar_id', $webinar->id);
                })
                ->first();

            if (!empty($file)) {

                $fileUpload = $request->file("ajax.{$id}.file_upload");

                if (!empty($fileUpload)) {
                    $data['file_upload'] = $fileUpload;
                }

                if (empty($data['storage'])) {
                    $data['storage'] = 'upload';
                }

                $sourceRequiredFileType = ['external_link', 's3', 'google_drive', 'upload'];
                $sourceRequiredFileVolume = ['external_link', 'google_drive'];
                $sourceDefaultFileTypeAndVolume = ['youtube', 'vimeo', 'iframe', 'secure_host'];

                if (in_array($data['storage'], $sourceDefaultFileTypeAndVolume)) {
                    $data['file_type'] = 'video';
                    $data['volume'] = !empty($data['volume']) ? $data['volume'] : 0;
                }

                $fileTypeIsChanged = !!(empty($data['file_type']) or $data['file_type'] != $file->file_type);

                $rules = [
                    'webinar_id' => 'required',
                    'chapter_id' => 'required',
                    'title' => 'required|max:255',
                    'accessibility' => 'required|' . Rule::in(File::$accessibility),
                    'file_url' => 'required',
                    'file_type' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileType)),
                    'volume' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileVolume)),
                    'description' => 'nullable',
                    'interactive_quiz' => 'nullable|array',
                    'lecture_notes' => 'nullable|array',
                ];

                if ($data['storage'] == 'upload_archive') {
                    $rules['file_url'] = 'nullable';
                    $rules['file_upload'] = ($fileTypeIsChanged ? 'required' : 'nullable') . '|file|mimes:zip|max:2097152'; // 2GB max size
                    $rules['interactive_type'] = 'required';
                    $rules['interactive_file_name'] = Rule::requiredIf($data['interactive_type'] == 'custom');
                }

                if (in_array($data['storage'], ['upload', 's3'])) {
                    $rules['file_url'] = 'nullable';
                    $rules['file_upload'] = $this->handleUploadAndS3FileValidationByType($data['file_type'] ?? null, $fileTypeIsChanged);
                }

                if ($data['storage'] == 'secure_host') {
                    $rules['file_url'] = 'nullable';
                    $rules['file_upload'] = ($fileTypeIsChanged ? 'required' : 'nullable') . '|file|mimes:mp4,avi,mkv,mov,wmv,flv,webm|max:2097152'; // 2GB max size

                    if ($data['secure_host_upload_type'] == "manual") {
                        $rules['secure_host_file_path'] = 'required';
                        $rules['volume'] = 'required';
                    }
                }

                $validator = Validator::make($data, $rules);

                if ($validator->fails()) {
                    return response([
                        'code' => 422,
                        'errors' => $validator->errors(),
                    ], 422);
                }

                $data['downloadable'] = !empty($data['downloadable']);
                if (in_array($data['storage'], ['youtube', 'vimeo', 'iframe', 'google_drive', 'upload_archive'])) {
                    $data['downloadable'] = false;
                } elseif (in_array($data['storage'], ['external_link', 's3']) and $data['file_type'] != 'video') {
                    $data['downloadable'] = true;
                }

                if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
                    $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
                    $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
                } else {
                    $data['check_previous_parts'] = false;
                    $data['access_after_day'] = null;
                }


                $volume = 0;
                $fileInfos = null;

                if ($data['storage'] == 'upload_archive') {

                    if (!empty($fileUpload)) {
                        $data['file_url'] = $this->uploadFile($fileUpload, "webinars/{$webinar->id}/files", null, $webinar->creator_id);
                    }

                    $fileInfos = $this->fileInfo($data['file_url']);

                    if (empty($fileInfos) or $fileInfos['extension'] != 'zip') {
                        return response([
                            'code' => 422,
                            'errors' => [
                                'file_url' => [trans('validation.mimes', ['attribute' => 'file', 'values' => 'zip'])]
                            ],
                        ], 422);
                    }

                    $volume = convertToMB($fileInfos['size'] ?? 0);
                    $fileInfos['extension'] = 'archive';
                    $data['interactive_file_path'] = $this->handleUnZipFile($webinar, $data);

                } elseif ($data['storage'] == 'upload') {
                    if (!empty($fileUpload)) {
                        $volume = convertToMB($fileUpload->getSize());

                        $data['file_url'] = $this->uploadFile($fileUpload, "webinars/{$webinar->id}/files", null, $webinar->creator_id);
                    }
                } elseif (in_array($data['storage'], ['s3', 'secure_host'])) {
                    if ($data['storage'] == 's3') {
                        if (!empty($fileUpload)) {
                            $data['volume'] = convertToMB($fileUpload->getSize());
                            $result = $this->uploadFileToS3($fileUpload);
                        }
                    } else {
                        if ($data['secure_host_upload_type'] == "direct") {
                            if (!empty($fileUpload)) {
                                $data['volume'] = convertToMB($fileUpload->getSize());
                                $result = $this->uploadFileToBunny($webinar, $fileUpload);
                            }
                        } else {
                            $result['status'] = true;
                            $result['path'] = $data['file_url'];
                        }
                    }

                    if (!$result['status']) {
                        return $result['path'];
                    }

                    $data['file_url'] = $result['path'];
                    $fileInfos['extension'] = $data['file_type'];
                    $fileInfos['size'] = $data['volume'];

                    $volume = $data['volume'];

                } else {
                    $volume = !empty($data['volume']) ? $data['volume'] : 0; // input is MB
                }


                $changeChapter = ($data['chapter_id'] != $file->chapter_id);
                $oldChapterId = $file->chapter_id;

                $file->update([
                    'chapter_id' => $data['chapter_id'],
                    'file' => $data['file_url'],
                    'volume' => $volume,
                    'file_type' => !empty($fileInfos) ? $fileInfos['extension'] : $data['file_type'],
                    'accessibility' => $data['accessibility'],
                    'storage' => $data['storage'],
                    'secure_host_upload_type' => $data['secure_host_upload_type'] ?? null,
                    'interactive_type' => $data['interactive_type'] ?? null,
                    'interactive_file_name' => $data['interactive_file_name'] ?? null,
                    'interactive_file_path' => $data['interactive_file_path'] ?? null,
                    'online_viewer' => (!empty($data['online_viewer']) and $data['online_viewer'] == 'on'),
                    'downloadable' => $data['downloadable'],
                    'check_previous_parts' => $data['check_previous_parts'],
                    'access_after_day' => $data['access_after_day'],
                    'status' => (!empty($data['status']) and $data['status'] == 'on') ? File::$Active : File::$Inactive,
                    'updated_at' => time()
                ]);

                if ($changeChapter) {
                    WebinarChapterItem::changeChapter($file->creator_id, $oldChapterId, $file->chapter_id, $file->id, WebinarChapterItem::$chapterFile);
                }

                $locale = $request->get('locale', getDefaultLocale());

                FileTranslation::updateOrCreate([
                    'file_id' => $file->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'interactive_quiz' => $this->normalizeInteractiveQuiz($data['interactive_quiz'] ?? null),
                    'lecture_notes' => $this->normalizeLectureNotes($data['lecture_notes'] ?? null),
                ]);

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

    private function handleUploadAndS3FileValidationByType($fileType = null, $required = true)
    {
        $rule = ($required ? 'required' : 'nullable') . '|file|max:2097152'; // 2GB max size

        if (!empty($fileType)) {
            switch ($fileType) {
                case 'pdf':
                    $rule .= '|mimes:pdf';
                    break;
                case 'power_point':
                    $rule .= '|mimes:ppt,pptx';
                    break;
                case 'sound':
                    $rule .= '|mimes:mp3,wav,ogg,aac';
                    break;
                case 'video':
                    $rule .= '|mimes:mp4,avi,mkv,mov,wmv,flv,webm';
                    break;
                case 'image':
                    $rule .= '|mimes:jpg,jpeg,png,gif,bmp,webp,svg';
                    break;
                case 'archive':
                    $rule .= '|mimes:zip,rar,tar,gz,7z';
                    break;
                case 'document':
                    $rule .= '|mimes:doc,docx,xls,xlsx,csv,txt,rtf';
                    break;
                case 'project':
                    $rule .= '';
                    break;
            }
        }

        return $rule;
    }

    public function fileInfo($path)
    {
        $file = array();

        $file_path = public_path($path);

        $filePath = pathinfo($file_path);

        $file['name'] = $filePath['filename'];
        $file['extension'] = $filePath['extension'];
        $file['size'] = filesize($file_path);

        return $file;
    }

    private function uploadFileToS3($file)
    {
        $user = auth()->user();

        $path = '/store/' . $user->id;

        $result = [
            'path' => null,
            'status' => true
        ];

        try {
            $fileName = time() . $file->getClientOriginalName();

            $storage = Storage::disk('minio');

            if (!$storage->exists($path)) {
                $storage->makeDirectory($path);
            }

            $path = $storage->put($path, $file, $fileName);
            $result['path'] = $storage->url($path);
        } catch (\Exception $ex) {

            $result = [
                'path' => response([
                    'code' => 500,
                    'message' => $ex->getMessage(),
                    'traces' => $ex->getTrace(),
                ], 500),
                'status' => false
            ];
        }

        return $result;
    }

    private function uploadFileToBunny($webinar, $file)
    {
        $result = [
            'path' => null,
            'status' => true
        ];

        try {
            $bunnyVideoStream = new BunnyVideoStream();

            $collectionId = $bunnyVideoStream->createCollection("course {$webinar->id}", true);

            if ($collectionId) {

                $videoUrl = $bunnyVideoStream->uploadVideo($file->getClientOriginalName(), $collectionId, $file);

                $result['path'] = $videoUrl;
            }
        } catch (\Exception $ex) {

            $result = [
                'path' => response([
                    'code' => 500,
                    'message' => $ex->getMessage(),
                    'traces' => $ex->getTrace(),
                ], 500),
                'status' => false
            ];
        }

        return $result;
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $file = File::where('id', $id)->first();


        if (!empty($file)) {
            $webinar = Webinar::query()->find($file->webinar_id);

            if ($file->creator_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {

                if ($file->storage == "secure_host") {
                    $bunnyVideoStream = new BunnyVideoStream();
                    $bunnyVideoStream->deleteVideo($file->file);
                }


                WebinarChapterItem::where('user_id', $file->creator_id)
                    ->where('item_id', $file->id)
                    ->where('type', WebinarChapterItem::$chapterFile)
                    ->delete();

                $file->delete();
            }
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}


