{{-- GĐ5: thẻ khóa học (bundle thật) trên trang /classes. $course = null khi dùng làm mẫu cho JS --}}
<article class="edtika-classes-page__bundle-card">
    <div class="edtika-classes-page__bundle-top">
        <img src="{{ asset('store/icons/—Pngtree—abstract purple line wave background_5542852 1.png') }}" alt="Abstract background">
    </div>

    <div class="edtika-classes-page__bundle-body">
        <h4 class="edtika-classes-page__bundle-name" data-suggested-name>{{ $course['name'] ?? '' }}</h4>

        <ul class="edtika-classes-page__bundle-features" data-suggested-features>
            @foreach(($course['items'] ?? []) as $feature)
                <li>{{ $feature }}</li>
            @endforeach
        </ul>

        <div class="edtika-classes-page__bundle-bottom">
            <div>
                <div class="edtika-classes-page__bundle-price" data-suggested-price>{{ $course['price'] ?? '' }}</div>
                <div class="edtika-classes-page__bundle-billing" data-suggested-billing>{{ $course['billing'] ?? '' }}</div>
            </div>

            <div class="edtika-classes-page__bundle-actions">
                @if(!empty($course) and !empty($course['owned']))
                    <a href="{{ $course['learn_url'] }}" class="edtika-classes-page__bundle-btn edtika-classes-page__bundle-btn--buy">Vào học</a>
                @else
                    <button type="button"
                            class="edtika-classes-page__bundle-btn edtika-classes-page__bundle-btn--buy js-sf-buy"
                            data-suggested-buy
                            data-bundle-id="{{ $course['bundle_id'] ?? '' }}"
                            data-title="{{ $course['name'] ?? '' }}">Mua</button>
                @endif
                <a href="{{ $course['detail_url'] ?? '/bundles' }}" class="edtika-classes-page__bundle-btn edtika-classes-page__bundle-btn--view" data-suggested-view>Xem</a>
            </div>
        </div>
    </div>
</article>
