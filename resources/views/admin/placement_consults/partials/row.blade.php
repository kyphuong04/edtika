{{-- 1 dòng "Yêu cầu tư vấn". Server render lại dòng này sau khi cập nhật (AJAX)
     để thay đúng chỗ mà không tải lại trang. Cần: $item, $pcRoute, $canViewAttempt. --}}
@php
    $attempt = $item->attempt;
    $speakingUrl = $attempt && $attempt->speaking_recording_path
        ? \Illuminate\Support\Facades\Storage::url($attempt->speaking_recording_path)
        : null;
    $initials = collect(preg_split('/\s+/u', trim((string) $item->full_name)))
        ->filter()->take(-2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
@endphp
<tr data-pc-row="{{ $item->id }}" class="{{ $item->status === 'new' ? 'is-new' : '' }}">
    <td>
        <div class="pc-person">
            <span class="pc-avatar" aria-hidden="true">{{ $initials ?: '?' }}</span>
            <div class="pc-person__text">
                <div class="pc-name">{{ $item->full_name }}</div>
                @if(!$item->user)
                    <div class="pc-muted">Tài khoản đã bị xoá</div>
                @elseif($item->user->full_name !== $item->full_name)
                    <div class="pc-muted" title="Tên tài khoản">TK: {{ $item->user->full_name }}</div>
                @endif
            </div>
        </div>
    </td>
    <td>
        <a class="pc-phone" href="tel:{{ preg_replace('/[^0-9+]/', '', $item->mobile) }}"><i class="fas fa-phone-alt"></i>{{ $item->mobile }}</a>
        @if($item->email)
            <a class="pc-email" href="mailto:{{ $item->email }}">{{ $item->email }}</a>
        @endif
    </td>
    <td><span class="pc-course">{{ $item->course_title }}</span></td>
    <td>
        <div class="pc-result">
            @if($item->final_level)
                <span class="pc-chip pc-chip--level">{{ $item->final_level }}</span>
            @else
                <span class="pc-muted">—</span>
            @endif

            @if($attempt && !empty($attempt->levels_taken))
                <div class="pc-scores">
                    @foreach($attempt->levels_taken as $i => $lvl)
                        <span class="pc-chip pc-chip--score {{ $attempt->isStepScored($i) ? '' : 'is-ref' }}"
                              title="Đề {{ $i + 1 }}{{ $attempt->isStepScored($i) ? '' : ' — tham khảo, không tính điểm' }}">{{ $lvl }} · {{ $attempt->scores[$i] ?? '—' }}/10</span>
                    @endforeach
                </div>
            @endif

            @if($attempt && $attempt->status === 'archived')
                <div class="pc-muted">Bài thi đã được reset</div>
            @endif
        </div>
    </td>
    <td class="text-center">
        @if($speakingUrl)
            <button type="button" class="pc-icon-btn pc-icon-btn--soft" data-pc-play="{{ $speakingUrl }}" title="Nghe phần Speaking">
                <i class="fas fa-play"></i>
            </button>
        @else
            <span class="pc-muted" title="Không có ghi âm"><i class="fas fa-microphone-slash"></i></span>
        @endif
    </td>
    <td>
        <div class="pc-date">{{ $item->created_at->format('d/m/Y') }}</div>
        <div class="pc-muted">{{ $item->created_at->format('H:i') }}</div>
    </td>
    <td>
        <span class="pc-status pc-status--{{ $item->status }}">{{ $item->statusLabel() }}</span>
        @if($item->handled_at)
            <div class="pc-muted pc-handled" title="Người xử lý gần nhất">
                {{ optional($item->handler)->full_name ?? 'Không rõ' }} · {{ $item->handled_at->format('d/m H:i') }}
            </div>
        @endif
    </td>
    <td>
        @if($item->admin_note)
            <div class="pc-note" title="{{ $item->admin_note }}">{{ $item->admin_note }}</div>
        @else
            <span class="pc-muted">—</span>
        @endif
    </td>
    <td>
        <div class="pc-actions">
            <button type="button" class="pc-icon-btn pc-icon-btn--primary" title="Cập nhật trạng thái / ghi chú"
                    data-pc-edit
                    data-action="{{ route($pcRoute . '.update', $item->id) }}"
                    data-name="{{ $item->full_name }}"
                    data-meta="{{ $item->mobile }} · {{ $item->course_title }}"
                    data-status="{{ $item->status }}"
                    data-note="{{ $item->admin_note }}">
                <i class="fas fa-pen"></i>
            </button>

            @if($canViewAttempt && $item->user_id && $attempt)
                <a href="{{ route('admin.users.placement_result_detail', $item->user_id) }}" target="_blank"
                   class="pc-icon-btn pc-icon-btn--soft" title="Xem chi tiết bài làm Placement gần nhất">
                    <i class="fas fa-file-alt"></i>
                </a>
            @endif

            <button type="button" class="pc-icon-btn pc-icon-btn--danger" title="Xoá yêu cầu"
                    data-pc-delete="{{ route($pcRoute . '.destroy', $item->id) }}"
                    data-name="{{ $item->full_name }}">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </td>
</tr>
