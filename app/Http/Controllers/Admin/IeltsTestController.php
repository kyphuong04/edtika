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
     * Store new test
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'type' => 'required|in:mock,practice,diagnostic',
            'format' => 'required|in:academic,general,both',
        ]);
        
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
                
                // Enforce mock test durations
                'listening_duration' => 30,
                'reading_duration' => 60,
                'writing_duration' => 60,
                'speaking_duration' => 15,
                
                // All skills required
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
            // Practice test - flexible
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
                
                // Flexible durations
                'listening_duration' => $request->listening_duration,
                'reading_duration' => $request->reading_duration,
                'writing_duration' => $request->writing_duration,
                'speaking_duration' => $request->speaking_duration,
                
                // Flexible skills
                'has_listening' => $request->has('has_listening') ? 1 : 0,
                'has_reading' => $request->has('has_reading') ? 1 : 0,
                'has_writing' => $request->has('has_writing') ? 1 : 0,
                'has_speaking' => $request->has('has_speaking') ? 1 : 0,
                
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
        
        // Store passage text for reading
        if ($request->skill === 'reading') {
            $data['passage_text'] = $request->passage_text;
            $data['passage_title'] = $request->passage_title;
        }
        
        $section = IeltsTestSection::create($data);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Section created successfully',
            'section' => $section
        ]);
    }
    
    /**
     * Manage questions for a section
     */
    public function manageQuestions($sectionId)
    {
        $section = IeltsTestSection::with(['test', 'questions'])->findOrFail($sectionId);
        
        $data = [
            'pageTitle' => 'Manage Questions - ' . $section->title,
            'section' => $section,
            'test' => $section->test,
        ];
        
        return view('admin.ielts_tests.questions', $data);
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
     * Approve test
     */
    public function approve(Request $request, $id)
    {
        $authUser = auth()->user();
        
        // Check permission
        if (!$authUser->isManager() && !$authUser->isCeo()) {
            abort(403);
        }
        
        $test = IeltsTest::findOrFail($id);
        
        $autoPublish = $request->input('auto_publish', false);
        
        $test->update([
            'status' => $autoPublish ? 'published' : 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => time(),
            'is_active' => $autoPublish ? 1 : 0,
        ]);
        
        // Send in-app notification to creator
        if ($test->created_by) {
            Notification::create([
                'user_id' => $test->created_by,
                'sender' => Notification::$SystemSender,
                'title' => 'IELTS Test Approved',
                'message' => 'Your test "' . $test->title . '" has been approved.',
                'type' => 'single',
                'created_at' => time(),
            ]);
        }
        
        return back()->with(['toast' => [
            'title' => 'Success',
            'msg' => 'Test approved successfully!',
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
     * View pending approval tests
     */
    public function pendingApproval()
    {
        $authUser = auth()->user();
        
        if (!$authUser->isManager() && !$authUser->isCeo()) {
            abort(403);
        }
        
        $tests = IeltsTest::with('creator')
            ->pendingApproval()
            ->orderBy('submitted_for_approval_at', 'desc')
            ->paginate(20);
        
        $data = [
            'pageTitle' => 'Pending Approval',
            'tests' => $tests,
        ];
        
        return view('admin.ielts_tests.pending_approval', $data);
    }
}
