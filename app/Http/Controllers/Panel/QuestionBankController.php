<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsMockQuestionBank;
use App\Models\IeltsPracticeQuestionBank;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class QuestionBankController extends Controller
{
    /**
     * Dashboard - Show question bank overview
     */
    public function index()
    {
        $user = auth()->user();
        
        // Check permissions
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
            abort(403, 'Unauthorized');
        }
        
        // Mock statistics
        $mockStats = [
            'total' => IeltsMockQuestionBank::count(),
            'standalone' => IeltsMockQuestionBank::whereNull('group_id')->count(),
            'grouped' => IeltsMockQuestionBank::whereNotNull('group_id')->count(),
            'groups' => \App\Models\IeltsQuestionGroup::where('bank_type', 'mock')->count(),
            'listening' => IeltsMockQuestionBank::where('skill', 'listening')->count(),
            'reading' => IeltsMockQuestionBank::where('skill', 'reading')->count(),
            'writing' => IeltsMockQuestionBank::where('skill', 'writing')->count(),
            'speaking' => IeltsMockQuestionBank::where('skill', 'speaking')->count(),
        ];
        
        // Practice statistics
        $practiceStats = [
            'total' => IeltsPracticeQuestionBank::count(),
            'standalone' => IeltsPracticeQuestionBank::whereNull('group_id')->count(),
            'grouped' => IeltsPracticeQuestionBank::whereNotNull('group_id')->count(),
            'groups' => \App\Models\IeltsQuestionGroup::where('bank_type', 'practice')->count(),
            'listening' => IeltsPracticeQuestionBank::where('skill', 'listening')->count(),
            'reading' => IeltsPracticeQuestionBank::where('skill', 'reading')->count(),
            'writing' => IeltsPracticeQuestionBank::where('skill', 'writing')->count(),
            'speaking' => IeltsPracticeQuestionBank::where('skill', 'speaking')->count(),
        ];
        
        // Recent questions
        $recentMock = IeltsMockQuestionBank::newest()->limit(5)->get();
        $recentPractice = IeltsPracticeQuestionBank::newest()->limit(5)->get();
        
        // Recent groups
        $recentMockGroups = \App\Models\IeltsQuestionGroup::where('bank_type', 'mock')
            ->with(['mockQuestions'])
            ->newest()
            ->limit(3)
            ->get();
            
        $recentPracticeGroups = \App\Models\IeltsQuestionGroup::where('bank_type', 'practice')
            ->with(['practiceQuestions'])
            ->newest()
            ->limit(3)
            ->get();
        
        return view('design_1.panel.question_bank.dashboard', compact(
            'mockStats',
            'practiceStats',
            'recentMock',
            'recentPractice',
            'recentMockGroups',
            'recentPracticeGroups'
        ));
    }
    
    /**
     * Mock Bank - List questions
     */
    public function mockList(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }
        
        $query = IeltsMockQuestionBank::query();
        
        // Topic filter
        if ($request->filled('topic')) {
            $query->whereJsonContains('tags', $request->topic);
        }
        
        // Filters
        if ($request->filled('skill')) {
            $query->bySkill($request->skill);
        }
        
        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        
        if ($request->filled('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Get topic statistics
        $allQuestions = IeltsMockQuestionBank::all();
        $topics = [];
        
        foreach ($allQuestions as $question) {
            if ($question->tags && is_array($question->tags)) {
                foreach ($question->tags as $tag) {
                    if (!isset($topics[$tag])) {
                        $topics[$tag] = 0;
                    }
                    $topics[$tag]++;
                }
            }
        }
        
        arsort($topics);
        
        // Pagination
        $questions = $query->newest()->paginate(20);
        
        // View mode (card or table)
        $viewMode = $request->get('view', 'table');
        
        return view('design_1.panel.question_bank.mock_list', compact('questions', 'topics', 'viewMode'));
    }
    
    /**
     * Practice Bank - List questions
     */
    public function practiceList(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }
        
        $query = IeltsPracticeQuestionBank::query();
        
        // Topic filter
        if ($request->filled('topic')) {
            $query->whereJsonContains('tags', $request->topic);
        }
        
        // Filters (same as mock + target band)
        if ($request->filled('skill')) {
            $query->bySkill($request->skill);
        }
        
        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        
        if ($request->filled('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }
        
        if ($request->filled('target_band')) {
            $query->byTargetBand($request->target_band);
        }
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Get topic statistics
        $allQuestions = IeltsPracticeQuestionBank::all();
        $topics = [];
        
        foreach ($allQuestions as $question) {
            if ($question->tags && is_array($question->tags)) {
                foreach ($question->tags as $tag) {
                    if (!isset($topics[$tag])) {
                        $topics[$tag] = 0;
                    }
                    $topics[$tag]++;
                }
            }
        }
        
        arsort($topics);
        
        $questions = $query->newest()->paginate(20);
        
        // View mode
        $viewMode = $request->get('view', 'table');
        
        return view('design_1.panel.question_bank.practice_list', compact('questions', 'topics', 'viewMode'));
    }
    
    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $bankType = $request->get('bank_type', 'mock'); // mock or practice
        
        return view('design_1.panel.question_bank.create', compact('bankType'));
    }
    
    /**
     * Store new question
     */
    public function store(Request $request)
    {
        $bankType = $request->get('bank_type', 'mock');
        
        // Validation rules
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
        
        // Practice-specific fields
        if ($bankType === 'practice') {
            $rules['practice_focus'] = 'nullable|string|max:100';
            $rules['target_band'] = 'nullable|string|max:10';
        }
        
        $validated = $request->validate($rules);
        
        // Set creator
        $validated['created_by'] = auth()->id();
        $validated['created_at'] = time();
        $validated['updated_at'] = time();
        
        // Create question
        if ($bankType === 'mock') {
            $question = IeltsMockQuestionBank::create($validated);
            $listRoute = 'panel.question_bank.mock.list';
        } else {
            $question = IeltsPracticeQuestionBank::create($validated);
            $listRoute = 'panel.question_bank.practice.list';
        }
        
        return redirect()->route($listRoute)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Question added to ' . ucfirst($bankType) . ' Bank!',
                'status' => 'success'
            ]]);
    }
    
    /**
     * Show edit form
     */
    public function edit($bankType, $id)
    {
        if ($bankType === 'mock') {
            $question = IeltsMockQuestionBank::findOrFail($id);
        } else {
            $question = IeltsPracticeQuestionBank::findOrFail($id);
        }
        
        return view('design_1.panel.question_bank.edit', compact('question', 'bankType'));
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
            $listRoute = 'panel.question_bank.mock.list';
        } else {
            $question = IeltsPracticeQuestionBank::findOrFail($id);
            $listRoute = 'panel.question_bank.practice.list';
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
     * Delete question
     */
    public function destroy($bankType, $id)
    {
        if ($bankType === 'mock') {
            $question = IeltsMockQuestionBank::findOrFail($id);
            $listRoute = 'panel.question_bank.mock.list';
        } else {
            $question = IeltsPracticeQuestionBank::findOrFail($id);
            $listRoute = 'panel.question_bank.practice.list';
        }
        
        $question->delete();
        
        return redirect()->route($listRoute)
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Question deleted successfully!',
                'status' => 'success'
            ]]);
    }
    
    // ============================================
    // GROUP METHODS (Question Groups System)
    // ============================================
    
    /**
     * List question groups
     */
    public function groupList(Request $request, $bankType)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }
        
        $query = \App\Models\IeltsQuestionGroup::where('bank_type', $bankType)
            ->with(['mockQuestions', 'practiceQuestions']);
        
        // Filters
        if ($request->filled('skill')) {
            $query->bySkill($request->skill);
        }
        
        if ($request->filled('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        $groups = $query->newest()->paginate(20);
        
        return view('design_1.panel.question_bank.group_list', compact('groups', 'bankType'));
    }
    
    /**
     * Show create group form
     */
    public function createGroup(Request $request, $bankType)
    {
        return view('design_1.panel.question_bank.group_create', compact('bankType'));
    }
    
    /**
     * Store new group with questions
     */
    public function storeGroup(Request $request)
    {
        $validated = $request->validate([
            'bank_type' => 'required|in:mock,practice',
            'skill' => 'required|in:listening,reading,writing,speaking',
            'title' => 'required|max:255',
            'description' => 'nullable',
            'passage' => 'nullable',
            'transcript' => 'nullable',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg',
            'task_image' => 'nullable|image',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'tags' => 'nullable|string',
            'questions' => 'required|array|min:1',
        ]);
        
        // Create group
        $group = \App\Models\IeltsQuestionGroup::create([
            'creator_id' => auth()->id(),
            'bank_type' => $validated['bank_type'],
            'skill' => $validated['skill'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'passage' => $validated['passage'] ?? null,
            'transcript' => $validated['transcript'] ?? null,
            'difficulty_level' => $validated['difficulty_level'],
            'tags' => $validated['tags'] ?? null,
        ]);
        
        // Handle file uploads
        if ($request->hasFile('audio_file')) {
            $path = $request->file('audio_file')->store('question_bank/audio', 'public');
            $group->update(['audio_file' => $path]);
        }
        
        if ($request->hasFile('task_image')) {
            $path = $request->file('task_image')->store('question_bank/images', 'public');
            $group->update(['task_image' => $path]);
        }
        
        // Create questions
        $model = $validated['bank_type'] === 'mock' 
            ? IeltsMockQuestionBank::class 
            : IeltsPracticeQuestionBank::class;
        
        foreach ($validated['questions'] as $index => $questionData) {
            $model::create([
                'group_id' => $group->id,
                'question_order' => $index + 1,
                'skill' => $validated['skill'],
                'question_type' => $questionData['question_type'],
                'question_text' => $questionData['question_text'],
                'correct_answer' => $questionData['correct_answer'],
                'answer_options' => $questionData['options'] ?? null,
                'points' => $questionData['points'] ?? 1,
                'difficulty_level' => $validated['difficulty_level'],
                'created_by' => auth()->id(),
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }
        
        return redirect()
            ->route('panel.question_bank.groups', [$validated['bank_type']])
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Group created with ' . count($validated['questions']) . ' questions!',
                'status' => 'success'
            ]]);
    }
    
    /**
     * Show edit group form
     */
    public function editGroup($bankType, $id)
    {
        $group = \App\Models\IeltsQuestionGroup::with(['mockQuestions', 'practiceQuestions'])->findOrFail($id);
        
        return view('design_1.panel.question_bank.group_edit', compact('group', 'bankType'));
    }
    
    /**
     * Update group
     */
    public function updateGroup(Request $request, $bankType, $id)
    {
        $group = \App\Models\IeltsQuestionGroup::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'passage' => 'nullable',
            'transcript' => 'nullable',
            'difficulty_level' => 'required',
            'tags' => 'nullable',
        ]);
        
        $group->update($validated);
        
        // Handle file uploads
        if ($request->hasFile('audio_file')) {
            $path = $request->file('audio_file')->store('question_bank/audio', 'public');
            $group->update(['audio_file' => $path]);
        }
        
        if ($request->hasFile('task_image')) {
            $path = $request->file('task_image')->store('question_bank/images', 'public');
            $group->update(['task_image' => $path]);
        }
        
        return redirect()
            ->route('panel.question_bank.groups', [$bankType])
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => 'Group updated successfully!',
                'status' => 'success'
            ]]);
    }
    
    /**
     * Delete group (cascade deletes questions)
     */
    public function destroyGroup($bankType, $id)
    {
        $group = \App\Models\IeltsQuestionGroup::with(['mockQuestions', 'practiceQuestions'])->findOrFail($id);
        $questionCount = $group->question_count;
        
        $group->delete(); // Cascade deletes all questions
        
        return redirect()
            ->route('panel.question_bank.groups', [$bankType])
            ->with(['toast' => [
                'title' => 'Success',
                'msg' => "Group deleted ({$questionCount} questions removed)",
                'status' => 'success'
            ]]);
    }
    
    // ============================================
    // IMPORT METHODS (Excel with Media Files)
    // ============================================
    
    /**
     * Show import form for specific skill
     */
    public function importForm($skill)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }
        
        // Validate skill
        if (!in_array($skill, ['listening', 'reading', 'writing', 'speaking'])) {
            abort(404);
        }
        
        return view('design_1.panel.question_bank.import', compact('skill'));
    }
    
    /**
     * Preview ZIP import data before saving
     */
    public function previewImport(Request $request)
    {
        $validated = $request->validate([
            'skill' => 'required|in:listening,reading,writing,speaking',
            'bank_type' => 'required|in:mock,practice',
            'zip_file' => 'required|file|mimes:zip|max:102400',
        ]);
        
        try {
            $zip = new ZipArchive;
            $tempPath = storage_path('app/temp/preview_' . uniqid());
            
            if (!File::exists($tempPath)) {
                File::makeDirectory($tempPath, 0755, true);
            }
            
            // Extract ZIP
            if ($zip->open($request->file('zip_file')->path()) === TRUE) {
                $zip->extractTo($tempPath);
                $zip->close();
                
                // Find Excel file
                $excelFile = $tempPath . '/questions.xlsx';
                
                if (!file_exists($excelFile)) {
                    File::deleteDirectory($tempPath);
                    throw new \Exception('questions.xlsx not found in ZIP');
                }
                
                // Parse Excel to array (don't import yet)
                $data = Excel::toArray(new \stdClass(), $excelFile);
                
                // Get first sheet
                $rows = $data[0] ?? [];
                
                if (count($rows) < 2) {
                    File::deleteDirectory($tempPath);
                    throw new \Exception('Excel file is empty');
                }
                
                // Headers in first row
                $headers = array_shift($rows);
                
                // Convert to preview data
                $previewData = [];
                foreach ($rows as $index => $row) {
                    if ($index >= 10) break; // Preview max 10 rows
                    
                    $question = [];
                    foreach ($headers as $colIndex => $header) {
                        $question[$header] = $row[$colIndex] ?? '';
                    }
                    $previewData[] = $question;
                }
                
                // Store temp path in session for later processing
                session([
                    'import_temp_path' => $tempPath,
                    'import_data' => $validated,
                    'import_headers' => $headers,
                    'import_total_rows' => count($rows),
                ]);
                
                return view('design_1.panel.question_bank.import_preview', [
                    'skill' => $validated['skill'],
                    'bankType' => $validated['bank_type'],
                    'headers' => $headers,
                    'previewData' => $previewData,
                    'totalRows' => count($rows),
                ]);
                
            } else {
                throw new \Exception('Failed to open ZIP file');
            }
            
        } catch (\Exception $e) {
            if (isset($tempPath) && File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }
            
            return redirect()
                ->back()
                ->with(['toast' => [
                    'title' => 'Preview Failed',
                    'msg' => $e->getMessage(),
                    'status' => 'error'
                ]]);
        }
    }
    
    /**
     * Process ZIP import with Excel + media files (called after preview confirmation)
     */
    public function processImport(Request $request)
    {
        // Check if called from preview
        if (!session()->has('import_temp_path')) {
            return redirect()
                ->route('panel.question_bank')
                ->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'Import session expired. Please upload again.',
                    'status' => 'error'
                ]]);
        }
        
        $tempPath = session('import_temp_path');
        $validated = session('import_data');
        
        try {
            $excelFile = $tempPath . '/questions.xlsx';
            
            if (!file_exists($excelFile)) {
                throw new \Exception('Excel file not found');
            }
            
            // Get appropriate importer for skill
            $importerClass = $this->getImporterClass($validated['skill']);
            $importer = new $importerClass(
                $validated['bank_type'],
                $tempPath,
                auth()->id()
            );
            
            // Import Excel
            Excel::import($importer, $excelFile);
            
            // Cleanup
            File::deleteDirectory($tempPath);
            session()->forget(['import_temp_path', 'import_data', 'import_headers', 'import_total_rows']);
            
            return redirect()
                ->route('panel.question_bank')
                ->with(['toast' => [
                    'title' => 'Success!',
                    'msg' => ucfirst($validated['skill']) . ' questions imported successfully!',
                    'status' => 'success'
                ]]);
                
        } catch (\Exception $e) {
            // Cleanup on error
            if (isset($tempPath) && File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }
            session()->forget(['import_temp_path', 'import_data', 'import_headers', 'import_total_rows']);
            
            return redirect()
                ->route('panel.question_bank')
                ->with(['toast' => [
                    'title' => 'Import Failed',
                    'msg' => $e->getMessage(),
                    'status' => 'error'
                ]]);
        }
    }
    
    /**
     * Cancel import preview
     */
    public function cancelImport()
    {
        $tempPath = session('import_temp_path');
        
        if ($tempPath && File::exists($tempPath)) {
            File::deleteDirectory($tempPath);
        }
        
        session()->forget(['import_temp_path', 'import_data', 'import_headers', 'import_total_rows']);
        
        return redirect()
            ->route('panel.question_bank')
            ->with(['toast' => [
                'title' => 'Import Cancelled',
                'msg' => 'Import has been cancelled',
                'status' => 'info'
            ]]);
    }
    
    /**
     * Process ZIP import with Excel + media files
     */
    public function OLD_processImport(Request $request)
    {
        $validated = $request->validate([
            'skill' => 'required|in:listening,reading,writing,speaking',
            'bank_type' => 'required|in:mock,practice',
            'zip_file' => 'required|file|mimes:zip|max:102400', // 100MB max
        ]);
        
        try {
            $zip = new ZipArchive;
            $tempPath = storage_path('app/temp/import_' . uniqid());
            
            // Create temp directory
            if (!File::exists($tempPath)) {
                File::makeDirectory($tempPath, 0755, true);
            }
            
            // Extract ZIP
            if ($zip->open($request->file('zip_file')->path()) === TRUE) {
                $zip->extractTo($tempPath);
                $zip->close();
                
                // Find Excel file
                $excelFile = $tempPath . '/questions.xlsx';
                
                if (!file_exists($excelFile)) {
                    throw new \Exception('questions.xlsx not found in ZIP file');
                }
                
                // Get appropriate importer for skill
                $importerClass = $this->getImporterClass($validated['skill']);
                $importer = new $importerClass(
                    $validated['bank_type'],
                    $tempPath,
                    auth()->id()
                );
                
                // Import Excel
                Excel::import($importer, $excelFile);
                
                // Cleanup temp folder
                File::deleteDirectory($tempPath);
                
                return redirect()
                    ->route('panel.question_bank')
                    ->with(['toast' => [
                        'title' => 'Success!',
                        'msg' => ucfirst($validated['skill']) . ' questions imported successfully!',
                        'status' => 'success'
                    ]]);
                    
            } else {
                throw new \Exception('Failed to open ZIP file');
            }
            
        } catch (\Exception $e) {
            // Cleanup on error
            if (isset($tempPath) && File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }
            
            return redirect()
                ->back()
                ->with(['toast' => [
                    'title' => 'Import Failed',
                    'msg' => $e->getMessage(),
                    'status' => 'error'
                ]]);
        }
    }
    
    /**
     * Download Excel template for specific skill
     */
    public function downloadTemplate($skill)
    {
        if (!in_array($skill, ['listening', 'reading', 'writing', 'speaking'])) {
            abort(404);
        }
        
        // Generate template based on skill
        $templateData = $this->getTemplateStructure($skill);
        
        return Excel::download(
            new \App\Exports\QuestionTemplateExport($skill, $templateData),
            "{$skill}_template.xlsx"
        );
    }
    
    /**
     * Get importer class for skill
     */
    protected function getImporterClass($skill)
    {
        $importers = [
            'listening' => \App\Imports\ListeningQuestionImport::class,
            'reading' => \App\Imports\ReadingQuestionImport::class,
            'writing' => \App\Imports\WritingQuestionImport::class,
            'speaking' => \App\Imports\SpeakingQuestionImport::class,
        ];
        
        return $importers[$skill];
    }
    
    /**
     * Get template structure for Excel export
     */
    protected function getTemplateStructure($skill)
    {
        $templates = [
            'listening' => [
                'headers' => ['Group Name', 'Question #', 'Question Type', 'Question Text', 'Audio File', 'Timestamp Start', 'Timestamp End', 'Correct Answer', 'Difficulty Level', 'Tags', 'Instruction', 'Passage Text', 'Option A', 'Option B', 'Option C', 'Option D'],
                'sample_row' => ['Part 1 - Hotel Conversation', 1, 'multiple_choice', 'What is the main topic?', 'Part1.mp3', '00:15', '00:45', 'Option A', 'intermediate', 'conversation, main_idea', 'Choose the correct answer', 'Transcript...', 'Option A', 'Option B', 'Option C', 'Option D'],
            ],
            'reading' => [
                'headers' => ['Group Name', 'Question #', 'Question Type', 'Question Text', 'Passage Text', 'Image File', 'Correct Answer', 'Difficulty Level', 'Tags', 'Instruction', 'Option A', 'Option B', 'Option C', 'Option D'],
                'sample_row' => ['Climate Change Passage', 1, 'multiple_choice', 'According to the passage...', 'Long passage text here...', '', 'Option B', 'intermediate', 'comprehension, detail', 'Choose the correct answer', 'Option A', 'Option B', 'Option C', 'Option D'],
            ],
            'writing' => [
                'headers' => ['Group Name', 'Question #', 'Task Type', 'Question Text', 'Task Image', 'Sample Answer', 'Points', 'Difficulty Level', 'Tags', 'Instruction'],
                'sample_row' => ['Academic Writing Set 1', 1, 'Task 1', 'The graph shows...', 'graph1.png', 'Model answer here...', 5.0, 'intermediate', 'graph_description, trend', 'Summarise the information'],
            ],
            'speaking' => [
                'headers' => ['Group Name', 'Question #', 'Part', 'Question Text', 'Cue Card Image', 'Sample Answer', 'Points', 'Difficulty Level', 'Tags', 'Instruction'],
                'sample_row' => ['Speaking Part 1 - Hometown', 1, 'Part 1', 'What is your hometown?', '', 'I am from Hanoi...', 3.0, 'beginner', 'introduction, hometown', 'Answer the question'],
            ],
        ];
        
        return $templates[$skill];
    }
}

