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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MyStudentsController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Dashboard (new main entry point)
    // ─────────────────────────────────────────────────────────────────────────

    public function dashboard(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isTeacher()) {
            abort(403);
        }

        $instructorWebinars = Webinar::where(function ($q) use ($user) {
            $q->where('creator_id', $user->id)
              ->orWhere('teacher_id', $user->id);
        })->where('status', 'active')->pluck('id');

        $studentIds    = $this->getTeacherStudentIdsFromWebinars($instructorWebinars);
        $totalStudents = count($studentIds);

        // Summary metric cards
        $quizAccuracy     = $this->calculateAvgQuizAccuracy($studentIds);
        $exerciseAccuracy = $this->calculateAvgExerciseAccuracy($studentIds);
        $testBands        = $this->calculateAvgTestBands($studentIds);
        $improvementRate  = $this->calculateAvgImprovementRate($studentIds);

        // Default chart data (quiz_accuracy)
        $chartJson = json_encode($this->buildWeeklyChartDataByMetric($studentIds, 'quiz_accuracy', 8));

        // Bottom tables
        $studentsNearExam     = $this->getStudentsNearExamDate($studentIds, 8);
        $studentsLowAccuracy  = $this->getStudentsLowAccuracy($studentIds, 8);
        $studentsBelowAimBand = $this->getStudentsBelowAimBand($studentIds, 8);

        return view('design_1.panel.my_students.dashboard', [
            'pageTitle'             => trans('update.my_students'),
            'totalStudents'         => $totalStudents,
            'quizAccuracy'          => $quizAccuracy,
            'exerciseAccuracy'      => $exerciseAccuracy,
            'testBands'             => $testBands,
            'improvementRate'       => $improvementRate,
            'chartJson'             => $chartJson,
            'studentsNearExam'      => $studentsNearExam,
            'studentsLowAccuracy'   => $studentsLowAccuracy,
            'studentsBelowAimBand'  => $studentsBelowAimBand,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Add student form & store
    // ─────────────────────────────────────────────────────────────────────────

    public function addStudentForm(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isTeacher()) {
            abort(403);
        }

        $instructorWebinars = Webinar::where(function ($q) use ($user) {
            $q->where('creator_id', $user->id)
              ->orWhere('teacher_id', $user->id);
        })->where('status', 'active')->with('translations')->get();

        return view('design_1.panel.my_students.add_student', [
            'pageTitle'          => 'Add Student',
            'instructorWebinars' => $instructorWebinars,
        ]);
    }

    public function addStudentStore(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isTeacher()) {
            abort(403);
        }

        $request->validate([
            'email'      => 'required|email',
            'webinar_id' => 'required|integer',
        ]);

        // Verify the webinar belongs to this teacher
        $webinar = Webinar::where('id', $request->webinar_id)
            ->where(function ($q) use ($user) {
                $q->where('creator_id', $user->id)->orWhere('teacher_id', $user->id);
            })->first();

        if (!$webinar) {
            return back()->withErrors(['webinar_id' => 'Khóa học không hợp lệ.']);
        }

        $student = User::where('email', $request->email)->first();

        if (!$student) {
            return back()->withErrors(['email' => 'Không tìm thấy học viên với email này.'])->withInput();
        }

        // Check if already enrolled
        $exists = Sale::where('buyer_id', $student->id)
            ->where('webinar_id', $webinar->id)
            ->whereNull('refund_at')
            ->exists();

        if ($exists) {
            return back()->with('add_student_error', 'Học viên đã được đăng ký khóa học này.')->withInput();
        }

        // Create free enrollment sale record
        Sale::create([
            'buyer_id'   => $student->id,
            'seller_id'  => $user->id,
            'webinar_id' => $webinar->id,
            'total_amount' => 0,
            'created_at'   => time(),
            'updated_at'   => time(),
        ]);

        return redirect('/panel/my-students/list')
            ->with('add_student_success', 'Học viên đã được thêm thành công.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX chart data endpoint
    // ─────────────────────────────────────────────────────────────────────────

    public function chartData(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isTeacher()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Close session early to prevent session locking during long queries
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $metric = $request->get('metric', 'quiz_accuracy');
        $weeks  = min((int) $request->get('weeks', 8), 24);

        $instructorWebinars = Webinar::where(function ($q) use ($user) {
            $q->where('creator_id', $user->id)
              ->orWhere('teacher_id', $user->id);
        })->where('status', 'active')->pluck('id');

        $studentIds = $this->getTeacherStudentIdsFromWebinars($instructorWebinars);

        return response()->json($this->buildWeeklyChartDataByMetric($studentIds, $metric, $weeks));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Existing student list
    // ─────────────────────────────────────────────────────────────────────────

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

        // Get students with pagination (default order: newest sale first, unless a sort is active)
        if (empty($request->get('sort'))) {
            $query->orderBy('sales.created_at', 'desc');
        }
        $students = $query->paginate(15);

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

        // ── Batch-fetch extra card data ────────────────────────────────────────
        $studentDisplayIds = $students->map(fn($s) => $s->id)->filter()->unique()->values()->toArray();

        $aimBands = DB::table('users_metas')
            ->whereIn('user_id', $studentDisplayIds)
            ->where('name', 'aim_band')
            ->pluck('value', 'user_id')
            ->toArray();

        $examDates = DB::table('users_metas')
            ->whereIn('user_id', $studentDisplayIds)
            ->where('name', 'mock_test_date')
            ->pluck('value', 'user_id')
            ->toArray();

        $estimatedBands = [];
        $lastAttemptDates = [];
        if (!empty($studentDisplayIds)) {
            $latestAttempts = DB::table('ielts_test_attempts as a')
                ->whereIn('a.user_id', $studentDisplayIds)
                ->whereNotNull('a.overall_band')
                ->whereNotNull('a.completed_at')
                ->whereRaw('a.completed_at = (
                    SELECT MAX(a2.completed_at) FROM ielts_test_attempts a2
                    WHERE a2.user_id = a.user_id
                      AND a2.overall_band IS NOT NULL
                      AND a2.completed_at IS NOT NULL
                )')
                ->select('a.user_id', 'a.overall_band', 'a.completed_at')
                ->get();
            foreach ($latestAttempts as $row) {
                $estimatedBands[$row->user_id]  = $row->overall_band;
                $lastAttemptDates[$row->user_id] = $row->completed_at;
            }
        }

        $webinarIdsForTitles = $students->pluck('webinar_id')->filter()->unique()->toArray();
        $webinarTitles = [];
        if (!empty($webinarIdsForTitles)) {
            $webinarTitles = Webinar::whereIn('id', $webinarIdsForTitles)
                ->with('translations')
                ->get()
                ->pluck('title', 'id')
                ->toArray();
        }

        // Attach extra data to each student object
        foreach ($students as $student) {
            if (!empty($student->id)) {
                $student->aim_band         = $aimBands[$student->id]          ?? null;
                $student->exam_date        = $examDates[$student->id]         ?? null;
                $student->estimated_band   = $estimatedBands[$student->id]    ?? null;
                $student->course_title     = $webinarTitles[$student->webinar_id] ?? null;
                $student->last_activity_at = $lastAttemptDates[$student->id]  ?? null;
            }
        }
        // ── End batch-fetch ────────────────────────────────────────────────────

        $data = [
            'pageTitle'           => trans('update.my_students'),
            'students'            => $students,
            'userGroups'          => $userGroups,
            'roles'               => $roles,
            'totalStudents'       => $totalStudents,
            'totalActiveStudents' => $totalStudents - $totalExpireStudents,
            'totalExpireStudents' => $totalExpireStudents,
            'averageLearning'     => $averageLearning,
        ];

        return view('design_1.panel.my_students.index', $data);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers — student ID fetch
    // ─────────────────────────────────────────────────────────────────────────

    private function getTeacherStudentIdsFromWebinars($instructorWebinars): array
    {
        if ($instructorWebinars->isEmpty()) {
            return [];
        }

        return DB::table('sales')
            ->whereIn('webinar_id', $instructorWebinars)
            ->whereNull('refund_at')
            ->distinct()
            ->pluck('buyer_id')
            ->toArray();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers — summary metric calculations
    // ─────────────────────────────────────────────────────────────────────────

    private function calculateAvgQuizAccuracy(array $studentIds): float
    {
        if (empty($studentIds)) return 0.0;

        $result = DB::table('quizzes_results')
            ->join('quizzes', 'quizzes.id', '=', 'quizzes_results.quiz_id')
            ->whereIn('quizzes_results.user_id', $studentIds)
            ->where('quizzes.total_mark', '>', 0)
            ->whereNotNull('quizzes_results.user_grade')
            ->selectRaw('AVG(quizzes_results.user_grade / quizzes.total_mark * 100) as accuracy')
            ->value('accuracy');

        return round((float) ($result ?? 0), 1);
    }

    private function calculateAvgExerciseAccuracy(array $studentIds): float
    {
        if (empty($studentIds)) return 0.0;

        $result = DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->whereIn('ielts_test_attempts.user_id', $studentIds)
            ->where('ielts_tests.type', 'practice')
            ->whereNotNull('ielts_test_attempts.completed_at')
            ->whereNotNull('ielts_test_attempts.overall_band')
            ->selectRaw('AVG(ielts_test_attempts.overall_band / 9 * 100) as accuracy')
            ->value('accuracy');

        return round((float) ($result ?? 0), 1);
    }

    private function calculateAvgTestBands(array $studentIds): array
    {
        $empty = ['listening' => 0, 'reading' => 0, 'writing' => 0, 'speaking' => 0, 'overall' => 0];

        if (empty($studentIds)) return $empty;

        $result = DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->whereIn('ielts_test_attempts.user_id', $studentIds)
            ->where('ielts_tests.type', 'mock')
            ->whereNotNull('ielts_test_attempts.completed_at')
            ->selectRaw('
                AVG(ielts_test_attempts.listening_band) as listening,
                AVG(ielts_test_attempts.reading_band)   as reading,
                AVG(ielts_test_attempts.writing_band)   as writing,
                AVG(ielts_test_attempts.speaking_band)  as speaking,
                AVG(ielts_test_attempts.overall_band)   as overall
            ')
            ->first();

        return [
            'listening' => round((float) ($result->listening ?? 0), 2),
            'reading'   => round((float) ($result->reading   ?? 0), 2),
            'writing'   => round((float) ($result->writing   ?? 0), 2),
            'speaking'  => round((float) ($result->speaking  ?? 0), 2),
            'overall'   => round((float) ($result->overall   ?? 0), 2),
        ];
    }

    private function calculateAvgImprovementRate(array $studentIds): float
    {
        if (empty($studentIds)) return 0.0;

        // Fetch each student's earliest mock overall_band
        $firstBands = DB::table('ielts_test_attempts as a')
            ->join('ielts_tests as t', 't.id', '=', 'a.test_id')
            ->whereIn('a.user_id', $studentIds)
            ->where('t.type', 'mock')
            ->whereNotNull('a.completed_at')
            ->whereNotNull('a.overall_band')
            ->where('a.overall_band', '>', 0)
            ->whereRaw('a.completed_at = (
                SELECT MIN(a2.completed_at) FROM ielts_test_attempts a2
                JOIN ielts_tests t2 ON t2.id = a2.test_id
                WHERE a2.user_id = a.user_id AND t2.type = "mock"
                  AND a2.completed_at IS NOT NULL AND a2.overall_band IS NOT NULL
            )')
            ->pluck('a.overall_band', 'a.user_id')
            ->toArray();

        // Fetch each student's latest mock overall_band
        $lastBands = DB::table('ielts_test_attempts as a')
            ->join('ielts_tests as t', 't.id', '=', 'a.test_id')
            ->whereIn('a.user_id', $studentIds)
            ->where('t.type', 'mock')
            ->whereNotNull('a.completed_at')
            ->whereNotNull('a.overall_band')
            ->whereRaw('a.completed_at = (
                SELECT MAX(a2.completed_at) FROM ielts_test_attempts a2
                JOIN ielts_tests t2 ON t2.id = a2.test_id
                WHERE a2.user_id = a.user_id AND t2.type = "mock"
                  AND a2.completed_at IS NOT NULL AND a2.overall_band IS NOT NULL
            )')
            ->pluck('a.overall_band', 'a.user_id')
            ->toArray();

        $rates = [];
        foreach ($firstBands as $uid => $firstBand) {
            if (isset($lastBands[$uid]) && $firstBand > 0) {
                $rates[] = (((float) $lastBands[$uid] - (float) $firstBand) / (float) $firstBand) * 100;
            }
        }

        return count($rates) > 0 ? round(array_sum($rates) / count($rates), 1) : 0.0;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers — weekly chart data
    // ─────────────────────────────────────────────────────────────────────────

    private function buildWeeklyChartDataByMetric(array $studentIds, string $metric, int $weeks): array
    {
        $labels           = [];
        $myStudentsData   = [];
        $allStudentsData  = [];
        $kpiData          = [];
        $skillData        = ['listening' => [], 'reading' => [], 'writing' => [], 'speaking' => []];

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek()->timestamp;
            $weekEnd   = Carbon::now()->subWeeks($i)->endOfWeek()->timestamp;
            $labels[]  = 'W' . Carbon::now()->subWeeks($i)->format('W') . '/' . Carbon::now()->subWeeks($i)->format('y');

            switch ($metric) {
                case 'quiz_accuracy':
                    $myStudentsData[]  = $this->weeklyQuizAccuracy($studentIds, $weekStart, $weekEnd);
                    $allStudentsData[] = Cache::remember("all_quiz_acc_{$weekStart}", 1800, fn() => $this->weeklyQuizAccuracy(null, $weekStart, $weekEnd));
                    $kpiData[]         = 70;
                    break;

                case 'exercise_accuracy':
                    $myStudentsData[]  = $this->weeklyExerciseAccuracy($studentIds, $weekStart, $weekEnd);
                    $allStudentsData[] = Cache::remember("all_ex_acc_{$weekStart}", 1800, fn() => $this->weeklyExerciseAccuracy(null, $weekStart, $weekEnd));
                    $kpiData[]         = 70;
                    break;

                case 'test_band':
                    foreach (['listening', 'reading', 'writing', 'speaking'] as $skill) {
                        $skillData[$skill][] = $this->weeklyTestBandForSkill($studentIds, $skill, $weekStart, $weekEnd);
                    }
                    break;

                case 'improvement_rate':
                    $myStudentsData[]  = $this->weeklyImprovementRate($studentIds, $weekStart, $weekEnd);
                    $allStudentsData[] = Cache::remember("all_impr_rate_{$weekStart}", 1800, fn() => $this->weeklyImprovementRate(null, $weekStart, $weekEnd));
                    $kpiData[]         = 10;
                    break;
            }
        }

        if ($metric === 'test_band') {
            return [
                'labels'  => $labels,
                'metric'  => $metric,
                'yLabel'  => '% Band (band/9×100)',
                'datasets' => [
                    ['label' => 'Listening', 'data' => $skillData['listening'], 'borderColor' => '#5482ff', 'backgroundColor' => 'rgba(84,130,255,0.08)', 'tension' => 0.4, 'fill' => false],
                    ['label' => 'Reading',   'data' => $skillData['reading'],   'borderColor' => '#22b67f', 'backgroundColor' => 'rgba(34,182,127,0.08)', 'tension' => 0.4, 'fill' => false],
                    ['label' => 'Writing',   'data' => $skillData['writing'],   'borderColor' => '#ff9f43', 'backgroundColor' => 'rgba(255,159,67,0.08)',  'tension' => 0.4, 'fill' => false],
                    ['label' => 'Speaking',  'data' => $skillData['speaking'],  'borderColor' => '#e74c3c', 'backgroundColor' => 'rgba(231,76,60,0.08)',   'tension' => 0.4, 'fill' => false],
                ],
            ];
        }

        $metricLabels = [
            'quiz_accuracy'    => 'Quiz Accuracy (%)',
            'exercise_accuracy'=> 'Exercise Accuracy (%)',
            'improvement_rate' => 'Improvement Rate (%)',
        ];

        return [
            'labels'  => $labels,
            'metric'  => $metric,
            'yLabel'  => $metricLabels[$metric] ?? '%',
            'datasets' => [
                ['label' => 'My Students',  'data' => $myStudentsData,  'borderColor' => '#5482ff', 'backgroundColor' => 'rgba(84,130,255,0.08)',  'tension' => 0.4, 'fill' => true],
                ['label' => 'All Students', 'data' => $allStudentsData, 'borderColor' => '#22b67f', 'backgroundColor' => 'rgba(34,182,127,0.08)',  'tension' => 0.4, 'fill' => false],
                ['label' => 'KPI',          'data' => $kpiData,          'borderColor' => '#ff9f43', 'backgroundColor' => 'transparent',            'tension' => 0,   'fill' => false, 'borderDash' => [6, 4]],
            ],
        ];
    }

    private function weeklyQuizAccuracy(?array $studentIds, int $start, int $end): float
    {
        $q = DB::table('quizzes_results')
            ->join('quizzes', 'quizzes.id', '=', 'quizzes_results.quiz_id')
            ->where('quizzes.total_mark', '>', 0)
            ->whereNotNull('quizzes_results.user_grade')
            ->whereBetween('quizzes_results.created_at', [$start, $end]);

        if ($studentIds !== null && !empty($studentIds)) {
            $q->whereIn('quizzes_results.user_id', $studentIds);
        }

        return round((float) ($q->selectRaw('AVG(quizzes_results.user_grade / quizzes.total_mark * 100) as v')->value('v') ?? 0), 1);
    }

    private function weeklyExerciseAccuracy(?array $studentIds, int $start, int $end): float
    {
        $q = DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->where('ielts_tests.type', 'practice')
            ->whereNotNull('ielts_test_attempts.overall_band')
            ->whereBetween('ielts_test_attempts.completed_at', [$start, $end]);

        if ($studentIds !== null && !empty($studentIds)) {
            $q->whereIn('ielts_test_attempts.user_id', $studentIds);
        }

        return round((float) ($q->selectRaw('AVG(ielts_test_attempts.overall_band / 9 * 100) as v')->value('v') ?? 0), 1);
    }

    private function weeklyTestBandForSkill(?array $studentIds, string $skill, int $start, int $end): float
    {
        $col = "ielts_test_attempts.{$skill}_band";

        $q = DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->where('ielts_tests.type', 'mock')
            ->whereNotNull('ielts_test_attempts.completed_at')
            ->whereNotNull($col)
            ->whereBetween('ielts_test_attempts.completed_at', [$start, $end]);

        if ($studentIds !== null && !empty($studentIds)) {
            $q->whereIn('ielts_test_attempts.user_id', $studentIds);
        }

        return round((float) ($q->selectRaw("AVG($col / 9 * 100) as v")->value('v') ?? 0), 1);
    }

    private function weeklyImprovementRate(?array $studentIds, int $start, int $end): float
    {
        // Students who completed a mock test within the week
        $q = DB::table('ielts_test_attempts as a')
            ->join('ielts_tests as t', 't.id', '=', 'a.test_id')
            ->where('t.type', 'mock')
            ->whereNotNull('a.overall_band')
            ->where('a.overall_band', '>', 0)
            ->whereBetween('a.completed_at', [$start, $end]);

        if ($studentIds !== null && !empty($studentIds)) {
            $q->whereIn('a.user_id', $studentIds);
        }

        $weekBands = $q->select('a.user_id', DB::raw('MAX(a.overall_band) as band'))
            ->groupBy('a.user_id')
            ->pluck('band', 'user_id')
            ->toArray();

        if (empty($weekBands)) return 0.0;

        // Batch-fetch all first bands in a single query instead of N+1 per-user queries
        $userIds = array_keys($weekBands);

        $allEarliestAttempts = DB::table('ielts_test_attempts as a2')
            ->join('ielts_tests as t2', 't2.id', '=', 'a2.test_id')
            ->whereIn('a2.user_id', $userIds)
            ->where('t2.type', 'mock')
            ->whereNotNull('a2.overall_band')
            ->where('a2.overall_band', '>', 0)
            ->whereNotNull('a2.completed_at')
            ->orderBy('a2.completed_at', 'asc')
            ->select('a2.user_id', 'a2.overall_band')
            ->get();

        $firstBands = [];
        foreach ($allEarliestAttempts as $att) {
            if (!isset($firstBands[$att->user_id])) {
                $firstBands[$att->user_id] = $att->overall_band;
            }
        }

        $rates = [];
        foreach ($weekBands as $uid => $latestBand) {
            $firstBand = $firstBands[$uid] ?? null;
            if ($firstBand && (float) $firstBand > 0) {
                $rates[] = (((float) $latestBand - (float) $firstBand) / (float) $firstBand) * 100;
            }
        }

        return count($rates) > 0 ? round(array_sum($rates) / count($rates), 1) : 0.0;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers — bottom table queries
    // ─────────────────────────────────────────────────────────────────────────

    private function getStudentsNearExamDate(array $studentIds, int $limit = 8): \Illuminate\Support\Collection
    {
        if (empty($studentIds)) return collect([]);

        return DB::table('users')
            ->join('users_metas', function ($join) {
                $join->on('users_metas.user_id', '=', 'users.id')
                     ->where('users_metas.name', '=', 'mock_test_date');
            })
            ->whereIn('users.id', $studentIds)
            ->whereNotNull('users_metas.value')
            ->where('users_metas.value', '>=', date('Y-m-d'))
            ->select('users.id', 'users.full_name', 'users.avatar', 'users_metas.value as exam_date')
            ->orderBy('users_metas.value', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($s) {
                $s->days_remaining = max(0, Carbon::today()->diffInDays(Carbon::parse($s->exam_date), false));
                return $s;
            });
    }

    private function getStudentsLowAccuracy(array $studentIds, int $limit = 8): \Illuminate\Support\Collection
    {
        if (empty($studentIds)) return collect([]);

        // Use EXISTS for better performance on large quiz results table
        return DB::table('users')
            ->select('users.id', 'users.full_name', 'users.avatar',
                DB::raw('(
                    SELECT ROUND(AVG(qr.user_grade / q.total_mark * 100), 1)
                    FROM quizzes_results qr
                    JOIN quizzes q ON q.id = qr.quiz_id
                    WHERE qr.user_id = users.id
                      AND q.total_mark > 0
                      AND qr.user_grade IS NOT NULL
                    LIMIT 1
                ) as accuracy')
            )
            ->whereIn('users.id', $studentIds)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('quizzes_results as qr')
                      ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
                      ->whereRaw('qr.user_id = users.id')
                      ->where('q.total_mark', '>', 0)
                      ->whereNotNull('qr.user_grade');
            })
            ->having('accuracy', '<', 60) // Only get low accuracy students
            ->orderBy('accuracy', 'asc')
            ->limit($limit)
            ->get();
    }

    private function getStudentsBelowAimBand(array $studentIds, int $limit = 8): \Illuminate\Support\Collection
    {
        if (empty($studentIds)) return collect([]);

        // Use window function for better performance instead of correlated subquery
        $latestBands = DB::select("
            SELECT user_id, overall_band
            FROM (
                SELECT a.user_id, a.overall_band,
                       ROW_NUMBER() OVER (PARTITION BY a.user_id ORDER BY a.completed_at DESC) as rn
                FROM ielts_test_attempts a
                JOIN ielts_tests t ON t.id = a.test_id
                WHERE a.user_id IN (" . implode(',', array_map('intval', $studentIds)) . ")
                  AND t.type = 'mock'
                  AND a.completed_at IS NOT NULL
                  AND a.overall_band IS NOT NULL
            ) ranked
            WHERE rn = 1
        ");

        if (empty($latestBands)) return collect([]);

        $latestBandsArray = [];
        foreach ($latestBands as $band) {
            $latestBandsArray[$band->user_id] = $band->overall_band;
        }

        return DB::table('users')
            ->join('users_metas as um', function ($join) {
                $join->on('um.user_id', '=', 'users.id')
                     ->where('um.name', '=', 'aim_band');
            })
            ->whereIn('users.id', array_keys($latestBandsArray))
            ->whereNotNull('um.value')
            ->select('users.id', 'users.full_name', 'users.avatar', 'um.value as aim_band')
            ->limit($limit * 3) // Get more to filter in PHP
            ->get()
            ->filter(function ($s) use ($latestBandsArray) {
                $actual = (float) ($latestBandsArray[$s->id] ?? 0);
                return $actual > 0 && $actual < (float) $s->aim_band;
            })
            ->map(function ($s) use ($latestBandsArray) {
                $s->actual_band = round((float) ($latestBandsArray[$s->id] ?? 0), 1);
                $s->gap         = round((float) $s->aim_band - $s->actual_band, 1);
                return $s;
            })
            ->sortByDesc('gap')
            ->take($limit)
            ->values();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Existing private helpers (filters, expired students)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Apply filters to students query
     */
    private function applyFilters($query, $request, $instructorWebinars)
    {
        $from      = $request->input('from');
        $to        = $request->input('to');
        $full_name = $request->get('full_name') ?? $request->get('search');
        $sort      = $request->get('sort');
        $group_id  = $request->get('group_id');
        $role_id   = $request->get('role_id');
        $status    = $request->get('status');

        // Date range filter
        $query = fromAndToDateFilter($from, $to, $query, 'sales.created_at');

        // Name / search filter
        if (!empty($full_name)) {
            $query->where('users.full_name', 'like', "%$full_name%");
        }

        // Sorting
        if (!empty($sort)) {
            if ($sort === 'rate_asc') {
                $query->orderBy('webinar_reviews.rates', 'asc');
            } elseif ($sort === 'rate_desc') {
                $query->orderBy('webinar_reviews.rates', 'desc');
            } elseif ($sort === 'name_asc') {
                $query->orderBy('users.full_name', 'asc');
            } elseif ($sort === 'name_desc') {
                $query->orderBy('users.full_name', 'desc');
            } elseif ($sort === 'active_asc') {
                $query->orderBy('users.updated_at', 'asc');
            } elseif ($sort === 'active_desc') {
                $query->orderBy('users.updated_at', 'desc');
            } elseif ($sort === 'exam_asc' || $sort === 'exam_desc') {
                // Sort by exam date via LEFT JOIN on users_metas
                $query->leftJoin(DB::raw("(SELECT user_id, value as exam_sort_date FROM users_metas WHERE name = 'mock_test_date') as em_sort"), function ($join) {
                    $join->on('em_sort.user_id', '=', 'users.id');
                });
                $dir = ($sort === 'exam_asc') ? 'asc' : 'desc';
                $query->orderBy('em_sort.exam_sort_date', $dir);
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
