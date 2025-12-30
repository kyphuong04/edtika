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
    /**
     * Display user's tests
     */
    public function index()
    {
        $user = auth()->user();
        
        // Only teachers and admins can access
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
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
    
    /**
     * Show create form
     */
    public function create()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }
        
        // Get Question Bank groups for selection
        try {
            $mockGroups = \App\Models\IeltsQuestionGroup::where('bank_type', 'mock')
                ->get()
                ->groupBy('skill');
                
            $practiceGroups = \App\Models\IeltsQuestionGroup::where('bank_type', 'practice')
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

    /**
     * Show choose type page (Mock vs Practice)
     */
    public function chooseType()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }
        
        $data = [
            'pageTitle' => 'Create IELTS Test - Choose Type',
        ];
        
        return view('design_1.panel.ielts_tests_manage.choose_type', $data);
    }

    /**
     * Show Mock Test creation form
     */
    public function createMock()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }
        
        $data = [
            'pageTitle' => 'Create Mock Test',
        ];
        
        return view('design_1.panel.ielts_tests_manage.create_mock', $data);
    }

    /**
     * Show Practice Test creation form
     */
    public function createPractice()
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }
        
        $categories = IeltsPracticeCategory::all()->groupBy('skill');
        
        $data = [
            'pageTitle' => 'Create Practice Test',
            'categories' => $categories,
        ];
        
        return view('design_1.panel.ielts_tests_manage.create_practice', $data);
    }

    /**
     * Store Mock Test
     */
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

    /**
     * Store Practice Test
     */
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

    
    /**
     * Store new test
     */
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
    
    /**
     * Store new test from Question Bank groups
     */
    public function storeFromBank(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:mock,practice,diagnostic',
            'format' => 'nullable|in:academic,general',
            'group_ids' => 'required|array|min:1',
            'group_ids.*' => 'exists:ielts_question_groups,id',
        ]);
        
        // Create test shell
        $slug = \Str::slug($validated['title']);
        $count = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = \Str::slug($validated['title']) . '-' . $count;
            $count++;
        }
        
        $test = IeltsTest::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'type' => $validated['type'],
            'format' => $validated['format'] ?? 'academic',
            'created_by' => $user->id,
            'status' => 'draft',
            'is_active' => true,
            'has_listening' => false,
            'has_reading' => false,
            'has_writing' => false,
            'has_speaking' => false,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        
        // Copy questions from selected groups
        $totalQuestions = 0;
        
        foreach ($validated['group_ids'] as $groupId) {
            $group = \App\Models\IeltsQuestionGroup::with(['mockQuestions', 'practiceQuestions'])->find($groupId);
            
            if (!$group) continue;
            
            // Enable skill for this test
            $skillField = 'has_' . $group->skill;
            $test->$skillField = true;
            
            // Get questions from group
            $questions = $group->bank_type === 'mock' ? $group->mockQuestions : $group->practiceQuestions;
            
            foreach ($questions as $question) {
                // Copy question to test
                IeltsTestQuestion::create([
                    'test_id' => $test->id,
                    'skill' => $question->skill,
                    'question_type' => $question->question_type,
                    'question_text' => $question->question_text,
                    'passage_text' => $question->passage_text ?? null,
                    'audio_file' => $question->audio_file ?? null,
                    'image_file' => $question->image_file ?? null,
                    'options' => $question->options,
                    'correct_answer' => $question->correct_answer,
                    'difficulty_level' => $question->difficulty_level,
                    'points' => $question->points ?? 1.0,
                    'instruction' => $question->instruction,
                    'tags' => $question->tags,
                    // Track source
                    'source_question_id' => $question->id,
                    'source_group_id' => $groupId,
                ]);
                
                $totalQuestions++;
            }
            
            // Update group usage count
            $group->incrementUsage();
        }
        
        $test->save();
        
        return redirect()
            ->route('panel.my_ielts_tests.index')
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => "Test created with {$totalQuestions} questions from " . count($validated['group_ids']) . " groups!",
                'status' => 'success'
            ]]);
    }
    
    /**
     * Show edit form
     */
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
    
    /**
     * Update test
     */
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
    
    /**
     * Delete test
     */
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
    
    /**
     * Duplicate test
     */
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
    
    /**
     * Manage sections
     */
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
    
    /**
     * Submit test for approval
     */
    public function submitForApproval($id)
    {
        $test = IeltsTest::where('id', $id)
            ->where('created_by', auth()->id())
            ->firstOrFail();
        
        // Check if test has sections
        if ($test->sections->count() === 0) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Cannot submit test without sections',
                'status' => 'error'
            ]]);
        }
        
        // Validate mock test structure if applicable
        if ($test->type === 'mock') {
            $validation = $test->validateMockTestStructure();
            if (!$validation['valid']) {
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'Test incomplete: ' . implode(', ', $validation['errors']),
                    'status' => 'error'
                ]]);
            }
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
            ->route('panel.my_ielts_tests')
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Test submitted for approval! Manager/CEO will review it.',
                'status' => 'success'
            ]]);
    }
    
    /**
     * Show create section form
     */
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
    
    /**
     * Store new section
     */
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
    
    /**
     * Show edit section form
     */
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
    
    /**
     * Update section
     */
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
    
    /**
     * Delete section
     */
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
    
    /**
     * Show questions for section
     */
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
    
    /**
     * Show create question form
     */
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
    
    /**
     * Store new question
     */
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
    
    /**
     * Show edit question form
     */
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
    
    /**
     * Update question
     */
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
    
    /**
     * Delete question
     */
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
}
