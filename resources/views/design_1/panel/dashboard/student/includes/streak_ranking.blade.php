@php
    $streak  = $ieltsData['streak']   ?? 0;
    $rank    = $ieltsData['userRank'] ?? '--';
@endphp

<div class="row gx-12 mt-16">

    {{-- Streak --}}
    <div class="col-6">
        <div class="bg-white rounded-24 p-16 text-center">
            <div class="d-flex-center size-40 rounded-12 bg-warning-40 mx-auto mb-8">
                <x-iconsax-bul-flash class="icons text-warning" width="20px" height="20px"/>
            </div>
            <p class="font-22 font-weight-bold text-dark mb-0">{{ $streak }}</p>
            <p class="font-11 text-gray-500 mb-0">
                @if($streak == 1) 1-day @elseif($streak > 1) {{ $streak }}-day @else -- @endif
                STREAK
            </p>
        </div>
    </div>

    {{-- Ranking --}}
    <div class="col-6">
        <div class="bg-white rounded-24 p-16 text-center">
            <div class="d-flex-center size-40 rounded-12 bg-primary-40 mx-auto mb-8">
                <x-iconsax-bul-cup class="icons text-primary" width="20px" height="20px"/>
            </div>
            <p class="font-22 font-weight-bold text-dark mb-0">#{{ $rank }}</p>
            <p class="font-11 text-gray-500 mb-0">RANKING</p>
        </div>
    </div>

</div>
