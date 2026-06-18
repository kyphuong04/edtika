@php
    $authUser = auth()->user();

    if (empty($authUser)) {
        $enrolledCourses = collect();
        $showSwitch = false;
    } else {
        // Build enrolled courses list similar to other sidebar helpers
        $enrolledCourses = \App\Models\Sale::where('buyer_id', $authUser->id)
            ->where('type', 'webinar')
            ->whereNull('refund_at')
            ->whereNotNull('webinar_id')
            ->with('webinar')
            ->get()
            ->filter(fn($s) => !is_null($s->webinar))
            ->map(fn($s) => $s->webinar)
            ->unique('id')
            ->values();

        $showSwitch = (
            $authUser->isUser() || $authUser->isStudent() || $authUser->isTeacher() || $authUser->isAdmin() || $authUser->isOrganization()
        );
    }
@endphp

<style>
.btn {
    border-radius: 12px;
    background: #fff;
    color: #511D99;
    border: 1.5px solid #511D99;
    transition: background .15s, color .15s, border-color .15s;
}

.btn:hover {
    background: #511D99;
    color: #fff;
    border-color: #511D99;
}
</style>

@if(!empty($showSwitch))
    <div class="panel-sidebar__switch-courses px-16 pb-12">
        <div class="dropdown w-100">
            <button class="btn btn-sm rounded-pill w-100 d-flex align-items-center justify-content-between"
                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span>Switch courses</span>
                <i class="fa fa-angle-down ml-2" aria-hidden="true"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-right shadow rounded-12 border-0 mt-8" style="min-width:220px;">
                @forelse($enrolledCourses as $course)
                    <a class="dropdown-item d-flex align-items-center gap-8 py-8 px-12" href="{{ $course->getLearningPageUrl() }}">
                        <div class="size-32 rounded-8 bg-gray-100 flex-shrink-0">
                            <img src="{{ $course->getIcon() }}" alt="" class="img-cover rounded-8">
                        </div>
                        <span class="font-12 text-dark">{{ truncate($course->title, 28) }}</span>
                    </a>
                @empty
                    <span class="dropdown-item font-12 text-gray-500">No courses enrolled</span>
                @endforelse
                <div class="dropdown-divider"></div>
                <a class="dropdown-item font-12 text-primary" href="/panel/courses/purchases">All courses</a>
            </div>
        </div>
    </div>
@endif
