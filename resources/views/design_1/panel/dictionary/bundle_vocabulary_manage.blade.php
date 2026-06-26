@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .bundle-vocab-scroll {
        max-height: min(58vh, 560px);
        overflow-y: auto;
        overflow-x: auto;
    }

    .bundle-vocab-scroll::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .bundle-vocab-scroll::-webkit-scrollbar-thumb {
        background: rgba(81, 29, 153, 0.35);
        border-radius: 10px;
    }

    .bundle-vocab-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(81, 29, 153, 0.5);
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
        border: 1px solid rgba(81, 29, 153, 0.18);
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
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-20">
            <div>
                <h2 class="section-title mb-4">{{ trans('panel.bundle_vocabulary_library') }}</h2>
                <p class="text-gray-500 mb-0">{{ trans('panel.bundle_vocabulary_manage_hint') }}</p>
            </div>
            @if($canApprove)
                <div class="d-flex align-items-center flex-wrap gap-8">
                    <a href="{{ url('/panel/dictionary/bundle-vocabulary/manage') }}" class="btn btn-sm {{ request()->get('status') ? 'btn-outline-primary' : 'btn-primary' }}">{{ trans('panel.bundle_vocabulary_all_statuses') }}</a>
                    <a href="{{ url('/panel/dictionary/bundle-vocabulary/manage?status=pending') }}" class="btn btn-sm {{ request()->get('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">{{ trans('panel.bundle_vocabulary_pending_only') }}</a>

                    <form method="get" action="{{ url('/panel/dictionary/bundle-vocabulary/manage') }}" class="d-flex align-items-center gap-8">
                    <select name="status" class="form-control">
                        <option value="">{{ trans('panel.bundle_vocabulary_all_statuses') }}</option>
                        <option value="pending" {{ request()->get('status') === 'pending' ? 'selected' : '' }}>{{ trans('panel.bundle_vocabulary_pending_only') }}</option>
                        <option value="approved" {{ request()->get('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request()->get('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="draft" {{ request()->get('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    <button class="btn btn-primary" type="submit">{{ trans('public.filter') }}</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="bg-white rounded-12 p-16 mb-20">
            <h4 class="font-16 mb-12">{{ trans('panel.bundle_vocabulary_create_new') }}</h4>
            <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_bundle') }}</label>
                    <select name="bundle_id" class="form-control" required>
                        <option value="">Select bundle</option>
                        @foreach($availableBundles as $bundle)
                            <option value="{{ $bundle->id }}" {{ old('bundle_id') == $bundle->id ? 'selected' : '' }}>#{{ $bundle->id }} - {{ $bundle->slug }}</option>
                        @endforeach
                    </select>
                    @error('bundle_id')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_set_name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('public.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_intro_content') }}</label>
                    <textarea name="intro_content" class="form-control" rows="4" placeholder="{{ trans('panel.bundle_vocabulary_intro_content_placeholder') }}">{{ old('intro_content') }}</textarea>
                    @error('intro_content')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_feature_content') }}</label>
                    <textarea name="feature_content" class="form-control" rows="4" placeholder="{{ trans('panel.bundle_vocabulary_feature_content_placeholder') }}">{{ old('feature_content') }}</textarea>
                    @error('feature_content')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_source_file') }}</label>
                    <input type="file" name="source_file" class="form-control" accept=".csv,.txt,.xls,.xlsx" required>
                    <p class="font-12 text-gray-500 mt-8 mb-0">{{ trans('panel.bundle_vocabulary_file_template_hint') }}</p>
                    @error('source_file')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ trans('public.save') }}</button>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="bg-white rounded-12 p-16">
            <h4 class="font-16 mb-12">{{ trans('public.list') }}</h4>

            <div class="table-responsive bundle-vocab-scroll">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('panel.bundle_vocabulary_set_name') }}</th>
                            <th>{{ trans('panel.bundle_vocabulary_bundle') }}</th>
                            <th>{{ trans('panel.bundle_vocabulary_status') }}</th>
                            <th>{{ trans('panel.bundle_vocabulary_words_count') }}</th>
                            <th>{{ trans('panel.bundle_vocabulary_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sets as $set)
                            <tr>
                                <td>{{ $set->id }}</td>
                                <td>
                                    <a href="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id) }}" class="text-dark">{{ $set->name }}</a>
                                    <div class="text-gray-500 font-12">{{ !empty($set->creator) ? $set->creator->full_name : '-' }}</div>
                                </td>
                                <td>{{ !empty($set->bundle) ? $set->bundle->slug : '-' }}</td>
                                <td>
                                    <span class="badge {{ $set->status === 'approved' ? 'badge-success' : ($set->status === 'pending' ? 'badge-warning' : ($set->status === 'rejected' ? 'badge-danger' : 'badge-secondary')) }}">
                                        {{ ucfirst($set->status) }}
                                    </span>
                                </td>
                                <td>{{ $set->words_count }}</td>
                                <td>
                                    <a href="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id) }}" class="btn btn-sm btn-outline-primary">{{ trans('public.edit') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500">{{ trans('public.no_result') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-16 bundle-vocab-pagination">{{ $sets->appends(request()->all())->links() }}</div>
        </div>
    </div>
</div>
@endsection
