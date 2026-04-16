<?php

namespace App\Http\Controllers\Admin\traits;

use App\Models\Accounting;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\FormSubmission;
use App\Models\IeltsGradingRating;
use App\Models\IeltsTestAttempt;
use App\Models\Meeting;
use App\Models\ReserveMeeting;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Support;
use App\Models\SupportConversation;
use App\Models\TimeSpentOnCourse;
use App\Models\UserMeta;
use App\Models\Webinar;
use App\Models\WebinarAssignmentHistory;
use App\Models\WebinarReport;
use App\Models\WebinarReview;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Calculation\Web;

trait DashboardTrait
{
    public function dailySalesTypeStatistics()
    {
        $this->authorize('admin_general_dashboard_daily_sales_statistics');

        $beginOfDay = strtotime("today", time());
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;

        $webinarsSales = Sale::whereNull('refund_at')
            ->where('type', Sale::$webinar)
            ->whereBetween('created_at', [$beginOfDay, $endOfDay])
            ->whereHas('webinar', function ($query) {
                $query->where('type', Webinar::$webinar);
            })->count();

        $courseSales = Sale::whereNull('refund_at')
            ->where('type', Sale::$webinar)
            ->whereBetween('created_at', [$beginOfDay, $endOfDay])
            ->whereHas('webinar', function ($query) {
                $query->where('type', Webinar::$course);
            })->count();

        $appointmentSales = Sale::whereNull('refund_at')
            ->where('type', Sale::$meeting)
            ->whereBetween('created_at', [$beginOfDay, $endOfDay])
            ->count();

        $allSales = Sale::whereNull('refund_at')
            ->whereIn('type', [Sale::$webinar, Sale::$meeting])
            ->whereBetween('created_at', [$beginOfDay, $endOfDay])
            ->count();


        return [
            'webinarsSales' => $webinarsSales,
            'courseSales' => $courseSales,
            'appointmentSales' => $appointmentSales,
            'allSales' => $allSales,
        ];
    }

    public function getIncomeStatistics()
    {
        $this->authorize('admin_general_dashboard_income_statistics');

        $dateStartAndEnd = $this->getAllDateStartAndEnd();

        $beginOfDay = $dateStartAndEnd['today']['start'];
        $endOfDay = $dateStartAndEnd['today']['end'];

        $beginOfMonth = $dateStartAndEnd['month']['start'];
        $endOfMonth = $dateStartAndEnd['month']['end'];

        $beginOfYear = $dateStartAndEnd['year']['start'];
        $endOfYear = $dateStartAndEnd['year']['end'];

        $totalSales = $this->getIncomes();

        $todaySales = $this->getIncomes($beginOfDay, $endOfDay);

        $monthSales = $this->getIncomes($beginOfMonth, $endOfMonth);

        $yearSales = $this->getIncomes($beginOfYear, $endOfYear);

        return [
            'totalSales' => $totalSales,
            'todaySales' => $todaySales,
            'monthSales' => $monthSales,
            'yearSales' => $yearSales,
        ];
    }

    private function getIncomes($from = null, $to = null)
    {
        $query = Accounting::where(function ($query) {
            $query->where('system', true)
                ->orWhere('tax', true);
        });

        $query = fromAndToDateFilter($from, $to, $query, 'created_at', false);

        $additions = deepClone($query)
            ->where('type', Accounting::$addiction)
            ->sum('amount');

        $deductions = deepClone($query)
            ->where('type', Accounting::$deduction)
            ->sum('amount');

        $income = $additions - $deductions;
        return $income > 0 ? $income : 0;
    }

    private function getAllDateStartAndEnd()
    {
        $time = time();
        $beginOfDay = strtotime("today", $time);
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;

        $monday = strtotime('next Monday -1 week');
        $beginOfWeek = date('w', $monday) == date('w') ? strtotime(date("Y-m-d", $monday) . " +7 days") : $monday;
        $endOfWeek = strtotime(date("Y-m-d", $beginOfWeek) . " +7 days") - 1;

        $beginOfMonth = strtotime(date('Y-m-01', $time));// First day of the month.
        $endOfMonth = strtotime(date('Y-m-t', $time));// Last day of the month.

        $beginOfYear = strtotime(date('Y-01-01', $time));// First day of the year.
        $endOfYear = strtotime(date('Y-m-d', strtotime('12/31'))); // Last day of the year.

        return [
            'today' => [
                'start' => $beginOfDay,
                'end' => $endOfDay,
            ],
            'week' => [
                'start' => $beginOfWeek,
                'end' => $endOfWeek,
            ],
            'month' => [
                'start' => $beginOfMonth,
                'end' => $endOfMonth,
            ],
            'year' => [
                'start' => $beginOfYear,
                'end' => $endOfYear,
            ],
        ];
    }

    public function getTotalSalesStatistics()
    {
        $dateStartAndEnd = $this->getAllDateStartAndEnd();

        $beginOfDay = $dateStartAndEnd['today']['start'];
        $endOfDay = $dateStartAndEnd['today']['end'];

        $beginOfMonth = $dateStartAndEnd['month']['start'];
        $endOfMonth = $dateStartAndEnd['month']['end'];

        $beginOfYear = $dateStartAndEnd['year']['start'];
        $endOfYear = $dateStartAndEnd['year']['end'];

        $totalSales = Sale::whereNull('refund_at')->count();

        $todaySales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$beginOfDay, $endOfDay])
            ->count();

        $monthSales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$beginOfMonth, $endOfMonth])
            ->count();

        $yearSales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$beginOfYear, $endOfYear])
            ->count();

        return [
            'totalSales' => $totalSales,
            'todaySales' => $todaySales,
            'monthSales' => $monthSales,
            'yearSales' => $yearSales,
        ];
    }

    public function getNewSalesCount()
    {
        $this->authorize('admin_general_dashboard_new_sales');

        return Sale::whereNull('refund_at')
            ->whereDoesntHave('saleLog')
            ->count();
    }

    public function getNewCommentsCount()
    {
        $this->authorize('admin_general_dashboard_new_comments');

        return Comment::where('status', 'pending')
            ->count();
    }

    public function getNewTicketsCount()
    {
        $this->authorize('admin_general_dashboard_new_tickets');

        $time = time();
        $beginOfDay = strtotime("today", $time);
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;

        return Support::whereNotNull('department_id')
            ->whereIn('status', ['replied', 'open'])
            ->whereBetween('updated_at', [$beginOfDay, $endOfDay])
            ->count();
    }

    public function getPendingReviewCount()
    {
        $this->authorize('admin_general_dashboard_new_reviews');

        return Webinar::where('status', 'pending')
            ->count();
    }

    public function getMonthAndYearSalesChart($type = 'month_of_year')
    {
        $labels = [];
        $data = [];

        if ($type == 'day_of_month') {

            for ($day = 1; $day <= 31; $day++) {
                $startDay = strtotime(date('Y-m-' . $day));
                $endDay = strtotime('-1 second', strtotime('+1 day', $startDay));

                $labels[] = str_pad($day, 2, 0, STR_PAD_LEFT);

                $amount = Sale::whereNull('refund_at')
                    ->whereBetween('created_at', [$startDay, $endDay])
                    ->sum('total_amount');
                $data[] = round($amount, 2);
            }

        } elseif ($type == 'month_of_year') {
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create(date('Y'), $month);

                $start_date = $date->timestamp;
                $end_date = $date->copy()->endOfMonth()->timestamp;

                $labels[] = trans('panel.month_' . $month);

                $amount = Sale::whereNull('refund_at')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->sum('total_amount');

                $data[] = round($amount, 2);
            }
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function getMonthAndYearSalesChartStatistics()
    {
        $dateStartAndEnd = $this->getAllDateStartAndEnd();

        $beginOfDay = $dateStartAndEnd['today']['start'];
        $endOfDay = $dateStartAndEnd['today']['end'];

        $beginOfWeek = $dateStartAndEnd['week']['start'];
        $endOfWeek = $dateStartAndEnd['week']['end'];

        $beginOfMonth = $dateStartAndEnd['month']['start'];
        $endOfMonth = $dateStartAndEnd['month']['end'];

        $beginOfYear = $dateStartAndEnd['year']['start'];
        $endOfYear = $dateStartAndEnd['year']['end'];

        $lastDayStart = $beginOfDay - 24 * 60 * 60;
        $lastDayEnd = $endOfDay - 24 * 60 * 60;

        $lastWeekStart = $beginOfWeek - 7 * 24 * 60 * 60;
        $lastWeekEnd = $endOfWeek - 7 * 24 * 60 * 60;

        $time = time();
        $lastMonthStart = strtotime(date('Y-m-01', strtotime('last month', $time))); // First day of the last month.
        $lastMonthEnd = strtotime(date('Y-m-t', strtotime('last month', $time))); // Last day of the last month.

        $lastYearStart = $beginOfYear - 365 * 24 * 60 * 60;
        $lastYearEnd = $endOfYear - 365 * 24 * 60 * 60;


        $todaySales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$beginOfDay, $endOfDay])
            ->sum('total_amount');

        $lastDaySales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$lastDayStart, $lastDayEnd])
            ->sum('total_amount');

        $weekSales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$beginOfWeek, $endOfWeek])
            ->sum('total_amount');

        $lastWeekSales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('total_amount');

        $monthSales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$beginOfMonth, $endOfMonth])
            ->sum('total_amount');

        $lastMonthSales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('total_amount');

        $yearSales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$beginOfYear, $endOfYear])
            ->sum('total_amount');

        $lastYearSales = Sale::whereNull('refund_at')
            ->whereBetween('created_at', [$lastYearStart, $lastYearEnd])
            ->sum('total_amount');

        return [
            'todaySales' => [
                'amount' => $todaySales,
                'grow_percent' => $this->getGrowPercent($lastDaySales, $todaySales),
            ],
            'weekSales' => [
                'amount' => $weekSales,
                'grow_percent' => $this->getGrowPercent($lastWeekSales, $weekSales),
            ],
            'monthSales' => [
                'amount' => $monthSales,
                'grow_percent' => $this->getGrowPercent($lastMonthSales, $monthSales),
            ],
            'yearSales' => [
                'amount' => $yearSales,
                'grow_percent' => $this->getGrowPercent($lastYearSales, $yearSales),
            ],
        ];
    }

    private function getGrowPercent($last, $new)
    {
        $percent = trans('admin/main.no_previous_value');
        $status = 'up';

        if ($last != 0) {
            $tmp = ($new - $last);
            $abs = abs($last);

            $res = ($tmp / $abs * 100);

            $percent = round($res, 3) . '%';
            $status = $res > 0 ? 'up' : 'down';
        }

        return [
            'percent' => $percent,
            'status' => $status
        ];
    }

    public function getRecentComments()
    {
        $this->authorize('admin_general_dashboard_recent_comments');

        return Comment::orderBy('created_at', 'desc')->limit(6)->get();
    }

    public function getRecentTickets()
    {
        $this->authorize('admin_general_dashboard_recent_tickets');

        $tickets = Support::whereNotNull('department_id')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingReply = Support::whereNotNull('department_id')
            ->whereIn('status', ['open', 'replied'])
            ->count();

        return [
            'tickets' => $tickets,
            'pendingReply' => $pendingReply,
        ];
    }

    public function getRecentWebinars()
    {
        $this->authorize('admin_general_dashboard_recent_webinars');


        $webinars = Webinar::where('type', Webinar::$webinar)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingReviews = Webinar::where('type', Webinar::$webinar)
            ->where('status', 'pending')
            ->count();

        return [
            'webinars' => $webinars,
            'pendingReviews' => $pendingReviews,
        ];
    }

    public function getRecentCourses()
    {
        $this->authorize('admin_general_dashboard_recent_courses');


        $courses = Webinar::where('type', Webinar::$course)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingReviews = Webinar::where('type', Webinar::$course)
            ->where('status', 'pending')
            ->count();

        return [
            'courses' => $courses,
            'pendingReviews' => $pendingReviews,
        ];
    }

    public function usersStatisticsChart()
    {
        $labels = [];
        $data = [];

        for ($day = 1; $day <= 31; $day++) {
            $startDay = strtotime(date('Y-m-' . $day));
            $endDay = strtotime('-1 second', strtotime('+1 day', $startDay));

            $labels[] = str_pad($day, 2, 0, STR_PAD_LEFT);

            $count = User::whereBetween('created_at', [$startDay, $endDay])
                ->count();

            $data[] = ($count > 0) ? $count : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function getManagerOrganizationDashboardData(): array
    {
        $nowCarbon = Carbon::now();
        $now = $nowCarbon->timestamp;
        $yearStart = $nowCarbon->copy()->startOfYear()->timestamp;
        $monthStart = $nowCarbon->copy()->subDays(30)->startOfDay()->timestamp;
        $currentMonthStart = $nowCarbon->copy()->startOfMonth()->timestamp;
        $monthsElapsed = max(1, $nowCarbon->month);

        $adminIds = User::query()
            ->where('role_name', Role::$admin)
            ->pluck('id')
            ->toArray();

        $leadUsers = User::query()
            ->where('role_name', Role::$user)
            ->whereBetween('created_at', [$yearStart, $now])
            ->count();

        $adminMeetingIds = [];
        if (!empty($adminIds)) {
            $adminMeetingIds = Meeting::query()
                ->whereIn('creator_id', $adminIds)
                ->pluck('id')
                ->toArray();
        }

        $leadsConsulted = 0;
        if (!empty($adminMeetingIds)) {
            $leadsConsulted = ReserveMeeting::query()
                ->whereIn('meeting_id', $adminMeetingIds)
                ->whereBetween('created_at', [$yearStart, $now])
                ->distinct('user_id')
                ->count('user_id');
        }

        $salesBaseQuery = Sale::query()
            ->whereBetween('created_at', [$yearStart, $now])
            ->where(function ($query) {
                $query->whereNotNull('webinar_id')
                    ->orWhereNotNull('bundle_id')
                    ->orWhereNotNull('meeting_id');
            });

        if (!empty($adminIds)) {
            $salesBaseQuery->whereIn('seller_id', $adminIds);
        } else {
            $salesBaseQuery->whereRaw('1 = 0');
        }

        $leadsPaid = (clone $salesBaseQuery)
            ->whereNull('refund_at')
            ->distinct('buyer_id')
            ->count('buyer_id');

        $leadsRejected = (clone $salesBaseQuery)
            ->whereNotNull('refund_at')
            ->count();

        $rejectedLeadsList = (clone $salesBaseQuery)
            ->whereNotNull('refund_at')
            ->with([
                'buyer' => function ($query) {
                    $query->select('id', 'full_name', 'email', 'avatar', 'avatar_settings');
                },
                'seller' => function ($query) {
                    $query->select('id', 'full_name');
                },
                'webinar' => function ($query) {
                    $query->select('id', 'title');
                },
            ])
            ->orderBy('refund_at', 'desc')
            ->limit(30)
            ->get();

        $weeklyLabels = [];
        $weeklyRevenueActual = [];
        $weeklyRevenueKpi = [];
        $weeklyRejectedDeals = [];
        $weeklyClosedDeals = [];
        $weeklyLeads = [];

        for ($i = 3; $i >= 0; $i--) {
            $weekStart = $nowCarbon->copy()->startOfWeek()->subWeeks($i)->startOfDay();
            $weekEnd = $weekStart->copy()->endOfWeek()->endOfDay();
            $weekStartTs = $weekStart->timestamp;
            $weekEndTs = $weekEnd->timestamp;

            $weeklyLabels[] = $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m');

            $revenueQuery = Sale::query()
                ->whereNull('refund_at')
                ->whereBetween('created_at', [$weekStartTs, $weekEndTs]);

            if (!empty($adminIds)) {
                $revenueQuery->whereIn('seller_id', $adminIds);
            } else {
                $revenueQuery->whereRaw('1 = 0');
            }

            $actualRevenue = (float)$revenueQuery->sum('total_amount');
            $weeklyRevenueActual[] = round($actualRevenue, 2);

            $targetRevenue = 0;
            if (Schema::hasTable('sales_kpis') && !empty($adminIds)) {
                $targetRevenue = (float)DB::table('sales_kpis')
                    ->whereIn('user_id', $adminIds)
                    ->where('period_type', 'weekly')
                    ->whereBetween('period_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                    ->sum('target_revenue');
            }
            if ($targetRevenue <= 0) {
                $targetRevenue = $actualRevenue * 1.1;
            }
            $weeklyRevenueKpi[] = round($targetRevenue, 2);

            $rejectedDealsQuery = Sale::query()
                ->whereNotNull('refund_at')
                ->whereBetween('created_at', [$weekStartTs, $weekEndTs]);
            if (!empty($adminIds)) {
                $rejectedDealsQuery->whereIn('seller_id', $adminIds);
            } else {
                $rejectedDealsQuery->whereRaw('1 = 0');
            }
            $weeklyRejectedDeals[] = $rejectedDealsQuery->count();

            $closedDealsQuery = Sale::query()
                ->whereNull('refund_at')
                ->whereBetween('created_at', [$weekStartTs, $weekEndTs])
                ->where(function ($query) {
                    $query->whereNotNull('webinar_id')
                        ->orWhereNotNull('bundle_id')
                        ->orWhereNotNull('meeting_id');
                });
            if (!empty($adminIds)) {
                $closedDealsQuery->whereIn('seller_id', $adminIds);
            } else {
                $closedDealsQuery->whereRaw('1 = 0');
            }
            $weeklyClosedDeals[] = $closedDealsQuery->distinct('buyer_id')->count('buyer_id');

            $weeklyLeads[] = User::query()
                ->where('role_name', Role::$user)
                ->whereBetween('created_at', [$weekStartTs, $weekEndTs])
                ->count();
        }

        $studentIds = User::query()
            ->where('role_name', Role::$student)
            ->pluck('id')
            ->toArray();

        $totalStudents = count($studentIds);
        $activeStudentIds = [];
        if (!empty($studentIds)) {
            $activeStudentIds = TimeSpentOnCourse::query()
                ->whereIn('user_id', $studentIds)
                ->where('entry_time', '>=', $monthStart)
                ->distinct('user_id')
                ->pluck('user_id')
                ->toArray();
        }
        $dropOffStudents = max(0, $totalStudents - count($activeStudentIds));

        $avgAssignmentGradeMonth = (float)WebinarAssignmentHistory::query()
            ->whereBetween('created_at', [$monthStart, $now])
            ->whereNotNull('grade')
            ->avg('grade');

        $aimBandRate = 0;
        if (Schema::hasTable('ielts_test_attempts')) {
            $ieltsCompleted = DB::table('ielts_test_attempts')
                ->whereBetween('completed_at', [$monthStart, $now])
                ->where('status', 'completed')
                ->whereNotNull('overall_band')
                ->count();

            $ieltsReached = DB::table('ielts_test_attempts')
                ->whereBetween('completed_at', [$monthStart, $now])
                ->where('status', 'completed')
                ->where('overall_band', '>=', 6.5)
                ->count();

            $aimBandRate = ($ieltsCompleted > 0)
                ? round(($ieltsReached / $ieltsCompleted) * 100, 1)
                : 0;
        }

        $extraCourseRate = 0;
        if (!empty($studentIds)) {
            $paidSalesPerStudent = Sale::query()
                ->whereIn('buyer_id', $studentIds)
                ->whereNull('refund_at')
                ->where(function ($query) {
                    $query->whereNotNull('webinar_id')
                        ->orWhereNotNull('bundle_id');
                });

            $buyersWithPurchase = (clone $paidSalesPerStudent)
                ->distinct('buyer_id')
                ->count('buyer_id');

            $buyersWithExtraCourse = (clone $paidSalesPerStudent)
                ->select('buyer_id', DB::raw('COUNT(*) as total_sales'))
                ->groupBy('buyer_id')
                ->havingRaw('COUNT(*) > 1')
                ->get()
                ->count();

            $extraCourseRate = ($buyersWithPurchase > 0)
                ? round(($buyersWithExtraCourse / $buyersWithPurchase) * 100, 1)
                : 0;
        }

        $mentorIds = User::query()
            ->where('role_name', Role::$teacher)
            ->pluck('id')
            ->toArray();
        $mentorCount = count($mentorIds);

        $avgStudentsPerMentor = 0;
        if (!empty($mentorIds)) {
            $mentorMeetingIds = Meeting::query()
                ->whereIn('creator_id', $mentorIds)
                ->pluck('id')
                ->toArray();

            if (!empty($mentorMeetingIds)) {
                $studentsWithMentors = ReserveMeeting::query()
                    ->whereIn('meeting_id', $mentorMeetingIds)
                    ->distinct('user_id')
                    ->count('user_id');

                $avgStudentsPerMentor = round($studentsWithMentors / max(1, $mentorCount), 1);
            }
        }

        $avgGradingWaitHours = 0;
        if (Schema::hasTable('ielts_test_attempts') && !empty($mentorIds)) {
            $writingAvgSeconds = (float)DB::table('ielts_test_attempts')
                ->whereIn('writing_graded_by', $mentorIds)
                ->whereNotNull('writing_graded_at')
                ->whereNotNull('completed_at')
                ->whereBetween('writing_graded_at', [$monthStart, $now])
                ->selectRaw('AVG(GREATEST(0, writing_graded_at - completed_at)) as avg_seconds')
                ->value('avg_seconds');

            $speakingAvgSeconds = (float)DB::table('ielts_test_attempts')
                ->whereIn('speaking_graded_by', $mentorIds)
                ->whereNotNull('speaking_graded_at')
                ->whereNotNull('completed_at')
                ->whereBetween('speaking_graded_at', [$monthStart, $now])
                ->selectRaw('AVG(GREATEST(0, speaking_graded_at - completed_at)) as avg_seconds')
                ->value('avg_seconds');

            $components = array_filter([$writingAvgSeconds, $speakingAvgSeconds], function ($value) {
                return $value > 0;
            });

            if (!empty($components)) {
                $avgGradingWaitHours = round((array_sum($components) / count($components)) / 3600, 1);
            }
        }

        $avgSupportWaitHours = 0;
        if (!empty($mentorIds)) {
            $firstMentorReplies = SupportConversation::query()
                ->whereIn('supporter_id', $mentorIds)
                ->whereBetween('created_at', [$monthStart, $now])
                ->select('support_id', DB::raw('MIN(created_at) as first_reply_at'))
                ->groupBy('support_id')
                ->get()
                ->keyBy('support_id');

            if ($firstMentorReplies->isNotEmpty()) {
                $supports = Support::query()
                    ->whereIn('id', $firstMentorReplies->keys()->toArray())
                    ->whereBetween('created_at', [$monthStart, $now])
                    ->get(['id', 'created_at']);

                $waitSeconds = [];
                foreach ($supports as $support) {
                    $reply = $firstMentorReplies->get($support->id);
                    if (!empty($reply) && !empty($reply->first_reply_at) && !empty($support->created_at)) {
                        $waitSeconds[] = max(0, ((int)$reply->first_reply_at - (int)$support->created_at));
                    }
                }

                if (!empty($waitSeconds)) {
                    $avgSupportWaitHours = round((array_sum($waitSeconds) / count($waitSeconds)) / 3600, 1);
                }
            }
        }

        $avgMentorBlogsPerMonth = 0;
        if (!empty($mentorIds) && $monthsElapsed > 0) {
            $mentorBlogCount = Blog::query()
                ->whereIn('author_id', $mentorIds)
                ->where('status', 'publish')
                ->whereBetween('created_at', [$yearStart, $now])
                ->count();

            $avgMentorBlogsPerMonth = round($mentorBlogCount / $monthsElapsed / max(1, $mentorCount), 1);
        }

        $adminCount = count($adminIds);
        $avgAdminRevenuePerMonth = 0;
        if (!empty($adminIds) && $monthsElapsed > 0) {
            $adminRevenue = Sale::query()
                ->whereIn('seller_id', $adminIds)
                ->whereNull('refund_at')
                ->whereBetween('created_at', [$yearStart, $now])
                ->sum('total_amount');

            $avgAdminRevenuePerMonth = $adminRevenue / $monthsElapsed / max(1, $adminCount);
        }

        $avgAdminCloseRatePerMonth = 0;
        if (!empty($adminIds)) {
            $adminRates = [];
            foreach ($adminIds as $adminId) {
                $personalMeetingIds = Meeting::query()
                    ->where('creator_id', $adminId)
                    ->pluck('id')
                    ->toArray();

                $consultedThisMonth = 0;
                if (!empty($personalMeetingIds)) {
                    $consultedThisMonth = ReserveMeeting::query()
                        ->whereIn('meeting_id', $personalMeetingIds)
                        ->whereBetween('created_at', [$currentMonthStart, $now])
                        ->distinct('user_id')
                        ->count('user_id');
                }

                $paidThisMonth = Sale::query()
                    ->where('seller_id', $adminId)
                    ->whereNull('refund_at')
                    ->whereBetween('created_at', [$currentMonthStart, $now])
                    ->where(function ($query) {
                        $query->whereNotNull('webinar_id')
                            ->orWhereNotNull('bundle_id')
                            ->orWhereNotNull('meeting_id');
                    })
                    ->distinct('buyer_id')
                    ->count('buyer_id');

                $adminRates[] = $consultedThisMonth > 0
                    ? min(100, round(($paidThisMonth / $consultedThisMonth) * 100, 1))
                    : 0;
            }

            if (!empty($adminRates)) {
                $avgAdminCloseRatePerMonth = round(array_sum($adminRates) / count($adminRates), 1);
            }
        }

        $avgAdminBlogsPerMonth = 0;
        if (!empty($adminIds) && $monthsElapsed > 0) {
            $adminBlogCount = Blog::query()
                ->whereIn('author_id', $adminIds)
                ->where('status', 'publish')
                ->whereBetween('created_at', [$yearStart, $now])
                ->count();

            $avgAdminBlogsPerMonth = round($adminBlogCount / $monthsElapsed / max(1, $adminCount), 1);
        }

        return [
            'summary' => [
                'leads' => $leadUsers,
                'consulted' => $leadsConsulted,
                'paid' => $leadsPaid,
                'rejected' => $leadsRejected,
            ],
            'rejectedLeadsList' => $rejectedLeadsList,
            'revenueChart' => [
                'labels' => $weeklyLabels,
                'actual' => $weeklyRevenueActual,
                'kpi' => $weeklyRevenueKpi,
            ],
            'salesChart' => [
                'labels' => $weeklyLabels,
                'rejected' => $weeklyRejectedDeals,
                'closed' => $weeklyClosedDeals,
                'leads' => $weeklyLeads,
            ],
            'activeStudent' => [
                'dropOffCount' => $dropOffStudents,
                'avgAssignmentScore' => round($avgAssignmentGradeMonth, 1),
                'aimBandRate' => $aimBandRate,
                'extraCourseRate' => $extraCourseRate,
            ],
            'activeMentor' => [
                'avgStudentsPerMentor' => $avgStudentsPerMentor,
                'avgGradingWaitHours' => $avgGradingWaitHours,
                'avgSupportWaitHours' => $avgSupportWaitHours,
                'avgBlogsPerMonth' => $avgMentorBlogsPerMonth,
            ],
            'activeAdmin' => [
                'avgRevenuePerMonth' => round($avgAdminRevenuePerMonth, 2),
                'avgCloseRatePerMonth' => $avgAdminCloseRatePerMonth,
                'avgBlogsPerMonth' => $avgAdminBlogsPerMonth,
            ],
        ];
    }

    public function getManagerBusinessDashboardData(): array
    {
        $now = Carbon::now();
        $adminUsers = User::query()
            ->where('role_name', Role::$admin)
            ->select('id', 'full_name', 'email', 'created_at')
            ->get();

        $adminIds = $adminUsers->pluck('id')->toArray();

        $chartData = [
            'week' => $this->buildManagerBusinessSeriesByWeek($adminIds, $now),
            'month' => $this->buildManagerBusinessSeriesByMonth($adminIds, $now),
            'year' => $this->buildManagerBusinessSeriesByYear($adminIds, $now),
        ];

        $adminRevenueMap = [];
        if (!empty($adminIds)) {
            $adminRevenueMap = Sale::query()
                ->whereIn('seller_id', $adminIds)
                ->whereNull('refund_at')
                ->select('seller_id', DB::raw('SUM(total_amount) as total_revenue'))
                ->groupBy('seller_id')
                ->pluck('total_revenue', 'seller_id')
                ->toArray();
        }

        $salesRepresentatives = $adminUsers
            ->map(function ($admin) use ($adminRevenueMap) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->full_name,
                    'email' => $admin->email,
                    'totalRevenue' => (float)($adminRevenueMap[$admin->id] ?? 0),
                    'registeredAt' => $admin->created_at,
                ];
            })
            ->sortByDesc('totalRevenue')
            ->values();

        return [
            'chartData' => $chartData,
            'salesRepresentatives' => $salesRepresentatives,
        ];
    }

    public function getAdminOrganizationMarketingDashboardData($user): array
    {
        $nowCarbon = Carbon::now();
        $now = $nowCarbon->timestamp;
        $yearStart = $nowCarbon->copy()->startOfYear()->timestamp;

        $prevMonthStart = $nowCarbon->copy()->startOfMonth()->subMonth()->startOfMonth()->timestamp;
        $prevMonthEnd = $nowCarbon->copy()->startOfMonth()->subMonth()->endOfMonth()->timestamp;

        $adminTeamIds = User::query()
            ->where(function ($query) use ($user) {
                $query->where('organ_id', $user->id)
                    ->orWhere('id', $user->id);
            })
            ->where('role_name', Role::$admin)
            ->pluck('id')
            ->toArray();

        if (!in_array($user->id, $adminTeamIds)) {
            $adminTeamIds[] = $user->id;
        }

        $otherAdminIds = array_values(array_filter($adminTeamIds, static function ($adminId) use ($user) {
            return (int)$adminId !== (int)$user->id;
        }));

        $personalMeetingIds = Meeting::query()
            ->where('creator_id', $user->id)
            ->pluck('id')
            ->toArray();

        $marketingLeads = FormSubmission::query()
            ->whereBetween('created_at', [$yearStart, $now])
            ->whereNotNull('user_id')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('organ_id', $user->id)
                    ->where('role_name', Role::$user);
            })
            ->distinct('user_id')
            ->count('user_id');

        $leadsConsulted = 0;
        if (!empty($personalMeetingIds)) {
            $leadsConsulted = ReserveMeeting::query()
                ->whereIn('meeting_id', $personalMeetingIds)
                ->whereBetween('created_at', [$yearStart, $now])
                ->distinct('user_id')
                ->count('user_id');
        }

        $personalSalesBase = Sale::query()
            ->where('seller_id', $user->id)
            ->whereBetween('created_at', [$yearStart, $now])
            ->where(function ($query) {
                $query->whereNotNull('webinar_id')
                    ->orWhereNotNull('bundle_id')
                    ->orWhereNotNull('meeting_id');
            });

        $leadsPaid = (clone $personalSalesBase)
            ->whereNull('refund_at')
            ->distinct('buyer_id')
            ->count('buyer_id');

        $leadsRejected = (clone $personalSalesBase)
            ->whereNotNull('refund_at')
            ->count();

        $rejectedLeadsList = (clone $personalSalesBase)
            ->whereNotNull('refund_at')
            ->with([
                'buyer' => function ($query) {
                    $query->select('id', 'full_name', 'email', 'avatar', 'avatar_settings');
                },
                'webinar' => function ($query) {
                    $query->select('id', 'title');
                },
            ])
            ->orderBy('refund_at', 'desc')
            ->limit(20)
            ->get();

        $weeklyLabels = [];
        $weeklyPersonal = [];
        $weeklyTeam = [];

        for ($i = 4; $i >= 0; $i--) {
            $weekStart = $nowCarbon->copy()->startOfWeek()->subWeeks($i)->startOfDay();
            $weekEnd = $weekStart->copy()->endOfWeek()->endOfDay();

            $weekStartTs = $weekStart->timestamp;
            $weekEndTs = $weekEnd->timestamp;

            $weeklyLabels[] = $weekStart->format('j/m') . ' - ' . $weekEnd->format('j/m');

            $weeklyPersonal[] = Sale::query()
                ->where('seller_id', $user->id)
                ->whereNull('refund_at')
                ->whereBetween('created_at', [$weekStartTs, $weekEndTs])
                ->where(function ($query) {
                    $query->whereNotNull('webinar_id')
                        ->orWhereNotNull('bundle_id')
                        ->orWhereNotNull('meeting_id');
                })
                ->distinct('buyer_id')
                ->count('buyer_id');

            $teamClosed = 0;
            if (!empty($otherAdminIds)) {
                $teamClosed = Sale::query()
                    ->whereIn('seller_id', $otherAdminIds)
                    ->whereNull('refund_at')
                    ->whereBetween('created_at', [$weekStartTs, $weekEndTs])
                    ->where(function ($query) {
                        $query->whereNotNull('webinar_id')
                            ->orWhereNotNull('bundle_id')
                            ->orWhereNotNull('meeting_id');
                    })
                    ->distinct('buyer_id')
                    ->count('buyer_id');
            }

            $weeklyTeam[] = $teamClosed;
        }

        $orgStudentIds = User::query()
            ->where('organ_id', $user->id)
            ->where('role_name', Role::$student)
            ->pluck('id')
            ->toArray();

        $monthAgo = $nowCarbon->copy()->subDays(30)->startOfDay()->timestamp;

        $activeStudentIds = [];
        if (!empty($orgStudentIds)) {
            $activeStudentIds = TimeSpentOnCourse::query()
                ->whereIn('user_id', $orgStudentIds)
                ->where('entry_time', '>=', $monthAgo)
                ->distinct('user_id')
                ->pluck('user_id')
                ->toArray();
        }

        $activeStudents = count($activeStudentIds);
        $nonActiveStudents = max(0, count($orgStudentIds) - $activeStudents);

        $teacherIds = User::query()
            ->where('organ_id', $user->id)
            ->where('role_name', Role::$teacher)
            ->pluck('id')
            ->toArray();

        $activeTeacherIds = [];
        if (!empty($teacherIds)) {
            $activeTeacherIds = IeltsGradingRating::query()
                ->whereIn('instructor_id', $teacherIds)
                ->where('created_at', '>=', $monthAgo)
                ->distinct('instructor_id')
                ->pluck('instructor_id')
                ->toArray();
        }
        $mentorsUnderKpi = max(0, count($teacherIds) - count($activeTeacherIds));

        $formRegistrations = FormSubmission::query()
            ->with(['user' => function ($query) {
                $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'email', 'created_at');
            }])
            ->whereNotNull('user_id')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('organ_id', $user->id)
                    ->where('role_name', Role::$user);
            })
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get();

        $orgMemberIds = User::query()
            ->where('organ_id', $user->id)
            ->pluck('id')
            ->toArray();
        $orgMemberIds[] = $user->id;

        $orgWebinarIds = Webinar::query()
            ->whereIn('creator_id', $orgMemberIds)
            ->orWhereIn('teacher_id', $orgMemberIds)
            ->pluck('id')
            ->toArray();

        $completingStudents = [];
        if (!empty($orgWebinarIds) && !empty($orgStudentIds)) {
            $salesWithStudents = Sale::query()
                ->whereIn('webinar_id', $orgWebinarIds)
                ->whereIn('buyer_id', $orgStudentIds)
                ->whereNull('refund_at')
                ->with([
                    'webinar',
                    'buyer' => function ($query) {
                        $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'email');
                    },
                ])
                ->get();

            $progresses = [];

            foreach ($salesWithStudents as $sale) {
                if (empty($sale->webinar) || empty($sale->buyer)) {
                    continue;
                }

                $buyerId = $sale->buyer_id;
                $webinar = $sale->webinar;

                $filesStat = $webinar->getFilesLearningProgressStat($buyerId);
                $sessionsStat = $webinar->getSessionsLearningProgressStat($buyerId);
                $textLessonsStat = $webinar->getTextLessonsLearningProgressStat($buyerId);

                $passed = $filesStat['passed'] + $sessionsStat['passed'] + $textLessonsStat['passed'];
                $count = $filesStat['count'] + $sessionsStat['count'] + $textLessonsStat['count'];

                $progress = ($count > 0) ? min(100, round(($passed * 100) / $count)) : 0;
                if ($progress >= 100) {
                    continue;
                }

                $progresses[] = [
                    'user' => $sale->buyer,
                    'webinar' => $webinar,
                    'progress' => $progress,
                    'remaining' => 100 - $progress,
                ];
            }

            usort($progresses, static function ($a, $b) {
                return $a['remaining'] <=> $b['remaining'];
            });

            $completingStudents = array_slice($progresses, 0, 20);
        }

        $saleAchievementPct = ($leadsConsulted > 0)
            ? min(100, round(($leadsPaid / $leadsConsulted) * 100, 1))
            : 0;

        $prevMonthConsultedLeads = 0;
        if (!empty($personalMeetingIds)) {
            $prevMonthConsultedLeads = ReserveMeeting::query()
                ->whereIn('meeting_id', $personalMeetingIds)
                ->whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
                ->distinct('user_id')
                ->count('user_id');
        }

        $daysInPrevMonth = $nowCarbon->copy()->subMonth()->daysInMonth;
        $leadsPerDay = $daysInPrevMonth > 0 ? round($prevMonthConsultedLeads / $daysInPrevMonth, 1) : 0;

        $prevMonthPaidSalesQuery = Sale::query()
            ->where('seller_id', $user->id)
            ->whereNull('refund_at')
            ->whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
            ->where(function ($query) {
                $query->whereNotNull('webinar_id')
                    ->orWhereNotNull('bundle_id')
                    ->orWhereNotNull('meeting_id');
            });

        $prevMonthPaidLeads = (clone $prevMonthPaidSalesQuery)
            ->distinct('buyer_id')
            ->count('buyer_id');

        $personalConversionRate = ($prevMonthConsultedLeads > 0)
            ? min(100, round(($prevMonthPaidLeads / $prevMonthConsultedLeads) * 100, 1))
            : 0;

        $prevMonthPaidSales = (clone $prevMonthPaidSalesQuery)
            ->select('id', 'buyer_id', 'created_at', 'total_amount')
            ->get();

        $avgDealDays = 0;
        if ($prevMonthPaidSales->count() > 0) {
            $days = [];

            foreach ($prevMonthPaidSales as $sale) {
                $firstFormAt = (int)FormSubmission::query()->where('user_id', $sale->buyer_id)->min('created_at');
                $firstMeetingAt = 0;

                if (!empty($personalMeetingIds)) {
                    $firstMeetingAt = (int)ReserveMeeting::query()
                        ->whereIn('meeting_id', $personalMeetingIds)
                        ->where('user_id', $sale->buyer_id)
                        ->min('created_at');
                }

                $touchPoints = array_filter([$firstFormAt, $firstMeetingAt]);
                if (empty($touchPoints)) {
                    continue;
                }

                $firstTouchAt = min($touchPoints);
                $days[] = max(0, ($sale->created_at - $firstTouchAt) / 86400);
            }

            if (!empty($days)) {
                $avgDealDays = round(array_sum($days) / count($days), 1);
            }
        }

        $contractRevenue = (float)(clone $prevMonthPaidSalesQuery)->sum('total_amount');
        $contractCount = (int)(clone $prevMonthPaidSalesQuery)->count();
        $contractValue = $contractCount > 0 ? round($contractRevenue / $contractCount, 2) : 0;

        $adminUsers = User::query()
            ->whereIn('id', $adminTeamIds)
            ->select('id', 'full_name', 'avatar', 'avatar_settings')
            ->get()
            ->keyBy('id');

        $topSellers = [];
        foreach ($adminTeamIds as $adminId) {
            $adminData = $adminUsers->get($adminId);
            if (empty($adminData)) {
                continue;
            }

            $adminMeetingIds = Meeting::query()
                ->where('creator_id', $adminId)
                ->pluck('id')
                ->toArray();

            $adminConsulted = 0;
            if (!empty($adminMeetingIds)) {
                $adminConsulted = ReserveMeeting::query()
                    ->whereIn('meeting_id', $adminMeetingIds)
                    ->whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
                    ->distinct('user_id')
                    ->count('user_id');
            }

            $adminPaid = Sale::query()
                ->where('seller_id', $adminId)
                ->whereNull('refund_at')
                ->whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
                ->where(function ($query) {
                    $query->whereNotNull('webinar_id')
                        ->orWhereNotNull('bundle_id')
                        ->orWhereNotNull('meeting_id');
                })
                ->distinct('buyer_id')
                ->count('buyer_id');

            $rate = $adminConsulted > 0
                ? min(100, round(($adminPaid / $adminConsulted) * 100, 1))
                : 0;

            $topSellers[] = [
                'user' => $adminData,
                'consulted' => $adminConsulted,
                'paid' => $adminPaid,
                'rate' => $rate,
            ];
        }

        usort($topSellers, static function ($a, $b) {
            if ($a['rate'] == $b['rate']) {
                return $b['paid'] <=> $a['paid'];
            }

            return $b['rate'] <=> $a['rate'];
        });

        $topSellers = array_slice($topSellers, 0, 5);

        return [
            'marketingLeads' => $marketingLeads,
            'leadsConsulted' => $leadsConsulted,
            'leadsPaid' => $leadsPaid,
            'leadsRejected' => $leadsRejected,
            'rejectedLeadsList' => $rejectedLeadsList,
            'weeklyLabels' => $weeklyLabels,
            'weeklyPersonal' => $weeklyPersonal,
            'weeklyTeam' => $weeklyTeam,
            'activeStudents' => $activeStudents,
            'nonActiveStudents' => $nonActiveStudents,
            'mentorsUnderKpi' => $mentorsUnderKpi,
            'formRegistrations' => $formRegistrations,
            'completingStudents' => $completingStudents,
            'saleAchievementPct' => $saleAchievementPct,
            'leadsPerDay' => $leadsPerDay,
            'personalConversionRate' => $personalConversionRate,
            'avgDealDays' => $avgDealDays,
            'contractValue' => $contractValue,
            'contractRevenue' => round($contractRevenue, 2),
            'topSellers' => $topSellers,
        ];
    }

    private function buildManagerBusinessSeriesByWeek(array $adminIds, Carbon $now): array
    {
        $labels = [];
        $thisYear = [];
        $lastYear = [];
        $kpi = [];

        for ($i = 6; $i >= 0; $i--) {
            $dayStart = $now->copy()->subDays($i)->startOfDay();
            $dayEnd = $dayStart->copy()->endOfDay();
            $labels[] = $dayStart->format('d/m');

            $thisYearRevenue = $this->sumRevenueByRange($adminIds, $dayStart->timestamp, $dayEnd->timestamp);
            $thisYear[] = round($thisYearRevenue, 2);

            $lastYearStart = $dayStart->copy()->subYear();
            $lastYearEnd = $dayEnd->copy()->subYear();
            $lastYearRevenue = $this->sumRevenueByRange($adminIds, $lastYearStart->timestamp, $lastYearEnd->timestamp);
            $lastYear[] = round($lastYearRevenue, 2);

            $kpiValue = $this->getKpiRevenueForRange($adminIds, $dayStart, $dayEnd, 'weekly');
            if ($kpiValue <= 0) {
                $kpiValue = $thisYearRevenue * 1.1;
            }

            $kpi[] = round($kpiValue, 2);
        }

        return [
            'labels' => $labels,
            'thisYear' => $thisYear,
            'lastYear' => $lastYear,
            'kpi' => $kpi,
        ];
    }

    private function buildManagerBusinessSeriesByMonth(array $adminIds, Carbon $now): array
    {
        $labels = [];
        $thisYear = [];
        $lastYear = [];
        $kpi = [];

        for ($i = 3; $i >= 0; $i--) {
            $weekStart = $now->copy()->startOfWeek()->subWeeks($i)->startOfDay();
            $weekEnd = $weekStart->copy()->endOfWeek()->endOfDay();

            $labels[] = $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m');

            $thisYearRevenue = $this->sumRevenueByRange($adminIds, $weekStart->timestamp, $weekEnd->timestamp);
            $thisYear[] = round($thisYearRevenue, 2);

            $lastYearStart = $weekStart->copy()->subYear();
            $lastYearEnd = $weekEnd->copy()->subYear();
            $lastYearRevenue = $this->sumRevenueByRange($adminIds, $lastYearStart->timestamp, $lastYearEnd->timestamp);
            $lastYear[] = round($lastYearRevenue, 2);

            $kpiValue = $this->getKpiRevenueForRange($adminIds, $weekStart, $weekEnd, 'weekly');
            if ($kpiValue <= 0) {
                $kpiValue = $thisYearRevenue * 1.1;
            }

            $kpi[] = round($kpiValue, 2);
        }

        return [
            'labels' => $labels,
            'thisYear' => $thisYear,
            'lastYear' => $lastYear,
            'kpi' => $kpi,
        ];
    }

    private function buildManagerBusinessSeriesByYear(array $adminIds, Carbon $now): array
    {
        $labels = [];
        $thisYear = [];
        $lastYear = [];
        $kpi = [];

        $currentYear = (int)$now->format('Y');
        $previousYear = $currentYear - 1;

        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::create($currentYear, $month, 1)->startOfDay();
            $monthEnd = $monthStart->copy()->endOfMonth()->endOfDay();
            $labels[] = trans('panel.month_' . $month);

            $thisYearRevenue = $this->sumRevenueByRange($adminIds, $monthStart->timestamp, $monthEnd->timestamp);
            $thisYear[] = round($thisYearRevenue, 2);

            $lastYearStart = Carbon::create($previousYear, $month, 1)->startOfDay();
            $lastYearEnd = $lastYearStart->copy()->endOfMonth()->endOfDay();
            $lastYearRevenue = $this->sumRevenueByRange($adminIds, $lastYearStart->timestamp, $lastYearEnd->timestamp);
            $lastYear[] = round($lastYearRevenue, 2);

            $kpiValue = $this->getKpiRevenueForRange($adminIds, $monthStart, $monthEnd, 'monthly');
            if ($kpiValue <= 0) {
                $kpiValue = $thisYearRevenue * 1.1;
            }

            $kpi[] = round($kpiValue, 2);
        }

        return [
            'labels' => $labels,
            'thisYear' => $thisYear,
            'lastYear' => $lastYear,
            'kpi' => $kpi,
        ];
    }

    private function sumRevenueByRange(array $adminIds, int $startAt, int $endAt): float
    {
        if (empty($adminIds)) {
            return 0;
        }

        return (float)Sale::query()
            ->whereIn('seller_id', $adminIds)
            ->whereNull('refund_at')
            ->whereBetween('created_at', [$startAt, $endAt])
            ->sum('total_amount');
    }

    private function getKpiRevenueForRange(array $adminIds, Carbon $startAt, Carbon $endAt, string $periodType): float
    {
        if (empty($adminIds) || !Schema::hasTable('sales_kpis')) {
            return 0;
        }

        return (float)DB::table('sales_kpis')
            ->whereIn('user_id', $adminIds)
            ->where('period_type', $periodType)
            ->whereBetween('period_date', [$startAt->toDateString(), $endAt->toDateString()])
            ->sum('target_revenue');
    }

    public function getManagerUserGrowthDashboardData(): array
    {
        $now = Carbon::now();
        $nowTimestamp = $now->timestamp;
        $yearStart = $now->copy()->startOfYear()->timestamp;
        $leadRoles = [Role::$user, Role::$student];

        $newLeadUsers = User::query()
            ->whereIn('role_name', $leadRoles)
            ->whereBetween('created_at', [$yearStart, $nowTimestamp])
            ->get(['id', 'full_name', 'email', 'created_at']);

        $newLeadIds = $newLeadUsers->pluck('id')->toArray();

        $firstPaidMap = [];
        if (!empty($newLeadIds)) {
            $firstPaidMap = Sale::query()
                ->whereIn('buyer_id', $newLeadIds)
                ->whereNull('refund_at')
                ->where(function ($query) {
                    $query->whereNotNull('webinar_id')
                        ->orWhereNotNull('bundle_id');
                })
                ->select('buyer_id', DB::raw('MIN(created_at) as first_paid_at'))
                ->groupBy('buyer_id')
                ->pluck('first_paid_at', 'buyer_id')
                ->toArray();
        }

        $newPaidUsers = 0;
        $leadToPaidDurations = [];

        foreach ($newLeadUsers as $leadUser) {
            $firstPaidAt = (int)($firstPaidMap[$leadUser->id] ?? 0);
            if ($firstPaidAt > 0 && $firstPaidAt >= (int)$leadUser->created_at) {
                $newPaidUsers++;
                $leadToPaidDurations[] = $firstPaidAt - (int)$leadUser->created_at;
            }
        }

        $newLeads = $newLeadUsers->count();
        $conversionRate = $newLeads > 0 ? round(($newPaidUsers / $newLeads) * 100, 1) : 0;
        $avgLeadToPaidSeconds = !empty($leadToPaidDurations)
            ? (int)round(array_sum($leadToPaidDurations) / count($leadToPaidDurations))
            : 0;

        return [
            'summary' => [
                'newLeads' => $newLeads,
                'newPaidUsers' => $newPaidUsers,
                'conversionRate' => $conversionRate,
                'avgLeadToPaidSeconds' => $avgLeadToPaidSeconds,
                'avgLeadToPaidLabel' => $this->formatLeadToPaidDuration($avgLeadToPaidSeconds),
            ],
            'chartData' => [
                'week' => $this->buildManagerUserGrowthSeriesByWeek($now),
                'month' => $this->buildManagerUserGrowthSeriesByMonth($now),
                'year' => $this->buildManagerUserGrowthSeriesByYear($now),
            ],
            'renewalReminders' => $this->getManagerRenewalReminderStudents($now, 30),
        ];
    }

    private function buildManagerUserGrowthSeriesByWeek(Carbon $now): array
    {
        $labels = [];
        $leads = [];

        for ($i = 6; $i >= 0; $i--) {
            $dayStart = $now->copy()->subDays($i)->startOfDay();
            $dayEnd = $dayStart->copy()->endOfDay();

            $labels[] = $dayStart->format('d/m');
            $leads[] = User::query()
                ->whereIn('role_name', [Role::$user, Role::$student])
                ->whereBetween('created_at', [$dayStart->timestamp, $dayEnd->timestamp])
                ->count();
        }

        return [
            'labels' => $labels,
            'leads' => $leads,
        ];
    }

    private function buildManagerUserGrowthSeriesByMonth(Carbon $now): array
    {
        $labels = [];
        $leads = [];

        for ($i = 3; $i >= 0; $i--) {
            $weekStart = $now->copy()->startOfWeek()->subWeeks($i)->startOfDay();
            $weekEnd = $weekStart->copy()->endOfWeek()->endOfDay();

            $labels[] = $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m');
            $leads[] = User::query()
                ->whereIn('role_name', [Role::$user, Role::$student])
                ->whereBetween('created_at', [$weekStart->timestamp, $weekEnd->timestamp])
                ->count();
        }

        return [
            'labels' => $labels,
            'leads' => $leads,
        ];
    }

    private function buildManagerUserGrowthSeriesByYear(Carbon $now): array
    {
        $labels = [];
        $leads = [];

        $currentYear = (int)$now->format('Y');

        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::create($currentYear, $month, 1)->startOfDay();
            $monthEnd = $monthStart->copy()->endOfMonth()->endOfDay();

            $labels[] = trans('panel.month_' . $month);
            $leads[] = User::query()
                ->whereIn('role_name', [Role::$user, Role::$student])
                ->whereBetween('created_at', [$monthStart->timestamp, $monthEnd->timestamp])
                ->count();
        }

        return [
            'labels' => $labels,
            'leads' => $leads,
        ];
    }

    private function getManagerRenewalReminderStudents(Carbon $now, int $daysThreshold = 30): array
    {
        $nowTimestamp = $now->timestamp;
        $thresholdTimestamp = $now->copy()->addDays($daysThreshold)->endOfDay()->timestamp;

        $studentIds = User::query()
            ->where('role_name', Role::$student)
            ->pluck('id')
            ->toArray();

        if (empty($studentIds)) {
            return [];
        }

        $sales = Sale::query()
            ->whereIn('buyer_id', $studentIds)
            ->whereNull('refund_at')
            ->where(function ($query) {
                $query->whereNotNull('webinar_id')
                    ->orWhereNotNull('bundle_id');
            })
            ->with([
                'buyer' => function ($query) {
                    $query->select('id', 'full_name', 'email', 'avatar', 'avatar_settings');
                },
                'webinar' => function ($query) {
                    $query->select('id', 'access_days');
                },
                'bundle' => function ($query) {
                    $query->select('id', 'access_days');
                },
            ])
            ->get();

        $nearestExpiringByStudent = [];

        foreach ($sales as $sale) {
            $itemTitle = null;
            $accessDays = 0;

            if (!empty($sale->webinar_id) && !empty($sale->webinar)) {
                $itemTitle = $sale->webinar->title;
                $accessDays = (int)($sale->webinar->access_days ?? 0);
            } elseif (!empty($sale->bundle_id) && !empty($sale->bundle)) {
                $itemTitle = $sale->bundle->title;
                $accessDays = (int)($sale->bundle->access_days ?? 0);
            }

            if ($accessDays <= 0 || empty($itemTitle) || empty($sale->buyer)) {
                continue;
            }

            $expireAt = (int)$sale->created_at + ($accessDays * 86400);

            if ($expireAt < $nowTimestamp || $expireAt > $thresholdTimestamp) {
                continue;
            }

            $remainingDays = (int)ceil(($expireAt - $nowTimestamp) / 86400);
            $buyerId = (int)$sale->buyer_id;

            $candidate = [
                'studentId' => $buyerId,
                'studentName' => $sale->buyer->full_name,
                'studentEmail' => $sale->buyer->email,
                'itemTitle' => $itemTitle,
                'expireAt' => $expireAt,
                'remainingDays' => max(0, $remainingDays),
                'reminderUrl' => !empty($sale->buyer->email) ? 'mailto:' . $sale->buyer->email : null,
            ];

            $existing = $nearestExpiringByStudent[$buyerId] ?? null;
            if (empty($existing) || $expireAt < $existing['expireAt']) {
                $nearestExpiringByStudent[$buyerId] = $candidate;
            }
        }

        return collect($nearestExpiringByStudent)
            ->sortBy('expireAt')
            ->values()
            ->take(25)
            ->all();
    }

    private function formatLeadToPaidDuration(int $durationSeconds): string
    {
        if ($durationSeconds <= 0) {
            return '0 ngày';
        }

        $days = round($durationSeconds / 86400, 1);
        if ($days >= 1) {
            return rtrim(rtrim(number_format($days, 1, '.', ''), '0'), '.') . ' ngày';
        }

        $hours = round($durationSeconds / 3600, 1);
        return rtrim(rtrim(number_format($hours, 1, '.', ''), '0'), '.') . ' giờ';
    }

    public function getManagerLearningQualityDashboardData(): array
    {
        $now = Carbon::now();
        $nowTimestamp = $now->timestamp;
        $last30DaysTimestamp = $now->copy()->subDays(30)->startOfDay()->timestamp;

        $students = User::query()
            ->where('role_name', Role::$student)
            ->select('id', 'full_name', 'email', 'avatar', 'avatar_settings', 'created_at')
            ->orderBy('id', 'desc')
            ->get();

        $studentIds = $students->pluck('id')->toArray();

        if (empty($studentIds)) {
            return [
                'summary' => [
                    'avgCompletionSeconds' => 0,
                    'avgCompletionLabel' => '0 giờ',
                    'courseCompletionRate' => 0,
                    'aimRate' => 0,
                ],
                'allStudents' => collect(),
                'studentStatusChart' => [
                    'active' => 0,
                    'dropout' => 0,
                ],
                'dropoutActiveStudents' => collect(),
                'underPerformanceStudents' => collect(),
                'dissatisfiedStudents' => collect(),
            ];
        }

        $recentActivitySecondsMap = TimeSpentOnCourse::query()
            ->whereIn('user_id', $studentIds)
            ->where('entry_time', '>=', $last30DaysTimestamp)
            ->select('user_id', DB::raw('SUM(COALESCE(seconds_spent, 0)) as total_seconds'))
            ->groupBy('user_id')
            ->pluck('total_seconds', 'user_id')
            ->toArray();

        $activeStudentIds = collect($recentActivitySecondsMap)
            ->filter(function ($seconds) {
                return (int)$seconds > 0;
            })
            ->keys()
            ->map(function ($id) {
                return (int)$id;
            })
            ->toArray();

        $enrolledPairs = Sale::query()
            ->whereIn('buyer_id', $studentIds)
            ->whereNull('refund_at')
            ->whereNotNull('webinar_id')
            ->select('buyer_id', 'webinar_id')
            ->distinct()
            ->get();

        $courseIds = $enrolledPairs->pluck('webinar_id')->filter()->unique()->values()->toArray();

        $courseTotalItemsMap = [];
        if (!empty($courseIds)) {
            $fileCounts = Schema::hasTable('files')
                ? DB::table('files')
                    ->whereIn('webinar_id', $courseIds)
                    ->select('webinar_id', DB::raw('COUNT(*) as count_items'))
                    ->groupBy('webinar_id')
                    ->pluck('count_items', 'webinar_id')
                    ->toArray()
                : [];

            $sessionCounts = Schema::hasTable('sessions')
                ? DB::table('sessions')
                    ->whereIn('webinar_id', $courseIds)
                    ->select('webinar_id', DB::raw('COUNT(*) as count_items'))
                    ->groupBy('webinar_id')
                    ->pluck('count_items', 'webinar_id')
                    ->toArray()
                : [];

            $textLessonCounts = Schema::hasTable('text_lessons')
                ? DB::table('text_lessons')
                    ->whereIn('webinar_id', $courseIds)
                    ->select('webinar_id', DB::raw('COUNT(*) as count_items'))
                    ->groupBy('webinar_id')
                    ->pluck('count_items', 'webinar_id')
                    ->toArray()
                : [];

            foreach ($courseIds as $courseId) {
                $courseTotalItemsMap[$courseId] = (int)($fileCounts[$courseId] ?? 0)
                    + (int)($sessionCounts[$courseId] ?? 0)
                    + (int)($textLessonCounts[$courseId] ?? 0);
            }
        }

        $learnedByPairMap = [];

        if (Schema::hasTable('course_learning') && !empty($studentIds) && !empty($courseIds)) {
            if (Schema::hasTable('files')) {
                $fileLearnedRows = DB::table('course_learning as cl')
                    ->join('files as f', 'f.id', '=', 'cl.file_id')
                    ->whereIn('cl.user_id', $studentIds)
                    ->whereIn('f.webinar_id', $courseIds)
                    ->whereNotNull('cl.file_id')
                    ->select('cl.user_id', 'f.webinar_id', DB::raw('COUNT(DISTINCT cl.file_id) as learned_count'))
                    ->groupBy('cl.user_id', 'f.webinar_id')
                    ->get();

                foreach ($fileLearnedRows as $row) {
                    $key = $row->user_id . ':' . $row->webinar_id;
                    $learnedByPairMap[$key] = (int)($learnedByPairMap[$key] ?? 0) + (int)$row->learned_count;
                }
            }

            if (Schema::hasTable('sessions')) {
                $sessionLearnedRows = DB::table('course_learning as cl')
                    ->join('sessions as s', 's.id', '=', 'cl.session_id')
                    ->whereIn('cl.user_id', $studentIds)
                    ->whereIn('s.webinar_id', $courseIds)
                    ->whereNotNull('cl.session_id')
                    ->select('cl.user_id', 's.webinar_id', DB::raw('COUNT(DISTINCT cl.session_id) as learned_count'))
                    ->groupBy('cl.user_id', 's.webinar_id')
                    ->get();

                foreach ($sessionLearnedRows as $row) {
                    $key = $row->user_id . ':' . $row->webinar_id;
                    $learnedByPairMap[$key] = (int)($learnedByPairMap[$key] ?? 0) + (int)$row->learned_count;
                }
            }

            if (Schema::hasTable('text_lessons')) {
                $textLessonRows = DB::table('course_learning as cl')
                    ->join('text_lessons as tl', 'tl.id', '=', 'cl.text_lesson_id')
                    ->whereIn('cl.user_id', $studentIds)
                    ->whereIn('tl.webinar_id', $courseIds)
                    ->whereNotNull('cl.text_lesson_id')
                    ->select('cl.user_id', 'tl.webinar_id', DB::raw('COUNT(DISTINCT cl.text_lesson_id) as learned_count'))
                    ->groupBy('cl.user_id', 'tl.webinar_id')
                    ->get();

                foreach ($textLessonRows as $row) {
                    $key = $row->user_id . ':' . $row->webinar_id;
                    $learnedByPairMap[$key] = (int)($learnedByPairMap[$key] ?? 0) + (int)$row->learned_count;
                }
            }
        }

        $pairSecondsSpentMap = TimeSpentOnCourse::query()
            ->whereIn('user_id', $studentIds)
            ->whereIn('course_id', $courseIds)
            ->select('user_id', 'course_id', DB::raw('SUM(COALESCE(seconds_spent, 0)) as spent_seconds'))
            ->groupBy('user_id', 'course_id')
            ->get()
            ->mapWithKeys(function ($row) {
                return [$row->user_id . ':' . $row->course_id => (int)$row->spent_seconds];
            })
            ->toArray();

        $studentProgressMap = [];
        $totalEnrolledPairs = 0;
        $completedPairs = 0;
        $completedPairSeconds = [];

        foreach ($enrolledPairs as $pair) {
            $studentId = (int)$pair->buyer_id;
            $courseId = (int)$pair->webinar_id;
            $pairKey = $studentId . ':' . $courseId;

            $totalItems = (int)($courseTotalItemsMap[$courseId] ?? 0);
            if ($totalItems <= 0) {
                continue;
            }

            $totalEnrolledPairs++;
            $learnedItems = (int)($learnedByPairMap[$pairKey] ?? 0);
            $progressPercent = min(100, round(($learnedItems / $totalItems) * 100, 1));

            $studentProgressMap[$studentId][] = $progressPercent;

            if ($progressPercent >= 100) {
                $completedPairs++;
                $spentSeconds = (int)($pairSecondsSpentMap[$pairKey] ?? 0);
                if ($spentSeconds > 0) {
                    $completedPairSeconds[] = $spentSeconds;
                }
            }
        }

        $courseCompletionRate = $totalEnrolledPairs > 0
            ? round(($completedPairs / $totalEnrolledPairs) * 100, 1)
            : 0;

        $avgCompletionSeconds = !empty($completedPairSeconds)
            ? (int)round(array_sum($completedPairSeconds) / count($completedPairSeconds))
            : 0;

        if ($avgCompletionSeconds <= 0 && !empty($pairSecondsSpentMap)) {
            $avgCompletionSeconds = (int)round(array_sum($pairSecondsSpentMap) / max(1, count($pairSecondsSpentMap)));
        }

        $aimRate = 0;
        if (Schema::hasTable('ielts_test_attempts')) {
            $totalCompletedTests = IeltsTestAttempt::query()
                ->whereIn('user_id', $studentIds)
                ->where('status', 'completed')
                ->whereNotNull('overall_band')
                ->count();

            $aimReachedTests = IeltsTestAttempt::query()
                ->whereIn('user_id', $studentIds)
                ->where('status', 'completed')
                ->where('overall_band', '>=', 6.5)
                ->count();

            $aimRate = $totalCompletedTests > 0
                ? round(($aimReachedTests / $totalCompletedTests) * 100, 1)
                : 0;
        }

        $averageGradeMap = WebinarAssignmentHistory::query()
            ->whereIn('student_id', $studentIds)
            ->whereNotNull('grade')
            ->select('student_id', DB::raw('AVG(grade) as avg_grade'))
            ->groupBy('student_id')
            ->pluck('avg_grade', 'student_id')
            ->toArray();

        $studentStatusRows = $students->map(function ($student) use ($activeStudentIds, $recentActivitySecondsMap, $studentProgressMap, $averageGradeMap) {
            $studentId = (int)$student->id;
            $isActive = in_array($studentId, $activeStudentIds);
            $progressList = $studentProgressMap[$studentId] ?? [];
            $avgProgress = !empty($progressList)
                ? round(array_sum($progressList) / count($progressList), 1)
                : 0;

            return [
                'id' => $studentId,
                'name' => $student->full_name,
                'email' => $student->email,
                'avatar' => $student->getAvatar(40),
                'status' => $isActive ? 'active' : 'dropout',
                'statusLabel' => $isActive ? 'Active' : 'Dropout',
                'activitySeconds30d' => (int)($recentActivitySecondsMap[$studentId] ?? 0),
                'avgProgress' => $avgProgress,
                'avgGrade' => isset($averageGradeMap[$studentId]) ? round((float)$averageGradeMap[$studentId], 1) : null,
            ];
        });

        $dropoutActiveStudents = $studentStatusRows
            ->sortBy(function ($item) {
                return $item['status'] === 'dropout' ? 0 : 1;
            })
            ->values();

        $underPerformanceStudents = $studentStatusRows
            ->filter(function ($item) {
                $lowGrade = !is_null($item['avgGrade']) && $item['avgGrade'] < 60;
                $lowProgress = $item['avgProgress'] < 35;
                $lowActivity = $item['activitySeconds30d'] < 900;

                return $lowGrade || ($lowProgress && $lowActivity);
            })
            ->sortBy(function ($item) {
                return ($item['avgGrade'] ?? 1000) * 1000 + $item['avgProgress'];
            })
            ->values();

        $lowReviewUsers = WebinarReview::query()
            ->whereIn('creator_id', $studentIds)
            ->where('rates', '<=', 2)
            ->select('creator_id as user_id', DB::raw('COUNT(*) as total'))
            ->groupBy('creator_id')
            ->pluck('total', 'user_id')
            ->toArray();

        $reportUsers = Schema::hasTable('webinar_reports')
            ? WebinarReport::query()
                ->whereIn('user_id', $studentIds)
                ->select('user_id', DB::raw('COUNT(*) as total'))
                ->groupBy('user_id')
                ->pluck('total', 'user_id')
                ->toArray()
            : [];

        $supportComplaintUsers = Support::query()
            ->whereIn('user_id', $studentIds)
            ->whereIn('status', ['open', 'replied'])
            ->select('user_id', DB::raw('COUNT(*) as total'))
            ->groupBy('user_id')
            ->pluck('total', 'user_id')
            ->toArray();

        $dissatisfiedStudents = $studentStatusRows
            ->map(function ($student) use ($lowReviewUsers, $reportUsers, $supportComplaintUsers) {
                $studentId = (int)$student['id'];
                $lowReviews = (int)($lowReviewUsers[$studentId] ?? 0);
                $reports = (int)($reportUsers[$studentId] ?? 0);
                $supports = (int)($supportComplaintUsers[$studentId] ?? 0);

                $score = ($lowReviews * 2) + ($reports * 2) + $supports;

                $reasonParts = [];
                if ($lowReviews > 0) {
                    $reasonParts[] = $lowReviews . ' đánh giá thấp';
                }
                if ($reports > 0) {
                    $reasonParts[] = $reports . ' báo cáo lỗi';
                }
                if ($supports > 0) {
                    $reasonParts[] = $supports . ' ticket chưa hài lòng';
                }

                return [
                    'id' => $studentId,
                    'name' => $student['name'],
                    'email' => $student['email'],
                    'status' => $student['status'],
                    'issueScore' => $score,
                    'reason' => !empty($reasonParts) ? implode(' | ', $reasonParts) : null,
                ];
            })
            ->filter(function ($row) {
                return $row['issueScore'] > 0;
            })
            ->sortByDesc('issueScore')
            ->values();

        return [
            'summary' => [
                'avgCompletionSeconds' => $avgCompletionSeconds,
                'avgCompletionLabel' => $this->formatLearningDuration($avgCompletionSeconds),
                'courseCompletionRate' => $courseCompletionRate,
                'aimRate' => $aimRate,
            ],
            'allStudents' => $studentStatusRows,
            'studentStatusChart' => [
                'active' => count($activeStudentIds),
                'dropout' => max(0, $students->count() - count($activeStudentIds)),
            ],
            'dropoutActiveStudents' => $dropoutActiveStudents,
            'underPerformanceStudents' => $underPerformanceStudents,
            'dissatisfiedStudents' => $dissatisfiedStudents,
        ];
    }

    public function getManagerTeamPerformanceDashboardData(): array
    {
        $now = Carbon::now();
        $nowTimestamp = $now->timestamp;
        $monthStart = $now->copy()->startOfMonth()->timestamp;
        $leadRoles = [Role::$user, Role::$student];

        $teamAdmins = User::query()
            ->where('role_name', Role::$admin)
            ->select('id', 'full_name', 'email')
            ->orderBy('id', 'asc')
            ->get();

        $adminIds = $teamAdmins->pluck('id')->toArray();

        if (empty($adminIds)) {
            return [
                'summary' => [
                    'teamCloseRate' => 0,
                    'avgContactLabel' => '0m',
                    'overdueLeads' => 0,
                ],
                'teamMembers' => collect(),
            ];
        }

        $monthlyAssignedLeads = User::query()
            ->whereIn('role_name', $leadRoles)
            ->whereIn('organ_id', $adminIds)
            ->whereBetween('created_at', [$monthStart, $nowTimestamp])
            ->get(['id', 'organ_id', 'created_at']);

        $monthlyLeadIds = $monthlyAssignedLeads->pluck('id')->toArray();
        $monthlyAssignedCount = count($monthlyLeadIds);

        $paidMonthlyLeadCount = 0;
        if (!empty($monthlyLeadIds)) {
            $paidMonthlyLeadCount = Sale::query()
                ->whereIn('buyer_id', $monthlyLeadIds)
                ->whereNull('refund_at')
                ->where(function ($query) {
                    $query->whereNotNull('webinar_id')
                        ->orWhereNotNull('bundle_id')
                        ->orWhereNotNull('meeting_id');
                })
                ->distinct('buyer_id')
                ->count('buyer_id');
        }

        $teamCloseRate = $monthlyAssignedCount > 0
            ? round(($paidMonthlyLeadCount / $monthlyAssignedCount) * 100, 1)
            : 0;

        $monthlyFirstContactRows = DB::table('reserve_meetings as rm')
            ->join('meetings as m', 'm.id', '=', 'rm.meeting_id')
            ->whereIn('m.creator_id', $adminIds)
            ->whereIn('rm.user_id', $monthlyLeadIds)
            ->select('rm.user_id', DB::raw('MIN(rm.created_at) as first_contact_at'))
            ->groupBy('rm.user_id')
            ->get();

        $monthlyLeadCreatedMap = $monthlyAssignedLeads
            ->pluck('created_at', 'id')
            ->toArray();

        $teamContactDurations = [];
        foreach ($monthlyFirstContactRows as $row) {
            $leadId = (int)$row->user_id;
            $leadCreatedAt = (int)($monthlyLeadCreatedMap[$leadId] ?? 0);
            $firstContactAt = (int)($row->first_contact_at ?? 0);

            if ($leadCreatedAt > 0 && $firstContactAt >= $leadCreatedAt) {
                $teamContactDurations[] = $firstContactAt - $leadCreatedAt;
            }
        }

        $teamAvgContactSeconds = !empty($teamContactDurations)
            ? (int)round(array_sum($teamContactDurations) / count($teamContactDurations))
            : 0;

        $allAssignedLeads = User::query()
            ->whereIn('role_name', $leadRoles)
            ->whereIn('organ_id', $adminIds)
            ->get(['id', 'created_at']);

        $allAssignedLeadIds = $allAssignedLeads->pluck('id')->toArray();

        $lastContactMap = [];
        if (!empty($allAssignedLeadIds)) {
            $lastContactMap = DB::table('reserve_meetings as rm')
                ->join('meetings as m', 'm.id', '=', 'rm.meeting_id')
                ->whereIn('m.creator_id', $adminIds)
                ->whereIn('rm.user_id', $allAssignedLeadIds)
                ->select('rm.user_id', DB::raw('MAX(rm.created_at) as last_contact_at'))
                ->groupBy('rm.user_id')
                ->pluck('last_contact_at', 'user_id')
                ->toArray();
        }

        $paidLeadIdsMap = [];
        if (!empty($allAssignedLeadIds)) {
            $paidLeadIdsMap = Sale::query()
                ->whereIn('buyer_id', $allAssignedLeadIds)
                ->whereNull('refund_at')
                ->where(function ($query) {
                    $query->whereNotNull('webinar_id')
                        ->orWhereNotNull('bundle_id')
                        ->orWhereNotNull('meeting_id');
                })
                ->distinct('buyer_id')
                ->pluck('buyer_id', 'buyer_id')
                ->toArray();
        }

        $overdueLeads = 0;
        $followUpWindow = 7 * 86400;
        $firstContactDue = 2 * 86400;

        foreach ($allAssignedLeads as $lead) {
            $leadId = (int)$lead->id;
            $leadCreatedAt = (int)$lead->created_at;
            $age = max(0, $nowTimestamp - $leadCreatedAt);

            if ($age < $firstContactDue) {
                continue;
            }

            if (isset($paidLeadIdsMap[$leadId])) {
                continue;
            }

            $lastContactAt = (int)($lastContactMap[$leadId] ?? 0);
            if ($lastContactAt <= 0 || ($nowTimestamp - $lastContactAt) >= $followUpWindow) {
                $overdueLeads++;
            }
        }

        $meetingIdsByAdmin = DB::table('meetings')
            ->whereIn('creator_id', $adminIds)
            ->select('id', 'creator_id')
            ->get()
            ->groupBy('creator_id')
            ->map(function ($rows) {
                return collect($rows)->pluck('id')->map(function ($id) {
                    return (int)$id;
                })->toArray();
            })
            ->toArray();

        $memberRows = $teamAdmins->map(function ($admin) use ($leadRoles, $monthStart, $nowTimestamp, $meetingIdsByAdmin) {
            $adminId = (int)$admin->id;

            $assignedMonthlyLeads = User::query()
                ->whereIn('role_name', $leadRoles)
                ->where('organ_id', $adminId)
                ->whereBetween('created_at', [$monthStart, $nowTimestamp])
                ->get(['id', 'created_at']);

            $assignedMonthlyLeadIds = $assignedMonthlyLeads->pluck('id')->toArray();
            $assignedMonthlyCount = count($assignedMonthlyLeadIds);

            $meetingIds = $meetingIdsByAdmin[$adminId] ?? [];

            $totalCalls = 0;
            $firstContactRows = collect();

            if (!empty($meetingIds)) {
                $totalCalls = ReserveMeeting::query()
                    ->whereIn('meeting_id', $meetingIds)
                    ->whereBetween('created_at', [$monthStart, $nowTimestamp])
                    ->count();

                if (!empty($assignedMonthlyLeadIds)) {
                    $firstContactRows = DB::table('reserve_meetings')
                        ->whereIn('meeting_id', $meetingIds)
                        ->whereIn('user_id', $assignedMonthlyLeadIds)
                        ->select('user_id', DB::raw('MIN(created_at) as first_contact_at'))
                        ->groupBy('user_id')
                        ->get();
                }
            }

            $createdMap = $assignedMonthlyLeads->pluck('created_at', 'id')->toArray();
            $contactDurations = [];

            foreach ($firstContactRows as $row) {
                $leadId = (int)$row->user_id;
                $leadCreatedAt = (int)($createdMap[$leadId] ?? 0);
                $firstContactAt = (int)($row->first_contact_at ?? 0);

                if ($leadCreatedAt > 0 && $firstContactAt >= $leadCreatedAt) {
                    $contactDurations[] = $firstContactAt - $leadCreatedAt;
                }
            }

            $avgContactSeconds = !empty($contactDurations)
                ? (int)round(array_sum($contactDurations) / count($contactDurations))
                : 0;

            $paidLeadCount = 0;
            if (!empty($assignedMonthlyLeadIds)) {
                $paidLeadCount = Sale::query()
                    ->whereIn('buyer_id', $assignedMonthlyLeadIds)
                    ->where('seller_id', $adminId)
                    ->whereNull('refund_at')
                    ->where(function ($query) {
                        $query->whereNotNull('webinar_id')
                            ->orWhereNotNull('bundle_id')
                            ->orWhereNotNull('meeting_id');
                    })
                    ->distinct('buyer_id')
                    ->count('buyer_id');
            }

            $closeRate = $assignedMonthlyCount > 0
                ? round(($paidLeadCount / $assignedMonthlyCount) * 100, 1)
                : 0;

            return [
                'id' => $adminId,
                'name' => $admin->full_name,
                'assignedLeadsInMonth' => $assignedMonthlyCount,
                'totalCalls' => $totalCalls,
                'avgContactSeconds' => $avgContactSeconds,
                'avgContactLabel' => $this->formatContactDurationShort($avgContactSeconds),
                'closeRate' => $closeRate,
            ];
        })->sortByDesc('closeRate')->values();

        return [
            'summary' => [
                'teamCloseRate' => $teamCloseRate,
                'avgContactSeconds' => $teamAvgContactSeconds,
                'avgContactLabel' => $this->formatContactDurationShort($teamAvgContactSeconds),
                'overdueLeads' => $overdueLeads,
            ],
            'teamMembers' => $memberRows,
        ];
    }

    public function getManagerAdminPerformanceDashboardData(): array
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $monthStartTs = $monthStart->timestamp;
        $monthEndTs = $monthEnd->timestamp;
        $daysElapsed = max(1, (int)$now->day);

        $adminUsers = User::query()
            ->where('role_name', Role::$admin)
            ->select('id', 'full_name', 'email', 'avatar', 'avatar_settings')
            ->orderBy('id', 'asc')
            ->get();

        $adminIds = $adminUsers->pluck('id')->toArray();

        if (empty($adminIds)) {
            return [
                'monthlyKpiProgress' => 0,
                'monthlyKpiTarget' => 0,
                'monthlyRevenue' => 0,
                'adminList' => collect(),
                'metrics' => [
                    'totalRevenue' => 0,
                    'avgDealValue' => 0,
                    'avgCallsPerDay' => 0,
                    'avgDropRate' => 0,
                    'avgClosingTimeDays' => 0,
                ],
                'chartData' => [
                    'week' => ['labels' => [], 'revenue' => [], 'revenueKpi' => [], 'sales' => [], 'salesKpi' => []],
                    'month' => ['labels' => [], 'revenue' => [], 'revenueKpi' => [], 'sales' => [], 'salesKpi' => []],
                    'year' => ['labels' => [], 'revenue' => [], 'revenueKpi' => [], 'sales' => [], 'salesKpi' => []],
                ],
                'funnel' => [
                    'newLeads' => 0,
                    'qualified' => 0,
                    'demo' => 0,
                    'wonClosed' => 0,
                ],
                'saleLogs' => collect(),
                'topSellers' => collect(),
            ];
        }

        $meetingIds = Meeting::query()
            ->whereIn('creator_id', $adminIds)
            ->pluck('id')
            ->toArray();

        $paidSalesBase = Sale::query()
            ->whereIn('seller_id', $adminIds)
            ->whereNull('refund_at')
            ->where(function ($query) {
                $query->whereNotNull('webinar_id')
                    ->orWhereNotNull('bundle_id')
                    ->orWhereNotNull('meeting_id');
            });

        $monthlyPaidSalesBase = deepClone($paidSalesBase)
            ->whereBetween('created_at', [$monthStartTs, $monthEndTs]);

        $monthlyRevenue = (float)deepClone($monthlyPaidSalesBase)->sum('total_amount');
        $totalRevenue = (float)deepClone($paidSalesBase)->sum('total_amount');

        $monthlyDealCount = deepClone($monthlyPaidSalesBase)
            ->distinct('buyer_id')
            ->count('buyer_id');

        $avgDealValue = $monthlyDealCount > 0
            ? round($monthlyRevenue / $monthlyDealCount, 2)
            : 0;

        $callsThisMonth = 0;
        $consultedThisMonth = 0;
        if (!empty($meetingIds)) {
            $callsThisMonth = ReserveMeeting::query()
                ->whereIn('meeting_id', $meetingIds)
                ->whereBetween('created_at', [$monthStartTs, $monthEndTs])
                ->count();

            $consultedThisMonth = ReserveMeeting::query()
                ->whereIn('meeting_id', $meetingIds)
                ->whereBetween('created_at', [$monthStartTs, $monthEndTs])
                ->distinct('user_id')
                ->count('user_id');
        }

        $avgCallsPerDay = round($callsThisMonth / $daysElapsed, 1);

        $rejectedThisMonth = Sale::query()
            ->whereIn('seller_id', $adminIds)
            ->whereNotNull('refund_at')
            ->whereBetween('created_at', [$monthStartTs, $monthEndTs])
            ->count();

        $avgDropRate = ($monthlyDealCount + $rejectedThisMonth) > 0
            ? round(($rejectedThisMonth / ($monthlyDealCount + $rejectedThisMonth)) * 100, 1)
            : 0;

        $monthlyPaidSales = deepClone($monthlyPaidSalesBase)->get(['buyer_id', 'seller_id', 'created_at']);

        $avgClosingTimeDays = 0;
        if ($monthlyPaidSales->count() > 0) {
            $totalDays = 0;
            $counted = 0;

            foreach ($monthlyPaidSales as $sale) {
                $firstFormAt = FormSubmission::query()
                    ->where('user_id', $sale->buyer_id)
                    ->min('created_at');

                $firstMeetingAt = ReserveMeeting::query()
                    ->whereIn('meeting_id', $meetingIds)
                    ->where('user_id', $sale->buyer_id)
                    ->min('created_at');

                $touchPoints = array_filter([(int)$firstFormAt, (int)$firstMeetingAt]);

                if (!empty($touchPoints)) {
                    $firstTouchAt = min($touchPoints);
                    $totalDays += max(0, ((int)$sale->created_at - $firstTouchAt) / 86400);
                    $counted++;
                }
            }

            $avgClosingTimeDays = $counted > 0
                ? round($totalDays / $counted, 1)
                : 0;
        }

        $monthlyKpiTarget = 0;
        if (Schema::hasTable('sales_kpis')) {
            $monthlyKpiTarget = (float)DB::table('sales_kpis')
                ->whereIn('user_id', $adminIds)
                ->where('period_type', 'monthly')
                ->whereBetween('period_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->sum('target_revenue');
        }

        $monthlyKpiProgress = $monthlyKpiTarget > 0
            ? min(100, round(($monthlyRevenue / $monthlyKpiTarget) * 100))
            : (($consultedThisMonth > 0) ? min(100, round(($monthlyDealCount / $consultedThisMonth) * 100)) : 0);

        $adminRevenueMap = deepClone($monthlyPaidSalesBase)
            ->select('seller_id', DB::raw('SUM(total_amount) as total_revenue'))
            ->groupBy('seller_id')
            ->pluck('total_revenue', 'seller_id')
            ->toArray();

        $adminClosedMap = deepClone($monthlyPaidSalesBase)
            ->select('seller_id', DB::raw('COUNT(DISTINCT buyer_id) as closed_count'))
            ->groupBy('seller_id')
            ->pluck('closed_count', 'seller_id')
            ->toArray();

        $adminConsultedMap = [];
        if (!empty($meetingIds)) {
            $adminConsultedMap = DB::table('reserve_meetings as rm')
                ->join('meetings as m', 'm.id', '=', 'rm.meeting_id')
                ->whereIn('m.creator_id', $adminIds)
                ->whereBetween('rm.created_at', [$monthStartTs, $monthEndTs])
                ->select('m.creator_id', DB::raw('COUNT(DISTINCT rm.user_id) as consulted_count'))
                ->groupBy('m.creator_id')
                ->pluck('consulted_count', 'creator_id')
                ->toArray();
        }

        $adminList = $adminUsers->map(function ($admin) use ($adminRevenueMap, $adminClosedMap, $adminConsultedMap) {
            $adminId = (int)$admin->id;
            $consulted = (int)($adminConsultedMap[$adminId] ?? 0);
            $closed = (int)($adminClosedMap[$adminId] ?? 0);

            return [
                'id' => $adminId,
                'name' => $admin->full_name,
                'email' => $admin->email,
                'avatar' => $admin->getAvatar(36),
                'monthlyRevenue' => (float)($adminRevenueMap[$adminId] ?? 0),
                'closedDeals' => $closed,
                'closeRate' => $consulted > 0 ? round(($closed / $consulted) * 100, 1) : 0,
            ];
        })->sortByDesc('monthlyRevenue')->values();

        $buildSalesKpiTarget = function (Carbon $startAt, Carbon $endAt, int $actualSales) use ($adminIds) {
            $target = 0;

            if (Schema::hasTable('sales_kpis') && Schema::hasColumn('sales_kpis', 'target_sales')) {
                $target = (int)DB::table('sales_kpis')
                    ->whereIn('user_id', $adminIds)
                    ->whereBetween('period_date', [$startAt->toDateString(), $endAt->toDateString()])
                    ->sum('target_sales');
            }

            if ($target <= 0) {
                $target = (int)max(1, ceil($actualSales * 1.1));
            }

            return $target;
        };

        $chartData = [
            'week' => ['labels' => [], 'revenue' => [], 'revenueKpi' => [], 'sales' => [], 'salesKpi' => []],
            'month' => ['labels' => [], 'revenue' => [], 'revenueKpi' => [], 'sales' => [], 'salesKpi' => []],
            'year' => ['labels' => [], 'revenue' => [], 'revenueKpi' => [], 'sales' => [], 'salesKpi' => []],
        ];

        for ($i = 6; $i >= 0; $i--) {
            $dayStart = $now->copy()->subDays($i)->startOfDay();
            $dayEnd = $dayStart->copy()->endOfDay();
            $actualRevenue = $this->sumRevenueByRange($adminIds, $dayStart->timestamp, $dayEnd->timestamp);
            $actualSales = $this->countPaidDealsByRange($adminIds, $dayStart->timestamp, $dayEnd->timestamp);

            $chartData['week']['labels'][] = $dayStart->format('d/m');
            $chartData['week']['revenue'][] = round($actualRevenue, 2);

            $revenueKpi = $this->getKpiRevenueForRange($adminIds, $dayStart, $dayEnd, 'weekly');
            if ($revenueKpi <= 0) {
                $revenueKpi = $actualRevenue * 1.1;
            }

            $chartData['week']['revenueKpi'][] = round($revenueKpi, 2);
            $chartData['week']['sales'][] = $actualSales;
            $chartData['week']['salesKpi'][] = $buildSalesKpiTarget($dayStart, $dayEnd, $actualSales);
        }

        for ($i = 3; $i >= 0; $i--) {
            $weekStart = $now->copy()->startOfWeek()->subWeeks($i)->startOfDay();
            $weekEnd = $weekStart->copy()->endOfWeek()->endOfDay();
            $actualRevenue = $this->sumRevenueByRange($adminIds, $weekStart->timestamp, $weekEnd->timestamp);
            $actualSales = $this->countPaidDealsByRange($adminIds, $weekStart->timestamp, $weekEnd->timestamp);

            $chartData['month']['labels'][] = $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m');
            $chartData['month']['revenue'][] = round($actualRevenue, 2);

            $revenueKpi = $this->getKpiRevenueForRange($adminIds, $weekStart, $weekEnd, 'weekly');
            if ($revenueKpi <= 0) {
                $revenueKpi = $actualRevenue * 1.1;
            }

            $chartData['month']['revenueKpi'][] = round($revenueKpi, 2);
            $chartData['month']['sales'][] = $actualSales;
            $chartData['month']['salesKpi'][] = $buildSalesKpiTarget($weekStart, $weekEnd, $actualSales);
        }

        $currentYear = (int)$now->format('Y');
        for ($month = 1; $month <= 12; $month++) {
            $periodStart = Carbon::create($currentYear, $month, 1)->startOfDay();
            $periodEnd = $periodStart->copy()->endOfMonth()->endOfDay();
            $actualRevenue = $this->sumRevenueByRange($adminIds, $periodStart->timestamp, $periodEnd->timestamp);
            $actualSales = $this->countPaidDealsByRange($adminIds, $periodStart->timestamp, $periodEnd->timestamp);

            $chartData['year']['labels'][] = trans('panel.month_' . $month);
            $chartData['year']['revenue'][] = round($actualRevenue, 2);

            $revenueKpi = $this->getKpiRevenueForRange($adminIds, $periodStart, $periodEnd, 'monthly');
            if ($revenueKpi <= 0) {
                $revenueKpi = $actualRevenue * 1.1;
            }

            $chartData['year']['revenueKpi'][] = round($revenueKpi, 2);
            $chartData['year']['sales'][] = $actualSales;
            $chartData['year']['salesKpi'][] = $buildSalesKpiTarget($periodStart, $periodEnd, $actualSales);
        }

        $newLeadsCount = User::query()
            ->where('role_name', Role::$user)
            ->whereBetween('created_at', [$monthStartTs, $monthEndTs])
            ->count();

        $qualifiedCount = 0;
        $demoCount = 0;
        if (!empty($meetingIds)) {
            $qualifiedCount = ReserveMeeting::query()
                ->whereIn('meeting_id', $meetingIds)
                ->whereBetween('created_at', [$monthStartTs, $monthEndTs])
                ->distinct('user_id')
                ->count('user_id');

            $demoCount = ReserveMeeting::query()
                ->whereIn('meeting_id', $meetingIds)
                ->where('status', ReserveMeeting::$finished)
                ->whereBetween('created_at', [$monthStartTs, $monthEndTs])
                ->distinct('user_id')
                ->count('user_id');
        }

        $saleLogs = collect();
        if (!empty($meetingIds)) {
            $saleLogs = ReserveMeeting::query()
                ->whereIn('meeting_id', $meetingIds)
                ->whereNotNull('description')
                ->where('description', '!=', '')
                ->with([
                    'user' => function ($query) {
                        $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'email');
                    },
                    'meeting' => function ($query) {
                        $query->select('id', 'creator_id');
                    },
                    'meeting.creator' => function ($query) {
                        $query->select('id', 'full_name');
                    },
                ])
                ->orderBy('created_at', 'desc')
                ->limit(12)
                ->get();
        }

        $topSellerRows = $adminList->map(function ($admin) use ($adminConsultedMap) {
            $consulted = (int)($adminConsultedMap[$admin['id']] ?? 0);

            return [
                'id' => $admin['id'],
                'name' => $admin['name'],
                'email' => $admin['email'],
                'avatar' => $admin['avatar'],
                'revenue' => $admin['monthlyRevenue'],
                'closedDeals' => $admin['closedDeals'],
                'consultedLeads' => $consulted,
                'closeRate' => $admin['closeRate'],
            ];
        })->sortByDesc('revenue')->values()->take(8);

        return [
            'monthlyKpiProgress' => $monthlyKpiProgress,
            'monthlyKpiTarget' => $monthlyKpiTarget,
            'monthlyRevenue' => $monthlyRevenue,
            'adminList' => $adminList,
            'metrics' => [
                'totalRevenue' => $totalRevenue,
                'avgDealValue' => $avgDealValue,
                'avgCallsPerDay' => $avgCallsPerDay,
                'avgDropRate' => $avgDropRate,
                'avgClosingTimeDays' => $avgClosingTimeDays,
            ],
            'chartData' => $chartData,
            'funnel' => [
                'newLeads' => $newLeadsCount,
                'qualified' => $qualifiedCount,
                'demo' => $demoCount,
                'wonClosed' => $monthlyDealCount,
            ],
            'saleLogs' => $saleLogs,
            'topSellers' => $topSellerRows,
        ];
    }

    public function getManagerLeadPerformanceDashboardData(): array
    {
        $now = Carbon::now();
        $todayStart = $now->copy()->startOfDay()->timestamp;
        $todayEnd = $now->copy()->endOfDay()->timestamp;

        $searchRaw = (string)request()->get('search', '');
        $leadTypeRaw = (string)request()->get('lead_type', '');

        $search = trim($searchRaw);
        $leadType = mb_strtolower(trim($leadTypeRaw));

        $adminUsers = User::query()
            ->where('role_name', Role::$admin)
            ->select('id', 'full_name')
            ->orderBy('id', 'asc')
            ->get();

        $adminIds = $adminUsers->pluck('id')->toArray();
        $adminNameMap = $adminUsers->pluck('full_name', 'id')->toArray();

        if (empty($adminIds)) {
            return [
                'summary' => [
                    'respondedToday' => 0,
                    'qualifiedLeads' => 0,
                    'unresponsiveLeads' => 0,
                ],
                'filters' => [
                    'search' => $search,
                    'leadType' => $leadType,
                ],
                'leadRows' => collect(),
            ];
        }

        $leads = User::query()
            ->where('role_name', Role::$user)
            ->whereIn('organ_id', $adminIds)
            ->select('id', 'full_name', 'email', 'organ_id', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $leadIds = $leads->pluck('id')->toArray();

        if (empty($leadIds)) {
            return [
                'summary' => [
                    'respondedToday' => 0,
                    'qualifiedLeads' => 0,
                    'unresponsiveLeads' => 0,
                ],
                'filters' => [
                    'search' => $search,
                    'leadType' => $leadType,
                ],
                'leadRows' => collect(),
            ];
        }

        $birthdayMap = UserMeta::query()
            ->whereIn('user_id', $leadIds)
            ->where('name', 'birthday')
            ->pluck('value', 'user_id')
            ->toArray();

        $meetingStatsMap = DB::table('reserve_meetings as rm')
            ->join('meetings as m', 'm.id', '=', 'rm.meeting_id')
            ->whereIn('m.creator_id', $adminIds)
            ->whereIn('rm.user_id', $leadIds)
            ->select(
                'rm.user_id',
                DB::raw('MAX(rm.created_at) as last_contact_at'),
                DB::raw('MIN(rm.created_at) as first_contact_at'),
                DB::raw('COUNT(*) as total_calls'),
                DB::raw("SUM(CASE WHEN rm.status = 'finished' THEN 1 ELSE 0 END) as finished_calls"),
                DB::raw("SUM(CASE WHEN rm.created_at BETWEEN {$todayStart} AND {$todayEnd} AND rm.status IN ('open', 'finished') THEN 1 ELSE 0 END) as responses_today")
            )
            ->groupBy('rm.user_id')
            ->get()
            ->keyBy('user_id');

        $lastFormActivityMap = FormSubmission::query()
            ->whereIn('user_id', $leadIds)
            ->select('user_id', DB::raw('MAX(created_at) as last_form_at'))
            ->groupBy('user_id')
            ->pluck('last_form_at', 'user_id')
            ->toArray();

        $salesStatsMap = Sale::query()
            ->whereIn('buyer_id', $leadIds)
            ->whereNull('refund_at')
            ->where(function ($query) {
                $query->whereNotNull('webinar_id')
                    ->orWhereNotNull('bundle_id')
                    ->orWhereNotNull('meeting_id');
            })
            ->select('buyer_id', DB::raw('MAX(created_at) as last_paid_at'), DB::raw('COUNT(*) as paid_deals'))
            ->groupBy('buyer_id')
            ->get()
            ->keyBy('buyer_id');

        $leadRows = $leads->map(function ($lead) use ($meetingStatsMap, $lastFormActivityMap, $salesStatsMap, $birthdayMap, $adminNameMap, $now) {
            $leadId = (int)$lead->id;

            $meetingStats = $meetingStatsMap->get($leadId);
            $salesStats = $salesStatsMap->get($leadId);

            $totalCalls = (int)($meetingStats->total_calls ?? 0);
            $finishedCalls = (int)($meetingStats->finished_calls ?? 0);
            $lastContactAt = (int)($meetingStats->last_contact_at ?? 0);
            $responsesToday = (int)($meetingStats->responses_today ?? 0);

            $paidDeals = (int)($salesStats->paid_deals ?? 0);
            $lastPaidAt = (int)($salesStats->last_paid_at ?? 0);
            $hasPaidDeals = $paidDeals > 0;

            $lastFormAt = (int)($lastFormActivityMap[$leadId] ?? 0);

            $activityCandidates = array_filter([
                (int)$lead->updated_at,
                (int)$lead->created_at,
                $lastContactAt,
                $lastFormAt,
                $lastPaidAt,
            ]);

            $lastActivityAt = !empty($activityCandidates) ? max($activityCandidates) : 0;
            $lastActivityLabel = $lastActivityAt > 0
                ? Carbon::createFromTimestamp($lastActivityAt)->diffForHumans()
                : '-';

            $birthdayRaw = $birthdayMap[$leadId] ?? null;
            $birthdayAt = 0;
            if (!empty($birthdayRaw)) {
                if (is_numeric($birthdayRaw)) {
                    $birthdayAt = (int)$birthdayRaw;
                } else {
                    $birthdayAt = (int)strtotime((string)$birthdayRaw);
                }
            }

            $score = 0;
            if ($totalCalls > 0) {
                $score += 20;
            }
            if ($finishedCalls > 0) {
                $score += 20;
            }
            if ($lastFormAt > 0) {
                $score += 15;
            }
            if ($hasPaidDeals) {
                $score += 35;
            }
            if ($lastContactAt > 0) {
                $daysSinceContact = (int)$now->copy()->diffInDays(Carbon::createFromTimestamp($lastContactAt));
                if ($daysSinceContact <= 2) {
                    $score += 10;
                } elseif ($daysSinceContact <= 7) {
                    $score += 5;
                }
            }
            if ($responsesToday > 0) {
                $score += 10;
            }

            $leadScore = min(100, $score);

            if ($hasPaidDeals || $leadScore >= 75) {
                $leadType = 'Hot';
            } elseif ($leadScore >= 45) {
                $leadType = 'Warm';
            } else {
                $leadType = 'Cold';
            }

            $leadStatus = $totalCalls > 0 ? 'Đã tư vấn' : 'Chưa tư vấn';

            if ($hasPaidDeals) {
                $nextStage = 'Đã chốt deal';
            } elseif ($leadScore >= 75) {
                $nextStage = 'Chốt deal';
            } elseif ($finishedCalls > 0) {
                $nextStage = 'Gửi đề xuất';
            } elseif ($totalCalls > 0) {
                $nextStage = 'Hẹn follow-up';
            } else {
                $nextStage = 'Gọi tư vấn lần đầu';
            }

            $daysSinceLastContact = $lastContactAt > 0
                ? (int)$now->copy()->diffInDays(Carbon::createFromTimestamp($lastContactAt))
                : 999;

            $isUnresponsive = !$hasPaidDeals && ($totalCalls === 0 || $daysSinceLastContact >= 7);
            $isQualified = !$hasPaidDeals && ($finishedCalls > 0 || $leadScore >= 70);
            $respondedToday = $responsesToday > 0;

            return [
                'id' => $leadId,
                'name' => $lead->full_name,
                'email' => $lead->email,
                'birthday' => $birthdayAt > 0 ? date('d/m/Y', $birthdayAt) : '-',
                'saleRep' => $adminNameMap[(int)$lead->organ_id] ?? '-',
                'leadScore' => $leadScore,
                'leadStatus' => $leadStatus,
                'leadType' => $leadType,
                'nextStage' => $nextStage,
                'lastActivity' => $lastActivityLabel,
                'respondedToday' => $respondedToday,
                'isQualified' => $isQualified,
                'isUnresponsive' => $isUnresponsive,
            ];
        });

        $summary = [
            'respondedToday' => $leadRows->where('respondedToday', true)->count(),
            'qualifiedLeads' => $leadRows->where('isQualified', true)->count(),
            'unresponsiveLeads' => $leadRows->where('isUnresponsive', true)->count(),
        ];

        if (!empty($search)) {
            $searchNeedle = mb_strtolower($search);

            $leadRows = $leadRows->filter(function ($row) use ($searchNeedle) {
                $haystack = mb_strtolower(implode(' ', [
                    (string)($row['name'] ?? ''),
                    (string)($row['email'] ?? ''),
                    (string)($row['saleRep'] ?? ''),
                    (string)($row['leadStatus'] ?? ''),
                    (string)($row['leadType'] ?? ''),
                ]));

                return mb_strpos($haystack, $searchNeedle) !== false;
            });
        }

        if (in_array($leadType, ['hot', 'warm', 'cold'])) {
            $leadRows = $leadRows->filter(function ($row) use ($leadType) {
                return mb_strtolower((string)($row['leadType'] ?? '')) === $leadType;
            });
        }

        $leadRows = $leadRows->values();

        return [
            'summary' => $summary,
            'filters' => [
                'search' => $search,
                'leadType' => $leadType,
            ],
            'leadRows' => $leadRows,
        ];
    }

    private function formatLearningDuration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '0 giờ';
        }

        $hours = $seconds / 3600;
        if ($hours >= 24) {
            $days = round($hours / 24, 1);
            return rtrim(rtrim(number_format($days, 1, '.', ''), '0'), '.') . ' ngày';
        }

        return rtrim(rtrim(number_format($hours, 1, '.', ''), '0'), '.') . ' giờ';
    }

    private function formatContactDurationShort(int $seconds): string
    {
        if ($seconds <= 0) {
            return '0m';
        }

        $minutes = (int)round($seconds / 60);
        if ($minutes < 60) {
            return $minutes . 'm';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours < 24) {
            return $remainingMinutes > 0
                ? ($hours . 'h ' . $remainingMinutes . 'm')
                : ($hours . 'h');
        }

        $days = round($hours / 24, 1);
        return rtrim(rtrim(number_format($days, 1, '.', ''), '0'), '.') . 'd';
    }

    private function countPaidDealsByRange(array $adminIds, int $startAt, int $endAt): int
    {
        if (empty($adminIds)) {
            return 0;
        }

        return Sale::query()
            ->whereIn('seller_id', $adminIds)
            ->whereNull('refund_at')
            ->whereBetween('created_at', [$startAt, $endAt])
            ->where(function ($query) {
                $query->whereNotNull('webinar_id')
                    ->orWhereNotNull('bundle_id')
                    ->orWhereNotNull('meeting_id');
            })
            ->distinct('buyer_id')
            ->count('buyer_id');
    }

    public function getClassesStatistics()
    {
        $labels = [Webinar::$webinar, Webinar::$course, Webinar::$textLesson];
        $data = [];

        $query = Webinar::where('status', Webinar::$active);
        $allClasses = $query->count();

        foreach ($labels as $label) {
            $count = deepClone($query)->where('type', $label)->count();
            $percent = !empty($allClasses) ? ($count * 100) / $allClasses : 0;
            $data[] = round($percent, 2);
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function getNetProfitChart($type = 'month_of_year')
    {
        $labels = [];
        $data = [];

        $query = Accounting::where('system', 1)
            ->where('tax', 0);

        if ($type == 'day_of_month') {

            for ($day = 1; $day <= 31; $day++) {
                $startDay = strtotime(date('Y-m-' . $day));
                $endDay = strtotime('-1 second', strtotime('+1 day', $startDay));

                $labels[] = str_pad($day, 2, 0, STR_PAD_LEFT);

                $amount = $this->computingAccounting(deepClone($query), $startDay, $endDay);
                $data[] = round($amount, 2);
            }
        } elseif ($type == 'month_of_year') {
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create(date('Y'), $month);

                $start_date = $date->timestamp;
                $end_date = $date->copy()->endOfMonth()->timestamp;

                $labels[] = trans('panel.month_' . $month);

                $amount = $this->computingAccounting(deepClone($query), $start_date, $end_date);
                $data[] = round($amount, 2);
            }
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function computingAccounting($query, $start, $end)
    {
        $additions = deepClone($query)->whereBetween('created_at', [$start, $end])
            ->where('type', Accounting::$addiction)
            ->sum('amount');

        $deductions = deepClone($query)->whereBetween('created_at', [$start, $end])
            ->where('type', Accounting::$deduction)
            ->sum('amount');

        $charge = $additions - $deductions;
        return $charge > 0 ? $charge : 0;
    }

    public function getNetProfitStatistics()
    {
        $dateStartAndEnd = $this->getAllDateStartAndEnd();

        $beginOfDay = $dateStartAndEnd['today']['start'];
        $endOfDay = $dateStartAndEnd['today']['end'];

        $beginOfWeek = $dateStartAndEnd['week']['start'];
        $endOfWeek = $dateStartAndEnd['week']['end'];

        $beginOfMonth = $dateStartAndEnd['month']['start'];
        $endOfMonth = $dateStartAndEnd['month']['end'];

        $beginOfYear = $dateStartAndEnd['year']['start'];
        $endOfYear = $dateStartAndEnd['year']['end'];

        $lastDayStart = $beginOfDay - 24 * 60 * 60;
        $lastDayEnd = $endOfDay - 24 * 60 * 60;

        $lastWeekStart = $beginOfWeek - 7 * 24 * 60 * 60;
        $lastWeekEnd = $endOfWeek - 7 * 24 * 60 * 60;


        $time = time();
        $lastMonthStart = strtotime(date('Y-m-01', strtotime('last month', $time))); // First day of the last month.
        $lastMonthEnd = strtotime(date('Y-m-t', strtotime('last month', $time))); // Last day of the last month.

        $lastYearStart = $beginOfYear - 365 * 24 * 60 * 60;
        $lastYearEnd = $endOfYear - 365 * 24 * 60 * 60;

        $query = Accounting::where('system', 1)
            ->where('tax', 0);


        $todayIncome = $this->computingAccounting(deepClone($query), $beginOfDay, $endOfDay);

        $lastDayIncome = $this->computingAccounting(deepClone($query), $lastDayStart, $lastDayEnd);

        $weekIncome = $this->computingAccounting(deepClone($query), $beginOfWeek, $endOfWeek);

        $lastWeekIncome = $this->computingAccounting(deepClone($query), $lastWeekStart, $lastWeekEnd);

        $monthIncome = $this->computingAccounting(deepClone($query), $beginOfMonth, $endOfMonth);

        $lastMonthIncome = $this->computingAccounting(deepClone($query), $lastMonthStart, $lastMonthEnd);

        $yearIncome = $this->computingAccounting(deepClone($query), $beginOfYear, $endOfYear);

        $lastYearIncome = $this->computingAccounting(deepClone($query), $lastYearStart, $lastYearEnd);

        return [
            'todayIncome' => [
                'amount' => $todayIncome,
                'grow_percent' => $this->getGrowPercent($lastDayIncome, $todayIncome),
            ],
            'weekIncome' => [
                'amount' => $weekIncome,
                'grow_percent' => $this->getGrowPercent($lastWeekIncome, $weekIncome),
            ],
            'monthIncome' => [
                'amount' => $monthIncome,
                'grow_percent' => $this->getGrowPercent($lastMonthIncome, $monthIncome),
            ],
            'yearIncome' => [
                'amount' => $yearIncome,
                'grow_percent' => $this->getGrowPercent($lastYearIncome, $yearIncome),
            ],
        ];
    }

    public function getTopSellingClasses()
    {
        return Webinar::where('status', Webinar::$active)
            ->join('sales', 'webinars.id', '=', 'sales.webinar_id')
            ->select('webinars.*', 'sales.webinar_id',
                DB::raw('count(sales.webinar_id) as sales_count'),
                DB::raw('sum(sales.total_amount) as sales_amount')
            )->whereNull('sales.refund_at')
            ->where('sales.amount', '>', '0')
            ->groupBy('sales.webinar_id')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();
    }

    public function getTopSellingAppointments()
    {
        return Meeting::where('disabled', false)
            ->join('sales', 'meetings.id', '=', 'sales.meeting_id')
            ->select('meetings.*', 'sales.meeting_id',
                DB::raw('count(sales.meeting_id) as sales_count'),
                DB::raw('sum(sales.total_amount) as sales_amount')
            )->whereNull('sales.refund_at')
            ->where('sales.amount', '>', '0')
            ->groupBy('sales.meeting_id')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();
    }

    public function getTopSellingTeachersAndOrganizations($role = 'teachers')
    {
        $users = User::where('status', 'active')
            ->join('sales', 'users.id', '=', 'sales.seller_id')
            ->select('users.*', 'sales.seller_id',
                DB::raw('count(sales.seller_id) as sales_count'),
                DB::raw('sum(sales.total_amount) as sales_amount')
            )->whereNull('sales.refund_at')
            ->where('sales.amount', '>', '0')
            ->where('users.role_name', (($role == 'teachers') ? Role::$teacher : Role::$teacher))
            ->groupBy('sales.seller_id')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        foreach ($users as $user) {
            $duration = Webinar::where('status', Webinar::$active)
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                })->sum('duration');

            $user->classes_durations = $duration;
        }

        return $users;
    }

    public function getMostActiveStudents()
    {
        return User::where('status', 'active')
            ->join('sales', 'users.id', '=', 'sales.buyer_id')
            ->select('users.*', 'sales.buyer_id',
                DB::raw('count(sales.webinar_id) as purchased_classes'),
                DB::raw('count(sales.meeting_id) as reserved_appointments'),
                DB::raw('sum(sales.total_amount) as total_cost')
            )->whereNull('sales.refund_at')
            ->where('sales.amount', '>', '0')
            ->where('users.role_name', Role::$user)
            ->groupBy('sales.buyer_id')
            ->orderBy('purchased_classes', 'desc')
            ->orderBy('reserved_appointments', 'desc')
            ->limit(5)
            ->get();
    }
}


