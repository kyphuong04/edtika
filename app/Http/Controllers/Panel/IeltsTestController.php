<?php

namespace App\Http\Controllers\Panel;


use App\Support\Ielts\BuildsIeltsQuestionPayload;
use App\Support\Ielts\GradesIeltsAttempts;

use App\Http\Controllers\Controller;
use App\Models\IeltsTest;
use App\Models\IeltsTestAttempt;
use App\Models\IeltsTestAnswer;
use App\Models\IeltsTestSection;
use App\Models\IeltsTestQuestion;
use App\Models\AcademicWordListWord;
use App\Models\Sale;
use App\QuizzesResult;
use Illuminate\Http\Request;

class IeltsTestController extends Controller
{
    /**
     * Display tests for students
     */
    use BuildsIeltsQuestionPayload, GradesIeltsAttempts;

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
     * Display the unified "Mock Test" page.
     *
     * GỘP (2026-08): Trang này giờ hiển thị CẢ Mock Test ("Full Test" tab)
     * và Practice Test ("Practice by Skill" tab) trên cùng 1 view, chuyển
     * tab bằng JS (không reload). Sidebar bên phải đổi từ "thông tin cá
     * nhân" sang bộ lọc (skill / bài lẻ-full đề / trạng thái).
     *
     * Lưu ý: bộ lọc "Level 1/2/3" (độ khó) đang bị ẩn trên UI vì hiện chưa
     * có cột dữ liệu tương ứng trên ielts_tests — khi có, bổ sung lại tại
     * đây (đính kèm 1 trường difficulty_level hoặc quy đổi từ
     * target_band_min/target_band_max) và bỏ ẩn phần filter trong view.
     */
    public function indexMock()
    {
        $authUser = auth()->user();

        // ----- Full Test (Mock) -----
        $mockTests = IeltsTest::with('sections')
            ->mockTests()
            ->published()
            ->active()
            ->get();

        foreach ($mockTests as $test) {
            $test->user_attempts = $test->getUserAttemptsCount($authUser->id);
            $test->best_attempt = $test->getUserBestAttempt($authUser->id);
            $test->last_attempt = IeltsTestAttempt::where('test_id', $test->id)
                ->where('user_id', $authUser->id)
                ->where('status', 'completed')
                ->orderBy('id', 'desc')
                ->first();
            $test->can_take = $test->canUserTake($authUser->id);
        }

        // ----- Practice by Skill -----
        // Note: Practice tests may have status 'approved' instead of 'published'.
        $practiceTests = IeltsTest::with('sections', 'practiceCategory')
            ->practiceTests()
            ->where(function ($q) {
                $q->where('status', 'published')
                  ->orWhere('status', 'approved');
            })
            ->get();

        foreach ($practiceTests as $test) {
            $test->user_attempts = $test->getUserAttemptsCount($authUser->id);
            $test->best_attempt = $test->getUserBestAttempt($authUser->id);
            $test->last_attempt = IeltsTestAttempt::where('test_id', $test->id)
                ->where('user_id', $authUser->id)
                ->where('status', 'completed')
                ->orderBy('id', 'desc')
                ->first();
            $test->can_take = $test->canUserTake($authUser->id);

            // Kỹ năng chính của bài practice (dùng để hiển thị icon/badge và
            // để lọc theo skill ở sidebar bộ lọc).
            $test->primary_skill = $test->getPrimarySkill();

            // Bài "Full đề" (nhiều section cùng 1 skill, VD 3 Reading passage
            // gộp trong 1 test) khác với "Bài lẻ" (chỉ 1 section/skill đó).
            // Dùng để phân biệt 2 checkbox filter "Bài lẻ" / "Full đề".
            $test->is_full_test = $test->primary_skill
                ? $test->sections->where('skill', $test->primary_skill)->count() > 1
                : false;

            // Section đại diện để hiển thị trên card (Passage/Part label +
            // tiêu đề nội dung). Với "Full đề" card chưa có thiết kế riêng
            // theo section cụ thể nên tạm lấy section đầu tiên của skill đó.
            $test->display_section = $test->primary_skill
                ? $test->sections->firstWhere('skill', $test->primary_skill)
                : $test->sections->first();
        }

        // Mock test daily limit info (hiển thị badge; giới hạn thật sự đang
        // tắt trong IeltsTest::canUserTake() — không đụng ở đây, xem ghi chú
        // trong Model).
        $dailyLimit = getIeltsSettings('mock_tests_per_day') ?? 2;
        $remainingToday = IeltsTest::getRemainingMockTestsToday($authUser->id);

        $sidebarData = $this->getSidebarData($authUser);

        $data = [
            'pageTitle' => 'IELTS Mock & Practice Tests',
            'mockTests' => $mockTests,
            'practiceTests' => $practiceTests,
            'dailyLimit' => $dailyLimit,
            'remainingToday' => $remainingToday,
            'authUser' => $authUser,
        ] + $sidebarData;

        return view('design_1.panel.ielts_tests.mock', $data);
    }

    /**
     * @deprecated Practice Tests đã được gộp vào trang Mock Test (tab
     * "Practice by Skill") — xem indexMock(). Giữ route/method này lại
     * (thay vì xoá) để các link cũ (bookmark, thông báo cũ, v.v.) trỏ tới
     * route này không bị 404, mà tự chuyển hướng sang trang gộp.
     */
    public function indexPractice(Request $request)
    {
        return redirect()->route('panel.ielts_tests.mock');
    }

    /**
     * Display only diagnostic tests.
     */
    public function indexDiagnostic()
    {
        $authUser = auth()->user();

        // $diagnosticTests = IeltsTest::with('sections', 'practiceCategory')
        //     ->where('type', 'diagnostic')
        //     ->where(function ($q) {
        //         $q->where('status', 'published')
        //             ->orWhere('status', 'approved');
        //     })
        //     ->where('is_active', 1)
        //     ->get();
        $diagnosticTests = IeltsTest::with('sections', 'practiceCategory')
            ->where('type', 'diagnostic')
            ->where(function ($q) use ($authUser) {
                $q->where('status', 'published')
                    ->orWhere('status', 'approved')
                    // Creator always sees their own tests regardless of status
                    // (draft, pending_approval, rejected...) for review/QA purposes.
                    ->orWhere('created_by', $authUser->id);
            })
        ->get();

        foreach ($diagnosticTests as $test) {
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
        $isEnglish = mb_strtolower(app()->getLocale()) === 'en';

        $data = [
            'pageTitle' => 'Diagnostic Tests',
            'practiceTests' => $diagnosticTests,
            'authUser' => $authUser,
            'groupBySkill' => false,
            'emptyStateTitle' => $isEnglish
                ? 'No diagnostic tests have been uploaded yet.'
                : 'Chưa có bộ đề Diagnostic Tests nào được upload lên.',
            'emptyStateHint' => $isEnglish
                ? 'Please check back later or contact your administrator for availability.'
                : 'Vui lòng quay lại sau hoặc liên hệ quản trị viên để được cập nhật bộ đề mới.',
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
    public function takeTest(Request $request, $attemptId)
    {
        $attempt = IeltsTestAttempt::with([
            'test',
            'currentSection.parts.questionGroups',
            'currentSection.questions',
            'answers',
        ])->findOrFail($attemptId);

        $authUser = auth()->user();

        if ($attempt->user_id !== $authUser->id) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('panel.ielts_tests.results', $attemptId);
        }

        $currentSection = $attempt->currentSection;

        if (!$currentSection) {
            $currentSection = $attempt->test->sections()->orderBy('sort_order')->first();

            if ($currentSection) {
                $attempt->current_section_id = $currentSection->id;
                $attempt->current_skill = $currentSection->skill;
                $attempt->save();
            } else {
                return back()->with(['toast' => [
                    'title' => 'Error',
                    'msg' => 'This test has no sections configured.',
                    'status' => 'error',
                ]]);
            }
        }

        $skill = $currentSection->skill;

        // Kích hoạt ngân sách thời gian. Speaking dùng scope riêng theo Part,
        // client gọi speakingStartPart() khi xem từng câu -> bỏ qua ở đây.
        if ($skill !== 'speaking') {
            $scopeKey = $attempt->resolveScopeKey($skill);

            if ($attempt->hasScopeExpired($scopeKey)) {
                $this->autoSubmitSkill($attempt, $skill);
                return redirect()->route('panel.ielts_tests.take', $attempt->id);
            }

            $attempt->activateScope($scopeKey);
        }

        $isMockTest = $attempt->test->isMockTest();

        $sectionData = $this->buildSectionData($currentSection);
        // Speaking cần Hint khi làm bài; Model Answer chỉ mở sẵn ở practice
        // test — mock test phải lấy qua speakingModelAnswer() sau khi trả lời.
        $sectionData = $this->stripSectionAnswers($sectionData, [
            'keep_hint' => $skill === 'speaking',
            'keep_model_answer' => $skill === 'speaking' && !$isMockTest,
        ]);
        $sectionData = $this->resolveSectionMediaUrls($sectionData);

        $savedAnswers = $attempt->answers->mapWithKeys(function ($answer) {
            return [
                $answer->question_id => [
                    'answer_text' => $answer->answer_text,
                    'answer_options' => $answer->answer_options_array,
                    'file_url' => $answer->audio_url,
                ],
            ];
        });

        $isMentorPreview = ((int) session('mentor_preview_attempt_id', 0) === (int) $attempt->id)
            && ((int) session('mentor_preview_test_id', 0) === (int) $attempt->test_id);

        $scopeKeyForInitialTimer = $skill !== 'speaking' ? $attempt->resolveScopeKey($skill) : null;

        return view('design_1.panel.ielts_tests.attempt.index', [
            'pageTitle' => 'Taking: ' . $attempt->test->title,
            'justContent' => true,
            'attempt' => $attempt,
            'test' => $attempt->test,
            'currentSection' => $currentSection,
            'sectionData' => $sectionData,
            'savedAnswers' => $savedAnswers,
            'isMentorPreview' => $isMentorPreview,
            'mentorPreviewExitUrl' => $isMentorPreview
                ? route('panel.my_ielts_tests.exit_preview', $attempt->test_id)
                : null,
            'initialRemainingSeconds' => $scopeKeyForInitialTimer
                ? $attempt->getScopeTimeRemaining($scopeKeyForInitialTimer)
                : null,
            'testType' => $attempt->test->type,
        ]);
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
        if ($currentSkill && $currentSkill !== 'speaking') {
            $attempt->settleScope($attempt->resolveScopeKey($currentSkill));
        } elseif ($currentSkill === 'speaking') {
            // Đóng bất kỳ scope Part nào của Speaking còn đang active.
            foreach (array_keys($attempt->skill_time_budget ?? []) as $scopeKey) {
                if (str_starts_with($scopeKey, 'speaking-part-')) {
                    $attempt->settleScope($scopeKey);
                }
            }
        }
        
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
        $this->autoGradeListening($attempt);
        $this->autoGradeReading($attempt);
        $this->calculateBandScores($attempt);
        
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

    public function attemptSectionData($attemptId)
    {
        $attempt = IeltsTestAttempt::with([
            'test',
            'currentSection.parts.questionGroups',
            'currentSection.questions',
        ])->findOrFail($attemptId);

        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return response()->json(['error' => 'attempt_completed'], 409);
        }

        if ($attempt->hasExpired()) {
            return response()->json(['error' => 'attempt_expired'], 409);
        }

        $section = $attempt->currentSection;

        if (!$section) {
            return response()->json(['error' => 'no_active_section'], 404);
        }

        $sectionData = $this->buildSectionData($section);
        $sectionData = $this->stripSectionAnswers($sectionData);
        $sectionData = $this->resolveSectionMediaUrls($sectionData);

        // Đáp án học viên đã lưu từ trước (resume sau khi reload/mất mạng)
        // — trả kèm để client hydrate lại state mà không cần thêm request.
        $savedAnswers = $attempt->answers()
            ->get(['question_id', 'answer_text', 'answer_options', 'file_url'])
            ->mapWithKeys(function ($answer) {
                return [
                    $answer->question_id => [
                        'answer_text' => $answer->answer_text,
                        'answer_options' => $answer->answer_options_array,
                        'file_url' => $answer->audio_url,
                    ],
                ];
            });

        return response()->json([
            'attempt_id' => $attempt->id,
            'section_id' => $section->id,
            'skill' => $section->skill,
            'test_type' => $attempt->test->type,
            'remaining_time_seconds' => $attempt->getTimeRemaining(),
            'section' => $sectionData,
            'saved_answers' => $savedAnswers,
        ]);
    }
    // Thêm cạnh autoSubmitTest() hiện có

    /**
     * Hết giờ 1 skill (Mock Test) -> tự động coi như học viên đã "Finish
     * Section" cho skill đó, chuyển sang section tiếp theo (hoặc nộp cả bài
     * nếu đây là section cuối). Không hỏi xác nhận — đúng chuẩn thi thật:
     * hết giờ là dừng bút.
     */
    private function autoSubmitSkill(IeltsTestAttempt $attempt, string $skill): void
    {
        $attempt->settleScope($attempt->resolveScopeKey($skill));
        $attempt->completeSection($skill);

        $nextSection = $attempt->getNextSection();

        if ($nextSection) {
            $attempt->current_skill = $nextSection->skill;
            $attempt->current_section_id = $nextSection->id;
            $attempt->save();
            return;
        }

        $this->autoGradeListening($attempt);
        $this->autoGradeReading($attempt);
        $this->calculateBandScores($attempt);

        $attempt->update([
            'status' => 'completed',
            'completed_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * Học viên chuyển sang Part khác của Speaking (JS ẩn/hiện panel, không
     * reload). Kích hoạt scope thời gian riêng cho Part đó — nếu Part trước
     * đang active, tự động chốt lại (xử lý trong activateScope()).
     */
    public function speakingStartPart(Request $request, $attemptId, $partId)
    {
        $attempt = IeltsTestAttempt::with('test')->findOrFail($attemptId);

        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $scopeKey = $attempt->resolveScopeKey('speaking', (int) $partId);

        if ($attempt->hasScopeExpired($scopeKey)) {
            // Part này đã hết 5 phút từ trước (VD học viên quay lại sau khi bỏ
            // dở) -> không cho ghi âm tiếp, coi như đã xong Part này.
            return response()->json([
                'status' => 'expired',
                'remaining_seconds' => 0,
            ]);
        }

        $attempt->activateScope($scopeKey);

        return response()->json([
            'status' => 'ok',
            'remaining_seconds' => $attempt->getScopeTimeRemaining($scopeKey),
        ]);
    }

    /**
     * Polling endpoint — client gọi định kỳ để đồng bộ lại timer (chống lệch
     * do throttle tab nền) và phát hiện hết giờ ngay cả khi học viên không
     * tương tác gì (không có request saveAnswer nào để server "tình cờ" biết).
     */
    public function scopeStatus(Request $request, $attemptId)
    {
        $attempt = IeltsTestAttempt::with('test')->findOrFail($attemptId);

        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $skill = $request->query('skill', $attempt->current_skill);
        $partId = $request->query('part_id') ? (int) $request->query('part_id') : null;
        $scopeKey = $attempt->resolveScopeKey($skill, $partId);

        return response()->json([
            'scope_key' => $scopeKey,
            'remaining_seconds' => $attempt->getScopeTimeRemaining($scopeKey),
            'expired' => $attempt->hasScopeExpired($scopeKey),
        ]);
    }

    /**
     * Model Answer của 1 câu Speaking trong mock test. Không gửi kèm payload
     * lúc load trang (sẽ lộ bài mẫu trước khi thí sinh nói) — chỉ trả về sau
     * khi câu đó đã có bản thu, hoặc khi attempt đã nộp.
     */
    public function speakingModelAnswer(Request $request, $attemptId, $questionId)
    {
        $attempt = IeltsTestAttempt::with('test')->findOrFail($attemptId);

        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $question = IeltsTestQuestion::whereKey($questionId)->first();

        if (!$question) {
            abort(404);
        }

        // Câu hỏi phải thuộc đúng bài thi của attempt này.
        $belongsToTest = IeltsTestSection::where('test_id', $attempt->test_id)
            ->where('id', $question->section_id)
            ->exists();

        if (!$belongsToTest) {
            abort(404);
        }

        $hasAnswered = $attempt->answers()
            ->where('question_id', $question->id)
            ->whereNotNull('answer_file')
            ->exists();

        if (!$hasAnswered && $attempt->status !== 'completed') {
            return response()->json([
                'status' => 'locked',
                'model_answer' => null,
            ], 403);
        }

        $questionData = $question->question_data;
        if (is_string($questionData)) {
            $decoded = json_decode($questionData, true);
            $questionData = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }

        return response()->json([
            'status' => 'ok',
            'model_answer' => $this->resolveModelAnswer($question, $questionData),
        ]);
    }
}