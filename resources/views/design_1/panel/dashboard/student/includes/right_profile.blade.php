@php
    $overallBand = $ieltsData['overallBand'] ?? 0;
    $bandDisplay = $overallBand > 0 ? number_format($overallBand, 1) : '--';
@endphp

<div class="bg-white rounded-24 p-16 d-flex align-items-center gap-12 h-100">
    {{-- Avatar --}}
    <div class="size-56 rounded-circle flex-shrink-0 bg-gray-100">
        <img src="{{ $authUser->getAvatar(56) }}" alt="" class="img-cover rounded-circle size-56">
    </div>

    {{-- Name & Band --}}
    <div class="min-w-0 flex-grow-1">
        <p class="font-14 font-weight-bold text-dark text-ellipsis mb-2">{{ strtoupper($authUser->full_name) }}</p>
        <p class="font-12 text-gray-500 mb-0">
            BAND ESTIMATE:
            <span class="font-weight-bold text-primary">{{ $bandDisplay }}</span>
        </p>
    </div>
</div>
