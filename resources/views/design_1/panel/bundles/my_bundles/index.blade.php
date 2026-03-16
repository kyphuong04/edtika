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

    /* ─── Bundle Card ────────────────────────────────────── */
    .materials-bundle-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: box-shadow .2s;
    }
    .materials-bundle-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,.10);
    }
    .materials-bundle-card__title {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        line-height: 1.4;
        margin-bottom: 4px;
        padding-right: 0;
    }
    .materials-bundle-card__subtitle {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 18px;
    }
    .materials-bundle-card__stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 24px;
        width: 100%;
    }
    .materials-bundle-card__stat {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 13px;
        color: #6b7280;
    }
    .materials-bundle-card__stat .stat-val {
        font-weight: 600;
        color: #111827;
    }
    .materials-bundle-card__price {
        font-size: 16px;
        font-weight: 700;
    }
    .materials-bundle-card__actions {
        position: absolute;
        top: 16px;
        right: 16px;
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

    {{-- Lists --}}
    @if(!empty($bundles) and !$bundles->isEmpty())
        <div id="tableListContainer" data-view-data-path="/panel/bundles">
            <div class="js-page-bundles-lists row">
                @foreach($bundles as $bundleItem)
                    <div class="col-12 col-md-6 col-lg-4 mt-20">
                        @include("design_1.panel.bundles.my_bundles.grid_card", ['bundle' => $bundleItem])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                 data-container-items=".js-page-bundles-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result', [
            'file_name' => 'bundles.svg',
            'title'     => trans('update.you_not_have_any_bundle'),
            'hint'      => trans('update.no_result_bundle_hint'),
            'btn'       => ['url' => '/panel/bundles/new', 'text' => trans('update.create_a_bundle')],
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
