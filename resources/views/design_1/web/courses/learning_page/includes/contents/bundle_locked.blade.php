{{-- GĐ3: nội dung đang bị khóa theo quy tắc học tuần tự của bundle --}}
<div class="d-flex-center flex-column text-center bg-white rounded-24 px-32 py-80">
    <div class="d-flex-center size-64 rounded-12 bg-gray-100">
        <x-iconsax-lin-lock class="icons text-gray-500" width="32px" height="32px"/>
    </div>

    <h3 class="font-16 font-weight-bold mt-16">{{ trans('bundle_learning.locked_title') }}</h3>
    <p class="font-14 text-gray-500 mt-8">{{ trans('bundle_learning.' . $reason . '_msg') }}</p>

    @if(!empty($bundle))
        <a href="/panel/courses/purchases/{{ $bundle->slug }}" class="btn btn-primary mt-24">{{ trans('bundle_learning.back_to_course') }}</a>
    @endif
</div>
