<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IeltsMockQuestionBank;
use App\Models\IeltsPracticeQuestionBank;
use App\Models\User;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    /**
     * Admin Dashboard - All questions from all teachers
     */
    public function index()
    {
        // Admin can see ALL questions from all teachers
        $mockStats = [
            'total' => IeltsMockQuestionBank::count(),
            'listening' => IeltsMockQuestionBank::where('skill', 'listening')->count(),
            'reading' => IeltsMockQuestionBank::where('skill', 'reading')->count(),
            'writing' => IeltsMockQuestionBank::where('skill', 'writing')->count(),
            'speaking' => IeltsMockQuestionBank::where('skill', 'speaking')->count(),
        ];
        
        $practiceStats = [
            'total' => IeltsPracticeQuestionBank::count(),
            'listening' => IeltsPracticeQuestionBank::where('skill', 'listening')->count(),
            'reading' => IeltsPracticeQuestionBank::where('skill', 'reading')->count(),
            'writing' => IeltsPracticeQuestionBank::where('skill', 'writing')->count(),
            'speaking' => IeltsPracticeQuestionBank::where('skill', 'speaking')->count(),
        ];
        
        // Creator statistics
        $topCreators = IeltsMockQuestionBank::selectRaw('created_by, COUNT(*) as count')
            ->whereNotNull('created_by')
            ->groupBy('created_by')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
        
        // Usage statistics
        $mostUsed = IeltsMockQuestionBank::mostUsed()->limit(10)->get();
        $leastUsed = IeltsMockQuestionBank::leastUsed()->where('usage_count', '>', 0)->limit(10)->get();
        
        // Recent additions
        $recentMock = IeltsMockQuestionBank::with('creator')->newest()->limit(10)->get();
        $recentPractice = IeltsPracticeQuestionBank::with('creator')->newest()->limit(10)->get();
        
        return view('design_1.admin.question_bank.dashboard', compact(
            'mockStats',
            'practiceStats',
            'topCreators',
            'mostUsed',
            'leastUsed',
            'recentMock',
            'recentPractice'
        ));
    }
    
    /**
     * Mock Bank - Admin view (all teachers)
     */
    public function mockList(Request $request)
    {
        $query = IeltsMockQuestionBank::with('creator');
        
        // Admin filters
        if ($request->filled('skill')) {
            $query->bySkill($request->skill);
        }
        
        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        
        if ($request->filled('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }
        
        if ($request->filled('creator')) {
            $query->where('created_by', $request->creator);
        }
        
        if ($request->filled('usage')) {
            if ($request->usage === 'unused') {
                $query->where('usage_count', 0);
            } elseif ($request->usage === 'low') {
                $query->whereBetween('usage_count', [1, 5]);
            } elseif ($request->usage === 'high') {
                $query->where('usage_count', '>', 10);
            }
        }
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort === 'most_used') {
            $query->mostUsed();
        } elseif ($sort === 'least_used') {
            $query->leastUsed();
        } else {
            $query->newest();
        }
        
        $questions = $query->paginate(30);
        
        // Get all creators for filter
        $creators = User::whereIn('role_id', [1, 4]) // Admin, Teacher
            ->orderBy('full_name')
            ->get();
        
        return view('design_1.admin.question_bank.mock_list', compact('questions', 'creators'));
    }
    
    /**
     * Practice Bank - Admin view
     */
    public function practiceList(Request $request)
    {
        $query = IeltsPracticeQuestionBank::with('creator');
        
        // Same filters as mock + target band
        if ($request->filled('skill')) {
            $query->bySkill($request->skill);
        }
        
        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        
        if ($request->filled('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }
        
        if ($request->filled('creator')) {
            $query->where('created_by', $request->creator);
        }
        
        if ($request->filled('target_band')) {
            $query->byTargetBand($request->target_band);
        }
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        $sort = $request->get('sort', 'newest');
        if ($sort === 'most_used') {
            $query->mostUsed();
        } elseif ($sort === 'least_used') {
            $query->leastUsed();
        } else {
            $query->newest();
        }
        
        $questions = $query->paginate(30);
        
        $creators = User::whereIn('role_id', [1, 4])
            ->orderBy('full_name')
            ->get();
        
        return view('design_1.admin.question_bank.practice_list', compact('questions', 'creators'));
    }
    
    /**
     * View question details (admin can see everything)
     */
    public function show($bankType, $id)
    {
        if ($bankType === 'mock') {
            $question = IeltsMockQuestionBank::with('creator', 'testUsages')->findOrFail($id);
        } else {
            $question = IeltsPracticeQuestionBank::with('creator', 'testUsages')->findOrFail($id);
        }
        
        return view('design_1.admin.question_bank.show', compact('question', 'bankType'));
    }
    
    /**
     * Admin can create questions directly
     */
    public function create(Request $request)
    {
        $bankType = $request->get('bank_type', 'mock');
        
        return view('design_1.admin.question_bank.create', compact('bankType'));
    }
    
    /**
     * Store new question (admin)
     */
    public function store(Request $request)
    {
        $bankType = $request->get('bank_type', 'mock');
        
        $rules = [
            'skill' => 'required|in:listening,reading,writing,speaking',
            'question_type' => 'required|string|max:50',
            'question_text' => 'required|string',
            'instruction' => 'nullable|string',
            'passage_text' => 'nullable|string',
            'answer_options' => 'nullable|array',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'points' => 'nullable|numeric|min:0.5|max:10',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'tags' => 'nullable|array',
            'section_type' => 'nullable|string|max:50',
        ];
        
        if ($bankType === 'practice') {
            $rules['practice_focus'] = 'nullable|string|max:100';
            $rules['target_band'] = 'nullable|string|max:10';
        }
        
        $validated = $request->validate($rules);
        $validated['created_by'] = auth()->id();
        $validated['created_at'] = time();
        $validated['updated_at'] = time();
        
        if ($bankType === 'mock') {
            $question = IeltsMockQuestionBank::create($validated);
            $listRoute = 'admin.question_bank.mock.list';
        } else {
            $question = IeltsPracticeQuestionBank::create($validated);
            $listRoute = 'admin.question_bank.practice.list';
        }
        
        return redirect()->route($listRoute)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Question added successfully!',
                'status' => 'success'
            ]]);
    }
    
    /**
     * Edit question (admin can edit any question)
     */
    public function edit($bankType, $id)
    {
        if ($bankType === 'mock') {
            $question = IeltsMockQuestionBank::findOrFail($id);
        } else {
            $question = IeltsPracticeQuestionBank::findOrFail($id);
        }
        
        return view('design_1.admin.question_bank.edit', compact('question', 'bankType'));
    }
    
    /**
     * Update question
     */
    public function update(Request $request, $bankType, $id)
    {
        $rules = [
            'skill' => 'required|in:listening,reading,writing,speaking',
            'question_type' => 'required|string|max:50',
            'question_text' => 'required|string',
            'instruction' => 'nullable|string',
            'passage_text' => 'nullable|string',
            'answer_options' => 'nullable|array',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'points' => 'nullable|numeric|min:0.5|max:10',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'tags' => 'nullable|array',
            'section_type' => 'nullable|string|max:50',
        ];
        
        if ($bankType === 'practice') {
            $rules['practice_focus'] = 'nullable|string|max:100';
            $rules['target_band'] = 'nullable|string|max:10';
        }
        
        $validated = $request->validate($rules);
        $validated['updated_by'] = auth()->id();
        $validated['updated_at'] = time();
        
        if ($bankType === 'mock') {
            $question = IeltsMockQuestionBank::findOrFail($id);
            $listRoute = 'admin.question_bank.mock.list';
        } else {
            $question = IeltsPracticeQuestionBank::findOrFail($id);
            $listRoute = 'admin.question_bank.practice.list';
        }
        
        $question->update($validated);
        
        return redirect()->route($listRoute)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Question updated successfully!',
                'status' => 'success'
            ]]);
    }
    
    /**
     * Delete question (admin can delete any)
     */
    public function destroy($bankType, $id)
    {
        if ($bankType === 'mock') {
            $question = IeltsMockQuestionBank::findOrFail($id);
            $listRoute = 'admin.question_bank.mock.list';
        } else {
            $question = IeltsPracticeQuestionBank::findOrFail($id);
            $listRoute = 'admin.question_bank.practice.list';
        }
        
        // Check if used in tests
        if ($question->usage_count > 0) {
            return redirect()->back()
                ->with(['toast' => [
                    'title' => 'Warning',
                    'msg' => 'Cannot delete question used in ' . $question->usage_count . ' tests!',
                    'status' => 'error'
                ]]);
        }
        
        $question->delete();
        
        return redirect()->route($listRoute)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Question deleted successfully!',
                'status' => 'success'
            ]]);
    }
    
    /**
     * Bulk delete unused questions (Admin-only feature)
     */
    public function bulkDeleteUnused($bankType)
    {
        if ($bankType === 'mock') {
            $deleted = IeltsMockQuestionBank::where('usage_count', 0)->delete();
            $listRoute = 'admin.question_bank.mock.list';
        } else {
            $deleted = IeltsPracticeQuestionBank::where('usage_count', 0)->delete();
            $listRoute = 'admin.question_bank.practice.list';
        }
        
        return redirect()->route($listRoute)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => "Deleted {$deleted} unused questions!",
                'status' => 'success'
            ]]);
    }
    
    /**
     * Statistics page (Admin-only)
     */
    public function statistics()
    {
        $stats = [
            'mock' => [
                'total' => IeltsMockQuestionBank::count(),
                'unused' => IeltsMockQuestionBank::where('usage_count', 0)->count(),
                'low_usage' => IeltsMockQuestionBank::whereBetween('usage_count', [1, 5])->count(),
                'high_usage' => IeltsMockQuestionBank::where('usage_count', '>', 10)->count(),
            ],
            'practice' => [
                'total' => IeltsPracticeQuestionBank::count(),
                'unused' => IeltsPracticeQuestionBank::where('usage_count', 0)->count(),
                'low_usage' => IeltsPracticeQuestionBank::whereBetween('usage_count', [1, 5])->count(),
                'high_usage' => IeltsPracticeQuestionBank::where('usage_count', '>', 10)->count(),
            ],
        ];
        
        return view('design_1.admin.question_bank.statistics', compact('stats'));
    }
}
