@extends('design_1.panel.layouts.panel')

@section('content')

@push('styles_top')
<style>
.btn {
    border-radius: 12px;
    background: #fff;
    color: #511D99;
    border: 1.5px solid #511D99;
    transition: background .15s, color .15s, border-color .15s;
}

.btn:hover {
    background: #511D99;
    color: #fff;
    border-color: #511D99;
}

.btn-1 {
    border-radius: 12px;
    background: #511D99;
    color: #fff;
    border: 1.5px solid #511D99;
    transition: background .15s, color .15s, border-color .15s;
}
.btn-1:hover {
    background: #fff;
    color: #511D99;
    border-color: #511D99;
}

.form-group input[type="email"].form-control:focus {
    border-color: #511D99 !important;
    box-shadow: 0 0 0 3px rgba(81, 29, 153, 0.12) !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #495057;
    line-height: 42px;
    padding-left: 14px;
    padding-right: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px;
    right: 10px;
}

.select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
    background-color: #511D99 !important;
    color: #fff !important;
}

.select2-container--default .select2-results__option--selected {
    background-color: rgba(81, 29, 153, 0.12) !important;
    color: #511D99 !important;
}

.select2-dropdown {
    border: 1px solid #511D99 !important;
    border-radius: 12px !important;
    overflow: hidden;
}


</style>
@endpush

<div class="d-flex align-items-center justify-content-between mb-20">
    <div>
        <h2 class="font-20 font-weight-bold mb-4">Thêm Học viên</h2>
        <p class="font-13 text-gray-500 mb-0">Thêm học viên vào một trong các khóa học của bạn.</p>
    </div>
    <a href="/panel/my-students/list" class="btn btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;">
        <x-iconsax-lin-arrow-left class="icons mr-1" width="16"/>
        Quay lại
    </a>
</div>

<div class="bg-white rounded-24 p-24" style="max-width:520px;">

    @if(session('add_student_error'))
        <div class="alert alert-danger rounded-12">{{ session('add_student_error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger rounded-12">
            <ul class="mb-0 pl-16">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/panel/my-students/add-student">
        @csrf

        <div class="form-group mt-0">
            <label class="form-group-label">Email học viên <span class="text-danger">*</span></label>
            <input type="email"
                   name="email"
                   class="form-control"
                   placeholder="example@email.com"
                   value="{{ old('email') }}"
                   required>
            <small class="text-gray-400">Nhập email của học viên đã đăng ký tài khoản trên hệ thống.</small>
        </div>

        <div class="form-group">
            <label class="form-group-label">Khóa học <span class="text-danger">*</span></label>
            <select name="webinar_id" class="form-control select2" required>
                <option value="">-- Chọn khóa học --</option>
                @foreach($instructorWebinars as $w)
                    <option value="{{ $w->id }}" {{ old('webinar_id') == $w->id ? 'selected' : '' }}>
                        {{ $w->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-1 btn-block rounded-12 d-inline-flex justify-content-center align-items-center" style="height:38px;gap:6px;white-space:nowrap;">
            <x-iconsax-lin-add class="icons mr-1" width="16"/>
            Thêm học viên
        </button>
    </form>
</div>

@endsection
