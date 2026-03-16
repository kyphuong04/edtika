{{-- Teacher Welcome Bar --}}
<div class="d-flex align-items-center justify-content-between bg-white rounded-24 px-24 py-16 teacher-welcome-bar">

    {{-- Left: Greeting + name --}}
    <div class="d-flex align-items-center gap-16 flex-1" style="min-width:0;">
        <div class="d-flex-center size-48 rounded-circle bg-primary-40 flex-shrink-0">
            <img src="{{ $authUser->getAvatar(48) }}" alt="{{ $authUser->full_name }}" class="img-cover rounded-circle" style="width:48px;height:48px;">
        </div>
        <div style="min-width:0;">
            <h2 class="font-18 font-weight-bold text-dark text-ellipsis text-uppercase">
                {{ trans('update.hello_user', ['user' => $authUser->full_name]) }} 👋
            </h2>
            <p class="font-12 text-gray-500 mt-4">{{ now()->format('l, d M Y') }}</p>
        </div>
    </div>

    {{-- Center: Star rating --}}
    <div class="d-flex align-items-center gap-8 mx-24">
        @php
            $avgRating   = $teacherRating['avgRating']   ?? 0;
            $reviewCount = $teacherRating['reviewCount'] ?? 0;
        @endphp

        @include('design_1.web.components.rate', [
            'rate'            => $avgRating,
            'rateCount'       => $reviewCount > 0 ? $reviewCount : null,
            'rateIconSize'    => '20px',
            'showRateStars'   => true, // always render five outlines when no rating
        ])
    </div>

    {{-- Right: Notification bell --}}
    <div class="flex-shrink-0">
        @include('design_1.panel.includes.header.notification')
    </div>
</div>
