@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Yêu cầu tư vấn</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
            <div class="breadcrumb-item">Yêu cầu tư vấn</div>
        </div>
    </div>

    <div class="section-body">
        @include('admin.placement_consults.partials.list', ['pcModalStack' => 'pc_modals'])
    </div>
</section>

@stack('pc_modals')
@endsection
