@php
    $weakPointPreviewItems = $ieltsData['weakPointPreviewItems'] ?? [];
    $weakPointItems = $ieltsData['weakPointItems'] ?? [];
    $weakPointTotalItems = (int) ($ieltsData['weakPointTotalItems'] ?? count($weakPointItems));

    $mistakeFrequency = [];
    foreach ($weakPointItems as $item) {
        $topicPart = trim((string) ($item['topic'] ?? ''));
        $questionPart = trim((string) ($item['question'] ?? ''));
        $signature = mb_strtolower($topicPart . '|' . $questionPart);

        if ($signature === '|') {
            $signature = 'general';
        }

        $mistakeFrequency[$signature] = ($mistakeFrequency[$signature] ?? 0) + 1;
    }

    $weakPointPreviewItems = collect($weakPointPreviewItems)
        ->sort(function ($a, $b) use ($mistakeFrequency) {
            $aSignature = mb_strtolower(trim((string) ($a['topic'] ?? '')) . '|' . trim((string) ($a['question'] ?? '')));
            $bSignature = mb_strtolower(trim((string) ($b['topic'] ?? '')) . '|' . trim((string) ($b['question'] ?? '')));

            if ($aSignature === '|') {
                $aSignature = 'general';
            }
            if ($bSignature === '|') {
                $bSignature = 'general';
            }

            $aCount = $mistakeFrequency[$aSignature] ?? 0;
            $bCount = $mistakeFrequency[$bSignature] ?? 0;

            if ($aCount === $bCount) {
                return ((int) ($b['occurred_at'] ?? 0)) <=> ((int) ($a['occurred_at'] ?? 0));
            }

            return $bCount <=> $aCount;
        })
        ->values()
        ->all();

    // Mistakes are rendered from darker to lighter using a soft, readable palette.
    $mistakeColorScale = ['#C9D8EE', '#D5E1F1', '#E1EAF5', '#EAF0F8', '#F2F6FB', '#F8FAFD'];
@endphp

<div class="bg-white rounded-24 p-16 h-100 d-flex flex-column">
    <div class="d-flex align-items-center justify-content-between mb-12">
        <h4 class="font-13 font-weight-bold text-dark mb-0" style="letter-spacing:.5px;">WEAK POINT</h4>
        <div class="d-flex align-items-center" style="gap:10px;">
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-12 py-4 font-11"
                    data-toggle="modal" data-target="#weakPointDetailsModal">
                Xem tất cả
            </button>
        </div>
    </div>

    <div class="mt-4">
        <div class="d-flex align-items-center justify-content-between mb-8">
            <p class="font-12 font-weight-bold text-dark mb-0">Những weakpoint hiện tại</p>
            <span class="badge badge-danger-light font-11">{{ $weakPointTotalItems }}</span>
        </div>

        @if(!empty($weakPointPreviewItems))
            <div class="d-flex flex-column" style="gap:8px;max-height:190px;overflow:auto;">
                @foreach($weakPointPreviewItems as $index => $item)
                    @php
                        $recommendation = $item['recommendations'][0] ?? null;
                        $shadeIndex = min($index, count($mistakeColorScale) - 1);
                        $cardBg = $mistakeColorScale[$shadeIndex];
                        $metaColor = '#4B5563';
                        $titleColor = '#1F2937';
                        $answerColor = '#047857';
                        $linkColor = '#5B21B6';
                    @endphp
                    <div class="rounded-12 p-10" style="background:{{ $cardBg }};border:1px solid #D7E1ED;">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="badge badge-warning-light font-10">{{ $item['skill_label'] ?? 'General' }}</span>
                            <span class="font-10" style="color:{{ $metaColor }};">{{ $item['source_label'] ?? 'Learning' }}</span>
                        </div>

                        <p class="font-11 font-weight-bold mb-4" style="color:{{ $titleColor }};">{{ $item['topic'] ?? 'Weak point' }}</p>

                        @if(!empty($item['your_answer']))
                            <p class="font-10 mb-2" style="color:{{ $metaColor }};">Câu trả lời của bạn: <span style="color:{{ $titleColor }};">{{ $item['your_answer'] }}</span></p>
                        @endif

                        @if(!empty($item['correct_answer']))
                            <p class="font-10 mb-2" style="color:{{ $answerColor }};">Gợi ý: {{ $item['correct_answer'] }}</p>
                        @endif

                        @if(!empty($recommendation['url']))
                            <a href="{{ $recommendation['url'] }}" class="font-10" style="color:{{ $linkColor }};">{{ $recommendation['label'] ?? 'Practice now' }} →</a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-12 bg-gray-100 p-10">
                <p class="font-11 text-gray-500 mb-0">Không có weakpoint nào. Hãy tiếp tục học và làm bài kiểm tra để nhận các weakpoint cá nhân hóa.</p>
            </div>
        @endif
    </div>
</div>

<style>
    #weakPointDetailsModal {
        pointer-events: none;
    }

    #weakPointDetailsModal .modal-dialog {
        pointer-events: auto;
        max-width: 760px;
    }

    .close {
        border: none;
        outline: none;
        background: transparent;
        box-shadow: none;
    }
</style>

<div class="modal fade" id="weakPointDetailsModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content rounded-16 border-0">
            <div class="modal-header border-bottom-gray-100">
                <h5 class="modal-title font-16 font-weight-bold">Tất cả các weakpoint và lỗi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-16">
                @if(!empty($weakPointItems))
                    <div class="d-flex flex-column" style="gap:10px;">
                        @foreach($weakPointItems as $item)
                            <div class="rounded-12 border border-gray-200 p-12">
                                <div class="d-flex align-items-center justify-content-between mb-6">
                                    <div class="d-flex align-items-center" style="gap:6px;">
                                        <span class="badge badge-danger-light font-10">{{ $item['skill_label'] ?? 'General' }}</span>
                                        <span class="badge badge-warning-light font-10">{{ $item['source_label'] ?? 'Learning' }}</span>
                                    </div>
                                    @if(!empty($item['test_title']))
                                        <span class="font-10 text-gray-500">{{ $item['test_title'] }}</span>
                                    @endif
                                </div>

                                <p class="font-12 font-weight-bold text-dark mb-4">{{ $item['topic'] ?? 'Weak point' }}</p>

                                @if(!empty($item['question']))
                                    <p class="font-11 text-gray-500 mb-4">Câu hỏi: {{ $item['question'] }}</p>
                                @endif

                                @if(!empty($item['your_answer']))
                                    <p class="font-11 text-dark mb-2">Câu trả lời của bạn: {{ $item['your_answer'] }}</p>
                                @endif

                                @if(!empty($item['correct_answer']))
                                    <p class="font-11 text-success mb-6">Gợi ý: {{ $item['correct_answer'] }}</p>
                                @endif

                                @if(!empty($item['recommendations']) && is_array($item['recommendations']))
                                    <div class="d-flex flex-wrap" style="gap:8px;">
                                        @foreach($item['recommendations'] as $rec)
                                            @if(!empty($rec['url']))
                                                <a href="{{ $rec['url'] }}" class="btn btn-sm btn-outline-primary rounded-pill font-10 px-10 py-4">
                                                    {{ $rec['label'] ?? 'Luyện tập' }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-12 bg-gray-100 p-16 text-center">
                        <p class="font-12 text-gray-500 mb-0">Không có chi tiết weakpoint nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        if (typeof window === 'undefined' || typeof window.jQuery === 'undefined') {
            return;
        }

        var $ = window.jQuery;
        var $modal = $('#weakPointDetailsModal');

        $modal.on('shown.bs.modal', function () {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
        });
    })();
</script>
