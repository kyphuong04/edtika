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
     * Show the Grade Tests dashboard for the teacher.
     * Displays today's graded count, and two queues (alert / normal) for the active skill.
     */
    public function index(Request $request)
    {
        $authUser = auth()->user();

        if (!$authUser->isTeacher() && !$authUser->isAdmin() && !$authUser->isOrganization() && !$authUser->isManager()) {
            abort(403);
        }

        $skill = in_array($request->get('skill'), ['writing', 'speaking'])
            ? $request->get('skill')
            : 'writing';

        // Today's graded count by this teacher for the active skill
        $todayStart = strtotime(date('Y-m-d') . ' 00:00:00');
        $todayEnd   = strtotime(date('Y-m-d') . ' 23:59:59');

        if ($skill === 'writing') {
            $gradedTodayCount = IeltsTestAttempt::where('writing_graded_by', $authUser->id)
                ->whereBetween('writing_graded_at', [$todayStart, $todayEnd])
                ->count();
        } else {
            $gradedTodayCount = IeltsTestAttempt::where('speaking_graded_by', $authUser->id)
                ->whereBetween('speaking_graded_at', [$todayStart, $todayEnd])
                ->count();
        }

        // Pending (ungraded) attempts for the active skill
        $query = IeltsTestAttempt::with(['test.webinar', 'user.userMetas'])
            ->where('status', 'completed');

        if ($skill === 'writing') {
            $query->whereHas('test', fn($q) => $q->where('has_writing', true))
                  ->whereNull('writing_band');
        } else {
            $query->whereHas('test', fn($q) => $q->where('has_speaking', true))
                  ->whereNull('speaking_band');
        }

        $pendingAttempts = $query->orderBy('completed_at', 'desc')->get();

        // Split into alert queue (AI score < aim_band) and normal queue (AI score >= aim_band)
        $alertAttempts  = collect();
        $normalAttempts = collect();

        foreach ($pendingAttempts as $attempt) {
            $aimBand = $attempt->user && $attempt->user->userMetas
                ? optional($attempt->user->userMetas->where('name', 'aim_band')->first())->value
                : null;

            $aiScore = $skill === 'writing' ? $attempt->writing_score : $attempt->speaking_score;

            $attempt->aim_band_display = $aimBand;
            $attempt->ai_score_display = $aiScore;

            if ($aimBand !== null && $aiScore !== null && (float) $aiScore < (float) $aimBand) {
                $alertAttempts->push($attempt);
            } else {
                $normalAttempts->push($attempt);
            }
        }

        return view('design_1.panel.ielts_grading.index', [
            'pageTitle'        => 'Grade Tests',
            'skill'            => $skill,
            'gradedTodayCount' => $gradedTodayCount,
            'alertAttempts'    => $alertAttempts,
            'normalAttempts'   => $normalAttempts,
        ]);
    }

    /**
     * Show the list of attempts already graded by the current teacher.
     */
    public function graded(Request $request)
    {
        $authUser = auth()->user();

        if (!$authUser->isTeacher() && !$authUser->isAdmin() && !$authUser->isOrganization() && !$authUser->isManager()) {
            abort(403);
        }

        $skill = in_array($request->get('skill'), ['writing', 'speaking'])
            ? $request->get('skill')
            : 'writing';

        $query = IeltsTestAttempt::with(['test.webinar', 'user'])
            ->where('status', 'completed');

        // if ($skill === 'writing') {
        //     $query->where('writing_graded_by', $authUser->id)
        //           ->whereNotNull('writing_band')
        //           ->orderByDesc('writing_graded_at');
        // } else {
        //     $query->where('speaking_graded_by', $authUser->id)
        //           ->whereNotNull('speaking_band')
        //           ->orderByDesc('speaking_graded_at');
        // }

        // $attempts = $query->paginate(20);

        // return view('design_1.panel.ielts_grading.graded', [
        //     'pageTitle' => 'Danh sách đã chấm',
        //     'skill'     => $skill,
        //     'attempts'  => $attempts,
        // ]);
        if ($skill === 'writing') {
            $query->where('writing_graded_by', $authUser->id)
                ->whereNotNull('writing_band')
                ->orderByDesc('writing_graded_at');
        } else {
            $query->where('speaking_graded_by', $authUser->id)
                ->whereNotNull('speaking_band')
                ->orderByDesc('speaking_graded_at');
        }

        // Search filter
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('full_name', 'like', '%' . $search . '%');
                })->orWhereHas('test', function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%');
                });
            });
        }

        $attempts = $query->paginate(20);

        return view('design_1.panel.ielts_grading.graded', [
            'pageTitle' => 'Danh sách đã chấm',
            'skill'     => $skill,
            'attempts'  => $attempts,
            'search'    => $request->get('q', ''),
        ]);
    }
    
    /**
     * Open grading form for one student's attempt.
     */
    public function grade($attemptId, Request $request, $skill = null)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isTeacher() && !$authUser->isAdmin() && !$authUser->isOrganization() && !$authUser->isManager()) {
            abort(403);
        }
        
        $attempt = IeltsTestAttempt::with([
            'test.sections.questions',
            'test.sections.questionGroup',
            'test.webinar',
            'user.userMetas',
            'answers.question.section'
        ])->findOrFail($attemptId);
        
        // Skill comes from route segment /{skill?} or query string ?skill=
        if (!$skill) {
            $skill = $request->get('skill');
        }
        if (!$skill || !in_array($skill, ['writing', 'speaking'])) {
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
            ->with('questionGroup')
            ->orderBy('sort_order')
            ->get();

        // Student aim band
        $aimBand = optional(
            $attempt->user && $attempt->user->userMetas
                ? $attempt->user->userMetas->where('name', 'aim_band')->first()
                : null
        )->value;

        // AI overall band
        $aiOverallBand = $skill === 'writing' ? $attempt->writing_score : $attempt->speaking_score;

        // AI per-criteria scores — stored as numeric array on each answer
        // Writing: [0]=task_achievement [1]=coherence [2]=lexical [3]=grammar
        // Speaking: [0]=fluency [1]=lexical [2]=grammar [3]=pronunciation
        $aiCriteriaAccum = [];
        $aiAnswerFeedback = [];
        foreach ($answers as $ans) {
            $bands = $skill === 'writing' ? ($ans->writing_bands ?? []) : ($ans->speaking_bands ?? []);
            if (is_array($bands) && count($bands) === 4) {
                if ($skill === 'writing') {
                    $keys = ['task_achievement', 'coherence', 'lexical', 'grammar'];
                } else {
                    $keys = ['fluency', 'lexical', 'grammar', 'pronunciation'];
                }
                foreach ($keys as $i => $key) {
                    $aiCriteriaAccum[$key][] = (float) $bands[$i];
                }
            }
        }
        // Average per criterion across all answers (usually 1 for writing, may be multiple for speaking)
        $aiCriteria = [];
        foreach ($aiCriteriaAccum as $key => $vals) {
            $aiCriteria[$key] = count($vals) ? round(array_sum($vals) / count($vals) * 2) / 2 : null;
        }

        // Teacher's existing criteria + feedback + band
        $teacherCriteria  = ($skill === 'writing' ? $attempt->writing_criteria  : $attempt->speaking_criteria)  ?? [];
        $existingFeedback = ($skill === 'writing' ? $attempt->writing_feedback  : $attempt->speaking_feedback)  ?? '';
        $existingBand     = ($skill === 'writing' ? $attempt->writing_band      : $attempt->speaking_band)      ?? '';
        
        return view('design_1.panel.ielts_grading.grade', [
            'pageTitle'       => 'Grade ' . ucfirst($skill) . ' — ' . $attempt->user->full_name,
            'attempt'         => $attempt,
            'test'            => $attempt->test,
            'user'            => $attempt->user,
            'skill'           => $skill,
            'answers'         => $answers,
            'sections'        => $sections,
            'aimBand'         => $aimBand,
            'aiOverallBand'   => $aiOverallBand,
            'aiCriteria'      => $aiCriteria,
            'aiAnswerFeedback'=> $aiAnswerFeedback,
            'teacherCriteria' => $teacherCriteria,
            'existingFeedback'=> $existingFeedback,
            'existingBand'    => $existingBand,
        ]);
    }
    
    /**
     * Save the grade and feedback.
     */
    public function submitGrade(Request $request, $attemptId)
    {
        $authUser = auth()->user();
        
        if (!$authUser->isTeacher() && !$authUser->isAdmin() && !$authUser->isOrganization() && !$authUser->isManager()) {
            abort(403);
        }
        
        $attempt = IeltsTestAttempt::findOrFail($attemptId);
        
        $validated = $request->validate([
            'skill'              => 'required|in:writing,speaking',
            'band_score'         => 'nullable|numeric|min:0|max:9',
            'feedback'           => 'nullable|string|max:50000',
            'criteria_scores'    => 'nullable|array',
            'section_feedback'   => 'nullable|array',
            'section_feedback.*' => 'nullable|string|max:50000',
            'annotated_essays'   => 'nullable|string',
        ]);

        $skill    = $validated['skill'];
        $feedback = $validated['feedback'] ?? '';
        $criteria = $validated['criteria_scores'] ?? [];

        /* ── Speaking: per-section criteria → flat averages + nested sections store ── */
        if ($skill === 'speaking') {
            $spKeys          = ['fluency', 'lexical', 'grammar', 'pronunciation'];
            $sectionFeedback = $validated['section_feedback'] ?? [];
            $sectionsStore   = [];
            $perCritAccum    = array_fill_keys($spKeys, []);

            foreach ($criteria as $sectionId => $sectionScores) {
                if (!is_array($sectionScores)) { continue; }
                $sid = (string)$sectionId;
                $secEntry = [];
                foreach ($spKeys as $k) {
                    $val = isset($sectionScores[$k]) && is_numeric($sectionScores[$k])
                        ? (float)$sectionScores[$k]
                        : null;
                    $secEntry[$k] = $val;
                    if ($val !== null) { $perCritAccum[$k][] = $val; }
                }
                $secEntry['feedback'] = $sectionFeedback[$sid] ?? '';
                $sectionsStore[$sid]  = $secEntry;
            }

            // Flat averages (for backward compat with review.blade.php)
            $flatCriteria = [];
            foreach ($spKeys as $k) {
                $vals = $perCritAccum[$k];
                $flatCriteria[$k] = !empty($vals)
                    ? round(array_sum($vals) / count($vals) * 2) / 2
                    : null;
            }
            $flatCriteria['sections'] = $sectionsStore;
            $criteria = $flatCriteria;

            // Auto-derive band from criteria average if not provided
            $allVals = array_filter(array_map(fn ($k) => $flatCriteria[$k], $spKeys), fn ($v) => $v !== null);
            $bandScore = !empty($allVals)
                ? round(array_sum($allVals) / count($allVals) * 2) / 2
                : ($validated['band_score'] ?? 0);

            // Build concatenated feedback from all sections
            if (empty($feedback)) {
                $parts = [];
                foreach ($sectionsStore as $sf) {
                    if (!empty($sf['feedback'])) { $parts[] = strip_tags($sf['feedback']); }
                }
                $feedback = implode("\n\n", $parts);
            }
        } else {
            $bandScore = $validated['band_score'] ?? 0;
        }

        // Merge annotated essays into criteria array (keyed by answer id)
        $rawAnnotated = $request->input('annotated_essays');
        if ($rawAnnotated) {
            $decoded = json_decode($rawAnnotated, true);
            if (is_array($decoded)) {
                $criteria['annotated_essays'] = $decoded;
            }
        } else {
            // Preserve existing annotations if none submitted
            $existingCriteria = $skill === 'writing' ? ($attempt->writing_criteria ?? []) : ($attempt->speaking_criteria ?? []);
            if (!empty($existingCriteria['annotated_essays'])) {
                $criteria['annotated_essays'] = $existingCriteria['annotated_essays'];
            }
        }
        
        if ($skill === 'writing') {
            $attempt->writing_band      = $bandScore;
            $attempt->writing_feedback  = $feedback;
            $attempt->writing_graded_by = $authUser->id;
            $attempt->writing_graded_at = time();
            $attempt->writing_criteria  = $criteria;
        } else {
            $attempt->speaking_band      = $bandScore;
            $attempt->speaking_feedback  = $feedback;
            $attempt->speaking_graded_by = $authUser->id;
            $attempt->speaking_graded_at = time();
            $attempt->speaking_criteria  = $criteria;
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

    /**
     * Submit a student's star rating for a teacher's grading of their attempt.
     */
    public function submitRating(Request $request)
    {
        $validated = $request->validate([
            'attempt_id'    => 'required|integer|exists:ielts_test_attempts,id',
            'instructor_id' => 'required|integer|exists:users,id',
            'skill'         => 'required|in:writing,speaking',
            'rating'        => 'required|integer|min:1|max:5',
        ]);

        $studentId = auth()->id();

        // Verify the attempt belongs to this student
        $attempt = IeltsTestAttempt::where('id', $validated['attempt_id'])
            ->where('user_id', $studentId)
            ->firstOrFail();

        // Verify the instructor actually graded this skill on this attempt
        $gradedByField = $validated['skill'] === 'writing' ? 'writing_graded_by' : 'speaking_graded_by';
        if ((int) $attempt->{$gradedByField} !== (int) $validated['instructor_id']) {
            return response()->json(['error' => 'Invalid grader for this attempt.'], 422);
        }

        \App\Models\IeltsGradingRating::updateOrCreate(
            [
                'attempt_id' => $validated['attempt_id'],
                'student_id' => $studentId,
                'skill'      => $validated['skill'],
            ],
            [
                'instructor_id' => $validated['instructor_id'],
                'rating'        => $validated['rating'],
            ]
        );

        return response()->json(['success' => true]);
    }
}
