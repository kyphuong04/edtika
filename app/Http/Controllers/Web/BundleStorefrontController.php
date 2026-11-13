<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\File;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * GĐ5 - Trang chủ khóa học theo design mới: danh sách, chi tiết, mua nhanh.
 * BundleController@index/show gọi sang đây khi bật config('bundle_learning.new_storefront').
 */
class BundleStorefrontController extends Controller
{
    /**
     * Trang danh sách khóa học (chọn band hiện tại / mục tiêu để gợi ý khóa).
     */
    public function catalog(Request $request)
    {
        $bundles = Bundle::query()
            ->where('status', Bundle::$active)
            ->whereNull('hidden_at')
            ->with(['gifts', 'tags', 'category'])
            ->withCount('bundleWebinars')
            ->orderByDesc('created_at')
            ->get();

        $user = auth()->user();
        $ownedIds = !empty($user) ? $user->getPurchasedBundlesIds() : [];

        $seoSettings = getSeoMetas('bundles_lists');

        return view('design_1.web.bundles.catalog.index', [
            'pageTitle' => $seoSettings['title'] ?? trans('update.bundles'),
            'pageDescription' => $seoSettings['description'] ?? '',
            'pageRobot' => getPageRobot('bundles_lists'),
            'seoSettings' => $seoSettings,
            'bundles' => $bundles,
            'ownedIds' => $ownedIds,
        ]);
    }

    /**
     * Trang chi tiết khóa học.
     */
    public function detail(Request $request, $slug)
    {
        $bundle = Bundle::query()
            ->where('slug', $slug)
            ->where('status', Bundle::$active)
            ->with(['gifts', 'highlights', 'tags', 'teacher', 'category', 'checkpoints'])
            ->firstOrFail();

        $webinarIds = $bundle->bundleWebinars()
            ->orderByRaw('`order` IS NULL, `order` ASC')
            ->orderBy('id')
            ->pluck('webinar_id')
            ->all();

        $modules = Webinar::query()
            ->whereIn('id', $webinarIds)
            ->where('status', Webinar::$active)
            ->get()
            ->sortBy(function ($webinar) use ($webinarIds) {
                return array_search($webinar->id, $webinarIds);
            })
            ->values();

        $activeWebinarIds = $modules->pluck('id')->all();

        $chapters = empty($activeWebinarIds) ? collect() : WebinarChapter::query()
            ->whereIn('webinar_id', $activeWebinarIds)
            ->where('status', 'active')
            ->orderByRaw('`order` IS NULL, `order` ASC')
            ->orderBy('id')
            ->get();
        $lessonsCount = $chapters->count();
        $chaptersByWebinar = $chapters->groupBy('webinar_id');

        $videosCount = empty($activeWebinarIds) ? 0 : File::query()
            ->whereIn('webinar_id', $activeWebinarIds)
            ->where('status', File::$Active)
            ->whereIn('file_type', File::$videoTypes)
            ->count();

        // Slider "Chi tiết và nội dung": dùng nội dung teacher nhập, chưa có thì tự tạo từ tên module
        $highlights = $bundle->highlights->map(function ($highlight) {
            return ['title' => $highlight->title, 'description' => $highlight->description];
        });

        if ($highlights->isEmpty()) {
            $highlights = collect([[
                'title' => trans('bundle_payment.highlight_intro'),
                'description' => strip_tags((string)$bundle->summary),
            ]]);

            foreach ($modules as $index => $module) {
                // Mô tả = tên các lesson trong module (như bản demo), không có thì dùng tóm tắt module
                $lessonTitles = ($chaptersByWebinar[$module->id] ?? collect())->pluck('title')->filter()->implode(' · ');

                $highlights->push([
                    'title' => 'Module ' . str_pad($index + 1, 2, '0', STR_PAD_LEFT) . ' — ' . $module->title,
                    'description' => $lessonTitles !== '' ? $lessonTitles : strip_tags((string)$module->summary),
                ]);
            }

            // Các phần chung của khóa theo design
            if ($bundle->checkpoints->isNotEmpty()) {
                foreach ($bundle->checkpoints as $checkpoint) {
                    $highlights->push([
                        'title' => $checkpoint->title ?: trans('bundle_learning.checkpoint_' . $checkpoint->type),
                        'description' => trans('bundle_payment.highlight_checkpoint_' . $checkpoint->type),
                    ]);
                }
            }
            $highlights->push([
                'title' => 'Teacher Support',
                'description' => trans('bundle_payment.highlight_teacher_support'),
            ]);
        }

        $user = auth()->user();
        $owned = (!empty($user) && $bundle->checkUserHasBought($user));

        return view('design_1.web.bundles.detail.index', [
            'pageTitle' => $bundle->title,
            'pageDescription' => $bundle->seo_description,
            'pageRobot' => getPageRobot('bundle_show'),
            'bundle' => $bundle,
            'modules' => $modules,
            'lessonsCount' => $lessonsCount,
            'videosCount' => $videosCount,
            'highlights' => $highlights->values(),
            'owned' => $owned,
        ]);
    }

    /**
     * Panel mua nhanh: tính tiền (có mã giảm giá) trước khi thanh toán.
     */
    public function quickBuyPreview(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'bundle_id' => 'required|integer',
            'coupon' => 'nullable|string|max:64',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 422, 'errors' => $validator->errors()], 422);
        }

        $bundle = $this->findSellableBundle((int)$request->get('bundle_id'));

        if (empty($bundle)) {
            return response()->json(['code' => 404], 404);
        }

        if ($bundle->checkUserHasBought($user)) {
            return response()->json([
                'code' => 200,
                'owned' => true,
                'learn_url' => '/panel/courses/purchases/' . $bundle->slug,
            ]);
        }

        [$discount, $couponMessage] = $this->resolveCoupon($request->get('coupon'));
        $calculate = $this->cartController()->calculatePrice($this->makeFakeCarts($bundle, $user), $user, $discount);

        return response()->json([
            'code' => 200,
            'owned' => false,
            'coupon_applied' => !empty($discount),
            'coupon_message' => $couponMessage,
            'discount_id' => !empty($discount) ? $discount->id : null,
            'sub_total' => handlePrice($calculate['sub_total']),
            'total_discount' => handlePrice($calculate['total_discount']),
            'has_discount' => $calculate['total_discount'] > 0,
            'tax_price' => handlePrice($calculate['tax_price']),
            'has_tax' => $calculate['tax_price'] > 0,
            'total' => handlePrice($calculate['total']),
            'is_free' => $calculate['total'] <= 0,
        ]);
    }

    /**
     * Panel mua nhanh: tạo đơn và chuyển sang cổng thanh toán của phương thức đã chọn.
     */
    public function quickBuy(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'bundle_id' => 'required|integer',
            'gateway' => 'required|string',
            'coupon' => 'nullable|string|max:64',
        ]);

        $bundle = $this->findSellableBundle((int)$request->get('bundle_id'));

        if (empty($bundle)) {
            abort(404);
        }

        $checkCourseForSale = checkCourseForSale($bundle, $user);
        if ($checkCourseForSale != 'ok') {
            return $checkCourseForSale;
        }

        [$discount] = $this->resolveCoupon($request->get('coupon'));
        $carts = $this->makeFakeCarts($bundle, $user);
        $cartController = $this->cartController();
        $calculate = $cartController->calculatePrice($carts, $user, $discount);

        // Khóa miễn phí (hoặc giảm 100%): dùng luồng có sẵn của checkout để ghi nhận đơn 0đ
        if ($calculate['total'] <= 0) {
            return $cartController->checkout(new Request(['discount_id' => !empty($discount) ? $discount->id : null]), $carts);
        }

        $order = $cartController->createOrderAndOrderItems($carts, $calculate, $user, $discount);

        return app(PaymentController::class)->paymentRequest(new Request([
            'gateway' => $request->get('gateway'),
            'order_id' => $order->id,
        ]));
    }

    /**
     * Dữ liệu cho panel giỏ hàng / thanh toán demo (không cần đăng nhập).
     * - Không truyền bundle_id: trả về các khóa học trong giỏ (DB nếu đã đăng nhập, cookie nếu là khách).
     * - Có bundle_id: trả về đúng 1 khóa để mở panel ở chế độ "Mua ngay".
     */
    public function cartPreview(Request $request)
    {
        $user = auth()->user();
        $items = collect();

        if ($request->filled('bundle_id')) {
            $bundle = $this->findSellableBundle((int)$request->get('bundle_id'));

            if (!empty($bundle)) {
                $items->push($this->makePreviewItem($bundle, null, $user));
            }
        } else {
            $carts = app(CartManagerController::class)->getCarts();

            foreach ($carts as $cart) {
                if (!empty($cart->bundle_id) && !empty($cart->bundle)) {
                    // Khách: xóa theo key trong cookie (uid); đã đăng nhập: xóa theo id bản ghi giỏ hàng
                    $removeKey = !empty($user) ? $cart->id : $cart->uid;
                    $items->push($this->makePreviewItem($cart->bundle, $removeKey, $user));
                }
            }
        }

        $currency = $items->isNotEmpty() ? $items->first()['currency'] : getDefaultCurrency();

        return response()->json([
            'code' => 200,
            'logged_in' => !empty($user),
            // Mẫu định dạng giá, JS thay __N__ bằng số đã định dạng
            'price_format' => addCurrencyCodeToPrice('__N__', $currency),
            'items' => $items->values(),
        ]);
    }

    /* ---------------- Helpers ---------------- */

    private function makePreviewItem(Bundle $bundle, $removeKey, $user): array
    {
        $price = (int)$bundle->price;
        $original = max((int)$bundle->original_price, $price);
        $offPercent = $bundle->getOriginalPriceDiscountPercent();

        return [
            'id' => $bundle->id,
            'remove_key' => $removeKey,
            'title' => $bundle->title,
            'tag' => !empty($bundle->category) ? $bundle->category->title : strtoupper($bundle->exam_type ?: 'IELTS'),
            'price' => $price,
            'old' => $original,
            'off' => $offPercent > 0 ? '-' . $offPercent . '%' : '',
            'currency' => $bundle->getPriceCurrency(),
            'url' => '/bundles/' . $bundle->slug,
            'learn_url' => '/panel/courses/purchases/' . $bundle->slug,
            'owned' => !empty($user) && $bundle->checkUserHasBought($user),
        ];
    }

    private function findSellableBundle(int $bundleId): ?Bundle
    {
        return Bundle::query()
            ->where('id', $bundleId)
            ->where('status', Bundle::$active)
            ->first();
    }

    private function makeFakeCarts(Bundle $bundle, $user)
    {
        $cart = new Cart();
        $cart->creator_id = $user->id;
        $cart->bundle_id = $bundle->id;
        $cart->ticket_id = null;
        $cart->special_offer_id = null;
        $cart->created_at = time();

        return collect([$cart]);
    }

    /**
     * @return array [Discount|null, string|null thông báo cho người dùng]
     */
    private function resolveCoupon($code): array
    {
        $code = trim((string)$code);

        if ($code === '') {
            return [null, null];
        }

        $discount = Discount::query()->where('code', $code)->first();

        if (empty($discount)) {
            return [null, trans('cart.coupon_invalid')];
        }

        $check = $discount->checkValidDiscount();

        if ($check != 'ok') {
            return [null, $check];
        }

        return [$discount, trans('bundle_payment.coupon_applied')];
    }

    private function cartController(): CartController
    {
        return app(CartController::class);
    }
}
