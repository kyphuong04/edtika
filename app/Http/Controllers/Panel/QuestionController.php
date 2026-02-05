<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsQuestionGroup;
use App\Models\IeltsMockQuestionBank;
use App\Models\IeltsPracticeQuestionBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class QuestionController extends Controller
{
    // Store questions
    public function store(Request $request, $groupId)
    {
        Log::info('Question Store - Request Data', ['all' => $request->all(), 'groupId' => $groupId]);
        
        $group = IeltsQuestionGroup::findOrFail($groupId);
        
        Log::info('Question Store - Group Info', ['group' => $group->toArray()]);
        
        // Check if batch creation (multiple questions)
        if ($request->has('questions') && is_array($request->input('questions'))) {
            return $this->storeBatch($request, $group);
        }
        
        // Single question creation
        return $this->storeSingle($request, $group);
    }
    
   // multiple question store
    private function storeBatch(Request $request, $group)
    {
        $questions = $request->input('questions');
        
        Log::info('Batch Store - Received questions:', ['questions' => $questions, 'count' => count($questions)]);
        Log::info('Batch Store - First question table_structure:', ['table_structure' => $questions[0]['table_structure'] ?? 'NOT FOUND']);
        
        // Validate - question_number và correct_answer chỉ required cho question rows, không required cho header rows
        $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.question_type' => 'required|string',
            'questions.*.row_type' => 'nullable|string|in:question,header',
            'questions.*.question_number' => 'nullable|integer',
            'questions.*.question_text' => 'required|string|min:3',
        ]);
        
        // Custom validation: nếu row_type = 'question' thì phải có question_number và correct_answer
        $customErrors = [];
        foreach ($questions as $index => $questionData) {
            $rowType = $questionData['row_type'] ?? 'question';
            Log::info("Validating question {$index}", ['row_type' => $rowType, 'has_qnum' => isset($questionData['question_number']), 'has_answer' => isset($questionData['correct_answer'])]);
            
            if ($rowType === 'question') {
                if (empty($questionData['question_number'])) {
                    $customErrors["questions.{$index}.question_number"] = "Question number is required for question rows.";
                }
                if (empty($questionData['correct_answer'])) {
                    $customErrors["questions.{$index}.correct_answer"] = "Correct answer is required for question rows.";
                }
            }
        }
        
        if (!empty($customErrors)) {
            Log::warning('Custom validation failed', ['errors' => $customErrors]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $customErrors
            ], 422);
        }
        
        // Determine which table to use
        Log::info('=== CRITICAL: Determining Model ===', [
            'group_id' => $group->id,
            'group_bank_type' => $group->bank_type,
            'will_use_model' => $group->bank_type === 'mock' ? 'IeltsMockQuestionBank' : 'IeltsPracticeQuestionBank'
        ]);
        
        $model = $group->bank_type === 'mock' 
            ? IeltsMockQuestionBank::class 
            : IeltsPracticeQuestionBank::class;
        
        Log::info('Model class determined:', ['model' => $model]);
        
        $savedQuestions = [];
        $errors = [];
        
        try {
            foreach ($questions as $index => $questionData) {
                try {
                    // Prepare data
                    $dataToCreate = $this->prepareQuestionData($questionData, $group, $model);
                    
                    Log::info("=== Creating Question {$index} ===", [
                        'model' => $model,
                        'data' => $dataToCreate
                    ]);
                    
                    // Create question
                    $question = $model::create($dataToCreate);
                    
                    Log::info("✅ Question created successfully", [
                        'id' => $question->id,
                        'question_number' => $question->question_order ?? $question->question_number ?? 'N/A',
                        'type' => $question->question_type
                    ]);
                    
                    $savedQuestions[] = $question;
                    
                } catch (\Exception $e) {
                    Log::error("❌ Question Batch Error - Index {$index}: " . $e->getMessage());
                    Log::error("Error trace: " . $e->getTraceAsString());
                    $errors[] = "Question " . ($questionData['question_number'] ?? $index+1) . ": " . $e->getMessage();
                }
            }
            
            if (count($savedQuestions) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => count($savedQuestions) . ' question(s) saved successfully!',
                    'saved_count' => count($savedQuestions),
                    'error_count' => count($errors),
                    'errors' => $errors
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No questions were saved',
                    'errors' => $errors
                ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Question Batch Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving questions: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store single question
     */
    private function storeSingle(Request $request, $group)
    {
        // Basic validation
        $request->validate([
            'question_type' => 'required|string',
            'question_number' => 'required|integer',
        ]);
        
        // Determine which table to use
        $model = $group->bank_type === 'mock' 
            ? IeltsMockQuestionBank::class 
            : IeltsPracticeQuestionBank::class;
        
        Log::info('Question Store - Model', ['model' => $model]);
        
        // Prepare question data from type-specific fields
        $questionData = $request->except(['_token', 'question_type', 'question_number', 'correct_answer', 'alternative_answers', 'word_limit', 'marks']);
        
        Log::info('Question Store - Prepared Data', ['questionData' => $questionData]);
        
        // Create question
        try {
            $dataToCreate = $this->prepareQuestionData($request->all(), $group, $model);
            
            Log::info('Question Store - Data to Create', $dataToCreate);
            
            $question = $model::create($dataToCreate);
            
            // Return based on request type
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'question' => $question,
                    'message' => 'Question created successfully!'
                ]);
            }
            
            return redirect()->route('panel.question-groups.show', $group->id)
                ->with('success', 'Question added successfully!');
                
        } catch (\Exception $e) {
            Log::error('Question create error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating question: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()
                ->with('error', 'Error creating question: ' . $e->getMessage());
        }
    }
    
    // Prepare question data for creation
    private function prepareQuestionData($requestData, $group, $model)
    {
        Log::info('prepareQuestionData - Input:', ['data' => $requestData]);
        
        // Extract known fields - these should NOT go into question_data JSON
        $knownFields = ['question_type', 'question_number', 'correct_answer', 'alternative_answers', 
                        'word_limit', 'marks', 'question_text', 'question_type_label', 'explanation', '_token', 
                        'row_type', 'columns', 'row_context', 'table_structure'];
        
        // Extract table_structure if present (for Table Completion questions)
        $tableStructure = null;
        if (isset($requestData['table_structure'])) {
            $tableStructure = is_array($requestData['table_structure']) 
                ? $requestData['table_structure'] 
                : json_decode($requestData['table_structure'], true);
        }
        
        // Extract options from fields like 'options[A]', 'options[B]', etc.
        $answerOptions = [];
        $extraQuestionData = [];
        
        foreach ($requestData as $key => $value) {
            if (preg_match('/^options\[([A-Z]+)\]$/', $key, $matches)) {
                $answerOptions[$matches[1]] = $value;
                $knownFields[] = $key; // Mark as known so it's not in question_data
            }
            // Extract question_data[...] format from frontend
            if (preg_match('/^question_data\[(.+)\]$/', $key, $matches)) {
                $extraQuestionData[$matches[1]] = $value;
                $knownFields[] = $key;
            }
        }
        
        // Get extra data for question_data JSON field
        $questionData = array_diff_key($requestData, array_flip($knownFields));
        
        // Merge extraQuestionData into questionData
        $questionData = array_merge($questionData, $extraQuestionData);
        
        // Add row_type to question_data if it exists (for flexible table/note structures)
        if (isset($requestData['row_type'])) {
            $questionData['row_type'] = $requestData['row_type'];
        }
        
        // Add columns and row_context to question_data for table completion
        if (isset($requestData['columns'])) {
            $questionData['columns'] = $requestData['columns'];
        }
        if (isset($requestData['row_context'])) {
            $questionData['row_context'] = $requestData['row_context'];
        }
        
        // Handle correct_answer - can be array or string
        // For header rows, correct_answer is not required
        $rowType = $requestData['row_type'] ?? 'question';
        $correctAnswer = $requestData['correct_answer'] ?? '';
        
        if ($rowType === 'header') {
            // Headers don't have answers - use empty string instead of null to avoid DB errors
            $correctAnswer = '';
        } else {
            if (is_array($correctAnswer)) {
                $correctAnswer = json_encode($correctAnswer);
            }
        }
        
        $dataToCreate = [
            'group_id' => $group->id,
            'skill' => $group->skill,
            'section_type' => $group->section ? $group->section->section_type : null,
            'question_type' => $requestData['question_type'] ?? null,
            'question_text' => $requestData['question_text'] ?? '',
            'question_data' => json_encode($questionData),
            'table_structure' => $tableStructure, // Don't json_encode - Model will handle it with cast
            'answer_options' => !empty($answerOptions) ? json_encode($answerOptions) : null,
            'correct_answer' => $correctAnswer, // Now properly handles arrays and headers
            'explanation' => $requestData['explanation'] ?? null, // Sample answer for Writing/Speaking
            'alternative_answers' => isset($requestData['alternative_answers']) 
                ? (is_array($requestData['alternative_answers']) 
                    ? json_encode($requestData['alternative_answers']) 
                    : $requestData['alternative_answers'])
                : null,
            'word_limit' => $requestData['word_limit'] ?? null,
            'marks' => $requestData['marks'] ?? ($rowType === 'header' ? 0 : 1.0),
        ];
        
        Log::info('prepareQuestionData - Output:', ['dataToCreate' => $dataToCreate]);
        
        // Add question_order only if column exists
        if (Schema::hasColumn($model::make()->getTable(), 'question_order')) {
            $dataToCreate['question_order'] = $requestData['question_number'] ?? null;
        }
        
        return $dataToCreate;
    }
    
    // update question
    public function update(Request $request, $questionId)
    {
        $request->validate([
            'question' => 'required|string',
            'question_type' => 'required|string',
        ]);
        
        // Find in either table
        $question = IeltsMockQuestionBank::find($questionId) 
            ?? IeltsPracticeQuestionBank::findOrFail($questionId);
        
        $questionData = $request->input('question_data', []);
        if ($request->has('options')) {
            $questionData['options'] = $request->input('options');
        }
        
        // Handle correct_answer - can be array or string
        $correctAnswer = $request->input('correct_answer');
        if (is_array($correctAnswer)) {
            $correctAnswer = json_encode($correctAnswer);
        }
        
        $question->update([
            'question_text' => $request->input('question'),
            'question_type' => $request->input('question_type'),
            'question_data' => is_array($questionData) ? json_encode($questionData) : $questionData,
            'correct_answer' => $correctAnswer,
            'explanation' => $request->input('explanation'),
            'alternative_answers' => $request->input('alternative_answers') 
                ? (is_array($request->input('alternative_answers')) 
                    ? json_encode($request->input('alternative_answers'))
                    : $request->input('alternative_answers'))
                : null,
            'word_limit' => $request->input('word_limit'),
            'marks' => $request->input('marks', 1.0),
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'question' => $question,
                'message' => 'Question updated successfully!'
            ]);
        }
        
        return back()->with('success', 'Question updated successfully!');
    }
    
    // delete question
    public function destroy(Request $request, $questionId)
    {
        $question = IeltsMockQuestionBank::find($questionId) 
            ?? IeltsPracticeQuestionBank::findOrFail($questionId);
        
        $groupId = $question->group_id;
        $question->delete();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully!'
            ]);
        }
        
        return redirect()->route('panel.question-groups.show', $groupId)
            ->with('success', 'Question deleted!');
    }
    
    // question type form
    public function getQuestionTypeForm($type)
    {
        $viewPath = 'design_1.panel.questions.types.' . $type;
        
        if (view()->exists($viewPath)) {
            return view($viewPath)->render();
        }
        
        return view('design_1.panel.questions.types.default')->render();
    }
    
    // question create form
    public function create(Request $request, $groupId)
    {
        $group = IeltsQuestionGroup::findOrFail($groupId);
        
        return view('design_1.panel.questions.create', compact('group'));
    }
    
    /**
     * Show the form for editing the specified question.
     */
    public function edit(Request $request, $questionId)
    {
        // Find question in either table
        $question = IeltsMockQuestionBank::find($questionId) 
            ?? IeltsPracticeQuestionBank::find($questionId);
        
        if (!$question) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Question not found'
                ], 404);
            }
            return back()->with('error', 'Question not found');
        }
        
        // Get the group
        $group = IeltsQuestionGroup::findOrFail($question->group_id);
        
        // Decode question_data if it's JSON
        $questionData = [];
        if ($question->question_data) {
            $decoded = is_string($question->question_data) 
                ? json_decode($question->question_data, true) 
                : $question->question_data;
            if (is_array($decoded)) {
                $questionData = $decoded;
            }
        }
        
        return view('design_1.panel.questions.edit', compact('question', 'group', 'questionData'));
    }
}
