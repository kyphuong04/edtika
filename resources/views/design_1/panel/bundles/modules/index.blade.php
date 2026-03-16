@extends('design_1.panel.layouts.panel')

@push("styles_top")
<style>
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
        background-color: #f3f4f6;
        color: #374151;
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
        background-color: #1f2937;
        color: #ffffff;
    }

    /* ─── Modules Container ──────────────────────────────── */
    .modules-container {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }

    .modules-list {
        background-color: #f3f4f6;
        border-radius: 12px;
        padding: 16px;
    }

    .modules-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .modules-header__title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }

    .modules-header__subtitle {
        font-size: 13px;
        color: #6b7280;
    }

    .modules-header__btn {
        padding: 10px 22px;
        background-color: #374151;
        color: #ffffff !important;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none !important;
        white-space: nowrap;
        transition: background-color .2s;
    }

    .modules-header__btn:hover {
        background-color: #1f2937;
        color: #ffffff !important;
    }

    /* ─── Module Row ─────────────────────────────────────── */
    .module-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #ffffff;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 14px 20px;
        margin-bottom: 10px;
        font-weight: 600;
        font-size: 14px;
        color: #111827;
        transition: box-shadow .15s;
    }

    .module-row:last-child {
        margin-bottom: 0;
    }

    .module-row:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
    }

    .module-row__actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .module-row__action-link {
        font-size: 12px;
        font-weight: 500;
        color: #6b7280;
        text-decoration: none;
        padding: 4px 10px;
        border-radius: 6px;
        transition: background-color .15s, color .15s;
    }

    .module-row__action-link:hover {
        background-color: #f3f4f6;
        color: #111827;
    }

    /* ─── Back Link ──────────────────────────────────────── */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #6b7280;
        text-decoration: none;
        margin-bottom: 16px;
        transition: color .15s;
    }

    .back-link:hover {
        color: #111827;
        text-decoration: none;
    }
</style>
@endpush

@section('content')

    {{-- Tab Navigation --}}
    <div class="materials-tabs">
        <a href="/panel/bundles" class="tab-btn active">Curriculum</a>
        <a href="/panel/courses" class="tab-btn">My Curriculum</a>
        <a href="/panel/vocab-coming-soon" class="tab-btn">Vocab &amp; Dictionary</a>
    </div>

    {{-- Back to bundles list --}}
    <a href="/panel/bundles" class="back-link">
        &#8592; Quay lại
    </a>

    {{-- Modules container --}}
    <div class="modules-container">

        {{-- Header: title + create button --}}
        <div class="modules-header">
            <div>
                <div class="modules-header__title">{{ $bundle->title }}</div>
                <div class="modules-header__subtitle">
                    Tổng số lượng bài học:
                    <strong>{{ $totalLessons }} bài</strong>
                </div>
            </div>
            <a href="/panel/bundles/{{ $bundle->id }}/module/create" class="modules-header__btn">
                Create New Module
            </a>
        </div>

        {{-- Module rows --}}
        <div class="modules-list">
            @if($bundle->bundleWebinars->isNotEmpty())
                @foreach($bundle->bundleWebinars as $bw)
                    @if($bw->webinar)
                        <div class="module-row">
                            <span>{{ strtoupper($bw->webinar->title) }}</span>
                            <div class="module-row__actions">
                                <a href="/panel/bundles/{{ $bundle->id }}/module/{{ $bw->webinar->id }}/edit"
                                   class="module-row__action-link">
                                    {{ trans('public.edit') }}
                                </a>
                                <a href="/panel/bundles/{{ $bundle->id }}/module/{{ $bw->webinar->id }}/delete"
                                   class="module-row__action-link text-danger delete-action">
                                    {{ trans('public.delete') }}
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center py-40" style="color:#9ca3af; font-size:14px;">
                    {{ trans('update.no_result_bundle_hint') }}
                </div>
            @endif
        </div>

    </div>

@endsection
