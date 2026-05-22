<div class="bg-white rounded-24 p-16 h-100 d-flex flex-column">
    <h4 class="font-14 font-weight-bold text-dark mb-16">Mentor Feedback</h4>

    @if(!empty($ieltsData['recentFeedbacks']) && count($ieltsData['recentFeedbacks']))
        <div class="d-flex flex-column gap-12 flex-grow-1" style="overflow-y:auto;max-height:280px;" data-simplebar @if(!empty($isRtl)) data-simplebar-direction="rtl" @endif>
            @foreach($ieltsData['recentFeedbacks'] as $feedback)
                @php
                    $grader = $feedback['grader'] ?? null;
                    $feedbackText = strip_tags((string) ($feedback['feedback'] ?? ''));
                    $attemptId = $feedback['attempt_id'] ?? null;
                @endphp
                <div class="rounded-16 bg-gray-100 p-12">
                    <div class="d-flex align-items-center gap-8">
                        <div class="size-36 rounded-circle flex-shrink-0 bg-white d-flex-center">
                            @if(!empty($grader))
                                <img src="{{ $grader->getAvatar(36) }}" alt="" class="img-cover rounded-circle">
                            @else
                                <x-iconsax-bul-message-text class="icons text-primary" width="18px" height="18px"/>
                            @endif
                        </div>
                        <div class="min-w-0 flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between gap-8">
                                <p class="font-12 font-weight-bold text-dark mb-0 text-ellipsis">{{ $feedback['test_title'] }} · {{ $feedback['skill'] }}</p>
                                @if(isset($feedback['band']) && $feedback['band'] !== null)
                                    <span class="badge badge-primary-light font-10 flex-shrink-0">{{ number_format((float) $feedback['band'], 1) }}</span>
                                @endif
                            </div>
                            <span class="font-11 text-gray-400">{{ !empty($grader) ? $grader->full_name : 'Mentor' }} · {{ !empty($feedback['graded_at']) ? dateTimeFormat($feedback['graded_at'], 'j M Y') : '' }}</span>
                        </div>
                    </div>
                    @if(!empty($feedbackText))
                        <p class="font-11 text-gray-500 mt-8 mb-0 white-space-pre-wrap">{{ truncate($feedbackText, 120) }}</p>
                    @endif

                    @if(!empty($attemptId))
                        <a href="{{ route('panel.ielts_tests.review', $attemptId) }}" class="btn btn-sm btn-outline-primary mt-10">
                            Xem chi tiết
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="d-flex-center flex-column text-center p-20 rounded-16 bg-gray-100 flex-grow-1">
            <x-iconsax-bul-message-text class="icons text-primary" width="32px" height="32px"/>
            <p class="font-12 text-gray-500 mt-8 mb-0">No mentor feedback yet.</p>
        </div>
    @endif
</div>
