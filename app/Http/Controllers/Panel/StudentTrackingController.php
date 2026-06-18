<?php

namespace App\Http\Controllers\Panel;

use App\Exports\WebinarStudents;
use App\Http\Controllers\Controller;
use App\Models\CourseLearning;
use App\Models\IeltsTestAttempt;
use App\Models\Quiz;
use App\Models\QuizzesResult;
use App\Models\Sale;
use App\Models\Webinar;
use App\Models\WebinarChapterItem;
use App\Models\WebinarAssignment;
use App\Models\WebinarAssignmentHistory;
use App\Models\Comment;
use App\Models\Support;
use App\Models\SupportDepartment;
use App\Models\Ticket;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class StudentTrackingController extends Controller
{
    public $perPage = 10;

    /**
     * Display list of students enrolled in instructor's courses
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Allow teachers/instructors and admins (organizations) to access student tracking
        if (!$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }

        // Get all courses/webinars created by this instructor or organization
        $instructorWebinars = Webinar::where(function ($query) use ($user) {
            $query->where('creator_id', $user->id)
                ->orWhere('teacher_id', $user->id);
        })->where('status', 'active')->pluck('id');

        // Get all students who purchased these courses
        $query = Sale::whereIn('webinar_id', $instructorWebinars)
            ->whereNull('refund_at')
            ->select('buyer_id', DB::raw('COUNT(DISTINCT webinar_id) as courses_count'))
            ->groupBy('buyer_id');

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query, $instructorWebinars);

        // Get student list with their stats
        $page = $request->get('page', 1);
        $count = $this->perPage;
        $total = $copyQuery->get()->count();

        $studentSales = $query
            ->offset(($page - 1) * $count)
            ->limit($count)
            ->get();

        $studentIds = $studentSales->pluck('buyer_id')->toArray();
        
        $students = User::whereIn('id', $studentIds)->get()->map(function ($student) use ($instructorWebinars) {
            // Get student's progress across all instructor's courses
            $enrolledCourses = Sale::where('buyer_id', $student->id)
                ->whereIn('webinar_id', $instructorWebinars)
                ->whereNull('refund_at')
                ->with('webinar')
                ->get();

            $totalProgress = 0;
            $completedCourses = 0;
            $totalQuizResults = 0;
            $averageQuizGrade = 0;

            foreach ($enrolledCourses as $sale) {
                if ($sale->webinar) {
                    // Calculate progress
                    $progress = $this->calculateCourseProgress($student->id, $sale->webinar_id);
                    $totalProgress += $progress;
                    
                    if ($progress >= 100) {
                        $completedCourses++;
                    }

                    // Get quiz results
                    $quizResults = QuizzesResult::whereHas('quiz', function ($q) use ($sale) {
                        $q->where('webinar_id', $sale->webinar_id);
                    })->where('user_id', $student->id)->get();

                    $totalQuizResults += $quizResults->count();
                    if ($quizResults->count() > 0) {
                        $averageQuizGrade += $quizResults->avg('user_grade');
                    }
                }
            }

            $coursesCount = $enrolledCourses->count();
            
            return [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'email' => $student->email,
                'avatar' => $student->getAvatar(48),
                'courses_enrolled' => $coursesCount,
                'courses_completed' => $completedCourses,
                'average_progress' => $coursesCount > 0 ? round($totalProgress / $coursesCount, 2) : 0,
                'total_quiz_results' => $totalQuizResults,
                'average_quiz_grade' => $totalQuizResults > 0 ? round($averageQuizGrade / $coursesCount, 2) : 0,
                'created_at' => $student->created_at,
            ];
        });

        if ($request->ajax()) {
            $html = "";
            foreach ($students as $student) {
                $html .= view('design_1.panel.students_tracking.student_item', ['student' => $student])->render();
            }

            return response()->json([
                'html' => $html,
                'has_more' => ($page * $count) < $total,
            ]);
        }

        // Get filter data
        $allWebinars = Webinar::whereIn('id', $instructorWebinars)->get();

        $data = [
            'pageTitle' => trans('panel.students_tracking'),
            'students' => $students,
            'allWebinars' => $allWebinars,
            'totalStudents' => $total,
            'pagination' => $this->makePagination($request, $students, $total, $count, true),
        ];

        return view('design_1.panel.students_tracking.index', $data);
    }

    /**
     * Show detailed performance page for a specific student (teacher role).
     */
    public function details($student_id)
    {
        $user = auth()->user();

        if (!$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }

        $student = User::findOrFail($student_id);

        $instructorWebinarIds = Webinar::where(function ($q) use ($user) {
            $q->where('creator_id', $user->id)->orWhere('teacher_id', $user->id);
        })->where('status', 'active')->pluck('id')->toArray();

        $enrolledCourses = Sale::where('buyer_id', $student_id)
            ->whereIn('webinar_id', $instructorWebinarIds)
            ->whereNull('refund_at')
            ->with('webinar')
            ->get();

        if ($enrolledCourses->isEmpty()) {
            abort(404, 'Student is not enrolled in any of your courses.');
        }

        // ── Student meta data ──────────────────────────────────────────────
        $metas = DB::table('users_metas')
            ->where('user_id', $student_id)
            ->whereIn('name', ['aim_band', 'mock_test_date'])
            ->pluck('value', 'name')
            ->toArray();
        $student->aim_band  = $metas['aim_band']       ?? null;
        $student->exam_date = $metas['mock_test_date']  ?? null;

        // ── Quiz Accuracy ──────────────────────────────────────────────────
        $quizAccuracy = round((float) (DB::table('quizzes_results')
            ->join('quizzes', 'quizzes.id', '=', 'quizzes_results.quiz_id')
            ->whereIn('quizzes.webinar_id', $instructorWebinarIds)
            ->where('quizzes_results.user_id', $student_id)
            ->where('quizzes.total_mark', '>', 0)
            ->whereNotNull('quizzes_results.user_grade')
            ->selectRaw('AVG(quizzes_results.user_grade / quizzes.total_mark * 100) as v')
            ->value('v') ?? 0), 1);

        // ── Satisfaction Rate (avg star rating by this student for instructor's courses) ──
        $satisfactionRate = round((float) (DB::table('webinar_reviews')
            ->whereIn('webinar_id', $instructorWebinarIds)
            ->where('creator_id', $student_id)
            ->where('status', 'active')
            ->where('rates', '>', 0)
            ->selectRaw('AVG(rates / 5 * 100) as v')
            ->value('v') ?? 0), 1);

        // ── Exercise Accuracy (IELTS practice tests) ───────────────────────
        $exerciseAccuracy = round((float) (DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->where('ielts_tests.type', 'practice')
            ->where('ielts_test_attempts.user_id', $student_id)
            ->whereNotNull('ielts_test_attempts.overall_band')
            ->selectRaw('AVG(ielts_test_attempts.overall_band / 9 * 100) as v')
            ->value('v') ?? 0), 1);

        // ── Improvement Rate ───────────────────────────────────────────────
        $improvementRate = 0.0;
        $mockBands = DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->where('ielts_tests.type', 'mock')
            ->where('ielts_test_attempts.user_id', $student_id)
            ->whereNotNull('ielts_test_attempts.overall_band')
            ->where('ielts_test_attempts.overall_band', '>', 0)
            ->whereNotNull('ielts_test_attempts.completed_at')
            ->orderBy('ielts_test_attempts.completed_at', 'asc')
            ->pluck('ielts_test_attempts.overall_band');
        if ($mockBands->count() >= 2) {
            $first = (float) $mockBands->first();
            $last  = (float) $mockBands->last();
            if ($first > 0) {
                $improvementRate = round((($last - $first) / $first) * 100, 1);
            }
        }

        // ── Skill bands avg for radar (mock tests) ─────────────────────────
        $sbRow = DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->where('ielts_tests.type', 'mock')
            ->where('ielts_test_attempts.user_id', $student_id)
            ->whereNotNull('ielts_test_attempts.completed_at')
            ->selectRaw('
                ROUND(AVG(listening_band),2) as l,
                ROUND(AVG(reading_band),2)   as r,
                ROUND(AVG(writing_band),2)   as w,
                ROUND(AVG(speaking_band),2)  as s,
                ROUND(AVG(overall_band),2)   as o
            ')
            ->first();
        $skillBands = [
            'listening' => (float)($sbRow->l ?? 0),
            'reading'   => (float)($sbRow->r ?? 0),
            'writing'   => (float)($sbRow->w ?? 0),
            'speaking'  => (float)($sbRow->s ?? 0),
            'overall'   => (float)($sbRow->o ?? 0),
        ];

        // Estimated band: avg overall from mocks, fallback to latest attempt
        $student->estimated_band = $skillBands['overall'] > 0
            ? $skillBands['overall']
            : (float)(DB::table('ielts_test_attempts')
                ->where('user_id', $student_id)
                ->whereNotNull('overall_band')
                ->orderByDesc('completed_at')
                ->value('overall_band') ?? 0);

        // ── Weak Points: skills sorted ascending by band ───────────────────
        $weakPoints = collect([
            ['skill' => 'listening', 'label' => 'Listening', 'band' => $skillBands['listening']],
            ['skill' => 'reading',   'label' => 'Reading',   'band' => $skillBands['reading']],
            ['skill' => 'writing',   'label' => 'Writing',   'band' => $skillBands['writing']],
            ['skill' => 'speaking',  'label' => 'Speaking',  'band' => $skillBands['speaking']],
        ])->sortBy('band')->values()->toArray();

        // ── Mock test results list ─────────────────────────────────────────
        $mockTestResults = DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->where('ielts_tests.type', 'mock')
            ->where('ielts_test_attempts.user_id', $student_id)
            ->whereNotNull('ielts_test_attempts.completed_at')
            ->select(
                'ielts_test_attempts.id',
                'ielts_tests.title as test_title',
                'ielts_test_attempts.overall_band',
                'ielts_test_attempts.listening_band',
                'ielts_test_attempts.reading_band',
                'ielts_test_attempts.writing_band',
                'ielts_test_attempts.speaking_band',
                'ielts_test_attempts.completed_at'
            )
            ->orderBy('ielts_test_attempts.completed_at', 'desc')
            ->limit(10)
            ->get();

        // ── Speaking & Writing history ─────────────────────────────────────
        $swHistory = DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->where('ielts_test_attempts.user_id', $student_id)
            ->where(function ($q) {
                $q->where('ielts_test_attempts.speaking_completed', 1)
                  ->orWhere('ielts_test_attempts.writing_completed', 1);
            })
            ->whereNotNull('ielts_test_attempts.completed_at')
            ->select(
                'ielts_test_attempts.id',
                'ielts_tests.title as test_title',
                'ielts_test_attempts.writing_band',
                'ielts_test_attempts.speaking_band',
                'ielts_test_attempts.writing_completed',
                'ielts_test_attempts.speaking_completed',
                'ielts_test_attempts.completed_at'
            )
            ->orderBy('ielts_test_attempts.completed_at', 'desc')
            ->limit(10)
            ->get();

        // ── Ranking among instructor's students (by best overall_band) ─────
        $allBuyers = Sale::whereIn('webinar_id', $instructorWebinarIds)
            ->whereNull('refund_at')->distinct()->pluck('buyer_id')->toArray();
        $allBands = DB::table('ielts_test_attempts')
            ->join('ielts_tests', 'ielts_tests.id', '=', 'ielts_test_attempts.test_id')
            ->where('ielts_tests.type', 'mock')
            ->whereIn('ielts_test_attempts.user_id', $allBuyers)
            ->whereNotNull('ielts_test_attempts.overall_band')
            ->selectRaw('ielts_test_attempts.user_id, MAX(overall_band) as top_band')
            ->groupBy('ielts_test_attempts.user_id')
            ->pluck('top_band', 'user_id')
            ->toArray();
        $myBand      = (float)($allBands[$student_id] ?? 0);
        $rank        = 1 + collect($allBands)->filter(fn($b, $uid) => $uid != $student_id && (float)$b > $myBand)->count();
        $totalRanked = count($allBands);
        $rankDisplay = $totalRanked > 0 ? '#' . $rank : '—';

        // ── Courses data ───────────────────────────────────────────────────
        $coursesData = [];
        foreach ($enrolledCourses as $sale) {
            if (!$sale->webinar) continue;
            $wid      = $sale->webinar->id;
            $progress = $this->calculateCourseProgress($student_id, $wid);

            $completedLessons = DB::table('course_learning as cl')
                ->join('text_lessons as tl', 'tl.id', '=', 'cl.text_lesson_id')
                ->join('webinar_chapters as wc', 'wc.id', '=', 'tl.chapter_id')
                ->where('cl.user_id', $student_id)
                ->where('wc.webinar_id', $wid)
                ->whereNotNull('cl.text_lesson_id')
                ->count();

            $exercisesDone = QuizzesResult::whereHas('quiz', fn($q) => $q->where('webinar_id', $wid))
                ->where('user_id', $student_id)
                ->count();

            $coursesData[] = [
                'webinar'           => $sale->webinar,
                'progress'          => $progress,
                'completed_lessons' => $completedLessons,
                'exercises_done'    => $exercisesDone,
                'enrolled_at'       => $sale->created_at,
            ];
        }

        return view('design_1.panel.students_tracking.details', [
            'pageTitle'         => $student->full_name . ' — Performance',
            'student'           => $student,
            'rankDisplay'       => $rankDisplay,
            'quizAccuracy'      => $quizAccuracy,
            'exerciseAccuracy'  => $exerciseAccuracy,
            'improvementRate'   => $improvementRate,
            'satisfactionRate'  => $satisfactionRate,
            'skillBands'        => $skillBands,
            'weakPoints'        => $weakPoints,
            'mockTestResults'   => $mockTestResults,
            'coursesData'       => $coursesData,
            'swHistory'         => $swHistory,
        ]);
    }

    /**
     * @deprecated – kept for any existing links; calls details() internally.
     */
    public function show($studentId)
    {
        return $this->details($studentId);
    }

    /**
     * Show detailed course progress for a student
     */
    public function courseProgress($studentId, $webinarId)
    {
        $user = auth()->user();
        
        if (!$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }

        $webinar = Webinar::where('id', $webinarId)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->with(['chapters.chapterItems'])
            ->firstOrFail();

        $student = User::findOrFail($studentId);

        // Verify student is enrolled
        $sale = Sale::where('buyer_id', $studentId)
            ->where('webinar_id', $webinarId)
            ->whereNull('refund_at')
            ->firstOrFail();

        // Get all chapter items
        $allItems = [];
        foreach ($webinar->chapters as $chapter) {
            foreach ($chapter->chapterItems as $item) {
                $isCompleted = false;
                
                // Check completion based on item type
                if ($item->type == 'text_lesson' && $item->text_lesson_id) {
                    $isCompleted = CourseLearning::where('user_id', $studentId)
                        ->where('text_lesson_id', $item->text_lesson_id)
                        ->exists();
                } elseif ($item->type == 'file' && $item->file_id) {
                    $isCompleted = CourseLearning::where('user_id', $studentId)
                        ->where('file_id', $item->file_id)
                        ->exists();
                } elseif ($item->type == 'session' && $item->session_id) {
                    $isCompleted = CourseLearning::where('user_id', $studentId)
                        ->where('session_id', $item->session_id)
                        ->exists();
                }

                $allItems[] = [
                    'id' => $item->id,
                    'title' => $item->title ?? $item->file->title ?? 'N/A',
                    'type' => $item->type,
                    'chapter' => $chapter->title,
                    'completed' => $isCompleted,
                ];
            }
        }

        // Get quiz results for this course
        $quizResults = QuizzesResult::whereHas('quiz', function ($q) use ($webinarId) {
            $q->where('webinar_id', $webinarId);
        })
        ->where('user_id', $studentId)
        ->with('quiz')
        ->get();

        // Get assignment submissions
        $assignments = WebinarAssignment::where('webinar_id', $webinarId)
            ->with(['histories' => function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])
            ->get();

        $progress = $this->calculateCourseProgress($studentId, $webinarId);

        $data = [
            'pageTitle' => trans('panel.course_progress') . ' - ' . $webinar->title,
            'student' => $student,
            'webinar' => $webinar,
            'progress' => $progress,
            'allItems' => $allItems,
            'quizResults' => $quizResults,
            'assignments' => $assignments,
        ];

        return view('design_1.panel.students_tracking.course_progress', $data);
    }

    /**
     * Show quiz results for a student
     */
    public function quizResults($studentId)
    {
        $user = auth()->user();
        
        if (!$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }

        $student = User::findOrFail($studentId);

        // Get instructor's courses
        $instructorWebinars = Webinar::where(function ($query) use ($user) {
            $query->where('creator_id', $user->id)
                ->orWhere('teacher_id', $user->id);
        })->pluck('id');

        $quizResults = QuizzesResult::where('user_id', $studentId)
            ->whereHas('quiz', function ($q) use ($instructorWebinars) {
                $q->whereIn('webinar_id', $instructorWebinars);
            })
            ->with(['quiz.webinar'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $data = [
            'pageTitle' => trans('panel.quiz_results') . ' - ' . $student->full_name,
            'student' => $student,
            'quizResults' => $quizResults,
        ];

        return view('design_1.panel.students_tracking.quiz_results', $data);
    }

    /**
     * Show assignments for a student
     */
    public function assignments($studentId)
    {
        $user = auth()->user();
        
        if (!$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }

        $student = User::findOrFail($studentId);

        // Get instructor's courses
        $instructorWebinars = Webinar::where(function ($query) use ($user) {
            $query->where('creator_id', $user->id)
                ->orWhere('teacher_id', $user->id);
        })->pluck('id');

        $assignmentHistories = WebinarAssignmentHistory::where('student_id', $studentId)
            ->whereHas('assignment', function ($q) use ($instructorWebinars) {
                $q->whereIn('webinar_id', $instructorWebinars);
            })
            ->with(['assignment.webinar'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $data = [
            'pageTitle' => trans('panel.assignments') . ' - ' . $student->full_name,
            'student' => $student,
            'assignmentHistories' => $assignmentHistories,
        ];

        return view('design_1.panel.students_tracking.assignments', $data);
    }

    /**
     * Show a unified activity timeline for a student in instructor-owned courses.
     */
    public function activity(Request $request, $studentId)
    {
        $user = auth()->user();

        if (!$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }

        $student = User::findOrFail($studentId);

        $instructorWebinars = Webinar::where(function ($query) use ($user) {
            $query->where('creator_id', $user->id)
                ->orWhere('teacher_id', $user->id);
        })->pluck('id');

        $enrollmentExists = Sale::where('buyer_id', $studentId)
            ->whereIn('webinar_id', $instructorWebinars)
            ->whereNull('refund_at')
            ->exists();

        if (!$enrollmentExists) {
            abort(404, 'Student is not enrolled in any of your courses.');
        }

        $webinarTitles = Webinar::whereIn('id', $instructorWebinars)
            ->get()
            ->pluck('title', 'id')
            ->toArray();

        $activities = collect();

        // Enrollments
        $enrollments = Sale::where('buyer_id', $studentId)
            ->whereIn('webinar_id', $instructorWebinars)
            ->whereNull('refund_at')
            ->select('id', 'webinar_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($enrollments as $sale) {
            $activities->push([
                'time' => (int) $sale->created_at,
                'type' => 'enrollment',
                'webinar_id' => (int) $sale->webinar_id,
                'title' => 'Đăng ký khóa học',
                'description' => $webinarTitles[$sale->webinar_id] ?? 'Khóa học',
                'url' => null,
            ]);
        }

        // Quiz results
        $quizResults = DB::table('quizzes_results as qr')
            ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
            ->where('qr.user_id', $studentId)
            ->whereIn('q.webinar_id', $instructorWebinars)
            ->select('qr.id', 'qr.user_grade', 'qr.status', 'qr.created_at', 'qr.quiz_id', 'q.total_mark', 'q.webinar_id')
            ->orderBy('qr.created_at', 'desc')
            ->get();

        // Load quiz titles via the Quiz model (handles translations) to avoid selecting translatable columns directly in raw queries
        $quizIds = $quizResults->pluck('quiz_id')->unique()->filter()->values()->all();
        $quizTitles = [];
        if (!empty($quizIds)) {
            $quizTitles = \App\Models\Quiz::whereIn('id', $quizIds)
                ->get()
                ->pluck('title', 'id')
                ->toArray();
        }

        foreach ($quizResults as $row) {
            $gradeText = is_null($row->user_grade) ? 'N/A' : ((string) $row->user_grade . '/' . (int) ($row->total_mark ?: 100));

            $activities->push([
                'time' => (int) $row->created_at,
                'type' => 'quiz',
                'webinar_id' => (int) $row->webinar_id,
                'title' => 'Làm quiz: ' . ($quizTitles[$row->quiz_id] ?? 'Quiz'),
                'description' => ($webinarTitles[$row->webinar_id] ?? 'Khóa học') . ' • Điểm: ' . $gradeText,
                'url' => '/panel/quizzes/results/' . $row->id . '/details',
            ]);
        }

        // Assignment submissions
        $assignmentRows = DB::table('webinar_assignment_history as wah')
            ->join('webinar_assignments as wa', 'wa.id', '=', 'wah.assignment_id')
            ->where('wah.student_id', $studentId)
            ->whereIn('wa.webinar_id', $instructorWebinars)
            ->select('wah.id', 'wah.assignment_id', 'wah.grade', 'wah.status', 'wah.created_at', 'wa.webinar_id')
            ->orderBy('wah.created_at', 'desc')
            ->get();

        // Load assignment titles via the WebinarAssignment model (handles translations)
        $assignmentIds = $assignmentRows->pluck('assignment_id')->unique()->filter()->values()->all();
        $assignmentTitles = [];
        if (!empty($assignmentIds)) {
            $assignmentTitles = \App\Models\WebinarAssignment::whereIn('id', $assignmentIds)
                ->get()
                ->pluck('title', 'id')
                ->toArray();
        }

        foreach ($assignmentRows as $row) {
            $gradeText = is_null($row->grade) ? 'Chưa chấm' : (string) $row->grade;

            $activities->push([
                'time' => (int) $row->created_at,
                'type' => 'assignment',
                'webinar_id' => (int) $row->webinar_id,
                'title' => 'Nộp assignment: ' . ($assignmentTitles[$row->assignment_id] ?? 'Assignment'),
                'description' => ($webinarTitles[$row->webinar_id] ?? 'Khóa học') . ' • Điểm: ' . $gradeText,
                'url' => '/panel/assignments/' . $row->assignment_id . '/students',
            ]);
        }

        // IELTS test attempts
        $testAttempts = DB::table('ielts_test_attempts as ita')
            ->join('ielts_tests as it', 'it.id', '=', 'ita.test_id')
            ->leftJoin('webinars as w', 'w.id', '=', 'it.webinar_id')
            ->where('ita.user_id', $studentId)
            ->where(function ($q) use ($instructorWebinars) {
                $q->whereIn('it.webinar_id', $instructorWebinars)
                    ->orWhereIn('w.id', $instructorWebinars);
            })
            ->select('ita.id', 'ita.test_id', 'ita.overall_band', 'ita.completed_at', 'ita.started_at', 'ita.updated_at', 'it.webinar_id')
            ->orderBy('ita.completed_at', 'desc')
            ->get();

        $testIds = $testAttempts->pluck('test_id')->unique()->filter()->values()->all();
        $testTitles = [];
        if (!empty($testIds)) {
            $testTitles = \App\Models\IeltsTest::whereIn('id', $testIds)
                ->get()
                ->pluck('title', 'id')
                ->toArray();
        }

        foreach ($testAttempts as $row) {
            $eventTime = !empty($row->completed_at)
                ? (int) $row->completed_at
                : (!empty($row->started_at) ? (int) $row->started_at : (int) $row->updated_at);
            if ($eventTime <= 0) {
                continue;
            }

            $bandText = is_null($row->overall_band) ? 'N/A' : (string) $row->overall_band;

            $activities->push([
                'time' => $eventTime,
                'type' => 'test',
                'webinar_id' => (int) $row->webinar_id,
                'title' => 'Hoàn thành bài test: ' . ($testTitles[$row->test_id] ?? 'IELTS Test'),
                'description' => ($webinarTitles[$row->webinar_id] ?? 'Khóa học') . ' • Overall band: ' . $bandText,
                'url' => null,
            ]);
        }

        // Lesson/content learning events (course_learning)
        $learningRows = DB::table('course_learning as cl')
            ->leftJoin('text_lessons as tl', 'tl.id', '=', 'cl.text_lesson_id')
            ->leftJoin('files as f', 'f.id', '=', 'cl.file_id')
            ->leftJoin('sessions as s', 's.id', '=', 'cl.session_id')
            ->where('cl.user_id', $studentId)
            ->where(function ($q) use ($instructorWebinars) {
                $q->whereIn('tl.webinar_id', $instructorWebinars)
                    ->orWhereIn('f.webinar_id', $instructorWebinars)
                    ->orWhereIn('s.webinar_id', $instructorWebinars);
            })
            ->select(
                'cl.created_at',
                'cl.text_lesson_id', 'tl.webinar_id as tl_webinar_id',
                'cl.file_id', 'f.webinar_id as f_webinar_id',
                'cl.session_id', 's.webinar_id as s_webinar_id'
            )
            ->orderBy('cl.created_at', 'desc')
            ->get();

        $lessonIds = $learningRows->pluck('text_lesson_id')->unique()->filter()->values()->all();
        $fileIds = $learningRows->pluck('file_id')->unique()->filter()->values()->all();
        $sessionIds = $learningRows->pluck('session_id')->unique()->filter()->values()->all();

        $lessonTitles = [];
        if (!empty($lessonIds)) {
            $lessonTitles = \App\Models\TextLesson::whereIn('id', $lessonIds)
                ->get()
                ->pluck('title', 'id')
                ->toArray();
        }

        $fileTitles = [];
        if (!empty($fileIds)) {
            $fileTitles = \App\Models\File::whereIn('id', $fileIds)
                ->get()
                ->pluck('title', 'id')
                ->toArray();
        }

        $sessionTitles = [];
        if (!empty($sessionIds)) {
            $sessionTitles = \App\Models\Session::whereIn('id', $sessionIds)
                ->get()
                ->pluck('title', 'id')
                ->toArray();
        }

        foreach ($learningRows as $row) {
            $eventTime = (int) $row->created_at;
            if ($eventTime <= 0) {
                continue;
            }

            $itemTitle = ($row->text_lesson_id ? ($lessonTitles[$row->text_lesson_id] ?? null) : null)
                ?: ($row->file_id ? ($fileTitles[$row->file_id] ?? null) : null)
                ?: ($row->session_id ? ($sessionTitles[$row->session_id] ?? null) : null)
                ?: 'Nội dung khóa học';
            $webinarId = $row->tl_webinar_id ?: ($row->f_webinar_id ?: $row->s_webinar_id);

            $activities->push([
                'time' => $eventTime,
                'type' => 'learning',
                'webinar_id' => (int) $webinarId,
                'title' => 'Học nội dung: ' . $itemTitle,
                'description' => $webinarTitles[$webinarId] ?? 'Khóa học',
                'url' => null,
            ]);
        }

        // Student support messages in teacher-owned courses
        $supportMessages = DB::table('support_conversations as sc')
            ->join('supports as sp', 'sp.id', '=', 'sc.support_id')
            ->where('sp.user_id', $studentId)
            ->whereIn('sp.webinar_id', $instructorWebinars)
            ->where('sc.sender_id', $studentId)
            ->whereNull('sp.department_id')
            ->select('sp.id as support_id', 'sp.webinar_id', 'sc.created_at')
            ->orderBy('sc.created_at', 'desc')
            ->get();

        foreach ($supportMessages as $row) {
            $activities->push([
                'time' => (int) $row->created_at,
                'type' => 'message',
                'webinar_id' => (int) $row->webinar_id,
                'title' => 'Gửi tin nhắn hỗ trợ',
                'description' => $webinarTitles[$row->webinar_id] ?? 'Khóa học',
                'url' => '/panel/support/' . $row->support_id . '/conversations',
            ]);
        }

        $enrolledWebinarIds = $enrollments->pluck('webinar_id')->unique()->filter()->values();
        $enrolledWebinars = Webinar::whereIn('id', $enrolledWebinarIds)->get()->keyBy('id');

        $courseStats = [];
        foreach ($enrolledWebinarIds as $wid) {
            $wid = (int) $wid;
            $courseActivities = $activities->filter(function ($a) use ($wid) {
                return (int) ($a['webinar_id'] ?? 0) === $wid;
            });

            $courseStats[] = [
                'webinar_id' => $wid,
                'webinar_title' => $enrolledWebinars[$wid]->title ?? ($webinarTitles[$wid] ?? 'Khóa học'),
                'progress' => (float) $this->calculateCourseProgress($studentId, $wid),
                'enrolled_at' => (int) ($enrollments->firstWhere('webinar_id', $wid)->created_at ?? 0),
                'lessons_done' => $courseActivities->where('type', 'learning')->count(),
                'quizzes_done' => $courseActivities->where('type', 'quiz')->count(),
                'assignments_done' => $courseActivities->where('type', 'assignment')->count(),
                'tests_done' => $courseActivities->where('type', 'test')->count(),
                'messages_sent' => $courseActivities->where('type', 'message')->count(),
                'last_activity_at' => (int) ($courseActivities->max('time') ?? 0),
                'tracking_url' => '/panel/students-tracking/' . $studentId . '/details?webinar=' . $wid,
            ];
        }

        usort($courseStats, function ($a, $b) {
            return $b['last_activity_at'] <=> $a['last_activity_at'];
        });

        $overview = [
            'student_id' => (int) $student->id,
            'student_name' => $student->full_name,
            'student_email' => $student->email,
            'courses_count' => $enrolledWebinarIds->count(),
            'avg_progress' => !empty($courseStats) ? round(collect($courseStats)->avg('progress'), 1) : 0,
            'total_learning_events' => $activities->where('type', 'learning')->count(),
            'total_quiz_attempts' => $activities->where('type', 'quiz')->count(),
            'total_assignment_submissions' => $activities->where('type', 'assignment')->count(),
            'total_test_attempts' => $activities->where('type', 'test')->count(),
            'total_support_messages' => $activities->where('type', 'message')->count(),
            'last_activity_at' => (int) ($activities->max('time') ?? 0),
        ];

        $recentQuizAttempts = $quizResults->take(10)->map(function ($row) use ($quizTitles, $webinarTitles) {
            return [
                'time' => (int) $row->created_at,
                'quiz_title' => $quizTitles[$row->quiz_id] ?? 'Quiz',
                'webinar_title' => $webinarTitles[$row->webinar_id] ?? 'Khóa học',
                'score' => is_null($row->user_grade) ? 'N/A' : ((string) $row->user_grade . '/' . (int) ($row->total_mark ?: 100)),
                'status' => $row->status ?? '-',
                'url' => '/panel/quizzes/results/' . $row->id . '/details',
            ];
        })->values();

        $recentAssignments = $assignmentRows->take(10)->map(function ($row) use ($assignmentTitles, $webinarTitles) {
            return [
                'time' => (int) $row->created_at,
                'assignment_title' => $assignmentTitles[$row->assignment_id] ?? 'Assignment',
                'webinar_title' => $webinarTitles[$row->webinar_id] ?? 'Khóa học',
                'grade' => is_null($row->grade) ? 'Chưa chấm' : (string) $row->grade,
                'status' => $row->status ?? '-',
                'url' => '/panel/assignments/' . $row->assignment_id . '/students',
            ];
        })->values();

        $recentTests = $testAttempts->take(10)->map(function ($row) use ($testTitles, $webinarTitles) {
            $eventTime = !empty($row->completed_at)
                ? (int) $row->completed_at
                : (!empty($row->started_at) ? (int) $row->started_at : (int) $row->updated_at);

            return [
                'time' => $eventTime,
                'test_title' => $testTitles[$row->test_id] ?? 'IELTS Test',
                'webinar_title' => $webinarTitles[$row->webinar_id] ?? 'Khóa học',
                'overall_band' => is_null($row->overall_band) ? 'N/A' : (string) $row->overall_band,
            ];
        })->values();

        $activities = $activities
            ->filter(fn($a) => !empty($a['time']))
            ->sortByDesc('time')
            ->values();

        $page = max(1, (int) $request->get('page', 1));
        $perPage = 20;
        $total = $activities->count();
        $items = $activities->slice(($page - 1) * $perPage, $perPage)->values();

        $pagination = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('design_1.panel.students_tracking.activity', [
            'pageTitle' => 'Activity - ' . $student->full_name,
            'student' => $student,
            'overview' => $overview,
            'courseStats' => $courseStats,
            'recentQuizAttempts' => $recentQuizAttempts,
            'recentAssignments' => $recentAssignments,
            'recentTests' => $recentTests,
            'activities' => $pagination,
        ]);
    }

    /**
     * Send support message to student
     */
    public function sendSupportMessage(Request $request, $studentId)
    {
        try {
            $user = auth()->user();
            
            if (!$user->isTeacher() && !$user->isOrganization()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('panel.access_denied'),
                ], 403);
            }

            $validated = $request->validate([
                'message' => 'required|string|min:10',
                'webinar_id' => 'required|exists:webinars,id',
            ]);

            $student = User::findOrFail($studentId);

            // Verify instructor owns the webinar
            $webinar = Webinar::where('id', $request->webinar_id)
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                })
                ->first();

            if (!$webinar) {
                return response()->json([
                    'success' => false,
                    'message' => trans('panel.webinar_not_found_or_access_denied'),
                ], 403);
            }

            // Create a support ticket
            $department = SupportDepartment::first();
            
            if (!$department) {
                // Create default department if none exists
                $department = SupportDepartment::create([
                    'title' => 'General Support',
                    'created_at' => time(),
                ]);
            }
            
            $support = Support::create([
                'user_id' => $studentId,
                'webinar_id' => $webinar->id,
                'department_id' => $department->id,
                'title' => 'Support from instructor: ' . $user->full_name . ' - ' . $webinar->title,
                'status' => 'open',
                'created_at' => time(),
            ]);

            // Add the message
            $ticket = Ticket::create([
                'support_id' => $support->id,
                'user_id' => $user->id,
                'message' => $request->message,
                'created_at' => time(),
            ]);

            // Send notification to student
            \App\Models\Notification::create([
                'user_id' => $studentId,
                'group_id' => null,
                'sender_id' => $user->id,
                'title' => trans('notification.new_support_message'),
                'message' => trans('notification.instructor_sent_message', ['instructor' => $user->full_name]),
                'sender' => 'system',
                'type' => 'support',
                'created_at' => time(),
            ]);

            return response()->json([
                'success' => true,
                'message' => trans('panel.message_sent_successfully'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending support message: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => trans('panel.error_occurred') . ' ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export students data
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isTeacher() && !$user->isOrganization()) {
            abort(403);
        }

        $instructorWebinars = Webinar::where(function ($query) use ($user) {
            $query->where('creator_id', $user->id)
                ->orWhere('teacher_id', $user->id);
        })->pluck('id');

        $sales = Sale::whereIn('webinar_id', $instructorWebinars)
            ->whereNull('refund_at')
            ->with(['buyer', 'webinar'])
            ->get();

        return Excel::download(new WebinarStudents($sales), 'students_tracking_' . time() . '.xlsx');
    }

    /**
     * Calculate course progress percentage for a student
     */
    protected function calculateCourseProgress($userId, $webinarId)
    {
        $webinar = Webinar::with(['chapters.chapterItems'])->find($webinarId);
        
        if (!$webinar) {
            return 0;
        }

        $totalItems = 0;
        $completedItems = 0;

        foreach ($webinar->chapters as $chapter) {
            foreach ($chapter->chapterItems as $item) {
                $totalItems++;
                
                $isCompleted = false;
                
                // Check completion based on item type
                if ($item->type == 'text_lesson' && $item->text_lesson_id) {
                    $isCompleted = CourseLearning::where('user_id', $userId)
                        ->where('text_lesson_id', $item->text_lesson_id)
                        ->exists();
                } elseif ($item->type == 'file' && $item->file_id) {
                    $isCompleted = CourseLearning::where('user_id', $userId)
                        ->where('file_id', $item->file_id)
                        ->exists();
                } elseif ($item->type == 'session' && $item->session_id) {
                    $isCompleted = CourseLearning::where('user_id', $userId)
                        ->where('session_id', $item->session_id)
                        ->exists();
                }
                
                if ($isCompleted) {
                    $completedItems++;
                }
            }
        }

        return $totalItems > 0 ? round(($completedItems / $totalItems) * 100, 2) : 0;
    }

    /**
     * Handle filters for student list
     */
    protected function handleFilters(Request $request, Builder $query, $instructorWebinars)
    {
        $webinarId = $request->get('webinar_id');
        $search = $request->get('search');

        if (!empty($webinarId) && $webinarId != 'all') {
            $query->where('webinar_id', $webinarId);
        }

        if (!empty($search)) {
            $query->whereHas('buyer', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
