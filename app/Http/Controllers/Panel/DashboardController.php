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
use App\Models\Support;
use App\Models\UserMeta;
use App\Models\UserWordProgress;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        $wordCount = AcademicWordListWord::count();
        if ($wordCount > 0) {
            $dayIndex        = (int) date('z') % $wordCount;
            $data['wordOfDay'] = AcademicWordListWord::skip($dayIndex)->first();
        } else {
            $data['wordOfDay'] = null;
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

    // ─── IELTS Data Helpers ─────────────────────────────────────────────────────

    private function getStudentIeltsData($user): array
    {
        $latestAttempt = IeltsTestAttempt::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->first();

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

        // Weakest first
        $weakPointsSorted = collect($skillBands)->sortBy(fn($v) => $v)->keys()->toArray();

        $activityData = $this->buildSkillActivityChart($user);

        $radarData = [
            'labels' => ['Listening', 'Reading', 'Writing', 'Speaking'],
            'data'   => [
                $skillBands['listening'],
                $skillBands['reading'],
                $skillBands['writing'],
                $skillBands['speaking'],
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

        $userOverall = $latestAttempt ? (float)($latestAttempt->overall_band ?? 0) : 0;
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
            'weakPoints'    => $weakPointsSorted,
            'radarData'     => $radarData,
            'activityData'  => $activityData,
            'topStudents'   => $topStudents,
            'userRank'      => $userRank,
            'streak'        => $streak,
            'overallBand'   => $userOverall,
        ];
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
                ->get();

            foreach (array_keys($skillMap) as $skill) {
                $mins = 0;
                foreach ($attempts as $a) {
                    $ft = $a->{$skill . '_finished_at'};
                    if ($ft && $ft >= $start && $ft <= $end) {
                        $mins += max(5, (int) round(($ft - ($a->started_at ?? $ft)) / 60));
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


