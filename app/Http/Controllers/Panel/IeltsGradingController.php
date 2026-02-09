<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsTest;
use App\Models\IeltsTestAttempt;
use App\Models\IeltsTestAnswer;
use App\Models\IeltsTestSection;
use Illuminate\Http\Request;

/**
 * Handles grading for IELTS Speaking and Writing sections.
 * These require manual evaluation by teachers.
 */
class IeltsGradingController extends Controller
{
    /**
     * Show the list of attempts waiting to be graded.
     */
    public function index(Request $request)
    {
        $authUser = auth()->user();
        
        // Only teachers can grade
        if (!$authUser->isTeacher() && !$authUser->isAdmin() && !$authUser->isOrganization()) {
            abort(403);
        }
        
        $status = $request->get('status', 'pending');
        $testType = $request->get('type', 'all');
        $skill = $request->get('skill', 'all');
        
        $query = IeltsTestAttempt::with(['test', 'user', 'answers.question.section'])
            ->where('status', 'completed');
        
        // Filter by mock or practice
        if ($testType === 'mock') {
            $query->whereHas('test', fn($q) => $q->where('test_type', 'mock'));
        } elseif ($testType === 'practice') {
            $query->whereHas('test', fn($q) => $q->where('test_type', 'practice'));
        }
        
        // Show only ungraded or already graded
        if ($status === 'pending') {
            $query->where(function($q) {
                $q->where(function($sub) {
                    $sub->whereHas('test', fn($t) => $t->where('has_writing', true))
                        ->whereNull('writing_band');
                })->orWhere(function($sub) {
                    $sub->whereHas('test', fn($t) => $t->where('has_speaking', true))
                        ->whereNull('speaking_band');
                });
            });
        } elseif ($status === 'graded') {
            $query->whereNotNull('overall_band');
        }
        
        // Filter by skill type
        if ($skill === 'writing') {
            $query->whereHas('test', fn($q) => $q->where('has_writing', true))
                  ->whereNull('writing_band');
        } elseif ($skill === 'speaking') {
            $query->whereHas('test', fn($q) => $q->where('has_speaking', true))
                  ->whereNull('speaking_band');
        }
        
        $attempts = $query->orderBy('completed_at', 'desc')->paginate(20);
        
        // Count how many are waiting
        $pendingWriting = IeltsTestAttempt::where('status', 'completed')
            ->whereHas('test', fn($q) => $q->where('has_writing', true))
            ->whereNull('writing_band')
            ->count();
            
        $pendingSpeaking = IeltsTestAttempt::where('status', 'completed')
            ->whereHas('test', fn($q) => $q->where('has_speaking', true))
            ->whereNull('speaking_band')
            ->count();
        
        return view('design_1.panel.ielts_grading.index', [
            'pageTitle' => trans('update.ielts_grading'),
            'attempts' => $attempts,
            'pendingWriting' => $pendingWriting,
            'pendingSpeaking' => $pendingSpeaking,
            'currentStatus' => $status,
            'currentType' => $testType,
            'currentSkill' => $skill,
        ]);
    }
    
    /**
     * Open grading form for one student's attempt.
     */
    public function grade($attemptId, Request $request)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isTeacher() && !$authUser->isAdmin() && !$authUser->isOrganization()) {
            abort(403);
        }
        
        $attempt = IeltsTestAttempt::with([
            'test.sections.questions',
            'user',
            'answers.question.section'
        ])->findOrFail($attemptId);
        
        // Default: grade writing first, then speaking
        $skill = $request->get('skill');
        if (!$skill) {
            if ($attempt->test->has_writing && !$attempt->writing_band) {
                $skill = 'writing';
            } elseif ($attempt->test->has_speaking && !$attempt->speaking_band) {
                $skill = 'speaking';
            } else {
                $skill = 'writing';
            }
        }
        
        $answers = $attempt->answers()
            ->whereHas('question.section', fn($q) => $q->where('skill', $skill))
            ->with('question.section')
            ->get();
        
        $sections = $attempt->test->sections()
            ->where('skill', $skill)
            ->orderBy('sort_order')
            ->get();
        
        return view('design_1.panel.ielts_grading.grade', [
            'pageTitle' => trans('update.grade') . ' ' . trans('update.' . $skill) . ' - ' . $attempt->user->full_name,
            'attempt' => $attempt,
            'test' => $attempt->test,
            'user' => $attempt->user,
            'skill' => $skill,
            'answers' => $answers,
            'sections' => $sections,
        ]);
    }
    
    /**
     * Save the grade and feedback.
     */
    public function submitGrade(Request $request, $attemptId)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isTeacher() && !$authUser->isAdmin() && !$authUser->isOrganization()) {
            abort(403);
        }
        
        $attempt = IeltsTestAttempt::findOrFail($attemptId);
        
        $validated = $request->validate([
            'skill' => 'required|in:writing,speaking',
            'band_score' => 'required|numeric|min:0|max:9',
            'feedback' => 'nullable|string|max:5000',
            'criteria_scores' => 'nullable|array',
            'criteria_scores.*' => 'nullable|numeric|min:0|max:9',
        ]);
        
        $skill = $validated['skill'];
        $bandScore = $validated['band_score'];
        $feedback = $validated['feedback'] ?? '';
        $criteria = $validated['criteria_scores'] ?? [];
        
        if ($skill === 'writing') {
            $attempt->writing_band = $bandScore;
            $attempt->writing_feedback = $feedback;
            $attempt->writing_graded_by = $authUser->id;
            $attempt->writing_graded_at = time();
            if (!empty($criteria)) {
                $attempt->writing_criteria = $criteria;
            }
        } else {
            $attempt->speaking_band = $bandScore;
            $attempt->speaking_feedback = $feedback;
            $attempt->speaking_graded_by = $authUser->id;
            $attempt->speaking_graded_at = time();
            if (!empty($criteria)) {
                $attempt->speaking_criteria = $criteria;
            }
        }
        
        // Recalculate overall band if all sections done
        $this->updateOverallBand($attempt);
        $attempt->save();
        
        return redirect()
            ->route('panel.ielts_grading.index')
            ->with(['toast' => [
                'title' => 'Grade Saved',
                'msg' => ucfirst($skill) . ' graded successfully.',
                'status' => 'success'
            ]]);
    }
    
    /**
     * Recalculate overall band when all skills are graded.
     */
    private function updateOverallBand(IeltsTestAttempt $attempt)
    {
        $test = $attempt->test;
        $scores = [];
        $required = 0;
        
        if ($test->has_listening) {
            $required++;
            if ($attempt->listening_score !== null) {
                $scores[] = $this->rawToBand($attempt->listening_score);
            }
        }
        
        if ($test->has_reading) {
            $required++;
            if ($attempt->reading_score !== null) {
                $scores[] = $this->rawToBand($attempt->reading_score);
            }
        }
        
        if ($test->has_writing) {
            $required++;
            if ($attempt->writing_band !== null) {
                $scores[] = $attempt->writing_band;
            }
        }
        
        if ($test->has_speaking) {
            $required++;
            if ($attempt->speaking_band !== null) {
                $scores[] = $attempt->speaking_band;
            }
        }
        
        // Only set overall if we have all scores
        if (count($scores) === $required && $required > 0) {
            $avg = array_sum($scores) / count($scores);
            $attempt->overall_band = round($avg * 2) / 2;
        }
    }
    
    /**
     * IELTS raw score to band conversion (Cambridge scale).
     */
    private function rawToBand($score)
    {
        $table = [
            40 => 9.0, 39 => 8.5, 38 => 8.5, 37 => 8.0, 36 => 8.0,
            35 => 7.5, 34 => 7.5, 33 => 7.0, 32 => 7.0, 31 => 6.5,
            30 => 6.5, 29 => 6.5, 28 => 6.0, 27 => 6.0, 26 => 6.0,
            25 => 5.5, 24 => 5.5, 23 => 5.5, 22 => 5.0, 21 => 5.0,
            20 => 5.0, 19 => 5.0, 18 => 4.5, 17 => 4.5, 16 => 4.5,
            15 => 4.0, 14 => 4.0, 13 => 4.0, 12 => 3.5, 11 => 3.5,
            10 => 3.0, 9 => 3.0, 8 => 2.5, 7 => 2.5, 6 => 2.0,
            5 => 2.0, 4 => 1.5, 3 => 1.0, 2 => 1.0, 1 => 0.5, 0 => 0.0,
        ];
        
        return $table[$score] ?? 0.0;
    }
    
    /**
     * Get a single answer (for AJAX popup).
     */
    public function viewAnswer($answerId)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isTeacher() && !$authUser->isAdmin() && !$authUser->isOrganization()) {
            abort(403);
        }
        
        $answer = IeltsTestAnswer::with(['question.section', 'attempt.user'])
            ->findOrFail($answerId);
        
        return response()->json([
            'answer' => $answer,
            'question' => $answer->question,
            'section' => $answer->question->section,
        ]);
    }
}
