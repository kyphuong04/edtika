<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\LearningPageAssignmentTrait;
use App\Http\Controllers\Web\traits\LearningPageForumTrait;
use App\Http\Controllers\Web\traits\LearningPageItemInfoTrait;
use App\Http\Controllers\Web\traits\LearningPageMixinsTrait;
use App\Http\Controllers\Web\traits\LearningPageNoticeboardsTrait;
use App\Http\Controllers\Web\traits\LearningPagePersonalNoteTrait;
use App\Models\Certificate;
use App\Models\CourseLearningLastView;
use App\Models\CourseNoticeboard;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LearningPageController extends Controller
{
    use LearningPageMixinsTrait, LearningPageAssignmentTrait, LearningPageItemInfoTrait,
        LearningPageNoticeboardsTrait, LearningPageForumTrait, LearningPagePersonalNoteTrait;
    
    public function trackTime(Request $request, $courseSlug)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'type' => 'required|in:file,session,text_lesson,quiz,assignment',
            'item_id' => 'required|integer',
            'time' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $course = Webinar::where('slug', $courseSlug)
            ->where('status', 'active')
            ->first();

        if (empty($course)) {
            return response()->json(['code' => 404, 'message' => 'Course not found'], 404);
        }

        $user = auth()->user();

        // Check if user has access to the course
        if (!$this->checkCourseAccess($course)) {
            return response()->json(['code' => 403, 'message' => 'Access denied'], 403);
        }

        // Store learning time (you can create a new model/table for this or use existing)
        // For now, just update the last view
        $this->storeCourseLearningLastView($course->id, $data['item_id'], $data['type']);

        return response()->json([
            'code' => 200,
            'message' => 'Time tracked successfully'
        ]);
    }

    public function index(Request $request, $slug, $justReturnData = false)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            $this->authorize("panel_webinars_learning_page");
        }

        $requestData = $request->all();

        $webinarController = new WebinarController();

        $data = $webinarController->course($request, $slug, true);

        $course = $data['course'];
        $user = $data['user'];

        /* Check Not Active */
        if ($course->status != "active" and (empty($user) or (!$user->isAdmin() and !$course->canAccess($user)))) {
            $data = [
                'pageTitle' => trans('update.access_denied'),
                'pageRobot' => getPageRobotNoIndex(),
            ];
            return $justReturnData ? false : view('design_1.web.courses.not_access.index', $data);
        }

        $installmentLimitation = $webinarController->installmentContentLimitation($user, $course->id, 'webinar_id');
        if ($installmentLimitation != "ok") {
            return $justReturnData ? false : $installmentLimitation;
        }


        if (!$data or (!$data['hasBought'] and empty($course->getInstallmentOrder()))) {
            if ($justReturnData) return false;
            abort(403);
        }


        if ($course->certificate) {
            $data["courseCertificate"] = Certificate::where('type', 'course')
                ->where('student_id', $user->id)
                ->where('webinar_id', $course->id)
                ->first();
        }

        $data["userIsCourseTeacher"] = ($course->creator_id == $user->id or $course->teacher_id == $user->id or $user->isAdmin());

        $data['userLearningLastView'] = CourseLearningLastView::query()
            ->where('user_id', $user->id)
            ->where('webinar_id', $course->id)
            ->first();

        $siteTitle = getGeneralSettings("site_name") ?? trans('update.platform');

        $data['breadcrumbs'] = [
            ['text' => $siteTitle, 'url' => '/'],
            ['text' => trans('update.course'), 'url' => $course->getUrl()],
            ['text' => trans('update.learning_page'), 'url' => null],
        ];

        $data['saleItem'] = $course->getSaleItem();

        // Handle Start Tracking Time
        $this->handleStartTrackingTime($course->id, $user->id);

        if ($justReturnData) {
            return $data;
        }

        return view('design_1.web.courses.learning_page.index', $data);
    }

    
}




