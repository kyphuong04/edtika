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

    .materials-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .materials-page-header__title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .materials-page-header__hint {
        margin: 4px 0 0;
        font-size: 13px;
        color: #6b7280;
    }
    .materials-page-header__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 18px;
        border-radius: 999px;
        background: #511D99;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: transform .2s ease, ease, opacity .2s ease;
    }
    .materials-page-header__button:hover {
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-1px);
        opacity: .96;
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
    .materials-bundle-card__status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .02em;
        text-transform: uppercase;
    }
    .materials-bundle-card__status--pending {
        background: rgba(245, 158, 11, .14);
        color: #b45309;
    }
    .materials-bundle-card__status--draft {
        background: rgba(107, 114, 128, .12);
        color: #4b5563;
    }
    .materials-bundle-card__status--active {
        background: rgba(16, 185, 129, .14);
        color: #047857;
    }
    .materials-bundle-card__status--inactive {
        background: rgba(239, 68, 68, .12);
        color: #b91c1c;
    }
    .materials-bundle-card__status--finished {
        background: rgba(81, 29, 153, .12);
        color: #511D99;
    }
    .materials-bundle-card__status--default {
        background: rgba(99, 102, 241, .12);
        color: #4338ca;
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

    <div class="materials-page-header">
        <div>
            <h3 class="materials-page-header__title">{{ trans('update.my_bundles') }}</h3>
            <p class="materials-page-header__hint">Tạo bundle mới và gửi xét duyệt để Manager/CEO duyệt trước khi hiển thị chính thức.</p>
        </div>

        @can('panel_bundles_create')
            <a href="/panel/bundles/new" class="materials-page-header__button">
                <x-iconsax-lin-add class="icons" width="18px" height="18px"/>
                {{ trans('update.create_a_bundle') }}
            </a>
        @endcan
    </div>

    {{-- Tab Navigation --}}
    <div class="materials-tabs">
        <a href="/panel/bundles" class="tab-btn active">Curriculum</a>
        <a href="/panel/courses" class="tab-btn">My Curriculum</a>
        <a href="{{ auth()->check() && auth()->user()->canManageBundleVocabulary() ? '/panel/dictionary/bundle-vocabulary/manage' : '/panel/dictionary' }}" class="tab-btn">Vocab &amp; Dictionary</a>
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
