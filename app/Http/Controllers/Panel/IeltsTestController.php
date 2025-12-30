<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsTest;
use App\Models\IeltsTestAttempt;
use App\Models\IeltsTestAnswer;
use App\Models\IeltsTestSection;
use App\QuizzesResult;
use Illuminate\Http\Request;

class IeltsTestController extends Controller
{
    /**
     * Display tests for students
     */
    public function index()
    {
        $authUser = auth()->user();
        
        $mockTests = IeltsTest::with('sections')
            ->mockTests()
            ->published()
            ->active()
            ->get();
        
        $practiceTests = IeltsTest::with('sections', 'practiceCategory')
            ->practiceTests()
            ->published()
            ->active()
            ->get();
        
        // Get user stats for each test
        foreach ($mockTests as $test) {
            $test->user_attempts = $test->getUserAttemptsCount($authUser->id);
            $test->best_attempt = $test->getUserBestAttempt($authUser->id);
            $test->can_take = $test->canUserTake($authUser->id);
            // Check enrollment status
            $test->user_enrolled = $test->require_enrollment && $test->webinar_id
                ? \App\Models\Sale::where('webinar_id', $test->webinar_id)
                    ->where('buyer_id', $authUser->id)
                    ->where('type', 'webinar')
                    ->where('status', 'success')
                    ->exists()
                : false;
        }
        
        foreach ($practiceTests as $test) {
            $test->user_attempts = $test->getUserAttemptsCount($authUser->id);
            $test->best_attempt = $test->getUserBestAttempt($authUser->id);
            $test->can_take = $test->canUserTake($authUser->id);
            $test->user_enrolled = $test->require_enrollment && $test->webinar_id
                ? \App\Models\Sale::where('webinar_id', $test->webinar_id)
                    ->where('buyer_id', $authUser->id)
                    ->where('type', 'webinar')
                    ->where('status', 'success')
                    ->exists()
                : false;
        }
        
        $data = [
            'pageTitle' => 'IELTS Tests',
            'mockTests' => $mockTests,
            'practiceTests' => $practiceTests,
        ];
        
        return view('design_1.panel.ielts_tests.index', $data);
    }
    
    /**
     * Display only mock tests
     */
    public function indexMock()
    {
        $authUser = auth()->user();
        
        $mockTests = IeltsTest::with('sections')
            ->mockTests()
            ->published()
            ->active()
            ->get();
        
        foreach ($mockTests as $test) {
            $test->user_attempts = $test->getUserAttemptsCount($authUser->id);
            $test->best_attempt = $test->getUserBestAttempt($authUser->id);
            $test->can_take = $test->canUserTake($authUser->id);
        }
        
        $data = [
            'pageTitle' => 'Mock Tests',
            'mockTests' => $mockTests,
            'practiceTests' => collect([]),
        ];
        
        return view('design_1.panel.ielts_tests.index', $data);
    }
    
    /**
     * Display only practice tests
     */
    public function indexPractice()
    {
        $authUser = auth()->user();
        
        $practiceTests = IeltsTest::with('sections', 'practiceCategory')
            ->practiceTests()
            ->published()
            ->active()
            ->get();
        
        foreach ($practiceTests as $test) {
            $test->user_attempts = $test->getUserAttemptsCount($authUser->id);
            $test->best_attempt = $test->getUserBestAttempt($authUser->id);
            $test->can_take = $test->canUserTake($authUser->id);
        }
        
        $data = [
            'pageTitle' => 'Practice Tests',
            'mockTests' => collect([]),
            'practiceTests' => $practiceTests,
        ];
        
        return view('design_1.panel.ielts_tests.index', $data);
    }
    
    /**
     * Show test details
     */
    public function show($id)
    {
        $test = IeltsTest::with('sections')->findOrFail($id);
        $authUser = auth()->user();
        
        if (!$test->is_active || $test->status !== 'published') {
            abort(404);
        }
        
        $canTake = $test->canUserTake($authUser->id);
        $attempts = IeltsTestAttempt::where('test_id', $test->id)
            ->where('user_id', $authUser->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $data = [
            'pageTitle' => $test->title,
            'test' => $test,
            'canTake' => $canTake,
            'attempts' => $attempts,
        ];
        
        return view('design_1.panel.ielts_tests.show', $data);
    }
    
    /**
     * Start new test attempt
     */
    public function startTest(Request $request, $id)
    {
        $test = IeltsTest::with('sections')->findOrFail($id);
        $authUser = auth()->user();
        
        if (!$test->canUserTake($authUser->id)) {
            return back()->with(['toast' => [
                'title' => 'Error',
                'msg' => 'You cannot take this test',
                'status' => 'error'
            ]]);
        }
        
        // Get attempt number
        $attemptNumber = IeltsTestAttempt::where('test_id', $test->id)
            ->where('user_id', $authUser->id)
            ->count() + 1;
        
        // Calculate total questions
        $totalQuestions = 0;
        if ($test->has_listening) $totalQuestions += 40;
        if ($test->has_reading) $totalQuestions += 40;
        if ($test->has_writing) $totalQuestions += 2;
        if ($test->has_speaking) $totalQuestions += 10; // Approximate
        
        // Create new attempt
        $attempt = IeltsTestAttempt::create([
            'test_id' => $test->id,
            'user_id' => $authUser->id,
            'attempt_number' => $attemptNumber,
            'status' => 'in_progress',
            'current_skill' => $test->has_listening ? 'listening' : 
                              ($test->has_reading ? 'reading' : 
                              ($test->has_writing ? 'writing' : 'speaking')),
            'started_at' => time(),
            'total_questions' => $totalQuestions,
            'remaining_time_seconds' => $test->total_duration * 60,
            'updated_at' => time(),
        ]);
        
        // Get first section
        $firstSection = $test->sections()->orderBy('sort_order')->first();
        if ($firstSection) {
            $attempt->current_section_id = $firstSection->id;
            $attempt->save();
        }
        
        return redirect()->route('panel.ielts_tests.take', $attempt->id);
    }
    
    /**
     * Take test interface
     */
    public function takeTest($attemptId)
    {
        $attempt = IeltsTestAttempt::with(['test.sections.questions', 'answers', 'currentSection'])
            ->findOrFail($attemptId);
        
        $authUser = auth()->user();
        
        // Check ownership
        if ($attempt->user_id !== $authUser->id) {
            abort(403);
        }
        
        // Check if already completed
        if ($attempt->status === 'completed') {
            return redirect()->route('panel.ielts_tests.results', $attemptId);
        }
        
        // Check time expiry
        if ($attempt->hasExpired()) {
            $this->autoSubmitTest($attempt);
            return redirect()->route('panel.ielts_tests.results', $attemptId);
        }
        
        $currentSection = $attempt->currentSection;
        $questions = $currentSection->questions;
        
        // Get existing answers
        $userAnswers = $attempt->answers()->pluck('answer_text', 'question_id')->toArray();
        
        $data = [
            'pageTitle' => 'Taking: ' . $attempt->test->title,
            'attempt' => $attempt,
            'test' => $attempt->test,
            'currentSection' => $currentSection,
            'questions' => $questions,
            'userAnswers' => $userAnswers,
        ];
        
        return view('design_1.panel.ielts_tests.take', $data);
    }
    
    /**
     * Save answer (AJAX)
     */
    public function saveAnswer(Request $request, $attemptId)
    {
        $attempt = IeltsTestAttempt::findOrFail($attemptId);
        
        if ($attempt->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $questionId = $request->input('question_id');
        $answerText = $request->input('answer_text');
        $answerOptions = $request->input('answer_options');
        
        IeltsTestAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
            ],
            [
                'answer_text' => $answerText,
                'answer_options' => $answerOptions ? json_encode($answerOptions) : null,
                'answered_at' => time(),
                'modified_at' => time(),
            ]
        );
        
        // Update progress
        $attempt->updateProgress();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Answer saved'
        ]);
    }
    
    /**
     * Finish current section and move to next
     */
    public function finishSection(Request $request, $attemptId)
    {
        $attempt = IeltsTestAttempt::with('test.sections')->findOrFail($attemptId);
        
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        $currentSkill = $attempt->current_skill;
        
        // Mark current skill as completed
        $attempt->completeSection($currentSkill);
        
        // Get next section
        $nextSection = $attempt->getNextSection();
        
        if ($nextSection) {
            $attempt->current_skill = $nextSection->skill;
            $attempt->current_section_id = $nextSection->id;
            $attempt->save();
            
            return response()->json([
                'status' => 'success',
                'next_section' => $nextSection->skill,
                'redirect' => route('panel.ielts_tests.take', $attempt->id)
            ]);
        } else {
            // All sections completed, submit test
            return response()->json([
                'status' => 'completed',
                'redirect' => route('panel.ielts_tests.submit_confirm', $attempt->id)
            ]);
        }
    }
    
    /**
     * Submit test
     */
    public function submitTest($attemptId)
    {
        $attempt = IeltsTestAttempt::with(['test', 'answers.question'])->findOrFail($attemptId);
        
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Auto-grade Listening & Reading
        $this->autoGradeListening($attempt);
        $this->autoGradeReading($attempt);
        
        // Calculate overall band (for L & R only, W & S need manual grading)
        $this->calculateBandScores($attempt);
        
        // Mark as completed
        $attempt->update([
            'status' => 'completed',
            'completed_at' => time(),
            'updated_at' => time(),
        ]);
        
        return redirect()->route('panel.ielts_tests.results', $attempt->id);
    }
    
    /**
     * Auto-grade listening section
     */
    private function autoGradeListening($attempt)
    {
        $listeningAnswers = $attempt->answers()
            ->whereHas('question.section', function($q) {
                $q->where('skill', 'listening');
            })
            ->with('question')
            ->get();
        
        $correctCount = 0;
        $totalCount = $listeningAnswers->count();
        
        foreach ($listeningAnswers as $answer) {
            if ($answer->question->auto_gradable) {
                $answer->autoGrade();
                if ($answer->is_correct) {
                    $correctCount++;
                }
            }
        }
        
        $attempt->listening_score = $correctCount;
        $attempt->save();
    }
    
    /**
     * Auto-grade reading section
     */
    private function autoGradeReading($attempt)
    {
        $readingAnswers = $attempt->answers()
            ->whereHas('question.section', function($q) {
                $q->where('skill', 'reading');
            })
            ->with('question')
            ->get();
        
        $correctCount = 0;
        $totalCount = $readingAnswers->count();
        
        foreach ($readingAnswers as $answer) {
            if ($answer->question->auto_gradable) {
                $answer->autoGrade();
                if ($answer->is_correct) {
                    $correctCount++;
                }
            }
        }
        
        $attempt->reading_score = $correctCount;
        $attempt->save();
    }
    
    /**
     * Calculate band scores using Cambridge conversion tables
     */
    private function calculateBandScores($attempt)
    {
        // Listening band conversion (simple approximation)
        $listeningBand = $this->scoreToBand($attempt->listening_score, 'listening');
        
        // Reading band conversion
        $readingBand = $this->scoreToBand($attempt->reading_score, 'reading');
        
        // Average (will be updated after W/S manual grading)
        if ($listeningBand && $readingBand) {
            $overallBand = ($listeningBand + $readingBand) / 2;
            $attempt->overall_band = round($overallBand * 2) / 2; // Round to nearest 0.5
        }
        
        $attempt->save();
    }
    
    /**
     * Convert raw score to IELTS band
     */
    private function scoreToBand($score, $skill)
    {
        if ($score === null) return null;
        
        // Cambridge conversion table (approximation)
        $conversion = [
            40 => 9.0, 39 => 8.5, 38 => 8.5, 37 => 8.0, 36 => 8.0,
            35 => 7.5, 34 => 7.5, 33 => 7.0, 32 => 7.0, 31 => 6.5,
            30 => 6.5, 29 => 6.5, 28 => 6.0, 27 => 6.0, 26 => 6.0,
            25 => 5.5, 24 => 5.5, 23 => 5.5, 22 => 5.0, 21 => 5.0,
            20 => 5.0, 19 => 5.0, 18 => 4.5, 17 => 4.5, 16 => 4.5,
            15 => 4.0, 14 => 4.0, 13 => 4.0, 12 => 3.5, 11 => 3.5,
            10 => 3.0, 9 => 3.0, 8 => 2.5, 7 => 2.5, 6 => 2.0,
            5 => 2.0, 4 => 1.5, 3 => 1.0, 2 => 1.0, 1 => 0.5, 0 => 0.0,
        ];
        
        return $conversion[$score] ?? 0.0;
    }
    
    /**
     * Auto-submit test when time expires
     */
    private function autoSubmitTest($attempt)
    {
        $attempt->update([
            'status' => 'completed',
            'completed_at' => time(),
        ]);
        
        $this->autoGradeListening($attempt);
        $this->autoGradeReading($attempt);
        $this->calculateBandScores($attempt);
    }
    
    /**
     * View results
     */
    public function results($attemptId)
    {
        $attempt = IeltsTestAttempt::with(['test', 'answers.question.section'])
            ->findOrFail($attemptId);
        
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        $data = [
            'pageTitle' => 'Test Results',
            'attempt' => $attempt,
            'test' => $attempt->test,
        ];
        
        return view('design_1.panel.ielts_tests.results', $data);
    }
    
    /**
     * Review answers
     */
    public function reviewAnswers($attemptId)
    {
        $attempt = IeltsTestAttempt::with(['test.sections.questions', 'answers'])
            ->findOrFail($attemptId);
        
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        $data = [
            'pageTitle' => 'Review Answers',
            'attempt' => $attempt,
            'test' => $attempt->test,
        ];
        
        return view('design_1.panel.ielts_tests.review', $data);
    }
}
