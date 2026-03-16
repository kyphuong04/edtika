{{-- Speaking Queue --}}
<div class="bg-white p-16 rounded-24 mt-24 h-100">
    <div class="d-flex align-items-center justify-content-between mb-16 position-relative">
        <h4 class="font-14 font-weight-bold text-dark flex-1 text-center">Hàng chờ Speaking</h4>
        <a href="{{ route('panel.ielts_grading.index', ['skill' => 'speaking']) }}"
           class="d-flex-center size-32 rounded-8 bg-gray-100 bg-hover-primary-40 text-gray-500">
            <x-iconsax-lin-arrow-right class="icons" width="16px" height="16px"/>
        </a>
    </div>

    @if(!empty($teacherSpeakingQueue) && count($teacherSpeakingQueue))
        <div class="d-flex flex-column gap-12">
            {{-- display latest three only --}}
            @foreach($teacherSpeakingQueue->take(3) as $item)
                @php $test = $item->test; @endphp
                <a href="{{ route('panel.ielts_grading.index', ['skill' => 'speaking']) }}"
                   class="d-flex align-items-center justify-content-between bg-gray-100 rounded-16 p-12 text-dark text-decoration-none bg-hover-primary-40 border-2 border-gray-300">
                    <div class="d-flex align-items-center gap-10" style="min-width:0;">
                        <div class="d-flex-center size-36 rounded-8 bg-white border-gray-200 flex-shrink-0">
                            <x-iconsax-bul-microphone class="icons text-success" width="18px" height="18px"/>
                        </div>
                        <span class="font-13 text-dark text-ellipsis">
                            {{ $test ? truncate($test->title, 28) : 'Bài Speaking #' . $item->test_id }}
                        </span>
                    </div>
                    <span class="badge badge-pill font-12 font-weight-bold flex-shrink-0 ml-8"
                          style="background:#dcfce7;color:#15803d;min-width:28px;text-align:center;">
                        {{ $item->pending_count }}
                    </span>
                </a>
            @endforeach
        </div>

        @php $totalSpeaking = \App\Models\IeltsTestAttempt::where('status','completed')->whereNull('speaking_band')->whereHas('test', fn($q)=>$q->where('has_speaking',true))->count(); @endphp
        @if($totalSpeaking > 0)
            <div class="mt-12 pt-12 border-top-gray-100 d-flex align-items-center justify-content-between">
                <span class="font-12 text-gray-500">Tổng chờ chấm:</span>
                <span class="font-14 font-weight-bold text-success">{{ $totalSpeaking }} bài</span>
            </div>
        @endif
    @else
        <div class="d-flex-center flex-column text-center p-24 bg-gray-100 border-dashed border-gray-200 rounded-16">
            <div class="d-flex-center size-40 rounded-12 bg-success-40">
                <x-iconsax-bul-microphone class="icons text-success" width="20px" height="20px"/>
            </div>
            <p class="font-13 text-gray-500 mt-10">Không có bài Speaking chờ chấm</p>
        </div>
    @endif
</div>
