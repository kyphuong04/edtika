@extends('design_1.panel.layouts.panel')

@push("styles_top")
<style>
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
</style>
@endpush

@section('content')

    {{-- Tab Navigation --}}
    <div class="materials-tabs">
        <a href="/panel/bundles" class="tab-btn">Curriculum</a>
        <a href="/panel/courses" class="tab-btn">My Curriculum</a>
        <a href="/panel/vocab-coming-soon" class="tab-btn active">Vocab &amp; Dictionary</a>
    </div>

    @include('design_1.panel.includes.no-result', [
        'file_name' => 'upcoming_courses.svg',
        'title'     => 'Vocab & Dictionary — Coming Soon',
        'hint'      => 'Chúng tôi đang xây dựng tính năng này. Hãy quay lại sau nhé!',
    ])

@endsection
