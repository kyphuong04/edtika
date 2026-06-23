@extends('admin.layouts.app')

@push('styles_top')
<style>
    .btn-manager-purple {
        background-color: #511D99;
        border-color: #511D99;
        color: #fff !important;
    }

    .btn-manager-purple:hover,
    .btn-manager-purple:focus,
    .btn-manager-purple:active {
        background-color: #42187f !important;
        border-color: #42187f !important;
        color: #fff !important;
    }

    .bundle-vocab-admin-scroll {
        max-height: min(64vh, 680px);
        overflow-y: auto;
        overflow-x: auto;
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: 12px;
    }

    .bundle-vocab-admin-scroll table {
        background: #fff;
    }

    .bundle-vocab-admin-scroll thead th {
        position: sticky;
        top: 0;
        background: #fff !important;
        z-index: 3;
        box-shadow: 0 1px 0 #e9ecef;
    }

    .bundle-vocab-admin-scroll tbody td {
        background: #fff;
    }

    .bundle-vocab-admin-scroll::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .bundle-vocab-admin-scroll::-webkit-scrollbar-thumb {
        background: rgba(81, 29, 153, 0.35);
        border-radius: 10px;
    }

    .bundle-vocab-admin-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(81, 29, 153, 0.5);
    }

    @media (max-width: 991px) {
        .bundle-vocab-admin-scroll {
            max-height: 48vh;
        }
    }

    .bundle-vocab-action-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .bundle-vocab-pagination .pagination {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .bundle-vocab-pagination .page-item {
        display: inline-flex;
        margin: 0;
    }

    .bundle-vocab-pagination .page-link {
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(81, 29, 153, 0.2);
        color: #511D99;
        background: #fff;
        text-decoration: none;
    }

    .bundle-vocab-pagination .page-item.active .page-link {
        background: #511D99;
        border-color: #511D99;
        color: #fff;
    }

    .bundle-vocab-pagination .page-item.disabled .page-link {
        opacity: 0.45;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark mb-4">{{ trans('panel.bundle_vocabulary_set_detail') }} #{{ $set->id }}</h1>
            <p class="text-gray-500 mb-0">Review, edit, then approve or reject this vocabulary set from the admin workspace.</p>
        </div>
        <a href="{{ getAdminPanelUrl() }}/bundle-vocabulary{{ request()->get('status') ? '?status='.request()->get('status') : '' }}" class="btn btn-manager-purple">{{ trans('panel.bundle_vocabulary_back_to_list') }}</a>
    </div>

    <div class="row">
        <div class="col-12 col-lg-5">
            <div class="bg-white rounded-16 shadow-sm p-20 mb-24" style="border-radius: 12px;">
                <form id="updateVocabularySetForm" method="post" action="{{ getAdminPanelUrl() }}/bundle-vocabulary/{{ $set->id }}/update" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="font-13 font-weight-bold text-dark">{{ trans('panel.bundle_vocabulary_bundle') }}</label>
                        <input type="text" class="form-control" value="{{ !empty($set->bundle) ? $set->bundle->slug : '-' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-13 font-weight-bold text-dark">{{ trans('panel.bundle_vocabulary_status') }}</label>
                        <input type="text" class="form-control" value="{{ ucfirst($set->status) }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-13 font-weight-bold text-dark">{{ trans('panel.bundle_vocabulary_set_name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $set->name) }}" required>
                        @error('name')<div class="text-danger mt-4">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="font-13 font-weight-bold text-dark">{{ trans('public.description') }}</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $set->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-13 font-weight-bold text-dark">{{ trans('panel.bundle_vocabulary_source_file') }}</label>
                        <input type="file" name="source_file" class="form-control" accept=".csv,.txt,.xls,.xlsx">
                        <p class="font-12 text-gray-500 mt-8 mb-0">{{ trans('panel.bundle_vocabulary_file_template_hint') }}</p>
                        @error('source_file')<div class="text-danger mt-4">{{ $message }}</div>@enderror
                    </div>

                </form>

                <div class="bundle-vocab-action-row mt-12">
                    <button type="submit" form="updateVocabularySetForm" class="btn btn-manager-purple">{{ trans('panel.bundle_vocabulary_update') }}</button>

                    @if($set->status !== 'approved')
                        <form method="post" action="{{ getAdminPanelUrl() }}/bundle-vocabulary/{{ $set->id }}/approve" class="d-inline">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-manager-purple">{{ trans('panel.bundle_vocabulary_approve') }}</button>
                        </form>
                    @endif

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectVocabularySetModal">{{ trans('panel.bundle_vocabulary_reject') }}</button>
                </div>

                @if(!empty($set->rejection_note))
                    <div class="alert alert-danger mt-16 mb-0">{{ $set->rejection_note }}</div>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="bg-white rounded-16 shadow-sm p-20" style="border-radius: 12px;">
                <h4 class="font-16 font-weight-bold mb-16">{{ trans('panel.bundle_vocabulary_words_count') }}: {{ $set->words_count }}</h4>
                <div class="table-responsive bundle-vocab-admin-scroll">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Word</th>
                                <th>POS</th>
                                <th>{{ trans('panel.definition') }}</th>
                                <th>{{ trans('panel.translation') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($words as $word)
                                <tr>
                                    <td>{{ $word->sort_order }}</td>
                                    <td>{{ $word->word }}</td>
                                    <td>{{ $word->part_of_speech }}</td>
                                    <td>{{ $word->definition }}</td>
                                    <td>{{ $word->translation_vi }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500">{{ trans('public.no_result') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-16 bundle-vocab-pagination">{{ $words->appends(request()->all())->links() }}</div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectVocabularySetModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>{{ trans('panel.bundle_vocabulary_reject') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                </div>
                <form action="{{ getAdminPanelUrl() }}/bundle-vocabulary/{{ $set->id }}/reject" method="POST">
                    @csrf
                    <div class="modal-body" style="padding: 24px;">
                        <label class="font-13 font-weight-bold text-dark">{{ trans('panel.bundle_vocabulary_rejection_note') }}</label>
                        <textarea name="rejection_note" class="form-control" rows="4" required>{{ old('rejection_note') }}</textarea>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 16px 24px;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ trans('panel.bundle_vocabulary_reject') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection