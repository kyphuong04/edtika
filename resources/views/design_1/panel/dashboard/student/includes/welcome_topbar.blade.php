@php
    $continueLearningRaw = $helloBox['continueLearningCourses'] ?? [];
    $enrolledCourses     = collect($continueLearningRaw);
    $continueCourse      = $enrolledCourses->first();
    $overallProgress     = !empty($ieltsData['overallBand']) ? round($ieltsData['overallBand'] / 9 * 100) : 0;
@endphp

<div class="ielts-welcome-bar bg-white rounded-24 p-16 d-flex flex-wrap align-items-center gap-12">

    {{-- Left: greeting --}}
    <div class="flex-grow-1 min-w-0">
        <h1 class="font-18 font-weight-bold text-dark text-ellipsis mb-0">
            WELCOME, {{ strtoupper($authUser->name ?? $authUser->full_name) }}! 👋
        </h1>
        {{-- IELTS overall progress bar --}}
        <div class="ielts-welcome-bar__progress mt-8">
            <div class="ielts-welcome-bar__track rounded-pill bg-gray-100" style="height:6px;">
                <div class="ielts-welcome-bar__fill rounded-pill bg-primary" style="width:{{ $overallProgress }}%;height:6px;transition:width .6s ease;"></div>
            </div>
        </div>
    </div>

    {{-- Switch Courses moved to sidebar --}}

    {{-- Continue → --}}
    @if($continueCourse)
        <a href="{{ $continueCourse->getLearningPageUrl() }}"
           class="btn btn-primary btn-sm rounded-pill px-16">
            Continue &rarr;
        </a>
    @else
        <a href="/classes" class="btn btn-primary btn-sm rounded-pill px-16">
            Explore courses &rarr;
        </a>
    @endif

    {{-- Notification bell --}}
    <a href="/panel/notifications" class="ielts-welcome-bar__bell d-flex-center size-40 rounded-circle bg-gray-100 position-relative text-dark">
        <x-iconsax-bul-notification class="icons" width="20px" height="20px"/>
        @php
            $unreadCount = auth()->user()->getUnReadNotifications()->count();
        @endphp
        @if($unreadCount > 0)
            <span class="position-absolute top-0 end-0 size-16 rounded-circle bg-danger d-flex-center font-10 text-white"
                  style="font-size:9px;top:2px;right:2px;min-width:16px;height:16px;">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
        @endif
    </a>

</div>
