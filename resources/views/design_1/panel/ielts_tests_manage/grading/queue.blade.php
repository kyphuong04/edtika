@extends('design_1.panel.layouts.panel')

@section('content')
<section class="mt-30">
    <h1 class="section-title mb-20"><i class="fas fa-clipboard-check mr-10"></i>Chấm bài Writing / Speaking</h1>

    <div class="mb-20">
        <a href="{{ route('panel.ielts_grading.index') }}" class="btn btn-sm rounded-12 {{ !$skillFilter ? 'btn-1' : '' }}">Tất cả</a>
        <a href="{{ route('panel.ielts_grading.index', ['skill' => 'writing']) }}" class="btn btn-sm rounded-12 {{ $skillFilter === 'writing' ? 'btn-1' : '' }}">Writing</a>
        <a href="{{ route('panel.ielts_grading.index', ['skill' => 'speaking']) }}" class="btn btn-sm rounded-12 {{ $skillFilter === 'speaking' ? 'btn-1' : '' }}">Speaking</a>
    </div>

    @if($attempts->isEmpty())
        <div class="alert alert-info">Không có bài nào đang chờ chấm.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Học viên</th>
                    <th>Đề thi</th>
                    <th>Hoàn thành lúc</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($attempts as $attempt)
                @php
                    $needsWriting = $attempt->test->sections->contains('skill', 'writing') && !$attempt->writing_graded_at;
                    $needsSpeaking = $attempt->test->sections->contains('skill', 'speaking') && !$attempt->speaking_graded_at;
                @endphp
                <tr>
                    <td>{{ $attempt->user->full_name ?? $attempt->user->name ?? '—' }}</td>
                    <td>{{ $attempt->test->title }}</td>
                    <td>{{ $attempt->completed_at ? \Carbon\Carbon::createFromTimestamp($attempt->completed_at)->format('d/m/Y H:i') : '—' }}</td>
                    <td>
                        @if($needsWriting)<span class="badge bg-warning">Chờ chấm Writing</span>@endif
                        @if($needsSpeaking)<span class="badge bg-warning ml-5">Chờ chấm Speaking</span>@endif
                    </td>
                    <td>
                        @if($needsWriting)
                            <a href="{{ route('panel.ielts_grading.writing.show', $attempt->id) }}" class="btn btn-sm btn-1 rounded-12">Chấm Writing</a>
                        @endif
                        @if($needsSpeaking)
                            <a href="{{ route('panel.ielts_grading.speaking.show', $attempt->id) }}" class="btn btn-sm btn-1 rounded-12">Chấm Speaking</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $attempts->links() }}
    @endif
</section>
@endsection