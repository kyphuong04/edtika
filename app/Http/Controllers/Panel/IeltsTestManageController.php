<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsTest;
use App\Models\IeltsTestSection;
use App\Models\IeltsTestQuestion;
use App\Models\IeltsPracticeCategory;
use App\Models\Notification;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;

class IeltsTestManageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Teachers, Admins, Managers, CEOs can access (role hierarchy)
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization() && !$user->isManager() && !$user->isCeo()) {
            abort(403, 'Unauthorized');
        }
        
        $tests = IeltsTest::where('created_by', $user->id)
            ->with('sections')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $stats = [
            'total' => $tests->count(),
            'published' => $tests->where('status', 'published')->count(),
            'drafts' => $tests->where('status', 'draft')->count(),
            'pending' => $tests->where('status', 'pending_approval')->count(),
        ];
        
        $data = [
            'pageTitle' => 'My IELTS Tests',
            'tests' => $tests,
            'stats' => $stats,
        ];
        
        return view('design_1.panel.ielts_tests_manage.index', $data);
    }
    
    public function create()
    {
        $user = auth()->user();
        
        // Teachers, Admins, Managers, CEOs can create tests
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization() && !$user->isManager() && !$user->isCeo()) {
            abort(403);
        }
        
        // Get Question Bank groups for selection
        try {
            $mockGroups = \App\Models\IeltsQuestionGroup::where('bank_type', 'mock')
                ->where('status', 'approved')
                ->get()
                ->groupBy('skill');
                
            $practiceGroups = \App\Models\IeltsQuestionGroup::where('bank_type', 'practice')
                ->where('status', 'approved')
                ->get()
                ->groupBy('skill');
        } catch (\Exception $e) {
            // If no groups exist, initialize empty collections
            $mockGroups = collect();
            $practiceGroups = collect();
        }
        
        $data = [
            'pageTitle' => 'Create IELTS Test from Question Bank',
            'mockGroups' => $mockGroups,
            'practiceGroups' => $practiceGroups,
        ];
        
        return view('design_1.panel.ielts_tests_manage.create_from_bank', $data);
    }

    public function chooseType()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization() && !$user->isManager() && !$user->isCeo()) {
            abort(403);
        }
        
        $data = [
            'pageTitle' => 'Create IELTS Test - Choose Type',
        ];
        
        return view('design_1.panel.ielts_tests_manage.choose_type', $data);
    }

  // form create mock test
    public function createMock()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization() && !$user->isManager() && !$user->isCeo()) {
            abort(403);
        }
        
        $data = [
            'pageTitle' => 'Create Mock Test',
        ];
        
        return view('design_1.panel.ielts_tests_manage.create_mock', $data);
    }
    // form create practice test
    public function createPractice()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization() && !$user->isManager() && !$user->isCeo()) {
            abort(403);
        }
        
        $categories = IeltsPracticeCategory::all()->groupBy('skill');
        
        $data = [
            'pageTitle' => 'Create Practice Test',
            'categories' => $categories,
        ];
        
        return view('design_1.panel.ielts_tests_manage.create_practice', $data);
    }
    // Store Mock Test
    public function storeMock(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'format' => 'required|in:academic,general,both',
            'delivery_method' => 'required|in:computer,paper,both',
            'difficulty_level' => 'nullable|in:beginner,intermediate,advanced,mixed',
            'target_band_min' => 'nullable|numeric|min:0|max:9',
            'target_band_max' => 'nullable|numeric|min:0|max:9',
            // Linking
            'access_type' => 'nullable|in:standalone,bundle',
            'bundle_id' => 'nullable|exists:bundles,id',
            'webinar_id' => 'nullable|exists:webinars,id',
            'lesson_id' => 'nullable|integer',
        ]);
        
        // Generate unique slug
        $slug = \Str::slug($validated['title']);
        $count = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = \Str::slug($validated['title']) . '-' . $count;
            $count++;
        }
        
        $test = IeltsTest::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'type' => 'mock',
            'format' => $validated['format'],
            'delivery_method' => $validated['delivery_method'],
            'difficulty_level' => $validated['difficulty_level'] ?? 'mixed',
            'target_band_min' => $validated['target_band_min'] ?? null,
            'target_band_max' => $validated['target_band_max'] ?? null,
            // Mock test has all 4 skills
            'has_listening' => true,
            'has_reading' => true,
            'has_writing' => true,
            'has_speaking' => true,
            // Linking
            'bundle_id' => $validated['bundle_id'] ?? null,
            'webinar_id' => $validated['webinar_id'] ?? null,
            'lesson_id' => $validated['lesson_id'] ?? null,
            'is_free' => ($validated['access_type'] ?? 'standalone') === 'standalone',
            // Metadata
            'created_by' => $user->id,
            'status' => 'draft',
            'is_active' => true,
            'created_at' => time(),
        ]);
        
        return redirect()
            ->route('panel.my_ielts_tests.sections', $test->id)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Mock Test created! Now add sections (Listening, Reading, Writing, Speaking).',
                'status' => 'success'
            ]]);
    }

    // Store Practice Test
    public function storePractice(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'format' => 'required|in:academic,general,both',
            'delivery_method' => 'required|in:computer,paper,both',
            'difficulty_level' => 'nullable|in:beginner,intermediate,advanced,mixed',
            'practice_mode' => 'required|in:timed,untimed,adaptive',
            'target_band_min' => 'nullable|numeric|min:0|max:9',
            'target_band_max' => 'nullable|numeric|min:0|max:9',
            'show_answers_immediately' => 'nullable|boolean',
            'allow_retake' => 'nullable|boolean',
            // Linking
            'access_type' => 'nullable|in:standalone,bundle',
            'bundle_id' => 'nullable|exists:bundles,id',
            'webinar_id' => 'nullable|exists:webinars,id',
            'lesson_id' => 'nullable|integer',
        ]);
        
        // Generate unique slug
        $slug = \Str::slug($validated['title']);
        $count = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = \Str::slug($validated['title']) . '-' . $count;
            $count++;
        }
        
        $test = IeltsTest::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'type' => 'practice',
            'format' => $validated['format'],
            'delivery_method' => $validated['delivery_method'],
            'difficulty_level' => $validated['difficulty_level'] ?? 'mixed',
            'practice_mode' => $validated['practice_mode'],
            'target_band_min' => $validated['target_band_min'] ?? null,
            'target_band_max' => $validated['target_band_max'] ?? null,
            'show_answers_immediately' => $validated['show_answers_immediately'] ?? false,
            'allow_retake' => $validated['allow_retake'] ?? true,
            // Linking
            'bundle_id' => $validated['bundle_id'] ?? null,
            'webinar_id' => $validated['webinar_id'] ?? null,
            'lesson_id' => $validated['lesson_id'] ?? null,
            'is_free' => ($validated['access_type'] ?? 'standalone') === 'standalone',
            // Metadata
            'created_by' => $user->id,
            'status' => 'draft',
            'is_active' => true,
            'created_at' => time(),
        ]);
        
        return redirect()
            ->route('panel.my_ielts_tests.sections', $test->id)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Practice Test created! Now add sections.',
                'status' => 'success'
            ]]);
    }

    
    // Store new test
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:mock,practice,diagnostic',
            'target_band_min' => 'nullable|numeric|min:1|max:9',
            'target_band_max' => 'nullable|numeric|min:1|max:9',
            'has_listening' => 'nullable|boolean',
            'has_reading' => 'nullable|boolean',
            'has_writing' => 'nullable|boolean',
            'has_speaking' => 'nullable|boolean',
            'practice_category_id' => 'nullable|exists:ielts_practice_categories,id',
            'practice_mode' => 'nullable|in:untimed,timed,exam_mode',
            // Access settings
            'is_free' => 'nullable|boolean',
            'is_lead_test' => 'nullable|boolean',
            'require_enrollment' => 'nullable|boolean',
            'webinar_id' => 'nullable|exists:webinars,id',
        ]);
        
        // Generate unique slug
        $slug = \Str::slug($validated['title']);
        $count = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = \Str::slug($validated['title']) . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;
        
        // For mock tests, set all skills to true
        if ($validated['type'] === 'mock') {
            $validated['has_listening'] = true;
            $validated['has_reading'] = true;
            $validated['has_writing'] = true;
            $validated['has_speaking'] = true;
        }
        
        $validated['created_by'] = $user->id;
        $validated['status'] = 'draft';
        $validated['is_active'] = true;
        $validated['created_at'] = time();
        
        // NOTE: total_duration is a GENERATED column - computed from section durations
        // Do not include it in the insert
        
        $test = IeltsTest::create($validated);
        
        return redirect()
            ->route('panel.my_ielts_tests.sections', $test->id)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Test created successfully! Now add sections.',
                'status' => 'success'
            ]]);
    }
    
    // Store new test from Question Bank groups
    public function storeFromBank(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:mock,practice,diagnostic',
            'format' => 'nullable|in:academic,general,full',
            'group_ids' => 'required|array|min:1',
            'group_ids.*' => 'exists:ielts_question_groups,id',
            // Practice test time settings
            'practice_mode' => 'nullable|in:untimed,timed,exam_mode',
            'total_duration' => 'nullable|integer|min:5|max:180',
            'show_answers_immediately' => 'nullable|boolean',
        ]);
        
        // Validate bank type matches test type
        $groups = \App\Models\IeltsQuestionGroup::whereIn('id', $validated['group_ids'])
            ->where('status', 'approved')
            ->get();
        
        if ($groups->count() !== count($validated['group_ids'])) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Some selected groups are not approved or do not exist.',
                'status' => 'error'
            ]])->withInput();
        }
        
        // Validate bank type for mock/practice tests
        if ($validated['type'] === 'mock') {
            $invalidGroups = $groups->where('bank_type', '!=', 'mock');
            if ($invalidGroups->isNotEmpty()) {
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'Mock tests can only use questions from the Mock Bank. Please remove Practice Bank groups.',
                    'status' => 'error'
                ]])->withInput();
            }
            
            // Validate that all 4 skills are present for Mock Test
            $skillsPresent = $groups->pluck('skill')->unique()->toArray();
            $requiredSkills = ['listening', 'reading', 'writing', 'speaking'];
            $missingSkills = array_diff($requiredSkills, $skillsPresent);
            
            if (!empty($missingSkills)) {
                return back()->with(['toast' => [
                    'title' => 'Mock Test Requires All 4 Skills',
                    'msg' => 'Missing: ' . implode(', ', array_map('ucfirst', $missingSkills)) . '. A complete IELTS Mock Test must include Listening, Reading, Writing, and Speaking.',
                    'status' => 'error'
                ]])->withInput();
            }
            
            // Validate standard IELTS parts count (HARD VALIDATION - must have exact parts)
            $partsCount = $groups->groupBy('skill')->map->count();
            $requiredParts = [
                'listening' => 4,
                'reading' => 3,
                'writing' => 2,
                'speaking' => 3,
            ];
            
            $errors = [];
            foreach ($requiredParts as $skill => $required) {
                $actual = $partsCount->get($skill, 0);
                if ($actual !== $required) {
                    $errors[] = ucfirst($skill) . ": $actual/$required parts";
                }
            }
            
            if (!empty($errors)) {
                return back()->with(['toast' => [
                    'title' => 'Invalid IELTS Mock Test Structure',
                    'msg' => implode(', ', $errors) . '. A standard IELTS Mock Test must have exactly: Listening (4 parts), Reading (3 passages), Writing (2 tasks), Speaking (3 parts).',
                    'status' => 'error'
                ]])->withInput();
            }
        } elseif ($validated['type'] === 'practice') {
            $invalidGroups = $groups->where('bank_type', '!=', 'practice');
            if ($invalidGroups->isNotEmpty()) {
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'Practice tests can only use questions from the Practice Bank. Please remove Mock Bank groups.',
                    'status' => 'error'
                ]])->withInput();
            }
        }
        // Diagnostic tests can use both bank types
        
        // Create test shell
        $slug = \Str::slug($validated['title']);
        $count = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = \Str::slug($validated['title']) . '-' . $count;
            $count++;
        }
        
        // Calculate total duration based on practice mode
        $totalDuration = null; // Let it be calculated from sections
        if ($validated['type'] === 'mock') {
            // Mock test uses standard IELTS timing - will be set in sections
        } elseif (isset($validated['practice_mode'])) {
            if ($validated['practice_mode'] === 'timed' && isset($validated['total_duration'])) {
                $totalDuration = $validated['total_duration'];
            } elseif ($validated['practice_mode'] === 'exam_mode') {
                // Will be calculated from sections
            }
            // For 'untimed', totalDuration stays null
        }
        
        $test = IeltsTest::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'type' => $validated['type'],
            'format' => $validated['format'] ?? 'academic',
            'created_by' => $user->id,
            // Tạo từ Question Bank (đã approved) → Published ngay, student làm được luôn
            'status' => 'published',
            'is_active' => true,
            'approved_at' => time(),
            'has_listening' => false,
            'has_reading' => false,
            'has_writing' => false,
            'has_speaking' => false,
            'practice_mode' => $validated['practice_mode'] ?? null,
            'show_answers_immediately' => $validated['show_answers_immediately'] ?? false,
            // Calculate target band from selected groups
            'target_band_min' => $groups->whereNotNull('target_band')->min('target_band'),
            'target_band_max' => $groups->whereNotNull('target_band')->max('target_band'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        
        // Copy questions from selected groups
        $totalQuestions = 0;
        
        // Standard IELTS section durations (in minutes)
        $standardDurations = [
            'listening' => 30,
            'reading' => 60,
            'writing' => 60,
            'speaking' => 15,
        ];
        
        // Standard IELTS skill order
        $skillOrder = ['listening', 'reading', 'writing', 'speaking'];
        
        // Group the selected groups by skill
        $groupsBySkill = [];
        foreach ($validated['group_ids'] as $groupId) {
            $group = \App\Models\IeltsQuestionGroup::with(['questions'])->find($groupId);
            if ($group) {
                $groupsBySkill[$group->skill][] = $group;
            }
        }
        
        // Process groups in standard IELTS order
        $globalSortOrder = 0;
        
        foreach ($skillOrder as $skill) {
            if (!isset($groupsBySkill[$skill])) continue;
            
            $skillGroups = $groupsBySkill[$skill];
            $partNumber = 0; // Part number within this skill
            
            // Enable skill for this test
            $skillField = 'has_' . $skill;
            $test->$skillField = true;
            
            foreach ($skillGroups as $group) {
                $partNumber++;
                $globalSortOrder++;
            
                // Set duration based on test type
                $sectionDuration = null;
                if ($validated['type'] === 'mock') {
                    $sectionDuration = $standardDurations[$skill] ?? 30;
                } elseif (isset($validated['practice_mode']) && $validated['practice_mode'] !== 'untimed') {
                    $sectionDuration = $group->duration ?? $standardDurations[$skill] ?? 30;
                }
                
                $section = IeltsTestSection::create([
                    'test_id' => $test->id,
                    'skill' => $skill,
                    'title' => $group->title,
                    'section_number' => $partNumber, // Part number within this skill (1, 2, 3...)
                    'instructions' => $group->instructions ?? null,
                    'passage_text' => $group->passage ?? $group->passage_text ?? null,
                    'question_start' => 1,
                    'question_end' => $group->questions->count() ?: 1,
                    'duration_minutes' => $sectionDuration,
                    'sort_order' => $globalSortOrder, // Global order across all sections
                    'question_group_id' => $group->id, // Link to original group for audio/content
                    'audio_file' => $group->audio_path ?? $group->audio_file ?? null, // Copy audio path
                    'created_at' => time(),
                ]);
            
                // Get questions from group
                $questions = $group->questions;
                $questionNumber = 0;
                
                foreach ($questions as $question) {
                    $questionNumber++;
                    
                    // Map question type to valid ENUM values
                    $questionType = $this->mapQuestionType($question->question_type ?? $group->question_type ?? 'multiple_choice');
                    
                    // Get answer options - field is 'answer_options' not 'options'
                    // Also check question_data for legacy questions that stored options there
                    $answerOptions = $question->answer_options ?? null;
                    if (empty($answerOptions) && !empty($question->question_data)) {
                        // Parse question_data to extract options
                        $questionData = is_string($question->question_data) 
                            ? json_decode($question->question_data, true) 
                            : $question->question_data;
                        if (is_array($questionData)) {
                            $extractedOptions = [];
                            foreach ($questionData as $key => $value) {
                                // Match both 'options[A]' and 'options[A' (some legacy data lost the closing bracket)
                                if (preg_match('/^options\[([A-Z]+)\]?$/', $key, $matches)) {
                                    $extractedOptions[$matches[1]] = $value;
                                }
                            }
                            if (!empty($extractedOptions)) {
                                $answerOptions = $extractedOptions;
                            }
                        }
                    }
                    if (is_array($answerOptions)) {
                        $answerOptions = json_encode($answerOptions);
                    }
                    
                    // Copy question to test section
                    IeltsTestQuestion::create([
                        'section_id' => $section->id,
                        'question_number' => $questionNumber,
                        'question_type' => $questionType,
                        'question_text' => $question->question_text ?? $question->content ?? '',
                        'question_image' => $question->image_file ?? null,
                        'question_audio' => $question->audio_file ?? null,
                        'instruction' => $question->instruction ?? null,
                        'correct_answer' => is_array($question->correct_answer) ? json_encode($question->correct_answer) : $question->correct_answer,
                        'answer_options' => $answerOptions,
                        'accept_synonyms' => $question->accept_synonyms ?? 0,
                        'case_sensitive' => $question->case_sensitive ?? 0,
                        'max_words' => $question->max_words ?? $group->max_words ?? null,
                        'points' => $question->points ?? 1.0,
                        'auto_gradable' => !in_array($skill, ['writing', 'speaking']),
                        'explanation' => $question->explanation ?? null,
                        'sort_order' => $questionNumber,
                        'created_at' => time(),
                    ]);
                    
                    $totalQuestions++;
                }
                
                // Update section question range
                $section->update([
                    'question_end' => $questionNumber ?: 1,
                ]);
                
                // Update group usage count if method exists
                if (method_exists($group, 'incrementUsage')) {
                    $group->incrementUsage();
                }
            } // End foreach $skillGroups
        } // End foreach $skillOrder
        
        $test->save();
        
        // Redirect to My Tests page
        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => "Test '{$test->title}' created with {$totalQuestions} questions!",
                'status' => 'success'
            ]]);
    }
    
    // form edit
    public function edit($id)
    {
        $test = IeltsTest::where('id', $id)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $categories = IeltsPracticeCategory::all()->groupBy('skill');
        
        $data = [
            'pageTitle' => 'Edit: ' . $test->title,
            'test' => $test,
            'categories' => $categories,
        ];
        
        return view('design_1.panel.ielts_tests_manage.edit', $data);
    }
    
    // update test
    public function update(Request $request, $id)
    {
        $test = IeltsTest::where('id', $id)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_duration' => 'required|integer|min:1',
            'target_band_min' => 'nullable|numeric|min:1|max:9',
            'target_band_max' => 'nullable|numeric|min:1|max:9',
            'practice_category_id' => 'nullable|exists:ielts_practice_categories,id',
            'practice_mode' => 'nullable|in:untimed,timed,exam_mode',
        ]);
        
        $validated['updated_at'] = time();
        
        $test->update($validated);
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Test updated successfully!',
            'status' => 'success'
        ]]);
    }
    
    // delete test
    public function destroy($id)
    {
        $test = IeltsTest::where('id', $id)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        // Check if has attempts
        if ($test->attempts()->count() > 0) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Cannot delete test with student attempts',
                'status' => 'error'
            ]]);
        }
        
        $test->delete();
        
        return redirect()
            ->route('panel.my_ielts_tests')
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Test deleted successfully!',
                'status' => 'success'
            ]]);
    }
    
    // duplicate test
    public function duplicate($id)
    {
        $original = IeltsTest::where('id', $id)
            ->where('created_by', auth()->id())
            ->with('sections.questions')
            ->firstOrFail();
        
        $newTest = $original->replicate();
        $newTest->title = $original->title . ' (Copy)';
        $newTest->status = 'draft';
        $newTest->created_at = time();
        $newTest->save();
        
        // Copy sections and questions
        foreach ($original->sections as $section) {
            $newSection = $section->replicate();
            $newSection->test_id = $newTest->id;
            $newSection->save();
            
            foreach ($section->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->section_id = $newSection->id;
                $newQuestion->save();
            }
        }
        
        return redirect()
            ->route('panel.my_ielts_tests.edit', $newTest->id)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Test duplicated successfully!',
                'status' => 'success'
            ]]);
    }
    
    // manage sections
    public function sections($id)
    {
        $test = IeltsTest::where('id', $id)
            ->where('created_by', auth()->id())
            ->with('sections.questions')
            ->firstOrFail();
        
        $data = [
            'pageTitle' => 'Manage Sections: ' . $test->title,
            'test' => $test,
        ];
        
        return view('design_1.panel.ielts_tests_manage.sections', $data);
    }
    
    /// submit for approval
    public function submitForApproval($id)
    {
        $test = IeltsTest::where('id', $id)
            ->where('created_by', auth()->id())
            ->with('sections')
            ->firstOrFail();
        
        // Check if test can be submitted
        if (!in_array($test->status, ['draft', 'rejected'])) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Only draft or rejected tests can be submitted for approval',
                'status' => 'error'
            ]]);
        }
        
        // Check if test has sections
        if ($test->sections->count() === 0) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Cannot submit test without sections. Please add content first.',
                'status' => 'error'
            ]]);
        }
        
        // Check if sections have questions
        $totalQuestions = 0;
        foreach ($test->sections as $section) {
            $totalQuestions += $section->questions()->count();
        }
        
        if ($totalQuestions === 0) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Cannot submit test without questions. Please add questions first.',
                'status' => 'error'
            ]]);
        }
        
        $test->update([
            'status' => 'pending_approval',
            'submitted_for_approval_at' => time(),
        ]);
        
        // Notify managers/CEOs about new pending test
        $managers = User::whereIn('role_name', [Role::$manager, Role::$ceo])
            ->where('status', 'active')
            ->get();
        
        $creator = auth()->user();
        $creatorName = $creator->full_name ?? $creator->email;
        
        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'sender' => Notification::$SystemSender,
                'title' => 'New IELTS Test Needs Approval',
                'message' => $creatorName . ' submitted test "' . $test->title . '" for approval.',
                'type' => 'single',
                'created_at' => time(),
            ]);
        }
        
        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Test submitted for approval! Manager/CEO will review it.',
                'status' => 'success'
            ]]);
    }
    
    // Show create section form
    public function createSection($testId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        return view('design_1.panel.ielts_tests_manage.section_create', [
            'test' => $test,
            'pageTitle' => 'Add Section - ' . $test->title,
        ]);
    }
    
    // Store new section
    public function storeSection(Request $request, $testId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $validated = $request->validate([
            'skill' => 'required|in:listening,reading,writing,speaking',
            'title' => 'required|string|max:255',
            'section_number' => 'required|integer|min:1',
            'instructions' => 'nullable|string',
            'question_start' => 'required|integer|min:1',
            'question_end' => 'required|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:1',
            'passage_text' => 'nullable|string',
            'listening_transcript' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:1',
        ]);
        
        // Check for duplicate skill in same test
        $existingSection = IeltsTestSection::where('test_id', $test->id)
            ->where('skill', $validated['skill'])
            ->where('title', $validated['title'])
            ->first();
            
        if ($existingSection) {
            return back()
                ->withErrors(['skill' => 'A section with this skill and title already exists. Please use a different title or edit the existing section.'])
                ->withInput();
        }
        
        // Validate question range
        if ($validated['question_end'] < $validated['question_start']) {
            return back()->withErrors(['question_end' => 'Question end must be greater than or equal to question start'])
                        ->withInput();
        }
        
        $validated['test_id'] = $test->id;
        $validated['sort_order'] = $validated['sort_order'] ?? ($test->sections()->max('sort_order') ?? 0) + 1;
        $validated['created_at'] = time();
        
        // Store listening transcript in passage_text if it's a listening section
        if ($validated['skill'] === 'listening' && !empty($validated['listening_transcript'])) {
            $validated['passage_text'] = $validated['listening_transcript'];
        }
        unset($validated['listening_transcript']);
        
        IeltsTestSection::create($validated);
        
        return redirect()
            ->route('panel.my_ielts_tests.sections', $test->id)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Section created successfully!',
                'status' => 'success'
            ]]);
    }
    
    // edit section form
    public function editSection($testId, $sectionId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $section = IeltsTestSection::where('test_id', $test->id)
            ->where('id', $sectionId)
            ->with('questions')
            ->firstOrFail();
        
        return view('design_1.panel.ielts_tests_manage.section_edit', [
            'test' => $test,
            'section' => $section,
            'pageTitle' => 'Edit Section - ' . $section->title,
        ]);
    }
    
    // update section
    public function updateSection(Request $request, $testId, $sectionId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $section = IeltsTestSection::where('test_id', $test->id)
            ->where('id', $sectionId)
            ->firstOrFail();
        
        $validated = $request->validate([
            'skill' => 'required|in:listening,reading,writing,speaking',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passage_text' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:1',
        ]);
        
        $section->update($validated);
        
        return redirect()
            ->route('panel.my_ielts_tests.sections', $test->id)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Section updated successfully!',
                'status' => 'success'
            ]]);
    }
    
    // delete section
    public function deleteSection($testId, $sectionId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $section = IeltsTestSection::where('test_id', $test->id)
            ->where('id', $sectionId)
            ->firstOrFail();
        
        // Delete section (cascade will delete questions)
        $section->delete();
        
        return redirect()
            ->route('panel.my_ielts_tests.sections', $test->id)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Section deleted successfully!',
                'status' => 'success'
            ]]);
    }
    
    // question for section
    public function questions($testId, $sectionId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $section = IeltsTestSection::where('test_id', $test->id)
            ->where('id', $sectionId)
            ->with('questions')
            ->firstOrFail();
        
        return view('design_1.panel.ielts_tests_manage.questions', [
            'test' => $test,
            'section' => $section,
            'pageTitle' => 'Manage Questions - ' . $section->title,
        ]);
    }
    
    //create question form
    public function createQuestion($testId, $sectionId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $section = IeltsTestSection::where('test_id', $test->id)
            ->where('id', $sectionId)
            ->firstOrFail();
        
        return view('design_1.panel.ielts_tests_manage.question_create', [
            'test' => $test,
            'section' => $section,
            'pageTitle' => 'Add Question',
        ]);
    }
    
    // Store new question
    public function storeQuestion(Request $request, $testId, $sectionId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $section = IeltsTestSection::where('test_id', $test->id)
            ->where('id', $sectionId)
            ->firstOrFail();
        
        
        $validated = $request->validate([
            'question_number' => [
                'required',
                'integer',
                'min:' . $section->question_start,
                'max:' . $section->question_end,
                \Illuminate\Validation\Rule::unique('ielts_test_questions', 'question_number')
                    ->where('section_id', $section->id)
            ],
            'question_type' => 'required|in:multiple_choice,fill_blank,true_false,matching,short_answer,essay',
            'question_text' => 'required|string',
            'instruction' => 'nullable|string',
            'points' => 'required|numeric|min:0',
            'correct_answer' => 'nullable|string',
            'accept_synonyms' => 'nullable|string',
            'explanation' => 'nullable|string',
            'auto_gradable' => 'nullable|boolean',
            'case_sensitive' => 'nullable|boolean',
            'max_words' => 'nullable|integer|min:1',
            'option_a' => 'nullable|string',
            'option_b' => 'nullable|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
        ]);
        
        // Build answer options for multiple choice
        if ($validated['question_type'] === 'multiple_choice') {
            $options = array_filter([
                $request->option_a,
                $request->option_b,
                $request->option_c,
                $request->option_d,
            ]);
            $validated['answer_options'] = json_encode($options);
        }
        
        $validated['section_id'] = $section->id;
        $validated['sort_order'] = ($section->questions()->max('sort_order') ?? 0) + 1;
        $validated['created_at'] = time();
        
        IeltsTestQuestion::create($validated);
        
        return redirect()
            ->route('panel.my_ielts_tests.questions', [$test->id, $section->id])
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Question created successfully!',
                'status' => 'success'
            ]]);
    }
    
    // edit question form
    public function editQuestion($testId, $sectionId, $questionId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $section = IeltsTestSection::where('test_id', $test->id)
            ->where('id', $sectionId)
            ->firstOrFail();
        
        $question = IeltsTestQuestion::where('section_id', $section->id)
            ->where('id', $questionId)
            ->firstOrFail();
        
        return view('design_1.panel.ielts_tests_manage.question_edit', [
            'test' => $test,
            'section' => $section,
            'question' => $question,
            'pageTitle' => 'Edit Question',
        ]);
    }

    // update question
    public function updateQuestion(Request $request, $testId, $sectionId, $questionId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $section = IeltsTestSection::where('test_id', $test->id)
            ->where('id', $sectionId)
            ->firstOrFail();
        
        $question = IeltsTestQuestion::where('section_id', $section->id)
            ->where('id', $questionId)
            ->firstOrFail();
        
        
        $validated = $request->validate([
            'question_number' => [
                'required',
                'integer',
                'min:' . $section->question_start,
                'max:' . $section->question_end,
                \Illuminate\Validation\Rule::unique('ielts_test_questions', 'question_number')
                    ->where('section_id', $section->id)
                    ->ignore($question->id)
            ],
            'question_type' => 'required|in:multiple_choice,fill_blank,true_false,matching,short_answer,essay',
            'question_text' => 'required|string',
            'instruction' => 'nullable|string',
            'points' => 'required|numeric|min:0',
            'correct_answer' => 'nullable|string',
            'accept_synonyms' => 'nullable|string',
            'explanation' => 'nullable|string',
            'auto_gradable' => 'nullable|boolean',
            'case_sensitive' => 'nullable|boolean',
            'max_words' => 'nullable|integer|min:1',
            'option_a' => 'nullable|string',
            'option_b' => 'nullable|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
        ]);
        
        // Build answer options for multiple choice
        if ($validated['question_type'] === 'multiple_choice') {
            $options = array_filter([
                $request->option_a,
                $request->option_b,
                $request->option_c,
                $request->option_d,
            ]);
            $validated['answer_options'] = json_encode($options);
        }
        
        $question->update($validated);
        
        return redirect()
            ->route('panel.my_ielts_tests.questions', [$test->id, $section->id])
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Question updated successfully!',
                'status' => 'success'
            ]]);
    }
    
    // delete question
    public function deleteQuestion($testId, $sectionId, $questionId)
    {
        $test = IeltsTest::where('id', $testId)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        $section = IeltsTestSection::where('test_id', $test->id)
            ->where('id', $sectionId)
            ->firstOrFail();
        
        $question = IeltsTestQuestion::where('section_id', $section->id)
            ->where('id', $questionId)
            ->firstOrFail();
        
        $question->delete();
        
        return redirect()
            ->route('panel.my_ielts_tests.questions', [$test->id, $section->id])
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Question deleted successfully!',
                'status' => 'success'
            ]]);
    }
    
    // Map Question Bank question types to valid ENUM values
    private function mapQuestionType($type)
    {
        // Valid ENUM values in ielts_test_questions table
        $validTypes = [
            'fill_blank',
            'multiple_choice',
            'multiple_select',
            'matching',
            'true_false_ng',
            'yes_no_ng',
            'short_answer',
            'essay',
            'diagram_label',
            'sentence_completion',
            'note_completion',
            'table_completion',
            'flow_chart',
            'summary_completion',
        ];
        
        // If already valid, return as-is
        if (in_array($type, $validTypes)) {
            return $type;
        }
        
        // Mapping from Question Bank types to valid ENUM values
        $mapping = [
            // Multiple choice variations
            'multiple_choice_single' => 'multiple_choice',
            'multiple_choice_multiple' => 'multiple_select', // Map to multiple_select for proper grading
            'mcq' => 'multiple_choice',
            'mcq_single' => 'multiple_choice',
            'mcq_multiple' => 'multiple_select',
            'choose_two' => 'multiple_select',
            'choose_three' => 'multiple_select',
            
            // True/False variations
            'true_false_not_given' => 'true_false_ng',
            'tfng' => 'true_false_ng',
            
            // Yes/No variations
            'yes_no_not_given' => 'yes_no_ng',
            'ynng' => 'yes_no_ng',
            
            // Matching variations
            'matching_headings' => 'matching',
            'matching_information' => 'matching',
            'matching_features' => 'matching',
            'matching_sentence_endings' => 'sentence_completion',
            
            // Completion variations
            'form_completion' => 'fill_blank',
            'flow_chart_completion' => 'flow_chart',
            'diagram_labeling' => 'diagram_label',
            'map_labeling' => 'diagram_label',
            
            // Writing tasks
            'task1_graph' => 'essay',
            'task1_map' => 'essay',
            'task1_process' => 'essay',
            'task1_letter' => 'essay',
            'task2_essay' => 'essay',
            'writing' => 'essay',
            
            // Speaking parts
            'part1_questions' => 'short_answer',
            'part2_cue_card' => 'essay',
            'part3_discussion' => 'short_answer',
            'speaking' => 'essay',
        ];
        
        return $mapping[$type] ?? 'multiple_choice';
    }
}
