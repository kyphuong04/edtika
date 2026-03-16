{{-- Students Needing Support --}}
<div class="bg-white p-16 rounded-24">
    <div class="d-flex align-items-center justify-content-between mb-16 position-relative">
        <h4 class="font-14 font-weight-bold text-dark flex-1 text-center">Học viên cần hỗ trợ</h4>
        <a href="/panel/my-students"
           class="d-flex-center size-32 rounded-8 bg-gray-100 bg-hover-primary-40 text-gray-500">
            <x-iconsax-lin-arrow-right class="icons" width="16px" height="16px"/>
        </a>
    </div>

    @if(!empty($teacherStudentsSupport) && count($teacherStudentsSupport))
        <div class="d-flex flex-column gap-12">
            {{-- show only three and add border --}}
            @foreach($teacherStudentsSupport->take(3) as $item)
                @php $student = $item['user']; @endphp
                <div class="d-flex align-items-center bg-gray-100 rounded-16 p-12 gap-10 border-2 border-gray-300">
                    <div class="size-40 rounded-circle flex-shrink-0">
                        <img src="{{ $student->getAvatar(40) }}" alt="{{ $student->full_name }}"
                             class="img-cover rounded-circle">
                    </div>
                    <div class="flex-1" style="min-width:0;">
                        <span class="d-block font-13 font-weight-bold text-dark text-ellipsis">
                            {{ truncate($student->full_name, 22) }}
                        </span>
                        @if($item['reason'] === 'quiz_failed')
                            <span class="d-block font-11 text-danger mt-2">
                                <x-iconsax-lin-warning-2 class="icons" width="12px" height="12px"/>
                                Điểm thấp: {{ truncate($item['detail'], 22) }}
                            </span>
                        @else
                            <span class="d-block font-11 text-warning mt-2">
                                <x-iconsax-bul-danger class="icons" width="12px" height="12px"/>
                                Band thấp: {{ $item['detail'] }}/9
                            </span>
                        @endif
                    </div>
                    <a href="/panel/support"
                       class="d-flex-center size-32 rounded-8 bg-white flex-shrink-0 text-gray-500">
                        <x-iconsax-lin-message class="icons" width="16px" height="16px"/>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="d-flex-center flex-column text-center p-24 bg-gray-100 border-dashed border-gray-200 rounded-16">
            <div class="d-flex-center size-40 rounded-12 bg-success-40">
                <x-iconsax-bul-profile-2user class="icons text-success" width="20px" height="20px"/>
            </div>
            <p class="font-13 text-gray-500 mt-10">Không có học viên cần hỗ trợ</p>
        </div>
    @endif
</div>
