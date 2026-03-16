<?php

namespace App\Http\Controllers\Panel;

use App\Bitwise\UserLevelOfTraining;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Mixins\Geo\Geo;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\Category;
use App\Models\DeleteAccountRequest;
use App\Models\Newsletter;
use App\Models\Region;
use App\Models\ReserveMeeting;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\UserBank;
use App\Models\UserLoginHistory;
use App\Models\UserMeta;
use App\Models\UserOccupation;
use App\Models\UserSelectedBank;
use App\Models\UserSelectedBankSpecification;
use App\Models\CoursePersonalNote;
use App\Models\IeltsGradingRating;
use App\Models\IeltsTestAttempt;
use App\Models\Sale;
use App\Models\SupportConversation;
use App\Models\UserZoomApi;
use App\Models\Webinar;
use App\Models\WebinarReview;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use UserFormFieldsTrait;

    public function setting(Request $request, $step = "basic_information")
    {
        $this->authorize("panel_others_profile_setting");

        $user = auth()->user();

        if ($user->isTeacher()) {
            return view('design_1.panel.settings.teacher_profile', [
                'pageTitle'   => 'My Profile',
                'user'        => $user,
                'teacherData' => $this->getTeacherProfileData($user),
            ]);
        }

        if ($user->isUser() || $user->isStudent()) {
            // Load user metas onto $user so $user->gender etc. are populated
            $userMetas = $user->userMetas;
            if (!empty($userMetas)) {
                foreach ($userMetas as $meta) {
                    $user->{$meta->name} = $meta->value;
                }
            }

            $userLoginHistories = UserLoginHistory::query()
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $userBoughtWebinarsIds = $user->getPurchasedCoursesIds();

            $purchasedCourses = collect();
            $purchaseSales = collect();
            if (count($userBoughtWebinarsIds)) {
                $purchasedCourses = Webinar::query()
                    ->whereIn('id', $userBoughtWebinarsIds)
                    ->with('teacher')
                    ->latest()
                    ->take(6)
                    ->get();

                $purchaseSales = \App\Models\Sale::where('buyer_id', $user->id)
                    ->whereIn('webinar_id', $userBoughtWebinarsIds)
                    ->whereNotNull('webinar_id')
                    ->orderBy('created_at', 'asc')
                    ->get()
                    ->keyBy('webinar_id');
            }

            $suggestedCourses = Webinar::query()
                ->where('status', 'active')
                ->whereNotIn('id', $userBoughtWebinarsIds ?: [0])
                ->with('teacher')
                ->inRandomOrder()
                ->take(6)
                ->get();

            $continueLearningCourse = null;
            if (count($userBoughtWebinarsIds)) {
                $continueLearningCourse = Webinar::query()
                    ->whereIn('id', $userBoughtWebinarsIds)
                    ->with('teacher')
                    ->inRandomOrder()
                    ->get()
                    ->first(function ($course) {
                        return $course->getProgress(true) < 100;
                    });
            }

            $userLanguages = getGeneralSettings('user_languages');
            if (!empty($userLanguages) && is_array($userLanguages)) {
                $userLanguages = getLanguages($userLanguages);
            } else {
                $userLanguages = [];
            }

            $recentNotes = CoursePersonalNote::query()
                ->with('course')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('design_1.panel.settings.user_profile', [
                'pageTitle' => trans('public.my_profile'),
                'user' => $user,
                'userLoginHistories' => $userLoginHistories,
                'purchasedCourses' => $purchasedCourses,
                'purchaseSales' => $purchaseSales,
                'suggestedCourses' => $suggestedCourses,
                'continueLearningCourse' => $continueLearningCourse,
                'userLanguages' => $userLanguages,
                'recentNotes' => $recentNotes,
            ]);
        }

        $data = [
            'pageTitle' => trans('panel.settings'),
            'user' => $user,
        ];
        $data = array_merge($data, $this->getUserEditPageData($request, $user, $step));

        return view('design_1.panel.settings.index', $data);
    }

    public function getUserEditPageData(Request $request, $user, $step): array
    {
        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $userMetas = $user->userMetas;

        if (!empty($userMetas)) {
            foreach ($userMetas as $meta) {
                $user->{$meta->name} = $meta->value;
            }
        }

        $occupations = $user->occupations->pluck('category_id')->toArray();


        $userLanguages = getGeneralSettings('user_languages');
        if (!empty($userLanguages) and is_array($userLanguages)) {
            $userLanguages = getLanguages($userLanguages);
        } else {
            $userLanguages = [];
        }

        $countries = null;
        $provinces = null;
        $cities = null;
        $districts = null;
        $attachments = null;
        $userLoginHistories = null;
        $formFieldsHtml = null;

        if ($step == "extra_information") {
            $countries = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                ->where('type', Region::$country)
                ->get();

            $userType = "organization";
            if ($user->isTeacher()) {
                $userType = "teacher";
            } elseif ($user->isUser()) {
                $userType = "user";
            }

            $formFieldsHtml = $this->getFormFieldsByUserType($request, $userType, true, $user);

        } elseif ($step == "about") {
            $attachments = $user->profileAttachments;
        } elseif ($step == "login_history") {
            $userLoginHistories = UserLoginHistory::query()->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $userBanks = UserBank::query()
            ->with([
                'specifications'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'categories' => $categories,
            'educations' => $userMetas->where('name', 'education'),
            'experiences' => $userMetas->where('name', 'experience'),
            'occupations' => $occupations,
            'userLanguages' => $userLanguages,
            'currentStep' => $step,
            'countries' => $countries,
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
            'userBanks' => $userBanks,
            'formFieldsHtml' => $formFieldsHtml,
            'attachments' => $attachments,
            'userLoginHistories' => $userLoginHistories,
        ];
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $organization = null;
        if (!empty($data['organization_id']) and !empty($data['user_id'])) {
            $organization = auth()->user();
            $user = User::where('id', $data['user_id'])
                ->where('organ_id', $organization->id)
                ->first();
        } else {
            $user = auth()->user();
        }

        $step = $data['step'] ?? "basic_information";

        $rules = [];

        if ($step == "basic_information") {
            $registerMethod = getGeneralSettings('register_method') ?? 'mobile';

            $rules = [
                'first_name' => 'required|string',
                'email' => (($registerMethod == 'email') ? 'required' : 'nullable') . '|email|max:255|unique:users,email,' . $user->id,
                'mobile' => (($registerMethod == 'mobile') ? 'required' : 'nullable') . '|numeric|unique:users,mobile,' . $user->id,
            ];
        }

        $this->validate($request, $rules);

        if (!empty($user)) {

            if (!empty($data['password']) && $step !== 'change_password') {
                $this->validate($request, [
                    'password' => 'required|confirmed|min:6',
                ]);

                $user->update([
                    'password' => User::generatePassword($data['password'])
                ]);
            }

            $updateData = [];
            $updateUserMeta = [];

            if ($step == "basic_information") {
                $joinNewsletter = (!empty($data['join_newsletter']) and $data['join_newsletter'] == 'on');

                // Combine first_name + last_name into full_name (supports both old full_name and new split fields)
                $firstName = trim($data['first_name'] ?? '');
                $lastName  = trim($data['last_name'] ?? '');
                $fullName  = $firstName . ($lastName ? ' ' . $lastName : '');

                $updateData = [
                    'full_name' => $fullName ?: ($data['full_name'] ?? null),
                    'email' => $data['email'],
                    'mobile' => $data['mobile'],
                    'language' => $data['language'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                    'currency' => $data['currency'] ?? null,
                    'offline' => (!empty($data['offline']) and $data['offline'] == "on"),
                    'offline_message' => (!empty($data['offline_message'])) ? $data['offline_message'] : null,
                    'newsletter' => $joinNewsletter,
                    'public_message' => (!empty($data['public_message']) and $data['public_message'] == 'on'),
                    'enable_profile_statistics' => (!empty($data['enable_profile_statistics']) and $data['enable_profile_statistics'] == 'on'),
                    'bio' => $data['bio'] ?? null,
                ];

                // Handle avatar upload from profile modal
                if (!empty($request->file('avatar'))) {
                    $updateData['avatar'] = $this->handleUploadImagesAndFiles($request, $user, "avatar");
                }

                // Handle gender and birthday (stored as UserMeta)
                $updateUserMeta['gender']   = $data['gender'] ?? null;
                $updateUserMeta['birthday'] = !empty($data['birthday']) ? convertTimeToUTCzone($data['birthday'])->getTimestamp() : null;

                $this->handleNewsletter($data['email'], $user->id, $joinNewsletter);
            } elseif ($step == "extra_information") {
                $updateData = [
                    "meeting_type" => $data['meeting_type'] ?? null,
                    "level_of_training" => !empty($data['level_of_training']) ? (new UserLevelOfTraining())->getValue($data['level_of_training']) : null,
                    "country_id" => $data['country_id'] ?? null,
                    "province_id" => $data['province_id'] ?? null,
                    "city_id" => $data['city_id'] ?? null,
                    "district_id" => $data['district_id'] ?? null,
                    "location" => (!empty($data['latitude']) and !empty($data['longitude'])) ? DB::raw("POINT(" . $data['latitude'] . "," . $data['longitude'] . ")") : null,
                    "address" => $data['address'] ?? null,
                ];

                $updateUserMeta = [
                    "birthday" => !empty($data['birthday']) ? convertTimeToUTCzone($data['birthday'])->getTimestamp() : null,
                    "gender" => $data['gender'] ?? null,
                ];

                // Handle User Socials
                $updateUserMeta['socials'] = (!empty($data['socials']) and is_array($data['socials'])) ? json_encode($data['socials']) : null;

                // Store Additional Forms
                $this->handleUserExtraForm($request, $user);

            } elseif ($step == "financial") {

                // Update User Bank Account
                if (!empty($data['bank_id'])) {
                    $this->handleUserBankAccount($user, $data);
                }

                $updateData = [
                    'identity_scan' => $this->handleUploadImagesAndFiles($request, $user, "identity_scan"),
                    'certificate' => $this->handleUploadImagesAndFiles($request, $user, "certificate"),
                ];

            } elseif ($step == "images") {

                $updateData = [
                    'avatar' => $this->handleUploadImagesAndFiles($request, $user, "avatar"),
                    'profile_video' => $this->handleUploadImagesAndFiles($request, $user, "profile_video"),
                    'cover_img' => $this->handleUploadImagesAndFiles($request, $user, "cover_img"),
                    'profile_secondary_image' => $this->handleUploadImagesAndFiles($request, $user, "profile_secondary_image"),
                ];


                if (!empty($request->file("signature_img"))) {
                    $signatureImgPath = $this->handleUploadImagesAndFiles($request, $user, "signature_img");

                    $updateUserMeta = [
                        'signature' => $signatureImgPath
                    ];
                }

            } elseif ($step == "about") {
                $updateData = [
                    'about' => $data['about'] ?? null,
                    'bio' => $data['bio'] ?? null,
                ];

                if (!$user->isUser()) {
                    UserOccupation::where('user_id', $user->id)->delete();

                    if (!empty($data['occupations'])) {
                        foreach ($data['occupations'] as $category_id) {
                            UserOccupation::create([
                                'user_id' => $user->id,
                                'category_id' => $category_id
                            ]);
                        }
                    }
                }

            } elseif ($step == "zoom") {

                if (!empty($data['zoom_api_key']) and !empty($data['zoom_api_secret'])) {
                    UserZoomApi::updateOrCreate(
                        [
                            'user_id' => $user->id,
                        ],
                        [
                            'api_key' => $data['zoom_api_key'] ?? null,
                            'api_secret' => $data['zoom_api_secret'] ?? null,
                            'account_id' => $data['zoom_account_id'] ?? null,
                            'created_at' => time()
                        ]
                    );
                } else {
                    UserZoomApi::where('user_id', $user->id)->delete();
                }
            } elseif ($step == "change_password") {
                if (!empty($data['password'])) {
                    $this->validate($request, [
                        'current_password' => 'required',
                        'password'         => 'required|confirmed|min:6',
                    ]);

                    if (!empty($data['current_password']) && !Hash::check($data['current_password'], $user->password)) {
                        return redirect()->back()->withErrors(['current_password' => trans('validation.current_password') ?: 'Current password is incorrect.'])->withInput();
                    }

                    $user->update([
                        'password' => User::generatePassword($data['password'])
                    ]);
                }

                $toastData = [
                    'title'  => trans('public.request_success'),
                    'msg'    => trans('panel.user_setting_success'),
                    'status' => 'success'
                ];
                return redirect("/panel/setting")->with(['toast' => $toastData]);
            }

            if (!empty($updateData)) {
                $user->update($updateData);
            }

            if (!empty($updateUserMeta)) {
                foreach ($updateUserMeta as $metaName => $metaValue) {
                    UserMeta::query()->where('user_id', $user->id)->where('name', $metaName)->delete();

                    if (!empty($metaValue)) {
                        UserMeta::query()->create([
                            'user_id' => $user->id,
                            'name' => $metaName,
                            'value' => $metaValue
                        ]);
                    }
                }
            }

            if ($user->isUser() || $user->isStudent()) {
                $url = "/panel/setting";
            } else {
                $url = "/panel/setting/step/{$step}";
            }
            if (!empty($organization)) {
                $userType = $user->isTeacher() ? 'instructors' : 'students';
                $url = "/panel/manage/{$userType}/{$user->id}/edit";
            }

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('panel.user_setting_success'),
                'status' => 'success'
            ];
            return redirect($url)->with(['toast' => $toastData]);
        }
        abort(404);
    }

    private function handleUserBankAccount($user, $data)
    {
        UserSelectedBank::query()->where('user_id', $user->id)->delete();

        $userSelectedBank = UserSelectedBank::query()->create([
            'user_id' => $user->id,
            'user_bank_id' => $data['bank_id']
        ]);

        if (!empty($data['bank_specifications'])) {
            $specificationInsert = [];

            foreach ($data['bank_specifications'] as $specificationId => $specificationValue) {
                if (!empty($specificationValue)) {
                    $specificationInsert[] = [
                        'user_selected_bank_id' => $userSelectedBank->id,
                        'user_bank_specification_id' => $specificationId,
                        'value' => $specificationValue
                    ];
                }
            }

            UserSelectedBankSpecification::query()->insert($specificationInsert);
        }
    }

    private function handleUploadImagesAndFiles(Request $request, $user, $name)
    {
        $path = $user->{$name};

        if (!empty($request->file($name))) {
            if (!empty($path)) {
                $this->removeFile($path);
            }

            $path = $this->uploadFile($request->file($name), "setting", $name, $user->id);
        }

        return $path;
    }

    private function handleUserExtraForm(Request $request, $user)
    {
        $userType = "organization";
        if ($user->isTeacher()) {
            $userType = "teacher";
        } elseif ($user->isUser()) {
            $userType = "user";
        }

        $form = $this->getFormFieldsByType($userType);

        if (!empty($form)) {
            $errors = $this->checkFormRequiredFields($request, $form);

            if (count($errors)) {
                return redirect()->back()->withErrors($errors);
            }

            $this->storeFormFields($request->all(), $user);
        }

        return "ok";
    }

    private function handleNewsletter($email, $user_id, $joinNewsletter)
    {
        $check = Newsletter::where('email', $email)->first();

        if ($joinNewsletter) {
            if (empty($check)) {
                Newsletter::create([
                    'user_id' => $user_id,
                    'email' => $email,
                    'created_at' => time()
                ]);
            } else {
                $check->update([
                    'user_id' => $user_id,
                ]);
            }

            $newsletterReward = RewardAccounting::calculateScore(Reward::NEWSLETTERS);
            RewardAccounting::makeRewardAccounting($user_id, $newsletterReward, Reward::NEWSLETTERS, $user_id, true);
        } elseif (!empty($check)) {
            $reward = RewardAccounting::where('user_id', $user_id)
                ->where('item_id', $user_id)
                ->where('type', Reward::NEWSLETTERS)
                ->where('status', RewardAccounting::ADDICTION)
                ->first();

            if (!empty($reward)) {
                $reward->delete();
            }

            $check->delete();
        }
    }

    public function storeMetas(Request $request)
    {
        $data = $request->all();

        if (!empty($data['name']) and !empty($data['value'])) {

            if (!empty($data['user_id'])) {
                $organization = auth()->user();
                $user = User::where('id', $data['user_id'])
                    ->where('organ_id', $organization->id)
                    ->first();
            } else {
                $user = auth()->user();
            }

            UserMeta::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'value' => $data['value'],
            ]);

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
    }

    public function updateMeta(Request $request, $meta_id)
    {
        $data = $request->all();
        $user = auth()->user();

        if (!empty($data['user_id'])) {
            $checkUser = User::find($data['user_id']);

            if ((!empty($checkUser) and ($data['user_id'] == $user->id) or $checkUser->organ_id == $user->id)) {
                $meta = UserMeta::where('id', $meta_id)
                    ->where('user_id', $data['user_id'])
                    ->where('name', $data['name'])
                    ->first();

                if (!empty($meta)) {
                    $meta->update([
                        'value' => $data['value']
                    ]);

                    return response()->json([
                        'code' => 200
                    ], 200);
                }

                return response()->json([
                    'code' => 403
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function deleteMeta(Request $request, $meta_id)
    {
        $data = $request->all();
        $user = auth()->user();

        if (!empty($data['user_id'])) {
            $checkUser = User::find($data['user_id']);

            if (!empty($checkUser) and ($data['user_id'] == $user->id or $checkUser->organ_id == $user->id)) {
                $meta = UserMeta::where('id', $meta_id)
                    ->where('user_id', $data['user_id'])
                    ->first();

                $meta->delete();

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function offlineToggle(Request $request)
    {
        $user = auth()->user();

        $message = $request->get('message');
        $toggle = $request->get('toggle');
        $toggle = (!empty($toggle) and $toggle == 'true');

        $user->offline = $toggle;
        $user->offline_message = $message;

        $user->save();

        return response()->json([
            'code' => 200
        ], 200);
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        if (!empty($user)) {
            DeleteAccountRequest::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'created_at' => time()
            ]);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'text' => trans('update.delete_account_request_stored_msg'),
                'dont_reload' => true
            ]);
        }

        abort(403);
    }

    public function getUserInfo($id)
    {
        $user = User::query()->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings')
            ->where('id', $id)
            ->first();

        if (!empty($user)) {
            $user->avatar = $user->getAvatar(40);
            $user->profile_url = $user->getProfileUrl();

            return response()->json([
                'user' => $user
            ]);
        }

        return response()->json([], 422);
    }

    public function deleteUserMedia($type)
    {
        $user = auth()->user();
        $items = ['avatar', 'cover_img', 'profile_secondary_image', 'profile_video', 'signature_img'];

        if (in_array($type, $items)) {
            if ($type == 'signature_img') {
                $user->userMetas()->where('name', 'signature')->delete();
            } else {
                $user->update([
                    "{$type}" => null,
                ]);
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans("update.delete_account_{$type}_msg"),
            ]);
        }

        return response()->json([], 422);
    }

    /* =========================================================
     *  TEACHER PROFILE  —  public actions (manager only)
     * ========================================================= */

    public function updateTeacherKpi(Request $request, $id)
    {
        abort_unless(auth()->user()->isManager() || auth()->user()->isAdmin(), 403);

        $request->validate(['kpi_target' => 'required|integer|min:0|max:9999']);

        UserMeta::updateOrCreate(
            ['user_id' => $id, 'name' => 'teacher_kpi_target'],
            ['value'   => $request->kpi_target]
        );

        return redirect()->back()->with('success', 'KPI đã được cập nhật.');
    }

    public function updateTeacherLevel(Request $request, $id)
    {
        abort_unless(auth()->user()->isManager() || auth()->user()->isAdmin(), 403);

        $allowed = ['Junior', 'Mid', 'Senior', 'Expert', 'Master'];
        $request->validate(['teacher_level' => 'required|in:' . implode(',', $allowed)]);

        UserMeta::updateOrCreate(
            ['user_id' => $id, 'name' => 'teacher_level'],
            ['value'   => $request->teacher_level]
        );

        return redirect()->back()->with('success', 'Level đã được cập nhật.');
    }

    /* =========================================================
     *  TEACHER PROFILE  —  private data helpers
     * ========================================================= */

    private function getTeacherProfileData(\App\User $user): array
    {
        $now        = Carbon::now();
        $monthStart = $now->copy()->startOfMonth()->timestamp;
        $monthEnd   = $now->copy()->endOfMonth()->timestamp;

        // Teacher's webinar IDs
        $webinarIds = Webinar::where('teacher_id', $user->id)->pluck('id')->toArray();

        // ── Monthly stats ─────────────────────────────────────────────
        $monthlyWriting = IeltsTestAttempt::where('writing_graded_by', $user->id)
            ->whereBetween('writing_graded_at', [$monthStart, $monthEnd])
            ->count();

        $monthlySpeaking = IeltsTestAttempt::where('speaking_graded_by', $user->id)
            ->whereBetween('speaking_graded_at', [$monthStart, $monthEnd])
            ->count();

        $monthlyStudents = 0;
        if (!empty($webinarIds)) {
            $monthlyStudents = Sale::whereIn('webinar_id', $webinarIds)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->distinct('buyer_id')
                ->count('buyer_id');
        }

        // ── KPI ───────────────────────────────────────────────────────
        $kpiTarget  = (int)(UserMeta::where('user_id', $user->id)
            ->where('name', 'teacher_kpi_target')->value('value') ?? 0);
        $kpiCurrent = $monthlyWriting + $monthlySpeaking;

        // ── Teacher level ─────────────────────────────────────────────
        $teacherLevel = UserMeta::where('user_id', $user->id)
            ->where('name', 'teacher_level')->value('value') ?? 'Junior';

        // ── Rating ────────────────────────────────────────────────────
        $webinarReviewCount = 0;
        $webinarReviewSum   = 0.0;
        if (!empty($webinarIds)) {
            $rq = WebinarReview::whereIn('webinar_id', $webinarIds)->where('status', 'active');
            $webinarReviewCount = (int)$rq->count();
            $webinarReviewSum   = $webinarReviewCount > 0 ? (float)$rq->sum('rates') : 0.0;
        }

        $gradingRatingCount = (int)IeltsGradingRating::where('instructor_id', $user->id)->count();
        $gradingRatingSum   = $gradingRatingCount > 0
            ? (float)IeltsGradingRating::where('instructor_id', $user->id)->sum('rating')
            : 0.0;

        $totalRatings = $webinarReviewCount + $gradingRatingCount;
        $avgRating    = $totalRatings > 0
            ? min(round(($webinarReviewSum + $gradingRatingSum) / $totalRatings, 1), 5)
            : 0.0;

        // ── Team IDs for comparison ───────────────────────────────────
        $teamIds = \App\User::where('role_name', 'teacher')->pluck('id')->toArray();

        // ── Chart + metric data for all periods ───────────────────────
        $chartData  = [];
        $metricData = [];

        foreach (['week', 'month', 'year'] as $period) {
            $ranges = $this->buildPeriodRanges($period);

            $chartData[$period]  = [];
            $metricData[$period] = [];

            foreach (['response_time', 'grading_wait', 'feedback_count', 'improvement'] as $metric) {
                $teacherVals = [];
                $teamVals    = [];

                foreach ($ranges as $range) {
                    [$rs, $re] = [$range['start'], $range['end']];

                    switch ($metric) {
                        case 'response_time':
                            $teacherVals[] = $this->tpAvgResponseTime($user->id, $rs, $re);
                            $teamVals[]    = $this->tpAvgResponseTime(null, $rs, $re, $teamIds);
                            break;
                        case 'grading_wait':
                            $teacherVals[] = $this->tpAvgGradingWait($user->id, $rs, $re);
                            $teamVals[]    = $this->tpAvgGradingWait(null, $rs, $re, $teamIds);
                            break;
                        case 'feedback_count':
                            $teacherVals[] = $this->tpAvgFeedbackCount($user->id, $rs, $re);
                            $teamVals[]    = $this->tpAvgFeedbackCount(null, $rs, $re, $teamIds);
                            break;
                        case 'improvement':
                            $teacherVals[] = $this->tpAvgImprovement($user->id, $rs, $re);
                            $teamVals[]    = $this->tpAvgImprovement(null, $rs, $re, $teamIds);
                            break;
                    }
                }

                $chartData[$period][$metric] = [
                    'labels'  => array_column($ranges, 'label'),
                    'teacher' => $teacherVals,
                    'team'    => $teamVals,
                ];
            }

            // Scalar metric values: use the MOST RECENT (last) range in the period
            $last = end($ranges);
            [$ls, $le] = [$last['start'], $last['end']];

            $metricData[$period] = [
                'response_time'  => $this->tpAvgResponseTime($user->id, $ls, $le),
                'grading_wait'   => $this->tpAvgGradingWait($user->id, $ls, $le),
                'feedback_count' => $this->tpAvgFeedbackCount($user->id, $ls, $le),
                'improvement'    => $this->tpAvgImprovement($user->id, $ls, $le),
            ];
        }

        return [
            'kpiTarget'        => $kpiTarget,
            'kpiCurrent'       => $kpiCurrent,
            'monthlyStudents'  => $monthlyStudents,
            'monthlyReferrals' => 0,
            'monthlySpeaking'  => $monthlySpeaking,
            'monthlyWriting'   => $monthlyWriting,
            'teacherLevel'     => $teacherLevel,
            'avgRating'        => $avgRating,
            'reviewCount'      => $totalRatings,
            'chartData'        => $chartData,
            'metricData'       => $metricData,
        ];
    }

    /**
     * Returns array of { label, start (unix), end (unix) } for each data point in the given period.
     */
    private function buildPeriodRanges(string $period): array
    {
        $ranges = [];
        $now    = Carbon::now();

        if ($period === 'week') {
            for ($i = 4; $i >= 0; $i--) {
                $s = $now->copy()->subWeeks($i)->startOfWeek();
                $e = $now->copy()->subWeeks($i)->endOfWeek();
                $ranges[] = [
                    'label' => $s->format('j/n') . '–' . $e->format('j/n'),
                    'start' => $s->timestamp,
                    'end'   => $e->timestamp,
                ];
            }
        } elseif ($period === 'month') {
            for ($i = 5; $i >= 0; $i--) {
                $s = $now->copy()->subMonths($i)->startOfMonth();
                $e = $now->copy()->subMonths($i)->endOfMonth();
                $ranges[] = [
                    'label' => $s->translatedFormat('M Y'),
                    'start' => $s->timestamp,
                    'end'   => $e->timestamp,
                ];
            }
        } else {
            for ($i = 3; $i >= 0; $i--) {
                $s = $now->copy()->subYears($i)->startOfYear();
                $e = $now->copy()->subYears($i)->endOfYear();
                $ranges[] = [
                    'label' => (string)$s->year,
                    'start' => $s->timestamp,
                    'end'   => $e->timestamp,
                ];
            }
        }

        return $ranges;
    }

    /** Avg response time in minutes (teacher replies to support tickets). */
    private function tpAvgResponseTime(?int $teacherId, int $start, int $end, array $teamIds = []): float
    {
        $q = DB::table('support_conversations as sc')
            ->join('supports as s', 's.id', '=', 'sc.support_id')
            ->whereBetween('sc.created_at', [$start, $end])
            ->whereRaw('sc.created_at > s.created_at');

        if ($teacherId !== null) {
            $q->where('sc.sender_id', $teacherId);
        } elseif (!empty($teamIds)) {
            $q->whereIn('sc.sender_id', $teamIds);
        }

        $avgSec = $q->avg(DB::raw('sc.created_at - s.created_at'));
        return $avgSec ? round($avgSec / 60, 1) : 0.0;
    }

    /** Avg grading wait in hours (writing_graded_at − completed_at). */
    private function tpAvgGradingWait(?int $teacherId, int $start, int $end, array $teamIds = []): float
    {
        $q = IeltsTestAttempt::whereNotNull('completed_at')
            ->whereNotNull('writing_graded_at')
            ->whereRaw('writing_graded_at > completed_at')
            ->whereBetween('writing_graded_at', [$start, $end]);

        if ($teacherId !== null) {
            $q->where('writing_graded_by', $teacherId);
        } elseif (!empty($teamIds)) {
            $q->whereIn('writing_graded_by', $teamIds);
        }

        $avgSec = $q->avg(DB::raw('writing_graded_at - completed_at'));
        return $avgSec ? round($avgSec / 3600, 1) : 0.0;
    }

    /** Avg number of writing_criteria items per graded attempt. */
    private function tpAvgFeedbackCount(?int $teacherId, int $start, int $end, array $teamIds = []): float
    {
        $q = IeltsTestAttempt::whereNotNull('writing_criteria')
            ->whereBetween('writing_graded_at', [$start, $end]);

        if ($teacherId !== null) {
            $q->where('writing_graded_by', $teacherId);
        } elseif (!empty($teamIds)) {
            $q->whereIn('writing_graded_by', $teamIds);
        }

        $attempts = $q->pluck('writing_criteria');
        if ($attempts->isEmpty()) {
            return 0.0;
        }

        $total = $attempts->sum(fn($c) => is_array($c) ? count($c) : 0);
        return round($total / $attempts->count(), 1);
    }

    /** Avg writing band score for teacher's graded attempts (proxy for student improvement). */
    private function tpAvgImprovement(?int $teacherId, int $start, int $end, array $teamIds = []): float
    {
        $q = IeltsTestAttempt::whereNotNull('writing_band')
            ->whereBetween('writing_graded_at', [$start, $end]);

        if ($teacherId !== null) {
            $q->where('writing_graded_by', $teacherId);
        } elseif (!empty($teamIds)) {
            $q->whereIn('writing_graded_by', $teamIds);
        }

        $avg = $q->avg('writing_band');
        return $avg ? round($avg, 2) : 0.0;
    }

}


