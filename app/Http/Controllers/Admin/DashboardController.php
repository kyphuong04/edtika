<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\traits\DashboardTrait;
use App\Http\Controllers\Controller;
use App\Models\FeatureWebinar;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Ticket;
use App\Models\Webinar;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    use DashboardTrait;

    public function index()
    {
        $this->authorize('admin_general_dashboard_show');
        $user = auth()->user();

        if (in_array($user->role_name, [Role::$manager, Role::$ceo], true)) {
            return view('admin.organization_dashboard', [
                'pageTitle' => trans('admin/main.general_dashboard_title'),
                'managerDashboard' => $this->getManagerOrganizationDashboardData(),
            ]);
        }

        if ($user->can('admin_general_dashboard_daily_sales_statistics')) {
            $dailySalesTypeStatistics = $this->dailySalesTypeStatistics();
        }

        if ($user->can('admin_general_dashboard_income_statistics')) {
            $getIncomeStatistics = $this->getIncomeStatistics();
        }

        if ($user->can('admin_general_dashboard_total_sales_statistics')) {
            $getTotalSalesStatistics = $this->getTotalSalesStatistics();
        }

        if ($user->can('admin_general_dashboard_new_sales')) {
            $getNewSalesCount = $this->getNewSalesCount();
        }

        if ($user->can('admin_general_dashboard_new_comments')) {
            $getNewCommentsCount = $this->getNewCommentsCount();
        }

        if ($user->can('admin_general_dashboard_new_tickets')) {
            $getNewTicketsCount = $this->getNewTicketsCount();
        }

        if ($user->can('admin_general_dashboard_new_reviews')) {
            $getPendingReviewCount = $this->getPendingReviewCount();
        }

        if ($user->can('admin_general_dashboard_sales_statistics_chart')) {
            $getMonthAndYearSalesChart = $this->getMonthAndYearSalesChart('month_of_year');
            $getMonthAndYearSalesChartStatistics = $this->getMonthAndYearSalesChartStatistics();
        }

        if ($user->can('admin_general_dashboard_recent_comments')) {
            $recentComments = $this->getRecentComments();
        }

        if ($user->can('admin_general_dashboard_recent_tickets')) {
            $recentTickets = $this->getRecentTickets();
        }

        if ($user->can('admin_general_dashboard_recent_webinars')) {
            $recentWebinars = $this->getRecentWebinars();
        }

        if ($user->can('admin_general_dashboard_recent_courses')) {
            $recentCourses = $this->getRecentCourses();
        }

        if ($user->can('admin_general_dashboard_users_statistics_chart')) {
            $usersStatisticsChart = $this->usersStatisticsChart();
        }

        $data = [
            'pageTitle' => trans('admin/main.general_dashboard_title'),
            'dailySalesTypeStatistics' => $dailySalesTypeStatistics ?? null,
            'getIncomeStatistics' => $getIncomeStatistics ?? null,
            'getTotalSalesStatistics' => $getTotalSalesStatistics ?? null,
            'getNewSalesCount' => $getNewSalesCount ?? 0,
            'getNewCommentsCount' => $getNewCommentsCount ?? 0,
            'getNewTicketsCount' => $getNewTicketsCount ?? 0,
            'getPendingReviewCount' => $getPendingReviewCount ?? 0,
            'getMonthAndYearSalesChart' => $getMonthAndYearSalesChart ?? null,
            'getMonthAndYearSalesChartStatistics' => $getMonthAndYearSalesChartStatistics ?? null,
            'recentComments' => $recentComments ?? null,
            'recentTickets' => $recentTickets ?? null,
            'recentWebinars' => $recentWebinars ?? null,
            'recentCourses' => $recentCourses ?? null,
            'usersStatisticsChart' => $usersStatisticsChart ?? null,
        ];

        return view('admin.dashboard', $data);
    }

    public function marketing()
    {
        $this->authorize('admin_marketing_dashboard_show');

        $data = [
            'pageTitle' => trans('admin/main.marketing_dashboard'),
            'organizationMarketingDashboard' => $this->getAdminOrganizationMarketingDashboardData(auth()->user()),
        ];

        return view('admin.marketing_dashboard', $data);
    }

    public function business()
    {
        $this->authorize('admin_general_dashboard_show');

        $user = auth()->user();
        abort_unless(in_array($user->role_name, [Role::$manager, Role::$ceo], true), 403);

        return view('admin.sales_dashboard', [
            'pageTitle' => trans('admin/main.business_dashboard'),
            'businessDashboard' => $this->getManagerBusinessDashboardData(),
        ]);
    }

    public function userGrowth()
    {
        $this->authorize('admin_general_dashboard_show');

        $user = auth()->user();
        abort_unless(in_array($user->role_name, [Role::$manager, Role::$ceo], true), 403);

        return view('admin.user_growth', [
            'pageTitle' => trans('admin/main.user_growth_dashboard'),
            'userGrowthDashboard' => $this->getManagerUserGrowthDashboardData(),
        ]);
    }

    public function learningQuality()
    {
        $this->authorize('admin_general_dashboard_show');

        $user = auth()->user();
        abort_unless(in_array($user->role_name, [Role::$manager, Role::$ceo], true), 403);

        return view('admin.learning_quality', [
            'pageTitle' => trans('admin/main.learning_quality_dashboard'),
            'learningQualityDashboard' => $this->getManagerLearningQualityDashboardData(),
        ]);
    }

    public function teamPerformance()
    {
        $this->authorize('admin_general_dashboard_show');

        $user = auth()->user();
        abort_unless(in_array($user->role_name, [Role::$manager, Role::$ceo], true), 403);

        return view('admin.team_performance', [
            'pageTitle' => trans('admin/main.team_performance_dashboard'),
            'teamPerformanceDashboard' => $this->getManagerTeamPerformanceDashboardData(),
        ]);
    }

    public function adminPerformance()
    {
        $this->authorize('admin_general_dashboard_show');

        $user = auth()->user();
        abort_unless(in_array($user->role_name, [Role::$manager, Role::$ceo], true), 403);

        return view('admin.admin_performance', [
            'pageTitle' => trans('admin/main.admin_performance_dashboard'),
            'adminPerformanceDashboard' => $this->getManagerAdminPerformanceDashboardData(),
        ]);
    }

    public function leadPerformance()
    {
        $this->authorize('admin_general_dashboard_show');

        $user = auth()->user();
        abort_unless(in_array($user->role_name, [Role::$manager, Role::$ceo], true), 403);

        return view('admin.lead_performance', [
            'pageTitle' => trans('admin/main.lead_performance_dashboard'),
            'leadPerformanceDashboard' => $this->getManagerLeadPerformanceDashboardData(),
        ]);
    }

    public function getSaleStatisticsData(Request $request)
    {
        $this->authorize('admin_general_dashboard_sales_statistics_chart');

        $type = $request->get('type');

        $chart = $this->getMonthAndYearSalesChart($type);

        return response()->json([
            'code' => 200,
            'chart' => $chart
        ], 200);
    }

    public function getNetProfitChartAjax(Request $request)
    {

        $type = $request->get('type');

        $chart = $this->getNetProfitChart($type);

        return response()->json([
            'code' => 200,
            'chart' => $chart
        ], 200);
    }

    public function cacheClear()
    {
        $this->authorize('admin_clear_cache');

        Artisan::call('clear:all', [
            '--force' => true
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Website cache successfully cleared.',
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }
}


