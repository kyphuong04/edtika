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
        
        // Validate
        $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.question_type' => 'required|string',
            'questions.*.question_number' => 'required|integer',
        ]);
        
        // Determine which table to use
        $model = $group->bank_type === 'mock' 
            ? IeltsMockQuestionBank::class 
            : IeltsPracticeQuestionBank::class;
        
        $savedQuestions = [];
        $errors = [];
        
        try {
            foreach ($questions as $index => $questionData) {
                try {
                    // Prepare data
                    $dataToCreate = $this->prepareQuestionData($questionData, $group, $model);
                    
                    // Create question
                    $question = $model::create($dataToCreate);
                    $savedQuestions[] = $question;
                    
                } catch (\Exception $e) {
                    Log::error("Question Batch Error - Index {$index}: " . $e->getMessage());
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
        // Extract known fields
        $knownFields = ['question_type', 'question_number', 'correct_answer', 'alternative_answers', 
                        'word_limit', 'marks', 'question_text', 'question_type_label', 'explanation', '_token'];
        
        // Extract options from fields like 'options[A]', 'options[B]', etc.
        $answerOptions = [];
        foreach ($requestData as $key => $value) {
            if (preg_match('/^options\[([A-Z]+)\]$/', $key, $matches)) {
                $answerOptions[$matches[1]] = $value;
                $knownFields[] = $key; // Mark as known so it's not in question_data
            }
        }
        
        // Get extra data for question_data JSON field
        $questionData = array_diff_key($requestData, array_flip($knownFields));
        
        // Handle correct_answer - can be array or string
        $correctAnswer = $requestData['correct_answer'] ?? '';
        if (is_array($correctAnswer)) {
            $correctAnswer = json_encode($correctAnswer);
        }
        
        $dataToCreate = [
            'group_id' => $group->id,
            'skill' => $group->skill,
            'section_type' => $group->section ? $group->section->section_type : null,
            'question_type' => $requestData['question_type'] ?? null,
            'question_text' => $requestData['question_text'] ?? '',
            'question_data' => json_encode($questionData),
            'answer_options' => !empty($answerOptions) ? json_encode($answerOptions) : null,
            'correct_answer' => $correctAnswer, // Now properly handles arrays
            'explanation' => $requestData['explanation'] ?? null, // Sample answer for Writing/Speaking
            'alternative_answers' => isset($requestData['alternative_answers']) 
                ? (is_array($requestData['alternative_answers']) 
                    ? json_encode($requestData['alternative_answers']) 
                    : $requestData['alternative_answers'])
                : null,
            'word_limit' => $requestData['word_limit'] ?? null,
            'marks' => $requestData['marks'] ?? 1.0,
        ];
        
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
