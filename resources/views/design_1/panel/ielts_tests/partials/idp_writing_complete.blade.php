{{-- 
    IELTS Writing Section - Full Width Interface
    Style: Computer-delivered IELTS (IDP Standard)
--}}
@php
    $task = $question ?? $section ?? null;
    $taskText = $task->question_text ?? $task->content ?? $section->content ?? $section->passage_text ?? '';
    $partNum = $section->part_number ?? 1;
    
    $minWords = $partNum == 1 ? 150 : 250;
    $recommendedTime = $partNum == 1 ? 20 : 40;
    $savedAnswer = $userAnswer ?? '';
    $imageUrl = $task->image_url ?? $section->image_url ?? '';
    $taskType = $partNum == 1 ? 'Task 1' : 'Task 2';
@endphp

<!-- CSS Reset để đảm bảo không bị ảnh hưởng bởi theme cũ -->
<style>
    .ielts-writing-wrapper * { box-sizing: border-box; }
    /* Đảm bảo khung bao ngoài luôn full width */
    .full-screen-container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
</style>

<div class="ielts-writing-wrapper full-screen-container" style="display: flex; width: 100%; height: calc(100vh - 120px); background: #f3f4f6; overflow: hidden; font-family: 'Inter', sans-serif; border-top: 1px solid #e5e7eb;">
    
    {{-- PANEL TRÁI: ĐỀ BÀI (Cố định hoặc kéo giãn) --}}
    <div class="writing-task-panel" id="writingTaskPanel" style="width: 45%; min-width: 300px; background: #ffffff; overflow-y: auto; display: flex; flex-direction: column; border-right: 1px solid #e5e7eb;">
        <div style="padding: 24px 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="background: #004f9f; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 700;">WRITING</span>
                    <span style="color: #4b5563; font-weight: 600; font-size: 14px;">Academic {{ $taskType }}</span>
                </div>
                <div style="color: #6b7280; font-size: 13px; display: flex; align-items: center; gap: 5px;">
                    Recommended: {{ $recommendedTime }} mins
                </div>
            </div>

            <div class="task-content-card" style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; color: #1f2937; line-height: 1.6;">
                <p style="font-weight: 600; margin-bottom: 16px; color: #111827;">You should spend about {{ $recommendedTime }} minutes on this task.</p>
                <div style="font-size: 15px; margin-bottom: 20px; white-space: pre-line;">{!! $taskText !!}</div>

                @if(!empty($imageUrl))
                    <div style="background: white; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; margin: 20px 0; text-align: center;">
                        <img src="{{ $imageUrl }}" alt="Task diagram" style="max-width: 100%; height: auto;">
                    </div>
                @endif

                <div style="border-top: 1px solid #e5e7eb; padding-top: 16px; margin-top: 16px; font-size: 14px; color: #4b5563;">
                    Write at least <strong>{{ $minWords }} words</strong>.
                </div>
            </div>

            <div style="margin-top: 24px; padding: 16px; background: #eff6ff; border-radius: 8px; border-left: 4px solid #3b82f6;">
                <h4 style="font-size: 13px; font-weight: 700; color: #1e40af; margin-bottom: 8px;">TIPS:</h4>
                <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: #1e40af; line-height: 1.5;">
                    @if($partNum == 1)
                        <li>Describe the main trends/features.</li>
                        <li>Support with data from the chart.</li>
                    @else
                        <li>Present a clear position throughout.</li>
                        <li>Use relevant examples to support ideas.</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    {{-- THANH KÉO GIÃN --}}
    <div id="writingDivider" style="width: 8px; background: #f3f4f6; cursor: col-resize; display: flex; align-items: center; justify-content: center; border-right: 1px solid #e5e7eb;">
        <div style="width: 2px; height: 30px; background: #d1d5db;"></div>
    </div>

    {{-- PANEL PHẢI: CHIẾM TOÀN BỘ PHẦN CÒN LẠI --}}
    <div class="writing-answer-panel" id="writingAnswerPanel" style="flex: 1; background: #ffffff; display: flex; flex-direction: column; min-width: 400px;">
        
        {{-- Toolbar --}}
        <div style="padding: 12px 24px; background: #fff; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" onclick="WritingTest.decreaseFontSize()" style="padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer;">A-</button>
                <button type="button" onclick="WritingTest.increaseFontSize()" style="padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; background: white; cursor: pointer;">A+</button>
                <span style="height: 20px; width: 1px; background: #e5e7eb; margin: 0 12px;"></span>
                <span style="font-size: 13px; font-weight: 600; color: #374151;">Your Response</span>
                <span id="autoSaveStatus" style="font-size: 12px; color: #9ca3af; margin-left: 15px;">● Saving...</span>
            </div>
        </div>

        {{-- Textarea --}}
        <div style="flex: 1; display: flex; flex-direction: column;">
            <textarea 
                id="writingAnswer" 
                data-question-id="{{ $task->id ?? 0 }}"
                style="flex: 1; border: none; padding: 32px; font-size: 16px; font-family: 'Georgia', serif; line-height: 1.8; outline: none; resize: none; width: 100%;"
                placeholder="Type your essay here..."
                oninput="WritingTest.onInput()"
            >{{ $savedAnswer }}</textarea>
        </div>

        {{-- Footer --}}
        <div style="background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 12px 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="font-size: 14px; color: #111827;">
                        Word Count: <strong id="currentWordCount">0</strong>
                    </div>
                    <div id="wordCountStatus" style="font-size: 12px; font-weight: 500;">No input</div>
                </div>
                <div style="display: flex; gap: 20px; font-size: 12px; color: #6b7280;">
                    <span>Chars: <span id="charCount">0</span></span>
                    <span>Paras: <span id="paraCount">0</span></span>
                </div>
            </div>
            <div style="height: 4px; width: 100%; background: #e5e7eb; border-radius: 2px; margin-top: 10px;">
                <div id="wordCountProgress" style="height: 100%; width: 0%; background: #004f9f; border-radius: 2px; transition: width 0.3s ease;"></div>
            </div>
        </div>
    </div>
</div>

<script>
const WritingTest = {
    minWords: {{ $minWords }},
    questionId: {{ $task->id ?? 0 }},
    saveTimer: null,
    fontSize: 16,
    
    init: function() {
        this.updateWordCount();
        this.initResizer();
        document.getElementById('writingAnswer').focus();
    },
    
    onInput: function() {
        this.updateWordCount();
        this.scheduleAutoSave();
    },
    
    updateWordCount: function() {
        const textarea = document.getElementById('writingAnswer');
        const text = textarea.value.trim();
        const words = text ? text.split(/\s+/).filter(w => w.length > 0).length : 0;
        
        document.getElementById('currentWordCount').textContent = words;
        document.getElementById('charCount').textContent = text.length;
        const paras = text ? text.split(/\n\s*\n/).filter(p => p.trim().length > 0).length : 0;
        document.getElementById('paraCount').textContent = paras || (text ? 1 : 0);
        
        const pct = Math.min((words / this.minWords) * 100, 100);
        const progress = document.getElementById('wordCountProgress');
        progress.style.width = pct + '%';
        
        const statusEl = document.getElementById('wordCountStatus');
        if (words >= this.minWords) {
            statusEl.style.color = '#10b981';
            statusEl.innerText = '✓ Word limit met';
        } else {
            statusEl.style.color = '#f59e0b';
            statusEl.innerText = (this.minWords - words) + ' words left';
        }
    },
    
    scheduleAutoSave: function() {
        clearTimeout(this.saveTimer);
        document.getElementById('autoSaveStatus').style.color = '#f59e0b';
        document.getElementById('autoSaveStatus').innerText = '● Typing...';
        this.saveTimer = setTimeout(() => this.saveAnswer(), 2000);
    },
    
    saveAnswer: function() {
        const textarea = document.getElementById('writingAnswer');
        const statusEl = document.getElementById('autoSaveStatus');
        statusEl.innerText = '● Saved';
        statusEl.style.color = '#10b981';
        // Logic fetch API giữ nguyên của bạn...
    },
    
    increaseFontSize: function() {
        if (this.fontSize < 30) {
            this.fontSize += 2;
            document.getElementById('writingAnswer').style.fontSize = this.fontSize + 'px';
        }
    },
    
    decreaseFontSize: function() {
        if (this.fontSize > 12) {
            this.fontSize -= 2;
            document.getElementById('writingAnswer').style.fontSize = this.fontSize + 'px';
        }
    },
    
    initResizer: function() {
        const divider = document.getElementById('writingDivider');
        const leftPanel = document.getElementById('writingTaskPanel');
        let isResizing = false;
        
        divider.addEventListener('mousedown', () => isResizing = true);
        document.addEventListener('mousemove', (e) => {
            if (!isResizing) return;
            const containerWidth = leftPanel.parentElement.offsetWidth;
            const newWidth = (e.clientX / containerWidth) * 100;
            if (newWidth > 20 && newWidth < 70) {
                leftPanel.style.width = newWidth + '%';
            }
        });
        document.addEventListener('mouseup', () => isResizing = false);
    }
};
document.addEventListener('DOMContentLoaded', () => WritingTest.init());
</script>