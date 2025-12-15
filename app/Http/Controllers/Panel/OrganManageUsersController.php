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
use App\Models\UserZoomApi;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class OrganManageUsersController extends Controller
{
    use UserFormFieldsTrait;
      
    public function manageUsers(Request $request, $userType)
    {
        /** @var \App\User $user */
        $user = auth()->user();
        
        // Only admin (organization) and teachers can manage users
        if (!$user->isAdmin() && !$user->isTeacher()) {
            abort(403, 'Only admins and teachers can manage users');
        }
        
        // Teachers cannot manage other teachers
        if ($user->isTeacher() && in_array($userType, ['instructors', 'teachers'])) {
            abort(403, 'Teachers cannot manage other teachers');
        }

        // Check permissions only for teachers (admin has full access)
        if ($user->isTeacher()) {
            // Support both 'teachers' and 'instructors' for authorization
            $permissionType = ($userType === 'teachers') ? 'instructors' : $userType;
            $this->authorize("panel_organization_{$permissionType}_lists");
        }

        $valid_type = ['instructors', 'teachers', 'students'];    
        $organization = $user;

        if (($user->isAdmin() || $user->isTeacher()) && in_array($userType, $valid_type)) {
            $query = User::query();
            
            // Teachers only see users in their organization
            if ($user->isTeacher() && !empty($user->organ_id)) {
                $query->where('organ_id', $user->organ_id);
            }
            // Admin sees all users (no organ_id filter)
            else if ($user->isAdmin()) {
                // Admin sees all users in the system
                // No organ_id filter
            }

            if ($userType == 'instructors' || $userType == 'teachers') {
                $query->where('role_name', Role::$teacher);
            } else if ($userType == 'students') {
                $query->where('role_name', Role::$user);
            }

            $query->select('*', DB::raw('ST_AsText(location) as location'));

            $copyQuery = deepClone($query);
            $query = $this->handleFilters($request, $query);
            $getListData = $this->getListsData($request, $query, $userType);

            if ($request->ajax()) {
                return $getListData;
            }

            $activeCount = deepClone($copyQuery)->where('status', 'active')->count();
            $verifiedCount = deepClone($copyQuery)->where('verified', true)->count();
            $inActiveCount = deepClone($copyQuery)->where('status', 'inactive')->count();


            $data = [
                'pageTitle' => trans('public.' . $userType),
                'user_type' => $userType,
                'organization' => $organization,
                'activeCount' => $activeCount,
                'inActiveCount' => $inActiveCount,
                'verifiedCount' => $verifiedCount,
            ];
            $data = array_merge($data, $getListData);

            // Map 'teachers' view to 'instructors' view
            $viewType = ($userType === 'teachers') ? 'instructors' : $userType;
            return view("design_1.panel.manage.{$viewType}.index", $data);
        }

        abort(404);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $name = $request->get('name');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $type = $request->get('type');
        $sort = $request->get('sort');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($name)) {
            $query->where('full_name', 'like', "%$name%");
        }

        if (!empty($email)) {
            $query->where('email', $email);
        }

        if (!empty($phone)) {
            $query->where('mobile', $phone);
        }

        if (!empty($type)) {
            if (in_array($type, ['active', 'inactive'])) {
                $query->where('status', $type);
            } elseif ($type == 'verified') {
                $query->where('verified', true);
            }
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'create_date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'create_date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getListsData(Request $request, Builder $query, $userType)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $users = $query
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $users, $total, $count, $userType);
        }

        return [
            'users' => $users,
            'pagination' => $this->makePagination($request, $users, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $users, $total, $count, $userType)
    {
        $html = "";

        foreach ($users as $userRow) {
            $html .= (string)view()->make("design_1.panel.manage.{$userType}.table_items", ['user' => $userRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $users, $total, $count, true)
        ]);
    }

    public function createUser(Request $request, $userType)
    {
        /** @var \App\User $user */
        $user = auth()->user();
        
        // Only admin (organization) and teachers can manage users
        if (!$user->isAdmin() && !$user->isTeacher()) {
            abort(403, 'Only admins and teachers can manage users');
        }
        
        // Teachers cannot create other teachers
        if ($user->isTeacher() && in_array($userType, ['instructors', 'teachers'])) {
            abort(403, 'Teachers cannot manage other teachers');
        }

        // Check permissions only for teachers (admin has full access)
        if ($user->isTeacher()) {
            // Support both 'teachers' and 'instructors' for authorization
            $permissionType = ($userType === 'teachers') ? 'instructors' : $userType;
            $this->authorize("panel_organization_{$permissionType}_create");
        }

        $valid_type = ['instructors', 'teachers', 'students'];
        $organization = $user;

        if (($user->isAdmin() || $user->isTeacher()) && in_array($userType, $valid_type)) {

            $packageType = ($userType == 'instructors' || $userType == 'teachers') ? 'instructors_count' : 'students_count';
            $userPackage = new UserPackage();
            $userAccountLimited = $userPackage->checkPackageLimit($packageType);

            if ($userAccountLimited) {
                session()->put('registration_package_limited', $userAccountLimited);

                return redirect()->back();
            }


            $userLanguages = getGeneralSettings('user_languages');
            if (!empty($userLanguages) and is_array($userLanguages)) {
                $userLanguages = getLanguages($userLanguages);
            } else {
                $userLanguages = [];
            }

            $step = 'basic_information';

            $userController = (new UserController());
            $data = $userController->getUserEditPageData($request, $organization, $step);

            $data['pageTitle'] = trans('public.new') . ' ' . trans('quiz.' . $userType);
            $data['new_user'] = true;
            $data['user_type'] = $userType;
            $data['user'] = $organization;
            $data['organization_id'] = $organization->id;
            $data['userLanguages'] = $userLanguages;
            $data['currentStep'] = $step;

            return view('design_1.panel.settings.index', $data);
        }

        abort(404);
    }

    public function storeUser(Request $request, $userType)
    {
        /** @var \App\User $user */
        $user = auth()->user();
        
        // Only admin (organization) and teachers can manage users
        if (!$user->isAdmin() && !$user->isTeacher()) {
            abort(403, 'Only admins and teachers can manage users');
        }
        
        // Teachers cannot create other teachers
        if ($user->isTeacher() && in_array($userType, ['instructors', 'teachers'])) {
            abort(403, 'Teachers cannot manage other teachers');
        }

        // Check permissions only for teachers (admin has full access)
        if ($user->isTeacher()) {
            // Support both 'teachers' and 'instructors' for authorization
            $permissionType = ($userType === 'teachers') ? 'instructors' : $userType;
            $this->authorize("panel_organization_{$permissionType}_create");
        }

        $valid_type = ['instructors', 'teachers', 'students'];
        $organization = $user;

        if (($user->isAdmin() || $user->isTeacher()) && in_array($userType, $valid_type)) {
            $this->validate($request, [
                'email' => 'required|string|email|max:255|unique:users',
                'full_name' => 'required|string',
                'mobile' => 'required|numeric|unique:users',
                'password' => 'required|confirmed|min:6',
            ]);

            $data = $request->all();
            $role_name = ($userType == 'instructors' || $userType == 'teachers') ? Role::$teacher : Role::$user;
            $role_id = ($userType == 'instructors' || $userType == 'teachers') ? Role::getTeacherRoleId() : Role::getUserRoleId();

            $referralSettings = getReferralSettings();
            $usersAffiliateStatus = (!empty($referralSettings) and !empty($referralSettings['users_affiliate_status']));

            $user = User::create([
                'role_name' => $role_name,
                'role_id' => $role_id,
                'email' => $data['email'],
                'organ_id' => $organization->id,
                'password' => Hash::make($data['password']),
                'full_name' => $data['full_name'],
                'mobile' => $data['mobile'],
                'language' => $data['language'] ?? null,
                'timezone' => $data['timezone'] ?? null,
                'currency' => $data['currency'] ?? null,
                'affiliate' => $usersAffiliateStatus,
                'newsletter' => (!empty($data['join_newsletter']) and $data['join_newsletter'] == 'on'),
                'public_message' => (!empty($data['public_messages']) and $data['public_messages'] == 'on'),
                'created_at' => time()
            ]);


            $notifyOptions = [
                '[organization.name]' => $organization->full_name,
                '[u.name]' => $user->full_name,
                '[u.role]' => trans("update.role_{$user->role_name}"),
            ];
            sendNotification('new_organization_user', $notifyOptions, 1);


            return redirect('/panel/manage/' . $userType . '/' . $user->id . '/edit');
        }

        abort(404);
    }

    public function editUser(Request $request, $userType, $user_id, $step = "basic_information")
    {
        /** @var \App\User $user */
        $user = auth()->user();
        
        // Only admin (organization) and teachers can manage users
        if (!$user->isAdmin() && !$user->isTeacher()) {
            abort(403, 'Only admins and teachers can manage users');
        }
        
        // Teachers cannot edit other teachers
        if ($user->isTeacher() && in_array($userType, ['instructors', 'teachers'])) {
            abort(403, 'Teachers cannot manage other teachers');
        }

        // Check permissions only for teachers (admin has full access)
        if ($user->isTeacher()) {
            // Support both 'teachers' and 'instructors' for authorization
            $permissionType = ($userType === 'teachers') ? 'instructors' : $userType;
            $this->authorize("panel_organization_{$permissionType}_edit");
        }

        $valid_type = ['instructors', 'teachers', 'students'];

        if (($user->isAdmin() || $user->isTeacher()) && in_array($userType, $valid_type)) {
            $query = User::query()->select('*', DB::raw('ST_AsText(location) as location'))
                ->where('id', $user_id);
            
            // Teachers can only edit users in their organization
            if ($user->isTeacher()) {
                $query->where('organ_id', $user->id);
            }
            // Admin can edit any user
            
            $targetUser = $query->first();

            if (!empty($targetUser)) {
                $data = [
                    'pageTitle' => trans('edit'),
                    'user' => $targetUser,
                    'organization_id' => $user->id,
                    'edit_new_user' => true,
                    'user_type' => $userType,
                ];

                $userController = (new UserController());
                $data = array_merge($data, $userController->getUserEditPageData($request, $targetUser, $step));

                return view('design_1.panel.settings.index', $data);
            }
        }

        abort(404);
    }

    public function deleteUser($userType, $user_id)
    {
        /** @var \App\User $user */
        $user = auth()->user();
        
        // Only admin (organization) and teachers can manage users
        if (!$user->isAdmin() && !$user->isTeacher()) {
            abort(403, 'Only admins and teachers can manage users');
        }
        
        // Teachers cannot delete other teachers
        if ($user->isTeacher() && in_array($userType, ['instructors', 'teachers'])) {
            abort(403, 'Teachers cannot manage other teachers');
        }

        // Check permissions only for teachers (admin has full access)
        if ($user->isTeacher()) {
            // Support both 'teachers' and 'instructors' for authorization
            $permissionType = ($userType === 'teachers') ? 'instructors' : $userType;
            $this->authorize("panel_organization_{$permissionType}_delete");
        }

        $valid_type = ['instructors', 'teachers', 'students'];

        if (($user->isAdmin() || $user->isTeacher()) && in_array($userType, $valid_type)) {
            $query = User::where('id', $user_id);
            
            // Teachers can only delete users in their organization
            if ($user->isTeacher()) {
                $query->where('organ_id', $user->id);
            }
            // Admin can delete any user
            
            $targetUser = $query->first();

            if (!empty($targetUser)) {
                $targetUser->update([
                    'organ_id' => null
                ]);

                return response()->json([
                    'code' => 200
                ]);
            }
        }

        return response()->json([], 422);
    }

}


