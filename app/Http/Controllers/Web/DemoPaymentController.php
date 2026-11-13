<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\PaymentChannels\Drivers\Demo\Channel as DemoChannel;
use Illuminate\Http\Request;

/**
 * GĐ5 - Trang thanh toán của cổng Demo (giả lập QR / ví / thẻ).
 * Route có middleware "signed": chỉ mở được bằng URL do Demo\Channel::paymentRequest() tạo ra.
 */
class DemoPaymentController extends Controller
{
    public function show(Request $request, $orderId)
    {
        if (!config('bundle_payment.demo_enabled')) {
            abort(404);
        }

        $user = auth()->user();

        $order = Order::query()
            ->where('id', $orderId)
            ->where('user_id', !empty($user) ? $user->id : 0)
            ->with(['orderItems.bundle', 'orderItems.webinar'])
            ->firstOrFail();

        // Đơn đã thanh toán / thất bại thì xem trạng thái
        if (!in_array($order->status, [Order::$pending, Order::$paying], true)) {
            return redirect("/payments/status?t={$order->id}");
        }

        $methods = config('bundle_payment.methods', []);
        $methodKey = array_key_exists($request->get('method'), $methods) ? $request->get('method') : array_key_first($methods);

        return view('design_1.web.cart.payment.demo', [
            'pageTitle' => trans('bundle_payment.demo_page_title'),
            'order' => $order,
            'methods' => $methods,
            'methodKey' => $methodKey,
            'expiresAt' => (int)$request->get('expires'),
            'successUrl' => DemoChannel::makeResultUrl($order, DemoChannel::RESULT_SUCCESS),
            'failUrl' => DemoChannel::makeResultUrl($order, DemoChannel::RESULT_FAIL),
            'cancelUrl' => DemoChannel::makeResultUrl($order, DemoChannel::RESULT_CANCEL),
            'expiredUrl' => DemoChannel::makeResultUrl($order, DemoChannel::RESULT_EXPIRED),
            'transferContent' => 'EDTIKA ' . $order->id,
        ]);
    }
}
