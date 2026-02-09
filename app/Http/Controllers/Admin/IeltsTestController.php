<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IeltsTest;
use App\Models\IeltsTestSection;
use App\Models\IeltsTestQuestion;
use App\Models\IeltsTestAttempt;
use App\Models\IeltsTestAnswer;
use App\Models\IeltsPracticeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Notification;

class IeltsTestController extends Controller
{
    /**
     * Display a listing of tests
     */
    public function index(Request $request)
    {
        $query = IeltsTest::with(['creator', 'approver']);
        
        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $tests = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $data = [
            'pageTitle' => 'IELTS Tests',
            'tests' => $tests,
        ];
        
        return view('admin.ielts_tests.index', $data);
    }
    
    /**
     * Show form to create new test
     */
    public function create()
    {
        $practiceCategories = IeltsPracticeCategory::active()->get()->groupBy('skill');
        
        $data = [
            'pageTitle' => 'Create IELTS Test',
            'practiceCategories' => $practiceCategories,
        ];
        
        return view('admin.ielts_tests.create', $data);
    }
    
    /**
     * Wizard-style test creation
     */
    public function wizard()
    {
        $practiceCategories = IeltsPracticeCategory::active()->get()->groupBy('skill');
        
        $data = [
            'pageTitle' => 'Create IELTS Test (Wizard)',
            'practiceCategories' => $practiceCategories,
        ];
        
        return view('admin.ielts_tests.wizard.create', $data);
    }
    
    /**
     * Store test from wizard and auto-create sections
     */
    public function wizardStore(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'type' => 'required|in:mock,practice',
            'format' => 'required|in:academic,general',
        ];

        if ($request->type === 'practice') {
            $rules['practice_skill'] = 'required|in:listening,reading,writing,speaking';
        }

        $this->validate($request, $rules);
        
        // Generate unique slug
        $slug = Str::slug($request->title);
        $count = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->title) . '-' . $count;
            $count++;
        }
        
        // Create test based on type
        if ($request->type === 'mock') {
            $test = $this->createMockTest($request, $slug);
            $this->createMockSections($test);
        } else {
            $test = $this->createPracticeTest($request, $slug);
            $this->createPracticeSection($test, $request->practice_skill, $request->skill_duration);
        }
        
        $toastData = [
            'title' => 'Success',
            'msg' => 'Test created with sections! Now add content.',
            'status' => 'success'
        ];
        
        // Redirect to first section's question groups
        $firstSection = $test->sections()->orderBy('order')->first();
        if ($firstSection) {
            return redirect()->route('admin.ielts_tests.question_groups', $firstSection->id)
                ->with(['toast' => $toastData]);
        }
        
        return redirect()->route('admin.ielts_tests.sections', $test->id)
            ->with(['toast' => $toastData]);
    }
    
    /**
     * Create Mock Test record
     */
    private function createMockTest(Request $request, $slug)
    {
        return IeltsTest::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'type' => 'mock',
            'format' => $request->format,
            
            'listening_duration' => 30,
            'reading_duration' => 60,
            'writing_duration' => 60,
            'speaking_duration' => 15,
            'total_duration' => 165,
            
            'has_listening' => 1,
            'has_reading' => 1,
            'has_writing' => 1,
            'has_speaking' => 1,
            
            'difficulty_level' => $request->difficulty_level ?? 'intermediate',
            'target_band_min' => $request->target_band_min,
            'target_band_max' => $request->target_band_max,
            
            'created_by' => auth()->id(),
            'created_at' => time(),
            'status' => 'draft',
        ]);
    }
    
    /**
     * Auto-create 4 sections for Mock Test (L-R-W-S order)
     */
    private function createMockSections(IeltsTest $test)
    {
        $sections = [
            [
                'skill' => 'listening',
                'title' => 'Listening',
                'description' => 'IELTS Listening Test - 4 Parts, 40 Questions',
                'duration' => 30,
                'total_questions' => 40,
                'order' => 1,
            ],
            [
                'skill' => 'reading',
                'title' => 'Reading',
                'description' => 'IELTS Reading Test - 3 Passages, 40 Questions',
                'duration' => 60,
                'total_questions' => 40,
                'order' => 2,
            ],
            [
                'skill' => 'writing',
                'title' => 'Writing',
                'description' => 'IELTS Writing Test - Task 1 & Task 2',
                'duration' => 60,
                'total_questions' => 2,
                'order' => 3,
            ],
            [
                'skill' => 'speaking',
                'title' => 'Speaking',
                'description' => 'IELTS Speaking Test - 3 Parts',
                'duration' => 15,
                'total_questions' => 3,
                'order' => 4,
            ],
        ];
        
        foreach ($sections as $sectionData) {
            IeltsTestSection::create([
                'test_id' => $test->id,
                'skill' => $sectionData['skill'],
                'title' => $sectionData['title'],
                'description' => $sectionData['description'],
                'duration' => $sectionData['duration'],
                'total_questions' => $sectionData['total_questions'],
                'order' => $sectionData['order'],
                'status' => 'active',
                'created_at' => time(),
            ]);
        }
    }
    
    /**
     * Create Practice Test record
     */
    private function createPracticeTest(Request $request, $slug)
    {
        $skill = $request->practice_skill;
        $duration = $request->skill_duration ?? $this->getDefaultDuration($skill);
        
        return IeltsTest::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'type' => 'practice',
            'format' => $request->format,
            
            'practice_category_id' => $request->practice_category_id,
            'practice_mode' => $request->practice_mode ?? 'untimed',
            'show_answers_immediately' => $request->has('show_answers_immediately') ? 1 : 0,
            'allow_retake' => $request->has('allow_retake') ? 1 : 0,
            
            'listening_duration' => $skill === 'listening' ? $duration : null,
            'reading_duration' => $skill === 'reading' ? $duration : null,
            'writing_duration' => $skill === 'writing' ? $duration : null,
            'speaking_duration' => $skill === 'speaking' ? $duration : null,
            'total_duration' => $duration,
            
            'has_listening' => $skill === 'listening' ? 1 : 0,
            'has_reading' => $skill === 'reading' ? 1 : 0,
            'has_writing' => $skill === 'writing' ? 1 : 0,
            'has_speaking' => $skill === 'speaking' ? 1 : 0,
            
            'difficulty_level' => $request->difficulty_level ?? 'intermediate',
            'target_band_min' => $request->target_band_min,
            'target_band_max' => $request->target_band_max,
            
            'created_by' => auth()->id(),
            'created_at' => time(),
            'status' => 'draft',
        ]);
    }
    
    /**
     * Auto-create 1 section for Practice Test
     */
    private function createPracticeSection(IeltsTest $test, $skill, $duration = null)
    {
        $duration = $duration ?? $this->getDefaultDuration($skill);
        $questionsCount = in_array($skill, ['listening', 'reading']) ? 40 : (($skill === 'writing') ? 2 : 3);
        
        IeltsTestSection::create([
            'test_id' => $test->id,
            'skill' => $skill,
            'title' => ucfirst($skill) . ' Practice',
            'description' => 'IELTS ' . ucfirst($skill) . ' Practice Test',
            'duration' => $duration,
            'total_questions' => $questionsCount,
            'order' => 1,
            'status' => 'active',
            'created_at' => time(),
        ]);
    }
    
    /**
     * Get default duration for skill
     */
    private function getDefaultDuration($skill)
    {
        return match($skill) {
            'listening' => 30,
            'reading' => 60,
            'writing' => 60,
            'speaking' => 15,
            default => 30,
        };
    }
    
    /**
     * Store new test
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'type' => 'required|in:mock,practice,diagnostic',
            'format' => 'required|in:academic,general,both',
        ];

        // Additional validation for practice tests
        if ($request->type === 'practice') {
            $rules['practice_skill'] = 'required|in:listening,reading,writing,speaking';
            $rules['skill_duration'] = 'required|integer|min:1';
        }

        $this->validate($request, $rules);
        
        // Generate unique slug
        $slug = Str::slug($request->title);
        $count = 1;
        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->title) . '-' . $count;
            $count++;
        }
        
        // For mock tests, enforce requirements
        if ($request->type === 'mock') {
            $data = [
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'type' => 'mock',
                'format' => $request->format,
                
                // Enforce mock test durations - LRWS order
                'listening_duration' => 30,
                'reading_duration' => 60,
                'writing_duration' => 60,
                'speaking_duration' => 15,
                'total_duration' => 165, // 30 + 60 + 60 + 15
                
                // All skills required in LRWS order
                'has_listening' => 1,
                'has_reading' => 1,
                'has_writing' => 1,
                'has_speaking' => 1,
                
                'difficulty_level' => $request->difficulty_level ?? 'intermediate',
                'target_band_min' => $request->target_band_min,
                'target_band_max' => $request->target_band_max,
                
                'created_by' => auth()->id(),
                'created_at' => time(),
                'status' => 'draft',
            ];
        } else {
            // Practice test - single skill only
            $skill = $request->practice_skill;
            $duration = $request->skill_duration;

            $data = [
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'type' => $request->type,
                'format' => $request->format,
                
                'practice_category_id' => $request->practice_category_id,
                'practice_mode' => $request->practice_mode ?? 'untimed',
                'show_answers_immediately' => $request->has('show_answers_immediately') ? 1 : 0,
                'allow_retake' => $request->has('allow_retake') ? 1 : 0,
                
                // Set duration for selected skill only
                'listening_duration' => $skill === 'listening' ? $duration : null,
                'reading_duration' => $skill === 'reading' ? $duration : null,
                'writing_duration' => $skill === 'writing' ? $duration : null,
                'speaking_duration' => $skill === 'speaking' ? $duration : null,
                'total_duration' => $duration,
                
                // Set only selected skill
                'has_listening' => $skill === 'listening' ? 1 : 0,
                'has_reading' => $skill === 'reading' ? 1 : 0,
                'has_writing' => $skill === 'writing' ? 1 : 0,
                'has_speaking' => $skill === 'speaking' ? 1 : 0,
                
                'difficulty_level' => $request->difficulty_level ?? 'mixed',
                'target_band_min' => $request->target_band_min,
                'target_band_max' => $request->target_band_max,
                
                'created_by' => auth()->id(),
                'created_at' => time(),
                'status' => 'draft',
            ];
        }
        
        $test = IeltsTest::create($data);
        
        $toastData = [
            'title' => 'Success',
            'msg' => 'Test created successfully! Now add sections.',
            'status' => 'success'
        ];
        
        return redirect()->route('admin.ielts_tests.sections', $test->id)->with(['toast' => $toastData]);
    }
    
    /**
     * Edit test
     */
    public function edit($id)
    {
        $test = IeltsTest::findOrFail($id);
        
        // Check if can be edited
        if (!$test->canBeEdited()) {
            $toastData = [
                'title' => 'Error',
                'msg' => 'This test cannot be edited. Current status: ' . $test->status,
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }
        
        $practiceCategories = IeltsPracticeCategory::active()->get()->groupBy('skill');
        
        $data = [
            'pageTitle' => 'Edit Test',
            'test' => $test,
            'practiceCategories' => $practiceCategories,
        ];
        
        return view('admin.ielts_tests.edit', $data);
    }
    
    /**
     * Update test
     */
    public function update(Request $request, $id)
    {
        $test = IeltsTest::findOrFail($id);
        
        if (!$test->canBeEdited()) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Cannot edit test in current status',
                'status' => 'error'
            ]]);
        }
        
        $this->validate($request, [
            'title' => 'required|max:255',
        ]);
        
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'target_band_min' => $request->target_band_min,
            'target_band_max' => $request->target_band_max,
            'updated_at' => time(),
        ];
        
        // Update practice-specific fields if applicable
        if ($test->isPracticeTest()) {
            $data['practice_category_id'] = $request->practice_category_id;
            $data['practice_mode'] = $request->practice_mode;
            $data['show_answers_immediately'] = $request->has('show_answers_immediately') ? 1 : 0;
            $data['allow_retake'] = $request->has('allow_retake') ? 1 : 0;
        }
        
        $test->update($data);
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Test updated successfully',
            'status' => 'success'
        ]]);
    }
    
    /**
     * Delete test
     */
    public function destroy($id)
    {
        $test = IeltsTest::findOrFail($id);
        
        // Check if has attempts
        if ($test->attempts()->count() > 0) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Cannot delete test with existing attempts',
                'status' => 'error'
            ]]);
        }
        
        $test->delete();
        
        return redirect()->route('admin.ielts_tests.index')->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Test deleted successfully',
            'status' => 'success'
        ]]);
    }
    
    /**
     * Manage sections for a test
     */
    public function manageSections($testId)
    {
        $test = IeltsTest::with('sections.questions')->findOrFail($testId);
        
        $data = [
            'pageTitle' => 'Manage Sections - ' . $test->title,
            'test' => $test,
        ];
        
        return view('admin.ielts_tests.sections', $data);
    }
    
    /**
     * Store new section
     */
    public function storeSection(Request $request, $testId)
    {
        $test = IeltsTest::findOrFail($testId);
        
        $this->validate($request, [
            'skill' => 'required|in:listening,reading,writing,speaking',
            'section_number' => 'required|integer',
            'title' => 'required|max:255',
            'question_start' => 'required|integer',
            'question_end' => 'required|integer|gte:question_start',
        ]);

        // Validate that selected skill is available in this test
        $skillField = 'has_' . $request->skill;
        if (!$test->$skillField) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'This test does not include ' . ucfirst($request->skill) . ' skill!',
                'status' => 'error'
            ]]);
        }

        // Additional validation based on skill
        if ($request->skill === 'listening' && !$request->hasFile('audio_file')) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Audio file is required for Listening sections!',
                'status' => 'error'
            ]]);
        }

        if ($request->skill === 'reading' && !$request->passage_text) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Reading passage text is required for Reading sections!',
                'status' => 'error'
            ]]);
        }
        
        $data = [
            'test_id' => $test->id,
            'skill' => $request->skill,
            'section_number' => $request->section_number,
            'title' => $request->title,
            'instructions' => $request->instructions,
            'question_start' => $request->question_start,
            'question_end' => $request->question_end,
            'duration_minutes' => $request->duration_minutes,
            'sort_order' => $request->sort_order ?? 0,
            'created_at' => time(),
        ];
        
        // Handle file uploads
        if ($request->hasFile('audio_file')) {
            $audio = $request->file('audio_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            $audio->move(public_path('uploads/ielts/audio'), $audioName);
            $data['audio_file'] = '/uploads/ielts/audio/' . $audioName;
        }
        
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/ielts/images'), $imageName);
            $data['image_file'] = '/uploads/ielts/images/' . $imageName;
        }
        
        // Store skill-specific data
        if ($request->skill === 'reading') {
            $data['passage_text'] = $request->passage_text;
            $data['passage_title'] = $request->passage_title;
        }

        if ($request->skill === 'writing') {
            $data['writing_task_type'] = $request->writing_task_type;
        }

        if ($request->skill === 'speaking') {
            $data['speaking_part_type'] = $request->speaking_part_type;
        }
        
        $section = IeltsTestSection::create($data);
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Section created successfully!',
            'status' => 'success'
        ]]);
    }
    
    /**
     * Manage question groups for a section
     */
    public function manageQuestionGroups($sectionId)
    {
        $section = IeltsTestSection::with('test')->findOrFail($sectionId);
        
        // Get question groups for this section
        $questionGroups = \App\Models\IeltsQuestionGroup::where('section_id', $section->id)
                            ->orderBy('question_start')
                            ->get();
        
        $data = [
            'pageTitle' => 'Manage Question Groups - ' . $section->title,
            'section' => $section,
            'test' => $section->test,
            'questionGroups' => $questionGroups,
        ];
        
        return view('admin.ielts_tests.question_groups', $data);
    }
    
    /**
     * Store question group
     */
    public function storeQuestionGroup(Request $request, $sectionId)
    {
        $section = IeltsTestSection::findOrFail($sectionId);
        
        $this->validate($request, [
            'title' => 'required|max:255',
            'question_type' => 'required',
            'question_start' => 'required|integer',
            'question_end' => 'required|integer|gte:question_start',
        ]);

        $data = [
            'section_id' => $section->id,
            'bank_type' => $section->test->type === 'mock' ? 'mock' : 'practice',
            'skill' => $section->skill,
            'title' => $request->title,
            'question_type' => $request->question_type,
            'question_start' => $request->question_start,
            'question_end' => $request->question_end,
            'instructions' => $request->instructions,
            'max_words' => $request->max_words,
            'creator_id' => auth()->id(),
        ];

        // Handle passage for reading
        if ($section->skill === 'reading' && $request->passage) {
            $data['passage'] = $request->passage;
        }

        // Handle audio file for listening
        if ($section->skill === 'listening' && $request->hasFile('audio_file')) {
            $audio = $request->file('audio_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            $audio->move(public_path('uploads/ielts/audio'), $audioName);
            $data['audio_file'] = '/uploads/ielts/audio/' . $audioName;
            $data['audio_path'] = $data['audio_file'];
        }

        // Handle task image
        if ($request->hasFile('task_image')) {
            $image = $request->file('task_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/ielts/images'), $imageName);
            $data['task_image'] = '/uploads/ielts/images/' . $imageName;
        }

        $questionGroup = \App\Models\IeltsQuestionGroup::create($data);

        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Question group created! Now add questions to this group.',
            'status' => 'success'
        ]]);
    }

    /**
     * Delete question group
     */
    public function deleteQuestionGroup($groupId)
    {
        $group = \App\Models\IeltsQuestionGroup::findOrFail($groupId);
        $group->delete();

        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Question group deleted successfully!',
            'status' => 'success'
        ]]);
    }
    
    /**
     * Manage questions for a section (Legacy - redirects to question groups)
     */
    public function manageQuestions($sectionId)
    {
        // Redirect to question groups workflow
        return redirect()->route('admin.ielts_tests.question_groups', $sectionId);
    }
    
    /**
     * Store question
     */
    public function storeQuestion(Request $request, $sectionId)
    {
        $section = IeltsTestSection::findOrFail($sectionId);
        
        $this->validate($request, [
            'question_number' => 'required|integer',
            'question_type' => 'required',
            'question_text' => 'required',
        ]);
        
        $data = [
            'section_id' => $section->id,
            'question_number' => $request->question_number,
            'question_type' => $request->question_type,
            'question_text' => $request->question_text,
            'instruction' => $request->instruction,
            'correct_answer' => $request->correct_answer,
            'answer_options' => $request->answer_options ? json_encode($request->answer_options) : null,
            'accept_synonyms' => $request->has('accept_synonyms') ? 1 : 0,
            'case_sensitive' => $request->has('case_sensitive') ? 1 : 0,
            'max_words' => $request->max_words,
            'points' => $request->points ?? 1.0,
            'auto_gradable' => in_array($request->question_type, ['essay']) ? 0 : 1,
            'hint' => $request->hint,
            'explanation' => $request->explanation,
            'sort_order' => $request->sort_order ?? $request->question_number,
            'created_at' => time(),
        ];
        
        $question = IeltsTestQuestion::create($data);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Question created successfully',
            'question' => $question
        ]);
    }
    
    /**
     * Submit test for approval
     */
    public function submitForApproval($id)
    {
        $test = IeltsTest::findOrFail($id);
        
        // Validate structure
        $validation = $test->validateMockTestStructure();
        
        if (!$validation['valid']) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'Test is incomplete: ' . implode(', ', $validation['errors']),
                'status' => 'error'
            ]]);
        }
        
        $test->update([
            'status' => 'pending_approval',
            'submitted_for_approval_at' => time(),
        ]);
        
        // TODO: Notify managers/CEOs
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Test submitted for approval!',
            'status' => 'success'
        ]]);
    }
    
    /**
     * Approve test - Khi approve sẽ tự động published luôn, student có thể làm ngay
     */
    public function approve(Request $request, $id)
    {
        $authUser = auth()->user();
        
        // Check permission - Admin, Manager, CEO có thể approve
        if (!$authUser->isAdmin() && !$authUser->isManager() && !$authUser->isCeo()) {
            abort(403);
        }
        
        $test = IeltsTest::findOrFail($id);
        
        // Khi approve → tự động published và active, student có thể làm ngay
        $test->update([
            'status' => 'published',
            'approved_by' => auth()->id(),
            'approved_at' => time(),
            'is_active' => 1,
        ]);
        
        // Send in-app notification to creator
        if ($test->created_by) {
            Notification::create([
                'user_id' => $test->created_by,
                'sender' => Notification::$SystemSender,
                'title' => 'IELTS Test Approved & Published',
                'message' => 'Your test "' . $test->title . '" has been approved and is now available to students!',
                'type' => 'single',
                'created_at' => time(),
            ]);
        }
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Test approved and published! Students can now access it.',
            'status' => 'success'
        ]]);
    }
    
    /**
     * Reject test
     */
    public function reject(Request $request, $id)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isManager() && !$authUser->isCeo()) {
            abort(403);
        }
        
        $test = IeltsTest::findOrFail($id);
        
        $reason = $request->input('rejection_reason');
        
        $test->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
        
        // Send in-app notification to creator with rejection reason
        if ($test->created_by) {
            Notification::create([
                'user_id' => $test->created_by,
                'sender' => Notification::$SystemSender,
                'title' => 'IELTS Test Rejected',
                'message' => 'Your test "' . $test->title . '" was rejected. Reason: ' . $reason,
                'type' => 'single',
                'created_at' => time(),
            ]);
        }
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Test rejected.',
            'status' => 'success'
        ]]);
    }
    
    /**
     * Publish approved test
     */
    public function publish($id)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isManager() && !$authUser->isCeo() && !$authUser->isAdmin()) {
            abort(403);
        }
        
        $test = IeltsTest::findOrFail($id);
        
        $test->update([
            'status' => 'published',
            'is_active' => 1,
        ]);
        
        // Notify creator
        if ($test->created_by) {
            Notification::create([
                'user_id' => $test->created_by,
                'sender' => Notification::$SystemSender,
                'title' => 'IELTS Test Published',
                'message' => 'Your test "' . $test->title . '" is now published and available to students.',
                'type' => 'single',
                'created_at' => time(),
            ]);
        }
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Test published successfully! Students can now access it.',
            'status' => 'success'
        ]]);
    }
    
    /**
     * Unpublish test
     */
    public function unpublish($id)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isManager() && !$authUser->isCeo() && !$authUser->isAdmin()) {
            abort(403);
        }
        
        $test = IeltsTest::findOrFail($id);
        
        $test->update([
            'status' => 'approved',
            'is_active' => 0,
        ]);
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Test unpublished.',
            'status' => 'success'
        ]]);
    }
    
    /**
     * View pending approval tests
     */
    public function pendingApproval()
    {
        $authUser = auth()->user();
        
        if (!$authUser->isManager() && !$authUser->isCeo()) {
            abort(403);
        }
        
        // Query Question Groups instead of Tests
        $groups = \App\Models\IeltsQuestionGroup::with('creator')
            ->where('status', 'pending')
            ->withCount('questions')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        
        $data = [
            'pageTitle' => trans('update.ielts_question_groups_pending_approval'),
            'groups' => $groups,
        ];
        
        return view('admin.ielts_tests.pending_approval', $data);
    }
    
    /**
     * Approve Question Group
     */
    public function approveQuestionGroup($id)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isManager() && !$authUser->isCeo()) {
            abort(403);
        }
        
        $group = \App\Models\IeltsQuestionGroup::findOrFail($id);
        
        $group->update(['status' => 'approved']);
        
            Notification::create([
                'user_id' => $group->creator_id,
                'sender' => Notification::$SystemSender,
                'title' => trans('update.question_group_approved'),
            'message' => trans('update.question_group_approved_msg', ['title' => $group->title]),
                'type' => 'single',
                'created_at' => time(),
            ]);
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Question group approved successfully!',
            'status' => 'success'
        ]]);
    }
    
    /**
     * Reject Question Group
     */
    public function rejectQuestionGroup(Request $request, $id)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isManager() && !$authUser->isCeo()) {
            abort(403);
        }
        
        $group = \App\Models\IeltsQuestionGroup::findOrFail($id);
        $reason = $request->input('rejection_reason');
        
        $group->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);
        
        // Notify creator
        if ($group->creator_id) {
            Notification::create([
                'user_id' => $group->creator_id,
                'sender' => Notification::$SystemSender,
                'title' => trans('update.question_group_rejected'),
            'message' => trans('update.question_group_rejected_msg', ['title' => $group->title, 'reason' => ($reason ?? 'No reason provided')]),
                'type' => 'single',
                'created_at' => time(),
            ]);
        }
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Question group rejected.',
            'status' => 'success'
        ]]);
    }
    
    /**
     * List all test attempts for grading
     */
    public function attempts(Request $request)
    {
        /** @var \App\User $authUser */
        $authUser = auth()->user();
        
        $query = IeltsTestAttempt::with(['test', 'user'])
            ->where('status', 'completed');
        
        // Filter by grading status
        if ($request->filled('grading_status')) {
            if ($request->grading_status === 'pending') {
                // Has ungraded Writing or Speaking answers
                $query->whereHas('answers', function($q) {
                    $q->whereHas('question.section', function($sq) {
                        $sq->whereIn('skill', ['writing', 'speaking']);
                    })->whereNull('graded_at');
                });
            } elseif ($request->grading_status === 'graded') {
                // All W/S answers are graded
                $query->whereDoesntHave('answers', function($q) {
                    $q->whereHas('question.section', function($sq) {
                        $sq->whereIn('skill', ['writing', 'speaking']);
                    })->whereNull('graded_at');
                });
            }
        }
        
        // Filter by test
        if ($request->filled('test_id')) {
            $query->where('test_id', $request->test_id);
        }
        
        // Filter by student
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        // For teachers, only show attempts from their students
        if ($authUser->isTeacher() && !$authUser->isManager() && !$authUser->isCeo()) {
            // Get students from organization or course students
            $studentIds = $authUser->getOrganizationStudents()->pluck('id')->toArray();
            if (!empty($studentIds)) {
                $query->whereIn('user_id', $studentIds);
            }
        }
        
        $attempts = $query->orderBy('completed_at', 'desc')->paginate(20);
        
        // Get tests for filter dropdown
        $tests = IeltsTest::where('status', 'published')
            ->orderBy('title')
            ->pluck('title', 'id');
        
        // Count stats
        $pendingCount = IeltsTestAttempt::where('status', 'completed')
            ->whereHas('answers', function($q) {
                $q->whereHas('question.section', function($sq) {
                    $sq->whereIn('skill', ['writing', 'speaking']);
                })->whereNull('graded_at');
            })->count();
        
        $data = [
            'pageTitle' => trans('update.ielts_grading_dashboard'),
            'attempts' => $attempts,
            'tests' => $tests,
            'pendingCount' => $pendingCount,
        ];
        
        return view('admin.ielts_tests.attempts', $data);
    }
    
    /**
     * View a specific attempt for grading
     */
    public function viewAttempt($attemptId)
    {
        /** @var \App\User $authUser */
        $authUser = auth()->user();
        
        $attempt = IeltsTestAttempt::with([
            'test.sections',
            'user',
            'answers.question.section',
            'answers.grader'
        ])->findOrFail($attemptId);
        
        // For teachers, verify they have access to this student
        if ($authUser->isTeacher() && !$authUser->isManager() && !$authUser->isCeo()) {
            $studentIds = $authUser->getOrganizationStudents()->pluck('id')->toArray();
            if (!empty($studentIds) && !in_array($attempt->user_id, $studentIds)) {
                abort(403, 'You do not have permission to grade this student.');
            }
        }
        
        // Group answers by skill
        $answersBySkill = [
            'listening' => [],
            'reading' => [],
            'writing' => [],
            'speaking' => [],
        ];
        
        foreach ($attempt->answers as $answer) {
            $skill = $answer->question->section->skill ?? 'unknown';
            if (isset($answersBySkill[$skill])) {
                $answersBySkill[$skill][] = $answer;
            }
        }
        
        // Calculate grading progress for W/S
        $writingTotal = count($answersBySkill['writing']);
        $writingGraded = collect($answersBySkill['writing'])->whereNotNull('graded_at')->count();
        
        $speakingTotal = count($answersBySkill['speaking']);
        $speakingGraded = collect($answersBySkill['speaking'])->whereNotNull('graded_at')->count();
        
        $data = [
            'pageTitle' => 'Grade Attempt: ' . $attempt->user->full_name,
            'attempt' => $attempt,
            'answersBySkill' => $answersBySkill,
            'writingProgress' => $writingTotal > 0 ? round(($writingGraded / $writingTotal) * 100) : 100,
            'speakingProgress' => $speakingTotal > 0 ? round(($speakingGraded / $speakingTotal) * 100) : 100,
        ];
        
        return view('admin.ielts_tests.view_attempt', $data);
    }
    
    /**
     * Grade a specific answer (Writing/Speaking)
     */
    public function gradeAnswer(Request $request, $answerId)
    {
        $authUser = auth()->user();
        
        $answer = IeltsTestAnswer::with(['question.section', 'attempt'])->findOrFail($answerId);
        $skill = $answer->question->section->skill;
        
        // Only allow grading Writing and Speaking
        if (!in_array($skill, ['writing', 'speaking'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only Writing and Speaking answers can be manually graded.'
            ], 400);
        }
        
        // Validate based on skill
        if ($skill === 'writing') {
            $request->validate([
                'task_achievement' => 'required|numeric|min:0|max:9',
                'coherence_cohesion' => 'required|numeric|min:0|max:9',
                'lexical_resource' => 'required|numeric|min:0|max:9',
                'grammatical_range' => 'required|numeric|min:0|max:9',
                'feedback' => 'nullable|string|max:5000',
            ]);
            
            $bands = [
                'task_achievement' => floatval($request->task_achievement),
                'coherence_cohesion' => floatval($request->coherence_cohesion),
                'lexical_resource' => floatval($request->lexical_resource),
                'grammatical_range' => floatval($request->grammatical_range),
            ];
            
            // Calculate overall band (average, rounded to nearest 0.5)
            $average = array_sum($bands) / 4;
            $overallBand = round($average * 2) / 2;
            
        } else { // speaking
            $request->validate([
                'fluency_coherence' => 'required|numeric|min:0|max:9',
                'lexical_resource' => 'required|numeric|min:0|max:9',
                'grammatical_range' => 'required|numeric|min:0|max:9',
                'pronunciation' => 'required|numeric|min:0|max:9',
                'feedback' => 'nullable|string|max:5000',
            ]);
            
            $bands = [
                'fluency_coherence' => floatval($request->fluency_coherence),
                'lexical_resource' => floatval($request->lexical_resource),
                'grammatical_range' => floatval($request->grammatical_range),
                'pronunciation' => floatval($request->pronunciation),
            ];
            
            // Calculate overall band
            $average = array_sum($bands) / 4;
            $overallBand = round($average * 2) / 2;
        }
        
        // Use the model's manual grade method
        $answer->manualGrade(
            true, // is_correct (for W/S, we consider any submitted answer as "correct" in terms of completion)
            $overallBand, // points_earned = band score
            $request->feedback,
            $bands
        );
        
        // Update attempt scores if all answers in this skill are graded
        $this->updateAttemptSkillScore($answer->attempt, $skill);
        
        return response()->json([
            'success' => true,
            'message' => ucfirst($skill) . ' answer graded successfully.',
            'band_score' => $overallBand,
            'bands' => $bands,
        ]);
    }
    
    /**
     * Update attempt's skill score when all answers are graded
     */
    private function updateAttemptSkillScore(IeltsTestAttempt $attempt, $skill)
    {
        // Get all answers for this skill
        $answers = $attempt->answers()
            ->whereHas('question.section', function($q) use ($skill) {
                $q->where('skill', $skill);
            })
            ->get();
        
        // Check if all are graded
        $allGraded = $answers->every(fn($a) => $a->graded_at !== null);
        
        if ($allGraded && $answers->count() > 0) {
            // Calculate average band score for the skill
            $averageBand = $answers->avg('points_earned');
            $roundedBand = round($averageBand * 2) / 2;
            
            // Update attempt
            $scoreField = $skill . '_score';
            $attempt->{$scoreField} = $roundedBand;
            
            // Recalculate overall band if all 4 skills have scores
            if ($attempt->listening_score && $attempt->reading_score && 
                $attempt->writing_score && $attempt->speaking_score) {
                
                $overall = ($attempt->listening_score + $attempt->reading_score + 
                           $attempt->writing_score + $attempt->speaking_score) / 4;
                $attempt->overall_band = round($overall * 2) / 2;
            }
            
            $attempt->save();
        }
    }
    
    /**
     * Export attempts to Excel
     */
    public function exportAttemptsExcel(Request $request)
    {
        // TODO: Implement Excel export
        return back()->with(['toast' => [
            'title' => 'Info',
            'msg' => 'Excel export coming soon.',
            'status' => 'info'
        ]]);
    }
    
    /**
     * IELTS Settings page
     */
    public function settings()
    {
        $settings = getIeltsSettings();
        
        $data = [
            'pageTitle' => 'IELTS Settings',
            'settings' => $settings,
        ];
        
        return view('admin.ielts_tests.settings', $data);
    }
    
    /**
     * Store IELTS Settings
     */
    public function storeSettings(Request $request)
    {
        $validated = $request->validate([
            'mock_tests_per_day' => 'required|integer|min:1|max:10',
            'practice_unlimited' => 'nullable|boolean',
            'mock_requires_4_skills' => 'nullable|boolean',
        ]);
        
        $settings = [
            'mock_tests_per_day' => (int) $validated['mock_tests_per_day'],
            'practice_unlimited' => $request->has('practice_unlimited'),
            'mock_requires_4_skills' => $request->has('mock_requires_4_skills'),
        ];
        
        // Save to settings table
        $setting = \App\Models\Setting::where('name', 'ielts_settings')->first();
        
        if ($setting) {
            $setting->update(['value' => json_encode($settings)]);
        } else {
            \App\Models\Setting::create([
                'name' => 'ielts_settings',
                'value' => json_encode($settings),
            ]);
        }
        
        // Clear cached settings
        \App\Models\Setting::$ieltsSettings = null;
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'IELTS settings saved successfully.',
            'status' => 'success'
        ]]);
    }
}
