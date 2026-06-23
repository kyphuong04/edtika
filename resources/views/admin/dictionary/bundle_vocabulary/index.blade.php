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
</style>
@endpush

@section('content')
<section class="section">
    <div class="bg-white rounded-16 shadow-sm p-24 mb-24" style="border-radius: 12px;">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center">
                <div class="rounded-12 p-12 mr-16" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-language text-white" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h1 class="font-20 font-weight-bold text-dark mb-4">{{ trans('panel.bundle_vocabulary_library') }}</h1>
                    <p class="text-gray-500 font-13 mb-0">Manager/CEO can review, edit, approve or reject submitted bundle vocabulary sets.</p>
                </div>
            </div>
            <div>
                <span class="badge badge-warning" style="padding: 10px 20px; font-size: 14px; border-radius: 20px; font-weight: 600;">
                    {{ trans('update.pending_count', ['count' => $pendingCount]) }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-16 shadow-sm p-20 mb-24" style="border-radius: 12px;">
        <form method="GET" class="m-0">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">{{ trans('panel.bundle_vocabulary_status') }}</label>
                    <select name="status" class="form-control">
                        <option value="">{{ trans('panel.bundle_vocabulary_all_statuses') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ trans('panel.bundle_vocabulary_pending_only') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">{{ trans('update.search') }}</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by set name, bundle slug, creator or description">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-manager-purple w-100">{{ trans('public.filter') }}</button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-16 shadow-sm overflow-hidden" style="border-radius: 12px;">
        <div class="px-20 py-16 border-bottom d-flex align-items-center justify-content-between">
            <div class="font-15 font-weight-bold text-dark">
                <i class="fas fa-books mr-8 text-primary"></i>{{ trans('public.list') }}
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="{{ getAdminPanelUrl() }}/bundle-vocabulary" class="btn btn-sm btn-manager-purple">{{ trans('panel.bundle_vocabulary_all_statuses') }}</a>
                <a href="{{ getAdminPanelUrl() }}/bundle-vocabulary?status=pending" class="btn btn-sm btn-warning">{{ trans('panel.bundle_vocabulary_pending_only') }}</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                        <th class="font-11 text-gray-600 text-uppercase py-16 px-20 font-weight-bold border-0">#</th>
                        <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0">{{ trans('panel.bundle_vocabulary_set_name') }}</th>
                        <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0">{{ trans('panel.bundle_vocabulary_bundle') }}</th>
                        <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('panel.bundle_vocabulary_status') }}</th>
                        <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('panel.bundle_vocabulary_words_count') }}</th>
                        <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('panel.bundle_vocabulary_created_by') }}</th>
                        <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">{{ trans('panel.bundle_vocabulary_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sets as $set)
                        <tr>
                            <td class="py-16 px-20">{{ $set->id }}</td>
                            <td class="py-16">
                                <div class="font-weight-bold text-dark">{{ $set->name }}</div>
                                @if(!empty($set->description))
                                    <div class="font-12 text-gray-500 mt-4">{{ \Illuminate\Support\Str::limit($set->description, 90) }}</div>
                                @endif
                            </td>
                            <td class="py-16">{{ !empty($set->bundle) ? $set->bundle->slug : '-' }}</td>
                            <td class="text-center py-16">
                                <span class="badge {{ $set->status === 'approved' ? 'badge-success' : ($set->status === 'pending' ? 'badge-warning' : ($set->status === 'rejected' ? 'badge-danger' : 'badge-secondary')) }}">{{ ucfirst($set->status) }}</span>
                            </td>
                            <td class="text-center py-16">{{ $set->words_count }}</td>
                            <td class="text-center py-16">{{ !empty($set->creator) ? $set->creator->full_name : '-' }}</td>
                            <td class="text-center py-16">
                                <a href="{{ getAdminPanelUrl() }}/bundle-vocabulary/{{ $set->id }}" class="btn btn-sm btn-manager-purple">{{ trans('public.edit') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-32 text-gray-500">{{ trans('public.no_result') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sets->hasPages())
            <div class="p-3" style="border-top: 1px solid #f0f0f0;">
                {{ $sets->appends(request()->all())->links() }}
            </div>
        @endif
    </div>
</section>
@endsection