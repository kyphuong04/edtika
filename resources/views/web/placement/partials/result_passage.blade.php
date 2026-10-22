{{-- Đoạn văn đọc trong modal "Chi tiết bài làm" (trang kết quả Placement Test).
     $psg: 1 phần tử của PlacementTest::reading_passages (position, instruction, word_bank, content). --}}
<div class="prs-passage">
    <div class="prs-passage__head">
        <span class="prs-passage__label"><i class="fas fa-book-open mr-1"></i>Đoạn văn đọc</span>
        @if(!empty($psg['instruction']))
            <div class="prs-passage__instruction">{{ $psg['instruction'] }}</div>
        @endif
    </div>

    @if(!empty($psg['word_bank']))
        <div class="prs-word-bank">
            @foreach($psg['word_bank'] as $word)
                <span class="prs-word-chip">{{ $word }}</span>
            @endforeach
        </div>
    @endif

    <div class="pt-rich">{!! ptRichText($psg['content'] ?? '') !!}</div>
</div>
