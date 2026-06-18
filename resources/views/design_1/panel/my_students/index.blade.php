@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
/* ── Student card grid ───────────────────────────────────────────── */
.sc-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
@media (max-width: 1199px) { .sc-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 991px)  { .sc-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px)  { .sc-grid { grid-template-columns: 1fr; } }

.sc-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f0f0f0;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: box-shadow .2s, border-color .2s;
}
.sc-card:hover {
    box-shadow: 0 4px 18px rgba(81,29,153,.12);
    border-color: #511D99;
}

/* header row */
.sc-card__header {
    display: flex;
    align-items: center;
    gap: 12px;
}
.sc-card__avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid #f0f0f0;
}
.sc-card__name {
    font-size: 14px;
    font-weight: 700;
    color: #1e2a3b;
    line-height: 1.3;
    margin: 0;
}

/* meta rows */
.sc-card__meta {
    font-size: 12px;
    color: #6c757d;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.sc-card__meta-row {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.sc-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.sc-meta-item .label { color: #adb5bd; font-weight: 500; }
.sc-meta-item .value { color: #495057; font-weight: 600; }
.sc-meta-item .value.band { color: #511D99; }

/* course row */
.sc-card__course-row {
    display: flex;
    align-items: baseline;
    gap: 4px;
    min-width: 0;
    font-size: 12px;
}
.sc-card__course-label {
    color: #adb5bd;
    font-weight: 500;
    white-space: nowrap;
    flex-shrink: 0;
}
.sc-card__course {
    font-weight: 600;
    color: #511D99;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0;
}

/* progress */
.sc-card__progress .progress {
    height: 6px;
    border-radius: 20px;
    background: #f0f0f0;
    margin-top: 4px;
}
.sc-card__progress .progress-bar {
    border-radius: 20px;
    background: linear-gradient(90deg, #511D99, #8431ff);
}

/* footer */
.sc-card__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 2px;
}
.sc-last-active {
    font-size: 11px;
    color: #adb5bd;
}
.sc-actions {
    display: flex;
    gap: 6px;
}
.sc-btn {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e9ecef;
    background: #ffffff;
    color: #511D99;
    transition: background .15s, color .15s, border-color .15s;
    cursor: pointer;
    text-decoration: none !important;
}
.sc-btn:hover { background: #511D99; border-color: #511D99; color: #fff; }

/* search bar */
.sc-search-wrap {
    margin-top: 24px;
    position: relative;
    flex: 1;
}
.sc-search-wrap .sc-search-icon {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    color: #adb5bd;
    pointer-events: none;
}
.sc-search-wrap input {
    padding-left: 40px;
    border-radius: 12px;
    border: 1.5px solid #d0d5dd;
    background: #fff;
    height: 44px;
    font-size: 14px;
    color: #495057;
    width: 100%;
}
.sc-search-wrap input:focus {
    outline: none;
    border-color: #511D99;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(81,29,153,.12);
}

/* filter dropdown */
.sc-filter-wrap {
    margin-top: 24px;
    position: relative;
}
.sc-filter-btn {
    height: 44px;
    padding: 0 18px;
    border-radius: 12px;
    border: 1.5px solid #d0d5dd;
    background: #fff;
    font-size: 14px;
    color: #495057;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    white-space: nowrap;
    min-width: 130px;
    justify-content: space-between;
    user-select: none;
}
.sc-filter-btn:hover { border-color: #511D99; background: #fff; }
.sc-filter-dropdown {
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
    min-width: 220px;
    z-index: 100;
    display: none;
    overflow: hidden;
}
.sc-filter-dropdown.show { display: block; }
.sc-filter-dropdown a {
    display: block;
    padding: 10px 16px;
    font-size: 13px;
    color: #495057;
    text-decoration: none;
    border-bottom: 1px solid #f8f9fa;
    transition: background .12s;
}
.sc-filter-dropdown a:last-child { border-bottom: none; }
.sc-filter-dropdown a:hover, .sc-filter-dropdown a.active { background: #f0f3ff; color: #511D99; font-weight: 600; }
.sc-filter-group-label {
    padding: 8px 16px 4px;
    font-size: 11px;
    font-weight: 700;
    color: #adb5bd;
    text-transform: uppercase;
    letter-spacing: .05em;
    pointer-events: none;
}

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


.btn-1 {
    border-radius: 12px;
    background: #511D99;
    color: #fff;
    border: 1.5px solid #511D99;
    transition: background .15s, color .15s, border-color .15s;
}
.btn-1:hover {
    background: #fff;
    color: #511D99;
    border-color: #511D99;
}
</style>
@endpush

@section('content')

{{-- ── Page header ────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-start justify-content-between mb-4">
    <div>
        <h2 class="font-20 font-weight-bold mb-4">{{ trans('update.my_students') }}</h2>
        <p class="font-13 text-gray-500 mb-0">Xem tóm tắt quá trình học tập, hoạt động luyện tập và tiến độ của học sinh.</p>
    </div>
    <div class="d-flex align-items-center gap-8">
        <a href="/panel/my-students" class="btn btn-sm rounded-12 d-none d-md-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;">
            <x-iconsax-lin-arrow-left class="icons mr-1" width="16"/>
            Quay lại
        </a>
        <a href="/panel/my-students/add-student" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;">
            <x-iconsax-lin-add class="icons" width="16"/>
            Thêm học viên
        </a>
    </div>
</div>

{{-- ── Search + Filter row ─────────────────────────────────────────────── --}}
<div class="d-flex align-items-center gap-12 mb-20">
    {{-- Search --}}
    <form method="GET" action="" class="sc-search-wrap" id="searchForm">
        <span class="sc-search-icon">
            <x-iconsax-lin-search-normal-1 width="18" height="18"/>
        </span>
        <input type="text"
               name="search"
               id="scSearchInput"
               placeholder="Tìm kiếm...."
               value="{{ request('search') }}"
               autocomplete="off">
        @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif
    </form>

    {{-- Filter / Sort dropdown --}}
    <div class="sc-filter-wrap">
        <div class="sc-filter-btn" id="scFilterToggle">
            <span id="scFilterLabel">
                @php
                    $sortLabels = [
                        'name_asc'    => 'Tên A → Z',
                        'name_desc'   => 'Tên Z → A',
                        'exam_asc'    => 'Ngày thi (gần nhất)',
                        'exam_desc'   => 'Ngày thi (xa nhất)',
                        'active_desc' => 'Hoạt động gần nhất',
                        'active_asc'  => 'Ít hoạt động nhất',
                    ];
                @endphp
                {{ $sortLabels[request('sort')] ?? 'Bộ lọc' }}
            </span>
            <x-iconsax-lin-arrow-down class="icons ml-4" width="16"/>
        </div>
        <div class="sc-filter-dropdown" id="scFilterDropdown">
            <div class="sc-filter-group-label">Sắp xếp theo tên</div>
            <a href="?search={{ request('search') }}&sort=name_asc"
               class="{{ request('sort') === 'name_asc' ? 'active' : '' }}">
                Tên A → Z
            </a>
            <a href="?search={{ request('search') }}&sort=name_desc"
               class="{{ request('sort') === 'name_desc' ? 'active' : '' }}">
                Tên Z → A
            </a>
            <div class="sc-filter-group-label">Sắp xếp theo ngày thi</div>
            <a href="?search={{ request('search') }}&sort=exam_asc"
               class="{{ request('sort') === 'exam_asc' ? 'active' : '' }}">
                Ngày thi (gần nhất)
            </a>
            <a href="?search={{ request('search') }}&sort=exam_desc"
               class="{{ request('sort') === 'exam_desc' ? 'active' : '' }}">
                Ngày thi (xa nhất)
            </a>
            <div class="sc-filter-group-label">Sắp xếp theo hoạt động</div>
            <a href="?search={{ request('search') }}&sort=active_desc"
               class="{{ request('sort') === 'active_desc' ? 'active' : '' }}">
                Hoạt động gần nhất
            </a>
            <a href="?search={{ request('search') }}&sort=active_asc"
               class="{{ request('sort') === 'active_asc' ? 'active' : '' }}">
                Ít hoạt động nhất
            </a>
            @if(request('sort'))
            <div class="sc-filter-group-label" style="border-top:1px solid #f0f0f0;margin-top:4px;"></div>
            <a href="?search={{ request('search') }}" style="color:#e74c3c;">
                <x-iconsax-lin-close-circle class="icons mr-1" width="14"/> Xoá bộ lọc
            </a>
            @endif
        </div>
    </div>
</div>

{{-- ── Student card grid ───────────────────────────────────────────────── --}}
@if(!empty($students) && !$students->isEmpty())

<div class="sc-grid">
    @foreach($students as $student)
    @php
        // Avatar – student is a User model instance so getAvatar() is available
        $avatarSrc = $student->getAvatar(48);

        // Last-active: prefer the latest IELTS attempt date (unix int); fall back to users.updated_at
        $lastActiveDays = null;
        $lastActiveTs = null;
        if (!empty($student->last_activity_at)) {
            $lastActiveTs = (int) $student->last_activity_at;
        } elseif (!empty($student->updated_at)) {
            $lastActiveTs = (int) $student->updated_at;
        }
        if ($lastActiveTs && $lastActiveTs > 0) {
            $lastActiveDays = (int) floor((time() - $lastActiveTs) / 86400);
        }

        // Exam date formatted
        $examDateFormatted = null;
        if (!empty($student->exam_date) && $student->exam_date !== '0000-00-00') {
            try {
                $examDateFormatted = \Carbon\Carbon::parse($student->exam_date)->format('d/m/Y');
            } catch (\Exception $e) { $examDateFormatted = null; }
        }

        // Progress %
        $progress = (float) ($student->learning ?? 0);

        // Message link: opens an existing conversation or creates one if needed
        $msgUrl  = '/panel/my-students/' . $student->id . '/message?webinar_id=' . (int) ($student->webinar_id ?? 0);
        $activityUrl = '/panel/students-tracking/' . $student->id . '/activity';
        $viewUrl = '/panel/students-tracking/' . $student->id . '/details';
    @endphp
    <div class="sc-card">

        {{-- Header: avatar + name --}}
        <div class="sc-card__header">
            <img src="{{ $avatarSrc }}"
                 class="sc-card__avatar"
                 alt="{{ $student->full_name }}"
                 onerror="this.src='/assets/default/img/user/avatar_default.png'">
            <div style="min-width:0">
                <p class="sc-card__name">{{ $student->full_name ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Band + Exam date (each item on its own line) --}}
        <div class="sc-card__meta">
            <div class="sc-meta-item">
                <span class="label">Estimated Band:</span>
                <span class="value band" style="color: #511D99;">{{ $student->estimated_band ?? '—' }}</span>
            </div>
            <div class="sc-meta-item">
                <span class="label">Aim band:</span>
                <span class="value band" style="color: #511D99;">{{ $student->aim_band ?? '—' }}</span>
            </div>
            <div class="sc-meta-item">
                <span class="label">Ngày thi:</span>
                <span class="value" style="color: #511D99;">{{ $examDateFormatted ?? '—' }}</span>
            </div>
        </div>

        {{-- Course (label + name inline) --}}
        <div class="sc-card__course-row">
            <span class="sc-card__course-label">Khóa học:</span>
            <span class="sc-card__course" style="color: #511D99;" title="{{ $student->course_title ?? '' }}">{{ $student->course_title ?? 'Chưa đăng ký khóa nào' }}</span>
        </div>

        {{-- Progress bar --}}
        <div class="sc-card__progress">
            <div class="progress">
                <div class="progress-bar"
                     role="progressbar"
                     style="width: {{ min(100, max(0, $progress)) }}%"
                     aria-valuenow="{{ $progress }}"
                     aria-valuemin="0"
                     aria-valuemax="100"
                     style="color: #511D99;"></div>
            </div>
        </div>

        {{-- Footer: last active + actions --}}
        <div class="sc-card__footer">
            <span class="sc-last-active">
                @if($lastActiveDays !== null)
                    Last Active:
                    @if($lastActiveDays === 0) Today
                    @elseif($lastActiveDays === 1) Yesterday
                    @else {{ $lastActiveDays }} days ago
                    @endif
                @else
                    &mdash;
                @endif
            </span>
            <div class="sc-actions">
                {{-- Message --}}
                <a href="{{ $msgUrl }}" class="sc-btn" title="Nhắn tin hỗ trợ">
                    <x-iconsax-lin-message-text class="icons" width="22"/>
                </a>
                {{-- Activity timeline --}}
                <a href="{{ $activityUrl }}" class="sc-btn" title="Xem toàn bộ hoạt động">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" aria-hidden="true">
                        <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                </a>
                {{-- View details --}}
                <a href="{{ $viewUrl }}" class="sc-btn" title="Xem chi tiết">
                    <x-iconsax-lin-eye class="icons" width="22"/>
                </a>
            </div>
        </div>

    </div>
    @endforeach
</div>

{{-- Pagination --}}
<div class="mt-20 text-center">
    {{ $students->appends(request()->input())->links() }}
</div>

@else
<div class="mt-20">
    @include('design_1.panel.includes.no-result',[
        'file_name' => 'students.svg',
        'title' => trans('panel.students_no_result'),
        'hint' => trans('panel.no_students_enrolled_hint'),
    ])
</div>
@endif

@endsection

@push('scripts_bottom')
<script>
(function() {
    // Filter dropdown toggle
    var toggle   = document.getElementById('scFilterToggle');
    var dropdown = document.getElementById('scFilterDropdown');
    if (toggle && dropdown) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
        document.addEventListener('click', function() {
            dropdown.classList.remove('show');
        });
    }

    // Live search (debounced submit)
    var searchInput = document.getElementById('scSearchInput');
    var searchForm  = document.getElementById('searchForm');
    if (searchInput && searchForm) {
        var timer;
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() { searchForm.submit(); }, 500);
        });
    }
})();
</script>
@endpush
