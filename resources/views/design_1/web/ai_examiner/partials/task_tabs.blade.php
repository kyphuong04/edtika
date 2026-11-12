{{-- Tab Writing Task 1 / Task 2 ở màn kết quả. Task chưa nộp bài → dẫn về màn làm bài --}}
<div class="aie-task-tabs">
    <div class="aie-task-tabs__wrap">
        @foreach([1, 2] as $tabTask)
            @if($tabTask === $task)
                <span class="is-active">{{ $taskConfig[$tabTask]['short'] }}</span>
            @elseif(!empty($taskLinks[$tabTask]))
                <a href="{{ $taskLinks[$tabTask] }}">{{ $taskConfig[$tabTask]['short'] }}</a>
            @else
                <a href="{{ route('ai_examiner.writing', ['task' => $tabTask]) }}" title="Bạn chưa nộp bài {{ $taskConfig[$tabTask]['short'] }} — bấm để làm bài">{{ $taskConfig[$tabTask]['short'] }}</a>
            @endif
        @endforeach
    </div>
</div>
