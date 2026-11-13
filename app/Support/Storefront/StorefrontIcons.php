<?php

namespace App\Support\Storefront;

/**
 * GĐ5 - Icon SVG dùng trên trang khóa học (lấy từ bản demo của khách).
 */
class StorefrontIcons
{
    const GIFT = '<svg viewBox="0 0 24 24"><rect x="3" y="9" width="18" height="12" rx="2"/><path d="M2 9h20M12 9v12M12 9c-8-1-8-9-3-7 2 1 3 4 3 7zm0 0c8-1 8-9 3-7-2 1-3 4-3 7z"/></svg>';

    const CART = '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h2l2.1 10.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.5L21 9H7"></path><circle cx="10.6" cy="20" r="1"></circle><circle cx="17.6" cy="20" r="1"></circle></svg>';

    const GIFT_ITEMS = [
        'gift' => self::GIFT,
        'book' => '<svg viewBox="0 0 24 24"><path d="M3 5h8a3 3 0 013 3v13a3 3 0 00-3-3H3zM21 5h-5a3 3 0 00-3 3v13a3 3 0 013-3h5z"/></svg>',
        'sparkles' => '<svg viewBox="0 0 24 24"><path d="m12 2 2.7 7.3L22 12l-7.3 2.7L12 22l-2.7-7.3L2 12l7.3-2.7z"/></svg>',
        'pen' => '<svg viewBox="0 0 24 24"><path d="M4 20l4-1 12-12-3-3L5 16zM14 7l3 3"/></svg>',
        'box' => '<svg viewBox="0 0 24 24"><rect x="4" y="6" width="16" height="15" rx="2"/><path d="M4 11h16M12 6v15"/></svg>',
        'cup' => '<svg viewBox="0 0 24 24"><path d="M4 5h14v12a4 4 0 01-4 4H8a4 4 0 01-4-4zM18 8h2a3 3 0 010 6h-2"/></svg>',
    ];

    public static function giftItem(?string $icon): string
    {
        return self::GIFT_ITEMS[$icon] ?? self::GIFT;
    }

    /**
     * Số khóa trong giỏ (đăng nhập: bảng cart; khách: cookie "carts").
     */
    public static function cartCount(): int
    {
        if (auth()->check()) {
            return \App\Models\Cart::query()->where('creator_id', auth()->id())->count();
        }

        $cookie = request()->cookie('carts');
        $items = !empty($cookie) ? json_decode($cookie, true) : [];

        return is_array($items) ? count($items) : 0;
    }
}
