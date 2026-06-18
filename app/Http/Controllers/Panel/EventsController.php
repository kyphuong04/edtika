<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\Bundle;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\InstallmentOrder;
use App\Models\InstallmentOrderPayment;
use App\Models\LiveCourse;
use App\Models\Notification;
use App\Models\Quiz;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\Session;
use App\Models\Subscribe;
use App\Models\Webinar;
use App\Models\WebinarAssignment;
use App\Models\WebinarPartnerTeacher;
use App\User;
use App\Sessions\ZoomOAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Spatie\CalendarLinks\Link;

class EventsController extends Controller
{
    public $user;
    public $userBoughtWebinarsIds;

    public function index(Request $request)
    {
        $user = auth()->user();
        $this->user = $user;
        $this->userBoughtWebinarsIds = $user->getPurchasedCoursesIds();


        $dayTimestamp = $request->get('date', time());

        $dayEvents = $this->handleEventsByDate($dayTimestamp);
        $getUpcomingEvents = $this->getUpcomingEvents();
        $upcomingEvents = $getUpcomingEvents['upcomingEvents'];

        $eventsWithTimestamp = $this->getAllEventsReturnWithTimestamp();

        // Load recipients for the live-course creation modal (only for instructors/admins)
        $groups = collect();
        $bundles = collect();
        $students = collect();
        if ($user->isTeacher() || $user->isAdmin() || $user->isOrganization() || $user->isManager() || $user->isCeo()) {
            $groups = Group::where('creator_id', $user->id)->orderBy('name')->get();
            $bundles = Bundle::query()
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                })
                ->where('status', Bundle::$active)
                ->orderBy('id', 'desc')
                ->get();

            $studentIds = $this->getInstructorCourseStudentIds($user);
            if (!empty($studentIds)) {
                $students = User::query()
                    ->whereIn('id', $studentIds)
                    ->orderBy('full_name')
                    ->get(['id', 'full_name', 'email']);
            }
        }

        $data = [
            'pageTitle' => trans('update.events_calendar'),
            'dayEvents' => $dayEvents,
            'dayTimestamp' => $dayTimestamp,
            'upcomingEvents' => $upcomingEvents,
            'eventsWithTimestamp' => $eventsWithTimestamp,
            'groups' => $groups,
            'bundles' => $bundles,
            'students' => $students,
        ];

        return view('design_1.panel.events.index', $data);
    }

    public function getEventsByDay(Request $request)
    {
        $this->validate($request, [
            'timestamp' => 'required',
        ]);

        $user = auth()->user();
        $this->user = $user;
        $this->userBoughtWebinarsIds = $user->getPurchasedCoursesIds();

        $dayTimestamp = $request->get('timestamp');
        $dayEvents = $this->handleEventsByDate($dayTimestamp);

        $html = (string)view()->make("design_1.panel.events.day_events", [
            'dayEvents' => $dayEvents,
            'dayTimestamp' => $dayTimestamp,
        ]);

        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);
    }

    public function getAllEventsReturnWithTimestamp()
    {
        $result = [];
        $events = $this->getAllEvents();

        if (!empty($events) and count($events)) {
            foreach ($events as $eventName => $eventItems) {
                if (!empty($eventItems) and is_array($eventItems)) {
                    foreach ($eventItems as $eventTimestamp => $eventItem) {
                        if (!empty($eventItem) and is_array($eventItem)) {
                            $startOfDayTimestamp = startOfDayTimestamp($eventTimestamp);
                            $endOfDayTimestamp = endOfDayTimestamp($eventTimestamp);

                            if ($startOfDayTimestamp) {
                                $result[$eventTimestamp] = [
                                    'title' => $eventName,
                                    'start_day' => $startOfDayTimestamp,
                                    'end_day' => $endOfDayTimestamp,
                                    ...$eventItem
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function getUpcomingEvents($count = 5)
    {
        $result = [];
        $events = $this->getAllEvents();

        if (!empty($events) and count($events)) {
            foreach ($events as $eventName => $eventItems) {
                if (!empty($eventItems) and is_array($eventItems)) {
                    foreach ($eventItems as $eventTimestamp => $eventItem) {
                        if (!empty($eventItem) and is_array($eventItem)) {
                            $result[] = [
                                'title' => $eventName,
                                ...$eventItem
                            ];
                        }
                    }
                }
            }
        }

        $upcomingEvents = [];

        if (count($result) > 0) {
            uasort($result, function ($a, $b) {
                return $a['event_at'] <=> $b['event_at'];
            });

            $upcomingEvents = array_slice($result, 0, $count, true); // take 5 item
        }

        return [
            'upcomingEvents' => $upcomingEvents,
            'total' => (!empty($events['total'])) ? $events['total'] : 0,
        ];
    }

    private function handleEventsByDate($dateTimestamp)
    {
        $carbonDate = Carbon::createFromTimestamp($dateTimestamp);
        $carbonDate->setTimezone(getTimezone());
        $startAt = $carbonDate->startOfDay()->timestamp;
        $endAt = $carbonDate->endOfDay()->timestamp;

        return $this->getAllEvents($startAt, $endAt);
    }

    private function getAllEvents($startAt = null, $endAt = null)
    {
        $total = 0;
        $events = [];

        // Course Expiration
        $events['courses_expirations'] = $this->getCourseExpirationEvent($startAt, $endAt);
        $total += count($events['courses_expirations']);

        // Quiz Expiration
        $events['quiz_expirations'] = $this->getQuizExpirationEvent($startAt, $endAt);
        $total += count($events['quiz_expirations']);

        // Live Session
        $events['live_sessions'] = $this->getLiveSessionEvent($startAt, $endAt);
        $total += count($events['live_sessions']);

        // Assignment Expiration
        $events['assignment_expirations'] = $this->getAssignmentExpirationEvent($startAt, $endAt);
        $total += count($events['assignment_expirations']);

        // Bundle Expiration
        $events['bundle_expirations'] = $this->getBundleExpirationEvent($startAt, $endAt);
        $total += count($events['bundle_expirations']);

        // Subscription Expiration
        $events['subscription_expirations'] = $this->getSubscriptionExpirationEvent($startAt, $endAt);
        $total += count($events['subscription_expirations']);

        // Registration Package Expiration
        $events['registration_package_expirations'] = $this->getRegistrationPackageExpirationEvent($startAt, $endAt);
        $total += count($events['registration_package_expirations']);

        // Installment
        $events['installments'] = $this->getInstallmentExpirationEvent($startAt, $endAt);
        $total += count($events['installments']);

        // Meeting
        $events['meetings'] = $this->getMeetingExpirationEvent($startAt, $endAt);
        $total += count($events['meetings']);

        // Live Class Start
        $events['live_class_start'] = $this->getLiveClassStartEvent($startAt, $endAt);
        $total += count($events['live_class_start']);

        // Live Courses (created from calendar)
        $events['live_courses'] = $this->getLiveCourseEvent($startAt, $endAt);
        $total += count($events['live_courses']);

        $events['total'] = $total;
        return $events;
    }

    private function getCourseExpirationEvent($startAt = null, $endAt = null)
    {
        $courses = [];

        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $webinars = Webinar::query()->whereIn('id', $this->userBoughtWebinarsIds)
                ->whereNotNull('access_days')
                ->get();

            foreach ($webinars as $webinar) {
                $expiredItem = false;
                $sale = $webinar->getSaleItem($this->user, true);


                if (!empty($sale)) {
                    $expireAt = $webinar->getExpiredAccessDays($sale->created_at, $sale->gift_id);

                    if (!empty($startAt) and !empty($endAt)) {
                        if ($expireAt >= $startAt and $expireAt <= $endAt) {
                            $expiredItem = true;
                        }
                    } elseif ($expireAt > time()) {
                        $expiredItem = true;
                    }

                    if ($expiredItem) {
                        $courses[$expireAt] = [
                            'subtitle' => $webinar->title,
                            'add_to_calendar_url' => $webinar->addToCalendarLink(),
                            'event_at' => $expireAt,
                            'time' => null,
                        ];;
                    }
                }
            }
        }

        return $courses;
    }

    private function getBundleExpirationEvent($startAt = null, $endAt = null)
    {
        $bundlesHasExpiration = [];
        $userBoughtBundlesIds = $this->user->getPurchasedBundlesIds();

        if (count($userBoughtBundlesIds)) {
            $bundles = Bundle::query()->whereIn('id', $userBoughtBundlesIds)
                ->whereNotNull('access_days')
                ->get();

            foreach ($bundles as $bundle) {
                $expiredItem = false;
                $sale = $bundle->getSaleItem($this->user);

                if (!empty($sale)) {
                    $expireAt = $bundle->getExpiredAccessDays($sale->created_at, $sale->gift_id);

                    if (!empty($startAt) and !empty($endAt)) {
                        if ($expireAt >= $startAt and $expireAt <= $endAt) {
                            $expiredItem = true;
                        }
                    } elseif ($expireAt > time()) {
                        $expiredItem = true;
                    }

                    if ($expiredItem) {
                        $bundlesHasExpiration[$expireAt] = [
                            'subtitle' => $bundle->title,
                            'add_to_calendar_url' => '',
                            'event_at' => $expireAt,
                            'time' => null,
                        ];;
                    }
                }
            }
        }

        return $bundlesHasExpiration;
    }

    private function getQuizExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $quizzes = Quiz::whereIn('webinar_id', $this->userBoughtWebinarsIds)
                ->where('status', 'active')
                ->get();

            foreach ($quizzes as $quiz) {
                $expiredItem = false;
                $expireAt = $quiz->getExpireTimestamp($this->user);

                if (!empty($startAt) and !empty($endAt)) {
                    if ($expireAt >= $startAt and $expireAt <= $endAt) {
                        $expiredItem = true;
                    }
                } elseif ($expireAt > time()) {
                    $expiredItem = true;
                }

                if ($expiredItem) {
                    $title = $quiz->title;
                    if (!empty($quiz->webinar)) {
                        $title .= " - " . $quiz->webinar->title;
                    }

                    $hasExpiration[$expireAt] = [
                        'subtitle' => $title,
                        'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                        'event_at' => $expireAt,
                        'time' => null,
                    ];
                }
            }
        }

        return $hasExpiration;
    }

    private function getLiveSessionEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        // Sessions from courses the user purchased
        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $sessions = Session::whereIn('webinar_id', $this->userBoughtWebinarsIds)
                ->where('status', 'active')
                ->where('date', '>=', time())
                ->get();

            foreach ($sessions as $session) {
                $expiredItem = false;
                $sessionDate = $session->date;

                if (!empty($startAt) and !empty($endAt)) {
                    if ($sessionDate >= $startAt and $sessionDate <= $endAt) {
                        $expiredItem = true;
                    }
                } elseif ($sessionDate > time()) {
                    $expiredItem = true;
                }

                if ($expiredItem) {
                    $title = $session->title;
                    if (!empty($session->webinar)) {
                        $title .= " - " . $session->webinar->title;
                    }

                    $hasExpiration[$sessionDate] = [
                        'subtitle' => $title,
                        'add_to_calendar_url' => $this->addToCalendarLink($title, $sessionDate),
                        'event_at' => $sessionDate,
                        'time' => dateTimeFormat($sessionDate, 'H:i'),
                    ];
                }
            }
        }

        // Sessions from courses the user teaches (instructor role)
        if ($this->user->isTeacher() || $this->user->isOrganization() || $this->user->isAdmin() || $this->user->isManager() || $this->user->isCeo()) {
            $teacherWebinarIds = Webinar::where('creator_id', $this->user->id)
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();

            if (!empty($teacherWebinarIds)) {
                $teachingSessions = Session::whereIn('webinar_id', $teacherWebinarIds)
                    ->where('status', 'active')
                    ->where('date', '>=', time())
                    ->get();

                foreach ($teachingSessions as $session) {
                    $sessionDate = $session->date;

                    if (isset($hasExpiration[$sessionDate])) {
                        continue; // already added from purchased check
                    }

                    $include = false;

                    if (!empty($startAt) and !empty($endAt)) {
                        if ($sessionDate >= $startAt and $sessionDate <= $endAt) {
                            $include = true;
                        }
                    } elseif ($sessionDate > time()) {
                        $include = true;
                    }

                    if ($include) {
                        $title = $session->title;
                        if (!empty($session->webinar)) {
                            $title .= " - " . $session->webinar->title;
                        }

                        $hasExpiration[$sessionDate] = [
                            'subtitle' => $title,
                            'add_to_calendar_url' => $this->addToCalendarLink($title, $sessionDate),
                            'event_at' => $sessionDate,
                            'time' => dateTimeFormat($sessionDate, 'H:i'),
                        ];
                    }
                }
            }
        }

        return $hasExpiration;
    }

    private function getAssignmentExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $assignments = WebinarAssignment::whereIn('webinar_id', $this->userBoughtWebinarsIds)
                ->where('status', 'active')
                ->get();

            foreach ($assignments as $assignment) {
                $expiredItem = false;
                $expireAt = $assignment->getDeadlineTimestamp($this->user);

                if (!empty($expireAt)) {
                    if (!empty($startAt) and !empty($endAt)) {
                        if ($expireAt >= $startAt and $expireAt <= $endAt) {
                            $expiredItem = true;
                        }
                    } elseif ($expireAt > time()) {
                        $expiredItem = true;
                    }
                }

                if ($expiredItem) {
                    $title = $assignment->title;
                    if (!empty($assignment->webinar)) {
                        $title .= " - " . $assignment->webinar->title;
                    }

                    $hasExpiration[$expireAt] = [
                        'subtitle' => $title,
                        'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                        'event_at' => $expireAt,
                        'time' => null,
                    ];
                }
            }
        }

        return $hasExpiration;
    }

    private function getSubscriptionExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        $activeSubscribe = Subscribe::getActiveSubscribe($this->user->id);

        if (!empty($activeSubscribe) and !empty($activeSubscribe->expire_at) and $activeSubscribe->expire_at > time()) {
            $expiredItem = false;
            $expireAt = $activeSubscribe->expire_at;

            if (!empty($startAt) and !empty($endAt)) {
                if ($expireAt >= $startAt and $expireAt <= $endAt) {
                    $expiredItem = true;
                }
            } elseif ($expireAt > time()) {
                $expiredItem = true;
            }

            if ($expiredItem) {
                $title = $activeSubscribe->title;

                $hasExpiration[$expireAt] = [
                    'subtitle' => $title,
                    'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                    'event_at' => $expireAt,
                    'time' => null,
                ];
            }
        }

        return $hasExpiration;
    }

    private function getRegistrationPackageExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        $userPackage = new UserPackage($this->user);
        $activePackage = $userPackage->getPackage();

        if (!empty($activePackage) and !empty($activePackage->expire_at) and $activePackage->expire_at > time()) {
            $expiredItem = false;
            $expireAt = $activePackage->expire_at;

            if (!empty($startAt) and !empty($endAt)) {
                if ($expireAt >= $startAt and $expireAt <= $endAt) {
                    $expiredItem = true;
                }
            } elseif ($expireAt > time()) {
                $expiredItem = true;
            }

            if ($expiredItem) {
                $title = $activePackage->title;

                $hasExpiration[$expireAt] = [
                    'subtitle' => $title,
                    'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                    'event_at' => $expireAt,
                    'time' => null,
                ];
            }
        }

        return $hasExpiration;
    }

    private function getInstallmentExpirationEvent($startAt = null, $endAt = null)
    {
        $installmentOrders = InstallmentOrder::query()
            ->where('user_id', $this->user->id)
            ->where('status', '!=', 'paying')
            ->with([
                'selectedInstallment' => function ($query) {
                    $query->with([
                        'steps' => function ($query) {
                            $query->orderBy('deadline', 'asc');
                        }
                    ]);
                    $query->withCount([
                        'steps'
                    ]);
                }
            ])
            ->get();

        $hasExpiration = [];

        foreach ($installmentOrders as $installmentOrder) {

            foreach ($installmentOrder->selectedInstallment->steps as $step) {
                $expiredItem = false;

                $payment = InstallmentOrderPayment::query()
                    ->where('installment_order_id', $installmentOrder->id)
                    ->where('selected_installment_step_id', $step->id)
                    ->where('status', 'paid')
                    ->first();

                if (empty($payment)) {
                    $expireAt = ($step->deadline * 86400) + $installmentOrder->created_at;

                    if (!empty($startAt) and !empty($endAt)) {
                        if ($expireAt >= $startAt and $expireAt <= $endAt) {
                            $expiredItem = true;
                        }
                    } elseif ($expireAt > time()) {
                        $expiredItem = true;
                    }

                    if ($expiredItem) {
                        $title = $step->installmentStep->title;

                        if (!empty($step->selectedInstallment->order) and !empty($step->selectedInstallment->order->webinar)) {
                            $title .= " - " . $step->selectedInstallment->order->webinar->title;
                        }


                        $hasExpiration[$expireAt] = [
                            'subtitle' => $title,
                            'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                            'event_at' => $expireAt,
                            'time' => null,
                        ];
                    }
                }
            }

        }

        return $hasExpiration;
    }

    private function getMeetingExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        $reserveMeetings = ReserveMeeting::query()->where('user_id', $this->user->id)
            ->whereNotNull('reserved_at')
            ->whereHas('sale', function ($query) {
                //$query->whereNull('refund_at');
            })
            ->where('date', '>', time())
            ->get();

        foreach ($reserveMeetings as $reserveMeeting) {
            $expiredItem = false;
            $expireAt = $reserveMeeting->start_at;

            if (!empty($startAt) and !empty($endAt)) {
                if ($expireAt >= $startAt and $expireAt <= $endAt) {
                    $expiredItem = true;
                }
            } elseif ($expireAt > time()) {
                $expiredItem = true;
            }

            if ($expiredItem) {
                $title = $reserveMeeting->meeting->creator->full_name;

                $hasExpiration[$expireAt] = [
                    'subtitle' => $title,
                    'add_to_calendar_url' => $this->addToCalendarLink("Meeting - $title", $expireAt),
                    'event_at' => $expireAt,
                    'time' => dateTimeFormat($reserveMeeting->start_at, 'H:i') . ' - ' . dateTimeFormat($reserveMeeting->end_at, 'H:i'),
                ];
            }
        }

        return $hasExpiration;
    }

    private function getLiveClassStartEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $webinars = Webinar::query()->whereIn('id', $this->userBoughtWebinarsIds)
                ->whereNotNull('access_days')
                ->where('type', Webinar::$webinar)
                ->where('start_date', '>', time())
                ->get();

            foreach ($webinars as $webinar) {
                $expiredItem = false;
                $expireAt = $webinar->start_date;

                if (!empty($startAt) and !empty($endAt)) {
                    if ($expireAt >= $startAt and $expireAt <= $endAt) {
                        $expiredItem = true;
                    }
                } elseif ($expireAt > time()) {
                    $expiredItem = true;
                }

                if ($expiredItem) {
                    $title = $webinar->title;

                    $hasExpiration[$expireAt] = [
                        'subtitle' => $title,
                        'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                        'event_at' => $expireAt,
                        'time' => dateTimeFormat($webinar->start_date, 'H:i'),
                    ];
                }
            }
        }

        return $hasExpiration;
    }

    private function getLiveCourseEvent($startAt = null, $endAt = null)
    {
        $result = [];

        $query = LiveCourse::query()
            ->where('status', LiveCourse::$Active)
            ->where(function ($query) {
                // Creators can always see their own live courses.
                $query->where('creator_id', $this->user->id);

                // Students in selected group can see targeted live courses.
                $userGroupIds = GroupUser::query()->where('user_id', $this->user->id)->pluck('group_id')->toArray();
                if (!empty($userGroupIds)) {
                    $query->orWhereIn('group_id', $userGroupIds);
                }

                // Students who purchased targeted bundles can see those live courses.
                $userBundleIds = $this->user->getPurchasedBundlesIds();
                if (!empty($userBundleIds)) {
                    $query->orWhereIn('bundle_id', $userBundleIds);
                }

                // Students specifically targeted by instructor can see those live courses.
                $query->orWhereHas('students', function ($studentQuery) {
                    $studentQuery->where('users.id', $this->user->id);
                });
            });

        if (!empty($startAt) && !empty($endAt)) {
            $query->whereBetween('date', [$startAt, $endAt]);
        } else {
            $query->where('date', '>', time());
        }

        foreach ($query->get() as $liveCourse) {
            $result[$liveCourse->date] = [
                'subtitle' => $liveCourse->title,
                'add_to_calendar_url' => $this->addToCalendarLink($liveCourse->title, $liveCourse->date),
                'join_url' => $liveCourse->getJoinLink(),
                'event_at' => $liveCourse->date,
                'time' => dateTimeFormat($liveCourse->date, 'H:i'),
            ];
        }

        return $result;
    }

    public function storeLiveCourse(Request $request)
    {
        $user = auth()->user();

        if (!$user->isTeacher() && !$user->isAdmin() && !$user->isOrganization() && !$user->isManager() && !$user->isCeo()) {
            abort(403);
        }

        $data = $request->all();
        $isLocal = ($data['session_api'] ?? 'local') === 'local';

        $validator = Validator::make($data, [
            'title'       => 'required|max:255',
            'session_api' => 'required|in:local,zoom',
            'link'        => $isLocal ? 'required|url|max:500' : 'nullable',
            'start_date'  => 'required|date',
            'duration'    => 'required|integer|min:1',
            'student_ids'   => 'nullable|array',
            'student_ids.*' => 'integer|exists:users,id',
            'bundle_id'   => 'nullable|integer|exists:bundles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate Zoom credentials if needed
        if (!$isLocal && !(new ZoomOAuth())->hasCredentials($user)) {
            return response()->json([
                'status'         => 'zoom_token_invalid',
                'zoom_error_msg' => trans('webinars.your_zoom_settings_are_not_complete'),
            ], 422);
        }

        $bundleId = !empty($data['bundle_id']) ? (int) $data['bundle_id'] : null;
        $selectedStudentIds = collect($data['student_ids'] ?? [])->filter()->map(function ($id) {
            return (int) $id;
        })->unique()->values()->toArray();

        if (!empty($selectedStudentIds)) {
            $allowedStudentIds = $this->getInstructorCourseStudentIds($user);
            $unauthorizedStudentIds = array_diff($selectedStudentIds, $allowedStudentIds);

            if (!empty($unauthorizedStudentIds)) {
                return response()->json([
                    'errors' => [
                        'student_ids' => [trans('public.access_denied')]
                    ]
                ], 422);
            }
        }

        if (!empty($bundleId)) {
            $bundle = Bundle::query()->find($bundleId);

            if (empty($bundle) || !$bundle->canAccess($user)) {
                return response()->json([
                    'errors' => [
                        'bundle_id' => [trans('public.access_denied')]
                    ]
                ], 422);
            }
        }

        $dateTimestamp = convertTimeToUTCzone($data['start_date'], getTimezone())->getTimestamp();

        $liveCourse = LiveCourse::create([
            'creator_id'         => $user->id,
            'title'              => $data['title'],
            'description'        => $data['description'] ?? null,
            'language'           => $data['language'] ?? null,
            'session_api'        => $data['session_api'],
            'link'               => $isLocal ? ($data['link'] ?? null) : null,
            'api_secret'         => $data['api_secret'] ?? null,
            'date'               => $dateTimestamp,
            'duration'           => (int) $data['duration'],
            'extra_time_to_join' => !empty($data['extra_time_to_join']) ? (int) $data['extra_time_to_join'] : null,
            'group_id'           => null,
            'bundle_id'          => $bundleId,
            'status'             => !empty($data['status']) ? LiveCourse::$Active : LiveCourse::$Inactive,
            'created_at'         => time(),
        ]);

        if (!empty($selectedStudentIds)) {
            $liveCourse->students()->sync($selectedStudentIds);
        }

        if (!$isLocal) {
            $zoomOAuth = new ZoomOAuth();
            $meetingCreated = $zoomOAuth->makeMeeting($liveCourse, $user);

            if (!$meetingCreated) {
                $zoomErrorMessage = $zoomOAuth->getLastError() ?: trans('update.zoom_error_msg');
                $liveCourse->delete();

                return response()->json([
                    'status' => 'zoom_token_invalid',
                    'zoom_error_msg' => $zoomErrorMessage,
                ], 422);
            }
        }

        // Send in-app notifications to selected recipients
        $recipientIds = [];

        if (!empty($selectedStudentIds)) {
            $recipientIds = array_merge($recipientIds, $selectedStudentIds);
        }

        if (!empty($liveCourse->bundle_id)) {
            $recipientIds = array_merge(
                $recipientIds,
                Sale::query()
                    ->where('bundle_id', $liveCourse->bundle_id)
                    ->whereNull('refund_at')
                    ->pluck('buyer_id')
                    ->toArray()
            );
        }

        $recipientIds = array_values(array_unique($recipientIds));

        foreach ($recipientIds as $studentId) {
            Notification::create([
                'user_id'    => $studentId,
                'group_id'   => null,
                'title'      => trans('update.new_live_course_notification_title', ['title' => $liveCourse->title]),
                'message'    => trans('update.new_live_course_notification_body', [
                    'title'    => $liveCourse->title,
                    'date'     => dateTimeFormat($dateTimestamp, 'j M Y H:i'),
                    'teacher'  => $user->full_name,
                ]),
                'sender'     => 'system',
                'type'       => 'single',
                'created_at' => time(),
            ]);
        }

        return response()->json([
            'code'     => 200,
            'msg'      => trans('update.live_course_created_successfully'),
            'edit_url' => '/panel/events',
        ]);
    }

    private function getInstructorCourseStudentIds($user)
    {
        $directWebinarIds = Webinar::query()
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->pluck('id')
            ->toArray();

        $partnerWebinarIds = WebinarPartnerTeacher::query()
            ->where('teacher_id', $user->id)
            ->pluck('webinar_id')
            ->toArray();

        $webinarIds = array_values(array_unique(array_merge($directWebinarIds, $partnerWebinarIds)));

        if (empty($webinarIds)) {
            return [];
        }

        $studentIds = Sale::query()
            ->whereIn('webinar_id', $webinarIds)
            ->where('type', Sale::$webinar)
            ->whereNull('refund_at')
            ->pluck('buyer_id')
            ->toArray();

        return array_values(array_unique($studentIds));
    }

    private function addToCalendarLink($title, $timestamp)
    {

        $date = \DateTime::createFromFormat('j M Y H:i', dateTimeFormat($timestamp, 'j M Y H:i', false));

        $link = Link::create($title, $date, $date); //->description('Cookies & cocktails!')

        return $link->google();
    }

}


