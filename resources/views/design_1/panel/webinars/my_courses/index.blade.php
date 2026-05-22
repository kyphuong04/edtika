@extends('design_1.panel.layouts.panel')

@push("styles_top")
<style>
    .curriculum-page {
        --primary: #511D99;
        --primary-hover: #451884;
    }
    .curriculum-page .text-primary,
    .curriculum-page .text-primary:hover,
    .curriculum-page .text-primary:focus {
        color: #511D99 !important;
    }
    .curriculum-page .bg-primary,
    .curriculum-page .btn-primary,
    .curriculum-page .btn-primary:hover,
    .curriculum-page .btn-primary:focus,
    .curriculum-page .btn-primary:active,
    .curriculum-page .btn-primary:not(:disabled):not(.disabled):active {
        background-color: #511D99 !important;
        border-color: #511D99 !important;
        color: #fff !important;
    }
    .curriculum-page .btn-outline-primary,
    .curriculum-page .btn-outline-primary:hover,
    .curriculum-page .btn-outline-primary:focus,
    .curriculum-page .btn-outline-primary:active {
        border-color: #511D99 !important;
        color: #511D99 !important;
    }
    .curriculum-page .btn-outline-primary:hover,
    .curriculum-page .btn-outline-primary:focus,
    .curriculum-page .btn-outline-primary:active {
        background-color: rgba(81, 29, 153, 0.1) !important;
    }
    .curriculum-page .border-primary {
        border-color: #511D99 !important;
    }

    /* ─── Tab Navigation ─────────────────────────────────── */
    .materials-tabs {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 28px;
        width: 100%;
    }
    .materials-tabs .tab-btn {
        flex: 1;
        text-align: center;
        padding: 11px 16px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        background-color: #ffff;
        color: #511D99;
        border: 1.5px solid transparent;
        transition: background-color .2s, color .2s;
        white-space: nowrap;
    }
    .materials-tabs .tab-btn:hover {
        background-color: #e5e7eb;
        color: #111827;
        text-decoration: none;
    }
    .materials-tabs .tab-btn.active {
        background-color: #511D99;
        color: #ffffff;
    }

    /* ─── Section Container ──────────────────────────────── */
    .curriculum-section {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        margin-bottom: 24px;
    }

    .curriculum-section__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .curriculum-section__title {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }

    .curriculum-section__subtitle {
        font-size: 13px;
        color: #6b7280;
    }

    .curriculum-section__btn {
        padding: 8px 22px;
        background-color: #511D99;
        color: #ffffff !important;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none !important;
        white-space: nowrap;
        flex-shrink: 0;
        transition: background-color .2s;
    }
    .curriculum-section__btn:hover {
        background-color: #1f2937;
        color: #fff !important;
    }

    /* ─── Grid ───────────────────────────────────────────── */
    .curriculum-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        max-height: 420px;
        overflow-y: auto;
        padding-right: 4px;
    }

    @media (max-width: 992px) {
        .curriculum-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .curriculum-grid { grid-template-columns: 1fr; }
    }

    /* ─── Course Card ────────────────────────────────────── */
    .curriculum-card {
        background: #ffffff;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 140px;
        transition: box-shadow .15s, border-color .15s;
        text-decoration: none !important;
        color: inherit !important;
    }

    .curriculum-card:hover {
        box-shadow: 0 3px 12px rgba(0,0,0,.09);
        border-color: #d1d5db;
        text-decoration: none !important;
    }

    .curriculum-card__title {
        font-size: 13px;
        font-weight: 600;
        color: #111827;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .curriculum-card__stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px 12px;
        font-size: 11px;
        color: #6b7280;
    }

    .curriculum-card__stat {
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        overflow: hidden;
    }

    .curriculum-card__stat svg {
        flex-shrink: 0;
        opacity: .55;
    }

    .curriculum-card__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: auto;
        padding-top: 10px;
        border-top: 1px solid #f3f4f6;
    }

    .curriculum-card__status {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50px;
    }
    .status--draft    { background: #e5e7eb; color: #6b7280; }
    .status--pending  { background: #fef3c7; color: #92400e; }
    .status--active   { background: #d1fae5; color: #065f46; }

    .curriculum-card__price {
        font-size: 12px;
        font-weight: 700;
        color: #1f2937;
    }

    /* ─── Empty State ────────────────────────────────────── */
    .curriculum-empty {
        text-align: center;
        color: #9ca3af;
        font-size: 13px;
        padding: 32px 0;
        grid-column: 1/-1;
    }
</style>
@endpush

@section('content')
<div class="curriculum-page">

    {{-- Tab Navigation --}}
    <div class="materials-tabs">
        <a href="/panel/bundles" class="tab-btn">Curriculum</a>
        <a href="/panel/courses" class="tab-btn active">My Curriculum</a>
        <a href="/panel/vocab-coming-soon" class="tab-btn">Vocab &amp; Dictionary</a>
    </div>

    {{-- ── My Curriculums Section ──────────────────────── --}}
    <div class="curriculum-section">
        <div class="curriculum-section__header">
            <div>
                <div class="curriculum-section__title">My curriculums</div>
                <div class="curriculum-section__subtitle">
                    Tổng số lượng bài học: <strong>{{ $myTotalLessons }} bài</strong>
                </div>
            </div>
            <button type="button" id="createCurriculumBtn" class="curriculum-section__btn">Tạo</button>
        </div>

        <div class="curriculum-grid">
            @forelse($myCourses as $course)
                @php
                    $lessonCount   = $course->sessions->count() + $course->files->count() + $course->textLessons->count();
                    $studentCount  = $course->purchases->count();
                    $salesCount    = $course->sales->count();
                    $hours         = $course->duration ? round($course->duration / 60, 1) : 0;
                    $statusClass   = match($course->status) {
                        'active'  => 'status--active',
                        'pending' => 'status--pending',
                        default   => 'status--draft',
                    };
                    $statusLabel   = match($course->status) {
                        'active'  => trans('public.active'),
                        'pending' => trans('public.pending'),
                        default   => 'Draft',
                    };
                    $bw = $course->bundleWebinars->first();
                    $cardHref = $bw
                        ? '/panel/bundles/' . $bw->bundle_id . '/module/' . $course->id . '/edit'
                        : '/panel/courses/' . $course->id . '/module-editor';
                @endphp
                <a href="{{ $cardHref }}" class="curriculum-card">
                    <div class="curriculum-card__title">{{ $course->title }}</div>
                    <div class="curriculum-card__stats">
                        <div class="curriculum-card__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            {{ $studentCount }} {{ trans('public.students') }}
                        </div>
                        <div class="curriculum-card__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                            {{ $lessonCount }} {{ trans('public.lessons') }}
                        </div>
                        <div class="curriculum-card__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            {{ $salesCount }} Sales
                        </div>
                        <div class="curriculum-card__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $hours }}h
                        </div>
                    </div>
                    <div class="curriculum-card__footer">
                        <span class="curriculum-card__status {{ $statusClass }}">{{ $statusLabel }}</span>
                        <span class="curriculum-card__price">
                            @if($course->price > 0)
                                {{ handlePrice($course->price, true, true, false, null, true) }}
                            @else
                                {{ trans('public.free') }}
                            @endif
                        </span>
                    </div>
                </a>
            @empty
                <div class="curriculum-empty">{{ trans('panel.you_not_have_any_webinar') }}</div>
            @endforelse
        </div>
    </div>

    {{-- ── Explore Curriculums Section ─────────────────── --}}
    <div class="curriculum-section">
        <div class="curriculum-section__header">
            <div>
                <div class="curriculum-section__title">Explore curriculums</div>
                <div class="curriculum-section__subtitle">
                    Tổng số lượng bài học: <strong>{{ $exploreTotalLessons }} bài</strong>
                </div>
            </div>
        </div>

        <div class="curriculum-grid">
            @forelse($exploreCourses as $course)
                @php
                    $lessonCount  = $course->sessions->count() + $course->files->count() + $course->textLessons->count();
                    $studentCount = $course->purchases->count();
                    $salesCount   = $course->sales->count();
                    $hours        = $course->duration ? round($course->duration / 60, 1) : 0;
                @endphp
                <a href="{{ $course->getUrl() }}" target="_blank" class="curriculum-card">
                    <div class="curriculum-card__title">{{ $course->title }}</div>
                    <div class="curriculum-card__stats">
                        <div class="curriculum-card__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            {{ $studentCount }} {{ trans('public.students') }}
                        </div>
                        <div class="curriculum-card__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                            {{ $lessonCount }} {{ trans('public.lessons') }}
                        </div>
                        <div class="curriculum-card__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            {{ $salesCount }} Sales
                        </div>
                        <div class="curriculum-card__stat">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $hours }}h
                        </div>
                    </div>
                    <div class="curriculum-card__footer">
                        <span class="curriculum-card__price">
                            @if($course->price > 0)
                                {{ handlePrice($course->price, true, true, false, null, true) }}
                            @else
                                {{ trans('public.free') }}
                            @endif
                        </span>
                    </div>
                </a>
            @empty
                <div class="curriculum-empty">{{ trans('panel.you_not_have_any_webinar') }}</div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts_bottom')
<script>
(function () {
    var createBtn = document.getElementById('createCurriculumBtn');
    if (!createBtn) return;

    var bundles = @json($myBundles->map(function ($b) { return ['id' => $b->id, 'title' => $b->title]; }));

    createBtn.addEventListener('click', function () {
        var options = '<option value="">-- Select Curriculum --</option>';
        bundles.forEach(function (b) {
            options += '<option value="' + b.id + '">' + b.title + '</option>';
        });

        var bodyHtml =
            '<div class="d-flex-center flex-column mt-4 mb-24">'
            + '<div class="d-flex-center size-64 rounded-16 bg-primary">'
            + '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="3" width="8" height="8" rx="2" fill="white"/><rect x="13" y="3" width="8" height="8" rx="2" fill="white"/><rect x="3" y="13" width="8" height="8" rx="2" fill="white"/><rect x="13" y="13" width="8" height="8" rx="2" fill="white"/></svg>'
            + '</div>'
            + '<h4 class="font-14 font-weight-bold mt-12">New Module</h4>'
            + '<p class="font-12 text-gray-500 mt-4">Create a new module and add it to a curriculum.</p>'
            + '</div>'
            + '<div class="form-group">'
            + '<label class="form-group-label">Curriculum</label>'
            + '<select id="swalBundleSelect" class="form-control">' + options + '</select>'
            + '<div id="swalBundleError" class="invalid-feedback" style="display:none">Please select a curriculum.</div>'
            + '</div>';

        var footerHtml =
            '<div class="d-flex align-items-center justify-content-end">'
            + '<button type="button" id="swalCreateModuleBtn" class="btn btn-sm btn-primary">Create</button>'
            + '<button type="button" class="close-swl btn btn-sm btn-danger ml-8">Close</button>'
            + '</div>';

        Swal.fire({
            html: makeModalHtml('New Module', closeIcon, bodyHtml, footerHtml),
            showCancelButton: false,
            showConfirmButton: false,
            width: '36rem',
            customClass: { content: 'p-0 text-left' },
        });
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.id === 'swalCreateModuleBtn') {
            var sel = document.getElementById('swalBundleSelect');
            var err = document.getElementById('swalBundleError');
            if (!sel || !sel.value) {
                if (sel) sel.classList.add('is-invalid');
                if (err) err.style.display = 'block';
                return;
            }
            window.location.href = '/panel/bundles/' + sel.value + '/module/create';
        }
    });
})();
</script>
@endpush
