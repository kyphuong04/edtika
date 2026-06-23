@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .bundle-vocab-scroll {
        max-height: min(62vh, 620px);
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
        <a href="{{ url('/panel/dictionary/bundle-vocabulary/manage') }}" class="btn btn-sm btn-outline-secondary mb-16">{{ trans('panel.bundle_vocabulary_back_to_list') }}</a>
    </div>

    <div class="col-12 col-lg-5">
        <div class="bg-white rounded-12 p-16 mb-20">
            <h4 class="font-16 mb-12">{{ trans('panel.bundle_vocabulary_set_detail') }} #{{ $set->id }}</h4>

            <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id.'/update') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_bundle') }}</label>
                    <input type="text" class="form-control" value="{{ !empty($set->bundle) ? $set->bundle->slug : '-' }}" readonly>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_set_name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $set->name) }}" required>
                    @error('name')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('public.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $set->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('panel.bundle_vocabulary_source_file') }}</label>
                    <input type="file" name="source_file" class="form-control" accept=".csv,.txt,.xls,.xlsx">
                    <p class="font-12 text-gray-500 mt-8 mb-0">{{ trans('panel.bundle_vocabulary_file_template_hint') }}</p>
                    @error('source_file')
                        <div class="text-danger mt-4">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ trans('panel.bundle_vocabulary_update') }}</button>
            </form>

            <hr>

            <div class="d-flex align-items-center flex-wrap gap-8">
                @if($set->status !== 'approved')
                    <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id.'/submit') }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-warning">{{ trans('panel.bundle_vocabulary_submit_for_approval') }}</button>
                    </form>
                @endif

                @if($canApprove)
                    <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id.'/approve') }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success">{{ trans('panel.bundle_vocabulary_approve') }}</button>
                    </form>

                    <form method="post" action="{{ url('/panel/dictionary/bundle-vocabulary/'.$set->id.'/reject') }}" class="w-100 mt-8">
                        {{ csrf_field() }}
                        <label class="input-label">{{ trans('panel.bundle_vocabulary_rejection_note') }}</label>
                        <textarea name="rejection_note" class="form-control" rows="2" required>{{ old('rejection_note') }}</textarea>
                        <button type="submit" class="btn btn-danger mt-8">{{ trans('panel.bundle_vocabulary_reject') }}</button>
                    </form>
                @endif
            </div>

            @if(!empty($set->rejection_note))
                <div class="alert alert-danger mt-16 mb-0">{{ $set->rejection_note }}</div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="bg-white rounded-12 p-16">
            <h4 class="font-16 mb-12">{{ trans('panel.bundle_vocabulary_words_count') }}: {{ $set->words_count }}</h4>
            <div class="table-responsive bundle-vocab-scroll">
                <table class="table custom-table">
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
@endsection
