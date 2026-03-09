@php
    $topStudents = $ieltsData['topStudents'] ?? collect();
@endphp

<div class="bg-white rounded-24 p-16 mt-16">
    <h5 class="font-13 font-weight-bold text-dark mb-12">Top Student</h5>

    @forelse($topStudents as $idx => $item)
        @php
            $student = $item['user'];
            $band    = $item['best_band'] ?? 0;
            $medals  = ['🥇','🥈','🥉'];
            $medal   = $medals[$idx] ?? '#' . ($idx + 1);
        @endphp
        @if($student)
            <div class="d-flex align-items-center gap-10 {{ $idx > 0 ? 'mt-10' : '' }}">
                <span class="font-12 flex-shrink-0" style="min-width:20px;">{{ $medal }}</span>
                <div class="size-32 rounded-circle flex-shrink-0 bg-gray-100">
                    <img src="{{ $student->getAvatar(32) }}" alt="" class="img-cover rounded-circle">
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="font-12 font-weight-bold text-dark text-ellipsis">{{ $student->full_name }}</div>
                    <div class="font-11 text-gray-400">Band {{ number_format($band, 1) }}</div>
                </div>
                {{-- Mini progress bar --}}
                <div class="flex-shrink-0" style="width:48px;">
                    <div class="rounded-pill bg-gray-100" style="height:4px;">
                        <div class="rounded-pill bg-primary" style="width:{{ round($band/9*100) }}%;height:4px;"></div>
                    </div>
                </div>
            </div>
        @endif
    @empty
        <p class="font-12 text-gray-400 mb-0 text-center py-8">No data yet</p>
    @endforelse
</div>
