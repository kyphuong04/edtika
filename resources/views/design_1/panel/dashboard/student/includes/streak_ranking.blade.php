@php
    $streak  = $ieltsData['streak']   ?? 0;
    $rank    = $ieltsData['userRank'] ?? '--';
@endphp

<div class="row gx-10 mt-16">

    {{-- Streak --}}
    <div class="col-6">
        <div class="bg-white rounded-24 p-12 h-100">
            <div class="h-100 d-flex align-items-center justify-content-center gap-30">
                <div class="d-flex-center size-32 rounded-12 bg-warning-40 flex-shrink-0">
                    <x-iconsax-bul-flash class="icons text-warning" width="16px" height="16px"/>
                </div>
                <div class="flex-grow-1 text-center">
                    <p class="font-28 font-weight-bold text-dark mb-0">{{ $streak }}</p>
                    <p class="font-10 text-gray-500 mb-0">
                        @if($streak == 1) 1-day @elseif($streak > 1) {{ $streak }}-day @else @endif
                        STREAK
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Ranking --}}
    <div class="col-6">
        <div class="bg-white rounded-24 p-12 h-100">
            <div class="h-100 d-flex align-items-center justify-content-center gap-30">
                <div class="d-flex-center size-32 rounded-12 bg-primary-40 flex-shrink-0">
                    <x-iconsax-bul-cup class="icons text-primary" width="16px" height="16px"/>
                </div>
                <div class="flex-grow-1 text-center">
                    <p class="font-28 font-weight-bold text-dark mb-0">#{{ $rank }}</p>
                    <p class="font-10 text-gray-500 mb-0">RANKING</p>
                </div>
            </div>
        </div>
    </div>

</div>
