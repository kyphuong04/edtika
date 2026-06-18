@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
.st-activity-wrap {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 16px;
    padding: 16px;
}

.st-activity-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 4px;
    border-bottom: 1px solid #f5f5f5;
}
.st-activity-item:last-child { border-bottom: none; }

.st-activity-dot {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.st-activity-dot svg {
    width: 18px;
    height: 18px;
    stroke: currentColor;
    fill: none;
    stroke-width: 1.8;
}

.st-dot-learning { background: #eef3ff; color: #4e7cff; }
.st-dot-quiz { background: #ecfdf3; color: #0f9f5f; }
.st-dot-assignment { background: #fff6e8; color: #e08a00; }
.st-dot-test { background: #eef7ff; color: #0b74c9; }
.st-dot-message { background: #f7f0ff; color: #7a46d1; }
.st-dot-enrollment { background: #f2f4f7; color: #4b5563; }

.st-activity-main {
    min-width: 0;
    flex: 1;
}
.st-activity-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e2a3b;
    margin: 0;
}
.st-activity-desc {
    margin: 4px 0 0;
    color: #7b8898;
    font-size: 12px;
}
.st-activity-time {
    margin-left: auto;
    white-space: nowrap;
    font-size: 12px;
    color: #98a2b3;
    flex-shrink: 0;
}
.st-activity-link {
    font-size: 12px;
    font-weight: 600;
    color: #511D99;
    text-decoration: none;
}
.st-activity-link:hover { text-decoration: underline; }

.st-grid {
    display: grid;
    grid-template-columns: repeat(12, minmax(0, 1fr));
    gap: 12px;
}

.st-card {
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 16px;
    padding: 14px;
}

.st-kpi-card { grid-column: span 3; }
.st-kpi-label { font-size: 12px; color: #7b8898; margin: 0; }
.st-kpi-value { font-size: 22px; font-weight: 800; color: #1e2a3b; margin: 6px 0 0; }

.st-table-wrap { overflow: auto; }
.st-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.st-table th, .st-table td {
    border-bottom: 1px solid #f2f4f7;
    padding: 10px 8px;
    text-align: left;
    white-space: nowrap;
}
.st-table th { color: #607087; font-weight: 700; font-size: 12px; }
.st-table td { color: #1e2a3b; }

.st-progress {
    width: 120px;
    height: 8px;
    border-radius: 999px;
    background: #edf1f7;
    overflow: hidden;
    margin-bottom: 6px;
}
.st-progress > span {
    display: block;
    height: 100%;
    background: linear-gradient(90deg, #511D99, #8431ff);
}

.st-block-title {
    font-size: 15px;
    font-weight: 800;
    margin: 0 0 10px;
    color: #1e2a3b;
}

.btn {
    border-radius: 12px;
    background: #fff;
    color: #511D99;
    border: 1.5px solid #511D99;
    transition: background .15s, color .15s, border-color .15s;
}

.btn:hover {
    background: #511D99;
    color: #fff;
    border-color: #511D99;
}


.btn-1 {
    border-radius: 12px;
    background: #511D99;
    color: #fff;
    border: 1.5px solid #511D99;
    transition: background .15s, color .15s, border-color .15s;
}
.btn-1:hover {
    background: #fff;
    color: #511D99;
    border-color: #511D99;
}

@media (max-width: 767px) {
    .st-activity-item { gap: 10px; }
    .st-activity-time { display: block; margin-left: 0; margin-top: 6px; }
    .st-kpi-card { grid-column: span 6; }
}

@media (max-width: 575px) {
    .st-kpi-card { grid-column: span 12; }
}
</style>
@endpush

@section('content')
<section>
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
        <h2 class="section-title">Hoạt động của Học viên - {{ $student->full_name }}</h2>
        <div class="d-flex align-items-center gap-8 mt-12 mt-md-0">
            <a href="/panel/students-tracking/{{ $student->id }}/details" class="btn btn-sm rounded-12 d-none d-md-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;">
                <i class="fa fa-eye mr-2"></i>Tracking
            </a>
            <a href="javascript:history.back()" class="btn-1 btn-sm rounded-12 d-inline-flex align-items-center" style="height:38px;gap:6px;white-space:nowrap;">
                <i class="fa fa-arrow-left mr-2"></i>{{ trans('panel.back') }}
            </a>
        </div>
    </div>

    <div class="st-activity-wrap mt-20">
        <h3 class="st-block-title">Tổng quan học viên</h3>
        <div class="st-grid mb-16">
            <div class="st-card st-kpi-card">
                <p class="st-kpi-label">Số khóa học đang tham gia</p>
                <p class="st-kpi-value">{{ $overview['courses_count'] ?? 0 }}</p>
            </div>
            <div class="st-card st-kpi-card">
                <p class="st-kpi-label">Tiến độ trung bình</p>
                <p class="st-kpi-value">{{ $overview['avg_progress'] ?? 0 }}%</p>
            </div>
            <div class="st-card st-kpi-card">
                <p class="st-kpi-label">Lượt học nội dung</p>
                <p class="st-kpi-value">{{ $overview['total_learning_events'] ?? 0 }}</p>
            </div>
            <div class="st-card st-kpi-card">
                <p class="st-kpi-label">Quiz đã làm</p>
                <p class="st-kpi-value">{{ $overview['total_quiz_attempts'] ?? 0 }}</p>
            </div>
        </div>

        <div class="st-grid mb-16">
            <div class="st-card" style="grid-column: span 12;">
                <h3 class="st-block-title">Theo dõi theo từng khóa học</h3>
                @if(!empty($courseStats) && count($courseStats) > 0)
                    <div class="st-table-wrap">
                        <table class="st-table">
                            <thead>
                                <tr>
                                    <th>Khóa học</th>
                                    <th>Tiến độ</th>
                                    <th>Nội dung đã học</th>
                                    <th>Quiz</th>
                                    <th>Assignment</th>
                                    <th>Test</th>
                                    <th>Tin nhắn</th>
                                    <th>Hoạt động gần nhất</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courseStats as $course)
                                    @php $progress = max(0, min(100, (float) ($course['progress'] ?? 0))); @endphp
                                    <tr>
                                        <td>{{ $course['webinar_title'] ?? 'Khóa học' }}</td>
                                        <td>
                                            <div class="st-progress"><span style="width: {{ $progress }}%"></span></div>
                                            <div>{{ $progress }}%</div>
                                        </td>
                                        <td>{{ $course['lessons_done'] ?? 0 }}</td>
                                        <td>{{ $course['quizzes_done'] ?? 0 }}</td>
                                        <td>{{ $course['assignments_done'] ?? 0 }}</td>
                                        <td>{{ $course['tests_done'] ?? 0 }}</td>
                                        <td>{{ $course['messages_sent'] ?? 0 }}</td>
                                        <td>
                                            @if(!empty($course['last_activity_at']))
                                                {{ dateTimeFormat($course['last_activity_at'], 'j M Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mb-0 text-gray-500">Chưa có dữ liệu khóa học.</p>
                @endif
            </div>
        </div>

        <div class="st-grid mb-16">
            <div class="st-card" style="grid-column: span 12;">
                <h3 class="st-block-title">Chi tiết gần đây: Quiz / Assignment / Test</h3>
                <div class="st-table-wrap">
                    <table class="st-table">
                        <thead>
                            <tr>
                                <th>Loại</th>
                                <th>Tên bài</th>
                                <th>Khóa học</th>
                                <th>Kết quả</th>
                                <th>Thời gian</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rows = collect();
                                foreach(($recentQuizAttempts ?? collect()) as $r){
                                    $rows->push([
                                        'type' => 'Quiz',
                                        'title' => $r['quiz_title'] ?? 'Quiz',
                                        'course' => $r['webinar_title'] ?? 'Khóa học',
                                        'result' => ($r['score'] ?? 'N/A') . ' • ' . ($r['status'] ?? '-'),
                                        'time' => $r['time'] ?? 0,
                                        'url' => $r['url'] ?? null,
                                    ]);
                                }
                                foreach(($recentAssignments ?? collect()) as $r){
                                    $rows->push([
                                        'type' => 'Assignment',
                                        'title' => $r['assignment_title'] ?? 'Assignment',
                                        'course' => $r['webinar_title'] ?? 'Khóa học',
                                        'result' => ($r['grade'] ?? '-') . ' • ' . ($r['status'] ?? '-'),
                                        'time' => $r['time'] ?? 0,
                                        'url' => $r['url'] ?? null,
                                    ]);
                                }
                                foreach(($recentTests ?? collect()) as $r){
                                    $rows->push([
                                        'type' => 'IELTS Test',
                                        'title' => $r['test_title'] ?? 'IELTS Test',
                                        'course' => $r['webinar_title'] ?? 'Khóa học',
                                        'result' => 'Overall band: ' . ($r['overall_band'] ?? 'N/A'),
                                        'time' => $r['time'] ?? 0,
                                        'url' => null,
                                    ]);
                                }
                                $rows = $rows->sortByDesc('time')->take(20);
                            @endphp

                            @if($rows->count() > 0)
                                @foreach($rows as $r)
                                    <tr>
                                        <td>{{ $r['type'] }}</td>
                                        <td>{{ $r['title'] }}</td>
                                        <td>{{ $r['course'] }}</td>
                                        <td>{{ $r['result'] }}</td>
                                        <td>{{ !empty($r['time']) ? dateTimeFormat($r['time'], 'j M Y H:i') : '-' }}</td>
                                        <td>
                                            @if(!empty($r['url']))
                                                <a href="{{ $r['url'] }}" class="st-activity-link" style="color: #511D99;">Xem</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="6">Chưa có dữ liệu chi tiết.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <h3 class="st-block-title">Timeline hoạt động</h3>
        @if($activities->count() > 0)
            @foreach($activities as $activity)
                @php
                    $type = $activity['type'] ?? 'enrollment';
                    $dotClass = 'st-dot-enrollment';
                    if ($type === 'learning') $dotClass = 'st-dot-learning';
                    elseif ($type === 'quiz') $dotClass = 'st-dot-quiz';
                    elseif ($type === 'assignment') $dotClass = 'st-dot-assignment';
                    elseif ($type === 'test') $dotClass = 'st-dot-test';
                    elseif ($type === 'message') $dotClass = 'st-dot-message';
                @endphp

                <div class="st-activity-item">
                    <span class="st-activity-dot {{ $dotClass }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 6v6l4 2"/>
                            <circle cx="12" cy="12" r="9"/>
                        </svg>
                    </span>

                    <div class="st-activity-main">
                        <p class="st-activity-title">{{ $activity['title'] }}</p>
                        <p class="st-activity-desc">{{ $activity['description'] }}</p>
                        @if(!empty($activity['url']))
                            <a href="{{ $activity['url'] }}" class="st-activity-link mt-4 d-inline-block" style="color: #511D99;">Xem chi tiết</a>
                        @endif
                    </div>

                    <span class="st-activity-time">{{ dateTimeFormat($activity['time'], 'j M Y H:i') }}</span>
                </div>
            @endforeach

            <div class="mt-20">
                {{ $activities->links() }}
            </div>
        @else
            @include('design_1.panel.includes.no-result',[
                'file_name' => 'students.svg',
                'title' => 'Chưa có hoạt động',
                'hint' => 'Học viên chưa có hoạt động nào trong các khóa học của bạn.',
            ])
        @endif
    </div>
</section>
@endsection
