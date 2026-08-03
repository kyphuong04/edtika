@if(!$attempt)
    <div class="text-center py-30 text-muted">
        <i class="fas fa-info-circle mr-2"></i>Học viên này chưa hoàn thành bài Placement Test.
    </div>
@else
    <div class="mb-16">
        <a href="{{ route('admin.users.placement_result_detail', $user) }}" class="btn btn-primary btn-sm" target="_blank">
            <i class="fas fa-file-alt mr-2"></i>Xem chi tiết bài làm
        </a>
    </div>
    <div class="row mb-20">
        <div class="col-md-6">
            <div style="border:2px solid #511D99;border-radius:12px;padding:16px;text-align:center;">
                <div style="font-size:13px;color:#6b7280;">Level cuối cùng</div>
                <div style="font-size:28px;font-weight:900;color:#511D99;">{{ $attempt->final_level ?? '—' }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div style="border:2px solid #e5e7eb;border-radius:12px;padding:16px;text-align:center;">
                <div style="font-size:13px;color:#6b7280;">Hoàn thành lúc</div>
                <div style="font-size:16px;font-weight:700;color:#111827;">
                    {{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : '—' }}
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Chi tiết từng đề đã làm</h5>
    <table class="table table-bordered mb-30">
        <thead>
            <tr>
                <th>#</th>
                <th>Level đề</th>
                <th>Điểm (số câu đúng / 10)</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($attempt->levels_taken ?? []) as $i => $level)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $level }}</span>
                        @if(!$attempt->isStepScored($i))
                            <span class="badge badge-secondary ml-1">Tham khảo — không tính điểm</span>
                        @endif
                    </td>
                    <td>{{ ($attempt->scores[$i] ?? '—') }} / 10</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center text-muted">Không có dữ liệu.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h5 class="mb-3">Phần thi Speaking</h5>
    @if($attempt->speakingQuestion)
        <div style="background:#f5f3ff;border:1px dashed #a78bfa;border-radius:10px;padding:14px;margin-bottom:14px;color:#511D99;font-weight:600;">
            {{ $attempt->speakingQuestion->question_text }}
        </div>
    @endif

    @if($speakingAudioUrl)
        <audio controls style="width:100%;" src="{{ $speakingAudioUrl }}"></audio>
    @else
        <div class="text-muted"><i class="fas fa-microphone-slash mr-2"></i>Học viên chưa ghi âm phần Speaking (hoặc đã bỏ qua).</div>
    @endif
@endif