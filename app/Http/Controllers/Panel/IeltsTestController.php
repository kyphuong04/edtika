<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\IeltsTest;
use App\Models\IeltsTestAttempt;
use App\Models\IeltsTestAnswer;
use App\Models\IeltsTestSection;
use App\Models\AcademicWordListWord;
use App\Models\Sale;
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
            $test->last_attempt = \App\Models\IeltsTestAttempt::where('test_id', $test->id)
                ->where('user_id', $authUser->id)
                ->where('status', 'completed')
                ->orderBy('id', 'desc')
                ->first();
            $test->can_take = $test->canUserTake($authUser->id);
        }
        
        // Mock test daily limit info
        $dailyLimit = getIeltsSettings('mock_tests_per_day') ?? 2;
        $remainingToday = IeltsTest::getRemainingMockTestsToday($authUser->id);
        
        $sidebarData = $this->getSidebarData($authUser);

        $data = [
            'pageTitle' => 'Mock Tests',
            'mockTests' => $mockTests,
            'dailyLimit' => $dailyLimit,
            'remainingToday' => $remainingToday,
            'authUser' => $authUser,
        ] + $sidebarData;
        
        return view('design_1.panel.ielts_tests.mock', $data);
    }
    
    /**
     * Display only practice tests
     */
    public function indexPractice(Request $request)
    {
        $authUser = auth()->user();
        
        // Note: Practice tests may have status 'approved' instead of 'published'
        $query = IeltsTest::with('sections', 'practiceCategory')
            ->practiceTests()
            ->where(function($q) {
                $q->where('status', 'published')
                  ->orWhere('status', 'approved');
            });
        
        // Filter by band score if provided
        $band = $request->get('band');
        if ($band) {
            list($min, $max) = explode('-', $band);
            $query->where(function($q) use ($min, $max) {
                $q->whereBetween('target_band_min', [(float)$min, (float)$max])
                  ->orWhereBetween('target_band_max', [(float)$min, (float)$max]);
            });
        }
        
        $practiceTests = $query->get();
        
        foreach ($practiceTests as $test) {
            $test->user_attempts = $test->getUserAttemptsCount($authUser->id);
            $test->best_attempt = $test->getUserBestAttempt($authUser->id);
            $test->last_attempt = \App\Models\IeltsTestAttempt::where('test_id', $test->id)
                ->where('user_id', $authUser->id)
                ->where('status', 'completed')
                ->orderBy('id', 'desc')
                ->first();
            $test->can_take = $test->canUserTake($authUser->id);
        }
        
        $sidebarData = $this->getSidebarData($authUser);

        $data = [
            'pageTitle' => 'Practice Tests',
            'practiceTests' => $practiceTests,
            'authUser' => $authUser,
        ] + $sidebarData;
        
        return view('design_1.panel.ielts_tests.practice', $data);
    }
    
    /**
     * Get common sidebar data for test listing pages.
     */
    private function getSidebarData($authUser): array
    {
        $randomWord = AcademicWordListWord::inRandomOrder()->first();

        $streak = $this->getUserStreak($authUser->id);

        $enrolledCourses = Sale::where('buyer_id', $authUser->id)
            ->where('type', 'webinar')
            ->whereNull('refund_at')
            ->whereNotNull('webinar_id')
            ->with('webinar')
            ->get()
            ->filter(fn ($s) => !is_null($s->webinar))
            ->map(fn ($s) => $s->webinar)
            ->unique('id')
            ->values();

        return [
            'bandEstimate'    => $authUser->band_estimate ?? 5.0,
            'streak'          => $streak,
            'randomWord'      => $randomWord,
            'enrolledCourses' => $enrolledCourses,
        ];
    }

    /**
     * Calculate consecutive-day streak from completed test attempts.
     */
    private function getUserStreak($userId): int
    {
        $attempts = IeltsTestAttempt::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->get()
            ->groupBy(fn ($item) => \Carbon\Carbon::createFromTimestamp($item->completed_at)->format('Y-m-d'));

        $streak = 0;
        $currentDate = now()->startOfDay();

        foreach ($attempts as $date => $dayAttempts) {
            $attemptDate = \Carbon\Carbon::parse($date);
            $daysDiff = $currentDate->diffInDays($attemptDate, false);

            if ($daysDiff == -$streak) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
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
            ->orderBy('id', 'desc')
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
        
        $canTake = $test->canUserTake($authUser->id);
        
        if ($canTake !== true) {
            $messages = [
                'daily_limit' => 'You have reached your daily mock test limit. Come back tomorrow!',
                'max_attempts' => 'You have reached the maximum attempts for this test.',
                'not_enrolled' => 'You need to enroll in the course to access this test.',
            ];
            
            return back()->with(['toast' => [
                'title' => 'Cannot Start Test',
                'msg' => $messages[$canTake] ?? 'You cannot take this test',
                'status' => 'error'
            ]]);
        }
        
        // Get the requested skill from form (for practice tests)
        $requestedSkill = $request->input('skill');
        
        // Get attempt number
        $attemptNumber = IeltsTestAttempt::where('test_id', $test->id)
            ->where('user_id', $authUser->id)
            ->count() + 1;
        
        // Calculate total questions from actual sections to support custom skills.
        $totalQuestions = (int) $test->sections->sum(function ($section) {
            if (!empty($section->question_start) && !empty($section->question_end) && $section->question_end >= $section->question_start) {
                return (int) $section->question_end - (int) $section->question_start + 1;
            }

            return 0;
        });
        
        // Determine starting skill - use requested skill if provided and valid
        $startingSkill = null;
        if ($requestedSkill && in_array($requestedSkill, ['listening', 'reading', 'writing', 'speaking', 'grammar', 'vocabulary'])) {
            // Verify the test has at least one section for this skill.
            if ($test->sections->contains('skill', $requestedSkill)) {
                $startingSkill = $requestedSkill;
            }
        }
        
        // Fallback to first available section if no skill specified.
        if (!$startingSkill) {
            $startingSkill = optional($test->sections->sortBy('sort_order')->first())->skill;
        }
        
        // Create new attempt
        $attempt = IeltsTestAttempt::create([
            'test_id' => $test->id,
            'user_id' => $authUser->id,
            'attempt_number' => $attemptNumber,
            'status' => 'in_progress',
            'current_skill' => $startingSkill,
            'started_at' => time(),
            'total_questions' => $totalQuestions,
            'remaining_time_seconds' => $test->total_duration * 60,
            'updated_at' => time(),
        ]);
        
        // Get first section for the requested skill
        $firstSection = $test->sections()
            ->where('skill', $startingSkill)
            ->orderBy('sort_order')
            ->first();
        
        // Fallback to any first section if no section found for requested skill
        if (!$firstSection) {
            $firstSection = $test->sections()->orderBy('sort_order')->first();
        }
        
        if ($firstSection) {
            $attempt->current_section_id = $firstSection->id;
            $attempt->current_skill = $firstSection->skill; // Ensure skill matches section
            $attempt->save();
        }
        
        return redirect()->route('panel.ielts_tests.take', $attempt->id);
    }
    
    /**
     * Take test interface
     */
    public function takeTest($attemptId)
    {
        $attempt = IeltsTestAttempt::with(['test.sections.questions', 'answers', 'currentSection.questionGroup'])
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
        
        // If no current section, get first section
        if (!$currentSection) {
            $currentSection = $attempt->test->sections()->orderBy('sort_order')->first();
            if ($currentSection) {
                $attempt->current_section_id = $currentSection->id;
                $attempt->current_skill = $currentSection->skill;
                $attempt->save();
            } else {
                // No sections available
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'This test has no sections configured.',
                    'status' => 'error'
                ]]);
            }
        }
        
        // Load questions with their groups for IDP-style grouping
        $questions = $currentSection->questions()
            ->with('questionGroup')
            ->orderBy('question_number')
            ->orderBy('id')
            ->get();

        // If no direct questions found, try to populate from question bank
        // This handles the newer bank-based workflow where questions are stored in IeltsMockQuestionBank
        if ($questions->isEmpty() && $currentSection->question_group_id) {
            $this->populateQuestionsFromBank($currentSection);

            // Reload questions after population
            $questions = $currentSection->questions()
                ->with('questionGroup')
                ->orderBy('question_number')
                ->orderBy('id')
                ->get();
        }
        
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
        
        // Use IDP-style interface for all tests
        return view('design_1.panel.ielts_tests.take_idp', $data);
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
        $audioUrl = null;
        
        // Handle audio file upload for Speaking section
        if ($request->hasFile('audio')) {
            $audioFile = $request->file('audio');
            $fileName = 'speaking_' . $attemptId . '_' . $questionId . '_' . time() . '.' . $audioFile->getClientOriginalExtension();
            $path = $audioFile->storeAs('speaking_answers', $fileName, 'public');
            $audioUrl = '/storage/' . $path;
        }
        
        $updateData = [
            'answer_text' => $answerText,
            'answer_options' => $answerOptions ? json_encode($answerOptions) : null,
            'answered_at' => time(),
            'modified_at' => time(),
        ];
        
        // Store audio URL in file_url column for Speaking section
        if (!empty($audioUrl)) {
            $updateData['file_url'] = $audioUrl;
        }
        
        IeltsTestAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
            ],
            $updateData
        );
        
        // Update progress
        $attempt->updateProgress();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Answer saved',
            'audio_url' => $audioUrl
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
            // All sections completed - auto-grade and redirect to results
            $this->autoGradeListening($attempt);
            $this->autoGradeReading($attempt);
            $this->calculateBandScores($attempt);
            
            $attempt->update([
                'status' => 'completed',
                'completed_at' => time(),
                'updated_at' => time(),
            ]);
            
            return response()->json([
                'status' => 'completed',
                'redirect' => route('panel.ielts_tests.results', $attempt->id)
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
     * Populate IeltsTestQuestion records from IeltsMockQuestionBank when the section
     * was created using the newer question-bank workflow (section has question_group_id
     * but no rows in ielts_test_questions yet).
     */
    private function populateQuestionsFromBank(IeltsTestSection $section): void
    {
        $group = $section->questionGroup;

        if (!$group) {
            return;
        }

        $bankQuestions = \App\Models\IeltsMockQuestionBank::where('group_id', $group->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $sourceBankType = 'mock';

        if ($bankQuestions->isEmpty()) {
            // practice bank may not have question_order column in older databases
            $practiceQuery = \App\Models\IeltsPracticeQuestionBank::where('group_id', $group->id);
            if (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\IeltsPracticeQuestionBank())->getTable(), 'question_order')) {
                $practiceQuery->orderBy('question_order');
            }
            $bankQuestions = $practiceQuery->orderBy('id')->get();
            $sourceBankType = 'practice';
        }

        if ($bankQuestions->isEmpty()) {
            return;
        }

        // Determine a base question number from the group (if set) or use 1
        $baseNumber = ($group->question_start && $group->question_start > 0) ? (int) $group->question_start : 1;

        foreach ($bankQuestions as $idx => $bankQ) {
            $questionNumber = $baseNumber + $idx;
            $questionType   = $bankQ->question_type ?? 'fill_blank';

            // Normalise answer_options to JSON string if needed
            $answerOptions = null;
            if (!empty($bankQ->answer_options)) {
                $answerOptions = is_array($bankQ->answer_options)
                    ? json_encode($bankQ->answer_options)
                    : $bankQ->answer_options;
            }

            // If answer_options is null, try to extract from question_data
            // The bank stores MCQ options inside question_data as {"options[A]":"...", "options[B]":"...", ...}
            if ($answerOptions === null && !empty($bankQ->question_data)) {
                $rawData = $bankQ->question_data;
                // question_data may be double-encoded
                $decoded = is_array($rawData) ? $rawData : json_decode($rawData, true);
                if (is_string($decoded)) {
                    $decoded = json_decode($decoded, true);
                }
                if (is_array($decoded)) {
                    $extractedOptions = [];
                    foreach ($decoded as $key => $value) {
                        // Handle keys like "options[A]" or "options[A" (broken, missing closing bracket) -> "A"
                        if (preg_match('/options\[([A-Za-z0-9]+)\]?/', $key, $matches)) {
                            $extractedOptions[$matches[1]] = $value;
                        } elseif (in_array($key, ['A', 'B', 'C', 'D', 'E', 'F'])) {
                            $extractedOptions[$key] = $value;
                        }
                    }
                    if (!empty($extractedOptions)) {
                        $answerOptions = json_encode($extractedOptions);
                    }
                }
            }

            $tableStructure = null;
            if (!empty($bankQ->table_structure)) {
                $tableStructure = is_array($bankQ->table_structure)
                    ? json_encode($bankQ->table_structure)
                    : $bankQ->table_structure;
            }

            $questionData = null;
            if (!empty($bankQ->question_data)) {
                $questionData = is_array($bankQ->question_data)
                    ? json_encode($bankQ->question_data)
                    : $bankQ->question_data;
            }

            \App\Models\IeltsTestQuestion::firstOrCreate(
                [
                    'section_id' => $section->id,
                    'sort_order' => $questionNumber,
                ],
                [
                    'question_group_id'  => $group->id,
                    'question_number'    => $questionNumber,
                    'question_order'     => $questionNumber,
                    'question_type'      => $questionType,
                    'question_text'      => $bankQ->question_text ?? '',
                    'instruction'        => $bankQ->instruction ?? null,
                    'correct_answer'     => $bankQ->correct_answer ?? '',
                    'answer_options'     => $answerOptions,
                    'table_structure'    => $tableStructure,
                    'question_data'      => $questionData,
                    'word_limit'         => $bankQ->word_limit ?? null,
                    'auto_gradable'      => $bankQ->auto_gradable ?? (in_array($questionType, ['essay', 'speaking']) ? 0 : 1),
                    'explanation'        => $bankQ->explanation ?? null,
                    'points'             => $bankQ->marks ?? $bankQ->points ?? 1.0,
                    'question_audio'     => $bankQ->audio_file ?? null,
                    'question_image'     => $bankQ->image_file ?? null,
                    'source_question_id' => $bankQ->id,
                    'source_bank_type'   => $sourceBankType,
                    'created_at'         => time(),
                ]
            );
        }
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
        $attempt = IeltsTestAttempt::with(['test.sections.questions.questionGroup', 'answers', 'writingGrader'])
            ->findOrFail($attemptId);
        
        $authUser = auth()->user();
        
        // Owner, teachers, admins, organizations, and managers can view
        $canView = $attempt->user_id === $authUser->id 
            || $authUser->isTeacher() 
            || $authUser->isAdmin() 
            || $authUser->isOrganization()
            || $authUser->isManager();
        
        if (!$canView) {
            abort(403);
        }
        
        $data = [
            'pageTitle' => trans('update.review_answers'),
            'attempt' => $attempt,
            'test' => $attempt->test,
        ];
        
        return view('design_1.panel.ielts_tests.review', $data);
    }
}
