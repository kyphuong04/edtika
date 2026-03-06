{{--
    IELTS Writing Section - Wireframe-matched design
    Left: WRITING panel (task content + image)
    Right: YOUR WRITING panel (textarea + word count)
--}}
@php
    $task         = $question ?? $section ?? null;
    $taskText     = $task->question_text ?? $task->content ?? $section->content ?? $section->passage_text ?? '';
    $partNum      = $section->part_number ?? 1;
    $minWords     = $partNum == 1 ? 150 : 250;
    $recommendedTime = $partNum == 1 ? 20 : 40;
    $savedAnswer  = $userAnswer ?? '';
    $questionNum  = $task->question_number ?? 1;
    // Resolve task image from IeltsQuestionGroup.
    // Questions created via admin do NOT have question_group_id set, so we
    // fall back to looking up the group by section_id directly.
    $imageUrl = null;
    $rawTaskImg = null;
    $taskGroup = ($task instanceof \App\Models\IeltsTestQuestion) ? $task->questionGroup : null;
    if (!$taskGroup && $section) {
        $taskGroup = \App\Models\IeltsQuestionGroup::where('section_id', $section->id)->first();
    }
    $rawTaskImg = $taskGroup?->task_image ?? null;
    if ($rawTaskImg) {
        $imageUrl = (str_starts_with($rawTaskImg, 'http') || str_starts_with($rawTaskImg, '/'))
            ? $rawTaskImg
            : \Storage::disk('public')->url($rawTaskImg);
    } else {
        $imageUrl = $task?->image_url ?? $section?->image_url ?? null;
    }
@endphp

<style>
/* ── WRITING WIREFRAME ───────────────────────── */
.wf-wr-container {
    display: flex;
    width: 100%; height: 100%;
    background: #e5e5e5;
    gap: 0;
    overflow: hidden;
}

/* Panels */
.wf-wr-panel {
    display: flex;
    flex-direction: column;
    background: #fff;
    overflow: hidden;
}
.wf-wr-left  { flex: 0 0 45%; min-width: 280px; }
.wf-wr-right { flex: 1; min-width: 320px; }

/* Grey panel header */
.wf-wr-panel-header {
    flex-shrink: 0;
    background: #d8d8d8;
    padding: 14px 24px;
    font-size: 22px;
    font-weight: 900;
    color: #111;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-bottom: 1px solid #ccc;
}

/* Panel body */
.wf-wr-panel-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px;
}
.wf-wr-panel-body::-webkit-scrollbar { width: 5px; }
.wf-wr-panel-body::-webkit-scrollbar-track { background: #f0f0f0; }
.wf-wr-panel-body::-webkit-scrollbar-thumb { background: #bbb; border-radius: 3px; }

/* ── LEFT: task content ─ */
.wf-wr-q-label {
    font-size: 14px; color: #333; margin-bottom: 10px; line-height: 1.5;
}
.wf-wr-q-bold { font-weight: 700; margin-bottom: 6px; font-size: 15px; line-height: 1.5; }
.wf-wr-q-text { font-size: 15px; line-height: 1.6; margin-bottom: 10px; color: #111; }
.wf-wr-q-text p { margin-bottom: 8px; }
.wf-wr-word-min {
    font-size: 14px; color: #333; margin-top: 12px; margin-bottom: 0;
}
/* Image placeholder / actual image */
.wf-wr-img-box {
    background: #f0f0f0;
    border: 1.5px solid #ccc;
    border-radius: 8px;
    margin: 16px 0;
    overflow: hidden;
    text-align: center;
}
.wf-wr-img-box img { max-width: 100%; display: block; }
.wf-wr-img-placeholder {
    width: 100%; min-height: 200px;
    background: #e8e8e8;
    display: flex; align-items: center; justify-content: center;
    color: #bbb; font-size: 13px;
}

/* ── DIVIDER ─────────────────────────────────── */
.wf-wr-divider {
    width: 7px;
    background: #e5e5e5;
    cursor: col-resize;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.wf-wr-divider-thumb {
    width: 2px; height: 36px;
    background: #bbb; border-radius: 2px;
}

/* ── RIGHT: textarea ─────────────────────────── */
.wf-wr-right-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.wf-wr-textarea {
    flex: 1;
    width: 100%;
    border: none;
    border-bottom: 1px solid #e5e7eb;
    padding: 20px 24px;
    font-size: 15px;
    font-family: Arial, sans-serif;
    line-height: 1.8;
    resize: none;
    outline: none;
    color: #111;
    background: #fff;
    overflow-y: auto;
}
.wf-wr-textarea::placeholder { color: #bbb; }
.wf-wr-textarea::-webkit-scrollbar { width: 5px; }
.wf-wr-textarea::-webkit-scrollbar-track { background: #f0f0f0; }
.wf-wr-textarea::-webkit-scrollbar-thumb { background: #bbb; border-radius: 3px; }

.wf-wr-bottom-bar {
    flex-shrink: 0;
    padding: 10px 24px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.wf-wr-wordcount-pill {
    display: inline-flex; align-items: center;
    background: #f3f4f6; border: 1.5px solid #e5e7eb;
    border-radius: 20px; padding: 5px 16px;
    font-size: 14px; color: #374151; font-weight: 600;
}
.wf-wr-wordcount-pill #wfWordCount { color: #111; }
.wf-wr-autosave { font-size: 12px; color: #9ca3af; }
.wf-wr-font-btns { display: flex; gap: 4px; }
.wf-wr-font-btn {
    padding: 4px 10px;
    border: 1.5px solid #d1d5db;
    border-radius: 5px;
    background: #fff;
    cursor: pointer;
    font-size: 13px;
    color: #374151;
    font-weight: 600;
    transition: background .15s;
}
.wf-wr-font-btn:hover { background: #f3f4f6; }
</style>

<div class="wf-wr-container">

    {{-- LEFT PANEL: WRITING ─────────────────── --}}
    <div class="wf-wr-panel wf-wr-left" id="writingTaskPanel">
        <div class="wf-wr-panel-header">WRITING</div>
        <div class="wf-wr-panel-body">

            <p class="wf-wr-q-label">
                Question {{ $questionNum }}: You should spend about <strong>{{ $recommendedTime }} minutes</strong> on this task.
            </p>

            @php
                $isHtmlTask = $taskText !== strip_tags($taskText);
            @endphp

            @if($isHtmlTask)
                {{-- HTML-formatted task text: render as-is --}}
                <div class="wf-wr-q-text">{!! $taskText !!}</div>
            @else
                @php
                    // Plain text: split into bold intro + body on double blank line
                    $taskParts = preg_split('/\r?\n\r?\n/', trim($taskText), 2);
                    $boldLine  = $taskParts[0] ?? '';
                    $bodyText  = $taskParts[1] ?? '';
                @endphp
                @if($boldLine)
                    <p class="wf-wr-q-bold">{!! nl2br(e($boldLine)) !!}</p>
                @endif
                @if($bodyText)
                    <div class="wf-wr-q-text">{!! nl2br(e($bodyText)) !!}</div>
                @endif
            @endif

            <p class="wf-wr-word-min">Write at least <strong>{{ $minWords }} words</strong>.</p>

            {{-- Image: actual image or placeholder if imageUrl stored on question --}}
            @if(!empty($imageUrl))
                <div class="wf-wr-img-box">
                    <img src="{{ $imageUrl }}" alt="Task diagram">
                </div>
            @elseif($task && ($task->has_image ?? false))
                <div class="wf-wr-img-box">
                    <div class="wf-wr-img-placeholder">[Image not available]</div>
                </div>
            @endif

        </div>
    </div>

    {{-- DRAG DIVIDER ─────────────────────────── --}}
    <div class="wf-wr-divider" id="writingDivider">
        <div class="wf-wr-divider-thumb"></div>
    </div>

    {{-- RIGHT PANEL: YOUR WRITING ─────────────── --}}
    <div class="wf-wr-panel wf-wr-right" id="writingAnswerPanel">
        <div class="wf-wr-panel-header">YOUR WRITING</div>
        <div class="wf-wr-right-body">

            <textarea
                id="writingAnswer"
                class="wf-wr-textarea"
                data-question-id="{{ $task->id ?? 0 }}"
                placeholder="Write your essay here..."
                oninput="WfWriting.onInput()"
            >{{ $savedAnswer }}</textarea>

            <div class="wf-wr-bottom-bar">
                <div class="wf-wr-wordcount-pill">
                    Word count: <span id="wfWordCount">0</span>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <span id="wfAutoSave" class="wf-wr-autosave">&bull; Saving...</span>
                    <div class="wf-wr-font-btns">
                        <button type="button" class="wf-wr-font-btn" onclick="WfWriting.decreaseFontSize()">A&minus;</button>
                        <button type="button" class="wf-wr-font-btn" onclick="WfWriting.increaseFontSize()">A+</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>{{-- end container --}}

<script>
const WfWriting = {
    minWords:   {{ $minWords }},
    questionId: {{ $task->id ?? 0 }},
    fontSize:   15,
    saveTimer:  null,

    init: function () {
        this.updateWordCount();
        this.initResizer();
        const ta = document.getElementById('writingAnswer');
        if (ta) ta.focus();
    },

    onInput: function () {
        this.updateWordCount();
        this.scheduleAutoSave();
    },

    updateWordCount: function () {
        const ta    = document.getElementById('writingAnswer');
        if (!ta) return;
        const text  = ta.value.trim();
        const words = text ? text.split(/\s+/).filter(w => w.length > 0).length : 0;
        const el    = document.getElementById('wfWordCount');
        if (el) el.textContent = words;
    },

    scheduleAutoSave: function () {
        clearTimeout(this.saveTimer);
        const sp = document.getElementById('wfAutoSave');
        if (sp) { sp.textContent = '\u2022 Typing...'; sp.style.color = '#f59e0b'; }
        this.saveTimer = setTimeout(() => this.saveAnswer(), 2000);
    },

    saveAnswer: function () {
        const ta  = document.getElementById('writingAnswer');
        const sp  = document.getElementById('wfAutoSave');
        if (!ta) return;
        const csrf    = document.querySelector('meta[name="csrf-token"]')?.content || '';
        // saveUrl is defined in the outer template's <script> block (same page scope)
        const url = (typeof saveUrl !== 'undefined' && saveUrl) ? saveUrl : null;
        if (!url) {
            if (sp) { sp.textContent = '\u2022 Saved'; sp.style.color = '#10b981'; }
            return;
        }
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ question_id: this.questionId, answer_text: ta.value })
        }).then(r => {
            if (sp) { sp.textContent = r.ok ? '\u2022 Saved' : '\u2022 Error'; sp.style.color = r.ok ? '#10b981' : '#ef4444'; }
        }).catch(() => {
            if (sp) { sp.textContent = '\u2022 Error'; sp.style.color = '#ef4444'; }
        });
    },

    increaseFontSize: function () {
        if (this.fontSize < 28) { this.fontSize += 2; this.applyFont(); }
    },
    decreaseFontSize: function () {
        if (this.fontSize > 12) { this.fontSize -= 2; this.applyFont(); }
    },
    applyFont: function () {
        const ta = document.getElementById('writingAnswer');
        if (ta) ta.style.fontSize = this.fontSize + 'px';
    },

    initResizer: function () {
        const div   = document.getElementById('writingDivider');
        const left  = document.getElementById('writingTaskPanel');
        if (!div || !left) return;
        let drag = false;
        div.addEventListener('mousedown',  () => { drag = true;  document.body.style.cursor = 'col-resize'; });
        document.addEventListener('mouseup',    () => { drag = false; document.body.style.cursor = ''; });
        document.addEventListener('mousemove', e => {
            if (!drag) return;
            const container = left.parentElement;
            const rect      = container.getBoundingClientRect();
            const pct       = ((e.clientX - rect.left) / rect.width) * 100;
            if (pct > 20 && pct < 75) {
                left.style.flex = '0 0 ' + pct + '%';
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => WfWriting.init());
</script>