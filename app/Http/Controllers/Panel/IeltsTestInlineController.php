<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsQuestionGroup;
use App\Models\IeltsTest;
use App\Models\IeltsTestPart;
use App\Models\IeltsTestQuestion;
use App\Models\IeltsTestSection;
use App\Models\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IeltsTestInlineController extends Controller
{
    public function chooseMethod()
    {
        $this->authorizeCreatorAccess();

        return view('design_1.panel.ielts_tests_manage.choose_method', [
            'pageTitle' => 'Choose Test Creation Method',
        ]);
    }

    public function createFromBank()
    {
        $this->authorizeCreatorAccess();

        try {
            $mockGroups = IeltsQuestionGroup::where('bank_type', 'mock')
                ->where('status', 'approved')
                ->get()
                ->groupBy('skill');

            $practiceGroups = IeltsQuestionGroup::where('bank_type', 'practice')
                ->where('status', 'approved')
                ->get()
                ->groupBy('skill');
        } catch (\Throwable $e) {
            $mockGroups = collect();
            $practiceGroups = collect();
        }

        return view('design_1.panel.ielts_tests_manage.create_from_bank', [
            'pageTitle' => 'Create IELTS Test from Question Bank',
            'mockGroups' => $mockGroups,
            'practiceGroups' => $practiceGroups,
        ]);
    }

    public function createInlineComplete()
    {
        $this->authorizeCreatorAccess();

        return view('design_1.panel.ielts_tests_manage.create_inline_complete_with_groups', [
            'pageTitle' => 'Create Complete IELTS Test',
        ]);
    }

    public function storeInlineComplete(Request $request)
    {
        $this->authorizeCreatorAccess();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:mock,practice',
            'format' => 'required|in:academic,general,both',
            'difficulty_level' => 'nullable|in:beginner,intermediate,advanced,mixed',
            'target_band_min' => 'nullable|numeric|min:0|max:9',
            'target_band_max' => 'nullable|numeric|min:0|max:9',
            'questions_data' => 'required|json',
        ]);

        $questionsData = json_decode($request->input('questions_data'), true);
        if (!is_array($questionsData) || empty($questionsData['sections'])) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'No questions added to test',
                'status' => 'error',
            ]]);
        }

        // Validate based on test type
        $testType = $validated['type'];
        $sectionRequirements = [
            'listening' => ['mock' => 40, 'practice' => 0],
            'reading' => ['mock' => 40, 'practice' => 0],
            'writing' => ['mock' => 2, 'practice' => 0],
            'speaking' => ['mock' => 3, 'practice' => 0],
        ];

        $validationErrors = [];
        $totalQuestions = 0;

        foreach ($questionsData['sections'] as $skill => $sectionData) {
            $questionCount = count($sectionData['questions'] ?? []);
            $totalQuestions += $questionCount;
            $required = $sectionRequirements[$skill][$testType] ?? 0;

            if ($testType === 'mock' && $questionCount < $required) {
                $validationErrors[] = ucfirst($skill) . ": $questionCount/$required questions";
            }
        }

        if ($testType === 'mock' && !empty($validationErrors)) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Mock Test requires all sections to be complete:\n' . implode('\n', $validationErrors),
                'status' => 'error',
            ]]);
        }

        if ($testType === 'practice' && $totalQuestions === 0) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Practice Test requires at least 1 question in any section',
                'status' => 'error',
            ]]);
        }

        $slug = Str::slug($validated['title']);
        $suffix = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['title']) . '-' . $suffix;
            $suffix++;
        }

        $user = auth()->user();

        try {
            DB::transaction(function () use ($validated, $questionsData, $slug, $user, $testType) {
                $availableSkills = [];
                foreach ($questionsData['sections'] as $skill => $sectionData) {
                    if (!empty($sectionData['questions'] ?? [])) {
                        $availableSkills[$skill] = true;
                    }
                }

                $test = IeltsTest::create([
                    'title' => $validated['title'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'type' => $testType,
                    'format' => $validated['format'],
                    'difficulty_level' => $validated['difficulty_level'] ?? 'intermediate',
                    'target_band_min' => $validated['target_band_min'] ?? null,
                    'target_band_max' => $validated['target_band_max'] ?? null,
                    'has_listening' => !empty($availableSkills['listening']),
                    'has_reading' => !empty($availableSkills['reading']),
                    'has_writing' => !empty($availableSkills['writing']),
                    'has_speaking' => !empty($availableSkills['speaking']),
                    'is_active' => true,
                    'is_free' => true,
                    'created_by' => $user->id,
                    'status' => 'draft',
                    'created_at' => time(),
                ]);

                $skillConfig = [
                    'listening' => ['title' => 'Listening', 'duration' => 30],
                    'reading' => ['title' => 'Reading', 'duration' => 60],
                    'writing' => ['title' => 'Writing', 'duration' => 60],
                    'speaking' => ['title' => 'Speaking', 'duration' => 15],
                ];

                $sectionOrder = 0;
                foreach ($questionsData['sections'] as $skill => $sectionData) {
                    $questions = $sectionData['questions'] ?? [];
                    if (empty($questions)) {
                        continue;
                    }

                    $sectionOrder++;
                    $questionCount = count($questions);
                    $section = IeltsTestSection::create([
                        'test_id' => $test->id,
                        'skill' => $skill,
                        'title' => $skillConfig[$skill]['title'] ?? ucfirst($skill),
                        'description' => $sectionData['description'] ?? null,
                        'duration' => $sectionData['duration'] ?? ($skillConfig[$skill]['duration'] ?? 30),
                        'sort_order' => $sectionOrder,
                        'question_start' => 1,
                        'question_end' => $questionCount,
                        'status' => 'active',
                        'created_at' => time(),
                    ]);

                    foreach ($questions as $index => $questionData) {
                        $this->createInlineQuestion($section, $questionData, $index + 1);
                    }
                }

                $test->update([
                    'status' => 'pending_approval',
                    'submitted_for_approval_at' => time(),
                ]);

                $this->notifyApprovers($test, $user);
            });
        } catch (\Throwable $e) {
            Log::error('Error storing inline IELTS test: ' . $e->getMessage());

            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Failed to create test: ' . $e->getMessage(),
                'status' => 'error',
            ]]);
        }

        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Complete test created and submitted for approval!',
                'status' => 'success',
            ]]);
    }

    private function createInlineQuestion(IeltsTestSection $section, array $questionData, int $sortOrder): void
    {
        $questionType = $this->normalizeQuestionType($questionData['type'] ?? 'multiple_choice');
        $answerOptions = $questionData['options'] ?? [];
        $correctAnswer = $questionData['correctAnswer'] ?? null;

        if (is_array($correctAnswer) || is_object($correctAnswer)) {
            $correctAnswer = json_encode($correctAnswer);
        }

        if (is_string($correctAnswer)) {
            $trimmed = trim($correctAnswer);
            if (str_contains($trimmed, "\n")) {
                $answers = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $trimmed))));
                if (count($answers) > 1) {
                    $correctAnswer = json_encode($answers);
                }
            }
        }

        $autoGradable = !in_array($questionType, ['essay', 'speaking_prompt'], true);

        IeltsTestQuestion::create([
            'section_id' => $section->id,
            'question_number' => $sortOrder,  // Always use sequential order to ensure uniqueness
            'question_order' => $sortOrder,
            'question_type' => $questionType,
            'question_text' => $questionData['text'] ?? $questionData['question_text'] ?? '',
            'instruction' => $questionData['instruction'] ?? null,
            'explanation' => $questionData['explanation'] ?? null,
            'answer_options' => $answerOptions,
            'correct_answer' => $correctAnswer,
            'question_data' => $questionData['question_data'] ?? null,
            'table_structure' => $questionData['table_structure'] ?? null,
            'flow_data' => $questionData['flow_data'] ?? null,
            'auto_gradable' => $autoGradable,
            'points' => $questionData['points'] ?? 1,
            'word_limit' => $questionData['wordLimit'] ?? $questionData['word_limit'] ?? null,
            'accept_synonyms' => $questionData['accept_synonyms'] ?? false,
            'case_sensitive' => $questionData['case_sensitive'] ?? false,
            'created_at' => time(),
        ]);
    }

    private function notifyApprovers(IeltsTest $test, User $creator): void
    {
        $approvers = User::query()
            ->where('id', '!=', $creator->id)
            ->get()
            ->filter(function (User $user) {
                return $this->hasAnyRole($user, ['admin', 'manager', 'ceo']);
            });

        foreach ($approvers as $approver) {
            Notification::create([
                'user_id' => $approver->id,
                'sender' => Notification::$SystemSender,
                'title' => 'New IELTS Test Pending Approval',
                'message' => "'{$test->title}' is ready for review.",
                'type' => 'single',
                'created_at' => time(),
            ]);
        }
    }

    /**
     * Store test with question groups and media uploads (NEW HIERARCHICAL STRUCTURE)
     */
    public function storeWithQuestionGroups(Request $request)
    {
        $this->authorizeCreatorAccess();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:mock,practice',
            'format' => 'required|in:academic,general,both',
            'difficulty_level' => 'nullable|in:beginner,intermediate,advanced,mixed',
            'target_band_min' => 'nullable|numeric|min:0|max:9',
            'target_band_max' => 'nullable|numeric|min:0|max:9',
            'question_groups_data' => 'required|json',
            'section_media' => 'nullable|array',
            'section_media.*.audio' => 'nullable|file|mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/mp4,audio/x-m4a,audio/ogg|max:51200',
            'group_media' => 'nullable|array',
            'group_media.*.audio' => 'nullable|file|mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/mp4,audio/x-m4a,audio/ogg|max:51200',
            'group_media.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'group_media.*.video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:204800',
        ]);

        $groupsData = json_decode($request->input('question_groups_data'), true);
        if (!is_array($groupsData) || empty($groupsData['sections'])) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'No question groups added to test',
                'status' => 'error',
            ]]);
        }

        // Validate structure
        $testType = $validated['type'];
        $validationErrors = [];
        $totalParts = 0;
        $totalGroups = 0;
        $totalQuestions = 0;

        foreach ($groupsData['sections'] as $skill => $sectionData) {
            $parts = $sectionData['parts'] ?? $sectionData['groups'] ?? [];
            $partCount = 0;
            $groupCount = 0;
            $questionCount = 0;

            if (!empty($parts)) {
                $partCount = count($parts);

                foreach ($parts as $partData) {
                    $groups = $partData['groups'] ?? [];

                    if (empty($groups) && !empty($partData['questions'])) {
                        $groups = [$partData];
                    }

                    $groupCount += count($groups);

                    foreach ($groups as $groupData) {
                        $questionCount += count($groupData['questions'] ?? []);
                    }
                }
            }

            $totalParts += $partCount;
            $totalGroups += $groupCount;
            $totalQuestions += $questionCount;

            if ($testType === 'mock') {
                $requiredParts = [
                    'listening' => 4,
                    'reading' => 3,
                    'writing' => 2,
                    'speaking' => 3,
                ][$skill] ?? 0;

                if ($partCount < $requiredParts) {
                    $validationErrors[] = ucfirst($skill) . ": needs {$requiredParts} parts";
                }
            }
        }

        if ($testType === 'mock' && !empty($validationErrors)) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Mock Test requires parts for all 4 sections:\n' . implode('\n', $validationErrors),
                'status' => 'error',
            ]]);
        }

        if ($testType === 'practice' && $totalParts === 0) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Practice Test requires at least 1 part',
                'status' => 'error',
            ]]);
        }

        $slug = Str::slug($validated['title']);
        $suffix = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['title']) . '-' . $suffix;
            $suffix++;
        }

        $user = auth()->user();

        try {
            DB::transaction(function () use ($validated, $groupsData, $slug, $user, $testType, $request) {
                $sectionAudioPaths = [];
                foreach ($groupsData['sections'] as $skill => $sectionData) {
                    if ($skill === 'listening') {
                        if ($request->hasFile('section_media.listening.audio')) {
                            $sectionAudioPaths['listening'] = $request->file('section_media.listening.audio')
                                ->store('ielts/test_sections/audio', 'public');
                        }
                    }
                }

                // Create test
                $test = IeltsTest::create([
                    'title' => $validated['title'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'type' => $testType,
                    'format' => $validated['format'],
                    'difficulty_level' => $validated['difficulty_level'] ?? 'intermediate',
                    'target_band_min' => $validated['target_band_min'] ?? null,
                    'target_band_max' => $validated['target_band_max'] ?? null,
                    'is_active' => true,
                    'is_free' => true,
                    'created_by' => $user->id,
                    'status' => 'draft',
                    'created_at' => time(),
                ]);

                $skillConfig = [
                    'listening' => ['title' => 'Listening', 'duration' => 30],
                    'reading' => ['title' => 'Reading', 'duration' => 60],
                    'writing' => ['title' => 'Writing', 'duration' => 60],
                    'speaking' => ['title' => 'Speaking', 'duration' => 15],
                ];

                $skillsUsed = [];
                $sectionOrder = 0;
                $questionCount = 0;

                // Process each skill section
                foreach ($groupsData['sections'] as $skill => $sectionData) {
                    $parts = $sectionData['parts'] ?? $sectionData['groups'] ?? [];
                    if (empty($parts)) {
                        continue;
                    }

                    $sectionOrder++;
                    $sectionQuestionStart = $questionCount + 1;

                    // Create section
                    $section = IeltsTestSection::create([
                        'test_id' => $test->id,
                        'skill' => $skill,
                        'title' => $skillConfig[$skill]['title'] ?? ucfirst($skill),
                        'description' => $sectionData['description'] ?? null,
                        'duration' => $sectionData['duration'] ?? ($skillConfig[$skill]['duration'] ?? 30),
                        'audio_file' => $sectionAudioPaths[$skill] ?? null,
                        'sort_order' => $sectionOrder,
                        'status' => 'active',
                        'created_at' => time(),
                    ]);

                    // Process parts and nested question groups
                    $partOrder = 0;
                    foreach ($parts as $partData) {
                        $partOrder++;

                        $part = $this->createPartWithMedia($section, $partData, $request, $partOrder);

                        $groupsInPart = $partData['groups'] ?? [];
                        if (empty($groupsInPart) && !empty($partData['questions'])) {
                            $groupsInPart = [$partData];
                        }

                        $groupOrder = 0;
                        foreach ($groupsInPart as $groupData) {
                            $groupOrder++;
                            $group = $this->createQuestionGroupWithMedia($section, $groupData, $request, $user->id, $part, $groupOrder);

                            $questions = $groupData['questions'] ?? [];
                            foreach ($questions as $questionData) {
                                $questionCount++;
                                $this->createQuestionInPart($section, $part, $group, $questionData, $questionCount);
                            }
                        }
                    }

                    // Update section question range (only if there are questions)
                    if ($questionCount > 0) {
                        $section->update([
                            'question_start' => $sectionQuestionStart,
                            'question_end' => $questionCount,
                        ]);
                    } else {
                        $section->update([
                            'question_start' => 0,
                            'question_end' => 0,
                        ]);
                    }

                    $skillsUsed[$skill] = true;
                }

                // Update test with skills used
                $test->update([
                    'has_listening' => !empty($skillsUsed['listening']),
                    'has_reading' => !empty($skillsUsed['reading']),
                    'has_writing' => !empty($skillsUsed['writing']),
                    'has_speaking' => !empty($skillsUsed['speaking']),
                    'status' => 'pending_approval',
                    'submitted_for_approval_at' => time(),
                ]);

                $this->notifyApprovers($test, $user);
            });
        } catch (\Throwable $e) {
            Log::error('Error storing IELTS test with question groups: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Failed to create test: ' . $e->getMessage(),
                'status' => 'error',
            ]]);
        }

        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Complete test with question groups created!',
                'status' => 'success',
            ]]);
    }

    /**
     * Create question group with media file uploads
     */
    private function createQuestionGroupWithMedia(
        IeltsTestSection $section,
        array $groupData,
        Request $request,
        int $creatorId,
        ?IeltsTestPart $part = null,
        int $groupOrder = 1
    ): IeltsQuestionGroup {
        $uploadId = $groupData['upload_id'] ?? null;
        $difficultyLevel = trim((string) ($groupData['difficulty_level'] ?? ''));

        if ($difficultyLevel === '') {
            $difficultyLevel = 'intermediate';
        }

        $audioFilePath = null;
        $taskImagePath = null;
        $videoFilePath = null;

        if (!empty($uploadId) && $request->hasFile("group_media.$uploadId.audio")) {
            $audioFilePath = $request->file("group_media.$uploadId.audio")
                ->store('ielts/question_groups/audio', 'public');
        }

        if (!empty($uploadId) && $request->hasFile("group_media.$uploadId.image")) {
            $taskImagePath = $request->file("group_media.$uploadId.image")
                ->store('ielts/question_groups/images', 'public');
        }

        if (!empty($uploadId) && $request->hasFile("group_media.$uploadId.video")) {
            $videoFilePath = $request->file("group_media.$uploadId.video")
                ->store('ielts/question_groups/videos', 'public');
        }

        $groupRecord = IeltsQuestionGroup::create([
            'section_id' => $section->id,
            'part_id' => $part?->id,
            'creator_id' => $creatorId,
            'skill' => $section->skill,
            'question_type' => $groupData['question_type'] ?? 'multiple_choice',
            'title' => $groupData['title'] ?? '',
            'description' => $groupData['description'] ?? null,
            'instructions' => $groupData['instructions'] ?? null,
            'passage' => $groupData['passage'] ?? null,
            'transcript' => $groupData['transcript'] ?? null,
            'audio_file' => $audioFilePath,
            'task_image' => $taskImagePath,
            'video_file' => $videoFilePath,
            'max_words' => $groupData['max_words'] ?? null,
            'target_band' => $groupData['target_band'] ?? null,
            'difficulty_level' => $difficultyLevel,
            'status' => 'pending',
            'sort_order' => $groupOrder,
            'created_at' => time(),
        ]);

        return $groupRecord;
    }

    /**
     * Create a single question within a question group
     */
    private function createInlineQuestionInGroup(
        IeltsTestSection $section,
        IeltsQuestionGroup $group,
        array $questionData,
        int $questionNumber
    ): void {
        $questionType = $this->normalizeQuestionType($questionData['type'] ?? 'multiple_choice');
        $answerOptions = $questionData['options'] ?? [];
        $correctAnswer = $questionData['correctAnswer'] ?? null;

        if (is_array($correctAnswer) || is_object($correctAnswer)) {
            $correctAnswer = json_encode($correctAnswer);
        }

        if (is_string($correctAnswer)) {
            $trimmed = trim($correctAnswer);
            if (str_contains($trimmed, "\n")) {
                $answers = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $trimmed))));
                if (count($answers) > 1) {
                    $correctAnswer = json_encode($answers);
                }
            }
        }

        $autoGradable = !in_array($questionType, ['essay', 'speaking_prompt'], true);

        IeltsTestQuestion::create([
            'section_id' => $section->id,
            'question_group_id' => $group->id,
            'question_number' => $questionNumber,
            'question_order' => $questionNumber,
            'question_type' => $questionType,
            'question_text' => $questionData['text'] ?? $questionData['question_text'] ?? '',
            'instruction' => $questionData['instruction'] ?? null,
            'answer_options' => $answerOptions,
            'correct_answer' => $correctAnswer,
            'question_data' => $questionData['question_data'] ?? null,
            'table_structure' => $questionData['table_structure'] ?? null,
            'flow_data' => $questionData['flow_data'] ?? null,
            'auto_gradable' => $autoGradable,
            'points' => $questionData['points'] ?? 1,
            'word_limit' => $questionData['wordLimit'] ?? $questionData['word_limit'] ?? null,
            'accept_synonyms' => $questionData['accept_synonyms'] ?? false,
            'case_sensitive' => $questionData['case_sensitive'] ?? false,
            'created_at' => time(),
        ]);
    }

    /**
     * Create a Part with media files (NEW: replaces group-centric approach with part-centric)
     */
    private function createPartWithMedia(
        IeltsTestSection $section,
        array $partData,
        Request $request,
        int $partOrder
    ): IeltsTestPart {
        $uploadId = $partData['upload_id'] ?? null;
        $difficultyLevel = trim((string) ($partData['difficulty_level'] ?? ''));

        if ($difficultyLevel === '') {
            $difficultyLevel = 'intermediate';
        }

        $audioFilePath = null;
        $taskImagePath = null;
        $videoFilePath = null;

        if (!empty($uploadId) && $request->hasFile("group_media.$uploadId.audio")) {
            $audioFilePath = $request->file("group_media.$uploadId.audio")
                ->store('ielts/test_parts/audio', 'public');
        }

        if (!empty($uploadId) && $request->hasFile("group_media.$uploadId.image")) {
            $taskImagePath = $request->file("group_media.$uploadId.image")
                ->store('ielts/test_parts/images', 'public');
        }

        if (!empty($uploadId) && $request->hasFile("group_media.$uploadId.video")) {
            $videoFilePath = $request->file("group_media.$uploadId.video")
                ->store('ielts/test_parts/videos', 'public');
        }

        $part = IeltsTestPart::create([
            'section_id' => $section->id,
            'title' => $partData['title'] ?? "Part {$partOrder}",
            'description' => $partData['description'] ?? null,
            'instructions' => $partData['instructions'] ?? null,
            'passage' => $partData['passage'] ?? null,
            'transcript' => $partData['transcript'] ?? null,
            'audio_file' => $audioFilePath,
            'task_image' => $taskImagePath,
            'video_file' => $videoFilePath,
            'sort_order' => $partOrder,
            'status' => 'active',
            'difficulty_level' => $difficultyLevel,
            'created_at' => time(),
        ]);

        return $part;
    }

    /**
     * Create a question within a Part
     */
    private function createQuestionInPart(
        IeltsTestSection $section,
        IeltsTestPart $part,
        IeltsQuestionGroup $group,
        array $questionData,
        int $questionNumber
    ): void {
        $questionType = $this->normalizeQuestionType($questionData['type'] ?? 'multiple_choice');
        $answerOptions = $questionData['options'] ?? [];
        $correctAnswer = $questionData['correctAnswer'] ?? null;

        if (is_array($correctAnswer) || is_object($correctAnswer)) {
            $correctAnswer = json_encode($correctAnswer);
        }

        if (is_string($correctAnswer)) {
            $trimmed = trim($correctAnswer);
            if (str_contains($trimmed, "\n")) {
                $answers = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $trimmed))));
                if (count($answers) > 1) {
                    $correctAnswer = json_encode($answers);
                }
            }
        }

        $autoGradable = !in_array($questionType, ['essay', 'speaking_prompt'], true);

        IeltsTestQuestion::create([
            'section_id' => $section->id,
            'question_group_id' => $group->id,
            'part_id' => $part->id,
            'question_number' => $questionNumber,
            'question_order' => $questionNumber,
            'question_type' => $questionType,
            'question_text' => $questionData['text'] ?? $questionData['question_text'] ?? '',
            'instruction' => $questionData['instruction'] ?? null,
            'answer_options' => $answerOptions,
            'correct_answer' => $correctAnswer,
            'question_data' => $questionData['question_data'] ?? null,
            'table_structure' => $questionData['table_structure'] ?? null,
            'flow_data' => $questionData['flow_data'] ?? null,
            'auto_gradable' => $autoGradable,
            'points' => $questionData['points'] ?? 1,
            'word_limit' => $questionData['wordLimit'] ?? $questionData['word_limit'] ?? null,
            'accept_synonyms' => $questionData['accept_synonyms'] ?? false,
            'case_sensitive' => $questionData['case_sensitive'] ?? false,
            'created_at' => time(),
        ]);
    }

    private function normalizeQuestionType(string $type): string
    {
        $mapping = [
            'multiple_choice' => 'multiple_choice',
            'multiple_select' => 'multiple_select',
            'true_false_ng' => 'true_false_ng',
            'yes_no_ng' => 'yes_no_ng',
            'fill_blank' => 'fill_blank',
            'sentence_completion' => 'sentence_completion',
            'note_completion' => 'note_completion',
            'table_completion' => 'table_completion',
            'flow_chart' => 'flow_chart',
            'diagram_label' => 'diagram_label',
            'short_answer' => 'short_answer',
            'essay' => 'essay',
            'speaking_prompt' => 'essay',
            'matching' => 'matching',
        ];

        return $mapping[$type] ?? 'multiple_choice';
    }

    private function authorizeCreatorAccess(): void
    {
        $user = auth()->user();

        if (!$user || !$this->hasAnyRole($user, ['admin', 'teacher', 'organization', 'manager', 'ceo'])) {
            abort(403, 'Unauthorized');
        }
    }

    private function hasAnyRole(User $user, array $roles): bool
    {
        $roleName = strtolower(optional($user->role)->name ?? '');

        return in_array($roleName, $roles, true);
    }
}
