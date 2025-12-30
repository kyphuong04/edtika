<?php

namespace App\Http\Controllers\Panel;

use App\Exports\WebinarStudents;
use App\Http\Controllers\Controller;
use App\Models\CourseLearning;
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
     * Show detailed information for a specific student
     */
    public function show($studentId)
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
        })->where('status', 'active')->pluck('id');

        // Verify student is enrolled in instructor's courses
        $enrolledCourses = Sale::where('buyer_id', $studentId)
            ->whereIn('webinar_id', $instructorWebinars)
            ->whereNull('refund_at')
            ->with(['webinar' => function ($q) {
                $q->with(['chapters.chapterItems']);
            }])
            ->get();

        if ($enrolledCourses->isEmpty()) {
            abort(404, 'Student not enrolled in your courses');
        }

        $coursesData = [];
        foreach ($enrolledCourses as $sale) {
            if ($sale->webinar) {
                $progress = $this->calculateCourseProgress($studentId, $sale->webinar_id);
                $quizResults = QuizzesResult::whereHas('quiz', function ($q) use ($sale) {
                    $q->where('webinar_id', $sale->webinar_id);
                })->where('user_id', $studentId)->get();

                $coursesData[] = [
                    'webinar' => $sale->webinar,
                    'progress' => $progress,
                    'quiz_count' => $quizResults->count(),
                    'average_grade' => $quizResults->count() > 0 ? round($quizResults->avg('user_grade'), 2) : 0,
                    'enrolled_at' => $sale->created_at,
                ];
            }
        }

        // Get recent activities
        $recentQuizResults = QuizzesResult::where('user_id', $studentId)
            ->whereHas('quiz', function ($q) use ($instructorWebinars) {
                $q->whereIn('webinar_id', $instructorWebinars);
            })
            ->with(['quiz.webinar'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get support tickets
        $supportTickets = Support::where('user_id', $studentId)
            ->whereHas('webinar', function ($q) use ($instructorWebinars) {
                $q->whereIn('id', $instructorWebinars);
            })
            ->with('webinar')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $data = [
            'pageTitle' => trans('panel.student_details') . ' - ' . $student->full_name,
            'student' => $student,
            'coursesData' => $coursesData,
            'recentQuizResults' => $recentQuizResults,
            'supportTickets' => $supportTickets,
        ];

        return view('design_1.panel.students_tracking.details', $data);
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
