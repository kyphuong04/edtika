<?php

/**
 * GĐ5 - Phương thức thanh toán hiển thị cho người mua và cổng (driver) xử lý từng phương thức.
 *
 * Khi khách chốt cổng thật: thêm driver vào app/PaymentChannels/Drivers/{TênCổng}/Channel.php,
 * thêm dòng vào bảng payment_channels (class_name = TênCổng, status = active),
 * rồi đổi biến env tương ứng bên dưới (ví dụ PAYMENT_METHOD_ATM_CHANNEL=Vnpay).
 */
return [
    // Cổng Demo: mô phỏng QR / thành công / thất bại / hết hạn. KHÔNG bật trên production thật.
    'demo_enabled' => env('PAYMENT_DEMO_ENABLED', false),

    // Thời hạn của mã QR / phiên thanh toán (phút)
    'qr_expiry_minutes' => (int)env('PAYMENT_QR_EXPIRY_MINUTES', 15),

    // Thứ tự hiển thị trên trang checkout và trong panel mua nhanh
    'methods' => [
        'vietqr' => [
            'title' => 'VietQR',
            'detail' => 'Quét QR bằng app ngân hàng',
            'icon' => '▦',
            'channel' => env('PAYMENT_METHOD_VIETQR_CHANNEL', 'Demo'),
            'recommended' => true,
        ],
        'wallet' => [
            'title' => 'Ví điện tử',
            'detail' => 'MoMo / ZaloPay',
            'icon' => '◈',
            'channel' => env('PAYMENT_METHOD_WALLET_CHANNEL', 'Demo'),
        ],
        'atm' => [
            'title' => 'Thẻ ATM',
            'detail' => 'VNPay / OnePay',
            'icon' => '▤',
            'channel' => env('PAYMENT_METHOD_ATM_CHANNEL', 'Demo'),
        ],
        'card' => [
            'title' => 'Thẻ quốc tế',
            'detail' => 'Visa / Mastercard',
            'icon' => '▧',
            'channel' => env('PAYMENT_METHOD_CARD_CHANNEL', 'Demo'),
        ],
    ],
];
