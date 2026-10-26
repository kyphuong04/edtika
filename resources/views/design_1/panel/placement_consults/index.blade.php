@extends('design_1.panel.layouts.panel')

{{-- Trang "Yêu cầu tư vấn" cho role Admin (/panel/placement-consults).
     Nội dung dùng chung với bản /admin của Manager/CEO. --}}

@section('content')
    <div class="mb-24">
        <h2 class="section-title mb-4">Yêu cầu tư vấn</h2>
        <p class="text-gray-500 mb-0">Thông tin học viên để lại ở trang kết quả Placement Test để được tư vấn lộ trình học.</p>
    </div>

    @include('admin.placement_consults.partials.list', ['pcModalStack' => 'panel_modals'])
@endsection
