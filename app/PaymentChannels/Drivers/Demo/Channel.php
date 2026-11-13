<?php

namespace App\PaymentChannels\Drivers\Demo;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * GĐ5 - Cổng thanh toán Demo.
 *
 * Đi đúng luồng của cổng thật: tạo đơn → trang thanh toán (QR có hạn) → verify → Order paying
 * → PaymentController tạo Sale và mở khóa học như bình thường.
 * Chỉ chạy khi config('bundle_payment.demo_enabled') = true.
 */
class Channel extends BasePaymentChannel implements IChannel
{
    const RESULT_SUCCESS = 'success';
    const RESULT_FAIL = 'fail';
    const RESULT_CANCEL = 'cancel';
    const RESULT_EXPIRED = 'expired';

    const RESULTS = [self::RESULT_SUCCESS, self::RESULT_FAIL, self::RESULT_CANCEL, self::RESULT_EXPIRED];

    public $show_test_mode_toggle = false;
    protected array $credentialItems = [];

    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->setCredentialItems($paymentChannel);
    }

    /**
     * Trả về URL trang thanh toán Demo (đã ký, hết hạn theo thời hạn QR).
     */
    public function paymentRequest(Order $order)
    {
        if (!config('bundle_payment.demo_enabled')) {
            throw new \Exception('Demo payment is disabled');
        }

        $expiryMinutes = max(1, (int)config('bundle_payment.qr_expiry_minutes', 15));

        return URL::temporarySignedRoute('payment_demo.show', now()->addMinutes($expiryMinutes), [
            'order' => $order->id,
            'method' => session('payment_method_key', 'vietqr'),
        ]);
    }

    /**
     * URL kết quả (đã ký) mà trang Demo dùng cho các nút thành công / thất bại / hủy / hết hạn.
     */
    public static function makeResultUrl(Order $order, string $result): string
    {
        return URL::temporarySignedRoute('payment_verify', now()->addMinutes(30), [
            'gateway' => 'Demo',
            'order_id' => $order->id,
            'result' => $result,
        ]);
    }

    public function verify(Request $request)
    {
        if (!config('bundle_payment.demo_enabled') or !$request->hasValidSignature()) {
            return null;
        }

        $user = auth()->user();
        $result = $request->get('result');

        $order = Order::query()
            ->where('id', $request->get('order_id'))
            ->where('user_id', !empty($user) ? $user->id : 0)
            ->first();

        if (empty($order) or !in_array($result, self::RESULTS, true)) {
            return null;
        }

        // Đơn đã xử lý rồi (bấm lại / F5) thì trả nguyên trạng thái, không tạo Sale lần nữa
        if (!in_array($order->status, [Order::$pending, Order::$paying], true)) {
            return $order;
        }

        $order->update([
            'status' => $result === self::RESULT_SUCCESS ? Order::$paying : Order::$fail,
        ]);

        return $order;
    }
}
