<?php

namespace App\Http\Controllers\Panel;

use App\Enums\UploadSource;
use App\Exports\WebinarStudents;
use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\BundleFilterOption;
use App\Models\Category;
use App\Models\ContentDeleteRequest;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Tag;
use App\Models\Translation\BundleTranslation;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BundlesController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_bundles_lists");

        $user = auth()->user();

        if ($user->isUser()) {
            abort(404);
        }

        $query = Bundle::query()->where(function ($query) use ($user) {
            $query->where('bundles.teacher_id', $user->id);
            $query->orWhere('bundles.creator_id', $user->id);
        });

        $showHidden = $request->get('hidden') == 1;

        if ($showHidden) {
            $query->whereNotNull('hidden_at');
        } else {
            $query->whereNull('hidden_at');
        }

        $copyQuery = deepClone($query);
        //$query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }

        $bundlesCount = deepClone($copyQuery)->count();

        $bundlesHours = deepClone($copyQuery)->join('bundle_webinars', 'bundle_webinars.bundle_id', 'bundles.id')
            ->join('webinars', 'webinars.id', 'bundle_webinars.webinar_id')
            ->select('bundles.*', DB::raw('sum(webinars.duration) as duration'))
            ->sum('duration');

        $bundleSales = Sale::query()->where('seller_id', $user->id)
            ->where('type', 'bundle')
            ->whereNotNull('bundle_id')
            ->whereNull('refund_at')
            ->get();

        $data = [
            'pageTitle' => trans('update.my_bundles'),
            'bundlesCount' => $bundlesCount,
            'bundleSalesAmount' => $bundleSales->sum('amount'),
            'bundleSalesCount' => $bundleSales->count(),
            'bundlesHours' => $bundlesHours,
            'showHidden' => $showHidden,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.bundles.my_bundles.index', $data);
    }

    private function getListsData(Request $request, Builder $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $bundles = $query
            ->with([
                /*'reviews' => function ($query) {
                    $query->where('status', 'active');
                },*/
                'bundleWebinars',
                'category',
                'teacher',
                'sales' => function ($query) {
                    $query->where('type', 'bundle')
                        ->whereNull('refund_at');
                }
            ])
            ->orderBy('slug', 'asc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $bundles, $total, $count);
        }

        return [
            'bundles' => $bundles,
            'pagination' => $this->makePagination($request, $bundles, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $bundles, $total, $count)
    {
        $html = "";

        foreach ($bundles as $bundleItem) {
            $html .= '<div class="col-12 col-md-6 col-lg-4 mt-20">';
            $html .= (string)view()->make("design_1.panel.bundles.my_bundles.grid_card", ['bundle' => $bundleItem]);
            $html .= '</div>';
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $bundles, $total, $count, true)
        ]);
    }

    public function create()
    {
        $this->authorize("panel_bundles_create");

        $user = auth()->user();

        if (!$user->isTeacher() and !$user->isAdmin()) {
            abort(404);
        }

        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();

        $teachers = null;
        $isOrganization = $user->isAdmin();

        if ($isOrganization) {
            $teachers = User::where('role_name', Role::$teacher)
                ->where('organ_id', $user->id)->get();
        }


        $data = [
            'pageTitle' => trans('update.new_bundle'),
            'teachers' => $teachers,
            'categories' => $categories,
            'isOrganization' => $isOrganization,
            'currentStep' => 1,
            'stepCount' => 6,
            'userLanguages' => getUserLanguagesLists(),
        ];

        return view('design_1.panel.bundles.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("panel_bundles_create");

        $user = auth()->user();

        if (!$user->isTeacher() and !$user->isAdmin()) {
            abort(404);
        }

        // $rules = [
        //     'title' => 'required|max:255',
        //     'thumbnail' => 'required',
        //     'image_cover' => 'required',
        //     'description' => 'required',
        // ];
        $rules = [
            'title' => 'required|max:255',
            'description' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $bundle = Bundle::create([
            'teacher_id' => $user->isTeacher() ? $user->id : (!empty($data['teacher_id']) ? $data['teacher_id'] : $user->id),
            'creator_id' => $user->id,
            'slug' => Bundle::makeSlug($data['title']),
            'status' => ((!empty($data['draft']) and $data['draft'] == 1) or (!empty($data['get_next']) and $data['get_next'] == 1)) ? Bundle::$isDraft : Bundle::$pending,
            'created_at' => time(),
        ]);

        if ($bundle) {
            // Handle Image and Video
            $this->storeWebinarMedia($request, $bundle);


            BundleTranslation::updateOrCreate([
                'bundle_id' => $bundle->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'summary' => $data['summary'],
                'seo_description' => $data['seo_description'],
            ]);
        }

        $notifyOptions = [
            '[u.name]' => $user->full_name,
            '[item_title]' => $bundle->title,
            '[content_type]' => trans('update.bundle'),
            '[link]' => getAdminPanelUrl("/bundles/{$bundle->id}/edit"),
        ];
        sendNotification("bundle_submission", $notifyOptions, $user->id);
        sendNotification("bundle_submission_for_admin", $notifyOptions, 1);
        sendNotification("new_item_created", $notifyOptions, 1);

        $url = '/panel/bundles';
        if ($data['get_next'] == 1) {
            $url = '/panel/bundles/' . $bundle->id . '/step/2';
        }

        return redirect($url);
    }

    public function edit(Request $request, $id, $step = 1)
    {
        $this->authorize("panel_bundles_create");

        $user = auth()->user();
        $isOrganization = $user->isAdmin();

        if (!$user->isTeacher() and !$user->isAdmin()) {
            abort(404);
        }

        $stepCount = 6;

        if ($step > $stepCount) {
            return redirect("/panel/bundles/{$id}/step/{$stepCount}");
        }

        $locale = $request->get('locale', app()->getLocale());

        $data = [
            'pageTitle' => trans('update.new_bundle_page_title_step', ['step' => $step]),
            'currentStep' => $step,
            'isOrganization' => $isOrganization,
            'userLanguages' => getUserLanguagesLists(),
            'locale' => mb_strtolower($locale),
            'defaultLocale' => getDefaultLocale(),
            'stepCount' => $stepCount
        ];

        $query = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            });

        if ($step == '1') {
            $data['teachers'] = $user->getOrganizationTeachers()->get();
        } elseif ($step == 2) {
            $query->with([
                'category' => function ($query) {
                    $query->with([
                        'filters' => function ($query) {
                            $query->with('options');
                        }
                    ]);
                },
                'filterOptions',
                'tags',
            ]);

            $categories = Category::where('parent_id', null)
                ->with('subCategories')
                ->get();

            $data['categories'] = $categories;
        } elseif ($step == 3) {
            $query->with([
                'tickets' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ]);
        } elseif ($step == 4) {
            $query->with([
                'bundleWebinars' => function ($query) {
                    $query->with([
                        'webinar'
                    ]);
                    $query->orderBy('order', 'asc');
                }
            ]);
        } elseif ($step == 5) {
            $query->with([
                'faqs' => function ($query) {
                    $query->orderBy('order', 'asc');
                }
            ]);
        }

        $bundle = $query->first();

        if (empty($bundle)) {
            abort(404);
        }

        $data['bundle'] = $bundle;

        $data['pageTitle'] = trans('public.edit') . ' ' . $bundle->title;

        $definedLanguage = [];
        if ($bundle->translations) {
            $definedLanguage = $bundle->translations->pluck('locale')->toArray();
        }

        $data['definedLanguage'] = $definedLanguage;

        if ($step == 2) {
            $data['bundleTags'] = $bundle->tags->pluck('title')->toArray();

            $bundleCategoryFilters = !empty($bundle->category) ? $bundle->category->filters : [];

            if (empty($bundle->category) and !empty($request->old('category_id'))) {
                $category = Category::where('id', $request->old('category_id'))->first();

                if (!empty($category)) {
                    $bundleCategoryFilters = $category->filters;
                }
            }

            $data['bundleCategoryFilters'] = $bundleCategoryFilters;
        } elseif ($step == 4) {
            $data['webinars'] = Webinar::select('id', 'creator_id', 'teacher_id')
                ->where('status', Webinar::$active)
                ->where('private', false)
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                })
                ->get();
        }

        return view('design_1.panel.bundles.create.index', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize("panel_bundles_create");

        $user = auth()->user();

        if (!$user->isTeacher() and !$user->isAdmin()) {
            abort(404);
        }

        $rules = [];
        $data = $request->all();
        $currentStep = $data['current_step'];
        $getStep = $data['get_step'];
        $getNextStep = (!empty($data['get_next']) and $data['get_next'] == 1);
        $isDraft = (!empty($data['draft']) and $data['draft'] == 1);

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (empty($bundle)) {
            abort(404);
        }

        if ($currentStep == 1) {
            $rules = [
                'title' => 'required|max:255',
                'description' => 'required',
            ];
        }

        if ($currentStep == 2) {
            $rules = [
                'category_id' => 'required',
            ];
        }

        if ($currentStep == 3) {
            $rules = [
                'price_currency' => 'required|in:USD,VND',
            ];
        }

        // Sửa 1: bỏ điều kiện thừa/sai, chỉ bắt buộc "rules" đúng lúc submit ở step 6
        $bundleRulesRequired = false;
        if ($currentStep == 6 and !$getNextStep and !$isDraft) {
            $bundleRulesRequired = empty($data['rules']);
        }

        $this->validate($request, $rules);

        // Sửa 2: KHÔNG hạ status nếu bundle đang active, trừ khi user chủ động chọn "Lưu nháp"
        if ($bundle->status === Bundle::$active and !$isDraft) {
            // Giữ nguyên trạng thái active — không set lại $data['status']
        } else {
            $data['status'] = ($isDraft or $bundleRulesRequired) ? Bundle::$isDraft : Bundle::$pending;
        }

        $data['updated_at'] = time();

        if ($currentStep == 1) {
            BundleTranslation::updateOrCreate([
                'bundle_id' => $bundle->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'summary' => $data['summary'],
                'seo_description' => $data['seo_description'],
            ]);

            // Handle Image and Video
            $this->storeWebinarMedia($request, $bundle);
        }

        if ($currentStep == 2) {
            $data['certificate'] = !empty($data['certificate']) ? true : false;


            BundleFilterOption::where('bundle_id', $bundle->id)->delete();
            Tag::where('bundle_id', $bundle->id)->delete();

            $filters = $request->get('filters', null);
            if (!empty($filters) and is_array($filters)) {
                foreach ($filters as $filter) {
                    BundleFilterOption::create([
                        'bundle_id' => $bundle->id,
                        'filter_option_id' => $filter
                    ]);
                }
            }

            if (!empty($request->get('tags'))) {
                $tags = explode(',', $request->get('tags'));

                foreach ($tags as $tag) {
                    Tag::create([
                        'bundle_id' => $bundle->id,
                        'title' => $tag,
                    ]);
                }
            }
        }

        if ($currentStep == 3) {
            $data['subscribe'] = !empty($data['subscribe']) ? true : false;

            $priceCurrency = strtoupper($data['price_currency'] ?? 'USD');
            $priceCurrency = in_array($priceCurrency, Bundle::PRICE_CURRENCIES) ? $priceCurrency : 'USD';

            $data['price_currency'] = $priceCurrency;
            $data['price'] = !empty($data['price']) ? $data['price'] : null;
        }

        unset($data['_token'],
            $data['current_step'],
            $data['draft'],
            $data['get_next'],
            $data['partners'],
            $data['tags'],
            $data['filters'],
            $data['ajax'],
            $data['title'],
            $data['description'],
            $data['seo_description'],
            $data['summary'],
            $data['thumbnail'],
            $data['image_cover'],
            $data['video_demo_source'],
            $data['video_demo'],
            $data['demo_video_path'],
        );

        if (empty($data['teacher_id']) and $user->isAdmin() and $bundle->creator_id == $user->id) {
            $data['teacher_id'] = $user->id;
        }

        $bundle->update($data);

        $url = '/panel/bundles';
        if ($getNextStep) {
            $nextStep = (!empty($getStep) and $getStep > 0) ? $getStep : $currentStep + 1;

            $url = '/panel/bundles/' . $bundle->id . '/step/' . (($nextStep <= 6) ? $nextStep : 6);
        }

        if ($bundleRulesRequired) {
            $url = '/panel/bundles/' . $bundle->id . '/step/6';

            return redirect($url)->withErrors(['rules' => trans('validation.required', ['attribute' => 'rules'])]);
        }

        if (!$getNextStep and !$isDraft and !$bundleRulesRequired) {
            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[item_title]' => $bundle->title,
                '[content_type]' => trans('update.bundle'),
                '[link]' => getAdminPanelUrl("/bundles/{$bundle->id}/edit"),
            ];
            sendNotification("content_review_request", $notifyOptions, 1);
        }

        return redirect($url);
    }

    // public function destroy(Request $request, $id)
    // {
    //     $this->authorize("panel_bundles_delete");

    //     $user = auth()->user();

    //     if (!$user->isTeacher() and !$user->isAdmin()) {
    //         abort(404);
    //     }

    //     $bundle = Bundle::where('id', $id)
    //         ->where('creator_id', $user->id)
    //         ->first();

    //     if (!$bundle) {
    //         abort(404);
    //     }

    //     // Bundle chưa từng active (đang nháp hoặc chờ duyệt) -> cho phép xóa trực tiếp
    //     $isNotPublishedYet = in_array($bundle->status, [Bundle::$isDraft, Bundle::$pending]);

    //     if (!$isNotPublishedYet && !canDeleteContentDirectly()) {
    //         if ($request->ajax()) {
    //             return response()->json([], 422);
    //         } else {
    //             $toastData = [
    //                 'title' => trans('public.request_failed'),
    //                 'msg' => trans('update.it_is_not_possible_to_delete_the_content_directly'),
    //                 'status' => 'error'
    //             ];
    //             return redirect()->back()->with(['toast' => $toastData]);
    //         }
    //     }

    //     $bundle->delete();

    //     return response()->json([
    //         'code' => 200,
    //         'redirect_to' => $request->get('redirect_to')
    //     ], 200);
    // }

    public function destroy(Request $request, $id)
    {
        $this->authorize("panel_bundles_delete");

        $user = auth()->user();

        if (!$user->isTeacher() and !$user->isAdmin()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!$bundle) {
            abort(404);
        }

        // Bundle chưa từng publish (đang nháp hoặc chờ duyệt) -> cho phép xóa thẳng
        $isNotPublishedYet = in_array($bundle->status, [Bundle::$isDraft, Bundle::$pending]);

        if (!$isNotPublishedYet && !canDeleteContentDirectly()) {
            if ($request->ajax()) {
                return response()->json([], 422);
            } else {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.it_is_not_possible_to_delete_the_content_directly'),
                    'status' => 'error'
                ];
                return redirect()->back()->with(['toast' => $toastData]);
            }
        }

        $bundle->delete();

        return response()->json([
            'code' => 200,
            'redirect_to' => $request->get('redirect_to')
        ], 200);
    }

    protected function storeWebinarMedia(Request $request, $bundle)
    {
        $thumbnail = $bundle->thumbnail ?? null;
        $imageCover = $bundle->image_cover ?? null;
        $videoDemoSource = $bundle->video_demo_source ?? null;
        $videoDemo = $bundle->video_demo ?? null;


        if (!empty($request->file('thumbnail'))) {
            $thumbnail = $this->uploadFile($request->file('thumbnail'), "bundles/{$bundle->id}", 'thumbnail', $bundle->creator_id);
        }

        if (!empty($request->file('image_cover'))) {
            $imageCover = $this->uploadFile($request->file('image_cover'), "bundles/{$bundle->id}", 'image_cover', $bundle->creator_id);
        }


        if (in_array($request->get('video_demo_source'), UploadSource::urlPathItems) and !empty($request->get('demo_video_path'))) {
            $videoDemoSource = $request->get('video_demo_source');
            $videoDemo = $request->get('demo_video_path');
        } elseif ($request->get('video_demo_source') == UploadSource::UPLOAD and !empty($request->file('demo_video_local'))) {
            $videoDemoSource = UploadSource::UPLOAD;
            $videoDemo = $this->uploadFile($request->file('demo_video_local'), "bundles/{$bundle->id}", 'video', $bundle->creator_id);
        } elseif ($request->get('video_demo_source') == UploadSource::S3 and !empty($request->file('demo_video_local'))) {
            $videoDemoSource = UploadSource::S3;
            $videoDemo = $this->uploadFile($request->file('demo_video_local'), "bundles/{$bundle->id}", 'video', $bundle->creator_id, 'minio');
        } elseif ($request->get('video_demo_source') == UploadSource::SECURE_HOST and !empty($request->file('demo_video_local'))) {
            $videoDemoSource = UploadSource::SECURE_HOST;
            $videoDemo = $this->uploadFile($request->file('demo_video_local'), "bundles/{$bundle->id}", "bundle_{$bundle->id}_video_demo", $bundle->creator_id, 'bunny');
        }

        $bundle->update([
            'thumbnail' => $thumbnail,
            'image_cover' => $imageCover,
            'video_demo_source' => $videoDemoSource,
            'video_demo' => $videoDemo,
        ]);

        return $bundle;
    }

    public function getContentItemByLocale(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'locale' => 'required',
            'relation' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($bundle)) {

            $itemId = $data['item_id'];
            $locale = $data['locale'];
            $relation = $data['relation'];

            if (!empty($bundle->$relation)) {
                $item = $bundle->$relation->where('id', $itemId)->first();

                if (!empty($item)) {
                    foreach ($item->translatedAttributes as $attribute) {
                        try {
                            $item->$attribute = $item->translate(mb_strtolower($locale))->$attribute;
                        } catch (\Exception $e) {
                            $item->$attribute = null;
                        }
                    }

                    return response()->json([
                        'item' => $item
                    ], 200);
                }
            }
        }

        abort(403);
    }

    public function exportStudentsList($id)
    {
        $this->authorize("panel_bundles_export_students_list");

        $user = auth()->user();

        if (!$user->isTeacher() and !$user->isAdmin()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($bundle)) {
            $sales = Sale::where('type', 'bundle')
                ->where('bundle_id', $bundle->id)
                ->whereNull('refund_at')
                ->whereHas('buyer')
                ->with([
                    'buyer' => function ($query) {
                        $query->select('id', 'full_name', 'email', 'mobile');
                    }
                ])->get();

            if (!empty($sales) and !$sales->isEmpty()) {
                $export = new WebinarStudents($sales);
                return Excel::download($export, trans('panel.users') . '.xlsx');
            }

            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('webinars.export_list_error_not_student'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function courses(Request $request, $id)
    {
        $this->authorize("panel_bundles_courses");

        $user = auth()->user();

        if (!$user->isTeacher() and !$user->isAdmin()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->first();

        if (!empty($bundle)) {
            $bundleWebinarsIds = $bundle->bundleWebinars()->pluck('webinar_id')->toArray();
            $query = Webinar::query()->whereIn('id', $bundleWebinarsIds);

            $myCoursesController = (new MyCoursesController());
            $pageListData = $myCoursesController->getMyCoursesListPageData($request, $query);

            if ($request->ajax()) {
                return $pageListData;
            }

            $data = [
                'pageTitle' => "“{$bundle->title}” ".  trans('product.courses'),
                'bundle' => $bundle,
                ...$pageListData,
            ];

            return view('design_1.panel.bundles.courses.index', $data);
        }

        abort(404);
    }

    public function modules(Request $request, $id)
    {
        $this->authorize("panel_bundles_lists");

        $user = auth()->user();

        if ($user->isUser()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->with(['bundleWebinars.webinar' => function ($q) {
                $q->with(['sessions', 'files', 'textLessons']);
            }])
            ->first();

        if (empty($bundle)) {
            abort(404);
        }

        $totalLessons = 0;
        foreach ($bundle->bundleWebinars as $bw) {
            $webinar = $bw->webinar;
            if ($webinar) {
                $totalLessons += $webinar->sessions->count()
                    + $webinar->files->count()
                    + $webinar->textLessons->count();
            }
        }

        return view('design_1.panel.bundles.modules.index', [
            'pageTitle' => $bundle->title,
            'bundle'    => $bundle,
            'totalLessons' => $totalLessons,
        ]);
    }

    public function moduleCreate(Request $request, $bundleId)
    {
        $this->authorize("panel_webinars_create");

        $user = auth()->user();

        if ($user->isUser()) {
            abort(404);
        }

        $bundle = \App\Models\Bundle::where('id', $bundleId)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->firstOrFail();

        $placeholderTitle = trans('public.new_chapter');

        $webinar = \App\Models\Webinar::create([
            'teacher_id'  => $user->id,
            'creator_id'  => $user->id,
            'slug'        => \App\Models\Webinar::makeSlug($placeholderTitle . ' ' . time()),
            'type'        => 'course',
            'private'     => false,
            'status'      => \App\Models\Webinar::$isDraft,
            'category_id' => $bundle->category_id,
            'created_at'  => time(),
        ]);

        \App\Models\Translation\WebinarTranslation::create([
            'webinar_id' => $webinar->id,
            'locale'     => mb_strtolower(app()->getLocale()),
            'title'      => $placeholderTitle,
        ]);

        \App\Models\BundleWebinar::create([
            'bundle_id'  => $bundle->id,
            'webinar_id' => $webinar->id,
        ]);

        return redirect('/panel/bundles/' . $bundleId . '/module/' . $webinar->id . '/edit');
    }

    public function moduleDestroy(Request $request, $bundleId, $courseId)
    {
        $this->authorize("panel_webinars_delete");

        $user = auth()->user();

        if ($user->isUser()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $bundleId)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->firstOrFail();

        $webinar = \App\Models\Webinar::where('id', $courseId)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->firstOrFail();

        $existingRequest = ContentDeleteRequest::query()
            ->where('user_id', $user->id)
            ->where('targetable_type', \App\Models\Webinar::class)
            ->where('targetable_id', $webinar->id)
            ->where('status', 'pending')
            ->first();

        if (empty($existingRequest)) {
            ContentDeleteRequest::create([
                'user_id' => $user->id,
                'targetable_type' => \App\Models\Webinar::class,
                'targetable_id' => $webinar->id,
                'description' => 'Delete request submitted from bundle module list.',
                'created_at' => time(),
            ]);
        }

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Delete request submitted for approval.',
            'status' => 'success'
        ];

        return back()->with(['toast' => $toastData]);
    }

    public function moduleEdit(Request $request, $bundleId, $courseId)
    {
        $this->authorize("panel_webinars_create");

        $user = auth()->user();

        if ($user->isUser()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $bundleId)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->firstOrFail();

        $webinar = \App\Models\Webinar::where('id', $courseId)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->with([
                'chapters' => function ($query) {
                    $query->orderBy('order', 'asc');
                    $query->with([
                        'chapterItems' => function ($query) {
                            $query->orderBy('order', 'asc');
                            $query->with([
                                'quiz' => function ($query) {
                                    $query->with([
                                        'quizQuestions' => function ($query) {
                                            $query->orderBy('order', 'asc');
                                        }
                                    ]);
                                }
                            ]);
                        }
                    ]);
                },
            ])
            ->firstOrFail();

        $stepCount = empty(getGeneralOptionsSettings('direct_publication_of_courses')) ? 8 : 7;

        return view('design_1.panel.bundles.module_editor.index', [
            'pageTitle'    => $webinar->title,
            'bundle'       => $bundle,
            'webinar'      => $webinar,
            'currentStep'  => 4,
            'stepCount'    => $stepCount,
            'locale'       => app()->getLocale(),
            'defaultLocale' => getDefaultLocale(),
            'userLanguages' => getUserLanguagesLists(),
        ]);
    }

    private function chapterItemTypeModelMap()
    {
        return [
            \App\Models\WebinarChapterItem::$chapterFile => \App\Models\File::class,
            \App\Models\WebinarChapterItem::$chapterSession => \App\Models\Session::class,
            \App\Models\WebinarChapterItem::$chapterTextLesson => \App\Models\TextLesson::class,
            \App\Models\WebinarChapterItem::$chapterAssignment => \App\Models\WebinarAssignment::class,
            \App\Models\WebinarChapterItem::$chapterQuiz => \App\Models\Quiz::class,
        ];
    }

    public function duplicate(Request $request, $id)
    {
        $this->authorize("panel_bundles_create");

        $user = auth()->user();

        if (!$user->isTeacher() and !$user->isAdmin()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->with(['translations', 'filterOptions', 'tags', 'faqs.translations', 'bundleWebinars'])
            ->first();

        if (empty($bundle)) {
            abort(404);
        }

        $newBundle = $this->replicateWithoutTranslatedAttributes($bundle);
        $newBundle->slug = Bundle::makeSlug($bundle->title . '-copy-' . time());
        $newBundle->status = Bundle::$isDraft;
        $newBundle->duplicated_from_id = $bundle->id;
        $newBundle->created_at = time();
        $newBundle->updated_at = null;
        $newBundle->save();

        foreach ($bundle->translations as $translation) {
            $newTranslation = $translation->replicate();
            $newTranslation->bundle_id = $newBundle->id;
            $newTranslation->title = $this->makeCopyTitle($translation->title);
            $newTranslation->save();
        }

        foreach ($bundle->filterOptions as $filterOption) {
            $newFilterOption = $filterOption->replicate();
            $newFilterOption->bundle_id = $newBundle->id;
            $newFilterOption->save();
        }

        foreach ($bundle->tags as $tag) {
            $newTag = $tag->replicate();
            $newTag->bundle_id = $newBundle->id;
            $newTag->save();
        }

        foreach ($bundle->faqs as $faq) {
            $newFaq = $this->replicateWithoutTranslatedAttributes($faq);
            $newFaq->bundle_id = $newBundle->id;
            $newFaq->save();

            $faqFk = $faq->getForeignKey(); // 'faq_id'
            foreach ($faq->translations as $faqTranslation) {
                $newFaqTranslation = $faqTranslation->replicate();
                $newFaqTranslation->{$faqFk} = $newFaq->id;
                $newFaqTranslation->save();
            }
        }

        foreach ($bundle->bundleWebinars as $bw) {
            $originalWebinar = $bw->webinar;

            if (empty($originalWebinar)) {
                continue;
            }

            $newWebinar = $this->duplicateWebinar($originalWebinar, $user->id);

            $newBundleWebinar = $bw->replicate();
            $newBundleWebinar->bundle_id = $newBundle->id;
            $newBundleWebinar->webinar_id = $newWebinar->id;
            $newBundleWebinar->save();
        }

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.bundle_duplicated_successfully'),
            'status' => 'success'
        ];

        return redirect("/panel/bundles")->with(['toast' => $toastData]);
    }

    private function duplicateWebinar($originalWebinar, $creatorId)
    {
        $chapterItemTypeModelMap = $this->chapterItemTypeModelMap(); // MỚI: gọi method thay vì đọc property

        $newWebinar = $this->replicateWithoutTranslatedAttributes($originalWebinar);
        $newWebinar->slug = \App\Models\Webinar::makeSlug($originalWebinar->title . '-copy-' . time());
        $newWebinar->status = \App\Models\Webinar::$isDraft;
        $newWebinar->creator_id = $creatorId;
        $newWebinar->created_at = time();
        $newWebinar->save();

        $webinarFk = $originalWebinar->getForeignKey();
        foreach ($originalWebinar->translations as $translation) {
            $newTranslation = $translation->replicate();
            $newTranslation->{$webinarFk} = $newWebinar->id;
            $newTranslation->save();
        }

        $chapters = \App\Models\WebinarChapter::where('webinar_id', $originalWebinar->id)
            ->orderBy('order')
            ->get();

        foreach ($chapters as $chapter) {
            $newChapter = $this->replicateWithoutTranslatedAttributes($chapter);
            $newChapter->webinar_id = $newWebinar->id;
            $newChapter->save();

            $chapterFk = $chapter->getForeignKey();
            foreach ($chapter->translations as $chapterTranslation) {
                $newChapterTranslation = $chapterTranslation->replicate();
                $newChapterTranslation->{$chapterFk} = $newChapter->id;
                $newChapterTranslation->save();
            }

            $items = \App\Models\WebinarChapterItem::where('chapter_id', $chapter->id)
                ->orderBy('order')
                ->get();

            foreach ($items as $item) {
                $modelClass = $chapterItemTypeModelMap[$item->type] ?? null;   // ĐỔI: dùng biến local

                if (empty($modelClass)) {
                    continue;
                }

                $originalContent = $modelClass::find($item->item_id);

                if (empty($originalContent)) {
                    continue;
                }

                $newContent = $originalContent->replicate();
                if (array_key_exists('webinar_id', $newContent->getAttributes())) {
                    $newContent->webinar_id = $newWebinar->id;
                }
                $newContent->save();

                $contentFk = $originalContent->getForeignKey();
                foreach ($originalContent->translations as $contentTranslation) {
                    $newContentTranslation = $contentTranslation->replicate();
                    $newContentTranslation->{$contentFk} = $newContent->id;
                    $newContentTranslation->save();
                }

                if ($item->type === \App\Models\WebinarChapterItem::$chapterQuiz
                    and method_exists($originalContent, 'quizQuestions')) {
                    foreach ($originalContent->quizQuestions as $question) {
                        $newQuestion = $this->replicateWithoutTranslatedAttributes($question);
                        $newQuestion->quiz_id = $newContent->id;
                        $newQuestion->save();

                        $questionFk = $question->getForeignKey();
                        foreach ($question->translations as $questionTranslation) {
                            $newQuestionTranslation = $questionTranslation->replicate();
                            $newQuestionTranslation->{$questionFk} = $newQuestion->id;
                            $newQuestionTranslation->save();
                        }
                    }
                }

                \App\Models\WebinarChapterItem::create([
                    'chapter_id' => $newChapter->id,
                    'item_id' => $newContent->id,
                    'type' => $item->type,
                    'user_id' => $creatorId,
                    'order' => $item->order,
                    'created_at' => time(),
                ]);
            }
        }

        return $newWebinar;
    }

    private function makeCopyTitle($title)
    {
        $baseTitle = preg_replace('/\s*-\s*Copy(\s*\(\d+\))?\s*$/i', '', $title);
        $candidate = $baseTitle . ' - Copy';
        $counter = 2;

        while (Bundle::whereTranslationLike('title', $candidate)->exists()) {
            $candidate = $baseTitle . " - Copy ({$counter})";
            $counter++;
        }

        return $candidate;
    }

    private function replicateWithoutTranslatedAttributes($model)
    {
        $newModel = $model->replicate();

        $except = property_exists($model, 'translatedAttributes') ? $model->translatedAttributes : [];
        $except[] = 'locale';

        foreach ($except as $attribute) {
            unset($newModel->{$attribute});
        }

        return $newModel;
    }

    public function toggleHidden(Request $request, $id)
    {
        $this->authorize("panel_bundles_create");

        $user = auth()->user();

        if (!$user->isTeacher() and !$user->isAdmin()) {
            abort(404);
        }

        $bundle = Bundle::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (empty($bundle)) {
            abort(404);
        }

        $bundle->update([
            'hidden_at' => $bundle->isHidden() ? null : time(),
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => $bundle->isHidden()
                ? trans('update.bundle_hidden_successfully')
                : trans('update.bundle_unhidden_successfully'),
            'status' => 'success'
        ];

        return back()->with(['toast' => $toastData]);
    }
}


