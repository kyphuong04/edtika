<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use App\Models\Sale;
use App\Models\Session;
use App\Models\Webinar;
use App\Models\WebinarPartnerTeacher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MyCoursesController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_webinars_lists");

        $user = auth()->user();

        if ($user->isUser()) {
            abort(404);
        }

        $query = Webinar::query()->where(function ($query) use ($user) {
            $query->where('creator_id', $user->id);
            $query->orWhere('teacher_id', $user->id);
        });

        return $this->getViewDataByQuery($request, $query, $user);
    }


    public function invitations(Request $request)
    {
        $this->authorize("panel_webinars_invited_lists");

        $user = auth()->user();

        if ($user->isUser()) {
            abort(404);
        }

        $invitedWebinarIds = WebinarPartnerTeacher::where('teacher_id', $user->id)->pluck('webinar_id')->toArray();
        $query = Webinar::query();
        $query->whereIn('id', $invitedWebinarIds);

        return $this->getViewDataByQuery($request, $query, $user, true);
    }

    private function getViewDataByQuery(Request $request, Builder $query, $user, $isInvitedCoursesPage = false)
    {
        $pageListData = $this->getMyCoursesListPageData($request, $query);

        if ($request->ajax()) {
            return $pageListData;
        }

        // My courses (teacher's own)
        $myCourses = Webinar::where(function ($q) use ($user) {
            $q->where('creator_id', $user->id)->orWhere('teacher_id', $user->id);
        })->with(['sessions', 'files', 'textLessons', 'purchases', 'sales', 'bundleWebinars'])->orderBy('updated_at', 'desc')->get();

        $myTotalLessons = 0;
        foreach ($myCourses as $c) {
            $myTotalLessons += $c->sessions->count() + $c->files->count() + $c->textLessons->count();
        }

        // All published courses (explore section)
        $exploreCourses = Webinar::where('status', Webinar::$active)
            ->with(['sessions', 'files', 'textLessons', 'purchases', 'sales'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $exploreTotalLessons = 0;
        foreach ($exploreCourses as $c) {
            $exploreTotalLessons += $c->sessions->count() + $c->files->count() + $c->textLessons->count();
        }

        $myBundles = \App\Models\Bundle::where(function ($q) use ($user) {
            $q->where('creator_id', $user->id)->orWhere('teacher_id', $user->id);
        })->orderBy('updated_at', 'desc')->get();

        $pageTitle = $isInvitedCoursesPage ? trans('panel.invited_classes') : trans('update.my_courses');

        $data = [
            'pageTitle'           => $pageTitle,
            'myCourses'           => $myCourses,
            'myTotalLessons'      => $myTotalLessons,
            'exploreCourses'      => $exploreCourses,
            'exploreTotalLessons' => $exploreTotalLessons,
            'myBundles'           => $myBundles,
            ...$pageListData,
        ];

        return view('design_1.panel.webinars.my_courses.index', $data);
    }

    private function handleMyCoursesListTopStats($user): array
    {
        $query = Webinar::query()->where(function ($query) use ($user) {
            $query->where('creator_id', $user->id);
            $query->orWhere('teacher_id', $user->id);
        });

        $totalCoursesCount = deepClone($query)->count();

        $totalCoursesHours = deepClone($query)->sum('duration');

        $totalLiveCoursesSalesAmount = 0;
        $totalCoursesSalesAmount = 0;

        $webinarSales = Sale::where('seller_id', $user->id)
            ->where('type', 'webinar')
            ->whereNotNull('webinar_id')
            ->whereNull('refund_at')
            ->with('webinar')
            ->get();

        foreach ($webinarSales as $webinarSale) {
            if (!empty($webinarSale->webinar) and $webinarSale->webinar->type == 'webinar') {
                $totalLiveCoursesSalesAmount += $webinarSale->amount;
            }

            $totalCoursesSalesAmount += $webinarSale->amount;
        }

        return [
            'totalCoursesCount' => $totalCoursesCount,
            'totalCoursesHours' => $totalCoursesHours,
            'totalLiveCoursesSalesAmount' => $totalLiveCoursesSalesAmount,
            'totalCoursesSalesAmount' => $totalCoursesSalesAmount,
        ];
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {

        return $query;
    }

    public function getMyCoursesListPageData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $courses = $query->with([
            'reviews' => function ($query) {
                $query->where('status', 'active');
            },
            'category',
            'teacher'
        ])
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($courses as $course) {
            $giftsIds = Gift::query()->where('webinar_id', $course->id)
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('date');
                    $query->orWhere('date', '<', time());
                })
                ->whereHas('sale')
                ->pluck('id')
                ->toArray();

            $sales = Sale::query()
                ->where(function ($query) use ($course, $giftsIds) {
                    $query->where('webinar_id', $course->id);
                    $query->orWhereIn('gift_id', $giftsIds);
                })
                ->whereNull('refund_at')
                ->get();

            $course->sales = $sales;
        }

        if ($request->ajax()) {
            return $this->handleAjaxResponse($request, $courses, $total, $count);
        }

        return [
            'courses' => $courses,
            'pagination' => $this->makePagination($request, $courses, $total, $count, true),
        ];
    }

    private function handleAjaxResponse(Request $request, $courses, $total, $count)
    {
        $html = "";

        foreach ($courses as $courseItem) {
            $html .= '<div class="col-12 col-lg-6 mb-32">';
            $html .= (string)view()->make("design_1.panel.webinars.my_courses.course_card.index", ['course' => $courseItem]);
            $html .= '</div>';
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $courses, $total, $count, true)
        ]);
    }

    private function getMyCoursesListUpcomingLiveSessions()
    {
        return Session::whereNotNull('webinar_id')
            ->where('date', '>=', time())
            ->orderBy('date', 'asc')
            ->where('status', Session::$Active)
            ->whereDoesntHave('agoraHistory', function ($query) {
                $query->whereNotNull('end_at');
            })
            ->get();
    }
}


