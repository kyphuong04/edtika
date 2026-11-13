{{-- GĐ5: nút giỏ hàng nổi (theo bản demo), số lượng cập nhật khi thêm vào giỏ.
     Bấm vào mở panel giỏ hàng / thanh toán demo (cart_panel), không cần đăng nhập. --}}
@php
    $edkCartCount = \App\Support\Storefront\StorefrontIcons::cartCount();
@endphp
<a href="/cart" id="pu-cart-fab" class="pu-cart-fab js-edk-open-cart" role="button" aria-label="Mở giỏ hàng" title="Giỏ hàng">
    {!! \App\Support\Storefront\StorefrontIcons::CART !!}
    <span id="pu-fab-count" class="pu-fab-count js-edk-cart-count">{{ $edkCartCount }}</span>
</a>

@include('design_1.web.bundles.storefront.cart_panel')
