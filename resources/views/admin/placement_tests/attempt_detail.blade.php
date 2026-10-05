@extends('admin.layouts.app')

@push('styles_top')
<style>
.pt-q-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:20px; margin-bottom:16px; }
.pt-q-num { display:inline-block; background:#f3e8ff; color:#511D99; font-weight:700; font-size:13px; padding:3px 10px; border-radius:8px; }
.pt-q-text { font-size:15px; font-weight:600; color:#111827; margin:10px 0; line-height:1.5; }
.pt-option { padding:8px 12px; border:2px solid #e5e7eb; border-radius:8px; margin-bottom:6px; }
.pt-img-options { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:8px; }
.pt-img-option { border:2px solid #e5e7eb; border-radius:10px; padding:8px; text-align:center; }
.pt-img-option img {
    width: 100%;
    aspect-ratio: 3 / 2;
    height: auto;
    object-fit: contain;
    background: #f8fafc;
    border-radius: 6px;
    margin-bottom: 6px;
    display: block;
}

/* Layout admin phủ nền tím lên mọi phần tử trong .main-content -> đè lại */
.main-content .pt-img-option img {
    background: #f8fafc !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
}
.pt-correct-line { margin-top:10px; font-size:13px; font-weight:700; color:#16a34a; }
.pt-test-header { background:#511D99; color:#fff; border-radius:12px; padding:14px 20px; margin:26px 0 14px; }

/* ── Hiển thị nội dung rich text ──────────────────────────────────────
   HTMLPurifier chuẩn hoá style về dạng "text-align:center" (không dấu
   cách) nên selector phải khớp cả hai biến thể. */
.pt-rich ul,
.pt-rich ol { padding-left: 24px !important; margin-bottom: 10px !important; }
.pt-rich ul { list-style: disc !important; }
.pt-rich ol { list-style: decimal !important; }
.pt-rich li { display: list-item !important; list-style: inherit !important; }
.pt-rich p  { margin-bottom: 8px; }
.pt-rich p:last-child { margin-bottom: 0; }
.pt-rich [style*="text-align:center"],
.pt-rich [style*="text-align: center"]  { text-align: center !important; }
.pt-rich [style*="text-align:right"],
.pt-rich [style*="text-align: right"]   { text-align: right !important; }
.pt-rich [style*="text-align:justify"],
.pt-rich [style*="text-align: justify"] { text-align: justify !important; }
.pt-rich [style*="text-align:left"],
.pt-rich [style*="text-align: left"]    { text-align: left !important; }

.pt-instruction {
    background: #f5f3ff;
    border-left: 4px solid #a78bfa;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 12px;
    font-size: 14px;
    font-weight: 600;
    color: #4c1d95;
    line-height: 1.5;
}

/* Layout admin có rule phủ nền tím mờ lên mọi phần tử trong .main-content;
   không đè lại thì khung hướng dẫn sẽ bị ám màu và khó đọc. */
.main-content .pt-instruction {
    background: #f5f3ff !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
}

.pt-word-bank {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    background: #f5f3ff;
    border: 1px dashed #a78bfa;
    border-radius: 10px;
    padding: 12px 14px;
}
.pt-word-chip {
    background: #fff;
    border: 1px solid #ddd6fe;
    color: #511D99;
    font-weight: 600;
    font-size: 13px;
    padding: 4px 12px;
    border-radius: 20px;
}

/* Thẻ đoạn văn dùng chung class .pt-q-card với câu hỏi nên selector phải
   hẹp, tránh ảnh hưởng word bank ở chỗ khác. */
.pt-q-card .pt-word-bank {
    margin-top: 14px;
    margin-bottom: 18px;
}

/* Layout admin phủ nền tím mờ + blur lên mọi phần tử trong .main-content.
   Không đè lại thì hộp từ gợi ý sẽ bị ám màu, chip trắng thành tím. */
.main-content .pt-word-bank {
    background: #f5f3ff !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    box-shadow: none !important;
}
.main-content .pt-word-chip {
    background: #fff !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    box-shadow: none !important;
}

/* ── Nhóm audio dùng chung cho nhiều câu (VD: câu 9–10) ── */
.pt-audio-group {
    background:#eef2ff; border:1px solid #c7d2fe; border-radius:14px;
    padding:12px 16px; margin:8px 0 12px;
}
.pt-audio-group-label {
    font-size:12px; font-weight:700; color:#4338CA;
    text-transform:uppercase; letter-spacing:.4px; margin-bottom:8px;
}
.main-content .pt-audio-group {
    background: #eef2ff !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
}
/* Nhãn nhóm audio + đề bài nằm cùng hàng. Nhãn viết hoa, đề bài giữ
   nguyên chữ thường nên phải tách text-transform ra khỏi hàng cha. */
.pt-audio-group-head {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}
.pt-audio-group-head .pt-audio-group-label {
    margin-bottom: 0;
    flex-shrink: 0;
}
.pt-audio-group-instruction {
    background: #fff;
    border-radius: 8px;
    padding: 5px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #4c1d95;
    line-height: 1.4;
    flex: 1;
    min-width: 0;
}
.main-content .pt-audio-group-instruction {
    background: #fff !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
}
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Chi tiết bài làm — {{ $user->full_name }}</h1>
    </div>

    <div class="section-body">
        <div class="mb-20" style="background:#f5f3ff;border-radius:12px;padding:16px;">
            <strong>Level cuối cùng: {{ $attempt->final_level }}</strong>
            &middot; Hoàn thành lúc {{ optional($attempt->completed_at)->format('d/m/Y H:i') }}
        </div>

        @foreach($testBlocks as $block)
            <div class="pt-test-header d-flex justify-content-between align-items-center">
                <span>Đề {{ $block['index'] + 1 }} &middot; Level {{ $block['test']->level }} &middot; {{ $block['test']->title }}</span>
                @if(!$block['is_scored'])
                    <span class="badge badge-warning">Tham khảo — không tính điểm</span>
                @endif
            </div>

            @php
                $blockPassages = collect($block['test']->reading_passages ?? [])->keyBy('position');
            @endphp

            @foreach($block['questions'] as $idx => $item)

                @if($blockPassages->has($idx + 1))
                    @php $psg = $blockPassages[$idx + 1]; @endphp
                    <div class="pt-q-card" style="background:#faf5ff;">
                        <strong>Đoạn văn đọc:</strong>

                        {{-- Từ gợi ý đứng TRÊN đoạn văn --}}
                        @if(!empty($psg['word_bank']))
                            <div class="pt-word-bank">
                                @foreach($psg['word_bank'] as $word)
                                    <span class="pt-word-chip">{{ $word }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-2 pt-rich">{!! ptRichText($psg['content']) !!}</div>
                    </div>
                @endif

                @php $q = $item['question']; $isCorrect = $item['is_correct']; @endphp
                <div class="pt-q-card" style="border-left:5px solid {{ $isCorrect ? '#22c55e' : '#ef4444' }};">
                    <span class="pt-q-num">Câu {{ $idx + 1 }}</span>
                    <span class="badge {{ $isCorrect ? 'badge-success' : 'badge-danger' }} float-right">
                        {{ $isCorrect ? 'Đúng' : 'Sai' }}
                    </span>

                    @if(!empty($q['instruction']))
                        <div class="pt-instruction pt-rich">{!! ptRichText($q['instruction']) !!}</div>
                    @endif

                    {{-- Audio của nhóm câu: chỉ render ở câu mở đầu nhóm, tránh
                         lặp player khi nhiều câu dùng chung một file. --}}
                    @if(!empty($q['audio_group_start']) && $q['audio_url'])
                        <div class="pt-audio-group">
                            <div class="pt-audio-group-head">
                                <div class="pt-audio-group-label">
                                    <i class="fas fa-headphones mr-1"></i>Audio cho câu {{ $q['audio_group_range'] ?? ($idx + 1) }}
                                </div>

                                @if(!empty($q['audio_group_instruction']))
                                    <div class="pt-audio-group-instruction">{{ $q['audio_group_instruction'] }}</div>
                                @endif
                            </div>

                            <audio controls style="width:100%;max-width:380px;" src="{{ $q['audio_url'] }}"></audio>
                        </div>
                    @endif

                    @if($q['type'] === 'multiple_choice')
                        <div class="pt-q-text pt-rich">{!! ptRichText($q['question_text']) !!}</div>

                        @php
                            // Chuẩn hoá trước khi so sánh, khớp với cách
                            // isAnswerCorrect() dùng trim() khi chấm điểm.
                            $givenValue = is_scalar($item['given']) ? trim((string) $item['given']) : null;
                            $hasAnswer = $givenValue !== null && $givenValue !== '';
                        @endphp

                        @if(!$hasAnswer)
                            <div class="p-2 mb-2" style="background:#fef3c7;border-radius:8px;font-size:13px;color:#78350f;">
                                <i class="fas fa-exclamation-circle mr-1"></i>Học viên không trả lời câu này.
                            </div>
                        @endif

                        @foreach($q['options'] as $option)
                            @php $isGiven = $hasAnswer && $givenValue === trim((string) $option); @endphp
                            <div class="pt-option" style="{{ $isGiven ? ($isCorrect ? 'border-color:#22c55e;background:#f0fdf4;' : 'border-color:#ef4444;background:#fef2f2;') : '' }}">
                                {{ $option }} @if($isGiven)<strong class="ml-2">(học viên chọn)</strong>@endif
                            </div>
                        @endforeach

                    @elseif($q['type'] === 'listening_image_choice')
                        <div class="pt-q-text pt-rich">{!! ptRichText($q['question_text']) !!}</div>

                        @php
                            $givenValue = is_scalar($item['given']) ? trim((string) $item['given']) : null;
                            $hasAnswer = $givenValue !== null && $givenValue !== '';
                        @endphp

                        @if(!$hasAnswer)
                            <div class="p-2 mb-2" style="background:#fef3c7;border-radius:8px;font-size:13px;color:#78350f;">
                                <i class="fas fa-exclamation-circle mr-1"></i>Học viên không trả lời câu này.
                            </div>
                        @endif

                        <div class="pt-img-options">
                            @foreach($q['image_options'] as $imgOpt)
                                @php $isGiven = $hasAnswer && $givenValue === trim((string) $imgOpt['label']); @endphp
                                <div class="pt-img-option" style="{{ $isGiven ? ($isCorrect ? 'border-color:#22c55e;' : 'border-color:#ef4444;') : '' }}">
                                    <img src="{{ $imgOpt['url'] }}">
                                    {{ $imgOpt['label'] }} @if($isGiven)(đã chọn)@endif
                                </div>
                            @endforeach
                        </div>

                    @elseif($q['type'] === 'error_correction')
                        <div class="pt-q-text pt-rich">{!! ptRichText($q['question_text']) !!}</div>
                        <div class="p-2" style="background:{{ $isCorrect ? '#f0fdf4' : '#fef2f2' }};border-radius:8px;">
                            Học viên trả lời: {{ $item['given'] ?: '(bỏ trống)' }}
                        </div>

                    @elseif($q['type'] === 'sentence_completion')
                        @php
                            $parts = preg_split('/_{2,}/', $q['question_text']);
                            // given có thể là null khi hết giờ auto-submit -> ép về
                            // mảng để không nổ "array offset on null".
                            $givenArr = is_array($item['given']) ? $item['given'] : [];
                        @endphp
                        <div class="pt-q-text" style="font-weight:400;">
                            @foreach($parts as $pIdx => $part)
                                {!! nl2br(e($part)) !!}
                                @if($pIdx < count($parts) - 1)
                                    <strong style="color:{{ $isCorrect ? '#16a34a' : '#dc2626' }};">
                                        [{{ $givenArr[$pIdx] ?? '(bỏ trống)' }}]
                                    </strong>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <div class="pt-correct-line">Đáp án đúng: {{ $item['correct_display'] }}</div>

                    @if($item['answer_help'])
                        <div class="mt-2 p-2" style="background:#eff6ff;border-left:3px solid #3b82f6;border-radius:6px;font-size:13px;color:#1e40af;">
                            <i class="fas fa-lightbulb mr-1"></i><strong>Giải thích:</strong>
                            <span class="pt-rich">{!! ptRichText($item['answer_help']) !!}</span>
                        </div>
                    @endif
                </div>
            @endforeach

            {{-- Đoạn văn ở vị trí "Cuối bài" --}}
            @if($blockPassages->has(count($block['questions']) + 1))
                @php $psgLast = $blockPassages[count($block['questions']) + 1]; @endphp
                <div class="pt-q-card" style="background:#faf5ff;">
                    <strong>Đoạn văn đọc:</strong>

                    @if(!empty($psgLast['word_bank']))
                        <div class="pt-word-bank">
                            @foreach($psgLast['word_bank'] as $word)
                                <span class="pt-word-chip">{{ $word }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-2 pt-rich">{!! ptRichText($psgLast['content']) !!}</div>
                </div>
            @endif
        @endforeach

        <div class="pt-q-card">
            <h5>Phần thi Speaking</h5>
            @if($attempt->speakingQuestion)
                <div class="mb-2 text-muted">{{ $attempt->speakingQuestion->question_text }}</div>
            @endif
            @if($speakingAudioUrl)
                <audio controls style="width:100%;max-width:400px;" src="{{ $speakingAudioUrl }}"></audio>
            @else
                <div class="text-muted">Học viên chưa ghi âm phần này.</div>
            @endif
        </div>
    </div>
</section>
@endsection