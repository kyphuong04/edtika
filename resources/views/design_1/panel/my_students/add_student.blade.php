@extends('design_1.panel.layouts.panel')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-20">
    <div>
        <h2 class="font-20 font-weight-bold mb-4">Add Student</h2>
        <p class="font-13 text-gray-500 mb-0">Thêm học viên vào một trong các khóa học của bạn.</p>
    </div>
    <a href="/panel/my-students/list" class="btn btn-sm btn-outline-secondary rounded-12 d-inline-flex align-items-center">
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

        <button type="submit" class="btn btn-primary btn-block rounded-12 mt-20">
            <x-iconsax-lin-add class="icons mr-1" width="16"/>
            Thêm học viên
        </button>
    </form>
</div>

@endsection
