@php
    $task = $question ?? $section ?? null;
    $taskText = $task->question_text ?? $task->instruction ?? $task->content ?? $task->passage_text ?? $section->content ?? $section->passage_text ?? trans('update.ielts_no_task_content');
    $saved = $userAnswer ?? '';
    $minWords = $section->part_number == 1 ? 150 : 250;
@endphp

{{-- Use existing IDP layout structure from parent --}}
<div class="idp-left" id="leftPanel" style="flex: 1; background: #fff; overflow-y: auto; padding: 20px 28px; border-right: 1px solid #ccc;">
    <div style="font-size: 15px; line-height: 1.7; color: #000; font-family: Arial, sans-serif;">
        {!! $taskText !!}
        
        @if(!empty($task->image_url))
            <div style="margin-top: 20px;">
                <img src="{{ $task->image_url }}" alt="Task diagram" style="max-width: 100%; border: 1px solid #ccc;">
            </div>
        @endif
    </div>
</div>

{{-- Resizable Divider --}}
<div class="idp-divider" id="divider" style="width: 18px; background: #e5e5e5; display: flex; align-items: center; justify-content: center; cursor: col-resize; border-left: 1px solid #ccc; border-right: 1px solid #ccc;">
    <span style="color: #888; font-size: 12px;">↔</span>
</div>

{{-- Right panel - Answer area --}}
<div class="idp-right" id="rightPanel" style="flex: 1; background: rgb(249, 249, 249); overflow-y: auto; padding: 20px 28px; display: flex; flex-direction: column;">
    <textarea 
        id="writingAnswer" 
        style="flex: 1; width: 100%; border: 1px solid #ccc; padding: 12px; font-size: 15px; font-family: Arial, sans-serif; line-height: 1.6; resize: none; background: #fff;"
        oninput="updateWordCount(); autoSave({{ $task->id ?? 0 }}, this.value)"
        placeholder="{{ trans('update.ielts_start_writing') }}">{{ $saved }}</textarea>
    
    <div style="text-align: right; padding: 8px 0; font-size: 13px; color: #666;">
        {!! trans('update.ielts_words_count', ['count' => '<span id="wordCount">0</span>']) !!}
    </div>
</div>