<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsQuestionGroup;
use App\Models\IeltsTest;
use App\Models\IeltsTestPart;
use App\Models\IeltsTestQuestion;
use App\Models\IeltsTestSection;
use App\Models\IeltsTestAttempt;
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
            'formAction' => route('panel.my_ielts_tests.store_with_groups'),
            'submitButtonText' => 'Submit Test for Approval',
            'cancelUrl' => route('panel.my_ielts_tests.index'),
            'currentTestType' => null,
            'testData' => [
                'sections' => [
                    'listening' => ['parts' => []],
                    'reading' => ['parts' => []],
                    'writing' => ['parts' => []],
                    'speaking' => ['parts' => []],
                    'grammar' => ['parts' => []],
                    'vocabulary' => ['parts' => []],
                ],
            ],
        ]);
    }

    public function editInlineComplete($id)
    {
        $this->authorizeCreatorAccess();

        $test = IeltsTest::with([
            'sections.parts.questionGroups',
            'sections.parts.questions',
            'sections.questions',
            'attempts',
        ])
            ->where('created_by', auth()->id())
            ->findOrFail($id);

        return view('design_1.panel.ielts_tests_manage.create_inline_complete_with_groups', [
            'pageTitle' => 'Edit IELTS Test',
            'test' => $test,
            'formAction' => route('panel.my_ielts_tests.update_inline_complete', $test->id),
            'submitButtonText' => 'Update & Submit for Approval',
            'cancelUrl' => route('panel.my_ielts_tests.index'),
            'currentTestType' => $test->type,
            'testData' => $this->buildInlineTestData($test),
        ]);
    }

    public function storeInlineComplete(Request $request)
    {
        $this->authorizeCreatorAccess();
        $saveAsDraft = $request->input('submit_action') === 'draft';

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:mock,practice',
            'format' => 'required|in:academic,general,both',
            'difficulty_level' => 'nullable|in:beginner,intermediate,advanced,mixed',
            'target_band_min' => 'nullable|numeric|min:0|max:9',
            'target_band_max' => 'nullable|numeric|min:0|max:9',
            'questions_data' => ($saveAsDraft ? 'nullable' : 'required') . '|json',
        ]);

        $questionsData = json_decode($request->input('questions_data') ?: '{"sections":{}}', true);
        if (!is_array($questionsData) || empty($questionsData['sections'])) {
            if ($saveAsDraft) {
                $questionsData = ['sections' => []];
            } else {
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'No questions added to test',
                    'status' => 'error',
                ]]);
            }
        }

        $testType = $validated['type'];

        if (!$saveAsDraft) {
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
        }

        $slug = Str::slug($validated['title']);
        $suffix = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['title']) . '-' . $suffix;
            $suffix++;
        }

        $user = auth()->user();

        try {
            DB::transaction(function () use ($validated, $questionsData, $slug, $user, $testType, $saveAsDraft) {
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
                    'status' => $saveAsDraft ? 'draft' : 'pending_approval',
                    'submitted_for_approval_at' => $saveAsDraft ? null : time(),
                    'created_at' => time(),
                ]);

                $skillConfig = [
                    'listening' => ['title' => 'Listening', 'duration' => 30],
                    'reading' => ['title' => 'Reading', 'duration' => 60],
                    'writing' => ['title' => 'Writing', 'duration' => 60],
                    'speaking' => ['title' => 'Speaking', 'duration' => 15],
                    'grammar' => ['title' => 'Grammar', 'duration' => 45],
                    'vocabulary' => ['title' => 'Vocabulary', 'duration' => 45],
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

                if (!$saveAsDraft) {
                    $test->update([
                        'status' => 'pending_approval',
                        'submitted_for_approval_at' => time(),
                    ]);

                    $this->notifyApprovers($test, $user);
                }
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
                'msg' => $saveAsDraft ? 'Draft saved successfully!' : 'Complete test created and submitted for approval!',
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
            'question_text' => $this->sanitizeInlineText($questionData['text'] ?? $questionData['question_text'] ?? '', 65000) ?? '',
            'instruction' => $this->sanitizeInlineText($questionData['instruction'] ?? null, 65000),
            'explanation' => $this->sanitizeInlineText($questionData['explanation'] ?? null, 65000),
            'answer_options' => $answerOptions,
            'correct_answer' => is_string($correctAnswer) ? $this->sanitizeInlineText($correctAnswer, 65000) : $correctAnswer,
            'question_data' => $this->sanitizeInlineNestedPayload($questionData['question_data'] ?? null),
            'table_structure' => $this->sanitizeInlineNestedPayload($questionData['table_structure'] ?? null),
            'flow_data' => $this->sanitizeInlineNestedPayload($questionData['flow_data'] ?? null),
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
            $notificationData = [
                'user_id' => $approver->id,
                'sender' => Notification::$SystemSender,
                'title' => 'New IELTS Test Pending Approval',
                'message' => "'{$test->title}' is ready for review.",
                'type' => 'single',
                'created_at' => time(),
            ];

            if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'action_url')) {
                $notificationData['action_url'] = getAdminPanelUrl("/ielts-tests/{$test->id}/review");
            }

            Notification::create($notificationData);
        }
    }

    /**
     * Store test with question groups and media uploads (NEW HIERARCHICAL STRUCTURE)
     */
    public function storeWithQuestionGroups(Request $request)
    {
        $this->authorizeCreatorAccess();

        $submitAction = $request->input('submit_action', 'submit');
        $saveAsDraft = in_array($submitAction, ['draft', 'preview'], true);
        $previewMode = $submitAction === 'preview';

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:mock,practice',
            'format' => 'required|in:academic,general,both',
            'difficulty_level' => 'nullable|in:beginner,intermediate,advanced,mixed',
            'target_band_min' => 'nullable|numeric|min:0|max:9',
            'target_band_max' => 'nullable|numeric|min:0|max:9',
            'question_groups_data' => ($saveAsDraft ? 'nullable' : 'required') . '|json',
            'section_media' => 'nullable|array',
            'section_media.*.audio' => 'nullable|file|mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/mp4,audio/x-m4a,audio/ogg|max:51200',
            'group_media' => 'nullable|array',
            'group_media.*.audio' => 'nullable|file|mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/mp4,audio/x-m4a,audio/ogg|max:51200',
            'group_media.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'group_media.*.video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:204800',
        ]);

        $groupsData = json_decode($request->input('question_groups_data') ?: '{"sections":{}}', true);
        if (!is_array($groupsData) || empty($groupsData['sections'])) {
            if ($saveAsDraft) {
                $groupsData = ['sections' => []];
            } else {
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'No question groups added to test',
                    'status' => 'error',
                ]]);
            }
        }

        $groupsData = $this->sanitizeInlineGroupsPayload($groupsData);

        if ($previewMode) {
            $hasAnyPart = false;
            foreach ($groupsData['sections'] as $sectionData) {
                $parts = $sectionData['parts'] ?? $sectionData['groups'] ?? [];
                if (!empty($parts)) {
                    $hasAnyPart = true;
                    break;
                }
            }

            if (!$hasAnyPart) {
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'Add at least 1 part before previewing the test.',
                    'status' => 'error',
                ]]);
            }
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

        if (!$saveAsDraft && $testType === 'mock' && !empty($validationErrors)) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Mock Test requires parts for all 4 sections:\n' . implode('\n', $validationErrors),
                'status' => 'error',
            ]]);
        }

        if (!$saveAsDraft && $testType === 'practice' && $totalParts === 0) {
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

        $createdTestId = null;
        try {
            DB::transaction(function () use ($validated, $groupsData, $slug, $user, $testType, $request, $saveAsDraft, &$createdTestId) {
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
                    'status' => $saveAsDraft ? 'draft' : 'pending_approval',
                    'submitted_for_approval_at' => $saveAsDraft ? null : time(),
                    'created_at' => time(),
                ]);

                $createdTestId = $test->id;

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
                                // For table completion questions, count each blank as a separate question number
                                $questionType = $questionData['type'] ?? 'multiple_choice';
                                if ($questionType === 'table_completion') {
                                    $slotCount = $questionData['slotCount'] ?? 1;
                                    $this->createQuestionInPart($section, $part, $group, $questionData, $questionCount + 1);
                                    $questionCount += $slotCount;
                                } else {
                                    $questionCount++;
                                    $this->createQuestionInPart($section, $part, $group, $questionData, $questionCount);
                                }
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
                    'status' => $saveAsDraft ? 'draft' : 'pending_approval',
                    'submitted_for_approval_at' => $saveAsDraft ? null : time(),
                ]);

                if (!$saveAsDraft) {
                    $this->notifyApprovers($test, $user);
                }
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

        if ($previewMode && $createdTestId) {
            return redirect()->route('panel.my_ielts_tests.preview_student', $createdTestId);
        }

        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => $saveAsDraft ? 'Draft saved successfully!' : 'Complete test with question groups created!',
                'status' => 'success',
            ]]);
    }

    public function updateInlineComplete(Request $request, $id)
    {
        $this->authorizeCreatorAccess();
        $submitAction = $request->input('submit_action', 'submit');
        $saveAsDraft = in_array($submitAction, ['draft', 'preview'], true);
        $previewMode = $submitAction === 'preview';

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:mock,practice',
            'format' => 'required|in:academic,general,both',
            'difficulty_level' => 'nullable|in:beginner,intermediate,advanced,mixed',
            'target_band_min' => 'nullable|numeric|min:0|max:9',
            'target_band_max' => 'nullable|numeric|min:0|max:9',
            'question_groups_data' => ($saveAsDraft ? 'nullable' : 'required') . '|json',
            'section_media' => 'nullable|array',
            'section_media.*.audio' => 'nullable|file|mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/mp4,audio/x-m4a,audio/ogg|max:51200',
            'group_media' => 'nullable|array',
            'group_media.*.audio' => 'nullable|file|mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/mp4,audio/x-m4a,audio/ogg|max:51200',
            'group_media.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'group_media.*.video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:204800',
        ]);

        $groupsData = json_decode($request->input('question_groups_data') ?: '{"sections":{}}', true);
        if (!is_array($groupsData) || empty($groupsData['sections'])) {
            if ($saveAsDraft) {
                $groupsData = ['sections' => []];
            } else {
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'No question groups added to test',
                    'status' => 'error',
                ]]);
            }
        }

        $groupsData = $this->sanitizeInlineGroupsPayload($groupsData);

        if ($previewMode) {
            $hasAnyPart = false;
            foreach ($groupsData['sections'] as $sectionData) {
                $parts = $sectionData['parts'] ?? $sectionData['groups'] ?? [];
                if (!empty($parts)) {
                    $hasAnyPart = true;
                    break;
                }
            }

            if (!$hasAnyPart) {
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'Add at least 1 part before previewing the test.',
                    'status' => 'error',
                ]]);
            }
        }

        $test = $this->findOwnedInlineTestOrFail($id);
        $testType = $validated['type'];

        if (!$saveAsDraft) {
            $sectionRequirements = [
                'listening' => ['mock' => 4, 'practice' => 0],
                'reading' => ['mock' => 3, 'practice' => 0],
                'writing' => ['mock' => 2, 'practice' => 0],
                'speaking' => ['mock' => 3, 'practice' => 0],
            ];

            $validationErrors = [];
            $totalParts = 0;

            foreach ($groupsData['sections'] as $skill => $sectionData) {
                $parts = $sectionData['parts'] ?? $sectionData['groups'] ?? [];
                $partCount = 0;

                if (!empty($parts)) {
                    $partCount = count($parts);
                }

                $totalParts += $partCount;

                if ($testType === 'mock') {
                    $requiredParts = [
                        'listening' => 4,
                        'reading' => 3,
                        'writing' => 2,
                        'speaking' => 3,
                    ][$skill] ?? 0;

                    if ($partCount < $requiredParts) {
                        $validationErrors[] = ucfirst($skill) . ': needs ' . $requiredParts . ' parts';
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
        }

        $slug = $test->slug ?: Str::slug($validated['title']);
        $suffix = 1;
        while (IeltsTest::where('slug', $slug)->where('id', '!=', $test->id)->exists()) {
            $slug = Str::slug($validated['title']) . '-' . $suffix;
            $suffix++;
        }

        $user = auth()->user();

        try {
            DB::transaction(function () use ($validated, $groupsData, $slug, $user, $testType, $request, $test, $saveAsDraft) {
                $test->update([
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
                    'status' => $saveAsDraft ? 'draft' : 'pending_approval',
                    'submitted_for_approval_at' => $saveAsDraft ? null : time(),
                    'approved_by' => null,
                    'approved_at' => null,
                ]);

                foreach ($test->sections as $section) {
                    $section->delete();
                }

                $this->persistInlineQuestionGroups($test, $groupsData, $request, $user, !$saveAsDraft);

                if ($saveAsDraft) {
                    $test->update([
                        'status' => 'draft',
                        'submitted_for_approval_at' => null,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            Log::error('Error updating inline IELTS test: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Failed to update test: ' . $e->getMessage(),
                'status' => 'error',
            ]]);
        }

        if ($previewMode) {
            return redirect()->route('panel.my_ielts_tests.preview_student', $test->id);
        }

        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => $saveAsDraft ? 'Draft saved successfully!' : 'Test updated and submitted for approval!',
                'status' => 'success',
            ]]);
    }

    public function previewAsStudent($id)
    {
        $this->authorizeCreatorAccess();

        $test = $this->findOwnedInlineTestOrFail($id);
        $user = auth()->user();

        $sections = $test->sections()->orderBy('sort_order')->get();
        if ($sections->isEmpty()) {
            return redirect()->route('panel.my_ielts_tests.edit_inline', $test->id)->with(['toast' => [
                'title' => 'Error',
                'msg' => 'The test has no sections to preview yet.',
                'status' => 'error',
            ]]);
        }

        IeltsTestAttempt::where('test_id', $test->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->delete();

        $totalQuestions = (int) $sections->sum(function ($section) {
            if (!empty($section->question_start) && !empty($section->question_end) && $section->question_end >= $section->question_start) {
                return (int) $section->question_end - (int) $section->question_start + 1;
            }

            return 0;
        });

        $startingSection = $sections->first();

        $attemptNumber = IeltsTestAttempt::where('test_id', $test->id)
            ->where('user_id', $user->id)
            ->count() + 1;

        $attempt = IeltsTestAttempt::create([
            'test_id' => $test->id,
            'user_id' => $user->id,
            'attempt_number' => $attemptNumber,
            'status' => 'in_progress',
            'current_skill' => $startingSection->skill,
            'current_section_id' => $startingSection->id,
            'started_at' => time(),
            'total_questions' => $totalQuestions,
            'remaining_time_seconds' => (int) (($test->total_duration ?? 0) * 60),
            'updated_at' => time(),
        ]);

        session()->put('mentor_preview_attempt_id', $attempt->id);
        session()->put('mentor_preview_test_id', $test->id);

        return redirect()->route('panel.ielts_tests.take', $attempt->id);
    }

    public function exitPreview($id)
    {
        $this->authorizeCreatorAccess();

        $test = $this->findOwnedInlineTestOrFail($id);
        $userId = auth()->id();

        $previewAttemptId = (int) session('mentor_preview_attempt_id', 0);
        if ($previewAttemptId > 0) {
            IeltsTestAttempt::query()
                ->where('id', $previewAttemptId)
                ->where('test_id', $test->id)
                ->where('user_id', $userId)
                ->where('status', 'in_progress')
                ->delete();
        }

        session()->forget('mentor_preview_attempt_id');
        session()->forget('mentor_preview_test_id');

        return redirect()->route('panel.my_ielts_tests.edit_inline', $test->id)->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Exited student preview mode.',
            'status' => 'success',
        ]]);
    }

    private function persistInlineQuestionGroups(IeltsTest $test, array $groupsData, Request $request, User $user, bool $submitForApproval = true): void
    {
        $sectionAudioPaths = [];
        foreach ($groupsData['sections'] as $skill => $sectionData) {
            if ($skill === 'listening') {
                $sectionAudioPaths['listening'] = $sectionData['files']['audio'] ?? $sectionData['audio_file'] ?? null;

                if ($request->hasFile('section_media.listening.audio')) {
                    $sectionAudioPaths['listening'] = $request->file('section_media.listening.audio')
                        ->store('ielts/test_sections/audio', 'public');
                }
            }
        }

        $skillConfig = [
            'listening' => ['title' => 'Listening', 'duration' => 30],
            'reading' => ['title' => 'Reading', 'duration' => 60],
            'writing' => ['title' => 'Writing', 'duration' => 60],
            'speaking' => ['title' => 'Speaking', 'duration' => 15],
            'grammar' => ['title' => 'Grammar', 'duration' => 45],
            'vocabulary' => ['title' => 'Vocabulary', 'duration' => 45],
        ];

        $skillsUsed = [];
        $sectionOrder = 0;
        $questionCount = 0;

        foreach ($groupsData['sections'] as $skill => $sectionData) {
            $parts = $sectionData['parts'] ?? $sectionData['groups'] ?? [];
            if (empty($parts)) {
                continue;
            }

            $sectionOrder++;
            $sectionQuestionStart = $questionCount + 1;

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

                    foreach (($groupData['questions'] ?? []) as $questionData) {
                        // For table completion questions, count each blank as a separate question number
                        $questionType = $questionData['type'] ?? 'multiple_choice';
                        if ($questionType === 'table_completion') {
                            $slotCount = $questionData['slotCount'] ?? 1;
                            $this->createQuestionInPart($section, $part, $group, $questionData, $questionCount + 1);
                            $questionCount += $slotCount;
                        } else {
                            $questionCount++;
                            $this->createQuestionInPart($section, $part, $group, $questionData, $questionCount);
                        }
                    }
                }
            }

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

        $test->update([
            'has_listening' => !empty($skillsUsed['listening']),
            'has_reading' => !empty($skillsUsed['reading']),
            'has_writing' => !empty($skillsUsed['writing']),
            'has_speaking' => !empty($skillsUsed['speaking']),
            'status' => $submitForApproval ? 'pending_approval' : 'draft',
            'submitted_for_approval_at' => $submitForApproval ? time() : null,
        ]);

        if ($submitForApproval) {
            $this->notifyApprovers($test, $user);
        }
    }

    private function buildInlineTestData(IeltsTest $test): array
    {
        $data = [
            'sections' => [
                'listening' => ['parts' => []],
                'reading' => ['parts' => []],
                'writing' => ['parts' => []],
                'speaking' => ['parts' => []],
                'grammar' => ['parts' => []],
                'vocabulary' => ['parts' => []],
            ],
        ];

        foreach ($test->sections as $section) {
            $skill = $section->skill;
            if (!isset($data['sections'][$skill])) {
                $data['sections'][$skill] = ['parts' => []];
            }

            $questionsByPart = $section->questions->groupBy('part_id');

            foreach ($section->parts as $part) {
                $partQuestions = $questionsByPart->get($part->id, collect());
                $partEntry = [
                    'id' => $part->id,
                    'upload_id' => 'existing_part_' . $part->id,
                    'title' => $part->title ?: ('Part ' . $part->sort_order),
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
                    $groupQuestions = $group->id ? $partQuestions->where('question_group_id', $group->id) : $partQuestions;

                    $partEntry['groups'][] = [
                        'id' => $group->id,
                        'upload_id' => 'existing_group_' . $group->id,
                        'title' => $group->title ?: $partEntry['title'],
                        'question_type' => $this->normalizeInlineQuestionType($group->question_type ?? ($groupQuestions->first()->question_type ?? 'short_answer')),
                        'max_words' => $group->max_words,
                        'target_band' => $group->target_band,
                        'passage' => $group->passage ?: $part->passage,
                        'task_image' => $group->task_image ?: $part->task_image,
                        'files' => [
                            'audio' => $group->audio_path ?? $group->audio_file ?? null,
                            'image' => $group->task_image ?? null,
                            'video' => $group->video_file ?? null,
                        ],
                        'questions' => $groupQuestions->sortBy('question_number')->map(function (IeltsTestQuestion $question) {
                            return $this->buildInlineQuestionData($question);
                        })->values()->all(),
                    ];
                }

                $data['sections'][$skill]['files'] = [
                    'audio' => $section->audio_file ?? null,
                    'image' => $section->image_file ?? null,
                    'video' => $section->video_file ?? null,
                ];

                $data['sections'][$skill]['parts'][] = $partEntry;
            }
        }

        return $data;
    }

    private function buildInlineQuestionData(IeltsTestQuestion $question): array
    {
        $questionType = $this->normalizeInlineQuestionType($question->question_type ?? 'short_answer');
        $answerOptions = $question->answer_options ?? [];
        $correctAnswer = $question->correct_answer;
        $correctAnswers = null;

        if (is_string($correctAnswer)) {
            $decodedCorrect = json_decode($correctAnswer, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $correctAnswer = $decodedCorrect;
            }
        }

        if (is_array($correctAnswer) && in_array($questionType, ['multiple_choice_multiple', 'note_completion'], true)) {
            $correctAnswers = array_values(array_filter(array_map('trim', $correctAnswer)));
        } elseif (is_string($correctAnswer) && $questionType === 'multiple_choice_multiple') {
            $correctAnswers = array_values(array_filter(array_map('trim', explode(',', $correctAnswer))));
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

        return $payload;
    }

    private function normalizeInlineQuestionType(string $type): string
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
            'short_answer' => 'short_answer',
            'essay' => 'essay',
            'speaking_prompt' => 'essay',
        ];

        return $mapping[$type] ?? $type;
    }

    private function findOwnedInlineTestOrFail($id): IeltsTest
    {
        return IeltsTest::with(['sections.parts.questionGroups', 'sections.parts.questions', 'sections.questions'])
            ->where('created_by', auth()->id())
            ->findOrFail($id);
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

        $audioFilePath = $groupData['files']['audio'] ?? $groupData['audio_file'] ?? null;
        $taskImagePath = $groupData['files']['image'] ?? $groupData['task_image'] ?? null;
        $videoFilePath = $groupData['files']['video'] ?? $groupData['video_file'] ?? null;

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
            'title' => $this->sanitizeInlineText($groupData['title'] ?? '', 255) ?? '',
            'description' => $this->sanitizeInlineText($groupData['description'] ?? null, 65000),
            'instructions' => $this->sanitizeInlineText($groupData['instructions'] ?? null, 65000),
            'passage' => $this->sanitizeInlineText($groupData['passage'] ?? null, 65000),
            'transcript' => $this->sanitizeInlineText($groupData['transcript'] ?? null, 65000),
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
        $normalizedQuestionData = $questionData['question_data'] ?? null;

        if (!is_array($normalizedQuestionData)) {
            $normalizedQuestionData = [];
        }

        if (!empty($questionData['title'])) {
            $normalizedQuestionData['title'] = $questionData['title'];
        }

        IeltsTestQuestion::create([
            'section_id' => $section->id,
            'question_group_id' => $group->id,
            'question_number' => $questionNumber,
            'question_order' => $questionNumber,
            'question_type' => $questionType,
            'question_text' => $this->sanitizeInlineText($questionData['text'] ?? $questionData['question_text'] ?? '', 65000) ?? '',
            'instruction' => $this->sanitizeInlineText($questionData['instruction'] ?? null, 65000),
            'answer_options' => $answerOptions,
            'correct_answer' => is_string($correctAnswer) ? $this->sanitizeInlineText($correctAnswer, 65000) : $correctAnswer,
            'question_data' => !empty($normalizedQuestionData) ? $this->sanitizeInlineNestedPayload($normalizedQuestionData) : null,
            'table_structure' => $this->sanitizeInlineNestedPayload($questionData['table_structure'] ?? null),
            'flow_data' => $this->sanitizeInlineNestedPayload($questionData['flow_data'] ?? null),
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

        $audioFilePath = $partData['files']['audio'] ?? $partData['audio_file'] ?? null;
        $taskImagePath = $partData['files']['image'] ?? $partData['image_file'] ?? null;
        $videoFilePath = $partData['files']['video'] ?? $partData['video_file'] ?? null;

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
            'title' => $this->sanitizeInlineText($partData['title'] ?? "Part {$partOrder}", 255) ?? "Part {$partOrder}",
            'description' => $this->sanitizeInlineText($partData['description'] ?? null, 65000),
            'instructions' => $this->sanitizeInlineText($partData['instructions'] ?? null, 65000),
            'passage' => $this->sanitizeInlineText($partData['passage'] ?? null, 65000),
            'transcript' => $this->sanitizeInlineText($partData['transcript'] ?? null, 65000),
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
        $normalizedQuestionData = $questionData['question_data'] ?? null;

        if (!is_array($normalizedQuestionData)) {
            $normalizedQuestionData = [];
        }

        if (!empty($questionData['title'])) {
            $normalizedQuestionData['title'] = $questionData['title'];
        }

        IeltsTestQuestion::create([
            'section_id' => $section->id,
            'question_group_id' => $group->id,
            'part_id' => $part->id,
            'question_number' => $questionNumber,
            'question_order' => $questionNumber,
            'question_type' => $questionType,
            'question_text' => $this->sanitizeInlineText($questionData['text'] ?? $questionData['question_text'] ?? '', 65000) ?? '',
            'instruction' => $this->sanitizeInlineText($questionData['instruction'] ?? null, 65000),
            'answer_options' => $answerOptions,
            'correct_answer' => is_string($correctAnswer) ? $this->sanitizeInlineText($correctAnswer, 65000) : $correctAnswer,
            'question_data' => !empty($normalizedQuestionData) ? $this->sanitizeInlineNestedPayload($normalizedQuestionData) : null,
            'table_structure' => $this->sanitizeInlineNestedPayload($questionData['table_structure'] ?? null),
            'flow_data' => $this->sanitizeInlineNestedPayload($questionData['flow_data'] ?? null),
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

    private function sanitizeInlineGroupsPayload(array $groupsData): array
    {
        if (empty($groupsData['sections']) || !is_array($groupsData['sections'])) {
            return $groupsData;
        }

        foreach ($groupsData['sections'] as $skill => &$sectionData) {
            if (!is_array($sectionData)) {
                $sectionData = [];
            }

            $sectionData['description'] = $this->sanitizeInlineText($sectionData['description'] ?? null, 65000);

            $parts = $sectionData['parts'] ?? $sectionData['groups'] ?? [];
            if (!is_array($parts)) {
                $parts = [];
            }

            foreach ($parts as &$partData) {
                if (!is_array($partData)) {
                    $partData = [];
                }

                unset($partData['file_input_names']);

                $partData['title'] = $this->sanitizeInlineText($partData['title'] ?? null, 255);
                $partData['description'] = $this->sanitizeInlineText($partData['description'] ?? null, 65000);
                $partData['instructions'] = $this->sanitizeInlineText($partData['instructions'] ?? null, 65000);
                $partData['passage'] = $this->sanitizeInlineText($partData['passage'] ?? null, 65000);
                $partData['transcript'] = $this->sanitizeInlineText($partData['transcript'] ?? null, 65000);

                if (!empty($partData['files']) && is_array($partData['files'])) {
                    foreach (['audio', 'image', 'video'] as $fileType) {
                        $partData['files'][$fileType] = $this->sanitizeInlineFileValue($partData['files'][$fileType] ?? null);
                    }
                }

                $groups = $partData['groups'] ?? [];
                if (!is_array($groups)) {
                    $groups = [];
                }

                foreach ($groups as &$groupData) {
                    if (!is_array($groupData)) {
                        $groupData = [];
                    }

                    unset($groupData['file_input_names']);

                    $groupData['title'] = $this->sanitizeInlineText($groupData['title'] ?? null, 255);
                    $groupData['description'] = $this->sanitizeInlineText($groupData['description'] ?? null, 65000);
                    $groupData['instructions'] = $this->sanitizeInlineText($groupData['instructions'] ?? null, 65000);
                    $groupData['passage'] = $this->sanitizeInlineText($groupData['passage'] ?? null, 65000);
                    $groupData['transcript'] = $this->sanitizeInlineText($groupData['transcript'] ?? null, 65000);
                    $groupData['task_image'] = $this->sanitizeInlineFileValue($groupData['task_image'] ?? null);

                    if (!empty($groupData['files']) && is_array($groupData['files'])) {
                        foreach (['audio', 'image', 'video'] as $fileType) {
                            $groupData['files'][$fileType] = $this->sanitizeInlineFileValue($groupData['files'][$fileType] ?? null);
                        }
                    }

                    $questions = $groupData['questions'] ?? [];
                    if (!is_array($questions)) {
                        $questions = [];
                    }

                    foreach ($questions as &$questionData) {
                        if (!is_array($questionData)) {
                            $questionData = [];
                        }

                        $questionData['title'] = $this->sanitizeInlineText($questionData['title'] ?? null, 255);
                        $questionData['text'] = $this->sanitizeInlineText($questionData['text'] ?? $questionData['question_text'] ?? null, 65000);
                        $questionData['instruction'] = $this->sanitizeInlineText($questionData['instruction'] ?? null, 65000);
                        $questionData['explanation'] = $this->sanitizeInlineText($questionData['explanation'] ?? null, 65000);

                        if (isset($questionData['correctAnswer']) && is_string($questionData['correctAnswer'])) {
                            $questionData['correctAnswer'] = $this->sanitizeInlineText($questionData['correctAnswer'], 65000);
                        }
                    }
                    unset($questionData);

                    $groupData['questions'] = $questions;
                }
                unset($groupData);

                $partData['groups'] = $groups;
            }
            unset($partData);

            $sectionData['parts'] = $parts;
            if (isset($sectionData['groups'])) {
                unset($sectionData['groups']);
            }

            $groupsData['sections'][$skill] = $sectionData;
        }
        unset($sectionData);

        return $groupsData;
    }

    private function sanitizeInlineText($value, int $maxLength = 65000): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        // Prevent huge SQL packets from pasted base64 media inside rich text content.
        $value = preg_replace('/<img[^>]*src=["\']data:image\/[^"\']+["\'][^>]*>/i', '', $value);
        $value = preg_replace('/data:(image|audio|video)\/[a-z0-9.+-]+;base64,[a-z0-9+\/=\r\n]+/i', '', $value);

        // Keep author styling, but remove heavy utility classes that bloat payload size.
        $value = preg_replace('/\s(?:class|id|dir|lang|role|contenteditable|spellcheck|autocorrect|autocapitalize|translate|tabindex|draggable)=("|\').*?\1/isu', '', $value);
        $value = preg_replace('/\s(?:data|aria)-[a-z0-9_:-]+=("|\').*?\1/isu', '', $value);

        // Clean oversized inline style attributes while preserving meaningful formatting.
        $value = preg_replace_callback('/\sstyle=("|\')(.*?)\1/isu', static function ($matches) {
            $quote = $matches[1];
            $style = html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $rules = preg_split('/\s*;\s*/', trim($style), -1, PREG_SPLIT_NO_EMPTY);
            $cleanRules = [];

            foreach ($rules as $rule) {
                if (!str_contains($rule, ':')) {
                    continue;
                }

                [$property, $val] = array_map('trim', explode(':', $rule, 2));
                if ($property === '' || $val === '') {
                    continue;
                }

                $propertyLower = strtolower($property);
                $valueLower = strtolower($val);

                // Drop Tailwind runtime CSS vars and var() chains that cause huge payloads.
                if (str_starts_with($propertyLower, '--tw-') || str_contains($valueLower, 'var(--tw-')) {
                    continue;
                }

                // Ignore obviously huge style fragments copied from external pages.
                if (strlen($property) > 64 || strlen($val) > 256) {
                    continue;
                }

                $cleanRules[] = $property . ': ' . $val;
            }

            if (empty($cleanRules)) {
                return '';
            }

            return ' style=' . $quote . implode('; ', $cleanRules) . $quote;
        }, $value);

        // Remove script/style blocks if any are pasted from external sources.
        $value = preg_replace('/<\s*(script|style)\b[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $value);

        if (strlen($value) > $maxLength) {
            // Keep semantic formatting tags and compact HTML instead of flattening to plain text.
            if (str_contains($value, '<')) {
                // 1) Remove all inline styles if payload is still too large.
                $value = preg_replace('/\sstyle=("|\').*?\1/isu', '', $value);
            }

            if (strlen($value) > $maxLength && str_contains($value, '<')) {
                // 2) Drop remaining non-essential HTML attributes while preserving tags.
                $value = preg_replace_callback('/<([a-z0-9]+)([^>]*)>/i', static function ($matches) {
                    $tag = strtolower($matches[1]);

                    // Keep link targets for anchors to avoid breaking URLs.
                    if ($tag === 'a') {
                        if (preg_match('/\shref=("|\').*?\1/isu', $matches[2], $href)) {
                            return '<a' . $href[0] . '>';
                        }
                        return '<a>';
                    }

                    return '<' . $tag . '>';
                }, $value);
            }

            if (strlen($value) > $maxLength) {
                // 3) Final hard cap as absolute safeguard.
                $value = substr($value, 0, $maxLength);
            }
        }

        return trim($value) === '' ? null : trim($value);
    }

    private function sanitizeInlineFileValue($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);
        if ($value === '' || str_starts_with(strtolower($value), 'data:')) {
            return null;
        }

        if (strlen($value) > 2048) {
            $value = substr($value, 0, 2048);
        }

        return $value;
    }

    private function sanitizeInlineNestedPayload($value, int $maxStringLength = 65000)
    {
        if (is_string($value)) {
            return $this->sanitizeInlineText($value, $maxStringLength);
        }

        if (!is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $value[$key] = $this->sanitizeInlineNestedPayload($item, $maxStringLength);
                continue;
            }

            if (is_string($item)) {
                $value[$key] = $this->sanitizeInlineText($item, $maxStringLength);
            }
        }

        return $value;
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
