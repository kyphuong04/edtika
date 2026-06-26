<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Panel\Traits\DashboardTrait;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\AcademicWordListWord;
use App\Models\Comment;
use App\Models\Gift;
use App\Models\IeltsTestAttempt;
use App\Models\Meeting;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\Subscribe;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesResult;
use App\Models\Support;
use App\Models\UserMeta;
use App\Models\UserWordProgress;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    use DashboardTrait;

    public function index(Request $request)
    {
        $user = auth()->user();

        $data = [
            'pageTitle' => trans('panel.dashboard'),
        ];

        if ($user->isUser() || $user->isStudent()) {
            $data = array_merge($data, $this->getStudentDashboardData($request, $user));
        } else {
            $data = array_merge($data, $this->getInstructorDashboardData($request, $user));
        }

        // Upcoming Events
        $data = array_merge($data, $this->handleDashboardUpcomingEvents($user));

        // Gifts Modal
        $data['giftModal'] = $this->showGiftModal($user);


        return view('design_1.panel.dashboard.index', $data);
    }

    private function getStudentDashboardData(Request $request, $user): array
    {
        $data = [];

        $data['activeSubscribe'] = Subscribe::getActiveSubscribe($user->id);
        $data['authUserBalanceCharge'] = $user->getAccountingCharge();
        $data['authUserReadyPayout'] = $user->getPayout();


        $userBoughtWebinarsIds = $user->getPurchasedCoursesIds();

        // hello_box
        $data['helloBox'] = $this->getStudentHelloBoxData($user, $userBoughtWebinarsIds);

        // Courses Overview
        $data['coursesOverview'] = $this->getStudentCoursesOverviewData($user, $userBoughtWebinarsIds);

        // My Assignments
        $data['myAssignments'] = $this->getStudentMyAssignmentsData($user, $userBoughtWebinarsIds);

        // Learning Activity
        $data['learningActivity'] = $this->getStudentLearningActivityData($user, $userBoughtWebinarsIds);

        // Noticeboard
        $data['unreadNoticeboards'] = $user->getUnreadNoticeboards();

        // Support Messages
        $data['supportMessages'] = $this->getStudentSupportMessagesData($user, $userBoughtWebinarsIds);

        // My quizzes
        $data['myQuizzes'] = $this->getStudentMyQuizzesData($user, $userBoughtWebinarsIds);

        // Upcoming Live Sessions
        $data['upcomingLiveSessions'] = $this->getStudentUpcomingLiveSessionsData($user, $userBoughtWebinarsIds);

        // Open Meetings
        $data['openMeetings'] = $this->getStudentOpenMeetingsData($user, $userBoughtWebinarsIds);

        // IELTS Dashboard Data
        $data['ieltsData'] = $this->getStudentIeltsData($user);

        // User settings stored in meta
        $data['aimBand']      = UserMeta::where('user_id', $user->id)->where('name', 'aim_band')->value('value');
        $data['mockTestDate'] = UserMeta::where('user_id', $user->id)->where('name', 'mock_test_date')->value('value');

        // Word of the Day (rotates daily, from academic word list)
        $dayIndex = 0;
        $wordCount = AcademicWordListWord::count();
        if ($wordCount > 0) {
            $dayIndex = (int) date('z') % $wordCount;
            $data['wordOfDay'] = AcademicWordListWord::orderBy('id')->skip($dayIndex)->first();
        } else {
            $dailyFallbackWords = [
                ['word' => 'food additives', 'pronunciation' => 'fuːd əˈdɪktɪvz', 'translation' => 'chất phụ gia thực phẩm', 'definition' => 'Substances added to food to preserve or improve it.', 'example' => 'Food additives improve the taste of food.'],
                ['word' => 'sustainable', 'pronunciation' => 'səˈsteɪnəbl', 'translation' => 'bền vững', 'definition' => 'Able to be maintained over the long term without exhausting resources.', 'example' => 'We need sustainable solutions for climate change.'],
                ['word' => 'significant', 'pronunciation' => 'sɪɡˈnɪfɪkənt', 'translation' => 'đáng kể', 'definition' => 'Large or important enough to be noticed.', 'example' => 'The results showed a significant improvement.'],
                ['word' => 'achieve', 'pronunciation' => 'əˈtʃiːv', 'translation' => 'đạt được', 'definition' => 'To successfully complete or reach a goal.', 'example' => 'She worked hard to achieve her target band score.'],
                ['word' => 'advantage', 'pronunciation' => 'ədˈvɑːntɪdʒ', 'translation' => 'lợi thế', 'definition' => 'A condition or circumstance that puts someone in a favorable position.', 'example' => 'Reading regularly gives students an advantage.'],
            ];

            $data['wordOfDay'] = $dailyFallbackWords[$dayIndex % count($dailyFallbackWords)];
        }

        return $data;
    }

    /**
     * Save student dashboard settings (aim_band, mock_test_date).
     */
    public function saveSettings(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'aim_band'       => 'nullable|numeric|min:0|max:9',
            'mock_test_date' => 'nullable|date_format:Y-m-d',
        ]);

        foreach (['aim_band', 'mock_test_date'] as $key) {
            if (array_key_exists($key, $validated) && $validated[$key] !== null) {
                UserMeta::updateOrCreate(
                    ['user_id' => $user->id, 'name' => $key],
                    ['value'   => $validated[$key]]
                );
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Weak-points detail page.
     */
    public function weakPoints()
    {
        $user      = auth()->user();
        $ieltsData = $this->getStudentIeltsData($user);

        return view('design_1.panel.dashboard.student.weak_points', [
            'pageTitle' => 'Weak Points',
            'ieltsData' => $ieltsData,
            'authUser'  => $user,
        ]);
    }

    public function adminPerformance()
    {
        $user = auth()->user();

        abort_unless($user && $user->isAdmin(), 403);

        return view('design_1.panel.dashboard.admin_performance', [
            'pageTitle' => 'Admin Performance',
            'adminPerformance' => $this->getAdminPerformanceDashboardData($user),
        ]);
    }

    // ─── IELTS Data Helpers ─────────────────────────────────────────────────────

    private function getStudentIeltsData($user): array
    {
        $latestAttempt = IeltsTestAttempt::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->first();

        $recentFeedbackAttempts = IeltsTestAttempt::query()
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNotNull('writing_feedback')
                    ->orWhereNotNull('speaking_feedback');
            })
            ->with([
                'test:id,title',
                'writingGrader:id,full_name,avatar,avatar_settings',
                'speakingGrader:id,full_name,avatar,avatar_settings',
            ])
            ->orderByRaw('GREATEST(COALESCE(writing_graded_at, 0), COALESCE(speaking_graded_at, 0), COALESCE(completed_at, 0)) desc')
            ->limit(4)
            ->get();

        $recentFeedbacks = collect();
        foreach ($recentFeedbackAttempts as $attempt) {
            if (!empty($attempt->writing_feedback)) {
                $recentFeedbacks->push([
                    'attempt_id' => $attempt->id,
                    'skill' => 'Writing',
                    'test_title' => $attempt->test->title ?? 'IELTS Test',
                    'feedback' => $attempt->writing_feedback,
                    'band' => $attempt->writing_band,
                    'grader' => $attempt->writingGrader,
                    'graded_at' => $attempt->writing_graded_at ?? $attempt->completed_at,
                ]);
            }

            if (!empty($attempt->speaking_feedback)) {
                $recentFeedbacks->push([
                    'attempt_id' => $attempt->id,
                    'skill' => 'Speaking',
                    'test_title' => $attempt->test->title ?? 'IELTS Test',
                    'feedback' => $attempt->speaking_feedback,
                    'band' => $attempt->speaking_band,
                    'grader' => $attempt->speakingGrader,
                    'graded_at' => $attempt->speaking_graded_at ?? $attempt->completed_at,
                ]);
            }
        }

        $recentFeedbacks = $recentFeedbacks
            ->sortByDesc('graded_at')
            ->values()
            ->take(3);

        $skills = ['listening', 'reading', 'writing', 'speaking'];
        $skillBands = [];
        foreach ($skills as $skill) {
            $skillBands[$skill] = $latestAttempt ? (float)($latestAttempt->{$skill . '_band'} ?? 0) : 0;
        }

        // Vocabulary: ratio of learned words mapped 0‑9
        $totalWords   = UserWordProgress::where('user_id', $user->id)->count();
        $learnedWords = UserWordProgress::where('user_id', $user->id)->where('is_learned', true)->count();
        $skillBands['vocabulary'] = $totalWords > 0 ? round(($learnedWords / $totalWords) * 9, 1) : 0;
        $skillBands['grammar']    = 0; // placeholder

        $skillProgress = [];
        foreach ($skillBands as $skill => $band) {
            $skillProgress[$skill] = round($band / 9 * 100);
        }

        $skillDetails = [];
        foreach (array_keys($skillBands) as $skill) {
            $courseTitle = null;

            if (in_array($skill, ['listening', 'reading', 'writing', 'speaking'], true)) {
                $latestSkillAttempt = IeltsTestAttempt::query()
                    ->where('user_id', $user->id)
                    ->whereNotNull($skill . '_band')
                    ->with(['test:id,title'])
                    ->orderBy('completed_at', 'desc')
                    ->first();

                $courseTitle = $latestSkillAttempt->test->title ?? null;
            } elseif ($skill === 'vocabulary') {
                $courseTitle = 'Dictionary Practice';
            } elseif ($skill === 'grammar') {
                $courseTitle = 'Grammar Course';
            }

            $skillDetails[$skill] = [
                'band' => (float) ($skillBands[$skill] ?? 0),
                'progress' => (int) ($skillProgress[$skill] ?? 0),
                'course' => $courseTitle,
            ];
        }

        // Weakest first
        $weakPointsSorted = collect($skillBands)->sortBy(fn($v) => $v)->keys()->toArray();

        $weakPointInsights = $this->buildWeakPointInsights($user);

        $activityData = $this->buildSkillActivityChart($user);

        $userOverall = $latestAttempt ? (float)($latestAttempt->overall_band ?? 0) : 0;

        $radarData = [
            'labels' => ['Listening', 'Reading', 'Writing', 'Speaking', 'Overall'],
            'data'   => [
                $skillBands['listening'],
                $skillBands['reading'],
                $skillBands['writing'],
                $skillBands['speaking'],
                $userOverall > 0 ? $userOverall : round(collect($skillBands)->only(['listening', 'reading', 'writing', 'speaking'])->filter(fn ($band) => $band > 0)->avg() ?: 0, 1),
            ],
        ];

        $topStudents = IeltsTestAttempt::with('user')
            ->whereNotNull('completed_at')
            ->whereNotNull('overall_band')
            ->select('user_id', DB::raw('MAX(overall_band) as best_band'))
            ->groupBy('user_id')
            ->orderBy('best_band', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($item) => ['user' => $item->user, 'best_band' => $item->best_band]);

        $userRank    = IeltsTestAttempt::whereNotNull('completed_at')
            ->whereNotNull('overall_band')
            ->select('user_id', DB::raw('MAX(overall_band) as best_band'))
            ->groupBy('user_id')
            ->havingRaw('MAX(overall_band) > ?', [$userOverall])
            ->get()
            ->count() + 1;

        $streak = $this->calculateLearningStreak($user);

        return [
            'latestAttempt' => $latestAttempt,
            'skillBands'    => $skillBands,
            'skillProgress' => $skillProgress,
            'skillDetails'  => $skillDetails,
            'weakPoints'    => $weakPointsSorted,
            'weakPointItems' => $weakPointInsights['items'],
            'weakPointPreviewItems' => $weakPointInsights['previewItems'],
            'weakPointGroupedItems' => $weakPointInsights['groupedItems'],
            'weakPointCountsBySkill' => $weakPointInsights['countsBySkill'],
            'weakPointTotalItems' => $weakPointInsights['totalItems'],
            'radarData'     => $radarData,
            'activityData'  => $activityData,
            'topStudents'   => $topStudents,
            'userRank'      => $userRank,
            'streak'        => $streak,
            'overallBand'   => $userOverall,
            'recentFeedbacks' => $recentFeedbacks,
        ];
    }

    private function buildWeakPointInsights($user): array
    {
        $skillLabels = [
            'listening' => 'Listening',
            'reading' => 'Reading',
            'writing' => 'Writing',
            'speaking' => 'Speaking',
            'vocabulary' => 'Vocabulary',
            'grammar' => 'Grammar',
        ];

        $lessonUrls = [
            'listening' => '/panel/courses/purchases?skill=listening',
            'reading' => '/panel/courses/purchases?skill=reading',
            'writing' => '/panel/courses/purchases?skill=writing',
            'speaking' => '/panel/courses/purchases?skill=speaking',
            'vocabulary' => '/panel/dictionary',
            'grammar' => '/panel/courses/purchases?skill=grammar',
        ];

        $practiceUrls = [
            'listening' => '/panel/ielts-tests/practice?skill=listening',
            'reading' => '/panel/ielts-tests/practice?skill=reading',
            'writing' => '/panel/ielts-tests/practice?skill=writing',
            'speaking' => '/panel/ielts-tests/practice?skill=speaking',
            'vocabulary' => '/panel/dictionary/flashcards',
            'grammar' => '/panel/quizzes/opens',
        ];

        $items = collect();

        $ieltsWrongAnswers = \App\Models\IeltsTestAnswer::query()
            ->where('is_correct', false)
            ->whereHas('attempt', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereNotNull('completed_at');
            })
            ->with([
                'attempt:id,test_id,user_id,completed_at',
                'attempt.test:id,title',
                'question:id,section_id,question_group_id,question_text,correct_answer',
                'question.section:id,skill',
                'question.questionGroup:id,title,skill',
            ])
            ->orderBy('answered_at', 'desc')
            ->limit(80)
            ->get();

        foreach ($ieltsWrongAnswers as $answer) {
            $question = $answer->question;
            $attempt = $answer->attempt;

            if (!$question || !$attempt) {
                continue;
            }

            $skill = $question->section->skill
                ?? $question->questionGroup->skill
                ?? null;

            $skillLabel = $skillLabels[$skill] ?? 'General';

            $questionText = Str::limit(trim(strip_tags((string) ($question->question_text ?? ''))), 140);
            $topic = trim((string) ($question->questionGroup->title ?? ''));
            if ($topic === '') {
                $topic = $questionText !== '' ? $questionText : 'IELTS Question';
            }

            $recommendations = [];

            if (!empty($lessonUrls[$skill])) {
                $recommendations[] = [
                    'label' => 'Review ' . $skillLabel . ' lessons',
                    'url' => $lessonUrls[$skill],
                    'kind' => 'lesson',
                ];
            }

            if (!empty($practiceUrls[$skill])) {
                $recommendations[] = [
                    'label' => 'Practice more ' . $skillLabel,
                    'url' => $practiceUrls[$skill],
                    'kind' => 'practice',
                ];
            }

            $recommendations[] = [
                'label' => 'Review this test attempt',
                'url' => '/panel/ielts-tests/attempt/' . $attempt->id . '/review',
                'kind' => 'review',
            ];

            $items->push([
                'source' => 'ielts',
                'source_label' => 'IELTS Test/Practice',
                'skill' => $skill,
                'skill_label' => $skillLabel,
                'topic' => $topic,
                'question' => $questionText,
                'your_answer' => $this->formatWeakPointAnswer($answer->answer_text),
                'correct_answer' => $this->formatWeakPointAnswer($question->correct_answer),
                'test_title' => $attempt->test->title ?? 'IELTS Test',
                'occurred_at' => (int) ($answer->answered_at ?? $attempt->completed_at ?? 0),
                'recommendations' => $recommendations,
            ]);
        }

        $quizResults = QuizzesResult::query()
            ->where('user_id', $user->id)
            ->whereNotNull('results')
            ->with(['quiz:id'])
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        $quizQuestionIds = [];
        foreach ($quizResults as $quizResult) {
            $decoded = json_decode($quizResult->results, true);

            if (!is_array($decoded)) {
                continue;
            }

            foreach ($decoded as $questionId => $resultRow) {
                if (is_numeric($questionId)) {
                    $quizQuestionIds[] = (int) $questionId;
                }
            }
        }

        $quizQuestionIds = array_values(array_unique($quizQuestionIds));

        $quizQuestions = QuizzesQuestion::query()
            ->whereIn('id', $quizQuestionIds)
            ->get()
            ->keyBy('id');

        foreach ($quizResults as $quizResult) {
            $decoded = json_decode($quizResult->results, true);

            if (!is_array($decoded)) {
                continue;
            }

            foreach ($decoded as $questionId => $resultRow) {
                if (!is_numeric($questionId) || !is_array($resultRow)) {
                    continue;
                }

                if (($resultRow['status'] ?? null) !== false) {
                    continue;
                }

                if (!empty($resultRow['manual_review'])) {
                    continue;
                }

                $questionId = (int) $questionId;
                $question = $quizQuestions->get($questionId);

                $skill = $this->mapQuizQuestionTypeToSkill($question->type ?? null);
                $skillLabel = $skillLabels[$skill] ?? 'General';

                $topic = $question ? (string) ($question->type_label ?? 'Quiz Question') : 'Quiz Question';
                $prompt = $question ? ($question->prompt_text ?: $question->title) : '';

                $recommendations = [
                    [
                        'label' => 'Review this quiz',
                        'url' => '/panel/quizzes/' . $quizResult->quiz_id . '/overview',
                        'kind' => 'lesson',
                    ],
                    [
                        'label' => 'Retake this quiz',
                        'url' => '/panel/quizzes/' . $quizResult->quiz_id . '/start',
                        'kind' => 'practice',
                    ],
                ];

                if (!empty($lessonUrls[$skill])) {
                    $recommendations[] = [
                        'label' => 'Study more ' . $skillLabel,
                        'url' => $lessonUrls[$skill],
                        'kind' => 'lesson',
                    ];
                }

                $items->push([
                    'source' => 'quiz',
                    'source_label' => 'Lesson Quiz/Homework',
                    'skill' => $skill,
                    'skill_label' => $skillLabel,
                    'topic' => $topic,
                    'question' => Str::limit(trim(strip_tags((string) $prompt)), 140),
                    'your_answer' => $this->formatWeakPointAnswer($resultRow['answer'] ?? null),
                    'correct_answer' => $this->resolveQuizCorrectAnswer($question),
                    'test_title' => $quizResult->quiz->title ?? 'Quiz',
                    'occurred_at' => (int) ($quizResult->created_at ?? 0),
                    'recommendations' => $recommendations,
                ]);
            }
        }

        $allItems = $items
            ->sortByDesc(fn($item) => (int) ($item['occurred_at'] ?? 0))
            ->values();

        $countsBySkill = [
            'listening' => 0,
            'reading' => 0,
            'writing' => 0,
            'speaking' => 0,
            'vocabulary' => 0,
            'grammar' => 0,
            'general' => 0,
        ];

        foreach ($allItems as $item) {
            $skill = $item['skill'] ?? 'general';

            if (!array_key_exists($skill, $countsBySkill)) {
                $skill = 'general';
            }

            $countsBySkill[$skill]++;
        }

        return [
            'items' => $allItems->take(60)->values()->all(),
            'previewItems' => $allItems->take(4)->values()->all(),
            'groupedItems' => $allItems->groupBy(function ($item) {
                $skill = $item['skill'] ?? 'general';
                return $skill ?: 'general';
            })->toArray(),
            'countsBySkill' => $countsBySkill,
            'totalItems' => $allItems->count(),
        ];
    }

    private function formatWeakPointAnswer($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_array($value)) {
            $value = implode(', ', array_map(function ($item) {
                return is_scalar($item) ? (string) $item : json_encode($item);
            }, $value));
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            if ($trimmed === '') {
                return '';
            }

            $decoded = json_decode($trimmed, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $flat = collect($decoded)
                    ->flatten(2)
                    ->map(function ($item) {
                        return is_scalar($item) ? trim((string) $item) : '';
                    })
                    ->filter()
                    ->implode(', ');

                if ($flat !== '') {
                    return Str::limit($flat, 140);
                }
            }

            return Str::limit(strip_tags($trimmed), 140);
        }

        return Str::limit(strip_tags((string) $value), 140);
    }

    private function resolveQuizCorrectAnswer($question): string
    {
        if (!$question) {
            return '';
        }

        if ($question->isChoiceQuestion()) {
            $correctAnswers = $question->quizzesQuestionsAnswers()
                ->where('correct', true)
                ->get()
                ->pluck('title')
                ->filter()
                ->toArray();

            return $this->formatWeakPointAnswer($correctAnswers);
        }

        if ($question->isTextQuestion() || $question->isMatchingQuestion()) {
            $correct = data_get($question->question_data, 'correct_answer');

            if (empty($correct)) {
                $correct = data_get($question->question_data, 'correct_answers');
            }

            if (empty($correct)) {
                $correct = $question->correct;
            }

            return $this->formatWeakPointAnswer($correct);
        }

        return $this->formatWeakPointAnswer($question->correct);
    }

    private function mapQuizQuestionTypeToSkill(?string $type): ?string
    {
        if (!$type) {
            return null;
        }

        $writingTypes = ['descriptive', 'rewrite_sentence'];
        $readingTypes = [
            'multiple',
            'true_false_not_given',
            'yes_no_not_given',
            'matching_headings',
            'matching_information',
            'matching_features',
            'matching_sentence_endings',
            'sentence_completion',
            'short_answer',
            'fill_blank',
        ];

        if (in_array($type, $writingTypes, true)) {
            return 'writing';
        }

        if (in_array($type, $readingTypes, true)) {
            return 'reading';
        }

        return null;
    }

    private function buildSkillActivityChart($user): array
    {
        $labels   = [];
        $skillMap = ['listening' => [], 'reading' => [], 'writing' => [], 'speaking' => []];

        for ($i = 6; $i >= 0; $i--) {
            $day    = Carbon::now()->subDays($i);
            $labels[] = $day->format('j/n');
            $start  = $day->copy()->startOfDay()->timestamp;
            $end    = $day->copy()->endOfDay()->timestamp;

            $attempts = IeltsTestAttempt::where('user_id', $user->id)
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('listening_finished_at', [$start, $end])
                      ->orWhereBetween('reading_finished_at',  [$start, $end])
                      ->orWhereBetween('writing_finished_at',  [$start, $end])
                      ->orWhereBetween('speaking_finished_at', [$start, $end]);
                })
                ->get([
                    'completed_at',
                    'listening_finished_at',
                    'reading_finished_at',
                    'writing_finished_at',
                    'speaking_finished_at',
                    'listening_band',
                    'reading_band',
                    'writing_band',
                    'speaking_band',
                ]);

            foreach (array_keys($skillMap) as $skill) {
                $mins = 0;
                foreach ($attempts as $a) {
                    $ft = $a->{$skill . '_finished_at'};
                    $band = (float) ($a->{$skill . '_band'} ?? 0);

                    if ($ft && $ft >= $start && $ft <= $end) {
                        $mins += 1;
                        continue;
                    }

                    if (!$ft && $band > 0 && (int) ($a->completed_at ?? 0) >= $start && (int) ($a->completed_at ?? 0) <= $end) {
                        $mins += 1;
                    }
                }
                $skillMap[$skill][] = $mins;
            }
        }

        return [
            'labels' => $labels,
            'series' => [
                ['name' => 'Listening', 'data' => $skillMap['listening']],
                ['name' => 'Reading',   'data' => $skillMap['reading']],
                ['name' => 'Writing',   'data' => $skillMap['writing']],
                ['name' => 'Speaking',  'data' => $skillMap['speaking']],
            ],
        ];
    }

    private function calculateLearningStreak($user): int
    {
        $streak = 0;
        $day    = Carbon::now()->startOfDay();

        for ($i = 0; $i <= 365; $i++) {
            $start = $day->copy()->startOfDay()->timestamp;
            $end   = $day->copy()->endOfDay()->timestamp;

            $has = IeltsTestAttempt::where('user_id', $user->id)
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('completed_at',           [$start, $end])
                      ->orWhereBetween('listening_finished_at', [$start, $end])
                      ->orWhereBetween('reading_finished_at',   [$start, $end]);
                })
                ->exists();

            if (!$has) {
                if ($i === 0) {
                    $day->subDay();
                    continue;
                }
                break;
            }

            $streak++;
            $day->subDay();
        }

        return $streak;
    }

    private function getInstructorDashboardData(Request $request, $user): array
    {
        $data = [];

        $userWebinars = Webinar::query()
            ->where(function (Builder $query) use ($user) {
                $query->where('webinars.creator_id', $user->id);
                $query->orWhere('webinars.teacher_id', $user->id);
            })
            ->leftJoin('sales', function ($join) use ($user) {
                $join->on('sales.webinar_id', '=', 'webinars.id');
                $join->whereNull('sales.refund_at');
                //$join->where('sales.amount', '>', '0');
            })
            ->select('webinars.*',
                DB::raw('count(sales.webinar_id) as sales_count'),
                DB::raw('sum(sales.total_amount) as sales_amount')
            )
            ->groupBy('webinars.id')
            ->orderBy('sales_count', 'desc')
            ->get();

        $userWebinarsIds = $userWebinars->pluck('id')->toArray();

        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');


        // hello_box
        $data['helloBox'] = $this->getInstructorHelloBoxData($user, $meetingIds, $userWebinars);

        // Courses Overview
        $data['coursesOverview'] = $this->getInstructorCoursesOverviewData($user, $userWebinars);

        // Sales Overview
        $data['salesOverview'] = $this->getInstructorSalesOverviewData($user, $userWebinarsIds);

        // Pending Student Assignments
        $data['pendingStudentAssignments'] = $this->getInstructorStudentAssignmentsData($user, $userWebinarsIds);

        // Registration Plan
        $userPackage = new UserPackage($user);
        $data['registrationPlan'] = $userPackage->getPackage();

        // Current Balance
        $data['authUserBalanceCharge'] = $user->getAccountingCharge();
        $data['authUserReadyPayout'] = $user->getPayout();

        // Noticeboard
        $data['unreadNoticeboards'] = $user->getUnreadNoticeboards();

        // Support Messages
        $data['supportMessages'] = $this->getInstructorSupportMessagesData($user, $userWebinarsIds);

        // Visitors Statistics
        $data['visitorsStatistics'] = $this->getInstructorVisitorsStatisticsData($user, $userWebinarsIds);

        if ($user->isTeacher()) {
            // Upcoming Live Sessions
            $data['upcomingLiveSessions'] = $this->getInstructorUpcomingLiveSessionsData($user, $userWebinarsIds);

            // Review Student Quizzes
            $data['reviewStudentQuizzes'] = $this->getInstructorReviewStudentQuizzes($user, $userWebinarsIds);

            // Open Meetings
            $data['openMeetings'] = $this->getInstructorOpenMeetingsData($user, $userWebinarsIds);

            // ── IELTS Teacher Dashboard blocks ──────────────────────────
            $data['teacherRating']             = $this->getTeacherAverageRating($user, $userWebinarsIds);
            $data['teacherGradingChart']       = $this->getTeacherGradingChartData($user);
            $data['teacherSpeakingQueue']      = $this->getTeacherSpeakingQueue($user);
            $data['teacherWritingQueue']       = $this->getTeacherWritingQueue($user);
            $data['teacherStudentsSupport']    = $this->getTeacherStudentsNeedingSupport($user, $userWebinarsIds);

        } elseif ($user->isAdmin()) {
            // Admin: Show organization-like features
            // Top Instructors
            $data['topInstructors'] = $this->getOrganizationTopInstructorsData($user);

            // Top Students
            $data['topStudents'] = $this->getOrganizationTopStudentsData($user);

            // Organization Sales Dashboard
            $data['orgSalesDashboard'] = $this->getOrganizationSalesDashboardData($user);
        }


        return $data;
    }

    private function handleDashboardUpcomingEvents($user)
    {
        $eventsController = (new EventsController());
        $eventsController->user = $user;
        $eventsController->userBoughtWebinarsIds = $user->getPurchasedCoursesIds();

        $eventsWithTimestamp = $eventsController->getAllEventsReturnWithTimestamp();
        $getUpcomingEvents = $eventsController->getUpcomingEvents(2);
        $upcomingEvents = $getUpcomingEvents['upcomingEvents'];
        $totalEvents = $getUpcomingEvents['total'];

        return [
            'upcomingEvents' => $upcomingEvents,
            'totalEvents' => $totalEvents,
            'eventsWithTimestamp' => $eventsWithTimestamp,
        ];
    }

    private function showGiftModal($user)
    {
        $gift = Gift::query()->where('email', $user->email)
            ->where('status', 'active')
            ->where('viewed', false)
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->first();

        if (!empty($gift)) {
            $gift->update([
                'viewed' => true
            ]);

            $data = [
                'gift' => $gift
            ];

            $result = (string)view()->make('design_1.web.gift.modal.show_to_receipt', $data);
            $result = str_replace(array("\r\n", "\n", "  "), '', $result);

            return $result;
        }

        return null;
    }

}


