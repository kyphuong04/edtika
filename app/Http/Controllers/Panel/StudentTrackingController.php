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
