{{-- Writing Queue --}}
<div class="bg-white p-16 rounded-24 mt-24 h-100">
    <div class="d-flex align-items-center justify-content-between mb-16 position-relative">
        <h4 class="font-14 font-weight-bold text-dark flex-1 text-center">Hàng chờ Writing</h4>
        <a href="{{ route('panel.ielts_grading.index', ['skill' => 'writing']) }}"
           class="d-flex-center size-32 rounded-8 bg-gray-100 bg-hover-primary-40 text-gray-500">
            <x-iconsax-lin-arrow-right class="icons" width="16px" height="16px"/>
        </a>
    </div>

    @if(!empty($teacherWritingQueue) && count($teacherWritingQueue))
        <div class="d-flex flex-column gap-12">
            {{-- show only three newest items --}}
            @foreach($teacherWritingQueue->take(3) as $item)
                @php $test = $item->test; @endphp
                <a href="{{ route('panel.ielts_grading.index', ['skill' => 'writing']) }}"
                   class="d-flex align-items-center justify-content-between bg-gray-100 rounded-16 p-12 text-dark text-decoration-none bg-hover-primary-40 border-2 border-gray-300">
                    <div class="d-flex align-items-center gap-10" style="min-width:0;">
                        <div class="d-flex-center size-36 rounded-8 bg-white border-gray-200 flex-shrink-0">
                            <x-iconsax-bul-edit class="icons text-primary" width="18px" height="18px"/>
                        </div>
                        <span class="font-13 text-dark text-ellipsis">
                            {{ $test ? truncate($test->title, 28) : 'Bài Writing #' . $item->test_id }}
                        </span>
                    </div>
                    <span class="badge badge-pill font-12 font-weight-bold flex-shrink-0 ml-8"
                          style="background:#ede9fe;color:#6d28d9;min-width:28px;text-align:center;">
                        {{ $item->pending_count }}
                    </span>
                </a>
            @endforeach
        </div>

        @php $totalWriting = \App\Models\IeltsTestAttempt::where('status','completed')->whereNull('writing_band')->whereHas('test', fn($q)=>$q->where('has_writing',true))->count(); @endphp
        @if($totalWriting > 0)
            <div class="mt-12 pt-12 border-top-gray-100 d-flex align-items-center justify-content-between">
                <span class="font-12 text-gray-500">Tổng chờ chấm:</span>
                <span class="font-14 font-weight-bold text-primary">{{ $totalWriting }} bài</span>
            </div>
        @endif
    @else
        <div class="d-flex-center flex-column text-center p-24 bg-gray-100 border-dashed border-gray-200 rounded-16">
            <div class="d-flex-center size-40 rounded-12 bg-primary-40">
                <x-iconsax-bul-edit class="icons text-primary" width="20px" height="20px"/>
            </div>
            <p class="font-13 text-gray-500 mt-10">Không có bài Writing chờ chấm</p>
        </div>
    @endif
</div>
