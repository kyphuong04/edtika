<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Panel\WebinarStatisticController;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Gift;
use App\Models\InstallmentOrder;
use App\Models\Role;
use App\Models\Sale;
use App\User;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyStudentsController extends Controller
{
    /**
     * Display list of students enrolled in teacher's courses
     * Similar to admin students view but filtered for teacher's courses only
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Please login');
        }

        // Only teachers can access
        if (!$user->isTeacher()) {
            abort(403);
        }

        // Get all courses created by this teacher
        $instructorWebinars = Webinar::where(function ($query) use ($user) {
            $query->where('creator_id', $user->id)
                ->orWhere('teacher_id', $user->id);
        })->where('status', 'active')->pluck('id');

        if ($instructorWebinars->isEmpty()) {
            // No courses, show empty state
            $data = [
                'pageTitle' => trans('update.my_students'),
                'students' => collect([]),
                'totalStudents' => 0,
                'totalActiveStudents' => 0,
                'totalExpireStudents' => 0,
                'averageLearning' => 0,
                'userGroups' => collect([]),
                'roles' => collect([]),
            ];

            return view('design_1.panel.my_students.index', $data);
        }

        // Get gifts for teacher's courses
        $giftsIds = Gift::query()->whereIn('webinar_id', $instructorWebinars)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->pluck('id')
            ->toArray();

        // Get installment sales
        $installmentSalesIds = [];
        $installmentOrders = InstallmentOrder::query()
            ->whereIn('webinar_id', $instructorWebinars)
            ->where('status', 'open')
            ->get();

        foreach ($installmentOrders as $installmentOrder) {
            $salesId = $installmentOrder->payments->pluck('sale_id')->toArray();
            $installmentSalesIds = array_merge($installmentSalesIds, $salesId);
        }

        // Build query for students
        $query = User::join('sales', 'sales.buyer_id', 'users.id')
            ->leftJoin('webinar_reviews', function ($query) use ($instructorWebinars) {
                $query->on('webinar_reviews.creator_id', 'users.id')
                    ->whereIn('webinar_reviews.webinar_id', $instructorWebinars);
            })
            ->select('users.*', 'webinar_reviews.rates', 'sales.access_to_purchased_item', 'sales.id as sale_id', 'sales.gift_id', 'sales.webinar_id', DB::raw('min(sales.created_at) as purchase_date'))
            ->where(function ($query) use ($instructorWebinars, $giftsIds, $installmentSalesIds) {
                $query->whereIn('sales.webinar_id', $instructorWebinars);
                $query->orWhereIn('sales.gift_id', $giftsIds);
                $query->orWhereIn('sales.id', $installmentSalesIds);
            })
            ->groupBy('sales.buyer_id')
            ->whereNull('sales.refund_at');

        // Apply filters
        $query = $this->applyFilters($query, $request, $instructorWebinars);

        // Get students with pagination
        $students = $query->orderBy('sales.created_at', 'desc')->paginate(15);

        // Get user groups and roles for filters
        $userGroups = Group::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $roles = Role::all();

        // Calculate statistics
        $totalStudents = User::join('sales', 'sales.buyer_id', 'users.id')
            ->where(function ($query) use ($instructorWebinars, $giftsIds) {
                $query->whereIn('sales.webinar_id', $instructorWebinars);
                $query->orWhereIn('sales.gift_id', $giftsIds);
            })
            ->whereNull('sales.refund_at')
            ->distinct('sales.buyer_id')
            ->count('sales.buyer_id');

        $totalExpireStudents = $this->calculateExpiredStudents($instructorWebinars, $giftsIds);

        // Calculate learning progress for all students
        $allStudentsIds = User::join('sales', 'sales.buyer_id', 'users.id')
            ->select('users.*', 'sales.webinar_id', DB::raw('sales.created_at as purchase_date'))
            ->where(function ($query) use ($instructorWebinars, $giftsIds) {
                $query->whereIn('sales.webinar_id', $instructorWebinars);
                $query->orWhereIn('sales.gift_id', $giftsIds);
            })
            ->whereNull('sales.refund_at')
            ->get();

        $webinarStatisticController = new WebinarStatisticController();
        $learningPercents = [];

        foreach ($allStudentsIds as $studentData) {
            $webinar = Webinar::find($studentData->webinar_id);
            if ($webinar) {
                $progress = $webinarStatisticController->getCourseProgressForStudent($webinar, $studentData->id);
                if (!isset($learningPercents[$studentData->id])) {
                    $learningPercents[$studentData->id] = [];
                }
                $learningPercents[$studentData->id][] = $progress;
            }
        }

        // Calculate average learning for each student
        foreach ($students as $key => $student) {
            if (!empty($student->gift_id)) {
                $gift = Gift::query()->where('id', $student->gift_id)->first();

                if (!empty($gift)) {
                    $receipt = $gift->receipt;

                    if (!empty($receipt)) {
                        $webinar = Webinar::find($student->webinar_id);
                        $receipt->rates = $student->rates;
                        $receipt->access_to_purchased_item = $student->access_to_purchased_item;
                        $receipt->sale_id = $student->sale_id;
                        $receipt->purchase_date = $student->purchase_date;
                        $receipt->learning = $webinar ? $webinarStatisticController->getCourseProgressForStudent($webinar, $receipt->id) : 0;

                        $students[$key] = $receipt;
                    } else {
                        // Gift recipient who has not registered yet
                        $newUser = new User();
                        $newUser->full_name = $gift->name;
                        $newUser->email = $gift->email;
                        $newUser->rates = 0;
                        $newUser->access_to_purchased_item = $student->access_to_purchased_item;
                        $newUser->sale_id = $student->sale_id;
                        $newUser->purchase_date = $student->purchase_date;
                        $newUser->learning = 0;

                        $students[$key] = $newUser;
                    }
                }
            } else {
                // Calculate average learning across all courses for this student
                if (!empty($learningPercents[$student->id])) {
                    $student->learning = round(array_sum($learningPercents[$student->id]) / count($learningPercents[$student->id]), 2);
                } else {
                    $student->learning = 0;
                }
            }
        }

        // Calculate overall average learning
        $allLearningValues = [];
        foreach ($learningPercents as $studentLearning) {
            if (!empty($studentLearning)) {
                $allLearningValues[] = array_sum($studentLearning) / count($studentLearning);
            }
        }
        $averageLearning = count($allLearningValues) ? round(array_sum($allLearningValues) / count($allLearningValues), 2) : 0;

        $data = [
            'pageTitle' => trans('update.my_students'),
            'students' => $students,
            'userGroups' => $userGroups,
            'roles' => $roles,
            'totalStudents' => $totalStudents,
            'totalActiveStudents' => $totalStudents - $totalExpireStudents,
            'totalExpireStudents' => $totalExpireStudents,
            'averageLearning' => $averageLearning,
        ];

        return view('design_1.panel.my_students.index', $data);
    }

    /**
     * Apply filters to students query
     */
    private function applyFilters($query, $request, $instructorWebinars)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $full_name = $request->get('full_name');
        $sort = $request->get('sort');
        $group_id = $request->get('group_id');
        $role_id = $request->get('role_id');
        $status = $request->get('status');

        // Date range filter
        $query = fromAndToDateFilter($from, $to, $query, 'sales.created_at');

        // Name search
        if (!empty($full_name)) {
            $query->where('users.full_name', 'like', "%$full_name%");
        }

        // Sorting by rate
        if (!empty($sort)) {
            if ($sort == 'rate_asc') {
                $query->orderBy('webinar_reviews.rates', 'asc');
            }

            if ($sort == 'rate_desc') {
                $query->orderBy('webinar_reviews.rates', 'desc');
            }
        }

        // User group filter
        if (!empty($group_id)) {
            $userIds = GroupUser::where('group_id', $group_id)->pluck('user_id')->toArray();
            $query->whereIn('users.id', $userIds);
        }

        // Role filter
        if (!empty($role_id)) {
            $query->where('users.role_id', $role_id);
        }

        // Status filter (active/expired)
        if (!empty($status) && $status == 'expire') {
            // Get all webinars with access days
            $webinarsWithAccessDays = Webinar::whereIn('id', $instructorWebinars)
                ->whereNotNull('access_days')
                ->get();

            if ($webinarsWithAccessDays->isNotEmpty()) {
                $query->where(function ($q) use ($webinarsWithAccessDays) {
                    foreach ($webinarsWithAccessDays as $webinar) {
                        $accessTimestamp = $webinar->access_days * 24 * 60 * 60;
                        $q->orWhere(function ($subQ) use ($webinar, $accessTimestamp) {
                            $subQ->where('sales.webinar_id', $webinar->id)
                                ->whereRaw('sales.created_at + ? < ?', [$accessTimestamp, time()]);
                        });
                    }
                });
            }
        } elseif (!empty($status) && $status == 'active') {
            // Show only active students (not expired)
            $webinarsWithAccessDays = Webinar::whereIn('id', $instructorWebinars)
                ->whereNotNull('access_days')
                ->pluck('id', 'access_days');

            if ($webinarsWithAccessDays->isNotEmpty()) {
                $query->where(function ($q) use ($webinarsWithAccessDays) {
                    foreach ($webinarsWithAccessDays as $accessDays => $webinarId) {
                        $accessTimestamp = $accessDays * 24 * 60 * 60;
                        $q->orWhere(function ($subQ) use ($webinarId, $accessTimestamp) {
                            $subQ->where('sales.webinar_id', $webinarId)
                                ->whereRaw('sales.created_at + ? >= ?', [$accessTimestamp, time()]);
                        });
                    }
                });
            }
        }

        return $query;
    }

    /**
     * Calculate total expired students across all teacher's courses
     */
    private function calculateExpiredStudents($instructorWebinars, $giftsIds)
    {
        $totalExpireStudents = 0;

        // Get all webinars with access_days set
        $webinarsWithAccessDays = Webinar::whereIn('id', $instructorWebinars)
            ->whereNotNull('access_days')
            ->get();

        foreach ($webinarsWithAccessDays as $webinar) {
            $accessTimestamp = $webinar->access_days * 24 * 60 * 60;

            $expiredCount = User::join('sales', 'sales.buyer_id', 'users.id')
                ->select('users.*', DB::raw('sales.created_at as purchase_date'))
                ->where(function ($query) use ($webinar, $giftsIds) {
                    $query->where('sales.webinar_id', $webinar->id);
                    $query->orWhereIn('sales.gift_id', $giftsIds);
                })
                ->whereRaw('sales.created_at + ? < ?', [$accessTimestamp, time()])
                ->whereNull('sales.refund_at')
                ->distinct('sales.buyer_id')
                ->count('sales.buyer_id');

            $totalExpireStudents += $expiredCount;
        }

        return $totalExpireStudents;
    }
}
