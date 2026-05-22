@php
    $savedDate = $mockTestDate ?? null;
    $daysLeft  = null;
    if ($savedDate) {
        $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($savedDate)->startOfDay(), false);
        if ($daysLeft < 0) $daysLeft = 0;
    }
    $savedAimBand = $aimBand ?? null;
@endphp

<div class="bg-white rounded-24 p-16">
    <div class="d-flex align-items-center justify-content-between mb-8">
        <h5 class="font-13 font-weight-bold text-dark mb-0">Count down</h5>
    </div>

    <div class="mt-8 text-center">
        @if($savedDate)
            @if((int)$daysLeft > 0)
                <p class="font-28 font-weight-bold text-primary mb-0">{{ $daysLeft }}</p>
                <p class="font-12 text-gray-500 mb-0">days left until mock test</p>
                <p class="font-11 text-gray-400 mb-0">{{ \Carbon\Carbon::parse($savedDate)->format('d.m.Y') }}</p>
            @else
                <p class="font-14 font-weight-bold text-success mb-0">🎉 Mock test day!</p>
                <p class="font-12 text-gray-500 mb-0">{{ \Carbon\Carbon::parse($savedDate)->format('d.m.Y') }}</p>
            @endif
        @else
            <p class="font-12 text-gray-400 mb-0">No mock test date set yet</p>
        @endif
    </div>
</div>

<div class="bg-white rounded-24 p-16 mt-16">
    <div class="d-flex align-items-center justify-content-between mb-8">
        <h5 class="font-13 font-weight-bold text-dark mb-0">Aim band</h5>
    </div>

    <div class="mt-8 text-center">
        @if($savedAimBand)
            <p class="font-28 font-weight-bold text-warning mb-0">{{ $savedAimBand }}</p>
            <p class="font-12 text-gray-500 mb-0">target IELTS band</p>
        @else
            <p class="font-12 text-gray-400 mb-0">No target band set yet</p>
        @endif
    </div>
</div>
